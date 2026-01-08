<?php

/********************************************************************************************************************
 ** Description: Controller for BCBF Candidate exam application history
 ** Created BY: Sagar Matale On 06-09-2024
 ********************************************************************************************************************/
defined('BASEPATH') or exit('No direct script access allowed');

class Candidate_exam_application_history_agency extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    $this->load->model('master_model');
    $this->load->model('iibfbcbf/Iibf_bcbf_model');
    $this->load->helper('iibfbcbf/iibf_bcbf_helper');

    $this->login_agency_centre_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
    $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');

    if ($this->login_user_type != 'agency' && $this->login_user_type != 'centre')
    {
      $this->login_user_type = 'invalid';
    }
    $this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout

    $this->centre_id = 0;
    if ($this->login_user_type == 'agency')
    {
      $this->agency_id = $this->login_agency_centre_id;
    }
    else if ($this->login_user_type == 'centre')
    {
      $this->centre_id = $this->login_agency_centre_id;

      $agency_id_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('centre_id' => $this->login_agency_centre_id), "agency_id");
      if (count($agency_id_data) > 0)
      {
        $this->agency_id = $agency_id_data[0]['agency_id'];
      }
    }
  }

  public function index($enc_training_id_or_regnumber = '')
  {
    $data['act_id'] = "Candidate exam application history";
    $data['sub_act_id'] = "Candidate exam application history";
    $data['page_title'] = 'IIBF - BCBF Candidate exam application history';
    $data['enc_training_id_or_regnumber'] = $enc_training_id_or_regnumber;

    if (isset($_POST) && count($_POST) > 0)
    {
      $this->form_validation->set_rules('training_id_or_regnumber', 'Training ID or Registration Number', 'trim|required|callback_validation_check_training_id_or_regnumber[]|xss_clean', array('required' => "Please enter the %s"));

      if ($this->form_validation->run())
      {
        redirect(site_url('iibfbcbf/agency/candidate_exam_application_history_agency/index/' . url_encode($this->input->post('training_id_or_regnumber'))));
      }
    }

    if($enc_training_id_or_regnumber != '')
    {
      $data['training_id_or_regnumber'] = $training_id_or_regnumber = url_decode($enc_training_id_or_regnumber);

      $this->db->limit(1);
      $this->db->where(" (cand.training_id = '" . $training_id_or_regnumber . "' or cand.regnumber = '" . $training_id_or_regnumber . "') ");
      $data['candidate_data'] = $candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates cand', array('cand.is_deleted' => '0'), "cand.candidate_id, cand.agency_id, cand.regnumber, cand.training_id, cand.salutation, cand.first_name, cand.middle_name, cand.last_name, cand.dob, IF(cand.gender=1,'Male','Female') AS DispGender, cand.mobile_no, cand.alt_mobile_no, cand.email_id, cand.alt_email_id, IF(cand.hold_release_status=1,'Auto Hold', IF(cand.hold_release_status=2,'Manual Hold','Release')) AS Disphold_release_status, cand.re_attempt, cand.created_on", array('cand.candidate_id'=>'DESC'));
      
      if(count($candidate_data) > 0)
      {
        if($candidate_data[0]['regnumber'] != '') { $this->db->where('me.regnumber',$candidate_data[0]['regnumber']); }
        else { $this->db->where('me.candidate_id',$candidate_data[0]['candidate_id']); }

        $data['exam_application_data'] = $exam_application_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.is_deleted' => '0'), "", array('me.member_exam_id'=>'DESC'));
        //_pq(1);
      }
    }
    
    $this->load->view('iibfbcbf/agency/candidate_exam_application_history_agency', $data);
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
