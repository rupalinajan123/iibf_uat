<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Title	:	Invoice Cron FTP
 * Author	:	Bhushan Amrutkar
 * Created	:	29-07-2019
*/
 
class Cron_ftp_invoice extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('log_model');
		
		$this->load->library('email');
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}
	public function index($current_date = "")
	{
		$start_time = date("Y-m-d H:i:s");
		
		$success = array();
		$error = array();
		
		// cron initialise log -
		$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Invoice File Transfer Cron - Start", $desc);
		
		if($current_date == "")
		{
			$current_date = date("Ymd");	
		}
		
		// files list -
		$file_list = array(
							"mem_invoice_".$current_date.".txt",
							"exam_invoice_".$current_date.".txt",
							"dra_exam_invoice_".$current_date.".txt",
							"dup_icard_invoice_".$current_date.".txt",
							"dup_cert_invoice_".$current_date.".txt",
							"renewal_invoice_".$current_date.".txt",
							"bankquest_invoice_".$current_date.".txt",
							"vision_invoice_".$current_date.".txt",
							"finquest_invoice_".$current_date.".txt",
							"cpd_invoice_".$current_date.".txt",
							"blended_course_invoice_".$current_date.".txt",
							"contact_classes_invoice_".$current_date.".txt",
							"dra_inst_invoice_".$current_date.".txt",
							"bulk_exam_invoice_".$current_date.".txt",
							"digital_elearning_invoice_".$current_date.".txt",
							"agency_center_invoice_".$current_date.".txt",
							"agn_ctr_renew_invoice_".$current_date.".txt",
							"credit_note_".$current_date.".txt",
							"exam_invoice_spm_elearning_".$current_date.".txt",
							"exam_invoice_amp_".$current_date.".txt",
							
							"mem_invoice_image_".$current_date.".zip",
							"exam_invoice_image_".$current_date.".zip",
							"dra_exam_invoice_image_".$current_date.".zip",
							"dup_icard_invoice_image_".$current_date.".zip",
							"dup_cert_invoice_image_".$current_date.".zip",
							"renewal_invoice_image_".$current_date.".zip",
							"bankquest_invoice_image_".$current_date.".zip",
							"vision_invoice_image_".$current_date.".zip",
							"finquest_invoice_image_".$current_date.".zip",
							"cpd_invoice_image_".$current_date.".zip",
							"blended_course_invoice_image_".$current_date.".zip",
							"contact_classes_invoice_image_".$current_date.".zip",
							"dra_inst_invoice_image_".$current_date.".zip",
							"bulk_exam_invoice_image_".$current_date.".zip",
							"digital_elearning_invoice_image_".$current_date.".zip",
							"agency_center_invoice_image_".$current_date.".zip",
							"agn_ctr_renew_invoice_image_".$current_date.".zip",
							"credit_note_image_".$current_date.".zip",
							"exam_invoice_spm_elearning_image_".$current_date.".zip",
							"amp_exam_invoice_image_".$current_date.".zip",
					);
					
		// file wise remote server path -
		$path_list = array(
							"/fromweb/invoice/data/",
							"/fromweb/invoice/data/",
							"/fromweb/invoice/data/",
							"/fromweb/invoice/data/",
							"/fromweb/invoice/data/",
							"/fromweb/invoice/data/",
							"/fromweb/invoice/data/",
							"/fromweb/invoice/data/",
							"/fromweb/invoice/data/",
							"/fromweb/invoice/data/",
							"/fromweb/invoice/data/",
							"/fromweb/invoice/data/",
							"/fromweb/invoice/data/",
							"/fromweb/invoice/data/",
							"/fromweb/invoice/data/",
							"/fromweb/invoice/data/",
							"/fromweb/invoice/data/",
							"/fromweb/invoice/data/",
							"/fromweb/invoice/data/",
							"/fromweb/invoice/data/",
							
							"/fromweb/invoice/images/",
							"/fromweb/invoice/images/",
							"/fromweb/invoice/images/",
							"/fromweb/invoice/images/",
							"/fromweb/invoice/images/",
							"/fromweb/invoice/images/",
							"/fromweb/invoice/images/",
							"/fromweb/invoice/images/",
							"/fromweb/invoice/images/",
							"/fromweb/invoice/images/",
							"/fromweb/invoice/images/",
							"/fromweb/invoice/images/",
							"/fromweb/invoice/images/",
							"/fromweb/invoice/images/",
							"/fromweb/invoice/images/",
							"/fromweb/invoice/images/",
							"/fromweb/invoice/images/",
							"/fromweb/invoice/images/",
							"/fromweb/invoice/images/",
							"/fromweb/invoice/images/",
					);
					
		// FTP Account (Remote Server) -
		$ftp_host 		= '10.10.233.70';
		$ftp_user_name 	= 'webonline_esds';	
		$ftp_user_pass 	= 'Esds##1234$';
		
		// remote server directory path -
		$local_server_dir_path = '/home/supp0rttest/public_html/uploads/invoice_cronfiles_pg/'.$current_date.'/';
		
		// connect using basic FTP -
		$conn = ftp_connect($ftp_host) or die("Could not connect to remote server.");
		
		// login to FTP -
		$login_result = @ftp_login($conn, $ftp_user_name, $ftp_user_pass);
		
		// check if FTP Connect to remote server -
		if($login_result)
		{
			// send each file to FTP -
			foreach($file_list as $key => $file_name)
			{
				// remote server file path -
				$remote_server_dir_path = $path_list[$key];
				
				// set mode according to file type -
				$mode = FTP_ASCII;	// for text file
				
				$info = pathinfo($file_name);
				if ($info["extension"] == "zip")
				{
					$mode = FTP_BINARY;	// for zip file	
				}
				
				// local file path -
				$local_file_path = $local_server_dir_path . $file_name;
				
				// remote file path -
				$remote_file_path = $remote_server_dir_path . $file_name;
				
				// check if local file is present -
				if(!file_exists($local_file_path))
				{
					$error[] = "File not exist : $file_name.";
				}
				else
				{
					// get file size -
					$file_size = filesize($local_file_path);
					
					// send local file to FTP -
					if (ftp_put($conn, $remote_file_path, $local_file_path, $mode))
					{
						//echo "Successfully file transfer : File Name - $file_name. <br>";
						$success[] = "Successfully file transfer : $file_name (size : $file_size).";
					}
					else
					{
						//echo "There was a problem while file transfer : File name - $file_name. <br>";
						$error[] = "There was a problem while file transfer : $file_name (size : $file_size).";
					}
				}
			}
			
			// close the connection -
			ftp_close($conn);
		}
		else
		{
			$error[] = "There was a problem while login to remote server ftp.";	
		}
		
		$end_time = date("Y-m-d H:i:s");
				
		// cron end log -
		$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
		$desc = json_encode($result);
		$this->log_model->cronlog("Invoice File Transfer Cron - Ends", $desc);
		
		echo "<pre>";
		print_r($success);
		echo "<br>";
		print_r($error);
		
		echo "<br>Start : ".$start_time;
		echo "<br>";
		echo "End : ".$end_time;
		
		// mail details -
		$msg = '';
		$msg .= 'Invoice Data Sync to IIBF FTP completed for the following files: <br><br>';
		$msg .= '<b>Success :</b> <br><br>';
		$msg .= implode("<br>", $success);
		$msg .= '<br><br><b>Error :</b> <br><br>';
		$msg .= implode("<br>", $error);
		$msg .= '<br><br><b>Start Time :</b> ' . $start_time;
		$msg .= '<br><b>End Time :</b> ' . $end_time;
		
		// send email notification -
		$result = $this->sendmail($current_date, $msg);
	}
	
	public function sendmail($current_date = "", $msg = "")
	{
		if($current_date == "")
		{
			$current_date = date("Ymd");	
		}
		
		$from_name = 'IIBF';
		$from_email = 'logs@iibf.esdsconnect.com';
		$subject = 'Invoice Data Sync to IIBF FTP completed';
		
		// email receipient list -
		$recipient_list = array('iibfdevp@esds.co.in','dattatreya@iibf.org.in', 'sgbhatia@iibf.org.in');
		
		//$recipient_list = array('bhushan.amrutkar@esds.co.in');
		
		// 'anishrivastava@iibf.org.in',
		
		$attachment_filename = 'logs_'.$current_date.'.txt';
		$attachment_path = './uploads/invoice_cronfiles_pg/'.$current_date.'/' . $attachment_filename;
		
		$message = '' . $msg;
		
		$config = array('mailtype' => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
		
		$this->email->initialize($config);
		$this->email->from($from_email, $from_name);
		$this->email->to($recipient_list);
		$this->email->subject($subject);
		$this->email->message($message);
		if($attachment_path != '')
		{
			$this->email->attach($attachment_path);
		}
		if($this->email->send())
		{
			echo 'Email Sent.';
			
			//$this->email->print_debugger();
			//echo $this->email->print_debugger();
			return true;
		}
		else
		{
			echo 'Email Not Sent.';
			
			return false;	
		}
	}
}