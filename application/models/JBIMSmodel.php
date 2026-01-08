<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class JBIMSmodel extends CI_Model 
{
    function __construct()
    {
        parent::__construct();
    }
	
	/**
	 * Create AMP activity logs
	 * @access public 
	 * @param string
	 * @return  array
	*/ 
	function create_log($log_title, $log_desc = "")
	{
		$obj = new OS_BR();
		$browser_details=implode('|',$obj->showInfo('all'));
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$data['ip'] = $this->input->ip_address();
		$data['browser'] =$browser_details;
		$this->db->insert('JBIMS_logs', $data);
	}
	
}