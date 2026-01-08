<?php
/*
 	* Controller Name	:	Cron_data_gen File Generation
 	* Created By		:	Bhushan
 	* Created Date		:	15-06-2020
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Cron_data_gen extends CI_Controller {
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
	public function edit_data_cj()
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
			$today = date('Y-m-d');
			//$this->db->where_in('DATE(benchmark_edit_date)',$yesterday);
			$member = array(300036905,510193012); 
			//$this->db->limit(1);
			$this->db->where_in('regnumber', $member);
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
				$declaration_cnt = 0;
				$photo_flg_cnt = 0;
				$sign_flg_cnt = 0;
				$idproof_flg_cnt = 0;
				$declaration_flg_cnt = 0;
				/* $vis_imp_cert_img_cnt = 0;
				$orth_han_cert_img_cnt = 0;
				$cer_palsy_cert_img_cnt = 0;
				$vis_imp_cert_img_flg_cnt = 0;
				$orth_han_cert_img_flg_cnt = 0;
				$cer_palsy_cert_img_flg_cnt = 0; */
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
					
					$photo = $signature = $idproofimg = $declaration = '';
					$photo_send = $signature_send = $idproofimg_send = $declaration_send = '';
					$photoflag = $imgdata['photo_flg'];
					$signflag = $imgdata['signature_flg'];
					$idprofflag = $imgdata['id_flg'];
					$declarationflag = $imgdata['declaration_flg'];
					$member_img_response = $this->Image_search_model->get_member_data($imgdata['regnumber']);

				    $photo = $member_img_response['scannedphoto'];
				    $idproofimg = $member_img_response['idproofphoto'];
					$declaration = $member_img_response['declarationphoto'];
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
					if($imgdata['declaration'] != "" && is_file("./uploads/declaration/".$imgdata['declaration']))
					{
						$declaration = MEM_FILE_EDIT_PATH.$imgdata['declaration'];
					}
					
					/* Benchmark Code Start */
					/* $benchmark_disability = $imgdata['benchmark_disability'];
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
					} */
					/* Benchmark Code End */
					
					
					$benchmark_data = $data.$img_edited_by.'|'.$optnletter.'|'.$photoflag.'|'.$signflag.'|'.$idprofflag.'|'.$declarationflag.'|'.date('d-M-y H:i:s',strtotime($imgdata['benchmark_edit_date'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id'].'|'.$photo.'|'.$signature.'|'.$idproofimg.'|'.$declaration."\n";
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
						$declaration_zip_flg = 0;
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

						if($imgdata['id_flg'] == 'Y' && $declaration != '')
						{
							$declaration_flg_cnt++;
							// For declaration images
							if(is_file("./uploads/declaration/".$imgdata['declaration']))
							{
								$image = "./uploads/declaration/".$imgdata['declaration'];
								$max_width = "800";
								$max_height = "500";
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$imgdata['declaration']);
								$declaration_to_add = $directory."/".$imgdata['declaration'];
								$new_declaration = substr($declaration_to_add,strrpos($declaration_to_add,'/') + 1);
								$declaration_zip_flg = $zip->addFile($declaration_to_add,$new_declaration);
								if(!$declaration_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Declaration not added to zip  - ".$imgdata['declaration']." (".$imgdata['regnumber'].")\n");	
								}
								else 
									$declaration_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Declaration does not exist  - ".$imgdata['declaration']." (".$imgdata['regnumber'].")\n");	
							}
						}
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg || $declaration_zip_flg)
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
						/* if($vis_imp_cert_img)
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
						} */
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
				fwrite($fp1, "\n"."Total Declaration Added = ".$declaration_cnt."/".$declaration_flg_cnt."\n");
				/* fwrite($fp1, "\n"."Total Visually impaired cert Added = ".$vis_imp_cert_img_cnt."/".$vis_imp_cert_img_flg_cnt." \n");
				fwrite($fp1, "\n"."Total Orthopedically handicapped cert Added = ".$orth_han_cert_img_cnt."/".$orth_han_cert_img_flg_cnt." \n");
				fwrite($fp1, "\n"."Total Cerebral palsy cert Added = ".$cer_palsy_cert_img_cnt."/".$cer_palsy_cert_img_flg_cnt." \n"); */
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
			$cron_file_path = "./uploads/cronFilesCustom/";	// Path with CURRENT DATE DIRECTORY
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
			//echo $this->db->last_query(); die;
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
					$data = ''.$imgdata['regnumber'].'|'.$imgdata['registrationtype'];
					/* mem_mem_no
					mem_mem_typ
					benchmark_upd_dt
					is_benchmark_disability
					is_visually_impaired
					is_orthopedically_handicapped
					is_cerebral_palsy
					vis_imp_cert_img_path
					orth_han_cert_img_path
					cer_palsy_cert_img_path
					visually_impaired_cert
					orthopedically_handicapped_cert
					cerebral_palsy_cert
					usr_id
 */
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
					/* $benchmark_data = $data.'|'.date('d-M-y H:i:s',strtotime($imgdata['benchmark_edit_date'])).'|'.$benchmark_disability.'|'.$visually_impaired.'|'.$vis_imp_cert_img.'|'.$orthopedically_handicapped.'|'.$orth_han_cert_img.'|'.$cerebral_palsy.'|'.$cer_palsy_cert_img.'|'.$img_edited_by."\n";
 */

					$benchmark_data = $data.'|'.date('d-M-y H:i:s',strtotime($imgdata['benchmark_edit_date'])).'|'.$benchmark_disability.'|'.$visually_impaired.'|'.$orthopedically_handicapped.'|'.$cerebral_palsy.'|'.$vis_imp_cert_img.'|'.$orth_han_cert_img.'|'.$cer_palsy_cert_img.'|'.$img_edited_by."\n";
					
					
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
			//$yesterday = '2022-04-27';
			$today = date('Y-m-d');
			$member = array(500213774);
			
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
	
	public function member_live_decl()
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
			
			//$yesterday = '2022-04-01';
			//$this->db->where('DATE(a.createdon) >=', '2019-11-27');
			//$this->db->where('DATE(a.createdon) <=', '2022-04-21'); 
			 $mem = array(510551360,510551364,510551373,510551374,510551378,510551385,510551391,510551396);
			// $excode = array('991','526','527');
			// $this->db->where_not_in('a.excode', $excode);
			$this->db->where_in('a.regnumber', $mem);
			//$this->db->where('a.declaration !=', ''); 
			//$this->db->where('a.declaration !=', '1'); 
			//$this->db->where('a.registrationtype =', 'O');  
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0,'is_renewal' => 0));
			//echo $this->db->last_query();  
			//die;
			// WHERE `registrationtype` LIKE 'O' AND `declaration` != '' AND `declaration` != '1' AND `isactive` = '1' AND `isdeleted` = 0
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
			//$exam_codes_arr = array('1003');
			$ref_id = array(6717624,6717561,6719694,6719829,6707757,6718863);
			$select = 'a.id as mem_exam_id, a.examination_date';
			$this->db->where_in('a.id' , $ref_id);
			//$this->db->where_in('a.exam_code',$exam_codes_arr);
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
	
	public function member_live_new()
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
			
			//$yesterday = '2021-10-11';
			//$this->db->where('DATE(a.createdon) >=', '2019-11-27');
			//$this->db->where('DATE(a.createdon) <=', '2019-11-27');
			//$mem = array(510536282,510536283,510536284,510536285,510536286,510536287,510536288,510536289,510536290,510536291,510536292,510536293,510536294,510536295,510536296,510536297,510536298,510536299,510536300,510536301,510536302,510536303,510536304,510536305,510536306,510536307,510536308,510536309,510536310,510536311,510536312,510536313,510536314,510536315,510536316,510536317,510536318,510536319,510536320,510536321,510536322,510536323,510536324,510536325,510536326,510536327,510536328,510536329,510536330,510536331,510536332,510536333,510536334,510536335,510536336,510536337,510536338,510536339,510536340,510536341,510536342,510536343,510536344,510536345,510536346,510536347,510536348,510536349,510536350,510536351,510536352,510536353,510536354,510536355,510536356,510536357,510536358,510536359,510536360,510536361,510536362,510536363,510536364,510536365,510536366,510536367,510536368,510536369,510536370,510536371,510536372,510536373,510536374,510536375,510536376,510536377,510536378,510536379,510536380,510536381,510536382,510536383,510536384,510536385,510536386,510536387,510536388,510536389,510536390,510536391,510536392,510536393,510536394,510536395,510536396,510536397,510536398,510536399,510536400,510536401,510536402,510536403,510536404,510536405,510536406,510536407,510536408,510536409,510536410,510536411,510536412,510536413,510536414,510536415,510536416,510536417,510536418,510536419,510536420,510536421,510536422,510536423,510536424,510536425,510536426,510536427,510536428,510536429,510536430,510536431,510536432,510536433,510536434,510536435,510536436,510536437,510536438,510536439,510536440,510536441,510536442,510536443,510536444,510536445,510536446,510536447,510536448,510536449,510536450,510536451,510536452,510536453,510536454,510536455,510536456,510536457,510536458,510536459,510536460,510536461,510536462,510536463,510536464,510536465,510536466,510536468,510536469,510536470,510536471,510536472,510536473,510536474,510536475,510536476,510536477,510536478,510536479,510536480,510536481,510536482,510536484,510536485,510536486,510536487,510536488,510536489,510536490,510536491,510536492,510536493,510536494,510536495,510536496,510536497,510536498,510536499,510536500,510536501,510536504,510536505,510536506,510536507,510536508,510536509,510536510,510536511,510536512,510536513,510536514,510536515,510536517,510536518,510536519,510536520,510536521,510536522,510536523,510536524,510536525,510536526,510536527,510536528,510536529,510536530,510536531,510536532,510536533,510536534,510536535,510536536,510536537,510536538,510536539,510536540,510536541,510536542,510536543,510536544,510536546,510536547,510536548,510536549,510536550,510536551,510536552,510536553,510536554,510536555,510536556,510536557,510536558,510536559,510536560,510536561,510536562,510536563);     
			$excode = array('991','526','527');     
			$this->db->where_not_in('a.excode', $excode);
			//$this->db->where_in('a.regnumber', $mem); 
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0));
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


