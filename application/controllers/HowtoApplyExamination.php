<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class HowtoApplyExamination extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('master_helper');
		$this->load->model('master_model');		
	}
	public function index() {
		$memtype = '';
		if(isset( $_GET['type'] )) {
			$memtype = trim($_GET['type']);
			$memtype = base64_decode( $memtype );	
		} else {
			redirect('HowtoApplyExamination/?type=Tw==');
		}
		if( !empty( $memtype ) ) {
			$data = array('middle_content' => 'howtoapplyinst', 'memtype' => $memtype);
			$this->load->view('common_view_fullwidth',$data); 
		}
	}
}
?>