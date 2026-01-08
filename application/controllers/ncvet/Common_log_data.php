<?php 
  /********************************************************************************************************************
  ** Description: Controller for NCVET Common Logs
  ** Created BY: Gaurav Shewale On 13-08-2025
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Common_log_data extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('ncvet/Ncvet_model');
      $this->load->helper('ncvet/ncvet_helper'); 
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
        $arr_candidate_log_data = $this->master_model->getRecords('ncvet_logs', array('pk_id' => $pk_id), 'log_id, module_slug, description, created_on', array('created_on'=>'DESC'));   
       
        $data['log_title'] = $log_title;
        $data['log_data']  = $arr_candidate_log_data;
        
        if ($module_slug == 'candidate_action') 
        {
          $module_slug_arr = ['kyc_recommender_Approved','kyc_recommender_Rejected','kyc_approver_Approved','kyc_approver_Rejected'];
        
          $this->db->where_in('module_slug',$module_slug_arr);
          $arr_kyc_log_data = $this->master_model->getRecords('ncvet_kyc_log_data', array('pk_id' => $pk_id), 'log_id, module_slug, description, created_on', array('created_on'=>'DESC'));

          $arr_log_data = array_merge($arr_candidate_log_data,$arr_kyc_log_data);

          usort($arr_log_data, function($a, $b) {
              return strtotime($b['created_on']) - strtotime($a['created_on']);
          }); 

          $data['log_data'] = $arr_log_data;
        }

        $result['response'] =  $this->load->view('ncvet/common/get_logs_common',$data,TRUE);
        $result['flag'] = "success";
      }
      else
      {      
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }
  } ?>  