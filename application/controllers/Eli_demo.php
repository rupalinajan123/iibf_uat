<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Eli_demo extends CI_Controller {
	public function __construct()
	{
		 parent::__construct(); 
		 
		 $this->load->model('Master_model');
		 $this->load->library('email');
		 $this->load->model('Emailsending');
	} 
	public function eli()
	{ 
		$this->db->limit(6000);
		$this->db->where('status',0);
		$Get_sql = $this->master_model->getRecords('demo_eligible_master');
		
		foreach($Get_sql as $record)
		{
			$member_no = $record['member_no'];
			$subject_code = $record['subject_code'];
			$exam_code = $this->config->item('examCodeJaiib');
			$eligible_period = 219;
			
			$this->db->where('member_no',$member_no);
			$this->db->where('subject_code',$subject_code);
			$this->db->where('exam_code',$exam_code);
			$this->db->where('eligible_period',$eligible_period);
			$this->db->delete('eligible_master');

			$update_data=array('status'=>1);
			$this->master_model->updateRecord('demo_eligible_master',$update_data,array('member_no'=>$member_no,'subject_code'=>$subject_code));	
		}
	}
}