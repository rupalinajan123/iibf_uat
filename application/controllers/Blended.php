<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class Blended extends CI_Controller
{
	
    public function __construct()
    { //exit;
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->helper('general_helper');
        $this->load->helper('blended_invoice_helper');
        $this->load->model('Master_model');
        $this->load->library('email');
        $this->load->helper('date');
        $this->load->library('email');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
		$this->load->model('billdesk_pg_model');
    }
	/* Showing Blended Form */
	public function index()
    {
        //exit;
		/* Get Programs */
		$current_date  = date("Y-m-d H:i:s");
        $this->db->join('blended_program_activation_master', 'blended_program_activation_master.program_code=blended_program_master.program_code','left');
        $this->db->where('program_reg_from_date <=', $current_date);
        $this->db->where('program_reg_to_date >=', $current_date);
        $this->db->where('blended_program_master.isdeleted', 0);
        $this->db->group_by('blended_program_master.program_code');
        $programs = $this->master_model->getRecords('blended_program_master');
	//	echo $this->db->last_query();exit;
		if(empty($programs))
		{
			// echo $this->db->last_query();
			$data  = array('middle_content' => 'blended/blended_close');
       	    $this->load->view('blended/blended_common_view', $data);
		}
		else
		{
			$flag       = 1;
			$var_errors = '';
			$valcookie  = register_get_cookie();
			if ($valcookie) {
				$regid     = $valcookie;
				$checkuser = $this->master_model->getRecords('member_registration', array('regid' => $regid,'regnumber !=' => '','isactive !=' => '0'));
				if (count($checkuser) > 0) {delete_cookie('regid');} 
				else {
					$checkpayment=$this->master_model->getRecords('payment_transaction',array('ref_id'=>$regid,'status'=>'2'));
					if (count($checkpayment) > 0) {
						$endTime      = date("Y-m-d H:i:s", strtotime("+20 minutes", strtotime($checkpayment[0]['date'])));
						$current_time = date("Y-m-d H:i:s");
						if (strtotime($current_time) <= strtotime($endTime)) {
							$flag = 0;
						} else {
							delete_cookie('regid');
						}
					} else {
						$flag = 1;
						delete_cookie('regid');
					}
				}
			}
			
			if ($this->session->userdata('enduserinfo')) {
				$this->session->unset_userdata('enduserinfo');
			}
			$row = array();
			$selectedMemberId = '';
			if (isset($_POST['btnGetDetails'])) 
			{
					$config = array(
			array(
					'field' => 'regnumber',
					'label' => 'Registration/Membership No.',
					'rules' => 'trim|required'
			),
			array(
					'field' => 'code2',
					'label' => 'Code',
					'rules' => 'trim|required|callback_check_captcha_userreg',
			),
		);
		$this->form_validation->set_rules($config);
			$dataarr=array(
				'regnumber'=> mysqli_real_escape_string($this->db->conn_id,$this->security->xss_clean($this->input->post('regnumber'))),
				'isactive'=>'1',
				'isdeleted'=>'0'
			);
			$request_cnt = $_POST['request_cnt'] + 1;
			$selectedMemberId = ltrim(rtrim($_POST['regnumber']));
			
			if($this->form_validation->run()==TRUE)
				{
				
					if ($selectedMemberId != '')  /* Check User Eligiblity */
					{
						$row = $this->validateMember($selectedMemberId);
					}
				}				
				/* else 
				{ 
					$row = array("msg" => "The Membership No OR captcha is Invalid.");
				} */
			} 
			else 
			{
				$password = $var_errors = '';
				$data['validation_errors'] = '';
				/* Check Server-Side Validations */
				if (isset($_POST['btnSubmit'])) 
				{
					$scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputidproof1 = $state = '';
					
					if($_POST['training_type'] != "PC"){
						$_POST['center'] = '306';
					}

					$this->form_validation->set_rules('regnumber', 'Membership No.', 'trim|required|xss_clean');
					$this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
				//$this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
				$this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|xss_clean');
					$this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required|xss_clean|callback_address1[Addressline1]');
					$this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
					$this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
					$this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
					if ($this->input->post('state') != '') {$state = $this->input->post('state');}
					$this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');
					$this->form_validation->set_rules('dob1', 'Date of Birth', 'trim|required|xss_clean');
					$this->form_validation->set_rules('optedu', 'Qualification', 'trim|required|xss_clean');
					if (isset($_POST['middlename'])) {
						$this->form_validation->set_rules('middlename', 'Middle Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
					}
					if (isset($_POST['lastname'])) {
						$this->form_validation->set_rules('lastname', 'Last Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
					}
					if (isset($_POST['optedu']) && $_POST['optedu'] == 'U') {
						$this->form_validation->set_rules('eduqual1', 'Please specify', 'trim|required|xss_clean');
					} else if (isset($_POST['optedu']) && $_POST['optedu'] == 'G') {
						$this->form_validation->set_rules('eduqual2', 'Please specify', 'trim|required|xss_clean');
					} else if (isset($_POST['optedu']) && $_POST['optedu'] == 'P') {
						$this->form_validation->set_rules('eduqual3', 'Please specify', 'trim|required|xss_clean');
					}
					if (isset($_POST['addressline2']) && $_POST['addressline2'] != '') {
						$this->form_validation->set_rules('addressline2', 'Addressline2', 'trim|max_length[30]|required|callback_address1[Addressline2]|xss_clean');
					}
					if (isset($_POST['addressline3']) && $_POST['addressline3'] != '') {
						$this->form_validation->set_rules('addressline3', 'Addressline3', 'trim|max_length[30]|required|callback_address1[Addressline3]|xss_clean');
					}
					if (isset($_POST['addressline4']) && $_POST['addressline4'] != '') {
						$this->form_validation->set_rules('addressline4', 'Addressline4', 'trim|max_length[30]|required|callback_address1[Addressline4]|xss_clean');
					}
					$this->form_validation->set_rules('stdcode', 'STD Code', 'trim|max_length[4]|required|numeric|xss_clean');
					$this->form_validation->set_rules('phone', ' Phone No', 'trim|required|numeric|xss_clean');
					//$this->form_validation->set_rules('res_stdcode', 'Residential STD Code', 'trim|max_length[4]|required|numeric|xss_clean');
					//$this->form_validation->set_rules('residential_phone', 'Residential Phone No', 'trim|required|numeric|xss_clean');
					
					
					if($_POST['registrationtype'] != "NM")
					{
						$this->form_validation->set_rules('institutionworking', 'Bank/Institution working', 'trim|required|alpha_numeric_spaces|xss_clean');
						$this->form_validation->set_rules('designation', 'Designation', 'trim|required|xss_clean');
					}
					 //$this->form_validation->set_rules('gstin_no', 'Bank GSTIN Number', 'trim|alpha_numeric|min_length[15]|xss_clean');
					$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
					$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
					$this->form_validation->set_rules('emergency_name', 'Name Of Contact Person', 'trim|required|alpha_numeric_spaces|xss_clean');
					$this->form_validation->set_rules('emergency_contact_no', 'Contact Person Mobile No', 'trim|required|numeric|xss_clean');
					$this->form_validation->set_rules('blood_group', 'Blood Group', 'trim|required|xss_clean');
					$this->form_validation->set_rules('training_type', 'Training Type', 'trim|required|xss_clean');
					$this->form_validation->set_rules('program', 'Course', 'trim|required|xss_clean');
					
					if($_POST['training_type'] != "VC")
					{
						$this->form_validation->set_rules('center', 'Center', 'trim|required|xss_clean');
						$this->form_validation->set_rules('venue_code', 'Venue', 'trim|required|xss_clean');
					}
					
					$this->form_validation->set_rules('training_date', 'Training Date', 'trim|required|xss_clean');
					$this->form_validation->set_rules('code', 'Security Code ', 'trim|required|xss_clean|callback_check_captcha_userreg');
					
					if ($this->form_validation->run() == TRUE) 
					{
						$resp = array( 'success' => 0, 'error' => 0,'msg' => '');
						$this->session->unset_userdata('enduserinfo');
						$eduqual1 = $eduqual2 = $eduqual3 = '';
						if ($_POST['optedu'] == 'U') { $eduqual1 = $_POST["eduqual1"]; } 
						else if ($_POST['optedu'] == 'G') { $eduqual2 = $_POST["eduqual2"];} 
						else if ($_POST['optedu'] == 'P') { $eduqual3 = $_POST["eduqual3"];}
						$date         = date('Y-m-d h:i:s');
						$dob1         = $_POST["dob1"];
						$dob          = str_replace('/', '-', $dob1);
						$dateOfBirth  = date('Y-m-d', strtotime($dob));
						
						$training_date = $_POST['training_date'];
						$batch_code = $_POST['batch_code'];
						
						/* Get Program Name */
						$programArr = $centerArr = $venueArr = array();
						$programQry = $this->db->query("SELECT program_name FROM blended_program_master WHERE program_code = '" . $_POST['program'] . "' AND isdeleted = 0 LIMIT 1 ");
						$programArr   = $programQry->row_array();
						$program_name = $programArr['program_name'];
						
						/* Get Center Name */
						$centerQry    = $this->db->query("SELECT center_name FROM offline_center_master WHERE center_code = '" . $_POST['center'] . "' AND center_delete = 0 LIMIT 1 ");
						$centerArr    = $centerQry->row_array();
						$center_name  = $centerArr['center_name'];
						
						/* Get Venue Details */
						$venueQry     = $this->db->query("SELECT venue_code,venue_name,batch_code,start_date,end_date FROM blended_venue_master WHERE  program_code='" . $_POST['program'] . "' AND center_code='" . $_POST['center'] . "' AND training_type='" . $_POST['training_type'] ."' AND  batch_code='" . $batch_code ."' AND isdeleted = 0 LIMIT 1");
						$venueArr     = $venueQry->row_array();
						$venue_name   = $venueArr['venue_name'];
						$start_date   = date("d-M-Y", strtotime($venueArr['start_date']));
						$end_date     = date("d-M-Y", strtotime($venueArr['end_date']));
						
						/* Get Attempt (0/1) From Eligible Master */
						$training_type = $_POST['training_type'];
						$attemptQry=$this->db->query("SELECT attempt,fee_flag FROM blended_eligible_master WHERE member_number='".$_POST['regnumber']."'  AND program_code = '" . $_POST['program'] . "' LIMIT 1 "); 
						$attemptArr = $attemptQry->row_array();
						$attempt   = $attemptArr['attempt'];
						$fee_flag=$attemptArr['fee_flag'];
						/* Check Count of Vitual Attempts */
						$VitualAttemptsCount = "";
						$VitualAttemptsCount = getVitualAttemptsCounts($_POST["regnumber"],$_POST['program'],$batch_code);
						if($VitualAttemptsCount != 0)
						{
							$attempt = 1;
						}/*elseif($VitualAttemptsCount == 0 && $fee_flag==1)
						{
							$attempt = 2;
						}*/
						
						/* Get Fees From Fee Master */
						$this->db->where('fee_delete', 0);
						$this->db->where('program_code', $_POST['program']);
						$this->db->where('batch_code', $batch_code);
						$this->db->where('training_type', $training_type);
						$this->db->where('attempt', $attempt); /* (0/1) */
						$FeesArr = $this->master_model->getRecords('blended_fee_master', '', 'fee_amount');
						foreach ($FeesArr as $Fkey => $FValue) 
						{
							$fees = $FValue['fee_amount'];
							if($fees != '0'){
								$fees = $FValue['fee_amount'].' + GST as applicable';
							}
							else{
								$fees;
							}
						} 
						/* Add Form Fields Value in the Session */
						if ($_POST["firstname"] != '') 
						{
							$user_data = array(
								'firstname' => $_POST["firstname"],
								'sel_namesub' => $_POST["sel_namesub"],
								'addressline1' => $_POST["addressline1"],
								'addressline2' => $_POST["addressline2"],
								'addressline3' => $_POST["addressline3"],
								'addressline4' => $_POST["addressline4"],
								'city' => $_POST["city"],
								'code' => trim($_POST["code"]),
								'designation' => $_POST["designation"],
								'district' => substr($_POST["district"], 0, 30),
								'dob' => $dateOfBirth,
								'eduqual1' => $eduqual1,
								'eduqual2' => $eduqual2,
								'eduqual3' => $eduqual3,
								'email' => $_POST["email"],
								'institution' => trim($_POST["institutionworking"]),
								'lastname' => $_POST["lastname"],
								'middlename' => $_POST["middlename"],
								'mobile' => $_POST["mobile"],
								'optedu' => $_POST["optedu"],
								'pincode' => $_POST["pincode"],
								'res_stdcode' => $_POST["res_stdcode"],
								'residential_phone' => $_POST["residential_phone"],
								'state' => $_POST["state"],
								'stdcode' => $_POST["stdcode"],
								'phone' => $_POST["phone"],
								'res_stdcode' => $_POST["res_stdcode"],
								'residential_phone' => $_POST["residential_phone"],
								'regnumber' => $_POST["regnumber"],
								'emergency_name' => $_POST['emergency_name'],
								'emergency_contact_no' => $_POST['emergency_contact_no'],
								'blood_group' => $_POST['blood_group'],
								'program' => $_POST['program'],
								'program_name' => $program_name,
								'training_type' => $_POST['training_type'],
								'fees' => $fees,
								'center' => $_POST['center'],
								'center_name' => $center_name,
								//'venue_code' => $_POST['venue_code'],
								'batch_code' => $batch_code,
								'venue_name' => $venue_name,
								'start_date' => $start_date,
								'end_date' => $end_date,
								'training_date' => $training_date,
                               // 'gstin_no' => $_POST['gstin_no']
							);
							
							/* Stored User Details In The Session */
							$this->session->set_userdata('enduserinfo', $user_data);
							$this->form_validation->set_message('error', "");
							
							/* User Log Activities  */
							$log_title ="Blended Course Registration-Preview Page";
							$log_message = serialize($user_data);
							$rId = '';
							$regNo = $_POST["regnumber"];
							storedUserActivity($log_title, $log_message, $rId, $regNo);
							redirect(base_url() . 'blended/preview'); /* Sent to Preview Page */
						} 
						else 
						{
							$var_errors = str_replace("<p>", "<span>", $var_errors);
							$var_errors = str_replace("</p>", "</span><br>", $var_errors);
						}
					}
				}
			}
			$undergraduate = $this->master_model->getRecords('qualification', array('type' => 'UG'));
			$graduate      = $this->master_model->getRecords('qualification', array('type' => 'GR'));
			$postgraduate  = $this->master_model->getRecords('qualification', array('type' => 'PG'));
			$this->db->where('institution_master.institution_delete', '0');
			$institution_master = $this->master_model->getRecords('institution_master', '', '', array('name' => 'asc'));
			$this->db->where('state_master.state_delete', '0');
			$states = $this->master_model->getRecords('state_master');
			$this->db->where('designation_master.designation_delete', '0');
			$designation = $this->master_model->getRecords('designation_master');
			
			/* Get Programs */
			$current_date  = date("Y-m-d H:i:s");
			$programs = array();
			$this->db->join('blended_program_activation_master', 'blended_program_activation_master.program_code=blended_program_master.program_code','left');
			$this->db->where('program_reg_from_date <=', $current_date);
			$this->db->where('program_reg_to_date >=', $current_date);
			$this->db->where('blended_program_master.isdeleted', 0);
			$this->db->where_not_in('blended_program_master.program_code',array('ITC','CBC','CBT'));
			$this->db->group_by('blended_program_master.program_code');
			$programs = $this->master_model->getRecords('blended_program_master');
			
			$this->db->where('offline_center_master.center_delete', 0);
			$centers = $this->master_model->getRecords('offline_center_master');
			
			/* $this->load->helper('captcha');
			$this->session->set_userdata("regcaptcha", rand(1, 100000));
			$vals                   = array(
				'img_path' => './uploads/applications/',
				'img_url' => base_url() . 'uploads/applications/'
			);
			$cap                    = create_captcha($vals);
			$_SESSION["regcaptcha"] = $cap['word'];  */
			
			$this->load->model('Captcha_model');
			$captcha_image = $this->Captcha_model->generate_captcha_img('regcaptcha');
			
			if ($flag == 0) {
				$data = array(
					'middle_content' => 'cookie_msg'
				);
				$this->load->view('blended/blended_common_view', $data);
			} else {
				$data = array(
					'middle_content' => 'blended/blended',
					'states' => $states,
					'undergraduate' => $undergraduate,
					'graduate' => $graduate,
					'postgraduate' => $postgraduate,
					'institution_master' => $institution_master,
					'designation' => $designation,
					'image' => $captcha_image,
					'var_errors' => $var_errors,
					'programs' => $programs,
					'centers' => $centers,
					'row' => $row
				);
				$this->load->view('blended/blended_common_view', $data);
			}
		  }
    }
	
	/* Validate Member Function */
	function validateMember($selectedMemberId)
	{
		$row = '';
		$validateQry = $this->db->query("SELECT member_number,training_type FROM blended_eligible_master WHERE member_number = '" . $selectedMemberId . "' AND  training_type != 'VP' LIMIT 1 "); 
		$validateMemberNo = $validateQry->row_array();
		$training_type = $validateMemberNo['training_type'];
		if (empty($validateMemberNo)) 
		{
			$row = array("msg" => "You are not eligible member to apply blended course..!");
		}
			if($training_type == 'VP')
			{
					//$program_name = $programs['program_name'];
					$msg_link = base_url('BlendedTraining');
					$this->session->set_flashdata('flsh_msg', 'You cannot apply for Training from this link..! <br>For applying kindly click on <a href= "'.$msg_link.'">'.$msg_link.'</a>');
					redirect(base_url() . 'blendedTraining'); 
					}
		else 
		{
			$blendedQry = $this->db->query("SELECT * FROM member_registration WHERE regnumber = '" . $selectedMemberId . "' AND isactive = '1' LIMIT 1 "); 
			$row        = $blendedQry->row_array();
			if (empty($row)) 
			{
				//$row = array("msg" => "Please Enter Valid Membership No.");
			}
		}
		return $row;
	}
	
	/* Form Preview */
	public function preview()
    {
        if (!$this->session->userdata('enduserinfo')) {
            redirect(base_url());
        }
        $selectedMemberId = $this->session->userdata['enduserinfo']['regnumber'];
		if ($selectedMemberId != '') {
            $blendedQry = $this->db->query("SELECT * FROM member_registration WHERE regnumber = '" . $selectedMemberId . "' AND isactive = '1' LIMIT 1 ");
            $row        = $blendedQry->row_array();
        }
        $undergraduate      = $this->master_model->getRecords('qualification', array('type' => 'UG'));
        $graduate           = $this->master_model->getRecords('qualification', array('type' => 'GR'));
        $postgraduate       = $this->master_model->getRecords('qualification', array('type' => 'PG'));
        $institution_master = $this->master_model->getRecords('institution_master');
        $states             = $this->master_model->getRecords('state_master');
        $designation        = $this->master_model->getRecords('designation_master');
		
		
		/*$data = array('middle_content' => 'blended/blended_preview','states' => $states,'undergraduate' => $undergraduate, 'graduate' => $graduate,'postgraduate' => $postgraduate,'institution_master' => $institution_master,'designation' => $designation,'row' => $row);
        $this->load->view('blended/blended_common_view', $data);*/
		
		
		/* Check Member Selected Program Validations */
		$program_code = $this->session->userdata['enduserinfo']['program'];
		//$batch_code = $this->session->userdata['enduserinfo']['batch_code']; AND batch_code='".$batch_code."'
		$validateQry = $this->db->query("SELECT member_number FROM blended_eligible_master WHERE member_number='".$selectedMemberId."' AND program_code='".$program_code."' LIMIT 1"); 
		$validateMemberNo = $validateQry->row_array();
		if(!empty($validateMemberNo))
		{
			$data = array('middle_content' => 'blended/blended_preview','states' => $states,'undergraduate' => $undergraduate, 'graduate' => $graduate,'postgraduate' => $postgraduate,'institution_master' => $institution_master,'designation' => $designation,'row' => $row);
        	$this->load->view('blended/blended_common_view', $data);
		}
		else
		{
			$this->session->set_flashdata('flsh_msg', 'You are not eligible to apply for '.$this->session->userdata['enduserinfo']['program_name'].' ..!');
			redirect(base_url() . 'blended'); 
		}
		
		
		
    }
	
	/* Member Details Stored In The Database */
    public function addmember()
    {
        if (!$this->session->userdata['enduserinfo']) {
            redirect(base_url());
        }
		$selectedMemberId = $this->session->userdata['enduserinfo']['regnumber'];
        if ($selectedMemberId != '') {
            $blendedQry = $this->db->query("SELECT * FROM member_registration WHERE regnumber = '" . $selectedMemberId . "' AND isactive = '1' LIMIT 1 ");
            $row        = $blendedQry->row_array();
        }
		
		$address1 = $address2 = $address3 = $address4 = '';
		if (isset($row['address1'])){ $address1 = $row['address1']; }
		if (isset($row['address2'])){ $address2 = $row['address2']; }
		if (isset($row['address3'])){ $address3 = $row['address3']; }
		if (isset($row['address4'])){ $address4 = $row['address4']; }
		
		$address1 = preg_replace('/[^A-Za-z0-9\. -]/', '', $address1);
		$address2 = preg_replace('/[^A-Za-z0-9\. -]/', '', $address2);
		$address3 = preg_replace('/[^A-Za-z0-9\. -]/', '', $address3);
		$address4 = preg_replace('/[^A-Za-z0-9\. -]/', '', $address4);
		
		$sel_namesub  = $row['namesub'];
        $firstname    = $row['firstname'];
        $middlename   = $row['middlename'];
        $lastname     = $row['lastname'];
        $addressline1 = $address1;
        $addressline2 = $address2;
        $addressline3 = $address3;
        $addressline4 = $address4;
        $district     = $row['district'];
        $city  = $row['city'];
        $state        = $row['state'];
        $pincode      = $row['pincode'];
        $dob          = $row['dateofbirth'];
		$qualification= $row['qualification'];
        $specify_qualification = $row['specify_qualification'];
        $institutionworking = $row['associatedinstitute'];
        $designation        = $row['designation'];
        $email              = $row['email'];
       	$res_stdcode        = $row['stdcode'];
        $residential_phone  = $row['office_phone'];
        $mobile             = $row['mobile'];
		$regnumber          = $this->session->userdata['enduserinfo']['regnumber'];
		$stdcode            =  $this->session->userdata['enduserinfo']['stdcode'];
        $phone              =  $this->session->userdata['enduserinfo']['phone'];
		$emergency_name     = $this->session->userdata['enduserinfo']['emergency_name'];
		$emergency_contact_no = $this->session->userdata['enduserinfo']['emergency_contact_no'];
        $blood_group        = $this->session->userdata['enduserinfo']['blood_group'];
        $program_code       = $this->session->userdata['enduserinfo']['program'];
        $program_name       = $this->session->userdata['enduserinfo']['program_name'];
		$training_type       = $this->session->userdata['enduserinfo']['training_type'];
        $center_code        = $this->session->userdata['enduserinfo']['center'];
        $center_name        = $this->session->userdata['enduserinfo']['center_name'];
        //$venue_code         = $this->session->userdata['enduserinfo']['venue_code'];
        $batch_code         = $this->session->userdata['enduserinfo']['batch_code'];
        $venue_name         = $this->session->userdata['enduserinfo']['venue_name'];
		$Fees               = $this->session->userdata['enduserinfo']['fees'];
		$training_date      = $this->session->userdata['enduserinfo']['training_date'];
		//$gstin_no              = $this->session->userdata['enduserinfo']['gstin_no'];
		$venueQry           = $this->db->query("SELECT venue_name,start_date,end_date,venue_code FROM blended_venue_master WHERE  program_code='" . $program_code . "' AND center_code='" . $center_code . "' AND  batch_code='" . $batch_code . "' AND training_type ='" .$training_type . "'  AND isdeleted = 0 LIMIT 1");
        $venueArr           = $venueQry->row_array();
		//echo "SQL:->".$this->db->last_query();
		$sDate         = $venueArr['start_date'];
		$eDate         = $venueArr['end_date'];
		$venue_code    = $venueArr['venue_code'];

		$insert_info        = array(
            'member_no' => $regnumber,
            'namesub' => $sel_namesub,
            'firstname' => $firstname,
            'middlename' => $middlename,
            'lastname' => $lastname,
            'address1' => $addressline1,
            'address2' => $addressline2,
            'address3' => $addressline3,
            'address4' => $addressline4,
            'district' => $district,
            'city' => $city,
            'state' => $state,
            'pincode' => $pincode,
            'dateofbirth' => date('Y-m-d', strtotime($dob)),
            'qualification' => $qualification,
            'specify_qualification' => $specify_qualification,
            'associatedinstitute' => $institutionworking,
            'designation' => $designation,
            'email' => $email,
            'stdcode' => $stdcode,
            'office_phone' => $phone,
            'mobile' => $mobile,
            'res_stdcode' => $res_stdcode,
            'residential_phone' => $residential_phone,
            'emergency_name' => $emergency_name,
			'emergency_contact_no' => $emergency_contact_no,
            'blood_group' => $blood_group,
            'program_code' => $program_code,
            'program_name' => $program_name,
			'training_type' => $training_type,
            'center_code' => $center_code,
            'center_name' => $center_name,
            'venue_code' => $venue_code,
            'batch_code' => $batch_code,
            'venue_name' => $venue_name,
            'start_date' => $sDate,
            'end_date' => $eDate,
			//'gstin_no' => $gstin_no,
            'createdon' => date('Y-m-d H:i:s')
        );
		//echo "<pre>"; print_r($insert_info); echo "</pre>";exit;
		
		/* Stored user details and selected field details in the database table */
        if ($last_id = $this->master_model->insertRecord('blended_registration', $insert_info, true)) 
		{    
		
			$program_zone_code = "";
			if ($center_code != "")
			{
				$this->db->where('center_delete', 0);
                $this->db->group_by('center_code');
                $this->db->where('center_code', $center_code);
                $centerProgramArr = $this->master_model->getRecords('offline_center_master','','state_code');
				$program_state_code = $centerProgramArr[0]['state_code'];
				
				$this->db->where('state_delete', 0);
                $this->db->group_by('zone_code');
                $this->db->where('state_code', $program_state_code);
                $programZoneArr = $this->master_model->getRecords('zone_state_master', '','zone_code');
				$program_zone_code = $programZoneArr[0]['zone_code'];
				if($program_zone_code == 'WZ'){$program_zone_code = 'CO';}
			}
	
			/* Check Registration Capacity */
			$RegCount="";
			$RegCount=blendedRegistrationCapacity($program_code,$center_code,$batch_code,$training_type,$venue_code,$sDate);
			
			/* Get Venue Capacity */
			$capacity = "";
			$capacity = getVenueCapacity($program_code,$center_code,$batch_code,$training_type,$venue_code,$sDate);

			/* Get User Attempt */
			$attemptQry=$this->db->query("SELECT attempt,fee_flag FROM blended_eligible_master WHERE member_number='".$regnumber."' AND program_code = '" . $program_code . "'  LIMIT 1"); 
			$attemptArr = $attemptQry->row_array();
			$attempt = $attemptArr['attempt'];
			$fee_flag=$attemptArr['fee_flag'];
			/* Check Count of Vitual Attempts */
			$VitualAttemptsCount = "";
			$VitualAttemptsCount = getVitualAttemptsCounts($regnumber,$program_code,$batch_code);
			if($VitualAttemptsCount != 0)
			{
				$attempt = 1;
			}

			if($RegCount >= $capacity)
			{
				$attemptVal = $attempt+1;
				$blended_data = array('pay_status' => 4, 'attempt' => $attemptVal, 'zone_code' => $program_zone_code, 'modify_date' => date('Y-m-d H:i:s'));
                $this->master_model->updateRecord('blended_registration',$blended_data,array('blended_id'=>$last_id));

				/* User Log Activities  */
				$log_title ="Blended Course Registraion - Capacity is full for this course.";
				$log_message = serialize($insert_info);
				$rId = $last_id;
				$regNo = $regnumber;
				storedUserActivity($log_title, $log_message, $rId, $regNo);
				//$this->session->set_flashdata('flsh_msg', 'Capacity is full for this course.');
				$this->session->set_flashdata('flsh_msg', $program_name.' course capacity is full for this date ( '.$sDate.' To '.$eDate.' ), Please select another training date.');			
				redirect(base_url() . 'blended'); 
			}
			else
			{
				$upd_files = array();
				
				/* User Log Activities  */
				$log_title ="Blended Course Registration-Add Member";
				$log_message = serialize($insert_info);
				$rId = $last_id;
				$regNo = $regnumber;
				storedUserActivity($log_title, $log_message, $rId, $regNo);
				$userarr=array('regno'=> $last_id,'email'=>$email,'regnumber'=>$regnumber,'program_code'=>$program_code);
				$this->session->set_userdata('memberdata', $userarr);
						
				if($VitualAttemptsCount == 0 && $attempt == 0 && $training_type == 'VC' )
				{
					$attemptVal = $attempt+1;
					$blended_data = array('zone_code'=>'CO','pay_status'=>'1','attempt'=>$attemptVal,'modify_date'=>date('Y-m-d H:i:s'));
				    $this->master_model->updateRecord('blended_registration',$blended_data,array('blended_id'=>$last_id));
					
					/* Set Email Content For user */
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_blended_email'));
					if (!empty($regnumber)) {
						$user_info = $this->Master_model->getRecords('member_registration',array('regnumber'=> $regnumber,'isactive'=>'1'),'email,mobile');}
					if (count($emailerstr) > 0) 
					{  
						$Qry=$this->db->query("SELECT program_code, program_name, training_type, center_name, venue_name, start_date, end_date FROM blended_registration WHERE member_no = '".$regnumber."' AND blended_id = '".$last_id."' LIMIT 1");
                            $detailsArr        = $Qry->row_array();
							$program_code = $detailsArr['program_code'];
                            $program_name = $detailsArr['program_name'];
							$training_type = $detailsArr['training_type'];
							if($training_type=="PC")
							{$training_type='Physical Classroom';}else{$training_type='Virtual Classes';}
                            $center_name  = $detailsArr['center_name'];
                            $venue_name   = $detailsArr['venue_name'];
                            $start_date1  = $detailsArr['start_date'];
                            $end_date1    = $detailsArr['end_date'];
                            $start_date   = date("d-M-Y", strtotime($start_date1));
                            $end_date     = date("d-M-Y", strtotime($end_date1));
                            $newstring    = str_replace("#program_name#","".$program_name."",$emailerstr[0]['emailer_text']);
                            $newstring1   = str_replace("#training_type#","".$training_type."",$newstring);
							$newstring2   = str_replace("#center_name#","".$center_name."",$newstring1);
                            $newstring3   = str_replace("#venue_name#","".'-'."",$newstring2);
                            $newstring4   = str_replace("#start_date#","".$start_date."",$newstring3);
                            $newstring5   = str_replace("#end_date#", "".$end_date."",$newstring4);
						
						/* Set Email sending options */
						$info_arr          = array(
							'to' => $user_info[0]['email'],
							//'to' => 'kyciibf@gmail.com',
							//'to' => 'bhushan.amrutkar09@gmail.com',
							'from' => $emailerstr[0]['from'],
							'subject' => $emailerstr[0]['subject'],
							'message' => $newstring5
						);	
						$attachpath = '';
						/* SMS Sending Code */
						$sms_newstring = str_replace("#program_name#", "" . $program_name . "", $emailerstr[0]['sms_text']);
						//$this->master_model->send_sms($user_info[0]['mobile'], $sms_newstring);
						$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']),$sms_newstring,'Xb5EFSwGg');
						$this->Emailsending->mailsend_attch($info_arr, $attachpath);
				   }
				   
					/* Set Email Content For Client */
					if (!empty($regnumber)) {
                            $reg_info = $this->Master_model->getRecords('blended_registration',array('member_no'=> $regnumber,'blended_id' => $last_id)); }
					if($reg_info[0]['member_no'] == $regnumber)
					{
						$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'blended_virtual_emailer_client'));
						if(count($emailerSelfStr) > 0)
						{
							$designation_name=$undergraduate_name=$graduate_name=$postgraduate_name=$specify_qualification=$institution_name=$pay="";
							$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
							$graduate=$this->master_model->getRecords('qualification', array('type' => 'GR'));
							$postgraduate=$this->master_model->getRecords('qualification', array('type'=> 'PG'));
							$institution_master = $this->master_model->getRecords('institution_master');
							$states             = $this->master_model->getRecords('state_master');
							$designation        = $this->master_model->getRecords('designation_master');
							if(count($designation)){
							 foreach($designation as $designation_row){
								if($reg_info[0]['designation']==$designation_row['dcode']){
									$designation_name = $designation_row['dname'];}} 
								}
							if(count($institution_master)){
							  foreach($institution_master as $institution_row){ 	
								if($reg_info[0]['associatedinstitute']==$institution_row['institude_id']){
									$institution_name = $institution_row['name'];}
								  }
								}
							if($reg_info[0]['qualification']=='U'){$undergraduate_name = 'Under Graduate';}
							if($reg_info[0]['qualification']=='G'){$graduate_name = 'Graduate';}
							if($reg_info[0]['qualification']=='P'){$postgraduate_name = 'Post Graduate';}	
							$qualificationArr=$this->master_model->getRecords('qualification',array('qid'=>$reg_info[0]['specify_qualification']),'name','','1');
							if(count($qualificationArr)){
								$specify_qualification = $qualificationArr[0]['name'];
							}
							$training_type = $reg_info[0]['training_type'];
							if($training_type=="PC"){
								$training_type='Physical Classroom';
							}
							else{
								$training_type='Virtual Classes';
							}
							$venue_name   = "-";
							$start_date   = date("d-M-Y", strtotime($reg_info[0]['start_date']));
							$end_date     = date("d-M-Y", strtotime($reg_info[0]['end_date']));
							
							if($reg_info[0]['pay_status'] == 1){ $pay = "Success";}
							
							$selfstr1 = str_replace("#regnumber#", "".$reg_info[0]['member_no']."",  $emailerSelfStr[0]['emailer_text']);
							$selfstr2 = str_replace("#program_name#", "".$reg_info[0]['program_name']."",  $selfstr1);
							$selfstr3 = str_replace("#center_name#", "".$reg_info[0]['center_name']."",  $selfstr2);
							$selfstr4 = str_replace("#venue_name#", "".$venue_name."",  $selfstr3);
							$selfstr5 = str_replace("#start_date#", "".$start_date."",  $selfstr4);
							$selfstr6 = str_replace("#end_date#", "".$end_date."",  $selfstr5);
							$selfstr7 = str_replace("#training_type#", "".$training_type."",  $selfstr6);
							$selfstr8 = str_replace("#fees#", "0",  $selfstr7);
							$selfstr9 = str_replace("#name#", "".$reg_info[0]['namesub']." ".$reg_info[0]['firstname']." ".$reg_info[0]['middlename']." ".$reg_info[0]['lastname'],  $selfstr8);
							$selfstr10 = str_replace("#address1#", "".$reg_info[0]['address1']."",  $selfstr9);
							$selfstr11 = str_replace("#address2#", "".$reg_info[0]['address2']."",  $selfstr10);
							$selfstr12 = str_replace("#address3#", "".$reg_info[0]['address3']."",  $selfstr11);
							$selfstr13 = str_replace("#address4#", "".$reg_info[0]['address4']."",  $selfstr12);
							$selfstr14 = str_replace("#district#", "".$reg_info[0]['district']."",  $selfstr13);
							$selfstr15 = str_replace("#city#", "".$reg_info[0]['city']."",  $selfstr14);
							$selfstr16 = str_replace("#state#", "".$reg_info[0]['state']."",  $selfstr15);
							$selfstr17 = str_replace("#pincode#", "".$reg_info[0]['pincode']."",  $selfstr16);
							$selfstr18 = str_replace("#designation#", "".$designation_name."",  $selfstr17);
							$selfstr19 = str_replace("#associatedinstitute#", "".$institution_name."",  $selfstr18);
							$selfstr20 = str_replace("#dateofbirth#", "".$reg_info[0]['dateofbirth']."",  $selfstr19);
							$selfstr21 = str_replace("#email#", "".$reg_info[0]['email']."",  $selfstr20);
							$selfstr22 = str_replace("#mobile#", "".$reg_info[0]['mobile']."",  $selfstr21);
							$selfstr23 = str_replace("#res_stdcode#", "".$reg_info[0]['res_stdcode']."",  $selfstr22);
							$selfstr24 = str_replace("#residential_phone#", "".$reg_info[0]['residential_phone']."",  $selfstr23);
							$selfstr25 = str_replace("#stdcode#", "".$reg_info[0]['stdcode']."",  $selfstr24);
							$selfstr26 = str_replace("#office_phone#", "".$reg_info[0]['office_phone']."",  $selfstr25);
							$selfstr27 = str_replace("#qualification#", "".$undergraduate_name.$graduate_name.$postgraduate_name."",  $selfstr26);
							$selfstr28 = str_replace("#specify_qualification#", "".$specify_qualification."",  $selfstr27);
							$selfstr29 = str_replace("#emergency_name#", "".$reg_info[0]['emergency_name']."",  $selfstr28);
							$selfstr30 = str_replace("#emergency_contact_no#", "".$reg_info[0]['emergency_contact_no']."",  $selfstr29);
							$final_selfstr = str_replace("#blood_group#", "".$reg_info[0]['blood_group']."",  $selfstr30);
							$s1 = str_replace("#program_code#", "".$reg_info[0]['program_code'],$emailerSelfStr[0]['subject']);
							 $final_sub = str_replace("#Batch_code#", "".$reg_info[0]['batch_code']."",  $s1);
							/* Get Client Emails Details */
							$emailsQry = $this->db->query("SELECT emails FROM offline_email_master WHERE zone_code = '" . $reg_info[0]['zone_code'] . "' AND program_code = '" . $reg_info[0]['program_code'] . "' AND batch_code = '" . $reg_info[0]['batch_code'] . "'AND isdelete = 0 LIMIT 1 ");
							
							
							$emailsArr    = $emailsQry->row_array();
							$emails  = $emailsArr['emails'];
							$self_mail_arr = array(
							'to'=>$emails,
							'from'=>$emailerSelfStr[0]['from'],
							'subject'=>$final_sub,
							'message'=>$final_selfstr);					
							$attachpath = '';
							$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);
						}
					}
					redirect(base_url() . 'blended/acknowledge/');
				}
				
				/* Get Attempts Count */
				$AttemptsCount = $TotalAttemptsCounts = "";
			    $AttemptsCount = getAttemptsCounts($regnumber,$program_code,$batch_code);
				$TotalAttemptsCounts = getTotalAttemptsCounts($regnumber,$program_code);
				
				if($AttemptsCount == 1)
				{
					$this->session->set_flashdata('flsh_msg', 'You have already applied to this course...!');
				    redirect(base_url() . 'blended/index'); 
				}
				/* Check 2 times registrations attempt count */
				elseif($TotalAttemptsCounts > 2) 
				{
					redirect(base_url() . "blended/make_payment");
					/*$this->session->set_flashdata('flsh_msg', 'You have already applied Blended Courses two times..!');
					 redirect(base_url() . 'blended/index');*/ 
				}
				else
				{   /* Call Make Payment Function */
				  	redirect(base_url() . "blended/make_payment");
				}
			}
        } 
		else 
		{
            $userarr = array('regno' => '','email' => '','regnumber' => '','program_code' => '');
            $this->session->set_userdata('memberdata', $userarr);
            $this->session->set_flashdata('flsh_msg', 'Error while during registration.please try again!');
            redirect(base_url() . 'blended/index'); 
        }
    }
	/* Payment Function */
    public function make_payment()
    {
		
		
        $cgst_rate = $sgst_rate = $igst_rate = $tax_type = '';
        $cgst_amt  = $sgst_amt = $igst_amt = '';
        $cs_total  = $igst_total = '';
        $getstate  = $getcenter = $getfees = array();
		$center_name = $center_code = $regno = $regnumber = $program_code = $amount = $state = $exempt = $state_no = $state_name = '';
		$flag      = 1;
		$regno        = $this->session->userdata['memberdata']['regno'];
		$regnumber    = $this->session->userdata['memberdata']['regnumber'];
		$program_code = $this->session->userdata['memberdata']['program_code'];
	//	echo'<pre>';print_r($this->session->userdata['memberdata']);exit;
		if($regno == "" || $regnumber == "" || $program_code == "")
		{
			/* User Log Activities  */
			$log_title ="Blended Course Registration-Session Expired";
			$log_message = 'Program Code : '.$this->session->userdata['memberdata']['program_code'];
			$rId = $this->session->userdata['memberdata']['regno'];
			$regNo = $this->session->userdata['memberdata']['regnumber'];
			storedUserActivity($log_title, $log_message, $rId, $regNo);
			
			$this->session->set_flashdata('flsh_msg', 'Your session has been expired. Please try again...!');
			redirect(base_url() . 'blended'); 
		}
	    $valcookie = register_get_cookie();
        if ($valcookie) {
            $regid     = $valcookie;
            $checkuser = $this->master_model->getRecords('member_registration', array(
                'regid' => $regno,
                'regnumber !=' => '',
                'isactive !=' => '0'
            ));
			//echo $this->db->last_query(); exit;
            if (count($checkuser) > 0) {
                delete_cookie('regid');
                redirect('http://iibf.org.in');
            } else {
                $checkpayment = $this->master_model->getRecords('payment_transaction', array(
                    'ref_id' => $regno,
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
        
		if (isset($_POST['processPayment']) && $_POST['processPayment']) 
		{
			$pg_name = $this->input->post('pg_name');
            register_set_cookie($regno);
           /*  include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key            = $this->config->item('sbi_m_key');
            $merchIdVal     = $this->config->item('sbi_merchIdVal');
            $AggregatorId   = $this->config->item('sbi_AggregatorId');
            $pg_success_url = base_url() . "blended/sbitranssuccess";
            $pg_fail_url    = base_url() . "blended/sbitransfail"; */
			
			/* Get User State Details */
			if (!empty($regnumber)) {
            $member_data = $this->Master_model->getRecords('member_registration', array('regnumber' => $regnumber, 'isactive' => '1'),'state');
			$state = $member_data[0]['state'];
			}
			/* Get User Selected Center, State & Zone Details */
			if ($regnumber != "" && $regno != "")
			{
				$center_data = $this->Master_model->getRecords('blended_registration', array('member_no' => $regnumber,'blended_id' => $regno),'center_name,center_code,batch_code,training_type,venue_code,start_date,end_date,program_name');
				$selected_center_name = $center_data[0]['center_name'];
				$selected_center_code = $center_data[0]['center_code'];
				$venue_batch_code = $center_data[0]['batch_code'];
				$selected_training_type = $center_data[0]['training_type'];
				$selected_venue_code = $center_data[0]['venue_code'];
				$sDate = $center_data[0]['start_date'];
				$eDate = $center_data[0]['end_date'];
				$program_name = $center_data[0]['program_name'];
				
				$this->db->where('center_delete', 0);
                $this->db->group_by('center_code');
                $this->db->where('center_code', $selected_center_code);
                $centerArr = $this->master_model->getRecords('offline_center_master', '', 'state_code');
				$selected_state_code = $centerArr[0]['state_code'];
				
				$this->db->where('state_delete', 0);
                $this->db->group_by('zone_code');
                $this->db->where('state_code', $selected_state_code);
                $selectedZoneArr = $this->master_model->getRecords('zone_state_master', '', 'zone_code');
				$selected_zone_code = $selectedZoneArr[0]['zone_code'];
			}
			/* Get Program Center, State & Zone Details */  
			if ($program_code != "")
			{
				$program_center_code = $selected_center_code;
				$this->db->where('center_delete', 0);
                $this->db->group_by('center_code');
				$this->db->where('center_code', $selected_center_code);
                $centerProgramArr = $this->master_model->getRecords('offline_center_master','','state_code,center_name,center_code');
				//echo $this->db->last_query();exit;
				$program_state_code = $centerProgramArr[0]['state_code'];
				$program_center_name = $centerProgramArr[0]['center_name'];
				$program_center_code = $centerProgramArr[0]['center_code'];
				
				/*$this->db->where('state_delete', 0);
                $this->db->group_by('zone_code');
                $this->db->where('state_code', $program_state_code);
                $programZoneArr = $this->master_model->getRecords('zone_state_master', '','zone_code,state_name,state_code');
				$program_zone_code = $programZoneArr[0]['zone_code'];
				$program_state_name = $programZoneArr[0]['state_name'];
				$program_state_code = $programZoneArr[0]['state_code'];
				if($program_zone_code == 'WZ'){$program_zone_code = 'CO';}*/
				
				$this->db->where('isdeleted', 0);
                $this->db->where('zone_code', $selected_zone_code);
                $programZoneArr = $this->master_model->getRecords('zone_master', '','state_code');
				$zone_state_code = $programZoneArr[0]['state_code'];
				
				$this->db->where('state_delete', 0);
                $this->db->group_by('zone_code');
                $this->db->where('state_no', $zone_state_code);
                $programZoneArr = $this->master_model->getRecords('zone_state_master', '','zone_code,state_name,state_code');
				$program_zone_code = $programZoneArr[0]['zone_code'];
				$program_state_name = $programZoneArr[0]['state_name'];
				$program_state_code = $programZoneArr[0]['state_code'];
				if($program_zone_code == 'WZ'){$program_zone_code = 'CO';}
			}
			
			/* Get State Details */
			if (!empty($selected_state_code)){
                $getstate = $this->master_model->getRecords('zone_state_master', array('state_code' => $selected_state_code,'state_delete' => '0'), 'exempt,state_no,state_name');
				
                $exempt     = $getstate[0]['exempt'];
				$state_no   = $getstate[0]['state_no'];
				$state_name = $getstate[0]['state_name'];
            }
			//echo $this->db->last_query();exit;
			/* Get User Attempt */
			$attemptQry=$this->db->query("SELECT attempt,fee_flag FROM blended_eligible_master WHERE member_number='".$regnumber."' AND program_code = '" . $program_code . "' LIMIT 1"); 
			$attemptArr = $attemptQry->row_array();
			$attempt = $attemptArr['attempt'];
			$fee_flag=$attemptArr['fee_flag'];
			/* Check Count of Vitual Attempts */
			$VitualAttemptsCount = "";
			$VitualAttemptsCount = getVitualAttemptsCounts($regnumber,$program_code,$venue_batch_code);
			if($VitualAttemptsCount != 0)
			{
				$attempt = 1;
			}
			/*elseif($fee_flag==1 && $VitualAttemptsCount==0)
			{
				$attempt=2;
			}*/
			/* Get Fee Details By exempt, program_code, batch_code & training_type*/
			//echo $program_code.'='.$exempt.'='.$selected_training_type;exit;
			if(!empty($program_code) && !empty($exempt) && !empty($selected_training_type))
			{
				$feeMasterArr = $this->master_model->getRecords('blended_fee_master',array('exempt' => $exempt,
'fee_delete'=> '0','program_code' => $program_code,'batch_code' => $venue_batch_code,'training_type' => $selected_training_type,'attempt' => $attempt)); //echo $this->db->last_query(); die;
			}

		//	echo $this->db->last_query();exit;
			/* Set Up Fees */
            $fee_amount   = $feeMasterArr[0]['fee_amount'];
            $sgst_amt     = $feeMasterArr[0]['sgst_amt'];
            $cgst_amt     = $feeMasterArr[0]['cgst_amt'];
            $cs_tot       = $feeMasterArr[0]['cs_tot'];
            $igst_amt     = $feeMasterArr[0]['igst_amt'];
            $igst_tot     = $feeMasterArr[0]['igst_tot'];	
			
			if($selected_state_code != $program_state_code)
			{
				$igst_rate  = $this->config->item('blended_igst_rate');
                $igst_amt   = $igst_amt;
                $igst_total = $igst_tot;
				$amount		= $igst_tot;
                $tax_type   = 'Inter';
				$cs_total   = '';
				$cgst_amt   = '';
                $sgst_amt   = '';
			}
			else{
				$cgst_rate  = $this->config->item('blended_cgst_rate');
                $sgst_rate  = $this->config->item('blended_sgst_rate');
				$tax_type   = 'Intra';
				$amount     = $cs_tot;
				$cgst_amt   = $cgst_amt;
                $sgst_amt   = $sgst_amt;
                $cs_total   = $cs_tot;
				$igst_amt   = '';
				$igst_total = '';
			}
			
			/* Check Registration Capacity */
			$RegCount = "";
			$RegCount = blendedRegistrationCapacity($program_code,$selected_center_code,$venue_batch_code,$selected_training_type,$selected_venue_code,$sDate);
			
			/* Get Venue Capacity */
			$capacity = "";
			$capacity = getVenueCapacity($program_code,$selected_center_code,$venue_batch_code,$selected_training_type,$selected_venue_code,$sDate);
			
			if($RegCount >= $capacity)
			{
				$attempt = $attempt+1;
				$blended_data = array('pay_status' => 4, 'zone_code' => $program_zone_code, 'attempt' => $attempt, 'modify_date' => date('Y-m-d H:i:s'));
				$this->master_model->updateRecord('blended_registration',$blended_data,array('blended_id'=>$regno));
				
				/* User Log Activities  */
				$log_title ="Blended Course Registraion - Capacity is full for this course.";
				$log_message = $log_message = 'Program Code : '.$program_code;
				$rId = $regno;
				$regNo = $regnumber;
				storedUserActivity($log_title, $log_message, $rId, $regNo);
				
				//$this->session->set_flashdata('flsh_msg', 'Capacity is full for this course.');
				
				$this->session->set_flashdata('flsh_msg', $program_name.' course capacity is full for this date ( '.$sDate.' To '.$eDate.' ), Please select another training date.');
				
				redirect(base_url() . 'blended'); 
			}
			
			/* Stored details in the Payment Transaction table */
            $insert_data = array('member_regnumber' => $regnumber,
								'gateway' => "sbiepay",
								'amount' => $amount,
								'date' => date('Y-m-d H:i:s'),
								'ref_id' => $regno,
								'description' => "Blended Course Registration",
								'pay_type' => 10,
								'status' => 2,
								'pg_flag' => 'iibftrg');
							
			//echo'<pre>';print_r($insert_data);exit;
			
            $pt_id           = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
			//echo $this->db->last_query();exit;
            $MerchantOrderNo = sbi_exam_order_id($pt_id);
			$custom_field    = $MerchantOrderNo . "^iibfexam^iibftrg^" . $regnumber;
			$custom_field_billdesk = $MerchantOrderNo."-iibfexam-iibftrg-".$regnumber;
            
			$update_data     = array( 'receipt_no' => $MerchantOrderNo,'pg_other_details' => $custom_field);
			$this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));
          //  echo $this->db->last_query();exit;
			$blended_update = array( 'fee' => $amount, 'zone_code' => $program_zone_code);
            $this->master_model->updateRecord('blended_registration', $blended_update, array('blended_id' => $regno));
			
			/* Stored Details in the Exam Invoice table */
			$blended_service_code = $this->config->item('blended_service_code');
			$invoice_insert_array=array('pay_txn_id' => $pt_id,
										'receipt_no' => $MerchantOrderNo,
										'member_no' => $regnumber,
										'state_of_center' => $selected_state_code,
										'center_name' => $selected_center_name,
										'center_code' => $selected_center_code,
										'app_type' => 'T',
										'service_code' => $blended_service_code,
										'qty' => '1',
										'state_code' => $state_no,
										'state_name' => $state_name,
										'tax_type' => $tax_type,
										'fee_amt' => $fee_amount,
										'cgst_rate' => $cgst_rate,
										'cgst_amt' => $cgst_amt,
										'sgst_rate' => $sgst_rate,
										'sgst_amt' => $sgst_amt,
										'igst_rate' => $igst_rate,
										'igst_amt' => $igst_amt,
										'cs_total' => $cs_total,
										'igst_total' => $igst_total,
										//'gstin_no' => '',
										'exempt' => $exempt,
										'created_on' => date('Y-m-d H:i:s'));
           // echo "<pre> invoice_insert_array => "; print_r($invoice_insert_array); echo "</pre>";
			//exit;
			$inser_id             = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array);
            /* $MerchantCustomerID   = $regno;
            $data["pg_form_url"]  = $this->config->item('sbi_pg_form_url');
            $data["merchIdVal"]   = $merchIdVal;
            $EncryptTrans         = $merchIdVal . "|DOM|IN|INR|" . $amount . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";
			
			//echo '<br>'.$EncryptTrans;
			//exit;
            $aes                  = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $EncryptTrans         = $aes->encrypt($EncryptTrans);
            $data["EncryptTrans"] = $EncryptTrans;
			$this->load->view('pg_sbi_form', $data);
			 */
				if ($pg_name == 'sbi') 
				{ 
               include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('sbi_m_key');
				$merchIdVal = $this->config->item('sbi_merchIdVal');
				$AggregatorId = $this->config->item('sbi_AggregatorId');
				
				$pg_success_url = base_url()."Blended/sbitranssuccess";
				$pg_fail_url    = base_url()."Blended/sbitransfail";
//exit;
                $MerchantCustomerID  = $inser_id;
                $data["pg_form_url"] = $this->config->item('sbi_pg_form_url');
                $data["merchIdVal"]  = $merchIdVal;
                $EncryptTrans        = $merchIdVal . "|DOM|IN|INR|" . $amount . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";
                $aes                 = new CryptAES();
                $aes->set_key(base64_decode($key));
                $aes->require_pkcs5();
                $EncryptTrans         = $aes->encrypt($EncryptTrans);
                $data["EncryptTrans"] = $EncryptTrans;
                $this->load->view('pg_sbi_form', $data);
					} 
					elseif ($pg_name == 'billdesk') 
					{
						$update_payment_data = array('gateway' =>'billdesk');
						$this->master_model->updateRecord('payment_transaction',$update_payment_data,array('id'=>$pt_id));
					
                $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $new_invoice_id, $new_invoice_id, '', 'Blended/handle_billdesk_response', '', '', '', $custom_field_billdesk);
                
			//	echo'<pre>';print_r($billdesk_res);exit;

                if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {
                    $data['bdorderid'] = $billdesk_res['bdorderid'];
                    $data['token']     = $billdesk_res['token'];
										$data['responseXHRUrl'] = $billdesk_res['responseXHRUrl']; 
										$data['returnUrl'] = $billdesk_res['returnUrl']; 
                    $this->load->view('pg_billdesk/pg_billdesk_form', $data);
                }else{
                    $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                    redirect(base_url() . 'Blended');
                }
            }
			
        } 
		else{
            $data['show_billdesk_option_flag'] = 1; //if issue occure make = 0.
						$this->load->view('pg_sbi/make_payment_page', $data);
        }
    } 
	
	public function handle_billdesk_response()
    {
       /* ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/
        $selected_invoice_id = $attachpath = $invoiceNumber = '';
        //$selected_invoice_id = $this->session->userdata['memberdata']['regno']; // Seleted Invoice Id
      
        
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
            $auth_status = $responsedata['auth_status'];	
            
            $get_user_regnum_info   = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id', '', '', '1');
            if (empty($get_user_regnum_info)) {
                redirect(base_url() . 'Blended');
            }
            $new_invoice_id   = $get_user_regnum_info[0]['ref_id'];
            $member_regnumber = $get_user_regnum_info[0]['member_regnumber'];
            //Query to get Payment details
            $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $member_regnumber), 'transaction_no,date,amount,id');
             $applicationNo = $get_user_regnum_info[0]['member_regnumber'];
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
            /* Transaction Log */
            $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
            $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);
            /* Update Exam Invoice */
			$qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
			if($auth_status == "0300" && $qry_api_response['auth_status'] == '0300' && $get_user_regnum_info[0]['status'] == 2) 
			{
			$reg_id        = $get_user_regnum_info[0]['ref_id'];
			$reg_data = $this->Master_model->getRecords('blended_registration', array('member_no' => $applicationNo,'blended_id' => $reg_id),'program_code,center_code,batch_code,training_type,venue_code,start_date');
						$selected_program_code = $reg_data[0]['program_code'];
						$selected_center_code = $reg_data[0]['center_code'];
						$venue_batch_code = $reg_data[0]['batch_code'];
						$selected_training_type = $reg_data[0]['training_type'];
						$selected_venue_code	= $reg_data[0]['venue_code'];		
						$sDate = $reg_data[0]['start_date'];
						/* Check Registration Capacity */
						$RegCount = "";
						$RegCount = blendedRegistrationCapacity($selected_program_code,$selected_center_code,$venue_batch_code,$selected_training_type,$selected_venue_code,$sDate);
						
						/* Get Venue Capacity */
						$capacity = "";
						$capacity = getVenueCapacity($selected_program_code,$selected_center_code,$venue_batch_code,$selected_training_type,$selected_venue_code,$sDate);
						
						/* Get User Attempt */
						$attemptQry=$this->db->query("SELECT attempt,fee_flag FROM blended_eligible_master WHERE member_number='".$applicationNo."' AND program_code = '" . $selected_program_code . "' LIMIT 1"); 
						$attemptArr = $attemptQry->row_array();
						$attempt = $attemptArr['attempt'];
						$fee_flag=$attemptArr['fee_flag'];
						/* Check Count of Vitual Attempts */
						$VitualAttemptsCount = "";
						$VitualAttemptsCount = getVitualAttemptsCounts($applicationNo,$selected_program_code,$venue_batch_code);
						if($VitualAttemptsCount != 0)
						{
							$attempt = 1;
						}
							
	
						$attempt = $attempt+1;
						if($RegCount >= $capacity)
						{
							// Refundable
							$blended_data = array('pay_status' => 3, 'attempt'=>$attempt, 'modify_date' => date('Y-m-d H:i:s'));
							$this->master_model->updateRecord('blended_registration',$blended_data,array('blended_id'=>$reg_id));
							/* User Log Activities  */
							$log_title ="Blended Course Registraion - Capacity is full after payment success.";
							$log_message = $log_message = 'Program Code : '.$selected_program_code;
							$rId = $reg_id;
							$regNo = $applicationNo;
							storedUserActivity($log_title, $log_message, $rId, $regNo);
							redirect(base_url().'blended/refund/'.base64_encode($MerchantOrderNo));
						}
						/* Update Pay Status and User Attemp Status */
						$blended_data = array('pay_status'=>1, 'attempt'=>$attempt, 'modify_date'=>date('Y-m-d H:i:s'));
                        $this->master_model->updateRecord('blended_registration',$blended_data,array('blended_id'=>$reg_id));
						
						$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_blended_email'));
						if (!empty($applicationNo)) {
                            $user_info = $this->Master_model->getRecords('member_registration',array('regnumber'=> $applicationNo,'isactive'=>'1'),'email,mobile');
                        }
                        if (count($emailerstr) > 0) 
						{
                            /* Set Email Content For user */
                            $Qry=$this->db->query("SELECT program_code, program_name, training_type, center_name, venue_name, start_date, end_date FROM blended_registration WHERE blended_id = '".$reg_id."' LIMIT 1");
                            $detailsArr        = $Qry->row_array();
							$program_code = $detailsArr['program_code'];
                            $program_name = $detailsArr['program_name'];
							$training_type = $detailsArr['training_type'];
							
							if($training_type=="PC"){
								$training_type='Physical Classroom';
                                $venue_name   = $detailsArr['venue_name'];
							}
							else{
								$training_type='Virtual Classes';
                            	$venue_name   = '-';
							}
							$center_name  = $detailsArr['center_name'];
                            $start_date1  = $detailsArr['start_date'];
                            $end_date1    = $detailsArr['end_date'];
                            $start_date   = date("d-M-Y", strtotime($start_date1));
                            $end_date     = date("d-M-Y", strtotime($end_date1));
                            $newstring    = str_replace("#program_name#","".$program_name."",$emailerstr[0]['emailer_text']);
                            $newstring1   = str_replace("#training_type#","".$training_type."",$newstring);
							$newstring2   = str_replace("#center_name#","".$center_name."",$newstring1);
                            $newstring3   = str_replace("#venue_name#","".$venue_name."",$newstring2);
                            $newstring4   = str_replace("#start_date#","".$start_date."",$newstring3);
                            $newstring5   = str_replace("#end_date#", "".$end_date."",$newstring4);
							
							/* Set Email sending options */
							$info_arr          = array(
                               	'to' => $user_info[0]['email'],
							//	'to' => 'kyciibf@gmail.com',
								//'to' => 'bhushan.amrutkar09@gmail.com',
                                'from' => $emailerstr[0]['from'],
                                'subject' => $emailerstr[0]['subject'],
                                'message' => $newstring5
                            );
                            $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo,'pay_txn_id' => $get_user_regnum_info[0]['id']));
							$zone_code = ""; 
							$zoneArr = array();
							//$regno = $this->session->userdata['memberdata']['regno'];
							$zoneArr = $this->master_model->getRecords('blended_registration',array('blended_id'=>$reg_id,'pay_status'=>1),'zone_code,gstin_no');
							$zone_code = $zoneArr[0]['zone_code'];
							
							$gstin_no          = $zoneArr[0]['gstin_no'];
							/* Invoice Number Genarate Functinality */
                            if (count($getinvoice_number) > 0){
								$invoiceNumber = generate_blended_invoice_number($getinvoice_number[0]['invoice_id'],$zone_code);
								if($invoiceNumber){$invoiceNumber = $this->config->item('blended_invoice_T'.$zone_code.'_prefix').$invoiceNumber;}
                                $update_data22 = array(
                                    'invoice_no' => $invoiceNumber,
                                    //'member_no' => $applicationNo,
                                    'transaction_no' => $transaction_no,
                                    'date_of_invoice' => date('Y-m-d H:i:s'),
                                    'modified_on' => date('Y-m-d H:i:s')
                                );
                                $this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
                                $this->master_model->updateRecord('exam_invoice', $update_data22, array('receipt_no' => $MerchantOrderNo));
								/* Invoice Genarate Function */
                                $attachpath = genarate_blended_invoice($getinvoice_number[0]['invoice_id'],$zone_code,$program_name);
								/* User Log Activities  */
								$log_title ="Blended Course Registration-Invoice Genarate";
								$log_message = serialize($update_data22);
								$rId = $reg_id;
								$regNo = $applicationNo;
								storedUserActivity($log_title, $log_message, $rId, $regNo);
                            }
                            if ($attachpath != '') 
							{	
					/* Email Send To Clints */
					
					if (!empty($applicationNo)) {
                            $reg_info = $this->Master_model->getRecords('blended_registration',array('member_no'=> $applicationNo,'blended_id' => $reg_id));
                        }
						$payment_infoArr=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$reg_info[0]['member_no']),'transaction_no,date,amount');
					
								if($reg_info[0]['member_no'] == $applicationNo)
								{
									$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'blended_emailer_client'));
									if(count($emailerSelfStr) > 0)
									{
										$designation_name=$undergraduate_name=$graduate_name=$postgraduate_name=$specify_qualification=$institution_name=$pay="";
										
										$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
										$graduate=$this->master_model->getRecords('qualification', array('type' => 'GR'));
										$postgraduate=$this->master_model->getRecords('qualification', array('type'=> 'PG'));
										
										$institution_master = $this->master_model->getRecords('institution_master');
										$states             = $this->master_model->getRecords('state_master');
										$designation        = $this->master_model->getRecords('designation_master');
										if(count($designation)){
										 foreach($designation as $designation_row){
											if($reg_info[0]['designation']==$designation_row['dcode']){
												$designation_name = $designation_row['dname'];}
												} 
											}
										if(count($institution_master)){
										  foreach($institution_master as $institution_row){ 	
											if($reg_info[0]['associatedinstitute']==$institution_row['institude_id']){
												$institution_name = $institution_row['name'];}
											  }
											}
										
										if($reg_info[0]['qualification']=='U'){$undergraduate_name = 'Under Graduate';}
										if($reg_info[0]['qualification']=='G'){$graduate_name = 'Graduate';}
										if($reg_info[0]['qualification']=='P'){$postgraduate_name = 'Post Graduate';}	
										
										$qualificationArr=$this->master_model->getRecords('qualification',array('qid'=>$reg_info[0]['specify_qualification']),'name','','1');
										if(count($qualificationArr)) 
										{
											$specify_qualification = $qualificationArr[0]['name'];
										}
										
										$training_type = $reg_info[0]['training_type'];
										if($training_type=="PC")
										{
											$training_type='Physical Classroom';
											$venue_name   = $reg_info[0]['venue_name'];
										}
										else
										{
											$training_type='Virtual Classes';
											$venue_name   = "-";
										}
										$center_name  = $reg_info[0]['center_name'];
										
										$start_date   = date("d-M-Y", strtotime($reg_info[0]['start_date']));
										$end_date     = date("d-M-Y", strtotime($reg_info[0]['end_date']));
										
										if($reg_info[0]['pay_status'] == 1){ $pay = "Success";}
										$selfstr1 = str_replace("#regnumber#", "".$reg_info[0]['member_no']."",  $emailerSelfStr[0]['emailer_text']);
										$selfstr2 = str_replace("#program_name#", "".$reg_info[0]['program_name']."",  $selfstr1);
										$selfstr3 = str_replace("#center_name#", "".$center_name."",  $selfstr2);
										$selfstr4 = str_replace("#venue_name#", "".$venue_name."", $selfstr3);
										$selfstr5 = str_replace("#start_date#", "".$start_date."", $selfstr4);
										$selfstr6 = str_replace("#end_date#", "".$end_date."",  $selfstr5);
										
										$selfstr7 = str_replace("#training_type#", "".$training_type."",  $selfstr6);	
										$selfstr8 = str_replace("#name#", "".$reg_info[0]['namesub']." ".$reg_info[0]['firstname']." ".$reg_info[0]['middlename']." ".$reg_info[0]['lastname'],  $selfstr7);
										$selfstr9 = str_replace("#address1#", "".$reg_info[0]['address1']."",  $selfstr8);
										$selfstr10 = str_replace("#address2#", "".$reg_info[0]['address2']."",  $selfstr9);
										$selfstr11 = str_replace("#address3#", "".$reg_info[0]['address3']."",  $selfstr10);
										$selfstr12 = str_replace("#address4#", "".$reg_info[0]['address4']."",  $selfstr11);
										
										$selfstr13 = str_replace("#district#", "".$reg_info[0]['district']."",  $selfstr12);
										$selfstr14 = str_replace("#city#", "".$reg_info[0]['city']."",  $selfstr13);
										$selfstr15 = str_replace("#state#", "".$reg_info[0]['state']."",  $selfstr14);
										$selfstr16 = str_replace("#pincode#", "".$reg_info[0]['pincode']."",  $selfstr15);
										$selfstr17 = str_replace("#designation#", "".$designation_name."",  $selfstr16);
										$selfstr18 = str_replace("#associatedinstitute#", "".$institution_name."",  $selfstr17);
										$selfstr19 = str_replace("#dateofbirth#", "".$reg_info[0]['dateofbirth']."",  $selfstr18);
										$selfstr20 = str_replace("#email#", "".$reg_info[0]['email']."",  $selfstr19);
										$selfstr21 = str_replace("#mobile#", "".$reg_info[0]['mobile']."",  $selfstr20);
										$selfstr22 = str_replace("#res_stdcode#", "".$reg_info[0]['res_stdcode']."",  $selfstr21);
										$selfstr23 = str_replace("#residential_phone#", "".$reg_info[0]['residential_phone']."",  $selfstr22);
										$selfstr24 = str_replace("#stdcode#", "".$reg_info[0]['stdcode']."",  $selfstr23);
										$selfstr25 = str_replace("#office_phone#", "".$reg_info[0]['office_phone']."",  $selfstr24);
										$selfstr26 = str_replace("#qualification#", "".$undergraduate_name.$graduate_name.$postgraduate_name."",  $selfstr25);
										$selfstr27 = str_replace("#specify_qualification#", "".$specify_qualification."",  $selfstr26);
										$selfstr28 = str_replace("#emergency_name#", "".$reg_info[0]['emergency_name']."",  $selfstr27);
										$selfstr29 = str_replace("#emergency_contact_no#", "".$reg_info[0]['emergency_contact_no']."",  $selfstr28);
										$selfstr30 = str_replace("#blood_group#", "".$reg_info[0]['blood_group']."",  $selfstr29);
										$selfstr31 = str_replace("#TRANSACTION_NO#", "".$payment_infoArr[0]['transaction_no']."",  $selfstr30);
										$selfstr32 = str_replace("#AMOUNT#", "".$payment_infoArr[0]['amount']."",  $selfstr31);
										$selfstr33 = str_replace("#STATUS#", "Transaction Successful",  $selfstr32);
										$final_selfstr = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_infoArr[0]['date']))."",  $selfstr33);
									$s1 = str_replace("#program_code#", "".$reg_info[0]['program_code'],$emailerSelfStr[0]['subject']);
									  $final_sub = str_replace("#Batch_code#", "".$reg_info[0]['batch_code']."",  $s1);
										/* Get Client Emails Details */
										$emailsQry = $this->db->query("SELECT emails FROM offline_email_master WHERE zone_code = '" . $reg_info[0]['zone_code'] . "' AND program_code = '" . $reg_info[0]['program_code'] . "' AND batch_code = '" . $reg_info[0]['batch_code'] . "'AND isdelete = 0 LIMIT 1 ");
										$emailsArr    = $emailsQry->row_array();
										$emails  = $emailsArr['emails'];	
									
										$self_mail_arr = array(
										'to'=>$emails,
										//'to'=>'chaitali.jadhav@esds.co.in',
										'from'=>$emailerSelfStr[0]['from'],
										'subject'=>$final_sub,
										'message'=>$final_selfstr);	
										$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);
									}
								}
								/* SMS Sending Code */
								$sms_newstring = str_replace("#program_name#", "" . $program_name . "", $emailerstr[0]['sms_text']);
								//$this->master_model->send_sms($user_info[0]['mobile'], $sms_newstring);
								$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']),$sms_newstring,'Xb5EFSwGg');
								
                                if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
                                    redirect(base_url() . 'blended/acknowledge/');
                                } else { redirect(base_url() . 'blended/acknowledge/');}
                            } else {
                                redirect(base_url() . 'blended/acknowledge/');
                            }
                        }
                    


				} 
				elseif ($auth_status=='0002') {
						$update_data33 = array(
	                    'transaction_no' => $transaction_no,
	                    'status' => 2,
	                    'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
	                    'auth_code' => '0002',
	                    'bankcode' => $bankid,
	                    'paymode' => $txn_process_type,
	                    'callback' => 'B2B'
						);
						$this->master_model->updateRecord('payment_transaction', $update_data33, array(
							'receipt_no' => $MerchantOrderNo
						));
						
						//Manage Logs
						$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
						$this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);
						
						$this->session->set_flashdata('flsh_msg', 'Transaction pending...!');
						redirect(base_url() . 'Blended');
				}
				else /* if ($transaction_error_type == 'payment_authorization_error') */ 
				{
					$update_data33 = array(
                    'transaction_no' => $transaction_no,
                    'status' => 0,
                    'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                    'auth_code' => '0300',
                    'bankcode' => $bankid,
                    'paymode' => $txn_process_type,
                    'callback' => 'B2B'
					);
					$this->master_model->updateRecord('payment_transaction', $update_data33, array(
						'receipt_no' => $MerchantOrderNo
					));
					
					//Manage Logs
					$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
					$this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);
					
					$this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
					redirect(base_url() . 'Blended');
				}
        } else {
            die("Please try again...");
        }
    }
   
	/* Payment Success And Invoice genrate */
    public function sbitranssuccess()
    {
    	die();
        delete_cookie('regid');
        if (isset($_REQUEST['encData'])) {
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('sbi_m_key');
            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $encData         = $aes->decrypt($_REQUEST['encData']);
            $responsedata    = explode("|", $encData);
            $MerchantOrderNo = $responsedata[0];
            $transaction_no  = $responsedata[1];
            $attachpath      = $invoiceNumber = '';
            if (isset($_REQUEST['merchIdVal'])) {$merchIdVal = $_REQUEST['merchIdVal'];}
            if (isset($_REQUEST['Bank_Code'])) {$Bank_Code = $_REQUEST['Bank_Code'];}
            if (isset($_REQUEST['pushRespData'])) {$encData = $_REQUEST['pushRespData'];}
            $q_details = sbiqueryapi($MerchantOrderNo);
            if ($q_details) {
                if ($q_details[2] == "SUCCESS") {
                    $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(
                        'receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id');
                    if ($get_user_regnum_info[0]['status'] == 2) {
                        $reg_id        = $get_user_regnum_info[0]['ref_id'];
                        //$applicationNo = $this->session->userdata['memberdata']['regnumber'];
                        $applicationNo = $get_user_regnum_info[0]['member_regnumber'];
						$update_data   = array(
                            //'member_regnumber' => $applicationNo,
                            'transaction_no' => $transaction_no,
                            'status' => 1,
                            'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
                            'auth_code' => '0300',
                            'bankcode' => $responsedata[8],
                            'paymode' => $responsedata[5],
                            'callback' => 'B2B'
                        );
                      	$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
						/* Transaction Log */
						$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                        $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
						
						$reg_data = $this->Master_model->getRecords('blended_registration', array('member_no' => $applicationNo,'blended_id' => $reg_id),'program_code,center_code,batch_code,training_type,venue_code,start_date');
						$selected_program_code = $reg_data[0]['program_code'];
						$selected_center_code = $reg_data[0]['center_code'];
						$venue_batch_code = $reg_data[0]['batch_code'];
						$selected_training_type = $reg_data[0]['training_type'];
						$selected_venue_code	= $reg_data[0]['venue_code'];		
						$sDate = $reg_data[0]['start_date'];
						/* Check Registration Capacity */
						$RegCount = "";
						$RegCount = blendedRegistrationCapacity($selected_program_code,$selected_center_code,$venue_batch_code,$selected_training_type,$selected_venue_code,$sDate);
						
						/* Get Venue Capacity */
						$capacity = "";
						$capacity = getVenueCapacity($selected_program_code,$selected_center_code,$venue_batch_code,$selected_training_type,$selected_venue_code,$sDate);
						
						/* Get User Attempt */
						$attemptQry=$this->db->query("SELECT attempt,fee_flag FROM blended_eligible_master WHERE member_number='".$applicationNo."' AND program_code = '" . $selected_program_code . "' LIMIT 1"); 
						$attemptArr = $attemptQry->row_array();
						$attempt = $attemptArr['attempt'];
						$fee_flag=$attemptArr['fee_flag'];
						/* Check Count of Vitual Attempts */
						$VitualAttemptsCount = "";
						$VitualAttemptsCount = getVitualAttemptsCounts($applicationNo,$selected_program_code,$venue_batch_code);
						if($VitualAttemptsCount != 0)
						{
							$attempt = 1;
						}
							
	
				$attempt = $attempt+1;
						if($RegCount >= $capacity)
						{
							// Refundable
							$blended_data = array('pay_status' => 3, 'attempt'=>$attempt, 'modify_date' => date('Y-m-d H:i:s'));
							$this->master_model->updateRecord('blended_registration',$blended_data,array('blended_id'=>$reg_id));
							/* User Log Activities  */
							$log_title ="Blended Course Registraion - Capacity is full after payment success.";
							$log_message = $log_message = 'Program Code : '.$selected_program_code;
							$rId = $reg_id;
							$regNo = $applicationNo;
							storedUserActivity($log_title, $log_message, $rId, $regNo);
							redirect(base_url().'blended/refund/'.base64_encode($MerchantOrderNo));
						}
						
						/* Update Pay Status and User Attemp Status */
						$blended_data = array('pay_status'=>1, 'attempt'=>$attempt, 'modify_date'=>date('Y-m-d H:i:s'));
                        $this->master_model->updateRecord('blended_registration',$blended_data,array('blended_id'=>$reg_id));
						
						$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_blended_email'));
						if (!empty($applicationNo)) {
                            $user_info = $this->Master_model->getRecords('member_registration',array('regnumber'=> $applicationNo,'isactive'=>'1'),'email,mobile');
                        }
                        if (count($emailerstr) > 0) 
						{
                            /* Set Email Content For user */
                            $Qry=$this->db->query("SELECT program_code, program_name, training_type, center_name, venue_name, start_date, end_date FROM blended_registration WHERE blended_id = '".$reg_id."' LIMIT 1");
                            $detailsArr        = $Qry->row_array();
							$program_code = $detailsArr['program_code'];
                            $program_name = $detailsArr['program_name'];
							$training_type = $detailsArr['training_type'];
							
							if($training_type=="PC"){
								$training_type='Physical Classroom';
                                $venue_name   = $detailsArr['venue_name'];
							}
							else{
								$training_type='Virtual Classes';
                            	$venue_name   = '-';
							}
							$center_name  = $detailsArr['center_name'];
                            $start_date1  = $detailsArr['start_date'];
                            $end_date1    = $detailsArr['end_date'];
                            $start_date   = date("d-M-Y", strtotime($start_date1));
                            $end_date     = date("d-M-Y", strtotime($end_date1));
                            $newstring    = str_replace("#program_name#","".$program_name."",$emailerstr[0]['emailer_text']);
                            $newstring1   = str_replace("#training_type#","".$training_type."",$newstring);
							$newstring2   = str_replace("#center_name#","".$center_name."",$newstring1);
                            $newstring3   = str_replace("#venue_name#","".$venue_name."",$newstring2);
                            $newstring4   = str_replace("#start_date#","".$start_date."",$newstring3);
                            $newstring5   = str_replace("#end_date#", "".$end_date."",$newstring4);
							
							/* Set Email sending options */
							$info_arr          = array(
                               	'to' => $user_info[0]['email'],
							//	'to' => 'kyciibf@gmail.com',
								//'to' => 'bhushan.amrutkar09@gmail.com',
                                'from' => $emailerstr[0]['from'],
                                'subject' => $emailerstr[0]['subject'],
                                'message' => $newstring5
                            );
                            $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo,'pay_txn_id' => $get_user_regnum_info[0]['id']));
							$zone_code = ""; 
							$zoneArr = array();
							//$regno = $this->session->userdata['memberdata']['regno'];
							$zoneArr = $this->master_model->getRecords('blended_registration',array('blended_id'=>$reg_id,'pay_status'=>1),'zone_code,gstin_no');
							$zone_code = $zoneArr[0]['zone_code'];
							
							$gstin_no          = $zoneArr[0]['gstin_no'];
							/* Invoice Number Genarate Functinality */
                            if (count($getinvoice_number) > 0){
								$invoiceNumber = generate_blended_invoice_number($getinvoice_number[0]['invoice_id'],$zone_code);
								if($invoiceNumber){$invoiceNumber = $this->config->item('blended_invoice_T'.$zone_code.'_prefix').$invoiceNumber;}
                                $update_data = array(
                                    'invoice_no' => $invoiceNumber,
                                    //'member_no' => $applicationNo,
                                    'transaction_no' => $transaction_no,
                                    'date_of_invoice' => date('Y-m-d H:i:s'),
                                    'modified_on' => date('Y-m-d H:i:s')
                                );
                                $this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
                                $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
								/* Invoice Genarate Function */
                                $attachpath = genarate_blended_invoice($getinvoice_number[0]['invoice_id'],$zone_code,$program_name);
								/* User Log Activities  */
								$log_title ="Blended Course Registration-Invoice Genarate";
								$log_message = serialize($update_data);
								$rId = $reg_id;
								$regNo = $applicationNo;
								storedUserActivity($log_title, $log_message, $rId, $regNo);
                            }
                            if ($attachpath != '') 
							{	
					/* Email Send To Clints */
					
					if (!empty($applicationNo)) {
                            $reg_info = $this->Master_model->getRecords('blended_registration',array('member_no'=> $applicationNo,'blended_id' => $reg_id));
                        }
						$payment_infoArr=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$reg_info[0]['member_no']),'transaction_no,date,amount');
					
								if($reg_info[0]['member_no'] == $applicationNo)
								{
									$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'blended_emailer_client'));
									if(count($emailerSelfStr) > 0)
									{
										$designation_name=$undergraduate_name=$graduate_name=$postgraduate_name=$specify_qualification=$institution_name=$pay="";
										
										$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
										$graduate=$this->master_model->getRecords('qualification', array('type' => 'GR'));
										$postgraduate=$this->master_model->getRecords('qualification', array('type'=> 'PG'));
										
										$institution_master = $this->master_model->getRecords('institution_master');
										$states             = $this->master_model->getRecords('state_master');
										$designation        = $this->master_model->getRecords('designation_master');
										if(count($designation)){
										 foreach($designation as $designation_row){
											if($reg_info[0]['designation']==$designation_row['dcode']){
												$designation_name = $designation_row['dname'];}
												} 
											}
										if(count($institution_master)){
										  foreach($institution_master as $institution_row){ 	
											if($reg_info[0]['associatedinstitute']==$institution_row['institude_id']){
												$institution_name = $institution_row['name'];}
											  }
											}
										
										if($reg_info[0]['qualification']=='U'){$undergraduate_name = 'Under Graduate';}
										if($reg_info[0]['qualification']=='G'){$graduate_name = 'Graduate';}
										if($reg_info[0]['qualification']=='P'){$postgraduate_name = 'Post Graduate';}	
										
										$qualificationArr=$this->master_model->getRecords('qualification',array('qid'=>$reg_info[0]['specify_qualification']),'name','','1');
										if(count($qualificationArr)) 
										{
											$specify_qualification = $qualificationArr[0]['name'];
										}
										
										$training_type = $reg_info[0]['training_type'];
										if($training_type=="PC")
										{
											$training_type='Physical Classroom';
											$venue_name   = $reg_info[0]['venue_name'];
										}
										else
										{
											$training_type='Virtual Classes';
											$venue_name   = "-";
										}
										$center_name  = $reg_info[0]['center_name'];
										
										$start_date   = date("d-M-Y", strtotime($reg_info[0]['start_date']));
										$end_date     = date("d-M-Y", strtotime($reg_info[0]['end_date']));
										
										if($reg_info[0]['pay_status'] == 1){ $pay = "Success";}
										$selfstr1 = str_replace("#regnumber#", "".$reg_info[0]['member_no']."",  $emailerSelfStr[0]['emailer_text']);
										$selfstr2 = str_replace("#program_name#", "".$reg_info[0]['program_name']."",  $selfstr1);
										$selfstr3 = str_replace("#center_name#", "".$center_name."",  $selfstr2);
										$selfstr4 = str_replace("#venue_name#", "".$venue_name."", $selfstr3);
										$selfstr5 = str_replace("#start_date#", "".$start_date."", $selfstr4);
										$selfstr6 = str_replace("#end_date#", "".$end_date."",  $selfstr5);
										
										$selfstr7 = str_replace("#training_type#", "".$training_type."",  $selfstr6);	
										$selfstr8 = str_replace("#name#", "".$reg_info[0]['namesub']." ".$reg_info[0]['firstname']." ".$reg_info[0]['middlename']." ".$reg_info[0]['lastname'],  $selfstr7);
										$selfstr9 = str_replace("#address1#", "".$reg_info[0]['address1']."",  $selfstr8);
										$selfstr10 = str_replace("#address2#", "".$reg_info[0]['address2']."",  $selfstr9);
										$selfstr11 = str_replace("#address3#", "".$reg_info[0]['address3']."",  $selfstr10);
										$selfstr12 = str_replace("#address4#", "".$reg_info[0]['address4']."",  $selfstr11);
										
										$selfstr13 = str_replace("#district#", "".$reg_info[0]['district']."",  $selfstr12);
										$selfstr14 = str_replace("#city#", "".$reg_info[0]['city']."",  $selfstr13);
										$selfstr15 = str_replace("#state#", "".$reg_info[0]['state']."",  $selfstr14);
										$selfstr16 = str_replace("#pincode#", "".$reg_info[0]['pincode']."",  $selfstr15);
										$selfstr17 = str_replace("#designation#", "".$designation_name."",  $selfstr16);
										$selfstr18 = str_replace("#associatedinstitute#", "".$institution_name."",  $selfstr17);
										$selfstr19 = str_replace("#dateofbirth#", "".$reg_info[0]['dateofbirth']."",  $selfstr18);
										$selfstr20 = str_replace("#email#", "".$reg_info[0]['email']."",  $selfstr19);
										$selfstr21 = str_replace("#mobile#", "".$reg_info[0]['mobile']."",  $selfstr20);
										$selfstr22 = str_replace("#res_stdcode#", "".$reg_info[0]['res_stdcode']."",  $selfstr21);
										$selfstr23 = str_replace("#residential_phone#", "".$reg_info[0]['residential_phone']."",  $selfstr22);
										$selfstr24 = str_replace("#stdcode#", "".$reg_info[0]['stdcode']."",  $selfstr23);
										$selfstr25 = str_replace("#office_phone#", "".$reg_info[0]['office_phone']."",  $selfstr24);
										$selfstr26 = str_replace("#qualification#", "".$undergraduate_name.$graduate_name.$postgraduate_name."",  $selfstr25);
										$selfstr27 = str_replace("#specify_qualification#", "".$specify_qualification."",  $selfstr26);
										$selfstr28 = str_replace("#emergency_name#", "".$reg_info[0]['emergency_name']."",  $selfstr27);
										$selfstr29 = str_replace("#emergency_contact_no#", "".$reg_info[0]['emergency_contact_no']."",  $selfstr28);
										$selfstr30 = str_replace("#blood_group#", "".$reg_info[0]['blood_group']."",  $selfstr29);
										$selfstr31 = str_replace("#TRANSACTION_NO#", "".$payment_infoArr[0]['transaction_no']."",  $selfstr30);
										$selfstr32 = str_replace("#AMOUNT#", "".$payment_infoArr[0]['amount']."",  $selfstr31);
										$selfstr33 = str_replace("#STATUS#", "Transaction Successful",  $selfstr32);
										$final_selfstr = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_infoArr[0]['date']))."",  $selfstr33);
									$s1 = str_replace("#program_code#", "".$reg_info[0]['program_code'],$emailerSelfStr[0]['subject']);
									  $final_sub = str_replace("#Batch_code#", "".$reg_info[0]['batch_code']."",  $s1);
										/* Get Client Emails Details */
										$emailsQry = $this->db->query("SELECT emails FROM offline_email_master WHERE zone_code = '" . $reg_info[0]['zone_code'] . "' AND program_code = '" . $reg_info[0]['program_code'] . "' AND batch_code = '" . $reg_info[0]['batch_code'] . "'AND isdelete = 0 LIMIT 1 ");
										$emailsArr    = $emailsQry->row_array();
										$emails  = $emailsArr['emails'];	
									
										$self_mail_arr = array(
										'to'=>$emails,
										'from'=>$emailerSelfStr[0]['from'],
										'subject'=>$final_sub,
										'message'=>$final_selfstr);	
										$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);
									}
								}
								/* SMS Sending Code */
								$sms_newstring = str_replace("#program_name#", "" . $program_name . "", $emailerstr[0]['sms_text']);
								//$this->master_model->send_sms($user_info[0]['mobile'], $sms_newstring);
								$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']),$sms_newstring,'Xb5EFSwGg');
								
                                if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
                                    redirect(base_url() . 'blended/acknowledge/');
                                } else { redirect(base_url() . 'blended/acknowledge/');}
                            } else {
                                redirect(base_url() . 'blended/acknowledge/');
                            }
                        }
                    }
                }
            }
            redirect(base_url() . 'blended/acknowledge/');
        } else {
            die("Please try again...");
        }
    }
	
	/* Payment Fail */
    public function sbitransfail()
    {
    	die();
        delete_cookie('regid');
        if (isset($_REQUEST['encData'])) {
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('sbi_m_key');
            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $encData              = $aes->decrypt($_REQUEST['encData']);
            $responsedata         = explode("|", $encData);
            $MerchantOrderNo      = $responsedata[0];
            $transaction_no       = $responsedata[1];
            $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(
                'receipt_no' => $MerchantOrderNo
            ), 'ref_id,status');
            if ($get_user_regnum_info[0]['status'] != 0 && $get_user_regnum_info[0]['status'] == 2) {
                if (isset($_REQUEST['merchIdVal'])) {$merchIdVal = $_REQUEST['merchIdVal'];}
                if (isset($_REQUEST['Bank_Code'])) {$Bank_Code = $_REQUEST['Bank_Code'];}
                if (isset($_REQUEST['pushRespData'])) {$encData = $_REQUEST['pushRespData'];}
                $update_data = array(
                    'transaction_no' => $transaction_no,
                    'status' => 0,
                    'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
                    'auth_code' => '0399',
                    'bankcode' => $responsedata[8],
                    'paymode' => $responsedata[5],
                    'callback' => 'B2B'
                );
                $this->master_model->updateRecord('payment_transaction', $update_data, array(
                    'receipt_no' => $MerchantOrderNo
                ));
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
            }
			$this->session->set_flashdata('flsh_msg', 'You have declined your transaction...!');
			redirect(base_url() . 'blended');
            //echo "Transaction failed";
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
        } else {
            die("Please try again...");
        }
    }
	
	/* Get Training Type By Program Code */
	public function getTrainingType()
	{
		$training_typeData = '';
		$program_code = $this->input->post('program_code');
		$regnumberHidden = $this->input->post('regnumberHidden');
		
		/* Check Member Selected Program Validations */
		$validateQry = $this->db->query("SELECT member_number FROM blended_eligible_master WHERE member_number='".$regnumberHidden."' AND program_code='".$program_code."' LIMIT 1"); 
		$validateMemberNo = $validateQry->row_array();
		if(!empty($validateMemberNo))
		{
			$this->db->where('program_code', $program_code);
			$this->db->where('isdelete', 0);
			$DateArr = $this->master_model->getRecords('blended_dates', '', 'DISTINCT(training_type)');
			if(!empty($DateArr))
			{
				$training_typeData .= "<select class='form-control' id='training_type' name='training_type' onchange='getDates(this.value);' required>";
				$training_typeData .= "<option value=''>- Select Training Type -</option>";
				foreach ($DateArr as $dkey => $dValue) 
				{
					
					$training_type   = $dValue['training_type'];
					if($training_type == 'PC')
					 { $training_type = 'Physical Classroom';}
					else
					{ $training_type = 'Virtual Classes';}
					
					$training_typeData .= "<option value=".$dValue['training_type'].">".$training_type."</option>";
				}
				$training_typeData .= "</select>";
				
				echo $training_typeData;
			}
		}
		else
		{
			$programs = array();
			$this->db->where('program_code', $program_code);
			$this->db->where('isdeleted', 0);
			$programs = $this->master_model->getRecords('blended_program_master','','program_name');
			foreach ($programs as $Bkey => $BValue) {
				$program_name   = $BValue['program_name'];
			}
			
			$this->session->set_flashdata('flsh_msg', 'You are not eligible to apply for '.$program_name.' ..!');
			//redirect(base_url() . 'blended'); 
			echo $training_typeData = '1';
		}
	}
	
	/* Get Multiple/Single Date*/
	public function getDates()
	{
		$program_code = $this->input->post('program_code');
        $training_type  = $this->input->post('training_type');
		$regnumberHidden = $this->input->post('regnumberHidden');
		$trainingDateData = '';
		
		/* Check Program Activations */
		$current_date  = date("Y-m-d H:i:s");
		$programs = array();
		$this->db->where('program_code', $program_code);
		$this->db->where('program_reg_from_date <=', $current_date);
		$this->db->where('program_reg_to_date >=', $current_date);
		$this->db->where('program_activation_delete', 0);
		$programs = $this->master_model->getRecords('blended_program_activation_master','','batch_code');
		foreach ($programs as $Bkey => $BValue) 
		{
			$batch_code[]   = $BValue['batch_code'];
		}
		
		/* Check Program Dates Activations */
		$this->db->where('program_code', $program_code);
		$this->db->where('training_type', $training_type);
		$this->db->where_in('batch_code', $batch_code);
		$this->db->where('isdelete', 0);
		$DateArr = $this->master_model->getRecords('blended_dates', '', 'start_date,end_date,batch_code,center_name');
		//echo $this->db->last_query();
		//exit;
		if(!empty($DateArr))
		{
			$trainingDateData .= "<select class='form-control' id='training_date' name='training_date' onchange='get_batch();getFees();getCenters();' required>";
			$trainingDateData .= "<option value=''>- Select Training Date -</option>";
			foreach ($DateArr as $dkey => $dValue) 
			{
				$start_date = date("d-M-Y", strtotime($dValue['start_date']));
				$end_date   = date("d-M-Y", strtotime($dValue['end_date']));
				$batch_code   = $dValue['batch_code'];		
				$center_name   = $dValue['center_name'];				
				$trainingDateData .= "<option value=".$dValue['start_date']."~".$dValue['end_date']." data-id=".$batch_code.">".$start_date." To ".$end_date." ( ".$center_name." )</option>";
			}
			$trainingDateData .= "</select>";
			
			$regnumberHidden = $this->input->post('regnumberHidden');
			
			/* Check single registrations attempt counts */
			$AttemptsCount = "";
			$AttemptsCount = getAttemptsCounts($regnumberHidden,$program_code,$batch_code);
			if($AttemptsCount == 1)
			{
				$this->session->set_flashdata('flsh_msg', 'You have already applied to this course..!');
				$trainingDateData = "1";
			}
			
			/* Check 2 times registrations attempt count */
			$TotalAttemptsCounts = getTotalAttemptsCounts($regnumberHidden,$program_code);
			/*if($TotalAttemptsCounts == 2)
			{
				$this->session->set_flashdata('flsh_msg', 'You have already applied Blended Courses two times..!');
				$trainingDateData = "1";
			}*/
			echo $trainingDateData;
		}
		else
		{
			echo $trainingDateData = "";
		}
		
		
	}
	
	/* Get Fees*/
	public function getFees()
	{
		$fee_amount = '';
		$program_code = $this->input->post('program_code');
        $training_type  = $this->input->post('training_type');
		$regnumberHidden = $this->input->post('regnumberHidden');
		$batch_code = $this->input->post('batch_code');
		
		/* Get Attempt */
		$attemptQry=$this->db->query("SELECT attempt,fee_flag FROM blended_eligible_master WHERE member_number='".$regnumberHidden."' AND program_code = '" . $program_code . "' LIMIT 1 "); 
		$attemptArr = $attemptQry->row_array();
		$attempt   = $attemptArr['attempt'];
		$fee_flag=$attemptArr['fee_flag'];
		
		$TotalAttemptsCounts = getTotalAttemptsCounts($regnumberHidden,$program_code);
		if($TotalAttemptsCounts > 2)
		{
			$attemptQry=$this->db->query("SELECT attempt,fee_flag FROM blended_eligible_master WHERE member_number='".$regnumberHidden."' AND program_code = '" . $program_code . "' LIMIT 1 "); 
			$attemptArr = $attemptQry->row_array();
			$attempt    = $attemptArr['attempt'];
			$fee_flag   = $attemptArr['fee_flag'];
		}
		else
		{
			/* Check Count of Vitual Attempts */
			$VitualAttemptsCount = "";
			$VitualAttemptsCount = getVitualAttemptsCounts($regnumberHidden,$program_code,$batch_code);
			if($VitualAttemptsCount != 0)
			{
				$attempt = 1;
			}/*elseif($fee_flag==1 && $VitualAttemptsCount==0)
			{
				$attempt = 2;
			}*/
		}
		/* Get Fees */
		$this->db->where('fee_delete', 0);
		$this->db->where('program_code', $program_code);
		$this->db->where('batch_code', $batch_code);
		$this->db->where('training_type', $training_type);
		$this->db->where('attempt', $attempt);
		$FeesArr = $this->master_model->getRecords('blended_fee_master', '', 'fee_amount');
		foreach ($FeesArr as $Fkey => $FValue) 
		{	
			if($FValue["fee_amount"] != '0'){
				$fee_amount = '<strong>'.$FValue["fee_amount"].' + GST as applicable</strong>';
			}
			else{
				$fee_amount .= '<strong>'.$FValue['fee_amount'].'</strong>';
			}
			echo $fee_amount;
		}
	}
	
