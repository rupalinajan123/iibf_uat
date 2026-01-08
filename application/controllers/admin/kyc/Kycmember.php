<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report extends CI_Controller {
	public $UserID;
			
	public function __construct(){
		parent::__construct();
		if($this->session->id==""){
			redirect('admin/Login'); 
		}		
		
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->UserID=$this->session->id;
		
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->helper('upload_helper');
		$this->load->helper('general_helper');
		$this->load->library('email');
		$this->load->model('Emailsending');
	}
	
	
	public function index()
	{
		$this->load->view('admin/kyc/kyc_reg_list');
	}
	
	
}