<?php

defined('BASEPATH') or exit('No direct script access allowed');


/**
 *
 * This Exam functionality made by PRATIBHA BORSE on date 26 May 2022 
 * 
 *  IPPB Exam same as CSC
 * 
 * Exam name-CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS - PAYMENTS BANKS
 * Subject name-  Inclusive Banking Thro' Business Correspondents - Module A
 * Subject code- 799
 * Exam code- 997
 * Exama period- 851
 * 
 * CSC venue is Shared beween csc and IPPB exam
 * In place of eligible_master using member_registration_ippb table 
 * Candidate data is auto populated in form
 * If user is new then inserting new member in member registration 
 * Else updating same record with existing memnumber 
 * Used SBI and Billdesk payment gateway
 * 
 */


class Ippbapplyexam extends CI_Controller
{

    public function __construct()
    {
		//echo "IPPB Service is not available due to maintenance activity from 12 PM to 02 PM."; exit;
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


        $cookie_name = "instruction";
        $cookie_value = "1";
        setcookie($cookie_name, $cookie_value, time() + (60 * 10), "/");
        $this->otptime = 60;

	//	echo "IPPB Service is not available for 30 minutes due to maintenance activity"; exit;	
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
    public function login()
    {
	// 	 if($this->get_client_ip() !='115.124.115.69')
	// {	
	// 	echo "IPPB Service is not available due to maintenance activity from 12 PM to 02 PM."; exit;	
	// }
	
        $aCandidate = array();
        if ($this->input->post('form_type') == 'search_form') {
            $this->form_validation->set_rules('searchStr', 'Enter Name or Membership no.', 'trim|required|xss_clean');
            if ($this->form_validation->run() == TRUE) {
                $searchStr = $this->input->post('searchStr');
                $aCandidate_ar = $this->master_model->getRecords('member_registration_ippb', array('emp_id' => $searchStr,'isdeleted' => '0'));
				$aCandidate = $aCandidate_ar[0];
				// echo "abc1----------------><pre>"; print_r(count($aCandidate_ar));  echo $this->db->last_query();//exit;

				if (count($aCandidate_ar) > 0) {
					$member_candidate = $this->master_model->getRecords('member_registration', array('email' => $aCandidate['email'],'mobile' => $aCandidate['mobile'],'isactive' => '1','registrationtype' => 'NM'));
					//Remove 'isactive' => '1'condition in order to fetch previous unsuccessful application Pooja Mane 2023-5-11
					//If member found in member registration table then show this details 
					// echo "abc2----------------><pre>"; print_r($member_candidate);  echo $this->db->last_query(); //exit;

					if (count($member_candidate) > 0) {
						// query to  tbl_member_image_base64 remove by vishal on 2023-04-11

						$member_candidate_data=array();


					
					//array merge pooja mane : 02-02-2023
					if(count($member_candidate_data)>0)
					{
						$aCandidate = array_merge($aCandidate_ar[0],$member_candidate[0],$member_candidate_data[0]);
					}else{
						$aCandidate =array_merge($aCandidate_ar[0],$member_candidate[0]);
					}
					}
				}
            	//    exit;
            }
        }

        $data = array('middle_content' => 'ippbapplyexam/ippb_exam_apply_login_search', 'aCandidate' => $aCandidate);
      
        $this->load->view('ippbapplyexam/common_view_fullwidth', $data);
    }

    //search candidate for ippb login
    public function candidate_details($emp_id)
    {
		//echo "IPPB Service is not available due to maintenance activity from 12 PM to 02 PM."; exit;
		//echo $emp_id;echo'<br>';
        $emp_id = base64_decode($emp_id);
		//$emp_id = '1017843';
        $otp_data = $this->db->query("SELECT * FROM verify_otp_ippb 
                                    WHERE emp_id = '" . $emp_id . "'
                                    AND is_otp_verified = 'y'
                                    AND otp_verified_on >= '" . date("Y-m-d H:i:s", strtotime('today')) . "'")->row();
									//echo $this->db->last_query();die;									
        if (empty($otp_data)) {
            $this->session->set_flashdata('error','Please login again');
            redirect(base_url() . 'Ippbapplyexam/login');
            exit;
        }

        $valcookie= applyexam_get_cookie();
		if($valcookie)
		{
			delete_cookie('examid');
		}
		$scannedphoto_file=$scannedsignaturephoto_file = $idproofphoto_file = $password=$var_errors='';
		$data['validation_errors'] = '';
		$examcode = $ExId = 997;
		$flag=1;$checkqualifyflag=0;
		$cookieflag=$exam_status=1;
		$check_exam_activation=check_exam_activate($ExId);
		 

        if($check_exam_activation==0)
		{
			redirect(base_url().'Ippbapplyexam/accessdenied/');
		}

        

		$aCandidate = array();
		if ($emp_id) {

			$searchStr = $emp_id;
			$aCandidate_ar = $this->master_model->getRecords('member_registration_ippb', array('emp_id' => $searchStr,'isdeleted' => '0'));
			$aCandidate = $aCandidate_ar[0];
			//echo $this->db->last_query();exit;
			
			if (count($aCandidate) > 0) {
				$member_candidate = $this->master_model->getRecords('member_registration', array('email' => $aCandidate['email'],'mobile' => $aCandidate['mobile'],'isactive' => '1','registrationtype' => 'NM'));
				
				//If member found in member registration table then show this details 
				if (count($member_candidate) > 0) {
				
					
					$member_candidate_data=array();
					if(count($member_candidate_data) > 0){
						$aCandidate=get_object_vars($member_candidate_data);
						
					}
					
					//array merge pooja mane : 02-02-2023
					$aCandidate = array_merge($aCandidate_ar[0],$member_candidate[0]);

					//Pratibha check eligible data here (if user have member reg number)
					if( isset($aCandidate['regnumber'])){

						//Query to check selected exam details
						$check_qualify_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
						
						//ask user to wait for 5 min, until the payment transaction process
						$valcookie=$aCandidate['regnumber'];
						if($valcookie)
						{
							$regnumber= $valcookie;
							$checkpayment=$this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$regnumber,'status'=>'2','pay_type'=>'2'),'',array('id'=>'DESC'));
							if(count($checkpayment) > 0)
							{
								$endTime = date("Y-m-d H:i:s",strtotime("+5 minutes",strtotime($checkpayment[0]['date'])));
								$current_time= date("Y-m-d H:i:s");
								if(strtotime($current_time)<=strtotime($endTime))
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

						if(count($check_qualify_exam) > 0)
						{
							//Condition to check the qualifying id exist
							if($check_qualify_exam[0]['qualifying_exam1']!='' && $check_qualify_exam[0]['qualifying_exam1']!='0' && $checkqualifyflag==0)
							{
								$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam1'],$examcode,$check_qualify_exam[0]['qualifying_part1']);
								$flag=$qaulifyarry['flag'];
								$message=$qaulifyarry['message'];
								if($flag==0)
								{
									$checkqualifyflag=1;
								}
							}
							if($check_qualify_exam[0]['qualifying_exam2']!='' && $check_qualify_exam[0]['qualifying_exam2']!='0' && $checkqualifyflag==0 )
							{	
								$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam2'],$examcode,$check_qualify_exam[0]['qualifying_part2']);
								$flag=$qaulifyarry['flag'];
								$message=$qaulifyarry['message'];
								if($flag==0)
								{
									$checkqualifyflag=1;
								}
							}
							if($check_qualify_exam[0]['qualifying_exam3']!='' && $check_qualify_exam[0]['qualifying_exam3']!='0' && $checkqualifyflag==0)
							{	
								$qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam3'],$examcode,$check_qualify_exam[0]['qualifying_part3']);
								$flag=$qaulifyarry['flag'];
								$message=$qaulifyarry['message'];
								if($flag==0)
								{
									$checkqualifyflag=1;
								}
							}
							else if($flag==1 && $checkqualifyflag==0)
							{


								$this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
								$check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>'NM','member_no'=>$aCandidate['regnumber']));
								if(count($check_eligibility_for_applied_exam) > 0)
								{
									foreach($check_eligibility_for_applied_exam as $check_exam_status)
									{
										if($check_exam_status['exam_status']=='F')
										{
											$exam_status=0;
										}
									}
									if($exam_status==1)
									{
										$flag=0;
										$message=$check_eligibility_for_applied_exam[0]['remark'];
									}
									else if($exam_status==0)
									{
										$check=$this->examapplied($aCandidate['regnumber'],$examcode);
										if(!$check)
										{
											$flag=1;
										}
										else
										{
											$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$examcode,'misc_master.misc_delete'=>'0'),'exam_month');
											$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
											$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
											//$message='1Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>.period. Hence you need not apply for the same.';
											$message='You have already applied for the exam, please wait till the result is declared. Hence you need not apply for the same.';
											$flag=0;
										}
									}
								}
								else
								{
									$check=$this->examapplied($aCandidate['regnumber'],$examcode);
									if($check)
									{
										$check_date=$this->examdate($aCandidate['regnumber'],$examcode);
									
										if(!$check_date)
										{
										$flag=1;

										}
										else
										{
											$message=$this->get_alredy_applied_examname($aCandidate['regnumber'],$examcode);
											$flag=0;
										}
									}
									else
									{
											$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$examcode,'misc_master.misc_delete'=>'0'),'exam_month');
											$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
											$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
											//$message='2Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>.period. Hence you need not apply for the same.';
											$message='You have already applied for the exam, please wait till the result is declared. Hence you need not apply for the same.';
										$flag=1;
									}
								}
							}
						}else
						{
							$flag=1;
						}

						//Query to check where exam applied successfully or not with transaction
						$is_transaction_doone=$this->master_model->getRecordCount('payment_transaction',array('exam_code'=>$examcode,'member_regnumber'=>$aCandidate['regnumber'],'status'=>'1'));
						if($is_transaction_doone >0)
						{
								$today_date=date('Y-m-d');
								$this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description');
								$this->db->where('exam_master.elg_mem_nm','Y');
								//$this->db->join('payment_transaction','payment_transaction.exam_code=member_exam.exam_code');
								$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
								$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
								$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
								$this->db->where('member_exam.pay_status','1');
								$applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>$examcode,'regnumber'=>$aCandidate['regnumber']));
						}
						########get Eligible createon date######
						$this->db->limit('1');
						$get_eligible_date=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_no'=>$aCandidate['regnumber']),'eligible_master.created_on');
						$eligiblecnt=0;
						if(count($applied_exam_info) > 0 &&  count($get_eligible_date) > 0 )
						{
							if(strtotime($applied_exam_info[0]['created_on'] ) > strtotime($get_eligible_date[0]['created_on']))
							{
								$eligiblecnt=$eligiblecnt+1;
							}
						}
						
						if($cookieflag==0)
						{
							$data=array('middle_content'=>'ippbapplyexam/exam_apply_cms_msg');
							$this->load->view('ippbapplyexam/nm_common_view',$data);
						}
						if($flag==0 && $cookieflag==1)
						{
							$data=array('middle_content'=>'ippbapplyexam/not_eligible','check_eligibility'=>$message);
							$this->load->view('ippbapplyexam/nm_common_view',$data);
						}
						else if($eligiblecnt)
						{

							$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$examcode,'misc_master.misc_delete'=>'0'),'exam_month');
							$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4);
							$exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
							//$message='3Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same.';
							$message='You have already applied for the exam, please wait till the result is declared. Hence you need not apply for the same.';
							$data=array('middle_content'=>'ippbapplyexam/already_apply','check_eligibility'=>$message);
							$this->load->view('ippbapplyexam/nm_common_view',$data);
						}

						//End pratibha eligible

					}
				}
			}
			
		}


		if(isset($_POST['btnSubmit']))  	
		{

            // echo "<pre>"; print_r($_POST); exit; 
			    ## Check if already applied within 1 hr
				$this->db->order_by('regid','DESC');
				$this->db->limit(1);
				$check_user = $this->master_model->getRecords('member_registration',array('email'=>$this->input->post('email'),'mobile'=>$this->input->post('mobile')),'regid,createdon');
				//echo $this->db->last_query();
				if(count($check_user) > 0)
				{
					 $created_date = $check_user[0]['createdon'];
					 $endTime = date("Y-m-d H:i:s",strtotime("+5 minutes",strtotime($check_user[0]['createdon'])));
					 $current_time= date("Y-m-d H:i:s");
					if(strtotime($current_time)<= strtotime($endTime))
					{
						$this->session->set_flashdata('error','Please apply after 5 minutes of your first attempt. i.e. 5 minutes after '.$created_date.'');
						redirect(base_url().'Ippbapplyexam/login');
					}
				}
							
		 		//echo '<pre>',print_r($_POST),'</pre>';exit;
			    $scribe_flag='N';
				$scannedphoto_file=$scannedsignaturephoto_file=$idproof_file=$outputphoto1=$outputsign1=$outputidproof1=$state='';
				$this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
				$this->form_validation->set_rules('firstname','First Name','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('addressline1','Addressline1','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('district','District','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('city','City','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('state','State','trim|required|xss_clean');
				
				
				if($this->input->post('state')!='')
				{
					$state=$this->input->post('state');
				}
				$this->form_validation->set_rules('pincode','Pincode/Zipcode','trim|required|numeric|xss_clean|callback_check_checkpin['.$state.']');
				$this->form_validation->set_rules('dob1','Date of Birth','trim|required|xss_clean');
				
				$this->form_validation->set_rules('gender','Gender','trim|required|xss_clean');
				$this->form_validation->set_rules('optedu','Qualification','trim|required|xss_clean');
				
				if(isset($_POST['middlename']) && $_POST['middlename']!='')
				{
					$this->form_validation->set_rules('middlename','Middle Name','trim|max_length[30]|alpha_numeric_spaces|xss_clean');
				}
				if(isset($_POST['lastname']) && $_POST['lastname']!='')
				{
					$this->form_validation->set_rules('lastname','Last Name','trim|max_length[30]|alpha_numeric_spaces|xss_clean');
				}
				
				if(isset($_POST['optedu']) && $_POST['optedu']=='U')
				{
					$this->form_validation->set_rules('eduqual1','Please specify','trim|required|xss_clean|callback_check_exam_eligibility['.$examcode.']');
				}
				else if(isset($_POST['optedu']) && $_POST['optedu']=='G')
				{
					$this->form_validation->set_rules('eduqual2','Please specify','trim|required|xss_clean|callback_check_exam_eligibility['.$examcode.']');
				}
				else if(isset($_POST['optedu']) && $_POST['optedu']=='P')
				{
					$this->form_validation->set_rules('eduqual3','Please specify','trim|required|xss_clean|callback_check_exam_eligibility['.$examcode.']');
				}
				
				if(isset($_POST['addressline2']) && $_POST['addressline2']!='')
				{
					$this->form_validation->set_rules('addressline2','Addressline2','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				}
				
				if(isset($_POST['addressline3']) && $_POST['addressline3']!='')
				{
					$this->form_validation->set_rules('addressline3','Addressline3','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				}
				
				if(isset($_POST['addressline4']) && $_POST['addressline4']!='')
				{
					$this->form_validation->set_rules('addressline4','Addressline4','trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				}
				
				if(isset($_POST['stdcode']) && $_POST['stdcode']!='')
				{
					$this->form_validation->set_rules('stdcode','STD Code','trim|max_length[4]|required|numeric|xss_clean');
				}
				
				if(isset($_POST['phone']) && $_POST['phone']!='')
				{
					$this->form_validation->set_rules('phone',' Phone No','trim|required|numeric|xss_clean');
				}
				$this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean');
				$this->form_validation->set_rules('mobile','Mobile','trim|required|numeric|min_length[10]|xss_clean');
				$this->form_validation->set_rules('venue[]','Venue','trim|required|xss_clean');
				$this->form_validation->set_rules('date[]','Date','trim|required|xss_clean');
				$this->form_validation->set_rules('time[]','Time','trim|required|xss_clean');
				// add photo update condition here 
				if(! isset($aCandidate['regnumber'])){
					  //echo "In regnumber validation"; exit;
					$this->form_validation->set_rules('scannedphoto','scanned Photograph','file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedphoto_upload');
					$this->form_validation->set_rules('scannedsignaturephoto','Scanned Signature Specimen','file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedsignaturephoto_upload');
					$this->form_validation->set_rules('idproof','Id Proof','trim|required|xss_clean');
					$this->form_validation->set_rules('idNo','ID No','trim|required|max_length[25]|alpha_numeric_spaces|xss_clean');
					$this->form_validation->set_rules('idproofphoto','Id proof','file_required|file_allowed_type[jpg,jpeg]|file_size_max[300]|callback_idproofphoto_upload');
				}
								
				## Aadhar validation condition added by Pooja mane on 24-02-2023 ##
				$old_adhar = $this->master_model->getRecords('member_registration',array('email'=>$this->input->post('email'),$this->input->post('aadhar_card'),$this->input->post('emp_id'),'mobile'=>$this->input->post('mobile')),'regid');

				if($this->input->post('aadhar_card') != '' )
				{
					if($old_adhar)
					{
					    $this->form_validation->set_rules('aadhar_card','Aadhar Card Number','trim|max_length[12]|min_length[12]|numeric|xss_clean');
					}
					if($old_adhar)
					{
					    $this->form_validation->set_rules('aadhar_card','Aadhar Card Number','trim|max_length[12]|min_length[12]|numeric|xss_clean|callback_check_aadhar');
					}
				}
				
				$this->form_validation->set_rules('medium','Medium','required|xss_clean');
				$this->form_validation->set_rules('selCenterName','Centre Name','required|xss_clean');
				$this->form_validation->set_rules('txtCenterCode','Centre Code','required|xss_clean');
				// $this->form_validation->set_rules('code', 'Security Code ', 'trim|required|xss_clean|callback_check_captcha_userreg');
				$this->form_validation->set_rules('declaration1', 'I Accept', 'trim|required|xss_clean');
				
				
				if($this->form_validation->run()==TRUE)
				{
					
					$scannedphoto_file=$scannedsignaturephoto_file=$idproof_file=$outputphoto1=$outputsign1=$outputidproof1=$state='';
					$outputphoto1=$outputsign1=$outputsign1='';
					$scannedphoto_file = '';
					$scannedsignaturephoto_file = '';
					$idproof_file = '';
					$enduserinfo = $this->session->userdata('enduserinfo');
					if(count($enduserinfo))
					{
						$this->session->unset_userdata('enduserinfo');
					}
					$non_memberdata_session = $this->session->userdata('non_memberdata');
					if(count($non_memberdata_session))
					{
						$this->session->unset_userdata('non_memberdata');
					}
					$subject_arr=array();
					$venue=$this->input->post('venue'); //print_r($venue); exit;
					$date=$this->input->post('date');
					$time=$this->input->post('time');
					

					//priyanka d - 15-may-23 >> added this for weekly off csc dates task
					foreach($venue as $v) {

						$disabledDates= getcentrenonavailability($v);
						//print_r($disabledDates);
						//print_r($this->input->post('date'));
						foreach($this->input->post('date') as $currDate) {
							//$currDate=strtotime('Y-m-d',$currDate);
							$disableIt=0;
							if(in_array($currDate,$disabledDates['disabledDates'])) {
								$disableIt=1;
							}
							
							if($disabledDates['weeklyOff']!='' && date('l', strtotime($currDate))==$disabledDates['weeklyOff']) {
								$disableIt=1;
							}
							if($disableIt==1)  {
							//	echo $currDate;
								$this->session->set_flashdata('error','Wrong Venue/date has been selected');
								redirect(base_url().'Ippbapplyexam/candidate_details/'.base64_encode($emp_id));
								exit;
							}
						}
					}

					//print_r($this->input->post('venue'));
					########### get POST data of subject ##############
					if(count($venue) >0 && count($date) && count($time) >0)	
					{
						foreach($venue as $k=>$v)
						{
							$this->db->join('exam_activation_master','exam_activation_master.exam_code=subject_master.exam_code AND exam_activation_master.exam_period=subject_master.exam_period');
							$compulsory_subjects_name=$this->master_model->getRecords('subject_master',array('subject_master.exam_code'=>$ExId,'subject_delete'=>'0','group_code'=>'C','subject_code'=>$k),'subject_description');
							$subject_arr[$k]=array('venue'=>$v,'date'=>$date[$k],'session_time'=>$time[$k],'subject_name'=>$compulsory_subjects_name[0]['subject_description']);
						}
					
						#########check duplication of venue,date,time##########		
						if(count($subject_arr) > 0)
						{	
							$msg='';
							$sub_flag=1;
							$sub_capacity=1;
							foreach($subject_arr as $k=>$v)
							{
									foreach($subject_arr as $j=>$val)
									{
										if($k!=$j)
										{
											//if($v['venue']==$val['venue'] && $v['date']==$val['date'] && $v['session_time']==$val['session_time'])
											if($v['date']==$val['date'] && $v['session_time']==$val['session_time'])
											{
												$sub_flag=0;
											}
										}
									}
								 $capacity=check_capacity($v['venue'],$v['date'],$v['session_time'],$_POST['selCenterName']);
								if($capacity==0)
								{
									#########get message if capacity is full##########
									$msg=getVenueDetails($v['venue'],$v['date'],$v['session_time'],$_POST['selCenterName']);
								}
								if($msg!='')
								{
									$this->session->set_flashdata('error',$msg);
									redirect(base_url().'Ippbapplyexam/candidate_details/'.base64_encode($emp_id));
                                    
								}
							}
						}
						if($sub_flag==0)
						{
							$this->session->set_flashdata('error','Date and Time for Venue can not be same!');
							redirect(base_url().'Ippbapplyexam/candidate_details/'.base64_encode($emp_id));
						}
					}
							
					$eduqual1=$eduqual2=$eduqual3='';
					if($_POST['optedu']=='U')
					{
						$eduqual1=$_POST["eduqual1"];
					}
					else if($_POST['optedu']=='G')
					{
						$eduqual2=$_POST["eduqual2"];
					}
					else if($_POST['optedu']=='P')
					{
						$eduqual3=$_POST["eduqual3"];
					}
					
					$date=date('Y-m-d h:i:s');
					//Generate dynamic photo
					if(! isset($aCandidate['regnumber'])){
						$input = $_POST["hiddenphoto"];
						if(isset($_FILES['scannedphoto']['name']) &&($_FILES['scannedphoto']['name']!=''))
						{
							$img = "scannedphoto";
							$tmp_nm = strtotime($date).rand(0,100);
							$new_filename = 'non_mem_photo_'.$tmp_nm;
							$config=array('upload_path'=>'./uploads/photograph',
											'allowed_types'=>'jpg|jpeg',
											'file_name'=>$new_filename,);
									
							$this->upload->initialize($config);
							$size = @getimagesize($_FILES['scannedphoto']['tmp_name']);
							if($size)
							{
								if($this->upload->do_upload($img))
								{
									$dt=$this->upload->data();
									$file=$dt['file_name'];
									$scannedphoto_file = $dt['file_name'];
									$outputphoto1 = base_url()."uploads/photograph/".$scannedphoto_file;
								}else{
									$this->session->set_flashdata('error','Scanned Photograph :'.$this->upload->display_errors());
								}
							}
							else
							{
								$this->session->set_flashdata('error','The filetype you are attempting to upload is not allowed');
							}
						}
						// generate dynamic scan signature
						$inputsignature = $_POST["hiddenscansignature"];
						if(isset($_FILES['scannedsignaturephoto']['name']) &&($_FILES['scannedsignaturephoto']['name']!=''))
						{
							$img = "scannedsignaturephoto";
							$tmp_signnm = strtotime($date).rand(0,100);
							$sign_new_filename = 'non_mem_sign_'.$tmp_signnm;
							$config=array('upload_path'=>'./uploads/scansignature',
												'allowed_types'=>'jpg|jpeg',
												'file_name'=>$sign_new_filename,);
									
							$this->upload->initialize($config);
							$size = @getimagesize($_FILES['scannedsignaturephoto']['tmp_name']);
							if($size)
							{
								if($this->upload->do_upload($img))
								{
									$dt=$this->upload->data();
									$scannedsignaturephoto_file=$dt['file_name'];
									$outputsign1 = base_url()."uploads/scansignature/".$scannedsignaturephoto_file;
								}
								else
								{
										$this->session->set_flashdata('error','Scanned Signature :'.$this->upload->display_errors());
								}
							}
							else
							{
									$this->session->set_flashdata('error','The filetype you are attempting to upload is not allowed');
							}
						}
						// generate dynamic id proof
						$inputidproofphoto = $_POST["hiddenidproofphoto"];
						if(isset($_FILES['idproofphoto']['name']) &&($_FILES['idproofphoto']['name']!=''))
						{
								$img = "idproofphoto";
								$tmp_inputidproof = strtotime($date).rand(0,100);
								$new_filename = 'non_mem_idproof_'.$tmp_inputidproof;
								$config=array('upload_path'=>'./uploads/idproof',
										'allowed_types'=>'jpg|jpeg',
										'file_name'=>$new_filename,);
										
								$this->upload->initialize($config);
								$size = @getimagesize($_FILES['idproofphoto']['tmp_name']);
								if($size)
								{
									if($this->upload->do_upload($img))
									{
										$dt=$this->upload->data();
										$idproof_file=$dt['file_name'];
										$outputidproof1 = base_url()."uploads/idproof/".$idproof_file;
									}
									else
									{
											$this->session->set_flashdata('error','Id proof :'.$this->upload->display_errors());
									}
								}
								else
								{
										$this->session->set_flashdata('error','The filetype you are attempting to upload is not allowed');
								}
						}
					}else{
						$scannedphoto_file = $aCandidate['scannedphoto'];
						$outputphoto1 = base_url()."uploads/photograph/".$scannedphoto_file;
						$scannedsignaturephoto_file=$aCandidate['scannedsignaturephoto'];
						$outputsign1 = base_url()."uploads/scansignature/".$scannedsignaturephoto_file;
						$idproof_file=$aCandidate['idproofphoto'];
						$outputidproof1 = base_url()."uploads/idproof/".$idproof_file; 
					}
					$dob1= $_POST["dob1"];
					$dob = str_replace('/','-',$dob1);
					$dateOfBirth = date('Y-m-d',strtotime($dob));
					
					if($scannedphoto_file!='' && $idproof_file!='' && $scannedsignaturephoto_file!='')
					{
						$user_data=array(	'firstname'				=>$_POST["firstname"],
                                            'sel_namesub'			=>$_POST["sel_namesub"],
                                            'addressline1'			=>$_POST["addressline1"],
                                            'addressline2'			=>$_POST["addressline2"],
                                            'addressline3'			=>$_POST["addressline3"],
                                            'addressline4'			=>$_POST["addressline4"],
                                            'city'					=>$_POST["city"],	
                                            'code'					=>trim($_POST["code"]),
                                            'district'				=>$_POST["district"],	
                                            'dob'					=>$dateOfBirth,
                                            'eduqual'				=>$_POST["eduqual"],	
                                            'eduqual1'				=>$eduqual1,	
                                            'eduqual2'				=>$eduqual2,	
                                            'eduqual3'				=>$eduqual3,	
                                            'email'					=>$_POST["email"],	
                                            'gender'				=>$_POST["gender"],	
                                            'idNo'					=>$_POST["idNo"],	
                                            'idproof'				=>$_POST["idproof"],	
                                            'lastname'				=>$_POST["lastname"],	
                                            'middlename'			=>$_POST["middlename"],	
                                            'mobile'				=>$_POST["mobile"],	
                                            'optedu'				=>$_POST["optedu"],	
                                            'optnletter'			=>$_POST["optnletter"],	
                                            'phone'					=>$_POST["phone"],	
                                            'pincode'				=>$_POST["pincode"],	
                                            'state'					=>$_POST["state"],	
                                            'stdcode'				=>$_POST["stdcode"],
                                            'scannedphoto'			=>$outputphoto1,
                                            'scannedsignaturephoto'	=>$outputsign1,
                                            'idproofphoto'			=>$outputidproof1,
                                            'photoname'				=>$scannedphoto_file,
                                            'signname'				=>$scannedsignaturephoto_file,
                                            'idname'				=>$idproof_file,
                                            'selCenterName'			=>$_POST["selCenterName"],
                                            'txtCenterCode'			=>$_POST["txtCenterCode"],
                                            'optmode'				=>$_POST["optmode"],
                                            'exid'					=>$_POST["exid"],
                                            'mtype'					=>$_POST["mtype"],
                                            'memtype'				=>$_POST["memtype"],
                                            'eprid'					=>$_POST["eprid"],
                                            'exam_month'   			=>$_POST["exmonth"],
                                            'rrsub'					=>$_POST["rrsub"],
                                            'excd'					=>base64_decode($_POST["excd"]),
                                            'exname'				=>$_POST["exname"],
                                            'fee'					=>$_POST["fee"],
                                            'medium'				=>$_POST['medium'],
                                            'aadhar_card'			=>$_POST['aadhar_card'],
                                            'grp_code'				=>$_POST['grp_code'],
                                            'subject_arr'			=>$subject_arr,
                                            'gstin_no'				=>0,
                                            'scribe_flag'			=>$scribe_flag,
                                            'emp_id'				=>$emp_id,
                                            'branch'				=>$_POST['branch'],
                                            'circle'				=>$_POST['circle'],
											'regnumber'				=> $aCandidate['regnumber']
                                        );

						$this->session->set_userdata('enduserinfo',$user_data);

						$log_title ="IPPB session enduserinfo:".$emp_id;
						$log_message = serialize($user_data);
						$rId = $emp_id;
						$regNo = $emp_id;
						storedUserActivity($log_title, $log_message, $rId, $regNo);

						redirect(base_url().'Ippbapplyexam/preview');
					}
					else
					{
						redirect(base_url().'Ippbapplyexam/candidate_details/'.base64_encode($emp_id));
					}
				}
				
		}
        

		// --------------------------------------------------------------
		

        if (count($aCandidate) > 0) {
        	$undergraduate = $this->master_model->getRecords('qualification', array('type' => 'UG'));
            $graduate = $this->master_model->getRecords('qualification', array('type' => 'GR'));
            $postgraduate = $this->master_model->getRecords('qualification', array('type' => 'PG'));
            $states = $this->master_model->getRecords('state_master');

            $this->db->not_like('name', 'Declaration Form');
            $this->db->not_like('name', 'college');
            $this->db->not_like('name', 'Aadhaar id');
            $this->db->not_like('name', 'Election Voters card');
            $idtype_master = $this->master_model->getRecords('idtype_master');
        	$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=medium_master.exam_code AND exam_activation_master.exam_period=medium_master.exam_period');
            $this->db->where('medium_master.exam_code', $ExId);
            $this->db->where('medium_delete', '0');
            $medium = $this->master_model->getRecords('medium_master');


            //Pratibha check eligible data here (if user have member reg number)
			if( isset($aCandidate['regnumber'])){
				 
				//END Of ask user to wait for 5 min, until the payment transaction process complete
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=exam_master.exam_code');
				$this->db->join("eligible_master",'eligible_master.exam_code=exam_activation_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period','left');
				$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
				$this->db->where("misc_master.misc_delete",'0');
				$this->db->where("eligible_master.member_no",$aCandidate['regnumber']);
				$this->db->where("eligible_master.app_category !=",'R');
				$this->db->where('exam_master.exam_code',$examcode);
				$examinfo=$this->master_model->getRecords('exam_master');
				
				####### get subject mention in eligible master ##########
				if(count($examinfo) > 0)
				{
					foreach($examinfo as $rowdata)
					{
							if($rowdata['exam_status']!='P')
							{
								$compulsory_subjects[]=$this->master_model->getRecords('subject_master',array('exam_code'=>$examcode,'subject_delete'=>'0','group_code'=>'C','exam_period'=>$rowdata['exam_period'],'subject_code'=>$rowdata['subject_code']));	
							}
						}	
						$compulsory_subjects = array_map('current', $compulsory_subjects);
						sort($compulsory_subjects );
				}	
				//echo $this->db->last_query();exit;
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
				$this->db->where('center_master.exam_name',$examcode);
				$this->db->where("center_delete",'0');
				$this->db->where("center_master.center_code !=",751);
				$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
				//Below code, if member is new member
				if(count($examinfo) <=0)
				{
					$this->db->select('exam_master.*,misc_master.*');
					$this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=misc_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period');//added on 5/6/2017
					$this->db->where("misc_master.misc_delete",'0');
					$this->db->where('exam_master.exam_code',$examcode);
		 			$examinfo = $this->master_model->getRecords('exam_master');
					//get center
					//get center as per exam
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
					$this->db->where("center_delete",'0');
					$this->db->where('exam_name',$examcode);
					$this->db->group_by('center_master.center_name');
					$center=$this->master_model->getRecords('center_master','','',array('center_name'=>'ASC'));
					####### get compulsory subject list##########
					$compulsory_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$examcode,'subject_delete'=>'0','group_code'=>'C','exam_period'=>$examinfo[0]['exam_period']),'',array('subject_code'=>'ASC'));
				}			
				
				$data=array('middle_content'=>'ippbapplyexam/ippb_exam_form','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'idtype_master'=>$idtype_master,'examinfo'=>$examinfo,'medium'=>$medium,'center'=>$center,'caiib_subjects'=>$caiib_subjects,'compulsory_subjects'=>$compulsory_subjects,'aCandidate' => $aCandidate);
				
			}else{
	            
	            $this->db->select('exam_master.*,misc_master.*');
	            $this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code');
	            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=misc_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period'); 
	            $this->db->where("misc_master.misc_delete", '0');
	            $this->db->where('exam_master.exam_code', $ExId);
	            $examinfo = $this->master_model->getRecords('exam_master');
	           
	            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
	            $this->db->where("center_delete", '0');
	            $this->db->where('exam_name', $ExId);
	            $this->db->group_by('center_master.center_name');
	            $center = $this->master_model->getRecords('center_master');

	            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=subject_master.exam_code AND exam_activation_master.exam_period=subject_master.exam_period');
	            $compulsory_subjects = $this->master_model->getRecords('subject_master', array('subject_master.exam_code' => $ExId, 'subject_delete' => '0', 'group_code' => 'C'), '', array('subject_code' => 'ASC'));

	            $data = array(
	                'middle_content' => 'ippbapplyexam/ippb_exam_form', 'states' => $states, 'undergraduate' => $undergraduate, 'graduate' => $graduate, 'postgraduate' => $postgraduate,'examinfo' => $examinfo, 'medium' => $medium, 'center' => $center, 'idtype_master' => $idtype_master, 'compulsory_subjects' => $compulsory_subjects, 'aCandidate' => $aCandidate
	            );
	        }
        }

        //    echo "abc----------------><pre>"; print_r($data); exit;
        // _pa($data);
        $this->load->view('ippbapplyexam/common_view_fullwidth', $data);
    }


    //Preview of register form 
	public function preview()
    {
		//echo "IPPB Service is not available due to maintenance activity from 5 PM to 07 PM."; exit;
     
         if(!$this->session->userdata('enduserinfo'))
         {
            redirect(base_url());
         }
					 	$log_title ="IPPB session enduserinfo on preview page:".$this->session->userdata['enduserinfo']['emp_id'];
						$log_message = serialize($this->session->userdata('enduserinfo'));
						$rId = $this->session->userdata['enduserinfo']['emp_id'];
						$regNo = $this->session->userdata['enduserinfo']['emp_id'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);
         ////check temp file uploaded or not////
		//  print_r($this->session->userdata); exit;
         $images_flag=0;
         if(!file_exists("uploads/photograph/".$this->session->userdata['enduserinfo']['photoname']))
         {
             $images_flag=1;
         }
         if(!file_exists("uploads/scansignature/".$this->session->userdata['enduserinfo']['signname']))
         {
             $images_flag=1;
         }
         if(!file_exists("uploads/idproof/".$this->session->userdata['enduserinfo']['idname']))
         {
             $images_flag=1;
         }
         if($images_flag)
         {
            $this->session->set_flashdata('error','Please upload valid image(s)');
			//redirect(base_url().'Ippbapplyexam/candidate_details/'.base64_encode($this->session->userdata['enduserinfo']['emp_id']));
         }
         $sub_flag=1;
         ############check capacity is full or not ##########
         $subject_arr=$this->session->userdata['enduserinfo']['subject_arr'];
         if(count($subject_arr) > 0)
         {		
             $msg='';
         
             $sub_capacity=1;
             foreach($subject_arr as $k=>$v)
             {
				foreach($subject_arr as $j=>$val)
				{
					if($k!=$j)
					{
						//if($v['venue']==$val['venue'] && $v['date']==$val['date'] && $v['session_time']==$val['session_time'])
						if($v['date']==$val['date'] && $v['session_time']==$val['session_time'])
						{
							$sub_flag=0;
						}
					}
				}
                $capacity=check_capacity($v['venue'],$v['date'],$v['session_time'],$this->session->userdata['enduserinfo']['selCenterName']);
				if($capacity==0)
				{
					#########get message if capacity is full##########
					$msg=getVenueDetails($v['venue'],$v['date'],$v['session_time'],$this->session->userdata['enduserinfo']['selCenterName']);
				}
				if($msg!='')
				{
					$this->session->set_flashdata('error',$msg);
					redirect(base_url().'Ippbapplyexam/candidate_details/'.base64_encode($this->session->userdata['enduserinfo']['emp_id']));
				}
             }
         }
         if($sub_flag==0)
         {
            $this->session->set_flashdata('error','Date and Time for Venue can not be same!');
			redirect(base_url().'Ippbapplyexam/candidate_details/'.base64_encode($this->session->userdata['enduserinfo']['emp_id']));
         }
         
         //check email,mobile duplication on the same time from different browser!!
         $endTime = date("H:i:s");
         $start_time= date("H:i:s",strtotime("-120 minutes",strtotime($endTime)));
         $this->db->where('Time(createdon) BETWEEN "'. $start_time. '" and "'. $endTime.'"');
        //  $this->db->where('email',$this->session->userdata['enduserinfo']['email']);
         $this->db->where('mobile',$this->session->userdata['enduserinfo']['mobile']);
         $check_duplication=$this->master_model->getRecords('member_registration',array('isactive'=>0));
        //  echo $this->db->last_query();exit;
         
        if(count($check_duplication) > 0)
        {
            redirect(base_url().'Ippbapplyexam/accessdenied/');
        }
         //check exam activation
         $check_exam_activation=check_exam_activate($this->session->userdata['enduserinfo']['excd']);
        //  echo "prt==>"; print_r($check_exam_activation); exit;

         if($check_exam_activation==0)
         {                
            redirect(base_url().'Ippbapplyexam/accessdenied/');
         }
         //check for valid fee
         if($this->session->userdata['enduserinfo']['fee']==0 || $this->session->userdata['enduserinfo']['fee']=='')
         {
            //$this->session->set_flashdata('error','Fee can not be zero(0) or Blank!!');
            redirect('http://iibf.org.in/');
         }
         //------------------------------------------------------------------
		 	$check = $this->examapplied($this->session->userdata['enduserinfo']['regnumber'], $this->session->userdata['enduserinfo']['excd']);
			// print_r($this->session->userdata['non_memberdata']); exit;
			if ($check) {
		
				$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
				$get_period_info = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => $this->session->userdata['enduserinfo']['excd'], 'misc_master.misc_delete' => '0'), 'exam_month');
				$month            = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);
				$exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);
            	// echo "<pre>".$this->db->last_query(); exit;

				redirect(base_url().'Ippbapplyexam/already_apply/'.$exam_period_date);
				
			}
		 //---------------------------------------------------------

		 
         $undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
         $graduate=$this->master_model->getRecords('qualification',array('type'=>'GR'));
         $postgraduate=$this->master_model->getRecords('qualification',array('type'=>'PG'));
         $institution_master=$this->master_model->getRecords('institution_master');
         $states=$this->master_model->getRecords('state_master');
         $designation=$this->master_model->getRecords('designation_master');
         $idtype_master=$this->master_model->getRecords('idtype_master');
         
         
         $this->db->join('exam_activation_master','exam_activation_master.exam_code=medium_master.exam_code AND exam_activation_master.exam_period=medium_master.exam_period');
         $this->db->where('medium_delete','0');
         $medium=$this->master_model->getRecords('medium_master',array('medium_master.exam_code'=>$this->session->userdata['enduserinfo']['excd']));
         
         
         
         $this->db->join('exam_activation_master','exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
         $center=$this->master_model->getRecords('center_master',array('exam_name'=>$this->session->userdata['enduserinfo']['excd']));
         
         $this->db->join('exam_activation_master','exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
         $this->db->like('misc_master.exam_code',$this->session->userdata['enduserinfo']['excd']);
         $exam_period=$this->master_model->getRecords('misc_master','','misc_master.exam_period'); 
         //echo $this->db->last_query();exit;
         
         $data=array('middle_content'=>'ippbapplyexam/ippb_mem_preview_register','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'institution_master'=>$institution_master,'designation'=>$designation,'medium'=>$medium,'center'=>$center,'exam_period'=>$exam_period,'idtype_master'=>$idtype_master,'compulsory_subjects'=>$this->session->userdata['enduserinfo']['subject_arr'],'emp_id'=>$this->session->userdata['enduserinfo']['emp_id']);
         $this->load->view('ippbapplyexam/common_view_fullwidth',$data);
    }

    public function accessdenied()
	{
        $message='<div style="color:#F00">You are not eligible to register for the selected examination. For details refer the link <strong>"Important Notice of Diploma and Certificate Examination syllabus" </strong> at the institutes website</div>';
        $data=array('middle_content'=>'ippbapplyexam/access-denied-registration','check_eligibility'=>$message);
        $this->load->view('ippbapplyexam/common_view_fullwidth',$data);
	}

	public function send_otp()
	{
		if (isset($_POST['emp_id']) && !empty($_POST['emp_id'])) {
			$user_otp_send_on = date('Y-m-d H:i:s');
			$user_otp_expired_on = date('Y-m-d H:i:s', strtotime('+30 minutes', strtotime($user_otp_send_on)));
			$otp = $this->generate_otp();
			
			$stud_arr = array(
				'emp_id' => $_POST['emp_id'],
				'firstname' => $_POST['firstname'],
				'email' => $_POST['email'],
				'mobile' => $_POST['mobile']
			);

			// with this function send otp to user via sms
			$this->send_otp_via_api($otp, $stud_arr);

			$this->db->delete('verify_otp_ippb', array('emp_id' => $_POST['emp_id']));
			$data = array(
				'regnumber' => '',
				'emp_id' => $_POST['emp_id'],
				'email' => $_POST['email'],
				'mobile' => $_POST['mobile'],
				'user_otp' => $otp,
				'otp_remove' => 'n',
				'user_otp_send_on' => $user_otp_send_on,
				'user_otp_expired_on' => $user_otp_expired_on,
				'is_otp_verified' => 'n',
			);
			$this->db->insert('verify_otp_ippb', $data);
			$inserted_id = $this->db->insert_id();

			echo json_encode(array(
				'status' => 'success',
				'msg' => 'OTP Send to user',
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

			$stud_arr = array(
				'emp_id' => $_POST['emp_id'],
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
			$this->db->update('verify_otp_ippb', $data, $where);
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
			$otp_data = $this->db->query("Select * from verify_otp_ippb where otp_id=" . $otp_id)->row();
			// echo 'expired time: '.$otp_data->user_otp_expired_on.' === '.strtotime($otp_data->user_otp_expired_on);
			// echo '<br>now time: '.date('Y-m-d H:i:s').' === '.strtotime(date('Y-m-d H:i:s')).'<br>';
			
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
						$this->db->update('verify_otp_ippb', $data, $where);

						echo json_encode(array(
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
						$this->db->update('verify_otp_ippb', $data, $where);

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
		$message = 'Your OTP for BCBF exam Application is ' . $otp . '. This password is valid for one transaction or 30 mins whichever is earlier. Do not share it with anyone.IIBF Team';
		$res = $this->master_model->send_sms_trustsignal(intval($mobile), $message, '3M1ie3r7g', '997', 'transactional', 'IIBFSM');
		// $res = $this->master_model->send_sms_trustsignal(intval($mobile), $message, '3M1ie3r7g', '997', 'otp', 'IIBFSM');
		// SEND OTP ON MOBILE (SMS) : END

		// SEND OTP ON EMAIL : START
		$ippb_otp_mail_arr = array(
			'to' => $stud_arr['email'],
			'subject' => 'Login OTP of Indian Post Payment Bank',
			'message' => '
					Dear ' . $stud_arr['firstname'] . ' (Employee ID: ' . $stud_arr['emp_id'] . '), <br/><br/>
					Your OTP for IPPB exam Application is <strong>' . $otp . '</strong> (Valid for 30 minutes). <br/>
					<div style="margin-top:10px;">Note : Do not share it with anyone.</div><br>
					Thanks & Regards,<br>IIBF TEAM ',
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

		//echo "IPPB Service is not available due to maintenance activity from 5 PM to 07 PM."; exit;

        $this->load->helper('update_image_name_helper');
        
        $flag=1;
        // $Mtype = base64_decode($this->input->get('Mtype'));
        // $ExId = base64_decode($this->input->get('ExId'));
        $scannedphoto_file=$scannedsignaturephoto_file = 	$idproofphoto_file = $password='';
        $data['validation_errors'] = '';
        //check email,mobile duplication on the same time from different browser!!
        $check_duplication = $this->master_model->getRecords('member_registration', array('email' => $this->session->userdata['enduserinfo']['email'],'mobile' => $this->session->userdata['enduserinfo']['mobile'],'isactive' => '1','registrationtype' => 'NM','regnumber !=' => '' ));
        
        // echo $this->db->last_query();exit;
        //echo $this->session->userdata('uniqueString');exit;
        //vdebug($_POST);exit;
        if(isset($_POST['btnSubmit']))  	
        // echo $this->db->last_query();exit;
        {

            $image_error_flag = 0;
            
            $scannedphoto_file = $this->session->userdata['enduserinfo']['photoname'];
            $img_response = check_files_exist('./uploads/photograph/'.$scannedphoto_file); //update_image_name_helper.php
            if($img_response['flag'] != 'success') { $image_error_flag = 1; }
            
            $scannedsignaturephoto_file = $this->session->userdata['enduserinfo']['signname'];
            $img_response = check_files_exist('./uploads/scansignature/'.$scannedsignaturephoto_file); //update_image_name_helper.php
            if($img_response['flag'] != 'success') { $image_error_flag = 1; }
            
            $idproofphoto_file = $this->session->userdata['enduserinfo']['idname'];
            $img_response = check_files_exist('./uploads/idproof/'.$idproofphoto_file); //update_image_name_helper.php
            if($img_response['flag'] != 'success') { $image_error_flag = 1; }
            
            if($image_error_flag == 1)
            {
                $this->session->set_flashdata('error','Please upload valid image(s)');
                redirect(base_url().'Ippbapplyexam/member/'.$this->session->userdata['enduserinfo']['emp_id']);
            }			
            
            $sel_namesub = strtoupper($this->session->userdata['enduserinfo']['sel_namesub']);
            $firstname = strtoupper($this->session->userdata['enduserinfo']['firstname']);
            $middlename = strtoupper($this->session->userdata['enduserinfo']['middlename']);
            $lastname = strtoupper($this->session->userdata['enduserinfo']['lastname']);
            $addressline1= strtoupper($this->session->userdata['enduserinfo']['addressline1']);
            $addressline2 = strtoupper($this->session->userdata['enduserinfo']['addressline2']);
            $addressline3 = strtoupper($this->session->userdata['enduserinfo']['addressline3']);
            $addressline4 = strtoupper($this->session->userdata['enduserinfo']['addressline4']);
            $district= strtoupper($this->session->userdata['enduserinfo']['district']);
            $nationality = strtoupper($this->session->userdata['enduserinfo']['city']);
            $state = $this->session->userdata['enduserinfo']['state'];
            $pincode= $this->session->userdata['enduserinfo']['pincode'];
            $dob= $this->session->userdata['enduserinfo']['dob'];
            $gender = $this->session->userdata['enduserinfo']['gender'];
            $optedu= $this->session->userdata['enduserinfo']['optedu'];
            if($optedu=='U')
            {
                $specify_qualification=$this->session->userdata['enduserinfo']['eduqual1'];
            }
            elseif($optedu=='G')
            {
                $specify_qualification=$this->session->userdata['enduserinfo']['eduqual2'];
            }
            else if($optedu=='P')
            {
                $specify_qualification=$this->session->userdata['enduserinfo']['eduqual3'];
            }
            
            $idproof = $this->session->userdata['enduserinfo']['idproof'];
            $idNo = $this->session->userdata['enduserinfo']['idNo'];
            $aadhar_card = $this->session->userdata['enduserinfo']['aadhar_card'];
            $optnletter = $this->session->userdata['enduserinfo']['optnletter'];
            $centerid=$this->session->userdata['enduserinfo']['selCenterName'];
            $centercode=$this->session->userdata['enduserinfo']['txtCenterCode'];
            $exmode=$this->session->userdata['enduserinfo']['optmode'];
            $stdcode =$this->session->userdata['enduserinfo']['stdcode'];
            $email = $this->session->userdata['enduserinfo']['email'];
            $phone = $this->session->userdata['enduserinfo']['phone'];
            $mobile = $this->session->userdata['enduserinfo']['mobile'];
            $images_flag=0;
            if(!file_exists("uploads/photograph/".$this->session->userdata['enduserinfo']['photoname']))
            {
                $images_flag=1;
            }
            if(!file_exists("uploads/scansignature/".$this->session->userdata['enduserinfo']['signname']))
            {
                $images_flag=1;
            }
            if(!file_exists("uploads/idproof/".$this->session->userdata['enduserinfo']['idname']))
            {
                $images_flag=1;
            }
            if($images_flag)
            {
                $this->session->set_flashdata('error','Please upload valid image(s)');
                redirect(base_url().'Ippbapplyexam/member/'.$this->session->userdata['enduserinfo']['emp_id']);
            }
            

			// $userarr=array('regno'=>'','password'=>'','email'=>'');
            // $this->session->set_userdata('non_memberdata', $userarr);

            if(count($check_duplication) > 0)
            {
                // echo " <pre>Do code for update existing memeber"; print_r($check_duplication); exit;
                $update_mem_data = array(
                    'namesub' => $sel_namesub,
                    'firstname'=>$firstname,
                    'middlename'=>$middlename,
                    'lastname'=>$lastname,
                    'address1'=>$addressline1,
                    'address2'=>$addressline2,
                    'address3'=>$addressline3,
                    'address4'=>$addressline4,
                    'district'=>$district,
                    'city'=>$nationality,
                    'state'=>$state,
                    'pincode'=>$pincode,
                    'dateofbirth'=>$dob,
                    'gender'=>$gender,
                    'qualification'=>$optedu,
                    'specify_qualification'=>$specify_qualification,
                    'stdcode'=>$stdcode,
                    'idproof'=>$idproof,
                    'idNo'=>$idNo,
                    'optnletter'=>'N',
                    'declaration'=>'1',
                    'excode'=>$this->session->userdata['enduserinfo']['excd'],
                    'fee'=>$this->session->userdata['enduserinfo']['fee'],
                    'exam_period'=>$this->session->userdata['enduserinfo']['eprid'],
                    'centerid'=>$centerid,
                    'centercode'=>$centercode,
                    'exmode'=>$exmode,
                    'aadhar_card'=>$aadhar_card,
                    'editedon'=>date('Y-m-d H:i:s')
                );
                
                $regnumber=$check_duplication[0]['regnumber'];
                if($this->master_model->updateRecord('member_registration',$update_mem_data,array('regnumber'=>$regnumber)))
                {                    
                    //logactivity($log_title ="Non-Member user registration ", $log_message = serialize($insert_info));
                    $log_title ="IPPB nonreg  UPDATE Array :".$update_mem_data;
                    $log_message = serialize($update_mem_data);
                    $rId = $regnumber;
                    $regNo = $regnumber;
                    storedUserActivity($log_title, $log_message, $rId, $regNo);	
                                
                    $amount=getExamFee($this->session->userdata['enduserinfo']['txtCenterCode'],$this->session->userdata['enduserinfo']['eprid'],base64_encode($this->session->userdata['enduserinfo']['excd']),$this->session->userdata['enduserinfo']['grp_code'],'NM','N');
                    
                    $inser_exam_array=array(	'regnumber'=>$regnumber,
                                                'exam_code'=>$this->session->userdata['enduserinfo']['excd'],
                                                'exam_mode'=>$this->session->userdata['enduserinfo']['optmode'],
                                                'exam_medium'=>$this->session->userdata['enduserinfo']['medium'],
                                                'exam_period'=>$this->session->userdata['enduserinfo']['eprid'],
                                                'exam_center_code'=>$this->session->userdata['enduserinfo']['txtCenterCode'],
                                                'exam_fee'=>$amount,
                                                'scribe_flag'=>$this->session->userdata['enduserinfo']['scribe_flag'],
                                                'created_on'=>date('y-m-d H:i:s')
                                            );
                                        
                                            
                        if($exam_last_id=$this->master_model->insertRecord('member_exam',$inser_exam_array,true))
                        {

                            // Renaming the previously uploaded file with Reg Num inserted in database
                            logactivity($log_title ="Exam applied During Non-Member user registration ", $log_message = serialize($inser_exam_array));
                            $exam_name_desc=$this->master_model->getRecords('exam_master',array('	exam_code'=>$this->session->userdata['enduserinfo']['excd'],'exam_delete'=>'0'),'description');
                            // echo $this->db->last_query(); print_r($exam_last_id); exit;
                            $userarr=array(
                                            'regno'=>$check_duplication[0]['regnumber'],
                                            'email'=>$email,
                                            'exam_fee'=>$this->session->userdata['enduserinfo']['fee'],
                                            'exam_desc'=>$exam_name_desc[0]['description'],
                                            'excode'=>$this->session->userdata['enduserinfo']['excd'],
                                            'memtype'=>$this->session->userdata['enduserinfo']['mtype'],
                                            'member_exam_id'=>$exam_last_id);
                                $this->session->set_userdata('non_memberdata', $userarr);
                            // echo $this->db->last_query(); print_r($this->config->item('exam_apply_gateway')); exit;
                                
                                if($this->config->item('exam_apply_gateway')=='sbi')
                                {
                                    redirect(base_url().'Ippbapplyexam/sbi_make_payment/');
                                }
                        }
                        else
                        {
                            $userarr=array('application_number'=>'','password'=>'','email'=>'');
                            $this->session->set_userdata('non_memberdata', $userarr); 
                            return false;
                        }
                        //Renaming the previously uploaded file with Reg Num inserted in database
                }else{
                        $this->session->set_flashdata('error','Error while during registration.please try again!');
                        redirect(base_url());
                }


            }else{
                $password=$this->generate_random_password();
                include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                $key = $this->config->item('pass_key');
                $aes = new CryptAES();
                $aes->set_key(base64_decode($key));
                $aes->require_pkcs5();
                $encPass = $aes->encrypt($password);
				// echo "error".$encPass;
                
                $insert_info = array(
                    'usrpassword'=>$encPass,
                    'namesub' => $sel_namesub,
                    'firstname'=>$firstname,
                    'middlename'=>$middlename,
                    'lastname'=>$lastname,
                    'address1'=>$addressline1,
                    'address2'=>$addressline2,
                    'address3'=>$addressline3,
                    'address4'=>$addressline4,
                    'district'=>$district,
                    'city'=>$nationality,
                    'state'=>$state,
                    'pincode'=>$pincode,
                    'dateofbirth'=>$dob,
                    'gender'=>$gender,
                    'qualification'=>$optedu,
                    'specify_qualification'=>$specify_qualification,
                    'email'=>$email,
                    'registrationtype'=>'NM',
                    'stdcode'=>$stdcode,
                    'office_phone'=>$phone,
                    'mobile'=>$mobile,
                    'scannedphoto'=>$scannedphoto_file,
                    'scannedsignaturephoto'=>$scannedsignaturephoto_file,	
                    'idproof'=>$idproof,
                    'idNo'=>$idNo,
                    'optnletter'=>'N',
                    'declaration'=>'1',
                    'idproofphoto'=>$idproofphoto_file,
                    'excode'=>$this->session->userdata['enduserinfo']['excd'],
                    'fee'=>$this->session->userdata['enduserinfo']['fee'],
                    'exam_period'=>$this->session->userdata['enduserinfo']['eprid'],
                    'centerid'=>$centerid,
                    'centercode'=>$centercode,
                    'exmode'=>$exmode,
                    'aadhar_card'=>$aadhar_card,
                    'createdon'=>date('Y-m-d H:i:s')
                );
                // echo "Do code for insert new memeber"; exit;

                if($last_id =$this->master_model->insertRecord('member_registration',$insert_info,true))
                {
					/*Insert ippb empid, branch, circle in employee_data_ippb table to avoid data missplaced: pratibha borse*/
					$insert_emp_info = array(
						'regnumber'=>$last_id,
						'emp_id' => $this->session->userdata['enduserinfo']['emp_id'],
						'circle'=>$this->session->userdata['enduserinfo']['circle'],
						'branch'=>$this->session->userdata['enduserinfo']['branch'],
						'mobile'=>$mobile,
						'createdon'=>date('Y-m-d H:i:s')
					);

					$last_emp_id =$this->master_model->insertRecord('employee_data_ippb',$insert_emp_info,true);

					//add user log for employee_data_ippb table
					$log_title ="IPPB employee data insert query for emp id:".$last_emp_id;

					$last_update_query = $this->db->last_query();

					$log_message = serialize($last_update_query);

					
					storedUserActivity($log_title, $log_message, $log_data, $log_data);


					/*Code end, Insert ippb empid, branch, circle in employee_data_ippb table to avoid data missplaced: pratibha borse*/


                    $add_img_data['reg_id'] = $last_id;
                    $add_img_data['photo'] = convert_img_into_base64(base_url().'uploads/photograph/'.$scannedphoto_file);
                    $add_img_data['sign'] = convert_img_into_base64(base_url().'uploads/scansignature/'.$scannedsignaturephoto_file);
                    $add_img_data['idproof'] = convert_img_into_base64(base_url().'uploads/idproof/'.$idproofphoto_file);
                    $add_img_data['created_on'] = date('Y-m-d H:i:s');
                    // $this->master_model->insertRecord('tbl_member_image_base64',$add_img_data,true);
                    
                    //logactivity($log_title ="Non-Member user registration ", $log_message = serialize($insert_info));
                    $log_title ="IPPB nonreg  INSERT Array :".$last_id;
                    $log_message = serialize($insert_info);
                    $rId = $last_id;
                    $regNo = $last_id;
                    storedUserActivity($log_title, $log_message, $rId, $regNo);	
                                
                    $amount=getExamFee($this->session->userdata['enduserinfo']['txtCenterCode'],$this->session->userdata['enduserinfo']['eprid'],base64_encode($this->session->userdata['enduserinfo']['excd']),$this->session->userdata['enduserinfo']['grp_code'],'NM','N');
                    
                    $inser_exam_array=array(	'regnumber'=>$last_id,
                                                'exam_code'=>$this->session->userdata['enduserinfo']['excd'],
                                                'exam_mode'=>$this->session->userdata['enduserinfo']['optmode'],
                                                'exam_medium'=>$this->session->userdata['enduserinfo']['medium'],
                                                'exam_period'=>$this->session->userdata['enduserinfo']['eprid'],
                                                'exam_center_code'=>$this->session->userdata['enduserinfo']['txtCenterCode'],
                                                'exam_fee'=>$amount,
                                                'scribe_flag'=>$this->session->userdata['enduserinfo']['scribe_flag'],
                                                'created_on'=>date('y-m-d H:i:s')
                                            );
                                        
                                            
                        if($exam_last_id=$this->master_model->insertRecord('member_exam',$inser_exam_array,true))
                        {

                            // Renaming the previously uploaded file with Reg Num inserted in database
                            logactivity($log_title ="Exam applied During Non-Member user registration ", $log_message = serialize($inser_exam_array));
                            $exam_name_desc=$this->master_model->getRecords('exam_master',array('	exam_code'=>$this->session->userdata['enduserinfo']['excd'],'exam_delete'=>'0'),'description');
                            // echo $this->db->last_query(); print_r($exam_last_id); exit;
                            $userarr=array('regno'=>$last_id,
                                                        'password'=>$password,
                                                        'email'=>$email,
                                                        'exam_fee'=>$this->session->userdata['enduserinfo']['fee'],
                                                        'exam_desc'=>$exam_name_desc[0]['description'],
                                                        'excode'=>$this->session->userdata['enduserinfo']['excd'],
                                                        'memtype'=>$this->session->userdata['enduserinfo']['mtype'],
                                                        'member_exam_id'=>$exam_last_id);
                                $this->session->set_userdata('non_memberdata', $userarr);
                            // echo $this->db->last_query(); print_r($this->config->item('exam_apply_gateway')); exit;
                                
                                if($this->config->item('exam_apply_gateway')=='sbi')
                                {
                                    redirect(base_url().'Ippbapplyexam/sbi_make_payment/');
                                }
                        }
                        else
                        {
                            $userarr=array('application_number'=>'','password'=>'','email'=>'');
                            $this->session->set_userdata('non_memberdata', $userarr); 
                            return false;
                        }
                        //Renaming the previously uploaded file with Reg Num inserted in database

                }
                else{
                        $this->session->set_flashdata('error','Error while during registration.please try again!');
                        redirect(base_url());
                }
            }
            
        }
    }



    //Thank you message to end user
    public function acknowledge($MerchantOrderNo=NULL)
    {
        $password=$decpass='';
        $data=array();
        //Query to get Payment details	
        $payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($MerchantOrderNo)),'member_regnumber,transaction_no,date,amount,exam_code,status');
        
        if(count($payment_info) <= 0)
        {redirect(base_url());}
        
        $this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period','left');
        $this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
        $this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
        $this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
        $exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$payment_info[0]['member_regnumber']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
        
        //Query to get Medium	
        $this->db->where('exam_code',$payment_info[0]['exam_code']);
        $this->db->where('exam_period',$exam_info[0]['exam_period']);
        $this->db->where('medium_code',$exam_info[0]['exam_medium']);
        $this->db->where('medium_delete','0');
        $medium=$this->master_model->getRecords('medium_master','','medium_description');
        
        //Query to get user details
        $this->db->join('state_master','state_master.state_code=member_registration.state');
        //$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
        $result=$this->master_model->getRecords('member_registration',array('regnumber'=>$payment_info[0]['member_regnumber']),'firstname,middlename,lastname,regnumber,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,scannedphoto,scannedsignaturephoto,idproofphoto,regid,isactive,regnumber,registrationtype');	
        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
        if(count($result) >0)
        {
        if($result[0]['isactive']==1)
        {
            $key = $this->config->item('pass_key');
            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $decpass = $aes->decrypt(trim($result[0]['usrpassword']));
            
            $mysqltime=date("H:i:s");
            $user_data=array('nmregid'=>$result[0]['regid'],
                                        'nmregnumber'=>$result[0]['regnumber'],
                                        'nmfirstname'=>$result[0]['firstname'],
                                        'nmmiddlename'=>$result[0]['middlename'],
                                        'nmlastname'=>$result[0]['lastname'],
                                        'nmtimer'=>base64_encode($mysqltime),
                                        'memtype'=>$result[0]['registrationtype'],
                                        'nmpassword'=>base64_encode($decpass));
            $this->session->set_userdata($user_data);
            //$sess = $this->session->userdata('nmregid');
        }
        }
        $data=array('application_number'=>$payment_info[0]['member_regnumber'],
        'password'=>$decpass,'payment_info'=>$payment_info,'exam_info'=>$exam_info,'medium'=>$medium,'result'=>$result);
        $this->load->view('ippbapplyexam/profile_thankyou',$data);
    }
  
    //Generate PDF
    public function pdf()
    {
            $user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata['non_memberdata']['regno']),'regnumber,usrpassword');
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('pass_key');
            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
            $html='<div class="content-wrapper">
            <section class="content-header">
        <center>    <h3>
            INDIAN INSTITUTE OF BANKING & FINANCE
            </h3>
            <p><span class="box-header with-border"> (AN ISO 9001:2008 Certified ) </span></p></center>
            </section>
            <section class="content">
            <div class="row">
                <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                    </div>
                    <div class="box-body">
                    <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label"></label>
                            <center>
                            <div class="col-sm-2">
                            Your application saved successfully.<br><br><strong>Your Membership No is</strong> '.$user_info[0]['regnumber'].' <strong>and Your password is </strong>'.$decpass.'<br><br>Please note down your Membership No and Password for further reference.<br> <br>You may print or save membership registration page for further reference.<br><br>Please ensure proper Page Setup before printing.<br><br>Click on Continue to print registration page.<br><br>You can save system generated application form as PDF for future refence
                            </div>
                            </center>
                        </div>
                    </div>
                </div> 
                </div>
            </div>
            </section>
        </div>';
        
            //this the the PDF filename that user will get to download
            $pdfFilePath = 'iibf'.'.pdf';
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

    //call back for e-mail duplication
	public function check_emailduplication($email)
    {
         if($email!="")
         {
             $where="(registrationtype='NM')";
             $this->db->where($where);
             $prev_count=$this->master_model->getRecordCount('member_registration',array('email'=>$email,'isactive'=>'1'));
             if($prev_count==0)
             {	
                 return true;
             }
             else
             {
                 $str='The entered email ID already exist';
                 $this->form_validation->set_message('check_emailduplication', $str); 
                 return false;
             }
         }
         else
         {
             return false;
         }
    }

     //call back for checkpin
	public function check_checkpin($pincode,$statecode)
	{
		if($statecode!="" && $pincode!='')
		{
			$this->db->where("$pincode BETWEEN start_pin AND end_pin");
		 	$prev_count=$this->master_model->getRecordCount('state_master',array('state_code'=>$statecode));
			//echo $this->db->last_query();
			if($prev_count==0)
			{	$str='Please enter Valid Pincode';
				$this->form_validation->set_message('check_checkpin', $str); 
				return false;}
			else
			$this->form_validation->set_message('error', "");
			{return true;}
		}
		else
		{
			$str='Pincode/State field is required.';
			$this->form_validation->set_message('check_checkpin', $str); 
			return false;
		}
	}
    //call back for mobile duplication
	public function check_mobileduplication($mobile)
	{
		if($mobile!="")
		{
			$where="(registrationtype='NM')";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('mobile'=>$mobile,'isactive'=>'1'));
			//echo $this->db->last_query();
			if($prev_count==0)
			{
				return true;
				}
			else
			{
				$str='The entered Mobile no already exist';
				$this->form_validation->set_message('check_mobileduplication', $str); 
				return false;
			}
		}
		else
		{
			return false;
		}
	}

    //check aadhar card
	public function check_aadhar($aadhar_card)
	{
		
		if($aadhar_card!="")
		{
			$where="registrationtype='NM'";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('aadhar_card'=>$aadhar_card,'isactive'=>'1'));
			//echo $this->db->last_query();
			//exit;
			if($prev_count==0)
			{
				return true;
			}
			else
			{
				$str='The entered Aadhar card number already exist';
				$this->form_validation->set_message('check_aadhar', $str); 
				return false;
			}
		}
		else
		{
			return false;
		}
	}

     
	 //Genereate random password function
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
	//call back for check exam code 19 and specified qualification to be Company secretary(CS)
	public function check_exam_eligibility($specify_qualification,$examcode)
	{
		if($specify_qualification!="" && $examcode!='')
		{
			if($examcode==19)
			{
					if($specify_qualification==91 && $examcode==19)
					{	
						$this->form_validation->set_message('error', "");
						return true;
					}
					else
					{
						$str='You are not eligible to apply for this exam, you should have CS qualification.';
						$this->form_validation->set_message('check_exam_eligibility', $str); 
						return false;
					}
			}
			else
			{
				return true;
			}
		}
		else
		{
			$str='exam / qualification field is required.';
			$this->form_validation->set_message('check_exam_eligibility', $str); 
			return false;
		}
	}
	
	//check mail alredy exist or not
	public function emailduplication()
	{
		$email=$_POST['email'];
		if($email!="")
		{
			$where="(registrationtype='NM')";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('email'=>$email,'isactive'=>'1'));
			//echo $this->db->last_query();
			if($prev_count==0)
			{	
				$data_arr=array('ans'=>'ok');		
				echo json_encode($data_arr);}
			else
			{
				//$user_info=$this->master_model->getRecords('member_registration',array('email'=>$email),'regnumber,firstname,middlename,lastname');
				//$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
				//$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				//$str='The entered email ID / mobile no already exist';
				$str='You are already registered and the email ID is in use.  If you have registered under non-member category for any other exam, please use the same registration number for applying for other examinations also.';
				$data_arr=array('ans'=>'exists','output'=>$str);		
				echo json_encode($data_arr);
				
			}
		}
		else
		{
			echo 'error';
		}
	}
	
	##---------check mobile number alredy exist or not for non member(Pratibha Borse)-----------##
	public function mobileduplication()
	{
		$mobile=$_POST['mobile'];
		if($mobile!="")
		{
			$where="( registrationtype='NM')";
			$this->db->where($where);
			$prev_count=$this->master_model->getRecordCount('member_registration',array('mobile'=>$mobile,'isactive'=>'1'));
			//echo $this->db->last_query();
			if($prev_count==0)
			{
				$data_arr=array('ans'=>'ok');		
				echo json_encode($data_arr);
				}
			else
			{
				//$user_info=$this->master_model->getRecords('member_registration',array('mobile'=>$mobile),'regnumber,firstname,middlename,lastname');
				//$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
				//$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				//$str='The entered email ID / mobile no already exist';
				$str='You are already registered and the Mobile no is in use.  If you have registered under non-member category for any other exam, please use the same registration number for applying for other examinations also.';
				$data_arr=array('ans'=>'exists','output'=>$str);		
				echo json_encode($data_arr);
			}
		}
		else
		{
			echo 'error';
		}
	}
	
	 public function checkpin()
	{
		$statecode=$_POST['statecode'];
		$pincode=$_POST['pincode'];
		if($statecode!="")
		{
			$this->db->where("$pincode BETWEEN start_pin AND end_pin");
		 	$prev_count=$this->master_model->getRecordCount('state_master',array('state_code'=>$statecode));
			//echo $this->db->last_query();
			if($prev_count==0)
			{echo 'false';}
			else
			{echo 'true';}
		}
		else
		{
			echo 'false';
		}
	}


    	##------------------Exam appky with SBI Payment Gate-way(Pratibha Borse)---------------##

	public function sbi_make_payment()
	{

		////check temp file uploaded or not////

		$images_flag=0;

		if(!file_exists("uploads/photograph/".$this->session->userdata['enduserinfo']['photoname']))

		{

			$images_flag=1;

		}

		if(!file_exists("uploads/scansignature/".$this->session->userdata['enduserinfo']['signname']))

		{

			$images_flag=1;

		}

		if(!file_exists("uploads/idproof/".$this->session->userdata['enduserinfo']['idname']))

		{

			$images_flag=1;

		}



		if($images_flag)

		{

			$this->session->set_flashdata('error','Please upload valid image(s)');
            // redirect(base_url().'Ippbapplyexam/candidate_details/'.base64_encode($this->session->userdata['enduserinfo']['emp_id']));

		}

		

		//check email,mobile duplication on the same time from different browser!!

		// $update_data = array('createdon' => date('Y-m-d H:i:s'));
		// $this->db->or_where('regnumber',$this->session->userdata['non_memberdata']['regno']);
		// $this->db->or_where('regid',$this->session->userdata['non_memberdata']['regno']);
		// $this->master_model->updateRecord('member_registration',$update_data,array());

		/* $endTime = date("H:i:s");

		$start_time= date("H:i:s",strtotime("-120 minutes",strtotime($endTime)));

		$this->db->where('Time(createdon) BETWEEN "'. $start_time. '" and "'. $endTime.'"');

		$this->db->where('email',$this->session->userdata['enduserinfo']['email']);

		$this->db->or_where('mobile',$this->session->userdata['enduserinfo']['mobile']);

		$check_duplication=$this->master_model->getRecords('member_registration',array('isactive'=>0));

		
        //echo $this->db->last_query(); print_r($check_duplication); exit;

		if(count($check_duplication) > 1)

		{

			redirect(base_url().'Ippbapplyexam/accessdenied/');

		} */

		$cgst_rate=$sgst_rate=$igst_rate=$tax_type='';

		$cgst_amt=$sgst_amt=$igst_amt='';

		$cs_total=$igst_total='';

		$getstate=$getcenter=$getfees=array();

		$valcookie= applyexam_get_cookie();

		if($valcookie)

		{

			delete_cookie('examid');

			redirect('http://iibf.org.in/');

		}

		if(isset($_POST['processPayment']) && $_POST['processPayment'])

		{


			$log_title ="IPPB - Clicked on processPayment ".$this->session->userdata['enduserinfo']['emp_id'];
						$log_message = serialize($this->session->userdata('enduserinfo'));
						$rId = $this->session->userdata['enduserinfo']['emp_id'];
						$regNo = $this->session->userdata['enduserinfo']['emp_id'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);

			$pg_name = $this->input->post('pg_name');

			$regno = $this->session->userdata['non_memberdata']['regno'];
            // echo"<pre>"; print_r($this->session->userdata['non_memberdata']); exit;

			

			if($this->config->item('sb_test_mode'))

			{

				$amount = $this->config->item('exam_apply_fee');

			}

			else

			{

				$amount=getExamFee($this->session->userdata['enduserinfo']['txtCenterCode'],$this->session->userdata['enduserinfo']['eprid'],base64_encode($this->session->userdata['enduserinfo']['excd']),$this->session->userdata['enduserinfo']['grp_code'],'NM','');

				//$amount=$this->session->userdata['enduserinfo']['fee'];

			}

			

			if($amount==0 || $amount=='')

			{

				$this->session->set_flashdata('error','Fee can not be zero(0) or Blank!!');

				redirect(base_url().'Ippbapplyexam/preview/');

			}

			//$MerchantOrderNo    = generate_order_id("sbi_exam_order_id");



			//With Registration Non-member

			//Non memeber / DBF Apply exam

			//Ref1 = orderid

			//Ref2 = iibfexam

			//Ref3 = orderid

			//Ref4 = exam_code + exam year + exam month ex (101201602)

			$yearmonth=$this->master_model->getRecords('misc_master',array('exam_code'=>$this->session->userdata['enduserinfo']['excd'],'exam_period'=>$this->session->userdata['enduserinfo']['eprid']),'exam_month');

            $exam_code=$this->session->userdata['enduserinfo']['excd'];

			$ref4=$exam_code.$yearmonth[0]['exam_month'];

			// Create transaction

			$member_exam_id=$this->session->userdata['non_memberdata']['member_exam_id'];
            if ($pg_name == 'sbi'){
                $gateway='sbiepay';
            }else{
                $gateway='billdesk';
            }

            $pg_flag='IPPB_EXAM_REG';
            $exam_desc= $this->session->userdata['non_memberdata']['exam_desc'];

			//priyanka d- 16-may-23 - adding this check because multiple records get added for same member_exam_id
			$checkRecordAlExist=$this->master_model->getRecords('payment_transaction',array('ref_id'=>$member_exam_id,'exam_code'=>997));
			if(count($checkRecordAlExist) > 0)
			{

						$log_title ="IPPB - Double record found ".$this->session->userdata['enduserinfo']['emp_id'];
						$log_message = serialize($checkRecordAlExist);
						$rId = $member_exam_id;
						$regNo = $this->session->userdata['enduserinfo']['emp_id'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);

				$this->session->set_flashdata('error','Duplicate records found for Agent Id:'.$this->session->userdata['enduserinfo']['emp_id'].' please try again');
				redirect(base_url().'Ippbapplyexam/login');
			}
			$insert_data = array(

				'member_regnumber' => $regno,

				'amount'           => $amount,

				'gateway'          => $gateway,

				'date'             => date('Y-m-d H:i:s'),

				'pay_type'         => '2',

				'ref_id'           => $member_exam_id,

				'description'      => $exam_desc,

				'status'           => '2',

				'exam_code'        => $this->session->userdata['enduserinfo']['excd'],

				'pg_flag'=>$pg_flag,

			);

			

			$pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);

			

			$MerchantOrderNo = sbi_exam_order_id($pt_id);

			

			// payment gateway custom fields -

			$custom_field = $MerchantOrderNo."^iibfexam^".$MerchantOrderNo."^".$ref4;

			

			// update receipt no. in payment transaction -

			$update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);

			$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));

			

			//set invoice details(Pratibha Borse)

			$getcenter=$this->master_model->getRecords('center_master',array('exam_name'=>$this->session->userdata['enduserinfo']['excd'],'center_code'=>$this->session->userdata['enduserinfo']['txtCenterCode'],'exam_period'=>$this->session->userdata['enduserinfo']['eprid'],'center_delete'=>'0'));

			if(count($getcenter) > 0)
			{

				//get state code,state name,state number.

				$getstate=$this->master_model->getRecords('state_master',array('state_code'=>$getcenter[0]['state_code'],'state_delete'=>'0'));
				
                // echo "<pre>"; print_r(getExamFeedetails(188,851,OTk3,B1_1,'NM','')); print_r($this->session->userdata['enduserinfo']); exit;

				//call to helper (fee_helper)

				$getfees=getExamFeedetails($this->session->userdata['enduserinfo']['txtCenterCode'],$this->session->userdata['enduserinfo']['eprid'],base64_encode($this->session->userdata['enduserinfo']['excd']),$this->session->userdata['enduserinfo']['grp_code'],$this->session->userdata['enduserinfo']['mtype'],'');
                // print_r($getfees); exit;
			}

		

			if($getcenter[0]['state_code']=='MAH')
			{

				//set a rate (e.g 9%,9% or 18%)

				$cgst_rate=$this->config->item('cgst_rate');

				$sgst_rate=$this->config->item('sgst_rate');


					//set an amount as per rate

					$cgst_amt=$getfees[0]['cgst_amt'];

					$sgst_amt=$getfees[0]['sgst_amt'];

				 	//set an total amount

					$cs_total=$getfees[0]['cs_tot'];

					$amount_base = $getfees[0]['fee_amount'];



				

				$tax_type='Intra';

			

			}else{


					$igst_rate=$this->config->item('igst_rate');

					$igst_amt=$getfees[0]['igst_amt'];

					$igst_total=$getfees[0]['igst_tot'];

				 	$amount_base = $getfees[0]['fee_amount'];

				    $tax_type='Inter';

			}

			if($getstate[0]['exempt']=='E')
			{

				 $cgst_rate=$sgst_rate=$igst_rate='';	

				 $cgst_amt=$sgst_amt=$igst_amt='';	

			}

			$invoice_insert_array=array('pay_txn_id'=>$pt_id,

														'receipt_no'=>$MerchantOrderNo,

														'exam_code'=>$this->session->userdata['enduserinfo']['excd'],

														'center_code'=>$getcenter[0]['center_code'],

														'center_name'=>$getcenter[0]['center_name'],

														'state_of_center'=>$getcenter[0]['state_code'],

														'member_no'=>$regno,

														'app_type'=>'O',

														'exam_period'=>$this->session->userdata['enduserinfo']['eprid'],

														'service_code'=>$this->config->item('exam_service_code'),

														'qty'=>'1',

														'state_code'=>$getstate[0]['state_no'],

														'state_name'=>$getstate[0]['state_name'],

														'tax_type'=>$tax_type,

														'fee_amt'=>$amount_base,

														'cgst_rate'=>$cgst_rate,

														'cgst_amt'=>$cgst_amt,

														'sgst_rate'=>$sgst_rate,

														'sgst_amt'=>$sgst_amt,

														'igst_rate'=>$igst_rate,

														'igst_amt'=>$igst_amt,

														'cs_total'=>$cs_total,

														'igst_total'=>$igst_total,

														'exempt'=>$getstate[0]['exempt'],

														'created_on'=>date('Y-m-d H:i:s')
                                                    );

								

			$inser_id=$this->master_model->insertRecord('exam_invoice',$invoice_insert_array,true);

			

			$log_title ="Non member exam invoice insertion :".$inser_id;

			$log_message = serialize($invoice_insert_array);

			$rId = $inser_id;

			$regNo = $inser_id;

			storedUserActivity($log_title, $log_message, $rId, $regNo);

			

			//if exam invocie entry skip

			if($inser_id==''){

				$inser_id=$this->master_model->insertRecord('exam_invoice',$invoice_insert_array,true);

				

				$log_title ="Non member exam invoice insertion again :".$inser_id;

				$log_message = serialize($invoice_insert_array);

				$rId = $inser_id;

				$regNo = $inser_id;

				storedUserActivity($log_title, $log_message, $rId, $regNo);

			}

			

			

			$log_title = "Exam invoice data from IPPB Nonreg cntrlr inser_id = '".$inser_id."'";

			$log_message = serialize($invoice_insert_array);

			$rId = $regno;

			$regNo = $regno;

			storedUserActivity($log_title, $log_message, $rId, $regNo);

			//insert into admit card table

			//################get userdata###########
			$this->db->or_where('regnumber',$regno);
			$this->db->or_where('regid',$regno);
			$user_info=$this->master_model->getRecords('member_registration',array());
            // echo $this->db->last_query(); print_r($user_info); exit; 
			//get associate institute details

			$institute_id='';

			$institution_name='';

			if($user_info[0]['associatedinstitute']!='')

			{

				$institution_master=$this->master_model->getRecords('institution_master',array('institude_id'=>$user_info[0]['associatedinstitute']));

				if(count($institution_master) >0)

				{

					$institute_id=$institution_master[0]['institude_id'];

					$institution_name=$institution_master[0]['name'];

				}

			}

		 //############check Gender########

			if($user_info[0]['gender']=='male')

			{$gender='M';}

			else

			{$gender='F';}

			//########prepare user name########

			$username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];

			$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);

			

			//###########get State##########

			$states=$this->master_model->getRecords('state_master',array('state_code'=>$user_info[0]['state'],'state_delete'=>'0'));

			$state_name='';

			if(count($states) >0)

			{

				$state_name=$states[0]['state_name'];

			}		

			//##############Examination Mode###########

			if($this->session->userdata['enduserinfo']['optmode']=='ON')

			{

				$mode='Online';

			}

			else

			{

				$mode='Offline';

			}	

			

			if(!empty($this->session->userdata['enduserinfo']['subject_arr']))

			{

					foreach($this->session->userdata['enduserinfo']['subject_arr'] as $k=>$v)

					{	
                        $this->db->group_by('subject_code');	

                        $compulsory_subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->session->userdata['enduserinfo']['excd'],'subject_delete'=>'0','group_code'=>'C','exam_period'=>$this->session->userdata['enduserinfo']['eprid'],'subject_code'=>$k),'subject_description');

                        $get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v['venue'],'exam_date'=>'0000-00-00','session_time'=>'','center_code'=>$this->session->userdata['enduserinfo']['selCenterName']));
								// echo $this->db->last_query().'<br>';echo"<pre>";  print_r($user_info); exit; 
						$admitcard_insert_array=array('mem_exam_id'=>$member_exam_id,

													'center_code'=>$getcenter[0]['center_code'],

													'center_name'=>$getcenter[0]['center_name'],

													'mem_type'=>$this->session->userdata['enduserinfo']['mtype'],

													'mem_mem_no'=>$regno,

													'g_1'=>$gender,

													'mam_nam_1'=>$userfinalstrname,

													'mem_adr_1'=>$user_info[0]['address1'],

													'mem_adr_2'=>$user_info[0]['address2'],

													'mem_adr_3'=>$user_info[0]['address3'],

													'mem_adr_4'=>$user_info[0]['address4'],

													'mem_adr_5'=>$user_info[0]['district'],

													'mem_adr_6'=>$user_info[0]['city'],

													'mem_pin_cd'=>$user_info[0]['pincode'],

													'state'=>$state_name,

													'exm_cd'=>$this->session->userdata['enduserinfo']['excd'],

													'exm_prd'=>$this->session->userdata['enduserinfo']['eprid'],

													'sub_cd '=>$k,

													'sub_dsc'=>$compulsory_subjects[0]['subject_description'],

													'm_1'=>$this->session->userdata['enduserinfo']['medium'],

													'inscd'=>$institute_id,

													'insname'=>$institution_name,

													'venueid'=>$get_subject_details[0]['venue_code'],

													'venue_name'=>$get_subject_details[0]['venue_name'],

													'venueadd1'=>$get_subject_details[0]['venue_addr1'],

													'venueadd2'=>$get_subject_details[0]['venue_addr2'],

													'venueadd3'=>$get_subject_details[0]['venue_addr3'],

													'venueadd4'=>$get_subject_details[0]['venue_addr4'],

													'venueadd5'=>$get_subject_details[0]['venue_addr5'],

													'venpin'=>$get_subject_details[0]['venue_pincode'],

													'exam_date'=>$v['date'],

													'time'=>$v['session_time'],

													'mode'=>$mode,

													'scribe_flag'=>$this->session->userdata['enduserinfo']['scribe_flag'],

													'vendor_code'=>$get_subject_details[0]['vendor_code'],

													'remark'=>2,

													'created_on'=>date('Y-m-d H:i:s'));
                                                    // echo"<pre>";  print_r($admitcard_insert_array); exit;

						$inser_id=$this->master_model->insertRecord('admit_card_details',$admitcard_insert_array);

						$log_title ="Non member admit card detail insertion :".$inser_id;

						$log_message = serialize($admitcard_insert_array);

						$rId = $inser_id;

						$regNo = $inser_id;

						storedUserActivity($log_title, $log_message, $rId, $regNo);

					}

			}

			else

			{
				
				if($this->session->userdata['enduserinfo']['excd']!=101)

				{

					$this->session->set_flashdata('Error','Session Expired!!');

					redirect(base_url().'Ippbapplyexam/preview/');

				}

			}

			//set cookie for Apply Exam

			applyexam_set_cookie($regno);



            /* This changes made by Pratibha borse Start code 09Feb2022 */
            if ($pg_name == 'sbi'){
                include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
	            $key            = $this->config->item('sbi_m_key');
	            $merchIdVal     = $this->config->item('sbi_merchIdVal');
	            $AggregatorId   = $this->config->item('sbi_AggregatorId');
	            $pg_success_url = base_url() . "Ippbapplyexam/sbitranssuccess";
	            $pg_fail_url    = base_url() . "Ippbapplyexam/sbitransfail";

                $MerchantCustomerID  = $regno;
	            $data["pg_form_url"] = $this->config->item('sbi_pg_form_url');
	            $data["merchIdVal"]  = $merchIdVal;
			    $EncryptTrans = $merchIdVal."|DOM|IN|INR|".$amount."|".$custom_field."|".$pg_success_url."|".$pg_fail_url."|".$AggregatorId."|".$MerchantOrderNo."|".$MerchantCustomerID."|NB|ONLINE|ONLINE";
	            $aes                 = new CryptAES();
	            $aes->set_key(base64_decode($key));
	            $aes->require_pkcs5();
	            $EncryptTrans         = $aes->encrypt($EncryptTrans);
	            $data["EncryptTrans"] = $EncryptTrans;
	            $this->load->view('pg_sbi_form', $data);
			}elseif ($pg_name == 'billdesk'){
                $custom_field_billdesk    = $regno . "-" . $pg_flag . "-" . $MerchantOrderNo . "-" . $regno;
                $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $regno, $regno, '', 'Ippbapplyexam/handle_billdesk_response', '', '', '', $custom_field_billdesk);
				 if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {

                    $data['bdorderid'] = $billdesk_res['bdorderid'];
                    $data['token']     = $billdesk_res['token'];
										$data['responseXHRUrl'] = $billdesk_res['responseXHRUrl']; 
										$data['returnUrl'] = $billdesk_res['returnUrl'];                    
                    $this->load->view('pg_billdesk/pg_billdesk_form', $data);
                }else{
                    $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                    redirect(base_url() . 'Ippbapplyexam/login');
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

	

	public function sbitranssuccess()

	{
        $this->load->helper('update_image_name_helper');
		$valcookie= applyexam_get_cookie();

		if($valcookie)

		{

			delete_cookie('examid');

		}

		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

		$key = $this->config->item('sbi_m_key');

		$aes = new CryptAES();

		$aes->set_key(base64_decode($key));

		$aes->require_pkcs5();

		$encData = $aes->decrypt($_REQUEST['encData']);

		$responsedata = explode("|",$encData);
        // print_r($responsedata); exit;

		$MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id

		$transaction_no  = $responsedata[1];

		$attachpath=$invoiceNumber=$admitcard_pdf='';

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

		

		//SBI CALLBACK B2B

		// Handle transaction success case 

		$q_details = sbiqueryapi($MerchantOrderNo);

		if ($q_details)

		{

			if ($q_details[2] == "SUCCESS")

			{

				$this->db->order_by('ref_id','DESC');
				$this->db->limit(1);
				$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
				
				if($get_user_regnum[0]['status']==2)

				{

					######### payment Transaction ############

					$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');

					$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));

					if($this->db->affected_rows())
					{
						$exam_code=$get_user_regnum[0]['exam_code'];
                        $reg_id=$get_user_regnum[0]['member_regnumber'];

						############check capacity is full or not ##########
						$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
						//$subject_arr=$this->session->userdata['enduserinfo']['subject_arr'];
						if(count($exam_admicard_details) > 0)
						{		
							$msg='';
							$sub_flag=1;
							$sub_capacity=1;
							foreach($exam_admicard_details as $row)
							{
								$capacity=csc_check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
								if($capacity==0)
								{
									#########get message if capacity is full##########
									/*Add code trans_start & trans_complete :   */
					                $update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
									$this->db->order_by('id','DESC');
									$this->db->limit(1);
									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
									$log_title ="IPPB nonreg Capacity full id:".$get_user_regnum[0]['member_regnumber'];
									$log_message = serialize($exam_admicard_details);
									$rId = $get_user_regnum[0]['ref_id'];
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);
                                    redirect(base_url().'Ippbapplyexam/refund/'.base64_encode($MerchantOrderNo));
								}
							}
						}
						
                        // $this->db->or_where('regnumber',$reg_id);
                        // $this->db->or_where('regid',$reg_id);
						$firstCharacterreg_id = substr($reg_id, 0, 1);
						if($firstCharacterreg_id !== '8'){
							$this->db->where('regid',$reg_id);
						}else{
							$this->db->where('regnumber',$reg_id);
						}
						
                        $user_info=$this->master_model->getRecords('member_registration',array());

                        if($user_info[0]['regnumber'] !== '' ){

							$firstStringCharacter = substr($user_info[0]['regnumber'], 0, 1);
							if($firstStringCharacter !== '3' || $firstStringCharacter == '8'){
								$applicationNoIppb = $user_info[0]['regnumber'];
							}else{
								$applicationNoIppb = generate_NM_memreg($reg_id);
							}
                        }else{
                            $applicationNoIppb = generate_NM_memreg($reg_id);
                        }

						$firstCharacterapplicationNoIppb = substr($applicationNoIppb, 0, 1);
						if($firstCharacterapplicationNoIppb !== '8'){
                            $applicationNoIppb = generate_NM_memreg($reg_id);
						}
						

						$log_title ="IPPB nonreg member reg number- updated or new :".$reg_id;
						$log_message = serialize($applicationNoIppb);
						$rId = $reg_id;
						$regNo = $reg_id;
						storedUserActivity($log_title, $log_message, $rId, $regNo);	


						######### payment Transaction ############
						$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNoIppb,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');
						$this->db->order_by('id','DESC');
						$this->db->limit(1);	
						$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
						
						$payment_query_update=$this->db->last_query();
						
						$log_title ="IPPBNONreg Paymnet update :".$payment_query_update;
						$log_message = serialize($update_data);
						$rId = $get_user_regnum[0]['ref_id'];
						$regNo = $get_user_regnum[0]['member_regnumber'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);
						######### Exam Invoice Transaction ############
						$update_data = array('transaction_no'=>$transaction_no);
						$this->db->where('receipt_no',$MerchantOrderNo);
						$this->db->order_by('invoice_id','DESC');
						$this->db->limit(1);	
						$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
						########## Update Member Registration#############

                        
                        // echo 'before update mem_reg'.$reg_id; 
                        $update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNoIppb);
                        $this->db->order_by('regid','DESC');
                        $this->db->limit(1);
						
                        // $this->db->or_where('regnumber',$reg_id);
                        // $this->db->or_where('regid',$reg_id);
						$firstCharacterreg_id = substr($reg_id, 0, 1);
						if($firstCharacterreg_id !== '8'){
							$this->db->where('regid',$reg_id);
						}else{
							$this->db->where('regnumber',$reg_id);
						}
                        $this->master_model->updateRecord('member_registration',$update_mem_data,array());

						##########Update IPPB employee regnumber #############
                        $update_mem_data = array('regnumber'=>$applicationNoIppb,'updatedon'=>date('Y-m-d H:i:s'));
                        $this->db->limit(1);
                        $this->db->or_where('regnumber',$reg_id);
                        $this->db->or_where('id',$last_emp_id);
                        $this->master_model->updateRecord('employee_data_ippb',$update_mem_data,array());

						
						##########Update Member Exam#############
						$update_data = array('pay_status' => '1','regnumber'=>$applicationNoIppb);
						$this->db->order_by('id','DESC');
						$this->db->limit(1);
						$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
						
						
						########## Generate Invoice #############
						$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNoIppb),'transaction_no,date,amount,id');
						//get invoice	
						$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
						$invoice_get_query= $this->db->last_query();
						$log_title ="IPPB NONreg invoice get query :".$invoice_get_query;
						$log_message = serialize($getinvoice_number);
						$rId = $get_user_regnum[0]['ref_id'];
						$regNo = $get_user_regnum[0]['member_regnumber'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);	
						if(count($getinvoice_number) > 0)
						{
								$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
								if($invoiceNumber)
								{
									$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
								}
							$update_data = array('invoice_no' => $invoiceNumber,'member_no'=>$applicationNoIppb,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
							/*Add code trans_start & trans_complete :   */
							$this->db->where('pay_txn_id',$payment_info[0]['id']);
							$this->db->order_by('invoice_id','DESC');
							$this->db->limit(1);
							$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
							
							
							$invoice_update_query= $this->db->last_query();
							$log_title ="IPPB NONreg invoice update query :".$invoice_update_query;
							$log_message = serialize($update_data);
							$rId = $get_user_regnum[0]['ref_id'];
							$regNo = $get_user_regnum[0]['member_regnumber'];
							storedUserActivity($log_title, $log_message, $rId, $regNo);	
							
							$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
							$log_title ="IPPB NONreg invoice Img path";
							$log_message = serialize($attachpath);
							$rId = $get_user_regnum[0]['ref_id'];
							$regNo = $get_user_regnum[0]['member_regnumber'];
							storedUserActivity($log_title, $log_message, $rId, $regNo);	
						}
                        //Query to get exam details	
                        $this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period','left');
                        $this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
                        $this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
                        $this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                        $exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNoIppb),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
                        $exam_info_query = $this->db->last_query();
                        
                        $log_title ="IPPBNONreg admit_card_details :".$exam_info_query;
                        $log_message = serialize($exam_info);
                        $rId = $get_user_regnum[0]['ref_id'];
                        $regNo = $get_user_regnum[0]['member_regnumber'];
                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                        
                        ########## Generate Admit card and allocate Seat #############
                        if(count($exam_admicard_details) > 0)
                        {
                            $password=random_password();
                            foreach($exam_admicard_details as $row)
                            {
                                $query='(exam_date = "0000-00-00" OR exam_date = "")';
                                $this->db->where($query);
                                $this->db->where('session_time=','');
                                if($this->session->userdata['csc_venue_flag'] == 'F'){
                                    $this->db->where('venue_flag','F');
                                }elseif($this->session->userdata['csc_venue_flag'] == 'P'){
                                    $this->db->where('venue_flag','P');
                                }
                                $get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'center_code'=>$row['center_code']));
                                
                                $admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
                                
                                //echo $this->db->last_query().'<br>';
                                $seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$admit_card_details[0]['exam_date'],$admit_card_details[0]['time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
                    
                                if($seat_number!='')
                                {
                                    $final_seat_number = $seat_number;
                                    $update_data = array('mem_mem_no'=>$applicationNoIppb,'pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
                                    $this->db->order_by('mem_exam_id','DESC');
                                    $this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
                                }
                                else
                                {
                                    $admit_card_details=$this->master_model->getRecords('admit_card_details',array('admitcard_id'=>$admit_card_details[0]['admitcard_id'],'remark'=>1));
                                    if(count($admit_card_details) > 0)
                                    {
                                        $log_title ="IPPB NONreg Seat number already allocated id:".$get_user_regnum[0]['member_regnumber'];
                                        $log_message = serialize($exam_admicard_details);
                                        $rId = $get_user_regnum[0]['ref_id'];
                                        $regNo = $get_user_regnum[0]['member_regnumber'];
                                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                                    }
                                    else
                                    {
                                        $log_title ="IPPB NONreg Fail user seat allocation id:".$applicationNoIppb;
                                        $log_message = serialize($this->session->userdata['enduserinfo']['subject_arr']);
                                        $rId = $get_user_regnum[0]['ref_id'];
                                        $regNo = $get_user_regnum[0]['member_regnumber'];
                                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                                        //redirect(base_url().'Nonreg/refund/'.base64_encode($MerchantOrderNo));
                                    }
                                }
                            }
                        }	
                        else
                        {
                            redirect(base_url().'Ippbapplyexam/refund/'.base64_encode($MerchantOrderNo));
                        }
                        
                        ##############Get Admit card#############
                        $admitcard_pdf=genarate_admitcard($applicationNoIppb,$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
                        $log_title ="IPPB NONreg admit cart image path:";
                        $log_message = serialize($admitcard_pdf);
                        $rId = $get_user_regnum[0]['ref_id'];
                        $regNo = $get_user_regnum[0]['member_regnumber'];
                        storedUserActivity($log_title, $log_message, $rId, $regNo);
						
					
					
						if($exam_info[0]['exam_mode']=='ON')
						{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
						{$mode='Offline';}
						else{$mode='';}
						//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
						$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
						$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
						//Query to get Medium	
						$this->db->where('exam_code',$exam_code);
						$this->db->where('exam_period',$exam_info[0]['exam_period']);
						$this->db->where('medium_code',$exam_info[0]['exam_medium']);
						$this->db->where('medium_delete','0');
						$medium=$this->master_model->getRecords('medium_master','','medium_description');
						//Query to get Payment details	
						
						//Query to get user details
						$this->db->join('state_master','state_master.state_code=member_registration.state');
                        // $this->db->or_where('regnumber',$reg_id);
                        // $this->db->or_where('regid',$reg_id);
						$firstCharacterreg_id = substr($reg_id, 0, 1);
						if($firstCharacterreg_id !== '8'){
							$this->db->where('regid',$reg_id);
						}else{
							$this->db->where('regnumber',$reg_id);
						}
						$result=$this->master_model->getRecords('member_registration',array(),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,usrpassword');	
						
						########get Old image Name############
						$log_title ="IPPB NONreg OLD Image :".$reg_id;
						$log_message = serialize($result);
						$rId = $get_user_regnum[0]['ref_id'];
						$regNo = $get_user_regnum[0]['member_regnumber'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);	
						
						$upd_files = array();
						$photo_file = 'p_'.$applicationNoIppb.'.jpg';
						$sign_file = 's_'.$applicationNoIppb.'.jpg';
						$proof_file = 'pr_'.$applicationNoIppb.'.jpg';
						
						/* if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
						{	$upd_files['scannedphoto'] = $photo_file;	} */
						$chk_photo = update_image_name("./uploads/photograph/", $result[0]['scannedphoto'], $photo_file); //update_image_name_helper.php
						if($chk_photo != "") { $upd_files['scannedphoto'] = $chk_photo; }
						
						/* if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
						{	$upd_files['scannedsignaturephoto'] = $sign_file;	} */
						$chk_sign = update_image_name("./uploads/scansignature/", $result[0]['scannedsignaturephoto'], $sign_file); //update_image_name_helper.php
						if($chk_sign != "") { $upd_files['scannedsignaturephoto'] = $chk_sign; }
						
						/* if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
						{	$upd_files['idproofphoto'] = $proof_file;	} */
						$chk_proof = update_image_name("./uploads/idproof/", $result[0]['idproofphoto'], $proof_file); //update_image_name_helper.php
						if($chk_proof != "") { $upd_files['idproofphoto'] = $chk_proof; }
						
						if(count($upd_files)>0)
						{
                            // $this->db->or_where('regnumber',$reg_id);
                            // $this->db->or_where('regid',$reg_id);
							$firstCharacterreg_id = substr($reg_id, 0, 1);
							if($firstCharacterreg_id !== '8'){
								$this->db->where('regid',$reg_id);
							}else{
								$this->db->where('regnumber',$reg_id);
							}

							$this->master_model->updateRecord('member_registration',$upd_files,array());
							$log_title ="IPPB NONreg PICS Update :".$reg_id;
							$log_message = serialize($this->db->last_query());
							$rId = $get_user_regnum[0]['ref_id'];
							$regNo = $get_user_regnum[0]['member_regnumber'];
							storedUserActivity($log_title, $log_message, $rId, $regNo);	
						}
						else
						{
								$upd_files['scannedphoto'] = $photo_file;
								$upd_files['scannedsignaturephoto'] = $sign_file;	
								$upd_files['idproofphoto'] = $proof_file;
                                // $this->db->or_where('regnumber',$reg_id);
                                // $this->db->or_where('regid',$reg_id);
								$firstCharacterreg_id = substr($reg_id, 0, 1);
								if($firstCharacterreg_id !== '8'){
									$this->db->where('regid',$reg_id);
								}else{
									$this->db->where('regnumber',$reg_id);
								}
								$this->master_model->updateRecord('member_registration',$upd_files,array());
								$log_title ="IPPB NONreg MANUAL PICS Update :".$reg_id;
								$log_message = serialize($upd_files);
								$rId = $get_user_regnum[0]['ref_id'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);	
						}
				
						$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
						$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);

						include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
						$key = $this->config->item('pass_key');
						$aes = new CryptAES();
						$aes->set_key(base64_decode($key));
						$aes->require_pkcs5();
						$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
						
						$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
						$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
						$newstring2 = str_replace("#REG_NUM#", "".$applicationNoIppb."",$newstring1);
						$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
						$newstring4 = str_replace("#EXAM_DATE#", "-",$newstring3);
						$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
						$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
						$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
						$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
						$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
						$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
						$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
						$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
						$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
						$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
						$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
						$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
						$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
						$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
						$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
						$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring19);
						$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
						if(count($elern_msg_string) > 0)
						{
							foreach($elern_msg_string as $row)
							{
								$arr_elern_msg_string[]=$row['exam_code'];
							}
							if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
							{
								$newstring21 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring20);		
							}
							else
							{
								$newstring21 = str_replace("#E-MSG#", '',$newstring20);		
							}
						}
						else
						{
							$newstring21 = str_replace("#E-MSG#", '',$newstring20);
						}
						$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
						$info_arr=array('to'=>$result[0]['email'],
												'from'=>$emailerstr[0]['from'],
												'subject'=>$emailerstr[0]['subject'],
												'message'=>$final_str
											);
						if($attachpath!='')
						{		
							//send sms	
							$files=array($attachpath,$admitcard_pdf);				
							$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
							$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
							$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
							$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
							// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);
							$message = 'Thanks for enrolling for BCBF-Payment Bank-exam. Your exam form and fee '.$payment_info[0]['amount'].' is received vide transaction '.$payment_info[0]['transaction_no'].'. Refer email for details. IIBF Team';
							$res = $this->master_model->send_sms_trustsignal(intval($result[0]['mobile']), $message, 'J0DWe39nR', '997', '', 'IIBFSM');

							$mail_flag=$this->Emailsending->mailsend_attch($info_arr,$files);
							if($mail_flag)
							{
								$log_title ="Mail nonreg Flag success :".$mail_flag;
								$log_message = serialize($info_arr);
								$rId = $get_user_regnum[0]['ref_id'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);	
							}
							else
							{
								$log_title ="Mail nonreg Flag fail :".$mail_flag;
								$log_message = serialize($info_arr);
								$rId = $get_user_regnum[0]['ref_id'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);	
							}
						}else{
							
								$log_title ="Attachement nonreg not found email mail";
								$log_message = serialize($attachpath);
								$rId = $get_user_regnum[0]['ref_id'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);	
						}//Manage Log
					
						
				
					}
					else
					{
						$log_title ="IPPB NONreg B2B Update fail:".$get_user_regnum[0]['member_regnumber'];
						$log_message = serialize($update_data);
						$rId = $MerchantOrderNo;
						$regNo = $get_user_regnum[0]['member_regnumber'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);	
					}
					$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
					$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
					redirect(base_url().'Ippbapplyexam/acknowledge/'.base64_encode($MerchantOrderNo));
				}

			}

		}

		//END OF SBI CALLBACK B2B

		redirect(base_url().'Ippbapplyexam/acknowledge/'.base64_encode($MerchantOrderNo));

	}

	public function sbitransfail()

	{

		//Delete cookie

		$valcookie= applyexam_get_cookie();

		if($valcookie)

		{

			delete_cookie('examid');

		}//cookie deleted

		if (isset($_REQUEST['encData']))

		{

			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

			$key = $this->config->item('sbi_m_key');

			$aes = new CryptAES();

			$aes->set_key(base64_decode($key));

			$aes->require_pkcs5();

			$encData = $aes->decrypt($_REQUEST['encData']);

			$responsedata = explode("|",$encData);

			$MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id

			$transaction_no  = $responsedata[1];

			//SBI CALLBACK SUCCESS

			// Handle transaction fail case 

			$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status,member_regnumber');

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

				$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' =>'0399','bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'B2B');

				$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

			

				//Query to get Payment details	

				$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id,member_regnumber');

				$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');

				

				$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');

				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');

				$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');

				$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');

				$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.regnumber'=>$payment_info[0]['member_regnumber']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');

			

				//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');

				$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);

				$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);

				

				$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];

				$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);

				$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));

				$newstring1 = str_replace("#application_num#", "",  $emailerstr[0]['emailer_text']);

				$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);

				$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);

				$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);

				

				$info_arr=array(	'to'=>$result[0]['email'],

											'from'=>$emailerstr[0]['from'],

											'subject'=>$emailerstr[0]['subject'],

											'message'=>$final_str

										);

				

				// send SMS

				$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);

				$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);

				// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	

				$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'Jw6bOIQGg');	

				$this->Emailsending->mailsend($info_arr);

			//Manage Log

			$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;

			$this->log_model->logtransaction("sbiepay", $pg_response,$responsedata[2]);		

			}

			//END OF SBI CALLBACK SUCCESS

			///Old Code

			redirect(base_url());

		}

		else

		{

			die("Please try again...");

		}

	}

	
    public function handle_billdesk_response()
    {
        $this->load->helper('update_image_name_helper');
        $valcookie= applyexam_get_cookie();

        if($valcookie)
        {
            delete_cookie('examid');

        }

    	// 	echo "<pre>"; print_r($_REQUEST['transaction_response']); exit;
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
      	
        $attachpath=$invoiceNumber=$admitcard_pdf='';

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



            $get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
			// print_r($get_user_regnum); exit;
            if (empty($get_user_regnum)) {
                //redirect(base_url());
            }

			$qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
			if($auth_status == "0300" && $qry_api_response['auth_status'] == '0300' && $get_user_regnum[0]['status'] == 2)
			{				
				// echo "---->"; print_r($get_user_regnum); exit;
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

                $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
				//  echo $this->db->last_query();print_r($update_data); exit;
                /* Transaction Log */
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                // $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
                $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);


                //-------------------------------------------------------------------
				if($this->db->affected_rows())
				{
					$exam_code=$get_user_regnum[0]['exam_code'];
					$reg_id=$get_user_regnum[0]['member_regnumber'];

					############check capacity is full or not ##########
					$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
					//$subject_arr=$this->session->userdata['enduserinfo']['subject_arr'];
					if(count($exam_admicard_details) > 0)
					{		
						$msg='';
						$sub_flag=1;
						$sub_capacity=1;
						foreach($exam_admicard_details as $row)
						{
							$capacity=csc_check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
							if($capacity==0)
							{
								#########get message if capacity is full##########
								/*Add code trans_start & trans_complete :   */
								$update_data = array('transaction_no' => $transaction_no,'status' => 1,'transaction_details' => $transaction_no,'auth_code' => '0300', 'bankcode' =>$bankid, 'paymode' => $txn_process_type,'callback'=>'B2B');
								$this->db->order_by('id','DESC');
								$this->db->limit(1);
								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								$log_title ="IPPB NONreg Capacity full id:".$get_user_regnum[0]['member_regnumber'];
								$log_message = serialize($exam_admicard_details);
								$rId = $get_user_regnum[0]['ref_id'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								redirect(base_url().'Ippbapplyexam/refund/'.base64_encode($MerchantOrderNo));
							}
						}
					}
				
					// $this->db->or_where('regnumber',$reg_id);
					// $this->db->or_where('regid',$reg_id);
					// $firstCharacterreg_id = substr($reg_id, 0, 1);
					// if($firstCharacterreg_id !== '8'){
					// 	$this->db->where('regid',$reg_id);
					// }else{
					// 	$this->db->where('regnumber',$reg_id);
					// }
					// 			$this->db->where('regid',$reg_id);
					// $user_info=$this->master_model->getRecords('member_registration');
					// if($user_info[0]['regnumber'] !== ''){
					// 	$applicationNoIppb = $user_info[0]['regnumber'];
					// }else{
					// 	$applicationNoIppb = generate_NM_memreg($reg_id);
					// }

					// if($user_info[0]['regnumber'] !== '' ){

					// 	$firstStringCharacter = substr($user_info[0]['regnumber'], 0, 1);
					// 	if($firstStringCharacter !== '3' || $firstStringCharacter == '8'){
					// 		$applicationNoIppb = $user_info[0]['regnumber'];
					// 	}else{
					// 		$applicationNoIppb = generate_NM_memreg($reg_id);
					// 	}
					// }else{
					// 	$applicationNoIppb = generate_NM_memreg($reg_id);
					// }
					
					// $firstCharacterapplicationNoIppb = substr($applicationNoIppb, 0, 1);
					// if($firstCharacterapplicationNoIppb !== '8'){
					// 	$applicationNoIppb = generate_NM_memreg($reg_id);
					// }

					$this->db->or_where('regnumber',$reg_id);
					$this->db->or_where('regid',$reg_id);
					$this->db->where('excode','997');
					$user_info=$this->master_model->getRecords('member_registration',array());
					
					if($user_info[0]['regnumber'] !== ''){
						$applicationNoIppb = $user_info[0]['regnumber'];
						}else{
						$applicationNoIppb = generate_NM_memreg($reg_id);
					}


					$log_title ="IPPB nonreg member reg number- updated or new :".$reg_id;
					$log_message = serialize($applicationNoIppb);
					$rId = $reg_id;
					$regNo = $reg_id;
					storedUserActivity($log_title, $log_message, $rId, $regNo);	

					######### payment Transaction ############
					$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNoIppb,'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'B2B');
					$this->db->order_by('id','DESC');
					$this->db->limit(1);	
					$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
					
					$payment_query_update=$this->db->last_query();
					
					$log_title ="IPPBNONreg Paymnet update :".$payment_query_update;
					$log_message = serialize($update_data);
					$rId = $get_user_regnum[0]['ref_id'];
					$regNo = $get_user_regnum[0]['member_regnumber'];
					storedUserActivity($log_title, $log_message, $rId, $regNo);
					######### Exam Invoice Transaction ############
					$update_data = array('transaction_no'=>$transaction_no);
					$this->db->where('receipt_no',$MerchantOrderNo);
					$this->db->order_by('invoice_id','DESC');
					$this->db->limit(1);	
					$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
					########## Update Member Registration#############

					
					// echo 'before update mem_reg'.$reg_id; 
					$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNoIppb);
					$this->db->order_by('regid','DESC');
					$this->db->limit(1);
					// $this->db->or_where('regnumber',$reg_id);
					// $this->db->or_where('regid',$reg_id);
					$firstCharacterreg_id = substr($reg_id, 0, 1);
					
					/* Below if commented by vishal on 6th june 2023 due to regnumber not updating issue
					if($firstCharacterreg_id !== '8'){
						$this->db->where('regid',$reg_id);
					}else{
						$this->db->where('regnumber',$reg_id);
					}*/
					// below where added by vishal on 6th june 2023 due to regnumber not updating issue
					$this->db->or_where('regid',$reg_id);
					$this->db->or_where('regnumber',$reg_id);
					$this->db->where('excode','997');
					$this->master_model->updateRecord('member_registration',$update_mem_data,array());

					##########Update IPPB employee regnumber #############
					$update_mem_data = array('regnumber'=>$applicationNoIppb,'updatedon'=>date('Y-m-d H:i:s'));
					$this->db->limit(1);
					$this->db->or_where('regnumber',$reg_id);
					$this->db->or_where('id',$last_emp_id);
					$this->master_model->updateRecord('employee_data_ippb',$update_mem_data,array());

					##########Update Member Exam#############
					$update_data = array('pay_status' => '1','regnumber'=>$applicationNoIppb);
					$this->db->order_by('id','DESC');
					$this->db->limit(1);
					$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
					
					
					########## Generate Invoice #############
					$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNoIppb),'transaction_no,date,amount,id');
					//get invoice	
					$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
					$invoice_get_query= $this->db->last_query();
					$log_title ="IPPB NONreg invoice get query :".$invoice_get_query;
					$log_message = serialize($getinvoice_number);
					$rId = $get_user_regnum[0]['ref_id'];
					$regNo = $get_user_regnum[0]['member_regnumber'];
					storedUserActivity($log_title, $log_message, $rId, $regNo);	
					if(count($getinvoice_number) > 0)
					{
						$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
						if($invoiceNumber)
						{
							$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
						}
						$update_data = array('invoice_no' => $invoiceNumber,'member_no'=>$applicationNoIppb,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
						/*Add code trans_start & trans_complete :   */
						$this->db->where('pay_txn_id',$payment_info[0]['id']);
						$this->db->order_by('invoice_id','DESC');
						$this->db->limit(1);
						$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
						
						
						$invoice_update_query= $this->db->last_query();
						$log_title ="IPPB NONreg invoice update query :".$invoice_update_query;
						$log_message = serialize($update_data);
						$rId = $get_user_regnum[0]['ref_id'];
						$regNo = $get_user_regnum[0]['member_regnumber'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);	
						
						$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
						$log_title ="IPPB NONreg invoice Img path";
						$log_message = serialize($attachpath);
						$rId = $get_user_regnum[0]['ref_id'];
						$regNo = $get_user_regnum[0]['member_regnumber'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);	
					}
					//Query to get exam details	
					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period','left');
					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
					$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNoIppb),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
					$exam_info_query = $this->db->last_query();
					
					$log_title ="IPPBNONreg admit_card_details :".$exam_info_query;
					$log_message = serialize($exam_info);
					$rId = $get_user_regnum[0]['ref_id'];
					$regNo = $get_user_regnum[0]['member_regnumber'];
					storedUserActivity($log_title, $log_message, $rId, $regNo);
					
					########## Generate Admit card and allocate Seat #############
					if(count($exam_admicard_details) > 0)
					{
						$password=random_password();
						foreach($exam_admicard_details as $row)
						{
							$query='(exam_date = "0000-00-00" OR exam_date = "")';
							$this->db->where($query);
							$this->db->where('session_time=','');
							if($this->session->userdata['csc_venue_flag'] == 'F'){
								$this->db->where('venue_flag','F');
							}elseif($this->session->userdata['csc_venue_flag'] == 'P'){
								$this->db->where('venue_flag','P');
							}
							$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'center_code'=>$row['center_code']));
							
							$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
							
							//echo $this->db->last_query().'<br>';
							$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$admit_card_details[0]['exam_date'],$admit_card_details[0]['time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
				
							if($seat_number!='')
							{
								$final_seat_number = $seat_number;
								$update_data = array('mem_mem_no'=>$applicationNoIppb,'pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
								$this->db->order_by('mem_exam_id','DESC');
								$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
							}
							else
							{
								$admit_card_details=$this->master_model->getRecords('admit_card_details',array('admitcard_id'=>$admit_card_details[0]['admitcard_id'],'remark'=>1));
								if(count($admit_card_details) > 0)
								{
									$log_title ="IPPB NONreg Seat number already allocated id:".$get_user_regnum[0]['member_regnumber'];
									$log_message = serialize($exam_admicard_details);
									$rId = $get_user_regnum[0]['ref_id'];
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);
								}
								else
								{
									$log_title ="IPPB NONreg Fail user seat allocation id:".$applicationNoIppb;
									$log_message = serialize($this->session->userdata['enduserinfo']['subject_arr']);
									$rId = $get_user_regnum[0]['ref_id'];
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);
									//redirect(base_url().'Nonreg/refund/'.base64_encode($MerchantOrderNo));
								}
							}
						}
					}	
					else
					{
						redirect(base_url().'Ippbapplyexam/refund/'.base64_encode($MerchantOrderNo));
					}
					
					##############Get Admit card#############
					$admitcard_pdf=genarate_admitcard($applicationNoIppb,$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
					$log_title ="IPPB NONreg admit cart image path:";
					$log_message = serialize($admitcard_pdf);
					$rId = $get_user_regnum[0]['ref_id'];
					$regNo = $get_user_regnum[0]['member_regnumber'];
					storedUserActivity($log_title, $log_message, $rId, $regNo);
					
				
				
					if($exam_info[0]['exam_mode']=='ON')
					{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
					{$mode='Offline';}
					else{$mode='';}
					//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
					$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
					//Query to get Medium	
					$this->db->where('exam_code',$exam_code);
					$this->db->where('exam_period',$exam_info[0]['exam_period']);
					$this->db->where('medium_code',$exam_info[0]['exam_medium']);
					$this->db->where('medium_delete','0');
					$medium=$this->master_model->getRecords('medium_master','','medium_description');
					//Query to get Payment details	
					
					//Query to get user details
					$this->db->join('state_master','state_master.state_code=member_registration.state');
					// $this->db->or_where('regnumber',$reg_id);
					// $this->db->or_where('regid',$reg_id);
					$firstCharacterreg_id = substr($reg_id, 0, 1);
					// Below if else commented by vishal on 6th june 2023 due to regnumber not updating issue
					// if($firstCharacterreg_id !== '8'){
					// 	$this->db->where('regid',$reg_id);
					// }else{
					// 	$this->db->where('regnumber',$reg_id);
					// }

				
					$this->db->or_where('regnumber',$reg_id);
					$this->db->or_where('regid',$reg_id);
					$result=$this->master_model->getRecords('member_registration',array(),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,usrpassword');	
					
					########get Old image Name############
					$log_title ="IPPB NONreg OLD Image :".$reg_id;
					$log_message = serialize($result);
					$rId = $get_user_regnum[0]['ref_id'];
					$regNo = $get_user_regnum[0]['member_regnumber'];
					storedUserActivity($log_title, $log_message, $rId, $regNo);	
					
					$upd_files = array();
					$photo_file = 'p_'.$applicationNoIppb.'.jpg';
					$sign_file = 's_'.$applicationNoIppb.'.jpg';
					$proof_file = 'pr_'.$applicationNoIppb.'.jpg';
					
					/* if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
					{	$upd_files['scannedphoto'] = $photo_file;	} */
					$chk_photo = update_image_name("./uploads/photograph/", $result[0]['scannedphoto'], $photo_file); //update_image_name_helper.php
					if($chk_photo != "") { $upd_files['scannedphoto'] = $chk_photo; }
					
					/* if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
					{	$upd_files['scannedsignaturephoto'] = $sign_file;	} */
					$chk_sign = update_image_name("./uploads/scansignature/", $result[0]['scannedsignaturephoto'], $sign_file); //update_image_name_helper.php
					if($chk_sign != "") { $upd_files['scannedsignaturephoto'] = $chk_sign; }
					
					/* if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
					{	$upd_files['idproofphoto'] = $proof_file;	} */
					$chk_proof = update_image_name("./uploads/idproof/", $result[0]['idproofphoto'], $proof_file); //update_image_name_helper.php
					if($chk_proof != "") { $upd_files['idproofphoto'] = $chk_proof; }
					
					if(count($upd_files)>0)
					{
						// $this->db->or_where('regnumber',$reg_id);
						// $this->db->or_where('regid',$reg_id);
						// Below if else commented by vishal on 6th june 2023 due to regnumber not updating issue
						$firstCharacterreg_id = substr($reg_id, 0, 1);
						// if($firstCharacterreg_id !== '8'){
						// 	$this->db->where('regid',$reg_id);
						// }else{
						// 	$this->db->where('regnumber',$reg_id);
						// }
						// Below where added by vishal on 6th june 2023 due to regnumber not updating issue
						if ($reg_id!='') {
							//$this->db->where('regid',$reg_id);
							$this->db->or_where('regid',$reg_id);
							$this->db->or_where('regnumber',$reg_id);
							$this->db->where('excode','997');
							$this->master_model->updateRecord('member_registration',$upd_files);
						}
						$log_title ="IPPB NONreg PICS Update :".$reg_id;
						$log_message = serialize($this->db->last_query());
						$rId = $get_user_regnum[0]['ref_id'];
						$regNo = $get_user_regnum[0]['member_regnumber'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);	
					}
					else
					{
							$upd_files['scannedphoto'] = $photo_file;
							$upd_files['scannedsignaturephoto'] = $sign_file;	
							$upd_files['idproofphoto'] = $proof_file;
							// $this->db->or_where('regnumber',$reg_id);
							// $this->db->or_where('regid',$reg_id);
							$firstCharacterreg_id = substr($reg_id, 0, 1);
							// Below if else commented by vishal on 6th june 2023 due to regnumber not updating issue
							// if($firstCharacterreg_id !== '8'){
							// 	$this->db->where('regid',$reg_id);
							// }else{
							// 	$this->db->where('regnumber',$reg_id);
							// }

							// Below where added by vishal on 6th june 2023 due to regnumber not updating issue
							if ($reg_id!='') {
								//$this->db->where('regid',$reg_id);
								$this->db->or_where('regid',$reg_id);
								$this->db->or_where('regnumber',$reg_id);
								$this->db->where('excode','997');
								$this->master_model->updateRecord('member_registration',$upd_files);
							}
							
							$log_title ="IPPB NONreg MANUAL PICS Update :".$reg_id;
							$log_message = serialize($upd_files);
							$rId = $get_user_regnum[0]['ref_id'];
							$regNo = $get_user_regnum[0]['member_regnumber'];
							storedUserActivity($log_title, $log_message, $rId, $regNo);	
					}
			
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);

					include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
					
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
					$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#REG_NUM#", "".$applicationNoIppb."",$newstring1);
					$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
					$newstring4 = str_replace("#EXAM_DATE#", "-",$newstring3);
					$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
					$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
					$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
					$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
					$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
					$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
					$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
					$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
					$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
					$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
					$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
					$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
					$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
					$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
					$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
					$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring19);
					$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
					if(count($elern_msg_string) > 0)
					{
						foreach($elern_msg_string as $row)
						{
							$arr_elern_msg_string[]=$row['exam_code'];
						}
						if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
						{
							$newstring21 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring20);		
						}
						else
						{
							$newstring21 = str_replace("#E-MSG#", '',$newstring20);		
						}
					}
					else
					{
						$newstring21 = str_replace("#E-MSG#", '',$newstring20);
					}
					$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
					$cc_array = array('iibfdevp@esds.co.in','dd.exm1@iibf.org.in');

					// 'shuchi.o@ippbonline.in'
				
					$info_arr=array('to'=>$result[0]['email'],
											'cc'=>$cc_array,
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
										);
					if($attachpath!='')
					{		
						//send sms	
						$files=array($attachpath,$admitcard_pdf);				
						$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
						$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
						$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
						$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
						// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);
						$message = 'Thanks for enrolling for BCBF-Payment Bank-exam. Your exam form and fee '.$payment_info[0]['amount'].' is received vide transaction '.$payment_info[0]['transaction_no'].'. Refer email for details. IIBF Team';
						$res = $this->master_model->send_sms_trustsignal(intval($result[0]['mobile']), $message, 'J0DWe39nR', '997', '', 'IIBFSM');

							
						$mail_flag=$this->Emailsending->mailsend_attch($info_arr,$files);
						if($mail_flag)
						{
							$log_title ="Mail nonreg Flag success :".$mail_flag;
							$log_message = serialize($info_arr);
							$rId = $get_user_regnum[0]['ref_id'];
							$regNo = $get_user_regnum[0]['member_regnumber'];
							storedUserActivity($log_title, $log_message, $rId, $regNo);	
						}
						else
						{
							$log_title ="Mail nonreg Flag fail :".$mail_flag;
							$log_message = serialize($info_arr);
							$rId = $get_user_regnum[0]['ref_id'];
							$regNo = $get_user_regnum[0]['member_regnumber'];
							storedUserActivity($log_title, $log_message, $rId, $regNo);	
						}
					}else{
						
							$log_title ="Attachement nonreg not found email mail";
							$log_message = serialize($attachpath);
							$rId = $get_user_regnum[0]['ref_id'];
							$regNo = $get_user_regnum[0]['member_regnumber'];
							storedUserActivity($log_title, $log_message, $rId, $regNo);	
					}//Manage Log
				
					
			
				}
				else
				{
					$log_title ="IPPB NONreg B2B Update fail:".$get_user_regnum[0]['member_regnumber'];
					$log_message = serialize($update_data);
					$rId = $MerchantOrderNo;
					$regNo = $get_user_regnum[0]['member_regnumber'];
					storedUserActivity($log_title, $log_message, $rId, $regNo);	
				}
				
				redirect(base_url().'Ippbapplyexam/acknowledge/'.base64_encode($MerchantOrderNo));
				exit();
                //-----------------------------------------------------------

			}
			else /* if ($transaction_error_type == 'payment_authorization_error') */ 
			{
				if($auth_status == "0399" && $qry_api_response['auth_status'] == '0399' && $get_user_regnum[0]['status'] == 2){
					
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

					$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id,member_regnumber');

					$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');

					$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');

					$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');

					$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');

					$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');

					$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.regnumber'=>$payment_info[0]['member_regnumber']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');

					//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');

					$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);

					$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);

					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];

					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);

					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));

					$newstring1 = str_replace("#application_num#", "",  $emailerstr[0]['emailer_text']);

					$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);

					$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);

					$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);

					$info_arr=array(	
                                'to'=>$result[0]['email'],

                                'from'=>$emailerstr[0]['from'],

                                'subject'=>$emailerstr[0]['subject'],

                                'message'=>$final_str
                    );

					// send SMS

					$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);

					$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);

					// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	

					$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'Jw6bOIQGg');	

					$this->Emailsending->mailsend($info_arr);

					//--------------------------------------------------------------------------------------------------
					$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
					$this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

					$this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
					redirect(base_url());
					//echo "Transaction failed";
			}
			}
		}
		else
		{
			redirect(base_url('/ippbapplyexam/trans_under_process'));
		}
		// else 
		// {
		//   die("Please try again...");
		//   }

    }

    public function trans_under_process()
    {     
	
		$this->load->view('ippbapplyexam/trans_under_process');
	}

	public function examapplied($regnumber = null, $exam_code = null)
    {
		 //$regnumber = '802161926';
		 //$exam_code = '997';
        //check where exam alredy apply or not
        $cnt        = 0;
        $today_date = date('Y-m-d');
        $this->db->select('member_exam.*');
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
        //$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
        $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
        $this->db->where('exam_master.elg_mem_o', 'Y');
        $this->db->where('pay_status', '1');
        $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => $exam_code, 'regnumber' => $regnumber));
        //echo $this->db->last_query();exit;
        ####check if number applied through the bulk registration (Prafull)###
        if (count($applied_exam_info) <= 0) {
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
            $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
            //$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
            $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
            $this->db->where('exam_master.elg_mem_o', 'Y');
            $this->db->where('bulk_isdelete', '0');
            $this->db->where('institute_id!=', '');
            $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => $exam_code, 'regnumber' => $regnumber));

        }
         //echo $this->db->last_query();
         //echo count($applied_exam_info);exit;

        //exit;
        ###### End of check  number applied through the bulk registration###
        
        // echo  $cnt;
		// if (!$cnt) {
		// 	echo "in";
		// }else{
		// 	echo "error";

		// }
		/*eligible master comdition added by pooja mane on 2023-04-17*/
		$this->db->order_by('id', 'desc');
        $eligible_info = $this->master_model->getRecords('eligible_master', array('exam_code' => $exam_code, 'member_no' => $regnumber),'exam_status');
		
        if($eligible_info[0]['exam_status'] == 'F'){
        	return 0;
        }
        else
        {
        	return count($applied_exam_info);
        }/*eligible master comdition end by pooja mane on 2023-04-17*/

        //return count($applied_exam_info);
    }
	public function already_apply($exam_period_date=null)
	{
		$message          = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>. period. Hence you need not apply for the same.';
        $data=array('middle_content'=>'ippbapplyexam/already_apply','check_eligibility'=>$message);
        $this->load->view('ippbapplyexam/mem_apply_exam_common_view',$data);
	}

	public function amount($exam_period_date=null)
	{
		// echo $amount = getExamFee($this->session->userdata['examinfo']['txtCenterCode'], $this->session->userdata['examinfo']['eprid'], $this->session->userdata['examinfo']['excd'], $this->session->userdata['examinfo']['grp_code'], $this->session->userdata('memtype'),$this->session->userdata['examinfo']['elearning_flag']);
		/*echo "<==Amount==>".$amount= getExamFee(582, 122, 60, 'B1_2', 'O','N'); 

		echo "<==el Amount==>".$el_amount=get_el_ExamFee(582, 122, 60, 'B1_2', 'O','N');

		$el_subject_cnt = 0;
		echo "<==total_elearning_amt==>". $total_elearning_amt = $el_amount * $el_subject_cnt;
		echo "<==final amt==>".$amount = $amount + $total_elearning_amt; */
	}

	######### if seat allocation full show message#######
	public function refund($order_no=NULL)
	{
		//payment detail
		//$this->db->join('member_exam','member_exam.id=payment_transaction.ref_id AND member_exam.exam_code=payment_transaction.exam_code');
		//$this->db->where('member_exam.regnumber',$this->session->userdata('regnumber'));
		$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($order_no)));
		if(count($payment_info) <=0)
		{
			redirect(base_url());
		}
		$this->db->where('remark','2');
		$admit_card_refund=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$payment_info[0]['ref_id']));
		if(count($admit_card_refund) >0)
		{
			$update_data = array('remark' => 3);
			$this->master_model->updateRecord('admit_card_details',$update_data,array('mem_exam_id'=>$payment_info[0]['ref_id']));
		}
		$exam_name=$this->master_model->getRecords('exam_master',array('exam_code'=>$payment_info[0]['exam_code']));
		$data=array('payment_info'=>$payment_info,'exam_name'=>$exam_name);
		$this->load->view('ippbapplyexam/non_mem_reg_refund',$data);
	}

	function genarate_admitcard_prt_test()
	{	
		$member_id = 897090335;
		$exam_code = 997;
		$exam_period = 851;
		try{
			
			//$member_id = 700001459;
			//$exam_code = 42;
			//$exam_period = 217;
			$CI = & get_instance();	
			//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code));
			
			$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code');
			$CI->db->from('admit_card_details');
			$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
			$CI->db->order_by("admitcard_id", "desc");
			$member_record = $CI->db->get();
			$member_result = $member_record->row();
			// echo $this->db->last_query();

			// print_r($member_result); exit;
			if(sizeof($member_result) == 0){
				return '';
				exit;
			}else{
			
				if(isset($member_result->vendor_code) || $member_result->vendor_code!='' ){
					$vcenter = $member_result->vendor_code;
				}elseif(!isset($member_result->vendor_code) || $member_result->vendor_code=='' ){
					$vcenter = '0';
				}
				if($exam_code == $this->config->item('examCodeJaiib')){
					if(isset($member_result->center_code) && $member_result->center_code==306){
						$vcenter = 3;
					}
				}
			
				$medium_code = $member_result->m_1;
				
				$CI->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin,insname,venue_name');
				$CI->db->from('admit_card_details');
				$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
				$CI->db->group_by('venueid');
				$CI->db->order_by("exam_date", "asc");
				$venue_record = $CI->db->get();
				$venue_result = $venue_record->result();
				
				$CI->db->select('description');
				$exam = $CI->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
				$exam_result = $exam->row();
				
				$CI->db->select('subject_description,admit_card_details.exam_date,time,venueid,seat_identification');
				$CI->db->from('admit_card_details');
				$CI->db->join('admit_subject_master', 'admit_subject_master.subject_code = LEFT(admit_card_details.sub_cd,3)');
				$CI->db->where(array('admit_subject_master.exam_code' => $exam_code,'admit_card_details.mem_mem_no'=>$member_id,'subject_delete'=>0,'exm_prd'=>$exam_period));
				$CI->db->where('pwd!=','');
				$CI->db->where('seat_identification!=','');
				$CI->db->where('remark',1);
				$CI->db->order_by("admit_card_details.exam_date", "asc");
				$subject = $CI->db->get();
				$subject_result = $subject->result();
				
				$pdate = $subject->result();
				
				// echo "<pre>".$this->db->last_query(); print_r($data); exit;
				foreach($pdate as $pdate){
					$exdate = date("d-M-y", strtotime($subject_result[0]->exam_date)); ;
					$examdate = explode("-",$exdate);
					$examdatearr[] = $examdate[1];
				}
			
				$exdate = date("d-M-y", strtotime($subject_result[0]->exam_date)); 
				$examdate = explode("-",$exdate);
				$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
				
				
				
				if($medium_code == 'ENGLISH' || $medium_code == 'E'){
					$medium_code_lng = 'E';
				}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
					$medium_code_lng = 'H';
				}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
					$medium_code_lng = 'A';
				}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
					$medium_code_lng = 'G';
				}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
					$medium_code_lng = 'K';
				}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
					$medium_code_lng = 'L';
				}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
					$medium_code_lng = 'M';
				}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
					$medium_code_lng = 'N';

				}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
					$medium_code_lng = 'O';
				}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
					$medium_code_lng = 'S';
				}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
					$medium_code_lng = 'T';
				}
			
				$CI->db->select('medium_description');
				$medium = $CI->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
				$medium_result = $medium->row();
				
				$data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,"venue_result"=>$venue_result,"vcenter"=>$vcenter,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description,'idate'=>$exdate,'exam_period'=>$exam_period);
				//  echo "<pre>".$this->db->last_query(); print_r(get_img_name($member_id,'p')); exit;
				
				if($exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004 || $exam_code == 1005 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 || $exam_code == 1009 || $exam_code == 1010 || $exam_code == 1011 || $exam_code == 1012 || $exam_code == 1013 || $exam_code == 1014 || $exam_code == 2027){
					$html=$CI->load->view('remote_admitcardpdf_attach', $data, true);
				}else{
					$html=$CI->load->view('admitcardpdf_attach', $data, true);
				}
				
				$CI->load->library('m_pdf');
				$pdf = $CI->m_pdf->load();
				$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";
				$pdf->WriteHTML($html);
				$path = $pdf->Output('uploads/admitcardpdf/'.$pdfFilePath, "F"); 
				
				//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
			
			
				$admit_card_details = $CI->db->get_where('admit_card_details', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
				
				$update_data = array('admitcard_image' => $pdfFilePath);
				//foreach($admit_card_details as $admit_card_update){
				foreach($admit_card_details->result_array() as $admit_card_update){
					//$CI->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_update['admitcard_id'],'remark'=>1));
					
					/*$CI->db->where('remark', 1);
					$CI->db->where('admitcard_image', '');
					$CI->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_update['admitcard_id']));*/
					
					
					$CI->db->where('admitcard_id', $admit_card_update['admitcard_id']);
					$CI->db->where('remark', 1);
					$CI->db->where('admitcard_image', '');
					$CI->db->update('admit_card_details',$update_data);	
					
					//$last_update_query_error = $CI->db->_error_message();
					
					$last_update_query = $this->db->last_query();
					
					$log_title ="Admitcard filename updated in for loop. id:".$admit_card_update['admitcard_id'];
					//$log_message = serialize($admit_card_details);
					//$log_message = $last_update_query."|".$last_update_query_error;
					$log_message = $last_update_query;
					$rId = $admit_card_update['admitcard_id'];
					$regNo = $member_id;
					//storedUserActivity($log_title, $log_message, $rId, $regNo);
					$log_data['title'] = $log_title;
					$log_data['description'] = $log_message;
					$log_data['regid'] = $rId;
					$log_data['regnumber'] = $regNo;
					$CI->db->insert('userlogs', $log_data);
					
				}
			
				// code to check if admi card file name updated
				//$admit_card_details_update = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'admitcard_image' => ''));
				
				$admit_card_details_update = $CI->db->get_where('admit_card_details', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'admitcard_image' => ''));
			
			
				if(count($admit_card_details_update->result_array()) > 0)
				{
					/*$CI->db->where('exm_cd', $exam_code);
					$CI->db->where('exm_prd', $exam_period);
					$CI->db->where('remark', 1);
					$CI->master_model->updateRecord('admit_card_details',$update_data,array('mem_mem_no'=>$member_id));*/
					
					
					$CI->db->where('mem_mem_no', $member_id);
					$CI->db->where('remark', 1);
					$CI->db->where('admitcard_image', '');
					$CI->db->where('exm_cd', $exam_code);
					$CI->db->where('exm_prd', $exam_period);
					$CI->db->update('admit_card_details',$update_data);	
					
					
					$log_title ="Admitcard filename updated in 2nd update. Member id:".$member_id;
					$log_message = serialize($admit_card_details_update->result_array());
					$rId = $member_id;
					$regNo = $member_id;
					//storedUserActivity($log_title, $log_message, $rId, $regNo);
					$log_data['title'] = $log_title;
					$log_data['description'] = $log_message;
					$log_data['regid'] = $rId;
					$log_data['regnumber'] = $regNo;
					$CI->db->insert('userlogs', $log_data);
				}
				// eof code to check if admi card file name updated
				
				echo 'uploads/admitcardpdf/'.$pdfFilePath;
			
			}
			
		}catch(Exception $e){
			echo $e->getMessage();
		}
	
	}

	public function get_alredy_applied_examname($regnumber=NULL,$exam_code=NULL)
	{
		$flag=0;
		$msg='';
		$today_date=date('Y-m-d');
		$this->db->select('subject_master.*,exam_master.description');
		$this->db->join('exam_master','exam_master.exam_code=subject_master.exam_code');
		$applied_exam_date=$this->master_model->getRecords('subject_master',array('subject_master.exam_code'=>base64_decode($exam_code),'exam_date >='=>$today_date,'subject_delete'=>'0'));
		if(count($applied_exam_date) > 0)
		{
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
			$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
			$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'pay_status'=>'1'),'member_exam.exam_code,exam_master.description,exam_activation_master.exam_period');
			$month = date('Y')."-".substr($getapplied_exam_code[0]['exam_period'],4);
			$exam_period_date=date('F',strtotime($month))."-".substr($getapplied_exam_code[0]['exam_period'],0,-2);
			### checking bulk applied ######
			if(count($getapplied_exam_code) <=0)
			{
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
				$this->db->where('bulk_isdelete','0');
				$this->db->where('institute_id!=','');
				$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'pay_status'=>'2'),'member_exam.exam_code,exam_master.description');
			}
			if(count($getapplied_exam_code) >0)
			{
				foreach($getapplied_exam_code as $exist_ex_code)
				{	
					$getapplied_exam_date=$this->master_model->getRecords('subject_master',array('exam_code'=>$exist_ex_code['exam_code'],'exam_date >='=>$today_date,'subject_delete'=>'0'));
					if(count($getapplied_exam_date) > 0)
					{
						foreach($getapplied_exam_date as $exist_ex_date)
						{
							foreach($applied_exam_date as $sel_ex_date)
							{
									if($sel_ex_date['exam_date']==$exist_ex_date['exam_date'])
									{
										//$msg='5Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same.';
										$msg='You have already applied for the exam, please wait till the result is declared. period. Hence you need not apply for the same.';
										$flag=1;
										break;
									}
								}
								if($flag==1)
								{
										//$msg='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>. period. Hence you need not apply for the same.';
										$msg='You have already applied for the exam, please wait till the result is declared. Hence you need not apply for the same.';
									break;
								}
							}
						}
					}
				}
			}
		return $msg;
	}
	//check whether applied exam date fall in same date of other exam date(Prafull)
	public function examdate($cscnmregnumber=NULL,$exam_code=NULL)
	{
		$flag=0;
		$today_date=date('Y-m-d');
		$applied_exam_date=$this->master_model->getRecords('subject_master',array('exam_code'=>$exam_code,'exam_date >='=>$today_date,'subject_delete'=>'0'));
		if(count($applied_exam_date) > 0)
		{
			$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
			$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$cscnmregnumber,'pay_status'=>'1'),'member_exam.exam_code');
			### checking bulk applied ######
			if(count($getapplied_exam_code) <=0)
			{
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
				$this->db->where('bulk_isdelete','0');
				$this->db->where('institute_id!=','');
				$getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$cscnmregnumber,'pay_status'=>'2'),'member_exam.exam_code');
			}
			if(count($getapplied_exam_code) >0)
			{
				foreach($getapplied_exam_code as $exist_ex_code)
				{	
					$getapplied_exam_date=$this->master_model->getRecords('subject_master',array('exam_code'=>$exist_ex_code['exam_code'],'exam_date >='=>$today_date,'subject_delete'=>'0'));
					if(count($getapplied_exam_date) > 0)
					{
						foreach($getapplied_exam_date as $exist_ex_date)
						{
							foreach($applied_exam_date as $sel_ex_date)
							{
									if($sel_ex_date['exam_date']==$exist_ex_date['exam_date'])
									{
										$flag=1;
										break;
									}
								}
								if($flag==1)
								{
									break;
								}
							}
						}
					}
				}
		}
		return $flag;
	}

	public function generate_regnumber_ippb(){
		$reg_id=3134821;
		echo $new_regnumber= generate_NM_memreg($reg_id); //exit

		// update member_regi table by reg_id

		$update_mem_data = array('regnumber'=>$new_regnumber);
		$this->db->order_by('regid','DESC');
		$this->db->limit(1);
		$this->db->where('regid',$reg_id);
		$this->db->where('isactive','1');
		$this->db->where('excode','997');
		$this->master_model->updateRecord('member_registration',$update_mem_data,array());
		echo "==member_registration updated==";
		// update payment transacton

		$update_data = array('member_regnumber'=>$new_regnumber);
		$this->db->order_by('id','DESC');
		$this->db->limit(1);	
		$this->db->where('status','1');
		$this->db->where('exam_code','997');
		$this->master_model->updateRecord('payment_transaction',$update_data,array('member_regnumber'=>$reg_id));
		echo "==payment_transaction updated==";

		##########Update IPPB employee regnumber #############
		$update_mem_data = array('regnumber'=>$new_regnumber,'updatedon'=>date('Y-m-d H:i:s'));
		$this->db->limit(1);
		$this->db->where('regnumber',$reg_id);
		$this->master_model->updateRecord('employee_data_ippb',$update_mem_data,array());
		echo "==employee_data_ippb updated==";

		##########Update Member Exam#############
		$update_data = array('pay_status' => '1','regnumber'=>$new_regnumber);
		$this->db->order_by('id','DESC');
		$this->db->limit(1);
		$this->db->where('pay_status','1');
		$this->db->where('exam_code','997');
		$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
		echo "==member_exam updated==";

		//update exam invoice
		$update_data = array('member_no'=>$new_regnumber,'modified_on'=>date('Y-m-d H:i:s'));
		$this->db->order_by('invoice_id','DESC');
		$this->db->limit(1);
		$this->db->where('exam_code','997');
		$this->master_model->updateRecord('exam_invoice',$update_data,array('member_no'=>$reg_id));
		echo "==exam_invoice updated==";

		//update admit card table
		$final_seat_number = $seat_number;
		$update_data = array('mem_mem_no'=>$new_regnumber,'modified_on'=>date('Y-m-d H:i:s'));
		$this->db->where('exm_cd','997');
		$this->db->order_by('mem_exam_id','DESC');
		$this->master_model->updateRecord('admit_card_details',$update_data,array('mem_mem_no'=>$reg_id));
		echo "==admit_card_details updated==";
		// echo $this->db->last_query();//exit;

	}

	public function ippb_custom_admitcard_pdf_single(){     
		//echo 'hi';exit;
		$exam_code = 997;     
		$exam_period = 851;    
		$member_array = array(802042621,802042625,802042627); 
		$arr_size = sizeof($member_array);  
		for($i=0;$i<=$arr_size;$i++){
			echo $path = genarate_admitcard_custom_new($member_array[$i],$exam_code,$exam_period);  
			echo "<br/>"; 
		}
	}

	public function custom_admitcardpdf_send_mail(){     //     
		$member_array = array(5773596,5774166,5773386,5774189,5774141);                   
		//$member_array = array(510324238);    
		//$date_array = array('2019-03-23');  
		//$center_array = array(306);      
		$this->db->distinct('mem_mem_no');    
		$this->db->where('remark',1);
		$this->db->where('exm_cd',$this->config->item('examCodCAIIB'));
		$this->db->where('exm_prd','121');
		//$this->db->where('free_paid_flg','F'); 
		//$this->db->where('record_source','Bulk');
		$this->db->where('admitcard_image !=','');
		//$this->db->where_in('center_code',$center_array);
		//$this->db->where_in('exam_date',$date_array);
		$this->db->where_in('mem_exam_id',$member_array);
		$sql = $this->master_model->getRecords('admit_card_details','','mem_mem_no,admitcard_image,exm_cd'); 
		    
		foreach($sql as $rec){ 
			
			$this->db->where('exam_code',$rec['exm_cd']);
			$exam_name = $this->master_model->getRecords('exam_master','','description');
			
			$final_str = 'Hello Sir/Madam <br/><br/>';
			$final_str.= 'Please check your  admit card letter for '.$exam_name[0]['description'].' examination';   
			$final_str.= '<br/><br/>';
			$final_str.= 'Regards,';
			$final_str.= '<br/>';
			$final_str.= 'IIBF TEAM'; 
			  
			$attachpath = "uploads/admitcardpdf/".$rec['admitcard_image'];  
			//$attachpath = "uploads/IIBF_ADMIT_CARD_510360428.pdf";   
			$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['mem_mem_no'],'isactive'=>'1'),'email,mobile');   
			$info_arr=array('to'=>$email[0]['email'], 
							//'to'=>'raajpardeshi@gmail.com',
							'from'=>'noreply@iibf.org.in',
							'subject'=>'Revised Admit Letter',
							'message'=>$final_str
						); 
			$files=array($attachpath);
			if($this->Emailsending->mailsend_attch($info_arr,$files)){
				echo "Mail send tooooo ==> ".$rec['mem_mem_no'];
				echo "<br/>";  
			}
			
			
			
			
			/*$text='Due to issue in seat number allotment, revised admit letter is sent to your registered mail ID. You can also download it from your login/profile.'; 
			
			$url ="http://www.hindit.co.in/API/pushsms.aspx?loginID=T1IIBF&password=supp0rt123&mobile=".$email[0]['mobile']."&text=".urlencode($text)."&senderid=IIBFNM&route_id=2 &Unicode=1";
			$string = preg_replace('/\s+/', '', $url);
			$x = curl_init($string);
			curl_setopt($x, CURLOPT_HEADER, 0);	
			curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);			
			$reply = curl_exec($x);
			curl_close($x);*/
			
			
		}
	}

	public function testippb(){



		$admitcard_pdf='https://iibf.esdsconnect.com/uploads/admitcardpdf/997_851_802248245.pdf';

		$files=array($admitcard_pdf);		
		$cc_array = array('vishal.phadol@esds.co.in','vishal.phadol@esds.com');
		$info_arr=array('to'=>'vishal.phadol@esds.co.in',
											'cc'=>$cc_array,
											'from'=>'logs@iibf.esdsconnect.com',
											'subject'=>'test',
											'message'=>'Test'
										);


		$mail_flag=$this->Emailsending->mailsend_attch($info_arr,$files);
		print_r($mail_flag);
		die;

		$message = 'Thanks for enrolling for BCBF-Payment Bank-exam. Your exam form and fee 500 is received vide transaction 123456. Refer email for details. IIBF Team';
		$res = $this->master_model->send_sms_trustsignal(7588553132, $message, 'J0DWe39nR', '997', '', 'IIBFSM');
		die;

		$reg_id='3129170';
		$firstCharacterreg_id = substr($reg_id, 0, 1);
		if($firstCharacterreg_id !== '8'){
			$this->db->where('regid',$reg_id);
		}else{
			$this->db->where('regnumber',$reg_id);
		}
		// $multiClause = array('regnumber' => $reg_id, 'regnumber' => '80');
			// $this->db->or_where($multiClause);
			// $this->db->or_where('regnumber',$reg_id);
			// $this->db->or_where('regid',$reg_id);
			$user_info=$this->master_model->getRecords('member_registration',array());

			echo $this->db->last_query()."<pre>";  print_r($user_info); exit;
	}

	public function bulk_mail($value='')
	{
		//exit();
		$this->db->join('payment_transaction','payment_transaction.ref_id=admit_card_details.mem_exam_id');
		$this->db->where('status','1');
		$candidates = $this->master_model->getRecords('admit_card_details',array('exm_cd'=>997,'remark'=>'1','exam_date >= '=> '2023-04-29'),'admitcard_image,mem_mem_no'); 
		//echo $this->db->last_query();
		//echo count($candidates);die;

		foreach ($candidates as $key => $candidate) {
						$email = 		$candidates = $this->master_model->getRecords('member_registration',array('regnumber'=>$candidate['mem_mem_no']),'email');
						$email_id =  $email[0]['email'];
						$admitcard_pdf='https://iibf.esdsconnect.com/uploads/admitcardpdf/'.$candidate['admitcard_image'];
						// echo $admitcard_pdf;
						$files=array($admitcard_pdf);		
						// $cc_array = array('vishal.phadol@esds.co.in','vishal.phadol@esds.com');
						$cc_array = array('iibfdevp@esds.co.in','dd.exm1@iibf.org.in','shuchi.o@ippbonline.in');
						$info_arr=array(
							'to'=>$email_id,
						'cc'=>$cc_array,
						'from'=>'logs@iibf.esdsconnect.com',
						'subject'=>'IPPB exam admit card',
						'message'=>'Dear Candidate , <br><br> Please find attached admit card for your IPPB examination registration.<br><br>

							<strong style="color:red">	Note - If you have earlier received the same admit letter via email, please ignore this email.<br>'
						);

						$mail_flag=$this->Emailsending->mailsend_attch($info_arr,$files);
						echo $key." >> ".$email_id."<br>";
						
			}
						
	}

}