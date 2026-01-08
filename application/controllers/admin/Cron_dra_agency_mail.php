<?php
/*
 	* Controller Name	:	Cpdcron
 	* Created By		:	Chaitali
 	* Created Date		:	08-01-2020
*/
//https://iibf.esdsconnect.com/admin/Cpdcron/cpd_data

defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_dra_agency_mail extends CI_Controller {
			
public function __construct()
{
		parent::__construct();
		
		$this->load->model('UserModel');
		$this->load->model('Master_model');
		$this->load->library('email');
		$this->load->model('Emailsending');
		$this->load->helper('dra_agency_center_mail_helper');
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
}

public function index() 
{
	$current_date = date('Y-m-d');
	$check_date = date('Y-m-d',strtotime("+ 2 day",strtotime($current_date))); 
	$approve_batch = $this->Master_model->getRecords('agency_batch  ',array('batch_status'=>'A','batch_to_date'=>$check_date));
	//echo $this->db->last_query(); exit;
	if(!empty($approve_batch))
	{
		foreach($approve_batch as $res)
		{
				$batch_id = $res['id'];
				$inspector_id = $res['inspector_id'];
				$inspector_details = $this->Master_model->getRecords('agency_inspector_master  ',array('id'=>$inspector_id));
				 $inspector_email = $inspector_details[0]['inspector_email'];
				if($inspector_email != '')
				{
					$agency_mail = batch_inspection_mail_reminder($batch_id,$inspector_email); 
					
				} 
				
			
		} 
	}
}
}