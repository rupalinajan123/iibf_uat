<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
	private $USERDATA=array();		
	public function __construct(){
		parent::__construct();
		$this->load->model('LoginModel');
		$this->load->model('KYC_Log_model'); 
	}
	
	public function index()
	{
		$data['error'] = '';
		$session_array=array();
		/*if($this->session->userdata('id') == '')
		{*/
			if(isset($_POST['submit']))
			{
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
					'password'=> $this->input->post('Password'),
					'u.active'=> '1',
					'u.deleted'=> '0',
				);
				if ($this->form_validation->run() == FALSE){
					//$this->load->view('admin/login/login',$data);
				}else{
					$res=$this->LoginModel->isUserExist($dataarr);
					if($res['rows'] == 1){
						$newdata = $res['result'][0];
						if($newdata ['role']== 'Recommender') 
					   {
							 $session_array['kyc_id'] = $newdata['id'];
							 $session_array['role']= 'recommender';
							 $session_array['name']= $newdata['name'];
							 $session_array['emailid']= $newdata['emailid'];
							 $session_array['username']= $newdata['username'];
							 $session_array['password']= $newdata['password'];
							 $session_array['roleid']= $newdata['roleid'];
							 $this->session->set_userdata($session_array);	
							 $this->KYC_Log_model->create_log('Recommender Login', $this->session->userdata('kyc_id'));			
							 redirect(base_url().'admin/kyc/Kyc/dashboard');
					   }else if($newdata ['role']== 'approver')
					   {
							//$newdata['kyc_id'] = $newdata['id'];
							//$newdata ['role']   = 'approver';
							$session_array['kyc_id'] = $newdata['id'];
							$session_array['role']   = 'approver';
							$session_array['name']= $newdata['name'];
							$session_array['emailid']= $newdata['emailid'];
							$session_array['username']= $newdata['username'];
							$session_array['password']= $newdata['password'];
							$session_array['roleid']= $newdata['roleid'];
							$this->session->set_userdata($session_array);
							 $this->KYC_Log_model->create_log('Approver Login', $this->session->userdata('kyc_id'));			
							redirect(base_url().'admin/kyc/Approver/dashboard');
					   }else if($newdata ['role']== 'superuser')
					   {    
							//$newdata['kyc_id'] = $newdata['id'];
							//$newdata ['role']   = 'approver';
							$session_array['kyc_id'] = $newdata['id'];
							$session_array['role']   = 'superuser';
							$session_array['name']= $newdata['name'];
							$session_array['emailid']= $newdata['emailid'];
							$session_array['username']= $newdata['username'];
							$session_array['password']= $newdata['password'];
							$session_array['roleid']= $newdata['roleid'];
							$this->session->set_userdata($session_array);
							 redirect(base_url().'admin/kyc/Kycsuperuser/dashboard');
					   }
					}else{
						$data['error']='<span style="">Invalid Credentials</span>';
						//$this->load->view('admin/login/login',$dataarr);
					}
				}
			}
			/* $this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => ''.base_url().'uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code'] = $cap['word'];
			$this->session->set_userdata('adminlogincaptcha', $cap['word']); */
			$this->load->model('Captcha_model');                 
			$data['image'] = $this->Captcha_model->generate_captcha_img('adminlogincaptcha');

			$this->load->view('admin/kyc/login/login',$data);
		/*}
		else
		{
			redirect(base_url().'admin/kyc/Kyc');
		}*/
	}
	
	public function LogMeIn(){		
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
		
		$data=array(
			'Username'=> $this->input->post('Username'),
			'Password'=> $this->input->post('Password'),
			
			'u.Active'=> 1,
		);
		
		if ($this->form_validation->run() == FALSE){
			$this->load->view('admin/kyc/login/login',$data);
		}else{
			$res=$this->LoginModel->isUserExist($data);
			if($res['rows'] == 1){
				$newdata = $res['result'][0];
				$this->session->set_userdata($newdata);				
				redirect('admin/kyc/Kyc');	
			}else{
				$data['error']='<span style="color:red;">Invalid Credentials</span>';
				$this->load->view('admin/kyc/login/login',$data);
			}
		}		
	}
	
	public function check_captcha_adminlogin($code) 
	{
		$this->load->model('Captcha_model');                 
		$data['image'] = $this->Captcha_model->generate_captcha_img('adminlogincaptcha');
		/* if(!isset($this->session->adminlogincaptcha) && empty($this->session->adminlogincaptcha))
		{
			redirect(base_url().'admin/kyc/login/');
		}
		
		if($code == '' || $this->session->adminlogincaptcha != $code )
		{
			$this->form_validation->set_message('check_captcha_adminlogin', 'Invalid %s.'); 
			$this->session->set_userdata("adminlogincaptcha", rand(1,100000));
			return false;
		}
		if($this->session->adminlogincaptcha == $code)
		{
			$this->session->set_userdata('adminlogincaptcha','');
			$this->session->unset_userdata("adminlogincaptcha");
			return true;
		} */
	}
	
	public function Logout(){
	 $this->KYC_Log_model->create_log('KYC User Logout', $this->session->userdata('kyc_id'));	
		$sessionData = $this->session->all_userdata();
		foreach($sessionData as $key =>$val){
			$this->session->unset_userdata($key);    
		}
		redirect('admin/kyc');
	}
}