<?php 
  /********************************************************************************************************************
  ** Description: Controller for SUPERVISION Common Logs
  ** Created BY: Priyanka Dhikale 20-may-24
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Common_log_data extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('supervision_model');
      $this->load->helper('supervision_helper'); 
		}    

    function get_logs_common_ajax()
    {
			if(isset($_POST) && count($_POST) > 0)
			{
        $enc_pk_id = $this->security->xss_clean($this->input->post('enc_pk_id'));
        $module_slug = $this->security->xss_clean($this->input->post('module_slug'));
        $module_slug_arr = explode(",",$module_slug);
        $log_title = $this->security->xss_clean($this->input->post('log_title'));

        $pk_id = url_decode($enc_pk_id); 
        
        $this->db->where_in('module_slug', $module_slug_arr);
        $data['log_data'] = $this->master_model->getRecords('supervision_logs', array('pk_id' => $pk_id), 'log_id, module_slug, description, created_on', array('created_on'=>'DESC'));   
        //echo $this->db->last_query();
        $data['log_title'] = $log_title;      
        
        $result['response'] =  $this->load->view('supervision/common/get_logs_common',$data,TRUE);
        $result['flag'] = "success";
      }
      else
      {      
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }
  } ?>  