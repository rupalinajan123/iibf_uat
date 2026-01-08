<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Jaiib_settel_round_one extends CI_Controller {

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

		$this->sms_template_id = '';

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

			

			// code here to start the cron job

			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

			$this->load->library('excel');

			$key = $this->config->item('sbi_m_key');

			

			$aes = new CryptAES();

			$aes->set_key(base64_decode($key));

			$aes->require_pkcs5();

			

$query='SELECT * FROM jaiib_settel_capacity where admitcard_generate = 0 Group by mem_exam_id order by id asc '; 

//$query='SELECT * FROM jaiib_settel_capacity where admitcard_generate = 0 AND receipt_no = 902575412  Group by mem_exam_id'; 

			$crnt_day_txn_qry = $this->db->query($query);  

			

		 

			

			//echo $this->db->last_query();exit;

		if ($crnt_day_txn_qry->num_rows())

		{ 

		echo $crnt_day_txn_qry->num_rows();

		echo '<br>';

		$ij=1;

			$start_time = date("Y-m-d H:i:s");

			$succ_cnt = $fail_cnt = $no_resp_cnt = $pending_cnt = 0;

			$succ_recp_arr = $fail_recp_arr = $no_resp_recp_arr = $pending_recp_arr = array();

			$todays_date = date("d-m-Y");

			

												

			foreach ($crnt_day_txn_qry->result_array() as $c_row)

			{

			$this->db->where('member_regnumber',$c_row['regnumber']);

			$this->db->where('pay_type',2);

			$this->db->where('date > ','2021-02-23 00:00:00');

			$this->db->where('status',1);

			$payment_state = $this->master_model->getRecords('payment_transaction','','status');

			if(count($payment_state) <= 0){

				

				$update_cap_arr5 = array('admitcard_generate'=>2);

				$where_cap_arr5 = array(

										'receipt_no'=>$c_row['receipt_no']

									);

				$this->master_model->updateRecord('jaiib_settel_capacity',$update_cap_arr5,$where_cap_arr5); 

				

				

				$this->db->where('receipt_no',$c_row['receipt_no']);

				$this->db->where('txn_status','SUCCESS');	

				$no_payment = 	$this->master_model->getRecords('no_payment');

				

				//sleep(1);

				$responsedata = explode('|',$no_payment[0]['txn_data']);;

				$receipt_no=$c_row['receipt_no'];

				$encData=implode('|',$responsedata);

				$resp_data = json_encode($responsedata);

				

				$resp_array = array('receipt_no'	=> $c_row['receipt_no'],

									'txn_status' 	=> $responsedata[2],

									'txn_data' 		=> $encData.'&CALLBACK=C_S2SS',

									'response_data' => $resp_data,

									'remark' 		=> '',

									'resp_date' 	=> date('Y-m-d H:i:s'),

									);

				$this->master_model->insertRecord('payment_c_s2s_log', $resp_array);

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

					

					$payment_flg = 0;

					$capacity_flag=0;

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

									{}

									else

									{

										

										//echo $this->db->last_query();exit;

										

									

											$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

											

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

															$capacity_flag=1;

															$insert_a1 = array(

																				'title'=>'no capacity1',

																				'mem_exam_id'=>$c_row['insert_a']

																			);

															$this->master_model->insertRecord('24_march_log',$insert_a1);

														}

													}

												}

												

												if(count($exam_admicard_details) > 0)

												{	

													if($capacity_flag!=1)

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

																$payment_flg =1;

																$admit_Card_img_name = $exam_info[0]['exam_code'].'_121'.$get_user_regnum[0]['member_regnumber'].'.pdf';

																

																$final_seat_number =$seat_number;

																$update_data = array('pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'),'admitcard_image'=>$admit_Card_img_name);

																$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));

																

																

																

								$update_cap_arr = array('admitcard_generate'=>1);

								$where_cap_arr = array(

														'mem_exam_id'=>$get_user_regnum[0]['ref_id'],

														'center_code'=>$exam_info[0]['exam_center_code'],

														'venueid'=>$get_subject_details[0]['venue_code'],

														'exam_date'=>$get_subject_details[0]['exam_date'],

														'time'=>$get_subject_details[0]['session_time']

													);

								$this->master_model->updateRecord('jaiib_settel_capacity',$update_cap_arr,$where_cap_arr);

																

																

															}

															else

															{

																$admit_card_details=$this->master_model->getRecords('admit_card_details',array('admitcard_id'=>$admit_card_details[0]['admitcard_id'],'remark'=>1));

																if(count($admit_card_details) > 0)

																{

																	$insert_a = array(

																					'title'=>'seat already allcate',

																					'mem_exam_id'=>$c_row['insert_a']

																				);

																$this->master_model->insertRecord('24_march_log',$insert_a);

																}

																else

																{

																	echo 'fail seat allocation';

																	

																	//redirect(base_url().'Home/refund/'.base64_encode($MerchantOrderNo));

																}

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

											

											######### payment Transaction ############

											if($payment_flg == 1){

										$update_data_p = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2SS');

										$update_query=$this->master_model->updateRecord('payment_transaction',$update_data_p,array('receipt_no'=>$MerchantOrderNo,'status'=>2));

										

												$update_data = array('pay_status' => '1');

												$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));

												

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

												$this->sms_template_id = 'P6tIFIwGR';

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
												$this->sms_template_id = 'jYZ1dSwGR';
												$final_str = $emailerstr[0]['emailer_text'];

											}else if($exam_info[0]['exam_code']==993)

											{

												$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'Cisi_emailer'));
												$this->sms_template_id = 'gewX5IwGR';
												$final_str = $emailerstr[0]['emailer_text'];

											}

											else

											{

												$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'apply_exam_transaction_success'));

												$this->sms_template_id = 'P6tIFIwGR';

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

												

												$get_payment_status1=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

												

												if($get_payment_status1[0]['status']==1){

												$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);

												

												$insert_a = array(

																	'title'=>'invoice number generate',

																	'mem_exam_id'=>$c_row['insert_a']

															);

												$this->master_model->insertRecord('24_march_log',$insert_a);

												

												if($invoiceNumber)

												{

													$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;

												}

												}else{

													

													$insert_a = array(

																	'title'=>'invoice number generate fail',

																	'mem_exam_id'=>$c_row['insert_a']

															);

												$this->master_model->insertRecord('24_march_log',$insert_a);

													

												}

											}

										//}

										

										 

										$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

										

										if($get_payment_status[0]['status']==1){

										

										$invoice_image_name = $get_user_regnum[0]['member_regnumber'].'_'.$invoiceNumber.'.jpg';  

										$invoice_image_name = str_replace("/","_",$invoice_image_name);

										$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'),'invoice_image'=>$invoice_image_name);

										$this->db->where('pay_txn_id',$payment_info[0]['id']);

										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo)); 

										

										$insert_a = array(

																	'title'=>'invoice update',

																	'mem_exam_id'=>$c_row['insert_a']

															);

												$this->master_model->insertRecord('24_march_log',$insert_a);

										

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

											$attachpath = '';

											//$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);

										}

										if($exam_info[0]['exam_code']!=101 || $exam_info[0]['exam_code']!=990 || $exam_info[0]['exam_code']!=993)

										{

												##############Get Admit card#############

												$admitcard_pdf = '';

												//$admitcard_pdf=genarate_admitcard($get_user_regnum[0]['member_regnumber'],$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);

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

											// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	

											$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);

											$this->Emailsending->mailsend_attch($info_arr,$files);

										//$this->Emailsending->mailsend($info_arr);

										}

										

										

										

									}

								}

							}

							else if($payment_status==0)

							{

								$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code');

								if($get_user_regnum[0]['status']==2)

								{

									$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2],'auth_code' => 0399,'bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'c_S2SS');

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

									$this->sms_template_id = 'Jw6bOIQGg';

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
									$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);	

									$this->Emailsending->mailsend($info_arr);

									}

								}

							 }				

							}

				

				

				else

				{

					$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=C_S2SS";

					$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);

				}

			}

			else

			{

				//die("Please try again...");

				echo "Please try again...";

			}

			

			}

			else{

				

				// pure refund member

				$pure_refund_jaiib_array = array(

												'regnumber'=>$c_row['regnumber'],

												'receipt_no'=>$c_row['receipt_no']

											);

				$this->master_model->insertRecord('pure_refund_jaiib', $pure_refund_jaiib_array);

				

				

				

				$update_cap_arr2 = array('admitcard_generate'=>4);

				$where_cap_arr2 = array(

										'receipt_no'=>$c_row['receipt_no']

									);

				$this->master_model->updateRecord('jaiib_settel_capacity',$update_cap_arr2,$where_cap_arr2);

				

			}

		echo $ij++;

		echo '<br>';}//foreach

			

					

					

			}

			

			

			

			

	}

	

	

	public function sbicallback_two() 

	{ 

			

			// code here to start the cron job

			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

			$this->load->library('excel');

			$key = $this->config->item('sbi_m_key');

			

			$aes = new CryptAES();

			$aes->set_key(base64_decode($key));

			$aes->require_pkcs5();

			

$query='SELECT * FROM jaiib_settel_capacity where admitcard_generate = 0 AND receipt_no = 902608716 Group by mem_exam_id order by id asc ';    

//$query='SELECT * FROM jaiib_settel_capacity where admitcard_generate = 0 AND receipt_no = 902575412  Group by mem_exam_id'; 

			$crnt_day_txn_qry = $this->db->query($query);   

			

		  

			

			//echo $this->db->last_query();exit;

		if ($crnt_day_txn_qry->num_rows())

		{ 

		echo $crnt_day_txn_qry->num_rows();

		echo '<br>';

		$ij=1;

			$start_time = date("Y-m-d H:i:s");

			$succ_cnt = $fail_cnt = $no_resp_cnt = $pending_cnt = 0;

			$succ_recp_arr = $fail_recp_arr = $no_resp_recp_arr = $pending_recp_arr = array();

			$todays_date = date("d-m-Y");

			

												

			foreach ($crnt_day_txn_qry->result_array() as $c_row)

			{

			$this->db->where('member_regnumber',$c_row['regnumber']);

			$this->db->where('pay_type',2);

			$this->db->where('date > ','2021-02-23 00:00:00');

			$this->db->where('status',1);

			$payment_state = $this->master_model->getRecords('payment_transaction','','status');

			if(count($payment_state) <= 0){

				

				$update_cap_arr5 = array('admitcard_generate'=>2);

				$where_cap_arr5 = array(

										'receipt_no'=>$c_row['receipt_no']

									);

				//$this->master_model->updateRecord('jaiib_settel_capacity',$update_cap_arr5,$where_cap_arr5); 

				

				

				$this->db->where('receipt_no',$c_row['receipt_no']);

				$this->db->where('txn_status','SUCCESS');	

				$no_payment = 	$this->master_model->getRecords('no_payment');

				

				//sleep(1);

				$responsedata = explode('|',$no_payment[0]['txn_data']);;

				$receipt_no=$c_row['receipt_no'];

				$encData=implode('|',$responsedata);

				$resp_data = json_encode($responsedata);

				

				$resp_array = array('receipt_no'	=> $c_row['receipt_no'],

									'txn_status' 	=> $responsedata[2],

									'txn_data' 		=> $encData.'&CALLBACK=C_S2SS',

									'response_data' => $resp_data,

									'remark' 		=> '',

									'resp_date' 	=> date('Y-m-d H:i:s'),

									);

				$this->master_model->insertRecord('payment_c_s2s_log', $resp_array);

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

					echo '123';exit;

					$payment_flg = 0;

					$capacity_flag=0;

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

									{}

									else

									{

										

										//echo $this->db->last_query();exit;

										

									

											$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

											

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

															$capacity_flag=1;

															$insert_a1 = array(

																				'title'=>'no capacity1',

																				'mem_exam_id'=>$c_row['insert_a']

																			);

															$this->master_model->insertRecord('24_march_log',$insert_a1);

														}

													}

												}

												

												if(count($exam_admicard_details) > 0)

												{	

													if($capacity_flag!=1)

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

																$payment_flg =1;

																$admit_Card_img_name = $exam_info[0]['exam_code'].'_121'.$get_user_regnum[0]['member_regnumber'].'.pdf';

																

																$final_seat_number =$seat_number;

																$update_data = array('pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'),'admitcard_image'=>$admit_Card_img_name);

																$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));

																

																

																

								$update_cap_arr = array('admitcard_generate'=>1);

								$where_cap_arr = array(

														'mem_exam_id'=>$get_user_regnum[0]['ref_id'],

														'center_code'=>$exam_info[0]['exam_center_code'],

														'venueid'=>$get_subject_details[0]['venue_code'],

														'exam_date'=>$get_subject_details[0]['exam_date'],

														'time'=>$get_subject_details[0]['session_time']

													);

								$this->master_model->updateRecord('jaiib_settel_capacity',$update_cap_arr,$where_cap_arr);

																

																

															}

															else

															{

																$admit_card_details=$this->master_model->getRecords('admit_card_details',array('admitcard_id'=>$admit_card_details[0]['admitcard_id'],'remark'=>1));

																if(count($admit_card_details) > 0)

																{

																	$insert_a = array(

																					'title'=>'seat already allcate',

																					'mem_exam_id'=>$c_row['insert_a']

																				);

																$this->master_model->insertRecord('24_march_log',$insert_a);

																}

																else

																{

																	echo 'fail seat allocation';

																	

																	//redirect(base_url().'Home/refund/'.base64_encode($MerchantOrderNo));

																}

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

											

											######### payment Transaction ############

											if($payment_flg == 1){

										$update_data_p = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2SS');

										$update_query=$this->master_model->updateRecord('payment_transaction',$update_data_p,array('receipt_no'=>$MerchantOrderNo,'status'=>2));

										

												$update_data = array('pay_status' => '1');

												$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));

												

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

													$this->sms_template_id = 'P6tIFIwGR';

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

												$this->sms_template_id = 'jYZ1dSwGR';

												$final_str = $emailerstr[0]['emailer_text'];

											}else if($exam_info[0]['exam_code']==993)

											{

												$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'Cisi_emailer'));
												$this->sms_template_id = 'gewX5IwGR';
												$final_str = $emailerstr[0]['emailer_text'];

											}

											else

											{

												$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'apply_exam_transaction_success'));
												$this->sms_template_id = 'P6tIFIwGR';
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

												

												$get_payment_status1=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

												

												if($get_payment_status1[0]['status']==1){

												$invoiceNumber =$this->generate_exam_invoice_number_wrong_invoice($getinvoice_number[0]['invoice_id']);

												

												$insert_a = array(

																	'title'=>'invoice number generate',

																	'mem_exam_id'=>$c_row['insert_a']

															);

												$this->master_model->insertRecord('24_march_log',$insert_a);

												

												if($invoiceNumber)

												{

													/*'EX/'.$yearnow.'-'.$yearnext.'/';*/

													//$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;

													$invoiceNumber='EX/20-21/'.$invoiceNumber;

												}

												}else{

													

													$insert_a = array(

																	'title'=>'invoice number generate fail',

																	'mem_exam_id'=>$c_row['insert_a']

															);

												$this->master_model->insertRecord('24_march_log',$insert_a);

													

												}

											}

										//}

										

										 

										$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

										

										if($get_payment_status[0]['status']==1){

										

										$new_str123 = str_replace('/','_',$invoiceNumber);

										$invoice_image_name = $get_user_regnum[0]['member_regnumber'].'_'.$new_str123.'.jpg';  

										$invoice_image_name = str_replace("/","_",$invoice_image_name);

										$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>$get_payment_status[0]['date'],'modified_on'=>$get_payment_status[0]['date'],'invoice_image'=>$invoice_image_name);

										$this->db->where('pay_txn_id',$payment_info[0]['id']);

										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo)); 

										

										$insert_a = array(

																	'title'=>'invoice update',

																	'mem_exam_id'=>$c_row['insert_a']

															);

												$this->master_model->insertRecord('24_march_log',$insert_a);

										

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

											$attachpath = '';

											//$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);

										}

										if($exam_info[0]['exam_code']!=101 || $exam_info[0]['exam_code']!=990 || $exam_info[0]['exam_code']!=993)

										{

												##############Get Admit card#############

												$admitcard_pdf = '';

												//$admitcard_pdf=genarate_admitcard($get_user_regnum[0]['member_regnumber'],$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);

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

											// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
											$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);

											$this->Emailsending->mailsend_attch($info_arr,$files);

										//$this->Emailsending->mailsend($info_arr);

										}

										

										

										

									}

								}

							}

							else if($payment_status==0)

							{

								$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code');

								if($get_user_regnum[0]['status']==2)

								{

									$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2],'auth_code' => 0399,'bankcode' => $responsedata[9],'paymode' =>  $responsedata[12],'callback'=>'c_S2SS');

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
									$this->sms_template_id = 'Jw6bOIQGg';
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

									$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);

									$this->Emailsending->mailsend($info_arr);

									}

								}

							 }				

							}

				

				

				else

				{

					$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=C_S2SS";

					$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);

				}

			}

			else

			{

				//die("Please try again...");

				echo "Please try again...";

			}

			

			}

			else{

				

				// pure refund member

				$pure_refund_jaiib_array = array(

												'regnumber'=>$c_row['regnumber'],

												'receipt_no'=>$c_row['receipt_no']

											);

				$this->master_model->insertRecord('pure_refund_jaiib', $pure_refund_jaiib_array);

				

				

				

				$update_cap_arr2 = array('admitcard_generate'=>4);

				$where_cap_arr2 = array(

										'receipt_no'=>$c_row['receipt_no']

									);

				$this->master_model->updateRecord('jaiib_settel_capacity',$update_cap_arr2,$where_cap_arr2);

				

			}

		echo $ij++;

		echo '<br>';}//foreach

			

					

					

			}

			

			

			

			

	}

	

	

