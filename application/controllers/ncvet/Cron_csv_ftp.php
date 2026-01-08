<?php
/*
 * Controller Name	:	Cron File Transfer
 * Created By		:	Priyanka D
 * Created Date		:	13 Oct 25
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Cron_csv_ftp extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model('log_model');
		$this->load->library('email');		
		//error_reporting(E_ALL);
		//ini_set('display_errors', 1);
	}
	public function elearning_data($current_date = "")
	{
		$start_time = date("Y-m-d H:i:s");
		$success = array();
		$error = array();
		
		// cron initialise log -
		$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("NCVET File Transfer Cron - Start", $desc);
		
		if($current_date == "")
		{
			$current_date = date("Ymd");	
		}
		
		// Get File Names
		$CurrentDate = date('Y-m-d',strtotime($current_date));
		
		$this->db->where('CurrentDate', $CurrentDate);
        $array = $this->master_model->getRecords('ncvet_cron_csv','','new_file_name,record_count');
		$new_file_name = $array[0]['new_file_name'];
		$record_count = $array[0]['record_count'];
		
		// files list -
		$file_list = array($new_file_name);

		//Priyanka D >> 11-Aug-25 >> to send multiple files to teamlease
		$register_data_type = array();
		$record_count=0;
		foreach($array as $a) {
			$file_list[]=$a['new_file_name'];
			//$register_data_type[]=$a['register_data_type'];
			$record_count+=$a['record_count'];
		}

					
		// file wise remote server path -
		//$path_list = array("/jaiib_kesdee/"); // comment this line on 07-march-24 by priyanka D ->> asked by client to send data to teamlease not kesdee
		$path_list = array("/teamlease/ncvet/");
	
		// FTP Account Test Server from Vinod
		$ftp_host 		= '115.124.98.143';
		$ftp_user_name 	= 'phptest';	
		$ftp_user_pass 	= 'v7nHQm8dZcj4';
		
		$local_server_dir_path = '/home/supp0rttest/public_html/uploads/ncvet/cronfiles/'.$current_date.'/';
		
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
				//$remote_server_dir_path = $path_list[$key];
				$remote_server_dir_path = $path_list[0];//Priyanka D >> 11-Aug-25 >> to send multiple files to teamlease
				
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
					$error[] = "NCVET Elearning - File not exist : $file_name.";
				}
				else
				{
					// get file size -
					$file_size = filesize($local_file_path);
					
					// send local file to FTP -
					if (ftp_put($conn, $remote_file_path, $local_file_path, $mode))
					{
						//echo "Successfully file transfer : File Name - $file_name. <br>";
						$success[] = "NCVET Elearning - Successfully file transfer : $file_name (size : $file_size).";
					}
					else
					{
						//echo "There was a problem while file transfer : File name - $file_name. <br>";
						$error[] = "NCVET Elearning - There was a problem while file transfer : $file_name (size : $file_size).";
					}
				}
			}
			
			// close the connection -
			ftp_close($conn);
		}
		else
		{
			$error[] = "NCVET Elearning - There was a problem while login to remote server ftp.";	
		}
		
		$end_time = date("Y-m-d H:i:s");
				
		// cron end log -
		$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
		$desc = json_encode($result);
		$this->log_model->cronlog("NCVET Elearning - CSV File Transfer Cron - Ends", $desc);
		
		echo "<pre>";
		print_r($success);
		echo "<br>";
		print_r($error);
		
		echo "<br>Start : ".$start_time;
		echo "<br>";
		echo "End : ".$end_time;
		
		if($record_count != 0)
		{
			// mail details -
			$msg = '';
			$msg .= 'Dear IIBF LMS Support Team,<br><br>';
			$msg .= 'The file named '.$new_file_name.' with a total of '.$record_count.' records has been copied to the SFTP location on '.date("Y/m/d H:i:s").' for further processing.  Please inform the IIBF team once the file has been processed.<br><br>';
			$msg .= 'If the file gets corrupted or there is mismatch in the number of records, please inform iibfdevp@esds.co.in.<br><br>';
			$msg .= 'Regards,<br>';
			$msg .= 'The ESDS Team.';
		}
		else
		{
			// mail details -
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$msg = '';
			$msg .= 'Dear IIBF LMS Support Team,<br><br>';
			$msg .= 'No data found for the  date: '.$yesterday.'<br><br>';
			$msg .= 'Regards,<br>';
			$msg .= 'The ESDS Team.';
			
		}
		// send email notification -
		$result = $this->ncvet_sendmail($current_date, $msg);
	}
	
	public function ncvet_sendmail($current_date = "", $msg = "")
	{
		if($current_date == "")
		{
			$current_date = date("Ymd");	
		}
		
		$from_name = 'IIBF';
		//$from_email = 'noreply@iibf.org.in';
		$from_email = 'logs@iibf.esdsconnect.com';
		$subject = 'NCVET Elearning: Member Applications CSV File Transfer To Teamlease Vendor';
	
		$recipient_list = 
		array('kalpanashetty@iibf.org.in','dd.aca4@iibf.org.in','elearning@iibf.org.in','smuralidaran@iibf.org.in','iibfdevp@esds.co.in','dd.aca2@iibf.org.in','iibfsupport@digivarsity.com'); 
		
		$recipient_list = array('iibfdevp@esds.co.in');
		
		$attachment_filename = 'logs_'.$current_date.'.txt';
		$attachment_path = './uploads/ncvet/cronfiles/'.$current_date.'/' . $attachment_filename;
		
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