public function member_live_decl_old()
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
			
			$file = "iibf_new_mem_details_decl_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n************************* IIBF New Member Details Cron Execution Started - ".$start_time." ********************* \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			
			//$yesterday = '2021-10-11';
			//$this->db->where('DATE(a.createdon) >=', '2019-11-27');
			//$this->db->where('DATE(a.createdon) <=', '2019-11-27');
			$mem = array(510535459,510536196,510536419,510536430,510536431,510536442,510536478,510536482,510536488,510536493,510536499,510536506,510536510,510536527,510536539,510536557,510536559,510536566,510536592,510536595,510536606,510536634,510536664,510536691,510536704,510536763,510536780,510536788,510536791,510536793,510536796,510536804,510536814,510536906,510536919,510536922,510536931,510536936,510536947,510536957,510536959,510536983,510537013,510537042,510537047,510537059);     
			$excode = array('991','526','527');     
			$this->db->where_not_in('a.excode', $excode);
			//$this->db->where_in('a.regnumber', $mem); 
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0));
			echo $this->db->last_query(); 
			//' DATE(createdon)'=>$yesterday,
			if(count($new_mem_reg))
			{
				$dirname = "regd_image_decl_".$current_date;
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


public function member_live()
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



			$mem = array(801759835);

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
      $members = array(510381293,510478885,510452090,510483403,801802615,510303700,510166759,510390842,510385941,500061412,510176669,510217130,510529708,500177825,510292997,510460673,510150620,510422849,510352940,510332736,510536130,510479729,510344750,510060058,510373758,510370812,510219658,510403940,801409279,500068431,510472297,510296855,510482243,510302460,510380026,510145292,510186738,510432431,510095032,510424684,510382538,500095592,510424713,510512666,510377331,510412660,500124115,510457575,500195063,510053454,510392819,510444243,510323440,510430404,510375436,510522204,510335565,500085469,510177608,510387336,510081294,510317144,510378392,510022000,510083790,510299539,510236088,510109165,6434821,510234589,510252892,510105306,510301530,500167169,510191550,510302291,510024618,510060561,500115044,500093153,510409540,500118482,500160397,510348159,500114812,510292014,510446727,510422059,510063713,6431893,510169570,510157245,7591748,500058393,500138812,510415861,400080568,510254070,510027963,510264413,510276273,510444375,510492871,500192018,510001233,510405883,510319027,510446140,510449663,510370673,500049190,100041089,500132638,7077545,801944712,510241997,510447788,510311099,500116425,510327399,500081593,500043293,500139963,500006768,510322343,500097479,510365139,510400980,510383362);
      //$this->db->where_in('c.regnumber',$members);
      //$this->db->where('DATE(a.created_on) >=', '2020-05-29');
      //$this->db->where('DATE(a.created_on) <=', '2020-06-03');
      
     // $exam_code_arry = array('1002','1010','1011','1012','1013','1014','1019','1020','2027');
      $select='c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,c.scannedphoto,c.scannedsignaturephoto,c.idproofphoto,c.image_path,c.reg_no, a.exam_code, ac.exam_date'; 
      //$this->db->where_in('a.exam_code',$exam_code_arry);
	  $this->db->where('DATE(ac.exam_date) =', '2022-04-23');
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
	public function member_live_chaitali_new()
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
			$mem = array(510170429,802182392,802182394,802182403,510052574,510296473,510375039,510163484,510220322,500212930,510278089,510172806,510306478,802140203,500048907,510569149,510287609,510554411,510542373,510550605,510467568,510089875,500150845,500126010,510557140,510559904,400006954,510217970,510165091,510575235,510107158,510476335,510575305,510098330,801982664,801813479,510438315,500132548,510167810,510441921,510045362,500143476,510575428,510575584,500175787,500181559,510456546,510458420,510038191,510576630,510301748,7436188,510468397,510424219,510021377,510009135,510121765,510082400,802068870,500170963,510051922,510450219,510285230,510543196,510171763,500110862,510318859,510409753,510225212,500089000,510460759,500017587); 
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
			$yesterday = '2022-04-19'; 
			       // $regid=array(801475079);
                    $this->db->select("dra_payment_transaction.*");
                    $this->db->where("dra_payment_transaction.status = 1");
                    $this->db->where("dra_payment_transaction.exam_period = 717");
                    $this->db->where("dra_payment_transaction.exam_code = 57");
                    $this->db->where("dra_payment_transaction.UTR_no = N10221915127254");
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
			$yesterday = '2022-08-03';
			//$member = array(802036458);
			$excode = array('991','1015','101');
			$this->db->where_in('a.excode', $excode);
			$this->db->join('payment_transaction b','b.member_regnumber = a.regnumber','LEFT');
			//$this->db->where_in('a.regnumber', $member);
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'bankcode' => 'csc','isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0));
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
			$mem = array(801960538,801962083);
			$excode = array('528','529','530','531','534');
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
		//$current_date = '20200902';
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
			$mem = array(802009362,802009540,802009661,802009807,802009914,802010012,802010017,802010164,802011453,802011525,802011625,802011656,802011675,802011798,802011848,802012132,802012167,802012226,802012318,802012335,802012822,802012922,802014006,802014040,802014086,802014493,802014689,802014720,802014736,802014889,802014907,802014909,802014917,802014988,802015018,802015120,802015297,802015468,802015673,802015715,802015748,802016069,802016149,802016178,802016278,802016335,802016363,802016392,802016513,802016515,802016529,802016569,802016585,802016643,802016820,802016858,802016917,802016930,802016978,802017025,802017042,802017126,802017132,802017346,802017451,802017539,802017642,802017702,802017727,802017750,802017754,802017832,802017924,802018526,802018541,802018561,802018631,802018637,802018640,802018642,802018686,802018704,802018714,802018758,802018802,802018890,802019001,801751765,802019030,802019098,802019224,802019290,802019328,802019404,802019415,802019424,802019520,802019524,802019557,801759548,802019583,802019585,802019682,802019715,802019804,802019913,802019915,802019938,802019943,802019961,802019964,802019972,802019982,801982819,802020061,802020088,802020090,802020113,802020118,802020123,802020124,802020133,802020172,802020188,802020211,802020229,802020309,802020332,802020358,802020393,802020396,802020412,802020442,802020490,802020519,802020524,802020556,802020602,802020611,802020614,802020618,802020623,802020625,802020631,802020645,802020669,802020677,802020679,802020680,802020686,802020695,802020713,802020718,802020823,802020849,802020872,802021013,802021050,802021081,802021098,802021297,802021322,802021407,802021452,802021466,802021478,802021548,802021557,802021564,802021568,802021599,802021620,802021691,802021704,802021707,802021721,802021845,802021998,802022002,802022012,802022015,802022036,802022056,802022079,802022146,802022155,802022179,802022226,802022239,802022263,802022267,802022268,802022293,802022309,802022319,802022338,802022353,802022363,802022388,802022412,802022425,802022507,802022543,802022544,802022548,802022549,802022556,802022564,802022596,801936070,802022707,801861964,802022754,802022794,802022823,802022858,802022886,802022903,802022944,801885065,802022993,802022994,802023019,802023031,802023037,802023082,802023109,802023118,802023123,802023153,802023189,802023254,802023265,802023271,801837564,802023310,802023334,802023355,802023361,802023406,802023410,802023491,802023499,802023502,802023508,802023510,802023527,802023539,802023573,802023581,802023584,802023587,802023588,802023595,802023619,802023628,802023654,802023667,802023672,802023677,802023893,802023953,801894632,802023978,802023989,802023990,802023992,802023997,802024002,802024003,802024009,802024040,802024057,802024060,802024064,802024070,802024076,802024097,802024102,802024126,802024133,802024149,802024155,802024152,802024173,802024193,802024199,801837063,802024226,802024229,802024240,802024265,802024266,802024271,802024273,802024290,802024293,802024299,802024302,802024304,802024329,802024346,802024347,802024365,801639159,802024391,802024390,802024394,802024400,802024418,802024445,802024454,802024453,802024457,802024461,802024466,802024473,802024474,802024480,802024485,802024495,802024500,802024504,802024516,802024523,802024529,802024532,802024534,802024537,802024540,802024558,802024564,802024571,802024572,802024573,802024581,802024583,802024588,802024601,802024609,801990574,802024640,802024824,802024834,802024837,802024846,802024847,802024897,802024917,802025238,802025481,802025551,802026116,802026081,802026143,802026563,802029640,802029932,802030173,802030183,802031769,802031813,802031819,802031822,802031824,801946955,802031841,802031847,802031851,802031856,802031863,802031869,802031871,802031875,802031882,802031890,802031892,802031893,802031896,802031903,802031905,802031907,802031913,802031918,802031920,802031921,802031929,802031930,802031933,802031942,802031946,802031968,802031971,802031973,802031975,802031978,802031982,802031983,802031986,802031989,802031999,802032008,802032009,802032020,802032022,802032032,802032062,802032066,802032068,802032075,802032096,802032097,802032104,802032111,802032123,802032126,802032132,802032141,802032142,802032146,802032147,802032148,802032152,802032155,802032161,802032163,802032169,802032173,802032178,802032205,802032221,802032224,802032225,802032226,802032227,802032228,802032229,802032231,802032233,802032234,802032235,802032237,802032239,802032240,802032241,802032242,802032243,802032244,802032245,802032248,802032251,802032253,802032255,802032259,802032258,802032261,802032264,802032265,801996033,802032266,802032268,802032271,802032273,802032272,802032275,802032279,802032281,802032282,802032285,802032288,802032290,802032291,802032292,802032293,802032294,802032296,802032299,802032300,802032301,802032303,802032304,802032305,802032306,802032308,802032311,802032312,802032313,802032314,802032315,802032317,802032318,802032319,802032321,802032320,802032322,802032324,802032328,802032326,801778916,802032329,802032333,802032335,802032336,802032340,802032342,802032344,802032346,802032350,802032352,802032359,802032360,802032363,802032364,802032367,802032368,802032369,802032373,802032370,802032371,802032375,802032380,802032382,802032383,802032386,802032388,802032389,802032390,802032391,802032393,802032395,802032399,802032400,802032401,802032406,802032407,802032409,802032413,802032412,802032415,802032418,802032424,802032425,802032426,802032427,802032429,802032430,802032433,802032434,802032436,802032439,802032440,802032443,802032442,802032446,802032447,802032448,802032451,802032452,802032455,802032461,802032466,802032469,802032473,802032474,802032475,801920346,802032476,802032477,802032478,801920332,802032480,802032481,802032482,802032485,802032483,802032484,802032486,802032488,802032491,802032492,802032493,802032494,802032498,802032499,802032502,802032504,802032509,802032510,802032511,802032513,802032517,802032518,801964749,802032521,802032520,802032522,802032526,802032530,802032533,802032534,802032536,802032538,802032541,802032544,802032545,802032550,802032552,802032549,802032551,802032554,802032557,802032562,802032558,802032559,802032566,802032570,802032571,801979398,802032572,802032577,802032573,802032575,802032579,802032581,802032582,802032584,802032585,802032586,802032588,802032591,802032594,802032596,802032597,802032598,802032600,802032599,802032601,802032602,802032604,802032606,802032607,802032608,802032609,802032611,802032612,802032614,802032616,802032617,802032618,802032621,802032620,802032623,802032626,802032627,802032628,802032629,802032630,802032631,802032632,802032638,802032642,802032641,802032643,802032645,802032646,802032647,802032649,802032656,802032654,802032655,802032660,802032661,802032664,802032665,802032667,802032671,802032674,802032676,802032675,802032677,802032678,802032679,802032681,802032680,802032686,802032682,802032687,802032692,802032688,802032693,802032700,802032698,802032697,802032701,802032703,802032710,802032711,802032712,802032717,802032719,802032721,802032722,802032723,802032724,802032728,802032726,802032729,802032730,802032731,802032732,802032733,802032735,802032736,802032740,802032741,802032746,802032745,802032748,802032751,801914241,802032754,802032755,802032759,802032760,802032762,802032764,802032769,802032770,802032772,802032773,802032774,802032775,802032777,802032780,801900531,802032785,802032787,802032788,802032793,802032790,802032792,802032791,802032794,802032795,802032798,802032799,802032802,802032803,802032804,802032806,802032805,802032809,802032811,802032812,802032813,802032814,802032816,802032817,802032818,802032820,802032819,802032823,802032825,802032826,802032829,802032830,802032835,802032836,802032837,802032841,802032843,802032844,801747128,802032845,802032847,802032848,802032853,802032854,802032855,802032857,802032858,802032859,802032860,802032862,802032870,802032872,802032873,802032874,802032875,802032876,802032877,802032878,802032880,802032881,802032882,802032883,802032885,802032884,802032887,802032888,802032900,802032890,802032891,802032897,802032893,802032895,802032896,802032898,802032899,802032901,802032903,801870605,802032904,802032906,802032908,802032909,802032911,802032913,802032915,802032923,802032924,802032925,802032927,802032928,802032930,802032933,802032937,802032939,802032940,802032943,802032944,802032946,802032949,802032952,802032953,802032954,802032955,802032960,802032962,802032965,802032966,802032968,802032969,802032971,802032972,802032974,802032983,802032976,802032975,802032978,802032980,802032981,802032986,802032987,802032988,801964383,802032989,802032991,802032992,802032993,802032997,802032998,802032999,801757013,802033000,802033001,802033003,802033011,802033012,802033016,802033020,802033022,802033023,802033024,802033027,802033026,802033030,802033033,802033034,802033037,802033044,802033045,801938084,802033051,802033052,802033054,802033055,801930642,802033057,802033058,802033059,802033062,802033065,802033068,802033074,802033076,802033078,802033080,802033083,802033092,802033097,802033098,802033099,802033104,802033107,802033105,802033111,802033113,802033114,801744199,802033116,802033117,802033123,802033124,802033127,802033128,802033132,802033135,802033136,802033137,802033138,802033139,802033143,802033145,802033158,801930612,802033162,802033163,802033166,802033168,801891871,802033173,802033174,802033178,802033180,802033185,802033188,802033190,802033192,802033191,802033195,802033196,802033198,802033199,802033205,802033208,802033207,802033209,802033211,802033265,802033444,802033445,802033448,802033616,802033714,802034067,802034070,802034071,802034074,802034076,802034079,802034080,802034083,802034084,802034085,802034089,802034090,802034092,802034095,802034102,802034106,802034105,802034107,802034108,802034109,802034110,802034111,802034112,802034115,802034122,802034121,801760043,802034123,802034128,802034133,802034134,802034135,802034136,802034137,802034140,802034141,802034143,802034144,802034146,802034148,802034150,802034151,802034155,802034157,802034158,802034160,802034164,802034163,802034165,802034166,802034169,802034171,802034173,802034174,802034178,802034179,802034181,802034183,802034185,802034189,802034190,802034192,802034193,802034194,802034196,802034198,802034200,802034202,802034201,802034205,802034206,802034213,802034214,802034216,802034215,802034219,802034220,802034223,802034225,802034228,802034229,802034231,802034230,802034232,802034234,802034236,802034239,802034240,802034241,802034242,802034249,802034250,802034251,802034252,802034253,802034255,802034258,802034259,802034261,802034263,802034264,802034265,802034268,802034270,802034271,802034276,802034279,802034280,802034281,802034282,802034283,802034284,802034286,802034287,801778011,802034289,802034290,802034293,802034297,802034301,802034303,802034305,802034308,802034309,802034311,802034312,802034314,802034317,802034320,802034321,802034322,802034323,802034326,802034328,802034329,802034332,802034336,802034337,802034338,802034339,802034340,802034341,802034346,802034349,802034351,802034355,802034359,802034357,802034360,802034361,802034363,802034366,802034367,802034369,802034371,802034374,802034375,802034378,802034379,802034381,801874568,802034383,802034386,802034387,802034388,802034389,802034391,802034395,802034397,802034398,802034403,802034404,802034407,802034409,802034410,802034411,802034415,802034416,802034418,802034419,802034420,802034424,802034426,802034428,802034429,802034430,802034434,802034435,802034437,802034439,802034440,802034441,802034445,802034446,802034447,802034449,802034450,801244331,802034452,802034454,802034455,802034459,802034461,801213134,802034464,802034470,802034472,802034474,802034477,802034483,802034484,802034485,802034488,802034490,802034491,802034494,802034495,802034499,802034506,802034508,802034507,802034510,801997380,802034511,802034512,801997381,802034514,802034516,802034519,802034521,802034523,802034524,802034525,802034528,802034529,802034530,802034531,802034534,802034537,802034538,802034542,801803768,801939667,802034546,802034549,802034550,802034551,802034554,802034555,801248038,802034556,802034558,802034560,802034563,802034564,802034566,801551335);
			$select = 'regnumber,namesub,firstname,middlename,lastname,scannedphoto,scannedsignaturephoto,idproofphoto';
			$thsi->db->where_in('regnumber', $mem);
			$this->db->join('payment_transaction','payment_transaction.member_regnumber = member_registration.regnumber','LEFT');
			$new_mem_reg = $this->Master_model->getRecords('member_registration ',array('isactive'=>'1','isdeleted'=>0,'bankcode' => 'csc', 'status'=>'1',$select);
			//' DATE(date)'=>$yesterday,'pay_type'=>2,
			echo $this->db->last_query(); die; 
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
	
	/* Only for IPPB Registration : 31-05-2022 */
	public function ippb_member()
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
		
		$cron_file_dir = "./uploads/cronFilesCustom/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		
		$this->log_model->cronlog("Vendor IPPB Member Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "iibf_ippb_mem_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_IPPB_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			
			fwrite($fp1, "\n"."************ Vendor IPPB Member Details Cron End ".$start_time." *************"."\n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ($current_date) ));
			
			$select = 'regnumber,namesub,firstname,middlename,lastname,scannedphoto,scannedsignaturephoto,idproofphoto,(select emp_id from member_registration_ippb where email = member_registration.email AND mobile = member_registration.mobile limit 1) as EMP_ID';
			$this->db->join('payment_transaction','payment_transaction.member_regnumber = member_registration.regnumber','LEFT');
			$new_mem_reg = $this->Master_model->getRecords('member_registration',array('pay_type'=>2,'isactive'=>'1','isdeleted'=>0,'excode' => '997', 'status'=>'1'),$select);
			//' DATE(date)'=>$yesterday,
			//echo $this->db->last_query(); die;
			if(count($new_mem_reg))
			{
				$dirname = "iipb_regd_image_".$current_date;
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
							$success['zip'] = "IPPB Member Images Zip Generated Successfully";
						}else{
							$error['zip'] = "Error While Generating IPPB Member Images Zip";
						}
					}
					$i++;
					$mem_cnt++;
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg){
						$success['file'] = "IPPB Member Details File Generated Successfully. ";
					}
					else{
						$error['file'] = "Error While Generating IPPB Member Details File.";
					}
				}
				fwrite($fp1, "\n"."Total IPPB Members Added = ".$mem_cnt."\n");
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
			$this->log_model->cronlog("Vendor IPPB Member Details Cron End", $desc);
			
			fwrite($fp1, "\n"."************ Vendor IPPB Member Details Cron End ".$end_time." *************"."\n");
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
			$ref_id = array(28880,28879,28862);
				
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
			$ref_id = array(6920342,6925992,6926035,6926237,6926295,6926290,6926321,6926349,6926355,6926384,6926419,6926422,6926452,6926456,6926500,6926507,6926511,6926514,6926569,6926570,6926603,6926616,6926620,6926688,6926691,6926716,6926717,6926753,6926768,6926801,6926803,6926827,6926834,6926889,6926909,6926941,6926944,6926960,6926983,6926987,6927044,6927085,6927082,6927097,6927098,6927109,6927121,6927191,6927212,6927243,6927248,6927261,6927274,6927304,6927356,6927363,6927374,6927392,6927396,6927398,6927405,6927244,6925975,6926814,6926444,6926555,6926959,6926009,6926395,6926812,6926904,6927052,6927349,6926173,6926207,6926302,6926357,6926433,6926554,6926725,6927004,6927091,6926756,6926906,6926646);
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
	public function exam_chaitali() 
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
			
			$not_exam_codes = array('991','1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014','2027','1019','1020'); // Not becoz this exam code we send exam date from other crons CSC and remote
			$mem = array(6558718,6565331,6550669,6565652,6558545,6552318,6548641,6558744);
			
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,a.elearning_flag,a.sub_el_count';
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
			$this->db->where_in('a.id' , $mem);
			$this->db->where_not_in('a.exam_code',$not_exam_codes);
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
					
					if($exam['exam_code'] == 1021 || $exam['exam_code'] == 1022 || $exam['exam_code'] == 1023 || $exam['exam_code'] == 1024 || $exam['exam_code'] == 1025)
					{
						$syllabus_code = 'R';
						$part_no = '1';
					}
					else
					{
						$subject_data = $this->Master_model->getRecords('subject_master',array('exam_code'=>$exam['exam_code'],'exam_period'=>$exam['exam_period']),'',array('id'=>'DESC'),0,1);
						if(count($subject_data)>0)
						{
							$syllabus_code = $subject_data[0]['syllabus_code'];
							$part_no = $subject_data[0]['part_no'];
						}
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
					else if($exam_code == '1016' || $exam_code == '1018')
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
			$mem = array(6558718,6565331,6550669,6565652,6558545,6552318,6548641,6558744);
			
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,a.elearning_flag';
			$this->db->where_in('a.id' , $mem);
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
			//$yesterday = '2021-03-20';
			$not_exam_codes = array('991','1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014','2027'); // Not becoz this exam code we send exam date from other crons CSC and remote
			$mem = array(6944754);  
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
			echo $this->db->last_query(); //die;
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
						
					
						
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$scribe_flag.'|'.$exam['elearning_flag'].'|'.$exam['sub_el_count'].'||'."\n"; 
						
					}
					else if($exam_code == 997)
					{
						$this->db->join('member_registration c','a.email=c.email','LEFT');
						$ex_code = $this->master_model->getRecords('member_registration_ippb a',array('email'=>$exam['email']),'emp_id,associatedinstitute');
						echo $this->db->last_query(); //die;
							if(count($ex_code))
							{
								$emp_id = $ex_code[0]['emp_id'];
								$inst_cd = $ex_code[0]['inst_cd'];
								if(empty($ex_code[0]['inst_cd']))
								{
								    $inst_cd = '2851';
								}
							}
							
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$scribe_flag.'|'.$exam['elearning_flag'].'|'.$exam['sub_el_count'].'|'.$emp_id.'|'.$inst_cd."\n"; 
					}
					else
					{
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$scribe_flag.'|'.$exam['elearning_flag'].'|'.$exam['sub_el_count'].'||'."\n"; 
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
			
		public function exam_ippb()
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
			$file = "exam_cand_report_ippb_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Candidate Exam Details Cron Start - ".$start_time." *********** \n");
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			//$yesterday = '2021-03-20';
		//	$not_exam_codes = array('991','1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014','2027'); // Not becoz this exam code we send exam date from other crons CSC and remote
		//	$mem = array(6868231,6869612,6868128,6864407,6861712,6869636,6868137,6864438,6868482,6864528);  
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,a.elearning_flag,a.sub_el_count,d.sub_cd,e.emp_id,c.associatedinstitute';
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
			$this->db->join('member_registration_ippb e','c.email=e.email','LEFT');
			$this->db->join('admit_card_details d','a.id=d.mem_exam_id','LEFT'); 
			$this->db->where_in('a.exam_code','997');
			//$this->db->where_in('a.exam_period',array('1018'));
		//	$this->db->where_in('a.id' , $mem);
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array('status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'pay_type'=>2),$select);
			//' DATE(a.created_on)'=>$yesterday,
			echo $this->db->last_query(); //die;
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
				     if($exam_code == 997)
					{
						$emp_id = $exam['emp_id'];
						$inst_cd = $exam['associatedinstitute'];
								if(empty($exam['associatedinstitute']))
								{
								    $inst_cd = '2851';
								}
							
							
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$scribe_flag.'|'.$exam['elearning_flag'].'|'.$exam['sub_el_count'].'|'.$emp_id.'|'.$inst_cd."\n"; 
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
			$mem = array(6558718,6565331,6550669,6565652,6558545,6552318,6548641,6558744);
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,a.elearning_flag';
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
			$this->db->where_not_in('a.exam_code',$not_exam_codes);
			$this->db->where_not_in('a.exam_period','122');
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
			//$yesterday ='2020-11-07';
			$id = array(6935396,6935391,6935405,6935406,6935408,6935411,6935412,6935415,6935419,6935425,6935431,6935434,6935433,6935445,6935443,6935446,6935447,6935577,6935608,6935614,6935620,6935625,6935626,6935624,6935623,6935627,6935635,6935636,6935642,6935645,6935650,6935643,6935657,6935661,6935662,6935678,6935696,6935695,6935697,6935702,6935701,6935703,6935709,6935720,6935722,6935726,6935741,6935744,6935757,6935771,6935770,6935772,6935780,6935783,6935787,6935801,6935814,6935826,6935836,6935845,6935866,6935868,6935876,6935877,6935880,6935882,6935883,6935884,6935878,6935889,6935890,6935892,6935896,6935903,6935905,6935906,6935907,6935908,6935910,6935913,6935922,6935919,6935921,6935923,6935925,6935930,6935931,6935932,6935936,6935937,6935940,6935938,6935946,6935948,6935949,6935947,6935951,6935952,6935955,6935956,6935954,6935967,6935968,6935969,6935966,6935971,6935975,6935993,6935992,6936001,6936002,6936000,6936024,6936028,6936023,6936029,6936031,6936009,6936034,6936036,6936049,6936058,6936093,6936067,6936108,6936109,6936113,6936116,6936115,6936118,6936119,6936095,6936123,6936122,6936125,6936127,6936129,6936130,6936134,6936135,6936137,6936138,6936143,6936144,6936147,6936148,6936150,6936149,6936151,6936152,6936154,6936153,6936165,6936166,6936164,6936159,6936162,6936167,6936169,6936172,6936177,6936176,6936179,6936180,6936182,6936184,6936187,6936190,6936191,6936194,6936193,6936195,6936196,6936197,6936199,6936200,6936202,6936203,6936205,6936207,6936201,6936208,6936209,6936210,6936216,6936217,6936221,6936222,6936225,6936224,6936226,6936227,6936230,6936229,6936234,6936236,6936237,6936235,6936242,6936244,6936245,6936250,6936251,6936254,6936255,6936260,6936261,6936262,6936263,6936264,6936266,6936268,6936269,6936270,6936274,6936276,6936278,6936279,6936280,6936283,6936282,6936289,6936288,6936290,6936296,6936297,6936299,6936305,6936306,6936312,6936314,6936318,6936319,6936325,6935407,6935488,6935593,6935659,6935694,6935742,6935773,6935810,6935812,6935857,6935867,6935887,6935965,6936003,6936020,6936019,6936124);
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,e.exam_date,c.email,c.associatedinstitute,c.mobile';
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
					if($exam_code == '997')
					{
						$ippb_data = $this->Master_model->getRecords('member_registration_ippb a',array('email'=>$exam['email'],'mobile'=>$exam['mobile']),'emp_id');
						$emp_id = $ippb_data[0]['emp_id'];
						
						if(empty($exam['associatedinstitute'])){ $inst_id = '2851';}
						$inst_id = $exam['associatedinstitute'];
						
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$exam['scribe_flag'].'|'.$exam_date.'|'.$inst_id.'|'.$emp_id."\n";
					}
					else{
					$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$exam['scribe_flag'].'|'.$exam_date.'||'."\n";
					}
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
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2022-06-29';
			
			$member = array(802026413,802026551,802026552,802026553,802026554,802026555,802026556,802026557,802026558,802026559,802026560,802026561,802026562,802029593);
			
			$this->db->select("namesub, firstname, middlename, lastname, regnumber, gender, scannedphoto, scannedsignaturephoto, idproofphoto, training_certificate, quali_certificate, qualification,registrationtype, address1, address2, city, district, pincode, state, dateofbirth, email, stdcode, phone, mobile, aadhar_no, idproof, a.transaction_no, DATE(a.date) date, DATE(a.updated_date) updated_date, a.UTR_no, a.amount, a.gateway, d.image_path, d.registration_no");
			$this->db->where("'d.isdeleted'= 0 AND c.pay_status = 1 ");
			$this->db->where("a.exam_period = 720 ");
			$this->db->join('dra_member_payment_transaction b','a.id = b.ptid','LEFT');
			$this->db->join('dra_member_exam c','b.memexamid = c.id','LEFT');
			$this->db->join('dra_members d','d.regid = c.regid','LEFT');
			$this->db->where_in('d.regnumber', $member);
			
			$this->db->where(" 'isdeleted'= 0 AND a.status = 1");/* DATE(a.updated_date) = '".$yesterday."' AND */
			//$this->db->where("DATE(a.updated_date) = '".$yesterday."' AND 'isdeleted'= 0 AND a.status = 1 ");
			
			$new_dra_reg = $this->Master_model->getRecords('dra_payment_transaction a');
      echo  $this->db->last_query(); //exit;
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
			//$yesterday = '2021-08-12';
			$member = array(845020728);
			
			
			
			$this->db->select("d.*");
			$this->db->where_in('d.regnumber',$member);
			//$this->db->where("DATE(d.editedby) = '".$yesterday."' AND d.isdeleted = 0 ");//AND d.new_reg = 1
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
			$member_no = array(6627194,7090556);
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
			$yesterday = '2022-04-23';
			
			//$member_no = array(6175319,6233893,6199638,6245890,6237946,6244364,6233102,6220980,6233265,6232370,6242607,6206311,6224567,6238399,6250861,6221833,6244488,6245703,6210187,6241376,6196316,6212323,6232761,6242285,6221991,6245331,6201521,6228804,6214614,6233350,6245320,6243990,6224894,6233098,6245876,6244823);
			
			$member_no = array(6920342,6925992,6926035,6926237,6926295,6926290,6926321,6926349,6926355,6926384,6926419,6926422,6926452,6926456,6926500,6926507,6926511,6926514,6926569,6926570,6926603,6926616,6926620,6926688,6926691,6926716,6926717,6926753,6926768,6926801,6926803,6926827,6926834,6926889,6926909,6926941,6926944,6926960,6926983,6926987,6927044,6927085,6927082,6927097,6927098,6927109,6927121,6927191,6927212,6927243,6927248,6927261,6927274,6927304,6927356,6927363,6927374,6927392,6927396,6927398,6927405,6927244,6925975,6926814,6926444,6926555,6926959,6926009,6926395,6926812,6926904,6927052,6927349,6926173,6926207,6926302,6926357,6926433,6926554,6926725,6927004,6927091,6926756,6926906,6926646);  
			$select = 'a.id as mem_exam_id, a.examination_date,b.transaction_no';
			$this->db->where_in('a.id', $member_no);
			//$this->db->where_in('a.exam_period', array('121','998','777','915','583','912')); //'998','777','219','120' 
			//$this->db->where_in('a.exam_code', array('991'));
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
			//$yesterday = '2022-04-24';
			// get Blended registration details for given date
			$id = array(23955,
23964,
23997,
24001,
24004,
24007);
			$br_select = 'c.*';
			$this->db->where_in('c.blended_id' , $id);
			//$this->db->where('c.fee =' , '0');
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
			$this->db->where_in('receipt_no',array(4279,4280));
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
			$yesterday = '2022-04-27';
			//DATE(date) = '".$yesterday."' OR
			//$this->db->where("( DATE(updated_date) = '".$yesterday."') AND status = 1");
			$this->db->where_in('UTR_no',array('INDBN26040222397/A','INDBN26040222397/B'));
			$bulk_payment = $this->Master_model->getRecords('bulk_payment_transaction');
			echo $this->db->last_query(); //die;
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
			$mem = array(6586790,6591059,6616763,6618732,6622241,6623245,6628712,6628802,6629903,6631064); 
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.scribe_flag';
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
			$this->db->where_in('a.id',$mem);
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>18,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1),$select);
			//' DATE(a.created_on)'=>$yesterday,
			echo $this->db->last_query();
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
            $members = array('510551117','510386813','510147882','510375448','510236475','510357596','510371227','510342998','510127387','510406431','510344777','510475395','510245378','510430529','510435022','510533960','510378797','510353080','510175651','510344960','510171448','510365001','510445239','500007627','510485911','510067518','510324092','510460939','510092643','500205380','510486287','510146211','510252952','510478016','510470032','510050358','510287475','510398057','510077572','510290453','510034917','510551134','500191642','802023724','510423903','510551136','500051754','510446116','510466127','510246558','500012586','801326471','510373803','500189036','510145467','510188614','510393732','510096369','510138171','510233005','510317067','510381052','510451581','510292768','500137771','510300223','510109497','510238941','510403797','510357568','510349086','510202570','510196060','510171803','510307432','510309767','510351706','510227303','500097560','510131290','510284682','510446345','510125211','510020892','510503530','510333958','510216879','7005340','510170392','510398432','500214146','510373758','510244365','510225391','510062466','510219553','500041446','801453878','510373644','510205432','510060221','V06442','510138208','510068492','510152102','510439336','510407125','500160401','500205533','510142450','510252630','500195652','300042368','510235710','510151675','500016707','500094921','510139229','510061559','510025772','510043792','500167585','510510477','100024373','510015136','510375040','510091344','500025299','802023741','510019827','510176264','500154164','500104093','510095500','510227358','510293885','510314792','500023540','500173668','510174922','510372621','500192717','510452132','510444375','510323187','510344407','510216405','500181543','510286522','500055471','400015711','400080568','510511649','500196619','500010280','510318875','7542117','500098409','500187967','510087421','510076127','510358084','510495432','510377912','510320014','510398817','510254070','510377803','510128310','510318935','510391729','510120583','510454387');
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