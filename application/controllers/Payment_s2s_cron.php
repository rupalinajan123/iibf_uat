<?php
/*
 * Controller Name	:	Payment S2S Processing Cron
 * Created By		:	Bhagwan Sahane
 * Created Date		:	08-09-2017
 *
 * Updated By		:	Bhagwan Sahane
 * Updated Date		:	08-09-2017
 * Updated Date		:	14-09-2017	[ Updation by Prafull ]
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Payment_s2s_cron extends CI_Controller {
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
		
		//accedd denied due to GST
		//$this->master_model->warning();
	}
	
	public function sbicallback()
	{
		//##################### START >> Code Added By Bhagwan Sahane, on 08-09-2017 #####################
		
		ini_set("memory_limit", "-1");
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		
		$order_id_arr = array();
		$mark_as_success = array();
		$mark_as_fail = array();
		
		$cron_start_time = date("Y-m-d H:i:s");
		$cron_current_date = date("Ymd");
		$cron_log_file_path = "./uploads/payment_s2s_cron_logs/";
		$sms_template_id = '';
		
		// cron start log
		//$cron_log_result = array("mark_as_success" => "", "mark_as_fail" => "", "Start Time" => $cron_start_time, "End Time" => "");
		$cron_log_result = array("Start Time" => $cron_start_time, "End Time" => "");
		$cron_log_desc = json_encode($cron_log_result);
		$this->log_model->cronlog("IIBF Payment S2S Processing Cron Execution Start", $cron_log_desc);
		// eof cron log
		
		$cron_log_file1 = "logs_".$cron_current_date.".txt";
		$fp1 = fopen($cron_log_file_path.'/'.$cron_log_file1, 'a');
		fwrite($fp1, "\n************************* IIBF Payment S2S Processing Cron Execution Started - ".$cron_start_time." ************************* \n");
		
		$_REQUEST = array();
		
		// get pending payment transactions
		//$sql = "SELECT receipt_no FROM payment_transaction WHERE date >= DATE_SUB(NOW(), INTERVAL 10 MINUTE) AND date < DATE_SUB(NOW(), INTERVAL 5 MINUTE) AND gateway = 'sbiepay' AND status = 2 ORDER BY id DESC";
		$sql = "SELECT receipt_no FROM payment_transaction WHERE receipt_no = '811950334' AND gateway = 'sbiepay' AND status = 2 ORDER BY id DESC";
		$pt_query = $this->db->query($sql);
		//echo $this->db->last_query();
		if ($pt_query->num_rows())
		{
			foreach ($pt_query->result_array() as $row)
			{
				$receipt_no = $row['receipt_no'];  // order_no
				
				$order_id_arr[] = $receipt_no;
				
				// get S2S response data for this transaction 
				$sql = "SELECT * FROM payment_s2s_log WHERE receipt_no = '".$receipt_no."' ORDER BY id DESC LIMIT 1";
				$pay_resp_query = $this->db->query($sql);
				//echo $this->db->last_query();
				if ($pay_resp_query->num_rows())
				{
					$row_resp = $pay_resp_query->result_array();
					//print_r($row_resp);
					
					if($row_resp[0]['txn_status'] == "SUCCESS")
					{
						$mark_as_success[] = $receipt_no;	
					}
					
					if($row_resp[0]['txn_status'] == "FAIL")
					{
						$mark_as_fail[] = $receipt_no;	
					}
					
					// actual s2s response data
					$_REQUEST = json_decode($row_resp[0]['response_data'], TRUE);
					//echo "**<pre>";
					//print_r($_REQUEST);
					//die(); exit;
			
		//##################### END >> Code Added By Bhagwan Sahane, on 08-09-2017 #####################
					
					/*error_reporting(E_ALL);
					//$pg_reply = @file_get_contents('php://input');
					
					$myfile = fopen("SBI_epay_callback.txt", "a") or die("Unable to open file!");
					//$txt = "user id date--";
					//fwrite($myfile, "\n". $pg_reply);
					//$raw_post_data = file_get_contents('php://input');
					//fwrite($myfile, "\ntesttset". implode(" ",$_REQUEST)
			//		fwrite($myfile, "\ntesttset ".count($_REQUEST)." ".$txt);
					fwrite($myfile, "\noooooooooooooooooooo ");
					fclose($myfile);
					
					// Check payment status
					//echo "Hi";
					
					$this->load->library('email');
					$this->load->model('Emailsending');
					$final_str= "SBI callback executed ";
					$info_arr=array(
									'to'=> "sagar.deshmukh@esds.co.in",
									'from'=> "starsagar123@gmail.com",
									'subject'=>"SBI callback",
									'message'=>$final_str." ***** ".$_REQUEST['merchIdVal']
								);
											
					$this->Emailsending->mailsend($info_arr);
					
				//	exit;
					*/
			
					if (isset($_REQUEST['pushRespData']))
					{
						$this->load->model('log_model');
			
						include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			
						$key = $this->config->item('sbi_m_key');
						
						$aes = new CryptAES();
						$aes->set_key(base64_decode($key));
						$aes->require_pkcs5();
			
						if (isset($_REQUEST['merchIdVal']))
						{
							$merchIdVal = $_REQUEST['merchIdVal'];
						}
						if (isset($_REQUEST['Bank_Code']))
						{
							$Bank_Code = $_REQUEST['Bank_Code'];
						}
						if (isset($_REQUEST['pushRespData']))
						{
							$encData = $_REQUEST['pushRespData'];
						}
			
						$encData = $aes->decrypt($_REQUEST['pushRespData']);
						$responsedata = explode("|",$encData);
						//print_r($responsedata);
						$cust=explode('^',$responsedata[6]);
						
						$responsedata[6]=$cust['1'];
						
						// Examination
						if($responsedata[6]=='iibfexam')
						{
							if($cust['2']!='iibfdra')	// Not DRA Exam Application
							{
								$MerchantOrderNo = $responsedata[0]; 
								$get_pg_flag=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'pg_flag');
								$responsedata[6]=$get_pg_flag[0]['pg_flag'];
							}
							else
							{
								$responsedata[6]=$cust['2'];
							}
						}
						
						// Registration
						if($responsedata[6]=='iibfregn')
						{
							if($cust['2']!='iibfregn')	// Not New Member registration
							{
								$MerchantOrderNo = $responsedata[0]; 
								$get_pg_flag=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'pg_flag');
								$responsedata[6]=$get_pg_flag[0]['pg_flag'];
								
								//$responsedata[6]=$cust['2'];
							}
						}
						
						if ($responsedata[6] == "iibfregn")
						{
							sleep(8);
							$MerchantOrderNo = $responsedata[0]; 
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
								//check user payment status is updated by B2B or not
								if($get_user_regnum_info[0]['status']==2)
								{
									$reg_id=$get_user_regnum_info[0]['ref_id'];
						
									//$applicationNo = generate_mem_reg_num();
									$applicationNo =generate_O_memreg($reg_id);
									
									$update_data = array('member_regnumber' => $applicationNo,'transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
										
									/*$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber');*/
									
									if(count($get_user_regnum_info) > 0)
									{
										$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
										$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
										
										$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,mobile');
										
										
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
										}
								}
								 //email to user
								 $emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_register_email'));
								 $sms_template_id = 'DPDoOIwMR';

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
															  'subject'=>$emailerstr[0]['subject'],
															  'message'=>$final_str
															);
									
						//set invoice
						$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum_info[0]['id']));
						//echo $this->db->last_query();exit;
						if(count($getinvoice_number) > 0)
						{
							
								if($getinvoice_number[0]['state_of_center']=='JAM')
								{
									$invoiceNumber = generate_registration_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
									if($invoiceNumber)
									{
										$invoiceNumber=$this->config->item('mem_invoice_no_prefix_jammu').$invoiceNumber;
									}
								}
								else
								{
								$invoiceNumber = generate_registration_invoice_number($getinvoice_number[0]['invoice_id']);
								if($invoiceNumber)
								{
									$invoiceNumber=$this->config->item('mem_invoice_no_prefix').$invoiceNumber;
								}
							}
							
							
							$update_data = array('invoice_no' => $invoiceNumber,'member_no'=>$applicationNo,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
							$this->db->where('pay_txn_id',$get_user_regnum_info[0]['id']);
							$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
							$attachpath=genarate_reg_invoice($getinvoice_number[0]['invoice_id']);
						}	
						if($attachpath!='')
						{	
						
										$sms_newstring = str_replace("#application_num#", "".$applicationNo."",  $emailerstr[0]['sms_text']);
										$sms_final_str= str_replace("#password#", "".$decpass."",  $sms_newstring);
										//$this->master_model->send_sms($user_info[0]['mobile'],$sms_final_str);
										$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);	
																		
										//if($this->Emailsending->mailsend($info_arr))
										if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
										{
											
										//$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
											//redirect(base_url('register/acknowledge/'));
										}
										else
										{
											//echo 'Error while sending email';
											//$this->session->set_flashdata('error','Error while sending email !!');
											//redirect(base_url('register/preview/'));
										}
										}
									else
									{
										//$this->session->set_flashdata('error','Error while sending email !!');
										//redirect(base_url('register/preview/'));
									}
								}
							}
						}	
							else if($payment_status==0)
							{
								$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status');
								if($get_user_regnum_info[0]['status']==2)
								{
									$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								}
							}
						}
						
						if ($responsedata[6] == "iibfren")
						{
							sleep(8);
							$MerchantOrderNo = $responsedata[0]; 
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
								 $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status,id');
								//check user payment status is updated by s2s or not
								if ($get_user_regnum_info[0]['status'] == 2) {
									$reg_id = $get_user_regnum_info[0]['ref_id'];
									$applicationNo = $get_user_regnum_info[0]['member_regnumber']; // User Entered Number
									
									$update_data = array('member_regnumber' => $applicationNo, 'transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'S2S');
									$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
								
									  if (count($get_user_regnum_info) > 0) {
										$update_mem_data = array('isactive' => '1', 'regnumber' => $applicationNo, 'is_renewal' => 1);
										$this->master_model->updateRecord('member_registration', $update_mem_data, array('regid' => $reg_id));
										$user_info = $this->master_model->getRecords('member_registration', array('regid' => $reg_id), 'usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,mobile');
										$upd_files = array();
										$photo_file = 'p_' . $applicationNo . '.jpg';
										$sign_file = 's_' . $applicationNo . '.jpg';
										$proof_file = 'pr_' . $applicationNo . '.jpg';
										if (@rename("./uploads/photograph/" . $user_info[0]['scannedphoto'], "./uploads/photograph/" . $photo_file)) {
											$upd_files['scannedphoto'] = $photo_file;
										}
										if (@rename("./uploads/scansignature/" . $user_info[0]['scannedsignaturephoto'], "./uploads/scansignature/" . $sign_file)) {
											$upd_files['scannedsignaturephoto'] = $sign_file;
										}
										if (@rename("./uploads/idproof/" . $user_info[0]['idproofphoto'], "./uploads/idproof/" . $proof_file)) {
											$upd_files['idproofphoto'] = $proof_file;
										}
										if (count($upd_files) > 0) {
											$this->master_model->updateRecord('member_registration', $upd_files, array('regid' => $reg_id));
										}
									}
									
									//Manage Log
									$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
									$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
									
									//email to user
									  $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' =>'user_renewal_email'));
									  $sms_template_id = 'MQvtFIwMg';
									if (count($emailerstr) > 0) {
										include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
										$key = $this->config->item('pass_key');
										$aes = new CryptAES();
										$aes->set_key(base64_decode($key));
										$aes->require_pkcs5();
										//$encPass = $aes->encrypt(trim($user_info[0]['usrpassword']));
										$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
										//$decpass = $aes->decrypt($user_info[0]['usrpassword']);
										$newstring = str_replace("#application_num#", "" . $applicationNo . "", $emailerstr[0]['emailer_text']);
										$final_str = str_replace("#password#", "" . $decpass . "", $newstring);
										$info_arr = array('to' => $user_info[0]['email'],
										//'to'=>'kumartupe@gmail.com',
										'from' => $emailerstr[0]['from'], 'subject' => $emailerstr[0]['subject'], 'message' => $final_str);
										// INVOICE CODE
										$getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $get_user_regnum_info[0]['id']));
										if (count($getinvoice_number) > 0) {
											if ($getinvoice_number[0]['state_of_center'] == 'JAM') {
												$invoiceNumber = generate_renewal_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
												if ($invoiceNumber) {
													$invoiceNumber = $this->config->item('renewal_mem_invoice_no_prefix_jammu') . $invoiceNumber;
												}
											} else {
												$invoiceNumber = generate_renewal_invoice_number($getinvoice_number[0]['invoice_id']);
												if ($invoiceNumber) {
													$invoiceNumber = $this->config->item('renewal_mem_invoice_no_prefix') . $invoiceNumber;
												}
											}
											$update_data = array('invoice_no' => $invoiceNumber, 'member_no' => $applicationNo, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
											$this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
											$this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
											$attachpath = genarate_renewal_invoice($getinvoice_number[0]['invoice_id']);
										}
										if ($attachpath != '') {
											$this->Emailsending->mailsend_attch($info_arr, $attachpath);
										} 
									}
								}
						}	
							else if($payment_status==0)
							{
								$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status');
								if($get_user_regnum_info[0]['status']==2)
								{
									$update_data = array('transaction_no' => $transaction_no, 'status' => 0, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'S2S');
									$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
								}
							}
						}
						
						else if($responsedata[6] == "iibfdup")
						{
							sleep(8);
							$MerchantOrderNo = $responsedata[0]; 
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
								// Handle transaction sucess case 
								$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,id');
								if($get_user_regnum[0]['status']==2)
								{
								if(count($get_user_regnum) > 0)
								{
									$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
								}
			
								$update_data = array('pay_status' => '1');
								$this->master_model->updateRecord('duplicate_icard',$update_data,array('did'=>$get_user_regnum[0]['ref_id']));
								
								$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'duplicate_id'));
								
								$sms_template_id = 'NA';

							   if(count($emailerstr) > 0 && (count($get_user_regnum) > 0))
								{
									//Query to get user details
									$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'namesub,firstname,middlename,lastname,email');
									$username=$user_info[0]['namesub'].' '.$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									$newstring1 = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);
									$newstring2 = str_replace("#MEM_NO#", "".$get_user_regnum[0]['member_regnumber']."", $newstring1 );
									$final_str= str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring2);
									$info_arr = array('to'=>$user_info[0]['email'],'from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'],'message'=>$final_str);
									
									//genertate invoice and email send with invoice attach 8-7-2017					
									//get invoice	
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum[0]['id']));
									if(count($getinvoice_number) > 0)
									{ 
										if($getinvoice_number[0]['state_of_center']=='JAM')
										{
											$invoiceNumber = generate_duplicate_id_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
											if($invoiceNumber)
											{
												$invoiceNumber=$this->config->item('Dup_Id_invoice_no_prefix_jammu').$invoiceNumber;
											}
										}
										else
										{
												$invoiceNumber = generate_duplicate_id_invoice_number($getinvoice_number[0]['invoice_id']);
												if($invoiceNumber)
												{
													$invoiceNumber=$this->config->item('Dup_Id_invoice_no_prefix').$invoiceNumber;
												}
											}
										$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
										$this->db->where('pay_txn_id',$get_user_regnum[0]['id']);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										$attachpath=genarate_duplicateicard_invoice($getinvoice_number[0]['invoice_id']);
									}
									if($attachpath!='')
									{
										$pay_status=array();
										$regnumber = $get_user_regnum[0]['member_regnumber'];
										$where1 = array('member_number'=> $regnumber);
										$pay_status= $this->master_model->updateRecord('member_idcard_cnt',array('card_cnt'=>'0'),$where1);
										
										 /* User Log Activities : Pooja */
										$uerlog = $this->master_model->getRecords('member_registration',array('regnumber'=>$regnumber,'isactive'=>'1'),'regid');
										$user_info = $this->master_model->getRecords('member_idcard_cnt',array('member_number'=>$regnumber));
										$log_title ="Apply for Duplicate Id card : ".$uerlog[0]['regid'];
										$log_message = serialize($user_info);
										$rId =$uerlog[0]['regid'];
										$regNo = $regnumber;
										storedUserActivity($log_title, $log_message, $rId, $regNo);
										$this->Emailsending->mailsend($info_arr);
									}
								}
								}
							}
							else if($payment_status==0)
							{
								// Handle transaction fail case 
								$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
								if($get_user_regnum[0]['status']==2)
								{
									$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								}
								// Handle transaction fail case 
							}
						}
						else if($responsedata[6] == "iibfdupcer")
						{
							sleep(8);
							$MerchantOrderNo = $responsedata[0]; 
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
								// Handle transaction sucess case 
								$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,id');
								if($get_user_regnum[0]['status']==2)
								{
								if(count($get_user_regnum) > 0)
								{
									$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'isactive'=>'1'),'regnumber,usrpassword,email');
									//if member is DRA member
									if(empty($user_info))
									{
										$user_info = $this->master_model->getRecords('dra_members',array('regnumber'=>$get_user_regnum[0]['member_regnumber']));
									}
								}
								$update_data = array('pay_status' => '1');
								$this->master_model->updateRecord('duplicate_certificate',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
								
								$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'duplicate_cert'));
								$sms_template_id = 'MVPWKSwGg';
								if(count($emailerstr) > 0 && (count($get_user_regnum) > 0))
								{
												$final_str = $emailerstr[0]['emailer_text'];
												$info_arr = array('to'=>$user_info[0]['email'],'from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'],'message'=>$final_str);
												
												//genertate invoice and email send with invoice attach 8-7-2017					
												//get invoice	
												$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum[0]['id']));
								 
												//echo $this->db->last_query();exit;
												if(count($getinvoice_number) > 0)
												{ 
														if($getinvoice_number[0]['state_of_center']=='JAM')
														{
														$invoiceNumber = generate_duplicate_cert_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
															if($invoiceNumber)
															{
																$invoiceNumber=$this->config->item('Dup_cert_invoice_no_prefix_jammu').$invoiceNumber;
															}
														}
														else
														{
															$invoiceNumber = generate_duplicate_cert_invoice_number($getinvoice_number[0]['invoice_id']);
															if($invoiceNumber)
															{
																$invoiceNumber=$this->config->item('Dup_cert_invoice_no_prefix').$invoiceNumber;
															}
														}
													$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
													$this->db->where('pay_txn_id',$get_user_regnum[0]['id']);
													$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
													$attachpath=genarate_duplicatecert_invoice($getinvoice_number[0]['invoice_id']);
												}
											
											if($attachpath!='')
											{	
												$this->Emailsending->mailsend_attch($info_arr,$attachpath);
											}
									}
									
								}
							}
							else if($payment_status==0)
							{
								// Handle transaction fail case 
								$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
								if($get_user_regnum[0]['status']==2)
								{
									$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								}
								// Handle transaction fail case 
							}
						}
						else if($responsedata[6] == "IIBF_EXAM_O")
						{
										sleep(8);
										$MerchantOrderNo = $responsedata[0]; 
										$transaction_no  = $responsedata[1];
										$payment_status = 2;
										$attachpath=$invoiceNumber=$admitcard_pdf='';
										
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
										$exam_period_date='';
										//Handle transaction success case
										$elective_subject_name='';
										$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
										if($get_user_regnum[0]['status']==2)
										{
												######### payment Transaction ############
												$this->db->trans_start();
												$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
												$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
												$this->db->trans_complete();
												
												if(count($get_user_regnum) > 0)
												{
												$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
												}
												
												//Query to get exam details	
												$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
												$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
												$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
												$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
												$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code');
												
												if($exam_info[0]['exam_code']!=101)
												{
													########## Generate Admit card and allocate Seat #############
													$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
													############check capacity is full or not ##########
													//$subject_arr=$this->session->userdata['examinfo']['subject_arr'];
													if(count($exam_admicard_details) > 0)
													{			
														$msg='';
														$sub_flag=1;
														$sub_capacity=1;
														foreach($exam_admicard_details as $row)
														{
															$capacity=check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
															if($capacity==0)
															{
																#########get message if capacity is full##########
																redirect(base_url().'Home/refund/'.base64_encode($MerchantOrderNo));
															}
														}
													}
													if(count($exam_admicard_details) > 0)
													{	
														$password=random_password();
														foreach($exam_admicard_details as $row)
														{
															$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'exam_date'=>$row['exam_date'],'session_time'=>$row['time']));
															
															$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
															
															//echo $this->db->last_query().'<br>';
															$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
												
															if($seat_number!='')
															{
																$final_seat_number =$seat_number;
																$update_data = array('pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
																$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
															}
															else
															{
																$log_title ="Fail user seat allocation id:".$get_user_regnum[0]['member_regnumber'];
																$log_message = serialize($this->session->userdata['examinfo']['subject_arr']);
																$rId = $get_user_regnum[0]['member_regnumber'];
																$regNo = $get_user_regnum[0]['member_regnumber'];
																storedUserActivity($log_title, $log_message, $rId, $regNo);
																//redirect(base_url().'Home/refund/'.base64_encode($MerchantOrderNo));
															}
														}
														##############Get Admit card#############
														$admitcard_pdf=genarate_admitcard($get_user_regnum[0]['member_regnumber'],$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
													}
													else
													{
														redirect(base_url().'Home/refund/'.base64_encode($MerchantOrderNo));
													}
						}
												
												######update member_exam######
												$update_data = array('pay_status' => '1');
												$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
												
												//Query to get user details
												$this->db->join('state_master','state_master.state_code=member_registration.state');
												$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
												$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');
											
												
												
												if(count($exam_info) <= 0)
												{
													$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']));
												}
											
												if($exam_info[0]['exam_mode']=='ON')
												{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
												{$mode='Offline';}
												else{$mode='';}
												
												if($exam_info[0]['examination_date']!='0000-00-00')
												{
													$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
												}
												else if($exam_info[0]['exam_code']!=990)
												{
													//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
													$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
													$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
												}
												//Query to get Medium	
												$this->db->where('exam_code',$exam_info[0]['exam_code']);
												$this->db->where('exam_period',$exam_info[0]['exam_period']);
												$this->db->where('medium_code',$exam_info[0]['exam_medium']);
												$this->db->where('medium_delete','0');
												$medium=$this->master_model->getRecords('medium_master','','medium_description');
												$this->db->where('state_delete','0');
												$states=$this->master_model->getRecords('state_master',array('state_code'=>$exam_info[0]['state_place_of_work']),'state_name');
										
												//Query to get Payment details	
												$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,id');
									
												$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
												$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
											//if($this->session->userdata['examinfo']['elected_exam_mode']=='E')
												if($exam_info[0]['place_of_work']!='' && $exam_info[0]['state_place_of_work']!='' && $exam_info[0]['pin_code_place_of_work']!='')
												{
													//get Elective Subeject name for CAIIB Exam	
												   if($exam_info[0]['elected_sub_code']!=0 && $exam_info[0]['elected_sub_code']!='')
												   {
													   $elective_sub_name_arr=$this->master_model->getRecords('subject_master',array('subject_code'=>$exam_info[0]['elected_sub_code'],'subject_delete'=>0),'subject_description');
														if(count($elective_sub_name_arr) > 0)
														{
															$elective_subject_name=$elective_sub_name_arr[0]['subject_description'];
														}	
												   }
													$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'member_exam_enrollment_nofee_elective'));
													$sms_template_id = 'oOmlKIQGR';
													$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
													$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
													$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
													$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
													$newstring5 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring4);
													$newstring6 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring5);
													$newstring7 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring6);
													$newstring8 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring7);
													$newstring9 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring8);
													$newstring10 = str_replace("#CITY#", "".$result[0]['city']."",$newstring9);
													$newstring11 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring10);
													$newstring12 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring11);
													$newstring13 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring12);
													$newstring14 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring13);
													$newstring15 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring14);
													$newstring16 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring15);
													$newstring17 = str_replace("#ELECTIVE_SUB#", "".$elective_subject_name."",$newstring16);
													$newstring18 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring17);
													$newstring19 = str_replace("#PLACE_OF_WORK#", "".strtoupper($exam_info[0]['place_of_work'])."",$newstring18);
													$newstring20 = str_replace("#STATE_PLACE_OF_WORK#", "".$states[0]['state_name']."",$newstring19);
													$newstring21 = str_replace("#PINCODE_PLACE_OF_WORK#", "".$exam_info[0]['pin_code_place_of_work']."",$newstring20);
													$final_str = str_replace("#MODE#", "".$mode."",$newstring21);
											 }
											else
											{
												if($exam_info[0]['exam_code']==990)
												{
													$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'DISA_emailer'));
													$sms_template_id = 'S8OmhSQGg';
													$final_str = $emailerstr[0]['emailer_text'];
												}
												else
												{
													$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'apply_exam_transaction_success'));
													$sms_template_id = 'P6tIFIwGR';
													$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
													$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
													$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
													$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
													$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
													$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
													$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
													$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
													$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
													$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
													$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
													$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
													$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
													$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
													$newstring15 = str_replace("#INSTITUDE#", "".$result[0]['name']."",$newstring14);
													$newstring16 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring15);
													$newstring17 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring16);
													$newstring18 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring17);
													$newstring19 = str_replace("#MODE#", "".$mode."",$newstring18);
													$newstring20 = str_replace("#PLACE_OF_WORK#", "".$result[0]['office']."",$newstring19);
													$newstring21 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring20);
													$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
												}
											}
											$info_arr=array('to'=>$result[0]['email'],
																	'from'=>$emailerstr[0]['from'],
																	'subject'=>$emailerstr[0]['subject'],
																	'message'=>$final_str
																);
																
											//echo $final_str; exit;
											
											//get invoice	
										$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
										//echo $this->db->last_query();exit;
										########### generate invoice ###########
										if(count($getinvoice_number) > 0)
										{
											if($getinvoice_number[0]['state_of_center']=='JAM')
											{
												$invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
												if($invoiceNumber)
												{
													$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
												}
											}
											else
											{
												$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
												if($invoiceNumber)
												{
													$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
												}
											}
											$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
											$this->db->where('pay_txn_id',$payment_info[0]['id']);
											$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
											$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
										}	
											
										if($attachpath!='')
										{		
												$files=array($attachpath,$admitcard_pdf);
												if($exam_info[0]['exam_code']==990)
												{
													$sms_final_str = $emailerstr[0]['sms_text'];
												}
												else
												{
													$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
													$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
													$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
													$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
												}
												// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
													$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);
												$this->Emailsending->mailsend_attch($info_arr,$files);
											//$this->Emailsending->mailsend($info_arr);
											}
										}
									}
									else if($payment_status==0)
									{
										$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code');
										if($get_user_regnum[0]['status']==2)
										{
											$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => 0399,'bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'S2S');
											$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
											$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
											//Query to get Payment details	
											$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
											if($get_user_regnum[0]['exam_code']!='990')
											{
												//Query to get user details
											$this->db->join('state_master','state_master.state_code=member_registration.state');
											$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
											$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');
											//Query to get exam details	
											$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
											$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
											$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
											$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
											$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
										
											//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
											$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
											$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
											
											$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
											$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
											$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
											$sms_template_id = 'Jw6bOIQGg';
											$newstring1 = str_replace("#application_num#", "".$get_user_regnum[0]['member_regnumber']."",  $emailerstr[0]['emailer_text']);
											$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
											$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
											$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
											
											$info_arr=array(	'to'=>$result[0]['email'],
																		'from'=>$emailerstr[0]['from'],
																		'subject'=>$emailerstr[0]['subject'],
																		'message'=>$final_str
																	);
											//send sms to Ordinary Member
											$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
											$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
											// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
											
											$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);

											$this->Emailsending->mailsend($info_arr);
											}
										}
									 }				
									}
						else if($responsedata[6] == "IIBF_EXAM_NM")
						{
										sleep(8);
										$MerchantOrderNo = $responsedata[0]; 
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
										// Handle transaction success case 
										$exam_period_date=$attachpath=$invoiceNumber='';
										$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
										if($get_user_regnum[0]['status']==2)
										{
										if(count($get_user_regnum) > 0)
										{
											$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
										}
										
										######### payment Transaction ############
										$this->db->trans_start(); 
										$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
										$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
										$this->db->trans_complete();
										
										//Query to get user details
										$this->db->join('state_master','state_master.state_code=member_registration.state');
										//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
										$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword');
										
										//Query to get exam details	
										$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
										$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
										$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
										$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
										$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.examination_date');
										if(count($exam_info) <= 0)
										{
											$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']));
										}
										
										########## Generate Admit card and allocate Seat #############
										if($exam_info[0]['exam_code']!=101)
										{
											$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
											if(count($exam_admicard_details) > 0)
											{
									############check capacity is full or not ##########
									//$subject_arr=$this->session->userdata['examinfo']['subject_arr'];
									if(count($exam_admicard_details) > 0)
									{		
										$msg='';
										$sub_flag=1;
										$sub_capacity=1;
										foreach($exam_admicard_details as $row)
										{
											 $capacity=check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
											if($capacity==0)
											{
												#########get message if capacity is full##########
												redirect(base_url().'NonMember/refund/'.base64_encode($MerchantOrderNo));
											}
										}
									}
									$password=random_password();
									foreach($exam_admicard_details as $row)
									{
										$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'exam_date'=>$row['exam_date'],'session_time'=>$row['time']));
										
										$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
										
										//echo $this->db->last_query().'<br>';
										$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
							
										if($seat_number!='')
										{
											$final_seat_number = $seat_number;
											$update_data = array('pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
											$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
										}
									}
									##############Get Admit card#############
									$admitcard_pdf=genarate_admitcard($get_user_regnum[0]['member_regnumber'],$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
								}		
											else
											{
									redirect(base_url().'NonMember/refund/'.base64_encode($MerchantOrderNo));
								}
										}
										######update member_exam######	
										$update_data = array('pay_status' => '1');
										$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
									
										if($exam_info[0]['exam_mode']=='ON')
										{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
										{$mode='Offline';}
										else{$mode='';}
										if($exam_info[0]['examination_date']!='0000-00-00')
										{
											$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
										}
										else if($exam_info[0]['exam_code']!=990)
										{
											//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
											$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
											$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
										}
										//Query to get Medium	
										$this->db->where('exam_code',$exam_info[0]['exam_code']);
										$this->db->where('exam_period',$exam_info[0]['exam_period']);
										$this->db->where('medium_code',$exam_info[0]['exam_medium']);
										$this->db->where('medium_delete','0');
										$medium=$this->master_model->getRecords('medium_master','','medium_description');
									
										//Query to get Payment details	
										$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,id');
								
										$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
										$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
										
										include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
										$key = $this->config->item('pass_key');
										$aes = new CryptAES();
										$aes->set_key(base64_decode($key));
										$aes->require_pkcs5();
										$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
										
										if($exam_info[0]['exam_code']==990)
										{
											$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'DISA_emailer'));
											$sms_template_id = 'S8OmhSQGg';
											$final_str = $emailerstr[0]['emailer_text'];
										}
										else
										{
											$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
											$sms_template_id = 'P6tIFIwGR';
											$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
										$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
										$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
										$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
										$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
										$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
										$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
										$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
										$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
										$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
										$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
										$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
										$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
										$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
										$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
										$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
										$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
										$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
										$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
										$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring19);
										$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring20);
										}
									
										$info_arr=array('to'=>$result[0]['email'],
																'from'=>$emailerstr[0]['from'],
																'subject'=>$emailerstr[0]['subject'],
																'message'=>$final_str
															);
										//get invoice	
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
									//echo $this->db->last_query();exit;
									if(count($getinvoice_number) > 0)
									{
										if($getinvoice_number[0]['state_of_center']=='JAM')
										{
											$invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
											if($invoiceNumber)
											{
												$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
											}
										}
										else
										{
											$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
											if($invoiceNumber)
											{
												$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
											}
										}
										$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'));
										$this->db->where('pay_txn_id',$payment_info[0]['id']);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
									}		
										
													
										if($attachpath!='')
										{	
											$files=array($attachpath,$admitcard_pdf);	
											if($exam_info[0]['exam_code']==990)
											{
												$sms_final_str = $emailerstr[0]['sms_text'];
											}
											else
											{
												$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
												$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
												$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
												$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
											}
											
											// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
											$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);
											$this->Emailsending->mailsend_attch($info_arr,$files);
											//$this->Emailsending->mailsend($info_arr);
										}
								}
							}
								else if($payment_status==0)
								{
										// Handle transaction  fail case
										// Handle transaction success case 
										$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code');
										if($get_user_regnum[0]['status']==2)
										{
											$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
											$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => 0399,'bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'S2S');
											$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
											if($get_user_regnum[0]['exam_code']!='990')
											{
												$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
												
												//Query to get Payment details	
												$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
												
												//Query to get exam details	
												/*$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
												$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
												$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
												$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');*/
												$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
											$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
											$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
											$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
												$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
										
												//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
												$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
												$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
										
												$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
												$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
												$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
												$sms_template_id = 'Jw6bOIQGg';

												$newstring1 = str_replace("#application_num#", "".$get_user_regnum[0]['member_regnumber']."",  $emailerstr[0]['emailer_text']);
												$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
												$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
												$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
												
												$info_arr=array(	'to'=>$result[0]['email'],
																			'from'=>$emailerstr[0]['from'],
																			'subject'=>$emailerstr[0]['subject'],
																			'message'=>$final_str
																		);
												
												$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
												$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
												// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
												$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);
												$this->Emailsending->mailsend($info_arr);
											}
										}
									}
								}
						else if($responsedata[6] == "IIBF_EXAM_REG")
						{
										sleep(8);
										$MerchantOrderNo = $responsedata[0]; 
										$transaction_no  = $responsedata[1];
										$payment_status = 2;
										$attachpath=$invoiceNumber='';
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
										// Handle transaction success case 
										$exam_period_date='';
										$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
										if($get_user_regnum[0]['status']==2)
										{
											$exam_code=$get_user_regnum[0]['exam_code'];
											$reg_id=$get_user_regnum[0]['member_regnumber'];
											
											########## Generate Admit card and allocate Seat #############
											if($exam_code!=101)
											{
												$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
												//$subject_arr=$this->session->userdata['enduserinfo']['subject_arr'];
												if(count($exam_admicard_details) > 0)
												{		
													$msg='';
													$sub_flag=1;
													$sub_capacity=1;
													foreach($exam_admicard_details as $row)
													{
														$capacity=check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
														if($capacity==0)
														{
															#########get message if capacity is full##########
															/*Add code trans_start & trans_complete : pooja  */
															$this->db->trans_start(); 
															$update_data = array('transaction_no' => $transaction_no,'status' => 1,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
															$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
															$this->db->trans_complete();
															redirect(base_url().'Nonreg/refund/'.base64_encode($MerchantOrderNo));
														}
													}
												}
											}
												
											//$applicationNo = generate_nm_reg_num();
											$applicationNo = generate_NM_memreg($reg_id);
											
											//Query to get exam details	
											$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
											$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
											$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
											$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
											$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.examination_date');
											
								
											######### payment Transaction ############
											$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
											$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
											
											######### update application number to Registration table#########
											$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
											$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
											
											######### update application number to member exam#########
											$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
											$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
											
											########## Generate Admit card and allocate Seat #############
											if($exam_code!='101')
											{
												if(count($exam_admicard_details) > 0)
												{
													$password=random_password();
													foreach($exam_admicard_details as $row)
													{
														$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'exam_date'=>$row['exam_date'],'session_time'=>$row['time']));
													
														$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
													
														//echo $this->db->last_query().'<br>';
														$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
													if($seat_number!='')
													{
														$final_seat_number = $seat_number;
														$update_data = array('mem_mem_no'=>$applicationNo,'pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
														$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
													}
													else
													{
														$log_title ="Fail user seat allocation id:".$applicationNo;
														$log_message = serialize($this->session->userdata['enduserinfo']['subject_arr']);
														$rId = $applicationNo;
														$regNo = $applicationNo;
														storedUserActivity($log_title, $log_message, $rId, $regNo);
													}
												}
													##############Get Admit card#############
													$admitcard_pdf=genarate_admitcard($applicationNo,$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
												}	
												else
												{
													redirect(base_url().'Nonreg/refund/'.base64_encode($MerchantOrderNo));
												}
											}
											
											
											if($exam_info[0]['exam_mode']=='ON')
											{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
											{$mode='Offline';}
											else{$mode='';}
											if($exam_info[0]['examination_date']!='0000-00-00')
											{
												$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
											}
											else
											{
												//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
												$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
												$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
											}
											//Query to get Medium	
											$this->db->where('exam_code',$exam_code);
											$this->db->where('exam_period',$exam_info[0]['exam_period']);
											$this->db->where('medium_code',$exam_info[0]['exam_medium']);
											$this->db->where('medium_delete','0');
											$medium=$this->master_model->getRecords('medium_master','','medium_description');
										
											//Query to get Payment details	
											$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNo),'transaction_no,date,amount,id');
											
											//Query to get user details
											$this->db->join('state_master','state_master.state_code=member_registration.state');
											//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
											$result=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,usrpassword');	
											
											$upd_files = array();
											$photo_file = 'p_'.$applicationNo.'.jpg';
											$sign_file = 's_'.$applicationNo.'.jpg';
											$proof_file = 'pr_'.$applicationNo.'.jpg';
											
											if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
											{	$upd_files['scannedphoto'] = $photo_file;	}
											
											if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
											{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
											
											if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
											{	$upd_files['idproofphoto'] = $proof_file;	}
											
											if(count($upd_files)>0)
											{
												$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
											}
									
											include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
											$key = $this->config->item('pass_key');
											$aes = new CryptAES();
											$aes->set_key(base64_decode($key));
											$aes->require_pkcs5();
											$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
										
											$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
											$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
											$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
											$sms_template_id = 'P6tIFIwGR';
											$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
											$newstring2 = str_replace("#REG_NUM#", "".$applicationNo."",$newstring1);
											$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
											$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
											$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
											$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
											$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
											$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
											$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
											$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
											$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
											$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
											$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
											$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
											$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
											$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
											$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
											$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
											$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
											$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring19);
											$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring20);
											
										
											$info_arr=array('to'=>$result[0]['email'],
																	'from'=>$emailerstr[0]['from'],
																	'subject'=>$emailerstr[0]['subject'],
																	'message'=>$final_str
																);
											//get invoice	
											$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
											//echo $this->db->last_query();exit;
											if(count($getinvoice_number) > 0)
											{
												if($getinvoice_number[0]['state_of_center']=='JAM')
												{
													$invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
													if($invoiceNumber)
													{
														$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
													}
												}
												else
												{
													$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
													if($invoiceNumber)
													{
														$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
													}
												}
												$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'member_no'=>$applicationNo,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
												$this->db->where('pay_txn_id',$payment_info[0]['id']);
												$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
												$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
			
											}	
											
											
											if($attachpath!='')
											{
												//send sms		
												$files=array($attachpath,$admitcard_pdf);			
												$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
												$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
												$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
												$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
												// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);				
												$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);		
												$this->Emailsending->mailsend_attch($info_arr,$files);
												//$this->Emailsending->mailsend($info_arr);
											 }
									 }
								}
								else if($payment_status==0)
								{
										// Handle transaction fail case 
										$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
										if($get_user_regnum[0]['status']==2)
										{
											$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' =>0399,'bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'S2S');
											$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
										
											//Query to get Payment details	
											$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id');
											$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
											
											$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
											$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
											$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
											$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
											$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.regnumber'=>$payment_info[0]['member_regnumber']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
										
											//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
											$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
											$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
											
											$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
											$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
											$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
											$sms_template_id = 'Jw6bOIQGg';
											$newstring1 = str_replace("#application_num#", "",  $emailerstr[0]['emailer_text']);
											$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
											$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
											$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
											
											$info_arr=array(	'to'=>$result[0]['email'],
																		'from'=>$emailerstr[0]['from'],
																		'subject'=>$emailerstr[0]['subject'],
																		'message'=>$final_str
																	);
											
											// send SMS
											$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
											$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
											// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
											$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);
											$this->Emailsending->mailsend($info_arr);
										}
									}
									}	
						else if($responsedata[6] == "IIBF_EXAM_DB")
						{
										sleep(8);
										$MerchantOrderNo = $responsedata[0]; 
										$transaction_no  = $responsedata[1];
										$payment_status = 2;
										$attachpath=$invoiceNumber='';
										
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
											// Handle transaction success case 
											/*	$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');*/
										  $get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
										if($get_user_regnum[0]['status']==2)
										{
											// Handle transaction success case 
											$exam_code=$get_user_regnum[0]['exam_code'];
											$reg_id=$get_user_regnum[0]['member_regnumber'];
											############check capacity is full or not ##########
											$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
											//$subject_arr=$this->session->userdata['enduserinfo']['subject_arr'];
											if(count($exam_admicard_details) > 0)
											{		
												$msg='';
												$sub_flag=1;
												$sub_capacity=1;
												foreach($exam_admicard_details as $row)
												{
													$capacity=check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
													if($capacity==0)
													{
														#########get message if capacity is full##########
														$this->db->trans_start(); 
														$update_data = array('transaction_no' => $transaction_no,'status' => 1,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
														$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
														$this->db->trans_complete();
														redirect(base_url().'Dbfuser/refund/'.base64_encode($MerchantOrderNo));
													}
												}
										}
								
											//$applicationNo = generate_dbf_reg_num(); 
											$applicationNo = generate_DBF_memreg($reg_id);
											######### payment Transaction ############
											$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
											$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
											
											##########Update Member Exam#############
											$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
											$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
											
											######update member_exam######
											$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
											$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
										
											//Query to get exam details	
										   $this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
											$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
											$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
											$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
											$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
											
											
											########## Generate Admit card and allocate Seat #############
											if(count($exam_admicard_details) > 0)
											{
												$password=random_password();
												foreach($exam_admicard_details as $row)
												{
													$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'exam_date'=>$row['exam_date'],'session_time'=>$row['time']));
													
													$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
													
													//echo $this->db->last_query().'<br>';
													$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
										
													if($seat_number!='')
													{
														$final_seat_number = $seat_number;
														$update_data = array('mem_mem_no'=>$applicationNo,'pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
														$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
													}
													else
													{
														$log_title ="Fail user seat allocation id:".$applicationNo;
														$log_message = serialize($this->session->userdata['enduserinfo']['subject_arr']);
														$rId = $applicationNo;
														$regNo = $applicationNo;
														storedUserActivity($log_title, $log_message, $rId, $regNo);
														//redirect(base_url().'Dbfuser/refund/'.base64_encode($MerchantOrderNo));
													}
												}
											##############Get Admit card#############
											$admitcard_pdf=genarate_admitcard($applicationNo,$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
											}	
											else
											{
												redirect(base_url().'Dbfuser/refund/'.base64_encode($MerchantOrderNo));
											}
												
											
											
											if($exam_info[0]['exam_mode']=='ON')
											{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
											{$mode='Offline';}
											else{$mode='';}
											//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
											$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
											$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
											//Query to get Medium	
											$this->db->where('exam_code',$exam_code);
											$this->db->where('exam_period',$exam_info[0]['exam_period']);
											$this->db->where('medium_code',$exam_info[0]['exam_medium']);
											$this->db->where('medium_delete','0');
											$medium=$this->master_model->getRecords('medium_master','','medium_description');
											//Query to get Payment details	
											$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNo),'transaction_no,date,amount,id');
											//Query to get user details
											$this->db->join('state_master','state_master.state_code=member_registration.state');
											//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
											$result=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto');	
										
											$upd_files = array();
											/*$photo_ext = end(explode('.',$result[0]['scannedphoto']));
											$sign_ext = end(explode('.',$result[0]['scannedsignaturephoto']));
											$id_ext = end(explode('.',$result[0]['idproofphoto']));
											
											$photo_file = 'p_'.$applicationNo.'.'.$photo_ext;
											$sign_file = 's_'.$applicationNo.'.'.$sign_ext;
											$proof_file = 'pr_'.$applicationNo.'.'.$id_ext;*/
											
											$photo_file = 'p_'.$applicationNo.'.jpg';
											$sign_file = 's_'.$applicationNo.'.jpg';
											$proof_file = 'pr_'.$applicationNo.'.jpg';
											
											if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
											{	$upd_files['scannedphoto'] = $photo_file;	}
											
											if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
											{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
											
											if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
											{	$upd_files['idproofphoto'] = $proof_file;	}
											
											if(count($upd_files)>0)
											{
												$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
											}
										
											$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
											$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
											$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
											$sms_template_id = 'P6tIFIwGR';
											$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
											$newstring2 = str_replace("#REG_NUM#", "".$applicationNo."",$newstring1);
											$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
											$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
											$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
											$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
											$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
											$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
											$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
											$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
											$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
											$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
											$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
											$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
											$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
											$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
											$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
											$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
											$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
											$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring19);
										
											$info_arr=array('to'=>$result[0]['email'],
																	'from'=>$emailerstr[0]['from'],
																	'subject'=>$emailerstr[0]['subject'],
																	'message'=>$final_str
																);
											
										//get invoice 	
											$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
											//echo $this->db->last_query();exit;
											if(count($getinvoice_number) > 0)
											{
												if($getinvoice_number[0]['state_of_center']=='JAM')
												{
												$invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
													if($invoiceNumber)
													{
														$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
													}
												}
												else
												{
												$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
													if($invoiceNumber)
													{
														$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
													}
												}
												$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'member_no'=>$applicationNo,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
												$this->db->where('pay_txn_id',$payment_info[0]['id']);
												$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
												$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
											}	
											if($attachpath!='')
											{		
												//send sms	
												$files=array($attachpath,$admitcard_pdf);					
												$sms_newstring = str_replace("#exam_name#", "".trim($exam_info[0]['description'])."",$emailerstr[0]['sms_text']);
												$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",$sms_newstring);
												$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",$sms_newstring1);
												$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",$sms_newstring2);
												// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
												$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);					
												$this->Emailsending->mailsend_attch($info_arr,$files);
												//$this->Emailsending->mailsend($info_arr);
												
											}
										}
									}
									else if($payment_status==0)
									{
											// Handle transaction fail case 
											$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
											if($get_user_regnum[0]['status']==2)
											{
												$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => 0399,'bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'S2S');
											$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
										
											//Query to get Payment details	
											$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id,member_regnumber');
											
											$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
											
											$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
											$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
											$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
											$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
											$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.regnumber'=>$payment_info[0]['member_regnumber']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
											
											//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
											$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
											$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
											
											$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
											$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
											$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
											$sms_template_id = 'Jw6bOIQGg';
											$newstring1 = str_replace("#application_num#", "",  $emailerstr[0]['emailer_text']);
											$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
											$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
											$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
											
											$info_arr=array(	'to'=>$result[0]['email'],
																		'from'=>$emailerstr[0]['from'],
						
																		'subject'=>$emailerstr[0]['subject'],
																		'message'=>$final_str
																	);
											
											// send SMS
											$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
											$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
											// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
											$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);
											$this->Emailsending->mailsend($info_arr);
											}
										}
									}		
						else if($responsedata[6] == "IIBF_EXAM_DB_EXAM")
						{
										sleep(8);
										$MerchantOrderNo = $responsedata[0]; 
										$transaction_no  = $responsedata[1];
										$payment_status = 2;
										$attachpath=$invoiceNumber='';
										
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
											// Handle transaction success case 
											$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
											if($get_user_regnum[0]['status']==2)
											{
												if(count($get_user_regnum) > 0)
											{
												$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
											}
											
											######### payment Transaction ############
											$this->db->trans_start();
											$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
											$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
											$this->db->trans_complete();
											
											//Query to get user details
											$this->db->join('state_master','state_master.state_code=member_registration.state');
											//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
											$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name');
											
											//Query to get exam details	
											$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
											$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
											$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
											$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
											$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
											
											########## Generate Admit card and allocate Seat #############
											$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
											if(count($exam_admicard_details) > 0)
											{
												############check capacity is full or not ##########
												//$subject_arr=$this->session->userdata['examinfo']['subject_arr'];
												if(count($exam_admicard_details) > 0)
												{	
													$msg='';
													$sub_flag=1;
													$sub_capacity=1;
													foreach($exam_admicard_details as $row)
													{
														$capacity=check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
														if($capacity==0)
														{
															#########get message if capacity is full##########
															redirect(base_url().'Dbf/refund/'.base64_encode($MerchantOrderNo));
														}
													}
												}
												
												$password=random_password();
												foreach($exam_admicard_details as $row)
												{
												$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'exam_date'=>$row['exam_date'],'session_time'=>$row['time']));
												
												$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
												
												//echo $this->db->last_query().'<br>';
												$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
										
													if($seat_number!='')
													{
														$final_seat_number = $seat_number;
														$update_data = array('pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
														$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
													}
													else
													{
														$log_title ="Fail user seat allocation id:".$get_user_regnum[0]['member_regnumber'];
														$log_message = serialize($this->session->userdata['examinfo']['subject_arr']);
														$rId = $get_user_regnum[0]['member_regnumber'];
														$regNo = $get_user_regnum[0]['member_regnumber'];
														storedUserActivity($log_title, $log_message, $rId, $regNo);
														//redirect(base_url().'Dbf/refund/'.base64_encode($MerchantOrderNo));
													}
												}
												
												##############Get Admit card#############
												$admitcard_pdf=genarate_admitcard($get_user_regnum[0]['member_regnumber'],$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
												######update member_exam transaction######
												$update_data = array('pay_status' => '1');
												$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
												}
											else
											{
												redirect(base_url().'Dbf/refund/'.base64_encode($MerchantOrderNo));
											}
											
											######update member_exam######	
											$update_data = array('pay_status' => '1');
											$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
											
											if($exam_info[0]['exam_mode']=='ON')
											{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
											{$mode='Offline';}
											else{$mode='';}
											//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
											$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
											$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
											//Query to get Medium	
											$this->db->where('exam_code',$exam_info[0]['exam_code']);
											$this->db->where('exam_period',$exam_info[0]['exam_period']);
											$this->db->where('medium_code',$exam_info[0]['exam_medium']);
											$this->db->where('medium_delete','0');
											$medium=$this->master_model->getRecords('medium_master','','medium_description');
											
											//Query to get Payment details	
											$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,id');
									
											$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
											$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
											$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
											$sms_template_id = 'P6tIFIwGR';
											$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
											$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
											$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
											$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
											$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
											$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
											$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
											$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
											$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
											$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
											$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
											$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
											$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
											$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
											$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
											$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
											$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
											$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
											$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
											$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring19);
										
											$info_arr=array('to'=>$result[0]['email'],
																	'from'=>$emailerstr[0]['from'],
																	'subject'=>$emailerstr[0]['subject'],
																	'message'=>$final_str
																);
												//get invoice	
											$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
											//echo $this->db->last_query();exit;
													if(count($getinvoice_number) > 0)
													{
													if($getinvoice_number[0]['state_of_center']=='JAM')
													{
														$invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
														if($invoiceNumber)
														{
															$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
														}
													}
													else
													{
														$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
														if($invoiceNumber)
														{
															$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
														}
													}
												$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
												$this->db->where('pay_txn_id',$payment_info[0]['id']);
												$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
												$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
											}	
											if($attachpath!='')
											{						
												$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
												$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
												$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
												$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
												// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
												$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);

												$this->Emailsending->mailsend_attch($info_arr,$attachpath);
												//$this->Emailsending->mailsend($info_arr);
											}
											}
										}
										else if($payment_status==0)
										{
											// Handle transaction fail case 
											$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
											if($get_user_regnum[0]['status']==2)
											{
												$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => 0399,'bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'S2S');
											$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
										
											$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
											
											//Query to get Payment details	
											$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
												
										   // Handle transaction 
											$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
											//Query to get exam details	
											$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
											$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
											$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
											$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
											$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
									
											//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
											$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
											$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
										
											$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
											$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
											$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
											$sms_template_id = 'Jw6bOIQGg';
											$newstring1 = str_replace("#application_num#", "".$get_user_regnum[0]['member_regnumber']."",  $emailerstr[0]['emailer_text']);
											$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
											$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
											$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
											
											$info_arr=array(	'to'=>$result[0]['email'],
																		'from'=>$emailerstr[0]['from'],
																		'subject'=>$emailerstr[0]['subject'],
																		'message'=>$final_str
																	);
											
											$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
											$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
											// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
											$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);

											$this->Emailsending->mailsend($info_arr);
											}
										}
									}		
						else if($responsedata[6] == "iibfdra")
						{
							sleep(8);
							$MerchantOrderNo = $responsedata[0];
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
								// Handle transaction sucess case 
								/*$get_user_regnum=$this->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
								if(count($get_user_regnum) > 0)
								{
									$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
								}*/
								//get payment transaction id
								$transdetail_det = $this->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'status,id');
								if($transdetail_det[0]['status']==2)
								{
									$transid = 0;
									if( count($transdetail_det) > 0 ) {
										$transdetail = $transdetail_det[0];
										$transid = $transdetail['id'];
										//echo "<BR>transid = ".$transid; 
										//get dra_member_exam_unique ids from dra_member_payment_transaction table
										$transmemdetails = $this->master_model->getRecords('dra_member_payment_transaction',array('ptid'=>$transid));
										//echo $this->db->last_query();
										//print_r($transmemdetails);
										if( count( $transmemdetails ) > 0 ) {
											foreach( $transmemdetails as $transmemdetail ) { //print_r($transmemdetail);
												$uniqueid = $transmemdetail['memexamid']; //unique id of dra_member_exam table
												$regidformemref = $this->master_model->getValue('dra_member_exam',array('id'=>$uniqueid),'regid');
												//echo "<BR>regidformemref = ".$regidformemref."  --  ".$uniqueid;
												$regnum = $this->master_model->getValue('dra_members',array('regid'=>$regidformemref),'regnumber');
												//echo "<BR>regnum = ".$regnum;
												if( empty( $regnum ) ) {
													//$regnumber = generate_dra_reg_num();
													//$regnumber = generate_nm_reg_num();
													$regnumber = generate_NM_memreg($regidformemref);
													$update_data = array('regnumber' => $regnumber);
													$this->master_model->updateRecord('dra_members',$update_data,array('regid'=>$regidformemref));
													//update uploaded file names which will include generated registration number
													//get cuurent saved file names from DB
													$currentpics = $this->master_model->getRecords('dra_members', array('regid'=>$regidformemref), 'scannedphoto, scannedsignaturephoto, idproofphoto, training_certificate, quali_certificate'); 									$scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $trainingphoto_file = $qualiphoto_file = '';
														
													if( count($currentpics) > 0 ) {
														$currentphotos = $currentpics[0];
														$scannedphoto_file = $currentphotos['scannedphoto'];
														$scannedsignaturephoto_file = $currentphotos['scannedsignaturephoto'];
														$idproofphoto_file = $currentphotos['idproofphoto'];
														$trainingphoto_file = $currentphotos['training_certificate'];
														$qualiphoto_file = $currentphotos['quali_certificate'];
													}
													$upd_files = array();
													$photo_file = 'p_'.$regnumber.'.jpg';
													$sign_file = 's_'.$regnumber.'.jpg';
													$proof_file = 'pr_'.$regnumber.'.jpg';
													$quali_file = 'degre_'.$regnumber.'.jpg';
													$training_file = 'traing_'.$regnumber.'.jpg';
													if( !empty( $scannedphoto_file ) ) {
														if(@ rename("./uploads/iibfdra/".$scannedphoto_file,"./uploads/iibfdra/".$photo_file))
														{	
															$upd_files['scannedphoto'] = $photo_file;	
														}
													}
													if( !empty( $scannedsignaturephoto_file ) ) {
														if(@ rename("./uploads/iibfdra/".$scannedsignaturephoto_file,"./uploads/iibfdra/".$sign_file))
														{	
															$upd_files['scannedsignaturephoto'] = $sign_file;	
														}
													}
													if( !empty( $idproofphoto_file ) ) {
														if(@ rename("./uploads/iibfdra/".$idproofphoto_file,"./uploads/iibfdra/".$proof_file))
														{	
															$upd_files['idproofphoto'] = $proof_file;	
														}
													}
													if( !empty( $qualiphoto_file ) ) {
														if(@ rename("./uploads/iibfdra/".$qualiphoto_file,"./uploads/iibfdra/".$quali_file))
														{	
															$upd_files['quali_certificate'] = $quali_file;	
														}
													}
													if( !empty( $trainingphoto_file ) ) {
														if(@ rename("./uploads/iibfdra/".$trainingphoto_file,"./uploads/iibfdra/".$training_file))
														{	
															$upd_files['training_certificate'] = $training_file;	
														}
													}
													if(count($upd_files)>0)
													{
														$this->master_model->updateRecord('dra_members',$upd_files,array('regid'=>$regidformemref));
													}							
												}
												
												$update_data = array('pay_status' => 1);
												$this->master_model->updateRecord('dra_member_exam',$update_data,array('id'=>$uniqueid));
												
												//echo "<BR>dra_member_exam id = ".$uniqueid;
											}
										}
									}
									
									$updated_date = date('Y-m-d H:i:s');
									
									$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2], 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'description' => $responsedata[7], 'updated_date' => $updated_date, 'callback'=>'S2S');
									//print_r($update_data);
									//echo "<BR>receipt_no = ".$MerchantOrderNo;
									$this->master_model->updateRecord('dra_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
									
									/******************* code added for GST Changes, by Bhagwan Sahane, on 05-07-2017 ***************/
						
									// get invoice
									$exam_invoice = $this->master_model->getRecords('exam_invoice',array('receipt_no' => $MerchantOrderNo,'pay_txn_id' => $transdetail_det[0]['id']),'invoice_id');
									
									if(count($exam_invoice) > 0)
									{
										// generate exam invoice no
										$invoice_no = generate_exam_invoice_number($exam_invoice[0]['invoice_id']);
										if($invoice_no)
										{
											$invoice_no = $this->config->item('exam_invoice_no_prefix').$invoice_no; // e.g. EXM/2017-18/000001
										}
										
										// update invoice details
										$invoice_update_data = array('invoice_no' => $invoice_no,'transaction_no' => $transaction_no,'date_of_invoice' => $updated_date,'modified_on' => $updated_date);
										$this->db->where('pay_txn_id',$transdetail_det[0]['id']);
										$this->master_model->updateRecord('exam_invoice',$invoice_update_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));
										
										log_dra_user($log_title = "Update DRA Exam Invoice Successful", $log_message = serialize($invoice_update_data));
										
										// generate invoice image
										$invoice_img_path = genarate_draexam_invoice($exam_invoice[0]['invoice_id']);
									}
									
									/******************* eof code added for GST Changes, by Bhagwan Sahane, on 05-07-2017 ***************/
								}
							}
							else if($payment_status==0)
							{
								$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2], 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'description' => $responsedata[7],'callback'=>'S2S');
								$this->master_model->updateRecord('dra_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								// Handle transaction fail case 
								
								$transdetail_det = $this->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$MerchantOrderNo));
								$transid = 0;
								if( count($transdetail_det) > 0 ) {
									$transdetail = $transdetail_det[0];
									$transid = $transdetail['id'];
									//echo "<BR>transid = ".$transid; 
									//get dra_member_exam_unique ids from dra_member_payment_transaction table
									$transmemdetails = $this->master_model->getRecords('dra_member_payment_transaction',array('ptid'=>$transid));
									//echo $this->db->last_query();
									//print_r($transmemdetails);
									if( count( $transmemdetails ) > 0 ) {
										foreach( $transmemdetails as $transmemdetail ) { //print_r($transmemdetail);
											$uniqueid = $transmemdetail['memexamid']; //unique id of dra_member_exam table
											$update_data = array('pay_status' => 0); //0 for fail
											$this->master_model->updateRecord('dra_member_exam',$update_data,array('id'=>$uniqueid));
											//echo "<BR>dra_member_exam id = ".$uniqueid;
										}
									}
								}
							}
						}
						
						else if ($responsedata[6] == "iibfamp")
						{
							sleep(8);
							$MerchantOrderNo = $responsedata[0]; 
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
									$get_user_regnum_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status, 	payment_option ');
								//check user payment status is updated by s2s or not
								if($get_user_regnum_info[0]['status']==2)
								{
									if($get_user_regnum_info[0]['payment_option']==1 || $get_user_regnum_info[0]['payment_option']==4)
									{
										$reg_id=$get_user_regnum_info[0]['ref_id'];
										//$applicationNo = generate_mem_reg_num();
										//Get membership number from 'amp_membershipno' and update in 'amp_candidates'
										$applicationNo =generate_amp_memreg($reg_id);
										//update amp registration table
										$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
										$this->master_model->updateRecord('amp_candidates',$update_mem_data,array('id'=>$reg_id));
										//get user information...
										$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id),'name,regnumber,email_id,photograph,signature,mobile_no,sponsor');
									
										$update_data = array('member_regnumber' => $applicationNo,'sponsor'=>$user_info[0]['sponsor'],'transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
										$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
										//get payment details
										
										//Query to get Payment details	
										$payment_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$user_info[0]['regnumber']),'transaction_no,date,amount');
								
										$upd_files = array();
										$photo_file = 'p_'.$applicationNo.'.jpg';
										$sign_file = 's_'.$applicationNo.'.jpg';
										$proof_file = 'pr_'.$applicationNo.'.jpg';
										
										if(@ rename("./uploads/amp/photograph/".$user_info[0]['scannedphoto'],"./uploads/amp/photograph/".$photo_file))
										{	$upd_files['scannedphoto'] = $photo_file;	}
										
										if(@ rename("./uploads/amp/signature/".$user_info[0]['scannedsignaturephoto'],"./uploads/amp/signature/".$sign_file))
										{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
								
										if(count($upd_files)>0)
										{
											$this->master_model->updateRecord('amp_candidates',$upd_files,array('id'=>$reg_id));
										}
									
								
								//email to user
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer'));
								$sms_template_id = 'N8YRKIwMg';
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
									'bcc'=>'skdutta@iibf.org.in,kavan@iibf.org.in'
									);
								//$this->send_mail($applicationNo);
								//$this->send_sms($applicationNo);
								
								//Manage Log
								
								
								$sms_newstring = str_replace("#fee#", "".$payment_info[0]['amount']."",  $emailerstr[0]['sms_text']);
								$sms_newstring1 = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring);
								$sms_final_str = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $sms_newstring1);
								// $this->master_model->send_sms($user_info[0]['mobile_no'],$sms_final_str);	
								$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);
								$this->Emailsending->mailsend($info_arr);
								redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
								}
								else
								{
									redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
								}
									}
									else if($get_user_regnum_info[0]['payment_option']==2 || $get_user_regnum_info[0]['payment_option']==3)
									{
										
										$payment_option='';
										if($get_user_regnum_info[0]['payment_option']== 2)
										{
											$payment_option='second';
										}
										else if($get_user_regnum_info[0]['payment_option']== 3)
										{
											$payment_option='full';
										}
										
										$reg_id=$get_user_regnum_info[0]['ref_id'];
										$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id),'name,regnumber,email_id,photograph,signature,mobile_no,sponsor');
										
										//update payment transaction
										$update_data = array('member_regnumber' => $user_info[0]['regnumber'],'sponsor'=>$user_info[0]['sponsor'],'transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
										$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
										
										//update amp registration table with installment status
										$update_mem_data = array('payment' =>$payment_option);
										$this->master_model->updateRecord('amp_candidates',$update_mem_data,array('id'=>$reg_id));
										
										//maintain log in for updated transaction
										$log_title ="Installment payment";
										$update_info['membershipno'] = $user_info[0]['regnumber'];
										$log_message = serialize($update_mem_data);
										$this->Ampmodel->create_log($log_title, $log_message);
							
										//Query to get Payment details	
										$payment_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$user_info[0]['regnumber']),'transaction_no,date,amount');
								
										//email to user
										$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer'));
										$sms_template_id = 'N8YRKIwMg';
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
											'bcc'=>'skdatta@iibf.org.in,kavan@iibf.org.in'
											);
											//$this->send_mail($applicationNo);
											//$this->send_sms($applicationNo);
									
										
										
										$sms_newstring = str_replace("#fee#", "".$payment_info[0]['amount']."",  $emailerstr[0]['sms_text']);
										$sms_newstring1 = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring);
										$sms_final_str = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $sms_newstring1);
										// $this->master_model->send_sms($user_info[0]['mobile_no'],$sms_final_str);	
										$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);
										
										$this->Emailsending->mailsend($info_arr);
										redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
									}
									else
									{
										redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
									}
								}
									}
							}
							else if($payment_status==0)
							{
								$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status');
								if($get_user_regnum_info[0]['status']==2)
								{
									$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
									$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
									//Manage Log
									$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
									$this->log_model->logamptransaction("sbiepay", $pg_response,$responsedata[2]);	
								}
							}
						}
						
						else
						{
							// TO Do:
						}
			
						// add payment responce in log
						if($responsedata[6] == "iibfdra")
						{
							$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=S2S";
							$this->log_model->logdratransaction("sbiepay", $pg_response, $responsedata[2]);
						}
						else if($responsedata[6] == "iibfamp")
						{
								$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=S2S";
								$this->log_model->logamptransaction("sbiepay", $pg_response, $responsedata[2]);
						}
						else
						{
							$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=S2S";
							$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
						}
					}
					else
					{
						die("Please try again...");
					}
			
					exit;
				}
			}
		}
		
		//##################### START >> Code Added By Bhagwan Sahane, on 08-09-2017 #####################
		
		// Cron End Logs
		$cron_end_time = date("Y-m-d H:i:s");
		//$cron_log_result = array("mark_as_success" => $mark_as_success, "mark_as_fail" => $mark_as_fail, "Start Time" => $cron_start_time, "End Time" => $cron_end_time);
		$cron_log_result = array("Start Time" => $cron_start_time, "End Time" => $cron_end_time);
		$cron_log_desc = json_encode($cron_log_result);
		$this->log_model->cronlog("IIBF Payment S2S Processing Cron Execution End", $cron_log_desc);
		// eof Cron End Logs
		
		$total_txn_list = implode(",",$order_id_arr);
		$mark_as_success_list = implode(",",$mark_as_success);
		$mark_as_fail_list = implode(",",$mark_as_fail);
		
		fwrite($fp1, "\n"."Total Txns = ".count($order_id_arr)."\n");
		fwrite($fp1, "\n"."Txns List = ".$total_txn_list."\n");
		
		fwrite($fp1, "\n"."Total Mark as Success = ".count($mark_as_success)."\n");
		fwrite($fp1, "\n"."Mark as Success List = ".$mark_as_success_list."\n");
		
		fwrite($fp1, "\n"."Total Mark as Fail = ".count($mark_as_fail)."\n");
		fwrite($fp1, "\n"."Mark as Fail = ".$mark_as_fail_list."\n");
			
		fwrite($fp1, "\n"."************************* IIBF Payment S2S Processing Cron Execution End ".$cron_end_time." *************************"."\n");
		fclose($fp1);
		
		//##################### END >> Code Added By Bhagwan Sahane, on 08-09-2017 #####################
	}
	
}