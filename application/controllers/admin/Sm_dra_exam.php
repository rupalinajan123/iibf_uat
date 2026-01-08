<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Sm_dra_exam extends CI_Controller 
	{
		
		public function __construct()
		{
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
			
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
		}
		
		/*  DRA Exam Data */
		public function dra_exam()
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
			//$this->log_model->cronlog("DRA Candidate Exam Details Cron Execution Start", $desc);
			
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "dra_exam_cand_report_sm_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_sm_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n********** DRA Candidate Exam Details Cron Start - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day")); 
				
				//$this->db->where("( DATE(date) = '".$yesterday."'  OR DATE(updated_date) = '".$yesterday."') AND status = 1");
				$this->db->where("DATE(updated_date) = '".$yesterday."' AND status = 1");
				$dra_payment = $this->Master_model->getRecords('dra_payment_transaction');
				
				if(count($dra_payment))
				{
					
					$data = '';
					$i = 1;
					$exam_cnt = 0;
					foreach($dra_payment as $payment)
					{
						$this->db->join('dra_member_exam b','a.memexamid = b.id','LEFT');
						$mem_exam_data = $this->Master_model->getRecords('dra_member_payment_transaction a',array('ptid'=>$payment['id']));
						if(count($mem_exam_data))
						{
							foreach($mem_exam_data as $exam)
							{
								$reg_num = '';
								$reg_type = '';
								$member = $this->Master_model->getRecords('dra_members',array('regid'=>$exam['regid']),'regnumber,registrationtype');
								if(count($member))
								{
									$reg_num = $member[0]['regnumber'];
									$reg_type = $member[0]['registrationtype'];
								}
								
								$syllabus_code = '';
								$part_no = '';
								$subject_data = $this->Master_model->getRecords('dra_subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
								if(count($subject_data)>0)
								{
									$syllabus_code = $subject_data[0]['syllabus_code'];
									$part_no = $subject_data[0]['part_no'];
								}
								$exam_mode = 'O';
								if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
								else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
								else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
								
								//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,	TRNG_INS_CD,trng_from,TRNG_TO,EXAM_DATE
								
								//print_r($exam);
								
								/*$trans_date = '';
									if($payment['created_date'] != '0000-00-00 00:00:00')
									{
									$trans_date = date('Y-m-d H:i:s',strtotime($payment['created_date']));
								}*/
								
								$trans_date = '';
								$transaction_no = '';
								if($payment['gateway']==1)
								{	
									$transaction_no = $payment['UTR_no'];
									$trans_date = date('Y-m-d H:i:s',strtotime($payment['updated_date']));
								}
								else if($payment['gateway']==2)
								{	
									$transaction_no = $payment['transaction_no'];
									$trans_date = date('Y-m-d H:i:s',strtotime($payment['created_date']));	
								}
								
								$training_from = '';
								if($exam['training_from'] != '0000-00-00')
								{
									$training_from = date('d-M-y',strtotime($exam['training_from']));
								}
								
								$training_to = '';
								if($exam['training_to'] != '0000-00-00')
								{
									$training_to = date('d-M-y',strtotime($exam['training_to']));
								}
								
								$data .= ''.$exam['exam_code'].'|'.$exam['exam_period'].'|'.$reg_num.'|'.$reg_type.'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$transaction_no.'|'.$trans_date.'|'.$payment['amount'].'|'.$transaction_no.'|'.$payment['inst_code'].'|'.$training_from.'|'.$training_to.'|'.$exam['fee_paid_flag'].'|'.$exam['exam_date']."\n";
								
								$exam_cnt++;
							}
						}
						$i++;
					}
					
					fwrite($fp1, "Total DRA Exam Applications - ".$exam_cnt."\n");
					
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
					$success['DRA_exam'] = "DRA Candidate Exam Details File Generated Successfully."; 
					else
					$error['DRA_exam'] = "Error While Generating DRA Candidate Exam Details File.";
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
				//$this->log_model->cronlog("DRA Candidate Exam Details Cron End", $desc);
				
				fwrite($fp1, "\n"."********** DRA Candidate Exam Details Cron End ".$end_time." ***********"."\n");
				fclose($fp1);
			}
		}
	} ?>		