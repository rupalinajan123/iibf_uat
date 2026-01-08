<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ELearningTest extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');	
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->helper('general_helper');
		$this->load->model('master_model');		
		$this->load->model('Emailsending');
		$this->load->model('log_model');
		$this->load->model('chk_session');
		$this->load->helper('cookie');
		$this->load->model('log_model');
		$this->load->model('KYC_Log_model'); 
		 $this->chk_session->Check_mult_session();
		//$this->load->model('chk_session');
	 	// 	
	}

	 ##---------default userlogin (prafull)-----------##
	public function exapplylogin()
	{	
		$this->chk_session->checklogin_Elearning();
		//check exam active or not
		 $check_exam_activation=check_exam_activate(base64_decode($this->input->get('ExId')));
		
	 	if($check_exam_activation==0)
		{
			redirect(base_url().'ELearningTest/accessdenied/');
		}
	
		$data=array();
		$data['error']='';
		
		$Extype = $this->input->get('Extype');
		$Mtype = $this->input->get('Mtype');
		if(isset($_POST['btnLogin']))
		{
			$config = array(
			array(
					'field' => 'Username',
					'label' => 'Username',
					'rules' => 'trim|required'
			),
			array(
					'field' => 'code',
					'label' => 'Code',
					'rules' => 'trim|required|callback_check_captcha_examapply',
			),
		);
		
		$this->form_validation->set_rules($config);
			$dataarr=array(
				'regnumber'=> $this->input->post('Username'),
				'isactive'=>'1',
				'isdeleted'=>'0'
			);
			if ($this->form_validation->run() == TRUE)
			{
				if(base64_decode($this->input->get('Mtype'))=='O' || base64_decode($this->input->get('Mtype'))=='o')
				{
					$where="(registrationtype='O' OR registrationtype='A' OR registrationtype='F')";	
				}if(base64_decode($this->input->get('Mtype'))=='NM' || base64_decode($this->input->get('Mtype'))=='nm'){
					$where="(registrationtype='NM'  OR registrationtype='DB')";	
				}
				$this->db->where($where);
				$user_info=$this->master_model->getRecords('member_registration',$dataarr);
				
				
				if(count($user_info) > 0)
				{ 
					 if($user_info[0]['isactive']==1)
					 {
						include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
						$key = $this->config->item('pass_key');
						$aes = new CryptAES();
						$aes->set_key(base64_decode($key));
						$aes->require_pkcs5();
						$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
				
						$mysqltime=date("H:i:s");
						$user_data=array('eregid'=>$user_info[0]['regid'],
													'eregnumber'=>$user_info[0]['regnumber'],
													'efirstname'=>$user_info[0]['firstname'],
													'emiddlename'=>$user_info[0]['middlename'],
													'elastname'=>$user_info[0]['lastname'],
													'etimer'=>base64_encode($mysqltime),
													'memtype'=>$user_info[0]['registrationtype'],
													'password_applyexam'=>base64_encode($decpass));
						$this->session->set_userdata($user_data);
						$sess = $this->session->userdata();
						redirect(base_url().'ELearningTest/examdetails/?ExId='.$this->input->get('ExId').'&Extype='.$Extype);
						 }
					  else if($user_info[0]['isactive']==0)
					  {
							$data['error']='<span style="">Invalid Credentials</span>'; 
					  }
					   else
					  {
							$data['error']='<span style="">This account is suspended</span>'; 
					  }
				}
				else
				{
					$data['error']='<span style="">Invalid Credentials</span>';
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors();
			}
		}
		
		$this->load->helper('captcha');
		$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data['image'] = $cap['image'];
		$data['code']=$cap['word'];
		$this->session->set_userdata('mem_applyexam_captcha', $cap['word']);
		$this->load->view('ELearning/mem_exam_apply_login',$data);

	}
	
		##---------check captcha userlogin (vrushali)-----------##
	public function check_captcha_examapply($code) 
	{
		if(!isset($this->session->mem_applyexam_captcha) && empty($this->session->mem_applyexam_captcha))
		{
			return false;
		}
		
		if($code == '' || $this->session->mem_applyexam_captcha != $code )
		{
			$this->form_validation->set_message('check_captcha_examapply', 'Invalid %s.'); 
			$this->session->set_userdata("mem_applyexam_captcha", rand(1,100000));
			return false;
		}
		if($this->session->mem_applyexam_captcha == $code)
		{
			$this->session->set_userdata('mem_applyexam_captcha','');
			$this->session->unset_userdata("mem_applyexam_captcha");
			return true;
		}
	}
	
	
	//##---- reload captcha functionality
	public function generatecaptchaajax()
	{
		$this->load->helper('captcha');
		$this->session->unset_userdata("mem_applyexam_captcha");
		$this->session->set_userdata("mem_applyexam_captcha", rand(1, 100000));
		$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data = $cap['image'];
		$_SESSION["mem_applyexam_captcha"] = $cap['word'];
		echo $data;
	}
	
	##GST Message
	public function accessdenied()
	{
		$message='<div style="color:#F00">You are not eligible to register for the selected examination. For details refer the link <strong>"Important Notice of Diploma and Certificate Examination syllabus" </strong> at the institutes website</div>';
		$data=array('middle_content'=>'ELearning/not_eligible','check_eligibility'=>$message);
  	    $this->load->view('ELearning/mem_apply_exam_common_view',$data);
	}
	
	##GST Message
	public function GST()
	{
		$message='<div style="color:#F00">Please pay GST amount of Exam/Mem registration in order to apply for the exam.
<a href="' . base_url() . 'GstRecovery/" target="new">click here</a> </div>';
		$data=array('middle_content'=>'ELearning/not_eligible','check_eligibility'=>$message);
  	    $this->load->view('ELearning/mem_apply_exam_common_view',$data);
	}
	
	##------------------ Specific Exam Details for logged in user(PRAFULL)---------------##
	public function examdetails()
	{		
	
			####check GST paid or not.	
			$GST_val=check_GST($this->session->userdata('eregnumber'));
			if($GST_val==2)
			{
				redirect(base_url() . 'ELearningTest/GST');
			}
			//check exam activation 
			$check_exam_activation=check_exam_activate(base64_decode($this->input->get('ExId')));
			if($check_exam_activation==0)
			{
				redirect(base_url().'ELearningTest/accessdenied/');
			}
			
			$flag=$this->checkusers(base64_decode($this->input->get('ExId')));
			
			if($flag==0)
			{
				redirect(base_url().'ELearningTest/accessdenied/');
			}
			
			$profile_flag=1;
		
		 	
			if($this->session->userdata('memtype')=='NM' || $this->session->userdata('memtype')=='DB')
			{
				if(!is_file(get_img_name($this->session->userdata('eregnumber'),'s')) || !is_file(get_img_name($this->session->userdata('eregnumber'),'p')) || validate_nonmemdata($this->session->userdata('eregnumber')))
				{
					$profile_flag=0;
				}
			}
			else
			{
				if(validate_userdata($this->session->userdata('eregnumber')) || !is_file(get_img_name($this->session->userdata('eregnumber'),'s')) || !is_file(get_img_name($this->session->userdata('eregnumber'),'p')) )
				{
					$profile_flag=0;
				}
			}
			$message='';$cookieflag=$exam_status=1;
			$applied_exam_info=array();
			$flag=1;$checkqualifyflag=0;
			$examcode=base64_decode($this->input->get('ExId'));
			$valcookie=$this->session->userdata('eregnumber');
			$check_qualify_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
		
			if($check_qualify_exam[0]['exam_category']==1)
			{
				redirect(base_url().'ELearningTest/examdetails/?ExId='.$this->input->get('ExId').'&'.'Extype='.$this->input->get('Extype'));
			}
			
			if($valcookie)
			{
				$regnumber= $valcookie;
				$checkpayment=$this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$regnumber,'status'=>'2','pay_type'=>'18'),'',array('id'=>'DESC'),'0','1');
				
				if(count($checkpayment) > 0)
				{
					$endTime = date("Y-m-d H:i:s",strtotime("+5 minutes",strtotime($checkpayment[0]['date'])));
					 $current_time= date("Y-m-d H:i:s");
					if(strtotime($current_time)<=strtotime($endTime))
					{
						$cookieflag=0;
					}
					else
					{
						delete_cookie('examid');
					}
				}
				else
				{
					delete_cookie('examid');
				}
			}
			else
			{
				delete_cookie('examid');
			}
			
			
			//$check=$this->examapplied($this->session->userdata('regnumber'),$this->input->get('excode2'));
			//if(!$check)
			//{
				//Query to check selected exam details
				if(count($check_qualify_exam) > 0)
				{
					//Condition to check the qualifying id exist
					//if($check_qualify_exam[0]['qualifying_exam1']!='' && $check_qualify_exam[0]['qualifying_exam1']!='0' && $checkqualifyflag==0)
					if($check_qualify_exam[0]['qualifying_exam1']!='' && $check_qualify_exam[0]['qualifying_exam1']!='0')
					{
						$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam1'],$examcode,$check_qualify_exam[0]['qualifying_part1']);
						$flag=$qaulifyarry['flag'];
						$message=$qaulifyarry['message'];
						
						if($flag==0)
						{
							$checkqualifyflag=1;
						}
					}
					//if($check_qualify_exam[0]['qualifying_exam2']!='' && $check_qualify_exam[0]['qualifying_exam2']!='0' && $checkqualifyflag==0)
					if($check_qualify_exam[0]['qualifying_exam2']!='' && $check_qualify_exam[0]['qualifying_exam2']!='0')
					{	
						$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam2'],$examcode,$check_qualify_exam[0]['qualifying_part2']);
						$flag=$qaulifyarry['flag'];
						$message=$qaulifyarry['message'];
						if($flag==0)
						{
							$checkqualifyflag=1;
						}
					}
					//if($check_qualify_exam[0]['qualifying_exam3']!='' && $check_qualify_exam[0]['qualifying_exam3']!='0' && $checkqualifyflag==0)
					if($check_qualify_exam[0]['qualifying_exam3']!='' && $check_qualify_exam[0]['qualifying_exam3']!='0')
					{	
						$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam3'],$examcode,$check_qualify_exam[0]['qualifying_part3']);
						$flag=$qaulifyarry['flag'];
						$message=$qaulifyarry['message'];
						if($flag==0)
						{
							$checkqualifyflag=1;
						}
						}
					else if($flag==1 && $checkqualifyflag==0)
					{
							//check eligibility for applied exam(These are the exam who don't have pre-qualifying exam)
							$this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
							$check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('eregnumber')));
							if(count($check_eligibility_for_applied_exam) > 0)
							{
								foreach($check_eligibility_for_applied_exam as $check_exam_status)
								{
									if($check_exam_status['exam_status']=='F')
									{
										$exam_status=0;
									}
								}
								
								//if($exam_status==1 ||$check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v' || $check_eligibility_for_applied_exam[0]['exam_status']=='B' || $check_eligibility_for_applied_exam[0]['exam_status']=='b')
								if($exam_status==1)
								{
									$flag=0;
									$message=$check_eligibility_for_applied_exam[0]['remark'];
								}
								/*else if(($check_eligibility_for_applied_exam[0]['exam_status']=='F' || $check_eligibility_for_applied_exam[0]['exam_status']=='f') && $check_eligibility_for_applied_exam[0]['fees']=='' && ($check_eligibility_for_applied_exam[0]['app_category']=='B1' || $check_eligibility_for_applied_exam[0]['app_category']=='B2'))
								{
									$flag=0;
									$message=$check_eligibility_for_applied_exam[0]['remark'];
								}*/
								//else if($check_eligibility_for_applied_exam[0]['exam_status']=='F' || $check_eligibility_for_applied_exam[0]['exam_status']=='R')
								else if($exam_status==0)
								{
									$check=$this->examapplied($this->session->userdata('eregnumber'),$this->input->get('ExId'));
									
									if(!$check)
									{
										$check_date=$this->examdate($this->session->userdata('eregnumber'),$this->input->get('ExId'));
										if(!$check_date)
										{
										//CAIIB apply directly
										$flag=1;
										}
										else
										{
											$message=$this->get_alredy_applied_examname($this->session->userdata('eregnumber'),$this->input->get('ExId'));
											//$message='Exam fall in same date';
											$flag=0;
										}
									}
									else
									{
										$this->db->join('exam_activation_master','exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
										$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>base64_decode($this->input->get('ExId')),'misc_master.misc_delete'=>'0'),'exam_month');
										
										//$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
										$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
										$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
										$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same111.';
										$flag=0;
									}
								}
							}
							else
							{
								$check=$this->examapplied($this->session->userdata('eregnumber'),$this->input->get('ExId'));
								if(!$check)
								{
									$check_date=$this->examdate($this->session->userdata('eregnumber'),$this->input->get('ExId'));
									
									if(!$check_date)
									{
									//CAIIB apply directly
									$flag=1;
									}
									else
									{
										$message=$this->get_alredy_applied_examname($this->session->userdata('eregnumber'),$this->input->get('ExId'));
										//$message='Exam fall in same date';
										$flag=0;
									}
								}
								else
								{
									$this->db->join('exam_activation_master','exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
									$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>base64_decode($this->input->get('ExId')),'misc_master.misc_delete'=>'0'),'exam_month');
										//$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
										$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
										$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
										$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same222.';
									//$message='You have already applied for the examination';
									$flag=0;
								}
							}
						}
					}
				else
				{
					$flag=1;
				}
				//Query to check where exam applied successfully or not with transaction
				$is_transaction_doone=$this->master_model->getRecordCount('payment_transaction',array('exam_code'=>$examcode,'member_regnumber'=>$this->session->userdata('eregnumber'),'status'=>'1'));
				
			 if($is_transaction_doone >0)
			 {
				$today_date=date('Y-m-d');
				$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,member_exam.created_on');
				$this->db->where('exam_master.elg_mem_o','Y');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
				$this->db->where('member_exam.pay_status','1');
				$this->db->order_by('member_exam.created_on','DESC');
				$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>$examcode,'regnumber'=>$this->session->userdata('eregnumber')));
			}
			
				########get Eligible createon date######
				$this->db->limit('1');
				$get_eligible_date=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode),'eligible_master.created_on');
				$eligiblecnt=0;
				if(count($applied_exam_info) > 0)
				{
					if(strtotime($applied_exam_info[0]['created_on'] ) > strtotime($get_eligible_date[0]['created_on']))
					{
						$eligiblecnt=$eligiblecnt+1;
					}
				}
			
				if($cookieflag==0 && $profile_flag==1)
				{
				
					$data=array('middle_content'=>'ELearning/exam_apply_cms_msg');
					$this->load->view('ELearning/mem_apply_exam_common_view',$data);
				}
				else if($flag==0 && $cookieflag==1)
				{
					if($profile_flag==0)
					{
						$message='<div style="color:#F00" class="col-md-4">Please update your profile!!<a href='.base_url().'> Click here </a>to login</div>';
					}
					
					 $data=array('middle_content'=>'ELearning/not_eligible','check_eligibility'=>$message);
					 $this->load->view('ELearning/mem_apply_exam_common_view',$data);
				}
				else if($eligiblecnt)
				{
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
					$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$examcode,'misc_master.misc_delete'=>'0'),'exam_month');
					//$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
					$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
					$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
					$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same333.';
					if($profile_flag==0)
					{
						$message='<div style="color:#F00" class="col-md-4">Please update your profile!!<a href='.base_url().'> Click here </a>to login</div>';
					}
					 $data=array('middle_content'=>'ELearning/already_apply','check_eligibility'=>$message);
					 $this->load->view('ELearning/mem_apply_exam_common_view',$data);
				}
				else if($cookieflag==1 && $profile_flag==1)
				{
					$exam_info=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
				 	if(count($exam_info)<=0)
					{
						redirect(base_url());
					}
					$this->session->set_userdata('examcode',$examcode);
					redirect(base_url().'ELearningTest/comApplication');
					//$data=array('middle_content'=>'ELearningTest/cms_page','exam_info'=>$exam_info);
					//$this->load->view('ELearningTest/mem_apply_exam_common_view',$data);
				}
				else if($profile_flag==0)
				{
					 $message='<div style="color:#F00" class="col-md-4">Please update your profile!!<a href='.base_url().'> Click here </a>to login</div>';
					 $data=array('middle_content'=>'ELearning/not_eligible','check_eligibility'=>$message);
					 $this->load->view('ELearning/mem_apply_exam_common_view',$data);
				}
			
			//}
			/*else
			{
				$data=array('middle_content'=>'already_apply','check_eligibility'=>'You have already applied for the examination.');
				 $this->load->view('common_view',$data);	
			}*/
				
		}
		
		
		
	##-------------- check qualify exam pass/fail
	public function checkqualify($qualify_id=NULL,$examcode=NULL,$part_no=NULL)
	{
		$message='';
		$flag=0;
		$exam_status=1;
		$check_qualify=array();
		$check_qualify_exam_name=$this->master_model->getRecords('exam_master',array('exam_code'=>$qualify_id),'description');
		if(count($check_qualify_exam_name) > 0)
		{
			if($examcode==19)
			{$message = 'You are not eligible to apply for this exam, you should either be <strong>CAIIB</strong> passed or should have <strong>CS qualification</strong>.';}
			else
			{$message='you have not cleared qualifying examination - <strong>'.$check_qualify_exam_name[0]['description'].'</strong>.';}
		}
		else
		{
			$message='you have not cleared qualifying examination.';
		}
		$check_qualify_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
		//Query to check the qualifying exam details of selected exam(Below code for those exam who have pre-qualifying exam)
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
		$check_qualify_exam_eligibility=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$qualify_id,'part_no'=>$part_no,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('eregnumber')),'exam_status,remark');

		if(count($check_qualify_exam_eligibility) > 0)
		{
			foreach($check_qualify_exam_eligibility as $check_exam_status)
			{
				if($check_exam_status['exam_status']=='F' || $check_exam_status['exam_status']=='V')
				{
					$exam_status=0;
				}
			}
			//if($check_qualify_exam_eligibility[0]['exam_status']=='P')
			if($exam_status==1)
			{
					//check eligibility for applied exam(This are the exam who  have pre qualifying exam)
					if(base64_decode($this->input->get('Extype'))=='3')
					{
					 $this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
					$check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.subject'=>'1'. $examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('eregnumber')));
					}
					else
					{
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
					$check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('eregnumber')));
					}
					if(count($check_eligibility_for_applied_exam) > 0)
					{
							foreach($check_eligibility_for_applied_exam as $check_exam_status)
							{
								if($check_exam_status['exam_status']=='F' || $check_exam_status['exam_status']=='V' || $check_exam_status['exam_status']=='D')
								{
									$exam_status=0;
								}
							}
						/*if($check_eligibility_for_applied_exam[0]['exam_status']=='P' || $check_eligibility_for_applied_exam[0]['exam_status']=='p' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v' || $check_eligibility_for_applied_exam[0]['exam_status']=='B' || $check_eligibility_for_applied_exam[0]['exam_status']=='b')*/
						if($exam_status==1)
						{
							$flag=0;
							if(base64_decode($this->input->get('Extype'))=='3')
							{
								
							$message = 'You have already cleared this subject under <strong>' . $check_qualify_exam_name[0]['description'] . '</strong> Elective Examination. Hence you cannot apply for the same';
								//$message='You have already cleared this subject under <strong>'.$check_qualify_exam_name[0]['description'].'</strong> Elective Examination. Hence you cannot apply for the same';
							}
							else
							{
								$message=$check_eligibility_for_applied_exam[0]['remark'];
							}
							$check_qualify=array('flag'=>$flag,'message'=>$message);
							return $check_qualify;
						}
						//else if($check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' )
						else if($exam_status==1)
						{
							$flag=0;
							$message=$check_eligibility_for_applied_exam[0]['remark'];
							$check_qualify=array('flag'=>$flag,'message'=>$message);
							return $check_qualify;
						}
						/*else if(($check_eligibility_for_applied_exam[0]['exam_status']=='F' || $check_eligibility_for_applied_exam[0]['exam_status']=='f') && $check_eligibility_for_applied_exam[0]['fees']=='' && ($check_eligibility_for_applied_exam[0]['app_category']=='B1' || $check_eligibility_for_applied_exam[0]['app_category']=='B2'))
						{
							$flag=0;
							$message=$check_eligibility_for_applied_exam[0]['remark'];
							$check_qualify=array('flag'=>$flag,'message'=>$message);
							return $check_qualify;
						}*/
								
						//else if($check_eligibility_for_applied_exam[0]['exam_status']=='F'  || $check_eligibility_for_applied_exam[0]['exam_status']=='R')
						else if($exam_status==0)
						{
							$flag=1;
							$check_qualify=array('flag'=>$flag,'message'=>$message);
							return $check_qualify;
						}
					}
					else
					{
						//CAIIB apply directly
						$flag=1;
						$check_qualify=array('flag'=>$flag,'message'=>$message);
						return $check_qualify;
					}
			}
			else
			{
				$flag=0;
				$qualification=0;
				$user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('eregnumber')),'specify_qualification');
				if(count($user_info) > 0)
				{
					$qualification=$user_info[0]['specify_qualification'];
				}
				if($qualification==91 && $examcode==19)
				{
					$flag=1;
				}
				if($check_qualify_exam_eligibility[0]['remark']!='')
				{
					$message=$check_qualify_exam_eligibility[0]['remark'];
				}
				$check_qualify=array('flag'=>$flag,'message'=>$message);
				return $check_qualify;
			}
		}
		else
		{
			//show message with pre-qualifying exam name if pre-qualify exam yet to not apply.
			$flag=0;
			if($qualify_id)
			{
				$get_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$qualify_id),'description');	
				if(count($get_exam) > 0)
				{
					$qualification=0;
					$user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('eregnumber')),'specify_qualification');
					if(count($user_info) > 0)
					{
						$qualification=$user_info[0]['specify_qualification'];
					}
					if(base64_decode($this->input->get('Extype'))=='3')
					{
						if($qualification==91 && $examcode==19)
						{
							$flag=1;
						}
						else
						{
							if($examcode==19)
							{$message='You are not eligible to apply for this exam, you should either be CAIIB passed or should have CS qualification.';}
							else
							{$message='you have not cleared qualifying examination123 - <strong>'.$get_exam[0]['description'].'</strong>.';}
						}
					}
					else
					{
						if($qualification==91 && $examcode==19)
						{
							$flag=1;
						}
						else
						{
							if($examcode==19)
							{$message='You are not eligible to apply for this exam, you should either be CAIIB passed or should have CS qualification.';}
							else
							{
							$message='You have not cleared  <strong>'.$get_exam[0]['description'].'</strong> examination, hence you cannot apply for <strong> '.$check_qualify_exam[0]['description'].'</strong>.';
							}
						}
					}
				}
			}
			$check_qualify=array('flag'=>$flag,'message'=>$message);
			return $check_qualify;
		}
	}
	
	
	public function examapplied($regnumber=NULL,$exam_code=NULL)
	{
		//check where exam alredy apply or not
		$cnt=0;
		$today_date=date('Y-m-d');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		//$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
		$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
		$this->db->where('exam_master.elg_mem_o','Y');
		$this->db->where('pay_status','1');
		$this->db->order_by('member_exam.created_on','DESC');
		$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($exam_code),'regnumber'=>$regnumber),'member_exam.created_on');
		//echo $this->db->last_query();exit;
		####check if number applied through the bulk registration (Prafull)###
		if(count($applied_exam_info)<=0)
		{
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			//$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
			$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
			$this->db->where('exam_master.elg_mem_o','Y');
			$this->db->where('bulk_isdelete','0');
			$this->db->where('institute_id!=','');
			$this->db->order_by('member_exam.created_on','DESC');
			$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($exam_code),'regnumber'=>$regnumber),'member_exam.created_on');
		}	
		###### End of check  number applied through the bulk registration###
		
		######get eligible created on data##########
		$this->db->limit('1');
		$get_eligible_date=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>base64_decode($exam_code),'member_no'=>$regnumber),'eligible_master.created_on');
		if(count($applied_exam_info) > 0)
		{
			if(strtotime($applied_exam_info[0]['created_on'] ) > strtotime($get_eligible_date[0]['created_on']))
			{
				$cnt=$cnt+1;
			}
		}
		return $cnt;
	}
	
	//check whether applied exam date fall in same date of other exam date(Prafull)
	public function examdate($regnumber=NULL,$exam_code=NULL)
	{
		$flag=0;
		$today_date=date('Y-m-d');
		$applied_exam_date=$this->master_model->getRecords('subject_master',array('exam_code'=>base64_decode($exam_code),'exam_date >='=>$today_date,'subject_delete'=>'0'));
		if(count($applied_exam_date) > 0)
		{
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
			$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'pay_status'=>'1'),'member_exam.exam_code');
			
			### checking bulk applied ######
			if(count($getapplied_exam_code) <=0)
			{
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$this->db->where('bulk_isdelete','0');
				$this->db->where('institute_id!=','');
				$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'pay_status'=>'2'),'member_exam.exam_code');
			}
			
			if(count($getapplied_exam_code) >0)
			{
				foreach($getapplied_exam_code as $exist_ex_code)
				{	
					$getapplied_exam_date=$this->master_model->getRecords('subject_master',array('exam_code'=>$exist_ex_code['exam_code'],'exam_date >='=>$today_date,'subject_delete'=>'0'));
					if(count($getapplied_exam_date) > 0)
					{
						foreach($getapplied_exam_date as $exist_ex_date)
						{
							foreach($applied_exam_date as $sel_ex_date)
							{
									if($sel_ex_date['exam_date']==$exist_ex_date['exam_date'])
									{
										$flag=1;
										break;
									}
								}
								if($flag==1)
								{
									break;
								}
							}
						}
					}
				}
		}
		return $flag;
	}
	
	//get applied exam name which is fall on same date(Prafull)
	public function get_alredy_applied_examname($regnumber=NULL,$exam_code=NULL)
	{
		$flag=0;
		$msg='';
		$today_date=date('Y-m-d');
		
		$this->db->select('subject_master.*,exam_master.description');
		$this->db->join('exam_master','exam_master.exam_code=subject_master.exam_code');
		$applied_exam_date=$this->master_model->getRecords('subject_master',array('subject_master.exam_code'=>base64_decode($exam_code),'exam_date >='=>$today_date,'subject_delete'=>'0'));
		if(count($applied_exam_date) > 0)
		{
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'pay_status'=>'1'),'member_exam.exam_code,exam_master.description');
			### checking bulk applied ######
			if(count($getapplied_exam_code) <=0)
			{
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$this->db->where('bulk_isdelete','0');
				$this->db->where('institute_id!=','');
				$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'pay_status'=>'2'),'member_exam.exam_code,exam_master.description');
			}
			if(count($getapplied_exam_code) >0)
			{
				foreach($getapplied_exam_code as $exist_ex_code)
				{	
					$getapplied_exam_date=array();
					$getapplied_exam_date=$this->master_model->getRecords('subject_master',array('exam_code'=>$exist_ex_code['exam_code'],'exam_date >='=>$today_date,'subject_delete'=>'0'));
					
					if(count($getapplied_exam_date) > 0)
					{
						foreach($getapplied_exam_date as $exist_ex_date)
						{
							foreach($applied_exam_date as $sel_ex_date)
							{
									if($sel_ex_date['exam_date']==$exist_ex_date['exam_date'])
									{
										$msg="You have already applied for <strong>".$exist_ex_code['description']."</strong> falling on same day, So you can not apply for <strong>".$sel_ex_date['description']."</strong> examination.";
										$flag=1;
										break;
									}
								}
								if($flag==1)
								{
										$msg="You have already applied for <strong>".$exist_ex_code['description']."</strong> falling on same day, So you can not apply for <strong>".$sel_ex_date['description']."</strong> examination.";
									break;
								}
							}
						}
						if($flag==1)
						{
							break;
						}
					}
				}
			}
		return $msg;
	}
	
	
	##------------------ CMS Page for logged in user(PRAFULL)---------------##
	public function comApplication()
	{
		$this->chk_session->ELearn_Mem_checklogin_external_user();
		//accedd denied due to GST
		//$this->master_model->warning();
		
		if(isset($_POST['btnPreviewSubmit']))  	
		{
		// echo '<pre>',print_r($_POST),'</pre>';exit;
			$scribe_flag='N';
			$caiib_subjects=array();
			$compulsory_subjects=array();
			$scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $state= $password = $var_errors = '';
			$venue=$this->input->post('venue');
			$date=$this->input->post('date');
			$time=$this->input->post('time');
			if($this->session->userdata('examinfo'))
			{
				$this->session->unset_userdata('examinfo');
			}
			$this->form_validation->set_rules('medium','Medium','required|xss_clean');
			$this->form_validation->set_rules('selCenterName','Centre Name','required|xss_clean');
			$this->form_validation->set_rules('txtCenterCode','Centre Code','required|xss_clean');
			if($this->input->post('gstin_no'))
			{
				$this->form_validation->set_rules('gstin_no', 'Bank GSTIN Number', 'trim|alpha_numeric|min_length[15]|xss_clean');
			}
			if($this->form_validation->run()==TRUE)
			{
					$subject_arr=array();
					$venue=$this->input->post('venue');
					$date=$this->input->post('date');
					$time=$this->input->post('time');
					if(count($venue) >0 && count($date) >0 && count($time) >0)	
					{
						foreach($venue as $k=>$v)
						{
							$compulsory_subjects_name=$this->master_model->getRecords('subject_master',array('exam_code'=>base64_decode($_POST['excd']),'subject_delete'=>'0','exam_period'=>$_POST['eprid'],'subject_code'=>$k),'subject_description');
							$subject_arr[$k]=array('venue'=>$v,'date'=>$date[$k],'session_time'=>$time[$k],'subject_name'=>$compulsory_subjects_name[0]['subject_description']);
						}
						#### add elective subject in venue,time,date array#########
						if(isset($_POST['venue_caiib']) && isset($_POST['date_caiib']) && isset($_POST['time_caiib']))
						{
								$subject_arr[$this->input->post('selSubcode')]=array('venue'=>$this->input->post('venue_caiib'),'date'=>$this->input->post('date_caiib'),'session_time'=>$this->input->post('time_caiib'),'subject_name'=>$this->input->post('selSubName1'));
						}
						#########check duplication of venue,date,time##########		
						if(count($subject_arr) > 0)
						{	
							$msg='';
							$sub_flag=1;
							$sub_capacity=1;
							foreach($subject_arr as $k=>$v)
							{
									foreach($subject_arr as $j=>$val)
									{
										if($k!=$j)
										{
											//if($v['venue']==$val['venue'] && $v['date']==$val['date'] && $v['session_time']==$val['session_time'])
											if($v['date']==$val['date'] && $v['session_time']==$val['session_time'])
											{
												$sub_flag=0;
											}
										}
									}
								 $capacity=check_capacity($v['venue'],$v['date'],$v['session_time'],$_POST['selCenterName']);
								if($capacity==0)
								{
									#########get message if capacity is full##########
									$msg=getVenueDetails($v['venue'],$v['date'],$v['session_time'],$_POST['selCenterName']);
								}
								if($msg!='')
								{
									$this->session->set_flashdata('error',$msg);
									redirect(base_url().'ELearningTest/comApplication');
								}
							}
						}
						if($sub_flag==0)
						{
							if(base64_decode($_POST['excd'])!=101)
							{
							$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
							redirect(base_url().'ELearningTest/comApplication');
							}
						}
					}
							#----Scrib Flag - changes by POOJA GODSE---#
 					$scribe_flag =$scribe_flag_d='N';
 					$Sub_menue_disability=$disability_value ='';
                if (isset($_POST['scribe_flag']))
                    {
                    $scribe_flag = $_POST['scribe_flag'];
                    }
  					if (isset($_POST['scribe_flag_d']))
                    {
                    $scribe_flag_d = $_POST['scribe_flag_d'];
                    }
					if (isset($_POST['Sub_menue']))
                    {
                    $Sub_menue_disability = $_POST['Sub_menue'];
                    }
					if (isset($_POST['disability_value']))
                    {
                    $disability_value = $_POST['disability_value'];
                    }
					#----Scrib Flag end - changes by POOJA GODSE---#
					
					
					$user_data=array('email'=>$_POST["email"],	
									'mobile'=>$_POST["mobile"],	
									'photo'=>'',
									'signname'=>'',
									'medium'=>$_POST['medium'],
									'selCenterName'=>$_POST['selCenterName'],
									'extype'=>$_POST['extype'],
									'exname'=>$_POST['exname'],
									'excd'=>$_POST['excd'],
									'eprid'=>$_POST['eprid'],
									'fee'=>$_POST['fee'],
									'txtCenterCode'=>$_POST['txtCenterCode'],
									'insdet_id'=>'',
									'selected_elect_subcode'=>$_POST['selSubcode'],
									'selected_elect_subname'=>$_POST['selSubName1'],
									'placeofwork'=>$_POST['placeofwork'],
									'state_place_of_work'=>$_POST['state_place_of_work'],
									'pincode_place_of_work'=>$_POST['pincode_place_of_work'],
									'elected_exam_mode'=>$_POST['elected_exam_mode'],
									'grp_code'=>$_POST['grp_code'],
									'subject_arr'=>$subject_arr,
									'scribe_flag'=>$scribe_flag,
									'scribe_flag_d'=>$scribe_flag_d,
									'disability_value'=>$disability_value,
									'Sub_menue_disability'=>$Sub_menue_disability,
									'gstin_no'=>$_POST['gstin_no']
									);
					$this->session->set_userdata('examinfo',$user_data);
					//logactivity($log_title ="Member exam apply details", $log_message = serialize($user_data));
					
					//logactivity($log_title ="Member exam apply details", $log_message = serialize($user_data));
					/* User Log Activities : Bhushan */
					$log_title ="Member exam apply details";
					$log_message = serialize($user_data);
					$rId = $this->session->userdata('regid');
					$regNo = $this->session->userdata('eregnumber');
					storedUserActivity($log_title, $log_message, $rId, $regNo);
					/* Close User Log Actitives */
						
					redirect(base_url().'ELearningTest/preview');
				}
				else
				{
					$var_errors = str_replace("<p>", "<span>", $var_errors);
					$var_errors = str_replace("</p>", "</span><br>", $var_errors);
				}
		}
		//Considering B1 as group code in query (By Prafull)
		if($this->session->userdata('examcode')=='')
		{
			redirect(base_url());	
		}
		
		//check exam activation
		$check_exam_activation=check_exam_activate($this->session->userdata('examcode'));
		if($check_exam_activation==0)
		{
			redirect(base_url().'ELearningTest/accessdenied/');
		}
		
		$cookieflag=1;
		$this->chk_session->checkphoto();
		//ask user to wait for 5 min, until the payment transaction process complete by (PRAFULL)
		$valcookie=$this->session->userdata('eregnumber');
		if($valcookie)
		{
			$regnumber= $valcookie;
			$checkpayment=$this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$regnumber,'status'=>'2','pay_type'=>'2'),'',array('id'=>'DESC'));
			if(count($checkpayment) > 0)
			{
				$endTime = date("Y-m-d H:i:s",strtotime("+5 minutes",strtotime($checkpayment[0]['date'])));
				 $current_time= date("Y-m-d H:i:s");
				if(strtotime($current_time)<=strtotime($endTime))
				{
					$cookieflag=0;
				}
				else
				{
					delete_cookie('examid');
				}
			}
			else
			{
				delete_cookie('examid');
			}
		}
		else
		{
			delete_cookie('examid');
		}
		//End Of ask user to wait for 5 min, until the payment transaction process complete by (PRAFULL)
					
		//Considering B1 as group code in query (By Prafull)
		if($this->session->userdata('examcode')=='')
		{
			redirect(base_url().'ELearningTest/examlist/');	
		}
	
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=exam_master.exam_code');
		$this->db->join("eligible_master",'eligible_master.exam_code=exam_activation_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period','left');
		$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
		$this->db->where("misc_master.misc_delete",'0');
		$this->db->where("eligible_master.member_no",$this->session->userdata('eregnumber'));
		$this->db->where("eligible_master.app_category !=",'R');
		$this->db->where('exam_master.exam_code',$this->session->userdata('examcode'));
		$examinfo=$this->master_model->getRecords('exam_master');
		
		####### get subject mention in eligible master ##########
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
		$this->db->where('center_master.exam_name',$this->session->userdata('examcode'));
		$this->db->where("center_delete",'0');

		$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
		//Below code, if member is new member
		if(count($examinfo) <=0)
		{
			$this->db->select('exam_master.*,misc_master.*');
			$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code');
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=misc_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period');//added on 5/6/2017
			$this->db->where("misc_master.misc_delete",'0');
			$this->db->where('exam_master.exam_code',$this->session->userdata('examcode'));
 			$examinfo = $this->master_model->getRecords('exam_master');
			//get center
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
			$this->db->where("center_delete",'0');
			$this->db->where('exam_name',$this->session->userdata('examcode'));
			$this->db->group_by('center_master.center_name');
			$center=$this->master_model->getRecords('center_master');
			####### get compulsory subject list##########
		}
	
		if(count($examinfo)<=0)
		{
			redirect(base_url());
		}
		
		$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
		$graduate=$this->master_model->getRecords('qualification',array('type'=>'GR'));
		$postgraduate=$this->master_model->getRecords('qualification',array('type'=>'PG'));
		
		$this->db->where('institution_delete','0');
		$institution_master=$this->master_model->getRecords('institution_master');
		
		$this->db->where('designation_delete','0');
		$designation=$this->master_model->getRecords('designation_master');
		
		$idtype_master=$this->master_model->getRecords('idtype_master');
		//To-do use exam-code wirh medium master
		
		$this->db->where('state_delete','0');
		$states=$this->master_model->getRecords('state_master');
			
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=medium_master.exam_code AND exam_activation_master.exam_period=medium_master.exam_period');
		$this->db->where('medium_master.exam_code',$this->session->userdata('examcode'));
		$this->db->where('medium_delete','0');
		$medium=$this->master_model->getRecords('medium_master');
		//get center as per exam
		
		//user information
		$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('eregid'),'regnumber'=>$this->session->userdata('eregnumber')));
		if(count($user_info) <=0)
		{
			redirect(base_url());
		}
		$scribe_disability = $this->master_model->getRecords('scribe_disability', array('is_delete' => '0'));
		
		
		$data=array(	'scribe_disability' => $scribe_disability,'middle_content'=>'ELearning/comApplication','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'institution_master'=>$institution_master,'designation'=>$designation,'user_info'=>$user_info,'idtype_master'=>$idtype_master,'examinfo'=>$examinfo,'medium'=>$medium,'center'=>$center);
		$this->load->view('ELearning/mem_apply_exam_common_view',$data);
		
	}
	
	##------------------ Preview for applied exam,for logged in user(PRAFULL)---------------##
	public function preview()
	{
		$this->chk_session->ELearn_Mem_checklogin_external_user();
		$sub_flag=1;
		$sub_capacity=1;
		//echo $this->session->userdata['examinfo']['selCenterName'];exit;
		$compulsory_subjects=array();
		if(!$this->session->userdata('examinfo'))
		{
			redirect(base_url().'home/dashboard/');
		}
		//check exam acivation
		$check_exam_activation=check_exam_activate(base64_decode($this->session->userdata['examinfo']['excd']));
		if($check_exam_activation==0)
		{
			redirect(base_url().'ELearningTest/accessdenied/');
		}
			
		############check capacity is full or not ##########
		$subject_arr=$this->session->userdata['examinfo']['subject_arr'];
		if(count($subject_arr) > 0)
		{		
			$msg='';
			$sub_flag=1;
			$sub_capacity=1;
			foreach($subject_arr as $k=>$v)
			{
					foreach($subject_arr as $j=>$val)
					{
						if($k!=$j)
						{
							//if($v['venue']==$val['venue'] && $v['date']==$val['date'] && $v['session_time']==$val['session_time'])
							if($v['date']==$val['date'] && $v['session_time']==$val['session_time'])
							{
								$sub_flag=0;
							}
						}
					}
				 $capacity=check_capacity($v['venue'],$v['date'],$v['session_time'],$this->session->userdata['examinfo']['selCenterName']);
				if($capacity==0)
				{
					#########get message if capacity is full##########
					$msg=getVenueDetails($v['venue'],$v['date'],$v['session_time'],$this->session->userdata['examinfo']['selCenterName']);
				}
				if($msg!='')
				{
					$this->session->set_flashdata('error',$msg);
					redirect(base_url().'ELearningTest/comApplication');
				}
			}
		}
		if($sub_flag==0)
		{
			$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
			redirect(base_url().'ELearningTest/comApplication');
		}
			
		$cookieflag=1;
		//$this->chk_session->checkphoto();
		//ask user to wait for 5 min, until the payment transaction process complete by (PRAFULL)
		$valcookie=$this->session->userdata('eregnumber');
		if($valcookie)
		{
			$regnumber= $valcookie;
			$checkpayment=$this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$regnumber,'status'=>'2','pay_type'=>'2'),'',array('id'=>'DESC'));
			if(count($checkpayment) > 0)
			{
				$endTime = date("Y-m-d H:i:s",strtotime("+5 minutes",strtotime($checkpayment[0]['date'])));
				 $current_time= date("Y-m-d H:i:s");
				if(strtotime($current_time)<=strtotime($endTime))
				{
					$cookieflag=0;
				}
				else
				{
					delete_cookie('examid');
				}
			}
			else
			{
				delete_cookie('examid');
			}
		}
		else
		{
			delete_cookie('examid');
		}
		//End Of ask user to wait for 5 min, until the payment transaction process complete by (PRAFULL)
			
		if(!$this->session->userdata('examinfo'))
		{
			redirect(base_url());
		}	
		//check for valid fee
		if($this->session->userdata['examinfo']['fee']==0 || $this->session->userdata['examinfo']['fee']=='')
		{
			$this->session->set_flashdata('error','Fee can not be zero(0) or Blank!!');
			redirect(base_url().'ELearningTest/comApplication/');
		}
		
		$check=$this->examapplied($this->session->userdata('eregnumber'),$this->session->userdata['examinfo']['excd']);
		if(!$check)
		{		
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=medium_master.exam_code AND exam_activation_master.exam_period=medium_master.exam_period');
			$this->db->where('medium_master.exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
			$this->db->where('medium_delete','0');
			$medium=$this->master_model->getRecords('medium_master');
			
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
			$this->db->where('exam_name',base64_decode($this->session->userdata['examinfo']['excd']));
			$this->db->where('center_code',$this->session->userdata['examinfo']['selCenterName']);
			$center=$this->master_model->getRecords('center_master','','center_name');
			//echo $this->db->last_query();exit;
			$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('eregid'),'regnumber'=>$this->session->userdata('eregnumber')));
			if(count($user_info) <=0)
			{
				redirect(base_url());
			}	
			$this->db->where('state_delete','0');
			$states=$this->master_model->getRecords('state_master');
		
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
			$misc=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'misc_delete'=>'0'));
			
			if($cookieflag==0)
			{
				$data=array('middle_content'=>'exam_apply_cms_msg');
			}
			else
			{
					$disability_value = $this->master_model->getRecords('scribe_disability', array('is_delete' =>0));
					$scribe_sub_disability = $this->master_model->getRecords('scribe_sub_disability', array('is_delete' =>0));
					$data=array('disability_value' => $disability_value,
			        'scribe_sub_disability' => $scribe_sub_disability,'middle_content'=>'ELearning/exam_preview','user_info'=>$user_info,'medium'=>$medium,'center'=>$center,'misc'=>$misc,'states'=>$states,'compulsory_subjects'=>$this->session->userdata['examinfo']['subject_arr']);
			}
			$this->load->view('ELearning/mem_apply_exam_common_view',$data);
		}
		else
		{
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
			$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'misc_master.misc_delete'=>'0'),'exam_month');
			//$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
			$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
			$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
			$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same444.';
			 $data=array('middle_content'=>'ELearning/already_apply','check_eligibility'=>$message);
			 $this->load->view('ELearning/mem_apply_exam_common_view',$data);	
		}
	}
	
	//Show acknowlodgement to to user after transaction succeess
	public function savedetails()
	{$this->chk_session->ELearn_Mem_checklogin_external_user();
		if(($this->session->userdata('examinfo')==''))
		{
			redirect(base_url());
		}
		$exam_code= base64_decode($this->session->userdata['examinfo']['excd']);
		$today_date=date('Y-m-d');
		$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month');
		$this->db->where('elg_mem_o','Y');	
		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
		$this->db->where("misc_master.misc_delete",'0');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
		$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
		$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>$exam_code,'regnumber'=>$this->session->userdata('eregnumber')));
		
		$this->db->where('medium_delete','0');
		$this->db->where('exam_code',$exam_code);
		$this->db->where('medium_code',$applied_exam_info[0]['exam_medium']);
		$medium=$this->master_model->getRecords('medium_master','','medium_description');
		
		$this->db->where('exam_name',$exam_code);
		$this->db->where('center_code',$applied_exam_info[0]['exam_center_code']);
		$this->db->where("center_delete",'0');
		$center=$this->master_model->getRecords('center_master');
		if(count($applied_exam_info) <=0)
		{
			redirect(base_url());
		}
		
			$user_data=array('regid'=>$this->session->userdata('eregid'),
										'regnumber'=>$this->session->userdata('eregnumber'),
										'firstname'=>$this->session->userdata('efirstname'),
										'middlename'=>$this->session->userdata('emiddlename'),
										'lastname'=>$this->session->userdata('elastname'),
										'memtype'=>$this->session->userdata('memtype'),
										'timer'=>$this->session->userdata('etimer'),
										'password'=>$this->session->userdata('mpassword_applyexam'));
		$this->session->set_userdata($user_data);
		$data=array('middle_content'=>'ELearning/exam_applied_success_withoutpay','medium'=>$medium,'center'=>$center,'applied_exam_info'=>$applied_exam_info);
		$this->load->view('ELearning/mem_apply_exam_common_view',$data);
	
	}
	
	//##---------check mobile number alredy exist or not on edit page(Prafull)-----------## 
	 public function editmobile()
	{
		$mobile = $_POST['mobile'];
		$regid = $_POST['regid'];
		if($mobile!="" && $regid!="")
		{
			$prev_count=$this->master_model->getRecordCount('member_registration',array('mobile'=>$mobile,'regid !='=>$regid,'isactive'=>'1','registrationtype'=>$this->session->userdata('memtype')));
			if($prev_count==0)
			{echo 'ok';}
			else
			{echo 'exists';}
		}
		else
		{
			echo 'error';
		}
	}
	
	
	##---------check pincode/zipcode alredy exist or not (prafull)-----------##
   public function checkpin()
	{
		$statecode=$_POST['statecode'];
		$pincode=$_POST['pincode'];
		if($statecode!="")
		{
			$this->db->where("$pincode BETWEEN start_pin AND end_pin");
		 	$prev_count=$this->master_model->getRecordCount('state_master',array('state_code'=>$statecode));
			//echo $this->db->last_query();
			if($prev_count==0)
			{echo 'false';}
			else
			{echo 'true';}
		}
		else
		{
			echo 'false';
		}
	}
	
	//##---------check mail alredy exist or not on edit page(Prafull)-----------## 
	 public function editemailduplication()
	{
		$email = $_POST['email'];
		$regid = $_POST['regid'];
		if($email!="" && $regid!="")
		{
			$prev_count=$this->master_model->getRecordCount('member_registration',array('email'=>$email,'regid !='=>$regid,'isactive'=>'1','registrationtype'=>$this->session->userdata('memtype')));
			if($prev_count==0)
			{echo 'ok';}
			else
			{echo 'exists';}
		}
		else
		{
			echo 'error';
		}
	}


	##------------------Insert data in member_exam table for applied exam,for logged in user With Payment(PRAFULL)---------------##
	public function Msuccess()
	{
		$this->chk_session->ELearn_Mem_checklogin_external_user();
		$photoname=$singname='';
		if(($this->session->userdata('examinfo')==''))
		{
			redirect(base_url());
		}
		if(isset($_POST['btnPreview']))
		{
			$amount=getExamFee($this->session->userdata['examinfo']['txtCenterCode'],$this->session->userdata['examinfo']['eprid'],$this->session->userdata['examinfo']['excd'],$this->session->userdata['examinfo']['grp_code'],$this->session->userdata('memtype'));
			$inser_array=array(	'regnumber'=>$this->session->userdata('eregnumber'),
			 								'exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),
											'exam_mode'=>'ON',
											'exam_medium'=>$this->session->userdata['examinfo']['medium'],
											'exam_period'=>$this->session->userdata['examinfo']['eprid'],
											'exam_center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],
											'exam_fee'=>$amount,
											'elected_sub_code'=>$this->session->userdata['examinfo']['selected_elect_subcode'],
											'place_of_work'=>$this->session->userdata['examinfo']['placeofwork'],
											'state_place_of_work'=>$this->session->userdata['examinfo']['state_place_of_work'],
											'pin_code_place_of_work'=>$this->session->userdata['examinfo']['pincode_place_of_work'],
											'scribe_flag'=>$this->session->userdata['examinfo']['scribe_flag'],
											'scribe_flag_PwBD' => $this->session->userdata['examinfo']['scribe_flag_d'],
											'disability' => $this->session->userdata['examinfo']['disability_value'],
											'sub_disability' => $this->session->userdata['examinfo']['Sub_menue_disability'],
											'created_on'=>date('y-m-d H:i:s')
											);
			if($inser_id=$this->master_model->insertRecord('member_exam',$inser_array,true))
			{
				//echo $this->session->userdata['examinfo']['fee'];
				$this->session->userdata['examinfo']['insdet_id']=$inser_id;
				//$data['insdet_id'] =$inser_id;  
  				//$this->session->set_userdata('examinfo', $data);  
				$update_array=array();
				

				// Re-set previous image update flags
				$prev_edited_on = '';

				$prev_photo_flg = "N";
				$prev_signature_flg = "N";
				$prev_id_flg = "N";
				$prev_edited_on_qry = $this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('eregid')),'DATE(images_editedon) images_editedon,photo_flg,signature_flg,id_flg');
				if(count($prev_edited_on_qry))
				{
					$prev_edited_on = $prev_edited_on_qry[0]['images_editedon'];
					$prev_photo_flg = $prev_edited_on_qry[0]['photo_flg'];
					$prev_signature_flg = $prev_edited_on_qry[0]['signature_flg'];
					$prev_id_flg = $prev_edited_on_qry[0]['id_flg'];
					if($prev_edited_on != date('Y-m-d'))
					{
						$this->master_model->updateRecord('member_registration', array('photo_flg'=>'N', 'signature_flg'=>'N', 'id_flg'=>'N'), array('regid'=>$this->session->userdata('eregid')));
					}
				}
				
				//update an array for images
				$photo_flg = '';
				if($prev_edited_on != '' && $prev_edited_on != date('Y-m-d'))
				{	$photo_flg = 'N';	}
				else {	$photo_flg = $prev_photo_flg;	}
				
				$signature_flg = '';
				if($prev_edited_on != '' && $prev_edited_on != date('Y-m-d'))
				{	$signature_flg = 'N';	}
				else {	$signature_flg = $prev_signature_flg;	}
				
				if($this->session->userdata['examinfo']['photo']!='')
				{
					$update_array=array_merge($update_array, array("scannedphoto"=>$this->session->userdata['examinfo']['photo']));
					$photo_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('eregid'),'regnumber'=>$this->session->userdata('eregnumber')),'scannedphoto');
					$photoname=$photo_name[0]['scannedphoto'];
					$photo_flg = 'Y';
				}
				if($this->session->userdata['examinfo']['signname']!='')
				{
					$update_array=array_merge($update_array, array("scannedsignaturephoto"=>$this->session->userdata['examinfo']['signname']));	
					$sing_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('eregid'),'regnumber'=>$this->session->userdata('eregnumber')),'scannedsignaturephoto');
					$singname=$sing_name[0]['scannedsignaturephoto'];
					$signature_flg = 'Y';
				}
				
				if($prev_edited_on != date('Y-m-d') && ($photo_flg == 'Y' || $signature_flg == 'Y'))
				{
					$update_array['photo_flg'] = $photo_flg;
					$update_array['signature_flg'] = $signature_flg;
					$update_array['images_editedon'] = date('Y-m-d H:i:s');
					$update_array['images_editedby'] = 'Candidate';
				}
				
				$email_mbl_flg = 0;
				//check if email is unique
				$check_email=$this->master_model->getRecordCount('member_registration',array('email'=>$this->session->userdata['examinfo']['email'],'isactive'=>'1','registrationtype'=>$this->session->userdata('memtype')));
				if($check_email==0)
				{
					$update_array=array_merge($update_array, array("email"=>$this->session->userdata['examinfo']['email']));
					$email_mbl_flg = 1;	
				}
				// check if mobile is unique
				$check_mobile=$this->master_model->getRecordCount('member_registration',array('mobile'=>$this->session->userdata['examinfo']['mobile'],'isactive'=>'1','registrationtype'=>$this->session->userdata('memtype')));
			
				if($check_mobile==0)
				{
					$update_array=array_merge($update_array, array("mobile"=>$this->session->userdata['examinfo']['mobile']));
					$email_mbl_flg = 1;	
				}
				if(count($update_array) > 0)
				{
					$edited = '';
					foreach($update_array as $key => $val)
					{
						$edited .= strtoupper($key)." = ".strtoupper($val)." && ";
					}
					
					if($email_mbl_flg == 1)
					{
						$update_array['editedon'] = date('Y-m-d H:i:s');
						$update_array['editedby'] = "Candidate";
					}
					
					$prevData = array();
					$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('eregid'),'regnumber'=>$this->session->userdata('eregnumber'),'isactive'=>'1'));
					if(count($user_info))
					{
						$prevData = $user_info[0];
					}
					
					$desc['updated_data'] = $update_array;
					$desc['old_data'] = $prevData;
					
					$this->master_model->updateRecord('member_registration',$update_array,array('regid'=>$this->session->userdata('eregid'),'regnumber'=>$this->session->userdata('eregnumber')));
					
					log_profile_user($log_title = "Profile updated successfully", $edited,'data',$this->session->userdata('eregid'),$this->session->userdata('eregnumber'));
					
					logactivity($log_title ="Member update profile during exam apply", $log_message = serialize($desc));
					
				}
				
				if($this->config->item('exam_apply_gateway')=='sbi')
				{
					redirect(base_url().'ELearningTest/sbi_make_payment/');
				}
				else
				{
					redirect(base_url().'ELearningTest/make_payment/');
				}
			}
		}
		else
		{
			redirect(base_url());
		}
	}
	
	
	// BillDesk payment gateway
	public function make_payment()
	{
		if(isset($_POST['processPayment']) && $_POST['processPayment'])
		{
			$regno = $this->session->userdata('eregnumber');//$this->session->userdata('regnumber');

			$MerchantID = $this->config->item('bd_MerchantID');
			$SecurityID = $this->config->item('bd_SecurityID');
			
			$checksum_key = $this->config->item('bd_ChecksumKey');
			
			$pg_return_url = base_url()."ELearningTest/pg_response";
			
			//$amount = trim($this->session->userdata['examinfo']['fee']); // Exam fee//$this->config->item('dup_id_card_fee'); 
			$amount ='1';
			// Create transaction
			$insert_data = array(
				'member_regnumber' => $regno,
				'amount'           => $amount,
				'gateway'          => "billdesk",
				'date'             => date('Y-m-d h:i:s'),
				'pay_type'         => '2',
				'ref_id'           => $this->session->userdata['examinfo']['insdet_id'],
				'description'      => $this->session->userdata['examinfo']['exname'],//"Duplicate ID card request. Reason:".$this->session->userdata('desc'),
				'status'           => '2',
				'exam_code'    =>base64_decode($this->session->userdata['examinfo']['excd'])
			);
				
			$pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);

			//$pt_id = "DP9878280".$pt_id;
			
			$update_data = array(
				'receipt_no' => $pt_id
			);
			//print_r($update_data); exit;
			$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));

			$MerchantOrderNo    = "IIBFEXAM".$pt_id;//"DP98782802";  // TO DO : need to change
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
						
						$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $pg_res[24],'auth_code' => $pg_res[14]);
						$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
						
						//Query to get user details
						$this->db->join('state_master','state_master.state_code=member_registration.state');
						$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
						$result=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('eregid'),'regnumber'=>$this->session->userdata('eregnumber')),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');
					
						
						
					//Query to get exam details	
					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$this->session->userdata('eregnumber'),'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
					
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
						
						
					
						//Query to get Payment details	
						$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$this->session->userdata('eregnumber')),'transaction_no,date,amount');
				
						$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
						$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
						$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'apply_exam_transaction_success'));
						$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
						$newstring2 = str_replace("#REG_NUM#", "".$this->session->userdata('eregnumber')."",$newstring1);
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
					
						$info_arr=array('to'=>$result[0]['email'],
												'from'=>$emailerstr[0]['from'],
												'subject'=>$emailerstr[0]['subject'],
												'message'=>$final_str
											);
						
							$user_data=array('regid'=>$this->session->userdata('eregid'),
															'regnumber'=>$this->session->userdata('eregnumber'),
															'firstname'=>$this->session->userdata('efirstname'),
															'middlename'=>$this->session->userdata('emiddlename'),
															'lastname'=>$this->session->userdata('elastname'),
															'memtype'=>$this->session->userdata('memtype'),
															'timer'=>$this->session->userdata('etimer'),
															'password'=>$this->session->userdata('mpassword_applyexam'));
							$this->session->set_userdata($user_data);					
						//To Do---Transaction email to user	currently we using failure emailer 					
						if($this->Emailsending->mailsend($info_arr))
						{
							redirect(base_url().'ELearningTest/details/'.base64_encode($MerchantOrderNo).'/'.$this->session->userdata['examinfo']['excd']);
						}
						}
						else if($payment_status==0)
						{
							// Handle transaction fail case 
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $pg_res[24],'auth_code' => $pg_res[14]);
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
						
							$result=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('eregid'),'regnumber'=>$this->session->userdata('eregnumber')),'firstname,middlename,lastname,email,mobile');
							
							//Query to get Payment details	
							$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$this->session->userdata('eregnumber')),'transaction_no,date,amount');
						
							
							$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
							$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
							$newstring1 = str_replace("#application_num#", "".$this->session->userdata('eregnumber')."",  $emailerstr[0]['emailer_text']);
							$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
							$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
							$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
							
							$info_arr=array(	'to'=>$result[0]['email'],
														'from'=>$emailerstr[0]['from'],
														'subject'=>$emailerstr[0]['subject'],
														'message'=>$final_str
													);
							//To Do---Transaction email to user	currently we using failure emailer 					
							if($this->Emailsending->mailsend($info_arr))
							{
								redirect(base_url().'ELearningTest/fail/'.base64_encode($MerchantOrderNo));
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
	
	
	
	##------------------Exam appky with SBI Payment Gate-way(PRAFULL)---------------##
	public function sbi_make_payment()
	{
		$this->chk_session->ELearn_Mem_checklogin_external_user();
		$cgst_rate=$sgst_rate=$igst_rate=$tax_type='';
		$cgst_amt=$sgst_amt=$igst_amt='';
		$cs_total=$igst_total='';
		$getstate=$getcenter=$getfees=array();
		$valcookie= applyexam_get_cookie();
		if($valcookie)
		{
			redirect('http://iibf.org.in/');
		}
		if(isset($_POST['processPayment']) && $_POST['processPayment'])
		{
			//checked for application in payment process and prevent user to apply exam on the same time(Prafull)
				$checkpayment=$this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$this->session->userdata('eregnumber'),'status'=>'2','pay_type'=>'2'),'',array('id'=>'DESC'));
				if(count($checkpayment) > 0)
				{
					$endTime = date("Y-m-d H:i:s",strtotime("+20 minutes",strtotime($checkpayment[0]['date'])));
					 $current_time= date("Y-m-d H:i:s");
					if(strtotime($current_time)<=strtotime($endTime))
					{
						$this->session->set_flashdata('error','Wait your transaction is under process!.');
						redirect(base_url().'ELearningTest/comApplication');
					}
				}
			$sub_flag=1;
			$regno = $this->session->userdata('eregnumber');
			include APPPATH . 'third_party/SBI_ePay/CryptAES.php';

			$key = $this->config->item('sbi_m_key');
			$merchIdVal = $this->config->item('sbi_merchIdVal');
			$AggregatorId = $this->config->item('sbi_AggregatorId');
			
			$pg_success_url = base_url()."ELearningTest/sbitranssuccess";
			$pg_fail_url    = base_url()."ELearningTest/sbitransfail";
			
			if($this->config->item('sb_test_mode'))
			{
				$amount = $this->config->item('exam_apply_fee');
			}
			else
			{
				//$amount=$this->session->userdata['examinfo']['fee'];
				$amount=getExamFee($this->session->userdata['examinfo']['txtCenterCode'],$this->session->userdata['examinfo']['eprid'],$this->session->userdata['examinfo']['excd'],$this->session->userdata['examinfo']['grp_code'],$this->session->userdata('memtype'));
			}
			
			if($amount==0 || $amount=='')
			{
				$this->session->set_flashdata('error','Fee can not be zero(0) or Blank!!');
				redirect(base_url().'ELearningTest/comApplication/');
			}
			//$MerchantOrderNo    = generate_order_id("sbi_exam_order_id");
			
			//Ordinary member Apply exam
			//	Ref1 = orderid
			//	Ref2 = iibfexam
			//	Ref3 = IIBF_EL
			//	Ref4 = exam_code + exam year + exam month ex (101201602)
			$yearmonth=$this->master_model->getRecords('misc_master',array('exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'exam_period'=> $this->session->userdata['examinfo']['eprid']),'exam_month');
			
			
			$exam_code=base64_decode($this->session->userdata['examinfo']['excd']);	
			
			$ref4=($exam_code).$yearmonth[0]['exam_month'];
		    
			$insert_data = array(
				'member_regnumber' => $regno,
				'amount'           => $amount,
				'gateway'          => "sbiepay",
				'date'             => date('Y-m-d H:i:s'),
				'pay_type'         => '18',
				'ref_id'           => $this->session->userdata['examinfo']['insdet_id'],
				'description'      => $this->session->userdata['examinfo']['exname'],
				'status'           => '2',
				'exam_code'    	   => base64_decode($this->session->userdata['examinfo']['excd']),
				'pg_flag'=>'IIBF_EL' // IIBF_EXAM_EL
			);
				
			$pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
			
			$MerchantOrderNo = sbi_exam_order_id($pt_id);
			
			// payment gateway custom fields -
			$custom_field = $MerchantOrderNo."^iibfexam^"."IIBF_EL"."^".$ref4;
			
			// update receipt no. in payment transaction -
			$update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
			$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));
			
			
			//set invoice details(Prafull)
			$getcenter=$this->master_model->getRecords('center_master',array('exam_name'=>base64_decode($this->session->userdata['examinfo']['excd']),'center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],'exam_period'=>$this->session->userdata['examinfo']['eprid'],'center_delete'=>'0'));
			if(count($getcenter) > 0)
			{
				//get state code,state name,state number.
				//$getstate=$this->master_model->getRecords('state_master',array('state_code'=>$getcenter[0]['state_code'],'state_delete'=>'0'));
				
				//call to helper (fee_helper)
				$getfees=getExamFeedetails($this->session->userdata['examinfo']['txtCenterCode'],$this->session->userdata['examinfo']['eprid'],$this->session->userdata['examinfo']['excd'],$this->session->userdata['examinfo']['grp_code'],$this->session->userdata('memtype'));
			}
			
			/* Start to Get Member State Code for GST Calculations : Bhushan 8/April/2019 */
			$get_mem_state_code = $this->master_model->getRecords('member_registration',array('regnumber'=>$this->session->userdata('eregnumber'),'isactive'=>'1'),'state');
			
			$mem_state_code = "MAH";
			if(count($get_mem_state_code) > 0){
				$mem_state_code = $get_mem_state_code[0]['state'];
			}
			//get state code,state name,state number.
			$getstate = $this->master_model->getRecords('state_master',array('state_code'=>$mem_state_code,'state_delete'=>'0'));
			if($mem_state_code == 'MAH'){
				//set a rate (e.g 9%,9% or 18%)
				$cgst_rate=$this->config->item('cgst_rate');
				$sgst_rate=$this->config->item('sgst_rate');
				//set an amount as per rate
				$cgst_amt=$getfees[0]['cgst_amt'];
				$sgst_amt=$getfees[0]['sgst_amt'];
				 //set an total amount
				$cs_total=$getfees[0]['cs_tot'];
				$tax_type='Intra';
			}else{
				$igst_rate=$this->config->item('igst_rate');
				$igst_amt=$getfees[0]['igst_amt'];
				$igst_total=$getfees[0]['igst_tot']; 
				$tax_type='Inter';
			}
			
			/* End to Get Member State Code for GST Calculations : Bhushan 8/April/2019 */
			
			if($getstate[0]['exempt']=='E')
			{
				 $cgst_rate=$sgst_rate=$igst_rate='';	
				 $cgst_amt=$sgst_amt=$igst_amt='';	
			}	
				
			$invoice_insert_array=array('pay_txn_id'=>$pt_id,
													'receipt_no'=>$MerchantOrderNo,
													'exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),
													'center_code'=>$getcenter[0]['center_code'],
													'center_name'=>$getcenter[0]['center_name'],
													'state_of_center'=>$mem_state_code,
													'member_no'=>$this->session->userdata('eregnumber'),
													'app_type'=>'L',
													'exam_period'=>$this->session->userdata['examinfo']['eprid'],
													'service_code'=>$this->config->item('exam_service_code'),
													'qty'=>'1',
													'state_code'=>$getstate[0]['state_no'],
													'state_name'=>$getstate[0]['state_name'],
													'tax_type'=>$tax_type,
													'fee_amt'=>$getfees[0]['fee_amount'],
													'cgst_rate'=>$cgst_rate,
													'cgst_amt'=>$cgst_amt,
													'sgst_rate'=>$sgst_rate,
													'sgst_amt'=>$sgst_amt,
													'igst_rate'=>$igst_rate,
													'igst_amt'=>$igst_amt,
													'cs_total'=>$cs_total,
													'igst_total'=>$igst_total,
													'exempt'=>$getstate[0]['exempt'],
													'gstin_no'=>$this->session->userdata['examinfo']['gstin_no'],
													'created_on'=>date('Y-m-d H:i:s'));
			$inser_id=$this->master_model->insertRecord('exam_invoice',$invoice_insert_array);
			//insert into admit card table
			//################get userdata###########
			$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$this->session->userdata('eregnumber'),'isactive'=>'1'));
			
			
			//get associate institute details
			$institute_id='';
			$institution_name='';
			if($user_info[0]['associatedinstitute']!='')
			{
				$institution_master=$this->master_model->getRecords('institution_master',array('institude_id'=>$user_info[0]['associatedinstitute']));
				if(count($institution_master) >0)
				{
					$institute_id=$institution_master[0]['institude_id'];
					$institution_name=$institution_master[0]['name'];
				}
			}
		 //############check Gender########
			if($user_info[0]['gender']=='male')
			{$gender='M';}
			else
			{$gender='F';}
			//########prepare user name########
			$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
			$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
			
			//###########get State##########
			$states=$this->master_model->getRecords('state_master',array('state_code'=>$user_info[0]['state'],'state_delete'=>'0'));
			$state_name='';
			if(count($states) >0)
			{
				$state_name=$states[0]['state_name'];
			}		
			//##############Examination Mode###########
			$mode='Online';
			
			//set cookie for Apply Exam
			applyexam_set_cookie($this->session->userdata['examinfo']['insdet_id']);
			//$update_data = array('receipt_no' => $pt_id);
			//	$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));
			$MerchantCustomerID = $regno;
			$data["pg_form_url"] = $this->config->item('sbi_pg_form_url'); // SBI ePay form URL
			$data["merchIdVal"]  = $merchIdVal;
			/*
			requestparameter=
			MerchantId | OperatingMode | MerchantCountry | MerchantCurrency |
PostingAmount | OtherDetails | SuccessURL | FailURL | AggregatorId | MerchantOrderNo |
MerchantCustomerID | Paymode | Accesmedium | TransactionSource

			Ex.
			requestparameter
=1000003|DOM|IN|INR|2|Other|https://test.sbiepay.coom/secure/fail.jsp|SBIEPAY|2|2|NB|ONLINE|ONLINE
			*/

			$EncryptTrans = $merchIdVal."|DOM|IN|INR|".$amount."|".$custom_field."|".$pg_success_url."|".$pg_fail_url."|".$AggregatorId."|".$MerchantOrderNo."|".$MerchantCustomerID."|NB|ONLINE|ONLINE";

			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			
			$EncryptTrans = $aes->encrypt($EncryptTrans);
			
			$data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value

			$this->load->view('pg_sbi_form',$data);
		}
		else
		{
			$this->load->view('pg_sbi/make_payment_page');
		}
	}
	
	public function sbitranssuccess()
	{
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		$key = $this->config->item('sbi_m_key');
		$aes = new CryptAES();
		$aes->set_key(base64_decode($key));
		$aes->require_pkcs5();
		$encData = $aes->decrypt($_REQUEST['encData']);
		$attachpath=$invoiceNumber=$admitcard_pdf='';
		$responsedata = explode("|",$encData);
		$MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
		$transaction_no  = $responsedata[1];
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
						
		$elective_subject_name='';
		//Sbi B2B callback
	//check sbi payment status with MerchantOrderNo 
	$q_details = sbiqueryapi($MerchantOrderNo);
	if ($q_details)
	{
		if ($q_details[2] == "SUCCESS")
		{
			$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
			//check user payment status is updated by b2b or not
			if($get_user_regnum[0]['status']==2)
			{
				######### payment Transaction ############
				$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
				$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
				if($this->db->affected_rows())	
				{
				if(count($get_user_regnum) > 0)
				{
					$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
				}
		
			
				//Query to get user details
				$this->db->join('state_master','state_master.state_code=member_registration.state');
				$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name');
				//Query to get exam details	
				$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code');
				
				
				#########update member Exam##############
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
				else
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
					$log_title ="B2B Update fail:".$get_user_regnum[0]['member_regnumber'];
					$log_message = serialize($update_data);
					$rId = $MerchantOrderNo;
					$regNo = $get_user_regnum[0]['member_regnumber'];
					storedUserActivity($log_title, $log_message, $rId, $regNo);	
				}
				
				//Manage Log
				$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
				$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
			}
		}
	}//End of check sbi payment status with MerchantOrderNo 
	///End of SBICALL Back	
		
		
		
		//Main Code
		$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
		$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
		$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
		$user_data=array(	'regid'=>$this->session->userdata('eregid'),
									'regnumber'=>$this->session->userdata('eregnumber'),
									'firstname'=>$this->session->userdata('efirstname'),
									'middlename'=>$this->session->userdata('emiddlename'),
									'lastname'=>$this->session->userdata('elastname'),
									'memtype'=>$this->session->userdata('memtype'),
									'timer'=>$this->session->userdata('etimer'),
									'password'=>$this->session->userdata('mpassword_applyexam'));
		$this->session->set_userdata($user_data);		

		$temp_user_data=array('eregid'=>'',
											'eregnumber'=>'',
											'efirstname'=>'',
											'emiddlename'=>'',
											'elastname'=>'',
											'etimer'=>'',
											'mpassword_applyexam'=>'');
			foreach($temp_user_data as $key =>$val)
			{
				$this->session->unset_userdata($key);    
			}											
		redirect(base_url().'ELearningTest/details/'.base64_encode($MerchantOrderNo).'/'.base64_encode($exam_info[0]['exam_code']));
		
	}
	
	public function sbitransfail()
	{
		if (isset($_REQUEST['encData']))
		{
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$encData = $aes->decrypt($_REQUEST['encData']);
			$responsedata = explode("|",$encData);
			$MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
			$transaction_no  = $responsedata[1];
			//SBICALL Back B2B
			$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
			if($get_user_regnum[0]['status']!=0 && $get_user_regnum[0]['status']==2)
			{
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
				
			$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => 0399,'bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'B2B');
			$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
			//Query to get Payment details	
			$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
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
			//Manage Log
			$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
			$this->log_model->logtransaction("sbiepay", $pg_response,$responsedata[2]);		
			}
			//End Of SBICALL Back	
			redirect(base_url().'ELearningTest/fail/'.base64_encode($MerchantOrderNo));
		}
		else
		{
			die("Please try again...");
		}
	}
	
	
	//Show acknowlodgement to to user after transaction succeess
	public function details($order_no=NULL,$excd=NULL)
	{
		if(!isset($this->session->userdata['examinfo']))
		{
			redirect(base_url());
		}
		//payment detail
		$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($order_no),'member_regnumber'=>$this->session->userdata('regnumber')));
		
		$today_date=date('Y-m-d');
		$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month');
		$this->db->where('elg_mem_o','Y');	
		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
		$this->db->where("misc_master.misc_delete",'0');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
		$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
		$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($excd),'regnumber'=>$this->session->userdata('regnumber'),'pay_status'=>'1'));
		if(count($applied_exam_info)<=0)
		{
			redirect(base_url().'Home/dashboard');
		}
		$this->db->where('medium_delete','0');
		$this->db->where('exam_code',base64_decode($excd));
		$this->db->where('medium_code',$applied_exam_info[0]['exam_medium']);
		$medium=$this->master_model->getRecords('medium_master','','medium_description');

		$this->db->where('exam_name',base64_decode($excd));
		$this->db->where("center_delete",'0');
		$this->db->where('center_code',$applied_exam_info[0]['exam_center_code']);
		$center=$this->master_model->getRecords('center_master');
		
		
		//get state details
		$this->db->where('state_delete','0');
		$states=$this->master_model->getRecords('state_master');
		
		if(count($applied_exam_info) <=0)
		{
			redirect(base_url());
		}
		
		/*
		$user_data=array('email'=>'',
						'mobile'=>'',	
						'photo'=>'',
						'signname'=>'',
						'medium'=>'',
						'selCenterName'=>'',
						'optmode'=>'',
						'extype'=>'',
						'exname'=>'',
						'excd'=>'',
						'eprid'=>'',
						'fee'=>'',
						'txtCenterCode'=>'',
						'insdet_id'=>'',
						'selected_elect_subcode'=>'',
						'selected_elect_subname'=>'',

						'placeofwork'=>'',
						'state_place_of_work'=>'',
						'pincode_place_of_work'=>'',
						'elected_exam_mode'=>''
                		);
		$this->session->unset_userdata('examinfo',$user_data);
		*/
		$data=array('middle_content'=>'ELearning/exam_applied_success','medium'=>$medium,'center'=>$center,'applied_exam_info'=>$applied_exam_info,'payment_info'=>$payment_info,'states'=>$states);
		$this->load->view('ELearning/mem_apply_exam_common_view',$data);
	}
	
	//Show acknowlodgement to to user after transaction Failure
	public function fail($order_no=NULL)
	{
		//payment detail
		$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($order_no),'member_regnumber'=>$this->session->userdata('eregnumber')));
		if(count($payment_info) <=0)
		{
			redirect(base_url());
		}
		$data=array('middle_content'=>'ELearning/exam_applied_fail','payment_info'=>$payment_info);
		$this->load->view('ELearning/mem_apply_exam_common_view',$data);
	
	}
	
	
		##------- check eligible user----------##
	public function checkusers($examcode=NULL)
	{
		$flag=0;
		if($examcode!=NULL)
		{
			 $exam_code = array(33,47,51,52);
		 	 if(in_array($examcode,$exam_code))
			{
				 $this->db->where_in('eligible_master.exam_code', $exam_code);
				 $valid_member_list=$this->master_model->getRecords('eligible_master',array('eligible_period'=>'802','member_type'=>'O'),'member_no');
				 if(count($valid_member_list) > 0)
				 {
					foreach($valid_member_list as $row)
					{
						$memberlist_arr[]=$row['member_no'];
					}
					
					 if(in_array($this->session->userdata('eregnumber'),$memberlist_arr))
					{
						$flag=1;
					}
					else
					{
						$flag=0;
					}
				}
				else
				{
					$flag=0;
				}
			}
			else
			{
				$flag=1;
			}
		}
		return $flag;
		
	}
	
	//call back for checkpin
	public function check_checkpin($pincode,$statecode)
	{
		if($statecode!="" && $pincode!='')
		{
			$this->db->where("$pincode BETWEEN start_pin AND end_pin");
		 	$prev_count=$this->master_model->getRecordCount('state_master',array('state_code'=>$statecode));
			//echo $this->db->last_query();
			if($prev_count==0)
			{	$str='Please enter Valid Pincode';
				$this->form_validation->set_message('check_checkpin', $str); 
				return false;}
			else
			$this->form_validation->set_message('error', "");
			{return true;}
		}
		else
		{
			$str='Pincode/State field is required.';
			$this->form_validation->set_message('check_checkpin', $str); 
			return false;
		}
	}
	
	public function check_mobileduplication($mobile)
	{
		if($mobile!="")
		{
			$prev_count= $this->master_model->getRecordCount('member_registration',array('mobile'=>$mobile,'regid !='=>$this->session->userdata('eregid'),'isactive'=>'1','registrationtype'=>$this->session->userdata('memtype')));
			//echo $this->db->last_query();
			
			if($prev_count==0)
			{
				return true;
			}
			else
			{
				$str='The entered Mobile no already exist';
				$this->form_validation->set_message('check_mobileduplication', $str); 
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	public function check_emailduplication($email)
	{
		if($email!="")
		{
			$prev_count= $this->master_model->getRecordCount('member_registration',array('email'=>$email,'regid !='=>$this->session->userdata('eregid'),'isactive'=>'1','registrationtype'=>$this->session->userdata('memtype')));
			
			if($prev_count==0)
			{	
				return true;
			}
			else
			{
				$str='The entered email ID already exist';
				$this->form_validation->set_message('check_emailduplication', $str); 
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	######### if seat allocation full show message#######
	public function refund($order_no=NULL)
	{
		//payment detail
		//$this->db->join('member_exam','member_exam.id=payment_transaction.ref_id AND member_exam.exam_code=payment_transaction.exam_code');
		//$this->db->where('member_exam.regnumber',$this->session->userdata('regnumber'));
		$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($order_no)));
		
		if(count($payment_info) <=0)
		{
			redirect(base_url());
		}
		
		$this->db->where('remark','2');
		$admit_card_refund=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$payment_info[0]['ref_id']));
		if(count($admit_card_refund) >0)
		{
			$update_data = array('remark' => 3);
			$this->master_model->updateRecord('admit_card_details',$update_data,array('mem_exam_id'=>$payment_info[0]['ref_id']));
		}
		
		$exam_name=$this->master_model->getRecords('exam_master',array('exam_code'=>$payment_info[0]['exam_code']));
		
		$data=array('middle_content'=>'ELearning/member_refund','payment_info'=>$payment_info,'exam_name'=>$exam_name);
		$this->load->view('ELearning/mem_apply_exam_common_view',$data);
		
	
	}
	
	/* Get scribe drop down*/
    public function getsub_menue() {
        $deptid = $this->input->post('deptid');
        // Code for fetching department Dropdown
       $scribe_sub_disability=$this->master_model->getRecords('scribe_sub_disability',array('code'=>$deptid ,'is_delete'=>'0'));
	   
	   
        // EOF Code fetching department Dropdown
        $department_dropdown = $search_department = '';
        if (!empty($scribe_sub_disability)) {
            $department_dropdown.= "<select class='form-control' id='Sub_menue' name='Sub_menue'>";
           
                $department_dropdown.= "<option value=''>--Select--</option>";
          
            foreach ($scribe_sub_disability as $dkey => $dValue) {
                $deptid = $dValue['sub_code'];
                $dept_name = $dValue['sub_disability'];
                
                
                $department_dropdown.= "<option value=" . $dValue['sub_code'] . ">" . $dept_name . "</option>";
            }
            $department_dropdown.= "</select>";
            echo $department_dropdown;
        } else {
            echo $department_dropdown = "";
        }
    }
	
}
