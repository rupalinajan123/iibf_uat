<?php
	/*
		* Controller Name	:	Cron File Generation For Exam Application CSV
		* Created By		:	Priyanka Dhikale
		* Created Date		:	09-10-2025
		* Last Update 		:   09-10-2025
	*/
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Cron_ncvet extends CI_Controller {
		public $UserID; 
		
		public function __construct(){
			parent::__construct();
			
			$this->load->model('UserModel');
			$this->load->model('Master_model'); 
			//$this->UserID=$this->session->id;
			$this->load->helper('pagination_helper'); 
			$this->load->library('pagination');
			$this->load->model('log_model');
			$this->load->model('Emailsending');
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
			define('MEM_FILE_PATH','/fromweb/testscript/images/newmem/');
		}
	
		public function send_elearning_data()
		{
			ini_set("memory_limit", "-1");
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$currrow_file_flg = 0;
			$success = array();
			$error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	
			$cron_file_dir = "./uploads/ncvet/cronfiles/";
			//Cron start Logs
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("NCVET Elearning CSV Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "ncvet_elearning_".$current_date.".csv";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n***** CSV Details Cron Execution Started - ".$start_time." *********** \n");
				
				$yesterday =  date('Y-m-d', strtotime("- 1 day"));
				
				$select = 'c.*'; 
				
				$this->db->join('ncvet_payment_transaction b','b.member_regnumber=c.regnumber','LEFT'); 
				$can_exam_data = $this->Master_model->getRecords('ncvet_candidates c',array('pay_type'=>1,'status'=>1,'is_active'=>'1','is_deleted'=>0),$select);
				//,'DATE(a.created_on)'=>$yesterday
				//echo ">>".$this->db->last_query();exit;
				
				if(count($can_exam_data))
				{
					$i = 1;
					$currrow_cnt = 0;
					
					// Column headers			
					$data1 = "First Name,Middle name,Last Name,Mem. Number,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Sub Code,Subject Desc,Attempt,OptFlg,DBF Collage_institute \n";
					$currrow_file_flg = fwrite($fp, $data1);
					 
					foreach($can_exam_data as $currrow)
					{		
						$syllabus_code = $subject_description = '';
						
						
						
								$data = '';	
								
								$collage_institute='';

								
								$city_data = $this->master_model->getRecords('city_master', array('id' => $currrow['city'], 'city_delete' => '0'), 'id, city_name', array('city_name' => 'ASC'));
      
								
								$firstname = preg_replace('/[^A-Za-z\/ ]/', '', $currrow['first_name']);
								$middlename = preg_replace('/[^A-Za-z]/', '', $currrow['middle_name']);
								$lastname = preg_replace('/[^A-Za-z]/', '', $currrow['last_name']);
								
								$mobile = preg_replace('/[^0-9]/', '', $currrow['mobile_no']);
								$pincode = preg_replace('/[^0-9]/', '', $currrow['pincode']);
								$dateofbirth = date('m/d/Y',strtotime($currrow['dob']));
								$address = $currrow['address1'].' '.$currrow['address2'].' '.$currrow['address3'].' '.$currrow['district'].' '.$city_data[0]['city_name'];
								$address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
								$gender = $currrow['gender'];
								if($gender == '1') {	$gender = 'M';	} else { $gender = 'F'; }
								$collage_institute = $designation_name = $institution_name = $optFlg='';
								
								$exam_name='FRB';
								$exam_code = 1070;$subject_code = 566; $subject_description = 'Fundamentals of Retail Banking';
								$data .= ''.$firstname.','.$middlename.','.$lastname.','.$currrow['regnumber'].','.$dateofbirth.','.$gender.','.$currrow['email_id'].','.$mobile.','.$address.','.$currrow['state'].','.$pincode.','.'INDIA'.','.''.','.$institution_name.','.$designation_name.','.$exam_code.','.$exam_name.','.$subject_code.','.$subject_description.','.''.','.$optFlg.','.$collage_institute."\n";
								$currrow_file_flg = fwrite($fp, $data);
								
								if($currrow_file_flg)
								$success['cand_exam'] = "NCVET CSV Details File Generated Successfully.";
								else
								$error['cand_exam'] = "NCVET While Generating Examination CSV Details File.";
								
								$i++;
								$currrow_cnt++;
						
					}
					fwrite($fp1, "Total Exam Applications - ".$currrow_cnt."\n");
					
					// File Rename Functinality
					
					$oldPath = $cron_file_dir.$current_date."/".$file."";
					$newPath = $cron_file_dir.$current_date."/ncvet_elearning_".date('dmYhi')."_".$currrow_cnt.".csv";
					rename($oldPath,$newPath);
					
					$OldName = $file;
					$NewName = "ncvet_elearning_".date('dmYhi')."_".$currrow_cnt.".csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $currrow_cnt,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('ncvet_cron_csv', $insert_info, true);
				}
				else
				{
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: ".$yesterday." \n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/".$file."";
					$newPath = $cron_file_dir.$current_date."/ncvet_elearning_".date('dmYhi')."_0.csv";
					rename($oldPath,$newPath);
					
					$OldName = $file;
					$NewName = "ncvet_elearning_".date('dmYhi')."_0.csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('ncvet_cron_csv', $insert_info, true);
					
					$success[] = "No data found for the date";
				}
				fclose($fp);
				// Cron End Logs
				$end_time = date("Y-m-d H:i:s");
				$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
				$desc = json_encode($result);
				$this->log_model->cronlog("NCVET CSV Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."***** NCVET CSV Details Cron Execution End ".$end_time." ******"."\n");
				fclose($fp1);
			}
			//echo'ok working'; 
		}
		
		
		

	}
