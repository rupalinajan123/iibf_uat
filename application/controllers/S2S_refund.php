<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class S2S_refund extends CI_Controller {
			
	public function __construct()
	{
			parent::__construct();
			
			$this->load->model('UserModel');
			$this->load->model('Master_model');
			$this->load->library('email');
			$this->load->model('Emailsending');
			$this->load->helper('custom_admitcard_helper');
		
	}
	public function index()
	{	
    	$this->db->limit(10);	
		$this->db->where('DATE(created_on) >', '2021-06-10');
		$get_receipt = $this->master_model->getRecords('cron_auto_refund_log',array('refund'=>1,'email_flag'=>0,'sms_flag'=>0),'receipt_no');		
		//echo $this->db->last_query(); die;
		if(!empty($get_receipt))
		{	
			foreach($get_receipt as $row)
			{
				//$id = $row['id'];
				$receipt_no = $row['receipt_no'];
				$transaction_no = $row['transaction_no'];
				$this->db->limit(1);
					$get_invoice = $this->master_model->getRecords('exam_invoice',array('receipt_no'=>$receipt_no),'transaction_no,invoice_no,invoice_image');
					
				if($get_invoice[0]['invoice_no'] == '' || $get_invoice[0]['invoice_image'] == '' || $get_invoice[0]['transaction_no'] == '')
				{
						## Get payment details
						$this->db->limit(1);
						$get_details = $this->master_model->getRecords('payment_transaction',array('receipt_no'=>$receipt_no,'gateway'=>'sbiepay'),'ref_id,member_regnumber,receipt_no,amount,pay_type,status,gateway,date,transaction_no');
						if(!empty($get_details))
						{	
							foreach($get_details as $rowdata)
							{
								 $receipt_no = $rowdata['receipt_no'];
								$pay_type = $rowdata['pay_type'];
								$reg_id=$rowdata[0]['ref_id'];
								$member_no=$rowdata['member_regnumber'];
								if($pay_type == '1')
								{
								## Get Member details to send mail and sms
								$this->db->limit(1);
								$user_info = $this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'email,mobile');
								
								$email = $user_info[0]['email'];	
								$mobile = $user_info[0]['mobile'];	
								}
								else{
									
									## Get Member details to send mail and sms
									$this->db->limit(1);
									$user_info = $this->master_model->getRecords('member_registration',array('regnumber'=>$member_no),'email,mobile');
									 $email = $user_info[0]['email'];	
									 $mobile = $user_info[0]['mobile'];
								}
								
										## Send mail
										$mail_sent = $this->sendMail();
										
										## Send sms
										$sms_sent = $this->sendSms();
										
										##update the cron_auto_refund_log table after sending mail and sms
										$update_data = array('email_flag' => '1','sms_flag'=>'1');
										$this->master_model->updateRecord('cron_auto_refund_log', $update_data,array('receipt_no'=>$receipt_no));
							}
						}//if
					}
				
			}//foreach
		}//if	
			
	}
	public function sendMail($transaction_no,$email)
	{
		$final_str.= 'Dear Candidate,';
		$final_str.= '<br/><br/>';
		$final_str.= 'If your Amount is deducted , that will soon get refunded and you will receive the amount within 10 days
Inconvenience caused is regretted.';
		$final_str.= '<br/><br/>';
		$final_str.= 'Regards';
		$final_str.= '<br/>';
		$final_str.= 'IIBF TEAM';
		$info_arr=array(
				'to'=>$email,
		'from'=>'noreply@iibf.org.in',
		'subject'=>'IIBF: Refund - Transaction no.' .$transaction_no,
		'message'=>$final_str); 
		if($this->Emailsending->mailsend_attch($info_arr,''))
			return 1;
		else
			return 0;
	}
	public function sendSms()
	{
		$sms_newstring ='Dear Candidate,
If your Amount is deducted , that will soon get refunded and you will receive the amount within 10 days.
Inconvenience caused is regretted.

Regards,
IIBF Team';
	if($this->send_sms_jaiib($mobile, $sms_newstring))
		return 1;
	else
		return 0;


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

}	
?>
