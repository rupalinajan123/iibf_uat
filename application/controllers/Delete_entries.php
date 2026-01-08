<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Delete_entries extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			error_reporting(E_ALL);
		} 
		
		
		public function index($number='')
		{ 		
			if($number != "")
			{
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$res = $this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$number),'receipt_no,ref_id');

				echo'<pre>';print_r($res);
				foreach($res as $r) {
				
				
					$this->db->query("delete FROM `exam_invoice` where receipt_no='".$r['receipt_no']."';");
					
					$this->db->query("delete FROM `member_exam` where id='".$r['ref_id']."';");
					

					$res1 = $this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$r['ref_id']),'admitcard_id');

					foreach($res1 as $rr) {

					$this->db->query("delete FROM `seat_allocation` where admit_card_id='".$rr['admitcard_id']."';");

					}
					$this->db->query("delete FROM `admit_card_details` where mem_exam_id='".$r['ref_id']."';");
					//echo $this->db->last_query();
				}
				$this->db->query("delete FROM `payment_transaction` where member_regnumber ='".$number."';");
				echo'deleted';
			}
			else
			{
				echo 'Please add member number in url';
			}
		}
	}				