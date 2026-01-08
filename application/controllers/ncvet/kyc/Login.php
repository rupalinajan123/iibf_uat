<?php 
  /********************************************************************************************************************
  ** Description: Controller for BULK KYC login page (Recommender & Approver) 
  ** Created BY: Anil S On 14-08-2025
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Login extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      $this->load->model('master_model');
      $this->load->model('ncvet/Kyc_model');
      $this->load->helper('ncvet/ncvet_helper');
    }

    /*public function generate_pass($value='')
    {
      echo $enc_password = $this->Kyc_model->password_encryption("Dharmik@2025#");
    }*/
    
    public function index()
    {   
      $this->load->helper('captcha');
      $this->Kyc_model->check_session_login(); // If session is already started then login page is directly redirect to dashboard
      $data['error'] = "";
      
      if(isset($_POST) && count($_POST) > 0)
      {
        $this->form_validation->set_rules('kyc_username','username','trim|required|xss_clean|callback_validation_check_username',array('required' => 'Please enter the %s'));
        $this->form_validation->set_rules('kyc_password','password','trim|required|xss_clean|callback_validation_check_login_details',array('required' => 'Please enter the %s'));	
        $this->form_validation->set_rules('kyc_captcha','code','trim|required|xss_clean|callback_validation_check_captcha',array('required' => 'Please enter the %s'));	
                
        if($this->form_validation->run())
        {
          generate_captcha('NCVET_KYC_LOGIN_CAPTCHA',6);
          
          $username = $this->input->post('kyc_username');
          $password = $this->input->post('kyc_password');	
          $remember_me = $this->input->post('kyc_remember_me');

          if($remember_me == '1')
          {            
            setcookie('COOKIE_KYC_USERNAME',$username, time()+60*60*24*365,'/');
            setcookie('COOKIE_KYC_PASSWORD',$password, time()+60*60*24*365,'/');
          }
          else
          {            
            setcookie('COOKIE_KYC_USERNAME',"", time()+60*60*24*365,'/');
            setcookie('COOKIE_KYC_PASSWORD',"", time()+60*60*24*365,'/');
          }
          
          $enc_password = $this->Kyc_model->password_encryption($password);

          //CHECK LOGIN DETAILS IN KYC ADMIN TABLE
          $admin_data = $this->master_model->getRecords('ncvet_kyc_admin', array('kyc_admin_username' => $username, 'kyc_admin_password' => $enc_password, 'is_active' => '1', 'is_deleted' => '0'), 'kyc_admin_id, kyc_admin_type, related_id, is_active, is_deleted');
          
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
              $this->Kyc_model->insert_user_login_logs($admin_data[0]['kyc_admin_id'],$admin_data[0]['kyc_admin_type'],'1');
              /******** END :  USER LOGIN LOGS CODE ********/

              $session_data = array('NCVET_KYC_LOGIN_ID' => $admin_data[0]['kyc_admin_id'], 'NCVET_KYC_ADMIN_TYPE' => $admin_data[0]['kyc_admin_type'], 'NCVET_KYC_RELATED_ID' => $admin_data[0]['related_id']);
              $this->session->set_userdata($session_data);						
              redirect(site_url('ncvet/kyc/kyc_dashboard'),'refresh');						
            }
          }
          else
          {
            $data['error'] = "Enter valid login details";
          }        						
        }			
      }     
      
      $data['COOKIE_KYC_USERNAME'] = $data['COOKIE_KYC_PASSWORD'] = '';      
      if(isset($_COOKIE['COOKIE_KYC_USERNAME']) && $_COOKIE['COOKIE_KYC_USERNAME'] != "") { $data['COOKIE_KYC_USERNAME'] = $_COOKIE['COOKIE_KYC_USERNAME']; }
      if(isset($_COOKIE['COOKIE_KYC_PASSWORD']) && $_COOKIE['COOKIE_KYC_PASSWORD'] != "") { $data['COOKIE_KYC_PASSWORD'] = $_COOKIE['COOKIE_KYC_PASSWORD']; }

      $data['captcha_img'] = generate_captcha('NCVET_KYC_LOGIN_CAPTCHA',6); //ncvet/ncvet_helper.php
      $data['page_title'] = 'NCVET - KYC Login';
      $this->load->view('ncvet/kyc/kyc_login',$data);
    }
    
    public function refresh_captcha() /******** START : REFRESH CAPTCHA ********/
    { 
      $this->load->helper('captcha');
      echo generate_captcha('NCVET_KYC_LOGIN_CAPTCHA',6); //ncvet/ncvet_helper.php
    }  /******** END : REFRESH CAPTCHA ********/
    
    /******** START : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/
    public function validation_check_captcha($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
      if(isset($_POST) && $_POST['kyc_captcha'] != "")
      {
        if($type == '1') { $captcha = $this->security->xss_clean($this->input->post('kyc_captcha')); }
        else if($type == '0') { $captcha = $str; }
        
        $session_captcha = $this->session->userdata('NCVET_KYC_LOGIN_CAPTCHA');
        
        if($captcha == $session_captcha)
        {
          $return_val_ajax = 'true';
        }
      }   
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['kyc_captcha'] != "")
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
      if(isset($_POST) && $_POST['kyc_username'] != "")
      {
        if($type == '1') { $kyc_username = $this->security->xss_clean($this->input->post('kyc_username')); }
        else if($type == '0') { $kyc_username = $str; }
        
        $result_data = $this->master_model->getRecords('ncvet_kyc_admin', array('kyc_admin_username' => $kyc_username, 'is_active' => '1', 'is_deleted' => '0'), 'kyc_admin_id, is_active, is_deleted');
        
        if(count($result_data) > 0) { $return_val_ajax = 'true'; }
      
        if($type == '1') { echo $return_val_ajax; }
        else if($type == '0') 
        { 
          if($return_val_ajax == 'true') { return TRUE; } 
          else if($_POST['kyc_username'] != "")
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
      $return_val_ajax = 'false';
      if(isset($_POST) && $_POST['kyc_username'] != "" && $_POST['kyc_password'] != "")
      {
        $kyc_username = $this->security->xss_clean($this->input->post('kyc_username'));
        
        if($type == '1') { $kyc_password = $this->security->xss_clean($this->input->post('kyc_password')); }
        else if($type == '0') { $kyc_password = $str; }

        $enc_password = $this->Kyc_model->password_encryption($kyc_password);
        
        $result_data = $this->master_model->getRecords('ncvet_kyc_admin', array('kyc_admin_username' => $kyc_username, 'kyc_admin_password' => $enc_password, 'is_active' => '1', 'is_deleted' => '0'), 'kyc_admin_id, is_active, is_deleted');
        
        if(count($result_data) > 0) { $return_val_ajax = 'true'; }
      
        if($type == '1') { echo $return_val_ajax; }
        else if($type == '0') 
        { 
          if($return_val_ajax == 'true') { return TRUE; } 
          else if($_POST['kyc_password'] != "")
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
      $user_id = $this->session->userdata('NCVET_KYC_LOGIN_ID');
      $user_type = $this->session->userdata('NCVET_KYC_ADMIN_TYPE');
      if(isset($user_id) && $user_id != "" && isset($user_type) && $user_type != "") { $this->Kyc_model->insert_user_login_logs($user_id,$user_type,'2'); }
      /******** END :  USER LOGOUT LOGS CODE ********/
      
      $admin_session_data = array('NCVET_KYC_LOGIN_ID' => "", 'NCVET_KYC_ADMIN_TYPE' => "", 'NCVET_KYC_RELATED_ID'=>"");
      $this->session->set_userdata($admin_session_data);
      redirect(site_url('ncvet/kyc/login'),'refresh');
    }	
  }