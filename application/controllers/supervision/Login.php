<?php 
  /********************************************************************************************************************
  ** Description: Controller for SUPERVISION Global login page (Global : Admin, PDC, Candidate) 
  ** Created BY: Priyanka Dhikale 20-may-24
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Login extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      $this->load->model('master_model');
      $this->load->model('supervision_model');
      $this->load->helper('supervision_helper');
    }
    
    public function index()
    {   
      $this->load->helper('captcha');
      $this->supervision_model->check_session_login(); // If session is already started then login page is directly redirect to dashboard
      $data['error'] = "";
      
      if(isset($_POST) && count($_POST) > 0)
      {
        $this->form_validation->set_rules('supervision_username','username','trim|required|xss_clean|callback_validation_check_username',array('required' => 'Please enter the %s'));
        $this->form_validation->set_rules('supervision_password','password','trim|required|xss_clean|callback_validation_check_login_details',array('required' => 'Please enter the %s'));	
        $this->form_validation->set_rules('supervision_captcha','code','trim|required|xss_clean|callback_validation_check_captcha',array('required' => 'Please enter the %s'));	
                
        if($this->form_validation->run())
        {
          generate_captcha('SUPERVISION_LOGIN_CAPTCHA',6);
          
          $username = $this->input->post('supervision_username');
          $password = $this->input->post('supervision_password');	
          $remember_me = $this->input->post('supervision_remember_me');

          if($remember_me == '1')
          {            
            setcookie('COOKIE_SUPERVISION_USERNAME',$username, time()+60*60*24*365,'/');
            setcookie('COOKIE_SUPERVISION_PASSWORD',$password, time()+60*60*24*365,'/');
          }
          else
          {            
            setcookie('COOKIE_SUPERVISION_USERNAME',"", time()+60*60*24*365,'/');
            setcookie('COOKIE_SUPERVISION_PASSWORD',"", time()+60*60*24*365,'/');
          }
          
          $enc_password = $this->supervision_model->password_encryption($password);

          //FIRST CHECK LOGIN DETAILS IN ADMIN TABLE
          $admin_data = $this->master_model->getRecords('supervision_admin', array('admin_username' => $username, 'admin_password' => $enc_password, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id, is_active, is_deleted,pdc_zone,admin_type');
          
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
              $this->supervision_model->insert_user_login_logs($admin_data[0]['admin_id'],'admin','1');
              /******** END :  USER LOGIN LOGS CODE ********/

              $session_data = array('SUPERVISION_LOGIN_ID' => $admin_data[0]['admin_id'],'SUPERVISION_USER_TYPE'=>'admin', 'SUPERVISION_ADMIN_TYPE' => $admin_data[0]['admin_type'],'SUPERVISION_ADMIN_PDC'=> $admin_data[0]['pdc_zone']);
              $this->session->set_userdata($session_data);			
             // echo'<pre>';print_r($session_data)			;exit;
              redirect(site_url('supervision/admin/dashboard_admin'),'refresh');						
            }
          }
          else //IF LOGIN DETAILS NOT FOUND IN ADMIN TABLE, THEN CHECK IN AGENCY TABLE
          {
            $candidate_data = $this->master_model->getRecords('supervision_candidates', array('candidate_code' => $username, 'password' => $enc_password, 'is_active' => '1', 'is_deleted' => '0'), 'id, is_active, is_deleted');

            if(count($candidate_data) > 0)
            {
              if($candidate_data[0]['is_deleted'] == '1')
              {	
                $data['error'] = "Your account is deleted. Please contact with administrator.";
              }
              else if($candidate_data[0]['is_active'] == '0')
              {	
                $data['error'] = "Your account is not active. Please contact with administrator.";
              }
              else
              {		
                /******** START :  USER LOGIN LOGS CODE ********/
                $this->supervision_model->insert_user_login_logs($candidate_data[0]['id'],'candidate','1');
                /******** END :  USER LOGIN LOGS CODE ********/

                $session_data = array('SUPERVISION_LOGIN_ID' => $candidate_data[0]['id'], 'SUPERVISION_USER_TYPE' => 'candidate');
                $this->session->set_userdata($session_data);						
                redirect(site_url('supervision/candidate/dashboard_candidate'),'refresh');						
              }
            }
                       
          }         						
        }			
      }     
      
      $data['COOKIE_SUPERVISION_USERNAME'] = $data['COOKIE_SUPERVISION_PASSWORD'] = '';      
      if(isset($_COOKIE['COOKIE_SUPERVISION_USERNAME']) && $_COOKIE['COOKIE_SUPERVISION_USERNAME'] != "") { $data['COOKIE_SUPERVISION_USERNAME'] = $_COOKIE['COOKIE_SUPERVISION_USERNAME']; }
      if(isset($_COOKIE['COOKIE_SUPERVISION_PASSWORD']) && $_COOKIE['COOKIE_SUPERVISION_PASSWORD'] != "") { $data['COOKIE_SUPERVISION_PASSWORD'] = $_COOKIE['COOKIE_SUPERVISION_PASSWORD']; }

      $data['captcha_img'] = generate_captcha('SUPERVISION_LOGIN_CAPTCHA',6); //supervision/supervision_helper.php
      $data['page_title'] = 'IIBF - Exam Supervision Login';
      $this->load->view('supervision/login',$data);
    }
    
    public function refresh_captcha() /******** START : REFRESH CAPTCHA ********/
    { 
      $this->load->helper('captcha');
      echo generate_captcha('SUPERVISION_LOGIN_CAPTCHA',6); //supervision/supervision_helper.php
    }  /******** END : REFRESH CAPTCHA ********/
    
    /******** START : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/
    public function validation_check_captcha($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
      if(isset($_POST) && $_POST['supervision_captcha'] != "")
      {
        if($type == '1') { $captcha = $this->security->xss_clean($this->input->post('supervision_captcha')); }
        else if($type == '0') { $captcha = $str; }
        
        $session_captcha = $this->session->userdata('SUPERVISION_LOGIN_CAPTCHA');
        
        if($captcha == $session_captcha)
        {
          $return_val_ajax = 'true';
        }
      }   
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['supervision_captcha'] != "")
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
      if(isset($_POST) && $_POST['supervision_username'] != "")
      {
        if($type == '1') { $supervision_username = $this->security->xss_clean($this->input->post('supervision_username')); }
        else if($type == '0') { $supervision_username = $str; }
        
        $result_data = $this->master_model->getRecords('supervision_admin', array('admin_username' => $supervision_username, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id, is_active, is_deleted');

        if(count($result_data) == 0) 
        {
          $result_data = $this->master_model->getRecords('supervision_candidates', array('candidate_code' => $supervision_username, 'is_active' => '1', 'is_deleted' => '0'), 'id, is_active, is_deleted');  
          
          
        }  

        if(count($result_data) > 0) { $return_val_ajax = 'true'; }
      
        if($type == '1') { echo $return_val_ajax; }
        else if($type == '0') 
        { 
          if($return_val_ajax == 'true') { return TRUE; } 
          else if($_POST['supervision_username'] != "")
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
      if(isset($_POST) && $_POST['supervision_username'] != "" && $_POST['supervision_password'] != "")
      {
        $supervision_username = $this->security->xss_clean($this->input->post('supervision_username'));
        
        if($type == '1') { $supervision_password = $this->security->xss_clean($this->input->post('supervision_password')); }
        else if($type == '0') { $supervision_password = $str; }

        $enc_password = $this->supervision_model->password_encryption($supervision_password);
        
        $result_data = $this->master_model->getRecords('supervision_admin', array('admin_username' => $supervision_username, 'admin_password' => $enc_password, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id, is_active, is_deleted');

       // echo $this->db->last_query();exit;

        if(count($result_data) == 0)
        {
          $result_data = $this->master_model->getRecords('supervision_candidates', array('candidate_code' => $supervision_username, 'password' => $enc_password, 'is_active' => '1', 'is_deleted' => '0'), 'id, is_active, is_deleted');

        }

        if(count($result_data) > 0) { $return_val_ajax = 'true'; }
      
        if($type == '1') { echo $return_val_ajax; }
        else if($type == '0') 
        { 
          if($return_val_ajax == 'true') { return TRUE; } 
          else if($_POST['supervision_password'] != "")
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
      $user_id = $this->session->userdata('SUPERVISION_LOGIN_ID');
      $user_type = $this->session->userdata('SUPERVISION_USER_TYPE');
      if(isset($user_id) && $user_id != "" && isset($user_type) && $user_type != "") { $this->supervision_model->insert_user_login_logs($user_id,$user_type,'2'); }
      /******** END :  USER LOGOUT LOGS CODE ********/
      
      $admin_session_data = array('SUPERVISION_LOGIN_ID' => "", 'SUPERVISION_USER_TYPE' => "");	 //SUPERVISION_USER_TYPE = admin, candidate, inspector
      $this->session->set_userdata($admin_session_data);
      redirect(site_url('supervision/login'),'refresh');
    }	
  }