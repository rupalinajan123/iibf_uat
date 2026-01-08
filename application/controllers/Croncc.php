<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Croncc extends CI_Controller 
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
		//$this->load->helper('blended_invoice_helper');
		$this->load->helper('date');
		$this->load->helper('contact_classes_invoice_helper');
		$this->load->model('billdesk_pg_model');
		$this->load->model('billdesk_pg_module_cron');
	}
	public function sbicallback()	
	{ 
		/* $filehandle = fopen("cs2s_log/croncc/lock.txt", "c+");
		if (flock($filehandle, LOCK_EX | LOCK_NB)) 
		{ */
			// code here to start the cron job		
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$this->load->library('excel');
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5(); 
			     /* echo $query='SELECT receipt_no FROM payment_transaction WHERE receipt_no IN(903691087,903691001,903690966,903690948,903690945,903690857,903690833,903690830,903690824,903690806,903690803,903690761,903690700,903691697) AND gateway = "billdesk" AND status = 2 AND pay_type="11"';    */   
			 
			       $query='SELECT receipt_no FROM payment_transaction WHERE date <= NOW() - INTERVAL 20 MINUTE 
			AND date > NOW() - INTERVAL 40 MINUTE AND gateway = "billdesk" AND status = 2 AND pay_type="11"';      
			
			$crnt_day_txn_qry = $this->db->query($query);
			echo "*********************************** New Cron Request Started***************************\n";
			echo  "Total Count =>". $crnt_day_txn_qry->num_rows();
			if ($crnt_day_txn_qry->num_rows())		 
			{
				/* $start_time = date("Y-m-d H:i:s");
				$succ_cnt = $fail_cnt = $no_resp_cnt = $pending_cnt = 0;
				$succ_recp_arr = $fail_recp_arr = $no_resp_recp_arr = $pending_recp_arr = array();
				$todays_date = date("d-m-Y");
				$dir = 'cs2s_log/croncc/'.$todays_date;
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
												->setCellValue('E'.$cell, "Response Date"); */
				foreach ($crnt_day_txn_qry->result_array() as $c_row)		
				{
					//$cell++;
					//sleep(1);
					$responsedata = $this->billdesk_pg_model->billdeskqueryapi($c_row['receipt_no']);
					//print_r($responsedata);
					 $receipt_no=$c_row['receipt_no'];
					$encData=implode('|',$responsedata);
					$resp_data = json_encode($responsedata);
					
					## Check payment_c_s2s_log entry 	
					$data_count = $this->master_model->getRecordCount('payment_transaction',array('receipt_no'=>$receipt_no,'status'=>1));
					if($data_count == 0)
					{
						
						## Update counts
						if($responsedata['auth_status'] == '0300')
						{
							$succ_cnt++;
							array_push($succ_recp_arr,$receipt_no);
						}else if($responsedata['auth_status'] == '0399')
						{
							$fail_cnt++;
							array_push($fail_recp_arr,$receipt_no);
							$update_data = array('status' => 0,'callback'=>'c_S2S','transaction_details'=>$responsedata[2]);
							$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$receipt_no,'status'=>2));
						}else if($responsedata['auth_status'] == 0002)
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
										'txn_status' 	=> $responsedata['transaction_error_type'],
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
						$merchIdVal = $Bank_Code = '';
						if (isset($responsedata['mercid'])) { $merchIdVal = $responsedata['mercid']; }
						if (isset($responsedata['bankid'])) { $Bank_Code = $responsedata['bankid']; }

						// $cust = explode('-',$responsedata['additional_info']['additional_info1']);

						 $pay_type = $responsedata['additional_info']['additional_info3'];
						;
						if($pay_type == "iibfcc")			
						{ 
							sleep(8);
							$MerchantOrderNo = $responsedata['orderid'];  
							$transaction_no  = $responsedata['transactionid'];
							$payment_status = 2;
							$auth_status = $responsedata['auth_status'];
							
							switch ($auth_status)
							{
								
								case "0300": $payment_status = 1; break; //success
								case "0399": $payment_status = 0; break; // failed
								case "0002": $payment_status = 2; break; // pending
							}
							if($payment_status == 1)
							{
								$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status,id,member_regnumber');
								echo "<br/>".$this->db->last_query();
								if ($get_user_regnum_info[0]['status'] == 2) 
								{
									$reg_id = $get_user_regnum_info[0]['ref_id'];
									$applicationNo = $get_user_regnum_info[0]['member_regnumber'];
									// User Entered Number							
																	
									$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata['transaction_error_type'] . " - " . $responsedata['transaction_error_desc'],'auth_code' => '0300', 'bankcode' => $responsedata['bankid'], 'paymode' =>  $responsedata['txn_process_type'],'callback'=>'c_S2S');
									
									$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
									echo '<br/>'.$this->db->last_query();
									
									// get primary key of contact_classes_registration table
									$get_refid = $this->master_model->getRecords('payment_transaction',array('receipt_no' => $MerchantOrderNo,'status' => 1));
									echo "<br/>".$this->db->last_query();
									
									//update pay_status 1 in contact_classes_registration table
									$ref_id = $get_refid[0]['ref_id'];
									$update_bank_data = array('pay_status' => 1,'modify_date' => date("Y-m-d H:i:s"));
									$this->master_model->updateRecord('contact_classes_registration', $update_bank_data, array('contact_classes_id' => $ref_id));
									echo "<br/>".$this->db->last_query();
									
									//subject remark update contact_classes_Subject_registration	
									$update_sub_data = array('remark' => 1,'modify_date' => date("Y-m-d H:i:s"));
									$this->master_model->updateRecord('contact_classes_Subject_registration', $update_sub_data, array('contact_classes_regid' =>$ref_id));
									
									$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo,'status' => 1));
									$mem_no =  $payment_info[0]['member_regnumber'];
									
									$member       = $this->db->query("SELECT *
															FROM contact_classes_registration
															WHERE pay_status = 1 AND 	contact_classes_id =".$ref_id);
									$memtype      = $member->result_array(); 
									print_r($memtype);
									echo "<br/>".$this->db->last_query();
									//get center name
									$this->db->where('center_code', $memtype[0]['center_code']);
									$center_info = $this->master_model->getRecords('contact_classes_center_master');
									
									$user_info  = $this->master_model->getRecords('contact_classes_Subject_registration', array('member_no' => $mem_no,'center_code'=>$memtype[0]['center_code'],'contact_classes_regid'=>$memtype[0]['contact_classes_id']));
									print_r($user_info);
									echo "<br/>".$this->db->last_query();
									$sub_array = array();
									foreach($user_info as $user_info_rec)
									{
										array_push($sub_array ,$user_info_rec['sub_code']);
									}
									
									// email to user
									$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'contactclasses'));
									
									$selfstr1  = str_replace("#regnumber#", "" . $mem_no . "", $emailerstr[0]['emailer_text']);
									$selfstr2  = str_replace("#program_name#", "" . $user_info[0]['program_name'] . "", $selfstr1);
									$selfstr3  = str_replace("#center_name#", "" . $center_info[0]['center_name'] . "", $selfstr2);
									$selfstr4  = str_replace("#venue_name#", "" . $user_info[0]['venue_name'] . "", $selfstr3);
									
									$selfstr7  = str_replace("#name#", "" . $memtype[0]['namesub'] . " " . $memtype[0]['firstname'] . " " . $memtype[0]['middlename'] . " " . $memtype[0]['lastname'], $selfstr4);
									$selfstr8  = str_replace("#address1#", "" . $memtype[0]['address1'] . "", $selfstr7);
									$selfstr9  = str_replace("#address2#", "" . $memtype[0]['address2'] . "", $selfstr8);
									$selfstr10 = str_replace("#address3#", "" . $memtype[0]['address3'] . "", $selfstr9);
									$selfstr11 = str_replace("#address4#", "" . $memtype[0]['address4'] . "", $selfstr10);
									
									$selfstr12 = str_replace("#district#", "" . $memtype[0]['district'] . "", $selfstr11);
									$selfstr13 = str_replace("#city#", "" . $memtype[0]['city'] . "", $selfstr12);
									$selfstr14 = str_replace("#state#", "" . $memtype[0]['state'] . "", $selfstr13);
									$selfstr15 = str_replace("#pincode#", "" . $memtype[0]['pincode'] . "", $selfstr14);
									$selfstr19 = str_replace("#email#", "" . $memtype[0]['email'] . "", $selfstr15);
									$selfstr20 = str_replace("#mobile#", "" . $memtype[0]['mobile'] . "", $selfstr19);
									
									$selfstr29     = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $selfstr20);
									$selfstr30     = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $selfstr29);
									$selfstr31     = str_replace("#STATUS#", "Transaction Successful", $selfstr30);
									$final_selfstr = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $selfstr31);
									
									
									
									  $info_arr = array('to'=>$memtype[0]['email'],
										//  'to' => 'kyciibf@gmail.com',
										'from' => $emailerstr[0]['from'],
										'subject' => $emailerstr[0]['subject'],
										'message' => $final_selfstr
									);
									echo $user_info[0]['zone_code'];
									if ($user_info[0]['zone_code'] == 'NZ') {
									$client_arr = array(
									'to'=>'sanjay@iibf.org.in,mkbhatia@iibf.org.in,iibfdevp@esds.co.in,se.pdcnz1@iibf.org.in,head-pdcnz@iibf.org.in', 
									'from' => $emailerstr[0]['from'],
									'subject' => $emailerstr[0]['subject'],
									'message' => $final_selfstr
									);
									print_r($client_arr);
									} elseif ($user_info[0]['zone_code'] == 'EZ') {
									$client_arr = array(
									'to'=>'iibfez@iibf.org.in',
									//  'to' => 'kyciibf@gmail.com',
									'from' => $emailerstr[0]['from'],
									'subject' => $emailerstr[0]['subject'],
									'message' => $final_selfstr 
									);
									}elseif ($user_info[0]['zone_code'] == 'SZ') {
									$client_arr = array(
									//	'to'=>'kyciibf@gmail.com',
									//  'to'=>'vratesh@iibf.org.in,sriram@iibf.org.in',
									'to'=>'sriram@iibf.org.in,priya@iibf.org.in,govindarajanr@iibf.org.in,iibfsz@iibf.org.in',
									'from' => $emailerstr[0]['from'],
									'subject' => $emailerstr[0]['subject'],
									'message' => $final_selfstr
									);
									}
									elseif ($user_info[0]['zone_code'] == 'CO') {
									$client_arr = array(
									//	'to'=>'kyciibf@gmail.com', 
									'to'=>'training@iibf.org.in,vratesh@iibf.org.in',
									'from' => $emailerstr[0]['from'],
									'subject' => $emailerstr[0]['subject'],
									'message' => $final_selfstr
									);
									}
									$getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
									'receipt_no' => $MerchantOrderNo,
									'pay_txn_id' => $get_user_regnum_info[0]['id']
									));
									
									echo "<br/>".$this->db->last_query();
									print_r($getinvoice_number);
									$session_id        =  $memtype[0]['contact_classes_id'];
									echo "<br/>".$this->db->last_query();
									if (count($getinvoice_number) > 0)
									{
										echo "<br />Invoice";
									  
										if ($user_info[0]['zone_code'] == 'CO') 
										{
											$invoiceNumber = generate_cc_invoice_number($user_info[0]['zone_code'],$getinvoice_number[0]['invoice_id']);
											if ($invoiceNumber) {
												$invoiceNumber = $this->config->item('Contact_classes_invoice_no_prefix_CO') . $invoiceNumber;
												}
										} elseif ($user_info[0]['zone_code'] == 'NZ')
										{	echo 'INvoice No generate';
											echo $getinvoice_number[0]['invoice_id'];
											  $invoiceNumber = generate_cc_invoice_number($user_info[0]['zone_code'],$getinvoice_number[0]['invoice_id']);
													if ($invoiceNumber) {
														$invoiceNumber = $this->config->item('Contact_classes_invoice_no_prefix_NZ') . $invoiceNumber;
													}
										} elseif ($user_info[0]['zone_code'] == 'SZ') {
											 $invoiceNumber = generate_cc_invoice_number($user_info[0]['zone_code'],$getinvoice_number[0]['invoice_id']);
													if ($invoiceNumber) {
														$invoiceNumber = $this->config->item('Contact_classes_invoice_no_prefix_SZ') . $invoiceNumber;
													}
										} elseif ($user_info[0]['zone_code'] == 'EZ') {
											  $invoiceNumber = generate_cc_invoice_number($user_info[0]['zone_code'],$getinvoice_number[0]['invoice_id']);
													if ($invoiceNumber) {
														$invoiceNumber = $this->config->item('Contact_classes_invoice_no_prefix_EZ') . $invoiceNumber;
													}
										}
										echo "<br/>".$invoiceNumber;	 
										$update_data = array(
											'invoice_no' => $invoiceNumber,
											'transaction_no' => $transaction_no,
											'date_of_invoice' => $getinvoice_number[0]['created_on'],
											'modified_on' => $getinvoice_number[0]['created_on']
										);
								$this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
								$this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
								echo "<br/>".$this->db->last_query();
									echo "<br/>".$user_info[0]['sub_code'];
											echo $attachpath = genarate_contact_classes_invoice_cs2s($getinvoice_number[0]['invoice_id'], $session_id , $user_info[0]['program_code'],$sub_array,$user_info[0]['zone_code']);
									}
						
									if ($attachpath != '') {
										   
											if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
											  
											   $this->Emailsending->mailsend_attch($client_arr, $attachpath);
											   
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
					echo '<br>--------------------------------------------------------------------------><br>';
				}	
			}
			
			//flock($filehandle, LOCK_UN);
		//}//If file	
		/* else 
		{
			// throw an exception here to stop the next cron job
			echo "Croncc is already running";
		} */
	}
			
}