<?php
/*
 	* Controller Name	:	Cron File Generation
 	* Created By		:	Bhushan
 	* Created Date		:	26-07-2019
*/

defined('BASEPATH') OR exit('No direct script access allowed');
class Cron_app_custom extends CI_Controller {
			//exit;
	public function __construct(){
		parent::__construct();
		
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
		define('SCE_FILE_PATH','/fromweb/testscript/images/scribe/');
		
		/* define('MEM_FILE_PATH','/fromweb/images/newmem/');
		define('CSC_MEM_FILE_PATH','/fromweb/images/newmem/');
		define('DRA_FILE_PATH','/fromweb/images/dra/');
		define('MEM_FILE_EDIT_PATH','/fromweb/images/edit/');
		define('MEM_FILE_RENEWAL_PATH','/fromweb/images/renewal/');
		define('DIGITAL_EL_MEM_FILE_PATH','/fromweb/images/newmem/'); */
		
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
		
	/* Membership benchmark Edit data Cron */

	//   /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_app_custom member_live_chaitali_new
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
		$declaration_zip_flg = 0;
		$vis_imp_cert_img_zip_flg = 0;
		$orth_han_cert_img_zip_flg = 0;
		$cer_palsy_cert_img_zip_flg = 0;
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
			//$yesterday = '2022-10-15';
			$mem = array(510577100); 
			//$excode = array('991','526','527','101');
			//$this->db->where_not_in('a.excode', $excode);
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
				$declaration_cnt = 0;
				$vis_imp_cert_img_cnt = $orth_han_cert_img_cnt = $cer_palsy_cert_img_cnt = 0;
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
					$declarationimg = '';
					if(is_file("./uploads/declaration/".$reg_data['declaration']))
					{
						$declarationimg = MEM_FILE_PATH.$reg_data['declaration'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Declaration does not exist  - ".$reg_data['declaration']." (".$reg_data['regnumber'].")\n");	
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
					
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['registrationtype'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname'].'|'.$reg_data['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$reg_data['pincode'].'|'.$reg_data['state'].'|'.$address1_pr.'|'.$address2_pr.'|'.$address3_pr.'|'.$address4_pr.'|'.$district_pr.'|'.$city_pr.'|'.$reg_data['pincode_pr'].'|'.$reg_data['state_pr'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$reg_data['specify_qualification'].'|'.$reg_data['associatedinstitute'].'|'.$branch.'|'.$reg_data['designation'].'|'.$mem_doj.'|'.$reg_data['email'].'|'.$std_code.'|'.$reg_data['office_phone'].'|'.$reg_data['mobile'].'|'.$reg_data['idproof'].'|'.$reg_data['idNo'].'|'.$transaction_no.'|'.$transaction_date.'|'.$transaction_amt.'|'.$transaction_no.'|'.$optnletter.'|||'.$photo_send.'|'.$signature_send.'|'.$idproofimg_send.'|'.$declarationimg.'|||||'.$reg_data['aadhar_card'].'|'.$reg_data['bank_emp_id'].'|'.$benchmark_disability.'|'.$visually_impaired.'|'.$vis_imp_cert_img.'|'.$orthopedically_handicapped.'|'.$orth_han_cert_img.'|'.$cerebral_palsy.'|'.$cer_palsy_cert_img."\n";
					
					//print_r($data); die;

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
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg|| $declaration_zip_flg)
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
			$this->log_model->cronlog("IIBF New Member Details Cron Execution End", $desc);
			fwrite($fp1, "\n"."************************* IIBF New Member Details Cron Execution End ".$end_time." **************************"."\n");
			fclose($fp1);
		}
	}
	
	
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
			$benchmark_file = "edited_cand_details_benchmark_".$current_date.".txt";
			
			$benchmark_fp = fopen($cron_file_path.'/'.$benchmark_file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Benchmark Edited Candidate Details Cron Start - ".$start_time."**********\n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2022-08-11';//date('Y-m-d', strtotime("- 1 day"));
			$today = date('Y-m-d');
			$mem = array(510465901,510471294,510189815,500016431,500108806,510307188,510313745,500069476,500174644,510254744,510361879,510422768,510314596,510345477,510450192,500117300,510109233,510249105,510334819,500059889,510090602,801324708,801472525,801487869,801491301,801498037,801500402,801472885,801500653,801487051,801450521,801329522,801482253,801466252,801362262,801499875,801298453,510120152,510261057,510259566,510462702,510466803,801502246,801489602,801400899,801494316,500058781,500162383,510263977,510270630,510218657,510452296,510236654,510107359,510026561,510301528,801481791,801496358,801488238,801208132,801491474,510329718,510393917,510056647,510361332,510437306,510345386,510433230,510272357,500061510,801503365,510341816,510233708,510441392,510379780,500155180,500185655,510146952,510375049,500187950,801379345,801499673,801414485,801504249,801504449,801503874,801504184,510121707,510465928,801505560,801405068,801506811,801506645,510472171,801503603,500105550,510367497,510383092,510288264,400138283,510106820,510310841,510328692,510363359,801508510,801366322,801481238,801495864,510322534,510209541,500001659,510103931,7521190,801488229,510463754,500041815,510187746,801509401,801510534,510276667,510314439,510141265,510147790,510431239,510376080,510205208,801480848,801471430,510389023,801450380,801511493,510318904,510124369,510216419,510211877,510336009,510151443,510055936,510133208,510241883,510341725,801470024,801511555,801469112,801482523,510415294,510401690,510386534,510335369,510375835,510407743,801513584,500140970,510204509,510331871,500090907,510169678,500011953,801469164,801510722,801515098,510108569,510162521,510415226,510412588,801514641,801512364,801368832,510272855,510276390,500016907,400051468,510159532,510214435,801515280,801319943,510278128,801516292,801515461,801516616,801315219,510311532,510444742,510373005,510381031,801308731,510310804,510107319,510135802,500171096,510167312,510061386,510070850,510409690,801513190,510222815,510012654,510333306,510468265,510256894,510360122,510402455,510425447,510452215,510471651,510070880,801471813,510043856,510098817,510241602,510275652,510351394,510359709,510399709,510405322,510414938,510418099,510462807,510304347,510345990,510368968,510344060,510035040,510201265,510282240,510367202,510379067,510111259,510372302,510112719,510114725,510176239,510197859,510377871,801520638,801520112,510323530,510330958,510411062,510243890,510424357,510244156,510292636,510297574,510303028,510368587,510028484,510094897,510117435,510209758,510416257,510447719,510411882,500142387,801234035,801465950,801520792,801521835,510442327,510403336,500103088,500168582,510432127,510472965,510447372,500127464,510382123,510322323,510325572,510352171,510439001,510433706,500163907,510386051,510049230,510463852,510452984,510254443,510280046,510331968,801350678,801280881,801362331,510057860,510317676,801516046,510429141,510436746,510298914,510276683,510413072,510439356,510323465,510466801,510315696,510376925,500153711,510422222,801523978,801511439,510286860,510202340,510465164,510118190,510205769,510388038,801404136,801342718,5633099,510122988,510423122,510321142,500120233,510253738,100018643,510321188,510408486,510364358,510433562,510030150,510344101,510381592,510445709,510417419,510438593,510231803,510336694,801526169,801526739,801529007,801478461,801527866,500177517,510138882,510330339,510380720,510375863,500187652,510392775,500196669,510269170,500193413,510259134,510273680,510317171,500058348,500131545,510279078,510351031,510386426,510460581,510403059,510401529,510260172,510303109,510313319,7233757,510051227,510377402,510371273,510288815,510251698,510455646,510035218,500130818,500186006,510440862,510457506,500178321,510218101,510064910,510189741,510460904,700022369,801528042,801529876,801530884,801247647,510098574,510106775,510235067,510235254,510085258,510195587,510374846,500208683,510466309,500144572,510199946,510214122,510288014,510446790,510475880,510285833,510205138,510452338,510284898,510450705,801533307,801532802,510350591,510272404,510420786,510450575,510271330,510323903,510343194,801535597,801521182,510151178,510320454,510420532,510034607,100050444,510436326,510302986,510382907,510052411,510206883,510264575,510359805,801474170,801534456,801267034,801467742,801531435,510109057,7588493,510257199,510403309,510415604,510370612,510394633,500166586,510123984,700025321,510470761,510433289,500049235,510413887,510077869,7467086,510468211,801538925,801314300,801539187,801537192,801305699,801535583,801538624,510214850,510275050,510289644,500161850,510223537,510095448,510331129,510205272,510105315,510183897,510253853,510256320,510352832,510464506,500122382,500195902,500171527,510102101,510315176,510358666,510390882,510407087,510413794,510427284,510430489,510191198,510235250,510393999,510397951,510432607,510433481,510462037,510320181,510421293,510450672,500095096,510279504,510299914,510432106,510439612,510445621,510456844,510458907,510461494,500207421,500206847,510105488,510122293,510233730,510258613,510308292,510379698,510450963,510451883,500047744,510410078,510301560,510458300,510359948,700024776,801540320,801357417,700021763,801544860,510434600,510437874,510389678,510456533,510267202,500088815,510303620,510359540,500203277,510178741,510051782,510294518,510302557,510318026,510198900,510270167,510377596,510274419,510366155,510361790,510349115,510377206,510354466,510387444,510070566,510387602,510212144,510152099,510436648,510389750,500061102,500110323,510394307,200068746,510295870,510422597,510434180,500138222,510471707,510103830,510371263,510418673,500050132,510420550,510423226,510089081,510216572,510407677,510170252,510290553,500068872,510253915,510257477,510343503,500134742,510425676,510363865,510300826,510323485,510411252,510212492,510087556,510362763,500085010,510369153,510274696,500185607,510305601,510035733,510378723,510358735,510334720,510413092,510413481,510413498,510346265,510346321,510426048,510430714,510301933,510218230,510444078,510451119,510261663,500105272,510424340,510463354,500154452,510157893,510447518,510233733,510394515,510262064,510329389,510132309,510325816,510375389,500151737,510136348,510433381,510446962,510436502,510239681,510342507,510261650,500058384,510263789,510034887,510276912,510238886,510425549,510161705,510303150,510293512,510316550,510317254,510338003,510315076,510375239,510375971,510399085,500190893,510422073,510429738,510370458,510433149,500073975,510391350,510399035,510147330,510422235,510383014,510393481,510224673,510315562,510338654,500056522,510349755,510258471,510258814,510381178,510340555,510388094,500116363,500211646,510462611,500105485,510436538,510212007,510341023,510222134,510472744,510013355,500029635,510074563,510188605,510189492,510320418,510353931,510381532,510389718,510217146,510388144,510173628,510153737,510389857,510200899,510409053,510362595,510437380,510191879,510384604,510333939,510406202,510438632,510381046,510323161,500154087,500150111,7506854,510373599,510382346,510388867,510389484,510073411,510418432,510174230,510438536,510439438,510202967,510453560,500085477,510233997,510264838,510245667,510444477,510430783,510110079,510386482,510375400,510337027,510415351,510318522,510436793,510204076,510226886,510357288,510377711,500196299,510432366,510069373,510206635,500005357,500181676,500177786,510040962,510082554,510301063,510321549,510352215,510222952,510324096,510458225,510431301,700024930,510219307,510146002,510152267,510212490,510212563,510311250,510343964,510399288,510410304,510420127,510421294,510448065,510468572,510005850,500018838,510058306,510064514,510074769,510009422,510412651,510425490,510436729,500035238,500080387,510207816,510282118,510310810,510166242,510182100,510264768,510287197,510316171,510327569,510394688,510425808,510431484,510447936,500117703,500146268,500096564,500114551,500165003,500041835,510051827,510091467,510094119,510145820,510175824,510261713,510284200,510299771,510309404,510398161,510415974,510400318,510446253,510432114,500085162,500068723,510411978,510208151,510211982,510442991,510002421,510065475,510294820,510308202,510382759,510409050,510413251,200016061,500017667,510066260,510082055,510109617,510157404,510283702,510360106,510375326,510392132,510426150,500156355,510055322,510214645,510247980,510284619,510374566,510375291,510409359,510429340,510441920,500116405,500039245,500166005,510086480,510126569,510210021,510267177,510292279,510375616,510406066,510030966,510044651,510104559,510115663,510151250,510306131,510362246,510368917,510240686,500069954,510228181,510339643,5093746,510445832,510099474,510224825,510252091,510260692,510264645,510379759,510398190,500049552,500085695,510470199,500172060,510214308,510242020,510275385,510276398,510300578,510310975,510311685,510465711,510129300,510162305,510204080,510445976,510254790,510278315,510313035,510319305,510337014,510342543,510048322,510345828,510378905,510393948,510437675,510420164,510469616,510436570,7495993,500205079,500180495,510422774,510032058,510344343,510056311,510090708,510248570,510456937,510418209,510202189,510240886,510278107,510335479,510376884,510396132,510414239,510444014,510446944,510451208,510459508,510000942,510370354,510382271,510147080,510436980,510214909,510240470,510291438,510391626,510435943,510439378,510452827,7496195,510480164,500099244,510064077,510066020,510245924,510257829,510280620,500203017,510314974,510347190,510431870,510448967,510454969,100010449,510093467,510164762,510198608,510400114,510437508,510209321,510022334,510341812,510034886,510345674,510365576,510294034,510463893,510145307,510245380,510338496,510310431,510353499,510379430,510422773,500150112,500112596,500209541,510166134,510480902,510480944,510481235,510460424,510458025,510458368,801547305,801454800,801466746,801547377,801545538,801550547,500203102,510055186,510098808,510265178,510302206,510364405,510413551,510448332,510453683,510468024,510458337,510255857,510238771,510030507,500089023,510150193,700023922,510286620,510458892,510074173,510115718,510423990,510252525,510315903,801549194,510284508,510402704,801538300,801551478,510083100,510216790,500139006,510450387,510459950,510070827,510166535,510329463,510460053,510199960,510373352,510376130,510383998,510464868,510463388,510419513,510421380,510036622,510215619,510034850,500181370,510103768,510346869,510461477,510426278,510065681,510353249,510390954,801541504,801557147,510418186,500078818,510430117,510075670,510041910,500155246,510303763,510357927,510191472,510463875,510363669,500212682,510397369,510352826,510481951,801261006,801494392,801557375,510219408,510016906,510322689,801559381,801311997,510125176,510394383,510437269,801519033,510232076,510345844,510397759,510059053,510092297,510472176,510065533,510482816,801567768,801567098,801567976,801563932,510226742,510072664,510010507,510026848,510040315,510376046,510438832,510355995,801426069,510407341,510238949,510264358,510449338,510054755,510162674,510411770,510431204,510164966,510130692,510394863,510413052,510304397,510348382,510172220,801581179,801584388,801469839,801583546,801582677,801584998,801584895,801585692,801299494,510458883,510445442,510093732,510469060,510183930,510354866,510388893,500056276,510379277,510222937,510385970,510169900,510420449,510201667,510257844,510380733,500161902,510429744,500143830,7044624,510176765,801535506,510236299,510292185,500103466,510158963,510446738,510208222,500131910,510206571,510483628,801487460,801612557,500093275,500155349,510074979,510485136,510273699,510386045,510386868,510455329,510273026,510410843,510463744,7538581,510425543,510121562,700014621,700016428,700018988,700020810,801586527,801587468,801588661,801589770,801481488,801378109,801567695,801588815,801468960,801486935,801596895,801557052,801604079,801441361,801480015,801493778,801588204,801628477,801430235,510239669,510314322,510315503,510323611,510333849,510358454,510361876,510382158,510383234,510419925,510441675,510423885,510215098,510195837,510464638,510446324,510082334,510249286,510299627,510404445,510404838,510411453,510182039,510427172,510440003,510456421,510167987,510245490,510203899,510087396,510442471,510435265,510419145,510401364,510305334,510363951,510456495,510466830,510486589,510467897,500215916,510031646,510100829,510108698,510143157,510429326,510461489,510440799,510441033,510443657,510452193,510452488,510458761,510370052,510405093,510416704,510468718,510442031,510459019,510022428,500107700,510079250,510147640,510356463,510418949,510452478,510425533,510416441,510361539,510345115,510300676,510264694,510038141,510020624,510464927,510461320,500152815,510411177,510467922,510467810,510216779,510442704,510219384,510288993,510453136,510454255,510071544,510280054,510425103,510427557,510437298,510448773,510323720,510409250,510435967,510161339,510167610,510177945,510201151,510421209,510423459,510434788,510437716,510437794,510455555,510440966,510441048,510456952,500134736,500197743,510133870,510169661,510186669,510208614,510234966,510317241,510348618,510356124,510373074,510376005,510457031,510465052,510465055,510466288,510466545,510276317,510414875,510207551,510208864,510218999,510272398,510332392,510447226,510452000,510455343,500153238,500062386,500183134,500216253,510276162,510285517,510289387,510292931,510400903,510413540,510414801,510415696,510422833,510428511,510432878,510432892,500154788,500099537,510118900,510308509,510415630,510102185,510261732,510262881,510374850,510427220,510455530,510456623,510293503,510433330,510199459,510229699,510376715,510434020,510459438,510468862,510372868,500178087,500148784,510190209,510194988,510312043,510368984,510390852,510419492,510430661,510438464,510099638,510399071,510012522,500092228,510078673,510239416,510283578,510300767,510421116,510370493,500216165,510284693,500154792,510461870,510496023,510294221,510404174,510417992,510464620,510317390,510340962,801616626,801621958,801636718,801566984,801640943,801535010,700021626,700022061,700022944,510366221,510377467,510386977,510393992,510396808,510430592,510443812,510461004,510463230,510112891,510361243,510421944,510422991,510427420,510354979,510467151,500127520,510252953,510351996,510371592,510425655,510460830,510327548,510352272,510412972,510447912,510448602,510411500,500054619,500131723,801641411,700024732,510164679,510197629,510303617,510327023,510340234,510341832,510359524,510386768,510443683,510447198,510450276,510459769,510463462,510467441,510312424,510445088,510258082,510460021,510463643,510111735,510462829,510375937,510205844,510145902,510195567,510210889,510302518,510319180,510373141,510431934,510454641,510028072,510038021,510069501,510165366,510185350,510220616,510270281,510284099,510315809,510427601,510435646,510148872,510283887,510362953,510308188,510465366,510156189,510316812,510278907,510460869,510466129,510277067,510424762,510430423,510327799,510397820,510460453,510461350,510288957,510199085,510439122,510172984,510229968,510452274,510453292,510421807,510254127,510448359,510428259,510365262,510393356,510393589,510363036,510440284,510084760,510216209,510416484,500159462,510124584,510305464,510347245,510463823,510252309,510454468,510454809,510311767,510475518,510376353,510452308,510116691,510408072,510458072,500121843,500187112,500211078,510001327,510005613,510124303,510140588,510223323,510229267,510269929,510270071,510281139,510296038,510306127,510309293,510328272,510334413,510364141,510370328,510375550,510419187,510421600,510423054,510423666,510438369,510443896,510444435,510456927,510457205,510466473,510466642,510443177,510229943,510217561,510174825,510130346,500137386,500117193,500184866,510464796,510464621,510461633,500199050,510427249,510467632,510457067,510408942,510463009,510407302,510400639,300028028,510377233,510368938,510364636,510338172,510338068,500175672,500154060,510295215,510213606,510392458,510382418,510194670,510055576,510340552,510319308,510310312,510251351,510249190,510238559,510490066,500085123,510035947,510017963,510322652,510496108,510405766,510421345,510443318,510448348,500186649,510403707,510464223,510470443,510164496,510283937,510470730,510329371,510397100,510254553,510370764,510409962,510139917,510313778,510329531,510348677,510287382,510469102,510319287,510419604,510500393,510243711,510402683,510206370,510376684,510499071,510479863,510483782,510329512,510452649,510219446,510161016,510462709,510414476,510420472,510443289,510447716,510447927,510450770,510452180,510445968,510343436,510327150,510443133,510373865,510413664,510415020,510438175,510441504,510440765,510075565,510362928,510497971,500094534,510063754,510089273,510094208,510353934,510397259,510314344,510500033,510503083,510467894,510465353,510457656,510453357,500118932,510153034,510162463,510447906,510374473,510352534,510253478,510441490,510434581,510359051,510399812,510461305,510381633,510422262,510426032,510143266,510215905,510224659,510248403,510290988,510028394,510466098,500127039,510342976,510170219,510358616,510420353,510417092,510204841,510007137,510315314,510207351,510202372,510169817,510420659,510487105,510351767,510343416,510277979,510072816,510334393,510492427,510491140,510462040,510491988,510312333,510386316,510406995,510473853,510418555,510418817,510432793,510433042,510439306,510455143,510032292,510156856,510185928,510248383,510263054,500179985,510365891,510024952,500176625,510068273,510116051,510248133,510274155,510305869,510319595,510368071,510421859,510454717,510351714,510390439,510414754,510415227,510431737,510438031,510486916,510222930,510313458,510313553,510395997,510396764,510400030,510419720,510424867,510429437,510434539,510451277,510462849,510465537,510471675,510484588,510041892,510422575,510442217,510489101,500064216,510104246,510334185,510379313,510405810,510412069,510419752,510459857,510226377,510282848,510307080,510411049,510434442,510460637,510193120,510477733,510440356,510085052,510094581,510102552,510463682,510438551,510436836,510435915,510435682,510391128,510390861,510364175,510031792,510396403,510379708,510372608,510353664,510354917,510352980,510026805,510067550,510090074,510113770,510129267,510129222,510203022,510215858,510217217,510221409,510227734,510227615,510265563,510274402,510282345,510291539,510324348,510327361,510327410,510331039,510485653,510471332,510468872,510468597,510464591,510453547,510453489,510446412,510444516,510443229,510438077,510434158,510433367,510419444,510221248,500196225,500198707,510123247,510496783,510434926,510420221,500123147,510196619,510226866,510310210,510351298,510378768,500189279,510328294,510204555,510467750,510225912,510301915,510030085,510034118,510249673,500070390,510340955,510401179,510125250,510402809,510226978,500080266,500186462,510092311,510137242,510457182,510350882,510341853,510372519,510273783,510218830,510394632,510378432,510468209,700021661,700024672,700025831,500050294,500173619,500202132,500208568,510028042,510049910,510058575,510061124,510076909,510080284,510089446,510094350,510100662,510100847,510152799,510158018,510170100,510181438,510181532,510190117,510193448,510213765,510225768,510231528,510249016,510252197,510254463,510264154,510264751,510265343,510265630,510275173,510281506,510300321,510300514,510305436,510306132,510312369,510312410,510320244,510332501,510333880,510335645,510346369,510351469,510371536,510384228,510392275,510395701,510400042,510405473,510409520,510412502,510413354,510420542,510426512,510434668,510437211,510445721,510447003,510453491,510457964,510458937,510463343,510465493,510467061,510467156,510467657,510467816,510498394,510501766,500158203,500143520,510392934,510280848,510293538,510319831,510123578,510290962,510241077,510498497,510206294,510293592,510355231,510380362,510380960,510391356,801641754,801187576,801333928,801428647,801649892,801649634,801653954,801654390,510382387,200064952,500211306,510180303,510400891,510263099,510410807,510187922,510282326,510351077,510470072,510145215,510393458,510398096,510222227,500135474,510253443,510313114,510213228,200029354,500068005,500102776,510154535,510266486,510289679,510463468,510506368,801654544,801654668,500101523,500199185,510248758,510257842,510273638,500074453,500151202,510323521,510348036,510424408,510430731,510346235,510379475,510395589,510416526,510431718,510439086,510455917,510465836,510470062,700026779,500115651,510029986,510042109,510189505,500094647,510437795,510445674,510464902,500054560,510033465,510184027,510212652,510443564,510346297,510165386,510344900,510163031,510172328,510425832,510037853,510038411,510419685,510243994,510080775,510363859,510303198,510461495,510259054,510390064,510194766,510176425,510103242,510290918,510425155,510273446,510408868,510152055,510291514,510452664,510465007,510056059,510096267,510155118,510163210,510334713,801220838,801261605,801295209,801379652,801559135,801641634,801645497,801303044,801389971,510459725,510328260,510317778,510039824,7473904,510225513,510379201,510388565,510440180,510444509,500155058,500185600,510052854,510060589,510092770,510173204,510314242,510319745,510359845,510366468,510417445,510431320,510437635,510394647,510438096,510300882,510454665,510439565,510409647,510338527,510289599,510038680,510391895,510312596,510382260,510334342,510441054,510073187,500130384,500130966,500130136,510441714,510414785,510444191,510443474,510455878,510364322,510415724,510438738,510453423,510163427,500176021,510085361,510270852,500064815,510312857,510347504,510386221,510424379,510430263,510434032,510458010,510458195,500057073,500203765,510062432,510263462,510319749,500051890,510376719,510230892,510180129,510029126,500200327,510045085,510076449,510104523,510239862,510350815,510424955,510305568,510440560,510137402,500151964,510174678,510090987,510218057,510279813,510397479,510426225,510426636,510435394,510383357,500215633,510021901,510387215,510455948,500055208,510437053,510328375,510386057,510383340,510442855,500091557,510223756,510382458,500093853,500075982,500144825,510186089,510376063,510165367,510202486,510217178,510223705,510222934,510225765,510241024,510312772,510354519,510016781,510413497,510430901,510434533,510304661,510049703,510453749,510242223,510059558,510433907,510457178,510336081,510016645,510173488,510212747,510285658,510027372,510164044,510039541,510113783,510361778,510106468,500205543,510362309,510120153,510287336,510361275,510069393,510206106,510413213,510097448,510223362,510426827,510484133,500066534,510423672,510024727,510102847,510369541,500141955,500003227,510280131,500076184,500049520,510118567,510164537,510322149,510372761,510146268,510140577,500169083,500149509,801660167,801385541,801540156,801650163,801657666,510279292,510480079,510071502,510351977,510181358,510234877,510438956,500062009,510022864,500067269,7288479,510346892,510292648,510303187,510277444,500068828,510384298,510056844,500065455,510357646,510164108,510224899,510342445,510382202,801661436,801664339,801667558,510340875,801672175,510371300,510400924,801338881,801673005,801496450,510092379,510213793,801405581,510128579,510330297,500169156,510383454,510385700,500081746,510162269,510326280,5629711,510387638,801496316,801674275,801677384,500136469,510005577,510102532,510331195,510230150,500071065,510300829,510037674,510418708,510046312,510194527,510105644,510401170,510236860,510287593,510381570,510071276,510351337,510199113,801677656,510047314,510312814,510085707,500137264,510361403,500079505,801678025,510104788,510276175,801678514,801235325,801407082,801678918,801426304,801679580,510354823,510098584,510254491,801680480,510013352,510116893,801681190,801480909,801682694,510403784,801598776,500110324,801681850,801325517,801686975,801687931,801679921,510478386,510027966,500136149,510105805,801311501,801692781,500056195,801696235,801694034,801697496,510507354,510313025,801700483,801700617,801701525,6543460,801535820,801334868,801702126,200013775,801703140,510386889,510507284,801687001,801703986,801686747,801703789,801519197,510198967,801681455,7591201,700020304,801706776,500004525,510076518,510207839,510243028,801707419,801709515,801277675,801710046,500035558,510323186,801541166,801709643,801716449,510393689,510387868,801728573,801730264,510407609,801732370,801707357,510395057,510504308,510452304,500113948,500093610,500055962,510386511,801732883,801734622,801734634,801677427,801735226,500144692,500047332,500178019,500212216,510184054,510499677,500033033,510291298,510318475,801734805,500012897,801738719,500188324,510391139,510409891,510292928,510453410,801694340,510014175,510258438,510050878,510422176,801268152,801406333,801671282,510510074,510058413,5118139,510452545,510258560,510026316,510176170,510510654,801270918,801743263,6802006,510482817,801251806,801747147,510505848,510092038,801746532,500061237,500072808,500086746,510343919,500149056,801757182,801746585,801747012,801757181,801759038,500091694,801401610,801743386,801744242,801730128,510068221,500099813,500135634,500153797,510285961,510351881,510394326,510468132,801243839,801715716,510103611,801763087,801761185,801760450,510315514,801740070,801631322,700015109,510057568,801765435,500003058,510217112,510398720,510423631,510469096,500107124,801340861,510446052,510182164,801406545,510459184,510174109,510253522,510304071,510506720,801182036,801743517,801779517,801247821,801779080,801779623,6288821,510035474,801774005,801783796,510435738,510236598,801784362,801780881,510369340,500193992,510395957,510513623,500151203,801778924,510011314,510157619,510396998,510399947,510418172,510436055,801761020,801787557,510202077,500083431,500196864,510229083,510305268,510438657,510425770,510428955,510316743,500143386,510137094,510275603,510331200,510335956,510389475,510452064,510458279,510509774,510074248,510370746,510440798,400129721,500195656,500213256,500211010,510098420,510105217,510154747,510222188,510253768,510354254,510289504,510359757,510361685,510368365,510371120,510382233,510409311,510410741,510416941,510445358,510448003,510448355,510492200,7259351,801764817,801793019,801328436,500207720,510253213,510169168,510284875,510326502,510285434,510276300,510160560,510132950,510079562,510468289,510381578,510457370,510396059,510397874,510277188,510363844,510469091,510458667,510368936,510327026,510517241,510517402,510035375,510140545,510211968,510275951,510276425,510302644,510317865,510366529,510378957,510396726,510408788,510414027,510415813,510420394,510427608,510444776,510444808,510446769,510464900,510493245,510512521,510512968,801795125,801798220,801248229,801296701,801468505,801501197,801512981,801519844,510473616,801625555,801732703,801311770,801790646,510458904,500077624,500151267,510057379,510122718,510250408,510277460,510382810,510394381,510414285,510496098,500117726,510109002,510122722,510135602,510147050,510166496,510253331,510269739,510199362,510357489,510403515,510407697,510431175,510439271,510440466,510458097,510465672,510295530,510237709,510077101,510423037,510489044,510497954,500168081,510152455,510179022,510195267,510371605,510425872,400109129,500158352,510289221,510382610,510520246,510188461,510189250,510286544,510317687,510443191,510447066,510035545,510441940,500134677,500137107,500158684,500162028,500177543,510044849,510144890,510246968,510300083,510305434,510386034,510416646,510439371,510440122,510465561,510474568,700021076,510091011,510107455,510129579,510216930,510257987,510280225,510328040,510361482,510412372,510429538,510031566,510123034,510223546,510323397,510371711,510382381,510443156,510523345,801805287,801799569,510187198,510199864,510220596,510248466,510320926,510327137,510347505,510170477,510345514,510461640,510108774,510188656,510248075,510273950,510284505,510318336,801806049,510525788,801706756,801787430,801807269,510133710,510242880,510286450,510380828,510484782,500095910,510140993,510175208,510502571,510075482,510296847,510304712,510312390,510360938,510423438,510446487,510525609,801808565,801808746,801809099,500113355,500152867,510073790,510133861,510195978,510279170,510290782,510320542,510327658,510379175,510390526,510412357,510435610,510441646,510461342,510462689,510505849,500088739,500199024,500206339,510154848,510168056,510181836,510199642,510352776,510369089,510369688,510380384,510466943,510467204,510468669,510469095,500173561,510298955,510423140,510441302,510481207,510529443,500186378,500207422,510060500,510105053,510147595,510216793,510300099,510412084,510527730,801808555,510078432,510086991,510145778,510379922,510384847,510393312,510440666,510473566,510525633,400121508,510116100,510166232,510174519,510195708,510311473,510344514,510358675,510398960,500042346,500096391,500101800,510281532,510303793,510040044,510058285,510154639,510180727,801811062,801811382,801811547,510032610,510035914,510146733,510193722,510322247,510323104,510329706,510386436,510402850,510448405,500089737,500138314,500170891,510042343,510125032,510158253,510197963,510214863,510236968,510244185,510251635,510358092,510365160,510423414,510437021,510468405,510471108,7587807,100065456,500134826,510018435,510209179,510318545,510408270,500181029,510094687,510399488,510445056,510458157,801813908,801775058,801811063,510436515,510446739,510466060,500116359,510101919,510262525,510238630,510390221,510460127,801815121,500124768,500136586,510315646,500052362,500152875,510065299,510331867,510414708,510523976,801817851,801807273,500014960,500160469,510226123,510289827,510392305,801816417,801820901,500068282,500084157,510446315,500067197,801710940,801817875,801784182,510085488,510195592,510205452,510236629,510278221,510316494,510331060,510396253,510494731,500055827,500114021,510138884,510295693,510365768,510388387,510391960,510392673,510459764,500120500,500183913,510021212,510042738,510160794,510176908,510229594,510245038,510408819,510441627,500138632,801820854,510100317,510333820,510459635,801261009,510499692,801831519,801832502,801832812,801832916,500124859,510359896,510386227,801805178,510367997,510460200,801831368,510346248,801836869,510332604,801243299,801833129,500213419,510239553,510302492,801845097,801845330,500031670,801312341,801353329,801790623,500085424,801803450,801803813,801821504,801846541,5985046,801816558,801848935,801851385,801848945,801855163,801852665,801853521,510057647,801851706,801835012,801847857,801854952,500139193,801859026,510300733,510405912,801236705,801852341,801852579,801862036,801862410,801862790,801854570,510490541,801859867,801865421,500090670,510515857,801859573,801863565,801866284,801866689,801864323,801866794,801288287,801807306,510299591,801845362,500177394,510194203,7513492,801799379,801865228,510167263,510518744,510521580,500076115,510318194,801862785,801878212,801800379,801862492,801885776,510437152,500069514,510154077,801064729,801657134,801857040,801862251,801891931,801893311,801894469,801894731,801897116,801898979,510054637,510372590,801031099,801290594,801812829,801894332,801899604,801899994,510529928,801871115,801901689,500166274,510516830,801901949,510058958,510476793,700027029,801563778,510334838,801903687,801871760,801242751,801885854,801892604,510532105,801905511,801905555,500082188,500150969,510070757,7016414,801893448,801903307,801907275,801909562,510418644,6539734,801911848,801907468,510451164,801905840,801914981,510147311,510212682,510390498,801915472,801916639,801917603,510270363,801259339,801414604,801917933,510402963,510350879,510532976,510457132,801927954,801928043,801928436,801286539,510061490,801928533,801929441,801927961,801930584,801931093,801931919,510533092,510532695,510121072,510255708,801933032,801927606,510378922,801933070,801933428,801934184,801934583,801934625,801890653,801932048,510530704,801286005,801927091,801933112,510533664,500015902,500046048,500120520,510023682,510068669,510076162,510107397,510144656,510169194,510314129,510317257,510333680,510374560,510412190,510418127,510497315,510518289,500190936,510227733,801274997,801834473,801859992,801899525,801472571,801761404,500204283,510232203,510232580,510325251,510349478,510393642,510433550,510496075,801908457,510248951,510381020,510386481,510399063,510423231,510428798,510454649,510460852,400061339,510160516,510171094,510204308,510234650,510498705,510533984,510534082,510534535,801938522,510534505,510534511,510534624,510533847,510533808,510533954,510534448,510534093,500059351,500199090,510278886,510344279,510373990,510534510,510534836,510534918,510535018,510535286,510535327,510535351,510535560,510147666,510317200,510361854,510426011,510440905,510535178,801337144,801940516,801941132,801943906,801876419,510347804,510088440,7359230,510032168,510535013,500080360,510095088,510327585,510415417,500068143,500072497,510344902,510413738,510497794,801946927,801947721,500013061,500208125,510170827,510242820,510295583,510318327,510400482,510533288,510534842,510535269,801943765,801944565,500123702,510118152,510212619,510213839,510299916,510307232,510349278,510357617,510435134,510448693,510451873,510503410,510534917,510535052,510535340,510535434,510535471,510535474,500065575,500143632,510050831,510142567,510159475,510199672,510238870,510242859,510249125,510323481,510382774,510396440,510411461,510456468,510503908,510514109,510534487,510534821,801958100,510535564,510535669,510535677,510535813,510535809,510535974,510536023,510535953,510535927,510537743,801940443,801947355,500125539,500160955,500170580,510117429,510152864,510179383,510213079,510216629,510222525,510239169,510322290,510341804,510387847,510427251,510461554,510463611,510535889,500212597,510045076,510087027,510132189,510188727,510191234,510195004,510210012,510240747,510252878,510265037,510306328,510308309,510333600,510340667,510362492,510363395,510370439,510419263,510425660,510484467,510504773,510527345,510534734,510535077,801963416,500200165,510219473,510331580,510348716,510349448,510382374,510426414,510466076,500003635,500104754,500164091,500180147,510055251,510293419,510295594,510333812,510336655,510415946,801964265,510546519,510033943,510349137,510362785,510462257,510468009,510494680,500186900,510090707,510137887,510247347,510323003,510332459,510465464,510466331,510539231,510542765,7383816,510108301,510155821,510277417,510395905,510411084,801949349,510547801,500135444,500161484,500215182,510086936,510092659,510226956,510370932,510381515,510400366,510405352,510405567,510426558,510444895,510459473,510544411,801273191,510548512,500030665,510418032,510035337,510028749,510030715,510007847,510087022,510112536,510241771,801963539,500114264,500168908,510017310,510099104,510113213,510126588,510140749,510156736,510170516,510180844,510245045,510274040,510303479,510321695,510327025,510360992,510529721,7403543,510388943,500053766,500189509,500214581,510004460,510222253,510247097,510302149,510333511,510416520,510443363,6600682,801404544,801938401,801972685,300037902,500215853,510022663,510086136,510215951,510285662,510483762,510518743,7486519,500174781,510025238,510150905,510159737,510188520,510189848,510343918,510388942,510405545,510408590,510511809,510550166,510536804,510335254,510425411,5660819,801964745,801380893,510068699,510174158,510205465,510240311,510386194,801236083,510537262,510114642,510302984,801672877,801983255,801982742,510324955,510392882,510546739,510549701,500016735,510189092,510399878,801940916,510538432,500187623,510043849,510295267,801964956,801985085,801344987,801941083,801984831,510097994,510263861,510263912,510316056,510427414,801986946,510103368,510159977,510382228,510541721,801987455,500041272,510043627,510080260,510092342,510228475,510256032,510375855,500183391,801515629,801991128,801691666,510479427,801743940,700016332,801987024,510550368,801995802,510325586,801987404,510121441,802001146,801998128,802006552,510205803,801894836,802004965,510329507,510162806,510092471,500151655); 
			$this->db->where_in('regnumber', $mem);
			
			$edited_benchmark_data = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','isdeleted'=>0,'benchmark_edit_flg' => 'Y','benchmark_disability'=>'Y'));
			//'DATE(benchmark_edit_date)'=>$yesterday,
			echo $this->db->last_query();
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
					
					
					$data = ''.$imgdata['regnumber'].'|'.$imgdata['registrationtype'];  // added by chaitali on Clients request on 2022-06-20
					
					/* $data = ''.$imgdata['regnumber'].'|'.$imgdata['registrationtype'].'|'.$imgdata['namesub'].'|'.$imgdata['firstname'].'|'.$imgdata['middlename'].'|'.$imgdata['lastname'].'|'.$imgdata['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$imgdata['pincode'].'|'.$imgdata['state'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$imgdata['specify_qualification'].'|'.$imgdata['associatedinstitute'].'|'.$branch_name.'|'.$imgdata['designation'].'|'.$mem_doj.'|'.$imgdata['email'].'|'.$std_code.'|'.$imgdata['office_phone'].'|'.$imgdata['mobile'].'|'.$imgdata['idproof'].'|'.$imgdata['idNo'].'||||'; */
					
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
					usr_id */

					
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
					
					$benchmark_data = $data.'|'.date('d-M-y H:i:s',strtotime($imgdata['benchmark_edit_date'])).'|'.$benchmark_disability.'|'.$visually_impaired.'|'.$orthopedically_handicapped.'|'.$cerebral_palsy.'|'.$vis_imp_cert_img.'|'.$orth_han_cert_img.'|'.$cer_palsy_cert_img.'|'.$img_edited_by."\n";
					  
					/* $benchmark_data = $data.$img_edited_by.'|'.$optnletter.'|N|N|N|'.date('d-M-y H:i:s',strtotime($imgdata['benchmark_edit_date'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id'].'|'.$benchmark_disability.'|'.$visually_impaired.'|'.$vis_imp_cert_img.'|'.$orthopedically_handicapped.'|'.$orth_han_cert_img.'|'.$cerebral_palsy.'|'.$cer_palsy_cert_img."\n"; */
							
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
								$max_width = "1000";
								$max_height = "800";
								
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
								$max_width = "1000";
								$max_height = "800";
								
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
								$max_width = "1000";
								$max_height = "800";
								
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
	public function benchmark_edit_data_old()
	{exit;
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
			$benchmark_file = "edited_cand_details_benchmark_".$current_date.".txt";
			
			$benchmark_fp = fopen($cron_file_path.'/'.$benchmark_file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Benchmark Edited Candidate Details Cron Start - ".$start_time."**********\n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$today = date('Y-m-d');
			
			$edited_benchmark_data = $this->Master_model->getRecords('member_registration',array('DATE(benchmark_edit_date)'=>$yesterday,'isactive'=>'1','isdeleted'=>0,'benchmark_edit_flg' => 'Y'));
			
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
					usr_id */

					
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
	
		/* Admit Card Cron */
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
		$declaration_zip_flg = 0;
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
			//$yesterday = '2022-08-11';
			$member_no = array(802104106,802104167,802104180,802104217,802104273,802104286,802104301); 
			$excode = array('528', '529', '530', '531', '534','991');
			$this->db->where_in('a.regnumber', $member_no);
			$this->db->where_not_in('a.excode', $excode); 
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0));	
			//' DATE(createdon)'=>$yesterday,
			//echo $this->db->last_query(); die;
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
						}
						else
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'iibfregn','status'=>1,'pay_type'=>1,'ref_id'=>$reg_data['regid']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
							//'pg_flag'=>'iibfregn',
							//echo $this->db->last_query(); die;
						}
					}
					else
					{
						$trans_details = $this->Master_model->getRecords('payment_transaction a',array('status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
						//'pg_flag'=>'IIBF_EXAM_REG',
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
					
					
					$data .= ''.$reg_data['regnumber'].'|'.$reg_data['registrationtype'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname'].'|'.$reg_data['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$reg_data['pincode'].'|'.$reg_data['state'].'|'.$address1_pr.'|'.$address2_pr.'|'.$address3_pr.'|'.$address4_pr.'|'.$district_pr.'|'.$city_pr.'|'.$reg_data['pincode_pr'].'|'.$reg_data['state_pr'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$reg_data['specify_qualification'].'|'.$reg_data['associatedinstitute'].'|'.$branch.'|'.$reg_data['designation'].'|'.$mem_doj.'|'.$reg_data['email'].'|'.$std_code.'|'.$reg_data['office_phone'].'|'.$reg_data['mobile'].'|'.$reg_data['idproof'].'|'.$reg_data['idNo'].'|'.$transaction_no.'|'.$transaction_date.'|'.$transaction_amt.'|'.$transaction_no.'|'.$optnletter.'|||'.$photo.'|'.$signature.'|'.$idproofimg.'|'.$declarationimg.'|||||'.$reg_data['aadhar_card'].'|'.$reg_data['bank_emp_id'].'|'.$benchmark_disability.'|'.$visually_impaired.'|'.$vis_imp_cert_img.'|'.$orthopedically_handicapped.'|'.$orth_han_cert_img.'|'.$cerebral_palsy.'|'.$cer_palsy_cert_img."\n";
					//print_r($data); die;
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
			//$yesterday = '2022-12-09';
			$member = array(801036174,801248754,801385885,801479644,801749887,801800103,801866493,801869836,802007886,802040145,802040402,802062399,802068218,802086163,802087185,802091547,802110103,802116119,802126938,802130342,802156746,802156755,802156855,802156934,802156950,802157110,802157237,802157286,802157314,802157350,802157394,802157423,802157434,802157478,802157592,802159049,802159062,802159067,802159142,802159176,802159241,802159341,802159345,802159356,802159527,802159551,802159572,802159585,802159599,802159610,802159612,802159615,802159641,802159683,802159710,802159712,802159750,802159751,802159804,802159915,802159940,802159943,802159963,802159967,802159975,802159996,802160011,802160019,802160047,802160059,802160065,802160075,802160099,802160101,802160105,802160108,802160109,802160129,802160137,802160148,802160149,802160156,802160165,802160171,802160180,802160193,802160210,802160212,802160214,802160234,802160237,802160240,802160242,802160243,802160264,802160269,802160270,802160273,802160277,802160289,802160291,802160295,802160299,802160300,802160302,802160307,802160314,802160329,802160331,802160334,802160335,802160336,802160338,802160342,802160350,802160353,802160361,802160371,802160383,802160391,802160394,802160396,802160408,802160409,802160423,802160427,802160432,802160467,802160471,802160476,802160479,802160489,802160501,802160502,802160507,802160508,802160512,802160521,802160523,802160525,802160532,802160533,802160537,802160545,802160547,802160552,802160565,802160567,802160574,802160578,802160588,802160589,802160590,802160591,802160594,802160595,802160596,802160604,802160605,802160608,802160609,802160611,802160612,802160614,802160615,802160616,802160617,802160620,802160624,802160625,802160626,802160627,802160628,802160633,802160634,802160636,802160639,802160640,802160641,802160643,802160647,802160648,802160649,802160651,802160653,802160654,802160655,802160656,802160658,802160660,802160661,802160664,802160665,802160667,802160668,802160669,802160672,802160673,802160674,802160675,802160677,802160678,802160679,802160680,802160682,802160683,802160685,802160688,802160690,802160692,802160693,802160694,802160695,802160696,802160698,802160699,802160700,802160702,802160703,802160705,802160708,802160709,802160710,802160712,802160715,802160717,802160719,802160720,802160721,802160722,802160723,802160725,802160726,802160729,802160730,802160732,802160735,802160737,802160738,802160739,802160740,802160742,802160745,802160749,802160750,802160755,802160759,802160760,802160761,802160768,802160769,802160771,802160773,802160774,802160775,802160776,802160777,802160778,802160781,802160783,802160785,802160790,802160827,802160828,802160829,802160958,802161120,802161342,802161343,802161345,802161347,802161351,802161353,802161356,802161357,802161359,802161361,802161362,802161366,802161367,802161370,802161372,802161373,802161376,802161378,802161379,802161380,802161382,802161383,802161384,802161387,802161388,802161391,802161392,802161397,802161398,802161400,802161405,802161406,802161407,802161408,802161411,802161412,802161413,802161415,802161420,802161423,802161427,802161428,802161430,802161431,802161432,802161434,802161436,802161437,802161438,802161440,802161441,802161442,802161447,802161448,802161450,802161453,802161455,802161457,802161458,802161467,802161468,802161469,802161470,802161475,802161476,802161477,802161478,802161479,802161484,802161485,802161489,802161490,802161491,802161492,802161493,802161494,802161496,802161498,802161500,802161501,802161504,802161506,802161507,802161508,802161509,802161510,802161512,802161513,802161515,802161563,802161567,802161570,802161572,802161573,802161578,802161579,802161580,802161583,802161591,802161592,802161596,802161600,802161606,802161610,802161612,802161614,802161615,802161616,802161617,802161618,802161620,802161624,802161626,802161627,802161628,802161629,802161631,802161632,802161633,802161634,802161636,802161640,802161641,802161642,802161643,802161644,802161647,802161652,802161654,802161655,802161656,802161658,802161661,802161671,802161673,802161674,802161676,802161677,802161678,802161682,802161687,802161689,802161692,802161693,802161695,802161697,802161699,802161700,802161704,802161705,802161707,802161708,802161710,802161712,802161713,802161716,802161718,802161719,802161721,802161725,802161727,802161728,802161732,802161735,802161741,802161744,802161745,802161746,802161747,802161748,802161753,802161755,802161758,802161769,802161774,802161776,802161777,802161778,802161779,802161783,802161787,802161788,802161790,802161791,802161792,802161800,802161802,802161803,802161804,802161805,802161807,802161808,802161809,802161810,802161813,802161815,802161816,802161818,802161819,802161821,802161822,802161825,802161828,802161832,802161837,802161840,802161841,802161843,802161850,802161851,802161853,802161857,802161862,802161866,802161867,802161868,802161869,802161871,802161872,802161873,802161874,802161879,802161881,802161885,802161893,802161894,802161897,802161902,802161905,802161906,802161907,802161911,802161916,802161918,802161921,802161925,802161927,802161928,802161930,802161942,802161947,802161953,802161955,802161956,802161958,802161959,802161962,802161963,802161965,802161966,802161969,802161975,802161977,802161978,802161979,802161981,802161984,802161986,802161988,802159343);
			$exam_period = array('998','997');
			$this->db->join('payment_transaction b','b.member_regnumber = a.regnumber','LEFT');
			$this->db->where_in('a.exam_period', $exam_period);
			$this->db->where_in('a.regnumber', $member);
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0,'bankcode' => 'csc'));
			//,'bankcode' => 'csc'
			//' DATE(createdon)'=>$yesterday,
			echo $this->db->last_query(); //die;
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
					//echo $reg_data['scannedphoto'];
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
						$trans_details = $this->Master_model->getRecords('payment_transaction a',array('status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');		
					}
					//'pg_flag'=>'CSC_NM_REG',
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
			
			$yesterday = '2022-12-10';
			//$mem = array(802067633,802067632,802067637);
			$excode = array('528', '529', '530', '531', '534');
			$this->db->where_in('a.excode', $excode);
			//$this->db->where_in('a.regnumber', $mem);
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0));	
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
		
		//$current_date = '20190603';
		
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
			$yesterday  = '2022-09-22';
			//$member_no = array();
			$select = 'regnumber,namesub,firstname,middlename,lastname,scannedphoto,scannedsignaturephoto,idproofphoto';
			$this->db->join('payment_transaction','payment_transaction.member_regnumber = member_registration.regnumber','LEFT');
			//$this->db->where_in('member_registration.regnumber',$member_no);
			$new_mem_reg = $this->Master_model->getRecords('member_registration',array(' DATE(date)'=>$yesterday,'isactive'=>'1','isdeleted'=>0,'bankcode' => 'csc', 'status'=>'1'),$select);
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
	
	public function csc_member_sagar()
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
			$mem = array();
			$select = 'regnumber,namesub,firstname,middlename,lastname,scannedphoto,scannedsignaturephoto,idproofphoto';
			$this->db->join('payment_transaction','payment_transaction.member_regnumber = member_registration.regnumber','LEFT');
			$this->db->where_in('regnumber',$mem);
			$new_mem_reg = $this->Master_model->getRecords('member_registration',array('pay_type'=>2,'isactive'=>'1','isdeleted'=>0,'bankcode' => 'csc', 'status'=>'1'),$select);
			//' DATE(date) >='=>'2021-05-01', ' DATE(date) <='=>'2021-05-14',
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
		$this->log_model->cronlog("E-learning Separate Module Member Registration Details Cron Execution Start", $desc);
		
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
			//$this->db->where('DATE(er.createdon)',$yesterday);
			$member_no = array(510062718,510284763,510284763,510284763,EL000004409,510554693,510554693,500126010,510099102,500112574,500112574,500112574,510046069);
			$this->db->group_by('er.regnumber');
			$select = 'er.regid, er.regnumber, er.namesub, er.firstname, er.middlename, er.lastname, er.state, er.email, er.registrationtype, er.mobile, er.isactive, er.createdon, pt.id AS PtId, pt.pay_type, pt.status AS PtStatus';
			$this->db->where_in('er.regnumber',$member_no);
			$this->db->join('payment_transaction pt', 'pt.member_regnumber = er.regnumber AND pt.pay_type = 20 AND status = 1', 'INNER');
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
			$this->log_model->cronlog("E-learning Separate Module Member Registration Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** E-learning Separate Module Member Registration Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1); 
		}
	}	
	
/* Membership Edit data Cron */
//URL : https://iibf.esdsconnect.com/index.php?/admin/Cron_app_custom/edit_data
	public function edit_data()  
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$Zip_flg = 0;
		$photo_file_flg = 0;
		$sign_file_flg = 0;
		$idproof_file_flg = 0;
		$decl_file_flg = 0;
		$edit_file_flg = 0;
		$success = array();
		$error = array();
		$ii = 0;
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
			
