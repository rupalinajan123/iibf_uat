<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronamp extends CI_Controller {
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
	}
	public function sbicallback()
	{
	die;
		$filehandle = fopen("cs2s_log/cronamp/lock.txt", "c+");
		if (flock($filehandle, LOCK_EX | LOCK_NB)) {
			// code here to start the cron job
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$this->load->library('excel');
			$key = $this->config->item('sbi_m_key');
			
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			
			$query='SELECT * FROM amp_payment_transaction WHERE receipt_no = "4000542" AND gateway = "sbiepay" AND status = 2';
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
				$dir = 'cs2s_log/cronamp/'.$todays_date;
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
						//echo '<pre>';
						//print_r($responsedata);
						
						$cust=explode('^',$responsedata[5]);
						$responsedata[12]=$cust['1'];
						//echo "<br/>".$responsedata[12];
						
						if ($responsedata[12] == "iibfamp")
						{
							sleep(8);
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
								$get_user_regnum_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'id,ref_id,status,payment_option');
								//echo $this->db->last_query();
								//echo $get_user_regnum_info[0]['payment_option'];
								//check user payment status is updated by s2s or not
								if($get_user_regnum_info[0]['status']==2)
								{
									echo "<br/ >".$get_user_regnum_info[0]['status'];
									
									if($get_user_regnum_info[0]['payment_option']==3)
									{
										
										echo "<br/>In else".$get_user_regnum_info[0]['payment_option'];
										$payment_option='';
										if($get_user_regnum_info[0]['payment_option']== 2)
										{
											$payment_option='second';
										}
										else if($get_user_regnum_info[0]['payment_option']== 3)
										{
											$payment_option='Full';
										}
										
										$reg_id=$get_user_regnum_info[0]['ref_id'];
										
										//update amp registration table with installment status
										$update_mem_data = array('payment' =>$payment_option);
										$this->master_model->updateRecord('amp_candidates',$update_mem_data,array('id'=>$reg_id));
										echo "<br/>".$this->db->last_query();
										//$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id),'name,regnumber,email_id,photograph,signature,mobile_no,sponsor');
										$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id));
										
										//update payment transaction
										$update_data = array('member_regnumber' => $user_info[0]['regnumber'],'sponsor'=>$user_info[0]['sponsor'],'transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');
										$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
										echo "<br/>".$this->db->last_query();
										
										//maintain log in for updated transaction
										$log_title ="Installment payment";
										$update_info['membershipno'] = $user_info[0]['regnumber'];
										$log_message = serialize($update_mem_data);
										$this->Ampmodel->create_log($log_title, $log_message);
							
										//Query to get Payment details	
										$payment_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$user_info[0]['regnumber']),'transaction_no,date,amount');
								
										//email to user
										$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer'));
										if(count($emailerstr) > 0)
										{
											$username=$user_info[0]['name'];
											$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
											$newstring1 = str_replace("#REG_NUM#", "".$user_info[0]['regnumber']."",  $emailerstr[0]['emailer_text']);
											$newstring2 = str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring1);
											$newstring3 = str_replace("#EMAIL#", "".$user_info[0]['email_id']."",  $newstring2);
											$newstring4 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $newstring3);
											$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $newstring4);
											$newstring6 = str_replace("#STATUS#", "Transaction Successful",  $newstring5);
											$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring6);
										
											
											$info_arr=array('to'=>$user_info[0]['email_id'],
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str,
											//'bcc'=>'kumartupe@gmail.com,raajpardeshi@gmail.com'
											);
										//$this->send_mail($applicationNo);
										//$this->send_sms($applicationNo);
									
											//Invoice generation
											$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum_info[0]['id']));
									
											if(count($getinvoice_number) > 0)
											{
												$invoiceNumber = generate_amp_invoice_number($getinvoice_number[0]['invoice_id']);
											//	echo '<pre>',print_r($invoiceNumber),'</pre>';
														if($invoiceNumber)
														{
															$invoiceNumber=$this->config->item('amp_invoice_no_prefix').$invoiceNumber;
														}
														
												$update_data = array('invoice_no' => $invoiceNumber,'member_no'=>$user_info[0]['regnumber'],'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
													$this->db->where('pay_txn_id',$get_user_regnum_info[0]['id']);
													$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
													$attachpath=genarate_amp_invoice($getinvoice_number[0]['invoice_id']);
												echo "<br/>".$this->db->last_query();
												echo '<pre>update_data',print_r($update_data),'</pre>';
												echo '<pre>',print_r($attachpath),'</pre>';
												
					
											}
											
											if($attachpath!='')
											{
												$sms_newstring = str_replace("#fee#", "".$payment_info[0]['amount']."",  $emailerstr[0]['sms_text']);
												$sms_newstring1 = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring);
												$sms_final_str = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $sms_newstring1);
												//$this->master_model->send_sms($user_info[0]['mobile_no'],$sms_final_str);
												$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']),$sms_final_str,'N8YRKIwMg');												
												
												//$this->Emailsending->mailsend($info_arr);
												if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
												{
												
													//email send to sk datta and kavan for self sponsor
													if($user_info[0]['sponsor']=='self')
													{
														$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_self'));
														if(count($emailerSelfStr) > 0){
															$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
															
															if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
															
															if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
															
															if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
															
															if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = 'No'; }
															
															if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
															//echo $payment;exit;
															if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
															
															$selfstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerSelfStr[0]['emailer_text']);
															$selfstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr1);
															$selfstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr2);
															$selfstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $selfstr3);
															$selfstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $selfstr4);
															$selfstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $selfstr5);
															$selfstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $selfstr6);
															$selfstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $selfstr7);
															$selfstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $selfstr8);
															$selfstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $selfstr9);
															$selfstr11 = str_replace("#phone_no#", "".$phone_no."",  $selfstr10);
															$selfstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $selfstr11);
															$selfstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $selfstr12);
															$selfstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $selfstr13);
															$selfstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $selfstr14);
															$selfstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $selfstr15);
															$selfstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $selfstr16);
															$selfstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $selfstr17);
															$selfstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $selfstr18);
															$selfstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $selfstr19);
															$selfstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $selfstr20);
															$selfstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $selfstr21);
															$selfstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $selfstr22);
															$selfstr24 = str_replace("#till_present#", "".$till_present."",  $selfstr23);
															$selfstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $selfstr24);
															$selfstr26 = str_replace("#payment#", "".$payment."",  $selfstr25);
															$selfstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $selfstr26);
															$selfstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $selfstr27);
															$selfstr29 = str_replace("#STATUS#", "Transaction Successful",  $selfstr28);
															$selfstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $selfstr29);
															$final_selfstr = str_replace("#sponsor#", "".$sponsor."",  $selfstr30);
															
															$self_mail_arr = array(
														//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in,prabhakara@iibf.org.in',
															'to'=>'ravita@iibf.org.in,training@iibf.org.in',
															'from'=>$emailerSelfStr[0]['from'],
															'subject'=>$emailerSelfStr[0]['subject'],
															'message'=>$final_selfstr,
															);
															
															//echo '<pre>',print_r($self_mail_arr),'</pre>';
															$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);
														}
													}
													
													//email send to sk datta and kavan for bank sponsor
												if($user_info[0]['sponsor']=='bank'){
												$contact_mail_id = $user_info[0]['sponsor_contact_email'];
														$emailerBankStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_bank'));
														if(count($emailerBankStr) > 0){
															$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
															
															if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
															
															if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
															
															if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
															
															if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = ''; }
															
															if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
															
															if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
															
															if($user_info[0]['sponsor_contact_phone']!=0){ $sponsor_contact_phone = $user_info[0]['sponsor_contact_phone']; }else{ $sponsor_contact_phone = ''; }
															
															$bankstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerBankStr[0]['emailer_text']);
															$bankstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr1);
															$bankstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr2);
															$bankstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $bankstr3);
															$bankstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $bankstr4);
															$bankstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $bankstr5);
															$bankstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $bankstr6);
															$bankstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $bankstr7);
															$bankstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $bankstr8);
															$bankstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $bankstr9);
															$bankstr11 = str_replace("#phone_no#", "".$phone_no."",  $bankstr10);
															$bankstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $bankstr11);
															$bankstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $bankstr12);
															$bankstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $bankstr13);
															$bankstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $bankstr14);
															$bankstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $bankstr15);
															$bankstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $bankstr16);
															$bankstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $bankstr17);
															$bankstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $bankstr18);
															$bankstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $bankstr19);
															$bankstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $bankstr20);
															$bankstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $bankstr21);
															$bankstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $bankstr22);
															$bankstr24 = str_replace("#till_present#", "".$till_present."",  $bankstr23);
															$bankstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $bankstr24);
															$bankstr26 = str_replace("#payment#", "".$payment."",  $bankstr25);
															$bankstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $bankstr26);
															$bankstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $bankstr27);
															$bankstr29 = str_replace("#STATUS#", "Transaction Successful",  $bankstr28);
															$bankstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $bankstr29);
															$bankstr31 = str_replace("#sponsor#", "".$sponsor."",  $bankstr30);
															$bankstr32 = str_replace("#sponsor_bank_name#", "".$user_info[0]['sponsor_bank_name']."",  $bankstr31);
															$bankstr33 = str_replace("#sponsor_email#", "".$user_info[0]['sponsor_email']."",  $bankstr32);
															$bankstr34 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr33);
															$bankstr35 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr34);
															$bankstr36 = str_replace("#sponsor_contact_designation#", "".$user_info[0]['sponsor_contact_designation']."",  $bankstr35);
															$bankstr37 = str_replace("#sponsor_contact_std#", "".$user_info[0]['sponsor_contact_std']."",  $bankstr36);
															$bankstr38 = str_replace("#sponsor_contact_phone#", "".$sponsor_contact_phone."",  $bankstr37);
															$bankstr39 = str_replace("#sponsor_contact_mobile#", "".$user_info[0]['sponsor_contact_mobile']."",  $bankstr38);
															$final_bankstr = str_replace("#sponsor_contact_email#", "".$user_info[0]['sponsor_contact_email']."",  $bankstr39);
															
															$bank_mail_arr = array(
															//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in',
															'to'=>'ravita@iibf.org.in,training@iibf.org.in,'.$contact_mail_id.'',
															'from'=>$emailerBankStr[0]['from'],
															'subject'=>$emailerBankStr[0]['subject'],
															'message'=>$final_bankstr,
															);
															
															$this->Emailsending->mailsend_attch($bank_mail_arr,$attachpath);
														}
													}
													
												} 
												
											}
											
											//Manage Log
												$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
												
												$this->log_model->logamptransaction("sbiepay", $pg_response, $responsedata[2]);
												
												//redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
											}
											else
											{
												//redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
											}
									
									}
										
									
								}
							}
							
						}
						if($responsedata[12] == "iibfamp")
						{
								$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=C_S2S";
								$this->log_model->logamptransaction("sbiepay", $pg_response, $responsedata[2]);
						}
							
					}
					else
					{
						echo "Please try again...";
					}
			}//foreach
				
					
				}
				
			  
			} else {
				// throw an exception here to stop the next cron job
				echo "cronamp is already running";
			}
			fclose($filehandle);
		
	}
		
}
