<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class InstructionsExamination extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('master_helper');
		$this->load->model('master_model');		
	}
	public function index() {
		redirect('InstructionsExamination/listing/?type=Tw==');
	}
	public function listing() {
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
			$data = array('middle_content' => 'exam_rules_list','exams' => $arr, 'memtype' => $memtype);
			$this->load->view('common_view_fullwidth',$data);
		} else {
			redirect('InstructionsExamination/listing/?type=Tw==');
		}
	}
}
?>