<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Active_exams_lists extends CI_Controller 
{
	public $UserID;
	public function __construct()
  	{
   		parent::__construct();
		if( $this->session->id == "" ){
			redirect('admin/Login');
		}		
		$this->load->model('UserModel');
		$this->load->model('Master_model');
		$this->UserID = $this->session->id;
		$this->load->helper('master_helper');
		$this->load->helper('upload_helper');
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
	}
	public function index()
	{
		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');
		$this->session->set_userdata('start','');
		
		$page_info = $this->Master_model->getRecords("page_master");
		$data['page_info'] = $page_info;
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
			<li>Pages</li>
		</ol>';
		$this->load->view('admin/active_exam_list',$data);
	}
	/* Added for validations */
	public function alpha_numeric_underscore($str)
	{
		return ( ! preg_match("/^[A-Za-z0-9_-]+$/", $str)) ? FALSE : TRUE;
	}
	
	
	public function getList(){
		$data['result'] = array();
		$data['action'] = array();
		$data['links'] = '';
		$data['success'] = '';
		$field = '';
		$value = '';
		$sortkey = '';
		$sortval = '';
		$per_page = '';
		$limit = 10;
		$start = 0;
		$session_arr = check_session();
		$searc_str = isset($_POST['value']) ? $_POST['value'] : '';
		$curr_date = date('Y-m-d');
		if($session_arr)
		{
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}
		// $this->db->where('page_delete',0);	
						if ($searc_str!='') {
							$this->db->like('em.description',$searc_str);
						}
						$this->db->order_by('bu.id','desc');
						$this->db->where(' bu.exam_from_date <= "'.$curr_date.'" AND bu.exam_to_date >= "'.$curr_date.'"');
						$this->db->join('exam_master em','exam_master em.exam_code=bu.exam_code',false);
		$total_row_bulk = $this->Master_model->getRecordCount("bulk_exam_activation_master bu",$field,$value);
						if ($searc_str!='') {
							$this->db->like('em.description',$searc_str);
						}
						$this->db->order_by('bu.id','desc');
						$this->db->where(' bu.exam_from_date <= "'.$curr_date.'" AND bu.exam_to_date >= "'.$curr_date.'"');
						$this->db->join('exam_master em','exam_master em.exam_code=bu.exam_code',false);
		$res_bulk = $this->Master_model->getRecords("bulk_exam_activation_master bu",'','bu.id,bu.exam_code,bu.exam_period,bu.institute_code,em.description,bu.exam_from_date,bu.exam_to_date', $value, $sortkey, $sortval, $per_page, $start);


		
						if ($searc_str!='') {
							$this->db->like('em.description',$searc_str);
						}
						$this->db->order_by('eam.id','desc');
						$this->db->where('(eam.exam_from_date <= "'.$curr_date.'" AND eam.exam_to_date >= "'.$curr_date.'" )');
						$this->db->join('exam_master em','em.exam_code=eam.exam_code',false);
		$total_row_exam = $this->Master_model->getRecordCount("exam_activation_master eam",$field,$value);
						
						if ($searc_str!='') {
							$this->db->like('em.description',$searc_str);
						}
						$this->db->order_by('eam.id','desc');
						$this->db->join('exam_master em','em.exam_code=eam.exam_code','LEFT',false);
						$this->db->where('eam.exam_from_date <= "'.$curr_date.'" AND eam.exam_to_date >= "'.$curr_date.'" ');
		$res_exam = $this->Master_model->getRecords("exam_activation_master eam",'','eam.id,eam.exam_code,eam.exam_period,em.description,eam.exam_from_date,eam.exam_to_date', $value, $sortkey, $sortval, $per_page, $start);
		// echo $this->db->last_query();
		// die;
		// echo "<pre>"; print_r($res_exam);die;

		$total_row = $total_row_bulk+$total_row_exam;

		$res = array_merge($res_bulk,$res_exam);
		$res = array_slice($res,$start,$per_page);
		$url = base_url()."admin/Active_exams_lists/getList/";
		$config = pagination_init($url,$total_row, $per_page, 2);
		$this->pagination->initialize($config);
	


		if($res)
		{
			$result = (array) $res;

			$data['result'] = $result;

			foreach($result as $row)
			{
		
				if ( $row['exam_from_date'] <= $curr_date && $row['exam_to_date'] >= $curr_date) {
					$data['action'][]='<p class="text-success"><strong>Active</strong></p>';;
				}else{
					$data['action'][]='<p class="text-danger">Inactive</p>';
				}
		
			}
			// echo $start."$$".$per_page;die;

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
		}
		
		$json_res = json_encode($data);
		echo $json_res;
	}
	


}