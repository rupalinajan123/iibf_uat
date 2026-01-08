<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Dashboard_candidate extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();

      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 

      $this->login_candidate_id = $this->session->userdata('IIBF_BCBF_CANDIDATE_LOGIN_ID');
      $this->Iibf_bcbf_model->check_candidate_session_all_pages(); // If admin session is not started then redirect to logout

      $this->id_proof_file_path = 'uploads/iibfbcbf/id_proof';
      $this->qualification_certificate_file_path = 'uploads/iibfbcbf/qualification_certificate';
      $this->candidate_photo_path = 'uploads/iibfbcbf/photo';
      $this->candidate_sign_path = 'uploads/iibfbcbf/sign';
    }
    
    public function index()
		{   
			$data['act_id'] = "Dashboard";
			$data['sub_act_id'] = "";
      $data['page_title'] = 'IIBF - BCBF Candidate Dashboard';

      $this->load->view('iibfbcbf/candidate/dashboard_candidate', $data);
    }

    /******** START : UPDATE CANDIDATE DATA ********/
    public function update_profile()
    {
      $data['candidate_id'] = $candidate_id = $this->login_candidate_id;
      $data['enc_candidate_id'] = $enc_candidate_id = url_encode($candidate_id);
      
      $this->db->join('iibfbcbf_agency_centre_batch acb', 'acb.batch_id = bc.batch_id', 'INNER');
      $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_batch_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0', 'bc.regnumber !='=>''), "bc.*, IF(bc.gender=1,'Male','Female') AS DispGender, IF(bc.qualification=1, 'Under Graduate', IF(bc.qualification=2,'Graduate','Post Graduate')) AS DispQualification, acb.batch_start_date, acb.batch_end_date");        
      if(count($form_data) == 0) { redirect(site_url('iibfbcbf/candidate/dashboard_candidate')); }
      
      if($form_data[0]['batch_end_date'] >= date('Y-m-d')) 
      { 
        $this->session->set_flashdata('error','You can update your profile information after ('.date('d M Y', strtotime($form_data[0]['batch_end_date'])).') completing the training.');
        redirect(site_url('iibfbcbf/candidate/dashboard_candidate')); 
      }

      $data['enc_batch_id'] = $enc_batch_id = url_encode($form_data[0]['batch_id']);      
      $dob_end_date = date('Y-m-d', strtotime("- 18 year", strtotime(date('Y-m-d'))));
      
      $error_flag = 0;
      $data['act_id'] = "Update Profile";
      $data['sub_act_id'] = "Update Profile";
      $data['id_proof_file_error'] = $data['qualification_certificate_file_error'] = $data['candidate_photo_error'] = $data['candidate_sign_error'] = '';
      $data['id_proof_file_path'] = $id_proof_file_path = $this->id_proof_file_path;
      $data['qualification_certificate_file_path'] = $qualification_certificate_file_path = $this->qualification_certificate_file_path;
      $data['candidate_photo_path'] = $candidate_photo_path = $this->candidate_photo_path;
      $data['candidate_sign_path'] = $candidate_sign_path = $this->candidate_sign_path;
      //$data['dob_start_date'] = $dob_start_date;
      $data['dob_end_date'] = $dob_end_date;   
      
      if (isset($_POST) && count($_POST) > 0)
      {
        /* _pa($_FILES); 
        _pa($_POST,1); */

        $id_proof_file_req_flg = $candidate_photo_req_flg = $candidate_sign_req_flg = $img_ediited_on_flag = '';
        
        if ($form_data[0]['id_proof_file'] == "") { $id_proof_file_req_flg = 'required|'; }
        if(isset($_POST['id_proof_file_cropper']) && $_POST['id_proof_file_cropper'] != "") { $id_proof_file_req_flg = ''; }

        if ($form_data[0]['candidate_photo'] == "") { $candidate_photo_req_flg = 'required|'; }
        if(isset($_POST['candidate_photo_cropper']) && $_POST['candidate_photo_cropper'] != "") { $candidate_photo_req_flg = ''; }

        if ($form_data[0]['candidate_sign'] == "") { $candidate_sign_req_flg = 'required|'; }
        if(isset($_POST['candidate_sign_cropper']) && $_POST['candidate_sign_cropper'] != "") { $candidate_sign_req_flg = ''; }
        
        $this->form_validation->set_rules('mobile_no', 'mobile number', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|min_length[10]|max_length[10]|callback_validation_check_mobile_exist[' . $enc_candidate_id . ']|callback_fun_restrict_input[first_zero_not_allowed]|xss_clean', array('required' => "Please enter the %s"));
        $this->form_validation->set_rules('alt_mobile_no', 'alternate mobile number', 'trim|callback_fun_restrict_input[allow_only_numbers]|min_length[10]|max_length[10]|callback_fun_restrict_input[first_zero_not_allowed]|xss_clean');
        $this->form_validation->set_rules('email_id', 'email id', 'trim|required|max_length[80]|valid_email|callback_validation_check_email_exist[' . $enc_candidate_id . ']|xss_clean', array('required' => "Please enter the %s"));
        $this->form_validation->set_rules('alt_email_id', 'alternate email id', 'trim|max_length[80]|valid_email|xss_clean');
        $this->form_validation->set_rules('address1', 'address line-1', 'trim|required|max_length[75]|xss_clean', array('required' => "Please enter the %s"));
        $this->form_validation->set_rules('address2', 'address line-2', 'trim|max_length[75]|xss_clean');
        $this->form_validation->set_rules('address3', 'address line-3', 'trim|max_length[75]|xss_clean');
        $this->form_validation->set_rules('address4', 'address line-4', 'trim|max_length[75]|xss_clean');
        $this->form_validation->set_rules('state', 'state', 'trim|required|xss_clean', array('required' => "Please select the %s"));
        $this->form_validation->set_rules('city', 'city', 'trim|required|xss_clean', array('required' => "Please select the %s"));
        $this->form_validation->set_rules('district', 'district', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_numbers_and_space]|max_length[30]|xss_clean', array('required' => "Please enter the %s"));
        $this->form_validation->set_rules('pincode', 'pincode', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|callback_validation_check_valid_pincode[' . $this->input->post('state') . ']|xss_clean', array('required' => "Please enter the %s"));
        
        $this->form_validation->set_rules('id_proof_file', 'proof of identity', 'trim|'.$id_proof_file_req_flg.'xss_clean');
        $this->form_validation->set_rules('candidate_photo', 'passport-size photo of the candidate', 'trim|'.$candidate_photo_req_flg.'xss_clean');
        $this->form_validation->set_rules('candidate_sign', 'signature of the candidate', 'trim|'.$candidate_sign_req_flg.'xss_clean');        
        
        //$this->form_validation->set_rules('xxx', '', 'trim|required|xss_clean');
        if ($this->form_validation->run())
        {
          $new_id_proof_file = $new_candidate_photo = $new_candidate_sign = '';

          $file_name_str = date("YmdHis") . '_' . rand(1000, 9999);
          
          if (isset($_POST['id_proof_file_cropper']) && $_POST['id_proof_file_cropper'] != "")
          {
            $id_proof_file_cropper = $this->security->xss_clean($this->input->post('id_proof_file_cropper'));
            $new_file_name1 = "id_proof_" . $file_name_str . '.' . strtolower(pathinfo($id_proof_file_cropper, PATHINFO_EXTENSION));
            if (copy(str_replace(base_url(), '', $id_proof_file_cropper), $id_proof_file_path . '/' . $new_file_name1))
            {
              $add_data['id_proof_file'] = $id_proof_file = $new_id_proof_file = basename($new_file_name1);

              if($form_data[0]['kyc_status'] != '0') { $img_ediited_on_flag = '1'; }
            }
            else
            {
              $data['id_proof_file_error'] = 'Please upload valid Proof of Identity';
              $error_flag = 1;
            }
          }

          if (isset($_POST['candidate_photo_cropper']) && $_POST['candidate_photo_cropper'] != "")
          {
            $candidate_photo_cropper = $this->security->xss_clean($this->input->post('candidate_photo_cropper'));

            $new_file_name3 = "photo_" . $file_name_str . '.' . strtolower(pathinfo($candidate_photo_cropper, PATHINFO_EXTENSION));
            if (copy(str_replace(base_url(), '', $candidate_photo_cropper), $candidate_photo_path . '/' . $new_file_name3))
            {
              $add_data['candidate_photo'] = $candidate_photo = $new_candidate_photo = basename($new_file_name3);
              if($form_data[0]['kyc_status'] != '0') { $img_ediited_on_flag = '1'; }
            }
            else
            {
              $data['candidate_photo_error'] = 'Please upload valid Passport-size Photo';
              $error_flag = 1;
            }
          }

          if (isset($_POST['candidate_sign_cropper']) && $_POST['candidate_sign_cropper'] != "")
          {
            $candidate_sign_cropper = $this->security->xss_clean($this->input->post('candidate_sign_cropper'));
            $new_file_name4 = "sign_" . $file_name_str . '.' . strtolower(pathinfo($candidate_sign_cropper, PATHINFO_EXTENSION));
            if (copy(str_replace(base_url(), '', $candidate_sign_cropper), $candidate_sign_path . '/' . $new_file_name4))
            {
              $add_data['candidate_sign'] = $candidate_sign = $new_candidate_sign = basename($new_file_name4);
              if($form_data[0]['kyc_status'] != '0') { $img_ediited_on_flag = '1'; }
            }
            else
            {
              $data['candidate_sign_error'] = 'Please upload valid Signature of the Candidate';
              $error_flag = 1;
            }
          }

          if ($error_flag == 0)
          {
            $posted_arr = json_encode($_POST) . ' >> ' . json_encode($_FILES);
            $candidateName = $this->Iibf_bcbf_model->getLoggedInUserDetails($candidate_id, 'candidate');
            
            $add_data['mobile_no'] = $this->input->post('mobile_no');
            $add_data['alt_mobile_no'] = $this->input->post('alt_mobile_no');
            $add_data['email_id'] = strtolower($this->input->post('email_id'));
            $add_data['alt_email_id'] = strtolower($this->input->post('alt_email_id'));
            $add_data['address1'] = $this->input->post('address1');
            $add_data['address2'] = $this->input->post('address2');
            $add_data['address3'] = $this->input->post('address3');
            $add_data['address4'] = $this->input->post('address4');
            $add_data['state'] = $this->input->post('state');
            $add_data['city'] = $this->input->post('city');
            $add_data['district'] = $this->input->post('district');
            $add_data['pincode'] = $this->input->post('pincode');
            $add_data['ip_address'] = get_ip_address(); //general_helper.php
            
            //START : IF FILE NOT EXIST WHILE ADDING / UPDATING THE RECORD, THEN REDIRECT & SHOW ERROR MESSAGE
            $chk_id_proof_file = $chk_candidate_photo = $chk_candidate_sign = '';
            if ($new_id_proof_file == '')
            {
              $chk_id_proof_file = $form_data[0]['id_proof_file'];
            }
            else if ($new_id_proof_file != '')
            {
              $chk_id_proof_file = $new_id_proof_file;
            }            

            if ($new_candidate_photo == '')
            {
              $chk_candidate_photo = $form_data[0]['candidate_photo'];
            }
            else if ($new_candidate_photo != '')
            {
              $chk_candidate_photo = $new_candidate_photo;
            }

            if ($new_candidate_sign == '')
            {
              $chk_candidate_sign = $form_data[0]['candidate_sign'];
            }
            else if ($new_candidate_sign != '')
            {
              $chk_candidate_sign = $new_candidate_sign;
            }

            //START : IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN REDIRECT & SHOW ERROR MESSAGE
            $this->Iibf_bcbf_model->check_file_exist($chk_id_proof_file, "./" . $id_proof_file_path . "/", 'iibfbcbf/candidate/dashboard_candidate/update_profile', 'Candidate profile not updated due to missing Proof of Identity'); //IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN SHOW ERROR MESSAGE

            $this->Iibf_bcbf_model->check_file_exist($chk_candidate_photo, "./" . $candidate_photo_path . "/", 'iibfbcbf/candidate/dashboard_candidate/update_profile', 'Candidate profile not updated due to missing Passport Photograph of the Candidate'); //IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN SHOW ERROR MESSAGE

            $this->Iibf_bcbf_model->check_file_exist($chk_candidate_sign, "./" . $candidate_sign_path . "/", 'iibfbcbf/candidate/dashboard_candidate/update_profile', 'Candidate profile not updated due to missing Signature of the Candidate'); //IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN SHOW ERROR MESSAGE
            //END : IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN REDIRECT & SHOW ERROR MESSAGE            

            $add_data['updated_on'] = date("Y-m-d H:i:s");
            $add_data['updated_by'] = $candidate_id;

            if($img_ediited_on_flag == '1')
            {
              $add_data['img_ediited_on'] = date("Y-m-d H:i:s");
              $add_data['kyc_status'] = '0';
              $add_data['kyc_recommender_status'] = '';
              $add_data['recommender_id'] = '0';
              $add_data['kyc_approver_status'] = '';
              $add_data['approver_id'] = '0';
              $add_data['kyc_recommender_date'] = '';
              $add_data['kyc_approver_date'] = '';
            }
            $this->master_model->updateRecord('iibfbcbf_batch_candidates', $add_data, array('candidate_id' => $candidate_id));
            
            $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate Profile Updated', 'iibfbcbf_batch_candidates', $this->db->last_query(), $candidate_id, 'candidate_action', 'The candidate has successfully updated the profile', $posted_arr);

            $this->session->set_flashdata('success', 'Candidate profile updated successfully');            

            // START : Rename the images and update into database table name
            if ($candidate_id > 0)
            {
              $candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.regnumber !=' => ''), "bc.candidate_id, bc.regnumber, bc.id_proof_file, bc.qualification_certificate_file, bc.candidate_photo, bc.candidate_sign");
              if (count($candidate_data) > 0)
              {
                $id_proof_file = $candidate_data[0]['id_proof_file'];
                $candidate_photo = $candidate_data[0]['candidate_photo'];
                $candidate_sign = $candidate_data[0]['candidate_sign'];

                $id_proof_file_new = 'id_proof_' . $candidate_data[0]['regnumber'] . '.' . pathinfo($id_proof_file, PATHINFO_EXTENSION);
                $candidate_photo_new = 'photo_' . $candidate_data[0]['regnumber'] . '.' . pathinfo($candidate_photo, PATHINFO_EXTENSION);
                $candidate_sign_new = 'sign_' . $candidate_data[0]['regnumber'] . '.' . pathinfo($candidate_sign, PATHINFO_EXTENSION);

                $up_img_data = array();
                if ($id_proof_file != $id_proof_file_new)
                {
                  if (rename($id_proof_file_path . "/" . $id_proof_file, $id_proof_file_path . "/" . $id_proof_file_new))
                  {
                    $up_img_data['id_proof_file'] = $id_proof_file_new;
                  }
                }
                
                if ($candidate_photo != $candidate_photo_new)
                {
                  if (rename($candidate_photo_path . "/" . $candidate_photo, $candidate_photo_path . "/" . $candidate_photo_new))
                  {
                    $up_img_data['candidate_photo'] = $candidate_photo_new;
                  }
                }

                if ($candidate_sign != $candidate_sign_new)
                {
                  if (rename($candidate_sign_path . "/" . $candidate_sign, $candidate_sign_path . "/" . $candidate_sign_new))
                  {
                    $up_img_data['candidate_sign'] = $candidate_sign_new;
                  }
                }

                if (count($up_img_data) > 0)
                {
                  $this->master_model->updateRecord('iibfbcbf_batch_candidates', $up_img_data, array('candidate_id' => $candidate_data[0]['candidate_id']));

                  $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate Profile Updated - image log', 'iibfbcbf_batch_candidates', $this->db->last_query(), $candidate_data[0]['candidate_id'], 'candidate_action_image_log', 'The candidate has successfully updated the profile images', '');
                }
              }
            }
            // END : Rename the images and update into database table name
            redirect(site_url('iibfbcbf/candidate/dashboard_candidate/update_profile'));
          }
        }
      }	
      
      $data['page_title'] = 'IIBF - BCBF Candidate Update Profile'; 

      $data['state_master_data'] = $this->master_model->getRecords('state_master', array('state_delete' => '0', 'id !=' => '11'), 'id, state_code, state_no, exempt, state_name', array('state_name'=>'ASC'));
      $data['bank_associated_master_data'] = $this->master_model->getRecords('iibfbcbf_bank_associated_master', array('is_active' => '1', 'is_deleted' => '0'), 'bank_id, bank_name, bank_code', array("bank_name = 'Other'" => 'ASC', 'bank_name'=>'ASC'));
      $this->load->view('iibfbcbf/candidate/update_profile_candidate', $data);
    }/******** END : UPDATE CANDIDATE DATA ********/

    function get_city_ajax()
    {
      if (isset($_POST) && count($_POST) > 0)
      {
        $onchange_fun = "validate_file('city')";
        $html = '	<select class="form-control chosen-select ignore_required" name="city" id="city" required onchange="' . $onchange_fun . '">';
        $state_id = $this->security->xss_clean($this->input->post('state_id'));

        $city_data = $this->master_model->getRecords('city_master', array('state_code' => $state_id, 'city_delete' => '0'), 'id, city_name', array('city_name' => 'ASC'));
        if (count($city_data) > 0)
        {
          $html .= '	<option value="">Select City</option>';
          foreach ($city_data as $city)
          {
            $html .= '	<option value="' . $city['id'] . '">' . $city['city_name'] . '</option>';
          }
        }
        else
        {
          $html .= '	<option value="">Select City</option>';
        }
        $html .= '</select>';
        $html .= "<script>$('.chosen-select').chosen({width: '100%'});function validate_file(input_id) { $('#'+input_id).valid(); }</script>";

        $result['flag'] = "success";
        $result['response'] = $html;
      }
      else
      {
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }

    /******** START : VALIDATION FUNCTION TO CHECK CANDIDATE MOBILE EXIST OR NOT ********/
    public function validation_check_mobile_exist($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
      if (isset($_POST) && $_POST['mobile_no'] != "")
      {
        if ($type == '1')
        {
          $mobile_no = $this->security->xss_clean($this->input->post('mobile_no'));
          $enc_candidate_id = $this->security->xss_clean($this->input->post('enc_candidate_id'));

          if ($enc_candidate_id != "" && $enc_candidate_id != '0')
          {
            $candidate_id = url_decode($enc_candidate_id);
          }
          else
          {
            $candidate_id = $enc_candidate_id;
          }
        }
        else
        {
          $mobile_no = $str;
          $enc_candidate_id = $type;
          $candidate_id = url_decode($enc_candidate_id);
        }

        $candidate_data = $this->Iibf_bcbf_model->validation_check_candidate_eligibility_to_add_in_new_batch($candidate_id, 'mobile', $mobile_no);
        if (count($candidate_data) == 0)
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
        else if ($_POST['mobile_no'] != "")
        {
          $this->form_validation->set_message('validation_check_mobile_exist', 'The mobile number is already exist');
          return false;
        }
      }
    }
    /******** END : VALIDATION FUNCTION TO CHECK CANDIDATE MOBILE EXIST OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK CANDIDATE EMAIL ID EXIST OR NOT ********/
    public function validation_check_email_exist($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
      if (isset($_POST) && $_POST['email_id'] != "")
      {
        if ($type == '1')
        {
          $email_id = strtolower($this->security->xss_clean($this->input->post('email_id')));
          $enc_candidate_id = $this->security->xss_clean($this->input->post('enc_candidate_id'));

          if ($enc_candidate_id != "" && $enc_candidate_id != '0')
          {
            $candidate_id = url_decode($enc_candidate_id);
          }
          else
          {
            $candidate_id = $enc_candidate_id;
          }
        }
        else
        {
          $email_id = strtolower($str);
          $enc_candidate_id = $type;
          $candidate_id = url_decode($enc_candidate_id);
        }

        $candidate_data = $this->Iibf_bcbf_model->validation_check_candidate_eligibility_to_add_in_new_batch($candidate_id, 'email', $email_id);
        if (count($candidate_data) == 0)
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
        else if ($_POST['email_id'] != "")
        {
          $this->form_validation->set_message('validation_check_email_exist', 'The email id is already exist');
          return false;
        }
      }
    }
    /******** END : VALIDATION FUNCTION TO CHECK CANDIDATE MOBILE EXIST OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK PINCODE IS VALID OR NOT ********/
    public function validation_check_valid_pincode($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
      if (isset($_POST) && $_POST['pincode'] != "")
      {
        if ($type == '1')
        {
          $pincode = $this->security->xss_clean($this->input->post('pincode'));
          $selected_state_code = $this->security->xss_clean($this->input->post('selected_state_code'));
        }
        else
        {
          $pincode = $str;
          $selected_state_code = $type;
        }

        $this->db->where(" '" . $pincode . "' BETWEEN start_pin AND end_pin ");
        $result_data = $this->master_model->getRecords('state_master', array('state_code' => $selected_state_code), 'id, state_code, start_pin, end_pin');

        if (count($result_data) > 0)
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
        else if ($_POST['pincode'] != "")
        {
          $pin_length = strlen($_POST['pincode']);

          $err_msg = 'Please enter valid pincode as per selected city';
          if ($pin_length != 6)
          {
            $err_msg = 'Please enter only 6 numbers in pincode';
          }

          $this->form_validation->set_message('validation_check_valid_pincode', $err_msg);
          return false;
        }
      }
    }
    /******** END : VALIDATION FUNCTION TO CHECK PINCODE IS VALID OR NOT ********/
    
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

    /******** START : VALIDATION FUNCTION TO CHECK VALID FILE ********/
    function fun_validate_file_upload($str,$parameter) // Custom callback function for check valid file
    {
      $result = $this->Iibf_bcbf_model->fun_validate_file_upload($parameter); 
      if($result['flag'] == 'success') { return true; }
      else
      {
        $this->form_validation->set_message('fun_validate_file_upload', $result['response']);
        return false;
      }
    }/******** END : VALIDATION FUNCTION TO CHECK VALID FILE ********/


    /******** START : VIEW CANDIDATE DATA ********/
    public function view_profile()
    {
      $data['candidate_id'] = $candidate_id = $this->login_candidate_id;
      $data['enc_candidate_id'] = $enc_candidate_id = url_encode($candidate_id);
      
      $this->db->join('state_master sm', 'sm.state_code = bc.state', 'LEFT');
      $this->db->join('city_master cm1', 'cm1.id = bc.city', 'LEFT');
      $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = bc.agency_id', 'LEFT');
      $this->db->join('iibfbcbf_centre_master cm', 'cm.centre_id = bc.centre_id', 'INNER');
      $this->db->join('city_master cm2', 'cm2.id = cm.centre_city', 'LEFT');
      $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_batch_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0'), "bc.*, IF(bc.gender=1,'Male','Female') AS DispGender, IF(bc.qualification=1, 'Under Graduate', IF(bc.qualification=2,'Graduate','Post Graduate')) AS DispQualification, sm.state_name, cm1.city_name, IF(bc.id_proof_type=1, 'Aadhar Card', IF(bc.id_proof_type=2,'Driving Licence',IF(bc.id_proof_type=3,'Employee ID', IF(bc.id_proof_type=4,'Pan Card','Passport')))) AS DispIdProofType, IF(bc.qualification_certificate_type=1, '10th Pass', IF(bc.qualification_certificate_type=2,'12th Pass',IF(bc.qualification_certificate_type=3,'Graduation',IF(bc.qualification_certificate_type=4,'Post Graduation','')))) AS DispQualificationCertificateType,  IF(bc.hold_release_status=1,'Auto Hold', IF(bc.hold_release_status=2,'Manual Hold','Release')) AS Disphold_release_status,am.agency_name, am.agency_code, am.allow_exam_types, cm.centre_name, cm.centre_username, cm2.city_name AS centre_city_name");
      if(count($form_data) == 0) { redirect(site_url('iibfbcbf/candidate/dashboard_candidate')); }
      
      $data['act_id'] = "View Profile";
      $data['sub_act_id'] = "View Profile";
      
      $data['id_proof_file_path'] = $this->id_proof_file_path;
      $data['qualification_certificate_file_path'] = $this->qualification_certificate_file_path;
      $data['candidate_photo_path'] = $this->candidate_photo_path;
      $data['candidate_sign_path'] = $this->candidate_sign_path;
       
      $data['page_title'] = 'IIBF - BCBF Candidate View Profile';
      $this->load->view('iibfbcbf/candidate/view_profile_candidate', $data);
    }/******** END : VIEW CANDIDATE DATA ********/
    
  }	    