			/* $file = "edited_cand_details_".$current_date.".txt";
			$photo_file = "edited_cand_details_photo_".$current_date.".txt";
			$sign_file = "edited_cand_details_sign_".$current_date.".txt";
			$id_file = "edited_cand_details_id_".$current_date.".txt"; */
			$file = "edited_cand_details_".$current_date.".txt";	// Path with CURRENT DATE DIRECTORY
			//$benchmark_file = "edited_cand_details_".$current_date.".txt";
			
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			 $photo_fp = fopen($cron_file_path.'/'.$file, 'w'); 
			$sign_fp = fopen($cron_file_path.'/'.$file, 'w');
			$id_fp = fopen($cron_file_path.'/'.$file, 'w'); 
			$decl_fp = fopen($cron_file_path.'/'.$file, 'w'); 
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Edited Candidate Details Cron Start - ".$start_time."**********\n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2023-01-20';//date('Y-m-d', strtotime("- 1 day"));
			$today = date('Y-m-d'); 
			//$this->db->limit(1);
			$member_no = array(510575990,510575783,510575900,510522819,6440551,510575998,200054910,500188117,510061523,510145882,510403554,510511755,510576197,510296157,510478745,500093363,510296221,510057678,510074201,510575930,510575828,510294752,510048517,500168832,510325398,500027226,500000907,510574834,500179664,500086036,500069051,7441670,6601161,500154515,500077742,510057382,510276559,510315377,510342326,510575939,510276078,510575701,510159892,510163042,500092107,200027565,510576156,510437005,510576151,510419234,510509242,510543379,510575701,510575968,510575891,510514284,510334679,510204175,510533524,500054967,510576125,500216181,510198444,510361130,510520015,500188635,510548966,510482369,510431145,510447234,510037602,500045254,500101799,500175595,500185737,510540444,510446922,510279520,7002965,510556443,510247453,510528424,510294716,510466236,500008833,510575938,510303603,510474158,500194297,500040024,510377195,510119347,510426388,510430786,510575701,7475883,500162878,510461270,510454802,510575979,510576033,500017713,500017213,510575847,510181170,510064523,510076413,510487483,510576326,510575829,510553601,500001158,500060079,500074634,500083350,500101610,510327205,510383438,510576391,500190013,500123738,510122011,500078313,500144780,500200831,500174283,500193679,510227414,510575559,500115962,510401635,7234404,500095663,500086489,510055837,510576419,510428463,510377195,500189857,500008884,510036268,510576666,400128330,510044043,510234772,500128288,510277794,510298890,510576108,500063452,510214145,510576355,500154723,510121753,500208400,510242615,500142736,6588631,510052574,510335369,510576697,510576676,510235112,500102900,500201408,500052992,500117879,510430675,510080087,510018729,500195378,510045986,500112961,510576540,500149155,510415270,500165527,510058447,510532543,500121217,500028258,500207341,500008125,510169660,7539499,510175571,500150274,4601027,510575365,500052129,500098040,500183324,510531414,500135094,400117855,500035140,500008810,500111918,7225076,500105598,400099194,510110734,500193990,6409426,6997220,500016364,500057146,510574977,510575365,500201814,100034137,510131409,500064440,510163118,500184414,510576310,510047553,500187045,510269110,510222064,500017081,500184785,500008986,510292736,6991137,510521418,400046889,510576379,500165320,510574247,500151819,500204117,510163118,510187660,510318523,510054045,510432560,510277851,500068254,510161886,510417661,500062256,500091931,510316611,510173443,510570128,500127250,500138697,500010515,500154087,510072834,510464032,510341936,510576026,510577332,510575559,500126719,500045493,510570128,510577243,510570128,510576165,510072742,510412106,510400238,510450521,510468721,510470696,510433787,510540553,510019659,500207208,510057666,510131753,510160733,510209500,510285503,510315906,510529657,500045283,510577512,510577488,510577444,510295667,510308659,510577691,510243048,510517134,7166430,510576617,510156152,510243666,510391819,510396417,510541406,510150192,510323279,510357656,500161073,7454471,510568381,510534206,510522504,510513067,510172022,510553889,510140605,510507188,500019406,500049480,500136267,500187809,510576596,510577026,510577031,510577700,510557073,510536248,510538355,510538112,510540674,510543270,510543787,510543735,510544599,510545742,510544990,510549990,510549435,510553987,510554717,510554885,510555300,510554965,510555943,510556798,510555484,510559343,510557180,510558706,510561085,510561445,510563247,510565081,510563601,510569687,510571182,510572034,510572555,510573522,510572666,510574471,510576310,510575586,510574844,510574856,510576369,510577249,510577355,510576771,510577250,510577347,510578032,7575029,7356499,400066326,400023098,100071847,200054301,500002459,500032997,500033872,500002271,500014003,500038868,500043032,500062934,500049677,500060371,500051328,500054730,500063237,500050749,500072067,500071927,500078451,500078448,500083530,500083297,500081849,500086753,500097822,500103154,500108330,500113078,500136415,500142348,500159058,500158333,500151425,500152244,500153962,500172966,500177702,500165605,500166746,500190202,500193166,500196192,500195413,500194141,500197152,500210737,500208676,500200376,500210192,510018262,510047073,510049608,510052933,510092944,510094235,510117283,510117003,510140409,510141634,510141572,510162866,510188980,510197982,510208145,510217097,510223472,510244010,510247609,510271592,510270692,510292584,510300970,510327448,510334966,510351959,510355224,510361307,510369361,510374891,510377691,510388067,510393820,510393611,510399354,510401536,510408255,510405826,510405560,510411093,510415483,510428033,510430415,510429641,510434913,510452818,510452563,510446450,510450804,510454101,510459142,510467384,510480420,510476681,510481387,510488898,510489756,510490672,510496784,510495180,510499561,510498688,510505677,510520794,510521021,510532596,510531928,510575805,500176866,510079140,500165186,510220971,510555165,500034426,500061584,500068714,500083622,500095409,500097218,510056357,510538373,510573342,510574826,510576362,500019369,510575805,510576125,510570128,510570128,500175787,510576325,510576322,510576325,510576322,500198661,510574247,510487941,100069482); 
			$this->db->where_in('regnumber',$member_no);
			$edited_mem_data = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','isdeleted'=>0,'images_editedon'=>'0000-00-00'));
			//' DATE(editedon)'=>$yesterday,
			//echo $this->db->last_query(); die; 
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
					
