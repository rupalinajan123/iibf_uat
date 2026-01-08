<?php
/*
 	* Controller Name	:	Cpdcron
 	* Created By		:	Chaitali
 	* Created Date		:	18-06-2020
*/
//https://iibf.esdsconnect.com/admin/Cpdcron/cpd_data

defined('BASEPATH') OR exit('No direct script access allowed');

class Jaiib_mail_send extends CI_Controller {
			
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


public function jaiib_data()
{
    $admit_data = array();
    $select = 'mem_mem_no';
    $this->db->distinct('mem_mem_no');
   // $this->db->where('exm_cd','21');
    $this->db->where('exm_prd','220');
    $this->db->where('remark','4');
	$this->db->where('mem_type !=','DB');
    $this->db->where('mail_send','0');
	$this->db->limit(4);
    $admit_data = $this->Master_model->getRecords('admit_card_details_jaiib','',$select);
   
   /* echo "<pre>";
    print_r($admit_data);
    echo $this->db->last_query();
    exit;
   */
    if(!empty($admit_data))
    {   
       // $insert_data = array();
        foreach($admit_data as $admit_row)
        {
		 $member_no = $admit_row['mem_mem_no'];
       
		 $select = 'member_no';
          $this->db->where('member_no',$member_no);
           $check_data = $this->master_model->getRecords('jaiib_sms_send','',$select);
         if(count($check_data) == 0)
		 {
           
            $new_mem_reg = array();
            $select = 'regnumber,email,mobile';
            $this->db->where('regnumber',$member_no);
            $new_mem_reg = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','isdeleted'=>0),$select);
            //check in email table
            $insert_data = array(
                'member_no' =>$new_mem_reg[0]['regnumber'],
                'email' =>$new_mem_reg[0]['email'],
                'contact_no' =>$new_mem_reg[0]['mobile']);
               
               if($this->master_model->insertRecord('jaiib_sms_send',$insert_data))
			   {
				 $update_data_admit = array('mail_send' => '1');
                 $this->master_model->updateRecord('admit_card_details_jaiib',$update_data_admit,array('mem_mem_no'=>$new_mem_reg[0]['regnumber']));
			   }
            }  
        }
    }
}

public function jaiib_sms()
{
	$sms_data = array();
	$this->db->distinct('member_no');  
	$this->db->where('sms_send','0');
	$this->db->limit(500);
    $sms_data = $this->Master_model->getRecords('jaiib_sms_send','');
	if(!empty($sms_data))
	{
		foreach($sms_data as $res)
		{ 
			$mobile = $res['contact_no']; 
				/* SMS Sending Code */
				$sms_newstring ='Dear Candidate,The JAIIB/DB&F/SOB (physical classroom environment) scheduled for May 2020 could not be conducted due to COVID-19 pandemic. The Candidates who have registered for JAIIB/DBF/SOB exam scheduled in May-2020 need not register again, they are made eligible for Dec 2020 exam. Revised admit letter with new Centre/Venue/Batch is available on the website. In case any candidate is transferred due to work requirement he/she may change the Centre/Venue/Batch, if required, using the below link: https://iibf.esdsconnect.com/Applyjaiib/login The active date for the above link is extended up to 12-Nov-2020. With best RegardsIIBF Team';
			   
			 $this->send_sms_jaiib($mobile, $sms_newstring);
		   
			 $update_data = array('sms_send' =>'1');
			 $this->master_model->updateRecord('jaiib_sms_send', $update_data,array('member_no'=>$res['member_no']));
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
		//print_r($reply); die;
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
		 $query = $this->db->query("SELECT `DISTINCT(member_no)`,`email`,`contact_no`,`email_sent_status`,`sms_send` FROM `jaiib_mail_send`");
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