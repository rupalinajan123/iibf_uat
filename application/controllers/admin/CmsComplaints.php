<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CmsComplaints extends CI_Controller {
	public $UserID;
	public function __construct(){
		parent::__construct();
		if($this->session->id==""){
			redirect('admin/Login');
		}
		if($this->session->userdata('roleid')!=1)
		{
			redirect(base_url().'admin/MainController');
		}		
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->UserID=$this->session->id;
		
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
	}
	
	public function index()
	{
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li><a href="'.base_url().'admin/'.$this->router->fetch_class().'">Members / Candidates Support Services (HELP) (BETA)</a></li>
							   </ol>';
		$data['result'] = array();
		if( isset( $_POST['btnSearch'] ) ) {
			$result = array();
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');
			$searchby = $this->input->post('optsearchby');
			$texttosearch = $this->input->post('txtsearch');
			$this->form_validation->set_rules('from_date','From Date','trim');
			if( !empty( $searchby ) ) {
				$this->form_validation->set_rules('txtsearch','Text to search','trim|required');
			}
			if($this->form_validation->run()==TRUE)
			{
				if( !empty( $from_date ) ) {
					$this->db->where('date(complain_date) >= ', date('Y-m-d', strtotime($from_date)) );
				}
				if( !empty( $to_date ) ) {
					$this->db->where('date(complain_date) <= ', date('Y-m-d', strtotime($to_date)));
				}
				if( !empty( $searchby ) ) {
					if( $searchby == 'cmp_id' )	{
						$this->db->where('sub_cat_cd', $texttosearch);	
					} else if( $searchby == 'memno' ) {
						$this->db->where('regnumber', $texttosearch);	
					} else if( $searchby == 'name' ) {
						$this->db->where('mem_name', $texttosearch);	
					} else if( $searchby == 'mobi' ) {
						$this->db->where('mobile', $texttosearch);	
					} else if( $searchby == 'emailid' ) {
						$this->db->where('email', $texttosearch);	
					} 
				}
				$res = $this->UserModel->getRecords("cms_master");
				if($res) {
					$result = $res->result_array();
				} 
			} else {
				$data['validation_errors'] = validation_errors(); 
			}
			$data['result'] = $result;
		} else {
			/*$res = $this->UserModel->getRecords("cms_master");
			if($res) {
				$result = $res->result_array();
				$data['result'] = $result;
			}*/	
			$data['result'] = array();
		}
		$this->load->view('admin/complaints_list',$data);
	}
}