					if($ii == 0){$data = ''; }else{$data = "\n";}
					
					$data .= $editeddata['regnumber'].'|'.$editeddata['registrationtype'].'|'.$editeddata['namesub'].'|'.$editeddata['firstname'].'|'.$editeddata['middlename'].'|'.$editeddata['lastname'].'|'.$editeddata['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$editeddata['pincode'].'|'.$editeddata['state'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$editeddata['specify_qualification'].'|'.$editeddata['associatedinstitute'].'|'.$branch_name.'|'.$editeddata['designation'].'|'.$mem_doj.'|'.$editeddata['email'].'|'.$std_code.'|'.$editeddata['office_phone'].'|'.$editeddata['mobile'].'|'.$editeddata['idproof'].'|'.$editeddata['idNo'].'||||';
					
					$data .= $edited_by.'|'.$optnletter.'|N|N|N|N|'.date('d-M-y H:i:s',strtotime($editeddata['editedon'])).'|'.$editeddata['aadhar_card'].'|'.$editeddata['bank_emp_id'].'||||';
					
					
					
					//print_r($data); die;
					$edit_data_flg = fwrite($fp, $data);
					if($edit_data_flg)
						$success['edit_data'] = "Edited Candidate Details File Generated Successfully.";
					else
						$error['edit_data'] = "Error While Generating Edited Candidate Details File.";
					
					$i++;
					$mem_cnt++;
					$ii++;
				}
				