public function sbicallback_three(){

	

	// code here to start the cron job

	include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

	$this->load->library('excel');

	$key = $this->config->item('sbi_m_key');

	

	$aes = new CryptAES();

	$aes->set_key(base64_decode($key)); 

	$aes->require_pkcs5();  

	

	$query='SELECT * FROM jaiib_settel_capacity_11april2021_558_move where  admitcard_generate = 0 Group by mem_exam_id order by id asc ';         

	$crnt_day_txn_qry = $this->db->query($query);  

	

	if ($crnt_day_txn_qry->num_rows()){

		echo $crnt_day_txn_qry->num_rows();

		echo '<br>';

		$ij=1;

		

		$start_time = date("Y-m-d H:i:s");

		$succ_cnt = $fail_cnt = $no_resp_cnt = $pending_cnt = 0;

		$succ_recp_arr = $fail_recp_arr = $no_resp_recp_arr = $pending_recp_arr = array();

		$todays_date = date("d-m-Y");

		

		foreach ($crnt_day_txn_qry->result_array() as $c_row){

			$this->db->where('member_regnumber',$c_row['regnumber']);

			$this->db->where('pay_type',2);

			$this->db->where('date > ','2021-02-23 00:00:00');

			$this->db->where('status',1);

			$payment_state = $this->master_model->getRecords('payment_transaction','','status');

			if(count($payment_state) <= 0){

				

				$update_cap_arr5 = array('admitcard_generate'=>2);

				$where_cap_arr5 = array('receipt_no'=>$c_row['receipt_no']);

				$this->master_model->updateRecord('jaiib_settel_capacity_11april2021_558_move',$update_cap_arr5,$where_cap_arr5);  

				

				$this->db->where('receipt_no',$c_row['receipt_no']);

				$this->db->where('txn_status','SUCCESS');	

				$no_payment = 	$this->master_model->getRecords('no_payment');

				

				//sleep(1);

				$responsedata = explode('|',$no_payment[0]['txn_data']);;

				$receipt_no=$c_row['receipt_no'];

				$encData=implode('|',$responsedata);

				$resp_data = json_encode($responsedata);

				

				$resp_array = array('receipt_no'	=> $c_row['receipt_no'],

									'txn_status' 	=> $responsedata[2],

									'txn_data' 		=> $encData.'&CALLBACK=C_S2SS',

									'response_data' => $resp_data,

									'remark' 		=> '',

									'resp_date' 	=> date('Y-m-d H:i:s'),

									);

				$this->master_model->insertRecord('payment_c_s2s_log', $resp_array);

				

				if (isset($responsedata) && count($responsedata) > 0){

					

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

					

					

					if($responsedata[12] == "IIBF_EXAM_O"){

						//echo 'here123';exit;

						$payment_flg = 0;

						$capacity_flag=0;

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

						if($payment_status==1){

							

							$exam_period_date='';

							//Handle transaction success case

							$elective_subject_name='';

							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

							

							if($get_user_regnum[0]['status']==2){

								if($get_user_regnum[0]['exam_code']=='1016'){

									

								}else{

									$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

									if(count($get_user_regnum) > 0){

										$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');

									}

									//Query to get exam details	

									$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');

									$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');

									$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');

									$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');

									$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code');

									

									

	if($exam_info[0]['exam_code']!=101 || $exam_info[0]['exam_code']!=990 || $exam_info[0]['exam_code']!=993){

			$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));

		

			$this->db->where('mem_exam_id',$get_user_regnum[0]['ref_id']);

			$this->db->group_by('sub_cd,mem_exam_id');

			$admit_card_details = $this->master_model->getRecords('admit_card_details');

			

			if(!empty($admit_card_details))

			{

				foreach($admit_card_details as $val)

				{

					if($val['pwd']!='')

					{

						$password =$val['pwd'];

					}	

				}

			}

													

			if($password == '')

			{

				$password = random_password(); 

			}

			 

			if(!empty($admit_card_details)){

				foreach($admit_card_details as $val){

					if($val['seat_identification']==''){

						$this->db->order_by("seat_no", "desc"); 

						$seat_allocation=$this->master_model->getRecords('seat_allocation',array('venue_code'=>$val['venueid'],'session'=>$val['time'],'center_code'=>$val['center_code'],'date'=>$val['exam_date']));

						if(!empty($seat_allocation)){

							//check venue_capacity

							$venue_capacity=$this->master_model->getRecords('venue_master',array('venue_code'=>$val['venueid'],'session_time'=>$val['time'],'center_code'=>$val['center_code'],'exam_date'=>$val['exam_date']));

							

							$venue_capacity=$venue_capacity[0]['session_capacity']+100; 

							if(!empty($venue_capacity)){

								if($seat_allocation[0]['seat_no']<=$venue_capacity){

									$seat_no=$seat_allocation[0]['seat_no'];

									//inset new recode with append  seat number

									$seat_no=$seat_no+1;

									

									if($seat_no<10)

									{

										$seat_no='00'.$seat_no;

									}

									elseif($seat_no>10 && $seat_no<100)

									{

										$seat_no='0'.$seat_no;

									}

									

									$invoice_insert_array = array(

																'seat_no' => $seat_no,

																'exam_code' => $val['exm_cd'],

																'venue_code'=>$val['venueid'],

																'session'=>$val['time'],

																'center_code'=>$val['center_code'],

																'date'=>$val['exam_date'],

																'exam_period'=>$val['exm_prd'],

																'subject_code'=>$val['sub_cd'],

																'admit_card_id'=>$val['admitcard_id'],

																'createddate'=>date('Y-m-d H:i:s')

															);

									if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array)){

										

										$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';

										$update_info = array(

											'seat_identification' => $seat_no,

											'modified_on'=>$val['created_on'],

											'admitcard_image'=>$admitcard_image,

											'pwd' => $password, 

											'remark'=>1,

										);

										

										if($this->master_model->updateRecord('admit_card_details', $update_info,array('admitcard_id'=>$val['admitcard_id']))){

											echo '<br>Recode updated sucessfully in admit card<br>';

											echo 'Settel.'.$get_user_regnum[0]['ref_id'];

										}else{

											echo '<br>Recode Not updated '.$get_user_regnum[0]['ref_id'];

											echo '<br/>';

										}

									}

									

								}else{

									echo '<br>Capacity has been full<br>';

									$capacity_full['capacity'][] =$get_user_regnum[0]['ref_id'];

								}

							}else{

								echo '<br>Venue not present in venue master123<br>';

							} 

						}else{

							

							$venue_capacity=$this->master_model->getRecords('venue_master',array(			        'venue_code'=>$val['venueid'],'session_time'=>$val['time'],'center_code'=>$val['center_code'],'exam_date'=>$val['exam_date']));

							

							if(!empty($venue_capacity)){

								if($seat_allocation[0]['seat_no']<=$venue_capacity[0]['session_capacity']){

									$seat_no='001';

									$invoice_insert_array = array(

										'seat_no' => $seat_no,

										'exam_code' => $val['exm_cd'],

										'venue_code'=>$val['venueid'],

										'session'=>$val['time'],

										'center_code'=>$val['center_code'],

										'date'=>$val['exam_date'],

										'exam_period'=>$val['exm_prd'],

										'subject_code'=>$val['sub_cd'],

										'admit_card_id'=>$val['admitcard_id'],

										'createddate'=>date('Y-m-d H:i:s')

									);

									

									if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array)){

										

										$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';

										$update_info = array(

											'seat_identification' => $seat_no,

											'modified_on'=>$val['created_on'],

											'admitcard_image'=>$admitcard_image,

											'pwd' => $password, 

											'remark'=>1,

										);

										

										if($this->master_model->updateRecord('admit_card_details', $update_info,array('admitcard_id'=>$val['admitcard_id']))){

											echo 'Recode updated sucessfully'.$get_user_regnum[0]['ref_id'];

											echo '<br/>';

										}else{

											echo 'Recode Not updated sucessfully in admit card<br>'.$get_user_regnum[0]['ref_id'];

										}

										

									}

								}else{

									echo '<br>Capacity has been full<br>';

									$capacity_full['capacity'][] =$get_user_regnum[0]['ref_id'];

								}

							}else{

								echo '<br>Venue not present in venue master234<br>';

							}

							

							

						}

					}

				}

			}

			

	}

	

	######### payment Transaction AND update member_exam ############

	

	$update_data_p = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2SS');

	$update_query=$this->master_model->updateRecord('payment_transaction',$update_data_p,array('receipt_no'=>$MerchantOrderNo,'status'=>2));

	

	$update_data = array('pay_status' => '1');

	$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));

	

	

	//Query to get user details

	$this->db->join('state_master','state_master.state_code=member_registration.state');

	$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');

	$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');

	

	if(count($exam_info) <= 0){

		$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']));

	}

	

	if($exam_info[0]['exam_mode']=='ON'){

		$mode='Online';

	}elseif($exam_info[0]['exam_mode']=='OF'){

		$mode='Offline';

	}else{

		$mode='';

	}

	

	if($exam_info[0]['examination_date']!='0000-00-00')

	{

		$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));

	}

	elseif($exam_info[0]['exam_code']!=990)

	{

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

	

	if($exam_info[0]['place_of_work']!='' && $exam_info[0]['state_place_of_work']!='' && $exam_info[0]['pin_code_place_of_work']!=''){

		

		if($exam_info[0]['elected_sub_code']!=0 && $exam_info[0]['elected_sub_code']!='')

		{

			$elective_sub_name_arr=$this->master_model->getRecords('subject_master',array('subject_code'=>$exam_info[0]['elected_sub_code'],'subject_delete'=>0),'subject_description');

			if(count($elective_sub_name_arr) > 0)

			{

				$elective_subject_name=$elective_sub_name_arr[0]['subject_description'];

			}

		}

		

		

		$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'member_exam_enrollment_nofee_elective'));
		$this->sms_template_id = 'P6tIFIwGR';
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

		

		

		

	}else{

		

		if($exam_info[0]['exam_code']==990)

		{

			$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'DISA_emailer'));
			$this->sms_template_id = 'jYZ1dSwGR';
			$final_str = $emailerstr[0]['emailer_text'];

		}elseif($exam_info[0]['exam_code']==993)

		{

			$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'Cisi_emailer'));
			$this->sms_template_id = 'gewX5IwGR';
			$final_str = $emailerstr[0]['emailer_text'];

		}else

		{

			$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'apply_exam_transaction_success'));
			$this->sms_template_id = 'P6tIFIwGR';
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

					

					

					

	$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));

	

	if(count($getinvoice_number) > 0){

		$invoiceNumber ='';	

		$get_payment_status1=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

		if($get_payment_status1[0]['status']==1){

			$invoiceNumber =$this->generate_exam_invoice_number_wrong_invoice($getinvoice_number[0]['invoice_id']);

			

			// EX/20-21/283981

			// 510326613_EX_20-21_283981.jpg

			

			$invoiceNumber='EX/20-21/'.$invoiceNumber;

		}

	}

	

	$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

	if($get_payment_status[0]['status']==1)

	{

		$new_str123 = str_replace('/','_',$invoiceNumber);

		$invoice_image_name = $get_user_regnum[0]['member_regnumber'].'_'.$new_str123.'.jpg';  

		$invoice_image_name = str_replace("/","_",$invoice_image_name);

		$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>$get_payment_status[0]['date'],'modified_on'=>$get_payment_status[0]['date'],'invoice_image'=>$invoice_image_name);

		$this->db->where('pay_txn_id',$payment_info[0]['id']);

		$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo)); 

	}

	

	$attachpath = '';

	$admitcard_pdf = '';

	if($attachpath!=''){

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

		

		// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
		$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);

		$this->Emailsending->mailsend_attch($info_arr,$files);

		

		

	}

									

									

								}

							}

							

						}

					}

					

					if($responsedata[12] == "IIBF_EXAM_DB_EXAM"){

						//echo 'here123';exit;

						$payment_flg = 0;

						$capacity_flag=0;

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

						if($payment_status==1){

							

							$exam_period_date='';

							//Handle transaction success case

							$elective_subject_name='';

							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

							

							if($get_user_regnum[0]['status']==2){

								if($get_user_regnum[0]['exam_code']=='1016'){

									

								}else{

									$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

									if(count($get_user_regnum) > 0){

										$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');

									}

									//Query to get exam details	

									$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');

									$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');

									$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');

									$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');

									$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code');

									

									

	if($exam_info[0]['exam_code']!=101 || $exam_info[0]['exam_code']!=990 || $exam_info[0]['exam_code']!=993){

			$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));

		

			$this->db->where('mem_exam_id',$get_user_regnum[0]['ref_id']);

			$this->db->group_by('sub_cd,mem_exam_id');

			$admit_card_details = $this->master_model->getRecords('admit_card_details');

			

			if(!empty($admit_card_details))

			{

				foreach($admit_card_details as $val)

				{

					if($val['pwd']!='')

					{

						$password =$val['pwd'];

					}	

				}

			}

													

			if($password == '')

			{

				$password = random_password(); 

			}

			 

			if(!empty($admit_card_details)){

				foreach($admit_card_details as $val){

					if($val['seat_identification']==''){

						$this->db->order_by("seat_no", "desc"); 

						$seat_allocation=$this->master_model->getRecords('seat_allocation',array('venue_code'=>$val['venueid'],'session'=>$val['time'],'center_code'=>$val['center_code'],'date'=>$val['exam_date']));

						if(!empty($seat_allocation)){

							//check venue_capacity

							$venue_capacity=$this->master_model->getRecords('venue_master',array('venue_code'=>$val['venueid'],'session_time'=>$val['time'],'center_code'=>$val['center_code'],'exam_date'=>$val['exam_date']));

							

							$venue_capacity=$venue_capacity[0]['session_capacity']+100; 

							if(!empty($venue_capacity)){

								if($seat_allocation[0]['seat_no']<=$venue_capacity){

									$seat_no=$seat_allocation[0]['seat_no'];

									//inset new recode with append  seat number

									$seat_no=$seat_no+1;

									

									if($seat_no<10)

									{

										$seat_no='00'.$seat_no;

									}

									elseif($seat_no>10 && $seat_no<100)

									{

										$seat_no='0'.$seat_no;

									}

									

									$invoice_insert_array = array(

																'seat_no' => $seat_no,

																'exam_code' => $val['exm_cd'],

																'venue_code'=>$val['venueid'],

																'session'=>$val['time'],

																'center_code'=>$val['center_code'],

																'date'=>$val['exam_date'],

																'exam_period'=>$val['exm_prd'],

																'subject_code'=>$val['sub_cd'],

																'admit_card_id'=>$val['admitcard_id'],

																'createddate'=>date('Y-m-d H:i:s')

															);

									if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array)){

										

										$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';

										$update_info = array(

											'seat_identification' => $seat_no,

											'modified_on'=>$val['created_on'],

											'admitcard_image'=>$admitcard_image,

											'pwd' => $password, 

											'remark'=>1,

										);

										

										if($this->master_model->updateRecord('admit_card_details', $update_info,array('admitcard_id'=>$val['admitcard_id']))){

											echo '<br>Recode updated sucessfully in admit card<br>';

											echo 'Settel.'.$get_user_regnum[0]['ref_id'];

										}else{

											echo '<br>Recode Not updated '.$get_user_regnum[0]['ref_id'];

											echo '<br/>';

										}

									}

									

								}else{

									echo '<br>Capacity has been full<br>';

									$capacity_full['capacity'][] =$get_user_regnum[0]['ref_id'];

								}

							}else{

								echo '<br>Venue not present in venue master123<br>';

							} 

						}else{

							

							$venue_capacity=$this->master_model->getRecords('venue_master',array(			        'venue_code'=>$val['venueid'],'session_time'=>$val['time'],'center_code'=>$val['center_code'],'exam_date'=>$val['exam_date']));

							

							if(!empty($venue_capacity)){

								if($seat_allocation[0]['seat_no']<=$venue_capacity[0]['session_capacity']){

									$seat_no='001';

									$invoice_insert_array = array(

										'seat_no' => $seat_no,

										'exam_code' => $val['exm_cd'],

										'venue_code'=>$val['venueid'],

										'session'=>$val['time'],

										'center_code'=>$val['center_code'],

										'date'=>$val['exam_date'],

										'exam_period'=>$val['exm_prd'],

										'subject_code'=>$val['sub_cd'],

										'admit_card_id'=>$val['admitcard_id'],

										'createddate'=>date('Y-m-d H:i:s')

									);

									

									if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array)){

										

										$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';

										$update_info = array(

											'seat_identification' => $seat_no,

											'modified_on'=>$val['created_on'],

											'admitcard_image'=>$admitcard_image,

											'pwd' => $password, 

											'remark'=>1,

										);

										

										if($this->master_model->updateRecord('admit_card_details', $update_info,array('admitcard_id'=>$val['admitcard_id']))){

											echo 'Recode updated sucessfully'.$get_user_regnum[0]['ref_id'];

											echo '<br/>';

										}else{

											echo 'Recode Not updated sucessfully in admit card<br>'.$get_user_regnum[0]['ref_id'];

										}

										

									}

								}else{

									echo '<br>Capacity has been full<br>';

									$capacity_full['capacity'][] =$get_user_regnum[0]['ref_id'];

								}

							}else{

								echo '<br>Venue not present in venue master234<br>';

							}

							

							

						}

					}

				}

			}

			

	}

	

	######### payment Transaction AND update member_exam ############

	

	$update_data_p = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2],'auth_code' => '0300', 'bankcode' => $responsedata[9], 'paymode' =>  $responsedata[16],'callback'=>'c_S2SS');

	$update_query=$this->master_model->updateRecord('payment_transaction',$update_data_p,array('receipt_no'=>$MerchantOrderNo,'status'=>2));

	

	$update_data = array('pay_status' => '1');

	$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));

	

	

	//Query to get user details

	$this->db->join('state_master','state_master.state_code=member_registration.state');

	$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');

	$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');

	

	if(count($exam_info) <= 0){

		$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']));

	}

	

	if($exam_info[0]['exam_mode']=='ON'){

		$mode='Online';

	}elseif($exam_info[0]['exam_mode']=='OF'){

		$mode='Offline';

	}else{

		$mode='';

	}

	

	if($exam_info[0]['examination_date']!='0000-00-00')

	{

		$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));

	}

	elseif($exam_info[0]['exam_code']!=990)

	{

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

	

	if($exam_info[0]['place_of_work']!='' && $exam_info[0]['state_place_of_work']!='' && $exam_info[0]['pin_code_place_of_work']!=''){

		

		if($exam_info[0]['elected_sub_code']!=0 && $exam_info[0]['elected_sub_code']!='')

		{

			$elective_sub_name_arr=$this->master_model->getRecords('subject_master',array('subject_code'=>$exam_info[0]['elected_sub_code'],'subject_delete'=>0),'subject_description');

			if(count($elective_sub_name_arr) > 0)

			{

				$elective_subject_name=$elective_sub_name_arr[0]['subject_description'];

			}

		}

		

		

		$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'member_exam_enrollment_nofee_elective'));
		$this->sms_template_id = 'P6tIFIwGR';
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

		

		

		

	}else{

		

		if($exam_info[0]['exam_code']==990)

		{

			$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'DISA_emailer'));
			$this->sms_template_id = 'jYZ1dSwGR';
			$final_str = $emailerstr[0]['emailer_text'];

		}elseif($exam_info[0]['exam_code']==993)

		{

			$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'Cisi_emailer'));
			$this->sms_template_id = 'gewX5IwGR';
			$final_str = $emailerstr[0]['emailer_text'];

		}else

		{

			$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'apply_exam_transaction_success'));
			$this->sms_template_id = 'P6tIFIwGR';
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

					

					

					

	$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));

	

	if(count($getinvoice_number) > 0){

		$invoiceNumber ='';	

		$get_payment_status1=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

		if($get_payment_status1[0]['status']==1){

			$invoiceNumber =$this->generate_exam_invoice_number_wrong_invoice($getinvoice_number[0]['invoice_id']);

			

			// EX/20-21/283981

			// 510326613_EX_20-21_283981.jpg

			

			$invoiceNumber='EX/20-21/'.$invoiceNumber;

		}

	}

	

	$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

	if($get_payment_status[0]['status']==1)

	{

		$new_str123 = str_replace('/','_',$invoiceNumber);

		$invoice_image_name = $get_user_regnum[0]['member_regnumber'].'_'.$new_str123.'.jpg';  

		$invoice_image_name = str_replace("/","_",$invoice_image_name);

		$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>$get_payment_status[0]['date'],'modified_on'=>$get_payment_status[0]['date'],'invoice_image'=>$invoice_image_name);

		$this->db->where('pay_txn_id',$payment_info[0]['id']);

		$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo)); 

	}

	

	$attachpath = '';

	$admitcard_pdf = '';

	if($attachpath!=''){

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

		

		// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
		$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);

		$this->Emailsending->mailsend_attch($info_arr,$files);

		

		

	}

									

									

								}

							}

							

						}

					}

				}

			}else{

				// pure refund member

				$pure_refund_jaiib_array = array(

												'regnumber'=>$c_row['regnumber'],

												'receipt_no'=>$c_row['receipt_no']

											);

				$this->master_model->insertRecord('pure_refund_jaiib', $pure_refund_jaiib_array);

				// total record 393 and last_id : 399

				

				$update_cap_arr2 = array('admitcard_generate'=>4);

				$where_cap_arr2 = array(

										'receipt_no'=>$c_row['receipt_no']

									);

				$this->master_model->updateRecord('jaiib_settel_capacity_11april2021_558_move',$update_cap_arr2,$where_cap_arr2);

			}

		}

		

		$capacity = array_map("unserialize", array_unique(array_map("serialize", $capacity_full['capacity'])));

		echo $str = implode(",",$capacity);

	}

	

	

}

			

