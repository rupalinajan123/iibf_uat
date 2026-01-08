<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login_test extends CI_Controller {



public function __construct()

	{

		parent::__construct();

		$this->load->helper('master_helper');

		$this->load->model('master_model');		

	}

	

	##---------default userlogin (prafull)-----------##

	public function index()

	{	

		

	

			

		if($this->session->userdata('regid'))

		{

			redirect(base_url().'home/dashboard/');

		}

		$data=array();

		$data['error']='';

		if(isset($_POST['submit']))

		{

			$config = array(

			array(

					'field' => 'Username',

					'label' => 'Username',

					'rules' => 'trim|required'

			),

			array(

					'field' => 'Password',

					'label' => 'Password',

					'rules' => 'trim|required',

			),

		/*	array(

					'field' => 'code',

					'label' => 'Code',

					'rules' => 'trim|required|callback_check_captcha_userlogin',

			),*/

		);

		

		$this->form_validation->set_rules($config);

		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

		$key = $this->config->item('pass_key');

		$aes = new CryptAES();

		$aes->set_key(base64_decode($key));

		$aes->require_pkcs5();

		$encpass = $aes->encrypt($this->input->post('Password'));

			$dataarr=array(

				'regnumber'=> $this->input->post('Username'),

				'usrpassword'=>$encpass,

			);

			if ($this->form_validation->run() == TRUE)

			{

				$this->db->select('registrationtype,regid,regnumber,firstname,middlename,lastname,createdon,registrationtype,isactive,usrpassword');

				$where="(registrationtype='O' OR registrationtype='A' OR registrationtype='F')";

				$this->db->where($where);

				$this->db->where('isactive','1');

				

				$user_info=$this->master_model->getRecords('member_registration',$dataarr);

				if(count($user_info) > 0)

				{ 

					 if($user_info[0]['isactive']==1)

					  {

						  	$showlink = "no";	

							$mysqltime=date("H:i:s");

							$user_data=array('regid'=>$user_info[0]['regid'],

														'regnumber'=>$user_info[0]['regnumber'],

														'firstname'=>$user_info[0]['firstname'],

														'middlename'=>$user_info[0]['middlename'],

														'lastname'=>$user_info[0]['lastname'],

														'memtype'=>$user_info[0]['registrationtype'],

														'timer'=>base64_encode($mysqltime),

														'showlink'=>$showlink,

														'exam_name'=>$exam_name,

														'password'=>base64_encode($this->input->post('Password')));

					$this->session->set_userdata($user_data);

					redirect(base_url().'home/dashboard/');	

					  }

					  else if($user_info[0]['isactive']==0)

					  {

							$data['error']='<span style="">Invalid Credentials</span>'; 

					  }

					  else

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

		$cap = create_captcha($vals);*/

		$data['image'] = '';

		$data['code']='123';

		$this->session->set_userdata('userlogincaptcha', $cap['word']);

		$this->load->view('login',$data);

		



	}

	

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

								 redirect(base_url().'login/forgetack/');

							}

							else

							{

								$this->session->set_flashdata('error','Error while sending email !!');

								 redirect(base_url().'login/mylogin');

							}					

						

					}

					}

				else

				{

							$this->session->set_flashdata('error_message','Invalid Membership/Registration No!');

						 	redirect(base_url().'login/forgotpassword/');

				}

			}

		}

		$this->load->view('forgetpass',$data);

	}

	

}



