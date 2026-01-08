<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cisi extends CI_Controller {
	
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
			$this->load->model('billdesk_pg_model');
	
		//$this->load->model('chk_session');
	  //	$this->chk_session->chk_member_session();
	  
	  //accedd denied due to GST
		//$this->master_model->warning();
	}

	 ##---------default userlogin (prafull)-----------##
	public function login()
	{	
		//session_destroy();
		//check exam active or not
		$this->session->unset_userdata('examinfo');
		$this->session->unset_userdata('cscexaminfo');
		$this->session->unset_userdata('enduserinfo');
		$this->session->unset_userdata('userinfo');
		$this->session->unset_userdata('examcode');  
		
		$data=array();
		$data['error']='';
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
				$where="(registrationtype='O' OR registrationtype='NM')";
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
						$user_data=array('mregid_applyexam'=>$user_info[0]['regid'],
													'mregnumber_applyexam'=>$user_info[0]['regnumber'],
													'mfirstname_applyexam'=>$user_info[0]['firstname'],
													'mmiddlename_applyexam'=>$user_info[0]['middlename'],
													'mlastname_applyexam'=>$user_info[0]['lastname'],
													'mtimer_applyexam'=>base64_encode($mysqltime),
													'memtype'=>$user_info[0]['registrationtype'],
													'mpassword_applyexam'=>base64_encode($decpass));
						$this->session->set_userdata($user_data);
						$sess = $this->session->userdata();
						$flag=$this->checkusers('993');
						$check=$this->examapplied($this->input->post('Username'),base64_encode('993'));
						if($flag==0)
						{
							redirect(base_url().'Cisi/accessdenied/');
						}
						else if($check)
						{
							redirect(base_url().'Cisi/alreadyapplied/');
						}
						else
						{
							
							$log_title ="CISI login".$user_info[0]['regnumber'];
							$log_message = serialize($sess);
							$rId = $user_info[0]['regnumber'];
							$regNo = $user_info[0]['regnumber'];
							storedUserActivity($log_title, $log_message, $rId, $regNo);	
							//redirect(base_url().'Cisi/sbi_make_payment/');
							redirect(base_url().'Cisi/dashboard/');
							//redirect(base_url().'Cisi/sbi_make_payment/');
							redirect(base_url().'Cisi/dashboard/');
						}
						//redirect(base_url().'Cisi/examdetails/');
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
		
		/*$this->load->helper('captcha');
		$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data['image'] = $cap['image'];
		$data['code']=$cap['word'];
		$this->session->set_userdata('mem_applyexam_captcha', $cap['word']);*/
		$this->load->model('Captcha_model');                 
		$captcha_img = $this->Captcha_model->generate_captcha_img('mem_applyexam_captcha');
		$data['image'] = $captcha_img;
		$this->load->view('Cisi/Cisi_login',$data);

	}
	
		public function dashboard()
		{
		$dataarr = array("regnumber"=>$this->session->userdata('mregnumber_applyexam'));
		$user_info=$this->master_model->getRecords('member_registration',$dataarr);
		####CisiExamintion has been closed#####
		//$user_info=array();
		#####Remove above line to make Cisi live ###
		$data = array("user_info"=>$user_info);
		
		$this->load->view('Cisi/Cisi_DASHBOARD',$data);
		
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
		/*$this->load->helper('captcha');
		$this->session->unset_userdata("mem_applyexam_captcha");
		$this->session->set_userdata("mem_applyexam_captcha", rand(1, 100000));
		$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data = $cap['image'];
		$_SESSION["mem_applyexam_captcha"] = $cap['word'];
		echo $data;*/
		$this->load->model('Captcha_model');                 
		echo $captcha_img = $this->Captcha_model->generate_captcha_img('mem_applyexam_captcha');
	}
	
	
	public function accessdenied()
	{
		$message='<div style="color:#F00">You are not eligible to register for Certificate in Risk in Financial Services Level 2 (Cisi)</div>';
		$data=array('middle_content'=>'Cisi/not_eligible','check_eligibility'=>$message);
  	    $this->load->view('Cisi/mem_apply_exam_common_view',$data);
	}
	
	public function alreadyapplied()
	{
		 $message='<div style="color:#F00">You have already applied for Certificate in Risk in Financial Services Level 2 (Cisi) </div>';
		 $data=array('middle_content'=>'Cisi/not_eligible','check_eligibility'=>$message);
		 $this->load->view('Cisi/mem_apply_exam_common_view',$data);
	}
	 
	##------------------ Specific Exam Details for logged in user(PRAFULL)---------------##
	public function examdetails()
	{		
			$flag=$this->checkusers(base64_decode($this->input->get('ExId')));
			if($flag==0)
			{  
				redirect(base_url().'Cisi/accessdenied/');
			}
			
			$profile_flag=1;
			if(validate_userdata($this->session->userdata('mregnumber_applyexam')) || !is_file(get_img_name($this->session->userdata('mregnumber_applyexam'),'s')) || !is_file(get_img_name($this->session->userdata('mregnumber_applyexam'),'p')) )
			{
				$profile_flag=0;
			}
			$message='';$cookieflag=1;
			$applied_exam_info=array();
			$flag=1;$checkqualifyflag=0;
			$examcode=base64_decode($this->input->get('ExId'));
			$valcookie=$this->session->userdata('mregnumber_applyexam');
			$check_qualify_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
		
			if($check_qualify_exam[0]['exam_category']==1)
			{
				redirect(base_url().'ApplySplexamM/examdetails/?ExId='.$this->input->get('ExId').'&'.'Extype='.$this->input->get('Extype'));
			}
			
			if($valcookie)
			{
				$regnumber= $valcookie;
				$checkpayment=$this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$regnumber,'status'=>'2','pay_type'=>'2'),'',array('id'=>'DESC'),'0','1');
				
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
							$check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('mregnumber_applyexam')));
							if(count($check_eligibility_for_applied_exam) > 0)
							{
								if($check_eligibility_for_applied_exam[0]['exam_status']=='P' || $check_eligibility_for_applied_exam[0]['exam_status']=='p' ||$check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v' || $check_eligibility_for_applied_exam[0]['exam_status']=='B' || $check_eligibility_for_applied_exam[0]['exam_status']=='b')
								{
									$flag=0;
									$message=$check_eligibility_for_applied_exam[0]['remark'];
								}
								else if(($check_eligibility_for_applied_exam[0]['exam_status']=='F' || $check_eligibility_for_applied_exam[0]['exam_status']=='f') && $check_eligibility_for_applied_exam[0]['fees']=='' && ($check_eligibility_for_applied_exam[0]['app_category']=='B1' || $check_eligibility_for_applied_exam[0]['app_category']=='B2'))
								{
									$flag=0;
									$message=$check_eligibility_for_applied_exam[0]['remark'];
								}
								else if($check_eligibility_for_applied_exam[0]['exam_status']=='F' || $check_eligibility_for_applied_exam[0]['exam_status']=='R')
								{
									$check=$this->examapplied($this->session->userdata('mregnumber_applyexam'),$this->input->get('ExId'));
									if(!$check)
									{
										$check_date=$this->examdate($this->session->userdata('mregnumber_applyexam'),$this->input->get('ExId'));
										if(!$check_date)
										{
										//CAIIB apply directly
										$flag=1;
										}
										else
										{
											$message=$this->get_alredy_applied_examname($this->session->userdata('mregnumber_applyexam'),$this->input->get('ExId'));
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
										$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same.';
										$flag=0;
									}
								}
							}
							else
							{
							
								$check=$this->examapplied($this->session->userdata('mregnumber_applyexam'),$this->input->get('ExId'));
							
								if(!$check)
								{
									
									$check_date=$this->examdate($this->session->userdata('mregnumber_applyexam'),$this->input->get('ExId'));
									
									if(!$check_date)
									{
									//CAIIB apply directly
									$flag=1;
									}
									else
									{
										$message=$this->get_alredy_applied_examname($this->session->userdata('mregnumber_applyexam'),$this->input->get('ExId'));
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
										$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same.';
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
				$is_transaction_doone=$this->master_model->getRecordCount('payment_transaction',array('exam_code'=>$examcode,'member_regnumber'=>$this->session->userdata('mregnumber_applyexam'),'status'=>'1'));
				
			 if($is_transaction_doone >0)
			 {
				$today_date=date('Y-m-d');
				$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description');
				$this->db->where('exam_master.elg_mem_o','Y');
				//$this->db->join('payment_transaction','payment_transaction.exam_code=member_exam.exam_code');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				//$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
				$this->db->where('member_exam.pay_status','1');
				$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>$examcode,'regnumber'=>$this->session->userdata('mregnumber_applyexam')));
			}
				
				if($cookieflag==0 && $profile_flag==1)
				{
					$data=array('middle_content'=>'Cisi/exam_apply_cms_msg');
					$this->load->view('Cisi/mem_apply_exam_common_view',$data);
				}
				else if($flag==0 && $cookieflag==1)
				{
					if($profile_flag==0)
					{
						$message='<div style="color:#F00" class="col-md-4">Please update your profile!!<a href='.base_url().'> Click here </a>to login</div>';
					}
					 $data=array('middle_content'=>'Cisi/not_eligible','check_eligibility'=>$message);
					 $this->load->view('Cisi/mem_apply_exam_common_view',$data);
				}

				else if(count($applied_exam_info) > 0)
				{
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
					$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$examcode,'misc_master.misc_delete'=>'0'),'exam_month');
					//$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
					$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
					$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
					$message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same.';
					if($profile_flag==0)
					{
						$message='<div style="color:#F00" class="col-md-4">Please update your profile!!<a href='.base_url().'> Click here </a>to login</div>';
					}
					 $data=array('middle_content'=>'Cisi/already_apply','check_eligibility'=>$message);
					 $this->load->view('Cisi/mem_apply_exam_common_view',$data);
				}
				else if($cookieflag==1 && $profile_flag==1)
				{
					$exam_info=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
				 	if(count($exam_info)<=0)
					{
						redirect(base_url());
					}
					$data=array('middle_content'=>'Cisi/cms_page','exam_info'=>$exam_info);
					$this->load->view('Cisi/mem_apply_exam_common_view',$data);
				}
				else if($profile_flag==0)
				{
					 $message='<div style="color:#F00" class="col-md-4">Please update your profile!!<a href='.base_url().'> Click here </a>to login</div>';
					 $data=array('middle_content'=>'Cisi/not_eligible','check_eligibility'=>$message);
					 $this->load->view('Cisi/mem_apply_exam_common_view',$data);
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
		$check_qualify=array();
		$check_qualify_exam_name=$this->master_model->getRecords('exam_master',array('exam_code'=>$qualify_id),'description');
		if(count($check_qualify_exam_name) > 0)
		{
			$message='you have not cleared qualifying examination - <strong>'.$check_qualify_exam_name[0]['description'].'</strong>.';
		}
		else
		{
			$message='you have not cleared qualifying examination.';
		}
		$check_qualify_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
		//Query to check the qualifying exam details of selected exam(Below code for those exam who have pre-qualifying exam)
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
		$check_qualify_exam_eligibility=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$qualify_id,'part_no'=>$part_no,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('mregnumber_applyexam')),'exam_status,remark');
		
		if(count($check_qualify_exam_eligibility) > 0)
		{
			if($check_qualify_exam_eligibility[0]['exam_status']=='P')
			{
					//check eligibility for applied exam(This are the exam who  have pre qualifying exam)
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
					$check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('mregnumber_applyexam')));
					
					if(count($check_eligibility_for_applied_exam) > 0)
					{
						if($check_eligibility_for_applied_exam[0]['exam_status']=='P' || $check_eligibility_for_applied_exam[0]['exam_status']=='p' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v' || $check_eligibility_for_applied_exam[0]['exam_status']=='B' || $check_eligibility_for_applied_exam[0]['exam_status']=='b')
						{
							$flag=0;
							if(base64_decode($this->input->get('Extype'))=='3')
							{
								
									$message='You have already cleared this subject as separate  Examination. Hence you cannot apply for the same.';
								//$message='You have already cleared this subject under <strong>'.$check_qualify_exam_name[0]['description'].'</strong> Elective Examination. Hence you cannot apply for the same';
							}
							else
							{
								$message=$check_eligibility_for_applied_exam[0]['remark'];
							}
							$check_qualify=array('flag'=>$flag,'message'=>$message);
							return $check_qualify;
						}
						else if($check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' )
						{
							$flag=0;
							$message=$check_eligibility_for_applied_exam[0]['remark'];
							$check_qualify=array('flag'=>$flag,'message'=>$message);
							return $check_qualify;
						}
						else if(($check_eligibility_for_applied_exam[0]['exam_status']=='F' || $check_eligibility_for_applied_exam[0]['exam_status']=='f') && $check_eligibility_for_applied_exam[0]['fees']=='' && ($check_eligibility_for_applied_exam[0]['app_category']=='B1' || $check_eligibility_for_applied_exam[0]['app_category']=='B2'))
						{
							$flag=0;
							$message=$check_eligibility_for_applied_exam[0]['remark'];
							$check_qualify=array('flag'=>$flag,'message'=>$message);
							return $check_qualify;
						}
								
						else if($check_eligibility_for_applied_exam[0]['exam_status']=='F'  || $check_eligibility_for_applied_exam[0]['exam_status']=='R')
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
				$message=$check_qualify_exam_eligibility[0]['remark'];
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
					if(base64_decode($this->input->get('Extype'))=='3')
					{
						$message='you have not cleared qualifying examination - <strong>'.$get_exam[0]['description'].'</strong>.';
					}
					else
					{
						$message='You have not cleared  <strong>'.$get_exam[0]['description'].'</strong> examination, hence you cannot apply for <strong> '.$check_qualify_exam[0]['description'].'</strong>.';
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
		//$today_date=date('Y-m-d');
		$this->db->where('pay_status','1');
		$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($exam_code),'regnumber'=>$regnumber));
		//echo $this->db->last_query();exit;
		return count($applied_exam_info);
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
					}
				}
			}
		return $msg;
	}
	


	##------------------Insert data in member_exam table for applied exam,for logged in user With Payment(PRAFULL)---------------##
	public function Msuccess()
	{
		$photoname=$singname='';
		if(($this->session->userdata('examinfo')==''))
		{
			redirect(base_url());
		}
		if(isset($_POST['btnPreview']))
		{
			$inser_array=array(	'regnumber'=>$this->session->userdata('mregnumber_applyexam'),
			 								'exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),
											'exam_mode'=>$this->session->userdata['examinfo']['optmode'],
											'exam_medium'=>$this->session->userdata['examinfo']['medium'],
											'exam_period'=>$this->session->userdata['examinfo']['eprid'],
											'exam_center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],
											'exam_fee'=>$this->session->userdata['examinfo']['fee'],
											'elected_sub_code'=>$this->session->userdata['examinfo']['selected_elect_subcode'],
											'place_of_work'=>$this->session->userdata['examinfo']['placeofwork'],
											'state_place_of_work'=>$this->session->userdata['examinfo']['state_place_of_work'],
											'pin_code_place_of_work'=>$this->session->userdata['examinfo']['pincode_place_of_work'],
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
				$prev_edited_on_qry = $this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam')),'DATE(images_editedon) images_editedon,photo_flg,signature_flg,id_flg');
				if(count($prev_edited_on_qry))
				{
					$prev_edited_on = $prev_edited_on_qry[0]['images_editedon'];
					$prev_photo_flg = $prev_edited_on_qry[0]['photo_flg'];
					$prev_signature_flg = $prev_edited_on_qry[0]['signature_flg'];
					$prev_id_flg = $prev_edited_on_qry[0]['id_flg'];
					if($prev_edited_on != date('Y-m-d'))
					{
						$this->master_model->updateRecord('member_registration', array('photo_flg'=>'N', 'signature_flg'=>'N', 'id_flg'=>'N'), array('regid'=>$this->session->userdata('mregid_applyexam')));
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
					$photo_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')),'scannedphoto');
					$photoname=$photo_name[0]['scannedphoto'];
					$photo_flg = 'Y';
				}
				if($this->session->userdata['examinfo']['signname']!='')
				{
					$update_array=array_merge($update_array, array("scannedsignaturephoto"=>$this->session->userdata['examinfo']['signname']));	
					$sing_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')),'scannedsignaturephoto');
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
					$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam'),'isactive'=>'1'));
					if(count($user_info))
					{
						$prevData = $user_info[0];
					}
					
					$desc['updated_data'] = $update_array;
					$desc['old_data'] = $prevData;
					
					$this->master_model->updateRecord('member_registration',$update_array,array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')));
					
					log_profile_user($log_title = "Profile updated successfully", $edited,'data',$this->session->userdata('mregid_applyexam'),$this->session->userdata('mregnumber_applyexam'));
					
					logactivity($log_title ="Member update profile during exam apply", $log_message = serialize($desc));
					
				}
				
				if($this->config->item('exam_apply_gateway')=='sbi')
				{
					redirect(base_url().'Cisi/sbi_make_payment/');
				}
				else
				{
					redirect(base_url().'Cisi/make_payment/');
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
			$regno = $this->session->userdata('mregnumber_applyexam');//$this->session->userdata('regnumber');

			$MerchantID = $this->config->item('bd_MerchantID');
			$SecurityID = $this->config->item('bd_SecurityID');
			
			$checksum_key = $this->config->item('bd_ChecksumKey');
			
			$pg_return_url = base_url()."Cisi/pg_response";
			
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
						$result=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');
					
						
						
					//Query to get exam details	
					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$this->session->userdata('mregnumber_applyexam'),'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
					
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
						$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$this->session->userdata('mregnumber_applyexam')),'transaction_no,date,amount');
				
						$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
						$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
						$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'apply_exam_transaction_success'));
						$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
						$newstring2 = str_replace("#REG_NUM#", "".$this->session->userdata('mregnumber_applyexam')."",$newstring1);
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
						
							$user_data=array('regid'=>$this->session->userdata('mregid_applyexam'),
															'regnumber'=>$this->session->userdata('mregnumber_applyexam'),
															'firstname'=>$this->session->userdata('mfirstname_applyexam'),
															'middlename'=>$this->session->userdata('mmiddlename_applyexam'),
															'lastname'=>$this->session->userdata('mlastname_applyexam'),
															'memtype'=>$this->session->userdata('memtype'),
															'timer'=>$this->session->userdata('mtimer_applyexam'),
															'password'=>$this->session->userdata('mpassword_applyexam'));
							$this->session->set_userdata($user_data);					
						//To Do---Transaction email to user	currently we using failure emailer 					
						if($this->Emailsending->mailsend($info_arr))
						{
							redirect(base_url().'Cisi/details/'.base64_encode($MerchantOrderNo).'/'.$this->session->userdata['examinfo']['excd']);
						}
						}
						else if($payment_status==0)
						{
							// Handle transaction fail case 
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $pg_res[24],'auth_code' => $pg_res[14]);
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
						
							$result=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam')),'firstname,middlename,lastname,email,mobile');
							
							//Query to get Payment details	
							$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$this->session->userdata('mregnumber_applyexam')),'transaction_no,date,amount');
						
							
							$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
							$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
							$newstring1 = str_replace("#application_num#", "".$this->session->userdata('mregnumber_applyexam')."",  $emailerstr[0]['emailer_text']);
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
								redirect(base_url().'Cisi/fail/'.base64_encode($MerchantOrderNo));
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
		if(isset($_POST['processPayment']) && $_POST['processPayment'])
		{
				$pg_name = 'billdesk';
				if(isset($_POST['pg_name']) && $_POST['pg_name'] != "") { $pg_name = $this->input->post('pg_name'); }
				
			$regno = $this->session->userdata('mregnumber_applyexam');//$this->session->userdata('regnumber');

			//$amount = $this->config->item('Cisi_apply_fee');
			$amount =$this->config->item('Cisi_cs_total');
			$inser_array=array(	'regnumber'=>$this->session->userdata('mregnumber_applyexam'),
			 								'exam_code'=>'993',
											'exam_mode'=>'',
											'exam_medium'=>'E',
											'exam_period'=>'997',
											'exam_center_code'=>'306',
											'exam_fee'=>$amount,
											'created_on'=>date('y-m-d H:i:s')
											);
				$inser_id = $member_exam_id = $this->master_model->insertRecord('member_exam',$inser_array,true);
			
			$log_title ="CISI insert in member exam ".$this->session->userdata('mregnumber_applyexam');
			$log_message = serialize($inser_array);
			$rId = $this->session->userdata('mregnumber_applyexam');
			$regNo = $this->session->userdata('mregnumber_applyexam');
			storedUserActivity($log_title, $log_message, $rId, $regNo);
			//$MerchantOrderNo    = generate_order_id("sbi_exam_order_id");
			
			//Ordinary member Apply exam 
			//	Ref1 = orderid
			//	Ref2 = iibfexam
			//	Ref3 = member reg num
			//	Ref4 = exam_code + exam year + exam month ex (101201602)
			
			//$ref4='990301706';
			$ref4='993'.date('Y').''.date('m');
		    if($this->session->userdata('memtype')=='O')
			{
				$pg_flag='IIBF_EXAM_O';
			}
			else if($this->session->userdata('memtype')=='NM')
			{
				$pg_flag='IIBF_EXAM_NM';
			}
			$insert_data = array(
									'member_regnumber' => $regno,
									'amount'           => $amount,
									'gateway'          => "sbiepay",
									'date'             => date('Y-m-d H:i:s'),
									'pay_type'         => '2',
									'ref_id'           => $inser_id,
									'description'      =>'Certificate in Risk in Financial Services Level 2',//"Duplicate ID card request. Reason:".$this->session->userdata('desc'),
									'status'           => '2',
									'exam_code'    	   => '993',
									//'receipt_no'       => $MerchantOrderNo,
									'pg_flag'=>$pg_flag,
									//'pg_other_details'=>$custom_field
			);
			
			$pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
			
			$log_title ="CISI insert in payment transaction ".$regno;
			$log_message = serialize($insert_data);
			$rId = $regno;
			$regNo = $regno;
			storedUserActivity($log_title, $log_message, $rId, $regNo);
			
			if ($this->db->trans_status() === FALSE)
			{
				echo "Apply after some time..!!";exit;
			}
			else
			{
				$MerchantOrderNo = sbi_exam_order_id($pt_id);
				// payment gateway custom fields -
				$custom_field = $MerchantOrderNo."^iibfexam^".$this->session->userdata('mregnumber_applyexam')."^".$ref4;
				
				//$custom_field = $MerchantOrderNo."^iibfexam^iibfdupcer^".$member_no;
			//$custom_field_billdesk = $MerchantOrderNo."-iibfexam" . "-iibfdupcer" . "-" .$member_no;
			$custom_field_billdesk = $MerchantOrderNo."-iibfexam-".$this->session->userdata('mregnumber_applyexam')."-".$ref4;
			
				/* Cisi Invoice */
				$invoice_insert_array=array('pay_txn_id'=>$pt_id,
											'receipt_no'=>$MerchantOrderNo,
											'exam_code'=>'993',
											'center_code'=>'306',
											'center_name'=>'MUMBAI',
											'state_of_center'=>'MAH',
											'member_no'=>$this->session->userdata('mregnumber_applyexam'),
											'app_type'=>'O',
											'exam_period'=>'997',
											'service_code'=>$this->config->item('Cisi_service_code'),
											'qty'=>'1',
											'state_code'=>'27',
											'state_name'=>'MAHARASHTRA',
											'tax_type'=>'Intra',
											'fee_amt'=>$this->config->item('Cisi_apply_fee'),
											'cgst_rate'=>$this->config->item('Cisi_cgst_rate'),
											'cgst_amt'=>$this->config->item('Cisi_cgst_amt'),
											'sgst_rate'=>$this->config->item('Cisi_sgst_rate'),
											'sgst_amt'=>$this->config->item('Cisi_sgst_amt'),
											'igst_rate'=>'0.00',
											'igst_amt'=>'0.00',
											'cs_total'=>$this->config->item('Cisi_cs_total'),
											'igst_total'=>'0.00',
											'exempt'=>'NE',
											'created_on'=>date('Y-m-d H:i:s'));
				$inser_id=$this->master_model->insertRecord('exam_invoice',$invoice_insert_array);
				
				
				$log_title ="CISI insert in exam invoice ".$this->session->userdata('mregnumber_applyexam');
				$log_message = serialize($invoice_insert_array);
				$rId = $this->session->userdata('mregnumber_applyexam');
				$regNo = $this->session->userdata('mregnumber_applyexam');
				storedUserActivity($log_title, $log_message, $rId, $regNo);
				/* Close Cisi Invoice */
		
				// update receipt no. in payment transaction -
				$update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
				
				$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));
				
					if ($pg_name == 'sbi')
					{
						include APPPATH . 'third_party/SBI_ePay/CryptAES.php';					
						$key = $this->config->item('sbi_m_key');
						$merchIdVal = $this->config->item('sbi_merchIdVal');
						$AggregatorId = $this->config->item('sbi_AggregatorId');
				
		$pg_success_url = base_url()."Cisi/sbitranssuccess";
						$pg_fail_url    = base_url()."Cisi/sbitransfail";		
				$log_title ="CISI payment upadte ".$this->session->userdata('mregnumber_applyexam');
				$log_message = serialize($update_data);
				$rId = $this->session->userdata('mregnumber_applyexam');
				$regNo = $this->session->userdata('mregnumber_applyexam');
				storedUserActivity($log_title, $log_message, $rId, $regNo);
				
				$MerchantCustomerID = $regno;
				$data["pg_form_url"] = $this->config->item('sbi_pg_form_url'); // SBI ePay form URL
				$data["merchIdVal"]  = $merchIdVal;
				$EncryptTrans = $merchIdVal."|DOM|IN|INR|".$amount."|".$custom_field."|".$pg_success_url."|".$pg_fail_url."|".$AggregatorId."|".$MerchantOrderNo."|".$MerchantCustomerID."|NB|ONLINE|ONLINE";
		
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				
				$EncryptTrans = $aes->encrypt($EncryptTrans);
				$data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
				$this->load->view('pg_sbi_form',$data);
			}
					elseif ($pg_name == 'billdesk') 
					{
						$update_payment_data = array('gateway' =>'billdesk');
						$this->master_model->updateRecord('payment_transaction',$update_payment_data,array('id'=>$pt_id));
						
						$billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $member_exam_id, $member_exam_id, '', 'Cisi/handle_billdesk_response', '', '', '', $custom_field_billdesk);
						
						if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') 
						{
							$data['bdorderid'] = $billdesk_res['bdorderid'];
							$data['token'] = $billdesk_res['token'];
							$data['responseXHRUrl'] = $billdesk_res['responseXHRUrl']; 
							$data['returnUrl'] = $billdesk_res['returnUrl'];
							$this->load->view('pg_billdesk/pg_billdesk_form', $data);
						}
						else
						{
							$this->session->set_flashdata('error','Transaction failed...!');
							redirect(base_url().'Cisi/login');
						}
					}
				}
		}
		else
		{
			$data['show_billdesk_option_flag'] = 1;
			$this->load->view('pg_sbi/make_payment_page', $data);
		}
	}
	
		public function handle_billdesk_response()
		{
			/* ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL); */
			
			if (isset($_REQUEST['transaction_response'])) 
			{
				$response_encode = $_REQUEST['transaction_response'];
				$bd_response = $this->billdesk_pg_model->verify_res($response_encode);
				$attachpath=$invoiceNumber=$admitcard_pdf='';
				//echo '<pre>'; print_r($bd_response); echo '</pre>'; //exit;
				
				$responsedata = $bd_response['payload'];
				//echo '<pre>'; print_r($responsedata); echo '</pre>'; exit;
				
				$MerchantOrderNo = $responsedata['orderid']; // To DO: temp testing changes please remove it and use valid receipt id
				$transaction_no  = $responsedata['transactionid'];
				$merchIdVal = $responsedata['mercid'];
				$Bank_Code = $responsedata['bankid'];
				 $auth_status = $responsedata['auth_status'];	
				$encData = $_REQUEST['transaction_response'];
				
				$elective_subject_name='';
				$exam_period_date='';
				
				$transaction_error_type = $responsedata['transaction_error_type'];				
				
				$qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
				if($auth_status == "0300" && $qry_api_response['auth_status'] == '0300')
				{		
					//echo '<br>'.date('H:i:s');
					$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
					//echo '<br>'.$this->db->last_query(); 
					
					//check user payment status is updated by s2s or not
					if($get_user_regnum[0]['status']==2)
					{
						if(count($get_user_regnum) > 0)
						{
							$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
						}
						
						$update_data = array('pay_status' => '1');
						$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
						
						$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata['transaction_error_type']." - ".$responsedata['transaction_error_desc'],'auth_code' => '0300', 'bankcode' => $responsedata['bankid'], 'paymode' => $responsedata['txn_process_type'],'callback'=>'B2B');
						
						$this->db->trans_start();
						$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
						$this->db->trans_complete();
						
						//Query to get user details
						//$this->db->join('state_master','state_master.state_code=member_registration.state');
						//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
						$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']));
						
						//Query to get exam details	
						$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
						$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
						$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
						$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
						$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code');
						
						if(count($exam_info) <= 0)
						{
							$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']));
						}						
						
						if($exam_info[0]['exam_mode']=='ON') { $mode='Online'; } 
						elseif($exam_info[0]['exam_mode']=='OF') { $mode='Offline'; }
						else{ $mode=''; }
						
						if($exam_info[0]['examination_date']!='0000-00-00')
						{
							$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
						}
						else if($exam_info[0]['exam_code']!=993)
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
						$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,exam_code,id');
						
						$username = $result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
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
							
							$emailerstr = $this->master_model->getRecords('emailer',array('emailer_name'=>'member_exam_enrollment_nofee_elective'));
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
						}
						else
						{
							if($exam_info[0]['exam_code']==993)
							{
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'Cisi_emailer'));
								$final_str = $emailerstr[0]['emailer_text'];
							}							
						}
						
						$info_arr=array('to'=>$result[0]['email'],
						'from'=>$emailerstr[0]['from'],
						'subject'=>$emailerstr[0]['subject'],
						'message'=>$final_str
						);
						
						//get invoice	
						$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']),'invoice_id');
						
						if(count($getinvoice_number) > 0)
						{
							$invoiceNumber =generate_CISI_invoice_number($getinvoice_number[0]['invoice_id']);
							if($invoiceNumber)
							{
								$invoiceNumber=$this->config->item('Cisi_invoice_no_prefix').$invoiceNumber;
							}
							
							$log_title ="CISI invoice number generate ".$transaction_no;
							$log_message = "CISI invoice number generate ".$transaction_no;
							$rId = $this->session->userdata('mregnumber_applyexam');;
							$regNo = $this->session->userdata('mregnumber_applyexam');;
							storedUserActivity($log_title, $log_message, $rId, $regNo);
							
							$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
							$this->db->where('pay_txn_id',$payment_info[0]['id']);
							$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
							$attachpath=genarate_CISI_invoice($getinvoice_number[0]['invoice_id']);
						}
						
						if($exam_info[0]['exam_code']==993)
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
						
						if($attachpath!='')
						{	
							$this->master_model->send_sms('7588096918',$sms_final_str);	/* $result[0]['mobile'] */
							//$this->Emailsending->mailsend($info_arr);
							$this->Emailsending->mailsend_attch($info_arr,$attachpath);
						}
						//Manage Log
						$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
						$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata['transaction_error_type']);
					}
					
					redirect(base_url().'Cisi/details/'.base64_encode($MerchantOrderNo));
				}
				elseif ($auth_status == "0002") {
					
					$update_data = array('transaction_no' => $transaction_no,'status' => 2,'transaction_details' => $responsedata['transaction_error_type']." - ".$responsedata['transaction_error_desc'],'auth_code' => '0002','bankcode' => $responsedata['bankid'],'paymode' =>  $responsedata['txn_process_type'],'callback'=>'B2B');
					$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
						$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
						$this->log_model->logtransaction("billdesk", $pg_response,$responsedata['transaction_error_type']);	
					redirect(base_url().'Cisi/pending/'.base64_encode($MerchantOrderNo));
				}
				else
				{					
					$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code');
					if($get_user_regnum[0]['status']!=0 && $get_user_regnum[0]['status']==2)
					{
						$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata['transaction_error_type']." - ".$responsedata['transaction_error_desc'],'auth_code' => '0399','bankcode' => $responsedata['bankid'],'paymode' =>  $responsedata['txn_process_type'],'callback'=>'B2B');
						$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
						//Query to get Payment details	
						$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
						if($get_user_regnum[0]['exam_code']!='993')
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
							$this->master_model->send_sms('7588096918',$sms_final_str);	/* $result[0]['mobile'] */
							$this->Emailsending->mailsend($info_arr);
						}
						//Manage Log
						$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
						$this->log_model->logtransaction("billdesk", $pg_response,$responsedata['transaction_error_type']);		
					}
					//End Of SBICALL Back	
					redirect(base_url().'Cisi/fail/'.base64_encode($MerchantOrderNo));
				}
			}
			else 
			{
				die("Please try again...");
			}		
		}		
		
	public function sbitranssuccess()
	{
		die();
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		$key = $this->config->item('sbi_m_key');
		$aes = new CryptAES();
		$aes->set_key(base64_decode($key));
		$aes->require_pkcs5();
		$encData = $aes->decrypt($_REQUEST['encData']);
		$responsedata = explode("|",$encData);
		$MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
		$transaction_no  = $responsedata[1];
		$attachpath=$invoiceNumber='';
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
	$exam_period_date='';
	$q_details = sbiqueryapi($MerchantOrderNo);
	if ($q_details)
	{
		if ($q_details[2] == "SUCCESS")
		{
			$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
		
			//check user payment status is updated by s2s or not
			if($get_user_regnum[0]['status']==2)
			{
				if(count($get_user_regnum) > 0)
				{
					$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
				}
	
			$update_data = array('pay_status' => '1');
			$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));

			$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
			
			$this->db->trans_start();
			$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
			$this->db->trans_complete();
			
			//Query to get user details
			//$this->db->join('state_master','state_master.state_code=member_registration.state');
			//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
			$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']));
			
			//Query to get exam details	
			$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
			$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code,member_exam.exam_code');
			
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
			else if($exam_info[0]['exam_code']!=993)
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
			$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,exam_code,id');
	
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
			 }
			else
			{
				if($exam_info[0]['exam_code']==993)
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
				'subject'=>$emailerstr[0]['subject'],
				'message'=>$final_str
				);
			
			//get invoice	
			$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']),'invoice_id');
			
			if(count($getinvoice_number) > 0)
			{
				$invoiceNumber =generate_CISI_invoice_number($getinvoice_number[0]['invoice_id']);
				if($invoiceNumber)
				{
					$invoiceNumber=$this->config->item('Cisi_invoice_no_prefix').$invoiceNumber;
				}
				
				$log_title ="CISI invoice number generate ".$transaction_no;
				$log_message = "CISI invoice number generate ".$invoiceNumber;
				$rId = $this->session->userdata('mregnumber_applyexam');;
				$regNo = $this->session->userdata('mregnumber_applyexam');;
				storedUserActivity($log_title, $log_message, $rId, $regNo);
				
				$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
				$this->db->where('pay_txn_id',$payment_info[0]['id']);
				$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
				
				$log_title ="CISI update invoice number ".$transaction_no;
				$log_message = serialize($update_data);
				$rId = $this->session->userdata('mregnumber_applyexam');;
				$regNo = $this->session->userdata('mregnumber_applyexam');;
				storedUserActivity($log_title, $log_message, $rId, $regNo); 
				
				
				$attachpath=genarate_CISI_invoice($getinvoice_number[0]['invoice_id']);
			}
					
			if($exam_info[0]['exam_code']==993)
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
				
			if($attachpath!='')
			{	
				//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
				$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'C-48OSQMg',$exam_info[0]['exam_code']);
				//$this->Emailsending->mailsend($info_arr);
				$this->Emailsending->mailsend_attch($info_arr,$attachpath);
			}
				//Manage Log
				$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
				$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
			}
		}
	}//End of check sbi payment status with MerchantOrderNo 
	///End of SBICALL Back	
		
		
		
		//Main Code
		/*$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
		$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
		$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');*/
		$user_data=array(	'regid'=>$this->session->userdata('mregid_applyexam'),
									'regnumber'=>$this->session->userdata('mregnumber_applyexam'),
									'firstname'=>$this->session->userdata('mfirstname_applyexam'),
									'middlename'=>$this->session->userdata('mmiddlename_applyexam'),
									'lastname'=>$this->session->userdata('mlastname_applyexam'),
									'memtype'=>$this->session->userdata('memtype'),
									'timer'=>$this->session->userdata('mtimer_applyexam'),
									'password'=>$this->session->userdata('mpassword_applyexam'));
		//$this->session->set_userdata($user_data);		
		$this->session->unset_userdata($user_data);
		//redirect(base_url().'Cisi/details/'.base64_encode($MerchantOrderNo).'/'.base64_encode($exam_info[0]['exam_code']));
		redirect(base_url().'Cisi/details/'.base64_encode($MerchantOrderNo));
	}
	
	public function sbitransfail()
	{
		die();
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
			$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code');
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
			if($get_user_regnum[0]['exam_code']!='993')
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
			$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.exam_code');
		
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
			//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
			$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'Jw6bOIQGg',$exam_info[0]['exam_code']);
			$this->Emailsending->mailsend($info_arr);
			}
			//Manage Log
			$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
			$this->log_model->logtransaction("sbiepay", $pg_response,$responsedata[2]);		
			}
			//End Of SBICALL Back	
			redirect(base_url().'Cisi/fail/'.base64_encode($MerchantOrderNo));
		}
		else
		{
			die("Please try again...");
		}
	}
	
	
	//Show acknowlodgement to to user after transaction succeess
	public function details($order_no=NULL,$excd=NULL)
	{
		if($order_no!=NULL)
		{
		//payment detail
		/*$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($order_no),'member_regnumber'=>$this->session->userdata('mregnumber_applyexam')));
		
		$today_date=date('Y-m-d');
		$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month');
		$this->db->where('elg_mem_o','Y');	
		$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
		$this->db->where("misc_master.misc_delete",'0');
		$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
		//$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
		
		$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
		$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($excd),'regnumber'=>$this->session->userdata('mregnumber_applyexam')));
		
			
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
		}*/
		$data=array('middle_content'=>'Cisi/exam_applied_success');
		$this->load->view('Cisi/mem_apply_exam_common_view',$data);
		}
		else
		{
			redirect(base_url().'Cisi/login');
		}
	}
	
	//Show acknowlodgement to to user after transaction Failure
	public function fail($order_no=NULL)
	{
		if($order_no!=NULL)
		{
		//payment detail
		/*$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($order_no),'member_regnumber'=>$this->session->userdata('mregnumber_applyexam')));
		if(count($payment_info) <=0)
		{
			redirect(base_url());
		}*/
		$data=array('middle_content'=>'Cisi/exam_applied_fail');
		$this->load->view('Cisi/mem_apply_exam_common_view',$data);
		}
		else
		{
			redirect(base_url().'Cisi/login');
		}
	}
	

		//Show acknowlodgement to to user after transaction Failure
	public function pending($order_no=NULL)
	{
		if($order_no!=NULL)
		{
		//payment detail
		/*$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($order_no),'member_regnumber'=>$this->session->userdata('mregnumber_applyexam')));
		if(count($payment_info) <=0)
		{
			redirect(base_url());
		}*/
		$data=array('middle_content'=>'Cisi/exam_applied_pending');
		$this->load->view('Cisi/mem_apply_exam_common_view',$data);
		}
		else
		{
			redirect(base_url().'Cisi/login');
		}
	}
	
		##------- check eligible user----------##
	public function checkusers($examcode=NULL)
	{
		
		$flag=0;
		if($examcode!=NULL)
		{ 
			 $exam_code = array(993);
		 	 if(in_array($examcode,$exam_code))
			{
				 $this->db->where_in('eligible_master.exam_code', $exam_code);
				 $valid_member_list=$this->master_model->getRecords('eligible_master',array('eligible_period'=>'997'),'member_no');
				 if(count($valid_member_list) > 0)
				 { 
					foreach($valid_member_list as $row)
					{
						$memberlist_arr[]=$row['member_no'];
					}
					 if(in_array($this->session->userdata('mregnumber_applyexam'),$memberlist_arr))
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
		return  $flag; 
	}
}