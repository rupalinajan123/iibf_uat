<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CustomeS2S extends CI_Controller {

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

		//accedd denied due to GST

		//$this->master_model->warning();

	}

	

	// SBI ePay API for query transaction

	public function sbiqueryapi($MerchantOrderNo = "DP123369121")

	{

		$merchIdVal = $this->config->item('sbi_merchIdVal');

		$AggregatorId = $this->config->item('sbi_AggregatorId');

		$atrn  = "";

		$queryRequest  = $atrn."|".$merchIdVal."|".$MerchantOrderNo;

		

		//echo "<br><br> Webservice URL : ".$service_url = "https://test.sbiepay.com/payagg/orderStatusQuery/getOrderStatusQuery";

		$service_url = $this->config->item('sbi_status_query_api');

		echo "<br><br> Webservice params : ".$post_param = "queryRequest=".$queryRequest."&aggregatorId=".$AggregatorId."&merchantId=".$merchIdVal;

		$ch = curl_init();       

		curl_setopt($ch,CURLOPT_URL,$service_url);                                                 

		curl_setopt($ch, CURLOPT_POST, true); 

		curl_setopt($ch, CURLOPT_POSTFIELDS,$post_param);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      

		echo "<br><br>Web service response : ".$result = curl_exec($ch);

		

		$response_array = explode("|", $result);

		print_r($response_array);

		//var_dump($result);   

		curl_close($ch);

	}

	

	// SBI ePay API for Refund and Cancellation API

	public function sbirefundapi($MerchantOrderNo = "DP987787930")

	{

		$merchIdVal = $this->config->item('sbi_merchIdVal');

		$AggregatorId = $this->config->item('sbi_AggregatorId');

		$refundRequestId = "RID12345";

		$atrn   = "7008771670841";

		$refundAmount  = "2";

		$refundAmountCurrency = "INR";

		$refundRequest = $AggregatorId."|".$merchIdVal."|".$refundRequestId."|".$atrn."|".$refundAmount."|".$refundAmountCurrency."|".$MerchantOrderNo;

		

		echo "<br><br> Webservice URL : ".$service_url = "https://test.sbiepay.com/payagg/orderRefundCancellation/bookRefundCancellation";

		//$service_url = $this->config->item('sbi_refund_canc_api');

		

		echo "<br><br> Webservice params : ".$post_param = "refundRequest=".$refundRequest."&aggregatorId=".$AggregatorId."&merchantId=".$merchIdVal;

						

		$ch = curl_init();       

		curl_setopt($ch,CURLOPT_URL,$service_url);                                                 

		curl_setopt($ch, CURLOPT_POST, true); 

		curl_setopt($ch, CURLOPT_POSTFIELDS,$post_param); 

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      

		echo "<br><br>Web service response : ".$result = curl_exec($ch);

		

		$response_array = explode("|", $result);

		print_r($response_array);

		

		//var_dump($result);   

		curl_close($ch);

	}

	

	public function sbicancellationapi($MerchantOrderNo = "DP123369121")

	{

		echo "<BR>";

		echo "Order Number = ".generate_order_id($field_name = "reg_sbi_order_id");

		echo "<BR>";

		

		echo "<BR>";

		echo "Order Number = ".generate_order_id($field_name = "bd_exam_order_id");

		echo "<BR>";

		

		echo "<BR>";

		echo "Order Number = ".generate_order_id($field_name = "idcard_sbi_order_id");

		echo "<BR>";

		

		echo "<BR>";

		echo "DRA Order Number = ".generate_dra_order_id();

		echo "<BR>";

		

		echo "<BR>";

		echo "Member reg id sequense = ".generate_mem_reg_num();

		echo "<BR>";

		

		echo "<BR>";

		echo "Non Member reg id sequense = ".generate_nm_reg_num();

		echo "<BR>";

		

		echo "<BR>";

		echo "DBF Member reg id sequense = ".generate_dbf_reg_num();

		echo "<BR>";

		

		echo "<BR>";

		echo "DRA Member reg id sequense = ".generate_dra_reg_num();

		echo "<BR>";

		exit;

	}

	

	public function sbiquerytransaction($MerchantOrderNo = "DP123369121")

	{

		error_reporting(E_ALL);

		//include('CryptAES.php');

		//$this->load->library('CryptAES');

		include APPPATH . 'third_party/SBI_ePay/CryptAES.php';

		

		$key = $this->config->item('sbi_m_key');

		$merchIdVal = $this->config->item('sbi_merchIdVal');

		$AggregatorId = $this->config->item('sbi_AggregatorId');

		

		$aes = new CryptAES();

		$aes->set_key(base64_decode($key));

		$aes->require_pkcs5();

		

		$pg_reponse_url = base_url()."Payment/sbiquerysuccess";

		

		$data["pg_form_url"] = $this->config->item('sbi_query_form_url'); // SBI ePay form URL

		$data["merchIdVal"] = $merchIdVal;

		$data["aggIdVal"]   = $AggregatorId;

		

		$encryptQuery = "|".$merchIdVal."|".$MerchantOrderNo."|".$pg_reponse_url; // SBI encrypted form field value

		

		$data["encryptQuery"] = $aes->encrypt($encryptQuery);

print_r($data);

		$this->load->view('pg_sbi_trans_verify_form',$data);

	}

	

	public function sbiquerysuccess()

	{

		$_REQUEST['pushRespData'] = $_REQUEST['encStatusData'];

		// Only for testing purpose

		if (isset($_REQUEST['pushRespData']))

		{

			$this->load->model('log_model');

			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

			

			//$key = 'fBc5628ybRQf88f/aqDUOQ==';

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

				echo "<Br> encD = ".$encData = $_REQUEST['pushRespData'];

			}

			

			echo "<BR>".$merchIdVal;

			echo "<BR>".$Bank_Code;

			echo "<BR>".$encData = $aes->decrypt($_REQUEST['pushRespData']);

			$responsedata = explode("|",$encData);

			print_r($responsedata);

			if ($responsedata[12] == "MEM_REG")

			{

				$MerchantOrderNo = filter_var($responsedata[0], FILTER_SANITIZE_NUMBER_INT);//$responsedata[0];

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

				

				// Update member_regnumber, description, transaction_details, ref_id	

				

				/*$update_data = array(

					'transaction_no' => $transaction_no,

					'status' => $payment_status

				);*/

echo "MerchantOrderNo = ".$MerchantOrderNo; print_r($update_data);

				//echo $this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

			}

			else if($responsedata[12] == "DRA_EXAM")

			{

				

			}

			else

			{

				

			}

			// add payment responce in log

			$pg_response = "encStatusData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;

			$this->log_model->logtransaction("SBI_ePay", $pg_response, $responsedata[2]);

		}

		else

		{

			die("Please try again...");

		}

	}

	

	public function sbicallback()
	{

		//$filehandle = fopen("cs2s_log/lock.txt", "c+");

		

			// code here to start the cron job

			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

			$this->load->library('excel');

			$key = $this->config->item('sbi_m_key');

			

			$aes = new CryptAES();

			$aes->set_key(base64_decode($key));

			$aes->require_pkcs5();

			

			$query='SELECT receipt_no FROM payment_transaction WHERE  status = 2 AND receipt_no=902642626';

			$crnt_day_txn_qry = $this->db->query($query);

			echo "*********************************** New Cron Request Started***************************\n";

			echo  "Total Count =>". $crnt_day_txn_qry->num_rows();

			//echo $this->db->last_query();exit;

		if (true)

		{
			

			$start_time = date("Y-m-d H:i:s");

			$succ_cnt = $fail_cnt = $no_resp_cnt = $pending_cnt = 0;

			$succ_recp_arr = $fail_recp_arr = $no_resp_recp_arr = $pending_recp_arr = array();

			$todays_date = date("d-m-Y");

			$dir = 'cs2s_log/'.$todays_date;

			

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
				$c_row['receipt_no']='902642626';
				$responsedata = sbiqueryapi('902642626');

				$receipt_no=$c_row['receipt_no'];

				$encData=implode('|',$responsedata);

				$resp_data = json_encode($responsedata);

				## Check payment_c_s2s_log entry 

			

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

				// Examination
				

				if($responsedata[12]=='iibfexam')

				{

					if($cust['2']!='iibfdra')	// Not DRA Exam Application

					{

						$MerchantOrderNo = $responsedata[6];  

						$get_pg_flag=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'pg_flag');

						$responsedata[12]=$get_pg_flag[0]['pg_flag'];

					}

					else

					{

						$responsedata[12]=$cust['2'];

					}

				}

				

				// Registration

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

				
			 if($responsedata[12] == "IIBF_EXAM_O")

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

								$elective_subject_name='';

								$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
							
								///check for dual exam applied or not 

								

								////

								

								

								if($get_user_regnum[0]['status']==2)

								{

									

									///charter bank S2S call

									if($get_user_regnum[0]['exam_code']=='1016')

									{

											   $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(

							'receipt_no' => $MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

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

				/* Transaction Log */

				$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;

							$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);

				//echo $reg_id; die;

							

				$reg_data = $this->Master_model->getRecords('caiib_jaiib_newexam_registration', array('member_no' => $applicationNo, 'mem_exam_id' => $reg_id),'exam_code');

				$selected_exam_code =$reg_data[0]['exam_code'];

			   

				

				/* Get User Attempt */

				$attemptQry=$this->db->query("SELECT attempt,fee_flag FROM caiib_jaiib_newexam_eligible WHERE member_no='".$applicationNo."' AND exam_code = '" . $selected_exam_code . "' LIMIT 1"); 

				$attemptArr = $attemptQry->row_array();

				$attempt = $attemptArr['attempt'];

				$fee_flag=$attemptArr['fee_flag'];

				

	  

			$attempt = $attempt+1;

			   

				

				/* Update Pay Status and User Attemp Status */

				$blended_data = array('pay_status'=>1, 'attempt'=>$attempt, 'modify_date'=>date('Y-m-d H:i:s'));

				$memberexam_data = array('pay_status'=>1,  'modified_on'=>date('Y-m-d H:i:s'));

							   //$regno=$this->session->userdata['memberdata']['regno'];

							$this->master_model->updateRecord('caiib_jaiib_newexam_registration',$blended_data,array('mem_exam_id'=>$reg_id,'member_no'=>$applicationNo));

	$this->master_model->updateRecord('member_exam',$memberexam_data,array('id'=>$reg_id));

				

				$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'charterd_email'));

				if (!empty($applicationNo)) {

								$user_info = $this->Master_model->getRecords('member_registration',array('regnumber'=> $applicationNo,'isactive'=>'1'),'email,mobile');

							}

							if (count($emailerstr) > 0) 

				{

								/* Set Email Content For user */

								$Qry=$this->db->query("SELECT exam_code, qualification FROM caiib_jaiib_newexam_registration WHERE mem_exam_id = '".$reg_id."' LIMIT 1");

								$detailsArr        = $Qry->row_array();

				  $exam_code = $detailsArr['exam_code'];

								$exam_name ='Chartered Banker Intitute'; //$detailsArr['qualification'];

								$newstring    = str_replace("#exam_name#","".$exam_name."",$emailerstr[0]['emailer_text']);

				  /* Set Email sending options */

				  $info_arr          = array(

									'to' => $user_info[0]['email'],

									'from' => $emailerstr[0]['from'],

									'subject' => $emailerstr[0]['subject'],

									'message' => $newstring

								);

								$getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo,'pay_txn_id' => $get_user_regnum_info[0]['id']));

				  $zone_code = ""; 

				  $zoneArr = array();

				  //$regno = $this->session->userdata['memberdata']['regno'];

				  $zoneArr = $this->master_model->getRecords('caiib_jaiib_newexam_registration',array('mem_exam_id'=>$reg_id,'pay_status'=>1),'gstin_no');

				 

				  $gstin_no          = $zoneArr[0]['gstin_no'];

				  /* Invoice Number Genarate Functinality */

								if (count($getinvoice_number) > 0){

					$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);

							if($invoiceNumber)

							{

								$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;

							}

					

									$update_data = array(

										'invoice_no' => $invoiceNumber,

										'transaction_no' => $transaction_no,

										'date_of_invoice' => date('Y-m-d H:i:s'),

										'modified_on' => date('Y-m-d H:i:s')

									);

									$this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);

									$this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));

					/* Invoice Genarate Function */

									$attachpath = genarate_chartered_exam_invoice($getinvoice_number[0]['invoice_id']);

	$this->Emailsending->mailsend_attch($info_arr,$attachpath);

					/* User Log Activities  */

					$log_title ="Charterd accountant Registration-Invoice Genarate";

					$log_message = serialize($update_data);

					$rId = $reg_id;

					$regNo = $applicationNo;

					storedUserActivity($log_title, $log_message, $rId, $regNo);

								}

								if ($attachpath != '') 

				  { 

			  /* Email Send To Clints */

			  

			  if (!empty($applicationNo)) {

								$reg_info = $this->Master_model->getRecords('caiib_jaiib_newexam_registration',array('member_no'=> $applicationNo,'mem_exam_id' => $reg_id));

							}

				$payment_infoArr=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$reg_info[0]['member_no']),'transaction_no,date,amount');

			  

					if($reg_info[0]['member_no'] == $applicationNo)

					{

					  $emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'charterd_emailer_client'));

					  if(count($emailerSelfStr) > 0)

					  {

						$designation_name=$undergraduate_name=$graduate_name=$postgraduate_name=$specify_qualification=$institution_name=$pay="";

						

					   $qualification = $reg_info[0]['qualification'];

						

					   $exam_name ='Chartered Banker Intitute'; //$detailsArr['qualification'];

				  

					  

						if($reg_info[0]['pay_status'] == 1){ $pay = "Success";}

						$selfstr1 = str_replace("#regnumber#", "".$reg_info[0]['member_no']."",  $emailerSelfStr[0]['emailer_text']);

						$selfstr2 = str_replace("#exam_name#", "".$exam_name."",  $selfstr1);

					   $selfstr8 = str_replace("#name#", "".$reg_info[0]['namesub']." ".$reg_info[0]['firstname']." ".$reg_info[0]['middlename']." ".$reg_info[0]['lastname'],  $selfstr2);

						$selfstr9 = str_replace("#address1#", "".$reg_info[0]['address1']."",  $selfstr8);

						$selfstr10 = str_replace("#address2#", "".$reg_info[0]['address2']."",  $selfstr9);

						$selfstr11 = str_replace("#address3#", "".$reg_info[0]['address3']."",  $selfstr10);

						$selfstr12 = str_replace("#address4#", "".$reg_info[0]['address4']."",  $selfstr11);

						$selfstr13 = str_replace("#district#", "".$reg_info[0]['district']."",  $selfstr12);

						$selfstr14 = str_replace("#city#", "".$reg_info[0]['city']."",  $selfstr13);

						$selfstr15 = str_replace("#state#", "".$reg_info[0]['state']."",  $selfstr14);

						$selfstr16 = str_replace("#pincode#", "".$reg_info[0]['pincode']."",  $selfstr15);

						$selfstr19 = str_replace("#dateofbirth#", "".$reg_info[0]['dateofbirth']."",  $selfstr16);

						$selfstr20 = str_replace("#email#", "".$reg_info[0]['email']."",  $selfstr19);

						$selfstr21 = str_replace("#mobile#", "".$reg_info[0]['mobile']."",  $selfstr20);

						$selfstr26 = str_replace("#qualification#", "".$reg_info[0]['qualification']."",  $selfstr21);

						$selfstr31 = str_replace("#TRANSACTION_NO#", "".$payment_infoArr[0]['transaction_no']."",  $selfstr26);

						$selfstr32 = str_replace("#AMOUNT#", "".$payment_infoArr[0]['amount']."",  $selfstr31);

						$selfstr33 = str_replace("#STATUS#", "Transaction Successful",  $selfstr32);

						$final_selfstr = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_infoArr[0]['date']))."",  $emailerSelfStr[0]['subject']);

					 // $s1 = str_replace("#exam_code#", "".$reg_info[0]['exam_code'],$emailerSelfStr[0]['subject']);

						$final_sub = str_replace("#exam_code#", "".$reg_info[0]['exam_code']."",  $final_selfstr);

						/* Get Client Emails Details */

					   // $emailsQry = $this->db->query("SELECT emails FROM offline_email_master WHERE  exam_code = '" . $reg_info[0]['exam_code'] . "'AND isdelete = 0 LIMIT 1 ");

					   // $emailsArr    = $emailsQry->row_array();

					   // $emails  = $emailsArr['emails'];  

					  

						$self_mail_arr = array(

						'to'=>$emails,

						'from'=>$emailerSelfStr[0]['from'],

						'subject'=>$final_sub,

						'message'=>$final_selfstr); 

						$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);

					  }

					}

					

								} else { 

									redirect(base_url() . 'caiib_jaiib_reg/caiib_jaiib_reg_acknowledge/');

								}

							}

						}

									else

									{

										######### payment Transaction ############

										$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');

										$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));

										//echo $this->db->last_query();exit;

										

										if($this->db->affected_rows())

										{

											$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

											if($get_payment_status[0]['status']==1)

											{

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

											if($exam_info[0]['exam_code']!=101 || $exam_info[0]['exam_code']!=990 || $exam_info[0]['exam_code']!=993)

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

															$log_title ="Capacity full id:".$get_user_regnum[0]['member_regnumber'];

															$log_message = serialize($exam_admicard_details);

															$rId = $get_user_regnum[0]['ref_id'];

															$regNo = $get_user_regnum[0]['member_regnumber'];

															storedUserActivity($log_title, $log_message, $rId, $regNo);

															//redirect(base_url().'Home/refund/'.base64_encode($MerchantOrderNo));

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

															$admit_card_details=$this->master_model->getRecords('admit_card_details',array('admitcard_id'=>$admit_card_details[0]['admitcard_id'],'remark'=>1));

															if(count($admit_card_details) > 0)

															{

																$log_title ="Seat number already allocated id:".$get_user_regnum[0]['member_regnumber'];

																$log_message = serialize($exam_admicard_details);

																$rId = $admit_card_details[0]['admitcard_id'];

																$regNo = $get_user_regnum[0]['member_regnumber'];

																storedUserActivity($log_title, $log_message, $rId, $regNo);

															}

															else

															{

																$log_title ="Fail user seat allocation id:".$get_user_regnum[0]['member_regnumber'];

																$log_message = serialize($exam_admicard_details);

																$rId = $get_user_regnum[0]['member_regnumber'];

																$regNo = $get_user_regnum[0]['member_regnumber'];

																storedUserActivity($log_title, $log_message, $rId, $regNo);

																//redirect(base_url().'Home/refund/'.base64_encode($MerchantOrderNo));

															}

														}

													}

												}

												else

												{

													//redirect(base_url().'Home/refund/'.base64_encode($MerchantOrderNo));

												}

					}

											######update member_exam######

											if($get_payment_status[0]['status']==1){

												$update_data = array('pay_status' => '1');

												$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));

												$log_title ="C_S2S member exam Update:".$get_user_regnum[0]['ref_id'];

												$log_message = $this->db->last_query();

												$rId = $get_user_regnum[0]['ref_id'];

												$regNo = $get_user_regnum[0]['ref_id'];

												storedUserActivity($log_title, $log_message, $rId, $regNo);

											

											}else{

												

												$log_title ="C_S2S member exam Update fail:".$get_user_regnum[0]['ref_id'];

												$log_message = serialize($get_payment_status[0]['status']);

												$rId = $get_user_regnum[0]['ref_id'];

												$regNo = $get_user_regnum[0]['ref_id'];

												storedUserActivity($log_title, $log_message, $rId, $regNo);

												

											}

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

												$elern_msg_string=$this->master_model->getRecords('elearning_examcode');

												if(count($elern_msg_string) > 0)

												{

													foreach($elern_msg_string as $row)

													{

														$arr_elern_msg_string[]=$row['exam_code'];

													}

													if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))

													{

														$newstring22 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring21);		

													}

													else

													{

														$newstring22 = str_replace("#E-MSG#", '',$newstring21);		

													}

												}

												else

												{

													$newstring22 = str_replace("#E-MSG#", '',$newstring21);

												}

												$final_str = str_replace("#MODE#", "".$mode."",$newstring22);

										 }

										else

										{

											if($exam_info[0]['exam_code']==990)

											{

												$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'DISA_emailer'));

												$final_str = $emailerstr[0]['emailer_text'];

											}else if($exam_info[0]['exam_code']==993)

											{

												$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'Cisi_emailer'));

												$final_str = $emailerstr[0]['emailer_text'];

											}

											else

											{

												$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'apply_exam_transaction_success'));

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

												$elern_msg_string=$this->master_model->getRecords('elearning_examcode');

												if(count($elern_msg_string) > 0)

												{

													foreach($elern_msg_string as $row)

													{

														$arr_elern_msg_string[]=$row['exam_code'];

													}

													if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))

													{

														$newstring22 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring21);		

													}

													else

													{

														$newstring22 = str_replace("#E-MSG#", '',$newstring21);		

													}

												}

												else

												{

													$newstring22 = str_replace("#E-MSG#", '',$newstring21);

												}

												$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring22);

											}

										}

										$info_arr=array('to'=>$result[0]['email'],

																'from'=>$emailerstr[0]['from'],

																'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],

																'message'=>$final_str

															);

										//echo $final_str; exit;

										//get invoice	

									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));

									//echo $this->db->last_query();exit;

									########### generate invoice ###########

									if(count($getinvoice_number) > 0)

									{

										$invoiceNumber ='';	

										

										/*if($getinvoice_number[0]['state_of_center']=='JAM')

										{

											$invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);

											if($invoiceNumber)

											{

												$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;

											}

										}

										else

										{*/

											if($exam_info[0]['exam_code']==990)

											{

												if($get_payment_status[0]['status']==1){

												$invoiceNumber =generate_DISA_invoice_number($getinvoice_number[0]['invoice_id']);

												if($invoiceNumber)

												{

													$invoiceNumber=$this->config->item('DISA_invoice_no_prefix').$invoiceNumber;

												}

												}

											}

											else if($exam_info[0]['exam_code']==993)

											{

												if($get_payment_status[0]['status']==1){

												$invoiceNumber =generate_CISI_invoice_number($getinvoice_number[0]['invoice_id']);

												if($invoiceNumber)

												{

												$invoiceNumber=$this->config->item('Cisi_invoice_no_prefix').$invoiceNumber;

												}

												}

											}

											else

											{

												

												if($get_payment_status[0]['status']==1){

												//$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);

												

												$log_title ="C_S2S exam invoice generate number:".$getinvoice_number[0]['invoice_id'];

												$log_message = $this->db->last_query();

												$rId = $MerchantOrderNo;

												$regNo = $get_user_regnum[0]['member_regnumber'];

												storedUserActivity($log_title, $log_message, $rId, $regNo);

												

												if($invoiceNumber)

												{

													$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;

												}

												}else{

													

													$log_title ="C_S2S exam invoice generate number fail:".$getinvoice_number[0]['invoice_id'];

													$log_message = $MerchantOrderNo;

													$rId = $MerchantOrderNo;

													$regNo = $get_user_regnum[0]['member_regnumber'];

													storedUserActivity($log_title, $log_message, $rId, $regNo);

													

												}

											}

										//}

										

										if($get_payment_status[0]['status']==1){

										

										/*$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));

										$this->db->where('pay_txn_id',$payment_info[0]['id']);

										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
*/
										

										$log_title ="C_S2S exam invoice Update:".$get_user_regnum[0]['member_regnumber'];

										$log_message = $this->db->last_query();

										$rId = $MerchantOrderNo;

										$regNo = $get_user_regnum[0]['member_regnumber'];

										storedUserActivity($log_title, $log_message, $rId, $regNo);

										

										}else{

											

											$log_title ="C_S2S exam invoice Update fail:".$get_user_regnum[0]['member_regnumber'];

											$log_message = $get_payment_status[0]['status'];

											$rId = $MerchantOrderNo;

											$regNo = $get_user_regnum[0]['member_regnumber'];

											storedUserActivity($log_title, $log_message, $rId, $regNo);

											

										}

										

										

										

										if($exam_info[0]['exam_code']==990)

										{

											$attachpath=genarate_DISA_invoice($getinvoice_number[0]['invoice_id']);	

										}else if($exam_info[0]['exam_code']==993)

										{

											$attachpath=genarate_CISI_invoice($getinvoice_number[0]['invoice_id']);

										}

										else

										{

											$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);

										}

										if($exam_info[0]['exam_code']!=101 || $exam_info[0]['exam_code']!=990 || $exam_info[0]['exam_code']!=993)

										{

												##############Get Admit card#############

												$admitcard_pdf=genarate_admitcard($get_user_regnum[0]['member_regnumber'],$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);

										}

									}	

									if($attachpath!='')

									{		

											$files=array($attachpath,$admitcard_pdf);

											if($exam_info[0]['exam_code']==990 || $exam_info[0]['exam_code']==993)

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

											$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	

											$this->Emailsending->mailsend_attch($info_arr,$files);

										//$this->Emailsending->mailsend($info_arr);

										}

										}

										}

										else

										{

											$log_title ="C_S2S Update fail:".$get_user_regnum[0]['member_regnumber'];

											$log_message = serialize($update_data);

											$rId = $MerchantOrderNo;

											$regNo = $get_user_regnum[0]['member_regnumber'];

											storedUserActivity($log_title, $log_message, $rId, $regNo);	

										}

									}

								}

							}

							else if($payment_status==0)

							{

								$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code');

								if($get_user_regnum[0]['status']==2)

								{

									$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2],'auth_code' => 0399,'bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'c_S2S');

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

									$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	

									$this->Emailsending->mailsend($info_arr);

									}

								}

							 }				

							}

							

				else if($responsedata[12] == "IIBF_EXAM_NM")

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

								// Handle transaction success case 

								$exam_period_date=$attachpath=$invoiceNumber='';

								$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

									///check for duplicate entry

								/*	$cnt=0;

									$today_date=date('Y-m-d');

									$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');

									$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');

									$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");

									$this->db->where('exam_master.elg_mem_nm','Y');

									$this->db->where('pay_status','1');

									$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>$get_user_regnum[0]['exam_code'],'regnumber'=>$get_user_regnum[0]['member_regnumber']));

									$cnt = count($applied_exam_info);

								if($cnt)

								{

									

									$insert_data = array();

									$insert_data = array(

													"exam_code"        => $get_user_regnum[0]['exam_code'], 

													"exam_period"      => $applied_exam_info[0]['exam_period'],  

													"member_regnumber" => $get_user_regnum[0]['member_regnumber'],

													"ref_id"           => $get_user_regnum[0]['ref_id'] ,

													"receipt_no"       => $get_user_regnum[0]['receipt_no'] ,

													"pay_type"         => $get_user_regnum[0]['pay_type'] ,

													"status"           => $get_user_regnum[0]['status'] ,

													"pay_status"       => isset($get_user_regnum[0]['pay_type'])&&$get_user_regnum[0]['status']!=''?$get_user_regnum[0]['status']:0, 

													'transaction_no'   => $transaction_no ,

													'payment_date' 	   => $get_user_regnum[0]['date'] ,

													'is_new_record'    => 'new_record_added_by_us',

													'str_reason'       => 'payment status from S2S',

													'record_type'      => "exam_invoice",

													'refund_case' => '1'

												);

									///checked transaction number already present or not	

									$get_transaction_detail = $this->master_model->getRecordCount('exam_invoice_settlement', array(

										'transaction_no' => $transaction_no));

									if($get_transaction_detail<=0)

									{

										$this->master_model->insertRecord('exam_invoice_settlement',$insert_data);	

									}

									exit;		

								}*/

					

								///check duplcate entry end

						

								

								

								if($get_user_regnum[0]['status']==2)

							{

							######### payment Transaction ############

							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');

							$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));

							if($this->db->affected_rows())

							{

								

								$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

							if($get_payment_status[0]['status']==1)

							{

							if(count($get_user_regnum) > 0)

							{

							$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');

							}

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

							if($exam_info[0]['exam_code']!=101 || $exam_info[0]['exam_code']!=990 || $exam_info[0]['exam_code']!=993)

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

											$log_title ="Capacity full id:".$get_user_regnum[0]['member_regnumber'];

											$log_message = serialize($exam_admicard_details);

											$rId = $get_user_regnum[0]['ref_id'];

											$regNo = $get_user_regnum[0]['member_regnumber'];

											storedUserActivity($log_title, $log_message, $rId, $regNo);

											//redirect(base_url().'NonMember/refund/'.base64_encode($MerchantOrderNo));

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

								}		

								else

								{

								//redirect(base_url().'NonMember/refund/'.base64_encode($MerchantOrderNo));

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

								$final_str = $emailerstr[0]['emailer_text'];

							}

							else if($exam_info[0]['exam_code']==993)

							{

								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'Cisi_emailer'));

								$final_str = $emailerstr[0]['emailer_text'];

							}

							else

							{

								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));

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

								$elern_msg_string=$this->master_model->getRecords('elearning_examcode');

								if(count($elern_msg_string) > 0)

								{

									foreach($elern_msg_string as $row)

									{

										$arr_elern_msg_string[]=$row['exam_code'];

									}

									if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))

									{

										$newstring21 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring20);		

									}

									else

									{

										$newstring21 = str_replace("#E-MSG#", '',$newstring20);		

									}

								}

								else

								{

									$newstring21 = str_replace("#E-MSG#", '',$newstring20);

								}

							

								$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);

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

								/*if($getinvoice_number[0]['state_of_center']=='JAM')

								{

									$invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);

									if($invoiceNumber)

									{

										$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;

									}

								}

								else

								{*/

									if($exam_info[0]['exam_code']==990)

									{

										$invoiceNumber =generate_DISA_invoice_number($getinvoice_number[0]['invoice_id']);

										if($invoiceNumber)

										{

											$invoiceNumber=$this->config->item('DISA_invoice_no_prefix').$invoiceNumber;

										}

									}

									else if($exam_info[0]['exam_code']==993)

										{

											$invoiceNumber =generate_CISI_invoice_number($getinvoice_number[0]['invoice_id']);

											if($invoiceNumber)

											{

											$invoiceNumber=$this->config->item('Cisi_invoice_no_prefix').$invoiceNumber;

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

								

									

								//}

							$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'));

							$this->db->where('pay_txn_id',$payment_info[0]['id']);

							$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));

							if($exam_info[0]['exam_code']==990)

							{

								$attachpath=genarate_DISA_invoice($getinvoice_number[0]['invoice_id']);

							}

							else if($exam_info[0]['exam_code']==993)

							{

								$attachpath=genarate_CISI_invoice($getinvoice_number[0]['invoice_id']);

							}

							else

							{

								$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);

							}

							if($exam_info[0]['exam_code']!=101 || $exam_info[0]['exam_code']!=990 || $exam_info[0]['exam_code']!=993)

								{

									##############Get Admit card#############

									$admitcard_pdf=genarate_admitcard($get_user_regnum[0]['member_regnumber'],$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);

								}

							}		

							if($attachpath!='')

							{	

							$files=array($attachpath,$admitcard_pdf);	

							if($exam_info[0]['exam_code']==990 || $exam_info[0]['exam_code']==993)

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

							

							$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	

							$this->Emailsending->mailsend_attch($info_arr,$files);

							//$this->Emailsending->mailsend($info_arr);

							}

							}

							}

									else

									{

										$log_title ="S2S Update fail:".$get_user_regnum[0]['member_regnumber'];

										$log_message = serialize($update_data);

										$rId = $MerchantOrderNo;

										$regNo = $get_user_regnum[0]['member_regnumber'];

										storedUserActivity($log_title, $log_message, $rId, $regNo);	

					

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

									$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2],'auth_code' => 0399,'bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'c_S2S');

									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

									if($get_user_regnum[0]['exam_code']!='990')

									{

										$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');

										

										//Query to get Payment details	

										$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');

										

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

										$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	

										$this->Emailsending->mailsend($info_arr);

									}

								}

							}

						}

				else if($responsedata[12] == "IIBF_EXAM_REG")

				{

								sleep(8);

								$MerchantOrderNo = $responsedata[6];  

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

								######### payment Transaction ############

								$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');

								$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));

								if($this->db->affected_rows())

								{

									$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');

									if($get_payment_status[0]['status']==1)

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

													#Add code trans_start & trans_complete : pooja  #

													$this->db->trans_start(); 

													$update_data = array('transaction_no' => $transaction_no,'status' => 1,'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');

													$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

													$this->db->trans_complete();

													//redirect(base_url().'Nonreg/refund/'.base64_encode($MerchantOrderNo));

												}

											}

										}

									}

										

									//$applicationNo = generate_nm_reg_num();

									$applicationNo = generate_NM_memreg($reg_id);

									

									######### payment Transaction ############

									$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');

									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

									

									######### update application number to Registration table#########

									$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);

									$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));

									

									######### update application number to member exam#########

									$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);

									$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));

									

									

									//Query to get exam details	

									$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');

									$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');

									$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');

									$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');

									$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.examination_date');

									

						

									

									

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

												$log_message = serialize($exam_admicard_details);

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

											//redirect(base_url().'Nonreg/refund/'.base64_encode($MerchantOrderNo));

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

									

									########get Old image Name############

									$log_title ="Non member OLD Image :".$reg_id;

									$log_message = serialize($result);

									$rId = $reg_id;

									$regNo = $reg_id;

									storedUserActivity($log_title, $log_message, $rId, $regNo);

									

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

										$log_title ="Non member PICS Update S2S :".$reg_id;

										$log_message = serialize($upd_files);

										$rId = $reg_id;

										$regNo = $reg_id;

										storedUserActivity($log_title, $log_message, $rId, $regNo);	

									}

									else

									{

										$upd_files['scannedphoto'] = $photo_file;

										$upd_files['scannedsignaturephoto'] = $sign_file;	

										$upd_files['idproofphoto'] = $proof_file;

										$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));

										$log_title ="Non member PICS MANUAL PICS Update S2S :".$reg_id;

										$log_message = serialize($upd_files);

										$rId = $reg_id;

										$regNo = $reg_id;

										storedUserActivity($log_title, $log_message, $rId, $regNo);	

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

									$elern_msg_string=$this->master_model->getRecords('elearning_examcode');

									if(count($elern_msg_string) > 0)

									{

										foreach($elern_msg_string as $row)

										{

											$arr_elern_msg_string[]=$row['exam_code'];

										}

										if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))

										{

											$newstring21 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring20);		

										}

										else

										{

											$newstring21 = str_replace("#E-MSG#", '',$newstring20);		

										}

									}

									else

									{

										$newstring21 = str_replace("#E-MSG#", '',$newstring20);

									}

							

									$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);

									

								

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

										/*if($getinvoice_number[0]['state_of_center']=='JAM')

										{

											$invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);

											if($invoiceNumber)

											{

												$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;

											}

										}

										else

										{*/

											$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);

											if($invoiceNumber)

											{

												$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;

											}

										//}

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

										$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);						

										$this->Emailsending->mailsend_attch($info_arr,$files);

										//$this->Emailsending->mailsend($info_arr);

									}	

								}

								}

								else

								{

									$log_title ="S2S Update fail:".$get_user_regnum[0]['member_regnumber'];

									$log_message = serialize($update_data);

									$rId = $MerchantOrderNo;

									$regNo = $get_user_regnum[0]['member_regnumber'];

									storedUserActivity($log_title, $log_message, $rId, $regNo);	

								}

							 }

						}

						else if($payment_status==0)

						{

								// Handle transaction fail case 

								$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');

								if($get_user_regnum[0]['status']==2)

								{

									$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2],'auth_code' =>0399,'bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'c_S2S');

									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

								

									//Query to get Payment details	

									$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id');

									$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');

									

									$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');

									$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');

									$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');

									$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');

									$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.regnumber'=>$payment_info[0]['member_regnumber']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');

								

									//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');

									$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);

									$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);

									

									$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];

									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);

									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));

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

									$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	

									$this->Emailsending->mailsend($info_arr);

								}

							}

							}	

				else if($responsedata[12] == "IIBF_EXAM_DB")

				{

								sleep(8);

								$MerchantOrderNo = $responsedata[6];  

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

									######### payment Transaction ############

									$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');

									$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));

									if($this->db->affected_rows())

									{

										$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');

										

										if($get_payment_status[0]['status']==1)

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

													$update_data = array('transaction_no' => $transaction_no,'status' => 1,'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');

													$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

													$this->db->trans_complete();

													//redirect(base_url().'Dbfuser/refund/'.base64_encode($MerchantOrderNo));

												}

											}

									}

							

										//$applicationNo = generate_dbf_reg_num(); 

										$applicationNo = generate_DBF_memreg($reg_id);

										######### payment Transaction ############

										$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');

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

										$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');

										

										

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

													$log_message = serialize($exam_admicard_details);

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

											//redirect(base_url().'Dbfuser/refund/'.base64_encode($MerchantOrderNo));

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

										$elern_msg_string=$this->master_model->getRecords('elearning_examcode');

										if(count($elern_msg_string) > 0)

										{

											foreach($elern_msg_string as $row)

											{

												$arr_elern_msg_string[]=$row['exam_code'];

											}

											if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))

											{

												$newstring20 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring19);		

											}

											else

											{

												$newstring20 = str_replace("#E-MSG#", '',$newstring19);		

											}

										}

										else

										{

											$newstring20 = str_replace("#E-MSG#", '',$newstring19);

										}

							

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

											/*if($getinvoice_number[0]['state_of_center']=='JAM')

											{

												$invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);

												if($invoiceNumber)

												{

													$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;

												}

											}

											else

											{*/

												$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);

												if($invoiceNumber)

												{

													$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;

												}

											//}

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

											$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);						

											$this->Emailsending->mailsend_attch($info_arr,$files);

										}

										}

									}

									else

									{

										$log_title ="S2S Update fail:".$get_user_regnum[0]['member_regnumber'];

										$log_message = serialize($update_data);

										$rId = $MerchantOrderNo;

										$regNo = $get_user_regnum[0]['member_regnumber'];

										storedUserActivity($log_title, $log_message, $rId, $regNo);	

									}

								}

							}

							else if($payment_status==0)

							{

									// Handle transaction fail case 

									$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');

									if($get_user_regnum[0]['status']==2)

									{

										$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2],'auth_code' => 0399,'bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'c_S2S');

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

									$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	

									$this->Emailsending->mailsend($info_arr);

									}

								}

							}		

				else if($responsedata[12] == "IIBF_EXAM_DB_EXAM")

				{

								sleep(8);

								$MerchantOrderNo = $responsedata[6];  

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

									$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

									/*///check for duplicate entry

									$cnt=0;

									//check where exam alredy apply or not

									$today_date=date('Y-m-d');

									$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');

									$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');

									//$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');

									$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");

									$this->db->where('exam_master.elg_mem_db','Y');

									$this->db->where('pay_status','1');

									$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>$get_user_regnum[0]['exam_code'],'regnumber'=>$get_user_regnum[0]['member_regnumber']));

									$cnt = count($applied_exam_info);

									if($cnt)

									{

									

									$insert_data = array();

									$insert_data = array(

													"exam_code"        => $get_user_regnum[0]['exam_code'], 

													"exam_period"      => $applied_exam_info[0]['exam_period'], 

													"member_regnumber" => $get_user_regnum[0]['member_regnumber'],

													"ref_id"           => $get_user_regnum[0]['ref_id'] ,

													"receipt_no"       => $get_user_regnum[0]['receipt_no'] ,

													"pay_type"         => $get_user_regnum[0]['pay_type'] ,

													"status"           => $get_user_regnum[0]['status'] ,

													"pay_status"       => isset($get_user_regnum[0]['pay_type'])&&$get_user_regnum[0]['status']!=''?$get_user_regnum[0]['status']:0,

													'transaction_no'   => $transaction_no ,

													'payment_date' 	   => $get_user_regnum[0]['date'] ,

													'is_new_record'    => 'new_record_added_by_us',

													'record_type'      => "exam_invoice",

													'refund_case' => '1',

													'str_reason'  => 'payment status from S2S'

												);

									///checked transaction number already present or not	

									$get_transaction_detail = $this->master_model->getRecordCount('exam_invoice_settlement', array(

										'transaction_no' => $transaction_no));

									if($get_transaction_detail<=0)

									{

										$this->master_model->insertRecord('exam_invoice_settlement',$insert_data);	

									}

									exit;		

								}*/

								

									

									if($get_user_regnum[0]['status']==2)

									{

									

									######### payment Transaction ############

									$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');

									$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));

									if($this->db->affected_rows())

									{

										

									$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

										if($get_payment_status[0]['status']==1)

										{

										

									if(count($get_user_regnum) > 0)

									{

										$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');

									}

									

									######### payment Transaction ############

								/*	$this->db->trans_start();

									$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');

									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

									$this->db->trans_complete();*/

									

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

													//redirect(base_url().'Dbf/refund/'.base64_encode($MerchantOrderNo));

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

										//redirect(base_url().'Dbf/refund/'.base64_encode($MerchantOrderNo));

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

									$elern_msg_string=$this->master_model->getRecords('elearning_examcode');

									if(count($elern_msg_string) > 0)

									{

										foreach($elern_msg_string as $row)

										{

											$arr_elern_msg_string[]=$row['exam_code'];

										}

										if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))

										{

											$newstring20 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring19);		

										}

										else

										{

											$newstring20 = str_replace("#E-MSG#", '',$newstring19);		

										}

									}

									else

									{

										$newstring20 = str_replace("#E-MSG#", '',$newstring19);

									}

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

											/*if($getinvoice_number[0]['state_of_center']=='JAM')

											{

												$invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);

												if($invoiceNumber)

												{

													$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;

												}

											}

											else

											{*/

												$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);

												if($invoiceNumber)

												{

													$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;

												}

											//}

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

										$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	

										$this->Emailsending->mailsend_attch($info_arr,$attachpath);

										//$this->Emailsending->mailsend($info_arr);

									}

									}

									}

									else

									{

										$log_title ="S2S Update fail:".$get_user_regnum[0]['member_regnumber'];

										$log_message = serialize($update_data);

										$rId = $MerchantOrderNo;

										$regNo = $get_user_regnum[0]['member_regnumber'];

										storedUserActivity($log_title, $log_message, $rId, $regNo);	

									}

									}

								}

								else if($payment_status==0)

								{

									// Handle transaction fail case 

									$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');

									if($get_user_regnum[0]['status']==2)

									{

										$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2],'auth_code' => 0399,'bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'c_S2S');

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

									$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	

									$this->Emailsending->mailsend($info_arr);

									}

								}

							}		

				else if($responsedata[12] == "iibfdra")

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

						/*$get_user_regnum=$this->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');

						if(count($get_user_regnum) > 0)

						{

							$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');

						}*/

						//get payment transaction id

						$transdetail_det = $this->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'status,id,date');

						if($transdetail_det[0]['status']==2)

						{

							

							$updated_date = date('Y-m-d H:i:s');

							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2], 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16], 'description' => $responsedata[7], 'updated_date' => $updated_date, 'callback'=>'c_S2S');

							$this->master_model->updateRecord('dra_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

						if($this->db->affected_rows())

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

							

							/*$updated_date = date('Y-m-d H:i:s');

							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2], 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16], 'description' => $responsedata[7], 'updated_date' => $updated_date, 'callback'=>'c_S2S');

							$this->master_model->updateRecord('dra_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));*/

							

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

								$invoice_update_data = array('invoice_no' => $invoice_no,'transaction_no' => $transaction_no,'date_of_invoice' =>$transdetail_det[0]['date'],'modified_on' => $updated_date);

								$this->db->where('pay_txn_id',$transdetail_det[0]['id']);

								$this->master_model->updateRecord('exam_invoice',$invoice_update_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));

								

								log_dra_user($log_title = "Update DRA Exam Invoice Successful", $log_message = serialize($invoice_update_data));

								

								// generate invoice image

								$invoice_img_path = genarate_draexam_invoice($exam_invoice[0]['invoice_id']);

							}

						}

							/******************* eof code added for GST Changes, by Bhagwan Sahane, on 05-07-2017 ***************/

						}

					}

					else if($payment_status==0)

					{

						$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2], 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16], 'description' => $responsedata[7],'callback'=>'c_S2S');

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

				

				else if ($responsedata[12] == "iibfamp")

				{//die();

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

						$get_user_regnum_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'id,ref_id,status,payment_option ');

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

								//$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id),'name,regnumber,email_id,photograph,signature,mobile_no,sponsor');

								$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id));

							

								$update_data = array('member_regnumber' => $applicationNo,'sponsor'=>$user_info[0]['sponsor'],'transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');

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

									//'bcc'=>'skdutta@iibf.org.in,kavan@iibf.org.in'

									);

								//$this->send_mail($applicationNo);

								//$this->send_sms($applicationNo);

								

								//Manage Log

								//Invoice generation

								$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum_info[0]['id']));

			

								if(count($getinvoice_number) > 0)

								{

									$invoiceNumber = generate_amp_invoice_number($getinvoice_number[0]['invoice_id']);

									if($invoiceNumber)

									{

										$invoiceNumber=$this->config->item('amp_invoice_no_prefix').$invoiceNumber;

									}

											

									$update_data = array('invoice_no' => $invoiceNumber,'member_no'=>$applicationNo,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));

									$this->db->where('pay_txn_id',$get_user_regnum_info[0]['id']);

									$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));

									$attachpath=genarate_amp_invoice($getinvoice_number[0]['invoice_id']);	

								}

								if($attachpath!='')

								{	 

								

									$sms_newstring = str_replace("#fee#", "".$payment_info[0]['amount']."",  $emailerstr[0]['sms_text']);

									$sms_newstring1 = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring);

									$sms_final_str = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $sms_newstring1);

									$this->master_model->send_sms($user_info[0]['mobile_no'],$sms_final_str);	

									

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

												

												if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = ''; }

												

												if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }

												

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

												'to'=>'ravita@iibf.org.in,training@iibf.org.in',

												'from'=>$emailerSelfStr[0]['from'],

												'subject'=>$emailerSelfStr[0]['subject'],

												'message'=>$final_selfstr,

												);

												

												//$this->Emailsending->mailsend($self_mail_arr);

												$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);

											}

										}

									

											//email send to sk datta and kavan for bank sponsor

										if($user_info[0]['sponsor']=='bank'){

										//get bank contact email id

										   $contact_mail_id = $user_info[0]['sponsor_contact_email'];

										

											$emailerBankStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_bank'));

											if(count($emailerBankStr) > 0){

												$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';

												

												if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }

												

												if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }

												

												if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }

												

												if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = 'NO'; }

												

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

											//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in,prabhakara@iibf.org.in',

												//'to'=>'21bhavsartejasvi@gmail.com,'.$contact_mail_id.'',

												'to'=>'ravita@iibf.org.in,training@iibf.org.in,'.$contact_mail_id.'',

												'from'=>$emailerBankStr[0]['from'],

												'subject'=>$emailerBankStr[0]['subject'],

												'message'=>$final_bankstr,

												);

												//print_r($bank_mail_arr);exit;

												//$this->Emailsending->mailsend($bank_mail_arr);

												$this->Emailsending->mailsend_attch($bank_mail_arr,$attachpath);

											}

										}

									

										$this->session->set_flashdata('success','Amp registration has been done successfully !!');

										//redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));

									}

									else

									{

										//redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));

									}

									

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

									$payment_option='Full';

								}

								

								$reg_id=$get_user_regnum_info[0]['ref_id'];

								

								//update amp registration table with installment status

								$update_mem_data = array('payment' =>$payment_option);

								$this->master_model->updateRecord('amp_candidates',$update_mem_data,array('id'=>$reg_id));

								//$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id),'name,regnumber,email_id,photograph,signature,mobile_no,sponsor');

								$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id));

								

								//update payment transaction

								$update_data = array('member_regnumber' => $user_info[0]['regnumber'],'sponsor'=>$user_info[0]['sponsor'],'transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');

								$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

								

								

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

										//echo $this->db->last_query();

										//echo '<pre>update_data',print_r($update_data),'</pre>';

										//echo '<pre>',print_r($attachpath),'</pre>';

										

			

									}

									

									if($attachpath!='')

									{

										$sms_newstring = str_replace("#fee#", "".$payment_info[0]['amount']."",  $emailerstr[0]['sms_text']);

										$sms_newstring1 = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring);

										$sms_final_str = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $sms_newstring1);

										$this->master_model->send_sms($user_info[0]['mobile_no'],$sms_final_str);	

										

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

								else

								{

									//redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));

								}

							}

						}

					}

					else if($payment_status==0)

					{

						$get_user_regnum_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status');

						if($get_user_regnum_info[0]['status']==2)

						{

							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2],'auth_code' => '0399', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');

							$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

							//Manage Log

							$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;

							$this->log_model->logamptransaction("sbiepay", $pg_response,$responsedata[2]);	

						}

					}

				}

				

				

				if ($responsedata[12] == "iibftrg")

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

							/* Check Registration Capacity */

							$RegCount = "";

							$RegCount = blendedRegistrationCapacity($selected_program_code,$selected_center_code,$venue_batch_code,$selected_training_type,$selected_venue_code,$sDate);

							

							/* Get Venue Capacity */

							$capacity = "";

							$capacity = getVenueCapacity($selected_program_code,$selected_center_code,$venue_batch_code,$selected_training_type,$selected_venue_code,$sDate);

							

							/* Get User Attempt */

							$attemptQry=$this->db->query("SELECT attempt,fee_flag FROM blended_eligible_master WHERE member_number='".$applicationNo."' AND program_code = '" . $selected_program_code . "' LIMIT 1"); 

							$attemptArr = $attemptQry->row_array();

							$attempt = $attemptArr['attempt'];

							$fee_flag=$attemptArr['fee_flag'];

							/* Check Count of Vitual Attempts */

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

								/* User Log Activities  */

								$log_title ="Blended Course Registraion - Capacity is full after payment success.";

								$log_message = $log_message = 'Program Code : '.$selected_program_code;

								$rId = $reg_id;

								$regNo = $applicationNo;

								storedUserActivity($log_title, $log_message, $rId, $regNo);

							}

							

							/* Update Pay Status and User Attemp Status */

							$blended_data = array('pay_status'=>1, 'attempt'=>$attempt, 'modify_date'=>date('Y-m-d H:i:s'));

							$this->master_model->updateRecord('blended_registration',$blended_data,array('blended_id'=>$reg_id));

							

							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_blended_email'));

							if (!empty($applicationNo)) {

								$user_info = $this->Master_model->getRecords('member_registration',array('regnumber'=> $applicationNo,'isactive'=>'1'),'email,mobile');

							}

							if (count($emailerstr) > 0) 

							{

								/* Set Email Content For user */

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

								

								/* Set Email sending options */

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

								/* Invoice Number Genarate Functinality */

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

									/* Invoice Genarate Function */

									$attachpath = genarate_blended_invoice($getinvoice_number[0]['invoice_id'],$zone_code,$program_name,$gstin_no);

									/* User Log Activities  */

									$log_title ="Blended Course Registration-Invoice Genarate";

									$log_message = serialize($update_data);

									$rId = $reg_id;

									$regNo = $applicationNo;

									storedUserActivity($log_title, $log_message, $rId, $regNo);

								}

								if ($attachpath != '') 

								{	

						/* Email Send To Clints */

						

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

											/* Get Client Emails Details */

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

									/* SMS Sending Code */

									$sms_newstring = str_replace("#program_name#", "" . $program_name . "", $emailerstr[0]['sms_text']);

									$this->master_model->send_sms($user_info[0]['mobile'], $sms_newstring);

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

				

				

				else if($responsedata[12] == "IIBFDRAREG")

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

					//Payment Success

					if($payment_status==1)

					{

						// Handle transaction success case                    

						$get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status,id');

						if ($get_user_regnum[0]['status'] == 2)

						{

							if (count($get_user_regnum) > 0)

							{

								// Get agency_id from agency_center table

								$agency_center_info = $this->master_model->getRecords('agency_center', array(

									'center_id' => $get_user_regnum[0]['ref_id']

								), 'agency_id,center_id,institute_code');

							}

						   

							// Get Details from dra_inst_registration table for email and center type

							if(count($agency_center_info) > 0){   

								$user_info = $this->master_model->getRecords('dra_inst_registration', array(

									'id' => $agency_center_info[0]['agency_id']

								), 'id,email_id,inst_head_email,center_type');            

													   

								$email_id = $user_info[0]['inst_head_email'];

							}                       

						   

							$update_data = array(

								'transaction_no' => $transaction_no,

								'status' => 1,

								'transaction_details' => $responsedata[2] . " - " . $responsedata[7],

								'auth_code' => '0300',

								'bankcode' => $responsedata[9],

								'paymode' =>  $responsedata[16],

								'callback' => 'c_S2S'

							);

						   

							$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));

							$agency_id = $agency_center_info[0]['agency_id'];

							$center_id = $agency_center_info[0]['center_id'];

							$institute_code = $agency_center_info[0]['institute_code'];

						   

							if($this->db->affected_rows())

							{   

						   

							 if($institute_code != 0) // Conditions for only center add (After Login)

							 {

								$validate_upto = '';

								if ($user_info[0]['center_type'] == 'T')

								{

									$created_on    = date('Y-m-d H:i:s');

									$validate_upto = date('Y-m-d H:i:s', strtotime('+3 months', strtotime($created_on)));

								}

							   

								$update_data = array('pay_status' => '1');

								$this->master_model->updateRecord('agency_center', $update_data, array('center_id' => $agency_id));

							 }

							 else // Conditions for only Agency + center add(Outer Registration)

							 {

								 

								 /* Payment Status Updates */

								$update_data1 = array('status' => '1');

								$this->master_model->updateRecord('dra_inst_registration',$update_data1,array('id'=>$agency_id));

								$update_data2 = array('pay_status' => '1','center_status'=>'A');

								$this->master_model->updateRecord('agency_center',$update_data2, array('center_id'=>$center_id));

								$update_data3 = array('pay_status' => '1');

								$this->master_model->updateRecord('dra_accerdited_master', $update_data3, array('dra_inst_registration_id' => $agency_id));

											   

								$check_status = $this->master_model->getRecords('dra_accerdited_master',array('dra_inst_registration_id' => $agency_id,'pay_status' => '1'),'pay_status');

								if($check_status[0]['pay_status'] == '1')

								{

									$update_data4 = array('center_id' => $center_id);

									$last_id = $this->master_model->insertRecord('config_institute_code', $update_data4, true);      

									/* Get last Inst. Code */

									$institute_code = $last_id;

									if($institute_code != "" && $institute_code > 0)

									{

										/* Add Inst. Code  in agency_center and dra_accerdited_master */

										$update_data = array('institute_code' => $institute_code);

										$this->master_model->updateRecord('agency_center', $update_data, array('center_id' => $center_id));

										$update_data = array('institute_code' => $institute_code);

										$this->master_model->updateRecord('dra_accerdited_master', $update_data, array('dra_inst_registration_id' => $agency_id));

									   

									}

								   

								}

								 

							 }

							//$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;

							$emailerstr = $this->master_model->getRecords('emailer', array(

								'emailer_name' => 'dra_institute'

							));

						   

							if (count($emailerstr) > 0 && (count($get_user_regnum) > 0)) {

							  

								$final_str = $emailerstr[0]['emailer_text'];                           

								$info_arr  = array(

									'to' => $email_id,

									'from' => $emailerstr[0]['from'],

									'subject' => $emailerstr[0]['subject'],

									'message' => $final_str

								);

											   

								//genertate invoice and email send with invoice attach 8-7-2017                   

								//get invoice   

								$getinvoice_number = $this->master_model->getRecords('exam_invoice', array(

									'receipt_no' => $MerchantOrderNo,

									'pay_txn_id' => $get_user_regnum[0]['id']

								));

							   

								if (count($getinvoice_number) > 0) {

								   

									$invoiceNumber = generate_dra_invoice_number($getinvoice_number[0]['invoice_id']);

									if ($invoiceNumber) {

										$invoiceNumber = $this->config->item('DRA_invoice_no_prefix') . $invoiceNumber;

									}

								   

									$update_data = array(

										'invoice_no' => $invoiceNumber,

										'transaction_no' => $transaction_no,

										'date_of_invoice' => date('Y-m-d H:i:s'),

										'modified_on' => date('Y-m-d H:i:s')

									);

								   

									$this->db->where('pay_txn_id', $get_user_regnum[0]['id']);

									$this->master_model->updateRecord('exam_invoice', $update_data, array(

										'receipt_no' => $MerchantOrderNo

									));

								   

									$attachpath = genarate_dra_invoice($getinvoice_number[0]['invoice_id']);

								}

								if ($attachpath != '') {

								   

									if ($this->Emailsending->mailsend_attch_DRA($info_arr, $attachpath)) {

									  

									}

								}

							}

						}                   

					}                   

				}else if($payment_status==0)

				   

					{//Payment Fail                   

						$update_data = array(

							'transaction_no' => $transaction_no,

							'status' => 0,

							'transaction_details' => $responsedata[2],

							'bankcode' => $responsedata[9],

							'paymode' =>  $responsedata[16],

							'description' => $responsedata[7],

							'callback'=>'c_S2S'

						 );                   

						// Handle transaction fail case

						 $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(

							'receipt_no' => $MerchantOrderNo

						), 'ref_id,status');

					}

				   

				   

				}

				

				else if($responsedata[12] == "IIBFDRAREN")

				{

					sleep(8);

					//Added by manoj MMM for agency center Renewal 

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

					//Payment Success

					if($payment_status==1)

					{

						// Handle transaction success case 					

						$get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status,id');

						 if ($get_user_regnum[0]['status'] == 2) 

						{

							

							  $update_data = array(

								'transaction_no' => $transaction_no,

								'status' => 1,

								'transaction_details' => $responsedata[2] . " - " . $responsedata[7],

								'auth_code' => '0300',

								'bankcode' => $responsedata[9],

								'paymode' =>  $responsedata[16],

								'callback' => 'c_S2S'

							);

							

							$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));

							if($this->db->affected_rows())

							{	

								if (count($get_user_regnum) > 0) 

								{

									$renew_info = $this->master_model->getRecords('agency_center_renew', array(

										'agency_renew_id' => $get_user_regnum[0]['ref_id']

									), 'agency_id');

								}

								

								if(count($renew_info) > 0){

									 $user_info = $this->master_model->getRecords('dra_inst_registration', array(

										'id' => $renew_info[0]['agency_id']

									), 'inst_head_email');	

															

									$email_id = $user_info[0]['inst_head_email'];

								}						

								// Get email of institute by its id                        

								$update_agency_pay_statues = array('pay_status' => '1');

								$this->master_model->updateRecord('agency_center_renew', $update_agency_pay_statues, array(

								'agency_renew_id' => $get_user_regnum[0]['ref_id']

								));

								

							

							//================== NEW CODE ADDED TO update PAY status  BY Manoj============

							$agency_info = $this->master_model->getRecords('agency_center_renew', array(

								'agency_renew_id' => $get_user_regnum[0]['ref_id']

							));	

									

							$agency_id 	= $agency_info[0]['agency_id']; 					

							$center_ids = $agency_info[0]['centers_id']; 

							$center_arr = explode(',',$center_ids);

							

							// ADD CODE TO SET PAY STATUS 1: SUCCESS  

							$update_data = array('center_status' => 'A','pay_status'  => '1');					

							

							foreach($center_arr as $center_id){

								$this->master_model->updateRecord('agency_center', $update_data, array(

									'center_id' => $center_id

								));

							}

							//===============================================================================						

							 

							//$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;

							$emailerstr = $this->master_model->getRecords('emailer', array(

								'emailer_name' => 'dra_agency_renew'

							));

							

							if (count($emailerstr) > 0 && (count($get_user_regnum) > 0)) {

							   

								$final_str = $emailerstr[0]['emailer_text'];							

								$info_arr  = array(

									'to' => $email_id,

									'from' => $emailerstr[0]['from'],

									'subject' => $emailerstr[0]['subject'],

									'message' => $final_str

								);

												

								//genertate invoice and email send with invoice attach 8-7-2017                    

								//get invoice    

								$getinvoice_number = $this->master_model->getRecords('exam_invoice', array(

									'receipt_no' => $MerchantOrderNo,

									'pay_txn_id' => $get_user_regnum[0]['id']

								));

								

								if (count($getinvoice_number) > 0) {

									

									$invoiceNumber = generate_agnecy_renewal_invoice_number($getinvoice_number[0]['invoice_id']);

									if ($invoiceNumber) {

										$invoiceNumber = $this->config->item('DRA_agency_renew_invoice_no_prefix') . $invoiceNumber;

									}

									

									$update_data = array(

										'invoice_no' => $invoiceNumber,

										'transaction_no' => $transaction_no,

										'date_of_invoice' => date('Y-m-d H:i:s'),

										'modified_on' => date('Y-m-d H:i:s')

									);

									

									$this->db->where('pay_txn_id', $get_user_regnum[0]['id']);

									$this->master_model->updateRecord('exam_invoice', $update_data, array(

										'receipt_no' => $MerchantOrderNo

									));

									

									$attachpath = genarate_agnecy_renewal_invoice($getinvoice_number[0]['invoice_id']);

								}

								if ($attachpath != '') {

									

									if ($this->Emailsending->mailsend_attch_DRA($info_arr, $attachpath)) {

									   

									} 

								} 

							}

						}					

					}					

				}else if($payment_status==0)

					

					{//Payment Fail					

						 $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status');

						if ($get_user_regnum_info[0]['status'] != 0 && $get_user_regnum_info[0]['status'] == 2) 

						{

							$update_data = array(

								'transaction_no' => $transaction_no,

								'status' => 0,

								'transaction_details' => $responsedata[2] . " - " . $responsedata[7],

								'auth_code' => '0399',

								'bankcode' => $responsedata[9],

								'paymode' =>  $responsedata[16],

								'callback' => 'c_S2S'

							 );

							$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));

						}

					}

					

					

				}

				

				else if($responsedata[12] == "IIBF_EL")

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

								$elective_subject_name='';

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

										$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');

										}

										//Query to get exam details	

										$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');

										$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');

										$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');

										$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');

										$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code');

										

										######update member_exam######

										$update_data = array('pay_status' => '1');

										$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));

										

										//Query to get user details

										$this->db->join('state_master','state_master.state_code=member_registration.state');

										$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name');

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

										

										

										

											$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'Elearning_apply_exam_transaction_success'));

						$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);

						$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);

						$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);

						$newstring4 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring3);

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

						$newstring15 = str_replace("#MODE#", "".$mode."",$newstring14);

						$newstring16 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring15);

						$elern_msg_string=$this->master_model->getRecords('elearning_examcode');

						if(count($elern_msg_string) > 0)

						{

							foreach($elern_msg_string as $row)

							{

								$arr_elern_msg_string[]=$row['exam_code'];

							}

							if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))

							{

								$newstring17 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring16);		

							}

							else

							{

								$newstring17 = str_replace("#E-MSG#", '',$newstring16);		

							}

						}

						else

						{

							$newstring17 = str_replace("#E-MSG#", '',$newstring16);

						}

						$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring17);

										

						

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

									$invoiceNumber =generate_elearning_exam_invoice_number($getinvoice_number[0]['invoice_id']);

									if($invoiceNumber)

									{

										$invoiceNumber=$this->config->item('El_exam_invoice_no_prefix').$invoiceNumber;

									}

									$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));

									$this->db->where('pay_txn_id',$payment_info[0]['id']);

									$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));

									$attachpath=genarate_elearning_exam_invoice($getinvoice_number[0]['invoice_id']);

								}	

									

								if($attachpath!='')

								{		

										$files=array($attachpath,$admitcard_pdf);

										$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);

										$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);

										$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);

										$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);

										$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	

										$this->Emailsending->mailsend_attch($info_arr,$files);

									//$this->Emailsending->mailsend($info_arr);

									}

									}

									else

									{

										$log_title ="S2S Update fail:".$get_user_regnum[0]['member_regnumber'];

										$log_message = serialize($update_data);

										$rId = $MerchantOrderNo;

										$regNo = $get_user_regnum[0]['member_regnumber'];

										storedUserActivity($log_title, $log_message, $rId, $regNo);	

									}

								}

							}

							else if($payment_status==0)

							{

								$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code');

								if($get_user_regnum[0]['status']==2)

								{

									$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2],'auth_code' => 0399,'bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'c_S2S');

									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

									$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');

									//Query to get Payment details	

									$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');

									if($get_user_regnum[0]['exam_code']!='990')

									{

										//Query to get user details

									$this->db->join('state_master','state_master.state_code=member_registration.state');

									$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name');

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

									$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	

									$this->Emailsending->mailsend($info_arr);

									}

								}

							 }				

							}

				

				else if($responsedata[12] == "IIBF_ELR")

				{

								sleep(8);

								$MerchantOrderNo = $responsedata[6];  

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

								######### payment Transaction ############

								$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');

								$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));

								if($this->db->affected_rows())

								{

									$exam_code=$get_user_regnum[0]['exam_code'];

									$reg_id=$get_user_regnum[0]['member_regnumber'];

									

									//$applicationNo = generate_nm_reg_num();

									$applicationNo = generate_NM_memreg($reg_id);

									

									######### payment Transaction ############

									$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2S');

									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

									

									######### update application number to Registration table#########

									$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);

									$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));

									

									######### update application number to member exam#########

									$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);

									$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));

									

									

									//Query to get exam details	

									$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');

									$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');

									$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');

									$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');

									$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.examination_date');

				

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

									

									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'ELnon_member_apply_exam_transaction_success'));

									$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);

									$newstring2 = str_replace("#REG_NUM#", "".$applicationNo."",$newstring1);

									$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);

									$newstring4 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring3);

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

									$newstring15 = str_replace("#MODE#", "".$mode."",$newstring14);

									$newstring16 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring15);

									$newstring17 = str_replace("#PASS#", "".$decpass."",$newstring16);

									$elern_msg_string=$this->master_model->getRecords('elearning_examcode');

									if(count($elern_msg_string) > 0)

									{

										foreach($elern_msg_string as $row)

										{

											$arr_elern_msg_string[]=$row['exam_code'];

										}

										if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))

										{

											$newstring18 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring17);		

										}

										else

										{

											$newstring18 = str_replace("#E-MSG#", '',$newstring17);		

										}

									}

									else

									{

										$newstring18 = str_replace("#E-MSG#", '',$newstring17);

									}

									

									$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring18);

									

								

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

											$invoiceNumber =generate_elearning_exam_invoice_number($getinvoice_number[0]['invoice_id']);

											if($invoiceNumber)

											{

												$invoiceNumber=$this->config->item('El_exam_invoice_no_prefix').$invoiceNumber;

											}

										$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'member_no'=>$applicationNo,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));

										$this->db->where('pay_txn_id',$payment_info[0]['id']);

										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));

										$attachpath=genarate_elearning_exam_invoice($getinvoice_number[0]['invoice_id']);

									}	

									

									

									if($attachpath!='')

									{

										//send sms		

										$files=array($attachpath,$admitcard_pdf);			

										$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);

										$sms_newstring1 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring);

										$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring1);

										$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);						

										$this->Emailsending->mailsend_attch($info_arr,$files);

										//$this->Emailsending->mailsend($info_arr);

									 }

								}

								else

								{

									$log_title ="S2S Update fail:".$get_user_regnum[0]['member_regnumber'];

									$log_message = serialize($update_data);

									$rId = $MerchantOrderNo;

									$regNo = $get_user_regnum[0]['member_regnumber'];

									storedUserActivity($log_title, $log_message, $rId, $regNo);	

								}

							 }

						}

						else if($payment_status==0)

						{

								// Handle transaction fail case 

								$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');

								if($get_user_regnum[0]['status']==2)

								{

									$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2],'auth_code' =>0399,'bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'c_S2S');

									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

								

									//Query to get Payment details	

									$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id');

									$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');

									

									$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');

									$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');

									$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');

									$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');

									$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.regnumber'=>$payment_info[0]['member_regnumber']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');

								

									//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');

									$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);

									$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);

									

									$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];

									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);

									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));

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

									$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	

									$this->Emailsending->mailsend($info_arr);

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

				//die("Please try again...");

				echo "Please try again...";

			}

			//exit;

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

					echo $str = "\n Cron execution started at :$start_time \n\n Total Count =>". $crnt_day_txn_qry->num_rows()."\n\nTotal records SUCCESS: $succ_cnt\n($succ_recp) \nTotal records FAIL: $fail_cnt\n($fail_recp) \n Total records PENDING: $pending_cnt\n($pending_recp)\n Total records No Response: $no_resp_cnt\n($no_resp_recp)\n Cron execution ended at: $end_time\n";

					fwrite($fp, $str);

					fclose($fp);

			}

			

		   flock($filehandle, LOCK_UN);  // don't forget to release the lock

		

		

		## Check cron flag if it is in running state

		/*$cron = $this->master_model->getRecords('cron_flag','','cron_flag');

		//echo $this->db->last_query();exit;

		if($cron[0]['cron_flag'] == 'running')

		{

			exit;

		}

		else {

			

			## Update cron flag running

			$update_data = array('cron_flag' => 'running');

			$update_query=$this->master_model->updateRecord('cron_flag',$update_data);

			

			//error_reporting(E_ALL);

			//ini_set('display_errors', 1);

			



			

				

			## Update cron flag no running

			$update_data = array('cron_flag' => '');

			$update_query=$this->master_model->updateRecord('cron_flag',$update_data);

		}//else

		*/

	}

	

	public function sbirefund($MerchantOrderNo = "DP987787930")

	{

		include APPPATH . 'third_party/SBI_ePay/CryptAES.php';

		$key = $this->config->item('sbi_m_key');

		

		$pg_response_url = base_url()."Payment/sbirefundresponse";

		$data["pg_form_url"] = "https://test.sbiepay.com/secure/AggregatorRefundRequest"; // SBI ePay form URL

		$data["merchIdVal"] = $this->config->item('sbi_merchIdVal');

		$data["aggId"] = $this->config->item('sbi_AggregatorId');

		$AggregatorId = $this->config->item('sbi_AggregatorId');

		

		

		

		

		

		$RefundRequestID = "1234541870123";

		$RefundAmount = "2";

		$transaction_no = "7008771670841";// ATRN no received from SBI

		

		//refundRequestParams= AggregatorId|MerchantId|RefundRequestID|ATRN|RefundAmount|Currency|MerchantOrderNo|ResponseURL 

		echo $refundRequestParams = $AggregatorId."|".$data["merchIdVal"]."|".$RefundRequestID."|".$transaction_no."|".$RefundAmount."|INR|".$MerchantOrderNo."|".$pg_response_url;

		

		$aes = new CryptAES();

		$aes->set_key(base64_decode($key));

		$aes->require_pkcs5();

		

		$refundRequestParams = $aes->encrypt($refundRequestParams);

		

		$data["refundRequestParams"] = $refundRequestParams; // SBI encrypted form field value

		$this->load->view('pg_sbirefund_form',$data);

	}

	

	public function sbirefundresponse()

	{

		print_r($_REQUEST);

		$_REQUEST['pushRespData'] = $_REQUEST['encRefundData'];

		// Only for testing purpose

		if (isset($_REQUEST['pushRespData']))

		{

			$this->load->model('log_model');

			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

			

			//$key = 'fBc5628ybRQf88f/aqDUOQ==';

			$key = $this->config->item('sbi_m_key');

			

			$aes = new CryptAES();

			$aes->set_key(base64_decode($key));

			$aes->require_pkcs5();

			if (isset($_REQUEST['merchIdVal']))

			{

				$merchIdVal = $_REQUEST['merchIdVal'];

			}

			if (isset($_REQUEST['pushRespData']))

			{

				echo "<Br> encD = ".$encData = $_REQUEST['pushRespData'];

			}

			

			//echo "<BR>".$merchIdVal;

			echo "<BR>".$encData = $aes->decrypt($_REQUEST['pushRespData']);

			$responsedata = explode("|",$encData);

			print_r($responsedata);

exit;

			if ($responsedata[12] == "MEM_REG")

			{

				$MerchantOrderNo = filter_var($responsedata[0], FILTER_SANITIZE_NUMBER_INT);//$responsedata[0];

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

				

				// Update member_regnumber, description, transaction_details, ref_id	

				

				$update_data = array(

					'transaction_no' => $transaction_no,

					'status' => $payment_status

				);

echo "MerchantOrderNo = ".$MerchantOrderNo; print_r($update_data);

				//echo $this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

			}

			else if($responsedata[12] == "DRA_EXAM")

			{

				

			}

			else

			{

				

			}

			// add payment responce in log

			//$pg_response = "encRefundData=".$encData."&merchIdVal=".$merchIdVal;

			//$this->log_model->logtransaction("SBI_ePay", $pg_response, $responsedata[2]);

		}

		else

		{

			die("Please try again...");

			//echo "Please try again...";

		}

		//exit;

	}

	

	// BillDesk payment gateway

	public function make_payment()

	{

		

		if(isset($_POST['processPayment']) && $_POST['processPayment'])

		{

			

			$regno = $this->session->userdata('regnumber');//$this->session->userdata('regnumber');

			$MerchantID = $this->config->item('bd_MerchantID');

			$SecurityID = $this->config->item('bd_SecurityID');

			

			$checksum_key = $this->config->item('bd_ChecksumKey');

			

			$pg_return_url = base_url()."Payment/pg_response";

			

			//$amount = trim($this->session->userdata['examinfo']['fee']); // Exam fee//$this->config->item('dup_id_card_fee'); 

			$amount ='1';

			//$MerchantOrderNo = generate_order_id("bd_exam_order_id");

			// Create transaction

			$insert_data = array(

				'member_regnumber' => $regno,

				'amount'           => $amount,

				'gateway'          => "billdesk",

				'date'             => date('Y-m-d H:i:s'),

				'pay_type'         => '2',

				'ref_id'           => $this->session->userdata['examinfo']['insdet_id'],

				'description'      => $this->session->userdata['examinfo']['exname'],

				'status'           => '2',

				'exam_code'    =>base64_decode($this->session->userdata['examinfo']['excd']),

				//'receipt_no'       => $MerchantOrderNo

			);

				

			$pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);

			

			$MerchantOrderNo = bd_exam_order_id($pt_id);

			

			// update receipt no. in payment transaction -

			$update_data = array('receipt_no' => $MerchantOrderNo);

			$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));

			$MerchantCustomerID = $regno;

			

			$custom_field = "iibfexam";

			$data["pg_form_url"] = $this->config->item('bd_pg_form_url'); // SBI ePay form URL

			/*

			Format:			requestparameter=MerchantID|CustomerID|NA|TxnAmount|NA|NA|NA|CurrencyType|NA|TypeField1|SecurityID|NA|NA|TypeField2|AdditionalInfo1|AdditionalInfo2|AdditionalInfo3|AdditionalInfo4|AdditionalInfo5|NA|NA|RU|Checksum

			Ex.	

requestparameter=IIBF|2138759|NA|500.00|NA|NA|NA|INR|NA|R|iibf|NA|NA|F|iibfexam|500081141|148201701|NA|NA|NA|NA|http://abc.somedomain.com|2387462372

			*/

			$member_exam_id=$this->session->userdata['examinfo']['insdet_id'];

			$requestparameter = $MerchantID."|".$MerchantOrderNo."|NA|".$amount."|NA|NA|NA|INR|NA|R|".$SecurityID."|NA|NA|F|".$custom_field."|".$MerchantCustomerID."|".$member_exam_id."|NA|NA|NA|NA|".$pg_return_url;

			

			// Generate checksum for request parameter

			$req_param = $requestparameter."|".$checksum_key;

			$checksum = crc32($req_param);

			$requestparameter = $requestparameter . "|".$checksum;

			$data["msg"] = $requestparameter;

		

			$this->load->view('pg_bd_form',$data);

		}

		else

		{

			$this->load->view('pg_bd/make_payment_page');

		}

	}

	

	public function pg_response()

	{	

	//$_REQUEST['msg'] = "IIBF|2138196|HYBK4897974090|39740|00000002.00|YBK|NA|01|INR|DIRECT|NA|NA|NA|15-11-2016 13:23:02|0300|NA|iibfexam|510296983|32201701|NA|NA|NA|NA|NA|Merchant transaction successfull|2915503922";

		

		//	$_REQUEST['msg'] = "IIBF|2138195|HHMP4897894246|NA|2.00|HMP|NA|NA|INR|DIRECT|NA|NA|NA|15-11-2016 12:55:48|0399|NA|iibfexam|510296983|32201701|NA|NA|NA|NA|NA|Canceled By User|1435616898";

			

		if (isset($_REQUEST['msg']))

		{

			//echo "<pre>";

			//print_r($_REQUEST);

			//echo "<BR> Response : ".$_REQUEST['msg'];

			

			// validate checksum

			preg_match_all("/(.*)\|([0-9]*)$/", $_REQUEST['msg'],$result);

			//print_r($result);

			$res_checksum = $result[2][0];

			$msg_without_Checksum = $result[1][0];

		

			//$common_string = "sRKUUgdDrMGL";

			$checksum_key = $this->config->item('bd_ChecksumKey');

			$string_new=$msg_without_Checksum."|".$checksum_key;

			$checksum = crc32($string_new);

			

			$pg_res = explode("|",$msg_without_Checksum);   //print_r($pg_res); exit;

			

			// add payment responce in log

			$pg_response = "msg=".$_REQUEST['msg'];

			$this->log_model->logtransaction("billdesk", $pg_response, $pg_res[14]);

			

			if ($res_checksum == $checksum)

			{

				if($pg_res[16] == "iibfexam")

				{

					$MerchantOrderNo = filter_var($pg_res[1], FILTER_SANITIZE_NUMBER_INT);//$responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id

					$transaction_no  = $pg_res[2];

					$payment_status = 2;

					switch ($pg_res[14])

					{

						case "0300":

							$payment_status = 1;

							break;

						case "0399":

							$payment_status = 0;

							break;

						/*case "PENDING":

							$payment_status = 2;

							break;*/

					}

					

					if($payment_status==1)

					{

						

						// Handle transaction success case 

						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');

						if(count($get_user_regnum) > 0)

						{

							$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');

						}

	

						$update_data = array('pay_status' => '1');

						$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));

						

						$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $pg_res[24],'auth_code' => $pg_res[14],'bankcode' => $pg_res[5],'paymode' => $pg_res[7],'callback'=>'c_S2S');

						$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

						

						//Query to get user details

						$this->db->join('state_master','state_master.state_code=member_registration.state');

						$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');

						$result=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber')),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');

					

						

						

					//Query to get exam details	

					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');

					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');

					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');

					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');

					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$this->session->userdata('regnumber'),'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');

					

						if($exam_info[0]['exam_mode']=='ON')

						{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')

						{$mode='Offline';}

						else{$mode='';}

						//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');

						$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);

						$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);

						//Query to get Medium	

						$this->db->where('exam_code',base64_decode($this->session->userdata['examinfo']['excd']));

						$this->db->where('exam_period',$exam_info[0]['exam_period']);

						$this->db->where('medium_code',$exam_info[0]['exam_medium']);

						$this->db->where('medium_delete','0');

						$medium=$this->master_model->getRecords('medium_master','','medium_description');

						

						$this->db->where('state_delete','0');

						$states=$this->master_model->getRecords('state_master',array('state_code'=>$this->session->userdata['examinfo']['state_place_of_work']),'state_name');

					

						//Query to get Payment details	

						$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$this->session->userdata('regnumber')),'transaction_no,date,amount');

				

						$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];

						$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);

						if($this->session->userdata['examinfo']['elected_exam_mode']=='E')

			 			{

							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'member_exam_enrollment_nofee_elective'));

							$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);

							$newstring2 = str_replace("#REG_NUM#", "".$this->session->userdata('regnumber')."",$newstring1);

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

							$newstring17 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring16);

							$newstring18 = str_replace("#PLACE_OF_WORK#", "".strtoupper($this->session->userdata['examinfo']['placeofwork'])."",$newstring17);

							$newstring19 = str_replace("#STATE_PLACE_OF_WORK#", "".$states[0]['state_name']."",$newstring18);

							$newstring20 = str_replace("#PINCODE_PLACE_OF_WORK#", "".$this->session->userdata['examinfo']['pincode_place_of_work']."",$newstring19);

							$final_str = str_replace("#MODE#", "".$mode."",$newstring20);

					 	 }

					    else

						{

							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'apply_exam_transaction_success'));

							$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);

							$newstring2 = str_replace("#REG_NUM#", "".$this->session->userdata('regnumber')."",$newstring1);

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

						$info_arr=array('to'=>$result[0]['email'],

												'from'=>$emailerstr[0]['from'],

												'subject'=>$emailerstr[0]['subject'],

												'message'=>$final_str

											);

								

						$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);

						$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);

						$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);

						$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);

						$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	

						//To Do---Transaction email to user	currently we using failure emailer 					

						if($this->Emailsending->mailsend($info_arr))

						{

							//redirect(base_url().'Home/details/'.base64_encode($MerchantOrderNo).'/'.$this->session->userdata['examinfo']['excd']);

						}

						}

						else if($payment_status==0)

						{

							// Handle transaction fail case 

							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $pg_res[24],'auth_code' => $pg_res[14],'bankcode' => $pg_res[5],'paymode' => $pg_res[7],'callback'=>'c_S2S');

							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

						

							$result=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber')),'firstname,middlename,lastname,email,mobile');

							

							//Query to get Payment details	

							$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$this->session->userdata('regnumber')),'transaction_no,date,amount');

						

							

							

						// Handle transaction 

						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');

						//Query to get user details

						$this->db->join('state_master','state_master.state_code=member_registration.state');

						$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');

						$result=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber')),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');

					//Query to get exam details	

					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');

					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');

					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');

					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');

					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$this->session->userdata('regnumber'),'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');

					

							//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');

							$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);

							$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);

							

							

							$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];

							$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);

							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));

							$newstring1 = str_replace("#application_num#", "".$this->session->userdata('regnumber')."",  $emailerstr[0]['emailer_text']);

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

							$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	

												

							//To Do---Transaction email to user	currently we using failure emailer 					

							if($this->Emailsending->mailsend($info_arr))

							{

								//redirect(base_url().'Home/fail/'.base64_encode($MerchantOrderNo));

							}

					

							

							

							//echo 'transaction fail';exit;

						}

				}

				///echo "<BR>Checksum validated successfully<br>";

				//echo "SUCCESS:".$pg_res[2];

			}

			else

			{

				//echo "<BR>Checksum validation unsuccessful<br>";

				//echo "INVALID:".$pg_res[2];

			}

				// Redirect to success/failure

		}

		else

		{

			die("Please try again...");	

		}

	}

	

}

