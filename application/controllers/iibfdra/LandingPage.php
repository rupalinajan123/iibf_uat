<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class LandingPage extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('master_helper');
		$this->load->model('master_model');		
	}
	public function index() {
		$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
		//added join to get exam date - 01-02-2017
		$this->db->join('dra_subject_master sub','a.exam_code = sub.exam_code', 'right');
		
		$res = $this->master_model->getRecords("dra_exam_master a");
		$data = array( 'exams' => $res );
		$this->load->view('iibfdra/landingpage',$data);	
	}
}
?>