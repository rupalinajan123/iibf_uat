<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF Candidate login page
  ** Created BY: Sagar Matale On 28-05-2024
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Login_candidate extends CI_Controller 
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
      $this->Iibf_bcbf_model->check_session_candidate_login(); // If session is already started then login page is directly redirect to dashboard
      $data['error'] = "";
      
      if(isset($_POST) && count($_POST) > 0)
      {
        $this->form_validation->set_rules('iibf_bcbf_candidate_registration_number','Training ID or Registration Number','trim|required|xss_clean|callback_validation_check_registration_number',array('required' => 'Please enter the %s'));
        $this->form_validation->set_rules('iibf_bcbf_candidate_email_mobile','Registered Email / Mobile','trim|required|xss_clean|callback_validation_check_email_mobile',array('required' => 'Please enter the %s'));
        $this->form_validation->set_rules('iibf_bcbf_captcha','code','trim|required|xss_clean|callback_validation_check_captcha',array('required' => 'Please enter the %s'));	
        $this->form_validation->set_rules('iibf_bcbf_enter_otp','OTP','trim|required|xss_clean|callback_validation_validate_otp',array('required' => 'Please enter the %s'));	
                
        if($this->form_validation->run())
        {
          generate_captcha('IIBF_BCBF_CANDIDATE_LOGIN_CAPTCHA',6);
          
          $iibf_bcbf_candidate_registration_number = $this->input->post('iibf_bcbf_candidate_registration_number');
          $iibf_bcbf_candidate_email_mobile = $this->input->post('iibf_bcbf_candidate_email_mobile');
          $iibf_bcbf_enter_otp = $this->input->post('iibf_bcbf_enter_otp');

          $this->db->where(' (mobile_no = "'.$iibf_bcbf_candidate_email_mobile.'" OR email_id = "'.$iibf_bcbf_candidate_email_mobile.'") ');
          $this->db->where(" (training_id = '".$iibf_bcbf_candidate_registration_number."' OR regnumber = '".$iibf_bcbf_candidate_registration_number."') ");
          $result_data = $this->master_model->getRecords('iibfbcbf_batch_candidates', array('is_deleted'=>'0', 'hold_release_status'=>'3'), 'candidate_id, regnumber, email_id, mobile_no, is_deleted', array('candidate_id'=>'DESC'),0,1);
          
          if(count($result_data) > 0)
          {
            $up_data['is_validate'] = 1;
            $up_data['updated_on']  = date("Y-m-d H:i:s");
            $this->master_model->updateRecord('iibfbcbf_candidate_login_otp', $up_data, array('candidate_id' => $result_data[0]['candidate_id'], 'regnumber' => $result_data[0]['regnumber'], 'email_id' => $result_data[0]['email_id'], 'mobile_no' => $result_data[0]['mobile_no'], 'otp_type'=>'1', 'otp'=>$iibf_bcbf_enter_otp));
            
            $this->Iibf_bcbf_model->insert_user_login_logs($result_data[0]['candidate_id'],'candidate','1');

            $session_data = array('IIBF_BCBF_CANDIDATE_LOGIN_ID' => $result_data[0]['candidate_id']);
            $this->session->set_userdata($session_data);						
            redirect(site_url('iibfbcbf/candidate/dashboard_candidate'),'refresh');
          }
          else
          {
            $data['error'] = "The Training ID or Registration Number & Email / Mobile combination does not exist. Please enter correct details.";
          }        						
        }			
      }
      
      $data['captcha_img'] = generate_captcha('IIBF_BCBF_CANDIDATE_LOGIN_CAPTCHA',6); //iibfbcbf/iibf_bcbf_helper.php
      $data['page_title'] = 'IIBF - BCBF Candidate Login';
      $this->load->view('iibfbcbf/candidate/login_candidate',$data);
    }

    function direct_login($candidate_id=0)
    {
      $candidate_session_data = array('IIBF_BCBF_CANDIDATE_LOGIN_ID' => $candidate_id);
      $this->session->set_userdata($candidate_session_data);
      redirect(site_url('iibfbcbf/candidate/dashboard_candidate'));
    }
    
    public function refresh_captcha() /******** START : REFRESH CAPTCHA ********/
    { 
      $this->load->helper('captcha');
      echo generate_captcha('IIBF_BCBF_CANDIDATE_LOGIN_CAPTCHA',6); //iibfbcbf/iibf_bcbf_helper.php
    }  /******** END : REFRESH CAPTCHA ********/
    
    /******** START : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/
    public function validation_check_captcha($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
      if(isset($_POST) && $_POST['iibf_bcbf_captcha'] != "")
      {
        if($type == '1') { $captcha = $this->security->xss_clean($this->input->post('iibf_bcbf_captcha')); }
        else if($type == '0') { $captcha = $str; }
        
        $session_captcha = $this->session->userdata('IIBF_BCBF_CANDIDATE_LOGIN_CAPTCHA');
        
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

    /******** START : VALIDATION FUNCTION TO CHECK THE CORRECT REGISTRATION NUMBER ********/
    public function validation_check_registration_number($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
      if(isset($_POST) && $_POST['iibf_bcbf_candidate_registration_number'] != "")
      {
        if($type == '1') { $iibf_bcbf_candidate_registration_number = $this->security->xss_clean($this->input->post('iibf_bcbf_candidate_registration_number')); }
        else if($type == '0') { $iibf_bcbf_candidate_registration_number = $str; }
        
        $this->db->where(" (training_id = '".$iibf_bcbf_candidate_registration_number."' OR regnumber = '".$iibf_bcbf_candidate_registration_number."') ");
        $result_data = $this->master_model->getRecords('iibfbcbf_batch_candidates', array('is_deleted'=>'0', 'hold_release_status'=>'3'), 'candidate_id', array('candidate_id'=>'DESC'),0,1);
        
        if(count($result_data) > 0) { $return_val_ajax = 'true'; }
      
        if($type == '1') { echo $return_val_ajax; }
        else if($type == '0') 
        { 
          if($return_val_ajax == 'true') { return TRUE; } 
          else if($_POST['iibf_bcbf_candidate_registration_number'] != "")
          {
            $this->form_validation->set_message('validation_check_registration_number','Please enter the valid Training ID or Registration Number');
            return false;
          }
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK THE REGISTRATION NUMBER ********/

    /******** START : VALIDATION FUNCTION TO CHECK THE CORRECT MOBILE / EMAIL ********/
    public function validation_check_email_mobile($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
      if(isset($_POST) && $_POST['iibf_bcbf_candidate_email_mobile'] != "")
      {
        if($type == '1') { $iibf_bcbf_candidate_email_mobile = $this->security->xss_clean($this->input->post('iibf_bcbf_candidate_email_mobile')); }
        else if($type == '0') { $iibf_bcbf_candidate_email_mobile = $str; }
        
        $this->db->where(' (mobile_no = "'.$iibf_bcbf_candidate_email_mobile.'" OR email_id = "'.$iibf_bcbf_candidate_email_mobile.'") ');
        $result_data = $this->master_model->getRecords('iibfbcbf_batch_candidates', array('is_deleted'=>'0', 'hold_release_status'=>'3'), 'candidate_id', array('candidate_id'=>'DESC'),0,1);
        
        if(count($result_data) > 0) { $return_val_ajax = 'true'; }
      
        if($type == '1') { echo $return_val_ajax; }
        else if($type == '0') 
        { 
          if($return_val_ajax == 'true') { return TRUE; } 
          else if($_POST['iibf_bcbf_candidate_email_mobile'] != "")
          {
            $this->form_validation->set_message('validation_check_email_mobile','Please enter the valid Registered Email / Mobile');
            return false;
          }
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK THE CORRECT MOBILE / EMAIL ********/
    
    /******** START : LOGOUT FUNCTION ********/
    public function logout()
    {
      /******** START : CANDIDATE LOGOUT LOGS CODE ********/
      $candidate_id = $this->session->userdata('IIBF_BCBF_CANDIDATE_LOGIN_ID');
      if(isset($candidate_id) && $candidate_id != "") { $this->Iibf_bcbf_model->insert_user_login_logs($candidate_id,'candidate','2'); }
      /******** END :  CANDIDATE LOGOUT LOGS CODE ********/

      if ($this->session->flashdata('error')) { $this->session->set_flashdata('error', $this->session->flashdata('error')); }
      
      $candidate_session_data = array('IIBF_BCBF_CANDIDATE_LOGIN_ID' => "");
      $this->session->set_userdata($candidate_session_data);
      redirect(site_url('iibfbcbf/candidate/login_candidate'),'refresh');
    }/******** END : LOGOUT FUNCTION ********/

    /******** START : SEND / RESEND OTP FUNCTION ********/
    function send_resend_otp()
    {
      $result['flag'] = "error";
	    
      if(isset($_POST) && count($_POST) > 0)
      {
        $current_form_action = $this->security->xss_clean($this->input->post('current_form_action'));
        $iibf_bcbf_candidate_registration_number = $this->security->xss_clean($this->input->post('iibf_bcbf_candidate_registration_number'));
        $iibf_bcbf_candidate_email_mobile = $this->security->xss_clean($this->input->post('iibf_bcbf_candidate_email_mobile'));

        $this->db->where(' (mobile_no = "'.$iibf_bcbf_candidate_email_mobile.'" OR email_id = "'.$iibf_bcbf_candidate_email_mobile.'") ');
        $this->db->where(" (training_id = '".$iibf_bcbf_candidate_registration_number."' OR regnumber = '".$iibf_bcbf_candidate_registration_number."') ");
        $result_data = $this->master_model->getRecords('iibfbcbf_batch_candidates', array('is_deleted'=>'0', 'hold_release_status'=>'3'), 'candidate_id, training_id, regnumber, salutation, first_name, middle_name, last_name, mobile_no, email_id, is_deleted', array('candidate_id'=>'DESC'),0,1);
          
        if(count($result_data) > 0)
        {
          //START : GET PREVIOUS OTP TIME AND COMPARE IT WITH CURRENT TIME. iF THE DIFFERENCE IS LESS THAN 60 SEC, SHOW ERROR MESSAGE TO PREVENT MULTIPLE REQUESTS
          $last_otp_data = $this->master_model->getRecords('iibfbcbf_candidate_login_otp', array('candidate_id'=> $result_data[0]['candidate_id'], 'training_id'=> $result_data[0]['training_id'], 'regnumber'=> $result_data[0]['regnumber'], 'email_id'=> $result_data[0]['email_id'], 'mobile_no'=> $result_data[0]['mobile_no'], 'otp_type'=>'1', 'is_validate'=>'0'), 'created_on', array('otp_id'=>'DESC'),0,1);
          
          $time_after_1min = date('Y-m-d H:i:s', strtotime("+55sec", strtotime($last_otp_data[0]['created_on'])));
          if(count($last_otp_data) > 0 && $time_after_1min > date('Y-m-d H:i:s'))
          {
            $result['flag'] = "error";
            $result['response_msg'] = 'Duplicate request found. Wait for 1 min.';
          }//END : GET PREVIOUS OTP TIME AND COMPARE IT WITH CURRENT TIME. iF THE DIFFERENCE IS LESS THAN 60 SEC, SHOW ERROR MESSAGE TO PREVENT MULTIPLE REQUESTS
          else
          {
            $otp_send_fun = $this->fun_send_otp_sms($result_data);
            if($otp_send_fun)
            {
              $result['flag'] = "success";
              $result['mask_email'] = $mask_email = $this->Iibf_bcbf_model->mask_email_mobile('email', $result_data[0]['email_id']);
              $result['mask_mobile'] = $mask_mobile = $this->Iibf_bcbf_model->mask_email_mobile('mobile', $result_data[0]['mobile_no']);
              $result['resend_time_sec'] = $this->check_time(date('Y-m-d H:i:s'));
              $result['response_msg'] = '<b>OTP successfully sent on '.$mask_mobile.' & '.$mask_email.'.<br>The OTP is valid for 10 minutes.<b>';
            }          
          }          
        }
        else
        {
          $result['flag'] = "error";
          $result['response_msg'] = '<b>The Training ID or Registration Number & Email / Mobile combination does not exist. Please enter correct details.<b>';
        }
      }      
      echo json_encode($result);
    }/******** END : SEND / RESEND OTP FUNCTION ********/

    public function validation_validate_otp($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
    {
      //$return_val_ajax = 'false';
      $result['flag'] = $flag = "error";
      $response = 'Please enter the correct OTP';
      if (isset($_POST) && $_POST['iibf_bcbf_candidate_registration_number'] != "" && $_POST['iibf_bcbf_candidate_email_mobile'] != "" && $_POST['iibf_bcbf_enter_otp'] != "")
      {
        if ($type == '1')
        {
          $iibf_bcbf_enter_otp = $this->input->post('iibf_bcbf_enter_otp');
        }
        else if ($type == '0')
        {
          $iibf_bcbf_enter_otp = $str;
        }

        $iibf_bcbf_candidate_registration_number = $this->input->post('iibf_bcbf_candidate_registration_number');
        $iibf_bcbf_candidate_email_mobile = $this->input->post('iibf_bcbf_candidate_email_mobile');

        $this->db->where(' (mobile_no = "'.$iibf_bcbf_candidate_email_mobile.'" OR email_id = "'.$iibf_bcbf_candidate_email_mobile.'") ');
        $this->db->where(" (training_id = '".$iibf_bcbf_candidate_registration_number."' OR regnumber = '".$iibf_bcbf_candidate_registration_number."') ");
        $result_data = $this->master_model->getRecords('iibfbcbf_batch_candidates', array('is_deleted'=>'0', 'hold_release_status'=>'3'), 'candidate_id, training_id, regnumber, salutation, first_name, middle_name, last_name, mobile_no, email_id, is_deleted', array('candidate_id'=>'DESC'),0,1);
        
        $otp_data = $this->master_model->getRecords('iibfbcbf_candidate_login_otp', array('candidate_id' => $result_data[0]['candidate_id'], 'regnumber' => $result_data[0]['regnumber'], 'email_id' => $result_data[0]['email_id'], 'mobile_no' => $result_data[0]['mobile_no'], 'otp_type'=>'1'), 'otp_id, otp, is_validate, otp_expired_on', array('otp_id' => 'DESC'), '', 1);
       
        if (count($otp_data) > 0 && $otp_data[0]['is_validate'] == '0')
        {
          if ($otp_data[0]['otp'] != $iibf_bcbf_enter_otp)
          {
            $result['response'] = $response = "Please enter the correct OTP.";
          }
          else if ($otp_data[0]['otp_expired_on'] < date("Y-m-d H:i:s"))
          {
            $result['response'] = $response = "The OTP has already expired.";
          }
          else
          {
            $result['flag'] = $flag = "success";
          }
        }
        else
        {
          $result['response'] = $response = "Please enter the correct OTP.";
        }
      }

      if ($type == '1')
      {/*  echo $return_val_ajax; */
        echo json_encode($result);
      }
      else if ($type == '0')
      {
        if ($flag == 'success')
        {
          return TRUE;
        }
        else
        {
          $this->form_validation->set_message('validation_validate_otp', $response);
          return false;
        }
      }
    }

    /******** START : SEND OTP ON EMAIL & SMS FUNCTION ********/
    function fun_send_otp_sms($member_data = array())
    {
      if (count($member_data) > 0)
      {
        $candidate_name = $member_data[0]['salutation'].' '.$member_data[0]['first_name'];
        if($member_data[0]['middle_name'] != '') { $candidate_name .= ' '.$member_data[0]['middle_name']; }
        if($member_data[0]['last_name'] != '') { $candidate_name .= ' '.$member_data[0]['last_name']; }
        
        $otp = $this->generate_otp();
        $email_id = $member_data[0]['email_id'];
        $mobile_no = $member_data[0]['mobile_no'];
        $otp_sent_on = date('Y-m-d H:i:s');
        $otp_expired_on = date('Y-m-d H:i:s', strtotime('+ 10 minutes', strtotime($otp_sent_on)));

        $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'member_login_with_otp'));
        
        $email_text = $emailerstr[0]['emailer_text'];
        $email_text = str_replace('#CANDIDATENAME#', $candidate_name, $email_text);
        $email_text = str_replace('#OTP#', $otp, $email_text);

        $sms_text = $emailerstr[0]['sms_text'];
        $sms_text = str_replace('#OTP#', $otp, $sms_text);

        $otp_mail_arr['subject'] = $emailerstr[0]['subject'];
        $otp_mail_arr['mail_content'] = $email_text;
        $otp_mail_arr['to_email'] = $email_id;
        $otp_mail_arr['to_name'] = $candidate_name; 
        //$otp_mail_arr['view_flag'] = '1'; 
        $otp_mail_arr['is_header_footer_required'] = '1'; 
        $email_response = $this->Iibf_bcbf_model->iibfbcbf_send_mail_common($otp_mail_arr);

        $sms_response = $this->master_model->send_sms_common_all($mobile_no, $sms_text, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']);

        if ($email_response)
        {
          $add_data['candidate_id'] = $member_data[0]['candidate_id'];
          $add_data['training_id'] = $member_data[0]['training_id'];
          $add_data['regnumber'] = $member_data[0]['regnumber'];
          $add_data['email_id'] = $email_id;
          $add_data['mobile_no'] = $mobile_no;
          $add_data['otp'] = $otp;
          $add_data['is_validate'] = '0';
          $add_data['otp_type'] = '1';
          $add_data['otp_expired_on'] = $otp_expired_on;
          $add_data['created_on'] = $otp_sent_on;
          $this->db->insert('iibfbcbf_candidate_login_otp', $add_data);

          return true;
        }
        else
        {
          return false;
        }
      }
      else
      {
        return false;
      }
    }/******** END : SEND OTP ON EMAIL & SMS FUNCTION ********/

    /******** START : GENERATE OTP FUNCTION ********/
    public function generate_otp()
    {
      //return '123456';
      return rand(100000, 999999);
    }/******** END : GENERATE OTP FUNCTION ********/

    /******** START : SHOW RESEND TIMER FUNCTION ********/
    function check_time($time)
    {
      $otptime = 60; //in sec
      $timeMobileFirst  = strtotime($time);
      $currentTimeInSec = strtotime(date('Y-m-d H:i:s'));

      $remainingTimeMobile = 0;
      if ($timeMobileFirst <= $currentTimeInSec)
      {
        $diffTimeMobile =  $currentTimeInSec - $timeMobileFirst;

        if ($diffTimeMobile < $otptime)
        {
          $remainingTimeMobile = $otptime - $diffTimeMobile;
        }
      }
      return $remainingTimeMobile;
    } /******** END : SHOW RESEND TIMER FUNCTION ********/
  }