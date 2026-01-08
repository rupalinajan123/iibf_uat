<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller
{
	public $UserID;
			
public function __construct()
{
		parent::__construct();
		$this->load->model('LoginModel');
		$this->load->helper('captcha');
	
}
	public function index()
	{ 
		//echo $this->session->userdata('id');exit;
		$data['error'] = '';
		//echo "####".$this->session->userdata('id');
		if($this->session->userdata('id')=='')
		{
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
			
				/*$dataarr=array(
					'username'=> $this->input->post('Username'),
					'password'=> md5($this->input->post('Password')),
					'u.active'=> '1',
					'u.deleted'=> '0',
				);*/
				
				$dataarr=array(
					'username'=> $this->input->post('Username'),
					'password'=> $this->input->post('Password'),
					'u.active'=> '1',
					'u.deleted'=> '0',
					'u.roleid !='=> '4',
					'u.active !='=> '5',
				);
				if ($this->form_validation->run() == FALSE){
					//$this->load->view('admin/login/login',$data);
				}else{
					$res=$this->LoginModel->isUserExist($dataarr);
					if($res['rows']==1){
						$newdata = $res['result'][0];
						//print_r($newdata);exit;
						$this->session->set_userdata($newdata);				
						redirect('admin/ExamCountDashboard/ExamCount');	
					}else{
						$data['error']='<span style="">Invalid Credentials</span>';
						//$this->load->view('admin/login/login',$dataarr);
					}
				}
			}
			
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => base_url().'uploads/applications/'
							//'img_url' => './uploads/applications/',
						);
			$cap = create_captcha($vals);
			//print_r($cap);
			
			$data['image'] = $cap['image'];
			$data['code']=$cap['word'];
			
			$this->session->set_userdata('adminlogincaptcha', $cap['word']);
			
			$this->load->view('admin/ExamCountDashboard/login/login',$data);
		}
		else
		{
			redirect(base_url().'admin/ExamCountDashboard/ExamCount');
		}
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
		
		/*$data=array(
			'Username'=> $this->input->post('Username'),
			'Password'=> md5($this->input->post('Password')),
			'u.Active'=> 1,
		);*/
		
		$data=array(
			'Username'=> $this->input->post('Username'),
			'Password'=> $this->input->post('Password'),
			'u.Active'=> 1,
		);
		
		if ($this->form_validation->run() == FALSE){
			$this->load->view('admin/ExamCountDashboard/login/login',$data);
		}else{
			$res=$this->LoginModel->isUserExist($data);
			if($res['rows']==1){
				$newdata = $res['result'][0];
				$this->session->set_userdata($newdata);				
				redirect('admin/ExamCountDashboard/ExamCount');	
			}else{
				$data['error']='<span style="color:red;">Invalid Credentials</span>';
				$this->load->view('admin/ExamCountDashboard/login/login',$data);
			}
		}		
	}
	
	public function check_captcha_adminlogin($code) 
	{
		if(!isset($this->session->adminlogincaptcha) && empty($this->session->adminlogincaptcha))
		{
			redirect(base_url().'admin/ExamCountDashboard/login/');
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
		}
	}
	
	public function Logout(){
		$sessionData = $this->session->all_userdata();
		foreach($sessionData as $key =>$val){
			$this->session->unset_userdata($key);    
		}
		redirect('admin/ExamCountDashboard/ExamCount/index');
	}
}