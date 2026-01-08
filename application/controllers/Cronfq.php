<?php
/*
// Name:- Chaitali jadhav
// Date:- 2021-05-27
// purpose :- Double varification cron for FinQuest Module Daily Cron.
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronfq extends CI_Controller 
{
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
		$this->load->helper('renewal_invoice_helper');
		$this->load->helper('date');
		$this->load->helper('finquest_invoice_helper');
	}
	public function sbicallback()	
	{
		$filehandle = fopen("cs2s_log/cronfq/lock.txt", "c+");
		if (flock($filehandle, LOCK_EX | LOCK_NB)) 
		{
			// code here to start the cron job		
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$this->load->library('excel');
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			//echo $query='SELECT receipt_no FROM payment_transaction WHERE receipt_no = "902715061" AND gateway = "sbiepay" AND status = 2 AND pay_type="11"';
			
			 $query='SELECT receipt_no FROM payment_transaction WHERE date <= NOW() - INTERVAL 20 MINUTE 
			AND date > NOW() - INTERVAL 40 MINUTE AND gateway = "sbiepay" AND status = 2 AND pay_type="8"'; 
			
			$crnt_day_txn_qry = $this->db->query($query);
			echo "*********************************** New Cron Request Started***************************\n";
			echo  "Total Count =>". $crnt_day_txn_qry->num_rows();
			if ($crnt_day_txn_qry->num_rows())		
			{
				$start_time = date("Y-m-d H:i:s");
				$succ_cnt = $fail_cnt = $no_resp_cnt = $pending_cnt = 0;
				$succ_recp_arr = $fail_recp_arr = $no_resp_recp_arr = $pending_recp_arr = array();
				$todays_date = date("d-m-Y");
				$dir = 'cs2s_log/cronfq/'.$todays_date;
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
						$cust=explode('^',$responsedata[5]);
						$responsedata[12]=$cust['2'];
						if($responsedata[12] == "iibffq")			
						{
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
							if($payment_status == 1)
							{
								$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status,id,member_regnumber');
								//echo "<br/>".$this->db->last_query();
								if ($get_user_regnum_info[0]['status'] == 2) 
								{
									$reg_id = $get_user_regnum_info[0]['ref_id'];
									$applicationNo = $get_user_regnum_info[0]['member_regnumber'];
									$last_id = $get_user_regnum_info[0]['ref_id'];
									
									
									// User Entered Number							
									$update_data = array('member_regnumber' => $applicationNo, 'transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $responsedata[2] . " - " . $responsedata[8], 'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16], 'callback' => 'C_S2S');
									$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
									//echo '<br/>'.$this->db->last_query();
									
									 // genarate subscription number
									$session_id          = $last_id;
									$subscription_number = genarate_finquest_subscription_number($session_id, 'F'); 
									
									 $update_bank_data = array(
											'pay_status' => 1,
											'modified_on' => date("Y-m-d H:i:s"),
											'subscription_from_date' => date('Y-m-d'),
											'subscription_to_date' => date('Y-m-d', strtotime('+1 years')),
											'subscription_no' => $subscription_number
										);
										
										$this->master_model->updateRecord('fin_quest', $update_bank_data, array(
											'id' => $last_id
										));
										
										//get basic details of member_regnumber
										$member_details = $this->master_model->getRecords('fin_quest', array('id' => $last_id));
										$email = $member_details[0]['email_id'];
										 // email to user
											$start_date         = date('d/m/Y');
											$end_date           = date('d/m/Y', strtotime('+1 years'));
											$subscription_range = $start_date . " to " . $end_date;
											$emailerstr         = $this->master_model->getRecords('emailer', array(
												'emailer_name' => 'finquest'
											));
											$newstring1         = str_replace("#NO#", "" . $subscription_number . "", $emailerstr[0]['emailer_text']);
											$final_str          = str_replace("#DATE#", "" . $subscription_range . "", $newstring1);
											$info_arr           = array(
												'to' => $email,
												//'to'=>'kyciibf@gmail.com',
												'from' => $emailerstr[0]['from'],
												'subject' => $emailerstr[0]['subject'],
												'message' => $final_str
											);
											
											//to client	
											
											// genarate invoice
											$getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
												'receipt_no' => $MerchantOrderNo,
												'pay_txn_id' => $get_user_regnum_info[0]['id']
											));
											
											 if (count($getinvoice_number) > 0) 
											 {
                            
												$invoiceNumber = generate_finquest_invoice_number($getinvoice_number[0]['invoice_id']);
												if ($invoiceNumber) {
													$invoiceNumber = $this->config->item('FinQuest_invoice_no_prefix') . $invoiceNumber;
												}
												
												$update_data = array(
													'invoice_no' => $invoiceNumber,
													'transaction_no' => $transaction_no,
													'date_of_invoice' => date('Y-m-d H:i:s'),
													'modified_on' => date('Y-m-d H:i:s')
												);
												$this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
												$this->master_model->updateRecord('exam_invoice', $update_data, array(
													'receipt_no' => $MerchantOrderNo
												));
												
												$attachpath = genarate_finquest_invoice($getinvoice_number[0]['invoice_id'], $session_id);
												
											}
											 if ($attachpath != '') 
											 {
											if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) 
											{
													
										#------------send mail to chetan & ruchi-----------#
										$message="Hello Ruchi,<br><br>
										
										Please add following member to fin@quest mailing list.<br>
										
										Member details :<br>
										Email id:  
										".$email.'<br><br>
										
														-- <br>
												 REGARDS,<br>
												
												CHAITALI JADHAV | jr. Software Engineer<br>
												
												ESDS Software Solution Pvt. Ltd .<br>
												Website : WWW.ESDS.CO.IN | Email: CHAITALI.JADHAV@ESDS.CO.IN<br>
												Address : Plot No. B- 24 & 25, NICE Industrial Area, Satpur MIDC, <br>   
												Nashik 422 007<br>
												Mobile    : +91 7744883663 | Toll Free: 1800 209 3006 | Landline: +91 (0253) 663 6500<br>
												"_We are committed to creating Lifetime Customer Relationships by<br>
												delivering World Class Managed Data Center Services and Cloud enabled
												Solutions._<br>"

														';
									
										$info_arr_c  = array(
										'to'=>'ruchi.demsepatil@esds.co.in,chetan.thakare@esds.co.in',
										'from' => 'CHAITALI.JADHAV@ESDS.CO.IN',
										'subject' => 'Add member to finquest subscription list.',
										'message' => $message
										);
										
										$attachpath_c='';	
												   
									 
										#------------end send mail to chetan-----------#	
										
													
													
												} 
											} 
											
																
																
									###################################
								}	
							}//If payment
							else if($payment_status==0)					
							{
								$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status');
							    if($get_user_regnum_info[0]['status']==2)
								{
									$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								}
											
							}
							
						}//if
						
					}
					else
					{
						echo "Please try again...";
					}
				}	
			}
			flock($filehandle, LOCK_UN);
		}//If file	
		else 
		{
			// throw an exception here to stop the next cron job
			echo "Cronfq is already running";
			$start_time = date("Y-m-d H:i:s");
				$succ_cnt = $fail_cnt = $no_resp_cnt = $pending_cnt = 0;
				$succ_recp_arr = $fail_recp_arr = $no_resp_recp_arr = $pending_recp_arr = array();
				$todays_date = date("d-m-Y");
				$dir = 'cs2s_log/cronfq/'.$todays_date;
		}
	}
			
}