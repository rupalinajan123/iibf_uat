<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
	private $USERDATA = array();		
	public function __construct(){
		parent::__construct();
		$this->load->model('LoginModel');
		$this->load->model('master_model');
		$this->load->model('CreditNoteModel');
		$this->load->helper('general_helper');
	}

	//login to maker and checker
	public function index()
	{
		if( !$this->session->userdata('creditnote_admin') ) {
			
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
					'password'=> $this->input->post('Password'),
					'active'=> '1',
					'deleted'=> '0',
				);
				if ($this->form_validation->run() == FALSE){
					//$this->load->view('admin/login/login',$data);
				}else{
					$res = $this->CreditNoteModel->isUserExist($dataarr);
					//print_r($res); die();
					if( $res['rows'] == 1 ) {						
						$newdata = $res['result'][0];						
						// Added by Manoj to store user type in session value. on 14 jan 2018
						if( $newdata['roleid'] ==10 ) {
							$newdata['admin_user_type'] = 'Aniruddha';	
						
						}
						else{

							$data['error']='<span style="color:red;">Invalid Credentials</span>';
						    $this->session->set_flashdata('error_message','Invalid Credentials');
						}						
						//$this->session->set_userdata($newdata);	
						$this->session->set_userdata('creditnote_admin', $newdata);	
						//print_r($newdata); die;	
						redirect('mempass/admin/MainController');	
					} else {
						$data['error']='<span style="color:red;">Invalid Credentials</span>';
						$this->session->set_flashdata('error_message','Invalid Credentials');
						//$this->load->view('admin/login/login',$dataarr);
					}
				}
			} 
			$this->load->helper('captcha');
			$vals = array(
				'img_path' => './uploads/applications/',
				'img_url' => base_url().'uploads/applications/',
			);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code'] = $cap['word'];
			$this->session->set_userdata('creditnoteadminlogincaptcha', $cap['word']);
			$this->load->view('mempass/admin/login/login',$data);
		}
		else
		{
			//echo "swati"; die;
			redirect(base_url().'mempass/admin/MainController');
		}
	}
	
	//captcha login
	public function check_captcha_adminlogin($code) 
	{
		if(!isset($this->session->creditnoteadminlogincaptcha) && empty($this->session->creditnoteadminlogincaptcha))
		{
			redirect(base_url().'mempass/admin/login/');
		}
		if($code == '' || $this->session->creditnoteadminlogincaptcha != $code )
		{
			$this->form_validation->set_message('check_captcha_adminlogin', 'Invalid %s.'); 
			$this->session->set_userdata("creditnoteadminlogincaptcha", rand(1,100000));
			return false;
		}
		if($this->session->creditnoteadminlogincaptcha == $code)
		{
			$this->session->set_userdata('creditnoteadminlogincaptcha','');
			$this->session->unset_userdata("creditnoteadminlogincaptcha");
			return true;
		}
	}
	
	// function to change maker or checker admin password, Added by - swati
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
					redirect(base_url().'mempass/admin/login/changepassword');
				}
				
				$encPass = md5($new_pass);
				$curr_encrypass = md5(trim($current_pass));
				
				// get DRA admin id -
				$this->UserData = $this->session->userdata('creditnote_admin');
				$creditnote_admin_id = $this->UserData['id'];
				
				$row = $this->master_model->getRecordCount('administrators', array('password' => $curr_encrypass, 'id' => $creditnote_admin_id));
				if($row == 0)
				{
					$this->session->set_flashdata('error','Current Password is Wrong.'); 
					redirect(base_url().'mempass/admin/login/changepassword');
				}
				else
				{
					$input_array = array('password' => $encPass);
					$this->master_model->updateRecord('administrators', $input_array, array('id' => $creditnote_admin_id));
					
					//log_creditnote_admin($log_title = "Creditnote Password Changed Successful", $log_message = serialize($input_array));
					
					$this->session->set_flashdata('success','Password Changed successfully.'); 
					redirect(base_url().'mempass/admin/login/changepassword');
				}
			}
		}
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'mempass/admin/MainController"><i class="fa fa-home"></i> Home</a></li>
					<li class="active">Change Password</li>
				</ol>';
		
		$this->load->view('mempass/admin/login/change_password', $data);
	}
	
	//session time out
	public function Logout(){
		$sessionData = $this->session->userdata('creditnote_admin');
		$this->session->unset_userdata('creditnote_admin');
		redirect('mempass/admin/login');
	}
}