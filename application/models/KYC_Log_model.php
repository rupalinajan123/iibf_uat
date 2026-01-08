<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class KYC_Log_model extends CI_Model 
{

    function __construct()
    {
        parent::__construct();
    }
	
	/**
	 * Create activity logs		
	 * @access public 
	 * @param string
	 * @return  array
	*/ 
	
	//Kyc  all Activity logs 	
	function create_log($log_title,$user_id,$kyc_id=false,$regnumber=false,$log_desc = "")
	{
		$today = date("Y-m-d H:i:s");
		$data['regnumber'] =$regnumber;
		$data['date']  = $today;
		$data['user_id'] =$user_id;
		$data['kyc_id'] =$kyc_id;
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$this->db->insert('kyc_log', $data);
	}
		
		//Kyc  email  logs 	
	function email_log($kyc_id ,$user_id,$type,$email_reminder_count=false ,$regnumber=false,$email,$email_date,$user_type)
	{
		$today = date("Y-m-d H:i:s");
		$data['kyc_id'] =$kyc_id;
		$data['user_id']  = $user_id;
		$data['type'] =$type;
		$data['email_reminder_count'] =$email_reminder_count;
		$data['regnumber'] = $regnumber;
		$data['email'] = $email;
		$data['email_date'] = $today;
		$data['user_type'] = $user_type;
		$this->db->insert('kyc_email_logs', $data);
	}
	
	function benchmark_create_log($log_title,$user_id,$kyc_id=false,$regnumber=false,$log_desc = "")
	{
		$today = date("Y-m-d H:i:s");
		$data['regnumber'] =$regnumber;
		$data['date']  = $today;
		$data['user_id'] =$user_id;
		$data['kyc_id'] =$kyc_id;
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$this->db->insert('benchmark_kyc_log', $data);
	}
		
		//Kyc  email  logs 	
	function benchmark_email_log($kyc_id ,$user_id,$type,$email_reminder_count=false ,$regnumber=false,$email,$email_date,$user_type)
	{
		$today = date("Y-m-d H:i:s");
		$data['kyc_id'] =$kyc_id;
		$data['user_id']  = $user_id;
		$data['type'] =$type;
		$data['email_reminder_count'] =$email_reminder_count;
		$data['regnumber'] = $regnumber;
		$data['email'] = $email;
		$data['email_date'] = $today;
		$data['user_type'] = $user_type;
		$this->db->insert('benchmark_kyc_email_logs', $data);
	}

	//scribe kyc logs : Pooja Mane : 12/12/2022
	function scribe_create_log($log_title,$user_id,$kyc_id=false,$regnumber=false,$log_desc = "")
	{
		$today = date("Y-m-d H:i:s");
		$data['regnumber'] =$regnumber;
		$data['date']  = $today;
		$data['user_id'] =$user_id;
		$data['kyc_id'] =$kyc_id;
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$this->db->insert('scribe_kyc_log', $data);
	}

	//scribe Kyc  email  logs 	
	function scribe_email_log($kyc_id ,$user_id,$type,$email_reminder_count=false ,$regnumber=false,$email,$email_date,$user_type)
	{
		$today = date("Y-m-d H:i:s");
		$data['kyc_id'] =$kyc_id;
		$data['user_id']  = $user_id;
		$data['type'] =$type;
		$data['email_reminder_count'] =$email_reminder_count;
		$data['regnumber'] = $regnumber;
		$data['email'] = $email;
		$data['email_date'] = $today;
		$data['user_type'] = $user_type;
		$this->db->insert('scribe_kyc_email_logs', $data);
	}
	//scribe logs end By Pooja Mane : 12/12/2022
}
