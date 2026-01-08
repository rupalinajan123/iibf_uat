<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Professional_bankers extends CI_Controller
{
	public function __construct()
	{
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
		$this->load->model('log_model');
		$this->load->model('KYC_Log_model');
		$this->load->model('billdesk_pg_model');
		$this->chk_session->Check_mult_session();

		$this->exam_period = '997';
		$this->group_code = 'B1_1';
		$this->valid_exam_code_arr = array('1021', '1022', '1023', '1024', '1025');
		//echo base64_encode(1025);
	}

	public function index($enc_exm_cd = 0)
	{
		$exm_cd_result = $this->validate_exm_cd($enc_exm_cd);
		if (count($exm_cd_result) > 0 && isset($exm_cd_result['flag']) && $exm_cd_result['flag'] == 'success') {
			redirect(site_url('professional_bankers/login/' . $enc_exm_cd));
		} else {
			//echo 'Redirect to website link';
			redirect('https://iibf.org.in/CertificateProfessionalBanker.asp');
		}
	}

	public function login($enc_exm_cd = 0) //START : LOGIN
	{
		//START : VALIDATE EXAM CODE IN LINK
		$exm_cd_result = $this->validate_exm_cd($enc_exm_cd);
		if (count($exm_cd_result) > 0 && isset($exm_cd_result['flag']) && $exm_cd_result['flag'] == 'success') {
		} else {
			redirect(site_url('professional_bankers/index/' . $enc_exm_cd));
		}
		//END : VALIDATE EXAM CODE IN LINK

		$exm_cd = base64_decode($enc_exm_cd);

		//START : check session already started for user
		$login_type = $this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_TYPE');
		$login_regnumber = $this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_REGNUMBER');
		$login_exam_code = $this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_EXAM_CODE');

		if (isset($login_type) && $login_type == 'PROFESSIONAL_BANKERS' && isset($login_regnumber) && $login_regnumber != '' && isset($login_exam_code) && $login_exam_code != '' && in_array($login_exam_code, $this->valid_exam_code_arr)/*  && $login_exam_code == $exm_cd */) {
			redirect(site_url('professional_bankers/dashboard'));
		} else {
			$this->unset_session_data();
		}
		//END : check session already started for user

		$data = array();
		$data['error'] = '';
		$data['enc_exm_cd'] = $enc_exm_cd;
		$data['exm_cd'] = $exm_cd;

		if (isset($_POST) && count($_POST) > 0) {
			$this->form_validation->set_rules('exam_code', 'Exam Name', 'trim|required|callback_validate_exam_code|xss_clean', array('required' => 'Please select the %s'));
			$this->form_validation->set_rules('Username', 'Username', 'trim|required|callback_validate_member_no[1]|xss_clean', array('required' => 'Please enter the %s'));
			$this->form_validation->set_rules('code', 'Code', 'trim|required|callback_validate_captcha_code[1]|xss_clean', array('required' => 'Please enter the %s'));

			if ($this->form_validation->run() == TRUE) {
				$member_no = $this->security->xss_clean(trim($this->input->post('Username')));
				$exam_code = base64_decode($this->security->xss_clean(trim($this->input->post('exam_code'))));

				$user_data = array('PROFESSIONAL_BANKERS_LOGIN_REGNUMBER' => $member_no, 'PROFESSIONAL_BANKERS_LOGIN_TYPE' => 'PROFESSIONAL_BANKERS', 'PROFESSIONAL_BANKERS_LOGIN_EXAM_CODE' => $exam_code);
				$this->session->set_userdata($user_data);

				redirect(site_url('professional_bankers/dashboard'));
			}
		}

		$this->load->model('Captcha_model');
		$captcha_img = $this->Captcha_model->generate_captcha_img('PROFESSIONAL_BANKERS_CAPTCHA');
		$data['image'] = $captcha_img;

		/* $this->db->where_in('exam_code',array('1021', '1022', '1023', '1024', '1025'));
			$data['pb_exam_data'] = $pb_exam_data = $this->master_model->getRecords('exam_master',array(), 'id, exam_name, exam_code, description'); */

		$this->db->limit(1);
		$this->db->where_in('exam_code', array('1021', '1022', '1023', '1024', '1025'));
		$data['disp_exam_data'] = $disp_exam_data = $this->master_model->getRecords('exam_master', array('exam_code' => $exm_cd), 'id, exam_name, exam_code, description');
		if (count($disp_exam_data) == 0) {
			redirect(site_url('professional_bankers/index/' . $enc_exm_cd));
		}

		$this->load->view('professional_bankers/login', $data);
	} //END : LOGIN

	public function validate_exm_cd($enc_exm_cd = 0) //START : VALIDATE EXAM CODE PASS IN URL
	{
		$result['flag'] = 'error';
		if ($enc_exm_cd != '0') {
			$exm_cd = base64_decode($enc_exm_cd);
			if (in_array($exm_cd, $this->valid_exam_code_arr)) {
				$result['flag'] = 'success';
			}
		}

		return $result;
	} //END : VALIDATE EXAM CODE PASS IN URL

	public function generatecaptchaajax() //START : GENERATE CAPTCHA CODE AJAX
	{
		$this->load->model('Captcha_model');
		echo $captcha_img = $this->Captcha_model->generate_captcha_img('PROFESSIONAL_BANKERS_CAPTCHA');
	} //END : GENERATE CAPTCHA CODE AJAX	

	public function validate_captcha_code($code_str = "", $type = "0") //START : CAPTCHA CODE VALIDATION FOR SERVER SIDE AND CLIENT SIDE // 0 => Ajax, 1=>Server
	{
		$session_name = 'PROFESSIONAL_BANKERS_CAPTCHA';
		$session_captcha = $_SESSION[$session_name];

		if (isset($_POST) && count($_POST) > 0) {
			$captcha_code = $this->security->xss_clean(trim($this->input->post('code')));

			if ($captcha_code == $session_captcha) {
				if ($type == '0') {
					echo "true";
				} else if ($type == '1') {
					return TRUE;
				}
			} else {
				if ($type == '0') {
					echo "false";
				} else if ($type == '1') {
					$this->form_validation->set_message('validate_captcha_code', 'Please enter correct code');
					return FALSE;
				}
			}
		} else {
			if ($type == '0') {
				echo "false";
			} else if ($type == '1') {
				$this->form_validation->set_message('validate_captcha_code', 'Please enter correct code');
				return FALSE;
			}
		}
	} //END : CAPTCHA CODE VALIDATION FOR SERVER SIDE AND CLIENT SIDE	

	public function validate_exam_code($str = "") //START : EXAM VALIDATION FOR SERVER SIDE
	{
		$error_msg = 'Invalid request';
		$return_val = FALSE;

		if (isset($_POST) && count($_POST) > 0) {
			$exam_code = base64_decode($this->security->xss_clean(trim($this->input->post('exam_code'))));

			if (in_array($exam_code, $this->valid_exam_code_arr)) {
				$return_val = TRUE;
			} else {
				$error_msg = 'Invalid exam code selection';
			}
		}

		$this->form_validation->set_message('validate_exam_code', $error_msg);
		return $return_val;
	} //END : MEMBER NUMBER VALIDATION FOR SERVER SIDE AND CLIENT SIDE

	public function validate_member_no($str = "", $type = "0") //START : MEMBER NUMBER VALIDATION FOR SERVER SIDE AND CLIENT SIDE // type = 0 => Ajax, 1=>Server
	{
		$error_msg = 'Invalid request';
		$flag = 'error';
		$return_val = FALSE;

		if (isset($_POST) && count($_POST) > 0) {
			$member_no = $this->security->xss_clean(trim($this->input->post('Username')));
			$exam_code = base64_decode($this->security->xss_clean(trim($this->input->post('exam_code'))));

			$response_res = $this->validate_member_by_api($exam_code, $member_no);
			if ($response_res['flag'] == 'success') {
				$flag = 'success';
				$error_msg = "";
				$return_val = TRUE;
			} else {
				$error_msg = $response_res['error_msg'];
			}
		}

		if ($type == '0') {
			$result['flag'] = $flag;
			$result['response'] = $error_msg;
			echo json_encode($result);
		} else if ($type == '1') {
			$this->form_validation->set_message('validate_member_no', $error_msg);
			return $return_val;
		}
	} //END : MEMBER NUMBER VALIDATION FOR SERVER SIDE AND CLIENT SIDE		

	function validate_member_by_api($exam_code = 0, $member_no = 0)
	{
		$error_msg = '';
		$flag = 'error';

		$response_res = $this->bankers_api_curl($exam_code, $member_no);
		//echo '<pre>'; print_r($response); echo '</pre>'; exit;
		if (count($response_res) > 0) {
			$response_flag = '';
			if (isset($response_res['response'])) {
				$response_flag = $response_res['response'];
			}

			if ($response_flag == 'success') {
				$response_msg_str = '';
				if (isset($response_res['response_msg'])) {
					$response_msg_str = $response_res['response_msg'];
				}

				if ($response_msg_str != "") {
					$response_msg_arr = json_decode($response_msg_str, true);
					$response_msg_arr = $response_msg_arr[0];
					//echo '<pre>'; print_r($response_msg_arr); echo '</pre>'; exit;

					if (count($response_msg_arr) > 0) {
						$res_ex_cd = $res_mem_no = $is_member_eligible = '';
						if (isset($response_msg_arr[0])) {
							$res_ex_cd = $response_msg_arr[0];
						}
						if (isset($response_msg_arr[1])) {
							$res_mem_no = $response_msg_arr[1];
						}
						if (isset($response_msg_arr[2])) {
							$is_member_eligible = $response_msg_arr[2];
						}

						if ($res_ex_cd == $exam_code && $res_mem_no == $member_no && $is_member_eligible != "") {
							if ($is_member_eligible == "Y") {
								$flag = 'success';
								$error_msg = "";
							} else {
								$exam_data = $this->get_exam_master_data($exam_code);
								$error_msg = 'You have not passed all the exams required under the ' . $exam_data[0]['description'] . ' Track. You can register for professional banker qualification only after you clear all the exam/s indicated as below,';

								$fail_exam_code_arr = array();
								for ($i = 4; $i <= 14; $i += 2) {
									if (isset($response_msg_arr[$i]) && $response_msg_arr[$i] == "N") {
										if (isset($response_msg_arr[$i - 1])) {
											$fail_exam_code_arr[] = $response_msg_arr[$i - 1];
										}
									}
								}

								$exam_name_arr[$this->config->item('examCodeCaiib')] = 'CAIIB';
								$exam_name_arr['527'] = 'Ethics in Banking';
								$exam_name_arr['529'] = 'Ethics in Banking';
								$exam_name_arr['1019'] = 'Strategic Management & Innovation in Banking';
								$exam_name_arr['1005'] = 'Certified Credit Professional';
								$exam_name_arr['1007'] = 'Risk in Financial Services';
								$exam_name_arr['1006'] = 'Certified Treasury Professional';
								$exam_name_arr['1008'] = 'Certified Accounting & Audit Professionals';
								$exam_name_arr['19'] = 'Certified Banking Compliance Professional';
								$exam_name_arr['1004'] = 'Certificate Exam in Prevention of Cyber Crimes & Fraud management';
								$exam_name_arr['1020'] = 'Certificate Exam in Emerging Technologies';
								$exam_name_arr['1013'] = 'Certificate in Digital Banking';
								$exam_name_arr['1009'] = 'Certificate in Foreign Exchange (FEDAI)';
								$exam_name_arr['11'] = 'Diploma in International Banking & Finance';

								if (count($fail_exam_code_arr) > 0) {
									/* $this->db->where_in('exam_code',$fail_exam_code_arr);
										$fail_exam_data = $this->master_model->getRecords('exam_master',array('exam_delete'=>'0'), 'id, exam_name, exam_code, description, exam_type, exam_instruction_file, member_instruction, nonmember_instruction');
										
										if(count($fail_exam_data) > 0)
										{
											$sr_no = 1;
											foreach($fail_exam_data as $res)
											{
												$error_msg .= '<p style="margin: 5px 0 0 0; line-height: 15px;">'.$sr_no.'. '.$res['description'].'</p>';
												$sr_no++;
											}
										} */

									$sr_no = 1;
									foreach ($fail_exam_code_arr as $fail_exam_code) {
										if (array_key_exists($fail_exam_code, $exam_name_arr)) {
											$error_msg .= '<p style="margin: 5px 0 0 0; line-height: 15px;">' . $sr_no . '. ' . $exam_name_arr[$fail_exam_code] . '</p>';
											$sr_no++;
										}
									}
								}

								$error_msg = rtrim($error_msg, ", ");
							}
						} else {
							$error_msg = "Invalid Membership No.";
						}
					} else {
						$error_msg = "Invalid Membership No.";
					}
				} else {
					$error_msg = "Invalid Membership No.";
				}
			} else {
				$error_msg = "Invalid Membership No.";
				if (isset($response_res['response_msg'])) {
					$error_msg = $response_res['response_msg'];
				}
			}
		} else {
			$error_msg = "Invalid Membership No.";
		}

		$result['flag'] = $flag;
		$result['error_msg'] = $error_msg;
		return $result;
	}

	function test_api()
	{
		// $exam_code = '1021';
		$exam_code = '1022';
		$member_no = $member_number = '510033933';
		$return_flag = 1;
		$response_res = $this->bankers_api_curl($exam_code, $member_no);
		echo '<pre>';
		print_r($response_res);
		echo '</pre>';
		exit;
	}

	function bankers_api_curl($exam_code = 0, $member_no = 0)
	{
		if (base_url() == 'https://iibf.teamgrowth.net/' || base_url() == 'http://iibf.teamgrowth.net/') {
			$api_response_data = $this->master_model->getRecords('professional_banker_api_response_temp', array('exam_code' => $exam_code, 'member_no' => $member_no), 'response_msg');

			$final_arr = array();
			if (count($api_response_data) > 0) {
				$final_arr['response'] = 'success';
				$final_arr['response_msg'] = $api_response_data[0]['response_msg'];
			}

			return $response = $final_arr;
		} else {
			return $response = $this->master_model->professional_bankers_api_curl($exam_code, $member_no);
		}
	}

	public function dashboard() //START : DASHBOARD
	{
		$login_regnumber = $this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_REGNUMBER');
		$login_exam_code = $this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_EXAM_CODE');
		$member_data = $this->check_user_validity_after_login();

		$data['login_regnumber'] = $login_regnumber;
		$data['member_data'] = $member_data;
		$data['exam_data'] = $this->get_exam_master_data($login_exam_code);
		$data['member_exam_application_data'] = $member_exam_application_data = $this->get_member_exam_application_details($login_regnumber);
		//echo '<pre>'; print_r($member_exam_application_data); echo '</pre>'; //exit;

		$fee_details = $this->getProfessionalBankerCreditFee($this->exam_period, $login_exam_code, $this->group_code, $member_data[0]['registrationtype'], 'MAH'); //$member_data[0]['state']

		/* if($member_data[0]['state'] == 'MAH') { $fee_amount = $fee_details['cs_tot']; }
			else { $fee_amount = $fee_details['igst_tot']; } */

		$upload_path = "uploads/professional_bankers";

		$fee_amount = $fee_details['cs_tot'];
		$data['fee_amount'] = $fee_amount;

		if (isset($_POST['professional_bankers'])) {
			if (count($member_exam_application_data) > 0) {
				$this->chk_member_validation_for_exam_application($member_exam_application_data);
			}

			if ($member_exam_application_data[0]['PaymentStatus'] == 1 && $member_exam_application_data[0]['kyc_status'] == 2 && $_POST['professional_bankers'] == 're-upload') // payment completed, but kyc is rejected
			{
				//Allow only to update experience certificate
				if (empty($_FILES['exp_cert']['name'])) {
					$this->form_validation->set_rules('exp_cert', 'Experience Certificate', 'required', array('required' => 'Please select the %s'));
				} else {
					$exp_cert_name = '';
					if ($_FILES['exp_cert']['name'] != "") {
						$expCertData = $this->upload_file("exp_cert", array('pdf'), "pb_exp_cert_" . $login_regnumber . "_" . $login_exam_code . "_" . date("YmdHis"), "./" . $upload_path, "pdf", '', '', '', '', '5120');

						if ($expCertData['response'] == 'error') {
							$data['exp_cert_error'] = $expCertData['message'];
							$exp_cert_error_flag = 1;
						} else if ($expCertData['response'] == 'success') {
							$exp_cert_name = $expCertData['message'];
							//echo "<br>".$expCertData['message'];
						}

						if ($exp_cert_error_flag == 0 && $exp_cert_name != "" && file_exists($upload_path . "/" . $exp_cert_name)) {
							$up_pb_data['exp_cert'] = $exp_cert_name;
							$up_pb_data['kyc_status'] = '0';
							$up_pb_data['remark'] = '';
							$up_pb_data['modified_on'] = date('Y-m-d H:i:s');
							$this->master_model->updateRecord('professional_banker_registrations', $up_pb_data, array('pb_reg_id' => $member_exam_application_data[0]['pb_reg_id']));

							$this->session->set_flashdata('success', 'Experience certificate uploaded successfully');
							redirect(site_url('professional_bankers/dashboard'));
						}
					} else {
						$this->session->set_flashdata('error', 'Invalid parameter');
						redirect(site_url('professional_bankers/dashboard'));
					}
				}
			} else if ($_POST['professional_bankers'] == 'pay_now') {
				$this->form_validation->set_rules('fee_amount', 'Fee Amount', 'trim|required|xss_clean', array('required' => 'Please enter the %s'));

				if (empty($_FILES['exp_cert']['name'])) {
					$this->form_validation->set_rules('exp_cert', 'Experience Certificate', 'required', array('required' => 'Please select the %s'));
				}

				if ($this->form_validation->run() == TRUE) {
					$posted_fee_amount = $this->security->xss_clean(trim($this->input->post('fee_amount')));

					$add_exp_cert = '';
					if ($fee_amount > 0 && $fee_amount == $posted_fee_amount) {
						if ($_FILES['exp_cert']['name'] != "") {
							$expCertData = $this->upload_file("exp_cert", array('pdf'), "pb_exp_cert_" . $login_regnumber . "_" . $login_exam_code . "_" . date("YmdHis"), "./" . $upload_path, "pdf", '', '', '', '', '5120');

							if ($expCertData['response'] == 'error') {
								$data['exp_cert_error'] = $expCertData['message'];
								$exp_cert_error_flag = 1;
							} else if ($expCertData['response'] == 'success') {
								$add_exp_cert = $expCertData['message'];
								//echo "<br>".$expCertData['message'];
							}
						}

						if ($exp_cert_error_flag == 0 && $add_exp_cert != "" && file_exists($upload_path . "/" . $add_exp_cert)) {
							//echo '<pre>'; print_r($_POST); print_r($_FILES);echo '</pre>'; exit;

							$add_data['regnumber'] = $login_regnumber;
							$add_data['exam_code'] = $login_exam_code;
							$add_data['exam_mode'] = '';
							$add_data['exam_medium'] = 'E';
							$add_data['exam_period'] = $this->exam_period;
							$add_data['exam_center_code'] = '306';
							$add_data['exam_fee'] = $fee_amount;
							$add_data['created_on'] = date('Y-m-d H:i:s');

							if ($mem_exam_id = $this->master_model->insertRecord('member_exam', $add_data, true)) {
								$this->session->userdata['PROFESSIONAL_BANKERS_MEMBER_EXAM_ID'] = $mem_exam_id;

								/* User Log Activities */
								$log_title = "Professional Banker - Credit Member exam apply details - Insert Professional_bankers.php";
								$log_message = serialize($inser_array);
								$rId = $login_regnumber;
								$regNo = $login_regnumber;
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								/* Close User Log Activities */

								$add_pb_data['mem_exam_id'] = $mem_exam_id;
								$add_pb_data['exam_code'] = $login_exam_code;
								$add_pb_data['exam_period'] = $this->exam_period;
								$add_pb_data['regnumber'] = $login_regnumber;
								$add_pb_data['amount'] = $fee_amount;
								$add_pb_data['exp_cert'] = $add_exp_cert;
								$add_pb_data['kyc_status'] = '0';
								$add_pb_data['created_on'] = date('Y-m-d H:i:s');
								$pb_reg_id = $this->master_model->insertRecord('professional_banker_registrations', $add_pb_data, true);

								if ($this->config->item('exam_apply_gateway') == 'sbi') {
									redirect(site_url('professional_bankers/sbi_make_payment'));
								} else {
									redirect(site_url('professional_bankers/dashboard'));
								}
							}
						}
					} else {
						$this->session->set_flashdata('error', 'Invalid fee selection');
						redirect(site_url('professional_bankers/dashboard'));
					}
				}
			}
		}

		$data['act_id'] = $data['sub_act_id'] = 'dashboard';

		$rejection_logs = array();
		if (count($member_exam_application_data) > 0) {
			$this->db->order_by('created_on', 'DESC');
			$rejection_logs = $this->master_model->getRecords('professional_banker_rejection_logs', array('pb_reg_id' => $member_exam_application_data[0]['pb_reg_id']), 'log_id, exp_cert, action, remark, created_on');
		}
		$data['rejection_logs'] = $rejection_logs;
		$this->load->view('professional_bankers/dashboard', $data);
	} //END : DASHBOARD

	function chk_member_validation_for_exam_application($member_exam_application_data = array())
	{
		if (count($member_exam_application_data) > 0) {
			if ($member_exam_application_data[0]['PaymentStatus'] == 1 && $member_exam_application_data[0]['kyc_status'] == 1) // payment completed and kyc approved
			{
				$this->session->set_flashdata('error', 'You have already applied for this exam');
				redirect(site_url('professional_bankers/dashboard'));
			} else if ($member_exam_application_data[0]['PaymentStatus'] == 1 && $member_exam_application_data[0]['kyc_status'] == 0) // payment completed, but kyc is pending
			{
				$this->session->set_flashdata('error', 'You have already applied for this exam, but your KYC is pending.');
				redirect(site_url('professional_bankers/dashboard'));
			}
		}
	}

	public function check_user_validity_after_login() //START : CHECK MEMBER VALIDATION AFTER LOGIN
	{
		$login_type = $this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_TYPE');
		$login_regnumber = $this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_REGNUMBER');
		$exam_code = $this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_EXAM_CODE');

		if (isset($login_type) && $login_type == 'PROFESSIONAL_BANKERS' && isset($login_regnumber) && $login_regnumber != '' && isset($exam_code) && $exam_code != '') {
			$response_res = $this->validate_member_by_api($exam_code, $login_regnumber);
			if ($response_res['flag'] == 'success') {
				$member_data = $this->get_member_details($login_regnumber);
				return $member_data;

				/* if(!empty($member_data) && count($member_data) > 0)
					{
						$chk_member_eligibilty = $this->chk_member_eligibilty($login_regnumber);
						
						if(count($chk_member_eligibilty) > 0)
						{ 
							return $member_data;
						}
						else
						{
							redirect(site_url('professional_bankers/logout'));
						}
					}
					else
					{
						redirect(site_url('professional_bankers/logout'));
					} */
			} else {
				redirect(site_url('professional_bankers/logout'));
			}
		} else {
			redirect(site_url('professional_bankers/logout'));
		}
	} //END : CHECK MEMBER VALIDATION AFTER LOGIN

	public function logout() //START : LOGOUT
	{
		$this->unset_session_data();
		redirect(site_url('professional_bankers/login/' . base64_encode($this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_EXAM_CODE'))));
	} //END : LOGOUT

	function unset_session_data() //START : UNSET SESSION DATA
	{
		$user_data = array('PROFESSIONAL_BANKERS_LOGIN_REGNUMBER' => '', 'PROFESSIONAL_BANKERS_LOGIN_TYPE' => '');
		$this->session->set_userdata($user_data);
	} //END : UNSET SESSION DATA

	function get_member_details($member_no = 0) //START : GET MEMBER DETAILS 
	{
		//$this->db->where("(registrationtype='O' OR registrationtype='A' OR registrationtype='F')");
		$member_data = $this->master_model->getRecords('member_registration', array(
			'regnumber' => $member_no,
			'isactive' => '1', 'isdeleted' => '0'
		), 'registrationtype, regid, regnumber, namesub, firstname, middlename, lastname, email, mobile, state, createdon, registrationtype, isactive, usrpassword');
		return $member_data;
	} //END : GET MEMBER DETAILS

	function get_exam_master_data($exam_code = 0) //START : GET EXAM MASTER DATA 
	{
		$exam_data = $this->master_model->getRecords('exam_master', array(
			'exam_code' => $exam_code,
			'exam_delete' => '0'
		), 'id, exam_name, exam_code, description, exam_type, exam_instruction_file, member_instruction, nonmember_instruction');
		return $exam_data;
	} //END : GET EXAM MASTER DATA

	function get_member_exam_application_details($member_no = 0) //START : GET MEMBER SUCCESS EXAM DETAILS 
	{
		$login_exam_code = $this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_EXAM_CODE');

		$this->db->limit(1);
		$this->db->join('payment_transaction pt', 'pt.ref_id = me.id', 'INNER');
		$this->db->join('professional_banker_registrations pbr', 'pbr.mem_exam_id = me.id', 'INNER');
		$exam_info = $this->master_model->getRecords('member_exam me', array(
			'me.regnumber' => $member_no,
			'me.exam_code' => $login_exam_code, 'me.exam_period' => $this->exam_period, 'me.exam_code' => $login_exam_code, 'pt.member_regnumber' => $member_no
		), 'me.id, me.regnumber, me.exam_fee, me.elearning_flag, me.free_paid_flg, me.sub_el_count, pt.id as PtId, pt.amount, pt.date, pt.transaction_no, pt.receipt_no, pt.status AS PaymentStatus, pbr.pb_reg_id, pbr.kyc_status, pbr.exp_cert, pbr.remark', array('me.id' => 'DESC'));
		return $exam_info;
	} //END : GET MEMBER SUCCESS EXAM DETAILS 

	function getProfessionalBankerCreditFee($exam_period = NULL, $excd = NULL, $grp_code = NULL, $memcategory = NULL, $state_code = NULL)
	{
		$result_arr = array();
		$fee_amount = $sgst_amt = $cgst_amt = $igst_amt = $cs_tot = $igst_tot = 0;

		/* echo '<br> exam_period : '.$exam_period;
			echo '<br> excd : '.$excd;
			echo '<br> grp_code : '.$grp_code;
			echo '<br> memcategory : '.$memcategory;
			echo '<br> state_code : '.$state_code; */

		if ($exam_period != NULL  && $excd != NULL  && $grp_code != NULL  && $memcategory != NULL) {
			$getstatedetails = $this->master_model->getRecords('state_master', array('state_code' => $state_code, 'state_delete' => '0'));

			$today_date = date('Y-m-d');
			$this->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
			$getfees = $this->master_model->getRecords('fee_master', array('exam_code' => $excd, 'member_category' => $memcategory, 'exam_period' => $exam_period, 'group_code' => $grp_code, 'exempt' => $getstatedetails[0]['exempt']));
			//echo $this->db->last_query();//exit;

			if (count($getfees) > 0) {
				if ($state_code == 'MAH') {
					$fee_amount = $getfees[0]['fee_amount'];
					$cs_tot = $getfees[0]['cs_tot'];
					$sgst_amt = $getfees[0]['sgst_amt'];
					$cgst_amt = $getfees[0]['cgst_amt'];
				} else {
					$fee_amount = $getfees[0]['fee_amount'];
					$igst_tot = $getfees[0]['igst_tot'];
					$igst_amt = $getfees[0]['igst_amt'];
				}
			}
		}

		$result_arr['fee_amount'] = $fee_amount;
		$result_arr['sgst_amt'] = $sgst_amt;
		$result_arr['cgst_amt'] = $cgst_amt;
		$result_arr['igst_amt'] = $igst_amt;
		$result_arr['cs_tot'] = $cs_tot;
		$result_arr['igst_tot'] = $igst_tot;
		return $result_arr;
	}

	function get_pb_exam_data($pb_exam_id = 0, $regnumber = 0)
	{
		$login_exam_code = $this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_EXAM_CODE');

		$result_data = $this->master_model->getRecords('member_exam me', array('me.id' => $pb_exam_id, 'me.regnumber' => $regnumber, 'me.exam_code' => $login_exam_code, 'me.exam_period' => $this->exam_period,  'me.exam_code' => $login_exam_code), 'me.id, me.regnumber, me.exam_fee', array('id' => 'DESC'), 0, 1);
		return $result_data;
	}

	public function sbi_make_payment()
	{
		$login_regnumber = $this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_REGNUMBER');
		$login_exam_code = $this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_EXAM_CODE');
		$member_exam_application_data = $this->get_member_exam_application_details($login_regnumber);
		if (count($member_exam_application_data) > 0) {
			$this->chk_member_validation_for_exam_application($member_exam_application_data);

			if ($member_exam_application_data[0]['PaymentStatus'] == 1 && $member_exam_application_data[0]['kyc_status'] == 2) // payment completed and kyc rejected
			{
				$this->session->set_flashdata('error', 'You have already paid for this exam, but your KYC is rejected. So kindly re-upload your experience certificate again.');
				redirect(site_url('professional_bankers/dashboard'));
			}
		}

		$member_data = $this->check_user_validity_after_login();
		$exam_master_data = $this->get_exam_master_data($login_exam_code);

		$pb_exam_id = $this->session->userdata('PROFESSIONAL_BANKERS_MEMBER_EXAM_ID');
		$pb_exam_data = $this->get_pb_exam_data($pb_exam_id, $login_regnumber);
		if (count($pb_exam_data) <= 0) {
			redirect(site_url('professional_bankers/dashboard'));
		}

		$cgst_rate = $sgst_rate = $igst_rate = $tax_type = $cgst_amt = $sgst_amt = $igst_amt = $cs_total = $igst_total = '';
		$getstate = $getcenter = $getfees = array();

		if (isset($_POST['processPayment']) && $_POST['processPayment']) {
			$pg_name = 'sbi';
			if (isset($_POST['pg_name']) && $_POST['pg_name'] != "") {
				$pg_name = $_POST['pg_name'];
			}

			//checked for application in payment process and prevent user to apply exam on the same time(Prafull)
			$checkpayment = $this->master_model->getRecords('payment_transaction', array('member_regnumber' => $login_regnumber, 'status' => '2', 'pay_type' => '2'), '', array('id' => 'DESC'));
			if (count($checkpayment) > 0) {
				$endTime = date("Y-m-d H:i:s", strtotime("+120 minutes", strtotime($checkpayment[0]['date'])));
				$current_time = date("Y-m-d H:i:s");
				if (strtotime($current_time) <= strtotime($endTime)) {
					$this->session->set_flashdata('error', 'Your transactions is in process, please try after 2 hrs after your initial transaction.');
					redirect(site_url('professional_bankers/dashboard'));
				}
			}

			$regno = $login_regnumber;
			/* include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('sbi_m_key');
				$merchIdVal = $this->config->item('sbi_merchIdVal');
				$AggregatorId = $this->config->item('sbi_AggregatorId');
				$pg_success_url = site_url("professional_bankers/sbitranssuccess");
				$pg_fail_url = site_url("professional_bankers/sbitransfail"); */

			/* if($this->config->item('sb_test_mode'))
				{
					$amount = $this->config->item('exam_apply_fee');
				}
				else */ {
				$fee_details = $this->getProfessionalBankerCreditFee($this->exam_period, $login_exam_code, $this->group_code, $member_data[0]['registrationtype'], 'MAH'); //$member_data[0]['state']

				/* if($member_data[0]['state'] == 'MAH') { $amount = $fee_details['cs_tot']; }
					else { $amount = $fee_details['igst_tot']; } */
				$amount = $fee_details['cs_tot'];
			}

			if ($amount == 0 || $amount == '') {
				$this->session->set_flashdata('error', 'Fee can not be zero(0) or Blank!!');
				redirect(site_url('professional_bankers/dashboard'));
			}

			// $MerchantOrderNo    = generate_order_id("sbi_exam_order_id");
			// Ordinary member Apply exam
			//	Ref1 = orderid
			//	Ref2 = iibfexam
			//	Ref3 = member reg num
			//	Ref4 = exam_code + exam year + exam month ex (101201602)
			$exam_code = $login_exam_code;
			$ref4 = $exam_code . date("Ym");

			if ($member_data[0]['registrationtype'] == 'O' || $member_data[0]['registrationtype'] == 'A' || $member_data[0]['registrationtype'] == 'F') {
				$pg_flag = 'IIBF_EXAM_O';
			} else if ($member_data[0]['registrationtype'] == 'NM' || $member_data[0]['registrationtype'] == 'DB') {
				$pg_flag = 'IIBF_EXAM_NM';
			}

			// Create transaction
			$add_data['member_regnumber'] = $login_regnumber;
			$add_data['amount'] = $amount;
			$add_data['gateway'] = "sbiepay";
			$add_data['date'] = date('Y-m-d H:i:s');
			$add_data['pay_type'] = '2';
			$add_data['ref_id'] = $this->session->userdata['PROFESSIONAL_BANKERS_MEMBER_EXAM_ID'];
			$add_data['description'] = $exam_master_data[0]['description'];
			$add_data['status'] = '2';
			$add_data['exam_code'] = $exam_code;
			$add_data['pg_flag'] = $pg_flag;
			$pt_id = $this->master_model->insertRecord('payment_transaction', $add_data, true);

			$MerchantOrderNo = sbi_exam_order_id($pt_id);
			// payment gateway custom fields -
			$custom_field = $MerchantOrderNo . "^iibfexam^" . $login_regnumber . "^" . $ref4;
			$custom_field_billdesk = $MerchantOrderNo . "-iibfexam-" . $login_regnumber . "-" . $ref4;

			// update receipt no. in payment transaction -
			$update_data['receipt_no'] = $MerchantOrderNo;
			$update_data['pg_other_details'] = $custom_field;
			$this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));

			//$getstate = $this->master_model->getRecords('state_master',array('state_code'=>$member_data[0]['state'],'state_delete'=>'0'));
			$getstate = $this->master_model->getRecords('state_master', array('state_code' => 'MAH', 'state_delete' => '0'));

			$fee_details = $this->getProfessionalBankerCreditFee($this->exam_period, $login_exam_code, $this->group_code, $member_data[0]['registrationtype'], 'MAH'); //$member_data[0]['state']

			/* echo '<pre>'; print_r($fee_details); echo '</pre>'; //exit;
				echo '<br>'.$getstate[0]['state_code']; */

			if ($getstate[0]['state_code'] == 'MAH') {
				// set a rate (e.g 9%,9% or 18%)
				$cgst_rate = $this->config->item('cgst_rate');
				$sgst_rate = $this->config->item('sgst_rate');

				//set an amount as per rate
				$cgst_amt = $fee_details['cgst_amt'];
				$sgst_amt = $fee_details['sgst_amt'];

				//set an total amount
				$cs_total = $fee_details['cs_tot'];
				$amount_base = $fee_details['fee_amount'];
				$total_el_base_amount = $total_el_gst_amount = 0;
				$tax_type = 'Intra';
			} else {
				// set a rate (e.g 9%,9% or 18%)
				$igst_rate = $this->config->item('igst_rate');

				//set an amount as per rate
				$igst_amt = $fee_details['igst_amt'];

				//set an total amount
				$igst_total = $fee_details['igst_tot'];
				$amount_base = $fee_details['fee_amount'];

				$total_el_base_amount = $total_el_gst_amount = 0;
				$tax_type = 'Inter';
			}

			/* if($getstate[0]['exempt'] == 'E')
				{
					$cgst_rate = $sgst_rate = $igst_rate = '';
					$cgst_amt = $sgst_amt = $igst_amt = '';
				} */

			$gst_no = '0';

			$add_invoice['pay_txn_id'] = $pt_id;
			$add_invoice['receipt_no'] = $MerchantOrderNo;
			$add_invoice['exam_code'] = $login_exam_code;
			$add_invoice['center_code'] = '306';
			$add_invoice['center_name'] = 'MUMBAI';
			$add_invoice['state_of_center'] = 'MAH';
			$add_invoice['member_no'] = $login_regnumber;
			$add_invoice['app_type'] = 'O';
			$add_invoice['exam_period'] = $this->exam_period;
			$add_invoice['service_code'] = '999294';
			$add_invoice['qty'] = '1';
			$add_invoice['state_code'] = $getstate[0]['state_no'];
			$add_invoice['state_name'] = $getstate[0]['state_name'];
			$add_invoice['tax_type'] = $tax_type;
			$add_invoice['fee_amt'] = $amount_base;
			$add_invoice['cgst_rate'] = $cgst_rate;
			$add_invoice['cgst_amt'] = $cgst_amt;
			$add_invoice['sgst_rate'] = $sgst_rate;
			$add_invoice['sgst_amt'] = $sgst_amt;
			$add_invoice['igst_rate'] = $igst_rate;
			$add_invoice['igst_amt'] = $igst_amt;
			$add_invoice['cs_total'] = $cs_total;
			$add_invoice['igst_total'] = $igst_total;
			$add_invoice['exempt'] = $getstate[0]['exempt'];
			$add_invoice['created_on'] = date('Y-m-d H:i:s');
			//echo '<pre>'; print_r($add_invoice); echo '</pre>'; exit;

			$invoice_id = $this->master_model->insertRecord('exam_invoice', $add_invoice, true);
			$log_title = "Exam invoice data from Professional_bankers.php last id invoice_id = '" . $invoice_id . "'";
			$log_message =  serialize($add_invoice);
			$rId = $login_regnumber;
			$regNo = $login_regnumber;
			storedUserActivity($log_title, $log_message, $rId, $regNo);

			if ($pg_name == 'sbi') {
				include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('sbi_m_key');
				$merchIdVal = $this->config->item('sbi_merchIdVal');
				$AggregatorId = $this->config->item('sbi_AggregatorId');
				$pg_success_url = site_url("professional_bankers/sbitranssuccess");
				$pg_fail_url = site_url("professional_bankers/sbitransfail");

				// set cookie for Apply Exam
				applyexam_set_cookie($this->session->userdata['PROFESSIONAL_BANKERS_MEMBER_EXAM_ID']);
				$MerchantCustomerID = $regno;
				$data["pg_form_url"] = $this->config->item('sbi_pg_form_url'); // SBI ePay form URL
				$data["merchIdVal"] = $merchIdVal;

				$EncryptTrans = $merchIdVal . "|DOM|IN|INR|" . $amount . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$EncryptTrans = $aes->encrypt($EncryptTrans);
				$data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
				$this->load->view('pg_sbi_form', $data);
			} elseif ($pg_name == 'billdesk') {
				$update_payment_data = array('gateway' => 'billdesk');
				$this->master_model->updateRecord('payment_transaction', $update_payment_data, array('id' => $pt_id));

				$billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $regno, $regno, '', 'Professional_bankers/handle_billdesk_response', '', '', '', $custom_field_billdesk);

				if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {
					$data['bdorderid'] = $billdesk_res['bdorderid'];
					$data['token'] = $billdesk_res['token'];
					$data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
					$data['returnUrl'] = $billdesk_res['returnUrl'];
					$this->load->view('pg_billdesk/pg_billdesk_form', $data);
				} else {
					$this->session->set_flashdata('error', 'Transaction failed...!');
					redirect(site_url('professional_bankers/dashboard'));
				}
			}
		} else {
			$data['show_billdesk_option_flag'] = 1;
			$this->load->view('pg_sbi/make_payment_page', $data);
		}
	}

	public function handle_billdesk_response()
	{
		/* ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL); */

		$this->check_user_validity_after_login();

		if (isset($_REQUEST['transaction_response'])) {
			$response_encode = $_REQUEST['transaction_response'];
			$bd_response = $this->billdesk_pg_model->verify_res($response_encode);
			$attachpath = $invoiceNumber = $admitcard_pdf = '';
			//echo '<pre>'; print_r($bd_response); echo '</pre>'; exit;

			$responsedata = $bd_response['payload'];

			$MerchantOrderNo = $responsedata['orderid']; // To DO: temp testing changes please remove it and use valid receipt id
			$transaction_no  = $responsedata['transactionid'];
			$merchIdVal = $responsedata['mercid'];
			$Bank_Code = $responsedata['bankid'];
			$encData = $_REQUEST['transaction_response'];

			$this->session->userdata['session_array']['payment_receipt_no'] = $MerchantOrderNo;

			$transaction_error_type = $responsedata['transaction_error_type'];
			$qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
			if ($transaction_error_type == "success" && $qry_api_response['transaction_error_type'] == 'success') {
				$payment_data = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status,date');

				if ($payment_data[0]['status'] == 2) //IF payment status is pending
				{
					// ######## payment Transaction ############
					$update_data['transaction_no'] = $transaction_no;
					$update_data['status'] = 1;
					$update_data['transaction_details'] = $responsedata['transaction_error_type'] . " - " . $responsedata['transaction_error_desc'];
					$update_data['auth_code'] = '0300';
					$update_data['bankcode'] = $responsedata['bankid'];
					$update_data['paymode'] = $responsedata['txn_process_type'];
					$update_data['callback'] = 'B2B';
					$update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));

					// ######## Insert Log ############
					$query_update_payment_tra = $this->db->last_query();
					$log_title = "Professional_bankers.php ctrl query_update_payment_tra :" . $query_update_payment_tra;
					$log_message = serialize($update_data);
					$rId = $payment_data[0]['member_regnumber'];
					$regNo = $payment_data[0]['member_regnumber'];
					storedUserActivity($log_title, $log_message, $rId, $regNo);

					$get_payment_status = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber, ref_id, status, date');

					if ($get_payment_status[0]['status'] == 1) {
						if (count($payment_data) > 0) {
							$user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $payment_data[0]['member_regnumber']), 'regnumber, usrpassword, email');
						}
					} else {
						redirect(base_url() . 'professional_bankers/refund/' . base64_encode($MerchantOrderNo));
					}
				}

				$get_payment_status = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber, ref_id, status, date');

				// #####update member_exam######
				$update_dataa['pay_status'] = '1';
				if ($get_payment_status[0]['status'] == 1) {
					$this->master_model->updateRecord('member_exam', $update_dataa, array('id' => $payment_data[0]['ref_id']));

					$query_update_member_exam = $this->db->last_query();
					$log_title = "Professional_bankers.php ctrl query_update_member_exam :" . $query_update_member_exam;
					$log_message = serialize($update_dataa);
					$rId = $payment_data[0]['member_regnumber'];
					$regNo = $payment_data[0]['member_regnumber'];
					storedUserActivity($log_title, $log_message, $rId, $regNo);
				} else {
					$log_title = "Professional_bankers.php ctrl query_update_member_exam fail :";
					$log_message = $payment_data[0]['ref_id'];
					$rId = $payment_data[0]['member_regnumber'];
					$regNo = $payment_data[0]['member_regnumber'];
					storedUserActivity($log_title, $log_message, $rId, $regNo);
				}

				$exam_info = $this->get_exam_info($payment_data[0]['member_regnumber'], $payment_data[0]['ref_id']);

				if ($exam_info[0]['exam_mode'] == 'ON') {
					$mode = 'Online';
				} elseif ($exam_info[0]['exam_mode'] == 'OF') {
					$mode = 'Offline';
				} else {
					$mode = '';
				}

				// Query to get Payment details
				$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $payment_data[0]['member_regnumber']), 'transaction_no, date, amount, id');

				// get invoice
				$getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $payment_info[0]['id']));
				// echo $this->db->last_query();exit;

				if (count($getinvoice_number) > 0) {
					$invoiceNumber = '';
					if ($get_payment_status[0]['status'] == 1) // && $capacity > 0
					{
						$invoiceNumber = generate_exam_invoice_number($getinvoice_number[0]['invoice_id']); //helper
						$query_invoiceNumber = $this->db->last_query();
						$log_title = "Professional_bankers.php ctrl exam_invoice number generate :" . $getinvoice_number[0]['invoice_id'];
						$log_message = $query_invoiceNumber;
						$rId = $payment_data[0]['member_regnumber'];
						$regNo = $payment_data[0]['member_regnumber'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);
					} else {
						$log_title = "Professional_bankers.php ctrl exam_invoice number generate fail :";
						$log_message = $getinvoice_number[0]['invoice_id'];
						$rId = $payment_data[0]['member_regnumber'];
						$regNo = $payment_data[0]['member_regnumber'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);
					}

					if ($invoiceNumber) {
						$invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
					}

					$update_data_invoice['invoice_no'] = $invoiceNumber;
					$update_data_invoice['transaction_no'] = $transaction_no;
					$update_data_invoice['date_of_invoice'] = date('Y-m-d H:i:s');
					$update_data_invoice['modified_on'] = date('Y-m-d H:i:s');

					if ($get_payment_status[0]['status'] == 1) {
						$this->db->where('pay_txn_id', $payment_info[0]['id']);
						$this->master_model->updateRecord('exam_invoice', $update_data_invoice, array('receipt_no' => $MerchantOrderNo));

						$attachpath = genarate_PB_invoice($getinvoice_number[0]['invoice_id']); //helper

						$log_title = "Professional_bankers.php ctrl exam invoice update :";
						$log_message = '';
						$rId = $MerchantOrderNo;
						$regNo = $MerchantOrderNo;
						storedUserActivity($log_title, $log_message, $rId, $regNo);
					} else {
						$log_title = "Professional_bankers.php ctrl exam invoice update fail :";
						$log_message = $getinvoice_number[0]['invoice_id'];
						$rId = $MerchantOrderNo;
						$regNo = $MerchantOrderNo;
						storedUserActivity($log_title, $log_message, $rId, $regNo);
					}

					$update_data_me = array('pay_status' => '1');
					$this->master_model->updateRecord('member_exam', $update_data_me, array('id' => $payment_data[0]['ref_id']));

					$query_exam_invoice_generate = $this->db->last_query();
					$log_title = "Professional_bankers.php exam_invoice :" . $query_exam_invoice_generate;
					$log_message = serialize($attachpath);
					$rId = $payment_data[0]['member_regnumber'];
					$regNo = $payment_data[0]['member_regnumber'];
					storedUserActivity($log_title, $log_message, $rId, $regNo);
				}

				if ($attachpath != '') {
					$this->send_mail_common('success', $payment_data[0]['member_regnumber'], $exam_info[0]['description'], $payment_info[0]['amount'], $payment_info[0]['transaction_no'], $payment_info[0]['date'], $attachpath);

					/* $sms_newstring = str_replace("#exam_name#", "" . $exam_info[0]['description'] . "", $emailerstr[0]['sms_text']);
						$sms_newstring1 = str_replace("#period#", "" . $exam_period_date . "", $sms_newstring);
						$sms_newstring2 = str_replace("#fee#", "" . $payment_info[0]['amount'] . "", $sms_newstring1);
						$sms_final_str = str_replace("#transaction_no#", "" . $payment_info[0]['transaction_no'] . "", $sms_newstring2);
						$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'C-48OSQMg',$exam_info[0]['exam_code']); */
				}

				// Manage Log
				$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
				$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata['transaction_error_type']);
				$this->session->set_flashdata('success', 'Your transactions is successful.');
				redirect(site_url('professional_bankers/dashboard'));
			} else {
				$get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status');

				if ($get_user_regnum[0]['status'] != 0 && $get_user_regnum[0]['status'] == 2) {
					$update_data = array(
						'transaction_no' => $transaction_no,
						'status' => 0,
						'transaction_details' => $responsedata['transaction_error_type'] . " - " . $responsedata['transaction_error_desc'],
						'auth_code' => 0399,
						'bankcode' => $responsedata['bankid'],
						'paymode' => $responsedata['txn_process_type'],
						'callback' => 'B2B'
					);
					$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));

					// Query to get Payment details
					$payment_info = $this->master_model->getRecords('payment_transaction', array(
						'receipt_no' => $MerchantOrderNo,
						'member_regnumber' => $get_user_regnum[0]['member_regnumber']
					), 'member_regnumber, transaction_no,date,amount, ref_id');

					$exam_info = $this->get_exam_info($payment_info[0]['member_regnumber'], $payment_info[0]['ref_id']);

					$this->send_mail_common('fail', $payment_info[0]['member_regnumber'], $exam_info[0]['description'], $payment_info[0]['amount'], $payment_info[0]['transaction_no'], $payment_info[0]['date']);

					// Manage Log
					$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
					$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata['transaction_error_type']);
				}

				redirect(site_url('professional_bankers/fail/' . base64_encode($MerchantOrderNo)));
			}
		} else {
			die("Please try again...");
		}
	}

	public function sbitranssuccess()
	{
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		$key = $this->config->item('sbi_m_key');
		$aes = new CryptAES();
		$aes->set_key(base64_decode($key));
		$aes->require_pkcs5();
		$encData = $aes->decrypt($_REQUEST['encData']);
		$responsedata = explode("|", $encData);
		$MerchantOrderNo = $responsedata[0];
		$transaction_no = $responsedata[1];
		$attachpath = $invoiceNumber = $admitcard_pdf = '';

		$this->check_user_validity_after_login();

		if (isset($_REQUEST['merchIdVal'])) {
			$merchIdVal = $_REQUEST['merchIdVal'];
		}
		if (isset($_REQUEST['Bank_Code'])) {
			$Bank_Code = $_REQUEST['Bank_Code'];
		}
		if (isset($_REQUEST['pushRespData'])) {
			$encData = $_REQUEST['pushRespData'];
		}

		// Sbi B2B callback
		// check sbi payment status with MerchantOrderNo
		$q_details = sbiqueryapi($MerchantOrderNo);
		if ($q_details) {
			if ($q_details[2] == "SUCCESS") {
				$payment_data = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status,date');

				if ($payment_data[0]['status'] == 2) //IF payment status is pending
				{
					// ######## payment Transaction ############
					$update_data['transaction_no'] = $transaction_no;
					$update_data['status'] = 1;
					$update_data['transaction_details'] = $responsedata[2] . " - " . $responsedata[7];
					$update_data['auth_code'] = '0300';
					$update_data['bankcode'] = $responsedata[8];
					$update_data['paymode'] = $responsedata[5];
					$update_data['callback'] = 'B2B';
					$update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));

					// ######## Insert Log ############
					$query_update_payment_tra = $this->db->last_query();
					$log_title = "Professional_bankers.php ctrl query_update_payment_tra :" . $query_update_payment_tra;
					$log_message = serialize($update_data);
					$rId = $payment_data[0]['member_regnumber'];
					$regNo = $payment_data[0]['member_regnumber'];
					storedUserActivity($log_title, $log_message, $rId, $regNo);

					$get_payment_status = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber, ref_id, status, date');

					if ($get_payment_status[0]['status'] == 1) {
						if (count($payment_data) > 0) {
							$user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $payment_data[0]['member_regnumber']), 'regnumber, usrpassword, email');
						}
					} else {
						redirect(base_url() . 'professional_bankers/refund/' . base64_encode($MerchantOrderNo));
					}
				}

				$get_payment_status = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber, ref_id, status, date');

				// #####update member_exam######
				$update_dataa['pay_status'] = '1';
				if ($get_payment_status[0]['status'] == 1) {
					$this->master_model->updateRecord('member_exam', $update_dataa, array('id' => $payment_data[0]['ref_id']));

					$query_update_member_exam = $this->db->last_query();
					$log_title = "Professional_bankers.php ctrl query_update_member_exam :" . $query_update_member_exam;
					$log_message = serialize($update_dataa);
					$rId = $payment_data[0]['member_regnumber'];
					$regNo = $payment_data[0]['member_regnumber'];
					storedUserActivity($log_title, $log_message, $rId, $regNo);
				} else {
					$log_title = "Professional_bankers.php ctrl query_update_member_exam fail :";
					$log_message = $payment_data[0]['ref_id'];
					$rId = $payment_data[0]['member_regnumber'];
					$regNo = $payment_data[0]['member_regnumber'];
					storedUserActivity($log_title, $log_message, $rId, $regNo);
				}

				$exam_info = $this->get_exam_info($payment_data[0]['member_regnumber'], $payment_data[0]['ref_id']);

				if ($exam_info[0]['exam_mode'] == 'ON') {
					$mode = 'Online';
				} elseif ($exam_info[0]['exam_mode'] == 'OF') {
					$mode = 'Offline';
				} else {
					$mode = '';
				}

				// Query to get Payment details
				$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $payment_data[0]['member_regnumber']), 'member_regnumber, transaction_no, date, amount, id');

				// get invoice
				$getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $payment_info[0]['id']));
				// echo $this->db->last_query();exit;

				if (count($getinvoice_number) > 0) {
					$invoiceNumber = '';
					if ($get_payment_status[0]['status'] == 1) // && $capacity > 0
					{
						$invoiceNumber = generate_exam_invoice_number($getinvoice_number[0]['invoice_id']); //helper
						$query_invoiceNumber = $this->db->last_query();
						$log_title = "Professional_bankers.php ctrl exam_invoice number generate :" . $getinvoice_number[0]['invoice_id'];
						$log_message = $query_invoiceNumber;
						$rId = $payment_data[0]['member_regnumber'];
						$regNo = $payment_data[0]['member_regnumber'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);
					} else {
						$log_title = "Professional_bankers.php ctrl exam_invoice number generate fail :";
						$log_message = $getinvoice_number[0]['invoice_id'];
						$rId = $payment_data[0]['member_regnumber'];
						$regNo = $payment_data[0]['member_regnumber'];
						storedUserActivity($log_title, $log_message, $rId, $regNo);
					}

					if ($invoiceNumber) {
						$invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
					}

					$update_data_invoice['invoice_no'] = $invoiceNumber;
					$update_data_invoice['transaction_no'] = $transaction_no;
					$update_data_invoice['date_of_invoice'] = date('Y-m-d H:i:s');
					$update_data_invoice['modified_on'] = date('Y-m-d H:i:s');

					if ($get_payment_status[0]['status'] == 1) {
						$this->db->where('pay_txn_id', $payment_info[0]['id']);
						$this->master_model->updateRecord('exam_invoice', $update_data_invoice, array('receipt_no' => $MerchantOrderNo));

						$attachpath = genarate_PB_invoice($getinvoice_number[0]['invoice_id']); //helper

						$log_title = "Professional_bankers.php ctrl exam invoice update :";
						$log_message = '';
						$rId = $MerchantOrderNo;
						$regNo = $MerchantOrderNo;
						storedUserActivity($log_title, $log_message, $rId, $regNo);
					} else {
						$log_title = "Professional_bankers.php ctrl exam invoice update fail :";
						$log_message = $getinvoice_number[0]['invoice_id'];
						$rId = $MerchantOrderNo;
						$regNo = $MerchantOrderNo;
						storedUserActivity($log_title, $log_message, $rId, $regNo);
					}

					$update_data_me = array('pay_status' => '1');
					$this->master_model->updateRecord('member_exam', $update_data_me, array('id' => $payment_data[0]['ref_id']));

					$query_exam_invoice_generate = $this->db->last_query();
					$log_title = "Professional_bankers.php exam_invoice :" . $query_exam_invoice_generate;
					$log_message = serialize($attachpath);
					$rId = $payment_data[0]['member_regnumber'];
					$regNo = $payment_data[0]['member_regnumber'];
					storedUserActivity($log_title, $log_message, $rId, $regNo);
				}

				if ($attachpath != '') {
					$this->send_mail_common('success', $payment_info[0]['member_regnumber'], $exam_info[0]['description'], $payment_info[0]['amount'], $payment_info[0]['transaction_no'], $payment_info[0]['date'], $attachpath);

					/* $sms_newstring = str_replace("#exam_name#", "" . $exam_info[0]['description'] . "", $emailerstr[0]['sms_text']);
						$sms_newstring1 = str_replace("#period#", "" . $exam_period_date . "", $sms_newstring);
						$sms_newstring2 = str_replace("#fee#", "" . $payment_info[0]['amount'] . "", $sms_newstring1);
						$sms_final_str = str_replace("#transaction_no#", "" . $payment_info[0]['transaction_no'] . "", $sms_newstring2);
						$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'C-48OSQMg',$exam_info[0]['exam_code']); */
				}

				// Manage Log
				$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
				$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
				$this->session->set_flashdata('success', 'Your transactions is successful.');
			}
		} else {
			$this->session->set_flashdata('error', 'Error occurred.');
		}
		redirect(site_url('professional_bankers/dashboard'));
	}

	public function sbitransfail()
	{
		if (isset($_REQUEST['encData'])) {
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$encData = $aes->decrypt($_REQUEST['encData']);
			$responsedata = explode("|", $encData);
			$MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
			$transaction_no = $responsedata[1];
			// SBICALL Back B2B
			$get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status');

			$this->check_user_validity_after_login();

			if ($get_user_regnum[0]['status'] != 0 && $get_user_regnum[0]['status'] == 2) {
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
					'auth_code' => 0399,
					'bankcode' => $responsedata[8],
					'paymode' => $responsedata[5],
					'callback' => 'B2B'
				);
				$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));

				// Query to get Payment details
				$payment_info = $this->master_model->getRecords('payment_transaction', array(
					'receipt_no' => $MerchantOrderNo,
					'member_regnumber' => $get_user_regnum[0]['member_regnumber']
				), 'member_regnumber, transaction_no,date,amount, ref_id');

				$exam_info = $this->get_exam_info($payment_info[0]['member_regnumber'], $payment_info[0]['ref_id']);

				$this->send_mail_common('fail', $payment_info[0]['member_regnumber'], $exam_info[0]['description'], $payment_info[0]['amount'], $payment_info[0]['transaction_no'], $payment_info[0]['date']);

				// Manage Log
				$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
				$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
			}
			// End Of SBICALL Back

			redirect(site_url('professional_bankers/fail/' . base64_encode($MerchantOrderNo)));
		} else {
			die("Please try again...");
		}
	}

	public function fail($order_no = NULL)
	{
		$login_regnumber = $this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_REGNUMBER');

		$this->check_user_validity_after_login();

		// payment detail
		$payment_info = $this->master_model->getRecords('payment_transaction', array(
			'receipt_no' => base64_decode($order_no),
			'member_regnumber' => $login_regnumber
		));
		if (count($payment_info) <= 0) {
			redirect(base_url('professional_bankers/dashboard'));
		}

		$data['payment_info'] = $payment_info;
		$data['act_id'] = $data['sub_act_id'] = 'dashboard';
		$this->load->view('professional_bankers/exam_applied_fail', $data);
	}

	function send_mail_common($mail_type = '', $member_regnumber = 0, $exam_name = '', $amount = 0, $transaction_no = '', $payment_date = '', $attachpath = '')
	{
		// Query to get user details
		$this->db->join('state_master', 'state_master.state_code=member_registration.state');
		$this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
		$member_data = $this->master_model->getRecords('member_registration', array('regnumber' => $member_regnumber), 'firstname, middlename, lastname, address1, address2, address3, address4, district, city, email, mobile, office, pincode, state_master.state_name, institution_master.name');

		$username = $member_data[0]['firstname'] . ' ' . $member_data[0]['middlename'] . ' ' . $member_data[0]['lastname'];
		$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);

		if (base_url() == 'https://iibf.teamgrowth.net/' || base_url() == 'http://iibf.teamgrowth.net/' || base_url() == 'https://iibf.esdsconnect.com/staging/') {
			$info_arr['to'] = 'sagar.matale@esds.co.in,shweta.pingale@esds.co.in';
		} else {
			$info_arr['to'] = 'sagar.matale@esds.co.in,shweta.pingale@esds.co.in';//$member_data[0]['email'];
		}

		$info_arr['from'] = "logs@iibf.esdsconnect.com";
		$info_arr['subject'] = $exam_name . ' Enrolment Acknowledgement : ' . $member_regnumber;

		$final_str = '';
		if ($mail_type == 'success') {
			$final_str = '<div style="max-width:600px; width:100%; margin:20px auto;">
												<table style="width:100%; background:#FFFFCC;" cellspacing="5" cellpadding="5" border="1">
													<tbody style="">
														<tr><td colspan="2"><h2 style="margin: 10px 0; text-align: center; ">Transaction Details</h2></td></tr>
														<tr>
															<td colspan="2">
																<p style="margin: 10px 0;">Dear ' . $userfinalstrname . '<br><br>Your transaction has been success.</p>
															</td>
														</tr>
														<tr style="">
															<td><p style=""><strong style="">Member Number : </strong></p></td>
															<td>' . $member_regnumber . '</td>
														</tr>
														<tr style="">
															<td><p style=""><strong style="">Member Name : </strong></p></td>
															<td>' . $userfinalstrname . '</td>
														</tr>
														<tr style="">
															<td><p style=""><strong style="">Exam Name : </strong></p></td>
															<td>' . $exam_name . '</td>
														</tr>								
														<tr style="">
															<td><p style=""><strong style="">Amount : </strong></p></td>
															<td>' . $amount . '</td>
														</tr>
														<tr>
															<td><p><strong>Transaction ID:</strong></p></td>
															<td>' . $transaction_no . '</td>
														</tr>
														<tr>
															<td><p><strong>Transaction Date :</strong> </p></td>
															<td>' . date('Y-m-d H:i:s A', strtotime($payment_date)) . '</td>
														</tr>
													</tbody>
												</table>	
												<p>Yours truly,<br>IIBF Team</p>
											</div>';

			$info_arr['message'] = $final_str;
			$files = array($attachpath);
			$this->Emailsending->mailsend_attch($info_arr, $files);
		} else if ($mail_type == 'fail') {
			$final_str = '<div style="max-width:600px; width:100%; margin:20px auto;">
								<table style="width:100%; background:#FFFFCC;" cellspacing="5" cellpadding="5" border="1">
									<tbody>
										<tr><td colspan="2"><h2 style="margin: 10px 0; text-align: center; ">Transaction Details</h2></td></tr>
										<tr>
											<td colspan="2">
												<p style="margin: 10px 0;">Dear ' . $userfinalstrname . '<br><br>Please note that your transaction has failed. However kindly note down your transaction ID No. for future correspondence.</p>
											</td>
										</tr>
										<tr>
											<td><p><strong>Member Number : </strong></p></td>
											<td>' . $member_regnumber . '</td>
										</tr>
										<tr>
											<td><p><strong>Member Name : </strong></p></td>
											<td>' . $userfinalstrname . '</td>
										</tr>
										<tr>
											<td><p><strong>Exam Name : </strong></p></td>
											<td>' . $exam_name . '</td>
										</tr>
										<tr>
											<td><p><strong>Transaction ID:</strong></p></td>
											<td>' . $transaction_no . '</td>
										</tr>
										<tr>
											<td><p><strong>Transaction Date :</strong> </p></td>
											<td>' . date('Y-m-d H:i:s A', strtotime($payment_date)) . '</td>
										</tr>
									</tbody>
								</table>
								<p>Yours truly,<br>IIBF Team</p>
							</div>';

			$info_arr['message'] = $final_str;
			$this->Emailsending->mailsend($info_arr);
		}
	}

	function get_exam_info($regnumber = 0, $ref_id = 0)
	{
		// Query to get exam details
		//$this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period', 'LEFT');
		//$this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period', 'LEFT');
		//$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period', 'LEFT');
		$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code', 'LEFT');
		$exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $regnumber, 'member_exam.id' => $ref_id), 'member_exam.exam_code, member_exam.exam_mode, member_exam.exam_medium, member_exam.exam_period, member_exam.exam_center_code, exam_master.description, member_exam.state_place_of_work, member_exam.place_of_work, member_exam.pin_code_place_of_work, member_exam.examination_date, member_exam.elected_sub_code'); //center_master.center_name, misc_master.exam_month,

		return $exam_info;
	}

	public function refund($order_no = 0)
	{
		$this->check_user_validity_after_login();

		//echo base64_encode($order_no);
		if ($order_no == '0') {
			redirect(site_url('professional_bankers/login'));
		}

		$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => base64_decode($order_no)));

		if (count($payment_info) <= 0) {
			redirect(base_url('professional_bankers/dashboard'));
		}

		/* $this->db->where('remark', '2');
			$admit_card_refund = $this->master_model->getRecords('admit_card_details', array(
			'mem_exam_id' => $payment_info[0]['ref_id']
			));
			
			if (count($admit_card_refund) > 0)
			{
				$update_data = array(
				'remark' => 3
				);
				$this->master_model->updateRecord('admit_card_details', $update_data, array(
				'mem_exam_id' => $payment_info[0]['ref_id']
				));
			} */

		$exam_name = $this->master_model->getRecords('exam_master', array('exam_code' => $payment_info[0]['exam_code']));
		//echo $this->db->last_query(); exit;

		##adding below code for processing the refund process - added by chaitali on 2021-09-17						
		$insert_data = array('receipt_no' => base64_decode($order_no), 'transaction_no' => $payment_info[0]['transaction_no'], 'refund' => '0', 'created_on' => date('Y-m-d'), 'email_flag' => '0', 'sms_flag' => '0');
		$this->master_model->insertRecord('exam_payment_refund', $insert_data);
		//echo $this->db->last_query(); die;		
		## ended insert code

		$data = array('payment_info' => $payment_info, 'exam_name' => $exam_name);
		$this->load->view('professional_bankers/member_refund', $data);
	}

	function upload_file($input_name, $valid_arr, $new_file_name, $upload_path, $allowed_types, $is_multiple = 0, $cnt = '', $height = 0, $width = 0, $size = 0)
	{
		$flag = 0;
		if ($is_multiple == 0) {
			$path_img = $_FILES[$input_name]['name'];
		} else {
			$path_img = $_FILES[$input_name]['name'][$cnt];
		}

		$ext_img = pathinfo($path_img, PATHINFO_EXTENSION);
		$valid_ext_arr = $valid_arr;

		/* print_r($valid_ext_arr);
			echo '<br>'.$allowed_types; 
			echo '<br>'.$ext_img; */
		//exit;

		if (!in_array(strtolower($ext_img), $valid_ext_arr)) {
			$flag = 1;
		}

		if ($flag == 0) {
			/* $chk_upload_dir = './uploads';
				if(!is_dir($chk_upload_dir))
				{
					$dir = mkdir($chk_upload_dir,0755);
					
					$myfile0 = fopen($chk_upload_dir."/index.php", "w") or die("Unable to open file!");
					$txt0 = "";
					fwrite($myfile0, $txt0);				
					fclose($myfile0);
				}
				
				if(is_dir($upload_path)){ }
				else
				{ 
					$dir=mkdir($upload_path,0755);
					
					$myfile = fopen($upload_path."/index.php", "w") or die("Unable to open file!");
					$txt = "";
					fwrite($myfile, $txt);				
					fclose($myfile);
				} */

			$this->create_directories($upload_path);

			$file = $_FILES;
			if ($is_multiple == 0) {
				$_FILES['file_upload']['name'] = $file[$input_name]['name'];
			} else {
				$_FILES['file_upload']['name'] = $file[$input_name]['name'][$cnt];
			}

			$path = $_FILES['file_upload']['name'];
			$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

			$filename = '';
			if ($new_file_name != "") //CHECK NEW FILENAME IS BLANK OR NOT. IF BLANK, CONVERT CURRENT FILENAME INTO VALID FORMAT
			{
				$raw_filename = $this->remove_special_character_from_string($new_file_name, '80');
				$filename = $raw_filename . "." . $ext;
			} else {
				$raw_filename = str_replace("." . $ext, "", strtolower($path));
				$raw_filename = $this->remove_special_character_from_string($raw_filename, '50') . "_" . rand(100, 999) . date("YmdHis");
				$filename = $raw_filename . "." . $ext;
			}
			$final_img = $filename;

			$config['file_name']     = $final_img;
			$config['upload_path']   = $upload_path;
			$config['allowed_types'] = $allowed_types;

			if ($size > 0) {
				$config['max_size']      = $size; //in kb
			}

			$this->upload->initialize($config);

			if ($is_multiple == 0) {
				$_FILES['file_upload']['type'] = $file[$input_name]['type'];
				$_FILES['file_upload']['tmp_name'] = $file[$input_name]['tmp_name'];
				$_FILES['file_upload']['error'] = $file[$input_name]['error'];
				$_FILES['file_upload']['size'] = $file[$input_name]['size'];
			} else {
				$_FILES['file_upload']['type'] = $file[$input_name]['type'][$cnt];
				$_FILES['file_upload']['tmp_name'] = $file[$input_name]['tmp_name'][$cnt];
				$_FILES['file_upload']['error'] = $file[$input_name]['error'][$cnt];
				$_FILES['file_upload']['size'] = $file[$input_name]['size'][$cnt];
			}

			if ($this->upload->do_upload('file_upload')) {
				$data = $this->upload->data();
				return array('response' => 'success', 'message' => $final_img);
			} else {
				return array('response' => 'error', 'message' => $this->upload->display_errors());
			}
		} else {
			return array('response' => 'error', 'message' => "Please upload valid " . str_replace('|', ' | ', $allowed_types) . " extension image.");
		}
	}

	function create_directories($directory_path = '') // CREATE DIRECTORY UPTO nth LEVEL
	{
		$directory_path = str_replace("./", "", $directory_path);
		$directory_path_arr = explode("/", $directory_path);
		$chk_dir_path = './';
		if (count($directory_path_arr) > 0) {
			$i = 0;
			foreach ($directory_path_arr as $res) {
				if ($i > 0) {
					$chk_dir_path .= "/";
				}
				$chk_dir_path .= $res;

				if (!is_dir($chk_dir_path)) {
					$dir = mkdir($chk_dir_path, 0755);
					$myfile = fopen($chk_dir_path . "/index.php", "w") or die("Unable to open file!");
					$txt = "";
					fwrite($myfile, $txt);
					fclose($myfile);
				}
				$i++;
			}
		}
		return $chk_dir_path;
	}

	function remove_special_character_from_string($old_string = '', $char_limit = '50') //START : REMOVE SPECIAL CHARACTER FROM STRING
	{
		$find_arr = array('`', '~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '=', '+', '[', '{', ']', '}', '|', ';', ':', '"', '<', ',', '.', '>', '/', '?', "'", '/\/', ' ');
		$new_string = substr($this->check_multiple_underscore(str_replace($find_arr, '_', $old_string)), 0, $char_limit);

		/* echo "<br>Old Name : ".$old_string;
			echo "<br>New Name : ".$new_string; */
		return strtolower($new_string);
	} //END : REMOVE SPECIAL CHARACTER FROM STRING

	function check_multiple_underscore($new_name = '') //START : REMOVE MULTIPLE UNDERSCORE FROM STRING
	{
		if (strpos($new_name, '__') !== false) {
			$new_name = str_replace('__', '_', $new_name);
			return $this->check_multiple_underscore($new_name);
		} else {
			return $new_name;
		}
	} //END : REMOVE MULTIPLE UNDERSCORE FROM STRING

	function payment_history()
	{
		$this->check_user_validity_after_login();

		$login_regnumber = $this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_REGNUMBER');
		$login_exam_code = $this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_EXAM_CODE');

		$this->db->join('member_exam me', 'me.id = pbr.mem_exam_id AND me.exam_code = "' . $login_exam_code . '" AND me.exam_period = "' . $this->exam_period . '"', 'INNER');
		$this->db->join('payment_transaction pt', 'pt.ref_id = me.id AND pt.exam_code = "' . $login_exam_code . '"', 'INNER');
		$this->db->join('exam_invoice ei', 'ei.receipt_no = pt.receipt_no', 'INNER');
		$payment_history_data = $this->master_model->getRecords('professional_banker_registrations pbr', array('pbr.regnumber' => $login_regnumber, 'pbr.exam_code' => $login_exam_code, 'pbr.exam_period' => $this->exam_period), 'pbr.pb_reg_id, pbr.pb_reg_id, pbr.mem_exam_id, pbr.exam_code, pbr.exam_period, pbr.regnumber, pbr.amount, pbr.exp_cert, pbr.kyc_status, pbr.remark, pt.amount, pt.status AS PaymentStatus, pt.date AS PaymentDate, pt.transaction_no', array('pbr.pb_reg_id' => 'DESC'));

		$data['exam_data'] = $this->get_exam_master_data($login_exam_code);
		$data['payment_history_data'] = $payment_history_data;
		$data['act_id'] = $data['sub_act_id'] = 'payment_history';
		$this->load->view('professional_bankers/payment_history', $data);
	}

	function download_exp_cert($enc_pb_reg_id = 0)
	{
		$this->check_user_validity_after_login();

		$login_regnumber = $this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_REGNUMBER');
		$login_exam_code = $this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_EXAM_CODE');

		$pb_reg_id = $enc_pb_reg_id;
		if ($enc_pb_reg_id != '0') {
			$pb_reg_id = base64_decode($enc_pb_reg_id);
		}

		$exp_cert_data = $this->master_model->getRecords('professional_banker_registrations pbr', array('pbr.pb_reg_id' => $pb_reg_id, 'pbr.regnumber' => $login_regnumber, 'pbr.exam_code' => $login_exam_code, 'pbr.exam_period' => $this->exam_period), 'pbr.exp_cert');

		if (count($exp_cert_data) > 0) {
			$file_full_path = ('./uploads/professional_bankers/' . $exp_cert_data[0]['exp_cert']);
			$this->download_file($file_full_path, $exp_cert_data[0]['exp_cert']);
		} else {
			echo 'Invalid download request';
		}
	}

	function download_exp_cert_log($enc_log_id = 0)
	{
		$this->check_user_validity_after_login();

		$login_regnumber = $this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_REGNUMBER');
		$login_exam_code = $this->session->userdata('PROFESSIONAL_BANKERS_LOGIN_EXAM_CODE');

		$log_id = $enc_log_id;
		if ($enc_log_id != '0') {
			$log_id = base64_decode($enc_log_id);
		}

		$this->db->join('professional_banker_rejection_logs rl', 'rl.pb_reg_id = pbr.pb_reg_id', 'INNER');
		$exp_cert_data = $this->master_model->getRecords('professional_banker_registrations pbr', array('pbr.regnumber' => $login_regnumber, 'pbr.exam_code' => $login_exam_code, 'pbr.exam_period' => $this->exam_period, 'rl.log_id' => $log_id), 'rl.exp_cert');

		if (count($exp_cert_data) > 0) {
			$file_full_path = ('./uploads/professional_bankers/' . $exp_cert_data[0]['exp_cert']);
			$this->download_file($file_full_path, $exp_cert_data[0]['exp_cert']);
		} else {
			echo 'Invalid download request';
		}
	}

	function download_file($file_full_path = '', $file_name = '')
	{
		$this->load->helper('file');
		if ($file_full_path != '' && $file_name != '') {
			$mime = get_mime_by_extension($file_full_path);

			// Build the headers to push out the file properly.
			header('Pragma: public');     // required
			header('Expires: 0');         // no cache
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($file_full_path)) . ' GMT');
			header('Cache-Control: private', false);
			header('Content-Type: ' . $mime);  // Add the mime type from Code igniter.
			header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');  // Add the file name
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: ' . filesize($file_full_path)); // provide file size
			header('Connection: close');
			readfile($file_full_path); // push it out
			exit();
		}
	}
}
