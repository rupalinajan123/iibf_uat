<?php
## Cron  - Execute after every 2 hours
## Set to check member registrations via S2S after every 2 hours and see if registration successful mail is sent to user or not 
## If mail not sent then this script will send mail to such users
defined('BASEPATH') OR exit('No direct script access allowed');
class CronS2S extends CI_Controller {
	public function __construct(){
		parent::__construct();
		//$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		//$this->load->helper('master_helper');
		//$this->load->helper('general_helper');
		//$this->load->library('email');
		$this->load->model('Emailsending');
		//$this->load->model('log_model');
		
	}
	public function index()
	{
		
		echo $app_server=explode('.',gethostname());if(isset($app_server[0])){echo $app_server[0];}
		echo '<br/>';
		echo '<br/>';
		echo '<pre>';
		print_r($_SERVER);
		echo '</pre>';
		echo '<br/>';
		
		$elective_subject_name = ''; 
		$exam_period_date = '';
		$attachpath_admitcard = '';
		## Get records from payment_transaction
		//SELECT * FROM `payment_transaction` WHERE `status` = 1 AND `callback` = 'S2S' AND date >= DATE_SUB(NOW(),INTERVAL 2 HOUR)

		$this->db->where('date >= DATE_SUB(NOW(),INTERVAL 2 HOUR)');
		$this->db->limit(1);
		$records = $this->Master_model->getRecords('payment_transaction',array('callback'=>'S2S','status'=>'1'));
		echo "<br/>".$this->db->last_query();
		//print_r($records);
		if(!empty($records) && count($records) > 0)
		{
			foreach($records as $row)
			{
				$member_regnumber = $row['member_regnumber'];
				## Get email address from member_registration
				//$this->db->limit(1);
				$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
				$result = $this->Master_model->getRecords('member_registration',array('regnumber'=>$row['member_regnumber']),'firstname,middlename,lastname,regnumber,address1,address2,address3,address4,district,city,email,office,institution_master.name as institute_name,pincode,mobile,usrpassword,scannedphoto,scannedsignaturephoto,idproofphoto,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img');
				//echo "<br/>".$this->db->last_query();
				if(!empty($result) && count($result) > 0)
				{
						if($row['pay_type'] == 2)
						{
							// Apply Exam	
							## Get exam details
							$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period','LEFT');
							$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code','LEFT');
							$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period','LEFT');
							$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period','LEFT');
							$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$row['member_regnumber'],'member_exam.id'=>$row['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code,center_master.center_name');
					
							/*echo $this->db->last_query();
							
							echo '<pre>';
							print_r($exam_info);
							exit;*/

							/*if($exam_info[0]['exam_mode']=='ON')
							{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
							{$mode='Offline';}
							else{$mode='';}*/
							$mode='Online';

							if(isset($exam_info[0]['examination_date']) && $exam_info[0]['examination_date']!='0000-00-00')
							{
								$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
							}
							else
							{
								//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
								if(isset($exam_info[0]['exam_month'])){
								$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
								$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2); }
							}

							//Query to get Medium	
							$this->db->where('exam_code',$exam_info[0]['exam_code']);
							$this->db->where('exam_period',$exam_info[0]['exam_period']);
							$this->db->where('medium_code',$exam_info[0]['exam_medium']);
							$this->db->where('medium_delete','0');
							$medium=$this->Master_model->getRecords('medium_master','','medium_description');
							
							$this->db->where('state_delete','0');
							$states=$this->Master_model->getRecords('state_master',array('state_code'=>$exam_info[0]['state_place_of_work']),'state_name');

							## code to send email
							$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
							$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
						
							if($exam_info[0]['place_of_work']!='' && $exam_info[0]['state_place_of_work']!='' && $exam_info[0]['pin_code_place_of_work']!='')
									{
										//get Elective Subeject name for CAIIB Exam	
									   if($exam_info[0]['elected_sub_code']!=0 && $exam_info[0]['elected_sub_code']!='')
									   {
										   $elective_sub_name_arr=$this->Master_model->getRecords('subject_master',array('subject_code'=>$exam_info[0]['elected_sub_code'],'subject_delete'=>0),'subject_description');
										
											if(count($elective_sub_name_arr) > 0)
											{
												$elective_subject_name=$elective_sub_name_arr[0]['subject_description'];

											}	
									   }
										   
										$emailerstr=$this->Master_model->getRecords('emailer',array('emailer_name'=>'member_exam_enrollment_nofee_elective'));
										$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
										$newstring2 = str_replace("#REG_NUM#", "".$result[0]['regnumber']."",$newstring1);
										$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
										$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
										$newstring5 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring4);
										$newstring6 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring5);
										$newstring7 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring6);
										$newstring8 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring7);
										$newstring9 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring8);
										$newstring10 = str_replace("#CITY#", "".$result[0]['city']."",$newstring9);
										$newstring11 = str_replace("#STATE#", "".$states[0]['state_name']."",$newstring10);
										$newstring12 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring11);
										$newstring13 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring12);
										$newstring14 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring13);
										$newstring15 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring14);
										$newstring16 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring15);
										$newstring17 = str_replace("#ELECTIVE_SUB#", "".$elective_subject_name."",$newstring16);
										$newstring18 = str_replace("#AMOUNT#", "".$row['amount']."",$newstring17);
										$newstring19 = str_replace("#PLACE_OF_WORK#", "".strtoupper($exam_info[0]['place_of_work'])."",$newstring18);
										$newstring20 = str_replace("#STATE_PLACE_OF_WORK#", "".$states[0]['state_name']."",$newstring19);
										$newstring21 = str_replace("#PINCODE_PLACE_OF_WORK#", "".$exam_info[0]['pin_code_place_of_work']."",$newstring20);

										$final_str = str_replace("#MODE#", "".$mode."",$newstring21);
									}
									else
									{
										$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'apply_exam_transaction_success'));
										$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
										$newstring2 = str_replace("#REG_NUM#", "".$row['member_regnumber']."",$newstring1);
										$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
										$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
										$newstring5 = str_replace("#AMOUNT#", "".$row['amount']."",$newstring4);
										$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
										$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
										$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
										$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
										$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
										$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
										$newstring12 = str_replace("#STATE#", "".$states[0]['state_name']."",$newstring11);
										$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
										$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
										$newstring15 = str_replace("#INSTITUDE#", "".$result[0]['institute_name']."",$newstring14);
										$newstring16 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring15);
										$newstring17 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring16);
										$newstring18 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring17);
										$newstring19 = str_replace("#MODE#", "".$mode."",$newstring18);
										$newstring20 = str_replace("#PLACE_OF_WORK#", "".$result[0]['office']."",$newstring19);
										$newstring21 = str_replace("#TRANSACTION_NO#", "".$row['transaction_no']."",$newstring20);
										
										$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($row['date']))."",$newstring21);
									 }
									$info_arr=array(//'to'=>$result[0]['email'],
									'to'=>'sagar.matale@esds.co.in',
															'from'=>$emailerstr[0]['from'],
															'subject'=>$emailerstr[0]['subject'],
															'message'=>$final_str
														);
								
									## Get admitcard PDF path
									$admit_card=$this->Master_model->getRecords('admit_card_details',array('mem_exam_id'=>$row['ref_id']),'admitcard_image');

									if(!empty($admit_card) && count($admit_card) > 0 )
									{
										$admitcard_pdf = $admit_card[0]['admitcard_image'];
										$attachpath_admitcard = 'uploads/admitcardpdf/'.$admitcard_pdf;
									}	
									//http://iibf.teamgrowth.net/uploads/admitcardpdf/21_220_5317858.pdf
									//http://iibf.teamgrowth.net/uploads/examinvoice/user/100002527_EXM_2021-22_001898.jpg
									## Get invoice path
									$invoice = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$row['id']),'invoice_image');

									if(!empty($invoice) && count($invoice) > 0 )
									{
										$invoice_pdf = $invoice[0]['invoice_image'];
										$attachpath_invoice = 'uploads/examinvoice/user/'.$invoice_pdf;
									}	

									if($attachpath_admitcard!='' && $attachpath_invoice != '')
									{		
										$files=array($attachpath_invoice,$attachpath_admitcard);
										
										//echo '<pre>';print_r($info_arr);echo '</pre>';
										if($this->Emailsending->mailsend_attch($info_arr,$files)){
											echo 'Success';
											
											$insert_info_arr = array(
												'to_email'=>$result[0]['email'],
												'mail_content'=>$final_str
											);
											
											$last_id = str_pad($this->master_model->insertRecord('tbl_s2s_email_logs',$insert_info_arr,true));
										}else{
											echo 'Error';
										}
									}
									
						}
						/*else if($row['pay_type'] == 1)
						{
							//If Member Registration
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_register_email'));
							if(count($emailerstr) > 0)
							{
								include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
								$key = $this->config->item('pass_key');
								$aes = new CryptAES();
								$aes->set_key(base64_decode($key));
								$aes->require_pkcs5();
								$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
								$applicationNo = $row['member_regnumber'];
								## Get email text
								$newstring = str_replace("#application_num#", "".$applicationNo."",  $emailerstr[0]['emailer_text']);
								$final_str= str_replace("#password#", "".$decpass."",  $newstring);
								$info_arr=array('to'=>$result[0]['email'],
								//'to'=>'kumartupe@gmail.com',
								'from'=>$emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'],
								'message'=>$final_str
								);
						
								## Get invoice path
								$mem_invoice = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$row['id']),'invoice_image');
								if(!empty($invoice) && count($invoice) > 0 )
								{
									$invoice = $invoice[0]['invoice_image'];
									$attachpath = 'uploads/reginvoice/user/'.$invoice;
								}	
								if($attachpath!='')
								{	
									$sms_newstring = str_replace("#application_num#", "".$applicationNo."",  $emailerstr[0]['sms_text']);
									$sms_final_str= str_replace("#password#", "".$decpass."",  $sms_newstring);
									//$this->Master_model->send_sms($user_info[0]['mobile'],$sms_final_str);
									echo '<pre>';print_r($info_arr);echo '</pre>';
									//$this->Emailsending->mailsend_attch($info_arr,$attachpath)
								}	
							}
						}//Else*/
							
				}//If member details
				else{
					echo "Invalid Member registration number!<br/>";
				}
			}//foreach	
		}//If
	}
}
?>