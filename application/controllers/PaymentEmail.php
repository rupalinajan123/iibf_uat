<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PaymentEmail extends CI_Controller {

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


	

	public function callback()

	{
			//$get_pg_flag=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>'32745','status'=>1),'pg_flag,receipt_no,member_regnumber');
			
			$query='SELECT pg_flag,receipt_no,member_regnumber FROM payment_transaction WHERE date <= NOW() - INTERVAL 20 MINUTE 
			AND date > NOW() - INTERVAL 40 MINUTE AND gateway = "sbiepay" AND status = 1';
			$get_pg_flag = $this->db->query($query);
			if ($get_pg_flag->num_rows())
			{
				foreach($get_pg_flag->result_array() as $emailRow)
				{
					$responsedata[6]=$emailRow['pg_flag'];
					$MerchantOrderNo=$emailRow['receipt_no'];
					$applicationNo=$emailRow['member_regnumber'];
					
					$resp_array = array('pg_flag'	=> $emailRow['pg_flag'],
									'receipt_no' 	=> $emailRow['receipt_no'],
									'member_regnumber' => $emailRow['member_regnumber']
									);
					$this->master_model->insertRecord('send_success_transaction_mail', $resp_array);
					
					if ($responsedata[6] == "iibfregn")
					{
								$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status,id,member_regnumber');
													$reg_id=$get_user_regnum_info[0]['ref_id'];
										$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,mobile,regnumber');
	
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
									$newstring = str_replace("#application_num#", "".$user_info[0]['regnumber']."",  $emailerstr[0]['emailer_text']);
									$final_str= str_replace("#password#", "".$decpass."",  $newstring);
									$info_arr=array(
															'to'=>$user_info[0]['email'],
															  'from'=>$emailerstr[0]['from'],
															  'subject'=>$emailerstr[0]['subject'].' '.$applicationNo,
															  'message'=>$final_str
															);
						$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum_info[0]['id']));
										$attachpath='https://iibf.esdsconnect.com/uploads/reginvoice/user/'.$getinvoice_number[0]['invoice_image'];
									if($attachpath!='')
						{	
								$this->Emailsending->mailsend_attch($info_arr,$attachpath);
						}
						}
					}
					else if ($responsedata[6] == "iibfren")
					{
							 $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status,id');
									$reg_id = $get_user_regnum_info[0]['ref_id'];
								  $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' =>'user_renewal_email'));
								if (count($emailerstr) > 0) {
									include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
									$key = $this->config->item('pass_key');
									$aes = new CryptAES();
									$aes->set_key(base64_decode($key));
									$aes->require_pkcs5();
									$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
									$newstring = str_replace("#application_num#", "" . $applicationNo . "", $emailerstr[0]['emailer_text']);
									$final_str = str_replace("#password#", "" . $decpass . "", $newstring);
									$info_arr = array(
									'to' => $user_info[0]['email'],
									'from' => $emailerstr[0]['from'], 
									'subject' => $emailerstr[0]['subject'].' '.$applicationNo, 
									'message' => $final_str);
									
									$getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $get_user_regnum_info[0]['id']));
										$attachpath=$attachpath='https://iibf.esdsconnect.com/uploads/renewal_invoice/user/'.$getinvoice_number[0]['invoice_image'];
										$this->Emailsending->mailsend_attch($info_arr, $attachpath);
								}
							
						
						
					}
					else if($responsedata[6] == "iibfdup")
					{
							// Handle transaction sucess case 
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,id');
							
							if(count($get_user_regnum) > 0)
							{
								$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
							}
							$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,id');
									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'duplicate_id'));
								   if(count($emailerstr) > 0 && (count($get_user_regnum) > 0))
									{
										//Query to get user details
										$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'namesub,firstname,middlename,lastname,email');
										$username=$user_info[0]['namesub'].' '.$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
										$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
										$newstring1 = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);
										$newstring2 = str_replace("#MEM_NO#", "".$get_user_regnum[0]['member_regnumber']."", $newstring1 );
										$final_str= str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring2);
										$info_arr = array(
										'to'=>$user_info[0]['email'],
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],
										'message'=>$final_str);
										//genertate invoice and email send with invoice attach 8-7-2017													//get invoice	
										$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum[0]['id']));
										$attachpath=$attachpath='https://iibf.esdsconnect.com/uploads/dupicardinvoice/user/'.$getinvoice_number[0]['invoice_image'];
										$this->Emailsending->mailsend($info_arr);
									}
								
							
						
					}
					else if($responsedata[6] == "iibfdupcer")
					{
						// Handle transaction sucess case 
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,id');
							
							if(count($get_user_regnum) > 0)
							{
								$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'isactive'=>'1'),'regnumber,usrpassword,email');
								//if member is DRA member
								if(empty($user_info))
								{
									$user_info = $this->master_model->getRecords('dra_members',array('regnumber'=>$get_user_regnum[0]['member_regnumber']));
								}
							}
							$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,id');
							
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'duplicate_cert'));
							if(count($emailerstr) > 0 && (count($get_user_regnum) > 0))
							{
									$final_str = $emailerstr[0]['emailer_text'];
									$info_arr = array('to'=>$user_info[0]['email'],'from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'].' '.$get_payment_status[0]['member_regnumber'],'message'=>$final_str);
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum[0]['id']));
									$attachpath=$attachpath='https://iibf.esdsconnect.com/uploads/dupcertinvoice/user/'.$getinvoice_number[0]['invoice_image'];
									$this->Emailsending->mailsend_attch($info_arr,$attachpath);
								}
							
					}
					else if($responsedata[6] == "IIBF_EXAM_O")
					{
									$exam_period_date='';
									$elective_subject_name='';
									$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
										if($get_user_regnum[0]['exam_code']=='1016')
										{
											$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(
											'receipt_no' => $MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
											$reg_id        = $get_user_regnum_info[0]['ref_id'];
											$applicationNo = $get_user_regnum_info[0]['member_regnumber'];
											$reg_data = $this->master_model->getRecords('caiib_jaiib_newexam_registration', array('member_no' => $applicationNo, 'mem_exam_id' => $reg_id),'exam_code');
					$selected_exam_code =$reg_data[0]['exam_code'];
					$attemptQry=$this->db->query("SELECT attempt,fee_flag FROM caiib_jaiib_newexam_eligible WHERE member_no='".$applicationNo."' AND exam_code = '" . $selected_exam_code . "' LIMIT 1"); 
					$attemptArr = $attemptQry->row_array();
					$attempt = $attemptArr['attempt'];
					$fee_flag=$attemptArr['fee_flag'];
					$attempt = $attempt+1;
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'charterd_email'));
					if (!empty($applicationNo)) {
									$user_info = $this->master_model->getRecords('member_registration',array('regnumber'=> $applicationNo,'isactive'=>'1'),'email,mobile');
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
										'subject' => $emailerstr[0]['subject'].' '.$applicationNo,
										'message' => $newstring
									);
									$getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo,'pay_txn_id' => $get_user_regnum_info[0]['id']));
					  $zone_code = ""; 
					  $zoneArr = array();
					  //$regno = $this->session->userdata['memberdata']['regno'];
					  $zoneArr = $this->master_model->getRecords('caiib_jaiib_newexam_registration',array('mem_exam_id'=>$reg_id,'pay_status'=>1),'gstin_no');
					 
					  $gstin_no          = $zoneArr[0]['gstin_no'];
					  /* Invoice Number Genarate Functinality */
									$attachpath='https://iibf.esdsconnect.com/uploads/examinvoice/user/'.$getinvoice_number[0]['invoice_image'];
									if ($attachpath != '') 
					  { 
				  /* Email Send To Clints */
				  
				  if (!empty($applicationNo)) {
									$reg_info = $this->master_model->getRecords('caiib_jaiib_newexam_registration',array('member_no'=> $applicationNo,'mem_exam_id' => $reg_id));
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
											$final_sub = str_replace("#exam_code#", "".$reg_info[0]['exam_code']."",  $final_selfstr);
										  
							$self_mail_arr = array(
							'to'=>$emails,
							'from'=>$emailerSelfStr[0]['from'],
							'subject'=>$final_sub.' '.$reg_info[0]['member_no'],
							'message'=>$final_selfstr); 
							$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);
						  }
						}
					}
				}
			}
										else
										{
											
											######### payment Transaction ############
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
											$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
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
															$newstring22 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring21);														}
														else
														{
															$newstring22 = str_replace("#E-MSG#", '',$newstring21);														}
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
															$newstring22 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring21);														}
														else
														{
															$newstring22 = str_replace("#E-MSG#", '',$newstring21);														}
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
										//echo $this->db->last_query();exit;
									########### generate invoice ###########
									$attachpath=$attachpath='https://iibf.esdsconnect.com/uploads/examinvoice/user/'.$getinvoice_number[0]['invoice_image'];
									$admitcard_pdf='https://iibf.esdsconnect.com/uploads/admitcardpdf/'.$exam_admicard_details[0]['admitcard_image'];
									$files=array($attachpath,$admitcard_pdf);
									$this->Emailsending->mailsend_attch($info_arr,$files);
									
											
								}
							}
					else if($responsedata[6] == "IIBF_EXAM_NM")
					{
						$exam_period_date=$attachpath=$invoiceNumber='';
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
						$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
						
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
								$newstring21 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring20);										}
							else
							{
								$newstring21 = str_replace("#E-MSG#", '',$newstring20);										}
						}
						else
						{
							$newstring21 = str_replace("#E-MSG#", '',$newstring20);
						}
						$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
					}
							$info_arr=array(
							'to'=>$result[0]['email'],
							'from'=>$emailerstr[0]['from'],
							'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],
							'message'=>$final_str
						);
					//get invoice	
					$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
					
					$attachpath=$attachpath='https://iibf.esdsconnect.com/uploads/examinvoice/user/'.$getinvoice_number[0]['invoice_image'];
					$admitcard_pdf='https://iibf.esdsconnect.com/uploads/admitcardpdf/'.$exam_admicard_details[0]['admitcard_image'];		
					$files=array($attachpath,$admitcard_pdf);	
					$this->Emailsending->mailsend_attch($info_arr,$files);
					
					
					
					
						
					}
					else if($responsedata[6] == "IIBF_EXAM_REG")
					{

										$exam_period_date=$exam_admicard_details='';
										$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
										
										$exam_code=$get_user_regnum[0]['exam_code'];
										$reg_id=$get_user_regnum[0]['member_regnumber'];
										########## Generate Admit card and allocate Seat #############
										if($exam_code!=101)
										{
											$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
										}
										//Query to get exam details	
										$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
										$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
										$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
										$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
										$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.examination_date');
										########## Generate Admit card and allocate Seat #############
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
										$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$applicationNo),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,usrpassword');	
									
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
												$newstring21 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring20);											}
											else
											{
												$newstring21 = str_replace("#E-MSG#", '',$newstring20);											}
										}
										else
										{
											$newstring21 = str_replace("#E-MSG#", '',$newstring20);
										}
											$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
											$info_arr=array(
																'to'=>$result[0]['email'],
																'from'=>$emailerstr[0]['from'],
																'subject'=>$emailerstr[0]['subject'].' '.$applicationNo,
																'message'=>$final_str
															);
										//get invoice	
										$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
										$attachpath=$attachpath='https://iibf.esdsconnect.com/uploads/examinvoice/user/'.$getinvoice_number[0]['invoice_image'];
										$admitcard_pdf='https://iibf.esdsconnect.com/uploads/admitcardpdf/'.$exam_admicard_details[0]['admitcard_image'];
										$files=array($attachpath,$admitcard_pdf);	
										$this->Emailsending->mailsend_attch($info_arr,$files);
					
					}	
					else if($responsedata[6] == "IIBF_EXAM_DB")
					{
									  $get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
										
										// Handle transaction success case 
										$exam_code=$get_user_regnum[0]['exam_code'];
										$reg_id=$get_user_regnum[0]['member_regnumber'];
										############check capacity is full or not ##########
										$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
										
										
									   $this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
										$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
									$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
										$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
										$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
									
										if($exam_info[0]['exam_mode']=='ON')
										{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
										{$mode='Offline';}
										else{$mode='';}
										
										$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
										$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
										
										$this->db->where('exam_code',$exam_code);
										$this->db->where('exam_period',$exam_info[0]['exam_period']);
										$this->db->where('medium_code',$exam_info[0]['exam_medium']);
										$this->db->where('medium_delete','0');
										$medium=$this->master_model->getRecords('medium_master','','medium_description');
										
										$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNo),'transaction_no,date,amount,id');
									
										$this->db->join('state_master','state_master.state_code=member_registration.state');
										
										$result=$this->master_model->getRecords('member_registration',array('regnumber '=>$applicationNo),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto');	
										
										include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
										$key = $this->config->item('pass_key');
										$aes = new CryptAES();
										$aes->set_key(base64_decode($key));
										$aes->require_pkcs5();
										$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
										
										$upd_files = array();
										
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
												$newstring21 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring20);												}
											else
											{
												$newstring21 = str_replace("#E-MSG#", '',$newstring20);												}
										}
										else
										{
											$newstring21 = str_replace("#E-MSG#", '',$newstring20);
										}
										$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
										$info_arr=array('to'=>$result[0]['email'],
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'].' '.$applicationNo,
										'message'=>$final_str
										);
															
										$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
										
											$attachpath=$attachpath='https://iibf.esdsconnect.com/uploads/examinvoice/user/'.$getinvoice_number[0]['invoice_image'];
											$admitcard_pdf='https://iibf.esdsconnect.com/uploads/admitcardpdf/'.$exam_admicard_details[0]['admitcard_image'];
										
											
											$files=array($attachpath,$admitcard_pdf);
											$this->Emailsending->mailsend_attch($info_arr,$files);
										
										
									
										
									
								
					}	
					else if($responsedata[6] == "IIBF_EXAM_DB_EXAM")
					{
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
						if(count($get_user_regnum) > 0)
						{
						$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
						}
						$this->db->join('state_master','state_master.state_code=member_registration.state');
						$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword');
						$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
						$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
						$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');										$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
						$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
						########## Generate Admit card and allocate Seat #############
						$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
						if($exam_info[0]['exam_mode']=='ON')
						{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
						{$mode='Offline';}
						else{$mode='';}
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
						$newstring21 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring20);											}
						else
						{
						$newstring21 = str_replace("#E-MSG#", '',$newstring20);											}
						}
						else
						{
						$newstring21 = str_replace("#E-MSG#", '',$newstring20);
						}
						$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
										$info_arr=array(
										'to'=>$result[0]['email'],
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],
										'message'=>$final_str
									);
									
						//get invoice	
						$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,
						'pay_txn_id'=>$payment_info[0]['id']));
						$attachpath=$attachpath='https://iibf.esdsconnect.com/uploads/examinvoice/user/'.$getinvoice_number[0]['invoice_image'];
						$admitcard_pdf='https://iibf.esdsconnect.com/uploads/admitcardpdf/'.$exam_admicard_details[0]['admitcard_image'];
						$files=array($attachpath,$admitcard_pdf);
						$this->Emailsending->mailsend_attch($info_arr,$files);
					}
					else if($responsedata[6] == "iibfmisc")
					{
						
							$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id','','','1');
							
								$gst_recovery_details_pk = $get_user_regnum_info[0]['ref_id'];
								$member_regnumber        = $get_user_regnum_info[0]['member_regnumber'];
								
								$get_exam_period = $this->master_model->getRecords('gst_recovery_details', array('gst_recovery_details_pk' => $get_user_regnum_info[0]['ref_id']), 'exam_period','','','');
								
								if($get_exam_period[0]['exam_period'] == '552')
								{
									$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_bcbf_gst_recovery_email'),'','','1');
								}
								else
								{
									$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_gst_recovery_email'),'','','1');
								}
							
								if (!empty($member_regnumber)) 
								{
									$user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $member_regnumber, 'isactive' => '1'), 'email,mobile','','','1');
									
								}
								
								if (count($emailerstr) > 0) 
								{ 
									/* Set Email sending options */
									$info_arr   = array(
										'to' => $user_info[0]['email'],
										'from' => $emailerstr[0]['from'],
										'subject' => $emailerstr[0]['subject'],
										'message' => $emailerstr[0]['emailer_text']
									);
									$getinvoice_number=$this->master_model->getRecords('gst_recovery_details',array('id'=>$$gst_recovery_details_pk));
									$attachpath ='https://iibf.esdsconnect.com/uploads/gst_recovery_invoice/user/'.$getinvoice_number[0]['doc_image'];
									if ($getinvoice_number[0]['doc_image']!= '') 
									{
										$this->Emailsending->mailsend_attch($info_arr, $attachpath);
									} 
									else 
									{
										if($get_exam_period[0]['exam_period'] == '552')
										{
											if ($this->Emailsending->mailsend($info_arr)) 
											{
												$anil_info_arr   = array(  
												'to' => 'anil@iibf.org.in',
												'from' => $emailerstr[0]['from'],
												'subject' => "BCBF 8 Rs Fee Paid",
												'message' => "Member No.".$member_regnumber);
												$this->Emailsending->mailsend($anil_info_arr);
																						
											} 
										}
									}
								}
							
						
					}
					else if ($responsedata[6] == "iibftrg")
					{
							 $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id');
								$reg_id        = $get_user_regnum_info[0]['ref_id'];
								$applicationNo = $get_user_regnum_info[0]['member_regnumber'];
								$reg_data = $this->master_model->getRecords('blended_registration', array('member_no' => $applicationNo,'blended_id' => $reg_id),'program_code,center_code,batch_code,training_type,venue_code,start_date');
								$selected_program_code = $reg_data[0]['program_code'];
								$selected_center_code = $reg_data[0]['center_code'];
								$venue_batch_code = $reg_data[0]['batch_code'];
								$selected_training_type = $reg_data[0]['training_type'];
								$selected_venue_code	= $reg_data[0]['venue_code'];
								$sDate = $reg_data[0]['start_date'];
								
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_blended_email'));
								if (!empty($applicationNo)) {
									$user_info = $this->master_model->getRecords('member_registration',array('regnumber'=> $applicationNo,'isactive'=>'1'),'email,mobile');
								}
								if (count($emailerstr) > 0) 
								{
									$Qry=$this->db->query("SELECT program_code, program_name, training_type, center_name, venue_name, start_date, end_date,member_no FROM blended_registration WHERE blended_id = '".$reg_id."' LIMIT 1");
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
										'subject' => $emailerstr[0]['subject'].' '.$detailsArr['member_no'],
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
									$attachpath='https://iibf.esdsconnect.com/uploads/blended_invoice/user/'.$zone_code.'/'.$getinvoice_number[0]['invoice_image'];
									
									if ($attachpath != '') 
									{	
									if (!empty($applicationNo)) {
									$reg_info = $this->master_model->getRecords('blended_registration',array('member_no'=> $applicationNo,'blended_id' => $reg_id));
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
									} 
								}
							
					}
					else if($responsedata[6] == "IIBFDRAREG")
					{
						// Handle transaction success case                    
						$get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status,id');
						if (count($get_user_regnum) > 0)
						{
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
						$check_status = $this->master_model->getRecords('dra_accerdited_master',array('dra_inst_registration_id' => $agency_id,'pay_status' => '1'),'pay_status');
							
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
										   
							
							$getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
								'receipt_no' => $MerchantOrderNo,
								'pay_txn_id' => $get_user_regnum[0]['id']
							));
							$attachpath=$attachpath='https://iibf.esdsconnect.com/uploads/drainvoice/user/'.$getinvoice_number[0]['invoice_image'];  
							$this->Emailsending->mailsend_attch_DRA($info_arr, $attachpath);
						}
							
					}
					else if($responsedata[6] == "IIBFDRAREN")
					{
							// Handle transaction success case 		
								$get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status,id');
								
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
									}													// Get email of institute by its id                        
									
								$agency_info = $this->master_model->getRecords('agency_center_renew', array(
									'agency_renew_id' => $get_user_regnum[0]['ref_id']
								));	
								$agency_id 	= $agency_info[0]['agency_id']; 		
								$center_ids = $agency_info[0]['centers_id']; 
								$center_arr = explode(',',$center_ids);
								// ADD CODE TO SET PAY STATUS 1: SUCCESS  
								
								//===============================================================================												 
								//$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
								$emailerstr = $this->master_model->getRecords('emailer', array(
									'emailer_name' => 'dra_agency_renew'
								));
										if (count($emailerstr) > 0 && (count($get_user_regnum) > 0)) 
										{
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
									$attachpath=$attachpath='https://iibf.esdsconnect.com/uploads/agency_renewal_invoice/user/'.$getinvoice_number[0]['invoice_image'];	
									
									if ($attachpath != '') {
											if ($this->Emailsending->mailsend_attch_DRA($info_arr, $attachpath)) {
										   
										} 
									} 
								}
																	
													
					}
					else if($responsedata[6] == "IIBF_EL")
					{
						
							$exam_period_date='';
							//Handle transaction success case
							$elective_subject_name='';
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
							
							
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
								
								$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,id');
								$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
								$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
								
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
										$newstring17 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring16);								}
									else
									{
										$newstring17 = str_replace("#E-MSG#", '',$newstring16);								}
								}
								else
								{
									$newstring17 = str_replace("#E-MSG#", '',$newstring16);
								}
								$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring17);
								$info_arr=array(
								'to'=>$result[0]['email'],
								'from'=>$emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],
								'message'=>$final_str
							);
							$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
							$attachpath=$attachpath='https://iibf.esdsconnect.com/uploads/examinvoice/user/'.$getinvoice_number[0]['invoice_image'];
							$files=array($attachpath);
							$this->Emailsending->mailsend_attch($info_arr,$files);
								
					}
					else if($responsedata[6] == "IIBF_ELR")
					{
									// Handle transaction success case 
									$exam_period_date='';
									$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
									
										$exam_code=$get_user_regnum[0]['exam_code'];
										$reg_id=$get_user_regnum[0]['member_regnumber'];
										
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
										$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNo),'transaction_no,date,amount,id');
										//Query to get user details
										$this->db->join('state_master','state_master.state_code=member_registration.state');
										$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$applicationNo),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,usrpassword');	
										
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
												$newstring18 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring17);											}
											else
											{
												$newstring18 = str_replace("#E-MSG#", '',$newstring17);											}
										}
										else
										{
											$newstring18 = str_replace("#E-MSG#", '',$newstring17);
										}
										$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring18);
										$info_arr=array(
										'to'=>$result[0]['email'],
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'].' '.$applicationNo,
										'message'=>$final_str
										);
										//get invoice	
										$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
										$attachpath='https://iibf.esdsconnect.com/uploads/examinvoice/user/'.$getinvoice_number[0]['invoice_image'];
										$files=array($attachpath);
										$this->Emailsending->mailsend_attch($info_arr,$files);
										
									
							
					}			
					else if($responsedata[6] == "iibfcpd")
					{
									
							$exam_period_date='';
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
							
							
											if(count($get_user_regnum) > 0)
											{
												$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'isactive'=>'1'),'regnumber,usrpassword,email');
											}
										$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'cpd'));
										if(count($emailerstr) > 0 && (count($get_user_regnum) > 0))
										{
											$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum[0]['id']));
									
										 $attachpath='https://iibf.esdsconnect.com/uploads/cpdinvoice/user/'.$getinvoice_number[0]['invoice_image'];
											$final_str = $emailerstr[0]['emailer_text'];
											$info_arr = array(
											'to'=>$user_info[0]['email'],
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'].' '.$user_info[0]['regnumber'],
											'message'=>$final_str);
											$this->Emailsending->mailsend_attch_cpd($info_arr,$attachpath);
										
									}
								
							
						
				
					}
					}
			}
			
			
			//$get_pg_flag_amp=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>'','status'=>1),'pg_flag,receipt_no,member_regnumber');
			//exit;
			
			$query1='SELECT pg_flag,receipt_no,member_regnumber FROM amp_payment_transaction WHERE date <= NOW() - INTERVAL 20 MINUTE 
			AND date > NOW() - INTERVAL 40 MINUTE AND gateway = "sbiepay" AND status = 1';
			$get_pg_flag_amp = $this->db->query($query1);
			
			if ($get_pg_flag_amp->num_rows())
			{
				foreach($get_pg_flag_amp->result_array() as $emailRow)
				{
					$MerchantOrderNo=$emailRow['receipt_no'];
					$applicationNo=$emailRow['member_regnumber'];
					
					$resp_array = array('pg_flag'	=> $emailRow['pg_flag'],
									'receipt_no' 	=> $emailRow['receipt_no'],
									'member_regnumber' => $emailRow['member_regnumber']
									);
					$this->master_model->insertRecord('send_success_transaction_mail', $resp_array);
					
								$get_user_regnum_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'id,ref_id,status,payment_option ');
								if($get_user_regnum_info[0]['payment_option']==1 || $get_user_regnum_info[0]['payment_option']==4)
								{
									$reg_id=$get_user_regnum_info[0]['ref_id'];
									$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id));
									$payment_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$user_info[0]['regnumber']),'transaction_no,date,amount');
									$upd_files = array();
									
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
										$info_arr=array(
										'to'=>$user_info[0]['email_id'],
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'].' '.$user_info[0]['regnumber'],
										'message'=>$final_str,
										);
									
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum_info[0]['id']));
									$attachpath=$attachpath='https://iibf.esdsconnect.com/uploads/ampinvoice/user/'.$getinvoice_number[0]['invoice_image'];
										 
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
													$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);
												}
											}
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
									$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id));
									$payment_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$user_info[0]['regnumber']),'transaction_no,date,amount');
									
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
										$info_arr=array(
										'to'=>$user_info[0]['email_id'],
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'].' '.$user_info[0]['regnumber'],
										'message'=>$final_str,
										//'bcc'=>'kumartupe@gmail.com,raajpardeshi@gmail.com'
										);
										$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum_info[0]['id']));
										$attachpath='https://iibf.esdsconnect.com/uploads/ampinvoice/user/'.$getinvoice_number[0]['invoice_image'];
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
														'to'=>'ravita@iibf.org.in,training@iibf.org.in',
														'from'=>$emailerSelfStr[0]['from'],
														'subject'=>$emailerSelfStr[0]['subject'],
														'message'=>$final_selfstr,
														);
														$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);
													}
												}
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
									}
								}
							}
			}
			
			
	public function 	pending_status_email()
	{
			$query='SELECT pg_flag,receipt_no,member_regnumber,ref_id FROM payment_transaction WHERE date <= NOW() - INTERVAL 120 MINUTE 

			AND date > NOW() - INTERVAL 240 MINUTE AND gateway = "sbiepay" AND status = 2 ';
			$get_pg_flag = $this->db->query($query);
			if ($get_pg_flag->num_rows())
			{
				$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'pending_status_email'));
				foreach($get_pg_flag->result_array() as $emailRow)
				{
					$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$emailRow['member_regnumber']),'regnumber,email');
					if(count($user_info) <0)
					{
					$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$emailRow['ref_id']),'regnumber,email');
					}
					
					$resp_array = array('email'	=> $user_info[0]['email'],
					'member_regnumber' => $user_info[0]['regnumber']
					);
					$this->master_model->insertRecord('send_pending_transaction_mail', $resp_array);
					
								$info_arr=array(			'to'=>$user_info[0]['email'],
																	'from'=>$emailerstr[0]['from'],
																	'subject'=>$emailerstr[0]['subject'].' '.$user_info[0]['regnumber'],
																	'message'=>$emailerstr[0]['emailer_text']
																);
									$this->Emailsending->mailsend_attch($info_arr);
				}
			}
	
	}	
			
			
		}