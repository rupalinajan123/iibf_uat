<?php 
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Test_csc extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      $this->load->helper('file'); 
      $this->load->helper('getregnumber_helper'); 
      $this->load->model('billdesk_pg_model');
      $this->load->model('log_model');
    }

    function index()
    {
      $server_ip = $_SERVER['SERVER_ADDR'];
      echo "<br>SERVER_ADDR IP Address: $server_ip";
      
      $ip = $_SERVER['REMOTE_ADDR']; 
      echo "<br>REMOTE_ADDR IP Address: $ip", "<br>"; 

      $app_server = explode('.',gethostname());
      if(isset($app_server[0])){ echo "<br>".$app_server[0];}
      echo "<br><br>";

      echo '<a href="'.base_url('iibfbcbf/test_csc/pay_now').'">Pay With CSC Wallet</a>';      
    }

    function pay_now()
    {
      $userarr = array();
      $userarr['regno'] = '101';
      $userarr['email'] = 'sagar.matale@esds.co.in';
      $userarr['exam_fee'] = '99';
      $userarr['exam_desc'] = 'Test Exam Description';
      $userarr['excode'] = '1039';
      $userarr['memtype'] = 'NM';
      $userarr['member_exam_id'] = '102';
      $this->session->set_userdata('non_memberdata', $userarr); 
      
      $this->session->set_userdata(array('memtype'=>'NM', 'csctype'=>'iibfbcbf_apply_exam'));
      //_pa($_SESSION,1);
      redirect(site_url('CSC_connect/User.php'));
    }
  }