<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class InspectorLogin extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('LoginModel');	
	}
	public function index()
	{
		
		$data['error'] = '';
		//print_r($_POST['submit']);die;
		//if( !$this->session->userdata('FGet') ) {
			if(isset($_POST['submit'])) {
				//print_r($_POST); die;
				$this->form_validation->set_rules('username', 'Username', 'required');
				$this->form_validation->set_rules('password', 'Password', 'required');
				
				if($this->form_validation->run() == TRUE)
        {
					$val1=$_POST['val1'];		  
					$val2=$_POST['val2'];		  
					$val3=$_POST['val3'];
					$add_val= ($val1+$val2);

					//echo $add_val.'---'.$val3;die;
					
					if($add_val==$val3)
					{

						$aPost = $this->input->post();

						$aWhere = array('username' => $aPost['username'], 'password'=>md5($aPost['password']), 'is_active' => '1', 'is_delete' => '0');
						
						$this->db->select('id, inspector_name, inspector_mobile, inspector_email, inspector_designation, batch_online_offline_flag, username, password, is_active, is_delete');					
						$this->db->order_by('id','asc');
						
						$aUser=$this->master_model->getRecords('agency_inspector_master',$aWhere);
						//echo $this->db->last_query();die;
						//echo '---'; print_r($aUser); die;
						if(!empty($aUser[0]))
            {
							$this->session->set_userdata('inspector_id',$aUser[0]['id']);
							$this->session->set_userdata('inspector_username',$aUser[0]['username']);
							$this->session->set_userdata('inspector_name',$aUser[0]['inspector_name']);
							$this->session->set_userdata('dra_inspector', $aUser[0]);

							redirect('iibfdra/inspectorHome/dashboard');	
						} 
            else
            {
              $data['error'] = 'Invalid Credentials';
            }
					}
					else
					{
						$data['error'] = 'Please enter correct answer';
					}
				}
				else
				{
					//echo "test"; exit;
					$data['error'] = 'Invalid Credentials';
				}
			}
			
			$this->load->view('iibfdra/inspector_login',$data);
		/*}
		else
		{
			redirect(base_url().'iibfdra/inspectorHome/dashboard');
		}*/
	}
	
	public function check_captcha_dralogin($code) 
	{
		if(!isset($this->session->drainstlogincaptcha) && empty($this->session->drainstlogincaptcha))
		{
			redirect(base_url().'iibfdra/InspectorLogin/');
		}
		if($code == '' || $this->session->drainstlogincaptcha != $code )
		{
			$this->form_validation->set_message('check_captcha_dralogin', 'Invalid %s.'); 
			$this->session->set_userdata("drainsplogincaptcha", rand(1,100000));
			return false;
		}
		if($this->session->drainstlogincaptcha == $code)
		{
			$this->session->set_userdata('drainsplogincaptcha','');
			$this->session->unset_userdata("drainsplogincaptcha");
			return true;
		}
	}
	
	public function Logout(){
		//$this->session->unset_userdata('dra_institute');
    $this->session->set_userdata('inspector_id','');
    $this->session->set_userdata('inspector_username','');
    $this->session->set_userdata('inspector_name','');
    $this->session->set_userdata('dra_inspector', '');
		redirect('iibfdra/InspectorLogin');
	}

	// reload captcha functionality
	public function generatecaptchaajax()
	{
		
		$this->load->helper('captcha');
		$this->session->unset_userdata("drainstlogincaptcha");
		$this->session->set_userdata("drainstlogincaptcha", rand(1, 100000));
		$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data = $cap['image'];
		//$_SESSION["regcaptcha"] = $cap['word'];
		$this->session->set_userdata('drainsplogincaptcha', $cap['word']);
		echo $data;
	}
}
?>