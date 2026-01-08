<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Title	:	KYC Cron FTP
 * Author	:	Bhagwan Sahane
 * Created	:	09-05-2017
 * Updated	:	31-05-2017
 *
 */
class Cron_ftp_kyc_test extends CI_Controller {
	
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
		$this->log_model->cronlog("KYC File Transfer Cron - Start", $desc);
		
		if($current_date == "")
		{
			$current_date = date("Ymd");	
		}
		
		// files list -
		$file_list = array(
							"member_kyc_recommended_".$current_date.".txt",
							"member_kyc_approved_".$current_date.".txt",
							"member_kyc_edited_".$current_date.".txt",
							"member_kyc_email_".$current_date.".txt"
					);
					
		// file wise remote server path -
		$path_list = array(
							"/fromweb/testscript/newmem/",
							"/fromweb/testscript/newmem/",
							"/fromweb/testscript/newmem/",
							"/fromweb/testscript/newmem/"
					);
					
		// FTP Account (Remote Server) -
		$ftp_host 		= '10.10.233.70';
		$ftp_user_name 	= 'webonline_esds';	
		$ftp_user_pass 	= 'Esds##1234$';
		
		// remote server directory path 
		$local_server_dir_path = '/home/supp0rttest/public_html/uploads/cronFilesCustom/'.$current_date.'/';
		
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
		$this->log_model->cronlog("KYC File Transfer Cron - Ends", $desc);
		
		echo "<pre>";
		print_r($success);
		echo "<br>";
		print_r($error);
		
		echo "<br>Start : ".$start_time;
		echo "<br>";
		echo "End : ".$end_time;
		
		// mail details -
		$msg = '';
		$msg .= 'KYC Data Sync to IIBF FTP completed for the following files: <br><br>';
		$msg .= '<b>Success :</b> <br><br>';
		$msg .= implode("<br>", $success);
		$msg .= '<br><br><b>Error :</b> <br><br>';
		$msg .= implode("<br>", $error);
		$msg .= '<br><br><b>Start Time :</b> ' . $start_time;
		$msg .= '<br><b>End Time :</b> ' . $end_time;
		
		// send email notification -
		//$result = $this->sendmail($current_date, $msg);
	}
	
	public function sendmail($current_date = "", $msg = "")
	{
		if($current_date == "")
		{
			$current_date = date("Ymd");	
		}
		
		
		$from_name = 'IIBF';
		$from_email = 'logs@iibf.esdsconnect.com';
		$subject = 'KYC Data Sync to IIBF FTP completed';
		
		// email receipient list -
		$recipient_list = array('iibfdevp@esds.co.in');
		
		//$recipient_list = array('bhagwan.sahane@esds.co.in', 'prafull.tupe@esds.co.in', 'pawansing.pardeshi@esds.co.in', 'pooja.godse@esds.co.in', 'bhushan.amrutkar@esds.co.in', 'ronit.powar@esds.co.in', 'anilr@esds.co.in');
		
		$attachment_filename = 'logs_'.$current_date.'.txt';
		$attachment_path = './uploads/cronFilesCustom/'.$current_date.'/' . $attachment_filename;
		
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