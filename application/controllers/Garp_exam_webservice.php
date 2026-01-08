<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	header("Access-Control-Allow-Origin: *");
	class Garp_exam extends CI_Controller
	{
    public function __construct()
    //exit;
    {
			parent::__construct();
			$this->load->library('upload');
			$this->load->helper('upload_helper');
			$this->load->helper('master_helper');
			$this->load->helper('general_helper');
			//$this->load->helper('exam_invoice_helper');
			$this->load->model('Master_model');
			$this->load->library('email');
			$this->load->helper('date');
			$this->load->library('email');
			$this->load->model('Emailsending');
			$this->load->model('log_model');
		}
		
    /* Showing Garp_exam Form */
    public function index()
    {
			//exit;
			/* Get Programs */
			$current_date = date("Y-m-d H:i:s");
			$this->db->join('exam_activation_master b', 'a.exam_code = b.exam_code', 'right');
			$this->db->where('b.exam_from_date <=', $current_date);
			$this->db->where('b.exam_to_date >=', $current_date);
			$this->db->where('a.exam_code ', 1018);
			$programs = $this->master_model->getRecords('exam_master a');
			if (empty($programs)) 
			{
				$data = array(
				'middle_content' => 'garp_exam_closed'
				);
				$this->load->view('garp_exam/common_view', $data);
			} 
			else 
			{
				$flag       = 1;
				$var_errors = '';
				$valcookie  = register_get_cookie();
				if ($valcookie) 
				{
					$regid     = $valcookie;
					$checkuser = $this->master_model->getRecords('member_registration', array(
					'regid' => $regid,
					'regnumber !=' => '',
					'isactive !=' => '0'
					));
					if (count($checkuser) > 0) 
					{
						delete_cookie('regid');
					} 
					else 
					{
						$checkpayment = $this->master_model->getRecords('payment_transaction', array(
						'ref_id' => $regid,
						'status' => '2'
						));
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
				
				if ($this->session->userdata('enduserinfo')) 
				{
					$this->session->unset_userdata('enduserinfo');
				}
				
				$row              = $validateMemberNo = array();
				$selectedMemberId = '';
				if (isset($_POST['btnGetDetails'])) 
				{
					$selectedMemberId = ltrim(rtrim($_POST['regnumber']));
					if ($selectedMemberId != '')  /* Check User Eligiblity */
					{
						
						$config = array(
						array(
						'field' => 'regnumber',
						'label' => 'Registration/Membership No.',
						'rules' => 'trim|xss_clean|required'
						),
						array(
						'field' => 'code',
						'label' => 'Code',
						'rules' => 'trim|required|xss_clean|callback_check_captcha_userreg',
						),
						);
						$this->form_validation->set_rules($config);
						
						if($this->form_validation->run()==TRUE)
						{
							if ($selectedMemberId != '')  /* Check User Eligiblity */
							{
								$row = $this->validateMember($selectedMemberId); 
							}
						}
						
						else{
							//$this->session->set_flashdata('error','Invalid Captcha');
							//redirect(base_url().'Garp_exam');
							$row = array("msg" => $this->session->set_flashdata('error'));
						}
						
						
					} 
					else 
					{ 
						$row = array("msg" => "The Membership No field is required.");
					}
				} 
				else 
				{
					$password                  = $var_errors = '';
					$data['validation_errors'] = '';
					/* Check Server-Side Validations */
					if (isset($_POST['btnSubmit'])) {
						$scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputidproof1 = $state = '';
						$this->form_validation->set_rules('regnumber', 'Membership No.', 'trim|required|xss_clean');
						$this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
						//$this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
						$this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|xss_clean');
						//   $this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required|xss_clean|callback_address1[Addressline1]');
						//   $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
						//   $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
						//   $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
						//   if ($this->input->post('state') != '') {$state = $this->input->post('state');}
						//   $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');
						$this->form_validation->set_rules('dob1', 'Date of Birth', 'trim|required|xss_clean');
						$this->form_validation->set_rules('optedu', 'Qualification', 'trim|required|xss_clean');
						if (isset($_POST['middlename'])) {
							$this->form_validation->set_rules('middlename', 'Middle Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
						}
						if (isset($_POST['lastname'])) {
							$this->form_validation->set_rules('lastname', 'Last Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
						}
						if (isset($_POST['optedu']) && $_POST['optedu'] == 'CAIIB') {
							$this->form_validation->set_rules('optedu', 'qualification', 'trim|required|xss_clean');
							} else if (isset($_POST['optedu']) && $_POST['optedu'] == 'JAIIB') {
							$this->form_validation->set_rules('optedu', 'qualification', 'trim|required|xss_clean');
						}
						//   if (isset($_POST['addressline2']) && $_POST['addressline2'] != '') {
						//     $this->form_validation->set_rules('addressline2', 'Addressline2', 'trim|max_length[30]|required|callback_address1[Addressline2]|xss_clean');
						//   }
						//   if (isset($_POST['addressline3']) && $_POST['addressline3'] != '') {
						//     $this->form_validation->set_rules('addressline3', 'Addressline3', 'trim|max_length[30]|required|callback_address1[Addressline3]|xss_clean');
						//   }
						//   if (isset($_POST['addressline4']) && $_POST['addressline4'] != '') {
						//     $this->form_validation->set_rules('addressline4', 'Addressline4', 'trim|max_length[30]|required|callback_address1[Addressline4]|xss_clean');
						//   }
						//$this->form_validation->set_rules('stdcode', 'STD Code', 'trim|max_length[4]|required|numeric|xss_clean');
						//$this->form_validation->set_rules('phone', ' Phone No', 'trim|required|numeric|xss_clean');
						//$this->form_validation->set_rules('res_stdcode', 'Residential STD Code', 'trim|max_length[4]|required|numeric|xss_clean');
						//$this->form_validation->set_rules('residential_phone', 'Residential Phone No', 'trim|required|numeric|xss_clean');
						// if($_POST['registrationtype'] != "NM")
						// {
						//   $this->form_validation->set_rules('staffnumber', 'staffnumber', 'trim|required|xss_clean');
						//   $this->form_validation->set_rules('organisation', 'organisation', 'trim|required|xss_clean');
						//   $this->form_validation->set_rules('department', 'department', 'trim|required|xss_clean');
						// }
						//$this->form_validation->set_rules('gstin_no', 'Bank GSTIN Number', 'trim|alpha_numeric|min_length[15]|xss_clean');
						$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
						$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
						$this->form_validation->set_rules('code', 'Security Code ', 'trim|required|xss_clean|callback_check_captcha_userreg');
						$this->form_validation->set_rules('declaration1', 'I Accept', 'trim|required|xss_clean');
						if ($this->form_validation->run() == TRUE) {
							$resp = array(
							'success' => 0,
							'error' => 0,
							'msg' => ''
							);
							$this->session->unset_userdata('enduserinfo');
							$eduqual1    = $eduqual2 = $eduqual3 = '';
							$date        = date('Y-m-d h:i:s');
							$dob1        = $_POST["dob1"];
							$dob         = str_replace('/', '-', $dob1);
							$dateOfBirth = date('Y-m-d', strtotime($dob));
							$attempt     = 0;
							$fees        = $fee = '';
							$cgst_rate   = $sgst_rate = $igst_rate = $tax_type = '';
							$cgst_amt    = $sgst_amt = $igst_amt = '';
							$cs_total    = $igst_total = '';
							/* Get Fees From Fee Master */
							//$this->db->where('fee_delete', 0);
							$this->db->where('exam_code', $_POST['exam_code']);
							$this->db->where('mem_type', $_POST['registrationtype']);
							$this->db->where('attempt', $attempt);
							/* (0/1) */
							$FeesArr = $this->master_model->getRecords('garp_exam_fee_master');
							foreach ($FeesArr as $Fkey => $FValue) {
								$fee_amount  = $FValue['fee_amount'];
								$sgst_amt    = $FValue['sgst_amt'];
								$cgst_amt    = $FValue['cgst_amt'];
								$cs_tot      = $FValue['cs_tot'];
								$igst_amt    = $FValue['igst_amt'];
								$igst_tot    = $FValue['igst_tot'];
								$member_data = $this->Master_model->getRecords('member_registration', array(
								'regnumber' => $_POST['regnumber'],
								'isactive' => '1'
								), 'state');
								$state       = $member_data[0]['state'];
								if ($state != 'MAH') {
									//  // $igst_rate  = $this->config->item('blended_igst_rate');
									$igst_amt   = $igst_amt;
									$igst_total = $igst_tot;
									$fee        = $igst_tot;
									//         //  $tax_type   = 'Inter';
									$cs_total   = '';
									$cgst_amt   = '';
									$sgst_amt   = '';
									} else {
									// $cgst_rate  = $this->config->item('blended_cgst_rate');
									//  $sgst_rate  = $this->config->item('blended_sgst_rate');
									//$tax_type   = 'Intra';
									$fee        = $cs_tot;
									$cgst_amt   = $cgst_amt;
									$sgst_amt   = $sgst_amt;
									$cs_total   = $cs_tot;
									$igst_amt   = '';
									$igst_total = '';
								}
								$fees = $fee;
								//$fees = $FValue['fee_amount'];
								$preview_fees = $FValue['fee_amount'];
								if ($fees != '0') {
									$preview_fees = $FValue['fee_amount'];
									} else {
									$preview_fees;
								}
							}
							/* Add Form Fields Value in the Session */
							if ($_POST["firstname"] != '') {
								$user_data = array(
								'firstname' => $_POST["firstname"],
								'sel_namesub' => $_POST["sel_namesub"],
								'dob' => $dateOfBirth,
								'eduqual1' => $eduqual1,
								'eduqual2' => $eduqual2,
								'eduqual3' => $eduqual3,
								'email' => $_POST["email"],
								'lastname' => $_POST["lastname"],
								'middlename' => $_POST["middlename"],
								'mobile' => $_POST["mobile"],
								'optedu' => $_POST["optedu"],
								'regnumber' => $_POST["regnumber"],
								'exam_code' => $_POST['exam_code'],
								'exam_period' => $_POST['exam_period'],
								'exam_name' => $_POST['exam_name'],
								//'qualification' => $_POST['qualification'],
								'fees' => $fees,
								'preview_fees' => $preview_fees,
								'registrationtype' => $_POST['registrationtype']
								);
								/* Stored User Details In The Session */
								$this->session->set_userdata('enduserinfo', $user_data);
								$this->form_validation->set_message('error', "");
								/* User Log Activities  */
								$log_title   = " GARP-FRR Registration-Preview Page";
								$log_message = serialize($user_data);
								$rId         = '';
								$regNo       = $_POST["regnumber"];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								redirect(base_url() . 'Garp_exam/preview');
								/* Sent to Preview Page */
								} else {
								$var_errors = str_replace("<p>", "<span>", $var_errors);
								$var_errors = str_replace("</p>", "</span><br>", $var_errors);
							}
						}
					}
				}
				$states = $this->master_model->getRecords('state_master');
				$this->db->where('designation_master.designation_delete', '0');
				$designation  = $this->master_model->getRecords('designation_master');
				/* Get Programs */
				$current_date = date("Y-m-d H:i:s");
				$this->load->helper('captcha');
				//   $this->session->set_userdata("regcaptcha", rand(1, 100000));
				//   $vals                   = array(
				//     'img_path' => './uploads/applications/',
				//     'img_url' => base_url() . 'uploads/applications/'
				//   );
				//   $cap                    = create_captcha($vals);
				//   $_SESSION["regcaptcha"] = $cap['word'];
				$this->load->model('Captcha_model');
				$captcha_image  = $this->Captcha_model->generate_captcha_img('regcaptcha');
				
				if ($flag == 0) {
					$data = array(
					'middle_content' => 'garp_exam_reg'
					);
					$this->load->view('garp_exam/common_view', $data);
					} else {
					$data = array(
					'middle_content' => 'garp_exam_reg',
					'states' => $states,
					'image' => $captcha_image,
					
					'var_errors' => $var_errors,
					'row' => $row
					//'validateMemberNo' => $validateMemberNo
					);
					$this->load->view('garp_exam/common_view', $data);
				}
			}
		}
    /* Validate Member Function */
    function validateMember($selectedMemberId)
    {
			
			$selectedMemberId = '510208326';
			//webservice call
			$curlResponce = $this->isGarpEligible($selectedMemberId);
			
			$validateMemberNo = json_decode($curlResponce);
			
			if(COUNT($validateMemberNo) > 0 ){
			 
			//print_r($validateMemberNo);
			foreach ($validateMemberNo as $exams) {
            //print_r($exams);
            $exam_code=1018;
            $exam_period=997;
            $exam_name= $exams[1];
            $regunmber= $exams[2];
            $attempts=0;
			 
		
				$this->db->order_by('exam_result_date', 'desc');
				$this->db->limit(0, 1);
				$this->db->where('mem_no', $selectedMemberId);
				if ($exam_name == 'CAIIB') {
					$resultdata = $this->master_model->getRecords('caiib_cons_mrk');
					} else {
					$resultdata = $this->master_model->getRecords('jaiib_cons_mrk');
				}
				//echo $this->db->last_query();die;
				if (COUNT($resultdata) > 0) {
					//  echo $resultdata[0]['result_flag']; die;
					if ($resultdata[0]['result_flag'] == 'F') {
						// $row = array("msg" => "You are not eligible to apply this exam!!<br>Please click here <a target='blank_' href='http://www.iibf.org.in/iib_internationalcollab.asp'>http://www.iibf.org.in/iib_internationalcollab.asp</a> for more details");
						$this->session->set_flashdata('flsh_msg', "You are not eligible to apply this exam!!<br>Please click here <a target='blank_' href='http://www.iibf.org.in/iib_internationalcollab.asp'>http://www.iibf.org.in/iib_internationalcollab.asp</a> for more details");
						redirect(base_url() . 'Garp_exam');
					}
				}
				$blendedQry = $this->db->query("SELECT * FROM member_registration  WHERE regnumber = '" . $selectedMemberId . "' AND isactive = '1' LIMIT 1 ");
				$row        = $blendedQry->row_array();
				if (empty($row)) {
					$row = array(
					"msg" => "Please Enter Valid Membership No."
					);
					} else {
					$charterd = $this->db->query("SELECT * FROM garp_exam_registration WHERE member_no = '" . $selectedMemberId . "' AND pay_status ='1'   ");
					$rows     = $charterd->row_array();
					if (!empty($rows)) {
						if ($exam_name == 'JAIIB' && COUNT($rows) > 0) {
							$registration_date = date('Y-m-d', strtotime($rows['createdon']));
							$effectiveDate     = strtotime("+1 months", strtotime($rows['createdon'])); // returns timestamp
							$check_date        = date('Y-m-d', $effectiveDate); // formatted version
							$current_date      = date('Y-m-d');
							//echo $check_date;die;
							if ($current_date < $check_date) {
								$row = array(
								"msg" => " You have already applied in the current window (1st to 15th Jan 2022)."
								);
								//$row = array("msg" => "You have already registered on ".$registration_date.". Please re-enrol after expiry of 1 months from the date already registered.");
							}
							} elseif ($exam_name == 'CAIIB' && COUNT($rows) > 0) {
							//$registration_date=date('Y-m-d',$rows['createdon']); 
							$effectiveDate = strtotime("+1 months", strtotime($rows['createdon'])); // returns timestamp
							$check_date    = date('Y-m-d', $effectiveDate); // formatted version
							$current_date  = date('Y-m-d');
							//echo $check_date;die;
							if ($current_date < $check_date) {
								$row = array(
								"msg" => " You have already applied in the current window (1st to 15th Jan 2022)."
								);
								//$row = array("msg" => "You have already registered on ".$rows['createdon'].". Please re-enrol after expiry of 1 months from the date already registered.");
							}
						}
					}
				}
            
			 }//end of foreach
			} else {
				$row = array(
				"msg" => "You are not eligible to apply this exam!!<br>Please click here <a target='blank_' href='http://www.iibf.org.in/iib_internationalcollab.asp'>http://www.iibf.org.in/iib_internationalcollab.asp</a> for more details"
				);
			}
		//	print_r($row);die;
			return $row;
			// return $validateMemberNo;
		}
		//eligible webservice
		public function isGarpEligible($memberno)
		{
		    //echo "$memberno";die;
			$service_url = 'http://10.10.233.66:8084/garpapi/getExamCodeByMemNo/'.$memberno;
			$curl = curl_init($service_url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$curl_response = curl_exec($curl);
			//print_r($curl_response);
			curl_close($curl);
			
			return $curl_response;
			//exit;
		}
		
		public function isGarpEligible1()
		{
			$service_url = 'http://10.10.233.66:8084/garpapi/getExamCodeByMemNo/100000059;
			//510208326';
			$curl = curl_init($service_url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$curl_response = curl_exec($curl);
			print_r($curl_response);
			curl_close($curl);
			exit;
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
			$states           = $this->master_model->getRecords('state_master');
			/* Check Member Selected Program Validations */
			$status           = ''; 
			
			$curlResponce = $this->isGarpEligible($selectedMemberId);
			
			$validateMemberNo = json_decode($curlResponce);
			
			if(COUNT($validateMemberNo) > 0 ){
			    
				$data = array(
				'middle_content' => 'garp_exam_preview',
				'states' => $states,
				'row' => $row
				);
				$this->load->view('garp_exam/common_view', $data);
        } else {
				$this->session->set_flashdata('flsh_msg', 'You are not eligible to apply for this exam!!');
				redirect(base_url() . 'Garp_exam');
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
			$sel_namesub       = $row['namesub'];
			$firstname         = $row['firstname'];
			$middlename        = $row['middlename'];
			$lastname          = $row['lastname'];
			$dob               = $row['dateofbirth'];
			$qualification     = $this->session->userdata['enduserinfo']['exam_name'];
			$email             = $row['email'];
			$mobile            = $row['mobile'];
			$regnumber         = $this->session->userdata['enduserinfo']['regnumber'];
			$exam_code         = $this->session->userdata['enduserinfo']['exam_code'];
			$exam_period       = $this->session->userdata['enduserinfo']['exam_period'];
			$Fees              = $this->session->userdata['enduserinfo']['fees'];
			$registrationtype  = $this->session->userdata['enduserinfo']['registrationtype'];
			$insert_info       = array(
			'member_no' => $regnumber,
			'namesub' => $sel_namesub,
			'firstname' => $firstname,
			'middlename' => $middlename,
			'lastname' => $lastname,
			'dateofbirth' => date('Y-m-d', strtotime($dob)),
			'qualification' => $qualification,
			'email' => $email,
			'mobile' => $mobile,
			'exam_code' => $exam_code,
			'fee' => $Fees,
			'registrationtype' => $registrationtype,
			'exam_period' => $exam_period,
			'createdon' => date('Y-m-d H:i:s')
			);
			$data_instert      = array(
			'regnumber' => $regnumber,
			'exam_code' => $exam_code,
			'exam_fee' => $Fees,
			'exam_period' => $exam_period,
			'exam_medium' => 'E',
			'exam_center_code' => 991,
			//'exam_center_code' => 991,
			// 'exam_center_code' => 991,
			'created_on' => date('Y-m-d H:i:s')
			);
			//echo "<pre>"; print_r($insert_info); echo "</pre>";exit;
			/*Payment Check Code - Bhushan */
			$check_payment_val = check_payment_status($regnumber);
			if ($check_payment_val == 1) {
				//redirect(base_url() . 'Home/Payment_process');
				$msg  = '<h4> Your transaction is in process. Please wait for some time.</h4>';
				$data = array(
				'middle_content' => 'member_notification',
				'msg' => $msg
				);
				$this->load->view('common_view', $data);
        } else {
				$insert_id = '';
				/* Stored user details and selected field details in the database table */
				if ($lastid = $this->master_model->insertRecord('member_exam', $data_instert, true)) {
					$insert_id   = $lastid;
					$insert_info = array(
					'mem_exam_id' => $insert_id,
					'member_no' => $regnumber,
					'namesub' => $sel_namesub,
					'firstname' => $firstname,
					'middlename' => $middlename,
					'lastname' => $lastname,
					'dateofbirth' => date('Y-m-d', strtotime($dob)),
					'qualification' => $qualification,
					'email' => $email,
					'mobile' => $mobile,
					'exam_code' => $exam_code,
					'fee' => $Fees,
					'exam_period' => $exam_period,
					'registrationtype' => $registrationtype,
					'createdon' => date('Y-m-d H:i:s')
					);
					$last_id     = $this->master_model->insertRecord('garp_exam_registration', $insert_info, true);
					/* Get User Attempt */
					//$insert_id=$this->session->userdata['enduserinfo']['lastid'];
					$attempt     = 0;
					$upd_files   = array();
					/* User Log Activities  */
					$log_title   = "GARP registration-Add Member";
					$log_message = serialize($insert_info);
					$rId         = $last_id;
					$regNo       = $regnumber;
					storedUserActivity($log_title, $log_message, $rId, $regNo);
					$userarr = array(
					'regno' => $last_id,
					'email' => $email,
					'regnumber' => $regnumber,
					'exam_code' => $exam_code,
					'memex_id' => $lastid
					);
					$this->session->set_userdata('memberdata', $userarr);
					/* Get Attempts Count */
					$AttemptsCount = $TotalAttemptsCounts = "";
					//$AttemptsCount = getAttemptsCounts($regnumber,$exam_code);
					//$TotalAttemptsCounts = getTotalAttemptsCounts($regnumber,$exam_code);
					if ($AttemptsCount == 1) {
						$this->session->set_flashdata('flsh_msg', 'You have already applied to this course...!');
						redirect(base_url() . 'Garp_exam/index');
						} else {
						/* Call Make Payment Function */
						redirect(base_url() . "Garp_exam/make_payment");
					}
					} else {
					$userarr = array(
					'regno' => '',
					'email' => '',
					'regnumber' => '',
					'exam_code' => '',
					'memex_id' => ''
					);
					$this->session->set_userdata('memberdata', $userarr);
					$this->session->set_flashdata('flsh_msg', 'Error while during registration.please try again!');
					redirect(base_url() . 'Garp_exam/index');
				}
			}
		}
    /* Payment Function */
    public function make_payment()
    {
			$cgst_rate   = $sgst_rate = $igst_rate = $tax_type = '';
			$cgst_amt    = $sgst_amt = $igst_amt = '';
			$cs_total    = $igst_total = '';
			$getstate    = $getcenter = $getfees = array();
			$center_name = $center_code = $regno = $regnumber = $exam_code = $amount = $state = $exempt = $state_no = $state_name = '';
			$flag        = 1;
			$insert_id   = $this->session->userdata['memberdata']['memex_id'];
			$regno       = $this->session->userdata['memberdata']['regno'];
			$regnumber   = $this->session->userdata['memberdata']['regnumber'];
			$exam_code   = $this->session->userdata['memberdata']['exam_code'];
			if ($regno == "" || $regnumber == "" || $exam_code == "") {
				/* User Log Activities  */
				$log_title   = " GARP Exam Registration-Session Expired";
				$log_message = 'Exam Code : ' . $this->session->userdata['memberdata']['exam_code'];
				$rId         = $this->session->userdata['memberdata']['regno'];
				$regNo       = $this->session->userdata['memberdata']['regnumber'];
				storedUserActivity($log_title, $log_message, $rId, $regNo);
				$this->session->set_flashdata('flsh_msg', 'Your session has been expired. Please try again...!');
				redirect(base_url() . 'Garp_exam');
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
					'ref_id' => $insert_id,
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
			if (isset($_POST['processPayment']) && $_POST['processPayment']) {
				register_set_cookie($regno);
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key            = $this->config->item('sbi_m_key');
				$merchIdVal     = $this->config->item('sbi_merchIdVal');
				$AggregatorId   = $this->config->item('sbi_AggregatorId');
				$pg_success_url = base_url() . "Garp_exam/sbitranssuccess";
				$pg_fail_url    = base_url() . "Garp_exam/sbitransfail";
				/* Get User State Details */
				if (!empty($regnumber)) {
					$member_data = $this->Master_model->getRecords('member_registration', array(
					'regnumber' => $regnumber,
					'isactive' => '1'
					), 'state');
					$state       = $member_data[0]['state'];
					//$state_code = $member_data[0]['state_code'];
				}
				/* Get State Details */
				/* Get User Attempt */
				$reg_type              = $this->db->query("SELECT registrationtype FROM member_registration WHERE regnumber='" . $regnumber . "' LIMIT 1");
				$mem_reg_type          = $reg_type->row_array();
				$mem_registration_type = $mem_reg_type['registrationtype'];
				$attempt               = 0;
				/* Check Count of Vitual Attempts */
				/*elseif($fee_flag==1 && $VitualAttemptsCount==0)
					
					
					
					{
					
					
					
					$attempt=2;
					
					
					
				}*/
				/* Get Fee Details By exempt, exam_code, batch_code & training_type*/
				// if(!empty($exam_code) && !empty($exempt) )
				if (!empty($exam_code)) {
					$feeMasterArr = $this->master_model->getRecords('garp_exam_fee_master', array(
					'fee_delete' => '0',
					'exam_code' => $exam_code,
					'attempt' => $attempt,
					'mem_type' => $mem_registration_type
					)); //echo $this->db->last_query(); die;
				}
				/* Set Up Fees */
				$fee_amount = $feeMasterArr[0]['fee_amount'];
				$sgst_amt   = $feeMasterArr[0]['sgst_amt'];
				$cgst_amt   = $feeMasterArr[0]['cgst_amt'];
				$cs_tot     = $feeMasterArr[0]['cs_tot'];
				$igst_amt   = $feeMasterArr[0]['igst_amt'];
				$igst_tot   = $feeMasterArr[0]['igst_tot'];
				if ($state != 'MAH') {
					$igst_rate  = $this->config->item('garp_igst_rate');
					$igst_amt   = $igst_amt;
					$igst_total = $igst_tot;
					$amount     = $igst_tot;
					$tax_type   = 'Inter';
					$cs_total   = '';
					$cgst_amt   = '';
					$sgst_amt   = '';
					} else {
					$cgst_rate  = $this->config->item('garp_cgst_rate');
					$sgst_rate  = $this->config->item('garp_sgst_rate');
					$tax_type   = 'Intra';
					$amount     = $cs_tot;
					$cgst_amt   = $cgst_amt;
					$sgst_amt   = $sgst_amt;
					$cs_total   = $cs_tot;
					$igst_amt   = '';
					$igst_total = '';
				}
				/* Stored details in the Payment Transaction table */
				$insert_data     = array(
				'member_regnumber' => $regnumber,
				'gateway' => "sbiepay",
				'amount' => $amount,
				'date' => date('Y-m-d H:i:s'),
				'ref_id' => $insert_id,
				'exam_code' => $this->session->userdata['enduserinfo']['exam_code'],
				'description' => "GARP-FRR Exam Registration",
				'pay_type' => 2,
				'status' => 2,
				'pg_flag' => 'IIBF_EXAM_O'
				);
				$pt_id           = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
				$MerchantOrderNo = sbi_exam_order_id($pt_id);
				$custom_field    = $MerchantOrderNo . "^iibfexam^iibftrg^" . $regnumber;
				$update_data     = array(
				'receipt_no' => $MerchantOrderNo,
				'pg_other_details' => $custom_field
				);
				$this->master_model->updateRecord('payment_transaction', $update_data, array(
				'id' => $pt_id
				));
				/* Stored Details in the Exam Invoice table */
				//$blended_service_code = $this->config->item('blended_service_code');
				$getstate             = $this->master_model->getRecords('state_master', array(
				'state_code' => $state
				));
				$invoice_insert_array = array(
				'pay_txn_id' => $pt_id,
				'receipt_no' => $MerchantOrderNo,
				'member_no' => $regnumber,
				// 'state_of_center' => $getstate[0]['state_code'],
				//'center_name' => $selected_center_name,
				//'center_code' => $selected_center_code,
				'app_type' => 'O',
				'service_code' => '999294',
				'qty' => '1',
				'state_code' => $getstate[0]['state_no'],
				'state_name' => $getstate[0]['state_name'],
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
				'exam_code' => $this->session->userdata['enduserinfo']['exam_code'],
				'exam_period' => $this->session->userdata['enduserinfo']['exam_period'],
				'exempt' => $exempt,
				'created_on' => date('Y-m-d H:i:s')
				);
				// echo "<pre> invoice_insert_array => "; print_r($invoice_insert_array); echo "</pre>";
				//exit;
				$inser_id             = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array);
				$MerchantCustomerID   = $regno;
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
        } else {
				$this->load->view('pg_sbi/make_payment_page');
			}
		}
    /* Payment Success And Invoice genrate */
    public function sbitranssuccess()
    {
			//delete_cookie('regid');
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
				if (isset($_REQUEST['merchIdVal'])) {
					$merchIdVal = $_REQUEST['merchIdVal'];
				}
				if (isset($_REQUEST['Bank_Code'])) {
					$Bank_Code = $_REQUEST['Bank_Code'];
				}
				if (isset($_REQUEST['pushRespData'])) {
					$encData = $_REQUEST['pushRespData'];
				}
				$q_details = sbiqueryapi($MerchantOrderNo);
				if ($q_details) {
					if ($q_details[2] == "SUCCESS") {
						$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(
						'receipt_no' => $MerchantOrderNo
						), 'ref_id,member_regnumber,status,id');
						//print_r($get_user_regnum_info);die;
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
							$this->master_model->updateRecord('payment_transaction', $update_data, array(
							'receipt_no' => $MerchantOrderNo
							));
							/* Transaction Log */
							$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
							//$this->log_model->loggarptransaction("sbiepay", $pg_response, $responsedata[2]);
							$newlog      = array(
							'date' => date("Y-m-d H:i:s"),
							'title' => 'step1-payment transaction table updated suucessfully',
							'description' => 'transaction no = ' . $transaction_no . ' , receipt no= ' . $MerchantOrderNo . ' , memberexamid' . $reg_id
							);
							$this->master_model->insertRecord('garp_tracklogs', $newlog);
							$this->log_model->loggarptransaction("sbiepay", $pg_response, $responsedata[2]);
							//echo $reg_id; die;
							$memberexam_data = array(
							'pay_status' => 1,
							'modified_on' => date('Y-m-d H:i:s')
							);
							//$regno=$this->session->userdata['memberdata']['regno'];
							$this->master_model->updateRecord('member_exam', $memberexam_data, array(
							'id' => $reg_id
							));
							$newlog1            = array(
							'date' => date("Y-m-d H:i:s"),
							'title' => 'step2- member exam table updated suucessfully',
							'description' => 'memberexamid = ' . $reg_id . ' - this payment status upadated'
							);
							$insert_logs        = $this->master_model->insertRecord('garp_tracklogs', $newlog1);
							$reg_data           = $this->Master_model->getRecords('garp_exam_registration table', array(
							'member_no' => $applicationNo,
							'mem_exam_id' => $reg_id
							), 'exam_code');
							$selected_exam_code = $reg_data[0]['exam_code'];
							$newlog2            = array(
							'date' => date("Y-m-d H:i:s"),
							'title' => 'step3- get data from registration table',
							'description' => 'member no = ' . $applicationNo . ' ,mem_exam_id =' . $reg_id . ' - get data from garp_exam_registration table'
							);
							$insert_logs        = $this->master_model->insertRecord('garp_tracklogs', $newlog2);
							/* Get User Attempt */
							$attempt            = 0;
							$newlog3            = array(
							'date' => date("Y-m-d H:i:s"),
							'title' => 'step4- get data from eligible table',
							'description' => 'member no = ' . $applicationNo . ' ,exam_code =' . $selected_exam_code . ' - get data from garp_exam_eligible table'
							);
							//$insert_logs        = $this->master_model->insertRecord('garp_tracklogs', $newlog3);
							$attempt            = $attempt + 1;
							/* Update Pay Status and User Attemp Status */
							$blended_data       = array(
							'pay_status' => 1,
							'attempt' => $attempt,
							'modify_date' => date('Y-m-d H:i:s')
							);
							// $memberexam_data = array('pay_status'=>1,  'modified_on'=>date('Y-m-d H:i:s'));
							//$regno=$this->session->userdata['memberdata']['regno'];
							$this->master_model->updateRecord('garp_exam_registration', $blended_data, array(
							'mem_exam_id' => $reg_id,
							'member_no' => $applicationNo
							));
							//$this->master_model->updateRecord('member_exam',$memberexam_data,array('id'=>$reg_id));
							$newlog4     = array(
							'date' => date("Y-m-d H:i:s"),
							'title' => 'step5- update data to garp_exam_registration table',
							'description' => 'member no = ' . $applicationNo . ' ,mem_exam_id =' . $reg_id . ' - table updated with pay status and attempt'
							);
							$insert_logs = $this->master_model->insertRecord('garp_tracklogs', $newlog4);
							$emailerstr  = $this->master_model->getRecords('emailer', array(
							'emailer_name' => 'garp_email'
							));
							if (!empty($applicationNo)) {
								$user_info = $this->Master_model->getRecords('member_registration', array(
								'regnumber' => $applicationNo,
								'isactive' => '1'
								), 'email,mobile');
							}
							if (count($emailerstr) > 0) {
								/* Set Email Content For user */
								$Qry               = $this->db->query("SELECT exam_code, qualification FROM garp_exam_registration WHERE mem_exam_id = '" . $reg_id . "' LIMIT 1");
								$detailsArr        = $Qry->row_array();
								$exam_code         = $detailsArr['exam_code'];
								$exam_name         = 'GARP Exam'; //$detailsArr['qualification'];
								$newstring         = str_replace("#exam_name#", "" . $exam_name . "", $emailerstr[0]['emailer_text']);
								/* Set Email sending options */
								$info_arr          = array(
								'to' => '' . $user_info[0]['email'] . ',iibfdevp@esds.co.in,swati.watpade@esds.co.in',
								//'to' => 'swati.watpade@esds.co.in',
								'from' => $emailerstr[0]['from'],
								'subject' => $emailerstr[0]['subject'],
								'message' => $newstring
								);
								$getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
								'receipt_no' => $MerchantOrderNo,
								'pay_txn_id' => $get_user_regnum_info[0]['id']
								));
								$zone_code         = "";
								$zoneArr           = array();
								//$regno = $this->session->userdata['memberdata']['regno'];
								/* Invoice Number Genarate Functinality */
								if (count($getinvoice_number) > 0) {
									$invoiceNumber = generate_GARP_invoice_number($getinvoice_number[0]['invoice_id']);
									if ($invoiceNumber) {
										$invoiceNumber = $this->config->item('garp_invoice_no_prefix') . $invoiceNumber;
									}
									$update_data = array(
									'invoice_no' => $invoiceNumber,
									//'member_no' => $applicationNo,
									'transaction_no' => $transaction_no,
									'date_of_invoice' => date('Y-m-d H:i:s'),
									'modified_on' => date('Y-m-d H:i:s')
									);
									$this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
									$this->master_model->updateRecord('exam_invoice', $update_data, array(
									'receipt_no' => $MerchantOrderNo
									));
									/* Invoice Genarate Function */
									$attachpath = genarate_garp_exam_invoice($getinvoice_number[0]['invoice_id']);
									$this->Emailsending->mailsend_attch($info_arr, $attachpath);
									/* User Log Activities  */
									$log_title   = "GARP-FRR Exam-Invoice Genarate";
									$log_message = serialize($update_data);
									$rId         = $reg_id;
									$regNo       = $applicationNo;
									storedUserActivity($log_title, $log_message, $rId, $regNo);
								}
								if ($attachpath != '') {
									/* Email Send To Clints */
									if (!empty($applicationNo)) {
										$reg_info = $this->Master_model->getRecords('garp_exam_registration', array(
										'member_no' => $applicationNo,
										'mem_exam_id' => $reg_id
										));
									}
									$payment_infoArr = $this->master_model->getRecords('payment_transaction', array(
									'receipt_no' => $MerchantOrderNo,
									'member_regnumber' => $reg_info[0]['member_no']
									), 'transaction_no,date,amount');
									if ($reg_info[0]['member_no'] == $applicationNo) {
										$emailerSelfStr = $this->master_model->getRecords('emailer', array(
										'emailer_name' => 'garp_emailer_client'
										));
										if (count($emailerSelfStr) > 0) {
											$designation_name = $undergraduate_name = $graduate_name = $postgraduate_name = $specify_qualification = $institution_name = $pay = "";
											$qualification    = $reg_info[0]['qualification'];
											$exam_name        = 'GARP Exam'; //$detailsArr['qualification'];
											if ($reg_info[0]['pay_status'] == 1) {
												$pay = "Success";
											}
											$selfstr1      = str_replace("#regnumber#", "" . $reg_info[0]['member_no'] . "", $emailerSelfStr[0]['emailer_text']);
											$selfstr2      = str_replace("#exam_name#", "" . $exam_name . "", $selfstr1);
											$selfstr8      = str_replace("#name#", "" . $reg_info[0]['namesub'] . " " . $reg_info[0]['firstname'] . " " . $reg_info[0]['middlename'] . " " . $reg_info[0]['lastname'], $selfstr2);
											$selfstr9      = str_replace("#address1#", "" . $reg_info[0]['address1'] . "", $selfstr8);
											$selfstr10     = str_replace("#address2#", "" . $reg_info[0]['address2'] . "", $selfstr9);
											$selfstr11     = str_replace("#address3#", "" . $reg_info[0]['address3'] . "", $selfstr10);
											$selfstr12     = str_replace("#address4#", "" . $reg_info[0]['address4'] . "", $selfstr11);
											$selfstr13     = str_replace("#district#", "" . $reg_info[0]['district'] . "", $selfstr12);
											$selfstr14     = str_replace("#city#", "" . $reg_info[0]['city'] . "", $selfstr13);
											$selfstr15     = str_replace("#state#", "" . $reg_info[0]['state'] . "", $selfstr14);
											$selfstr16     = str_replace("#pincode#", "" . $reg_info[0]['pincode'] . "", $selfstr15);
											$selfstr19     = str_replace("#dateofbirth#", "" . $reg_info[0]['dateofbirth'] . "", $selfstr16);
											$selfstr20     = str_replace("#email#", "" . $reg_info[0]['email'] . "", $selfstr19);
											$selfstr21     = str_replace("#mobile#", "" . $reg_info[0]['mobile'] . "", $selfstr20);
											$selfstr26     = str_replace("#qualification#", "" . $reg_info[0]['qualification'] . "", $selfstr21);
											$selfstr31     = str_replace("#TRANSACTION_NO#", "" . $payment_infoArr[0]['transaction_no'] . "", $selfstr26);
											$selfstr32     = str_replace("#AMOUNT#", "" . $payment_infoArr[0]['amount'] . "", $selfstr31);
											$selfstr33     = str_replace("#STATUS#", "Transaction Successful", $selfstr32);
											$final_selfstr = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_infoArr[0]['date'])) . "", $emailerSelfStr[0]['subject']);
											// $s1 = str_replace("#exam_code#", "".$reg_info[0]['exam_code'],$emailerSelfStr[0]['subject']);
											$final_sub     = str_replace("#exam_code#", "" . $reg_info[0]['exam_code'] . "", $final_selfstr);
											/* Get Client Emails Details */
											// $emailsQry = $this->db->query("SELECT emails FROM offline_email_master WHERE  exam_code = '" . $reg_info[0]['exam_code'] . "'AND isdelete = 0 LIMIT 1 ");
											// $emailsArr    = $emailsQry->row_array();
											// $emails  = $emailsArr['emails'];  
											$self_mail_arr = array(
											//'to'=>$emails,
											'to' => 'iibfdevp@esds.co.in',
											'from' => $emailerSelfStr[0]['from'],
											'subject' => $final_sub,
											'message' => $final_selfstr
											);
											// $this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);
										}
									}
									} else {
									redirect(base_url() . 'Garp_exam/Garp_exam_acknowledge/');
								}
							}
						}
					}
				} //$q
				redirect(base_url() . 'Garp_exam/Garp_exam_acknowledge/');
        } else {
				die("Please try again...");
			}
		}
    /* Payment Fail */
    public function sbitransfail()
    {
			// delete_cookie('regid');
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
					if (isset($_REQUEST['merchIdVal'])) {
						$merchIdVal = $_REQUEST['merchIdVal'];
					}
					if (isset($_REQUEST['Bank_Code'])) {
						$Bank_Code = $_REQUEST['Bank_Code'];
					}
					if (isset($_REQUEST['pushRespData'])) {
						$encData = $_REQUEST['pushRespData'];
					}
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
					$this->log_model->loggarptransaction("sbiepay", $pg_response, $responsedata[2]);
				}
				$this->session->set_flashdata('flsh_msg', 'You have declined your transaction...!');
				redirect(base_url() . 'Garp_exam');
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
    /* Get Fees*/
    public function getFees()
    {
			$fee_amount          = '';
			$exam_code           = $this->input->post('exam_code');
			/* Get Attempt */
			$attempt             = 0;
			$TotalAttemptsCounts = '';
			if ($TotalAttemptsCounts > 2) {
				$attempt    = 0;
        } else {
				/* Check Count of Vitual Attempts */
				$VitualAttemptsCount = "";
				$VitualAttemptsCount = getVitualAttemptsCounts($regnumberHidden, $exam_code, $batch_code);
				if ($VitualAttemptsCount != 0) {
					$attempt = 1;
				}
				/*elseif($fee_flag==1 && $VitualAttemptsCount==0)
					
					
					
					{
					
					
					
					$attempt = 2;
					
					
					
				}*/
			}
			/* Get Fees */
			$this->db->where('fee_delete', 0);
			$this->db->where('exam_code', $exam_code);
			$this->db->where('batch_code', $batch_code);
			$this->db->where('training_type', $training_type);
			$this->db->where('attempt', $attempt);
			$FeesArr = $this->master_model->getRecords('garp_exam_fee_master', '', 'fee_amount');
			foreach ($FeesArr as $Fkey => $FValue) {
				if ($FValue["fee_amount"] != '0') {
					$fee_amount = '<strong>' . $FValue["fee_amount"] . ' + GST as applicable</strong>';
					} else {
					$fee_amount .= '<strong>' . $FValue['fee_amount'] . '</strong>';
				}
				echo $fee_amount;
			}
		}
    /* Showing acknowledge after registration */
    public function Garp_exam_acknowledge()
    {
			$exam_name = 'GARP Exam';
			$data      = array(
			'middle_content' => 'garp_exam_acknowledge',
			'exam_name' => $exam_name
			);
			$this->load->view('garp_exam/common_view', $data);
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
    public function refund($order_no = NULL)
    {
			$payment_info = $this->master_model->getRecords('payment_transaction', array(
			'receipt_no' => base64_decode($order_no)
			));
			if (count($payment_info) <= 0) {
				redirect(base_url() . 'Garp_exam/');
			}
			$data = array(
			'middle_content' => 'blended/blended_refund',
			'payment_info' => $payment_info
			);
			$this->load->view('garp_exam/common_view', $data);
		}
    
		/* Captcha Validations */
    public function check_captcha_userreg($code)
    {
			if ($code != '' && $_SESSION["regcaptcha"] == $code) 
			{
				return true;
			}
			else
			{
				$this->form_validation->set_message('check_captcha_userreg', 'Invalid Captcha %s.');
				return false;
			} 
		}
		/* Captcha Validations */
		
    public function ajax_check_captcha_code()
    {
			$code = $_POST['code'];
			if ($code == '' || $_SESSION["regcaptcha"] != $code) {
				echo 'failure';
        } else if ($_SESSION["regcaptcha"] == $code) {
				echo 'success';
			}
		}
    /* Generate Validations */
    public function generatecaptchaajax()
    {
			$session_name = 'regcaptcha';
			if (isset($_POST['session_name']) && $_POST['session_name'] != "") {
				$session_name = $this->security->xss_clean(trim($this->input->post('session_name')));
			}
			$this->load->model('Captcha_model');
			echo $captcha_img = $this->Captcha_model->generate_captcha_img($session_name);
		}
    public function set_session_garp_redirect_edit_profile_ajax()
    {
			$_SESSION["GARP_REDIRECT_EDIT_PROFILE_FLAG"]     = 1;
			$_SESSION["GARP_REDIRECT_EDIT_PROFILE_VALIDITY"] = date('Y-m-d H:i', strtotime('+5 minutes', strtotime(date("Y-m-d H:i"))));
			$result['flag']                                  = "success";
			echo json_encode($result);
		}
	} 	