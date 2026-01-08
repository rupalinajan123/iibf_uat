<?php

/********************************************************************************************************************
 ** Description: Controller for NCVET CANDIDATE REGISTRATION
 ** Created BY: Priyanka Dhikale 18th aug 2025
 ********************************************************************************************************************/
defined('BASEPATH') or exit('No direct script access allowed');

class Candidate_registration extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    $this->load->model('master_model');
    $this->load->model('ncvet/Ncvet_model');
    $this->load->helper('ncvet/ncvet_helper');
    $this->load->helper('file');
    $this->load->helper('master_helper');
    $this->load->helper('general_helper');
    $this->load->model('billdesk_pg_model');
    $this->load->model('log_model');

    $this->id_proof_file_path = 'uploads/ncvet/id_proof';
    $this->aadhar_file_path = 'uploads/ncvet/aadhar_file';
    $this->qualification_certificate_file_path = 'uploads/ncvet/qualification_certificate';
    $this->candidate_photo_path = 'uploads/ncvet/photo';
    $this->candidate_sign_path = 'uploads/ncvet/sign';
    $this->exp_certificate_path = 'uploads/ncvet/experience';
    $this->institute_idproof_path = 'uploads/ncvet/institute_idproof';
    $this->declarationform_path = 'uploads/ncvet/declaration';
    $this->disability_path = 'uploads/ncvet/disability';
  }

  /******** START : ADD  CANDIDATES DATA ********/
  public function index()
  {

    $data = array();
    /* if ($this->session->userdata('enduserinfo'))
      {
        $this->session->unset_userdata('enduserinfo');
      }
      */
    $flag = 1;
    $valcookie = register_get_cookie();
    //   echo $valcookie;exit;

    if ($valcookie) {
      $candidate_id = $valcookie;
      //$candidate_id= '57';
      $checkuser = $this->master_model->getRecords('ncvet_candidates', array('candidate_id' => $candidate_id, 'regnumber !=' => '', 'is_active !=' => '0'));
      if (count($checkuser) > 0) {
        delete_cookie('candidate_id');
      } else {
        $checkpayment = $this->master_model->getRecords('ncvet_payment_transaction', array('ref_id' => $candidate_id, 'status' => '2', 'pay_type' => '1'));
        //echo $this->db->last_query();
        //exit;
        if (count($checkpayment) > 0) {
          ///$datearr=explode(' ',$checkpayment[0]['date']);
          $endTime      = date("Y-m-d H:i:s", strtotime("+20 minutes", strtotime($checkpayment[0]['date'])));
          $current_time = date("Y-m-d H:i:s");
          if (strtotime($current_time) <= strtotime($endTime)) {
            $flag = 0;
          } else {
            delete_cookie('candidate_id');
          }
        } else {
          $flag = 1;
          delete_cookie('candidate_id');
        }
      }
    }
    if ($flag == 0) {

      $this->load->view('ncvet/register_cookie_msg', $data);
    }
    $data['mode'] = $mode = 'Add';


    $error_flag = 0;


    $data['id_proof_file_error'] = $data['aadhar_file_error'] = $data['qualification_certificate_file_error'] = $data['candidate_photo_error'] = $data['candidate_sign_error'] = $data['exp_certificate_error'] = $data['institute_idproof_error'] = $data['declarationform_error'] = $data['disability_error'] = '';

    $data['id_proof_file_path'] = $id_proof_file_path = $this->id_proof_file_path;
    $data['aadhar_file_path'] = $aadhar_file_path = $this->aadhar_file_path;
    $data['qualification_certificate_file_path'] = $qualification_certificate_file_path = $this->qualification_certificate_file_path;
    $data['candidate_photo_path'] = $candidate_photo_path = $this->candidate_photo_path;
    $data['candidate_sign_path'] = $candidate_sign_path = $this->candidate_sign_path;
    $data['exp_certificate_path'] = $exp_certificate_path = $this->exp_certificate_path;
    $data['institute_idproof_path'] = $institute_idproof_path = $this->institute_idproof_path;
    $data['declarationform_path'] = $declarationform_path = $this->declarationform_path;
    $data['disability_path'] = $disability_path = $this->disability_path;

    $data['dob_end_date'] = $dob_end_date = date('Y-m-d', strtotime("- 18 year", strtotime(date('Y-m-d'))));

    if (isset($_POST) && count($_POST) > 0) {
      /* _pa($_FILES); _pa($_POST); */

      //
      $this->Ncvet_model->insert_common_log('Candidate Enrollment posted data', 'ncvet_candidates', 'Candidate Enrollment posted data', 'Candidate Enrollment posted data', 'candidate_action', 'The candidate Enrollment posted data', serialize($_POST));

      $validate_form_type = 'full';
      if ($mode == 'Add') {
        $form_action = $this->security->xss_clean($this->input->post('form_action'));
        if ($form_action == '1') {
          $validate_form_type = 'basic';
        }
      }

      $_POST['emmail_id'] = $this->session->userdata('ncvet_verified_email');
      $_POST['mobile_no'] = $this->session->userdata('ncvet_verified_mobile');



      $id_proof_file_req_flg = $aadhar_file_req_flg = $qualification_certificate_file_req_flg = $candidate_photo_req_flg = $candidate_sign_req_flg = $exp_certificate_req_flg = $institute_idproof_req_flg = $scanned_vis_imp_cert_req_flg = $scanned_orth_han_cert_req_flg = $scanned_cer_palsy_cert_req_flg = $declarationform_req_flg = '';

      if ($mode == 'Add') {
        $id_proof_file_req_flg =  $aadhar_file_req_flg = $candidate_photo_req_flg = $candidate_sign_req_flg = $exp_certificate_req_flg = $institute_idproof_req_flg = $scanned_vis_imp_cert_req_flg = $scanned_orth_han_cert_req_flg = $scanned_cer_palsy_cert_req_flg = $declarationform_req_flg = 'required|'; {
          if (isset($_POST['id_proof_file_cropper']) && $_POST['id_proof_file_cropper'] != "") {
            $id_proof_file_req_flg = '';
          }
          if (isset($_POST['aadhar_file_cropper']) && $_POST['aadhar_file_cropper'] != "") {
            $aadhar_file_req_flg = '';
          }
          if (isset($_POST['qualification_certificate_file_cropper']) && $_POST['qualification_certificate_file_cropper'] != "") {
            $qualification_certificate_file_req_flg = '';
          }
          if (isset($_POST['exp_certificate_cropper']) && $_POST['exp_certificate_cropper'] != "") {
            $exp_certificate_req_flg = '';
          }
          if (isset($_POST['scanned_vis_imp_cert_cropper']) && $_POST['scanned_vis_imp_cert_cropper'] != "") {
            $scanned_vis_imp_cert_req_flg = '';
          }
          if (isset($_POST['scanned_orth_han_cert_cropper']) && $_POST['scanned_orth_han_cert_cropper'] != "") {
            $scanned_orth_han_cert_req_flg = '';
          }
          if (isset($_POST['scanned_cer_palsy_cert_cropper']) && $_POST['scanned_cer_palsy_cert_cropper'] != "") {
            $scanned_cer_palsy_cert_req_flg = '';
          }
          if (isset($_POST['declarationform_cropper']) && $_POST['declarationform_cropper'] != "") {
            $declarationform_req_flg = '';
          }

          if (isset($_POST['candidate_photo_cropper']) && $_POST['candidate_photo_cropper'] != "") {
            $candidate_photo_req_flg = '';
          }
          if (isset($_POST['candidate_sign_cropper']) && $_POST['candidate_sign_cropper'] != "") {
            $candidate_sign_req_flg = '';
          }
        }
      }


      $enc_candidate_id = 0;
      $this->form_validation->set_rules('salutation', 'candidate name (salutation)', 'trim|required|xss_clean', array('required' => "Please select the %s"));
      $this->form_validation->set_rules('first_name', 'first name', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[20]|xss_clean', array('required' => "Please enter the %s"));
      $this->form_validation->set_rules('middle_name', 'middle name', 'trim|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[20]|xss_clean');
      $this->form_validation->set_rules('last_name', 'last name', 'trim|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[20]|xss_clean');
      //$this->form_validation->set_rules('dob', 'date of birth', 'trim|required|callback_fun_validate_dob['.$dob_start_date.'####'.$dob_end_date.']|xss_clean', array('required'=>"Please select the %s"));        
      $this->form_validation->set_rules('dob', 'date of birth', 'trim|required|callback_fun_validate_dob[' . $dob_end_date . ']|xss_clean', array('required' => "Please select the %s"));
      $this->form_validation->set_rules('gender', 'gender', 'trim|required|xss_clean', array('required' => "Please select the %s"));
      $this->form_validation->set_rules('guardian_salutation', 'Guardian name (salutation)', 'trim|required|xss_clean', array('required' => "Please select the %s"));
      $this->form_validation->set_rules('guardian_name', 'Guardian Name', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[150]|xss_clean', array('required' => "Please enter the %s"));
      $this->form_validation->set_rules('mobile_no', 'mobile number', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|min_length[10]|max_length[10]|callback_validation_check_mobile_exist[' . $enc_candidate_id . ']|callback_fun_restrict_input[first_zero_not_allowed]|xss_clean', array('required' => "Please enter the %s"));

      $this->form_validation->set_rules('email_id', 'email id', 'trim|required|max_length[80]|valid_email|callback_validation_check_email_exist[' . $enc_candidate_id . ']|xss_clean', array('required' => "Please enter the %s"));



      $this->form_validation->set_rules('address1', 'address line-1', 'trim|required|max_length[75]|xss_clean', array('required' => "Please enter the %s"));
      $this->form_validation->set_rules('address2', 'address line-2', 'trim|max_length[75]|xss_clean');
      $this->form_validation->set_rules('address3', 'address line-3', 'trim|max_length[75]|xss_clean');
      $this->form_validation->set_rules('state', 'state', 'trim|required|xss_clean', array('required' => "Please select the %s"));
      $this->form_validation->set_rules('city', 'city', 'trim|required|xss_clean', array('required' => "Please select the %s"));
      $this->form_validation->set_rules('district', 'district', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_numbers_and_space]|max_length[30]|xss_clean', array('required' => "Please enter the %s"));
      $this->form_validation->set_rules('pincode', 'pincode', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|callback_validation_check_valid_pincode[' . $this->input->post('state') . ']|xss_clean', array('required' => "Please enter the %s"));

      $this->form_validation->set_rules('address1_pr', 'address line-1', 'trim|required|max_length[75]|xss_clean', array('required' => "Please enter the %s"));
      $this->form_validation->set_rules('address2_pr', 'address line-2', 'trim|max_length[75]|xss_clean');
      $this->form_validation->set_rules('address3_pr', 'address line-3', 'trim|max_length[75]|xss_clean');
      $this->form_validation->set_rules('state_pr', 'state', 'trim|required|xss_clean', array('required' => "Please select the %s"));
      $this->form_validation->set_rules('city_pr', 'city', 'trim|required|xss_clean', array('required' => "Please select the %s"));
      $this->form_validation->set_rules('district_pr', 'district', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_numbers_and_space]|max_length[30]|xss_clean', array('required' => "Please enter the %s"));
      $this->form_validation->set_rules('pincode_pr', 'pincode', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|callback_validation_check_valid_pincode_pr[' . $this->input->post('state') . ']|xss_clean', array('required' => "Please enter the %s"));

      $this->form_validation->set_rules('qualification', 'qualification', 'trim|required|xss_clean', array('required' => "Please select the %s"));
      if ($_POST['qualification'] == '1' || $_POST['qualification'] == '2') {

        $this->form_validation->set_rules('qualification_certificate_file', 'Qualification certificate', 'callback_fun_validate_file_upload[qualification_certificate_file|' . $qualification_certificate_file_req_flg . '|jpg,jpeg,png,pdf|5120|qualification certificate|75]');
      }

      $this->form_validation->set_rules('qualification_state', 'State', 'trim|required|xss_clean', array('required' => "Please select the %s"));

      if ($_POST['qualification'] == '1') {

        //$this->form_validation->set_rules('experience', 'experience', 'trim|required|callback_qualification_exp_validation|xss_clean', array('required' => "Please select the %s"));
        $this->form_validation->set_rules('experience', 'experience', 'trim|required|xss_clean', array('required' => "Please select the %s"));

        //if($_POST['experience']=='Y') 
        {
          $this->form_validation->set_rules('exp_certificate', 'Experience certificate', 'callback_fun_validate_file_upload[exp_certificate|' . $exp_certificate_req_flg . '|jpg,jpeg,png,pdf|5120|Experience certificate|75]');
        }
      }

      if ($_POST['qualification'] == '3' || $_POST['qualification'] == '4') {

        if ($_POST['qualification'] == '3')
          $_POST['semester'] = $_POST['graduation_sem'];

        if ($_POST['qualification'] == '4')
          $_POST['semester'] = $_POST['post_graduation_sem'];
        $this->form_validation->set_rules('semester', 'Semester', 'trim|required|xss_clean', array('required' => "Please select the %s"));
        $this->form_validation->set_rules('collage', 'Name of Collage', 'trim|required|max_length[255]|xss_clean', array('required' => "Please enter the %s"));
        $this->form_validation->set_rules('university', 'Name of University', 'trim|required|max_length[150]|xss_clean', array('required' => "Please enter the %s"));
        $this->form_validation->set_rules('institute_idproof', 'Institute IDproof', 'callback_fun_validate_file_upload[institute_idproof|jpg,jpeg,png,pdf|5120|Institute IDProof|75]');
        $this->form_validation->set_rules('declarationform', 'Declaration', 'callback_fun_validate_file_upload[declarationform|' . $declarationform_req_flg . '|jpg,jpeg,png,pdf|5120|proof of identity|75]');
      }

      $this->form_validation->set_rules('id_proof_file', 'proof of identity', 'callback_fun_validate_file_upload[id_proof_file|' . $id_proof_file_req_flg . '|jpg,jpeg,png|5120|proof of identity|75]'); //callback parameter separated by pipe 'input name|required|allowed extension|max size in kb|display name|min size in kb'
      $this->form_validation->set_rules('aadhar_file', 'Aadhar File', 'callback_fun_validate_file_upload[aadhar_file|' . $aadhar_file_req_flg . '|jpg,jpeg,png|5120|Aadhar File|75]'); //callback parameter separated by pipe 'input name|required|allowed extension|max size in kb|display name|min size in kb'

      $this->form_validation->set_rules('candidate_photo', 'passport-size photo of the candidate', 'callback_fun_validate_file_upload[candidate_photo|' . $candidate_photo_req_flg . '|jpg,jpeg,png|20|passport photo of the candidate|14]'); //callback parameter separated by pipe 'input name|required|allowed extension|max size in kb|display name|min size in kb'  
      $this->form_validation->set_rules('candidate_sign', 'signature of the candidate', 'callback_fun_validate_file_upload[candidate_sign|' . $candidate_sign_req_flg . '|jpg,jpeg,png|20|signature of the candidate|14]'); //callback parameter separated by pipe 'input name|required|allowed extension|max size in kb|display name|min size in kb' 

      $this->form_validation->set_rules('aadhar_no', 'aadhar number', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|min_length[12]|max_length[12]|callback_validation_check_aadhar_no_exist[' . $enc_candidate_id . ']|xss_clean');


      $this->form_validation->set_rules('id_proof_number', 'id proof number', 'trim|required|callback_validation_check_id_proof_number_exist[' . $enc_candidate_id . ']|xss_clean');


      $this->form_validation->set_rules('benchmark_disability', 'Benchmark Disability', 'trim|required|xss_clean', array('required' => "Please select the %s"));


      if (isset($_POST['visually_impaired']) && $_POST['visually_impaired'] == 'Y') {
        $this->form_validation->set_rules('scanned_vis_imp_cert', 'Visually impaired Attach scan copy of PWD certificate', 'callback_fun_validate_file_upload[scanned_vis_imp_cert|' . $scanned_vis_imp_cert_req_flg . '|jpg,jpeg,png,pdf|5120|Visually impaired|75]');
      }
      if (isset($_POST['orthopedically_handicapped']) && $_POST['orthopedically_handicapped'] == 'Y') {
        $this->form_validation->set_rules('scanned_orth_han_cert', 'Orthopedically handicapped Attach scan copy of PWD certificate', 'callback_fun_validate_file_upload[scanned_orth_han_cert|' . $scanned_orth_han_cert_req_flg . '|jpg,jpeg,png,pdf|5120|Orthopedically handicapped|75]');
      }
      if (isset($_POST['cerebral_palsy']) && $_POST['cerebral_palsy'] == 'Y') {
        $this->form_validation->set_rules('scanned_cer_palsy_cert', 'Cerebral palsy Attach scan copy of PWD certificate', 'callback_fun_validate_file_upload[scanned_cer_palsy_cert|' . $scanned_cer_palsy_cert_req_flg . '|jpg,jpeg,png,pdf|5120|Cerebral palsy|75]');
      }
      //echo '<pre>'; print_r($_POST); echo '</pre>';exit; 

      if ($this->form_validation->run()) {

        $new_id_proof_file = $new_aadhar_file = $new_qualification_certificate_file = $new_candidate_photo = $new_candidate_sign = $new_qualification_certificate_file = $new_exp_certificate = $new_institute_idproof = $new_declarationform = $new_scanned_vis_imp_cert = $new_scanned_orth_han_cert = $new_scanned_cer_palsy_cert = '';

        $file_name_str = date("YmdHis") . '_' . rand(1000, 9999);


        //if ($validate_form_type == 'full') //THIS FIELDS VALIDATE ONLY IF USER CLICK ON SUBMIT II BUTTON
        {
          if ($_FILES['id_proof_file']['name'] != "") {

            $new_file_name1 = "id_proof_" . $file_name_str;

            $upload_data1 = $this->Ncvet_model->upload_file("id_proof_file", array('jpg', 'jpeg', 'png'), $new_file_name1, "./" . $id_proof_file_path, "jpg|jpeg|png", '', '', '', '', '5120', '', '', $new_file_name1);

            if ($upload_data1['response'] == 'error') {
              $data['id_proof_file_error'] = $upload_data1['message'];
              $error_flag = 1;
            } else if ($upload_data1['response'] == 'success') {
              $add_data['id_proof_file'] = $id_proof_file = $new_id_proof_file = $upload_data1['message'];
            }
          } else if (isset($_POST['old_candidate_id']) && $_POST['old_candidate_id'] != "" && isset($_POST['id_proof_file_old']) && $_POST['id_proof_file_old'] != "") {
            $id_proof_file_old = $this->security->xss_clean($this->input->post('id_proof_file_old'));
            if (isset($_POST['parent_table']) && $_POST['parent_table'] == "ncvet_candidates") {
              $add_data['id_proof_file'] = $id_proof_file = $new_id_proof_file = basename($id_proof_file_old);
            } else {
              $new_file_name1 = "id_proof_" . $file_name_str . '.' . strtolower(pathinfo($id_proof_file_old, PATHINFO_EXTENSION));
              if (copy(str_replace(base_url(), '', $id_proof_file_old), $id_proof_file_path . '/' . $new_file_name1)) {
                $add_data['id_proof_file'] = $id_proof_file = $new_id_proof_file = basename($new_file_name1);
              } else {
                $data['id_proof_file_error'] = 'Please upload valid Proof of Identity';
                $error_flag = 1;
              }
            }
          } else if (isset($_POST['id_proof_file_cropper']) && $_POST['id_proof_file_cropper'] != "") {

            $id_proof_file_cropper = $this->security->xss_clean($this->input->post('id_proof_file_cropper'));
            $new_file_name1 = "id_proof_" . $file_name_str . '.' . strtolower(pathinfo($id_proof_file_cropper, PATHINFO_EXTENSION));

            if (copy(str_replace(base_url(), '', $id_proof_file_cropper), $id_proof_file_path . '/' . $new_file_name1)) {
              $add_data['id_proof_file'] = $id_proof_file = $new_id_proof_file = basename($new_file_name1);
              //echo $_POST['id_proof_file_cropper'];
            } else {
              $data['id_proof_file_error'] = 'Please upload valid Proof of Identity';
              $error_flag = 1;
            }
          }

          if ($_FILES['aadhar_file']['name'] != "") {

            $new_file_name1 = "aadhar_" . $file_name_str;

            $upload_data1 = $this->Ncvet_model->upload_file("aadhar_file", array('jpg', 'jpeg', 'png'), $new_file_name1, "./" . $aadhar_file_path, "jpg|jpeg|png", '', '', '', '', '5120', '', '', $new_file_name1);

            if ($upload_data1['response'] == 'error') {
              $data['aadhar_file_error'] = $upload_data1['message'];
              $error_flag = 1;
            } else if ($upload_data1['response'] == 'success') {
              $add_data['aadhar_file'] = $aadhar_file = $new_aadhar_file = $upload_data1['message'];
            }
          } else if (isset($_POST['old_candidate_id']) && $_POST['old_candidate_id'] != "" && isset($_POST['aadhar_file_old']) && $_POST['aadhar_file_old'] != "") {
            $aadhar_file_old = $this->security->xss_clean($this->input->post('aadhar_file_old'));
            if (isset($_POST['parent_table']) && $_POST['parent_table'] == "ncvet_candidates") {
              $add_data['aadhar_file'] = $aadhar_file = $new_aadhar_file = basename($aadhar_file_old);
            } else {
              $new_file_name1 = "aadhar_" . $file_name_str . '.' . strtolower(pathinfo($aadhar_file_old, PATHINFO_EXTENSION));
              if (copy(str_replace(base_url(), '', $aadhar_file_old), $aadhar_file_path . '/' . $new_file_name1)) {
                $add_data['aadhar_file'] = $aadhar_file = $new_aadhar_file = basename($new_file_name1);
              } else {
                $data['aadhar_file_error'] = 'Please upload valid Proof of Identity';
                $error_flag = 1;
              }
            }
          } else if (isset($_POST['aadhar_file_cropper']) && $_POST['aadhar_file_cropper'] != "") {

            $aadhar_file_cropper = $this->security->xss_clean($this->input->post('aadhar_file_cropper'));
            $new_file_name1 = "aadhar_" . $file_name_str . '.' . strtolower(pathinfo($aadhar_file_cropper, PATHINFO_EXTENSION));

            if (copy(str_replace(base_url(), '', $aadhar_file_cropper), $aadhar_file_path . '/' . $new_file_name1)) {
              $add_data['aadhar_file'] = $aadhar_file = $new_aadhar_file = basename($new_file_name1);
              //echo $_POST['aadhar_file_cropper'];
            } else {
              $data['aadhar_file_error'] = 'Please upload valid Proof of Identity';
              $error_flag = 1;
            }
          }

          if ($_FILES['qualification_certificate_file']['name'] != "") {

            $new_file_name2 = "quali_cert_" . $file_name_str;

            $upload_data2 = $this->Ncvet_model->upload_file("qualification_certificate_file", array('jpg', 'jpeg', 'png', 'pdf'), $new_file_name2, "./" . $qualification_certificate_file_path, "jpg|jpeg|png|pdf", '', '', '', '', '5120', '', '', $new_file_name2);
            if ($upload_data2['response'] == 'error') {
              $data['qualification_certificate_file_error'] = $upload_data2['message'];
              $error_flag = 1;
            } else if ($upload_data2['response'] == 'success') {
              $add_data['qualification_certificate_file'] = $qualification_certificate_file = $new_qualification_certificate_file = $upload_data2['message'];
            }
          } else if (isset($_POST['old_candidate_id']) && $_POST['old_candidate_id'] != "" && isset($_POST['qualification_certificate_file_old']) && $_POST['qualification_certificate_file_old'] != "") {
            $qualification_certificate_file_old = $this->security->xss_clean($this->input->post('qualification_certificate_file_old'));
            if (isset($_POST['parent_table']) && $_POST['parent_table'] == "ncvet_candidates") {
              $add_data['qualification_certificate_file'] = $qualification_certificate_file = $new_qualification_certificate_file = basename($qualification_certificate_file_old);
            } else {
              $new_file_name4 = "quali_cert_" . $file_name_str . '.' . strtolower(pathinfo($qualification_certificate_file_old, PATHINFO_EXTENSION));
              if (copy(str_replace(base_url(), '', $qualification_certificate_file_old), $qualification_certificate_file_path . '/' . $new_file_name4)) {
                $add_data['qualification_certificate_file'] = $qualification_certificate_file = $new_qualification_certificate_file = basename($new_file_name4);
              } else {
                $data['qualification_certificate_file_error'] = 'Please upload valid Qualification Certificate Photo';
                $error_flag = 1;
              }
            }
          } else if (isset($_POST['qualification_certificate_file_cropper']) && $_POST['qualification_certificate_file_cropper'] != "") {
            $qualification_certificate_file_cropper = $this->security->xss_clean($this->input->post('qualification_certificate_file_cropper'));

            $new_file_name4 = "quali_cert_" . $file_name_str . '.' . strtolower(pathinfo($qualification_certificate_file_cropper, PATHINFO_EXTENSION));
            if (copy(str_replace(base_url(), '', $qualification_certificate_file_cropper), $qualification_certificate_file_path . '/' . $new_file_name4)) {
              $add_data['qualification_certificate_file'] = $qualification_certificate_file = $new_qualification_certificate_file = basename($new_file_name4);
            } else {
              $data['qualification_certificate_file_error'] = 'Please upload valid Qualification Certificate Photo';
              $error_flag = 1;
            }
          }

          if ($_FILES['candidate_photo']['name'] != "") {

            $new_file_name3 = "photo_" . $file_name_str;

            $upload_data3 = $this->Ncvet_model->upload_file("candidate_photo", array('jpg', 'jpeg', 'png'), $new_file_name3, "./" . $candidate_photo_path, "jpg|jpeg|png", '', '', '', '', '20', '', '', $new_file_name3);
            if ($upload_data3['response'] == 'error') {
              $data['candidate_photo_error'] = $upload_data3['message'];
              $error_flag = 1;
            } else if ($upload_data3['response'] == 'success') {
              $add_data['candidate_photo'] = $candidate_photo = $new_candidate_photo = $upload_data3['message'];
            }
          } else if (isset($_POST['old_candidate_id']) && $_POST['old_candidate_id'] != "" && isset($_POST['candidate_photo_old']) && $_POST['candidate_photo_old'] != "") {
            $candidate_photo_old = $this->security->xss_clean($this->input->post('candidate_photo_old'));
            if (isset($_POST['parent_table']) && $_POST['parent_table'] == "ncvet_candidates") {
              $add_data['candidate_photo'] = $candidate_photo = $new_candidate_photo = basename($candidate_photo_old);
            } else {
              $new_file_name3 = "photo_" . $file_name_str . '.' . strtolower(pathinfo($candidate_photo_old, PATHINFO_EXTENSION));
              if (copy(str_replace(base_url(), '', $candidate_photo_old), $candidate_photo_path . '/' . $new_file_name3)) {
                $add_data['candidate_photo'] = $candidate_photo = $new_candidate_photo = basename($new_file_name3);
              } else {
                $data['candidate_photo_error'] = 'Please upload valid Passport-size Photo';
                $error_flag = 1;
              }
            }
          } else if (isset($_POST['candidate_photo_cropper']) && $_POST['candidate_photo_cropper'] != "") {
            $candidate_photo_cropper = $this->security->xss_clean($this->input->post('candidate_photo_cropper'));

            $new_file_name3 = "photo_" . $file_name_str . '.' . strtolower(pathinfo($candidate_photo_cropper, PATHINFO_EXTENSION));
            if (copy(str_replace(base_url(), '', $candidate_photo_cropper), $candidate_photo_path . '/' . $new_file_name3)) {
              $add_data['candidate_photo'] = $candidate_photo = $new_candidate_photo = basename($new_file_name3);
            } else {
              $data['candidate_photo_error'] = 'Please upload valid Passport-size Photo';
              $error_flag = 1;
            }
          }

          if ($_FILES['candidate_sign']['name'] != "") {

            $new_file_name4 = "sign_" . $file_name_str;

            $upload_data4 = $this->Ncvet_model->upload_file("candidate_sign", array('jpg', 'jpeg', 'png'), $new_file_name4, "./" . $candidate_sign_path, "jpg|jpeg|png", '', '', '', '', '20', '', '', $new_file_name4);
            if ($upload_data4['response'] == 'error') {
              $data['candidate_sign_error'] = $upload_data4['message'];
              $error_flag = 1;
            } else if ($upload_data4['response'] == 'success') {
              $add_data['candidate_sign'] = $candidate_sign = $new_candidate_sign = $upload_data4['message'];
            }
          } else if (isset($_POST['old_candidate_id']) && $_POST['old_candidate_id'] != "" && isset($_POST['candidate_sign_old']) && $_POST['candidate_sign_old'] != "") {
            $candidate_sign_old = $this->security->xss_clean($this->input->post('candidate_sign_old'));
            if (isset($_POST['parent_table']) && $_POST['parent_table'] == "ncvet_candidates") {
              $add_data['candidate_sign'] = $candidate_sign = $new_candidate_sign = basename($candidate_sign_old);
            } else {
              $new_file_name4 = "sign_" . $file_name_str . '.' . strtolower(pathinfo($candidate_sign_old, PATHINFO_EXTENSION));
              if (copy(str_replace(base_url(), '', $candidate_sign_old), $candidate_sign_path . '/' . $new_file_name4)) {
                $add_data['candidate_sign'] = $candidate_sign = $new_candidate_sign = basename($new_file_name4);
              } else {
                $data['candidate_sign_error'] = 'Please upload valid Signature of the Candidate';
                $error_flag = 1;
              }
            }
          } else if (isset($_POST['candidate_sign_cropper']) && $_POST['candidate_sign_cropper'] != "") {
            $candidate_sign_cropper = $this->security->xss_clean($this->input->post('candidate_sign_cropper'));
            $new_file_name4 = "sign_" . $file_name_str . '.' . strtolower(pathinfo($candidate_sign_cropper, PATHINFO_EXTENSION));
            if (copy(str_replace(base_url(), '', $candidate_sign_cropper), $candidate_sign_path . '/' . $new_file_name4)) {
              $add_data['candidate_sign'] = $candidate_sign = $new_candidate_sign = basename($new_file_name4);
            } else {
              $data['candidate_sign_error'] = 'Please upload valid Signature of the Candidate';
              $error_flag = 1;
            }
          }

          if ($_FILES['declarationform']['name'] != "") {

            $new_file_name4 = "declaration_" . $file_name_str;

            $upload_data5 = $this->Ncvet_model->upload_file("declarationform", array('jpg', 'jpeg', 'png', 'pdf'), $new_file_name4, "./" . $declarationform_path, "jpg|jpeg|png|pdf", '', '', '', '', '20', '', '', $new_file_name4);
            if ($upload_data5['response'] == 'error') {
              $data['declarationform_error'] = $upload_data5['message'];
              $error_flag = 1;
            } else if ($upload_data5['response'] == 'success') {
              $add_data['declarationform'] = $declarationform = $new_declarationform = $upload_data5['message'];
            }
          } else if (isset($_POST['old_candidate_id']) && $_POST['old_candidate_id'] != "" && isset($_POST['declarationform_old']) && $_POST['declarationform_old'] != "") {
            $declarationform_old = $this->security->xss_clean($this->input->post('declarationform_old'));
            if (isset($_POST['parent_table']) && $_POST['parent_table'] == "ncvet_candidates") {
              $add_data['declarationform'] = $declarationform = $new_declarationform = basename($declarationform_old);
            } else {
              $new_file_name4 = "declaration_" . $file_name_str . '.' . strtolower(pathinfo($declarationform_old, PATHINFO_EXTENSION));
              if (copy(str_replace(base_url(), '', $declarationform_old), $declarationform_path . '/' . $new_file_name4)) {
                $add_data['declarationform'] = $declarationform = $new_declarationform = basename($new_file_name4);
              } else {
                $data['declarationform_error'] = 'Please upload valid declaration of the Candidate';
                $error_flag = 1;
              }
            }
          } else if (isset($_POST['declarationform_cropper']) && $_POST['declarationform_cropper'] != "") {
            $declarationform_cropper = $this->security->xss_clean($this->input->post('declarationform_cropper'));
            $new_file_name4 = "declaration_" . $file_name_str . '.' . strtolower(pathinfo($declarationform_cropper, PATHINFO_EXTENSION));
            if (copy(str_replace(base_url(), '', $declarationform_cropper), $declarationform_path . '/' . $new_file_name4)) {
              $add_data['declarationform'] = $declarationform = $new_declarationform = basename($new_file_name4);
            } else {
              $data['declarationform_error'] = 'Please upload valid declaration of the Candidate';
              $error_flag = 1;
            }
          }

          if ($_FILES['scanned_vis_imp_cert']['name'] != "") {

            $new_file_name5 = "vis_imp_" . $file_name_str;

            $upload_data5 = $this->Ncvet_model->upload_file("scanned_vis_imp_cert", array('jpg', 'jpeg', 'png', 'pdf'), $new_file_name5, "./" . $disability_path, "jpg|jpeg|png|pdf", '', '', '', '', '20', '', '', $new_file_name5);
            if ($upload_data5['response'] == 'error') {
              $data['scanned_vis_imp_cert_error'] = $upload_data5['message'];
              $error_flag = 1;
            } else if ($upload_data5['response'] == 'success') {
              $add_data['vis_imp_cert_img'] = $scanned_vis_imp_cert = $new_scanned_vis_imp_cert = $upload_data5['message'];
            }
          } else if (isset($_POST['old_candidate_id']) && $_POST['old_candidate_id'] != "" && isset($_POST['scanned_vis_imp_cert_old']) && $_POST['scanned_vis_imp_cert_old'] != "") {
            $scanned_vis_imp_cert_old = $this->security->xss_clean($this->input->post('scanned_vis_imp_cert_old'));
            if (isset($_POST['parent_table']) && $_POST['parent_table'] == "ncvet_candidates") {
              $add_data['vis_imp_cert_img'] = $scanned_vis_imp_cert = $new_scanned_vis_imp_cert = basename($scanned_vis_imp_cert_old);
            } else {
              $new_file_name5 = "vis_imp_" . $file_name_str . '.' . strtolower(pathinfo($scanned_vis_imp_cert_old, PATHINFO_EXTENSION));
              if (copy(str_replace(base_url(), '', $scanned_vis_imp_cert_old), $disability_path . '/' . $new_file_name5)) {
                $add_data['vis_imp_cert_img'] = $scanned_vis_imp_cert = $new_scanned_vis_imp_cert = basename($new_file_name5);
              } else {
                $data['scanned_vis_imp_cert_error'] = 'Please upload valid PWD Certificate of the Candidate';
                $error_flag = 1;
              }
            }
          } else if (isset($_POST['scanned_vis_imp_cert_cropper']) && $_POST['scanned_vis_imp_cert_cropper'] != "") {
            $scanned_vis_imp_cert_cropper = $this->security->xss_clean($this->input->post('scanned_vis_imp_cert_cropper'));
            $new_file_name5 = "vis_imp_" . $file_name_str . '.' . strtolower(pathinfo($scanned_vis_imp_cert_cropper, PATHINFO_EXTENSION));
            if (copy(str_replace(base_url(), '', $scanned_vis_imp_cert_cropper), $disability_path . '/' . $new_file_name5)) {
              $add_data['vis_imp_cert_img'] = $scanned_vis_imp_cert = $new_scanned_vis_imp_cert = basename($new_file_name5);
            } else {
              $data['scanned_vis_imp_cert_error'] = 'Please upload valid PWD Certificate of the Candidate';
              $error_flag = 1;
            }
          }

          if ($_FILES['scanned_orth_han_cert']['name'] != "") {

            $new_file_name6 = "orth_han_" . $file_name_str;

            $upload_data6 = $this->Ncvet_model->upload_file("scanned_orth_han_cert", array('jpg', 'jpeg', 'png', 'pdf'), $new_file_name6, "./" . $disability_path, "jpg|jpeg|png|pdf", '', '', '', '', '20', '', '', $new_file_name6);
            if ($upload_data6['response'] == 'error') {
              $data['scanned_orth_han_cert_error'] = $upload_data6['message'];
              $error_flag = 1;
            } else if ($upload_data6['response'] == 'success') {
              $add_data['orth_han_cert_img'] = $scanned_orth_han_cert = $new_scanned_orth_han_cert = $upload_data6['message'];
            }
          } else if (isset($_POST['old_candidate_id']) && $_POST['old_candidate_id'] != "" && isset($_POST['scanned_orth_han_cert_old']) && $_POST['scanned_orth_han_cert_old'] != "") {
            $scanned_orth_han_cert_old = $this->security->xss_clean($this->input->post('scanned_orth_han_cert_old'));
            if (isset($_POST['parent_table']) && $_POST['parent_table'] == "ncvet_candidates") {
              $add_data['orth_han_cert_img'] = $scanned_orth_han_cert = $new_scanned_orth_han_cert = basename($scanned_orth_han_cert_old);
            } else {
              $new_file_name6 = "orth_han_" . $file_name_str . '.' . strtolower(pathinfo($scanned_orth_han_cert_old, PATHINFO_EXTENSION));
              if (copy(str_replace(base_url(), '', $scanned_orth_han_cert_old), $disability_path . '/' . $new_file_name6)) {
                $add_data['orth_han_cert_img'] = $scanned_orth_han_cert = $new_scanned_orth_han_cert = basename($new_file_name6);
              } else {
                $data['scanned_orth_han_cert_error'] = 'Please upload valid PWD Certificate of the Candidate';
                $error_flag = 1;
              }
            }
          } else if (isset($_POST['scanned_orth_han_cert_cropper']) && $_POST['scanned_orth_han_cert_cropper'] != "") {
            $scanned_orth_han_cert_cropper = $this->security->xss_clean($this->input->post('scanned_orth_han_cert_cropper'));
            $new_file_name6 = "orth_han_" . $file_name_str . '.' . strtolower(pathinfo($scanned_orth_han_cert_cropper, PATHINFO_EXTENSION));
            if (copy(str_replace(base_url(), '', $scanned_orth_han_cert_cropper), $disability_path . '/' . $new_file_name6)) {
              $add_data['orth_han_cert_img'] = $scanned_orth_han_cert = $new_scanned_orth_han_cert = basename($new_file_name6);
            } else {
              $data['scanned_orth_han_cert_error'] = 'Please upload valid PWD Certificate of the Candidate';
              $error_flag = 1;
            }
          }

          if ($_FILES['scanned_cer_palsy_cert']['name'] != "") {

            $new_file_name7 = "cer_palsy_" . $file_name_str;

            $upload_data7 = $this->Ncvet_model->upload_file("scanned_cer_palsy_cert", array('jpg', 'jpeg', 'png', 'pdf'), $new_file_name7, "./" . $disability_path, "jpg|jpeg|png|pdf", '', '', '', '', '20', '', '', $new_file_name7);
            if ($upload_data7['response'] == 'error') {
              $data['scanned_cer_palsy_cert_error'] = $upload_data7['message'];
              $error_flag = 1;
            } else if ($upload_data7['response'] == 'success') {
              $add_data['cer_palsy_cert_img'] = $scanned_cer_palsy_cert = $new_scanned_cer_palsy_cert = $upload_data7['message'];
            }
          } else if (isset($_POST['old_candidate_id']) && $_POST['old_candidate_id'] != "" && isset($_POST['scanned_cer_palsy_cert_old']) && $_POST['scanned_cer_palsy_cert_old'] != "") {
            $scanned_cer_palsy_cert_old = $this->security->xss_clean($this->input->post('scanned_cer_palsy_cert_old'));
            if (isset($_POST['parent_table']) && $_POST['parent_table'] == "ncvet_candidates") {
              $add_data['cer_palsy_cert_img'] = $scanned_cer_palsy_cert = $new_scanned_cer_palsy_cert = basename($scanned_cer_palsy_cert_old);
            } else {
              $new_file_name7 = "cer_palsy_" . $file_name_str . '.' . strtolower(pathinfo($scanned_cer_palsy_cert_old, PATHINFO_EXTENSION));
              if (copy(str_replace(base_url(), '', $scanned_cer_palsy_cert_old), $disability_path . '/' . $new_file_name7)) {
                $add_data['cer_palsy_cert_img'] = $scanned_cer_palsy_cert = $new_scanned_cer_palsy_cert = basename($new_file_name7);
              } else {
                $data['scanned_cer_palsy_cert_error'] = 'Please upload valid PWD Certificate of the Candidate';
                $error_flag = 1;
              }
            }
          } else if (isset($_POST['scanned_cer_palsy_cert_cropper']) && $_POST['scanned_cer_palsy_cert_cropper'] != "") {
            $scanned_cer_palsy_cert_cropper = $this->security->xss_clean($this->input->post('scanned_cer_palsy_cert_cropper'));
            $new_file_name7 = "cer_palsy_" . $file_name_str . '.' . strtolower(pathinfo($scanned_cer_palsy_cert_cropper, PATHINFO_EXTENSION));
            if (copy(str_replace(base_url(), '', $scanned_cer_palsy_cert_cropper), $disability_path . '/' . $new_file_name7)) {
              $add_data['cer_palsy_cert_img'] = $scanned_cer_palsy_cert = $new_scanned_cer_palsy_cert = basename($new_file_name7);
            } else {
              $data['scanned_cer_palsy_cert_error'] = 'Please upload valid PWD Certificate of the Candidate';
              $error_flag = 1;
            }
          }

          if ($_FILES['institute_idproof']['name'] != "") {

            $new_file_name8 = "inst_id_" . $file_name_str;

            $upload_data8 = $this->Ncvet_model->upload_file("institute_idproof", array('jpg', 'jpeg', 'png', 'pdf'), $new_file_name8, "./" . $institute_idproof_path, "jpg|jpeg|png|pdf", '', '', '', '', '20', '', '', $new_file_name8);
            if ($upload_data8['response'] == 'error') {
              $data['institute_idproof_error'] = $upload_data8['message'];
              $error_flag = 1;
            } else if ($upload_data8['response'] == 'success') {
              $add_data['institute_idproof'] = $institute_idproof = $new_institute_idproof = $upload_data8['message'];
            }
          } else if (isset($_POST['old_candidate_id']) && $_POST['old_candidate_id'] != "" && isset($_POST['institute_idproof_old']) && $_POST['institute_idproof_old'] != "") {
            $institute_idproof_old = $this->security->xss_clean($this->input->post('institute_idproof_old'));
            if (isset($_POST['parent_table']) && $_POST['parent_table'] == "ncvet_candidates") {
              $add_data['institute_idproof'] = $institute_idproof = $new_institute_idproof = basename($institute_idproof_old);
            } else {
              $new_file_name8 = "inst_id_" . $file_name_str . '.' . strtolower(pathinfo($institute_idproof_old, PATHINFO_EXTENSION));
              if (copy(str_replace(base_url(), '', $institute_idproof_old), $institute_idproof_path . '/' . $new_file_name8)) {
                $add_data['institute_idproof'] = $institute_idproof = $new_institute_idproof = basename($new_file_name8);
              } else {
                $data['institute_idproof_error'] = 'Please upload valid Institute ID Proof of the Candidate';
                $error_flag = 1;
              }
            }
          } else if (isset($_POST['institute_idproof_cropper']) && $_POST['institute_idproof_cropper'] != "") {
            $institute_idproof_cropper = $this->security->xss_clean($this->input->post('institute_idproof_cropper'));
            $new_file_name8 = "inst_id_" . $file_name_str . '.' . strtolower(pathinfo($institute_idproof_cropper, PATHINFO_EXTENSION));
            if (copy(str_replace(base_url(), '', $institute_idproof_cropper), $institute_idproof_path . '/' . $new_file_name8)) {
              $add_data['institute_idproof'] = $institute_idproof = $new_institute_idproof = basename($new_file_name8);
            } else {
              $data['institute_idproof_error'] = 'Please upload valid Institute ID Proof of the Candidate';
              $error_flag = 1;
            }
          }

          if ($_FILES['exp_certificate']['name'] != "") {

            $new_file_name10 = "exp_cert_" . $file_name_str;

            $upload_data10 = $this->Ncvet_model->upload_file("exp_certificate", array('jpg', 'jpeg', 'png', 'pdf'), $new_file_name10, "./" . $exp_certificate_path, "jpg|jpeg|png|pdf", '', '', '', '', '20', '', '', $new_file_name10);
            if ($upload_data10['response'] == 'error') {
              $data['exp_certificate_error'] = $upload_data10['message'];
              $error_flag = 1;
            } else if ($upload_data10['response'] == 'success') {
              $add_data['exp_certificate'] = $exp_certificate = $new_exp_certificate = $upload_data10['message'];
            }
          } else if (isset($_POST['old_candidate_id']) && $_POST['old_candidate_id'] != "" && isset($_POST['exp_certificate_old']) && $_POST['exp_certificate_old'] != "") {
            $exp_certificate_old = $this->security->xss_clean($this->input->post('exp_certificate_old'));
            if (isset($_POST['parent_table']) && $_POST['parent_table'] == "ncvet_candidates") {
              $add_data['exp_certificate'] = $exp_certificate = $new_exp_certificate = basename($exp_certificate_old);
            } else {
              $new_file_name10 = "exp_cert_" . $file_name_str . '.' . strtolower(pathinfo($exp_certificate_old, PATHINFO_EXTENSION));
              if (copy(str_replace(base_url(), '', $exp_certificate_old), $exp_certificate_path . '/' . $new_file_name10)) {
                $add_data['exp_certificate'] = $exp_certificate = $new_exp_certificate = basename($new_file_name10);
              } else {
                $data['exp_certificate_error'] = 'Please upload valid Experience Certificate of the Candidate';
                $error_flag = 1;
              }
            }
          } else if (isset($_POST['exp_certificate_cropper']) && $_POST['exp_certificate_cropper'] != "") {
            $exp_certificate_cropper = $this->security->xss_clean($this->input->post('exp_certificate_cropper'));
            $new_file_name10 = "exp_cert_" . $file_name_str . '.' . strtolower(pathinfo($exp_certificate_cropper, PATHINFO_EXTENSION));
            if (copy(str_replace(base_url(), '', $exp_certificate_cropper), $exp_certificate_path . '/' . $new_file_name10)) {
              $add_data['exp_certificate'] = $exp_certificate = $new_exp_certificate = basename($new_file_name10);
            } else {
              $data['exp_certificate_error'] = 'Please upload valid Experience Certificate of the Candidate';
              $error_flag = 1;
            }
          }
        }

        if ($error_flag == 1) {
          @unlink("./" . $id_proof_file_path . "/" . $upload_data1['message']);
          @unlink("./" . $aadhar_file_path . "/" . $upload_data1['message']);
          @unlink("./" . $qualification_certificate_file_path . "/" . $upload_data2['message']);
          @unlink("./" . $candidate_photo_path . "/" . $upload_data3['message']);
          @unlink("./" . $candidate_sign_path . "/" . $upload_data4['message']);
          @unlink("./" . $scanned_vis_imp_cert_path . "/" . $upload_data5['message']);
          @unlink("./" . $scanned_orth_han_cert_path . "/" . $upload_data5['message']);
          @unlink("./" . $scanned_cer_palsy_cert_path . "/" . $upload_data7['message']);
          @unlink("./" . $institute_idproof_path . "/" . $upload_data8['message']);
          @unlink("./" . $declarationform_path . "/" . $upload_data4['message']);

          /*echo 'upload_data2='.$upload_data1['message'].'==upload_data2'.$upload_data2['message'].'=upload_data3='.$upload_data3['message'].'=upload_data4='.$upload_data4['message'].'=upload_data5='.$upload_data5['message'].'=upload_data6='.$upload_data6['message'].'=upload_data7='.$upload_data7['message'].'=upload_data8='.$upload_data8['message'];
          echo'error=<pre>';print_r($add_data);
            echo'<pre>';print_r($_POST);
            exit;*/
        } else if ($error_flag == 0) {
          $posted_arr = json_encode($_POST) . ' >> ' . json_encode($_FILES);




          $add_data['salutation'] = $this->input->post('salutation');
          $add_data['first_name'] = $this->input->post('first_name');
          $add_data['middle_name'] = $this->input->post('middle_name');
          $add_data['last_name'] = $this->input->post('last_name');
          $add_data['dob'] = $this->input->post('dob');
          $add_data['gender'] = $this->input->post('gender');
          $add_data['guardian_salutation'] = $this->input->post('guardian_salutation');
          $add_data['guardian_name'] = $this->input->post('guardian_name');
          $add_data['mobile_no'] = $this->session->userdata('ncvet_verified_mobile'); //$_POST['mobile_no'];
          $add_data['email_id'] = strtolower($this->session->userdata('ncvet_verified_email'));


          $add_data['address1'] = $this->input->post('address1');
          $add_data['address2'] = $this->input->post('address2');
          $add_data['address3'] = $this->input->post('address3');

          $add_data['state'] = $this->input->post('state');
          $add_data['city'] = $this->input->post('city');
          $add_data['district'] = $this->input->post('district');
          $add_data['pincode'] = $this->input->post('pincode');
          $add_data['address1_pr'] = $this->input->post('address1_pr');
          $add_data['address2_pr'] = $this->input->post('address2_pr');
          $add_data['address3_pr'] = $this->input->post('address3_pr');
          $add_data['state_pr'] = $this->input->post('state_pr');
          $add_data['city_pr'] = $this->input->post('city_pr');
          $add_data['district_pr'] = $this->input->post('district_pr');
          $add_data['pincode_pr'] = $this->input->post('pincode_pr');

          $add_data['qualification'] = $this->input->post('qualification');
          $add_data['qualification_state'] = $this->input->post('qualification_state');

          if ($_POST['qualification'] != 1 && $_POST['qualification'] != 2) {
            $add_data['qualification_certificate_file'] = '';
          }
          if ($_POST['qualification'] == 1) {
            $add_data['experience'] = $this->input->post('experience');
          } else
            $add_data['experience'] = 'N';

          if ($_POST['experience'] != 'Y') {
            $add_data['exp_certificate'] = '';
          }



          if ($_POST['qualification'] == 3 || $_POST['qualification'] == 4) {
            $add_data['semester'] = $_POST['semester'];
            $add_data['collage'] = $this->input->post('collage');
            $add_data['university'] = $this->input->post('university');
          }


          if ($_POST['qualification'] != 3 && $_POST['qualification'] != 4) {
            $add_data['semester'] = '';
            $add_data['collage'] = '';
            $add_data['university'] = '';
            $add_data['institute_idproof'] = '';
            $add_data['declarationform'] = '';
          }
          $add_data['id_proof_number'] = $this->input->post('id_proof_number');
          $add_data['aadhar_no'] = $this->input->post('aadhar_no');

          $add_data['registration_type'] = 'NM';

          $add_data['benchmark_disability'] = $this->input->post('benchmark_disability');
          if ($add_data['benchmark_disability'] == 'Y') {
            $add_data['visually_impaired'] = $this->input->post('visually_impaired');
            $add_data['orthopedically_handicapped'] = $this->input->post('orthopedically_handicapped');
            $add_data['cerebral_palsy'] = $this->input->post('cerebral_palsy');
          }
          if ($add_data['visually_impaired'] != 'Y') {
            $add_data['vis_imp_cert_img'] = '';
          }
          if ($add_data['orthopedically_handicapped'] != 'Y') {
            $add_data['orth_han_cert_img'] = '';
          }
          if ($add_data['cerebral_palsy'] != 'Y') {
            $add_data['cer_palsy_cert_img'] = '';
          }


          //$add_data['hold_release_status'] = '3';
          $add_data['ip_address'] = get_ip_address(); //general_helper.php   


          {
            //START : IF FILE NOT EXIST WHILE ADDING / UPDATING THE RECORD, THEN REDIRECT & SHOW ERROR MESSAGE
            $chk_id_proof_file =  $chk_aadhar_file = $chk_qualification_certificate_file = $chk_candidate_photo = $chk_candidate_sign = ''; {
              $chk_id_proof_file = $id_proof_file;
              $chk_aadhar_file = $aadhar_file;
              $chk_qualification_certificate_file = $qualification_certificate_file;
              $chk_candidate_photo = $candidate_photo;
              $chk_candidate_sign = $candidate_sign;
            }
          }

          if ($mode == "Add") {

            $add_data['created_on'] = date("Y-m-d H:i:s");


            $this->session->set_userdata('enduserinfo', $add_data);

            $this->Ncvet_model->insert_common_log('Candidate Enrollment session', 'ncvet_candidates', 'Candidate Enrollment session', 'Candidate Enrollment session', 'candidate_action', 'The candidate Enrollment session', serialize($add_data));

            $this->form_validation->set_message('error', "");
            redirect(base_url() . 'ncvet/candidate_registration/preview');
          }
        }
      } else {
        echo '<h3>Validation Failed</h3>';
        echo validation_errors('<p style="color:red;">', '</p>');
        /*echo '<pre>'; print_r($_POST); echo '</pre>';
                    $var_errors = str_replace("<p>", "<span>", $var_errors);
                    $var_errors = str_replace("</p>", "</span><br>", $var_errors);
                    echo $var_errors;
                    exit;*/
      }
    }

    $data['page_title'] = 'NCVET - Candidate Enrollment';

    $data['state_master_data'] = $this->master_model->getRecords('state_master', array('state_delete' => '0', 'id !=' => '11'), 'id, state_code, state_no, exempt, state_name', array('state_name' => 'ASC'));
    $this->load->helper('captcha');
    $data['captcha_img'] = generate_captcha('NCVET_CANDIDATE_ENROLLMENT_CAPTCHA', 6);

    $this->load->view('ncvet/candidate_registration', $data);
  }
  /******** END : ADD  CANDIDATES DATA ********/


  public function cookie_msg()
  {
    $data = array('middle_content' => 'cookie_msg');
    $this->load->view('ncvet/register_cookie_msg', $data);
  }
  public function preview()
  {
    if (!$this->session->userdata('enduserinfo')) {
      redirect(base_url() . '/ncvet/candidate_registration');
    }
    // echo'<pre>';print_r($this->session->userdata['enduserinfo']);
    //check email,mobile duplication on the same time from different browser!!
    $endTime    = date("H:i:s");
    $start_time = date("H:i:s", strtotime("-20 minutes", strtotime($endTime)));
    //  echo'<pre>'.$this->session->userdata('ncvet_verified_mobile');print_r($this->session->userdata['enduserinfo']);
    $this->db->where('Time(created_on) BETWEEN "' . $start_time . '" and "' . $endTime . '"');
    $this->db->where('email_id', $this->session->userdata['enduserinfo']['email_id']);
    $this->db->or_where('mobile_no', $this->session->userdata['enduserinfo']['mobile_no']);
    $check_duplication = $this->master_model->getRecords('ncvet_candidates', array('is_active' => 0));

    if (count($check_duplication) > 0) {
      //echo $this->db->last_query();exit;
      //redirect(base_url() . 'ncvet/candidate_registration/cookie_msg');
    }


    $data['state_master_data'] = $this->master_model->getRecords('state_master', array('state_delete' => '0', 'id !=' => '11'), 'id, state_code, state_no, exempt, state_name', array('state_name' => 'ASC'));
    $data['candidate_data'] = $this->session->userdata['enduserinfo'];
    //echo'<pre>';print_r($this->session->userdata['enduserinfo']);exit;
    $data['id_proof_file_path'] = $id_proof_file_path = $this->id_proof_file_path;
    $data['aadhar_file_path'] = $aadhar_file_path = $this->aadhar_file_path;
    $data['qualification_certificate_file_path'] = $qualification_certificate_file_path = $this->qualification_certificate_file_path;
    $data['candidate_photo_path'] = $candidate_photo_path = $this->candidate_photo_path;
    $data['candidate_sign_path'] = $candidate_sign_path = $this->candidate_sign_path;
    $data['exp_certificate_path'] = $exp_certificate_path = $this->exp_certificate_path;
    $data['institute_idproof_path'] = $institute_idproof_path = $this->institute_idproof_path;
    $data['declarationform_path'] = $declarationform_path = $this->declarationform_path;
    $data['disability_path'] = $disability_path = $this->disability_path;

    $this->load->view('ncvet/preview_register', $data);
  }

  //Genereate random password function
  public function generate_random_password($length = 8, $level = 2) // function to generate new password

  {
    list($usec, $sec) = explode(' ', microtime());
    srand((float) $sec + ((float) $usec * 100000));
    $validchars[1] = "0123456789abcdfghjkmnpqrstvwxyz";
    $validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $validchars[3] = "0123456789_!@#*()-=+abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#*()-=+";
    $password      = "";
    $counter       = 0;
    while ($counter < $length) {
      $actChar = substr($validchars[$level], rand(0, strlen($validchars[$level]) - 1), 1);
      if (!strstr($password, $actChar)) {
        $password .= $actChar;
        $counter++;
      }
    }
    return $password;
  }

  function check_email_mobile_otp_verification($str = '', $type = '')
  {
    $flag = '';
    $message = 'Please verify the email id or mobile no';

    if ($type != '' && ($type == 'email_id' || $type == 'mobile_no')) {
      $this->db->where_in('otp_type', array(3, 4));
      $this->db->limit(1);
      $otp_data = $this->master_model->getRecords('ncvet_candidate_login_otp', array($type => $str), 'email_id, otp, is_validate, created_on, DATE(otp_expired_on) AS OtpExpiryDate', array('otp_id' => 'DESC'));
      if (count($otp_data) > 0) {
        if ($otp_data[0]['is_validate'] == '1' && $otp_data[0]['OtpExpiryDate'] >= date('Y-m-d')) {
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
  }
  public function addmember()
  {
    $this->load->helper('update_image_name_helper');
    $aadhar_card = '';
    if (!$this->session->userdata['enduserinfo']) {
      redirect(base_url());
    }
    // echo'<pre>';print_r($this->session->userdata['enduserinfo']);exit;
    //check email,mobile duplication on the same time from different browser!!
    $endTime    = date("H:i:s");
    $start_time = date("H:i:s", strtotime("-20 minutes", strtotime($endTime)));
    $this->db->where('Time(created_on) BETWEEN "' . $start_time . '" and "' . $endTime . '"');
    $this->db->where('email_id', $this->session->userdata['enduserinfo']['email_id']);
    $this->db->or_where('mobile_no', $this->session->userdata['enduserinfo']['mobile_no']);
    $check_duplication = $this->master_model->getRecords('ncvet_candidates', array('is_active' => 0));

    if (count($check_duplication) > 0) {
      //redirect(base_url() . 'ncvet/candidate_registration/cookie_msg');
    }

    $password = $this->generate_random_password();
    include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
    $key = $this->config->item('pass_key');
    $aes = new CryptAES();
    $aes->set_key(base64_decode($key));
    $aes->require_pkcs5();
    $encPass = $aes->encrypt($password);

    /* Benchmark */
    $benchmark_disability        = $this->session->userdata['enduserinfo']['benchmark_disability'];
    $scanned_vis_imp_cert_file   = $this->session->userdata['enduserinfo']['vis_imp_cert_img'];
    $scanned_orth_han_cert_file  = $this->session->userdata['enduserinfo']['orth_han_cert_img'];
    $scanned_cer_palsy_cert_file = $this->session->userdata['enduserinfo']['cer_palsy_cert_img'];

    $visually_impaired          = $this->session->userdata['enduserinfo']['visually_impaired'];
    $orthopedically_handicapped = $this->session->userdata['enduserinfo']['orthopedically_handicapped'];
    $cerebral_palsy             = $this->session->userdata['enduserinfo']['cerebral_palsy'];
    if ($benchmark_disability == 'N') {
      $scanned_vis_imp_cert_file   = '';
      $scanned_orth_han_cert_file  = '';
      $scanned_cer_palsy_cert_file = '';
      $visually_impaired           = '';
      $orthopedically_handicapped  = '';
      $cerebral_palsy              = '';
    }
    if ($visually_impaired == 'N') {
      $scanned_vis_imp_cert_file = '';
    }
    if ($orthopedically_handicapped == 'N') {
      $scanned_orth_han_cert_file = '';
    }
    if ($cerebral_palsy == 'N') {
      $scanned_cer_palsy_cert_file = '';
    }

    if ($benchmark_disability == 'Y') {
      if ($visually_impaired == 'N' && $orthopedically_handicapped == 'N' && $cerebral_palsy == 'N') {
        $benchmark_disability = 'N';
      }
    }

    $email_verification_flag = $this->check_email_mobile_otp_verification($this->session->userdata['enduserinfo']['email_id'], 'email_id');
    if ($email_verification_flag == false) {
      $this->session->set_flashdata('error', 'The OTP is not verified for email ' . $this->session->userdata['enduserinfo']['email_id']);
      redirect(site_url('ncvet/candidate_registration'));
    }

    $mobile_verification_flag = $this->check_email_mobile_otp_verification($this->session->userdata['enduserinfo']['mobile_no'], 'mobile_no');
    if ($mobile_verification_flag == false) {
      $this->session->set_flashdata('error', 'The OTP is not verified for mobile ' . $this->session->userdata['enduserinfo']['mobile_no']);
      redirect(site_url('ncvet/candidate_registration'));
    }


    $insert_info = array(
      'password'                    => $encPass,
      'salutation'                  => $this->session->userdata['enduserinfo']['salutation'],
      'first_name'                  => $this->session->userdata['enduserinfo']['first_name'],
      'middle_name'                 => $this->session->userdata['enduserinfo']['middle_name'],
      'last_name'                   => $this->session->userdata['enduserinfo']['last_name'],
      'dob'                       => date('Y-m-d', strtotime($this->session->userdata['enduserinfo']['dob'])),
      'gender'                     => $this->session->userdata['enduserinfo']['gender'],
      'guardian_salutation'         => $this->session->userdata['enduserinfo']['guardian_salutation'],
      'guardian_name'               => $this->session->userdata['enduserinfo']['guardian_name'],
      'email_id'                      => $this->session->userdata['enduserinfo']['email_id'],
      'mobile_no'                     => $this->session->userdata['enduserinfo']['mobile_no'],
      'address1'                   => $this->session->userdata['enduserinfo']['address1'],
      'address2'                   => $this->session->userdata['enduserinfo']['address2'],
      'address3'                   => $this->session->userdata['enduserinfo']['address3'],
      'district'                   => $this->session->userdata['enduserinfo']['district'],
      'city'                       => $this->session->userdata['enduserinfo']['city'],
      'state'                      => $this->session->userdata['enduserinfo']['state'],
      'pincode'                    => $this->session->userdata['enduserinfo']['pincode'],
      'address1_pr'                => $this->session->userdata['enduserinfo']['address1_pr'],
      'address2_pr'                => $this->session->userdata['enduserinfo']['address2_pr'],
      'address3_pr'                => $this->session->userdata['enduserinfo']['address3_pr'],
      'district_pr'                => $this->session->userdata['enduserinfo']['district_pr'],
      'city_pr'                    => $this->session->userdata['enduserinfo']['city_pr'],
      'state_pr'                   => $this->session->userdata['enduserinfo']['state_pr'],
      'pincode_pr'                 => $this->session->userdata['enduserinfo']['pincode_pr'],

      'qualification'              => $this->session->userdata['enduserinfo']['qualification'],
      'qualification_state'              => $this->session->userdata['enduserinfo']['qualification_state'],
      'qualification_certificate_file' => $this->session->userdata['enduserinfo']['qualification_certificate_file'],
      'experience'                 => $this->session->userdata['enduserinfo']['experience'],
      'exp_certificate'           => $this->session->userdata['enduserinfo']['exp_certificate'],
      'semester'                => $this->session->userdata['enduserinfo']['semester'],
      'collage'                => $this->session->userdata['enduserinfo']['collage'],
      'university'                => $this->session->userdata['enduserinfo']['university'],
      'institute_idproof'         => $this->session->userdata['enduserinfo']['institute_idproof'],
      'candidate_photo'               => $this->session->userdata['enduserinfo']['candidate_photo'],
      'candidate_sign'            => $this->session->userdata['enduserinfo']['candidate_sign'],
      'id_proof_file'              => $this->session->userdata['enduserinfo']['id_proof_file'],
      'declarationform'             => $this->session->userdata['enduserinfo']['declarationform'],
      'aadhar_no'                 => $this->session->userdata['enduserinfo']['aadhar_no'],
      'aadhar_file'              => $this->session->userdata['enduserinfo']['aadhar_file'],

      'registration_type'           => 'NM',
      'id_proof_number'            => $this->session->userdata['enduserinfo']['id_proof_number'],
      'created_on'                  => date('Y-m-d H:i:s'),
      'benchmark_disability'       => $benchmark_disability,
      'vis_imp_cert_img'           => $scanned_vis_imp_cert_file,
      'orth_han_cert_img'          => $scanned_orth_han_cert_file,
      'cer_palsy_cert_img'         => $scanned_cer_palsy_cert_file,
      'visually_impaired'          => $visually_impaired,
      'orthopedically_handicapped' => $orthopedically_handicapped,
      'cerebral_palsy'             => $cerebral_palsy,
      'ip_address'                  => $this->session->userdata['enduserinfo']['ip_address'],
      'kyc_eligible_date'         => date('Y-m-d') // temp
    );

    if ($last_id = $this->master_model->insertRecord('ncvet_candidates', $insert_info, true)) {
      $add_img_data['candidate_id']      = $last_id;



      $this->Ncvet_model->insert_common_log('Candidate Enrollment insert data', 'ncvet_candidates', 'Candidate Enrollment insert data', 'Candidate Enrollment insert data', 'candidate_action', 'The candidate Enrollment insert data', serialize($insert_info));


      $upd_files = array();


      $userarr = array(
        'regno' => $last_id,
        'password'               => $password,
        'email_id'                  => $this->session->userdata['enduserinfo']['email_id']
      );
      $this->session->set_userdata('ncvet_memberdata', $userarr);

      redirect(base_url() . "ncvet/candidate_registration/make_payment");
    } else {
      echo $this->db->last_query();
      $userarr = array(
        'regno' => '',
        'password'               => '',
        'email'                  => ''
      );
      $this->session->set_userdata('ncvet_memberdata', $userarr);
      //$this->make_payment();
      $this->session->set_flashdata('error', 'Error while during Enrollment.please try again!');
      //redirect(base_url().'ncvet/candidate_registration');
    }

    //}

  }
  public function make_payment()
  {

    if (!$this->session->userdata('enduserinfo')) {
      redirect(base_url() . '/ncvet/candidate_registration');
    }
    ////check temp file uploaded or not////
    $images_flag = 0;

    $cgst_rate = $sgst_rate = $igst_rate = $tax_type = '';
    $cgst_amt  = $sgst_amt  = $igst_amt  = 0;
    $cs_total  = $igst_total  = 0;
    $getstate  = $getcenter  = $getfees  = array();
    $flag      = 1;
    // TO do:
    // Validate reg no in DB
    //$_REQUEST['regno'] = "ODExODU5OTE1";
    //$regno = base64_decode($_REQUEST['regno']);

    $regno = $this->session->userdata['ncvet_memberdata']['regno'];
    //echo'<pre>';print_r($this->session->userdata['ncvet_memberdata']);exit;
    if (!empty($regno)) {
      $member_data = $this->master_model->getRecords('ncvet_candidates', array('candidate_id' => $regno, 'is_active' => '0'));
    }

    $valcookie = register_get_cookie();

    if ($valcookie) {
      $regid = $valcookie;
      //$regid= '57';
      $checkuser = $this->master_model->getRecords('ncvet_candidates', array('candidate_id' => $regno, 'regnumber !=' => '', 'is_active !=' => '0'));
      if (count($checkuser) > 0) {
        delete_cookie('regid');
        redirect('http://iibf.org.in');
      } else {
        $checkpayment = $this->master_model->getRecords('ncvet_payment_transaction', array('ref_id' => $regno, 'status' => '2'));
        if (count($checkpayment) > 0) {
          ///$datearr=explode(' ',$checkpayment[0]['date']);
          $endTime      = date("Y-m-d H:i:s", strtotime("+20 minutes", strtotime($checkpayment[0]['date'])));
          $current_time = date("Y-m-d H:i:s");
          if (strtotime($current_time) <= strtotime($endTime)) {
            $flag = 0;
          } else {
            delete_cookie('regid');
            redirect('http://iibf.org.in');
          }
        } else {
          $flag = 1;
          delete_cookie('regid');
          redirect('http://iibf.org.in');
        }
      }
    }

    if (isset($_POST['processPayment']) && $_POST['processPayment']) {

      $checkpayment = $this->master_model->getRecords('ncvet_payment_transaction', array('ref_id' => $regno, 'status' => '2'));
      //echo $this->db->last_query();exit;
      if (count($checkpayment) > 0) {
        delete_cookie('regid');
        redirect(base_url() . '/ncvet/candidate_registration');
      }
      // $pg_name = $this->input->post('pg_name');
      $pg_name = 'billdesk';
      if ($pg_name == 'sbi') {
        $gateway = 'sbiepay';
      } else {
        $gateway = 'billdesk';
      }

      //setting cookie for tracking multiple payment scenario
      register_set_cookie($regno); // temp off

      $state = $member_data[0]['state_pr'];
      $fee   = $member_data[0]['fee'];
      $amount = $this->config->item('ncvet_enroll_fee');

      // Create transaction
      $insert_data = array(
        'gateway'     => $gateway,
        'amount'      => $amount,
        'date'        => date('Y-m-d H:i:s'),
        'ref_id'      => $regno,
        'description' => "Candidate Enrollment",
        'pay_type'    => 1,
        'status'      => 2,
        'pg_flag'     => 'ncvetregn',
      );


      $pt_id = $this->master_model->insertRecord('ncvet_payment_transaction', $insert_data, true);

      //ncvet_logs
      $this->Ncvet_model->insert_common_log('Candidate payment transaction insert', 'ncvet_candidates', $this->db->last_query(), $regno, 'candidate_action_payment transaction', 'payment transaction insert ', serialize($insert_data));

      $MerchantOrderNo = generate_ncvet_reg_receipt_no($pt_id);


      $custom_field          = $regno . "^ncvetregn^" . $regno . "^" . $MerchantOrderNo;
      $custom_field_billdesk = $regno . "-ncvetPay-" . $regno . "-" . $MerchantOrderNo;

      // update receipt no. in payment transaction -
      $update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
      $this->master_model->updateRecord('ncvet_payment_transaction', $update_data, array('id' => $pt_id));

      //get value for invoice details [Tejasvi]
      if (!empty($state)) {
        //get state code,state name,state number.
        $getstate = $this->master_model->getRecords('state_master', array('state_code' => $state, 'state_delete' => '0'));
      }
      $cgst_rate = 0;
      $sgst_rate = 0;
      //set an amount as per rate
      $cgst_amt = 0;
      $sgst_amt = 0;
      //set an total amount
      $cs_total = $amount;
      $tax_type = '-';

      $invoice_insert_array = array(
        'pay_txn_id' => $pt_id,
        'receipt_no'                               => $MerchantOrderNo,
        'member_no'                                => $regno,
        'state_of_center'                          => $state,
        'app_type'                                 => 'R',
        'service_code'                             => $this->config->item('NCVET_service_code'),
        'qty'                                      => '1',
        'state_code'                               => $getstate[0]['state_no'],
        'state_name'                               => $getstate[0]['state_name'],
        'tax_type'                                 => $tax_type,
        'fee_amt'                                  => $amount,
        'cgst_rate'                                => $cgst_rate,
        'cgst_amt'                                 => $cgst_amt,
        'sgst_rate'                                => $sgst_rate,
        'sgst_amt'                                 => $sgst_amt,
        'igst_rate'                                => $igst_rate,
        'igst_amt'                                 => $igst_amt,
        'cs_total'                                 => $cs_total,
        'igst_total'                               => $igst_total,
        'gstin_no'                                 => '',
        'exempt'                                   => $getstate[0]['exempt'],
        'created_on'                               => date('Y-m-d H:i:s')
      );

      $inser_id = $this->master_model->insertRecord('ncvet_exam_invoice', $invoice_insert_array);


      //ncvet_logs
      $this->Ncvet_model->insert_common_log('Candidate exam invoice insert', 'ncvet_candidates', $this->db->last_query(), $regno, 'candidate_action_exam_invoice', 'exam invoice insert ', serialize($invoice_insert_array));

      /* This changes made by Pratibha borse Start code 09Feb2022 */
      if ($pg_name == 'billdesk') {

        $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $regno, $regno, '', 'ncvet/candidate_registration/handle_billdesk_response', '', '', '', $custom_field_billdesk);

        if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {
          $data['bdorderid']      = $billdesk_res['bdorderid'];
          $data['token']          = $billdesk_res['token'];
          $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
          $data['returnUrl']      = $billdesk_res['returnUrl'];
          $this->load->view('pg_billdesk/pg_billdesk_form', $data);
        } else {
          $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
          redirect(base_url() . 'ncvet/candidate_registration');
        }
      }
      /*  End code */
    } else {
      //$data["regno"] = $_REQUEST['regno'];
      $data['show_billdesk_option_flag'] = 1;
      $this->load->view('pg_sbi/make_payment_page', $data);
    }
  }


  public function handle_billdesk_response()
  {


    delete_cookie('regid');
    $_SESSION['ncvet_session_regid'] = $this->session->userdata['ncvet_memberdata']['regno'];
    $this->session->unset_userdata('enduserinfo');
    $this->session->unset_userdata('ncvet_memberdata');


    echo '<pre>';
    print_r($_REQUEST);
    $this->load->helper('update_image_name_helper');
    if (isset($_REQUEST['transaction_response'])) {
      $response_encode        = $_REQUEST['transaction_response'];
      $bd_response            = $this->billdesk_pg_model->verify_res($response_encode);
      $responsedata           = $bd_response['payload'];
      $attachpath             = $invoiceNumber             = '';
      $MerchantOrderNo        = $responsedata['orderid'];
      $transaction_no         = $responsedata['transactionid'];
      $transaction_error_type = $responsedata['transaction_error_type'];
      $transaction_error_desc = $responsedata['transaction_error_desc'];
      $bankid                 = $responsedata['bankid'];
      $txn_process_type       = $responsedata['txn_process_type'];
      $merchIdVal             = $responsedata['mercid'];
      $Bank_Code              = $responsedata['bankid'];
      $encData                = $_REQUEST['transaction_response'];
      $auth_status            = $responsedata['auth_status'];

      $get_user_regnum_info = $this->master_model->getRecords('ncvet_payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status,id');

      $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
      echo '<pre>';
      print_r($qry_api_response);
      if ($auth_status == '0300' && $qry_api_response['auth_status'] == '0300' && $get_user_regnum_info[0]['status'] == 2) {

        $update_data = array(
          'transaction_no'      => $transaction_no,
          'status'              => 1,
          'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
          'gateway'             => 'billdesk',
          'auth_code'           => '0300',
          'bankcode'            => $bankid,
          'paymode'             => $txn_process_type,
          'callback'            => 'B2B',
        );

        $update_query = $this->master_model->updateRecord('ncvet_payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));
        echo '<br>2' . $this->db->last_query();


        //ncvet_logs
        $this->Ncvet_model->insert_common_log('Candidate paymentupdate', 'ncvet_candidates', $this->db->last_query(), $get_user_regnum_info[0]['ref_id'], 'candidate_action_paymentupdate', 'paymentupdate ', serialize($update_data));
        if ($this->db->affected_rows()) {

          $reg_id = $get_user_regnum_info[0]['ref_id'];

          $applicationNo = generate_ncvet_memreg($reg_id);
          /* update member number in payment transaction */
          $update_data = array('member_regnumber' => $applicationNo);
          $this->master_model->updateRecord('ncvet_payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
          echo '<br>4' . $this->db->last_query();
          echo '<pre>get_user_regnum_info=';
          print_r($get_user_regnum_info);
          if (count($get_user_regnum_info) > 0) {
            $update_mem_data = array('is_active' => '1', 'regnumber' => $applicationNo);
            $this->master_model->updateRecord('ncvet_candidates', $update_mem_data, array('candidate_id' => $reg_id));
            echo $this->db->last_query();
            $user_info = $this->master_model->getRecords('ncvet_candidates', array('candidate_id' => $reg_id), 'password,email_id,candidate_photo,candidate_sign,id_proof_file,aadhar_file,mobile_no,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img,declarationform,institute_idproof,qualification_certificate_file,exp_certificate');

            //ncvet_logs
            $this->Ncvet_model->insert_common_log('Candidate OLD Image', 'ncvet_candidates', $this->db->last_query(), $reg_id, 'candidate_action_old_img', 'OLD Image ', serialize($user_info));

            $upd_files           = array();
            $photo_file          = 'p_' . $applicationNo . '.' . pathinfo($user_info[0]['candidate_photo'], PATHINFO_EXTENSION);
            $sign_file           = 's_' . $applicationNo . '.' . pathinfo($user_info[0]['candidate_sign'], PATHINFO_EXTENSION);
            $proof_file          = 'pr_' . $applicationNo . '.' . pathinfo($user_info[0]['id_proof_file'], PATHINFO_EXTENSION);
            $aadhar_f_file          = 'aadhar_' . $applicationNo . '.' . pathinfo($user_info[0]['aadhar_file'], PATHINFO_EXTENSION);
            $declaration_file    = 'declaration_' . $applicationNo . '.' . pathinfo($user_info[0]['declarationform'], PATHINFO_EXTENSION);
            $qualification_certificate_file  = 'qual_' . $applicationNo . '.' . pathinfo($user_info[0]['qualification_certificate_file'], PATHINFO_EXTENSION);
            $exp_certificate      = 'exp_' . $applicationNo . '.' . pathinfo($user_info[0]['exp_certificate'], PATHINFO_EXTENSION);
            $institute_idproof    = 'inst_id_' . $applicationNo . '.' . pathinfo($user_info[0]['institute_idproof'], PATHINFO_EXTENSION);
            $visually_file       = 'v_' . $applicationNo . '.' . pathinfo($user_info[0]['vis_imp_cert_img'], PATHINFO_EXTENSION);
            $orthopedically_file = 'o_' . $applicationNo . '.' . pathinfo($user_info[0]['orth_han_cert_img'], PATHINFO_EXTENSION);
            $cerebral_file       = 'c_' . $applicationNo . '.' . pathinfo($user_info[0]['cer_palsy_cert_img'], PATHINFO_EXTENSION);


            $chk_photo = update_image_name($this->candidate_photo_path . "/", $user_info[0]['candidate_photo'], $photo_file); //update_image_name_helper.php
            if ($chk_photo != "") {
              $upd_files['candidate_photo'] = $chk_photo;
            }

            $chk_sign = update_image_name($this->candidate_sign_path . "/", $user_info[0]['candidate_sign'], $sign_file); //update_image_name_helper.php
            if ($chk_sign != "") {
              $upd_files['candidate_sign'] = $chk_sign;
            }

            $chk_proof = update_image_name($this->id_proof_file_path . "/", $user_info[0]['id_proof_file'], $proof_file); //update_image_name_helper.php
            if ($chk_proof != "") {
              $upd_files['id_proof_file'] = $chk_proof;
            }

            $chk_aadhar = update_image_name($this->aadhar_file_path . "/", $user_info[0]['aadhar_file'], $aadhar_f_file); //update_image_name_helper.php
            if ($chk_aadhar != "") {
              $upd_files['aadhar_file'] = $chk_aadhar;
            }

            if ($user_info[0]['declarationform'] != '') {
              $chk_declaration = update_image_name($this->declarationform_path . "/", $user_info[0]['declarationform'], $declaration_file); //update_image_name_helper.php
              if ($chk_declaration != "") {
                $upd_files['declarationform'] = $chk_declaration;
              }
            }

            if ($user_info[0]['vis_imp_cert_img'] != '') {
              $chk_visually = update_image_name($this->disability_path . "/", $user_info[0]['vis_imp_cert_img'], $visually_file); //update_image_name_helper.php
              if ($chk_visually != "") {
                $upd_files['vis_imp_cert_img'] = $chk_visually;
              }
            }
            if ($user_info[0]['orth_han_cert_img'] != '') {
              $chk_orthopedically = update_image_name($this->disability_path . "/", $user_info[0]['orth_han_cert_img'], $orthopedically_file); //update_image_name_helper.php
              if ($chk_orthopedically != "") {
                $upd_files['orth_han_cert_img'] = $chk_orthopedically;
              }
            }

            if ($user_info[0]['cer_palsy_cert_img'] != '') {
              $chk_cerebral = update_image_name($this->disability_path . "/", $user_info[0]['cer_palsy_cert_img'], $cerebral_file); //update_image_name_helper.php
              if ($chk_cerebral != "") {
                $upd_files['cer_palsy_cert_img'] = $chk_cerebral;
              }
            }

            if ($user_info[0]['qualification_certificate_file'] != '') {
              $chk_qualification_certificate_file = update_image_name($this->qualification_certificate_file_path . "/", $user_info[0]['qualification_certificate_file'], $qualification_certificate_file); //update_image_name_helper.php
              if ($chk_qualification_certificate_file != "") {
                $upd_files['qualification_certificate_file'] = $chk_qualification_certificate_file;
              }
            }

            if ($user_info[0]['exp_certificate'] != '') {
              $chk_exp_certificate = update_image_name($this->exp_certificate_path . "/", $user_info[0]['exp_certificate'], $exp_certificate); //update_image_name_helper.php
              if ($chk_exp_certificate != "") {
                $upd_files['exp_certificate'] = $chk_exp_certificate;
              }
            }

            if ($user_info[0]['institute_idproof'] != '') {
              $chk_institute_idproof = update_image_name($this->institute_idproof_path . "/", $user_info[0]['institute_idproof'], $institute_idproof); //update_image_name_helper.php
              if ($chk_institute_idproof != "") {
                $upd_files['institute_idproof'] = $chk_institute_idproof;
              }
            }

            if (count($upd_files) > 0) {
              $this->master_model->updateRecord('ncvet_candidates', $upd_files, array('candidate_id' => $reg_id));

              //ncvet_logs
              $this->Ncvet_model->insert_common_log('Candidate PIC update ', 'ncvet_candidates', $this->db->last_query(), $reg_id, 'candidate_action_update_img', 'PIC update  ', serialize($this->db->last_query()));
            } else {
              $upd_files['candidate_photo']          = $photo_file;
              $upd_files['candidate_sign']          = $sign_file;
              $upd_files['id_proof_file']          = $proof_file;
              $upd_files['aadhar_file']          = $aadhar_f_file;
              $upd_files['declarationform']           = $declaration_file;
              $upd_files['vis_imp_cert_img']      = $visually_file;
              $upd_files['orth_han_cert_img']     = $orthopedically_file;
              $upd_files['cer_palsy_cert_img']    = $cerebral_file;
              $upd_files['qualification_certificate_file']    = $qualification_certificate_file;
              $upd_files['exp_certificate']    = $exp_certificate;
              $upd_files['institute_idproof']    = $institute_idproof;

              $this->master_model->updateRecord('ncvet_candidates', $upd_files, array('candidate_id' => $reg_id));

              //ncvet_logs
              $this->Ncvet_model->insert_common_log('Candidate MANUAL PICS Update ', 'ncvet_candidates', $this->db->last_query(), $reg_id, 'candidate_action_update_img', 'MANUAL PICS Update  ', serialize($upd_files));
            }
          }

          //email to user
          $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'ncvet_candidate_enrollment'));

          //ncvet_logs
          $this->Ncvet_model->insert_common_log('Candidate emailer count ', 'ncvet_candidates', $this->db->last_query(), $reg_id, 'candidate_action_emailer', 'emailer count ', serialize($emailerstr));
          if (count($emailerstr) > 0) {
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('pass_key');
            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $decpass = $aes->decrypt(trim($user_info[0]['password']));
            //$decpass = $aes->decrypt($user_info[0]['password']);
            $newstring = str_replace("#application_num#", "" . $applicationNo . "", $emailerstr[0]['emailer_text']);
            $final_str = str_replace("#password#", "" . $decpass . "", $newstring);
            $final_str = str_replace("#profilelink#", "" . base_url() . '/ncvet/candidate/login_candidate' . "", $final_str);
            $info_arr  = array(
              'to' => $user_info[0]['email_id'],
              //'to'=>'kumartupe@gmail.com',
              'from'                  => $emailerstr[0]['from'],
              'subject'               => $emailerstr[0]['subject'] . ' ' . $applicationNo,
              'message'               => $final_str,
            );

            //ncvet_logs

            $this->Ncvet_model->insert_common_log('Candidate info_arr ', 'ncvet_candidates', $this->db->last_query(), $reg_id, 'candidate_action_member_info', 'member info ', serialize($info_arr));
            //get invoice
            $getinvoice_number = $this->master_model->getRecords('ncvet_exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $get_user_regnum_info[0]['id']));
            //echo $this->db->last_query();exit;


            //ncvet_logs
            $this->Ncvet_model->insert_common_log('Candidate before Invoice log update ', 'ncvet_candidates', $this->db->last_query(), $reg_id, 'candidate_action_before_log_update', 'before Invoice log update ', '');

            if (count($getinvoice_number) > 0) {

              $invoiceNumber = generate_ncvet_enroll_invoice_number($getinvoice_number[0]['invoice_id']);
              if ($invoiceNumber) {
                $invoiceNumber = $this->config->item('ncvet_mem_invoice_no_prefix') . $invoiceNumber;
              }

              $update_data = array('invoice_no' => $invoiceNumber, 'member_no' => $applicationNo, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
              $this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
              $this->master_model->updateRecord('ncvet_exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
              $attachpath = ncvet_genarate_reg_invoice($getinvoice_number[0]['invoice_id']);

              //ncvet_logs
              $this->Ncvet_model->insert_common_log('Candidate  after Invoice log update ', 'ncvet_candidates', $this->db->last_query(), $reg_id, 'candidate_action_after_log_update', ' after Invoice log update ', $attachpath);
            }

            if ($attachpath != '') {
              $sms_newstring = str_replace("#application_num#", "" . $applicationNo . "", $emailerstr[0]['sms_text']);
              $sms_final_str = str_replace("#password#", "" . $decpass . "", $sms_newstring);

              $this->master_model->send_sms_common_all($user_info[0]['mobile_no'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']);
              //if($this->Emailsending->mailsend($info_arr))

              if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {

                $this->Ncvet_model->insert_common_log('Candidate  after Invoice Sent on mail ', 'ncvet_candidates', '', $reg_id, 'candidate_action_after_mail_sent', ' after Invoice sent ', $attachpath);

                redirect(base_url() . 'ncvet/candidate_registration/acknowledge/');
              } else {
                redirect(base_url() . 'ncvet/candidate_registration/acknowledge/');
              }
            } else {
              redirect(base_url() . 'ncvet/candidate_registration/acknowledge/');
            }
          }
        }

        //Manage Log
        $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
        $this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);
        redirect(base_url() . 'ncvet/candidate_registration/acknowledge/');
        exit();
      } elseif ($auth_status == '0002'  && $get_user_regnum_info[0]['status'] == 2) {

        $update_data = array(
          'transaction_no'      => $transaction_no,
          'status'              => 2,
          'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
          'auth_code'           => '0300',
          'bankcode'            => $bankid,
          'paymode'             => $txn_process_type,
          'callback'            => 'B2B',
        );

        $this->master_model->updateRecord('ncvet_payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
        $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
        $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

        $this->session->set_flashdata('error', 'Transaction under process...!');
        
        redirect(base_url('ncvet/candidate_registration'));
      } else /* if ($transaction_error_type == 'payment_authorization_error') */ {
        if ($get_user_regnum_info[0]['status'] != 0 && $get_user_regnum_info[0]['status'] == 2) {
          $update_data = array(
            'transaction_no'      => $transaction_no,
            'status'              => 0,
            'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
            'auth_code'           => '0399',
            'bankcode'            => $bankid,
            'paymode'             => $txn_process_type,
            'callback'            => 'B2B',
          );

          $this->master_model->updateRecord('ncvet_payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
          $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
          $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

          $this->session->set_flashdata('error', 'Transaction failed...!');
          redirect(base_url('ncvet/candidate_registration'));
        }
      }
    } else {
      die("Please try again...");
    }
  }
  //Thank you message to end user
  public function acknowledge()
  {

    $data = array();
    if (isset($_SESSION['ncvet_session_regid']) && $_SESSION['ncvet_session_regid'] != '') {
      $user_info = $this->master_model->getRecords('ncvet_candidates', array('candidate_id' => $this->session->userdata('ncvet_session_regid')), 'regnumber, password, created_on');

      $time_after_5min = date('Y-m-d H:i:s', strtotime("+5 min", strtotime($user_info[0]['created_on'])));
      if (date('Y-m-d H:i:s') > $time_after_5min) {
        $_SESSION['ncvet_session_regid'] = '';
        redirect(base_url() . '/ncvet/candidate_registration/');
      }

      include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
      $key = $this->config->item('pass_key');
      $aes = new CryptAES();
      $aes->set_key(base64_decode($key));
      $aes->require_pkcs5();
      $decpass = $aes->decrypt(trim($user_info[0]['password']));

      $data = array('application_number', 'application_number' => $user_info[0]['regnumber'], 'password' => $decpass, 'user_info' => $user_info);
      $this->load->view('ncvet/acknowledgment', $data);
    } else {
      redirect(base_url());
    }
  }


  public function send_otp()
  {
    $email_id = strtolower($_POST['email_id']);
    $type  = $_POST['type'];
    if ($type == 'send_otp' || $type == 'resend_otp') {
      $arr_email_status = $this->validation_check_email_exist($email_id, 2);
      if ($arr_email_status['status']) {
        $sendOTPStatus = $this->send_otp_sms_email($email_id, 'email_id');
        if ($sendOTPStatus) {
          $status = true;
          $msg    = 'OTP successfully sent to email address. The OTP is valid for 10 minutes.';
        } else {
          $status = false;
          $msg    = 'Error occured, While sending an OTP on email id.';
        }
      } else {
        $status = $arr_email_status['status'];;
        $msg    = $arr_email_status['msg'];
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

    $arr_email_status['status'] = $status;
    $arr_email_status['msg']    = $msg;
    echo json_encode($arr_email_status);
  }

  public function send_otp_mobile()
  {
    $mobile_no = $_POST['mobile_no'];
    $type   = $_POST['type'];
    if ($type == 'send_otp' || $type == 'resend_otp') {
      $arr_mobile_status = $this->validation_check_mobile_exist($mobile_no, 2);

      if ($arr_mobile_status['status']) {
        $sendOTPStatus = $this->send_otp_sms_email($mobile_no, 'mobile_no');
        if ($sendOTPStatus) {
          $status = true;
          $msg    = 'OTP successfully sent to mobile number. The OTP is valid for 10 minutes.';
        } else {
          $status = false;
          $msg    = 'Error occured, While sending an OTP on mobile no.';
        }
      } else {
        $status = $arr_mobile_status['status'];;
        $msg    = $arr_mobile_status['msg'];
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

    $arr_mobile_status['status'] = $status;
    $arr_mobile_status['msg']    = $msg;
    echo json_encode($arr_mobile_status);
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
      // echo '<pre>';print_r($email_sms_response);exit;
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

  function  qualification_exp_validation()
  {
    $flag = 1;
    if (isset($_POST) && $_POST['qualification'] == "1"  && $_POST['experience'] == "N") {
      $flag = 1;
    }
    if ($flag == 0) {
      $this->form_validation->set_message('qualification_exp_validation', 'You are not eligible for enrollment');
      return false;
    } else return true;
  }

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

  /******** START : VALIDATION FUNCTION TO CHECK CANDIDATE ID PROOF NUMBER EXIST OR NOT ********/
  public function validation_check_id_proof_number_exist($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    $enc_batch_id = '';
    if (isset($_POST) && $_POST['id_proof_number'] != "") {

      if ($type == '1') {
        $id_proof_number = strtolower($this->security->xss_clean($this->input->post('id_proof_number')));
      } else {
        $id_proof_number = strtolower($str);
      }

      //check if candidate mobile exist or not
      $result_data = $this->master_model->getRecords('ncvet_candidates am', array('am.is_active' => '1', 'am.is_deleted' => '0', 'am.id_proof_number' => $id_proof_number), 'am.candidate_id, am.id_proof_number, am.email_id');

      if (count($result_data) == 0 && preg_match('/^([0-9]{12})$/', $id_proof_number)) {
        $return_val_ajax = 'true';
      }
    }

    if ($type == '1') {
      echo $return_val_ajax;
    } else {
      if ($return_val_ajax == 'true') {
        return TRUE;
      } else if ($_POST['id_proof_number'] != "") {
        $this->form_validation->set_message('validation_check_id_proof_number_exist', 'The APAAR ID/ABC ID is already exist or not in Proper Format');
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK CANDIDATE ID PROOF NUMBER EXIST OR NOT ********/

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
  public function refresh_captcha()
  /******** START : REFRESH CAPTCHA ********/
  {
    $this->load->helper('captcha');
    echo generate_captcha('NCVET_CANDIDATE_ENROLLMENT_CAPTCHA', 6); //ncvet/ncvet_helper.php
  }
  /******** END : REFRESH CAPTCHA ********/

  /******** START : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/
  public function validation_check_captcha($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    if (isset($_POST) && $_POST['ncvet_candidate_enrollment_captcha'] != "") {
      if ($type == '1') {
        $captcha = $this->security->xss_clean($this->input->post('ncvet_candidate_enrollment_captcha'));
      } else if ($type == '0') {
        $captcha = $str;
      }

      $session_captcha = $this->session->userdata('NCVET_CANDIDATE_ENROLLMENT_CAPTCHA');

      if ($captcha == $session_captcha) {
        $return_val_ajax = 'true';
      }
    }

    if ($type == '1') {
      echo $return_val_ajax;
    } else if ($type == '0') {
      if ($return_val_ajax == 'true') {
        return TRUE;
      } else if ($_POST['ncvet_candidate_enrollment_captcha'] != "") {
        $this->form_validation->set_message('validation_check_captcha', 'Please enter the valid code');
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/

  function get_city_ajax()
  {
    if (isset($_POST) && count($_POST) > 0) {
      $city_field_id = $_POST['city_field_id'];
      $onchange_fun = "validate_file('" . $city_field_id . "')";
      $html = '	<select class="form-control chosen-select ignore_required" name="' . $city_field_id . '" id="' . $city_field_id . '" required onchange="' . $onchange_fun . '">';
      $state_id = $this->security->xss_clean($this->input->post('state_id'));

      $city_data = $this->master_model->getRecords('city_master', array('state_code' => $state_id, 'city_delete' => '0'), 'id, city_name', array('city_name' => 'ASC'));
      if (count($city_data) > 0) {
        $html .= '	<option value="">Select City</option>';
        foreach ($city_data as $city) {
          if (isset($_POST['selectedCity']) && $_POST['selectedCity'] == $city['id'])
            $html .= '	<option selected value="' . $city['id'] . '">' . $city['city_name'] . '</option>';
          else
            $html .= '	<option value="' . $city['id'] . '">' . $city['city_name'] . '</option>';
        }
      } else {
        $html .= '	<option value="">Select City</option>';
      }
      $html .= '</select>';
      $html .= "<script>$('.chosen-select').chosen({width: '100%'});function validate_file(input_id) { $('#'+input_id).valid(); }</script>";

      $result['flag'] = "success";
      $result['response'] = $html;
    } else {
      $result['flag'] = "error";
    }
    echo json_encode($result);
  }

  /******** START : VALIDATION FUNCTION TO CHECK PINCODE IS VALID OR NOT ********/
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
  public function validation_check_valid_pincode_pr($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    if (isset($_POST) && $_POST['pincode_pr'] != "") {
      if ($type == '1') {

        $pincode = $this->security->xss_clean($this->input->post('pincode_pr'));
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
      } else if ($_POST['pincode_pr'] != "") {
        $pin_length = strlen($_POST['pincode_pr']);

        $err_msg = 'Please enter valid pincode as per selected city';
        if ($pin_length != 6) {
          $err_msg = 'Please enter only 6 numbers in pincode';
        }

        $this->form_validation->set_message('validation_check_valid_pincode_pr', $err_msg);
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK PINCODE IS VALID OR NOT ********/

  /******** START : VALIDATION FUNCTION TO CHECK CANDIDATE Aadhar EXIST OR NOT ********/
  public function validation_check_aadhar_no_exist($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    if (isset($_POST) && $_POST['aadhar_no'] != "") {
      if ($type == '1') {
        $aadhar_no = $this->security->xss_clean($this->input->post('aadhar_no'));
      } else {
        $aadhar_no = $str;
      }

      //check if candidate mobile exist or not
      $result_data = $this->master_model->getRecords('ncvet_candidates am', array('am.is_active' => '1', 'am.is_deleted' => '0', 'am.aadhar_no' => $aadhar_no), 'am.candidate_id, am.aadhar_no, am.email_id');

      if (count($result_data) == 0) {
        $return_val_ajax = 'true';
      }
    }

    if ($type == '1') {
      echo $return_val_ajax;
    } else {
      if ($return_val_ajax == 'true') {
        return TRUE;
      } else if ($_POST['aadhar_no'] != "") {
        $this->form_validation->set_message('validation_check_aadhar_no_exist', 'The Aadhar number is already exist');
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK CANDIDATE Aadhar EXIST OR NOT ********/


  /******** START : VALIDATION FUNCTION TO CHECK CANDIDATE MOBILE EXIST OR NOT ********/
  public function validation_check_mobile_exist($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';

    $arr_response = array();
    $arr_response['status'] = true;
    $arr_response['msg']    = '';

    if (isset($_POST) && $_POST['mobile_no'] != "") {
      if ($type == '1') {
        $mobile_no = $this->security->xss_clean($this->input->post('mobile_no'));
      } else {
        $mobile_no = $str;
      }

      //check if candidate mobile exist or not
      $result_data = $this->master_model->getRecords('ncvet_candidates am', array('am.is_active' => '1', 'am.is_deleted' => '0', 'am.mobile_no' => $mobile_no), 'am.candidate_id, am.mobile_no, am.email_id');

      if (count($result_data) == 0) {
        $return_val_ajax = 'true';
      }
    }

    if ($type == '1') {
      echo $return_val_ajax;
    } else if ($type = '2') {
      if ($return_val_ajax != 'true') {
        $arr_response['status'] = false;
        $arr_response['msg']    = 'The mobile number is already exist';
      }
      return $arr_response;
    } else {
      if ($return_val_ajax == 'true') {
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
    $return_val_ajax = 'false';
    $arr_response = array();
    $arr_response['status'] = true;
    $arr_response['msg']    = '';

    if (isset($_POST) && $_POST['email_id'] != "") {
      if ($type == '1') {
        $email_id = strtolower($this->security->xss_clean($this->input->post('email_id')));
      } else {
        $email_id = strtolower($str);
      }

      //check if candidate mobile exist or not
      $result_data = $this->master_model->getRecords('ncvet_candidates am', array('am.is_deleted' => '0', 'am.is_active' => '1', 'am.email_id' => $email_id), 'am.candidate_id, am.mobile_no, am.email_id');
      
      if (count($result_data) == 0) {
        $return_val_ajax = 'true';
      }
    }

    if ($type == '1') {
      echo $return_val_ajax;
    } 
    else if ($type = '2') {
      if ($return_val_ajax != 'true') {
        $arr_response['status'] = false;
        $arr_response['msg']    = 'The email id is already exist';
      }
      return $arr_response;
    } else {
      if ($return_val_ajax == 'true') {
        return TRUE;
      } 
      else if ($_POST['email_id'] != "") {
        $this->form_validation->set_message('validation_check_email_exist', 'The email id is already exist');
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK CANDIDATE MOBILE EXIST OR NOT ********/


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

  /******** START : VALIDATION FUNCTION TO CHECK THE PASSWORD & CONFIRM PASSWORD IS SAME OR NOT ********/
  function validation_check_password($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    $msg = 'Please enter same password and confirm password';
    if (isset($_POST) && $_POST['confirm_password'] != "") {
      $candidate_password = $this->security->xss_clean($this->input->post('candidate_password'));
      if ($type == '1') {
        $confirm_password = $this->security->xss_clean($this->input->post('confirm_password'));
      } else if ($type == '0') {
        $confirm_password = $str;
      }

      if ($candidate_password == $confirm_password) {
        $return_val_ajax = 'true';
      }
    }

    if ($type == '1') {
      echo $return_val_ajax;
    } else if ($type == '0') {
      if ($return_val_ajax == 'true') {
        return TRUE;
      } else if ($_POST['confirm_password'] != "") {
        $this->form_validation->set_message('validation_check_password', $msg);
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK THE PASSWORD & CONFIRM PASSWORD IS SAME OR NOT ********/
}
