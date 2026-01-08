<?php 
  /********************************************************************************************************************
  ** Description: Controller for NCVET Global login page (Global : Admin) 
  ** Created BY: Gaurav Shewale On 11-08-2025
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Login extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      $this->load->model('master_model');
      $this->load->model('ncvet/Ncvet_model');
      $this->load->helper('ncvet/ncvet_helper');
    }
    
    public function index()
    {   
      $this->load->helper('captcha');
      $this->Ncvet_model->check_session_login(); // If session is already started then login page is directly redirect to dashboard
      $data['error'] = "";
     
      if(isset($_POST) && count($_POST) > 0)
      {
        $this->form_validation->set_rules('ncvet_username','username','trim|required|xss_clean|callback_validation_check_username',array('required' => 'Please enter the %s'));
        $this->form_validation->set_rules('ncvet_password','password','trim|required|xss_clean|callback_validation_check_login_details',array('required' => 'Please enter the %s'));	
        $this->form_validation->set_rules('ncvet_captcha','code','trim|required|xss_clean|callback_validation_check_captcha',array('required' => 'Please enter the %s'));	
                
        if($this->form_validation->run())
        {
          generate_captcha('NCVET_LOGIN_CAPTCHA',6);
          
          $username = $this->input->post('ncvet_username');
          $password = $this->input->post('ncvet_password');	
          $remember_me = $this->input->post('ncvet_remember_me');

          if($remember_me == '1')
          {            
            setcookie('COOKIE_NCVET_USERNAME',$username, time()+60*60*24*365,'/');
            setcookie('COOKIE_NCVET_PASSWORD',$password, time()+60*60*24*365,'/');
          }
          else
          {            
            setcookie('COOKIE_NCVET_USERNAME',"", time()+60*60*24*365,'/');
            setcookie('COOKIE_NCVET_PASSWORD',"", time()+60*60*24*365,'/');
          }
          
          $enc_password = $this->Ncvet_model->password_encryption($password);

          //FIRST CHECK LOGIN DETAILS IN ADMIN TABLE
          $admin_data = $this->master_model->getRecords('ncvet_admin', array('admin_username' => $username, 'admin_password' => $enc_password, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id, is_active, is_deleted');
          
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
              $this->Ncvet_model->insert_user_login_logs($admin_data[0]['admin_id'],'admin','1');
              /******** END :  USER LOGIN LOGS CODE ********/

              $session_data = array('NCVET_LOGIN_ID' => $admin_data[0]['admin_id'], 'NCVET_USER_TYPE' => 'admin');
              $this->session->set_userdata($session_data);						
              redirect(site_url('ncvet/admin/dashboard_admin'),'refresh');						
            }
          }
          else //IF LOGIN DETAILS NOT FOUND IN ADMIN TABLE, THEN CHECK IN AGENCY TABLE
          {
            $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_code' => $username, 'agency_password' => $enc_password, 'is_active' => '1', 'is_deleted' => '0'), 'agency_id, agency_code, is_active, is_deleted');

            if(count($agency_data) > 0)
            {
              if($agency_data[0]['is_deleted'] == '1')
              {	
                $data['error'] = "Your account is deleted. Please contact with administrator.";
              }
              else if($agency_data[0]['is_active'] == '0')
              {	
                $data['error'] = "Your account is not active. Please contact with administrator.";
              }
              else
              {		
                /******** START :  USER LOGIN LOGS CODE ********/
                $this->Ncvet_model->insert_user_login_logs($agency_data[0]['agency_id'],'agency','1');
                /******** END :  USER LOGIN LOGS CODE ********/

                $session_data = array('NCVET_LOGIN_ID' => $agency_data[0]['agency_id'], 'NCVET_USER_TYPE' => 'agency', 'NCVET_AGENCY_CODE'=>$agency_data[0]['agency_code']);
                $this->session->set_userdata($session_data);						
                redirect(site_url('ncvet/agency/dashboard_agency'),'refresh');						
              }
            }            
          }         						
        }			
      }     
      
      $data['COOKIE_NCVET_USERNAME'] = $data['COOKIE_NCVET_PASSWORD'] = '';      
      if(isset($_COOKIE['COOKIE_NCVET_USERNAME']) && $_COOKIE['COOKIE_NCVET_USERNAME'] != "") { $data['COOKIE_NCVET_USERNAME'] = $_COOKIE['COOKIE_NCVET_USERNAME']; }
      if(isset($_COOKIE['COOKIE_NCVET_PASSWORD']) && $_COOKIE['COOKIE_NCVET_PASSWORD'] != "") { $data['COOKIE_NCVET_PASSWORD'] = $_COOKIE['COOKIE_NCVET_PASSWORD']; }

      $data['captcha_img'] = generate_captcha('NCVET_LOGIN_CAPTCHA',6); //ncvet/ncvet_helper.php
      $data['page_title'] = 'IIBF - NCVET Login';
      $this->load->view('ncvet/login',$data);
    }
    
    public function refresh_captcha() /******** START : REFRESH CAPTCHA ********/
    { 
      $this->load->helper('captcha');
      echo generate_captcha('NCVET_LOGIN_CAPTCHA',6); //ncvet/ncvet_helper.php
    }  /******** END : REFRESH CAPTCHA ********/
    
    /******** START : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/
    public function validation_check_captcha($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
      if(isset($_POST) && $_POST['ncvet_captcha'] != "")
      {
        if($type == '1') { $captcha = $this->security->xss_clean($this->input->post('ncvet_captcha')); }
        else if($type == '0') { $captcha = $str; }
        
        $session_captcha = $this->session->userdata('NCVET_LOGIN_CAPTCHA');
        
        if($captcha == $session_captcha)
        {
          $return_val_ajax = 'true';
        }
      }   
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['ncvet_captcha'] != "")
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
      
      if(isset($_POST) && $_POST['ncvet_username'] != "")
      {
        if($type == '1') { $ncvet_username = $this->security->xss_clean($this->input->post('ncvet_username')); }
        else if($type == '0') { $ncvet_username = $str; }
        
        $result_data = $this->master_model->getRecords('ncvet_admin', array('admin_username' => $ncvet_username, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id, is_active, is_deleted');

        if(count($result_data) == 0) 
        {
          $result_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_code' => $ncvet_username, 'is_active' => '1', 'is_deleted' => '0'), 'agency_id, is_active, is_deleted');   
        }  
        
        if(count($result_data) > 0) { $return_val_ajax = 'true'; }
        
        if($type == '1') { echo $return_val_ajax; }
        else if($type == '0') 
        { 
          if($return_val_ajax == 'true') { return TRUE; } 
          else if($_POST['ncvet_username'] != "")
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
      if(isset($_POST) && $_POST['ncvet_username'] != "" && $_POST['ncvet_password'] != "")
      {
        $ncvet_username = $this->security->xss_clean($this->input->post('ncvet_username'));
        
        if($type == '1') { $ncvet_password = $this->security->xss_clean($this->input->post('ncvet_password')); }
        else if($type == '0') { $ncvet_password = $str; }

        $enc_password = $this->Ncvet_model->password_encryption($ncvet_password);
        
        $result_data = $this->master_model->getRecords('ncvet_admin', array('admin_username' => $ncvet_username, 'admin_password' => $enc_password, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id, is_active, is_deleted');

        if(count($result_data) == 0)
        {
          $result_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_code' => $ncvet_username, 'agency_password' => $enc_password, 'is_active' => '1', 'is_deleted' => '0'), 'agency_id, is_active, is_deleted');
        }

        if(count($result_data) > 0) { $return_val_ajax = 'true'; }
        
        if($type == '1') { echo $return_val_ajax; }
        else if($type == '0') 
        { 
          if($return_val_ajax == 'true') { return TRUE; } 
          else if($_POST['ncvet_password'] != "")
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
      $user_id = $this->session->userdata('NCVET_LOGIN_ID');
      $user_type = $this->session->userdata('NCVET_USER_TYPE');
      if(isset($user_id) && $user_id != "" && isset($user_type) && $user_type != "") { $this->Ncvet_model->insert_user_login_logs($user_id,$user_type,'2'); }
      /******** END :  USER LOGOUT LOGS CODE ********/
      
      $admin_session_data = array('NCVET_LOGIN_ID' => "", 'NCVET_USER_TYPE' => "", 'NCVET_AGENCY_CODE' => "");	 //NCVET_USER_TYPE = admin, agency, inspector
      $this->session->set_userdata($admin_session_data);
      redirect(site_url('ncvet/login'),'refresh');
    }	
  }