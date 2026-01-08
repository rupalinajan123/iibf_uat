<?php
/*
 	* Controller Name	:	Cron_data_gen File Generation
 	* Created By		:	Bhushan
 	* Created Date		:	15-06-2020
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Cron_data_gen_declaration extends CI_Controller {
	public function __construct(){
		parent::__construct();
		//echo md5('Iibf@123');
		$this->load->model('UserModel');
		$this->load->model('Master_model');
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->model('log_model');
		/* File Path */
		define('MEM_FILE_PATH','/fromweb/testscript/images/newmem/');
		define('CSC_MEM_FILE_PATH','/fromweb/testscript/images/newmem/');
		define('DIGITAL_EL_MEM_FILE_PATH','/fromweb/testscript/images/newmem/');
		define('DRA_FILE_PATH','/fromweb/testscript/images/dra/');
		define('MEM_FILE_EDIT_PATH','/fromweb/testscript/images/edit/');
		define('MEM_FILE_RENEWAL_PATH','/fromweb/testscript/images/renewal/');
		//define('EXAM_ELEARNING_SPM_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/'); //E-learning Separate Module 
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}
	/*public function ci_sessoin_delete()
	{
		$yesterday = date('Y-m-d', strtotime("- 1 day"));
		//$yesterday = '2019-10-01';
		//$this->db->where('FROM_UNIXTIME(timestamp, "%Y-%m-%d")');
		$this->db->where('FROM_UNIXTIME(timestamp, '.$yesterday.')');
		$this->db->delete('ci_sessions');
		//echo ">>".$this->db->last_query();
		$query = $this->db->query('OPTIMIZE TABLE ci_sessions');
	}*/
	/* Membership benchmark Edit data Cron */
	public function benchmark_edit_data()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$Zip_flg = 0;
		$benchmark_file_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");
		$cron_file_dir = "./uploads/cronFilesCustom/";
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
			$cron_file_path = "./uploads/cronFilesCustom/20210820";	// Path with CURRENT DATE DIRECTORY
			$benchmark_file = "edited_cand_details_benchmark_".$current_date.".txt";
			$benchmark_fp = fopen($cron_file_path.'/'.$benchmark_file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Benchmark Edited Candidate Details Cron Start - ".$start_time."**********\n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday = '2021-03-20';
			$today = date('Y-m-d');
			$member = array(500151655);
			$this->db->where_in('regnumber', $member);
			$edited_benchmark_data = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','isdeleted'=>0,'benchmark_edit_flg' => 'Y'));
			//'DATE(benchmark_edit_date)'=>$yesterday,
			if(count($edited_benchmark_data))
			{
				$dirname = "edited_benchmark_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory)){
					// Delete all the files in directory
					array_map('unlink', glob($directory."/*.*"));
					// Remove directory
					rmdir($directory);
					$dir_flg = mkdir($directory, 0700);
				}
				else{
					$dir_flg = mkdir($directory, 0700);
				}
				// Create a zip of images folder
				$zip = new ZipArchive;
				$Zip_flg = $zip->open($directory.'.zip', ZipArchive::CREATE);
				$vis_imp_cert_img_cnt = 0;
				$orth_han_cert_img_cnt = 0;
				$cer_palsy_cert_img_cnt = 0;
				$vis_imp_cert_img_flg_cnt = 0;
				$orth_han_cert_img_flg_cnt = 0;
				$cer_palsy_cert_img_flg_cnt = 0;
				foreach($edited_benchmark_data as $imgdata)
				{	
					$img_edited_by = '';
					$data = '';
					$gender = '';
					if($imgdata['gender'] == 'male')	{ $gender = 'M';}
					else if($imgdata['gender'] == 'female')	{ $gender = 'F';}
					$qualification = '';
					switch($imgdata['qualification'])
					{
						// Values changes as per client req. : 22 July 2019
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;		
					}
					//MEM_MEM_NO	,MEM_MEM_TYP	,MEM_TLE,	MEM_NAM_1,	MEM_NAM_2	,MEM_NAM_3,	ID_CARD_NAME,	MEM_ADR_1,	MEM_ADR_2,	MEM_ADR_3,	MEM_ADR_4,	MEM_ADR_5,	MEM_ADR_6,	MEM_PIN_CD,	MEM_STE_CD,	MEM_DOB,	MEM_SEX_CD,	MEM_QLF_GRD,	MEM_QLF_CD,	MEM_INS_CD,	BRANCH,	MEM_DSG_CD,	MEM_BNK_JON_DT ,EMAIL,	STD_R,	PHONE_R,	MOBILE,	ID_TYPE,	ID_NO,	BDRNO,	TRN_DATE,	TRN_AMT,	USR_ID,	AR_FLG,	filename_1 filler char(400),PHOTO LOBFILE (filename_1) TERMINATED BY EOF,	PHOTO_FLG,	SIGNATURE_FLG,	ID_FLG,	UPDATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'
					$mem_dob = '';
					if($imgdata['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($imgdata['dateofbirth']));
					}
					$mem_doj = '';
					if($imgdata['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($imgdata['dateofjoin']));
					}
					if(strlen($imgdata['stdcode']) > 10)
					{	$std_code = substr($imgdata['stdcode'],0,9);		}
					else
					{	$std_code = $imgdata['stdcode'];	}
					if(strlen($imgdata['address1']) > 30)
					{	$address1 = substr($imgdata['address1'],0,29);		}
					else
					{	$address1 = $imgdata['address1'];	}
					if(strlen($imgdata['address2']) > 30)
					{	$address2 = substr($imgdata['address2'],0,29);		}
					else
					{	$address2 = $imgdata['address2'];	}
					if(strlen($imgdata['address3']) > 30)
					{	$address3 = substr($imgdata['address3'],0,29);		}
					else
					{	$address3 = $imgdata['address3'];	}
					if(strlen($imgdata['address4']) > 30)
					{	$address4 = substr($imgdata['address4'],0,29);		}
					else
					{	$address4 = $imgdata['address4'];	}
					if(strlen($imgdata['district']) > 30)
					{	$district = substr($imgdata['district'],0,29);		}
					else
					{	$district = $imgdata['district'];	}
					if(strlen($imgdata['city']) > 30)
					{	$city = substr($imgdata['city'],0,29);		}
					else
					{	$city = $imgdata['city'];	}
					if($imgdata['editedby'] == '')
					{
						$edited_by = "Candidate";
					}
					else
					{
						$edited_by = $imgdata['editedby'];	
					}
					$branch = '';
					$branch_name = '';
					if($imgdata['editedon'] < "2016-12-29 00:00:00")
					{
						$branch = $imgdata['branch'];
					}
					else if($imgdata['editedon'] >= "2016-12-29")
					{
						if(is_numeric($imgdata['office']))
						{
							if($imgdata['branch']!='')
								$branch = $imgdata['branch'];
							else
								$branch = $imgdata['office'];
						}
						else
						{
							if($imgdata['branch']!='')
								$branch = $imgdata['branch'];
							else
								$branch = $imgdata['office'];
						}
					}
					if($branch == '')
					{
						$branch = $city;
					}
					if(strlen($branch) > 20)
					{	$branch_name = substr($branch,0,19);	}
					else
					{	$branch_name = $branch;	}
					$optnletter = "Y";
					if($imgdata['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else if($imgdata['optnletter'] != "")
					{	$optnletter = $imgdata['optnletter'];	}
					$data = ''.$imgdata['regnumber'].'|'.$imgdata['registrationtype'].'|'.$imgdata['namesub'].'|'.$imgdata['firstname'].'|'.$imgdata['middlename'].'|'.$imgdata['lastname'].'|'.$imgdata['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$imgdata['pincode'].'|'.$imgdata['state'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$imgdata['specify_qualification'].'|'.$imgdata['associatedinstitute'].'|'.$branch_name.'|'.$imgdata['designation'].'|'.$mem_doj.'|'.$imgdata['email'].'|'.$std_code.'|'.$imgdata['office_phone'].'|'.$imgdata['mobile'].'|'.$imgdata['idproof'].'|'.$imgdata['idNo'].'||||';
					if($imgdata['images_editedby'] == ''){
						$img_edited_by = "Candidate";
					}
					else{
						$img_edited_by = $imgdata['images_editedby'];	
					}
					/* Benchmark Code Start */
					$benchmark_disability = $imgdata['benchmark_disability'];
					$visually_impaired = $imgdata['visually_impaired'];
					$vis_imp_cert_img = '';
					$orthopedically_handicapped = $imgdata['orthopedically_handicapped'];
					$orth_han_cert_img = '';
					$cerebral_palsy = $imgdata['cerebral_palsy'];
					$cer_palsy_cert_img = '';
					if($benchmark_disability == 'Y')
					{
						$vis_imp_cert_img = '';
						if($visually_impaired == 'Y')
						{
							if($imgdata['vis_imp_cert_img'] != "" && is_file("./uploads/disability/".$imgdata['vis_imp_cert_img']))
							{
								$vis_imp_cert_img 	= MEM_FILE_EDIT_PATH.$imgdata['vis_imp_cert_img'];
							}
						}
						$orth_han_cert_img = '';
						if($orthopedically_handicapped == 'Y')
						{
							if($imgdata['orth_han_cert_img'] != "" && is_file("./uploads/disability/".$imgdata['orth_han_cert_img']))
							{
								$orth_han_cert_img 	= MEM_FILE_EDIT_PATH.$imgdata['orth_han_cert_img'];
							}
						}
						$cer_palsy_cert_img = '';
						if($cerebral_palsy == 'Y')
						{
							if($imgdata['cer_palsy_cert_img'] != "" && is_file("./uploads/disability/".$imgdata['cer_palsy_cert_img']))
							{
								$cer_palsy_cert_img 	= MEM_FILE_EDIT_PATH.$imgdata['cer_palsy_cert_img'];
							}
						}
					}
					/* Benchmark Code End */
					$benchmark_data = $data.$img_edited_by.'|'.$optnletter.'|N|N|N|'.date('d-M-y H:i:s',strtotime($imgdata['benchmark_edit_date'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id'].'|'.$benchmark_disability.'|'.$visually_impaired.'|'.$vis_imp_cert_img.'|'.$orthopedically_handicapped.'|'.$orth_han_cert_img.'|'.$cerebral_palsy.'|'.$cer_palsy_cert_img."\n";
					$benchmark_file_flg = fwrite($benchmark_fp, $benchmark_data);
					if($benchmark_file_flg)
						$success['benchmark_file'] = "Edited Candidate Details Photo File Generated Successfully. ";
					else
						$error['benchmark_file'] = "Error While Generating Edited Candidate Details Photo File.";
					// Zip Image Folder
					if($dir_flg)
					{
						$benchmark_zip_flg = 0;
						/* Benchmark Code Start */
						// For Visually impaired certificate images
						if($vis_imp_cert_img)
						{
							$vis_imp_cert_img_flg_cnt++;
							if(is_file("./uploads/disability/".$imgdata['vis_imp_cert_img']))
							{
								$image = "./uploads/disability/".$imgdata['vis_imp_cert_img'];
								$max_width = "200";
								$max_height = "200";
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['vis_imp_cert_img']);
								$vis_imp_cert_img_to_add = $directory."/".$imgdata['vis_imp_cert_img'];
								$new_vis_imp_cert_img = substr($vis_imp_cert_img_to_add,strrpos($vis_imp_cert_img_to_add,'/') + 1);
								$vis_imp_cert_img_zip_flg = $zip->addFile($vis_imp_cert_img_to_add,$new_vis_imp_cert_img);
								if(!$vis_imp_cert_img_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Visually impaired certificate not added to zip  - ".$imgdata['vis_imp_cert_img']." (".$imgdata['vis_imp_cert_img'].")\n");	
								}
								else
									$vis_imp_cert_img_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Visually impaired certificate does not exist  - ".$imgdata['vis_imp_cert_img']." (".$imgdata['vis_imp_cert_img'].")\n");	
							}
						}
						// For Orthopedically handicapped certificate images
						if($orth_han_cert_img)
						{
							$orth_han_cert_img_flg_cnt++;
							if(is_file("./uploads/disability/".$imgdata['orth_han_cert_img']))
							{
								$image = "./uploads/disability/".$imgdata['orth_han_cert_img'];
								$max_width = "200";
								$max_height = "200";
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['orth_han_cert_img']);
								$orth_han_cert_img_to_add = $directory."/".$imgdata['orth_han_cert_img'];
								$new_orth_han_cert_img = substr($orth_han_cert_img_to_add,strrpos($orth_han_cert_img_to_add,'/') + 1);
								$orth_han_cert_img_zip_flg = $zip->addFile($orth_han_cert_img_to_add,$new_orth_han_cert_img);
								if(!$orth_han_cert_img_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Orthopedically handicapped certificate not added to zip  - ".$imgdata['orth_han_cert_img']." (".$imgdata['orth_han_cert_img'].")\n");	
								}
								else
									$orth_han_cert_img_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Orthopedically handicapped certificate does not exist  - ".$imgdata['orth_han_cert_img']." (".$imgdata['orth_han_cert_img'].")\n");	
							}
						}
						// For Cerebral palsy certificate images
						if($cer_palsy_cert_img)
						{
							$cer_palsy_cert_img_flg_cnt++;
							if(is_file("./uploads/disability/".$imgdata['cer_palsy_cert_img']))
							{
								$image = "./uploads/disability/".$imgdata['cer_palsy_cert_img'];
								$max_width = "200";
								$max_height = "200";
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['cer_palsy_cert_img']);
								$cer_palsy_cert_img_to_add = $directory."/".$imgdata['cer_palsy_cert_img'];
								$new_cer_palsy_cert_img = substr($cer_palsy_cert_img_to_add,strrpos($cer_palsy_cert_img_to_add,'/') + 1);
								$cer_palsy_cert_img_zip_flg = $zip->addFile($cer_palsy_cert_img_to_add,$new_cer_palsy_cert_img);
								if(!$cer_palsy_cert_img_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Cerebral palsy certificate not added to zip  - ".$imgdata['cer_palsy_cert_img']." (".$imgdata['cer_palsy_cert_img'].")\n");	
								}
								else
									$cer_palsy_cert_img_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Cerebral palsy certificate does not exist  - ".$imgdata['cer_palsy_cert_img']." (".$imgdata['cer_palsy_cert_img'].")\n");	
							}
						}
						if($benchmark_zip_flg)
						{
							$success['zip'] = "Edited Candidate Benchmark Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating Edited Candidate Benchmark Zip";
						}
					}
				}
				fwrite($fp1, "\n"."Total Visually impaired cert Added = ".$vis_imp_cert_img_cnt."/".$vis_imp_cert_img_flg_cnt." \n");
				fwrite($fp1, "\n"."Total Orthopedically handicapped cert Added = ".$orth_han_cert_img_cnt."/".$orth_han_cert_img_flg_cnt." \n");
				fwrite($fp1, "\n"."Total Cerebral palsy cert Added = ".$cer_palsy_cert_img_cnt."/".$cer_palsy_cert_img_flg_cnt." \n");
				$zip->close();
			}
			else
			{
				$success['img'] = "No Benchmark Edit data found for the date";
			}
			// Close Benchmark
			fclose($benchmark_fp);
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Benchmark Edited Candidate Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Benchmark Edited Candidate Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/* Membership benchmark Edit data Cron */
	public function new_edit_data()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$Zip_flg = 0;
		$benchmark_file_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");
		$cron_file_dir = "./uploads/cronFilesCustom/";
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
			$file = "edited_cand_details_".$current_date.".txt";	// Path with CURRENT DATE DIRECTORY
			$benchmark_file = "edited_cand_details_".$current_date.".txt";
			$benchmark_fp = fopen($cron_file_path.'/'.$benchmark_file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n**********  Edited Candidate Details Cron Start - ".$start_time."**********\n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2021-12-29';
			$today = date('Y-m-d');
			$member = array(801598730,801709276,801709976,801715203,801788864,801789617,801791905,801794530,801798646,801799031,801799050,801800056,801800385,801800907,801813266,801835716,801838608,801839508,801856016,801859118,801860462,801861727,801865803,801867137,801870129,801870641,801871452,801871934,801894834,510532281,801926102,801933278,801939822,510477786,510478665,510480226,510483131,510487211,510488980,510496895,510499659,510500838,801758596,801758602,510515699,801834254,801834381,801834373,801834452,801834465,801834657,801835198,801837316,801837497,801837678,801837710,801837755,801837760,801837762,801837963,801838042,801838241,801838252,801838676,801839302,801839549,801839656,801842908,801843105,801850294,801858546,801861526,801869259,801870273,801870310,801870372,801870532,801870689,801870772,801871000,801871724,801871939,801872387,801872906,801894636,801896140,801896171,801897496,801905300,510532227,801928071,801929177,801929884,510533181,510533185,801936630,801936713,801937334,801937335,510533733,510533968,510534465,510535054,510535085,510535241,510535319,510535402,510535421);
			
			$this->db->where_in('regnumber',$member);
			//$this->db->where_in('DATE(benchmark_edit_date)',$yesterday);
			$edited_mem_data = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','isdeleted'=>0));
			//' DATE(editedon)'=>$yesterday,
			//echo $this->db->last_query(); die;
			if(count($edited_mem_data))
			{
				$dirname = "edited_image_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory)){
					// Delete all the files in directory
					array_map('unlink', glob($directory."/*.*"));
					// Remove directory
					rmdir($directory);
					$dir_flg = mkdir($directory, 0700);
				}
				else{
					$dir_flg = mkdir($directory, 0700);
				}
				// Create a zip of images folder
				$zip = new ZipArchive;
				$Zip_flg = $zip->open($directory.'.zip', ZipArchive::CREATE);
				//$imgdata = $edited_img_data[0];
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				$photo_flg_cnt = 0;
				$sign_flg_cnt = 0;
				$idproof_flg_cnt = 0;
				$vis_imp_cert_img_cnt = 0;
				$orth_han_cert_img_cnt = 0;
				$cer_palsy_cert_img_cnt = 0;
				$vis_imp_cert_img_flg_cnt = 0;
				$orth_han_cert_img_flg_cnt = 0;
				$cer_palsy_cert_img_flg_cnt = 0;
				foreach($edited_mem_data as $imgdata)
				{	
					$img_edited_by = '';
					$data = '';
					$gender = '';
					if($imgdata['gender'] == 'male')	{ $gender = 'M';}
					else if($imgdata['gender'] == 'female')	{ $gender = 'F';}
					$qualification = '';
					switch($imgdata['qualification'])
					{
						// Values changes as per client req. : 22 July 2019
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;		
					}
					//MEM_MEM_NO	,MEM_MEM_TYP	,MEM_TLE,	MEM_NAM_1,	MEM_NAM_2	,MEM_NAM_3,	ID_CARD_NAME,	MEM_ADR_1,	MEM_ADR_2,	MEM_ADR_3,	MEM_ADR_4,	MEM_ADR_5,	MEM_ADR_6,	MEM_PIN_CD,	MEM_STE_CD,	MEM_DOB,	MEM_SEX_CD,	MEM_QLF_GRD,	MEM_QLF_CD,	MEM_INS_CD,	BRANCH,	MEM_DSG_CD,	MEM_BNK_JON_DT ,EMAIL,	STD_R,	PHONE_R,	MOBILE,	ID_TYPE,	ID_NO,	BDRNO,	TRN_DATE,	TRN_AMT,	USR_ID,	AR_FLG,	filename_1 filler char(400),PHOTO LOBFILE (filename_1) TERMINATED BY EOF,	PHOTO_FLG,	SIGNATURE_FLG,	ID_FLG,	UPDATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'
					$mem_dob = '';
					if($imgdata['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($imgdata['dateofbirth']));
					}
					$mem_doj = '';
					if($imgdata['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($imgdata['dateofjoin']));
					}
					if(strlen($imgdata['stdcode']) > 10)
					{	$std_code = substr($imgdata['stdcode'],0,9);		}
					else
					{	$std_code = $imgdata['stdcode'];	}
					if(strlen($imgdata['address1']) > 30)
					{	$address1 = substr($imgdata['address1'],0,29);		}
					else
					{	$address1 = $imgdata['address1'];	}
					if(strlen($imgdata['address2']) > 30)
					{	$address2 = substr($imgdata['address2'],0,29);		}
					else
					{	$address2 = $imgdata['address2'];	}
					if(strlen($imgdata['address3']) > 30)
					{	$address3 = substr($imgdata['address3'],0,29);		}
					else
					{	$address3 = $imgdata['address3'];	}
					if(strlen($imgdata['address4']) > 30)
					{	$address4 = substr($imgdata['address4'],0,29);		}
					else
					{	$address4 = $imgdata['address4'];	}
					if(strlen($imgdata['district']) > 30)
					{	$district = substr($imgdata['district'],0,29);		}
					else
					{	$district = $imgdata['district'];	}
					if(strlen($imgdata['city']) > 30)
					{	$city = substr($imgdata['city'],0,29);		}
					else
					{	$city = $imgdata['city'];	}
					if($imgdata['editedby'] == '')
					{
						$edited_by = "Candidate";
					}
					else
					{
						$edited_by = $imgdata['editedby'];	
					}
					$branch = '';
					$branch_name = '';
					if($imgdata['editedon'] < "2016-12-29 00:00:00")
					{
						$branch = $imgdata['branch'];
					}
					else if($imgdata['editedon'] >= "2016-12-29")
					{
						if(is_numeric($imgdata['office']))
						{
							if($imgdata['branch']!='')
								$branch = $imgdata['branch'];
							else
								$branch = $imgdata['office'];
						}
						else
						{
							if($imgdata['branch']!='')
								$branch = $imgdata['branch'];
							else
								$branch = $imgdata['office'];
						}
					}
					if($branch == '')
					{
						$branch = $city;
					}
					if(strlen($branch) > 20)
					{	$branch_name = substr($branch,0,19);	}
					else
					{	$branch_name = $branch;	}
					$optnletter = "Y";
					if($imgdata['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else if($imgdata['optnletter'] != "")
					{	$optnletter = $imgdata['optnletter'];	}
					$data = ''.$imgdata['regnumber'].'|'.$imgdata['registrationtype'].'|'.$imgdata['namesub'].'|'.$imgdata['firstname'].'|'.$imgdata['middlename'].'|'.$imgdata['lastname'].'|'.$imgdata['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$imgdata['pincode'].'|'.$imgdata['state'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$imgdata['specify_qualification'].'|'.$imgdata['associatedinstitute'].'|'.$branch_name.'|'.$imgdata['designation'].'|'.$mem_doj.'|'.$imgdata['email'].'|'.$std_code.'|'.$imgdata['office_phone'].'|'.$imgdata['mobile'].'|'.$imgdata['idproof'].'|'.$imgdata['idNo'].'||||';
					if($imgdata['images_editedby'] == ''){
						$img_edited_by = "Candidate";
					}
					else{
						$img_edited_by = $imgdata['images_editedby'];	
					}
					$this->load->model('Image_search_model');
					
					$photo = $signature = $idproofimg = '';
					$photo_send = $signature_send = $idproofimg_send = '';
					$photoflag = $imgdata['photo_flg'];
					$signflag = $imgdata['signature_flg'];
					$idprofflag = $imgdata['id_flg'];
					$member_img_response = $this->Image_search_model->get_member_data($imgdata['regnumber']);
				    $photo = $member_img_response['scannedphoto'];
				    $idproofimg = $member_img_response['idproofphoto'];
				    $signature = $member_img_response['scannedsignaturephoto'];
					if($imgdata['scannedphoto'] != "" && is_file("./uploads/photograph/".$imgdata['scannedphoto']))
					{
						$photo 	= MEM_FILE_EDIT_PATH.$imgdata['scannedphoto'];
					}
					if($imgdata['scannedsignaturephoto'] != "" && is_file("./uploads/scansignature/".$imgdata['scannedsignaturephoto']))
					{
						$signature 	= MEM_FILE_EDIT_PATH.$imgdata['scannedsignaturephoto'];
					}
					if($imgdata['idproofphoto'] != "" && is_file("./uploads/idproof/".$imgdata['idproofphoto']))
					{
						$idproofimg = MEM_FILE_EDIT_PATH.$imgdata['idproofphoto'];
					}
					
					/* Benchmark Code Start */
					$benchmark_disability = $imgdata['benchmark_disability'];
					$visually_impaired = $imgdata['visually_impaired'];
					$vis_imp_cert_img = '';
					$orthopedically_handicapped = $imgdata['orthopedically_handicapped'];
					$orth_han_cert_img = '';
					$cerebral_palsy = $imgdata['cerebral_palsy'];
					$cer_palsy_cert_img = '';
					if($benchmark_disability == 'Y')
					{
						$vis_imp_cert_img = '';
						if($visually_impaired == 'Y')
						{
							if($imgdata['vis_imp_cert_img'] != "" && is_file("./uploads/disability/".$imgdata['vis_imp_cert_img']))
							{
								$vis_imp_cert_img 	= MEM_FILE_EDIT_PATH.$imgdata['vis_imp_cert_img'];
							}
						}
						$orth_han_cert_img = '';
						if($orthopedically_handicapped == 'Y')
						{
							if($imgdata['orth_han_cert_img'] != "" && is_file("./uploads/disability/".$imgdata['orth_han_cert_img']))
							{
								$orth_han_cert_img 	= MEM_FILE_EDIT_PATH.$imgdata['orth_han_cert_img'];
							}
						}
						$cer_palsy_cert_img = '';
						if($cerebral_palsy == 'Y')
						{
							if($imgdata['cer_palsy_cert_img'] != "" && is_file("./uploads/disability/".$imgdata['cer_palsy_cert_img']))
							{
								$cer_palsy_cert_img 	= MEM_FILE_EDIT_PATH.$imgdata['cer_palsy_cert_img'];
							}
						}
					}
					/* Benchmark Code End */
					/* if($imgdata['images_editedon'] == '0000-00-00 00:00:00')
					{
						 $date = date('d-M-y H:i:s',strtotime($imgdata['editedon']));
					}
					else
					{
						$date = date('d-M-y H:i:s',strtotime($imgdata['images_editedon']));
					} */
					 $date = date('d-M-y H:i:s',strtotime($imgdata['images_editedon']));
					//die;
					$benchmark_data = $data.$img_edited_by.'|'.$optnletter.'|'.$photoflag.'|'.$signflag.'|'.$idprofflag.'|'.$date.'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id'].'|'.$photo.'|'.$signature.'|'.$idproofimg.'|'.$benchmark_disability.'|'.$visually_impaired.'|'.$vis_imp_cert_img.'|'.$orthopedically_handicapped.'|'.$orth_han_cert_img.'|'.$cerebral_palsy.'|'.$cer_palsy_cert_img."\n";
					$benchmark_file_flg = fwrite($benchmark_fp, $benchmark_data);
					if($benchmark_file_flg)
						$success['benchmark_file'] = "Edited Candidate Details Photo File Generated Successfully. ";
					else
						$error['benchmark_file'] = "Error While Generating Edited Candidate Details Photo File.";
					
					// Zip Image Folder
					if($dir_flg)
					{
						$photo_zip_flg = 0;
						$sign_zip_flg = 0;
						$idproof_zip_flg = 0;
						if($imgdata['photo_flg'] == 'Y' && $photo != '')
						{
							$photo_flg_cnt++;
							// For photo images
							if(is_file("./uploads/photograph/".$imgdata['scannedphoto']))
							{
								$image = "./uploads/photograph/".$imgdata['scannedphoto'];
								$max_width = "200";
								$max_height = "200";
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['scannedphoto']);
								//copy($actual_photo_path,$directory."/".$actual_photo_name);
								$photo_to_add = $directory."/".$imgdata['scannedphoto'];
								$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
								$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
								if(!$photo_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$imgdata['scannedphoto']." (".$imgdata['regnumber'].")\n");	
								}
								else
									$photo_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$imgdata['scannedphoto']." (".$imgdata['regnumber'].")\n");	
							}
						}
						if($imgdata['signature_flg'] == 'Y' && $signature != '')
						{
							$sign_flg_cnt++;
							//copy("./uploads/scansignature/".$imgdata['scannedsignaturephoto'],$directory."/".$imgdata['scannedsignaturephoto']);
							// For signature images
							if(is_file("./uploads/scansignature/".$imgdata['scannedsignaturephoto']))
							{
								$image = "./uploads/scansignature/".$imgdata['scannedsignaturephoto'];
								$max_width = "140";
								$max_height = "100";
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['scannedsignaturephoto']);
								//copy($actual_sign_path,$directory."/".$actual_sign_name);
								$sign_to_add = $directory."/".$imgdata['scannedsignaturephoto'];
								$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
								$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
								if(!$sign_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$imgdata['scannedsignaturephoto']." (".$imgdata['regnumber'].")\n");	
								}
								else
									$sign_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Signature does not exist  - ".$imgdata['scannedsignaturephoto']." (".$imgdata['regnumber'].")\n");	
							}
						}
						if($imgdata['id_flg'] == 'Y' && $idproofimg != '')
						{
							$idproof_flg_cnt++;
							// For ID proof images
							if(is_file("./uploads/idproof/".$imgdata['idproofphoto']))
							{
								$image = "./uploads/idproof/".$imgdata['idproofphoto'];
								$max_width = "800";
								$max_height = "500";
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['idproofphoto']);
								//copy($actual_idproof_path,$directory."/".$actual_idproof_name);
								$proof_to_add = $directory."/".$imgdata['idproofphoto'];
								$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
								$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
								if(!$idproof_zip_flg)
								{
									fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$imgdata['idproofphoto']." (".$imgdata['regnumber'].")\n");	
								}
								else 
									$idproof_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$imgdata['idproofphoto']." (".$imgdata['regnumber'].")\n");	
							}
						}
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg)
						{
							$success['zip'] = "Edited Candidate Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating Edited Candidate Images Zip";
						}
						$benchmark_zip_flg = 0;
						/* Benchmark Code Start */
						// For Visually impaired certificate images
						if($vis_imp_cert_img)
						{
							$vis_imp_cert_img_flg_cnt++;
							if(is_file("./uploads/disability/".$imgdata['vis_imp_cert_img']))
							{
								$image = "./uploads/disability/".$imgdata['vis_imp_cert_img'];
								$max_width = "200";
								$max_height = "200";
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['vis_imp_cert_img']);
								$vis_imp_cert_img_to_add = $directory."/".$imgdata['vis_imp_cert_img'];
								$new_vis_imp_cert_img = substr($vis_imp_cert_img_to_add,strrpos($vis_imp_cert_img_to_add,'/') + 1);
								$vis_imp_cert_img_zip_flg = $zip->addFile($vis_imp_cert_img_to_add,$new_vis_imp_cert_img);
								if(!$vis_imp_cert_img_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Visually impaired certificate not added to zip  - ".$imgdata['vis_imp_cert_img']." (".$imgdata['vis_imp_cert_img'].")\n");	
								}
								else
									$vis_imp_cert_img_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Visually impaired certificate does not exist  - ".$imgdata['vis_imp_cert_img']." (".$imgdata['vis_imp_cert_img'].")\n");	
							}
						}
						// For Orthopedically handicapped certificate images
						if($orth_han_cert_img)
						{
							$orth_han_cert_img_flg_cnt++;
							if(is_file("./uploads/disability/".$imgdata['orth_han_cert_img']))
							{
								$image = "./uploads/disability/".$imgdata['orth_han_cert_img'];
								$max_width = "200";
								$max_height = "200";
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['orth_han_cert_img']);
								$orth_han_cert_img_to_add = $directory."/".$imgdata['orth_han_cert_img'];
								$new_orth_han_cert_img = substr($orth_han_cert_img_to_add,strrpos($orth_han_cert_img_to_add,'/') + 1);
								$orth_han_cert_img_zip_flg = $zip->addFile($orth_han_cert_img_to_add,$new_orth_han_cert_img);
								if(!$orth_han_cert_img_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Orthopedically handicapped certificate not added to zip  - ".$imgdata['orth_han_cert_img']." (".$imgdata['orth_han_cert_img'].")\n");	
								}
								else
									$orth_han_cert_img_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Orthopedically handicapped certificate does not exist  - ".$imgdata['orth_han_cert_img']." (".$imgdata['orth_han_cert_img'].")\n");	
							}
						}
						// For Cerebral palsy certificate images
						if($cer_palsy_cert_img)
						{
							$cer_palsy_cert_img_flg_cnt++;
							if(is_file("./uploads/disability/".$imgdata['cer_palsy_cert_img']))
							{
								$image = "./uploads/disability/".$imgdata['cer_palsy_cert_img'];
								$max_width = "200";
								$max_height = "200";
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['cer_palsy_cert_img']);
								$cer_palsy_cert_img_to_add = $directory."/".$imgdata['cer_palsy_cert_img'];
								$new_cer_palsy_cert_img = substr($cer_palsy_cert_img_to_add,strrpos($cer_palsy_cert_img_to_add,'/') + 1);
								$cer_palsy_cert_img_zip_flg = $zip->addFile($cer_palsy_cert_img_to_add,$new_cer_palsy_cert_img);
								if(!$cer_palsy_cert_img_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Cerebral palsy certificate not added to zip  - ".$imgdata['cer_palsy_cert_img']." (".$imgdata['cer_palsy_cert_img'].")\n");	
								}
								else
									$cer_palsy_cert_img_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Cerebral palsy certificate does not exist  - ".$imgdata['cer_palsy_cert_img']." (".$imgdata['cer_palsy_cert_img'].")\n");	
							}
						}
						if($benchmark_zip_flg)
						{
							$success['zip'] = "Edited Candidate Benchmark Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating Edited Candidate Benchmark Zip";
						}
					}
					
				
				}
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."/".$photo_flg_cnt." \n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."/".$sign_flg_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."/".$idproof_flg_cnt."\n");
				fwrite($fp1, "\n"."Total Visually impaired cert Added = ".$vis_imp_cert_img_cnt."/".$vis_imp_cert_img_flg_cnt." \n");
				fwrite($fp1, "\n"."Total Orthopedically handicapped cert Added = ".$orth_han_cert_img_cnt."/".$orth_han_cert_img_flg_cnt." \n");
				fwrite($fp1, "\n"."Total Cerebral palsy cert Added = ".$cer_palsy_cert_img_cnt."/".$cer_palsy_cert_img_flg_cnt." \n");
				$zip->close();
			}
			else
			{
				$success['img'] = "No Image data found for the date";
			}
		
			fclose($benchmark_fp);
			/* fclose($photo_fp);
			fclose($sign_fp);
			fclose($id_fp); */
			// Cron End Logs 
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Edited Candidate Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Edited Candidate Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	/* Membership benchmark Edit data Cron */public function new_edit_data(){	ini_set("memory_limit", "-1");	$dir_flg = 0;	$parent_dir_flg = 0;	$Zip_flg = 0;	$benchmark_file_flg = 0;	$success = array();	$error = array();	$start_time = date("Y-m-d H:i:s");	$current_date = date("Ymd");	$cron_file_dir = "./uploads/cronFilesCustom/";	$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");	$desc = json_encode($result);	$this->log_model->cronlog("Edited Candidate Details Cron Execution Start", $desc);	if(!file_exists($cron_file_dir.$current_date))	{		$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 	}	if(file_exists($cron_file_dir.$current_date))	{		$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY		$file = "edited_cand_details_".$current_date.".txt";	// Path with CURRENT DATE DIRECTORY		$benchmark_file = "edited_cand_details_".$current_date.".txt";		$benchmark_fp = fopen($cron_file_path.'/'.$benchmark_file, 'w');		$file1 = "logs_".$current_date.".txt";		$fp1 = fopen($cron_file_path.'/'.$file1, 'a');		fwrite($fp1, "\n**********  Edited Candidate Details Cron Start - ".$start_time."**********\n");		$yesterday = date('Y-m-d', strtotime("- 1 day"));		//$yesterday = '2021-12-29';		$today = date('Y-m-d');		$member = array(500049965,500095668,845010995,845059790,845087783,510141891,510209937,801073170,510255199,510269241,510276478,510362050,510397564,801469257,510484520,801793620,801801279,801802584,801834084,801867137,801897838,510023225,500016124,500097066,500144349,500153721,500185972,500169486,500037957,500160415,510038950,510039446,500003316,500046329,500094707,500097029,500135191,500156055,500156367,500158836,500183131,500185774,500194811,500196518,500197107,500215694,5118139,510050198,510071163,510120194,510133120,510150620,510173403,510209333,510239504,510245499,510270363,510281647,510290052,510317683,510350049,510351192,510355344,510359921,510361147,510366626,510367389,510368285,510368302,510371746,510372184,510374635,510378636,510379134,510391019,510391115,510400295,510404768,510414298,510414767,510443113,510444426,510446313,801452736,510457891,510465690,510487201,510491010,510492174,510495281,510497241,510499098,510500327,510505624,510527003,801835350,801863079,801869683,510531493,801870927,801871158,801871489,801871759,801871904,801872828,801878649,801897071,510531936,801907452,510532694,510532710,510532745,510532770,510532891,801920098,510532909,801928033,801928113,801928184,801929616,801931937,801933237,801938752,510533326,510533358,510533529,510533733,801938332,510533871,510533964,510533978,510534026,510534102,510534293);				$this->db->where_in('regnumber',$member);		//$this->db->where_in('DATE(benchmark_edit_date)',$yesterday);		$edited_mem_data = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','isdeleted'=>0));		//' DATE(editedon)'=>$yesterday,		//echo $this->db->last_query(); die;		if(count($edited_mem_data))		{			$dirname = "edited_image_".$current_date;			$directory = $cron_file_path.'/'.$dirname;			if(file_exists($directory)){				// Delete all the files in directory				array_map('unlink', glob($directory."/*.*"));				// Remove directory				rmdir($directory);				$dir_flg = mkdir($directory, 0700);			}			else{				$dir_flg = mkdir($directory, 0700);			}			// Create a zip of images folder			$zip = new ZipArchive;			$Zip_flg = $zip->open($directory.'.zip', ZipArchive::CREATE);			//$imgdata = $edited_img_data[0];			$photo_cnt = 0;			$sign_cnt = 0;			$idproof_cnt = 0;			$declaration_cnt = 0;			$photo_flg_cnt = 0;			$sign_flg_cnt = 0;			$idproof_flg_cnt = 0;			$declaration_flg_cnt = 0;			$vis_imp_cert_img_cnt = 0;			$orth_han_cert_img_cnt = 0;			$cer_palsy_cert_img_cnt = 0;			$vis_imp_cert_img_flg_cnt = 0;			$orth_han_cert_img_flg_cnt = 0;			$cer_palsy_cert_img_flg_cnt = 0;			foreach($edited_mem_data as $imgdata)			{					$img_edited_by = '';				$data = '';				$gender = '';				if($imgdata['gender'] == 'male')	{ $gender = 'M';}				else if($imgdata['gender'] == 'female')	{ $gender = 'F';}				$qualification = '';				switch($imgdata['qualification'])				{					// Values changes as per client req. : 22 July 2019					case "U"	: 	$qualification = 'UG';									break;					case "G"	: 	$qualification = 'G';									break;					case "P"	: 	$qualification = 'PG';									break;						}				//MEM_MEM_NO	,MEM_MEM_TYP	,MEM_TLE,	MEM_NAM_1,	MEM_NAM_2	,MEM_NAM_3,	ID_CARD_NAME,	MEM_ADR_1,	MEM_ADR_2,	MEM_ADR_3,	MEM_ADR_4,	MEM_ADR_5,	MEM_ADR_6,	MEM_PIN_CD,	MEM_STE_CD,	MEM_DOB,	MEM_SEX_CD,	MEM_QLF_GRD,	MEM_QLF_CD,	MEM_INS_CD,	BRANCH,	MEM_DSG_CD,	MEM_BNK_JON_DT ,EMAIL,	STD_R,	PHONE_R,	MOBILE,	ID_TYPE,	ID_NO,	BDRNO,	TRN_DATE,	TRN_AMT,	USR_ID,	AR_FLG,	filename_1 filler char(400),PHOTO LOBFILE (filename_1) TERMINATED BY EOF,	PHOTO_FLG,	SIGNATURE_FLG,	ID_FLG,	UPDATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'				$mem_dob = '';				if($imgdata['dateofbirth'] != '0000-00-00')				{					$mem_dob = date('d-M-y',strtotime($imgdata['dateofbirth']));				}				$mem_doj = '';				if($imgdata['dateofjoin'] != '0000-00-00')				{					$mem_doj = date('d-M-y',strtotime($imgdata['dateofjoin']));				}				if(strlen($imgdata['stdcode']) > 10)				{	$std_code = substr($imgdata['stdcode'],0,9);		}				else				{	$std_code = $imgdata['stdcode'];	}				if(strlen($imgdata['address1']) > 30)				{	$address1 = substr($imgdata['address1'],0,29);		}				else				{	$address1 = $imgdata['address1'];	}				if(strlen($imgdata['address2']) > 30)				{	$address2 = substr($imgdata['address2'],0,29);		}				else				{	$address2 = $imgdata['address2'];	}				if(strlen($imgdata['address3']) > 30)				{	$address3 = substr($imgdata['address3'],0,29);		}				else				{	$address3 = $imgdata['address3'];	}				if(strlen($imgdata['address4']) > 30)				{	$address4 = substr($imgdata['address4'],0,29);		}				else				{	$address4 = $imgdata['address4'];	}				if(strlen($imgdata['district']) > 30)				{	$district = substr($imgdata['district'],0,29);		}				else				{	$district = $imgdata['district'];	}				if(strlen($imgdata['city']) > 30)				{	$city = substr($imgdata['city'],0,29);		}				else				{	$city = $imgdata['city'];	}				if($imgdata['editedby'] == '')				{					$edited_by = "Candidate";				}				else				{					$edited_by = $imgdata['editedby'];					}				$branch = '';				$branch_name = '';				if($imgdata['editedon'] < "2016-12-29 00:00:00")				{					$branch = $imgdata['branch'];				}				else if($imgdata['editedon'] >= "2016-12-29")				{					if(is_numeric($imgdata['office']))					{						if($imgdata['branch']!='')							$branch = $imgdata['branch'];						else							$branch = $imgdata['office'];					}					else					{						if($imgdata['branch']!='')							$branch = $imgdata['branch'];						else							$branch = $imgdata['office'];					}				}				if($branch == '')				{					$branch = $city;				}				if(strlen($branch) > 20)				{	$branch_name = substr($branch,0,19);	}				else				{	$branch_name = $branch;	}				$optnletter = "Y";				if($imgdata['optnletter'] == "optnl")				{	$optnletter = "Y";	}				else if($imgdata['optnletter'] != "")				{	$optnletter = $imgdata['optnletter'];	}				$data = ''.$imgdata['regnumber'].'|'.$imgdata['registrationtype'].'|'.$imgdata['namesub'].'|'.$imgdata['firstname'].'|'.$imgdata['middlename'].'|'.$imgdata['lastname'].'|'.$imgdata['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$imgdata['pincode'].'|'.$imgdata['state'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$imgdata['specify_qualification'].'|'.$imgdata['associatedinstitute'].'|'.$branch_name.'|'.$imgdata['designation'].'|'.$mem_doj.'|'.$imgdata['email'].'|'.$std_code.'|'.$imgdata['office_phone'].'|'.$imgdata['mobile'].'|'.$imgdata['idproof'].'|'.$imgdata['idNo'].'||||';				if($imgdata['images_editedby'] == ''){					$img_edited_by = "Candidate";				}				else{					$img_edited_by = $imgdata['images_editedby'];					}				$this->load->model('Image_search_model');								$photo = $signature = $idproofimg = $declaration = '';				$photo_send = $signature_send = $idproofimg_send = $declaration_send = '';				$photoflag = $imgdata['photo_flg'];				$signflag = $imgdata['signature_flg'];				$idprofflag = $imgdata['id_flg'];				$declarationflag = $imgdata['declaration_flg'];				$member_img_response = $this->Image_search_model->get_member_data($imgdata['regnumber']);			    $photo = $member_img_response['scannedphoto'];			    $idproofimg = $member_img_response['idproofphoto'];			    $declaration = $member_img_response['declaration'];			    $signature = $member_img_response['scannedsignaturephoto'];				if($imgdata['scannedphoto'] != "" && is_file("./uploads/photograph/".$imgdata['scannedphoto']))				{					$photo 	= MEM_FILE_EDIT_PATH.$imgdata['scannedphoto'];				}				if($imgdata['scannedsignaturephoto'] != "" && is_file("./uploads/scansignature/".$imgdata['scannedsignaturephoto']))				{					$signature 	= MEM_FILE_EDIT_PATH.$imgdata['scannedsignaturephoto'];				}				if($imgdata['idproofphoto'] != "" && is_file("./uploads/idproof/".$imgdata['idproofphoto']))				{					$idproofimg = MEM_FILE_EDIT_PATH.$imgdata['idproofphoto'];				}                if($imgdata['declaration'] != "" && is_file("./uploads/declaration/".$imgdata['declaration']))				{					$declaration = MEM_FILE_EDIT_PATH.$imgdata['declaration'];				}								/* Benchmark Code Start */				$benchmark_disability = $imgdata['benchmark_disability'];				$visually_impaired = $imgdata['visually_impaired'];				$vis_imp_cert_img = '';				$orthopedically_handicapped = $imgdata['orthopedically_handicapped'];				$orth_han_cert_img = '';				$cerebral_palsy = $imgdata['cerebral_palsy'];				$cer_palsy_cert_img = '';				if($benchmark_disability == 'Y')				{					$vis_imp_cert_img = '';					if($visually_impaired == 'Y')					{						if($imgdata['vis_imp_cert_img'] != "" && is_file("./uploads/disability/".$imgdata['vis_imp_cert_img']))						{							$vis_imp_cert_img 	= MEM_FILE_EDIT_PATH.$imgdata['vis_imp_cert_img'];						}					}					$orth_han_cert_img = '';					if($orthopedically_handicapped == 'Y')					{						if($imgdata['orth_han_cert_img'] != "" && is_file("./uploads/disability/".$imgdata['orth_han_cert_img']))						{							$orth_han_cert_img 	= MEM_FILE_EDIT_PATH.$imgdata['orth_han_cert_img'];						}					}					$cer_palsy_cert_img = '';					if($cerebral_palsy == 'Y')					{						if($imgdata['cer_palsy_cert_img'] != "" && is_file("./uploads/disability/".$imgdata['cer_palsy_cert_img']))						{							$cer_palsy_cert_img 	= MEM_FILE_EDIT_PATH.$imgdata['cer_palsy_cert_img'];						}					}				}				/* Benchmark Code End */				/* if($imgdata['images_editedon'] == '0000-00-00 00:00:00')				{					 $date = date('d-M-y H:i:s',strtotime($imgdata['editedon']));				}				else				{					$date = date('d-M-y H:i:s',strtotime($imgdata['images_editedon']));				} */				 $date = date('d-M-y H:i:s',strtotime($imgdata['images_editedon']));				//die;				$benchmark_data = $data.$img_edited_by.'|'.$optnletter.'|'.$photoflag.'|'.$signflag.'|'.$idprofflag.'|'.$declarationflag.'|'.$date.'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id'].'|'.$photo.'|'.$signature.'|'.$idproofimg.'|'.$declaration.'|'.$benchmark_disability.'|'.$visually_impaired.'|'.$vis_imp_cert_img.'|'.$orthopedically_handicapped.'|'.$orth_han_cert_img.'|'.$cerebral_palsy.'|'.$cer_palsy_cert_img."\n";				$benchmark_file_flg = fwrite($benchmark_fp, $benchmark_data);				if($benchmark_file_flg)					$success['benchmark_file'] = "Edited Candidate Details Photo File Generated Successfully. ";				else					$error['benchmark_file'] = "Error While Generating Edited Candidate Details Photo File.";								// Zip Image Folder				if($dir_flg)				{					$photo_zip_flg = 0;					$sign_zip_flg = 0;					$idproof_zip_flg = 0;					$declaration_zip_flg = 0;					if($imgdata['photo_flg'] == 'Y' && $photo != '')					{						$photo_flg_cnt++;						// For photo images						if(is_file("./uploads/photograph/".$imgdata['scannedphoto']))						{							$image = "./uploads/photograph/".$imgdata['scannedphoto'];							$max_width = "200";							$max_height = "200";							$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);							imagejpeg($img_resize_data, $directory."/".$imgdata['scannedphoto']);							//copy($actual_photo_path,$directory."/".$actual_photo_name);							$photo_to_add = $directory."/".$imgdata['scannedphoto'];							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);							if(!$photo_zip_flg)							{								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$imgdata['scannedphoto']." (".$imgdata['regnumber'].")\n");								}							else								$photo_cnt++;						}						else						{							fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$imgdata['scannedphoto']." (".$imgdata['regnumber'].")\n");							}					}					if($imgdata['signature_flg'] == 'Y' && $signature != '')					{						$sign_flg_cnt++;						//copy("./uploads/scansignature/".$imgdata['scannedsignaturephoto'],$directory."/".$imgdata['scannedsignaturephoto']);						// For signature images						if(is_file("./uploads/scansignature/".$imgdata['scannedsignaturephoto']))						{							$image = "./uploads/scansignature/".$imgdata['scannedsignaturephoto'];							$max_width = "140";							$max_height = "100";							$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);							imagejpeg($img_resize_data, $directory."/".$imgdata['scannedsignaturephoto']);							//copy($actual_sign_path,$directory."/".$actual_sign_name);							$sign_to_add = $directory."/".$imgdata['scannedsignaturephoto'];							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);							if(!$sign_zip_flg)							{								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$imgdata['scannedsignaturephoto']." (".$imgdata['regnumber'].")\n");								}							else								$sign_cnt++;						}						else						{							fwrite($fp1, "**ERROR** - Signature does not exist  - ".$imgdata['scannedsignaturephoto']." (".$imgdata['regnumber'].")\n");							}					}					if($imgdata['id_flg'] == 'Y' && $idproofimg != '')					{						$idproof_flg_cnt++;						// For ID proof images						if(is_file("./uploads/idproof/".$imgdata['idproofphoto']))						{							$image = "./uploads/idproof/".$imgdata['idproofphoto'];							$max_width = "800";							$max_height = "500";							$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);							imagejpeg($img_resize_data, $directory."/".$imgdata['idproofphoto']);							//copy($actual_idproof_path,$directory."/".$actual_idproof_name);							$proof_to_add = $directory."/".$imgdata['idproofphoto'];							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);							if(!$idproof_zip_flg)							{								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$imgdata['idproofphoto']." (".$imgdata['regnumber'].")\n");								}							else 								$idproof_cnt++;						}						else						{							fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$imgdata['idproofphoto']." (".$imgdata['regnumber'].")\n");							}					}					if($imgdata['id_flg'] == 'Y' && $declaration != '')					{						$declaration_flg_cnt++;						// For declaration images						if(is_file("./uploads/declaration/".$imgdata['declaration']))						{							$image = "./uploads/declaration/".$imgdata['declaration'];							$max_width = "800";							$max_height = "500";							$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);							imagejpeg($img_resize_data, $directory."/".$imgdata['declaration']);							$declaration_to_add = $directory."/".$imgdata['declaration'];							$new_declaration = substr($declaration_to_add,strrpos($declaration_to_add,'/') + 1);							$declaration_zip_flg = $zip->addFile($declaration_to_add,$new_declaration);							if(!$declaration_zip_flg)							{								fwrite($fp1, "**ERROR** - Declaration not added to zip  - ".$imgdata['declaration']." (".$imgdata['regnumber'].")\n");								}							else 								$declaration_cnt++;						}						else						{							fwrite($fp1, "**ERROR** - Declaration does not exist  - ".$imgdata['declaration']." (".$imgdata['regnumber'].")\n");							}					}					if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg|| $declaration_zip_flg)					{						$success['zip'] = "Edited Candidate Images Zip Generated Successfully";					}					else					{						$error['zip'] = "Error While Generating Edited Candidate Images Zip";					}					$benchmark_zip_flg = 0;					/* Benchmark Code Start */					// For Visually impaired certificate images					if($vis_imp_cert_img)					{						$vis_imp_cert_img_flg_cnt++;						if(is_file("./uploads/disability/".$imgdata['vis_imp_cert_img']))						{							$image = "./uploads/disability/".$imgdata['vis_imp_cert_img'];							$max_width = "200";							$max_height = "200";							$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);							imagejpeg($img_resize_data, $directory."/".$imgdata['vis_imp_cert_img']);							$vis_imp_cert_img_to_add = $directory."/".$imgdata['vis_imp_cert_img'];							$new_vis_imp_cert_img = substr($vis_imp_cert_img_to_add,strrpos($vis_imp_cert_img_to_add,'/') + 1);							$vis_imp_cert_img_zip_flg = $zip->addFile($vis_imp_cert_img_to_add,$new_vis_imp_cert_img);							if(!$vis_imp_cert_img_zip_flg)							{								fwrite($fp1, "**ERROR** - Visually impaired certificate not added to zip  - ".$imgdata['vis_imp_cert_img']." (".$imgdata['vis_imp_cert_img'].")\n");								}							else								$vis_imp_cert_img_cnt++;						}						else						{							fwrite($fp1, "**ERROR** - Visually impaired certificate does not exist  - ".$imgdata['vis_imp_cert_img']." (".$imgdata['vis_imp_cert_img'].")\n");							}					}					// For Orthopedically handicapped certificate images					if($orth_han_cert_img)					{						$orth_han_cert_img_flg_cnt++;						if(is_file("./uploads/disability/".$imgdata['orth_han_cert_img']))						{							$image = "./uploads/disability/".$imgdata['orth_han_cert_img'];							$max_width = "200";							$max_height = "200";							$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);							imagejpeg($img_resize_data, $directory."/".$imgdata['orth_han_cert_img']);							$orth_han_cert_img_to_add = $directory."/".$imgdata['orth_han_cert_img'];							$new_orth_han_cert_img = substr($orth_han_cert_img_to_add,strrpos($orth_han_cert_img_to_add,'/') + 1);							$orth_han_cert_img_zip_flg = $zip->addFile($orth_han_cert_img_to_add,$new_orth_han_cert_img);							if(!$orth_han_cert_img_zip_flg)							{								fwrite($fp1, "**ERROR** - Orthopedically handicapped certificate not added to zip  - ".$imgdata['orth_han_cert_img']." (".$imgdata['orth_han_cert_img'].")\n");								}							else								$orth_han_cert_img_cnt++;						}						else						{							fwrite($fp1, "**ERROR** - Orthopedically handicapped certificate does not exist  - ".$imgdata['orth_han_cert_img']." (".$imgdata['orth_han_cert_img'].")\n");							}					}					// For Cerebral palsy certificate images					if($cer_palsy_cert_img)					{						$cer_palsy_cert_img_flg_cnt++;						if(is_file("./uploads/disability/".$imgdata['cer_palsy_cert_img']))						{							$image = "./uploads/disability/".$imgdata['cer_palsy_cert_img'];							$max_width = "200";							$max_height = "200";							$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);							imagejpeg($img_resize_data, $directory."/".$imgdata['cer_palsy_cert_img']);							$cer_palsy_cert_img_to_add = $directory."/".$imgdata['cer_palsy_cert_img'];							$new_cer_palsy_cert_img = substr($cer_palsy_cert_img_to_add,strrpos($cer_palsy_cert_img_to_add,'/') + 1);							$cer_palsy_cert_img_zip_flg = $zip->addFile($cer_palsy_cert_img_to_add,$new_cer_palsy_cert_img);							if(!$cer_palsy_cert_img_zip_flg)							{								fwrite($fp1, "**ERROR** - Cerebral palsy certificate not added to zip  - ".$imgdata['cer_palsy_cert_img']." (".$imgdata['cer_palsy_cert_img'].")\n");								}							else								$cer_palsy_cert_img_cnt++;						}						else						{							fwrite($fp1, "**ERROR** - Cerebral palsy certificate does not exist  - ".$imgdata['cer_palsy_cert_img']." (".$imgdata['cer_palsy_cert_img'].")\n");							}					}					if($benchmark_zip_flg)					{						$success['zip'] = "Edited Candidate Benchmark Zip Generated Successfully";					}					else					{						$error['zip'] = "Error While Generating Edited Candidate Benchmark Zip";					}				}										}			fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."/".$photo_flg_cnt." \n");			fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."/".$sign_flg_cnt."\n");			fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."/".$idproof_flg_cnt."\n");			fwrite($fp1, "\n"."Total Declaration Added = ".$declaration_cnt."/".$declaration_flg_cnt."\n");			fwrite($fp1, "\n"."Total Visually impaired cert Added = ".$vis_imp_cert_img_cnt."/".$vis_imp_cert_img_flg_cnt." \n");			fwrite($fp1, "\n"."Total Orthopedically handicapped cert Added = ".$orth_han_cert_img_cnt."/".$orth_han_cert_img_flg_cnt." \n");			fwrite($fp1, "\n"."Total Cerebral palsy cert Added = ".$cer_palsy_cert_img_cnt."/".$cer_palsy_cert_img_flg_cnt." \n");			$zip->close();		}		else		{			$success['img'] = "No Image data found for the date";		}			fclose($benchmark_fp);		/* fclose($photo_fp);		fclose($sign_fp);		fclose($id_fp); */		// Cron End Logs 		$end_time = date("Y-m-d H:i:s");		$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);		$desc = json_encode($result);		$this->log_model->cronlog("Edited Candidate Details Cron End", $desc);		fwrite($fp1, "\n"."********** Edited Candidate Details Cron End ".$end_time." ***********"."\n");		fclose($fp1);	}}
	/* Membership benchmark Edit data Cron */
	public function edit_data_new()
	{
		ini_set("memory_limit", "-1");
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
		$cron_file_dir = "./uploads/cronFilesCustom/";
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
			/* $photo_file = "edited_cand_details_photo_".$current_date.".txt";
			$sign_file = "edited_cand_details_sign_".$current_date.".txt";
			$id_file = "edited_cand_details_id_".$current_date.".txt";
			$photo_fp = fopen($cron_file_path.'/'.$photo_file, 'w');
			$sign_fp = fopen($cron_file_path.'/'.$sign_file, 'w');
			$id_fp = fopen($cron_file_path.'/'.$id_file, 'w'); */
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Edited Candidate Details Cron Start - ".$start_time."**********\n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2021-12-29';
			//$today = date('Y-m-d');
			$member = array(400041962); 
			$this->db->where_in('regnumber',$member);
			$edited_mem_data = $this->Master_model->getRecords('member_registration',array(' DATE(editedon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
			//' DATE(editedon)'=>$yesterday,
			//echo $this->db->last_query();
			if(count($edited_mem_data))
			{
				$i = 1;
				$mem_cnt = 0;
				foreach($edited_mem_data as $editeddata)
				{
					$photo_data = '';
					$sign_data = '';
					$id_data = '';
					$data = '';
					$gender = '';
					if($editeddata['gender'] == 'male')	{ $gender = 'M';}
					else if($editeddata['gender'] == 'female')	{ $gender = 'F';}
					$qualification = '';
					switch($editeddata['qualification'])
					{				
						// Values changes as per client req. : 22 July 2019
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;
						/* 			
							case "U"	: 	$qualification = 1;
										break;
							case "G"	: 	$qualification = 3;
										break;
							case "P"	: 	$qualification = 5;
										break;				
						*/						
					}
					//MEM_MEM_NO	,MEM_MEM_TYP	,MEM_TLE,	MEM_NAM_1,	MEM_NAM_2	,MEM_NAM_3,	ID_CARD_NAME,	MEM_ADR_1,	MEM_ADR_2,	MEM_ADR_3,	MEM_ADR_4,	MEM_ADR_5,	MEM_ADR_6,	MEM_PIN_CD,	MEM_STE_CD,	MEM_DOB,	MEM_SEX_CD,	MEM_QLF_GRD,	MEM_QLF_CD,	MEM_INS_CD,	BRANCH,	MEM_DSG_CD,	MEM_BNK_JON_DT ,EMAIL,	STD_R,	PHONE_R,	MOBILE,	ID_TYPE,	ID_NO,	BDRNO,	TRN_DATE,	TRN_AMT,	USR_ID,	AR_FLG,	filename_1 filler char(400),PHOTO LOBFILE (filename_1) TERMINATED BY EOF,	PHOTO_FLG,	SIGNATURE_FLG,	ID_FLG,	UPDATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'
					$mem_dob = '';
					if($editeddata['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($editeddata['dateofbirth']));
					}
					$mem_doj = '';
					if($editeddata['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($editeddata['dateofjoin']));
					}
					if(strlen($editeddata['stdcode']) > 10)
					{	$std_code = substr($editeddata['stdcode'],0,9);		}
					else
					{	$std_code = $editeddata['stdcode'];	}
					if(strlen($editeddata['address1']) > 30)
					{	$address1 = substr($editeddata['address1'],0,29);		}
					else
					{	$address1 = $editeddata['address1'];	}
					if(strlen($editeddata['address2']) > 30)
					{	$address2 = substr($editeddata['address2'],0,29);		}
					else
					{	$address2 = $editeddata['address2'];	}
					if(strlen($editeddata['address3']) > 30)
					{	$address3 = substr($editeddata['address3'],0,29);		}
					else
					{	$address3 = $editeddata['address3'];	}
					if(strlen($editeddata['address4']) > 30)
					{	$address4 = substr($editeddata['address4'],0,29);		}
					else
					{	$address4 = $editeddata['address4'];	}
					if(strlen($editeddata['district']) > 30)
					{	$district = substr($editeddata['district'],0,29);		}
					else
					{	$district = $editeddata['district'];	}
					if(strlen($editeddata['city']) > 30)
					{	$city = substr($editeddata['city'],0,29);		}
					else
					{	$city = $editeddata['city'];	}
					if($editeddata['editedby'] == '')
					{
						$edited_by = "Candidate";
					}
					else
					{
						$edited_by = $editeddata['editedby'];	
					}
					$branch = '';
					$branch_name = '';
					if($editeddata['editedon'] < "2016-12-29 00:00:00")
					{
						$branch = $editeddata['branch'];
					}
					else if($editeddata['editedon'] >= "2016-12-29")
					{
						if(is_numeric($editeddata['office']))
						{
							if($editeddata['branch']!='')
								$branch = $editeddata['branch'];
							else
								$branch = $editeddata['office'];
						}
						else
						{
							if($editeddata['branch']!='')
								$branch = $editeddata['branch'];
							else
								$branch = $editeddata['office'];
						}
					}
					if($branch == '')
					{
						$branch = $city;
					}
					if(strlen($branch) > 20)
					{	$branch_name = substr($branch,0,19);	}
					else
					{	$branch_name = $branch;	}
					$optnletter = "Y";
					if($editeddata['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else if($editeddata['optnletter'] != "")
					{	$optnletter = $editeddata['optnletter'];	}
					/* $data = ''.$editeddata['regnumber'].'|'.$editeddata['registrationtype'].'|'.$editeddata['namesub'].'|'.$editeddata['firstname'].'|'.$editeddata['middlename'].'|'.$editeddata['lastname'].'|'.$editeddata['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$editeddata['pincode'].'|'.$editeddata['state'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$editeddata['specify_qualification'].'|'.$editeddata['associatedinstitute'].'|'.$branch_name.'|'.$editeddata['designation'].'|'.$mem_doj.'|'.$editeddata['email'].'|'.$std_code.'|'.$editeddata['office_phone'].'|'.$editeddata['mobile'].'|'.$editeddata['idproof'].'|'.$editeddata['idNo'].'||||';
					$data .= $edited_by.'|'.$optnletter.'|N|N|N|'.date('d-M-y H:i:s',strtotime($editeddata['editedon'])).'|'.$editeddata['aadhar_card'].'|'.$editeddata['bank_emp_id']."\n";
					$edit_data_flg = fwrite($fp, $data);
					if($edit_data_flg)
						$success['edit_data'] = "Edited Candidate Details File Generated Successfully.";
					else
						$error['edit_data'] = "Error While Generating Edited Candidate Details File.";
					$i++;
					$mem_cnt++; */
				}
				fwrite($fp1, "\n"."Total Members Edited = ".$mem_cnt."\n");
			}
			else
			{
				$success[] = "No Profile data found for the date";
			}
			// Image data 
			$member = array(400041962);
			$this->db->where_in('regnumber',$member);
			$edited_img_data = $this->Master_model->getRecords('member_registration',array('DATE(images_editedon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
			//'DATE(images_editedon)'=>$yesterday,
			//echo $this->db->last_query(); die;
			if(count($edited_img_data))
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
				//$imgdata = $edited_img_data[0];
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				$photo_flg_cnt = 0;
				$sign_flg_cnt = 0;
				$idproof_flg_cnt = 0;
				foreach($edited_img_data as $imgdata)
				{	
					/* $photo = '';
					$signature = '';
					$idproofimg = ''; */
					$img_edited_by = '';
					$data = '';
					$gender = '';
					if($imgdata['gender'] == 'male')	{ $gender = 'M';}
					else if($imgdata['gender'] == 'female')	{ $gender = 'F';}
					$qualification = '';
					switch($imgdata['qualification'])
					{
						// Values changes as per client req. : 22 July 2019
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;
						/* 			
							case "U"	: 	$qualification = 1;
										break;
							case "G"	: 	$qualification = 3;
										break;
							case "P"	: 	$qualification = 5;
										break;				
						*/			
					}
					//MEM_MEM_NO	,MEM_MEM_TYP	,MEM_TLE,	MEM_NAM_1,	MEM_NAM_2	,MEM_NAM_3,	ID_CARD_NAME,	MEM_ADR_1,	MEM_ADR_2,	MEM_ADR_3,	MEM_ADR_4,	MEM_ADR_5,	MEM_ADR_6,	MEM_PIN_CD,	MEM_STE_CD,	MEM_DOB,	MEM_SEX_CD,	MEM_QLF_GRD,	MEM_QLF_CD,	MEM_INS_CD,	BRANCH,	MEM_DSG_CD,	MEM_BNK_JON_DT ,EMAIL,	STD_R,	PHONE_R,	MOBILE,	ID_TYPE,	ID_NO,	BDRNO,	TRN_DATE,	TRN_AMT,	USR_ID,	AR_FLG,	filename_1 filler char(400),PHOTO LOBFILE (filename_1) TERMINATED BY EOF,	PHOTO_FLG,	SIGNATURE_FLG,	ID_FLG,	UPDATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'
					$mem_dob = '';
					if($imgdata['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($imgdata['dateofbirth']));
					}
					$mem_doj = '';
					if($imgdata['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($imgdata['dateofjoin']));
					}
					if(strlen($imgdata['stdcode']) > 10)
					{	$std_code = substr($imgdata['stdcode'],0,9);		}
					else
					{	$std_code = $imgdata['stdcode'];	}
					if(strlen($imgdata['address1']) > 30)
					{	$address1 = substr($imgdata['address1'],0,29);		}
					else
					{	$address1 = $imgdata['address1'];	}
					if(strlen($imgdata['address2']) > 30)
					{	$address2 = substr($imgdata['address2'],0,29);		}
					else
					{	$address2 = $imgdata['address2'];	}
					if(strlen($imgdata['address3']) > 30)
					{	$address3 = substr($imgdata['address3'],0,29);		}
					else
					{	$address3 = $imgdata['address3'];	}
					if(strlen($imgdata['address4']) > 30)
					{	$address4 = substr($imgdata['address4'],0,29);		}
					else
					{	$address4 = $imgdata['address4'];	}
					if(strlen($imgdata['district']) > 30)
					{	$district = substr($imgdata['district'],0,29);		}
					else
					{	$district = $imgdata['district'];	}
					if(strlen($imgdata['city']) > 30)
					{	$city = substr($imgdata['city'],0,29);		}
					else
					{	$city = $imgdata['city'];	}
					if($imgdata['editedby'] == '')
					{
						$edited_by = "Candidate";
					}
					else
					{
						$edited_by = $imgdata['editedby'];	
					}
					$branch = '';
					$branch_name = '';
					if($imgdata['editedon'] < "2016-12-29 00:00:00")
					{
						$branch = $imgdata['branch'];
					}
					else if($imgdata['editedon'] >= "2016-12-29")
					{
						if(is_numeric($imgdata['office']))
						{
							if($imgdata['branch']!='')
								$branch = $imgdata['branch'];
							else
								$branch = $imgdata['office'];
						}
						else
						{
							if($imgdata['branch']!='')
								$branch = $imgdata['branch'];
							else
								$branch = $imgdata['office'];
						}
					}
					if($branch == '')
					{
						$branch = $city;
					}
					if(strlen($branch) > 20)
					{	$branch_name = substr($branch,0,19);	}
					else
					{	$branch_name = $branch;	}
					$optnletter = "Y";
					if($imgdata['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else if($imgdata['optnletter'] != "")
					{	$optnletter = $imgdata['optnletter'];	}
					$data = ''.$imgdata['regnumber'].'|'.$imgdata['registrationtype'].'|'.$imgdata['namesub'].'|'.$imgdata['firstname'].'|'.$imgdata['middlename'].'|'.$imgdata['lastname'].'|'.$imgdata['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$imgdata['pincode'].'|'.$imgdata['state'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$imgdata['specify_qualification'].'|'.$imgdata['associatedinstitute'].'|'.$branch_name.'|'.$imgdata['designation'].'|'.$mem_doj.'|'.$imgdata['email'].'|'.$std_code.'|'.$imgdata['office_phone'].'|'.$imgdata['mobile'].'|'.$imgdata['idproof'].'|'.$imgdata['idNo'].'||||';
					if($imgdata['images_editedby'] == '')
					{
						$img_edited_by = "Candidate";
					}
					else
					{
						$img_edited_by = $imgdata['images_editedby'];	
					}
					if($imgdata['scannedphoto'] != "" && is_file("./uploads/photograph/".$imgdata['scannedphoto']))
					{
						$photo 	= MEM_FILE_EDIT_PATH.$imgdata['scannedphoto'];
					}
					if($imgdata['scannedsignaturephoto'] != "" && is_file("./uploads/scansignature/".$imgdata['scannedsignaturephoto']))
					{
						$signature 	= MEM_FILE_EDIT_PATH.$imgdata['scannedsignaturephoto'];
					}
					if($imgdata['idproofphoto'] != "" && is_file("./uploads/idproof/".$imgdata['idproofphoto']))
					{
						$idproofimg = MEM_FILE_EDIT_PATH.$imgdata['idproofphoto'];
					}
					if($photo != '')
					{
						if($imgdata['photo_flg']=='Y')
						{
							$data .= $data.$img_edited_by.'|'.$optnletter.'|'.$photo.'|Y|N|N|'.date('d-M-y H:i:s',strtotime($imgdata['images_editedon'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id']."\n";
							$photo_file_flg = fwrite($fp, $data);
						}
						$edited_photo_flg = $imgdata['photo_flg'];
						if($photo_file_flg)
							$success['photo_file'] = "Edited Candidate Details Photo File Generated Successfully. ";
						else
							$error['photo_file'] = "Error While Generating Edited Candidate Details Photo File.";
					}
					else
					{
						$edited_photo_flg = "N";
					}
					if($signature != '')
					{
						if($imgdata['signature_flg']=='Y')
						{
							$data .= $data.$img_edited_by.'|'.$optnletter.'|'.$signature.'|N|Y|N|'.date('d-M-y H:i:s',strtotime($imgdata['images_editedon'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id']."\n";
							$sign_file_flg = fwrite($fp, $data);
						}
						$edited_sign_flg = $imgdata['signature_flg'];
						if($sign_file_flg)
							$success['sign_file'] = "Edited Candidate Details Signature File Generated Successfully.";
						else
							$error['sign_file'] = "Error While Generating Edited Candidate Details Signature File.";
					}
					else
					{
						$edited_sign_flg = "N";	
					}
					if($idproofimg != '')
					{
						if($imgdata['id_flg']=='Y')
						{
							$data .= $data.$img_edited_by.'|'.$optnletter.'|'.$idproofimg.'|N|N|Y|'.date('d-M-y H:i:s',strtotime($imgdata['images_editedon'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id']."\n";
							$idproof_file_flg =  fwrite($fp, $data);
						}
						$edited_idproof_flg = $imgdata['id_flg'];
						if($idproof_file_flg)
							$success['idproof_file'] = "Edited Candidate Details Id-Proof File Generated Successfully. ";
						else
							$error['idproof_file'] = "Error While Generating Edited Candidate Details Id-Proof File.";
					}
					else
					{
						$edited_idproof_flg = "N";
					}
					// Zip Image Folder
					if($dir_flg)
					{
						$photo_zip_flg = 0;
						$sign_zip_flg = 0;
						$idproof_zip_flg = 0;
						if($imgdata['photo_flg'] == 'Y' && $photo != '')
						{
							$photo_flg_cnt++;
							// For photo images
							if(is_file("./uploads/photograph/".$imgdata['scannedphoto']))
							{
								$image = "./uploads/photograph/".$imgdata['scannedphoto'];
								$max_width = "200";
								$max_height = "200";
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['scannedphoto']);
								//copy($actual_photo_path,$directory."/".$actual_photo_name);
								$photo_to_add = $directory."/".$imgdata['scannedphoto'];
								$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
								$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
								if(!$photo_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$imgdata['scannedphoto']." (".$imgdata['regnumber'].")\n");	
								}
								else
									$photo_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$imgdata['scannedphoto']." (".$imgdata['regnumber'].")\n");	
							}
						}
						if($imgdata['signature_flg'] == 'Y' && $signature != '')
						{
							$sign_flg_cnt++;
							//copy("./uploads/scansignature/".$imgdata['scannedsignaturephoto'],$directory."/".$imgdata['scannedsignaturephoto']);
							// For signature images
							if(is_file("./uploads/scansignature/".$imgdata['scannedsignaturephoto']))
							{
								$image = "./uploads/scansignature/".$imgdata['scannedsignaturephoto'];
								$max_width = "140";
								$max_height = "100";
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['scannedsignaturephoto']);
								//copy($actual_sign_path,$directory."/".$actual_sign_name);
								$sign_to_add = $directory."/".$imgdata['scannedsignaturephoto'];
								$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
								$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
								if(!$sign_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$imgdata['scannedsignaturephoto']." (".$imgdata['regnumber'].")\n");	
								}
								else
									$sign_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Signature does not exist  - ".$imgdata['scannedsignaturephoto']." (".$imgdata['regnumber'].")\n");	
							}
						}
						if($imgdata['id_flg'] == 'Y' && $idproofimg != '')
						{
							$idproof_flg_cnt++;
							// For ID proof images
							if(is_file("./uploads/idproof/".$imgdata['idproofphoto']))
							{
								$image = "./uploads/idproof/".$imgdata['idproofphoto'];
								$max_width = "800";
								$max_height = "500";
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['idproofphoto']);
								//copy($actual_idproof_path,$directory."/".$actual_idproof_name);
								$proof_to_add = $directory."/".$imgdata['idproofphoto'];
								$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
								$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
								if(!$idproof_zip_flg)
								{
									fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$imgdata['idproofphoto']." (".$imgdata['regnumber'].")\n");	
								}
								else 
									$idproof_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$imgdata['idproofphoto']." (".$imgdata['regnumber'].")\n");	
							}
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
				}
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."/".$photo_flg_cnt." \n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."/".$sign_flg_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."/".$idproof_flg_cnt."\n");
				$zip->close();
			}
			else
			{
				$success['img'] = "No Image data found for the date";
			}
			fclose($fp);
			/* fclose($photo_fp);
			fclose($sign_fp);
			fclose($id_fp); */
			// Cron End Logs 
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Edited Candidate Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Edited Candidate Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	
	/* Temp Exam Application Cron*/
	public function exam_temp()
	{	//exit;
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Candidate Exam Temp Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "temp_exam_cand_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "temp_logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Candidate Exam Temp Details Cron Start - ".$start_time." *********** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			$this->db->where_in('a.regnumber', $member);
			$member = array('510276652','100017682','500050705','500134768','500161321','500169210','510086332','510144765','510202376','510279672','510227819','510375250','510422317');
			$exam_codes_arr = array('1002','1003','1004');
			$select = 'a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,a.id,a.created_on';
			$this->db->where_in('a.regnumber', $member);
			$this->db->where_in('a.exam_code',$exam_codes_arr);
			$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array('isactive'=>'1','isdeleted'=>0,'pay_status'=>1),$select);
			//' DATE(a.created_on)'=>$yesterday,
			echo $this->db->last_query();
			if(count($can_exam_data))
			{
				$i = 1;
				$exam_cnt = 0;
				foreach($can_exam_data as $exam)
				{
					$select = 'ref_id';
					$check_payment_data = $this->Master_model->getRecords('payment_transaction',array('pay_type'=>2,'status'=>1,'ref_id'=>$exam['id']),$select);
					if(empty($check_payment_data))
					{
						$data = '';
					//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG
					$exam_mode = 'O';
					if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
					else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
					else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
					$syllabus_code = '';
					$part_no = '';
					$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
					if(count($subject_data)>0)
					{
						$syllabus_code = $subject_data[0]['syllabus_code'];
						$part_no = $subject_data[0]['part_no'];
					}
					$trans_date = '';
					if($exam['created_on'] != '0000-00-00')
					{
						$trans_date = date('d-M-Y',strtotime($exam['created_on']));
					}
					$exam_period = '777';
					if($exam['examination_date'] != '' && $exam['examination_date'] != "0000-00-00")
					{
						$ex_period = $this->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam['examination_date']));
						if(count($ex_period))
						{
							$exam_period = $ex_period[0]['period'];	
						}
					}
					else{	$exam_period = $exam['exam_period'];	}
					$exam_code = '';
					if($exam['exam_code'] == 340 || $exam['exam_code'] == 3400 || $exam['exam_code'] == 34000)
					{
					 	$exam_code = 34;
					}elseif($exam['exam_code'] == 580 || $exam['exam_code'] == 5800 || $exam['exam_code'] == 58000){
						$exam_code = 58;
					}elseif($exam['exam_code'] == 1600 || $exam['exam_code'] == 16000){
						$exam_code = 160;
					}elseif($exam['exam_code'] == 200){
						$exam_code = 20;
					}elseif($exam['exam_code'] == 1770 || $exam['exam_code'] == 17700){
						$exam_code =177;
					}elseif ($exam['exam_code'] == 590){
						$exam_code = 59;
					}elseif ($exam['exam_code'] == 810){
						$exam_code = 81;
					}elseif ($exam['exam_code'] == 1750){
						$exam_code = 175;
					}else{
						$exam_code = $exam['exam_code'];
					}
					$scribe_flag = $exam['scribe_flag'];
					// Condition for DISA and CSIC Exam Application
					if($exam_code == '990' || $exam_code == '993'){
						$part_no = 1;
						$exam_mode = 'O';
						$syllabus_code = 'R';
						$scribe_flag = 'N';
					}
					$place_of_work = '';
					$pin_code_place_of_work = '';
					$state_place_of_work = '';
					$city = '';
					$branch = '';
					$branch_name = '';
					$state = '';
					$pincode = '';
					//$exam['elected_sub_code']!=0
					//if($exam_code == 60 || $exam_code == 21) // For CAIIB & JAIIB
					if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == $this->config->item('examCodeCaiibElective68') || $exam_code == $this->config->item('examCodeCaiibElective69') || $exam_code == $this->config->item('examCodeCaiibElective70') || $exam_code == $this->config->item('examCodeCaiibElective71') || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) // For CAIIB, CAIIB Elective & JAIIB, above line commented and this line added by Bhagwan Sahane, on 06-10-2017
					{
						if($exam['state'])
						{	$state = $exam['state']; }
						if($exam['pincode'])
						{	$pincode = $exam['pincode']; }
						if(strlen($exam['city']) > 30)
						{	$city = substr($exam['city'],0,29);		}
						else
						{	$city = $exam['city'];	}
						if($exam['editedon'] < "2016-12-29 00:00:00")
						{
							$branch = $exam['branch'];
						}
						else if($exam['editedon'] >= "2016-12-29")
						{
							if(is_numeric($exam['office']))
							{
								if($exam['branch']!='')
									$branch = $exam['branch'];
								else
									$branch = $city;
							}
							else
							{
								if($exam['branch']!='')
									$branch = $exam['branch'];
								else
									$branch = $exam['office'];
							}
						}
						if($branch == '')
						{
							$branch = $city;
						}
						if(strlen($branch) > 20)
						{	$branch_name = substr($branch,0,19); }
						else
						{	$branch_name = $branch;	}
						if($exam['place_of_work'])
							$place_of_work = $exam['place_of_work'];
						else
							$place_of_work =  $branch_name;
						if($exam['pin_code_place_of_work'])
							$pin_code_place_of_work = $exam['pin_code_place_of_work'];
						else
							$pin_code_place_of_work =  $pincode;
						if($exam['state_place_of_work'])
							$state_place_of_work = $exam['state_place_of_work'];
						else
							$state_place_of_work =  $state;
						$elected_sub_code = '';
						if($exam_code == $this->config->item('examCodeCaiib'))
						{	$elected_sub_code = $exam['elected_sub_code'];	}
						if(strlen($place_of_work) > 30)
						{	$place_of_work = substr($place_of_work,0,29);		}
						else
						{	$place_of_work = $place_of_work;	}
						// Get old exam_code for CAIIB
						/*if($exam_code == 60)
						{
							$ex_code = $this->master_model->getRecords('eligible_master_60_117',array('member_no'=>$exam['regnumber'],'member_type'=>$exam['registrationtype']),'exam_code');
							if(count($ex_code))
							{
								$exam_code = $ex_code[0]['exam_code'];
							}
						}*/
						$transaction_no = '0000000000000';
						$exam_fee = '0';
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$transaction_no.'|'.$trans_date.'|'.$exam_fee.'|'.$transaction_no.'|'.$scribe_flag."\n";
					}
					else
					{    
						$transaction_no = '0000000000000';
						$exam_fee = '0';
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$transaction_no.'|'.$trans_date.'|'.$exam_fee.'|'.$transaction_no.'|'.$scribe_flag."\n";
					}
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "Candidate Exam Details File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating Candidate Exam Details File.";
					$i++;
					$exam_cnt++;
					}
				}
				fwrite($fp1, "Total Exam Temp Applications - ".$exam_cnt."\n");
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
			$this->log_model->cronlog("Candidate Exam Temp Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Candidate Exam Temp Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	/* Admit Card Cron */
	public function admit_card_temp()
	{
		//exit;
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		//$cron_file_dir = "./uploads/cronFilesCustom/";
		$cron_file_dir = "./uploads/cronFilesCustom/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("Exam Admit Card Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "temp_admit_card_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "temp_logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Exam Temp Admit Card Details Cron Start - ".$start_time." ********** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2017-08-04';
			// get member exam application details for given date
			$exam_codes_arr = array('1003');
			$ref_id = array(4888661,4888450,4889744,4888457,4887440);
			$select = 'a.id as mem_exam_id, a.examination_date';
			$this->db->where_in('a.id' , $ref_id);
			$this->db->where_in('a.exam_code',$exam_codes_arr);
			$cand_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_status'=>1),$select);
			//'pay_type'=>2,'status'=>1,
			//' DATE(a.created_on)'=>$yesterday,
			echo $this->db->last_query();
			if(count($cand_exam_data))
			{
				$admit_card_count = 0;
				$admit_card_sub_count = 0;
				foreach($cand_exam_data as $exam)
				{
					$mem_exam_id = $exam['mem_exam_id'];
					$select = 'ref_id';
					$check_payment_data = $this->Master_model->getRecords('payment_transaction',array('pay_type'=>2,'status'=>1,'ref_id'=>$mem_exam_id),$select);
					if(empty($check_payment_data))
					{
						// get admit card details for this member by mem_exam_id
						$this->db->where('remark', 1);
						$this->db->where_in('exc_prd', '777');
						$admit_card_details_arr = $this->Master_model->getRecords('admit_card_details',array('mem_exam_id' => $mem_exam_id));
						echo $this->db->last_query();
						if(count($admit_card_details_arr))
						{
							foreach($admit_card_details_arr as $admit_card_data)
							{
								$data = '';
								$exam_date = date('d-M-y',strtotime($admit_card_data['exam_date']));
								$trn_date = date('d-M-y',strtotime($admit_card_data['created_on']));
								$venue_name = '';
								if($admit_card_data['venue_name'] != '')
								{
									$venue_name = trim(str_replace(PHP_EOL, '', $admit_card_data['venue_name']));
									$venue_name = str_replace(array("\n", "\r"), '', $venue_name);
								}
								$venueadd1 = '';
								if($admit_card_data['venueadd1'] != '')
								{
									$venueadd1 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd1']));
									$venueadd1 = str_replace(array("\n", "\r"), '', $venueadd1);
								}
								$venueadd2 = '';
								if($admit_card_data['venueadd2'] != '')
								{
									$venueadd2 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd2']));
									$venueadd2 = str_replace(array("\n", "\r"), '', $venueadd2);
								}
								$venueadd3 = '';
								if($admit_card_data['venueadd3'] != '')
								{
									$venueadd3 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd3']));
									$venueadd3 = str_replace(array("\n", "\r"), '', $venueadd3);
								}
								// code to get actual exam period for exam application, added by Bhagwan Sahane, on 28-09-2017
								$exam_period = '777';
								if($exam['examination_date'] != '' && $exam['examination_date'] != "0000-00-00")
								{
									$ex_period = $this->Master_model->getRecords('special_exam_dates',array('examination_date'=>$exam['examination_date']));
									if(count($ex_period))
									{
										$exam_period = $ex_period[0]['period'];	
									}
								}
								else
								{
									$exam_period = $admit_card_data['exm_prd'];
								}
								$exam_code = '';
								if($admit_card_data['exm_cd'] == 340 || $admit_card_data['exm_cd'] == 3400 || $admit_card_data['exm_cd'] == 34000)
								{
									$exam_code = 34;
								}elseif($admit_card_data['exm_cd'] == 580 || $admit_card_data['exm_cd'] == 5800 || $admit_card_data['exm_cd'] == 58000){
									$exam_code = 58;
								}elseif($admit_card_data['exm_cd'] == 1600 || $admit_card_data['exm_cd'] == 16000){
									$exam_code = 160;
								}elseif($admit_card_data['exm_cd'] == 200){
									$exam_code = 20;
								}elseif($admit_card_data['exm_cd'] == 1770 || $admit_card_data['exm_cd'] == 17700){
									$exam_code =177;
								}elseif ($admit_card_data['exm_cd'] == 590){
									$exam_code = 59;
								}elseif ($admit_card_data['exm_cd'] == 810){
									$exam_code = 81;
								}elseif ($admit_card_data['exm_cd'] == 1750){
									$exam_code = 175;
								}else{
									$exam_code = $admit_card_data['exm_cd'];
								}
								$transaction_no = '0000000000000';
	//EXM_CD|EXM_PRD|MEMBER_NO|CTR_CD|CTR_NAM|SUB_CD|SUB_DSC|VENUE_CD|VENUE_ADDR1|VENUE_ADDR2|VENUE_ADDR3|VENUE_ADDR4|VENUE_ADDR5|VENUE_PINCODE|EXAM_SEAT_NO|EXAM_PASSWORD|EXAM_DATE|EXAM_TIME|EXAM_MODE(Online/Offline)| EXAM_MEDIUM|SCRIBE_FLG(Y/N)|VENDOR_CODE(1/3)|TRN_DATE|VENUE_NAME
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$admit_card_data['mem_mem_no'].'|'.$admit_card_data['center_code'].'|'.$admit_card_data['center_name'].'|'.$admit_card_data['sub_cd'].'|'.$admit_card_data['sub_dsc'].'|'.$admit_card_data['venueid'].'|'.$venueadd1.'|'.$venueadd2.'|'.$venueadd3.'|'.$admit_card_data['venueadd4'].'|'.$admit_card_data['venueadd5'].'|'.$admit_card_data['venpin'].'|'.$admit_card_data['seat_identification'].'|'.$admit_card_data['pwd'].'|'.$exam_date.'|'.$admit_card_data['time'].'|'.$admit_card_data['mode'].'|'.$admit_card_data['m_1'].'|'.$admit_card_data['scribe_flag'].'|'.$admit_card_data['vendor_code'].'|'.$trn_date.'|'.$venue_name.'|'.$transaction_no."\n";
								$admit_card_sub_count++;
								$file_w_flg = fwrite($fp, $data);
							}
							if($file_w_flg)
							{
								$success[] = "Exam Temp Admit Card Details File Generated Successfully. ";
							}
							else
							{
								$error[] = "Error While Generating Exam Temp Admit Card Details File.";
							}
						}
						$admit_card_count++;
					}
				}
				fwrite($fp1, "\n"."Total Exam Temp Admit Card Details Added = ".$admit_card_count."\n");
				fwrite($fp1, "\n"."Total Exam Temp Admit Card Subject Details Added = ".$admit_card_sub_count."\n");
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
			$this->log_model->cronlog("Exam Temp Admit Card Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Exam Temp Admit Card Details Cron End ".$end_time." **********"."\n");
			fclose($fp1);
		}
	}
///usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_data_gen member_live
	
public function member_live()
	{

		/* All declaration code added by pratibha borse on 6April22 */ 
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$photo_zip_flg = 0;
		$sign_zip_flg = 0;
		$idproof_zip_flg = 0;
		$declaration_zip_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("IIBF New Member Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "iibf_new_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n************************* IIBF New Member Details Cron Execution Started - ".$start_time." ********************* \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			
			$yesterday = '2021-10-11';
			//$this->db->where('DATE(a.createdon) >=', '2019-11-27');
			//$this->db->where('DATE(a.createdon) <=', '2019-11-27');
			$mem = array(510533804,510534038,510534297,510535073,510535120,510535371,510535446,510535453,510535454,510535455,510535459,510535463,510535465,510535467,510535468,510535469,510535478,510535480,510535481,510535482);
			$excode = array('991','526','527');
			$this->db->where_not_in('a.excode', $excode);
			$this->db->where_in('a.regnumber', $mem);
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0));
			echo $this->db->last_query(); 
			//' DATE(createdon)'=>$yesterday,
			if(count($new_mem_reg))
			{
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
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				$declaration_cnt = 0;
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					
					$gender = '';
					if($reg_data['gender'] == 'male')	{ $gender = 'M';}
					else if($reg_data['gender'] == 'female')	{ $gender = 'F';}
					
					$photo = '';
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
					{
						$photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
					{
						$signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
					{
						$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$declarationimg = '';
					if(is_file("./uploads/declaration/".$reg_data['declaration']))
					{
						$declarationimg = MEM_FILE_PATH.$reg_data['declaration'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Declaration does not exist  - ".$reg_data['declaration']." (".$reg_data['regnumber'].")\n");	
					}
					

					$qualification = '';
					switch($reg_data['qualification'])
					{
						// Values changes as per client req. : 22 July 2019
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;
						/* 			
							case "U"	: 	$qualification = 1;
										break;
							case "G"	: 	$qualification = 3;
										break;
							case "P"	: 	$qualification = 5;
										break;				
						*/		
					}
					$transaction_no = '';
					$transaction_date = '';
					$transaction_amt = '0';
					if($reg_data['registrationtype']!='NM')
					{
						
						if($reg_data['registrationtype'] == 'DB')
						{
							
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_DB','pay_type'=>2,'member_regnumber'=>$reg_data['regnumber'],'status'=>1),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
							
							if(empty($trans_details))
		
						{   
							$this->db->select("DATE(a.updated_date) AS date, a.UTR_no AS transaction_no, a.amount");
							$this->db->join('bulk_member_payment_transaction b','a.id = b.ptid','LEFT');
							$this->db->join('member_exam c','b.memexamid = c.id','LEFT');
							$this->db->join('member_registration d','d.regnumber = c.regnumber','LEFT');
							$this->db->where("a.status = 1 AND  d.regnumber = '".$reg_data['regnumber']."'");
							//( DATE(a.updated_date) = '".$yesterday."') AND 
							$trans_details = $this->Master_model->getRecords('bulk_payment_transaction a');	
							//print_r($trans_details); die;
							//$trans_details = $this->db->get('bulk_payment_transaction a');
						}
						//print_r($trans_details); die;
							
							
						}
						else
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'iibfregn','status'=>1,'pay_type'=>1,'ref_id'=>$reg_data['regid']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');							
						}
					}
					else
					{
									
						$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_REG','status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						
						/* For Bulk Members if Non-Member Application */
						
						
						//print_r($trans_details); die;
						if(empty($trans_details))
		
						{   
							$this->db->select("DATE(a.updated_date) AS date, a.UTR_no AS transaction_no, a.amount");
							$this->db->join('bulk_member_payment_transaction b','a.id = b.ptid','LEFT');
							$this->db->join('member_exam c','b.memexamid = c.id','LEFT');
							$this->db->join('member_registration d','d.regnumber = c.regnumber','LEFT');
							$this->db->where("a.status = 1 AND  d.regnumber = '".$reg_data['regnumber']."'");
							//( DATE(a.updated_date) = '".$yesterday."') AND 
							$trans_details = $this->Master_model->getRecords('bulk_payment_transaction a');	
							//print_r($trans_details); die;
							//$trans_details = $this->db->get('bulk_payment_transaction a');
						}
					}
					if(count($trans_details))
					{
						$transaction_no = $trans_details[0]['transaction_no'];
						$transaction_amt = $trans_details[0]['amount'];
						$date = $trans_details[0]['date'];
						if($date!='0000-00-00')
						{	$transaction_date = date('d-M-y',strtotime($date));	}
					}
					elseif($reg_data['excode'] == '191' || $reg_data['excode'] == '1910' || $reg_data['excode'] == '19100')
					{
						$get_date = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','regnumber'=>$reg_data['regnumber']),'DATE_FORMAT(createdon,"%Y-%m-%d") createdon');
						
						$date = $get_date[0]['createdon'];
						$transaction_date = date('d-M-y',strtotime($date));	
						$transaction_no = '000000000000';
						$transaction_amt = '0';	
					}
					$mem_dob = '';
					if($reg_data['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($reg_data['dateofbirth']));
					}
					$mem_doj = '';
					if($reg_data['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($reg_data['dateofjoin']));
					}
					
					if(strlen($reg_data['stdcode']) > 10)
					{	$std_code = substr($reg_data['stdcode'],0,9);	}
					else
					{	$std_code = $reg_data['stdcode'];	}
					
					if(strlen($reg_data['office']) > 20)
					{	$branch = substr($reg_data['office'],0,19);	}
					else
					{	$branch = $reg_data['office'];	}
					
					if(strlen($reg_data['address1']) > 30)
					{	$address1 = substr($reg_data['address1'],0,29);	}
					else
					{	$address1 = $reg_data['address1'];	}
					
					if(strlen($reg_data['address2']) > 30)
					{	$address2 = substr($reg_data['address2'],0,29);	}
					else
					{	$address2 = $reg_data['address2'];	}
					
					if(strlen($reg_data['address3']) > 30)
					{	$address3 = substr($reg_data['address3'],0,29);	}
					else
					{	$address3 = $reg_data['address3'];	}
					
					if(strlen($reg_data['address4']) > 30)
					{	$address4 = substr($reg_data['address4'],0,29);	}
					else
					{	$address4 = $reg_data['address4'];	}
					
					if(strlen($reg_data['district']) > 30)
					{	$district = substr($reg_data['district'],0,29);	}
					else
					{	$district = $reg_data['district'];	}
					
					if(strlen($reg_data['city']) > 30)
					{	$city = substr($reg_data['city'],0,29);	}
					else
					{	$city = $reg_data['city'];	}
					
					// code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					if(strlen($reg_data['address1_pr']) > 30)
					{	$address1_pr = substr($reg_data['address1_pr'],0,29);	}
					else
					{	$address1_pr = $reg_data['address1_pr'];	}
					
					if(strlen($reg_data['address2_pr']) > 30)
					{	$address2_pr = substr($reg_data['address2_pr'],0,29);	}
					else
					{	$address2_pr = $reg_data['address2_pr'];	}
					
					if(strlen($reg_data['address3_pr']) > 30)
					{	$address3_pr = substr($reg_data['address3_pr'],0,29);	}
					else
					{	$address3_pr = $reg_data['address3_pr'];	}
					
					if(strlen($reg_data['address4_pr']) > 30)
					{	$address4_pr = substr($reg_data['address4_pr'],0,29);	}
					else
					{	$address4_pr = $reg_data['address4_pr'];	}
					
					if(strlen($reg_data['district_pr']) > 30)
					{	$district_pr = substr($reg_data['district_pr'],0,29);	}
					else
					{	$district_pr = $reg_data['district_pr'];	}
					
					if(strlen($reg_data['city_pr']) > 30)
					{	$city_pr = substr($reg_data['city_pr'],0,29);	}
					else
					{	$city_pr = $reg_data['city_pr'];	}
					// eof code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					
					$optnletter = "Y";
					if($reg_data['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else
					{	$optnletter = $reg_data['optnletter'];	}
					
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['registrationtype'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname'].'|'.$reg_data['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$reg_data['pincode'].'|'.$reg_data['state'].'|'.$address1_pr.'|'.$address2_pr.'|'.$address3_pr.'|'.$address4_pr.'|'.$district_pr.'|'.$city_pr.'|'.$reg_data['pincode_pr'].'|'.$reg_data['state_pr'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$reg_data['specify_qualification'].'|'.$reg_data['associatedinstitute'].'|'.$branch.'|'.$reg_data['designation'].'|'.$mem_doj.'|'.$reg_data['email'].'|'.$std_code.'|'.$reg_data['office_phone'].'|'.$reg_data['mobile'].'|'.$reg_data['idproof'].'|'.$reg_data['idNo'].'|'.$transaction_no.'|'.$transaction_date.'|'.$transaction_amt.'|'.$transaction_no.'|'.$optnletter.'|||'.$photo.'|'.$signature.'|'.$idproofimg.'|'.$declarationimg.'|||||'.$reg_data['aadhar_card'].'|'.$reg_data['bank_emp_id']."\n";
					
					if($dir_flg)
					{
						// For photo images
						if($photo)
						{
							$image = "./uploads/photograph/".$reg_data['scannedphoto'];
							$max_width = "200";
							$max_height = "200";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
							
							$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						
						// For signature images
						if($signature)
						{
							$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
							$max_width = "140";
							$max_height = "100";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
							
							$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$sign_cnt++;
						}
						
						// For ID proof images
						if($idproofimg)
						{
							$image = "./uploads/idproof/".$reg_data['idproofphoto'];
							$max_width = "800";
							$max_height = "500";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
							
							$proof_to_add = $directory."/".$reg_data['idproofphoto'];
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							if(!$idproof_zip_flg)
							{
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else 
								$idproof_cnt++;
						}


						// For ID declaration images
						if($declarationimg)
						{
							$image = "./uploads/declaration/".$reg_data['declaration'];
							$max_width = "800";
							$max_height = "500";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['declaration']);
							
							$declaration_to_add = $directory."/".$reg_data['declaration'];
							$new_declaration = substr($declaration_to_add,strrpos($declaration_to_add,'/') + 1);
							$declaration_zip_flg = $zip->addFile($declaration_to_add,$new_declaration);
							if(!$declaration_zip_flg) 
							{
								fwrite($fp1, "**ERROR** - Declaration not added to zip  - ".$reg_data['declaration']." (".$reg_data['regnumber'].")\n");	
							}
							else 
								$declaration_cnt++;
						}
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg || $declaration_zip_flg)
						{
							$success['zip'] = "New Member Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating New Member Images Zip";
						}
					}
					
					$i++;
					$mem_cnt++;
					
					//fwrite($fp1, "\n");
					
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg)
					{
						$success['file'] = "New Member Details File Generated Successfully. ";
					}
					else
					{
						$error['file'] = "Error While Generating New Member Details File.";
					}
				}
				
				fwrite($fp1, "\n"."Total New Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$declaration_cnt."\n");
				
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
			
			fwrite($fp1, "\n"."************************* IIBF New Member Details Cron Execution End ".$end_time." **************************"."\n");
			fclose($fp1);
		}
	}
/* Added by Chaitali on 2021-07-09 */
public function CSCRPE_member_chaitali()
  {
    ini_set("memory_limit", "-1");
    
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
    
    $cron_file_dir = "./uploads/cscrpedata/";
    
    $result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
    $desc = json_encode($result);
    
    $this->log_model->cronlog("Vendor CSC Member Details Cron Execution Start", $desc);
    
    if(!file_exists($cron_file_dir.$current_date))
    {
      $parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
    }
    if(file_exists($cron_file_dir.$current_date))
    {
      $cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
      
      $file = "iibf_CSC_mem_details_".$current_date.".txt";
      $fp = fopen($cron_file_path.'/'.$file, 'w');
      
      $file1 = "logs_CSC_".$current_date.".txt";
      $fp1 = fopen($cron_file_path.'/'.$file1, 'a');
      
      fwrite($fp1, "\n"."************ Vendor CSC Member Details Cron End ".$start_time." *************"."\n");
      
      $yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ($current_date) ));
      
      $yesterday = '2021-06-19';
      $members = array(510384163,801563663,801528431,801674298,801764984,510458998,510142528,801564424,510530495,510507837,510162737,801564435,510511299,801813221,500212288,801813231,801813237,801813235,801813236,510452244,801813245,510506927,510241654,801739218,801813279,801813277,801813281,801813297,100026215,801813305,801813318,510290866,510202327,510530497,510511224,801813358,500158338,6205023,500043762,510267343,510521385,510340674,801450479,500149593,500175891,801813415,801813421,510202494,510270093,510014613,500133190,801813440,801813443,510338784,801813451,801811341,510232586,510164968,510053829,500041266,801426307,500129671,801813495,801813484,510273607,500186266,510474669,510131914,510449730,510524267,510530533,510361969,500204865,801813521,510314001,801739513,510250225,801813527,510383522,510290803,801813531,801813533,801813532,801813536,801678751,510147767,510502890,801813540,510188777,500142321,510228227,510244238,510386732,510298895,510054967,801813581,510218016,510530546,510408634,801813613,801739582,801813646,510472279,510186204,801679325,5363681,801470998,500007875,510374115,510501450,500153422,801813729,510409234,510333555,801764629,400063093,510315784,801813846,801162936,801813805,510162630,510521398,6217580,510422691,400133975,500094303,510131935,510229108,510232686,801563899,510509394,510474407,510511191,510436595,510339838,801813965,510238848,801813968,801813971,510278381,510055833,510017868,801764793,500176077,801813989,510287282,801813994,801813997,510140881,500041081,510216229,500038196,510429808,510523542,510461806,510329998,510187120,510450640,500177030,801764567,510429109,801814320,510435183,510506747,500041722,6769716,510134406,801814363,500203525,510256951,500007612,500083953,801814509,510338956,801814629,801453248,500008160,510507919,510452284,510450860,801814692,801814708,510375845,510479183,510439464,510364162,801422501,510251676,801814733,510452675,801814846,500162525,801814863,801814869,801814944,510453488,801392028,510337270,801511037,500143997,801175606,801815137,801815138,510270589,510352260,801815176,7606555,510524423,801815195,510524402,801336804,801815216,801815218,510230342,801343055,801343305,801345096,510258386,801815230,801815231,801553306,801815238,801815240,500039227,510057538,500052599,801429737,801815287,510150029,500209773,801553456,510464988,510473551,510004639,801815390,510414381,801673696,7294725,801815452,510427935,801470348,7434951,801677908,510332491,510524059,801344006,510395815,500113292,510387336,510292537,500215867,500094124,801815672,801342838,510519964,510362359,510257178,801553403,500013068,510476079,510409958,801816375,801344135,510483886,801816395,801739667,801816465,801739149,7492150,801816475,500124732,801816477,510026492,801816524,510048517,510507891,801553428,500096837,510312339,510339195,801816623,801816635,510381579,510255299,510185807,801816665,801816664,801347069,801554816,801553047,500187296,510179172,510338849,500040298,510123982,801817211,801817217,801817226,801427851,801817250,510437383,801817318,510285811,510331313,801177080,510494336,510506437,510078651,801817477,801817484,801817510,801817509,801817514,801817519,801817524,801817537,801817530,801817540,801428363,510162547,510330604,510212857,510209616,510480227,510404718,801817578,510401161,510507161,510431977,801343198,510383978,500051254,510312980,801817636,801457786,510376334,510334001,500024695,510268852,801817761,510333862,801817784,500146446,801817787,6313183,801817802,801817805,510431966,801817836,801817883,801817895,801673169,801817918,801456714,801817963,510269595,801817996,510309307,801818043,510318713,7611724,510411771,510016637,510530673,510398823,801818088,510261940,510471801,801818099,801818103,510390969,801818113,510365002,801818114,510337661,801588925,510438937,510177133,510497765,500133622,801818258,500149558,801235755,801818262,801764496,801236394,510282843,801497276,500123917,500150867,801818792,510503042,510190382,510398369,510520069,801819454,801819460,500051754,801819465,510169676,801819508,510229044,510300231,510530678,500196687,801109697,510140672,510482764,801819644,510174123,510407377,801819660,510527524,801819677,510512310,510097558,510047190,510482779,510333236,801168189,801820801,801820818,510359396,500038285,510434662,801820877,510449246,510152828,510158259,510498877,510154375,510249241,801820964,510525259,510398511,510086148,510365762,510338297,510442513,510407503,510342607,801821024,500135104,510508894,801821041,510227736,510122462,510282656,510308923,510121753,510334073,500118173,510508247,510530568,510530710,510209905,801821100,510360974,510001381,510370402,510302138,510530712,510162478,801821165,801821170,510349086,801821175,801821176,801821192,801821197,801821196,801793001,510136806,801821269,801206540,510501070,801186377,500123001,801821308,510437293,510291756,801821325,510142763,801821366,801764786,510512070,500052361,500052468,801821612,510530726,801821624,510238126,801821644,801821663,510121806,510458568,510357380,801821731,510390619,510404899,510457647,801121883,510228488,510096129,510119931,7221863,500038124,500149719,5241455,500172818,510279971,510021008,500205493,510272240,801553612,500009154,510451939,801813341,801813368,510457485,510336813,510410302,510412037,510392542,510327778,510429711,510472902,510482437,510350136,500053871,510394776,500212414,500001162,510348898,510079474,510061955,510507722,500053622,5388232,500091715,510026385,510062444,510305559,510483244,510088090,510493749,500204497,510397283,510530554,510333514,510136373,510390303,510464899,7599791,510472292,510129094,510022299,510408672,510425640,510280332,510435403,400035141,6627292,510161618,500109759,510327419,510243102,801814404,510429871,510517441,500127686,500077596,801814674,510369405,510111347,510123951,500175617,510342995,6989756,801648459,500075734,510291874,510117372,510439208,510382252,510322413,510279344,510249573,510437925,510473897,510530560,7239984,500098101,500083125,510019942,510363614,510152830,510002192,510239638,510310571,500158675,510094640,510464783,500094849,510348106,510244255,510482456,510074542,510355157,510183976,510224569,510309684,510430213,510530610,500056962,510299967,510368211,510469779,510327337,510518117,510090321,500054636,510017913,510013316,510530664,510030666,510390614,510142876,510030932,510222140,801765031,6828746,510173458,500131952,510485982,500098509,510208033,510017954,510384989,510069789,7183154,801819670,510327655,510310862,510063037,510508076,801820800,510354124,510193679,510230398,510452801,510062176,801821086,510143804,510037669,510325284,510211632,510150461,500071089,510286701,801821276,801821037,6555934,510356055,510031877,510004561,510375448,510359133,510284694,801821616,510433772,500089534,510128602,500201973,510286800,801278586,801821742,510319748,510442260,510449468,801249331,510196827,500026534,510525004,510099559,801813238,510306672,510357854,500049721,500131959,510481495,510461906,510093488,510279700,510031398,801813356,500018531,500014717,500169916,510244823,500077554,510375640,510177539,510310084,510379525,510511305,510335040,510145312,500187803,500069419,500151029,500139181,801813522,510232359,510255466,500008460,510178968,510437206,6102788,510277857,510176031,510431447,510140224,510061021,801813544,100015347,510332818,801680867,510254574,510510970,510106548,510451448,500188195,510137619,500011189,801176224,510465179,510132646,510271854,510422746,510070850,500065804,510481681,510163326,510210841,510402960,510132250,500099852,510247405,801813986,510530564,510282380,510142571,510179795,510387474,510139527,500087291,510203185,510024813,510266283,801814331,500186396,801814686,500114808,100022557,510070827,510072924,500029514,510107199,510028545,801764839,510511655,510512194,510185663,510386206,801814954,510388203,510248605,510144139,510511270,510359551,500095438,510264202,510398981,510479553,510152394,510468023,510304299,510242558,801815438,510447901,510221635,500017641,801174129,510353499,801815649,510124023,510355768,510444113,801567395,510293814,801554967,500150916,510274308,500215312,500189315,801173144,500142725,510043954,510437679,510135313,510446223,500212032,500112038,510463541,500171096,510157613,510055837,500041529,510206183,801621662,500179796,510333928,510237397,500129961,500004140,510015654,4865286,510465463,510092725,510515793,801259176,500077619,500189416,500107149,510106943,510382541,510375603,510035226,510476416,7553676,510137921,510389967,510317116,510360650,510383610,500192536,500213680,500072996,510069402,510360753,500100025,510096966,500040548,510220372,510507343,500061194,510294789,510128026,801742349,510530274,801253457,510454070,510332365,510147241,510463129,510130494,801819521,801201137,510030924,510039825,500131802,510411663,500131152,801532192,510354207,500050618,510136557,801820889,510060891,510138821,510086831,510101015,510061711,500006824,510155421,510140992,510325935,510223817,500123075,510223536,510126792,801821349,500013654,510349476,510169527,500002459,510167707,500101530,510326062);
      //$this->db->where_in('c.regnumber',$members);
      //$this->db->where('DATE(a.created_on) >=', '2020-05-29');
      //$this->db->where('DATE(a.created_on) <=', '2020-06-03');
      
      $exam_code_arry = array('1002','1010','1011','1012','1013','1014','1019','1020','2027');
      $select='c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,c.scannedphoto,c.scannedsignaturephoto,c.idproofphoto,c.image_path,c.reg_no, a.exam_code, ac.exam_date'; 
      $this->db->where_in('a.exam_code',$exam_code_arry);
	  $this->db->where('DATE(ac.exam_date) =', '2022-01-23');
	  $this->db->where_in('c.regnumber',$members);
      $this->db->join('member_registration c', 'a.regnumber = c.regnumber', 'LEFT');
      $this->db->join('admit_card_details ac', 'ac.mem_exam_id = a.id', 'LEFT');
      $new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
			'c.isactive' => '1',
			'c.isdeleted' => 0,
			'a.pay_status' => 1,
      		'ac.remark' => 1
      ), $select);
      echo "<br> Qry : ".$this->db->last_query();
      //,'DATE(a.created_on) >= ' => '2021-05-01'
      if(count($new_mem_reg))
      {
        $dirname = "CSC_regd_image_".$current_date;
        $directory = $cron_file_path.'/'.$dirname;
        if(file_exists($directory)){
          array_map('unlink', glob($directory."/*.*"));
          rmdir($directory);
          $dir_flg = mkdir($directory, 0700);
        }
        else{
          $dir_flg = mkdir($directory, 0700);
        }
        // Create a zip of images folder
        $zip = new ZipArchive;
        $zip->open($directory.'.zip', ZipArchive::CREATE);
        
        $i = 1;
        $mem_cnt = 0;
        $photo_cnt = 0;
        $sign_cnt = 0;
        $idproof_cnt = 0;
        
        foreach($new_mem_reg as $reg_data)
        {
          $data = '';					
          $image_path = $reg_data['image_path'];
          
          $photo = '';
          if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
          {
            $photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
          }
          elseif($image_path != '' && is_file("./uploads".$image_path."photo/p_".$reg_data['reg_no'].".jpg"))
          {
            $phtofile = "./uploads".$image_path."photo/p_".$reg_data['reg_no'].".jpg";
            $scannedphoto = "p_".$reg_data['regnumber'].".jpg";
            
            $photo 	= MEM_FILE_PATH.$scannedphoto;
          }
          else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '') //Check photo in kyc folder          
          {
            if($reg_data['reg_no'] != '' && file_exists("./uploads/photograph/k_p_".$reg_data['reg_no'].".jpg"))
            {
              $k_scannedphoto = "k_p_".$reg_data['reg_no'].".jpg";
              $photo = MEM_FILE_PATH.$k_scannedphoto;
            }
            else if($reg_data['regnumber'] != '' && file_exists("./uploads/photograph/k_p_".$reg_data['regnumber'].".jpg"))
            {
              $k_scannedphoto = "k_p_".$reg_data['regnumber'].".jpg";
              $photo = MEM_FILE_PATH.$k_scannedphoto;
            }
          }
          else
          {
            fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
          }
          
          /*$photo = '';
            
            if(is_file("./uploads/photograph/".$reg_data['scannedphoto'])){
            $photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
            }
            else{
            fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
          }*/
          
          $signature = '';
          if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
          {
            $signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
          }
          elseif($image_path != '' && is_file("./uploads".$image_path."signature/s_".$reg_data['reg_no'].".jpg"))
          {
            $signaturefile = "./uploads".$image_path."signature/s_".$reg_data['reg_no'].".jpg";
            $scannedsignature = "s_".$reg_data['regnumber'].".jpg";
            $signature 	= MEM_FILE_PATH.$scannedsignature;
          }
          else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '') //Check photo in kyc folder          
          {
            if($reg_data['reg_no'] != '' && file_exists("./uploads/scansignature/k_s_".$reg_data['reg_no'].".jpg"))
            {
              $k_scannedsignature = "k_s_".$reg_data['reg_no'].".jpg";
              $signature = MEM_FILE_PATH.$k_scannedsignature;
            }
            else if($reg_data['regnumber'] != '' && file_exists("./uploads/scansignature/k_s_".$reg_data['regnumber'].".jpg"))
            {
              $k_scannedsignature = "k_s_".$reg_data['regnumber'].".jpg";
              $signature = MEM_FILE_PATH.$k_scannedsignature;
            }
          }
          else
          {
            fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
          }
          /*$signature = '';
            if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto'])){
            $signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
            }
            else{
            fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
          }*/
          
          $idproofimg = '';
          
          if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
          {
            $idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
          }
          elseif($image_path != '' && is_file("./uploads".$image_path."idproof/pr_".$reg_data['reg_no'].".jpg"))
          {	
            $idprooffile = "./uploads".$image_path."idproof/pr_".$reg_data['reg_no'].".jpg";
            $scannedidproofimg = "pr_".$reg_data['regnumber'].".jpg";
            
            $idproofimg 	= MEM_FILE_PATH.$scannedidproofimg;
          }
          else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '') //Check photo in kyc folder          
          {
            if($reg_data['reg_no'] != '' && file_exists("./uploads/idproof/k_pr_".$reg_data['reg_no'].".jpg"))
            {
              $k_scannedidproofimg = "k_pr_".$reg_data['reg_no'].".jpg";
              $idproofimg = MEM_FILE_PATH.$k_scannedidproofimg;
            }
            else if($reg_data['regnumber'] != '' && file_exists("./uploads/idproof/k_pr_".$reg_data['regnumber'].".jpg"))
            {
              $k_scannedidproofimg = "k_pr_".$reg_data['regnumber'].".jpg";
              $idproofimg = MEM_FILE_PATH.$k_scannedidproofimg;
            }
          }
          else
          {
            fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
          }
          
          /*$idproofimg = '';
            if(is_file("./uploads/idproof/".$reg_data['idproofphoto'])){
            $idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
            }
            else{
            fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
          }*/
          
          // Member Number | Name Prefix | Firstname | Middlename | Lastname
          
          /***** ADDED BY SAGAR ON 15-07-2020 *******/
          $include_zip_flag = 0;
          if($reg_data['exam_code'] == '1002')
          {
            if($reg_data['exam_date'] != '2020-08-02' && $reg_data['exam_date'] != '2020-08-08' && $reg_data['exam_date'] != '2020-08-09') 
            { 
              $include_zip_flag = 1;
            }
          }
          else if($reg_data['exam_code'] != '1002')
          {
            $include_zip_flag = 1;
          } 
          
          if($include_zip_flag == 1)
          {
            $data .= ''.$reg_data['regnumber'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname']."|\n";
          } 
          
          if($dir_flg)
          {
            // For photo images
            if($photo)
            {
              $max_width = "200";
              $max_height = "200";
              
              if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
              {
                $image = "./uploads/photograph/".$reg_data['scannedphoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
                $photo_to_add = $directory."/".$reg_data['scannedphoto'];
              }
              elseif($image_path != '')
              {
                $image = $phtofile;
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$scannedphoto);
                $photo_to_add = $directory."/".$scannedphoto;
              }
              else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '')
              {
                if($reg_data['reg_no'] != '' && file_exists("./uploads/photograph/k_p_".$reg_data['reg_no'].".jpg"))
                {
                  $image = "./uploads/photograph/k_p_".$reg_data['reg_no'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_p_".$reg_data['reg_no'].".jpg");
                  $photo_to_add = $directory."/k_p_".$reg_data['reg_no'].".jpg";
                }
                else if($reg_data['regnumber'] != '' && file_exists("./uploads/photograph/k_p_".$reg_data['regnumber'].".jpg"))
                {
                  $image = "./uploads/photograph/k_p_".$reg_data['regnumber'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_p_".$reg_data['regnumber'].".jpg");
                  $photo_to_add = $directory."/k_p_".$reg_data['regnumber'].".jpg";
                }
              }
              else
              {
                $image = "./uploads/photograph/".$reg_data['scannedphoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
                $photo_to_add = $directory."/".$reg_data['scannedphoto'];
              }
              
              $new_photo = str_replace("k_","",substr($photo_to_add,strrpos($photo_to_add,'/') + 1));
              
              /***** ADDED BY SAGAR ON 15-07-2020 *******/
              if($include_zip_flag == 1)
              {
                $photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
                if(!$photo_zip_flg)
                {
                  fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
                } 
                else 
                { 
                	$photo_cnt++; 
                }
              }  						
            }
            
            // For signature images
            if($signature){
              
              $max_width = "140";
              $max_height = "100";
              if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
              {
                $image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
                
                $sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
              }
              elseif($image_path != '')
              {
                $image = $signaturefile;
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$scannedsignature);
                $sign_to_add = $directory."/".$scannedsignature;
              }
              else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '')
              {
                if($reg_data['reg_no'] != '' && file_exists("./uploads/scansignature/k_s_".$reg_data['reg_no'].".jpg"))
                {
                  $image = "./uploads/scansignature/k_s_".$reg_data['reg_no'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_s_".$reg_data['reg_no'].".jpg");
                  $sign_to_add = $directory."/k_s_".$reg_data['reg_no'].".jpg";
                }
                else if($reg_data['regnumber'] != '' && file_exists("./uploads/scansignature/k_s_".$reg_data['regnumber'].".jpg"))
                {
                  $image = "./uploads/scansignature/k_s_".$reg_data['regnumber'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_s_".$reg_data['regnumber'].".jpg");
                  $sign_to_add = $directory."/k_s_".$reg_data['regnumber'].".jpg";
                }
              }
              else
              {
                $image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
                
                $sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
              }
              
              $new_sign = str_replace("k_","",substr($sign_to_add,strrpos($sign_to_add,'/') + 1));
              
              /***** ADDED BY SAGAR ON 15-07-2020 *******/
              if($include_zip_flag == 1)
              {
                $sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
                if(!$sign_zip_flg)
                {
                  fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
                }
                else 
                {
                	$sign_cnt++;
                }
              }              
            }
            
            // For ID proof images
            if($idproofimg)
            {
              $max_width = "800";
              $max_height = "500";
              if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
              {
                $image = "./uploads/idproof/".$reg_data['idproofphoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
                
                $proof_to_add = $directory."/".$reg_data['idproofphoto'];
              }
              elseif($image_path != '')
              {
                $image = $idprooffile;
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$scannedidproofimg);
                
                $proof_to_add = $directory."/".$scannedidproofimg;
                
              }
              else if($reg_data['reg_no'] != '' || $reg_data['regnumber'] != '')
              {
                if($reg_data['reg_no'] != '' && file_exists("./uploads/idproof/k_pr_".$reg_data['reg_no'].".jpg"))
                {
                  $image = "./uploads/idproof/k_pr_".$reg_data['reg_no'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_pr_".$reg_data['reg_no'].".jpg");
                  $proof_to_add = $directory."/k_pr_".$reg_data['reg_no'].".jpg";
                }
                else if($reg_data['regnumber'] != '' && file_exists("./uploads/idproof/k_pr_".$reg_data['regnumber'].".jpg"))
                {
                  $image = "./uploads/idproof/k_pr_".$reg_data['regnumber'].".jpg";
                  $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                  imagejpeg($imgdata, $directory."/k_pr_".$reg_data['regnumber'].".jpg");
                  $proof_to_add = $directory."/k_pr_".$reg_data['regnumber'].".jpg";
                }
              }
              else
              {
                $image = "./uploads/idproof/".$reg_data['idproofphoto'];
                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
                imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
                
                $proof_to_add = $directory."/".$reg_data['idproofphoto'];
              }
              
              $new_proof = str_replace("k_","",substr($proof_to_add,strrpos($proof_to_add,'/') + 1));
              
              /***** ADDED BY SAGAR ON 15-07-2020 *******/
              if($include_zip_flag == 1)
              {
                $idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
                if(!$idproof_zip_flg)
                {
                  fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
                }
                else 
                { 
                  $idproof_cnt++;
                }
              }
            }
            
            if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg){
              $success['zip'] = "cscvendor Member Images Zip Generated Successfully";
              }else{
              $error['zip'] = "Error While Generating cscvendor Member Images Zip";
            }
          }
          $i++;
          if($include_zip_flag == 1) { $mem_cnt++; }
          $file_w_flg = fwrite($fp, $data);
          if($file_w_flg){
            $success['file'] = "CSC Member Details File Generated Successfully. ";
          }
          else{
            $error['file'] = "Error While Generating CSC Member Details File.";
          }
        }
        fwrite($fp1, "\n"."Total CSC Members Added = ".$mem_cnt."\n");
        fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
        fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
        fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
        //$file_w_flg = fwrite($fp, $data);
        $zip->close();
      }
      else{
        $success[] = "No data found for the date";
      }
      fclose($fp);
      // Cron End Logs
      $end_time = date("Y-m-d H:i:s");
      $result = array("success" =>$success,"error" =>$error,"Start Time"=>$start_time,"End Time"=>$end_time);
      $desc = json_encode($result);
      $this->log_model->cronlog("Vendor CSC Member Details Cron End", $desc);
      
      fwrite($fp1, "\n"."************ Vendor CSC Member Details Cron End ".$end_time." *************"."\n");
      fclose($fp1);
    }
  }
  
	/* Member Registration Cron */
	public function member_live_chaitali()
	{
		$this->load->model('Image_search_model');
		ini_set("memory_limit", "-1");
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
		$cron_file_dir = "./uploads/cronFilesCustom/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("IIBF New Member Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "iibf_new_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n************************* IIBF New Member Details Cron Execution Started - ".$start_time." ********************* \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday = '2020-11-07';
			//$this->db->where('DATE(a.createdon) >=', '2019-11-27');
			//$this->db->where('DATE(a.createdon) <=', '2019-11-27');
			$mem = array(510381293,510478885,510452090,510483403,801802615,510303700,510166759,510390842,510385941,500061412,510176669,510217130,510529708,500177825,510292997,510460673,510150620,510422849,510352940,510332736,510536130,510479729,510344750,510060058,510373758,510370812,510219658,510403940,801409279,500068431,510472297,510296855,510482243,510302460,510380026,510145292,510186738,510432431,510095032,510424684,510382538,500095592,510424713,510512666,510377331,510412660,500124115,510457575,500195063,510053454,510392819,510444243,510323440,510430404,510375436,510522204,510335565,500085469,510177608,510387336,510081294,510317144,510378392,510022000,510083790,510299539,510236088,510109165,6434821,510234589,510252892,510105306,510301530,500167169,510191550,510302291,510024618,510060561,500115044,500093153,510409540,500118482,500160397,510348159,500114812,510292014,510446727,510422059,510063713,6431893,510169570,510157245,7591748,500058393,500138812,510415861,400080568,510254070,510027963,510264413,510276273,510444375,510492871,500192018,510001233,510405883,510319027,510446140,510449663,510370673,500049190,100041089,500132638,7077545,801944712,510241997,510447788,510311099,500116425,510327399,500081593,500043293,500139963,500006768,510322343,500097479,510365139,510400980,510383362); 
			//$excode = array('991','526','527','101');
			//$this->db->where_in('a.excode', $excode);
			$this->db->where_in('a.regnumber', $mem);
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0));
			//echo $this->db->last_query();  //exit;
			//' DATE(createdon)'=>$yesterday,
			if(count($new_mem_reg))
			{
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
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					$gender = '';
					if($reg_data['gender'] == 'male')	{ $gender = 'M';}
					else if($reg_data['gender'] == 'female')	{ $gender = 'F';}
					
					$this->load->model('Image_search_model');
					
					$photo = $signature = $idproofimg = '';
					$photo_send = $signature_send = $idproofimg_send = '';
					$member_img_response = $this->Image_search_model->get_member_data($reg_data['regnumber']);
				    $photo = $member_img_response['scannedphoto'];
				    $idproofimg = $member_img_response['idproofphoto'];
				    $signature = $member_img_response['scannedsignaturephoto'];
					
					if($photo != "")
					{
						$explode_photo = explode("/",$photo);
						if(count($explode_photo) > 0)
						{
							//print_r($explode_photo);		
							//echo '<br> cnt : '.count($explode_photo);
							
							$photo_send = MEM_FILE_PATH.$explode_photo[(count($explode_photo)-1)];
						}
						//echo '<br>'.$photo; exit;
					}
					if($signature != "")
					{
						$explode_signature = explode("/",$signature);
						if(count($explode_signature) > 0)
						{
							
							$signature_send = MEM_FILE_PATH.$explode_signature[(count($explode_signature)-1)];
						}
						
					}
					if($idproofimg != "")
					{
						$explode_idproofimg = explode("/",$idproofimg);
						if(count($explode_idproofimg) > 0)
						{
							
							$idproofimg_send = MEM_FILE_PATH.$explode_idproofimg[(count($explode_idproofimg)-1)];
						}
						
					}
					
					/* if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
					{
						 $photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					} */
					/* $signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
					{
						$signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					} */
					/* $idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
					{
						$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					} */
					$qualification = '';
					switch($reg_data['qualification'])
					{
						// Values changes as per client req. : 22 July 2019
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;
						/* 			
							case "U"	: 	$qualification = 1;
										break;
							case "G"	: 	$qualification = 3;
										break;
							case "P"	: 	$qualification = 5;
										break;				
						*/		
					}
					$transaction_no = '';
					$transaction_date = '';
					$transaction_amt = '0';
					if($reg_data['registrationtype']!='NM')
					{
						if($reg_data['registrationtype'] == 'DB')
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_DB','pay_type'=>2,'member_regnumber'=>$reg_data['regnumber'],'status'=>1),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
							if(empty($trans_details))
						{   
							$this->db->select("DATE(a.updated_date) AS date, a.UTR_no AS transaction_no, a.amount");
							$this->db->join('bulk_member_payment_transaction b','a.id = b.ptid','LEFT');
							$this->db->join('member_exam c','b.memexamid = c.id','LEFT');
							$this->db->join('member_registration d','d.regnumber = c.regnumber','LEFT');
							$this->db->where("a.status = 1 AND  d.regnumber = '".$reg_data['regnumber']."'");
							//( DATE(a.updated_date) = '".$yesterday."') AND 
							$trans_details = $this->Master_model->getRecords('bulk_payment_transaction a');	
							//print_r($trans_details); die;
							//$trans_details = $this->db->get('bulk_payment_transaction a');
						}
						//print_r($trans_details); die;
						}
						else
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'iibfregn','status'=>1,'pay_type'=>1,'ref_id'=>$reg_data['regid']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');							
						}
					}
					else
					{
						$trans_details = $this->Master_model->getRecords('payment_transaction a',array('status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						//'pg_flag'=>'IIBF_EXAM_REG',
						/* For Bulk Members if Non-Member Application */
						//print_r($trans_details); die;
						if(empty($trans_details))
						{   
							$this->db->select("DATE(a.updated_date) AS date, a.UTR_no AS transaction_no, a.amount");
							$this->db->join('bulk_member_payment_transaction b','a.id = b.ptid','LEFT');
							$this->db->join('member_exam c','b.memexamid = c.id','LEFT');
							$this->db->join('member_registration d','d.regnumber = c.regnumber','LEFT');
							$this->db->where("a.status = 1 AND  d.regnumber = '".$reg_data['regnumber']."'");
							//( DATE(a.updated_date) = '".$yesterday."') AND 
							$trans_details = $this->Master_model->getRecords('bulk_payment_transaction a');	
							//print_r($trans_details); die;
							//$trans_details = $this->db->get('bulk_payment_transaction a');
						}
					}
					if(count($trans_details))
					{
						$transaction_no = $trans_details[0]['transaction_no'];
						$transaction_amt = $trans_details[0]['amount'];
						$date = $trans_details[0]['date'];
						if($date!='0000-00-00')
						{	$transaction_date = date('d-M-y',strtotime($date));	}
					}
					elseif($reg_data['excode'] == '191' || $reg_data['excode'] == '1910' || $reg_data['excode'] == '19100')
					{
						$get_date = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','regnumber'=>$reg_data['regnumber']),'DATE_FORMAT(createdon,"%Y-%m-%d") createdon');
						$date = $get_date[0]['createdon'];
						$transaction_date = date('d-M-y',strtotime($date));	
						$transaction_no = '000000000000';
						$transaction_amt = '0';	
					}
					$mem_dob = '';
					if($reg_data['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($reg_data['dateofbirth']));
					}
					$mem_doj = '';
					if($reg_data['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($reg_data['dateofjoin']));
					}
					if(strlen($reg_data['stdcode']) > 10)
					{	$std_code = substr($reg_data['stdcode'],0,9);	}
					else
					{	$std_code = $reg_data['stdcode'];	}
					if(strlen($reg_data['office']) > 20)
					{	$branch = substr($reg_data['office'],0,19);	}
					else
					{	$branch = $reg_data['office'];	}
					if(strlen($reg_data['address1']) > 30)
					{	$address1 = substr($reg_data['address1'],0,29);	}
					else
					{	$address1 = $reg_data['address1'];	}
					if(strlen($reg_data['address2']) > 30)
					{	$address2 = substr($reg_data['address2'],0,29);	}
					else
					{	$address2 = $reg_data['address2'];	}
					if(strlen($reg_data['address3']) > 30)
					{	$address3 = substr($reg_data['address3'],0,29);	}
					else
					{	$address3 = $reg_data['address3'];	}
					if(strlen($reg_data['address4']) > 30)
					{	$address4 = substr($reg_data['address4'],0,29);	}
					else
					{	$address4 = $reg_data['address4'];	}
					if(strlen($reg_data['district']) > 30)
					{	$district = substr($reg_data['district'],0,29);	}
					else
					{	$district = $reg_data['district'];	}
					if(strlen($reg_data['city']) > 30)
					{	$city = substr($reg_data['city'],0,29);	}
					else
					{	$city = $reg_data['city'];	}
					// code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					if(strlen($reg_data['address1_pr']) > 30)
					{	$address1_pr = substr($reg_data['address1_pr'],0,29);	}
					else
					{	$address1_pr = $reg_data['address1_pr'];	}
					if(strlen($reg_data['address2_pr']) > 30)
					{	$address2_pr = substr($reg_data['address2_pr'],0,29);	}
					else
					{	$address2_pr = $reg_data['address2_pr'];	}
					if(strlen($reg_data['address3_pr']) > 30)
					{	$address3_pr = substr($reg_data['address3_pr'],0,29);	}
					else
					{	$address3_pr = $reg_data['address3_pr'];	}
					if(strlen($reg_data['address4_pr']) > 30)
					{	$address4_pr = substr($reg_data['address4_pr'],0,29);	}
					else
					{	$address4_pr = $reg_data['address4_pr'];	}
					if(strlen($reg_data['district_pr']) > 30)
					{	$district_pr = substr($reg_data['district_pr'],0,29);	}
					else
					{	$district_pr = $reg_data['district_pr'];	}
					if(strlen($reg_data['city_pr']) > 30)
					{	$city_pr = substr($reg_data['city_pr'],0,29);	}
					else
					{	$city_pr = $reg_data['city_pr'];	}
					// eof code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					$optnletter = "Y";
					if($reg_data['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else
					{	$optnletter = $reg_data['optnletter'];	}
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['registrationtype'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname'].'|'.$reg_data['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$reg_data['pincode'].'|'.$reg_data['state'].'|'.$address1_pr.'|'.$address2_pr.'|'.$address3_pr.'|'.$address4_pr.'|'.$district_pr.'|'.$city_pr.'|'.$reg_data['pincode_pr'].'|'.$reg_data['state_pr'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$reg_data['specify_qualification'].'|'.$reg_data['associatedinstitute'].'|'.$branch.'|'.$reg_data['designation'].'|'.$mem_doj.'|'.$reg_data['email'].'|'.$std_code.'|'.$reg_data['office_phone'].'|'.$reg_data['mobile'].'|'.$reg_data['idproof'].'|'.$reg_data['idNo'].'|'.$transaction_no.'|'.$transaction_date.'|'.$transaction_amt.'|'.$transaction_no.'|'.$optnletter.'|||'.$photo_send.'|'.$signature_send.'|'.$idproofimg_send.'|||||'.$reg_data['aadhar_card'].'|'.$reg_data['bank_emp_id']."\n";
					if($dir_flg)
					{
						// For photo images
						if($photo_send)
						{
							//$image = "./uploads/photograph/".$reg_data['scannedphoto'];
							$image = "./".$phtofile;
							$max_width = "200";
							$max_height = "200";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							//imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
							imagejpeg($imgdata,$photo);
							//$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							$photo_to_add = $photo;
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						// For signature images
						if($signature_send)
						{
							//$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
							$image = "./".$signaturefile;
							$max_width = "140";
							$max_height = "100";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							//imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
							imagejpeg($imgdata,$signature);
							//$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							$sign_to_add = $signature;
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$sign_cnt++;
						}
						// For ID proof images
						if($idproofimg_send)
						{
							//$image = "./uploads/idproof/".$reg_data['idproofphoto'];
							$image = "./".$idproofimg;
							$max_width = "800";
							$max_height = "500";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata,$idproofimg);
							//$proof_to_add = $directory."/".$reg_data['idproofphoto'];
							$proof_to_add = $idproofimg;
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							if(!$idproof_zip_flg)
							{
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else 
								$idproof_cnt++;
						}
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
					$mem_cnt++;
					//fwrite($fp1, "\n");
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg)
					{
						$success['file'] = "New Member Details File Generated Successfully. ";
					}
					else
					{
						$error['file'] = "Error While Generating New Member Details File.";
					}
				}
				fwrite($fp1, "\n"."Total New Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
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
			fwrite($fp1, "\n"."************************* IIBF New Member Details Cron Execution End ".$end_time." **************************"."\n");
			fclose($fp1);
		}
	}
	/* Member Registration Cron */
	public function member()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$photo_zip_flg = 0;
		$sign_zip_flg = 0;
		$idproof_zip_flg = 0;
		$vis_imp_cert_img_zip_flg = 0;
		$orth_han_cert_img_zip_flg = 0;
		$cer_palsy_cert_img_zip_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");
		//$current_date = '20200105';
		$cron_file_dir = "./uploads/cronFilesCustom/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("New Member Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "iibf_new_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** New Member Details Cron Start - ".$start_time." ********** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2019-11-21';
			$member = array(510472006);
			$excode = array('526','527','991');
			$this->db->where_not_in('a.excode', $excode);
			$this->db->where_in('regnumber', $member);
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0));	
			//' DATE(createdon)'=>$yesterday,
			echo $this->db->last_query();
			if(count($new_mem_reg))
			{
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
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				$vis_imp_cert_img_cnt = $orth_han_cert_img_cnt = $cer_palsy_cert_img_cnt = 0;
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					$gender = '';
					if($reg_data['gender'] == 'male')	{ $gender = 'M';}
					else if($reg_data['gender'] == 'female')	{ $gender = 'F';}
					$photo = '';
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
					{
						$photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}
					$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
					{
						$signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}
					$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
					{
						$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
					$qualification = '';
					switch($reg_data['qualification'])
					{
						// Values changes as per client req. : 22 July 2019
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;
						/* 			
							case "U"	: 	$qualification = 1;
										break;
							case "G"	: 	$qualification = 3;
										break;
							case "P"	: 	$qualification = 5;
										break;				
						*/					
					}
					$transaction_no = '';
					$transaction_date = '';
					$transaction_amt = '0';
					if($reg_data['registrationtype']!='NM')
					{
						if($reg_data['registrationtype'] == 'DB')
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_DB','pay_type'=>2,'member_regnumber'=>$reg_data['regnumber'],'status'=>1),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						}
						else
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'iibfregn','status'=>1,'pay_type'=>1,'ref_id'=>$reg_data['regid']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						}
					}
					else
					{
						$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_REG','status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						/* For Bulk Members if Non-Member Application */
						if(empty($trans_details))
						{
							$this->db->select("DATE(a.updated_date) AS date, a.UTR_no AS transaction_no, a.amount");
							$this->db->join('bulk_member_payment_transaction b','a.id = b.ptid','LEFT');
							$this->db->join('member_exam c','b.memexamid = c.id','LEFT');
							$this->db->join('member_registration d','d.regnumber = c.regnumber','LEFT');
							$this->db->where("( DATE(a.updated_date) = '".$yesterday."') AND a.status = 1 AND d.regnumber = '".$reg_data['regnumber']."'");
							$trans_details = $this->Master_model->getRecords('bulk_payment_transaction a');	
							//$trans_details = $this->db->get('bulk_payment_transaction a');
						}
						/*if(empty($trans_details))
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_ELR','status'=>1,'pay_type'=>18,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						}
						if(empty($trans_details))
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EX_ELR','status'=>1,'pay_type'=>18,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						}*/
					}
					if(count($trans_details))
					{
						$transaction_no = $trans_details[0]['transaction_no'];
						$transaction_amt = $trans_details[0]['amount'];
						$date = $trans_details[0]['date'];
						if($date!='0000-00-00')
						{	$transaction_date = date('d-M-y',strtotime($date));	}
					}
					elseif($reg_data['excode'] == '191' || $reg_data['excode'] == '1910' || $reg_data['excode'] == '19100')
					{
						$get_date = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','regnumber'=>$reg_data['regnumber']),'DATE_FORMAT(createdon,"%Y-%m-%d") createdon');
						$date = $get_date[0]['createdon'];
						$transaction_date = date('d-M-y',strtotime($date));	
						$transaction_no = '000000000000';
						$transaction_amt = '0';	
					}
					$mem_dob = '';
					if($reg_data['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($reg_data['dateofbirth']));
					}
					$mem_doj = '';
					if($reg_data['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($reg_data['dateofjoin']));
					}
					if(strlen($reg_data['stdcode']) > 10)
					{	$std_code = substr($reg_data['stdcode'],0,9);	}
					else
					{	$std_code = $reg_data['stdcode'];	}
					if(strlen($reg_data['office']) > 20)
					{	$branch = substr($reg_data['office'],0,19);	}
					else
					{	$branch = $reg_data['office'];	}
					if(strlen($reg_data['address1']) > 30)
					{	$address1 = substr($reg_data['address1'],0,29);	}
					else
					{	$address1 = $reg_data['address1'];	}
					if(strlen($reg_data['address2']) > 30)
					{	$address2 = substr($reg_data['address2'],0,29);	}
					else
					{	$address2 = $reg_data['address2'];	}
					if(strlen($reg_data['address3']) > 30)
					{	$address3 = substr($reg_data['address3'],0,29);	}
					else
					{	$address3 = $reg_data['address3'];	}
					if(strlen($reg_data['address4']) > 30)
					{	$address4 = substr($reg_data['address4'],0,29);	}
					else
					{	$address4 = $reg_data['address4'];	}
					if(strlen($reg_data['district']) > 30)
					{	$district = substr($reg_data['district'],0,29);	}
					else
					{	$district = $reg_data['district'];	}
					if(strlen($reg_data['city']) > 30)
					{	$city = substr($reg_data['city'],0,29);	}
					else
					{	$city = $reg_data['city'];	}
					// code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					if(strlen($reg_data['address1_pr']) > 30)
					{	$address1_pr = substr($reg_data['address1_pr'],0,29);	}
					else
					{	$address1_pr = $reg_data['address1_pr'];	}
					if(strlen($reg_data['address2_pr']) > 30)
					{	$address2_pr = substr($reg_data['address2_pr'],0,29);	}
					else
					{	$address2_pr = $reg_data['address2_pr'];	}
					if(strlen($reg_data['address3_pr']) > 30)
					{	$address3_pr = substr($reg_data['address3_pr'],0,29);	}
					else
					{	$address3_pr = $reg_data['address3_pr'];	}
					if(strlen($reg_data['address4_pr']) > 30)
					{	$address4_pr = substr($reg_data['address4_pr'],0,29);	}
					else
					{	$address4_pr = $reg_data['address4_pr'];	}
					if(strlen($reg_data['district_pr']) > 30)
					{	$district_pr = substr($reg_data['district_pr'],0,29);	}
					else
					{	$district_pr = $reg_data['district_pr'];	}
					if(strlen($reg_data['city_pr']) > 30)
					{	$city_pr = substr($reg_data['city_pr'],0,29);	}
					else
					{	$city_pr = $reg_data['city_pr'];	}
					// eof code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					$optnletter = "Y";
					if($reg_data['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else
					{	$optnletter = $reg_data['optnletter'];	}
					/* Benchmark Code Start */
					$benchmark_disability = $reg_data['benchmark_disability'];
					$visually_impaired = $reg_data['visually_impaired'];
					$vis_imp_cert_img = '';
					$orthopedically_handicapped = $reg_data['orthopedically_handicapped'];
					$orth_han_cert_img = '';
					$cerebral_palsy = $reg_data['cerebral_palsy'];
					$cer_palsy_cert_img = '';
					if($benchmark_disability == 'Y')
					{
						$vis_imp_cert_img = '';
						if($visually_impaired == 'Y')
						{
							if(is_file("./uploads/disability/".$reg_data['vis_imp_cert_img'])){
							$vis_imp_cert_img 	= MEM_FILE_PATH.$reg_data['vis_imp_cert_img'];
							}else{
								fwrite($fp1, "**ERROR** - Visually impaired certificate does not exist  - ".$reg_data['vis_imp_cert_img']." (".$reg_data['regnumber'].")\n");	
							}
						}
						$orth_han_cert_img = '';
						if($orthopedically_handicapped == 'Y')
						{
							if(is_file("./uploads/disability/".$reg_data['orth_han_cert_img'])){
							$orth_han_cert_img 	= MEM_FILE_PATH.$reg_data['orth_han_cert_img'];
							}else{
								fwrite($fp1, "**ERROR** - Orthopedically handicapped certificate does not exist  - ".$reg_data['orth_han_cert_img']." (".$reg_data['regnumber'].")\n");	
							}
						}
						$cer_palsy_cert_img = '';
						if($cerebral_palsy == 'Y')
						{
							if(is_file("./uploads/disability/".$reg_data['cer_palsy_cert_img'])){
							$cer_palsy_cert_img 	= MEM_FILE_PATH.$reg_data['cer_palsy_cert_img'];
							}else{
								fwrite($fp1, "**ERROR** - Cerebral palsy certificate does not exist  - ".$reg_data['cer_palsy_cert_img']." (".$reg_data['regnumber'].")\n");	
							}
						}
					}
					/* Benchmark Code End */
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['registrationtype'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname'].'|'.$reg_data['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$reg_data['pincode'].'|'.$reg_data['state'].'|'.$address1_pr.'|'.$address2_pr.'|'.$address3_pr.'|'.$address4_pr.'|'.$district_pr.'|'.$city_pr.'|'.$reg_data['pincode_pr'].'|'.$reg_data['state_pr'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$reg_data['specify_qualification'].'|'.$reg_data['associatedinstitute'].'|'.$branch.'|'.$reg_data['designation'].'|'.$mem_doj.'|'.$reg_data['email'].'|'.$std_code.'|'.$reg_data['office_phone'].'|'.$reg_data['mobile'].'|'.$reg_data['idproof'].'|'.$reg_data['idNo'].'|'.$transaction_no.'|'.$transaction_date.'|'.$transaction_amt.'|'.$transaction_no.'|'.$optnletter.'|||'.$photo.'|'.$signature.'|'.$idproofimg.'|||||'.$reg_data['aadhar_card'].'|'.$reg_data['bank_emp_id'].'|'.$benchmark_disability.'|'.$visually_impaired.'|'.$vis_imp_cert_img.'|'.$orthopedically_handicapped.'|'.$orth_han_cert_img.'|'.$cerebral_palsy.'|'.$cer_palsy_cert_img."\n";
					if($dir_flg)
					{
						/* Benchmark Code Start */
						// For Visually impaired certificate images
						if($vis_imp_cert_img)
						{
							$image = "./uploads/disability/".$reg_data['vis_imp_cert_img'];
							$max_width = "200";
							$max_height = "200";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['vis_imp_cert_img']);
							$vis_imp_cert_img_to_add = $directory."/".$reg_data['vis_imp_cert_img'];
							$new_vis_imp_cert_img = substr($vis_imp_cert_img_to_add,strrpos($vis_imp_cert_img_to_add,'/') + 1);
							$vis_imp_cert_img_zip_flg = $zip->addFile($vis_imp_cert_img_to_add,$new_vis_imp_cert_img);
							if(!$vis_imp_cert_img_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Visually impaired certificate not added to zip  - ".$reg_data['vis_imp_cert_img']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$vis_imp_cert_img_cnt++;
						}
						// For Orthopedically handicapped certificate images
						if($orth_han_cert_img)
						{
							$image = "./uploads/disability/".$reg_data['orth_han_cert_img'];
							$max_width = "200";
							$max_height = "200";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['orth_han_cert_img']);
							$orth_han_cert_img_to_add = $directory."/".$reg_data['orth_han_cert_img'];
							$new_orth_han_cert_img = substr($orth_han_cert_img_to_add,strrpos($orth_han_cert_img_to_add,'/') + 1);
							$orth_han_cert_img_zip_flg = $zip->addFile($orth_han_cert_img_to_add,$new_orth_han_cert_img);
							if(!$orth_han_cert_img_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Orthopedically handicapped certificate not added to zip  - ".$reg_data['orth_han_cert_img']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$orth_han_cert_img_cnt++;
						}
						// For Cerebral palsy certificate images
						if($cer_palsy_cert_img)
						{
							$image = "./uploads/disability/".$reg_data['cer_palsy_cert_img'];
							$max_width = "200";
							$max_height = "200";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['cer_palsy_cert_img']);
							$cer_palsy_cert_img_to_add = $directory."/".$reg_data['cer_palsy_cert_img'];
							$new_cer_palsy_cert_img = substr($cer_palsy_cert_img_to_add,strrpos($cer_palsy_cert_img_to_add,'/') + 1);
							$cer_palsy_cert_img_zip_flg = $zip->addFile($cer_palsy_cert_img_to_add,$new_cer_palsy_cert_img);
							if(!$cer_palsy_cert_img_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Cerebral palsy certificate not added to zip  - ".$reg_data['cer_palsy_cert_img']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$cer_palsy_cert_img_cnt++;
						}
						/* Benchmark Code End */
						// For photo images
						if($photo)
						{
							$image = "./uploads/photograph/".$reg_data['scannedphoto'];
							$max_width = "200";
							$max_height = "200";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
							$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						// For signature images
						if($signature)
						{
							$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
							$max_width = "140";
							$max_height = "100";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
							$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$sign_cnt++;
						}
						// For ID proof images
						if($idproofimg)
						{
							$image = "./uploads/idproof/".$reg_data['idproofphoto'];
							$max_width = "800";
							$max_height = "500";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
							$proof_to_add = $directory."/".$reg_data['idproofphoto'];
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							if(!$idproof_zip_flg)
							{
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else 
								$idproof_cnt++;
						}
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
					$mem_cnt++;
					//fwrite($fp1, "\n");
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg)
					{
						$success['file'] = "New Member Details File Generated Successfully. ";
					}
					else
					{
						$error['file'] = "Error While Generating New Member Details File.";
					}
				}
				fwrite($fp1, "\n"."Total New Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				fwrite($fp1, "\n"."Total Visually impaired cert Added = ".$vis_imp_cert_img_cnt."\n");
				fwrite($fp1, "\n"."Total Orthopedically handicapped cert Added = ".$orth_han_cert_img_cnt."\n");
				fwrite($fp1, "\n"."Total Cerebral palsy cert Added = ".$cer_palsy_cert_img_cnt."\n");
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
			$this->log_model->cronlog("New Member Details Cron End", $desc);
			fwrite($fp1, "\n"."********** New Member Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	/* CSC Member Registration Cron */
	public function member_csc()
	{
		ini_set("memory_limit", "-1");
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
		//$current_date = '20200105';
		$cron_file_dir = "./uploads/cronFilesCustom/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("CSC Member Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "iibf_new_mem_csc_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** CSC Member Details Cron Start - ".$start_time." ********** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday = '2021-07-07';
			$member = array(801835306,801835317,801836905,801835876,801837157,801837416,801836968);
			$excode = array('991','1015','101');
			$this->db->where_in('a.excode', $excode);
			$this->db->join('payment_transaction b','b.member_regnumber = a.regnumber','LEFT');
			
			$this->db->where_in('a.regnumber', $member);
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('bankcode' => 'csc','isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0));
			//' DATE(createdon)'=>$yesterday,'bankcode' => 'csc'
			echo  $this->db->last_query(); 
			//die;
			if(count($new_mem_reg))
			{
				$dirname = "regd_image_csc_".$current_date;
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
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					$gender = '';
					if($reg_data['gender'] == 'male')	{ $gender = 'M';}
					else if($reg_data['gender'] == 'female')	{ $gender = 'F';}
					/*
					$photo 		= MEM_FILE_PATH.$reg_data['scannedphoto'];
					$signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];*/
					$photo = '';
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
					{
						$photo 	= CSC_MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}
					$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
					{
						$signature 	= CSC_MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}
					$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
					{
						$idproofimg = CSC_MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
					$qualification = '';
					switch($reg_data['qualification'])
					{
						// Values changes as per client req. : 22 July 2019
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;
						/* 			
							case "U"	: 	$qualification = 1;
										break;
							case "G"	: 	$qualification = 3;
										break;
							case "P"	: 	$qualification = 5;
										break;				
						*/				
					}
					$transaction_no = '';
					$transaction_date = '';
					$transaction_amt = '0';
					// CSC_EXM_NM
					// CSC_NM_REG
					if($reg_data['registrationtype']!='NM')
					{
						if($reg_data['registrationtype'] == 'DB')
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_DB','pay_type'=>2,'member_regnumber'=>$reg_data['regnumber'],'status'=>1),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						}
						else
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'iibfregn','status'=>1,'pay_type'=>1,'ref_id'=>$reg_data['regid']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						}
					}
					else
					{
						$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'CSC_NM_REG','status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');		
					}
					if(count($trans_details))
					{
						$transaction_no = $trans_details[0]['transaction_no'];
						$transaction_amt = $trans_details[0]['amount'];
						$date = $trans_details[0]['date'];
						if($date!='0000-00-00')
						{	$transaction_date = date('d-M-y',strtotime($date));	}
					}
					$mem_dob = '';
					if($reg_data['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($reg_data['dateofbirth']));
					}
					$mem_doj = '';
					if($reg_data['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($reg_data['dateofjoin']));
					}
					if(strlen($reg_data['stdcode']) > 10)
					{	$std_code = substr($reg_data['stdcode'],0,9);	}
					else
					{	$std_code = $reg_data['stdcode'];	}
					if(strlen($reg_data['office']) > 20)
					{	$branch = substr($reg_data['office'],0,19);	}
					else
					{	$branch = $reg_data['office'];	}
					if(strlen($reg_data['address1']) > 30)
					{	$address1 = substr($reg_data['address1'],0,29);	}
					else
					{	$address1 = $reg_data['address1'];	}
					if(strlen($reg_data['address2']) > 30)
					{	$address2 = substr($reg_data['address2'],0,29);	}
					else
					{	$address2 = $reg_data['address2'];	}
					if(strlen($reg_data['address3']) > 30)
					{	$address3 = substr($reg_data['address3'],0,29);	}
					else
					{	$address3 = $reg_data['address3'];	}
					if(strlen($reg_data['address4']) > 30)
					{	$address4 = substr($reg_data['address4'],0,29);	}
					else
					{	$address4 = $reg_data['address4'];	}
					if(strlen($reg_data['district']) > 30)
					{	$district = substr($reg_data['district'],0,29);	}
					else
					{	$district = $reg_data['district'];	}
					if(strlen($reg_data['city']) > 30)
					{	$city = substr($reg_data['city'],0,29);	}
					else
					{	$city = $reg_data['city'];	}
					// code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					if(strlen($reg_data['address1_pr']) > 30)
					{	$address1_pr = substr($reg_data['address1_pr'],0,29);	}
					else
					{	$address1_pr = $reg_data['address1_pr'];	}
					if(strlen($reg_data['address2_pr']) > 30)
					{	$address2_pr = substr($reg_data['address2_pr'],0,29);	}
					else
					{	$address2_pr = $reg_data['address2_pr'];	}
					if(strlen($reg_data['address3_pr']) > 30)
					{	$address3_pr = substr($reg_data['address3_pr'],0,29);	}
					else
					{	$address3_pr = $reg_data['address3_pr'];	}
					if(strlen($reg_data['address4_pr']) > 30)
					{	$address4_pr = substr($reg_data['address4_pr'],0,29);	}
					else
					{	$address4_pr = $reg_data['address4_pr'];	}
					if(strlen($reg_data['district_pr']) > 30)
					{	$district_pr = substr($reg_data['district_pr'],0,29);	}
					else
					{	$district_pr = $reg_data['district_pr'];	}
					if(strlen($reg_data['city_pr']) > 30)
					{	$city_pr = substr($reg_data['city_pr'],0,29);	}
					else
					{	$city_pr = $reg_data['city_pr'];	}
					// eof code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					$optnletter = "Y";
					if($reg_data['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else
					{	$optnletter = $reg_data['optnletter'];	}
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['registrationtype'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname'].'|'.$reg_data['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$reg_data['pincode'].'|'.$reg_data['state'].'|'.$address1_pr.'|'.$address2_pr.'|'.$address3_pr.'|'.$address4_pr.'|'.$district_pr.'|'.$city_pr.'|'.$reg_data['pincode_pr'].'|'.$reg_data['state_pr'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$reg_data['specify_qualification'].'|'.$reg_data['associatedinstitute'].'|'.$branch.'|'.$reg_data['designation'].'|'.$mem_doj.'|'.$reg_data['email'].'|'.$std_code.'|'.$reg_data['office_phone'].'|'.$reg_data['mobile'].'|'.$reg_data['idproof'].'|'.$reg_data['idNo'].'|'.$transaction_no.'|'.$transaction_date.'|'.$transaction_amt.'|'.$transaction_no.'|'.$optnletter.'|||'.$photo.'|'.$signature.'|'.$idproofimg.'|||||'.$reg_data['aadhar_card'].'|'.$reg_data['bank_emp_id']."\n";
					if($dir_flg)
					{
						// For photo images
						if($photo)
						{
							$image = "./uploads/photograph/".$reg_data['scannedphoto'];
							$max_width = "200";
							$max_height = "200";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
							$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						// For signature images
						if($signature)
						{
							$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
							$max_width = "140";
							$max_height = "100";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
							$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$sign_cnt++;
						}
						// For ID proof images
						if($idproofimg)
						{
							$image = "./uploads/idproof/".$reg_data['idproofphoto'];
							$max_width = "800";
							$max_height = "500";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
							$proof_to_add = $directory."/".$reg_data['idproofphoto'];
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							if(!$idproof_zip_flg)
							{
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else 
								$idproof_cnt++;
						}
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
					$mem_cnt++;
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg)
					{
						$success['file'] = "New Member Details File Generated Successfully. ";
					}
					else
					{
						$error['file'] = "Error While Generating New Member Details File.";
					}
				}
				fwrite($fp1, "\n"."Total New Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
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
			$this->log_model->cronlog("CSC Member Details Cron End", $desc);
			fwrite($fp1, "\n"."********** CSC Member Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	/* Member Registration Digital ELearning Cron */
	public function member_digital_eLearning()
	{
		ini_set("memory_limit", "-1");
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
		$cron_file_dir = "./uploads/cronFilesCustom/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("Digital ELearning Member Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "iibf_digital_eLearning_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_digital_eLearning_mem_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n***** IIBF Digital ELearning Member Details Cron Start - ".$start_time." ***** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday = '2020-11-07';
			$mem = array(801656190);
			$excode = array('526','527');
			$this->db->where_in('a.regnumber',$mem);
			$this->db->where_in('a.excode', $excode);
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0));	
			//' DATE(createdon)'=>$yesterday,
			if(count($new_mem_reg))
			{
				$dirname = "mem_digital_eLearning_image_".$current_date;
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
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					$gender = '';
					if($reg_data['gender'] == 'male')	{ $gender = 'M';}
					else if($reg_data['gender'] == 'female')	{ $gender = 'F';}
					$photo = '';
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
					{
						$photo 	= DIGITAL_EL_MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}
					$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
					{
						$signature 	= DIGITAL_EL_MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}
					$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
					{
						$idproofimg = DIGITAL_EL_MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
					$qualification = '';
					switch($reg_data['qualification'])
					{
						// Values changes as per client req. : 22 July 2019
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;
						/* 			
							case "U"	: 	$qualification = 1;
										break;
							case "G"	: 	$qualification = 3;
										break;
							case "P"	: 	$qualification = 5;
										break;				
						*/		
					}
					$transaction_no = '';
					$transaction_date = '';
					$transaction_amt = '0';
					/*if($reg_data['registrationtype'] == 'NM')
					{*/
						$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_ELR','pay_type'=>18,'member_regnumber'=>$reg_data['regnumber'],'status'=>1),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
					/*}*/
					if(count($trans_details))
					{
						$transaction_no = $trans_details[0]['transaction_no'];
						$transaction_amt = $trans_details[0]['amount'];
						$date = $trans_details[0]['date'];
						if($date!='0000-00-00')
						{	$transaction_date = date('d-M-y',strtotime($date));	}
					}
					$mem_dob = '';
					if($reg_data['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($reg_data['dateofbirth']));
					}
					$mem_doj = '';
					if($reg_data['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($reg_data['dateofjoin']));
					}
					if(strlen($reg_data['stdcode']) > 10)
					{	$std_code = substr($reg_data['stdcode'],0,9);	}
					else
					{	$std_code = $reg_data['stdcode'];	}
					if(strlen($reg_data['office']) > 20)
					{	$branch = substr($reg_data['office'],0,19);	}
					else
					{	$branch = $reg_data['office'];	}
					if(strlen($reg_data['address1']) > 30)
					{	$address1 = substr($reg_data['address1'],0,29);	}
					else
					{	$address1 = $reg_data['address1'];	}
					if(strlen($reg_data['address2']) > 30)
					{	$address2 = substr($reg_data['address2'],0,29);	}
					else
					{	$address2 = $reg_data['address2'];	}
					if(strlen($reg_data['address3']) > 30)
					{	$address3 = substr($reg_data['address3'],0,29);	}
					else
					{	$address3 = $reg_data['address3'];	}
					if(strlen($reg_data['address4']) > 30)
					{	$address4 = substr($reg_data['address4'],0,29);	}
					else
					{	$address4 = $reg_data['address4'];	}
					if(strlen($reg_data['district']) > 30)
					{	$district = substr($reg_data['district'],0,29);	}
					else
					{	$district = $reg_data['district'];	}
					if(strlen($reg_data['city']) > 30)
					{	$city = substr($reg_data['city'],0,29);	}
					else
					{	$city = $reg_data['city'];	}
					// code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					if(strlen($reg_data['address1_pr']) > 30)
					{	$address1_pr = substr($reg_data['address1_pr'],0,29);	}
					else
					{	$address1_pr = $reg_data['address1_pr'];	}
					if(strlen($reg_data['address2_pr']) > 30)
					{	$address2_pr = substr($reg_data['address2_pr'],0,29);	}
					else
					{	$address2_pr = $reg_data['address2_pr'];	}
					if(strlen($reg_data['address3_pr']) > 30)
					{	$address3_pr = substr($reg_data['address3_pr'],0,29);	}
					else
					{	$address3_pr = $reg_data['address3_pr'];	}
					if(strlen($reg_data['address4_pr']) > 30)
					{	$address4_pr = substr($reg_data['address4_pr'],0,29);	}
					else
					{	$address4_pr = $reg_data['address4_pr'];	}
					if(strlen($reg_data['district_pr']) > 30)
					{	$district_pr = substr($reg_data['district_pr'],0,29);	}
					else
					{	$district_pr = $reg_data['district_pr'];	}
					if(strlen($reg_data['city_pr']) > 30)
					{	$city_pr = substr($reg_data['city_pr'],0,29);	}
					else
					{	$city_pr = $reg_data['city_pr'];	}
					// eof code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					$optnletter = "Y";
					if($reg_data['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else
					{	$optnletter = $reg_data['optnletter'];	}
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['registrationtype'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname'].'|'.$reg_data['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$reg_data['pincode'].'|'.$reg_data['state'].'|'.$address1_pr.'|'.$address2_pr.'|'.$address3_pr.'|'.$address4_pr.'|'.$district_pr.'|'.$city_pr.'|'.$reg_data['pincode_pr'].'|'.$reg_data['state_pr'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$reg_data['specify_qualification'].'|'.$reg_data['associatedinstitute'].'|'.$branch.'|'.$reg_data['designation'].'|'.$mem_doj.'|'.$reg_data['email'].'|'.$std_code.'|'.$reg_data['office_phone'].'|'.$reg_data['mobile'].'|'.$reg_data['idproof'].'|'.$reg_data['idNo'].'|'.$transaction_no.'|'.$transaction_date.'|'.$transaction_amt.'|'.$transaction_no.'|'.$optnletter.'|||'.$photo.'|'.$signature.'|'.$idproofimg.'|||||'.$reg_data['aadhar_card'].'|'.$reg_data['bank_emp_id']."\n";
					if($dir_flg)
					{
						// For photo images
						if($photo)
						{
							$image = "./uploads/photograph/".$reg_data['scannedphoto'];
							$max_width = "200";
							$max_height = "200";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
							$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						// For signature images
						if($signature)
						{
							$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
							$max_width = "140";
							$max_height = "100";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
							$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$sign_cnt++;
						}
						// For ID proof images
						if($idproofimg)
						{
							$image = "./uploads/idproof/".$reg_data['idproofphoto'];
							$max_width = "800";
							$max_height = "500";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
							$proof_to_add = $directory."/".$reg_data['idproofphoto'];
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							if(!$idproof_zip_flg)
							{
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else 
								$idproof_cnt++;
						}
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg)
						{
							$success['zip'] = "Digital ELearning Member Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating Digital ELearning Member Images Zip";
						}
					}
					$i++;
					$mem_cnt++;
					//fwrite($fp1, "\n");
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg)
					{
						$success['file'] = "Digital ELearning Member Details File Generated Successfully. ";
					}
					else
					{
						$error['file'] = "Error While Generating Digital ELearning Member Details File.";
					}
				}
				fwrite($fp1, "\n"."Total Digital ELearning Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Digital ELearning Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Digital ELearning Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total Digital ELearning ID Proofs Added = ".$idproof_cnt."\n");
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
			$this->log_model->cronlog("Digital ELearning Member Details Cron End", $desc);
			fwrite($fp1, "\n"."******* IIBF Digital ELearning Member Details Cron End ".$end_time." ********"."\n");
			fclose($fp1);
		}
	}
	/* Only for CSC Vendor : 21-11-2019 */
	public function csc_member()
	{
	    ini_set("memory_limit", "-1");
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
		$current_date = '20200902';
		$cron_file_dir = "./uploads/rahultest/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Vendor CSC Member Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "iibf_csc_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_CSC_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n"."************ Vendor CSC Member Details Cron End ".$start_time." *************"."\n");
			$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ($current_date) ));
			//$mem = array(801503079,801503085);
			$select = 'regnumber,namesub,firstname,middlename,lastname,scannedphoto,scannedsignaturephoto,idproofphoto';
			//$thsi->db->where_in('regnumber', $mem);
			$this->db->join('payment_transaction','payment_transaction.member_regnumber = member_registration.regnumber','LEFT');
			$new_mem_reg = $this->Master_model->getRecords('member_registration ',array('isactive'=>'1','isdeleted'=>0,'bankcode' => 'csc', 'status'=>'1',' DATE(date)'=>$yesterday,),$select);
			//' DATE(date)'=>$yesterday,'pay_type'=>2,
			if(count($new_mem_reg))
			{
				$dirname = "csc_regd_image_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory)){
					array_map('unlink', glob($directory."/*.*"));
					rmdir($directory);
					$dir_flg = mkdir($directory, 0700);
				}
				else{
					$dir_flg = mkdir($directory, 0700);
				}
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				$i = 1;
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					$photo = '';
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto'])){
						$photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					else{
						fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}
					$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto'])){
						$signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					else{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}
					$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto'])){
						$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					else{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
					// Member Number | Name Prefix | Firstname | Middlename | Lastname
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname']."|\n";
					if($dir_flg)
					{
						// For photo images
						if($photo){
							$image = "./uploads/photograph/".$reg_data['scannedphoto'];
							$max_width = "200";
							$max_height = "200";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
							$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg){
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						// For signature images
						if($signature){
							$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
							$max_width = "140";
							$max_height = "100";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
							$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg){
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}else
								$sign_cnt++;
						}
						// For ID proof images
						if($idproofimg){
							$image = "./uploads/idproof/".$reg_data['idproofphoto'];
							$max_width = "800";
							$max_height = "500";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
							$proof_to_add = $directory."/".$reg_data['idproofphoto'];
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							if(!$idproof_zip_flg){
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
							}else 
								$idproof_cnt++;
						}
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg){
							$success['zip'] = "CSC Member Images Zip Generated Successfully";
						}else{
							$error['zip'] = "Error While Generating CSC Member Images Zip";
						}
					}
					$i++;
					$mem_cnt++;
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg){
						$success['file'] = "CSC Member Details File Generated Successfully. ";
					}
					else{
						$error['file'] = "Error While Generating CSC Member Details File.";
					}
				}
				fwrite($fp1, "\n"."Total CSC Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				//$file_w_flg = fwrite($fp, $data);
				$zip->close();
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" =>$success,"error" =>$error,"Start Time"=>$start_time,"End Time"=>$end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Vendor CSC Member Details Cron End", $desc);
			fwrite($fp1, "\n"."************ Vendor CSC Member Details Cron End ".$end_time." *************"."\n");
			fclose($fp1);
		}
	}
	
	/* E-learning Separate Module Member Registration Cron : Added by sagar on 26-08-2021 */
	public function member_elearning_spm()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = $parent_dir_flg = $member_file_flg = 0;
		$success = $error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
						
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		//$this->log_model->cronlog("E-learning Separate Module Member Registration Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date)) { $parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); }
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "iibf_new_member_elearning_spm_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** E-learning Separate Module Member Registration Details Cron Start - ".$start_time." *********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$this->db->where('DATE(er.createdon) LIKE','%2021-10-%');
			$mem = array('510034895');				
			$this->db->group_by('er.regnumber');
			$select = 'er.regid, er.regnumber, er.namesub, er.firstname, er.middlename, er.lastname, er.state, er.email, er.registrationtype, er.mobile, er.isactive, er.createdon, pt.id AS PtId, pt.pay_type, pt.status AS PtStatus';
			$this->db->where_in('er.regnumber',$mem);
			$this->db->join('payment_transaction pt', 'pt.member_regnumber = er.regnumber AND pt.pay_type = 20 ', 'INNER');
			$member_data = $this->Master_model->getRecords('spm_elearning_registration er',array('er.isactive'=>'1'),$select);
					
			echo $this->db->last_query();
			if(count($member_data))
			{
				$i = 1;
				$rec_cnt = 0;
				foreach($member_data as $res)
				{
					$data = '';
											
					$data .= $res['regnumber'].'|'.$res['namesub'].'|'.$res['firstname'].'|'.$res['middlename'].'|'.$res['lastname'].'|'.$res['state'].'|'.$res['email'].'|'.$res['mobile'].'|'.$res['createdon']."\n";  
					
					$member_file_flg = fwrite($fp, $data);
					
					if($member_file_flg){ $success['member_details'] = "E-learning Separate Module Member Registration Details File Generated Successfully."; }
					else { $error['member_details'] = "Error While Generating E-learning Separate Module Member Registration Details File."; }
					
					$i++;
					$rec_cnt++;
				}
				
				fwrite($fp1, "Total E-learning Separate Module Member Registration - ".$rec_cnt."\n");
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
			//$this->log_model->cronlog("E-learning Separate Module Member Registration Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** E-learning Separate Module Member Registration Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1); 
		}
	}	
		
	
	/* Member Registration Cron */
	public function edit_data_single()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$photo_zip_flg = 0;
		$sign_zip_flg = 0;
		$idproof_zip_flg = 0;
		
		$vis_imp_cert_img_zip_flg = 0;
		$orth_han_cert_img_zip_flg = 0;
		$cer_palsy_cert_img_zip_flg = 0;
		
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");
		//$current_date = '20200105';
		$cron_file_dir = "./uploads/cronFilesCustom/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("New Member Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "edited_cand_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** New Member Details Cron Start - ".$start_time." ********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday = '2021-12-28'; 
		//	$excode = array('526','527','991');
			//$this->db->where_not_in('a.excode', $excode);
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(editedon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));	
			//echo $this->db->last_query(); exit;
			if(count($new_mem_reg))
			{
				$dirname = "edited_image_".$current_date;
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
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				
				$vis_imp_cert_img_cnt = $orth_han_cert_img_cnt = $cer_palsy_cert_img_cnt = 0;
				
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					
					$gender = '';
					if($reg_data['gender'] == 'male')	{ $gender = 'M';}
					else if($reg_data['gender'] == 'female')	{ $gender = 'F';}
					
					$photo = '';
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
					{
						$photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
					{
						$signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
					{
						$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					$qualification = '';
					switch($reg_data['qualification'])
					{
						// Values changes as per client req. : 22 July 2019
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;
						/* 			
							case "U"	: 	$qualification = 1;
										break;
							case "G"	: 	$qualification = 3;
										break;
							case "P"	: 	$qualification = 5;
										break;				
						*/					
										
					}
					
					$transaction_no = '';
					$transaction_date = '';
					$transaction_amt = '0';
					if($reg_data['registrationtype']!='NM')
					{
						if($reg_data['registrationtype'] == 'DB')
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_DB','pay_type'=>2,'member_regnumber'=>$reg_data['regnumber'],'status'=>1),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						}
						else
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'iibfregn','status'=>1,'pay_type'=>1,'ref_id'=>$reg_data['regid']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						}
					}
					else
					{
						$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_REG','status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						
					}
					if(count($trans_details))
					{
						$transaction_no = $trans_details[0]['transaction_no'];
						$transaction_amt = $trans_details[0]['amount'];
						$date = $trans_details[0]['date'];
						if($date!='0000-00-00')
						{	$transaction_date = date('d-M-y',strtotime($date));	}
					}
					elseif($reg_data['excode'] == '191' || $reg_data['excode'] == '1910' || $reg_data['excode'] == '19100')
					{
						$get_date = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','regnumber'=>$reg_data['regnumber']),'DATE_FORMAT(editedon,"%Y-%m-%d") editedon');
						
						$date = $get_date[0]['editedon'];
						$transaction_date = date('d-M-y',strtotime($date));	
						$transaction_no = '000000000000';
						$transaction_amt = '0';	
					}
					$mem_dob = '';
					if($reg_data['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($reg_data['dateofbirth']));
					}
					$mem_doj = '';
					if($reg_data['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($reg_data['dateofjoin']));
					}
					
					if(strlen($reg_data['stdcode']) > 10)
					{	$std_code = substr($reg_data['stdcode'],0,9);	}
					else
					{	$std_code = $reg_data['stdcode'];	}
					
					if(strlen($reg_data['office']) > 20)
					{	$branch = substr($reg_data['office'],0,19);	}
					else
					{	$branch = $reg_data['office'];	}
					
					if(strlen($reg_data['address1']) > 30)
					{	$address1 = substr($reg_data['address1'],0,29);	}
					else
					{	$address1 = $reg_data['address1'];	}
					
					if(strlen($reg_data['address2']) > 30)
					{	$address2 = substr($reg_data['address2'],0,29);	}
					else
					{	$address2 = $reg_data['address2'];	}
					
					if(strlen($reg_data['address3']) > 30)
					{	$address3 = substr($reg_data['address3'],0,29);	}
					else
					{	$address3 = $reg_data['address3'];	}
					
					if(strlen($reg_data['address4']) > 30)
					{	$address4 = substr($reg_data['address4'],0,29);	}
					else
					{	$address4 = $reg_data['address4'];	}
					
					if(strlen($reg_data['district']) > 30)
					{	$district = substr($reg_data['district'],0,29);	}
					else
					{	$district = $reg_data['district'];	}
					
					if(strlen($reg_data['city']) > 30)
					{	$city = substr($reg_data['city'],0,29);	}
					else
					{	$city = $reg_data['city'];	}
					
					// code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					if(strlen($reg_data['address1_pr']) > 30)
					{	$address1_pr = substr($reg_data['address1_pr'],0,29);	}
					else
					{	$address1_pr = $reg_data['address1_pr'];	}
					
					if(strlen($reg_data['address2_pr']) > 30)
					{	$address2_pr = substr($reg_data['address2_pr'],0,29);	}
					else
					{	$address2_pr = $reg_data['address2_pr'];	}
					
					if(strlen($reg_data['address3_pr']) > 30)
					{	$address3_pr = substr($reg_data['address3_pr'],0,29);	}
					else
					{	$address3_pr = $reg_data['address3_pr'];	}
					
					if(strlen($reg_data['address4_pr']) > 30)
					{	$address4_pr = substr($reg_data['address4_pr'],0,29);	}
					else
					{	$address4_pr = $reg_data['address4_pr'];	}
					
					if(strlen($reg_data['district_pr']) > 30)
					{	$district_pr = substr($reg_data['district_pr'],0,29);	}
					else
					{	$district_pr = $reg_data['district_pr'];	}
					
					if(strlen($reg_data['city_pr']) > 30)
					{	$city_pr = substr($reg_data['city_pr'],0,29);	}
					else
					{	$city_pr = $reg_data['city_pr'];	}
					// eof code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					
					$optnletter = "Y";
					if($reg_data['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else
					{	$optnletter = $reg_data['optnletter'];	}
					
					 
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['registrationtype'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname'].'|'.$reg_data['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$reg_data['pincode'].'|'.$reg_data['state'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$reg_data['specify_qualification'].'|'.$reg_data['associatedinstitute'].'|'.$branch.'|'.$reg_data['designation'].'|'.$mem_doj.'|'.$reg_data['email'].'|'.$std_code.'|'.$reg_data['office_phone'].'|'.$reg_data['mobile'].'|'.$reg_data['idproof'].'|'.$reg_data['idNo'].'|'.$optnletter.'|||'.$photo.'|'.$signature.'|'.$idproofimg.'|||||'.$reg_data['aadhar_card'].'|'.$reg_data['bank_emp_id']."\n"; 
					
					if($dir_flg)
					{
						// For photo images
						if($photo)
						{
							$image = "./uploads/photograph/".$reg_data['scannedphoto'];
							$max_width = "200";
							$max_height = "200";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
							
							$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						
						// For signature images
						if($signature)
						{
							$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
							$max_width = "140";
							$max_height = "100";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
							
							$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$sign_cnt++;
						}
						
						// For ID proof images
						if($idproofimg)
						{
							$image = "./uploads/idproof/".$reg_data['idproofphoto'];
							$max_width = "800";
							$max_height = "500";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
							
							$proof_to_add = $directory."/".$reg_data['idproofphoto'];
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							if(!$idproof_zip_flg)
							{
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else 
								$idproof_cnt++;
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
					$mem_cnt++;
					
					//fwrite($fp1, "\n");
					
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg)
					{
						$success['file'] = "Edited Candidate Images Zip Generated Successfully";
					}
					else
					{
						$error['file'] = "Error While Generating Edited Candidate Images Zip.";
					}
				}
				
				fwrite($fp1, "\n"."Total New Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
			
				
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
			$this->log_model->cronlog("New Member Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Edited Candidate Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/* Membership Edit data Cron */
	public function edit_data()
	{
		ini_set("memory_limit", "-1");
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
		$cron_file_dir = "./uploads/cronFilesCustom/";
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
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Edited Candidate Details Cron Start - ".$start_time."**********\n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday = '2021-02-27';
			//$today = date('Y-m-d');
			$member = array(510487742);
			$this->db->where_in('regnumber',$member);
			$edited_mem_data = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','isdeleted'=>0));
			//' DATE(editedon)'=>$yesterday,
			//echo $this->db->last_query();
			if(count($edited_mem_data))
			{
				$i = 1;
				$mem_cnt = 0;
				foreach($edited_mem_data as $editeddata)
				{
					$photo_data = '';
					$sign_data = '';
					$id_data = '';
					$data = '';
					$gender = '';
					if($editeddata['gender'] == 'male')	{ $gender = 'M';}
					else if($editeddata['gender'] == 'female')	{ $gender = 'F';}
					$qualification = '';
					switch($editeddata['qualification'])
					{				
						// Values changes as per client req. : 22 July 2019
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;
						/* 			
							case "U"	: 	$qualification = 1;
										break;
							case "G"	: 	$qualification = 3;
										break;
							case "P"	: 	$qualification = 5;
										break;				
						*/						
					}
					//MEM_MEM_NO	,MEM_MEM_TYP	,MEM_TLE,	MEM_NAM_1,	MEM_NAM_2	,MEM_NAM_3,	ID_CARD_NAME,	MEM_ADR_1,	MEM_ADR_2,	MEM_ADR_3,	MEM_ADR_4,	MEM_ADR_5,	MEM_ADR_6,	MEM_PIN_CD,	MEM_STE_CD,	MEM_DOB,	MEM_SEX_CD,	MEM_QLF_GRD,	MEM_QLF_CD,	MEM_INS_CD,	BRANCH,	MEM_DSG_CD,	MEM_BNK_JON_DT ,EMAIL,	STD_R,	PHONE_R,	MOBILE,	ID_TYPE,	ID_NO,	BDRNO,	TRN_DATE,	TRN_AMT,	USR_ID,	AR_FLG,	filename_1 filler char(400),PHOTO LOBFILE (filename_1) TERMINATED BY EOF,	PHOTO_FLG,	SIGNATURE_FLG,	ID_FLG,	UPDATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'
					$mem_dob = '';
					if($editeddata['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($editeddata['dateofbirth']));
					}
					$mem_doj = '';
					if($editeddata['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($editeddata['dateofjoin']));
					}
					if(strlen($editeddata['stdcode']) > 10)
					{	$std_code = substr($editeddata['stdcode'],0,9);		}
					else
					{	$std_code = $editeddata['stdcode'];	}
					if(strlen($editeddata['address1']) > 30)
					{	$address1 = substr($editeddata['address1'],0,29);		}
					else
					{	$address1 = $editeddata['address1'];	}
					if(strlen($editeddata['address2']) > 30)
					{	$address2 = substr($editeddata['address2'],0,29);		}
					else
					{	$address2 = $editeddata['address2'];	}
					if(strlen($editeddata['address3']) > 30)
					{	$address3 = substr($editeddata['address3'],0,29);		}
					else
					{	$address3 = $editeddata['address3'];	}
					if(strlen($editeddata['address4']) > 30)
					{	$address4 = substr($editeddata['address4'],0,29);		}
					else
					{	$address4 = $editeddata['address4'];	}
					if(strlen($editeddata['district']) > 30)
					{	$district = substr($editeddata['district'],0,29);		}
					else
					{	$district = $editeddata['district'];	}
					if(strlen($editeddata['city']) > 30)
					{	$city = substr($editeddata['city'],0,29);		}
					else
					{	$city = $editeddata['city'];	}
					if($editeddata['editedby'] == '')
					{
						$edited_by = "Candidate";
					}
					else
					{
						$edited_by = $editeddata['editedby'];	
					}
					$branch = '';
					$branch_name = '';
					if($editeddata['editedon'] < "2016-12-29 00:00:00")
					{
						$branch = $editeddata['branch'];
					}
					else if($editeddata['editedon'] >= "2016-12-29")
					{
						if(is_numeric($editeddata['office']))
						{
							if($editeddata['branch']!='')
								$branch = $editeddata['branch'];
							else
								$branch = $editeddata['office'];
						}
						else
						{
							if($editeddata['branch']!='')
								$branch = $editeddata['branch'];
							else
								$branch = $editeddata['office'];
						}
					}
					if($branch == '')
					{
						$branch = $city;
					}
					if(strlen($branch) > 20)
					{	$branch_name = substr($branch,0,19);	}
					else
					{	$branch_name = $branch;	}
					$optnletter = "Y";
					if($editeddata['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else if($editeddata['optnletter'] != "")
					{	$optnletter = $editeddata['optnletter'];	}
					$data = ''.$editeddata['regnumber'].'|'.$editeddata['registrationtype'].'|'.$editeddata['namesub'].'|'.$editeddata['firstname'].'|'.$editeddata['middlename'].'|'.$editeddata['lastname'].'|'.$editeddata['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$editeddata['pincode'].'|'.$editeddata['state'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$editeddata['specify_qualification'].'|'.$editeddata['associatedinstitute'].'|'.$branch_name.'|'.$editeddata['designation'].'|'.$mem_doj.'|'.$editeddata['email'].'|'.$std_code.'|'.$editeddata['office_phone'].'|'.$editeddata['mobile'].'|'.$editeddata['idproof'].'|'.$editeddata['idNo'].'||||';
					$data .= $edited_by.'|'.$optnletter.'|N|N|N|'.date('d-M-y H:i:s',strtotime($editeddata['editedon'])).'|'.$editeddata['aadhar_card'].'|'.$editeddata['bank_emp_id']."\n";
					$edit_data_flg = fwrite($fp, $data);
					if($edit_data_flg)
						$success['edit_data'] = "Edited Candidate Details File Generated Successfully.";
					else
						$error['edit_data'] = "Error While Generating Edited Candidate Details File.";
					$i++;
					$mem_cnt++;
				}
				fwrite($fp1, "\n"."Total Members Edited = ".$mem_cnt."\n");
			}
			else
			{
				$success[] = "No Profile data found for the date";
			}
			// Image data 
			$member = array(801460764,7663801);
			$this->db->where_in('regnumber',$member);
			$edited_img_data = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','isdeleted'=>0));
			//'DATE(images_editedon)'=>$yesterday,
			//echo $this->db->last_query(); die;
			if(count($edited_img_data))
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
				//$imgdata = $edited_img_data[0];
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				$photo_flg_cnt = 0;
				$sign_flg_cnt = 0;
				$idproof_flg_cnt = 0;
				foreach($edited_img_data as $imgdata)
				{	
					$photo = '';
					$signature = '';
					$idproofimg = '';
					$img_edited_by = '';
					$data = '';
					$gender = '';
					if($imgdata['gender'] == 'male')	{ $gender = 'M';}
					else if($imgdata['gender'] == 'female')	{ $gender = 'F';}
					$qualification = '';
					switch($imgdata['qualification'])
					{
						// Values changes as per client req. : 22 July 2019
						case "U"	: 	$qualification = 'UG';
										break;
						case "G"	: 	$qualification = 'G';
										break;
						case "P"	: 	$qualification = 'PG';
										break;
						/* 			
							case "U"	: 	$qualification = 1;
										break;
							case "G"	: 	$qualification = 3;
										break;
							case "P"	: 	$qualification = 5;
										break;				
						*/			
					}
					//MEM_MEM_NO	,MEM_MEM_TYP	,MEM_TLE,	MEM_NAM_1,	MEM_NAM_2	,MEM_NAM_3,	ID_CARD_NAME,	MEM_ADR_1,	MEM_ADR_2,	MEM_ADR_3,	MEM_ADR_4,	MEM_ADR_5,	MEM_ADR_6,	MEM_PIN_CD,	MEM_STE_CD,	MEM_DOB,	MEM_SEX_CD,	MEM_QLF_GRD,	MEM_QLF_CD,	MEM_INS_CD,	BRANCH,	MEM_DSG_CD,	MEM_BNK_JON_DT ,EMAIL,	STD_R,	PHONE_R,	MOBILE,	ID_TYPE,	ID_NO,	BDRNO,	TRN_DATE,	TRN_AMT,	USR_ID,	AR_FLG,	filename_1 filler char(400),PHOTO LOBFILE (filename_1) TERMINATED BY EOF,	PHOTO_FLG,	SIGNATURE_FLG,	ID_FLG,	UPDATED_ON TIMESTAMP 'DD/MON/YY HH24:MI:SS'
					$mem_dob = '';
					if($imgdata['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($imgdata['dateofbirth']));
					}
					$mem_doj = '';
					if($imgdata['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($imgdata['dateofjoin']));
					}
					if(strlen($imgdata['stdcode']) > 10)
					{	$std_code = substr($imgdata['stdcode'],0,9);		}
					else
					{	$std_code = $imgdata['stdcode'];	}
					if(strlen($imgdata['address1']) > 30)
					{	$address1 = substr($imgdata['address1'],0,29);		}
					else
					{	$address1 = $imgdata['address1'];	}
					if(strlen($imgdata['address2']) > 30)
					{	$address2 = substr($imgdata['address2'],0,29);		}
					else
					{	$address2 = $imgdata['address2'];	}
					if(strlen($imgdata['address3']) > 30)
					{	$address3 = substr($imgdata['address3'],0,29);		}
					else
					{	$address3 = $imgdata['address3'];	}
					if(strlen($imgdata['address4']) > 30)
					{	$address4 = substr($imgdata['address4'],0,29);		}
					else
					{	$address4 = $imgdata['address4'];	}
					if(strlen($imgdata['district']) > 30)
					{	$district = substr($imgdata['district'],0,29);		}
					else
					{	$district = $imgdata['district'];	}
					if(strlen($imgdata['city']) > 30)
					{	$city = substr($imgdata['city'],0,29);		}
					else
					{	$city = $imgdata['city'];	}
					if($imgdata['editedby'] == '')
					{
						$edited_by = "Candidate";
					}
					else
					{
						$edited_by = $imgdata['editedby'];	
					}
					$branch = '';
					$branch_name = '';
					if($imgdata['editedon'] < "2016-12-29 00:00:00")
					{
						$branch = $imgdata['branch'];
					}
					else if($imgdata['editedon'] >= "2016-12-29")
					{
						if(is_numeric($imgdata['office']))
						{
							if($imgdata['branch']!='')
								$branch = $imgdata['branch'];
							else
								$branch = $imgdata['office'];
						}
						else
						{
							if($imgdata['branch']!='')
								$branch = $imgdata['branch'];
							else
								$branch = $imgdata['office'];
						}
					}
					if($branch == '')
					{
						$branch = $city;
					}
					if(strlen($branch) > 20)
					{	$branch_name = substr($branch,0,19);	}
					else
					{	$branch_name = $branch;	}
					$optnletter = "Y";
					if($imgdata['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else if($imgdata['optnletter'] != "")
					{	$optnletter = $imgdata['optnletter'];	}
					$data = ''.$imgdata['regnumber'].'|'.$imgdata['registrationtype'].'|'.$imgdata['namesub'].'|'.$imgdata['firstname'].'|'.$imgdata['middlename'].'|'.$imgdata['lastname'].'|'.$imgdata['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$imgdata['pincode'].'|'.$imgdata['state'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$imgdata['specify_qualification'].'|'.$imgdata['associatedinstitute'].'|'.$branch_name.'|'.$imgdata['designation'].'|'.$mem_doj.'|'.$imgdata['email'].'|'.$std_code.'|'.$imgdata['office_phone'].'|'.$imgdata['mobile'].'|'.$imgdata['idproof'].'|'.$imgdata['idNo'].'||||';
					if($imgdata['images_editedby'] == '')
					{
						$img_edited_by = "Candidate";
					}
					else
					{
						$img_edited_by = $imgdata['images_editedby'];	
					}
					if($imgdata['scannedphoto'] != "" && is_file("./uploads/photograph/".$imgdata['scannedphoto']))
					{
						$photo 	= MEM_FILE_EDIT_PATH.$imgdata['scannedphoto'];
					}
					if($imgdata['scannedsignaturephoto'] != "" && is_file("./uploads/scansignature/".$imgdata['scannedsignaturephoto']))
					{
						$signature 	= MEM_FILE_EDIT_PATH.$imgdata['scannedsignaturephoto'];
					}
					if($imgdata['idproofphoto'] != "" && is_file("./uploads/idproof/".$imgdata['idproofphoto']))
					{
						$idproofimg = MEM_FILE_EDIT_PATH.$imgdata['idproofphoto'];
					}
					if($photo != '')
					{
						if($imgdata['photo_flg']=='Y')
						{
							$photo_data = $data.$img_edited_by.'|'.$optnletter.'|'.$photo.'|Y|N|N|'.date('d-M-y H:i:s',strtotime($imgdata['images_editedon'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id']."\n";
							$photo_file_flg = fwrite($photo_fp, $photo_data);
						}
						$edited_photo_flg = $imgdata['photo_flg'];
						if($photo_file_flg)
							$success['photo_file'] = "Edited Candidate Details Photo File Generated Successfully. ";
						else
							$error['photo_file'] = "Error While Generating Edited Candidate Details Photo File.";
					}
					else
					{
						$edited_photo_flg = "N";
					}
					if($signature != '')
					{
						if($imgdata['signature_flg']=='Y')
						{
							$sign_data = $data.$img_edited_by.'|'.$optnletter.'|'.$signature.'|N|Y|N|'.date('d-M-y H:i:s',strtotime($imgdata['images_editedon'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id']."\n";
							$sign_file_flg =  fwrite($sign_fp, $sign_data);
						}
						$edited_sign_flg = $imgdata['signature_flg'];
						if($sign_file_flg)
							$success['sign_file'] = "Edited Candidate Details Signature File Generated Successfully.";
						else
							$error['sign_file'] = "Error While Generating Edited Candidate Details Signature File.";
					}
					else
					{
						$edited_sign_flg = "N";	
					}
					if($idproofimg != '')
					{
						if($imgdata['id_flg']=='Y')
						{
							$id_data = $data.$img_edited_by.'|'.$optnletter.'|'.$idproofimg.'|N|N|Y|'.date('d-M-y H:i:s',strtotime($imgdata['images_editedon'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id']."\n";
							$idproof_file_flg =  fwrite($id_fp, $id_data);
						}
						$edited_idproof_flg = $imgdata['id_flg'];
						if($idproof_file_flg)
							$success['idproof_file'] = "Edited Candidate Details Id-Proof File Generated Successfully. ";
						else
							$error['idproof_file'] = "Error While Generating Edited Candidate Details Id-Proof File.";
					}
					else
					{
						$edited_idproof_flg = "N";
					}
					// Zip Image Folder
					if($dir_flg)
					{
						$photo_zip_flg = 0;
						$sign_zip_flg = 0;
						$idproof_zip_flg = 0;
						if($imgdata['photo_flg'] == 'Y' && $photo != '')
						{
							$photo_flg_cnt++;
							// For photo images
							if(is_file("./uploads/photograph/".$imgdata['scannedphoto']))
							{
								$image = "./uploads/photograph/".$imgdata['scannedphoto'];
								$max_width = "200";
								$max_height = "200";
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['scannedphoto']);
								//copy($actual_photo_path,$directory."/".$actual_photo_name);
								$photo_to_add = $directory."/".$imgdata['scannedphoto'];
								$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
								$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
								if(!$photo_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$imgdata['scannedphoto']." (".$imgdata['regnumber'].")\n");	
								}
								else
									$photo_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$imgdata['scannedphoto']." (".$imgdata['regnumber'].")\n");	
							}
						}
						if($imgdata['signature_flg'] == 'Y' && $signature != '')
						{
							$sign_flg_cnt++;
							//copy("./uploads/scansignature/".$imgdata['scannedsignaturephoto'],$directory."/".$imgdata['scannedsignaturephoto']);
							// For signature images
							if(is_file("./uploads/scansignature/".$imgdata['scannedsignaturephoto']))
							{
								$image = "./uploads/scansignature/".$imgdata['scannedsignaturephoto'];
								$max_width = "140";
								$max_height = "100";
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['scannedsignaturephoto']);
								//copy($actual_sign_path,$directory."/".$actual_sign_name);
								$sign_to_add = $directory."/".$imgdata['scannedsignaturephoto'];
								$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
								$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
								if(!$sign_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$imgdata['scannedsignaturephoto']." (".$imgdata['regnumber'].")\n");	
								}
								else
									$sign_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Signature does not exist  - ".$imgdata['scannedsignaturephoto']." (".$imgdata['regnumber'].")\n");	
							}
						}
						if($imgdata['id_flg'] == 'Y' && $idproofimg != '')
						{
							$idproof_flg_cnt++;
							// For ID proof images
							if(is_file("./uploads/idproof/".$imgdata['idproofphoto']))
							{
								$image = "./uploads/idproof/".$imgdata['idproofphoto'];
								$max_width = "800";
								$max_height = "500";
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['idproofphoto']);
								//copy($actual_idproof_path,$directory."/".$actual_idproof_name);
								$proof_to_add = $directory."/".$imgdata['idproofphoto'];
								$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
								$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
								if(!$idproof_zip_flg)
								{
									fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$imgdata['idproofphoto']." (".$imgdata['regnumber'].")\n");	
								}
								else 
									$idproof_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$imgdata['idproofphoto']." (".$imgdata['regnumber'].")\n");	
							}
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
				}
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."/".$photo_flg_cnt." \n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."/".$sign_flg_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."/".$idproof_flg_cnt."\n");
				$zip->close();
			}
			else
			{
				$success['img'] = "No Image data found for the date";
			}
			fclose($fp);
			fclose($photo_fp);
			fclose($sign_fp);
			fclose($id_fp);
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Edited Candidate Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Edited Candidate Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/* Duplicate i-card  Cron */
	public function dup_icard()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$dup_icard_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
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
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Duplicate I-card Details Cron Start - ".$start_time." ******************** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday ='2020-12-20';
			$ref_id = array(24958,24988,24992,25063,25133,25151,25174,25175,25212);
				
			$select = 'c.regnumber,c.reason_type,c.icard_cnt,a.registrationtype,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount,b.transaction_no';
			
			$this->db->join('member_registration a','a.regnumber=c.regnumber','LEFT');
			$this->db->join('payment_transaction b','b.ref_id=c.did','LEFT');
			$this->db->where_in('c.did', $ref_id);
			$dup_icard_data = $this->Master_model->getRecords('duplicate_icard c',array('pay_type'=>3,'isactive'=>'1','status'=>'1','isdeleted'=>0),$select);
			//' DATE(added_date)'=>$yesterday,
			if(count($dup_icard_data))
			{
				$data = '';
				$i = 1;
				$mem_cnt = 0;
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
					$icard_date = '';
					if($icard['date'] != '0000-00-00')
					{
						$icard_date = date('d-M-Y',strtotime($icard['date']));
					}
					$data .= ''.$icard['regnumber'].'|'.$icard['registrationtype'].'|'.$reason_type.'|'.$icard['transaction_no'].'|'.$icard['icard_cnt'].'|'.$icard_date.'|'.$icard['amount'].'|'.$icard['transaction_no']."\n";
					$i++;
					$mem_cnt++;
				}
				fwrite($fp1, "\n"."Total Duplicate I-card Applications = ".$mem_cnt."\n");
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
			$this->log_model->cronlog("Duplicate I-card Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Duplicate I-card Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	/* Free Remote Exam Application Cron*/
	public function remote_exam_free()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Candidate Free Remote Exam Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "free_remote_exam_cand_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Free Remote Exam Details Cron Start - ".$start_time." *********** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			$yesterday ='2020-11-07';
			$exam_codes_arr = array($this->config->item('examCodeCaiib'));
			//'1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014','991'
			$mem = array(6332183,6332477,6332487,6332498,6332522,6332587,6332602,6332618,6332668,6332722,6332735,6332745,6332747,6332750,6332751,6332472,6332437,6332430,6332197,6332210,6332215,6332231,6332241,6332288,6332297,6332354,6332356,6332365,6332369,6332375,6332386,6332396,6332796,6332835,6333264,6333279,6333308,6333333,6333453,6333470,6333830,6334144,6334676,6334681,6334804,6334858,6334871,6334884,6333177,6333156,6333152,6332853,6332857,6332891,6332905,6332945,6333000,6333004,6333012,6333032,6333065,6333101,6333118,6333120,6333129,6335531,6332159,6331727,6331884,6331886,6331891,6331893,6331895,6331906,6331924,6331933,6331950,6331952,6331953,6331956,6331972,6331974,6331883,6331879,6331868,6331734,6331736,6331743,6331753,6331755,6331767,6331783,6331817,6331825,6331849,6331850,6331852,6331859,6331861,6331977,6331978,6332073,6332074,6332077,6332080,6332082,6332087,6332096,6332108,6332109,6332112,6332119,6332120,6332123,6332128,6332064,6332044,6332043,6331979,6331981,6331985,6331986,6331991,6331995,6331996,6332002,6332013,6332019,6332020,6332030,6332039,6332040,6332138);
			$select = 'a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,a.elearning_flag,a.free_paid_flg,a.created_on'; //,e.exam_date
			$this->db->join('member_registration c','a.regnumber = c.regnumber','LEFT'); 
			//$this->db->join('admit_card_details e','a.id = e.mem_exam_id','LEFT'); 
			$this->db->where_in('a.exam_code',$exam_codes_arr);
			$this->db->where_in('a.exam_period','221');
			$this->db->where_in('a.id',$mem);
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array('isactive'=>'1','isdeleted'=>0,'pay_status'=>1),$select);
			
			//echo $this->db->last_query(); die;
			//' DATE(a.created_on)'=>$yesterday,'a.free_paid_flg'=>'F'
			if(count($can_exam_data))
			{
				$i = 1;
				$exam_cnt = 0;
				foreach($can_exam_data as $exam)
				{
					$data = '';
					$exam_mode = 'O';
					if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
					else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
					else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
					$syllabus_code = '';
					$part_no = '';
					$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
					if(count($subject_data)>0){
						$syllabus_code = $subject_data[0]['syllabus_code'];
						$part_no = $subject_data[0]['part_no'];
					}
					$trans_date = '';
					if($exam['created_on'] != '0000-00-00'){
						$trans_date = date('d-M-Y',strtotime($exam['created_on']));
					}
					/* $exam_date = '';
					if($exam['exam_date'] != '0000-00-00'){
						$exam_date = date('d-M-Y',strtotime($exam['exam_date']));
					} */
					$place_of_work = $pin_code_place_of_work = $state_place_of_work = $city = $branch = $branch_name = $state = $pincode = $exam_period = $exam_code = '';
					$exam_period = $exam['exam_period'];
					$exam_code = $exam['exam_code'];
					$transaction_no = '0000000000000';
					$exam_fee = '0';
					//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG,EXAM_DATE,ELEARNING_FLAG(Y/N),FREE_PAID_FLG(F/P)
					$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$transaction_no.'|'.$trans_date.'|'.$exam_fee.'|'.$transaction_no.'|'.$exam['scribe_flag'].'||'.$exam['elearning_flag'].'|'.'F'."\n"; //'|'.$exam_date.'|'.$exam['free_paid_flg']
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "Free Remote Exam Details File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating Free Remote Exam Details File.";
					$i++;
					$exam_cnt++;
				}
				fwrite($fp1, "Total Free Remote Exam Applications - ".$exam_cnt."\n");
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
			$this->log_model->cronlog("Free Remote Exam Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Free Remote Exam Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	/* Remote Exam Application Cron*/
	public function remote_exam()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Candidate Remote Exam Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "remote_exam_cand_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Remote Exam Details Cron Start - ".$start_time." *********** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			$yesterday = '2020-11-07';
			$exam_codes_arr = array('1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014','2027','1019','1020');
			$ref_id = array(6435408);
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,e.exam_date,a.elearning_flag,a.free_paid_flg';
			//$this->db->like('b.date', '2020-12-');
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('member_registration c','a.regnumber = c.regnumber','LEFT'); 
			$this->db->join('admit_card_details e','a.id = e.mem_exam_id','LEFT'); 
			$this->db->where_in('a.exam_code',$exam_codes_arr);
			$this->db->where_in('a.id',$ref_id);
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1),$select);
			echo $this->db->last_query();
			//' DATE(a.created_on)'=>$yesterday,
			if(count($can_exam_data))
			{
				$i = 1;
				$exam_cnt = 0;
				foreach($can_exam_data as $exam)
				{
					$data = '';
					$exam_mode = 'O';
					if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
					else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
					else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
					$syllabus_code = '';
					$part_no = '';
					$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
					if(count($subject_data)>0)
					{
						$syllabus_code = $subject_data[0]['syllabus_code'];
						$part_no = $subject_data[0]['part_no'];
					}
					$trans_date = '';
					if($exam['date'] != '0000-00-00')
					{
						$trans_date = date('d-M-Y',strtotime($exam['date']));
					}
					$exam_date = '';
					if($exam['exam_date'] != '0000-00-00')
					{
						$exam_date = date('d-M-Y',strtotime($exam['exam_date']));
					}
					$place_of_work = $pin_code_place_of_work = $state_place_of_work = $city = $branch = $branch_name = $state = $pincode = $exam_period = $exam_code = $free_paid_flg = '';
					$exam_period = $exam['exam_period'];
					$exam_code = $exam['exam_code'];
					if($exam['exam_fee'] == '295.00')
					{
						$free_paid_flg = 'F';
					}
					else
					{
						$free_paid_flg = $exam['free_paid_flg'];
					}
					if($exam_code  == '2027')
					{
						$exam_code = '1017';
					}
					else{
						$exam_code = $exam['exam_code'];
					}
					//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG,EXAM_DATE,ELEARNING_FLAG,free_paid_fl FREE_PAID_FLG(P/F)
					$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$exam['scribe_flag'].'|'.$exam_date.'|'.$exam['elearning_flag'].'|'.$free_paid_flg."\n";
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "Remote Exam Details File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating Remote Exam Details File.";
					$i++;
					$exam_cnt++;
				}
				fwrite($fp1, "Total Remote Exam Applications - ".$exam_cnt."\n");
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
			$this->log_model->cronlog("Remote Exam Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Remote Exam Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	/* Exam Application Cron*/
	public function exam_old()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
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
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Candidate Exam Details Cron Start - ".$start_time." *********** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			$yesterday = '2020-09-01';
			$mem = array(510092357,510439133,510445767,500174753,510440894,510439503,510448179,510439194,510441035,510440886,510440923,510184993,510445718,510445720,510439785,510446042,510439650,510445793);
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,a.elearning_flag';
			$this->db->where_in('a.regnumber' , $mem);
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array('status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'a.exam_code !='=>'991','pay_type'=>2),$select);
			//' DATE(a.created_on)'=>$yesterday,
			if(count($can_exam_data))
			{
				$i = 1;
				$exam_cnt = 0;
				foreach($can_exam_data as $exam)
				{
					$data = '';
					//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG
					$exam_mode = 'O';
					if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
					else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
					else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
					$syllabus_code = '';
					$part_no = '';
					$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
					if(count($subject_data)>0)
					{
						$syllabus_code = $subject_data[0]['syllabus_code'];
						$part_no = $subject_data[0]['part_no'];
					}
					$trans_date = '';
					if($exam['date'] != '0000-00-00')
					{
						$trans_date = date('d-M-Y',strtotime($exam['date']));
					}
					$exam_period = '';
					if($exam['examination_date'] != '' && $exam['examination_date'] != "0000-00-00")
					{
						$ex_period = $this->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam['examination_date']));
						if(count($ex_period))
						{
							$exam_period = $ex_period[0]['period'];	
						}
					}
					else{	$exam_period = $exam['exam_period'];	}
					$exam_code = '';
					if($exam['exam_code'] == 340 || $exam['exam_code'] == 3400 || $exam['exam_code'] == 34000)
					{
					 	$exam_code = 34;
					}elseif($exam['exam_code'] == 580 || $exam['exam_code'] == 5800 || $exam['exam_code'] == 58000){
						$exam_code = 58;
					}elseif($exam['exam_code'] == 1600 || $exam['exam_code'] == 16000){
						$exam_code = 160;
					}elseif($exam['exam_code'] == 200){
						$exam_code = 20;
					}elseif($exam['exam_code'] == 1770 || $exam['exam_code'] == 17700){
						$exam_code =177;
					}elseif ($exam['exam_code'] == 590){
						$exam_code = 59;
					}elseif ($exam['exam_code'] == 810){
						$exam_code = 81;
					}elseif ($exam['exam_code'] == 1750){
						$exam_code = 175;
					}else{
						$exam_code = $exam['exam_code'];
					}
					$scribe_flag = $exam['scribe_flag'];
					// Condition for DISA and CSIC Exam Application
					if($exam_code == '990' || $exam_code == '993'){
						$part_no = 1;
						$exam_mode = 'O';
						$syllabus_code = 'R';
						$scribe_flag = 'N';
					}
					$place_of_work = '';
					$pin_code_place_of_work = '';
					$state_place_of_work = '';
					$city = '';
					$branch = '';
					$branch_name = '';
					$state = '';
					$pincode = '';
					//$exam['elected_sub_code']!=0
					//if($exam_code == 60 || $exam_code == 21) // For CAIIB & JAIIB
					if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == $this->config->item('examCodeCaiibElective68') || $exam_code == $this->config->item('examCodeCaiibElective69') || $exam_code == $this->config->item('examCodeCaiibElective70') || $exam_code == $this->config->item('examCodeCaiibElective71') || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) // For CAIIB, CAIIB Elective & JAIIB, above line commented and this line added by Bhagwan Sahane, on 06-10-2017
					{
						if($exam['state'])
						{	$state = $exam['state']; }
						if($exam['pincode'])
						{	$pincode = $exam['pincode']; }
						if(strlen($exam['city']) > 30)
						{	$city = substr($exam['city'],0,29);		}
						else
						{	$city = $exam['city'];	}
						if($exam['editedon'] < "2016-12-29 00:00:00")
						{
							$branch = $exam['branch'];
						}
						else if($exam['editedon'] >= "2016-12-29")
						{
							if(is_numeric($exam['office']))
							{
								if($exam['branch']!='')
									$branch = $exam['branch'];
								else
									$branch = $city;
							}
							else
							{
								if($exam['branch']!='')
									$branch = $exam['branch'];
								else
									$branch = $exam['office'];
							}
						}
						if($branch == '')
						{
							$branch = $city;
						}
						if(strlen($branch) > 20)
						{	$branch_name = substr($branch,0,19); }
						else
						{	$branch_name = $branch;	}
						if($exam['place_of_work'])
							$place_of_work = $exam['place_of_work'];
						else
							$place_of_work =  $branch_name;
						if($exam['pin_code_place_of_work'])
							$pin_code_place_of_work = $exam['pin_code_place_of_work'];
						else
							$pin_code_place_of_work =  $pincode;
						if($exam['state_place_of_work'])
							$state_place_of_work = $exam['state_place_of_work'];
						else
							$state_place_of_work =  $state;
						$elected_sub_code = '';
						if($exam_code == $this->config->item('examCodeCaiib'))
						{	$elected_sub_code = $exam['elected_sub_code'];	}
						if(strlen($place_of_work) > 30)
						{	$place_of_work = substr($place_of_work,0,29);		}
						else
						{	$place_of_work = $place_of_work;	}
						// Get old exam_code for CAIIB
						/*if($exam_code == 60)
						{
							$ex_code = $this->master_model->getRecords('eligible_master_60_117',array('member_no'=>$exam['regnumber'],'member_type'=>$exam['registrationtype']),'exam_code');
							if(count($ex_code))
							{
								$exam_code = $ex_code[0]['exam_code'];
							}
						}*/
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$scribe_flag.'|'.$exam['elearning_flag']."\n"; 
					}
					else
					{
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$scribe_flag.'|'.$exam['elearning_flag']."\n"; 
					}
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "Candidate Exam Details File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating Candidate Exam Details File.";
					$i++;
					$exam_cnt++;
				}
				fwrite($fp1, "Total Exam Applications - ".$exam_cnt."\n");
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
			$this->log_model->cronlog("Candidate Exam Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Candidate Exam Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	///usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_data_gen exam
	/* Exam Application Cron*/
	public function exam_old_ch()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
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
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Candidate Exam Details Cron Start - ".$start_time." *********** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			$yesterday = '2020-11-05';
			$not_exam_codes = array('991','1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014'); // Not becoz this exam code we send exam date from other crons CSC and remote
			$mem = array(5216535,4798994,4685683,4648124,4634619,4836899,4685683,4844339,4702147,4764188,4635263,4723152,4658096,4635263,4738185,4637137,4702147,4701186,4777993,4643739,4632282,4775747,4727930,4702147,4758431,4672123,4698307,4700679,4662027,4734660,4702390,4792678,4687536);
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,a.elearning_flag';
			$this->db->like('b.date', '2020-12-');
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
			$this->db->where_not_in('a.exam_code',$not_exam_codes);
			$this->db->where_in('a.exam_period','220');
			$this->db->where_in('a.id' , $mem);
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array('status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'pay_type'=>2),$select);
			//' DATE(a.created_on)'=>$yesterday,
			if(count($can_exam_data))
			{
				$i = 1;
				$exam_cnt = 0;
				foreach($can_exam_data as $exam)
				{
					$data = '';
					//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG
					$exam_mode = 'O';
					if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
					else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
					else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
					$syllabus_code = '';
					$part_no = '';
					$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
					if(count($subject_data)>0)
					{
						$syllabus_code = $subject_data[0]['syllabus_code'];
						$part_no = $subject_data[0]['part_no'];
					}
					$trans_date = '';
					if($exam['date'] != '0000-00-00')
					{
						$trans_date = date('d-M-Y',strtotime($exam['date']));
					}
					$exam_period = '';
					if($exam['examination_date'] != '' && $exam['examination_date'] != "0000-00-00")
					{
						$ex_period = $this->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam['examination_date']));
						if(count($ex_period))
						{
							$exam_period = $ex_period[0]['period'];	
						}
					}
					else{	$exam_period = $exam['exam_period'];	}
					$exam_code = '';
					if($exam['exam_code'] == 340 || $exam['exam_code'] == 3400 || $exam['exam_code'] == 34000)
					{
					 	$exam_code = 34;
					}elseif($exam['exam_code'] == 580 || $exam['exam_code'] == 5800 || $exam['exam_code'] == 58000){
						$exam_code = 58;
					}elseif($exam['exam_code'] == 1600 || $exam['exam_code'] == 16000){
						$exam_code = 160;
					}elseif($exam['exam_code'] == 200){
						$exam_code = 20;
					}elseif($exam['exam_code'] == 1770 || $exam['exam_code'] == 17700){
						$exam_code =177;
					}elseif ($exam['exam_code'] == 590){
						$exam_code = 59;
					}elseif ($exam['exam_code'] == 810){
						$exam_code = 81;
					}elseif ($exam['exam_code'] == 1750){
						$exam_code = 175;
					}else{
						$exam_code = $exam['exam_code'];
					}
					$scribe_flag = $exam['scribe_flag'];
					// Condition for DISA and CSIC Exam Application
					if($exam_code == '990' || $exam_code == '993'){
						$part_no = 1;
						$exam_mode = 'O';
						$syllabus_code = 'R';
						$scribe_flag = 'N';
					}
					$place_of_work = '';
					$pin_code_place_of_work = '';
					$state_place_of_work = '';
					$city = '';
					$branch = '';
					$branch_name = '';
					$state = '';
					$pincode = '';
					//$exam['elected_sub_code']!=0
					//if($exam_code == 60 || $exam_code == 21) // For CAIIB & JAIIB
					if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == $this->config->item('examCodeCaiibElective68') || $exam_code == $this->config->item('examCodeCaiibElective69') || $exam_code == $this->config->item('examCodeCaiibElective70') || $exam_code == $this->config->item('examCodeCaiibElective71') || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) // For CAIIB, CAIIB Elective & JAIIB, above line commented and this line added by Bhagwan Sahane, on 06-10-2017
					{
						if($exam['state'])
						{	$state = $exam['state']; }
						if($exam['pincode'])
						{	$pincode = $exam['pincode']; }
						if(strlen($exam['city']) > 30)
						{	$city = substr($exam['city'],0,29);		}
						else
						{	$city = $exam['city'];	}
						if($exam['editedon'] < "2016-12-29 00:00:00")
						{
							$branch = $exam['branch'];
						}
						else if($exam['editedon'] >= "2016-12-29")
						{
							if(is_numeric($exam['office']))
							{
								if($exam['branch']!='')
									$branch = $exam['branch'];
								else
									$branch = $city;
							}
							else
							{
								if($exam['branch']!='')
									$branch = $exam['branch'];
								else
									$branch = $exam['office'];
							}
						}
						if($branch == '')
						{
							$branch = $city;
						}
						if(strlen($branch) > 20)
						{	$branch_name = substr($branch,0,19); }
						else
						{	$branch_name = $branch;	}
						if($exam['place_of_work'])
							$place_of_work = $exam['place_of_work'];
						else
							$place_of_work =  $branch_name;
						if($exam['pin_code_place_of_work'])
							$pin_code_place_of_work = $exam['pin_code_place_of_work'];
						else
							$pin_code_place_of_work =  $pincode;
						if($exam['state_place_of_work'])
							$state_place_of_work = $exam['state_place_of_work'];
						else
							$state_place_of_work =  $state;
						$elected_sub_code = '';
						if($exam_code == $this->config->item('examCodeCaiib'))
						{	$elected_sub_code = $exam['elected_sub_code'];	}
						if(strlen($place_of_work) > 30)
						{	$place_of_work = substr($place_of_work,0,29);		}
						else
						{	$place_of_work = $place_of_work;	}
						// Get old exam_code for CAIIB
						/*if($exam_code == 60)
						{
							$ex_code = $this->master_model->getRecords('eligible_master_60_117',array('member_no'=>$exam['regnumber'],'member_type'=>$exam['registrationtype']),'exam_code');
							if(count($ex_code))
							{
								$exam_code = $ex_code[0]['exam_code'];
							}
						}*/
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$scribe_flag.'|'.$exam['elearning_flag']."\n"; 
					}
					else
					{
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$scribe_flag.'|'.$exam['elearning_flag']."\n"; 
					}
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "Candidate Exam Details File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating Candidate Exam Details File.";
					$i++;
					$exam_cnt++;
				}
				fwrite($fp1, "Total Exam Applications - ".$exam_cnt."\n");
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
			$this->log_model->cronlog("Candidate Exam Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Candidate Exam Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
/* Exam Application Cron*/
	public function exam()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
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
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Candidate Exam Details Cron Start - ".$start_time." *********** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			$yesterday = '2021-03-20';
			$not_exam_codes = array('991','1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014','2027'); // Not becoz this exam code we send exam date from other crons CSC and remote
			$mem = array(6424587); 
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,a.elearning_flag,a.sub_el_count,d.sub_cd';
		//$this->db->LIKE('b.date', '2020-12-');
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
			$this->db->join('admit_card_details d','a.id=d.mem_exam_id','LEFT'); 
			$this->db->where_not_in('a.exam_code',$not_exam_codes);
			//$this->db->where_in('a.exam_period',array('1018'));
			$this->db->where_in('a.id' , $mem);
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array('status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'pay_type'=>2),$select);
			//' DATE(a.created_on)'=>$yesterday,
			if(count($can_exam_data))
			{
				$i = 1;
				$exam_cnt = 0;
				foreach($can_exam_data as $exam)
				{
					$data = '';
					//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG
					$exam_mode = 'O';
					if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
					else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
					else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
					$syllabus_code = '';
					$part_no = '';
					$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
					if(count($subject_data)>0)
					{
						$syllabus_code = $subject_data[0]['syllabus_code'];
						$part_no = $subject_data[0]['part_no'];
					}
					$trans_date = '';
					if($exam['date'] != '0000-00-00')
					{
						$trans_date = date('d-M-Y',strtotime($exam['date']));
					}
					$exam_period = '';
					if($exam['examination_date'] != '' && $exam['examination_date'] != "0000-00-00")
					{
						$ex_period = $this->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam['examination_date']));
						if(count($ex_period))
						{
							$exam_period = $ex_period[0]['period'];	
						}
					}
					else{	$exam_period = $exam['exam_period'];	}
					$exam_code = '';
					if($exam['exam_code'] == 340 || $exam['exam_code'] == 3400 || $exam['exam_code'] == 34000)
					{
					 	$exam_code = 34;
					}elseif($exam['exam_code'] == 580 || $exam['exam_code'] == 5800 || $exam['exam_code'] == 58000){
						$exam_code = 58;
					}elseif($exam['exam_code'] == 1600 || $exam['exam_code'] == 16000){
						$exam_code = 160;
					}elseif($exam['exam_code'] == 200){
						$exam_code = 20;
					}elseif($exam['exam_code'] == 1770 || $exam['exam_code'] == 17700){
						$exam_code =177;
					}elseif ($exam['exam_code'] == 590){
						$exam_code = 59;
					}elseif ($exam['exam_code'] == 810){
						$exam_code = 81;
					}elseif ($exam['exam_code'] == 1750){
						$exam_code = 175;
					}else{
						$exam_code = $exam['exam_code'];
					}
					$scribe_flag = $exam['scribe_flag'];
					// Condition for DISA and CSIC Exam Application
					if($exam_code == '990' || $exam_code == '993'){
						$part_no = 1;
						$exam_mode = 'O';
						$syllabus_code = 'R';
						$scribe_flag = 'N';
					}
					else if($exam_code == '1016')
                    {
                        $part_no = 1;
                        $syllabus_code = 'R';
                    }
					$place_of_work = '';
					$pin_code_place_of_work = '';
					$state_place_of_work = '';
					$city = '';
					$branch = '';
					$branch_name = '';
					$state = '';
					$pincode = '';
					//$exam['elected_sub_code']!=0
					//if($exam_code == 60 || $exam_code == 21) // For CAIIB & JAIIB
					if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == $this->config->item('examCodeCaiibElective68') || $exam_code == $this->config->item('examCodeCaiibElective69') || $exam_code == $this->config->item('examCodeCaiibElective70') || $exam_code == $this->config->item('examCodeCaiibElective71') || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) // For CAIIB, CAIIB Elective & JAIIB, above line commented and this line added by Bhagwan Sahane, on 06-10-2017
					{
						if($exam['state'])
						{	$state = $exam['state']; }
						if($exam['pincode'])
						{	$pincode = $exam['pincode']; }
						if(strlen($exam['city']) > 30)
						{	$city = substr($exam['city'],0,29);		}
						else
						{	$city = $exam['city'];	}
						if($exam['editedon'] < "2016-12-29 00:00:00")
						{
							$branch = $exam['branch'];
						}
						else if($exam['editedon'] >= "2016-12-29")
						{
							if(is_numeric($exam['office']))
							{
								if($exam['branch']!='')
									$branch = $exam['branch'];
								else
									$branch = $city;
							}
							else
							{
								if($exam['branch']!='')
									$branch = $exam['branch'];
								else
									$branch = $exam['office'];
							}
						}
						if($branch == '')
						{
							$branch = $city;
						}
						if(strlen($branch) > 20)
						{	$branch_name = substr($branch,0,19); }
						else
						{	$branch_name = $branch;	}
						if($exam['place_of_work'])
							$place_of_work = $exam['place_of_work'];
						else
							$place_of_work =  $branch_name;
						if($exam['pin_code_place_of_work'])
							$pin_code_place_of_work = $exam['pin_code_place_of_work'];
						else
							$pin_code_place_of_work =  $pincode;
						if($exam['state_place_of_work'])
							$state_place_of_work = $exam['state_place_of_work'];
						else
							$state_place_of_work =  $state;
						$elected_sub_code = '';
						if($exam_code == $this->config->item('examCodeCaiib'))
						{	$elected_sub_code = $exam['sub_cd'];	}
						if(strlen($place_of_work) > 30)
						{	$place_of_work = substr($place_of_work,0,29);		}
						else
						{	$place_of_work = $place_of_work;	}
						// Get old exam_code for CAIIB
						/*if($exam_code == 60)
						{
							$ex_code = $this->master_model->getRecords('eligible_master_60_117',array('member_no'=>$exam['regnumber'],'member_type'=>$exam['registrationtype']),'exam_code');
							if(count($ex_code))
							{
								$exam_code = $ex_code[0]['exam_code'];
							}
						}*/
						##code added by pratibha on 2021-10-13
						
					
						
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$scribe_flag.'|'.$exam['elearning_flag'].'|'.$exam['sub_el_count']."\n"; 
						
					}
					else
					{
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$scribe_flag.'|'.$exam['elearning_flag'].'|'.$exam['sub_el_count']."\n"; 
					}
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "Candidate Exam Details File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating Candidate Exam Details File.";
					$i++;
					$exam_cnt++;
				}
				fwrite($fp1, "Total Exam Applications - ".$exam_cnt."\n");
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
			$this->log_model->cronlog("Candidate Exam Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Candidate Exam Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
		
		
	/* Exam E-learning Separate Module Application Cron : Added by sagar on 24-08-2021 */
	public function exam_elearning_spm()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		//$this->log_model->cronlog("Candidate Exam E-learning Separate Module Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date)) { $parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); }
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "exam_cand_elearning_spm_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Candidate Exam E-learning Separate Module Details Cron Start - ".$start_time." *********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$this->db->where('DATE(ms.created_on)',$yesterday);
			/* $this->db->where('DATE(ms.created_on) >= ','2021-09-01');
			$this->db->where('DATE(ms.created_on) <= ','2021-09-30');
			
			$mem = array(8600018045037,1772622964219,5760959428613,3737240849619,7150490671717,0318730408117,5814730234427,9959876734537,4556305184415,7917921980313,4089925678133,2853212548239,2313073472419,1768517173325,4178387242427,2882542957533,9773500504527,4043962661819,8565433964917,8972037875535,7296481335633,6691835326017,8416961467327,7347431783029,8472983364025,8313166851223,5015256409317,6699532210223,5302859815733,9258468936817,4418304273039,7432629358819,7612226179613,0317746849835,7804805453127,9103073428823,7545250928719,6123555589929,6220817636939,8269971980319,8759181853137,6697588915927,0223199796417,4609342425413,7356787598125,3240900585735,5491870467837,0934555616039,8422534167215,0041702189419,0313936483037,9201317286633,2462426644029,2880582215613,3716629150433,4501692135319,3782429432123,7858893078139,6269216965733,1308794501037,3687251083323,0995830734739,7969883109135,8426464929917,6176003534525,5876053618133,0187082322713,3825771650237,0757424687537,4605652929315,8425252247835,6229501336823,1901226780939,5058380377917,5859223017535,8943453052219,6046660278437,4751212399437,3349416405137,5767506292039,5597305618929,8753363003425,1786022691525,3731834698419,6450223890637,7396662379517,1952618510617,5348992751229,6122119642619,1116002273429,5817388074717,2388448928339,7585782861727,2759356399213,6758134792923,8267217251939,1520994159215,4464929596427,6744522902213,1628270821833,6236012831525,5853377707917,2930627540017,4376529306139,4746789717835,1764018565325,0044902573337,6788136410413,6122640703223,0507071216633,9332681477819,0557548179135,3900592262719,8564403659823,9252235213315,8046174104527,2694641462835,4882580176219,1674480047625,5245434860627,0041133739113,7683316996029,1813865237633,5784918184237,6089189153939,6894160935315,2991469122237,1545464445939,6692660380527,0986040033823,3389475321319,3635104895025,6364286030017,1008466486215);
			$this->db->where_in('ms.transaction_no' , $mem);
			
			$select = 'ms.el_sub_id, ms.regid, ms.subject_description, ms.fee_amount, ms.sgst_amt,  ms.cgst_amt, ms.igst_amt, ms.cs_tot, ms.igst_tot, ms.updated_on, pt.transaction_no, ms.exam_code, ms.subject_code, pt.member_regnumber, pt.member_exam_id, pt.amount, DATE_FORMAT(pt.date,"%Y-%m-%d")date, pt.receipt_no, pt.ref_id, pt.status, er.regnumber, er.registrationtype, er.state';
			$this->db->join('payment_transaction pt','pt.receipt_no = ms.receipt_no','LEFT');
			$this->db->join('spm_elearning_registration er','er.regnumber = ms.regnumber','LEFT'); 
			$can_exam_data = $this->Master_model->getRecords('spm_elearning_member_subjects ms',array('ms.status'=>'1', 'pt.pay_type'=>'20', 'pt.status'=>'1','er.isactive'=>'1'),$select); */
			
			$sql = $this->db->query("SELECT `ms`.`el_sub_id`, `ms`.`regid`, `ms`.`subject_description`, `ms`.`fee_amount`, `ms`.`sgst_amt`, `ms`.`cgst_amt`, `ms`.`igst_amt`, `ms`.`cs_tot`, `ms`.`igst_tot`, `ms`.`updated_on`, `pt`.`transaction_no`, `ms`.`exam_code`, `ms`.`subject_code`, `pt`.`member_regnumber`, `pt`.`member_exam_id`, `pt`.`amount`, DATE_FORMAT(pt.date, '%Y-%m-%d')date, `pt`.`receipt_no`, `pt`.`ref_id`, `pt`.`status`, `er`.`regnumber`, `er`.`registrationtype`, `er`.`state` FROM `spm_elearning_member_subjects` `ms` LEFT JOIN `payment_transaction` `pt` ON `pt`.`receipt_no` = `ms`.`receipt_no` LEFT JOIN `spm_elearning_registration` `er` ON `er`.`regnumber` = `ms`.`regnumber` WHERE  `ms`.`transaction_no` IN(8555841509129,1267678432935,8181787945825,3269459598227,1438415715513,6640082735925,7054835132617,4142451738135,5017093960129,8944697211715,2664206426113,8424401126517,4748236098917,6887544163123,0704319482435,9359817423037,7803945800635,9725640998323,7483247824019,9808080099629,9153966114423,4565554709637,6507654312435,3029154428037,1768577400627,2453471418539,5860284926235,5819044692013,3210990200715,0840758330113,6413561345939,0601227525933,6890038282417,7576508070017,1951384350127,2369335925425,8465530052229,6740211182035,4833253100413,6920569850435,8700714132717,6737536155317,9257077986417,2428901618039) AND `ms`.`status` = '1' AND `pt`.`pay_type` = '20' AND `pt`.`status` = '1' AND `er`.`isactive` = '1'");
			
			
			//echo $this->db->last_query(); exit; 
			$can_exam_data = $sql->result_array();
			if(count($can_exam_data))
			{
				$i = 1;
				$exam_cnt = 0;
				foreach($can_exam_data as $exam)
				{
					$data = '';
					
					$trans_date = '';
					if($exam['date'] != '0000-00-00') { $trans_date = date('d-M-Y',strtotime($exam['date'])); }
					
					$data .= ''.$exam['el_sub_id'].'|'.$exam['regid'].'|'.$exam['regnumber'].'|'.$exam['transaction_no'].'|'.$exam['receipt_no'].'|'.$exam['exam_code'].'|'.$exam['subject_code'].'|'.$exam['subject_description'].'|'.$exam['fee_amount'].'|'.$exam['sgst_amt'].'|'.$exam['cgst_amt'].'|'.$exam['igst_amt'].'|'.$exam['cs_tot'].'|'.$exam['igst_tot'].'|'.$exam['status'].'|'.$trans_date.'|'.$exam['updated_on']."\n";  
					
					$exam_file_flg = fwrite($fp, $data);
					
					if($exam_file_flg){ $success['cand_exam'] = "Candidate Exam E-learning Separate Module Details File Generated Successfully."; }
					else { $error['cand_exam'] = "Error While Generating Candidate Exam E-learning Separate Module Details File."; }
					
					$i++;
					$exam_cnt++;
				}
				
				fwrite($fp1, "Total Exam E-learning Separate Module Applications - ".$exam_cnt."\n");
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
			//$this->log_model->cronlog("Candidate Exam E-learning Separate Module Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Candidate Exam E-learning Separate Module Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}	
		
		
	/* Exam Application Cron*/
	public function exam_cross()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
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
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Candidate Exam Details Cron Start - ".$start_time." *********** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			$yesterday = '2020-11-05';
			$not_exam_codes = array('991','1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014'); // Not becoz this exam code we send exam date from other crons CSC and remote
			$mem = array(5459171,
5459172,
5459191);
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,a.elearning_flag,a.sub_el_count';
		//$this->db->LIKE('b.date', '2020-12-');
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
			$this->db->where_not_in('a.exam_code',$not_exam_codes);
			//$this->db->where_in('a.exam_period','915');
			$this->db->where_in('a.id' , $mem);
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array('status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'pay_type'=>2),$select);
			//' DATE(a.created_on)'=>$yesterday,
			if(count($can_exam_data))
			{
				$i = 1;
				$exam_cnt = 0;
				foreach($can_exam_data as $exam)
				{
					$data = '';
					//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG
					$exam_mode = 'O';
					if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
					else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
					else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
					$syllabus_code = '';
					$part_no = '';
					$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
					if(count($subject_data)>0)
					{
						$syllabus_code = $subject_data[0]['syllabus_code'];
						$part_no = $subject_data[0]['part_no'];
					}
					$trans_date = '';
					if($exam['date'] != '0000-00-00')
					{
						$trans_date = date('d-M-Y',strtotime($exam['date']));
					}
					$exam_period = '';
					if($exam['examination_date'] != '' && $exam['examination_date'] != "0000-00-00")
					{
						$ex_period = $this->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam['examination_date']));
						if(count($ex_period))
						{
							$exam_period = $ex_period[0]['period'];	
						}
					}
					else{	$exam_period = $exam['exam_period'];	}
					$exam_code = '';
					if($exam['exam_code'] == 340 || $exam['exam_code'] == 3400 || $exam['exam_code'] == 34000)
					{
					 	$exam_code = 34;
					}elseif($exam['exam_code'] == 580 || $exam['exam_code'] == 5800 || $exam['exam_code'] == 58000){
						$exam_code = 58;
					}elseif($exam['exam_code'] == 1600 || $exam['exam_code'] == 16000){
						$exam_code = 160;
					}elseif($exam['exam_code'] == 200){
						$exam_code = 20;
					}elseif($exam['exam_code'] == 1770 || $exam['exam_code'] == 17700){
						$exam_code =177;
					}elseif ($exam['exam_code'] == 590){
						$exam_code = 59;
					}elseif ($exam['exam_code'] == 810){
						$exam_code = 81;
					}elseif ($exam['exam_code'] == 1750){
						$exam_code = 175;
					}else{
						$exam_code = $exam['exam_code'];
					}
					$scribe_flag = $exam['scribe_flag'];
					// Condition for DISA and CSIC Exam Application
					if($exam_code == '990' || $exam_code == '993'){
						$part_no = 1;
						$exam_mode = 'O';
						$syllabus_code = 'R';
						$scribe_flag = 'N';
					}
					$place_of_work = '';
					$pin_code_place_of_work = '';
					$state_place_of_work = '';
					$city = '';
					$branch = '';
					$branch_name = '';
					$state = '';
					$pincode = '';
					//$exam['elected_sub_code']!=0
					//if($exam_code == 60 || $exam_code == 21) // For CAIIB & JAIIB
					if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == $this->config->item('examCodeCaiibElective68') || $exam_code == $this->config->item('examCodeCaiibElective69') || $exam_code == $this->config->item('examCodeCaiibElective70') || $exam_code == $this->config->item('examCodeCaiibElective71') || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) // For CAIIB, CAIIB Elective & JAIIB, above line commented and this line added by Bhagwan Sahane, on 06-10-2017
					{
						if($exam['state'])
						{	$state = $exam['state']; }
						if($exam['pincode'])
						{	$pincode = $exam['pincode']; }
						if(strlen($exam['city']) > 30)
						{	$city = substr($exam['city'],0,29);		}
						else
						{	$city = $exam['city'];	}
						if($exam['editedon'] < "2016-12-29 00:00:00")
						{
							$branch = $exam['branch'];
						}
						else if($exam['editedon'] >= "2016-12-29")
						{
							if(is_numeric($exam['office']))
							{
								if($exam['branch']!='')
									$branch = $exam['branch'];
								else
									$branch = $city;
							}
							else
							{
								if($exam['branch']!='')
									$branch = $exam['branch'];
								else
									$branch = $exam['office'];
							}
						}
						if($branch == '')
						{
							$branch = $city;
						}
						if(strlen($branch) > 20)
						{	$branch_name = substr($branch,0,19); }
						else
						{	$branch_name = $branch;	}
						if($exam['place_of_work'])
							$place_of_work = $exam['place_of_work'];
						else
							$place_of_work =  $branch_name;
						if($exam['pin_code_place_of_work'])
							$pin_code_place_of_work = $exam['pin_code_place_of_work'];
						else
							$pin_code_place_of_work =  $pincode;
						if($exam['state_place_of_work'])
							$state_place_of_work = $exam['state_place_of_work'];
						else
							$state_place_of_work =  $state;
						$elected_sub_code = '';
						if($exam_code == $this->config->item('examCodeCaiib'))
						{	$elected_sub_code = $exam['elected_sub_code'];	}
						if(strlen($place_of_work) > 30)
						{	$place_of_work = substr($place_of_work,0,29);		}
						else
						{	$place_of_work = $place_of_work;	}
						// Get old exam_code for CAIIB
						/*if($exam_code == 60)
						{
							$ex_code = $this->master_model->getRecords('eligible_master_60_117',array('member_no'=>$exam['regnumber'],'member_type'=>$exam['registrationtype']),'exam_code');
							if(count($ex_code))
							{
								$exam_code = $ex_code[0]['exam_code'];
							}
						}*/
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$scribe_flag.'|'.$exam['elearning_flag'].'|'.$exam['sub_el_count']."\n"; 
					}
					else
					{
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$scribe_flag.'|'.$exam['elearning_flag'].'|'.$exam['sub_el_count']."\n"; 
					}
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "Candidate Exam Details File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating Candidate Exam Details File.";
					$i++;
					$exam_cnt++;
				}
				fwrite($fp1, "Total Exam Applications - ".$exam_cnt."\n");
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
			$this->log_model->cronlog("Candidate Exam Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Candidate Exam Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}	
		
		
		
		/* Exam Application Cron*/
	public function exam_jaiib()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
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
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Candidate Exam Details Cron Start - ".$start_time." *********** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			$yesterday = '2020-11-05';
			$not_exam_codes = array('991','1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014'); // Not becoz this exam code we send exam date from other crons CSC and remote
			$mem = array(500110925);
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,a.elearning_flag';
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
			$this->db->where_not_in('a.exam_code',$not_exam_codes);
			$this->db->where_not_in('a.exam_period','220');
			$this->db->where_in('a.regnumber' , $mem);
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array('status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'pay_type'=>2),$select);
			//' DATE(a.created_on)'=>$yesterday,
			if(count($can_exam_data))
			{
				$i = 1;
				$exam_cnt = 0;
				foreach($can_exam_data as $exam)
				{
					$data = '';
					//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG
					$exam_mode = 'O';
					if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
					else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
					else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
					$syllabus_code = '';
					$part_no = '';
					$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
					if(count($subject_data)>0)
					{
						$syllabus_code = $subject_data[0]['syllabus_code'];
						$part_no = $subject_data[0]['part_no'];
					}
					$trans_date = '';
					if($exam['date'] != '0000-00-00')
					{
						$trans_date = date('d-M-Y',strtotime($exam['date']));
					}
					$exam_period = '';
					if($exam['examination_date'] != '' && $exam['examination_date'] != "0000-00-00")
					{
						$ex_period = $this->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam['examination_date']));
						if(count($ex_period))
						{
							$exam_period = $ex_period[0]['period'];	
						}
					}
					else{	$exam_period = $exam['exam_period'];	}
					$exam_code = '';
					if($exam['exam_code'] == 340 || $exam['exam_code'] == 3400 || $exam['exam_code'] == 34000)
					{
					 	$exam_code = 34;
					}elseif($exam['exam_code'] == 580 || $exam['exam_code'] == 5800 || $exam['exam_code'] == 58000){
						$exam_code = 58;
					}elseif($exam['exam_code'] == 1600 || $exam['exam_code'] == 16000){
						$exam_code = 160;
					}elseif($exam['exam_code'] == 200){
						$exam_code = 20;
					}elseif($exam['exam_code'] == 1770 || $exam['exam_code'] == 17700){
						$exam_code =177;
					}elseif ($exam['exam_code'] == 590){
						$exam_code = 59;
					}elseif ($exam['exam_code'] == 810){
						$exam_code = 81;
					}elseif ($exam['exam_code'] == 1750){
						$exam_code = 175;
					}else{
						$exam_code = $exam['exam_code'];
					}
					$scribe_flag = $exam['scribe_flag'];
					// Condition for DISA and CSIC Exam Application
					if($exam_code == '990' || $exam_code == '993'){
						$part_no = 1;
						$exam_mode = 'O';
						$syllabus_code = 'R';
						$scribe_flag = 'N';
					}
					$place_of_work = '';
					$pin_code_place_of_work = '';
					$state_place_of_work = '';
					$city = '';
					$branch = '';
					$branch_name = '';
					$state = '';
					$pincode = '';
					//$exam['elected_sub_code']!=0
					//if($exam_code == 60 || $exam_code == 21) // For CAIIB & JAIIB
					if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == $this->config->item('examCodeCaiibElective68') || $exam_code == $this->config->item('examCodeCaiibElective69') || $exam_code == $this->config->item('examCodeCaiibElective70') || $exam_code == $this->config->item('examCodeCaiibElective71') || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) // For CAIIB, CAIIB Elective & JAIIB, above line commented and this line added by Bhagwan Sahane, on 06-10-2017
					{
						if($exam['state'])
						{	$state = $exam['state']; }
						if($exam['pincode'])
						{	$pincode = $exam['pincode']; }
						if(strlen($exam['city']) > 30)
						{	$city = substr($exam['city'],0,29);		}
						else
						{	$city = $exam['city'];	}
						if($exam['editedon'] < "2016-12-29 00:00:00")
						{
							$branch = $exam['branch'];
						}
						else if($exam['editedon'] >= "2016-12-29")
						{
							if(is_numeric($exam['office']))
							{
								if($exam['branch']!='')
									$branch = $exam['branch'];
								else
									$branch = $city;
							}
							else
							{
								if($exam['branch']!='')
									$branch = $exam['branch'];
								else
									$branch = $exam['office'];
							}
						}
						if($branch == '')
						{
							$branch = $city;
						}
						if(strlen($branch) > 20)
						{	$branch_name = substr($branch,0,19); }
						else
						{	$branch_name = $branch;	}
						if($exam['place_of_work'])
							$place_of_work = $exam['place_of_work'];
						else
							$place_of_work =  $branch_name;
						if($exam['pin_code_place_of_work'])
							$pin_code_place_of_work = $exam['pin_code_place_of_work'];
						else
							$pin_code_place_of_work =  $pincode;
						if($exam['state_place_of_work'])
							$state_place_of_work = $exam['state_place_of_work'];
						else
							$state_place_of_work =  $state;
						$elected_sub_code = '';
						if($exam_code == $this->config->item('examCodeCaiib'))
						{	$elected_sub_code = $exam['elected_sub_code'];	}
						if(strlen($place_of_work) > 30)
						{	$place_of_work = substr($place_of_work,0,29);		}
						else
						{	$place_of_work = $place_of_work;	}
						// Get old exam_code for CAIIB
						/*if($exam_code == 60)
						{
							$ex_code = $this->master_model->getRecords('eligible_master_60_117',array('member_no'=>$exam['regnumber'],'member_type'=>$exam['registrationtype']),'exam_code');
							if(count($ex_code))
							{
								$exam_code = $ex_code[0]['exam_code'];
							}
						}*/
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$scribe_flag.'|'.$exam['elearning_flag']."\n"; 
					}
					else
					{
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$scribe_flag.'|'.$exam['elearning_flag']."\n"; 
					}
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "Candidate Exam Details File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating Candidate Exam Details File.";
					$i++;
					$exam_cnt++;
				}
				fwrite($fp1, "Total Exam Applications - ".$exam_cnt."\n");
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
			$this->log_model->cronlog("Candidate Exam Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Candidate Exam Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	/* CSC Exam Application Cron*/
	public function csc_exam()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
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
			$file = "csc_exam_cand_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** CSC Exam Details Cron Start - ".$start_time." *********** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			$yesterday ='2020-11-07';
			$id = array(6381565);
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,e.exam_date';
			$this->db->where_in('a.id',$id);
			//$this->db->like('b.date', '2020-12-');
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('member_registration c','a.regnumber = c.regnumber','LEFT'); 
			$this->db->join('admit_card_details e','a.id = e.mem_exam_id','LEFT'); 
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'a.exam_code'=>'991'),$select);
			//' DATE(a.created_on)'=>$yesterday,
			if(count($can_exam_data))
			{
				$i = 1;
				$exam_cnt = 0;
				foreach($can_exam_data as $exam)
				{
					$data = '';
					$exam_mode = 'O';
					if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
					else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
					else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
					$syllabus_code = '';
					$part_no = '';
					$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
					if(count($subject_data)>0)
					{
						$syllabus_code = $subject_data[0]['syllabus_code'];
						$part_no = $subject_data[0]['part_no'];
					}
					$trans_date = '';
					if($exam['date'] != '0000-00-00')
					{
						$trans_date = date('d-M-Y',strtotime($exam['date']));
					}
					$exam_date = '';
					if($exam['exam_date'] != '0000-00-00')
					{
						$exam_date = date('d-M-Y',strtotime($exam['exam_date']));
					}
					$place_of_work = $pin_code_place_of_work = $state_place_of_work = $city = $branch = $branch_name = $state = $pincode = $exam_period = $exam_code = '';
					$exam_period = $exam['exam_period'];
					$exam_code = $exam['exam_code'];
					//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG,EXAM_DATE
					$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$exam['scribe_flag'].'|'.$exam_date."\n";
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "CSC Exam Details File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating CSC Exam Details File.";
					$i++;
					$exam_cnt++;
				}
				fwrite($fp1, "Total CSC Exam Applications - ".$exam_cnt."\n");
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
			$this->log_model->cronlog("CSC Exam Details Cron End", $desc);
			fwrite($fp1, "\n"."********** CSC Exam Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	/* DRA Members Cron */
	public function dra_member(){
		ini_set("memory_limit", "-1");
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
		$cron_file_dir = "./uploads/cronFilesCustom/";
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
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** New DRA Candidate Details Cron Start - ".$start_time." ***************** \n");
			//$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday = '2021-10-11';
			
			$member = array(801920876,801920877,801920878,801920879,801920880,801920881,801920882,801920883,801920884,801920885,801920886,801920887,801920888,801920889,801920890,801920891,801920892,801920893,801920894,801920895,801920896,801920897,801920898,801920899,801920900,801920901,801920902,801920903,801920904,801920905,801920906,801920907,801920908,801920909,801920910,801920911,801920912,801920913,801920914,801920915,801920916,801920917,801920918,801920919,801920920,801920921,801920922,801920923,801920924,801920925,801920926,801920927,801920928,801920929,801920930,801920931,801920932,801920933,801920934,801920935,801920936,801920937,801920938,801920939,801920940,801920941,801920942,801920943,801920944,801920945,801920946,801920947,801920948,801920949,801920950,801920951,801920952,801920953,801920954,801920955,801920956,801920957,801920958,801920959,801920960,801920961,801920962,801920963,801920964,801920965,801920966,801920967,801920968,801920969,801920970,801920971,801920972,801920973,801920974,801920975,801920976,801920977,801920978,801920979,801920980,801920981,801920982,801920983,801920984,801920985,801920986,801920987,801920988,801920989,801920990,801920991,801920992,801920993,801920994,801920995,801920996,801920997,801920998,801920999,801921000,801921001,801921002,801921003,801921004,801921005,801921006,801921007,801921008,801921009,801921010,801921011,801921012,801921013,801921014,801921015,801921016,801921017,801921018,801921019,801921020,801921021,801921022,801921023,801921024,801921025,801921026,801921027,801921028,801921029,801921030,801921031,801921032,801921033,801921034,801921035,801921036,801921037,801921039,801921040,801921041,801921042,801921043,801921044,801921045,801921046,801921047,801921048,801921049,801921050,801921051,801921052,801921053,801921054,801921055,801921056,801921057,801921058,801921059,801921060,801921061,801921062,801921063,801921064,801921065,801921066,801921067,801921068,801921069,801921070,801921071,801921072,801921073,801921074,801921075,801921076,801921077,801921078,801921079,801921080,801921081,801921082,801921083,801921084,801921085,801921086,801921087,801921088,801921089,801921090,801921091,801921092,801921093,801921094,801921095,801921096,801921097,801921098,801921099,801921100,801921101,801921102,801921103,801921104,801921105,801921106,801921107,801921108,801921109,801921110,801921111,801921112,801921113,801921114,801921115,801921116,801921117,801921118,801921119,801921120,801921121,801921122,801921123,801921124,801921125,801921126,801921127,801921128,801921129,801921130,801921131,801921132,801921133,801921134,801921135,801921136,801921137,801921138,801921139,801921140,801921141,801921142,801921143,801921144,801921145,801921146,801921147,801921148,801921149,801921150,801921151,801921152,801921153,801921154,801921155,801921156,801921157,801921158,801921159,801921160,801921161,801921162,801921163,801921164,801921165,801921166,801921167,801921168,801921169,801921170,801921171,801921172,801921173,801921174,801921175,801921176,801921177,801921178,801921179,801921180,801921181,801921182,801921183,801921184,801921185,801921186,801921187,801921188,801921189,801921190,801921191,801921192,801921193,801921194,801921195,801921196,801921197,801921198,801921199,801921200,801921201,801921202,801921203,801921204,801921205,801921206,801921207,801921208,801921209,801921210,801921211,801921212,801921213,801921214,801921215,801921216,801921217,801921218,801921219,801921220,801921221,801921222,801921223,801921224,801921225,801921226,801921227,801921228,801921229,801921230,801921231,801921232,801921233,801921234,801921235,801921236,801921237,801921238,801921239,801921240,801921241,801921242,801921243,801921244,801921245,801921246,801921247,801921248,801921249,801921250,801921251,801921252,801921253,801921254,801921255,801921256,801921257,801921258,801921259,801921260,801921261,801921262,801921263,801921264,801921265,801921266,801921267,801921268,801921269,801921270,801921271,801921272,801921273,801921274,801921275,801921276,801921277,801921278,801921279,801921280,801921281,801921282,801921283,801921284,801921285,801921286,801921287,801921288,801921289,801921290,801921291,801921292,801921293,801921294,801921295,801921296,801921297,801921298,801921299,801921300,801921301,801921302,801921303,801921304,801921305,801921306,801921307,801921308,801921309,801921310,801921311,801921312,801921313,801921314,801921315,801921316,801921317,801921318,801921319,801921320,801921321,801921322,801921323,801921324,801921325,801921326,801921327,801921328,801921329,801921330,801921331,801921332,801921333,801921334,801921335,801921336,801921337,801921338,801921339,801921340,801921341,801921342,801921343,801921344,801921345,801921346,801921347,801921348,801921349,801921350,801921351,801921352,801921353,801921354,801921355,801921356,801921357,801921358,801921359,801921360,801921361,801921362,801921363,801921364,801921365,801921366,801921367,801921368,801921369,801921370,801921371,801921372,801921373,801921374,801921375,801921376,801921377,801921378,801921379,801921380,801921381,801921382,801921383,801921384,801921385,801921386,801921387,801921388,801921389,801921390,801921391,801921392,801921393,801921394,801921395,801921396,801921397,801921398,801921399,801921400,801921401,801921402,801921403,801921404,801921405,801921406,801921407,801921408,801921409,801921410,801921411,801921412,801921413,801921414,801921415,801921416,801921417,801921418,801921419,801921420,801921421,801921422,801921423,801921424,801921425,801921426,801921427,801921428,801921429,801921430,801921431,801921432,801921433,801921434,801921435,801921436,801921437,801921438,801921439,801921440,801921441,801921442,801921443,801921444,801921445,801921446,801921447,801921448,801921449,801921450,801921451,801921452,801921453,801921454,801921455,801921456,801921457,801921458,801921459,801921460,801921461,801921462,801921463,801921464,801921465,801921466,801921467,801921468,801921469,801921470,801921471,801921472,801921473,801921474,801921475,801921476,801921477,801921478,801921479,801921480,801921481,801921482,801921483,801921484,801921485,801921486,801921487,801921488,801921489,801921490,801921491,801921492,801921493,801921494,801921495,801921496,801921497,801921498,801921499,801921500,801921501,801921502,801921503,801921504,801921505,801921506,801921507,801921508,801921509,801921510,801921511,801921512,801921513,801921514,801921515,801921516,801921517,801921518,801921519,801921520,801921521,801921522,801921523,801921524,801921525,801921526,801921527,801921528,801921529,801921530,801921531,801921532,801921533,801921534,801921535,801921536,801921537,801921538,801921539,801921540,801921541,801921542,801921543,801921544,801921545,801921546,801921547,801921548,801921549,801921550,801921551,801921552,801921553,801921554,801921555,801921556,801921557,801921558,801921559,801921560,801921561,801921562,801921563,801921564,801921565,801921566,801921567,801921568,801921569,801921570,801921571,801921573,801921574,801921575,801921576,801921577,801921578,801921579,801921580,801921581,801921582,801921583,801921584,801921585,801921586,801921587,801921588,801921589,801921590,801921591,801921592,801921593,801921594,801921595,801921596,801921597,801921598,801921599,801921600,801921601,801921602,801921603,801921604,801921605,801921606,801921607,801921608,801921609,801921610,801921611,801921612,801921613,801921614,801921615,801921616,801921617,801921618,801921619,801921620,801921621,801921622,801921623,801921624,801921625,801921626,801921627,801921628,801921629,801921630,801921631,801921632,801921633,801921634,801921635,801921636,801921637,801921638,801921639,801921640,801921641,801921642,801921643,801921644,801921645,801921646,801921647,801921648,801921649,801921650,801921651,801921652,801921653,801921654,801921655,801921656,801921657,801921658,801921659,801921660,801921661,801921662,801921663,801921664,801921665,801921666,801921667,801921668,801921669,801921670,801921671,801921672,801921673,801921674,801921675,801921676,801921677,801921678,801921679,801921680,801921681,801921682,801921683,801921684,801921685,801921686,801921687,801921688,801921689,801921690,801921691,801921692,801921693,801921694,801921695,801921696,801921697,801921698,801921699,801921700,801921701,801921702,801921703,801921704,801921705,801921706,801921707,801921708,801921709,801921710,801921711,801921712,801921713,801921714,801921715,801921716,801921717,801921718,801921719,801921720,801921721,801921722,801921723,801921724,801921725,801921726,801921727,801921728,801921729,801921730,801921731,801921732,801921733,801921734,801921735,801921736,801921737,801921738,801921739,801921740,801921741,801921742,801921743,801921744,801921745,801921746,801921747,801921748,801921749,801921750,801921751,801921752,801921753,801921754,801921755,801921756,801921757,801921758,801921759,801921760,801921761,801921762,801921763,801921764,801921765,801921766,801921767,801921768,801921769,801921770,801921771,801921772,801921773,801921774,801921775,801921776,801921777,801921778,801921779,801921780,801921781,801921782,801921783,801921784,801921785,801921786,801921787,801921788,801921790,801921791,801921792,801921793,801921794,801921795,801921796,801921797,801921798,801921799,801921800,801921801,801921802,801921803,801921804,801921805,801921806,801921807,801921808,801921809,801921810,801921811,801921812,801921813,801921814,801921815,801921816,801921817,801921818,801921819,801921820,801921821,801921822,801921823,801921824,801921825,801921826,801921827,801921828,801921829,801921830,801921831,801921832,801921833,801921834,801921835,801921836,801921837,801921838,801921839,801921840,801921841,801921842,801921843,801921844,801921845,801921846,801921847,801921848,801921849,801921850,801921851,801921852,801921853,801921854,801921855,801921856,801921857,801921858,801921859,801921860,801921861,801921862,801921863,801921864,801921865,801921866,801921867,801921868,801921869,801921870,801921871,801921872,801921873,801921874,801921875,801921876,801921877,801921878,801921879,801921880,801921881,801921882,801921883,801921884,801921885,801921886,801921887,801921888,801921889,801921890,801921891,801921892,801921893,801921894,801921895,801921896,801921897,801921898,801921899,801921900,801921901,801921902,801921903,801921904,801921905,801921906,801921907,801921908,801921909,801921910,801921911,801921912,801921913,801921914,801921915,801921916,801921917,801921918,801921919,801921920,801921921,801921922,801921923,801921924,801921925,801921926,801921927,801921928,801921929,801921930,801921931,801921932,801921933,801921934,801921935,801921936,801921937,801921938,801921939,801921940,801921941,801921942,801921943,801921944,801921945,801921946,801921947,801921948,801921949,801921950,801921951,801921952,801921953,801921954,801921955,801921956,801921957,801921958,801921959,801921960,801921961,801921962,801921963,801921964,801921965,801921966,801921967,801921968,801921969,801921970,801921971,801921972,801921973,801921974,801921975,801921976,801921977,801921978,801921979,801921980,801921981,801921982,801921983,801921984,801921985,801921986,801921987,801921988,801921989,801921990,801921991,801921992,801921993,801921994,801921995,801921996,801921997,801921998,801921999,801922000,801922001,801922002,801922003,801922004,801922005,801922006,801922007,801922008,801922009,801922010,801922011,801922012,801922013,801922014,801922015,801922016,801922017,801922018,801922019,801922020,801922021,801922022,801922023,801922024,801922025,801922026,801922027,801922028,801922029,801922030,801922031,801922032,801922033,801922034,801922035,801922036,801922037,801922038,801922039,801922040,801922041,801922042,801922043,801922044,801922045,801922046,801922047,801922048,801922049,801922050,801922051,801922052,801922053,801922054,801922055,801922056,801922057,801922058,801922059,801922060,801922061,801922062,801922063,801922064,801922065,801922066,801922067,801922068,801922069,801922070,801922071,801922072,801922073,801922074,801922075,801922076,801922077,801922078,801922079,801922080,801922081,801922082,801922083,801922084,801922085,801922086,801922087,801922088,801922089,801922090,801922091,801922092,801922093,801922094,801922095,801922096,801922097,801922098,801922099,801922100,801922101,801922102,801922103,801922104,801922105,801922106,801922107,801922108,801922109,801922110,801922111,801922112,801922113,801922114,801922115,801922116,801922117,801922118,801922119,801922120,801922121,801922122,801922123,801922124,801922125,801922126,801922127,801922128,801922129,801922130,801922131,801922132,801922133,801922134,801922135,801922136,801922137,801922138,801922139,801922140,801922141,801922142,801922143,801922144,801922145,801922146,801922147,801922148,801922149,801922150,801922151,801922152,801922153,801922154,801922155,801922156,801922157,801922158,801922159,801922160,801922161,801922162,801922163,801922164,801922165,801922166,801922167,801922168,801922169,801922170,801922171,801922172,801922173,801922174,801922175,801922176,801922177,801922178,801922179,801922180,801922181,801922182,801922183,801922184,801922185,801922186,801922187,801922188,801922189,801922190,801922191,801922192,801922193,801922194,801922195,801922196,801922197,801922198,801922199,801922200,801922201,801922202,801922203,801922204,801922205,801922206,801922207,801922208,801922209,801922210,801922211,801922212,801922213,801922214,801922215,801922216,801922217,801922218,801922219,801922220,801922221,801922222,801922223,801922224,801922225,801922226,801922227,801922228,801922229,801922230,801922231,801922232,801922233,801922234,801922235,801922236,801922237,801922238,801922239,801922240,801922241,801922242,801922243,801922244,801922245,801922246,801922247,801922248,801922249,801922250,801922251,801922252,801922253,801922254,801922255,801922256,801922257,801922258,801922259,801922260,801922261,801922262,801922263,801922264,801922265,801922266,801922267,801922268,801922269,801922270,801922271,801922272,801922273,801922274,801922275,801922276,801922277,801922278,801922279,801922280,801922281,801922282,801922283,801922284,801922285,801922286,801922287,801922288,801922289,801922290,801922291,801922292,801922293,801922294,801922295,801922296,801922297,801922298,801922299,801922300,801922301,801922302,801922303,801922304,801922305,801922306,801922307,801922308,801922309,801922310,801922311,801922312,801922313,801922314,801922315,801922316,801922317,801922318,801922319,801922320,801922321,801922322,801922323,801922324,801922325,801922326,801922327,801922328,801922329,801922330,801922331,801922332,801922333,801922334,801922335,801922336,801922337,801922338,801922339,801922340,801922341,801922342,801922343,801922344,801922345,801922346,801922347,801922348,801922349,801922350,801922351,801922352,801922353,801922354,801922355,801922356,801922357,801922358,801922359,801922360,801922361,801922362,801922363,801922364,801922365,801922366,801922367,801922368,801922369,801922370,801922371,801922372,801922373,801922374,801922375,801922376,801922377,801922378,801922379,801922380,801922381,801922382,801922383,801922384,801922385,801922386,801922387,801922388,801922389,801922390,801922391,801922392,801922393,801922394,801922395,801922396,801922397,801922398,801922399,801922400,801922401,801922402,801922403,801922404,801922405,801922406,801922407,801922408,801922409,801922410,801922411,801922412,801922413,801922414,801922415,801922416,801922417,801922418,801922419,801922420,801922421,801922422,801922423,801922424,801922425,801922426,801922427,801922428,801922429,801922430,801922431,801922432,801922433,801922434,801922435,801922436,801922437,801922438,801922439,801922440,801922441,801922442,801922443,801922444,801922445,801922446,801922447,801922448,801922449,801922450,801922451,801922452,801922453,801922454,801922455,801922456,801922457,801922458,801922459,801922460,801922461,801922462,801922463,801922464,801922465,801922466,801922467,801922468,801922469,801922470,801922471,801922472,801922473,801922474,801922475,801922476,801922477,801922478,801922479,801922480,801922481,801922482,801922483,801922484,801922485,801922486,801922487,801922488,801922489,801922490,801922491,801922492,801922493,801922494,801922495,801922496,801922497,801922498,801922499,801922500,801922501,801922502,801922503,801922504,801922505,801922506,801922507,801922508,801922509,801922510,801922511,801922512,801922513,801922514,801922515,801922516,801922517,801922518,801922519,801922520,801922521,801922522,801922523,801922524,801922525,801922526,801922527,801922528,801922529,801922530,801922531,801922532,801922533,801922534,801922535,801922536,801922537,801922538,801922539,801922540,801922541,801922542,801922543,801922544,801922545,801922546,801922547,801922548,801922549,801922550,801922551,801922552,801922553,801922554,801922555,801922556,801922557,801922558,801922559,801922560,801922561,801922562,801922563,801922564,801922565,801922566,801922567,801922568,801922569,801922570,801922571,801922572,801922573,801922574,801922575,801922576,801922577,801922578,801922579,801922580,801922581,801922582,801922583,801922584,801922585,801922586,801922587,801922588,801922589,801922590,801922591,801922592,801922593,801922594,801922595,801922596,801922597,801922598,801922599,801922600,801922601,801922602,801922603,801922604,801922605,801922606,801922607,801922608,801922609,801922610,801922611,801922612,801922613,801922614,801922615,801922616,801922617,801922618,801922619,801922620,801922621,801922622,801922623,801922624,801922625,801922626,801922627,801922628,801922629,801922630,801922631,801922632,801922633,801922634,801922635,801922636,801922637,801922638,801922639,801922640,801922641,801922642,801922643,801922644,801922645,801922646,801922647,801922648,801922649,801922650,801922651,801922652,801922653,801922654,801922655,801922656,801922657,801922658,801922659,801922660,801922661,801922662,801922663,801922664,801922665,801922666,801922667,801922668,801922669,801922670,801922671,801922672,801922673,801922674,801922675,801922676,801922677,801922678,801922679,801922680,801922681,801922682,801922683,801922684,801922685,801922686,801922687,801922688,801922689,801922690,801922691,801922692,801922693,801922694,801922695,801922696,801922697,801922698,801922699,801922700,801922701,801922702,801922703,801922704,801922705,801922706,801922707,801922708,801922709,801922710,801922711,801922712,801922713,801922714,801922715,801922716,801922717,801922718,801922719,801922720,801922721,801922722,801922723,801922724,801922725,801922726,801922727,801922728,801922729,801922730,801922731,801922732,801922733,801922734,801922735,801922736,801922737,801922738,801922739,801922740,801922741,801922742,801922743,801922744,801922745,801922746,801922747,801922748,801922749,801922750,801922751,801922752,801922753,801922754,801922755,801922756,801922757,801922758,801922759,801922760,801922761,801922762,801922763,801922764,801922765,801922766,801922767,801922768,801922769,801922770,801922771,801922772,801922773,801922774,801922775,801922776,801922777,801922778,801922779,801922780,801922781,801922782,801922783,801922784,801922785,801922786,801922787,801922788,801922789,801922790,801922791,801922792,801922793,801922794,801922795,801922796,801922797,801922798,801922799,801922800,801922801,801922802,801922803,801922804,801922805,801922806,801922807,801922808,801922809,801922810,801922811,801922812,801922813,801922814,801922815,801922816,801922817,801922818,801922819,801922820,801922821,801922822,801922823,801922824,801922825,801922826,801922827,801922828,801922829,801922830,801922831,801922832,801922833,801922834,801922835,801922836,801922837,801922838,801922839,801922840,801922841,801922842,801922843,801922844,801922845,801922846,801922847,801922848,801922849,801922850,801922851,801922852,801922853,801922854,801922855,801922856,801922857,801922858,801922859,801922860,801922861,801922862,801922863,801922864,801922865,801922866,801922867,801922868,801922869,801922870,801922871,801922872,801922873,801922874,801922875,801922876,801922877,801922878,801922879,801922880,801922881,801922882,801922883,801922884,801922885,801922886,801922887,801922888,801922889,801922890,801922891,801922892,801922893,801922894);
			
			$this->db->select("namesub, firstname, middlename, lastname, regnumber, gender, scannedphoto, scannedsignaturephoto, idproofphoto, training_certificate, quali_certificate, qualification,registrationtype, address1, address2, city, district, pincode, state, dateofbirth, email, stdcode, phone, mobile, aadhar_no, idproof, a.transaction_no, DATE(a.date) date, DATE(a.updated_date) updated_date, a.UTR_no, a.amount, a.gateway, d.image_path, d.registration_no");
			$this->db->where("'d.isdeleted'= 0 AND c.pay_status = 1 ");
			// $this->db->where("a.exam_period = 702 ");
			$this->db->join('dra_member_payment_transaction b','a.id = b.ptid','LEFT');
			$this->db->join('dra_member_exam c','b.memexamid = c.id','LEFT');
			$this->db->join('dra_members d','d.regid = c.regid','LEFT');
			$this->db->where_in('d.regnumber', $member);
			
			$this->db->where(" 'isdeleted'= 0 AND a.status = 1");/* DATE(a.updated_date) = '".$yesterday."' AND */
			//$this->db->where("DATE(a.updated_date) = '".$yesterday."' AND 'isdeleted'= 0 AND a.status = 1 ");
			
			$new_dra_reg = $this->Master_model->getRecords('dra_payment_transaction a');
      //   echo  $this->db->last_query(); exit;
		/*AND re_attempt = 0
		$this->db->where("( DATE(a.date) = '".$yesterday."'  OR DATE(a.updated_date) = '".$yesterday."') AND 'isdeleted'= 0 AND a.status = 1");*/
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
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				$tcimg_cnt = 0;
				$dcimg_cnt = 0;
				foreach($new_dra_reg as $dra)
				{
					$gender = '';
					if($dra['gender'] == 'male')	{ $gender = 'M';}
					else if($dra['gender'] == 'female')	{ $gender = 'F';}
					$dc_file = '';
					$tc_file = '';
					$photo_file = '';
					$sign_file = '';
					$idproof_file = '';
					if($dir_flg)
					{
						if($dra['quali_certificate'])
						{
							$file_arr = explode('.',$dra['quali_certificate']);
							$dc_file = "dc_".$dra['regnumber'].".".end($file_arr);
						}
						if($dra['training_certificate']) 
						{
							$file_arr1 = explode('.',$dra['training_certificate']);
							$tc_file = "tc_".$dra['regnumber'].".".end($file_arr1);
						}
						// Photograph
						if($dra['scannedphoto'] != '' && is_file("./uploads/iibfdra/".$dra['scannedphoto']))
						{
							$photo_file = $dra['scannedphoto'];
							copy("./uploads/iibfdra/".$dra['scannedphoto'],$directory."/".$dra['scannedphoto']);
							$photo_to_add = $directory."/".$dra['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$dra['scannedphoto']." (".$dra['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."photo/"."p_".$dra['registration_no'].".jpg"))
						{
							$photo_file = "p_".$dra['regnumber'].".jpg";
							copy("./uploads/".$dra['image_path']."photo/"."p_".$dra['registration_no'].".jpg",$directory."/".$photo_file);
							$photo_to_add = $directory."/".$photo_file;
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$photo_file." (".$dra['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						else
						{
							fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$dra['scannedphoto']." (".$dra['regnumber'].")\n");	
						}
						// Signature
						if(is_file("./uploads/iibfdra/".$dra['scannedsignaturephoto']))
						{
							$sign_file = $dra['scannedsignaturephoto'];
							copy("./uploads/iibfdra/".$dra['scannedsignaturephoto'],$directory."/".$dra['scannedsignaturephoto']);
							$sign_to_add = $directory."/".$dra['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$dra['scannedsignaturephoto']." (".$dra['regnumber'].")\n");	
							}
							else
								$sign_cnt++;
						}
						else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."signature/"."s_".$dra['registration_no'].".jpg"))
						{
							$sign_file = "s_".$dra['regnumber'].".jpg";
							copy("./uploads/".$dra['image_path']."signature/"."s_".$dra['registration_no'].".jpg",$directory."/".$sign_file);
							$sign_to_add = $directory."/".$sign_file;
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$sign_file." (".$dra['regnumber'].")\n");	
							}
							else
								$sign_cnt++;
						}
						else
						{
							fwrite($fp1, "**ERROR** - Signature does not exist  - ".$dra['scannedsignaturephoto']." (".$dra['regnumber'].")\n");	
						}
						//ID Proof
						if(is_file("./uploads/iibfdra/".$dra['idproofphoto']))
						{
							$idproof_file = $dra['idproofphoto'];
							copy("./uploads/iibfdra/".$dra['idproofphoto'],$directory."/".$dra['idproofphoto']);
							$proof_to_add = $directory."/".$dra['idproofphoto'];
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							if(!$idproof_zip_flg)
							{
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$dra['idproofphoto']." (".$dra['regnumber'].")\n");	
							}
							else 
								$idproof_cnt++;
						}
						else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."idproof/"."pr_".$dra['registration_no'].".jpg"))
						{
							$idproof_file = "pr_".$dra['regnumber'].".jpg";
							copy("./uploads/".$dra['image_path']."idproof/"."pr_".$dra['registration_no'].".jpg",$directory."/".$idproof_file);
							$proof_to_add = $directory."/".$idproof_file;
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							if(!$idproof_zip_flg)
							{
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$idproof_file." (".$dra['regnumber'].")\n");	
							}
							else
								$idproof_cnt++;
						}
						else
						{
							fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$dra['idproofphoto']." (".$dra['regnumber'].")\n");	
						}
						// TC
						if($tc_file != '' && is_file("./uploads/iibfdra/".$dra['training_certificate']))
						{
							//$tc_file = $dra['training_certificate'];
							copy("./uploads/iibfdra/".$dra['training_certificate'],$directory."/".$tc_file);
							$tc_to_add = $directory."/".$tc_file;
							$new_tc = substr($tc_to_add,strrpos($tc_to_add,'/') + 1);
							$tc_zip_flg = $zip->addFile($tc_to_add,$new_tc);
							if(!$tc_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Training Certificate not added to zip  - ".$dra['training_certificate']." (".$dra['regnumber'].")\n");	
							}
							else 
								$tcimg_cnt++;
						}
						else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."training_cert/"."traing_".$dra['registration_no'].".jpg"))
						{
							$tc_file = "tc_".$dra['regnumber'].".jpg";
							copy("./uploads/".$dra['image_path']."training_cert/"."traing_".$dra['registration_no'].".jpg",$directory."/".$tc_file);
							$tc_to_add = $directory."/".$tc_file;
							$new_tc = substr($tc_to_add,strrpos($tc_to_add,'/') + 1);
							$tc_zip_flg = $zip->addFile($tc_to_add,$new_tc);
							if(!$tc_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Training Certificate not added to zip  - ".$tc_file." (".$dra['regnumber'].")\n");	
							}
							else
								$tcimg_cnt++;
						}
						else
						{
							//fwrite($fp1, "**ERROR** - Training Certificate does not exist  - ".$dra['training_certificate']." (".$dra['regnumber'].")\n");	
						}
						// DC
						if($dc_file && is_file("./uploads/iibfdra/".$dra['quali_certificate']))
						{
							copy("./uploads/iibfdra/".$dra['quali_certificate'],$directory."/".$dc_file);
							$dc_to_add = $directory."/".$dc_file;
							$new_dc = substr($dc_to_add,strrpos($dc_to_add,'/') + 1);
							$dc_zip_flg = $zip->addFile($dc_to_add,$new_dc);
							if(!$dc_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Degree Certificate not added to zip  - ".$dra['quali_certificate']." (".$dra['regnumber'].")\n");	
							}
							else 
								$dcimg_cnt++;
						}
						else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."degree_cert/"."degre_".$dra['registration_no'].".jpg"))
						{
							$dc_file = "dc_".$dra['regnumber'].".jpg";
							copy("./uploads/".$dra['image_path']."degree_cert/"."degre_".$dra['registration_no'].".jpg",$directory."/".$dc_file);
							$dc_to_add = $directory."/".$dc_file;
							$new_dc = substr($dc_to_add,strrpos($dc_to_add,'/') + 1);
							$dc_zip_flg = $zip->addFile($dc_to_add,$new_dc);
							if(!$dc_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Degree Certificate not added to zip  - ".$dc_file." (".$dra['regnumber'].")\n");	
							}
							else
								$dcimg_cnt++;
						}
						else
						{
							fwrite($fp1, "**ERROR** - Degree Certificate does not exist  - ".$dra['quali_certificate']." (".$dra['regnumber'].")\n");
						}
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg || $tc_zip_flg || $dc_zip_flg)
						{
							$success['zip'] = "New DRA Candidate Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating New DRA Candidate Images Zip";
						}
					}
					/*$photo 		= DRA_FILE_PATH.$photo_file;
					$signature 	= DRA_FILE_PATH.$sign_file;
					$idproofimg = DRA_FILE_PATH.$idproof_file;
					$tcimg 		= DRA_FILE_PATH.$tc_file;
					$dcimg 		= DRA_FILE_PATH.$dc_file;*/
					$photo 	= "";
					if($photo_file!=""){
						$photo 	= DRA_FILE_PATH.$photo_file;
					}
					$signature 	= "";
					if($sign_file!=""){
						$signature 	= DRA_FILE_PATH.$sign_file;
					}
					$idproofimg 	= "";
					if($idproof_file!=""){
						$idproofimg = DRA_FILE_PATH.$idproof_file;
					}
					$tcimg 	= "";
					if($tc_file!=""){
						$tcimg 	= DRA_FILE_PATH.$tc_file;
					}
					$dcimg 	= "";
					if($dc_file!=""){
						$dcimg 	= DRA_FILE_PATH.$dc_file;
					}
					$qualification = '';
					$specialisation = '';
					switch($dra['qualification'])
					{
						/*case "tenth"	: 	$qualification = 1;
											$specialisation = 7;
											break;
						case "twelth"	: 	$qualification = 1;
											$specialisation = 8;
											break;
						case "graduate"	: 	$qualification = 3;
											$specialisation = 2;
											break;*/
						/* Changes updated as per 'Exam_Application_QualificationCode' by Dattatreya sir on 09-01-2020 */					
						case "tenth"	: 	$qualification = 'UG';
											$specialisation = 7;
											break;
						case "twelth"	: 	$qualification = 'UG';
											$specialisation = 8;
											break;
						case "graduate"	: 	$qualification = 'G';
											$specialisation = 2;
											break;					
					}
					//801135663|NM|MR|ADITYA|NAGNATH|SHIDDHE|MR ADITYA NAGNATH SHIDDHE|862|SHUKRAWAR PETH|SOLAPUR|SOLAPUR|||413001|MAH|24-Mar-91|M|1|8||||||shreekantyadwad@yahoo.co.in|||9096439943|2||6647590215714|29-Sep-16|12075|6647590215714|Y|||/webonline/fromweb/images/dra/p_801135663.jpg|/webonline/fromweb/images/dra/s_801135663.jpg|/webonline/fromweb/images/dra/pr_801135663.jpg|/webonline/fromweb/images/dra/tc_801135663.jpg|/webonline/fromweb/images/dra/dc_801135663.jpg|||||
					//MEM_MEM_NO,MEM_MEM_TYP,MEM_TLE,MEM_NAM_1,MEM_NAM_2,MEM_NAM_3,ID_CARD_NAME,MEM_ADR_1,MEM_ADR_2,MEM_ADR_3,MEM_ADR_4,MEM_ADR_5,MEM_ADR_6,MEM_PIN_CD,MEM_STE_CD,MEM_DOB,MEM_SEX_CD,MEM_QLF_GRD,MEM_QLF_CD,MEM_INS_CD,BRANCH,MEM_DSG_CD,MEM_BNK_JON_DT ,FI_YEAR_ID,      EMAIL,STD_R,PHONE_R,MOBILE,ID_TYPE,ID_NO,BDRNO,TRN_DATE,TRN_AMT,INSTRUMENT_NO,AR_FLG,FILE_ID,ZONE_CD,filename_1 filler char(400),PHOTO LOBFILE (filename_1) TERMINATED BY EOF,filename_2 filler char(400),SIGNATURE LOBFILE (filename_2) TERMINATED BY EOF,filename_3 filler char(400),ID LOBFILE (filename_3) TERMINATED BY EOF,filename_4 filler char(400),TRG_CERT LOBFILE (filename_4) TERMINATED BY EOF,filename_5 filler char(400),QLF_CERT LOBFILE (filename_5) TERMINATED BY EOF,USR_ID,LOT_NO,LOT_TYP,UPD_DT
					$displayname = $dra['namesub'].' '.$dra['firstname'].' '.$dra['middlename'].' '.$dra['lastname'];
					$dob = '';
					if($dra['dateofbirth'] != '0000-00-00')
					{
						$dob = date('d-M-y',strtotime($dra['dateofbirth']));
					}
					if(strlen($dra['stdcode']) > 10)
					{	$std_code = substr($dra['stdcode'],0,9);	}
					else
					{	$std_code = $dra['stdcode'];	}
					
					if(strlen(str_replace("\&quot;", "", $dra['address1'])) > 30)
					{	$address1 = substr(str_replace("\&quot;", "", $dra['address1']),0,29);	}
					else
					{	$address1 = str_replace("\&quot;", "", $dra['address1']);	} 
				
					if(strlen(str_replace("\&quot;", "", $dra['address2'])) > 30)
					{	$address2 = substr(str_replace("\&quot;", "", $dra['address2']),0,29);	}
					else
					{	$address2 = str_replace("\&quot;", "", $dra['address2']);	}
				
					if(strlen($dra['city']) > 30)
					{	$city = substr($dra['city'],0,29);	}
					else
					{	$city = $dra['city'];	}
					if(strlen($dra['district']) > 30)
					{	$district = substr($dra['district'],0,29);	}
					else
					{	$district = $dra['district'];	}
					$trans_date = '';
					$transaction_no = '';
					if($dra['gateway']==1)
					{	
						$transaction_no = $dra['UTR_no'];
						$trans_date = date('d-M-y',strtotime($dra['updated_date']));
					}
					else if($dra['gateway']==2)
					{	
						$transaction_no = $dra['transaction_no'];
						$trans_date = date('d-M-y',strtotime($dra['date']));	
					}
					$data .= ''.$dra['regnumber'].'|'.$dra['registrationtype'].'|'.$dra['namesub'].'|'.$dra['firstname'].'|'.$dra['middlename'].'|'.$dra['lastname'].'|'.$displayname.'|'.$address1.'|'.$address2.'|'.$city.'|'.$district.'|||'.$dra['pincode'].'|'.$dra['state'].'|'.$dob.'|'.$gender.'|'.$qualification.'|'.$specialisation.'||||||'.$dra['email'].'|'.$std_code.'|'.$dra['phone'].'|'.$dra['mobile'].'|'.$dra['idproof'].'||'.$transaction_no.'|'.$trans_date.'|'.$dra['amount'].'|'.$transaction_no.'|Y|||'.$photo.'|'.$signature.'|'.$idproofimg.'|'.$tcimg.'|'.$dcimg.'|||||'.$dra['aadhar_no']."\n";
					$i++;
					$mem_cnt++;
				}
				fwrite($fp1, "\n"."Total New DRA  Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				fwrite($fp1, "\n"."Total Training Certificates Added = ".$tcimg_cnt."\n");
				fwrite($fp1, "\n"."Total Degree Certificates Added = ".$dcimg_cnt."\n");
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
			$this->log_model->cronlog("New DRA Candidate Details Cron End", $desc);
			fwrite($fp1, "\n"."********** New DRA Candidate Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	
	public function dra_member_edited()
	{
		ini_set("memory_limit", "-1");
		
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
		//xxx $cron_file_dir = "./uploads/cronfiles_pg/";
		$cron_file_dir = "./uploads/cronFilesCustom/";
		
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		//xxx $this->log_model->cronlog("DRA Edited Candidate Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "dra_edited_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** DRA Edited Candidate Details Cron Start - ".$start_time." ***************** \n");
			
			//$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday = '2021-08-12';
			
			$this->db->select("d.*");
			$this->db->where("DATE(d.editedby) = '".$yesterday."' AND d.isdeleted = 0 ");//AND d.new_reg = 1
			$new_dra_reg = $this->Master_model->getRecords('dra_members d');
			//echo $this->db->last_query(); exit;
			
			if(count($new_dra_reg))
			{
				$data = '';
				$dirname = "dra_edited_regd_image_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory))
				{
				 	array_map('@unlink', glob($directory."/*.*"));
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
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				$tcimg_cnt = 0;
				$dcimg_cnt = 0;
				foreach($new_dra_reg as $dra)
				{
					$gender = '';
					if($dra['gender'] == 'male')	{ $gender = 'M';}
					else if($dra['gender'] == 'female')	{ $gender = 'F';}
					
					$dc_file = '';
					$tc_file = '';
					$photo_file = '';
					$sign_file = '';
					$idproof_file = '';
					if($dir_flg)
					{
						if($dra['quali_certificate'])
						{
							$file_arr = explode('.',$dra['quali_certificate']);
							$dc_file = "dc_".$dra['regnumber'].".".end($file_arr);
						}
						
						if($dra['training_certificate']) 
						{
							$file_arr1 = explode('.',$dra['training_certificate']);
							$tc_file = "tc_".$dra['regnumber'].".".end($file_arr1);
						}
						
						// Photograph
						if($dra['photo_flg'] == 'Y')
						{
							if($dra['scannedphoto'] != '' && is_file("./uploads/iibfdra/".$dra['scannedphoto']))
							{
								$photo_file = $dra['scannedphoto'];
								copy("./uploads/iibfdra/".$dra['scannedphoto'],$directory."/".$dra['scannedphoto']);
								$photo_to_add = $directory."/".$dra['scannedphoto'];
								$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
								$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
								if(!$photo_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$dra['scannedphoto']." (".$dra['regnumber'].")\n");	
								}
								else
									$photo_cnt++;
							}
							else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."photo/"."p_".$dra['registration_no'].".jpg"))
							{
								
								$photo_file = "p_".$dra['regnumber'].".jpg";
								
								copy("./uploads/".$dra['image_path']."photo/"."p_".$dra['registration_no'].".jpg",$directory."/".$photo_file);
								$photo_to_add = $directory."/".$photo_file;
								$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
								$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
								if(!$photo_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$photo_file." (".$dra['regnumber'].")\n");	
								}
								else
									$photo_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$dra['scannedphoto']." (".$dra['regnumber'].")\n");	
							}
						}
						
						// Signature
						if($dra['signature_flg'] == 'Y')
						{
							if(is_file("./uploads/iibfdra/".$dra['scannedsignaturephoto']))
							{
								$sign_file = $dra['scannedsignaturephoto'];
								copy("./uploads/iibfdra/".$dra['scannedsignaturephoto'],$directory."/".$dra['scannedsignaturephoto']);
								$sign_to_add = $directory."/".$dra['scannedsignaturephoto'];
								$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
								$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
								if(!$sign_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$dra['scannedsignaturephoto']." (".$dra['regnumber'].")\n");	
								}
								else
									$sign_cnt++;
							}
							else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."signature/"."s_".$dra['registration_no'].".jpg"))
							{
								$sign_file = "s_".$dra['regnumber'].".jpg";
								
								copy("./uploads/".$dra['image_path']."signature/"."s_".$dra['registration_no'].".jpg",$directory."/".$sign_file);
								$sign_to_add = $directory."/".$sign_file;
								$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
								$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
								if(!$sign_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$sign_file." (".$dra['regnumber'].")\n");	
								}
								else
									$sign_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Signature does not exist  - ".$dra['scannedsignaturephoto']." (".$dra['regnumber'].")\n");	
							}
						}
						
						//ID Proof
						if($dra['id_flg'] == 'Y')
						{
							if(is_file("./uploads/iibfdra/".$dra['idproofphoto']))
							{
								$idproof_file = $dra['idproofphoto'];
								copy("./uploads/iibfdra/".$dra['idproofphoto'],$directory."/".$dra['idproofphoto']);
								$proof_to_add = $directory."/".$dra['idproofphoto'];
								$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
								$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
								if(!$idproof_zip_flg)
								{
									fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$dra['idproofphoto']." (".$dra['regnumber'].")\n");	
								}
								else 
									$idproof_cnt++;
							}
							else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."idproof/"."pr_".$dra['registration_no'].".jpg"))
							{
								$idproof_file = "pr_".$dra['regnumber'].".jpg";
								
								copy("./uploads/".$dra['image_path']."idproof/"."pr_".$dra['registration_no'].".jpg",$directory."/".$idproof_file);
								$proof_to_add = $directory."/".$idproof_file;
								$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
								$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
								if(!$idproof_zip_flg)
								{
									fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$idproof_file." (".$dra['regnumber'].")\n");	
								}
								else
									$idproof_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$dra['idproofphoto']." (".$dra['regnumber'].")\n");	
							}
						}
							
						// TC						 
						/* if($tc_file != '' && is_file("./uploads/iibfdra/".$dra['training_certificate']))
						{
							//$tc_file = $dra['training_certificate'];
							copy("./uploads/iibfdra/".$dra['training_certificate'],$directory."/".$tc_file);
							$tc_to_add = $directory."/".$tc_file;
							$new_tc = substr($tc_to_add,strrpos($tc_to_add,'/') + 1);
							$tc_zip_flg = $zip->addFile($tc_to_add,$new_tc);
							if(!$tc_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Training Certificate not added to zip  - ".$dra['training_certificate']." (".$dra['regnumber'].")\n");	
							}
							else 
								$tcimg_cnt++;
						}
						else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."training_cert/"."traing_".$dra['registration_no'].".jpg"))
						{
							$tc_file = "tc_".$dra['regnumber'].".jpg";
							
							copy("./uploads/".$dra['image_path']."training_cert/"."traing_".$dra['registration_no'].".jpg",$directory."/".$tc_file);
							$tc_to_add = $directory."/".$tc_file;
							$new_tc = substr($tc_to_add,strrpos($tc_to_add,'/') + 1);
							$tc_zip_flg = $zip->addFile($tc_to_add,$new_tc);
							if(!$tc_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Training Certificate not added to zip  - ".$tc_file." (".$dra['regnumber'].")\n");	
							}
							else
								$tcimg_cnt++;
						}
						else
						{
							//fwrite($fp1, "**ERROR** - Training Certificate does not exist  - ".$dra['training_certificate']." (".$dra['regnumber'].")\n");	
						} */
						
						// DC
						if($dra['qualicertificate_flg'] == 'Y')
						{
							if($dc_file && is_file("./uploads/iibfdra/".$dra['quali_certificate']))
							{
								copy("./uploads/iibfdra/".$dra['quali_certificate'],$directory."/".$dc_file);
								$dc_to_add = $directory."/".$dc_file;
								$new_dc = substr($dc_to_add,strrpos($dc_to_add,'/') + 1);
								$dc_zip_flg = $zip->addFile($dc_to_add,$new_dc);
								if(!$dc_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Degree Certificate not added to zip  - ".$dra['quali_certificate']." (".$dra['regnumber'].")\n");	
								}
								else 
									$dcimg_cnt++;
							}
							else if($dra['image_path'] != '' && is_file("./uploads".$dra['image_path']."degree_cert/"."degre_".$dra['registration_no'].".jpg"))
							{
								$dc_file = "dc_".$dra['regnumber'].".jpg";
								
								copy("./uploads/".$dra['image_path']."degree_cert/"."degre_".$dra['registration_no'].".jpg",$directory."/".$dc_file);
								$dc_to_add = $directory."/".$dc_file;
								$new_dc = substr($dc_to_add,strrpos($dc_to_add,'/') + 1);
								$dc_zip_flg = $zip->addFile($dc_to_add,$new_dc);
								if(!$dc_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Degree Certificate not added to zip  - ".$dc_file." (".$dra['regnumber'].")\n");	
								}
								else
									$dcimg_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Degree Certificate does not exist  - ".$dra['quali_certificate']." (".$dra['regnumber'].")\n");
							}
						}							
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg || $tc_zip_flg || $dc_zip_flg)
						{
							$success['zip'] = "DRA Edited Candidate Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating DRA Edited Candidate Images Zip";
						}
					}
					
					$photo 	= "";
					if($photo_file!=""){ $photo 	= DRA_FILE_PATH.$photo_file; }
					
					$signature 	= "";
					if($sign_file!=""){ $signature 	= DRA_FILE_PATH.$sign_file; }
					
					$idproofimg 	= "";
					if($idproof_file!=""){ $idproofimg = DRA_FILE_PATH.$idproof_file; }
					
					$tcimg 	= "";
					if($tc_file!=""){ $tcimg 	= DRA_FILE_PATH.$tc_file; }
					
					$dcimg 	= "";
					if($dc_file!=""){ $dcimg 	= DRA_FILE_PATH.$dc_file; }
					
					$qualification = '';
					$specialisation = '';
					switch($dra['qualification'])
					{
						/* Changes updated as per 'Exam_Application_QualificationCode' by Dattatreya sir on 09-01-2020 */	
						case "tenth"	: 	$qualification = 'UG';
											$specialisation = 7;
											break;
						case "twelth"	: 	$qualification = 'UG';
											$specialisation = 8;
											break;
						case "graduate"	: 	$qualification = 'G';
											$specialisation = 2;
											break;					
					}
					
					$displayname = $dra['namesub'].' '.$dra['firstname'].' '.$dra['middlename'].' '.$dra['lastname'];
					
					$mem_dob = '';
					if($dra['dateofbirth'] != '0000-00-00') { $mem_dob = date('d-M-y',strtotime($dra['dateofbirth'])); }
					
					$mem_doj = '';
					
					if(strlen($dra['stdcode']) > 10) {	$std_code = substr($dra['stdcode'],0,9);		}
					else { $std_code = $dra['stdcode']; }
						
					if(strlen($dra['address1']) > 30) { $address1 = substr($dra['address1'],0,29); }
					else { $address1 = $dra['address1'];	}
					
					if(strlen($dra['address2']) > 30) {	$address2 = substr($dra['address2'],0,29); }
					else {	$address2 = $dra['address2'];	}
					
					if(strlen($dra['address3']) > 30) {	$address3 = substr($dra['address3'],0,29);		}
					else { $address3 = $dra['address3'];	}
					
					if(strlen($dra['address4']) > 30) {	$address4 = substr($dra['address4'],0,29);		}
					else { $address4 = $dra['address4'];	}
					
					if(strlen($dra['district']) > 30) {	$district = substr($dra['district'],0,29);		}
					else { $district = $dra['district'];	}
					
					if(strlen($dra['city']) > 30) {	$city = substr($dra['city'],0,29);		}
					else {	$city = $dra['city'];	}
					
					/* if($dra['editedby'] == '') { $edited_by = "Candidate"; }
					else { $edited_by = $dra['editedby']; } */
					$edited_by = "Candidate";
					
					$branch = '';
					$branch_name = '';
					if($branch == '') { $branch = $city; }
					
					if(strlen($branch) > 20) {	$branch_name = substr($branch,0,19);	}
					else {	$branch_name = $branch;	}
					
					$optnletter = "N";	
					
					$designation = $idNo = $bank_emp_id = '';
					
					//THESE ARE THE HEADER PROVIDED BY PALLAVI
					/* MEM_MEM_NO, MEM_MEM_TYP, MEM_TLE, MEM_NAM_1, MEM_NAM_2, MEM_NAM_3, ID_CARD_NAME, MEM_ADR_1, MEM_ADR_2, MEM_ADR_3, MEM_ADR_4, MEM_ADR_5, MEM_ADR_6, MEM_PIN_CD, MEM_STE_CD, MEM_DOB, MEM_SEX_CD, MEM_QLF_GRD, MEM_QLF_CD, MEM_INS_CD, BRANCH, MEM_DSG_CD, MEM_BNK_JON_DT, EMAIL, STD_R, PHONE_R, MOBILE, ID_TYPE, ID_NO, BDRNO, TRN_DATE, TRN_AMT, USR_ID, AR_FLG, PHOTO_FLG, SIGNATURE_FLG, ID_FLG, UPDATED_ON, AADHAR_NO, QLF_CERT_FLG, BANK_EMP_ID, PHOTO_PATH, ID_PATH, SIGNATURE_PATH, QLF_CERTIFICATE_PATH */ 
					
					$editedon = date('d-M-y H:i:s',strtotime($dra['editedby'])); //SWATI UPDATE EDITED DATA TIME IN editedby COLUMN
					
					$img_dir_path = '/fromweb/testscript/dra/images/';
					
					$scannedphoto_path = $idproofphoto_path = $scannedsignaturephoto_path = $dc_file_path = '';
					if($dra['scannedphoto'] != "" && $dra['photo_flg'] == 'Y') { $scannedphoto_path = $img_dir_path.$dra['scannedphoto']; }
					if($dra['idproofphoto'] != "" && $dra['id_flg'] == 'Y') { $idproofphoto_path = $img_dir_path.$dra['idproofphoto']; }
					if($dra['scannedsignaturephoto'] != "" && $dra['signature_flg'] == 'Y') { $scannedsignaturephoto_path = $img_dir_path.$dra['scannedsignaturephoto']; }
					if($dc_file != "" && $dra['qualicertificate_flg'] == 'Y') { $dc_file_path = $img_dir_path.$dc_file; }					
					
					$data .= ''.$dra['regnumber'].'|'.$dra['registrationtype'].'|'.$dra['namesub'].'|'.$dra['firstname'].'|'.$dra['middlename'].'|'.$dra['lastname'].'|'.$displayname.'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$dra['pincode'].'|'.$dra['state'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$specialisation.'|'.$dra['inst_code'].'|'.$branch_name.'|'.$designation.'|'.$mem_doj.'|'.$dra['email'].'|'.$std_code.'|'.$dra['phone'].'|'.$dra['mobile'].'|'.$dra['idproof'].'|'.$idNo.'||||'.$edited_by.'|'.$optnletter.'|'.$dra['photo_flg'].'|'.$dra['signature_flg'].'|'.$dra['id_flg'].'|'.$editedon.'|'.$dra['aadhar_no'].'|'.$dra['qualicertificate_flg'].'|'.$bank_emp_id.'|'.$scannedphoto_path.'|'.$idproofphoto_path.'|'.$scannedsignaturephoto_path.'|'.$dc_file_path."\n";
					
					$i++;
					$mem_cnt++; 
				}
				
				fwrite($fp1, "\n"."Total Edited DRA  Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				fwrite($fp1, "\n"."Total Training Certificates Added = ".$tcimg_cnt."\n");
				fwrite($fp1, "\n"."Total Degree Certificates Added = ".$dcimg_cnt."\n");
				
				$file_w_flg = fwrite($fp, $data); 
				if($file_w_flg)
				{
					$success['file'] = "DRA Edited Candidate Details File Generated Successfully. ";
				}
				else
				{
					$error['file'] = "Error While Generating DRA Edited Candidate Details File.";
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
			//xxx $this->log_model->cronlog("DRA Edited Candidate Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** DRA Edited Candidate Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	
	
	
	/*  DRA Exam Data */
	public function dra_exam_new()
	{
	    
	    //echo 'swati'; die;
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
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
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** DRA Candidate Exam Details Cron Start - ".$start_time." ******************** \n");
			$yesterday = '2021-10-12'; 
			       // $regid=array(801475079);
                    $this->db->select("dra_payment_transaction.*");
                    $this->db->where("dra_payment_transaction.status = 1");
                    $this->db->where("dra_payment_transaction.exam_period = 711");
                    //$this->db->where_in( 'm.regnumber' , $regid);
                    $this->db->where("(DATE(d.modified_on) = '".$yesterday."')");
			
                    $this->db->join('dra_member_payment_transaction dm','dm.ptid = dra_payment_transaction.id','LEFT');
                    $this->db->join('dra_member_exam d','d.id=dm.memexamid','LEFT');
                    $this->db->join('dra_members m','d.regid = m.regid','LEFT');
                    $dra_payment = $this->Master_model->getRecords('dra_payment_transaction');
                    echo $this->db->last_query();
	
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
			$this->log_model->cronlog("DRA Candidate Exam Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** DRA Candidate Exam Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/* Duplicate Certificate Cron */
	public function dup_cert()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$dup_cert_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		// Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Duplicate Certificate Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "dup_cert_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Duplicate Certificate Details Cron Start - ".$start_time." ******************** \n");
			//$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday = '2020-11-07';
			$ref_id = array(55077);
			// get duplicate certificate registration details for given date
			$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
			
			
		    $this->db->where_in('c.id', $ref_id);
				
			$this->db->join('payment_transaction b','b.ref_id=c.id','LEFT');
			$dup_cert_data = $this->Master_model->getRecords('duplicate_certificate c',array('pay_type' => 4,'pay_status' => 1,'status' => '1'),$select);
			//' DATE(created_on)' => $yesterday,
			if(count($dup_cert_data))
			{
				$data = '';
				$i = 1;
				$mem_cnt = 0;
				foreach($dup_cert_data as $dup_cert)
				{										
//EXM_CD|EXM_NAME|MEM_NO|MEM_TYP|MEM_TLE|MEM_NAM_1|MEM_NAM_2|MEM_NAM_3|MEM_ADR_1|MEM_ADR_2|MEM_ADR_3|MEM_ADR_4|MEM_ADR_5|MEM_ADR_6|MEM_STE_CD|MEM_PIN_CD|MEM_EMAIL|MEM_MOBILE_NO|FEE_AMT|TRN_NO|TRN_DATE|INSTRUMENT_NO|INSTRUMENT_TYPE|
					$trn_date = '';
					if($dup_cert['date'] != '0000-00-00')
					{
						$trn_date = date('d-M-Y',strtotime($dup_cert['date']));
					}
					$INSTRUMENT_TYPE = 'ONLINE';	// mode of application
					$data .= ''.$dup_cert['exam_code'].'|'.$dup_cert['exam_name'].'|'.$dup_cert['regnumber'].'|'.$dup_cert['registrationtype'].'|'.$dup_cert['namesub'].'|'.$dup_cert['firstname'].'|'.$dup_cert['middlename'].'|'.$dup_cert['lastname'].'|'.$dup_cert['address1'].'|'.$dup_cert['address2'].'|'.$dup_cert['address3'].'|'.$dup_cert['address4'].'|'.$dup_cert['district'].'|'.$dup_cert['city'].'|'.$dup_cert['state'].'|'.$dup_cert['pincode'].'|'.$dup_cert['email'].'|'.$dup_cert['mobile'].'|'.$dup_cert['amount'].'|'.$dup_cert['transaction_no'].'|'.$trn_date.'|'.$dup_cert['transaction_no'].'|'.$INSTRUMENT_TYPE."\n";
					$i++;
					$mem_cnt++;
				}
				$dup_cert_flg = fwrite($fp, $data);
				if($dup_cert_flg)
						$success['dup_cert'] = "Duplicate Certificate Details File Generated Successfully. ";
				else
					$error['dup_cert'] = "Error While Generating Duplicate Certificate Details File.";
				fwrite($fp1, "\n"."Total Duplicate Certificate Applications = ".$mem_cnt."\n");
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
			$this->log_model->cronlog("Duplicate Certificate Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Duplicate Certificate Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	/* Membership Renewal Cron */
	public function renewal()
	{
		ini_set("memory_limit", "-1");
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
		$cron_file_dir = "./uploads/cronFilesCustom/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("Renewal Member Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "renewal_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Renewal Member Details Cron Start - ".$start_time." ********** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday = '2020-08-21';
			//$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
			$member_no = array(5936373);
			$this->db->where_in('a.regnumber', $member_no);
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0, 'is_renewal' => 1));
			//' DATE(createdon)'=>$yesterday,
			if(count($new_mem_reg))
			{
				$dirname = "renewal_image_".$current_date;
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
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					$gender = '';
					if($reg_data['gender'] == 'male')	{ $gender = 'M';}
					else if($reg_data['gender'] == 'female')	{ $gender = 'F';}
					$photo = '';
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
					{
						$photo 	= MEM_FILE_RENEWAL_PATH.$reg_data['scannedphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}
					$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
					{
						$signature 	= MEM_FILE_RENEWAL_PATH.$reg_data['scannedsignaturephoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}
					$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
					{
						$idproofimg = MEM_FILE_RENEWAL_PATH.$reg_data['idproofphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
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
					$transaction_date = '';
					$transaction_amt = '0';
					if($reg_data['registrationtype']!='NM')
					{
						if($reg_data['registrationtype'] == 'DB')	// DB Member
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_DB','pay_type'=>2,'member_regnumber'=>$reg_data['regnumber'],'status'=>1),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						}
						else	// Ordinary Member
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'iibfren','status'=>1,'pay_type'=>5,'ref_id'=>$reg_data['regid']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						}
					}
					else	// Non Member
					{
						$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_REG','status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
					}
					if(count($trans_details))
					{
						$transaction_no = $trans_details[0]['transaction_no'];
						$transaction_amt = $trans_details[0]['amount'];
						$date = $trans_details[0]['date'];
						if($date!='0000-00-00')
						{	$transaction_date = date('d-M-y',strtotime($date));	}
					}
					$mem_dob = '';
					if($reg_data['dateofbirth'] != '0000-00-00')
					{
						$mem_dob = date('d-M-y',strtotime($reg_data['dateofbirth']));
					}
					$mem_doj = '';
					if($reg_data['dateofjoin'] != '0000-00-00')
					{
						$mem_doj = date('d-M-y',strtotime($reg_data['dateofjoin']));
					}
					if(strlen($reg_data['stdcode']) > 10)
					{	$std_code = substr($reg_data['stdcode'],0,9);	}
					else
					{	$std_code = $reg_data['stdcode'];	}
					if(strlen($reg_data['office']) > 20)
					{	$branch = substr($reg_data['office'],0,19);	}
					else
					{	$branch = $reg_data['office'];	}
					if(strlen($reg_data['address1']) > 30)
					{	$address1 = substr($reg_data['address1'],0,29);	}
					else
					{	$address1 = $reg_data['address1'];	}
					if(strlen($reg_data['address2']) > 30)
					{	$address2 = substr($reg_data['address2'],0,29);	}
					else
					{	$address2 = $reg_data['address2'];	}
					if(strlen($reg_data['address3']) > 30)
					{	$address3 = substr($reg_data['address3'],0,29);	}
					else
					{	$address3 = $reg_data['address3'];	}
					if(strlen($reg_data['address4']) > 30)
					{	$address4 = substr($reg_data['address4'],0,29);	}
					else
					{	$address4 = $reg_data['address4'];	}
					if(strlen($reg_data['district']) > 30)
					{	$district = substr($reg_data['district'],0,29);	}
					else
					{	$district = $reg_data['district'];	}
					if(strlen($reg_data['city']) > 30)
					{	$city = substr($reg_data['city'],0,29);	}
					else
					{	$city = $reg_data['city'];	}
					// code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					if(strlen($reg_data['address1_pr']) > 30)
					{	$address1_pr = substr($reg_data['address1_pr'],0,29);	}
					else
					{	$address1_pr = $reg_data['address1_pr'];	}
					if(strlen($reg_data['address2_pr']) > 30)
					{	$address2_pr = substr($reg_data['address2_pr'],0,29);	}
					else
					{	$address2_pr = $reg_data['address2_pr'];	}
					if(strlen($reg_data['address3_pr']) > 30)
					{	$address3_pr = substr($reg_data['address3_pr'],0,29);	}
					else
					{	$address3_pr = $reg_data['address3_pr'];	}
					if(strlen($reg_data['address4_pr']) > 30)
					{	$address4_pr = substr($reg_data['address4_pr'],0,29);	}
					else
					{	$address4_pr = $reg_data['address4_pr'];	}
					if(strlen($reg_data['district_pr']) > 30)
					{	$district_pr = substr($reg_data['district_pr'],0,29);	}
					else
					{	$district_pr = $reg_data['district_pr'];	}
					if(strlen($reg_data['city_pr']) > 30)
					{	$city_pr = substr($reg_data['city_pr'],0,29);	}
					else
					{	$city_pr = $reg_data['city_pr'];	}
					// eof code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					$optnletter = "Y";
					if($reg_data['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else
					{	$optnletter = $reg_data['optnletter'];	}
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['registrationtype'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname'].'|'.$reg_data['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$reg_data['pincode'].'|'.$reg_data['state'].'|'.$address1_pr.'|'.$address2_pr.'|'.$address3_pr.'|'.$address4_pr.'|'.$district_pr.'|'.$city_pr.'|'.$reg_data['pincode_pr'].'|'.$reg_data['state_pr'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$reg_data['specify_qualification'].'|'.$reg_data['associatedinstitute'].'|'.$branch.'|'.$reg_data['designation'].'|'.$mem_doj.'|'.$reg_data['email'].'|'.$std_code.'|'.$reg_data['office_phone'].'|'.$reg_data['mobile'].'|'.$reg_data['idproof'].'|'.$reg_data['idNo'].'|'.$transaction_no.'|'.$transaction_date.'|'.$transaction_amt.'|'.$transaction_no.'|'.$optnletter.'|||'.$photo.'|'.$signature.'|'.$idproofimg.'|||||'.$reg_data['aadhar_card'].'|'.$reg_data['bank_emp_id']."\n";
					if($dir_flg)
					{
						// For photo images
						if($photo)
						{
							$image = "./uploads/photograph/".$reg_data['scannedphoto'];
							$max_width = "200";
							$max_height = "200";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
							$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						// For signature images
						if($signature)
						{
							$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
							$max_width = "140";
							$max_height = "100";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
							$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$sign_cnt++;
						}
						// For ID proof images
						if($idproofimg)
						{
							$image = "./uploads/idproof/".$reg_data['idproofphoto'];
							$max_width = "800";
							$max_height = "500";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
							$proof_to_add = $directory."/".$reg_data['idproofphoto'];
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							if(!$idproof_zip_flg)
							{
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else 
								$idproof_cnt++;
						}
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg)
						{
							$success['zip'] = "Renewal Member Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating Renewal Member Images Zip";
						}
					}
					$i++;
					$mem_cnt++;
					//fwrite($fp1, "\n");
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg)
					{
						$success['file'] = "Renewal Member Details File Generated Successfully. ";
					}
					else
					{
						$error['file'] = "Error While Generating Renewal Member Details File.";
					}
				}
				fwrite($fp1, "\n"."Total Renewal Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
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
			$this->log_model->cronlog("Renewal Member Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Renewal Member Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	/* Admit Card Cron */
	public function admit_card()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("Exam Admit Card Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "admit_card_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Exam Admit Card Details Cron Start - ".$start_time." ********** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday = '2021-12-13';
			
			//$member_no = array(6175319,6233893,6199638,6245890,6237946,6244364,6233102,6220980,6233265,6232370,6242607,6206311,6224567,6238399,6250861,6221833,6244488,6245703,6210187,6241376,6196316,6212323,6232761,6242285,6221991,6245331,6201521,6228804,6214614,6233350,6245320,6243990,6224894,6233098,6245876,6244823);
			
			$member_no = array(6485522,6485537); 
			$select = 'a.id as mem_exam_id, a.examination_date,b.transaction_no';
			$this->db->where_in('a.id', $member_no);
			//$this->db->where_in('a.exam_period', array('121','998','777','915','583','912')); //'998','777','219','120'
			//$this->db->where_in('a.exam_code', array('21'));
			//$this->db->LIKE('b.date', '2021-01-');
			//$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$cand_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'pay_status'=>1),$select);
			//' DATE(a.created_on)'=>$yesterday,
			//echo $this->db->last_query();
			if(count($cand_exam_data))
			{
				$admit_card_count = 0;
				$admit_card_sub_count = 0;
				foreach($cand_exam_data as $exam)
				{
					$mem_exam_id = $exam['mem_exam_id'];
					// get admit card details for this member by mem_exam_id
					$this->db->where('remark', 1);
					//$this->db->where('sub_cd', 171);
					//$this->db->where_in('exm_prd', array('121','777','998','912','915','219','583')); //'777','998','912','913','219'
					$admit_card_details_arr = $this->Master_model->getRecords('admit_card_details',array('mem_exam_id' => $mem_exam_id));
					//echo $this->db->last_query();
					if(count($admit_card_details_arr))
					{
						foreach($admit_card_details_arr as $admit_card_data)
						{
							$data = '';
							$exam_date = date('d-M-y',strtotime($admit_card_data['exam_date']));
							$trn_date = date('d-M-y',strtotime($admit_card_data['created_on']));
							$venue_name = '';
							if($admit_card_data['venue_name'] != '')
							{
								$venue_name = trim(str_replace(PHP_EOL, '', $admit_card_data['venue_name']));
								$venue_name = str_replace(array("\n", "\r"), '', $venue_name);
							}
							$venueadd1 = '';
							if($admit_card_data['venueadd1'] != '')
							{
								$venueadd1 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd1']));
								$venueadd1 = str_replace(array("\n", "\r"), '', $venueadd1);
							}
							$venueadd2 = '';
							if($admit_card_data['venueadd2'] != '')
							{
								$venueadd2 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd2']));
								$venueadd2 = str_replace(array("\n", "\r"), '', $venueadd2);
							}
							$venueadd3 = '';
							if($admit_card_data['venueadd3'] != '')
							{
								$venueadd3 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd3']));
								$venueadd3 = str_replace(array("\n", "\r"), '', $venueadd3);
							}
							// code to get actual exam period for exam application, added by Bhagwan Sahane, on 28-09-2017
							$exam_period = '';
							if($exam['examination_date'] != '' && $exam['examination_date'] != "0000-00-00")
							{
								$ex_period = $this->Master_model->getRecords('special_exam_dates',array('examination_date'=>$exam['examination_date']));
								if(count($ex_period))
								{
									$exam_period = $ex_period[0]['period'];	
								}
							}
							else
							{
								$exam_period = $admit_card_data['exm_prd'];
							}
							$exam_code = '';
							if($admit_card_data['exm_cd'] == 340 || $admit_card_data['exm_cd'] == 3400 || $admit_card_data['exm_cd'] == 34000)
							{
								$exam_code = 34;
							}elseif($admit_card_data['exm_cd'] == 580 || $admit_card_data['exm_cd'] == 5800 || $admit_card_data['exm_cd'] == 58000){
								$exam_code = 58;
							}elseif($admit_card_data['exm_cd'] == 1600 || $admit_card_data['exm_cd'] == 16000){
								$exam_code = 160;
							}elseif($admit_card_data['exm_cd'] == 200){
								$exam_code = 20;
							}elseif($admit_card_data['exm_cd'] == 1770 || $admit_card_data['exm_cd'] == 17700){
								$exam_code =177;
							}elseif ($admit_card_data['exm_cd'] == 590){
								$exam_code = 59;
							}elseif ($admit_card_data['exm_cd'] == 810){
								$exam_code = 81;
							}elseif ($admit_card_data['exm_cd'] == 1750){
								$exam_code = 175;
							}else{
								$exam_code = $admit_card_data['exm_cd'];
							}
//EXM_CD|EXM_PRD|MEMBER_NO|CTR_CD|CTR_NAM|SUB_CD|SUB_DSC|VENUE_CD|VENUE_ADDR1|VENUE_ADDR2|VENUE_ADDR3|VENUE_ADDR4|VENUE_ADDR5|VENUE_PINCODE|EXAM_SEAT_NO|EXAM_PASSWORD|EXAM_DATE|EXAM_TIME|EXAM_MODE(Online/Offline)| EXAM_MEDIUM|SCRIBE_FLG(Y/N)|VENDOR_CODE(1/3)|TRN_DATE|VENUE_NAME
							$data .= ''.$exam_code.'|'.$exam_period.'|'.$admit_card_data['mem_mem_no'].'|'.$admit_card_data['center_code'].'|'.$admit_card_data['center_name'].'|'.$admit_card_data['sub_cd'].'|'.$admit_card_data['sub_dsc'].'|'.$admit_card_data['venueid'].'|'.$venueadd1.'|'.$venueadd2.'|'.$venueadd3.'|'.$admit_card_data['venueadd4'].'|'.$admit_card_data['venueadd5'].'|'.$admit_card_data['venpin'].'|'.$admit_card_data['seat_identification'].'|'.$admit_card_data['pwd'].'|'.$exam_date.'|'.$admit_card_data['time'].'|'.$admit_card_data['mode'].'|'.$admit_card_data['m_1'].'|'.$admit_card_data['scribe_flag'].'|'.$admit_card_data['vendor_code'].'|'.$trn_date.'|'.$venue_name.'|'.$exam['transaction_no']."\n";
							$admit_card_sub_count++;
							$file_w_flg = fwrite($fp, $data);
						}
						if($file_w_flg)
						{
							$success[] = "Exam Admit Card Details File Generated Successfully. ";
						}
						else
						{
							$error[] = "Error While Generating Exam Admit Card Details File.";
						}
					}
					$admit_card_count++;
				}
				fwrite($fp1, "\n"."Total Exam Admit Card Details Added = ".$admit_card_count."\n");
				fwrite($fp1, "\n"."Total Exam Admit Card Subject Details Added = ".$admit_card_sub_count."\n");
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
			$this->log_model->cronlog("Exam Admit Card Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Exam Admit Card Details Cron End ".$end_time." **********"."\n");
			fclose($fp1);
		}
	}
	/* Admit Card Cron */
	public function admit_card_free()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("Free Exam Admit Card Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "free_admit_card_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Free Exam Admit Card Details Cron Start - ".$start_time." ********** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday ='2020-11-07';
			// get member exam application details for given date
			$member = array(6332183,6332477,6332487,6332498,6332522,6332587,6332602,6332618,6332668,6332722,6332735,6332745,6332747,6332750,6332751,6332472,6332437,6332430,6332197,6332210,6332215,6332231,6332241,6332288,6332297,6332354,6332356,6332365,6332369,6332375,6332386,6332396,6332796,6332835,6333264,6333279,6333308,6333333,6333453,6333470,6333830,6334144,6334676,6334681,6334804,6334858,6334871,6334884,6333177,6333156,6333152,6332853,6332857,6332891,6332905,6332945,6333000,6333004,6333012,6333032,6333065,6333101,6333118,6333120,6333129,6335531,6332159,6331727,6331884,6331886,6331891,6331893,6331895,6331906,6331924,6331933,6331950,6331952,6331953,6331956,6331972,6331974,6331883,6331879,6331868,6331734,6331736,6331743,6331753,6331755,6331767,6331783,6331817,6331825,6331849,6331850,6331852,6331859,6331861,6331977,6331978,6332073,6332074,6332077,6332080,6332082,6332087,6332096,6332108,6332109,6332112,6332119,6332120,6332123,6332128,6332064,6332044,6332043,6331979,6331981,6331985,6331986,6331991,6331995,6331996,6332002,6332013,6332019,6332020,6332030,6332039,6332040,6332138);
			$select = 'a.id as mem_exam_id, a.examination_date';
			$this->db->where_in('a.id', $member);
			$cand_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_status'=>1),$select);
			//' DATE(a.created_on)'=>$yesterday,'free_paid_flg'=>'F',
			if(count($cand_exam_data))
			{
				$admit_card_count = 0;
				$admit_card_sub_count = 0;
				foreach($cand_exam_data as $exam)
				{
					$mem_exam_id = $exam['mem_exam_id'];
					// get admit card details for this member by mem_exam_id
					$this->db->where('remark', 1);
					$admit_card_details_arr = $this->Master_model->getRecords('admit_card_details',array('mem_exam_id' => $mem_exam_id));
					//,'free_paid_flg'=>'F'
					if(count($admit_card_details_arr))
					{
						foreach($admit_card_details_arr as $admit_card_data)
						{
							$data = '';
							$exam_date = date('d-M-y',strtotime($admit_card_data['exam_date']));
							$trn_date = date('d-M-y',strtotime($admit_card_data['created_on']));
							$venue_name = '';
							if($admit_card_data['venue_name'] != ''){
								$venue_name = trim(str_replace(PHP_EOL, '', $admit_card_data['venue_name']));
								$venue_name = str_replace(array("\n", "\r"), '', $venue_name);
							}
							$venueadd1 = '';
							if($admit_card_data['venueadd1'] != ''){
								$venueadd1 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd1']));
								$venueadd1 = str_replace(array("\n", "\r"), '', $venueadd1);
							}
							$venueadd2 = '';
							if($admit_card_data['venueadd2'] != ''){
								$venueadd2 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd2']));
								$venueadd2 = str_replace(array("\n", "\r"), '', $venueadd2);
							}
							$venueadd3 = '';
							if($admit_card_data['venueadd3'] != ''){
								$venueadd3 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd3']));
								$venueadd3 = str_replace(array("\n", "\r"), '', $venueadd3);
							}
							$exam_period = '';
							$exam_period = $admit_card_data['exm_prd'];
							$exam_code = '';
							$exam_code = $admit_card_data['exm_cd'];
							$transaction_no = '0000000000000';
//EXM_CD|EXM_PRD|MEMBER_NO|CTR_CD|CTR_NAM|SUB_CD|SUB_DSC|VENUE_CD|VENUE_ADDR1|VENUE_ADDR2|VENUE_ADDR3|VENUE_ADDR4|VENUE_ADDR5|VENUE_PINCODE|EXAM_SEAT_NO|EXAM_PASSWORD|EXAM_DATE|EXAM_TIME|EXAM_MODE(Online/Offline)| EXAM_MEDIUM|SCRIBE_FLG(Y/N)|VENDOR_CODE(1/3)|TRN_DATE|VENUE_NAME|TRANSACTION_NO|FREE_PAID_FLG(F/P)
							$data .= ''.$exam_code.'|'.$exam_period.'|'.$admit_card_data['mem_mem_no'].'|'.$admit_card_data['center_code'].'|'.$admit_card_data['center_name'].'|'.$admit_card_data['sub_cd'].'|'.$admit_card_data['sub_dsc'].'|'.$admit_card_data['venueid'].'|'.$venueadd1.'|'.$venueadd2.'|'.$venueadd3.'|'.$admit_card_data['venueadd4'].'|'.$admit_card_data['venueadd5'].'|'.$admit_card_data['venpin'].'|'.$admit_card_data['seat_identification'].'|'.$admit_card_data['pwd'].'|'.$exam_date.'|'.$admit_card_data['time'].'|'.$admit_card_data['mode'].'|'.$admit_card_data['m_1'].'|'.$admit_card_data['scribe_flag'].'|'.$admit_card_data['vendor_code'].'|'.$trn_date.'|'.$venue_name.'|'.$transaction_no.'|'.'F'."\n"; //'|'.$admit_card_data['free_paid_flg'].
							$admit_card_sub_count++;
							$file_w_flg = fwrite($fp, $data);
						}
						if($file_w_flg){
							$success[] = "Free Exam Admit Card Details File Generated Successfully. ";
						}
						else{
							$error[] = "Error While Generating Free Exam Admit Card Details File.";
						}
					}
					$admit_card_count++;
				}
				fwrite($fp1, "\n"."Total Free Exam Admit Card Details Added = ".$admit_card_count."\n");
				fwrite($fp1, "\n"."Total Free Exam Admit Card Subject Details Added = ".$admit_card_sub_count."\n");
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Free Exam Admit Card Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Free Exam Admit Card Details Cron End ".$end_time." **********"."\n");
			fclose($fp1);
		}
	}
	// FBankQuest Registrations Cron */
	public function bankquest()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$cpd_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		// Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("BankQuest Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "bankquest_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** BankQuest Details Cron Start - ".$start_time." ******************** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday = '2020-10-29';
			// get BankQuest registration details for given date
			$ref_id = array(3206,3207);
			$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
			$this->db->where_in('c.bv_id' , $ref_id);
			$this->db->join('payment_transaction b','b.ref_id=c.bv_id','LEFT');
			$bankquest_data = $this->Master_model->getRecords('bank_vision c',array('pay_type' => 6,'pay_status' => 1,'status' => '1'),$select);
			//' DATE(created_on)' => $yesterday,
			if(count($bankquest_data))
			{
				$data = '';
				$i = 1;
				$bankquest_reg_cnt = 0;
				foreach($bankquest_data as $bq_reg)
				{										
//NAME_TLE|MEM_NAM_1|MEM_NAM_2|MEM_NAM_3|SEX(M/F)|EMAIL|CONTACT_NO|ADDRESS1|ADDRESS2|ADDRESS3|ADDRESS4|CITY|STATE|PINCODE|SUBSCRIPTION_NO|SUBSCRIPTION_AMT|SUBSCRIPTION_FROM_DATE|SUBSCRIPTION_TO_DATE|TRANSACTION_NO|TRANSACTION_DATE|PRD_TYPE(BQ)|
					$subscription_from_date = '';
					if($bq_reg['subscription_from_date'] != '0000-00-00')
					{
						$subscription_from_date = date('d-M-Y',strtotime($bq_reg['subscription_from_date']));
					}
					$subscription_to_date = '';
					if($bq_reg['subscription_to_date'] != '0000-00-00')
					{
						$subscription_to_date = date('d-M-Y',strtotime($bq_reg['subscription_to_date']));
					}
					$trn_date = '';
					if($bq_reg['date'] != '0000-00-00')
					{
						$trn_date = date('d-M-Y',strtotime($bq_reg['date']));
					}
					$PRD_TYPE = 'BQ';	// BQ for BankQuest
					$data .= ''.$bq_reg['namesub'].'|'.$bq_reg['fname'].'|'.$bq_reg['mname'].'|'.$bq_reg['lname'].'|'.$bq_reg['gender'].'|'.$bq_reg['email_id'].'|'.$bq_reg['contact_no'].'|'.$bq_reg['address_1'].'|'.$bq_reg['address_2'].'|'.$bq_reg['address_3'].'|'.$bq_reg['address_4'].'|'.$bq_reg['city'].'|'.$bq_reg['state'].'|'.$bq_reg['pincode'].'|'.$bq_reg['subscription_no'].'|'.$bq_reg['amount'].'|'.$subscription_from_date.'|'.$subscription_to_date.'|'.$bq_reg['transaction_no'].'|'.$trn_date.'|'.$PRD_TYPE.'|'.$bq_reg['state']."\n";
					$i++;
					$bankquest_reg_cnt++;
				}
				$cpd_flg = fwrite($fp, $data);
				if($cpd_flg)
						$success['bq_reg'] = "BankQuest Details File Generated Successfully. ";
				else
					$error['bq_reg'] = "Error While Generating BankQuest Details File.";
				fwrite($fp1, "\n"."Total BankQuest Applications = ".$bankquest_reg_cnt."\n");
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
			$this->log_model->cronlog("BankQuest Details Cron End", $desc);
			fwrite($fp1, "\n"."********** BankQuest Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	/* Vision Registrations Cron */
	public function vision()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$cpd_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		// Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Vision Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "iibf_vision_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Vision Details Cron Start - ".$start_time." ******************** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday = '2020-10-29';
			// get Vision registration details for given date
			$ref_id = array(4453);
			$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
		    $this->db->where_in('c.vision_id' , $ref_id);
			$this->db->join('payment_transaction b','b.ref_id=c.vision_id','LEFT');
			$vision_data = $this->Master_model->getRecords('iibf_vision c',array('pay_type' => 7,'pay_status' => 1,'status' => '1'),$select);
			//' DATE(created_on)' => $yesterday,
			if(count($vision_data))
			{
				$data = '';
				$i = 1;
				$vision_reg_cnt = 0;
				foreach($vision_data as $vi_reg)
				{										
//NAME_TLE|MEM_NAM_1|MEM_NAM_2|MEM_NAM_3|SEX(M/F)|EMAIL|CONTACT_NO|ADDRESS1|ADDRESS2|ADDRESS3|ADDRESS4|CITY|STATE|PINCODE|SUBSCRIPTION_NO|SUBSCRIPTION_AMT|SUBSCRIPTION_FROM_DATE|SUBSCRIPTION_TO_DATE|TRANSACTION_NO|TRANSACTION_DATE|PRD_TYPE(VI)|
					$subscription_from_date = '';
					if($vi_reg['subscription_from_date'] != '0000-00-00')
					{
						$subscription_from_date = date('d-M-Y',strtotime($vi_reg['subscription_from_date']));
					}
					$subscription_to_date = '';
					if($vi_reg['subscription_to_date'] != '0000-00-00')
					{
						$subscription_to_date = date('d-M-Y',strtotime($vi_reg['subscription_to_date']));
					}
					$trn_date = '';
					if($vi_reg['date'] != '0000-00-00')
					{
						$trn_date = date('d-M-Y',strtotime($vi_reg['date']));
					}
					$PRD_TYPE = 'VIS';	// VI for Vision
					$data .= ''.$vi_reg['namesub'].'|'.$vi_reg['fname'].'|'.$vi_reg['mname'].'|'.$vi_reg['lname'].'|'.$vi_reg['gender'].'|'.$vi_reg['email_id'].'|'.$vi_reg['contact_no'].'|'.$vi_reg['address_1'].'|'.$vi_reg['address_2'].'|'.$vi_reg['address_3'].'|'.$vi_reg['address_4'].'|'.$vi_reg['city'].'|'.$vi_reg['state'].'|'.$vi_reg['pincode'].'|'.$vi_reg['subscription_no'].'|'.$vi_reg['amount'].'|'.$subscription_from_date.'|'.$subscription_to_date.'|'.$vi_reg['transaction_no'].'|'.$trn_date.'|'.$PRD_TYPE.'|'.$vi_reg['state']."\n";
					$i++;
					$vision_reg_cnt++;
				}
				$cpd_flg = fwrite($fp, $data);
				if($cpd_flg)
						$success['vi_reg'] = "Vision Details File Generated Successfully. ";
				else
					$error['vi_reg'] = "Error While Generating Vision Details File.";
				fwrite($fp1, "\n"."Total Vision Applications = ".$vision_reg_cnt."\n");
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
			$this->log_model->cronlog("Vision Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Vision Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	// FinQuest Registrations Cron */
	public function finquest()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$cpd_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		// Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("FinQuest Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "finquest_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** FinQuest Details Cron Start - ".$start_time." ******************** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday = '2022-01-02';
			// get FinQuest registration details for given date
			$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
			$this->db->join('payment_transaction b','b.ref_id=c.id','LEFT');
			$finquest_data = $this->Master_model->getRecords('fin_quest c',array(' DATE(created_on)' => $yesterday,'pay_type' => 8,'pay_status' => 1,'status' => '1'),$select);
			if(count($finquest_data))
			{
				$data = '';
				$i = 1;
				$finquest_reg_cnt = 0;
				foreach($finquest_data as $fq_reg)
				{										
//MEM_NO|MEM_TYP|NAME_TLE|MEM_NAM_1|MEM_NAM_2|MEM_NAM_3|SEX(M/F)|EMAIL|CONTACT_NO|ADDRESS1|ADDRESS2|ADDRESS3|ADDRESS4|CITY|STATE|PINCODE|SUBSCRIPTION_NO|SUBSCRIPTION_AMT|SUBSCRIPTION_FROM_DATE|SUBSCRIPTION_TO_DATE|TRANSACTION_NO|TRANSACTION_DATE|PRD_TYPE(FQ)|
					$subscription_from_date = '';
					if($fq_reg['subscription_from_date'] != '0000-00-00')
					{
						$subscription_from_date = date('d-M-Y',strtotime($fq_reg['subscription_from_date']));
					}
					$subscription_to_date = '';
					if($fq_reg['subscription_to_date'] != '0000-00-00')
					{
						$subscription_to_date = date('d-M-Y',strtotime($fq_reg['subscription_to_date']));
					}
					$trn_date = '';
					if($fq_reg['date'] != '0000-00-00')
					{
						$trn_date = date('d-M-Y',strtotime($fq_reg['date']));
					}
					$PRD_TYPE = 'FQ';	// FQ for FinQuest
					$gender = '';
					if($fq_reg['gender'] == 'male')	{ $gender = 'M';}
					else if($fq_reg['gender'] == 'female')	{ $gender = 'F';}
					$data .= ''.$fq_reg['mem_no'].'|'.$fq_reg['registrationtype'].'|'.$fq_reg['namesub'].'|'.$fq_reg['fname'].'|'.$fq_reg['mname'].'|'.$fq_reg['lname'].'|'.$gender.'|'.$fq_reg['email_id'].'|'.$fq_reg['contact_no'].'|'.$fq_reg['address_1'].'|'.$fq_reg['address_2'].'|'.$fq_reg['address_3'].'|'.$fq_reg['address_4'].'|'.$fq_reg['city'].'|'.$fq_reg['state'].'|'.$fq_reg['pincode'].'|'.$fq_reg['subscription_no'].'|'.$fq_reg['amount'].'|'.$subscription_from_date.'|'.$subscription_to_date.'|'.$fq_reg['transaction_no'].'|'.$trn_date.'|'.$PRD_TYPE."\n";
					$i++;
					$finquest_reg_cnt++;
				}
				$cpd_flg = fwrite($fp, $data);
				if($cpd_flg)
						$success['fq_reg'] = "FinQuest Details File Generated Successfully. ";
				else
					$error['fq_reg'] = "Error While Generating FinQuest Details File.";
				fwrite($fp1, "\n"."Total FinQuest Applications = ".$finquest_reg_cnt."\n");
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
			$this->log_model->cronlog("FinQuest Details Cron End", $desc);
			fwrite($fp1, "\n"."********** FinQuest Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	// CPD Registrations Cron
	public function cpd()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$cpd_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		// Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("CPD Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "cpd_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** CPD Details Cron Start - ".$start_time." ******************** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday = '2021-01-31';
			// get CPD registration details for given date
			$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
			$ref_id = array(1233);
			$this->db->join('payment_transaction b','b.ref_id=c.id','LEFT');
             $this->db->where_in('c.id', $ref_id);
			$cpd_data = $this->Master_model->getRecords('cpd_registration c',array('pay_type' => 9,'pay_status' => 1,'status' => '1'),$select);
		//' DATE(created_on)' => $yesterday,
			if(count($cpd_data))
			{
				$data = '';
				$i = 1;
				$mem_cnt = 0;
				foreach($cpd_data as $cpd_reg)
				{										
//MEM_NO|MEM_TYP|MEM_TLE|MEM_NAM_1|MEM_NAM_2|MEM_NAM_3|MEM_EMAIL|MEM_MOBILE_NO|MEM_ADR_1|MEM_ADR_2|MEM_ADR_3|MEM_ADR_4|MEM_ADR_5|MEM_ADR_6|MEM_STE_CD|MEM_PIN_CD|INST_CD|BRANCH_OFFICE|DESIGNATION|QUALIFICATION|SPECIFY_QUALIFICATION|EXPERIENCE|FEE_AMT|TRN_NO|TRN_DATE|
					$trn_date = '';
					if($cpd_reg['date'] != '0000-00-00')
					{
						$trn_date = date('d-M-Y',strtotime($cpd_reg['date']));
					}
					$data .= ''.$cpd_reg['member_no'].'|'.$cpd_reg['registrationtype'].'|'.$cpd_reg['namesub'].'|'.$cpd_reg['firstname'].'|'.$cpd_reg['middlename'].'|'.$cpd_reg['lastname'].'|'.$cpd_reg['email'].'|'.$cpd_reg['mobile'].'|'.$cpd_reg['address1'].'|'.$cpd_reg['address2'].'|'.$cpd_reg['address3'].'|'.$cpd_reg['address4'].'|'.$cpd_reg['district'].'|'.$cpd_reg['city'].'|'.$cpd_reg['state'].'|'.$cpd_reg['pincode'].'|'.$cpd_reg['associatedinstitute'].'|'.$cpd_reg['office'].'|'.$cpd_reg['designation'].'|'.$cpd_reg['qualification'].'|'.$cpd_reg['specified_qualification'].'|'.$cpd_reg['experience'].'|'.$cpd_reg['amount'].'|'.$cpd_reg['transaction_no'].'|'.$trn_date."\n";
					$i++;
					$mem_cnt++;
				}
				$cpd_flg = fwrite($fp, $data);
				if($cpd_flg)
						$success['cpd_reg'] = "CPD Details File Generated Successfully. ";
				else
					$error['cpd_reg'] = "Error While Generating CPD Details File.";
				fwrite($fp1, "\n"."Total CPD Applications = ".$mem_cnt."\n");
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
			$this->log_model->cronlog("CPD Details Cron End", $desc);
			fwrite($fp1, "\n"."********** CPD Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	// Blended Registrations Cron */
	public function blended()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$blended_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		// Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Blended Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "blended_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Blended Details Cron Start - ".$start_time." ******************** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday = '2020-11-07';
			// get Blended registration details for given date
			$id = array(21163);
			$br_select = 'c.*';
			$this->db->where_in('c.blended_id' , $id);
			$br_blended_data = $this->Master_model->getRecords('blended_registration c',array('pay_status' => 1),$br_select);
			//' DATE(createdon)' => $yesterday,
			echo $this->db->last_query();
			if(count($br_blended_data))
			{
				$data = '';
				$i = 1;
				$mem_cnt = 0;
				foreach($br_blended_data as $br_blended_reg)
				{
					$blended_id = $br_blended_reg['blended_id'];
					$transaction_no = '';
					$trn_date = '';
					$fees = 0;
					$receipt_no = '';
					$pay_txn_id = '';
					$pay_type = '';
					$record_type_flag = 1;	// 1 => training_type = VC, attempt = 1, fees = 0 and 2 => training_type = PC
					// check if training type is Virtual, attempt is 1 and Fees is 0
					if($br_blended_reg['training_type'] == 'VC' && $br_blended_reg['attempt'] == 1 && $br_blended_reg['fee'] == 0)
					{
						$record_type_flag = 1;
						$select = 'c.*';
						$blended_data = $this->Master_model->getRecords('blended_registration c',array('pay_status' => 1, 'blended_id' => $blended_id),$select);
					}
					else if($br_blended_reg['training_type'] == 'PC' && $br_blended_reg['attempt'] == 0 && $br_blended_reg['fee'] == 0)
					{
						$record_type_flag = 1;
						$select = 'c.*';
						$blended_data = $this->Master_model->getRecords('blended_registration c',array('pay_status' => 1, 'blended_id' => $blended_id),$select);
						//' DATE(createdon)' => $yesterday,
					}
					else
					{
						$record_type_flag = 2;
						// get Blended registration details for given date
						$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount,b.receipt_no,b.id AS pay_txn_id,b.pay_type';
						$this->db->join('payment_transaction b','b.ref_id=c.blended_id','LEFT');
						$blended_data = $this->Master_model->getRecords('blended_registration c',array('pay_type' => 10,'pay_status' => 1,'status' => '1', 'blended_id' => $blended_id),$select);
						//' DATE(createdon)' => $yesterday,
					}
					if(count($blended_data))
					{
						foreach($blended_data as $blended_reg)
						{										
		//member_no|program_code|zone_code|center_code|center_name|batch_code|venue_name|start_date|end_date|namesub|firstname|middlename|lastname|address1|address2|address3|address4|district|city|state|pincode|dateofbirth|email|mobile|res_stdcode+residential_phone|stdcode+office_phone|qualification|qualification_name|designation|designation_name|associatedinstitute|associatedinstitute_name|specify_qualification|emergency_contact_no|blood_group|fee|pay_status|txn_date|invoice_no|tax_type|app_type|exempt|txn_no|pay_type|emergency_name|attempt|training_type|mem_gstin_no|
							// check if training type is Virtual, attempt is 1 and Fees is 0
							if($record_type_flag == 1)
							{
								$transaction_no = '';
								$txn_date = '';
								if($blended_reg['createdon'] != '0000-00-00')
								{
									$trn_date = date('d-M-Y',strtotime($blended_reg['createdon']));
								}
								$fees = $blended_reg['fee'];
								$receipt_no = '';
								$pay_txn_id = '';
								$pay_type = 10;
							}
							else
							{
								$transaction_no = $blended_reg['transaction_no'];
								$txn_date = '';
								if($blended_reg['date'] != '0000-00-00')
								{
									$trn_date = date('d-M-Y',strtotime($blended_reg['date']));
								}
								$fees = $blended_reg['amount'];
								$receipt_no = $blended_reg['receipt_no'];
								$pay_txn_id = $blended_reg['pay_txn_id'];
								$pay_type = $blended_reg['pay_type'];
							}
							$start_date = '';
							if($blended_reg['start_date'] != '0000-00-00')
							{
								$start_date = date('d-M-Y',strtotime($blended_reg['start_date']));
							}
							$end_date = '';
							if($blended_reg['end_date'] != '0000-00-00')
							{
								$end_date = date('d-M-Y',strtotime($blended_reg['end_date']));
							}
							$dateofbirth = '';
							if($blended_reg['dateofbirth'] != '0000-00-00')
							{
								$dateofbirth = date('d-M-Y',strtotime($blended_reg['dateofbirth']));
							}
							$qualification_name = '';
							if($blended_reg['qualification'] == 'U')
							{
								$qualification_name = 'Under Graduate';
							}
							elseif($blended_reg['qualification'] == 'G')
							{
								$qualification_name = 'Graduate';		
							}
							elseif($blended_reg['qualification'] == 'P')
							{
								$qualification_name = 'Post Graduate';	
							}
							$designation_name = '';
							$designation_code = $blended_reg['designation'];
							$designation_details = $this->Master_model->getRecords('designation_master',array('dcode'=>$designation_code),'dname');
							if(count($designation_details))
							{
								/*foreach($designation_details as $designation_data)
								{
									$designation_name = $designation_data['dname'];
								}*/
								$designation_name = $designation_details[0]['dname'];
							}
							$associatedinstitute_name = '';
							$associatedinstitute_code = $blended_reg['associatedinstitute'];
							$associatedinstitute_details = $this->Master_model->getRecords('institution_master',array('institude_id'=>$associatedinstitute_code),'name');
							if(count($associatedinstitute_details))
							{
								/*foreach($associatedinstitute_details as $associatedinstitute_data)
								{
									$associatedinstitute_name = $associatedinstitute_data['name'];
								}*/
								$associatedinstitute_name = $associatedinstitute_details[0]['name'];
							}
							$specified_qualification_name = '';
							$specified_qualification_code = $blended_reg['specify_qualification'];
							$specified_qualification_details = $this->Master_model->getRecords('qualification',array('qid'=>$specified_qualification_code),'name');
							if(count($specified_qualification_details))
							{
								/*foreach($specified_qualification_details as $specified_qualification_data)
								{
									$specified_qualification_name = $specified_qualification_data['name'];
								}*/
								$specified_qualification_name = $specified_qualification_details[0]['name'];
							}
							$pay_status = 'SUCCESS';
							// Invoice details
							$invoice_no = '';
							$tax_type = '';
							$app_type = '';
							$exempt = '';
							if($receipt_no != '' && $pay_txn_id != '')
							{
								//$receipt_no = $blended_reg['receipt_no'];
								//$pay_txn_id = $blended_reg['pay_txn_id'];
								// get invoice details for this blended course payment transaction by id and receipt_no
								$this->db->where('transaction_no !=','');
								$this->db->where('app_type','T');
								$this->db->where('receipt_no',$receipt_no);
								$blended_course_invoice_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
								if(count($blended_course_invoice_data))
								{
									foreach($blended_course_invoice_data as $blended_course_invoice)
									{
										$invoice_no = $blended_course_invoice['invoice_no'];
										$tax_type = $blended_course_invoice['tax_type'];
										$app_type = $blended_course_invoice['app_type'];
										$exempt = $blended_course_invoice['exempt'];
									}
								}
							}
							if(strlen($blended_reg['city']) > 20)
							{	$city = substr($blended_reg['city'],0,19);	}
							else
							{	$city = $blended_reg['city'];	}
							if(strlen($blended_reg['district']) > 20)
							{	$district = substr($blended_reg['district'],0,19);	}
							else
							{	$district = $blended_reg['district'];	}
							/*$data .= ''.$blended_reg['member_no'].'|'.$blended_reg['program_code'].'|'.$blended_reg['zone_code'].'|'.$blended_reg['center_code'].'|'.$blended_reg['center_name'].'|'.$blended_reg['batch_code'].'|'.$blended_reg['venue_name'].'|'.$start_date.'|'.$end_date.'|'.$blended_reg['namesub'].'|'.$blended_reg['firstname'].'|'.$blended_reg['middlename'].'|'.$blended_reg['lastname'].'|'.$blended_reg['address1'].'|'.$blended_reg['address2'].'|'.$blended_reg['address3'].'|'.$blended_reg['address4'].'|'.$blended_reg['district'].'|'.$blended_reg['city'].'|'.$blended_reg['state'].'|'.$blended_reg['pincode'].'|'.$dateofbirth.'|'.$blended_reg['email'].'|'.$blended_reg['mobile'].'|'.$blended_reg['res_stdcode'].' '.$blended_reg['residential_phone'].'|'.$blended_reg['stdcode'].' '.$blended_reg['office_phone'].'|'.$blended_reg['specify_qualification'].'|'.$qualification_name.'|'.$blended_reg['designation'].'|'.$designation_name.'|'.$blended_reg['associatedinstitute'].'|'.$associatedinstitute_name.'|'.$specified_qualification_name.'|'.$blended_reg['emergency_contact_no'].'|'.$blended_reg['blood_group'].'|'.$blended_reg['fee'].'|'.$pay_status.'|'.$trn_date.'|'.$invoice_no.'|'.$tax_type.'|'.$app_type.'|'.$exempt.'|'.$blended_reg['transaction_no'].'|'.$blended_reg['pay_type'].'|'.$blended_reg['emergency_name'].'|'.$blended_reg['attempt'].'|'.$blended_reg['training_type']."|\n";*/
							$zone_code = '';
							$zone_code = $blended_reg['zone_code'];
							if($zone_code == 'CO' || $zone_code == 'CZ'){
								$zone_code = 'WZ';
							}
							$data .= ''.$blended_reg['member_no'].'|'.$blended_reg['program_code'].'|'.$zone_code.'|'.$blended_reg['center_code'].'|'.$blended_reg['center_name'].'|'.$blended_reg['batch_code'].'|'.$blended_reg['venue_name'].'|'.$start_date.'|'.$end_date.'|'.$blended_reg['namesub'].'|'.$blended_reg['firstname'].'|'.$blended_reg['middlename'].'|'.$blended_reg['lastname'].'|'.$blended_reg['address1'].'|'.$blended_reg['address2'].'|'.$blended_reg['address3'].'|'.$blended_reg['address4'].'|'.$district.'|'.$city.'|'.$blended_reg['state'].'|'.$blended_reg['pincode'].'|'.$dateofbirth.'|'.$blended_reg['email'].'|'.$blended_reg['mobile'].'|'.$blended_reg['res_stdcode'].$blended_reg['residential_phone'].'|'.$blended_reg['stdcode'].$blended_reg['office_phone'].'|'.$blended_reg['specify_qualification'].'|'.$qualification_name.'|'.$blended_reg['designation'].'|'.$designation_name.'|'.$blended_reg['associatedinstitute'].'|'.$associatedinstitute_name.'|'.$specified_qualification_name.'|'.$blended_reg['emergency_contact_no'].'|'.$blended_reg['blood_group'].'|'.$fees.'|'.$pay_status.'|'.$trn_date.'|'.$invoice_no.'|'.$tax_type.'|'.$app_type.'|'.$exempt.'|'.$transaction_no.'|'.$pay_type.'|'.$blended_reg['emergency_name'].'|'.$blended_reg['attempt'].'|'.$blended_reg['training_type'].'|'.$blended_reg['gstin_no']."\n";
							$i++;
							$mem_cnt++;
						}
					}
				}
				$blended_flg = fwrite($fp, $data);
				if($blended_flg)
						$success['blended_reg'] = "Blended Details File Generated Successfully. ";
				else
					$error['blended_reg'] = "Error While Generating Blended Details File.";
				fwrite($fp1, "\n"."Total Blended Applications = ".$mem_cnt."\n");
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
			$this->log_model->cronlog("Blended Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Blended Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	// Blended Registrations Cron */
		public function blended_examtraining()
		{
			ini_set("memory_limit", "-1");
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$blended_flg = 0;
			$success = array();
			$error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	
			$cron_file_dir = "./uploads/cronFilesCustom/";
			// Cron start Logs
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("Blended Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "blended_details_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n********** Blended Details Cron Start - ".$start_time." ******************** \n");
				//$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2020-10-08';
				// get Blended registration details for given date
				$br_select = 'c.*';
				//$br_blended_data = $this->Master_model->getRecords('blended_registration c',array(' DATE(createdon)' => $yesterday,'pay_status' => 1),$br_select);
				$batch = array('VITC003');
				$this->db->where_in('c.batch_code', $batch);
				$this->db->where_in('c.program_code', array('ITC','ITC2','ITC3'));
				$this->db->like('createdon','2021-01-21');
				$br_blended_data = $this->Master_model->getRecords('blended_registration c',array('pay_status' => 1),$br_select);
				//' DATE(createdon)' => $yesterday,
				//echo $this->db->last_query();
				if(count($br_blended_data))
				{
					$data = '';
					$i = 1;
					$mem_cnt = 0;
					foreach($br_blended_data as $br_blended_reg)
					{
						$blended_id = $br_blended_reg['blended_id'];
						$transaction_no = '';
						$trn_date = '';
						$fees = 0;
						$receipt_no = '';
						$pay_txn_id = '';
						$pay_type = '';
						$record_type_flag = 1;	// 1 => training_type = VC, attempt = 1, fees = 0 and 2 => training_type = PC
						// check if training type is Virtual, attempt is 1 and Fees is 0
						if($br_blended_reg['training_type'] == 'VC' && $br_blended_reg['attempt'] == 0 && $br_blended_reg['fee'] == 0)
						{
							$record_type_flag = 1;
							$select = 'c.*';
							$blended_data = $this->Master_model->getRecords('blended_registration c',array('pay_status' => 1, 'blended_id' => $blended_id),$select);
							//' DATE(createdon)' => $yesterday,
							//echo $this->db->last_query();
						} 
						else if($br_blended_reg['training_type'] == 'PC' && $br_blended_reg['attempt'] == 0 && $br_blended_reg['fee'] == 0)
						{
							$record_type_flag = 1;
							$select = 'c.*';
							$blended_data = $this->Master_model->getRecords('blended_registration c',array('pay_status' => 1, 'blended_id' => $blended_id),$select);
						}
						else
						{
							$record_type_flag = 2;
							// get Blended registration details for given date
							$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount,b.receipt_no,b.id AS pay_txn_id,b.pay_type';
							$this->db->join('payment_transaction b','b.ref_id=c.blended_id','LEFT');
							$blended_data = $this->Master_model->getRecords('blended_registration c',array(' DATE(createdon)' => $yesterday,'pay_type' => 10,'pay_status' => 1,'status' => '1', 'blended_id' => $blended_id),$select);
						}
						if(count($blended_data))
						{
							foreach($blended_data as $blended_reg)
							{										
								//member_no|program_code|zone_code|center_code|center_name|batch_code|venue_name|start_date|end_date|namesub|firstname|middlename|lastname|address1|address2|address3|address4|district|city|state|pincode|dateofbirth|email|mobile|res_stdcode+residential_phone|stdcode+office_phone|qualification|qualification_name|designation|designation_name|associatedinstitute|associatedinstitute_name|specify_qualification|emergency_contact_no|blood_group|fee|pay_status|txn_date|invoice_no|tax_type|app_type|exempt|txn_no|pay_type|emergency_name|attempt|training_type|mem_gstin_no|
								// check if training type is Virtual, attempt is 1 and Fees is 0
								if($record_type_flag == 1)
								{
									$transaction_no = '';
									$txn_date = '';
									if($blended_reg['createdon'] != '0000-00-00')
									{
										$trn_date = date('d-M-Y',strtotime($blended_reg['createdon']));
									}
									$fees = $blended_reg['fee'];
									$receipt_no = '';
									$pay_txn_id = '';
									$pay_type = 10;
								}
								else
								{
									$transaction_no = $blended_reg['transaction_no'];
									$txn_date = '';
									if($blended_reg['date'] != '0000-00-00')
									{
										$trn_date = date('d-M-Y',strtotime($blended_reg['date']));
									}
									$fees = $blended_reg['amount'];
									$receipt_no = $blended_reg['receipt_no'];
									$pay_txn_id = $blended_reg['pay_txn_id'];
									$pay_type = $blended_reg['pay_type'];
								}
								$start_date = '';
								if($blended_reg['start_date'] != '0000-00-00')
								{
									$start_date = date('d-M-Y',strtotime($blended_reg['start_date']));
								}
								$end_date = '';
								if($blended_reg['end_date'] != '0000-00-00')
								{
									$end_date = date('d-M-Y',strtotime($blended_reg['end_date']));
								}
								$dateofbirth = '';
								if($blended_reg['dateofbirth'] != '0000-00-00')
								{
									$dateofbirth = date('d-M-Y',strtotime($blended_reg['dateofbirth']));
								}
								$qualification_name = '';
								if($blended_reg['qualification'] == 'U')
								{
									$qualification_name = 'Under Graduate';
								}
								elseif($blended_reg['qualification'] == 'G')
								{
									$qualification_name = 'Graduate';		
								}
								elseif($blended_reg['qualification'] == 'P')
								{
									$qualification_name = 'Post Graduate';	
								}
								$designation_name = '';
								$designation_code = $blended_reg['designation'];
								$designation_details = $this->Master_model->getRecords('designation_master',array('dcode'=>$designation_code),'dname');
								if(count($designation_details))
								{
									/*foreach($designation_details as $designation_data)
										{
										$designation_name = $designation_data['dname'];
									}*/
									$designation_name = $designation_details[0]['dname'];
								}
								$associatedinstitute_name = '';
								$associatedinstitute_code = $blended_reg['associatedinstitute'];
								$associatedinstitute_details = $this->Master_model->getRecords('institution_master',array('institude_id'=>$associatedinstitute_code),'name');
								if(count($associatedinstitute_details))
								{
									/*foreach($associatedinstitute_details as $associatedinstitute_data)
										{
										$associatedinstitute_name = $associatedinstitute_data['name'];
									}*/
									$associatedinstitute_name = $associatedinstitute_details[0]['name'];
								}
								$specified_qualification_name = '';
								$specified_qualification_code = $blended_reg['specify_qualification'];
								$specified_qualification_details = $this->Master_model->getRecords('qualification',array('qid'=>$specified_qualification_code),'name');
								if(count($specified_qualification_details))
								{
									/*foreach($specified_qualification_details as $specified_qualification_data)
										{
										$specified_qualification_name = $specified_qualification_data['name'];
									}*/
									$specified_qualification_name = $specified_qualification_details[0]['name'];
								}
								$pay_status = 'SUCCESS';
								// Invoice details
								$invoice_no = '';
								$tax_type = '';
								$app_type = '';
								$exempt = '';
								if($receipt_no != '' && $pay_txn_id != '')
								{
									//$receipt_no = $blended_reg['receipt_no'];
									//$pay_txn_id = $blended_reg['pay_txn_id'];
									// get invoice details for this blended course payment transaction by id and receipt_no
									$this->db->where('transaction_no !=','');
									$this->db->where('app_type','T');
									$this->db->where('receipt_no',$receipt_no);
									$blended_course_invoice_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
									if(count($blended_course_invoice_data))
									{
										foreach($blended_course_invoice_data as $blended_course_invoice)
										{
											$invoice_no = $blended_course_invoice['invoice_no'];
											$tax_type = $blended_course_invoice['tax_type'];
											$app_type = $blended_course_invoice['app_type'];
											$exempt = $blended_course_invoice['exempt'];
										}
									}
								}
								if(strlen($blended_reg['city']) > 20)
								{	$city = substr($blended_reg['city'],0,19);	}
								else
								{	$city = $blended_reg['city'];	}
								if(strlen($blended_reg['district']) > 20)
								{	$district = substr($blended_reg['district'],0,19);	}
								else
								{	$district = $blended_reg['district'];	}
								/*$data .= ''.$blended_reg['member_no'].'|'.$blended_reg['program_code'].'|'.$blended_reg['zone_code'].'|'.$blended_reg['center_code'].'|'.$blended_reg['center_name'].'|'.$blended_reg['batch_code'].'|'.$blended_reg['venue_name'].'|'.$start_date.'|'.$end_date.'|'.$blended_reg['namesub'].'|'.$blended_reg['firstname'].'|'.$blended_reg['middlename'].'|'.$blended_reg['lastname'].'|'.$blended_reg['address1'].'|'.$blended_reg['address2'].'|'.$blended_reg['address3'].'|'.$blended_reg['address4'].'|'.$blended_reg['district'].'|'.$blended_reg['city'].'|'.$blended_reg['state'].'|'.$blended_reg['pincode'].'|'.$dateofbirth.'|'.$blended_reg['email'].'|'.$blended_reg['mobile'].'|'.$blended_reg['res_stdcode'].' '.$blended_reg['residential_phone'].'|'.$blended_reg['stdcode'].' '.$blended_reg['office_phone'].'|'.$blended_reg['specify_qualification'].'|'.$qualification_name.'|'.$blended_reg['designation'].'|'.$designation_name.'|'.$blended_reg['associatedinstitute'].'|'.$associatedinstitute_name.'|'.$specified_qualification_name.'|'.$blended_reg['emergency_contact_no'].'|'.$blended_reg['blood_group'].'|'.$blended_reg['fee'].'|'.$pay_status.'|'.$trn_date.'|'.$invoice_no.'|'.$tax_type.'|'.$app_type.'|'.$exempt.'|'.$blended_reg['transaction_no'].'|'.$blended_reg['pay_type'].'|'.$blended_reg['emergency_name'].'|'.$blended_reg['attempt'].'|'.$blended_reg['training_type']."|\n";*/
								$zone_code = '';
								$zone_code = $blended_reg['zone_code'];
								if($zone_code == 'CO' || $zone_code == 'CZ'){
									$zone_code = 'WZ';
								}
								$data .= ''.$blended_reg['member_no'].'|'.$blended_reg['program_code'].'|'.$zone_code.'|'.$blended_reg['center_code'].'|'.$blended_reg['center_name'].'|'.$blended_reg['batch_code'].'|'.$blended_reg['venue_name'].'|'.$start_date.'|'.$end_date.'|'.$blended_reg['namesub'].'|'.$blended_reg['firstname'].'|'.$blended_reg['middlename'].'|'.$blended_reg['lastname'].'|'.$blended_reg['address1'].'|'.$blended_reg['address2'].'|'.$blended_reg['address3'].'|'.$blended_reg['address4'].'|'.$district.'|'.$city.'|'.$blended_reg['state'].'|'.$blended_reg['pincode'].'|'.$dateofbirth.'|'.$blended_reg['email'].'|'.$blended_reg['mobile'].'|'.$blended_reg['res_stdcode'].$blended_reg['residential_phone'].'|'.$blended_reg['stdcode'].$blended_reg['office_phone'].'|'.$blended_reg['specify_qualification'].'|'.$qualification_name.'|'.$blended_reg['designation'].'|'.$designation_name.'|'.$blended_reg['associatedinstitute'].'|'.$associatedinstitute_name.'|'.$specified_qualification_name.'|'.$blended_reg['emergency_contact_no'].'|'.$blended_reg['blood_group'].'|'.$fees.'|'.$pay_status.'|'.$trn_date.'|'.$invoice_no.'|'.$tax_type.'|'.$app_type.'|'.$exempt.'|'.$transaction_no.'|'.$pay_type.'|'.$blended_reg['emergency_name'].'|'.$blended_reg['attempt'].'|'.$blended_reg['training_type'].'|'.$blended_reg['gstin_no']."\n";
								$i++;
								$mem_cnt++;
							}
						}
					}
					$blended_flg = fwrite($fp, $data);
					if($blended_flg)
					$success['blended_reg'] = "Blended Details File Generated Successfully. ";
					else
					$error['blended_reg'] = "Error While Generating Blended Details File.";
					fwrite($fp1, "\n"."Total Blended Applications = ".$mem_cnt."\n");
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
				$this->log_model->cronlog("Blended Details Cron End", $desc);
				fwrite($fp1, "\n"."********** Blended Details Cron End ".$end_time." ***********"."\n");
				fclose($fp1);
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
	function resize_image_max($image,$max_width,$max_height) 
	{
		ini_set("memory_limit","256M");
		ini_set("gd.jpeg_ignore_warning", 1);
		$org_img = $image;
		$image = @ImageCreateFromJpeg($image);
		if (!$image)
		{
			$image= imagecreatefromstring(file_get_contents($org_img));
		}
		$w = imagesx($image); //current width
		$h = imagesy($image); //current height
		if ((!$w) || (!$h)) { $GLOBALS['errors'][] = 'Image couldn\'t be resized because it wasn\'t a valid image.'; return false; }
		if (($w <= $max_width) && ($h <= $max_height)) { return $image; } //no resizing needed
		//try max width first...
		$ratio = $max_width / $w;
		$new_w = $max_width;
		$new_h = $h * $ratio;
		//if that didn't work
		if ($new_h > $max_height) {
			$ratio = $max_height / $h;
			$new_h = $max_height;
			$new_w = $w * $ratio;
		}
		$new_image = imagecreatetruecolor ($new_w, $new_h);
		imagecopyresampled($new_image,$image, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
		return $new_image;
	}
	/* Fuction to fetch DRA Inst./Accredited Institute registrations */
	public function dra_inst()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$dra_inst_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		//$current_date = "20171107";	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		// Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("DRA Institute Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "dra_inst_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** DRA Institute Cron Start - ".$start_time." ******************** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2018-01-30';
			// get DRA Institute registration details for given date
			$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
			$this->db->join('payment_transaction b','b.ref_id=c.id','LEFT');
			$dra_inst_data = $this->Master_model->getRecords('dra_inst_registration c',array(' DATE(created_on)' => $yesterday,'pay_type' => 12,'c.status' => 1,'b.status' => '1'),$select);
			if(count($dra_inst_data))
			{
				$data = '';
				$i = 1;
				$mem_cnt = 0;
				foreach($dra_inst_data as $dra_inst_reg)
				{										
//INST_NAME|ESTB_YEAR|MAIN_ADR_1|MAIN_ADR_2|MAIN_ADR_3|MAIN_ADR_4|MAIN_ADR_5|MAIN_ADR_6|MAIN_STE_CD|MAIN_PIN_CD|INST_PHONE_NO|INST_FAX_NO|INST_WEBSITE|INST_HEAD_NAME|INST_HEAD_CONTACT_NO|INST_HEAD_EMAIL|LOC_NAME|LOC_ADR_1|LOC_ADR_2|LOC_ADR_3|LOC_ADR_4|LOC_ADR_5|LOC_ADR_6|LOC_STE_CD|LOC_PIN_CD|OFFICE_NO|CONTACT_PERSON_NAME|CONTACT_PERSON_MOBILE|CONTACT_PERSON_EMAIL|INST_TYPE|DUE_DLGNC|TRN_DATE|FEE_AMT|TRN_NO|
					$trn_date = '';
					if($dra_inst_reg['date'] != '0000-00-00')
					{
						$trn_date = date('d-M-Y',strtotime($dra_inst_reg['date']));
					}
					$data .= ''.$dra_inst_reg['inst_name'].'|'.$dra_inst_reg['estb_year'].'|'.$dra_inst_reg['main_address1'].'|'.$dra_inst_reg['main_address2'].'|'.$dra_inst_reg['main_address3'].'|'.$dra_inst_reg['main_address4'].'|'.$dra_inst_reg['main_district'].'|'.$dra_inst_reg['main_city'].'|'.$dra_inst_reg['main_state'].'|'.$dra_inst_reg['main_pincode'].'|'.$dra_inst_reg['inst_phone'].'|'.$dra_inst_reg['inst_fax_no'].'|'.$dra_inst_reg['inst_website'].'|'.$dra_inst_reg['inst_head_name'].'|'.$dra_inst_reg['inst_head_contact_no'].'|'.$dra_inst_reg['inst_head_email'].'|'.$dra_inst_reg['location_name'].'|'.$dra_inst_reg['address1'].'|'.$dra_inst_reg['address2'].'|'.$dra_inst_reg['address3'].'|'.$dra_inst_reg['address4'].'|'.$dra_inst_reg['district'].'|'.$dra_inst_reg['city'].'|'.$dra_inst_reg['state'].'|'.$dra_inst_reg['pincode'].'|'.$dra_inst_reg['contact_person_name'].'|'.$dra_inst_reg['contact_person_mobile'].'|'.$dra_inst_reg['email_id'].'|'.$dra_inst_reg['email_id'].'|'.$dra_inst_reg['inst_type'].'|'.$dra_inst_reg['due_diligence'].'|'.$trn_date.'|'.$dra_inst_reg['amount'].'|'.$dra_inst_reg['transaction_no']."|\n";
					$i++;
					$mem_cnt++;
				}
				$dra_inst_flg = fwrite($fp, $data);
				if($dra_inst_flg)
						$success['dra_inst_reg'] = "DRA Institute Details File Generated Successfully. ";
				else
					$error['dra_inst_reg'] = "Error While Generating DRA Institute Details File.";
				fwrite($fp1, "\n"."Total DRA Institute Applications = ".$mem_cnt."\n");
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
			$this->log_model->cronlog("DRA Institute Details Cron End", $desc);
			fwrite($fp1, "\n"."********** DRA Institute Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	/* Bulk Exam Application Cron */
	public function bulk_exam_old()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Bulk Candidate Exam Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "bulk_exam_cand_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Bulk Candidate Exam Details Cron Start - ".$start_time." ******************** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			$yesterday = '2018-02-12';
			// get payment transaction for given date // DATE(date) = '".$yesterday."' OR 
			//$this->db->where("(DATE(updated_date) = '".$yesterday."') AND status = 1");
			$this->db->where_in('UTR_no',array('33278-Patiala-13102020','17528-Narsingpur-13102020','19224-MAHASAMUND-13102020','31371-DEOGHAR-13102020','13374-EAST SINGHBUM-13102020','19234-PALANPUR-13102020','13384-LOHARDAGA-13102020','13546-UJJAIN-13-10-2020','31220-KABIRDHAM-13-10-2020','17229-SURGUJA-13-10-2020'));
			$this->db->where('status','1');
			$bulk_payment = $this->Master_model->getRecords('bulk_payment_transaction');
			if(count($bulk_payment))
			{
				$data = '';
				$i = 1;
				$exam_cnt = 0;
				foreach($bulk_payment as $payment)
				{
					// get exam invoice for this transaction
					$this->db->where('transaction_no !=','');
					$this->db->where('app_type','Z');
					$exam_inv_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$payment['id']));
					// get all exam applications invloved in this transaction
					$this->db->join('member_exam b','a.memexamid = b.id','LEFT');
					$mem_exam_data = $this->Master_model->getRecords('bulk_member_payment_transaction a',array('ptid'=>$payment['id']));
					if(count($mem_exam_data))
					{
						foreach($mem_exam_data as $exam)
						{
							$syllabus_code = '';
							$part_no = '';
							$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
							if(count($subject_data)>0)
							{
								$syllabus_code = $subject_data[0]['syllabus_code'];
								$part_no = $subject_data[0]['part_no'];
							}
							$exam_mode = 'O';
							if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
							else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
							else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
							$exam_period = '';
							if($exam['examination_date'] != '' && $exam['examination_date'] != "0000-00-00")
							{
								$ex_period = $this->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam['examination_date']));
								if(count($ex_period))
								{
									$exam_period = $ex_period[0]['period'];	
								}
							}
							else
							{
								$exam_period = $exam['exam_period'];
							}
							$exam_code = '';
							if($exam['exam_code'] == 340 || $exam['exam_code'] == 3400 || $exam['exam_code'] == 34000){
								$exam_code = 34;
							}elseif($exam['exam_code'] == 580 || $exam['exam_code'] == 5800 || $exam['exam_code'] == 58000){
								$exam_code = 58;
							}elseif($exam['exam_code'] == 1600 || $exam['exam_code'] == 16000){
								$exam_code = 160;
							}elseif($exam['exam_code'] == 200){
								$exam_code = 20;
							}elseif($exam['exam_code'] == 1770 || $exam['exam_code'] == 17700){
								$exam_code =177;
							}elseif ($exam['exam_code'] == 590){
								$exam_code = 59;
							}elseif ($exam['exam_code'] == 810){
								$exam_code = 81;
							}elseif ($exam['exam_code'] == 1750){
								$exam_code = 175;
							}elseif ($exam['exam_code'] == 1010 || $exam['exam_code'] == 10100 || $exam['exam_code'] == 101000){
								$exam_code = 101;
							}
							else{
								$exam_code = $exam['exam_code'];
							}
							$trans_date = '';
							$transaction_no = '';
							if($payment['gateway']==1){	
								$transaction_no = $payment['UTR_no'];
								$trans_date = date('d-M-Y',strtotime($payment['updated_date']));
							}
							else if($payment['gateway']==2){	
								$transaction_no = $payment['transaction_no'];
								$trans_date = date('d-M-Y',strtotime($payment['created_date']));	
							}
							if($exam['institute_id'] != 0){
								$bulk_flg = 'Y';
							} else { 
							   	$bulk_flg = 'N'; 
							}
							$place_of_work = '';
							$pin_code_place_of_work = '';
							$state_place_of_work = '';
							$city = '';
							$branch = '';
							$branch_name = '';
							$state = '';
							$pincode = '';
							//$exam['elected_sub_code']!=0
							if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == $this->config->item('examCodeCaiibElective68') || $exam_code == $this->config->item('examCodeCaiibElective69') || $exam_code == $this->config->item('examCodeCaiibElective70') || $exam_code == $this->config->item('examCodeCaiibElective71') || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) 
							{
								if($exam['state']){	$state = $exam['state']; }
								if($exam['pincode']){ $pincode = $exam['pincode']; }
								if(strlen($exam['city']) > 30) { 
									$city = substr($exam['city'],0,29);
								}
								else { 
									$city = $exam['city'];	
								}
								if($exam['editedon'] < "2016-12-29 00:00:00") {
									$branch = $exam['branch'];
								} 
								else if($exam['editedon'] >= "2016-12-29") 
								{
									if(is_numeric($exam['office']))
									{
										if($exam['branch']!='')
											$branch = $exam['branch'];
										else
											$branch = $city;
									} else
									{
										if($exam['branch']!='')
											$branch = $exam['branch'];
										else
											$branch = $exam['office'];
									}
								}
								if($branch == ''){
									$branch = $city;
								}
								if(strlen($branch) > 20)
								{	$branch_name = substr($branch,0,19); }
								else
								{	$branch_name = $branch;	}
								if($exam['place_of_work'])
									$place_of_work = $exam['place_of_work'];
								else
									$place_of_work =  $branch_name;
								if($exam['pin_code_place_of_work'])
									$pin_code_place_of_work = $exam['pin_code_place_of_work'];
								else
									$pin_code_place_of_work =  $pincode;
								if($exam['state_place_of_work'])
									$state_place_of_work = $exam['state_place_of_work'];
								else
									$state_place_of_work =  $state;
								$elected_sub_code = '';
								if($exam_code == $this->config->item('examCodeCaiib'))
								{	$elected_sub_code = $exam['elected_sub_code'];	}
								if(strlen($place_of_work) > 30)
								{	$place_of_work = substr($place_of_work,0,29);		}
								else
								{	$place_of_work = $place_of_work;	}
//EXM_CD|EXM_PRD|MEM_NO|MEM_TYP|MED_CD|CTR_CD|ONLINE_OFFLINE_YN|SYLLABUS_CODE|CURRENCY_CODE|EXM_PART|SUB_CD|PLACE_OF_WORK|POW_PINCD|POW_STE_CD|BDRNO|TRN_DATE|FEE_AMT|INSTRUMENT_NO|SCRIBE_FLG|INS_CD|DISCOUNT_PER|TDS_AMT|BULK_FLG(Y/N)|
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['member_type'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam_inv_data[0]['transaction_no'].'|'.$trans_date.'|'.$payment['amount'].'|'.$exam_inv_data[0]['transaction_no'].'|'.$exam['scribe_flag'].'|'.$exam['institute_id'].'|'.$exam_inv_data[0]['disc_rate'].'|'.$exam_inv_data[0]['tds_amt'].'|'.$bulk_flg."\n";
							}
							else
							{
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['member_type'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam_inv_data[0]['transaction_no'].'|'.$trans_date.'|'.$payment['amount'].'|'.$exam_inv_data[0]['transaction_no'].'|'.$exam['scribe_flag'].'|'.$exam['institute_id'].'|'.$exam_inv_data[0]['disc_rate'].'|'.$exam_inv_data[0]['tds_amt'].'|'.$bulk_flg."\n";
							}
							$exam_cnt++;
						}
					}
					$i++;
				}
				fwrite($fp1, "Total Bulk Exam Applications - ".$exam_cnt."\n");
				$exam_file_flg = fwrite($fp, $data);
				if($exam_file_flg)
					$success['Bulk_exam'] = "Bulk Candidate Exam Details File Generated Successfully."; 
				else
					$error['Bulk_exam'] = "Error While Generating Bulk Candidate Exam Details File.";
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
			$this->log_model->cronlog("Bulk Candidate Exam Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Bulk Candidate Exam Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	/* Bulk Exam Application Cron */
	public function bulk_exam_old_ch()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Bulk Candidate Exam Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "bulk_exam_cand_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Bulk Candidate Exam Details Cron Start - ".$start_time." ******************** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			//$yesterday = '2020-09-15';
			// get payment transaction for given date // DATE(date) = '".$yesterday."' OR 
			//$this->db->where("(DATE(updated_date) = '".$yesterday."') AND status = 1");
			$this->db->where_in('UTR_no',array('38437-CHANDAULI-03112020'));
			$this->db->where('status','1');
			$bulk_payment = $this->Master_model->getRecords('bulk_payment_transaction');
			if(count($bulk_payment))
			{
				$data = '';
				$i = 1;
				$exam_cnt = 0;
				foreach($bulk_payment as $payment)
				{
					// get exam invoice for this transaction
					$this->db->where('transaction_no !=','');
					$this->db->where('app_type','Z');
					$exam_inv_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$payment['id']));
					// get all exam applications invloved in this transaction
					$this->db->join('member_exam b','a.memexamid = b.id','LEFT');
					$mem_exam_data = $this->Master_model->getRecords('bulk_member_payment_transaction a',array('ptid'=>$payment['id']));
					//,'b.exam_period'=>'777'
					if(count($mem_exam_data))
					{
						foreach($mem_exam_data as $exam)
						{
							$syllabus_code = '';
							$part_no = '';
							$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
							if(count($subject_data)>0)
							{
								$syllabus_code = $subject_data[0]['syllabus_code'];
								$part_no = $subject_data[0]['part_no'];
							}
							$exam_mode = 'O';
							if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
							else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
							else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
							$exam_period = '';
							if($exam['examination_date'] != '' && $exam['examination_date'] != "0000-00-00")
							{
								$ex_period = $this->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam['examination_date']));
								if(count($ex_period))
								{
									$exam_period = $ex_period[0]['period'];	
								}
							}
							else
							{
								$exam_period = $exam['exam_period'];
							}
							$exam_code = '';
							if($exam['exam_code'] == 340 || $exam['exam_code'] == 3400 || $exam['exam_code'] == 34000){
								$exam_code = 34;
							}elseif($exam['exam_code'] == 580 || $exam['exam_code'] == 5800 || $exam['exam_code'] == 58000){
								$exam_code = 58;
							}elseif($exam['exam_code'] == 1600 || $exam['exam_code'] == 16000){
								$exam_code = 160;
							}elseif($exam['exam_code'] == 200){
								$exam_code = 20;
							}elseif($exam['exam_code'] == 1770 || $exam['exam_code'] == 17700){
								$exam_code =177;
							}elseif ($exam['exam_code'] == 590){
								$exam_code = 59;
							}elseif ($exam['exam_code'] == 810){
								$exam_code = 81;
							}elseif ($exam['exam_code'] == 1750){
								$exam_code = 175;
							}elseif ($exam['exam_code'] == 1010 || $exam['exam_code'] == 10100 || $exam['exam_code'] == 101000){
								$exam_code = 101;
							}
							else{
								$exam_code = $exam['exam_code'];
							}
							$trans_date = '';
							$transaction_no = '';
							if($payment['gateway']==1){	
								$transaction_no = $payment['UTR_no'];
								$trans_date = date('d-M-Y',strtotime($payment['updated_date']));
							}
							else if($payment['gateway']==2){	
								$transaction_no = $payment['transaction_no'];
								$trans_date = date('d-M-Y',strtotime($payment['created_date']));	
							}
							if($exam['institute_id'] != 0){
								$bulk_flg = 'Y';
							} else { 
							   	$bulk_flg = 'N'; 
							}
							$place_of_work = '';
							$pin_code_place_of_work = '';
							$state_place_of_work = '';
							$city = '';
							$branch = '';
							$branch_name = '';
							$state = '';
							$pincode = '';
							$original_base_fee=$calculate_discount=$taken_discount=0;
							$bulk_discount_flg = $exam['bulk_discount_flg'];
							$original_base_fee = $exam['original_base_fee'];
							$discount_percent = $exam['discount_percent'];
							$calculate_discount = $exam['calculate_discount'];
							$taken_discount = $exam['taken_discount'];
							$elearning_flag = $exam['elearning_flag'];
							$base_fee = $exam['base_fee'];
							$disc_rate = 0.00;
							//$exam['elected_sub_code']!=0
							if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == $this->config->item('examCodeCaiibElective68') || $exam_code == $this->config->item('examCodeCaiibElective69') || $exam_code == $this->config->item('examCodeCaiibElective70') || $exam_code == $this->config->item('examCodeCaiibElective71') || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) 
							{
								if($exam['state']){	$state = $exam['state']; }
								if($exam['pincode']){ $pincode = $exam['pincode']; }
								if(strlen($exam['city']) > 30) { 
									$city = substr($exam['city'],0,29);
								}
								else { 
									$city = $exam['city'];	
								}
								if($exam['editedon'] < "2016-12-29 00:00:00") {
									$branch = $exam['branch'];
								} 
								else if($exam['editedon'] >= "2016-12-29") 
								{
									if(is_numeric($exam['office']))
									{
										if($exam['branch']!='')
											$branch = $exam['branch'];
										else
											$branch = $city;
									} else
									{
										if($exam['branch']!='')
											$branch = $exam['branch'];
										else
											$branch = $exam['office'];
									}
								}
								if($branch == ''){
									$branch = $city;
								}
								if(strlen($branch) > 20)
								{	$branch_name = substr($branch,0,19); }
								else
								{	$branch_name = $branch;	}
								if($exam['place_of_work'])
									$place_of_work = $exam['place_of_work'];
								else
									$place_of_work =  $branch_name;
								if($exam['pin_code_place_of_work'])
									$pin_code_place_of_work = $exam['pin_code_place_of_work'];
								else
									$pin_code_place_of_work =  $pincode;
								if($exam['state_place_of_work'])
									$state_place_of_work = $exam['state_place_of_work'];
								else
									$state_place_of_work =  $state;
								$elected_sub_code = '';
								if($exam_code == $this->config->item('examCodeCaiib'))
								{	$elected_sub_code = $exam['elected_sub_code'];	}
								if(strlen($place_of_work) > 30)
								{	$place_of_work = substr($place_of_work,0,29);		}
								else
								{	$place_of_work = $place_of_work;	}
//EXM_CD|EXM_PRD|MEM_NO|MEM_TYP|MED_CD|CTR_CD|ONLINE_OFFLINE_YN|SYLLABUS_CODE|CURRENCY_CODE|EXM_PART|SUB_CD|PLACE_OF_WORK|POW_PINCD|POW_STE_CD|BDRNO|TRN_DATE|FEE_AMT|INSTRUMENT_NO|SCRIBE_FLG|INS_CD|DISCOUNT_PER|TDS_AMT|BULK_FLG(Y/N)|BULK_DISCOUNT_FLG(Y/N)|ORIGINAL_BASE_FEE|DISCOUNT_PERCENT|CALCULATE_DISCOUNT|TAKEN_DISCOUNT| ELEARNING_FLAG(Y/N)
		// $exam_inv_data[0]['disc_rate'] 
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['member_type'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam_inv_data[0]['transaction_no'].'|'.$trans_date.'|'.$payment['amount'].'|'.$exam_inv_data[0]['transaction_no'].'|'.$exam['scribe_flag'].'|'.$exam['institute_id'].'|'.$disc_rate.'|'.$exam_inv_data[0]['tds_amt'].'|'.$bulk_flg.'|'.$bulk_discount_flg.'|'.$original_base_fee.'|'.$discount_percent.'|'.$calculate_discount.'|'.$taken_discount.'|'.$elearning_flag.'|'.$base_fee."\n";
							}
							else
							{
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['member_type'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam_inv_data[0]['transaction_no'].'|'.$trans_date.'|'.$payment['amount'].'|'.$exam_inv_data[0]['transaction_no'].'|'.$exam['scribe_flag'].'|'.$exam['institute_id'].'|'.$disc_rate.'|'.$exam_inv_data[0]['tds_amt'].'|'.$bulk_flg.'|'.$bulk_discount_flg.'|'.$original_base_fee.'|'.$discount_percent.'|'.$calculate_discount.'|'.$taken_discount.'|'.$elearning_flag.'|'.$base_fee."\n";
							}
							$exam_cnt++;
						}
					}
					$i++;
				}
				fwrite($fp1, "Total Bulk Exam Applications - ".$exam_cnt."\n");
				$exam_file_flg = fwrite($fp, $data);
				if($exam_file_flg)
					$success['Bulk_exam'] = "Bulk Candidate Exam Details File Generated Successfully."; 
				else
					$error['Bulk_exam'] = "Error While Generating Bulk Candidate Exam Details File.";
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
			$this->log_model->cronlog("Bulk Candidate Exam Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Bulk Candidate Exam Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	/* Bulk Exam Application Cron */
	public function bulk_exam()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Bulk Candidate Exam Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "bulk_exam_cand_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Bulk Candidate Exam Details Cron Start - ".$start_time." ******************** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			$yesterday = '2021-08-17';
			// get payment transaction for given date // DATE(date) = '".$yesterday."' OR 
			//$this->db->where("(DATE(updated_date) = '".$yesterday."') AND status = 1");
			$this->db->where_in('receipt_no',array(2954));
			$this->db->where('status','1');
			//$this->db->where("status = 1");
			$bulk_payment = $this->Master_model->getRecords('bulk_payment_transaction');
			//echo $this->db->last_query().'<br>'; 
			if(count($bulk_payment))
			{
				$data = '';
				$i = 1;
				$exam_cnt = 0;
				foreach($bulk_payment as $payment)
				{
					// get exam invoice for this transaction
					$this->db->where('transaction_no !=','');
					$this->db->where('app_type','Z');
					$exam_inv_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$payment['id']));
					// get all exam applications invloved in this transaction
					$this->db->join('member_exam b','a.memexamid = b.id','LEFT');
					$this->db->join('admit_card_details c','a.memexamid = c.mem_exam_id','LEFT');
					$mem_exam_data = $this->Master_model->getRecords('bulk_member_payment_transaction a',array('ptid'=>$payment['id']));
					//,'b.exam_period'=>'777'
					//echo $this->db->last_query();  die;
					if(count($mem_exam_data))
					{
						foreach($mem_exam_data as $exam)
						{
							$syllabus_code = '';
							$part_no = '';
							$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
							if(count($subject_data)>0)
							{
								$syllabus_code = $subject_data[0]['syllabus_code'];
								$part_no = $subject_data[0]['part_no'];
							}
							$exam_mode = 'O';
							if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
							else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
							else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
							$exam_period = '';
							if($exam['examination_date'] != '' && $exam['examination_date'] != "0000-00-00")
							{
								$ex_period = $this->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam['examination_date']));
								if(count($ex_period))
								{
									$exam_period = $ex_period[0]['period'];	
								}
							}
							else
							{
								$exam_period = $exam['exam_period'];
							}
							$exam_code = '';
							if($exam['exam_code'] == 340 || $exam['exam_code'] == 3400 || $exam['exam_code'] == 34000){
								$exam_code = 34;
							}elseif($exam['exam_code'] == 580 || $exam['exam_code'] == 5800 || $exam['exam_code'] == 58000){
								$exam_code = 58;
							}elseif($exam['exam_code'] == 1600 || $exam['exam_code'] == 16000){
								$exam_code = 160;
							}elseif($exam['exam_code'] == 200){
								$exam_code = 20;
							}elseif($exam['exam_code'] == 1770 || $exam['exam_code'] == 17700){
								$exam_code =177;
							}elseif ($exam['exam_code'] == 590){
								$exam_code = 59;
							}elseif ($exam['exam_code'] == 810){
								$exam_code = 81;
							}elseif ($exam['exam_code'] == 1750){
								$exam_code = 175;
							}elseif ($exam['exam_code'] == 1010 || $exam['exam_code'] == 10100 || $exam['exam_code'] == 101000){
								$exam_code = 101;
							}
							elseif ($exam['exam_code'] == 2010){
								$exam_code = 1010;
							}
							else{
								$exam_code = $exam['exam_code'];
							}
							$trans_date = '';
							$transaction_no = '';
							if($payment['gateway']==1){	
								$transaction_no = $payment['UTR_no'];
								$trans_date = date('d-M-Y',strtotime($payment['updated_date']));
							}
							else if($payment['gateway']==2){	
								$transaction_no = $payment['transaction_no'];
								$trans_date = date('d-M-Y',strtotime($payment['created_date']));	
							}
							if($exam['institute_id'] != 0){
								$bulk_flg = 'Y';
							} else { 
							   	$bulk_flg = 'N'; 
							}
							$exam_date = '';
							if($exam['exam_date'] != '0000-00-00' && $exam['exam_date'] != '' )
							{
								$exam_date = date('d-M-Y',strtotime($exam['exam_date']));
							}
							//echo $exam_date; die;
							$place_of_work = '';
							$pin_code_place_of_work = '';
							$state_place_of_work = '';
							$city = '';
							$branch = '';
							$branch_name = '';
							$state = '';
							$pincode = '';
							$original_base_fee=$calculate_discount=$taken_discount=0;
							$bulk_discount_flg = $exam['bulk_discount_flg'];
							$original_base_fee = $exam['original_base_fee'];
							$discount_percent = $exam['discount_percent'];
							$calculate_discount = $exam['calculate_discount'];
							$taken_discount = $exam['taken_discount'];
							$elearning_flag = $exam['elearning_flag'];
							$base_fee = $exam['base_fee'];
							$disc_rate = 0.00;
							//$exam['elected_sub_code']!=0
							if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == $this->config->item('examCodeCaiibElective68') || $exam_code == $this->config->item('examCodeCaiibElective69') || $exam_code == $this->config->item('examCodeCaiibElective70') || $exam_code == $this->config->item('examCodeCaiibElective71') || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) 
							{
								if($exam['state']){	$state = $exam['state']; }
								if($exam['pincode']){ $pincode = $exam['pincode']; }
								if(strlen($exam['city']) > 30) { 
									$city = substr($exam['city'],0,29);
								}
								else { 
									$city = $exam['city'];	
								}
								if($exam['editedon'] < "2016-12-29 00:00:00") {
									$branch = $exam['branch'];
								} 
								else if($exam['editedon'] >= "2016-12-29") 
								{
									if(is_numeric($exam['office']))
									{
										if($exam['branch']!='')
											$branch = $exam['branch'];
										else
											$branch = $city;
									} else
									{
										if($exam['branch']!='')
											$branch = $exam['branch'];
										else
											$branch = $exam['office'];
									}
								}
								if($branch == ''){
									$branch = $city;
								}
								if(strlen($branch) > 20)
								{	$branch_name = substr($branch,0,19); }
								else
								{	$branch_name = $branch;	}
								if($exam['place_of_work'])
									$place_of_work = $exam['place_of_work'];
								else
									$place_of_work =  $branch_name;
								if($exam['pin_code_place_of_work'])
									$pin_code_place_of_work = $exam['pin_code_place_of_work'];
								else
									$pin_code_place_of_work =  $pincode;
								if($exam['state_place_of_work'])
									$state_place_of_work = $exam['state_place_of_work'];
								else
									$state_place_of_work =  $state;
								$elected_sub_code = '';
								if($exam_code == $this->config->item('examCodeCaiib'))
								{	$elected_sub_code = $exam['elected_sub_code'];	}
								if(strlen($place_of_work) > 30)
								{	$place_of_work = substr($place_of_work,0,29);		}
								else
								{	$place_of_work = $place_of_work;	}
//EXM_CD|EXM_PRD|MEM_NO|MEM_TYP|MED_CD|CTR_CD|ONLINE_OFFLINE_YN|SYLLABUS_CODE|CURRENCY_CODE|EXM_PART|SUB_CD|PLACE_OF_WORK|POW_PINCD|POW_STE_CD|BDRNO|TRN_DATE|FEE_AMT|INSTRUMENT_NO|SCRIBE_FLG|INS_CD|DISCOUNT_PER|TDS_AMT|BULK_FLG(Y/N)|BULK_DISCOUNT_FLG(Y/N)|ORIGINAL_BASE_FEE|DISCOUNT_PERCENT|CALCULATE_DISCOUNT|TAKEN_DISCOUNT| ELEARNING_FLAG(Y/N)
		// $exam_inv_data[0]['disc_rate'] 
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['member_type'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam_inv_data[0]['transaction_no'].'|'.$trans_date.'|'.$payment['amount'].'|'.$exam_inv_data[0]['transaction_no'].'|'.$exam['scribe_flag'].'|'.$exam['institute_id'].'|'.$disc_rate.'|'.$exam_inv_data[0]['tds_amt'].'|'.$bulk_flg.'|'.$bulk_discount_flg.'|'.$original_base_fee.'|'.$discount_percent.'|'.$calculate_discount.'|'.$taken_discount.'|'.$elearning_flag.'|'.$base_fee.'|'.$exam_date."\n";
							}
							else
							{
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['member_type'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam_inv_data[0]['transaction_no'].'|'.$trans_date.'|'.$payment['amount'].'|'.$exam_inv_data[0]['transaction_no'].'|'.$exam['scribe_flag'].'|'.$exam['institute_id'].'|'.$disc_rate.'|'.$exam_inv_data[0]['tds_amt'].'|'.$bulk_flg.'|'.$bulk_discount_flg.'|'.$original_base_fee.'|'.$discount_percent.'|'.$calculate_discount.'|'.$taken_discount.'|'.$elearning_flag.'|'.$base_fee.'|'.$exam_date."\n";
							}
							$exam_cnt++;
						}
					}
					$i++;
				}
				fwrite($fp1, "Total Bulk Exam Applications - ".$exam_cnt."\n");
				$exam_file_flg = fwrite($fp, $data);
				if($exam_file_flg)
					$success['Bulk_exam'] = "Bulk Candidate Exam Details File Generated Successfully."; 
				else
					$error['Bulk_exam'] = "Error While Generating Bulk Candidate Exam Details File.";
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
			$this->log_model->cronlog("Bulk Candidate Exam Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Bulk Candidate Exam Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	/* Bulk Exam Admit Card */
	public function bulk_admit_card()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("Bulk Exam Admit Card Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "bulk_admit_card_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w'); 
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Bulk Exam Admit Card Details Cron Start - ".$start_time." ********** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday = '2021-10-11';
			//DATE(date) = '".$yesterday."' OR
			//$this->db->where("( DATE(updated_date) = '".$yesterday."') AND status = 1");
			$this->db->where_in('UTR_no',array('31106-CHAMOLI-UTTARKHAND-16112021'));
			$bulk_payment = $this->Master_model->getRecords('bulk_payment_transaction');
			echo $this->db->last_query();
			if(count($bulk_payment))
			{
				$data = '';
				$i = 1;
				$exam_cnt = 0;
				$admit_card_count = 0;
				$admit_card_sub_count = 0;
				foreach($bulk_payment as $payment)
				{
					// get exam invoice for this transaction
					/*$this->db->where('transaction_no !=','');
					$this->db->where('app_type','Z');
					$exam_inv_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$payment['id']));*/
					// get all exam applications invloved in this transaction
					$this->db->join('member_exam b','a.memexamid = b.id','LEFT');
					$cand_exam_data = $this->Master_model->getRecords('bulk_member_payment_transaction a',array('ptid'=>$payment['id']));
						if(count($cand_exam_data))
						{
							foreach($cand_exam_data as $exam)
							{
								$mem_exam_id = $exam['memexamid'];
								// get admit card details for this member by mem_exam_id
								$this->db->where('remark', 1);
								$admit_card_details_arr = $this->Master_model->getRecords('admit_card_details',array('mem_exam_id' => $mem_exam_id));
								if(count($admit_card_details_arr))
								{
									foreach($admit_card_details_arr as $admit_card_data)
									{
										$data = '';
										$exam_date = date('d-M-y',strtotime($admit_card_data['exam_date']));
										//$trn_date = date('d-M-y',strtotime($admit_card_data['created_on']));
										$trn_date = date('d-M-y',strtotime($payment['updated_date']));
										$venueadd1 = '';
										if($admit_card_data['venueadd1'] != '')
										{
											$venueadd1 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd1']));
											$venueadd1 = str_replace(array("\n", "\r", "|"), '', $venueadd1);
										}
										$venueadd2 = '';
										if($admit_card_data['venueadd2'] != '')
										{
											$venueadd2 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd2']));
											$venueadd2 = str_replace(array("\n", "\r", "|"), '', $venueadd2);
										}
										$venueadd3 = '';
										if($admit_card_data['venueadd3'] != '')
										{
											$venueadd3 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd3']));
											$venueadd3 = str_replace(array("\n", "\r", "|"), '', $venueadd3);
										}
										// code to get actual exam period for exam application, added by Bhagwan Sahane, on 28-09-2017
										$exam_period = '';
										if($exam['examination_date'] != '' && $exam['examination_date'] != "0000-00-00")
										{
											$ex_period = $this->Master_model->getRecords('special_exam_dates',array('examination_date'=>$exam['examination_date']));
											if(count($ex_period))
											{
												$exam_period = $ex_period[0]['period'];	
											}
										}
										else
										{
											$exam_period = $admit_card_data['exm_prd'];
										}
										/* Start Dyanamic Exam code get from database table : Bhushan 25 March 2019 */
										$exam_code = '';
										if($admit_card_data['exm_cd'] != '' && $admit_card_data['exm_cd'] != 0)
										{
											$ex_code = $this->master_model->getRecords('bulk_exam_activation_master',array('exam_code'=>$admit_card_data['exm_cd'])); // Added on 25 March 2019 - Bhushan (As per pooja changes for bulk)
											if(count($ex_code))
											{
												if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0)
												{	$exam_code = $ex_code[0]['original_val'];	}
												else
												{	$exam_code = $admit_card_data['exm_cd'];	}
											}
										}else{
											$exam_code = $admit_card_data['exm_cd'];
										}
										/* End Dyanamic Exam code get from database table : Bhushan 25 March 2019 */
//EXM_CD|EXM_PRD|MEMBER_NO|CTR_CD|CTR_NAM|SUB_CD|SUB_DSC|VENUE_CD|VENUE_ADDR1|VENUE_ADDR2|VENUE_ADDR3|VENUE_ADDR4|VENUE_ADDR5|VENUE_PINCODE|EXAM_SEAT_NO|EXAM_PASSWORD|EXAM_DATE|EXAM_TIME|EXAM_MODE(Online/Offline)| EXAM_MEDIUM|SCRIBE_FLG(Y/N)|VENDOR_CODE(1/3)|TRN_DATE|
										$data .= ''.$exam_code.'|'.$exam_period.'|'.$admit_card_data['mem_mem_no'].'|'.$admit_card_data['center_code'].'|'.$admit_card_data['center_name'].'|'.$admit_card_data['sub_cd'].'|'.$admit_card_data['sub_dsc'].'|'.$admit_card_data['venueid'].'|'.$venueadd1.'|'.$venueadd2.'|'.$venueadd3.'|'.$admit_card_data['venueadd4'].'|'.$admit_card_data['venueadd5'].'|'.$admit_card_data['venpin'].'|'.$admit_card_data['seat_identification'].'|'.$admit_card_data['pwd'].'|'.$exam_date.'|'.$admit_card_data['time'].'|'.$admit_card_data['mode'].'|'.$admit_card_data['m_1'].'|'.$admit_card_data['scribe_flag'].'|'.$admit_card_data['vendor_code'].'|'.$trn_date."\n";
										$admit_card_sub_count++;
										$file_w_flg = fwrite($fp, $data);
									}
									if($file_w_flg)
									{
										$success[] = "Bulk Exam Admit Card Details File Generated Successfully. ";
									}
									else
									{
										$error[] = "Error While Generating Bulk Exam Admit Card Details File.";
									}
								}
								$admit_card_count++;
							}
						}
				}
				fwrite($fp1, "\n"."Total Bulk Exam Admit Card Details Added = ".$admit_card_count."\n");
				fwrite($fp1, "\n"."Total Bulk Exam Admit Card Subject Details Added = ".$admit_card_sub_count."\n");
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
			$this->log_model->cronlog("Bulk Exam Admit Card Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Bulk Exam Admit Card Details Cron End ".$end_time." **********"."\n");
			fclose($fp1);
		}
	}
	/* Blended Member Feedback */
	public function blended_feedback_member()
	{
		ini_set("memory_limit", "-1");
		//error_reporting(E_ALL);
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$blended_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		// Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Blended Feedback Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date)){
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "blended_member_feedback_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Blended Member Feedback Details Cron Start - ".$start_time." ******************** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2018-08-03';
			$br_select = 'c.*';
			$br_blended_data = $this->Master_model->getRecords('blended_feedback_infrastructure c',array(' DATE(creation_date)' => $yesterday,'c.feedback_type' => 'I'),$br_select);
			if(count($br_blended_data)){
				$data = '';
				$i = 1;
				$mem_cnt = 0;
				$res = array();
				foreach($br_blended_data as $br_blended_reg){
					$batch_code = $br_blended_reg['batch_code'];
					$mem_details = array();
					$mem_details['member_no'] = $br_blended_reg['member_no'];
					$mem_details['mem_name'] = $br_blended_reg['mem_name'];
					$mem_details['batch_code'] = $batch_code;
					$mem_details['program_code'] = $br_blended_reg['program_code'];
					$this->db->order_by('topic_code','ASC');
					$blended_data = $this->Master_model->getRecords('infrastructure_feedback_question',array('isactive' => 1,'feedback_type' => 'I'));
					if(count($blended_data)){
						$q_details = array();
						foreach($blended_data as $key => $blended_reg){										
							$q_no = $key+1;
							$q_details[$key]['qes'] = $blended_reg['question'];
							if($blended_reg['option1'] != ''){
								$q_details[$key]['ans'] = $br_blended_reg['q'.$q_no.'_ans'];
							}
							else{
								$q_details[$key]['ans'] = $br_blended_reg['comment'];	
							}
							$i++;
							$mem_cnt++;
						}
						$mem_details['feedback_data'] = $q_details;	
					}
					$res[] = $mem_details;
				}
				$program_code = $batch_code = $mem_name = $member_no = $feedback_data = $member_name_no = '';
				foreach($res as $key1 => $details){	
					$program_code = $details['program_code'];
					$batch_code = $details['batch_code'];
					$mem_name = $details['mem_name'];
					$member_no = $details['member_no'];
					$feedback_data = $details['feedback_data'];
					$member_name_no = $mem_name."-".$member_no;
					$flag = 0;
					$b = 1;
					foreach($feedback_data as $key2 => $rows){
						$review_no = '';
						$review_arr = array('Excellent'	=> 5,'Very Good' => 4,'Good' => 3,'Fair' => 2,'Poor' => 1);
						foreach($review_arr as $k => $val){
							if($rows['ans'] == $k) { 
								$review_no = $val; 
							}
							else if($rows['qes'] == 'Any other comments :'){
								$review_no = $rows['ans']; 
								$flag = 1;
							}
						}
						$review_no = preg_replace("/\s+/", " ", $review_no);
						if($flag == 0){
							$data .= ''.$program_code.'|'.$batch_code.'|'.$member_name_no.'|'.'0'.$b.'.'.$rows['qes'].'||'.$review_no."\n";
						}
						else{
							$data .= ''.$program_code.'|'.$batch_code.'|'.$member_name_no.'|'.'0'.$b.'.'.$rows['qes'].'|'.$review_no."|5\n";
						}
						$b++;
					}
				}
				$blended_flg = fwrite($fp, $data);
				if($blended_flg)
						$success['blended_reg'] = "Blended MemberFeedback Details File Generated Successfully. ";
				else
					$error['blended_reg'] = "Error While Generating Blended Member Feedback Details File.";
				fwrite($fp1, "\n"."Total Blended Member Feedbacks = ".$mem_cnt."\n");
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Blended Member Feedback Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Blended Member Feedback Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
 	/* Blended Faculty Feedback */
	public function blended_feedback_faculty()
	{
		ini_set("memory_limit", "-1");
		//error_reporting(E_ALL);
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$blended_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		// Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Blended Feedback Faculty Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date)){
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "blended_faculty_feedback_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Blended Faculty Feedback Details Cron Start - ".$start_time." ******************** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2018-08-03';
			$br_select = 'c.*';
			$br_blended_data = $this->Master_model->getRecords('blended_feedback_Faculty c',array(' DATE(creation_date)' => $yesterday,'c.feedback_type' => 'F'),$br_select);
			if(count($br_blended_data)){
				$data = '';
				$i = 1;
				$mem_cnt = 0;
				$res = array();
				foreach($br_blended_data as $br_blended_reg){
					$batch_code = $br_blended_reg['batch_code'];
					$mem_details = array();
					$mem_details['batch_code'] = $batch_code;
					$mem_details['program_code'] = $br_blended_reg['program_code'];
					$this->db->order_by('f_id','ASC');
					$blended_data = $this->Master_model->getRecords('faculty_feedback_question',array('isactive' => 1,'feedback_type' => 'F','batch_code' => $batch_code));
					if(count($blended_data)){
						$q_details = array();
						foreach($blended_data as $key => $blended_reg){										
							$q_no = $key+1;
							$q_details[$key]['qes'] = $blended_reg['question'];
							$q_details[$key]['session_code'] = $blended_reg['session_code'];
							$q_details[$key]['batch_date'] = $blended_reg['batch_date'];
							$q_details[$key]['facilitator_code'] = $blended_reg['facilitator_code'];
							$q_details[$key]['topic_code'] = $blended_reg['topic_code'];
							if($blended_reg['option1'] != ''){
								$q_details[$key]['ans'] = $br_blended_reg['q'.$q_no.'_ans'];
							}
							else{
								$q_details[$key]['ans'] = $br_blended_reg['comment'];	
							}
							$i++;
							$mem_cnt++;
						}
						$mem_details['feedback_data'] = $q_details;	
					}
					$res[] = $mem_details;
				}
				$program_code = $batch_code = $feedback_data = '';
				foreach($res as $key1 => $details){	
					$program_code = $details['program_code'];
					$batch_code = $details['batch_code'];
					$feedback_data = $details['feedback_data'];
					foreach($feedback_data as $key2 => $rows){
						$review_no = '';
						$review_arr = array('Excellent'	=> 'E','Very Good' => 'V','Good' => 'G','Fair' => 'F','Poor' => 'P');
						foreach($review_arr as $k => $val){
							if($rows['ans'] == $k) { 
								$review_no = $val; 
							}
							else if($rows['qes'] == 'Any other comments :'){
								$review_no = $rows['ans']; 
							}
						}
						$review_no = preg_replace("/\s+/", " ", $review_no);
						$data .= ''.$program_code.'|'.$batch_code.'|'.$rows['session_code'].'|'.$rows['batch_date'].'|'.$rows['facilitator_code'].'|'.$rows['topic_code'].'|'.$review_no."\n";
					}
				}
				$blended_flg = fwrite($fp, $data);
				if($blended_flg)
						$success['blended_reg'] = "Blended Faculty Feedback Details File Generated Successfully. ";
				else
					$error['blended_reg'] = "Error While Generating Blended Faculty Feedback Details File.";
				fwrite($fp1, "\n"."Total Blended Faculty Feedbacks = ".$mem_cnt."\n");
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Blended Faculty Feedback Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Blended Faculty Feedback Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	/* Digital Elearning Reg. Cron : Bhushan 12/04/2019 */
	public function digital_elearning()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = $parent_dir_flg = $exam_file_flg = 0;
		$error = $success = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Digital Elearning Exam Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date)){
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "digital_elearning_exam_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n***** Digital Elearning Exam Details Cron Start - ".$start_time." ***** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			$yesterday = '2020-11-07';
			$mem = array(5364320);
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.scribe_flag';
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
			$this->db->where_in('a.id',$mem);
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>18,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1),$select);
			//' DATE(a.created_on)'=>$yesterday,
			if(count($can_exam_data)){
				$i = 1;
				$exam_cnt = 0;
				foreach($can_exam_data as $exam){
					$data = $exam_mode = $trans_date = $exam_period = $exam_code = '';
					$exam_mode = 'O';
					if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
					else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
					else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
					if($exam['date'] != '0000-00-00'){
						$trans_date = date('d-M-Y',strtotime($exam['date']));
					}
					$syllabus_code = '';
					$part_no = '';
					$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
					if(count($subject_data)>0)
					{
						$syllabus_code = $subject_data[0]['syllabus_code'];
						$part_no = $subject_data[0]['part_no'];
					}
					$exam_period = $exam['exam_period'];	
					$exam_code = $exam['exam_code'];	
					$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$exam['scribe_flag']."\n";
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "Digital Elearning Exam Details Cron File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating Digital Elearning Exam Details Cron File.";
					$i++;
					$exam_cnt++;
				}
				fwrite($fp1, "Total Digital Elearning Exam Applications - ".$exam_cnt."\n");
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			$end_time = date("Y-m-d H:i:s");
			$result = array("success"=>$success,"error"=>$error,"Start Time" =>$start_time,"End Time"=>$end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Digital Elearning Exam Details Cron End", $desc);
			fwrite($fp1, "\n"."***** Digital Elearning Exam Details Cron End ".$end_time." *****"."\n");
			fclose($fp1);
		}
	}
	/* Only for CSC Vendor */
	public function csc_member_old()
	{
	    ini_set("memory_limit", "-1");
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
		//$current_date = '20190603';
		$cron_file_dir = "./uploads/rahultest/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("CSC Member Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "iibf_csc_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_CSC_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "IIBF CSC New Member Details Cron Start - ".$start_time."\n");
			$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ($current_date) ));
			$this->db->join('payment_transaction b','b.member_regnumber = a.regnumber','LEFT');
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(date)'=>$yesterday,'pay_type'=>2,'isactive'=>'1','isdeleted'=>0,'bankcode' => 'csc', 'status'=>'1'));
			if(count($new_mem_reg)){
				$dirname = "csc_regd_image_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory)){
					array_map('unlink', glob($directory."/*.*"));
					rmdir($directory);
					$dir_flg = mkdir($directory, 0700);
				}
				else{
					$dir_flg = mkdir($directory, 0700);
				}
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				$i = 1;
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					$gender = '';
					if($reg_data['gender'] == 'male')	{ $gender = 'M';}
					else if($reg_data['gender'] == 'female')	{ $gender = 'F';}
					$photo = '';
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto'])){
						$photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					else{
						fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}
					$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto'])){
						$signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					else{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}
					$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto'])){
						$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					else{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
					$transaction_no = '';
					$transaction_date = '';
					$transaction_amt = '0';
					$trans_details = $this->Master_model->getRecords('payment_transaction a',array(' DATE(date)'=>$yesterday,'status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');	
					if(count($trans_details)){
						$transaction_no = $trans_details[0]['transaction_no'];
						$transaction_amt = $trans_details[0]['amount'];
						$date = $trans_details[0]['date'];
						if($date!='0000-00-00')
						{	$transaction_date = date('d-M-y',strtotime($date));	}
					}
					$mem_dob = '';
					if($reg_data['dateofbirth'] != '0000-00-00'){
						$mem_dob = date('d-M-y',strtotime($reg_data['dateofbirth']));
					}
					if(strlen($reg_data['stdcode']) > 10){	$std_code = substr($reg_data['stdcode'],0,9);	}
					else{	$std_code = $reg_data['stdcode'];	}
					if(strlen($reg_data['office']) > 20){	$branch = substr($reg_data['office'],0,19);	}
					else{	$branch = $reg_data['office'];	}
					if(strlen($reg_data['address1']) > 30){	$address1 = substr($reg_data['address1'],0,29);	}
					else{	$address1 = $reg_data['address1'];	}
					if(strlen($reg_data['address2']) > 30){	$address2 = substr($reg_data['address2'],0,29);	}
					else{	$address2 = $reg_data['address2'];	}
					if(strlen($reg_data['address3']) > 30){	$address3 = substr($reg_data['address3'],0,29);	}
					else{	$address3 = $reg_data['address3'];	}
					if(strlen($reg_data['address4']) > 30){	$address4 = substr($reg_data['address4'],0,29);	}
					else{	$address4 = $reg_data['address4'];	}
					if(strlen($reg_data['district']) > 30){	$district = substr($reg_data['district'],0,29);	}
					else{	$district = $reg_data['district'];	}
					if(strlen($reg_data['city']) > 30){	$city = substr($reg_data['city'],0,29);	}
					else{	$city = $reg_data['city'];	}
					//Member Number | Registration Type(O/NM) | Name Prefix | Firstname | Middlename | Lastname | Address1 | Address2 | Address3 | Address4 | District | City | Pincode | State | DOB | Gender| Designation | Email | Mobile  | Transaction Number | Transaction Date | Transaction Amount | photo | signature | idproofimg
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['registrationtype'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$reg_data['pincode'].'|'.$reg_data['state'].'|'.$mem_dob.'|'.$gender.'|'.$reg_data['designation'].'|'.$reg_data['email'].'|'.$reg_data['mobile'].'|'.$transaction_no.'|'.$transaction_date.'|'.$transaction_amt.'|'.$photo.'|'.$signature.'|'.$idproofimg."|\n";
					if($dir_flg)
					{
						// For photo images
						if($photo){
							$image = "./uploads/photograph/".$reg_data['scannedphoto'];
							$max_width = "200";
							$max_height = "200";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
							$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg){
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						// For signature images
						if($signature){
							$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
							$max_width = "140";
							$max_height = "100";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
							$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg){
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}else
								$sign_cnt++;
						}
						// For ID proof images
						if($idproofimg){
							$image = "./uploads/idproof/".$reg_data['idproofphoto'];
							$max_width = "800";
							$max_height = "500";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
							$proof_to_add = $directory."/".$reg_data['idproofphoto'];
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							if(!$idproof_zip_flg){
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
							}else 
								$idproof_cnt++;
						}
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg){
							$success['zip'] = "CSC Member Images Zip Generated Successfully";
						}else{
							$error['zip'] = "Error While Generating CSC Member Images Zip";
						}
					}
					$i++;
					$mem_cnt++;
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg){
						$success['file'] = "CSC Member Details File Generated Successfully. ";
					}
					else{
						$error['file'] = "Error While Generating CSC Member Details File.";
					}
				}
				fwrite($fp1, "\n"."Total CSC Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				//$file_w_flg = fwrite($fp, $data);
				$zip->close();
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" =>$success,"error" =>$error,"Start Time"=>$start_time,"End Time"=>$end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("CSC Member Details Cron End", $desc);
			fwrite($fp1, "\n"."************ CSC Member Details Cron End ".$end_time." *************"."\n");
			fclose($fp1);
		}
	}
	/* Exam Training Application Cron*/
	public function exam_training()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = $parent_dir_flg = $exam_file_flg = 0;
		$success = $error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/";
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Exam Training Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date)){
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "exam_training_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Exam Training Details Cron Start - ".$start_time." *********** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			$yesterday = '2020-10-18';
			$select = 'a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,c.regnumber,DATE_FORMAT(a.created_on,"%Y-%m-%d") created_on,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag';
			$this->db->where_in('a.exam_code', array('191','1910','19100')); 
			$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'isactive'=>'1','isdeleted'=>0,'a.pay_status'=>1),$select);
			if(count($can_exam_data)){
				$i = 1;
				$exam_cnt = 0;
				foreach($can_exam_data as $exam){
					$data = $syllabus_code = $part_no = $trans_date = '';
					$exam_mode = 'O';
					if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
					else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
					else if($exam['exam_mode'] == 'OF'){ $exam_mode = 'F';}
					$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
					if(count($subject_data)>0){
						$syllabus_code = $subject_data[0]['syllabus_code'];
						$part_no = $subject_data[0]['part_no'];
					}
					if($exam['created_on'] != '0000-00-00'){
						$trans_date = date('d-M-Y',strtotime($exam['created_on']));
					}
					$exam_code = '';
					$exam_code = $exam['exam_code'];
					$exam_period = $exam['exam_period'];
					$scribe_flag = $exam['scribe_flag'];
					$transaction_no = '000000000000';
					if($exam_code == 1910 || $exam_code == 19100){
					 	$exam_code = 191;
					}
					else{
						$exam_code = 191;
					}
									//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG
					$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$transaction_no.'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$transaction_no.'|'.$scribe_flag."\n";
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "Exam Training Details File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating Exam Training Details File.";
					$i++;
					$exam_cnt++;
				}
				fwrite($fp1, "\nTotal Exam Training Applications - ".$exam_cnt."\n");
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Exam Training Details Cron End", $desc);
			fwrite($fp1, "\n"."********** Exam Training Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron NSEIT_member
	/* Only for NSEIT Vendor : 29-05-2020 */
	public function NSEIT_member_old()
	{
	    ini_set("memory_limit", "-1");
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
		//$current_date = '20190603';
		$cron_file_dir = "./uploads/rahultest/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Vendor NSEIT Member Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "iibf_NSEIT_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_NSEIT_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n"."************ Vendor NSEIT Member Details Cron End ".$start_time." *************"."\n");
			$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ($current_date) ));
			//$yesterday = '2020-05-29';
			$exam_code_arry = array('1002'); // Anti Money Laundering and Know Your Customer
            $select    = 'c.regnumber,namesub,firstname,middlename,lastname,scannedphoto,scannedsignaturephoto,idproofphoto';
            $this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
            $this->db->where_in('a.exam_code', $exam_code_arry);
            $new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
                'isactive' => '1',
                'isdeleted' => 0,
                'pay_status' => 1,
                'DATE(a.created_on)' => $yesterday
            ), $select);
			if(count($new_mem_reg))
			{
				$dirname = "NSEIT_regd_image_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory)){
					array_map('unlink', glob($directory."/*.*"));
					rmdir($directory);
					$dir_flg = mkdir($directory, 0700);
				}
				else{
					$dir_flg = mkdir($directory, 0700);
				}
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				$i = 1;
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					$photo = '';
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto'])){
						$photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					else{
						fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}
					$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto'])){
						$signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					else{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}
					$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto'])){
						$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					else{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
					// Member Number | Name Prefix | Firstname | Middlename | Lastname
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname']."|\n";
					if($dir_flg)
					{
						// For photo images
						if($photo){
							$image = "./uploads/photograph/".$reg_data['scannedphoto'];
							$max_width = "200";
							$max_height = "200";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
							$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg){
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						// For signature images
						if($signature){
							$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
							$max_width = "140";
							$max_height = "100";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
							$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg){
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}else
								$sign_cnt++;
						}
						// For ID proof images
						if($idproofimg){
							$image = "./uploads/idproof/".$reg_data['idproofphoto'];
							$max_width = "800";
							$max_height = "500";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
							$proof_to_add = $directory."/".$reg_data['idproofphoto'];
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							if(!$idproof_zip_flg){
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
							}else 
								$idproof_cnt++;
						}
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg){
							$success['zip'] = "NSEIT Member Images Zip Generated Successfully";
						}else{
							$error['zip'] = "Error While Generating NSEIT Member Images Zip";
						}
					}
					$i++;
					$mem_cnt++;
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg){
						$success['file'] = "NSEIT Member Details File Generated Successfully. ";
					}
					else{
						$error['file'] = "Error While Generating NSEIT Member Details File.";
					}
				}
				fwrite($fp1, "\n"."Total NSEIT Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				//$file_w_flg = fwrite($fp, $data);
				$zip->close();
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" =>$success,"error" =>$error,"Start Time"=>$start_time,"End Time"=>$end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Vendor NSEIT Member Details Cron End", $desc);
			fwrite($fp1, "\n"."************ Vendor NSEIT Member Details Cron End ".$end_time." *************"."\n");
			fclose($fp1);
		}
	}
	public function NSEIT_member()
	{
	    ini_set("memory_limit", "-1");
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
		$cron_file_dir = "./uploads/rahultest/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Vendor NSEIT Member Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "iibf_NSEIT_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_NSEIT_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n"."************ Vendor NSEIT Member Details Cron End ".$start_time." *************"."\n");
			$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ($current_date) ));
			//$yesterday = '2020-05-31';
            $members = array(500176542,510319027,500049421,500037957,100086278,500193417,500091402,500076253,500176542,500079719,500079023,510319027,100086278,500193417);
			//$this->db->where('DATE(a.created_on) >=', '2020-05-29');
			//$this->db->where('DATE(a.created_on) <=', '2020-06-03');
			//$exam_code_arry = array('1002');
			$select='c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,c.scannedphoto,c.scannedsignaturephoto,c.idproofphoto,c.image_path,c.reg_no';
$this->db->where_in('c.regnumber',$members);
			//$this->db->where_in('a.exam_code',$exam_code_arry);
			$this->db->where_in('c.regnumber',$members);
			$this->db->join('member_registration c', 'a.regnumber = c.regnumber', 'LEFT');
			$this->db->group_by('c.regnumber'); 
            $new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
                'c.isactive' => '1',
                'c.isdeleted' => 0,
                'a.pay_status' => 1,
				/*'DATE(a.created_on)' => $yesterday*/
            ), $select);
			echo $this->db->last_query();
			if(count($new_mem_reg))
			{
				$dirname = "NSEIT_regd_image_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory)){
					array_map('unlink', glob($directory."/*.*"));
					rmdir($directory);
					$dir_flg = mkdir($directory, 0700);
				}
				else{
					$dir_flg = mkdir($directory, 0700);
				}
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				$i = 1;
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					$image_path = $reg_data['image_path'];
					$photo = '';
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
					{
						$photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					elseif($image_path != '')
					{
						$phtofile = "./uploads".$image_path."photo/p_".$reg_data['reg_no'].".jpg";
						$scannedphoto = "p_".$reg_data['regnumber'].".jpg";
						$photo 	= MEM_FILE_PATH.$scannedphoto;
					}
					else
					{
						fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}
					/*$photo = '';
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto'])){
						$photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					else{
						fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}*/
					$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
					{
						$signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					elseif($image_path != '')
					{
						$signaturefile = "./uploads".$image_path."signature/s_".$reg_data['reg_no'].".jpg";
						$scannedsignature = "s_".$reg_data['regnumber'].".jpg";
						$signature 	= MEM_FILE_PATH.$scannedsignature;
					}
					else
					{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}
					/*$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto'])){
						$signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					else{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}*/
					$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
					{
						$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					elseif($image_path != '')
					{	
						$idprooffile = "./uploads".$image_path."idproof/pr_".$reg_data['reg_no'].".jpg";
						$scannedidproofimg = "pr_".$reg_data['regnumber'].".jpg";
						$idproofimg 	= MEM_FILE_PATH.$scannedidproofimg;
					}
					else
					{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
					/*$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto'])){
						$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					else{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}*/
					// Member Number | Name Prefix | Firstname | Middlename | Lastname
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname']."|\n";
					if($dir_flg)
					{
						// For photo images
						if($photo)
						{
							$max_width = "200";
							$max_height = "200";
							if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
							{
								$image = "./uploads/photograph/".$reg_data['scannedphoto'];
								$imgdata = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
								$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							}
							elseif($image_path != '')
							{
								$image = $phtofile;
								$imgdata = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($imgdata, $directory."/".$scannedphoto);
								$photo_to_add = $directory."/".$scannedphoto;
							}
							else
							{
								$image = "./uploads/photograph/".$reg_data['scannedphoto'];
								$imgdata = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
								$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							}
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg){
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						// For signature images
						if($signature){
							$max_width = "140";
							$max_height = "100";
							if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
							{
								$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
								$imgdata = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
								$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							}
							elseif($image_path != '')
							{
								$image = $signaturefile;
								$imgdata = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($imgdata, $directory."/".$scannedsignature);
								$sign_to_add = $directory."/".$scannedsignature;
							}
							else
							{
								$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
								$imgdata = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
								$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							}
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg){
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}else
								$sign_cnt++;
						}
						// For ID proof images
						if($idproofimg){
							$max_width = "800";
							$max_height = "500";
							if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
							{
								$image = "./uploads/idproof/".$reg_data['idproofphoto'];
								$imgdata = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
								$proof_to_add = $directory."/".$reg_data['idproofphoto'];
							}
							elseif($image_path != '')
							{
								$image = $idprooffile;
								$imgdata = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($imgdata, $directory."/".$scannedidproofimg);
								$proof_to_add = $directory."/".$scannedidproofimg;
							}
							else
							{
								$image = "./uploads/idproof/".$reg_data['idproofphoto'];
								$imgdata = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
								$proof_to_add = $directory."/".$reg_data['idproofphoto'];
							}
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							if(!$idproof_zip_flg){
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
							}else 
								$idproof_cnt++;
						}
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg){
							$success['zip'] = "cscvendor Member Images Zip Generated Successfully";
						}else{
							$error['zip'] = "Error While Generating cscvendor Member Images Zip";
						}
					}
					$i++;
					$mem_cnt++;
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg){
						$success['file'] = "NSEIT Member Details File Generated Successfully. ";
					}
					else{
						$error['file'] = "Error While Generating NSEIT Member Details File.";
					}
				}
				fwrite($fp1, "\n"."Total NSEIT Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				//$file_w_flg = fwrite($fp, $data);
				$zip->close();
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" =>$success,"error" =>$error,"Start Time"=>$start_time,"End Time"=>$end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Vendor NSEIT Member Details Cron End", $desc);
			fwrite($fp1, "\n"."************ Vendor NSEIT Member Details Cron End ".$end_time." *************"."\n");
			fclose($fp1);
		}
	}
	// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron cscvendor_member
	/* Only for CSCVendor : 29-05-2019 */
	public function cscvendor_member_old()
	{
	    ini_set("memory_limit", "-1");
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
		//$current_date = '20190603';
		$cron_file_dir = "./uploads/rahultest/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Vendor cscvendor Member Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "iibf_cscvendor_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_cscvendor_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n"."************ Vendor cscvendor Member Details Cron End ".$start_time." *************"."\n");
			$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ($current_date) ));
			//$yesterday = '2020-05-29';
			$exam_code_arry = array('1003','1004'); 
            $select    = 'c.regnumber,namesub,firstname,middlename,lastname,scannedphoto,scannedsignaturephoto,idproofphoto';
            $this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
            $this->db->where_in('a.exam_code', $exam_code_arry);
            $new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
                'isactive' => '1',
                'isdeleted' => 0,
                'pay_status' => 1,
                'DATE(a.created_on)' => $yesterday
            ), $select);
			if(count($new_mem_reg))
			{
				$dirname = "cscvendor_regd_image_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory)){
					array_map('unlink', glob($directory."/*.*"));
					rmdir($directory);
					$dir_flg = mkdir($directory, 0700);
				}
				else{
					$dir_flg = mkdir($directory, 0700);
				}
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				$i = 1;
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					$photo = '';
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto'])){
						$photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					else{
						fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}
					$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto'])){
						$signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					else{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}
					$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto'])){
						$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					else{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
					// Member Number | Name Prefix | Firstname | Middlename | Lastname
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname']."|\n";
					if($dir_flg)
					{
						// For photo images
						if($photo){
							$image = "./uploads/photograph/".$reg_data['scannedphoto'];
							$max_width = "200";
							$max_height = "200";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
							$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg){
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						// For signature images
						if($signature){
							$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
							$max_width = "140";
							$max_height = "100";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
							$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg){
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}else
								$sign_cnt++;
						}
						// For ID proof images
						if($idproofimg){
							$image = "./uploads/idproof/".$reg_data['idproofphoto'];
							$max_width = "800";
							$max_height = "500";
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
							$proof_to_add = $directory."/".$reg_data['idproofphoto'];
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							if(!$idproof_zip_flg){
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
							}else 
								$idproof_cnt++;
						}
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg){
							$success['zip'] = "cscvendor Member Images Zip Generated Successfully";
						}else{
							$error['zip'] = "Error While Generating cscvendor Member Images Zip";
						}
					}
					$i++;
					$mem_cnt++;
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg){
						$success['file'] = "cscvendor Member Details File Generated Successfully. ";
					}
					else{
						$error['file'] = "Error While Generating cscvendor Member Details File.";
					}
				}
				fwrite($fp1, "\n"."Total cscvendor Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				//$file_w_flg = fwrite($fp, $data);
				$zip->close();
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" =>$success,"error" =>$error,"Start Time"=>$start_time,"End Time"=>$end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Vendor cscvendor Member Details Cron End", $desc);
			fwrite($fp1, "\n"."************ Vendor cscvendor Member Details Cron End ".$end_time." *************"."\n");
			fclose($fp1);
		}
	}
	// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron cscvendor_member
	/* Only for CSCVendor : 29-05-2019 */
	public function cscvendor_member()
	{
	    ini_set("memory_limit", "-1");
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
		$cron_file_dir = "./uploads/rahultest/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Vendor cscvendor Member Details Cron Execution Start", $desc);
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "iibf_cscvendor_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_cscvendor_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n"."************ Vendor cscvendor Member Details Cron End ".$start_time." *************"."\n");
			$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ($current_date) ));
			//$yesterday = '2020-05-29';
            //$members = array('510101898','510415515');
			//$this->db->where_in('c.regnumber',$members);
			//$this->db->where('DATE(a.created_on) >=', '2020-05-29');
			//$this->db->where('DATE(a.created_on) <=', '2020-06-03');
			$exam_code_arry = array('1002','1003','1004','1005','1006','1007','1008','1009'); // Send Free and Paid Both Applications 
			$members = array('200088462','500141926','500186734','500204276','510037097','510141921','510204961','510208087','510217835','510218781','510271500','510297198','510337035','510371135','510385073','510385073','510388396','510450116','510472830','801516936','801516962','801517132','801517162','801517261','801517353','801517358'); 
			$select='c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,c.scannedphoto,c.scannedsignaturephoto,c.idproofphoto,c.image_path,c.reg_no';
			$this->db->where_in('a.exam_code',$exam_code_arry);
			$this->db->where_in('c.regnumber',$members);
			$this->db->join('member_registration c', 'a.regnumber = c.regnumber', 'LEFT');
            $new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
                'c.isactive' => '1',
                'c.isdeleted' => 0,
                'a.pay_status' => 1,
				//'DATE(a.created_on)' => $yesterday
            ), $select);
			//echo $this->db->last_query();
			if(count($new_mem_reg))
			{
				$dirname = "cscvendor_regd_image_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory)){
					array_map('unlink', glob($directory."/*.*"));
					rmdir($directory);
					$dir_flg = mkdir($directory, 0700);
				}
				else{
					$dir_flg = mkdir($directory, 0700);
				}
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				$i = 1;
				$mem_cnt = 0;
				$photo_cnt = 0;
				$sign_cnt = 0;
				$idproof_cnt = 0;
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					$image_path = $reg_data['image_path'];
					$photo = '';
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
					{
						$photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					elseif($image_path != '')
					{
						$phtofile = "./uploads".$image_path."photo/p_".$reg_data['reg_no'].".jpg";
						$scannedphoto = "p_".$reg_data['regnumber'].".jpg";
						$photo 	= MEM_FILE_PATH.$scannedphoto;
					}
					else
					{
						fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}
					/*$photo = '';
					if(is_file("./uploads/photograph/".$reg_data['scannedphoto'])){
						$photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];
					}
					else{
						fwrite($fp1, "Error - Photograph does not exist  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
					}*/
					$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
					{
						$signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					elseif($image_path != '')
					{
						$signaturefile = "./uploads".$image_path."signature/s_".$reg_data['reg_no'].".jpg";
						$scannedsignature = "s_".$reg_data['regnumber'].".jpg";
						$signature 	= MEM_FILE_PATH.$scannedsignature;
					}
					else
					{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}
					/*$signature = '';
					if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto'])){
						$signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
					}
					else{
						fwrite($fp1, "**ERROR** - Signature does not exist  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
					}*/
					$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
					{
						$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					elseif($image_path != '')
					{	
						$idprooffile = "./uploads".$image_path."idproof/pr_".$reg_data['reg_no'].".jpg";
						$scannedidproofimg = "pr_".$reg_data['regnumber'].".jpg";
						$idproofimg 	= MEM_FILE_PATH.$scannedidproofimg;
					}
					else
					{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
					/*$idproofimg = '';
					if(is_file("./uploads/idproof/".$reg_data['idproofphoto'])){
						$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					else{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}*/
					// Member Number | Name Prefix | Firstname | Middlename | Lastname
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname']."|\n";
					if($dir_flg)
					{
						// For photo images
						if($photo)
						{
							$max_width = "200";
							$max_height = "200";
							if(is_file("./uploads/photograph/".$reg_data['scannedphoto']))
							{
								$image = "./uploads/photograph/".$reg_data['scannedphoto'];
								$imgdata = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
								$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							}
							elseif($image_path != '')
							{
								$image = $phtofile;
								$imgdata = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($imgdata, $directory."/".$scannedphoto);
								$photo_to_add = $directory."/".$scannedphoto;
							}
							else
							{
								$image = "./uploads/photograph/".$reg_data['scannedphoto'];
								$imgdata = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
								$photo_to_add = $directory."/".$reg_data['scannedphoto'];
							}
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							if(!$photo_zip_flg){
								fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
							}
							else
								$photo_cnt++;
						}
						// For signature images
						if($signature){
							$max_width = "140";
							$max_height = "100";
							if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto']))
							{
								$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
								$imgdata = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
								$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							}
							elseif($image_path != '')
							{
								$image = $signaturefile;
								$imgdata = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($imgdata, $directory."/".$scannedsignature);
								$sign_to_add = $directory."/".$scannedsignature;
							}
							else
							{
								$image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
								$imgdata = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
								$sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
							}
							$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
							$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
							if(!$sign_zip_flg){
								fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
							}else
								$sign_cnt++;
						}
						// For ID proof images
						if($idproofimg){
							$max_width = "800";
							$max_height = "500";
							if(is_file("./uploads/idproof/".$reg_data['idproofphoto']))
							{
								$image = "./uploads/idproof/".$reg_data['idproofphoto'];
								$imgdata = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
								$proof_to_add = $directory."/".$reg_data['idproofphoto'];
							}
							elseif($image_path != '')
							{
								$image = $idprooffile;
								$imgdata = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($imgdata, $directory."/".$scannedidproofimg);
								$proof_to_add = $directory."/".$scannedidproofimg;
							}
							else
							{
								$image = "./uploads/idproof/".$reg_data['idproofphoto'];
								$imgdata = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
								$proof_to_add = $directory."/".$reg_data['idproofphoto'];
							}
							$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
							$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
							if(!$idproof_zip_flg){
								fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
							}else 
								$idproof_cnt++;
						}
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg){
							$success['zip'] = "cscvendor Member Images Zip Generated Successfully";
						}else{
							$error['zip'] = "Error While Generating cscvendor Member Images Zip";
						}
					}
					$i++;
					$mem_cnt++;
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg){
						$success['file'] = "cscvendor Member Details File Generated Successfully. ";
					}
					else{
						$error['file'] = "Error While Generating cscvendor Member Details File.";
					}
				}
				fwrite($fp1, "\n"."Total cscvendor Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				//$file_w_flg = fwrite($fp, $data);
				$zip->close();
			}
			else{
				$success[] = "No data found for the date";
			}
			fclose($fp);
			// Cron End Logs
			$end_time = date("Y-m-d H:i:s");
			$result = array("success" =>$success,"error" =>$error,"Start Time"=>$start_time,"End Time"=>$end_time);
			$desc = json_encode($result);
			$this->log_model->cronlog("Vendor cscvendor Member Details Cron End", $desc);
			fwrite($fp1, "\n"."************ Vendor cscvendor Member Details Cron End ".$end_time." *************"."\n");
			fclose($fp1);
		}
	}
}