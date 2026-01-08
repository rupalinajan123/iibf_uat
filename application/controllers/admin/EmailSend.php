<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class EmailSend extends CI_Controller {
	public $UserID;
			
	public function __construct(){
		parent::__construct();
		if($this->session->userdata('roleid')!=1){
			redirect('admin/Login');
		}	
		
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->UserID=$this->session->id;
		
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->helper('upload_helper');
		$this->load->helper('general_helper');
		$this->load->library('email');
		$this->load->model('Emailsending');
	}
	
	// By VSU : Function to send mail for "Request to update profile images only"
	public function index()
	{
		//email to user
		$cand_arr = array(510299450,510299641,510299703,510299708,510299898,510300045,510300297,510300394,510300776,510300856,510300867,510300903,801148423,801148865,801150972,801150973,510301315);
		
		//$cand_arr = array("801148423","510300903","510300867","510300856","510300776","510300457","510300424","510300394","510300368","510300297","510300045","510300132","510299938","510299930","510300023","510299898","510299708","510299703","510299676","510299641","510299606","510299541","510299457","510299450","510299412");
		
		//$cand_arr = array("510299450","510299457","510299461","510299641","510299676","510299703","510299708","510299898","510299930","510300132","510300045","510300297","510300394","510300424","510300457","510300640","510300776","510300856","510300867","510300903","801148423","510299412","510299541","510299606","510300023","510299938","801148045","510300368");
	//510299450,510299457,510299461,510299641,510299676,510299703,510299708,510299898,510299930,510300132,510300045,510300297,510300394,510300424,510300457,510300640,510300776,510300856,510300867,510300903,801148423,510299412,510299541,510299606,510300023,510299938,801148045,510300368
		
		if(count($cand_arr) > 0)
		{
			$cnt=1;
			$emailerstr=$this->master_model->getRecords('refund_email',array('emailer_name'=>'profile_images'));
			
			foreach($cand_arr as $reg_num)
			{
				//echo $users['email'].'<br>';
				$email_res = $this->master_model->getRecords('member_reg_img_blank',array("regnumber"=>"'".$reg_num."'"),'email');
				//echo $emailerstr[0]['emailer_text'];
				if(count($email_res)>0)
				{
					$info_arr = array('to'=>$email_res[0]['email'],
									  'from'=>$emailerstr[0]['from'],
									  'subject'=>$emailerstr[0]['subject'],
									  'message'=>$emailerstr[0]['emailer_text']
									);
					/*echo $cnt.'='.$email_res[0]['email'].' '.'Application Number='.$reg_num.'<br>';
					$cnt++;*/
					
					if($this->Emailsending->mailsend($info_arr))
					{
					
						echo $cnt.'='.$email_res[0]['email'].' '.'Application Number='.$reg_num.'<br>';
						$cnt++;
						//exit;
					}
					
				}
			}
			echo "mail sent";
		}
	}
	
	// By VSU : Function to send mail for "Request to update profile"
	public function profile()
	{
		//email to user
		//$cand_arr = array(510301029,510301030,510301031,510301032,510301034,510301035,510301037,510301039,510301040,510301041,510301042,510301043,510301044,510301046,510301045,510301047,510301048,510301049,510301050,510301051,510301052,510301053,510301054,510301055,510301057,510301059,510301060,510301061,510301062,510301063,510301064,510301065,510301066,510301067,510301068,510301069,510301070,510301071,510301072,510301073,510301074,510301075,510301076,510301078,510301077,510301080,510301079,510301081,510301083,510301082,510301084,510301085,510301086,510301087,510301088,510301089,510301090,510301092,510301093,510301095,510301097,510301098,510301099,510301100,510301101,510301103,510301105,510301106,510301107,510301108,510301109,510301112,510301110,510301111,510301113,510301115,510301114,510301117,510301116,510301118,510301120,510301119,510301122,510301121,510301124,510301125,510301126,510301127,510301128,510301129,510301131,510301130,510301132,510301133,510301134,510301135,510301136,510301137,510301138,510301139,510301141,510301143,510301144,510301145,510301146,510301147,510301153,510301148,510301149,510301150,510301151,510301152,510301154,510301156,510301160,510301162,510301163,510301164,510301165,510301166,510301167,510301168,510301169,510301170,510301171,510301172,510301173,510301175,510301174,510301176,510301177,510301179,510301178,510301180,510301181,510301182,510301183,510301184,510301185,510301186,510301187,510301189,510301191,510301192,510301193,510301194,510301195,510301196,510301198,510301200);
		
		//$cand_arr = array(510301153,510301148,510301149,510301150,510301151,510301152,510301154,510301156,510301160,510301162,510301163,510301164,510301165,510301166,510301167,510301168,510301169,510301170,510301171,510301172,510301173,510301175,510301174,510301176,510301177,510301179,510301178,510301180,510301181,510301182,510301183,510301184,510301185,510301186,510301187,510301189,510301191,510301192,510301193,510301194,510301195,510301196,510301198,510301200);
		
		$cand_arr = array(510299450,510299641,510299703,510299708,510299898,510300045,510300297,510300394,510300776,510300856,510300867,510300903,510301032,510301035,510301041,510301042,510301044,510301046,510301047,510301049,510301054,510301055,510301063,510301068,510301077,510301083,510301089,510301099,510301103,510301105,510301106,510301126,510301131,510301150,510301151,510301153,510301156,510301167,510301168,510301176,510301177,510301179,510301194,510301204,510301208,510301210,510301213,510301226,510301227,510301242,510301246,510301254,510301255,510301274,510301275,510301317,510301321,510301325,801148423);
		
		if(count($cand_arr) > 0)
		{
			$cnt=1;
			$emailerstr=$this->master_model->getRecords('refund_email',array('emailer_name'=>'profile'));
			
			foreach($cand_arr as $reg_num)
			{
				//echo $users['email'].'<br>';
				$email_res = $this->master_model->getRecords('member_registration_first_name_null_with_mobile_no',array("regnumber"=>"'".$reg_num."'"),'email');
				if(count($email_res)>0)
				{
					$info_arr = array('to'=>$email_res[0]['email'],
									  'from'=>$emailerstr[0]['from'],
									  'subject'=>$emailerstr[0]['subject'],
									  'message'=>$emailerstr[0]['emailer_text']
									);
									
					/*echo $cnt.'='.$email_res[0]['email'].' '.'Application Number='.$reg_num.'<br>';
					$cnt++;*/
					
					if($this->Emailsending->mailsend($info_arr))
					{
					
						echo $cnt.' = '.$email_res[0]['email'].'   '.'Application Number = '.$reg_num.'<br>';
						$cnt++;
						//exit;
					}
					
				}
				//exit;
				
				
			}
			echo "email sent Profile";
		}
	}
	
	//By VSU : Function to send exam application mail on succcessful payment (Chargeback - Exam)
	public function exam_mail_send($regnumber,$exam_id,$receipt_no)
	{
		echo "222";
		if($regnumber!='' && $exam_id!='' && $receipt_no!='')
		{
			//Query to get user details
			$this->db->join('state_master','state_master.state_code=member_registration.state');
			$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
			$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$regnumber),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');
		
			//Query to get exam details	
			/*$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');*/
			$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
			$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'member_exam.id'=>$exam_id),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code');
		
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
				$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
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
			$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$receipt_no,'member_regnumber'=>$regnumber),'transaction_no,date,amount');
	
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
				$newstring2 = str_replace("#REG_NUM#", "".$regnumber."",$newstring1);
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
				
				#-----------------------------------------E-learning msg ---------------------------------------------------------#	
				
					$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
						if(count($elern_msg_string) > 0)
						{
							foreach($elern_msg_string as $row)
							{
								$arr_elern_msg_string[]=$row['exam_code'];
							}
							if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
							{
								$newstring22 = str_replace("#E-MSG#", $this->config->item('e-learn-msg'),$newstring21);		
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
						#-----------------------------------------E-learning msg end ----------------------------------------------------------#	
			}
			else
			{
				
				$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'apply_exam_transaction_success'));
				$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
				$newstring2 = str_replace("#REG_NUM#", "".$regnumber."",$newstring1);
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
				
			
				#-----------------------------------------E-learning msg ---------------------------------------------------------#	
				
					$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
						if(count($elern_msg_string) > 0)
						{
							foreach($elern_msg_string as $row)
							{
								$arr_elern_msg_string[]=$row['exam_code'];
							}
							if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
							{
								$newstring22 = str_replace("#E-MSG#", $this->config->item('e-learn-msg'),$newstring21);		
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
		
						#-----------------------------------------E-learning msg end ----------------------------------------------------------#	
		
			}
			/*$info_arr=array('to'=>$result[0]['email'],
							'from'=>$emailerstr[0]['from'],
							'subject'=>$emailerstr[0]['subject'],
							'message'=>$final_str
						);*/
						
			$info_arr=array('to'=>'raajpardeshi@gmail.com',
				'from'=>$emailerstr[0]['from'],
				'subject'=>$emailerstr[0]['subject'],
				'message'=>$final_str
			);
			if($this->Emailsending->mailsend($info_arr))
			{
				echo "mail sent";	
			}
			else
			{
				echo 'not sent';	
			}
		}
	}
	
	// By : VSU - Function to send custom registration mail (Chargeback - registration)
	public function custom_reg_mail($regid,$regnum)
	{
		//echo "New";
		//email to user
		$last = $this->uri->total_segments();
		/*$regid = $this->uri->segment($last-2);
		$regnum = $this->uri->segment($last-1);*/
		
		if(is_numeric($regnum) && is_numeric($regid))
		{
			$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_register_email'));
			if(count($emailerstr) > 0)
			{
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				
				//$decpass = $aes->decrypt($user_info[0]['usrpassword']);
				
				//Query to get user details
				/*$this->db->join('state_master','state_master.state_code=member_registration.state','LEFT');
				$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute','LEFT');
				$this->db->join('qualification','qualification.qid=member_registration.specify_qualification','LEFT');
				$this->db->join('idtype_master','idtype_master.id=member_registration.idproof','LEFT');
				$this->db->join('designation_master','designation_master.dcode=member_registration.designation','LEFT');
				$this->db->join('payment_transaction','payment_transaction.ref_id=member_registration.regid','LEFT');			*/	
				
				$result = $this->master_model->getRecords('member_registration',array('regnumber'=>$regnum,'regid'=>$regid),'regid,regnumber,firstname,middlename,lastname,email,usrpassword,dateofbirth,dateofjoin,gender');
				echo $this->db->last_query()."<br>";
				if(count($result)>0)
				{
					//echo "IN";
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
					
					$newstring1 = str_replace("#application_num#", "".$regnum."",$emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#password#", "".$decpass."",$newstring1);
					
					//$final_str = str_replace("#PINCODE#", "".strtoupper($result[0]['pincode'])."",$newstring24);
					//$result[0]['email'] //shruti.samdani@esds.co.in
					$info_arr=array('to'=>'bhushan.amrutkar@esds.co.in',
									'from'=>$emailerstr[0]['from'],
									'subject'=>$emailerstr[0]['subject'],
									'message'=>$newstring2
								);				
					//echo "<pre>";
					//print_r($info_arr);			
					
					$mail_flg = $this->Emailsending->mailsend($info_arr);
					//var_dump($mail_flg);
					if($mail_flg)
					{
						echo 'Email sent successfully !!';
						/*$this->session->set_flashdata('success','Email sent successfully !!');
						if($flag!=0 && $flag!=1)
						{
							redirect(base_url().'admin/Search/search_success');
						}
						elseif($flag==0)
						{
							redirect(base_url().'admin/Report/datewise');
						}elseif($flag==1){
							redirect(base_url().'admin/Report/query');
						}*/
					}
					else
					{
						echo 'Error while sending email';
						/*$this->session->set_flashdata('error','Error while sending email !!');
						if($flag!=0 && $flag!=1 && $flag!=2)
						{
							redirect(base_url().'admin/Search/search_success');
						}
						elseif($flag==0)
						{
							redirect(base_url().'admin/Report/datewise');
						}elseif($flag==1){
							redirect(base_url().'admin/Report/query');
						}*/
					}
				}
				else
				{
					echo 'Something went wrong...';
					/*$this->session->set_flashdata('error','Something went wrong...');
					if($flag!=0 && $flag!=1)
					{
						redirect(base_url().'admin/Search/search_success');
					}
					elseif($flag==0)
					{
						redirect(base_url().'admin/Report/datewise');
					}elseif($flag==1){
						redirect(base_url().'admin/Report/query');
					}*/
				}
			}
			else
			{
				echo 'Something went wrong...';
				/*$this->session->set_flashdata('error','Something went wrong...');
				if($flag!=0 && $flag!=1)
				{
					redirect(base_url().'admin/Search/search_success');
				}
				elseif($flag==0)
				{
					redirect(base_url().'admin/Report/datewise');
				}elseif($flag==1){
					redirect(base_url().'admin/Report/query');
				}*/
			}
		}
	}
	
	
public function gst_recovery_email()
	{
		echo "HELLO";
		exit;
		$attchpath_pdf = 'https://iibf.esdsconnect.com/uploads/bulkexaminvoice/user/bulk_1343_EX_17-18_144407.jpg';
		
		//email to user
		$cand_arr = array('510331950');
		
		if(count($cand_arr) > 0)
		{
			$cnt=1;
			$emailerstr=$this->master_model->getRecords('refund_email',array('emailer_name'=>'gst_recovery_email'));

			foreach($cand_arr as $reg_num)
			{
				//echo $users['email'].'<br>';
				$email_res = $this->master_model->getRecords('member_registration',array("regnumber"=>"'".$reg_num."'"),'email');
				//echo $emailerstr[0]['emailer_text'];
				if(count($email_res)>0)
				{
					//$email_res[0]['email'] anishrivastava@iibf.org.in
					$files_pdf=array($attchpath_pdf);
					$info_arr = array('to'=>'bhushan.amrutkar@esds.co.in',
					  'from'=>$emailerstr[0]['from'],
					  'subject'=>$emailerstr[0]['subject'],
					  'message'=>$emailerstr[0]['emailer_text']
					);
					/*echo $cnt.'='.$email_res[0]['email'].' '.'Application Number='.$reg_num.'<br>';
					$cnt++;*/
					
					if($this->Emailsending->mailsend_attch($info_arr,$files_pdf))
					{
					
						echo "<br>".$cnt.'='.$email_res[0]['email'].' '.'Application Number = '.$reg_num.'<br>';
						$cnt++;
						//exit;
					}
					
				}
			}
			echo "mail sent";
		}
	}
	
	
	
}