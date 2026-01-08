<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF AGENCY REGISTRATION
  ** Created BY: Sagar Matale On 28-02-2024
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Agency_registration extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      $this->load->helper('file');
		}

    public function index() 
    { 
      $data['page_title'] = 'IIBF - BCBF Agency Registration';  

      if(isset($_POST) && count($_POST) > 0)
      {
        $this->form_validation->set_rules('agency_name', 'agency name', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[90]|xss_clean', array('required'=>"Please enter the %s"));     
        $this->form_validation->set_rules('estb_year', 'establishment year', 'trim|required|xss_clean', array('required'=>"Please select the %s"));  
        $this->form_validation->set_rules('agency_address1', 'address line-1', 'trim|required|max_length[75]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('agency_address2', 'address line-2', 'trim|max_length[75]|xss_clean');
        $this->form_validation->set_rules('agency_address3', 'address line-3', 'trim|max_length[75]|xss_clean');
        $this->form_validation->set_rules('agency_address4', 'address line-4', 'trim|max_length[75]|xss_clean');
        $this->form_validation->set_rules('agency_state', 'state', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('agency_city', 'city', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('agency_district', 'district', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[30]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('agency_pincode', 'pincode', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|callback_validation_check_valid_pincode['.$this->input->post('agency_state').']|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('contact_person_name', 'contact person name', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[90]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('contact_person_designation', 'contact person designation', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[90]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('contact_person_mobile', 'mobile number', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|min_length[10]|max_length[10]|callback_validation_check_mobile_exist[]|callback_fun_restrict_input[first_zero_not_allowed]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('contact_person_email', 'email id', 'trim|required|max_length[80]|valid_email|callback_validation_check_email_exist[]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('gst_no', 'GST no.', 'trim|required|min_length[15]|max_length[15]|callback_fun_restrict_input[allow_only_alphabets_and_numbers]|callback_fun_validate_gst_no|callback_validation_check_gst_no_exist|xss_clean', array('required'=>"Please enter the %s"));

        $this->form_validation->set_rules('agency_password', 'password', 'trim|required|callback_fun_validate_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[8]|callback_validation_check_password|xss_clean',array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));

        ///$this->form_validation->set_rules('xxx', 'xxx', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        if($this->form_validation->run())
        {          
          $posted_arr = json_encode($_POST);
          
          $add_data['agency_name'] = $agency_name = $this->input->post('agency_name');
          $add_data['estb_year'] = $this->input->post('estb_year');
          $add_data['agency_address1'] = $this->input->post('agency_address1');
          $add_data['agency_address2'] = $this->input->post('agency_address2');
          $add_data['agency_address3'] = $this->input->post('agency_address3');
          $add_data['agency_address4'] = $this->input->post('agency_address4');
          $add_data['agency_state'] = $this->input->post('agency_state');
          $add_data['agency_city'] = $this->input->post('agency_city');
          $add_data['agency_district'] = $this->input->post('agency_district');
          $add_data['agency_pincode'] = $this->input->post('agency_pincode');
          $add_data['contact_person_name'] = $this->input->post('contact_person_name');
          $add_data['contact_person_designation'] = $this->input->post('contact_person_designation');
          $add_data['contact_person_mobile'] = $this->input->post('contact_person_mobile');
          $add_data['contact_person_email'] = strtolower($this->input->post('contact_person_email'));
          $add_data['gst_no'] = $this->input->post('gst_no');
          $add_data['ip_address'] = get_ip_address(); //general_helper.php   
          
          
          $add_data['is_active'] = '2';
          $add_data['agency_password'] = $this->Iibf_bcbf_model->password_encryption($this->input->post('agency_password'));
          $add_data['created_on'] = date("Y-m-d H:i:s");
          
          $this->master_model->insertRecord('iibfbcbf_agency_master',$add_data);
          $agency_id = $this->db->insert_id();

          $total_record_qry = $this->db->query('SELECT am.agency_id FROM iibfbcbf_agency_master am WHERE am.agency_id <= "'.$agency_id.'"');
          $get_total_record = $total_record_qry->num_rows();
          
          $up_data['agency_code'] = 1000 + $get_total_record;            
          $this->master_model->updateRecord('iibfbcbf_agency_master', $up_data, array('agency_id'=>$agency_id));
          
          $this->Iibf_bcbf_model->insert_common_log('Agency Registration', 'iibfbcbf_agency_master', $this->db->last_query(), $agency_id,'agency_action','The agency has successfully registered', $posted_arr); 
          
          $this->session->set_flashdata('success','Agency registration successfully done');          
          redirect(site_url('iibfbcbf/agency_registration'));
        }
      }	
      
      $data['state_master_data'] = $this->master_model->getRecords('state_master', array('state_delete' => '0', 'id !=' => '11'), 'id, state_code, state_no, exempt, state_name', array('state_name'=>'ASC'));
      
      $this->load->helper('captcha');
      $data['captcha_img'] = generate_captcha('IIBF_BCBF_AGENCY_REGISTRATION_CAPTCHA',6); //iibfbcbf/iibf_bcbf_helper.php
      $this->load->view('iibfbcbf/agency_registration', $data);
    }

    public function refresh_captcha() /******** START : REFRESH CAPTCHA ********/
    { 
      $this->load->helper('captcha');
      echo generate_captcha('IIBF_BCBF_AGENCY_REGISTRATION_CAPTCHA',6); //iibfbcbf/iibf_bcbf_helper.php
    }  /******** END : REFRESH CAPTCHA ********/
    
    /******** START : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/
    public function validation_check_captcha($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
      if(isset($_POST) && $_POST['iibf_bcbf_agency_registration_captcha'] != "")
      {
        if($type == '1') { $captcha = $this->security->xss_clean($this->input->post('iibf_bcbf_agency_registration_captcha')); }
        else if($type == '0') { $captcha = $str; }
        
        $session_captcha = $this->session->userdata('IIBF_BCBF_AGENCY_REGISTRATION_CAPTCHA');
        
        if($captcha == $session_captcha)
        {
          $return_val_ajax = 'true';
        }
      }   
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['iibf_bcbf_agency_registration_captcha'] != "")
        {
          $this->form_validation->set_message('validation_check_captcha','Please enter the valid code');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/

    function get_city_ajax()
    {
			if(isset($_POST) && count($_POST) > 0)
			{
        $onchange_fun = "validate_file('agency_city')";
				$html = '	<select class="form-control chosen-select ignore_required" name="agency_city" id="agency_city" required onchange="'.$onchange_fun.'">';
				$state_id = $this->security->xss_clean($this->input->post('state_id'));
        
        $city_data = $this->master_model->getRecords('city_master', array('state_code' => $state_id, 'city_delete' => '0'), 'id, city_name', array('city_name'=>'ASC'));
				if(count($city_data) > 0)
				{
					$html .= '	<option value="">Select City</option>';
					foreach($city_data as $city)
					{
						$html .= '	<option value="'.$city['id'].'">'.$city['city_name'].'</option>';
          }
        }
				else
				{
					$html .= '	<option value="">Select City</option>';
        }
				$html .= '</select>';
				$html .="<script>$('.chosen-select').chosen({width: '100%'});function validate_file(input_id) { $('#'+input_id).valid(); }</script>";
        
        $result['flag'] = "success";
        $result['response'] = $html;
      }
      else
      {      
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }
     
    /******** START : VALIDATION FUNCTION TO CHECK PINCODE IS VALID OR NOT ********/
    public function validation_check_valid_pincode($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['agency_pincode'] != "")
			{
        if($type == '1') 
        { 
          $agency_pincode = $this->security->xss_clean($this->input->post('agency_pincode')); 
          $selected_state_code = $this->security->xss_clean($this->input->post('selected_state_code'));
        }
        else 
        { 
          $agency_pincode = $str; 
          $selected_state_code = $type;
        }

        $this->db->where(" '".$agency_pincode."' BETWEEN start_pin AND end_pin ");
        $result_data = $this->master_model->getRecords('state_master', array('state_code' => $selected_state_code), 'id, state_code, start_pin, end_pin');
              
        if(count($result_data) > 0)
        {
          $return_val_ajax = 'true';
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['agency_pincode'] != "")
        {
          $pin_length = strlen($_POST['agency_pincode']);

          $err_msg = 'Please enter valid pincode as per selected city';
          if($pin_length != 6) { $err_msg = 'Please enter only 6 numbers in pincode'; }

          $this->form_validation->set_message('validation_check_valid_pincode',$err_msg);
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK PINCODE IS VALID OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK AGENCY MOBILE EXIST OR NOT ********/
    public function validation_check_mobile_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['contact_person_mobile'] != "")
			{
        if($type == '1') 
        { 
          $contact_person_mobile = $this->security->xss_clean($this->input->post('contact_person_mobile')); 
        }
        else 
        { 
          $contact_person_mobile = $str;
        }

        //check if agency mobile exist or not
        $result_data = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted' => '0', 'am.contact_person_mobile' => $contact_person_mobile), 'am.agency_id, am.contact_person_mobile, am.contact_person_email');
      
        if(count($result_data) == 0)
        {
          $return_val_ajax = 'true';
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['contact_person_mobile'] != "")
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
			if(isset($_POST) && $_POST['contact_person_email'] != "")
			{
        if($type == '1') 
        { 
          $contact_person_email = strtolower($this->security->xss_clean($this->input->post('contact_person_email')));
        }
        else 
        { 
          $contact_person_email = strtolower($str);
        }

        //check if agency mobile exist or not
        $result_data = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted' => '0', 'am.contact_person_email' => $contact_person_email), 'am.agency_id, am.contact_person_mobile, am.contact_person_email');
      
        if(count($result_data) == 0)
        {
          $return_val_ajax = 'true';
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['contact_person_email'] != "")
        {
          $this->form_validation->set_message('validation_check_email_exist','The email id is already exist');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK AGENCY MOBILE EXIST OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK AGENCY GST NUMBER EXIST OR NOT ********/
    public function validation_check_gst_no_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['gst_no'] != "")
			{
        if($type == '1') 
        { 
          $gst_no = $this->security->xss_clean($this->input->post('gst_no'));
        }
        else 
        { 
          $gst_no = $str;
        }

        $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('is_deleted' => '0', 'gst_no' => $gst_no), 'agency_id, gst_no, is_active');
      
        if(count($agency_data) == 0)
        {
          $return_val_ajax = 'true';
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['gst_no'] != "")
        {
          $this->form_validation->set_message('validation_check_gst_no_exist','The gst number is already exist');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK AGENCY GST NUMBER EXIST OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK VALID GST NUMBER ********/
    function fun_validate_gst_no($str) // Custom callback function for check valid GST number
    {
      if($str != '')
      {
        $result = $this->Iibf_bcbf_model->fun_validate_gst_no($str); 
        if($result['flag'] == 'success') { return true; }
        else
        {
          $this->form_validation->set_message('fun_validate_gst_no', $result['response']);
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK VALID GST NUMBER ********/

    /******** START : VALIDATION FUNCTION TO RESTRICT INPUT VALUES ********/
    function fun_restrict_input($str,$type) // Custom callback function for restrict input
    { 
      if($str != '')
      {
        $result = $this->Iibf_bcbf_model->fun_restrict_input($str, $type); 
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
          $result = $this->Iibf_bcbf_model->fun_validate_password($str); 
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
        $agency_password = $this->security->xss_clean($this->input->post('agency_password'));
        if($type == '1') { $confirm_password = $this->security->xss_clean($this->input->post('confirm_password')); }
        else if($type == '0') { $confirm_password = $str; }   
        
        if($agency_password == $confirm_password)
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
    
  } ?>  