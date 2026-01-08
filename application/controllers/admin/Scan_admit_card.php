<?php
/*
 * Controller Name	:	Scan Admit Cards
 * Created By		:	Bhagwan Sahane
 * Created Date		:	06-11-2017
 *
 * Updated By		:	Bhagwan Sahane
 * Updated Date		:	17-03-2018
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Scan_admit_card extends CI_Controller {
	public $UserID;
			
	public function __construct(){
		parent::__construct();
		
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		
		$this->load->model('log_model');
		$this->load->model('Emailsending');
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}
	
	// Admit Card
	public function index()
	{
		ini_set("memory_limit", "-1");
		
		$base_url = './uploads/admitcardpdf/';
		
		$success = array();
		$error = array();
		
		$found = array();
		$not_found = array();
		
		$admit_card_file_path = '';
		$admit_card_count = 0;
		
		$exam_code = $this->config->item('examCodeJaiib');
		$exam_period = 118;
		
		$start_limit = 0;
		$limit = 1000;
		
		$start_time = date("Y-m-d H:i:s");
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("IIBF Exam Admit Card Details Cron Execution Start", $desc);
		
		//$select = 'regnumber';
		//$cand_exam_data = $this->Master_model->getRecords('member_exam',array('pay_status'=>1,'exam_code'=>21,'exam_period'=>217),$select, array('id' => 'ASC'), $start_limit, $limit);
		
		$select = 'mem_mem_no AS regnumber';
		//$cand_exam_data = $this->Master_model->getRecords('admit_card_details_scan','',$select, array('admitcard_id' => 'ASC'), $start_limit, $limit);
		$cand_exam_data = $this->Master_model->getRecords('admit_card_details',array('exm_cd' => $exam_code, 'exm_prd' => $exam_period, 'remark' => 1),$select, array('admitcard_id' => 'ASC'), $start_limit, $limit);
		//echo "<br>SQL => ".$this->db->last_query();
		//die();
		if(count($cand_exam_data))
		{
			foreach($cand_exam_data as $exam)
			{
				$mem_no = $exam['regnumber'];
				
				$admit_card_file_path = $base_url.$exam_code.'_'.$exam_period.'_'.$mem_no.'.pdf';
				
				if(is_file($admit_card_file_path))
				{
					$found[] = $exam['regnumber'];
				}
				else
				{
					$not_found[] = $exam['regnumber'];
				}
				
				$admit_card_count++;
			}
		}
		else
		{
			$success[] = "No data found for the date";
		}
		
		// Cron End Logs
		$end_time = date("Y-m-d H:i:s");
		$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
		$desc = json_encode($result);
		$this->log_model->cronlog("IIBF Exam Admit Card Details Cron Execution End", $desc);
		
		// send email notification -
		$message = '';
		//$message .= '<br><b>Members whose Admit Cards Found :</b> <br><br>';
		//$message .= implode("<br>", $found);
		$message .= '<br><br><b>Members whose Admit Cards Not Found :</b> <br><br>';
		$message .= implode("<br>", $not_found);
		
		$message .= '<br><br><b>Total Scan Count :</b> ' . $admit_card_count;
		
		$message .= '<br><br><b>Start Limit :</b> ' . $start_limit;
		
		$message .= '<br><br><b>Start Time :</b> ' . $start_time;
		$message .= '<br><b>End Time :</b> ' . $end_time;
		
		$from_name = 'IIBF';
		$from_email = 'logs@iibf.esdsconnect.com';
		$subject = 'Admit Card Scan Report for Exam Code ' . $exam_code . ' & Period ' . $exam_period;
		
		// email receipient list -
		$recipient_list = array('bvsahane89@gmail.com', 'bhagwan.sahane@esds.co.in');
		
		$config = Array('mailtype' => 'html', 'charset' => 'utf-8','wordwrap' => TRUE);
		
		$this->email->initialize($config);
		$this->email->from($from_email, $from_name); 
		$this->email->to($recipient_list);
		$this->email->subject($subject);
		$this->email->message($message);
		
		if($this->email->send())
		{
			echo '<br>Email Sent.<br>';
			
			//$this->email->print_debugger();
			//echo $this->email->print_debugger();
		}
		else
		{
			echo '<br>Email Not Sent.<br>';
		}
		
		echo "<br>Admit Cards Scan Done !!!";
	}
}