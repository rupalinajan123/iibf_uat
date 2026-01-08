<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
	private $USERDATA = array();		
	public function __construct(){
		parent::__construct();
		$this->load->model('LoginModel');
		$this->load->model('master_model');
		$this->load->model('DonationModel');
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
						'rules' => 'trim|required|callback_check_captcha_adminlogin',
					),
				);
				$this->form_validation->set_rules($config);

				$dataarr=array(
					'username' => $this->input->post('Username'),
					'password' => $this->input->post('Password'),
				);
				if ($this->form_validation->run() == FALSE)
				{

					$data['error']='<span style="color:red;">Invalid Credentials.</span>';
					$this->session->set_flashdata('error_message','Invalid Code');
				}
				else 
				{
					$res = $this->DonationModel->isUserExist($dataarr);
					if( $res['rows'] == 1 ) 
					{						
						$newdata = $res['result'][0];						
						$this->session->set_userdata($newdata);	
						$this->session->set_userdata('donation_admin', $newdata);	
						redirect('donations_admin/admin/Donation_admin/donation_admin_list');	
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

			$cap = create_captcha($vals);
			$data['captchaImg'] = $cap['image'];
			$data['code'] = $cap['word'];

			$this->session->set_userdata('donationadminlogincaptcha', $cap['word']);
			$this->load->view('donations_admin/admin/login/login',$data);		
	}
	
	//captcha login
	public function check_captcha_adminlogin($code) 
	{
		if(!isset($this->session->donationadminlogincaptcha) && empty($this->session->donationadminlogincaptcha))
		{
			redirect(base_url().'donations_admin/admin/Login');
		}
		if($code == '' || $this->session->donationadminlogincaptcha != $code)
		{
			$this->form_validation->set_message('check_captcha_adminlogin'); 
			$this->session->set_userdata("donationadminlogincaptcha", rand(1,100000));
			return false;
		}
		if($this->session->careeradminlogincaptcha == $code)
		{
			$this->session->set_userdata('donationadminlogincaptcha','');
			$this->session->unset_userdata("donationadminlogincaptcha");
			return true;
		}
	}
	
	
	//session time out
	public function Logout(){
		$sessionData = $this->session->userdata('donation_admin');
		$this->session->unset_userdata('donation_admin');
		redirect('donations_admin/admin/Login');
	} 
}