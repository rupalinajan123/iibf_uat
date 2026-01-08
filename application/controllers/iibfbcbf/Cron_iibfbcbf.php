<?php
/********************************************************************
 * DESCRIPTION: DAILY COMMON CRON FOR BCBF MODULE
 * CREATED BY: SAGAR MATALE ON 2024-03-25
 * UPDATED BY: SAGAR MATALE ON 2024-10-10 
 ********************************************************************/

defined('BASEPATH') or exit('No direct script access allowed');
class Cron_iibfbcbf extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    
    $this->load->model('master_model');
    $this->load->model('iibfbcbf/Iibf_bcbf_model');
    $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set("memory_limit", "-1");
  }

  function index()
  {
    echo 'Welcome to DAILY COMMON CRON FOR BCBF MODULE';
  }

  /********************************************************************
   * DESCRIPTION: SEND EMAIL & SMS TO BCBF CANDIDATES ON 2nd DAY OF BATCH TRAINING. MOSTLY AT THE MID NIGHT OR DAY 2 MORNING.
   * CREATED BY: SAGAR MATALE ON 2024-03-25
   * UPDATED BY: SAGAR MATALE ON 2024-10-10 : REVISED EMAIL & SMS CONTENT RECEIVED
   ********************************************************************/
  public function iibfbcbf_candidate_send_mail_sms()
  {
    //GET THE LIST OF BCBF APPROVED BATCHES WHICH COMPLETED THE 1ST DAY TRAINING
    $yesterday = date('Y-m-d', strtotime("- 1 day"));
    $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = btch.agency_id', 'INNER');
    $this->db->join('iibfbcbf_batch_candidates cand', 'cand.batch_id = btch.batch_id', 'INNER');
    $batch_candidate_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch btch', array('btch.is_deleted'=>'0', 'btch.batch_status'=>'3', 'cand.is_deleted' => '0', 'cand.hold_release_status'=> '3', 'btch.batch_start_date'=>$yesterday), 'btch.batch_id, btch.batch_code, btch.batch_type, btch.batch_start_date, btch.batch_end_date, btch.batch_daily_start_time, btch.batch_daily_end_time, btch.contact_person_name, btch.contact_person_mobile, btch.contact_person_email, am.agency_name, cand.candidate_id, cand.agency_id, cand.regnumber, cand.batch_id, cand.training_id, cand.salutation, cand.first_name, cand.middle_name, cand.last_name, cand.dob, cand.email_id, cand.mobile_no, cand.parent_id, cand.hold_release_status, cand.is_deleted'); 
    $batch_candidate_data_qry = $this->db->last_query(); 
    //_pq(1); 
    
    if(count($batch_candidate_data) > 0)
    {
      $log_id = $this->save_bcbf_training_log('',"Training 2nd Day Notification - Execution Started : ".date('Y-m-d H:i:s'));
      $this->save_bcbf_training_log($log_id, $batch_candidate_data_qry." >> Record Count : ".count($batch_candidate_data));

      $emailerstr = $this->master_model->getRecords('emailer',array('emailer_name'=>'IIBFBCBF_TRAINING_INFORMATION'), 'emailer_name, emailer_text, sms_text, sms_template_id, sms_sender, subject');
      $emailerstr_qry = $this->db->last_query();
      if(count($emailerstr) > 0)
      {      
        $send_cnt = 0;
        foreach($batch_candidate_data as $batch_member_res)
        {
          $batch_type = 'Basic Batch'; if($batch_member_res['batch_type'] == '2') { $batch_type = 'Advanced Batch'; }
          
          $CandidateName = '';								
          if($batch_member_res['salutation'] != '') { $CandidateName .= $batch_member_res['salutation']; }
          if($batch_member_res['first_name'] != '') { $CandidateName .= ' '.$batch_member_res['first_name']; }
          if($batch_member_res['middle_name'] != '') { $CandidateName .= ' '.$batch_member_res['middle_name']; }
          if($batch_member_res['last_name'] != '') { $CandidateName .= ' '.$batch_member_res['last_name']; }
                    
          $mail_arg = array();
          $mail_arg['subject'] = $emailerstr[0]['subject'];
          $mail_arg['to_email'] = $batch_member_res['email_id']; //'sagar.matale@esds.co.in'; //
          $mail_arg['to_name'] = $CandidateName; //'sagar'; //
          $mail_arg['cc_email'] = '';//sagar.matale@esds.co.in,anil.s@esds.co.in
          $mail_arg['bcc_email'] = 'iibfteam@esds.co.in';//sagar.matale@esds.co.in,anil.s@esds.co.in
          $mail_arg['is_header_footer_required'] = '0';
          $mail_arg['view_flag'] = '0';
            
          $mail_content = '
            <style type="text/css">            
              p { padding: 0; margin: 0; font-weight: bold; }
              p.footer_regards { line-height: 20px; }
              table.inner_tbl { font-size: 14px; border-collapse: collapse; width: 100%; color:#000; }
              table.inner_tbl tbody tr td { padding: 5px 10px; border-collapse: collapse; border: 1px solid #776f6f; line-height:20px; vertical-align:top; min-width:200px; }                          
            </style>'.$emailerstr[0]['emailer_text'];

          $mail_content = str_replace("#BATCH_TYPE#", $batch_type, $mail_content);
          $mail_content = str_replace("#CANDIDATENAME#", $CandidateName, $mail_content);
          $mail_content = str_replace("#DOB#", date("d-M-Y", strtotime($batch_member_res['dob'])), $mail_content);
          $mail_content = str_replace("#BATCH_START_DATE#", date("d-M-Y", strtotime($batch_member_res['batch_start_date'])), $mail_content);
          $mail_content = str_replace("#BATCH_END_DATE#", date("d-M-Y", strtotime($batch_member_res['batch_end_date'])), $mail_content);
          $mail_content = str_replace("#BATCH_DAILY_START_TIME#", date("h:i A", strtotime($batch_member_res['batch_daily_start_time'])), $mail_content);
          $mail_content = str_replace("#BATCH_DAILY_END_TIME#", date("h:i A", strtotime($batch_member_res['batch_daily_end_time'])), $mail_content);
          $mail_content = str_replace("#AGENCY_NAME#", $batch_member_res['agency_name'], $mail_content);
          $mail_content = str_replace("#BATCH_COORDINATOR_NAME#", $batch_member_res['contact_person_name'], $mail_content);
          $mail_content = str_replace("#BATCH_COORDINATOR_MOBILE#", $batch_member_res['contact_person_mobile'], $mail_content);

          if($batch_member_res['contact_person_email'] != "")
          {
            $mail_content = str_replace("#BATCH_COORDINATOR_EMAIL#", $batch_member_res['contact_person_email'], $mail_content);       
          }
          else { $mail_content = str_replace("#BATCH_COORDINATOR_EMAIL#", 'NA', $mail_content); }    

          $mail_arg['mail_content'] = $mail_content;
          $this->Iibf_bcbf_model->iibfbcbf_send_mail_common($mail_arg);
            
          //START : SMS SENDING CODE
          $batch_type_sms = 'Basic'; if($batch_member_res['batch_type'] == '2') { $batch_type_sms = 'Advanced'; }
          $sms_text = '';
          $mobile = $batch_member_res['mobile_no'];	
          $sms_text = $emailerstr[0]['sms_text'];
          $sms_text = str_replace('#TRAINING_TYPE#', $batch_type_sms, $sms_text);
          $sms_text = str_replace('#BATCH_START_DATE#', date('d/m/Y',strtotime($batch_member_res['batch_start_date'])), $sms_text);
          $sms_text = str_replace('#BATCH_END_DATE#', date('d/m/Y',strtotime($batch_member_res['batch_end_date'])), $sms_text);
          $sms_text = str_replace('#AGENCY_NAME#', substr($batch_member_res['agency_name'],0,30), $sms_text);
          $res = $this->master_model->send_sms_common_all($mobile, $sms_text, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']);

          $add_log['controller_name'] = $this->router->fetch_class();
          $add_log['function_name'] = $this->router->fetch_method();
          $add_log['log_id_fk'] = $log_id;
          $add_log['email_details'] = serialize($mail_arg);
          $add_log['sms_details'] = serialize($sms_text);
          $add_log['created_on'] = date('Y-m-d H:i:s');
          $this->master_model->insertRecord('iibfbcbf_cron_log_send_mail_sms_to_candidates_data', $add_log, true);
          $send_cnt++; 
        } 
        
        $this->save_bcbf_training_log($log_id, "Total Email & Sms send count : ".$send_cnt);
      }
      else
      {
        $this->save_bcbf_training_log($log_id, "Emailer Not found : ".$emailerstr_qry);
      }
      
      $this->save_bcbf_training_log($log_id, "Execution Ended : ".date('Y-m-d H:i:s'));
    }    
  }

  /********************************************************************
   * DESCRIPTION: SEND EMAIL & SMS TO BCBF CANDIDATES ON LAST DAY OF BATCH TRAINING.
   * CREATED BY: SAGAR MATALE ON 2024-10-10   
   ********************************************************************/
  public function iibfbcbf_candidate_send_mail_sms_on_training_last_day()
  {
    //GET THE LIST OF BCBF APPROVED BATCHES WHOSE TRAINING LAST DAY IS TODAY
    $todays_date = date('Y-m-d');
    $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = btch.agency_id', 'INNER');
    $this->db->join('iibfbcbf_batch_candidates cand', 'cand.batch_id = btch.batch_id', 'INNER');
    $batch_candidate_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch btch', array('btch.is_deleted'=>'0', 'btch.batch_status'=>'3', 'cand.is_deleted' => '0', 'cand.hold_release_status'=> '3', 'btch.batch_end_date'=>$todays_date), 'btch.batch_id, btch.batch_code, btch.batch_type, btch.batch_start_date, btch.batch_end_date, btch.batch_daily_start_time, btch.batch_daily_end_time, btch.contact_person_name, btch.contact_person_mobile, btch.contact_person_email, am.agency_name, cand.candidate_id, cand.agency_id, cand.regnumber, cand.batch_id, cand.training_id, cand.salutation, cand.first_name, cand.middle_name, cand.last_name, cand.dob, cand.email_id, cand.mobile_no, cand.parent_id, cand.hold_release_status, cand.is_deleted'); 
    $batch_candidate_data_qry = $this->db->last_query();  
    //_pq(1);   

    if(count($batch_candidate_data) > 0)
    {
      $log_id = $this->save_bcbf_training_log('',"Training Last Day Notification - Execution Started : ".date('Y-m-d H:i:s'));
      $this->save_bcbf_training_log($log_id, $batch_candidate_data_qry." >> Record Count : ".count($batch_candidate_data));

      $emailerstr = $this->master_model->getRecords('emailer',array('emailer_name'=>'IIBFBCBF_TRAINING_INFORMATION_LAST_DAY_NOTIFICATION'), 'emailer_name, emailer_text, sms_text, sms_template_id, sms_sender, subject');
      $emailerstr_qry = $this->db->last_query();
      if(count($emailerstr) > 0)
      {      
        $send_cnt = 0;
        foreach($batch_candidate_data as $batch_member_res)
        {
          $batch_type = 'Basic Batch'; if($batch_member_res['batch_type'] == '2') { $batch_type = 'Advanced Batch'; }
          
          $CandidateName = '';								
          if($batch_member_res['salutation'] != '') { $CandidateName .= $batch_member_res['salutation']; }
          if($batch_member_res['first_name'] != '') { $CandidateName .= ' '.$batch_member_res['first_name']; }
          if($batch_member_res['middle_name'] != '') { $CandidateName .= ' '.$batch_member_res['middle_name']; }
          if($batch_member_res['last_name'] != '') { $CandidateName .= ' '.$batch_member_res['last_name']; }
                    
          $mail_arg = array();
          $mail_arg['subject'] = $emailerstr[0]['subject'];
          $mail_arg['to_email'] = $batch_member_res['email_id']; //'sagar.matale@esds.co.in'; //
          $mail_arg['to_name'] = $CandidateName; //'sagar'; //
          $mail_arg['cc_email'] = '';//sagar.matale@esds.co.in,anil.s@esds.co.in
          $mail_arg['bcc_email'] = 'iibfteam@esds.co.in';//sagar.matale@esds.co.in,anil.s@esds.co.in
          $mail_arg['is_header_footer_required'] = '0';
          $mail_arg['view_flag'] = '0';
            
          $mail_content = '
            <style type="text/css">            
              p { padding: 0; margin: 0; font-weight: bold; }
              p.footer_regards { line-height: 20px; }
              table.inner_tbl { font-size: 14px; border-collapse: collapse; width: 100%; color:#000; border: }
              table.inner_tbl tbody tr td { padding: 5px 10px; border-collapse: collapse; border: 1px solid #776f6f; line-height:20px; vertical-align:top; min-width:200px; }                          
            </style>'.$emailerstr[0]['emailer_text'];

          $mail_content = str_replace("#TRAINING_ID#", $batch_member_res['training_id'], $mail_content);
          $mail_content = str_replace("#BATCH_TYPE#", $batch_type, $mail_content);
          $mail_content = str_replace("#CANDIDATENAME#", $CandidateName, $mail_content);
          $mail_content = str_replace("#DOB#", date("d-M-Y", strtotime($batch_member_res['dob'])), $mail_content);
          $mail_content = str_replace("#BATCH_START_DATE#", date("d-M-Y", strtotime($batch_member_res['batch_start_date'])), $mail_content);
          $mail_content = str_replace("#BATCH_END_DATE#", date("d-M-Y", strtotime($batch_member_res['batch_end_date'])), $mail_content);
          $mail_content = str_replace("#BATCH_DAILY_START_TIME#", date("h:i A", strtotime($batch_member_res['batch_daily_start_time'])), $mail_content);
          $mail_content = str_replace("#BATCH_DAILY_END_TIME#", date("h:i A", strtotime($batch_member_res['batch_daily_end_time'])), $mail_content);
          $mail_content = str_replace("#AGENCY_NAME#", $batch_member_res['agency_name'], $mail_content);
          $mail_content = str_replace("#BATCH_COORDINATOR_NAME#", $batch_member_res['contact_person_name'], $mail_content);
          $mail_content = str_replace("#BATCH_COORDINATOR_MOBILE#", $batch_member_res['contact_person_mobile'], $mail_content);

          if($batch_member_res['contact_person_email'] != "")
          {
            $mail_content = str_replace("#BATCH_COORDINATOR_EMAIL#", $batch_member_res['contact_person_email'], $mail_content);       
          }
          else { $mail_content = str_replace("#BATCH_COORDINATOR_EMAIL#", 'NA', $mail_content); }

          $mail_arg['mail_content'] = $mail_content;          
          $this->Iibf_bcbf_model->iibfbcbf_send_mail_common($mail_arg);          
            
          //START : SMS SENDING CODE
          $batch_type_sms = 'Basic'; if($batch_member_res['batch_type'] == '2') { $batch_type_sms = 'Advanced'; }
          $sms_text = '';
          $mobile = $batch_member_res['mobile_no'];	
          $sms_text = $emailerstr[0]['sms_text'];
          $sms_text = str_replace('#AGENCY_NAME#', substr($batch_member_res['agency_name'],0,30), $sms_text);
          $sms_text = str_replace('#TRAINING_ID#', $batch_member_res['training_id'], $sms_text);
          $res = $this->master_model->send_sms_common_all($mobile, $sms_text, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']);

          $add_log['controller_name'] = $this->router->fetch_class();
          $add_log['function_name'] = $this->router->fetch_method();
          $add_log['log_id_fk'] = $log_id;
          $add_log['email_details'] = serialize($mail_arg);
          $add_log['sms_details'] = serialize($sms_text);
          $add_log['created_on'] = date('Y-m-d H:i:s');
          $this->master_model->insertRecord('iibfbcbf_cron_log_send_mail_sms_to_candidates_data', $add_log, true);
          $send_cnt++; 
        } 
        
        $this->save_bcbf_training_log($log_id, "Total Email & Sms send count : ".$send_cnt);
      }
      else
      {
        $this->save_bcbf_training_log($log_id, "Emailer Not found : ".$emailerstr_qry);
      }
      
      $this->save_bcbf_training_log($log_id, "Execution Ended : ".date('Y-m-d H:i:s'));
    }    
  }

  function save_bcbf_training_log($log_id='',$description='')
  {
    if($log_id == '')
    {
      $add_log['controller_name'] = $this->router->fetch_class();
      $add_log['function_name'] = $this->router->fetch_method();
      $add_log['description'] = $description;
      $add_log['execution_start_time'] = date('Y-m-d H:i:s');
      $add_log['created_on'] = date('Y-m-d H:i:s');
      return $log_id = $this->master_model->insertRecord('iibfbcbf_cron_log_send_mail_sms_to_candidates', $add_log, true);
    }
    else
    {
      $log_data = $this->master_model->getRecords('iibfbcbf_cron_log_send_mail_sms_to_candidates', array('log_id'=>$log_id), 'description'); 

      $up_log['execution_end_time'] = date('Y-m-d H:i:s');
      $up_log['description'] = $log_data[0]['description']."<br>".$description;
      $up_log['updated_on'] = date('Y-m-d H:i:s');
      $this->master_model->updateRecord('iibfbcbf_cron_log_send_mail_sms_to_candidates',$up_log,array('log_id'=>$log_id));
    }
  }
}
