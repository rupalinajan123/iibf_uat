<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Candidate extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Master_model');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set("memory_limit", "-1");
    $this->load->model('master_model');
  }


  public function sent_password()
  {
    $this->db->select('*');
    $this->db->where('id >',77);
    $this->db->where('pay_status','1');
    $agency_data = $this->master_model->getRecords("dra_accerdited_master");
    $str = '';

    echo "<pre>"; print_r($agency_data); exit;

    foreach ($agency_data as $key => $value) 
    {
      $decPassword = $this->generatePassword(12);  
      
      $arr_upd = array(
        'password'     => md5($decPassword)
      );

      $updated_id = $this->master_model->updateRecord('dra_accerdited_master',$arr_upd,array('id' =>$value['id']));
      
      $credentials = ' Username : '.$value['institute_code'].'<br>'.'Password : '.$decPassword; 

      if ($updated_id) {
        $str .= '<tr>
                    <td>'.$value['institute_name'].'</td>
                    <td>'.$credentials.'</td>
                </tr>';
      }
    }

    $instituteDetails =    '<table border="1">
                              <thead>
                                  <tr>
                                      <th>Institute Name</th>
                                      <th>Credentials</th>
                                  </tr>
                              </thead>
                              <tbody>      
                              '.$str.'    
                              </tbody>
                            </table>';

    echo $instituteDetails; exit;                         

  }

  public function generatePassword($length) 
  {
    // Define the characters you want to include in the password
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
    
    // Initialize the password variable
    $password = '';
    
    // Get the maximum index for the character set
    $maxIndex = strlen($characters) - 1;
    
    // Loop to generate each character randomly
    for ($i = 0; $i < $length; $i++) {
        $index = rand(0, $maxIndex);
        $password .= $characters[$index];
    }
    
    return $password;
  }

  public function history()
  {
    if($this->input->is_ajax_request()) { 
        $arr_response = [];
        
        if(count($_POST) > 0) 
        {
          $batch_id        = $_POST['batch_id'];
          $candidate_type  = $_POST['candidate_type'];
          $candidate_value = $_POST['candidate_value'];

          if ($candidate_type == 'email') {
              $arr_response = $this->check_email($batch_id, $candidate_value);
          } elseif ($candidate_type == 'mobile') {
              $arr_response = $this->check_mobile_no($batch_id, $candidate_value);
          } elseif ($candidate_type == 'id') {
              $arr_response = $this->check_idproof($batch_id, $candidate_value);
          } elseif ($candidate_type == 'aadhar') {
              $arr_response = $this->check_aadhar_no($batch_id, $candidate_value);
          }
        }

        echo json_encode($arr_response);
        exit;
    }

    // For the form view
    $data = [];
    $this->db->select('id,batch_code,batch_status');
    $this->db->where('batch_status','Approved');
    $this->db->like('batch_code', 'DB', 'after');
    $this->db->where('is_deleted',0);
    $batch_data = $this->master_model->getRecords("agency_batch");
    $data['batch'] = $batch_data;

    $this->load->view('iibfdra/Version_2/dra_candidates/candidate_details', $data);
}


  public function check_email($batch_id,$email)
  {
    $arr_response = [];
      
    $arr_response['status']  = 'error';
    $arr_response['massege'] = '';

    $this->db->select('dmm.regid, dmm.batch_id,abb.batch_code, dmm.hold_release, dmm.mobile_no, abb.batch_status, abb.batch_from_date, abb.batch_to_date, dmm.regnumber, dmm.re_attempt');
    $this->db->join('agency_batch abb', 'abb.id = dmm.batch_id');
    $this->db->where('dmm.email_id', $email);
    $this->db->where('abb.batch_status !=', 'Cancelled');

    if (isset($_POST['regId']) && $_POST['regId'] != "") {
      $this->db->where('dmm.regid !=', $_POST['regId']);
    }
    
    $email_data  = $this->master_model->getRecords('dra_members dmm');
    $isStatus   = false;
    
    $this->db->where('id',$batch_id);
    $batch_data = $this->master_model->getRecords('agency_batch');

    if (count($email_data) > 0) 
    {  
      $new_batch_start_date = new DateTime($batch_data[0]['batch_from_date']);
      
      foreach ($email_data as $key => $value) 
      {
        $holdBatchDaysStatus = false;
        $sameBatchIdStatus   = false;
        $daysvalidStatus     = false;
        $examattemptStatus   = false;

        $old_batch_end_date = new DateTime($value['batch_to_date']);
        $next5Days = clone $old_batch_end_date;
        $next5Days->modify('+5 days');

        // Format the result if needed
        $next5DaysFormatted = $next5Days->format('Y-m-d');

        // Format the result if needed
        $next5DaysFormatted = $next5Days->format('Y-m-d');
        // echo $old_batch_end_date;
        if ($value['hold_release'] == 'Auto Hold' || $value['hold_release'] == 'Manual Hold') 
        {
          if ($next5Days < $new_batch_start_date) 
          {
            $a = 5;
            $holdBatchDaysStatus = true;      
          }
        }
        
        if ($value['batch_id'] != $batch_id) {
          $sameBatchIdStatus = true;
          $b = 6;       
        }

        $endDateOfFirstBatch    = strtotime($value['batch_to_date']);
        $startDateOfSecondBatch = strtotime($batch_data[0]['batch_from_date']);
        $daysDifference         = ($startDateOfSecondBatch - $endDateOfFirstBatch) / (60 * 60 * 24);
        $attemptCount           = $value['re_attempt'];
        $regnumber              = $value['regnumber'];

        $this->db->where('member_no',$regnumber);
        $this->db->where('member_no !=','');
        $this->db->where('exam_status','F');
        $this->db->order_by("id","desc");
        $batch_exam_data = $this->master_model->getRecords('dra_eligible_master');
        // print_r($batch_exam_data); 
        if ($daysDifference < 270) 
        {
          if ($attemptCount >= 3 || count($batch_exam_data) >= 1) 
          {
            $c = 7;
            $examattemptStatus = true;    
          }
        }
        else
        {
          $d = 8;
          $daysvalidStatus = true;
        }
        
        $batchCode       = $value['batch_code'];
        $batchDuration   = date("d-M-Y", strtotime($value['batch_from_date'])).' to '.date("d-M-Y", strtotime($value['batch_to_date']));
        $batchStatus     = $value['batch_status'];
        $candidateStatus = $value['hold_release'];
        $reAttempt       = $value['re_attempt'];

        if (($daysvalidStatus || $examattemptStatus) || ($sameBatchIdStatus && $holdBatchDaysStatus)) 
        { 
          $isStatus   = true; 
        } 
        else
        {
          $isStatus = false; break;
        }
      }
      
      if (!$isStatus) 
      {
        $arr_response['status']  = 'success';
        $arr_response['massege'] = $arr_response['massege'] = 'This candidate has already been enrolled in batch <b>'.$batchCode.'</b>, with a duration from <b>'.$batchDuration.'</b>. The batch status is <b>'.$batchStatus.'</b>, and candidate status is <b>'.$candidateStatus.'</b>, and their attempt is <b>'.$reAttempt.'</b>';
      }
      else
      {
        $arr_response['status']  = 'success';
        $arr_response['massege'] = 'This candidate is new. to allow the candidate to enroll in the <b>'.$batch_data[0]['batch_code'].'</b> batch.';
      }
    }
    else
    {
      $arr_response['status']  = 'success';
      $arr_response['massege'] = 'This candidate is new. to allow the candidate to enroll in the <b>'.$batch_data[0]['batch_code'].'</b> batch.';
    }

    return $arr_response;
  }

    public function check_mobile_no($batch_id,$mobile_no) 
    {
      $arr_response = [];
      
      $arr_response['status']  = 'error';
      $arr_response['massege'] = '';

      $this->db->select('dmm.regid,dmm.batch_id,abb.batch_code, dmm.hold_release, dmm.mobile_no, abb.batch_status, abb.batch_from_date, abb.batch_to_date, dmm.regnumber, dmm.re_attempt');
      $this->db->join('agency_batch abb', 'abb.id = dmm.batch_id');
      $this->db->where('dmm.mobile_no', $mobile_no);
      $this->db->group_start(); // Start the OR condition
      // $this->db->or_where('(dmm.hold_release != "Auto Hold" OR dmm.hold_release != "Manual Hold")');
      $this->db->where('abb.batch_status !=', 'Cancelled');

      // $this->db->or_where('dmm.hold_release', 'Release');
      $this->db->group_end(); // End the OR condition
      
      $mobile_no_data = $this->master_model->getRecords('dra_members dmm');

      $this->db->where('id',$batch_id);
      $batch_data = $this->master_model->getRecords('agency_batch');

      $isStatus = false;
      if(count($mobile_no_data) > 0) 
      {

        $new_batch_start_date = new DateTime($batch_data[0]['batch_from_date']);
        
        $a=$b=$c=$d=" ";
        foreach ($mobile_no_data as $key => $value) 
        {
          $holdBatchDaysStatus = false;
          $sameBatchIdStatus   = false;
          $daysvalidStatus     = false;
          $examattemptStatus   = false;

          $old_batch_end_date = new DateTime($value['batch_to_date']);
          $next5Days = clone $old_batch_end_date;
          $next5Days->modify('+5 days');

          // Format the result if needed
          $next5DaysFormatted = $next5Days->format('Y-m-d');

          if ($value['hold_release'] == 'Auto Hold' || $value['hold_release'] == 'Manual Hold') 
          {
            if ($next5Days < $new_batch_start_date) 
            {
              $holdBatchDaysStatus = true;      
            }
          }

          if ($value['batch_id'] != $batch_id) {
            $sameBatchIdStatus = true;
          }

          $endDateOfFirstBatch    = strtotime($value['batch_to_date']);
          $startDateOfSecondBatch = strtotime($batch_data[0]['batch_from_date']);
          $daysDifference         = ($startDateOfSecondBatch - $endDateOfFirstBatch) / (60 * 60 * 24);
          $attemptCount           = $value['re_attempt'];
          $regnumber              = $value['regnumber'];

          $this->db->where('member_no',$regnumber);
          $this->db->where('member_no !=','');
          $this->db->where('exam_status','F');
          $this->db->order_by("id","desc");
          $batch_exam_data = $this->master_model->getRecords('dra_eligible_master');
          
          if ($daysDifference < 270) 
          {
            if ($attemptCount >= 3 || count($batch_exam_data) >= 1) 
            {
              $examattemptStatus = true;    
            }
          }
          else
          {
            $daysvalidStatus = true;
          }

          $batchCode       = $value['batch_code'];
          $batchDuration   = date("d-M-Y", strtotime($value['batch_from_date'])).' to '.date("d-M-Y", strtotime($value['batch_to_date']));
          $batchStatus     = $value['batch_status'];
          $candidateStatus = $value['hold_release'];
          $reAttempt       = $value['re_attempt'];

          if (($daysvalidStatus || $examattemptStatus) || ($sameBatchIdStatus && $holdBatchDaysStatus)) 
          { 
            $isStatus   = true; 
          } 
          else
          { 
            $isStatus   = false; break;
          }
        }

        if (!$isStatus) 
        {
          $arr_response['status']  = 'success';
          $arr_response['massege'] = $arr_response['massege'] = 'This candidate has already been enrolled in batch <b>'.$batchCode.'</b>, with a duration from <b>'.$batchDuration.'</b>. The batch status is <b>'.$batchStatus.'</b>, and candidate status is <b>'.$candidateStatus.'</b>, and their attempt is <b>'.$reAttempt.'</b>';
        }
        else
        {
          $arr_response['status']  = 'success';
          $arr_response['massege'] = 'This candidate is new. to allow the candidate to enroll in the <b>'.$batch_data[0]['batch_code'].'</b> batch.';
        }
      }
      else
      {
        $arr_response['status']  = 'success';
        $arr_response['massege'] = 'This candidate is new. to allow the candidate to enroll in the <b>'.$batch_data[0]['batch_code'].'</b> batch.';
      }

      return $arr_response;
    }

    public function check_aadhar_no($batch_id,$aadhar_no)
    { 
      $arr_response = [];
      
      $arr_response['status']  = 'error';
      $arr_response['massege'] = '';

      $this->db->select('dmm.regid, dmm.batch_id, dmm.hold_release, dmm.mobile_no, abb.batch_status, abb.batch_from_date, abb.batch_to_date, dmm.regnumber, dmm.re_attempt');
      $this->db->join('agency_batch abb', 'abb.id = dmm.batch_id');
      $this->db->where('dmm.aadhar_no', $aadhar_no);
      
      if (isset($_POST['regId']) && $_POST['regId'] != "") {
        $this->db->where('dmm.regid !=', $_POST['regId']);
      }

      $this->db->where('abb.batch_status !=', 'Cancelled');
      
      $aadhar_no_data = $this->master_model->getRecords('dra_members dmm');
      $isStatus = false;

      $this->db->where('id',$batch_id);
      $batch_data = $this->master_model->getRecords('agency_batch');

      if(count($aadhar_no_data) > 0) 
      {
        $new_batch_start_date = new DateTime($batch_data[0]['batch_from_date']);

        $a=$b=$c=$d=" ";

        foreach ($aadhar_no_data as $key => $value) 
        {
          $holdBatchDaysStatus = false;
          $sameBatchIdStatus   = false;
          $daysvalidStatus     = false;
          $examattemptStatus   = false;

          $old_batch_end_date = new DateTime($value['batch_to_date']);
          $next5Days = clone $old_batch_end_date;
          $next5Days->modify('+5 days');

          // Format the result if needed
          $next5DaysFormatted = $next5Days->format('Y-m-d');

          if ($value['hold_release'] == 'Auto Hold' || $value['hold_release'] == 'Manual Hold') 
          {
            if ($next5Days < $new_batch_start_date) 
            {
              $a = 5;
              $holdBatchDaysStatus = true;      
            }
          }

          if ($value['batch_id'] != $batch_id) {
            $sameBatchIdStatus = true;
            $b = 6;       
          }

          $endDateOfFirstBatch    = strtotime($value['batch_to_date']);
          $startDateOfSecondBatch = strtotime($batch_data[0]['batch_from_date']);
          $daysDifference         = ($startDateOfSecondBatch - $endDateOfFirstBatch) / (60 * 60 * 24);
          $attemptCount           = $value['re_attempt'];
          $regnumber              = $value['regnumber'];

          $this->db->where('member_no',$regnumber);
          $this->db->where('member_no !=','');
          $this->db->where('exam_status','F');
          $this->db->order_by("id","desc");
          $batch_exam_data = $this->master_model->getRecords('dra_eligible_master');

          if ($daysDifference < 270) 
          {
            if ($attemptCount >= 3 || count($batch_exam_data) >= 1) 
            {
              $c = 7;
              $examattemptStatus = true;    
            }
          }
          else
          {
            $d = 8;
            $daysvalidStatus = true;
          }
          
          $batchCode       = $value['batch_code'];
          $batchDuration   = date("d-M-Y", strtotime($value['batch_from_date'])).' to '.date("d-M-Y", strtotime($value['batch_to_date']));
          $batchStatus     = $value['batch_status'];
          $candidateStatus = $value['hold_release'];
          $reAttempt       = $value['re_attempt'];

          if (($daysvalidStatus || $examattemptStatus) || ($sameBatchIdStatus && $holdBatchDaysStatus)) 
          { 
            $isStatus   = true; 
          } 
          else
          {
            $isStatus   = false; break;
          }
        }
        
        if (!$isStatus) 
        {
          $arr_response['status']  = 'success';
          $arr_response['massege'] = 'This candidate has already been enrolled in batch <b>'.$batchCode.'</b>, with a duration from <b>'.$batchDuration.'</b>. The batch status is <b>'.$batchStatus.'</b>, and candidate status is <b>'.$candidateStatus.'</b>, and their attempt is <b>'.$reAttempt.'</b>';
        }
        else
        {
          $arr_response['status']  = 'success';
          $arr_response['massege'] = 'This candidate is new. to allow the candidate to enroll in the <b>'.$batch_data[0]['batch_code'].'</b> batch.';
        }
      }
      else
      {
        $arr_response['status']  = 'success';
        $arr_response['massege'] = 'This candidate is new. to allow the candidate to enroll in the <b>'.$batch_data[0]['batch_code'].'</b> batch.';
      }

      return $arr_response;
    }
    
    public function check_idproof($batch_id,$idproof)
    { 
      $arr_response = [];
      
      $arr_response['status']  = 'error';
      $arr_response['massege'] = '';

      $this->db->select('dmm.regid,dmm.batch_id,abb.batch_code, dmm.hold_release, dmm.idproof_no, abb.batch_status, abb.batch_from_date, abb.batch_to_date, dmm.regnumber, dmm.re_attempt');
      $this->db->join('agency_batch abb', 'abb.id = dmm.batch_id');
      $this->db->where('dmm.idproof_no', $idproof);
      $this->db->where('abb.batch_status !=', 'Cancelled');

      if (isset($_POST['regId']) && $_POST['regId'] != "") {
        $this->db->where('dmm.regid !=', $_POST['regId']);
      }

      $idproof_data = $this->master_model->getRecords('dra_members dmm');      
      $isStatus = false;

      $this->db->where('id',$batch_id);
      $batch_data = $this->master_model->getRecords('agency_batch');

      if(count($idproof_data) > 0) 
      {       
        $new_batch_start_date = new DateTime($batch_data[0]['batch_from_date']);

        foreach ($idproof_data as $key => $value) 
        {
          $holdBatchDaysStatus = false;
          $sameBatchIdStatus   = false;
          $daysvalidStatus     = false;
          $examattemptStatus   = false;

          $old_batch_end_date = new DateTime($value['batch_to_date']);
          $next5Days = clone $old_batch_end_date;
          $next5Days->modify('+5 days');

          // Format the result if needed
          $next5DaysFormatted = $next5Days->format('Y-m-d');

          if ($value['hold_release'] == 'Auto Hold' || $value['hold_release'] == 'Manual Hold') 
          {
            if ($next5Days < $new_batch_start_date) 
            {
              $a = 5;
              $holdBatchDaysStatus = true;      
            }
          }

          if ($value['batch_id'] != $batch_id) {
            $sameBatchIdStatus = true;
            $b = 6;       
          }

          $endDateOfFirstBatch    = strtotime($value['batch_to_date']);
          $startDateOfSecondBatch = strtotime($batch_data[0]['batch_from_date']);
          $daysDifference         = ($startDateOfSecondBatch - $endDateOfFirstBatch) / (60 * 60 * 24);
          $attemptCount           = $value['re_attempt'];
          $regnumber              = $value['regnumber'];

          $this->db->where('member_no',$regnumber);
          $this->db->where('member_no !=','');
          $this->db->where('exam_status','F');
          $this->db->order_by("id","desc");
          $batch_exam_data = $this->master_model->getRecords('dra_eligible_master');

          if ($daysDifference < 270) 
          {
            if ($attemptCount >= 3 || count($batch_exam_data) >= 1) 
            {
              $c = 7;
              $examattemptStatus = true;    
            }
          }
          else
          {
            $d = 8;
            $daysvalidStatus = true;
          }

          $batchCode       = $value['batch_code'];
          $batchDuration   = date("d-M-Y", strtotime($value['batch_from_date'])).' to '.date("d-M-Y", strtotime($value['batch_to_date']));
          $batchStatus     = $value['batch_status'];
          $candidateStatus = $value['hold_release'];
          $reAttempt       = $value['re_attempt'];

          if (($daysvalidStatus || $examattemptStatus) || ($sameBatchIdStatus && $holdBatchDaysStatus)) 
          { 
            $isStatus   = true; 
          } 
          else
          {
            $isStatus   = false; break;
          }
        }
        
        if (!$isStatus) 
        {
          $arr_response['status']  = 'success';
          $arr_response['massege'] = $arr_response['massege'] = 'This candidate has already been enrolled in batch <b>'.$batchCode.'</b>, with a duration from <b>'.$batchDuration.'</b>. The batch status is <b>'.$batchStatus.'</b>, and candidate status is <b>'.$candidateStatus.'</b>, and their attempt is <b>'.$reAttempt.'</b>';
        }
        else
        {
          $arr_response['status']  = 'success';
          $arr_response['massege'] = 'This candidate is new. to allow the candidate to enroll in the <b>'.$batch_data[0]['batch_code'].'</b> batch.';
        }
      }
      else
      {
        $arr_response['status']  = 'success';
        $arr_response['massege'] = 'This candidate is new. to allow the candidate to enroll in the <b>'.$batch_data[0]['batch_code'].'</b> batch.';
      }

      return $arr_response;
    }
}
