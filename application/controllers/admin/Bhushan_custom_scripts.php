<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	header("Access-Control-Allow-Origin: *");
	class Bhushan_custom_scripts extends CI_Controller
	{    
		public function __construct()
		{ 
			parent::__construct();
			$this->load->library('upload');
			$this->load->helper('upload_helper');
			$this->load->helper('general_helper');
			$this->load->model('Master_model');
			$this->load->library('email');
			$this->load->helper('date');
			$this->load->library('email');
			$this->load->model('Emailsending');
			$this->load->model('log_model'); 
		}
		
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Bhushan_custom_scripts refund_status_mail
		// https://iibf.esdsconnect.com/admin/Bhushan_custom_scripts/refund_status_mail
		public function refund_status_mail()
		{	
		
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			
			$select = 'c.namesub,c.firstname,c.middlename,c.lastname,c.email,a.member_regnumber,a.receipt_no,a.trn_no,a.amount,a.trn_date';
			$this->db->join('member_registration c', 'a.member_regnumber = c.regnumber', 'LEFT');
            $data = $this->Master_model->getRecords('payment_refund_mail a', array(
                'c.isactive' => '1',
                'c.isdeleted' => 0,
                'a.email_status' => 0,
				'a.refund_status' => 0,
				//'DATE(a.create_date)' => $yesterday
            ), $select);
			

      		//echo "<br> Qry : ".$this->db->last_query();
			///exit;
	  		
	  
			if(!empty($data) && count($data) > 0)
			{
				foreach($data as $row)
				{
					$member_name = $to_email = $from_email = $subject = $exam_date = $exam_name = '';
					
					if($row['firstname'] != '') { $member_name .= $row['firstname']; }
					if($row['middlename'] != '') { $member_name .= " ".$row['middlename']; }
					if($row['lastname'] != '') { $member_name .= " ".$row['lastname']; }
				
					$member_regnumber = $row['member_regnumber'];
					
					$receipt_no = $row['receipt_no'];
					$trn_no = $row['trn_no'];
					$amount = $row['amount'];
					$trn_date = $row['trn_date'];
				
				$to_email = $row['email']; 
					//$to_email = 'bhushan.amrutkar@esds.co.in';
					
					$subject = "Refund Status";
					$mail_body = '<html>
					<head>
					<title>Scribe certificates for exams</title>
					</head>
					<body style="max-width:600px;border:1px solid #ccc;margin:10px auto;font-size:16px;line-height:18px;">
					<div style="text-align: center;padding: 15px 10px;border-bottom: 1px solid #ccc;line-height:22px;">INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>(AN ISO 21001:2018 Certified )</div>
					<div style="padding:20px;">
					<p style="margin:0 0 20px 0;">Dear '.$member_name.',</p>
					<p style="margin:0 0 10px 0;line:height:22px;">
					
 
This is to inform you that ,your amount has been deducted at SBI end but application could not process. We will be initiating refund in next 3 days of amount deducted at your end.
Kindly note that amount will be credited into your respective account in 10-15 working days.
 
					Transaction Details: 
					<p>Transaction No.:  '.$trn_no.'</P>
					<p>Order I.:  '.$receipt_no.'</P>
					<p>Amount:  '.$amount.'</P>
					<p>Transaction Date:  '.$trn_date.'</P>
					</p>
					<p style="margin:18px 0 0 0;line-height: 20px;">Yours Truly,<br>IIBF Team.</p>
					</div>                            
					</body>
					</html>';
					
					$email_arr = array( 'to'=>$to_email, 'from'=>'noreply@iibf.org.in', 'subject'=>$subject, 'message'=>$mail_body);
					if($this->Emailsending->mailsend($email_arr))
					{
						$update_data = array(
                            'email_status' => 1,
                            'email_sent_date' => date("Y-m-d H:i:s")
                        );
						$this->master_model->updateRecord('payment_refund_mail', $update_data, array('member_regnumber ' => $member_regnumber
                        ));
                        	//echo "<br> Qry : ".$this->db->last_query();
					}
					else 
					{ 
						//echo "fail";	 
					}
				}
			}
		}	
		
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php Bhushan_custom_scripts scribe_member_mail
		public function scribe_member_mail()
		{	
			
			$current_date = date("Y-m-d h:i:s");
			$date = date('Y-m-d h:i:s', strtotime("-15 minutes", strtotime ($current_date)));

			$select = 'c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,m.description,c.email,a.exam_code,ac.exam_date';
			$this->db->where('a.exam_period','777');
			$this->db->where('a.created_on >=', $date);
			$this->db->where('a.created_on <=', $current_date);
			
			//$this->db->where('a.created_on >=', '2020-08-07 21:18:00');
			//$this->db->where('a.created_on <=', '2020-08-10 11:11:00');
			
			$this->db->join('exam_master m', 'a.exam_code = m.exam_code', 'LEFT');
			$this->db->join('member_registration c', 'a.regnumber = c.regnumber', 'LEFT');
			$this->db->join('admit_card_details ac', 'ac.mem_exam_id = a.id', 'LEFT');
            $data = $this->Master_model->getRecords('member_exam a', array(
                'c.isactive' => '1',
                'c.isdeleted' => 0,
                'a.pay_status' => 1,
                'ac.remark' => 1,
				'a.scribe_flag' => 'Y',
            ), $select);
			
      		//echo "<br> Qry : ".$this->db->last_query();
			///exit;
	  		
	  
			if(!empty($data) && count($data) > 0)
			{
				foreach($data as $row)
				{
					$member_name = $to_email = $from_email = $subject = $exam_date = $exam_name = '';
					
					if($row['firstname'] != '') { $member_name .= $row['firstname']; }
					if($row['middlename'] != '') { $member_name .= " ".$row['middlename']; }
					if($row['lastname'] != '') { $member_name .= " ".$row['lastname']; }
					$exam_date = $row['exam_date'];
					$exam_name = $row['description'];
					$to_email = $row['email']; 
					//$to_email = 'bhushan.amrutkar@esds.co.in';
					
					$subject = "IIBF : Scribe Certificates";
					$mail_body = '<html>
					<head>
					<title>Scribe certificates for exams</title>
					</head>
					<body style="max-width:600px;border:1px solid #ccc;margin:10px auto;font-size:16px;line-height:18px;">
					<div style="text-align: center;padding: 15px 10px;border-bottom: 1px solid #ccc;line-height:22px;">INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>(AN ISO 21001:2018 Certified )</div>
					<div style="padding:20px;">
					<p style="margin:0 0 20px 0;">Dear '.$member_name.',</p>
					<p style="margin:0 0 10px 0;line:height:22px;">You have opted for services of scribe for the '.$exam_name.' examination scheduled on '.$exam_date.' under Remote Proctored mode<br>
 
<br>For the purpose of approving the scribe and to give you extra time as per rules, you are requested to email Admit letter, Details of the scribe, Declaration and Relevant Doctors Certificates to suhas@iibf.org.in / amit@iibf.org.in at least one week before the exam date.<br>
 
<br>Your application for scribe will be scrutinized and an email will be sent 1-2 days before the exam date, mentioning the status of acceptance of scribe.<br>
 
<br>You will be required to produce the print out of permission granted, required documents along with the Admit Letter to the test conducting authority (procter).<br>
 
<br>Click Here - <a href="http://www.iibf.org.in/documents/Scribe_Guideliness_R-150219.pdf" target="_blank">GENERAL GUIDELINES/RULES FOR USING SCRIBE BY VISUALLY IMPAIRED & ORTHOPEADICALLY CHALLENGED CANDIDATES</a>

 </p>
					<p style="margin:18px 0 0 0;line-height: 20px;">Yours Truly,<br>IIBF Team.</p>
					</div>                            
					</body>
					</html>';
					
					$email_arr = array( 'to'=>$to_email, 'from'=>'noreply@iibf.org.in', 'subject'=>$subject, 'message'=>$mail_body);
					if($this->Emailsending->mailsend($email_arr))
					{
						//echo "Mail send to => ".$to_email;
						$insert_info = array(
						'to_email' => $to_email,
						'createdon' => date('Y-m-d H:i:s'));
						$this->master_model->insertRecord('scribe_emails', $insert_info, true);
					}
					else 
					{ 
						//echo "fail";	 
					}
				}
			}
		}	
		
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php Bhushan_custom_scripts dv_payment_refund_mail
		public function dv_payment_refund_mail()
		{	
			$current_date = date("Y-m-d");
			
			//$current_date = '2020-09-09';
			
			$this->db->where('refund_date', $current_date);
            $data = $this->Master_model->getRecords('dv_payment_refunds');
			
      		//echo "<br> Qry : ".$this->db->last_query();
			//exit;
	  		
			if(!empty($data) && count($data) > 0)
			{
			
				$to_email = 'iibfdevp@esds.co.in'; 
				//$to_email = 'bhushan.amrutkar@esds.co.in';
				
				$subject = "DV Payment Refunds Details";
				
				$mail_body = '<html>
				<head>
				<title>DV Payment Refunds Details</title>
				</head>
				<body style="max-width:600px;border:1px solid #ccc;margin:10px auto;font-size:16px;line-height:18px;border: 1px solid black;">
				<div style="
	text-align: center;
	padding: 15px 10px;
	border-bottom: 1px solid #ccc;
	line-height: 22px;
	font-weight: bold;
	color: blue;
	background-color: gold;
">INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>(AN ISO 21001:2018 Certified )</div>
				<div style="padding:20px;">
				<p style="margin:0 0 20px 0;">Dear Team,</p>
				<p style="margin:0 0 10px 0;line:height:22px;">
				
				<p>Following trns found in the DV payment refund table, We have refunded this trns.</p>
				
				<span style="font-weight: bold;">Order Id&nbsp;&nbsp;&nbsp;|&nbsp;Transaction No&nbsp;|&nbsp;&nbsp;Refund Date<br></span>
				';
				
				$str = '';
				foreach($data as $row)
				{
					
					
					$str .= '<br>'.$row["order_id"].' | '.$row["trn_no"].' | '.$row["refund_date"].'<br>';
					
					
					
					
				}
				
						$mail_body .= $str;
				
					$mail_body .= '</p>
					
					<p style="margin:18px 0 0 0;line-height: 20px;font-weight: bold;">Thanks and Regards, <br> Bhushan Amrutkar.</p>
					</div>                            
					</body>
					</html>';
				
				
				//echo $mail_body;
				$email_arr = array( 'to'=>$to_email, 'from'=>'noreply@iibf.org.in', 'subject'=>$subject, 'message'=>$mail_body);
				$this->Emailsending->mailsend($email_arr);
				
			}
	  
		}
		
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php Bhushan_custom_scripts sbi_refund_csv
		public function sbi_refund_csv()
		{
			ini_set("memory_limit", "-1");
			$dir_flg        = 0;
			$parent_dir_flg = 0;
			$exam_file_flg  = 0;
			$success        = array();
			$error          = array();
			$start_time     = date("Y-m-d H:i:s");
			$current_date   = date("Ymd");
			$cron_file_dir  = "./uploads/DV_Refund_SBI/";
			$result         = array(
				"success" => "",
				"error" => "",
				"Start Time" => $start_time,
				"End Time" => ""
			);
			$desc           = json_encode($result);
			$this->log_model->cronlog("Refunded List CSV Cron Execution Start", $desc);
			if (!file_exists($cron_file_dir . $current_date)) {
				$parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
			}
			if (file_exists($cron_file_dir . $current_date)) {
				$cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
				$file           = "Refunded_list_" . $current_date . ".csv";
				$fp             = fopen($cron_file_path . '/' . $file, 'w');
				$file1          = "logs_" . $current_date . ".txt";
				$fp1            = fopen($cron_file_path . '/' . $file1, 'a');
				fwrite($fp1, "\n******** Refunded List CSV Cron Execution Started - " . $start_time . " ******** \n");
				
				$select='p.gateway,p.amount,p.date,p.refund_request_id,p.transaction_no,p.receipt_no,p.ARRN,p.refund_details';
				$this->db->where('m.credit_note_number', '');
				$this->db->join('payment_refund p', 'm.transaction_no = p.transaction_no', 'LEFT');
				$can_exam_data = $this->Master_model->getRecords('maker_checker m', array('m.req_status' => 5,'p.status'=>1), $select);
				if (count($can_exam_data)) 
				{
					$i             = 1;
					$exam_cnt      = 0;
					
					// Column headers for CSV  
					$data1="Gateway,Amount,Refund_initiate_date,Refund_request_id,Transaction_no,Receipt_no,ARRN,Refund_details \n";
					$exam_file_flg = fwrite($fp, $data1);
					
					foreach ($can_exam_data as $exam) 
					{
						$data = $Gateway = $Amount = $Refund_initiate_date = $Refund_request_id = $Transaction_no = $Receipt_no = $ARRN = $Refund_details = '';
						
						$Gateway = $exam['gateway'];
						$Amount = $exam['amount'];
						$Refund_initiate_date = $exam['date'];
						$Refund_request_id = $exam['refund_request_id'];
						$Transaction_no = $exam['transaction_no'];
						$Receipt_no = $exam['receipt_no'];
						$ARRN = $exam['ARRN'];
						$Refund_details = $exam['refund_details'];
						
						$data .= ''.$Gateway.','.$Amount.','.$Refund_initiate_date.','.$Refund_request_id.','.$Transaction_no.','.$Receipt_no.','.$ARRN.','.$Refund_details."\n";
						
						$exam_file_flg = fwrite($fp, $data);
						if ($exam_file_flg)
							$success['cand_exam'] = "Refunded List CSV File Generated Successfully.";
						else
							$error['cand_exam'] = "Error While Generating MPS CSV File.";
						$i++;
						$exam_cnt++;
					}
					fwrite($fp1, "Total Refunded - " . $exam_cnt . "\n");
					
					$OldName = '';
					$newPath = $cron_file_dir . $current_date . "/Refunded_list_" . $current_date . ".csv";
					$NewName     = "Refunded_list_" . $current_date . ".csv";
					
					$insert_info = array(
						'CurrentDate' => $current_date,
						'old_file_name' => $OldName,
						'new_file_name' => $NewName,
						'record_count' => $exam_cnt,
						'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_refunded_list_csv', $insert_info, true);
					
					$this->send_mail_sbi();
				} 
				
				fclose($fp);
				$end_time = date("Y-m-d H:i:s");
				$result   = array(
					"success" => $success,
					"error" => $error,
					"Start Time" => $start_time,
					"End Time" => $end_time
	
				);
				$desc     = json_encode($result);
				$this->log_model->cronlog("Refunded List CSV Cron Execution End", $desc);
				fwrite($fp1, "\n" . "******** Refunded List CSV Cron Execution End " . $end_time . " ********" . "\n");
				fclose($fp1);
			}
		}
		public function send_mail_sbi()
		{
			$this->load->library('email');	
			$current_date   = date("Ymd");
			
			$from_name = 'Indian Institute of Banking and Finance';
			$from_email = 'logs@iibf.esdsconnect.com';
			$subject = 'IIBF: Need actual refund date of refunded transactions';
			$cc = 'iibfdevp@esds.co.in,anishrivastava@iibf.org.in,dattatreya@iibf.org.in';
			$recipient_list = array('support.sbiepay@sbi.co.in');
		
			$attachment_filename = 'Refunded_list_'.$current_date.'.csv';
			$attachment_path = './uploads/DV_Refund_SBI/'.$current_date.'/' . $attachment_filename;
			
			$message = '';
			$message .='<html><head><title>DV Payment Refunds Details</title></head>
				<body style="max-width:600px;border:1px solid #ccc;margin:10px auto;font-size:16px;line-height:18px;border: 1px solid black;"><div style="text-align: center;	padding: 15px 10px;	border-bottom: 1px solid #ccc;	line-height: 22px;
	font-weight: bold;	color: blue;background-color: gold;">INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>(AN ISO 21001:2018 Certified )</div><div style="padding:20px;">';
			$message .= '<p>&nbsp;Hello SBI Team,<br><br>';
			$message .= '&nbsp;Please find the attached file of refunded transactions. <br><br>&nbsp;Please send us the actual refund dates of given transactions.<br></p>';
			$message .= '<br><br>&nbsp;Thanks & Regards,<br>';
			$message .= '&nbsp;Bhushan Amrutkar|Sr.Software Engineer<br>';
			$message .= '&nbsp;ESDS Software Solution Pvt.Ltd.<br>';
			$message .= '</div></body></html>';
		
			$config = array('mailtype' => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
			
			$this->email->initialize($config);
			$this->email->from($from_email, $from_name);
			$this->email->to($recipient_list);
			$this->email->subject($subject);
			$this->email->message($message);
						
			$info_arr1=array('to'=>$recipient_list,'from'=>$from_name,'cc'=>$cc,'subject'=>$subject,'message'=>$message);    
			
			if($attachment_path != '')
			{
				$this->Emailsending->mailsend_attch_cc($info_arr1,$attachment_path);
				/*$this->email->attach($attachment_path);
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
				}*/
			}
		}
		
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php Bhushan_custom_scripts credit_note_csv
		public function credit_note_csv()
		{
			ini_set("memory_limit", "-1");
			$dir_flg        = 0;
			$parent_dir_flg = 0;
			$exam_file_flg  = 0;
			$success        = array();
			$error          = array();
			$start_time     = date("Y-m-d H:i:s");
			$current_date   = date("Ymd");
			$cron_file_dir  = "./uploads/Credit_Note_CSV/";
			$result         = array(
				"success" => "",
				"error" => "",
				"Start Time" => $start_time,
				"End Time" => ""
			);
			$desc           = json_encode($result);
			$this->log_model->cronlog("Credit Note CSV Cron Execution Start", $desc);
			if (!file_exists($cron_file_dir . $current_date)) {
				$parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
			}
			if (file_exists($cron_file_dir . $current_date)) {
				$cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
				$file           = "Credit_Note_" . $current_date . ".csv";
				$fp             = fopen($cron_file_path . '/' . $file, 'w');
				$file1          = "logs_" . $current_date . ".txt";
				$fp1            = fopen($cron_file_path . '/' . $file1, 'a');
				fwrite($fp1, "\n******** Credit Note List CSV Cron Execution Started - " . $start_time . " ******** \n");
				$start_date = date('Y-m-d', strtotime(date('Y-m-01').' -1 MONTH'));
    			$end_date = date("Y-m-t", strtotime($start_date));

				$select ='req_id,req_title,req_desc,req_member_no,req_module,transaction_no,req_exceptional_case,req_reason,req_status,credit_note_image,refund_date,credit_note_date,credit_note_number,sbi_refund_date,sbi_refund_status';
				$this->db->where('credit_note_number !=', '');
				$this->db->where('DATE(credit_note_gen_date) >=', $start_date);
				$this->db->where('DATE(credit_note_gen_date) <=', $end_date);
				$can_exam_data = $this->Master_model->getRecords('maker_checker','',$select);
				//echo $this->db->last_query();
				//exit; 
				if (count($can_exam_data)) 
				{
					$i             = 1;
					$exam_cnt      = 0;
					
					$data1="req_id,req_title,req_member_no,req_module,transaction_no,req_exceptional_case,req_reason,req_status,credit_note_image,esds_refund_date,credit_note_date,credit_note_number,sbi_refund_date \n";
					$exam_file_flg = fwrite($fp, $data1);
					
					foreach ($can_exam_data as $exam) 
					{
						$data =  '';
						
						$data .= ''.$exam['req_id'].','.$exam['req_title'].','.$exam['req_member_no'].','.$exam['req_module'].','.$exam['transaction_no'].','.$exam['req_exceptional_case'].','.$exam['req_reason'].','.$exam['req_status'].','.$exam['credit_note_image'].','.$exam['refund_date'].','.$exam['credit_note_date'].','.$exam['credit_note_number'].','.$exam['sbi_refund_date']."\n";
						
						$exam_file_flg = fwrite($fp, $data);
						if ($exam_file_flg)
							$success['cand_exam'] = "Credit Note CSV File Generated Successfully.";
						else
							$error['cand_exam'] = "Error While Generating Credit Note File.";
						$i++;
						$exam_cnt++;
					}
					fwrite($fp1, "Total Credit Note - " . $exam_cnt . "\n");
					
					$newPath = $cron_file_dir.$current_date."/Credit_Note_".$current_date.".csv";
					$NewName = "Credit_Note_".$current_date.".csv";
	
					$this->send_mail_credit_note();
				} 
				fclose($fp);
				$end_time = date("Y-m-d H:i:s");
				$result   = array("success" => $success,"error" => $error,"Start Time" => $start_time,"End Time" => $end_time);
				$desc     = json_encode($result);
				$this->log_model->cronlog("Credit Note CSV Cron Execution End", $desc);
				fwrite($fp1, "\n" . "******** Credit Note CSV Cron Execution End " . $end_time . " ********" . "\n");
				fclose($fp1);
			}
			
		}
		public function send_mail_credit_note()
		{
			$this->load->library('email');	
			$current_date   = date("Ymd");
			$start_date = date('Y-m-d', strtotime(date('Y-m-01').' -1 MONTH'));
    		$end_date = date("Y-m-t", strtotime($start_date));
			$from_name = 'Indian Institute of Banking and Finance';
			$from_email = 'logs@iibf.esdsconnect.com';
			$subject = 'IIBF: Credit Note Data from ('.$start_date.' to '.$end_date.')';
			$cc = 'iibfdevp@esds.co.in,dattatreya@iibf.org.in';
			$recipient_list = array('pallavi.panchal@esds.co.in');
			
			$attachment_filename = 'Credit_Note_'.$current_date.'.csv';
			$attachment_path = './uploads/Credit_Note_CSV/'.$current_date.'/' . $attachment_filename;
			
			$message = '';
			$message .='<html><head><title>Credit Note Details</title></head>
				<body style="max-width:600px;border:1px solid #ccc;margin:10px auto;font-size:16px;line-height:18px;border: 1px solid black;"><div style="text-align: center;	padding: 15px 10px;	border-bottom: 1px solid #ccc;	line-height: 22px;
	font-weight: bold;	color: blue;background-color: gold;">INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>(AN ISO 21001:2018 Certified )</div><div style="padding:20px;">';
			$message .= '<p>&nbsp;Hello Pallavi,<br><br>';
			$message .= '&nbsp;Please find the attached file of credit note data from '.$start_date.' to '.$end_date.' <br><br>Let us know if any query in the CSV.</p>';
			$message .= '<br><br>&nbsp;Thanks & Regards,<br>';
			$message .= '&nbsp;Bhushan Amrutkar|Sr.Software Engineer<br>';
			$message .= '&nbsp;ESDS Software Solution Pvt.Ltd.<br>';
			$message .= '</div></body></html>';
		
			$config = array('mailtype' => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
			
			$this->email->initialize($config);
			$this->email->from($from_email, $from_name);
			$this->email->to($recipient_list);
			$this->email->subject($subject);
			$this->email->message($message);
			
			$info_arr1=array('to'=>$recipient_list,'from'=>$from_email,'cc'=>$cc,'subject'=>$subject,'message'=>$message);   
			if($attachment_path != '')
			{
				$this->Emailsending->mailsend_attch_cc($info_arr1,$attachment_path);
				/*$this->email->attach($attachment_path);
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
				}*/
			}
		}
}
