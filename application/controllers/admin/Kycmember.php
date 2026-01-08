<?php
defined('BASEPATH') or exit('No direct script access allowed');

/** SAGAR WALZADE : CUSTOM FUNCTIONS START */
function _pa($a)
{
	echo '<pre>';
	echo print_r($a, true);
	echo '</pre>';
}
function _lq()
{
	$CI = &get_instance();
	echo $CI->db->last_query();
}
/** SAGAR WALZADE : CUSTOM FUNCTIONS END */

class Kycmember extends CI_Controller
{
	public $UserID;

	public function __construct()
	{
		parent::__construct();
		if ($this->session->id == "") {
			redirect('admin/Login');
		}

		$this->load->model('UserModel');
		$this->load->model('Master_model');
		$this->UserID = $this->session->id;

		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->helper('upload_helper');
		$this->load->helper('general_helper');
		$this->load->library('email');
		$this->load->model('Emailsending');
		$this->load->model('KYC_Log_model');
	}

	public function index()
	{

		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Member List</li>
							   </ol>';
		$this->load->view('admin/kyc_reg_list', $data);
	}

	public function getList()
	{

		$field = '';
		$value = '';
		$sortkey = '';
		$sortval = '';
		$per_page = '';
		$limit = 10;
		$start = 0;
		$data['success'] = '';
		$data['links'] = '';


		$session_arr = check_session();
		if ($session_arr) {
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}

		if ($field != '' && $field == 'regnumber') {
			$field = 'member_registration.regnumber';
		}

		$select = 'member_kyc.regnumber,firstname,dateofbirth,regid,MAX(kyc_id)';

		if (strpos($value, '~') !== false) {

			$new_value = explode('~', $value);
			$value1 = $new_value[0];
			$value2 = $new_value[1];

			$this->db->where('DATE(recommended_date) BETWEEN "' . $value1 . '" AND "' . $value2 . '" ');
			$this->db->join('member_registration', 'member_registration.regnumber=member_kyc.regnumber ', 'INNER');
			$this->db->group_by('member_registration.regnumber');
			$kyc = $this->UserModel->getRecords("member_kyc", $select, '', $value, $sortkey, $sortval, $per_page, $start);

			/*
			- SAGAR WALZADE : Code start here
			- date : 27-4-2022
			- issue : showing wrong total count of result
			- change : same query used only limit,offset removed and passed total count to $total_row
			*/
			$data['query_paginate'] = $this->db->last_query();
			$this->db->where('DATE(recommended_date) BETWEEN "' . $value1 . '" AND "' . $value2 . '" ');
			$this->db->join('member_registration', 'member_registration.regnumber=member_kyc.regnumber ', 'INNER');
			$this->db->group_by('member_registration.regnumber');
			$kyc_data = $this->UserModel->getRecords("member_kyc", $select, '', $value, $sortkey, $sortval)->result_array();
			$total_row = count($kyc_data);
			$data['query_total'] = $this->db->last_query();
			/* SAGAR WALZADE : Code end here */

			// $total_row = $this->UserModel->getRecordCount("member_kyc", '', '', $select);
		} else {

			$this->db->join('member_registration', 'member_registration.regnumber=member_kyc.regnumber ', 'INNER');
			$this->db->group_by('member_registration.regnumber');
			$kyc = $this->UserModel->getRecords("member_kyc", $select, $field, $value, $sortkey, $sortval, $per_page, $start);

			/*
			- SAGAR WALZADE : Code start here
			- date : 27-4-2022
			- issue : showing wrong total count of result
			- change : same query used only limit,offset removed and passed total count to $total_row
			*/
			$data['query_paginate'] = $this->db->last_query();
			$this->db->join('member_registration', 'member_registration.regnumber=member_kyc.regnumber ', 'INNER');
			$this->db->group_by('member_registration.regnumber');
			$kyc_data = $this->UserModel->getRecords("member_kyc", $select, $field, $value, $sortkey, $sortval)->result_array();
			$total_row = count($kyc_data);
			$data['query_total'] = $this->db->last_query();
			/* SAGAR WALZADE : Code end here */

			// $total_row = $this->UserModel->getRecordCount("member_kyc", '', '', $select);
		}
		//echo $field."----".$value;
		//echo $this->db->last_query();
		//exit;

		$url = base_url() . "admin/Kycmember/getList/";
		$config = pagination_init($url, $total_row, $per_page, 2);
		$this->pagination->initialize($config);

		$i = 0;
		$kyc_details = $kyc->result_array();
		$data['result'] = $kyc_details;
		foreach ($kyc_details as $row) {

			$confirm = 'Do you want to re-send registration mail?';
			$send_mail = '<a href="' . base_url() . 'admin/Kycmember/send_mail/' . base64_encode($row['regid']) . '/' . base64_encode($row['regnumber']) . '/0" onclick="return confirmMailSend();">Send Mail</a>';
			$kyc_details[$i]['send_mail'] = $send_mail;
			$i++;
		}

		$str_links = $this->pagination->create_links();
		if (($start + $per_page) > $total_row) {
			$end_of_total = $total_row;
		} else {
			$end_of_total = $start + $per_page;
		}

		$data["links"] = $str_links;
		$data['success'] = 'Success';
		$data['result'] = $kyc_details;
		$data['index'] = $start + 1;
		$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';

		$json_res = json_encode($data);
		echo $json_res;
	}

