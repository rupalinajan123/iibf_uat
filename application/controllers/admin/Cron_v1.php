<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cron extends CI_Controller {
	public $UserID;
			
	public function __construct(){
		parent::__construct();
		/*if($this->session->id==""){
			redirect('admin/Login');
		}*/	
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		//$this->UserID=$this->session->id;
		
		$this->load->helper('pagination_helper'); 
		$this->load->library('pagination');
		$this->load->model('log_model');
		
		define('MEM_FILE_PATH','/webonline/fromweb/images/newmem/');
		define('DRA_FILE_PATH','/webonline/fromweb/images/dra/');
		define('MEM_FILE_EDIT_PATH','/webonline/fromweb/images/edit/');
	}
	
	
	// By VSU : Fuction to fetch new member registration data and export it in TXT format
	public function member()
	{
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$photo_zip_flg = 0;
		$sign_zip_flg = 0;
		$idproof_zip_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("IIBF New Member Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "iibf_new_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$today = date('Y-m-d');
			//$this->db->join('payment_transaction b','b.ref_id=a.regid AND b.member_regnumber=a.regnumber','LEFT');
			//$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'pay_type'=>1));
			
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
			//echo $this->db->last_query();
			if(count($new_mem_reg))
			{
				/*echo "<pre>";
				print_r($new_mem_reg);*/
				$data = '';
				
				//echo $cron_file_path.'/'.$file;
				$dirname = "regd_image_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory))
				{
					array_map('unlink', glob($directory."/*.*"));
					rmdir($directory);
					$dir_flg = mkdir($directory, 0700);
				}
				else
				{
					$dir_flg = mkdir($directory, 0700);
				}
				
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				
				$i = 1;
				foreach($new_mem_reg as $reg_data)
				{
					$gender = '';
					if($reg_data['gender'] == 'male')	{ $gender = 'M';}
					else if($reg_data['gender'] == 'female')	{ $gender = 'F';}
					
					$photo 		= MEM_FILE_PATH.$reg_data['scannedphoto'];
					$signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
					
					$qualification = '';
					switch($reg_data['qualification'])
					{
						case "U"	: 	$qualification = 1;
										break;
						case "G"	: 	$qualification = 3;
										break;
						case "P"	: 	$qualification = 5;
										break;
					}
					
					$transaction_no = '';
					$transaction_date = '0000-00-00';
					$transaction_amt = '0';
					if($reg_data['registrationtype']!='NM')
					{
						$trans_details = $this->Master_model->getRecords('payment_transaction a',array(' DATE(date)'=>$yesterday,'status'=>1,'pay_type'=>1,'ref_id'=>$reg_data['regid']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
					}
					else
					{
						$trans_details = $this->Master_model->getRecords('payment_transaction a',array(' DATE(date)'=>$yesterday,'status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
					}
					if(count($trans_details))
					{
						$transaction_no = $trans_details[0]['transaction_no'];
						$transaction_amt = $trans_details[0]['amount'];
						$date = $trans_details[0]['date'];
						if($date!='0000-00-00')
						{	$transaction_date = date('d-M-y',strtotime($date));	}
					}
					
													
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['registrationtype'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname'].'|'.$reg_data['displayname'].'|'.$reg_data['address1'].'|'.$reg_data['address2'].'|'.$reg_data['address3'].'|'.$reg_data['address4'].'|'.$reg_data['district'].'|'.$reg_data['city'].'|'.$reg_data['pincode'].'|'.$reg_data['state'].'|'.date('d-M-y',strtotime($reg_data['dateofbirth'])).'|'.$gender.'|'.$qualification.'|'.$reg_data['specify_qualification'].'|'.$reg_data['associatedinstitute'].'|'.$reg_data['branch'].'|'.$reg_data['designation'].'|'.date('d-M-y',strtotime($reg_data['dateofjoin'])).'|'.$reg_data['email'].'|'.$reg_data['stdcode'].'|'.$reg_data['office_phone'].'|'.$reg_data['mobile'].'|'.$reg_data['idproof'].'|'.$reg_data['idNo'].'|'.$transaction_no.'|'.$transaction_date.'|'.$transaction_amt.'|'.$transaction_no.'|'.$reg_data['optnletter'].'|||'.$photo.'|'.$signature.'|'.$idproofimg.'|||||'."\n";
					
					if($dir_flg)
					{
						copy("./uploads/photograph/".$reg_data['scannedphoto'],$directory."/".$reg_data['scannedphoto']);
						copy("./uploads/scansignature/".$reg_data['scannedsignaturephoto'],$directory."/".$reg_data['scannedsignaturephoto']);
						copy("./uploads/idproof/".$reg_data['idproofphoto'],$directory."/".$reg_data['idproofphoto']);
						
						$photo_to_add = $directory."/".$reg_data['scannedphoto'];
						$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
						$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
						
						$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
						$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
						$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
						
						$proof_to_add = $directory."/".$reg_data['idproofphoto'];
						$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
						$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg)
						{
							$success['zip'] = "New Member Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating New Member Images Zip";
						}
					}
					
					$i++;
				}
				$file_w_flg = fwrite($fp, $data);
				if($file_w_flg)
				{
					$success['file'] = "New Member Details File Generated Successfully. ";
				}
				else
				{
					$error['file'] = "Error While Generating New Member Details File.";
				}
				
				$zip->close();
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
			$this->log_model->cronlog("IIBF New Member Details Cron Execution End", $desc);
		}
	}
	
	// By VSU : Fuction to fetch user's edited data and export it in TXT format
	public function edit_data()
	{
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$Zip_flg = 0;
		$photo_file_flg = 0;
		$sign_file_flg = 0;
		$idproof_file_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Edited Candidate Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "edited_cand_details_".$current_date.".txt";
			$photo_file = "edited_cand_details_photo_".$current_date.".txt";
			$sign_file = "edited_cand_details_sign_".$current_date.".txt";
			$id_file = "edited_cand_details_id_".$current_date.".txt";
			
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$photo_fp = fopen($cron_file_path.'/'.$photo_file, 'w');
			$sign_fp = fopen($cron_file_path.'/'.$sign_file, 'w');
			$id_fp = fopen($cron_file_path.'/'.$id_file, 'w');
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$today = date('Y-m-d');
			$edited_mem_data = $this->Master_model->getRecords('member_registration',array(' DATE(editedon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
			if(count($edited_mem_data))
			{
				$dirname = "edited_image_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory))
				{
					// Delete all the files in directory
					array_map('unlink', glob($directory."/*.*"));
					// Remove directory
					rmdir($directory);
					$dir_flg = mkdir($directory, 0700);
				}
				else
				{
					$dir_flg = mkdir($directory, 0700);
				}
				
				// Create a zip of images folder
				$zip = new ZipArchive;
				$Zip_flg = $zip->open($directory.'.zip', ZipArchive::CREATE);
				
				
				$i = 1;
				foreach($edited_mem_data as $editeddata)
				{
					$data = '';
					$photo_data = '';
					$sign_data = '';
					$id_data = '';
					
					$gender = '';
					if($editeddata['gender'] == 'male')	{ $gender = 'M';}
					else if($editeddata['gender'] == 'female')	{ $gender = 'F';}
					
					$qualification = '';
					switch($editeddata['qualification'])
					{
						case "U"	: 	$qualification = 1;
										break;
						case "G"	: 	$qualification = 3;
										break;
						case "P"	: 	$qualification = 5;
										break;
					}
					$photo = '';
					$signature = '';
					$idproofimg = '';
					
					if($editeddata['scannedphoto'] != "")
					{
						if(file_exists("./uploads/photograph/".$editeddata['scannedphoto']))
						{
							$photo 	= MEM_FILE_EDIT_PATH.$editeddata['scannedphoto'];
						}
					}
					if($editeddata['scannedsignaturephoto'] != "")
					{
						if(file_exists("./uploads/scansignature/".$editeddata['scannedsignaturephoto']))
						{
							$signature 	= MEM_FILE_EDIT_PATH.$editeddata['scannedsignaturephoto'];
						}
					}
					if($editeddata['idproofphoto'] != "")
					{
						if(file_exists("./uploads/idproof/".$editeddata['idproofphoto']))
						{
							$idproofimg = MEM_FILE_EDIT_PATH.$editeddata['idproofphoto'];
						}
					}
					
					
					//MEM_MEM_NO	,MEM_MEM_TYP	,MEM_TLE,	MEM_NAM_1,	MEM_NAM_2	,MEM_NAM_3,	ID_CARD_NAME,	MEM_ADR_1,	MEM_ADR_2,	MEM_ADR_3,	MEM_ADR_4,	MEM_ADR_5,	MEM_ADR_6,	MEM_PIN_CD,	MEM_STE_CD,	MEM_DOB,	MEM_SEX_CD,	MEM_QLF_GRD,	MEM_QLF_CD,	MEM_INS_CD,	BRANCH,	MEM_DSG_CD,	MEM_BNK_JON_DT ,EMAIL,	STD_R,	PHONE_R,	MOBILE,	ID_TYPE,	ID_NO,	BDRNO,	TRN_DATE,	TRN_AMT,	USR_ID,	AR_FLG,	filename_1 filler char(400),PHOTO LOBFILE (filename_1) TERMINATED BY EOF,	PHOTO_FLG,	SIGNATURE_FLG,	ID_FLG,	UPDATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'
					
					$data = ''.$editeddata['regnumber'].'|'.$editeddata['registrationtype'].'|'.$editeddata['namesub'].'|'.$editeddata['firstname'].'|'.$editeddata['middlename'].'|'.$editeddata['lastname'].'|'.$editeddata['displayname'].'|'.$editeddata['address1'].'|'.$editeddata['address2'].'|'.$editeddata['address3'].'|'.$editeddata['address4'].'|'.$editeddata['district'].'|'.$editeddata['city'].'|'.$editeddata['pincode'].'|'.$editeddata['state'].'|'.date('d-M-y',strtotime($editeddata['dateofbirth'])).'|'.$gender.'|'.$qualification.'|'.$editeddata['specify_qualification'].'|'.$editeddata['associatedinstitute'].'|'.$editeddata['branch'].'|'.$editeddata['designation'].'|'.date('d-M-y',strtotime($editeddata['dateofjoin'])).'|'.$editeddata['email'].'|'.$editeddata['stdcode'].'|'.$editeddata['office_phone'].'|'.$editeddata['mobile'].'|'.$editeddata['idproof'].'|'.$editeddata['idNo'].'||||'.$editeddata['editedby'].'|'.$editeddata['optnletter'].'|';
					if($photo != '')
					{
						if($editeddata['photo_flg']=='Y')
						{
							$photo_data = $data.''.$photo.'|Y|N|N|'.date('d-M-y H:i:s',strtotime($editeddata['editedon'])).'|'."\n";
							$photo_file_flg = fwrite($photo_fp, $photo_data);
						}
						if($photo_file_flg)
							$success['photo_file'] = "Edited Candidate Details Photo File Generated Successfully. ";
						else
							$error['photo_file'] = "Error While Generating Edited Candidate Details Photo File.";
					}
					
					if($signature != '')
					{
						if($editeddata['signature_flg']=='Y')
						{
							$sign_data = $data.''.$signature.'|N|Y|N|'.date('d-M-y H:i:s',strtotime($editeddata['editedon'])).'|'."\n";
							$sign_file_flg =  fwrite($sign_fp, $sign_data);
						}
						if($sign_file_flg)
							$success['sign_file'] = "Edited Candidate Details Signature File Generated Successfully.";
						else
							$error['sign_file'] = "Error While Generating Edited Candidate Details Signature File.";
					}
					
					if($idproofimg != '')
					{
						if($editeddata['id_flg']=='Y')
						{
							$id_data = $data.''.$idproofimg.'|N|N|Y|'.date('d-M-y H:i:s',strtotime($editeddata['editedon'])).'|'."\n";
							$idproof_file_flg =  fwrite($id_fp, $id_data);
						}
						if($idproof_file_flg)
							$success['idproof_file'] = "Edited Candidate Details Id-Proof File Generated Successfully. ";
						else
							$error['idproof_file'] = "Error While Generating Edited Candidate Details Id-Proof File.";
					}
					
					$data .= $editeddata['photo_flg'].'|'.$editeddata['signature_flg'].'|'.$editeddata['id_flg'].'|'.date('d-M-y H:i:s',strtotime($editeddata['editedon'])).'|'."\n";
					
					$edit_data_flg = fwrite($fp, $data);
					if($edit_data_flg)
						$success['edit_data'] = "Edited Candidate Details File Generated Successfully.";
					else
						$error['edit_data'] = "Error While Generating Edited Candidate Details File.";
					
					if($dir_flg)
					{
						$photo_zip_flg = 0;
						$sign_zip_flg = 0;
						$idproof_zip_flg = 0;
						if($editeddata['photo_flg'] == 'Y' && $photo != '')
						{
							copy("./uploads/photograph/".$editeddata['scannedphoto'],$directory."/".$editeddata['scannedphoto']);
							//copy($actual_photo_path,$directory."/".$actual_photo_name);
							$photo_to_add = $directory."/".$editeddata['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
						}
						if($editeddata['signature_flg'] == 'Y' && $signature != '')
						{
							copy("./uploads/scansignature/".$editeddata['scannedsignaturephoto'],$directory."/".$editeddata['scannedsignaturephoto']);
							//copy($actual_sign_path,$directory."/".$actual_sign_name);
							$sign_to_add = $directory."/".$editeddata['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
						}
						if($editeddata['id_flg'] == 'Y' && $idproofimg != '')
						{
							copy("./uploads/idproof/".$editeddata['idproofphoto'],$directory."/".$editeddata['idproofphoto']);
							//copy($actual_idproof_path,$directory."/".$actual_idproof_name);
							$proof_to_add = $directory."/".$editeddata['idproofphoto'];
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
						}
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg)
						{
							$success['zip'] = "Edited Candidate Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating Edited Candidate Images Zip";
						}
					}
					$i++;
					//break;
				}
				
				$zip->close();
			}
			else
			{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			fclose($photo_fp);
			fclose($sign_fp);
			fclose($id_fp);
			
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Edited Candidate Details Cron Execution End", $desc);
		}
	}
	
	// By VSU : Fuction to fetch duplicate i-card requests and export in TXT format
	public function dup_icard()
	{
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$dup_icard_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles/";
		
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Duplicate I-card Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "iibf_dup_icard_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
				
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$select = 'c.regnumber,c.reason_type,c.icard_cnt,a.registrationtype,b.transaction_no,b.date,b.amount,b.transaction_no';
			$this->db->join('member_registration a','a.regnumber=c.regnumber','LEFT');
			$this->db->join('payment_transaction b','b.ref_id=c.did','LEFT');
			$dup_icard_data = $this->Master_model->getRecords('duplicate_icard c',array(' DATE(added_date)'=>$yesterday,'pay_type'=>3,'isactive'=>'1','status'=>'1','isdeleted'=>0),$select);
			if(count($dup_icard_data))
			{
				$data = '';
				$i = 1;
				foreach($dup_icard_data as $icard)
				{
					$reason_type = '';
					switch($icard['reason_type'])
					{
						case "mis"	: 	$reason_type = 1;
										break;
						case "dam"	: 	$reason_type = 2;
										break;
						case "cha"	: 	$reason_type = 3;
										break;
					}
					
					//MEM_MEM_NO,MEM_MEM_TYP,REQ_REASON,BDRNO,MEM_DUP_SNO,TRN_DATE,TRN_AMT,INSTRUMENT_NO
													
					$data .= ''.$icard['regnumber'].'|'.$icard['registrationtype'].'|'.$reason_type.'|'.$icard['transaction_no'].'|'.$icard['icard_cnt'].'|'.date('d-M-Y',strtotime($icard['date'])).'|'.$icard['amount'].'|'.$icard['transaction_no']."|\n";
		
					$i++;
				}
				
				$dup_icard_flg = fwrite($fp, $data);
				if($dup_icard_flg)
						$success['dup_icard'] = "Duplicate I-card Details File Generated Successfully. ";
				else
					$error['dup_icard'] = "Error While Generating Duplicate I-card Details File.";
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
			$this->log_model->cronlog("Duplicate I-card Details Cron Execution End", $desc);
		}
	}
	
	// By VSU : Fuction to fetch candidate exam application data and export in TXT format
	public function exam()
	{
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles/";
		
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Candidate Exam Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "exam_cand_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			$select = 'a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,b.member_regnumber,b.member_exam_id,b.amount,b.date,b.transaction_no,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work';
			/*$this->db->join('member_exam a','a.regnumber=c.regnumber','LEFT'); 
			$this->db->join('payment_transaction b','b.ref_id=a.id','LEFT');
			$can_exam_data = $this->Master_model->getRecords('member_registration c',array(' DATE(a.created_on)'=>$yesterday,'pay_type'=>2),$select);
			*/
			$this->db->join('member_registration c','a.regnumber=c.regid','LEFT'); 
			$this->db->join('payment_transaction b','b.ref_id=a.id','LEFT');
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0),$select);
			
			//echo $this->db->last_query();
			if(count($can_exam_data))
			{
				/*echo "<pre>";
				print_r($can_exam_data);*/
				$data = '';
				$i = 1;
				foreach($can_exam_data as $exam)
				{
					//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO
					$exam_mode = '';
					if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
					else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
					
					$syllabus_code = '';
					$part_no = '';
					$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
					if(count($subject_data)>0)
					{
						$syllabus_code = $subject_data[0]['syllabus_code'];
						$part_no = $subject_data[0]['part_no'];
					}
					if($exam['elected_sub_code']!=0)
					{
						$data .= ''.$exam['exam_code'].'|'.$exam['exam_period'].'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$exam['elected_sub_code'].'|'.$exam['place_of_work'].'|'.$exam['pin_code_place_of_work'].'|'.$exam['state_place_of_work'].'|'.$exam['transaction_no'].'|'.date('d-M-Y',strtotime($exam['date'])).'|'.$exam['exam_fee'].'|'.$exam['transaction_no']."|\n";
					}
					else
					{
						$data .= ''.$exam['exam_code'].'|'.$exam['exam_period'].'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.date('d-M-Y',strtotime($exam['date'])).'|'.$exam['exam_fee'].'|'.$exam['transaction_no']."|\n";
					}
					
					//echo $data."<br>";
					
					
					$i++;
				}
				$exam_file_flg = fwrite($fp, $data);
				if($exam_file_flg)
						$success['cand_exam'] = "Candidate Exam Details File Generated Successfully.";
				else
					$error['cand_exam'] = "Error While Generating Candidate Exam Details File.";
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
			$this->log_model->cronlog("Candidate Exam Details Cron Execution End", $desc);
		}
	}
	
	// By VSU : Fuction to fetch new DRA member registration data and export it in TXT format
	public function dra_member()
	{
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$Zip_flg = 0;
		$file_w_flg = 0;
		$photo_zip_flg = 0;
		$sign_zip_flg = 0;
		$idproof_zip_flg = 0;
		$tc_zip_flg = 0;
		$dc_zip_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles/";
		
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("New DRA Candidate Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "dra_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$this->db->join('dra_members a','b.ref_id=a.regid AND b.member_regnumber=a.regnumber','LEFT');
			$new_dra_reg = $this->Master_model->getRecords('payment_transaction b',array(' DATE(createdon)'=>$yesterday,'status'=>1,'isactive'=>'1','isdeleted'=>0));
			if(count($new_dra_reg))
			{
				$data = '';
				$dirname = "dra_regd_image_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory))
				{
					array_map('unlink', glob($directory."/*.*"));
					rmdir($directory);
					$dir_flg = mkdir($directory, 0700);
				}
				else
				{
					$dir_flg = mkdir($directory, 0700);
				}
				
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				
				$i = 1;
				foreach($new_dra_reg as $dra)
				{
					$gender = '';
					if($dra['gender'] == 'male')	{ $gender = 'M';}
					else if($dra['gender'] == 'female')	{ $gender = 'F';}
					
					$photo 		= DRA_FILE_PATH.$dra['scannedphoto'];
					$signature 	= DRA_FILE_PATH.$dra['scannedsignaturephoto'];
					$idproofimg = DRA_FILE_PATH.$dra['idproofphoto'];
					$tcimg 		= DRA_FILE_PATH.$dra['training_certificate'];
					$dcimg 		= DRA_FILE_PATH.$dra['quali_certificate'];
					
					$qualification = '';
					switch($dra['qualification'])
					{
						case "U"	: 	$qualification = 1;
										break;
						case "G"	: 	$qualification = 3;
										break;
						case "P"	: 	$qualification = 5;
										break;
					}
					
					//801135663|NM|MR|ADITYA|NAGNATH|SHIDDHE|MR ADITYA NAGNATH SHIDDHE|862|SHUKRAWAR PETH|SOLAPUR|SOLAPUR|||413001|MAH|24-Mar-91|M|1|8||||||shreekantyadwad@yahoo.co.in|||9096439943|2||6647590215714|29-Sep-16|12075|6647590215714|Y|||/webonline/fromweb/images/dra/p_801135663.jpg|/webonline/fromweb/images/dra/s_801135663.jpg|/webonline/fromweb/images/dra/pr_801135663.jpg|/webonline/fromweb/images/dra/tc_801135663.jpg|/webonline/fromweb/images/dra/dc_801135663.jpg|||||
					
					$displayname = $dra['namesub'].' '.$dra['firstname'].' '.$dra['middlename'].' '.$dra['lastname'];
													
					$data .= ''.$dra['regnumber'].'|'.$dra['registrationtype'].'|'.$dra['namesub'].'|'.$dra['firstname'].'|'.$dra['middlename'].'|'.$dra['lastname'].'|'.$displayname.'|'.$dra['address1'].'|'.$dra['address2'].'|'.$dra['city'].'|'.$dra['district'].'|||'.$dra['pincode'].'|'.$dra['state'].'|'.date('d-M-y',strtotime($dra['dateofbirth'])).'|'.$gender.'|'.$qualification.'|'.$dra['specify_qualification'].'||||||'.$dra['email'].'|||'.$dra['mobile'].'|'.$dra['idproof'].'||'.$dra['transaction_no'].'|'.date('d-M-y',strtotime($dra['date'])).'|'.$dra['amount'].'|'.$dra['transaction_no'].'|'.$dra['optnletter'].'|||'.$photo.'|'.$signature.'|'.$idproofimg.'|'.$tcimg.'|'.$dcimg.'|||||'."\n";
					
					if($dir_flg)
					{
						copy("./uploads/iibfdra/".$dra['scannedphoto'],$directory."/".$dra['scannedphoto']);
						copy("./uploads/iibfdra/".$dra['scannedsignaturephoto'],$directory."/".$dra['scannedsignaturephoto']);
						copy("./uploads/iibfdra/".$dra['idproofphoto'],$directory."/".$dra['idproofphoto']);
						copy("./uploads/iibfdra/".$dra['training_certificate'],$directory."/".$dra['training_certificate']);
						copy("./uploads/iibfdra/".$dra['quali_certificate'],$directory."/".$dra['quali_certificate']);
						
						$photo_to_add = $directory."/".$dra['scannedphoto'];
						$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
						$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
						
						$sign_to_add = $directory."/".$dra['scannedsignaturephoto'];
						$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
						$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
						
						$proof_to_add = $directory."/".$dra['idproofphoto'];
						$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
						$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
						
						$tc_to_add = $directory."/".$dra['training_certificate'];
						$new_tc = substr($tc_to_add,strrpos($tc_to_add,'/') + 1);
						$tc_zip_flg = $zip->addFile($tc_to_add,$new_tc);
						
						$dc_to_add = $directory."/".$dra['quali_certificate'];
						$new_dc = substr($dc_to_add,strrpos($dc_to_add,'/') + 1);
						$dc_zip_flg = $zip->addFile($dc_to_add,$new_dc);
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg || $tc_zip_flg || $dc_zip_flg)
						{
							$success['zip'] = "New DRA Candidate Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating New DRA Candidate Images Zip";
						}
					}
					$i++;
				}
				$file_w_flg = fwrite($fp, $data);
				if($file_w_flg)
				{
					$success['file'] = "New DRA Candidate Details File Generated Successfully. ";
				}
				else
				{
					$error['file'] = "Error While Generating New DRA Candidate Details File.";
				}
				
				$zip->close();
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
			$this->log_model->cronlog("New DRA Candidate Details Cron Execution End", $desc);
		}
	}
	
	// By VSU : Fuction to fetch candidate exam application data and export in TXT format
	public function dra_exam()
	{
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles/";
		
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("DRA Candidate Exam Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "dra_exam_cand_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			
			/*$select = 'a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,b.member_regnumber,b.member_exam_id,b.amount,b.date,b.transaction_no,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype';
			
			$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
			$this->db->join('payment_transaction b','b.ref_id=a.id','LEFT');
			$can_exam_data = $this->Master_model->getRecords('dra_member_exam a',array(' DATE(a.created_on)'=>$yesterday,'pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0),$select);
			
			
			//echo $this->db->last_query();
			if(count($can_exam_data))
			{
				//echo "<pre>";
				//print_r($can_exam_data);
				
				$data = '';
				$file = "exam_cand_report_".$current_date.".txt";
				
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$i = 1;
				foreach($can_exam_data as $exam)
				{
					//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE ,FEE_AMT,INSTRUMENT_NO,TRNG_INS_CD,trng_from,TRNG_TO
					$exam_mode = '';
					if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
					else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
					
					$syllabus_code = '';
					$part_no = '';
					$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
					if(count($subject_data)>0)
					{
						$syllabus_code = $subject_data[0]['syllabus_code'];
						$part_no = $subject_data[0]['part_no'];
					}
					
					$data .= ''.$exam['exam_code'].'|'.$exam['exam_period'].'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.date('d-M-Y',strtotime($exam['date'])).'|'.$exam['exam_fee'].'|'.$exam['transaction_no']."|\n";
					
					$i++;
				}
				fwrite($fp, $data);
				fclose($fp);
			}*/
			
			$dra_payment = $this->Master_model->getRecords('dra_payment_transaction',array(' DATE(created_date)'=>$yesterday,'status'=>1));
			//echo $this->db->last_query();
			if(count($dra_payment))
			{
				/*echo "<pre>";
				print_r($can_exam_data);*/
				
				$data = '';
				$i = 1;
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
							
							if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
							else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
							
							//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,	TRNG_INS_CD,trng_from,TRNG_TO
							
							//print_r($exam);
							
							$transaction_no = '';
							if($payment['gateway']==1)
							{	$transaction_no = $payment['UTR_no'];	}
							else if($payment['gateway']==2)
							{	$transaction_no = $payment['transaction_no'];	}
							
							$data .= ''.$exam['exam_code'].'|'.$exam['exam_period'].'|'.$reg_num.'|'.$reg_type.'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$transaction_no.'|'.date('d-M-Y',strtotime($payment['created_date'])).'|'.$payment['amount'].'|'.$transaction_no.'|'.$payment['inst_code'].'|'.date('d-M-y',strtotime($exam['training_from'])).'|'.date('d-M-y',strtotime($exam['training_to']))."|\n";
						}
					}
					
					$i++;
				}
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
			$this->log_model->cronlog("DRA Candidate Exam Details Cron Execution End", $desc);
		}
	}
	
	public function get_calendar_input()
	{
		$calendar = array();
		$from_year = date('Y', strtotime("- 60 year"));
		$to_year = date('Y');
		
		for($y=$from_year;$y<=$to_year;$y++)
		{
			$calendar['year'][] = $y;
		}
		
		for($i=1;$i<13;$i++)
		{
			$calendar['month'][] = date('F',strtotime('01-'.$i.'-'.$to_year)); 
		}
		
		for($j=1;$j<32;$j++)
		{
			$calendar['date'][] = $j; 
		}
	}
}