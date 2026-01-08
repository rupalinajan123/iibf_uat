<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Moulogin extends CI_Controller {

public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');	
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->model('master_model');		
		$this->load->library('email');
		$this->load->model('Emailsending');
		//$this->load->model('chk_session');
		//$this->chk_session->chk_bank_login_session();
		//$this->load->library('OS_BR');
		if($this->session->userdata('memberdata'))
		{
			$this->session->unset_userdata('memberdata');
		}
	}
	
	##---------default userlogin (prafull)-----------##
	public function index()
	{	
		
		echo "<marquee width='auto' direction='right' height='50px' scrollamount='10'>
		<h3 style='color:red;' >As per the updated guidelines of BCBF, this link is deactivated for BCBF exams from 1st April 2024.<h3>
		</marquee>";
		$data=array();
		$data['error']='';

		if($this->session->userdata('institute_id')=='')
		{
			
			if(isset($_POST['submit']))
			{
				$config = array(
				array(
						'field' => 'bankname',
						'label' => 'Select Bank',
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
						'rules' => 'trim|required|callback_check_captcha_userlogin',
				),
			);
			         
			$this->form_validation->set_rules($config);
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('pass_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$encpass = $aes->encrypt($this->input->post('Password'));
			$dataarr=array(
				'institute_code'=> $this->input->post('bankname'),
				'password'=>$encpass,
				'accerdited_delete'=>'0'
			);
				if ($this->form_validation->run() == TRUE)
				{	
					$this->db->where('accerdited_delete', '0');
					$bank_info=$this->master_model->getRecords('bulk_accerdited_master',$dataarr);
					// echo $this->db->last_query();die;
					if(count($bank_info) > 0)
					{ 
						 if($bank_info[0]['accerdited_delete']==0) 
						  {
								$mysqltime=date("H:i:s");
								//code for separate admin for el
								$ins_code = $bank_info[0]['institute_code'];
								if ($bank_info[0]['is_admin']=='yes') {
									$ins_name_arr = explode('_', $bank_info[0]['institute_code']);
									$ins_code = $ins_name_arr[0];
								}
								$user_data=array('institute_id'=>$ins_code,
															'institute_name'=>$bank_info[0]['institute_name'],
															'is_admin'=>$bank_info[0]['is_admin'],
															'timer'=>base64_encode($mysqltime),
															'mou_flg'=> "1"
															);
								$this->session->set_userdata($user_data);
								$hostname = '';
								$app_server=explode('.',gethostname());if(isset($app_server[0])){$hostname =  $app_server[0];}
								$log_title ="Bulk login logs";
								$log_message = $hostname;
								$rId = $bank_info[0]['institute_name'];
								$regNo = $bank_info[0]['institute_name'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);

								redirect(base_url().'bulk/BulkApply/mouexamlist/');
						  }
						  else if($user_info[0]['accerdited_delete']==1)
						  {
								$data['error']='<span style="">This account is suspended</span>'; 
						  }
						 
				}
					else
					{
						$data['error']='<span style="">Invalid Credentials</span>';
					}
				}
				else
				{
					$data['validation_errors'] = validation_errors();
				}
			}

			/*$this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => base_url().'uploads/applications/',
						);
			$cap = create_captcha($vals);
			
			$data['image'] = $cap['image'];
			$data['code']=$cap['word'];
			$this->session->set_userdata('bankuserlogincaptcha', $cap['word']);*/
			$this->load->model('Captcha_model');                
      	    $captcha_img = $this->Captcha_model->generate_captcha_img('bankuserlogincaptcha');
      	    $data['image'] = $captcha_img;
			$data['banklist']=$this->master_model->getRecords('bulk_accerdited_master',array('accerdited_delete'=>0));
			$this->load->view('bulk/moulogin',$data); 
		}
		else
		{	
			redirect(base_url().'bulk/Bankdashboard/');
		}

	}
	public function get_inst_pass()
	{   
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789"; 
		echo $password = substr( str_shuffle( $chars ), 0, 8); 
		echo '</br>'; 
		echo md5($password); 
	} 
	##---------check captcha userlogin (prafull)-----------##
	public function check_captcha_userlogin($code) 
	{
		if(!isset($this->session->bankuserlogincaptcha) && empty($this->session->bankuserlogincaptcha))
		{
			redirect(base_url().'bulk/Moulogin/');
		}
		
		if($code == '' || $this->session->bankuserlogincaptcha != $code )
		{
			$this->form_validation->set_message('check_captcha_userlogin', 'Invalid %s.'); 
			$this->session->set_userdata("bankuserlogincaptcha", rand(1,100000));
			return false;
		}
		if($this->session->bankuserlogincaptcha == $code)
		{
			$this->session->set_userdata('bankuserlogincaptcha','');
			$this->session->unset_userdata("bankuserlogincaptcha");
			return true;
		}
	}
	
	
	//##---- reload captcha functionality
	public function generatecaptchaajax()
	{
		/*$this->load->helper('captcha');
		$this->session->unset_userdata("bankuserlogincaptcha");
		$this->session->set_userdata("bankuserlogincaptcha", rand(1, 100000));
		$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data = $cap['image'];
		$_SESSION["bankuserlogincaptcha"] = $cap['word'];
		echo $data;*/

		$this->load->model('Captcha_model');                
    $captcha_img = $this->Captcha_model->generate_captcha_img('bankuserlogincaptcha');
    echo $captcha_img;
	}
	
	
	##---------forget password (prafull)-----------##
	public function forgotpassword()
	{
		$data['page_title']='Forget Password';
		$data['pass_error']=$data['error']='';
		if(isset($_POST['btn_forget_pass']))
		{
			$this->form_validation->set_rules('memno','Membership No.','trim|required|xss_clean');
			if($this->form_validation->run())
			{
				$memno=$this->input->post('memno');
				$this->db->where('isactive','1');
				$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$memno));
				if(count($result)>0)
				{
				//generate random password
				$password=$this->generate_random_password();
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				 $key = $this->config->item('pass_key');
				 $aes = new CryptAES();
				 $aes->set_key(base64_decode($key));
				 $aes->require_pkcs5();
				 $encPass = $aes->encrypt($password);
		
				// update a password in db
				//$query=$this->master_model->updateRecord('member_registration',array('usrpassword'=>$encPass,'editedon'=>date('Y-m-d H:i:s'),'editedby'=>'Candidate'),array('regid'=>$result[0]['regid']));
				$query=$this->master_model->updateRecord('member_registration',array('usrpassword'=>$encPass),array('regid'=>$result[0]['regid']));
				$log_arr=array('regnumber'=>$memno,'usrpassword'=>$encPass,'editedon'=>date('Y-m-d H:i:s'),'editedby'=>'Candidate');
				logactivity($log_title ="Forget pass Member ", $log_message = serialize($log_arr));
				if($query)
				{
							$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
							$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'member_forgetpass'));
							$newstring1 = str_replace("#application_num#", "".$result[0]['regnumber']."",  $emailerstr[0]['emailer_text']);
							$newstring2= str_replace("#password#", "".$password."",  $newstring1);
							$newstring3= str_replace("#username#", "".$userfinalstrname."",  $newstring2);
							$final_str= str_replace("#url#", "".base_url()."",  $newstring3);
							
							$info_arr=array(
														'to'=>$result[0]['email'],
														'from'=>$emailerstr[0]['from'],
														'subject'=>$emailerstr[0]['subject'],
														'message'=>$final_str
													);
													
							if($this->Emailsending->mailsend($info_arr))
							{
								//$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
								 redirect(base_url().'bulk/Moulogin/forgetack/');
							}
							else
							{
								$this->session->set_flashdata('error','Error while sending email !!');
								 redirect(base_url().'bulk/Moulogin/mylogin');
							}					
					}
					}
				else
				{
							$this->session->set_flashdata('error_message','Invalid Membership/Registration No!');
						 	redirect(base_url().'bulk/Moulogin/forgotpassword/');
				}
			}
		}
		$this->load->view('forgetpass',$data);
	}
	
	 ##---------Genereate random password function (prafull)-----------##
	function generate_random_password($length = 8, $level = 2) // function to generate new password
	{
		list($usec, $sec) = explode(' ', microtime());
		srand((float) $sec + ((float) $usec * 100000));
		$validchars[1] = "0123456789abcdfghjkmnpqrstvwxyz";
		$validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$validchars[3] = "0123456789_!@#*()-=+abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#*()-=+";
		$password = "";
		$counter = 0;
		while ($counter < $length) {
		$actChar = substr($validchars[$level], rand(0, strlen($validchars[$level]) - 1), 1);
		if (!strstr($password, $actChar)) {
			$password .= $actChar;
				$counter++;
			}
		}
		return $password;
	}
	 
	 //## forget pass acknowledgment
	 public function forgetack()
	 {
		 
		$this->load->view('forgetacknowledgement');	
	}
	 
	##---------End user logout (prafull)-----------##//
	public function Logout(){
		$sessionData = $this->session->all_userdata();
		foreach($sessionData as $key =>$val){
			$this->session->unset_userdata($key);    
		}
		redirect(base_url().'bulk/Moulogin/');
	}
}
