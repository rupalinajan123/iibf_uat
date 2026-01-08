<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CountController extends CI_Controller {
	public $UserID;
			
	public function __construct(){
		parent::__construct();
		if($this->session->id==""){
			redirect('admin/Login');
		}		
		$this->load->model('UserModel');
		$this->UserID=$this->session->id;
		
	}
	public function index()
	{ 
		//$from_date = '';
		$to_date = '';
		//print_r($_POST);
		/*if(isset($_POST['from_date']) && $_POST['from_date']!=''){
			$from_date = $_POST['from_date'];
		}*/
		if(isset($_POST['to_date']) && $_POST['to_date']!=''){
			$to_date = $_POST['to_date'];
		}
		if($to_date != "")
		{
			$data = array();
			if($this->session->userdata('roleid') == 3)
			{
				$this->Page('dashboard/query_dashboard',$data);
			}
			else
			{
				/* Today's Member Registration Count*/
				$data['total_count_NM'] = $this->total_count($action = "NM", $to_date);
				$data['total_count_O'] = $this->total_count($action = "O", $to_date);
				$data['total_count_DB'] = $this->total_count($action = "DB", $to_date);
				//$data['total_count_A'] = $this->total_count($action = "A", $to_date);
				//$data['total_count_F'] = $this->total_count($action = "F", $to_date);
				$data['flag'] = "true";
				if($this->session->userdata('roleid') == 1)	//Super Admin
				{
					$this->load->view('admin/dashboard/statistics',$data);
				}
				else //Report Admin
				{
					$this->load->view('admin/dashboard/report_dashboard',$data);
				}
			}
		}
		else
		{
			$data['flag'] = "false";
			$this->load->view('admin/dashboard/statistics',$data);
		}
	}
	
	public function getUserInfo(){
		$data['AdminUser']=$this->UserModel->getUserInfo($this->UserID);
		return $data;
	}	
	
	public function total_count($action = 'NM', $to_date)
	{
		/* Member Type :  NM | O | DB | A | F */
		$todays_count = 0;
		$where = "(DATE(createdon) = '".$to_date."')";
		$where .= " AND isactive = '1' AND isdeleted = 0";
		
		if($action == 'NM'){
			$where .= ' AND registrationtype = "NM"';	
		}
		elseif($action == 'O'){
			$where .= ' AND registrationtype = "O"';	
		}
		elseif($action == 'DB'){
			$where .= ' AND registrationtype = "DB"';	
		}
		/*elseif($action == 'A'){
			$where .= ' AND registrationtype = "A"';	
		}
		elseif($action == 'F'){
			$where .= ' AND registrationtype = "F"';	
		}*/
		$select = 'COUNT(regid) AS todays_count'; 
		$this->db->where($where);
		$res = $this->UserModel->getRecords("member_registration", $select, '', '', '', '', '', '');
		
		//echo "<br> SQL => ".$this->db->last_query(); echo "<br>";
		
		$result = $res->result_array();
		$todays_count = $result[0]['todays_count'];
		return $todays_count;
	}
}