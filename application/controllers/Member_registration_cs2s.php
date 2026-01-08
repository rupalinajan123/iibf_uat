<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Member_registration_cs2s extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');	
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->model('master_model');		
		$this->load->library('email');
		$this->load->model('Emailsending');
		$this->load->model('log_model');
		$this->load->model('Ampmodel');
		$this->load->helper('blended_invoice_helper');
		$this->load->helper('date');
		$this->load->helper('gstrecovery_invoice_helper');
	}
	public function member_regn_log($log_title, $log_message = "", $rId = NULL, $regNo = NULL)
	{
		$obj = new OS_BR();
		$browser_details=implode('|',$obj->showInfo('all'));
		$data['title'] = $log_title;
		$data['description'] = $log_message;
		$data['regid'] = $rId;
		$data['regnumber'] = $regNo;
		$data['ip'] = $this->input->ip_address();
		$data['browser'] = $browser_details;
		$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$this->db->insert('userlogs_member_cs2s', $data);
	}
	public function sbicallback()
	{
		$log_title ="sbicallback Function call Member registration Cron";
		$log_message = 'sbicallback Function call Member registration Cron';
		$rId = '';
		$regNo = '';
		$this->member_regn_log($log_title, $log_message, $rId, $regNo);
		
		$filehandle = fopen("cs2s_log/member_registration/lock.txt", "c+");
		if (flock($filehandle, LOCK_EX | LOCK_NB)) {
			// code here to start the cron job
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$this->load->library('excel');
			$key = $this->config->item('sbi_m_key');
			
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			
			$query='SELECT receipt_no FROM payment_transaction WHERE date <= NOW() - INTERVAL 20 MINUTE 
			AND date > NOW() - INTERVAL 40 MINUTE AND gateway = "sbiepay" AND status = 2 AND pay_type = 1';
			$crnt_day_txn_qry = $this->db->query($query);
			echo "*********************************** New Cron Request Started***************************\n";
			echo  "Total Count =>". $crnt_day_txn_qry->num_rows();
			//echo $this->db->last_query();exit;
			if ($crnt_day_txn_qry->num_rows())
			{
				$start_time = date("Y-m-d H:i:s");
				$succ_cnt = $fail_cnt = $no_resp_cnt = $pending_cnt = 0;
				$succ_recp_arr = $fail_recp_arr = $no_resp_recp_arr = $pending_recp_arr = array();
				$todays_date = date("d-m-Y");
				$dir = 'cs2s_log/member_registration/'.$todays_date;
				if(!is_dir($dir)){
					mkdir($dir, 0755);
				}
				$cell = 1;
				
				$objPHPExcel = new PHPExcel();
				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$cell, "Receipt No")
												->setCellValue('B'.$cell, "Transaction Status")
												->setCellValue('C'.$cell, "Transaction Data")
												->setCellValue('D'.$cell, "Response Data")
												->setCellValue('E'.$cell, "Response Date");
													
				foreach ($crnt_day_txn_qry->result_array() as $c_row)
				{
					$cell++;
					//sleep(1);
					$responsedata = sbiqueryapi($c_row['receipt_no']);
					$receipt_no=$c_row['receipt_no'];
					$encData=implode('|',$responsedata);
					$resp_data = json_encode($responsedata);
					if(empty($responsedata) || $responsedata == 0 || $responsedata == "")
					{
						$responsedata = sbiqueryapi($c_row['receipt_no']);	
					}else if(empty($responsedata) || $responsedata == 0 || $responsedata == "")
					{
						$responsedata = sbiqueryapi($c_row['receipt_no']);	
					}
				
					## Check payment_c_s2s_log entry 
					$data_count = $this->master_model->getRecordCount('payment_transaction',array('receipt_no'=>$receipt_no,'status'=>1));
					if($data_count == 0)
					{
						## Add log file
						$fp = @fopen($dir."/logs_".date("dmY").".txt", "a") or die("Unable to open file!");
						echo $str = "\n $receipt_no=$encData\n";
						fwrite($fp, $str);
						fclose($fp);
						
						## Excel file
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$cell,$c_row['receipt_no'])
													->setCellValue('B'.$cell, $responsedata[2])
													->setCellValue('C'.$cell, $encData.'&CALLBACK=C_S2S')
													->setCellValue('D'.$cell, $resp_data)
													->setCellValue('E'.$cell, date('Y-m-d H:i:s'));
						// Save Excel xls File
						$filename="log_excel.xls";
						$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
						$objWriter->save(str_replace(__FILE__,$dir.'/'.$filename,__FILE__));
						## Update counts
						if($responsedata[2] == 'SUCCESS')
						{
							$succ_cnt++;
							array_push($succ_recp_arr,$receipt_no);
						}else if($responsedata[2] == 'FAIL' || $responsedata[2] == 'ABORT')
						{
							$fail_cnt++;
							array_push($fail_recp_arr,$receipt_no);
							$update_data = array('status' => 0,'callback'=>'c_S2S','transaction_details'=>$responsedata[2]);
							$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$receipt_no,'status'=>2));
						}else if($responsedata[2] == 'PENDING')
						{
							$pending_cnt++;
							array_push($pending_recp_arr,$receipt_no);
						}
						else{
							$no_resp_cnt++;
							array_push($no_resp_recp_arr,$receipt_no);
						}
						
					}
					$resp_array = array('receipt_no'	=> $c_row['receipt_no'],
										'txn_status' 	=> $responsedata[2],
										'txn_data' 		=> $encData.'&CALLBACK=C_S2S',
										'response_data' => $resp_data,
										'remark' 		=> '',
										'resp_date' 	=> date('Y-m-d H:i:s'),
										);
					$this->master_model->insertRecord('payment_c_s2s_log', $resp_array);
					if (isset($responsedata) && count($responsedata) > 0)
					{
						$this->load->model('log_model');
						include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
						$key = $this->config->item('sbi_m_key');
						
						$aes = new CryptAES();
						$aes->set_key(base64_decode($key));
						$aes->require_pkcs5();
						if (isset($responsedata[0]))
						{
							$merchIdVal = $responsedata[0];
						}
						if (isset($responsedata[9]))
						{
							$Bank_Code = $responsedata[9];
						}
						$responsedata[16]=$responsedata[12];
						//print_r($responsedata);
						$cust=explode('^',$responsedata[5]);
						$responsedata[12]=$cust['1'];
						
						// Registration
						## Code commented on 10-Mar-2021 - chaitali's request
						if($responsedata[12]=='iibfregn')
						{
							if($cust['2']!='iibfregn')	// Not New Member registration
							{
								$MerchantOrderNo = $responsedata[6];  
								$get_pg_flag=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'pg_flag');
								$responsedata[12]=$get_pg_flag[0]['pg_flag'];
								
								//$responsedata[12]=$cust['2'];
							}
						}
						
						if ($responsedata[12] == "iibfregn")
						{
							//sleep(8);
							$MerchantOrderNo = $responsedata[6];  
							$transaction_no  = $responsedata[1];
							
							$payment_status = 2;
							
							switch ($responsedata[2])
							{
								case "SUCCESS":
									$payment_status = 1;
									break;
								case "FAIL":
									$payment_status = 0;
									break;
								case "PENDING":
									$payment_status = 2;
									break;
							}
							if($payment_status==1)
							{
								$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status,id');
								//check user payment status is updated by S2S or not
								if($get_user_regnum_info[0]['status']==2)
								{
									$reg_id=$get_user_regnum_info[0]['ref_id'];
									//$applicationNo = generate_mem_reg_num();
									$applicationNo =generate_O_memreg($reg_id);

									$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S','member_regnumber' => $applicationNo);
									$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
									
									## Add query log
									$log_title ="Payment status update in payment transaction for member registration Cron1 C_S2S :".$reg_id;
									$log_message = $this->db->last_query();
									$rId = $MerchantOrderNo;
									$regNo = $transaction_no;
									//storedUserActivity($log_title, $log_message, $rId, $regNo);
									$this->member_regn_log($log_title, $log_message, $rId, $regNo);
												
									if(!empty($applicationNo))
									{		
										if(count($get_user_regnum_info) > 0)
										{
											$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
											$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
											
											$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,mobile');
											########get Old image Name############
											$log_title ="Ordinory member OLD Image Cron 1 C_S2S :".$reg_id;
											$log_message = serialize($user_info);
											$rId = $reg_id;
											$regNo = $reg_id;
											//storedUserActivity($log_title, $log_message, $rId, $regNo);
											$this->member_regn_log($log_title, $log_message, $rId, $regNo);
									
											
											$upd_files = array();
											$photo_file = 'p_'.$applicationNo.'.jpg';
											$sign_file = 's_'.$applicationNo.'.jpg';
											$proof_file = 'pr_'.$applicationNo.'.jpg';
											
											if(@ rename("./uploads/photograph/".$user_info[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
											{	$upd_files['scannedphoto'] = $photo_file;	}
											
											if(@ rename("./uploads/scansignature/".$user_info[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
											{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
											
											if(@ rename("./uploads/idproof/".$user_info[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
											{	$upd_files['idproofphoto'] = $proof_file;	}
											
											if(count($upd_files)>0)
											{
												$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
												$log_title ="Ordinory member PIC update Cron1 C_S2S :".$reg_id;
												$log_message = serialize($upd_files);
												$rId = $reg_id;
												$regNo = $reg_id;
												//storedUserActivity($log_title, $log_message, $rId, $regNo);
												$this->member_regn_log($log_title, $log_message, $rId, $regNo);
											}
											else
											{
												$upd_files['scannedphoto'] = $photo_file;
												$upd_files['scannedsignaturephoto'] = $sign_file;	
												$upd_files['idproofphoto'] = $proof_file;
												$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
												$log_title ="Member MANUAL PICS Update Cron1 C_S2S :".$reg_id;
												$log_message = serialize($upd_files);
												$rId = $reg_id;
												$regNo = $reg_id;
												//storedUserActivity($log_title, $log_message, $rId, $regNo);	
												$this->member_regn_log($log_title, $log_message, $rId, $regNo);
											}
									}
										 //email to user
										 $emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_register_email'));
										 if(count($emailerstr) > 0)
										{
										include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
										$key = $this->config->item('pass_key');
										$aes = new CryptAES();
										$aes->set_key(base64_decode($key));
										$aes->require_pkcs5();
										//$encPass = $aes->encrypt(trim($user_info[0]['usrpassword']));
										$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
										//$decpass = $aes->decrypt($user_info[0]['usrpassword']);
										$newstring = str_replace("#application_num#", "".$applicationNo."",  $emailerstr[0]['emailer_text']);
										$final_str= str_replace("#password#", "".$decpass."",  $newstring);
										$info_arr=array('to'=>$user_info[0]['email'],
																  'from'=>$emailerstr[0]['from'],
																  'subject'=>$emailerstr[0]['subject'].' '.$applicationNo,
																  'message'=>$final_str
																);
										
							//set invoice
							$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum_info[0]['id']));
							//echo $this->db->last_query();exit;
							if(count($getinvoice_number) > 0)
							{
								
									$invoiceNumber = generate_registration_invoice_number($getinvoice_number[0]['invoice_id']);
									if($invoiceNumber)
									{
										$invoiceNumber=$this->config->item('mem_invoice_no_prefix').$invoiceNumber;
							    	}
								
								
								$update_data = array('invoice_no' => $invoiceNumber,'member_no'=>$applicationNo,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
								$this->db->where('pay_txn_id',$get_user_regnum_info[0]['id']);
								$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
								$attachpath=genarate_reg_invoice($getinvoice_number[0]['invoice_id']);
								
								$log_title ="Ordinory member invoice update C_S2S Cron1 Invoice number:".$invoiceNumber;
								$log_message = serialize($update_data);
								$rId = $invoiceNumber;
								$regNo = $invoiceNumber;
								$this->member_regn_log($log_title, $log_message, $rId, $regNo);
												
							}	
										if($attachpath!='')
										{	
										
											$sms_newstring = str_replace("#application_num#", "".$applicationNo."",  $emailerstr[0]['sms_text']);
											$sms_final_str= str_replace("#password#", "".$decpass."",  $sms_newstring);
											// $this->master_model->send_sms($user_info[0]['mobile'],$sms_final_str);
											$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']),$sms_final_str,'DPDoOIwMR');

																			
											//if($this->Emailsending->mailsend($info_arr))
											if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
											{
												
											//$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
												//redirect(base_url('register/acknowledge/'));
											}
													
										}
													
									}
									}
								}
							}	
							else if($payment_status==0)
							{
								$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status');
								if($get_user_regnum_info[0]['status']==2)
								{
									$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2],'auth_code' => '0399', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');
									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								}
							}
						} 
							// add payment responce in log
							if($responsedata[12] == "iibfregn")
							{
								$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=C_S2S";
								$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
							}
					}
					else
					{
						echo "Please try again...";
					}
			}//foreach
				
					$succ_recp = implode(",",$succ_recp_arr);
					$fail_recp = implode(",",$fail_recp_arr);
					$no_resp_recp = implode(",",$no_resp_recp_arr);
					$pending_recp = implode(",",$pending_recp_arr);
					$end_time = date("Y-m-d H:i:s");
					## Counts files
					$fp = @fopen($dir."/detail_logs_new_data_".date("dmY").".txt", "a") or die("Unable to open file!");
					echo $str = "\n***********************************************************\n\n Cron execution started at :$start_time \n\n Total Count =>". $crnt_day_txn_qry->num_rows()."\n\nTotal records SUCCESS: $succ_cnt\n($succ_recp) \nTotal records FAIL: $fail_cnt\n($fail_recp) \n Total records PENDING: $pending_cnt\n($pending_recp)\n Total records No Response: $no_resp_cnt\n($no_resp_recp)\n Cron execution ended at: $end_time\n";
					fwrite($fp, $str);
					fclose($fp);
					
					## Total Counts files
					$fp = @fopen($dir."/log_counts_".date("dmY").".txt", "a") or die("Unable to open file!");
					echo $str = "\n***********************************************************\n\n Cron execution started at :$start_time \n\n Total Count =>". $crnt_day_txn_qry->num_rows()."\n\nTotal records SUCCESS: $succ_cnt \nTotal records FAIL: $fail_cnt \n Total records PENDING: $pending_cnt\n Total records No Response: $no_resp_cnt\n Cron execution ended at: $end_time\n";
					fwrite($fp, $str);
					fclose($fp);
				}
				
			   flock($filehandle, LOCK_UN);  // don't forget to release the lock
			} else {
				// throw an exception here to stop the next cron job
				echo "Cron1 is already running";
			}
			fclose($filehandle);
		
	}
		
}