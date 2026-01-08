<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Check_inspector_session extends CI_Controller
{  
  public function __construct()
  {
    parent::__construct();       
    $this->load->helper('master_helper');
    $this->load->model('master_model');
    $this->load->model('UserModel');
    $this->load->helper('pagination_helper');
    $this->load->library('pagination');
    $this->load->helper('general_helper');
  }

  public function index()
  {
    $result['flag'] = "error";	
    if ($this->session->userdata('dra_inspector'))
    {
      $result['flag'] = "success";	
    }
    
    echo json_encode($result);
  }
}
