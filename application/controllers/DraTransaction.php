<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class DraTransaction extends CI_Controller {
	public $InstData;
	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata('dra_institute')) {
			redirect('iibfdra/InstituteLogin');
		}	
		$this->InstData = $this->session->userdata('dra_institute');
		$this->load->helper('master_helper');
		$this->load->model('master_model');	
		$this->load->model('UserModel');
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');	
	}
	public function index() {
		$this->dashboard();
	}
	public function dashboard()
	{
		$data=array('middle_content'=>'dashboard');
		$this->load->view('iibfdra/common_view',$data);
	}
	
	##------------------------Transaction Details done by (PRAFULL)----------------##
	public function transaction()
	{
		
	}
}
?>