<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
 
function log_dra_agency_admin($log_title, $log_message = "")
{
    $CI = & get_instance();
    $CI->load->model('Log_agency_model');
    $CI->Log_agency_model->create_dra_agency_adminlog($log_title, $log_message);
}

function log_dra_agency_center_detail($log_title,$center_id, $log_message = "")
{
    $CI = & get_instance();
    $CI->load->model('Log_agency_model');
    $CI->Log_agency_model->create_dra_agency_center_log($log_title,$center_id, $log_message);
}

function log_dra_agency_batch_detail($log_title,$batch_id, $log_message = "")
{
    $CI = & get_instance();
    $CI->load->model('Log_agency_model');
	$CI->Log_agency_model->create_dra_agency_batch_log($log_title,$batch_id, $log_message);   
}

function storedDraActivity($log_title, $log_data = "", $user_id)
{
    $CI = & get_instance();
    $CI->load->model('Log_agency_model');
	$CI->Log_agency_model->add_storedDraActivity($log_title, $log_data, $user_id);   
}

function config_batch_code($batch_id)
{
    $CI = & get_instance();
    $CI->load->model('Log_agency_model');
	return ($config_batch_code = $CI->Log_agency_model->config_batch_code($batch_id));
}

function config_batch_code_V2($batch_id)
{
    $CI = & get_instance();
    $CI->load->model('Log_agency_model');
    return ($config_batch_code = $CI->Log_agency_model->config_batch_code_V2($batch_id));
}

function log_dra_agency_action($log_title,$agency_id,$log_message = "")
{
    $CI = & get_instance();
    $CI->load->model('Log_agency_model');
    $CI->Log_agency_model->create_dra_agency_action_log($log_title,$agency_id,$log_message);
}

/* End of file general_helper.php */
/* Location: ./application/helpers/general_agency_helper.php */