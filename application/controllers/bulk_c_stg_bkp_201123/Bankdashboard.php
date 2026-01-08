<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Bankdashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->model('Master_model');
        $this->load->helper('pagination_helper');
        $this->load->library('pagination');
        $this->load->helper('upload_helper');
        $this->load->library('email');
        $this->load->model('Emailsending');
		//$this->load->model('chk_session');
		//$this->chk_session->chk_bank_login_session();
    }
	
	/* Bulk Dashboard */
    public function index()
    {
		$data  = array('middle_content' => 'bulk/bank_dashboard');
		$this->load->view('bulk/bulk_common_view', $data);
		//$this->load->view('bulk/bank_dashboard');
    }
	
	/* Bank Profile */
	public function view_profile()
    {
		if(empty($this->session->userdata['institute_id']))
		{
			redirect(base_url().'bulk/Banklogin');
		}
			
			$instid = $_SESSION['institute_id'];
			$instRes = $this->master_model->getRecords('bulk_accerdited_master',array('institute_code '=>$instid));
			
			
		$data  = array('middle_content' => 'bulk/view_profile','instRes'=>$instRes);
       	    $this->load->view('bulk/bulk_common_view', $data);
		//$this->load->view('bulk/view_profile');
    }
	/* Bank Logout */
	public function  logout()
    {
		$data  = array('middle_content' => 'bulk/view_profile');
       	    $this->load->view('bulk/bulk_common_view', $data);
		//$this->load->view('bulk/view_profile');
    }
	
	/* Apply Exam Function */
  	public function exam_applicantlst()
    {
		$data  = array('middle_content' => 'bulk/exam_applicantlst');
       	    $this->load->view('bulk/bulk_common_view', $data);
		//$this->load->view('bulk/exam_applicantlst');
    }
	
	/* Transcation Function */
	public function transcation()
    {
		$data  = array('middle_content' => 'bulk/transaction/transactions');
       	$this->load->view('bulk/bulk_common_view', $data);
		//$this->load->view('bulk/transaction/transactions');
    }
	
	/* Add member  Function */
public function add_member()
{
	if(isset($_POST['getdata']))
	{
		$this->form_validation->set_rules('regnumber', 'Membership No.', 'trim|required|xss_clean');
		if ($this->form_validation->run() == TRUE) 
		{
			$mem_info=array();
			if(isset($_POST['regnumber']))
			{
				$mem_no=$_POST['regnumber'];
			}else
			{
				$this->session->set_flashdata('error','Enter the Membership number not present!!');
				redirect(base_url('bulk/Bankdashboard/add_member/'));
			}
		//query to get member details
			$this->db->where("isactive",'1'); 
			$mem_info=  $this->master_model->getRecords('member_registration',array('regnumber'=>$mem_no));
			
			if(!empty($mem_info))
			{
				$data['mem_info']=$mem_info;
			}else
			{                          
				$mem_info=array();
				$data['mem_info']=$mem_info;
				$this->session->set_flashdata('error','Membership number not present!!');
				redirect(base_url('bulk/Bankdashboard/add_member/'));
			}
			$data  = array('middle_content' => 'bulk/bulk_add_member');
       	    $this->load->view('bulk/bulk_common_view', $data);
			//$this->load->view('bulk/bulk_add_member',$data);
		}else
		{
			$this->session->set_flashdata('error','Enter the Membership number !');
			redirect(base_url('bulk/Bankdashboard/add_member/'));
		}
			
	}else
	{
			$mem_info=array();
			$data['mem_info']=$mem_info;
			$data  = array('middle_content' => 'bulk/bulk_add_member');
       	    $this->load->view('bulk/bulk_common_view', $data);
			//$this->load->view('bulk/bulk_add_member',$data);	
	}
	}
	
}