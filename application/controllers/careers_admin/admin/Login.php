<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
	private $USERDATA = array();		
	public function __construct(){
		parent::__construct();
		$this->load->model('LoginModel');
		$this->load->model('master_model');
		$this->load->model('CareerAdminModel');
		$this->load->helper('general_helper');
	}

	//login to careeradmin
	public function index()
	{
			if(isset($_POST['submit'])) { 
				$config = array(
					array(
						'field' => 'Username',
						'label' => 'Username',
						'rules' => 'trim|required|alpha_numeric|max_length[30]'
					),
					array(
						'field' => 'Password',
						'label' => 'Password',
						'rules' => 'trim|required',
					),
					array(
						'field' => 'code',
						'label' => 'Code',
						'rules' => 'trim|required|callback_check_captcha_adminlogin',
					),
				);
				$this->form_validation->set_rules($config);
				$dataarr=array(
					'username' => $this->input->post('Username'),
					'password' => $this->input->post('Password'),
					'active'   => '1',
					'deleted'  => '0',
				);
				if ($this->form_validation->run() == FALSE)
				{

					$data['error']='<span style="color:red;">Invalid Credentials.</span>';
					$this->session->set_flashdata('error_message','Invalid Code');
				}
				else 
				{
					$res = $this->CareerAdminModel->isUserExist($dataarr);
					if( $res['rows'] == 1 ) 
					{						
						$newdata = $res['result'][0];						
						$this->session->set_userdata($newdata);	
						$this->session->set_userdata('career_admin', $newdata);	
						redirect('careers_admin/admin/Career_admin/career_admin_list');	
					} 

					else 
					{
						$data['error']='<span style="color:red;">Invalid Credentials.</span>';
						$this->session->set_flashdata('error_message','Invalid Credentials.');
					}
				}
			} 
			$this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => base_url().'uploads/applications/',
						);

			/* $cap = create_captcha($vals);
			$data['captchaImg'] = $cap['image'];
			$data['code'] = $cap['word'];
			$this->session->set_userdata('careeradminlogincaptcha', $cap['word']); */

      $this->load->model('Captcha_model');
			$data['captchaImg'] = $this->Captcha_model->generate_captcha_img('careeradminlogincaptcha');

			$this->load->view('careers_admin/admin/login/login',$data);		
	}
	
	//captcha login
	public function check_captcha_adminlogin($code) 
	{
		if(!isset($this->session->careeradminlogincaptcha) && empty($this->session->careeradminlogincaptcha))
		{
			redirect(base_url().'careers_admin/admin/Login');
		}
		if($code == '' || $this->session->careeradminlogincaptcha != $code)
		{
			$this->form_validation->set_message('check_captcha_adminlogin'); 
			$this->session->set_userdata("careeradminlogincaptcha", rand(1,100000));
			return false;
		}
		if($this->session->careeradminlogincaptcha == $code)
		{
			$this->session->set_userdata('careeradminlogincaptcha','');
			$this->session->unset_userdata("careeradminlogincaptcha");
			return true;
		}
	}
	
	// function to change maker or checker admin password, Added by - swati
	/*public function changepassword()
	{
		$data = array();
		if(isset($_POST['btn_password']))
		{
			$this->form_validation->set_rules('current_pass','','required|xss_clean');
			$this->form_validation->set_rules('txtnpwd','New Password','required|xss_clean');
			$this->form_validation->set_rules('txtrpwd','Re-type new password','required|xss_clean|matches[txtnpwd]');
			if($this->form_validation->run())
			{
				$current_pass = $this->input->post('current_pass');
				$new_pass = $this->input->post('txtnpwd');
				
				// check is current password and new password is same -
				if($current_pass == $new_pass)
				{
					$this->session->set_flashdata('error','Current Password & New Password should not be same.'); 
					redirect(base_url().'careers_admin/admin/Login/changepassword');
				}
				
				$encPass = md5($new_pass);
				$curr_encrypass = md5(trim($current_pass));
				
				// get DRA admin id -
				$this->UserData = $this->session->userdata('career_admin');
				$career_admin_id = $this->UserData['id'];
				
				$row = $this->master_model->getRecordCount('administrators', array('password' => $curr_encrypass, 'id' => $career_admin_id));
				if($row == 0)
				{
					$this->session->set_flashdata('error','Current Password is Wrong.'); 
					redirect(base_url().'careers_admin/admin/Login/changepassword');
				}
				else
				{
					$input_array = array('password' => $encPass);
					$this->master_model->updateRecord('administrators', $input_array, array('id' => $career_admin_id));
					
					//log_creditnote_admin($log_title = "Creditnote Password Changed Successful", $log_message = serialize($input_array));
					
					$this->session->set_flashdata('success','Password Changed successfully.'); 
					redirect(base_url().'careers_admin/admin/Login/changepassword');
				}
			}
		}
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'careers_admin/admin/Career_admin/career_admin_list"><i class="fa fa-home"></i> Home</a></li>
					<li class="active">Change Password</li>
				</ol>';
		
		$this->load->view('careers_admin/admin/Login/change_password', $data);
	}*/
	
	//session time out
	public function Logout(){
		$sessionData = $this->session->userdata('career_admin');
		$this->session->unset_userdata('career_admin');
		redirect('careers_admin/admin/Login');
	} 
}