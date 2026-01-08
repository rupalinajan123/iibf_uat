<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PaymentS2S_nopayment extends CI_Controller {

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

		ini_set('display_errors', 1);

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

			

			$query="SELECT * FROM no_payment where txn_status IN('SUCCESS') AND status = 0 LIMIT 1";

			$crnt_day_txn_qry = $this->db->query($query);
			$sms_template_id = '';
			

			//echo "*********************************** New Cron Request Started***************************\n";

			//echo  "Total Count =>". $crnt_day_txn_qry->num_rows();

			//echo $this->db->last_query();

			//echo '<br/>';

		$ex_arr = array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB'));

		if ($crnt_day_txn_qry->num_rows())

		{

			foreach ($crnt_day_txn_qry->result_array() as $c_row)

			{

				echo $c_row['receipt_no'];

				echo '<br/>';

				

				$no_payment_update_remark = array('status'=>'1');

				$no_payment_remark = array('id'=>$c_row['id']);

				$this->master_model->updateRecord('no_payment',$no_payment_update_remark,$no_payment_remark);

				

				

				$this->db->where('receipt_no',$c_row['receipt_no']);

				$get_payment_member = $this->master_model->getRecords('payment_transaction','','member_regnumber,id');

				

				$this->db->where('status',1);

				$this->db->where('pay_type',2);

				$this->db->where('date > ','2021-02-23 00:00:01');

				$this->db->where_in('exam_code',$ex_arr);

				$this->db->where('member_regnumber',$get_payment_member[0]['member_regnumber']);

				$chk_member_payment_status = $this->master_model->getRecords('payment_transaction','','member_regnumber,id,receipt_no');

				

				//echo $this->db->last_query();

				

				if(count($chk_member_payment_status) > 0){ 

					// add data in temp refund table

					$this->db->where('status',2);

					$this->db->where('pay_type',2);

					$this->db->where('date > ','2021-02-23 00:00:01');

					$this->db->where_in('exam_code',$ex_arr);

					$this->db->where('member_regnumber',$get_payment_member[0]['member_regnumber']);

					$refundable_receipt_no = $this->master_model->getRecords('payment_transaction','','receipt_no');

					

					foreach($refundable_receipt_no as $refundable_receipt_no_rec){

						$invoice_insert_array = array('receipt_no'=>$payment[0]['receipt_no']);

						$this->master_model->insertRecord('refund_table', $invoice_insert_array);	

					}  

					

				} 

				else

				{

				

				 

				//echo $c_row['receipt_no'];

				//echo '<br/>';

				//sleep(1);

				$responsedata = $c_row['txn_data'];

				$receipt_no=$c_row['receipt_no'];

				$encData=implode('|',$responsedata);

				$resp_data = json_encode($responsedata);

				$responsedata = explode('|',$responsedata);;

			

				## Check payment_c_s2s_log entry 

				$data_count = $this->master_model->getRecordCount('payment_transaction',array('receipt_no'=>$receipt_no,'status'=>1));

								

				$resp_array = array('receipt_no'	=> $c_row['receipt_no'],

									'txn_status' 	=> $responsedata[2],

									'txn_data' 		=> $encData.'&CALLBACK=C_S2S',

									'response_data' => $resp_data,

									'remark' 		=> '',

									'resp_date' 	=> date('Y-m-d H:i:s'),

									);

				$this->master_model->insertRecord('payment_c_s2s_log', $resp_array);

				//echo $this->db->last_query();

				//exit;

				/*echo '<pre>';

				print_r($responsedata);

				echo '</pre>';*/

			

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

				

				if($responsedata[12] == "IIBF_EXAM_O")

				{

					sleep(8);

					$MerchantOrderNo = $responsedata[6];  

					$transaction_no  = $responsedata[1];

					$payment_status = 2;

					$attachpath=$invoiceNumber=$admitcard_pdf='';

					

					//echo '>>>'. $responsedata[2];

					//exit;

					

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

								if($get_user_regnum[0]['status']==2)

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

												$log_message = '';

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
												$sms_template_id = 'P6tIFIwGR';

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
												$sms_template_id = 'S8OmhSQGg';

												$final_str = $emailerstr[0]['emailer_text'];

											}else if($exam_info[0]['exam_code']==993)

											{

												$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'Cisi_emailer'));
												$sms_template_id = 'gewX5IwGR';

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

												$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);

												

												$log_title ="C_S2S exam invoice generate number:".$getinvoice_number[0]['invoice_id'];

												$log_message = '';

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

										

										$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));

										$this->db->where('pay_txn_id',$payment_info[0]['id']);

										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));

										

										$log_title ="C_S2S exam invoice Update:".$get_user_regnum[0]['member_regnumber'];

										$log_message = '';

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

											// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
											$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$sms_template_id);

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

			}

			else

			{

				//die("Please try again...");

				echo "Please try again...";

			}

			

			}

			

		}//foreach

			

					

			}

	}

	

	public function refund_case(){ 

		$this->db->where('txn_status','SUCCESS');

		$this->db->where('status',0);

		//$this->db->limit(10); 

		$sql = $this->master_model->getRecords('no_payment');

		foreach($sql as $rec){

			

			$no_payment_update_remark = array('status'=>'1');

			$no_payment_remark = array('receipt_no'=>$rec['receipt_no']);

			$this->master_model->updateRecord('no_payment',$no_payment_update_remark,$no_payment_remark);

			

			$this->db->where('receipt_no',$rec['receipt_no']);

			$payment = $this->master_model->getRecords('payment_transaction','','status');

			

			$this->db->where('receipt_no',$rec['receipt_no']);

			$invoice = $this->master_model->getRecords('exam_invoice','','invoice_no,transaction_no');

			

			

			if($invoice[0]['invoice_no'] == '' && $invoice[0]['transaction_no'] == '' && $payment[0]['status'] == 2){

				$invoice_insert_array = array('receipt_no'=>$rec['receipt_no']);

				$this->master_model->insertRecord('refund_table', $invoice_insert_array); 

			}

			

		}

	}

	

	

	public function refund_case_other_exam(){  

		$this->db->where('txn_status','SUCCESS');

		$this->db->where('status',0);

		//$this->db->limit(10); 

		$sql = $this->master_model->getRecords('no_payment_other_exam');  

		foreach($sql as $rec){

			

			$no_payment_update_remark = array('status'=>'1');

			$no_payment_remark = array('receipt_no'=>$rec['receipt_no']);

			$this->master_model->updateRecord('no_payment_other_exam',$no_payment_update_remark,$no_payment_remark);

			

			$this->db->where('receipt_no',$rec['receipt_no']);

			$payment = $this->master_model->getRecords('payment_transaction','','status');

			

			$this->db->where('receipt_no',$rec['receipt_no']);

			$invoice = $this->master_model->getRecords('exam_invoice','','invoice_no,transaction_no');

			

			

			if($invoice[0]['invoice_no'] == '' && $invoice[0]['transaction_no'] == '' && $payment[0]['status'] == 2){

				$invoice_insert_array = array('receipt_no'=>$rec['receipt_no']);

				$this->master_model->insertRecord('refund_table_other_exam', $invoice_insert_array); 

			}

			

		}

	}

	

	public function refund_case_registration(){ 

		$this->db->where('txn_status','SUCCESS');

		$this->db->where('status',0);

		//$this->db->limit(10); 

		$sql = $this->master_model->getRecords('no_payment_registration');  

		foreach($sql as $rec){

			

			$no_payment_update_remark = array('status'=>'1');

			$no_payment_remark = array('receipt_no'=>$rec['receipt_no']);

			$this->master_model->updateRecord('no_payment_registration',$no_payment_update_remark,$no_payment_remark);

			

			$this->db->where('receipt_no',$rec['receipt_no']);

			$payment = $this->master_model->getRecords('payment_transaction','','status');

			

			$this->db->where('receipt_no',$rec['receipt_no']);

			$invoice = $this->master_model->getRecords('exam_invoice','','invoice_no,transaction_no');

			

			

			if($invoice[0]['invoice_no'] == '' && $invoice[0]['transaction_no'] == '' && $payment[0]['status'] == 2){

				$invoice_insert_array = array('receipt_no'=>$rec['receipt_no']);

				$this->master_model->insertRecord('refund_table_registration', $invoice_insert_array); 

			}

			

		} 

	}

	

	

	public function history_regnumber(){

		$member_number = $this->uri->segment(3);

		

		$this->db->where('isactive','1');

		$this->db->where('regnumber',$member_number);

		$this->db->orderby('regid','desc');

		$member_registration = $this->master_model->getRecords('member_registration','','regid,regnumber,isactive,createdon');

		

		

		$this->db->where('member_regnumber',$member_number);

		$this->db->orderby('id','desc');

		$payment_transaction = $this->master_model->getRecords('payment_transaction','','id,member_regnumber,exam_code,transaction_no,receipt_no,pay_type,ref_id,status,date');

		

		

		$this->db->where('member_no',$member_number);

		$this->db->orderby('invoice_id','desc');

		$exam_invoice = $this->master_model->getRecords('exam_invoice','','invoice_id,exam_code');

	}

	

}

