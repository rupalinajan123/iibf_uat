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
		//echo md5('Admin@sep2025#');
		$data['error'] = '';
		if( !$this->session->userdata('case_study_comp_admin') ) {
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
				// 	array(
				// 		'field' => 'val3',
				// 		'label' => 'Answer',
				// 		'rules' => 'trim|required',
				// 	),
				);
				$this->form_validation->set_rules($config);
				$dataarr=array(
					'username'=> $this->input->post('Username'),
					'password'=> md5($this->input->post('Password')),
					'active'=> '1',
					'deleted'=> '0',
				);
				if ($this->form_validation->run() == FALSE){
					//$this->load->view('login/login',$data);
				}
				else
				{
				// 	$val1=$_POST['val1'];		  
				// 	$val2=$_POST['val2'];		  
				// 	$val3=$_POST['val3'];
				// 	$add_val= ($val1+$val2);
					
				//	if($add_val==$val3)
				//	{
					$res = $this->LoginModel->isCaseWritingCompetitionAdminExist($dataarr);
					//print_r($res); die();
					if( $res['rows'] == 1 ) 
					{					
						$newdata = $res['result'][0];
						
						// Added by manoj to store user type in session value.
						if( $newdata['roleid'] == 1 ) {
							//$newdata['admin_user_type'] = 'Approver';	
							$newdata['admin_user_type'] = '';	
						}else{
							//$newdata['admin_user_type'] = 'New';
							$newdata['admin_user_type'] = '';
						}
						
						/*$user_type = $newdata['roleid'] == 1;
						if ($user_type == 1)
						{
						    //set session to non-expiring
						    //$this->session->sess_expiration = '32140800'; //~ one year
						    $this->session->sess_expiration = '30'; //0.5 Min.
						    $this->session->sess_expire_on_close = false;
						}
						else
						{
						    //set session expire time, after that user should login again
						    $this->session->sess_expiration = '1800'; //30 Minutes
						    $this->session->sess_expire_on_close = true;
						}*/

						//$this->session->set_userdata($newdata);	
						$this->session->set_userdata('case_study_comp_admin', $newdata);		

						


						redirect('case_writing_competition/Dashboard');	
					} 
					else 
					{
							$data['error']='Invalid Credentials';
							//$this->session->set_flashdata('error_message','Invalid Credentials');
					//$this->load->view('login/login',$dataarr);
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
				'img_url' => base_url().'uploads/applications/',
			);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code'] = $cap['word'];
			$this->session->set_userdata('CASE_WRITING_COMPETITION', $cap['word']); */
			
                $this->load->model('Captcha_model');
                $data['image'] = $this->Captcha_model->generate_captcha_img('CASE_WRITING_COMPETITION');

			$this->load->view('case_writing_competition/admin/login',$data);
		}
		else
		{
			redirect(base_url().'case_writing_competition/Dashboard');
		}
	}
	
	public function generate_captcha_ajax()
        {
            $session_name = 'CASE_WRITING_COMPETITION';
            if(isset($_POST['session_name']) && $_POST['session_name'] != "")
            {
                $session_name = $this->security->xss_clean(trim($this->input->post('session_name')));
            }
          
            $this->load->model('Captcha_model');
            echo $captcha_img = $this->Captcha_model->generate_captcha_img($session_name);
        }
	
	public function check_captcha_adminlogin($code) 
	{
		if(!isset($this->session->CASE_WRITING_COMPETITION) && empty($this->session->CASE_WRITING_COMPETITION))
		{
			redirect(base_url().'case_writing_competition/login/');
		}
		if($code == '' || $this->session->CASE_WRITING_COMPETITION != $code )
		{
			$this->form_validation->set_message('check_captcha_adminlogin', 'Invalid %s.'); 
			$this->session->set_userdata("CASE_WRITING_COMPETITION", rand(1,100000));
			return false;
		}
		if($this->session->CASE_WRITING_COMPETITION == $code)
		{
			$this->session->set_userdata('CASE_WRITING_COMPETITION','');
			$this->session->unset_userdata("CASE_WRITING_COMPETITION");
			return true;
		}
	}
	
	// function to change DRA admin password, Added by - Bhagwan Sahane, on 18-11-2016
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
					redirect(base_url().'case_writing_competition/login/changepassword');
				}
				
				$encPass = md5($new_pass);
				$curr_encrypass = md5(trim($current_pass));
				
				// get admin id -
				$this->UserData = $this->session->userdata('case_study_comp_admin');
				$case_study_comp_admin_id = $this->UserData['id'];
				
				$row = $this->master_model->getRecordCount('case_study_comp_admin', array('password' => $curr_encrypass, 'id' => $case_study_comp_admin_id));
				if($row == 0)
				{
					$this->session->set_flashdata('error','Current Password is Wrong.'); 
					redirect(base_url().'case_writing_competition/login/changepassword');
				}
				else
				{
					$input_array = array('password' => $encPass);
					$this->master_model->updateRecord('case_study_comp_admin', $input_array, array('id' => $case_study_comp_admin_id));
					
					log_dra_admin($log_title = "Admin Password Changed Successful", $log_message = serialize($input_array));
					
					$this->session->set_flashdata('success','Password Changed successfully.'); 
					redirect(base_url().'case_writing_competition/login/changepassword');
				}
			}
		}
		
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'case_writing_competition/Dashboard"><i class="fa fa-home"></i> Home</a></li>
					<li class="active">Change Password</li>
				</ol>';
		
		$this->load->view('case_writing_competition/login/change_password', $data);
	}
	
	public function Logout(){
		$sessionData = $this->session->userdata('case_study_comp_admin');
		$this->session->unset_userdata('case_study_comp_admin');
		redirect('case_writing_competition/');
	}
}