public function invoice_settel_553(){

	

	$invoice_arr = array(2884935,2885042,2885132,2885209,2885258,2885298,2885414,2885470,2885474,2885682,2885715,2885727,2885755,2885911,2885967,2886018,2886061,2886137,2886155,2886242,2886253,2886302,2886365,2886517,2886639,2886691,2886699,2886745,2886753,2886806,2886866,2886892,2886942,2887115,2887166,2887293,2887512,2887543,2887591,2887623,2887670,2887699,2887743,2887745,2887753,2887851,2887872,2887938,2887964,2888102,2888371,2888499,2888516,2888579,2888816,2888934,2888998,2889025,2889068,2889071,2889074,2889169,2889227,2889254,2889305,2889317,2889484,2889487,2889680,2890109,2890222,2890743,2891262,2891407,2891538,2892006,2892700,2893423,2893895,2894945,2895129,2896227,2896913,2897106,2897671,2897749,2897855,2897941,2898199,2898236,2898493,2899031,2899165,2899598,2899616,2899792,2900671,2900948,2901053,2901174,2901334,2901604,2901626,2901831,2901902,2902105,2902212,2902248,2902602,2902618,2902775,2902807,2902834,2903246,2903871,2904127,2904419,2904883,2905166,2905191,2905554,2905801,2905911,2906172,2906353,2906815,2906825,2907580,2907726,2908312,2908328,2908735,2908829,2908851,2909021,2909033,2909073,2909083,2909205,2909259,2909651,2909829,2910139,2910198,2910246,2910317,2910471,2910660,2910664,2910692,2910750,2910817,2911274,2911620,2911627,2911807,2911818,2911857,2911979,2912118,2912236,2912304,2912328,2912425,2912437,2912449,2912794,2913011,2913030,2913041,2913206,2913582,2913583,2913598,2914052,2914111,2914208,2914263,2914323,2914534,2914663,2914738,2914873,2914876,2914940,2915398,2915480,2915575,2915701,2915774,2916158,2916459,2916994,2917207,2917547,2917660,2917939,2918088,2918214,2918263,2918390,2918430,2918613,2918720,2918725,2918833,2918886,2918892,2918893,2918915,2919071,2919114,2919690,2919750,2919892,2919897,2919952,2919981,2920006,2920044,2920100,2920191,2920426,2920439); 

	 

	for($i=0;$i<sizeof($invoice_arr);$i++){

		

		

		$this->db->where('invoice_id',$invoice_arr[$i]);

		//$sql = $this->master_model->getRecords('invoice_553');

		//$sql = $this->master_model->getRecords('invoice_139');

		$sql = $this->master_model->getRecords('invoice_269');

		

		$this->db->where('invoice_id',$invoice_arr[$i]);

		$invoice_info = $this->master_model->getRecords('exam_invoice','','member_no');

		

		

		

		if(count($sql) <= 0){

		

			$resp_array = array('invoice_id'=>$invoice_arr[$i]);

			//$this->master_model->insertRecord('invoice_553', $resp_array);

			//$this->master_model->insertRecord('invoice_139', $resp_array);

			$this->master_model->insertRecord('invoice_269', $resp_array);

			

			$invoiceNumber =$this->generate_exam_invoice_number_wrong_invoice_553($invoice_arr[$i]);

			$invoiceNumber='EX/21-22/'.$invoiceNumber;

			

			$new_str123 = str_replace('/','_',$invoiceNumber);

			$invoice_image_name = $invoice_info[0]['member_no'].'_'.$new_str123.'.jpg';  

			$invoice_image_name = str_replace("/","_",$invoice_image_name);

			//700022994_EX_21-22_284440.jpg

			//EX/21-22/047850 

			

			//$date_of_invoice = '2021-04-01 10:00:00';

			//$date_of_invoice = '2021-04-29 10:00:00';

			$date_of_invoice = '2021-05-19 10:00:00';

			

			$update_data = array('invoice_no' => $invoiceNumber,'date_of_invoice'=>$date_of_invoice,'invoice_image'=>$invoice_image_name);

			$update_whr = array('invoice_id'=>$invoice_arr[$i]);

			$this->master_model->updateRecord('exam_invoice',$update_data,$update_whr);

			

			echo $this->db->last_query(); 

			echo '<br/>';

			

		

		}

	}

	

	

	

	

}			

			

