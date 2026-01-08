<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Login_sm extends CI_Controller {
		public function __construct()
		{
			parent::__construct();
			$this->load->library('upload');	
			$this->load->helper('upload_helper');
			$this->load->helper('master_helper');
			$this->load->model('master_model');		
			$this->load->library('email');
			$this->load->model('Emailsending');
			$this->load->model('chk_session');
			//$this->load->library('OS_BR');
			if($this->session->userdata('memberdata'))
			{
				$this->session->unset_userdata('memberdata');
			}
			
			/*ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL); */
		}
		
		##---------default userlogin (prafull)-----------##
		public function index()
		{	
			if(!isset($_COOKIE["instruction"]) || $_COOKIE["instruction"]==0)
			{
				redirect('https://iibf.esdsconnect.com/instructoinLogin.php');
			}
			//$this->chk_session->checklogin();
			
			if($this->session->userdata('regid'))
			{
				redirect(base_url().'home/dashboard/');
			}
			$data=array();
			$data['error']='';
			if(isset($_POST['submit']))
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
				/*	array(
					'field' => 'code',
					'label' => 'Code',
					'rules' => 'trim|required|callback_check_captcha_userlogin',
				),*/
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
					$this->db->select('registrationtype,regid,regnumber,firstname,middlename,lastname,createdon,registrationtype,isactive,usrpassword');
					$where="(registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
					$this->db->where($where);
					$this->db->where('isactive','1');
					
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0)
					{ 
						if($user_info[0]['isactive']==1)
					  {
							$showlink = "no";	
							$mysqltime=date("H:i:s");
							$user_data=array('regid'=>$user_info[0]['regid'],
							'regnumber'=>$user_info[0]['regnumber'],
							'firstname'=>$user_info[0]['firstname'],
							'middlename'=>$user_info[0]['middlename'],
							'lastname'=>$user_info[0]['lastname'],
							'memtype'=>$user_info[0]['registrationtype'],
							'timer'=>base64_encode($mysqltime),
							'showlink'=>$showlink,
							'exam_name'=>$exam_name,
							'password'=>base64_encode($this->input->post('Password')));
							$this->session->set_userdata($user_data);
							redirect(base_url().'home/dashboard/');	
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
			$data['code']=$cap['word'];*/
			$data['image'] ='' ;
			$data['code']='';
			//$this->session->set_userdata('userlogincaptcha', $cap['word']);
			$this->load->view('login',$data);
			
		}
		
		public function instruction()
		{	
			$data=array();
			$data['error']='';
			if(isset($_POST['submit']))
			{
				$config = array(
				array(
				'field' => 'code',
				'label' => 'Code',
				'rules' => 'trim|required|callback_check_captcha_userlogin',
				),
				);
				if ($this->form_validation->run() == TRUE)
				{
					redirect(base_url());
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
			$this->session->set_userdata('userlogincaptcha', $cap['word']);
			$this->load->view('instructoinLogin',$data);
		}
		
		##---------check captcha userlogin (prafull)-----------##
		public function check_captcha_userlogin($code) 
		{
			if(!isset($this->session->userlogincaptcha) && empty($this->session->userlogincaptcha))
			{
				redirect(base_url().'login/');
			}
			
			if($code == '' || $this->session->userlogincaptcha != $code )
			{
				$this->form_validation->set_message('check_captcha_userlogin', 'Invalid %s.'); 
				$this->session->set_userdata("userlogincaptcha", rand(1,100000));
				return false;
			}
			if($this->session->userlogincaptcha == $code)
			{
				$this->session->set_userdata('userlogincaptcha','');
				$this->session->unset_userdata("userlogincaptcha");
				return true;
			}
		}
		
		
		//##---- reload captcha functionality
		public function generatecaptchaajax()
		{
			$this->load->helper('captcha');
			$this->session->unset_userdata("userlogincaptcha");
			$this->session->set_userdata("userlogincaptcha", rand(1, 100000));
			$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',
			);
			$cap = create_captcha($vals);
			$data = $cap['image'];
			$_SESSION["userlogincaptcha"] = $cap['word'];
			echo $data;
		}
		
		
		##---------forget password (prafull)-----------##
		public function forgotpassword() 
		{
			$data['page_title']='Forget Password';
			$data['pass_error']=$data['error']='';
			if(isset($_POST['btn_forget_pass']))
			{
				$this->form_validation->set_rules('memno','Membership No.','trim|required|xss_clean');
				
				if($this->form_validation->run())
				{
					$memno=$this->input->post('memno');
					$this->db->select('regnumber,firstname,middlename,lastname,regid,email,mobile,usrpassword');
					$this->db->where('isactive','1');
					$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$memno));
					//echo $this->db->last_query(); exit;
					if(count($result)>0)
					{
						//generate random password
						//$password=$this->generate_random_password();
						include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
						$key = $this->config->item('pass_key');
						$aes = new CryptAES();
						$aes->set_key(base64_decode($key));
						$aes->require_pkcs5();
						//$encPass = $aes->encrypt($password);
						$password = $aes->decrypt($result[0]['usrpassword']); 
						//$query=$this->master_model->updateRecord('member_registration',array('usrpassword'=>$encPass),array('regid'=>$result[0]['regid']));
						//$log_arr=array('regnumber'=>$memno,'usrpassword'=>$encPass,'editedon'=>date('Y-m-d H:i:s'),'editedby'=>'Candidate');
						//logactivity($log_title ="Forget pass Member ", $log_message = serialize($log_arr));
						//if($query)
						{
							$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
							$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'member_forgetpass'));
							$newstring1 = str_replace("#application_num#", "".$result[0]['regnumber']."",  $emailerstr[0]['emailer_text']);
							$newstring2= str_replace("#password#", "".$password."",  $newstring1);
							$newstring3= str_replace("#username#", "".$userfinalstrname."",  $newstring2);
							$final_str= str_replace("#url#", "".base_url()."",  $newstring3);
							
							/******** START : SEND SMS CODE ***********/
							if($result[0]['mobile'] != "")
							{
								$disp_email = $this->obfuscate_email($result[0]['email']);
								$sms_string = str_replace("#EMAIL_ID#", $disp_email, $emailerstr[0]['sms_text']);
								$sms_string = str_replace("#APPLICATION_NUM#", $result[0]['regnumber'], $sms_string);
								$sms_string = str_replace("#PASSWORD#", $password,  $sms_string);	
								
								/* echo $sms_string; exit; */
								$this->master_model->send_sms('9096241879', $sms_string);	/* $result[0]['mobile'] */
								$this->master_model->send_sms('7588096918', $sms_string);	/* $result[0]['mobile'] */								
							}
							
							/******** END : SEND EMAIL CODE ***********/
							$info_arr=array(
							'to'=>'Prafull.Tupe@esds.co.in', /* $result[0]['email'] ,*/
							'from'=>$emailerstr[0]['from'],
							'subject'=>$emailerstr[0]['subject'],
							'message'=>$final_str
							);
							
							if($this->Emailsending->mailsend($info_arr))
							{
								//$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
								redirect(base_url().'login/forgetack/');
							}
							else
							{
								$this->session->set_flashdata('error','Error while sending email !!');
								redirect(base_url().'login/mylogin');
							}					
						}
					}
					else
					{
						$this->session->set_flashdata('error_message','Invalid Membership/Registration No!');
						redirect(base_url().'login/forgotpassword/');
					}
				}
			}
			$this->load->view('forgetpass',$data);
		}
		
		function obfuscate_email($email)
		{
			$extension = explode("@",$email);
			$name = implode('@', array_slice($extension, 0, count($extension)-1));
			$len = strlen($name); 
			$start = $len - 2;
			return str_repeat('*', $start).substr($name,$start,$len)."@".end($extension);   
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
		
		//## forget pass acknowledgment
		public function forgetack()
		{
			
			$this->load->view('forgetacknowledgement');	
		}
		
		##---------End user logout (prafull)-----------##//
		public function Logout(){
			$sessionData = $this->session->all_userdata();
			foreach($sessionData as $key =>$val){
				$this->session->unset_userdata($key);    
			}
			$cookie_name = "instruction";
			$cookie_value = "0";
			setcookie($cookie_name, $cookie_value, time() + (60 * 10), "/"); // 60 seconds ( 1 minute) * 10 = 10 minutes
			redirect('http://iibf.org.in/');
		}
	}	