<?php
/********************************************************************
 * DESCRIPTION: DAILY CRON FOR DRA MODULE
 * CREATED BY: SAGAR MATALE ON 2023-08-10
 * UPDATED BY: 
 ********************************************************************/

defined('BASEPATH') or exit('No direct script access allowed');
class Cron_dra extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    //$this->load->model('UserModel');
    //$this->load->helper('pagination_helper'); 
    //$this->load->library('pagination');
    $this->load->model('Master_model');
    $this->load->model('log_model');
    $this->load->model('Emailsending');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set("memory_limit", "-1");
  }

  function index()
  {
    echo 'Welcome to Daily Cron for DRA module';
  }

  /********************************************************************
   * DESCRIPTION: SEND MAIL  & SMS TO DRA CANDIDATES AFTER DAY 1 OF TRAINING. MOSTLY AT THE MID NIGHT OR DAY 2 MORNING.
   * CREATED BY: SAGAR MATALE ON 2023-08-10
   * UPDATED BY: 
   ********************************************************************/
  public function dra_members_send_mail_sms()
  {
    $log_id = $this->save_dra_log('','Execution Started');
    
    //GET THE LIST OF DRA APPROVED BATCHES WHICH COMPLETED THE 1ST DAY TRAINING
    $yesterday = date('Y-m-d', strtotime("- 1 day"));
    $this->db->join('dra_inst_registration ir', 'ir.id = ab.agency_id');
    $batch_data = $this->Master_model->getRecords('agency_batch ab', array('ab.is_deleted'=>'0', 'ab.batch_status'=>'Approved', 'ab.batch_code like '=>'BC%', 'ab.batch_from_date'=>$yesterday), 'ab.id, ab.batch_code, ab.contact_person_name, ab.alt_contact_person_name, ab.batch_from_date, ab.batch_to_date, ab.timing_from, ab.timing_to, ir.inst_name, ir.inst_head_contact_no, ir.inst_head_email'); 
    $batch_data_qry = $this->db->last_query(); 
    $this->save_dra_log($log_id, $batch_data_qry." >> Record Count : ".count($batch_data));

    if(count($batch_data) > 0)
    {
      foreach($batch_data as $batch_res)
      {
        //GET THE LIST OF DRA MEMBERS FOR SPECIFIC BATCHES
        $batch_member_data = $this->Master_model->getRecords('dra_members dm', array('dm.isactive'=>'1', 'dm.isdeleted' => '0', 'dm.hold_release'=> 'Release', 'dm.batch_id'=>$batch_res['id']), 'dm.regid, dm.inst_code, dm.registration_no, dm.batch_id, dm.training_id, dm.namesub, dm.firstname, dm.middlename, dm.lastname, dm.dateofbirth, dm.email_id, dm.mobile_no, dm.status, dm.IsNew, dm.new_reg, dm.entered_regnumber, dm.hold_release, dm.isactive, dm.isdeleted'); 
        $batch_member_data_qry = $this->db->last_query();
        $this->save_dra_log($log_id, $batch_member_data_qry." >> Record Count : ".count($batch_member_data));

        if(count($batch_member_data) > 0)
        {
          $send_cnt = 0;
          foreach($batch_member_data as $batch_member_res)
          {
            //START : ADDED BY SAGAR MATALE ON 24-07-2023 FOR SEND EMAIL & SMS
            $email_sms_arr = array();
            $email_sms_arr['namesub'] = $batch_member_res['namesub'];
            $email_sms_arr['firstname'] = $batch_member_res['firstname'];
            $email_sms_arr['middlename'] = $batch_member_res['middlename'];
            $email_sms_arr['lastname'] = $batch_member_res['lastname'];
            $email_sms_arr['dob1'] = $batch_member_res['dateofbirth'];
            $email_sms_arr['member_email_id'] = $batch_member_res['email_id'];
            $email_sms_arr['member_mobile'] = $batch_member_res['mobile_no'];
            $email_sms_arr['contact_person_name'] = $batch_res['contact_person_name'];
            $email_sms_arr['alt_contact_person_name'] = $batch_res['alt_contact_person_name'];
            $email_sms_arr['training_id'] = $batch_member_res['training_id'];
            $email_sms_arr['batch_from_date'] = $batch_res['batch_from_date'];
            $email_sms_arr['batch_to_date'] = $batch_res['batch_to_date'];
            $email_sms_arr['timing_from'] = $batch_res['timing_from'];
            $email_sms_arr['timing_to'] = $batch_res['timing_to'];
            $email_sms_arr['inst_name'] = $batch_res['inst_name'];
            $email_sms_arr['inst_head_contact_no'] = $batch_res['inst_head_contact_no'];
            $email_sms_arr['inst_head_email'] = $batch_res['inst_head_email'];
            $this->inc_send_mail_sms($email_sms_arr,$log_id);
            $send_cnt++;            
            //$this->pa($email_sms_arr,1);
          }

          $this->save_dra_log($log_id, "Batch Code Email & Sms count : ".$send_cnt);
        }
      }
    }

    $this->save_dra_log($log_id, "Execution Ended : ".date('Y-m-d H:i:s'));
  }

  //START : ADDED BY SAGAR MATALE ON 21-07-2023 FOR SENDING EMAIL & SMS
  public function inc_send_mail_sms($email_sms_arr = array(), $log_id=0)
  {
    if(count($email_sms_arr) > 0)
    {
      $this->load->model('Emailsending');
                
      $CandidateName = '';								
      if($email_sms_arr['namesub'] != '') { $CandidateName .= $email_sms_arr['namesub']; }
      if($email_sms_arr['firstname'] != '') { $CandidateName .= ' '.$email_sms_arr['firstname']; }
      if($email_sms_arr['middlename'] != '') { $CandidateName .= ' '.$email_sms_arr['middlename']; }
      if($email_sms_arr['lastname'] != '') { $CandidateName .= ' '.$email_sms_arr['lastname']; }	
      
      $AgencyContactPersonName = '';
      if($email_sms_arr['contact_person_name'] != "" )
      {
        $AgencyContactPersonName = $email_sms_arr['contact_person_name'];
      }
      else if($email_sms_arr['alt_contact_person_name'] != "" )
      {
        $AgencyContactPersonName = $email_sms_arr['alt_contact_person_name']; 
      }
      
      $emailerstr = $this->master_model->getRecords('emailer',array('emailer_name'=>'DRA_TRAINING_INFORMATION'));
      if(count($emailerstr) > 0)
      {
        /*$message_email = "
        <div style='max-width: 800px; width: 90%; padding: 0; margin: 20px 10px; font-size: 14px; line-height: 22px; color:#000;'>
          <p style='margin: 0;text-align: center;font-size: 18px;	line-height: 28px; padding: 10px 0 15px 0; '>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>(AN ISO 21001:2018 Certified )</p>

          <p style='margin: 0;'>Dear Candidate,</p>
          <p style='margin: 0;'>Information on your DRA training is given as below:</p>

          <div style='margin: 5px 0 15px 30px;'>
            <p style='margin: 0;line-height: 24px;'>
            Training ID: ".$email_sms_arr['training_id']." *<br>
            Candidate's Name : ".$CandidateName."<br>
            Candidate's Date of Birth: ".date('d/m/Y',strtotime($email_sms_arr['dob1']))."<br>
            Training Period: From ".date('d/m/Y',strtotime($email_sms_arr['batch_from_date']))." To ".date('d/m/Y',strtotime($email_sms_arr['batch_to_date']))."<br>
            Training Time: ".$email_sms_arr['timing_from']." to ".$email_sms_arr['timing_to']."<br>
            Name of Training Providing Agency: ".$email_sms_arr['inst_name']."<br>
            Name of Contact Person of the Agency: Mr./ Ms. ".$AgencyContactPersonName."<br>
            Contact Number and e-mail Id. of the Agency: ".$email_sms_arr['inst_head_contact_no']."; ".$email_sms_arr['inst_head_email']."<br>
            </p>
          </div>

          <p style='margin: 0 0 10px 0;'>Note I: While attending the online training, kindly mention your Name followed by your Training ID on the Online Platform. For Example: Your Name_T01G-BC00001</p>

          <p style='margin: 0;'>Note II: In case of any query or for any related information, please contact (by Call / E-mail) with the Contact Person of the Agency whose details are mentioned herein above.</p>

          <div style='margin: 15px 0 0 0;'>
            <p style='margin: 0 0 3px 0;'>Regards,</p>
            <p style='margin: 0;line-height: 18px;'>DRA Cell<br>Indian Institute of Banking & Finance</p>
          </div>
        </div>"; */
        //echo $message_email; exit;
        
        $email_text = $emailerstr[0]['emailer_text'];
        $email_text = str_replace('#TRAINING_ID#', $email_sms_arr['training_id'], $email_text);
        $email_text = str_replace('#CANDIDATENAME#', $CandidateName, $email_text);
        $email_text = str_replace('#DOB#', $email_sms_arr['dob1'], $email_text);
        $email_text = str_replace('#BATCH_FROM_DATE#', date('d/m/Y',strtotime($email_sms_arr['batch_from_date'])), $email_text);
        $email_text = str_replace('#BATCH_TO_DATE#', date('d/m/Y',strtotime($email_sms_arr['batch_to_date'])), $email_text);
        $email_text = str_replace('#TIMING_FROM#', $email_sms_arr['timing_from'], $email_text);
        $email_text = str_replace('#TIMING_TO#', $email_sms_arr['timing_to'], $email_text);
        $email_text = str_replace('#INST_NAME#', $email_sms_arr['inst_name'], $email_text);
        $email_text = str_replace('#AGENCYCONTACTPERSONNAME#', $AgencyContactPersonName, $email_text);
        $email_text = str_replace('#INST_HEAD_CONTACT_NO#', $email_sms_arr['inst_head_contact_no'], $email_text);
        $email_text = str_replace('#INST_HEAD_EMAIL#', $email_sms_arr['inst_head_email'], $email_text);
      
        $info_arr_dra = array();
        $info_arr_dra['to'] = 'sagar.matale@esds.co.in'; //$email_sms_arr['member_email_id'];
        $info_arr_dra['from'] = 'logs@iibf.esdsconnect.com';
        $info_arr_dra['bcc'] = 'sagar.matale@esds.co.in'; //array('iibfdevp@esds.co.in');
        $info_arr_dra['subject'] = $emailerstr[0]['subject'];
        $info_arr_dra['message'] = $email_text;									
        $this->Emailsending->sendmail($info_arr_dra);
        //$this->sendmail($info_arr_dra);
      
        //START : SMS SENDING CODE
        /*$mobile = '7588096918'; //$email_sms_arr['member_mobile'];	
        $message = 'Name: '.$CandidateName.'; Training ID: '.$email_sms_arr['training_id'].'; From '.date('d/m/Y',strtotime($email_sms_arr['batch_from_date'])).' To '.date('d/m/Y',strtotime($email_sms_arr['batch_to_date'])).'. Mention your Name and Training ID ('.$email_sms_arr['training_id'].') on the screen of online training platform. Please check e-mail on the same for the detailed info. Thank You. Team IIBF.';*/
        //$res = $this->master_model->send_sms_trustsignal(intval($mobile), $message, 'VtWir7qVR');

        $mobile = '7588096918'; //$email_sms_arr['member_mobile'];	
        $sms_text = $emailerstr[0]['sms_text'];
        $sms_text = str_replace('#CANDIDATENAME#', $CandidateName, $sms_text);
        $sms_text = str_replace('#TRAINING_ID#', $email_sms_arr['training_id'], $sms_text);
        $sms_text = str_replace('#BATCH_FROM_DATE#', date('d/m/Y',strtotime($email_sms_arr['batch_from_date'])), $sms_text);
        $sms_text = str_replace('#BATCH_TO_DATE#', date('d/m/Y',strtotime($email_sms_arr['batch_to_date'])), $sms_text);
        $res = $this->master_model->send_sms_common_all($mobile, $sms_text, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']);

        $add_log['controller_name'] = $this->router->fetch_class();
        $add_log['function_name'] = $this->router->fetch_method();
        $add_log['log_id_fk'] = $log_id;
        $add_log['email_details'] = serialize($info_arr_dra);
        $add_log['sms_details'] = serialize($sms_text);
        $add_log['created_on'] = date('Y-m-d H:i:s');
        $this->master_model->insertRecord('dra_cron_log_send_mail_sms_dra_members_data', $add_log, true);
      }
    }
  }//END : ADDED BY SAGAR MATALE ON 21-07-2023 FOR SENDING EMAIL & SMS

  function save_dra_log($log_id='',$description='')
  {
    if($log_id == '')
    {
      $add_log['controller_name'] = $this->router->fetch_class();
      $add_log['function_name'] = $this->router->fetch_method();
      $add_log['description'] = $description;
      $add_log['execution_start_time'] = date('Y-m-d H:i:s');
      $add_log['created_on'] = date('Y-m-d H:i:s');
      return $log_id = $this->master_model->insertRecord('dra_cron_log_send_mail_sms_dra_members', $add_log, true);
    }
    else
    {
      $log_data = $this->Master_model->getRecords('dra_cron_log_send_mail_sms_dra_members', array('log_id'=>$log_id), 'description'); 

      $up_log['execution_end_time'] = date('Y-m-d H:i:s');
      $up_log['description'] = $log_data[0]['description']."<br>".$description;
      $up_log['updated_on'] = date('Y-m-d H:i:s');
      $this->master_model->updateRecord('dra_cron_log_send_mail_sms_dra_members',$up_log,array('log_id'=>$log_id));
    }
  }  

  function pa($arr=array(),$exit_flag=0)
  {
    echo '<pre>';  print_r($arr);  echo '</pre>'; if($exit_flag == 1) { exit(); }
  }

  public function setting_smtp()
	{
		$permission=TRUE;
		
		if($permission==TRUE)
		{
			$config['protocol']    	= 'SMTP';
			//$config['smtp_host']    = 'iibf.esdsconnect.com';
			// local ip 10.11.38.100 instead of 115.124.108.41 can also be used
			$config['smtp_host']    = '115.124.108.41';
			$config['smtp_port']    = '25';
			$config['smtp_timeout'] = '10';
			$config['smtp_user']    = 'logs@iibf.esdsconnect.com';
			$config['smtp_pass']    = 'logs@IiBf!@#';
			$config['charset']    	= 'utf-8';
			$config['newline']    	= "\r\n";
			$config['mailtype'] 	= 'html'; // or html
			$config['validation'] 	= TRUE; // bool whether to validate email or not  
			$this->email->initialize($config);	
		}
	}

  public function sendmail($info_arr) 
	{			
		$this->setting_smtp();
		$this->email->clear(TRUE);
		$this->email->set_newline("\r\n");
		$this->email->from($info_arr['from']);
		$this->email->to($info_arr['to']);
    $this->email->subject($info_arr['subject'].' Pre-Production');//Added by Priyanka W for DRA testing
		$this->email->set_mailtype("html");
		$data['base_url']=base_url();
		$this->email->message($info_arr['message']);
		if($this->email->send())
		{
			 $this->email->print_debugger();
			//	echo $this->email->print_debugger();
			return true;
		}		
	}
}
