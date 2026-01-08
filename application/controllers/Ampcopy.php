<?php
defined('BASEPATH') OR exit('No direct script access allowed'); header("Access-Control-Allow-Origin: *");
class Ampcopy extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');	
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->helper('general_helper');
		$this->load->model('Master_model');		
		$this->load->library('email');
		$this->load->helper('date');
		$this->load->library('email');
		$this->load->model('Emailsending');
		$this->load->model('log_model');
		$this->load->model('Ampmodel');
		
		/*error_reporting(E_ALL);
		ini_set('display_errors',1);*/
		//$this->load->model('chk_session');
	  	//$this->chk_session->checklogin();
		//$this->load->model('chk_session');
	    //$this->chk_session->chk_member_session();
	}
	 
	public function self(){
		$photo_error = '';
		$sign_error = '';
		
		if($this->input->post('form_type')=='amp_form'){
			$aPost = $this->input->post();
			//echo '<pre>';print_r($aPost);die;
			if($this->is_valid('self')){
				$aPost['till_present'] = isset($aPost['till_present']) ? 1 : 0;
				$aPost['agree'] = isset($aPost['agree']) ? 1 : 0;
				$insert_info = array(
								'sponsor'=>'self',
								'name'=>$aPost['name'],
								'dob'=>$aPost['dob'],
								//'regnumber'=>$aPost['regnumber'],
								'bday'=>$aPost['bday'],
								'bmonth'=>$aPost['bmonth'],
								'byear'=>$aPost['byear'],
								'address1'=>$aPost['address1'],
								'address2'=>$aPost['address2'],
								'address3'=>$aPost['address3'],
								'address4'=>$aPost['address4'],
								'pincode_address'=>$aPost['pincode_address'],
								'std_code'=>$aPost['std_code'],
								'phone_no'=>$aPost['phone_no'],
								'mobile_no'=>$aPost['mobile_no'],
								'email_id'=>$aPost['email_id'],
								'alt_email_id'=>$aPost['alt_email_id'],
								'graduation'=>$aPost['graduation'],
								'post_graduation'=>$aPost['post_graduation'],
								'special_qualification'=>$aPost['special_qualification'],
								'name_employer'=>$aPost['name_employer'],
								'position'=>$aPost['position'],
								'work_from_month'=>$aPost['work_from_month'],
								'work_from_year'=>$aPost['work_from_year'],
								'work_to_month'=>$aPost['work_to_month'],
								'work_to_year'=>$aPost['work_to_year'],
								'till_present'=>$aPost['till_present'],
								'work_experiance'=>$aPost['work_experiance'],
								'payment'=>$aPost['payment'],
								'agree'=>$aPost['agree']
							   );
							   
				//$last_id = $this->master_model->insertRecord('amp_candidates',$insert_info,true);
				
					
					if(isset($_FILES['photograph']['name']) &&($_FILES['photograph']['name']!='')){
						$img = "photograph";
						$tmp_photonm = strtotime('now').rand(0,100);
						$new_filename = 'photo_'.$tmp_photonm;
						$config = array('upload_path'=>'./uploads/amp/photograph',
									'allowed_types'=>'jpg|jpeg',
									'file_name'=>$new_filename,
									'max_size'=>50);
									
						$this->upload->initialize($config);
						$size = @getimagesize($_FILES['photograph']['tmp_name']);
						if($size){
							if($this->upload->do_upload($img)){
								$dt=$this->upload->data();
								$insert_info['photograph'] = $dt['file_name'];
							}else{
								$photo_error = $this->upload->display_errors();
							}
						}else{
							$photo_error = 'The filetype you are attempting to upload is not allowed';
						}
					}
					
					if(isset($_FILES['signature']['name']) &&($_FILES['signature']['name']!='')){
						$img = "signature";
						$tmp_photonm = strtotime('now').rand(0,100);
						$new_filename = 'sign_'.$tmp_photonm;
						$config = array('upload_path'=>'./uploads/amp/signature',
									'allowed_types'=>'jpg|jpeg',
									'file_name'=>$new_filename,
									'max_size'=>50);
									
						$this->upload->initialize($config);
						$size = @getimagesize($_FILES['signature']['tmp_name']);
						if($size){
							if($this->upload->do_upload($img)){
								$dt=$this->upload->data();
								$insert_info['signature'] = $dt['file_name'];
							}else{
								$sign_error = $this->upload->display_errors();
							}
						}else{
							$sign_error = 'The filetype you are attempting to upload is not allowed';
						}
					}
					
					if($sign_error=='' && $photo_error==''){
						$this->session->set_userdata('insertdata', $insert_info);
						redirect(base_url().'amp/payment');
					}				
			}
		}
		
		$this->load->helper('captcha');
		$this->session->unset_userdata("regampcaptcha");
		$this->session->set_userdata("regampcaptcha", rand(1, 100000));
		$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		
		$this->session->set_userdata('regampcaptcha', $cap['word']);
		$data=array('middle_content'=>'amp/amp_self_sponsor','image' => $cap['image'],'photo_error'=>$photo_error,'sign_error'=>$sign_error,'sponsor'=>'Self');
		$this->load->view('amp/common_view_fullwidth',$data);
	}
	
	public function bank(){
		$photo_error = '';
		$sign_error = '';
		
		if($this->input->post('form_type')=='amp_form'){
			$aPost = $this->input->post();
			if($this->is_valid('bank')){
				//echo '<pre>';print_r($aPost);die;
				$aPost['till_present'] = isset($aPost['till_present']) ? 1 : 0;
				$aPost['agree'] = isset($aPost['agree']) ? 1 : 0;
				$insert_info = array(
								'sponsor_bank_name'=>$aPost['sponsor_bank_name'],
								'sponsor_email'=>$aPost['sponsor_email'],
								'sponsor_contact_person'=>$aPost['sponsor_contact_person'],
								'sponsor_contact_designation'=>$aPost['sponsor_contact_designation'],
								'sponsor_contact_std'=>$aPost['sponsor_contact_std'],
								'sponsor_contact_phone'=>$aPost['sponsor_contact_phone'],
								'sponsor_contact_mobile'=>$aPost['sponsor_contact_mobile'],
								'sponsor_contact_email'=>$aPost['sponsor_contact_email'],
								'sponsor'=>'bank',
								'name'=>$aPost['name'],
								'dob'=>$aPost['dob'],
								//'regnumber'=>$aPost['regnumber'],
								'bday'=>$aPost['bday'],
								'bmonth'=>$aPost['bmonth'],
								'byear'=>$aPost['byear'],
								'address1'=>$aPost['address1'],
								'address2'=>$aPost['address2'],
								'address3'=>$aPost['address3'],
								'address4'=>$aPost['address4'],
								'pincode_address'=>$aPost['pincode_address'],
								'std_code'=>$aPost['std_code'],
								'phone_no'=>$aPost['phone_no'],
								'mobile_no'=>$aPost['mobile_no'],
								'email_id'=>$aPost['email_id'],
								'alt_email_id'=>$aPost['alt_email_id'],
								'graduation'=>$aPost['graduation'],
								'post_graduation'=>$aPost['post_graduation'],
								'special_qualification'=>$aPost['special_qualification'],
								'name_employer'=>$aPost['name_employer'],
								'position'=>$aPost['position'],
								'work_from_month'=>$aPost['work_from_month'],
								'work_from_year'=>$aPost['work_from_year'],
								'work_to_month'=>$aPost['work_to_month'],
								'work_to_year'=>$aPost['work_to_year'],
								'till_present'=>$aPost['till_present'],
								'work_experiance'=>$aPost['work_experiance'],
								'payment'=>$aPost['payment'],
								'agree'=>$aPost['agree']
							   );
									
					if(isset($_FILES['photograph']['name']) &&($_FILES['photograph']['name']!='')){
						$img = "photograph";
						$tmp_photonm = strtotime('now').rand(0,100);
						$new_filename = 'photo_'.$tmp_photonm;
						$config = array('upload_path'=>'./uploads/amp/photograph',
									'allowed_types'=>'jpg|jpeg',
									'file_name'=>$new_filename,
									'max_size'=>50);
									
						$this->upload->initialize($config);
						$size = @getimagesize($_FILES['photograph']['tmp_name']);
						if($size){
							if($this->upload->do_upload($img)){
								$dt=$this->upload->data();
								$update_info = array('photograph'=>$dt['file_name']);
								$insert_info['photograph'] = $dt['file_name'];
							}else{
								$photo_error = $this->upload->display_errors();
							}
						}else{
							$photo_error = 'The filetype you are attempting to upload is not allowed';
						}
					}
					
					if(isset($_FILES['signature']['name']) &&($_FILES['signature']['name']!='')){
						$img = "signature";
						$tmp_photonm = strtotime('now').rand(0,100);
						$new_filename = 'sign_'.$tmp_photonm;
						$config = array('upload_path'=>'./uploads/amp/signature',
									'allowed_types'=>'jpg|jpeg',
									'file_name'=>$new_filename,
									'max_size'=>50);
									
						$this->upload->initialize($config);
						$size = @getimagesize($_FILES['signature']['tmp_name']);
						if($size){
							if($this->upload->do_upload($img)){
								$dt=$this->upload->data();
								$update_info = array('signature'=>$dt['file_name']);
								$insert_info['signature'] = $dt['file_name'];
							}else{
								$sign_error = $this->upload->display_errors();
							}
						}else{
							$sign_error = 'The filetype you are attempting to upload is not allowed';
						}
					}
				if($sign_error=='' && $photo_error==''){
					$this->session->set_userdata('insertdata', $insert_info);
					redirect(base_url().'amp/payment');
				}
			}
		}
		
		$this->load->helper('captcha');
		$this->session->unset_userdata("regampcaptcha");
		$this->session->set_userdata("regampcaptcha", rand(1, 100000));
		$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		
		$this->session->set_userdata('regampcaptcha', $cap['word']);
		$data=array('middle_content'=>'amp/amp_bank_sponsor','image' => $cap['image'],'photo_error'=>$photo_error,'sign_error'=>$sign_error,'sponsor'=>'Bank');
		$this->load->view('amp/common_view_fullwidth',$data);
	}
	
	//validation rules set
	public function is_valid($sponsor){
		$this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
		
		$config = array(
					array(
						'field' => 'name',
						'label' => 'Name',
						'rules' => 'required|alpha_numeric_spaces'
					),array(
						'field' => 'membershipno',
						'label' => 'Membership No.',
						'rules' => 'max_length[11]|numeric'
					),array(
						'field' => 'dob',
						'label' => 'Date of Birth',
						'rules' => 'required'
					),array(
						'field' => 'bday',
						'label' => 'Birbday Day',
						'rules' => 'required'
					),array(
						'field' => 'bmonth',
						'label' => 'Birbday Month',
						'rules' => 'required'
					),array(
						'field' => 'byear',
						'label' => 'Birbday Year',
						'rules' => 'required'
					),array(
						'field' => 'address1',
						'label' => 'Office/Residential Address',
						'rules' => 'required'
					),array(
						'field' => 'pincode_address',
						'label' => 'Pincode',
						'rules' => 'required|exact_length[6]|numeric'
					),array(
						'field' => 'std_code',
						'label' => 'STD code',
						'rules' => 'max_length[5]|numeric'
					),array(
						'field' => 'phone_no',
						'label' => 'Phone No.',
						'rules' => 'max_length[8]|numeric'
					),array(
						'field' => 'mobile_no',
						'label' => 'Mobile No.',
						'rules' => 'required|exact_length[10]|numeric|callback_check_unique_mobile'
					),array(
						'field' => 'email_id',
						'label' => 'Candidates Email Id',
						'rules' => 'required|max_length[50]|valid_email|callback_check_unique_email'
					),array(
						'field' => 'alt_email_id',
						'label' => 'Candidates Alternate Email Id',
						'rules' => 'max_length[50]|valid_email'
					),array(
						'field' => 'name_employer',
						'label' => 'Name of the Employer',
						'rules' => 'max_length[30]|alpha_numeric_spaces'
					),array(
						'field' => 'position',
						'label' => 'Position',
						'rules' => 'max_length[30]|alpha_numeric_spaces'
					),array(
						'field' => 'work_from_year',
						'label' => 'Work Experience Period from Year',
						'rules' => 'numeric'
					),array(
						'field' => 'work_to_year',
						'label' => 'Work Experience Period to Year',
						'rules' => 'numeric|callback_periodcheck'
					),array(
						'field' => 'work_experiance',
						'label' => 'Total Experience in month',
						'rules' => 'max_length[3]|numeric'
					),array(
						'field' => 'hiddenphoto',
						'label' => 'Photograph',
						'rules' => 'required'
					),array(
						'field' => 'hiddenscansignature',
						'label' => 'Signature',
						'rules' => 'required'
					),array(
						'field' => 'payment',
						'label' => 'Payment Option',
						'rules' => 'required'
					),array(
						'field' => 'agree',
						'label' => 'I Agree',
						'rules' => 'required'
					),array(
						'field' => 'captcha',
						'label' => 'Captcha',
						'rules' => 'required|callback_check_captcha_userreg'
					)
				);

		//validation rules set for bank sponser
		if($sponsor=='bank'){
			$config[] = array(
							'field' => 'sponsor_bank_name',
							'label' => 'Name Of Sponsored Bank',
							'rules' => 'required|max_length[30]|alpha_numeric_spaces'
						);
			$config[] = array(
							'field' => 'sponsor_email',
							'label' => 'Name Of Department Email',
							'rules' => 'required|max_length[50]|valid_email'
						);
			$config[] = array(
							'field' => 'sponsor_contact_person',
							'label' => 'Contact person name',
							'rules' => 'required|max_length[40]|alpha_numeric_spaces'
						);
			$config[] = array(
							'field' => 'sponsor_contact_designation',
							'label' => 'Contact person Designation',
							'rules' => 'required|max_length[50]|alpha_numeric_spaces'
						);
			$config[] = array(
							'field' => 'sponsor_contact_std',
							'label' => 'Contact person STD code',
							'rules' => 'max_length[5]|numeric'
						);
			$config[] = array(
							'field' => 'sponsor_contact_phone',
							'label' => 'Contact person Phone No.',
							'rules' => 'max_length[8]|numeric'
						);
			$config[] = array(
							'field' => 'sponsor_contact_mobile',
							'label' => 'Contact person Mobile number',
							'rules' => 'required|max_length[10]|numeric'
						);
			$config[] = array(
							'field' => 'sponsor_contact_email',
							'label' => 'Contact person Email id',
							'rules' => 'required|max_length[50]|valid_email'
						);
		}
		
		$this->form_validation->set_rules($config);
		
		if($this->form_validation->run() == FALSE){
			return false;
        }
		
		return true;
	}
	
	//work experiance period check
	public function periodcheck(){
		
		if($_POST['work_from_year']!='' && $_POST['work_from_month']!='' && $_POST['work_to_month']!='' && $_POST['work_to_year']!=''){
			$from =  strtotime($_POST['work_from_year'].'-'.$_POST['work_from_month'].'-'.'1');
			$to =  strtotime($_POST['work_to_year'].'-'.$_POST['work_to_month'].'-'.'1');
			if($from <= $to){
				return true;
			}else{
				$this->form_validation->set_message('periodcheck', 'Work experience Period to must be greater than Period From.'); 
				return false;
			}
		}else{
			return true;
		}
	}
	
	//call back for check unique email for candidate
	public function check_unique_email($email){
		
		//$sql = 'select * from amp_candidates where email_id = '.$this->db->escape($email).' and isactive=\'1\'';
		$result = $this->master_model->getRecords('amp_candidates',array('email_id'=>$email,'isactive'=>'1'));
		//$result = $this->db->query($sql);
		if(count($result) > 0){
			$this->form_validation->set_message('check_unique_email', 'Candidate Email ID already register.'); 
			return false;
		}else{
			return true;
		}
	}
	
	public function check_unique_mobile($mobile){
		
		$result = $this->master_model->getRecords('amp_candidates',array('mobile_no'=>$mobile,'isactive'=>'1'));
		//$result = $this->db->query($sql);
		if(count($result ) > 0){
			$this->form_validation->set_message('check_unique_mobile', 'Candidate Mobile Number already register.'); 
			return false;
		}else{
			return true;
		}
	}
	
	//call back for check captcha server side
	public function check_captcha_userreg($code) 
	{
		//return true;
		if($code == '' || $_SESSION["regampcaptcha"] != $code )
		{
			$this->form_validation->set_message('check_captcha_userreg', 'Invalid %s.'); 
			return false;
		}
		if($_SESSION["regampcaptcha"] == $code)
		{
			return true;
		}
	}
	
	// reload captcha functionality
	public function generatecaptchaajax()
	{
		$this->load->helper('captcha');
		$this->session->unset_userdata("regampcaptcha");
		$this->session->set_userdata("regampcaptcha", rand(1, 100000));
		$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',);
		$cap = create_captcha($vals);
		$data = $cap['image'];
		$this->session->set_userdata("regampcaptcha", $cap['word']);
		//echo $this->session->userdata("regampcaptcha");
		echo $data;
		
	}
	
	//sending mails this function need membership number
	public function send_mail($membershipno){
		$aCandidate = $this->master_model->getRecords('amp_candidates',array('regnumber'=>$membershipno));
		$emailerstr = $this->master_model->getRecords('emailer',array('emailer_name'=>'amp_reg'));
		
		$newstring1 = str_replace("#username#", "".$aCandidate[0]['name']."",  $emailerstr[0]['emailer_text']);
		$newstring2 = str_replace("#membershipno#", "".$aCandidate[0]['regnumber']."",  $newstring1);
		$newstring3 = str_replace("#name#", "".$aCandidate[0]['name']."",  $newstring2);
		$newstring4 = str_replace("#email#", "".$aCandidate[0]['email_id']."",  $newstring3);
		$newstring5 = str_replace("#transaction#", ""."TRANSACTION ID"."",  $newstring4);
		$newstring6 = str_replace("#amount#", "".$aCandidate[0]['payment']."",  $newstring5);
		$newstring7 = str_replace("#Transaction_status#", ""."Status"."",  $newstring6);
		$final_str = str_replace("#date#", "".date('Y-m-d h:s:i')."",  $newstring7);
		$info_arr = array('to'=>$aCandidate[0]['email_id'],'from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'],'message'=>$final_str);
		
		$this->Emailsending->mailsend($info_arr);
	}
	
	//sending SMS this function need membership number
	public function send_sms($membershipno){
		$aCandidate = $this->master_model->getRecords('amp_candidates',array('membershipno'=>$membershipno));
		$emailerstr = $this->master_model->getRecords('emailer',array('emailer_name'=>'amp_reg'));
		
		//$sms_newstring = str_replace("#application_num#", "".$applicationNo."",  $emailerstr[0]['sms_text']);
		$sms_final_str= $emailerstr[0]['sms_text'];
		$this->master_model->send_sms($aCandidate[0]['mobile_no'],$sms_final_str);
	}
	
	//candidate payment after form submit
	public function payment(){
		if(!isset($this->session->userdata['insertdata']))
			redirect(base_url().'amp/self');
		
		if($this->input->post('form_type')=='pay_form'){
			redirect(base_url().'amp/insert_amp_data');
		}
		
		$data=array('middle_content'=>'amp/payment');
		$this->load->view('amp/common_view_fullwidth',$data);
	}
	
		//insert candidate data call from payment function
		public function insert_amp_data(){
		$insertdata = $this->session->userdata('insertdata');
		$last_id = $this->master_model->insertRecord('amp_candidates',$insertdata,true);
		
		$userarr=array('amp_id'=>$last_id);
		$this->session->set_userdata('ampmemberdata', $userarr); 
		
		//create log activity
		$log_title ="Candidate registration";
		$log_message = serialize($insertdata);
		$this->Ampmodel->create_log($log_title, $log_message);
		
		redirect(base_url()."Amp/make_payment");		
				
					
		
					
		/*$this->send_mail($membershipno);
		$this->send_sms($membershipno);*/
		
		
		
		//redirect(base_url().'amp/thankyou');
	}
	
	//Final page with session clear
	public function thankyou(){
		$this->session->unset_userdata("insertdata");
		$data=array('middle_content'=>'amp/thankyou');
		$this->load->view('amp/common_view_fullwidth',$data);
	}
	
	//search candidate for installment payment
	public function login(){
		$aCandidate = array();
		if($this->input->post('form_type')=='search_form'){
			$this->form_validation->set_rules('searchStr','Enter Name or Membership no.','trim|required|xss_clean');
			if($this->form_validation->run()==TRUE){
				$searchStr = $this->input->post('searchStr');
				$wherecondition= "(name LIKE '%$searchStr%' OR regnumber = '$searchStr')";
				$this->db->where($wherecondition);
				//$this->db->or_where('regnumber',$searchStr);
				//$this->db->like('name',$searchStr);
				
				$aCandidate = $this->master_model->getRecords('amp_candidates',array('isactive'=>'1'));
				//echo $this->db->last_query();exit;
			}
		}
		$data=array('middle_content'=>'amp/login','aCandidate'=>$aCandidate);
		$this->load->view('amp/common_view_fullwidth',$data);
	}
	
	//candidate details and installment
	public function installment($membershipno = 0){
		$aCandidate = $this->master_model->getRecords('amp_candidates',array('regnumber'=>$membershipno));
		if(empty($aCandidate)){
			redirect(base_url().'amp/self');
		}
			
			if($this->input->post('form_type')=='installment_form'){
				
				//$update_info = array('payment'=>$this->input->post('payment'));
				
				$userarr=array('amp_id'=>$aCandidate[0]['id']);
				$this->session->set_userdata('ampmemberdata', $userarr); 
				
				$userarr=array('payment'=>$this->input->post('payment'));
				$this->session->set_userdata('insertdata', $userarr); 
				
				redirect(base_url()."Amp/make_payment");	
		
				//$this->master_model->updateRecord('amp_candidates',$update_info,array('regnumber'=>$membershipno));
				
				//log create for installment
			/*	$log_title ="Installment payment";
				$update_info['membershipno'] = $membershipno;
				$log_message = serialize($update_info);
				$this->Ampmodel->create_log($log_title, $log_message);
				
				redirect(base_url().'amp/thankyou');*/
			}
			
		$data=array('middle_content'=>'amp/installment','aCandidate'=>$aCandidate);
		$this->load->view('amp/common_view_fullwidth',$data);
	}
	
	
	//code done by prafull
	public function make_payment() {
		$flag=1;
		// TO do:
		// Validate reg no in DB
		//$_REQUEST['regno'] = "ODExODU5OTE1";
		//$regno = base64_decode($_REQUEST['regno']);
		
		$regno = $this->session->userdata['ampmemberdata']['amp_id'];
		
		//$valcookie= register_get_cookie();
		
		/*if($valcookie)
		{
			$regid= $valcookie;
			//$regid= '57';
			$checkuser=$this->master_model->getRecords('amp_candidates',array('id'=>$regno,'regnumber !='=>'','isactive !='=>'0'));
			if(count($checkuser)>0)
			{
				delete_cookie('regid');
				redirect('http://iibf.org.in');
			}
			else
			{
				$checkpayment=$this->master_model->getRecords('amp_payment_transaction',array('ref_id'=>$regno,'status'=>'2'));
				if(count($checkpayment) > 0)
				{
					///$datearr=explode(' ',$checkpayment[0]['date']);
					$endTime = date("Y-m-d H:i:s",strtotime("+20 minutes",strtotime($checkpayment[0]['date'])));
					$current_time= date("Y-m-d H:i:s");
					if(strtotime($current_time)<=strtotime($endTime))
					{
						$flag=0;
					}
					else
					{
						delete_cookie('regid');
						redirect('http://iibf.org.in');
					}
				}
				else
				{
					$flag=1;
					delete_cookie('regid');
					redirect('http://iibf.org.in');
				}
			}	
		}*/
		
		if(isset($_POST['processPayment']) && $_POST['processPayment'])
		{
			$payment_option=0;
			//setting cookie for tracking multiple payment scenario
			//register_set_cookie($regno);
			
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$merchIdVal = $this->config->item('sbi_merchIdVal');
			$AggregatorId = $this->config->item('sbi_AggregatorId');
			
			$pg_success_url = base_url()."Ampcopy/sbitranssuccess";
			$pg_fail_url    = base_url()."Ampcopy/sbitransfail";
			
			
			if(isset($this->session->userdata['insertdata']['payment']))
			{
				if($this->session->userdata['insertdata']['payment']=='full')
				{
					$amount = $this->config->item('amp_fullpay_amount');
					$payment_option=4;
				}
				else if($this->session->userdata['insertdata']['payment']=='first')
				{
					$amount = $this->config->item('amp_first_installment');
					$payment_option=1;
				}
				else if($this->session->userdata['insertdata']['payment']=='second')
				{
					$amount = $this->config->item('amp_second_installment');
					$payment_option=2;
				}	
				
				else if($this->session->userdata['insertdata']['payment']=='third')
				{
					$amount = $this->config->item('amp_third_installment');
					$payment_option=3;
				}	
			}
			else
			{
				redirect(base_url().'Amp/self');
			}
		
		$amount =1;
			//$MerchantOrderNo = generate_order_id("reg_sbi_order_id");
			
			 
			
			// Create transaction
			$insert_data = array(
				'gateway'     => "sbiepay",
				'amount'      => $amount,
				'date'        => date('Y-m-d H:i:s'),
				'ref_id'	  =>  $regno,	
				'description' => "AMP Membership Registration",
				'pay_type'    => 1,
				'status'      => 2,
				//'receipt_no'  => $MerchantOrderNo,
				'pg_flag'=>'AMP',
				'payment_option'=>$payment_option
				//'pg_other_details'=>$custom_field
			);
		
			$pt_id = $this->master_model->insertRecord('amp_payment_transaction',$insert_data,true);
			
			$MerchantOrderNo = amp_sbi_order_id($pt_id);
			
			//Member registration
			 //Ref1 = orderid
			 //Ref2 = iibfamp
			 //Ref3 = primary key of amp member registation table
			 //Ref4 = amp+ registration year month ex (amp201704)
			$yearmonth=date('Ym');
			$custom_field = $MerchantOrderNo."^iibfamp^".$regno."^".'amp'.$yearmonth;
			
			// update receipt no. in payment transaction -
			$update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
			$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('id'=>$pt_id));
			
			$MerchantCustomerID = $regno;
			
			$data["pg_form_url"] = $this->config->item('sbi_pg_form_url'); // SBI ePay form URL
			$data["merchIdVal"]  = $merchIdVal;
			
			/*
			requestparameter=
			MerchantId | OperatingMode | MerchantCountry | MerchantCurrency |
PostingAmount | OtherDetails | SuccessURL | FailURL | AggregatorId | MerchantOrderNo |
MerchantCustomerID | Paymode | Accesmedium | TransactionSource
			Ex.
			requestparameter
=1000003|DOM|IN|INR|2|Other|https://test.sbiepay.coom/secure/fail.jsp|SBIEPAY|2|2|NB|ONLINE|ONLINE
			*/
			$EncryptTrans = $merchIdVal."|DOM|IN|INR|".$amount."|".$custom_field."|".$pg_success_url."|".$pg_fail_url."|".$AggregatorId."|".$MerchantOrderNo."|".$MerchantCustomerID."|NB|ONLINE|ONLINE";
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			
			$EncryptTrans = $aes->encrypt($EncryptTrans);
			
			$data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
			$this->load->view('pg_sbi_form',$data);
		}
		else
		{
			//$data["regno"] = $_REQUEST['regno'];
			$this->load->view('pg_sbi/make_payment_page');
		}
		
	}
	
	public function sbitranssuccess()
	{
		//delete_cookie('regid');
		//print_r($_REQUEST['encData']);
//$_REQUEST['encData']='6N7QR1B/Kz1O3Q+GWcfdJcY7NhHGxCp8SbDgjXOc3kJkWolrLAg6NifwqMm9VBAzwCyNY2JWDt1v4HcN8yFAAw36jyZ0oopYmlVFX06tNlMHAWqLGS+S3EGynsHAPpxb7pQsObd6nFBvXEC2MVrsk3tn65zCjlxQ7+vg4Ryv3ZCGDC1Y+jicNwfvNBUOvAdvCyCe0lpM8y/uo+NzFQIybA==';		
		if (isset($_REQUEST['encData']))
		{
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$encData = $aes->decrypt($_REQUEST['encData']);
			$responsedata = explode("|",$encData);
			$MerchantOrderNo = $responsedata[0]; 
			$transaction_no  = $responsedata[1];
			if (isset($_REQUEST['merchIdVal']))
			{
				$merchIdVal = $_REQUEST['merchIdVal'];
			}
			if (isset($_REQUEST['Bank_Code']))
			{
				$Bank_Code = $_REQUEST['Bank_Code'];
			}
			if (isset($_REQUEST['pushRespData']))
			{
				$encData = $_REQUEST['pushRespData'];
			}
			//Sbi B2B callback
			//check sbi payment status with MerchantOrderNo 
			$q_details = sbiqueryapi($MerchantOrderNo);
			if ($q_details)
			{
				if ($q_details[2] == "SUCCESS")
				{
					$get_user_regnum_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status, 	payment_option ');
					//check user payment status is updated by s2s or not
					if($get_user_regnum_info[0]['status']==2)
					{
						if($get_user_regnum_info[0]['payment_option']==1 || $get_user_regnum_info[0]['payment_option']==4)
						{
							$reg_id=$get_user_regnum_info[0]['ref_id'];
							//$applicationNo = generate_mem_reg_num();
							//Get membership number from 'amp_membershipno' and update in 'amp_candidates'
							$applicationNo =generate_amp_memreg($reg_id);
							//update amp registration table
							$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
							$this->master_model->updateRecord('amp_candidates',$update_mem_data,array('id'=>$reg_id));
							//get user information...
							//$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id),'name,regnumber,email_id,photograph,signature,mobile_no,sponsor');
							$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id));
							
							$update_data = array('member_regnumber' => $applicationNo,'sponsor'=>$user_info[0]['sponsor'],'transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
							$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							//get payment details
							
							//Query to get Payment details	
							$payment_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$user_info[0]['regnumber']),'transaction_no,date,amount');
					
							$upd_files = array();
							$photo_file = 'p_'.$applicationNo.'.jpg';
							$sign_file = 's_'.$applicationNo.'.jpg';
							$proof_file = 'pr_'.$applicationNo.'.jpg';
							
							if(@ rename("./uploads/amp/photograph/".$user_info[0]['scannedphoto'],"./uploads/amp/photograph/".$photo_file))
							{	$upd_files['scannedphoto'] = $photo_file;	}
							
							if(@ rename("./uploads/amp/signature/".$user_info[0]['scannedsignaturephoto'],"./uploads/amp/signature/".$sign_file))
							{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
					
							if(count($upd_files)>0)
							{
								$this->master_model->updateRecord('amp_candidates',$upd_files,array('id'=>$reg_id));
							}
						
					
					//email to user
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer'));
					if(count($emailerstr) > 0)
					{
						$username=$user_info[0]['name'];
						$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
						$newstring1 = str_replace("#REG_NUM#", "".$user_info[0]['regnumber']."",  $emailerstr[0]['emailer_text']);
						$newstring2 = str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring1);
						$newstring3 = str_replace("#EMAIL#", "".$user_info[0]['email_id']."",  $newstring2);
						$newstring4 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $newstring3);
						$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $newstring4);
						$newstring6 = str_replace("#STATUS#", "Transaction Successful",  $newstring5);
						$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring6);
					
						
						$info_arr=array('to'=>$user_info[0]['email_id'],
						'from'=>$emailerstr[0]['from'],
						'subject'=>$emailerstr[0]['subject'],
						'message'=>$final_str,
						//'bcc'=>'skdatta@iibf.org.in,kavan@iibf.org.in'
						);
					//$this->send_mail($applicationNo);
					//$this->send_sms($applicationNo);
					
					//Manage Log
					$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
					
					$this->log_model->logamptransaction("sbiepay", $pg_response, $responsedata[2]);
					
					$sms_newstring = str_replace("#fee#", "".$payment_info[0]['amount']."",  $emailerstr[0]['sms_text']);
					$sms_newstring1 = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring);
					$sms_final_str = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $sms_newstring1);
					$this->master_model->send_sms($user_info[0]['mobile_no'],$sms_final_str);	
					$this->Emailsending->mailsend($info_arr);
					
					//email send to sk datta and kavan for self sponsor
					if($user_info[0]['sponsor']=='self'){
						$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_self'));
						if(count($emailerSelfStr) > 0){
							$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
							
							if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
							
							if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
							
							if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
							
							if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = ''; }
							
							if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
							
							if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
							
							$selfstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerSelfStr[0]['emailer_text']);
							$selfstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr1);
							$selfstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr2);
							$selfstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $selfstr3);
							$selfstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $selfstr4);
							$selfstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $selfstr5);
							$selfstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $selfstr6);
							$selfstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $selfstr7);
							$selfstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $selfstr8);
							$selfstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $selfstr9);
							$selfstr11 = str_replace("#phone_no#", "".$phone_no."",  $selfstr10);
							$selfstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $selfstr11);
							$selfstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $selfstr12);
							$selfstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $selfstr13);
							$selfstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $selfstr14);
							$selfstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $selfstr15);
							$selfstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $selfstr16);
							$selfstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $selfstr17);
							$selfstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $selfstr18);
							$selfstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $selfstr19);
							$selfstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $selfstr20);
							$selfstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $selfstr21);
							$selfstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $selfstr22);
							$selfstr24 = str_replace("#till_present#", "".$till_present."",  $selfstr23);
							$selfstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $selfstr24);
							$selfstr26 = str_replace("#payment#", "".$payment."",  $selfstr25);
							$selfstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $selfstr26);
							$selfstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $selfstr27);
							$selfstr29 = str_replace("#STATUS#", "Transaction Successful",  $selfstr28);
							$selfstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $selfstr29);
							$final_selfstr = str_replace("#sponsor#", "".$sponsor."",  $selfstr30);
							
							$self_mail_arr = array(
							//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in',
							'to'=>'kumartupe@gmail.com,raajpardeshi@gmail.com',
							'from'=>$emailerSelfStr[0]['from'],
							'subject'=>$emailerSelfStr[0]['subject'],
							'message'=>$final_selfstr,
							);
							
							$this->Emailsending->mailsend($self_mail_arr);
						}
					}
					
					//email send to sk datta and kavan for bank sponsor
					if($user_info[0]['sponsor']=='bank'){
						$emailerBankStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_bank'));
						if(count($emailerBankStr) > 0){
							$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
							
							if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
							
							if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
							
							if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
							
							if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = ''; }
							
							if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
							
							if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
							
							if($user_info[0]['sponsor_contact_phone']!=0){ $sponsor_contact_phone = $user_info[0]['sponsor_contact_phone']; }else{ $sponsor_contact_phone = ''; }
							
							$bankstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerBankStr[0]['emailer_text']);
							$bankstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr1);
							$bankstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr2);
							$bankstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $bankstr3);
							$bankstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $bankstr4);
							$bankstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $bankstr5);
							$bankstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $bankstr6);
							$bankstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $bankstr7);
							$bankstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $bankstr8);
							$bankstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $bankstr9);
							$bankstr11 = str_replace("#phone_no#", "".$phone_no."",  $bankstr10);
							$bankstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $bankstr11);
							$bankstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $bankstr12);
							$bankstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $bankstr13);
							$bankstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $bankstr14);
							$bankstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $bankstr15);
							$bankstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $bankstr16);
							$bankstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $bankstr17);
							$bankstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $bankstr18);
							$bankstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $bankstr19);
							$bankstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $bankstr20);
							$bankstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $bankstr21);
							$bankstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $bankstr22);
							$bankstr24 = str_replace("#till_present#", "".$till_present."",  $bankstr23);
							$bankstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $bankstr24);
							$bankstr26 = str_replace("#payment#", "".$payment."",  $bankstr25);
							$bankstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $bankstr26);
							$bankstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $bankstr27);
							$bankstr29 = str_replace("#STATUS#", "Transaction Successful",  $bankstr28);
							$bankstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $bankstr29);
							$bankstr31 = str_replace("#sponsor#", "".$sponsor."",  $bankstr30);
							$bankstr32 = str_replace("#sponsor_bank_name#", "".$user_info[0]['sponsor_bank_name']."",  $bankstr31);
							$bankstr33 = str_replace("#sponsor_email#", "".$user_info[0]['sponsor_email']."",  $bankstr32);
							$bankstr34 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr33);
							$bankstr35 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr34);
							$bankstr36 = str_replace("#sponsor_contact_designation#", "".$user_info[0]['sponsor_contact_designation']."",  $bankstr35);
							$bankstr37 = str_replace("#sponsor_contact_std#", "".$user_info[0]['sponsor_contact_std']."",  $bankstr36);
							$bankstr38 = str_replace("#sponsor_contact_phone#", "".$sponsor_contact_phone."",  $bankstr37);
							$bankstr39 = str_replace("#sponsor_contact_mobile#", "".$user_info[0]['sponsor_contact_mobile']."",  $bankstr38);
							$final_bankstr = str_replace("#sponsor_contact_email#", "".$user_info[0]['sponsor_contact_email']."",  $bankstr39);
							
							$bank_mail_arr = array(
							//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in',
							'to'=>'kumartupe@gmail.com,raajpardeshi@gmail.com',
							'from'=>$emailerBankStr[0]['from'],
							'subject'=>$emailerBankStr[0]['subject'],
							'message'=>$final_bankstr,
							);
							
							$this->Emailsending->mailsend($bank_mail_arr);
						}
					}
					
					redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
					}
					else
					{
						redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
					}
						}
						else if($get_user_regnum_info[0]['payment_option']==2 || $get_user_regnum_info[0]['payment_option']==3)
						{
							
							$payment_option='';
							if($get_user_regnum_info[0]['payment_option']== 2)
							{
								$payment_option='second';
							}
							else if($get_user_regnum_info[0]['payment_option']== 3)
							{
								$payment_option='Full';
							}
							
							$reg_id=$get_user_regnum_info[0]['ref_id'];
							//$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id),'name,regnumber,email_id,photograph,signature,mobile_no,sponsor');
							$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id));
							
							//update payment transaction
							$update_data = array('member_regnumber' => $user_info[0]['regnumber'],'sponsor'=>$user_info[0]['sponsor'],'transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
							$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							
							//update amp registration table with installment status
							$update_mem_data = array('payment' =>$payment_option);
							$this->master_model->updateRecord('amp_candidates',$update_mem_data,array('id'=>$reg_id));
							
							//maintain log in for updated transaction
							$log_title ="Installment payment";
							$update_info['membershipno'] = $user_info[0]['regnumber'];
							$log_message = serialize($update_mem_data);
							$this->Ampmodel->create_log($log_title, $log_message);
				
							//Query to get Payment details	
							$payment_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$user_info[0]['regnumber']),'transaction_no,date,amount');
					
							//email to user
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer'));
							if(count($emailerstr) > 0)
							{
								$username=$user_info[0]['name'];
								$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
								$newstring1 = str_replace("#REG_NUM#", "".$user_info[0]['regnumber']."",  $emailerstr[0]['emailer_text']);
								$newstring2 = str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring1);
								$newstring3 = str_replace("#EMAIL#", "".$user_info[0]['email_id']."",  $newstring2);
								$newstring4 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $newstring3);
								$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $newstring4);
								$newstring6 = str_replace("#STATUS#", "Transaction Successful",  $newstring5);
								$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring6);
							
								
								$info_arr=array('to'=>$user_info[0]['email_id'],
								'from'=>$emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'],
								'message'=>$final_str,
								//'bcc'=>'kumartupe@gmail.com,raajpardeshi@gmail.com'
								);
						//$this->send_mail($applicationNo);
						//$this->send_sms($applicationNo);
						
						//Manage Log
							$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
							
							$this->log_model->logamptransaction("sbiepay", $pg_response, $responsedata[2]);
							
							$sms_newstring = str_replace("#fee#", "".$payment_info[0]['amount']."",  $emailerstr[0]['sms_text']);
							$sms_newstring1 = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring);
							$sms_final_str = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $sms_newstring1);
							$this->master_model->send_sms($user_info[0]['mobile_no'],$sms_final_str);	
							$this->Emailsending->mailsend($info_arr);
							
							//email send to sk datta and kavan for self sponsor
							if($user_info[0]['sponsor']=='self'){
								$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_self'));
								if(count($emailerSelfStr) > 0){
									$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
									
									if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
									
									if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
									
									if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
									
									if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = ''; }
									
									if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
									
									if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
									
									$selfstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerSelfStr[0]['emailer_text']);
									$selfstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr1);
									$selfstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr2);
									$selfstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $selfstr3);
									$selfstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $selfstr4);
									$selfstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $selfstr5);
									$selfstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $selfstr6);
									$selfstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $selfstr7);
									$selfstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $selfstr8);
									$selfstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $selfstr9);
									$selfstr11 = str_replace("#phone_no#", "".$phone_no."",  $selfstr10);
									$selfstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $selfstr11);
									$selfstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $selfstr12);
									$selfstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $selfstr13);
									$selfstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $selfstr14);
									$selfstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $selfstr15);
									$selfstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $selfstr16);
									$selfstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $selfstr17);
									$selfstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $selfstr18);
									$selfstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $selfstr19);
									$selfstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $selfstr20);
									$selfstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $selfstr21);
									$selfstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $selfstr22);
									$selfstr24 = str_replace("#till_present#", "".$till_present."",  $selfstr23);
									$selfstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $selfstr24);
									$selfstr26 = str_replace("#payment#", "".$payment."",  $selfstr25);
									$selfstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $selfstr26);
									$selfstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $selfstr27);
									$selfstr29 = str_replace("#STATUS#", "Transaction Successful",  $selfstr28);
									$selfstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $selfstr29);
									$final_selfstr = str_replace("#sponsor#", "".$sponsor."",  $selfstr30);
									
									$self_mail_arr = array(
									//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in',
									'to'=>'kumartupe@gmail.com,raajpardeshi@gmail.com',
									'from'=>$emailerSelfStr[0]['from'],
									'subject'=>$emailerSelfStr[0]['subject'],
									'message'=>$final_selfstr,
									);
									
									$this->Emailsending->mailsend($self_mail_arr);
								}
							}
							
							//email send to sk datta and kavan for bank sponsor
							if($user_info[0]['sponsor']=='bank'){
								$emailerBankStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_bank'));
								if(count($emailerBankStr) > 0){
									$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
									
									if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
									
									if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
									
									if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
									
									if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = ''; }
									
									if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
									
									if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
									
									if($user_info[0]['sponsor_contact_phone']!=0){ $sponsor_contact_phone = $user_info[0]['sponsor_contact_phone']; }else{ $sponsor_contact_phone = ''; }
									
									$bankstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerBankStr[0]['emailer_text']);
									$bankstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr1);
									$bankstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr2);
									$bankstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $bankstr3);
									$bankstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $bankstr4);
									$bankstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $bankstr5);
									$bankstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $bankstr6);
									$bankstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $bankstr7);
									$bankstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $bankstr8);
									$bankstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $bankstr9);
									$bankstr11 = str_replace("#phone_no#", "".$phone_no."",  $bankstr10);
									$bankstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $bankstr11);
									$bankstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $bankstr12);
									$bankstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $bankstr13);
									$bankstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $bankstr14);
									$bankstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $bankstr15);
									$bankstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $bankstr16);
									$bankstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $bankstr17);
									$bankstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $bankstr18);
									$bankstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $bankstr19);
									$bankstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $bankstr20);
									$bankstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $bankstr21);
									$bankstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $bankstr22);
									$bankstr24 = str_replace("#till_present#", "".$till_present."",  $bankstr23);
									$bankstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $bankstr24);
									$bankstr26 = str_replace("#payment#", "".$payment."",  $bankstr25);
									$bankstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $bankstr26);
									$bankstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $bankstr27);
									$bankstr29 = str_replace("#STATUS#", "Transaction Successful",  $bankstr28);
									$bankstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $bankstr29);
									$bankstr31 = str_replace("#sponsor#", "".$sponsor."",  $bankstr30);
									$bankstr32 = str_replace("#sponsor_bank_name#", "".$user_info[0]['sponsor_bank_name']."",  $bankstr31);
									$bankstr33 = str_replace("#sponsor_email#", "".$user_info[0]['sponsor_email']."",  $bankstr32);
									$bankstr34 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr33);
									$bankstr35 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr34);
									$bankstr36 = str_replace("#sponsor_contact_designation#", "".$user_info[0]['sponsor_contact_designation']."",  $bankstr35);
									$bankstr37 = str_replace("#sponsor_contact_std#", "".$user_info[0]['sponsor_contact_std']."",  $bankstr36);
									$bankstr38 = str_replace("#sponsor_contact_phone#", "".$sponsor_contact_phone."",  $bankstr37);
									$bankstr39 = str_replace("#sponsor_contact_mobile#", "".$user_info[0]['sponsor_contact_mobile']."",  $bankstr38);
									$final_bankstr = str_replace("#sponsor_contact_email#", "".$user_info[0]['sponsor_contact_email']."",  $bankstr39);
									
									$bank_mail_arr = array(
									//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in',
									'to'=>'kumartupe@gmail.com,raajpardeshi@gmail.com',
									'from'=>$emailerBankStr[0]['from'],
									'subject'=>$emailerBankStr[0]['subject'],
									'message'=>$final_bankstr,
									);
									
									$this->Emailsending->mailsend($bank_mail_arr);
								}
							}
					
							redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
						}
						else
						{
							redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
						}
					}
						}
					}
				}
			///End of SBI B2B callback 
			redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
			}
			else
			{
				die("Please try again...");
			}
	}
	
	
	
	
	public function sbitransfail()
	{
		//delete_cookie('regid');
		//print_r($_REQUEST['encData']);exit;
		if (isset($_REQUEST['encData']))
		{
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$encData = $aes->decrypt($_REQUEST['encData']);
			$responsedata = explode("|",$encData);
			$MerchantOrderNo = $responsedata[0]; 
			$transaction_no  = $responsedata[1];
			$get_user_regnum_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status');
			
			if($get_user_regnum_info[0]['status']!=0 && $get_user_regnum_info[0]['status']==2)
			{
				if (isset($_REQUEST['merchIdVal']))
				{
					$merchIdVal = $_REQUEST['merchIdVal'];
				}
				if (isset($_REQUEST['Bank_Code']))
				{
					$Bank_Code = $_REQUEST['Bank_Code'];
				}
				if (isset($_REQUEST['pushRespData']))
				{
					$encData = $_REQUEST['pushRespData'];
				}
			
			
			$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
			$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
			//Manage Log
			$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
			$this->log_model->logamptransaction("sbiepay", $pg_response,$responsedata[2]);		
			}
			//Sbi fail code without callback
			echo "Transaction failed";
			
			echo "<script>
				(function (global) {
				
					if(typeof (global) === 'undefined')
					{
						throw new Error('window is undefined');
					}
				
					var _hash = '!';
					var noBackPlease = function () {
						global.location.href += '#';
				
						// making sure we have the fruit available for juice....
						// 50 milliseconds for just once do not cost much (^__^)
						global.setTimeout(function () {
							global.location.href += '!';
						}, 50);
					};
					
					// Earlier we had setInerval here....
					global.onhashchange = function () {
						if (global.location.hash !== _hash) {
							global.location.hash = _hash;
						}
					};
				
					global.onload = function () {
						
						noBackPlease();
				
						// disables backspace on page except on input fields and textarea..
						document.body.onkeydown = function (e) {
							var elm = e.target.nodeName.toLowerCase();
							if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
								e.preventDefault();
							}
							// stopping event bubbling up the DOM tree..
							e.stopPropagation();
						};
						
					};
				
				})(window);
				</script>";

			exit;
				/*	$this->load->model('log_model');
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$merchIdVal = $_REQUEST['merchIdVal'];
			$Bank_Code = $_REQUEST['Bank_Code'];
			$encData = $aes->decrypt($_REQUEST['encData']);
			$responsedata = explode("|",$encData);
			$MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
			$transaction_no  = $responsedata[1];*/
			
			//SBI Callback Code
			/*$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5]);
			$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));*/
		//END of SBI Callback code			
		
			//print_r($responsedata);  // Payment gateway response
			// TO DO : Redirect to user acknowledge page
			
		}
		else
		{
			die("Please try again...");
		}
	}
	
	
	//Thank you message to end user
	public function details($order_no=NULL)
	{
		if($order_no!=NULL)
		{
			$data=array();
			$this->session->unset_userdata("insertdata");
			//get user details
			$this->db->join('amp_candidates','amp_candidates.id=amp_payment_transaction.ref_id');
			$user_info_details=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>base64_decode($order_no)));
			
			if(empty($user_info_details)){
				redirect(base_url().'amp/self');
			}
			
			$data=array('middle_content'=>'amp/thankyou','user_info_details'=>$user_info_details);
			$this->load->view('amp/common_view_fullwidth',$data);
		
		}
		else
		{
			redirect(base_url().'amp/self');
		}
	}

	public function exampdf($order_no)
	{	
			
		$this->db->join('amp_candidates','amp_candidates.id=amp_payment_transaction.ref_id');
		$user_info_details=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>base64_decode($order_no)));
		
		if(empty($user_info_details)){
			redirect(base_url().'amp/self');
		}
			
		//echo '<pre>';print_r($user_info_details);die;
		if($user_info_details[0]['status']=='1'){ $status='Success';}else{ $status='Unsuccess';}
		$imagePath = base_url().'uploads/amp/photograph/'.$user_info_details[0]['photograph'];
		if(strtolower($user_info_details[0]['payment'])=='full'){
			$payment = 'Full Paid';
		}else{
			$payment =  ucfirst($user_info_details[0]['payment']).' Installment';
		}
									
		$html='<table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ; border: 1px solid #000; padding:25px;">         
	<tbody>
		<tr><td colspan="4" align="left">&nbsp;</td> </tr>
		<tr>
			<td colspan="4" align="center" height="25">
			<span id="1001a1" class="alert"></span>
			</td>
		</tr>

		<tr style="border-bottom:solid 1px #000;"> 
			<td colspan="4" height="1" align="center" ><img src="'.base_url().'assets/images/logo1.png"></td>
		</tr>
		<tr></tr>
		<tr><td style="text-align:center"><strong><h3>Exam Enrolment Acknowledgement</h3></strong></td></tr>	   
		<tr><td style="text-align:right"><img src="'.$imagePath.'" height="100" width="100" /></td></tr>
		<tr>
			<td colspan="4">
			</hr>

			<table width="98%" cellspacing="2" cellpadding="2" border="0" align="center">
				<tbody>
				<tr>
					<td class="tablecontent2" width="51%">Membership No : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['regnumber'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Name : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['name'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Date of Birth : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.date('d-M-Y',strtotime($user_info_details[0]['dob'])).'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Address : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['address1'].' '.$user_info_details[0]['address2'].' '.$user_info_details[0]['address3'].' '.$user_info_details[0]['address4'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Pincode : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['pincode_address'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Mobile Number : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['mobile_no'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Email ID : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['email_id'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Payment : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$payment.'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Amount : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['amount'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Sponsor : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.ucfirst($user_info_details[0]['sponsor']).'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Transaction Number : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['transaction_no'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Transaction Status : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$status.'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Transaction Date : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['date'].'</td>
				</tr>
				
				</tbody>
			</table>
			
			</td>
		</tr>
	</tbody>
</table>';
	//echo $html;die;
			//this the the PDF filename that user will get to download
			$pdfFilePath = 'exam'.'.pdf';
			//load mPDF library
			$this->load->library('m_pdf');
			//actually, you can pass mPDF parameter on this load() function
			$pdf = $this->m_pdf->load();
			//$pdf->SetHTMLHeader($header);
			$pdf->SetHTMLHeader(''); 
			$pdf->SetHTMLFooter('');
			$stylesheet = '/*Table with outline Classes*/
								table.tbl-2 { outline: none; width: 100%; border-right:1px solid #cccaca; border-top: 1px solid #cccaca;}
								table.tbl-2 th { background: #222D3A; border-bottom: 1px solid #cccaca; border-left:1px solid #dbdada; color: #fff; padding: 5px; text-align: center;}
								table.tbl-2 th.head { background: #CECECE; text-align:left;}
								table.tbl-2 td.tda2 { background: #f7f7f7; color: #4c4c4c; border-bottom: 1px solid #cccaca; border-left:1px solid #cccaca; line-height: 18px; padding: 5px; text-align: left;}
								table.tbl-2 td.tdb2 { background: #ebeaea; color: #4c4c4c; border-bottom: 1px solid #cccaca; border-left:1px solid #cccaca; line-height: 18px; padding: 5px; text-align: left;}
								table.tbl-2 td.tda2 a { color: #0d64a0;}
								table.tbl-2 td.tda2 a:hover{ color: #0d64a0; text-decoration:none;}
								table.tbl-2 td.tdb2 a { color: #0d64a0;}
								table.tbl-2 td.tdb2 a:hover{ color: #0d64a0; text-decoration:none;}
								.align_class_table{text-align:center !important;}
								.align_class_table_right{text-align:right !important;}';
			 header('Content-Type: application/pdf'); 
             header('Content-Description: inline; filename.pdf');
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
			$pdf->Output($pdfFilePath,'D');
	}
		
}

?><?php
defined('BASEPATH') OR exit('No direct script access allowed'); header("Access-Control-Allow-Origin: *");
class Amp extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');	
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->helper('general_helper');
		$this->load->model('Master_model');		
		$this->load->library('email');
		$this->load->helper('date');
		$this->load->library('email');
		$this->load->model('Emailsending');
		$this->load->model('log_model');
		$this->load->model('Ampmodel');
		
		/*error_reporting(E_ALL);
		ini_set('display_errors',1);*/
		//$this->load->model('chk_session');
	  	//$this->chk_session->checklogin();
		//$this->load->model('chk_session');
	    //$this->chk_session->chk_member_session();
	}
	 
	public function self(){
		$photo_error = '';
		$sign_error = '';
		
		if($this->input->post('form_type')=='amp_form'){
			$aPost = $this->input->post();
			//echo '<pre>';print_r($aPost);die;
			if($this->is_valid('self')){
				$aPost['till_present'] = isset($aPost['till_present']) ? 1 : 0;
				$aPost['agree'] = isset($aPost['agree']) ? 1 : 0;
				$insert_info = array(
								'sponsor'=>'self',
								'name'=>$aPost['name'],
								'dob'=>$aPost['dob'],
								//'regnumber'=>$aPost['regnumber'],
								'bday'=>$aPost['bday'],
								'bmonth'=>$aPost['bmonth'],
								'byear'=>$aPost['byear'],
								'address1'=>$aPost['address1'],
								'address2'=>$aPost['address2'],
								'address3'=>$aPost['address3'],
								'address4'=>$aPost['address4'],
								'pincode_address'=>$aPost['pincode_address'],
								'std_code'=>$aPost['std_code'],
								'phone_no'=>$aPost['phone_no'],
								'mobile_no'=>$aPost['mobile_no'],
								'email_id'=>$aPost['email_id'],
								'alt_email_id'=>$aPost['alt_email_id'],
								'graduation'=>$aPost['graduation'],
								'post_graduation'=>$aPost['post_graduation'],
								'special_qualification'=>$aPost['special_qualification'],
								'name_employer'=>$aPost['name_employer'],
								'position'=>$aPost['position'],
								'work_from_month'=>$aPost['work_from_month'],
								'work_from_year'=>$aPost['work_from_year'],
								'work_to_month'=>$aPost['work_to_month'],
								'work_to_year'=>$aPost['work_to_year'],
								'till_present'=>$aPost['till_present'],
								'work_experiance'=>$aPost['work_experiance'],
								'payment'=>$aPost['payment'],
								'agree'=>$aPost['agree']
							   );
							   
				//$last_id = $this->master_model->insertRecord('amp_candidates',$insert_info,true);
				
					
					if(isset($_FILES['photograph']['name']) &&($_FILES['photograph']['name']!='')){
						$img = "photograph";
						$tmp_photonm = strtotime('now').rand(0,100);
						$new_filename = 'photo_'.$tmp_photonm;
						$config = array('upload_path'=>'./uploads/amp/photograph',
									'allowed_types'=>'jpg|jpeg',
									'file_name'=>$new_filename,
									'max_size'=>50);
									
						$this->upload->initialize($config);
						$size = @getimagesize($_FILES['photograph']['tmp_name']);
						if($size){
							if($this->upload->do_upload($img)){
								$dt=$this->upload->data();
								$insert_info['photograph'] = $dt['file_name'];
							}else{
								$photo_error = $this->upload->display_errors();
							}
						}else{
							$photo_error = 'The filetype you are attempting to upload is not allowed';
						}
					}
					
					if(isset($_FILES['signature']['name']) &&($_FILES['signature']['name']!='')){
						$img = "signature";
						$tmp_photonm = strtotime('now').rand(0,100);
						$new_filename = 'sign_'.$tmp_photonm;
						$config = array('upload_path'=>'./uploads/amp/signature',
									'allowed_types'=>'jpg|jpeg',
									'file_name'=>$new_filename,
									'max_size'=>50);
									
						$this->upload->initialize($config);
						$size = @getimagesize($_FILES['signature']['tmp_name']);
						if($size){
							if($this->upload->do_upload($img)){
								$dt=$this->upload->data();
								$insert_info['signature'] = $dt['file_name'];
							}else{
								$sign_error = $this->upload->display_errors();
							}
						}else{
							$sign_error = 'The filetype you are attempting to upload is not allowed';
						}
					}
					
					if($sign_error=='' && $photo_error==''){
						$this->session->set_userdata('insertdata', $insert_info);
						redirect(base_url().'amp/payment');
					}				
			}
		}
		
		$this->load->helper('captcha');
		$this->session->unset_userdata("regampcaptcha");
		$this->session->set_userdata("regampcaptcha", rand(1, 100000));
		$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		
		$this->session->set_userdata('regampcaptcha', $cap['word']);
		$data=array('middle_content'=>'amp/amp_self_sponsor','image' => $cap['image'],'photo_error'=>$photo_error,'sign_error'=>$sign_error,'sponsor'=>'Self');
		$this->load->view('amp/common_view_fullwidth',$data);
	}
	
	public function bank(){
		$photo_error = '';
		$sign_error = '';
		
		if($this->input->post('form_type')=='amp_form'){
			$aPost = $this->input->post();
			if($this->is_valid('bank')){
				//echo '<pre>';print_r($aPost);die;
				$aPost['till_present'] = isset($aPost['till_present']) ? 1 : 0;
				$aPost['agree'] = isset($aPost['agree']) ? 1 : 0;
				$insert_info = array(
								'sponsor_bank_name'=>$aPost['sponsor_bank_name'],
								'sponsor_email'=>$aPost['sponsor_email'],
								'sponsor_contact_person'=>$aPost['sponsor_contact_person'],
								'sponsor_contact_designation'=>$aPost['sponsor_contact_designation'],
								'sponsor_contact_std'=>$aPost['sponsor_contact_std'],
								'sponsor_contact_phone'=>$aPost['sponsor_contact_phone'],
								'sponsor_contact_mobile'=>$aPost['sponsor_contact_mobile'],
								'sponsor_contact_email'=>$aPost['sponsor_contact_email'],
								'sponsor'=>'bank',
								'name'=>$aPost['name'],
								'dob'=>$aPost['dob'],
								//'regnumber'=>$aPost['regnumber'],
								'bday'=>$aPost['bday'],
								'bmonth'=>$aPost['bmonth'],
								'byear'=>$aPost['byear'],
								'address1'=>$aPost['address1'],
								'address2'=>$aPost['address2'],
								'address3'=>$aPost['address3'],
								'address4'=>$aPost['address4'],
								'pincode_address'=>$aPost['pincode_address'],
								'std_code'=>$aPost['std_code'],
								'phone_no'=>$aPost['phone_no'],
								'mobile_no'=>$aPost['mobile_no'],
								'email_id'=>$aPost['email_id'],
								'alt_email_id'=>$aPost['alt_email_id'],
								'graduation'=>$aPost['graduation'],
								'post_graduation'=>$aPost['post_graduation'],
								'special_qualification'=>$aPost['special_qualification'],
								'name_employer'=>$aPost['name_employer'],
								'position'=>$aPost['position'],
								'work_from_month'=>$aPost['work_from_month'],
								'work_from_year'=>$aPost['work_from_year'],
								'work_to_month'=>$aPost['work_to_month'],
								'work_to_year'=>$aPost['work_to_year'],
								'till_present'=>$aPost['till_present'],
								'work_experiance'=>$aPost['work_experiance'],
								'payment'=>$aPost['payment'],
								'agree'=>$aPost['agree']
							   );
									
					if(isset($_FILES['photograph']['name']) &&($_FILES['photograph']['name']!='')){
						$img = "photograph";
						$tmp_photonm = strtotime('now').rand(0,100);
						$new_filename = 'photo_'.$tmp_photonm;
						$config = array('upload_path'=>'./uploads/amp/photograph',
									'allowed_types'=>'jpg|jpeg',
									'file_name'=>$new_filename,
									'max_size'=>50);
									
						$this->upload->initialize($config);
						$size = @getimagesize($_FILES['photograph']['tmp_name']);
						if($size){
							if($this->upload->do_upload($img)){
								$dt=$this->upload->data();
								$update_info = array('photograph'=>$dt['file_name']);
								$insert_info['photograph'] = $dt['file_name'];
							}else{
								$photo_error = $this->upload->display_errors();
							}
						}else{
							$photo_error = 'The filetype you are attempting to upload is not allowed';
						}
					}
					
					if(isset($_FILES['signature']['name']) &&($_FILES['signature']['name']!='')){
						$img = "signature";
						$tmp_photonm = strtotime('now').rand(0,100);
						$new_filename = 'sign_'.$tmp_photonm;
						$config = array('upload_path'=>'./uploads/amp/signature',
									'allowed_types'=>'jpg|jpeg',
									'file_name'=>$new_filename,
									'max_size'=>50);
									
						$this->upload->initialize($config);
						$size = @getimagesize($_FILES['signature']['tmp_name']);
						if($size){
							if($this->upload->do_upload($img)){
								$dt=$this->upload->data();
								$update_info = array('signature'=>$dt['file_name']);
								$insert_info['signature'] = $dt['file_name'];
							}else{
								$sign_error = $this->upload->display_errors();
							}
						}else{
							$sign_error = 'The filetype you are attempting to upload is not allowed';
						}
					}
				if($sign_error=='' && $photo_error==''){
					$this->session->set_userdata('insertdata', $insert_info);
					redirect(base_url().'amp/payment');
				}
			}
		}
		
		$this->load->helper('captcha');
		$this->session->unset_userdata("regampcaptcha");
		$this->session->set_userdata("regampcaptcha", rand(1, 100000));
		$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		
		$this->session->set_userdata('regampcaptcha', $cap['word']);
		$data=array('middle_content'=>'amp/amp_bank_sponsor','image' => $cap['image'],'photo_error'=>$photo_error,'sign_error'=>$sign_error,'sponsor'=>'Bank');
		$this->load->view('amp/common_view_fullwidth',$data);
	}
	
	//validation rules set
	public function is_valid($sponsor){
		$this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
		
		$config = array(
					array(
						'field' => 'name',
						'label' => 'Name',
						'rules' => 'required|alpha_numeric_spaces'
					),array(
						'field' => 'membershipno',
						'label' => 'Membership No.',
						'rules' => 'max_length[11]|numeric'
					),array(
						'field' => 'dob',
						'label' => 'Date of Birth',
						'rules' => 'required'
					),array(
						'field' => 'bday',
						'label' => 'Birbday Day',
						'rules' => 'required'
					),array(
						'field' => 'bmonth',
						'label' => 'Birbday Month',
						'rules' => 'required'
					),array(
						'field' => 'byear',
						'label' => 'Birbday Year',
						'rules' => 'required'
					),array(
						'field' => 'address1',
						'label' => 'Office/Residential Address',
						'rules' => 'required'
					),array(
						'field' => 'pincode_address',
						'label' => 'Pincode',
						'rules' => 'required|exact_length[6]|numeric'
					),array(
						'field' => 'std_code',
						'label' => 'STD code',
						'rules' => 'max_length[5]|numeric'
					),array(
						'field' => 'phone_no',
						'label' => 'Phone No.',
						'rules' => 'max_length[8]|numeric'
					),array(
						'field' => 'mobile_no',
						'label' => 'Mobile No.',
						'rules' => 'required|exact_length[10]|numeric|callback_check_unique_mobile'
					),array(
						'field' => 'email_id',
						'label' => 'Candidates Email Id',
						'rules' => 'required|max_length[50]|valid_email|callback_check_unique_email'
					),array(
						'field' => 'alt_email_id',
						'label' => 'Candidates Alternate Email Id',
						'rules' => 'max_length[50]|valid_email'
					),array(
						'field' => 'name_employer',
						'label' => 'Name of the Employer',
						'rules' => 'max_length[30]|alpha_numeric_spaces'
					),array(
						'field' => 'position',
						'label' => 'Position',
						'rules' => 'max_length[30]|alpha_numeric_spaces'
					),array(
						'field' => 'work_from_year',
						'label' => 'Work Experience Period from Year',
						'rules' => 'numeric'
					),array(
						'field' => 'work_to_year',
						'label' => 'Work Experience Period to Year',
						'rules' => 'numeric|callback_periodcheck'
					),array(
						'field' => 'work_experiance',
						'label' => 'Total Experience in month',
						'rules' => 'max_length[3]|numeric'
					),array(
						'field' => 'hiddenphoto',
						'label' => 'Photograph',
						'rules' => 'required'
					),array(
						'field' => 'hiddenscansignature',
						'label' => 'Signature',
						'rules' => 'required'
					),array(
						'field' => 'payment',
						'label' => 'Payment Option',
						'rules' => 'required'
					),array(
						'field' => 'agree',
						'label' => 'I Agree',
						'rules' => 'required'
					),array(
						'field' => 'captcha',
						'label' => 'Captcha',
						'rules' => 'required|callback_check_captcha_userreg'
					)
				);

		//validation rules set for bank sponser
		if($sponsor=='bank'){
			$config[] = array(
							'field' => 'sponsor_bank_name',
							'label' => 'Name Of Sponsored Bank',
							'rules' => 'required|max_length[30]|alpha_numeric_spaces'
						);
			$config[] = array(
							'field' => 'sponsor_email',
							'label' => 'Name Of Department Email',
							'rules' => 'required|max_length[50]|valid_email'
						);
			$config[] = array(
							'field' => 'sponsor_contact_person',
							'label' => 'Contact person name',
							'rules' => 'required|max_length[40]|alpha_numeric_spaces'
						);
			$config[] = array(
							'field' => 'sponsor_contact_designation',
							'label' => 'Contact person Designation',
							'rules' => 'required|max_length[50]|alpha_numeric_spaces'
						);
			$config[] = array(
							'field' => 'sponsor_contact_std',
							'label' => 'Contact person STD code',
							'rules' => 'max_length[5]|numeric'
						);
			$config[] = array(
							'field' => 'sponsor_contact_phone',
							'label' => 'Contact person Phone No.',
							'rules' => 'max_length[8]|numeric'
						);
			$config[] = array(
							'field' => 'sponsor_contact_mobile',
							'label' => 'Contact person Mobile number',
							'rules' => 'required|max_length[10]|numeric'
						);
			$config[] = array(
							'field' => 'sponsor_contact_email',
							'label' => 'Contact person Email id',
							'rules' => 'required|max_length[50]|valid_email'
						);
		}
		
		$this->form_validation->set_rules($config);
		
		if($this->form_validation->run() == FALSE){
			return false;
        }
		
		return true;
	}
	
	//work experiance period check
	public function periodcheck(){
		
		if($_POST['work_from_year']!='' && $_POST['work_from_month']!='' && $_POST['work_to_month']!='' && $_POST['work_to_year']!=''){
			$from =  strtotime($_POST['work_from_year'].'-'.$_POST['work_from_month'].'-'.'1');
			$to =  strtotime($_POST['work_to_year'].'-'.$_POST['work_to_month'].'-'.'1');
			if($from <= $to){
				return true;
			}else{
				$this->form_validation->set_message('periodcheck', 'Work experience Period to must be greater than Period From.'); 
				return false;
			}
		}else{
			return true;
		}
	}
	
	//call back for check unique email for candidate
	public function check_unique_email($email){
		
		//$sql = 'select * from amp_candidates where email_id = '.$this->db->escape($email).' and isactive=\'1\'';
		$result = $this->master_model->getRecords('amp_candidates',array('email_id'=>$email,'isactive'=>'1'));
		//$result = $this->db->query($sql);
		if(count($result) > 0){
			$this->form_validation->set_message('check_unique_email', 'Candidate Email ID already register.'); 
			return false;
		}else{
			return true;
		}
	}
	
	public function check_unique_mobile($mobile){
		
		$result = $this->master_model->getRecords('amp_candidates',array('mobile_no'=>$mobile,'isactive'=>'1'));
		//$result = $this->db->query($sql);
		if(count($result ) > 0){
			$this->form_validation->set_message('check_unique_mobile', 'Candidate Mobile Number already register.'); 
			return false;
		}else{
			return true;
		}
	}
	
	//call back for check captcha server side
	public function check_captcha_userreg($code) 
	{
		//return true;
		if($code == '' || $_SESSION["regampcaptcha"] != $code )
		{
			$this->form_validation->set_message('check_captcha_userreg', 'Invalid %s.'); 
			return false;
		}
		if($_SESSION["regampcaptcha"] == $code)
		{
			return true;
		}
	}
	
	// reload captcha functionality
	public function generatecaptchaajax()
	{
		$this->load->helper('captcha');
		$this->session->unset_userdata("regampcaptcha");
		$this->session->set_userdata("regampcaptcha", rand(1, 100000));
		$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',);
		$cap = create_captcha($vals);
		$data = $cap['image'];
		$this->session->set_userdata("regampcaptcha", $cap['word']);
		//echo $this->session->userdata("regampcaptcha");
		echo $data;
		
	}
	
	//sending mails this function need membership number
	public function send_mail($membershipno){
		$aCandidate = $this->master_model->getRecords('amp_candidates',array('regnumber'=>$membershipno));
		$emailerstr = $this->master_model->getRecords('emailer',array('emailer_name'=>'amp_reg'));
		
		$newstring1 = str_replace("#username#", "".$aCandidate[0]['name']."",  $emailerstr[0]['emailer_text']);
		$newstring2 = str_replace("#membershipno#", "".$aCandidate[0]['regnumber']."",  $newstring1);
		$newstring3 = str_replace("#name#", "".$aCandidate[0]['name']."",  $newstring2);
		$newstring4 = str_replace("#email#", "".$aCandidate[0]['email_id']."",  $newstring3);
		$newstring5 = str_replace("#transaction#", ""."TRANSACTION ID"."",  $newstring4);
		$newstring6 = str_replace("#amount#", "".$aCandidate[0]['payment']."",  $newstring5);
		$newstring7 = str_replace("#Transaction_status#", ""."Status"."",  $newstring6);
		$final_str = str_replace("#date#", "".date('Y-m-d h:s:i')."",  $newstring7);
		$info_arr = array('to'=>$aCandidate[0]['email_id'],'from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'],'message'=>$final_str);
		
		$this->Emailsending->mailsend($info_arr);
	}
	
	//sending SMS this function need membership number
	public function send_sms($membershipno){
		$aCandidate = $this->master_model->getRecords('amp_candidates',array('membershipno'=>$membershipno));
		$emailerstr = $this->master_model->getRecords('emailer',array('emailer_name'=>'amp_reg'));
		
		//$sms_newstring = str_replace("#application_num#", "".$applicationNo."",  $emailerstr[0]['sms_text']);
		$sms_final_str= $emailerstr[0]['sms_text'];
		$this->master_model->send_sms($aCandidate[0]['mobile_no'],$sms_final_str);
	}
	
	//candidate payment after form submit
	public function payment(){
		if(!isset($this->session->userdata['insertdata']))
			redirect(base_url().'amp/self');
		
		if($this->input->post('form_type')=='pay_form'){
			redirect(base_url().'amp/insert_amp_data');
		}
		
		$data=array('middle_content'=>'amp/payment');
		$this->load->view('amp/common_view_fullwidth',$data);
	}
	
		//insert candidate data call from payment function
		public function insert_amp_data(){
		$insertdata = $this->session->userdata('insertdata');
		$last_id = $this->master_model->insertRecord('amp_candidates',$insertdata,true);
		
		$userarr=array('amp_id'=>$last_id);
		$this->session->set_userdata('ampmemberdata', $userarr); 
		
		//create log activity
		$log_title ="Candidate registration";
		$log_message = serialize($insertdata);
		$this->Ampmodel->create_log($log_title, $log_message);
		
		redirect(base_url()."Amp/make_payment");		
				
					
		
					
		/*$this->send_mail($membershipno);
		$this->send_sms($membershipno);*/
		
		
		
		//redirect(base_url().'amp/thankyou');
	}
	
	//Final page with session clear
	public function thankyou(){
		$this->session->unset_userdata("insertdata");
		$data=array('middle_content'=>'amp/thankyou');
		$this->load->view('amp/common_view_fullwidth',$data);
	}
	
	//search candidate for installment payment
	public function login(){
		$aCandidate = array();
		if($this->input->post('form_type')=='search_form'){
			$this->form_validation->set_rules('searchStr','Enter Name or Membership no.','trim|required|xss_clean');
			if($this->form_validation->run()==TRUE){
				$searchStr = $this->input->post('searchStr');
				$wherecondition= "(name LIKE '%$searchStr%' OR regnumber = '$searchStr')";
				$this->db->where($wherecondition);
				//$this->db->or_where('regnumber',$searchStr);
				//$this->db->like('name',$searchStr);
				
				$aCandidate = $this->master_model->getRecords('amp_candidates',array('isactive'=>'1'));
				//echo $this->db->last_query();exit;
			}
		}
		$data=array('middle_content'=>'amp/login','aCandidate'=>$aCandidate);
		$this->load->view('amp/common_view_fullwidth',$data);
	}
	
	//candidate details and installment
	public function installment($membershipno = 0){
		$aCandidate = $this->master_model->getRecords('amp_candidates',array('regnumber'=>$membershipno));
		if(empty($aCandidate)){
			redirect(base_url().'amp/self');
		}
			
			if($this->input->post('form_type')=='installment_form'){
				
				//$update_info = array('payment'=>$this->input->post('payment'));
				
				$userarr=array('amp_id'=>$aCandidate[0]['id']);
				$this->session->set_userdata('ampmemberdata', $userarr); 
				
				$userarr=array('payment'=>$this->input->post('payment'));
				$this->session->set_userdata('insertdata', $userarr); 
				
				redirect(base_url()."Amp/make_payment");	
		
				//$this->master_model->updateRecord('amp_candidates',$update_info,array('regnumber'=>$membershipno));
				
				//log create for installment
			/*	$log_title ="Installment payment";
				$update_info['membershipno'] = $membershipno;
				$log_message = serialize($update_info);
				$this->Ampmodel->create_log($log_title, $log_message);
				
				redirect(base_url().'amp/thankyou');*/
			}
			
		$data=array('middle_content'=>'amp/installment','aCandidate'=>$aCandidate);
		$this->load->view('amp/common_view_fullwidth',$data);
	}
	
	
	//code done by prafull
	public function make_payment() {
		$flag=1;
		// TO do:
		// Validate reg no in DB
		//$_REQUEST['regno'] = "ODExODU5OTE1";
		//$regno = base64_decode($_REQUEST['regno']);
		
		$regno = $this->session->userdata['ampmemberdata']['amp_id'];
		
		//$valcookie= register_get_cookie();
		
		/*if($valcookie)
		{
			$regid= $valcookie;
			//$regid= '57';
			$checkuser=$this->master_model->getRecords('amp_candidates',array('id'=>$regno,'regnumber !='=>'','isactive !='=>'0'));
			if(count($checkuser)>0)
			{
				delete_cookie('regid');
				redirect('http://iibf.org.in');
			}
			else
			{
				$checkpayment=$this->master_model->getRecords('amp_payment_transaction',array('ref_id'=>$regno,'status'=>'2'));
				if(count($checkpayment) > 0)
				{
					///$datearr=explode(' ',$checkpayment[0]['date']);
					$endTime = date("Y-m-d H:i:s",strtotime("+20 minutes",strtotime($checkpayment[0]['date'])));
					$current_time= date("Y-m-d H:i:s");
					if(strtotime($current_time)<=strtotime($endTime))
					{
						$flag=0;
					}
					else
					{
						delete_cookie('regid');
						redirect('http://iibf.org.in');
					}
				}
				else
				{
					$flag=1;
					delete_cookie('regid');
					redirect('http://iibf.org.in');
				}
			}	
		}*/
		
		if(isset($_POST['processPayment']) && $_POST['processPayment'])
		{
			$payment_option=0;
			//setting cookie for tracking multiple payment scenario
			//register_set_cookie($regno);
			
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$merchIdVal = $this->config->item('sbi_merchIdVal');
			$AggregatorId = $this->config->item('sbi_AggregatorId');
			
			$pg_success_url = base_url()."Amp/sbitranssuccess";
			$pg_fail_url    = base_url()."Amp/sbitransfail";
			
			
			if(isset($this->session->userdata['insertdata']['payment']))
			{
				if($this->session->userdata['insertdata']['payment']=='full')
				{
					$amount = $this->config->item('amp_fullpay_amount');
					$payment_option=4;
				}
				else if($this->session->userdata['insertdata']['payment']=='first')
				{
					$amount = $this->config->item('amp_first_installment');
					$payment_option=1;
				}
				else if($this->session->userdata['insertdata']['payment']=='second')
				{
					$amount = $this->config->item('amp_second_installment');
					$payment_option=2;
				}	
				
				else if($this->session->userdata['insertdata']['payment']=='third')
				{
					$amount = $this->config->item('amp_third_installment');
					$payment_option=3;
				}	
			}
			else
			{
				redirect(base_url().'Amp/self');
			}
		
			//$MerchantOrderNo = generate_order_id("reg_sbi_order_id");
			
			 
			
			// Create transaction
			$insert_data = array(
				'gateway'     => "sbiepay",
				'amount'      => $amount,
				'date'        => date('Y-m-d H:i:s'),
				'ref_id'	  =>  $regno,	
				'description' => "AMP Membership Registration",
				'pay_type'    => 1,
				'status'      => 2,
				//'receipt_no'  => $MerchantOrderNo,
				'pg_flag'=>'AMP',
				'payment_option'=>$payment_option
				//'pg_other_details'=>$custom_field
			);
		
			$pt_id = $this->master_model->insertRecord('amp_payment_transaction',$insert_data,true);
			
			$MerchantOrderNo = amp_sbi_order_id($pt_id);
			
			//Member registration
			 //Ref1 = orderid
			 //Ref2 = iibfamp
			 //Ref3 = primary key of amp member registation table
			 //Ref4 = amp+ registration year month ex (amp201704)
			$yearmonth=date('Ym');
			$custom_field = $MerchantOrderNo."^iibfamp^".$regno."^".'amp'.$yearmonth;
			
			// update receipt no. in payment transaction -
			$update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
			$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('id'=>$pt_id));
			
			$MerchantCustomerID = $regno;
			
			$data["pg_form_url"] = $this->config->item('sbi_pg_form_url'); // SBI ePay form URL
			$data["merchIdVal"]  = $merchIdVal;
			
			/*
			requestparameter=
			MerchantId | OperatingMode | MerchantCountry | MerchantCurrency |
PostingAmount | OtherDetails | SuccessURL | FailURL | AggregatorId | MerchantOrderNo |
MerchantCustomerID | Paymode | Accesmedium | TransactionSource
			Ex.
			requestparameter
=1000003|DOM|IN|INR|2|Other|https://test.sbiepay.coom/secure/fail.jsp|SBIEPAY|2|2|NB|ONLINE|ONLINE
			*/
			$EncryptTrans = $merchIdVal."|DOM|IN|INR|".$amount."|".$custom_field."|".$pg_success_url."|".$pg_fail_url."|".$AggregatorId."|".$MerchantOrderNo."|".$MerchantCustomerID."|NB|ONLINE|ONLINE";
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			
			$EncryptTrans = $aes->encrypt($EncryptTrans);
			
			$data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
			$this->load->view('pg_sbi_form',$data);
		}
		else
		{
			//$data["regno"] = $_REQUEST['regno'];
			$this->load->view('pg_sbi/make_payment_page');
		}
		
	}
	
	public function sbitranssuccess()
	{
		//delete_cookie('regid');
		//print_r($_REQUEST['encData']);
//$_REQUEST['encData']='6N7QR1B/Kz1O3Q+GWcfdJcY7NhHGxCp8SbDgjXOc3kJkWolrLAg6NifwqMm9VBAzwCyNY2JWDt1v4HcN8yFAAw36jyZ0oopYmlVFX06tNlMHAWqLGS+S3EGynsHAPpxb7pQsObd6nFBvXEC2MVrsk3tn65zCjlxQ7+vg4Ryv3ZCGDC1Y+jicNwfvNBUOvAdvCyCe0lpM8y/uo+NzFQIybA==';		
		if (isset($_REQUEST['encData']))
		{
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$encData = $aes->decrypt($_REQUEST['encData']);
			$responsedata = explode("|",$encData);
			$MerchantOrderNo = $responsedata[0]; 
			$transaction_no  = $responsedata[1];
			if (isset($_REQUEST['merchIdVal']))
			{
				$merchIdVal = $_REQUEST['merchIdVal'];
			}
			if (isset($_REQUEST['Bank_Code']))
			{
				$Bank_Code = $_REQUEST['Bank_Code'];
			}
			if (isset($_REQUEST['pushRespData']))
			{
				$encData = $_REQUEST['pushRespData'];
			}
			//Sbi B2B callback
			//check sbi payment status with MerchantOrderNo 
			$q_details = sbiqueryapi($MerchantOrderNo);
			if ($q_details)
			{
				if ($q_details[2] == "SUCCESS")
				{
					$get_user_regnum_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status, 	payment_option ');
					//check user payment status is updated by s2s or not
					if($get_user_regnum_info[0]['status']==2)
					{
						if($get_user_regnum_info[0]['payment_option']==1 || $get_user_regnum_info[0]['payment_option']==4)
						{
							$reg_id=$get_user_regnum_info[0]['ref_id'];
							//$applicationNo = generate_mem_reg_num();
							//Get membership number from 'amp_membershipno' and update in 'amp_candidates'
							$applicationNo =generate_amp_memreg($reg_id);
							//update amp registration table
							$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
							$this->master_model->updateRecord('amp_candidates',$update_mem_data,array('id'=>$reg_id));
							//get user information...
							//$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id),'name,regnumber,email_id,photograph,signature,mobile_no,sponsor');
							$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id));
							
							$update_data = array('member_regnumber' => $applicationNo,'sponsor'=>$user_info[0]['sponsor'],'transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
							$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							//get payment details
							
							//Query to get Payment details	
							$payment_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$user_info[0]['regnumber']),'transaction_no,date,amount');
					
							$upd_files = array();
							$photo_file = 'p_'.$applicationNo.'.jpg';
							$sign_file = 's_'.$applicationNo.'.jpg';
							$proof_file = 'pr_'.$applicationNo.'.jpg';
							
							if(@ rename("./uploads/amp/photograph/".$user_info[0]['scannedphoto'],"./uploads/amp/photograph/".$photo_file))
							{	$upd_files['scannedphoto'] = $photo_file;	}
							
							if(@ rename("./uploads/amp/signature/".$user_info[0]['scannedsignaturephoto'],"./uploads/amp/signature/".$sign_file))
							{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
					
							if(count($upd_files)>0)
							{
								$this->master_model->updateRecord('amp_candidates',$upd_files,array('id'=>$reg_id));
							}
						
					
					//email to user
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer'));
					if(count($emailerstr) > 0)
					{
						$username=$user_info[0]['name'];
						$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
						$newstring1 = str_replace("#REG_NUM#", "".$user_info[0]['regnumber']."",  $emailerstr[0]['emailer_text']);
						$newstring2 = str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring1);
						$newstring3 = str_replace("#EMAIL#", "".$user_info[0]['email_id']."",  $newstring2);
						$newstring4 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $newstring3);
						$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $newstring4);
						$newstring6 = str_replace("#STATUS#", "Transaction Successful",  $newstring5);
						$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring6);
					
						
						$info_arr=array('to'=>$user_info[0]['email_id'],
						'from'=>$emailerstr[0]['from'],
						'subject'=>$emailerstr[0]['subject'],
						'message'=>$final_str,
						//'bcc'=>'skdatta@iibf.org.in,kavan@iibf.org.in'
						);
					//$this->send_mail($applicationNo);
					//$this->send_sms($applicationNo);
					
					//Manage Log
					$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
					
					$this->log_model->logamptransaction("sbiepay", $pg_response, $responsedata[2]);
					
					$sms_newstring = str_replace("#fee#", "".$payment_info[0]['amount']."",  $emailerstr[0]['sms_text']);
					$sms_newstring1 = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring);
					$sms_final_str = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $sms_newstring1);
					$this->master_model->send_sms($user_info[0]['mobile_no'],$sms_final_str);	
					$this->Emailsending->mailsend($info_arr);
					
					//email send to sk datta and kavan for self sponsor
					if($user_info[0]['sponsor']=='self'){
						$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_self'));
						if(count($emailerSelfStr) > 0){
							$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
							
							if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
							
							if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
							
							if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
							
							if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = ''; }
							
							if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
							
							if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
							
							$selfstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerSelfStr[0]['emailer_text']);
							$selfstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr1);
							$selfstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr2);
							$selfstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $selfstr3);
							$selfstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $selfstr4);
							$selfstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $selfstr5);
							$selfstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $selfstr6);
							$selfstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $selfstr7);
							$selfstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $selfstr8);
							$selfstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $selfstr9);
							$selfstr11 = str_replace("#phone_no#", "".$phone_no."",  $selfstr10);
							$selfstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $selfstr11);
							$selfstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $selfstr12);
							$selfstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $selfstr13);
							$selfstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $selfstr14);
							$selfstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $selfstr15);
							$selfstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $selfstr16);
							$selfstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $selfstr17);
							$selfstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $selfstr18);
							$selfstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $selfstr19);
							$selfstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $selfstr20);
							$selfstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $selfstr21);
							$selfstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $selfstr22);
							$selfstr24 = str_replace("#till_present#", "".$till_present."",  $selfstr23);
							$selfstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $selfstr24);
							$selfstr26 = str_replace("#payment#", "".$payment."",  $selfstr25);
							$selfstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $selfstr26);
							$selfstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $selfstr27);
							$selfstr29 = str_replace("#STATUS#", "Transaction Successful",  $selfstr28);
							$selfstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $selfstr29);
							$final_selfstr = str_replace("#sponsor#", "".$sponsor."",  $selfstr30);
							
							$self_mail_arr = array(
							//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in',
							'to'=>'kumartupe@gmail.com,raajpardeshi@gmail.com',
							'from'=>$emailerSelfStr[0]['from'],
							'subject'=>$emailerSelfStr[0]['subject'],
							'message'=>$final_selfstr,
							);
							
							$this->Emailsending->mailsend($self_mail_arr);
						}
					}
					
					//email send to sk datta and kavan for bank sponsor
					if($user_info[0]['sponsor']=='bank'){
						$emailerBankStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_bank'));
						if(count($emailerBankStr) > 0){
							$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
							
							if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
							
							if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
							
							if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
							
							if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = ''; }
							
							if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
							
							if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
							
							if($user_info[0]['sponsor_contact_phone']!=0){ $sponsor_contact_phone = $user_info[0]['sponsor_contact_phone']; }else{ $sponsor_contact_phone = ''; }
							
							$bankstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerBankStr[0]['emailer_text']);
							$bankstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr1);
							$bankstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr2);
							$bankstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $bankstr3);
							$bankstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $bankstr4);
							$bankstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $bankstr5);
							$bankstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $bankstr6);
							$bankstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $bankstr7);
							$bankstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $bankstr8);
							$bankstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $bankstr9);
							$bankstr11 = str_replace("#phone_no#", "".$phone_no."",  $bankstr10);
							$bankstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $bankstr11);
							$bankstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $bankstr12);
							$bankstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $bankstr13);
							$bankstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $bankstr14);
							$bankstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $bankstr15);
							$bankstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $bankstr16);
							$bankstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $bankstr17);
							$bankstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $bankstr18);
							$bankstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $bankstr19);
							$bankstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $bankstr20);
							$bankstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $bankstr21);
							$bankstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $bankstr22);
							$bankstr24 = str_replace("#till_present#", "".$till_present."",  $bankstr23);
							$bankstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $bankstr24);
							$bankstr26 = str_replace("#payment#", "".$payment."",  $bankstr25);
							$bankstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $bankstr26);
							$bankstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $bankstr27);
							$bankstr29 = str_replace("#STATUS#", "Transaction Successful",  $bankstr28);
							$bankstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $bankstr29);
							$bankstr31 = str_replace("#sponsor#", "".$sponsor."",  $bankstr30);
							$bankstr32 = str_replace("#sponsor_bank_name#", "".$user_info[0]['sponsor_bank_name']."",  $bankstr31);
							$bankstr33 = str_replace("#sponsor_email#", "".$user_info[0]['sponsor_email']."",  $bankstr32);
							$bankstr34 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr33);
							$bankstr35 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr34);
							$bankstr36 = str_replace("#sponsor_contact_designation#", "".$user_info[0]['sponsor_contact_designation']."",  $bankstr35);
							$bankstr37 = str_replace("#sponsor_contact_std#", "".$user_info[0]['sponsor_contact_std']."",  $bankstr36);
							$bankstr38 = str_replace("#sponsor_contact_phone#", "".$sponsor_contact_phone."",  $bankstr37);
							$bankstr39 = str_replace("#sponsor_contact_mobile#", "".$user_info[0]['sponsor_contact_mobile']."",  $bankstr38);
							$final_bankstr = str_replace("#sponsor_contact_email#", "".$user_info[0]['sponsor_contact_email']."",  $bankstr39);
							
							$bank_mail_arr = array(
							//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in',
							'to'=>'kumartupe@gmail.com,raajpardeshi@gmail.com',
							'from'=>$emailerBankStr[0]['from'],
							'subject'=>$emailerBankStr[0]['subject'],
							'message'=>$final_bankstr,
							);
							
							$this->Emailsending->mailsend($bank_mail_arr);
						}
					}
					
					redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
					}
					else
					{
						redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
					}
						}
						else if($get_user_regnum_info[0]['payment_option']==2 || $get_user_regnum_info[0]['payment_option']==3)
						{
							
							$payment_option='';
							if($get_user_regnum_info[0]['payment_option']== 2)
							{
								$payment_option='second';
							}
							else if($get_user_regnum_info[0]['payment_option']== 3)
							{
								$payment_option='Full';
							}
							
							$reg_id=$get_user_regnum_info[0]['ref_id'];
							//$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id),'name,regnumber,email_id,photograph,signature,mobile_no,sponsor');
							$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id));
							
							//update payment transaction
							$update_data = array('member_regnumber' => $user_info[0]['regnumber'],'sponsor'=>$user_info[0]['sponsor'],'transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
							$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							
							//update amp registration table with installment status
							$update_mem_data = array('payment' =>$payment_option);
							$this->master_model->updateRecord('amp_candidates',$update_mem_data,array('id'=>$reg_id));
							
							//maintain log in for updated transaction
							$log_title ="Installment payment";
							$update_info['membershipno'] = $user_info[0]['regnumber'];
							$log_message = serialize($update_mem_data);
							$this->Ampmodel->create_log($log_title, $log_message);
				
							//Query to get Payment details	
							$payment_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$user_info[0]['regnumber']),'transaction_no,date,amount');
					
							//email to user
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer'));
							if(count($emailerstr) > 0)
							{
								$username=$user_info[0]['name'];
								$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
								$newstring1 = str_replace("#REG_NUM#", "".$user_info[0]['regnumber']."",  $emailerstr[0]['emailer_text']);
								$newstring2 = str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring1);
								$newstring3 = str_replace("#EMAIL#", "".$user_info[0]['email_id']."",  $newstring2);
								$newstring4 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $newstring3);
								$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $newstring4);
								$newstring6 = str_replace("#STATUS#", "Transaction Successful",  $newstring5);
								$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring6);
							
								
								$info_arr=array('to'=>$user_info[0]['email_id'],
								'from'=>$emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'],
								'message'=>$final_str,
								//'bcc'=>'kumartupe@gmail.com,raajpardeshi@gmail.com'
								);
						//$this->send_mail($applicationNo);
						//$this->send_sms($applicationNo);
						
						//Manage Log
							$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
							
							$this->log_model->logamptransaction("sbiepay", $pg_response, $responsedata[2]);
							
							$sms_newstring = str_replace("#fee#", "".$payment_info[0]['amount']."",  $emailerstr[0]['sms_text']);
							$sms_newstring1 = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring);
							$sms_final_str = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $sms_newstring1);
							$this->master_model->send_sms($user_info[0]['mobile_no'],$sms_final_str);	
							$this->Emailsending->mailsend($info_arr);
							
							//email send to sk datta and kavan for self sponsor
							if($user_info[0]['sponsor']=='self'){
								$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_self'));
								if(count($emailerSelfStr) > 0){
									$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
									
									if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
									
									if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
									
									if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
									
									if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = ''; }
									
									if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
									
									if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
									
									$selfstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerSelfStr[0]['emailer_text']);
									$selfstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr1);
									$selfstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr2);
									$selfstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $selfstr3);
									$selfstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $selfstr4);
									$selfstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $selfstr5);
									$selfstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $selfstr6);
									$selfstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $selfstr7);
									$selfstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $selfstr8);
									$selfstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $selfstr9);
									$selfstr11 = str_replace("#phone_no#", "".$phone_no."",  $selfstr10);
									$selfstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $selfstr11);
									$selfstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $selfstr12);
									$selfstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $selfstr13);
									$selfstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $selfstr14);
									$selfstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $selfstr15);
									$selfstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $selfstr16);
									$selfstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $selfstr17);
									$selfstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $selfstr18);
									$selfstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $selfstr19);
									$selfstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $selfstr20);
									$selfstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $selfstr21);
									$selfstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $selfstr22);
									$selfstr24 = str_replace("#till_present#", "".$till_present."",  $selfstr23);
									$selfstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $selfstr24);
									$selfstr26 = str_replace("#payment#", "".$payment."",  $selfstr25);
									$selfstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $selfstr26);
									$selfstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $selfstr27);
									$selfstr29 = str_replace("#STATUS#", "Transaction Successful",  $selfstr28);
									$selfstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $selfstr29);
									$final_selfstr = str_replace("#sponsor#", "".$sponsor."",  $selfstr30);
									
									$self_mail_arr = array(
									//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in',
									'to'=>'kumartupe@gmail.com,raajpardeshi@gmail.com',
									'from'=>$emailerSelfStr[0]['from'],
									'subject'=>$emailerSelfStr[0]['subject'],
									'message'=>$final_selfstr,
									);
									
									$this->Emailsending->mailsend($self_mail_arr);
								}
							}
							
							//email send to sk datta and kavan for bank sponsor
							if($user_info[0]['sponsor']=='bank'){
								$emailerBankStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_bank'));
								if(count($emailerBankStr) > 0){
									$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
									
									if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
									
									if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
									
									if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
									
									if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = ''; }
									
									if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
									
									if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
									
									if($user_info[0]['sponsor_contact_phone']!=0){ $sponsor_contact_phone = $user_info[0]['sponsor_contact_phone']; }else{ $sponsor_contact_phone = ''; }
									
									$bankstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerBankStr[0]['emailer_text']);
									$bankstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr1);
									$bankstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr2);
									$bankstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $bankstr3);
									$bankstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $bankstr4);
									$bankstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $bankstr5);
									$bankstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $bankstr6);
									$bankstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $bankstr7);
									$bankstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $bankstr8);
									$bankstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $bankstr9);
									$bankstr11 = str_replace("#phone_no#", "".$phone_no."",  $bankstr10);
									$bankstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $bankstr11);
									$bankstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $bankstr12);
									$bankstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $bankstr13);
									$bankstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $bankstr14);
									$bankstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $bankstr15);
									$bankstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $bankstr16);
									$bankstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $bankstr17);
									$bankstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $bankstr18);
									$bankstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $bankstr19);
									$bankstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $bankstr20);
									$bankstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $bankstr21);
									$bankstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $bankstr22);
									$bankstr24 = str_replace("#till_present#", "".$till_present."",  $bankstr23);
									$bankstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $bankstr24);
									$bankstr26 = str_replace("#payment#", "".$payment."",  $bankstr25);
									$bankstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $bankstr26);
									$bankstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $bankstr27);
									$bankstr29 = str_replace("#STATUS#", "Transaction Successful",  $bankstr28);
									$bankstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $bankstr29);
									$bankstr31 = str_replace("#sponsor#", "".$sponsor."",  $bankstr30);
									$bankstr32 = str_replace("#sponsor_bank_name#", "".$user_info[0]['sponsor_bank_name']."",  $bankstr31);
									$bankstr33 = str_replace("#sponsor_email#", "".$user_info[0]['sponsor_email']."",  $bankstr32);
									$bankstr34 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr33);
									$bankstr35 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr34);
									$bankstr36 = str_replace("#sponsor_contact_designation#", "".$user_info[0]['sponsor_contact_designation']."",  $bankstr35);
									$bankstr37 = str_replace("#sponsor_contact_std#", "".$user_info[0]['sponsor_contact_std']."",  $bankstr36);
									$bankstr38 = str_replace("#sponsor_contact_phone#", "".$sponsor_contact_phone."",  $bankstr37);
									$bankstr39 = str_replace("#sponsor_contact_mobile#", "".$user_info[0]['sponsor_contact_mobile']."",  $bankstr38);
									$final_bankstr = str_replace("#sponsor_contact_email#", "".$user_info[0]['sponsor_contact_email']."",  $bankstr39);
									
									$bank_mail_arr = array(
									//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in',
									'to'=>'kumartupe@gmail.com,raajpardeshi@gmail.com',
									'from'=>$emailerBankStr[0]['from'],
									'subject'=>$emailerBankStr[0]['subject'],
									'message'=>$final_bankstr,
									);
									
									$this->Emailsending->mailsend($bank_mail_arr);
								}
							}
					
							redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
						}
						else
						{
							redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
						}
					}
						}
					}
				}
			///End of SBI B2B callback 
			redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
			}
			else
			{
				die("Please try again...");
			}
	}
	
	
	
	
	public function sbitransfail()
	{
		//delete_cookie('regid');
		//print_r($_REQUEST['encData']);exit;
		if (isset($_REQUEST['encData']))
		{
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$encData = $aes->decrypt($_REQUEST['encData']);
			$responsedata = explode("|",$encData);
			$MerchantOrderNo = $responsedata[0]; 
			$transaction_no  = $responsedata[1];
			$get_user_regnum_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status');
			
			if($get_user_regnum_info[0]['status']!=0 && $get_user_regnum_info[0]['status']==2)
			{
				if (isset($_REQUEST['merchIdVal']))
				{
					$merchIdVal = $_REQUEST['merchIdVal'];
				}
				if (isset($_REQUEST['Bank_Code']))
				{
					$Bank_Code = $_REQUEST['Bank_Code'];
				}
				if (isset($_REQUEST['pushRespData']))
				{
					$encData = $_REQUEST['pushRespData'];
				}
			
			
			$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
			$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
			//Manage Log
			$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
			$this->log_model->logamptransaction("sbiepay", $pg_response,$responsedata[2]);		
			}
			//Sbi fail code without callback
			echo "Transaction failed";
			
			echo "<script>
				(function (global) {
				
					if(typeof (global) === 'undefined')
					{
						throw new Error('window is undefined');
					}
				
					var _hash = '!';
					var noBackPlease = function () {
						global.location.href += '#';
				
						// making sure we have the fruit available for juice....
						// 50 milliseconds for just once do not cost much (^__^)
						global.setTimeout(function () {
							global.location.href += '!';
						}, 50);
					};
					
					// Earlier we had setInerval here....
					global.onhashchange = function () {
						if (global.location.hash !== _hash) {
							global.location.hash = _hash;
						}
					};
				
					global.onload = function () {
						
						noBackPlease();
				
						// disables backspace on page except on input fields and textarea..
						document.body.onkeydown = function (e) {
							var elm = e.target.nodeName.toLowerCase();
							if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
								e.preventDefault();
							}
							// stopping event bubbling up the DOM tree..
							e.stopPropagation();
						};
						
					};
				
				})(window);
				</script>";

			exit;
				/*	$this->load->model('log_model');
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$merchIdVal = $_REQUEST['merchIdVal'];
			$Bank_Code = $_REQUEST['Bank_Code'];
			$encData = $aes->decrypt($_REQUEST['encData']);
			$responsedata = explode("|",$encData);
			$MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
			$transaction_no  = $responsedata[1];*/
			
			//SBI Callback Code
			/*$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5]);
			$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));*/
		//END of SBI Callback code			
		
			//print_r($responsedata);  // Payment gateway response
			// TO DO : Redirect to user acknowledge page
			
		}
		else
		{
			die("Please try again...");
		}
	}
	
	
	//Thank you message to end user
	public function details($order_no=NULL)
	{
		if($order_no!=NULL)
		{
			$data=array();
			$this->session->unset_userdata("insertdata");
			//get user details
			$this->db->join('amp_candidates','amp_candidates.id=amp_payment_transaction.ref_id');
			$user_info_details=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>base64_decode($order_no)));
			
			if(empty($user_info_details)){
				redirect(base_url().'amp/self');
			}
			
			$data=array('middle_content'=>'amp/thankyou','user_info_details'=>$user_info_details);
			$this->load->view('amp/common_view_fullwidth',$data);
		
		}
		else
		{
			redirect(base_url().'amp/self');
		}
	}

	public function exampdf($order_no)
	{	
			
		$this->db->join('amp_candidates','amp_candidates.id=amp_payment_transaction.ref_id');
		$user_info_details=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>base64_decode($order_no)));
		
		if(empty($user_info_details)){
			redirect(base_url().'amp/self');
		}
			
		//echo '<pre>';print_r($user_info_details);die;
		if($user_info_details[0]['status']=='1'){ $status='Success';}else{ $status='Unsuccess';}
		$imagePath = base_url().'uploads/amp/photograph/'.$user_info_details[0]['photograph'];
		if(strtolower($user_info_details[0]['payment'])=='full'){
			$payment = 'Full Paid';
		}else{
			$payment =  ucfirst($user_info_details[0]['payment']).' Installment';
		}
									
		$html='<table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ; border: 1px solid #000; padding:25px;">         
	<tbody>
		<tr><td colspan="4" align="left">&nbsp;</td> </tr>
		<tr>
			<td colspan="4" align="center" height="25">
			<span id="1001a1" class="alert"></span>
			</td>
		</tr>

		<tr style="border-bottom:solid 1px #000;"> 
			<td colspan="4" height="1" align="center" ><img src="'.base_url().'assets/images/logo1.png"></td>
		</tr>
		<tr></tr>
		<tr><td style="text-align:center"><strong><h3>Exam Enrolment Acknowledgement</h3></strong></td></tr>	   
		<tr><td style="text-align:right"><img src="'.$imagePath.'" height="100" width="100" /></td></tr>
		<tr>
			<td colspan="4">
			</hr>

			<table width="98%" cellspacing="2" cellpadding="2" border="0" align="center">
				<tbody>
				<tr>
					<td class="tablecontent2" width="51%">Membership No : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['regnumber'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Name : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['name'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Date of Birth : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.date('d-M-Y',strtotime($user_info_details[0]['dob'])).'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Address : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['address1'].' '.$user_info_details[0]['address2'].' '.$user_info_details[0]['address3'].' '.$user_info_details[0]['address4'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Pincode : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['pincode_address'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Mobile Number : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['mobile_no'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Email ID : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['email_id'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Payment : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$payment.'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Amount : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['amount'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Sponsor : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.ucfirst($user_info_details[0]['sponsor']).'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Transaction Number : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['transaction_no'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Transaction Status : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$status.'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Transaction Date : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['date'].'</td>
				</tr>
				
				</tbody>
			</table>
			
			</td>
		</tr>
	</tbody>
</table>';
	//echo $html;die;
			//this the the PDF filename that user will get to download
			$pdfFilePath = 'exam'.'.pdf';
			//load mPDF library
			$this->load->library('m_pdf');
			//actually, you can pass mPDF parameter on this load() function
			$pdf = $this->m_pdf->load();
			//$pdf->SetHTMLHeader($header);
			$pdf->SetHTMLHeader(''); 
			$pdf->SetHTMLFooter('');
			$stylesheet = '/*Table with outline Classes*/
								table.tbl-2 { outline: none; width: 100%; border-right:1px solid #cccaca; border-top: 1px solid #cccaca;}
								table.tbl-2 th { background: #222D3A; border-bottom: 1px solid #cccaca; border-left:1px solid #dbdada; color: #fff; padding: 5px; text-align: center;}
								table.tbl-2 th.head { background: #CECECE; text-align:left;}
								table.tbl-2 td.tda2 { background: #f7f7f7; color: #4c4c4c; border-bottom: 1px solid #cccaca; border-left:1px solid #cccaca; line-height: 18px; padding: 5px; text-align: left;}
								table.tbl-2 td.tdb2 { background: #ebeaea; color: #4c4c4c; border-bottom: 1px solid #cccaca; border-left:1px solid #cccaca; line-height: 18px; padding: 5px; text-align: left;}
								table.tbl-2 td.tda2 a { color: #0d64a0;}
								table.tbl-2 td.tda2 a:hover{ color: #0d64a0; text-decoration:none;}
								table.tbl-2 td.tdb2 a { color: #0d64a0;}
								table.tbl-2 td.tdb2 a:hover{ color: #0d64a0; text-decoration:none;}
								.align_class_table{text-align:center !important;}
								.align_class_table_right{text-align:right !important;}';
			 header('Content-Type: application/pdf'); 
             header('Content-Description: inline; filename.pdf');
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
			$pdf->Output($pdfFilePath,'D');
	}
		
}

?>