	public function send_mail()
	{
		$update_data = array();
		$msg = '';
		$today = date('Y-m-d H:i:s');
		//email to user
		$last = $this->uri->total_segments();
		$regid = base64_decode($this->uri->segment($last - 2));
		$regnumber = base64_decode($this->uri->segment($last - 1));
		$userdata = $this->master_model->getRecords("member_registration", array('regnumber' => $regnumber, 'regid' => $regid));
		$flag = $this->uri->segment($last);
		if (!$flag) {
			$flag = 0;
		}
		if ($regnumber != '' && is_numeric($regid)) {
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('pass_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$userpass = $aes->decrypt($userdata[0]['usrpassword']);

			$username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
			$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);

			$select = '*,MAX(kyc_id)';
			$user_kyc_details = $this->master_model->getRecords("member_kyc", array('regnumber' => $regnumber), $select, array('kyc_id' => 'DESC'));
			if ($userdata[0]['registrationtype'] == 'O' || $userdata[0]['registrationtype'] == 'F' || $userdata[0]['registrationtype'] == 'A') {

				if (count($user_kyc_details) > 0) {
					if ($user_kyc_details[0]['kyc_status'] == '0') {
						if ($user_kyc_details[0]['mem_name'] == 0) {
							$update_data[] = 'Name';
						}
						if ($user_kyc_details[0]['mem_dob'] == 0) {
							$update_data[] = 'DOB';
						}
						if ($user_kyc_details[0]['mem_associate_inst'] == 0) {
							$update_data[] = 'Employer';
						}
						if ($user_kyc_details[0]['mem_photo'] == 0) {
							$update_data[] = 'Photo';
						}

						if ($user_kyc_details[0]['mem_sign'] == 0) {
							$update_data[] = 'Sign';
						}
						if ($user_kyc_details[0]['mem_proof'] == 0) {
							$update_data[] = 'Id-proof';
						}
						$msg = implode(',', $update_data);

						$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'recommendation_email_O'));
						$newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "",  $emailerstr[0]['emailer_text']);
						$newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "",  $newstring1);
						$newstring3 = str_replace("#PASSWORD#", "" . $userpass . "",  $newstring2);
						$newstring4 = str_replace("#MSG#", "" . $msg . "",  $newstring3);
						$final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . 'nonmem/" style="color:#F00">Click here to Login </a>', $newstring4);
					} else if ($user_kyc_details[0]['kyc_status'] == '1') {
						$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'KYC_completion_email_to_O'));
						//echo $emailerstr[0]['emailer_text'];exit;
						$newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "",  $emailerstr[0]['emailer_text']);
						$final_str = str_replace("#USERNAME#", "" . $userfinalstrname . "",  $newstring1);
					}
				}
				$info_arr = array(
					//'to'=> "kyciibf@gmail.com",
					'to' => $userdata[0]['email'],
					'from' => $emailerstr[0]['from'],
					'subject' => $emailerstr[0]['subject'],
					'message' => $final_str
				);

				if ($this->Emailsending->mailsend($info_arr)) {
					$this->KYC_Log_model->email_log($user_kyc_details[0]['kyc_id'], $this->session->userdata('roleid'), '2', '', $regnumber, serialize($info_arr), $today, 'admin');
					$this->session->set_flashdata('success', 'Email sent successfully !!');
					if ($flag != 0 && $flag != 1) {
						redirect(base_url() . 'admin/Kycmember/');
					} elseif ($flag == 0) {
						redirect(base_url() . 'admin/Kycmember/');
					} elseif ($flag == 1) {
						redirect(base_url() . 'admin/Kycmember/');
					}
				} else {
					//echo 'Error while sending email';
					$this->session->set_flashdata('error', 'Error while sending email !!');
					if ($flag != 0 && $flag != 1 && $flag != 2) {
						redirect(base_url() . 'admin/Kycmember/');
					} elseif ($flag == 0) {
						redirect(base_url() . 'admin/Kycmember/');
					} elseif ($flag == 1) {
						redirect(base_url() . 'admin/Kycmember/');
					}
				}
			} else if ($userdata[0]['registrationtype'] == 'NM' || $userdata[0]['registrationtype'] == 'DB') {




				if (count($user_kyc_details) > 0) {

					if ($user_kyc_details[0]['kyc_status'] == '0') {
						if ($user_kyc_details[0]['mem_name'] == 0) {
							$update_data[] = 'Name';
						}
						if ($user_kyc_details[0]['mem_dob'] == 0) {
							$update_data[] = 'DOB';
						}
						if ($user_kyc_details[0]['mem_photo'] == 0) {
							$update_data[] = 'Photo';
						}

						if ($user_kyc_details[0]['mem_sign'] == 0) {
							$update_data[] = 'Sign';
						}
						if ($user_kyc_details[0]['mem_proof'] == 0) {
							$update_data[] = 'Id-proof';
						}

						$msg = implode(',', $update_data);

						$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'recommendation_email_NM'));
						$newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "",  $emailerstr[0]['emailer_text']);
						$newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "",  $newstring1);
						$newstring3 = str_replace("#PASSWORD#", "" . $userpass . "",  $newstring2);
						$newstring4 = str_replace("#MSG#", "" . $msg . "",  $newstring3);
						$final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . 'nonmem/" style="color:#F00">Click here to Login </a>', $newstring4);
					} else if ($user_kyc_details[0]['kyc_status'] == '1') {
						$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'KYC_completion_email_to_NM'));
						//echo $emailerstr[0]['emailer_text'];exit;
						$newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "",  $emailerstr[0]['emailer_text']);
						$final_str = str_replace("#USERNAME#", "" . $userfinalstrname . "",  $newstring1);
					}
				}	//echo $final_str;
				//exit;
				$info_arr = array(
					//'to'=> "kyciibf@gmail.com",
					'to' => $userdata[0]['email'],
					'from' => $emailerstr[0]['from'],
					'subject' => $emailerstr[0]['subject'],
					'message' => $final_str
				);

				if ($this->Emailsending->mailsend($info_arr)) {
					$this->KYC_Log_model->email_log($user_kyc_details[0]['kyc_id'], $this->session->userdata('roleid'), '2', '', $regnumber, serialize($info_arr), $today, 'admin');
					$this->session->set_flashdata('success', 'Email sent successfully !!');
					if ($flag != 0 && $flag != 1) {
						redirect(base_url() . 'admin/Kycmember/');
					} elseif ($flag == 0) {
						redirect(base_url() . 'admin/Kycmember/');
					} elseif ($flag == 1) {
						redirect(base_url() . 'admin/Kycmember/');
					}
				} else {
					//echo 'Error while sending email';
					$this->session->set_flashdata('error', 'Error while sending email !!');
					if ($flag != 0 && $flag != 1 && $flag != 2) {
						redirect(base_url() . 'admin/Kycmember/');
					} elseif ($flag == 0) {
						redirect(base_url() . 'admin/Kycmember/');
					} elseif ($flag == 1) {
						redirect(base_url() . 'admin/Kycmember/');
					}
				}
			} else {
				$this->session->set_flashdata('error', 'Something went wrong...');
				if ($flag != 0 && $flag != 1) {
					redirect(base_url() . 'admin/Kycmember/');
				} elseif ($flag == 0) {
					redirect(base_url() . 'admin/Kycmember/');
				} elseif ($flag == 1) {
					redirect(base_url() . 'admin/Kycmember/');
				}
			}
		}
	}

	/*
	- by SAGAR WALZADE : Code start here
	- date : 10-5-2022
	- function use : Function to fetch all todays count of kyc done by recommenders and approvers.
	- Changes : In old function, multiple unnecessary quiries running due to duplicate code, so complete function re-written + code & queries optimized 
	- (previous function renamed into "statistic_old")
	*/
	public function statistic()
	{

		$a_remianing_details = array();
		$today = date("Y-m-d");
		$srch_date = $this->input->post('from_date');
		$error = '';

		if(isset($_POST['btnSearch']) && $_POST['from_date'] == ''){
			$error = '<span style="">Plese select date</span>';
		}

		if ($srch_date == '') {
			$srch_date = $today;
		}

		// For recomender
		$this->db->where('roleid', '4');
		$this->db->where('active !=', '0');
		$rocomeder = $this->UserModel->getRecords("administrators");
		$rocomeder_details = $rocomeder->result_array();

		$i = 0;
		foreach ($rocomeder_details as $record) {
			$this->db->where('recommended_by', $record['id']);
			$this->db->like('recommended_date ', $srch_date);

			$rocomeder_cnt = $this->UserModel->getRecordCount("member_kyc");
			$recomended_cnt[$i] = $rocomeder_cnt;

			// Remaining count for recomender

			$this->db->where('user_id', $record['id']);
			$this->db->where('date ', $srch_date);
			$r_remianing = $this->UserModel->getRecords("admin_kyc_users", "allocated_count");
			$r_remianing_details = $r_remianing->result_array();
			//echo $this->db->last_query();
			$r_rem_cnt = 0;
			foreach ($r_remianing_details as $r_rem) {

				$str = $r_rem['allocated_count'];
				$r_rem_cnt += $str;
			}

			$rem_cnt = $r_rem_cnt - $recomended_cnt[$i];
			$recomender_rem_cnt[$i] = $rem_cnt;
			$i++;
		}

		// For approver
		$this->db->where('roleid', '5');
		$this->db->where('active !=', '0');
		$approver = $this->UserModel->getRecords("administrators");
		$approver_details = $approver->result_array();

		$j = 0;
		foreach ($approver_details as $result) {
			$this->db->where('approved_by', $result['id']);
			$this->db->like('approved_date ', $srch_date);
			$approver_cnt = $this->UserModel->getRecordCount("member_kyc");
			$approved_cnt[$j] = $approver_cnt;


			// Remaining count for approver
			$this->db->where('user_id', $result['id']);
			$this->db->where('date ', $srch_date);
			$a_remianing = $this->UserModel->getRecords("admin_kyc_users", "allocated_count");
			$a_remianing_details = $a_remianing->result_array();
			$a_rem_cnt = 0;
			foreach ($a_remianing_details as $a_rem) {
				$str = $a_rem['allocated_count'];
				$a_rem_cnt += $str;
			}
			$a_rem_cnt = $a_rem_cnt - $approved_cnt[$j];
			$approver_rem_cnt[$j] = $a_rem_cnt;
			$j++;
		}

		$data = array(
			"rocomeder_details" => $rocomeder_details, 
			"approver_details" => $approver_details, 
			"recomended_cnt" => $recomended_cnt, 
			"approved_cnt" => $approved_cnt, 
			"recomender_rem_cnt" => $recomender_rem_cnt, 
			"approver_rem_cnt" => $approver_rem_cnt, 
			"error" => $error
		);

		$this->load->view('admin/kyc_statistic_report', $data);
	}
	/* by SAGAR WALZADE : Code end here */

	/*
	- by SAGAR WALZADE : Code start here
	- date : 13-4-2022 (code updated by 5-3-2022)
	- function use : Function to fetch all counts as per the date provided. (complete function re-written)
	- Changes : In previous function counts was wrong, so complete function re-written  + code & queries optimized 
	- (previous function renamed into "asondate_old")
	*/

	public function asondate()
	{
		$from_date = $end_date = '';

		if (isset($_POST['submit'])) {
			$from_date = $this->input->post('from_date'); //'2019-07-01';
			$end_date = $this->input->post('to_date'); //'2019-07-31';
		} else {
			$from_date = date("Y-m-d", strtotime("first day of previous month"));
			$end_date = date("Y-m-d", strtotime("last day of previous month"));
			// $from_date = '2022-01-01';
			// $end_date = '2022-12-01';
		}



		$new_o_results = $this->kyc_report_new_ordinary_members($from_date, $end_date);
		$new_nm_results = $this->kyc_report_new_non_members($from_date, $end_date);
		$edit_o_results = $this->kyc_report_edit_ordinary_members($from_date, $end_date);
		// echo "<pre>";
		// print_r($edit_o_results);
		// die;
		$edit_nm_results = $this->kyc_report_edit_non_members($from_date, $end_date);


		/*Dupilcate card*/
		$this->db->where('pay_status', '1');
		$this->db->where('DATE(`added_date`)>=', $from_date);
		$this->db->where('DATE(`added_date`)<=', $end_date);
		//$this->db->where('DATE(`added_date`)>=', $kyc_start_date);
		$dup_card_count = $this->UserModel->getRecordCount("duplicate_icard");

		/*membership Id-card*/
		$dwn_mem_icard = $this->db->query("SELECT DISTINCT(member_number)  FROM `member_idcard_cnt` WHERE  DATE(`dwn_date`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'");
		$dwn_mem_icard_count = $dwn_mem_icard->num_rows();

		$data = array(
			"from_date" => $from_date,
			"end_date" => $end_date,

			"new_registration_count_M" => count($new_o_results['new_registration_count_M']),
			//added by pooja mane 2023-11-16
			"profile_not_edited_count_M" => count($new_o_results['profile_not_edited_count_M']),
			"approve_new_member" => count($new_o_results['approve_new_member']),
			"pending_new_list_member" => $new_o_results['pending_new_list_member'],
			'approver_new_pending' => count($new_o_results['approver_new_pending']),
			'ap_rejected_count_o_mem' => count($new_o_results['ap_rejected_count_o_mem']),
			'rec_rejected_count_o_mem' => count($new_o_results['rec_rejected_count_o_mem']),

			"new_registration_count_NM" => count($new_nm_results['new_registration_count_NM']),
			"approve_new_nonmember" => count($new_nm_results['approve_new_nonmember']),
			"pending_new_list_nonmembers" => $new_nm_results['pending_new_list_nonmembers'],
			'approver_new_pending_non' => count($new_nm_results['approver_new_pending_non']),
			'ap_rejected_count_non_mem' => count($new_nm_results['ap_rejected_count_non_mem']),
			'rec_rejected_count_non_mem' => count($new_nm_results['rec_rejected_count_non_mem']),

			"edit_registration_count_M" => count($edit_o_results['edit_registration_count_M']),
			"approve_edit_member" => count($edit_o_results['approve_edit_member']),
			"pending_edit_member" => $edit_o_results['pending_edit_member'],
			'approver_edit_pending' => count($edit_o_results['approver_edit_pending']),
			'ap_rejected_count_edit_o_mem' => count($edit_o_results['ap_rejected_count_edit_o_mem']),
			'rec_rejected_count_edit_o_mem' => count($edit_o_results['rec_rejected_count_edit_o_mem']),

			'edit_registration_count_NM' => count($edit_nm_results['edit_registration_count_NM']),
			"approve_edit_nonmember" => count($edit_nm_results['approve_edit_nonmember']),
			"pending_edit_nonmember" => $edit_nm_results['pending_edit_nonmember'],
			'approver_edit_pending_non' => count($edit_nm_results['approver_edit_pending_non']),
			'ap_rejected_count_edit_non_mem' => count($edit_nm_results['ap_rejected_count_edit_non_mem']),
			'rec_rejected_count_edit_non_mem' => count($edit_nm_results['rec_rejected_count_edit_non_mem']),

			'dup_card_count' => $dup_card_count,
			'dwn_mem_icard_count' => $dwn_mem_icard_count,
		);

		$this->load->view('admin/kyc_asondate_report', $data);
	}

	public function kyc_report_new_ordinary_members($from_date, $end_date)
	{
		$data['new_registration_count_M'] = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('O') 
		AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'
		AND `isactive` = '1'
		AND `is_renewal` = '0'
		AND `isdeleted` = '0'")->result_array();
		//Removed AND `kyc_edit` = '0' pooja mane : 04-07-2023
		//echo $this->db->last_query();echo'<br>';//die;

		$data['profile_not_edited_count_M'] = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('O') 
		AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'
		AND `isactive` = '1'
		AND `is_renewal` = '0'
		AND `kyc_edit` = '0'
		AND `isdeleted` = '0'")->result_array();
		//echo $this->db->last_query();die;

		$data['approve_new_member'] = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('O') 
		AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'
		AND `isactive` = '1' 
		AND `isdeleted` = '0'
		AND `is_renewal` = '0'
		AND `kyc_edit` = '0' 
		AND `kyc_status` = '1'")->result_array();

		$status_zero = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('O') 
		AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'
		AND `isactive` = '1' 
		AND `isdeleted` = '0'
		AND `is_renewal` = '0' 
		AND `kyc_edit` = '0'
		AND `kyc_status` = '0'")->result_array();


		$regnumbers = "'" . implode("','", array_column($status_zero, 'regnumber')) . "'";

		$data['approver_new_pending'] = $this->db->query("SELECT regnumber FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` = 0 
		AND `kyc_status` = '0' 
		AND `kyc_state` = 1 
		AND `user_type` LIKE '%recommender%'
		AND `approved_by` = 0")->result_array();

		$data['rec_rejected_count_o_mem'] = $this->db->query("SELECT * FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
			AND `field_count` != 0 
			AND `kyc_status` = '0' 
			AND `user_type` LIKE '%recommender%'
			AND `approved_date` LIKE '%0000-00-00%'
			AND `mem_type` IN ('O')")->result_array();


		$data['ap_rejected_count_o_mem'] = $this->db->query("SELECT * FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
			AND `field_count` != 0 
			AND `kyc_status` = '0' 
			AND `user_type` LIKE '%approver%'
			AND `approved_date` LIKE '%0000-00-00%'
			AND `mem_type` IN ('O')")->result_array();
		// echo count($status_zero)." -".count($data['approver_new_pending'])."+".count($data['rec_rejected_count_o_mem'])."+".count($data['ap_rejected_count_o_mem']);
		// die;

		$data['pending_new_list_member'] = count($status_zero) - (count($data['approver_new_pending']) + count($data['rec_rejected_count_o_mem']) + count($data['ap_rejected_count_o_mem']));

		return $data;
	}

	public function kyc_report_new_non_members($from_date, $end_date)
	{
		$data['new_registration_count_NM'] = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('NM','DB') 
		AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_edit` = 0")->result_array();
		//echo $this->db->last_query();die;

		$data['approve_new_nonmember'] = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('NM','DB') 
		AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_status` = '1'
		AND `kyc_edit` = 0")->result_array();
		//echo $this->db->last_query();die;

		$status_zero = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('NM','DB') 
		AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_status` = '0'
		AND `kyc_edit` = 0")->result_array();
		//echo $this->db->last_query();die;

		$regnumbers = "'" . implode("','", array_column($status_zero, 'regnumber')) . "'";

		$data['approver_new_pending_non'] = $this->db->query("SELECT regnumber FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` = 0 
		AND `kyc_status` = '0' 
		AND `kyc_state` = 1 
		AND `user_type` LIKE '%recommender%'
		AND `approved_by` = 0")->result_array();
		//echo $this->db->last_query();die;

		$data['rec_rejected_count_non_mem'] = $this->db->query("SELECT * FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` != 0 
		AND `kyc_status` = '0' 
		AND `user_type` LIKE '%recommender%'
		AND `approved_date` LIKE '%0000-00-00%'
		AND `mem_type` IN ('NM','DB')")->result_array();
		//echo $this->db->last_query();die;

		$data['ap_rejected_count_non_mem'] = $this->db->query("SELECT * FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` != 0 
		AND `kyc_status` = '0' 
		AND `user_type` LIKE '%approver%'
		AND `approved_date` LIKE '%0000-00-00%'
		AND `mem_type` IN ('NM','DB')")->result_array();

		$data['pending_new_list_nonmembers'] = count($status_zero) - (count($data['approver_new_pending_non']) + count($data['rec_rejected_count_non_mem']) + count($data['ap_rejected_count_non_mem']));

		return $data;
	}

	public function kyc_report_edit_ordinary_members($from_date, $end_date)
	{
						

		$data['edit_registration_count_M'] = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('O') 
		AND ( (DATE(`editedon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "') OR (DATE(`images_editedon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "' )) 
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_edit` = 1")->result_array();
		// echo $this->db->last_query();die;

		$data['approve_edit_member'] = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('O') 
	    AND ( (DATE(`editedon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "') OR (DATE(`images_editedon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "' )) 
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_status` = '1'
		AND `kyc_edit` = 1")->result_array();

		$status_zero = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('O') 
		AND ( (DATE(`editedon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "') OR (DATE(`images_editedon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "' )) 
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_status` = '0'
		AND `kyc_edit` = 1")->result_array();

		$regnumbers = "'" . implode("','", array_column($status_zero, 'regnumber')) . "'";
		// echo $this->db->last_query();
		// echo count($status_zero);
		// echo "<br>";
		// die;

		$data['approver_edit_pending'] = $this->db->query("SELECT regnumber FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` = 0 
		AND `kyc_status` = '0' 
		AND `kyc_state` = 1 
		AND `user_type` LIKE '%recommender%'
		AND `approved_by` = 0")->result_array();

		$data['rec_rejected_count_edit_o_mem'] = $this->db->query("SELECT * FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` != 0 
		AND `kyc_status` = '0' 
		AND `user_type` LIKE '%recommender%'
		AND `approved_date` LIKE '%0000-00-00%'
		AND `mem_type` IN ('O')")->result_array();

		$data['ap_rejected_count_edit_o_mem'] = $this->db->query("SELECT * FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` != 0 
		AND `kyc_status` = '0' 
		AND `user_type` LIKE '%approver%'
		AND `approved_date` LIKE '%0000-00-00%'
		AND `mem_type` IN ('O')")->result_array();

		$data['pending_edit_member'] = count($status_zero) - ( count($data['approver_edit_pending']) + count($data['rec_rejected_count_edit_o_mem']) + count($data['ap_rejected_count_edit_o_mem']) );


		return $data;
	}

	public function kyc_report_edit_non_members($from_date, $end_date)
	{
		$data['edit_registration_count_NM'] = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('NM','DB') 
			AND ( (DATE(`editedon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "') OR (DATE(`images_editedon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "' )) 
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_edit` = 1")->result_array();

		$data['approve_edit_nonmember'] = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('NM','DB') 
		AND ( (DATE(`editedon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "') OR (DATE(`images_editedon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "' )) 
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_status` = '1'
		AND `kyc_edit` = 1")->result_array();

		$status_zero = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('NM','DB') 
		AND ( (DATE(`editedon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "') OR (DATE(`images_editedon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "' ))
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_status` = '0'
		AND `kyc_edit` = 1")->result_array();

		$regnumbers = "'" . implode("','", array_column($status_zero, 'regnumber')) . "'";

		$data['approver_edit_pending_non'] = $this->db->query("SELECT regnumber FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` = 0 
		AND `kyc_status` = '0' 
		AND `kyc_state` = 1 
		AND `user_type` LIKE '%recommender%'
		AND `approved_by` = 0")->result_array();

		$data['rec_rejected_count_edit_non_mem'] = $this->db->query("SELECT * FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` != 0 
		AND `kyc_status` = '0' 
		AND `user_type` LIKE '%recommender%'
		AND `approved_date` LIKE '%0000-00-00%'
		AND `mem_type` IN ('NM','DB')")->result_array();

		$data['ap_rejected_count_edit_non_mem'] = $this->db->query("SELECT * FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` != 0 
		AND `kyc_status` = '0' 
		AND `user_type` LIKE '%approver%'
		AND `approved_date` LIKE '%0000-00-00%'
		AND `mem_type` IN ('NM','DB')")->result_array();

		$data['pending_edit_nonmember'] = count($status_zero) - (count($data['approver_edit_pending_non']) + count($data['rec_rejected_count_edit_non_mem']) + count($data['ap_rejected_count_edit_non_mem']));

		return $data;
	}
	/* by SAGAR WALZADE : Code end here */

	/*
	- by SAGAR WALZADE : Code start here
	- date : 18-4-2022 (code updated by 4-5-2022)
	- function use : Function to fetch list of all pending (new) members for recommender.
	- Changes : Previous function was wrong, so written complete new function + code optimized 
	- (previous function renameed into "recommender_new_download_CSV_old")
	*/
	public function recommender_new_download_CSV()
	{
		$from_date = $this->uri->segment(4);
		$end_date = $this->uri->segment(5);
		if ($from_date == '' && $end_date == '') {
			$from_date = date("Y-m-d", strtotime("first day of previous month"));
			$end_date = date("Y-m-d", strtotime("last day of previous month"));
			// $from_date = '2021-12-01';
			// $end_date = '2022-05-03';
		}
		$csv = "Member No,Member type,Registration date \n";

		// ordinary member functionality
		$status_zero = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('O','NM','DB') 
		AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_status` = '0'
		AND `kyc_edit` = 0")->result_array();

		$regnumbers = "'" . implode("','", array_column($status_zero, 'regnumber')) . "'";

		$approver_new_pending = $this->db->query("SELECT regnumber FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` = 0 
		AND `kyc_status` = '0' 
		AND `kyc_state` = 1 
		AND `user_type` LIKE '%recommender%'
		AND `approved_by` = 0")->result_array();

		$rec_rejected_count = $this->db->query("SELECT regnumber FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
			AND `field_count` != 0 
			AND `kyc_status` = '0' 
			AND `user_type` LIKE '%recommender%'
			AND `approved_date` LIKE '%0000-00-00%'
			AND `mem_type` IN ('O','NM','DB')")->result_array();

		$ap_rejected_count = $this->db->query("SELECT regnumber FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
			AND `field_count` != 0 
			AND `kyc_status` = '0' 
			AND `user_type` LIKE '%approver%'
			AND `approved_date` LIKE '%0000-00-00%'
			AND `mem_type` IN ('O','NM','DB')")->result_array();

		$non_kyc = array_column($status_zero, 'regnumber');
		$approver_pending = array_column($approver_new_pending, 'regnumber');
		$rec_rejected = array_column($rec_rejected_count, 'regnumber');
		$ap_rejected = array_column($ap_rejected_count, 'regnumber');
		$exclude_regnumbers = array_merge($approver_pending, $rec_rejected, $ap_rejected);
		$recommender_pending_array = array_diff($non_kyc, $exclude_regnumbers);
		$recommender_pending_regnumbers = "'" . implode("','", $recommender_pending_array) . "'";

		$pending_for_recommender_new = $this->db->query(
			"SELECT `regnumber`, `registrationtype`, `createdon`
			FROM `member_registration` 
			WHERE regnumber != '' 
			AND `benchmark_disability` != 'Y'
			AND `isactive` = '1' 
			AND `isdeleted` = '0'
			AND regnumber IN (" . $recommender_pending_regnumbers . ")"
		)->result_array();

		foreach ($pending_for_recommender_new  as $record) {
			$csv .= $record['regnumber'] . ',' . $record['registrationtype'] . ',' . $record['createdon'] . "\n";
		}

		// non member functionality
		// $status_zero = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		// WHERE regnumber != '' 
		// AND registrationtype IN ('NM','DB') 
		// AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'
		// AND `benchmark_disability` != 'Y'
		// AND `isactive` = '1' 
		// AND `isdeleted` = '0' 
		// AND `kyc_status` = '0'
		// AND `kyc_edit` = 0")->result_array();

		// $regnumbers = "'" . implode("','", array_column($status_zero, 'regnumber')) . "'";

		// $approver_new_pending = $this->db->query("SELECT regnumber FROM `member_kyc` 
		// WHERE kyc_id IN (
		// 	SELECT MAX(kyc_id)
		// 	FROM member_kyc
		// 	where regnumber IN (" . $regnumbers . ")
		// 	GROUP BY regnumber
		// 	)
		// AND `field_count` = 0 
		// AND `kyc_status` = '0' 
		// AND `kyc_state` = 1 
		// AND `user_type` LIKE '%recommender%'
		// AND `approved_by` = 0")->result_array();

		// $non_kyc = array_column($status_zero, 'regnumber');
		// $approver_pending = array_column($approver_new_pending, 'regnumber');
		// $recommender_pending_array = array_diff($non_kyc, $approver_pending);
		// $recommender_pending_regnumbers = "'" . implode("','", $recommender_pending_array) . "'";

		// $pending_for_recommender_non_new = $this->db->query(
		// 	"SELECT `regnumber`, `registrationtype`, `createdon` 
		// 	FROM `member_registration` 
		// 	WHERE regnumber != '' 
		// 	AND `benchmark_disability` != 'Y'
		// 	AND `isactive` = '1' 
		// 	AND `isdeleted` = '0'
		// 	AND regnumber IN (" . $recommender_pending_regnumbers . ")")->result_array();

		// foreach ($pending_for_recommender_non_new  as $record) {
		// 	$csv .= $record['regnumber'] . ',' . $record['registrationtype'] . ',' . $record['createdon'] . "\n";
		// }

		$filename = "new_member_pending_for_recommender.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=' . $filename);
		$csv_handler = fopen('php://output', 'w');
		fwrite($csv_handler, $csv);
		fclose($csv_handler);
	}
	/* by SAGAR WALZADE : Code end here */

	/*
	- by SAGAR WALZADE : Code start here
	- date : 18-4-2022 (code updated by 4-5-2022)
	- function use : Function to fetch list of all pending (edited) members for recommender.
	- Changes : Previous function was wrong, so written complete new function + code optimized
	- (previous function renameed into "recommender_edit_download_CSV_old")
	*/
	public function recommender_edit_download_CSV()
	{
		$from_date = $this->uri->segment(4);
		$end_date = $this->uri->segment(5);
		if ($from_date == '' && $end_date == '') {
			$from_date = date("Y-m-d", strtotime("first day of previous month"));
			$end_date = date("Y-m-d", strtotime("last day of previous month"));
			// $from_date = '2021-12-01';
			// $end_date = '2022-05-03';
		}
		$csv = "Member No,Member type, Data edited date, Image edited date  \n"; //Column headers

		// ordinary member functionality
		$status_zero = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('O','NM','DB') 
		AND ( (DATE(`editedon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "') OR (DATE(`images_editedon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "' ))
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_status` = '0'
		AND `kyc_edit` = 1")->result_array();

		$regnumbers = "'" . implode("','", array_column($status_zero, 'regnumber')) . "'";

		$approver_edit_pending = $this->db->query("SELECT regnumber FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` = 0 
		AND `kyc_status` = '0' 
		AND `kyc_state` = 1 
		AND `user_type` LIKE '%recommender%'
		AND `approved_by` = 0")->result_array();

		$rec_rejected_count_edit = $this->db->query("SELECT * FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` != 0 
		AND `kyc_status` = '0' 
		AND `user_type` LIKE '%recommender%'
		AND `approved_date` LIKE '%0000-00-00%'
		AND `mem_type` IN ('O','NM','DB')")->result_array();

		$ap_rejected_count_edit = $this->db->query("SELECT * FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` != 0 
		AND `kyc_status` = '0' 
		AND `user_type` LIKE '%approver%'
		AND `approved_date` LIKE '%0000-00-00%'
		AND `mem_type` IN ('O','NM','DB')")->result_array();

		$non_kyc = array_column($status_zero, 'regnumber');
		$approver_pending = array_column($approver_edit_pending, 'regnumber');
		$rec_rejected = array_column($rec_rejected_count_edit, 'regnumber');
		$ap_rejected = array_column($ap_rejected_count_edit, 'regnumber');
		$exclude_regnumbers = array_merge($approver_pending, $rec_rejected, $ap_rejected);
		$recommender_pending_array = array_diff($non_kyc, $exclude_regnumbers);
		$recommender_pending_regnumbers = "'" . implode("','", $recommender_pending_array) . "'";

		$pending_for_recommender_edit = $this->db->query(
			"SELECT `regnumber`, `registrationtype`, `editedon`, `images_editedon` 
			FROM `member_registration` 
			WHERE regnumber != '' 
			AND `benchmark_disability` != 'Y'
			AND `isactive` = '1' 
			AND `isdeleted` = '0'
			AND regnumber IN (" . $recommender_pending_regnumbers . ")"
		)->result_array();

		foreach ($pending_for_recommender_edit  as $record) {
			$csv .= $record['regnumber'] . ',' . $record['registrationtype'] . ',' . $record['editedon'] . ',' . $record['images_editedon'] . "\n";
		}

		// // non member functionality
		// $status_zero = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		// WHERE regnumber != '' 
		// AND registrationtype IN ('NM','DB') 
		// AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'
		// AND `benchmark_disability` != 'Y'
		// AND `isactive` = '1' 
		// AND `isdeleted` = '0' 
		// AND `kyc_status` = '0'
		// AND `kyc_edit` = 1")->result_array();

		// $regnumbers = "'" . implode("','", array_column($status_zero, 'regnumber')) . "'";

		// $approver_edit_pending = $this->db->query("SELECT regnumber FROM `member_kyc` 
		// WHERE kyc_id IN (
		// 	SELECT MAX(kyc_id)
		// 	FROM member_kyc
		// 	where regnumber IN (" . $regnumbers . ")
		// 	GROUP BY regnumber
		// 	)
		// AND `field_count` = 0 
		// AND `kyc_status` = '0' 
		// AND `kyc_state` = 1 
		// AND `user_type` LIKE '%recommender%'
		// AND `approved_by` = 0")->result_array();

		// $non_kyc = array_column($status_zero, 'regnumber');
		// $approver_pending = array_column($approver_edit_pending, 'regnumber');
		// $recommender_pending_array = array_diff($non_kyc, $approver_pending);
		// $recommender_pending_regnumbers = "'" . implode("','", $recommender_pending_array) . "'";

		// $pending_for_recommender_non_edit = $this->db->query(
		// 	"SELECT `regnumber`, `registrationtype`, `editedon`, `images_editedon` 
		// 	FROM `member_registration` 
		// 	WHERE regnumber != '' 
		// 	AND `benchmark_disability` != 'Y'
		// 	AND `isactive` = '1' 
		// 	AND `isdeleted` = '0'
		// 	AND regnumber IN (" . $recommender_pending_regnumbers . ")")->result_array();

		// foreach ($pending_for_recommender_non_edit  as $record) {
		// 	$csv .= $record['regnumber'] . ',' . $record['registrationtype'] . ',' . $record['editedon'] . ',' . $record['images_editedon'] . "\n";
		// }

		$filename = "edit_member_pending_for_recommender.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=' . $filename);
		$csv_handler = fopen('php://output', 'w');
		fwrite($csv_handler, $csv);
		fclose($csv_handler);
	}
	/* by SAGAR WALZADE : Code end here */

	/*
	- by SAGAR WALZADE : Code start here
	- date : 20-4-2022 (code updated by 4-5-2022)
	- function use : Function to fetch list of all pending (new) members for approver.
	- Changes : Previous function was wrong, so written complete new function + code optimized
	- (previous function renameed into "approver_new_download_CSV_old")
	*/
	public function approver_new_download_CSV()
	{
		$from_date = $this->uri->segment(4);
		$end_date = $this->uri->segment(5);
		if ($from_date == '' && $end_date == '') {
			$from_date = date("Y-m-d", strtotime("first day of previous month"));
			$end_date = date("Y-m-d", strtotime("last day of previous month"));
			// $from_date = '2021-12-01';
			// $end_date = '2022-05-03';
		}
		$csv = "Member No,Member type, Recommend date \n"; //Column headers

		$status_zero = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('O') 
		AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_status` = '0'
		AND `kyc_edit` = 0")->result_array();

		$regnumbers = "'" . implode("','", array_column($status_zero, 'regnumber')) . "'";

		$ordinary_approver_pending = $this->db->query("SELECT `regnumber`,`mem_type`,`recommended_date` FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` = 0 
		AND `kyc_status` = '0' 
		AND `kyc_state` = 1 
		AND `user_type` LIKE '%recommender%'
		AND `approved_by` = 0")->result_array();

		$status_zero = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('NM','DB') 
		AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_status` = '0'
		AND `kyc_edit` = 0")->result_array();

		$regnumbers = "'" . implode("','", array_column($status_zero, 'regnumber')) . "'";

		$nonmember_approver_pending = $this->db->query("SELECT `regnumber`,`mem_type`,`recommended_date` FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` = 0 
		AND `kyc_status` = '0' 
		AND `kyc_state` = 1 
		AND `user_type` LIKE '%recommender%'
		AND `approved_by` = 0")->result_array();

		foreach ($ordinary_approver_pending  as $record) {
			$csv .= $record['regnumber'] . ',' . $record['mem_type'] . ',' . $record['recommended_date'] . "\n";
		}

		foreach ($nonmember_approver_pending  as $record) {
			$csv .= $record['regnumber'] . ',' . $record['mem_type'] . ',' . $record['recommended_date'] . "\n";
		}

		$filename = "new_member_pending_for_approver.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=' . $filename);
		$csv_handler = fopen('php://output', 'w');
		fwrite($csv_handler, $csv);
		fclose($csv_handler);
	}
	/* by SAGAR WALZADE : Code end here */

	/*
	- by SAGAR WALZADE : Code start here
	- date : 20-4-2022 (code updated by 4-5-2022)
	- function use : Function to fetch list of all pending (edited) members for approver.
	- Changes : Previous function was wrong, so written complete new function + code optimized
	- (previous function renameed into "approver_edit_download_CSV_old")
	*/
	public function approver_edit_download_CSV()
	{
		$from_date = $this->uri->segment(4);
		$end_date = $this->uri->segment(5);
		if ($from_date == '' && $end_date == '') {
			$from_date = date("Y-m-d", strtotime("first day of previous month"));
			$end_date = date("Y-m-d", strtotime("last day of previous month"));
			// $from_date = '2021-12-01';
			// $end_date = '2022-05-03';
		}
		$csv = "Member No,Member type, Recommend date \n"; //Column headers

		$status_zero = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('O') 
		AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_status` = '0'
		AND `kyc_edit` = 1")->result_array();

		$regnumbers = "'" . implode("','", array_column($status_zero, 'regnumber')) . "'";

		$ordinary_approver_pending = $this->db->query("SELECT `regnumber`,`mem_type`,`recommended_date` FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` = 0 
		AND `kyc_status` = '0' 
		AND `kyc_state` = 1 
		AND `user_type` LIKE '%recommender%'
		AND `approved_by` = 0")->result_array();

		$status_zero = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('NM','DB') 
		AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_status` = '0'
		AND `kyc_edit` = 1")->result_array();

		$regnumbers = "'" . implode("','", array_column($status_zero, 'regnumber')) . "'";

		$nonmember_approver_pending = $this->db->query("SELECT `regnumber`,`mem_type`,`recommended_date` FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` = 0 
		AND `kyc_status` = '0' 
		AND `kyc_state` = 1 
		AND `user_type` LIKE '%recommender%'
		AND `approved_by` = 0")->result_array();

		foreach ($ordinary_approver_pending  as $record) {
			$csv .= $record['regnumber'] . ',' . $record['mem_type'] . ',' . $record['recommended_date'] . "\n";
		}

		foreach ($nonmember_approver_pending  as $record) {
			$csv .= $record['regnumber'] . ',' . $record['mem_type'] . ',' . $record['recommended_date'] . "\n";
		}

		$filename = "edit_member_pending_for_approver.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=' . $filename);
		$csv_handler = fopen('php://output', 'w');
		fwrite($csv_handler, $csv);
		fclose($csv_handler);
	}
	/* by SAGAR WALZADE : Code end here */

	/*
	- by SAGAR WALZADE : Code start here
	- date : 20-4-2022 (code updated by 4-5-2022)
	- function use : Function to fetch list of all rejected (new) members by recommender.
	- Changes : Previous function was wrong, so written complete new function
	- (previous function renameed into "approver_rejected_download_CSV_old")
	*/
	public function recommender_rejected_download_CSV()
	{
		$from_date = $this->uri->segment(4);
		$end_date = $this->uri->segment(5);
		if ($from_date == '' && $end_date == '') {
			$from_date = date("Y-m-d", strtotime("first day of previous month"));
			$end_date = date("Y-m-d", strtotime("last day of previous month"));
			// $from_date = '2021-12-01';
			// $end_date = '2022-05-03';
		}
		$csv = "Member No,Member type, Recommend date \n"; //Column headers

		$status_zero = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('O', 'NM', 'DB') 
		AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_status` = '0'
		AND `kyc_edit` = 0")->result_array();

		$regnumbers = "'" . implode("','", array_column($status_zero, 'regnumber')) . "'";

		$rejected_members = $this->db->query("SELECT `regnumber`, `mem_type`, `recommended_date` 
		FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
			AND `field_count` != 0 
			AND `kyc_status` = '0' 
			AND `user_type` LIKE '%recommender%'
			AND `approved_date` LIKE '%0000-00-00%'
			AND `mem_type` IN ('O', 'NM', 'DB')")->result_array();

		foreach ($rejected_members  as $record) {
			$csv .= $record['regnumber'] . ',' . $record['mem_type'] . ',' . $record['recommended_date'] . "\n";
		}

		$filename = "recommender_new_rejected_member.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=' . $filename);
		$csv_handler = fopen('php://output', 'w');
		fwrite($csv_handler, $csv);
		fclose($csv_handler);
	}
	/* by SAGAR WALZADE : Code end here */

	/*
	- by SAGAR WALZADE : Code start here
	- date : 20-4-2022 (code updated by 4-5-2022)
	- function use : Function to fetch list of all rejected (new) members by recommender.
	- Changes : Previous function was wrong, so written complete new function
	- (previous function renameed into "approver_rejected_download_CSV_old")
	*/
	public function recommender_rejected_download_edited_CSV()
	{
		$from_date = $this->uri->segment(4);
		$end_date = $this->uri->segment(5);
		if ($from_date == '' && $end_date == '') {
			$from_date = date("Y-m-d", strtotime("first day of previous month"));
			$end_date = date("Y-m-d", strtotime("last day of previous month"));
			// $from_date = '2021-12-01';
			// $end_date = '2022-05-03';
		}
		$csv = "Member No,Member type, Recommend date \n"; //Column headers

		$status_zero = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('O', 'NM', 'DB') 
		AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_status` = '0'
		AND `kyc_edit` = 1")->result_array();

		$regnumbers = "'" . implode("','", array_column($status_zero, 'regnumber')) . "'";

		$edit_rejected_members = $this->db->query("SELECT `regnumber`, `mem_type`, `recommended_date` 
		FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
			AND `field_count` != 0 
			AND `kyc_status` = '0' 
			AND `user_type` LIKE '%recommender%'
			AND `approved_date` LIKE '%0000-00-00%'
			AND `mem_type` IN ('O', 'NM', 'DB')")->result_array();

		foreach ($edit_rejected_members  as $record) {
			$csv .= $record['regnumber'] . ',' . $record['mem_type'] . ',' . $record['recommended_date'] . "\n";
		}

		$filename = "recommender_edit_rejected_member.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=' . $filename);
		$csv_handler = fopen('php://output', 'w');
		fwrite($csv_handler, $csv);
		fclose($csv_handler);
	}
	/* by SAGAR WALZADE : Code end here */

	/*
	- by SAGAR WALZADE : Code start here
	- date : 20-4-2022 (code updated by 3-5-2022)
	- function use : Function to fetch list of all rejected (new) members by approver.
	- Changes : Previous function was wrong, so written complete new function
	- (previous function renameed into "approver_rejected_download_CSV_old")
	*/
	public function approver_rejected_download_CSV()
	{
		$from_date = $this->uri->segment(4);
		$end_date = $this->uri->segment(5);
		if ($from_date == '' && $end_date == '') {
			$from_date = date("Y-m-d", strtotime("first day of previous month"));
			$end_date = date("Y-m-d", strtotime("last day of previous month"));
			// $from_date = '2021-12-01';
			// $end_date = '2022-05-03';
		}
		$csv = "Member No,Member type, Recommend date \n"; //Column headers

		$status_zero = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('O') 
		AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_status` = '0'
		AND `kyc_edit` = 0")->result_array();

		$regnumbers = "'" . implode("','", array_column($status_zero, 'regnumber')) . "'";

		$ordinary_rejected = $this->db->query("SELECT `regnumber`, `mem_type`, `recommended_date` FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` != 0 
		AND `kyc_status` = '0' 
		AND `user_type` LIKE '%approver%'
		AND `approved_date` LIKE '%0000-00-00%'
		AND `mem_type` IN ('O')")->result_array();

		$status_zero = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('NM','DB') 
		AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_status` = '0'
		AND `kyc_edit` = 0")->result_array();

		$regnumbers = "'" . implode("','", array_column($status_zero, 'regnumber')) . "'";

		$nonmember_rejected = $this->db->query("SELECT `regnumber`, `mem_type`, `recommended_date` FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` != 0 
		AND `kyc_status` = '0' 
		AND `user_type` LIKE '%approver%'
		AND `approved_date` LIKE '%0000-00-00%'
		AND `mem_type` IN ('NM','DB')")->result_array();


		foreach ($ordinary_rejected  as $record) {
			$csv .= $record['regnumber'] . ',' . $record['mem_type'] . ',' . $record['recommended_date'] . "\n";
		}

		foreach ($nonmember_rejected  as $record) {
			$csv .= $record['regnumber'] . ',' . $record['mem_type'] . ',' . $record['recommended_date'] . "\n";
		}

		$filename = "approver_new_rejected_member.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=' . $filename);
		$csv_handler = fopen('php://output', 'w');
		fwrite($csv_handler, $csv);
		fclose($csv_handler);
	}
	/* by SAGAR WALZADE : Code end here */


	/*
	- by SAGAR WALZADE : Code start here
	- date : 20-4-2022 (code updated by 3-5-2022)
	- function use : Function to fetch list of all rejected (edited) members by approver.
	- Changes : There is no function available for this, and this should be present on asondate report page.
	*/
	public function approver_rejected_download_edited_CSV()
	{
		$from_date = $this->uri->segment(4);
		$end_date = $this->uri->segment(5);
		if ($from_date == '' && $end_date == '') {
			$from_date = date("Y-m-d", strtotime("first day of previous month"));
			$end_date = date("Y-m-d", strtotime("last day of previous month"));
			// $from_date = '2021-12-01';
			// $end_date = '2022-05-03';
		}
		$csv = "Member No,Member type, Recommend date \n"; //Column headers

		$status_zero = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('O') 
		AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_status` = '0'
		AND `kyc_edit` = 1")->result_array();

		$regnumbers = "'" . implode("','", array_column($status_zero, 'regnumber')) . "'";

		$ordinary_rejected = $this->db->query("SELECT `regnumber`, `mem_type`, `recommended_date` FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` != 0 
		AND `kyc_status` = '0' 
		AND `user_type` LIKE '%approver%'
		AND `approved_date` LIKE '%0000-00-00%'
		AND `mem_type` IN ('O')")->result_array();

		$status_zero = $this->db->query("SELECT `regnumber` FROM `member_registration` 
		WHERE regnumber != '' 
		AND registrationtype IN ('NM','DB') 
		AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'
		AND `benchmark_disability` != 'Y'
		AND `isactive` = '1' 
		AND `isdeleted` = '0' 
		AND `kyc_status` = '0'
		AND `kyc_edit` = 1")->result_array();

		$regnumbers = "'" . implode("','", array_column($status_zero, 'regnumber')) . "'";

		$nonmember_rejected = $this->db->query("SELECT `regnumber`, `mem_type`, `recommended_date` FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` != 0 
		AND `kyc_status` = '0' 
		AND `user_type` LIKE '%approver%'
		AND `approved_date` LIKE '%0000-00-00%'
		AND `mem_type` IN ('NM','DB')")->result_array();

		foreach ($ordinary_rejected as $record) {
			$csv .= $record['regnumber'] . ',' . $record['mem_type'] . ',' . $record['recommended_date'] . "\n";
		}

		foreach ($nonmember_rejected as $record) {
			$csv .= $record['regnumber'] . ',' . $record['mem_type'] . ',' . $record['recommended_date'] . "\n";
		}

		$filename = "approver_edit_rejected_member.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=' . $filename);
		$csv_handler = fopen('php://output', 'w');
		fwrite($csv_handler, $csv);
		fclose($csv_handler);
	}
	/* by SAGAR WALZADE : Code end here */


	/*
	- by SAGAR WALZADE : Code start here
	- date : 18-4-2022
	- function use : Return multiple result set from store procedure result (for internal function use - asondate)
	*/
	public function multiple_result($sql)
	{
		if (empty($sql))
			return NULL;

		$i = 0;
		$set = [];

		if (mysqli_multi_query($this->db->conn_id, $sql)) {
			do {
				mysqli_next_result($this->db->conn_id);

				if (FALSE != $result = mysqli_store_result($this->db->conn_id)) {
					$row_id = 0;

					while ($row = $result->fetch_object()) {
						$set[$i][$row_id] = $row;
						$row_id++;
					}
				}

				$i++;
			} while (mysqli_more_results($this->db->conn_id));
		}

		return $set;
	}
	/* by SAGAR WALZADE : Code end here */


	// FUNCTION NOT IN USE (RENAMED)
	public function statistic_old()
	{
		$a_remianing_details = array();
		$today = date("Y-m-d");
		$srch_date = $this->input->post('from_date');

		if (isset($srch_date)) {
			if ($srch_date != '') {
				$error = '';
				// For recomender
				$this->db->where('roleid', '4');
				$rocomeder = $this->UserModel->getRecords("administrators");
				$rocomeder_details = $rocomeder->result_array();

				$i = 0;
				foreach ($rocomeder_details as $record) {
					$this->db->where('recommended_by', $record['id']);
					$this->db->like('recommended_date ', $srch_date);

					$rocomeder_cnt = $this->UserModel->getRecordCount("member_kyc");
					$recomended_cnt[$i] = $rocomeder_cnt;

					// Remaining count for recomender

					$this->db->where('user_id', $record['id']);
					$this->db->where('date ', $srch_date);
					$r_remianing = $this->UserModel->getRecords("admin_kyc_users", "allocated_count");
					$r_remianing_details = $r_remianing->result_array();
					$r_rem_cnt = 0;
					foreach ($r_remianing_details as $r_rem) {

						$str = $r_rem['allocated_count'];
						$r_rem_cnt += $str;
					}

					$rem_cnt = $r_rem_cnt - $recomended_cnt[$i];
					$recomender_rem_cnt[$i] = $rem_cnt;
					$i++;
				}

				// For approver
				$this->db->where('roleid', '5');
				$approver = $this->UserModel->getRecords("administrators");
				$approver_details = $approver->result_array();

				$j = 0;
				foreach ($approver_details as $result) {
					$this->db->where('approved_by', $result['id']);
					$this->db->like('approved_date ', $srch_date);
					$approver_cnt = $this->UserModel->getRecordCount("member_kyc");
					$approved_cnt[$j] = $approver_cnt;


					// Remaining count for approver
					$this->db->where('user_id', $result['id']);
					$this->db->where('date ', $srch_date);
					$a_remianing = $this->UserModel->getRecords("admin_kyc_users", "allocated_count");
					$a_remianing_details = $a_remianing->result_array();
					$a_rem_cnt = 0;
					foreach ($a_remianing_details as $a_rem) {

						$str = $a_rem['allocated_count'];
						$a_rem_cnt += $str;
					}
					$a_rem_cnt = $a_rem_cnt - $approved_cnt[$j];
					$approver_rem_cnt[$j] = $a_rem_cnt;
					$j++;
				}
			} elseif ($srch_date == '') {
				$error = '<span style="">Plese select date</span>';
				// For recomender
				$this->db->where('roleid', '4');
				$rocomeder = $this->UserModel->getRecords("administrators");
				$rocomeder_details = $rocomeder->result_array();

				$i = 0;
				foreach ($rocomeder_details as $record) {
					$this->db->where('recommended_by', $record['id']);
					$this->db->like('recommended_date ', $today);

					$rocomeder_cnt = $this->UserModel->getRecordCount("member_kyc");
					$recomended_cnt[$i] = $rocomeder_cnt;


					// Remaining count for recomender
					$this->db->where('user_id', $record['id']);
					$this->db->where('date ', $today);
					$r_remianing = $this->UserModel->getRecords("admin_kyc_users", "allocated_count");
					$r_remianing_details = $r_remianing->result_array();
					$r_rem_cnt = 0;
					foreach ($r_remianing_details as $r_rem) {

						$str = $r_rem['allocated_count'];
						$r_rem_cnt += $str;
					}
					$rem_cnt = $r_rem_cnt - $recomended_cnt[$i];
					$recomender_rem_cnt[$i] = $rem_cnt;
					$i++;
				}


				// For approver
				$this->db->where('roleid', '5');
				$approver = $this->UserModel->getRecords("administrators");
				$approver_details = $approver->result_array();

				$j = 0;
				foreach ($approver_details as $result) {
					$this->db->where('approved_by', $result['id']);
					$this->db->like('approved_date ', $today);
					$approver_cnt = $this->UserModel->getRecordCount("member_kyc");
					$approved_cnt[$j] = $approver_cnt;


					// Remaining count for approver
					$this->db->where('user_id', $result['id']);
					$this->db->where('date ', $today);
					$a_remianing = $this->UserModel->getRecords("admin_kyc_users", "allocated_count");
					$a_remianing_details = $a_remianing->result_array();
					$a_rem_cnt = 0;
					foreach ($a_remianing_details as $a_rem) {

						$str = $a_rem['allocated_count'];
						$a_rem_cnt += $str;
					}
					$a_rem_cnt = $a_rem_cnt - $approved_cnt[$j];
					$approver_rem_cnt[$j] = $a_rem_cnt;
					$j++;
				}
			}
		} else {
			// For recomender
			$this->db->where('roleid', '4');
			$rocomeder = $this->UserModel->getRecords("administrators");
			$rocomeder_details = $rocomeder->result_array();

			$i = 0;
			foreach ($rocomeder_details as $record) {
				$this->db->where('recommended_by', $record['id']);
				$this->db->like('recommended_date ', $today);

				$rocomeder_cnt = $this->UserModel->getRecordCount("member_kyc");
				$recomended_cnt[$i] = $rocomeder_cnt;


				// Remaining count for recomender
				$this->db->where('user_id', $record['id']);
				$this->db->where('date ', $today);
				$r_remianing = $this->UserModel->getRecords("admin_kyc_users", "allocated_count");
				$r_remianing_details = $r_remianing->result_array();
				$r_rem_cnt = 0;
				if (!empty($r_remianing_details)) {
					foreach ($r_remianing_details as $r_rem) {
						$str = $r_rem['allocated_count'];
						$r_rem_cnt += $str;
					}
				}

				$rem_cnt = $r_rem_cnt - $recomended_cnt[$i];
				$recomender_rem_cnt[$i] = $rem_cnt;

				$i++;
			}


			// For approver
			$this->db->where('roleid', '5');
			$approver = $this->UserModel->getRecords("administrators");
			$approver_details = $approver->result_array();

			$j = 0;
			foreach ($approver_details as $result) {
				$this->db->where('approved_by', $result['id']);
				$this->db->like('approved_date ', $today);
				$approver_cnt = $this->UserModel->getRecordCount("member_kyc");
				$approved_cnt[$j] = $approver_cnt;


				// Remaining count for approver
				$this->db->where('user_id', $result['id']);
				$this->db->where('date ', $today);
				$a_remianing = $this->UserModel->getRecords("admin_kyc_users", "allocated_count");
				$a_remianing_details = $a_remianing->result_array();
				$a_rem_cnt = 0;
				if (!empty($a_remianing_details)) {
					foreach ($a_remianing_details as $a_rem) {
						$str = $a_rem['allocated_count'];
						$a_rem_cnt += $str;
					}
				}
				$a_rem_cnt = $a_rem_cnt - $approved_cnt[$j];
				$approver_rem_cnt[$j] = $a_rem_cnt;
				$j++;
			}
			$error = '';
		}


		$data = array("rocomeder_details" => $rocomeder_details, "approver_details" => $approver_details, "recomended_cnt" => $recomended_cnt, "approved_cnt" => $approved_cnt, "recomender_rem_cnt" => $recomender_rem_cnt, "approver_rem_cnt" => $approver_rem_cnt, "error" => $error);

		$this->load->view('admin/kyc_statistic_report', $data);
	}

	// FUNCTION NOT IN USE (RENAMED)
	public function asondate_old()
	{
		$from_date = $end_date = '';

		if (isset($_POST['submit'])) {
			$from_date = $this->input->post('from_date'); //'2019-07-01';
			$end_date = $this->input->post('to_date'); //'2019-07-31';
			//echo $from_date; die;

			$kyc_start_date = $this->config->item('kyc_start_date');
			$new_registration_count = $total_edit_count = $pending_new_list_member = $pending_edit_member = $non_member_pending = $approve_edit_member = $approve_new_member = 0;
			/*New registration count */

			/*$query = $this->db->query("SELECT `regnumber`  FROM `member_registration` WHERE `isactive` = '1' AND DATE(`createdon`) >= '".$kyc_start_date."'");
				 	 $new_registration_count= $query->num_rows();*/
			/*echo $this->db->last_query();
					exit;*/
			#-------------------------member o new registration count -----------------------------------------#
			$query_M = $this->db->query("SELECT `regnumber`  FROM `member_registration` WHERE  registrationtype   IN ('O') AND  `isactive` = '1' AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'");
			$new_registration_count_M = $query_M->num_rows();
			//echo  $new_registration_count_M ;die;


			$query_NM = $this->db->query("SELECT `regnumber`  FROM `member_registration` WHERE  registrationtype  IN ('NM','DB') AND  `isactive` = '1' AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'");
			$new_registration_count_NM = $query_NM->num_rows();
			#-------------------------End member o new registration count -----------------------------------------#	

			#-------------------------member o edit registration count -----------------------------------------#				
			$query_M = $this->db->query("SELECT `regnumber`  FROM `member_registration` WHERE  kyc_edit =1 AND registrationtype  IN ('O') AND  `isactive` = '1' AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'");
			$edit_registration_count_M = $query_M->num_rows();

			$query_NM = $this->db->query("SELECT `regnumber`  FROM `member_registration` WHERE  kyc_edit  = 1 AND registrationtype  IN ('NM','DB') AND  `isactive` = '1' AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'");
			$edit_registration_count_NM = $query_NM->num_rows();

			#-------------------------member o edit registration count -----------------------------------------#					


			#--------------------------------------pending for new list-------------------------------------------#
			//member 
			$new_members = $new = array();
			$new_members = $this->db->query("SELECT  `regnumber`  FROM `member_registration` WHERE  registrationtype  IN ('O')  AND `isactive` = '1' AND `isdeleted` = 0 AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "' AND `kyc_status` = '0' AND `kyc_edit` = 0");
			$new_members1 =  $new_members->result_array();
			//echo $this->db->last_query(); exit;
			$count_new_member_status = '0';
			foreach ($new_members1 as $k => $v) {
				$new[$k] = $v['regnumber'];
				$count_new_member_status++;
			}
			$newarray = implode("','", $new);

			$query_new = $this->db->query("SELECT DISTINCT(regnumber) FROM `member_kyc` WHERE mem_type  IN ('O')  AND `regnumber` IN ('" . $newarray . "')");
			$pending_kyc = $query_new->num_rows();

			$pending_new_list_member = $count_new_member_status - $pending_kyc;

			//Non memeber 

			$new_nonmembers = $new = array();
			$new_nonmembers = $this->db->query("SELECT  `regnumber`  FROM `member_registration` WHERE  registrationtype  IN ('NM','DB')  AND `isactive` = '1' AND `isdeleted` = 0 AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $end_date . "' AND `kyc_status` = '0' AND `kyc_edit` = 0");
			$new_nonmembers1 =  $new_nonmembers->result_array();

			$count_new_nonmembers_status = '0';
			foreach ($new_nonmembers1 as $k => $v) {
				$new[$k] = $v['regnumber'];
				$count_new_nonmembers_status++;
			}
			$newarray = implode("','", $new);

			$query_new_nonmembers = $this->db->query("SELECT DISTINCT(regnumber) FROM `member_kyc` WHERE mem_type  IN ('NM','DB')  AND `regnumber` IN ('" . $newarray . "')");
			$pending_kyc = $query_new_nonmembers->num_rows();
			$pending_new_list_nonmembers = $count_new_nonmembers_status - $pending_kyc;


			#--------------------------------------end pending for new list-------------------------------------------#				   

			#-----------------------------------------pending for Edit list-------------------------------------------------#

			//member 
			$edit_members = $new = array();
			$type = array('O');
			$this->db->where_in('registrationtype', $type);
			$this->db->where('kyc_edit', '1');
			$this->db->where('kyc_status ', '0');
			$this->db->where('createdon >=', $from_date);
			$this->db->where('createdon <=', $end_date);
			$edit_members = $this->master_model->getRecords("member_registration", array('isactive' => '1'), 'regnumber');

			$count_edited_member_status = 0;
			foreach ($edit_members as $k => $v) {
				$edit[$k] = $v['regnumber'];
				$count_edited_member_status++;
			}
			$editarray = implode("','", $edit);
			$query1 = $this->db->query("SELECT DISTINCT(regnumber)  FROM `member_kyc` WHERE mem_type  IN ('O')  AND `regnumber` IN ('" . $editarray . "')  ");
			$present_member = $query1->num_rows();

			$not_prent_member = $count_edited_member_status - $present_member;

			$query2 = $this->db->query("SELECT regnumber,kyc_id
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																				  WHERE regnumber IN ('" . $editarray . "')
																				 GROUP BY regnumber
																	  )
																 AND kyc_status = '0' AND  kyc_state = 2  AND mem_type  IN ('O') ");
			$state2_member = $query2->num_rows();

			$pending_edit_member = $not_prent_member + $state2_member;


			/*non member pendinmg count*/


			//pending for edit list non member 
			$registrationtype = array('NM', 'DB');
			$edit_nonmembers = $e_nonnew = array();
			$this->db->where('kyc_edit', '1');
			$this->db->where('kyc_status ', '0');
			$this->db->where('createdon >=', $from_date);
			$this->db->where('createdon <=', $end_date);
			$this->db->where_in('registrationtype', $registrationtype);
			$edit_nonmembers = $this->master_model->getRecords("member_registration", array('isactive' => '1'), 'regnumber');

			$count_edited_nonmember_status = 0;
			foreach ($edit_nonmembers as $k => $v) {
				$e_nonnew[$k] = $v['regnumber'];
				$count_edited_nonmember_status++;
			}

			$non_editarray = implode("','", $e_nonnew);
			$non_query1 = $this->db->query("SELECT DISTINCT(regnumber)  FROM `member_kyc` WHERE mem_type  IN ('NM','DB')  AND  `regnumber` IN ('" . $non_editarray . "')");
			$present_nonmember = $non_query1->num_rows();
			$not_prent_nonmember = $count_edited_nonmember_status - $present_nonmember;

			$non_query2 = $this->db->query("SELECT regnumber,kyc_id
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																				  WHERE regnumber IN ('" . $non_editarray . "')
																				 GROUP BY regnumber
																	  )
																 AND kyc_status = '0' AND  kyc_state = 2  AND mem_type  IN ('NM','DB')  ");
			$state2_nonmember = $non_query2->num_rows();
			$pending_edit_nonmember = $not_prent_nonmember + $state2_nonmember;
			$non_member_pending = $pending_edit_nonmember;

			#-----------------------new list approve---------------------------------#
			/*	$approve_new = $this->db->query("SELECT  `regnumber`   FROM `member_registration` WHERE `isactive` = '1' AND `isdeleted` = 0 AND `kyc_status` = '1' AND `kyc_edit` = 0");
							$approve_new_member  = $approve_new->num_rows();*/
			$type = array('O');
			$this->db->where('isactive', '1');
			$this->db->where('kyc_status', '1');
			$this->db->where('kyc_edit', 0);
			$this->db->where('createdon >=', $from_date);
			$this->db->where('createdon <=', $end_date);
			$this->db->where_in('registrationtype', $type);
			$approve_new_member = $this->UserModel->getRecordCount("member_registration");


			$type = array('NM', 'DB');
			$this->db->where('isactive', '1');
			$this->db->where('kyc_status', '1');
			$this->db->where('kyc_edit', 0);
			$this->db->where('createdon >=', $from_date);
			$this->db->where('createdon <=', $end_date);
			$this->db->where_in('registrationtype', $type);
			$approve_new_nonmember = $this->UserModel->getRecordCount("member_registration");

			#-----------------------end new list approve---------------------------------#				

			#----------------------------edit list approve--------------------------------#
			/*$approve_edit= $this->db->query("SELECT  `regnumber`   FROM `member_registration` WHERE `isactive` = '1' AND `isdeleted` = 0 AND `kyc_status` = '1' AND `kyc_edit` = 1");
							$approve_edit_member  = $approve_edit->num_rows();
							*/
			$type = array('O');
			$this->db->where('isactive', '1');
			$this->db->where('kyc_status', '1');
			$this->db->where('kyc_edit', 1);
			$this->db->where_in('registrationtype', $type);
			$this->db->where('createdon >=', $from_date);
			$this->db->where('createdon <=', $end_date);
			$approve_edit_member = $this->UserModel->getRecordCount("member_registration");

			$type = array('NM', 'DB');
			$this->db->where('isactive', '1');
			$this->db->where('kyc_status', '1');
			$this->db->where('createdon >=', $from_date);
			$this->db->where('createdon <=', $end_date);
			$this->db->where_in('registrationtype', $type);
			$approve_edit_nonmember = $this->UserModel->getRecordCount("member_registration");
			#----------------------------edit list approve--------------------------------#						
			/*Dupilcate card*/
			/*$dup_card= $this->db->query("SELECT  `regnumber`   FROM `duplicate_icard` WHERE DATE(`added_date`) >= '".$kyc_start_date."' AND `pay_status` = '1'");
						$dup_card_count  = $dup_card->num_rows();	*/


			$this->db->where('pay_status', '1');
			$this->db->where('DATE(`added_date`)>=', $from_date);
			$this->db->where('DATE(`added_date`)<=', $end_date);
			//$this->db->where('DATE(`added_date`)>=', $kyc_start_date);
			$dup_card_count = $this->UserModel->getRecordCount("duplicate_icard");


			/*membership Id-card*/
			$dwn_mem_icard = $this->db->query("SELECT DISTINCT(member_number)  FROM `member_idcard_cnt` WHERE  DATE(`dwn_date`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'");
			$dwn_mem_icard_count = $dwn_mem_icard->num_rows();


			//pending for approver 
			#-------------------------new-----------------------------------#
			//member 

			$query2 = $this->db->query("SELECT regnumber
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																				
																				 GROUP BY regnumber
																	  )
																AND `field_count` =0 AND `kyc_status` = '0' AND `kyc_state` = 1 AND `approved_by` = 0 AND  mem_type  IN ('O') AND `record_source` = 'New' ");
			$ap_new_pending_count = $query2->num_rows();

			//non member	


			$query2 = $this->db->query("SELECT regnumber
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																					
																				 GROUP BY regnumber
																	  )
															AND `field_count` =0 AND `kyc_status` = '0' AND `kyc_state` = 1 AND `approved_by` = 0 AND  mem_type  IN ('NM','DB') AND `record_source` = 'New' ");
			$ap_new_pending_count_non = $query2->num_rows();

			#----------------------------end-------------------------------------#

			#-------------------------edit ----------------------------------------#
			//member 

			$query2 = $this->db->query("SELECT regnumber
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																				 GROUP BY regnumber
																	  )
															AND `field_count` =0 AND `kyc_status` = '0' AND `kyc_state` = 1 AND `approved_by` = 0 AND  mem_type  IN ('O') AND `record_source` = 'Edit' ");
			$ap_edit_pending_count = $query2->num_rows();
			//echo $ap_edit_pending_count;
			//echo '';
			//non member	


			$query2 = $this->db->query("SELECT regnumber
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																				 GROUP BY regnumber
																	  )
														AND `field_count` =0 AND `kyc_status` = '0' AND `kyc_state` = 1 AND `approved_by` = 0 AND  mem_type  IN ('NM','DB') AND `record_source` = 'Edit' ");
			$ap_edit_pending_count_non = $query2->num_rows();

			#------------------------end------------------------#
			#-------------------------Rejected added by chaitali 2022-03-25----------------------------------------#

			//member 						

			$query2 = $this->db->query("SELECT * FROM `member_kyc`
						WHERE `mem_type` LIKE 'O' AND `field_count` != 0 AND 
						`kyc_status` = '0' AND `user_type` LIKE '%approver%' AND DATE(`recommended_date`) BETWEEN '" . $from_date . "' AND '" . $end_date . "' AND approved_date LIKE '%0000-00-00%' ");
			$ap_rejected_count_o_mem = $query2->num_rows();

			//non member	

			$query2 = $this->db->query("SELECT * FROM `member_kyc`
						WHERE mem_type  IN ('NM','DB') AND `field_count` != 0 AND 
						`kyc_status` = '0' AND `user_type` LIKE '%approver%' AND DATE(`recommended_date`) BETWEEN '" . $from_date . "' AND '" . $end_date . "' AND approved_date LIKE '%0000-00-00%' ");
			$ap_rejected_count_non_mem = $query2->num_rows();

			#------------------------end------------------------#											
			$data = array(
				"new_registration_count_M" => $new_registration_count_M,
				"new_registration_count_NM" => $new_registration_count_NM,
				"edit_registration_count_M" => $edit_registration_count_M,
				'edit_registration_count_NM' => $edit_registration_count_NM,

				'non_member_pending' => $non_member_pending,

				"approve_new_member" => $approve_new_member,
				"approve_new_nonmember" => $approve_new_nonmember,

				"approve_edit_member" => $approve_edit_member,
				"approve_edit_nonmember" => $approve_edit_nonmember,


				"pending_new_list_member" => $pending_new_list_member,
				"pending_new_list_nonmembers" => $pending_new_list_nonmembers,

				"pending_edit_member" => $pending_edit_member,
				"pending_edit_nonmember" => $non_member_pending,

				//						"approve_non_member"=>$approve_non_member,
				'dup_card_count' => $dup_card_count,
				'dwn_mem_icard_count' => $dwn_mem_icard_count,
				'approver_new_pending' => $ap_new_pending_count,
				'approver_new_pending_non' => $ap_new_pending_count_non,


				'approver_edit_pending' => $ap_edit_pending_count,
				'approver_edit_pending_non' => $ap_edit_pending_count_non,
				'ap_rejected_count_o_mem' => $ap_rejected_count_o_mem,
				'ap_rejected_count_non_mem' => $ap_rejected_count_non_mem
			);
			//'approver_non_pending'=>$ap_non_pending_count);


		} else {



			$kyc_start_date = $this->config->item('kyc_start_date');
			$new_registration_count = $total_edit_count = $pending_new_list_member = $pending_edit_member = $non_member_pending = $approve_edit_member = $approve_new_member = 0;
			/*New registration count */

			/*$query = $this->db->query("SELECT `regnumber`  FROM `member_registration` WHERE `isactive` = '1' AND DATE(`createdon`) >= '".$kyc_start_date."'");
				 	 $new_registration_count= $query->num_rows();*/
			/*echo $this->db->last_query();
					exit;*/
			#-------------------------member o new registration count -----------------------------------------#
			$query_M = $this->db->query("SELECT `regnumber`  FROM `member_registration` WHERE  registrationtype   IN ('O') AND  `isactive` = '1' AND DATE(`createdon`) >= '" . $kyc_start_date . "'");
			$new_registration_count_M = $query_M->num_rows();

			$query_NM = $this->db->query("SELECT `regnumber`  FROM `member_registration` WHERE  registrationtype  IN ('NM','DB') AND  `isactive` = '1' AND DATE(`createdon`) >= '" . $kyc_start_date . "'");
			$new_registration_count_NM = $query_NM->num_rows();
			#-------------------------End member o new registration count -----------------------------------------#	

			#-------------------------member o edit registration count -----------------------------------------#				
			$query_M = $this->db->query("SELECT `regnumber`  FROM `member_registration` WHERE  kyc_edit =1 AND registrationtype  IN ('O') AND  `isactive` = '1'");
			$edit_registration_count_M = $query_M->num_rows();

			$query_NM = $this->db->query("SELECT `regnumber`  FROM `member_registration` WHERE  kyc_edit  = 1 AND registrationtype  IN ('NM','DB') AND  `isactive` = '1'");
			$edit_registration_count_NM = $query_NM->num_rows();

			#-------------------------member o edit registration count -----------------------------------------#					


			#--------------------------------------pending for new list-------------------------------------------#
			//member 
			$new_members = $new = array();
			$new_members = $this->db->query("SELECT  `regnumber`  FROM `member_registration` WHERE  registrationtype  IN ('O')  AND `isactive` = '1' AND `isdeleted` = 0 AND DATE(`createdon`) BETWEEN '" . $kyc_start_date . "' AND '" . date('Y-m-d') . "' AND `kyc_status` = '0' AND `kyc_edit` = 0");
			$new_members1 =  $new_members->result_array();
			//echo $this->db->last_query(); exit;
			$count_new_member_status = '0';
			foreach ($new_members1 as $k => $v) {
				$new[$k] = $v['regnumber'];
				$count_new_member_status++;
			}
			$newarray = implode("','", $new);

			$query_new = $this->db->query("SELECT DISTINCT(regnumber) FROM `member_kyc` WHERE mem_type  IN ('O')  AND `regnumber` IN ('" . $newarray . "')");
			//echo $this->db->last_query(); exit;
			$pending_kyc = $query_new->num_rows();
			$pending_new_list_member = $count_new_member_status - $pending_kyc;

			//Non memeber 

			$new_nonmembers = $new = array();
			$new_nonmembers = $this->db->query("SELECT  `regnumber`  FROM `member_registration` WHERE  registrationtype  IN ('NM','DB')  AND `isactive` = '1' AND `isdeleted` = 0 AND DATE(`createdon`) BETWEEN '" . $kyc_start_date . "' AND '" . date('Y-m-d') . "' AND `kyc_status` = '0' AND `kyc_edit` = 0");


			$new_nonmembers1 =  $new_nonmembers->result_array();

			$count_new_nonmembers_status = '0';
			foreach ($new_nonmembers1 as $k => $v) {
				$new[$k] = $v['regnumber'];
				$count_new_nonmembers_status++;
			}
			$newarray = implode("','", $new);

			$query_new_nonmembers = $this->db->query("SELECT DISTINCT(regnumber) FROM `member_kyc` WHERE mem_type  IN ('NM','DB')  AND `regnumber` IN ('" . $newarray . "')");

			$pending_kyc = $query_new_nonmembers->num_rows();
			$pending_new_list_nonmembers = $count_new_nonmembers_status - $pending_kyc;


			#--------------------------------------end pending for new list-------------------------------------------#				   

			#-----------------------------------------pending for Edit list-------------------------------------------------#

			//member 
			$edit_members = $new = array();
			$type = array('O');
			$this->db->where_in('registrationtype', $type);
			$this->db->where('kyc_edit', '1');
			$this->db->where('kyc_status ', '0');
			$edit_members = $this->master_model->getRecords("member_registration", array('isactive' => '1'), 'regnumber');

			$count_edited_member_status = 0;
			foreach ($edit_members as $k => $v) {
				$edit[$k] = $v['regnumber'];
				$count_edited_member_status++;
			}
			$editarray = implode("','", $edit);
			$query1 = $this->db->query("SELECT DISTINCT(regnumber)  FROM `member_kyc` WHERE mem_type  IN ('O')  AND `regnumber` IN ('" . $editarray . "')  ");
			$present_member = $query1->num_rows();

			$not_prent_member = $count_edited_member_status - $present_member;

			$query2 = $this->db->query("SELECT regnumber,kyc_id
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																				  WHERE regnumber IN ('" . $editarray . "')
																				 GROUP BY regnumber
																	  )
																 AND kyc_status = '0' AND  kyc_state = 2  AND mem_type  IN ('O') ");
			$state2_member = $query2->num_rows();

			$pending_edit_member = $not_prent_member + $state2_member;


			/*non member pendinmg count*/


			//pending for edit list non member 
			$registrationtype = array('NM', 'DB');
			$edit_nonmembers = $e_nonnew = array();
			$this->db->where('kyc_edit', '1');
			$this->db->where('kyc_status ', '0');
			$this->db->where_in('registrationtype', $registrationtype);
			$edit_nonmembers = $this->master_model->getRecords("member_registration", array('isactive' => '1'), 'regnumber');

			$count_edited_nonmember_status = 0;
			foreach ($edit_nonmembers as $k => $v) {
				$e_nonnew[$k] = $v['regnumber'];
				$count_edited_nonmember_status++;
			}

			$non_editarray = implode("','", $e_nonnew);
			$non_query1 = $this->db->query("SELECT DISTINCT(regnumber)  FROM `member_kyc` WHERE mem_type  IN ('NM','DB')  AND  `regnumber` IN ('" . $non_editarray . "')");
			$present_nonmember = $non_query1->num_rows();
			$not_prent_nonmember = $count_edited_nonmember_status - $present_nonmember;

			$non_query2 = $this->db->query("SELECT regnumber,kyc_id
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																				  WHERE regnumber IN ('" . $non_editarray . "')
																				 GROUP BY regnumber
																	  )
																 AND kyc_status = '0' AND  kyc_state = 2  AND mem_type  IN ('NM','DB')  ");
			$state2_nonmember = $non_query2->num_rows();
			$pending_edit_nonmember = $not_prent_nonmember + $state2_nonmember;
			$non_member_pending = $pending_edit_nonmember;

			#-----------------------new list approve---------------------------------#
			/*	$approve_new = $this->db->query("SELECT  `regnumber`   FROM `member_registration` WHERE `isactive` = '1' AND `isdeleted` = 0 AND `kyc_status` = '1' AND `kyc_edit` = 0");
							$approve_new_member  = $approve_new->num_rows();*/
			$type = array('O');
			$this->db->where('isactive', '1');
			$this->db->where('kyc_status', '1');
			$this->db->where('kyc_edit', 0);
			$this->db->where_in('registrationtype', $type);
			$approve_new_member = $this->UserModel->getRecordCount("member_registration");


			$type = array('NM', 'DB');
			$this->db->where('isactive', '1');
			$this->db->where('kyc_status', '1');
			$this->db->where('kyc_edit', 0);
			$this->db->where_in('registrationtype', $type);
			$approve_new_nonmember = $this->UserModel->getRecordCount("member_registration");

			#-----------------------end new list approve---------------------------------#				

			#----------------------------edit list approve--------------------------------#
			/*$approve_edit= $this->db->query("SELECT  `regnumber`   FROM `member_registration` WHERE `isactive` = '1' AND `isdeleted` = 0 AND `kyc_status` = '1' AND `kyc_edit` = 1");
							$approve_edit_member  = $approve_edit->num_rows();
							*/
			$type = array('O');
			$this->db->where('isactive', '1');
			$this->db->where('kyc_status', '1');
			$this->db->where('kyc_edit', 1);
			$this->db->where_in('registrationtype', $type);
			$approve_edit_member = $this->UserModel->getRecordCount("member_registration");

			$type = array('NM', 'DB');
			$this->db->where('isactive', '1');
			$this->db->where('kyc_status', '1');
			$this->db->where_in('registrationtype', $type);
			$approve_edit_nonmember = $this->UserModel->getRecordCount("member_registration");
			#----------------------------edit list approve--------------------------------#						
			/*Dupilcate card*/
			/*$dup_card= $this->db->query("SELECT  `regnumber`   FROM `duplicate_icard` WHERE DATE(`added_date`) >= '".$kyc_start_date."' AND `pay_status` = '1'");
						$dup_card_count  = $dup_card->num_rows();	*/


			$this->db->where('pay_status', '1');
			$this->db->where('DATE(`added_date`)>=', $kyc_start_date);
			$dup_card_count = $this->UserModel->getRecordCount("duplicate_icard");


			/*membership Id-card*/
			$dwn_mem_icard = $this->db->query("SELECT DISTINCT(member_number)  FROM `member_idcard_cnt` WHERE `dwn_date` BETWEEN '" . $kyc_start_date . "' AND '" . date('Y-m-d') . "'");
			$dwn_mem_icard_count = $dwn_mem_icard->num_rows();


			//pending for approver 
			#-------------------------new-----------------------------------#
			//member 

			$query2 = $this->db->query("SELECT regnumber
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																				
																				 GROUP BY regnumber
																	  )
																AND `field_count` =0 AND `kyc_status` = '0' AND `kyc_state` = 1 AND `approved_by` = 0 AND  mem_type  IN ('O') AND `record_source` = 'New' ");

			$ap_new_pending = $query2->result_array();
			//print_r($ap_new_pending_count);
			$final_array = array();
			foreach ($ap_new_pending as $res) {
				$regnumber = $res['regnumber'];
				$member_count = $this->db->query("SELECT regnumber From member_registration Where isactive = '1' AND regnumber IN('" . $regnumber . "')");
				//echo $this->db->last_query(); 
				if ($member_count->num_rows() > 0) {
					array_push($final_array, $regnumber);
				}
			}
			//print_r($final_array);
			$ap_new_pending_count = count($final_array);

			//non member	


			$query2 = $this->db->query("SELECT regnumber
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																					
																				 GROUP BY regnumber
																	  )
															AND `field_count` =0 AND `kyc_status` = '0' AND `kyc_state` = 1 AND `approved_by` = 0 AND  mem_type  IN ('NM','DB') AND `record_source` = 'New' ");
			//$ap_new_pending_count_non= $query2->num_rows();
			$ap_new_pending_non = $query2->result_array();
			//print_r($ap_new_pending_count);
			$final_array = array();
			foreach ($ap_new_pending_non as $res) {
				$regnumber = $res['regnumber'];
				$member_count = $this->db->query("SELECT regnumber From member_registration Where isactive = '1' AND regnumber IN('" . $regnumber . "')");
				//echo $this->db->last_query(); 
				if ($member_count->num_rows() > 0) {
					array_push($final_array, $regnumber);
				}
			}
			//print_r($final_array);
			$ap_new_pending_count_non = count($final_array);



			#----------------------------end-------------------------------------#

			#-------------------------edit ----------------------------------------#
			//member 

			$query2 = $this->db->query("SELECT regnumber
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																				 GROUP BY regnumber
																	  )
															AND `field_count` =0 AND `kyc_status` = '0' AND `kyc_state` = 1 AND `approved_by` = 0 AND  mem_type  IN ('O') AND `record_source` = 'Edit' ");
			//$ap_edit_pending_count = $query2->num_rows();
			$ap_edit_pending = $query2->result_array();
			//print_r($ap_new_pending_count);
			$final_array = array();
			foreach ($ap_edit_pending as $res) {
				$regnumber = $res['regnumber'];
				$member_count = $this->db->query("SELECT regnumber From member_registration Where isactive = '1' AND regnumber IN('" . $regnumber . "')");
				//echo $this->db->last_query(); 
				if ($member_count->num_rows() > 0) {
					array_push($final_array, $regnumber);
				}
			}
			//print_r($final_array);
			$ap_edit_pending_count = count($final_array);

			//echo $ap_edit_pending_count;
			//echo '';
			//non member	


			$query2 = $this->db->query("SELECT regnumber
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																				 GROUP BY regnumber
																	  )
														AND `field_count` =0 AND `kyc_status` = '0' AND `kyc_state` = 1 AND `approved_by` = 0 AND  mem_type  IN ('NM','DB') AND `record_source` = 'Edit' ");
			//$ap_edit_pending_count_non= $query2->num_rows();
			$ap_edit_pending_non = $query2->result_array();
			//print_r($ap_new_pending_count);
			$final_array = array();
			foreach ($ap_edit_pending_non as $res) {
				$regnumber = $res['regnumber'];
				$member_count = $this->db->query("SELECT regnumber From member_registration Where isactive = '1' AND regnumber IN('" . $regnumber . "')");
				//echo $this->db->last_query(); 
				if ($member_count->num_rows() > 0) {
					array_push($final_array, $regnumber);
				}
			}
			//print_r($final_array);
			$ap_edit_pending_count_non = count($final_array);
			#------------------------end------------------------#
			#-------------------------Rejected added by chaitali 2022-03-25----------------------------------------#

			//member 						

			$query2 = $this->db->query("SELECT * FROM `member_kyc`
						WHERE `mem_type` LIKE 'O' AND `field_count` != 0 AND 
						`kyc_status` = '0' AND `user_type` LIKE '%approver%' ");
			$ap_rejected_count_o_mem = $query2->num_rows();

			//non member	

			$query2 = $this->db->query("SELECT * FROM `member_kyc`
						WHERE mem_type  IN ('NM','DB') AND `field_count` != 0 AND 
						`kyc_status` = '0' AND `user_type` LIKE '%approver%' ");
			$ap_rejected_count_non_mem = $query2->num_rows();

			#------------------------end------------------------#												
			$data = array(
				"new_registration_count_M" => $new_registration_count_M,
				"new_registration_count_NM" => $new_registration_count_NM,
				"edit_registration_count_M" => $edit_registration_count_M,
				'edit_registration_count_NM' => $edit_registration_count_NM,

				'non_member_pending' => $non_member_pending,

				"approve_new_member" => $approve_new_member,
				"approve_new_nonmember" => $approve_new_nonmember,

				"approve_edit_member" => $approve_edit_member,
				"approve_edit_nonmember" => $approve_edit_nonmember,


				"pending_new_list_member" => $pending_new_list_member,
				"pending_new_list_nonmembers" => $pending_new_list_nonmembers,

				"pending_edit_member" => $pending_edit_member,
				"pending_edit_nonmember" => $non_member_pending,

				//						"approve_non_member"=>$approve_non_member,
				'dup_card_count' => $dup_card_count,
				'dwn_mem_icard_count' => $dwn_mem_icard_count,
				'approver_new_pending' => $ap_new_pending_count,
				'approver_new_pending_non' => $ap_new_pending_count_non,


				'approver_edit_pending' => $ap_edit_pending_count,
				'approver_edit_pending_non' => $ap_edit_pending_count_non,
				'ap_rejected_count_o_mem' => $ap_rejected_count_o_mem,
				'ap_rejected_count_non_mem' => $ap_rejected_count_non_mem
			);
			//'approver_non_pending'=>$ap_non_pending_count);


		}
		$this->load->view('admin/kyc_asondate_report', $data);
	}

	// FUNCTION NOT IN USE (RENAMED)
	public function recommender_new_download_CSV_old()
	{

		$from_date = $this->uri->segment(4);
		$to_date = $this->uri->segment(5);
		if ($from_date != '' && $to_date != '') {
			$kyc_start_date = $this->config->item('kyc_start_date');
			$csv = "Member No,Member type,Registration date \n"; //Column headers

			$new_members = $new = array();
			$new_members = $this->db->query("SELECT  `regnumber`  FROM `member_registration` WHERE  registrationtype  IN ('O','NM','DB')  AND `isactive` = '1' AND `isdeleted` = 0 AND DATE(`createdon`) BETWEEN '" . $from_date . "' AND '" . $to_date . "' AND `kyc_status` = '0' AND `kyc_edit` = 0");
			$new_members1 =  $new_members->result_array();

			$count_new_member_status = '0';
			foreach ($new_members1 as $k => $v) {
				$new[$k] = $v['regnumber'];
				$count_new_member_status++;
			}
			$newarray = implode("','", $new);

			$query_new = $this->db->query("SELECT DISTINCT(regnumber) FROM `member_kyc` WHERE mem_type  IN ('O','NM','DB')  AND `regnumber` IN ('" . $newarray . "')");
			$pending_kyc = $query_new->result_array();


			$new_members1 = array_column($new_members1, 'regnumber');
			$pending_kyc = array_column($pending_kyc, 'regnumber');

			$pending_new_list_member = array_diff($new_members1, $pending_kyc);

			$new_unique_arr = array_unique($pending_new_list_member);
			$type = array('NM', 'DB', 'O');
			$this->db->distinct('regnumber');
			$this->db->select('regnumber,registrationtype,createdon');
			$this->db->where('isactive', '1');
			$this->db->where('kyc_status', '0');
			$this->db->where('kyc_edit', '0');
			$this->db->where('createdon >=', $from_date);
			$this->db->where('createdon <=', $to_date);
			$this->db->where_in('registrationtype', $type);
			$this->db->where_in('regnumber', $new_unique_arr);
			$pending_kyc = $this->master_model->getRecords("member_registration");

			foreach ($pending_kyc  as $record) {
				// print_r($record);exit;
				$csv .= $record['regnumber'] . ',' . $record['registrationtype'] . ',' . $record['createdon'] . "\n";
			}

			$filename = "new_member_pending_for_recommender.csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename=' . $filename);
			$csv_handler = fopen('php://output', 'w');
			fwrite($csv_handler, $csv);
			fclose($csv_handler);
		} else {

			$kyc_start_date = $this->config->item('kyc_start_date');
			$csv = "Member No,Member type,Registration date \n"; //Column headers

			$new_members = $new = array();
			$new_members = $this->db->query("SELECT  `regnumber`  FROM `member_registration` WHERE  registrationtype  IN ('O','NM','DB')  AND `isactive` = '1' AND `isdeleted` = 0 AND DATE(`createdon`) BETWEEN '" . $kyc_start_date . "' AND '" . date('Y-m-d') . "' AND `kyc_status` = '0' AND `kyc_edit` = 0");
			$new_members1 =  $new_members->result_array();

			$count_new_member_status = '0';
			foreach ($new_members1 as $k => $v) {
				$new[$k] = $v['regnumber'];
				$count_new_member_status++;
			}
			$newarray = implode("','", $new);

			$query_new = $this->db->query("SELECT DISTINCT(regnumber) FROM `member_kyc` WHERE mem_type  IN ('O','NM','DB')  AND `regnumber` IN ('" . $newarray . "')");
			$pending_kyc = $query_new->result_array();


			$new_members1 = array_column($new_members1, 'regnumber');
			$pending_kyc = array_column($pending_kyc, 'regnumber');

			$pending_new_list_member = array_diff($new_members1, $pending_kyc);

			$new_unique_arr = array_unique($pending_new_list_member);
			$type = array('NM', 'DB', 'O');
			$this->db->distinct('regnumber');
			$this->db->select('regnumber,registrationtype,createdon');
			$this->db->where('isactive', '1');
			$this->db->where('kyc_status', '0');
			$this->db->where('kyc_edit', '0');

			$this->db->where_in('registrationtype', $type);
			$this->db->where_in('regnumber', $new_unique_arr);
			$pending_kyc = $this->master_model->getRecords("member_registration");

			foreach ($pending_kyc  as $record) {
				// print_r($record);exit;
				$csv .= $record['regnumber'] . ',' . $record['registrationtype'] . ',' . $record['createdon'] . "\n";
			}

			$filename = "new_member_pending_for_recommender.csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename=' . $filename);
			$csv_handler = fopen('php://output', 'w');
			fwrite($csv_handler, $csv);
			fclose($csv_handler);
		}
	}

	// FUNCTION NOT IN USE (RENAMED)
	public function recommender_edit_download_CSV_old()
	{
		$from_date = $this->uri->segment(4);
		$to_date = $this->uri->segment(5);
		if ($from_date != '' && $to_date != '') {
			$kyc_start_date = $this->config->item('kyc_start_date');
			$csv = "Member No,Member type, Data edited date, Image edited date  \n"; //Column headers
			//member 
			$edit_members = $new = array();
			$type = array('O', 'NM', 'DB');
			$this->db->distinct('regnumber');
			$this->db->where_in('registrationtype', $type);
			$this->db->where('kyc_edit', '1');
			$this->db->where('kyc_status ', '0');
			$this->db->where('createdon >=', $from_date);
			$this->db->where('createdon <=', $to_date);
			$edit_members = $this->master_model->getRecords("member_registration", array('isactive' => '1'), 'regnumber');

			$count_edited_member_status = 0;
			foreach ($edit_members as $k => $v) {
				$edit[$k] = $v['regnumber'];
				$count_edited_member_status++;
			}
			$editarray = implode("','", $edit);

			$query1 = $this->db->query("SELECT DISTINCT(regnumber)  FROM `member_kyc` WHERE mem_type  IN ('O','NM','DB')  AND `regnumber` IN ('" . $editarray . "')  ");
			$present_member = $query1->result_array();

			$edit_members = array_column($edit_members, 'regnumber');
			$present_member = array_column($present_member, 'regnumber');
			$edit_array_merge = array_diff($edit_members, $present_member);
			$edit_array_merge = array_values($edit_array_merge);

			//$edit_array_merge=array_merge($edit_members,$present_member);
			//$not_prent_nonmember=$count_edited_member_status-$present_member ;

			$edit_unique_arr = array_unique($edit_array_merge);

			$query2 = $this->db->query("SELECT regnumber
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																				  WHERE regnumber IN ('" . $editarray . "')
																				 GROUP BY regnumber
																	  )
																 AND kyc_status = '0' AND  kyc_state = 2  AND mem_type  IN ('O','NM','DB') ");
			$edit_unique_arr2 = $query2->result_array();
			$edit_unique_arr2 = array_column($edit_unique_arr2, 'regnumber');


			$pending_edit_member1 = array_merge($edit_unique_arr, $edit_unique_arr2);

			//$pending_edit_member=$not_prent_member+$state2_member;

			$pending_edit_member = array_unique($pending_edit_member1);

			$type = array('NM', 'DB', 'O');
			$this->db->distinct('regnumber');
			$this->db->select('regnumber,registrationtype,editedon,images_editedon');
			$this->db->where('isactive', '1');
			$this->db->where('kyc_status', '0');
			$this->db->where('kyc_edit', '1');
			$this->db->where_in('registrationtype', $type);
			$this->db->where_in('regnumber', $pending_edit_member);
			$this->db->where('createdon >=', $from_date);
			$this->db->where('createdon <=', $to_date);
			$pending_kyc_edit = $this->master_model->getRecords("member_registration");

			foreach ($pending_kyc_edit as $record) {


				// print_r($record);exit;
				$csv .= $record['regnumber'] . ',' . $record['registrationtype'] . ',' . $record['editedon'] . ',' . $record['images_editedon'] . "\n";
			}

			$filename = "edit_member_pending_for_recommender.csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename=' . $filename);
			$csv_handler = fopen('php://output', 'w');
			fwrite($csv_handler, $csv);
			fclose($csv_handler);
		} else {
			$kyc_start_date = $this->config->item('kyc_start_date');
			$csv = "Member No,Member type, Data edited date, Image edited date  \n"; //Column headers
			//member 
			$edit_members = $new = array();
			$type = array('O', 'NM', 'DB');
			$this->db->distinct('regnumber');
			$this->db->where_in('registrationtype', $type);
			$this->db->where('kyc_edit', '1');
			$this->db->where('kyc_status ', '0');
			$edit_members = $this->master_model->getRecords("member_registration", array('isactive' => '1'), 'regnumber');

			$count_edited_member_status = 0;
			foreach ($edit_members as $k => $v) {
				$edit[$k] = $v['regnumber'];
				$count_edited_member_status++;
			}
			$editarray = implode("','", $edit);

			$query1 = $this->db->query("SELECT DISTINCT(regnumber)  FROM `member_kyc` WHERE mem_type  IN ('O','NM','DB')  AND `regnumber` IN ('" . $editarray . "')  ");
			$present_member = $query1->result_array();

			$edit_members = array_column($edit_members, 'regnumber');
			$present_member = array_column($present_member, 'regnumber');
			$edit_array_merge = array_diff($edit_members, $present_member);
			$edit_array_merge = array_values($edit_array_merge);

			//$edit_array_merge=array_merge($edit_members,$present_member);
			//$not_prent_nonmember=$count_edited_member_status-$present_member ;

			$edit_unique_arr = array_unique($edit_array_merge);

			$query2 = $this->db->query("SELECT regnumber
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																				  WHERE regnumber IN ('" . $editarray . "')
																				 GROUP BY regnumber
																	  )
																 AND kyc_status = '0' AND  kyc_state = 2  AND mem_type  IN ('O','NM','DB') ");
			$edit_unique_arr2 = $query2->result_array();
			$edit_unique_arr2 = array_column($edit_unique_arr2, 'regnumber');


			$pending_edit_member1 = array_merge($edit_unique_arr, $edit_unique_arr2);

			//$pending_edit_member=$not_prent_member+$state2_member;

			$pending_edit_member = array_unique($pending_edit_member1);

			$type = array('NM', 'DB', 'O');
			$this->db->distinct('regnumber');
			$this->db->select('regnumber,registrationtype,editedon,images_editedon');
			$this->db->where('isactive', '1');
			$this->db->where('kyc_status', '0');
			$this->db->where('kyc_edit', '1');
			$this->db->where_in('registrationtype', $type);
			$this->db->where_in('regnumber', $pending_edit_member);
			$pending_kyc_edit = $this->master_model->getRecords("member_registration");

			foreach ($pending_kyc_edit as $record) {


				// print_r($record);exit;
				$csv .= $record['regnumber'] . ',' . $record['registrationtype'] . ',' . $record['editedon'] . ',' . $record['images_editedon'] . "\n";
			}

			$filename = "edit_member_pending_for_recommender.csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename=' . $filename);
			$csv_handler = fopen('php://output', 'w');
			fwrite($csv_handler, $csv);
			fclose($csv_handler);
		}
	}

	// FUNCTION NOT IN USE (RENAMED)
	public function approver_new_download_CSV_old()
	{
		//exit; 
		$from_date = $this->uri->segment(4);
		$to_date = $this->uri->segment(5);
		if ($from_date != '' && $to_date != '') {
			$kyc_start_date = $this->config->item('kyc_start_date');
			$csv = "Member No,Member type, Recommend date \n"; //Column headers
			//member 



			$query2 = $this->db->query("SELECT regnumber,mem_type,recommended_date
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																				
																				 GROUP BY regnumber
																	  )
															AND `field_count` =0 AND `kyc_status` = '0' AND `kyc_state` = 1 AND `approved_by` = 0 AND  mem_type  IN ('O','NM','DB')  AND `record_source` = 'New'");
			$ap_new_pending_count = $query2->result_array();




			foreach ($ap_new_pending_count  as $record) {


				// print_r($record);exit;
				$csv .= $record['regnumber'] . ',' . $record['mem_type'] . ',' . $record['recommended_date'] . "\n";
			}

			$filename = "new_member_pending_for_approver.csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename=' . $filename);
			$csv_handler = fopen('php://output', 'w');
			fwrite($csv_handler, $csv);
			fclose($csv_handler);
		} else {
			$kyc_start_date = $this->config->item('kyc_start_date');
			$csv = "Member No,Member type, Recommend date \n"; //Column headers
			//member 



			$query2 = $this->db->query("SELECT regnumber,mem_type,recommended_date
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																				
																				 GROUP BY regnumber
																	  )
															AND `field_count` =0 AND `kyc_status` = '0' AND `kyc_state` = 1 AND `approved_by` = 0 AND  mem_type  IN ('O','NM','DB')  AND `record_source` = 'New'");
			$ap_new_pending_count = $query2->result_array();




			foreach ($ap_new_pending_count  as $record) {


				// print_r($record);exit;
				$csv .= $record['regnumber'] . ',' . $record['mem_type'] . ',' . $record['recommended_date'] . "\n";
			}

			$filename = "new_member_pending_for_approver.csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename=' . $filename);
			$csv_handler = fopen('php://output', 'w');
			fwrite($csv_handler, $csv);
			fclose($csv_handler);
		}
	}

	// FUNCTION NOT IN USE (RENAMED)
	public function approver_edit_download_CSV_old()
	{
		//exit; 
		$from_date = $this->uri->segment(4);
		$to_date = $this->uri->segment(5);
		if ($from_date != '' && $to_date != '') {
			$kyc_start_date = $this->config->item('kyc_start_date');
			$csv = "Member No,Member type, Recommend date \n"; //Column headers
			//member 



			$query2 = $this->db->query("SELECT regnumber,mem_type,recommended_date
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																				
																				 GROUP BY regnumber
																	  )
																AND `field_count` =0 AND `kyc_status` = '0' AND `kyc_state` = 1 AND `approved_by` = 0 AND  mem_type  IN ('O','NM','DB')  AND `record_source` = 'Edit'");
			$ap_new_pending_count = $query2->result_array();




			foreach ($ap_new_pending_count  as $record) {


				// print_r($record);exit;
				$csv .= $record['regnumber'] . ',' . $record['mem_type'] . ',' . $record['recommended_date'] . "\n";
			}

			$filename = "edit_member_pending_for_approver.csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename=' . $filename);
			$csv_handler = fopen('php://output', 'w');
			fwrite($csv_handler, $csv);
			fclose($csv_handler);
		} else {
			$kyc_start_date = $this->config->item('kyc_start_date');
			$csv = "Member No,Member type, Recommend date \n"; //Column headers
			//member 



			$query2 = $this->db->query("SELECT regnumber,mem_type,recommended_date
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																				
																				 GROUP BY regnumber
																	  )
																AND `field_count` =0 AND `kyc_status` = '0' AND `kyc_state` = 1 AND `approved_by` = 0 AND  mem_type  IN ('O','NM','DB')  AND `record_source` = 'Edit'");
			$ap_new_pending_count = $query2->result_array();




			foreach ($ap_new_pending_count  as $record) {


				// print_r($record);exit;
				$csv .= $record['regnumber'] . ',' . $record['mem_type'] . ',' . $record['recommended_date'] . "\n";
			}

			$filename = "edit_member_pending_for_approver.csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename=' . $filename);
			$csv_handler = fopen('php://output', 'w');
			fwrite($csv_handler, $csv);
			fclose($csv_handler);
		}
	}

	// FUNCTION NOT IN USE (RENAMED)
	public function approver_rejected_download_CSV_old()
	{
		//exit; 

		$kyc_start_date = $this->config->item('kyc_start_date');
		$csv = "Member No,Member type, Recommend date \n"; //Column headers
		//member 



		$query2 = $this->db->query("SELECT * FROM `member_kyc` WHERE mem_type  IN ('O','NM','DB') 
		AND `field_count` != 0 AND `kyc_status` = '0' AND `user_type` LIKE '%approver%' ");
		$ap_new_pending_count = $query2->result_array();




		foreach ($ap_new_pending_count  as $record) {
			// print_r($record);exit;
			$csv .= $record['regnumber'] . ',' . $record['mem_type'] . ',' . $record['recommended_date'] . "\n";
		}

		$filename = "approver_rejected_member.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=' . $filename);
		$csv_handler = fopen('php://output', 'w');
		fwrite($csv_handler, $csv);
		fclose($csv_handler);
	}


	// developed for speed optimizations - but not in use
	public function asondate_with_sp()
	{
		$from_date = $end_date = '';

		if (isset($_POST['submit'])) {
			$from_date = $this->input->post('from_date'); //'2019-07-01';
			$end_date = $this->input->post('to_date'); //'2019-07-31';
		} else {
			$from_date = date("Y-m-d", strtotime("first day of previous month"));
			$end_date = date("Y-m-d", strtotime("last day of previous month"));
		}

		$sql = "CALL kyc_report_new_ordinary_members('" . $from_date . "', '" . $end_date . "');";
		$new_o_results = $this->multiple_result($sql);

		$sql = "CALL kyc_report_new_non_members('" . $from_date . "', '" . $end_date . "');";
		$new_nm_results = $this->multiple_result($sql);

		$sql = "CALL kyc_report_edit_ordinary_members('" . $from_date . "', '" . $end_date . "');";
		$edit_o_results = $this->multiple_result($sql);

		$sql = "CALL kyc_report_edit_non_members('" . $from_date . "', '" . $end_date . "');";
		$edit_nm_results = $this->multiple_result($sql);

		$new_o_rejected = ($new_o_results[4][0]->new_o_recommender_reject + $new_o_results[5][0]->new_o_approver_reject);
		$new_nm_rejected = ($new_nm_results[4][0]->new_nm_recommender_reject + $new_nm_results[5][0]->new_nm_approver_reject);
		$edit_o_rejected = ($edit_o_results[4][0]->edit_o_recommender_reject + $edit_o_results[5][0]->edit_o_approver_reject);
		$edit_nm_rejected = ($edit_nm_results[4][0]->edit_nm_recommender_reject + $edit_nm_results[5][0]->edit_nm_approver_reject);

		/*Dupilcate card*/
		$this->db->where('pay_status', '1');
		$this->db->where('DATE(`added_date`)>=', $from_date);
		$this->db->where('DATE(`added_date`)<=', $end_date);
		//$this->db->where('DATE(`added_date`)>=', $kyc_start_date);
		$dup_card_count = $this->UserModel->getRecordCount("duplicate_icard");

		/*membership Id-card*/
		$dwn_mem_icard = $this->db->query("SELECT DISTINCT(member_number)  FROM `member_idcard_cnt` WHERE  DATE(`dwn_date`) BETWEEN '" . $from_date . "' AND '" . $end_date . "'");
		$dwn_mem_icard_count = $dwn_mem_icard->num_rows();

		$data = array(
			"from_date" => $from_date,
			"end_date" => $end_date,


			"new_registration_count_M" => $new_o_results[0][0]->new_o_registrations,
			"new_registration_count_NM" => $new_nm_results[0][0]->new_nm_registrations,
			"edit_registration_count_M" => $edit_o_results[0][0]->edit_o_registrations,
			'edit_registration_count_NM' => $edit_nm_results[0][0]->edit_nm_registrations,


			"approve_new_member" => $new_o_results[1][0]->new_o_kyc_completed,
			"approve_new_nonmember" => $new_nm_results[1][0]->new_nm_kyc_completed,
			"approve_edit_member" => $edit_o_results[1][0]->edit_o_kyc_completed,
			"approve_edit_nonmember" => $edit_nm_results[1][0]->edit_nm_kyc_completed,


			"pending_new_list_member" => $new_o_results[2][0]->new_o_recommender_pending,
			"pending_new_list_nonmembers" => $new_nm_results[2][0]->new_nm_recommender_pending,
			"pending_edit_member" => $edit_o_results[2][0]->edit_o_recommender_pending,
			"pending_edit_nonmember" => $edit_nm_results[2][0]->edit_nm_recommender_pending,


			'approver_new_pending' => $new_o_results[3][0]->new_o_approver_pending,
			'approver_new_pending_non' => $new_nm_results[3][0]->new_nm_approver_pending,
			'approver_edit_pending' => $edit_o_results[3][0]->edit_o_approver_pending,
			'approver_edit_pending_non' => $edit_nm_results[3][0]->edit_nm_approver_pending,


			'dup_card_count' => $dup_card_count,
			'dwn_mem_icard_count' => $dwn_mem_icard_count,


			'ap_rejected_count_o_mem' => $new_o_rejected,
			'ap_rejected_count_non_mem' => $new_nm_rejected,
			'ap_rejected_count_edit_o_mem' => $edit_o_rejected,
			'ap_rejected_count_edit_non_mem' => $edit_nm_rejected
		);


		$this->load->view('admin/kyc_asondate_report', $data);
	}
}
