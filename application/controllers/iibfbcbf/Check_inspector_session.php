<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Check_inspector_session extends CI_Controller
{  
  public function __construct()
  {
    parent::__construct();       
    $this->load->model('master_model');
    $this->load->model('iibfbcbf/Iibf_bcbf_model');
    $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
    
    $this->login_inspector_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
    $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');
  }

  public function index()
  {
    $result['flag'] = "error";	
    if($this->login_user_type == 'inspector')
    {
      $result['flag'] = "success";	
    }
    
    echo json_encode($result);
  }
}
