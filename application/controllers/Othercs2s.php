<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Othercs2s extends CI_Controller {

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

		$filehandle = fopen("other_cs2s_log/lock.txt", "c+");

		if (flock($filehandle, LOCK_EX | LOCK_NB)) {

			// code here to start the cron job

			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

			$this->load->library('excel');

			$key = $this->config->item('sbi_m_key');

			

			$aes = new CryptAES();

			$aes->set_key(base64_decode($key));

			$aes->require_pkcs5();

			$sms_template_id = '';

			echo $query='SELECT receipt_no FROM payment_transaction WHERE date <= NOW() - INTERVAL 60 MINUTE 

			AND date > NOW() - INTERVAL 240 MINUTE AND gateway = "sbiepay" AND status = 2 AND pg_flag IN("iibftrg","iibfcc","iibfcpd","iibfdupcer","iibfdup")';

			$crnt_day_txn_qry = $this->db->query($query);

			exit;

			echo "*********************************** New Cron Request Started***************************\n";

			echo  "Total Count =>". $crnt_day_txn_qry->num_rows();

			//echo $this->db->last_query();exit;

			if ($crnt_day_txn_qry->num_rows())

			{

				$start_time = date("Y-m-d H:i:s");

				$succ_cnt = $fail_cnt = $no_resp_cnt = $pending_cnt = 0;

				$succ_recp_arr = $fail_recp_arr = $no_resp_recp_arr = $pending_recp_arr = array();

				$todays_date = date("d-m-Y");

				$dir = 'other_cs2s_log/'.$todays_date;

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

					## Check other_c_s2s_log entry 

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

					$this->master_model->insertRecord('other_c_s2s_log', $resp_array);

					//echo $this->db->last_query();

					//exit;

				

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

					$responsedata[12]=$cust['1'];

					// Examination

					if($responsedata[12] == "iibfdup")

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

							

							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');

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

									/*if($getinvoice_number[0]['state_of_center']=='JAM')

									{

										$invoiceNumber = generate_duplicate_id_invoice_number_jammu($getinvoice_number[0]['invoice_id']);

										if($invoiceNumber)

										{

											$invoiceNumber=$this->config->item('Dup_Id_invoice_no_prefix_jammu').$invoiceNumber;

										}

									}

									else

									{*/

											$invoiceNumber = generate_duplicate_id_invoice_number($getinvoice_number[0]['invoice_id']);

											if($invoiceNumber)

											{

												$invoiceNumber=$this->config->item('Dup_Id_invoice_no_prefix').$invoiceNumber;

											}

									//	}

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

								$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2],'auth_code' => '0399', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');

							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

							}

							// Handle transaction fail case 

						}

					}

					else if($responsedata[12] == "iibfdupcer")

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

						{// Handle transaction sucess case 

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

							

							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');

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

													/*if($getinvoice_number[0]['state_of_center']=='JAM')

													{

													$invoiceNumber = generate_duplicate_cert_invoice_number_jammu($getinvoice_number[0]['invoice_id']);

														if($invoiceNumber)

														{

															$invoiceNumber=$this->config->item('Dup_cert_invoice_no_prefix_jammu').$invoiceNumber;

														}

													}

													else

													{*/

														$invoiceNumber = generate_duplicate_cert_invoice_number($getinvoice_number[0]['invoice_id']);

														if($invoiceNumber)

														{

															$invoiceNumber=$this->config->item('Dup_cert_invoice_no_prefix').$invoiceNumber;

														}

													//}

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

								$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2],'auth_code' => '0399', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');

							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

							}

							// Handle transaction fail case 

						}

					}

					else if ($responsedata[12] == "iibftrg")

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

							 $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id');

							//check user payment status is updated by S2S or not

							 if ($get_user_regnum_info[0]['status'] == 2) 

							{

								$reg_id        = $get_user_regnum_info[0]['ref_id'];

								$applicationNo = $get_user_regnum_info[0]['member_regnumber'];

								

								$update_data   = array(

									//'member_regnumber' => $applicationNo,

									'transaction_no' => $transaction_no,

									'status' => 1,

									'transaction_details' => $responsedata[2] . " - " . $responsedata[7],

									'auth_code' => '0300',

									'bankcode' => $responsedata[9],

									'paymode' =>  $responsedata[16],

									'callback' => 'c_S2S'

								);

								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

										

								$reg_data = $this->Master_model->getRecords('blended_registration', array('member_no' => $applicationNo,'blended_id' => $reg_id),'program_code,center_code,batch_code,training_type,venue_code,start_date');

								$selected_program_code = $reg_data[0]['program_code'];

								$selected_center_code = $reg_data[0]['center_code'];

								$venue_batch_code = $reg_data[0]['batch_code'];

								$selected_training_type = $reg_data[0]['training_type'];

								$selected_venue_code	= $reg_data[0]['venue_code'];		

								$sDate = $reg_data[0]['start_date'];

								// Check Registration Capacity 

								$RegCount = "";

								$RegCount = blendedRegistrationCapacity($selected_program_code,$selected_center_code,$venue_batch_code,$selected_training_type,$selected_venue_code,$sDate);

								

								//Get Venue Capacity 

								$capacity = "";

								$capacity = getVenueCapacity($selected_program_code,$selected_center_code,$venue_batch_code,$selected_training_type,$selected_venue_code,$sDate);

								

								// Get User Attempt

								$attemptQry=$this->db->query("SELECT attempt,fee_flag FROM blended_eligible_master WHERE member_number='".$applicationNo."' AND program_code = '" . $selected_program_code . "' LIMIT 1"); 

								$attemptArr = $attemptQry->row_array();

								$attempt = $attemptArr['attempt'];

								$fee_flag=$attemptArr['fee_flag'];

								// Check Count of Vitual Attempts 

								$VitualAttemptsCount = "";

								$VitualAttemptsCount = getVitualAttemptsCounts($applicationNo,$selected_program_code,$venue_batch_code);

								if($VitualAttemptsCount != 0)

								{

									$attempt = 1;

								}

									

			

								$attempt = $attempt+1;

								if($RegCount >= $capacity)

								{

									// Refundable

									$blended_data = array('pay_status' => 3, 'attempt'=>$attempt, 'modify_date' => date('Y-m-d H:i:s'));

									$this->master_model->updateRecord('blended_registration',$blended_data,array('blended_id'=>$reg_id));

									// User Log Activities  

									$log_title ="Blended Course Registraion - Capacity is full after payment success.";

									$log_message = $log_message = 'Program Code : '.$selected_program_code;

									$rId = $reg_id;

									$regNo = $applicationNo;

									storedUserActivity($log_title, $log_message, $rId, $regNo);

								}

								

								// Update Pay Status and User Attemp Status 

								$blended_data = array('pay_status'=>1, 'attempt'=>$attempt, 'modify_date'=>date('Y-m-d H:i:s'));

								$this->master_model->updateRecord('blended_registration',$blended_data,array('blended_id'=>$reg_id));

								

								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_blended_email'));
								$sms_template_id = 'Xb5EFSwGg';

								if (!empty($applicationNo)) {

									$user_info = $this->Master_model->getRecords('member_registration',array('regnumber'=> $applicationNo,'isactive'=>'1'),'email,mobile');

								}

								if (count($emailerstr) > 0) 

								{

									// Set Email Content For user 

									$Qry=$this->db->query("SELECT program_code, program_name, training_type, center_name, venue_name, start_date, end_date FROM blended_registration WHERE blended_id = '".$reg_id."' LIMIT 1");

									$detailsArr        = $Qry->row_array();

									$program_code = $detailsArr['program_code'];

									$program_name = $detailsArr['program_name'];

									$training_type = $detailsArr['training_type'];

									

									if($training_type=="PC"){

										$training_type='Physical Classroom';

										$venue_name   = $detailsArr['venue_name'];

									}

									else{

										$training_type='Virtual Classes';

										$venue_name   = '-';

									}

									$center_name  = $detailsArr['center_name'];

									$start_date1  = $detailsArr['start_date'];

									$end_date1    = $detailsArr['end_date'];

									$start_date   = date("d-M-Y", strtotime($start_date1));

									$end_date     = date("d-M-Y", strtotime($end_date1));

									$newstring    = str_replace("#program_name#","".$program_name."",$emailerstr[0]['emailer_text']);

									$newstring1   = str_replace("#training_type#","".$training_type."",$newstring);

									$newstring2   = str_replace("#center_name#","".$center_name."",$newstring1);

									$newstring3   = str_replace("#venue_name#","".$venue_name."",$newstring2);

									$newstring4   = str_replace("#start_date#","".$start_date."",$newstring3);

									$newstring5   = str_replace("#end_date#", "".$end_date."",$newstring4);

									

									// Set Email sending options 

									$info_arr          = array(

										'to' => $user_info[0]['email'],

										'from' => $emailerstr[0]['from'],

										'subject' => $emailerstr[0]['subject'],

										'message' => $newstring5

									);

									$getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo,'pay_txn_id' => $get_user_regnum_info[0]['id']));

									$zone_code = ""; 

									$zoneArr = array();

									//$regno = $this->session->userdata['memberdata']['regno'];

									$zoneArr = $this->master_model->getRecords('blended_registration',array('blended_id'=>$reg_id,'pay_status'=>1),'zone_code,gstin_no');

									$zone_code = $zoneArr[0]['zone_code'];

									

									$gstin_no          = $zoneArr[0]['gstin_no'];

									// Invoice Number Genarate Functinality 

									if (count($getinvoice_number) > 0){

										$invoiceNumber = generate_blended_invoice_number($getinvoice_number[0]['invoice_id'],$zone_code);

										if($invoiceNumber){$invoiceNumber = $this->config->item('blended_invoice_T'.$zone_code.'_prefix').$invoiceNumber;}

										$update_data = array(

											'invoice_no' => $invoiceNumber,

											//'member_no' => $applicationNo,

											'transaction_no' => $transaction_no,

											'date_of_invoice' => date('Y-m-d H:i:s'),

											'modified_on' => date('Y-m-d H:i:s')

										);

										$this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);

										$this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));

										//Invoice Genarate Function 

										$attachpath = genarate_blended_invoice($getinvoice_number[0]['invoice_id'],$zone_code,$program_name,$gstin_no);

										// User Log Activities  

										$log_title ="Blended Course Registration-Invoice Genarate";

										$log_message = serialize($update_data);

										$rId = $reg_id;

										$regNo = $applicationNo;

										storedUserActivity($log_title, $log_message, $rId, $regNo);

									}

									if ($attachpath != '') 

									{	

							//Email Send To Clints 

							

							if (!empty($applicationNo)) {

									$reg_info = $this->Master_model->getRecords('blended_registration',array('member_no'=> $applicationNo,'blended_id' => $reg_id));

								}

								$payment_infoArr=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$reg_info[0]['member_no']),'transaction_no,date,amount');

							

										if($reg_info[0]['member_no'] == $applicationNo)

										{

											$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'blended_emailer_client'));


											if(count($emailerSelfStr) > 0)

											{

												$designation_name=$undergraduate_name=$graduate_name=$postgraduate_name=$specify_qualification=$institution_name=$pay="";

												

												$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));

												$graduate=$this->master_model->getRecords('qualification', array('type' => 'GR'));

												$postgraduate=$this->master_model->getRecords('qualification', array('type'=> 'PG'));

												

												$institution_master = $this->master_model->getRecords('institution_master');

												$states             = $this->master_model->getRecords('state_master');

												$designation        = $this->master_model->getRecords('designation_master');

												if(count($designation)){

												 foreach($designation as $designation_row){

													if($reg_info[0]['designation']==$designation_row['dcode']){

														$designation_name = $designation_row['dname'];}

														} 

													}

												if(count($institution_master)){

												  foreach($institution_master as $institution_row){ 	

													if($reg_info[0]['associatedinstitute']==$institution_row['institude_id']){

														$institution_name = $institution_row['name'];}

													  }

													}

												

												if($reg_info[0]['qualification']=='U'){$undergraduate_name = 'Under Graduate';}

												if($reg_info[0]['qualification']=='G'){$graduate_name = 'Graduate';}

												if($reg_info[0]['qualification']=='P'){$postgraduate_name = 'Post Graduate';}	

												

												$qualificationArr=$this->master_model->getRecords('qualification',array('qid'=>$reg_info[0]['specify_qualification']),'name','','1');

												if(count($qualificationArr)) 

												{

													$specify_qualification = $qualificationArr[0]['name'];

												}

												

												$training_type = $reg_info[0]['training_type'];

												if($training_type=="PC")

												{

													$training_type='Physical Classroom';

													$venue_name   = $reg_info[0]['venue_name'];

												}

												else

												{

													$training_type='Virtual Classes';

													$venue_name   = "-";

												}

												$center_name  = $reg_info[0]['center_name'];

												

												$start_date   = date("d-M-Y", strtotime($reg_info[0]['start_date']));

												$end_date     = date("d-M-Y", strtotime($reg_info[0]['end_date']));

												

												if($reg_info[0]['pay_status'] == 1){ $pay = "Success";}

												$selfstr1 = str_replace("#regnumber#", "".$reg_info[0]['member_no']."",  $emailerSelfStr[0]['emailer_text']);

												$selfstr2 = str_replace("#program_name#", "".$reg_info[0]['program_name']."",  $selfstr1);

												$selfstr3 = str_replace("#center_name#", "".$center_name."",  $selfstr2);

												$selfstr4 = str_replace("#venue_name#", "".$venue_name."", $selfstr3);

												$selfstr5 = str_replace("#start_date#", "".$start_date."", $selfstr4);

												$selfstr6 = str_replace("#end_date#", "".$end_date."",  $selfstr5);

												

												$selfstr7 = str_replace("#training_type#", "".$training_type."",  $selfstr6);	

												$selfstr8 = str_replace("#name#", "".$reg_info[0]['namesub']." ".$reg_info[0]['firstname']." ".$reg_info[0]['middlename']." ".$reg_info[0]['lastname'],  $selfstr7);

												$selfstr9 = str_replace("#address1#", "".$reg_info[0]['address1']."",  $selfstr8);

												$selfstr10 = str_replace("#address2#", "".$reg_info[0]['address2']."",  $selfstr9);

												$selfstr11 = str_replace("#address3#", "".$reg_info[0]['address3']."",  $selfstr10);

												$selfstr12 = str_replace("#address4#", "".$reg_info[0]['address4']."",  $selfstr11);

												

												$selfstr13 = str_replace("#district#", "".$reg_info[0]['district']."",  $selfstr12);

												$selfstr14 = str_replace("#city#", "".$reg_info[0]['city']."",  $selfstr13);

												$selfstr15 = str_replace("#state#", "".$reg_info[0]['state']."",  $selfstr14);

												$selfstr16 = str_replace("#pincode#", "".$reg_info[0]['pincode']."",  $selfstr15);

												$selfstr17 = str_replace("#designation#", "".$designation_name."",  $selfstr16);

												$selfstr18 = str_replace("#associatedinstitute#", "".$institution_name."",  $selfstr17);

												$selfstr19 = str_replace("#dateofbirth#", "".$reg_info[0]['dateofbirth']."",  $selfstr18);

												$selfstr20 = str_replace("#email#", "".$reg_info[0]['email']."",  $selfstr19);

												$selfstr21 = str_replace("#mobile#", "".$reg_info[0]['mobile']."",  $selfstr20);

												$selfstr22 = str_replace("#res_stdcode#", "".$reg_info[0]['res_stdcode']."",  $selfstr21);

												$selfstr23 = str_replace("#residential_phone#", "".$reg_info[0]['residential_phone']."",  $selfstr22);

												$selfstr24 = str_replace("#stdcode#", "".$reg_info[0]['stdcode']."",  $selfstr23);

												$selfstr25 = str_replace("#office_phone#", "".$reg_info[0]['office_phone']."",  $selfstr24);

												$selfstr26 = str_replace("#qualification#", "".$undergraduate_name.$graduate_name.$postgraduate_name."",  $selfstr25);

												$selfstr27 = str_replace("#specify_qualification#", "".$specify_qualification."",  $selfstr26);

												$selfstr28 = str_replace("#emergency_name#", "".$reg_info[0]['emergency_name']."",  $selfstr27);

												$selfstr29 = str_replace("#emergency_contact_no#", "".$reg_info[0]['emergency_contact_no']."",  $selfstr28);

												$selfstr30 = str_replace("#blood_group#", "".$reg_info[0]['blood_group']."",  $selfstr29);

												$selfstr31 = str_replace("#TRANSACTION_NO#", "".$payment_infoArr[0]['transaction_no']."",  $selfstr30);

												$selfstr32 = str_replace("#AMOUNT#", "".$payment_infoArr[0]['amount']."",  $selfstr31);

												$selfstr33 = str_replace("#STATUS#", "Transaction Successful",  $selfstr32);

												$final_selfstr = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_infoArr[0]['date']))."",  $selfstr33);

												$s1 = str_replace("#program_code#", "".$reg_info[0]['program_code'],$emailerSelfStr[0]['subject']);

												$final_sub = str_replace("#Batch_code#", "".$reg_info[0]['batch_code']."",  $s1);

												// Get Client Emails Details 

												$emailsQry = $this->db->query("SELECT emails FROM offline_email_master WHERE zone_code = '" . $reg_info[0]['zone_code'] . "' AND program_code = '" . $reg_info[0]['program_code'] . "' AND batch_code = '" . $reg_info[0]['batch_code'] . "'AND isdelete = 0 LIMIT 1 ");

												$emailsArr    = $emailsQry->row_array();

												$emails  = $emailsArr['emails'];	

												

												$self_mail_arr = array(

												'to'=>$emails,

												'from'=>$emailerSelfStr[0]['from'],

												'subject'=> $final_sub,

												'message'=>$final_selfstr);	

												$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);

											}

										}

										// SMS Sending Code 

										$sms_newstring = str_replace("#program_name#", "" . $program_name . "", $emailerstr[0]['sms_text']);

										// $this->master_model->send_sms($user_info[0]['mobile'], $sms_newstring);
										$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']),$sms_newstring,$sms_template_id);

										if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {

										  

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

					else if($responsedata[12] == "iibfcpd")

					{

							sleep(8);

							$MerchantOrderNo = $responsedata[6];  

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

									$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');

									if($get_user_regnum[0]['status']==2)

									{

										######### payment Transaction ############

										$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');

										$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));

										if($this->db->affected_rows())

										{

											if(count($get_user_regnum) > 0)

											{

												$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'isactive'=>'1'),'regnumber,usrpassword,email');

											}

											

											///chiatli's code

										$created_on = date('Y-m-d H:i:s');

										$validate_upto  = date('Y-m-d H:i:s', strtotime('+2 years', strtotime($created_on)));

										$update_data = array('pay_status'=>'1','validate_upto'=>$validate_upto);

										$this->master_model->updateRecord('cpd_registration',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));

										

										$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'cpd'));



										if(count($emailerstr) > 0 && (count($get_user_regnum) > 0))

										{

											$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum[0]['id']));

								

											if(count($getinvoice_number) > 0)

											{ 

														$invoiceNumber = generate_cpd_invoice_number($getinvoice_number[0]['invoice_id']);

														if($invoiceNumber)

														{

															$invoiceNumber=$this->config->item('CPD_invoice_no_prefix').$invoiceNumber;

														}

													

												$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));

												$this->db->where('pay_txn_id',$get_user_regnum[0]['id']);

												$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));

												

												$attachpath=genarate_cpd_invoice($getinvoice_number[0]['invoice_id']);

											}

										

										if($attachpath!='')

										{	 

											$final_str = $emailerstr[0]['emailer_text'];

											$info_arr = array('to'=>$user_info[0]['email'],'from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'],'message'=>$final_str);

											

											if($this->Emailsending->mailsend_attch_cpd($info_arr,$attachpath))

											{

												redirect(base_url().'Cpd/acknowledge/'.base64_encode($MerchantOrderNo));

											}

											else

											{

												redirect(base_url('Cpd/acknowledge/'));

											}

										}

										else

										{

											redirect(base_url('Cpd/acknowledge/'));

										}	

									

								

											

											//Manage Log

										$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=C_S2S"; 

										$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);

										//$this->db->last_query();exit;

										//$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;

									}

								}

								else if($payment_status==0)

								{

									$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code');

									if($get_user_regnum[0]['status']==2)

									{

										$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2],'auth_code' => 0399,'bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'c_S2S');

										$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

									}

								 }				

								}

								// add payment responce in log

								if($responsedata[12] == "iibfdra")

								{

									$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=C_S2S";

									$this->log_model->logdratransaction("sbiepay", $pg_response, $responsedata[2]);

								}

								else if($responsedata[12] == "iibfamp")

								{

										$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=C_S2S";

										$this->log_model->logamptransaction("sbiepay", $pg_response, $responsedata[2]);

								}

								else

								{

									$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=C_S2S";

									$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);

								}

							}

							else

							{

								echo "Please try again...";

							}

					}//esle

					else if($responsedata[12] == "iibfcc")

					{

						## Contact classes code here

					}

					$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=C_S2S";

					$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);

					

				}

				else

				{

					//die("Please try again...");

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

				echo "Cron is already running";

			}

			fclose($filehandle);

	}

}

