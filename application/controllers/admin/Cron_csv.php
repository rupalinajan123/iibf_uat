<?php
	/*
		* Controller Name	:	Cron File Generation For Exam Application CSV
		* Created By		:	Bhushan Amrutkar
		* Created Date		:	20-03-2018
		* Last Update 		:   21-04-2020
	*/
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Cron_csv extends CI_Controller {
		public $UserID; 
		
		public function __construct(){
			parent::__construct();
			/*if($this->session->userdata('roleid')!=1){
				redirect('admin/Login');
			}*/	
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
		/* CSV Cron For TATA/MPS Vendor : 30/March/2018 : Bhushan */  
		/* exam_csv_hold_for_sometime >> Now Active for JAIIB and CAIIB > 12-10-2020 */
		public function exam_csv()
		{exit;
			
			ini_set("memory_limit", "-1");
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$exam_file_flg = 0;
			$success = array();
			$error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	
			$cron_file_dir = "./uploads/cronCSV/";
			//Cron start Logs
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("Examination CSV Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "iibfportal_".$current_date.".csv";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n***** Examination CSV Details Cron Execution Started - ".$start_time." *********** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2017-11-06';
				
				$exam_code = array($this->config->item('examCodeJaiib'),$this->config->item('examCodeCaiib'),'62',$this->config->item('examCodeCaiibElective63'),'64','65','66','67',$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),'72',$this->config->item('examCodeDBF'),'58','580','81','5800','810','58000',$this->config->item('examCodeSOB'));
				
				$select = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id';
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
				$this->db->where_in('a.exam_code', $exam_code);
				$can_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'DATE(a.created_on)'=>$yesterday),$select);
				
				//echo "<br>SQL => ".$this->db->last_query(); //exit;
				
				if(count($can_exam_data))
				{
					$i = 1;
					$exam_cnt = 0;
					
					// Column headers			
					$data1 = "First Name,Middle name,Last Name,Mem. Number,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt \n";
					$exam_file_flg = fwrite($fp, $data1);
					
					foreach($can_exam_data as $exam)
					{
						$data = '';					
						$syllabus_code = '';
						$subject_description = '';
						
						// get admit card details for this member by mem_exam_id
						$mem_exam_id = $exam['mem_exam_id'];
						$selectCol = 'DISTINCT(exm_cd),exm_prd,sub_cd,sub_dsc';
						$this->db->where('remark', 1);
						$this->db->group_by('exm_cd');
						$admit_card_details_arr = $this->Master_model->getRecords('admit_card_details',array('mem_exam_id' => $mem_exam_id),$selectCol);
						
						$subject_code = $subject_description = '';
						if(count($admit_card_details_arr))
						{	
							foreach($admit_card_details_arr as $admit_card_data)
							{
								$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period'],'group_code'=>'E','subject_code' =>$exam['elected_sub_code']),'',array('id'=>'DESC'),0,1);
								if(count($subject_data)>0)
								{
									$subject_code = $subject_data[0]['subject_code'];
									$subject_description = $subject_data[0]['subject_description'];
									$subject_description = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $subject_description);
								}
								
								$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = '';
								
								$exam_code = '';
								if($exam['exam_code'] != '' && $exam['exam_code'] != 0){
									$ex_code = $this->master_model->getRecords('exam_activation_master',array('exam_code'=>$exam['exam_code']));
									if(count($ex_code)){
										if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0)
										{	$exam_code = $ex_code[0]['original_val'];	}
										else
										{	$exam_code = $exam['exam_code'];	}
									}
								}
								else{ $exam_code = $exam['exam_code'];	}
								
								$elected_sub_code = '';
								if($exam_code == $this->config->item('examCodeCaiib')){ $elected_sub_code = $exam['elected_sub_code'];	}
								
								$dateofbirth = date('m/d/Y',strtotime($exam['dateofbirth']));
								$address = $exam['address1'].' '.$exam['address2'].' '.$exam['address3'].' '.$exam['address4'].' '.$exam['district'].' '.$exam['city'];
								$address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
								
								$gender = $exam['gender'];
								if($gender == 'male') {	$gender = 'M';	} else { $gender = 'F'; }
								
								$designation_name = '';
								$designation  = $this->master_model->getRecords('designation_master');
								if(count($designation)){
									foreach($designation as $designation_row){
										if($exam['designation']==$designation_row['dcode']){
										$designation_name = $designation_row['dname'];}
									} 
								}	
								$designation_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
								
								$institution_name = '';
								$institution_master = $this->master_model->getRecords('institution_master');	
								if(count($institution_master)){
									foreach($institution_master as $institution_row){ 	
										if($exam['associatedinstitute']==$institution_row['institude_id']){
										$institution_name = $institution_row['name'];}
									}
								}
								$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
								
								//$firstname = preg_replace('/[^A-Za-z]/', '', $exam['firstname']);
								$firstname = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
								$middlename = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
								$lastname = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
								$mobile = preg_replace('/[^0-9]/', '', $exam['mobile']);
								$pincode = preg_replace('/[^0-9]/', '', $exam['pincode']);
								
								$sub_cd = $admit_card_data['sub_cd'];
								$sub_dsc = $admit_card_data['sub_dsc'];
								
								$exam_name = '';
								$exam_arr = array($this->config->item('examCodeJaiib')=>'JAIIB',$this->config->item('examCodeCaiib')=>'CAIIB','62'=>'CORPORATE BANKING',$this->config->item('examCodeCaiibElective63')=>'RURAL BANKING','64'=>'INTERNATIONAL BANKING','65'=>'RETAIL BANKING','66'=>'CO-OPERATIVE BANKING','67'=>'FINANCIAL ADVISING',$this->config->item('examCodeCaiibElective68')=>'HUMAN RESOURCES MANAGEMENT',$this->config->item('examCodeCaiibElective69')=>'INFORMATION TECHNOLOGY',$this->config->item('examCodeCaiibElective70')=>'RISK MANAGEMENT',$this->config->item('examCodeCaiibElective71')=>'CENTRAL BANKING','72'=>'TREASURY MANAGEMENT',$this->config->item('examCodeDBF')=>'Diploma in Banking & Finance','58'=>'CUST. SER & BKG CODES AND STAN','580'=>'CUST. SER & BKG CODES AND STAN','81'=>'CERTIFICATE IN RISK IN FINANCIAL SERVICES','5800'=>'CUST. SER & BKG CODES AND STAN','810'=>'CERTIFICATE IN RISK IN FINANCIAL SERVICES','58000'=>'CUST. SER & BKG CODES AND STAN',$this->config->item('examCodeSOB')=>'SPECIALIST OFFICERS OF BANKS');
								foreach($exam_arr as $k => $val){
									if($exam_code == $k) { $exam_name = $val; }
								}
								
								if($exam_code == $this->config->item('examCodeJaiib') || $exam_code == '62' || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == '64' || $exam_code == '65' || $exam_code == '66' || $exam_code == '67' || $exam_code == $this->config->item('examCodeCaiibElective68') || $exam_code == $this->config->item('examCodeCaiibElective69') || $exam_code == $this->config->item('examCodeCaiibElective70') || $exam_code == $this->config->item('examCodeCaiibElective71') || $exam_code == '72' || $exam_code == $this->config->item('examCodeDBF') || $exam_code == '58' || $exam_code == '580' || $exam_code == '81' || $exam_code == '5800' || $exam_code == '810' || $exam_code == '58000' || $exam_code == $this->config->item('examCodeSOB'))
								{
									$subject_code = '';
									$subject_description = '';
								}
								
								//First Name|Middle name|Last Name|Mem. Number|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession	|Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt
								$data .= ''.$firstname.','.$middlename.','.$lastname.','.$exam['regnumber'].','.$dateofbirth.','.$gender.','.$exam['email'].','.$mobile.','.$address.','.$exam['state'].','.$pincode.','.'INDIA'.','.''.','.$institution_name.','.$designation_name.','.$exam_code.','.$exam_name.','.$subject_code.','.$subject_description.','.''."\n";
								
								$exam_file_flg = fwrite($fp, $data);
								
								if($exam_file_flg)
								$success['cand_exam'] = "Examination CSV Details File Generated Successfully.";
								else
								$error['cand_exam'] = "Error While Generating Examination CSV Details File.";
								
								$i++;
								$exam_cnt++;
							}
						}
					}
					fwrite($fp1, "Total Exam Applications - ".$exam_cnt."\n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/iibfportal_".$current_date.".csv";
					$newPath = $cron_file_dir.$current_date."/iibfportal_".date('dmYhi')."_".$exam_cnt.".csv";
					rename($oldPath,$newPath);
					
					$OldName = "iibfportal_".$current_date.".csv";
					$NewName = "iibfportal_".date('dmYhi')."_".$exam_cnt.".csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_csv', $insert_info, true);
					
				}
				else
				{
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: ".$yesterday." \n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/iibfportal_".$current_date.".csv";
					$newPath = $cron_file_dir.$current_date."/iibfportal_".date('dmYhi')."_0.csv";
					rename($oldPath,$newPath);
					
					$OldName = "iibfportal_".$current_date.".csv";
					$NewName = "iibfportal_".date('dmYhi')."_0.csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_csv', $insert_info, true);
					
					$success[] = "No data found for the date";
				}
				fclose($fp);
				
				// Cron End Logs
				$end_time = date("Y-m-d H:i:s");
				$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
				$desc = json_encode($result);
				$this->log_model->cronlog("Examination CSV Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."***** Examination CSV Details Cron Execution End ".$end_time." ******"."\n");
				fclose($fp1);
			}
		}
		
		
		/* CSV Cron For MPS Vendor : 15/July/2020 : Bhushan */
    public function mps_csv()
    { exit;
			ini_set("memory_limit", "-1");
			$dir_flg        = 0;
			$parent_dir_flg = 0;
			$exam_file_flg  = 0;
			$success        = array();
			$error          = array();
			$start_time     = date("Y-m-d H:i:s");
			$current_date   = date("Ymd");
			$cron_file_dir  = "./uploads/cronCSV_mps/";
			$result         = array(
			"success" => "",
			"error" => "",
			"Start Time" => $start_time,
			"End Time" => ""
			);
			$desc           = json_encode($result);
			$this->log_model->cronlog("MPS CSV Cron Execution Start", $desc);
			if (!file_exists($cron_file_dir . $current_date)) {
				$parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
			}
			if (file_exists($cron_file_dir . $current_date)) {
				$cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
				$file           = "iibfportal_" . $current_date . ".csv";
				$fp             = fopen($cron_file_path . '/' . $file, 'w');
				$file1          = "logs_" . $current_date . ".txt";
				$fp1            = fopen($cron_file_path . '/' . $file1, 'a');
				fwrite($fp1, "\n******** MPS CSV Cron Execution Started - " . $start_time . " ******** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				
				//$yesterday = '2019-04-09';
				
				$exam_code_arr = array('1007','1010'); 
				
				$select    = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date,a.elearning_flag';
				$this->db->where_in('a.exam_code', $exam_code_arr);
				$this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
				$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
				$can_exam_data = $this->Master_model->getRecords('member_exam a', array(
				'pay_type' => 2,
				'status' => 1,
				'isactive' => '1',
				'isdeleted' => 0,
				'pay_status' => 1,
				'elearning_flag' => 'Y',
				'DATE(a.created_on)' => $yesterday
				), $select);
				
				//echo $this->db->last_query();
				
				if (count($can_exam_data)) {
					$i             = 1;
					$exam_cnt      = 0;
					// Column headers for CSV            
					$data1         = "First Name,Middle name,Last Name,Mem. Number,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt,Registration Date \n";
					$exam_file_flg = fwrite($fp, $data1);
					foreach ($can_exam_data as $exam) {
						$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $registration_date = $data = $syllabus_code = $subject_description = $subject_code = $subject_description = $institution_name = $designation_name = $exam_code = $exam_name = '';
						
						$exam_code = $exam['exam_code'];
						//$dateofbirth       = date('m/d/Y', strtotime($exam['dateofbirth']));
						$registration_date = date('m/d/Y', strtotime($exam['registration_date']));
						$dateofbirth       = date('d-m-Y', strtotime($exam['dateofbirth']));
						
						$address           = $exam['address1'] . ' ' . $exam['address2'] . ' ' . $exam['address3'] . ' ' . $exam['address4'] . ' ' . $exam['district'] . ' ' . $exam['city'];
						$address           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
						$gender            = $exam['gender'];
						if ($gender == 'male') {
							$gender = 'M';
							} else {
							$gender = 'F';
						}
						$designation = $this->master_model->getRecords('designation_master');
						if (count($designation)) {
							foreach ($designation as $designation_row) {
								if ($exam['designation'] == $designation_row['dcode']) {
									$designation_name = $designation_row['dname'];
								}
							}
						}
						$designation_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
						$institution_master = $this->master_model->getRecords('institution_master');
						if (count($institution_master)) {
							foreach ($institution_master as $institution_row) {
								if ($exam['associatedinstitute'] == $institution_row['institude_id']) {
									$institution_name = $institution_row['name'];
								}
							}
						}
						$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
						$firstname        = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
						$middlename       = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
						$lastname         = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
						$mobile           = preg_replace('/[^0-9]/', '', $exam['mobile']);
						$pincode          = preg_replace('/[^0-9]/', '', $exam['pincode']);
						$exam_arr         = array(
						'1007' => 'CERTIFICATE IN RISK IN FINANCIAL SERVICES LEVEL 1',
						'1010' => 'CERTIFICATE EXAM. IN CUSTOMER SERVICE & BANKING CODES AND STANDARDS'						
						);
						foreach ($exam_arr as $k => $val) {
							if ($exam_code == $k) {
								$exam_name = $val;
							}
						}
						//First Name|Middle name|Last Name|Mem. Number|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession    |Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt|Registration Date
						$data .= '' . $firstname . ',' . $middlename . ',' . $lastname . ',' . $exam['regnumber'] . ',' . $dateofbirth . ',' . $gender . ',' . $exam['email'] . ',' . $mobile . ',' . $address . ',' . $exam['state'] . ',' . $pincode . ',' . 'INDIA' . ',' . '' . ',' . $institution_name . ',' . $designation_name . ',' . $exam_code . ',' . $exam_name . ',' . $subject_code . ',' . $subject_description . ',' . '' . ',' . $registration_date . "\n";
						$exam_file_flg = fwrite($fp, $data);
						if ($exam_file_flg)
						$success['cand_exam'] = "MPS CSV File Generated Successfully.";
						else
						$error['cand_exam'] = "Error While Generating MPS CSV File.";
						$i++;
						$exam_cnt++;
					}
					fwrite($fp1, "Total Applications - " . $exam_cnt . "\n");
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_csv_mps', $insert_info, true);
					} else {
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_0.csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_0.csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_csv_mps', $insert_info, true);
					$success[] = "No data found for the date";
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
				$this->log_model->cronlog("MPS CSV Cron Execution End", $desc);
				fwrite($fp1, "\n" . "******** MPS CSV Cron Execution End " . $end_time . " ********" . "\n");
				fclose($fp1);
			}
		}
		
		
		/* CSV Cron For kesdee Vendor : 19/April/2018 : Bhushan */
		public function exam_csv_kesdee()
		{
			ini_set("memory_limit", "-1");
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$exam_file_flg = 0;
			$success = array();
			$error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");
			$cron_file_dir = "./uploads/cronCSV_kesdee/";
			//Cron start Logs
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("Examination CSV Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "iibfportal_".$current_date.".csv";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************ Examination CSV Details Cron Execution Started - ".$start_time." *********** \n");
				
				$mem_arr = $mem_arr1 = array();
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2019-11-01';
				
				$exam_code = array('34','340','3400','34000');
				$select = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id';
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
				$this->db->where_in('a.exam_code', $exam_code);
				$can_exam_data1 = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'DATE(a.created_on)'=>$yesterday),$select);
				
				//echo "<br><br> 1 >>".$this->db->last_query();
				
				$exam_code = array('151');
				$select = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.registrationtype,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.exam_fee';
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
				$this->db->where_in('a.exam_code', $exam_code);
				$can_exam_data2 = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'DATE(a.created_on)'=>$yesterday),$select);
				//echo "<br><br> 2 >>".$this->db->last_query();
				
				$current_dates = date('Y-m-d');
				//$current_dates = '22019-11-01';
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2022-01-04';
				$select = 'group_code, cs_tot, member_category';
				$this->db->where_in('exam_code', $exam_code);
				$this->db->where("('$current_dates' BETWEEN fr_date AND `to_date`)");
				$this->db->where("('$yesterday' BETWEEN fr_date AND `to_date`)");
				$fee_master = $this->Master_model->getRecords('fee_master',array('exam_code'=>'151','fee_delete'=>0),$select);
				//echo "<br><br> 3 >>".$this->db->last_query();
				
				if(count($fee_master)<=0)
				{
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					//$yesterday = '2019-11-01';
					
					$select = 'group_code, cs_tot, member_category';
					$this->db->where_in('exam_code', $exam_code);
					$this->db->where("('$yesterday' BETWEEN fr_date AND `to_date`)");
					$fee_master = $this->Master_model->getRecords('fee_master',array('exam_code'=>'151','fee_delete'=>0),$select);
				}
				
				$chk_fee_data = array();
				if(count($fee_master) > 0)
				{
					foreach($fee_master as $fee_res)
					{
						if($fee_res['group_code'] == 'R')
						{
							$fee_res['group_code'] = 'B1_1';
						}
						$chk_fee_data[$fee_res['member_category'].'_'.$fee_res['group_code']] = $fee_res;
					}
				}
				
				//echo '<pre>'; print_r($chk_fee_data); echo '</pre>';//exit;
				if(count($can_exam_data2))
				{ $i= 0;
					foreach($can_exam_data2 as $row)
					{
						$this->db->limit(1);
						$group_code_data = $this->Master_model->getRecords('eligible_master',array('exam_code'=>$row['exam_code'],'eligible_period'=>$row['exam_period'],'member_no'=>$row['regnumber']),'app_category');
						
						$key_group_code = 'B1_1';
						if(count($group_code_data) > 0)
						{
							if($group_code_data[0]['app_category'] == "R")
							{
								$key_group_code = 'B1_1';
							}
							else
							{
								$key_group_code = $group_code_data[0]['app_category'];
							}
						}
						
						$mem_registrationtype = $row['registrationtype'];
						$chk_fee_amt = 0;
						if(array_key_exists($mem_registrationtype."_".$key_group_code,$chk_fee_data))
						{
							$chk_fee_amt = $chk_fee_data[$mem_registrationtype."_".$key_group_code]['cs_tot'];
						}
						
						//if($fee_master[0]['cs_tot']==$row['exam_fee'])
						if($chk_fee_amt==$row['exam_fee'])
						{
							$mem_arr[$i]['transaction_no'] = $row['transaction_no'];
							$mem_arr[$i]['exam_code'] = $row['exam_code'];
							$mem_arr[$i]['firstname'] = $row['firstname'];
							$mem_arr[$i]['middlename'] = $row['middlename'];
							$mem_arr[$i]['lastname'] = $row['lastname'];
							$mem_arr[$i]['regnumber'] = $row['regnumber'];
							$mem_arr[$i]['dateofbirth'] = $row['dateofbirth'];
							$mem_arr[$i]['gender'] = $row['gender'];
							$mem_arr[$i]['email'] = $row['email'];
							$mem_arr[$i]['stdcode'] = $row['stdcode'];
							$mem_arr[$i]['office_phone'] = $row['office_phone'];
							$mem_arr[$i]['mobile'] = $row['mobile'];
							$mem_arr[$i]['address1'] = $row['address1'];
							$mem_arr[$i]['address2'] = $row['address2'];
							$mem_arr[$i]['address3'] = $row['address3'];
							$mem_arr[$i]['address4'] = $row['address4'];
							$mem_arr[$i]['district'] = $row['district'];
							$mem_arr[$i]['city'] = $row['city'];
							$mem_arr[$i]['state'] = $row['state'];
							$mem_arr[$i]['pincode'] = $row['pincode'];
							$mem_arr[$i]['associatedinstitute'] = $row['associatedinstitute'];
							$mem_arr[$i]['designation'] = $row['designation'];
							$mem_arr[$i]['exam_period'] = $row['exam_period'];
							$mem_arr[$i]['elected_sub_code'] = $row['elected_sub_code'];
							$mem_arr[$i]['editedon'] = $row['editedon'];
							$mem_arr[$i]['examination_date'] = $row['examination_date'];
							$mem_arr[$i]['mem_exam_id'] = $row['mem_exam_id'];
						}
						$i++;
					}
					
				}
				
				//echo '<pre>';
				//print_r($mem_arr);
				$can_exam_data = array_merge($can_exam_data1,$mem_arr);
				
				//echo "<br>SQL => ".$this->db->last_query(); //exit;
				//echo '<pre>';
				//print_r($can_exam_data);
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$exam_code_rpe = array('1002','1014');
				$select = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.elearning_flag';
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
				$this->db->where_in('a.exam_code', $exam_code_rpe);
				$can_exam_data3 = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'DATE(a.created_on)'=>$yesterday,'a.elearning_flag'=>'Y'),$select);
				
				if(count($can_exam_data3))
				{ $i= 0;
					foreach($can_exam_data3 as $row)
					{
						
						$mem_arr1[$i]['transaction_no'] = $row['transaction_no'];
						$mem_arr1[$i]['exam_code'] = $row['exam_code'];
						$mem_arr1[$i]['firstname'] = $row['firstname'];
						$mem_arr1[$i]['middlename'] = $row['middlename'];
						$mem_arr1[$i]['lastname'] = $row['lastname'];
						$mem_arr1[$i]['regnumber'] = $row['regnumber'];
						$mem_arr1[$i]['dateofbirth'] = $row['dateofbirth'];
						$mem_arr1[$i]['gender'] = $row['gender'];
						$mem_arr1[$i]['email'] = $row['email'];
						$mem_arr1[$i]['stdcode'] = $row['stdcode'];
						$mem_arr1[$i]['office_phone'] = $row['office_phone'];
						$mem_arr1[$i]['mobile'] = $row['mobile'];
						$mem_arr1[$i]['address1'] = $row['address1'];
						$mem_arr1[$i]['address2'] = $row['address2'];
						$mem_arr1[$i]['address3'] = $row['address3'];
						$mem_arr1[$i]['address4'] = $row['address4'];
						$mem_arr1[$i]['district'] = $row['district'];
						$mem_arr1[$i]['city'] = $row['city'];
						$mem_arr1[$i]['state'] = $row['state'];
						$mem_arr1[$i]['pincode'] = $row['pincode'];
						$mem_arr1[$i]['associatedinstitute'] = $row['associatedinstitute'];
						$mem_arr1[$i]['designation'] = $row['designation'];
						$mem_arr1[$i]['exam_period'] = $row['exam_period'];
						$mem_arr1[$i]['elected_sub_code'] = $row['elected_sub_code'];
						$mem_arr1[$i]['editedon'] = $row['editedon'];
						$mem_arr1[$i]['examination_date'] = $row['examination_date'];
						$mem_arr1[$i]['mem_exam_id'] = $row['mem_exam_id'];
						
						$i++;
					}
					
				}
				
				$can_exam_data4 = array_merge($can_exam_data,$mem_arr1);
				echo '<pre>'; print_r($can_exam_data4); echo '</pre>';
				
				if(count($can_exam_data4))
				{
					$i = 1;
					$exam_cnt = 0;
					
					// Column headers			
					$data1 = "First Name,Middle name,Last Name,Mem. Number,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt \n";
					$exam_file_flg = fwrite($fp, $data1);
					
					foreach($can_exam_data4 as $exam)
					{
						$data = '';					
						$syllabus_code = '';
						$subject_description = '';
						
						// get admit card details for this member by mem_exam_id
						$mem_exam_id = $exam['mem_exam_id'];
						$selectCol = 'DISTINCT(exm_cd),exm_prd,sub_cd,sub_dsc';
						$this->db->where('remark', 1);
						$this->db->group_by('exm_cd');
						$admit_card_details_arr = $this->Master_model->getRecords('admit_card_details',array('mem_exam_id' => $mem_exam_id),$selectCol);
						
						$subject_code = $subject_description = '';
						if(count($admit_card_details_arr))
						{	
							foreach($admit_card_details_arr as $admit_card_data)
							{
								$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period'],'group_code'=>'E','subject_code' =>$exam['elected_sub_code']),'',array('id'=>'DESC'),0,1);
								if(count($subject_data)>0)
								{
									$subject_code = $subject_data[0]['subject_code'];
									$subject_description = $subject_data[0]['subject_description'];
									$subject_description = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $subject_description);
								}
								
								$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = '';
								
								$exam_code = '';
								if($exam['exam_code'] != '' && $exam['exam_code'] != 0){
									
									$ex_code = $this->master_model->getRecords('exam_activation_master',array('exam_code'=>$exam['exam_code']));
									if(count($ex_code)){
										if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0)
										{	$exam_code = $ex_code[0]['original_val'];	}
										else
										{	$exam_code = $exam['exam_code'];	}
									}
								}
								else{ $exam_code = $exam['exam_code'];	}
								
								$elected_sub_code = '';
								if($exam_code == $this->config->item('examCodeCaiib')){ $elected_sub_code = $exam['elected_sub_code'];	}
								
								$dateofbirth = date('m/d/Y',strtotime($exam['dateofbirth']));
								$address = $exam['address1'].' '.$exam['address2'].' '.$exam['address3'].' '.$exam['address4'].' '.$exam['district'].' '.$exam['city'];
								$address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
								
								$gender = $exam['gender'];
								if($gender == 'male') {	$gender = 'M';	} else { $gender = 'F'; }
								
								$designation_name = '';
								$designation  = $this->master_model->getRecords('designation_master');
								if(count($designation)){
									foreach($designation as $designation_row){
										if($exam['designation']==$designation_row['dcode']){
										$designation_name = $designation_row['dname'];}
									} 
								}	
								$designation_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
								
								$institution_name = '';
								$institution_master = $this->master_model->getRecords('institution_master');	
								if(count($institution_master)){
									foreach($institution_master as $institution_row){ 	
										if($exam['associatedinstitute']==$institution_row['institude_id']){
										$institution_name = $institution_row['name'];}
									}
								}
								$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
								
								//$firstname = preg_replace('/[^A-Za-z]/', '', $exam['firstname']);
								$firstname = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
								$middlename = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
								$lastname = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
								$mobile = preg_replace('/[^0-9]/', '', $exam['mobile']);
								$pincode = preg_replace('/[^0-9]/', '', $exam['pincode']);
								
								$sub_cd = $admit_card_data['sub_cd'];
								$sub_dsc = $admit_card_data['sub_dsc'];
								
								$exam_name = '';
								$exam_arr = array('34'=>'ANTI-MONEY LAUNDERING AND KNOW YOUR CUSTOMER','340'=>'ANTI-MONEY LAUNDERING AND KNOW YOUR CUSTOMER','3400'=>'ANTI-MONEY LAUNDERING AND KNOW YOUR CUSTOMER','151'=>'DIPLOMA IN TREASURY INVESTMENT AND RISK MANAGEMENT','34000'=>'ANTI-MONEY LAUNDERING AND KNOW YOUR CUSTOMER','1002'=>'ANTI-MONEY LAUNDERING AND KNOW YOUR CUSTOMER','1014'=>'CERTIFICATE IN INTERNATIONAL TRADE FINANCE');
								
								foreach($exam_arr as $k => $val){
									if($exam_code == $k) { $exam_name = $val; }
								}
								
								if($exam_code == '34' || $exam_code == '340' || $exam_code == '3400' || $exam_code == '151' || $exam_code == '34000' || $exam_code == '1002' || $exam_code == '1014')
								{
									$subject_code = '';
									$subject_description = '';
								}
								
								//First Name|Middle name|Last Name|Mem. Number|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession	|Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt
								$data .= ''.$firstname.','.$middlename.','.$lastname.','.$exam['regnumber'].','.$dateofbirth.','.$gender.','.$exam['email'].','.$mobile.','.$address.','.$exam['state'].','.$pincode.','.'INDIA'.','.''.','.$institution_name.','.$designation_name.','.$exam_code.','.$exam_name.','.$subject_code.','.$subject_description.','.''."\n";
								
								$exam_file_flg = fwrite($fp, $data);
								
								if($exam_file_flg)
								$success['cand_exam'] = "Examination CSV Details File Generated Successfully.";
								else
								$error['cand_exam'] = "Error While Generating Examination CSV Details File.";
								
								$i++;
								$exam_cnt++;
							}
						}
					}
					fwrite($fp1, "Total Exam Applications - ".$exam_cnt."\n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/iibfportal_".$current_date.".csv";
					$newPath = $cron_file_dir.$current_date."/iibfportal_".date('dmYhi')."_".$exam_cnt.".csv";
					rename($oldPath,$newPath);
					
					$OldName = "iibfportal_".$current_date.".csv";
					$NewName = "iibfportal_".date('dmYhi')."_".$exam_cnt.".csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_csv_kesdee', $insert_info, true);
					
				}
				else
				{
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: ".$yesterday." \n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/iibfportal_".$current_date.".csv";
					$newPath = $cron_file_dir.$current_date."/iibfportal_".date('dmYhi')."_0.csv";
					rename($oldPath,$newPath);
					
					$OldName = "iibfportal_".$current_date.".csv";
					$NewName = "iibfportal_".date('dmYhi')."_0.csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_csv_kesdee', $insert_info, true);
					
					$success[] = "No data found for the date";
				}
				fclose($fp);
				
				// Cron End Logs
				$end_time = date("Y-m-d H:i:s");
				$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
				$desc = json_encode($result);
				$this->log_model->cronlog("Examination CSV Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."************ Examination CSV Details Cron Execution End ".$end_time." *************"."\n");
				fclose($fp1);
			}
		}
		
		
		public function exam_csv_kesdee_custom()
		{
			ini_set("memory_limit", "-1");
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$exam_file_flg = 0;
			$success = array();
			$error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");
			$cron_file_dir = "./uploads/rahultest/";
			//Cron start Logs
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("Examination CSV Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "iibfportal_".$current_date.".csv";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************ Examination CSV Details Cron Execution Started - ".$start_time." *********** \n");
				
				$mem_arr = $mem_arr1 = array();
				
				//$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$yesterday = '2022-01-04';
				
				$exam_code = array('34','340','3400','34000');
				$select = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id';
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
				$this->db->where_in('a.exam_code', $exam_code);
				$can_exam_data1 = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'DATE(a.created_on)'=>$yesterday),$select);
				
				echo "<br><br> 1 >>".$this->db->last_query(); //exit;
				
				$exam_code = array('151');
				$select = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.exam_fee';
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
				$this->db->where_in('a.exam_code', $exam_code);
				$can_exam_data2 = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'DATE(a.created_on)'=>$yesterday),$select);
				echo "<br><br> 2 >>".$this->db->last_query(); //exit;
				
				$current_dates = date('Y-m-d');
				//$current_dates = '22019-11-01';
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$select = 'cs_tot';
				$this->db->where_in('exam_code', $exam_code);
				$this->db->where("('$current_dates' BETWEEN fr_date AND `to_date`)");
				$this->db->where("('$yesterday' BETWEEN fr_date AND `to_date`)");
				$fee_master = $this->Master_model->getRecords('fee_master',array('exam_code'=>'151','group_code'=>'B1_1','fee_delete'=>0),$select);
				echo "<br><br> 3 >>".$this->db->last_query(); exit;
				
				if(count($fee_master)<=0)
				{
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					//$yesterday = '2019-11-01';
					
					$select = 'cs_tot';
					$this->db->where_in('exam_code', $exam_code);
					$this->db->where("('$yesterday' BETWEEN fr_date AND `to_date`)");
					$fee_master = $this->Master_model->getRecords('fee_master',array('exam_code'=>'151','group_code'=>'B1_1','fee_delete'=>0),$select);
				}
				
				if(count($can_exam_data2))
				{ $i= 0;
					foreach($can_exam_data2 as $row)
					{
						if($fee_master[0]['cs_tot']==$row['exam_fee'])
						{
							$mem_arr[$i]['transaction_no'] = $row['transaction_no'];
							$mem_arr[$i]['exam_code'] = $row['exam_code'];
							$mem_arr[$i]['firstname'] = $row['firstname'];
							$mem_arr[$i]['middlename'] = $row['middlename'];
							$mem_arr[$i]['lastname'] = $row['lastname'];
							$mem_arr[$i]['regnumber'] = $row['regnumber'];
							$mem_arr[$i]['dateofbirth'] = $row['dateofbirth'];
							$mem_arr[$i]['gender'] = $row['gender'];
							$mem_arr[$i]['email'] = $row['email'];
							$mem_arr[$i]['stdcode'] = $row['stdcode'];
							$mem_arr[$i]['office_phone'] = $row['office_phone'];
							$mem_arr[$i]['mobile'] = $row['mobile'];
							$mem_arr[$i]['address1'] = $row['address1'];
							$mem_arr[$i]['address2'] = $row['address2'];
							$mem_arr[$i]['address3'] = $row['address3'];
							$mem_arr[$i]['address4'] = $row['address4'];
							$mem_arr[$i]['district'] = $row['district'];
							$mem_arr[$i]['city'] = $row['city'];
							$mem_arr[$i]['state'] = $row['state'];
							$mem_arr[$i]['pincode'] = $row['pincode'];
							$mem_arr[$i]['associatedinstitute'] = $row['associatedinstitute'];
							$mem_arr[$i]['designation'] = $row['designation'];
							$mem_arr[$i]['exam_period'] = $row['exam_period'];
							$mem_arr[$i]['elected_sub_code'] = $row['elected_sub_code'];
							$mem_arr[$i]['editedon'] = $row['editedon'];
							$mem_arr[$i]['examination_date'] = $row['examination_date'];
							$mem_arr[$i]['mem_exam_id'] = $row['mem_exam_id'];
						}
						$i++;
					}
					
				}
				
				//echo '<pre>';
				//print_r($mem_arr);
				$can_exam_data = array_merge($can_exam_data1,$mem_arr);
				
				//echo "<br>SQL => ".$this->db->last_query(); //exit;
				//echo '<pre>';
				//print_r($can_exam_data);
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$exam_code_rpe = array('1002','1014');
				$select = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.elearning_flag';
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
				$this->db->where_in('a.exam_code', $exam_code_rpe);
				$can_exam_data3 = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'DATE(a.created_on)'=>$yesterday,'a.elearning_flag'=>'Y'),$select);
				
				if(count($can_exam_data3))
				{ $i= 0;
					foreach($can_exam_data3 as $row)
					{
						
						$mem_arr1[$i]['transaction_no'] = $row['transaction_no'];
						$mem_arr1[$i]['exam_code'] = $row['exam_code'];
						$mem_arr1[$i]['firstname'] = $row['firstname'];
						$mem_arr1[$i]['middlename'] = $row['middlename'];
						$mem_arr1[$i]['lastname'] = $row['lastname'];
						$mem_arr1[$i]['regnumber'] = $row['regnumber'];
						$mem_arr1[$i]['dateofbirth'] = $row['dateofbirth'];
						$mem_arr1[$i]['gender'] = $row['gender'];
						$mem_arr1[$i]['email'] = $row['email'];
						$mem_arr1[$i]['stdcode'] = $row['stdcode'];
						$mem_arr1[$i]['office_phone'] = $row['office_phone'];
						$mem_arr1[$i]['mobile'] = $row['mobile'];
						$mem_arr1[$i]['address1'] = $row['address1'];
						$mem_arr1[$i]['address2'] = $row['address2'];
						$mem_arr1[$i]['address3'] = $row['address3'];
						$mem_arr1[$i]['address4'] = $row['address4'];
						$mem_arr1[$i]['district'] = $row['district'];
						$mem_arr1[$i]['city'] = $row['city'];
						$mem_arr1[$i]['state'] = $row['state'];
						$mem_arr1[$i]['pincode'] = $row['pincode'];
						$mem_arr1[$i]['associatedinstitute'] = $row['associatedinstitute'];
						$mem_arr1[$i]['designation'] = $row['designation'];
						$mem_arr1[$i]['exam_period'] = $row['exam_period'];
						$mem_arr1[$i]['elected_sub_code'] = $row['elected_sub_code'];
						$mem_arr1[$i]['editedon'] = $row['editedon'];
						$mem_arr1[$i]['examination_date'] = $row['examination_date'];
						$mem_arr1[$i]['mem_exam_id'] = $row['mem_exam_id'];
						
						$i++;
					}
					
				}
				
				$can_exam_data4 = array_merge($can_exam_data,$mem_arr1);
				
				if(count($can_exam_data4))
				{
					$i = 1;
					$exam_cnt = 0;
					
					// Column headers			
					$data1 = "First Name,Middle name,Last Name,Mem. Number,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt \n";
					$exam_file_flg = fwrite($fp, $data1);
					
					foreach($can_exam_data4 as $exam)
					{
						$data = '';					
						$syllabus_code = '';
						$subject_description = '';
						
						// get admit card details for this member by mem_exam_id
						$mem_exam_id = $exam['mem_exam_id'];
						$selectCol = 'DISTINCT(exm_cd),exm_prd,sub_cd,sub_dsc';
						$this->db->where('remark', 1);
						$this->db->group_by('exm_cd');
						$admit_card_details_arr = $this->Master_model->getRecords('admit_card_details',array('mem_exam_id' => $mem_exam_id),$selectCol);
						
						$subject_code = $subject_description = '';
						if(count($admit_card_details_arr))
						{	
							foreach($admit_card_details_arr as $admit_card_data)
							{
								$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period'],'group_code'=>'E','subject_code' =>$exam['elected_sub_code']),'',array('id'=>'DESC'),0,1);
								if(count($subject_data)>0)
								{
									$subject_code = $subject_data[0]['subject_code'];
									$subject_description = $subject_data[0]['subject_description'];
									$subject_description = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $subject_description);
								}
								
								$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = '';
								
								$exam_code = '';
								if($exam['exam_code'] != '' && $exam['exam_code'] != 0){
									
									$ex_code = $this->master_model->getRecords('exam_activation_master',array('exam_code'=>$exam['exam_code']));
									if(count($ex_code)){
										if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0)
										{	$exam_code = $ex_code[0]['original_val'];	}
										else
										{	$exam_code = $exam['exam_code'];	}
									}
								}
								else{ $exam_code = $exam['exam_code'];	}
								
								$elected_sub_code = '';
								if($exam_code == $this->config->item('examCodeCaiib')){ $elected_sub_code = $exam['elected_sub_code'];	}
								
								$dateofbirth = date('m/d/Y',strtotime($exam['dateofbirth']));
								$address = $exam['address1'].' '.$exam['address2'].' '.$exam['address3'].' '.$exam['address4'].' '.$exam['district'].' '.$exam['city'];
								$address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
								
								$gender = $exam['gender'];
								if($gender == 'male') {	$gender = 'M';	} else { $gender = 'F'; }
								
								$designation_name = '';
								$designation  = $this->master_model->getRecords('designation_master');
								if(count($designation)){
									foreach($designation as $designation_row){
										if($exam['designation']==$designation_row['dcode']){
										$designation_name = $designation_row['dname'];}
									} 
								}	
								$designation_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
								
								$institution_name = '';
								$institution_master = $this->master_model->getRecords('institution_master');	
								if(count($institution_master)){
									foreach($institution_master as $institution_row){ 	
										if($exam['associatedinstitute']==$institution_row['institude_id']){
										$institution_name = $institution_row['name'];}
									}
								}
								$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
								
								//$firstname = preg_replace('/[^A-Za-z]/', '', $exam['firstname']);
								$firstname = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
								$middlename = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
								$lastname = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
								$mobile = preg_replace('/[^0-9]/', '', $exam['mobile']);
								$pincode = preg_replace('/[^0-9]/', '', $exam['pincode']);
								
								$sub_cd = $admit_card_data['sub_cd'];
								$sub_dsc = $admit_card_data['sub_dsc'];
								
								$exam_name = '';
								$exam_arr = array('34'=>'ANTI-MONEY LAUNDERING AND KNOW YOUR CUSTOMER','340'=>'ANTI-MONEY LAUNDERING AND KNOW YOUR CUSTOMER','3400'=>'ANTI-MONEY LAUNDERING AND KNOW YOUR CUSTOMER','151'=>'DIPLOMA IN TREASURY INVESTMENT AND RISK MANAGEMENT','34000'=>'ANTI-MONEY LAUNDERING AND KNOW YOUR CUSTOMER','1002'=>'ANTI-MONEY LAUNDERING AND KNOW YOUR CUSTOMER','1014'=>'CERTIFICATE IN INTERNATIONAL TRADE FINANCE');
								
								foreach($exam_arr as $k => $val){
									if($exam_code == $k) { $exam_name = $val; }
								}
								
								if($exam_code == '34' || $exam_code == '340' || $exam_code == '3400' || $exam_code == '151' || $exam_code == '34000' || $exam_code == '1002' || $exam_code == '1014')
								{
									$subject_code = '';
									$subject_description = '';
								}
								
								//First Name|Middle name|Last Name|Mem. Number|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession	|Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt
								$data .= ''.$firstname.','.$middlename.','.$lastname.','.$exam['regnumber'].','.$dateofbirth.','.$gender.','.$exam['email'].','.$mobile.','.$address.','.$exam['state'].','.$pincode.','.'INDIA'.','.''.','.$institution_name.','.$designation_name.','.$exam_code.','.$exam_name.','.$subject_code.','.$subject_description.','.''."\n";
								
								$exam_file_flg = fwrite($fp, $data);
								
								if($exam_file_flg)
								$success['cand_exam'] = "Examination CSV Details File Generated Successfully.";
								else
								$error['cand_exam'] = "Error While Generating Examination CSV Details File.";
								
								$i++;
								$exam_cnt++;
							}
						}
					}
					fwrite($fp1, "Total Exam Applications - ".$exam_cnt."\n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/iibfportal_".$current_date.".csv";
					$newPath = $cron_file_dir.$current_date."/iibfportal_".date('dmYhi')."_".$exam_cnt.".csv";
					rename($oldPath,$newPath);
					
					$OldName = "iibfportal_".$current_date.".csv";
					$NewName = "iibfportal_".date('dmYhi')."_".$exam_cnt.".csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_csv_kesdee', $insert_info, true);
					
				}
				else
				{
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: ".$yesterday." \n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/iibfportal_".$current_date.".csv";
					$newPath = $cron_file_dir.$current_date."/iibfportal_".date('dmYhi')."_0.csv";
					rename($oldPath,$newPath);
					
					$OldName = "iibfportal_".$current_date.".csv";
					$NewName = "iibfportal_".date('dmYhi')."_0.csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_csv_kesdee', $insert_info, true);
					
					$success[] = "No data found for the date";
				}
				fclose($fp);
				
				// Cron End Logs
				$end_time = date("Y-m-d H:i:s");
				$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
				$desc = json_encode($result);
				$this->log_model->cronlog("Examination CSV Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."************ Examination CSV Details Cron Execution End ".$end_time." *************"."\n");
				fclose($fp1);
			}
		}
		
		
		
		/* CSV Cron For Shezartech Vendor : 05/April/2019 : Bhushan */
    public function exam_csv_shezartech()
    {
    	exit();// from feb 2023 this data will be sent to teamlease
			ini_set("memory_limit", "-1");
			$dir_flg        = 0;
			$parent_dir_flg = 0;
			$exam_file_flg  = 0;
			$success        = array();
			$error          = array();
			$start_time     = date("Y-m-d H:i:s");
			$current_date   = date("Ymd");
			$cron_file_dir  = "./uploads/cronCSV_shezartech/";
			$result         = array(
			"success" => "",
			"error" => "",
			"Start Time" => $start_time,
			"End Time" => ""
			);
			$desc           = json_encode($result);
			$this->log_model->cronlog("Shezartech CSV Cron Execution Start", $desc);
			if (!file_exists($cron_file_dir . $current_date)) {
				$parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
			}
			if (file_exists($cron_file_dir . $current_date)) {
				$cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
				$file           = "iibfportal_" . $current_date . ".csv";
				$fp             = fopen($cron_file_path . '/' . $file, 'w');
				$file1          = "logs_" . $current_date . ".txt";
				$fp1            = fopen($cron_file_path . '/' . $file1, 'a');
				fwrite($fp1, "\n********* Shezartech CSV Cron Execution Started - " . $start_time . " ********* \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2019-04-09';
				$exam_code = array('529');
				$select    = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date';
				$this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
				$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
				$this->db->where_in('a.exam_code', $exam_code);
				$can_exam_data = $this->Master_model->getRecords('member_exam a', array(
				'pay_type' => 18,
				'status' => 1,
				'isactive' => '1',
				'isdeleted' => 0,
				'pay_status' => 1,
				'DATE(a.created_on)' => $yesterday
				), $select);
				if (count($can_exam_data)) {
					$i             = 1;
					$exam_cnt      = 0;
					// Column headers for CSV            
					$data1         = "First Name,Middle name,Last Name,Mem. Number,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt,Registration Date \n";
					$exam_file_flg = fwrite($fp, $data1);
					foreach ($can_exam_data as $exam) {
						$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $registration_date = $data = $syllabus_code = $subject_description = $subject_code = $subject_description = $institution_name = $designation_name = $exam_code = $exam_name = '';
						if ($exam['exam_code'] != '' && $exam['exam_code'] != 0) {
							$ex_code = $this->master_model->getRecords('exam_activation_master', array(
							'exam_code' => $exam['exam_code']
							));
							if (count($ex_code)) {
								if ($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0) {
									$exam_code = $ex_code[0]['original_val'];
									} else {
									$exam_code = $exam['exam_code'];
								}
							}
							} else {
							$exam_code = $exam['exam_code'];
						}
						$dateofbirth       = date('m/d/Y', strtotime($exam['dateofbirth']));
						$registration_date = date('m/d/Y', strtotime($exam['registration_date']));
						$address           = $exam['address1'] . ' ' . $exam['address2'] . ' ' . $exam['address3'] . ' ' . $exam['address4'] . ' ' . $exam['district'] . ' ' . $exam['city'];
						$address           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
						$gender            = $exam['gender'];
						if ($gender == 'male') {
							$gender = 'M';
							} else {
							$gender = 'F';
						}
						$designation = $this->master_model->getRecords('designation_master');
						if (count($designation)) {
							foreach ($designation as $designation_row) {
								if ($exam['designation'] == $designation_row['dcode']) {
									$designation_name = $designation_row['dname'];
								}
							}
						}
						$designation_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
						$institution_master = $this->master_model->getRecords('institution_master');
						if (count($institution_master)) {
							foreach ($institution_master as $institution_row) {
								if ($exam['associatedinstitute'] == $institution_row['institude_id']) {
									$institution_name = $institution_row['name'];
								}
							}
						}
						$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
						$firstname        = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
						$middlename       = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
						$lastname         = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
						$mobile           = preg_replace('/[^0-9]/', '', $exam['mobile']);
						$pincode          = preg_replace('/[^0-9]/', '', $exam['pincode']);
						
						$exam_arr         = array('529' => 'ETHICS IN BANKING (E-LEARNING)');
						
						foreach ($exam_arr as $k => $val) {
							if ($exam_code == $k) {
								$exam_name = $val;
							}
						}
						//First Name|Middle name|Last Name|Mem. Number|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession    |Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt|Registration Date
						$data .= '' . $firstname . ',' . $middlename . ',' . $lastname . ',' . $exam['regnumber'] . ',' . $dateofbirth . ',' . $gender . ',' . $exam['email'] . ',' . $mobile . ',' . $address . ',' . $exam['state'] . ',' . $pincode . ',' . 'INDIA' . ',' . '' . ',' . $institution_name . ',' . $designation_name . ',' . $exam_code . ',' . $exam_name . ',' . $subject_code . ',' . $subject_description . ',' . '' . ',' . $registration_date . "\n";
						$exam_file_flg = fwrite($fp, $data);
						if ($exam_file_flg)
						$success['cand_exam'] = "Shezartech CSV File Generated Successfully.";
						else
						$error['cand_exam'] = "Error While Generating Shezartech CSV File.";
						$i++;
						$exam_cnt++;
					}
					fwrite($fp1, "Total Applications - " . $exam_cnt . "\n");
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_csv_shezartech', $insert_info, true);
					} else {
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_0.csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_0.csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_csv_shezartech', $insert_info, true);
					$success[] = "No data found for the date";
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
				$this->log_model->cronlog("Shezartech CSV Cron Execution End", $desc);
				fwrite($fp1, "\n" . "********* Shezartech CSV Cron Execution End " . $end_time . " *********" . "\n");
				fclose($fp1);
			}
		}
		/* CSV Cron For Flipick Vendor : 05/April/2019 : Bhushan */
    public function exam_csv_flipick()
    {
    	exit();// from feb 2023 this data will be sent to teamlease
			ini_set("memory_limit", "-1");
			$dir_flg        = 0;
			$parent_dir_flg = 0;
			$exam_file_flg  = 0;
			$success        = array();
			$error          = array();
			$start_time     = date("Y-m-d H:i:s");
			$current_date   = date("Ymd");
			$cron_file_dir  = "./uploads/cronCSV_flipick/";
			$result         = array(
			"success" => "",
			"error" => "",
			"Start Time" => $start_time,
			"End Time" => ""
			);
			$desc           = json_encode($result);
			$this->log_model->cronlog("Flipick CSV Cron Execution Start", $desc);
			if (!file_exists($cron_file_dir . $current_date)) {
				$parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
			}
			if (file_exists($cron_file_dir . $current_date)) {
				$cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
				$file           = "iibfportal_" . $current_date . ".csv";
				$fp             = fopen($cron_file_path . '/' . $file, 'w');
				$file1          = "logs_" . $current_date . ".txt";
				$fp1            = fopen($cron_file_path . '/' . $file1, 'a');
				fwrite($fp1, "\n********* Flipick CSV Cron Execution Started - " . $start_time . " ********* \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2019-04-09';
				
				$exam_code = array('528');
				
				$select    = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date';
				$this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
				$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
				$this->db->where_in('a.exam_code', $exam_code);
				$can_exam_data = $this->Master_model->getRecords('member_exam a', array(
				'pay_type' => 18,
				'status' => 1,
				'isactive' => '1',
				'isdeleted' => 0,
				'pay_status' => 1,
				'DATE(a.created_on)' => $yesterday
				), $select);
				if (count($can_exam_data)) {
					$i             = 1;
					$exam_cnt      = 0;
					// Column headers for CSV            
					$data1         = "First Name,Middle name,Last Name,Mem. Number,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt,Registration Date \n";
					$exam_file_flg = fwrite($fp, $data1);
					foreach ($can_exam_data as $exam) {
						$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $registration_date = $data = $syllabus_code = $subject_description = $subject_code = $subject_description = $institution_name = $designation_name = $exam_code = $exam_name = '';
						if ($exam['exam_code'] != '' && $exam['exam_code'] != 0) {
							$ex_code = $this->master_model->getRecords('exam_activation_master', array(
							'exam_code' => $exam['exam_code']
							));
							if (count($ex_code)) {
								if ($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0) {
									$exam_code = $ex_code[0]['original_val'];
									} else {
									$exam_code = $exam['exam_code'];
								}
							}
							} else {
							$exam_code = $exam['exam_code'];
						}
						$dateofbirth       = date('m/d/Y', strtotime($exam['dateofbirth']));
						$registration_date = date('m/d/Y', strtotime($exam['registration_date']));
						$address           = $exam['address1'] . ' ' . $exam['address2'] . ' ' . $exam['address3'] . ' ' . $exam['address4'] . ' ' . $exam['district'] . ' ' . $exam['city'];
						$address           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
						$gender            = $exam['gender'];
						if ($gender == 'male') {
							$gender = 'M';
							} else {
							$gender = 'F';
						}
						$designation = $this->master_model->getRecords('designation_master');
						if (count($designation)) {
							foreach ($designation as $designation_row) {
								if ($exam['designation'] == $designation_row['dcode']) {
									$designation_name = $designation_row['dname'];
								}
							}
						}
						$designation_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
						$institution_master = $this->master_model->getRecords('institution_master');
						if (count($institution_master)) {
							foreach ($institution_master as $institution_row) {
								if ($exam['associatedinstitute'] == $institution_row['institude_id']) {
									$institution_name = $institution_row['name'];
								}
							}
						}
						$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
						$firstname        = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
						$middlename       = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
						$lastname         = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
						$mobile           = preg_replace('/[^0-9]/', '', $exam['mobile']);
						$pincode          = preg_replace('/[^0-9]/', '', $exam['pincode']);
						$exam_arr         = array(
						'528' => 'DIGITAL BANKING (E-LEARNING)'
						);
						foreach ($exam_arr as $k => $val) {
							if ($exam_code == $k) {
								$exam_name = $val;
							}
						}
						//First Name|Middle name|Last Name|Mem. Number|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession    |Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt|Registration Date
						$data .= '' . $firstname . ',' . $middlename . ',' . $lastname . ',' . $exam['regnumber'] . ',' . $dateofbirth . ',' . $gender . ',' . $exam['email'] . ',' . $mobile . ',' . $address . ',' . $exam['state'] . ',' . $pincode . ',' . 'INDIA' . ',' . '' . ',' . $institution_name . ',' . $designation_name . ',' . $exam_code . ',' . $exam_name . ',' . $subject_code . ',' . $subject_description . ',' . '' . ',' . $registration_date . "\n";
						$exam_file_flg = fwrite($fp, $data);
						if ($exam_file_flg)
						$success['cand_exam'] = "Flipick CSV File Generated Successfully.";
						else
						$error['cand_exam'] = "Error While Generating Flipick CSV File.";
						$i++;
						$exam_cnt++;
					}
					fwrite($fp1, "Total Applications - " . $exam_cnt . "\n");
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_csv_flipick', $insert_info, true);
					} else {
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_0.csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_0.csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_csv_flipick', $insert_info, true);
					$success[] = "No data found for the date";
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
				$this->log_model->cronlog("Flipick CSV Cron Execution End", $desc);
				fwrite($fp1, "\n" . "********* Flipick CSV Cron Execution End " . $end_time . " *********" . "\n");
				fclose($fp1);
			}
		}
    
    /* FOR CSC EXAM */
		/* CSV Cron For CSC Vendor : 27/MAY/2019 : Bhushan */
    public function exam_csv_csc()
    {
			ini_set("memory_limit", "-1");
			$dir_flg        = 0;
			$parent_dir_flg = 0;
			$exam_file_flg  = 0;
			$success        = array();
			$error          = array();
			$start_time     = date("Y-m-d H:i:s");
			$current_date   = date("Ymd");
			$cron_file_dir  = "./uploads/cronCSV_csc/";
			$result         = array(
			"success" => "",
			"error" => "",
			"Start Time" => $start_time, 
			"End Time" => ""
			);
			$desc           = json_encode($result);
			$this->log_model->cronlog("CSC CSV Cron Execution Start", $desc);
			if (!file_exists($cron_file_dir . $current_date)) {
				$parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
			}
			if (file_exists($cron_file_dir . $current_date)) {
				$cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
				$file           = "iibfportal_" . $current_date . ".csv";
				$fp             = fopen($cron_file_path . '/' . $file, 'w');
				$file1          = "logs_" . $current_date . ".txt";
				$fp1            = fopen($cron_file_path . '/' . $file1, 'a');
				fwrite($fp1, "\n***** CSC CSV Cron Execution Started - " . $start_time . " ***** \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2022-07-13';
				$exam_code = array('991','997');
				$select    = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date,a.exam_medium,a.exam_center_code,d.venueid,d.pwd,d.exam_date,d.time';
				$this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
				$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
				$this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
				$this->db->where_in('a.exam_code', $exam_code);
				$can_exam_data = $this->Master_model->getRecords('member_exam a', array(
				'remark' => 1,
				'pay_type' => 2,
				'status' => 1,
				'isactive' => '1',
				'isdeleted' => 0,
				'pay_status' => 1,
				'DATE(a.created_on)' => $yesterday
				), $select);
				//'bankcode' => 'csc',
				
				//echo $this->db->last_query();
				if (count($can_exam_data)) {
					$i             = 1;
					$exam_cnt      = 0;
					// Column headers for CSV            
					$data1         = "First Name,Middle name,Last Name,Mem. Number,Password,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt,Registration Date,Exam date,Time,Exam Medium,Exam Center Code,Venue Code \n";
					$exam_file_flg = fwrite($fp, $data1);
					foreach ($can_exam_data as $exam) {
						$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $registration_date = $data = $syllabus_code = $subject_description = $subject_code = $subject_description = $institution_name = $designation_name = $exam_code = $exam_name = $medium_name = '';
						
						if ($exam['exam_code'] != '' && $exam['exam_code'] != 0) {
							$ex_code = $this->master_model->getRecords('exam_activation_master', array(
							'exam_code' => $exam['exam_code']
							));
							if (count($ex_code)) {
								if ($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0) {
									$exam_code = $ex_code[0]['original_val'];
								} 
								else {
									$exam_code = $exam['exam_code'];
								}
							}
						} 
						else {
							$exam_code = $exam['exam_code'];
						}
						
						//$dateofbirth       = date('m/d/Y', strtotime($exam['dateofbirth']));
						//$registration_date = date('m/d/Y', strtotime($exam['registration_date']));
						
						$dateofbirth       = date('d-m-Y', strtotime($exam['dateofbirth']));
						$registration_date = date('d-m-Y', strtotime($exam['registration_date']));
						
						$address           = $exam['address1'] . ' ' . $exam['address2'] . ' ' . $exam['address3'] . ' ' . $exam['address4'] . ' ' . $exam['district'] . ' ' . $exam['city'];
						$address           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
						$gender            = $exam['gender'];
						if ($gender == 'male' || $gender == 'Male') {
							$gender = 'M';
							} elseif($gender == 'female' || $gender == 'Female') {
							$gender = 'F';
						}
						$designation = $this->master_model->getRecords('designation_master');
						if (count($designation)) {
							foreach ($designation as $designation_row) {
								if ($exam['designation'] == $designation_row['dcode']) {
									$designation_name = $designation_row['dname'];
								}
							}
						}
						$designation_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
						
						$medium = $this->master_model->getRecords('medium_master');
						if (count($medium)) {
							foreach ($medium as $medium_row) {
								if ($exam['exam_medium'] == $medium_row['medium_code']) {
									$medium_name = $medium_row['medium_description'];
								}
							}
						}
						$medium_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $medium_name);
						
						$institution_master = $this->master_model->getRecords('institution_master');
						if (count($institution_master)) {
							foreach ($institution_master as $institution_row) {
								if ($exam['associatedinstitute'] == $institution_row['institude_id']) {
									$institution_name = $institution_row['name'];
								}
							}
						}
						$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
						$firstname        = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
						$middlename       = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
						$lastname         = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
						$mobile           = preg_replace('/[^0-9]/', '', $exam['mobile']);
						$pincode          = preg_replace('/[^0-9]/', '', $exam['pincode']);
						
						$exam_arr         = array('991'=>'CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS/FACILITATORS');
						
						foreach ($exam_arr as $k => $val) {
							if ($exam_code == $k) {
								$exam_name = $val;
							}
						}
						
						$select    = 'regnumber';
						$this->db->where_in('exam_code', 991);
						$this->db->where_in('regnumber', $exam['regnumber']);
						$attempt_count = $this->Master_model->getRecords('member_exam', array('pay_status' => 1), $select);
						$attempt_count = count($attempt_count);
						
						$attempt_count = $attempt_count - 1;
						
						/* First Name|Middle name|Last Name|Mem. Number|Password|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession|Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt|Registration Date|Exam Medium|Exam Center Code|Venue Code */
						
						$data .= '' . $firstname . ',' . $middlename . ',' . $lastname . ',' . $exam['regnumber'] . ',' . $exam['pwd'] .',' . $dateofbirth . ',' . $gender . ',' . $exam['email'] . ',' . $mobile . ',' . $address . ',' . $exam['state'] . ',' . $pincode . ',' . 'INDIA' . ',' . '' . ',' . $institution_name . ',' . $designation_name . ',' . $exam_code . ',' . $exam_name . ',' . $subject_code . ',' . $subject_description . ',' . $attempt_count . ',' . $registration_date . ',' . $exam['exam_date'] . ',' . $exam['time'] . ',' .$medium_name.',' . $exam['exam_center_code'] . ',' . $exam['venueid']."\n";
						
						$exam_file_flg = fwrite($fp, $data);
						if ($exam_file_flg)
						$success['cand_exam'] = "CSC CSV File Generated Successfully.";
						else
						$error['cand_exam'] = "Error While Generating CSC CSV File.";
						$i++;
						$exam_cnt++;
					}
					fwrite($fp1, "Total Applications - " . $exam_cnt . "\n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_csv_csc', $insert_info, true);
					} else {
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_0.csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_0.csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_csv_csc', $insert_info, true);
					$success[] = "No data found for the date";
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
				$this->log_model->cronlog("CSC CSV Cron Execution End", $desc);
				fwrite($fp1, "\n" . "***** CSC CSV Cron Execution End " . $end_time . " ******" . "\n");
				fclose($fp1);
			}
		}
		
		/* CSV Cron For Free CSC Vendor : 29/Sep/2020 : Bhushan */
    public function exam_csv_csc_free()
    {
			ini_set("memory_limit", "-1");
			$dir_flg        = 0;
			$parent_dir_flg = 0;
			$exam_file_flg  = 0;
			$success        = array();
			$error          = array();
			$start_time     = date("Y-m-d H:i:s");
			$current_date   = date("Ymd");
			$cron_file_dir  = "./uploads/CSC_FREE_APP/";
			$result         = array(
			"success" => "",
			"error" => "",
			"Start Time" => $start_time,
			"End Time" => ""
			);
			$desc           = json_encode($result);
			$this->log_model->cronlog("CSC Free CSV Cron Execution Start", $desc);
			if (!file_exists($cron_file_dir . $current_date)) {
				$parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
			}
			if (file_exists($cron_file_dir . $current_date)) {
				$cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
				$file           = "iibfportal_" . $current_date . ".csv";
				$fp             = fopen($cron_file_path . '/' . $file, 'w');
				$file1          = "logs_" . $current_date . ".txt";
				$fp1            = fopen($cron_file_path . '/' . $file1, 'a');
				fwrite($fp1, "\n***** CSC Free CSV Cron Execution Started - " . $start_time . " ***** \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2020-09-29';
				$exam_code = array('991');
				$select    = 'a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date,a.exam_medium,a.exam_center_code,d.venueid,d.pwd,d.exam_date,d.time';
				$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
				$this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
				$this->db->where_in('a.exam_code', $exam_code);
				$can_exam_data = $this->Master_model->getRecords('member_exam a', array(
				'remark' => 1,
				'isactive' => '1',
				'isdeleted' => 0,
				'pay_status' => 1,
				'a.free_paid_flg' => 'F',
				'DATE(a.created_on)' => $yesterday
				), $select);
				//echo $this->db->last_query();
				if (count($can_exam_data)) {
					$i             = 1;
					$exam_cnt      = 0;
					// Column headers for CSV            
					$data1         = "First Name,Middle name,Last Name,Mem. Number,Password,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt,Registration Date,Exam date,Time,Exam Medium,Exam Center Code,Venue Code \n";
					$exam_file_flg = fwrite($fp, $data1);
					foreach ($can_exam_data as $exam) {
						$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $registration_date = $data = $syllabus_code = $subject_description = $subject_code = $subject_description = $institution_name = $designation_name = $exam_code = $exam_name = $medium_name = '';
						
						if ($exam['exam_code'] != '' && $exam['exam_code'] != 0) {
							$ex_code = $this->master_model->getRecords('exam_activation_master', array(
							'exam_code' => $exam['exam_code']
							));
							if (count($ex_code)) {
								if ($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0) {
									$exam_code = $ex_code[0]['original_val'];
								} 
								else {
									$exam_code = $exam['exam_code'];
								}
							}
						} 
						else {
							$exam_code = $exam['exam_code'];
						}
						
						//$dateofbirth       = date('m/d/Y', strtotime($exam['dateofbirth']));
						//$registration_date = date('m/d/Y', strtotime($exam['registration_date']));
						
						$dateofbirth       = date('d-m-Y', strtotime($exam['dateofbirth']));
						$registration_date = date('d-m-Y', strtotime($exam['registration_date']));
						
						$address           = $exam['address1'] . ' ' . $exam['address2'] . ' ' . $exam['address3'] . ' ' . $exam['address4'] . ' ' . $exam['district'] . ' ' . $exam['city'];
						$address           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
						$gender            = $exam['gender'];
						if ($gender == 'male' || $gender == 'Male') {
							$gender = 'M';
							} elseif($gender == 'female' || $gender == 'Female') {
							$gender = 'F';
						}
						$designation = $this->master_model->getRecords('designation_master');
						if (count($designation)) {
							foreach ($designation as $designation_row) {
								if ($exam['designation'] == $designation_row['dcode']) {
									$designation_name = $designation_row['dname'];
								}
							}
						}
						$designation_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
						
						$medium = $this->master_model->getRecords('medium_master');
						if (count($medium)) {
							foreach ($medium as $medium_row) {
								if ($exam['exam_medium'] == $medium_row['medium_code']) {
									$medium_name = $medium_row['medium_description'];
								}
							}
						}
						$medium_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $medium_name);
						
						$institution_master = $this->master_model->getRecords('institution_master');
						if (count($institution_master)) {
							foreach ($institution_master as $institution_row) {
								if ($exam['associatedinstitute'] == $institution_row['institude_id']) {
									$institution_name = $institution_row['name'];
								}
							}
						}
						$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
						$firstname        = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
						$middlename       = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
						$lastname         = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
						$mobile           = preg_replace('/[^0-9]/', '', $exam['mobile']);
						$pincode          = preg_replace('/[^0-9]/', '', $exam['pincode']);
						
						$exam_arr         = array('991'=>'CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS/FACILITATORS');
						
						foreach ($exam_arr as $k => $val) {
							if ($exam_code == $k) {
								$exam_name = $val;
							}
						}
						
						$select    = 'regnumber';
						$this->db->where_in('exam_code', 991);
						$this->db->where_in('regnumber', $exam['regnumber']);
						$attempt_count = $this->Master_model->getRecords('member_exam', array('pay_status' => 1), $select);
						$attempt_count = count($attempt_count);
						
						$attempt_count = $attempt_count - 1;
						
						/* First Name|Middle name|Last Name|Mem. Number|Password|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession|Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt|Registration Date|Exam Medium|Exam Center Code|Venue Code */
						
						$data .= '' . $firstname . ',' . $middlename . ',' . $lastname . ',' . $exam['regnumber'] . ',' . $exam['pwd'] .',' . $dateofbirth . ',' . $gender . ',' . $exam['email'] . ',' . $mobile . ',' . $address . ',' . $exam['state'] . ',' . $pincode . ',' . 'INDIA' . ',' . '' . ',' . $institution_name . ',' . $designation_name . ',' . $exam_code . ',' . $exam_name . ',' . $subject_code . ',' . $subject_description . ',' . $attempt_count . ',' . $registration_date . ',' . $exam['exam_date'] . ',' . $exam['time'] . ',' .$medium_name.',' . $exam['exam_center_code'] . ',' . $exam['venueid']."\n";
						
						$exam_file_flg = fwrite($fp, $data);
						if ($exam_file_flg)
						$success['cand_exam'] = "CSC Free CSV File Generated Successfully.";
						else
						$error['cand_exam'] = "Error While Generating CSC Free CSV File.";
						$i++;
						$exam_cnt++;
					}
					fwrite($fp1, "Total Free Applications - " . $exam_cnt . "\n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_csv_csc_free', $insert_info, true);
					} else {
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_0.csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_0.csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_csv_csc_free', $insert_info, true);
					$success[] = "No data found for the date";
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
				$this->log_model->cronlog("CSC Free CSV Cron Execution End", $desc);
				fwrite($fp1, "\n" . "***** CSC Free CSV Cron Execution End " . $end_time . " ******" . "\n");
				fclose($fp1);
			}
		}
		
		
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csv SIFFY_MSME
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csv_ftp_siffy SIFFY_MSME_FTP
		/* CSV Cron For SIFFY_MSME Vendor : 01/June/2020 : Bhushan */
    public function SIFFY_MSME()
    {
			ini_set("memory_limit", "-1");
			$dir_flg        = 0;
			$parent_dir_flg = 0;
			$exam_file_flg  = 0;
			$success        = array();
			$error          = array();
			$start_time     = date("Y-m-d H:i:s");
			$current_date   = date("Ymd");
			$cron_file_dir  = "./uploads/cronCSV_SIFFY_MSME/";
			$result         = array(
			"success" => "",
			"error" => "",
			"Start Time" => $start_time,
			"End Time" => ""
			);
			$desc           = json_encode($result);
			$this->log_model->cronlog("SIFFY_MSME CSV Cron Execution Start", $desc);
			if (!file_exists($cron_file_dir . $current_date)) {
				$parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
			}
			if (file_exists($cron_file_dir . $current_date)) {
				$cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
				$file           = "iibfportal_" . $current_date . ".csv";
				$fp             = fopen($cron_file_path . '/' . $file, 'w');
				$file1          = "logs_" . $current_date . ".txt";
				$fp1            = fopen($cron_file_path . '/' . $file1, 'a');
				fwrite($fp1, "\n********* SIFFY_MSME CSV Cron Execution Started - " . $start_time . " ********* \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				
				//$yesterday = '2019-04-09';
				$exam_code_arr = array('1003','1005','1011','1004');
				$select    = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date,a.elearning_flag';
				//$this->db->where('DATE(a.created_on) >=', '2020-05-28');
				//$this->db->where('DATE(a.created_on) <=', '2020-06-01');
				$this->db->where_in('a.exam_code', $exam_code_arr);
				$this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
				$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
				$can_exam_data = $this->Master_model->getRecords('member_exam a', array(
				'pay_type' => 2,
				'status' => 1,
				'isactive' => '1',
				'isdeleted' => 0,
				'pay_status' => 1,
				'elearning_flag' => 'Y',
				'DATE(a.created_on)' => $yesterday
				), $select);
				
				//echo $this->db->last_query();
				
				if (count($can_exam_data)) {
					$i             = 1;
					$exam_cnt      = 0;
					// Column headers for CSV            
					$data1         = "First Name,Middle name,Last Name,Mem. Number,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt,Registration Date \n";
					$exam_file_flg = fwrite($fp, $data1);
					foreach ($can_exam_data as $exam) {
						$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $registration_date = $data = $syllabus_code = $subject_description = $subject_code = $subject_description = $institution_name = $designation_name = $exam_code = $exam_name = '';
						
						$exam_code = $exam['exam_code'];
						//$dateofbirth       = date('m/d/Y', strtotime($exam['dateofbirth']));
						$registration_date = date('m/d/Y', strtotime($exam['registration_date']));
						
						$dateofbirth       = date('d-m-Y', strtotime($exam['dateofbirth']));
						
						$address           = $exam['address1'] . ' ' . $exam['address2'] . ' ' . $exam['address3'] . ' ' . $exam['address4'] . ' ' . $exam['district'] . ' ' . $exam['city'];
						$address           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
						$gender            = $exam['gender'];
						if ($gender == 'male') {
							$gender = 'M';
							} else {
							$gender = 'F';
						}
						$designation = $this->master_model->getRecords('designation_master');
						if (count($designation)) {
							foreach ($designation as $designation_row) {
								if ($exam['designation'] == $designation_row['dcode']) {
									$designation_name = $designation_row['dname'];
								}
							}
						}
						$designation_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
						$institution_master = $this->master_model->getRecords('institution_master');
						if (count($institution_master)) {
							foreach ($institution_master as $institution_row) {
								if ($exam['associatedinstitute'] == $institution_row['institude_id']) {
									$institution_name = $institution_row['name'];
								}
							}
						}
						$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
						$firstname        = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
						$middlename       = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
						$lastname         = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
						$mobile           = preg_replace('/[^0-9]/', '', $exam['mobile']);
						$pincode          = preg_replace('/[^0-9]/', '', $exam['pincode']);
						
						$exam_arr = array('1003' => 'CERTIFICATE COURSE ON MSME','1005' => 'CERTIFIED CREDIT PROFESSIONAL','1011'=>'IT Security','1004'=>'PREVENTION OF CYBER CRIMES AND FRAUD MANAGEMENT');
						
						foreach ($exam_arr as $k => $val) {
							if ($exam_code == $k) {
								$exam_name = $val;
							}
						}
						//First Name|Middle name|Last Name|Mem. Number|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession    |Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt|Registration Date
						$data .= '' . $firstname . ',' . $middlename . ',' . $lastname . ',' . $exam['regnumber'] . ',' . $dateofbirth . ',' . $gender . ',' . $exam['email'] . ',' . $mobile . ',' . $address . ',' . $exam['state'] . ',' . $pincode . ',' . 'INDIA' . ',' . '' . ',' . $institution_name . ',' . $designation_name . ',' . $exam_code . ',' . $exam_name . ',' . $subject_code . ',' . $subject_description . ',' . '' . ',' . $registration_date . "\n";
						$exam_file_flg = fwrite($fp, $data);
						if ($exam_file_flg)
						$success['cand_exam'] = "SIFFY_MSME CSV File Generated Successfully.";
						else
						$error['cand_exam'] = "Error While Generating SIFFY_MSME CSV File.";
						$i++;
						$exam_cnt++;
					}
					fwrite($fp1, "Total Applications - " . $exam_cnt . "\n");
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_csv_SIFFY_MSME', $insert_info, true);
					} else {
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_0.csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_0.csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_csv_SIFFY_MSME', $insert_info, true);
					$success[] = "No data found for the date";
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
				$this->log_model->cronlog("SIFFY_MSME CSV Cron Execution End", $desc);
				fwrite($fp1, "\n" . "********* SIFFY_MSME CSV Cron Execution End " . $end_time . " *********" . "\n");
				fclose($fp1);
			}
		}
		
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csv exam_csv_NSEIT
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csv_ftp_NSEIT index
		/* CSV Cron For NSEIT Vendor : 29/MAY/2020 : Bhushan */
    public function exam_csv_NSEIT()
    {
		$this->prepare_nseit_csv();// for other exams than 997 >> priyanka d >> 14-june 23

		$current_date   = date("Ymd");
		$file           = "iibfportal_997_" . $current_date . ".csv";
		$file1          = "logs_997_" . $current_date . ".txt";
		$exam_code=array('997');
		$vendor='nseit';
		$this->prepare_nseit_csv($file,$file1,$exam_code,$vendor);
	}
	
	//priyanka d >> 14-june-23 >> adding this function to send 997 data to nseit and keep old logic same which sent rpe data to nseit. just making common code for both
	public function prepare_nseit_csv($file='',$file1='',$exam_code=array(),$vendor='')  {

		ini_set("memory_limit", "-1");
			$dir_flg        = 0;
			$parent_dir_flg = 0;
			$exam_file_flg  = 0;
			$success        = array();
			$error          = array();
			$start_time     = date("Y-m-d H:i:s");
			$current_date   = date("Ymd");
			$cron_file_dir  = "./uploads/cronCSV_NSEIT/";
			$result         = array(
			"success" => "",
			"error" => "",
			"Start Time" => $start_time,
			"End Time" => ""
			);
			$desc           = json_encode($result);
			$this->log_model->cronlog("NSEIT CSV Cron Execution Start", $desc);
			if (!file_exists($cron_file_dir . $current_date)) {
				$parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
			}
			if (file_exists($cron_file_dir . $current_date)) {
				$cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
				if($file=='') {
					$file           = "iibfportal_" . $current_date . ".csv";
				}
				
				$fp             = fopen($cron_file_path . '/' . $file, 'w');

				if($file1=='') {
					$file1          = "logs_" . $current_date . ".txt";
				}

				$fp1            = fopen($cron_file_path . '/' . $file1, 'a');

				fwrite($fp1, "\n***** NSEIT CSV Cron Execution Started - " . $start_time . " ***** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$yesterday = date('Y-m-d');
				//$yesterday = '2020-05-29';
				$exam_code_array=$exam_code;
				if(empty($exam_code)) {
					$exam_code = array('1002','1010','1011','1012','1013','1014','1019','1020','2027'); // Send Free and Paid Both Applications
				}
				
				
				//DISTINCT(b.transaction_no),
				$select    = 'a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date,a.exam_medium,a.exam_center_code,d.venueid,d.pwd,d.exam_date,d.time';
				//$this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
				$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
				$this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
				$this->db->where_in('a.exam_code', $exam_code);
				$can_exam_data = $this->Master_model->getRecords('member_exam a', array(
				'remark' => 1,
				'selected_vendor'=>$vendor, // priyanka d >> 14-june-23 >> this line added for 997
				/*'pay_type' => 2,
				'status' => 1,*/
				'isactive' => '1',
				'isdeleted' => 0,
				'pay_status' => 1,
				'DATE(a.created_on)' => $yesterday
				), $select);
				
			//	echo $this->db->last_query(); 
				
				if (count($can_exam_data)) {
					$i             = 1;
					$exam_cnt      = 0;
					
					// Column headers for CSV            
					$data1         = "First Name,Middle name,Last Name,Mem. Number,Password,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt,Registration Date,Exam date,Time,Exam Medium,Exam Center Code,Venue Code \n";
					
					$exam_file_flg = fwrite($fp, $data1);
					
					foreach ($can_exam_data as $exam) 
					{
						$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $registration_date = $data = $syllabus_code = $subject_description = $subject_code = $subject_description = $institution_name = $designation_name = $exam_code = $exam_name = $medium_name = '';
						
						$exam_code = $exam['exam_code'];
						if($exam_code == '2027') { $exam_code = '1017'; }
						
						$dateofbirth       = date('d-m-Y', strtotime($exam['dateofbirth']));
						$registration_date = date('d-m-Y', strtotime($exam['registration_date']));
						$address           = $exam['address1'] . ' ' . $exam['address2'] . ' ' . $exam['address3'] . ' ' . $exam['address4'] . ' ' . $exam['district'] . ' ' . $exam['city'];
						$address           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
						$gender            = $exam['gender'];
						if ($gender == 'male' || $gender == 'Male') {
							$gender = 'M';
							} elseif($gender == 'female' || $gender == 'Female') {
							$gender = 'F';
						}
						$designation = $this->master_model->getRecords('designation_master');
						if (count($designation)) {
							foreach ($designation as $designation_row) {
								if ($exam['designation'] == $designation_row['dcode']) {
									$designation_name = $designation_row['dname'];
								}
							}
						}
						$designation_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
						$medium = $this->master_model->getRecords('medium_master');
						if (count($medium)) {
							foreach ($medium as $medium_row) {
								if ($exam['exam_medium'] == $medium_row['medium_code']) {
									$medium_name = $medium_row['medium_description'];
								}
							}
						}
						$medium_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $medium_name);
						$institution_master = $this->master_model->getRecords('institution_master');
						if (count($institution_master)) {
							foreach ($institution_master as $institution_row) {
								if ($exam['associatedinstitute'] == $institution_row['institude_id']) {
									$institution_name = $institution_row['name'];
								}
							}
						}
						$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
						$firstname        = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
						$middlename       = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
						$lastname         = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
						$mobile           = preg_replace('/[^0-9]/', '', $exam['mobile']);
						$pincode          = preg_replace('/[^0-9]/', '', $exam['pincode']);
						
						$exam_arr         = array('1002' => 'ANTI MONEY LAUNDERING AND KNOW YOUR CUSTOME',
						'1010' => 'CUSTOMER SERVICE AND BANKING CODES AND STANDARDS',
						'1011' => 'CERTIFICATE EXAMINATION IN IT SECURITY',
						'1012' => 'CERTIFIED INFORMATION SYSTEM BANKER REVISED SYLLABUS',
						'1013' => 'CERTIFICATE COURSE IN DIGITAL BANKING',
						'1014' => 'CERTIFICATE IN INTERNATIONAL TRADE FINANCE',
						'1019' => 'CERTIFICATE COURSE IN STRATEGIC MANAGEMENT & INNOVATIONS IN BANKING',
						'1020' => 'Certificate Course in Emerging Technologies',
						'1017' => 'CERTIFICATE COURSE ON RESOLUTION OF STRESSED ASSETS WITH SPECIAL EMPHASIS ON INSOLVENCY AND BANKRUPTCY CODE 2016 FOR BANKERS',
						'997'=>' CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS - PAYMENTS BANKS');
						foreach ($exam_arr as $k => $val) {
							if ($exam_code == $k) {
								$exam_name = $val;
							}
						}
						
						//$exam_code_array = array('1002');
						$select    = 'regnumber';
						$this->db->where_in('exam_code', $exam['exam_code']);
						$this->db->where_in('regnumber', $exam['regnumber']);
						$attempt_count = $this->Master_model->getRecords('member_exam', array('pay_status' => 1), $select);
						$attempt_count = count($attempt_count);
						$attempt_count = $attempt_count - 1;
						
						/* First Name|Middle name|Last Name|Mem. Number|Password|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession|Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt|Registration Date|Exam Medium|Exam Center Code|Venue Code */
						
						if($exam_code == '1002')
						{
							if($exam['exam_date'] != '2020-08-02' && $exam['exam_date'] != '2020-08-08' && $exam['exam_date'] != '2020-08-09')
							{
								$data .= '' . $firstname . ',' . $middlename . ',' . $lastname . ',' . $exam['regnumber'] . ',' . $exam['pwd'] .',' . $dateofbirth . ',' . $gender . ',' . $exam['email'] . ',' . $mobile . ',' . $address . ',' . $exam['state'] . ',' . $pincode . ',' . 'INDIA' . ',' . '' . ',' . $institution_name . ',' . $designation_name . ',' . $exam_code . ',' . $exam_name . ',' . $subject_code . ',' . $subject_description . ',' . $attempt_count . ',' . $registration_date . ',' . $exam['exam_date'] . ',' . $exam['time'] . ',' .$medium_name.',' . $exam['exam_center_code'] . ',' . $exam['venueid']."\n";
							}
						}
						elseif($exam_code != '1002')
						{
							$data .= '' . $firstname . ',' . $middlename . ',' . $lastname . ',' . $exam['regnumber'] . ',' . $exam['pwd'] .',' . $dateofbirth . ',' . $gender . ',' . $exam['email'] . ',' . $mobile . ',' . $address . ',' . $exam['state'] . ',' . $pincode . ',' . 'INDIA' . ',' . '' . ',' . $institution_name . ',' . $designation_name . ',' . $exam_code . ',' . $exam_name . ',' . $subject_code . ',' . $subject_description . ',' . $attempt_count . ',' . $registration_date . ',' . $exam['exam_date'] . ',' . $exam['time'] . ',' .$medium_name.',' . $exam['exam_center_code'] . ',' . $exam['venueid']."\n";
						}
						
						$exam_file_flg = fwrite($fp, $data);
						if ($exam_file_flg)
						$success['cand_exam'] = "NSEIT CSV File Generated Successfully.";
						else
						$error['cand_exam'] = "Error While Generating NSEIT CSV File.";
						$i++;
						if($data != '')
						{
							$exam_cnt++;
						}
					}
					fwrite($fp1, "Total Applications - " . $exam_cnt . "\n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";

					//priyanka d- 15-june-23 >> change file name for 997 IPPB
					if(in_array('997',$exam_code_array)) {
						$oldPath = $cron_file_dir . $current_date . "/iibfportal_997_" . $current_date . ".csv";
						$newPath = $cron_file_dir . $current_date . "/iibfportal_997_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					}
					rename($oldPath, $newPath);

					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";

					//priyanka d- 15-june-23 >> change file name for 997 IPPB
					if(in_array('997',$exam_code_array)) {

						$OldName     = "iibfportal_997_" . $current_date . ".csv";
						$NewName     = "iibfportal_997_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					}

					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_csv_NSEIT', $insert_info, true);
					} else {
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_0.csv";

					//priyanka d- 15-june-23 >> change file name for 997 IPPB
					if(in_array('997',$exam_code_array)) {
						$oldPath = $cron_file_dir . $current_date . "/iibfportal_997_" . $current_date . ".csv";
						$newPath = $cron_file_dir . $current_date . "/iibfportal_997_" . date('dmYhi') . "_0".".csv";
					}

					rename($oldPath, $newPath);

					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_0.csv";

					if(in_array('997',$exam_code_array)) {

						$OldName     = "iibfportal_997_" . $current_date . ".csv";
						$NewName     = "iibfportal_997_" . date('dmYhi') . "_0.csv";
					}
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_csv_NSEIT', $insert_info, true);
					$success[] = "No data found for the date";
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
				$this->log_model->cronlog("NSEIT CSV Cron Execution End", $desc);
				fwrite($fp1, "\n" . "***** NSEIT CSV Cron Execution End " . $end_time . " ******" . "\n");
				fclose($fp1);
			}
	}

	//Added by Priyanka W for RPE 1020 Exam data sending using FTP
	public function exam_csv_Teamlease_RPE()
    {
		ini_set("memory_limit", "-1");

		$file = ''
		$file1 = ''
		$dir_flg        = 0;
		$parent_dir_flg = 0;
		$exam_file_flg  = 0;
		$success        = array();
		$error          = array();
		$start_time     = date("Y-m-d H:i:s");
		$current_date   = date("Ymd");
		$cron_file_dir  = "./uploads/cronCSV_teamlease_RPE/";
		$result         = array(
		"success" => "",
		"error" => "",
		"Start Time" => $start_time,
		"End Time" => ""
		);
		$desc           = json_encode($result);
		$this->log_model->cronlog("TEAMLEASE RPE CSV Cron Execution Start", $desc);
		if (!file_exists($cron_file_dir . $current_date)) {
			$parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
		}
		if (file_exists($cron_file_dir . $current_date)) {
			$cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
			if($file=='') {
				$file           = "iibfportal_" . $current_date . ".csv";
			}
			
			$fp             = fopen($cron_file_path . '/' . $file, 'w');

			if($file1=='') {
				$file1          = "logs_" . $current_date . ".txt";
			}

			$fp1            = fopen($cron_file_path . '/' . $file1, 'a');

			fwrite($fp1, "\n***** TEAMLEASE RPE CSV Cron Execution Started - " . $start_time . " ***** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday = date('Y-m-d');
			//$yesterday = '2020-05-29';
			$exam_code_array=$exam_code;
			if(empty($exam_code)) {
				$exam_code = array('1020'); // Send Free and Paid Both Applications
			}
			echo $exam_code; die;
			
			//DISTINCT(b.transaction_no),
			$select    = 'a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date,a.exam_medium,a.exam_center_code,d.venueid,d.pwd,d.exam_date,d.time';
			//$this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
			$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
			$this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
			$this->db->where_in('a.exam_code', $exam_code);
			$can_exam_data = $this->Master_model->getRecords('member_exam a', array(
			'remark' => 1,
			'selected_vendor'=>$vendor,
			/*'pay_type' => 2,
			'status' => 1,*/
			'isactive' => '1',
			'isdeleted' => 0,
			'pay_status' => 1,
			'DATE(a.created_on)' => $yesterday
			), $select);
			
			echo $this->db->last_query(); die;
			
			if (count($can_exam_data)) {
				$i             = 1;
				$exam_cnt      = 0;
				
				// Column headers for CSV            
				$data1         = "First Name,Middle name,Last Name,Mem. Number,Password,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt,Registration Date,Exam date,Time,Exam Medium,Exam Center Code,Venue Code \n";
				
				$exam_file_flg = fwrite($fp, $data1);
				
				foreach ($can_exam_data as $exam) 
				{
					$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $registration_date = $data = $syllabus_code = $subject_description = $subject_code = $subject_description = $institution_name = $designation_name = $exam_code = $exam_name = $medium_name = '';
					
					$exam_code = $exam['exam_code'];
					
					$dateofbirth       = date('d-m-Y', strtotime($exam['dateofbirth']));
					$registration_date = date('d-m-Y', strtotime($exam['registration_date']));
					$address           = $exam['address1'] . ' ' . $exam['address2'] . ' ' . $exam['address3'] . ' ' . $exam['address4'] . ' ' . $exam['district'] . ' ' . $exam['city'];
					$address           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
					$gender            = $exam['gender'];
					if ($gender == 'male' || $gender == 'Male') {
						$gender = 'M';
						} elseif($gender == 'female' || $gender == 'Female') {
						$gender = 'F';
					}
					$designation = $this->master_model->getRecords('designation_master');
					if (count($designation)) {
						foreach ($designation as $designation_row) {
							if ($exam['designation'] == $designation_row['dcode']) {
								$designation_name = $designation_row['dname'];
							}
						}
					}
					$designation_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
					$medium = $this->master_model->getRecords('medium_master');
					if (count($medium)) {
						foreach ($medium as $medium_row) {
							if ($exam['exam_medium'] == $medium_row['medium_code']) {
								$medium_name = $medium_row['medium_description'];
							}
						}
					}
					$medium_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $medium_name);
					$institution_master = $this->master_model->getRecords('institution_master');
					if (count($institution_master)) {
						foreach ($institution_master as $institution_row) {
							if ($exam['associatedinstitute'] == $institution_row['institude_id']) {
								$institution_name = $institution_row['name'];
							}
						}
					}
					$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
					$firstname        = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
					$middlename       = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
					$lastname         = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
					$mobile           = preg_replace('/[^0-9]/', '', $exam['mobile']);
					$pincode          = preg_replace('/[^0-9]/', '', $exam['pincode']);
					
					$exam_arr         = array(
					'1020' => 'Certificate Course in Emerging Technologies',
					);
					foreach ($exam_arr as $k => $val) {
						if ($exam_code == $k) {
							$exam_name = $val;
						}
					}
					
					//$exam_code_array = array('1002');
					$select    = 'regnumber';
					$this->db->where_in('exam_code', $exam['exam_code']);
					$this->db->where_in('regnumber', $exam['regnumber']);
					$attempt_count = $this->Master_model->getRecords('member_exam', array('pay_status' => 1), $select);
					$attempt_count = count($attempt_count);
					$attempt_count = $attempt_count - 1;
					
					/* First Name|Middle name|Last Name|Mem. Number|Password|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession|Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt|Registration Date|Exam Medium|Exam Center Code|Venue Code */
					
					$data .= '' . $firstname . ',' . $middlename . ',' . $lastname . ',' . $exam['regnumber'] . ',' . $exam['pwd'] .',' . $dateofbirth . ',' . $gender . ',' . $exam['email'] . ',' . $mobile . ',' . $address . ',' . $exam['state'] . ',' . $pincode . ',' . 'INDIA' . ',' . '' . ',' . $institution_name . ',' . $designation_name . ',' . $exam_code . ',' . $exam_name . ',' . $subject_code . ',' . $subject_description . ',' . $attempt_count . ',' . $registration_date . ',' . $exam['exam_date'] . ',' . $exam['time'] . ',' .$medium_name.',' . $exam['exam_center_code'] . ',' . $exam['venueid']."\n";
					
					$exam_file_flg = fwrite($fp, $data);
					if ($exam_file_flg)
					$success['cand_exam'] = "TEAMLEASE RPE CSV File Generated Successfully.";
					else
					$error['cand_exam'] = "Error While Generating TEAMLEASE RPE CSV File.";
					$i++;
					if($data != '')
					{
						$exam_cnt++;
					}
				}
				fwrite($fp1, "Total Applications - " . $exam_cnt . "\n");
				
				// File Rename Functinality
				$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
				$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";

				rename($oldPath, $newPath);

				$OldName     = "iibfportal_" . $current_date . ".csv";
				$NewName     = "iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";

				$insert_info = array(
				'CurrentDate' => $current_date,
				'old_file_name' => $OldName,
				'new_file_name' => $NewName,
				'record_count' => $exam_cnt,
				'createdon' => date('Y-m-d H:i:s')
				);
				$this->master_model->insertRecord('cron_csv_NSEIT', $insert_info, true);
				} else {
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
				// File Rename Functinality
				$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
				$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_0.csv";

				rename($oldPath, $newPath);

				$OldName     = "iibfportal_" . $current_date . ".csv";
				$NewName     = "iibfportal_" . date('dmYhi') . "_0.csv";

				$insert_info = array(
				'CurrentDate' => $current_date,
				'old_file_name' => $OldName,
				'new_file_name' => $NewName,
				'record_count' => 0,
				'createdon' => date('Y-m-d H:i:s')
				);
				$this->master_model->insertRecord('cron_csv_teamlease_RPE', $insert_info, true);
				$success[] = "No data found for the date";
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
			$this->log_model->cronlog("TEAMLEASE RPE CSV Cron Execution End", $desc);
			fwrite($fp1, "\n" . "***** TEAMLEASE RPE CSV Cron Execution End " . $end_time . " ******" . "\n");
			fclose($fp1);
		}
	}
	//End 
 
	 public function rpe_teamlease()
    {
			ini_set("memory_limit", "-1");
			$dir_flg        = 0;
			$parent_dir_flg = 0;
			$exam_file_flg  = 0;
			$success        = array();
			$error          = array();
			$start_time     = date("Y-m-d H:i:s");
			$current_date   = date("Ymd");
			$cron_file_dir  = "./uploads/rpe_teamlease/";
			$result         = array(
			"success" => "",
			"error" => "",
			"Start Time" => $start_time,
			"End Time" => ""
			);
			$desc           = json_encode($result);
			$this->log_model->cronlog("NSEIT CSV Cron Execution Start", $desc);
			if (!file_exists($cron_file_dir . $current_date)) {
				$parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
			}
			if (file_exists($cron_file_dir . $current_date)) {
				$cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
				$file           = "iibfportal_" . $current_date . ".csv";
				$fp             = fopen($cron_file_path . '/' . $file, 'w');
				$file1          = "logs_" . $current_date . ".txt";
				$fp1            = fopen($cron_file_path . '/' . $file1, 'a');
				fwrite($fp1, "\n***** NSEIT CSV Cron Execution Started - " . $start_time . " ***** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2020-05-29';
				
				$exam_code = array('1014'); // Send Free and Paid Both Applications
				
				//DISTINCT(b.transaction_no),
				$select    = 'a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date,a.exam_medium,a.exam_center_code,d.venueid,d.pwd,d.exam_date,d.time';
				//$this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
				$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
				$this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
				$this->db->where_in('a.exam_code', $exam_code);
				$can_exam_data = $this->Master_model->getRecords('member_exam a', array(
				'remark' => 1,
				/*'pay_type' => 2,
				'status' => 1,*/
				'isactive' => '1',
				'isdeleted' => 0,
				'pay_status' => 1,
				'DATE(a.created_on)' => $yesterday
				), $select);
				
				//echo $this->db->last_query();
				
				if (count($can_exam_data)) {
					$i             = 1;
					$exam_cnt      = 0;
					
					// Column headers for CSV            
					$data1         = "First Name,Middle name,Last Name,Mem. Number,Password,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt,Registration Date,Exam date,Time,Exam Medium,Exam Center Code,Venue Code \n";
					
					$exam_file_flg = fwrite($fp, $data1);
					
					foreach ($can_exam_data as $exam) 
					{
						$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $registration_date = $data = $syllabus_code = $subject_description = $subject_code = $subject_description = $institution_name = $designation_name = $exam_code = $exam_name = $medium_name = '';
						
						$exam_code = $exam['exam_code'];
						if($exam_code == '2027') { $exam_code = '1017'; }
						
						$dateofbirth       = date('d-m-Y', strtotime($exam['dateofbirth']));
						$registration_date = date('d-m-Y', strtotime($exam['registration_date']));
						$address           = $exam['address1'] . ' ' . $exam['address2'] . ' ' . $exam['address3'] . ' ' . $exam['address4'] . ' ' . $exam['district'] . ' ' . $exam['city'];
						$address           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
						$gender            = $exam['gender'];
						if ($gender == 'male' || $gender == 'Male') {
							$gender = 'M';
							} elseif($gender == 'female' || $gender == 'Female') {
							$gender = 'F';
						}
						$designation = $this->master_model->getRecords('designation_master');
						if (count($designation)) {
							foreach ($designation as $designation_row) {
								if ($exam['designation'] == $designation_row['dcode']) {
									$designation_name = $designation_row['dname'];
								}
							}
						}
						$designation_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
						$medium = $this->master_model->getRecords('medium_master');
						if (count($medium)) {
							foreach ($medium as $medium_row) {
								if ($exam['exam_medium'] == $medium_row['medium_code']) {
									$medium_name = $medium_row['medium_description'];
								}
							}
						}
						$medium_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $medium_name);
						$institution_master = $this->master_model->getRecords('institution_master');
						if (count($institution_master)) {
							foreach ($institution_master as $institution_row) {
								if ($exam['associatedinstitute'] == $institution_row['institude_id']) {
									$institution_name = $institution_row['name'];
								}
							}
						}
						$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
						$firstname        = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
						$middlename       = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
						$lastname         = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
						$mobile           = preg_replace('/[^0-9]/', '', $exam['mobile']);
						$pincode          = preg_replace('/[^0-9]/', '', $exam['pincode']);
						
						$exam_arr         = array('1002' => 'ANTI MONEY LAUNDERING AND KNOW YOUR CUSTOME',
						'1010' => 'CUSTOMER SERVICE AND BANKING CODES AND STANDARDS',
						'1011' => 'CERTIFICATE EXAMINATION IN IT SECURITY',
						'1012' => 'CERTIFIED INFORMATION SYSTEM BANKER REVISED SYLLABUS',
						'1013' => 'CERTIFICATE COURSE IN DIGITAL BANKING',
						'1014' => 'CERTIFICATE IN INTERNATIONAL TRADE FINANCE',
						'1019' => 'CERTIFICATE COURSE IN STRATEGIC MANAGEMENT & INNOVATIONS IN BANKING',
						'1020' => 'Certificate Course in Emerging Technologies',
						'1017' => 'CERTIFICATE COURSE ON RESOLUTION OF STRESSED ASSETS WITH SPECIAL EMPHASIS ON INSOLVENCY AND BANKRUPTCY CODE 2016 FOR BANKERS');
						foreach ($exam_arr as $k => $val) {
							if ($exam_code == $k) {
								$exam_name = $val;
							}
						}
						
						//$exam_code_array = array('1002');
						$select    = 'regnumber';
						$this->db->where_in('exam_code', $exam['exam_code']);
						$this->db->where_in('regnumber', $exam['regnumber']);
						$attempt_count = $this->Master_model->getRecords('member_exam', array('pay_status' => 1), $select);
						$attempt_count = count($attempt_count);
						$attempt_count = $attempt_count - 1;
						
						/* First Name|Middle name|Last Name|Mem. Number|Password|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession|Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt|Registration Date|Exam Medium|Exam Center Code|Venue Code */
						
						if($exam_code == '1002')
						{
							if($exam['exam_date'] != '2020-08-02' && $exam['exam_date'] != '2020-08-08' && $exam['exam_date'] != '2020-08-09')
							{
								$data .= '' . $firstname . ',' . $middlename . ',' . $lastname . ',' . $exam['regnumber'] . ',' . $exam['pwd'] .',' . $dateofbirth . ',' . $gender . ',' . $exam['email'] . ',' . $mobile . ',' . $address . ',' . $exam['state'] . ',' . $pincode . ',' . 'INDIA' . ',' . '' . ',' . $institution_name . ',' . $designation_name . ',' . $exam_code . ',' . $exam_name . ',' . $subject_code . ',' . $subject_description . ',' . $attempt_count . ',' . $registration_date . ',' . $exam['exam_date'] . ',' . $exam['time'] . ',' .$medium_name.',' . $exam['exam_center_code'] . ',' . $exam['venueid']."\n";
							}
						}
						elseif($exam_code != '1002')
						{
							$data .= '' . $firstname . ',' . $middlename . ',' . $lastname . ',' . $exam['regnumber'] . ',' . $exam['pwd'] .',' . $dateofbirth . ',' . $gender . ',' . $exam['email'] . ',' . $mobile . ',' . $address . ',' . $exam['state'] . ',' . $pincode . ',' . 'INDIA' . ',' . '' . ',' . $institution_name . ',' . $designation_name . ',' . $exam_code . ',' . $exam_name . ',' . $subject_code . ',' . $subject_description . ',' . $attempt_count . ',' . $registration_date . ',' . $exam['exam_date'] . ',' . $exam['time'] . ',' .$medium_name.',' . $exam['exam_center_code'] . ',' . $exam['venueid']."\n";
						}
						
						$exam_file_flg = fwrite($fp, $data);
						if ($exam_file_flg)
						$success['cand_exam'] = "NSEIT CSV File Generated Successfully.";
						else
						$error['cand_exam'] = "Error While Generating NSEIT CSV File.";
						$i++;
						if($data != '')
						{
							$exam_cnt++;
						}
					}
					fwrite($fp1, "Total Applications - " . $exam_cnt . "\n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_rpe_teamlease', $insert_info, true);
					} else {
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_0.csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_0.csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_rpe_teamlease', $insert_info, true);
					$success[] = "No data found for the date";
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
				$this->log_model->cronlog("NSEIT CSV Cron Execution End", $desc);
				fwrite($fp1, "\n" . "***** NSEIT CSV Cron Execution End " . $end_time . " ******" . "\n");
				fclose($fp1);
			}
		}
		
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csv exam_csv_csc_vendor
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csv_ftp_cscvendor index 
		/* CSV Cron For CSC Vendor 1003,1004: 29/MAY/2020 : Bhushan */
    public function exam_csv_csc_vendor()
    { 
			ini_set("memory_limit", "-1");
			$dir_flg        = 0;
			$parent_dir_flg = 0;
			$exam_file_flg  = 0;
			$success        = array();
			$error          = array();
			$start_time     = date("Y-m-d H:i:s");
			$current_date   = date("Ymd");
			$cron_file_dir  = "./uploads/cronCSV_cscvendor/";
			$result         = array(
			"success" => "",
			"error" => "",
			"Start Time" => $start_time,
			"End Time" => ""
			);
			$desc           = json_encode($result);
			$this->log_model->cronlog("cscvendor CSV Cron Execution Start", $desc);
			if (!file_exists($cron_file_dir . $current_date)) {
				$parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
			}
			if (file_exists($cron_file_dir . $current_date)) {
				$cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
				$file           = "iibfportal_" . $current_date . ".csv";
				$fp             = fopen($cron_file_path . '/' . $file, 'w');
				$file1          = "logs_" . $current_date . ".txt";
				$fp1            = fopen($cron_file_path . '/' . $file1, 'a');
				fwrite($fp1, "\n***** cscvendor CSV Cron Execution Started - " . $start_time . " ***** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2020-05-29';
				
				$exam_code = array('1002','1003','1004','1005','1006','1007','1008','1009'); // Send Free and Paid Both Applications
				// DISTINCT(b.transaction_no),
				$select    = 'a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date,a.exam_medium,a.exam_center_code,d.venueid,d.pwd,d.exam_date,d.time';
				//$this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
				$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
				$this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
				$this->db->where_in('a.exam_code', $exam_code);
				$can_exam_data = $this->Master_model->getRecords('member_exam a', array(
				'remark' => 1,
				/*'pay_type' => 2,
				'status' => 1,*/
				'isactive' => '1',
				'isdeleted' => 0,
				'pay_status' => 1,
				'DATE(a.created_on)' => $yesterday
				), $select);
				//echo $this->db->last_query();
				
				if (count($can_exam_data)) {
					$i             = 1;
					$exam_cnt      = 0;
					// Column headers for CSV            
					$data1         = "First Name,Middle name,Last Name,Mem. Number,Password,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt,Registration Date,Exam date,Time,Exam Medium,Exam Center Code,Venue Code \n";
					$exam_file_flg = fwrite($fp, $data1);
					
					foreach ($can_exam_data as $exam) 
					{
						$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $registration_date = $data = $syllabus_code = $subject_description = $subject_code = $subject_description = $institution_name = $designation_name = $exam_code = $exam_name = $medium_name = '';
						
						$exam_code = $exam['exam_code'];
						$dateofbirth       = date('d-m-Y', strtotime($exam['dateofbirth']));
						$registration_date = date('d-m-Y', strtotime($exam['registration_date']));
						$address           = $exam['address1'] . ' ' . $exam['address2'] . ' ' . $exam['address3'] . ' ' . $exam['address4'] . ' ' . $exam['district'] . ' ' . $exam['city'];
						$address           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
						$gender            = $exam['gender'];
						if ($gender == 'male' || $gender == 'Male') {
							$gender = 'M';
							} elseif($gender == 'female' || $gender == 'Female') {
							$gender = 'F';
						}
						$designation = $this->master_model->getRecords('designation_master');
						if (count($designation)) {
							foreach ($designation as $designation_row) {
								if ($exam['designation'] == $designation_row['dcode']) {
									$designation_name = $designation_row['dname'];
								}
							}
						}
						$designation_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
						
						$medium = $this->master_model->getRecords('medium_master');
						if (count($medium)) {
							foreach ($medium as $medium_row) {
								if ($exam['exam_medium'] == $medium_row['medium_code']) {
									$medium_name = $medium_row['medium_description'];
								}
							}
						}
						$medium_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $medium_name);
						
						$institution_master = $this->master_model->getRecords('institution_master');
						if (count($institution_master)) {
							foreach ($institution_master as $institution_row) {
								if ($exam['associatedinstitute'] == $institution_row['institude_id']) {
									$institution_name = $institution_row['name'];
								}
							}
						}
						$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
						$firstname        = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
						$middlename       = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
						$lastname         = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
						$mobile           = preg_replace('/[^0-9]/', '', $exam['mobile']);
						$pincode          = preg_replace('/[^0-9]/', '', $exam['pincode']);
						
						$exam_arr         = array('1002' => 'ANTI MONEY LAUNDERING AND KNOW YOUR CUSTOME',
						'1003' => 'CERTIFICATE COURSE ON MSME',
						'1004' => 'PREVENTION OF CYBER CRIMES AND FRAUD MANAGEMENT',
						'1005' => 'CERTIFIED CREDIT PROFESSIONAL',
						'1006' => 'CERTIFIED TREASURY PROFESSIONALS',
						'1007' => 'CERTIFICATE IN RISK IN FINANCIAL SERVICES LEVEL 1',
						'1008' => 'CERTIFIED ACCOUNTING AND AUDIT PROFESSIONAL',
						'1009' => 'CERTIFICATE COURSE IN FOREIGN EXCHANGE');
						
						foreach ($exam_arr as $k => $val) {
							if ($exam_code == $k) {
								$exam_name = $val;
							}
						}
						
						//$exam_code_arr = array('1003','1004');
						$select    = 'regnumber';
						$this->db->where_in('exam_code', $exam['exam_code']);
						$this->db->where_in('regnumber', $exam['regnumber']);
						$attempt_count = $this->Master_model->getRecords('member_exam', array('pay_status' => 1), $select);
						$attempt_count = count($attempt_count);
						$attempt_count = $attempt_count - 1;
						
						/* First Name|Middle name|Last Name|Mem. Number|Password|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession|Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt|Registration Date|Exam Medium|Exam Center Code|Venue Code */
						
						if($exam_code == '1002' && ($exam['exam_date']  == '2020-08-02' || $exam['exam_date']  == '2020-08-08' || $exam['exam_date']  == '2020-08-09'))
						{
							$data .= '' . $firstname . ',' . $middlename . ',' . $lastname . ',' . $exam['regnumber'] . ',' . $exam['pwd'] .',' . $dateofbirth . ',' . $gender . ',' . $exam['email'] . ',' . $mobile . ',' . $address . ',' . $exam['state'] . ',' . $pincode . ',' . 'INDIA' . ',' . '' . ',' . $institution_name . ',' . $designation_name . ',' . $exam_code . ',' . $exam_name . ',' . $subject_code . ',' . $subject_description . ',' . $attempt_count . ',' . $registration_date . ',' . $exam['exam_date'] . ',' . $exam['time'] . ',' .$medium_name.',' . $exam['exam_center_code'] . ',' . $exam['venueid']."\n";
						}
						elseif($exam_code != '1002')
						{
							$data .= '' . $firstname . ',' . $middlename . ',' . $lastname . ',' . $exam['regnumber'] . ',' . $exam['pwd'] .',' . $dateofbirth . ',' . $gender . ',' . $exam['email'] . ',' . $mobile . ',' . $address . ',' . $exam['state'] . ',' . $pincode . ',' . 'INDIA' . ',' . '' . ',' . $institution_name . ',' . $designation_name . ',' . $exam_code . ',' . $exam_name . ',' . $subject_code . ',' . $subject_description . ',' . $attempt_count . ',' . $registration_date . ',' . $exam['exam_date'] . ',' . $exam['time'] . ',' .$medium_name.',' . $exam['exam_center_code'] . ',' . $exam['venueid']."\n";
						}
						
						$exam_file_flg = fwrite($fp, $data);
						if ($exam_file_flg)
						$success['cand_exam'] = "cscvendor CSV File Generated Successfully.";
						else
						$error['cand_exam'] = "Error While Generating cscvendor CSV File.";
						$i++;
						if($data != '')
						{
							$exam_cnt++;
						}
					}
					fwrite($fp1, "Total Applications - " . $exam_cnt . "\n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_csv_cscvendor', $insert_info, true);
					} else {
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_0.csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_0.csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_csv_cscvendor', $insert_info, true);
					$success[] = "No data found for the date";
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
				$this->log_model->cronlog("cscvendor CSV Cron Execution End", $desc);
				fwrite($fp1, "\n" . "***** cscvendor CSV Cron Execution End " . $end_time . " ******" . "\n");
				fclose($fp1);
			}
		}
		
		/* CSV Cron For BSBF NAR Flipick Vendor : 17/Sep/2020 : Bhushan */
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csv bcbf_nar_csv_flipick
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csv_ftp_flipick bcbf_nar_csv
    public function bcbf_nar_csv_flipick()
    { 	     
			ini_set("memory_limit", "-1");
			$dir_flg        = 0;
			$parent_dir_flg = 0;
			$exam_file_flg  = 0;
			$success        = array();
			$error          = array();
			$start_time     = date("Y-m-d H:i:s");
			$current_date   = date("Ymd");
			//$current_date   = '20200924';
			$cron_file_dir  = "./uploads/BCBF-NAR-Flipick/";
			$result         = array(
			"success" => "",
			"error" => "",
			"Start Time" => $start_time,
			"End Time" => ""
			);
			$desc           = json_encode($result);
			$this->log_model->cronlog("Flipick CSV Cron Execution Start", $desc);
			if (!file_exists($cron_file_dir . $current_date)) {
				$parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
			}
			if (file_exists($cron_file_dir . $current_date)) {
				$cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
				$file           = "iibfportal_" . $current_date . ".csv";
				$fp             = fopen($cron_file_path . '/' . $file, 'w');
				$file1          = "logs_" . $current_date . ".txt";
				$fp1            = fopen($cron_file_path . '/' . $file1, 'a');
				fwrite($fp1, "\n********* Flipick BCBF NAR CSV Cron Execution Started - " . $start_time . " ********* \n");
				
		    $yesterday = date('Y-m-d', strtotime("- 1 day"));
				
		    //$yesterday = '2020-09-23';
				
				
				
				$exm_cd = array('1015','996','994','1026');
				//$exm_prd = array('1');
				
				$select    = 'exm_cd,exm_prd,mem_type,g_1,mem_mem_no,mam_nam_1,email,mobile,center_code,center_name,sub_cd,sub_dsc,venueid,venue_name,venueadd1,venueadd2,venueadd3,venueadd4,venueadd5,venpin,seat_identification,pwd,exam_date,time,mode,m_1,scribe_flag,vendor_code';
				$this->db->join('member_registration m', 'a.mem_mem_no = m.regnumber', 'LEFT');
				$this->db->where_in('a.exm_cd', $exm_cd);
				//$this->db->where_in('a.exm_prd', $exm_prd);
				$can_exam_data = $this->Master_model->getRecords('admit_card_details a', array(
				'a.remark' => 1,
				'm.isactive' => '1',
				'm.isdeleted' => 0,
				'DATE(a.modified_on)' => $yesterday
				), $select);
				//echo $this->db->last_query();
				if (count($can_exam_data)) 
				{
					$i             = 1;
					$exam_cnt      = 0;
					// Column headers for CSV            
					$data1         = "EXM_CD,EXM_PRD,MEM_TYPE,GENDER,MEM_MEM_NO,MAM_NAM_1,EMAIL,MOBILE,CENTER_CODE,CENTER_NAME,SUB_CD,SUB_DSC,VENUEID,VENUE_NAME,VENUEADD,VENPIN,SEAT_IDENTIFICATION,PWD,EXAM_DATE,TIME,MODE,MED,SCRIBE_FLAG,VENDOR_CODE \n";
					
					$exam_file_flg = fwrite($fp, $data1);
					
					foreach ($can_exam_data as $exam) 
					{
						$data = $EXM_CD = $EXM_PRD = $MEM_TYPE = $G_1 = $MEM_MEM_NO = $MAM_NAM_1 = $EMAIL = $MOBILE = $CENTER_CODE = $CENTER_NAME = $SUB_CD = $SUB_DSC = $VENUEID = $VENUE_NAME = $VENUEADD1 = $VENUEADD2 = $VENUEADD3 = $VENUEADD4 = $VENUEADD5 = $VENPIN = $SEAT_IDENTIFICATION = $PWD = $EXAM_DATE = $TIME = $MODE = $M_1 = $SCRIBE_FLAG = $VENDOR_CODE = '';
						
						
						
						$VENUEADD=$exam['venueadd1'].' '.$exam['venueadd2'].' '.$exam['venueadd3'].' '.$exam['venueadd4'].' '.$exam['venueadd5'];
						$VENUEADD=preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $VENUEADD);
						$VENUE_NAME=preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['venue_name']);
						$MAM_NAM_1=preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['mam_nam_1']);
						$SUB_DSC=preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['sub_dsc']);
						$CENTER_NAME=preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['center_name']);
						
						
						
						$data .= '' . $exam['exm_cd'].','.$exam['exm_prd'].','.$exam['mem_type'].','.$exam['g_1'].','.$exam['mem_mem_no'].','.$MAM_NAM_1.','.$exam['email'].','.$exam['mobile'].','.$exam['center_code'].','.$CENTER_NAME.','.$exam['sub_cd'].','.$SUB_DSC.','.$exam['venueid'].','.$VENUE_NAME.','.$VENUEADD.','.$exam['venpin'].','.$exam['seat_identification'].','.$exam['pwd'].','.$exam['exam_date'].','.$exam['time'].','.$exam['mode'].','.$exam['m_1'].','.$exam['scribe_flag'].','.$exam['vendor_code'] . "\n"; 
						
						
						$exam_file_flg = fwrite($fp, $data);
						if ($exam_file_flg)
						$success['cand_exam'] = "Flipick BCBF NAR CSV File Generated Successfully.";
						else
						$error['cand_exam'] = "Error While Generating Flipick BCBF NAR CSV File.";
						$i++;
						$exam_cnt++;
					}
					fwrite($fp1, "Total BCBF NAR Applications - " . $exam_cnt . "\n");
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_bsbf_nar_flipick', $insert_info, true);
				} 
				else {
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_0.csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_0.csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_bsbf_nar_flipick', $insert_info, true);
					$success[] = "No data found for the date";
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
				$this->log_model->cronlog("Flipick BCBF NAR CSV Cron Execution End", $desc);
				fwrite($fp1, "\n" . "********* Flipick BCBF NAR CSV Cron Execution End " . $end_time . " *********" . "\n");
				fclose($fp1);
			}
		}
		
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csv dra_csv_nseit
		public function dra_csv_nseit()
    { 	      
			ini_set("memory_limit", "-1");
			$dir_flg        = 0;
			$parent_dir_flg = 0;
			$exam_file_flg  = 0;
			$success        = array();
			$error          = array();
			$start_time     = date("Y-m-d H:i:s");
			$current_date   = date("Ymd");
			//$current_date   = '20200924';
			$cron_file_dir  = "./uploads/dra_csv/";
			$result         = array(
			"success" => "",
			"error" => "",
			"Start Time" => $start_time,
			"End Time" => ""
			);
			$desc           = json_encode($result);
			$this->log_model->cronlog("DRA CSV Cron Execution Start", $desc);
			if (!file_exists($cron_file_dir . $current_date)) {
				$parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
			}
			
			if (file_exists($cron_file_dir . $current_date)) {
				$cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
				$file           = "iibfportal_" . $current_date . ".csv";
				$fp             = fopen($cron_file_path . '/' . $file, 'w');
				$file1          = "logs_" . $current_date . ".txt";
				$fp1            = fopen($cron_file_path . '/' . $file1, 'a');
				fwrite($fp1, "\n********* DRA BCBF NAR CSV Cron Execution Started - " . $start_time . " ********* \n");
				
		    $yesterday = date('Y-m-d', strtotime("- 1 day"));
				
		    //$yesterday = '2020-09-23';
				$exm_cd = array('45','57');
				
				$select    = 'exm_cd,exm_prd,mem_type,g_1,mem_mem_no,mam_nam_1,email,mobile,center_code,center_name,sub_cd,sub_dsc,venueid,venue_name,venueadd1,venueadd2,venueadd3,venueadd4,venueadd5,venpin,seat_identification,pwd,exam_date,time,mode,m_1,scribe_flag,vendor_code,institute_code';
				$this->db->join('dra_members m', 'a.mem_mem_no = m.regnumber', 'LEFT');
				$this->db->where_in('a.exm_cd', $exm_cd);
				$can_exam_data = $this->Master_model->getRecords('dra_admit_card_details a', array(
				'a.remark' => 1,
				'DATE(a.modified_on)' => $yesterday
				), $select);
				//echo $this->db->last_query();
				if (count($can_exam_data)) 
				{
					$i             = 1;
					$exam_cnt      = 0;
					// Column headers for CSV            
					$data1         = "EXM_CD,EXM_PRD,MEM_TYPE,GENDER,MEM_MEM_NO,MAM_NAM_1,EMAIL,MOBILE,CENTER_CODE,CENTER_NAME,SUB_CD,SUB_DSC,VENUEID,VENUE_NAME,VENUEADD,VENPIN,SEAT_IDENTIFICATION,PWD,EXAM_DATE,TIME,MODE,MED,SCRIBE_FLAG,VENDOR_CODE,INSTITUTE_CODE \n";
					
					$exam_file_flg = fwrite($fp, $data1);
					
					foreach ($can_exam_data as $exam) 
					{
						$data = $EXM_CD = $EXM_PRD = $MEM_TYPE = $G_1 = $MEM_MEM_NO = $MAM_NAM_1 = $EMAIL = $MOBILE = $CENTER_CODE = $CENTER_NAME = $SUB_CD = $SUB_DSC = $VENUEID = $VENUE_NAME = $VENUEADD1 = $VENUEADD2 = $VENUEADD3 = $VENUEADD4 = $VENUEADD5 = $VENPIN = $SEAT_IDENTIFICATION = $PWD = $EXAM_DATE = $TIME = $MODE = $M_1 = $SCRIBE_FLAG = $VENDOR_CODE = $INSTITUTE_CODE = '';
				    
						$VENUEADD=$exam['venueadd1'].' '.$exam['venueadd2'].' '.$exam['venueadd3'].' '.$exam['venueadd4'].' '.$exam['venueadd5'];
						$VENUEADD=preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $VENUEADD);
						$VENUE_NAME=preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['venue_name']);
						$MAM_NAM_1=preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['mam_nam_1']);
						$SUB_DSC=preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['sub_dsc']);
						$CENTER_NAME=preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $exam['center_name']);
						
						
						
						$data .= '' . $exam['exm_cd'].','.$exam['exm_prd'].','.$exam['mem_type'].','.$exam['g_1'].','.$exam['mem_mem_no'].','.$MAM_NAM_1.','.$exam['email'].','.$exam['mobile'].','.$exam['center_code'].','.$CENTER_NAME.','.$exam['sub_cd'].','.$SUB_DSC.','.$exam['venueid'].','.$VENUE_NAME.','.$VENUEADD.','.$exam['venpin'].','.$exam['seat_identification'].','.$exam['pwd'].','.$exam['exam_date'].','.$exam['time'].','.$exam['mode'].','.$exam['m_1'].','.$exam['scribe_flag'].','.$exam['vendor_code'].','.$exam['institute_code'] . "\n"; 
						
						
						$exam_file_flg = fwrite($fp, $data);
						if ($exam_file_flg)
						$success['cand_exam'] = "DRA  CSV File Generated Successfully.";
						else
						$error['cand_exam'] = "Error While Generating DRA CSV File.";
						$i++;
						$exam_cnt++;
					}
					fwrite($fp1, "Total DRA  Applications - " . $exam_cnt . "\n");
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_csv_dra', $insert_info, true);
				} 
				else {
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_0.csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_0.csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_csv_dra', $insert_info, true);
					$success[] = "No data found for the date";
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
				$this->log_model->cronlog("DRA CSV Cron Execution End", $desc);
				fwrite($fp1, "\n" . "********* DRA  CSV Cron Execution End " . $end_time . " *********" . "\n");
				fclose($fp1);
			}
		}
		
		
		/* jaiib_csv_kesdee - elearning_flag = Y : 22/Oct/2020 : Bhushan*/
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csv kesdee_jaiib
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csv_ftp_kesdee kesdee_jaiib
		public function jaiib_csv_kesdee()
		{
			ini_set("memory_limit", "-1");
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$exam_file_flg = 0;
			$success = array();
			$error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	
			$cron_file_dir = "./uploads/jaiib_kesdee/";
			//Cron start Logs
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("Examination CSV Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "iibfportal_".$current_date.".csv";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n***** Examination CSV Details Cron Execution Started - ".$start_time." *********** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				
				$exam_code = array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB'));
				$select = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.optFlg';
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
				$this->db->where_in('a.exam_code', $exam_code);
				$can_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'DATE(a.created_on)'=>$yesterday,'sub_el_count >' => 0),$select);
				
				//echo ">>".$this->db->last_query();
				
				if(count($can_exam_data))
				{
					$i = 1;
					$exam_cnt = 0;
					
					// Column headers			
					$data1 = "First Name,Middle name,Last Name,Mem. Number,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Subject Code,Subject Desc,Attempt,OptFlg \n";
					$exam_file_flg = fwrite($fp, $data1);
					
					foreach($can_exam_data as $exam)
					{		
						$syllabus_code = $subject_description = '';
						
						// get admit card details for this member by mem_exam_id
						$mem_exam_id = $exam['mem_exam_id'];
						$selectCol = 'exm_cd,exm_prd,sub_cd,sub_dsc';
						$this->db->where('remark', 1);
						$this->db->where('sub_el_flg', 'Y');
						$admit_card_details_arr = $this->Master_model->getRecords('admit_card_details',array('mem_exam_id' => $mem_exam_id),$selectCol);
						
						if(count($admit_card_details_arr))
						{	
							foreach($admit_card_details_arr as $admit_card_data)
							{
								$data = '';	
								$subject_code = $subject_description = '';
								$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period'],'group_code'=>'C','subject_code' =>$admit_card_data['sub_cd']),'subject_code,subject_description',array('id'=>'DESC'),0,1);	
								
								if(count($subject_data)>0)
								{
									$subject_code = $subject_data[0]['subject_code'];
									$subject_description = $subject_data[0]['subject_description'];
									$subject_description = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $subject_description);
								}
								
								$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $exam_code = $designation_name = $institution_name = '';
								
								$designation  = $this->master_model->getRecords('designation_master','','dcode,dname');
								if(count($designation)){
									foreach($designation as $designation_row){
										if($exam['designation']==$designation_row['dcode']){
										$designation_name = $designation_row['dname'];}
									} 
								}	
								
								$institution_master=$this->master_model->getRecords('institution_master','','institude_id,name');	
								if(count($institution_master)){
									foreach($institution_master as $institution_row){ 	
										if($exam['associatedinstitute']==$institution_row['institude_id']){
										$institution_name = $institution_row['name'];}
									}
								}
								
								$exam_code = $exam['exam_code'];
								$firstname = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
								$middlename = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
								$lastname = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
								$mobile = preg_replace('/[^0-9]/', '', $exam['mobile']);
								$pincode = preg_replace('/[^0-9]/', '', $exam['pincode']);
								$dateofbirth = date('m/d/Y',strtotime($exam['dateofbirth']));
								$address = $exam['address1'].' '.$exam['address2'].' '.$exam['address3'].' '.$exam['address4'].' '.$exam['district'].' '.$exam['city'];
								$address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
								$gender = $exam['gender'];
								if($gender == 'male') {	$gender = 'M';	} else { $gender = 'F'; }
								$designation_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
								$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
								
								/*$exam_arr = array($this->config->item('examCodeJaiib')=>'JAIIB',
								'36'=>'Legal and Regulatory Aspects of Banking',
								'34'=>'Principles and Practices of Banking',
								'35'=>'Accounting and  Finance for Bankers',
								$this->config->item('examCodeDBF')=>'Diploma In Banking And Finance',
								'121'=>'Principles & Practices of Banking',
								'122'=>'Accounting & Finance for Bankers',
								'123'=>'Legal & Regulatory Aspects of Banking',
								$this->config->item('examCodeSOB')=>'Specialist Officers of Banks');
								*/

								$exam_arr = array($this->config->item('examCodeJaiib')=>'JAIIB',
								'211'=>'Indian Economy & Indian Financial System',
								'214'=>'Retail Banking & Wealth Management',
								'213'=>'Accounting & Financial Management for Bankers',
								'212'=>'Principles & Practices of Banking',
												$this->config->item('examCodeDBF')=>'Diploma In Banking And Finance',
												'423'=>'Accounting & Financial Management for Bankers',
												'422'=>'Principles & Practices of Banking',
												'421'=>'Indian Economy & Indian Financial System',
												'424'=>'Retail Banking & Wealth Management',
												$this->config->item('examCodeSOB')=>'Specialist Officers of Banks'
								);
								
								foreach($exam_arr as $k => $val){
									if($exam_code == $k) { 
										$exam_name = $val; 
									}
								}
								
								//First Name|Middle name|Last Name|Mem. Number|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession	|Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt
								$data .= ''.$firstname.','.$middlename.','.$lastname.','.$exam['regnumber'].','.$dateofbirth.','.$gender.','.$exam['email'].','.$mobile.','.$address.','.$exam['state'].','.$pincode.','.'INDIA'.','.''.','.$institution_name.','.$designation_name.','.$exam_code.','.$exam_name.','.$subject_code.','.$subject_description.','.''.','.$exam['optFlg']."\n";
								$exam_file_flg = fwrite($fp, $data);
								
								if($exam_file_flg)
								$success['cand_exam'] = "Examination CSV Details File Generated Successfully.";
								else
								$error['cand_exam'] = "Error While Generating Examination CSV Details File.";
								
								$i++;
								$exam_cnt++;
							}
						}
					}
					fwrite($fp1, "Total Exam Applications - ".$exam_cnt."\n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/iibfportal_".$current_date.".csv";
					$newPath = $cron_file_dir.$current_date."/iibfportal_".date('dmYhi')."_".$exam_cnt.".csv";
					rename($oldPath,$newPath);
					
					$OldName = "iibfportal_".$current_date.".csv";
					$NewName = "iibfportal_".date('dmYhi')."_".$exam_cnt.".csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_csv_JAIIB', $insert_info, true);
				}
				else
				{
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: ".$yesterday." \n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/iibfportal_".$current_date.".csv";
					$newPath = $cron_file_dir.$current_date."/iibfportal_".date('dmYhi')."_0.csv";
					rename($oldPath,$newPath);
					
					$OldName = "iibfportal_".$current_date.".csv";
					$NewName = "iibfportal_".date('dmYhi')."_0.csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_csv_JAIIB', $insert_info, true);
					
					$success[] = "No data found for the date";
				}
				fclose($fp);
				// Cron End Logs
				$end_time = date("Y-m-d H:i:s");
				$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
				$desc = json_encode($result);
				$this->log_model->cronlog("Examination CSV Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."***** Examination CSV Details Cron Execution End ".$end_time." ******"."\n");
				fclose($fp1);
			}
		}
		
		
		/* caiib_csv_mps : 22/Oct/2020 : Bhushan */  
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csv caiib_csv_mps
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csv_ftp mps_caiib
		public function caiib_csv_mps()
		{
			exit;
			ini_set("memory_limit", "-1");
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$exam_file_flg = 0;
			$success = array();
			$error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	
			$cron_file_dir = "./uploads/caiib_mps/";
			//Cron start Logs
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("Examination CSV Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date)){
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "iibfportal_".$current_date.".csv";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n***** Examination CSV Details Cron Execution Started - ".$start_time." *********** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2017-11-06';
				
				$exam_code = array($this->config->item('examCodeCaiib'),'62',$this->config->item('examCodeCaiibElective63'),'64','65','66','67',$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),'72');
				$select = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id';
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
				$this->db->where_in('a.exam_code', $exam_code);
				$can_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'DATE(a.created_on)'=>$yesterday),$select);
				
				//echo "<br>SQL => ".$this->db->last_query(); //exit;
				
				if(count($can_exam_data))
				{
					$i = 1;
					$exam_cnt = 0;
					// Column headers			
					$data1 = "First Name,Middle name,Last Name,Mem. Number,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt \n";
					$exam_file_flg = fwrite($fp, $data1);
					
					foreach($can_exam_data as $exam)
					{
						$data = '';					
						$syllabus_code = '';
						$subject_description = '';
						
						// get admit card details for this member by mem_exam_id
						$mem_exam_id = $exam['mem_exam_id'];
						$selectCol = 'DISTINCT(exm_cd),exm_prd,sub_cd,sub_dsc';
						$this->db->where('remark', 1);
						$this->db->group_by('exm_cd');
						$admit_card_details_arr = $this->Master_model->getRecords('admit_card_details',array('mem_exam_id' => $mem_exam_id),$selectCol);
						
						$subject_code = $subject_description = '';
						if(count($admit_card_details_arr))
						{	
							foreach($admit_card_details_arr as $admit_card_data)
							{
								$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period'],'group_code'=>'E','subject_code' =>$exam['elected_sub_code']),'subject_code,subject_description',array('id'=>'DESC'),0,1);
								if(count($subject_data)>0)
								{
									$subject_code = $subject_data[0]['subject_code'];
									$subject_description = $subject_data[0]['subject_description'];
									$subject_description = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $subject_description);
								}
								
								$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $designation_name = $institution_name = $exam_code = $elected_sub_code = '';
								
								$designation  = $this->master_model->getRecords('designation_master','','dcode,dname');
								if(count($designation)){
									foreach($designation as $designation_row){
										if($exam['designation']==$designation_row['dcode']){
										$designation_name = $designation_row['dname'];}
									} 
								}
								$institution_master=$this->master_model->getRecords('institution_master','','institude_id,name');	
								if(count($institution_master)){
									foreach($institution_master as $institution_row){ 	
										if($exam['associatedinstitute']==$institution_row['institude_id']){
										$institution_name = $institution_row['name'];}
									}
								}
								
								
								$exam_code = $exam['exam_code'];
								if($exam_code == $this->config->item('examCodeCaiib')){ $elected_sub_code = $exam['elected_sub_code'];}
								$dateofbirth = date('m/d/Y',strtotime($exam['dateofbirth']));
								$address = $exam['address1'].' '.$exam['address2'].' '.$exam['address3'].' '.$exam['address4'].' '.$exam['district'].' '.$exam['city'];
								$address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
								$gender = $exam['gender'];
								if($gender == 'male') {	$gender = 'M';	} else { $gender = 'F'; }
								$designation_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
								$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
								$firstname = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
								$middlename = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
								$lastname = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
								$mobile = preg_replace('/[^0-9]/', '', $exam['mobile']);
								$pincode = preg_replace('/[^0-9]/', '', $exam['pincode']);
								$sub_cd = $admit_card_data['sub_cd'];
								$sub_dsc = $admit_card_data['sub_dsc'];
								
								
								$exam_arr = array($this->config->item('examCodeCaiib')=>'CAIIB','62'=>'CORPORATE BANKING',$this->config->item('examCodeCaiibElective63')=>'RURAL BANKING','64'=>'INTERNATIONAL BANKING','65'=>'RETAIL BANKING','66'=>'CO-OPERATIVE BANKING','67'=>'FINANCIAL ADVISING',$this->config->item('examCodeCaiibElective68')=>'HUMAN RESOURCES MANAGEMENT',$this->config->item('examCodeCaiibElective69')=>'INFORMATION TECHNOLOGY',$this->config->item('examCodeCaiibElective70')=>'RISK MANAGEMENT',$this->config->item('examCodeCaiibElective71')=>'CENTRAL BANKING','72'=>'TREASURY MANAGEMENT');
								foreach($exam_arr as $k => $val){
									if($exam_code == $k) { $exam_name = $val; }
								}
								
								if($exam_code == '62' || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == '64' || $exam_code == '65' || $exam_code == '66' || $exam_code == '67' || $exam_code == $this->config->item('examCodeCaiibElective68') || $exam_code == $this->config->item('examCodeCaiibElective69') || $exam_code == $this->config->item('examCodeCaiibElective70') || $exam_code == $this->config->item('examCodeCaiibElective71') || $exam_code == '72'){
									$subject_code = '';
									$subject_description = '';
								}
								
								//First Name|Middle name|Last Name|Mem. Number|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession	|Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt
								$data .= ''.$firstname.','.$middlename.','.$lastname.','.$exam['regnumber'].','.$dateofbirth.','.$gender.','.$exam['email'].','.$mobile.','.$address.','.$exam['state'].','.$pincode.','.'INDIA'.','.''.','.$institution_name.','.$designation_name.','.$exam_code.','.$exam_name.','.$subject_code.','.$subject_description.','.''."\n";
								
								$exam_file_flg = fwrite($fp, $data);
								
								if($exam_file_flg)
								$success['cand_exam'] = "Examination CSV Details File Generated Successfully.";
								else
								$error['cand_exam'] = "Error While Generating Examination CSV Details File.";
								
								$i++;
								$exam_cnt++;
							}
						}
					}
					fwrite($fp1, "Total Exam Applications - ".$exam_cnt."\n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/iibfportal_".$current_date.".csv";
					$newPath = $cron_file_dir.$current_date."/iibfportal_".date('dmYhi')."_".$exam_cnt.".csv";
					rename($oldPath,$newPath);
					
					$OldName = "iibfportal_".$current_date.".csv";
					$NewName = "iibfportal_".date('dmYhi')."_".$exam_cnt.".csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('caiib_csv_mps', $insert_info, true);
					
				}
				else
				{
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: ".$yesterday." \n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/iibfportal_".$current_date.".csv";
					$newPath = $cron_file_dir.$current_date."/iibfportal_".date('dmYhi')."_0.csv";
					rename($oldPath,$newPath);
					
					$OldName = "iibfportal_".$current_date.".csv";
					$NewName = "iibfportal_".date('dmYhi')."_0.csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('caiib_csv_mps', $insert_info, true);
					
					$success[] = "No data found for the date";
				}
				fclose($fp);
				
				// Cron End Logs
				$end_time = date("Y-m-d H:i:s");
				$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
				$desc = json_encode($result);
				$this->log_model->cronlog("Examination CSV Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."***** Examination CSV Details Cron Execution End ".$end_time." ******"."\n");
				fclose($fp1);
			}
		}
		
		/* caiib_csv_kesdee : CODE ADDED BY SAGAR ON 07-04-2021 */  
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csv caiib_csv_kesdee
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csv_ftp kesdee_caiib
		public function caiib_csv_kesdee()
		{ 
			error_reporting(E_ALL);// Report all errors
			ini_set("error_reporting", E_ALL);// Same as error_reporting(E_ALL);
			ini_set('display_errors', 1);
			ini_set("memory_limit", "-1");
			
			$today_date = date("Y-m-d");  
			
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$exam_file_flg = 0;
			$success = array();
			$error = array();
			$start_time = date('Y-m-d H:i:s');
			$current_date = date("Ymd", strtotime($today_date));	
			$cron_file_dir = "./uploads/caiib_kesdee/";
			//Cron start Logs
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("Examination CSV Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "iibfportal_".$current_date.".csv";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n***** Examination CSV Details Cron Execution Started - ".$start_time." *********** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime($today_date)));
				
				$exam_code = array($this->config->item('examCodeCaiib'),$this->config->item('examCodeCaiibElective63'),'65',$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'));
				//$this->db->where('a.regnumber', '510000975');
				$select = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.optFlg';
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT');
				
				$this->db->where_in('a.exam_code', $exam_code);
				$can_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'DATE(a.created_on)'=>$yesterday, 'a.elearning_flag'=>'Y'),$select);
				
				//echo "<br>SQL => ".$this->db->last_query(); //exit;
				
				if(count($can_exam_data))
				{
					$i = 1;
					$exam_cnt = 0;
					// Column headers			
					$data1 = "First Name,Middle name,Last Name,Mem. Number,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt,E-learning Subject Code,OptFlg \n";
					$exam_file_flg = fwrite($fp, $data1);
					
					foreach($can_exam_data as $exam)
					{
						$data = '';					
						$syllabus_code = '';
						$subject_description = '';
						
						// get admit card details for this member by mem_exam_id
						$mem_exam_id = $exam['mem_exam_id'];
						$selectCol = 'exm_cd, exm_prd, sub_cd, sub_dsc';
						$this->db->where('sub_el_flg', 'Y');
						$this->db->where('remark', 1);
						//$this->db->group_by('exm_cd');
						$admit_card_details_arr = $this->Master_model->getRecords('admit_card_details',array('mem_exam_id' => $mem_exam_id),$selectCol);
						//echo "<br>SQL => ".$this->db->last_query(); exit;
						
						$subject_code = $subject_description = '';
						if(count($admit_card_details_arr))
						{	
							foreach($admit_card_details_arr as $admit_card_data)
							{
								$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period'],'group_code'=>'E','subject_code' =>$exam['elected_sub_code']),'subject_code,subject_description',array('id'=>'DESC'),0,1);
								if(count($subject_data)>0)
								{
									$subject_code = $subject_data[0]['subject_code'];
									$subject_description = $subject_data[0]['subject_description'];
									$subject_description = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $subject_description);
								}
								
								$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $designation_name = $institution_name = $exam_code = $elected_sub_code = $elearning_sub_code = '';
								
								$designation  = $this->master_model->getRecords('designation_master','','dcode,dname');
								if(count($designation)){
									foreach($designation as $designation_row){
										if($exam['designation']==$designation_row['dcode']){
										$designation_name = $designation_row['dname'];}
									} 
								}
								
								$institution_master=$this->master_model->getRecords('institution_master','','institude_id,name');	
								if(count($institution_master)){
									foreach($institution_master as $institution_row){ 	
										if($exam['associatedinstitute']==$institution_row['institude_id']){
										$institution_name = $institution_row['name'];}
									}
								}
								
								$exam_code = $exam['exam_code'];
								if($exam_code == $this->config->item('examCodeCaiib')){ $elected_sub_code = $exam['elected_sub_code'];}
								$dateofbirth = date('m/d/Y',strtotime($exam['dateofbirth']));
								$address = $exam['address1'].' '.$exam['address2'].' '.$exam['address3'].' '.$exam['address4'].' '.$exam['district'].' '.$exam['city'];
								$address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
								$gender = $exam['gender'];
								if($gender == 'male') {	$gender = 'M';	} else { $gender = 'F'; }
								$designation_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
								$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
								$firstname = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
								$middlename = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
								$lastname = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
								$mobile = preg_replace('/[^0-9]/', '', $exam['mobile']);
								$pincode = preg_replace('/[^0-9]/', '', $exam['pincode']);
								$sub_cd = $admit_card_data['sub_cd'];
								$sub_dsc = $admit_card_data['sub_dsc'];							
								
								$exam_arr = array($this->config->item('examCodeCaiib')=>'CAIIB',
								'62'=>'CORPORATE BANKING',
								$this->config->item('examCodeCaiibElective63')=>'RURAL BANKING',
								'64'=>'INTERNATIONAL BANKING',
								'65'=>'RETAIL BANKING',
								'66'=>'CO-OPERATIVE BANKING',
								'67'=>'FINANCIAL ADVISING',
								$this->config->item('examCodeCaiibElective68')=>'HUMAN RESOURCES MANAGEMENT',
								$this->config->item('examCodeCaiibElective69')=>'INFORMATION TECHNOLOGY & Digital Banking',
								$this->config->item('examCodeCaiibElective70')=>'RISK MANAGEMENT',
								$this->config->item('examCodeCaiibElective71')=>'CENTRAL BANKING',
								'72'=>'TREASURY MANAGEMENT'
							);
								foreach($exam_arr as $k => $val)
								{
									if($exam_code == $k) { $exam_name = $val; }
								}
								
								if($exam_code == '62' || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == '64' || $exam_code == '65' || $exam_code == '66' || $exam_code == '67' || $exam_code == $this->config->item('examCodeCaiibElective68') || $exam_code == $this->config->item('examCodeCaiibElective69') || $exam_code == $this->config->item('examCodeCaiibElective70') || $exam_code == $this->config->item('examCodeCaiibElective71') || $exam_code == '72')
								{
									$subject_code = '';
									$subject_description = '';
								}
								
								$elearning_sub_code = $admit_card_data['sub_cd'];
								
								//First Name|Middle name|Last Name|Mem. Number|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession	|Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt
								$data = ''.$firstname.','.$middlename.','.$lastname.','.$exam['regnumber'].','.$dateofbirth.','.$gender.','.$exam['email'].','.$mobile.','.$address.','.$exam['state'].','.$pincode.','.'INDIA'.','.''.','.$institution_name.','.$designation_name.','.$exam_code.','.$exam_name.','.$subject_code.','.$subject_description.','.''.','.$elearning_sub_code.','.$exam['optFlg']."\n";
								
								$exam_file_flg = fwrite($fp, $data);
								
								if($exam_file_flg)
								$success['cand_exam'] = "Examination CSV Details File Generated Successfully.";
								else
								$error['cand_exam'] = "Error While Generating Examination CSV Details File.";
								
								$i++;
								$exam_cnt++;
							}
						}
					}
					fwrite($fp1, "Total Exam Applications - ".$exam_cnt."\n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/iibfportal_".$current_date.".csv";
					$newPath = $cron_file_dir.$current_date."/iibfportal_".date('dmY', strtotime($today_date)).date('hi')."_".$exam_cnt.".csv";
					rename($oldPath,$newPath);
					
					$OldName = "iibfportal_".$current_date.".csv";
					$NewName = "iibfportal_".date('dmY', strtotime($today_date)).date('hi')."_".$exam_cnt.".csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('caiib_csv_kesdee', $insert_info, true);
					
				}
				else
				{
					$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime($today_date)));
					fwrite($fp1, "No data found for the date: ".$yesterday." \n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/iibfportal_".$current_date.".csv";
					$newPath = $cron_file_dir.$current_date."/iibfportal_".date('dmY', strtotime($today_date)).date('hi')."_0.csv";
					rename($oldPath,$newPath);
					
					$OldName = "iibfportal_".$current_date.".csv";
					$NewName = "iibfportal_".date('dmY', strtotime($today_date)).date('hi')."_0.csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('caiib_csv_kesdee', $insert_info, true);
					
					$success[] = "No data found for the date";
				}
				fclose($fp);
				
				// Cron End Logs
				$end_time = date('Y-m-d H:i:s');
				$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
				$desc = json_encode($result);
				$this->log_model->cronlog("Examination CSV Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."***** Examination CSV Details Cron Execution End ".$end_time." ******"."\n");
				fclose($fp1);
			}
		}
		
		
		/* CSV Cron For SIFFY  Vendor : 21/April/2020 : Bhushan */
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csv SIFFY_csv 
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_csv_ftp_siffy index
		public function SIFFY_csv()
		{exit;
			ini_set("memory_limit", "-1");
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$exam_file_flg = 0;
			$success = array();
			$error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	
			$cron_file_dir = "./uploads/cronCSV_SIFFY/";
			//Cron start Logs
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("SIFFY CSV Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "iibfportal_".$current_date.".csv";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************ SIFFY CSV Details Cron Execution Started - ".$start_time." *********** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2020-04-22';
				$select = 'user_name,email_id,contact_no,member_number,exam_code,exam_name,subject_code,subject_name,created_on';
				$can_exam_data = $this->Master_model->getRecords('tbl_sify_elearning',array('DATE(created_on)'=>$yesterday,'exam_code'=>'20'),$select);
				
				//echo "<br>SQL => ".$this->db->last_query(); //exit;
				
				if(count($can_exam_data))
				{
					$i = 1;
					$exam_cnt = 0;
					
					// Column headers			
					$data1 = "First Name,Middle name,Last Name,Mem. Number,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt \n";
					$exam_file_flg = fwrite($fp, $data1);
					
					foreach($can_exam_data as $exam)
					{
						$data = $firstname = $middlename = $lastname = $regnumber = $dateofbirth = $gender = $email = $mobile = $address = $state = $pincode = $institution_name = $designation_name = $exam_code = $exam_name = $subject_code = $subject_description = '';
						
						$mobile = preg_replace('/[^0-9]/', '', $exam['contact_no']);
						$firstname = preg_replace('/[^A-Za-z\/ ]/', '', $exam['user_name']);
						$exam_name = preg_replace('/[^A-Za-z\/ ]/', '', $exam['exam_name']);
						$exam_code = $exam['exam_code'];
						$subject_code = $exam['subject_code'];
						$subject_description = preg_replace('/[^A-Za-z\/ ]/', '', $exam['subject_name']);
						$regnumber = $exam['member_number'];
						$email = $exam['email_id'];
						
						//First Name|Middle name|Last Name|Mem. Number|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession	|Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt
						$data .= ''.$firstname.','.$middlename.','.$lastname.','.$regnumber.','.$dateofbirth.','.$gender.','.$email.','.$mobile.','.$address.','.$state.','.$pincode.','.'INDIA'.','.''.','.$institution_name.','.$designation_name.','.$exam_code.','.$exam_name.','.$subject_code.','.$subject_description.','.''."\n";
						
						$exam_file_flg = fwrite($fp, $data);
						
						if($exam_file_flg)
						$success['cand_exam'] = "SIFFY CSV Details File Generated Successfully.";
						else
						$error['cand_exam'] = "Error While Generating SIFFY CSV Details File.";
						
						$i++;
						$exam_cnt++;
					}
					fwrite($fp1, "Total Exam Applications - ".$exam_cnt."\n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/iibfportal_".$current_date.".csv";
					$newPath = $cron_file_dir.$current_date."/iibfportal_".date('dmYhi')."_".$exam_cnt.".csv";
					rename($oldPath,$newPath);
					
					$OldName = "iibfportal_".$current_date.".csv";
					$NewName = "iibfportal_".date('dmYhi')."_".$exam_cnt.".csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_csv_SIFFY', $insert_info, true);
					
				}
				else
				{
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: ".$yesterday." \n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/iibfportal_".$current_date.".csv";
					$newPath = $cron_file_dir.$current_date."/iibfportal_".date('dmYhi')."_0.csv";
					rename($oldPath,$newPath);
					
					$OldName = "iibfportal_".$current_date.".csv";
					$NewName = "iibfportal_".date('dmYhi')."_0.csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_csv_SIFFY', $insert_info, true);
					
					$success[] = "No data found for the date";
				}
				fclose($fp);
				
				// Cron End Logs
				$end_time = date("Y-m-d H:i:s");
				$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
				$desc = json_encode($result);
				$this->log_model->cronlog("SIFFY CSV Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."************ SIFFY CSV Details Cron Execution End ".$end_time." *************"."\n");
				fclose($fp1);
			}
		}
		
		/* CSV Cron For CSC Vendor : 29/May/2019 : Bhushan */
    public function exam_csv_csc_old()
    {
			ini_set("memory_limit", "-1");
			$dir_flg        = 0;
			$parent_dir_flg = 0;
			$exam_file_flg  = 0;
			$success        = array();
			$error          = array();
			$start_time     = date("Y-m-d H:i:s");
			$current_date   = date("Ymd");
			$cron_file_dir  = "./uploads/cronCSV_csc/";
			$result         = array(
			"success" => "",
			"error" => "",
			"Start Time" => $start_time,
			"End Time" => ""
			);
			$desc           = json_encode($result);
			$this->log_model->cronlog("CSC CSV Cron Execution Start", $desc);
			if (!file_exists($cron_file_dir . $current_date)) {
				$parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
			}
			if (file_exists($cron_file_dir . $current_date)) {
				$cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
				$file           = "iibfportal_" . $current_date . ".csv";
				$fp             = fopen($cron_file_path . '/' . $file, 'w');
				$file1          = "logs_" . $current_date . ".txt";
				$fp1            = fopen($cron_file_path . '/' . $file1, 'a');
				fwrite($fp1, "\n***** CSC CSV Cron Execution Started - " . $start_time . " ***** \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2019-05-22';
				$exam_code = array('991');
				$select    = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date,a.exam_medium,a.exam_center_code,d.venueid,d.pwd';
				$this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
				$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
				$this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
				$this->db->where_in('a.exam_code', $exam_code);
				$can_exam_data = $this->Master_model->getRecords('member_exam a', array(
				'pay_type' => 2,
				'status' => 1,
				'isactive' => '1',
				'isdeleted' => 0,
				'pay_status' => 1,
				'bankcode' => 'csc',
				'DATE(a.created_on)' => $yesterday
				), $select);
				//echo $this->db->last_query();
				if (count($can_exam_data)) {
					$i             = 1;
					$exam_cnt      = 0;
					// Column headers for CSV            
					$data1         = "First Name,Middle name,Last Name,Mem. Number,Password,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt,Registration Date,Exam Medium,Exam Center Code,Venue Code \n";
					$exam_file_flg = fwrite($fp, $data1);
					foreach ($can_exam_data as $exam) {
						$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $registration_date = $data = $syllabus_code = $subject_description = $subject_code = $subject_description = $institution_name = $designation_name = $exam_code = $exam_name = $medium_name = '';
						
						if ($exam['exam_code'] != '' && $exam['exam_code'] != 0) {
							$ex_code = $this->master_model->getRecords('exam_activation_master', array(
							'exam_code' => $exam['exam_code']
							));
							if (count($ex_code)) {
								if ($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0) {
									$exam_code = $ex_code[0]['original_val'];
								} 
								else {
									$exam_code = $exam['exam_code'];
								}
							}
						} 
						else {
							$exam_code = $exam['exam_code'];
						}
						$dateofbirth       = date('m/d/Y', strtotime($exam['dateofbirth']));
						$registration_date = date('m/d/Y', strtotime($exam['registration_date']));
						$address           = $exam['address1'] . ' ' . $exam['address2'] . ' ' . $exam['address3'] . ' ' . $exam['address4'] . ' ' . $exam['district'] . ' ' . $exam['city'];
						$address           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
						$gender            = $exam['gender'];
						if ($gender == 'male' || $gender == 'Male') {
							$gender = 'M';
							} elseif($gender == 'female' || $gender == 'Female') {
							$gender = 'F';
						}
						$designation = $this->master_model->getRecords('designation_master');
						if (count($designation)) {
							foreach ($designation as $designation_row) {
								if ($exam['designation'] == $designation_row['dcode']) {
									$designation_name = $designation_row['dname'];
								}
							}
						}
						$designation_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
						
						$medium = $this->master_model->getRecords('medium_master');
						if (count($medium)) {
							foreach ($medium as $medium_row) {
								if ($exam['exam_medium'] == $medium_row['medium_code']) {
									$medium_name = $medium_row['medium_description'];
								}
							}
						}
						$medium_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $medium_name);
						
						$institution_master = $this->master_model->getRecords('institution_master');
						if (count($institution_master)) {
							foreach ($institution_master as $institution_row) {
								if ($exam['associatedinstitute'] == $institution_row['institude_id']) {
									$institution_name = $institution_row['name'];
								}
							}
						}
						$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
						$firstname        = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
						$middlename       = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
						$lastname         = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
						$mobile           = preg_replace('/[^0-9]/', '', $exam['mobile']);
						$pincode          = preg_replace('/[^0-9]/', '', $exam['pincode']);
						
						$exam_arr         = array('991'=>'CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS/FACILITATORS');
						
						foreach ($exam_arr as $k => $val) {
							if ($exam_code == $k) {
								$exam_name = $val;
							}
						}
						
						/* First Name|Middle name|Last Name|Mem. Number|Password|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession|Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt|Registration Date|Exam Medium|Exam Center Code|Venue Code */
						
						$data .= '' . $firstname . ',' . $middlename . ',' . $lastname . ',' . $exam['regnumber'] . ',' . $exam['pwd'] .',' . $dateofbirth . ',' . $gender . ',' . $exam['email'] . ',' . $mobile . ',' . $address . ',' . $exam['state'] . ',' . $pincode . ',' . 'INDIA' . ',' . '' . ',' . $institution_name . ',' . $designation_name . ',' . $exam_code . ',' . $exam_name . ',' . $subject_code . ',' . $subject_description . ',' . '' . ',' . $registration_date . ',' . $medium_name.',' . $exam['exam_center_code'] . ',' . $exam['venueid']."\n";
						
						$exam_file_flg = fwrite($fp, $data);
						if ($exam_file_flg)
						$success['cand_exam'] = "CSC CSV File Generated Successfully.";
						else
						$error['cand_exam'] = "Error While Generating CSC CSV File.";
						$i++;
						$exam_cnt++;
					}
					fwrite($fp1, "Total Applications - " . $exam_cnt . "\n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_csv_csc', $insert_info, true);
					} else {
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
					// File Rename Functinality
					$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
					$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_0.csv";
					rename($oldPath, $newPath);
					$OldName     = "iibfportal_" . $current_date . ".csv";
					$NewName     = "iibfportal_" . date('dmYhi') . "_0.csv";
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s')
					);
					$this->master_model->insertRecord('cron_csv_csc', $insert_info, true);
					$success[] = "No data found for the date";
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
				$this->log_model->cronlog("CSC CSV Cron Execution End", $desc);
				fwrite($fp1, "\n" . "***** CSC CSV Cron Execution End " . $end_time . " ******" . "\n");
				fclose($fp1);
			}
		}
		
		// tbl_sify_elearning
		public function exam_csv_temp()
		{
			exit;
			ini_set("memory_limit", "-1");
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$exam_file_flg = 0;
			$success = array();
			$error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	
			$cron_file_dir = "./uploads/cronCSV/";
			//Cron start Logs
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("IMPS CSV Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "iibfportal_".$current_date.".csv";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n***** IMPS CSV Details Cron Execution Started - ".$start_time." *********** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2020-04-22';
				
				$exam_code = array($this->config->item('examCodeJaiib'),$this->config->item('examCodeCaiib'));
				$select = 'user_name,email_id,contact_no,member_number,exam_code,exam_name,subject_code,subject_name,created_on';
				$this->db->where_in('exam_code',$exam_code);
				$can_exam_data = $this->Master_model->getRecords('tbl_sify_elearning',array('DATE(created_on)'=>$yesterday),$select);
				
				//echo "<br>SQL => ".$this->db->last_query(); //exit;
				
				if(count($can_exam_data))
				{
					$i = 1;
					$exam_cnt = 0;
					
					// Column headers			
					$data1 = "First Name,Middle name,Last Name,Mem. Number,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt \n";
					$exam_file_flg = fwrite($fp, $data1);
					
					foreach($can_exam_data as $exam)
					{
						$data = $firstname = $middlename = $lastname = $regnumber = $dateofbirth = $gender = $email = $mobile = $address = $state = $pincode = $institution_name = $designation_name = $exam_code = $exam_name = $subject_code = $subject_description = '';
						
						$mobile = preg_replace('/[^0-9]/', '', $exam['contact_no']);
						$firstname = preg_replace('/[^A-Za-z\/ ]/', '', $exam['user_name']);
						$exam_name = preg_replace('/[^A-Za-z\/ ]/', '', $exam['exam_name']);
						$exam_code = $exam['exam_code'];
						$subject_code = $exam['subject_code'];
						$subject_description = preg_replace('/[^A-Za-z\/ ]/', '', $exam['subject_name']);
						$regnumber = $exam['member_number'];
						$email = $exam['email_id'];
						
						//First Name|Middle name|Last Name|Mem. Number|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession	|Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt
						$data .= ''.$firstname.','.$middlename.','.$lastname.','.$regnumber.','.$dateofbirth.','.$gender.','.$email.','.$mobile.','.$address.','.$state.','.$pincode.','.'INDIA'.','.''.','.$institution_name.','.$designation_name.','.$exam_code.','.$exam_name.','.$subject_code.','.$subject_description.','.''."\n";
						
						$exam_file_flg = fwrite($fp, $data);
						
						if($exam_file_flg)
						$success['cand_exam'] = "IMPS CSV Details File Generated Successfully.";
						else
						$error['cand_exam'] = "Error While Generating IMPS CSV Details File.";
						
						$i++;
						$exam_cnt++;
					}
					fwrite($fp1, "Total Exam Applications - ".$exam_cnt."\n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/iibfportal_".$current_date.".csv";
					$newPath = $cron_file_dir.$current_date."/iibfportal_".date('dmYhi')."_".$exam_cnt.".csv";
					rename($oldPath,$newPath);
					
					$OldName = "iibfportal_".$current_date.".csv";
					$NewName = "iibfportal_".date('dmYhi')."_".$exam_cnt.".csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_csv', $insert_info, true);
					
				}
				else
				{
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: ".$yesterday." \n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/iibfportal_".$current_date.".csv";
					$newPath = $cron_file_dir.$current_date."/iibfportal_".date('dmYhi')."_0.csv";
					rename($oldPath,$newPath);
					
					$OldName = "iibfportal_".$current_date.".csv";
					$NewName = "iibfportal_".date('dmYhi')."_0.csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_csv', $insert_info, true);
					
					$success[] = "No data found for the date";
				}
				fclose($fp);
				
				// Cron End Logs
				$end_time = date("Y-m-d H:i:s");
				$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
				$desc = json_encode($result);
				$this->log_model->cronlog("IMPS CSV Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."***** IMPS CSV Details Cron Execution End ".$end_time." ******"."\n");
				fclose($fp1);
			}
		}
		
		/* CSV Cron For kesdee Vendor : 19/April/2018 : Bhushan */
		public function exam_csv_kesdee_old()
		{
			ini_set("memory_limit", "-1");
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$exam_file_flg = 0;
			$success = array();
			$error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	
			$cron_file_dir = "./uploads/cronCSV_kesdee/";
			//Cron start Logs
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("Examination CSV Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "iibfportal_".$current_date.".csv";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n***** Examination CSV Details Cron Execution Started - ".$start_time." *********** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2017-11-06';
				
				$exam_code = array('34','340','3400','151','34000');
				$select = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id';
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
				$this->db->where_in('a.exam_code', $exam_code);
				$can_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'DATE(a.created_on)'=>$yesterday),$select);
				
				//echo "<br>SQL => ".$this->db->last_query(); //exit;
				
				if(count($can_exam_data))
				{
					$i = 1;
					$exam_cnt = 0;
					
					// Column headers			
					$data1 = "First Name,Middle name,Last Name,Mem. Number,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt \n";
					$exam_file_flg = fwrite($fp, $data1);
					
					foreach($can_exam_data as $exam)
					{
						$data = '';					
						$syllabus_code = '';
						$subject_description = '';
						
						// get admit card details for this member by mem_exam_id
						$mem_exam_id = $exam['mem_exam_id'];
						$selectCol = 'DISTINCT(exm_cd),exm_prd,sub_cd,sub_dsc';
						$this->db->where('remark', 1);
						$this->db->group_by('exm_cd');
						$admit_card_details_arr = $this->Master_model->getRecords('admit_card_details',array('mem_exam_id' => $mem_exam_id),$selectCol);
						
						$subject_code = $subject_description = '';
						if(count($admit_card_details_arr))
						{	
							foreach($admit_card_details_arr as $admit_card_data)
							{
								$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period'],'group_code'=>'E','subject_code' =>$exam['elected_sub_code']),'',array('id'=>'DESC'),0,1);
								if(count($subject_data)>0)
								{
									$subject_code = $subject_data[0]['subject_code'];
									$subject_description = $subject_data[0]['subject_description'];
									$subject_description = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $subject_description);
								}
								
								$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = '';
								
								$exam_code = '';
								if($exam['exam_code'] != '' && $exam['exam_code'] != 0){
									$ex_code = $this->master_model->getRecords('exam_activation_master',array('exam_code'=>$exam['exam_code']));
									if(count($ex_code)){
										if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0)
										{	$exam_code = $ex_code[0]['original_val'];	}
										else
										{	$exam_code = $exam['exam_code'];	}
									}
								}
								else{ $exam_code = $exam['exam_code'];	}
								
								$elected_sub_code = '';
								if($exam_code == $this->config->item('examCodeCaiib')){ $elected_sub_code = $exam['elected_sub_code'];	}
								
								$dateofbirth = date('m/d/Y',strtotime($exam['dateofbirth']));
								$address = $exam['address1'].' '.$exam['address2'].' '.$exam['address3'].' '.$exam['address4'].' '.$exam['district'].' '.$exam['city'];
								$address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
								
								$gender = $exam['gender'];
								if($gender == 'male') {	$gender = 'M';	} else { $gender = 'F'; }
								
								$designation_name = '';
								$designation  = $this->master_model->getRecords('designation_master');
								if(count($designation)){
									foreach($designation as $designation_row){
										if($exam['designation']==$designation_row['dcode']){
										$designation_name = $designation_row['dname'];}
									} 
								}	
								$designation_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
								
								
								$institution_name = '';
								$institution_master = $this->master_model->getRecords('institution_master');	
								if(count($institution_master)){
									foreach($institution_master as $institution_row){ 	
										if($exam['associatedinstitute']==$institution_row['institude_id']){
										$institution_name = $institution_row['name'];}
									}
								}
								$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
								
								//$firstname = preg_replace('/[^A-Za-z]/', '', $exam['firstname']);
								$firstname = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
								$middlename = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
								$lastname = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
								$mobile = preg_replace('/[^0-9]/', '', $exam['mobile']);
								$pincode = preg_replace('/[^0-9]/', '', $exam['pincode']);
								
								$sub_cd = $admit_card_data['sub_cd'];
								$sub_dsc = $admit_card_data['sub_dsc'];
								
								$exam_name = '';
								$exam_arr = array('34'=>'ANTI-MONEY LAUNDERING AND KNOW YOUR CUSTOMER','340'=>'ANTI-MONEY LAUNDERING AND KNOW YOUR CUSTOMER','3400'=>'ANTI-MONEY LAUNDERING AND KNOW YOUR CUSTOMER','151'=>'DIPLOMA IN TREASURY INVESTMENT AND RISK MANAGEMENT','34000'=>'ANTI-MONEY LAUNDERING AND KNOW YOUR CUSTOMER');
								
								foreach($exam_arr as $k => $val){
									if($exam_code == $k) { $exam_name = $val; }
								}
								
								if($exam_code == '34' || $exam_code == '340' || $exam_code == '3400' || $exam_code == '151' || $exam_code == '34000')
								{
									$subject_code = '';
									$subject_description = '';
								}
								
								//First Name|Middle name|Last Name|Mem. Number|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession	|Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt
								$data .= ''.$firstname.','.$middlename.','.$lastname.','.$exam['regnumber'].','.$dateofbirth.','.$gender.','.$exam['email'].','.$mobile.','.$address.','.$exam['state'].','.$pincode.','.'INDIA'.','.''.','.$institution_name.','.$designation_name.','.$exam_code.','.$exam_name.','.$subject_code.','.$subject_description.','.''."\n";
								
								$exam_file_flg = fwrite($fp, $data);
								
								if($exam_file_flg)
								$success['cand_exam'] = "Examination CSV Details File Generated Successfully.";
								else
								$error['cand_exam'] = "Error While Generating Examination CSV Details File.";
								
								$i++;
								$exam_cnt++;
							}
						}
					}
					fwrite($fp1, "Total Exam Applications - ".$exam_cnt."\n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/iibfportal_".$current_date.".csv";
					$newPath = $cron_file_dir.$current_date."/iibfportal_".date('dmYhi')."_".$exam_cnt.".csv";
					rename($oldPath,$newPath);
					
					$OldName = "iibfportal_".$current_date.".csv";
					$NewName = "iibfportal_".date('dmYhi')."_".$exam_cnt.".csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_csv_kesdee', $insert_info, true);
					
				}
				else
				{
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: ".$yesterday." \n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/iibfportal_".$current_date.".csv";
					$newPath = $cron_file_dir.$current_date."/iibfportal_".date('dmYhi')."_0.csv";
					rename($oldPath,$newPath);
					
					$OldName = "iibfportal_".$current_date.".csv";
					$NewName = "iibfportal_".date('dmYhi')."_0.csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_csv_kesdee', $insert_info, true);
					
					$success[] = "No data found for the date";
				}
				fclose($fp);
				
				// Cron End Logs
				$end_time = date("Y-m-d H:i:s");
				$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
				$desc = json_encode($result);
				$this->log_model->cronlog("Examination CSV Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."***** Examination CSV Details Cron Execution End ".$end_time." ******"."\n");
				fclose($fp1);
			}
		}
		
		/* CSV Cron For kesdee Vendor : 19/April/2018 : Bhushan */
		public function exam_csv_kesdee_09_06_2020()
		{
			ini_set("memory_limit", "-1");
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$exam_file_flg = 0;
			$success = array();
			$error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");
			$cron_file_dir = "./uploads/cronCSV_kesdee/";
			//Cron start Logs
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("Examination CSV Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "iibfportal_".$current_date.".csv";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************ Examination CSV Details Cron Execution Started - ".$start_time." *********** \n");
				
				$mem_arr = array();
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2019-11-01';
				
				$exam_code = array('34','340','3400','34000');
				$select = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id';
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
				$this->db->where_in('a.exam_code', $exam_code);
				$can_exam_data1 = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'DATE(a.created_on)'=>$yesterday),$select);
				
				//echo "<br><br> 1 >>".$this->db->last_query();
				
				$exam_code = array('151');
				$select = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.exam_fee';
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
				$this->db->where_in('a.exam_code', $exam_code);
				$can_exam_data2 = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'DATE(a.created_on)'=>$yesterday),$select);
				//echo "<br><br> 2 >>".$this->db->last_query();
				
				$current_dates = date('Y-m-d');
				//$current_dates = '22019-11-01';
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$select = 'cs_tot';
				$this->db->where_in('exam_code', $exam_code);
				$this->db->where("('$current_dates' BETWEEN fr_date AND `to_date`)");
				$this->db->where("('$yesterday' BETWEEN fr_date AND `to_date`)");
				$fee_master = $this->Master_model->getRecords('fee_master',array('exam_code'=>'151','group_code'=>'B1_1','fee_delete'=>0),$select);
				//echo "<br><br> 3 >>".$this->db->last_query();
				
				if(count($fee_master)<=0)
				{
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					//$yesterday = '2019-11-01';
					
					$select = 'cs_tot';
					$this->db->where_in('exam_code', $exam_code);
					$this->db->where("('$yesterday' BETWEEN fr_date AND `to_date`)");
					$fee_master = $this->Master_model->getRecords('fee_master',array('exam_code'=>'151','group_code'=>'B1_1','fee_delete'=>0),$select);
				}
				
				if(count($can_exam_data2))
				{ $i= 0;
					foreach($can_exam_data2 as $row)
					{
						if($fee_master[0]['cs_tot']==$row['exam_fee'])
						{
							$mem_arr[$i]['transaction_no'] = $row['transaction_no'];
							$mem_arr[$i]['exam_code'] = $row['exam_code'];
							$mem_arr[$i]['firstname'] = $row['firstname'];
							$mem_arr[$i]['middlename'] = $row['middlename'];
							$mem_arr[$i]['lastname'] = $row['lastname'];
							$mem_arr[$i]['regnumber'] = $row['regnumber'];
							$mem_arr[$i]['dateofbirth'] = $row['dateofbirth'];
							$mem_arr[$i]['gender'] = $row['gender'];
							$mem_arr[$i]['email'] = $row['email'];
							$mem_arr[$i]['stdcode'] = $row['stdcode'];
							$mem_arr[$i]['office_phone'] = $row['office_phone'];
							$mem_arr[$i]['mobile'] = $row['mobile'];
							$mem_arr[$i]['address1'] = $row['address1'];
							$mem_arr[$i]['address2'] = $row['address2'];
							$mem_arr[$i]['address3'] = $row['address3'];
							$mem_arr[$i]['address4'] = $row['address4'];
							$mem_arr[$i]['district'] = $row['district'];
							$mem_arr[$i]['city'] = $row['city'];
							$mem_arr[$i]['state'] = $row['state'];
							$mem_arr[$i]['pincode'] = $row['pincode'];
							$mem_arr[$i]['associatedinstitute'] = $row['associatedinstitute'];
							$mem_arr[$i]['designation'] = $row['designation'];
							$mem_arr[$i]['exam_period'] = $row['exam_period'];
							$mem_arr[$i]['elected_sub_code'] = $row['elected_sub_code'];
							$mem_arr[$i]['editedon'] = $row['editedon'];
							$mem_arr[$i]['examination_date'] = $row['examination_date'];
							$mem_arr[$i]['mem_exam_id'] = $row['mem_exam_id'];
						}
						$i++;
					}
					
				}
				
				//echo '<pre>';
				//print_r($mem_arr);
				$can_exam_data = array_merge($can_exam_data1,$mem_arr);
				
				//echo "<br>SQL => ".$this->db->last_query(); //exit;
				//echo '<pre>';
				//print_r($can_exam_data);
				
				if(count($can_exam_data))
				{
					$i = 1;
					$exam_cnt = 0;
					
					// Column headers			
					$data1 = "First Name,Middle name,Last Name,Mem. Number,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt \n";
					$exam_file_flg = fwrite($fp, $data1);
					
					foreach($can_exam_data as $exam)
					{
						$data = '';					
						$syllabus_code = '';
						$subject_description = '';
						
						// get admit card details for this member by mem_exam_id
						$mem_exam_id = $exam['mem_exam_id'];
						$selectCol = 'DISTINCT(exm_cd),exm_prd,sub_cd,sub_dsc';
						$this->db->where('remark', 1);
						$this->db->group_by('exm_cd');
						$admit_card_details_arr = $this->Master_model->getRecords('admit_card_details',array('mem_exam_id' => $mem_exam_id),$selectCol);
						
						$subject_code = $subject_description = '';
						if(count($admit_card_details_arr))
						{	
							foreach($admit_card_details_arr as $admit_card_data)
							{
								$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period'],'group_code'=>'E','subject_code' =>$exam['elected_sub_code']),'',array('id'=>'DESC'),0,1);
								if(count($subject_data)>0)
								{
									$subject_code = $subject_data[0]['subject_code'];
									$subject_description = $subject_data[0]['subject_description'];
									$subject_description = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $subject_description);
								}
								
								$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = '';
								
								$exam_code = '';
								if($exam['exam_code'] != '' && $exam['exam_code'] != 0){
									
									$ex_code = $this->master_model->getRecords('exam_activation_master',array('exam_code'=>$exam['exam_code']));
									if(count($ex_code)){
										if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0)
										{	$exam_code = $ex_code[0]['original_val'];	}
										else
										{	$exam_code = $exam['exam_code'];	}
									}
								}
								else{ $exam_code = $exam['exam_code'];	}
								
								$elected_sub_code = '';
								if($exam_code == $this->config->item('examCodeCaiib')){ $elected_sub_code = $exam['elected_sub_code'];	}
								
								$dateofbirth = date('m/d/Y',strtotime($exam['dateofbirth']));
								$address = $exam['address1'].' '.$exam['address2'].' '.$exam['address3'].' '.$exam['address4'].' '.$exam['district'].' '.$exam['city'];
								$address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
								
								$gender = $exam['gender'];
								if($gender == 'male') {	$gender = 'M';	} else { $gender = 'F'; }
								
								$designation_name = '';
								$designation  = $this->master_model->getRecords('designation_master');
								if(count($designation)){
									foreach($designation as $designation_row){
										if($exam['designation']==$designation_row['dcode']){
										$designation_name = $designation_row['dname'];}
									} 
								}	
								$designation_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
								
								$institution_name = '';
								$institution_master = $this->master_model->getRecords('institution_master');	
								if(count($institution_master)){
									foreach($institution_master as $institution_row){ 	
										if($exam['associatedinstitute']==$institution_row['institude_id']){
										$institution_name = $institution_row['name'];}
									}
								}
								$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
								
								//$firstname = preg_replace('/[^A-Za-z]/', '', $exam['firstname']);
								$firstname = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
								$middlename = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
								$lastname = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
								$mobile = preg_replace('/[^0-9]/', '', $exam['mobile']);
								$pincode = preg_replace('/[^0-9]/', '', $exam['pincode']);
								
								$sub_cd = $admit_card_data['sub_cd'];
								$sub_dsc = $admit_card_data['sub_dsc'];
								
								$exam_name = '';
								$exam_arr = array('34'=>'ANTI-MONEY LAUNDERING AND KNOW YOUR CUSTOMER','340'=>'ANTI-MONEY LAUNDERING AND KNOW YOUR CUSTOMER','3400'=>'ANTI-MONEY LAUNDERING AND KNOW YOUR CUSTOMER','151'=>'DIPLOMA IN TREASURY INVESTMENT AND RISK MANAGEMENT','34000'=>'ANTI-MONEY LAUNDERING AND KNOW YOUR CUSTOMER');
								
								foreach($exam_arr as $k => $val){
									if($exam_code == $k) { $exam_name = $val; }
								}
								
								if($exam_code == '34' || $exam_code == '340' || $exam_code == '3400' || $exam_code == '151' || $exam_code == '34000')
								{
									$subject_code = '';
									$subject_description = '';
								}
								
								//First Name|Middle name|Last Name|Mem. Number|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession	|Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt
								$data .= ''.$firstname.','.$middlename.','.$lastname.','.$exam['regnumber'].','.$dateofbirth.','.$gender.','.$exam['email'].','.$mobile.','.$address.','.$exam['state'].','.$pincode.','.'INDIA'.','.''.','.$institution_name.','.$designation_name.','.$exam_code.','.$exam_name.','.$subject_code.','.$subject_description.','.''."\n";
								
								$exam_file_flg = fwrite($fp, $data);
								
								if($exam_file_flg)
								$success['cand_exam'] = "Examination CSV Details File Generated Successfully.";
								else
								$error['cand_exam'] = "Error While Generating Examination CSV Details File.";
								
								$i++;
								$exam_cnt++;
							}
						}
					}
					fwrite($fp1, "Total Exam Applications - ".$exam_cnt."\n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/iibfportal_".$current_date.".csv";
					$newPath = $cron_file_dir.$current_date."/iibfportal_".date('dmYhi')."_".$exam_cnt.".csv";
					rename($oldPath,$newPath);
					
					$OldName = "iibfportal_".$current_date.".csv";
					$NewName = "iibfportal_".date('dmYhi')."_".$exam_cnt.".csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => $exam_cnt,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_csv_kesdee', $insert_info, true);
					
				}
				else
				{
					$yesterday = date('Y-m-d', strtotime("- 1 day"));
					fwrite($fp1, "No data found for the date: ".$yesterday." \n");
					
					// File Rename Functinality
					$oldPath = $cron_file_dir.$current_date."/iibfportal_".$current_date.".csv";
					$newPath = $cron_file_dir.$current_date."/iibfportal_".date('dmYhi')."_0.csv";
					rename($oldPath,$newPath);
					
					$OldName = "iibfportal_".$current_date.".csv";
					$NewName = "iibfportal_".date('dmYhi')."_0.csv";
					
					$insert_info = array(
					'CurrentDate' => $current_date,
					'old_file_name' => $OldName,
					'new_file_name' => $NewName,
					'record_count' => 0,
					'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_csv_kesdee', $insert_info, true);
					
					$success[] = "No data found for the date";
				}
				fclose($fp);
				
				// Cron End Logs
				$end_time = date("Y-m-d H:i:s");
				$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
				$desc = json_encode($result);
				$this->log_model->cronlog("Examination CSV Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."************ Examination CSV Details Cron Execution End ".$end_time." *************"."\n");
				fclose($fp1);
			}
		}

		/* CSV Cron For Teamlease Vendor : 25/December/2022 : Pooja Mane */
		public function exam_csv_teamlease()
		{

				ini_set("memory_limit", "-1");
				$dir_flg        = 0;
				$parent_dir_flg = 0;
				$exam_file_flg  = 0;
				$success        = array();
				$error          = array();
				$start_time     = date("Y-m-d H:i:s");
				$current_date   = date("Ymd");
				$cron_file_dir  = "./uploads/cronCSV_teamlease/";
				$result         = array(
				"success" => "",
				"error" => "",
				"Start Time" => $start_time,
				"End Time" => ""
				);
				$desc           = json_encode($result);
				//$this->log_model->cronlog("Teamlease CSV Cron Execution Start", $desc);
				if (!file_exists($cron_file_dir . $current_date)) {
					$parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
				}
				if (file_exists($cron_file_dir . $current_date)) {
					$cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
					$file           = "iibfportal_" . $current_date . ".csv";
					$fp             = fopen($cron_file_path . '/' . $file, 'w');
					$file1          = "logs_" . $current_date . ".txt";
					$fp1            = fopen($cron_file_path . '/' . $file1, 'a');
					fwrite($fp1, "\n********* Teamlease CSV Cron Execution Started - " . $start_time . " ********* \n");
					$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime($current_date)));
				
					
					$exam_code = array('528', '529', '530', '531', '534');
					// $member_no = array(802067633);
					$select    = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname,a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date';
					$this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
					$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
					$this->db->where_in('a.exam_code', $exam_code);
					// $this->db->where_in('a.regnumber', $member_no);
					$can_exam_data = $this->Master_model->getRecords('member_exam a', array(
					'pay_type' => 18,
					'status' => 1,
					'isactive' => '1',
					'isdeleted' => 0,
					'pay_status' => 1,
					'DATE(a.created_on)' => $yesterday 
					), $select);
					//,
					//echo $this->db->last_query();
					if (count($can_exam_data)) {
						$i             = 1;
						$exam_cnt      = 0;
						// Column headers for CSV            
						$data1         = "First Name,Middle name,Last Name,Mem. Number,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt,Registration Date \n";
						$exam_file_flg = fwrite($fp, $data1);
						foreach ($can_exam_data as $exam) {
							$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $registration_date = $data = $syllabus_code = $subject_description = $subject_code = $subject_description = $institution_name = $designation_name = $exam_code = $exam_name = '';
							if ($exam['exam_code'] != '' && $exam['exam_code'] != 0) {
								$ex_code = $this->master_model->getRecords('exam_activation_master', array(
								'exam_code' => $exam['exam_code']
								));
								if (count($ex_code)) {
									if ($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0) {
										$exam_code = $ex_code[0]['original_val'];
										} else {
										$exam_code = $exam['exam_code'];
									}
								}
								} else {
								$exam_code = $exam['exam_code'];
							}
							$dateofbirth       = date('m/d/Y', strtotime($exam['dateofbirth']));
							$registration_date = date('m/d/Y', strtotime($exam['registration_date']));
							$address           = $exam['address1'] . ' ' . $exam['address2'] . ' ' . $exam['address3'] . ' ' . $exam['address4'] . ' ' . $exam['district'] . ' ' . $exam['city'];
							$address           = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
							$gender            = $exam['gender'];
							if ($gender == 'male') {
								$gender = 'M';
								} else {
								$gender = 'F';
							}
							$designation = $this->master_model->getRecords('designation_master');
							if (count($designation)) {
								foreach ($designation as $designation_row) {
									if ($exam['designation'] == $designation_row['dcode']) {
										$designation_name = $designation_row['dname'];
									}
								}
							}
							$designation_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
							$institution_master = $this->master_model->getRecords('institution_master');
							if (count($institution_master)) {
								foreach ($institution_master as $institution_row) {
									if ($exam['associatedinstitute'] == $institution_row['institude_id']) {
										$institution_name = $institution_row['name'];
									}
								}
							}
							$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
							$firstname        = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
							$middlename       = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
							$lastname         = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
							$mobile           = preg_replace('/[^0-9]/', '', $exam['mobile']);
							$pincode          = preg_replace('/[^0-9]/', '', $exam['pincode']);
							$exam_arr         = array(
							'528' => 'DIGITAL BANKING (E-LEARNING)',
							'529' => 'ETHICS IN BANKING (E-LEARNING)',
							'530'=>'Climate Risk & Sustainable Finance (Foundation) E-Learning',
              '531' => 'Certificate Course in Climate Risk & Sustainable Finance (Advanced) E-Learning Mode',
              '534' => 'Certificate Course in Project Finance (Foundation) E-Learning Mode'
							);
							foreach ($exam_arr as $k => $val) {
								if ($exam_code == $k) {
									$exam_name = $val;
								}
							}
							//First Name|Middle name|Last Name|Mem. Number|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession    |Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt|Registration Date
							$data .= '' . $firstname . ',' . $middlename . ',' . $lastname . ',' . $exam['regnumber'] . ',' . $dateofbirth . ',' . $gender . ',' . $exam['email'] . ',' . $mobile . ',' . $address . ',' . $exam['state'] . ',' . $pincode . ',' . 'INDIA' . ',' . '' . ',' . $institution_name . ',' . $designation_name . ',' . $exam_code . ',' . $exam_name . ',' . $subject_code . ',' . $subject_description . ',' . '' . ',' . $registration_date . "\n";
							$exam_file_flg = fwrite($fp, $data);
							if ($exam_file_flg)
							$success['cand_exam'] = "Teamlease CSV File Generated Successfully.";
							else
							$error['cand_exam'] = "Error While Generating Teamlease CSV File.";
							$i++;
							$exam_cnt++;
						}
						fwrite($fp1, "Total Applications - " . $exam_cnt . "\n");
						// File Rename Functinality
						$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
						$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmY', strtotime($current_date)).date('hi') . "_" . $exam_cnt . ".csv";
						rename($oldPath, $newPath);
						$OldName     = "iibfportal_" . $current_date . ".csv";
						//$NewName     = "iibfportal_" . date('dmY', strtotime($current_date)).date('hi') . "_" . $exam_cnt . ".csv";
						$NewName     = "iibfportal_" . date('dmY', strtotime($current_date)).date('hi'). "_" . $exam_cnt . ".csv";
						$insert_info = array(
						'CurrentDate' => $current_date,
						'old_file_name' => $OldName,
						'new_file_name' => $NewName,
						'record_count' => $exam_cnt,
						'createdon' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('cron_csv_teamlease', $insert_info, true);
						} else {
						$yesterday = date('Y-m-d', strtotime("- 1 day"));
						fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
						// File Rename Functinality
						$oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
						$newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmY', strtotime($current_date)).date('hi') . "_0.csv";
						rename($oldPath, $newPath);
						$OldName     = "iibfportal_" . $current_date . ".csv";
						$NewName     = "iibfportal_" . date('dmY', strtotime($current_date)).date('hi') . "_0.csv";
						$insert_info = array(
						'CurrentDate' => $current_date,
						'old_file_name' => $OldName,
						'new_file_name' => $NewName,
						'record_count' => 0,
						'createdon' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('cron_csv_teamlease', $insert_info, true);
						$success[] = "No data found for the date";
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
					//$this->log_model->cronlog("Teamlease CSV Cron Execution End", $desc);
					fwrite($fp1, "\n" . "********* Teamlease CSV Cron Execution End " . $end_time . " *********" . "\n");
					fclose($fp1);
				}
		}
	}