function generate_exam_invoice_number_wrong_invoice_553($invoice_id= NULL)

{

		$last_id='';

		$CI = & get_instance();

		//$CI->load->model('my_model');

		if($invoice_id  !=NULL)

		{

			$insert_info = array('invoice_id'=>$invoice_id);

			$last_id = str_pad($CI->master_model->insertRecord('config_exam_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;

		}

		return $last_id;

	}			

	

	

	function generate_exam_invoice_number_wrong_invoice($invoice_id= NULL)

	{

		$last_id='';

		$CI = & get_instance();

		//$CI->load->model('my_model');

		if($invoice_id  !=NULL)

		{

			$insert_info = array('invoice_id'=>$invoice_id);

			$last_id = str_pad($CI->master_model->insertRecord('config_exam_invoice_31_3_2020',$insert_info,true), 6, "0", STR_PAD_LEFT);;

		}

		return $last_id;

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

						

						$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $pg_res[24],'auth_code' => $pg_res[14],'bankcode' => $pg_res[5],'paymode' => $pg_res[7],'callback'=>'c_S2SS');

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
							$this->sms_template_id = 'P6tIFIwGR';
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
							$this->sms_template_id = 'P6tIFIwGR';
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

						// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
						$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);

						//To Do---Transaction email to user	currently we using failure emailer 					

						if($this->Emailsending->mailsend($info_arr))

						{

							//redirect(base_url().'Home/details/'.base64_encode($MerchantOrderNo).'/'.$this->session->userdata['examinfo']['excd']);

						}

						}

						else if($payment_status==0)

						{

							// Handle transaction fail case 

							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $pg_res[24],'auth_code' => $pg_res[14],'bankcode' => $pg_res[5],'paymode' => $pg_res[7],'callback'=>'c_S2SS');

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
							$this->sms_template_id = 'Jw6bOIQGg';
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

							// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
							$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
												

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

