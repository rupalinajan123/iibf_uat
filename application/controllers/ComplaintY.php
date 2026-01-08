<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ComplaintY extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	public function __construct()
	{
		 parent::__construct(); 
		 //load mPDF library
		 //$this->load->library('m_pdf');
		 $this->load->model('Master_model');
	} 
	
	
	
	public function index(){
		
		
		$fday = $this->input->post('fday');
		
		if($fday == ''){
		
			$date = date("Y-m-d");
			$newdate = strtotime('-1 day', strtotime($date));
			$newdate = date('Y-m-d', $newdate);
			$this->db->like('complain_date',$today);
			$result=$this->master_model->getRecords('cms_master','','regnumber,	member_type,complain_details,email,mobile,exam_code,subcatcode,category_code,complain_date,attachment',array('compid'=>'DESC'));
			
		}else{
			$today = $fday;
			$this->db->like('complain_date',$today);
			$result=$this->master_model->getRecords('cms_master','','regnumber,	member_type,complain_details,email,mobile,exam_code,subcatcode,category_code,complain_date,attachment',array('compid'=>'DESC'));
		}
			
		
		
	    $data = array("record"=>$result);
		
		$this->load->view('complaint',$data);
		
	}
	
}
