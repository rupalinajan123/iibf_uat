<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Refund_email extends CI_Controller {
	public function __construct(){
		 parent::__construct(); 
		 $this->load->model('Master_model');
		 $this->load->library('email');
		 $this->load->model('Emailsending');
	}
	public function index(){
		$today = date('Y-m-d');
		$select = 'm.req_member_no,d.email,d.firstname,m.transaction_no,p.amount';
		$this->db->join('member_registration d','d.regnumber = m.req_member_no','LEFT');
		$this->db->join('payment_transaction p','p.transaction_no = m.transaction_no','LEFT');
		$credit_note_data = $this->Master_model->getRecords('maker_checker m',array('DATE(m.refund_date)' => $today,'m.req_status' => '5'),$select);
		 
		foreach($credit_note_data as $record){
			$message = 'Dear '.$record['firstname'].' ,<br><br>The Refund has been initiated today for transaction no '.$record['transaction_no'].'.<br><br> The amount ('.$record['amount'].') will be credited in the account in 8-10 working days.<br><br>Thank You,<br> IIBF Team.';
			
			$info_arr=array('to'=>$record['email'],
							'from'=>'noreply@iibf.org.in',
							'subject'=>'IIBF: Amount Refund Status',
							'message'=>$message
							);
			$this->Emailsending->mailsend($info_arr);
		}
	}
}