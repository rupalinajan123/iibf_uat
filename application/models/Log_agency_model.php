<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Log_agency_model extends CI_Model 
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
	
	// function to create DRA Aagency Admin Log
	function create_dra_agency_adminlog($log_title, $log_desc = "")
	{
		$dra_data = $this->session->userdata('dra_admin');
		
		$data['title'] = $log_title;
		$data['description'] = $log_desc;
		$data['userid'] = $dra_data['id'];
		$data['date'] = date('Y-m-d H:i:s');
		$data['ip'] = $this->input->ip_address();
		$this->db->insert('dra_agency_adminlogs', $data);
	}
	
	
	// function to create DRA Aagency Center Admin Log
	function create_dra_agency_center_log($log_title,$center_id,$log_desc = "")
	{
		$dra_data = $this->session->userdata('dra_admin');		
		$data['title'] = $log_title;
		$data['center_id'] = $center_id;
		$data['description'] = $log_desc;
		$data['date'] = date('Y-m-d H:i:s');
		$data['userid'] = $dra_data['id'];
		$data['ip'] = $this->input->ip_address();
		$this->db->insert('dra_agency_center_adminlogs', $data);
	}
	
	
	// function to create DRA Aagency Batch Admin Log
	function create_dra_agency_batch_log($log_title,$batch_id,$log_desc = "")
	{
		$dra_data = $this->session->userdata('dra_admin');		
		$data['title'] = $log_title;
		$data['batch_id'] = $batch_id;
		$data['description'] = $log_desc;
		$data['date'] = date('Y-m-d H:i:s');
		$data['userid'] = $dra_data['id'];
		$data['ip'] = $this->input->ip_address();
		$this->db->insert('dra_agency_batch_adminlogs', $data);
	}
	

	function add_storedDraActivity($log_title, $log_data = "", $user_id)
	{
		$obj = new OS_BR();
		$browser_details=implode('|',$obj->showInfo('all'));
		$data['title'] = $log_title;
		$data['description'] = $log_data;
		$data['user_id'] = $user_id;
		$data['date'] = date('Y-m-d H:i:s');
		$data['ip'] = $this->input->ip_address();
		$data['browser'] = $browser_details;
		$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$this->db->insert('dra_agency_center_logs', $data);
	}
	
	
	function config_batch_code($batch_id)
	{
		$data['batch_id'] = $batch_id;		
		$this->db->insert('config_batch_code', $data);
		return $this->db->insert_id();
	}

  function config_batch_code_V2($batch_id)
  {
    $data['batch_id'] = $batch_id;    
    $this->db->insert('config_batch_code_V2', $data);
    return $this->db->insert_id();
  }
	
	
	// function to create DRA Aagency Action Admin Log ( Eg: Activate / deactivate agency)
	function create_dra_agency_action_log($log_title,$agency_id,$log_desc = "")
	{
		$logs_array = unserialize($log_desc);
		
		$dra_data = $this->session->userdata('dra_admin');		
		$data['title']       = $log_title;
		$data['agency_id']   = $agency_id;
		$data['reason']      = isset($logs_array['reason']) ? $logs_array['reason']:'';
		$data['description'] = $log_desc;
		$data['date'] = date('Y-m-d H:i:s');
		$data['userid'] = $dra_data['id'];
		$data['ip'] = $this->input->ip_address();
		$this->db->insert('dra_agency_action_adminlogs', $data);
	}	
	
}

/* End of file log.php */
/* Location: ./application/models/Log_agency_model.php */