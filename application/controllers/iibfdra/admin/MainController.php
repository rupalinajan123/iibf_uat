<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MainController extends CI_Controller {
	public $UserID;
	public $UserData;
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('dra_admin')) {
			redirect('iibfdra/admin/Login');
		}	
		$this->UserData = $this->session->userdata('dra_admin');
		$this->load->model('UserModel');
		$this->UserID = $this->UserData['id'];
	}
	public function index()
	{
		$data = $this->getUserInfo();
		
		// get total members registered -
		$data['total_reg_dra_exam'] = $this->total_reg_dra_exam();
		$data['total_reg_dra_telecaller_exam'] = $this->total_reg_dra_telecaller_exam();
		$data['total_reg_reattempt'] = $this->total_reg_reattempt();
		
		// get total members registered today -
		$current_date = date("Y-m-d");
		$data['total_reg_today_dra_exam'] = $this->total_reg_dra_exam($current_date);
		$data['total_reg_today_dra_telecaller_exam'] = $this->total_reg_dra_telecaller_exam($current_date);
		$data['total_reg_today_reattempt'] = $this->total_reg_reattempt($current_date);
				
		$this->load->view('iibfdra/admin/home/home', $data);	
	}
	public function getUserInfo(){
		$data['DraUser'] = $this->UserModel->getDraUserInfo( $this->UserID );
		return $data;
	}
	
	// function to get all member registered for DRA Exam with paid status -
	public function total_reg_dra_exam($current_date = "")
	{
		$total_count = 0;
		
		$where = "exam_code = 45 AND pay_status = '1' AND dra_memberexam_delete = '0'";	// success
		if($current_date != "")
		{
			$where .= " AND DATE(created_on) = '".$current_date."'";	
		}
		
		$select = 'COUNT(*) AS total'; 
		$this->db->where($where);
		$res = $this->UserModel->getRecords("dra_member_exam ", $select, '', '', '', '', '', '');
		$result = $res->result_array();
		
		$total_count = $result[0]['total'];
		
		return $total_count;
	}
	
	// function to get all member registered for DRA Telecaller Exam with paid status -
	public function total_reg_dra_telecaller_exam($current_date = "")
	{
		$total_count = 0;
		
		$where = "exam_code = 57 AND pay_status = '1' AND dra_memberexam_delete = '0'";	// success
		if($current_date != "")
		{
			$where .= " AND DATE(created_on) = '".$current_date."'";
		}
		
		$select = 'COUNT(*) AS total'; 
		$this->db->where($where);
		$res = $this->UserModel->getRecords("dra_member_exam ", $select, '', '', '', '', '', '');
		$result = $res->result_array();
		
		$total_count = $result[0]['total'];
		
		return $total_count;
	}
	
	// function to get all member Re-Attempted for DRA & DRA Telecaller Exam with paid status -
	public function total_reg_reattempt($current_date = "")
	{
		$total_count = 0;
		
		$where = "pay_status = '1' AND dra_memberexam_delete = '0'";	// success
		if($current_date != "")
		{
			$where .= " AND DATE(created_on) = '".$current_date."'";
		}
		
		$sql = "SELECT regid, COUNT(*) - 1 AS total FROM dra_member_exam WHERE " . $where . " GROUP BY regid, exam_code Having COUNT(*) > 1"; 
		$res = $this->db->query($sql);
		$result = $res->result_array();
		
		foreach($result as $row)
		{
			$total_count += $row['total'];	
		}
		
		return $total_count;
	}	
}