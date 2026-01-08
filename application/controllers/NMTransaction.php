<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class NMTransaction extends CI_Controller {
	public function __construct()

	{
		parent::__construct();
		$this->load->helper('master_helper');
		$this->load->model('master_model');
		$this->load->model('chk_session');		
		$this->chk_session->chk_non_member_session();
		if($this->session->userdata('examinfo'))
		{
			$this->session->unset_userdata('examinfo');
		}
		if($this->session->userdata('examcode'))
		{
			$this->session->unset_userdata('examcode');
		}
		if($this->session->userdata('nm_without_pass') && ($this->session->userdata('nm_without_pass') == 1)){
			$this->session->set_flashdata('error_nm_without_pass', 'You do not have access.');
			redirect(base_url() . 'NonMember/examlist');
		}
	}
	
	// Function to view member transaction details (Vrushali)
	public function index()
	{
		/*$this->db->select('payment_transaction.*,exam_master.description as description,payment_transaction.description as description');
  	    $this->db->join('member_exam','member_exam.exam_code=payment_transaction.exam_code');
		$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period'); 
		$this->db->join('exam_master','exam_master.exam_code=exam_activation_master.exam_code');
		$this->db->where('member_exam.pay_status','1');*/
		
		$transData1 = $this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$this->session->userdata('nmregnumber'),'status'=>'1'),'',array('payment_transaction.id'=>'DESC'));
		
		
		
		//check member is bulk or not (Tejasvi)
		$transData2 = array();
		$bulk_entry = '';
		$is_bulk_entry = $this->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$this->session->userdata('nmregnumber'),'record_source'=>'Bulk'),'record_source');
		if(count($is_bulk_entry))
		{
			$bulk_entry = 'Bulk';
		}
		if($bulk_entry == 'Bulk')
		{
			$not_nos = array(2,3);
			//$this->db->join('member_exam','member_exam.regnumber = member_registration.regnumber');
			$this->db->join('bulk_member_payment_transaction','bulk_member_payment_transaction.memexamid= member_exam.id');
			$this->db->join('bulk_payment_transaction','bulk_payment_transaction.id=bulk_member_payment_transaction.ptid');
			$this->db->where(array('member_exam.bulk_isdelete!='=>1,'bulk_payment_transaction.status'=>1,'member_exam.regnumber'=>$this->session->userdata('nmregnumber')));
			$this->db->where_not_in('member_exam.pay_status',$not_nos);
			$transData2 = $this->master_model->getRecords('member_exam');
			
		}
		$data['transData']= array_merge($transData1,$transData2);
		$data['middle_content']= 'nonmember/transaction_history';
		//echo $this->db->last_query();exit;
		//echo "<pre>";print_r($transData);
		//$data=array('middle_content'=>'nonmember/transaction_history');
		$this->load->view('nonmember/nm_common_view',$data);
	}
}

