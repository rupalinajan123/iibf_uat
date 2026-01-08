<?php
/*
 	* Controller Name	:	Cron "FTP" File Generation
 	* Created By		:	Bhushan
 	* Created Date		:	29-07-2019
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Cron_ftp extends CI_Controller {
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
		$this->log_model->cronlog("File Transfer Cron - Start", $desc);
		
		if($current_date == "")
		{
			$current_date = date("Ymd");	
		}
		
		// files list
		$file_list = array(
							"iibf_new_mem_details_".$current_date.".txt",
							"iibf_new_mem_csc_details_".$current_date.".txt",
							"iibf_new_member_elearning_spm_details_".$current_date.".txt",
							"iibf_digital_eLearning_mem_details_".$current_date.".txt",
							"edited_cand_details_".$current_date.".txt",
							"edited_cand_details_photo_".$current_date.".txt",
							"edited_cand_details_sign_".$current_date.".txt",
							"edited_cand_details_id_".$current_date.".txt",
							"iibf_dup_icard_details_".$current_date.".txt",
							"exam_cand_report_".$current_date.".txt",
							"exam_cand_elearning_spm_report_".$current_date.".txt",
							"dra_mem_details_".$current_date.".txt",
							"dra_edited_mem_details_".$current_date.".txt",
							"dra_exam_cand_report_".$current_date.".txt",
							"dup_cert_details_".$current_date.".txt",
							"renewal_mem_details_".$current_date.".txt",
							"admit_card_".$current_date.".txt",
							"bankquest_details_".$current_date.".txt",
							"iibf_vision_details_".$current_date.".txt",
							"finquest_details_".$current_date.".txt",
							"cpd_details_".$current_date.".txt",
							"blended_details_".$current_date.".txt",
							"dra_inst_details_".$current_date.".txt",
							"bulk_exam_cand_report_".$current_date.".txt",
							"bulk_admit_card_".$current_date.".txt",
							"blended_member_feedback_".$current_date.".txt",
							"blended_faculty_feedback_".$current_date.".txt",
							"digital_elearning_exam_report_".$current_date.".txt",
							"csc_exam_cand_report_".$current_date.".txt",
							"exam_training_report_".$current_date.".txt",
							"temp_exam_cand_report_".$current_date.".txt",
							"temp_admit_card_".$current_date.".txt",
							"remote_exam_cand_report_".$current_date.".txt",
							"edited_cand_details_benchmark_".$current_date.".txt",
							"free_remote_exam_cand_report_".$current_date.".txt",
							"free_admit_card_".$current_date.".txt",
							"dra_admit_card_".$current_date.".txt",
							"iibf_scribe_reg_details_".$current_date.".txt",
							
							
							"regd_image_".$current_date.".zip",
							"regd_image_csc_".$current_date.".zip",
							"mem_digital_eLearning_image_".$current_date.".zip",
							"edited_image_".$current_date.".zip",
							"dra_regd_image_".$current_date.".zip",
							"dra_edited_regd_image_".$current_date.".zip",
							"renewal_image_".$current_date.".zip",
							"edited_benchmark_".$current_date.".zip",
							"scribe_image_".$current_date.".zip",
							
					);
					
		// file wise remote server path
		$path_list = array(
							"/fromweb/newmem/",
							"/fromweb/newmem/",
							"/fromweb/newmem/",
							"/fromweb/newmem/",
							"/fromweb/edit/data/",
							"/fromweb/edit/data/",
							"/fromweb/edit/data/",
							"/fromweb/edit/data/",
							"/fromweb/iibfdup/",
							"/fromweb/exam/data/",
							"/fromweb/exam/data/",
							"/fromweb/dra/",
							"/fromweb/dra/",
							"/fromweb/dra/",
							"/fromweb/dupcert/",
							"/fromweb/renewal/",
							"/fromweb/admitcard/",
							"/fromweb/bankquest/",
							"/fromweb/iibfvision/",
							"/fromweb/finquest/",
							"/fromweb/cpd/",
							"/fromweb/blended/",
							"/fromweb/drainst/",
							"/fromweb/exam/data/",
							"/fromweb/admitcard/",
							"/fromweb/blendedfeedback/",
							"/fromweb/blendedfeedback/",
							"/fromweb/digitalelearning/",
							"/fromweb/exam/data/",
							"/fromweb/exam/data/",
							"/fromweb/exam/data/",
							"/fromweb/admitcard/",
							"/fromweb/exam/data/",
							"/fromweb/edit/data/",
							"/fromweb/exam/data/",
							"/fromweb/admitcard/",
							"/fromweb/admitcard/",
							"/fromweb/scribe/",

							"/fromweb/newmem/images/",
							"/fromweb/newmem/images/",
							"/fromweb/newmem/images/",
							"/fromweb/edit/images/",
							"/fromweb/dra/images/",
							"/fromweb/dra/images/",
							"/fromweb/renewal/images/",
							"/fromweb/edit/images/",
							"/fromweb/scribe/images/",
												
					);

		// FTP Account (Remote Server)
		$ftp_host 		= '10.10.233.70';
		$ftp_user_name 	= 'webonline_esds';	
		$ftp_user_pass 	= 'Esds##1234$';
		
		// remote server directory path
		$local_server_dir_path = '/home/supp0rttest/public_html/uploads/cronfiles_pg/'.$current_date.'/';
		
		// connect using basic FTP
		$conn = ftp_connect($ftp_host) or die("Could not connect to remote server.");
		
		// login to FTP -
		$login_result = @ftp_login($conn, $ftp_user_name, $ftp_user_pass);
		
		// check if FTP Connect to remote server
		if($login_result)
		{
			// send each file to FTP
			foreach($file_list as $key => $file_name)
			{
				// remote server file path
				$remote_server_dir_path = $path_list[$key];
				
				// set mode according to file type
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
		$msg .= 'Photo and Data Sync to IIBF FTP completed for the following files: <br><br>';
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
		$subject = 'Photo and Data Sync to IIBF FTP completed';
		
		// email receipient list -
		//$recipient_list = array('bhagwan.sahane@esds.co.in', 'prafull.tupe@esds.co.in', 'pawansing.pardeshi@esds.co.in', 'bhushan.amrutkar@esds.co.in', 'roopal.agrawal@esds.co.in', 'anilr@esds.co.in', 'dattatreya@iibf.org.in', 'anishrivastava@iibf.org.in', 'sgbhatia@iibf.org.in');
		
	$recipient_list = array('iibfdevp@esds.co.in', 'dattatreya@iibf.org.in', 'sgbhatia@iibf.org.in');
		
		$attachment_filename = 'logs_'.$current_date.'.txt';
		$attachment_path = './uploads/cronfiles_pg/'.$current_date.'/' . $attachment_filename;
		
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