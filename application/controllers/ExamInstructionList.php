<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExamInstructionList extends CI_Controller {
	public function __construct()

	{
		parent::__construct();
		$this->load->helper('master_helper');
		$this->load->model('master_model');		
	}
	public function index()
	{
		$memtype = '';
		$arr = array();
		if(isset( $_GET['type'] )) {
			$memtype = trim($_GET['type']);
			$memtype = base64_decode( $memtype );	
		} 
		if( !empty( $memtype ) ) {
			$exam_types = $this->master_model->getRecords('exam_type');
			$conditionarr = array();
			if( count( $exam_types ) > 0 ) {
				foreach( $exam_types as $exam_type ) {
					$type = $exam_type['type'];
					$conditionarr['exam_type'] = $exam_type['id'];
					if( $memtype == "O" ) {
						$conditionarr['elg_mem_o'] = 'Y';	
					} else if( $memtype == "NM" ) {
						$conditionarr['elg_mem_nm'] = 'Y';	
					}
					$exams = $this->master_model->getRecords('exam_master',$conditionarr);
					if( count( $exams ) > 0 ) {
						$arr[$type] = $exams;
					}
				}
			}
			//print_r( $this->db->last_query() ); die("test");
			$data = array('middle_content' => 'exam_list','exams' => $arr, 'memtype' => $memtype);
			$this->load->view('common_view',$data);
		} else {
			redirect('ExamInstructionList/?type=Tw==');
		}
	}
}

