<?php

/*
 	* Controller Name	:	KYC Cron File Generation
 	* Created By		:	Bhushan
 	* Created Date		:	30-07-2019
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_kyc extends CI_Controller {
			
	public function __construct(){
		parent::__construct();
		
		$this->load->model('UserModel');
		$this->load->model('Master_model');
		$this->load->model('log_model');
		$this->load->model('KYC_Log_model');
		
		$this->load->library('email');
		$this->load->model('Emailsending');
		
		/*define('MEM_FILE_PATH','/webonline/fromweb/images/newmem/');
		define('DRA_FILE_PATH','/webonline/fromweb/images/dra/');
		define('MEM_FILE_EDIT_PATH','/webonline/fromweb/images/edit/');*/
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}
	
	// Fuction to fetch KYC Recommended member data and export it in TXT format
	public function recommended()
	{
		//MEM_KYC_ID|MEM_MEM_NO|MEM_MEM_TYP|MEM_TLE|MEM_NAM_1|MEM_NAM_2|MEM_NAM_3|MEM_DOB|MEM_INS_CD|REC_MEM_NAM_FLG(Y/N)|REC_MEM_DOB_FLG(Y/N)|REC_MEM_INS_CD_FLG(Y/N)|REC_PHOTO_FLG(Y/N)|REC_SIGNATURE_FLG(Y/N)|REC_IDPROOFIMG_FLG(Y/N)|CREATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|UPDATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|REC_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|REC_BY_USER_ID|REC_BY_USER_NAME|REC_BY_USER_TYPE(R/A)|LIST_TYPE(NEW/EDIT)
		
		ini_set("memory_limit", "-1");
		
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");
			
		$cron_file_dir = "./uploads/kyc_cronfiles_pg/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("IIBF KYC Recommended Member Details Cron Execution Start", $desc);
		
		// create current date folder if not already exist
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			// cron file name
			$file = "member_kyc_recommended_".$current_date.".txt";
			
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			// create log file
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n************************* IIBF KYC Recommended Member Details Cron Execution Started - ".$start_time." ********************* \n");
			
			// get yesterday's date
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			
			$select = 'mk.kyc_id, mk.regnumber, mk.mem_type, mk.old_data, mk.mem_name, mk.mem_dob, mk.mem_associate_inst, mk.mem_photo, mk.mem_sign, mk.mem_proof, mr.createdon, mk.user_edited_date, mk.recommended_date, mk.recommended_by, mk.user_type, mk.record_source';
			$this->db->join('member_registration mr', 'mk.regnumber = mr.regnumber', 'LEFT');
			$member_kyc = $this->Master_model->getRecords('member_kyc mk', array(' DATE(mk.recommended_date)' => $yesterday, 'mk.kyc_status' => '0', 'mk.kyc_state' => 1, 'mr.isactive' => '1', 'mr.isactive' => '1', 'mr.isdeleted' => 0), $select);
			
			
			
			if(count($member_kyc))
			{
				
				
				$i = 1;
				$mem_cnt = 0;
				
				foreach($member_kyc as $kyc_data)
				{
					$data = '';
					
					// get old data
					$old_data = $kyc_data['old_data'];
					$unserialize_data = unserialize($old_data);
					
					
					
					$namesub = $unserialize_data[0]['namesub'];
					$firstname = $unserialize_data[0]['firstname'];
					$middlename = $unserialize_data[0]['middlename'];
					$lastname = $unserialize_data[0]['lastname'];
					$dateofbirth = $unserialize_data[0]['dateofbirth'];
					$associatedinstitute = $unserialize_data[0]['associatedinstitute'];
					
					$mem_dob = '';
					if($dateofbirth != '0000-00-00')
					{
						$mem_dob = date('d-M-y', strtotime($dateofbirth));
					}
					
					$REC_MEM_NAM_FLG = "N";
					if($kyc_data['mem_name'] == '0')
					{
						$REC_MEM_NAM_FLG = "Y";
					}
					
					$REC_MEM_DOB_FLG = "N";
					if($kyc_data['mem_dob'] == '0')
					{
						$REC_MEM_DOB_FLG = "Y";
					}
					
					$REC_MEM_INS_CD_FLG = "N";
					if($kyc_data['mem_associate_inst'] == '0')
					{
						$REC_MEM_INS_CD_FLG = "Y";
					}
					
					$REC_PHOTO_FLG = "N";
					if($kyc_data['mem_photo'] == '0')
					{
						$REC_PHOTO_FLG = "Y";
					}
					
					$REC_SIGNATURE_FLG = "N";
					if($kyc_data['mem_sign'] == '0')
					{
						$REC_SIGNATURE_FLG = "Y";
					}
					
					$REC_IDPROOFIMG_FLG = "N";
					if($kyc_data['mem_proof'] == '0')
					{
						$REC_IDPROOFIMG_FLG = "Y";
					}
					
					$CREATED_ON = '';
					if($kyc_data['createdon'] != '0000-00-00 00:00:00')
					{
						$CREATED_ON = date('d-M-y H:i:s', strtotime($kyc_data['createdon']));
					}
					
					$UPDATED_ON = '';
					if($kyc_data['user_edited_date'] != '0000-00-00 00:00:00')
					{
						$UPDATED_ON = date('d-M-y H:i:s', strtotime($kyc_data['user_edited_date']));
					}
					
					$REC_ON = date('d-M-y H:i:s', strtotime($kyc_data['recommended_date']));
					
					$REC_BY_USER_ID = $kyc_data['recommended_by'];
					
					// get kyc admin name
					$kyc_admin = $this->Master_model->getRecords('administrators', array(' id' => $kyc_data['recommended_by']), 'name,username', FALSE, FALSE, FALSE);
					
					$REC_BY_USER_NAME = '';
					if(count($kyc_admin))
					{
						$kyc_admin_name = $kyc_admin[0]['name'];
						$kyc_admin_username = $kyc_admin[0]['username'];
						
						$REC_BY_USER_NAME = $kyc_admin_username;
					}
					
					// get kyc user type (R/A)
					$user_type = $kyc_data['user_type'];
						
					$REC_BY_USER_TYPE = '';
					if($user_type == 'recommender')
					{
						$REC_BY_USER_TYPE = "R";
					}
					elseif($user_type == 'approver')
					{
						$REC_BY_USER_TYPE = "A";	
					}
					
					$LIST_TYPE = $kyc_data['record_source'];
					
					$data .= ''.$kyc_data['kyc_id'].'|'.$kyc_data['regnumber'].'|'.$kyc_data['mem_type'].'|'.$namesub.'|'.$firstname.'|'.$middlename.'|'.$lastname.'|'.$mem_dob.'|'.$associatedinstitute.'|'.$REC_MEM_NAM_FLG.'|'.$REC_MEM_DOB_FLG.'|'.$REC_MEM_INS_CD_FLG.'|'.$REC_PHOTO_FLG.'|'.$REC_SIGNATURE_FLG.'|'.$REC_IDPROOFIMG_FLG.'|'.$CREATED_ON.'|'.$UPDATED_ON.'|'.$REC_ON.'|'.$REC_BY_USER_ID.'|'.$REC_BY_USER_NAME.'|'.$REC_BY_USER_TYPE.'|'.$LIST_TYPE."\n";
					
					$i++;
					$mem_cnt++;
					
					$file_w_flg = fwrite($fp, $data);
					
					if($file_w_flg)
					{
						$success['file'] = "KYC Recommended Member Details File Generated Successfully. ";
					}
					else
					{
						$error['file'] = "Error While Generating KYC Recommended Member Details File.";
					}
				}
				
				fwrite($fp1, "\n"."Total KYC Recommended Member Added = ".$mem_cnt."\n");
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
			$this->log_model->cronlog("IIBF KYC Recommended Member Details Cron Execution End", $desc);
			
			fwrite($fp1, "\n"."************************* IIBF KYC Recommended Member Details Cron Execution End ".$end_time." **************************"."\n");
			fclose($fp1);
		}
	}
	
	// Fuction to fetch KYC Approved member data and export it in TXT format
	public function approved()
	{
		//MEM_KYC_ID|MEM_MEM_NO|MEM_MEM_TYP|MEM_TLE|MEM_NAM_1|MEM_NAM_2|MEM_NAM_3|MEM_DOB|MEM_INS_CD|REC_MEM_NAM_FLG(Y)|REC_MEM_DOB_FLG(Y)|REC_MEM_INS_CD_FLG(Y)|REC_PHOTO_FLG(Y)|REC_SIGNATURE_FLG(Y)|REC_IDPROOFIMG_FLG(Y)|CREATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|UPDATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|REC_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|REC_BY_USER_ID|REC_BY_USER_NAME|REC_BY_USER_TYPE(R/A)|APPROVED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|APPROVED_BY_USER_ID|APPROVED_BY_USER_NAME|LIST_TYPE(NEW/EDIT)
		
		ini_set("memory_limit", "-1");
		
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");
			
		$cron_file_dir = "./uploads/kyc_cronfiles_pg/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("IIBF KYC Approved Member Details Cron Execution Start", $desc);
		
		// create current date folder if not already exist
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			// cron file name
			$file = "member_kyc_approved_".$current_date.".txt";
			
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			// create log file
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n************************* IIBF KYC Approved Member Details Cron Execution Started - ".$start_time." ********************* \n");
			
			// get yesterday's date
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			
			$select = 'mk.kyc_id, mk.regnumber, mk.mem_type, mk.old_data, mk.mem_name, mk.mem_dob, mk.mem_associate_inst, mk.mem_photo, mk.mem_sign, mk.mem_proof, mr.createdon, mk.user_edited_date, mk.recommended_date, mk.recommended_by, mk.user_type, mk.approved_by, mk.approved_date, mk.record_source';
			$this->db->join('member_registration mr', 'mk.regnumber = mr.regnumber', 'LEFT');
			$member_kyc = $this->Master_model->getRecords('member_kyc mk', array(' DATE(mk.approved_date)' => $yesterday, 'mk.kyc_status' => '1', 'mk.kyc_state' => 3, 'mr.isactive' => '1', 'mr.isdeleted' => 0), $select);
			
			
			
			if(count($member_kyc))
			{
				
				
				$i = 1;
				$mem_cnt = 0;
				
				foreach($member_kyc as $kyc_data)
				{
					$data = '';
					
					// get old data
					$old_data = $kyc_data['old_data'];
					$unserialize_data = unserialize($old_data);
					
					
					
					$namesub = $unserialize_data[0]['namesub'];
					$firstname = $unserialize_data[0]['firstname'];
					$middlename = $unserialize_data[0]['middlename'];
					$lastname = $unserialize_data[0]['lastname'];
					$dateofbirth = $unserialize_data[0]['dateofbirth'];
					$associatedinstitute = $unserialize_data[0]['associatedinstitute'];
					
					$mem_dob = '';
					if($dateofbirth != '0000-00-00')
					{
						$mem_dob = date('d-M-y', strtotime($dateofbirth));
					}
					
					/*$REC_MEM_NAM_FLG = "N";
					if($kyc_data['mem_name'] == '0')
					{
						$REC_MEM_NAM_FLG = "Y";
					}
					
					$REC_MEM_DOB_FLG = "N";
					if($kyc_data['mem_dob'] == '0')
					{
						$REC_MEM_DOB_FLG = "Y";
					}
					
					$REC_MEM_INS_CD_FLG = "N";
					if($kyc_data['mem_associate_inst'] == '0')
					{
						$REC_MEM_INS_CD_FLG = "Y";
					}
					
					$REC_PHOTO_FLG = "N";
					if($kyc_data['mem_photo'] == '0')
					{
						$REC_PHOTO_FLG = "Y";
					}
					
					$REC_SIGNATURE_FLG = "N";
					if($kyc_data['mem_sign'] == '0')
					{
						$REC_SIGNATURE_FLG = "Y";
					}
					
					$REC_IDPROOFIMG_FLG = "N";
					if($kyc_data['mem_proof'] == '0')
					{
						$REC_IDPROOFIMG_FLG = "Y";
					}*/
					
					$REC_MEM_NAM_FLG = "Y";
					$REC_MEM_DOB_FLG = "Y";
					$REC_MEM_INS_CD_FLG = "Y";
					$REC_PHOTO_FLG = "Y";
					$REC_SIGNATURE_FLG = "Y";
					$REC_IDPROOFIMG_FLG = "Y";
					
					$CREATED_ON = '';
					if($kyc_data['createdon'] != '0000-00-00 00:00:00')
					{
						$CREATED_ON = date('d-M-y H:i:s', strtotime($kyc_data['createdon']));
					}
					
					$UPDATED_ON = '';
					if($kyc_data['user_edited_date'] != '0000-00-00 00:00:00')
					{
						$UPDATED_ON = date('d-M-y H:i:s', strtotime($kyc_data['user_edited_date']));
					}
					
					$REC_ON = date('d-M-y H:i:s', strtotime($kyc_data['recommended_date']));
					
					$APPROVED_ON = date('d-M-y H:i:s', strtotime($kyc_data['approved_date']));
					
					$REC_BY_USER_ID = $kyc_data['recommended_by'];
					
					// get kyc admin recommender name
					$kyc_admin = $this->Master_model->getRecords('administrators', array(' id' => $kyc_data['recommended_by']), 'name,username', FALSE, FALSE, FALSE);
					
					$REC_BY_USER_NAME = '';
					if(count($kyc_admin))
					{
						$kyc_admin_name = $kyc_admin[0]['name'];
						$kyc_admin_username = $kyc_admin[0]['username'];
						
						$REC_BY_USER_NAME = $kyc_admin_username;
					}
					
					$APPROVED_BY_USER_ID = $kyc_data['approved_by'];
					
					// get kyc admin approver name
					$kyc_admin = $this->Master_model->getRecords('administrators', array(' id' => $kyc_data['approved_by']), 'name,username', FALSE, FALSE, FALSE);
					
					$APPROVED_BY_USER_NAME = '';
					if(count($kyc_admin))
					{
						$kyc_admin_name = $kyc_admin[0]['name'];
						$kyc_admin_username = $kyc_admin[0]['username'];
						
						$APPROVED_BY_USER_NAME = $kyc_admin_username;
					}
					
					$user_type = $kyc_data['user_type'];
						
					$REC_BY_USER_TYPE = '';
					if($user_type == 'recommender')
					{
						$REC_BY_USER_TYPE = "R";
					}
					elseif($user_type == 'approver')
					{
						$REC_BY_USER_TYPE = "A";	
					}
					
					$LIST_TYPE = $kyc_data['record_source'];
					
					$data .= ''.$kyc_data['kyc_id'].'|'.$kyc_data['regnumber'].'|'.$kyc_data['mem_type'].'|'.$namesub.'|'.$firstname.'|'.$middlename.'|'.$lastname.'|'.$mem_dob.'|'.$associatedinstitute.'|'.$REC_MEM_NAM_FLG.'|'.$REC_MEM_DOB_FLG.'|'.$REC_MEM_INS_CD_FLG.'|'.$REC_PHOTO_FLG.'|'.$REC_SIGNATURE_FLG.'|'.$REC_IDPROOFIMG_FLG.'|'.$CREATED_ON.'|'.$UPDATED_ON.'|'.$REC_ON.'|'.$REC_BY_USER_ID.'|'.$REC_BY_USER_NAME.'|'.$REC_BY_USER_TYPE.'|'.$APPROVED_ON.'|'.$APPROVED_BY_USER_ID.'|'.$APPROVED_BY_USER_NAME.'|'.$LIST_TYPE."\n";
					
					$i++;
					$mem_cnt++;
					
					$file_w_flg = fwrite($fp, $data);
					
					if($file_w_flg)
					{
						$success['file'] = "KYC Approved Member Details File Generated Successfully. ";
					}
					else
					{
						$error['file'] = "Error While Generating KYC Approved Member Details File.";
					}
				}
				
				fwrite($fp1, "\n"."Total KYC Approved Member Added = ".$mem_cnt."\n");
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
			$this->log_model->cronlog("IIBF KYC Approved Member Details Cron Execution End", $desc);
			
			fwrite($fp1, "\n"."************************* IIBF KYC Approved Member Details Cron Execution End ".$end_time." **************************"."\n");
			fclose($fp1);
		}
	}
	
	// Fuction to fetch KYC Edited member data and export it in TXT format
	public function edited()
	{
		//MEM_KYC_ID|MEM_MEM_NO|MEM_MEM_TYP|MEM_TLE|MEM_NAM_1|MEM_NAM_2|MEM_NAM_3|MEM_DOB|MEM_INS_CD|REC_MEM_NAM_FLG(Y/N)|REC_MEM_DOB_FLG(Y/N)|REC_MEM_INS_CD_FLG(Y/N)|REC_PHOTO_FLG(Y/N)|REC_SIGNATURE_FLG(Y/N)|REC_IDPROOFIMG_FLG(Y/N)|EDIT_MEM_NAM_FLG(Y/N)|EDIT_MEM_DOB_FLG(Y/N)|EDIT_MEM_INS_CD_FLG(Y/N)|EDIT_PHOTO_FLG(Y/N)|EDIT_SIGNATURE_FLG(Y/N)|EDIT_IDPROOFIMG_FLG(Y/N)|CREATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|UPDATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|REC_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|REC_BY_USER_ID|REC_BY_USER_NAME|REC_BY_USER_TYPE(R/A)|LIST_TYPE(NEW/EDIT)
		
		ini_set("memory_limit", "-1");
		
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");
			
		$cron_file_dir = "./uploads/kyc_cronfiles_pg/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("IIBF KYC Edited Member Details Cron Execution Start", $desc);
		
		// create current date folder if not already exist
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			// cron file name
			$file = "member_kyc_edited_".$current_date.".txt";
			
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			// create log file
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n************************* IIBF KYC Edited Member Details Cron Execution Started - ".$start_time." ********************* \n");
			
			// get yesterday's date
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			
			$select = 'mk.kyc_id, mk.regnumber, mk.mem_type, mk.old_data, mk.mem_name, mk.mem_dob, mk.mem_associate_inst, mk.mem_photo, mk.mem_sign, mk.mem_proof, mr.createdon, mk.user_edited_date, mk.recommended_date, mk.recommended_by, mk.user_type, mk.approved_by, mk.approved_date, mk.record_source, mk.edited_mem_name, mk.edited_mem_dob, mk.edited_mem_associate_inst, mk.edited_mem_photo, mk.edited_mem_sign, mk.edited_mem_proof';
			$this->db->join('member_registration mr', 'mk.regnumber = mr.regnumber', 'LEFT');
			$member_kyc = $this->Master_model->getRecords('member_kyc mk', array(' DATE(mk.user_edited_date)' => $yesterday, 'mk.kyc_status' => '0', 'mk.kyc_state' => 2, 'mr.isactive' => '1', 'mr.isdeleted' => 0), $select);
			
			
			
			if(count($member_kyc))
			{
				/*echo "<pre>";
				print_r($member_kyc);*/
				
				$i = 1;
				$mem_cnt = 0;
				
				foreach($member_kyc as $kyc_data)
				{
					$data = '';
					
					// get old data
					$old_data = $kyc_data['old_data'];
					$unserialize_data = unserialize($old_data);
					
					
					
					$namesub = $unserialize_data[0]['namesub'];
					$firstname = $unserialize_data[0]['firstname'];
					$middlename = $unserialize_data[0]['middlename'];
					$lastname = $unserialize_data[0]['lastname'];
					$dateofbirth = $unserialize_data[0]['dateofbirth'];
					$associatedinstitute = $unserialize_data[0]['associatedinstitute'];
					
					$mem_dob = '';
					if($dateofbirth != '0000-00-00')
					{
						$mem_dob = date('d-M-y', strtotime($dateofbirth));
					}
					
					// recommended flag start [ 0 - Not OK Means Recommeded Field, 1 - OK Means Not Recommeded Field ]
					$REC_MEM_NAM_FLG = "N";
					if($kyc_data['mem_name'] == '0')
					{
						$REC_MEM_NAM_FLG = "Y";
					}
					
					$REC_MEM_DOB_FLG = "N";
					if($kyc_data['mem_dob'] == '0')
					{
						$REC_MEM_DOB_FLG = "Y";
					}
					
					$REC_MEM_INS_CD_FLG = "N";
					if($kyc_data['mem_associate_inst'] == '0')
					{
						$REC_MEM_INS_CD_FLG = "Y";
					}
					
					$REC_PHOTO_FLG = "N";
					if($kyc_data['mem_photo'] == '0')
					{
						$REC_PHOTO_FLG = "Y";
					}
					
					$REC_SIGNATURE_FLG = "N";
					if($kyc_data['mem_sign'] == '0')
					{
						$REC_SIGNATURE_FLG = "Y";
					}
					
					$REC_IDPROOFIMG_FLG = "N";
					if($kyc_data['mem_proof'] == '0')
					{
						$REC_IDPROOFIMG_FLG = "Y";
					}
					// recommended flag end
					
					// edited flag start [ 0 - Not Edited Field, 1 - Edited Field ]
					$EDIT_MEM_NAM_FLG = "N";
					if($kyc_data['edited_mem_name'] == '1')
					{
						$EDIT_MEM_NAM_FLG = "Y";
					}
					
					$EDIT_MEM_DOB_FLG = "N";
					if($kyc_data['edited_mem_dob'] == '1')
					{
						$EDIT_MEM_DOB_FLG = "Y";
					}
					
					$EDIT_MEM_INS_CD_FLG = "N";
					if($kyc_data['edited_mem_associate_inst'] == '1')
					{
						$EDIT_MEM_INS_CD_FLG = "Y";
					}
					
					$EDIT_PHOTO_FLG = "N";
					if($kyc_data['edited_mem_photo'] == '1')
					{
						$EDIT_PHOTO_FLG = "Y";
					}
					
					$EDIT_SIGNATURE_FLG = "N";
					if($kyc_data['edited_mem_sign'] == '1')
					{
						$EDIT_SIGNATURE_FLG = "Y";
					}
					
					$EDIT_IDPROOFIMG_FLG = "N";
					if($kyc_data['edited_mem_proof'] == '1')
					{
						$EDIT_IDPROOFIMG_FLG = "Y";
					}
					// edited flag end

					$CREATED_ON = '';
					if($kyc_data['createdon'] != '0000-00-00 00:00:00')
					{
						$CREATED_ON = date('d-M-y H:i:s', strtotime($kyc_data['createdon']));
					}
					
					$UPDATED_ON = '';
					if($kyc_data['user_edited_date'] != '0000-00-00 00:00:00')
					{
						$UPDATED_ON = date('d-M-y H:i:s', strtotime($kyc_data['user_edited_date']));
					}
					
					$REC_ON = date('d-M-y H:i:s', strtotime($kyc_data['recommended_date']));
					
					$REC_BY_USER_ID = $kyc_data['recommended_by'];
					
					// get kyc admin name
					$kyc_admin = $this->Master_model->getRecords('administrators', array(' id' => $kyc_data['recommended_by']), 'name,username', FALSE, FALSE, FALSE);
					
					$REC_BY_USER_NAME = '';
					if(count($kyc_admin))
					{
						$kyc_admin_name = $kyc_admin[0]['name'];
						$kyc_admin_username = $kyc_admin[0]['username'];
						
						$REC_BY_USER_NAME = $kyc_admin_username;
					}
					
					$user_type = $kyc_data['user_type'];
						
					$REC_BY_USER_TYPE = '';
					if($user_type == 'recommender')
					{
						$REC_BY_USER_TYPE = "R";
					}
					elseif($user_type == 'approver')
					{
						$REC_BY_USER_TYPE = "A";	
					}
					
					$LIST_TYPE = $kyc_data['record_source'];
					
					$data .= ''.$kyc_data['kyc_id'].'|'.$kyc_data['regnumber'].'|'.$kyc_data['mem_type'].'|'.$namesub.'|'.$firstname.'|'.$middlename.'|'.$lastname.'|'.$mem_dob.'|'.$associatedinstitute.'|'.$REC_MEM_NAM_FLG.'|'.$REC_MEM_DOB_FLG.'|'.$REC_MEM_INS_CD_FLG.'|'.$REC_PHOTO_FLG.'|'.$REC_SIGNATURE_FLG.'|'.$REC_IDPROOFIMG_FLG.'|'.$EDIT_MEM_NAM_FLG.'|'.$EDIT_MEM_DOB_FLG.'|'.$EDIT_MEM_INS_CD_FLG.'|'.$EDIT_PHOTO_FLG.'|'.$EDIT_SIGNATURE_FLG.'|'.$EDIT_IDPROOFIMG_FLG.'|'.$CREATED_ON.'|'.$UPDATED_ON.'|'.$REC_ON.'|'.$REC_BY_USER_ID.'|'.$REC_BY_USER_NAME.'|'.$REC_BY_USER_TYPE.'|'.$LIST_TYPE."\n";
					
					$i++;
					$mem_cnt++;
					
					$file_w_flg = fwrite($fp, $data);
					
					if($file_w_flg)
					{
						$success['file'] = "KYC Edited Member Details File Generated Successfully. ";
					}
					else
					{
						$error['file'] = "Error While Generating KYC Edited Member Details File.";
					}
				}
				
				fwrite($fp1, "\n"."Total KYC Edited Member Added = ".$mem_cnt."\n");
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
			$this->log_model->cronlog("IIBF KYC Edited Member Details Cron Execution End", $desc);
			
			fwrite($fp1, "\n"."************************* IIBF KYC Edited Member Details Cron Execution End ".$end_time." **************************"."\n");
			fclose($fp1);
		}
	}
	
	// Fuction to fetch KYC Email data and export it in TXT format
	public function email()
	{
		//MEM_KYC_ID|MEM_MEM_NO|EMAIL_TYPE(RECOMMEND/APPROVE/SENDMAIL/REMINDER)|EMAIL_CONTENT|REM_CNT(0/1/2)|EMAIL_SENT_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|EMAIL_SENT_BY_USER_ID|EMAIL_SENT_BY_USER_NAME|EMAIL_SENT_BY_USER_TYPE(REC/APP/ADM/CRN)
		
		// updated by Bhagwan Sahane, on 20-07-2017
		// MEM_MEM_TYP column added new
	//MEM_KYC_ID|MEM_MEM_NO|MEM_MEM_TYP|EMAIL_TYPE(RECOMMEND/APPROVE/SENDMAIL/REMINDER)|EMAIL_CONTENT|REM_CNT(0/1/2)|EMAIL_SENT_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|EMAIL_SENT_BY_USER_ID|EMAIL_SENT_BY_USER_NAME|EMAIL_SENT_BY_USER_TYPE(REC/APP/ADM/CRN)
		
		ini_set("memory_limit", "-1");
		
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");
			
		$cron_file_dir = "./uploads/kyc_cronfiles_pg/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("IIBF KYC Email Details Cron Execution Start", $desc);
		
		// create current date folder if not already exist
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			// cron file name
			$file = "member_kyc_email_".$current_date.".txt";
			
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			// create log file
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n************************* IIBF KYC Email Details Cron Execution Started - ".$start_time." ********************* \n");
			
			// get yesterday's date
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			
			//$select = 'kel.kyc_id, kel.regnumber, kel.type, kel.email, kel.email_reminder_count, kel.email_date, kel.user_id, kel.user_type';
			//$kyc_email_logs = $this->Master_model->getRecords('kyc_email_logs kel', array(' DATE(kel.email_date)' => $yesterday), $select);
			
			$select = 'kel.kyc_id, kel.regnumber, mk.mem_type, kel.type, kel.email, kel.email_reminder_count, kel.email_date, kel.user_id, kel.user_type';
			$this->db->group_by('kel.kyc_id');
			$this->db->where('kel.user_id !=','0');
			$this->db->join('member_kyc mk', 'kel.kyc_id = mk.kyc_id', 'LEFT');
			$kyc_email_logs = $this->Master_model->getRecords('kyc_email_logs kel', array(' DATE(kel.email_date)' => $yesterday), $select);
			
			
			
			if(count($kyc_email_logs))
			{
				
				
				$i = 1;
				$mem_cnt = 0;
				
				foreach($kyc_email_logs as $kyc_data)
				{
					$data = '';
					
					// get email data
					$email_data = $kyc_data['email'];
					$unserialize_email_data = unserialize($email_data);
					
					//print_r($unserialize_email_data); die();
					
					$to = $unserialize_email_data['to'];
					$from = $unserialize_email_data['from'];
					$subject = $unserialize_email_data['subject'];
					$message = $unserialize_email_data['message'];
					
					$MEM_KYC_ID = $kyc_data['kyc_id'];
					
					$EMAIL_TYPE = '';
					if($kyc_data['type'] == '0')
					{
						$EMAIL_TYPE = "RECOMMEND";
					}
					elseif($kyc_data['type'] == '1')
					{
						$EMAIL_TYPE = "APPROVE";
					}
					elseif($kyc_data['type'] == '2')
					{
						$EMAIL_TYPE = "SENDMAIL";
					}
					elseif($kyc_data['type'] == '3')
					{
						$EMAIL_TYPE = "REMINDER";	
					}
					
					$REM_CNT = $kyc_data['email_reminder_count'];	// updated through reminder email cron
					
					$EMAIL_SENT_ON = date('d-M-y H:i:s', strtotime($kyc_data['email_date']));
					
					$EMAIL_SENT_BY_USER_ID = $kyc_data['user_id'];
					
					$EMAIL_SENT_BY_USER_NAME = '';
					
					$EMAIL_SENT_BY_USER_TYPE = '';
					if($kyc_data['user_type'] == 'recommender')
					{
						$EMAIL_SENT_BY_USER_TYPE = "REC";
						
						// get kyc admin name
						$kyc_admin = $this->Master_model->getRecords('administrators', array(' id' => $kyc_data['user_id']), 'name,username', FALSE, FALSE, FALSE);
						
						if(count($kyc_admin))
						{
							$kyc_admin_name = $kyc_admin[0]['name'];
							$kyc_admin_username = $kyc_admin[0]['username'];
							
							$EMAIL_SENT_BY_USER_NAME = $kyc_admin_username;
						}
					}
					elseif($kyc_data['user_type'] == 'approver')
					{
						$EMAIL_SENT_BY_USER_TYPE = "APP";
						
						// get kyc admin name
						$kyc_admin = $this->Master_model->getRecords('administrators', array(' id' => $kyc_data['user_id']), 'name,username', FALSE, FALSE, FALSE);
						
						if(count($kyc_admin))
						{
							$kyc_admin_name = $kyc_admin[0]['name'];
							$kyc_admin_username = $kyc_admin[0]['username'];
							
							$EMAIL_SENT_BY_USER_NAME = $kyc_admin_username;
						}	
					}
					elseif($kyc_data['user_type'] == 'admin')
					{
						$EMAIL_SENT_BY_USER_TYPE = "ADM";	// updated through kyc admin
						
						// get kyc admin name
						$kyc_admin = $this->Master_model->getRecords('administrators', array(' id' => $kyc_data['user_id']), 'name,username', FALSE, FALSE, FALSE);
						
						if(count($kyc_admin))
						{
							$kyc_admin_name = $kyc_admin[0]['name'];
							$kyc_admin_username = $kyc_admin[0]['username'];
							
							$EMAIL_SENT_BY_USER_NAME = $kyc_admin_username;
						}
					}
					elseif($kyc_data['user_type'] == 'cron')
					{
						$EMAIL_SENT_BY_USER_TYPE = "CRN";	// updated through reminder email cron
						
						$EMAIL_SENT_BY_USER_NAME = 'Cron';	// default kyc cron user
					}
					
					$data .= ''.$kyc_data['kyc_id'].'|'.$kyc_data['regnumber'].'|'.$kyc_data['mem_type'].'|'.$EMAIL_TYPE.'|'.trim(preg_replace('/\s+/',' ', strip_tags($message))).'|'.$REM_CNT.'|'.$EMAIL_SENT_ON.'|'.$EMAIL_SENT_BY_USER_ID.'|'.$EMAIL_SENT_BY_USER_NAME.'|'.$EMAIL_SENT_BY_USER_TYPE."\n";
					
					$i++;
					$mem_cnt++;
					
					$file_w_flg = fwrite($fp, $data);
					
					if($file_w_flg)
					{
						$success['file'] = "KYC Email Details File Generated Successfully. ";
					}
					else
					{
						$error['file'] = "Error While Generating KYC Email Details File.";
					}
				}
				
				fwrite($fp1, "\n"."Total KYC Email Details Added = ".$mem_cnt."\n");
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
			$this->log_model->cronlog("IIBF KYC Email Details Cron Execution End", $desc);
			
			fwrite($fp1, "\n"."************************* IIBF KYC Email Details Cron Execution End ".$end_time." **************************"."\n");
			fclose($fp1);
		}
	}
	
	// Fuction to logout kyc user each day forcefully
	public function logout()
	{
		//die();
		
		$msg = '';
		
		// Cron Start Logs
		$start_time = date("Y-m-d H:i:s");
		$result = array("Start Time" => $start_time, "End Time" => "", "Message" => $msg);
		$desc = json_encode($result);
		$this->log_model->cronlog("IIBF KYC Logout Cron Execution Start", $desc);
		
		// get yesterday's date
		$yesterday = date('Y-m-d', strtotime("- 1 day"));
		
		// get all the allocations of previous day
		$admin_kyc_users = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => $yesterday),'kyc_user_id,allotted_member_id');
		
		if(count($admin_kyc_users) > 0)
		{
			foreach($admin_kyc_users as $row)
			{
				$kyc_user_id = $row['kyc_user_id'];
				$allotted_member_id = $row['allotted_member_id'];
				
				//log activity 
				$this->KYC_Log_model->create_log('KYC User Logout', '', false, false, $allotted_member_id);
				
				//echo $this->db->last_query(); die();
				
				// empty all the allocations of previous day
				$this->master_model->updateRecord('admin_kyc_users', array('allotted_member_id' => ''), array('kyc_user_id' => $kyc_user_id));
				
				$msg .= 'Records Updated Successfully|';
			}
		}
		else
		{
			$msg .= 'No data found for the date';	
		}
		
		// Cron End Logs
		$end_time = date("Y-m-d H:i:s");
		$result = array("Start Time" => $start_time, "End Time" => $end_time, "Message" => $msg);
		$desc = json_encode($result);
		$this->log_model->cronlog("IIBF KYC Logout Cron Execution End", $desc);
	}
	
	// Fuction to send reminder email after 7 and 14 days
	public function reminder($rem_count = 1)
	{
		$today = date("Y-m-d H:i:s");
		
		$msg = '';
		
		// Cron Start Logs
		$start_time = date("Y-m-d H:i:s");
		$result = array("Start Time" => $start_time, "End Time" => "", "Message" => $msg);
		$desc = json_encode($result);
		$this->log_model->cronlog("IIBF KYC Reminder ".$rem_count." Cron Execution Start", $desc);
		
		// get reminder date
		$rem_date = '';
		if($rem_count == 1) // reminder after 7 days
		{
			$date_before_7_days = date('Y-m-d', strtotime("- 7 days"));
			
			$rem_date = $date_before_7_days;
		}
		else // reminder after 14 days
		{
			$date_before_14_days = date('Y-m-d', strtotime("- 14 days"));
			
			$rem_date = $date_before_14_days;	
		}
		
		//$rem_date = '2017-06-05';
		
		//$select = 'mk.kyc_id, mk.regnumber, mk.mem_name, mk.mem_dob, mk.mem_associate_inst, mk.mem_photo, mk.mem_sign, mk.mem_proof, mk.recommended_date, kel.email';
		//$this->db->join('kyc_email_logs kel', 'kel.kyc_id = mk.kyc_id AND kel.regnumber = mk.regnumber', 'LEFT');
		
		$select = 'mk.kyc_id, mk.regnumber, mk.mem_name, mk.mem_dob, mk.mem_associate_inst, mk.mem_photo, mk.mem_sign, mk.mem_proof, mk.recommended_date';
		$member_kyc = $this->Master_model->getRecords('member_kyc mk', array(' DATE(mk.recommended_date)' => $rem_date, 'mk.kyc_status' => '0', 'mk.kyc_state' => 1, 'mk.field_count !=' => 0), $select);
		
		// SELECT `mk`.`kyc_id`, `mk`.`regnumber`, `mk`.`mem_name`, `mk`.`mem_dob`, `mk`.`mem_associate_inst`, `mk`.`mem_photo`, `mk`.`mem_sign`, `mk`.`mem_proof`, `mk`.`recommended_date` FROM `member_kyc` `mk` WHERE DATE(mk.recommended_date) = '2017-06-05' AND `mk`.`kyc_status` = '0' AND `mk`.`kyc_state` = 1 AND `mk`.`field_count` !=0 
		
		//echo $this->db->last_query(); die();
		
		if(count($member_kyc))
		{
			/*echo "<pre>";
			print_r($member_kyc);*/
			
			$i = 1;
			$mem_cnt = 0;
			
			$update_data = array();
			
			foreach($member_kyc as $kyc_data)
			{
				$data = '';
				
				$update_data = array();
				
				$kyc_id = $kyc_data['kyc_id'];
				$regnumber = $kyc_data['regnumber'];
				
				$recommended_date = date("d-m-Y", strtotime($kyc_data['recommended_date']));
				
				// get email data
				/*$email_data = $kyc_data['email'];
				$unserialize_data = unserialize($email_data);
				
				//print_r($unserialize_data); die();
				
				$to = $unserialize_data['to'];
				$from = $unserialize_data['from'];
				$subject = $unserialize_data['subject'];
				$message = $unserialize_data['message'];*/
				
				$userdata = $this->master_model->getRecords("member_registration",array('regnumber'=>$regnumber,'isactive'=>'1'));
				
				$username = $userdata[0]['namesub'].' '.$userdata[0]['firstname'].' '.$userdata[0]['middlename'].' '.$userdata[0]['lastname'];
				$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
				
				$useremail = $userdata[0]['email'];
					
				if($userdata[0]['registrationtype']=='O' || $userdata[0]['registrationtype']=='F' || $userdata[0]['registrationtype']=='A')
				{
					$emailerstr = $this->master_model->getRecords('emailer',array('emailer_name'=>'reminder_email_O'));
				}
				elseif($userdata[0]['registrationtype']=='DB' || $userdata[0]['registrationtype']=='NM')
				{
					$emailerstr = $this->master_model->getRecords('emailer',array('emailer_name'=>'reminder_email_NM'));
				}
				
				// recommended flag start [ 0 - Not OK Means Recommeded Field, 1 - OK Means Not Recommeded Field ]
				if($kyc_data['mem_name'] == '0')
				{
					$update_data[] = 'Name';
				}
				
				if($kyc_data['mem_dob'] == '0')
				{
					$update_data[]='DOB';
				}
				
				if($kyc_data['mem_associate_inst'] == '0')
				{
					$update_data[]='Employer';
				}
				
				if($kyc_data['mem_photo'] == '0')
				{
					$update_data[]='Photo';
				}
				
				if($kyc_data['mem_sign'] == '0')
				{
					$update_data[]='Sign';
				}
				
				if($kyc_data['mem_proof'] == '0')
				{
					$update_data[]='Id-proof';
				}
				// recommended flag end
				
				$msg=implode(',',$update_data);
				
				$newstring1 = str_replace("#REGNUMBER#", "".$regnumber."", $emailerstr[0]['emailer_text']);
				$newstring2 = str_replace("#USERNAME#", "".$userfinalstrname."", $newstring1);
				$newstring3 = str_replace("#DATE#", "".$recommended_date."", $newstring2);
				$final_str = str_replace("#MSG#", "".$msg."", $newstring3);
				
				//echo $final_str; die();
				
				$info_arr = array(
									//'to' => "kyciibf@gmail.com",
									'to' => $useremail,
									'from' => $emailerstr[0]['from'],
									'subject' => $emailerstr[0]['subject'],
									'message' => $final_str
							);
				
				if($this->Emailsending->mailsend($info_arr))
				{ 
					// email log
					$this->KYC_Log_model->email_log($kyc_id, 0, '3', $rem_count, $regnumber, serialize($info_arr), $today, 'cron');
				}
				
				$i++;
				$mem_cnt++;
			}
			
			$msg .= 'Email Sent Successfully';
		}
		else
		{
			$msg .= 'No data found for the date';
		}
		
		// Cron End Logs
		$end_time = date("Y-m-d H:i:s");
		$result = array("Start Time" => $start_time, "End Time" => $end_time, "Message" => $msg);
		$desc = json_encode($result);
		$this->log_model->cronlog("IIBF KYC Reminder ".$rem_count." Cron Execution End", $desc);
	}
	
		// Fuction to fetch Benchmark KYC Recommended member data and export it in TXT format
		public function benchmark_recommended()
		{
			ini_set("memory_limit", "-1");
			$parent_dir_flg = 0;
			$file_w_flg = 0;
			$success = array();
			$error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");
			$cron_file_dir = "./uploads/kyc_cronfiles_pg/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF KYC Benchmark Recommended Member Details Cron Execution Start", $desc);
			// create current date folder if not already exist
			if(!file_exists($cron_file_dir.$current_date)){
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				// cron file name
				$file = "benchmark_member_kyc_recommended_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				// create log file
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n********** IIBF Benchmark KYC Recommended Member Details Cron Execution Started - ".$start_time." ********************* \n");
				
				// get yesterday's date
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				
				//$yesterday = '2020-08-21';
				
				$select = 'mk.kyc_id, mk.regnumber, mk.old_data, mk.mem_visually, mk.mem_orthopedically, mk.mem_cerebral, mr.createdon, mk.user_edited_date, mk.recommended_date, mk.recommended_by, mk.user_type, mk.record_source';
				$this->db->join('member_registration mr', 'mk.regnumber = mr.regnumber', 'LEFT');
				$member_kyc = $this->Master_model->getRecords('benchmark_member_kyc mk', array(' DATE(mk.recommended_date)' => $yesterday, 'mk.kyc_status' => '0', 'mk.kyc_state' => 1, 'mr.isactive' => '1', 'mr.isactive' => '1', 'mr.isdeleted' => 0), $select);
				if(count($member_kyc))
				{
					$i = 1;
					$mem_cnt = 0;
					foreach($member_kyc as $kyc_data)
					{
						$data = '';
						$REC_VISUALLY_FLG = "N";
						if($kyc_data['mem_visually'] == '0')
						{
							$REC_VISUALLY_FLG = "Y";
						}
						elseif($kyc_data['mem_visually'] == '0')
						{
							$REC_VISUALLY_FLG = "NA";	
						}
						
						$REC_ORTHOPEDICALLY_FLG = "N";
						if($kyc_data['mem_orthopedically'] == '0')
						{
							$REC_ORTHOPEDICALLY_FLG = "Y";
						}
						elseif($kyc_data['mem_orthopedically'] == '3')
						{
							$REC_ORTHOPEDICALLY_FLG = "NA";
						}
						
						$REC_CEREBRAL_FLG = "N";
						if($kyc_data['mem_cerebral'] == '0')
						{
							$REC_CEREBRAL_FLG = "Y";
						}
						elseif($kyc_data['mem_cerebral'] == '3')
						{
							$REC_CEREBRAL_FLG = "NA";
						}
						
						$CREATED_ON = '';
						if($kyc_data['createdon'] != '0000-00-00 00:00:00')
						{
							$CREATED_ON = date('d-M-y H:i:s', strtotime($kyc_data['createdon']));
						}
						$UPDATED_ON = '';
						if($kyc_data['user_edited_date'] != '0000-00-00 00:00:00')
						{
							$UPDATED_ON = date('d-M-y H:i:s', strtotime($kyc_data['user_edited_date']));
						}
						$REC_ON = date('d-M-y H:i:s', strtotime($kyc_data['recommended_date']));
						$REC_BY_USER_ID = $kyc_data['recommended_by'];
						// get kyc admin name
						$kyc_admin = $this->Master_model->getRecords('administrators', array(' id' => $kyc_data['recommended_by']), 'name,username', FALSE, FALSE, FALSE);
						$REC_BY_USER_NAME = '';
						if(count($kyc_admin))
						{
							$kyc_admin_name = $kyc_admin[0]['name'];
							$kyc_admin_username = $kyc_admin[0]['username'];
							$REC_BY_USER_NAME = $kyc_admin_username;
						}
						// get kyc user type (R/A)
						$user_type = $kyc_data['user_type'];
						$REC_BY_USER_TYPE = '';
						if($user_type == 'recommender')
						{
							$REC_BY_USER_TYPE = "R";
						}
						elseif($user_type == 'approver')
						{
							$REC_BY_USER_TYPE = "A";	
						}
						$LIST_TYPE = $kyc_data['record_source'];
						//MEM_KYC_ID|MEM_MEM_NO|REC_VISUALLY_FLG(Y/N/NA)|REC_ORTHOPEDICALLY_FLG(Y/N/NA)|REC_CEREBRAL_FLG(Y/N/NA)|CREATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|UPDATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|REC_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|REC_BY_USER_ID|REC_BY_USER_NAME|REC_BY_USER_TYPE(R/A)|LIST_TYPE(NEW/EDIT)
						//$data .= ''.$kyc_data['kyc_id'].'|'.$kyc_data['regnumber'].'|'.$REC_VISUALLY_FLG.'|'.$REC_ORTHOPEDICALLY_FLG.'|'.$REC_CEREBRAL_FLG.'|'.$CREATED_ON.'|'.$UPDATED_ON.'|'.$REC_ON.'|'.$REC_BY_USER_ID.'|'.$REC_BY_USER_NAME.'|'.$REC_BY_USER_TYPE.'|'.$LIST_TYPE."\n";
						
						
						//MEM_KYC_ID|MEM_MEM_NO|REC_VISUALLY_FLG(Y/N/NA)|REC_ORTHOPEDICALLY_FLG(Y/N/NA)|REC_CEREBRAL_FLG(Y/N/NA)|CREATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|UPDATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|REC_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|REC_BY_USER_ID|REC_BY_USER_NAME|REC_BY_USER_TYPE(R/A)
						
						$data .= ''.$kyc_data['kyc_id'].'|'.$kyc_data['regnumber'].'|'.$REC_VISUALLY_FLG.'|'.$REC_ORTHOPEDICALLY_FLG.'|'.$REC_CEREBRAL_FLG.'|'.$CREATED_ON.'|'.$REC_ON.'|'.$REC_BY_USER_ID.'|'.$REC_BY_USER_NAME.'|'.$REC_BY_USER_TYPE."\n";
						
//10|510379944|N|NA|NA|01-Jul-20 18:01:45|21-Aug-20 10:54:13|30|recommender4|R
//11|511000109|Y|NA|NA|24-Jul-20 17:10:06|21-Aug-20 10:54:27|30|recommender4|R
						
						$i++;
						$mem_cnt++;
						$file_w_flg = fwrite($fp, $data);
						if($file_w_flg){
							$success['file'] = "Benchmark KYC Recommended Member Details File Generated Successfully. ";
						}
						else{
							$error['file'] = "Error While Generating Benchmark KYC Recommended Member Details File.";
						}
					}
					fwrite($fp1, "\n"."Total Benchmark KYC Recommended Member Added = ".$mem_cnt."\n");
				}
				else{
					$success[] = "No data found for the date";
				}
				fclose($fp);
				// Cron End Logs
				$end_time = date("Y-m-d H:i:s");
				$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
				$desc = json_encode($result);
				$this->log_model->cronlog("IIBF Benchmark KYC Recommended Member Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."********** IIBF Benchmark KYC Recommended Member Details Cron Execution End ".$end_time." ***********"."\n");
				fclose($fp1);
			}
		}
		// Fuction to fetch Benchmark KYC Approved member data and export it in TXT format
		public function benchmark_approved()
		{
			ini_set("memory_limit", "-1");
			$parent_dir_flg = 0;
			$file_w_flg = 0;
			$success = array();
			$error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");
			$cron_file_dir = "./uploads/kyc_cronfiles_pg/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF Benchmark KYC Approved Member Details Cron Execution Start", $desc);
			// create current date folder if not already exist
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				// cron file name
				$file = "benchmark_member_kyc_approved_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				// create log file
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n********** IIBF Benchmark KYC Approved Member Details Cron Execution Started - ".$start_time." ********************* \n");
				// get yesterday's date
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2020-08-21';
				
				$select = 'mk.kyc_id, mk.regnumber, mk.old_data, mk.mem_visually, mk.mem_orthopedically, mk.mem_cerebral, mr.createdon, mk.user_edited_date, mk.recommended_date, mk.recommended_by, mk.user_type, mk.approved_by, mk.approved_date, mk.record_source';
				$this->db->join('member_registration mr', 'mk.regnumber = mr.regnumber', 'LEFT');
				$member_kyc = $this->Master_model->getRecords('benchmark_member_kyc mk', array(' DATE(mk.approved_date)' => $yesterday, 'mk.kyc_status' => '1', 'mk.kyc_state' => 3, 'mr.isactive' => '1', 'mr.isdeleted' => 0), $select);
				if(count($member_kyc))
				{
					$i = 1;
					$mem_cnt = 0;
					foreach($member_kyc as $kyc_data)
					{
						$data = '';
						
						$REC_VISUALLY_FLG = "N";
						if($kyc_data['mem_visually'] == '1')
						{
							$REC_VISUALLY_FLG = "Y";
						}
						elseif($kyc_data['mem_visually'] == '3')
						{
							$REC_VISUALLY_FLG = "NA";
						}
						
						$REC_ORTHOPEDICALLY_FLG = "N";
						if($kyc_data['mem_orthopedically'] == '1')
						{
							$REC_ORTHOPEDICALLY_FLG = "Y";
						}
						elseif($kyc_data['mem_orthopedically'] == '3')
						{
							$REC_ORTHOPEDICALLY_FLG = "NA";
						}
						
						$REC_CEREBRAL_FLG = "N";
						if($kyc_data['mem_cerebral'] == '1')
						{
							$REC_CEREBRAL_FLG = "Y";
						}
						elseif($kyc_data['mem_cerebral'] == '3')
						{
							$REC_CEREBRAL_FLG = "NA";
						}
						
						
						$CREATED_ON = '';
						if($kyc_data['createdon'] != '0000-00-00 00:00:00')
						{
							$CREATED_ON = date('d-M-y H:i:s', strtotime($kyc_data['createdon']));
						}
						$UPDATED_ON = '';
						if($kyc_data['user_edited_date'] != '0000-00-00 00:00:00')
						{
							$UPDATED_ON = date('d-M-y H:i:s', strtotime($kyc_data['user_edited_date']));
						}
						$REC_ON = date('d-M-y H:i:s', strtotime($kyc_data['recommended_date']));
						$APPROVED_ON = date('d-M-y H:i:s', strtotime($kyc_data['approved_date']));
						$REC_BY_USER_ID = $kyc_data['recommended_by'];
						// get kyc admin recommender name
						$kyc_admin = $this->Master_model->getRecords('administrators', array(' id' => $kyc_data['recommended_by']), 'name,username', FALSE, FALSE, FALSE);
						$REC_BY_USER_NAME = '';
						if(count($kyc_admin))
						{
							$kyc_admin_name = $kyc_admin[0]['name'];
							$kyc_admin_username = $kyc_admin[0]['username'];
							$REC_BY_USER_NAME = $kyc_admin_username;
						}
						$APPROVED_BY_USER_ID = $kyc_data['approved_by'];
						// get kyc admin approver name
						$kyc_admin = $this->Master_model->getRecords('administrators', array(' id' => $kyc_data['approved_by']), 'name,username', FALSE, FALSE, FALSE);
						$APPROVED_BY_USER_NAME = '';
						if(count($kyc_admin))
						{
							$kyc_admin_name = $kyc_admin[0]['name'];
							$kyc_admin_username = $kyc_admin[0]['username'];
							$APPROVED_BY_USER_NAME = $kyc_admin_username;
						}
						$user_type = $kyc_data['user_type'];
						$REC_BY_USER_TYPE = '';
						if($user_type == 'recommender')
						{
							$REC_BY_USER_TYPE = "R";
						}
						elseif($user_type == 'approver')
						{
							$REC_BY_USER_TYPE = "A";	
						}
						$LIST_TYPE = $kyc_data['record_source'];
										//MEM_KYC_ID|MEM_MEM_NO|REC_VISUALLY_FLG(Y/N)|REC_ORTHOPEDICALLY_FLG(Y/N)|REC_CEREBRAL_FLG(Y/N)|CREATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|UPDATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|REC_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|REC_BY_USER_ID|REC_BY_USER_NAME|REC_BY_USER_TYPE(R/A)|APPROVED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'|APPROVED_BY_USER_ID|APPROVED_BY_USER_NAME|LIST_TYPE(NEW/EDIT)
						
						//$data .= ''.$kyc_data['kyc_id'].'|'.$kyc_data['regnumber'].'|'.$REC_VISUALLY_FLG.'|'.$REC_ORTHOPEDICALLY_FLG.'|'.$REC_CEREBRAL_FLG.'|'.$CREATED_ON.'|'.$UPDATED_ON.'|'.$REC_ON.'|'.$REC_BY_USER_ID.'|'.$REC_BY_USER_NAME.'|'.$REC_BY_USER_TYPE.'|'.$APPROVED_ON.'|'.$APPROVED_BY_USER_ID.'|'.$APPROVED_BY_USER_NAME.'|'.$LIST_TYPE."\n";
						
						$data .= ''.$kyc_data['kyc_id'].'|'.$kyc_data['regnumber'].'|'.$REC_VISUALLY_FLG.'|'.$REC_ORTHOPEDICALLY_FLG.'|'.$REC_CEREBRAL_FLG.'|'.$CREATED_ON.'|'.$REC_ON.'|'.$REC_BY_USER_ID.'|'.$REC_BY_USER_NAME.'|'.$REC_BY_USER_TYPE.'|'.$APPROVED_ON.'|'.$APPROVED_BY_USER_ID.'|'.$APPROVED_BY_USER_NAME."\n";

						
						$i++;
						$mem_cnt++;
						$file_w_flg = fwrite($fp, $data);
						if($file_w_flg)
						{
							$success['file'] = "Benchmark KYC Approved Member Details File Generated Successfully. ";
						}
						else
						{
							$error['file'] = "Error While Generating Benchmark KYC Approved Member Details File.";
						}
					}
					fwrite($fp1, "\n"."Total Benchmark KYC Approved Member Added = ".$mem_cnt."\n");
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
				$this->log_model->cronlog("IIBF Benchmark KYC Approved Member Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."********** IIBF Benchmark KYC Approved Member Details Cron Execution End ".$end_time." ***********"."\n");
				fclose($fp1);
			}
		}
		
}