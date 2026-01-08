<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class InstituteLogin extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('LoginModel');	
	}
	public function index()
	{
		if( !$this->session->userdata('dra_institute') ) {
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
						'rules' => 'trim|required|callback_check_captcha_dralogin',
					),
				);
				$this->form_validation->set_rules($config);
				$dataarr=array(
					'institute_code'=> $this->input->post('Username'),
					'password'=> md5($this->input->post('Password')),
					'accerdited_delete'=> '0',
				);
				if ($this->form_validation->run() == FALSE){
					//$this->load->view('iibfdra/Version_2/institute_login',$data);
				}else{
					$res = $this->LoginModel->isDraInstExist($dataarr);
					//print_r($res); die();
					if( $res['rows'] == 1 ) {
						$newdata = $res['result'][0];
						$this->session->set_userdata('dra_institute', $newdata);		
						redirect('iibfdra/Version_2/InstituteHome/dashboard');	
					} else {
						$data['error']='<span style="color:red;">Invalid Credentials</span>';
						$this->session->set_flashdata('error_message','Invalid Credentials');
					}
				}
			}
			$this->load->helper('captcha');
			$vals = array(
				'img_path' => './uploads/applications/',
				'img_url' => base_url().'/uploads/applications/',
			);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code'] = $cap['word'];
			$this->session->set_userdata('drainstlogincaptcha', $cap['word']);
			//print_r($data);exit;
			$this->load->view('iibfdra/Version_2/institute_login',$data);
		}
		else
		{
			redirect(base_url().'iibfdra/Version_2/InstituteHome/dashboard');
		}
	}
	
	public function check_captcha_dralogin($code) 
	{
		if(!isset($this->session->drainstlogincaptcha) && empty($this->session->drainstlogincaptcha))
		{
			redirect(base_url().'iibfdra/Version_2/InstituteLogin/');
		}
		if($code == '' || $this->session->drainstlogincaptcha != $code )
		{
			$this->form_validation->set_message('check_captcha_dralogin', 'Invalid %s.'); 
			$this->session->set_userdata("drainstlogincaptcha", rand(1,100000));
			return false;
		}
		if($this->session->drainstlogincaptcha == $code)
		{
			$this->session->set_userdata('drainstlogincaptcha','');
			$this->session->unset_userdata("drainstlogincaptcha");
			return true;
		}
	}
	
	public function Logout(){
		$this->session->unset_userdata('dra_institute');
		redirect('iibfdra/Version_2/InstituteLogin');
	}
}
?>