<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF Common Logs
  ** Created BY: Sagar Matale On 27-11-2023
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Common_log_data extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
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
        $data['log_data'] = $this->master_model->getRecords('iibfbcbf_logs', array('pk_id' => $pk_id), 'log_id, module_slug, description, created_on', array('created_on'=>'DESC'));   
        //echo $this->db->last_query();
        $data['log_title'] = $log_title;      
        
        $result['response'] =  $this->load->view('iibfbcbf/common/get_logs_common',$data,TRUE);
        $result['flag'] = "success";
      }
      else
      {      
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }
  } ?>  