<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class DBFTransaction extends CI_Controller {
	public function __construct()

	{
		parent::__construct();
		$this->load->helper('master_helper');
		$this->load->model('master_model');
		$this->load->model('chk_session');		
		$this->chk_session->chk_dbf_member_session();
		if($this->session->userdata('examinfo'))
		{
			$this->session->unset_userdata('examinfo');
		}
		if($this->session->userdata('examcode'))
		{
			$this->session->unset_userdata('examcode');
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
		$transData = $this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$this->session->userdata('dbregnumber'),'status'=>'1'),'',array('payment_transaction.id'=>'DESC'));
		
		//echo $this->db->last_query();exit;
		//echo "<pre>";print_r($transData);
		$data=array('middle_content'=>'dbf/transaction_history','transData'=>$transData);
			$this->load->view('dbf/dbf_common_view',$data);
	}
}