				fwrite($fp1, "\n"."Total Members Edited = ".$mem_cnt."\n");
			}
			else
			{
				$success[] = "No Profile data found for the date";
			}
			
			// Image data 
			//$this->db->limit(1);
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2023-01-20';//date('Y-m-d', strtotime("- 1 day")); 
			$today = date('Y-m-d'); 
			$member_no = array(510575990,510575783,510575900,510522819,6440551,510575998,200054910,500188117,510061523,510145882,510403554,510511755,510576197,510296157,510478745,500093363,510296221,510057678,510074201,510575930,510575828,510294752,510048517,500168832,510325398,500027226,500000907,510574834,500179664,500086036,500069051,7441670,6601161,500154515,500077742,510057382,510276559,510315377,510342326,510575939,510276078,510575701,510159892,510163042,500092107,200027565,510576156,510437005,510576151,510419234,510509242,510543379,510575701,510575968,510575891,510514284,510334679,510204175,510533524,500054967,510576125,500216181,510198444,510361130,510520015,500188635,510548966,510482369,510431145,510447234,510037602,500045254,500101799,500175595,500185737,510540444,510446922,510279520,7002965,510556443,510247453,510528424,510294716,510466236,500008833,510575938,510303603,510474158,500194297,500040024,510377195,510119347,510426388,510430786,510575701,7475883,500162878,510461270,510454802,510575979,510576033,500017713,500017213,510575847,510181170,510064523,510076413,510487483,510576326,510575829,510553601,500001158,500060079,500074634,500083350,500101610,510327205,510383438,510576391,500190013,500123738,510122011,500078313,500144780,500200831,500174283,500193679,510227414,510575559,500115962,510401635,7234404,500095663,500086489,510055837,510576419,510428463,510377195,500189857,500008884,510036268,510576666,400128330,510044043,510234772,500128288,510277794,510298890,510576108,500063452,510214145,510576355,500154723,510121753,500208400,510242615,500142736,6588631,510052574,510335369,510576697,510576676,510235112,500102900,500201408,500052992,500117879,510430675,510080087,510018729,500195378,510045986,500112961,510576540,500149155,510415270,500165527,510058447,510532543,500121217,500028258,500207341,500008125,510169660,7539499,510175571,500150274,4601027,510575365,500052129,500098040,500183324,510531414,500135094,400117855,500035140,500008810,500111918,7225076,500105598,400099194,510110734,500193990,6409426,6997220,500016364,500057146,510574977,510575365,500201814,100034137,510131409,500064440,510163118,500184414,510576310,510047553,500187045,510269110,510222064,500017081,500184785,500008986,510292736,6991137,510521418,400046889,510576379,500165320,510574247,500151819,500204117,510163118,510187660,510318523,510054045,510432560,510277851,500068254,510161886,510417661,500062256,500091931,510316611,510173443,510570128,500127250,500138697,500010515,500154087,510072834,510464032,510341936,510576026,510577332,510575559,500126719,500045493,510570128,510577243,510570128,510576165,510072742,510412106,510400238,510450521,510468721,510470696,510433787,510540553,510019659,500207208,510057666,510131753,510160733,510209500,510285503,510315906,510529657,500045283,510577512,510577488,510577444,510295667,510308659,510577691,510243048,510517134,7166430,510576617,510156152,510243666,510391819,510396417,510541406,510150192,510323279,510357656,500161073,7454471,510568381,510534206,510522504,510513067,510172022,510553889,510140605,510507188,500019406,500049480,500136267,500187809,510576596,510577026,510577031,510577700,510557073,510536248,510538355,510538112,510540674,510543270,510543787,510543735,510544599,510545742,510544990,510549990,510549435,510553987,510554717,510554885,510555300,510554965,510555943,510556798,510555484,510559343,510557180,510558706,510561085,510561445,510563247,510565081,510563601,510569687,510571182,510572034,510572555,510573522,510572666,510574471,510576310,510575586,510574844,510574856,510576369,510577249,510577355,510576771,510577250,510577347,510578032,7575029,7356499,400066326,400023098,100071847,200054301,500002459,500032997,500033872,500002271,500014003,500038868,500043032,500062934,500049677,500060371,500051328,500054730,500063237,500050749,500072067,500071927,500078451,500078448,500083530,500083297,500081849,500086753,500097822,500103154,500108330,500113078,500136415,500142348,500159058,500158333,500151425,500152244,500153962,500172966,500177702,500165605,500166746,500190202,500193166,500196192,500195413,500194141,500197152,500210737,500208676,500200376,500210192,510018262,510047073,510049608,510052933,510092944,510094235,510117283,510117003,510140409,510141634,510141572,510162866,510188980,510197982,510208145,510217097,510223472,510244010,510247609,510271592,510270692,510292584,510300970,510327448,510334966,510351959,510355224,510361307,510369361,510374891,510377691,510388067,510393820,510393611,510399354,510401536,510408255,510405826,510405560,510411093,510415483,510428033,510430415,510429641,510434913,510452818,510452563,510446450,510450804,510454101,510459142,510467384,510480420,510476681,510481387,510488898,510489756,510490672,510496784,510495180,510499561,510498688,510505677,510520794,510521021,510532596,510531928,510575805,500176866,510079140,500165186,510220971,510555165,500034426,500061584,500068714,500083622,500095409,500097218,510056357,510538373,510573342,510574826,510576362,500019369,510575805,510576125,510570128,510570128,500175787,510576325,510576322,510576325,510576322,500198661,510574247,510487941,100069482);
			$this->db->where_in('regnumber',$member_no);
			$edited_img_data = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','isdeleted'=>0));
			//'DATE(images_editedon)'=>$yesterday, 
			//echo '<br>'.$this->db->last_query(); exit;
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
				$declaration_cnt = 0;
				$photo_flg_cnt = 0;
				$sign_flg_cnt = 0;
				$idproof_flg_cnt = 0;
				$declaration_flg_cnt = 0;
				foreach($edited_img_data as $imgdata)
				{	
					$photo = '';
					$signature = '';
					$idproofimg = '';
					$declaration = '';
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
					
					if($ii == 0){$data = ''; }else{$data = "\n";}
					$data .= $imgdata['regnumber'].'|'.$imgdata['registrationtype'].'|'.$imgdata['namesub'].'|'.$imgdata['firstname'].'|'.$imgdata['middlename'].'|'.$imgdata['lastname'].'|'.$imgdata['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$imgdata['pincode'].'|'.$imgdata['state'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$imgdata['specify_qualification'].'|'.$imgdata['associatedinstitute'].'|'.$branch_name.'|'.$imgdata['designation'].'|'.$mem_doj.'|'.$imgdata['email'].'|'.$std_code.'|'.$imgdata['office_phone'].'|'.$imgdata['mobile'].'|'.$imgdata['idproof'].'|'.$imgdata['idNo'].'||||';					
					
					if($imgdata['images_editedby'] == '')
					{
						$img_edited_by = "Candidate";
					}
					else
					{
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

				    $photoserver = $member_img_response['scannedphoto'];
				    $idproofimgserver = $member_img_response['idproofphoto'];
					$declarationserver = $member_img_response['declarationphoto'];
				    $signatureserver = $member_img_response['scannedsignaturephoto'];
					
					if($imgdata['scannedphoto'] != "" && is_file("./uploads/photograph/".$imgdata['scannedphoto']))
					{
						$photo 	= MEM_FILE_EDIT_PATH.$imgdata['scannedphoto'];
					}
					else{
						$photoarr = explode("/",$photoserver); 
						$photo 	= MEM_FILE_EDIT_PATH.$photoarr[2];
					}
					 
					if($imgdata['scannedsignaturephoto'] != "" && is_file("./uploads/scansignature/".$imgdata['scannedsignaturephoto']))
					{
						$signature 	= MEM_FILE_EDIT_PATH.$imgdata['scannedsignaturephoto'];
					}
					else{
						$signarr = explode("/",$signatureserver);
						$signature 	= MEM_FILE_EDIT_PATH.$signarr[2];
					}
					
					if($imgdata['idproofphoto'] != "" && is_file("./uploads/idproof/".$imgdata['idproofphoto']))
					{
						$idproofimg = MEM_FILE_EDIT_PATH.$imgdata['idproofphoto'];
					}
					else{
						$idarr = explode("/",$idproofimgserver);
						$idproofimg = MEM_FILE_EDIT_PATH.$idarr[2];
					}
					if($imgdata['declaration'] != "" && is_file("./uploads/declaration/".$imgdata['declaration']))
					{
						$declaration = MEM_FILE_EDIT_PATH.$imgdata['declaration'];
					}
					else{
						$declarationarr = explode("/",$declarationserver);
						$declaration = MEM_FILE_EDIT_PATH.$declarationarr[2];
					}
					$photo_img_data = '';
					$sign_img_data = '';
					$idproff_img_data = '';
					$decl_img_data = '';
					if($photoflag == 'Y'){$photo_img_data = $photo;}
					if($signflag == 'Y'){$sign_img_data = $signature;}
					if($idprofflag == 'Y'){$idproff_img_data = $idproofimg;}
					if($declarationflag == 'Y'){$decl_img_data = $declaration;}
					
					$edit_data = $data.$img_edited_by.'|'.$optnletter.'|'.$photoflag.'|'.$signflag.'|'.$idprofflag.'|'.$declarationflag.'|'.date('d-M-y H:i:s',strtotime($imgdata['images_editedon'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id'].'|'.$photo_img_data.'|'.$sign_img_data.'|'.$idproff_img_data.'|'.$decl_img_data;
					$edit_file_flg = fwrite($fp, $edit_data);
					
					if($edit_file_flg){
					$success['file'] = "Edited Candidate Details Photo File Generated Successfully. ";}
					else{
					$error['file'] = "Error While Generating Edited Candidate Details Photo File.";}
					
					
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
								//$image = $photoarr[2];
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
							elseif($photoserver!= '')
							{
								
								$image = $photoserver;
								$max_width = "200";
								$max_height = "200";
								
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$photoarr[2]);
								
								//copy($actual_photo_path,$directory."/".$actual_photo_name);
								$photo_to_add = $directory."/".$photoarr[2];
								$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
								$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
								if(!$photo_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$photoarr[2]." (".$imgdata['regnumber'].")\n");	
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
								//$image = $signature;
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
							elseif($signatureserver != '')
							{
								
								$image = $signatureserver;
								$max_width = "140";
								$max_height = "100";
								
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$signarr[2]);
								
								//copy($actual_sign_path,$directory."/".$actual_sign_name);
								$sign_to_add = $directory."/".$signarr[2];
								$new_sign = substr($sign_to_add,strrpos($sign_to_add,'/') + 1);
								$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
								if(!$sign_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$signarr[2]." (".$imgdata['regnumber'].")\n");	
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
							elseif($idproofimgserver != '')
							{ 
								$image = $idproofimgserver;
								$max_width = "800";
								$max_height = "500";
								
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$idarr[2]);
								
								//copy($actual_idproof_path,$directory."/".$actual_idproof_name);
								$proof_to_add = $directory."/".$idarr[2];
								$new_proof = substr($proof_to_add,strrpos($proof_to_add,'/') + 1);
								$idproof_zip_flg = $zip->addFile($proof_to_add,$new_proof);
								if(!$idproof_zip_flg)
								{
									fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$idarr[2]." (".$imgdata['regnumber'].")\n");	
								}
								else 
									$idproof_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$idproofimg." (".$imgdata['regnumber'].")\n");	
							}
						}
						
						
						if($imgdata['declaration_flg'] == 'Y' && $declaration != '')
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
									fwrite($fp1, "**ERROR** - Declaration not added to zip  - ".$declaration." (".$imgdata['regnumber'].")\n");	
								}
								else 
									$declaration_cnt++;
							}
							elseif($declarationserver !='')
							{
								$image = $declarationserver;
								$max_width = "800";
								$max_height = "500";
								$img_resize_data = $this->resize_image_max($image,$max_width,$max_height);
								imagejpeg($img_resize_data, $directory."/".$declarationarr[2]);
								$declaration_to_add = $directory."/".$declarationarr[2];
								$new_declaration = substr($declaration_to_add,strrpos($declaration_to_add,'/') + 1);
								$declaration_zip_flg = $zip->addFile($declaration_to_add,$new_declaration);
								if(!$declaration_zip_flg)
								{
									fwrite($fp1, "**ERROR** - Declaration not added to zip  - ".$declarationarr[2]." (".$imgdata['regnumber'].")\n");	
								}
								else 
									$declaration_cnt++;
							}
							else
							{
								fwrite($fp1, "**ERROR** - Declaration does not exist  - ".$declaration." (".$imgdata['regnumber'].")\n");	
							}
						}

						// echo'xxxxxxxxxxxxxxxxxxxxxx';
						// echo $photo_zip_flg;echo '<br>';
						// echo $sign_zip_flg;echo '<br>';
						// echo $idproof_zip_flg;echo '<br>';
						// echo $declaration_zip_flg;die;
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg || $declaration_zip_flg)
						{
							$success['zip'] = "Edited Candidate Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating Edited Candidate Images Zip";
						}
					}
					$ii++;
				}
				
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."/".$photo_flg_cnt." \n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."/".$sign_flg_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."/".$idproof_flg_cnt."\n");
				fwrite($fp1, "\n"."Total Declaration Added = ".$declaration_cnt."/".$declaration_flg_cnt."\n");
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
			fclose($decl_fp);
			
			
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
	public function edit_data_no_imagechk()
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
			$this->db->where_in('DATE(benchmark_edit_date)',$yesterday);
			$edited_mem_data = $this->Master_model->getRecords('member_registration',array(' DATE(editedon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
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
					
					
					$benchmark_data = $data.$img_edited_by.'|'.$optnletter.'|'.$photoflag.'|'.$signflag.'|'.$idprofflag.'|'.$declarationflag.'|'.date('d-M-y H:i:s',strtotime($imgdata['benchmark_edit_date'])).'|'.$imgdata['aadhar_card'].'|'.$imgdata['bank_emp_id'].'|'.$photo.'|'.$signature.'|'.$idproofimg.'|'.$declaration.'|'.$benchmark_disability.'|'.$visually_impaired.'|'.$vis_imp_cert_img.'|'.$orthopedically_handicapped.'|'.$orth_han_cert_img.'|'.$cerebral_palsy.'|'.$cer_palsy_cert_img."\n";
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
				fwrite($fp1, "\n"."Total Declaration Added = ".$declaration_cnt."/".$declaration_flg_cnt."\n");
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
	
	/* Membership Edit data Cron */
	public function edit_data_old()
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
			$today = date('Y-m-d');
			$edited_mem_data = $this->Master_model->getRecords('member_registration',array(' DATE(editedon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
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
			$edited_img_data = $this->Master_model->getRecords('member_registration',array('DATE(images_editedon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
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
			$ref_id = array(31211);
			$select = 'c.regnumber,c.reason_type,c.icard_cnt,a.registrationtype,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount,b.transaction_no';
			$this->db->join('member_registration a','a.regnumber=c.regnumber','LEFT');
			$this->db->where_in('c.did', $ref_id);
			$this->db->join('payment_transaction b','b.ref_id=c.did','LEFT');
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
			//$yesterday = '2022-08-11';
			//$not_exam_codes = array('991','1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014','2027','1019','1020'); // Not becoz this exam code we send exam date from other crons CSC and remote
			$mem = array(7507606);  
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,a.elearning_flag,a.sub_el_count';
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
			//$this->db->where_not_in('a.exam_code',$not_exam_codes);
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
			//$yesterday = '2022-12-10';
			$id = array(7460787,7460799);
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,e.exam_date,c.email,c.associatedinstitute,c.mobile';
			$this->db->where_in('a.id',$id);
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('member_registration c','a.regnumber = c.regnumber','LEFT'); 
			$this->db->join('admit_card_details e','a.id = e.mem_exam_id','LEFT'); 
			$this->db->where_in('a.exam_code',array('991','997'));
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'a.free_paid_flg'=>'P'),$select);
			//' DATE(a.created_on)'=>$yesterday,
			echo $this->db->last_query();
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
			//$yesterday = '2023-01-15';
			$exam_codes_arr = array('1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014','2027','1019','1020');
			//$mem = array(7457415,7457472,7457562,7457600,7457634,7457701,7457711,7457765,7457772,7457834,7458255,7458440,7458687,7458823,7458831,7458966,7458985,7459082,7459086,7459295,7459536,7459633,7459673,7459716,7459754,7459880,7459930,7460007,7460069,7443592,7457358,7457360,7457499,7457593,7457613,7457617,7457672,7457754,7457776,7457789,7457836,7457879,7457958,7457965,7458099,7458105,7458159,7458232,7458314,7458327,7458362,7458371,7458401,7458409,7458476,7458526,7458542,7458547,7458566,7458624,7458668,7458676,7458774,7458855,7458886,7458887,7458898,7458904,7458906,7458931,7458978,7458979,7458993,7459001,7459003,7459005,7459008,7459011,7459026,7459057,7459058,7459072,7459107,7459114,7459124,7459177,7459183,7459185,7459202,7459271,7459279,7459289,7459347,7459366,7459445,7459461,7459466,7459583,7459611,7459631,7459668,7459722,7459732,7459734,7459813,7459831,7459855,7459870,7459884,7459886,7459920,7459941,7459956,7459967,7459984,7460002,7460004,7460040,7460042,7460062,7460079,7457610,7457660,7458111,7458124,7458146,7458210,7458228,7458306,7458564,7458598,7458862,7458975,7459088,7459089,7459091,7459095,7459111,7459154,7459190,7459193,7459218,7459245,7459470,7459523,7459541,7459618,7459675,7459763,7459809,7459842,7459872,7459901,7459952,7460048,7457389,7457473,7457560,7457607,7457637,7457639,7457747,7457812,7457886,7458169,7458368,7458483,7458610,7458719,7458793,7458861,7458872,7459015,7459108,7459213,7459314,7459495,7459705,7459744,7459803,7460003,7460045,7460098,7460099,7460101);
			$ref_id = array(7509656,7509670,7509686,7509705,7509756,7509789,7510819,7510398,7509747,7509675,7509786,7509883);
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,e.exam_date,a.elearning_flag,a.free_paid_flg,sub_el_count';
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('member_registration c','a.regnumber = c.regnumber','LEFT'); 
			$this->db->join('admit_card_details e','a.id = e.mem_exam_id','LEFT'); 
			$this->db->where_in('a.exam_code',$exam_codes_arr);
			//$this->db->where_in('a.id' , $mem);
	    	$this->db->where_in('a.id',$ref_id);
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1),$select);
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
					}	//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG,EXAM_DATE,ELEARNING_FLAG,free_paid_fl FREE_PAID_FLG(P/F)

					//added by pooja mane : 24-01-2023
					if($exam['elearning_flag'] == 'Y')
					{
						$el_count = '1';//$exam['sub_el_count']
					}
					else{
						$el_count = $exam['sub_el_count'];
					}
					//condtion end : pooja mane
					
					$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$exam['scribe_flag'].'|'.$exam_date.'|'.$exam['elearning_flag'].'|'.$free_paid_flg.'|'.$el_count."\n";
					//'|'.$exam['sub_el_count'].
					
					
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
		if(!file_exists($cron_file_dir.$current_date)){
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
			$exam_codes_arr = array('1002','1003','1004','1005','1006','1007','1008','1009','1010','1011','1012','1013','1014','991');
			$select = 'a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,e.exam_date,a.elearning_flag,a.free_paid_flg,a.created_on';
			$this->db->join('member_registration c','a.regnumber = c.regnumber','LEFT'); 
			$this->db->join('admit_card_details e','a.id = e.mem_exam_id','LEFT'); 
			$this->db->where_in('a.exam_code',$exam_codes_arr);
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'a.free_paid_flg'=>'F'),$select);
			
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
					
					$exam_date = '';
					if($exam['exam_date'] != '0000-00-00'){
						$exam_date = date('d-M-Y',strtotime($exam['exam_date']));
					}
					
					$place_of_work = $pin_code_place_of_work = $state_place_of_work = $city = $branch = $branch_name = $state = $pincode = $exam_period = $exam_code = '';
					
					$exam_period = $exam['exam_period'];
					$exam_code = $exam['exam_code'];
					$transaction_no = '0000000000000';
					$exam_fee = '0';
					//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG,EXAM_DATE,ELEARNING_FLAG(Y/N),FREE_PAID_FLG(F/P)
					
					$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$transaction_no.'|'.$trans_date.'|'.$exam_fee.'|'.$transaction_no.'|'.$exam['scribe_flag'].'|'.$exam_date.'|'.$exam['elearning_flag'].'|'.$exam['free_paid_flg']."\n"; 
					
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
			else{
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
		$this->log_model->cronlog("Candidate Exam E-learning Separate Module Details Cron Execution Start", $desc);
		
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
			//$yesterday ='2022-12-10'; 
			//$this->db->where('DATE(ms.created_on)',$yesterday);
			//$this->db->group_by('ms.el_sub_id');
			$member_no = array(400015176);
			$select = 'ms.el_sub_id, ms.regid, ms.subject_description, ms.fee_amount, ms.sgst_amt,  ms.cgst_amt, ms.igst_amt, ms.cs_tot, ms.igst_tot, ms.updated_on, pt.transaction_no, ms.exam_code, ms.subject_code, pt.member_regnumber, pt.member_exam_id, pt.amount, DATE_FORMAT(pt.date,"%Y-%m-%d")date, pt.receipt_no, pt.ref_id, pt.status, er.regnumber, er.registrationtype, er.state';
			$this->db->where_in('er.regnumber',$member_no);
			$this->db->join('payment_transaction pt','pt.receipt_no = ms.receipt_no','LEFT');
			$this->db->join('spm_elearning_registration er','er.regnumber = ms.regnumber','LEFT'); 
			$can_exam_data = $this->Master_model->getRecords('spm_elearning_member_subjects ms',array('ms.status'=>'1', 'pt.pay_type'=>'20', 'pt.status'=>'1','er.isactive'=>'1'),$select);
			//echo $this->db->last_query(); exit; 
			
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
			$this->log_model->cronlog("Candidate Exam E-learning Separate Module Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Candidate Exam E-learning Separate Module Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}	
		
		
	/* DRA Members Cron */
	public function dra_member()
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
			
			$member = array(802176251,802176252,802173838);
			
			$this->db->select("namesub, firstname, middlename, lastname, regnumber, gender, scannedphoto, scannedsignaturephoto, idproofphoto, training_certificate, quali_certificate, qualification,registrationtype, address1, address2, city, district, pincode, state, dateofbirth, email, stdcode, phone, mobile, aadhar_no, idproof, a.transaction_no, DATE(a.date) date, DATE(a.updated_date) updated_date, a.UTR_no, a.amount, a.gateway, d.image_path, d.registration_no");
			$this->db->join('dra_member_payment_transaction b','a.id = b.ptid','LEFT');
			$this->db->join('dra_member_exam c','b.memexamid = c.id','LEFT');
			$this->db->join('dra_members d','d.regid = c.regid','LEFT');
			$this->db->where_in('d.regnumber', $member);
			//$this->db->where("DATE(a.updated_date) = '".$yesterday."' AND 'isdeleted'= 0 AND a.status = 1 AND d.new_reg = 1 ");
			$new_dra_reg = $this->Master_model->getRecords('dra_payment_transaction a');
			
		/*AND re_attempt = 0
		$this->db->where("( DATE(a.date) = '".$yesterday."'  OR DATE(a.updated_date) = '".$yesterday."') AND 'isdeleted'= 0 AND a.status = 1");*/
			
			if(count($new_dra_reg))
			{
				$data = '';
				$dirname = "dra_regd_image_".$current_date;
				$directory = $cron_file_path.'/'.$dirname;
				if(file_exists($directory))
				{
				 	array_map('@unlink', glob($directory."/*.*"));
				 	rmdir($directory);
				// $existing_files = glob($directory.'/*'); // get all file names
    //                 foreach($existing_files as $file)
    //                 { // iterate files
    //                     if(is_file($file))
    //                     {
    //                         @unlink($file); // delete file
    //                     }
    //                 }
    //                 rmdir($directory);
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
	
	/* DRA Edited Members data Cron : added by sagar on 13-08-2021 */
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
		$cron_file_dir = "./uploads/cronFilesCustom/";
				
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("DRA Edited Candidate Details Cron Execution Start", $desc);
		
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
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2022-11-24';
			$mem = array(802125287,802124715,801977023,802003991,802004000,801921746); 
			$this->db->where_in('regnumber', $mem);
			$this->db->select("d.*");
			$this->db->where("d.isdeleted = 0 AND d.regnumber != '' AND d.regnumber != '0'"); //AND d.new_reg = 1

			//"DATE(d.editedby) = '".$yesterday."' AND
			$new_dra_reg = $this->Master_model->getRecords('dra_members d');
			//,array('DATE(editedby)' =>$yesterday)
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
					
					$img_dir_path = '/fromweb/testscript/images/dra/'; 
					
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
			$this->log_model->cronlog("DRA Edited Candidate Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** DRA Edited Candidate Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
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
				
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2022-08-11';
			$ref_id = array(68758,68770,68772,68956);
			
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
					//coded added by chaitali on 2021-06-10
					$exam_code = $dup_cert['exam_code'];
					if($exam_code == '555')
					{
						$exam_code = 1;
					}
					$INSTRUMENT_TYPE = 'ONLINE';	// mode of application
													
					$data .= ''.$exam_code.'|'.$dup_cert['exam_name'].'|'.$dup_cert['regnumber'].'|'.$dup_cert['registrationtype'].'|'.$dup_cert['namesub'].'|'.$dup_cert['firstname'].'|'.$dup_cert['middlename'].'|'.$dup_cert['lastname'].'|'.$dup_cert['address1'].'|'.$dup_cert['address2'].'|'.$dup_cert['address3'].'|'.$dup_cert['address4'].'|'.$dup_cert['district'].'|'.$dup_cert['city'].'|'.$dup_cert['state'].'|'.$dup_cert['pincode'].'|'.$dup_cert['email'].'|'.$dup_cert['mobile'].'|'.$dup_cert['amount'].'|'.$dup_cert['transaction_no'].'|'.$trn_date.'|'.$dup_cert['transaction_no'].'|'.$INSTRUMENT_TYPE."\n";
		
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
		$declaration_zip_flg = 0;
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
			//$yesterday = '2017-09-07';
			
			//$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0, 'is_renewal' => 1));
			
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

					$declarationimg = '';
					if(is_file("./uploads/declaration/".$reg_data['declaration']))
					{
						$declarationimg = MEM_FILE_RENEWAL_PATH.$reg_data['declaration'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Declaration does not exist  - ".$reg_data['declaration']." (".$reg_data['regnumber'].")\n");	
					}
					
					$qualification = '';
					switch($reg_data['qualification'])
					{
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

						// For Declaration images
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
				fwrite($fp1, "\n"."Total Declaration Added = ".$declaration_cnt."\n");
				
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
	
	/* Free Admit Card Cron */
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
			// get member exam application details for given date
			$select = 'a.id as mem_exam_id, a.examination_date';
			$cand_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'free_paid_flg'=>'F','pay_status'=>1),$select);
			if(count($cand_exam_data))
			{
				$admit_card_count = 0;
				$admit_card_sub_count = 0;
				foreach($cand_exam_data as $exam)
				{
					$mem_exam_id = $exam['mem_exam_id'];
					
					// get admit card details for this member by mem_exam_id
					$this->db->where('remark', 1);
					$admit_card_details_arr = $this->Master_model->getRecords('admit_card_details',array('mem_exam_id' => $mem_exam_id,'free_paid_flg'=>'F'));
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
							
							// code to get actual exam period for exam application, added by Bhagwan Sahane, on 28-09-2017
							$exam_period = '';
							$exam_period = $admit_card_data['exm_prd'];
							$exam_code = '';
							$exam_code = $admit_card_data['exm_cd'];
							$transaction_no = '0000000000000';
							
//EXM_CD|EXM_PRD|MEMBER_NO|CTR_CD|CTR_NAM|SUB_CD|SUB_DSC|VENUE_CD|VENUE_ADDR1|VENUE_ADDR2|VENUE_ADDR3|VENUE_ADDR4|VENUE_ADDR5|VENUE_PINCODE|EXAM_SEAT_NO|EXAM_PASSWORD|EXAM_DATE|EXAM_TIME|EXAM_MODE(Online/Offline)| EXAM_MEDIUM|SCRIBE_FLG(Y/N)|VENDOR_CODE(1/3)|TRN_DATE|VENUE_NAME|TRANSACTION_NO|FREE_PAID_FLG(F/P)
							
							$data .= ''.$exam_code.'|'.$exam_period.'|'.$admit_card_data['mem_mem_no'].'|'.$admit_card_data['center_code'].'|'.$admit_card_data['center_name'].'|'.$admit_card_data['sub_cd'].'|'.$admit_card_data['sub_dsc'].'|'.$admit_card_data['venueid'].'|'.$venueadd1.'|'.$venueadd2.'|'.$venueadd3.'|'.$admit_card_data['venueadd4'].'|'.$admit_card_data['venueadd5'].'|'.$admit_card_data['venpin'].'|'.$admit_card_data['seat_identification'].'|'.$admit_card_data['pwd'].'|'.$exam_date.'|'.$admit_card_data['time'].'|'.$admit_card_data['mode'].'|'.$admit_card_data['m_1'].'|'.$admit_card_data['scribe_flag'].'|'.$admit_card_data['vendor_code'].'|'.$trn_date.'|'.$venue_name.'|'.$transaction_no.'|'.$admit_card_data['free_paid_flg']."\n";
							
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
		//	$yesterday = '2022-12-10';
			$ref_id = array(7553144,7553085); 
			// get member exam application details for given date
			$select = 'a.id as mem_exam_id, a.examination_date,b.transaction_no';
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->where_in('a.id' , $ref_id);
			$cand_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'pay_status'=>1),$select);
			//' DATE(a.created_on)'=>$yesterday,
			
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
							}else if($admit_card_data['exm_cd'] == 2027)
							{
								$exam_code = 1017;
							}
							else{
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
			
			// get BankQuest registration details for given date
			$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
			$this->db->join('payment_transaction b','b.ref_id=c.bv_id','LEFT');
			$bankquest_data = $this->Master_model->getRecords('bank_vision c',array(' DATE(created_on)' => $yesterday,'pay_type' => 6,'pay_status' => 1,'status' => '1'),$select);
			
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
			//$yesterday = '2017-08-31';
			
			// get Vision registration details for given date
			$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
			$this->db->join('payment_transaction b','b.ref_id=c.vision_id','LEFT');
			$vision_data = $this->Master_model->getRecords('iibf_vision c',array(' DATE(created_on)' => $yesterday,'pay_type' => 7,'pay_status' => 1,'status' => '1'),$select);
			
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
			//$yesterday = '2017-08-31';
			
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
			//$yesterday = '2017-08-17';
			$ref_id = array(1459,1461);
			// get CPD registration details for given date
			//$select = 'c.*';
			$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
			//$this->db->where_in('payment_transaction b',$ref_id);
			//$this->db->join('payment_transaction b',$id);
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
			//$yesterday = '2022-08-11';
			$id = array(25962);
			
			// get Blended registration details for given date
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
						//' DATE(createdon)' => $yesterday,
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
	public function bulk_exam_bk()
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
			//$yesterday = '2018-02-12';
			
			// get payment transaction for given date // DATE(date) = '".$yesterday."' OR 
			$this->db->where("(DATE(updated_date) = '".$yesterday."') AND status = 1");
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
							if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == $this->config->item('examCodeCaiibElective68') || $exam_code == $this->config->item('examCodeCaiibElective69') || $exam_code == $this->config->item('examCodeCaiibElective70') || $exam_code == $this->config->item('examCodeCaiibElective71')71 || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) 
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
			$yesterday = '2022-12-10';
			
			// get payment transaction for given date // DATE(date) = '".$yesterday."' OR 
			$this->db->where("(DATE(updated_date) = '".$yesterday."') AND status = 1");
			//$this->db->where("status = 1");
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
					$this->db->join('admit_card_details c','a.memexamid = c.mem_exam_id','LEFT');
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
							}elseif ($exam['exam_code'] == 2010){
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
							if($exam['exam_date'] != '0000-00-00' && $exam['exam_date'] != '')
							{
								$exam_date = date('d-M-Y',strtotime($exam['exam_date']));
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
	
	/* DRA Admit Card Cron - 25-09-2020 */
	public function dra_admit_card()
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
		$this->log_model->cronlog("DRA Exam Admit Card Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			$file = "dra_admit_card_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** DRA Exam Admit Card Details Cron Start - ".$start_time." ********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2020-09-23';
			$this->db->where("( DATE(updated_date) = '".$yesterday."') AND status = 1");
			$dra_payment = $this->Master_model->getRecords('dra_payment_transaction');
			
			if(count($dra_payment))
			{
				$data = '';
				$i = 1;
				$exam_cnt = 0;
				$admit_card_count = 0;
				$admit_card_sub_count = 0;
							
				foreach($dra_payment as $payment)
				{
					$this->db->join('dra_member_exam b','a.memexamid = b.id','LEFT');
					$cand_exam_data = $this->Master_model->getRecords('dra_member_payment_transaction a',array('ptid'=>$payment['id']));
					if(count($cand_exam_data))
					{
						foreach($cand_exam_data as $exam)
						{
							$mem_exam_id = $exam['memexamid'];
							$this->db->where('remark', 1);
							$admit_card_details_arr=$this->Master_model->getRecords('dra_admit_card_details',array('mem_exam_id' => $mem_exam_id));
							
							if(count($admit_card_details_arr))
							{
								foreach($admit_card_details_arr as $admit_card_data)
								{
									$data = '';
									
									$exam_date = date('d-M-y',strtotime($admit_card_data['exam_date']));
									//$trn_date = date('d-M-y',strtotime($admit_card_data['created_on']));
									$trn_date = date('d-M-y',strtotime($payment['updated_date']));
									
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
									
									$exam_period = $admit_card_data['exm_prd'];
									$exam_code = $admit_card_data['exm_cd'];
									
									//EXM_CD|EXM_PRD|MEMBER_NO|CTR_CD|CTR_NAM|SUB_CD|SUB_DSC|VENUE_CD|VENUE_ADDR1|VENUE_ADDR2|VENUE_ADDR3|VENUE_ADDR4|VENUE_ADDR5|VENUE_PINCODE|EXAM_SEAT_NO|EXAM_PASSWORD|EXAM_DATE|EXAM_TIME|EXAM_MODE(Online/Offline)| EXAM_MEDIUM|SCRIBE_FLG(Y/N)|VENDOR_CODE(1/3)|TRN_DATE|
									
									$data .= ''.$exam_code.'|'.$exam_period.'|'.$admit_card_data['mem_mem_no'].'|'.$admit_card_data['center_code'].'|'.$admit_card_data['center_name'].'|'.$admit_card_data['sub_cd'].'|'.$admit_card_data['sub_dsc'].'|'.$admit_card_data['venueid'].'|'.$venueadd1.'|'.$venueadd2.'|'.$venueadd3.'|'.$admit_card_data['venueadd4'].'|'.$admit_card_data['venueadd5'].'|'.$admit_card_data['venpin'].'|'.$admit_card_data['seat_identification'].'|'.$admit_card_data['pwd'].'|'.$exam_date.'|'.$admit_card_data['time'].'|'.$admit_card_data['mode'].'|'.$admit_card_data['m_1'].'|'.$admit_card_data['scribe_flag'].'|'.$admit_card_data['vendor_code'].'|'.$trn_date.'|'.$admit_card_data['free_paid_flg']."\n";
									
									$admit_card_sub_count++;
									
									$file_w_flg = fwrite($fp, $data);
								}
								
								if($file_w_flg)
								{
									$success[] = "Dra Exam Admit Card Details File Generated Successfully. ";
								}
								else
								{
									$error[] = "Error While Generating Dra Exam Admit Card Details File.";
								}
							}
							
							$admit_card_count++;
						}
					}
				}
				
				fwrite($fp1, "\n"."Total Dra Exam Admit Card Details Added = ".$admit_card_count."\n");
				fwrite($fp1, "\n"."Total Dra Exam Admit Card Subject Details Added = ".$admit_card_sub_count."\n");
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
			$this->log_model->cronlog("Dra Exam Admit Card Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Dra Exam Admit Card Details Cron End ".$end_time." **********"."\n");
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
			$yesterday = '2022-12-10';
			//DATE(date) = '".$yesterday."' OR
			$this->db->where("( DATE(updated_date) = '".$yesterday."') AND status = 1");
			$bulk_payment = $this->Master_model->getRecords('bulk_payment_transaction');
			
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
			$yesterday ='2022-12-10'; 
			//$ref_id = array(6992423,6989708,6991182);
			$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.scribe_flag';
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			//$this->db->where_in('a.id' , $ref_id);
			$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'pay_type'=>18,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1),$select);
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
		
		$cron_file_dir = "./uploads/cronfiles/";
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
    
    $cron_file_dir = "./uploads/cronfiles/";
    
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
      //$members = array('510101898','510415515');
      //$this->db->where_in('c.regnumber',$members);
      //$this->db->where('DATE(a.created_on) >=', '2020-05-29');
      //$this->db->where('DATE(a.created_on) <=', '2020-06-03');
      
      $exam_code_arry = array('1002','1010','1011','1012','1013','1014','1019','1020','2027');
      $select='c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,c.scannedphoto,c.scannedsignaturephoto,c.idproofphoto,c.image_path,c.reg_no, a.exam_code, ac.exam_date';
      $this->db->where_in('a.exam_code',$exam_code_arry);
      $this->db->join('member_registration c', 'a.regnumber = c.regnumber', 'LEFT');
      $this->db->join('admit_card_details ac', 'ac.mem_exam_id = a.id', 'LEFT');
      $new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
			'c.isactive' => '1',
			'c.isdeleted' => 0,
			'a.pay_status' => 1,
      		'ac.remark' => 1,
			'DATE(a.created_on)' => $yesterday
      ), $select);
      //echo "<br> Qry : ".$this->db->last_query();
      
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
  
  public function NSEIT_member_sagar()
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
      $members = array(7232642,7060477,510296157,400026081,500201132,3650143,510034682,801141461,7527943,500214774,510061398,4549571,7030100,510141040,500034158,510020677,510302907,500092558,500093603,510355441,200087616,510058224,500165074,500110540,7526991,510169776,801673415,510148941,6702987,801673522,500039017,510150628,510109552,500049190,510052664,510053454,7283285,845115338,510357869,200037221,4974441,510205726,510249159,510353806,801557608,7447913,801674070,801674088,500182301,500180891,510416044,510279623,510224071,500004827,500087217,500185035,510184665,7553602,500200618);
      //$this->db->where_in('c.regnumber',$members);
      //$this->db->where('DATE(a.created_on) >=', '2020-05-29');
      //$this->db->where('DATE(a.created_on) <=', '2020-06-03');
      
      //$exam_code_arry = array('1002','1010','1011','1012','1013','1014');
      $select='c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,c.scannedphoto,c.scannedsignaturephoto,c.idproofphoto,c.image_path,c.reg_no, a.exam_code, ac.exam_date';
      //$this->db->where_in('a.exam_code',$exam_code_arry);
	  $this->db->where_in('c.regnumber',$members);
      $this->db->join('member_registration c', 'a.regnumber = c.regnumber', 'LEFT');
      $this->db->join('admit_card_details ac', 'ac.mem_exam_id = a.id', 'LEFT');
      $new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
			'c.isactive' => '1',
			'c.isdeleted' => 0,
			'a.pay_status' => 1,
      		'ac.remark' => 1
      ), $select);
      //echo "<br> Qry : ".$this->db->last_query();
      //,'DATE(a.created_on) >= ' => '2021-05-01'
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
  
	
  	public function Csc_member_data_chaitali()
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
      
      $file = "iibf_CSC_mem_details_".$current_date.".txt";
      $fp = fopen($cron_file_path.'/'.$file, 'w');
      
      $file1 = "logs_NSEIT_".$current_date.".txt";
      $fp1 = fopen($cron_file_path.'/'.$file1, 'a');
      
      fwrite($fp1, "\n"."************ Vendor NSEIT Member Details Cron End ".$start_time." *************"."\n");
      
      $yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ($current_date) ));
      
      $yesterday = '2020-05-02';
      $members = array(500087801,500093046);
      $this->db->where_in('c.regnumber',$members);
     // $this->db->where('DATE(a.created_on) >=', '2021-05-01');
     // $this->db->where('DATE(a.created_on) <=', '2021-05-13');
      
     // $exam_code_arry = array('1002','1010','1011','1012','1013','1014');
      $exam_code = array('1003','1004','1005','1006','1007','1008','1009');
	  $select='c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,c.scannedphoto,c.scannedsignaturephoto,c.idproofphoto,c.image_path,c.reg_no, a.exam_code, ac.exam_date';
      $this->db->where_in('a.exam_code',$exam_code); 
      $this->db->join('member_registration c', 'a.regnumber = c.regnumber', 'LEFT');
      $this->db->join('admit_card_details ac', 'ac.mem_exam_id = a.id', 'LEFT');
      $new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
			'c.isactive' => '1',
			'c.isdeleted' => 0,
			'a.pay_status' => 1,
      		'ac.remark' => 1
			
      ), $select);
	  //'DATE(a.created_on) = ' => '2021-05-12'
      //echo "<br> Qry : ".$this->db->last_query(); die;
      
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
	    $cron_file_dir = "./uploads/cronfiles/";
	    
	    $result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
	    $desc = json_encode($result);
	    $this->log_model->cronlog("cscvendor Details Cron Execution Start", $desc);
	    
	    if(!file_exists($cron_file_dir.$current_date)){
	      $parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
	    }
	    
	    if(file_exists($cron_file_dir.$current_date))
	    {
	      $cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
	      
	      $file = "iibf_cscvendor_mem_details_".$current_date.".txt";
	      $fp = fopen($cron_file_path.'/'.$file, 'w');
	      $file1 = "logs_cscvendor_".$current_date.".txt";
	      $fp1 = fopen($cron_file_path.'/'.$file1, 'a');
	      fwrite($fp1, "\n"."***** Vendor cscvendor Member Details Cron Start ".$start_time." *****"."\n");
	      
	      $yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ($current_date) ));
	      
	      //$yesterday = '2020-06-17';
	      //$members = array('510101898','510415515');
	      //$this->db->where_in('c.regnumber',$members);
	      //$this->db->where('DATE(a.created_on) >=', '2020-05-29');
	      //$this->db->where('DATE(a.created_on) <=', '2020-06-03');
	      
	      $exam_code_arry = array('1002','1003','1004','1005','1006','1007','1008','1009');
	      $select='c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,c.scannedphoto,c.scannedsignaturephoto,c.idproofphoto,c.image_path,c.reg_no, a.exam_code, ac.exam_date';
	      $this->db->where_in('a.exam_code',$exam_code_arry);		
	      $this->db->join('member_registration c', 'a.regnumber = c.regnumber', 'LEFT');
	      $this->db->join('admit_card_details ac', 'ac.mem_exam_id = a.id', 'LEFT');
	      $new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
				'c.isactive' => '1',
				'c.isdeleted' => 0,
				'a.pay_status' => 1,
	      		'ac.remark' => 1,
				'DATE(a.created_on)' => $yesterday
	      ), $select);
	      //echo "<br> Qry : ".$this->db->last_query();
	      
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
	          if(is_file("./uploads/photograph/".$reg_data['scannedphoto'])){
	            $photo 	= MEM_FILE_PATH.$reg_data['scannedphoto'];	
	          }
	          elseif($image_path != '' && is_file("./uploads".$image_path."photo/p_".$reg_data['reg_no'].".jpg")){
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
	          else{
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
	          if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto'])){
	            $signature 	= MEM_FILE_PATH.$reg_data['scannedsignaturephoto'];
	          }
	          elseif($image_path != '' && is_file("./uploads".$image_path."signature/s_".$reg_data['reg_no'].".jpg")){
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
	          else{
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
	          if(is_file("./uploads/idproof/".$reg_data['idproofphoto'])){
	            $idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
	          }
	          elseif($image_path != '' && is_file("./uploads".$image_path."idproof/pr_".$reg_data['reg_no'].".jpg")){	
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
	          else{
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
	          if($reg_data['exam_code'] == '1002' && ($reg_data['exam_date']  == '2020-08-02' || $reg_data['exam_date']  == '2020-08-08' || $reg_data['exam_date']  == '2020-08-09'))
	          {
	            $include_zip_flag = 1;
	          }
	          elseif($reg_data['exam_code'] != '1002')
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
	              
	              if(is_file("./uploads/photograph/".$reg_data['scannedphoto'])){
	                $image = "./uploads/photograph/".$reg_data['scannedphoto']; 
	                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
	                imagejpeg($imgdata, $directory."/".$reg_data['scannedphoto']);
	                $photo_to_add = $directory."/".$reg_data['scannedphoto'];
	              }
	              elseif($image_path != ''){ 
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
	              else{
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
	                } else { $photo_cnt++; }
	              }
	            }
	            
	            // For signature images
	            if($signature)
	            {
	              $max_width = "140";
	              $max_height = "100";
	              if(is_file("./uploads/scansignature/".$reg_data['scannedsignaturephoto'])){
	                $image = "./uploads/scansignature/".$reg_data['scannedsignaturephoto'];
	                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
	                imagejpeg($imgdata, $directory."/".$reg_data['scannedsignaturephoto']);
	                
	                $sign_to_add = $directory."/".$reg_data['scannedsignaturephoto'];
	              }
	              elseif($image_path != ''){
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
	              else{
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
	                else { $sign_cnt++; }
	              }
	            }
	            
	            // For ID proof images
	            if($idproofimg)
	            {
	              $max_width = "800";
	              $max_height = "500";
	              if(is_file("./uploads/idproof/".$reg_data['idproofphoto'])){
	                $image = "./uploads/idproof/".$reg_data['idproofphoto'];
	                $imgdata = $this->resize_image_max($image,$max_width,$max_height);
	                imagejpeg($imgdata, $directory."/".$reg_data['idproofphoto']);
	                
	                $proof_to_add = $directory."/".$reg_data['idproofphoto'];
	              }
	              elseif($image_path != ''){
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
	              else{
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
	                else { $idproof_cnt++; }
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
	      fwrite($fp1, "\n"."***** Vendor cscvendor Member Details Cron End ".$end_time." *****"."\n");
	      fclose($fp1);
	    }
  	}
	
  /* Only for Free CSC Vendor : 30-09-2020 */
	public function free_csc_member()
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
		
		$cron_file_dir = "./uploads/CSC_FREE_APP/";
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
			
			
			
			$exam_code = array('991');
			$select    = 'a.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,c.scannedphoto,c.scannedsignaturephoto,c.idproofphoto';
            $this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
            $this->db->where_in('a.exam_code', $exam_code);
            $new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
                'c.isactive' => '1',
                'c.isdeleted' => 0,
                'a.pay_status' => 1,
				'a.free_paid_flg' => 'F',
                'DATE(a.created_on)' => $yesterday
            ), $select);
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
	
	/* dra_member_vendor Cron */
	// admin/Crontest/dra_member_vendor
	// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron dra_member_vendor
	public function dra_member_vendor()
	{
		ini_set("memory_limit", "-1");
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$Zip_flg = 0;
		$file_w_flg = 0;
		$photo_zip_flg = 0;
		$sign_zip_flg = 0;
		$idproof_zip_flg = 0;
		$success = array();
		$error = array();
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");			
		$cron_file_dir = "./uploads/dra_csv/";
		
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
			
			$this->db->select("namesub, firstname, middlename, lastname, regnumber, scannedphoto, scannedsignaturephoto, idproofphoto, idproof, DATE(a.updated_date) updated_date, d.image_path, d.registration_no");
			$this->db->join('dra_member_payment_transaction b','a.id = b.ptid','LEFT');
			$this->db->join('dra_member_exam c','b.memexamid = c.id','LEFT');
			$this->db->join('dra_members d','d.regid = c.regid','LEFT');
			$this->db->where("DATE(a.updated_date) = '".$yesterday."'");
			$new_dra_reg = $this->Master_model->getRecords('dra_payment_transaction a');
			
			//echo $this->db->last_query();
			//exit;
			
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
				
				foreach($new_dra_reg as $dra)
				{
					$photo_file = '';
					$sign_file = '';
					$idproof_file = '';
					if($dir_flg)
					{
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
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg)
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
					
					$data .= ''.$dra['regnumber'].'|'.$dra['namesub'].'|'.$dra['firstname'].'|'.$dra['middlename'].'|'.$dra['lastname']."\n";
					
					$i++;
					$mem_cnt++;
				}
				
				fwrite($fp1, "\n"."Total New DRA Members Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total Photographs Added = ".$photo_cnt."\n");
				fwrite($fp1, "\n"."Total Signatures Added = ".$sign_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				
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

	/*SCRIBE REGISTRATION CRON : POOJA MANE : 16-11-2022*/
	public function scribe_old()
	{
		//echo "string";die;
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$photo_zip_flg = 0;
		$sign_zip_flg = 0;
		$idproof_zip_flg = 0;
		$declaration_zip_flg = 0;
		$vis_imp_cert_img_zip_flg = 0;
		$orth_han_cert_img_zip_flg = 0;
		$cer_palsy_cert_img_zip_flg = 0;
		
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		//echo $start_time;die;
		$current_date = date("Ymd");
		//$current_date = '20200105';
		$cron_file_dir = "./uploads/cronFilesCustom/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result); 

		$this->log_model->cronlog("New Scribe Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "iibf_scribe_reg_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** New Scribe Details Cron Start - ".$start_time." ********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//echo DATE($yesterday);
			//$yesterday = '2022-08-11';
			//echo'SQL1>>'.$this->db->last_query().'<br><br>'; //die;
			//$member_no = array(802104106,802104167,802104180,802104217,802104273,802104286,802104301); 
			//$excode = array('528','529','991');
			//$this->db->where_in('a.regnumber', $member_no);
			//$this->db->where_not_in('a.excode', $excode); 
			//$this->db->where('a.created_on', $yesterday); 
			$new_mem_reg = $this->Master_model->getRecords('scribe_registration a',array(' DATE(created_on)'=>$yesterday,'remark'=>'1'));	
			//echo'SQL2>>'.$this->db->last_query().'<br>'; //die;
			
			
			if(count($new_mem_reg))
			{
				$dirname = "scribe_image_".$current_date;
				//echo $dirname.'<br>';//die;
				$directory = $cron_file_path.'/'.$dirname;
				//echo $directory.'<br>';//die;
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
				//echo 'newdiectory'.$directory.'<br><br>';//die;
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				
				$i = 1;
				$mem_cnt = 0;
				/*$photo_cnt = 0;
				$sign_cnt = 0;*/
				$idproof_cnt = 0;
				$declaration_cnt = 0;
				$vis_imp_cert_img_cnt = $orth_han_cert_img_cnt = $cer_palsy_cert_img_cnt = 0;
				
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					//echo "<pre>";
					//print_r($reg_data).'<br>';
					$scribe_uid = $reg_data['scribe_uid'];
					$regnumber = $reg_data['regnumber'];
					$idproofimg = '';
					if(is_file("./uploads/scribe/idproof/".$reg_data['idproofphoto']))
					{
						$idproofimg = MEM_FILE_PATH.$reg_data['idproofphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					
					$declarationimg = '';
					if(is_file("./uploads/scribe/declaration/".$reg_data['declaration_img']))
					{
						$declarationimg = MEM_FILE_PATH.$reg_data['declaration_img'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Declaration does not exist  - ".$reg_data['declaration_img']." (".$reg_data['regnumber'].")\n");	
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
										
					}

					if(strlen($reg_data['exam_name']) > 30)
					{	$exam_name = substr($reg_data['exam_name'],0,29);	}
					else
					{	$exam_name = $reg_data['exam_name'];	}
					
				
					if(strlen($reg_data['subject_name']) > 30)
					{	$subject_name = substr($reg_data['subject_name'],0,29);	}
					else
					{	$subject_name = $reg_data['subject_name'];	}
					
					
					if(strlen($reg_data['firstname']) > 30)
					{	$firstname = substr($reg_data['firstname'],0,29);	}
					else
					{	$firstname = $reg_data['firstname'];	}
					
					
					if(strlen($reg_data['lastname']) > 30)
					{	$lastname = substr($reg_data['lastname'],0,29);	}
					else
					{	$lastname = $reg_data['lastname'];	}
				
					
					if(strlen($reg_data['center_name']) > 30)
					{	$center_name = substr($reg_data['center_name'],0,29);	}
					else
					{	$center_name = $reg_data['center_name'];	}
				
					
					if(strlen($reg_data['name_of_scribe']) > 30)
					{	$name_of_scribe = substr($reg_data['name_of_scribe'],0,29);	}
					else
					{	$name_of_scribe = $reg_data['name_of_scribe'];	}
				
					
					// code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					if(strlen($reg_data['mobile_scribe']) > 30)
					{	$mobile_scribe = substr($reg_data['mobile_scribe'],0,29);	}
					else
					{	$mobile_scribe = $reg_data['mobile_scribe'];	}
				
					
					if(strlen($reg_data['emp_details_scribe']) > 30)
					{	$emp_details_scribe = substr($reg_data['emp_details_scribe'],0,29);	}
					else
					{	$emp_details_scribe = $reg_data['emp_details_scribe'];	}
				
					
					if(strlen($reg_data['description']) > 30)
					{	$description = substr($reg_data['description'],0,29);	}
					else
					{	$description = $reg_data['description'];	}
					
					if(strlen($reg_data['photoid_no']) > 30)
					{	$photoid_no = substr($reg_data['photoid_no'],0,29);	}
					else
					{	$photoid_no = $reg_data['photoid_no'];	}
					
					if(strlen($reg_data['exam_date']) > 30)
					{	$exam_date = substr($reg_data['exam_date'],0,29);	}
					else
					{	$exam_date = $reg_data['exam_date'];	}
					
					if(strlen($reg_data['scribe_approve']) > 30)
					{	$scribe_approve = substr($reg_data['scribe_approve'],0,29);	}
					else
					{	$scribe_approve = $reg_data['scribe_approve'];	}

					if(strlen($reg_data['special_assistance']) > 30)
					{	$special_assistance = substr($reg_data['special_assistance'],0,29);	}
					else
					{	$special_assistance = $reg_data['special_assistance'];	}

					if(strlen($reg_data['extra_time']) > 30)
					{	$extra_time = substr($reg_data['extra_time'],0,29);	}
					else
					{	$extra_time = $reg_data['extra_time'];	}
					// eof code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					
					/*$optnletter = "Y";
					if($reg_data['optnletter'] == "optnl")
					{	$optnletter = "Y";	}
					else
					{	$optnletter = $reg_data['optnletter'];	}*/
					
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
							
							if(is_file("./uploads/scribe/disability/".$reg_data['vis_imp_cert_img'])){
							$vis_imp_cert_img 	= MEM_FILE_PATH.$reg_data['vis_imp_cert_img'];
							}else{
								fwrite($fp1, "**ERROR** - Visually impaired certificate does not exist  - ".$reg_data['vis_imp_cert_img']." (".$reg_data['regnumber'].")\n");	
							}

						}
						$orth_han_cert_img = '';
						if($orthopedically_handicapped == 'Y')
						{
							if(is_file("./uploads/scribe/disability/".$reg_data['orth_han_cert_img'])){
							$orth_han_cert_img 	= MEM_FILE_PATH.$reg_data['orth_han_cert_img'];
							}else{
								fwrite($fp1, "**ERROR** - Orthopedically handicapped certificate does not exist  - ".$reg_data['orth_han_cert_img']." (".$reg_data['regnumber'].")\n");	
							}
						}
						$cer_palsy_cert_img = '';
						if($cerebral_palsy == 'Y')
						{
							if(is_file("./uploads/scribe/disability/".$reg_data['cer_palsy_cert_img'])){
							$cer_palsy_cert_img 	= MEM_FILE_PATH.$reg_data['cer_palsy_cert_img'];
							}else{
								fwrite($fp1, "**ERROR** - Cerebral palsy certificate does not exist  - ".$reg_data['cer_palsy_cert_img']." (".$reg_data['regnumber'].")\n");	
							}
						}
					}
					$specified_qualification_name = '';
					$specified_qualification_code = $reg_data['specify_qualification'];
					$specified_qualification_details = $this->Master_model->getRecords('qualification',array('qid'=>$specified_qualification_code),'name');

					$data .= ''.$reg_data['scribe_uid'].'|'.$reg_data['exam_code'].'|'.$reg_data['exam_name'].'|'.$reg_data['subject_code'].'|'.$reg_data['subject_name'].'|'.$reg_data['regnumber'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname'].'|'.$reg_data['email'].'|'.$reg_data['mobile'].'|'.$reg_data['center_name'].'|'.$reg_data['center_code'].'|'.$reg_data['name_of_scribe'].'|'.$reg_data['mobile_scribe'].'|'.$qualification.'|'.$reg_data['specify_qualification'].'|'.$reg_data['emp_details_scribe'].'|'.$reg_data['description'].'|'.$visually_impaired.'|'.$vis_imp_cert_img.'|'.$orthopedically_handicapped.'|'.$orth_han_cert_img.'|'.$cerebral_palsy.'|'.$cer_palsy_cert_img.'|'.$reg_data['exam_date'].'|'."\n";
					
					if($dir_flg)
					{
						/* Benchmark Code Start */
						// For Visually impaired certificate images
						if($vis_imp_cert_img)
						{
							$image = "./uploads/scribe/disability/".$reg_data['vis_imp_cert_img'];
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
							$image = "./uploads/scribe/disability/".$reg_data['orth_han_cert_img'];
							$max_width = "200";
							$max_height = "200";
							echo $image;die;
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
							$image = "./uploads/scribe/disability/".$reg_data['cer_palsy_cert_img'];
							$max_width = "200";
							$max_height = "200";
							echo $image;die;
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
						
						
					
						
						
						// For ID proof images
						if($idproofimg)
						{
							$image = "./uploads/scribe/idproof/".$reg_data['idproofphoto'];
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
							$image = "./uploads/scribe/declaration/".$reg_data['declaration_img'];
							$max_width = "800";
							$max_height = "500";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['declaration_img']);
							
							$declaration_to_add = $directory."/".$reg_data['declaration_img'];
							$new_declaration = substr($declaration_to_add,strrpos($declaration_to_add,'/') + 1);
							$declaration_zip_flg = $zip->addFile($declaration_to_add,$new_declaration);
							if(!$declaration_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Declaration not added to zip  - ".$reg_data['declaration_img']." (".$reg_data['regnumber'].")\n");	
							}
							else 
								$declaration_cnt++;
						}
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg || $declaration_zip_flg)
						{
							$success['zip'] = "New Scribe Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating New Scribe Images Zip";
						}
					}
					
					$i++;
					$mem_cnt++;
					
					//fwrite($fp1, "\n");
					
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg)
					{
						$success['file'] = "New Scribe Details File Generated Successfully. ";
					}
					else
					{
						$error['file'] = "Error While Generating New Scribe Details File.";
					}
				}
				
				fwrite($fp1, "\n"."Total New Scribes Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				fwrite($fp1, "\n"."Total Declaration Added = ".$declaration_cnt."\n");
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
			$this->log_model->cronlog("New Scribe Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** New Scribe Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
		echo "Executed Successfully for Yesterday ".$yesterday;
	}
	/*SCRIBE REGISTRATION CRON : POOJA MANE : 16-11-2022*/

	/*SCRIBE REGISTRATION CRON : POOJA MANE : 24-11-2022*/
	public function scribe()
	{
		//echo "string";die;
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$photo_zip_flg = 0;
		$sign_zip_flg = 0;
		$idproof_zip_flg = 0;
		$declaration_zip_flg = 0;
		$vis_imp_cert_img_zip_flg = 0;
		$orth_han_cert_img_zip_flg = 0;
		$cer_palsy_cert_img_zip_flg = 0;
		
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		//echo $start_time;die;
		$current_date = date("Ymd");
		//$current_date = '20200105';
		$cron_file_dir = "./uploads/cronFilesCustom/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result); 

		$this->log_model->cronlog("New Scribe Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date)){
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "iibf_scribe_reg_details_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** New Scribe Details Cron Start - ".$start_time." ********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday = '2022-11-24';
			$today = date('Y-m-d'); 
			$new_mem_reg = $this->Master_model->getRecords('scribe_registration a',array(' DATE(modified_on)'=>$yesterday,'scribe_approve'=>'1'));	
			//echo'SQL2>>'.$this->db->last_query().'<br>'; die;
			
			
			if(count($new_mem_reg))
			{
				$dirname = "scribe_image_".$current_date;
				//echo $dirname.'<br>';//die;
				$directory = $cron_file_path.'/'.$dirname;
				//echo $directory.'<br>';//die;
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
				//echo 'newdiectory'.$directory.'<br><br>';//die;
				// Create a zip of images folder
				$zip = new ZipArchive;
				$zip->open($directory.'.zip', ZipArchive::CREATE);
				
				$i = 1;
				$mem_cnt = 0;
				/*$photo_cnt = 0;
				$sign_cnt = 0;*/
				$idproof_cnt = 0;
				$declaration_cnt = 0;
				$vis_imp_cert_img_cnt = $orth_han_cert_img_cnt = $cer_palsy_cert_img_cnt = 0;
				
				foreach($new_mem_reg as $reg_data)
				{
					$data = '';
					//echo "<pre>";
					//print_r($reg_data).'<br>';
					$scribe_uid = $reg_data['scribe_uid'];
					$regnumber = $reg_data['regnumber'];
					$idproofimg = '';
					if(is_file("./uploads/scribe/idproof/".$reg_data['idproofphoto']))
					{
						$idproofimg = SCE_FILE_PATH.$reg_data['idproofphoto'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - ID Proof does not exist  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
					}
					
					
					$declarationimg = '';
					if(is_file("./uploads/scribe/declaration/".$reg_data['declaration_img']))
					{
						$declarationimg = SCE_FILE_PATH.$reg_data['declaration_img'];
					}
					else
					{
						fwrite($fp1, "**ERROR** - Declaration does not exist  - ".$reg_data['declaration_img']." (".$reg_data['regnumber'].")\n");	
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
										
					}

					if(strlen($reg_data['exam_name']) > 30)
					{	$exam_name = substr($reg_data['exam_name'],0,29);	}
					else
					{	$exam_name = $reg_data['exam_name'];	}
					
				
					if(strlen($reg_data['subject_name']) > 30)
					{	$subject_name = substr($reg_data['subject_name'],0,29);	}
					else
					{	$subject_name = $reg_data['subject_name'];	}
					
					
					if(strlen($reg_data['center_name']) > 30)
					{	$center_name = substr($reg_data['center_name'],0,29);	}
					else
					{	$center_name = $reg_data['center_name'];	}
				
					
					if(strlen($reg_data['name_of_scribe']) > 30)
					{	$name_of_scribe = substr($reg_data['name_of_scribe'],0,29);	}
					else
					{	$name_of_scribe = $reg_data['name_of_scribe'];	}
				
					
					// code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					if(strlen($reg_data['mobile_scribe']) > 30)
					{	$mobile_scribe = substr($reg_data['mobile_scribe'],0,29);	}
					else
					{	$mobile_scribe = $reg_data['mobile_scribe'];	}
				
					
					if(strlen($reg_data['emp_details_scribe']) > 30)
					{	$emp_details_scribe = substr($reg_data['emp_details_scribe'],0,29);	}
					else
					{	$emp_details_scribe = $reg_data['emp_details_scribe'];	}
				
					
					if(strlen($reg_data['description']) > 30)
					{	$description = substr($reg_data['description'],0,29);	}
					else
					{	$description = $reg_data['description'];	}
					
					if(strlen($reg_data['photoid_no']) > 30)
					{	$photoid_no = substr($reg_data['photoid_no'],0,29);	}
					else
					{	$photoid_no = $reg_data['photoid_no'];	}
					
					if(empty($reg_data['special_assistance']))
					{	$special_assistance = 'N';	}
					else
					{	$special_assistance = 'Y';	}

					if(empty($reg_data['extra_time']))
					{	$extra_time = 'N';	}
					else
					{	$extra_time = 'Y';	}
					// eof code for permanent address fields added by Bhagwan Sahane, on 07-07-2017
					
				
					/*$specified_qualification_name = '';
					$specified_qualification_code = $reg_data['specify_qualification'];
					$specified_qualification_details = $this->Master_model->getRecords('qualification',array('qid'=>$specified_qualification_code),'name');*/


					$data .= ''.$reg_data['scribe_uid'].'|'.$reg_data['exam_code'].'|'.$reg_data['exam_name'].'|'.$reg_data['subject_code'].'|'.$reg_data['subject_name'].'|'.$reg_data['regnumber'].'|'.$reg_data['center_name'].'|'.$reg_data['center_code'].'|'.$reg_data['name_of_scribe'].'|'.$reg_data['mobile_scribe'].'|'.$qualification.'|'.$reg_data['emp_details_scribe'].'|'.$description.'|'.$reg_data['photoid_no'].'|'.$idproofimg.'|'.$declarationimg.'|'.$reg_data['exam_date'].'|'.$reg_data['modified_on'].'|'.$special_assistance.'|'.$extra_time."\n";
					 
					if($dir_flg)
					{
						
						// For ID proof images
						if($idproofimg)
						{
							$image = "./uploads/scribe/idproof/".$reg_data['idproofphoto'];
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
							$image = "./uploads/scribe/declaration/".$reg_data['declaration_img'];
							$max_width = "800";
							$max_height = "500";
							
							$imgdata = $this->resize_image_max($image,$max_width,$max_height);
							imagejpeg($imgdata, $directory."/".$reg_data['declaration_img']);
							
							$declaration_to_add = $directory."/".$reg_data['declaration_img'];
							$new_declaration = substr($declaration_to_add,strrpos($declaration_to_add,'/') + 1);
							$declaration_zip_flg = $zip->addFile($declaration_to_add,$new_declaration);
							if(!$declaration_zip_flg)
							{
								fwrite($fp1, "**ERROR** - Declaration not added to zip  - ".$reg_data['declaration_img']." (".$reg_data['regnumber'].")\n");	
							}
							else 
								$declaration_cnt++;
						}
						
						if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg || $declaration_zip_flg)
						{
							$success['zip'] = "New Scribe Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating New Scribe Images Zip";
						}
					}
					
					$i++;
					$mem_cnt++;
					
					//fwrite($fp1, "\n");
					
					$file_w_flg = fwrite($fp, $data);
					if($file_w_flg)
					{
						$success['file'] = "New Scribe Details File Generated Successfully. ";
					}
					else
					{
						$error['file'] = "Error While Generating New Scribe Details File.";
					}
				}
				
				fwrite($fp1, "\n"."Total New Scribes Added = ".$mem_cnt."\n");
				fwrite($fp1, "\n"."Total ID Proofs Added = ".$idproof_cnt."\n");
				fwrite($fp1, "\n"."Total Declaration Added = ".$declaration_cnt."\n");
				
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
			$this->log_model->cronlog("New Scribe Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** New Scribe Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
		echo "Executed Successfully for Yesterday ".$yesterday;
	}
	/*SCRIBE REGISTRATION CRON : POOJA MANE : 24-11-2022*/
}
