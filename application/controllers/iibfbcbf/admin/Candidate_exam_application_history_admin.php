<?php

/********************************************************************************************************************
 ** Description: Controller for BCBF Candidate exam application history - Admin
 ** Created BY: Sagar Matale On 16-10-2024
 ********************************************************************************************************************/
defined('BASEPATH') or exit('No direct script access allowed');

class Candidate_exam_application_history_admin extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    $this->load->model('master_model');
    $this->load->model('iibfbcbf/Iibf_bcbf_model');
    $this->load->helper('iibfbcbf/iibf_bcbf_helper');

    $this->login_admin_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
    $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');

    if ($this->login_user_type != 'admin')
    {
      $this->login_user_type = 'invalid';
    }
    $this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout

    $this->id_proof_file_path = 'uploads/iibfbcbf/id_proof';
    $this->qualification_certificate_file_path = 'uploads/iibfbcbf/qualification_certificate';
    $this->candidate_photo_path = 'uploads/iibfbcbf/photo';
    $this->candidate_sign_path = 'uploads/iibfbcbf/sign';
  }

  public function index($enc_training_id_or_regnumber = '')
  {
    $data['act_id'] = "Reports";
    $data['sub_act_id'] = "Candidate exam application history";
    $data['page_title'] = 'IIBF - BCBF Candidate exam application history';
    $data['enc_training_id_or_regnumber'] = $enc_training_id_or_regnumber;

    $data['id_proof_file_path'] = $this->id_proof_file_path;
    $data['qualification_certificate_file_path'] = $this->qualification_certificate_file_path;
    $data['candidate_photo_path'] =  $this->candidate_photo_path;
    $data['candidate_sign_path'] = $this->candidate_sign_path;

    if (isset($_POST) && count($_POST) > 0)
    {
      $this->form_validation->set_rules('training_id_or_regnumber', 'Training ID or Registration Number', 'trim|required|callback_validation_check_training_id_or_regnumber[]|xss_clean', array('required' => "Please enter the %s"));

      if ($this->form_validation->run())
      {
        redirect(site_url('iibfbcbf/admin/candidate_exam_application_history_admin/index/' . url_encode($this->input->post('training_id_or_regnumber'))));
      }
    }

    if($enc_training_id_or_regnumber != '')
    {
      $data['training_id_or_regnumber'] = $training_id_or_regnumber = url_decode($enc_training_id_or_regnumber);

      $result_data = $this->Iibf_bcbf_model->get_candidate_exam_application_history_data_common($training_id_or_regnumber);
      if(isset($result_data['candidate_data'])) { $data['candidate_data'] = $result_data['candidate_data']; }
      if(isset($result_data['exam_application_data'])) { $data['exam_application_data'] = $result_data['exam_application_data']; }
    }
    
    $this->load->view('iibfbcbf/admin/candidate_exam_application_history_admin', $data);
  }

  /******** START : VALIDATION FUNCTION TO CHECK CANDIDATE TRAINING ID OR REGNUMBER IS VALID OR NOT ********/
  public function validation_check_training_id_or_regnumber($str = '', $type = 0) //$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    if (isset($_POST) && $_POST['training_id_or_regnumber'] != "")
    {
      if ($type == '1')
      {
        $training_id_or_regnumber = strtolower($this->security->xss_clean($this->input->post('training_id_or_regnumber')));        
      }
      else
      {
        $training_id_or_regnumber = strtolower($str);
      }

      $this->db->where(" (training_id = '" . $training_id_or_regnumber . "' or regnumber = '" . $training_id_or_regnumber . "') ");
      $candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates', array('is_deleted' => '0'), "candidate_id");
      if (count($candidate_data) > 0)
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
      else if ($_POST['training_id_or_regnumber'] != "")
      {
        $this->form_validation->set_message('validation_check_training_id_or_regnumber', 'Please Enter correct Training ID or Registration Number');
        return false;
      }
    }
  }
  /******** END : VALIDATION FUNCTION TO CHECK CANDIDATE TRAINING ID OR REGNUMBER IS VALID OR NOT ********/
}
