<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class InstituteLogin extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('LoginModel');	
	}
	public function index()
	{
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
					//$this->load->view('iibfdra/institute_login',$data);
				}else{
					$res = $this->LoginModel->isDraInstExist($dataarr);
					//print_r($res); die();
					if( $res['rows'] == 1 ) {
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
								$this->session->set_flashdata('error_message','Agency has been deactivited!');
								$data['error']='<span style="color:red;">Agency has been deactivited!</span>';	
							}
						}/*added by aayusha-end */
						else
						{
						$this->session->set_flashdata('error_message','Invalid Credentials');
						$data['error']='<span style="color:red;">Invalid Credentials</span>';
						}
						
					}
				}
			}
			/*$this->load->helper('captcha');
			$vals = array(
				'img_path' => './uploads/applications/',
				'img_url' => base_url().'/uploads/applications/',
			);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code'] = $cap['word'];
			$this->session->set_userdata('drainstlogincaptcha', $cap['word']);*/
			$this->load->model('Captcha_model');                
      $data['image'] = $this->Captcha_model->generate_captcha_img('drainstlogincaptcha');
			$this->load->view('iibfdra/institute_login',$data);
		}
		else
		{
			redirect(base_url().'iibfdra/InstituteHome/dashboard');
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
		
		/*$this->load->helper('captcha');
		$this->session->unset_userdata("drainstlogincaptcha");
		$this->session->set_userdata("drainstlogincaptcha", rand(1, 100000));
		$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data = $cap['image'];
		//$_SESSION["regcaptcha"] = $cap['word'];
		$this->session->set_userdata('drainstlogincaptcha', $cap['word']);*/
		$this->load->model('Captcha_model');                
    echo $this->Captcha_model->generate_captcha_img('drainstlogincaptcha');
		 
	}
}
?>