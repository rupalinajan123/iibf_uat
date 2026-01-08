<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nonmem extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');	
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->model('master_model');		
		$this->load->library('email');
		$this->load->helper('date');
		$this->load->library('email');
		$this->load->model('Emailsending');
		$this->load->model('log_model');
		$this->load->model('chk_session');
		$this->chk_session->Check_mult_session();
		//$this->load->model('chk_session');
	  //	$this->chk_session->chk_member_session();
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	 ##---------default userlogin (prafull)-----------##
	public function index()
	{
		/*$text = 'test Prafull last 123'; //.' and Subject:'.$tapal_subject;
		$url ="http://www.hindit.co.in/API/pushsms.aspx?loginID=T1IIBF&password=supp0rt123&mobile=9096241879&text=".urlencode($text)."&senderid=IIBFNM&route_id=2 &Unicode=1";
		$string = preg_replace('/\s+/', '', $url);
		//$url = "http://sms.speedybulk.com/sendsmsv2.asp?user=etapal&password=etapal123&sender=ETAPAL&text=test&PhoneNumber=919096241879&unicode=1";
		$x = curl_init($string);
		curl_setopt($x, CURLOPT_HEADER, 0);	
		curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);			
		echo $reply = curl_exec($x);
		curl_close($x);
		exit;*/
		if($this->session->userdata('nmregid'))
		{
			redirect(base_url().'NonMember/dashboard/');
		}
		$data=array();
		$data['error']='';$showlink = "no";	$exam_name='';
		if(isset($_POST['btnLogin']))
		{
				$config = array(
				array(
						'field' => 'Username',
						'label' => 'Username',
						'rules' => 'trim|required'
				),
				array(
						'field' => 'Password',
						'label' => 'Password',
						'rules' => 'trim|required',
				),
				array(
						'field' => 'code',
						'label' => 'Code',
						'rules' => 'trim|required|callback_check_captcha_userlogin',
				),
			);
			
			$this->form_validation->set_rules($config);
			
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('pass_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$encpass = $aes->encrypt($this->input->post('Password'));
		
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
					'usrpassword'=>$encpass,
				);
				if ($this->form_validation->run() == TRUE)
				{
					$where="(registrationtype='NM' OR registrationtype='DB')";
					$this->db->where($where);
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0)
					{
							$chklink=$this->master_model->showcarddownloadlink($user_info[0]['regnumber']);
							if($chklink['is_show'] == "yes"){
							$exam_name=$chklink['exam_name'];
							$showlink = "yes";	
						}else{
							$showlink = "no";	
							$exam_name=$chklink['exam_name'];
						}
					}
					if(count($user_info))
					{ 
						 if($user_info[0]['isactive']==1)
						 {
							$mysqltime=date("H:i:s");
							 if($user_info[0]['registrationtype']=='NM')
							 {
									$user_data=array('nmregid'=>$user_info[0]['regid'],
																'nmregnumber'=>$user_info[0]['regnumber'],
																'nmfirstname'=>$user_info[0]['firstname'],
																'nmmiddlename'=>$user_info[0]['middlename'],
																'nmlastname'=>$user_info[0]['lastname'],
																'nmtimer'=>base64_encode($mysqltime),
																'memtype'=>$user_info[0]['registrationtype'],
																'showlink'=>$showlink,
																'exam_name'=>$exam_name,
																'nm_without_pass' => 0,
																'nmpassword'=>base64_encode($this->input->post('Password')));
									$this->session->set_userdata($user_data);
									$sess = $this->session->userdata();
									redirect(base_url().'NonMember/dashboard/');
							  }
							  else if($user_info[0]['registrationtype']=='DB')
							  {
								$user_data=array('dbregid'=>$user_info[0]['regid'],
															'dbregnumber'=>$user_info[0]['regnumber'],
															'dbfirstname'=>$user_info[0]['firstname'],
															'dbmiddlename'=>$user_info[0]['middlename'],
															'dblastname'=>$user_info[0]['lastname'],
															'dbtimer'=>base64_encode($mysqltime),
															'showlink'=>$showlink,
															'exam_name'=>$exam_name,
															'memtype'=>$user_info[0]['registrationtype'],
															'dbpassword'=>base64_encode($this->input->post('Password')));
								$this->session->set_userdata($user_data);
								$sess = $this->session->userdata();
								redirect(base_url().'Dbf/dashboard/');
								 }
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
			$this->session->set_userdata('nonmemlogincaptcha', $cap['word']);*/
			$this->load->model('Captcha_model');                 
			$captcha_img = $this->Captcha_model->generate_captcha_img('nonmemlogincaptcha');
			$data['image'] = $captcha_img;
			$this->load->view('nonmember/front_nonmember_login',$data);

	}
	
	function login_with_otp_nonmem()
	{
		if($this->session->userdata('nmregid'))
		{
			redirect(base_url().'NonMember/dashboard/');
		}

		$data=array();
		$data['error']='';
		$showlink = "no";	
		$exam_name='';

		if (count($_POST) && count($_POST) > 0)
		{    
			$action_flag = '';
			if (isset($_POST['send_otp']) && $_POST['send_otp'] == 'Send OTP')
			{
				$action_flag = 'send_otp';
			}
			else if (isset($_POST['verify_otp']) && $_POST['verify_otp'] == 'Verify OTP')
			{
				$action_flag = 'verify_otp';
			}

			if ($action_flag == 'send_otp')
			{	
				$dataarr=array(
				'regnumber'=> $this->input->post('regnumber')
				);

				$config = array(
				array(
						'field' => 'regnumber',
						'label' => 'Username',
						'rules' => 'trim|required'
					),
					array(
							'field' => 'code',
							'label' => 'Code',
							'rules' => 'trim|required',
							// 'rules' => 'trim|required|callback_check_captcha_userlogin',
					),
				);				
			}
			elseif ($action_flag == 'verify_otp') {
				$dataarr=array(
					'regnumber'=> $this->input->post('regnumber')
				);

				$config = array(
				array(
						'field' => 'regnumber',
						'label' => 'Username',
						'rules' => 'trim|required'
					),
					array(
						'field' => 'input_otp',
						'label' => 'input_otp',
						'rules' => 'trim|required',
					),
				);	
			}
			else
			{
				$dataarr=array(
					'regnumber'=> $this->input->post('regnumber'),
					'usrpassword'=>$encpass,
				);

				$config = array(
				array(
						'field' => 'regnumber',
						'label' => 'Username',
						'rules' => 'trim|required'
					),
					array(
							'field' => 'Password',
							'label' => 'Password',
							'rules' => 'trim|required',
					),
					array(
							'field' => 'code',
							'label' => 'Code',
							'rules' => 'trim|required',
							// 'rules' => 'trim|required|callback_check_captcha_userlogin',
					),
				);
			}

			$this->form_validation->set_rules($config);

			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('pass_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$encpass = $aes->encrypt($this->input->post('Password'));
			 
			if($this->form_validation->run() == TRUE)
			{ 
				$regnumber   = $this->input->post('regnumber');

				$where="(registrationtype='NM' OR registrationtype='DB')";
				$this->db->where($where);
				$user_info=$this->master_model->getRecords('member_registration',$dataarr);

				if(count($user_info) > 0)
				{
					$chklink=$this->master_model->showcarddownloadlink($user_info[0]['regnumber']);
					if($chklink['is_show'] == "yes"){
						$exam_name=$chklink['exam_name'];
						$showlink = "yes";	
					}else{
						$showlink = "no";	
						$exam_name=$chklink['exam_name'];
					}

					if ($action_flag == 'send_otp')
					{	
						$_SESSION['LOGIN_OTP_MEMBERSHIP_NO'] = $regnumber;
						$send_otp = $this->fun_send_otp_sms($user_info);
						// echo "Here <pre>"; print_r($send_otp);exit;
						
						// $this->master_model->send_sms_common_all($user_info[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender'], $exam_info[0]['exam_code']);

						if ($send_otp)
						{
						  $this->session->set_flashdata('success', 'OTP successfully sent on ' . $this->mask_email_mobile('mobile', $user_info[0]['mobile']) . ' & ' . $this->mask_email_mobile('email', $user_info[0]['email']) . '. OTP is valid for 10 minutes.');
						}
						else
						{
						  $this->session->set_flashdata('error', 'Error occurred. Please try again.');
						}
						redirect(site_url('nonmem/login_with_otp_nonmem'));
					}
					else if ($action_flag == 'verify_otp')
					{
						$otp_data = $this->master_model->getRecords('member_login_otp', array('regnumber' => $regnumber, 'is_validate' => '0', 'otp_type' => '1'), 'otp_id, otp, is_validate, otp_expired_on', array('otp_id' => 'DESC'), '', 1);
						$input_otp = $this->input->post('input_otp');

						if (count($otp_data) > 0)
						{
						  if ($otp_data[0]['otp'] != $input_otp)
						  {
						    $this->session->set_flashdata('error', 'Please enter the correct OTP.');
						    redirect(site_url('nonmem/login_with_otp_nonmem'));
						  }
						  else if ($otp_data[0]['otp_expired_on'] < date("Y-m-d H:i:s"))
						  {
						    $this->session->set_flashdata('error', 'The OTP has already expired.');
						    redirect(site_url('nonmem/login_with_otp_nonmem'));
						  }
						  else
						  {
						    $up_data['is_validate'] = 1;
						    $up_data['updated_on']  = date("Y-m-d H:i:s");
						    $this->master_model->updateRecord('member_login_otp', $up_data, array('otp_id' => $otp_data[0]['otp_id']));

						   	$mysqltime=date("H:i:s");
							if($user_info[0]['registrationtype']=='NM')
							{
								$user_data=array(	
													'nmregid'=>$user_info[0]['regid'],
													'nmregnumber'=>$user_info[0]['regnumber'],
													'nmfirstname'=>$user_info[0]['firstname'],
													'nmmiddlename'=>$user_info[0]['middlename'],
													'nmlastname'=>$user_info[0]['lastname'],
													'nmtimer'=>base64_encode($mysqltime),
													'memtype'=>$user_info[0]['registrationtype'],
													'showlink'=>$showlink,
													'exam_name'=>$exam_name,
													'nm_without_pass' => 0,
													'nmpassword'=>base64_encode($this->input->post('Password'))
												);
									$this->session->set_userdata($user_data);
									$sess = $this->session->userdata();
									redirect(base_url().'NonMember/dashboard/');
							  	}
								else if($user_info[0]['registrationtype']=='DB')
								{
									$user_data=array('dbregid'=>$user_info[0]['regid'],
																'dbregnumber'=>$user_info[0]['regnumber'],
																'dbfirstname'=>$user_info[0]['firstname'],
																'dbmiddlename'=>$user_info[0]['middlename'],
																'dblastname'=>$user_info[0]['lastname'],
																'dbtimer'=>base64_encode($mysqltime),
																'showlink'=>$showlink,
																'exam_name'=>$exam_name,
																'memtype'=>$user_info[0]['registrationtype'],
																'dbpassword'=>base64_encode($this->input->post('Password')));
									$this->session->set_userdata($user_data);
									$sess = $this->session->userdata();
									redirect(base_url().'Dbf/dashboard/');
								}
							  }
							}
							else
							{
							  $this->session->set_flashdata('error', 'Please enter the correct OTP.');
							  redirect(site_url('nonmem/login_with_otp_nonmem'));
							}
						}
					}
					if(count($user_info))
					{ 
					 	if($user_info[0]['isactive']==1)
					 	{
							$mysqltime=date("H:i:s");
							if($user_info[0]['registrationtype']=='NM')
							{
								$user_data=array(	
													'nmregid'=>$user_info[0]['regid'],
													'nmregnumber'=>$user_info[0]['regnumber'],
													'nmfirstname'=>$user_info[0]['firstname'],
													'nmmiddlename'=>$user_info[0]['middlename'],
													'nmlastname'=>$user_info[0]['lastname'],
													'nmtimer'=>base64_encode($mysqltime),
													'memtype'=>$user_info[0]['registrationtype'],
													'showlink'=>$showlink,
													'exam_name'=>$exam_name,
													'nm_without_pass' => 0,
													'nmpassword'=>base64_encode($this->input->post('Password'))
												);
								$this->session->set_userdata($user_data);
								$sess = $this->session->userdata();
								redirect(base_url().'NonMember/dashboard/');
							  }
							  else if($user_info[0]['registrationtype']=='DB')
							  {
								$user_data=array('dbregid'=>$user_info[0]['regid'],
															'dbregnumber'=>$user_info[0]['regnumber'],
															'dbfirstname'=>$user_info[0]['firstname'],
															'dbmiddlename'=>$user_info[0]['middlename'],
															'dblastname'=>$user_info[0]['lastname'],
															'dbtimer'=>base64_encode($mysqltime),
															'showlink'=>$showlink,
															'exam_name'=>$exam_name,
															'memtype'=>$user_info[0]['registrationtype'],
															'dbpassword'=>base64_encode($this->input->post('Password')));
								$this->session->set_userdata($user_data);
								$sess = $this->session->userdata();
								redirect(base_url().'Dbf/dashboard/');
							}
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
		

			$session_regnumber = $_SESSION['LOGIN_OTP_MEMBERSHIP_NO'];

		    $member_data = $this->get_member_data_login_with_otp($session_regnumber);

		    $show_otp_input_flag = 0;
		    if (count($member_data) > 0)
		    {
		      $regid = $member_data[0]['regid'];
		      $session_regnumber = $_SESSION['LOGIN_OTP_MEMBERSHIP_NO'] = $regnumber = $member_data[0]['regnumber'];
		      $email_id = $member_data[0]['email'];
		      $mobile_no = $member_data[0]['mobile'];

		      $show_otp_input_flag = 1;
		      $data['mask_email'] = $this->mask_email_mobile('email', $email_id);
		      $data['mask_mobile'] = $this->mask_email_mobile('mobile', $mobile_no);

		      $get_otp_record = $this->master_model->getRecords('member_login_otp', array('regnumber' => $regnumber, 'otp_type'=>'1'), 'otp_id, otp_expired_on, created_on', array('otp_id' => 'DESC'), '', 1);

		      if (count($get_otp_record) > 0)
		      {
		        $get_otp_record[0]['created_on'];
		        $data['resend_time_sec'] = $resend_time_sec = $this->check_time($get_otp_record[0]['created_on']);
		      }
		    }	

		    $data['session_regnumber'] = $session_regnumber;
    		$data['member_data'] = $member_data;
			$this->load->model('Captcha_model');                 
			$captcha_img = $this->Captcha_model->generate_captcha_img('LOGIN_WITH_OTP_CAPTCHA');
			$data['image'] = $captcha_img;
			$data['show_otp_input_flag'] = $show_otp_input_flag;
			$this->load->view('nonmember/front_nonmember_login_otp',$data);
	}

	function fun_send_otp_sms($member_data = array())
	{
	    if (count($member_data) > 0)
	    { 
			$regid 		 = $member_data[0]['regid'];
			$regnumber   = $member_data[0]['regnumber'];
			$email_id    = $member_data[0]['email'];
			$mobile_no   = $member_data[0]['mobile'];
			$otp 		 = $this->generate_otp();
			$otp_sent_on = date('Y-m-d H:i:s');
			$otp_expired_on = date('Y-m-d H:i:s', strtotime('+ 10 minutes', strtotime($otp_sent_on)));

			$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'member_login_with_otp'));
			// print_r($emailerstr); exit;
			$email_text = $emailerstr[0]['emailer_text'];
			$email_text = str_replace('#CANDIDATENAME#', $member_data[0]['firstname'] . " " . $member_data[0]['lastname'], $email_text);
			$email_text = str_replace('#OTP#', $otp, $email_text);

			$sms_text = $emailerstr[0]['sms_text'];
			$sms_text = str_replace('#OTP#', $otp, $sms_text);

			// $otp_mail_arr['to'] = 'Gaurav.Shewale@esds.co.in'; //$email_id;
			$otp_mail_arr['to'] = $email_id;
			$otp_mail_arr['subject'] = $emailerstr[0]['subject'];
			$otp_mail_arr['message'] = $email_text;
			$email_response = $this->Emailsending->mailsend($otp_mail_arr);
			// echo $mobile_no.$sms_text.$emailerstr[0]['sms_template_id'].$emailerstr[0]['sms_sender']; exit;
			$sms_response = $this->master_model->send_sms_common_all($mobile_no, $sms_text, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']);
			
			if ($email_response)
			{
				$add_data['regid'] = $regid;
				$add_data['regnumber'] = $regnumber;
				$add_data['email'] = $email_id;
				$add_data['mobile'] = $mobile_no;
				$add_data['otp'] = $otp;
				$add_data['is_validate'] = '0';
				$add_data['otp_type'] = '1';
				$add_data['otp_expired_on'] = $otp_expired_on;
				$add_data['created_on'] = $otp_sent_on;
				$this->db->insert('member_login_otp ', $add_data);

				return true;
			}
			else
			{
				return false;
			}
	    }
	    else
	    {
	      return false;
	    }
	}

	function get_member_data_login_with_otp($regnumber = '')
	{
		$this->db->where("(registrationtype='NM' OR registrationtype='DB')");
		return $this->master_model->getRecords('member_registration', array('isactive' => '1', 'regnumber' => $regnumber), 'regid, reg_no, regnumber, namesub, firstname, middlename, lastname, dateofbirth, gender, email, registrationtype, mobile, registration_status, createdon, kyc_status', array('regid' => 'DESC'));
	}

	public function generate_otp()
	{
		//return '123456';
		return rand(100000, 999999);
	} //END : GENERATE OTP FUNCTIONALITY

	public function mask_email_mobile($type = '', $str = '') //$type = 'mobile' / 'email' : 
	{
		$show_start = 0;
		$show_end = 3;

		if ($type == 'email')
		{
		  $show_start = 1;

		  $explode_email_arr = explode("@", $str);
		  $show_end = strlen($explode_email_arr[1]) + 2;
		}

		return substr($str, 0, $show_start) . str_repeat('*', (strlen($str) - ($show_start + $show_end))) . substr($str, '-' . $show_end);
	}


	function forgot_membership_no()
	{
		/*if($this->session->userdata('regid'))
		{
		  redirect(base_url() . 'home/dashboard/');
		}*/

		if($this->session->userdata('nmregid'))
		{
			redirect(base_url().'NonMember/dashboard/');
		}

		if (count($_POST) && count($_POST) > 0)
		{
	  		$action_flag = '';
			if (isset($_POST['send_otp']) && $_POST['send_otp'] == 'Send OTP')
			{
				$action_flag = 'send_otp';
			}
	  		else if (isset($_POST['verify_otp']) && $_POST['verify_otp'] == 'Verify OTP')
	  		{
	    		$action_flag = 'verify_otp';
	  		}

	  		$this->form_validation->set_rules('email_mobile', 'Email Id / Mobile Number', 'trim|required|callback_validation_email_mobile_forgot_membership_no|xss_clean', array('required' => 'Please enter the %s'));
	  
	  		if ($action_flag == 'send_otp')
	  		{
	    		$this->form_validation->set_rules('code', 'characters you see in the picture', 'trim|required|callback_validation_check_captcha_forgot_membership_no|xss_clean', array('required' => 'Please enter the %s'));
	  		}

	  		if ($action_flag == 'verify_otp')
	  		{
	    		$this->form_validation->set_rules('input_otp', 'OTP', 'trim|required|callback_validation_validate_otp_forgot[0]|xss_clean', array('required' => 'Please enter the %s'));
	  		}

	  		if ($this->form_validation->run() == TRUE)
	  		{
	    		//echo $action_flag;
	    		$email_mobile = $this->input->post('email_mobile');
			    $member_data  = $this->member_data_forgot_membership_no($email_mobile);
			    // $member_data = $this->get_forgot_member_data_with_otp($email_mobile);
			    //print_r($member_data);exit;

			    if (count($member_data) > 0)
			    {
			      	if ($action_flag == 'send_otp')
			      	{
				        $_SESSION['FORGOT_OTP_MEMBERSHIP_NO'] = $email_mobile;
				        $send_otp = $this->fun_send_otp_sms_forgot($member_data);
				        // print_r($send_otp);exit;
				        if ($send_otp)
				        {
							// echo "test";
							$this->session->set_flashdata('success', 'OTP successfully sent on ' . $this->mask_email_mobile('mobile', $member_data[0]['mobile']) . ' & ' . $this->mask_email_mobile('email', $member_data[0]['email']) . '. OTP is valid for 10 minutes.');
			        	}
			        	else
			        	{
			          		$this->session->set_flashdata('error', 'Error occurred. Please try again.');
			        	}
			        	redirect(site_url('nonmem/forgot_membership_no'));
			      	}
			      	else if ($action_flag == 'verify_otp')
			      	{
			        	$otp_data = $this->master_model->getRecords('member_login_otp', array('email' => $member_data[0]['email'], 'mobile' => $member_data[0]['mobile'], 'otp_type' => '2'), 'otp_id, otp, is_validate, otp_expired_on', array('otp_id' => 'DESC'), '', 1);
			        	// print_r($otp_data);exit;
			        	$input_otp = $this->input->post('input_otp');

			        	if (count($otp_data) > 0)
			        	{
			          		if ($otp_data[0]['otp'] != $input_otp)
			          		{
			            		$this->session->set_flashdata('error', 'Please enter the correct OTP.');
			            		redirect(site_url('nonmem/forgot_membership_no'));
			          		}
			          		else if ($otp_data[0]['otp_expired_on'] < date("Y-m-d H:i:s"))
			          		{
					            $this->session->set_flashdata('error', 'The OTP has already expired.');
					            redirect(site_url('nonmem/forgot_membership_no'));
			          		}
				          	else
				          	{
					            $up_data['is_validate'] = 1;
					            $up_data['updated_on'] = date("Y-m-d H:i:s");
					            $this->master_model->updateRecord('member_login_otp', $up_data, array('otp_id' => $otp_data[0]['otp_id']));

					            $_SESSION['FORGOT_OTP_MEMBERSHIP_NO'] = '';

			           
					            //send email to user for its details
			             		$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'forgot_membership_no_details'));

			              		$email_text = $emailerstr[0]['emailer_text'];
			              		$email_text = str_replace('#CANDIDATENAME#', $member_data[0]['firstname'] . " " . $member_data[0]['lastname'], $email_text);
			              		$email_text = str_replace('#MEMBERSHIPNO#', $member_data[0]['regnumber'], $email_text);
			              		$email_text = str_replace('#EMAILID#', $member_data[0]['email'], $email_text);
			              		$email_text = str_replace('#MOBILENO#', $member_data[0]['mobile'], $email_text);
			              		$email_text = str_replace('#BIRTHDATE#', $member_data[0]['dateofbirth'], $email_text);
			              		$email_text = str_replace('#GENDER#', $member_data[0]['gender'], $email_text);
								
			              		$sms_text = $emailerstr[0]['sms_text'];
								$sms_text = str_replace('#CANDIDATENAME#', $member_data[0]['firstname'] . " " . $member_data[0]['lastname'], $sms_text);
								$sms_text = str_replace('#MEMBERSHIPNO#', $member_data[0]['regnumber'], $sms_text);
								$sms_text = str_replace('#BIRTHDATE#', $member_data[0]['dateofbirth'], $sms_text);

			              		// $otp_mail_arr['to'] = 'Gaurav.Shewale@esds.co.in'; //$email_id;
			              		$otp_mail_arr['to'] 	 = $email_id;
			              		$otp_mail_arr['subject'] = $emailerstr[0]['subject'];
			              		$otp_mail_arr['message'] = $email_text;
			              		$email_response = $this->Emailsending->mailsend($otp_mail_arr);
			            		//redirect to thank you page.
			              		$sms_response = $this->master_model->send_sms_common_all($member_data[0]['mobile'], $sms_text, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']);
			              		
			             		redirect(site_url('nonmem/membership_details_success'));exit;
			             		//end of send email to user for its details
			          		}
			        	}
			        	else
			        	{
			          		$this->session->set_flashdata('error', 'Please enter the correct OTP.');
			          		redirect(site_url('nonmem/forgot_membership_no'));
			        	}
			      	}
			    }
			    else
			    {
			      	$this->session->set_flashdata('error','Error occurred. Please try again.');
			      	redirect(site_url('nonmem/forgot_membership_no'));
			    }
	  		}
		}

		$session_email_mobile 	= $_SESSION['FORGOT_OTP_MEMBERSHIP_NO'];
		$member_data 			= $this->member_data_forgot_membership_no($session_email_mobile);   
		$show_otp_input_flag 	= 0;
		
		if (count($member_data) > 0)
		{
			$regid = $member_data[0]['regid'];
			$session_email_mobile = $_SESSION['FORGOT_OTP_MEMBERSHIP_NO'];// = $email_mobile = $member_data[0]['email_mobile'];
			//print_r($session_email_mobile);exit;
			$email_id = $member_data[0]['email'];
			$mobile_no = $member_data[0]['mobile'];

			$show_otp_input_flag = 1;
			$data['mask_email'] = $this->mask_email_mobile('email', $email_id);
			$data['mask_mobile'] = $this->mask_email_mobile('mobile', $mobile_no);

			$get_otp_record = $this->master_model->getRecords('member_login_otp', array('email' => $email_id, 'mobile' => $mobile_no, 'otp_type'=>'2'), 'otp_id, otp_expired_on, created_on', array('otp_id' => 'DESC'), '', 1);
			//print_r($get_otp_record);exit;
		  	if (count($get_otp_record) > 0)
		  	{
			    $get_otp_record[0]['created_on'];
			    $data['resend_time_sec'] = $resend_time_sec = $this->check_time($get_otp_record[0]['created_on']);
		  	}
		}

		$data['session_email_mobile'] = $session_email_mobile;
		$data['member_data'] = $member_data;
		$data['show_otp_input_flag'] = $show_otp_input_flag;

		$this->load->model('Captcha_model');
		$data['image'] = $this->Captcha_model->generate_captcha_img('FORGOT_MEMBERSHIP_NO_CAPTCHA');
		$this->load->view('nonmember/forgot_membership_no', $data);
	}

	public function validation_email_mobile_forgot_membership_no($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
	{
	    $return_val_ajax = 'false';
	    if (isset($_POST) && $_POST['email_mobile'] != "")
	    {
	      if ($type == '1')
	      {
	        $email_mobile = $this->input->post('email_mobile');
	      }
	      else if ($type == '0')
	      {
	        $email_mobile = $str;
	      }

	      $user_data = $this->member_data_forgot_membership_no($email_mobile);

	      if (count($user_data) > 0)
	      {
	        $return_val_ajax = 'true';
	      }
	    }

	    if ($type == '1')
	    {
	      echo $return_val_ajax;
	    }
	    else if ($type == '0')
	    {
	      	if ($return_val_ajax == 'true')
	      	{
	        	return TRUE;
	      	}
	      	else
	      	{
	        	$this->form_validation->set_message('validation_email_mobile_forgot_membership_no', 'Please enter the valid Email Id / Mobile Number');
	        	return false;
	      	}
	    }
	}

	function member_data_forgot_membership_no($email_mobile = '', $reg_id = '', $regnumber = '')
	{
	    if ($email_mobile != '' || ($reg_id != "" && $regnumber != ""))
	    {
	      if ($email_mobile != '')
	      {
	        $this->db->where("(email='" . $email_mobile . "' OR mobile='" . $email_mobile . "')");
	      }
	      else if ($reg_id != '' && $regnumber != '')
	      {
	        $this->db->where("regid='" . $reg_id . "' AND regnumber='" . $regnumber . "'");
	      }

	      return $this->master_model->getRecords('member_registration', array("isactive" => '1'), 'regid, reg_no, regnumber, namesub, firstname, middlename, lastname, dateofbirth, gender, email, registrationtype, mobile, registration_status, createdon, kyc_status, usrpassword', array('regid' => 'DESC'));
	      //echo $this->db->last_query();
	    }
	    else
	    {
	      return array();
	    }
	}

	public function validation_check_captcha_forgot_membership_no($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
	{
	    $return_val_ajax = 'false';
	    if ( isset($_POST) && $_POST['code'] != "" )
	    {
	      if ($type == '1')
	      {
	        $captcha = $this->input->post('code');
	      }
	      else if ($type == '0')
	      {
	        $captcha = $str;
	      }

	      $session_captcha = $this->session->userdata('FORGOT_MEMBERSHIP_NO_CAPTCHA');
	      if ($captcha == $session_captcha)
	      {
	        $return_val_ajax = 'true';
	      }
	    }

	    if ($type == '1')
	    {
	      echo $return_val_ajax;
	    }
	    else if ($type == '0')
	    {
	      if ($return_val_ajax == 'true')
	      {
	        return TRUE;
	      }
	      else
	      {
	        $this->form_validation->set_message('validation_check_captcha_forgot_membership_no', 'Please enter the exact characters you see in the picture');
	        return false;
	      }
	    }
	}

	public function validation_validate_otp_forgot($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
	{
	    // $return_val_ajax = 'false';
	    $result['flag'] = $flag = "error";
	    $response = 'Please enter the correct OTP';
	    if (isset($_POST) && $_POST['input_otp'] != "" && $_POST['email_mobile'] != "")
	    {
	      if ($type == '1')
	      {
	        $input_otp = $this->input->post('input_otp');
	      }
	      else if ($type == '0')
	      {
	        $input_otp = $str;
	      }

	      $email_mobile = $this->input->post('email_mobile');

	      $this->db->where(" (email = '".$email_mobile."' OR mobile = '".$email_mobile."') "); 
	      $otp_data = $this->master_model->getRecords('member_login_otp', array('otp_type' => '2'), 'otp_id, otp, is_validate, otp_expired_on', array('otp_id' => 'DESC'), '', 1);      
	      if (count($otp_data) > 0)
	      {
	        if ($otp_data[0]['otp'] != $input_otp)
	        {
	          $result['response'] = $response = "Please enter the correct OTP.";
	        }
	        else if ($otp_data[0]['otp_expired_on'] < date("Y-m-d H:i:s"))
	        {
	          $result['response'] = $response = "The OTP has already expired.";
	        }
	        else
	        {
	          $result['flag'] = $flag = "success";
	          $result['response'] = $response = '';
	        }
	      }
	      else
	      {
	        $result['response'] = $response = "Please enter the correct OTP.";
	      }
	    }

	    if ($type == '1')
	    {/*  echo $return_val_ajax; */
	      echo json_encode($result);
	    }
	    else if ($type == '0')
	    {
	      /* echo $flag;
	      print_r($response);
	      exit; */
	      if ($flag == 'success')
	      {
	        return TRUE;
	      }
	      else
	      {
	        $this->form_validation->set_message('validation_validate_otp_forgot', $response);
	        return false;
	      }
	    }
	}

	function fun_send_otp_sms_forgot($member_data = array())
	{
	    if (count($member_data) > 0)
	    {
	      $regid = $member_data[0]['regid'];
	      $regnumber = $member_data[0]['regnumber'];
	      $email_id = $member_data[0]['email'];
	      $mobile_no = $member_data[0]['mobile'];
	      $otp = $this->generate_otp();
	      //print_r($otp);
	      $otp_sent_on = date('Y-m-d H:i:s');
	      $otp_expired_on = date('Y-m-d H:i:s', strtotime('+ 10 minutes', strtotime($otp_sent_on)));

	      $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'forgot_membership_no_otp'));
	      //print_r($emailerstr);exit;

	      $email_text = $emailerstr[0]['emailer_text'];
	      $email_text = str_replace('#CANDIDATENAME#', $member_data[0]['firstname'] . " " . $member_data[0]['lastname'], $email_text);
	      $email_text = str_replace('#OTP#', $otp, $email_text);

	      $sms_text = $emailerstr[0]['sms_text'];
		  $sms_text = str_replace('#OTP#', $otp, $sms_text);

	      // $otp_mail_arr['to'] = 'Gaurav.Shewale@esds.co.in'; //$email_id;
	      $otp_mail_arr['to'] = $email_id;
	      $otp_mail_arr['subject'] = $emailerstr[0]['subject'];
	      $otp_mail_arr['message'] = $email_text;
	      $email_response = $this->Emailsending->mailsend($otp_mail_arr);

	      $sms_response = $this->master_model->send_sms_common_all($mobile_no, $sms_text, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']);

	      //print_r($email_response);exit;
	      if ($email_response)
	      {
	        $add_data['regid'] = $regid;
	        $add_data['regnumber'] = $regnumber;
	        $add_data['email'] = $email_id;
	        $add_data['mobile'] = $mobile_no;
	        $add_data['otp'] = $otp;
	        $add_data['is_validate'] = '';
	        $add_data['otp_type'] = '2';
	        $add_data['otp_expired_on'] = $otp_expired_on;
	        $add_data['created_on'] = $otp_sent_on;
	        // print_r($add_data);exit;
	        $this->db->insert('member_login_otp ', $add_data);
	        //print_r($p);exit;

	        return true;
	      }
	      else
	      {
	        return false;
	      }
	    }
	    else
	    {
	      return false;
	    }
	}

	function membership_details_success()
    {
        //   $otp_data = $this->master_model->getRecords('member_login_otp', array('email' => $member_data[0]['email'], 'mobile' => $member_data[0]['mobile'], 'otp_type' => '2'), 'otp_id, otp, is_validate, otp_expired_on', array('otp_id' => 'DESC'), '', 1);
        //     print_r($otp_data);exit;
        //$this->db->where("(email='" . $email_mobile . "' OR mobile='" . $email_mobile . "')");
        // $email_id = $member_data[0]['email'];
        // $data['member_data'] = $this->master_model->getRecords('member_registration', array("isactive" => '1','email' => $email_id, 'otp_type'=>'2'), , array('regid' => 'DESC'));  
        // print_r($data);exit;
        $this->load->view('nonmember/thankyou_member');
    }

    public function set_session_member_with_otp_ajax()
	{
	    $result['flag'] = "error";

	    if (isset($_POST['email_mobile']))
	    {
	      $membership_no = $this->input->post('email_mobile');
	      $_SESSION['FORGOT_OTP_MEMBERSHIP_NO'] = $membership_no;

	      $member_data = $this->member_data_forgot_membership_no($membership_no);
	      if (count($member_data) > 0)
	      {
	        $this->fun_send_otp_sms_forgot($member_data);
	        $result['flag'] = "success";
	        $result['resend_time_sec'] = $this->check_time(date('Y-m-d H:i:s'));
	      }
	    }

	    echo json_encode($result);
	}

	public function reset_form_forgot_ajax()
  	{
	    $_SESSION['FORGOT_OTP_MEMBERSHIP_NO'] = '';
	    $result['flag'] = "success";
	    echo json_encode($result);
  	}

  	public function generate_captcha_ajax_forgot_membership_no()
  	{
	    $this->load->model('Captcha_model');
	    echo $captcha_img = $this->Captcha_model->generate_captcha_img('FORGOT_MEMBERSHIP_NO_CAPTCHA');
  	}

	function check_time($time)
	{
	    $timeMobileFirst  = strtotime($time);
	    $currentTimeInSec = strtotime(date('Y-m-d H:i:s'));

	    $remainingTimeMobile = 0;
	    if ($timeMobileFirst <= $currentTimeInSec)
	    {
	      $diffTimeMobile =  $currentTimeInSec - $timeMobileFirst;

	      if ($diffTimeMobile < $this->otptime)
	      {
	        $remainingTimeMobile = $this->otptime - $diffTimeMobile;
	      }
	    }
	    return $remainingTimeMobile;
	} //END : TO DISPLAY THE REMAINING TIME FOR RESEND OTP

	public function set_session_login_with_otp_ajax()
	{
	    $result['flag'] = "error";

	    if (isset($_POST['membership_no']))
	    {
	      $membership_no = $this->input->post('membership_no');
	      $_SESSION['LOGIN_OTP_MEMBERSHIP_NO'] = $membership_no;

	      $member_data = $this->get_member_data_login_with_otp($membership_no);
	      if (count($member_data) > 0)
	      {
	        $this->fun_send_otp_sms($member_data);
	        $result['flag'] = "success";
	        $result['resend_time_sec'] = $this->check_time(date('Y-m-d H:i:s'));
	      }
	    }

	    echo json_encode($result);
	}

	##---------check captcha userlogin (vrushali)-----------##
	public function check_captcha_userlogin($code) 
	{
		if(!isset($this->session->nonmemlogincaptcha) && empty($this->session->nonmemlogincaptcha))
		{
			return false;
		}
		
		if($code == '' || $this->session->nonmemlogincaptcha != $code )
		{
			$this->form_validation->set_message('check_captcha_userlogin', 'Invalid %s.'); 
			$this->session->set_userdata("nonmemlogincaptcha", rand(1,100000));
			return false;
		}
		if($this->session->userlogincaptcha == $code)
		{
			$this->session->set_userdata('nonmemlogincaptcha','');
			$this->session->unset_userdata("nonmemlogincaptcha");
			return true;
		}
	}
	
	// reload captcha functionality
	public function generatecaptchaajax()
	{
		/*$this->load->helper('captcha');
		$this->session->unset_userdata("nonmemlogincaptcha");
		$this->session->set_userdata("nonmemlogincaptcha", rand(1, 100000));
		$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data = $cap['image'];
		$_SESSION["nonmemlogincaptcha"] = $cap['word'];
		echo $data;*/
		$this->load->model('Captcha_model');                 
		echo $captcha_img = $this->Captcha_model->generate_captcha_img('nonmemlogincaptcha');
	}

	##---------forget password (prafull)-----------##
	public function forgotpassword()
	{
		$data['page_title']='Forget Password';
		$data['pass_error']=$data['error']='';
		if(isset($_POST['btn_forget_pass']))
		{
			$this->form_validation->set_rules('non_memno','Registration No.','trim|required|xss_clean');
			if($this->form_validation->run())
			{
				$non_memno=$this->input->post('non_memno');
				$this->db->where('isactive','1');
				$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$non_memno));
				if(count($result)>0)
				{
				//generate random password
				$password=$this->generate_random_password();
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				 $key = $this->config->item('pass_key');
				 $aes = new CryptAES();
				 $aes->set_key(base64_decode($key));
				 $aes->require_pkcs5();
				 $encPass = $aes->encrypt($password);
		
				// update a password in db
				//$query=$this->master_model->updateRecord('member_registration',array('usrpassword'=>$encPass,'editedon'=>date('Y-m-d H:i:s'),'editedby'=>'Candidate'),array('regid'=>$result[0]['regid']));
				$query=$this->master_model->updateRecord('member_registration',array('usrpassword'=>$encPass),array('regid'=>$result[0]['regid']));
				$log_arr=array('regnumber'=>$non_memno,'usrpassword'=>$encPass,'editedon'=>date('Y-m-d H:i:s'),'editedby'=>'Candidate');
				logactivity($log_title ="Forgrt pass Non member ", $log_message = serialize($log_arr));
				if($query)
				{
							$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
							$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'member_forgetpass'));
							$newstring1 = str_replace("#application_num#", "".$result[0]['regnumber']."",  $emailerstr[0]['emailer_text']);
							$newstring2= str_replace("#password#", "".$password."",  $newstring1);
							$newstring3= str_replace("#username#", "".$userfinalstrname."",  $newstring2);
							$final_str= str_replace("#url#", "".base_url()."",  $newstring3);
							
							$info_arr=array(
														'to'=>$result[0]['email'],
														'from'=>$emailerstr[0]['from'],
														'subject'=>$emailerstr[0]['subject'],' '.$result[0]['regnumber'],
														'message'=>$final_str
													);
							
							// $disp_email = $this->obfuscate_email($result[0]['email']);
							
							$sms_string = str_replace("#EMAIL_ID#", $result[0]['email'], $emailerstr[0]['sms_text']);
							$sms_string = str_replace("#APPLICATION_NUM#", $result[0]['regnumber'], $sms_string);
							$sms_string = str_replace("#PASSWORD#", $password,  $sms_string);
							
							$sms_response = $this->master_model->send_sms_common_all($result[0]['mobile'], $sms_string, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']);
							
							if($this->Emailsending->mailsend($info_arr))
							{
								//$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
								 redirect(base_url().'Nonmem/forgetack/');
							}
							else
							{
								$this->session->set_flashdata('error','Error while sending email !!');
								 redirect(base_url().'Nonmem/');
							}					
						}
					}
				else
				{
					 $this->session->set_flashdata('error_message','Invalid Membership/Registration No!');
					 redirect(base_url().'Nonmem/forgotpassword/');
				}
			}
		}
		$this->load->view('nonmember/nonmember_forgetpass',$data);
	}
	
	//### forget pass acknowledgment
	 public function forgetack()
	 {
		$this->load->view('nonmember/foergetpass_ack');	
	}
	
	
	 ##---------Genereate random password function (prafull)-----------##
	function generate_random_password($length = 8, $level = 2) // function to generate new password
	{
		list($usec, $sec) = explode(' ', microtime());
		srand((float) $sec + ((float) $usec * 100000));
		$validchars[1] = "0123456789abcdfghjkmnpqrstvwxyz";
		$validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$validchars[3] = "0123456789_!@#*()-=+abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#*()-=+";
		$password = "";
		$counter = 0;
		while ($counter < $length) {
		$actChar = substr($validchars[$level], rand(0, strlen($validchars[$level]) - 1), 1);
		if (!strstr($password, $actChar)) {
			$password .= $actChar;
				$counter++;
			}
		}
		return $password;
	}
	
	
}
