<?php
/*
 	* Controller Name	:	Cpdcron
 	* Created By		:	Chaitali
 	* Created Date		:	18-06-2020
*/
//https://iibf.esdsconnect.com/admin/Cpdcron/cpd_data

defined('BASEPATH') OR exit('No direct script access allowed');

class Jaiib_mail_send_new extends CI_Controller {
			
public function __construct()
{
		parent::__construct();
		
		$this->load->library('upload');	
			$this->load->helper('upload_helper');
			$this->load->helper('master_helper');
			$this->load->helper('general_helper');
			$this->load->model('Master_model');		
			$this->load->library('email');
			$this->load->helper('date');
			$this->load->library('email');
			$this->load->model('Emailsending');
			$this->load->model('log_model');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
}
///usr/local/bin/php /home/supp0rttest/public_html/index.php Jaiib_mail_send jaiib_mail_21
///usr/local/bin/php /home/supp0rttest/public_html/index.php Jaiib_mail_send jaiib_mail_42
///usr/local/bin/php /home/supp0rttest/public_html/index.php Jaiib_mail_send jaiib_mail_992

public function jaiib_mail_21()
{
    $admit_data = array();
    $select = 'mem_mem_no';
    $this->db->distinct('mem_mem_no');
    $this->db->where('exm_cd',$this->config->item('examCodeJaiib'));
    $this->db->where('exm_prd','220');
    $this->db->where('remark','4');
    $this->db->where('mail_send','1');
	$this->db->limit(10000);
    $admit_data = $this->Master_model->getRecords('admit_card_details_jaiib','',$select);
   
    /*echo "<pre>";
    print_r($admit_data);
    echo $this->db->last_query();
    exit;*/
   
    if(!empty($admit_data))
    {   
        $insert_data = array();
        foreach($admit_data as $admit_row)
        {
            $member_no = $admit_row['mem_mem_no'];
       
            $new_mem_reg = array();
            $select = 'regnumber,email,mobile,firstname';
            $this->db->where('regnumber',$member_no);
            $new_mem_reg = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','isdeleted'=>0),$select);
       
            //echo $this->db->last_query();
            //check in email table
            $select = 'member_no';
            $this->db->where('member_no',$member_no);
            $check_data = $this->master_model->getRecords('jaiib_mail_send_reversed',array('email_sent_status' => '1','sms_send' =>'1'),$select);
           
			if(count($check_data) == 0)
            {
                $insert_data = array(
                'member_no' =>$new_mem_reg[0]['regnumber'],
                'email' =>$new_mem_reg[0]['email'],
                'contact_no' =>$new_mem_reg[0]['mobile']);
               
                $this->master_model->insertRecord('jaiib_mail_send_reversed',$insert_data);
                //echo $this->db->last_query();
               
                    $final_str = 'Hello&nbsp;'.$new_mem_reg[0]['firstname'];
                    $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'Dear Candidate,';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'Greetings from IIBF!!';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'The JAIIB/DB&F/SOB (physical classroom environment) scheduled on May 2020 could not be conducted due to COVID-19 pandemic.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'The Institute proposes to conduct JAIIB/DB&F/SOB exam in the month of Dec-2020 as per below given schedule. The tentative schedule is considering COVID-19 environment.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= '
    <table style="width:100%">
    <tr>
    <th ><strong>Examination DATE</strong></th>
    <th ><strong>SUBJECTS</strong></th>
    </tr>
    <tr>
    <td>06/12/2020 Sunday</td>
    <td>Principles & Practices of Banking</td>
    </tr>
    <tr>
    <td>12/12/2020 Saturday</td>
    <td>Accounting & Finance for Bankers</td>
    </tr>
    <tr>
    <td>13/12/2020 Sunday</td>
    <td>Legal & Regulatory Aspects of Banking</td>
    </tr>
    </table>
    ';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'The Candidates who have registered for JAIIB/DBF/SOB exam scheduled in May-2020 need not register again, they are made eligible for Dec 2020 exam.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'The Centre/Venue/Batch selected by the candidate at the time of registration for May 2020 examinations may have been changed due to COVID-19 protocol/social distancing norms. Revised admit letter with new Centre/Venue/Batch is available on the website.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'Candidate should download the revised Admit letter and check all the details mentioned in it.
    In case any candidate is transferred due to work requirement he/she may change the Centre/Venue/Batch, if required, using the below link: (The above option can be exercised only once)';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= '<a href="https://iibf.esdsconnect.com/Applyjaiib/login">iibf.esdsconnect.com/Applyjaiib/login</a>';
                            $final_str.= '<br/>
    <br/>
    ';
                           
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'If the candidate changes the Centre/Venue/Batch a revised Admit letter will be generated. The revised admit letter can be downloaded by the candidate. It will also be email to the candidates registered email id as well as will be available under the candidates login profile.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'Note:';
                            $final_str.= '<br/>
    ';
                            $final_str.= '1. Change of the Centre/Venue/Batch is subject to availability of seats and is available on first-come-first-serve basis.';
                            $final_str.= '<br/>
    ';
                            $final_str.= '2.    The above link is active from 5-Nov-2020 to 8-Nov-2020.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'Candidates are required to co-operate with the examination conducting authorities for conducting the examination smoothly under COVID-19 environment.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'This email is being sent to you because you are one of the candidate who applied for JAIIB/DB&F/SOB examinations scheduled on May-2020.';
                            $final_str.= '<br/>
    <br/>
    ';
                           
                            $final_str.= 'Click on the below link to view detailed Schedule for JAIIB/DB&F/SOB/CAIIB exam in Dec-2020 (Centre based physical classroom environment following COVID-19 protocol).The tentative schedule is considering COVID-19 environment. Candidates are advised to read the Guidelines to be followed during the Examinations under COVID-19 environment provided in Annexure I.';
                            $final_str.= '<br/>
    <br/>
    ';
                           
                            $final_str.= '<a href="http://iibf.esdsconnect.com/uploads/JAIIB_CAIIB_Dec_exam_web_notice.pdf">Click here to view detailed Schedule for JAIIB/DB&F/SOB/CAIIB </a>';
                            $final_str.= '<br/>
    <br/>
    ';
                           
                            $final_str.= 'Please ignore this email in case you have already acted on the same.';
                            $final_str.= '<br/>
    <br/>
    ';
                           
                            $final_str.= '<br/>
    <br/>
    '; 
                            $final_str.= 'With best Regards';
                            $final_str.= '<br/>
    ';
                    $final_str.= 'IIBF TEAM';
               
                //'to'=>'chaitali.jadhav@esds.co.in',
                $info_arr=array(
                'to'=>$new_mem_reg[0]['email'],
                'from'=>'noreply@iibf.org.in',
                'subject'=>'IIBF: JAIIB/DB&F/SOB Examination Dec-2020',
                'message'=>$final_str);
                   
                if($this->Emailsending->mailsend_attch($info_arr,''))
                {
                        /* SMS Sending Code */
                        $sms_newstring ='Dear Candidate,
                        Warm Greetings from IIBF!
                        The JAIIB/DB&F/SOB (physical classroom environment) scheduled on May 2020 could not be conducted due to COVID-19 pandemic. The Candidates who have registered for JAIIB/DBF/SOB exam scheduled in May-2020 need not register again, they are made eligible for Dec 2020 exam. Revised admit letter with new Centre/Venue/Batch is available on the website.Candidate should download the revised Admit letter and check all the details mentioned in it. In case any candidate is transferred due to work requirement he/she may change the Centre/Venue/Batch, if required, using the below link:                                     https://iibf.esdsconnect.com/Applyjaiib/login
                       
                         With best Regards
                         IIBF Team';
                       
                     $this->send_sms_jaiib($new_mem_reg[0]['mobile'], $sms_newstring);
                   
                     $update_data = array('email_sent_status' => '1','sms_send' =>'1');
                     $this->master_model->updateRecord('jaiib_mail_send_reversed', $update_data,array('member_no'=>$new_mem_reg[0]['regnumber']));
                   
                     $update_data_admit = array('mail_send' => '2');
                     $this->master_model->updateRecord('admit_card_details_jaiib',$update_data_admit,array('mem_mem_no'=>$new_mem_reg[0]['regnumber']));   
            }   
            }
        }
    }
}

public function jaiib_mail_42()
{
    $admit_data = array();
    $select = 'mem_mem_no';
    $this->db->distinct('mem_mem_no');
    $this->db->where('exm_cd','42');
    $this->db->where('exm_prd','220');
    $this->db->where('remark','4');
    $this->db->where('mail_send','1');
	$this->db->limit(5000);
    $admit_data = $this->Master_model->getRecords('admit_card_details_jaiib','',$select);
   
    /*echo "<pre>";
    print_r($admit_data);
    echo $this->db->last_query();
    exit;*/
   
    if(!empty($admit_data))
    {   
        $insert_data = array();
        foreach($admit_data as $admit_row)
        {
            $member_no = $admit_row['mem_mem_no'];
       
            $new_mem_reg = array();
            $select = 'regnumber,email,mobile,firstname';
            $this->db->where('regnumber',$member_no);
            $new_mem_reg = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','isdeleted'=>0),$select);
       
            //echo $this->db->last_query();
            //check in email table
            $select = 'member_no';
            $this->db->where('member_no',$member_no);
            $check_data = $this->master_model->getRecords('jaiib_mail_send_reversed',array('email_sent_status' => '1','sms_send' =>'1'),$select);
           
			if(count($check_data) == 0)
            {
                $insert_data = array(
                'member_no' =>$new_mem_reg[0]['regnumber'],
                'email' =>$new_mem_reg[0]['email'],
                'contact_no' =>$new_mem_reg[0]['mobile']);
               
                $this->master_model->insertRecord('jaiib_mail_send_reversed',$insert_data);
                //echo $this->db->last_query();
               
                    $final_str = 'Hello&nbsp;'.$new_mem_reg[0]['firstname'];
                    $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'Dear Candidate,';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'Greetings from IIBF!!';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'The JAIIB/DB&F/SOB (physical classroom environment) scheduled on May 2020 could not be conducted due to COVID-19 pandemic.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'The Institute proposes to conduct JAIIB/DB&F/SOB exam in the month of Dec-2020 as per below given schedule. The tentative schedule is considering COVID-19 environment.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= '
    <table style="width:100%">
    <tr>
    <th ><strong>Examination DATE</strong></th>
    <th ><strong>SUBJECTS</strong></th>
    </tr>
    <tr>
    <td>06/12/2020 Sunday</td>
    <td>Principles & Practices of Banking</td>
    </tr>
    <tr>
    <td>12/12/2020 Saturday</td>
    <td>Accounting & Finance for Bankers</td>
    </tr>
    <tr>
    <td>13/12/2020 Sunday</td>
    <td>Legal & Regulatory Aspects of Banking</td>
    </tr>
    </table>
    ';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'The Candidates who have registered for JAIIB/DBF/SOB exam scheduled in May-2020 need not register again, they are made eligible for Dec 2020 exam.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'The Centre/Venue/Batch selected by the candidate at the time of registration for May 2020 examinations may have been changed due to COVID-19 protocol/social distancing norms. Revised admit letter with new Centre/Venue/Batch is available on the website.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'Candidate should download the revised Admit letter and check all the details mentioned in it.
    In case any candidate is transferred due to work requirement he/she may change the Centre/Venue/Batch, if required, using the below link: (The above option can be exercised only once)';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= '<a href="https://iibf.esdsconnect.com/Applyjaiib/login">iibf.esdsconnect.com/Applyjaiib/login</a>';
                            $final_str.= '<br/>
    <br/>
    ';
                           
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'If the candidate changes the Centre/Venue/Batch a revised Admit letter will be generated. The revised admit letter can be downloaded by the candidate. It will also be email to the candidates registered email id as well as will be available under the candidates login profile.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'Note:';
                            $final_str.= '<br/>
    ';
                            $final_str.= '1. Change of the Centre/Venue/Batch is subject to availability of seats and is available on first-come-first-serve basis.';
                            $final_str.= '<br/>
    ';
                            $final_str.= '2.    The above link is active from 5-Nov-2020 to 8-Nov-2020.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'Candidates are required to co-operate with the examination conducting authorities for conducting the examination smoothly under COVID-19 environment.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'This email is being sent to you because you are one of the candidate who applied for JAIIB/DB&F/SOB examinations scheduled on May-2020.';
                            $final_str.= '<br/>
    <br/>
    ';
                           
                            $final_str.= 'Click on the below link to view detailed Schedule for JAIIB/DB&F/SOB/CAIIB exam in Dec-2020 (Centre based physical classroom environment following COVID-19 protocol).The tentative schedule is considering COVID-19 environment. Candidates are advised to read the Guidelines to be followed during the Examinations under COVID-19 environment provided in Annexure I.';
                            $final_str.= '<br/>
    <br/>
    ';
                           
                            $final_str.= '<a href="http://iibf.esdsconnect.com/uploads/JAIIB_CAIIB_Dec_exam_web_notice.pdf">Click here to view detailed Schedule for JAIIB/DB&F/SOB/CAIIB </a>';
                            $final_str.= '<br/>
    <br/>
    ';
                           
                            $final_str.= 'Please ignore this email in case you have already acted on the same.';
                            $final_str.= '<br/>
    <br/>
    ';
                           
                            $final_str.= '<br/>
    <br/>
    '; 
                            $final_str.= 'With best Regards';
                            $final_str.= '<br/>
    ';
                    $final_str.= 'IIBF TEAM';
               
                //'to'=>'chaitali.jadhav@esds.co.in', //'to'=>$new_mem_reg[0]['email'],
                $info_arr=array(
               'to'=>$new_mem_reg[0]['email'],
                'from'=>'noreply@iibf.org.in',
                'subject'=>'IIBF: JAIIB/DB&F/SOB Examination Dec-2020',
                'message'=>$final_str);
                   
                if($this->Emailsending->mailsend_attch($info_arr,''))
                {
                        /* SMS Sending Code */
                        $sms_newstring ='Dear Candidate,
                        Warm Greetings from IIBF!
                        The JAIIB/DB&F/SOB (physical classroom environment) scheduled on May 2020 could not be conducted due to COVID-19 pandemic. The Candidates who have registered for JAIIB/DBF/SOB exam scheduled in May-2020 need not register again, they are made eligible for Dec 2020 exam. Revised admit letter with new Centre/Venue/Batch is available on the website.Candidate should download the revised Admit letter and check all the details mentioned in it. In case any candidate is transferred due to work requirement he/she may change the Centre/Venue/Batch, if required, using the below link:                                     https://iibf.esdsconnect.com/Applyjaiib/login
                       
                         With best Regards
                         IIBF Team';
                       
                     $this->send_sms_jaiib($new_mem_reg[0]['mobile'], $sms_newstring); //
                   
                     $update_data = array('email_sent_status' => '1','sms_send' =>'1');
                     $this->master_model->updateRecord('jaiib_mail_send_reversed', $update_data,array('member_no'=>$new_mem_reg[0]['regnumber']));
                   
                     $update_data_admit = array('mail_send' => '2');
                     $this->master_model->updateRecord('admit_card_details_jaiib',$update_data_admit,array('mem_mem_no'=>$new_mem_reg[0]['regnumber']));   
            }   
            }
        }
    }
}


public function jaiib_mail_992()
{
    $admit_data = array();
    $select = 'mem_mem_no';
    $this->db->distinct('mem_mem_no');
    $this->db->where('exm_cd',$this->config->item('examCodeSOB'));
    $this->db->where('exm_prd','220');
    $this->db->where('remark','4');
    $this->db->where('mail_send','1');
	$this->db->limit(5000);
    $admit_data = $this->Master_model->getRecords('admit_card_details_jaiib','',$select);
   
    /*echo "<pre>";
    print_r($admit_data);
    echo $this->db->last_query();
    exit;*/
   
    if(!empty($admit_data))
    {   
        $insert_data = array();
        foreach($admit_data as $admit_row)
        {
            $member_no = $admit_row['mem_mem_no'];
       
            $new_mem_reg = array();
            $select = 'regnumber,email,mobile,firstname';
            $this->db->where('regnumber',$member_no);
            $new_mem_reg = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','isdeleted'=>0),$select);
       
            //echo $this->db->last_query();
            //check in email table
            $select = 'member_no';
            $this->db->where('member_no',$member_no);
            $check_data = $this->master_model->getRecords('jaiib_mail_send_reversed',array('email_sent_status' => '1','sms_send' =>'1'),$select);
           
			if(count($check_data) == 0)
            {
                $insert_data = array(
                'member_no' =>$new_mem_reg[0]['regnumber'],
                'email' =>$new_mem_reg[0]['email'],
                'contact_no' =>$new_mem_reg[0]['mobile']);
               
                $this->master_model->insertRecord('jaiib_mail_send_reversed',$insert_data);
                //echo $this->db->last_query();
               
                    $final_str = 'Hello&nbsp;'.$new_mem_reg[0]['firstname'];
                    $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'Dear Candidate,';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'Greetings from IIBF!!';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'The JAIIB/DB&F/SOB (physical classroom environment) scheduled on May 2020 could not be conducted due to COVID-19 pandemic.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'The Institute proposes to conduct JAIIB/DB&F/SOB exam in the month of Dec-2020 as per below given schedule. The tentative schedule is considering COVID-19 environment.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= '
    <table style="width:100%">
    <tr>
    <th ><strong>Examination DATE</strong></th>
    <th ><strong>SUBJECTS</strong></th>
    </tr>
    <tr>
    <td>06/12/2020 Sunday</td>
    <td>Principles & Practices of Banking</td>
    </tr>
    <tr>
    <td>12/12/2020 Saturday</td>
    <td>Accounting & Finance for Bankers</td>
    </tr>
    <tr>
    <td>13/12/2020 Sunday</td>
    <td>Legal & Regulatory Aspects of Banking</td>
    </tr>
    </table>
    ';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'The Candidates who have registered for JAIIB/DBF/SOB exam scheduled in May-2020 need not register again, they are made eligible for Dec 2020 exam.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'The Centre/Venue/Batch selected by the candidate at the time of registration for May 2020 examinations may have been changed due to COVID-19 protocol/social distancing norms. Revised admit letter with new Centre/Venue/Batch is available on the website.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'Candidate should download the revised Admit letter and check all the details mentioned in it.
    In case any candidate is transferred due to work requirement he/she may change the Centre/Venue/Batch, if required, using the below link: (The above option can be exercised only once)';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= '<a href="https://iibf.esdsconnect.com/Applyjaiib/login">iibf.esdsconnect.com/Applyjaiib/login</a>';
                            $final_str.= '<br/>
    <br/>
    ';
                           
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'If the candidate changes the Centre/Venue/Batch a revised Admit letter will be generated. The revised admit letter can be downloaded by the candidate. It will also be email to the candidates registered email id as well as will be available under the candidates login profile.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'Note:';
                            $final_str.= '<br/>
    ';
                            $final_str.= '1. Change of the Centre/Venue/Batch is subject to availability of seats and is available on first-come-first-serve basis.';
                            $final_str.= '<br/>
    ';
                            $final_str.= '2.    The above link is active from 5-Nov-2020 to 8-Nov-2020.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'Candidates are required to co-operate with the examination conducting authorities for conducting the examination smoothly under COVID-19 environment.';
                            $final_str.= '<br/>
    <br/>
    ';
                            $final_str.= 'This email is being sent to you because you are one of the candidate who applied for JAIIB/DB&F/SOB examinations scheduled on May-2020.';
                            $final_str.= '<br/>
    <br/>
    ';
                           
                            $final_str.= 'Click on the below link to view detailed Schedule for JAIIB/DB&F/SOB/CAIIB exam in Dec-2020 (Centre based physical classroom environment following COVID-19 protocol).The tentative schedule is considering COVID-19 environment. Candidates are advised to read the Guidelines to be followed during the Examinations under COVID-19 environment provided in Annexure I.';
                            $final_str.= '<br/>
    <br/>
    ';
                           
                            $final_str.= '<a href="http://iibf.esdsconnect.com/uploads/JAIIB_CAIIB_Dec_exam_web_notice.pdf">Click here to view detailed Schedule for JAIIB/DB&F/SOB/CAIIB </a>';
                            $final_str.= '<br/>
    <br/>
    ';
                           
                            $final_str.= 'Please ignore this email in case you have already acted on the same.';
                            $final_str.= '<br/>
    <br/>
    ';
                           
                            $final_str.= '<br/>
    <br/>
    '; 
                            $final_str.= 'With best Regards';
                            $final_str.= '<br/>
    ';
                    $final_str.= 'IIBF TEAM';
               
                //'to'=>'chaitali.jadhav@esds.co.in',
                $info_arr=array(
                'to'=>$new_mem_reg[0]['email'],
                'from'=>'noreply@iibf.org.in',
                'subject'=>'IIBF: JAIIB/DB&F/SOB Examination Dec-2020',
                'message'=>$final_str);
                   
                if($this->Emailsending->mailsend_attch($info_arr,''))
                {
                        /* SMS Sending Code */
                        $sms_newstring ='Dear Candidate,
                        Warm Greetings from IIBF!
                        The JAIIB/DB&F/SOB (physical classroom environment) scheduled on May 2020 could not be conducted due to COVID-19 pandemic. The Candidates who have registered for JAIIB/DBF/SOB exam scheduled in May-2020 need not register again, they are made eligible for Dec 2020 exam. Revised admit letter with new Centre/Venue/Batch is available on the website.Candidate should download the revised Admit letter and check all the details mentioned in it. In case any candidate is transferred due to work requirement he/she may change the Centre/Venue/Batch, if required, using the below link:                                     https://iibf.esdsconnect.com/Applyjaiib/login
                       
                         With best Regards
                         IIBF Team';
                       
                     $this->send_sms_jaiib($new_mem_reg[0]['mobile'], $sms_newstring);
                   
                     $update_data = array('email_sent_status' => '1','sms_send' =>'1');
                     $this->master_model->updateRecord('jaiib_mail_send_reversed', $update_data,array('member_no'=>$new_mem_reg[0]['regnumber']));
                   
                     $update_data_admit = array('mail_send' => '2');
                     $this->master_model->updateRecord('admit_card_details_jaiib',$update_data_admit,array('mem_mem_no'=>$new_mem_reg[0]['regnumber']));   
            }   
            }
        }
    }
}



public function send_sms_jaiib($mobile=NULL,$text=NULL)
{

	if($mobile!=NULL && $text!=NULL)
	{
	
		$url ="http://www.hindit.co.in/API/pushsms.aspx?loginID=T1IIBF&password=supp0rt123&mobile=".$mobile."&text=".urlencode($text)."&senderid=IIBFNM&route_id=2&Unicode=0";
		
		$string = preg_replace('/\s+/', '', $url);
		
		$x = curl_init($string);
		
		curl_setopt($x, CURLOPT_HEADER, 0);	
		
		curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
		
		curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);			
		
		$reply = curl_exec($x);
		curl_close($x);
		$res = $this->sms_balance_notify_jaiib($reply);
	}

}
public function sms_balance_notify_jaiib($html)
{
	$this->load->library('email');
	
	$sms_balance = 0;

	//$html = file_get_contents('sms_api_reply.php'); //get the html returned from the following url
	
	$dom = new DOMDocument();
	
	libxml_use_internal_errors(TRUE); // disable libxml errors
	
	if(!empty($html)){ // if any html is actually returned
	
		$dom->loadHTML($html);
		
		libxml_clear_errors(); // remove errors for yucky html
		
		$dom_xpath = new DOMXPath($dom);
	
		// get all the h2's with an id
		$dom_row = $dom_xpath->query('//span[@id="Label6"]');
	
		if($dom_row->length > 0){
			foreach($dom_row as $row){
				$sms_balance_str = $row->nodeValue;
				//echo $sms_balance_str;
			}
			
			$sms_balance = (int) trim(str_replace("Your current balance is : ", "", $sms_balance_str));
			
			// check current sms balance
			if($sms_balance == 1000 || $sms_balance == 500 || $sms_balance == 300 || $sms_balance == 100)
			{
				// send email notification
				$from_name = 'IIBF';
				$from_email = 'noreply@iibf.org.in';
				$subject = 'SMS Balance Alert';
				
				// email receipient list -
				//$recipient_list = array('bhagwan.sahane@esds.co.in', 'shruti.samdani@esds.co.in', 'prafull.tupe@esds.co.in');
				
				$recipient_list = array('iibfdevp@esds.co.in');
				
				$message = 'Your current balance is : ' . $sms_balance;
				
				$config = array('mailtype' => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
				
				$this->email->initialize($config);
				$this->email->from($from_email, $from_name);
				$this->email->to($recipient_list);
				$this->email->subject($subject);
				$this->email->message($message);
				if($this->email->send())
				{
					//echo 'Email Sent.';
					
					//$this->email->print_debugger();
					//echo $this->email->print_debugger();
					
					return true;
				}
				else
				{
					//echo 'Email Not Sent.';
					
					return false;	
				}
			}
		}
	}
}

public function member_csv()
{
	$member_data = $this->Master_model->getRecords('jaiib_mail_send',array('email_sent_status'=>'1','sms_send'=>'1'));
	if(!empty($member_data))
	{
		 $csv = 			                                  "Member_No,Email,Mobile,Email_Sent_Status,Sms_Send\n";
		 $query = $this->db->query("SELECT `member_no`,`email`,`contact_no`,`email_sent_status`,`sms_send` FROM `jaiib_mail_send`");
		 $result = $query->result_array();
		 //print_r($result); die;
		 foreach($result as $record)
		 {
			 $csv.= $record['member_no'].','.$record['email'].','.$record['contact_no'].','.$record['email_sent_status'].','.$record['sms_send']."\n";
			 
		 }
		$filename = date("Ymd")."jaiib_mail_data.csv";
		$path = "uploads/jaiib_mailsend_member/".$filename ."";
		$csv_handler = fopen($path, 'w');
		fwrite ($csv_handler,$csv);
		fclose ($csv_handler);
		
		$final_str = 'Hello Roopal, <br/><br/>';
		$final_str.= 'Please find attached data of Members.';   

		$final_str.= '<br/><br/>';
		$final_str.= 'Thanks & Regards,';
		$final_str.= '<br/>';
		$final_str.= 'IIBF TEAM'; 
		$attachpath = $path;
		
		$info_arr=array('to'=>'chaitali.jadhav@esds.co.in',
						'cc'=>'chaitali.jadhav@esds.co.in',
						'from'=>'noreply@iibf.org.in',
						'subject'=>'IIBF: JAIIB Member Data',
						'message'=>$final_str
					); 
				
		$files=array($attachpath);
		$this->Emailsending->mailsend_attch_cpdsheet($info_arr,$files);
	}
}
}