<?php	
  /********************************************************************
  * Description	: Controller for Validate user captcha only
  * Created BY	: Sagar Matale On 27-08-2021
  * Update By		: Sagar Matale on 27-08-2021
	********************************************************************/
  
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Validate_user_captcha extends CI_Controller 
	{	
		public function __construct()
		{
			parent::__construct();
		}
		
		public function index($member_type='') 
		{
			$this->load->model('Captcha_model');
			$data['captcha_img'] = $this->Captcha_model->generate_captcha_img('VALIDATE_USER');
			
			if(isset($_POST) && count($_POST) > 0)  
			{
				$cookie_name = "instruction";
				$cookie_value = "1";
				setcookie($cookie_name, $cookie_value, time() + (60 * 10), "/"); // 60 seconds ( 1 minute) * 10 = 10 minutes
				//$CI->session->set_userdata('instruction','1');
				header("Location: https://iibf.esdsconnect.com/");
				
				/*$config = array(
				array(
				'field' => 'val3',
				'label' => 'Answer',
				'rules' => 'trim|required',
				) 
				);
				
				$this->form_validation->set_rules($config);
				
				if ($this->form_validation->run() == TRUE)
				{
					$val1=$_POST['val1'];		  
					$val2=$_POST['val2'];		  
					$val3=$_POST['val3'];
					$add_val= ($val1+$val2);
					
					if($add_val==$val3)
					{				
						$cookie_name = "instruction";
						$cookie_value = "1";
						setcookie($cookie_name, $cookie_value, time() + (60 * 10), "/"); // 60 seconds ( 1 minute) * 10 = 10 minutes
						//$CI->session->set_userdata('instruction','1');
						header("Location: https://iibf.esdsconnect.com/");
					}
					else 
					{
						$data['error']='Invalid Credentials';
						//$this->session->set_flashdata('error_message','Invalid Credentials');
						//$this->load->view('admin/login/login',$dataarr);
					}
				} */
			}
			
			$this->load->view('validate_user_captcha',$data);
		}
		
		function generate_captcha_ajax()
		{
			$session_name = 'VALIDATE_USER';
			if(isset($_POST['session_name']) && $_POST['session_name'] != "") 
			{ 
				$session_name = $this->security->xss_clean(trim($this->input->post('session_name')));
			}
			
			$this->load->model('Captcha_model');
			echo $captcha_img = $this->Captcha_model->generate_captcha_img($session_name);
		}
		
		public function check_captcha_code_ajax()
		{
			if(isset($_POST) && count($_POST) > 0)
			{
				$session_name = 'VALIDATE_USER';
				$session_captcha = '';
				
				if(isset($_POST['session_name']) && $_POST['session_name'] != "") 
				{ 
					$session_name = $this->security->xss_clean(trim($this->input->post('session_name')));
				}
				
				if(isset($_SESSION[$session_name])) { $session_captcha = $_SESSION[$session_name]; }
				
				$captcha_code = $this->security->xss_clean(trim($this->input->post('captcha_code')));
        
				if($captcha_code == $session_captcha) { echo 'true'; } else { echo "false"; }
        
			} else { echo "false"; }
		}		
  } 			