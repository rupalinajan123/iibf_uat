<?php

/********************************************************************************************************************
 ** Description: Controller for APABI INDIA REGISTRATION
 ** Created BY: Sagar Matale On 30-09-2024
 ********************************************************************************************************************/
defined('BASEPATH') or exit('No direct script access allowed');

class Apabi_india_registration extends CI_Controller
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
    $data['page_title'] = 'APABI INDIA Registration - 2024';
    $data['photo_error'] = '';

    if (isset($_POST) && count($_POST) > 0)
    {
      $this->form_validation->set_rules('salutation', 'salutation', 'trim|required|xss_clean', array('required' => "Please select the %s"));
      $this->form_validation->set_rules('name', 'name', 'trim|required|max_length[100]|xss_clean', array('required' => "Please enter the %s"));
      $this->form_validation->set_rules('designation', 'designation', 'trim|required|max_length[100]|xss_clean', array('required' => "Please enter the %s"));      
      $this->form_validation->set_rules('org_id', 'organization', 'trim|required|xss_clean', array('required' => "Please select the %s"));
      $this->form_validation->set_rules('email', 'email id', 'trim|required|max_length[90]|valid_email|callback_validation_check_email_exist|xss_clean', array('required' => "Please enter the %s"));
      $this->form_validation->set_rules('mobile', 'mobile number', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|min_length[10]|max_length[10]|callback_validation_check_mobile_exist|callback_fun_restrict_input[first_zero_not_allowed]|xss_clean', array('required' => "Please enter the %s"));
      $this->form_validation->set_rules('apabi_india_registration_captcha', 'code', 'trim|required|xss_clean|callback_validation_check_captcha', array('required' => 'Please enter the %s'));
      //$this->form_validation->set_rules('xxx', 'xxx', 'trim|required|xss_clean', array('required' => "Please select the %s"));
      
      if($this->form_validation->run())
      {
        $posted_arr = json_encode($_POST);
          
        $add_data['salutation'] = $salutation = $this->input->post('salutation');
        $add_data['name'] = $name = $this->input->post('name');
        $add_data['designation'] = $this->input->post('designation');
        $add_data['org_id'] = $this->input->post('org_id');
        $add_data['email'] = $to_email = $this->input->post('email');
        $add_data['mobile'] = $this->input->post('mobile');
        
        $add_data['ip_address'] = get_ip_address(); //general_helper.php 
        $add_data['created_on'] = date("Y-m-d H:i:s");
        $apabi_id =  $this->master_model->insertRecord('apabi_india_registrations', $add_data, true);
          
        if($apabi_id > 0)
        {
          $this->Apabi_india_model->insert_common_log('APABI INDIA Registration', 'apabi_india_registrations', $this->db->last_query(), $apabi_id,'apabi_india_register_action','The registration has successfully done.', $posted_arr); 

          $total_record_qry = $this->db->query('SELECT am.apabi_id FROM apabi_india_registrations am WHERE am.apabi_id <= "' . $apabi_id . '"');
          $get_total_record = $total_record_qry->num_rows();
            
          $up_data = array();
          $up_data['apabi_india_code'] = $apabi_india_code = 'APABI_INDIA_'.date('y').'_'.sprintf('%02d',$get_total_record);
          $this->master_model->updateRecord('apabi_india_registrations', $up_data, array('apabi_id' => $apabi_id));

          $this->Apabi_india_model->insert_common_log('APABI INDIA Registration - Update', 'apabi_india_registrations', $this->db->last_query(), $apabi_id,'apabi_india_register_action','The APABI code has successfully updated - '.$apabi_india_code, $posted_arr); 

          //START : SEND ACKNOWLEDGEMENT EMAIL
          $mail_arg = array();
          $mail_arg['subject'] = 'APABI INDIA Acknowledgement Email';
          $mail_arg['to_email'] = $to_email; //'sagar.matale@esds.co.in'; 
          $mail_arg['to_name'] = $salutation.''.$name;//'sagar'; 
          $mail_arg['cc_email'] = '';//sagar.matale@esds.co.in,anil.s@esds.co.in
          $mail_arg['bcc_email'] = 'anil.s@esds.co.in,sagar.matale@esds.co.in';//sagar.matale@esds.co.in,anil.s@esds.co.in
          $mail_arg['is_header_footer_required'] = '1';
          $mail_arg['view_flag'] = '0';
          $link = site_url('apabi_india_registrations/travel_details_registrations/'.base64_encode($apabi_id).'/'.base64_encode($apabi_code));
          $mail_content = '
            <style type="text/css">            
              p { padding: 0; margin: 0; font-weight: 500; font-size:14px; line-height:24px; }
              p.footer_regards { line-height: 20px; }
              table.inner_tbl { font-size: 14px; border-collapse: collapse; width: 100%; color:#000; margin:0 0 10px 0; }
              table.inner_tbl thead tr th { padding: 5px 10px; border-collapse: collapse; border: 1px solid #776f6f; line-height:20px; vertical-align:top; background-color:#eee; font-weight: 500; }                          
              table.inner_tbl tbody tr td { padding: 5px 10px; border-collapse: collapse; border: 1px solid #776f6f; line-height:20px; vertical-align:top; font-weight: 500; }                          
            </style>

            <p style="margin-bottom:10px;">Dear Sir/Madam,</p>
            
            <p>We acknowledge your registration to participate in the upcoming Asian-Pacific Association of Banking Institutes (APABI) Conference 2024, scheduled on 14th November 2024 (Thursday).</p>
            <p>For more details about the Conference, kindly visit <a href="https://iibfglobal.org/apabi-2024">https://iibfglobal.org/apabi-2024</a></p>
            
            <p style="margin:10px 0 10px 0;">Please feel free to contact the below mentioned officials for any clarification, if required</p>

            <table class="inner_tbl">
              <thead>
                <tr>
                  <th style="width:120px;">NAME</th>
                  <th>DESIGNATION</th>
                  <th style="width:120px;">E-MAIL ID</th>
                  <th>MOBILE NUMBER</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="white-space: nowrap;">Ms. P Soumya</td>
                  <td>Joint Director (Academics), IIBF</td>
                  <td style="white-space: nowrap;">jd.aca1@iibf.org.in</td>
                  <td>+91-7738543328<br>(Ms. P Soumya)</td>
                </tr>
                <tr>
                  <td style="white-space: nowrap;">Mr. Govind Churi</td>
                  <td>Assistant Director & Secretary to CEO, IIBF</td>
                  <td style="white-space: nowrap;">ceosec@iibf.org.in</td>
                  <td>+91-9833101603<br>(Mr. Govind Churi)</td>
                </tr>
              </tbody>
            </table>
            
            <p style="margin-top: 22px; margin-bottom: 0px; line-height: 20px;">Regards,<br>Team IIBF</p>';
              
            $mail_arg['mail_content'] = $mail_content;
            $this->Iibf_bcbf_model->iibfbcbf_send_mail_common($mail_arg);
            //END : SEND ACKNOWLEDGEMENT EMAIL
    
            $this->session->set_flashdata('success', 'Thank you for registering in the Asian-Pacific Association of Banking Institutes (APABI) Conference 2024.<br><br>We look forward to meeting you at the Conference.');
        }
        else
        {
          $this->Apabi_india_model->insert_common_log('APABI INDIA Registration', 'apabi_india_registrations', $this->db->last_query(), $apabi_id,'apabi_register_action','Erro occurred for registration.', $posted_arr);

          $this->session->set_flashdata('error', 'Error occurred. Please try again.');
        }
        redirect(site_url('apabi_india_registration/thank_you'), 'refresh');
      }
    }

    $data['organization_master_data'] = $this->master_model->getRecords('apabi_organization_master', array('is_delete' => '0'), 'id, org_id, org_name, org_code', array('org_name' => 'ASC'));

    $this->load->helper('captcha');
    $data['captcha_img'] = generate_captcha('APABI_INDIA_REGISTRATION_CAPTCHA', 6); //global_helper.php
    $this->load->view('apabi_india/apabi_india_registration', $data);
  }

  public function thank_you()
  {
    $data['page_title'] = 'APABI INDIA 2024 - Thank You';
    $type = $message = '';
    if($this->session->flashdata('success'))
    {
      $type = 'success';
      $message = $this->session->flashdata('success');
    }
    else if($this->session->flashdata('error'))
    {
      $type = 'error';
      $message = $this->session->flashdata('error');
    }

    $data = array();
    $data['type'] = $type;
    $data['message'] = $message;

    if($message == '') { redirect(site_url('apabi_india_registration')); }
    $this->load->view('apabi_india/thank_you', $data);
  }

  public function refresh_captcha()/******** START : REFRESH CAPTCHA ********/  
  {
    $this->load->helper('captcha');
    echo generate_captcha('APABI_INDIA_REGISTRATION_CAPTCHA', 6); //global_helper.php
  }/******** END : REFRESH CAPTCHA ********/

  /******** START : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/
  public function validation_check_captcha($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    if (isset($_POST) && $_POST['apabi_india_registration_captcha'] != "")
    {
      if ($type == '1')
      {
        $captcha = $this->security->xss_clean($this->input->post('apabi_india_registration_captcha'));
      }
      else if ($type == '0')
      {
        $captcha = $str;
      }

      $session_captcha = $this->session->userdata('APABI_INDIA_REGISTRATION_CAPTCHA');

      if ($captcha == $session_captcha)
      {
        $return_val_ajax = 'true';
      }
    }

    if ($type == '1')
    {
      echo $return_val_ajax;
    }
    else if ($type == '0')
    {
      if ($return_val_ajax == 'true')
      {
        return TRUE;
      }
      else if ($_POST['apabi_india_registration_captcha'] != "")
      {
        $this->form_validation->set_message('validation_check_captcha', 'Please enter the valid code');
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/

  
  /******** START : VALIDATION FUNCTION TO CHECK MOBILE EXIST OR NOT ********/
  public function validation_check_mobile_exist($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    if (isset($_POST) && $_POST['mobile'] != "")
    {
      $apabi_id = 0;
      if(isset($_POST['apabi_id'])) 
      { 
        $apabi_id = strtolower($this->security->xss_clean($this->input->post('apabi_id')));       
      }

      if ($type == '1')
      {
        $mobile = $this->security->xss_clean($this->input->post('mobile'));
      }
      else
      {
        $mobile = $str;
      }

      //check if agency mobile exist or not
      $result_data = $this->master_model->getRecords('apabi_india_registrations am', array('am.is_deleted' => '0', 'am.mobile' => $mobile, 'am.apabi_id !=' => $apabi_id), 'am.apabi_id, am.mobile, am.email');

      if (count($result_data) == 0)
      {
        $return_val_ajax = 'true';
      }
    }

    if ($type == '1')
    {
      echo $return_val_ajax;
    }
    else
    {
      if ($return_val_ajax == 'true')
      {
        return TRUE;
      }
      else if ($_POST['mobile'] != "")
      {
        $this->form_validation->set_message('validation_check_mobile_exist', 'The mobile number is already registered with us');
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK MOBILE EXIST OR NOT ********/

  /******** START : VALIDATION FUNCTION TO CHECK EMAIL ID EXIST OR NOT ********/
  public function validation_check_email_exist($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    if (isset($_POST) && $_POST['email'] != "")
    {
      $apabi_id = 0;
      if(isset($_POST['apabi_id'])) 
      { 
        $apabi_id = strtolower($this->security->xss_clean($this->input->post('apabi_id')));       
      }

      if ($type == '1')
      {
        $email = strtolower($this->security->xss_clean($this->input->post('email')));
      }
      else
      {
        $email = strtolower($str);
      }

      //check if agency mobile exist or not
      $result_data = $this->master_model->getRecords('apabi_india_registrations am', array('am.is_deleted' => '0', 'am.email' => $email, 'am.apabi_id !=' => $apabi_id), 'am.apabi_id, am.mobile, am.email');

      if (count($result_data) == 0)
      {
        $return_val_ajax = 'true';
      }
    }

    if ($type == '1')
    {
      echo $return_val_ajax;
    }
    else
    {
      if ($return_val_ajax == 'true')
      {
        return TRUE;
      }
      else if ($_POST['email'] != "")
      {
        $this->form_validation->set_message('validation_check_email_exist', 'The email id is already registered with us');
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK MOBILE EXIST OR NOT ********/

  /******** START : VALIDATION FUNCTION TO RESTRICT INPUT VALUES ********/
  function fun_restrict_input($str, $type) // Custom callback function for restrict input
  {
    if ($str != '')
    {
      $result = $this->Iibf_bcbf_model->fun_restrict_input($str, $type);
      if ($result['flag'] == 'success')
      {
        return true;
      }
      else
      {
        $this->form_validation->set_message('fun_restrict_input', $result['response']);
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO RESTRICT INPUT VALUES ********/
}
