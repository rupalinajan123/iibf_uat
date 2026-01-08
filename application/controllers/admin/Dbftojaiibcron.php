<?php
/*
 	* Controller Name	:	Dbftojaiibcron File Generation
 	* Created By		:	Priyanka D
 	* Created Date		:	21-02-2024
*/

defined('BASEPATH') OR exit('No direct script access allowed');
class Dbftojaiibcron extends CI_Controller {
			//exit;
	public function __construct(){
		parent::__construct();
		
		$this->load->model('UserModel');
		$this->load->model('Master_model');
		
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->model('log_model');
		
		/* File Path */
		define('MEM_FILE_PATH','/fromweb/images/newmem/');
		define('CSC_MEM_FILE_PATH','/fromweb/images/newmem/');
		define('DRA_FILE_PATH','/fromweb/images/dra/');
		define('MEM_FILE_EDIT_PATH','/fromweb/images/edit/');
		define('MEM_FILE_RENEWAL_PATH','/fromweb/images/renewal/');
		define('DIGITAL_EL_MEM_FILE_PATH','/fromweb/images/newmem/');
		define('SCE_FILE_PATH','/fromweb/images/scribe/');
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}
	
	public function ci_sessoin_delete()
	{
		$yesterday = date('Y-m-d', strtotime("- 2 day"));
		
		//$this->db->where('DATE(FROM_UNIXTIME(timestamp))', $yesterday);
		//$this->Master_model->getRecords('ci_sessions');
		//$this->db->delete('ci_sessions');
		//echo ">>".$this->db->last_query();
		//$this->db->delete('ci_sessions');
		//SELECT date(FROM_UNIXTIME(timestamp)) FROM `ci_sessions` where date(FROM_UNIXTIME(timestamp)) BETWEEN '2020-01-01' and '2020-08-31'
		
		//$query = $this->db->query('OPTIMIZE TABLE ci_sessions');
	}

	
	/* Exam Application Cron*/
	public function logdata()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Candidate DBFTOJAIIB Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "dbftojaiib_cand_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Candidate dbftojaiib Details Cron Start - ".$start_time." *********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
	
			
			$select = 'DISTINCT(b.transaction_no),,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,dbj.certificate_number,dbj.mobile,dbj.certificate_number,dbj.certificate_date,c.firstname,c.email,dbj.dbf_regnumber,e.invoice_image,e.exam_code,e.exam_period,m.medium_code,e.center_code';
			$this->db->join('payment_transaction b','b.ref_id = dbj.id','LEFT');
			$this->db->join('member_registration c','c.regnumber=dbj.regnumber','LEFT'); 
			$this->db->join('exam_invoice e','b.id = e.pay_txn_id','LEFT');
			$this->db->join('medium_master m','e.exam_code = m.exam_code','LEFT');
			$this->db->join('center_master cm','e.exam_code = cm.exam_name','LEFT');
			/*$can_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'status'=>1,'c.isactive'=>'1','c.isdeleted'=>0,'a.pay_status'=>1,'dbj.pay_status'=>1,'pay_type'=>25),$select);*/
			$can_exam_data = $this->Master_model->getRecords('dbftojaiib_registrations dbj',array('status'=>1,'c.isactive'=>'1','c.isdeleted'=>0,'dbj.pay_status'=>1,'pay_type'=>25,'m.exam_period'=>999),$select);
			
			echo $this->db->last_query();
			if(count($can_exam_data)) 
			{
				
				$i = 1;
				$exam_cnt = 0;
				foreach($can_exam_data as $exam)
				{
					$data = '';
					
					{
						$part_no=1;
						$elected_sub_code=$place_of_work=$state_place_of_work=$scribe_flag=$elearning_flag=$sub_el_count=$optFlg=$pin_code_place_of_work='';
						$trans_date = '';
						if($exam['date'] != '0000-00-00')
						{
							$trans_date = date('d-M-Y',strtotime($exam['date']));
						}
						$data .= ''.$exam['exam_code'].'|'.$exam['exam_period'].'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['medium_code'].'|'.$exam['center_code'].'|ON||IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['amount'].'|'.$exam['transaction_no'].'|'.$scribe_flag.'|'.$elearning_flag.'|'.$sub_el_count.'|'.$optFlg.'|'.$exam['dbf_regnumber']."\n"; 
						
						
					}
					
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "Candidate DBFTOJAIIB Details File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating Candidate DBFTOJAIIB Details File.";
					
					$i++;
					$exam_cnt++;
				}
				
				fwrite($fp1, "Total DBFTOJAIIB Applications - ".$exam_cnt."\n");
				
				
			}
			else
			{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Candidate DBFTOJAIIB Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Candidate DBFTOJAIIB Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	

}
