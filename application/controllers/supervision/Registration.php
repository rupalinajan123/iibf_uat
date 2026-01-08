<?php 
  /********************************************************************************************************************
  ** Description: Controller for Supervision Candidate REGISTRATION
  ** Created BY: Priyanka Dhikale On 17-may-2024

  ********************************************************************************************************************/

  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Registration extends CI_Controller 
  {
    public function __construct()
    {

      
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('supervision_model');
      $this->load->helper('supervision_helper'); 
      $this->load->helper('file');
      $this->load->library('upload');
      $this->load->helper('upload_helper');
      $this->load->helper('master_helper');
       
		}

    //callback to validate idproofphoto
    public function bank_id_card_upload()
    {
        if ($_FILES['bank_id_card']['size'] != 0) {
            return true;
        } 
        else {
            $this->form_validation->set_message('bank_id_card_upload', "No Id proof file selected");
            return false;
        }
    }

    public function index() 
    { 
      $data['page_title'] = 'Supervision Registration';  

      if(isset($_POST) && count($_POST) > 0)
      {
        $this->form_validation->set_rules('candidate_name', 'name', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[90]|xss_clean', array('required'=>"Please enter the %s"));     
        $this->form_validation->set_rules('pdc_zone', 'pdc zone', 'trim|required|xss_clean', array('required'=>"Please select the %s"));  
       
        $this->form_validation->set_rules('email', 'email id', 'trim|required|max_length[75]|valid_email|callback_validation_check_email_exist[]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('mobile', 'mobile number', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|min_length[10]|max_length[10]|callback_validation_check_mobile_exist[]|callback_fun_restrict_input[first_zero_not_allowed]|xss_clean', array('required'=>"Please enter the %s"));
     
        $this->form_validation->set_rules('designation', 'designation', 'trim|required|xss_clean');

        $this->form_validation->set_rules('bank_id_card', 'Id card', 'file_required|file_allowed_type[jpg,jpeg,png]|file_size_max[300]|callback_bank_id_card_upload');
        $this->form_validation->set_rules('bank', 'bank', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[100]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('branch', 'branch', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[100]|xss_clean', array('required'=>"Please enter the %s"));
   
       //$this->form_validation->set_rules('center', 'center', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|max_length[100]|xss_clean', array('required'=>"Please enter the %s"));

        $this->form_validation->set_rules('password', 'password', 'trim|required|callback_fun_validate_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[8]|callback_validation_check_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));

        if($this->form_validation->run())
        {          
          // generate dynamic id proof
          $outputidproof1 = $idproof_file='';
          $date = date('Y-m-d');
          if (isset($_FILES['bank_id_card']['name']) && ($_FILES['bank_id_card']['name'] != '')) 
          {
              $img              = "bank_id_card";
              $tmp_inputidproof = strtotime($date) . rand(0, 100);
              $new_filename     = 'bank_id_card_' . $tmp_inputidproof;
              $config           = array('upload_path' => './uploads/supervision',
                  'allowed_types'                         => 'jpg|jpeg|png',
                  'file_name'                             => $new_filename);

              $this->upload->initialize($config);
              $size = @getimagesize($_FILES['bank_id_card']['tmp_name']);
              if ($size) {
                  if ($this->upload->do_upload($img)) {
                      $dt             = $this->upload->data();
                      $idproof_file   = $dt['file_name'];
                      $outputidproof1 = base_url() . "uploads/idproof/" . $idproof_file;
                  } else {
                      $var_errors .= $this->upload->display_errors();
             
                  }
              } else {
                  $var_errors .= 'The filetype you are attempting to upload is not allowed';
              }

          }

          $posted_arr = json_encode($_POST);
          
          //$selected_center= $this->master_model->getRecords('supervision_center_master', array('center_delete' => '0','center_code '=>$this->input->post('center') ), 'id, center_code ,  center_name', array('center_name'=>'ASC'));

          $add_data['candidate_name'] = ucfirst(trim($candidate_name = $this->input->post('candidate_name')));
          $add_data['pdc_zone'] = $this->input->post('pdc_zone');
          $add_data['email'] = trim($this->input->post('email'));
          $add_data['mobile'] = trim($this->input->post('mobile'));
          $add_data['bank'] = ucfirst(trim($this->input->post('bank')));
          $add_data['branch'] = ucfirst(trim($this->input->post('branch')));
          $add_data['designation'] = ucfirst(trim($this->input->post('designation')));
          $add_data['bank_id_card'] = $idproof_file;

          //$add_data['center_code'] = (trim($this->input->post('center')));
          //$add_data['center_name'] = $selected_center[0]['center_name'];
         
          $add_data['ip_address'] = get_ip_address(); //general_helper.php   
          
          
          $add_data['is_active'] = '2';
          $add_data['password'] = $this->supervision_model->password_encryption($this->input->post('password'));
          $add_data['created_on'] = date("Y-m-d H:i:s");
          
          $this->master_model->insertRecord('supervision_candidates',$add_data);

          $id = $this->db->insert_id();

          $total_record_qry = $this->db->query('SELECT am.id FROM supervision_candidates am WHERE am.id <= "'.$id.'"');
          $get_total_record = $total_record_qry->num_rows();
          
          $up_data['candidate_code'] = 1000 + $get_total_record;            
          $this->master_model->updateRecord('supervision_candidates', $up_data, array('id'=>$id));
          
          $this->supervision_model->insert_common_log('Candidate Registration', 'supervision_candidates', $this->db->last_query(), $id,'candidate_action','The candidate has successfully registered', $posted_arr); 
          
          $this->session->set_flashdata('success','Candidate registration successfully done');          
          redirect(site_url('supervision/registration'));
        }
        else {
       
          echo validation_errors();
        }
      }	
      
      $exam = $this->master_model->getRecords('supervision_exam_activation', array('is_deleted' => '0'));

      $data['pdc_zone_master_data'] = $this->master_model->getRecords('pdc_zone_master', array('pdc_zone_delete' => '0'), 'id, pdc_zone_code,  pdc_zone_name', array('pdc_zone_name'=>'ASC'));
      
      //$this->db->join('pdc_zone_master sm', 'sm.pdc_zone_code = am.pdc_zone', 'LEFT');
      //$data['center_master_data'] = $this->master_model->getRecords('supervision_center_master am', array('am.center_delete' => '0','am.exam_name '=>$exam[0]['exam_code'],'am.exam_period '=>$exam[0]['exam_period'] ), 'am.id, am.center_code ,  am.center_name,am.pdc_zone, sm.pdc_zone_name', array('center_name'=>'ASC'));
      
      $this->load->helper('captcha');
      $data['captcha_img'] = generate_captcha('IIBF_SUPERVISION_REGISTRATION_CAPTCHA',6); //supervision/supervision_helper.php
      $this->load->view('supervision/registration', $data);
    }

    public function refresh_captcha() /******** START : REFRESH CAPTCHA ********/
    { 
      $this->load->helper('captcha');
      echo generate_captcha('IIBF_SUPERVISION_REGISTRATION_CAPTCHA',6); //supervision/supervision_helper.php
    }  /******** END : REFRESH CAPTCHA ********/
    
    /******** START : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/
    public function validation_check_captcha($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
      if(isset($_POST) && $_POST['iibf_supervision_registration_captcha'] != "")
      {
        if($type == '1') { $captcha = $this->security->xss_clean($this->input->post('iibf_supervision_registration_captcha')); }
        else if($type == '0') { $captcha = $str; }
        
        $session_captcha = $this->session->userdata('IIBF_SUPERVISION_REGISTRATION_CAPTCHA');
        
        if($captcha == $session_captcha)
        {
          $return_val_ajax = 'true';
        }
      }   
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['iibf_supervision_registration_captcha'] != "")
        {
          $this->form_validation->set_message('validation_check_captcha','Please enter the valid code');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/

     
    /******** START : VALIDATION FUNCTION TO CHECK AGENCY MOBILE EXIST OR NOT ********/
    public function validation_check_mobile_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['mobile'] != "")
			{
        if($type == '1') 
        { 
          $mobile = $this->security->xss_clean($this->input->post('mobile')); 
        }
        else 
        { 
          $mobile = $str;
        }

        //check if candidate mobile exist or not
        $result_data = $this->master_model->getRecords('supervision_candidates am', array('am.is_deleted' => '0', 'am.mobile' => $mobile), 'am.id, am.mobile, am.email');
      
        if(count($result_data) == 0)
        {
          $return_val_ajax = 'true';
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['mobile'] != "")
        {
          $this->form_validation->set_message('validation_check_mobile_exist','The mobile number is already exist');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK AGENCY MOBILE EXIST OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK AGENCY EMAIL ID EXIST OR NOT ********/
    public function validation_check_email_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['email'] != "")
			{
        if($type == '1') 
        { 
          $email = strtolower($this->security->xss_clean($this->input->post('email')));
        }
        else 
        { 
          $email = strtolower($str);
        }

        //check if candidate mobile exist or not
        $result_data = $this->master_model->getRecords('supervision_candidates am', array('am.is_deleted' => '0', 'am.email' => $email), 'am.id, am.mobile, am.email');
      
        if(count($result_data) == 0)
        {
          $return_val_ajax = 'true';
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['email'] != "")
        {
          $this->form_validation->set_message('validation_check_email_exist','The email id is already exist');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK AGENCY MOBILE EXIST OR NOT ********/

   
    /******** START : VALIDATION FUNCTION TO RESTRICT INPUT VALUES ********/
    function fun_restrict_input($str,$type) // Custom callback function for restrict input
    { 
      if($str != '')
      {
        $result = $this->supervision_model->fun_restrict_input($str, $type); 
        if($result['flag'] == 'success') { return true; }
        else
        {
          $this->form_validation->set_message('fun_restrict_input', $result['response']);
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO RESTRICT INPUT VALUES ********/

    /******** START : VALIDATION FUNCTION TO CHECK VALID PASSWORD ********/
    function fun_validate_password($str) // Custom callback function for check valid PASSWORD
    {
      if($str != '')
      {
        $password_length = strlen($str);
        $err_msg = '';
        if($password_length < 8) { $err_msg = 'Please enter minimum 8 characters in password'; }
        else if($password_length > 20) { $err_msg = 'Please enter maximum 20 characters in password'; }

        if($err_msg != "")
        {
          $this->form_validation->set_message('fun_validate_password', $err_msg);
          return false;
        }
        else
        {
          $result = $this->supervision_model->fun_validate_password($str); 
          if($result['flag'] == 'success') { return true; }
          else
          {
            $this->form_validation->set_message('fun_validate_password', $result['response']);
            return false;
          }
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK VALID PASSWORD ********/

    /******** START : VALIDATION FUNCTION TO CHECK THE PASSWORD & CONFIRM PASSWORD IS SAME OR NOT ********/
		function validation_check_password($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
		{
      $return_val_ajax = 'false';
      $msg = 'Please enter same password and confirm password';
			if(isset($_POST) && $_POST['confirm_password'] != "")
			{
        $password = $this->security->xss_clean($this->input->post('password'));
        if($type == '1') { $confirm_password = $this->security->xss_clean($this->input->post('confirm_password')); }
        else if($type == '0') { $confirm_password = $str; }   
        
        if($password == $confirm_password)
        {
          $return_val_ajax = 'true';
        }
			}
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['confirm_password'] != "")
        {
          $this->form_validation->set_message('validation_check_password',$msg);
          return false;
        }
      }
		}/******** END : VALIDATION FUNCTION TO CHECK THE PASSWORD & CONFIRM PASSWORD IS SAME OR NOT ********/
    
  }