<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
	private $USERDATA = array();		
	public function __construct(){
		parent::__construct();
		$this->load->model('LoginModel');
		$this->load->model('master_model');
		$this->load->helper('general_helper');
	}
	public function index()
	{
		if( !$this->session->userdata('bulk_admin') ) {
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
					'username'=> $this->input->post('Username'),
					'password'=> md5($this->input->post('Password')),
					'active'=> '1',
					'deleted'=> '0',
				);
				if ($this->form_validation->run() == FALSE){
					//$this->load->view('admin/login/login',$data);
				}else{
					$res = $this->LoginModel->isBulkUserExist($dataarr);
					//print_r($res); die();
					if( $res['rows'] == 1 ) {
						$newdata = $res['result'][0];
						//$this->session->set_userdata($newdata);	
						$this->session->set_userdata('bulk_admin', $newdata);		
						redirect('bulk/admin/MainController');	
					} else {
						$data['error']='<span style="color:red;">Invalid Credentials</span>';
						$this->session->set_flashdata('error_message','Invalid Credentials');
						//$this->load->view('admin/login/login',$dataarr);
					}
				}
			}
			/*$this->load->helper('captcha');
			$vals = array(
				'img_path' => './uploads/applications/',
				'img_url' => base_url().'uploads/applications/',
			);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code'] = $cap['word'];
			$this->session->set_userdata('bulkadminlogincaptcha', $cap['word']);*/
			
			$this->load->model('Captcha_model');                
      $captcha_img = $this->Captcha_model->generate_captcha_img('bulkadminlogincaptcha');
      $data['image'] = $captcha_img;

			$this->load->view('bulk/admin/login/login',$data);
		}
		else
		{
			redirect(base_url().'bulk/admin/MainController');
		}
	}
	
	public function check_captcha_adminlogin($code) 
	{
		if(!isset($this->session->bulkadminlogincaptcha) && empty($this->session->bulkadminlogincaptcha))
		{
			redirect(base_url().'bulk/admin/Login/');
		}
		if($code == '' || $this->session->bulkadminlogincaptcha != $code )
		{
			$this->form_validation->set_message('check_captcha_adminlogin', 'Invalid %s.'); 
			$this->session->set_userdata("bulkadminlogincaptcha", rand(1,100000));
			return false;
		}
		if($this->session->bulkadminlogincaptcha == $code)
		{
			$this->session->set_userdata('bulkadminlogincaptcha','');
			$this->session->unset_userdata("bulkadminlogincaptcha");
			return true;
		}
	}
	
	// function to change bulk admin password, Added by - Bhagwan Sahane, on 18-11-2016
	public function changepassword()
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
					redirect(base_url().'bulk/admin/login/changepassword');
				}
				
				$encPass = md5($new_pass);
				$curr_encrypass = md5(trim($current_pass));
				
				// get bulk admin id -
				$this->UserData = $this->session->userdata('bulk_admin');
				$bulk_admin_id = $this->UserData['id'];
				
				$row = $this->master_model->getRecordCount('bulk_admin', array('password' => $curr_encrypass, 'id' => $bulk_admin_id));
				if($row == 0)
				{
					$this->session->set_flashdata('error','Current Password is Wrong.'); 
					redirect(base_url().'bulk/admin/login/changepassword');
				}
				else
				{
					$input_array = array('password' => $encPass);
					$this->master_model->updateRecord('bulk_admin', $input_array, array('id' => $bulk_admin_id));
					
					log_bulk_admin($log_title = "Bulk Admin Password Changed Successful", $log_message = serialize($input_array));
					
					$this->session->set_flashdata('success','Password Changed successfully.'); 
					redirect(base_url().'bulk/admin/login/changepassword');
				}
			}
		}
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'bulk/admin/MainController"><i class="fa fa-home"></i> Home</a></li>
					<li class="active">Change Password</li>
				</ol>';
		
		$this->load->view('bulk/admin/login/change_password', $data);
	}
	
	public function Logout(){
		$sessionData = $this->session->userdata('bulk_admin');
		$this->session->unset_userdata('bulk_admin');
		redirect('bulk/admin/Login');
	}
}