/* Get Centers By Program Code */
    function getCenters()
    {
        $centerData   = '';
		  $total_reg =$centerCodeArr=$centerCodeAr=array();
        $program_code = $this->input->post('program_code');
		$regnumberHidden = $this->input->post('regnumberHidden');
		$batch_code = $this->input->post('batch_code');
		//check capacity
		
		    $this->db->where('pay_status', 1);
            $this->db->where('program_code', $program_code);
			$this->db->where('batch_code', $batch_code);
            $total_reg = $this->master_model->getRecords('blended_registration');
	
        if ($program_code) 
		{	
            $this->db->where('isdeleted', 0);
            $this->db->where('program_code', $program_code);
			$this->db->where('batch_code', $batch_code);
            $centerCodeArr = $this->master_model->getRecords('blended_venue_master', '', 'center_code,capacity');
			
			
			if(count($total_reg)>=$centerCodeArr[0]['capacity'])
			{
				//echo '*********';
					$centerData= 'capacity full';	
					
				//$this->session->set_flashdata('flsh_msg', 'Course capacity is full , Please select another training date.');	
				//redirect(base_url() . 'blended'); 
			}else
			{
				
            foreach ($centerCodeArr as $codekey => $codeValue) 
			{
                $center_code[] = $codeValue['center_code'];
            }
            if (!empty($center_code)) 
			{
                $this->db->where('center_delete', 0);
                $this->db->group_by('center_code');
                $this->db->where_in('center_code', $center_code);
                $centerArr = $this->master_model->getRecords('offline_center_master', '', 'center_code,center_name');
				
				$centerData .= "<select class='form-control' id='center' name='center' onchange='javascript:getVenue(this.value);'>";
        $centerData .= "<option value=''>- Select Center -</option>";
				foreach ($centerArr as $ckey => $cValue) {
                    $centerData .= "<option value=" . $cValue['center_code'] . ">" . $cValue['center_name'] . "</option>";
                }
				$centerData .= "</select>";
			}
			}
            echo $centerData;
			
			
        }
    }
	
	/* Get Venue Details By Center Code */
    function getVenue()
    {
        $venueData    = '';
        $program_code = $this->input->post('program_code');
        $center_code  = $this->input->post('center_code');
		$training_type  = $this->input->post('training_type');
		$batch_code  = $this->input->post('batch_code');
		
        if ($program_code != '' && $center_code != '' && $batch_code != '') 
		{
            $this->db->where('isdeleted', 0);
            $this->db->where('program_code', $program_code);
            $this->db->where('center_code', $center_code);
			$this->db->where('batch_code', $batch_code);
			$this->db->where('training_type', $training_type);
			$centerArr = $this->master_model->getRecords('blended_venue_master', '', 'venue_code,venue_name,start_date,end_date,batch_code');
			foreach ($centerArr as $ckey => $cValue) 
			{
                $start_date = date("d-M-Y", strtotime($cValue['start_date']));
                $end_date   = date("d-M-Y", strtotime($cValue['end_date']));
                $venuecode  = $cValue['venue_code'];
				$batch_code  = $cValue['batch_code'];
                $venueData .= "<p><strong>" . $cValue['venue_name'] . "</strong>";
                $venueData .= "</p> ";
                $venueData .= "<input type='hidden' name='venue_code' id='venue_code' value='" . $venuecode . "'/>";
            }
			
			$regnumberHidden = $this->input->post('regnumberHidden');
			
			/* Check single registrations attempt counts */
			$AttemptsCount = "";
			$AttemptsCount = getAttemptsCounts($regnumberHidden,$program_code,$batch_code);
			if($AttemptsCount == 1)
			{
				$this->session->set_flashdata('flsh_msg', 'You have already applied to this course..!');
				$venueData = "1";
			}
			/* Check 2 times registrations attempt count */
			$TotalAttemptsCounts = getTotalAttemptsCounts($regnumberHidden,$program_code);
			/*if($TotalAttemptsCounts == 2)
			{
				$this->session->set_flashdata('flsh_msg', 'You have already applied Blended Courses two times..!');
				$venueData = "1";
			}*/
            echo $venueData;
        }
    }
	
	/* Showing acknowledge after registration */
    public function acknowledge()
    {
        if (!$this->session->userdata('enduserinfo')) {redirect(base_url());}
        $regno        = $this->session->userdata['memberdata']['regno'];
        $Qry          = $this->db->query("SELECT program_name,center_name,venue_name,training_type,pay_status,start_date,end_date FROM blended_registration WHERE blended_id = '" . $regno . "'  LIMIT 1 ");
        $detailsArr   = $Qry->row_array();
		//echo $this->db->last_query(); exit;
		if(!empty($detailsArr))
		{
			if($detailsArr['pay_status']==0)
			{
					/* User Log Activities  */
				$log_title ="Blended Course Registraion - status not updated after payment .";
				$log_message = $log_message = 'batch Code : '.$detailsArr['batch_code'];
				$rId = $regno;
				$regNo = $detailsArr['member_no'];
				storedUserActivity($log_title, $log_message, $rId, $regNo);
				redirect(base_url() . 'blended/sbitransfail');
				redirect(base_url() . 'blended/sbitransfail');
				
			}else
			{
				$program_name = $detailsArr['program_name'];
				$training_type = $detailsArr['training_type'];
				$center_name  = $detailsArr['center_name'];
				$venue_name   = $detailsArr['venue_name'];
				$start_date1  = $detailsArr['start_date'];
				$end_date1    = $detailsArr['end_date'];
				$start_date   = date("d-M-Y", strtotime($start_date1));
				$end_date     = date("d-M-Y", strtotime($end_date1));
				$data         = array(
					'middle_content' => 'blended/blended_acknowledge',
					'program_name' => $program_name,
					'training_type' => $training_type,
					'center_name' => $center_name,
					'venue_name' => $venue_name,
					'start_date' => $start_date,
					'end_date' => $end_date
				);
				$this->load->view('blended/blended_common_view', $data);
			}
			
		}
		else
		{
		
			redirect(base_url() . 'blended/sbitransfail');
	}
}
	/* User Address Validations */
    public function address1($addressline1)
    {
        if (!preg_match('/^[a-z0-9 .,-\/]+$/i', $addressline1)) {
            $this->form_validation->set_message('address1', "Please enter valid addressline1");
            return false;
        } else {
            return true;
        }
    }
	/* Pincode Validations */
	public function check_checkpin($pincode, $statecode)
    {
        if ($statecode != "" && $pincode != '') {
            $this->db->where("$pincode BETWEEN start_pin AND end_pin");
            $prev_count = $this->master_model->getRecordCount('state_master', array(
                'state_code' => $statecode
            ));
            if ($prev_count == 0) {
                $str = 'Please enter Valid Pincode';
                $this->form_validation->set_message('check_checkpin', $str);
                return false;
            } else
                $this->form_validation->set_message('error', ""); {
                return true;
            }
        } else {
            $str = 'Pincode/State field is required.';
            $this->form_validation->set_message('check_checkpin', $str);
            return false;
        }
    }
	
	public function refund($order_no=NULL)
	{
		
		$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>base64_decode($order_no)));
		
		if(count($payment_info) <=0)
		{
			redirect(base_url().'blended/');
		}
		$data=array('middle_content'=>'blended/blended_refund','payment_info'=>$payment_info);
		$this->load->view('blended/blended_common_view',$data);
	
	}
	
	/* Captcha Validations */
	public function check_captcha_userreg($code)
    {
        if (isset($_SESSION["regcaptcha"])) {
            if ($code == '' || $_SESSION["regcaptcha"] != $code) {
                $this->form_validation->set_message('check_captcha_userreg', 'Invalid %s.');
                return false;
            }
            if ($_SESSION["regcaptcha"] == $code) {
                return true;
            }
        } else {
            return false;
        }
    }
	/* Captcha Validations */
	public function ajax_check_captcha_code()
    {
        $code = $_POST['code'];
		//echo 'session : '.$_SESSION["regcaptcha"];
		//echo 'posted : '.$code;
        if ($code == '' || $_SESSION["regcaptcha"] != $code) {
            echo 'failure';
        } else if ($_SESSION["regcaptcha"] == $code) {
            echo 'success';
        }
    }
	/* Generate Validations */
	public function generatecaptchaajax() 
    {
        /* $this->load->helper('captcha');
        $this->session->unset_userdata("regcaptcha");
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals                   = array(
            'img_path' => './uploads/applications/',
            'img_url' => base_url() . 'uploads/applications/'
        );
        $cap                    = create_captcha($vals);
        $data                   = $cap['image'];
        $_SESSION["regcaptcha"] = $cap['word'];
        echo $data; */
		$this->load->model('Captcha_model');
		echo $captcha_image = $this->Captcha_model->generate_captcha_img('regcaptcha');
    }
	
}
