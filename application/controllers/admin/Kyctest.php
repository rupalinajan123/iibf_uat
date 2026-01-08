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

class Kyctest extends CI_Controller
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

	public function kyccomplete_newlist()
	{
		$allocates_arr = array();
		$data['result'] = array();
		$regstr = $searchText = $searchBy = '';
		$today = date('Y-m-d H:i:s');

		//WHERE kyc_id IN (SELECT MAX(kyc_id) FROM member_kyc GROUP BY member_kyc.regnumber)

		/*	$this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber', 'LEFT');
		$this->db->where('member_registration.kyc_status','1');
	   $this->db->where('member_kyc.kyc_state',3);
		 $this->db->where('member_registration.isactive','1');
		$this->db->group_by('member_kyc.regnumber');
		$members = $this->master_model->getRecords("member_kyc",array('field_count'=>'0','approved_by '=>$this->session->userdata('kyc_id')),'kyc_id,member_kyc.regnumber,kyc_id,namesub,firstname,middlename,lastname,dateofbirth,registrationtype,email,recommended_by,recommended_date,approved_date');*/
		//echo $this->db->last_query();exit;
		$this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber', 'LEFT');

		$this->db->where('member_kyc.kyc_state', 3);
		$this->db->where('member_registration.isactive', '1');
		$members = $this->master_model->getRecords("member_kyc", array('field_count' => '0', 'approved_by ' => 95), 'kyc_id,member_kyc.regnumber,kyc_id,namesub,firstname,middlename,lastname,dateofbirth,registrationtype,email,recommended_by,recommended_date,approved_date');
		echo $this->db->last_query();exit;
		if (count($members)) {
			$recminfo = $this->master_model->getRecords("administrators", array('id' => $members[0]['recommended_by']), 'username');

			$data['result'] = $members;
			$data['recommended_name'] = $recminfo[0]['username'];
			$data['reg_no'] = $members[0]['regnumber'];
			$id = $data['reg_no'];
		}
		//insert the allocated array list in table
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li><li>Search</li></ol>';

		$this->db->distinct('registrationtype');
		$data['mem_type'] = $this->master_model->getRecords('member_registration', array('isactive' => '1'), 'registrationtype', array('registrationtype' => 'ASC'));
		$this->load->view('admin/kyc/Approver/kyccomplete_newlist', $data);
		//redirect(base_url().'admin/kyc/Approver/member');
	}

	public function index()
	{

		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Member List</li>
							   </ol>';
		$this->load->view('admin/kyc_reg_list', $data);
	}

	public function recommender_new_download_CSV()
	{
		$from_date = $this->uri->segment(4);
		$end_date = $this->uri->segment(5);
		if ($from_date == '' && $end_date == '') {
			// $from_date = date("Y-m-d", strtotime("first day of previous month"));
			// $end_date = date("Y-m-d", strtotime("last day of previous month"));
			$from_date = '2021-12-01';
			$end_date = '2022-05-03';
		}
		$csv = "Member No,Member type,Registration date \n";

		// ordinary member functionality
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

		$approver_new_pending = $this->db->query("SELECT regnumber FROM `member_kyc` 
		WHERE kyc_id IN (
			SELECT MAX(kyc_id)
			FROM member_kyc
			where regnumber IN (" . $regnumbers . ")
			GROUP BY regnumber
			)
		AND `field_count` = 0 
		AND `kyc_status` = '0' 
		AND `kyc_state` = 0 
		AND `user_type` LIKE '%recommender%'
		AND `approved_by` = 0")->result_array();

		$non_kyc = array_column($status_zero, 'regnumber');
		$approver_pending = array_column($approver_new_pending, 'regnumber');
		$recommender_pending_array = array_diff($non_kyc, $approver_pending);
		$recommender_pending_regnumbers = "'" . implode("','", $recommender_pending_array) . "'";

		$pending_for_recommender_o_new = $this->db->query(
			"SELECT `regnumber`, `registrationtype`, `createdon`, kyc_status, kyc_edit
			FROM `member_registration` 
			WHERE regnumber != '' 
			AND `benchmark_disability` != 'Y'
			AND `isactive` = '1' 
			AND `isdeleted` = '0'
			AND regnumber IN (" . $recommender_pending_regnumbers . ")"
		)->result_array();

		$completed = $this->db->query("SELECT `original_allotted_member_id` FROM `admin_kyc_users` WHERE kyc_user_id = 2620")->result_array();

		_pa($pending_for_recommender_o_new);
		_pa($completed);
		die;

	}
}
