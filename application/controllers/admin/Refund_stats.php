<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Refund_stats extends CI_Controller
{
    public function __construct()
	{
	    parent::__construct();
		$this->load->model('Master_model');
		$this->load->model('UserModel');
	}
	public function index()
	{
		
	}
	public function dashboard()
	{
	    $msg='';
	    $total_count='';
		$result =array();
	    $this->db->where('exam_master.exam_delete','0');
		$exams=$this->master_model->getRecords('exam_master','','',array('description'=>'ASC'));
		
	    if(isset($_POST['btnSubmit']))
		{
		   
		   $exam_code = $this->input->post('exam_code');
		   $query = $this->db->query('SELECT DISTINCT(mem_mem_no) as member_no,mam_nam_1 as mem_name,venue_name, 	venueadd1,venueadd2,venueadd3,venueadd4,venueadd5,exam_date,time,email
                        FROM `admit_card_details` 
                        LEFT JOIN member_registration ON member_registration.regnumber = admit_card_details.mem_mem_no
                        WHERE admit_card_details.remark = 3 AND admit_card_details.exm_cd = '.$exam_code.' GROUP BY mem_mem_no');
							
		    $result = $query->result_array();
			$total_count = count($result);
			//echo $this->db->last_query();
			if(empty($result))
			{
			    $msg='Record not found......!!!!';	    
			}
			
		}
		
		$data = array('exams'=>$exams,'msg'=>$msg,'result'=>$result,'total_count'=>$total_count);
	    $this->load->view('admin/refund_stats_list',$data);
	}
}
?>