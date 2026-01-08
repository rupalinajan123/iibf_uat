<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class InstituteLogin extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('LoginModel');	
	}
	public function index()
	{
		$data['error'] = '';
		if( !$this->session->userdata('FGet') ) { 
			if(isset($_POST['submit'])) {
				$config = array(
					array(
						'field' => 'Username',
						'label' => 'institute Code',
						'rules' => 'trim|required|numeric|max_length[30]'
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
				/* 	array(
						'field' => 'val3',
						'label' => 'Answer',
						'rules' => 'trim|required',
					), */
				);
				$this->form_validation->set_rules($config);
				$dataarr=array(
					'institute_code'=> $this->input->post('Username'),
					'password'=> md5($this->input->post('Password')),
					'accerdited_delete'=> '0',
				);
				//echo md5($this->input->post('Password')); die;
				if ($this->form_validation->run() == FALSE)
				{
					//$this->load->view('iibfdra/institute_login',$data);
				}
				else
				{
				// 	$val1=$_POST['val1'];		  
				// 	$val2=$_POST['val2'];		  
				// 	$val3=$_POST['val3'];
				// 	$add_val= ($val1+$val2);
					
					//if($add_val==$val3)
					//{
					$res = $this->LoginModel->isDraInstExist($dataarr);
					//print_r($res); die();
						if( $res['rows'] == 1 ) 
						{
						$newdata = $res['result'][0];
						$this->session->set_userdata('dra_institute', $newdata);		
						redirect('iibfdra/InstituteHome/dashboard');	
					} 
					else /*added by aayusha-start */
					{
							$this->db->select('dra_inst_registration.status');
							$this->db->join('dra_accerdited_master', 'dra_inst_registration.id = dra_accerdited_master.dra_inst_registration_id','left');
							$get_inst_detail=$this->master_model->getRecords('dra_inst_registration', array(
							'dra_accerdited_master.institute_code'=> $this->input->post('Username'),
							'dra_accerdited_master.password'=> md5($this->input->post('Password')),
							'dra_inst_registration.status' => '0'
						));
						if(count($get_inst_detail) >0)
						{
							if($get_inst_detail[0]['status']==0)
							{
									//$this->session->set_flashdata('error_message','Agency has been deactivated!');
									$data['error']='Agency has been deactivated!';	
							}
						}/*added by aayusha-end */
						else
						{
						$this->session->set_flashdata('error_message','Invalid Credentials');
							$data['error']='Invalid Credentials';
						}
						
					}
				//}
				// 	else
				// 	{
				// 		$data['error'] = 'Please enter correct answer';
				// 	}
			}
			}
			
			/* $this->load->helper('captcha');
			$vals = array(
				'img_path' => './uploads/applications/',
				'img_url' => base_url().'/uploads/applications/',
			);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code'] = $cap['word'];
			$this->session->set_userdata('drainstlogincaptcha', $cap['word']); */
			
			$this->load->model('Captcha_model');
                $data['image'] = $this->Captcha_model->generate_captcha_img('drainstlogincaptcha');
			$this->load->view('iibfdra/institute_login',$data);
		}
		else
		{
			redirect(base_url().'iibfdra/InstituteHome/dashboard');
		}
	}
	
	public function check_captcha_adminlogin($code) 
	{
		if(!isset($this->session->drainstlogincaptcha) && empty($this->session->drainstlogincaptcha))
		{
			redirect(base_url().'iibfdra/admin/login/');
		}
		if($code == '' || $this->session->drainstlogincaptcha != $code )
		{
			$this->form_validation->set_message('check_captcha_adminlogin', 'Invalid %s.'); 
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
	
	public function check_captcha_dralogin($code) 
	{
		if(!isset($this->session->drainstlogincaptcha) && empty($this->session->drainstlogincaptcha))
		{
			redirect(base_url().'iibfdra/InstituteLogin/');
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
		redirect('iibfdra/InstituteLogin');
	}

	// reload captcha functionality
	public function generatecaptchaajax()
	{
		
		            $session_name = 'drainstlogincaptcha';
            if(isset($_POST['session_name']) && $_POST['session_name'] != "")
              $session_name = 'draadminlogincaptcha';
            if(isset($_POST['session_name']) && $_POST['session_name'] != "")
            {
                $session_name = $this->security->xss_clean(trim($this->input->post('session_name')));
            }
          
            $this->load->model('Captcha_model');
            echo $captcha_img = $this->Captcha_model->generate_captcha_img($session_name);
	}
}	
?>