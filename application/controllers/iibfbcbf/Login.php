<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF Global login page (Global : Admin, Agency, Centre, Inspector) 
  ** Created BY: Sagar Matale On 11-10-2023
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Login extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper');
    }
    
    public function index()
    {   
      $this->load->helper('captcha');
      $this->Iibf_bcbf_model->check_session_login(); // If session is already started then login page is directly redirect to dashboard
      $data['error'] = "";
      
      if(isset($_POST) && count($_POST) > 0)
      {
        $this->form_validation->set_rules('iibf_bcbf_username','username','trim|required|xss_clean|callback_validation_check_username',array('required' => 'Please enter the %s'));
        $this->form_validation->set_rules('iibf_bcbf_password','password','trim|required|xss_clean|callback_validation_check_login_details',array('required' => 'Please enter the %s'));	
        $this->form_validation->set_rules('iibf_bcbf_captcha','code','trim|required|xss_clean|callback_validation_check_captcha',array('required' => 'Please enter the %s'));	
                
        if($this->form_validation->run())
        {
          generate_captcha('IIBF_BCBF_LOGIN_CAPTCHA',6);
          
          $username = $this->input->post('iibf_bcbf_username');
          $password = $this->input->post('iibf_bcbf_password');	
          $remember_me = $this->input->post('iibfbcbf_remember_me');

          if($remember_me == '1')
          {            
            setcookie('COOKIE_IIBF_BCBF_USERNAME',$username, time()+60*60*24*365,'/');
            setcookie('COOKIE_IIBF_BCBF_PASSWORD',$password, time()+60*60*24*365,'/');
          }
          else
          {            
            setcookie('COOKIE_IIBF_BCBF_USERNAME',"", time()+60*60*24*365,'/');
            setcookie('COOKIE_IIBF_BCBF_PASSWORD',"", time()+60*60*24*365,'/');
          }
          
          $enc_password = $this->Iibf_bcbf_model->password_encryption($password);

          //FIRST CHECK LOGIN DETAILS IN ADMIN TABLE
          $admin_data = $this->master_model->getRecords('iibfbcbf_admin', array('admin_username' => $username, 'admin_password' => $enc_password, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id, is_active, is_deleted');
          
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
              $this->Iibf_bcbf_model->insert_user_login_logs($admin_data[0]['admin_id'],'admin','1');
              /******** END :  USER LOGIN LOGS CODE ********/

              $session_data = array('IIBF_BCBF_LOGIN_ID' => $admin_data[0]['admin_id'], 'IIBF_BCBF_USER_TYPE' => 'admin');
              $this->session->set_userdata($session_data);						
              redirect(site_url('iibfbcbf/admin/dashboard_admin'),'refresh');						
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
                $this->Iibf_bcbf_model->insert_user_login_logs($agency_data[0]['agency_id'],'agency','1');
                /******** END :  USER LOGIN LOGS CODE ********/

                $session_data = array('IIBF_BCBF_LOGIN_ID' => $agency_data[0]['agency_id'], 'IIBF_BCBF_USER_TYPE' => 'agency', 'IIBF_BCBF_AGENCY_CODE'=>$agency_data[0]['agency_code']);
                $this->session->set_userdata($session_data);						
                redirect(site_url('iibfbcbf/agency/dashboard_agency'),'refresh');						
              }
            }
            else //IF LOGIN DETAILS NOT FOUND IN AGENCY TABLE, THEN CHECK IN CENTER TABLE
            {
              $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'INNER');
              $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_username' => $username, 'cm.centre_password' => $enc_password, 'cm.status' => '1', 'cm.is_deleted' => '0', 'am.allow_exam_types !=' => 'CSC'), 'cm.centre_id, cm.status, cm.is_deleted, am.is_active as AgencyStatus, am.is_deleted AgencyIsDeleted, am.allow_exam_types, am.agency_code');
              //echo $this->db->last_query(); exit;

              if(count($centre_data) > 0)
              {
                if($centre_data[0]['is_deleted'] == '1')
                {	
                  $data['error'] = "Your account is deleted. Please contact with administrator.";
                }
                else if($centre_data[0]['status'] == '0')
                {	
                  $data['error'] = "Your account is not active. Please contact with administrator.";
                }
                else if($centre_data[0]['AgencyStatus'] == '0')
                {	
                  $data['error'] = "The associated agency account is not active. Please contact with administrator.";
                }
                else if($centre_data[0]['AgencyIsDeleted'] == '1')
                {	
                  $data['error'] = "The associated agency account is deleted. Please contact with administrator.";
                }
                else
                {		
                  /******** START :  USER LOGIN LOGS CODE ********/
                  $this->Iibf_bcbf_model->insert_user_login_logs($centre_data[0]['centre_id'],'centre','1');
                  /******** END :  USER LOGIN LOGS CODE ********/

                  $session_data = array('IIBF_BCBF_LOGIN_ID' => $centre_data[0]['centre_id'], 'IIBF_BCBF_USER_TYPE' => 'centre', 'IIBF_BCBF_AGENCY_CODE'=>$centre_data[0]['agency_code']);
                  $this->session->set_userdata($session_data);						
                  redirect(site_url('iibfbcbf/agency/dashboard_agency'),'refresh');						
                }
              }
              else //IF LOGIN DETAILS NOT FOUND IN CENTER TABLE, THEN CHECK IN INSPECTOR TABLE
              {
                $inspector_data = $this->master_model->getRecords('iibfbcbf_inspector_master im', array('im.inspector_username' => $username, 'im.inspector_password' => $enc_password, 'im.is_active' => '1', 'im.is_deleted' => '0'), 'im.inspector_id, im.inspector_username');//check in inspector table

                if(count($inspector_data) > 0)
                {
                  if($inspector_data[0]['is_deleted'] == '1')
                  {	
                    $data['error'] = "Your account is deleted. Please contact with administrator.";
                  }
                  else if($inspector_data[0]['is_active'] == '0')
                  {	
                    $data['error'] = "Your account is not active. Please contact with administrator.";
                  }
                  else
                  {		
                    /******** START :  USER LOGIN LOGS CODE ********/
                    $this->Iibf_bcbf_model->insert_user_login_logs($inspector_data[0]['inspector_id'],'inspector','1');
                    /******** END :  USER LOGIN LOGS CODE ********/

                    $session_data = array('IIBF_BCBF_LOGIN_ID' => $inspector_data[0]['inspector_id'], 'IIBF_BCBF_USER_TYPE' => 'inspector');
                    $this->session->set_userdata($session_data);						
                    redirect(site_url('iibfbcbf/inspector/dashboard_inspector'),'refresh');						
                  }
                }
                else //IF LOGIN DETAILS NOT FOUND IN CENTER TABLE, THEN CHECK IN INSPECTOR TABLE
                {
                  $data['error'] = "Enter valid login details";
                }
              }            
            }            
          }         						
        }			
      }     
      
      $data['COOKIE_IIBF_BCBF_USERNAME'] = $data['COOKIE_IIBF_BCBF_PASSWORD'] = '';      
      if(isset($_COOKIE['COOKIE_IIBF_BCBF_USERNAME']) && $_COOKIE['COOKIE_IIBF_BCBF_USERNAME'] != "") { $data['COOKIE_IIBF_BCBF_USERNAME'] = $_COOKIE['COOKIE_IIBF_BCBF_USERNAME']; }
      if(isset($_COOKIE['COOKIE_IIBF_BCBF_PASSWORD']) && $_COOKIE['COOKIE_IIBF_BCBF_PASSWORD'] != "") { $data['COOKIE_IIBF_BCBF_PASSWORD'] = $_COOKIE['COOKIE_IIBF_BCBF_PASSWORD']; }

      $data['captcha_img'] = generate_captcha('IIBF_BCBF_LOGIN_CAPTCHA',6); //iibfbcbf/iibf_bcbf_helper.php
      $data['page_title'] = 'IIBF - BCBF Login';
      $this->load->view('iibfbcbf/login',$data);
    }
    
    public function refresh_captcha() /******** START : REFRESH CAPTCHA ********/
    { 
      $this->load->helper('captcha');
      echo generate_captcha('IIBF_BCBF_LOGIN_CAPTCHA',6); //iibfbcbf/iibf_bcbf_helper.php
    }  /******** END : REFRESH CAPTCHA ********/
    
    /******** START : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/
    public function validation_check_captcha($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
      if(isset($_POST) && $_POST['iibf_bcbf_captcha'] != "")
      {
        if($type == '1') { $captcha = $this->security->xss_clean($this->input->post('iibf_bcbf_captcha')); }
        else if($type == '0') { $captcha = $str; }
        
        $session_captcha = $this->session->userdata('IIBF_BCBF_LOGIN_CAPTCHA');
        
        if($captcha == $session_captcha)
        {
          $return_val_ajax = 'true';
        }
      }   
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['iibf_bcbf_captcha'] != "")
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
      if(isset($_POST) && $_POST['iibf_bcbf_username'] != "")
      {
        if($type == '1') { $iibf_bcbf_username = $this->security->xss_clean($this->input->post('iibf_bcbf_username')); }
        else if($type == '0') { $iibf_bcbf_username = $str; }
        
        $result_data = $this->master_model->getRecords('iibfbcbf_admin', array('admin_username' => $iibf_bcbf_username, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id, is_active, is_deleted');

        if(count($result_data) == 0) 
        {
          $result_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_code' => $iibf_bcbf_username, 'is_active' => '1', 'is_deleted' => '0'), 'agency_id, is_active, is_deleted');  
          
          if(count($result_data) == 0) 
          {
            $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'INNER');
            $result_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_username' => $iibf_bcbf_username, 'cm.status' => '1', 'cm.is_deleted' => '0', 'am.allow_exam_types !=' => 'CSC'), 'cm.centre_id, cm.status, cm.is_deleted');      
            
            if(count($result_data) == 0) 
            {
              $result_data = $this->master_model->getRecords('iibfbcbf_inspector_master', array('inspector_username' => $iibf_bcbf_username, 'is_active' => '1', 'is_deleted' => '0'), 'inspector_id, is_active, is_deleted');          
            }  
          }  
        }  

        if(count($result_data) > 0) { $return_val_ajax = 'true'; }
      
        if($type == '1') { echo $return_val_ajax; }
        else if($type == '0') 
        { 
          if($return_val_ajax == 'true') { return TRUE; } 
          else if($_POST['iibf_bcbf_username'] != "")
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
      if(isset($_POST) && $_POST['iibf_bcbf_username'] != "" && $_POST['iibf_bcbf_password'] != "")
      {
        $iibf_bcbf_username = $this->security->xss_clean($this->input->post('iibf_bcbf_username'));
        
        if($type == '1') { $iibf_bcbf_password = $this->security->xss_clean($this->input->post('iibf_bcbf_password')); }
        else if($type == '0') { $iibf_bcbf_password = $str; }

        $enc_password = $this->Iibf_bcbf_model->password_encryption($iibf_bcbf_password);
        
        $result_data = $this->master_model->getRecords('iibfbcbf_admin', array('admin_username' => $iibf_bcbf_username, 'admin_password' => $enc_password, 'is_active' => '1', 'is_deleted' => '0'), 'admin_id, is_active, is_deleted');

        if(count($result_data) == 0)
        {
          $result_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_code' => $iibf_bcbf_username, 'agency_password' => $enc_password, 'is_active' => '1', 'is_deleted' => '0'), 'agency_id, is_active, is_deleted');

          if(count($result_data) == 0)
          {
            $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'INNER');
            $result_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_username' => $iibf_bcbf_username, 'cm.centre_password' => $enc_password, 'cm.status' => '1', 'cm.is_deleted' => '0', 'am.allow_exam_types !=' => 'CSC'), 'cm.centre_id, cm.status, cm.is_deleted');

            if(count($result_data) == 0)
            {
              $result_data = $this->master_model->getRecords('iibfbcbf_inspector_master', array('inspector_username' => $iibf_bcbf_username, 'inspector_password' => $enc_password, 'is_active' => '1', 'is_deleted' => '0'), 'inspector_id, is_active, is_deleted');
            }
          }
        }

        if(count($result_data) > 0) { $return_val_ajax = 'true'; }
      
        if($type == '1') { echo $return_val_ajax; }
        else if($type == '0') 
        { 
          if($return_val_ajax == 'true') { return TRUE; } 
          else if($_POST['iibf_bcbf_password'] != "")
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
      $user_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');
      if(isset($user_id) && $user_id != "" && isset($user_type) && $user_type != "") { $this->Iibf_bcbf_model->insert_user_login_logs($user_id,$user_type,'2'); }
      /******** END :  USER LOGOUT LOGS CODE ********/
      
      $admin_session_data = array('IIBF_BCBF_LOGIN_ID' => "", 'IIBF_BCBF_USER_TYPE' => "", 'IIBF_BCBF_AGENCY_CODE' => "");	 //IIBF_BCBF_USER_TYPE = admin, agency, inspector
      $this->session->set_userdata($admin_session_data);
      redirect(site_url('iibfbcbf/login'),'refresh');
    }	
  }