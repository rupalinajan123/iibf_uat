<?php 
  /********************************************************************************************************************
  ** Description: Controller for APABI INDIA ADMIN PANEL
  ** Created BY: Sagar Matale On 30-09-2024
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Login extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      $this->load->model('master_model');
      $this->load->model('Apabi_india_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper');
    }
    
    public function index()
    {   
      $this->load->helper('captcha');
      $this->Apabi_india_model->check_apabi_india_session_login(); // If session is already started then login page is directly redirect to dashboard
      $data['error'] = "";
      
      if(isset($_POST) && count($_POST) > 0)
      {
        $this->form_validation->set_rules('apabi_india_admin_username','username','trim|required|xss_clean|callback_validation_check_username',array('required' => 'Please enter the %s'));
        $this->form_validation->set_rules('apabi_india_admin_password','password','trim|required|xss_clean|callback_validation_check_login_details',array('required' => 'Please enter the %s'));	
        $this->form_validation->set_rules('apabi_india_admin_captcha','code','trim|required|xss_clean|callback_validation_check_captcha',array('required' => 'Please enter the %s'));	
                
        if($this->form_validation->run())
        {
          generate_captcha('APABI_INDIA_ADMIN_LOGIN_CAPTCHA',6);
          
          $username = $this->input->post('apabi_india_admin_username');
          $password = $this->input->post('apabi_india_admin_password');	
          $remember_me = $this->input->post('apabi_india_admin_captcha');

          if($remember_me == '1')
          {            
            setcookie('COOKIE_APABI_INDIA_ADMIN_USERNAME',$username, time()+60*60*24*365,'/');
            setcookie('COOKIE_APABI_INDIA_ADMIN_PASSWORD',$password, time()+60*60*24*365,'/');
          }
          else
          {            
            setcookie('COOKIE_APABI_INDIA_ADMIN_USERNAME',"", time()+60*60*24*365,'/');
            setcookie('COOKIE_APABI_INDIA_ADMIN_PASSWORD',"", time()+60*60*24*365,'/');
          }
          
          $enc_password = $this->Apabi_india_model->password_encryption($password);

          //FIRST CHECK LOGIN DETAILS IN ADMIN TABLE
          $admin_data = $this->master_model->getRecords('apabi_india_admin', array('admin_username' => $username, 'admin_password' => $enc_password, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id, is_active, is_deleted');
          
          if(count($admin_data) > 0)
          {
            if($admin_data[0]['is_deleted'] == '1')
            {	
              $data['error'] = "Your account is deleted. Please contact with administrator.";
            }
            else if($admin_data[0]['is_active'] == '0')
            {	
              $data['error'] = "Your account is not active. Please contact with administrator.";
            }
            else
            {						
              /******** START :  USER LOGIN LOGS CODE ********/
              $this->Apabi_india_model->insert_user_login_logs($admin_data[0]['admin_id'],'admin','1');
              /******** END :  USER LOGIN LOGS CODE ********/

              $session_data = array('APABI_INDIA_ADMIN_LOGIN_ID' => $admin_data[0]['admin_id']);
              $this->session->set_userdata($session_data);						
              redirect(site_url('apabi_india_admin/dashboard_admin'),'refresh');						
            }
          }
          else
          {
            $data['error'] = "Enter valid login details";
          }        						
        }			
      }     
      
      $data['COOKIE_APABI_INDIA_USERNAME'] = $data['COOKIE_APABI_INDIA_PASSWORD'] = '';      
      if(isset($_COOKIE['COOKIE_APABI_INDIA_USERNAME']) && $_COOKIE['COOKIE_APABI_INDIA_USERNAME'] != "") { $data['COOKIE_APABI_INDIA_USERNAME'] = $_COOKIE['COOKIE_APABI_INDIA_USERNAME']; }
      if(isset($_COOKIE['COOKIE_APABI_INDIA_PASSWORD']) && $_COOKIE['COOKIE_APABI_INDIA_PASSWORD'] != "") { $data['COOKIE_APABI_INDIA_PASSWORD'] = $_COOKIE['COOKIE_APABI_INDIA_PASSWORD']; }

      $data['captcha_img'] = generate_captcha('APABI_INDIA_ADMIN_LOGIN_CAPTCHA',6); //iibfbcbf/apabi_helper.php
      $data['page_title'] = 'APABI INDIA Admin Login';
      $this->load->view('apabi_india_admin/login',$data);
    }
    
    public function refresh_captcha() /******** START : REFRESH CAPTCHA ********/
    { 
      $this->load->helper('captcha');
      echo generate_captcha('APABI_INDIA_ADMIN_LOGIN_CAPTCHA',6); //iibfbcbf/apabi_helper.php
    }  /******** END : REFRESH CAPTCHA ********/
    
    /******** START : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/
    public function validation_check_captcha($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
      if(isset($_POST) && $_POST['apabi_india_admin_captcha'] != "")
      {
        if($type == '1') { $captcha = $this->security->xss_clean($this->input->post('apabi_india_admin_captcha')); }
        else if($type == '0') { $captcha = $str; }
        
        $session_captcha = $this->session->userdata('APABI_INDIA_ADMIN_LOGIN_CAPTCHA');
        
        if($captcha == $session_captcha)
        {
          $return_val_ajax = 'true';
        }
      }   
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['apabi_india_admin_captcha'] != "")
        {
          $this->form_validation->set_message('validation_check_captcha','Please enter the valid code');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/

    /******** START : VALIDATION FUNCTION TO CHECK THE CORRECT USERNAME ********/
    public function validation_check_username($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
      if(isset($_POST) && $_POST['apabi_india_admin_username'] != "")
      {
        if($type == '1') { $apabi_india_admin_username = $this->security->xss_clean($this->input->post('apabi_india_admin_username')); }
        else if($type == '0') { $apabi_india_admin_username = $str; }
        
        $result_data = $this->master_model->getRecords('apabi_india_admin', array('admin_username' => $apabi_india_admin_username, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id, is_active, is_deleted');

        if(count($result_data) > 0) { $return_val_ajax = 'true'; }
      
        if($type == '1') { echo $return_val_ajax; }
        else if($type == '0') 
        { 
          if($return_val_ajax == 'true') { return TRUE; } 
          else if($_POST['apabi_india_admin_username'] != "")
          {
            $this->form_validation->set_message('validation_check_username','Please enter the valid username');
            return false;
          }
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK THE CORRECT USERNAME ********/

    /******** START : VALIDATION FUNCTION TO CHECK THE CORRECT LOGIN DETAILS ********/
    public function validation_check_login_details($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $this->load->library('upload');	
			$this->load->helper('upload_helper');
			$this->load->helper('master_helper');
			$this->load->model('master_model');		
			$this->load->library('email');
			$this->load->model('Emailsending');
			$this->load->model('chk_session');

      $return_val_ajax = 'false';
      if(isset($_POST) && $_POST['apabi_india_admin_username'] != "" && $_POST['apabi_india_admin_password'] != "")
      {
        $apabi_india_admin_username = $this->security->xss_clean($this->input->post('apabi_india_admin_username'));
        
        if($type == '1') { $apabi_india_admin_password = $this->security->xss_clean($this->input->post('apabi_india_admin_password')); }
        else if($type == '0') { $apabi_india_admin_password = $str; }

        $enc_password = $this->Apabi_india_model->password_encryption($apabi_india_admin_password);

        $result_data = $this->master_model->getRecords('apabi_india_admin', array('admin_username' => $apabi_india_admin_username, 'admin_password' => $enc_password, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id, is_active, is_deleted');
        
        if(count($result_data) > 0) { $return_val_ajax = 'true'; }
      
        if($type == '1') { echo $return_val_ajax; }
        else if($type == '0') 
        { 
          if($return_val_ajax == 'true') { return TRUE; } 
          else if($_POST['apabi_india_admin_password'] != "")
          {
            $this->form_validation->set_message('validation_check_login_details','Enter valid password');
            return false;
          }
        }
      }
    } /******** END : VALIDATION FUNCTION TO CHECK THE CORRECT LOGIN DETAILS ********/  
  	
    public function logout()
    {
      /******** START :  USER LOGOUT LOGS CODE ********/
      $user_id = $this->session->userdata('APABI_INDIA_ADMIN_LOGIN_ID');
      if(isset($user_id) && $user_id != "") { $this->Apabi_india_model->insert_user_login_logs($user_id,'','2'); }
      /******** END :  USER LOGOUT LOGS CODE ********/
      
      $admin_session_data = array('APABI_INDIA_ADMIN_LOGIN_ID' => "");
      $this->session->set_userdata($admin_session_data);
      redirect(site_url('apabi_india_admin/login'),'refresh');
    }	
  }