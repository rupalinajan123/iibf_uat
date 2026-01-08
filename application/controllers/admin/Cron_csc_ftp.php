<?php
/*
 * Controller Name	:	CSC Images Cron File Transfer
 * Created By		:	Bhushan Amrutkar
 * Created Date		:	29 May 2019
 */
 
defined('BASEPATH') OR exit('No direct script access allowed');
class Cron_csc_ftp extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model('log_model');
		
		$this->load->library('email');
		
		//error_reporting(E_ALL);
		//ini_set('display_errors', 1);
	}
	
	public function index($current_date = "")
	{
		$start_time = date("Y-m-d H:i:s");
		
		$success = array();
		$error = array();
		
		// cron initialise log -
		$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("CSC File Transfer Cron - Start", $desc);
		
		if($current_date == ""){
			$current_date = date("Ymd");	
		}
		
		// files list -
		$file_list = array("iibf_csc_mem_details_".$current_date.".txt","csc_regd_image_".$current_date.".zip");
					
		// file wise remote server path -
		//$path_list = array( "/csc/".$current_date."/","/csc/".$current_date."/");
					
		// FTP Account (Remote Server) -
		$ftp_host 		= '115.124.109.58';
		$ftp_user_name 	= 'ftpadmin';	
		$ftp_user_pass 	= '2Zb5Hb9xU6zi';
		
		/*$ftp_host 	= '115.124.120.228';
		$ftp_user_name 	= 'iibfgrow';	
		$ftp_user_pass 	= '7Uv7guf$r';*/
		
		$local_server_dir_path = '/home/supp0rttest/public_html/uploads/cronfiles/'.$current_date.'/';
		
		//$local_server_dir_path = '/home/iibfgrow/public_html/uploads/cronFilesCustom/'.$current_date.'/';
		
		// Connect using basic FTP -
		$conn = ftp_connect($ftp_host) or die("Could not connect to remote server.");
		
		// login to FTP -
		$login_result = @ftp_login($conn, $ftp_user_name, $ftp_user_pass);
		
		// check if FTP Connect to remote server -
		if($login_result)
		{
			
			$cron_file_dir = "/csc/";
			$current_date = date("Ymd");
			
			if (ftp_mkdir($conn, $cron_file_dir.$current_date))
		  	{
				echo "Directory Successfully created";
		  	}
			else
		  	{
		  		echo "Error while creating Directory";
		  	}
			$path_list = array( "/csc/".$current_date."/","/csc/".$current_date."/");
		
			
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
						$success[] = "CSC Successfully file transfer : $file_name (size : $file_size).";
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
		$this->log_model->cronlog("File Transfer Cron - Ends", $desc);
		
		echo "<pre>";
		print_r($success);
		echo "<br>";
		print_r($error);
		
		echo "<br>Start : ".$start_time;
		echo "<br>";
		echo "End : ".$end_time;
		
		// mail details -
		$msg = '';
		$msg .= 'CSC Photo and Data Sync to IIBF FTP completed for the following files: <br><br>';
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
		//$from_email = 'noreply@iibf.org.in';
		$from_email = 'logs@iibf.esdsconnect.com';
		$subject = 'CSC Photo and Data Sync to IIBF FTP completed';
		
		// email receipient list -
		//$recipient_list = array('bhagwan.sahane@esds.co.in', 'prafull.tupe@esds.co.in', 'pawansing.pardeshi@esds.co.in', 'pooja.godse@esds.co.in', 'bhushan.amrutkar@esds.co.in', 'tejasvi.bhavsar@esds.co.in', 'ronit.powar@esds.co.in', 'anilr@esds.co.in', 'dattatreya@iibf.org.in', 'anishrivastava@iibf.org.in', 'sgbhatia@iibf.org.in');
		
		$recipient_list = array('iibfdevp@esds.co.in');
		
		$attachment_filename = 'logs_CSC_'.$current_date.'.txt';
		$attachment_path = './uploads/cronFiles/'.$current_date.'/' . $attachment_filename;
		
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
	
	// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csc_ftp cscvendor
	// 1003, 1004 exam codes
	public function cscvendor($current_date = "")
	{
		$start_time = date("Y-m-d H:i:s");
		
		$success = array();
		$error = array();
		
		// cron initialise log -
		$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("cscvendor File Transfer Cron - Start", $desc);
		
		if($current_date == ""){
			$current_date = date("Ymd");	
		}
		
		// files list -
		$file_list = array("iibf_cscvendor_mem_details_".$current_date.".txt","cscvendor_regd_image_".$current_date.".zip");
					
		// file wise remote server path -
		//$path_list = array( "/csc/".$current_date."/","/csc/".$current_date."/");
					
		// FTP Account (Remote Server) -
		$ftp_host 		= '115.124.109.58';
		$ftp_user_name 	= 'ftpadmin';	
		$ftp_user_pass 	= '2Zb5Hb9xU6zi';
		
		/*$ftp_host 	= '115.124.120.228';
		$ftp_user_name 	= 'iibfgrow';	
		$ftp_user_pass 	= '7Uv7guf$r';*/
		
		$local_server_dir_path = '/home/supp0rttest/public_html/uploads/cronfiles/'.$current_date.'/';
		
		//$local_server_dir_path = '/home/iibfgrow/public_html/uploads/cronFilesCustom/'.$current_date.'/';
		
		// Connect using basic FTP -
		$conn = ftp_connect($ftp_host) or die("Could not connect to remote server.");
		
		// login to FTP -
		$login_result = @ftp_login($conn, $ftp_user_name, $ftp_user_pass);
		
		// check if FTP Connect to remote server -
		if($login_result)
		{
			
			$cron_file_dir = "/cscvendor/";
			$current_date = date("Ymd");
			
			if (ftp_mkdir($conn, $cron_file_dir.$current_date))
		  	{
				echo "Directory Successfully created";
		  	}
			else
		  	{
		  		echo "Error while creating Directory";
		  	}
			$path_list = array( "/cscvendor/".$current_date."/","/cscvendor/".$current_date."/");
		
			
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
						$success[] = "cscvendor Successfully file transfer : $file_name (size : $file_size).";
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
		$this->log_model->cronlog("File Transfer Cron - Ends", $desc);
		
		echo "<pre>";
		print_r($success);
		echo "<br>";
		print_r($error);
		
		echo "<br>Start : ".$start_time;
		echo "<br>";
		echo "End : ".$end_time;
		
		// mail details -
		$msg = '';
		$msg .= 'cscvendor(1003,1004) Photo and Data Sync to IIBF FTP completed for the following files: <br><br>';
		$msg .= '<b>Success :</b> <br><br>';
		$msg .= implode("<br>", $success);
		$msg .= '<br><br><b>Error :</b> <br><br>';
		$msg .= implode("<br>", $error);
		$msg .= '<br><br><b>Start Time :</b> ' . $start_time;
		$msg .= '<br><b>End Time :</b> ' . $end_time;
		
		// send email notification -
		$result = $this->sendmailcscvendor($current_date, $msg);
	}
	
	public function sendmailcscvendor($current_date = "", $msg = "")
	{
		if($current_date == "")
		{
			$current_date = date("Ymd");	
		}
		
		$from_name = 'IIBF'; 
		//$from_email = 'noreply@iibf.org.in';
		$from_email = 'logs@iibf.esdsconnect.com';
		$subject = 'cscvendor(1003,1004) Photo and Data Sync to IIBF FTP completed';
		
		// email receipient list -
		//$recipient_list = array('iibfdevp@esds.co.in','anishrivastava@iibf.org.in','iibfexam@cscacademy.org','omveer@csc.gov.in');
		
		$recipient_list = array('iibfdevp@esds.co.in');
		
		$attachment_filename = 'logs_cscvendor_'.$current_date.'.txt';
		$attachment_path = './uploads/cronFiles/'.$current_date.'/' . $attachment_filename;
		
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
	
	// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csc_ftp photo_NSEIT
	// 1002 exam codes
	public function photo_NSEIT($current_date = "")
	{
		$start_time = date("Y-m-d H:i:s");
		
		$success = array();
		$error = array();
		
		// cron initialise log -
		$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("NSEIT File Transfer Cron - Start", $desc);
		
		if($current_date == ""){
			$current_date = date("Ymd");	
		}
		
		// files list -
		$file_list = array("iibf_NSEIT_mem_details_".$current_date.".txt","NSEIT_regd_image_".$current_date.".zip");
					
		// file wise remote server path -
		//$path_list = array( "/csc/".$current_date."/","/csc/".$current_date."/");
					
		
		// FTP Account Test Server from Vinod
		$ftp_host 		= '115.124.109.58';
		$ftp_user_name 	= 'phptest';	
		$ftp_user_pass 	= 'v7nHQm8dZcj4';
		
		/*$ftp_host 	= '115.124.120.228';
		$ftp_user_name 	= 'iibfgrow';	
		$ftp_user_pass 	= '7Uv7guf$r';*/
		
		$local_server_dir_path = '/home/supp0rttest/public_html/uploads/cronfiles/'.$current_date.'/';
		
		//$local_server_dir_path = '/home/iibfgrow/public_html/uploads/cronFilesCustom/'.$current_date.'/';
		
		// Connect using basic FTP -
		$conn = ftp_connect($ftp_host) or die("Could not connect to remote server.");
		
		// login to FTP -
		$login_result = @ftp_login($conn, $ftp_user_name, $ftp_user_pass);
		
		// check if FTP Connect to remote server -
		if($login_result)
		{
			
			$cron_file_dir = "/NSEIT/";
			$current_date = date("Ymd");
			
			if (ftp_mkdir($conn, $cron_file_dir.$current_date))
		  	{
				echo "Directory Successfully created";
		  	}
			else
		  	{
		  		echo "Error while creating Directory";
		  	}
			$path_list = array( "/NSEIT/".$current_date."/","/NSEIT/".$current_date."/");
		
			
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
						$success[] = "NSEIT Successfully file transfer : $file_name (size : $file_size).";
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
		$this->log_model->cronlog("File Transfer Cron - Ends", $desc);
		
		echo "<pre>";
		print_r($success);
		echo "<br>";
		print_r($error);
		
		echo "<br>Start : ".$start_time;
		echo "<br>";
		echo "End : ".$end_time;
		
		// mail details -
		$msg = '';
		$msg .= 'NSEIT(1002) Photo and Data Sync to IIBF FTP completed for the following files: <br><br>';
		$msg .= '<b>Success :</b> <br><br>';
		$msg .= implode("<br>", $success);
		$msg .= '<br><br><b>Error :</b> <br><br>';
		$msg .= implode("<br>", $error);
		$msg .= '<br><br><b>Start Time :</b> ' . $start_time;
		$msg .= '<br><b>End Time :</b> ' . $end_time;
		
		// send email notification -
		$result = $this->sendmailNSEIT($current_date, $msg);
	}
	
	public function sendmailNSEIT($current_date = "", $msg = "")
	{
		if($current_date == "")
		{
			$current_date = date("Ymd");	
		}
		
		$from_name = 'IIBF'; 
		//$from_email = 'noreply@iibf.org.in';
		$from_email = 'logs@iibf.esdsconnect.com';
		$subject = 'NSEIT(1002) Photo and Data Sync to IIBF FTP completed';
		
		// email receipient list -
		//$recipient_list = array('iibfdevp@esds.co.in','anishrivastava@iibf.org.in','iibfexam@cscacademy.org','omveer@csc.gov.in');
		
		$recipient_list = array('iibfdevp@esds.co.in');
		
		$attachment_filename = 'logs_NSEIT_'.$current_date.'.txt';
		$attachment_path = './uploads/cronFiles/'.$current_date.'/' . $attachment_filename;
		
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
	
	// Cron_csc_ftp/free_csc_ftp
	// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csc_ftp free_csc_ftp
 	public function free_csc_ftp($current_date = "")
	{
		$start_time = date("Y-m-d H:i:s");
		
		$success = array();
		$error = array();
		
		// cron initialise log -
		$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("CSC File Transfer Cron - Start", $desc);
		
		if($current_date == ""){
			$current_date = date("Ymd");	
		}
		
		// files list -
		$file_list = array("iibf_csc_mem_details_".$current_date.".txt","csc_regd_image_".$current_date.".zip");
					
		// file wise remote server path -
		//$path_list = array( "/csc/".$current_date."/","/csc/".$current_date."/");
					
		// FTP Account (Remote Server) -
		$ftp_host 		= '115.124.109.58';
		$ftp_user_name 	= 'ftpadmin';	
		$ftp_user_pass 	= '2Zb5Hb9xU6zi';
		
		/*$ftp_host 	= '115.124.120.228';
		$ftp_user_name 	= 'iibfgrow';	
		$ftp_user_pass 	= '7Uv7guf$r';*/
		
		$local_server_dir_path = '/home/supp0rttest/public_html/uploads/CSC_FREE_APP/'.$current_date.'/';
		
		//$local_server_dir_path = '/home/iibfgrow/public_html/uploads/cronFilesCustom/'.$current_date.'/';
		
		// Connect using basic FTP -
		$conn = ftp_connect($ftp_host) or die("Could not connect to remote server.");
		
		// login to FTP -
		$login_result = @ftp_login($conn, $ftp_user_name, $ftp_user_pass);
		
		// check if FTP Connect to remote server -
		if($login_result)
		{
			
			$cron_file_dir = "/CSC_FREE_APP/";
			$current_date = date("Ymd");
			
			if (ftp_mkdir($conn, $cron_file_dir.$current_date))
		  	{
				echo "Directory Successfully created";
		  	}
			else
		  	{
		  		echo "Error while creating Directory";
		  	}
			$path_list = array( "/CSC_FREE_APP/".$current_date."/","/CSC_FREE_APP/".$current_date."/");
		
			
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
						$success[] = "CSC Successfully file transfer : $file_name (size : $file_size).";
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
		$this->log_model->cronlog("File Transfer Cron - Ends", $desc);
		
		echo "<pre>";
		print_r($success);
		echo "<br>";
		print_r($error);
		
		echo "<br>Start : ".$start_time;
		echo "<br>";
		echo "End : ".$end_time;
		
		// mail details -
		$msg = '';
		$msg .= 'CSC Photo and Data Sync to IIBF FTP completed for the following files: <br><br>';
		$msg .= '<b>Success :</b> <br><br>';
		$msg .= implode("<br>", $success);
		$msg .= '<br><br><b>Error :</b> <br><br>';
		$msg .= implode("<br>", $error);
		$msg .= '<br><br><b>Start Time :</b> ' . $start_time;
		$msg .= '<br><b>End Time :</b> ' . $end_time;
		
		// send email notification -
		$result = $this->sendmail_free_csc_ftp($current_date, $msg);
	}
	
	public function sendmail_free_csc_ftp($current_date = "", $msg = "")
	{
		if($current_date == "")
		{
			$current_date = date("Ymd");	
		}
		
		
		
		$from_name = 'IIBF';
		//$from_email = 'noreply@iibf.org.in';
		$from_email = 'logs@iibf.esdsconnect.com';
		$subject = 'Free CSC Photo and Data Sync to IIBF FTP completed';
		
		// email receipient list -
		//$recipient_list = array('bhagwan.sahane@esds.co.in', 'prafull.tupe@esds.co.in', 'pawansing.pardeshi@esds.co.in', 'pooja.godse@esds.co.in', 'bhushan.amrutkar@esds.co.in', 'tejasvi.bhavsar@esds.co.in', 'ronit.powar@esds.co.in', 'anilr@esds.co.in', 'dattatreya@iibf.org.in', 'anishrivastava@iibf.org.in', 'sgbhatia@iibf.org.in');
		
		$recipient_list = array('iibfdevp@esds.co.in');
		
		$attachment_filename = 'logs_CSC_'.$current_date.'.txt';
		$attachment_path = './uploads/CSC_FREE_APP/'.$current_date.'/' . $attachment_filename;
		
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
	
	// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csc_ftp dra_photo_NSEIT
	// 1002 exam codes
	public function dra_photo_NSEIT($current_date = "")
	{
		$start_time = date("Y-m-d H:i:s");
		
		$success = array();
		$error = array();
		
		// cron initialise log -
		$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("DRA NSEIT File Transfer Cron - Start", $desc);
		
		if($current_date == ""){
			$current_date = date("Ymd");	
		}
		
		// files list -
		$file_list = array("dra_mem_details_".$current_date.".txt","dra_regd_image_".$current_date.".zip");
					
		
		// FTP Account Test Server from Vinod
		$ftp_host 		= '115.124.109.58';
		$ftp_user_name 	= 'phptest';	
		$ftp_user_pass 	= 'v7nHQm8dZcj4';
		
		
		$local_server_dir_path = '/home/supp0rttest/public_html/uploads/dra_csv/'.$current_date.'/';
		
		// Connect using basic FTP -
		$conn = ftp_connect($ftp_host) or die("Could not connect to remote server.");
		
		// login to FTP -
		$login_result = @ftp_login($conn, $ftp_user_name, $ftp_user_pass);
		
		// check if FTP Connect to remote server -
		if($login_result)
		{
			
			$cron_file_dir = "/dra_csv/";
			$current_date = date("Ymd");
			
			if (ftp_mkdir($conn, $cron_file_dir.$current_date))
		  	{
				echo "Directory Successfully created";
		  	}
			else
		  	{
		  		echo "Error while creating Directory";
		  	}
			$path_list = array( "/dra_csv/".$current_date."/","/dra_csv/".$current_date."/");
		
			
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
						$success[] = "NSEIT Successfully file transfer : $file_name (size : $file_size).";
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
		$this->log_model->cronlog("File Transfer Cron - Ends", $desc);
		
		echo "<pre>";
		print_r($success);
		echo "<br>";
		print_r($error);
		
		echo "<br>Start : ".$start_time;
		echo "<br>";
		echo "End : ".$end_time;
		
		// mail details -
		$msg = '';
		$msg .= 'NSEIT: DRA Photo and Data Sync to IIBF FTP completed for the following files: <br><br>';
		$msg .= '<b>Success :</b> <br><br>';
		$msg .= implode("<br>", $success);
		$msg .= '<br><br><b>Error :</b> <br><br>';
		$msg .= implode("<br>", $error);
		$msg .= '<br><br><b>Start Time :</b> ' . $start_time;
		$msg .= '<br><b>End Time :</b> ' . $end_time;
		
		// send email notification -
		$result = $this->sendmail_dra_NSEIT($current_date, $msg);
	}
	
	public function sendmail_dra_NSEIT($current_date = "", $msg = "")
	{
		if($current_date == "")
		{
			$current_date = date("Ymd");	
		}
		
		$from_name = 'IIBF'; 
		//$from_email = 'noreply@iibf.org.in';
		$from_email = 'logs@iibf.esdsconnect.com';
		$subject = 'NSEIT: DRA Photo and Data Sync to IIBF FTP completed';
		
		// email receipient list -
		//$recipient_list = array('iibfdevp@esds.co.in','anishrivastava@iibf.org.in','iibfexam@cscacademy.org','omveer@csc.gov.in');
		
		$recipient_list = array('iibfdevp@esds.co.in');
		
		$attachment_filename = 'logs_'.$current_date.'.txt';
		$attachment_path = './uploads/dra_csv/'.$current_date.'/' . $attachment_filename;
		
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