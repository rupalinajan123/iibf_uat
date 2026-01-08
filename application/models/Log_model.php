<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Log_model extends CI_Model 
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
	
    function create_careers_log($log_title, $log_desc = "", $unique_no, $position_id)
	{
		$obj = new OS_BR();
		$browser_details=implode('|',$obj->showInfo('all'));
		$data['unique_no'] = $unique_no;
		$data['position_id'] = $position_id;
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$data['ip'] = $this->input->ip_address();
		$data['browser'] = $browser_details;
		$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$this->db->insert('careers_logs', $data);
	}
		
	function create_log($log_title, $log_desc = "")
	{
		$obj = new OS_BR();
		$browser_details=implode('|',$obj->showInfo('all'));
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$data['regid'] = $this->session->userdata('regid') != "" ? $this->session->userdata('regid') : "";
		$data['regnumber'] = $this->session->userdata('regnumber') != "" ? $this->session->userdata('regnumber') : "";
		$data['ip'] = $this->input->ip_address();
		$data['browser'] = $browser_details;
		$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$this->db->insert('userlogs', $data);
	}
	function logcfptransaction($gateway, $pg_response, $result)
	{
		$data['date'] = date('Y-m-d H:i:s');
		$data['gateway'] = $gateway;
		$data['data'] = $pg_response;
		$data['result'] = $result;
		$this->db->insert('cfp_paymentlogs', $data);
	}
	function bulk_create_log($log_title, $log_desc = "",$inst_id = "")
	{
		$obj = new OS_BR();
		$browser_details=implode('|',$obj->showInfo('all'));
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$data['inst_id'] = $inst_id;
		$data['regid'] = $this->session->userdata('regid') != "" ? $this->session->userdata('regid') : "";
		$data['regnumber'] = $this->session->userdata('regnumber') != "" ? $this->session->userdata('regnumber') : "";
		$data['ip'] = $this->input->ip_address();
		$data['browser'] = $browser_details;
		$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$this->db->insert('bulk_userlogs', $data);
	}
	
	function create_nm_log($log_title, $log_desc = "")
	{
		$obj = new OS_BR();
		$browser_details=implode('|',$obj->showInfo('all'));
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$data['regid'] = $this->session->userdata('nmregid') != "" ? $this->session->userdata('nmregid') : "";
		$data['regnumber'] = $this->session->userdata('nmregnumber') != "" ? $this->session->userdata('nmregnumber') : "";
		$data['ip'] = $this->input->ip_address();
		$data['browser'] = $browser_details;
		$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$this->db->insert('userlogs', $data);
	}
	
	function create_dbf_log($log_title, $log_desc = "")
	{
		$obj = new OS_BR();
		$browser_details=implode('|',$obj->showInfo('all'));
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$data['regid'] = $this->session->userdata('dbregid') != "" ? $this->session->userdata('dbregid') : "";
		$data['regnumber'] = $this->session->userdata('dbregnumber') != "" ? $this->session->userdata('dbregnumber') : "";
		$data['ip'] = $this->input->ip_address();
		$data['browser'] = $browser_details;
		$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$this->db->insert('userlogs', $data);
	}
	
	function userActivity_log($log_title, $log_desc = "", $rId = NULL, $regNo = NULL)
	{
		$obj = new OS_BR();
		$browser_details=implode('|',$obj->showInfo('all'));
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$data['regid'] = $rId;
		$data['regnumber'] = $regNo;
		$data['ip'] = $this->input->ip_address();
		$data['browser'] = $browser_details;
		$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$this->db->insert('userlogs', $data);
	}
	
	function logXLRItransaction($gateway, $pg_response, $result)
	{
		$data['date'] = date('Y-m-d H:i:s');
		$data['gateway'] = $gateway;
		$data['data'] = $pg_response;
		$data['result'] = $result;
		$this->db->insert('XLRI_paymentlogs', $data);
	}
	function logJBIMStransaction($gateway, $pg_response, $result)
	{
		$data['date'] = date('Y-m-d H:i:s');
		$data['gateway'] = $gateway;
		$data['data'] = $pg_response;
		$data['result'] = $result;
		$this->db->insert('JBIMS_paymentlogs', $data);
	}
	
	function bulk_userActivity_log($log_title, $log_desc = "",$inst_id = "", $rId = NULL, $regNo = NULL)
	{
		$obj = new OS_BR();
		$browser_details=implode('|',$obj->showInfo('all'));
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$data['inst_id'] = $inst_id;
		$data['regid'] = $rId;
		$data['regnumber'] = $regNo;
		$data['ip'] = $this->input->ip_address();
		$data['browser'] = $browser_details;
		$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$this->db->insert('bulk_userlogs', $data);
	}
	
	/**
	 * Create admin activity logs		
	 * @access public 
	 * @param string
	 * @return  array
	*/ 
	function create_admin_log($log_title, $log_desc = "")
	{
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$data['userid'] = $this->session->userdata('id');
		if($this->session->userdata('dra_admin')) {
			$dra_data = $this->session->userdata('dra_admin');
			$data['userid'] = $dra_data['id'];
		}
		if($this->session->userdata('dra_institute')) {
			$drainst_data = $this->session->userdata('dra_institute');
			$data['userid'] = $drainst_data['id'];
		}
		$data['ip'] = $this->input->ip_address();
		$this->db->insert('adminlogs', $data);
	}
	
	// function to create DRA User (Institute) Log
	function create_dra_userlog($log_title, $log_desc = "")
	{
		$drainst_data = $this->session->userdata('dra_institute');
		
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$data['userid'] = $drainst_data['id'];
		$data['ip'] = $this->input->ip_address();
		$this->db->insert('dra_userlogs', $data);
	}
	
	// function to create DRA Admin Log
	function create_dra_adminlog($log_title, $log_desc = "")
	{
		$dra_data = $this->session->userdata('dra_admin');
		
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$data['userid'] = $dra_data['id'];
		$data['ip'] = $this->input->ip_address();
		$this->db->insert('dra_adminlogs', $data);
	}
	// function to create Exam Admin Log
	function create_exam_adminlog($log_title, $log_desc = "")
	{
		$dra_data = $this->session->userdata('exam_admin');
		
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$data['userid'] = $dra_data['id'];
		$data['ip'] = $this->input->ip_address();
		$this->db->insert('exam_adminlogs', $data);
	}
	
	function create_admin_profile_log($log_title, $log_desc = "", $type = "", $regid = 0,$regnum = "")
	{
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$data['type'] = $type;
		$data['regid'] = $regid;
		$data['regnumber'] = $regnum;
		$data['editedby'] = 'Admin';
		$data['editedbyid'] = $this->session->userdata('id');
		$data['date'] = date('Y-m-d H:i:s');
		$data['ip'] = $this->input->ip_address();
		$this->db->insert('profilelogs', $data);
	}
	
	function create_user_profile_log($log_title, $log_desc = "", $type = "", $regid = 0,$regnum = "")
	{
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$data['type'] = $type;
		$data['regid'] = $regid;
		$data['regnumber'] = $regnum;
		$data['editedby'] = 'User';
		$data['editedbyid'] = $regid;
		$data['date'] = date('Y-m-d H:i:s');
		$data['ip'] = $this->input->ip_address();
		$this->db->insert('profilelogs', $data);
	}
	function bulk_create_user_profile_log($log_title, $log_desc = "",$inst_id = "", $type = "", $regid = 0,$regnum = "")
	{
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$data['inst_id'] = $inst_id;
		$data['type'] = $type;
		$data['regid'] = $regid;
		$data['regnumber'] = $regnum;
		$data['editedby'] = 'User';
		$data['editedbyid'] = $regid;
		$data['date'] = date('Y-m-d H:i:s');
		$data['ip'] = $this->input->ip_address();
		$this->db->insert('profilelogs', $data);
	}
	
	function logtransaction($gateway, $pg_response, $result)
	{
		$data['date'] = date('Y-m-d H:i:s');
		$data['gateway'] = $gateway;
		$data['data'] = $pg_response;
		$data['result'] = $result;
		$this->db->insert('paymentlogs', $data);
	}
	//GARP exam payment logs
		function loggarptransaction($gateway, $pg_response, $result)
	{
		$data['date'] = date('Y-m-d H:i:s');
		$data['gateway'] = $gateway;
		$data['data'] = $pg_response;
		$data['result'] = $result;
		$this->db->insert('garp_paymentlogs', $data);
	}
	function logcharteredtransaction($gateway, $pg_response, $result)
	{
		$data['date'] = date('Y-m-d H:i:s');
		$data['gateway'] = $gateway;
		$data['data'] = $pg_response;
		$data['result'] = $result;
		$this->db->insert('chartered_paymentlogs', $data);
	}
	
	function logamptransaction($gateway, $pg_response, $result)
	{
		$data['date'] = date('Y-m-d H:i:s');
		$data['gateway'] = $gateway;
		$data['data'] = $pg_response;
		$data['result'] = $result;
		$this->db->insert('amp_paymentlogs', $data);
	}
	
	function logdratransaction($gateway, $pg_response, $result)
	{
		$data['date'] = date('Y-m-d H:i:s');
		$data['gateway'] = $gateway;
		$data['data'] = $pg_response;
		$data['result'] = $result;
		$this->db->insert('dra_paymentlogs', $data);
	}
	
	function cronlog($log_title, $log_desc = "")
	{
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$data['ip'] = $this->input->ip_address();
		$this->db->insert('cronlogs', $data);
	}
	
	function logrefundpayment($gateway, $pg_response, $result)
	{
		$data['date'] = date('Y-m-d H:i:s');
		$data['gateway'] = $gateway;
		$data['data'] = $pg_response;
		$data['result'] = $result;
		$this->db->insert('refund_paymentlogs', $data);
	}
	
	function create_bulk_adminlog($log_title, $log_desc = "")
	{
		$bulk_data = $this->session->userdata('bulk_admin');
		
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$data['userid'] = $bulk_data['id'];
		$data['ip'] = $this->input->ip_address();
		$this->db->insert('bulk_adminlogs', $data);
	}
}

/* End of file log.php */
/* Location: ./application/models/log.php */