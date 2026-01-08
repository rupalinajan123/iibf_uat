<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_candidate extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    $this->load->model('master_model');
    $this->load->model('ncvet/Ncvet_model');
    $this->load->helper('ncvet/ncvet_helper');
    $this->load->helper('master_helper');
    $this->load->helper('general_helper');

    $this->login_candidate_id = $this->session->userdata('NCVET_CANDIDATE_LOGIN_ID');
    // $this->login_user_type    = $this->session->userdata('NCVET_USER_TYPE');
    $this->Ncvet_model->check_candidate_session_all_pages(); // If admin session is not started then redirect to logout

    $this->id_proof_file_path                  = 'uploads/ncvet/id_proof';
    $this->aadhar_file_path                    = 'uploads/ncvet/aadhar_file';
    $this->qualification_certificate_file_path = 'uploads/ncvet/qualification_certificate';
    $this->candidate_photo_path                = 'uploads/ncvet/photo';
    $this->candidate_sign_path                 = 'uploads/ncvet/sign';
    $this->declarationform_path                = 'uploads/ncvet/declaration';
    $this->exp_certificate_path                = 'uploads/ncvet/experience';
    $this->institute_idproof_path              = 'uploads/ncvet/institute_idproof';
    $this->disability_cert_img_path            = 'uploads/ncvet/disability';
  }

  public function index()
  {
    // echo $this->login_candidate_id;
    // exit;
    $data['act_id'] = "Dashboard";
    $data['sub_act_id'] = "";
    $data['page_title'] = 'IIBF - NCVET Candidate Dashboard';

    $this->load->view('ncvet/candidate/dashboard_candidate', $data);
  }

  /******** START : UPDATE CANDIDATE DATA ********/
  public function update_profile()
  {
    
    $data['candidate_id']     = $candidate_id     = $this->login_candidate_id;
    $data['enc_candidate_id'] = $enc_candidate_id = url_encode($candidate_id);

    // $this->db->join('ncvet_agency_centre_batch acb', 'acb.batch_id = bc.batch_id', 'INNER');
    $data['form_data'] = $form_data = $this->master_model->getRecords('ncvet_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0'), "bc.*, IF(bc.gender=1,'Male','Female') AS DispGender");
    if (count($form_data) == 0) {
      redirect(site_url('ncvet/candidate/dashboard_candidate'));
    }

    $dob_end_date = date('Y-m-d', strtotime("- 18 year", strtotime(date('Y-m-d'))));

    $error_flag = 0;
    $data['act_id'] = "Profile";
    $data['sub_act_id'] = "Profile";
    $data['id_proof_file_error'] = $data['qualification_certificate_file_error'] = $data['candidate_photo_error'] = $data['candidate_sign_error'] = $data['declarationform_error'] = $data['aadhar_file_error'] = $data['disability_error'] = '';

    $data['id_proof_file_path'] = $id_proof_file_path = $this->id_proof_file_path;
    $data['aadhar_file_path'] = $aadhar_file_path = $this->aadhar_file_path;
    $data['qualification_certificate_file_path'] = $qualification_certificate_file_path = $this->qualification_certificate_file_path;
    $data['candidate_photo_path'] = $candidate_photo_path = $this->candidate_photo_path;
    $data['candidate_sign_path'] = $candidate_sign_path = $this->candidate_sign_path;
    $data['declarationform_path'] = $declarationform_path = $this->declarationform_path;
    $data['exp_certificate_path'] = $exp_certificate_path = $this->exp_certificate_path;
    $data['institute_idproof_path'] = $institute_idproof_path = $this->institute_idproof_path;
    $data['disability_cert_img_path'] = $disability_cert_img_path = $this->disability_cert_img_path;
    $data['dob_end_date'] = $dob_end_date = date('Y-m-d', strtotime("- 18 year", strtotime(date('Y-m-d'))));
    //$data['dob_start_date'] = $dob_start_date;
    $data['dob_end_date'] = $dob_end_date;

    if (isset($_POST) && count($_POST) > 0) {
      /*if ( $form_data['updated_fields'] == '' && $form_data['kyc_status'] != 3) 
        {
          $this->session->set_flashdata('error', 'Please update at least one field'); 
          redirect(site_url('ncvet/candidate/dashboard_candidate/update_profile')); 
        }*/

      if (checkEditableField('Candidate Name', $form_data[0]['candidate_id']) || $form_data[0]['kyc_fullname_flag'] == 'N') {
        $this->form_validation->set_rules('salutation', 'Candidate Name (Salutation)', 'trim|required|xss_clean', array('required' => "Please select the %s"));
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|regex_match[/^([a-z A-Z])+$/]|max_length[20]|xss_clean', array('required' => "Please enter the %s", 'regex_match' => "Please enter a valid %s", 'max_length' => "The %s field can not exceed 20 characters in length."));
        $this->form_validation->set_rules('middle_name', 'Middle Name', 'trim|regex_match[/^([a-z A-Z])+$/]|max_length[20]|xss_clean', array('regex_match' => "Please enter a valid %s", 'max_length' => "The %s field can not exceed 20 characters in length."));
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|regex_match[/^([a-z A-Z])+$/]|max_length[20]|xss_clean', array('regex_match' => "Please enter a valid %s", 'max_length' => "The %s field can not exceed 20 characters in length."));

        if (checkEditableField('Candidate Name', $form_data[0]['candidate_id'])) 
        {  
          $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean', array('required' => "Please select the %s"));
        }  
      }

      if (checkEditableField('Date of Birth', $form_data[0]['candidate_id']) || $form_data[0]['kyc_dob_flag'] == 'N') {
        $this->form_validation->set_rules('dob', 'date of birth', 'trim|required|callback_fun_validate_dob[' . $dob_end_date . ']|xss_clean', array('required' => "Please select the %s"));
      }

      if (checkEditableField('Guardian Name', $form_data[0]['candidate_id'])) {
        $this->form_validation->set_rules('guardian_salutation', 'Guardian Salutation', 'trim|required|xss_clean', array('required' => "Please select the %s"));
        $this->form_validation->set_rules('guardian_name', 'Guardian Name', 'trim|required|regex_match[/^([a-z A-Z])+$/]|max_length[60]|xss_clean', array('required' => "Please enter the %s", 'regex_match' => "Please enter a valid %s", 'max_length' => "The %s field can not exceed 60 characters in length."));
      }

      if (checkEditableField('Communication Address', $form_data[0]['candidate_id'])) {
        $this->form_validation->set_rules('address1', 'Address 1', 'trim|required|max_length[75]|xss_clean', array('required' => "Please enter the %s", 'max_length' => "The %s field can not exceed 75 characters in length."));
        $this->form_validation->set_rules('address2', 'Address 2', 'trim|max_length[75]|xss_clean', array('max_length' => "The %s field can not exceed 75 characters in length."));
        $this->form_validation->set_rules('address3', 'Address 3', 'trim|max_length[75]|xss_clean', array('max_length' => "The %s field can not exceed 75 characters in length."));
        $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean', array('required' => "Please enter the %s", 'max_length' => "The %s field can not exceed 50 characters in length."));
        $this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean', array('required' => "Please select the %s"));
        $this->form_validation->set_rules('district', 'District', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_numbers_and_space]|max_length[30]|xss_clean', array('required' => "Please enter the %s"));
        $this->form_validation->set_rules('pincode', 'Pincode', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|callback_validation_check_valid_pincode[' . $this->input->post('state') . ']|max_length[6]|xss_clean', array('required' => "Please enter the %s"));
      }

      if (checkEditableField('Permanant Address', $form_data[0]['candidate_id'])) {
        $this->form_validation->set_rules('address1_pr', 'Address 1', 'trim|required|max_length[75]|xss_clean', array('required' => "Please enter the %s", 'max_length' => "The %s field can not exceed 75 characters in length."));
        $this->form_validation->set_rules('address2_pr', 'Address 2', 'trim|max_length[75]|xss_clean', array('max_length' => "The %s field can not exceed 75 characters in length."));
        $this->form_validation->set_rules('address3_pr', 'Address 3', 'trim|max_length[75]|xss_clean', array('max_length' => "The %s field can not exceed 75 characters in length."));
        $this->form_validation->set_rules('state_pr', 'State', 'trim|required|xss_clean', array('required' => "Please enter the %s", 'max_length' => "The %s field can not exceed 50 characters in length."));
        $this->form_validation->set_rules('city_pr', 'City', 'trim|required|xss_clean', array('required' => "Please select the %s"));
        $this->form_validation->set_rules('district_pr', 'District', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_numbers_and_space]|max_length[30]|xss_clean', array('required' => "Please enter the %s"));
        $this->form_validation->set_rules('pincode_pr', 'Pincode', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|callback_validation_check_valid_pincode_permenant[' . $this->input->post('state_pr') . ']|max_length[6]|xss_clean', array('required' => "Please enter the %s"));
      }

      if (checkEditableField('Mobile Number', $form_data[0]['candidate_id'])) {
        $this->form_validation->set_rules('mobile_no', 'mobile number', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|min_length[10]|max_length[10]|callback_validation_check_mobile_exist[' . $enc_candidate_id . ']|callback_check_email_mobile_otp_verification[mobile_no]|callback_fun_restrict_input[first_zero_not_allowed]|xss_clean', array('required' => "Please enter the %s"));
      }

      if (checkEditableField('Email Id', $form_data[0]['candidate_id'])) {
        $this->form_validation->set_rules('email_id', 'email id', 'trim|required|max_length[80]|valid_email|callback_validation_check_email_exist[' . $enc_candidate_id . ']|callback_check_email_mobile_otp_verification[email_id]|xss_clean', array('required' => "Please enter the %s"));
      }


      if (checkEditableField('Eligibility', $form_data[0]['candidate_id']) || $form_data[0]['kyc_eligibility_flag'] == 'N') 
      {
        $this->form_validation->set_rules('qualification', 'eligibility', 'trim|required|xss_clean', array('required' => "Please select the %s"));
        
        $qualification = $this->input->post('qualification');

        switch ($qualification) {
          case '1':
            $this->form_validation->set_rules('experience', 'experience', 'trim|required|xss_clean', array('required' => "Please select the %s"));
            $this->form_validation->set_rules('qualification_state', 'working/institution state', 'trim|required|xss_clean', array('required' => "Please select the %s"));
            break;
          case '2':
            $this->form_validation->set_rules('qualification_state', 'working/institution state', 'trim|required|xss_clean', array('required' => "Please select the %s"));
            break;
          case '3':
            $this->form_validation->set_rules('graduation_sem', 'semester', 'trim|required|xss_clean', array('required' => "Please select the %s"));
            $this->form_validation->set_rules('collage', 'college', 'trim|required|max_length[160]|xss_clean', array('required' => "Please select the %s", 'max_length' => "The %s field can not exceed 160 characters in length."));
            $this->form_validation->set_rules('university', 'university', 'trim|required|max_length[75]|xss_clean', array('required' => "Please select the %s", 'max_length' => "The %s field can not exceed 75 characters in length."));
            $this->form_validation->set_rules('qualification_state', 'working/institution state', 'trim|required|xss_clean', array('required' => "Please select the %s"));
            break;
          case '4':
            $this->form_validation->set_rules('post_graduation_sem', 'semester', 'trim|required|xss_clean', array('required' => "Please select the %s"));
            $this->form_validation->set_rules('collage', 'college', 'trim|required|max_length[160]|xss_clean', array('required' => "Please select the %s", 'max_length' => "The %s field can not exceed 160 characters in length."));
            $this->form_validation->set_rules('university', 'university', 'trim|required|max_length[75]|xss_clean', array('required' => "Please select the %s", 'max_length' => "The %s field can not exceed 75 characters in length."));
            $this->form_validation->set_rules('qualification_state', 'working/institution state', 'trim|required|xss_clean', array('required' => "Please select the %s"));
            break;
          default:

            break;
        }
      }

      if (checkEditableField('Aadhar Card', $form_data[0]['candidate_id']) || $form_data[0]['kyc_aadhar_flag'] == 'N') 
      {
        // Aadhar validation: required, numeric, 12 digits exact length
        $this->form_validation->set_rules('aadhar_no', 'Aadhar Card Number', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|min_length[12]|max_length[12]|callback_validation_check_aadhar_no_exist[' . $enc_candidate_id . ']|xss_clean', array(
          'required' => "Please enter the %s",
          'numeric' => "The %s field must contain only numbers",
          'max_length' => "The %s must be exactly 12 digits long."
        ));
      }

      if(checkEditableField('APAAR ID/ABC ID', $form_data[0]['candidate_id']) || $form_data[0]['kyc_apaar_flag'] == 'N')
      {
        // Assuming APAAR/ABC ID is also numeric and has a specific length (e.g., 12 digits, adjust as needed)
        $this->form_validation->set_rules('id_proof_number', 'APAAR ID/ABC ID', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|min_length[12]|max_length[12]|callback_validation_check_aapar_id_exist[' . $enc_candidate_id . ']|xss_clean', array(
          'required' => "Please enter the %s",
          'numeric' => "The %s field must contain only numbers",
          'max_length' => "The %s field can not exceed 12 digits long."
        ));
      }

      if (checkEditableField('Benchmark Disability', $form_data[0]['candidate_id'])) {
        $this->form_validation->set_rules('benchmark_disability', 'Person with Benchmark Disability', 'trim|required|xss_clean', array('required' => "Please select %s"));

        // Check if benchmark_disability is Yes before setting sub-field validation rules
        if ($this->input->post('benchmark_disability') == 'Y') {
          $this->form_validation->set_rules('visually_impaired', 'Visually impaired', 'trim|required|xss_clean', array('required' => "Please select %s"));
          $this->form_validation->set_rules('orthopedically_handicapped', 'Orthopedically Handicapped', 'trim|required|xss_clean', array('required' => "Please select %s"));
          $this->form_validation->set_rules('cerebral_palsy', 'Cerebral Palsy', 'trim|required|xss_clean', array('required' => "Please select %s"));
        }
      }

      $id_proof_file_req_flg = $aadhar_file_req_flg = $candidate_photo_req_flg = $candidate_sign_req_flg =  $declarationform_req_flg = $exp_certificate_req_flg = $institute_idproof_req_flg =  $qualification_certificate_file_req_flg = $vis_imp_cert_img_req_flg = $orth_han_cert_img_req_flg = $cer_palsy_cert_img_req_flg = $benchmark_ediited_on_flag = $img_ediited_on_flag = '';

      if ($form_data[0]['id_proof_file'] == "") {
        $id_proof_file_req_flg = 'required|';
      }
      if (isset($_POST['id_proof_file_cropper']) && $_POST['id_proof_file_cropper'] != "") {
        $id_proof_file_req_flg = '';
      }

      if ($form_data[0]['aadhar_file'] == "") {
        $aadhar_file_req_flg = 'required|';
      }
      if (isset($_POST['aadhar_file_cropper']) && $_POST['aadhar_file_cropper'] != "") {
        $aadhar_file_req_flg = '';
      }

      if ($form_data[0]['candidate_photo'] == "") {
        $candidate_photo_req_flg = 'required|';
      }
      if (isset($_POST['candidate_photo_cropper']) && $_POST['candidate_photo_cropper'] != "") {
        $candidate_photo_req_flg = '';
      }

      if ($form_data[0]['candidate_sign'] == "") {
        $candidate_sign_req_flg = 'required|';
      }
      if (isset($_POST['candidate_sign_cropper']) && $_POST['candidate_sign_cropper'] != "") {
        $candidate_sign_req_flg = '';
      }


      if ((isset($_POST['qualification']) && ($_POST['qualification'] == 3 || $_POST['qualification'] == 4)) || ($form_data[0]['declarationform'] == "" && ($form_data[0]['qualification'] == 3 || $form_data[0]['qualification'] == 4))) {
        if ($form_data[0]['declarationform'] == "") {
          $declarationform_req_flg = 'required|';
        }
        if (isset($_POST['declarationform_cropper']) && $_POST['declarationform_cropper'] != "") {
          $declarationform_req_flg = '';
        }
        $this->form_validation->set_rules('declarationform', 'declaration of the candidate', 'trim|' . $declarationform_req_flg . 'xss_clean');
      }

      if ((isset($_POST['qualification']) && ($_POST['qualification'] == 3 || $_POST['qualification'] == 4)) || ($form_data[0]['institute_idproof'] == "" && ($form_data[0]['qualification'] == 3 || $form_data[0]['qualification'] == 4))) {
        if ($form_data[0]['institute_idproof'] == "" && $form_data[0]['kyc_institute_idproof_flag'] == "N") {
          $institute_idproof_req_flg = 'required|';
        }
        if (isset($_POST['institute_idproof_cropper']) && $_POST['institute_idproof_cropper'] != "") {
          $institute_idproof_req_flg = '';
        }
        $this->form_validation->set_rules('institute_idproof', 'institutional id proof of the candidate', 'trim|' . $institute_idproof_req_flg . 'xss_clean');
      }

      if ((isset($_POST['qualification']) && ($_POST['qualification'] == 1 || $_POST['qualification'] == 2)) || (($form_data[0]['qualification'] == 1 || $form_data[0]['qualification'] == 2) && $form_data[0]['qualification_certificate_file'] == "")) {
        if ($form_data[0]['qualification_certificate_file'] == "") {
          $qualification_certificate_file_req_flg = 'required|';
        }
        if (isset($_POST['qualification_certificate_file_cropper']) && $_POST['qualification_certificate_file_cropper'] != "") {
          $qualification_certificate_file_req_flg = '';
        }

        $this->form_validation->set_rules('qualification_certificate_file', 'qualification certificate of the candidate', 'trim|' . $qualification_certificate_file_req_flg . 'xss_clean');
      }

      if ((isset($_POST['qualification']) && $_POST['qualification'] == 1 && $_POST['experience'] == 'Y') || ($form_data[0]['exp_certificate'] == "" && $form_data[0]['qualification'] == 1 && $form_data[0]['experience'] == 'Y')) {
        if ($form_data[0]['exp_certificate'] == "") {
          $exp_certificate_req_flg = 'required|';
        }
        if (isset($_POST['exp_certificate_cropper']) && $_POST['exp_certificate_cropper'] != "") {
          $exp_certificate_req_flg = '';
        }
        $this->form_validation->set_rules('exp_certificate', 'experience certificate of the candidate', 'trim|' . $exp_certificate_req_flg . 'xss_clean');
      }

      if ((isset($_POST['benchmark_disability']) && ($_POST['benchmark_disability'] == 'Y' && $_POST['visually_impaired'] == 'Y')) || ($form_data[0]['vis_imp_cert_img'] == "" && $form_data[0]['benchmark_disability'] == 'Y' && $form_data[0]['visually_impaired'] == 'Y')) {
        if ($form_data[0]['vis_imp_cert_img'] == "") {
          $vis_imp_cert_img_req_flg = 'required|';
        }
        if (isset($_POST['vis_imp_cert_img_cropper']) && $_POST['vis_imp_cert_img_cropper'] != "") {
          $vis_imp_cert_img_req_flg = '';
        }

        $this->form_validation->set_rules('vis_imp_cert_img', 'visually impaired certificate of the candidate', 'trim|' . $vis_imp_cert_img_req_flg . 'xss_clean');
      }

      if ((isset($_POST['benchmark_disability']) && ($_POST['benchmark_disability'] == 'Y' && $_POST['orthopedically_handicapped'] == 'Y')) || ($form_data[0]['orth_han_cert_img'] == "" && $form_data[0]['benchmark_disability'] == 'Y' && $form_data[0]['orthopedically_handicapped'] == 'Y')) {
        if ($form_data[0]['orth_han_cert_img'] == "") {
          $orth_han_cert_img_req_flg = 'required|';
        }
        if (isset($_POST['orth_han_cert_img_cropper']) && $_POST['orth_han_cert_img_cropper'] != "") {
          $orth_han_cert_img_req_flg = '';
        }
        $this->form_validation->set_rules('orth_han_cert_img', 'orthopedically handicapped certificate of the candidate', 'trim|' . $orth_han_cert_img_req_flg . 'xss_clean');
      }

      if ((isset($_POST['benchmark_disability']) && ($_POST['benchmark_disability'] == 'Y' && $_POST['cerebral_palsy'] == 'Y')) || ($form_data[0]['cer_palsy_cert_img'] == "" && $form_data[0]['benchmark_disability'] == 'Y' && $form_data[0]['cerebral_palsy'] == 'Y')) {
        if ($form_data[0]['cer_palsy_cert_img'] == "") {
          $cer_palsy_cert_img_req_flg = 'required|';
        }
        if (isset($_POST['cer_palsy_cert_img_cropper']) && $_POST['cer_palsy_cert_img_cropper'] != "") {
          $cer_palsy_cert_img_req_flg = '';
        }
        $this->form_validation->set_rules('cer_palsy_cert_img', 'cerebral palsy certificate of the candidate', 'trim|' . $cer_palsy_cert_img_req_flg . 'xss_clean');
      }

      // $this->form_validation->set_rules('mobile_no', 'mobile number', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|min_length[10]|max_length[10]|callback_validation_check_mobile_exist[' . $enc_candidate_id . ']|callback_fun_restrict_input[first_zero_not_allowed]|xss_clean', array('required' => "Please enter the %s"));
      // $this->form_validation->set_rules('alt_mobile_no', 'alternate mobile number', 'trim|callback_fun_restrict_input[allow_only_numbers]|min_length[10]|max_length[10]|callback_fun_restrict_input[first_zero_not_allowed]|xss_clean');
      // $this->form_validation->set_rules('email_id', 'email id', 'trim|required|max_length[80]|valid_email|callback_validation_check_email_exist[' . $enc_candidate_id . ']|xss_clean', array('required' => "Please enter the %s"));
      // $this->form_validation->set_rules('alt_email_id', 'alternate email id', 'trim|max_length[80]|valid_email|xss_clean');
      // $this->form_validation->set_rules('address1', 'address line-1', 'trim|required|max_length[75]|xss_clean', array('required' => "Please enter the %s"));
      //   $this->form_validation->set_rules('address2', 'address line-2', 'trim|max_length[75]|xss_clean');
      //   $this->form_validation->set_rules('address3', 'address line-3', 'trim|max_length[75]|xss_clean');
      //   $this->form_validation->set_rules('address4', 'address line-4', 'trim|max_length[75]|xss_clean');
      // $this->form_validation->set_rules('state', 'state', 'trim|required|xss_clean', array('required' => "Please select the %s"));
      // $this->form_validation->set_rules('city', 'city', 'trim|required|xss_clean', array('required' => "Please select the %s"));
      // $this->form_validation->set_rules('district', 'district', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_numbers_and_space]|max_length[30]|xss_clean', array('required' => "Please enter the %s"));
      // $this->form_validation->set_rules('pincode', 'pincode', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|callback_validation_check_valid_pincode[' . $this->input->post('state') . ']|xss_clean', array('required' => "Please enter the %s"));

      $this->form_validation->set_rules('id_proof_file', 'APAAR ID/ABC ID', 'trim|' . $id_proof_file_req_flg . 'xss_clean');
      $this->form_validation->set_rules('aadhar_file', 'aadhar card', 'trim|' . $aadhar_file_req_flg . 'xss_clean');
      $this->form_validation->set_rules('candidate_photo', 'passport-size photo of the candidate', 'trim|' . $candidate_photo_req_flg . 'xss_clean');
      $this->form_validation->set_rules('candidate_sign', 'signature of the candidate', 'trim|' . $candidate_sign_req_flg . 'xss_clean');

      //$this->form_validation->set_rules('xxx', '', 'trim|required|xss_clean');
      if ($this->form_validation->run()) {
        $new_id_proof_file = $new_candidate_photo = $new_candidate_sign = $new_aadhar_file = '';

        $file_name_str = date("YmdHis") . '_' . rand(1000, 9999);

        if (isset($_POST['id_proof_file_cropper']) && $_POST['id_proof_file_cropper'] != "") {
          $id_proof_file_cropper = $this->security->xss_clean($this->input->post('id_proof_file_cropper'));
          $new_file_name1 = "pr_" . $file_name_str . '.' . strtolower(pathinfo($id_proof_file_cropper, PATHINFO_EXTENSION));
          if (copy(str_replace(base_url(), '', $id_proof_file_cropper), $id_proof_file_path . '/' . $new_file_name1)) {
            $add_data['id_proof_file'] = $id_proof_file = $new_id_proof_file = basename($new_file_name1);

            if ($form_data[0]['kyc_status'] != '0') {
              $img_ediited_on_flag = '1';
            }
            if ($form_data[0]['kyc_id_card_flag'] == "Y") {
              $add_data['kyc_id_card_flag'] = "";
            }
          } else {
            $data['id_proof_file_error'] = 'Please upload valid Proof of Identity';
            $error_flag = 1;
          }
        }

        if (isset($_POST['aadhar_file_cropper']) && $_POST['aadhar_file_cropper'] != "") {
          $aadhar_file_cropper = $this->security->xss_clean($this->input->post('aadhar_file_cropper'));
          $new_file_name2 = "aadhar_" . $file_name_str . '.' . strtolower(pathinfo($aadhar_file_cropper, PATHINFO_EXTENSION));
          if (copy(str_replace(base_url(), '', $aadhar_file_cropper), $aadhar_file_path . '/' . $new_file_name2)) {
            $add_data['aadhar_file'] = $aadhar_file = $new_aadhar_file = basename($new_file_name2);

            if ($form_data[0]['kyc_status'] != '0') {
              $img_ediited_on_flag = '1';
            }
            if ($form_data[0]['kyc_aadhar_file_flag'] == "Y") {
              $add_data['kyc_aadhar_file_flag'] = "";
            }
          } else {
            $data['aadhar_file_error'] = 'Please upload valid Proof of Identity';
            $error_flag = 1;
          }
        }

        if (isset($_POST['candidate_photo_cropper']) && $_POST['candidate_photo_cropper'] != "") {
          $candidate_photo_cropper = $this->security->xss_clean($this->input->post('candidate_photo_cropper'));

          $new_file_name3 = "p_" . $file_name_str . '.' . strtolower(pathinfo($candidate_photo_cropper, PATHINFO_EXTENSION));
          if (copy(str_replace(base_url(), '', $candidate_photo_cropper), $candidate_photo_path . '/' . $new_file_name3)) {
            $add_data['candidate_photo'] = $candidate_photo = $new_candidate_photo = basename($new_file_name3);
            if ($form_data[0]['kyc_status'] != '0') {
              $img_ediited_on_flag = '1';
            }
            if ($form_data[0]['kyc_photo_flag'] == "Y") {
              $add_data['kyc_photo_flag'] = "";
            }
          } else {
            $data['candidate_photo_error'] = 'Please upload valid Passport-size Photo';
            $error_flag = 1;
          }
        }

        if (isset($_POST['candidate_sign_cropper']) && $_POST['candidate_sign_cropper'] != "") {
          $candidate_sign_cropper = $this->security->xss_clean($this->input->post('candidate_sign_cropper'));
          $new_file_name4 = "s_" . $file_name_str . '.' . strtolower(pathinfo($candidate_sign_cropper, PATHINFO_EXTENSION));
          if (copy(str_replace(base_url(), '', $candidate_sign_cropper), $candidate_sign_path . '/' . $new_file_name4)) {
            $add_data['candidate_sign'] = $candidate_sign = $new_candidate_sign = basename($new_file_name4);
            if ($form_data[0]['kyc_status'] != '0') {
              $img_ediited_on_flag = '1';
            }
            if ($form_data[0]['kyc_sign_flag'] == "Y") {
              $add_data['kyc_sign_flag'] = "";
            }
          } else {
            $data['candidate_sign_error'] = 'Please upload valid Signature of the Candidate';
            $error_flag = 1;
          }
        }

        if ((isset($_POST['qualification']) && ($_POST['qualification'] == 3 || $_POST['qualification'] == 4)) || ($form_data[0]['declarationform'] == '' && ($form_data[0]['qualification'] == 3 || $form_data[0]['qualification'] == 4))) {
          if (isset($_POST['declarationform_cropper']) && $_POST['declarationform_cropper'] != "") {
            $declarationform_cropper = $this->security->xss_clean($this->input->post('declarationform_cropper'));
            $new_file_name5 = "declaration_" . $file_name_str . '.' . strtolower(pathinfo($declarationform_cropper, PATHINFO_EXTENSION));

            if (copy(str_replace(base_url(), '', $declarationform_cropper), $declarationform_path . '/' . $new_file_name5)) {
              $add_data['declarationform'] = $declarationform = $new_declarationform = basename($new_file_name5);
              if ($form_data[0]['kyc_status'] != '0') {
                $img_ediited_on_flag = '1';
              }
              if ($form_data[0]['kyc_declaration_flag'] == "Y") {
                $add_data['kyc_declaration_flag'] = "";
              }
            } else {
              $data['declarationform_error'] = 'Please upload valid Declaration of the Candidate';
              $error_flag = 1;
            }
          }
        }

        if ((isset($_POST['qualification']) && ($_POST['qualification'] == 1 || $_POST['qualification'] == 2)) || ($form_data[0]['qualification_certificate_file'] == '' && ($form_data[0]['qualification'] == 1 || $form_data[0]['qualification'] == 2))) {
          if (isset($_POST['qualification_certificate_file_cropper']) && $_POST['qualification_certificate_file_cropper'] != "") {
            $qualification_certificate_file_cropper = $this->security->xss_clean($this->input->post('qualification_certificate_file_cropper'));
            $new_file_name6 = "qual_" . $file_name_str . '.' . strtolower(pathinfo($qualification_certificate_file_cropper, PATHINFO_EXTENSION));

            if (copy(str_replace(base_url(), '', $qualification_certificate_file_cropper), $qualification_certificate_file_path . '/' . $new_file_name6)) {
              $add_data['qualification_certificate_file'] = $qualification_certificate_file = $new_qualification_certificate_file = basename($new_file_name6);
              if ($form_data[0]['kyc_status'] != '0') {
                $img_ediited_on_flag = '1';
              }
              if ($form_data[0]['kyc_qualification_cert_flag'] == "Y") {
                $add_data['kyc_qualification_cert_flag'] = "";
              }
            } else {
              $data['qualification_certificate_file_error'] = 'Please upload valid Qualification Certificate of the Candidate';
              $error_flag = 1;
            }
          }
        }

        if ((isset($_POST['qualification']) && $_POST['qualification'] == 1 && $_POST['experience'] == 'Y') || ($form_data[0]['exp_certificate'] == '' && $form_data[0]['qualification'] == 1 && $form_data[0]['experience'] == 'Y')) {
          if (isset($_POST['exp_certificate_cropper']) && $_POST['exp_certificate_cropper'] != "") {
            $exp_certificate_cropper = $this->security->xss_clean($this->input->post('exp_certificate_cropper'));
            $new_file_name7 = "exp_" . $file_name_str . '.' . strtolower(pathinfo($exp_certificate_cropper, PATHINFO_EXTENSION));

            if (copy(str_replace(base_url(), '', $exp_certificate_cropper), $exp_certificate_path . '/' . $new_file_name7)) {
              $add_data['exp_certificate'] = $exp_certificate = $new_exp_certificate = basename($new_file_name7);
              if ($form_data[0]['kyc_status'] != '0') {
                $img_ediited_on_flag = '1';
              }
              if ($form_data[0]['kyc_exp_certificate_flag'] == "Y") {
                $add_data['kyc_exp_certificate_flag'] = "";
              }
            } else {
              $data['exp_certificate_error'] = 'Please upload valid Experience Certificate of the Candidate';
              $error_flag = 1;
            }
          }
        }

        if ((isset($_POST['qualification']) && ($_POST['qualification'] == 3 || $_POST['qualification'] == 4)) || ($form_data[0]['institute_idproof'] == '' && ($form_data[0]['qualification'] == 3 || $form_data[0]['qualification'] == 4))) {
          if (isset($_POST['institute_idproof_cropper']) && $_POST['institute_idproof_cropper'] != "") {
            $institute_idproof_cropper = $this->security->xss_clean($this->input->post('institute_idproof_cropper'));
            $new_file_name8 = "institute_idproof_" . $file_name_str . '.' . strtolower(pathinfo($institute_idproof_cropper, PATHINFO_EXTENSION));

            if (copy(str_replace(base_url(), '', $institute_idproof_cropper), $institute_idproof_path . '/' . $new_file_name8)) {
              $add_data['institute_idproof'] = $institute_idproof = $new_institute_idproof = basename($new_file_name8);
              if ($form_data[0]['kyc_status'] != '0') {
                $img_ediited_on_flag = '1';
              }
              if ($form_data[0]['kyc_institute_idproof_flag'] == "Y") {
                $add_data['kyc_institute_idproof_flag'] = "";
              }
            } else {
              $data['institute_idproof_error'] = 'Please upload valid Institutional ID Proof of the Candidate';
              $error_flag = 1;
            }
          }
        }

        if ((isset($_POST['visually_impaired']) && ($_POST['benchmark_disability'] == 'Y' && $_POST['visually_impaired'] == 'Y')) || ($form_data[0]['vis_imp_cert_img'] == '' && ($form_data[0]['benchmark_disability'] == 'Y' || $form_data[0]['visually_impaired'] == 'Y'))) {
          if (isset($_POST['vis_imp_cert_img_cropper']) && $_POST['vis_imp_cert_img_cropper'] != "") {
            $vis_imp_cert_img_cropper = $this->security->xss_clean($this->input->post('vis_imp_cert_img_cropper'));
            $new_file_name9 = "v_" . $file_name_str . '.' . strtolower(pathinfo($vis_imp_cert_img_cropper, PATHINFO_EXTENSION));

            if (copy(str_replace(base_url(), '', $vis_imp_cert_img_cropper), $disability_cert_img_path . '/' . $new_file_name9)) {
              $add_data['vis_imp_cert_img'] = $vis_imp_cert_img = $new_vis_imp_cert_img = basename($new_file_name9);
              if ($form_data[0]['kyc_status'] != '0') {
                $img_ediited_on_flag = '1';
              }
              if ($form_data[0]['benchmark_kyc_status'] != '0') {
                $benchmark_ediited_on_flag = '1';
              }
              if ($form_data[0]['kyc_vis_imp_cert_flag'] == "Y") {
                $add_data['kyc_vis_imp_cert_flag'] = "";
              }
            } else {
              $data['vis_imp_cert_img_error'] = 'Please upload valid visually impaired certificate of the Candidate';
              $error_flag = 1;
            }
          }
        }

        if ((isset($_POST['orthopedically_handicapped']) && ($_POST['benchmark_disability'] == 'Y' && $_POST['orthopedically_handicapped'] == 'Y')) || ($form_data[0]['orth_han_cert_img'] == '' && ($form_data[0]['benchmark_disability'] == 'Y' || $form_data[0]['orthopedically_handicapped'] == 'Y'))) {
          if (isset($_POST['orth_han_cert_img_cropper']) && $_POST['orth_han_cert_img_cropper'] != "") {
            $orth_han_cert_img_cropper = $this->security->xss_clean($this->input->post('orth_han_cert_img_cropper'));
            $new_file_name10 = "o_" . $file_name_str . '.' . strtolower(pathinfo($orth_han_cert_img_cropper, PATHINFO_EXTENSION));

            if (copy(str_replace(base_url(), '', $orth_han_cert_img_cropper), $disability_cert_img_path . '/' . $new_file_name10)) {
              $add_data['orth_han_cert_img'] = $orth_han_cert_img = $new_orth_han_cert_img = basename($new_file_name10);
              if ($form_data[0]['kyc_status'] != '0') {
                $img_ediited_on_flag = '1';
              }
              if ($form_data[0]['benchmark_kyc_status'] != '0') {
                $benchmark_ediited_on_flag = '1';
              }
              if ($form_data[0]['kyc_orth_han_cert_flag'] == "Y") {
                $add_data['kyc_orth_han_cert_flag'] = "";
              }
            } else {
              $data['orth_han_cert_img_error'] = 'Please upload valid orthopedically handicapped certificate of the Candidate';
              $error_flag = 1;
            }
          }
        }

        if ((isset($_POST['cerebral_palsy']) && ($_POST['benchmark_disability'] == 'Y' && $_POST['cerebral_palsy'] == 'Y')) || ($form_data[0]['cer_palsy_cert_img'] == '' && ($form_data[0]['benchmark_disability'] == 'Y' || $form_data[0]['cerebral_palsy'] == 'Y'))) {
          if (isset($_POST['cer_palsy_cert_img_cropper']) && $_POST['cer_palsy_cert_img_cropper'] != "") {
            $cer_palsy_cert_img_cropper = $this->security->xss_clean($this->input->post('cer_palsy_cert_img_cropper'));
            $new_file_name11 = "c_" . $file_name_str . '.' . strtolower(pathinfo($cer_palsy_cert_img_cropper, PATHINFO_EXTENSION));

            if (copy(str_replace(base_url(), '', $cer_palsy_cert_img_cropper), $disability_cert_img_path . '/' . $new_file_name11)) {
              $add_data['cer_palsy_cert_img'] = $cer_palsy_cert_img = $new_cer_palsy_cert_img = basename($new_file_name11);
              if ($form_data[0]['kyc_status'] != '0') {
                $img_ediited_on_flag = '1';
              }
              if ($form_data[0]['benchmark_kyc_status'] != '0') {
                $benchmark_ediited_on_flag = '1';
              }
              if ($form_data[0]['kyc_cer_palsy_cert_flag'] == "Y") {
                $add_data['kyc_cer_palsy_cert_flag'] = "";
              }
            } else {
              $data['cer_palsy_cert_img_error'] = 'Please upload valid cerebral palsy certificate of the Candidate';
              $error_flag = 1;
            }
          }
        }

        if ($error_flag == 0) {
          $posted_arr = json_encode($_POST) . ' >> ' . json_encode($_FILES);
          $candidateName = $this->Ncvet_model->getLoggedInUserDetails($candidate_id, 'candidate');

          //START : IF FILE NOT EXIST WHILE ADDING / UPDATING THE RECORD, THEN REDIRECT & SHOW ERROR MESSAGE
          $chk_id_proof_file = $chk_candidate_photo = $chk_candidate_sign = '';
          if ($new_id_proof_file == '') {
            $chk_id_proof_file = $form_data[0]['id_proof_file'];
          } else if ($new_id_proof_file != '') {
            $chk_id_proof_file = $new_id_proof_file;
          }

          $chk_aadhar_file = '';
          if ($new_aadhar_file == '') {
            $chk_aadhar_file = $form_data[0]['aadhar_file'];
          } else if ($new_aadhar_file != '') {
            $chk_aadhar_file = $new_aadhar_file;
          }

          if ($new_candidate_photo == '') {
            $chk_candidate_photo = $form_data[0]['candidate_photo'];
          } else if ($new_candidate_photo != '') {
            $chk_candidate_photo = $new_candidate_photo;
          }

          if ($new_candidate_sign == '') {
            $chk_candidate_sign = $form_data[0]['candidate_sign'];
          } else if ($new_candidate_sign != '') {
            $chk_candidate_sign = $new_candidate_sign;
          }

          if ((isset($_POST['qualification']) && ($_POST['qualification'] == 3 || $_POST['qualification'] == 4)) || ($form_data[0]['declarationform'] == '' && ($form_data[0]['qualification'] == 3 || $form_data[0]['qualification'] == 4))) {
            $chk_declarationform = '';
            if ($new_declarationform == '') {
              $chk_declarationform = $form_data[0]['declarationform'];
            } else if ($new_declarationform != '') {
              $chk_declarationform = $new_declarationform;
            }
          }

          if ((isset($_POST['qualification']) && ($_POST['qualification'] == 1 || $_POST['qualification'] == 2)) || ($form_data[0]['qualification_certificate_file'] == '' && ($form_data[0]['qualification'] == 1 || $form_data[0]['qualification'] == 2))) {
            $chk_qualification_certificate_file = '';
            if ($new_qualification_certificate_file == '') {
              $chk_qualification_certificate_file = $form_data[0]['qualification_certificate_file'];
            } else if ($new_qualification_certificate_file != '') {
              $chk_qualification_certificate_file = $new_qualification_certificate_file;
            }
          }

          if ((isset($_POST['qualification']) && $_POST['qualification'] == 1 && $_POST['experience'] == 'Y') || ($form_data[0]['exp_certificate'] == '' && $form_data[0]['qualification'] == 1 && $form_data[0]['experience'] == 'Y')) {
            $chk_exp_certificate = '';
            if ($new_exp_certificate == '') {
              $chk_exp_certificate = $form_data[0]['exp_certificate'];
            } else if ($new_exp_certificate != '') {
              $chk_exp_certificate = $new_exp_certificate;
            }
          }

          if ((isset($_POST['qualification']) && ($_POST['qualification'] == 3 || $_POST['qualification'] == 4)) || ($form_data[0]['institute_idproof'] == '' && ($form_data[0]['qualification'] == 3 || $form_data[0]['qualification'] == 4))) {
            $chk_institute_idproof = '';
            if ($new_institute_idproof == '') {
              $chk_institute_idproof = $form_data[0]['institute_idproof'];
            } else if ($new_institute_idproof != '') {
              $chk_institute_idproof = $new_institute_idproof;
            }
          }

          if ((isset($_POST['visually_impaired']) && ($_POST['benchmark_disability'] == 'Y' && $_POST['visually_impaired'] == 'Y')) || ($form_data[0]['vis_imp_cert_img'] == '' && ($form_data[0]['benchmark_disability'] == 'Y' || $form_data[0]['visually_impaired'] == 'Y'))) {
            $chk_vis_imp_cert_img = '';
            if ($new_vis_imp_cert_img == '') {
              $chk_vis_imp_cert_img = $form_data[0]['vis_imp_cert_img'];
            } else if ($new_vis_imp_cert_img != '') {
              $chk_vis_imp_cert_img = $new_vis_imp_cert_img;
            }
          }

          if ((isset($_POST['orthopedically_handicapped']) && ($_POST['benchmark_disability'] == 'Y' && $_POST['orthopedically_handicapped'] == 'Y')) || ($form_data[0]['orth_han_cert_img'] == '' && ($form_data[0]['benchmark_disability'] == 'Y' || $form_data[0]['orthopedically_handicapped'] == 'Y'))) {
            $chk_orth_han_cert_img = '';
            if ($new_orth_han_cert_img == '') {
              $chk_orth_han_cert_img = $form_data[0]['orth_han_cert_img'];
            } else if ($new_orth_han_cert_img != '') {
              $chk_orth_han_cert_img = $new_orth_han_cert_img;
            }
          }

          if ((isset($_POST['cerebral_palsy']) && ($_POST['benchmark_disability'] == 'Y' && $_POST['cerebral_palsy'] == 'Y')) || ($form_data[0]['cer_palsy_cert_img'] == '' && ($form_data[0]['benchmark_disability'] == 'Y' || $form_data[0]['cerebral_palsy'] == 'Y'))) {
            $chk_cer_palsy_cert_img = '';
            if ($new_cer_palsy_cert_img == '') {
              $chk_cer_palsy_cert_img = $form_data[0]['cer_palsy_cert_img'];
            } else if ($new_cer_palsy_cert_img != '') {
              $chk_cer_palsy_cert_img = $new_cer_palsy_cert_img;
            }
          }

          //START : IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN REDIRECT & SHOW ERROR MESSAGE
          $this->Ncvet_model->check_file_exist($chk_id_proof_file, "./" . $id_proof_file_path . "/", 'ncvet/candidate/dashboard_candidate/update_profile', 'Candidate profile not updated due to missing APAAR ID/ABC ID'); //IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN SHOW ERROR MESSAGE

          //START : IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN REDIRECT & SHOW ERROR MESSAGE
          $this->Ncvet_model->check_file_exist($chk_aadhar_file, "./" . $aadhar_file_path . "/", 'ncvet/candidate/dashboard_candidate/update_profile', 'Candidate profile not updated due to missing Aadhar Card'); //IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN SHOW ERROR MESSAGE

          $this->Ncvet_model->check_file_exist($chk_candidate_photo, "./" . $candidate_photo_path . "/", 'ncvet/candidate/dashboard_candidate/update_profile', 'Candidate profile not updated due to missing Passport Photograph of the Candidate'); //IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN SHOW ERROR MESSAGE

          $this->Ncvet_model->check_file_exist($chk_candidate_sign, "./" . $candidate_sign_path . "/", 'ncvet/candidate/dashboard_candidate/update_profile', 'Candidate profile not updated due to missing Signature of the Candidate'); //IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN SHOW ERROR MESSAGE
          //END : IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN REDIRECT & SHOW ERROR MESSAGE

          if ((isset($_POST['qualification']) && ($_POST['qualification'] == 3 || $_POST['qualification'] == 4)) || ($form_data[0]['declarationform'] == '' && ($form_data[0]['qualification'] == 3 || $form_data[0]['qualification'] == 4))) {
            $this->Ncvet_model->check_file_exist($chk_declarationform, "./" . $declarationform_path . "/", 'ncvet/candidate/dashboard_candidate/update_profile', 'Candidate profile not updated due to missing Declaration of the Candidate'); //IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN SHOW ERROR MESSAGE
            //END : IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN REDIRECT & SHOW ERROR MESSAGE            
          }

          if ((isset($_POST['qualification']) && ($_POST['qualification'] == 1 || $_POST['qualification'] == 2)) || ($form_data[0]['qualification_certificate_file'] == '' && ($form_data[0]['qualification'] == 1 || $form_data[0]['qualification'] == 2))) {
            $this->Ncvet_model->check_file_exist($chk_qualification_certificate_file, "./" . $qualification_certificate_file_path . "/", 'ncvet/candidate/dashboard_candidate/update_profile', 'Candidate profile not updated due to missing Qualification Certificate of the Candidate'); //IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN SHOW ERROR MESSAGE
            //END : IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN REDIRECT & SHOW ERROR MESSAGE            
          }

          if ((isset($_POST['qualification']) && $_POST['qualification'] == 1 && $_POST['experience'] == 'Y') || ($form_data[0]['exp_certificate'] == '' && $form_data[0]['qualification'] == 1 && $form_data[0]['experience'] == 'Y')) {
            $this->Ncvet_model->check_file_exist($chk_exp_certificate, "./" . $exp_certificate_path . "/", 'ncvet/candidate/dashboard_candidate/update_profile', 'Candidate profile not updated due to missing Experience Certificate of the Candidate'); //IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN SHOW ERROR MESSAGE
            //END : IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN REDIRECT & SHOW ERROR MESSAGE            
          }

          if ((isset($_POST['qualification']) && ($_POST['qualification'] == 3 || $_POST['qualification'] == 4)) || ($form_data[0]['institute_idproof'] == '' && ($form_data[0]['qualification'] == 3 || $form_data[0]['qualification'] == 4))) {
            $this->Ncvet_model->check_file_exist($chk_institute_idproof, "./" . $institute_idproof_path . "/", 'ncvet/candidate/dashboard_candidate/update_profile', 'Candidate profile not updated due to missing Institutional ID Proof of the Candidate'); //IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN SHOW ERROR MESSAGE
            //END : IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN REDIRECT & SHOW ERROR MESSAGE            
          }

          if ((isset($_POST['visually_impaired']) && ($_POST['benchmark_disability'] == 'Y' && $_POST['visually_impaired'] == 'Y')) || ($form_data[0]['vis_imp_cert_img'] == '' && ($form_data[0]['benchmark_disability'] == 'Y' || $form_data[0]['visually_impaired'] == 'Y'))) {
            $this->Ncvet_model->check_file_exist($chk_vis_imp_cert_img, "./" . $disability_cert_img_path . "/", 'ncvet/candidate/dashboard_candidate/update_profile', 'Candidate profile not updated due to missing visually impaired certificate of the Candidate'); //IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN SHOW ERROR MESSAGE
            //END : IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN REDIRECT & SHOW ERROR MESSAGE
          }

          if ((isset($_POST['orthopedically_handicapped']) && ($_POST['benchmark_disability'] == 'Y' && $_POST['orthopedically_handicapped'] == 'Y')) || ($form_data[0]['orth_han_cert_img'] == '' && ($form_data[0]['benchmark_disability'] == 'Y' || $form_data[0]['orthopedically_handicapped'] == 'Y'))) {
            $this->Ncvet_model->check_file_exist($chk_orth_han_cert_img, "./" . $disability_cert_img_path . "/", 'ncvet/candidate/dashboard_candidate/update_profile', 'Candidate profile not updated due to missing orthopedically handicapped certificate of the Candidate'); //IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN SHOW ERROR MESSAGE
            //END : IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN REDIRECT & SHOW ERROR MESSAGE
          }

          if ((isset($_POST['cerebral_palsy']) && ($_POST['benchmark_disability'] == 'Y' && $_POST['cerebral_palsy'] == 'Y')) || ($form_data[0]['cer_palsy_cert_img'] == '' && ($form_data[0]['benchmark_disability'] == 'Y' || $form_data[0]['cerebral_palsy'] == 'Y'))) {
            $this->Ncvet_model->check_file_exist($chk_cer_palsy_cert_img, "./" . $disability_cert_img_path . "/", 'ncvet/candidate/dashboard_candidate/update_profile', 'Candidate profile not updated due to missing cerebral palsy certificate of the Candidate'); //IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN SHOW ERROR MESSAGE
            //END : IF FILE NOT EXIST WHILE UPDATING THE PROFILE, THEN REDIRECT & SHOW ERROR MESSAGE
          }

          if (checkEditableField('Candidate Name', $candidate_id) || $form_data[0]['kyc_fullname_flag'] == 'N') 
          {
            $salutation  = $add_data['salutation']  = $this->security->xss_clean($this->input->post('salutation'));
            $first_name  = $add_data['first_name']  = $this->security->xss_clean($this->input->post('first_name'));
            $middle_name = $add_data['middle_name'] = $this->security->xss_clean($this->input->post('middle_name'));
            $last_name   = $add_data['last_name']   = $this->security->xss_clean($this->input->post('last_name'));
            
            if (checkEditableField('Candidate Name', $candidate_id)) 
            {
              $add_data['gender']      = $this->security->xss_clean($this->input->post('gender'));  
            }  

            if ($salutation != $form_data[0]['salutation'] || $first_name != $form_data[0]['first_name'] || $middle_name != $form_data[0]['middle_name'] || $last_name != $form_data[0]['last_name']) 
            {
              $img_ediited_on_flag = '1';
              $add_data['kyc_fullname_flag'] = '';              
            }
          }

          if (checkEditableField('Date of Birth', $form_data[0]['candidate_id']) || $form_data[0]['kyc_dob_flag'] == 'N')
          {
            $dob = $add_data['dob'] = $this->security->xss_clean($this->input->post('dob'));
            if ($dob != $form_data[0]['dob']) 
            {
              $img_ediited_on_flag = '1';    
              $add_data['kyc_dob_flag'] = '';         
            }
          }

          if (checkEditableField('Guardian Name', $candidate_id)) {
            $add_data['guardian_salutation'] = $this->security->xss_clean($this->input->post('guardian_salutation'));
            $add_data['guardian_name']       = $this->security->xss_clean($this->input->post('guardian_name'));
          }

          if (checkEditableField('Communication Address', $candidate_id)) {
            $add_data['address1'] = $this->security->xss_clean($this->input->post('address1'));
            $add_data['address2'] = $this->security->xss_clean($this->input->post('address2'));
            $add_data['address3'] = $this->security->xss_clean($this->input->post('address3'));
            $add_data['state']    = $this->security->xss_clean($this->input->post('state'));
            $add_data['city']     = $this->security->xss_clean($this->input->post('city'));
            $add_data['district'] = $this->security->xss_clean($this->input->post('district'));
            $add_data['pincode']  = $this->security->xss_clean($this->input->post('pincode'));
          }

          if (checkEditableField('Permanant Address', $candidate_id)) {
            $add_data['address1_pr'] = $this->security->xss_clean($this->input->post('address1_pr'));
            $add_data['address2_pr'] = $this->security->xss_clean($this->input->post('address2_pr'));
            $add_data['address3_pr'] = $this->security->xss_clean($this->input->post('address3_pr'));
            $add_data['state_pr']    = $this->security->xss_clean($this->input->post('state_pr'));
            $add_data['city_pr']     = $this->security->xss_clean($this->input->post('city_pr'));
            $add_data['district_pr'] = $this->security->xss_clean($this->input->post('district_pr'));
            $add_data['pincode_pr']  = $this->security->xss_clean($this->input->post('pincode_pr'));
          }

          if (checkEditableField('Mobile Number', $form_data[0]['candidate_id'])) {
            $add_data['mobile_no']  = $this->security->xss_clean($this->input->post('mobile_no'));
          }

          if (checkEditableField('Email Id', $form_data[0]['candidate_id'])) {
            $add_data['email_id']  = $this->security->xss_clean($this->input->post('email_id'));
          }

          if (checkEditableField('Eligibility', $form_data[0]['candidate_id']) || $form_data[0]['kyc_eligibility_flag'] == 'N') 
          {
            $add_data['qualification'] = $qualification = $this->security->xss_clean($this->input->post('qualification'));
            if ( $qualification != $form_data[0]['qualification'] ) {
              $img_ediited_on_flag = '1';
              $add_data['kyc_eligibility_flag'] = '';
            }

            if ($qualification == 1) {
              $add_data['experience'] = $experience = $this->security->xss_clean($this->input->post('experience'));
              $add_data['qualification_state'] = $qualification_state = $this->security->xss_clean($this->input->post('qualification_state'));
              if ( $experience != $form_data[0]['experience'] || $qualification_state != $form_data[0]['qualification_state'] ) {
                $img_ediited_on_flag = '1';
                $add_data['kyc_eligibility_flag'] = '';
              } 
            }

            if ($qualification == 2) {
              $add_data['qualification_state'] = $qualification_state = $this->security->xss_clean($this->input->post('qualification_state'));
              if ( $qualification_state != $form_data[0]['qualification_state'] ) {
                $img_ediited_on_flag = '1';
                $add_data['kyc_eligibility_flag'] = '';
              }
            }

            if ($qualification == 3 || $qualification == 4) {           
              $add_data['qualification_state'] = $qualification_state = $this->security->xss_clean($this->input->post('qualification_state'));
              if ($qualification == 3) {
                $add_data['semester'] = $semester = $this->security->xss_clean($this->input->post('graduation_sem'));
                if ( $semester != $form_data[0]['semester'] ) {
                  $img_ediited_on_flag = '1';
                  $add_data['kyc_eligibility_flag'] = '';
                }
              }

              if ($qualification == 4) {
                $add_data['semester'] = $semester = $this->security->xss_clean($this->input->post('post_graduation_sem'));
                if ( $semester != $form_data[0]['semester'] ) {
                  $img_ediited_on_flag = '1';
                  $add_data['kyc_eligibility_flag'] = '';
                }
              }

              $add_data['collage']    = $collage    = $this->security->xss_clean($this->input->post('collage'));
              $add_data['university'] = $university =  $this->security->xss_clean($this->input->post('university'));

              if ( $collage != $form_data[0]['collage'] || $university != $form_data[0]['university'] || $qualification_state != $form_data[0]['qualification_state'] ) {
                $img_ediited_on_flag = '1';
                $add_data['kyc_eligibility_flag'] = '';
              }
            }
          }

          if (checkEditableField('Aadhar Card', $candidate_id) || $form_data[0]['kyc_aadhar_flag'] == 'N') {
            $add_data['aadhar_no'] = $aadhar_no = $this->security->xss_clean($this->input->post('aadhar_no'));
            if ( $aadhar_no != $form_data[0]['aadhar_no'] ) {
              $img_ediited_on_flag = '1';
              $add_data['kyc_aadhar_flag'] = '';
            }
          }

          if (checkEditableField('APAAR ID/ABC ID', $candidate_id) || $form_data[0]['kyc_apaar_flag'] == 'N') {
            $add_data['id_proof_number'] = $id_proof_number = $this->security->xss_clean($this->input->post('id_proof_number'));
            if ( $id_proof_number != $form_data[0]['id_proof_number'] ) {
              $img_ediited_on_flag = '1';
              $add_data['kyc_apaar_flag'] = '';
            }
          }

          if (checkEditableField('Benchmark Disability', $candidate_id)) {
            $benchmark_disability = $this->security->xss_clean($this->input->post('benchmark_disability'));
            $add_data['benchmark_disability'] = $benchmark_disability;

            // Only save sub-disabilities if benchmark_disability is 'Y'
            if ($benchmark_disability == 'Y') {
              $add_data['visually_impaired'] = $visually_impaired = $this->security->xss_clean($this->input->post('visually_impaired'));
              $add_data['orthopedically_handicapped'] = $orthopedically_handicapped = $this->security->xss_clean($this->input->post('orthopedically_handicapped'));
              $add_data['cerebral_palsy'] = $cerebral_palsy = $this->security->xss_clean($this->input->post('cerebral_palsy'));

              if ($visually_impaired == 'N') {
                $add_data['vis_imp_cert_img'] = '';
                if ($form_data[0]['vis_imp_cert_img'] != '') {
                  @unlink("./" . $disability_cert_img_path . "/" . $form_data[0]['vis_imp_cert_img']);
                }
              }
              if ($orthopedically_handicapped == 'N') {
                if ($form_data[0]['orth_han_cert_img'] != '') {
                  @unlink("./" . $disability_cert_img_path . "/" . $form_data[0]['orth_han_cert_img']);
                }

                $add_data['orth_han_cert_img'] = '';
              }
              if ($cerebral_palsy == 'N') {
                if ($form_data[0]['cer_palsy_cert_img'] != '') {
                  @unlink("./" . $disability_cert_img_path . "/" . $form_data[0]['orth_han_cert_img']);
                }

                $add_data['cer_palsy_cert_img'] = '';
              }
            } else {
              // If 'No' is selected, reset sub-disabilities to 'N' in DB
              $add_data['visually_impaired'] = 'N';
              $add_data['orthopedically_handicapped'] = 'N';
              $add_data['cerebral_palsy'] = 'N';

              if ($form_data[0]['vis_imp_cert_img'] != '') {
                @unlink("./" . $disability_cert_img_path . "/" . $form_data[0]['vis_imp_cert_img']);
              }

              if ($form_data[0]['orth_han_cert_img'] != '') {
                @unlink("./" . $disability_cert_img_path . "/" . $form_data[0]['orth_han_cert_img']);
              }

              if ($form_data[0]['cer_palsy_cert_img'] != '') {
                @unlink("./" . $disability_cert_img_path . "/" . $form_data[0]['orth_han_cert_img']);
              }

              $add_data['vis_imp_cert_img'] = '';
              $add_data['orth_han_cert_img'] = '';
              $add_data['cer_palsy_cert_img'] = '';
            }
          }

          $add_data['updated_on'] = date("Y-m-d H:i:s");
          $add_data['updated_by'] = $candidate_id;

          if ($img_ediited_on_flag == '1') {
            $add_data['img_ediited_on'] = date("Y-m-d H:i:s");
            $add_data['kyc_status'] = '0';
            $add_data['kyc_recommender_status'] = '';
            $add_data['recommender_id'] = '0';
            $add_data['kyc_approver_status'] = '';
            $add_data['approver_id'] = '0';
            $add_data['kyc_recommender_date'] = '';
            $add_data['kyc_approver_date'] = '';
          }

          if ($benchmark_ediited_on_flag == '1') {
            $add_data['benchmark_edit_date']  = date("Y-m-d H:i:s");
            $add_data['benchmark_kyc_status'] = '0';
            $add_data['kyc_recommender_status'] = '';
            $add_data['recommender_id'] = '0';
            $add_data['kyc_approver_status'] = '';
            $add_data['approver_id'] = '0';
            $add_data['kyc_recommender_date'] = '';
            $add_data['kyc_approver_date'] = '';

            // $add_data['benchmark_kyc_recommender_status'] = '';
            // $add_data['benchmark_recommender_id'] = '0';
            // $add_data['benchmark_kyc_approver_status'] = '';
            // $add_data['benchmark_approver_id'] = '0';
            // $add_data['benchmark_kyc_recommender_date'] = '';
            // $add_data['benchmark_kyc_approver_date'] = '';
          }

          $add_data['updated_fields'] = '';

          $this->master_model->updateRecord('ncvet_candidates', $add_data, array('candidate_id' => $candidate_id));

          $this->Ncvet_model->insert_common_log('Candidate : Candidate Profile Updated', 'ncvet_candidates', $this->db->last_query(), $candidate_id, 'candidate_action', 'The candidate has successfully updated the profile', $posted_arr);

          $this->session->set_flashdata('success', 'Candidate profile updated successfully');

          // START : Rename the images and update into database table name
          if ($candidate_id > 0) {
            $candidate_data = $this->master_model->getRecords('ncvet_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.regnumber !=' => ''), "bc.candidate_id, bc.regnumber, bc.id_proof_file,bc.aadhar_file, bc.qualification_certificate_file, bc.candidate_photo, bc.candidate_sign, bc.declarationform, bc.vis_imp_cert_img, bc.orth_han_cert_img, bc.cer_palsy_cert_img,bc.exp_certificate,bc.institute_idproof");
            if (count($candidate_data) > 0) {
              $id_proof_file                  = $candidate_data[0]['id_proof_file'];
              $aadhar_file                    = $candidate_data[0]['aadhar_file'];
              $candidate_photo                = $candidate_data[0]['candidate_photo'];
              $candidate_sign                 = $candidate_data[0]['candidate_sign'];
              $declarationform                = $candidate_data[0]['declarationform'];
              $qualification_certificate_file = $candidate_data[0]['qualification_certificate_file'];
              $exp_certificate                = $candidate_data[0]['exp_certificate'];
              $institute_idproof              = $candidate_data[0]['institute_idproof'];
              $vis_imp_cert_img               = $candidate_data[0]['vis_imp_cert_img'];
              $orth_han_cert_img              = $candidate_data[0]['orth_han_cert_img'];
              $cer_palsy_cert_img             = $candidate_data[0]['cer_palsy_cert_img'];

              $id_proof_file_new   = 'pr_' . $candidate_data[0]['regnumber'] . '.' . pathinfo($id_proof_file, PATHINFO_EXTENSION);
              $aadhar_file_new     = 'aadhar_' . $candidate_data[0]['regnumber'] . '.' . pathinfo($aadhar_file, PATHINFO_EXTENSION);
              $candidate_photo_new = 'p_' . $candidate_data[0]['regnumber'] . '.' . pathinfo($candidate_photo, PATHINFO_EXTENSION);
              $candidate_sign_new = 's_' . $candidate_data[0]['regnumber'] . '.' . pathinfo($candidate_sign, PATHINFO_EXTENSION);

              if ((isset($_POST['qualification']) && ($_POST['qualification'] == 3 || $_POST['qualification'] == 4)) || ($form_data[0]['declarationform'] == '' && ($form_data[0]['qualification'] == 3 || $form_data[0]['qualification'] == 4))) {
                $declarationform_new = 'declaration_' . $candidate_data[0]['regnumber'] . '.' . pathinfo($declarationform, PATHINFO_EXTENSION);
              }

              if ((isset($_POST['qualification']) && ($_POST['qualification'] == 1 || $_POST['qualification'] == 2)) || ($form_data[0]['qualification_certificate_file'] == '' && ($form_data[0]['qualification'] == 1 || $form_data[0]['qualification'] == 2))) {
                $qualification_certificate_file_new = 'qual_' . $candidate_data[0]['regnumber'] . '.' . pathinfo($qualification_certificate_file, PATHINFO_EXTENSION);
              }

              if ((isset($_POST['qualification']) && $_POST['qualification'] == 1 && $_POST['experience'] == 'Y') || ($form_data[0]['exp_certificate'] == '' && $form_data[0]['qualification'] == 1 && $form_data[0]['experience'] == 'Y')) {
                $exp_certificate_new = 'exp_' . $candidate_data[0]['regnumber'] . '.' . pathinfo($exp_certificate, PATHINFO_EXTENSION);
              }

              if ((isset($_POST['qualification']) && ($_POST['qualification'] == 3 || $_POST['qualification'] == 4)) || ($form_data[0]['institute_idproof'] == '' && ($form_data[0]['qualification'] == 3 || $form_data[0]['qualification'] == 4))) {
                $institute_idproof_new = 'inst_id_' . $candidate_data[0]['regnumber'] . '.' . pathinfo($institute_idproof, PATHINFO_EXTENSION);
              }


              if ((isset($_POST['visually_impaired']) && ($_POST['benchmark_disability'] == 'Y' && $_POST['visually_impaired'] == 'Y')) || ($form_data[0]['vis_imp_cert_img'] == '' && ($form_data[0]['benchmark_disability'] == 'Y' || $form_data[0]['visually_impaired'] == 'Y'))) {
                $vis_imp_cert_img_new   = 'v_' . $candidate_data[0]['regnumber'] . '.' . pathinfo($vis_imp_cert_img, PATHINFO_EXTENSION);
              }

              if ((isset($_POST['orthopedically_handicapped']) && ($_POST['benchmark_disability'] == 'Y' && $_POST['orthopedically_handicapped'] == 'Y')) || ($form_data[0]['orth_han_cert_img'] == '' && ($form_data[0]['benchmark_disability'] == 'Y' || $form_data[0]['orthopedically_handicapped'] == 'Y'))) {
                $orth_han_cert_img_new   = 'o_' . $candidate_data[0]['regnumber'] . '.' . pathinfo($orth_han_cert_img, PATHINFO_EXTENSION);
              }

              if ((isset($_POST['cerebral_palsy']) && ($_POST['benchmark_disability'] == 'Y' && $_POST['cerebral_palsy'] == 'Y')) || ($form_data[0]['cer_palsy_cert_img'] == '' && ($form_data[0]['benchmark_disability'] == 'Y' || $form_data[0]['cerebral_palsy'] == 'Y'))) {
                $cer_palsy_cert_img_new   = 'c_' . $candidate_data[0]['regnumber'] . '.' . pathinfo($cer_palsy_cert_img, PATHINFO_EXTENSION);
              }

              $up_img_data = array();
              if ($id_proof_file != $id_proof_file_new) {
                if (rename($id_proof_file_path . "/" . $id_proof_file, $id_proof_file_path . "/" . $id_proof_file_new)) {
                  $up_img_data['id_proof_file'] = $id_proof_file_new;
                }
              }

              if ($aadhar_file != $aadhar_file_new) {
                if (rename($aadhar_file_path . "/" . $aadhar_file, $aadhar_file_path . "/" . $aadhar_file_new)) {
                  $up_img_data['aadhar_file'] = $aadhar_file_new;
                }
              }

              if ($candidate_photo != $candidate_photo_new) {
                if (rename($candidate_photo_path . "/" . $candidate_photo, $candidate_photo_path . "/" . $candidate_photo_new)) {
                  $up_img_data['candidate_photo'] = $candidate_photo_new;
                }
              }

              if ($candidate_sign != $candidate_sign_new) {
                if (rename($candidate_sign_path . "/" . $candidate_sign, $candidate_sign_path . "/" . $candidate_sign_new)) {
                  $up_img_data['candidate_sign'] = $candidate_sign_new;
                }
              }

              if ((isset($_POST['qualification']) && ($_POST['qualification'] == 3 || $_POST['qualification'] == 4)) || ($form_data[0]['declarationform'] == '' && ($form_data[0]['qualification'] == 3 || $form_data[0]['qualification'] == 4))) {
                if ($declarationform != $declarationform_new) {
                  if (rename($declarationform_path . "/" . $declarationform, $declarationform_path . "/" . $declarationform_new)) {
                    $up_img_data['declarationform'] = $declarationform_new;
                  }
                }
              }

              if ((isset($_POST['qualification']) && ($_POST['qualification'] == 1 || $_POST['qualification'] == 2)) || ($form_data[0]['qualification_certificate_file'] == '' && ($form_data[0]['qualification'] == 1 || $form_data[0]['qualification'] == 2))) {
                if ($qualification_certificate_file != $qualification_certificate_file_new) {
                  if (rename($qualification_certificate_file_path . "/" . $qualification_certificate_file, $qualification_certificate_file_path . "/" . $qualification_certificate_file_new)) {
                    $up_img_data['qualification_certificate_file'] = $qualification_certificate_file_new;
                  }
                }
              }

              if ((isset($_POST['qualification']) && $_POST['qualification'] == 1 && $_POST['experience'] == 'Y') || ($form_data[0]['exp_certificate'] == '' && $form_data[0]['qualification'] == 1 && $form_data[0]['experience'] == 'Y')) {
                if ($exp_certificate != $exp_certificate_new) {
                  if (rename($exp_certificate_path . "/" . $exp_certificate, $exp_certificate_path . "/" . $exp_certificate_new)) {
                    $up_img_data['exp_certificate'] = $exp_certificate_new;
                  }
                }
              }

              if ((isset($_POST['qualification']) && ($_POST['qualification'] == 3 || $_POST['qualification'] == 4)) || ($form_data[0]['institute_idproof'] == '' && ($form_data[0]['qualification'] == 3 || $form_data[0]['qualification'] == 4))) {
                if ($institute_idproof != $institute_idproof_new) {
                  if (rename($institute_idproof_path . "/" . $institute_idproof, $institute_idproof_path . "/" . $institute_idproof_new)) {
                    $up_img_data['institute_idproof'] = $institute_idproof_new;
                  }
                }
              }

              if ((isset($_POST['visually_impaired']) && ($_POST['benchmark_disability'] == 'Y' && $_POST['visually_impaired'] == 'Y')) || ($form_data[0]['vis_imp_cert_img'] == '' && ($form_data[0]['benchmark_disability'] == 'Y' || $form_data[0]['visually_impaired'] == 'Y'))) {
                if ($vis_imp_cert_img != $vis_imp_cert_img_new) {
                  if (rename($disability_cert_img_path . "/" . $vis_imp_cert_img, $disability_cert_img_path . "/" . $vis_imp_cert_img_new)) {
                    $up_img_data['vis_imp_cert_img'] = $vis_imp_cert_img_new;
                  }
                }
              }

              if ((isset($_POST['orthopedically_handicapped']) && ($_POST['benchmark_disability'] == 'Y' && $_POST['orthopedically_handicapped'] == 'Y')) || ($form_data[0]['orth_han_cert_img'] == '' && ($form_data[0]['benchmark_disability'] == 'Y' || $form_data[0]['orthopedically_handicapped'] == 'Y'))) {
                if ($orth_han_cert_img != $orth_han_cert_img_new) {
                  if (rename($disability_cert_img_path . "/" . $orth_han_cert_img, $disability_cert_img_path . "/" . $orth_han_cert_img_new)) {
                    $up_img_data['orth_han_cert_img'] = $orth_han_cert_img_new;
                  }
                }
              }

              if ((isset($_POST['cerebral_palsy']) && ($_POST['benchmark_disability'] == 'Y' && $_POST['cerebral_palsy'] == 'Y')) || ($form_data[0]['cer_palsy_cert_img'] == '' && ($form_data[0]['benchmark_disability'] == 'Y' || $form_data[0]['cerebral_palsy'] == 'Y'))) {
                if ($cer_palsy_cert_img != $cer_palsy_cert_img_new) {
                  if (rename($disability_cert_img_path . "/" . $cer_palsy_cert_img, $disability_cert_img_path . "/" . $cer_palsy_cert_img_new)) {
                    $up_img_data['cer_palsy_cert_img'] = $cer_palsy_cert_img_new;
                  }
                }
              }

              if (count($up_img_data) > 0) {
                $this->master_model->updateRecord('ncvet_candidates', $up_img_data, array('candidate_id' => $candidate_data[0]['candidate_id']));

                $this->Ncvet_model->insert_common_log('Candidate : Candidate Profile Updated - image log', 'ncvet_candidates', $this->db->last_query(), $candidate_data[0]['candidate_id'], 'candidate_action_image_log', 'The candidate has successfully updated the profile images', '');
              }
            }
          }
          // END : Rename the images and update into database table name
          redirect(site_url('ncvet/candidate/dashboard_candidate/update_profile'));
        }
      }
    }


    $data['page_title']        = 'IIBF - NCVET Candidate Profile';
    $data['qualification_arr'] = $this->config->item('ncvet_qualification_arr');

    $data['state_master_data'] = $this->master_model->getRecords('state_master', array('state_delete' => '0', 'id !=' => '11'), 'id, state_code, state_no, exempt, state_name', array('state_name' => 'ASC'));

    $data['pr_state_master_data'] = $this->master_model->getRecords('state_master', array('state_delete' => '0', 'id !=' => '11'), 'id, state_code, state_no, exempt, state_name', array('state_name' => 'ASC'));

    $data['bank_associated_master_data'] = [];


    $this->load->view('ncvet/candidate/update_profile_candidate', $data);
  }
  /******** END : UPDATE CANDIDATE DATA ********/

  function get_city_ajax()
  {
    if (isset($_POST) && count($_POST) > 0) {
      $address_type = $this->security->xss_clean($this->input->post('address_type'));
      if ($address_type == 'communication') {
        $city_field_name = 'city';
      } else {
        $city_field_name = 'city_pr';
      }

      $onchange_fun = "validate_file('city_pr')";
      $html = '	<select class="form-control chosen-select ignore_required" name="' . $city_field_name . '" id="' . $city_field_name . '" required onchange="' . $onchange_fun . '">';
      $state_id = $this->security->xss_clean($this->input->post('state_id'));

      $city_data = $this->master_model->getRecords('city_master', array('state_code' => $state_id, 'city_delete' => '0'), 'id, city_name', array('city_name' => 'ASC'));

      if (count($city_data) > 0) {
        $html .= '	<option value="">Select City</option>';
        foreach ($city_data as $city) {
          $html .= '	<option value="' . $city['id'] . '">' . $city['city_name'] . '</option>';
        }
      } else {
        $html .= '	<option value="">Select City</option>';
      }
      $html .= '</select>';

      if ($address_type == 'communication') {
        $html .= '<note class="form_note" id="city_err"></note>';
      } else {
        $html .= '<note class="form_note" id="city_pr_err"></note>';
      }

      $html .= "<script>$('.chosen-select').chosen({width: '100%'});function validate_file(input_id) { $('#'+input_id).valid(); }</script>";

      $result['flag'] = "success";
      $result['response'] = $html;
    } else {
      $result['flag'] = "error";
    }
    echo json_encode($result);
  }

  /******** START : VALIDATION FUNCTION TO CHECK CANDIDATE MOBILE EXIST OR NOT ********/
  public function validation_check_mobile_exist($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = false;
    if (isset($_POST) && $_POST['mobile_no'] != "") {
      if ($type == '1') {
        $mobile_no = $this->security->xss_clean($this->input->post('mobile_no'));
        $enc_candidate_id = $this->security->xss_clean($this->input->post('enc_candidate_id'));

        if ($enc_candidate_id != "" && $enc_candidate_id != '0') {
          $candidate_id = url_decode($enc_candidate_id);
        } else {
          $candidate_id = $enc_candidate_id;
        }
      } else {
        $mobile_no        = $str;
        $enc_candidate_id = $type;
        $candidate_id     = url_decode($enc_candidate_id);
      }

      $candidate_data = $this->Ncvet_model->validation_check_candidate_eligibility_to_add_in_new_batch($candidate_id, 'mobile', $mobile_no);
      if (count($candidate_data) == 0) {
        $return_val_ajax = true;
      }
    }

    if ($type == '1') {
      return $return_val_ajax;
    } else {
      if ($return_val_ajax) {
        return TRUE;
      } else if ($_POST['mobile_no'] != "") {
        $this->form_validation->set_message('validation_check_mobile_exist', 'The mobile number is already exist');
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK CANDIDATE MOBILE EXIST OR NOT ********/

  /******** START : VALIDATION FUNCTION TO CHECK CANDIDATE EMAIL ID EXIST OR NOT ********/
  public function validation_check_email_exist($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = false;
    if (isset($_POST) && $_POST['email_id'] != "") {
      if ($type == '1') {
        $email_id = strtolower($this->security->xss_clean($this->input->post('email_id')));
        $enc_candidate_id = $this->security->xss_clean($this->input->post('enc_candidate_id'));

        if ($enc_candidate_id != "" && $enc_candidate_id != '0') {
          $candidate_id = url_decode($enc_candidate_id);
        } else {
          $candidate_id = $enc_candidate_id;
        }
      } else {
        $email_id = strtolower($str);
        $enc_candidate_id = $type;
        $candidate_id = url_decode($enc_candidate_id);
      }

      $candidate_data = $this->Ncvet_model->validation_check_candidate_eligibility_to_add_in_new_batch($candidate_id, 'email', $email_id);
      if (count($candidate_data) == 0) {
        $return_val_ajax = true;
      }
    }

    if ($type == '1') {
      return $return_val_ajax;
    } else {
      if ($return_val_ajax) {
        return TRUE;
      } else if ($_POST['email_id'] != "") {
        $this->form_validation->set_message('validation_check_email_exist', 'The email id is already exist');
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK CANDIDATE MOBILE EXIST OR NOT ********/


  /******** START : VALIDATION FUNCTION TO CHECK CANDIDATE AADHAR NO EXIST OR NOT ********/
  public function validation_check_aadhar_no_exist($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    if (isset($_POST) && $_POST['aadhar_no'] != "") {
      if ($type == '1') {
        $aadhar_no = strtolower($this->security->xss_clean($this->input->post('aadhar_no')));
        $enc_candidate_id = $this->security->xss_clean($this->input->post('enc_candidate_id'));

        if ($enc_candidate_id != "" && $enc_candidate_id != '0') {
          $candidate_id = url_decode($enc_candidate_id);
        } else {
          $candidate_id = $enc_candidate_id;
        }
      } else {
        $aadhar_no = strtolower($str);
        $enc_candidate_id = $type;
        $candidate_id = url_decode($enc_candidate_id);
      }

      $candidate_data = $this->Ncvet_model->validation_check_candidate_eligibility_to_add_in_new_batch($candidate_id, 'aadhar_no', $aadhar_no);

      if (count($candidate_data) == 0) {
        $return_val_ajax = 'true';
      }
    }

    if ($type == '1') {
      echo $return_val_ajax;
    } else {
      if ($return_val_ajax) {
        return TRUE;
      } else if ($_POST['aadhar_no'] != "") {
        $this->form_validation->set_message('validation_check_aadhar_no_exist', 'The aadhar no is already exist');
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK CANDIDATE AADHAR NO EXIST OR NOT ********/

  /******** START : VALIDATION FUNCTION TO CHECK CANDIDATE AAPAR ID EXIST OR NOT ********/
  public function validation_check_aapar_id_exist($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    if (isset($_POST) && $_POST['id_proof_number'] != "") {
      if ($type == '1') {
        $id_proof_number = strtolower($this->security->xss_clean($this->input->post('id_proof_number')));
        $enc_candidate_id = $this->security->xss_clean($this->input->post('enc_candidate_id'));

        if ($enc_candidate_id != "" && $enc_candidate_id != '0') {
          $candidate_id = url_decode($enc_candidate_id);
        } else {
          $candidate_id = $enc_candidate_id;
        }
      } else {
        $id_proof_number = strtolower($str);
        $enc_candidate_id = $type;
        $candidate_id = url_decode($enc_candidate_id);
      }

      $candidate_data = $this->Ncvet_model->validation_check_candidate_eligibility_to_add_in_new_batch($candidate_id, 'id_proof_number', $id_proof_number);
      if (count($candidate_data) == 0) {
        $return_val_ajax = 'true';
      }
    }

    if ($type == '1') {
      echo $return_val_ajax;
    } else {
      if ($return_val_ajax) {
        return TRUE;
      } else if ($_POST['id_proof_number'] != "") {
        $this->form_validation->set_message('validation_check_aapar_id_exist', 'The aapar id is already exist');
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK CANDIDATE AAPAR ID EXIST OR NOT ********/

  /******** START : VALIDATION FUNCTION TO CHECK COMMUNICATION ADDRESS PINCODE IS VALID OR NOT ********/
  public function validation_check_valid_pincode($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    if (isset($_POST) && $_POST['pincode'] != "") {
      if ($type == '1') {
        $pincode = $this->security->xss_clean($this->input->post('pincode'));
        $selected_state_code = $this->security->xss_clean($this->input->post('selected_state_code'));
      } else {
        $pincode = $str;
        $selected_state_code = $type;
      }

      $this->db->where(" '" . $pincode . "' BETWEEN start_pin AND end_pin ");
      $result_data = $this->master_model->getRecords('state_master', array('state_code' => $selected_state_code), 'id, state_code, start_pin, end_pin');

      if (count($result_data) > 0) {
        $return_val_ajax = 'true';
      }
    }

    if ($type == '1') {
      echo $return_val_ajax;
    } else {
      if ($return_val_ajax == 'true') {
        return TRUE;
      } else if ($_POST['pincode'] != "") {
        $pin_length = strlen($_POST['pincode']);

        $err_msg = 'Please enter valid pincode as per selected city';
        if ($pin_length != 6) {
          $err_msg = 'Please enter only 6 numbers in pincode';
        }

        $this->form_validation->set_message('validation_check_valid_pincode', $err_msg);
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK COMMUNICATION ADDRESS PINCODE IS VALID OR NOT ********/

  /******** START : VALIDATION FUNCTION TO CHECK PERMENANT ADDRESS PINCODE IS VALID OR NOT ********/
  public function validation_check_valid_pincode_permenant($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    if (isset($_POST) && $_POST['pincode_pr'] != "") {
      if ($type == '1') {
        $pincode_pr = $this->security->xss_clean($this->input->post('pincode_pr'));
        $selected_state_code = $this->security->xss_clean($this->input->post('selected_state_code'));
      } else {
        $pincode_pr = $str;
        $selected_state_code = $type;
      }

      $this->db->where(" '" . $pincode_pr . "' BETWEEN start_pin AND end_pin ");
      $result_data = $this->master_model->getRecords('state_master', array('state_code' => $selected_state_code), 'id, state_code, start_pin, end_pin');

      if (count($result_data) > 0) {
        $return_val_ajax = 'true';
      }
    }

    if ($type == '1') {
      echo $return_val_ajax;
    } else {
      if ($return_val_ajax == 'true') {
        return TRUE;
      } else if ($_POST['pincode_pr'] != "") {
        $pin_length = strlen($_POST['pincode_pr']);

        $err_msg = 'Please enter valid pincode as per selected city';
        if ($pin_length != 6) {
          $err_msg = 'Please enter only 6 numbers in pincode';
        }

        $this->form_validation->set_message('validation_check_valid_pincode_permenant', $err_msg);
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK PERMENANT ADDRESS PINCODE IS VALID OR NOT ********/

  /******** START : VALIDATION FUNCTION TO RESTRICT INPUT VALUES ********/
  function fun_restrict_input($str, $type) // Custom callback function for restrict input
  {
    if ($str != '') {
      $result = $this->Ncvet_model->fun_restrict_input($str, $type);
      if ($result['flag'] == 'success') {
        return true;
      } else {
        $this->form_validation->set_message('fun_restrict_input', $result['response']);
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO RESTRICT INPUT VALUES ********/

  /******** START : VALIDATION FUNCTION TO CHECK VALID FILE ********/
  function fun_validate_file_upload($str, $parameter) // Custom callback function for check valid file
  {
    $result = $this->Ncvet_model->fun_validate_file_upload($parameter);
    if ($result['flag'] == 'success') {
      return true;
    } else {
      $this->form_validation->set_message('fun_validate_file_upload', $result['response']);
      return false;
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK VALID FILE ********/

  /******** START : CHANGE ADMIN PASSWORD ********/
  function change_password()
  {
    $data['act_id']     = "Change Password";
    $data['sub_act_id'] = "Change Password";
    $log_slug = '';

    $data['page_title'] = 'IIBF - NCVET Candidate Change Password';

    $data['form_data'] = $form_data = $this->master_model->getRecords('ncvet_candidates', array('candidate_id' => $this->login_candidate_id), 'candidate_id, is_active, is_deleted');

    $log_slug = 'candidate_self_password_action';

    if (isset($_POST) && count($_POST) > 0) {
      $this->form_validation->set_rules('current_pass_candidate', 'Current Password', 'trim|required|xss_clean|callback_validation_check_old_password', array('required' => 'Please enter %s'));
      $this->form_validation->set_rules('new_pass_candidate', 'New Password', 'trim|required|callback_fun_validate_password|xss_clean|callback_validation_check_new_password', array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));
      $this->form_validation->set_rules('confirm_pass_candidate', 'Confirm Password', 'trim|required|min_length[8]|callback_validation_check_password|xss_clean', array('required' => 'Please enter %s', 'min_length' => 'Please enter minimum 8 characters in length'));

      if ($this->form_validation->run()) {
        $posted_arr = json_encode($_POST);
        $candidate_name = $this->Ncvet_model->getLoggedInUserDetails($this->login_candidate_id, 'candidate');

        $up_data['password'] = $this->Ncvet_model->password_encryption($this->input->post('new_pass_candidate'));
        $up_data['updated_on'] = date("Y-m-d H:i:s");
        $up_data['updated_by'] = $this->login_candidate_id;
        $this->master_model->updateRecord('ncvet_candidates', $up_data, array('candidate_id' => $this->login_candidate_id));

        $this->Ncvet_model->insert_common_log('Candidate : Profile password updated', 'ncvet_candidates', $this->db->last_query(), $this->login_candidate_id, 'candidate_self_password_action', 'The ' . $candidate_name['disp_name'] . ' has successfully updated the password.', $posted_arr);

        $this->session->set_flashdata('success', 'Password successfully updated');

        redirect(site_url('ncvet/candidate/dashboard_candidate/change_password'));
      }
    }

    $data["enc_login_candidate_id"] = url_encode($this->login_candidate_id);
    $this->load->view('ncvet/candidate/change_password', $data);
  }
  /******** END : CHANGE ADMIN PASSWORD ********/

  /******** START : VALIDATION FUNCTION TO CHECK THE OLD PASSWORD ********/
  function validation_check_old_password($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    if (isset($_POST) && $_POST['current_pass_candidate'] != "") {
      if ($type == '1') {
        $current_pass_candidate = $this->security->xss_clean($this->input->post('current_pass_candidate'));
      } else if ($type == '0') {
        $current_pass_candidate = $str;
      }

      $enc_password = $this->Ncvet_model->password_encryption($current_pass_candidate);
      if (count($this->master_model->getRecords('ncvet_candidates', array('password' => $enc_password, 'candidate_id' => $this->login_candidate_id, 'is_active' => '1', 'is_deleted' => '0'), 'candidate_id, is_active, is_deleted')) > 0) {
        $return_val_ajax = 'true';
      }
    }

    if ($type == '1') {
      echo $return_val_ajax;
    } else if ($type == '0') {
      if ($return_val_ajax == 'true') {
        return TRUE;
      } else if ($_POST['current_pass_candidate'] != "") {
        $this->form_validation->set_message('validation_check_old_password', 'Please enter correct old password');
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK THE OLD PASSWORD ********/

  /******** START : VALIDATION FUNCTION TO CHECK VALID PASSWORD ********/
  function fun_validate_password($str) // Custom callback function for check valid PASSWORD
  {
    if ($str != '') {
      $password_length = strlen($str);
      $err_msg = '';
      if ($password_length < 8) {
        $err_msg = 'Please enter minimum 8 characters in password';
      } else if ($password_length > 20) {
        $err_msg = 'Please enter maximum 20 characters in password';
      }

      if ($err_msg != "") {
        $this->form_validation->set_message('fun_validate_password', $err_msg);
        return false;
      } else {
        $result = $this->Ncvet_model->fun_validate_password($str);
        if ($result['flag'] == 'success') {
          return true;
        } else {
          $this->form_validation->set_message('fun_validate_password', $result['response']);
          return false;
        }
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK VALID PASSWORD ********/

  /******** START : VALIDATION FUNCTION TO CHECK THE NEW PASSWORD & CONFIRM PASSWORD IS SAME OR NOT ********/
  function validation_check_password($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    $msg = 'Please enter the Confirm Password to match the New Password';
    if (isset($_POST) && $_POST['confirm_pass_candidate'] != "") {
      $new_pass_candidate = $this->security->xss_clean($this->input->post('new_pass_candidate'));
      if ($type == '1') {
        $confirm_pass_candidate = $this->security->xss_clean($this->input->post('confirm_pass_candidate'));
      } else if ($type == '0') {
        $confirm_pass_candidate = $str;
      }

      if ($new_pass_candidate == $confirm_pass_candidate) {
        $return_val_ajax = 'true';
      }
    }

    if ($type == '1') {
      echo $return_val_ajax;
    } else if ($type == '0') {
      if ($return_val_ajax == 'true') {
        return TRUE;
      } else if ($_POST['confirm_pass_candidate'] != "") {
        $this->form_validation->set_message('validation_check_password', $msg);
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK THE NEW PASSWORD & CONFIRM PASSWORD IS SAME OR NOT ********/

  /******** START : VALIDATION FUNCTION TO CHECK THE NEW PASSWORD IS DIFFERENT FROM CURRENT PASSWORD ********/
  function validation_check_new_password($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    $msg = 'New password must be different from Current password';

    if (isset($_POST) && $_POST['new_pass_candidate'] != "") {
      $current_pass_candidate = $this->security->xss_clean($this->input->post('current_pass_candidate'));
      if ($type == '1') {
        $new_pass_candidate = $this->security->xss_clean($this->input->post('new_pass_candidate'));
      } else if ($type == '0') {
        $new_pass_candidate = $str;
      }

      if (preg_match('/[A-Z]/', $new_pass_candidate) && preg_match('/[a-z]/', $new_pass_candidate) && preg_match('/[0-9]/', $new_pass_candidate)) {
        if ($current_pass_candidate != $new_pass_candidate) {
          $return_val_ajax = 'true';
        }
      } else {
        $msg = 'Password must contain at least one upper-case character, one lower-case character, one digit and one special character';
      }
    }

    if ($type == '1') {
      echo $return_val_ajax;
    } else if ($type == '0') {
      if ($return_val_ajax == 'true') {
        return TRUE;
      } else if ($_POST['new_pass_candidate'] != "") {
        $this->form_validation->set_message('validation_check_new_password', $msg);
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK THE NEW PASSWORD IS DIFFERENT FROM CURRENT PASSWORD ********/

  public function send_otp()
  {
    $arr_status = [];
    $email_id = strtolower($_POST['email_id']);
    $type  = $_POST['type'];
    if ($type == 'send_otp' || $type == 'resend_otp') {
      $arr_email_status = $this->validation_check_email_exist($email_id, 2);
      if ($arr_email_status) {
        $sendOTPStatus = $this->send_otp_sms_email($email_id, 'email_id');
        if ($sendOTPStatus) {
          $status = true;
          $msg    = 'OTP successfully sent to email address. The OTP is valid for 10 minutes.';
        } else {
          $status = false;
          $msg    = 'Error occured, While sending an OTP on email id.';
        }
      } else {
        $status = false;
        $msg    = 'Email id is already exist.';
      }
    } elseif ($type == 'verify_otp') {
      $input_otp = $_POST['otp'];

      $otp_data = $this->master_model->getRecords('ncvet_candidate_login_otp', array('email_id' => $email_id, 'is_validate' => '0', 'otp_type' => '3'), 'otp_id, otp, is_validate, otp_expired_on', array('otp_id' => 'DESC'), '', 1);

      if (count($otp_data) > 0) {
        if ($otp_data[0]['otp'] != $input_otp) {
          $status = false;
          $msg = 'Please enter the correct OTP.';
        } else if ($otp_data[0]['otp_expired_on'] < date("Y-m-d H:i:s")) {
          $status = false;
          $msg = 'The OTP has already expired.';
        } else {
          $up_data['is_validate'] = 1;
          $up_data['updated_on']  = date("Y-m-d H:i:s");
          $this->master_model->updateRecord('ncvet_candidate_login_otp', $up_data, array('otp_id' => $otp_data[0]['otp_id']));

          $status = true;
          $msg = 'OTP verified successfully.';
          $this->session->set_userdata('ncvet_verified_email', $email_id);
        }
      } else {
        $status = false;
        $msg    = 'No record found.';
      }
    }

    $arr_status['status'] = $status;
    $arr_status['msg']    = $msg;
    echo json_encode($arr_status);
  }

  public function send_otp_mobile()
  {
    $arr_status = [];
    $mobile_no = $_POST['mobile_no'];
    $type   = $_POST['type'];
    if ($type == 'send_otp' || $type == 'resend_otp') {
      $arr_mobile_status = $this->validation_check_mobile_exist(0, 1);

      if ($arr_mobile_status) {
        $sendOTPStatus = $this->send_otp_sms_email($mobile_no, 'mobile_no');

        if ($sendOTPStatus) {
          $status = true;
          $msg    = 'OTP successfully sent to mobile number. The OTP is valid for 10 minutes.';
        } else {
          $status = false;
          $msg    = 'Error occured, While sending an OTP on mobile no.';
        }
      } else {
        $status = false;
        $msg    = "Mobile number is already exist.";
      }
    } elseif ($type == 'verify_otp') {
      $input_otp = $_POST['otp'];

      $otp_data = $this->master_model->getRecords('ncvet_candidate_login_otp', array('mobile_no' => $mobile_no, 'is_validate' => '0', 'otp_type' => '4'), 'otp_id, otp, is_validate, otp_expired_on', array('otp_id' => 'DESC'), '', 1);

      if (count($otp_data) > 0) {
        if ($otp_data[0]['otp'] != $input_otp)
        // if (false)
        {
          $status = false;
          $msg = 'Please enter the correct OTP.';
        } else if ($otp_data[0]['otp_expired_on'] < date("Y-m-d H:i:s"))
        // else if (false)
        {
          $status = false;
          $msg = 'The OTP has already expired.';
        } else {
          $up_data['is_validate'] = 1;
          $up_data['updated_on']  = date("Y-m-d H:i:s");
          $this->master_model->updateRecord('ncvet_candidate_login_otp', $up_data, array('otp_id' => $otp_data[0]['otp_id']));

          $status = true;
          $msg = 'OTP verified successfully.';
          $this->session->set_userdata('ncvet_verified_mobile', $mobile_no);
        }
      } else {
        $status = false;
        $msg    = 'No record found.';
      }
    }

    $arr_status['status'] = $status;
    $arr_status['msg']    = $msg;
    echo json_encode($arr_status);
  }

  private function send_otp_sms_email($data, $field_type)
  {
    $data           = $data;
    // $email_id    = $email;
    $otp            = rand(100000, 999999);;
    $otp_sent_on    = date('Y-m-d H:i:s');
    $otp_expired_on = date('Y-m-d H:i:s', strtotime('+ 10 minutes', strtotime($otp_sent_on)));

    $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'email_mobile_verification'));
    if ($field_type == 'email_id') {
      $email_text = $emailerstr[0]['emailer_text'];
      // $email_text = str_replace('#CANDIDATENAME#', "Test", $email_text);
      $email_text = str_replace('#OTP#', $otp, $email_text);

      $otp_mail_arr['to']      = $data;
      $otp_mail_arr['subject'] = $emailerstr[0]['subject'];
      $otp_mail_arr['message'] = $email_text;
      $email_sms_response = $this->Emailsending->mailsend($otp_mail_arr);
    } elseif ($field_type == 'mobile_no') {
      $sms_text = $emailerstr[0]['sms_text'];
      $sms_text = str_replace('#OTP#', $otp, $sms_text);

      $email_sms_response = $this->master_model->send_sms_common_all($data, $sms_text, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']);

      if (isset($email_sms_response['status']) && $email_sms_response['status'] == 'success') {
      } else {
        return false;
        exit;
      }
    }

    if ($email_sms_response) {
      if ($field_type == 'email_id') {
        $add_data['email_id']    = $data;
        $add_data['otp_type'] = '3';
      } else {
        $add_data['mobile_no']   = $data;
        $add_data['otp_type'] = '4';
      }

      $add_data['otp']            = $otp;
      $add_data['is_validate']    = '0';
      $add_data['otp_expired_on'] = $otp_expired_on;
      $add_data['created_on']     = $otp_sent_on;

      $this->db->insert('ncvet_candidate_login_otp ', $add_data);
      return true;
    } else {
      return false;
    }
  }

  //START : ADDED BY GAURAV ON 2025-09-24. SERVER SIDE VALIDATION TO CHECK THE EMAIL & MOBILE IS VERIFIED CORRECTLY OR NOT
  function check_email_mobile_otp_verification($str = '', $type = '')
  {
    $flag = '';
    $message = 'Please verify the email or mobile';

    if ($type != '' && ($type == 'email_id' || $type == 'mobile_no')) {
      $this->db->where_in('otp_type', array(3, 4));
      $this->db->limit(1);
      $otp_data = $this->master_model->getRecords('ncvet_candidate_login_otp', array($type => $str), 'email_id, mobile_no, otp, is_validate, created_on', array('otp_id' => 'DESC'));

      if (count($otp_data) > 0) {
        if ($type == 'mobile_no') {
          $type = 'mobile number';
        }

        if ($otp_data[0]['is_validate'] == '1') {
          $flag = 'success';
        } else {
          $message = 'The OTP is not verified for ' . $type . ' ' . $str;
        }
      }
    }

    if ($flag == 'success') {
      return true;
    } else {
      $this->form_validation->set_message('check_email_mobile_otp_verification', $message);
      return false;
    }
  }//END : ADDED BY GAURAV ON 2025-09-24. SERVER SIDE VALIDATION TO CHECK THE EMAIL & MOBILE IS VERIFIED CORRECTLY OR NOT

  /******** START : VALIDATION FUNCTION TO CHECK VALID DATE OF BIRTH ********/
  function fun_validate_dob($str, $chk_dates_str = '') // Custom callback function for check valid DATE OF BIRTH
  {
    if ($str != '') {
      $current_val = $str;

      //$explode_input_arr = explode("####",$chk_dates_str);
      //$chk_dob_start_date = date('Y-m-d', strtotime($explode_input_arr[0]));
      //$chk_dob_end_date = date('Y-m-d', strtotime($explode_input_arr[1]));
      $chk_dob_end_date = $chk_dates_str;

      //if($chk_dob_start_date != "" && $chk_dob_end_date != "")
      if ($chk_dob_end_date != "") {
        //if($current_val >= $chk_dob_start_date && $current_val <= $chk_dob_end_date) { return true; }
        if ($current_val <= $chk_dob_end_date) {
          return true;
        } else {
          //$this->form_validation->set_message('fun_validate_dob', "Select date of birth between ".$chk_dob_start_date." to ".$chk_dob_end_date." date.");
          $this->form_validation->set_message('fun_validate_dob', "Select date of birth before " . date('Y-m-d', strtotime("+1days", strtotime($chk_dob_end_date))) . " date.");
          return false;
        }
      } else {
        return true;
      }
    } else {
      return true;
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK VALID DATE OF BIRTH ********/
}
