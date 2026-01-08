<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {
	public $UserID;
	public $UserData;
	
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('dra_admin')) {
			redirect('iibfdra/Version_2/admin/Login');
		}
		$this->UserData = $this->session->userdata('dra_admin');
		$this->UserID = $this->UserData['id'];
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->load->helper('master_helper');
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
	}
	
	public function index()
	{
		//$data["exam_period_list"] = array_unique($this->Master_model->getRecords("dra_misc_master","","exam_period")); // remove duplicates from this array
		
		$data["exam_period_list"] = $this->db->query("SELECT DISTINCT(exam_period) FROM dra_misc_master WHERE misc_delete = '0'")->result_array();
		
		$data["institute_list"] = $this->Master_model->getRecords("dra_accerdited_master","accerdited_delete = '0'","institute_code,institute_name");
		
		$this->load->view('iibfdra/Version_2/admin/dashboard/dashboard',$data);		
	}
	
	public function getSearchResult()
	{
		$data['result'] = array();
		$data['success'] = '';
		$field = '';
		$value = '';
		$per_page = '';
		$limit = 10;
		$start = 0;
		
		$session_arr = check_session();
		if($session_arr)
		{
			$value = $session_arr['value'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}
		
		$res = '';
		
		$total_row = 0;
		
		// DRA is hardcoded type in search result -
		$select = '"DRA", dra_exam_master.exam_code, dra_exam_master.description, COUNT(dra_member_exam.id) AS member_count';
		$where = '';
		if($value != "")
		{
			$post_data = explode('~',$value);
			$exam_period = isset($post_data[0]) ? $post_data[0] : '';
			$inst_code = isset($post_data[1]) ? $post_data[1] : '';
			
			if($exam_period != "" && $inst_code == "")
			{
				//$where .= 'dra_misc_master.exam_period = "'.$exam_period.'"';
				$where .= 'dra_member_exam.exam_period = "'.$exam_period.'" AND dra_member_exam.pay_status = "1" AND dra_member_exam.dra_memberexam_delete = "0" GROUP BY dra_member_exam.exam_code';
				
				//$this->db->join('dra_exam_master','dra_exam_master.exam_code = dra_misc_master.exam_code','LEFT');
				$this->db->join('dra_exam_master','dra_member_exam.exam_code = dra_exam_master.exam_code','LEFT');
				
				$this->db->where($where);
				$res = $this->UserModel->getRecords("dra_member_exam", $select, '', '', '', '', $per_page, $start);
				
				//$data['query'] = $this->db->last_query();	
				
				// get total record count for pagination
				
				$this->db->select('count(dra_member_exam.id) as tot');
				
				//$this->db->join('dra_exam_master','dra_exam_master.exam_code = dra_misc_master.exam_code','LEFT');
				$this->db->join('dra_exam_master','dra_member_exam.exam_code = dra_exam_master.exam_code','LEFT');
				
				$this->db->where($where);
				$resArr = $this->db->get("dra_member_exam")->result_array();
				if($resArr)
				{
					$total_row = $resArr[0]["tot"];
				}
				//$data['query1'] = $this->db->last_query();
			}
			else if($exam_period != "" && $inst_code != "")
			{
				//$where .= 'dra_misc_master.exam_period = "'.$exam_period.'" AND dra_members.inst_code = "'.$inst_code.'"';
				$where .= 'dra_member_exam.exam_period = "'.$exam_period.'" AND dra_members.inst_code = "'.$inst_code.'" AND dra_member_exam.pay_status = "1" AND dra_members.isdeleted = "0" AND dra_member_exam.dra_memberexam_delete = "0" GROUP BY dra_member_exam.exam_code';
				
				//$this->db->join('dra_exam_master','dra_exam_master.exam_code = dra_misc_master.exam_code','LEFT');
				$this->db->join('dra_exam_master','dra_member_exam.exam_code = dra_exam_master.exam_code','LEFT');
				$this->db->join('dra_members','dra_members.regid = dra_member_exam.regid','LEFT');
				
				$this->db->where($where);
				$res = $this->UserModel->getRecords("dra_member_exam", $select, '', '', '', '', $per_page, $start);
				
				//$data['query'] = $this->db->last_query();
				
				// get total record count for pagination
				
				$this->db->select('count(dra_member_exam.id) as tot');
				
				//$this->db->join('dra_exam_master','dra_exam_master.exam_code = dra_misc_master.exam_code','LEFT');
				$this->db->join('dra_exam_master','dra_member_exam.exam_code = dra_exam_master.exam_code','LEFT');
				$this->db->join('dra_members','dra_members.regid = dra_member_exam.regid','LEFT');
				
				$this->db->where($where);
				$resArr = $this->db->get("dra_member_exam")->result_array();
				if($resArr)
				{
					$total_row = $resArr[0]["tot"];
				}
				//$data['query1'] = $this->db->last_query();
			}
		}
		
		if($res)
		{
			$total_mem_count = 0;
			
			$rows = array();
			
			foreach($res->result_array() as $row)
			{    
				$rows[] = $row; // add the fetched result to the result array;
				
				$total_mem_count += $row['member_count'];
			}
			
			$data['total_mem_count'] = $total_mem_count;
			
			// check if query result is not empty values -
			//if($rows[0]['exam_code'] != "")
			//{
				//$total_row = $res->num_rows();
			
				$url = base_url()."iibfdra/Version_2/admin/dashboard/getSearchResult/";
			
				$config = pagination_init($url,$total_row, $per_page, 2);
				$this->pagination->initialize($config);
				
				$result = $res->result_array();
				
				$data['result'] = $result;
				
				if(count($result))
					$data['success'] = 'Success';
				else
					$data['success'] = '';
				
				$str_links = $this->pagination->create_links();
				$data["links"] = $str_links;
				if(($start+$per_page)>$total_row)
					$end_of_total = $total_row;
				else
					$end_of_total = $start+$per_page;
				
				$data['info'] = 'Showing '.($start+1).' to '.$end_of_total.' of '.$total_row.' entries';
				$data['index'] = $start+1;	
			//}
		}
		
		$json_res = json_encode($data);
		echo $json_res;
	}
		
}