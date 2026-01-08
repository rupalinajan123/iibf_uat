<?php

defined('BASEPATH') or exit('No direct script access allowed');




class Dbftojaiibcredit extends CI_Controller
{

    public function __construct()
    {
		//echo "IPPB Service is not available due to maintenance activity "; exit;
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->helper('general_helper');
        $this->load->model('master_model');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
        $this->load->model('chk_session');
        $this->load->helper('cookie');
        $this->load->model('KYC_Log_model');
        $this->chk_session->Check_mult_session();
        $this->load->model('billdesk_pg_model');
		$this->load->library('email');
		$this->load->helper('date');
		$this->session->set_userdata('csc_venue_flag','P');

		$this->load->helper('dbftojaiibcredit_invoice_helper');

        $cookie_name = "instruction";
        $cookie_value = "1";
        setcookie($cookie_name, $cookie_value, time() + (60 * 10), "/");
        $this->otptime = 60;
		
		/*if($this->get_client_ip() !='115.124.115.75' && $this->get_client_ip() !='106.214.101.113') {
			echo "IPPB Service is not available due to maintenance activity"; exit;	
		}*/
		
		
    }
    public function get_client_ip() {
			$ipaddress = '';
			if (getenv('HTTP_CLIENT_IP'))
				$ipaddress = getenv('HTTP_CLIENT_IP');
			else if(getenv('HTTP_X_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
			else if(getenv('HTTP_X_FORWARDED'))
				$ipaddress = getenv('HTTP_X_FORWARDED');
			else if(getenv('HTTP_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_FORWARDED_FOR');
			else if(getenv('HTTP_FORWARDED'))
			   $ipaddress = getenv('HTTP_FORWARDED');
			else if(getenv('REMOTE_ADDR'))
				$ipaddress = getenv('REMOTE_ADDR');
			else
				$ipaddress = 'UNKNOWN';
			return $ipaddress;
		}

    //search candidate for ippb login
    public function login($regnumber='')
    {
	
		$this->session->unset_userdata('matcheddetails');
        $aCandidate = $oCandidate =array();$not_eligible =0;
        if ($this->input->post('form_type') == 'search_form') {

			if($regnumber!='') {
				$message = 'Enter DB&F Membership no.';
				$regType = 'DB';
			}
            
			else
			{
				$message = 'Enter Membership no.';
				$regType = 'O';
			}
			$this->form_validation->set_rules('searchStr', $message, 'trim|required|xss_clean');
            
			if ($this->form_validation->run() == TRUE) {
                $searchStr = $this->input->post('searchStr');
                $aCandidate_ar = $this->master_model->getRecords('member_registration', array('regnumber' => $searchStr,'isdeleted' => '0','registrationtype'=>$regType));
				$aCandidate = $aCandidate_ar[0];
				if(!empty($aCandidate_ar)) {
					$this->session->set_userdata('aCandidate_ar',$aCandidate);
				}

				// 
				if($regnumber!='') {
					$check_eligible_member = $this->master_model->getRecords('eligible_master', array('
					exam_code' => 420,'member_no' => $aCandidate['regnumber']));
					if(!$check_eligible_member || empty($check_eligible_member)) {
						$not_eligible = 1;
					}
				}
        	}
		}
		$detailsNotMatched=0;
		if($regnumber!='') {
				$oCandidate_ar = $this->master_model->getRecords('member_registration', array('regnumber' => base64_decode($regnumber),'isdeleted' => '0','registrationtype'=>'O'));
				$oCandidate = $oCandidate_ar[0];
				if(!empty($aCandidate) && $aCandidate['dateofbirth']!=$oCandidate['dateofbirth']) {
					$detailsNotMatched=1;
				}
				
				if($detailsNotMatched==0) {
					$membernos = array('regnumber'			=> $oCandidate['regnumber'],
									'dbfregnumber'			=> $aCandidate['regnumber']
									);

					$this->session->set_userdata('matcheddetails',$membernos);
				}
				


			}
			//echo "abc1----------------><pre>"; print_r(count($oCandidate_ar));  echo $this->db->last_query();//exit;

        $data = array('middle_content' => 'dbftojaiibcredit/login_search', 'aCandidate' => $aCandidate,'oCandidate'=>$oCandidate,'detailsNotMatched'=>$detailsNotMatched,'not_eligible'=>$not_eligible);
      
        $this->load->view('dbftojaiibcredit/common_view_fullwidth', $data);
    }

	public function get_alredy_applied_examname($regnumber=NULL)
	{
		
		$msg='';
		
		$this->db->select('dbftojaiibcredit_registrations.*');
		$applied_exam_date=$this->master_model->getRecords('dbftojaiibcredit_registrations',array('regnumber'=>($regnumber),'pay_status '=>1));
		if(count($applied_exam_date) > 0)
		{
			$msg='You have already submitted application for DBF to JAIIB Credit Transfer. ';
			
			}
		return $msg;
	}
	// Custom callback function to validate the date
    public function valid_date($str) {
        // Check if the input is a valid date
        if (strtotime($str) === false) {
            $this->form_validation->set_message('valid_date', 'Valid Date not entered.');
            return FALSE;
        }
        return TRUE;
    }
	public function check_bank_joining_date ($date){
		$dbfregnumber = $this->session->userdata['matcheddetails']['dbfregnumber'];
		$dbCandidate_ar = $this->master_model->getRecords('member_registration', array('regnumber' => $dbfregnumber,'isdeleted' => '0','registrationtype'=>'DB'));
		$dbCandidate = $dbCandidate_ar[0];

		if(date('Y-m-d',strtotime($dbCandidate['createdon'])) > date('Y-m-d',strtotime($date))) {
			$this->form_validation->set_message('check_bank_joining_date', 'Since your date of joining the Bank is before DB&F Registration Date, you are not eligible for credit transfer. You may kindly apply for the upcoming JAIIB examinations only.');
            return FALSE;
		}
		return true;
	}
    //search candidate for ippb login
    public function candidate_details($regnumber='')
    {
	//	error_reporting(E_ALL);ini_set('display_errors', '1');
		if($this->get_client_ip() !='115.124.115.75' && $this->get_client_ip() !='115.124.115.69' && $this->get_client_ip() !='182.73.101.70') {
			//echo "IPPB Service is not available due to maintenance activity"; exit;	
		}
		if(!isset($this->session->userdata['matcheddetails']) || empty($this->session->userdata['matcheddetails'])) {
			$this->session->set_flashdata('error','Please login again');
            redirect(base_url() . 'Dbftojaiibcredit/login');
            exit;
		}
		$regnumber = $this->session->userdata['matcheddetails']['regnumber'];
		$dbfregnumber = $this->session->userdata['matcheddetails']['dbfregnumber'];

			 $this->db->join('subject_master', 'subject_master.subject_code =eligible_master.subject_code');
           
            $this->db->where("subject_master.subject_delete", '0');
            $this->db->where("eligible_master.exam_code ", '420');
            $this->db->where("eligible_master.member_no", $dbfregnumber);
            $eligible_subject_details = $this->master_model->getRecords('eligible_master');

			//echo $this->db->last_query();exit;
		if(!$eligible_subject_details || empty($eligible_subject_details)) {
			$this->session->set_flashdata('error','You are not eligible for Credit Transfer');
			$this->session->unset_userdata('matcheddetails');
            redirect(base_url() . 'Dbftojaiibcredit/login');
            exit;
		}
		$not_eligible_subject_details_Arr = array();
		$balance_attempt_left = 0;
		foreach($eligible_subject_details as $s) {
			if($s['exam_status']=='F') {
				$not_eligible_subject_details_Arr[]=$s['subject_code'];
				if($s['app_category']=='B1_2')
					$balance_attempt_left = 4;
				if($s['app_category']=='B2_1')
					$balance_attempt_left = 3;
				if($s['app_category']=='B2_2')
					$balance_attempt_left = 2;
				if($s['app_category']=='B3_1')
					$balance_attempt_left = 1;
			}
		}
		if($balance_attempt_left==0) {
			$this->session->set_flashdata('error','You are not eligible for Credit Transfer');
			$this->session->unset_userdata('matcheddetails');
            redirect(base_url() . 'Dbftojaiibcredit/login');
            exit;
		}
		$this->db->order_by("subject_code", "asc");
		$subject_details = $this->master_model->getRecords('subject_master', array(
								'exam_code'      => 420,
								'subject_delete' => '0',
								
							));
		//echo count($subject_details).'=='.count($not_eligible_subject_details_Arr);exit;
		if(count($subject_details)==count($not_eligible_subject_details_Arr)) {
			$this->session->set_flashdata('error','You are not eligible for Credit Transfer As all subjects are Failed');
			$this->session->unset_userdata('matcheddetails');
            redirect(base_url() . 'Dbftojaiibcredit/login');
            exit;
		}
        $otp_data = $this->db->query("SELECT * FROM verify_otp_dbftojaiibcredit 
                                    WHERE regnumber = '" . $regnumber . "'
                                    AND is_otp_verified = 'y'
                                    AND otp_verified_on >= '" . date("Y-m-d H:i:s", strtotime('today')) . "'")->row();
									//echo $this->db->last_query();die;									
        if (empty($otp_data)) {
            $this->session->set_flashdata('error','Please login again');
			$this->session->unset_userdata('matcheddetails');
            redirect(base_url() . 'Dbftojaiibcredit/login');
            exit;
        }

		$otp_data = $this->db->query("SELECT * FROM verify_otp_dbftojaiibcredit 
                                    WHERE regnumber = '" . $dbfregnumber . "'
                                    AND is_otp_verified = 'y'
                                    AND otp_verified_on >= '" . date("Y-m-d H:i:s", strtotime('today')) . "'")->row();
									//echo $this->db->last_query();die;									
        if (empty($otp_data)) {
            $this->session->set_flashdata('error','Please login again');
            redirect(base_url() . 'Dbftojaiibcredit/login');
            exit;
        }

        $valcookie= applyexam_get_cookie();
		if($valcookie)
		{
			delete_cookie('examid');
		}
		
		$data['validation_errors'] = '';
		
		$flag=1;$checkqualifyflag=0;
		$cookieflag=1;
			         

		$aCandidate = array();
		if ($regnumber) {

			$searchStr = $regnumber;

			$dbCandidate_ar = $this->master_model->getRecords('member_registration', array('regnumber' => $dbfregnumber,'isdeleted' => '0','registrationtype'=>'DB'));
			$dbCandidate = $dbCandidate_ar[0];

			$aCandidate_ar = $this->master_model->getRecords('member_registration', array('regnumber' => $regnumber,'isdeleted' => '0','registrationtype'=>'O'));
			$aCandidate = $aCandidate_ar[0];
			
			if (count($aCandidate) > 0) {

					$message=$this->get_alredy_applied_examname($aCandidate['regnumber'],$examcode);

					if( isset($aCandidate['regnumber'])){

						
						//ask user to wait for 5 min, until the payment transaction process
						$valcookie=$aCandidate['regnumber'];
						if($valcookie)
						{
							$regnumber= $valcookie;
							$checkpayment=$this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$regnumber,'status'=>'2','pay_type'=>$this->config->item('dbftojaiibcredit_pay_type')),'',array('id'=>'DESC'));
							if(count($checkpayment) > 0)
							{
								$endTime = date("Y-m-d H:i:s",strtotime("+5 minutes",strtotime($checkpayment[0]['date'])));
								$current_time= date("Y-m-d H:i:s");
								//priyanka d- 11-july-23 >> get real payment status from billdesk if not found then allow candidate to apply imidiatly

								$allowForApply=0;
								$responsedata = $this->billdesk_pg_model->billdeskqueryapi($checkpayment[0]['receipt_no']);
								
								if(isset($responsedata['status']) && $responsedata['status']==404) {
									$allowForApply=1;
									$toUpdateData = array(
										
										'status' => 0,
									);
									$where = array('id' => $checkpayment[0]['id']);
									$this->db->update('payment_transaction', $toUpdateData, $where);
								}
								

								if(strtotime($current_time)<=strtotime($endTime) && $allowForApply==0)
								{
									$cookieflag=0;
								}
								else
								{
									delete_cookie('examid');
								}
							}
							else
							{
								delete_cookie('examid');
							}
						}
						else
						{
							delete_cookie('examid');
						}
						//End Of ask user to ask user to wait for 5 min, until the payment transaction process

						if($cookieflag==0)
						{
							$data=array('middle_content'=>'dbftojaiibcredit/exam_apply_cms_msg');
							return $this->load->view('dbftojaiibcredit/nm_common_view',$data);
						}
						if($flag==0 && $cookieflag==1)
						{
							$data=array('middle_content'=>'dbftojaiibcredit/not_eligible','check_eligibility'=>$message);
							return $this->load->view('dbftojaiibcredit/nm_common_view',$data);
						}
						
					}
				}
				else {
						$data=array('middle_content'=>'dbftojaiibcredit/not_eligible','check_eligibility'=>$message);
							return $this->load->view('dbftojaiibcredit/nm_common_view',$data);
				}
			
		}

		$today_date=date('Y-m-d');
		$this->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
		$fee_details = $this->master_model->getRecords('dbftojaiibcredit_fee_master', array('fee_delete' => '0'));
			$fee = $fee_details[0];

		if(isset($_POST['btnSubmit']))  	
		{

            	$this->form_validation->set_rules('credit_subjects[]','Subjects','trim|max_length[30]|required|alpha_numeric|xss_clean');
				$this->form_validation->set_rules('dbf_regnumber','DBF Member Number','trim|max_length[30]|required|numeric|xss_clean|callback_cross_check_dob');
				$this->form_validation->set_rules('display_name','Full Name','trim|required|xss_clean');
				$this->form_validation->set_rules('dateofbirth','Date of birth','trim|required|xss_clean');
				$this->form_validation->set_rules('mobile','Mobile Number','trim|required|xss_clean');
				$this->form_validation->set_rules('balance_attempt_left','Balance Attempt Left','trim|required|xss_clean');
				$this->form_validation->set_rules('email','Email Id','trim|required|xss_clean');
				$this->form_validation->set_rules('bank_letter_date','Date of Bank Appointment letter','trim|required|xss_clean|callback_valid_date');
				$this->form_validation->set_rules('bank_joining_date','Date of Bank Joining','trim|required|xss_clean|callback_valid_date|callback_check_bank_joining_date');

				if(!isset($_POST['hiddenoffer_letter']) || !file_exists('./uploads/offer_letter/'.$_POST['hiddenoffer_letter']) ) {
					$this->form_validation->set_rules('offer_letter','Offer Letter Document','file_required|file_allowed_type[pdf]|file_size_max[5000]|callback_offer_letter_upload');
				}
				
				
				if($this->form_validation->run()==TRUE)
				{
					$errFlag=0;
					$dbfmemberdetails =array();
					$this->db->where('isactive', '1');
					$this->db->where('registrationtype', 'DB');
					$this->db->where('regnumber=', $dbfregnumber );
					$dbfmemberdetails = $this->master_model->getRecords('member_registration');

					if(isset($_POST['regnumber']) && $_POST['regnumber']!=$regnumber) {
						
						$errFlag=1;
					}
					if(isset($_POST['display_name']) && $_POST['display_name']!=$dbfmemberdetails[0]['displayname']) {
						//$errFlag=1;
					}
					if(isset($_POST['mobile']) && $_POST['mobile']!=$dbfmemberdetails[0]['mobile']) {
						$errFlag=1;
					}
					if(isset($_POST['email']) && $_POST['email']!=$dbfmemberdetails[0]['email']) {
						$errFlag=1;
					}
					if($errFlag==1) {
						//echo'<pre>'.$regnumber;print_r($dbfmemberdetails);echo'<pre>';print_r($_POST);exit;
						$this->session->set_flashdata('error','Something went wrong');
						redirect(base_url().'Dbftojaiibcredit/candidate_details/');
						exit;
					}

							$log_title   = "DBFTOJAIIB credit transfer:" . $dbfmemberdetails[0]['regnumber'];
                            $log_message = serialize($_POST);
                            $rId         = $dbfmemberdetails[0]['regnumber'];
                            $regNo       = $regnumber;
                            storedUserActivity($log_title, $log_message, $rId, $regNo);

					$offer_letter = $_POST['hiddenoffer_letter'];

					if(isset($_FILES['offer_letter']['name']) &&($_FILES['offer_letter']['name']!=''))
						{
							$img = "offer_letter";
							$date = date('Y-m-d H:i:s');
							$tmp_nm = strtotime($date).rand(0,100);
							$new_filename = 'offer_letter_'.$_POST['regnumber'];
							$config=array('upload_path'=>'./uploads/offer_letter',
											'allowed_types'=>'pdf',
											'file_name'=>$new_filename,);
									
							$this->upload->initialize($config);
							
								if($this->upload->do_upload($img))
								{
									$dt=$this->upload->data();
									$file=$dt['file_name'];
									$offer_letter_file = $dt['file_name'];
									$offer_letter = base_url()."uploads/offer_letter/".$offer_letter_file;
								}else{
									$this->session->set_flashdata('error','Offer Letter Document :'.$this->upload->display_errors());
								}
							
						}
					
						$credit_subjects = '';
						foreach($_POST['credit_subjects'] as $credit_subject) {
							$sub_details = $this->master_model->getRecords('subject_master', array(
								'exam_code'      => 420,
								'subject_delete' => '0',
								'subject_code'   => $credit_subject,
							), 'subject_description');

							
							if(!in_array($credit_subject,$not_eligible_subject_details_Arr))
								$credit_subjects .= $credit_subject.':'.$sub_details[0]['subject_description'].',';
						}
						$credit_subjects = rtrim($credit_subjects,',');

						$user_data=array(	
										'display_name'				=>$dbCandidate["displayname"],
										'mobile'					=>$dbCandidate["mobile"],
										'dateofbirth'				=>$dbCandidate["dateofbirth"],
										'email'						=>$dbCandidate["email"],
										'dbf_regnumber'				=>$dbCandidate["regnumber"],
										'credit_subjects'			=>$credit_subjects,
										'bank_letter_date'			=>date('Y-m-d',strtotime($_POST["bank_letter_date"])),
										'bank_joining_date'			=>date('Y-m-d',strtotime($_POST["bank_joining_date"])),
										'regnumber'					=>$regnumber,	
										'state'						=>$aCandidate['state'],	
										'fee'						=>$fee,	
										'offer_letter_file'			=> $offer_letter_file,
										'offer_letter'				=> $offer_letter,
										'balance_attempt_left'      => $balance_attempt_left
									);

					$this->session->set_userdata('enduserinfo',$user_data);

					$log_title ="DBFTOJAIIBCR session enduserinfo:".$dbCandidate["regnumber"];
					$log_message = serialize($user_data);
					$rId = $dbCandidate["regnumber"];
					$regNo = $regnumber;
					storedUserActivity($log_title, $log_message, $rId, $regNo);

					redirect(base_url().'Dbftojaiibcredit/preview');
					
				}
				
		}

		//echo'<pre>';print_r($not_eligible_subject_details_Arr);exit;
        $data = array(
			'middle_content' => 'dbftojaiibcredit/dbftojaiibcredit_form','not_eligible_subject_details_Arr'=>$not_eligible_subject_details_Arr,'eligible_subject_details'=>$eligible_subject_details,'aCandidate' => $aCandidate,'dbCandidate' => $dbCandidate,'subject_details'=>$subject_details,'regnumber'=>$regnumber,'fee'=>$fee,'balance_attempt_left'=>$balance_attempt_left
		);
        $this->load->view('dbftojaiibcredit/common_view_fullwidth', $data);
    }
	//callback to validate photo
    public function offer_letter_upload()
    {
        if ($_FILES['offer_letter']['size'] != 0) {
            return true;
        } else {
            $this->form_validation->set_message('offer_letter_upload', "No Offer Letter Document file selected");
            return false;
        }
    }

	public function cross_check_certificate() {

		$result = $this->cross_check_certificate_func();	
		if($result=='false') {
			$str='Invalid Certificate Number';
			$this->form_validation->set_message('cross_check_certificate', $str); 
			return false;
		}

	}
	public function ajax_cross_check_certificate() {

		$result = $this->cross_check_certificate_func();
		
		
		if($result!='false') {
			$result = (array)$result;
			$certificate_date = $result['certificate_date']; $certificate_date_msg = 0;

			if($certificate_date < '01-10-2019')
				$certificate_date_msg = 1;
			//echo'<pre>';print_r($result);
			echo json_encode(array('certificate_date'=>$certificate_date,'certificate_date_msg'=>$certificate_date_msg));
		}
		else {
			echo 'false';
		}
	}
	
	public function cross_check_dob() //priyanka d >> 11-july-23 added below condition
	{
		
		$returnArr = $this->cross_check_dob_func();
		$dbfmemberdetails = $returnArr['dbfmemberdetails'];
		$omemberdetails = $returnArr['omemberdetails'];

		if(date('Y-m-d',strtotime($dbfmemberdetails[0]['dateofbirth'])) != date('Y-m-d',strtotime($omemberdetails[0]['dateofbirth'])))
		{
			$str='Details are not Matched with entered member number';
			$this->form_validation->set_message('cross_check_dob', $str); 
			return false;
		}
		else  {
			return true;
		}
		return false;
	}
	
	public function cross_check_dob_func(){
		
		$dbfmemberdetails = $omemberdetails = array();
		$this->db->where('isactive', '1');
		$this->db->where('registrationtype', 'DB');
		$this->db->where('regnumber=', $this->input->post('dbf_regnumber') );
		$dbfmemberdetails = $this->master_model->getRecords('member_registration');
		
		//echo'<pre>';print_r($dbfmemberdetails);exit;

		$this->db->where('isactive', '1');
		$this->db->where('registrationtype', 'O');
		$this->db->where('regnumber=', $this->input->post('regnumber') );
		$omemberdetails = $this->master_model->getRecords('member_registration');

		return array('dbfmemberdetails'=>$dbfmemberdetails,'omemberdetails'=>$omemberdetails);
	}
    //Preview of register form 
	public function preview()
    {
		//echo "IPPB Service is not available due to maintenance activity from 5 PM to 07 PM."; exit;
     
         if(!$this->session->userdata('enduserinfo'))
         {
            redirect(base_url());
         }
					 	$log_title ="dbftojaiibcredit session enduserinfo on preview page:".$this->session->userdata['enduserinfo']['regnumber'];
						$log_message = serialize($this->session->userdata('enduserinfo'));
						$rId = $this->session->userdata['enduserinfo']['regnumber'];
						$regNo = $this->session->userdata['enduserinfo']['regnumber'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);
        
         
         if(!isset($this->session->userdata['matcheddetails']) || empty($this->session->userdata['matcheddetails'])) {
			$this->session->set_flashdata('error','Please login again');
            redirect(base_url() . 'Dbftojaiibcredit/login');
            exit;
		}
		$regnumber = $this->session->userdata['matcheddetails']['regnumber'];
		$dbfregnumber = $this->session->userdata['matcheddetails']['dbfregnumber'];

		$dbCandidate_ar = $this->master_model->getRecords('member_registration', array('regnumber' => $dbfregnumber,'isdeleted' => '0','registrationtype'=>'DB'));
			$dbCandidate = $dbCandidate_ar[0];
		//
		$aCandidate_ar = $this->master_model->getRecords('member_registration', array('regnumber' => $regnumber,'isdeleted' => '0','registrationtype'=>'O'));
		$aCandidate = $aCandidate_ar[0];
		//	echo'<pre>';print_r($dbCaaCandidatendidate_ar);exit;


		$this->db->join('subject_master', 'subject_master.subject_code =eligible_master.subject_code');
           
            $this->db->where("subject_master.subject_delete", '0');
            $this->db->where("eligible_master.exam_code ", '420');
            $this->db->where("eligible_master.member_no", $dbfregnumber);
            $eligible_subject_details = $this->master_model->getRecords('eligible_master');

			//echo $this->db->last_query();exit;
		if(!$eligible_subject_details || empty($eligible_subject_details)) {
			$this->session->set_flashdata('error','You are not eligible for Credit Transfer');
            redirect(base_url() . 'Dbftojaiibcredit/login');
            exit;
		}
		$not_eligible_subject_details_Arr = array();
		foreach($eligible_subject_details as $s) {
			if($s['exam_status']=='F') {
				$not_eligible_subject_details_Arr[]=$s['subject_code'];
			}
		}
		$this->db->order_by("subject_code", "asc");
		$subject_details = $this->master_model->getRecords('subject_master', array(
								'exam_code'      => 420,
								'subject_delete' => '0',
								
							));

				 			
         $data=array('middle_content'=>'dbftojaiibcredit/preview_form','subject_details'=>$subject_details,'not_eligible_subject_details_Arr'=>$not_eligible_subject_details_Arr,'dbCandidate'=>$dbCandidate,'aCandidate'=>$aCandidate,'enduserinfo'=>$this->session->userdata['enduserinfo']);
         $this->load->view('dbftojaiibcredit/common_view_fullwidth',$data);
    }

    public function accessdenied()
	{
        $message='<div style="color:#F00">You are not eligible to register for the selected examination. For details refer the link <strong>"Important Notice of Diploma and Certificate Examination syllabus" </strong> at the institutes website</div>';
        $data=array('middle_content'=>'dbftojaiibcredit/access-denied-registration','check_eligibility'=>$message);
        $this->load->view('dbftojaiibcredit/common_view_fullwidth',$data);
	}

	public function send_otp()
	{
		if (isset($_POST['regnumber']) && !empty($_POST['regnumber'])) {
			$user_otp_send_on = date('Y-m-d H:i:s');
			$user_otp_expired_on = date('Y-m-d H:i:s', strtotime('+30 minutes', strtotime($user_otp_send_on)));
			$otp = $this->generate_otp();
			
			$_POST['regnumber']=$this->session->userdata['aCandidate_ar']['regnumber'];
			$_POST['firstname']=$this->session->userdata['aCandidate_ar']['firstname'];
			$_POST['email']=$this->session->userdata['aCandidate_ar']['email'];
			$_POST['mobile']=$this->session->userdata['aCandidate_ar']['mobile'];
			$stud_arr = array(
				'regnumber' => $_POST['regnumber'],
				'firstname' => $_POST['firstname'],
				'email' => $_POST['email'],
				'mobile' => $_POST['mobile']
			);

			// with this function send otp to user via sms
			$this->send_otp_via_api($otp, $stud_arr);

			$this->db->delete('verify_otp_dbftojaiibcredit', array('regnumber' => $_POST['regnumber']));
			$data = array(
				
				'regnumber' => $_POST['regnumber'],
				'email' => $_POST['email'],
				'mobile' => $_POST['mobile'],
				'user_otp' => $otp,
				'otp_remove' => 'n',
				'user_otp_send_on' => $user_otp_send_on,
				'user_otp_expired_on' => $user_otp_expired_on,
				'is_otp_verified' => 'n',
			);
			$this->db->insert('verify_otp_dbftojaiibcredit', $data);
			$inserted_id = $this->db->insert_id();

			echo json_encode(array(
				'status' => 'success',
				'msg' => 'OTP Send to user on mobile '.$_POST['mobile'],
				'sec' => $this->check_time($user_otp_send_on),
				'inserted_id' => $inserted_id,
				// 'user_otp' => $otp,
			));
			exit;
		}
	}

	function resend_otp()
	{
		if (isset($_POST['otp_id']) && !empty($_POST['otp_id'])) {
			$otp_id = $_POST['otp_id'];
			$user_otp_send_on = date('Y-m-d H:i:s');
			$user_otp_expired_on = date('Y-m-d H:i:s', strtotime('+30 minutes', strtotime($user_otp_send_on)));
			$otp = $this->generate_otp();

			$_POST['regnumber']=$this->session->userdata['aCandidate_ar']['regnumber'];
			$_POST['firstname']=$this->session->userdata['aCandidate_ar']['firstname'];
			$_POST['email']=$this->session->userdata['aCandidate_ar']['email'];
			$_POST['mobile']=$this->session->userdata['aCandidate_ar']['mobile'];

			$stud_arr = array(
				'regnumber' => $_POST['regnumber'],
				'firstname' => $_POST['firstname'],
				'email' => $_POST['email'],
				'mobile' => $_POST['mobile']
			);

			// with this function send otp to user via sms
			$this->send_otp_via_api($otp, $stud_arr);

			$data = array(
				'user_otp' => $otp,
				'user_otp_send_on' => $user_otp_send_on,
				'user_otp_expired_on' => $user_otp_expired_on,
				'is_otp_verified' => 'n',
				'user_wrong_otp_count' => 0,
				'otp_remove' => 'n',
				'otp_verified_on' => null,
			);
			$where = array('otp_id' => $otp_id);
			$this->db->update('verify_otp_dbftojaiibcredit', $data, $where);
			echo json_encode(array(
				'status' => 'success',
				'sec' => $this->otptime,
				'user_otp_send_on' => $user_otp_send_on,
				'user_otp_expired_on' => $user_otp_expired_on,
				'updated_id' => $otp_id,
				// 'user_otp' => $otp
			));
			exit;
		} else {
			echo json_encode(array(
				'status' => 'failed',
			));
			exit;
		}
	}

	function verify_otp()
	{
		if (isset($_POST['submitted_otp']) && !empty($_POST['submitted_otp']) && isset($_POST['otp_id']) && !empty($_POST['otp_id'])) {
			$user_otp = $_POST['submitted_otp'];
			$otp_id = $_POST['otp_id'];
			$otp_data = $this->db->query("Select * from verify_otp_dbftojaiibcredit where  otp_id=" . $otp_id)->row();
			
			
			if (!empty($otp_data)) {
				// if "expire time" less than "current time" then otp expired
				if (strtotime($otp_data->user_otp_expired_on) < strtotime(date('Y-m-d H:i:s'))) {
					echo json_encode(array(
						'status' => 'failed',
						'msg' => 'OTP Expired! please click on resend to receive otp again.'
					));
					exit;
				} else {
					// if user otp match with db otp AND expired_time is greater than current time then success
					if (($user_otp == $otp_data->user_otp &&
						strtotime($otp_data->user_otp_expired_on) >= strtotime(date('Y-m-d H:i:s')))) {

						// otp verified - db update
						$otp_verified_on = date('Y-m-d H:i:s');
						$data = array(
							'user_otp' => '',
							'otp_remove' => 'y',
							'is_otp_verified' => 'y',
							'otp_verified_on' => $otp_verified_on,
						);
						$where = array('otp_id' => $otp_id);
						$this->db->update('verify_otp_dbftojaiibcredit', $data, $where);

						
						$this->db->where('isactive', '1');
						$this->db->where('regnumber=', $this->session->userdata['aCandidate_ar']['regnumber'] );
						$memberdetails = $this->master_model->getRecords('member_registration');
						
						$registerType = $memberdetails[0]['registrationtype'];


						
						echo json_encode(array(
							'registerType'=>$registerType,
							'status' => 'success',
							'msg' => 'OTP Matched! redirecting...'
						));
						exit;
					} else {

						// wrong otp attempt - db update
						$otp_verified_on = date('Y-m-d H:i:s');
						$wrong_count = ($otp_data->user_wrong_otp_count + 1);
						$data = array(
							'user_wrong_otp_count' => $wrong_count,
						);
						$where = array('otp_id' => $otp_id);
						$this->db->update('verify_otp_dbftojaiibcredit', $data, $where);

						echo json_encode(array(
							'status' => 'failed',
							'msg' => 'Wrong OTP! please try again'
						));
						exit;
					}
				}
			} else {
				echo json_encode(array(
					'status' => 'failed',
					'msg' => 'Invalid user!!!'
				));
				exit;
			}
			die;
		}
	}

	public function generate_otp()
	{
		return rand(100000, 999999);
		// return '111111';
	}

	public function send_otp_via_api($otp, $stud_arr)
	{

		// SEND OTP ON MOBILE (SMS) : START
		$mobile = $stud_arr['mobile'];
		
		//priyanka d - 25-jan-24 >> added code to send sms from mobicom
		$emailerstr1 = $this->master_model->getRecords('emailer', array('emailer_name' => "dbftojaiibcredit_email"));
		
		$sms_final_str1 = str_replace("{#var#}",$otp,  $emailerstr1[0]['sms_text']);

		$this->master_model->send_sms_common_all(($mobile), $sms_final_str1, $emailerstr1[0]['sms_template_id'], $emailerstr1[0]['sms_sender']); 

		//$this->master_model->send_sms_common_all(9145642016, $sms_final_str1, $emailerstr1[0]['sms_template_id'], $emailerstr1[0]['sms_sender']); 

		// SEND OTP ON MOBILE (SMS) : END

		// SEND OTP ON EMAIL : START
		$ippb_otp_mail_arr = array(
			'to' => 'amit@iibf.org.in',//$stud_arr['email'],
			'cc'=>'dd.it2@iibf.org.in',
			'subject' => 'Login OTP of DBf to JAIIB credit transfer From IIBF',
			'message' => $sms_final_str1,
		);
		return $this->Emailsending->mailsend($ippb_otp_mail_arr);
		// SEND OTP ON EMAIL : END

	}

    function check_time($time)
    {
        $timeMobileFirst  = strtotime($time);
        $currentTimeInSec = strtotime(date('Y-m-d H:i:s'));

        $remainingTimeMobile = 0;
        if ($timeMobileFirst <= $currentTimeInSec) {
            $diffTimeMobile =  $currentTimeInSec - $timeMobileFirst;

            if ($diffTimeMobile < $this->otptime) {
                $remainingTimeMobile = $this->otptime - $diffTimeMobile;
            }
        }
        return $remainingTimeMobile;
    }

        //callback to validate photo
    function scannedphoto_upload(){
        if($_FILES['scannedphoto']['size'] != 0){
            return true;
        }  
        else{
            $this->form_validation->set_message('scannedphoto_upload', "No Scanned Photograph file selected");
            return false;
        }
    }

        //callback to validate scannedsignaturephoto
    function scannedsignaturephoto_upload(){

            if($_FILES['scannedsignaturephoto']['size'] != 0){
            return true;
        }  
        else{
            $this->form_validation->set_message('scannedsignaturephoto_upload', "No  Scanned Signature file selected");
            return false;
        }
    }
    
    //callback to validate idproofphoto
    function idproofphoto_upload(){
            if($_FILES['idproofphoto']['size'] != 0){
            return true;
        }  
        else{
            $this->form_validation->set_message('idproofphoto_upload', "No Id proof file selected");
            return false;
        }
    }	
  
    //function to add membr in registration table and member_exam table
    public function addmember()
    {

		
        $flag=1;
        
        $data['validation_errors'] = '';

		
        //check email,mobile duplication on the same time from different browser!!
        $check_duplication = $this->master_model->getRecords('dbftojaiibcredit_registrations', array('regnumber' => $this->session->userdata['enduserinfo']['regnumber'],'pay_status' => '1' ));
        
       // echo $this->db->last_query();exit;
        if(isset($_POST['btnSubmit']))  	
        
        {

            $image_error_flag = 0;           
           
            
            $regnumber = ($this->session->userdata['enduserinfo']['regnumber']);
            $dbf_regnumber = ($this->session->userdata['enduserinfo']['dbf_regnumber']);
            $mobile = ($this->session->userdata['enduserinfo']['mobile']);
            $dateofbirth = date('Y-m-d',strtotime($this->session->userdata['enduserinfo']['dateofbirth']));
            $email= ($this->session->userdata['enduserinfo']['email']);
            $credit_subjects = ($this->session->userdata['enduserinfo']['credit_subjects']);
			$bank_letter_date = date('Y-m-d',strtotime($this->session->userdata['enduserinfo']['bank_letter_date']));
			$bank_joining_date = date('Y-m-d',strtotime($this->session->userdata['enduserinfo']['bank_joining_date']));
			$offer_letter_file = ($this->session->userdata['enduserinfo']['offer_letter_file']);
            $balance_attempt_left = ($this->session->userdata['enduserinfo']['balance_attempt_left']);
            
            $date = date('Y-m-d H:i:s');
            

            if(count($check_duplication) <= 0)
           	{
               
                $insert_info = array(
                    'regnumber' => $regnumber,
                    'dbf_regnumber'=>$dbf_regnumber,
                    'mobile'=>$mobile,
                    'dateofbirth'=>$dateofbirth,
                    'email'=>$email,
                    'credit_subjects'=>$credit_subjects,
					'bank_letter_date'=>$bank_letter_date,
					'bank_joining_date'=>$bank_joining_date,
                    'date'=>$date,
					'offer_letter'=>$offer_letter_file,
					'balance_attempt_left'=>$balance_attempt_left
                );

                if($last_id =$this->master_model->insertRecord('dbftojaiibcredit_registrations',$insert_info,true))
                {
								$this->session->userdata['enduserinfo']['dbftojaiibcredit_registration_id'] = $last_id;
                                $this->session->set_userdata('dbftojaiibcredit_memberdata', $this->session->userdata['enduserinfo']);
								
                                if($this->config->item('exam_apply_gateway')=='sbi')
                                {
                                    redirect(base_url().'Dbftojaiibcredit/sbi_make_payment/');
                                }
                       
                }
                else{
                        $this->session->set_flashdata('error','Error while during registration.please try again!');
                        redirect(base_url().'Dbftojaiibcredit/login');
                }
            }
			else{
				$this->session->set_flashdata('error','You have already requested for DBF to JAIIB Conversion');
				redirect(base_url().'Dbftojaiibcredit/login');
		}
        }
    }



    //Thank you message to end user
    public function acknowledge($MerchantOrderNo=NULL)
    {
	//	error_reporting(E_ALL);ini_set('display_errors', '1');
        $password=$decpass='';
        $data=array();
        //Query to get Payment details	
        $payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($MerchantOrderNo)),'member_regnumber,transaction_no,date,amount,exam_code,status');
        
        
      //  $this->db->join('exam_invoice','exam_invoice.receipt_no=payment_transaction.receipt_no');
        $this->db->join('payment_transaction','payment_transaction.ref_id=dbftojaiibcredit_registrations.id');
		$this->db->where('payment_transaction.pay_type',$this->config->item('dbftojaiibcredit_pay_type'));
        $result=$this->master_model->getRecords('dbftojaiibcredit_registrations',array('dbftojaiibcredit_registrations.dbf_regnumber'=>$payment_info[0]['member_regnumber']));
		//echo $this->db->last_query(); exit;
        
		if(count($result) <= 0)
        {redirect(base_url().'/Dbftojaiibcredit/login');}

        $data=array('application_number'=>$payment_info[0]['member_regnumber'],'payment_info'=>$payment_info,'result'=>$result);
        $this->load->view('dbftojaiibcredit/profile_thankyou',$data);
    }

    	##------------------Exam appky with SBI Payment Gate-way(Pratibha Borse)---------------##

	public function sbi_make_payment()
	{

		if(!$this->session->userdata('enduserinfo')) {
			$this->session->set_flashdata('flsh_msg', 'Your session has been expired. Please try again...!');
			redirect(base_url() . 'Dbftojaiibcredit/login'); 
		}
		$cgst_rate=$sgst_rate=$igst_rate=$tax_type='';
      $cgst_amt=$sgst_amt=$igst_amt='';
      $cs_total=$igst_total='';
		$regnumber = $this->session->userdata('enduserinfo')['regnumber'];
		$dbf_regnumber = $this->session->userdata('enduserinfo')['dbf_regnumber'];
		$state = $this->session->userdata('enduserinfo')['state'];
		$valcookie = register_get_cookie();
        if ($valcookie) {
            $regid     = $valcookie;
            $checkuser = $this->master_model->getRecords('member_registration', array(
                'regid' => $this->session->userdata('enduserinfo')['regnumber'],
                'regnumber !=' => '',
                'isactive !=' => '0'
            ));
			//echo $this->db->last_query(); exit;
            if (count($checkuser) > 0) {
                delete_cookie('regid');
                redirect('http://iibf.org.in');
            } else {
                $checkpayment = $this->master_model->getRecords('payment_transaction', array(
                    'pay_type' => $this->config->item('dbftojaiibcredit_pay_type'),
					'member_regnumber'=>$this->session->userdata('enduserinfo')['dbf_regnumber'],
                    'status' => '2'
                ));
                if (count($checkpayment) > 0) {
                    $endTime      = date("Y-m-d H:i:s", strtotime("+20 minutes", strtotime($checkpayment[0]['date'])));
                    $current_time = date("Y-m-d H:i:s");
                    if (strtotime($current_time) <= strtotime($endTime)) {
                        $flag = 0;
                    } else {
                        delete_cookie('regid');
                        redirect('http://iibf.org.in');
                    }
                } else {
                    $flag = 1;
                    delete_cookie('regid');
                    redirect('http://iibf.org.in');
                }
            }
        }

		
		if(isset($_POST['processPayment']) && $_POST['processPayment'])

		{


			$log_title ="Dbftojaiibcredit - Clicked on processPayment ".$this->session->userdata['enduserinfo']['dbf_regnumber'];
						$log_message = serialize($this->session->userdata('enduserinfo'));
						$rId = $this->session->userdata['enduserinfo']['dbf_regnumber'];
						$regNo = $this->session->userdata['enduserinfo']['regnumber'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);

			$pg_name = $this->input->post('pg_name');

			$regno = $this->session->userdata['dbftojaiibcredit_memberdata']['dbf_regnumber'];
           

			if($this->config->item('sb_test_mode'))

			{

				$amount = $this->config->item('exam_apply_fee');

			}

			else

			{
				$today_date=date('Y-m-d');
				$this->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$fee_details = $this->master_model->getRecords('dbftojaiibcredit_fee_master', array(
                   
                    'fee_delete' => '0'
                ));
				if($this->session->userdata['enduserinfo']['state']=='MAH')
					$amount=$fee_details[0]['cs_tot'];
				else
					$amount=$fee_details[0]['igst_tot'];

			}

			

			if($amount==0 || $amount=='')

			{

				$this->session->set_flashdata('error','Fee can not be zero(0) or Blank!!');

				redirect(base_url().'Dbftojaiibcredit/preview/');

			}

			$getstate = $this->master_model->getRecords('state_master', array('state_code' => $state, 'state_delete' => '0'));

			// Create transaction

			
            if ($pg_name == 'sbi'){
                $gateway='sbiepay';
            }else{
                $gateway='billdesk';
            }

            $pg_flag='DBFTOJAIIBCR';
            $exam_desc= $this->session->userdata['dbftojaiibcredit_memberdata']['exam_desc'];

			$insert_data = array(

				'member_regnumber' => $dbf_regnumber,
				'exam_code'        => 420,
				'amount'           => $amount,

				'gateway'          => $gateway,

				'date'             => date('Y-m-d H:i:s'),

				'pay_type'         => $this->config->item('dbftojaiibcredit_pay_type'),

				'ref_id'           => $this->session->userdata['enduserinfo']['dbftojaiibcredit_registration_id'],

				'description'      => 'DBF to JAIIB Credit Transfer',

				'status'           => '2',

				'pg_flag'=>$pg_flag,

			);

			

			$pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);

			

			$MerchantOrderNo = sbi_exam_order_id($pt_id);

			

			// payment gateway custom fields -

			$custom_field = $MerchantOrderNo."^iibfexam^".$MerchantOrderNo."^".$ref4;

			

			// update receipt no. in payment transaction -

			$update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);

			$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));

		

			if($this->session->userdata['enduserinfo']['state']=='MAH')
			{

				//set a rate (e.g 9%,9% or 18%)

				$cgst_rate=$this->config->item('cgst_rate');

				$sgst_rate=$this->config->item('sgst_rate');


					//set an amount as per rate

					$cgst_amt=$fee_details[0]['cgst_amt'];

					$sgst_amt=$fee_details[0]['sgst_amt'];

				 	//set an total amount

					$cs_total=$fee_details[0]['cs_tot'];

					$amount_base = $fee_details[0]['fee_amount'];




				$tax_type='Intra';

			

			}
			else{


					$igst_rate=$this->config->item('igst_rate');

					$igst_amt=$fee_details[0]['igst_amt'];

					$igst_total=$fee_details[0]['igst_tot'];

				 	$amount_base = $fee_details[0]['fee_amount'];

				    $tax_type='Inter';

			}

			if($getstate[0]['exempt']=='E')
			{

				 $cgst_rate=$sgst_rate=$igst_rate='';	

				 $cgst_amt=$sgst_amt=$igst_amt='';	

			}

			
				$center_details = $this->master_model->getRecords('center_master', array(
                   
                    'exam_name' => 420 ,'exam_period'=>'999'
                ));
 
			$invoice_insert_array=array('pay_txn_id'=>$pt_id,

											'receipt_no'                               => $MerchantOrderNo,
											'member_no'                                => $dbf_regnumber,
											'exam_code'                                => 420,
											'exam_period'                                => '999',
											'center_code'                          		=> $center_details[0]['center_code'],
											'center_name'                          		=> $center_details[0]['center_name'],
											'state_of_center'                          => $state,
											'app_type'                                 => 'DJT',
											'service_code'                             => $this->config->item('reg_service_code'),
											'qty'                                      => '1',
											'state_code'                               => $getstate[0]['state_no'],
											'state_name'                               => $getstate[0]['state_name'],
											'tax_type'                                 => $tax_type,
											'fee_amt'                                  => $fee_details[0]['fee_amount'],
											'cgst_rate'                                => $cgst_rate,
											'cgst_amt'                                 => $cgst_amt,
											'sgst_rate'                                => $sgst_rate,
											'sgst_amt'                                 => $sgst_amt,
											'igst_rate'                                => $igst_rate,
											'igst_amt'                                 => $igst_amt,
											'cs_total'                                 => $cs_total,
											'igst_total'                               => $igst_total,
											'gstin_no'                                 => '',
											'exempt'                                   => $getstate[0]['exempt'],
											'created_on'                               => date('Y-m-d H:i:s')
                                                    );

								

			$inser_id=$this->master_model->insertRecord('exam_invoice',$invoice_insert_array,true);

			//echo $this->db->last_query();exit;

			$log_title ="DBFTOJAIIBCR exam invoice insertion :".$inser_id;

			$log_message = $this->db->last_query();// serialize($invoice_insert_array);

			$rId = $inser_id;

			$regNo = $inser_id;

			storedUserActivity($log_title, $log_message, $rId, $regNo);

			

			//if exam invocie entry skip

			if($inser_id==''){

				$inser_id=$this->master_model->insertRecord('exam_invoice',$invoice_insert_array,true);

				

				$log_title ="DBFTOJAIIBCR exam invoice insertion again :".$inser_id;

				$log_message = serialize($invoice_insert_array);

				$rId = $inser_id;

				$regNo = $inser_id;

				storedUserActivity($log_title, $log_message, $rId, $regNo);

			}

		
			{
				if($this->get_client_ip() =='115.124.115.75' || $this->get_client_ip() =='115.124.115.69' ) {
					$amount=1;
				}
		
                $custom_field_billdesk    = $dbf_regnumber . "-" . $pg_flag . "-" . $MerchantOrderNo . "-" . $dbf_regnumber;
                $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $dbf_regnumber, $dbf_regnumber, '', 'Dbftojaiibcredit/handle_billdesk_response', '', '', '', $custom_field_billdesk);
				 if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {

                    $data['bdorderid'] = $billdesk_res['bdorderid'];
                    $data['token']     = $billdesk_res['token'];
										$data['responseXHRUrl'] = $billdesk_res['responseXHRUrl']; 
										$data['returnUrl'] = $billdesk_res['returnUrl'];                    
                    $this->load->view('pg_billdesk/pg_billdesk_form', $data);
                }else{
                    $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                    redirect(base_url() . 'Dbftojaiibcredit/login');
                }
			}
			/*  End code */

		}

		else

		{

			$data['show_billdesk_option_flag'] = 1;
			$this->load->view('pg_sbi/make_payment_page', $data);
		}

	}

	
    public function handle_billdesk_response()
    {
        //error_reporting(E_ALL);ini_set('display_errors', '1');
      	
        $attachpath=$invoiceNumber='';

		//echo'<pre>';print_r($_REQUEST);exit;
        if (isset($_REQUEST['transaction_response'])) 
				{
           
            $response_encode = $_REQUEST['transaction_response'];
            $bd_response     = $this->billdesk_pg_model->verify_res($response_encode);
            $responsedata           = $bd_response['payload'];
            $attachpath             = $invoiceNumber             = '';
            $MerchantOrderNo        = $responsedata['orderid'];
            $transaction_no         = $responsedata['transactionid'];
            $transaction_error_type = $responsedata['transaction_error_type'];
            $transaction_error_desc = $responsedata['transaction_error_desc'];
            $bankid                 = $responsedata['bankid'];
            $txn_process_type       = $responsedata['txn_process_type'];
            $merchIdVal             = $responsedata['mercid'];
            $Bank_Code              = $responsedata['bankid'];
            $encData                = $_REQUEST['transaction_response'];
            $auth_status 			= $responsedata['auth_status'];

			

            $user_payment_txn_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status,id');
			// print_r($get_user_regnum); exit;
            if (empty($user_payment_txn_info)) {
                //redirect(base_url());
            }

			$qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
			if($auth_status == "0300" && $qry_api_response['auth_status'] == '0300' && $user_payment_txn_info[0]['status'] == 2)
			{				
				 //echo "---->"; print_r($get_user_regnum); exit;
                $update_data  = array(
                    'transaction_no'      => $transaction_no,
                    'status'              => 1,
                    'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                    'gateway'             =>'billdesk',
                    'auth_code'           => '0300',
                    'bankcode'            => $bankid,
                    'paymode'             => $txn_process_type,
                    'callback'            => 'B2B',
                );

                $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo,'pay_type'=>$this->config->item('dbftojaiibcredit_pay_type')));
				
				$log_title   = "DBFTOJAIIBCR payment paymentupdate";
                $log_message = serialize($update_data);
                $reg_id         = $user_payment_txn_info[0]['ref_id'];
              

                storedUserActivity($log_title, $log_message, $reg_id, $reg_id);

                /* Transaction Log */
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
               
                $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);


                //-------------------------------------------------------------------
				if($this->db->affected_rows())
				{
					if (count($user_payment_txn_info) > 0) {
                        //$custom_reg_id='JE'.date('Y').sprintf("%04d", $reg_id);

                        $update_mem_data = array('pay_status' => '1');
                        $this->master_model->updateRecord('dbftojaiibcredit_registrations', $update_mem_data, array('id' => $user_payment_txn_info[0]['ref_id']));
                    }
					$dbftojaiibcredit_registrations_details = $this->master_model->getRecords('dbftojaiibcredit_registrations', array('id' => $user_payment_txn_info[0]['ref_id']));
                  
                    //get invoice
                    $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $user_payment_txn_info[0]['id']));
                  
					$log_title   = "DBFTOJAIIBCR get invoice details :" . $reg_id;
					$log_message = serialize($this->db->last_query());
					storedUserActivity($log_title, $log_message, $reg_id, $reg_id);
                    if (count($getinvoice_number) > 0) {

                        $invoiceNumber = generate_dbftojaiibcr_invoice_number($getinvoice_number[0]['invoice_id']);
                        if ($invoiceNumber) {
                            $invoiceNumber = $this->config->item('dbftojaiibcredit_no_prefix').$invoiceNumber;
                        }

                        $update_data = array('invoice_no' => $invoiceNumber,'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                        $this->db->where('pay_txn_id', $user_payment_txn_info[0]['id']);
                        $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
                        
                        $attachpath = genarate_dbftojaiibcredit_invoice($getinvoice_number[0]['invoice_id'],$dbftojaiibcredit_registrations_details[0]['dbf_regnumber']);
                        $log_title   = "DBFTOJAIIBCR Invoice log update  :" . $reg_id;
                        $log_message = serialize($this->db->last_query());
                        storedUserActivity($log_title, $log_message, $reg_id, $reg_id);
                    }

                   $emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'dbftojaiibcredit_email'));
                    if(count($emailerstr) > 0)
                    {  
						$log_title   = "DBFTOJAIIBCR email to send  :" . $reg_id;
                        $log_message = serialize($this->db->last_query());
                        storedUserActivity($log_title, $log_message, $reg_id, $reg_id);
                      /*'to'=>$email,*/
                      $final_str = $emailerstr[0]['emailer_text'];
                  
                                $info_arr = array(
                                'to'=>$dbftojaiibcredit_registrations_details[0]['email'],
								'cc'=>'iibfdevp@esds.co.in',								
                                'from'=>$emailerstr[0]['from'],
                                'subject'=>$emailerstr[0]['subject'],
                                'message'=>$final_str
                                );

                          if($attachpath!='')
                          {
							

                            if($this->Emailsending->mailsend_attch($info_arr,$attachpath)){
								$log_title   = "DBFTOJAIIBCR email to send  attachpath :" . $attachpath;
                        	$log_message = $final_str;
                        	storedUserActivity($log_title, $log_message, $reg_id, $reg_id);
							}
                          }
                      
                        
                    }

			
				}
				else
				{
					$log_title ="DBFTOJAIIBCR B2B Update fail:".$user_payment_txn_info[0]['member_regnumber'];
					$log_message = serialize($update_data);
					$rId = $MerchantOrderNo;
					$regNo = $user_payment_txn_info[0]['member_regnumber'];
					storedUserActivity($log_title, $log_message, $rId, $regNo);	
				}
				
				redirect(base_url().'Dbftojaiibcredit/acknowledge/'.base64_encode($MerchantOrderNo));
				exit();
                //-----------------------------------------------------------

			}
			else /* if ($transaction_error_type == 'payment_authorization_error') */ 
			{
				
				if($auth_status == "0399" && $qry_api_response['auth_status'] == '0399'	){
					
					$update_data = array(
						'transaction_no' => $transaction_no,
						'status' => 0,
						'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
						'auth_code' => '0399',
						'bankcode' => $bankid,
						'paymode' => $txn_process_type,
						'callback' => 'B2B'
					);

					$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

					//-------------------------------------------------------------------------------------------
					//Query to get Payment details	

					
					//--------------------------------------------------------------------------------------------------
					$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
					$this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

					$this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
					redirect(base_url().'/Dbftojaiibcredit/login');
					//echo "Transaction failed";
			}
			}
		}
		else
		{
			redirect(base_url('/dbftojaiibcredit/trans_under_process'));
		}
		

    }

    public function trans_under_process()
    {     
	
		$this->load->view('dbftojaiibcredit/trans_under_process');
	}

	public function already_apply($exam_period_date=null)
	{
		$message          = 'Application for DBF to JAIIB certificate conversion by you. Hence you need not apply for the same.';
        $data=array('middle_content'=>'dbftojaiibcredit/already_apply','check_eligibility'=>$message);
        $this->load->view('dbftojaiibcredit/mem_apply_exam_common_view',$data);
	}


}