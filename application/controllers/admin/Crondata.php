<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Crondata extends CI_Controller {
	public $UserID;
			
	public function __construct(){
		parent::__construct();
		
		if($this->session->userdata('roleid')!=1){
			redirect('admin/Login');
		}
		
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		//$this->UserID=$this->session->id;
		
		$this->load->helper('pagination_helper'); 
		$this->load->library('pagination');
		$this->load->model('log_model');
		$this->load->model('Emailsending');
		
		define('MEM_FILE_PATH','/webonline/fromweb/images/newmem/');
		define('DRA_FILE_PATH','/webonline/fromweb/images/dra/');
		define('MEM_FILE_EDIT_PATH','/webonline/fromweb/images/edit/');
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}
	
	// By VSU : Fuction to fetch new member registration data and export it in TXT format
	public function member()
	{	
		ini_set("memory_limit", "-1");
		
		if(isset($_POST['generate_data']))
		{
			if(isset($_POST['whereCondition']) && $_POST['whereCondition']!='')
			{
				$whereCondition = $_POST['whereCondition'];
				//$whereCondition = 'AND regnumber IN (510299340) ';
				
				$dir_flg = 0;
				$parent_dir_flg = 0;
				$file_w_flg = 0;
				$photo_zip_flg = 0;
				$sign_zip_flg = 0;
				$idproof_zip_flg = 0;
				$success = array();
				$error = array();
				
				$date = date("Y-m-d");
				
				$start_time = date("Y-m-d H:i:s");
				$current_date = date("Ymd",strtotime($date));	
				$cron_file_dir = "./uploads/cronFilesCustom/";
				
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
					
					$file1 = "logs_".$current_date.".txt";
					$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
					fwrite($fp1, "IIBF New Member Details Cron Execution Started - ".$start_time."\n");
					
					$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ( $date) ));
					//$yesterday = date('Y-m-d');
					
					//$this->db->join('payment_transaction b','b.ref_id=a.regid AND b.member_regnumber=a.regnumber','LEFT');
					//$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'pay_type'=>1));
					
					//$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
					
					$SQL = "SELECT * FROM `member_registration` `a` WHERE `isactive` = '1' AND `isdeleted` =0 ";
					//AND regnumber IN (510313364,510313371,510313372,510313373)
					//$SQL = "SELECT * FROM `member_registration` `a` WHERE `isactive` = '1' AND `isdeleted` =0 AND DATE(createdon) = '".$yesterday."'";
					
					$SQL .= $whereCondition;
					
					$new_mem_qry = $this->db->query($SQL);
					$new_mem_reg = $new_mem_qry->result_array();
					//echo $this->db->last_query();
					$last_query = $this->db->last_query();
					$this->session->set_flashdata('last_query','"'.$last_query.'"');
					if(count($new_mem_reg))
					{
						/*echo "<pre>";
						print_r($new_mem_reg);*/
						
						//$data = '';
						
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
						$mem_cnt = 0;
						$photo_cnt = 0;
						$sign_cnt = 0;
						$idproof_cnt = 0;
						foreach($new_mem_reg as $reg_data)
						{
							$data = '';
							
							//echo "<br>".$reg_data['regnumber'];;
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
							
							$reg_date = date('Y-m-d',strtotime($reg_data['createdon']));
							
							if($reg_data['registrationtype']!='NM')
							{
								if($reg_data['registrationtype'] == 'DB')
								{
									$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pay_type'=>2,'member_regnumber'=>$reg_data['regnumber'],'status'=>1),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
									//'pg_flag'=>'IIBF_EXAM_DB',
								}
								else
								{
									$trans_details = $this->Master_model->getRecords('payment_transaction a',array('status'=>1,'pay_type'=>1,'ref_id'=>$reg_data['regid']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
									//'pg_flag'=>'iibfregn',
								}
							}
							else
							{
								$trans_details = $this->Master_model->getRecords('payment_transaction a',array('status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
								//'pg_flag'=>'IIBF_EXAM_REG',
							}
							//echo $this->db->last_query();
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
							else if($reg_data['optnletter'] != "")
							{	$optnletter = $reg_data['optnletter'];	}
							
							$data .= ''.$reg_data['regnumber'].'|'.$reg_data['registrationtype'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname'].'|'.$reg_data['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$reg_data['pincode'].'|'.$reg_data['state'].'|'.$address1_pr.'|'.$address2_pr.'|'.$address3_pr.'|'.$address4_pr.'|'.$district_pr.'|'.$city_pr.'|'.$reg_data['pincode_pr'].'|'.$reg_data['state_pr'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$reg_data['specify_qualification'].'|'.$reg_data['associatedinstitute'].'|'.$branch.'|'.$reg_data['designation'].'|'.$mem_doj.'|'.$reg_data['email'].'|'.$std_code.'|'.$reg_data['office_phone'].'|'.$reg_data['mobile'].'|'.$reg_data['idproof'].'|'.$reg_data['idNo'].'|'.$transaction_no.'|'.$transaction_date.'|'.$transaction_amt.'|'.$transaction_no.'|'.$optnletter.'|||'.$photo.'|'.$signature.'|'.$idproofimg.'|||||'.$reg_data['aadhar_card']."|\n";
							
							if($dir_flg)
							{
								//copy("./uploads/photograph/".$reg_data['scannedphoto'],$directory."/".$reg_data['scannedphoto']);
								//copy("./uploads/scansignature/".$reg_data['scannedsignaturephoto'],$directory."/".$reg_data['scannedsignaturephoto']);
								//copy("./uploads/idproof/".$reg_data['idproofphoto'],$directory."/".$reg_data['idproofphoto']);
								
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
							//fwrite($fp1, "\n".$reg_data['regnumber']."\n");
							
							//exit;
							
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
					
					$this->session->set_flashdata('success','Data generated successfully !!');
					redirect(base_url().'admin/crondata/member');
				}
			}
			else
			{
				$this->session->set_flashdata('error','Please Enter Additional WHERE Clause...');
				redirect(base_url().'admin/Report/datewise');
			}
		}
		$datares['breadcrumb'] = '';
		$datares['title'] = 'Generate Member Data';
		$this->load->view('admin/cron_data',$datares);
	}
	
	
	// By VSU : Fuction to fetch candidate exam application data and export in TXT format
	public function exam()
	{
		ini_set("memory_limit", "-1");
		
		if(isset($_POST['generate_data']))
		{
			if(isset($_POST['whereCondition']) && $_POST['whereCondition']!='')
			{
				$whereCondition = $_POST['whereCondition'];
				$dir_flg = 0;
				$parent_dir_flg = 0;
				$exam_file_flg = 0;
				$success = array();
				$error = array();
				
				$date = date("Y-m-d");
				
				$start_time = date("Y-m-d H:i:s");
				$current_date = date("Ymd",strtotime($date));		
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
					$file = $cron_file_dir.$current_date;
					if(!file_exists($cron_file_path.'/'.$file))
					{
						$file = "exam_cand_report_".$current_date.".txt";
					}
					else
					{
						$file = "exam_cand_report_".$current_date."_".date('His').".txt";	
					}
					
					$fp = fopen($cron_file_path.'/'.$file, 'w');
					
					$file1 = "logs_".$current_date.".txt";
					$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
					fwrite($fp1, "***************************** Candidate Exam Details Cron Execution Started - ".$start_time." ************************** \n");
					
					$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ( $date) ));
					//$yesterday = $date;
					
					//$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode';
					
					$SQL = "SELECT DISTINCT(b.transaction_no), `a`.`exam_code`, `a`.`exam_mode`, `a`.`exam_medium`, `a`.`exam_period`, `a`.`exam_center_code`, `a`.`exam_fee`, `a`.`examination_date`, `b`.`member_regnumber`, `b`.`member_exam_id`, `b`.`amount`, DATE_FORMAT(b.date, '%Y-%m-%d') date, `b`.`receipt_no`, `b`.`ref_id`, `b`.`status`, `c`.`regnumber`, `c`.`registrationtype`, `a`.`elected_sub_code`, `a`.`place_of_work`, `a`.`state_place_of_work`, `a`.`pin_code_place_of_work`, `c`.`editedon`, `c`.`branch`, `c`.`office`, `c`.`city`, `c`.`state`, `c`.`pincode`, `a`.`scribe_flag` FROM `member_exam` `a` LEFT JOIN `payment_transaction` `b` ON `b`.`ref_id`=`a`.`id` LEFT JOIN `member_registration` `c` ON `a`.`regnumber`=`c`.`regnumber` WHERE `pay_type` = 2 AND `status` = 1 AND `isactive` = '1' AND `isdeleted` =0 AND `pay_status` = 1 AND DATE(a.created_on) >= '2018-03-02' ";
					//$can_exam_data = $this->Master_model->getRecords('member_exam a',array(),$select,'',8000,1000);
					//AND `c`.`regnumber` IN (510042272, 510120489, 510250039, 6523313)
					
					$SQL .= $whereCondition;
					$SQL .= " ORDER BY `a`.`id` ASC ";
					$exam_qry = $this->db->query($SQL);
					$can_exam_data = $exam_qry->result_array();
					
					//echo"<br>".$this->db->last_query();
					//exit; 
					$last_query = $this->db->last_query();
					$this->session->set_flashdata('last_query','"'.$last_query.'"');
					
					//echo "<br> Count - ".count($can_exam_data)."<br>";
					if(count($can_exam_data))
					{
						
						/*echo "<pre>";
						print_r($can_exam_data);*/
						//$data = '';
						$i = 1;
						$exam_cnt = 0;
						foreach($can_exam_data as $exam)
						{
							$data = '';
							//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG
							
							//error_reporting(E_ALL);
							//ini_set('display_errors', 'On');
							
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
							//echo " regnumber - ".$exam['regnumber'].", ";
							//echo "syllabus_code - ".$syllabus_code;
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
							if($exam['exam_code'] != '' && $exam['exam_code'] != 0)
							{
								$ex_code = $this->master_model->getRecords('exam_activation_master',array('exam_code'=>$exam['exam_code']));
								if(count($ex_code))
								{
									if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0)
									{	$exam_code = $ex_code[0]['original_val'];	}
									else
									{	$exam_code = $exam['exam_code'];	}
								}
							}
							else{	$exam_code = $exam['exam_code'];	}
							
							$place_of_work = '';
							$pin_code_place_of_work = '';
							$state_place_of_work = '';
							$city = '';
							$branch = '';
							$branch_name = '';
							$state = '';
							$pincode = '';
							
							// **************************************************************
							//$exam_arr = array(21, 60, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72);
							//if($exam_code == 60 || $exam_code == 21) // For CAIIB & JAIIB
							//if(in_array($exam_code, $exam_arr)) // For CAIIB + Elective & JAIIB
							
							if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == $this->config->item('examCodeCaiibElective68') || $exam_code == $this->config->item('examCodeCaiibElective69') || $exam_code == $this->config->item('examCodeCaiibElective70') || $exam_code == $this->config->item('examCodeCaiibElective71') || $exam_code == 72) // For CAIIB & JAIIB
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
								
								// **************************************************************
								//if($exam_code == 60)
								//if(in_array($exam_code, $exam_arr))
								
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
								
								//$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no']."|\n";
								
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$exam['scribe_flag']."|\n";
								
								
							}
							else
							{
								//$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'||'.$branch_name.'|'.$pincode.'|'.$state.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no']."|\n";
								
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'||'.$branch_name.'|'.$pincode.'|'.$state.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$exam['scribe_flag']."|\n";
							}
							
							//echo $data."<br>";
							
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
					$this->log_model->cronlog("Candidate Exam Details Cron Execution End", $desc);
					
					fwrite($fp1, "\n"."***************************** Candidate Exam Details Cron Execution End ".$end_time." **************************"."\n");
					fclose($fp1);
					
					$this->session->set_flashdata('success','Data generated successfully !!');
					redirect(base_url().'admin/crondata/exam');
				}
			}
		}
		$datares['breadcrumb'] = '';
		$datares['title'] = 'Generate Exam Application Data';
		$this->load->view('admin/cron_data',$datares);
	}
	
	// By VSU : Fuction to fetch candidate exam application data and export in TXT format
	public function exam_elective()
	{
		ini_set("memory_limit", "-1");
		
		if(isset($_POST['generate_data']))
		{
			if(isset($_POST['whereCondition']) && $_POST['whereCondition']!='')
			{
				$whereCondition = $_POST['whereCondition'];
				$dir_flg = 0;
				$parent_dir_flg = 0;
				$exam_file_flg = 0;
				$success = array();
				$error = array();
				
				$date = date("Y-m-d");
				
				$start_time = date("Y-m-d H:i:s");
				$current_date = date("Ymd",strtotime($date));		
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
					
					if(!file_exists($cron_file_path.'/'.$file))
					{
						$file = "exam_cand_report_".$current_date.".txt";
					}
					else
					{
						$file = "exam_cand_report_".$current_date."_".date('His').".txt";	
					}
					
					$fp = fopen($cron_file_path.'/'.$file, 'w');
					
					$file1 = "logs_".$current_date.".txt";
					$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
					fwrite($fp1, "***************************** Candidate Exam Details Cron Execution Started - ".$start_time." ************************** \n");
					
					$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ( $date) ));
					//$yesterday = $date;
					
					$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode';
					
					$SQL = "SELECT DISTINCT(b.transaction_no), `a`.`exam_code`, `a`.`exam_mode`, `a`.`exam_medium`, `a`.`exam_period`, `a`.`exam_center_code`, `a`.`exam_fee`, `a`.`examination_date`, `b`.`member_regnumber`, `b`.`member_exam_id`, `b`.`amount`, DATE_FORMAT(b.date, '%Y-%m-%d') date, `b`.`receipt_no`, `b`.`ref_id`, `b`.`status`, `c`.`regnumber`, `c`.`registrationtype`, `a`.`elected_sub_code`, `a`.`place_of_work`, `a`.`state_place_of_work`, `a`.`pin_code_place_of_work`, `c`.`editedon`, `c`.`branch`, `c`.`office`, `c`.`city`, `c`.`state`, `c`.`pincode` FROM `member_exam` `a` LEFT JOIN `payment_transaction` `b` ON `b`.`ref_id`=`a`.`id` LEFT JOIN `member_registration` `c` ON `a`.`regnumber`=`c`.`regnumber` WHERE `pay_type` = 2 AND `status` = 1 AND `isactive` = '1' AND `isdeleted` =0 AND `pay_status` = 1 AND DATE(a.created_on) >= '2017-01-01' ";
					//$can_exam_data = $this->Master_model->getRecords('member_exam a',array(),$select,'',8000,1000);
					//AND `c`.`regnumber` IN (510042272, 510120489, 510250039, 6523313)
					
					$SQL .= $whereCondition;
					$SQL .= " ORDER BY `a`.`id` ASC ";
					$exam_qry = $this->db->query($SQL);
					$can_exam_data = $exam_qry->result_array();
					
					//echo"<br>".$this->db->last_query();
					$last_query = $this->db->last_query();
					$this->session->set_flashdata('last_query','"'.$last_query.'"');
					
					//echo "<br> Count - ".count($can_exam_data)."<br>";
					if(count($can_exam_data))
					{
						
						/*echo "<pre>";
						print_r($can_exam_data);*/
						//$data = '';
						$i = 1;
						$exam_cnt = 0;
						foreach($can_exam_data as $exam)
						{
							$data = '';
							//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO
							
							//error_reporting(E_ALL);
							//ini_set('display_errors', 'On');
							
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
							//echo " regnumber - ".$exam['regnumber'].", ";
							//echo "syllabus_code - ".$syllabus_code;
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
							if($exam['exam_code'] != '' && $exam['exam_code'] != 0)
							{
								$ex_code = $this->master_model->getRecords('exam_activation_master',array('exam_code'=>$exam['exam_code']));
								if(count($ex_code))
								{
									if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0)
									{	$exam_code = $ex_code[0]['original_val'];	}
									else
									{	$exam_code = $exam['exam_code'];	}
								}
							}
							else{	$exam_code = $exam['exam_code'];	}
							
							$place_of_work = '';
							$pin_code_place_of_work = '';
							$state_place_of_work = '';
							$city = '';
							$branch = '';
							$branch_name = '';
							$state = '';
							$pincode = '';
							
							$exam_arr = array($this->config->item('examCodeJaiib'), $this->config->item('examCodeCaiib'), 62, $this->config->item('examCodeCaiibElective63'), 64, 65, 66, 67, $this->config->item('examCodeCaiibElective68'), $this->config->item('examCodeCaiibElective69'), $this->config->item('examCodeCaiibElective70'), 71, 72);
							//if($exam_code == 60 || $exam_code == 21) // For CAIIB & JAIIB
							if(in_array($exam_code, $exam_arr)) // For CAIIB + Elective & JAIIB
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
								//if($exam_code == 60)
								
								if(in_array($exam_code, $exam_arr))
								{	$elected_sub_code = $exam['elected_sub_code'];	}
								
								if(strlen($place_of_work) > 30)
								{	$place_of_work = substr($place_of_work,0,29);		}
								else
								{	$place_of_work = $place_of_work;	}
								
								// Get old exam_code for CAIIB
								if($exam_code == $this->config->item('examCodeCaiib'))
								{
									$ex_code = $this->master_model->getRecords('eligible_master_60_117',array('member_no'=>$exam['regnumber'],'member_type'=>$exam['registrationtype']),'exam_code');
									if(count($ex_code))
									{
										$exam_code = $ex_code[0]['exam_code'];
									}
								}
								
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no']."|\n";
							}
							else
							{
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'||'.$branch_name.'|'.$pincode.'|'.$state.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no']."|\n";
							}
							
							//echo $data."<br>";
							
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
					$this->log_model->cronlog("Candidate Exam Details Cron Execution End", $desc);
					
					fwrite($fp1, "\n"."***************************** Candidate Exam Details Cron Execution End ".$end_time." **************************"."\n");
					fclose($fp1);
					
					$this->session->set_flashdata('success','Data generated successfully !!');
					redirect(base_url().'admin/crondata/exam');
				}
			}
		}
		$datares['breadcrumb'] = '';
		$datares['title'] = 'Generate Exam Application Data';
		$this->load->view('admin/cron_data',$datares);
	}
	
	// By VSU : Fuction to fetch user's edited data and export it in TXT format
	public function edit_data()
	{
		ini_set("memory_limit", "-1");
		
		if(isset($_POST['generate_data']))
		{
			if(isset($_POST['whereCondition']) && $_POST['whereCondition']!='')
			{
				$whereCondition = $_POST['whereCondition'];
				
				$dir_flg = 0;
				$parent_dir_flg = 0;
				$Zip_flg = 0;
				$photo_file_flg = 0;
				$sign_file_flg = 0;
				$idproof_file_flg = 0;
				$success = array();
				$error = array();
				
				$date = date("Y-m-d");
				
				$start_time = date("Y-m-d H:i:s");
				$current_date = date("Ymd",strtotime($date));
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
					fwrite($fp1, "\n************************* Edited Candidate Details Cron Execution Started - ".$start_time."*****************************\n");
					
					$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ( $date) ));
					$today = date('Y-m-d');
					
					$SQL = "SELECT * FROM member_registration WHERE `isactive` = '1' AND `isdeleted` =0 ";
					$SQL .= $whereCondition;
					$new_mem_qry = $this->db->query($SQL);
					$edited_mem_data = $new_mem_qry->result_array();
					
					
					
					//$edited_mem_data = $this->Master_model->getRecords('member_registration',array(' DATE(editedon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
					//echo $this->db->last_query()."<br>***************************<br>";
					$last_query = $this->db->last_query();
					$this->session->set_flashdata('last_query','"'.$last_query.'"');
					if(count($edited_mem_data))
					{
						$i = 1;
						$mem_cnt = 0;
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
							
							$data .= $edited_by.'|'.$optnletter.'|N|N|N|'.date('d-M-y H:i:s',strtotime($editeddata['editedon'])).'|'.$editeddata['aadhar_card']."|\n";
							
							$edit_data_flg = fwrite($fp, $data);
							if($edit_data_flg)
								$success['edit_data'] = "Edited Candidate Details File Generated Successfully.";
							else
								$error['edit_data'] = "Error While Generating Edited Candidate Details File.";
							
							$i++;
							$mem_cnt++;
							
							//break;
							//fwrite($fp1, "-------------------------------------------------------------------------------------------------------------\n");
						}
						
						fwrite($fp1, "\n"."Total Members Edited = ".$mem_cnt."\n");
					}
					else
					{
						$success[] = "No Profile data found for the date";
					}
					
					// Image data
					
					$SQL1 = "SELECT * FROM member_registration WHERE `isactive` = '1' AND `isdeleted` =0 ";
					
					if(strpos($whereCondition,'editedon') !== false)
					{
						$whereCondition = str_replace('editedon','images_editedon',$whereCondition);
					}
					
					$SQL1 .= $whereCondition;
					$new_mem_qry1 = $this->db->query($SQL1);
					$edited_img_data = $new_mem_qry1->result_array();
					
					//echo $this->db->last_query()."<br>***************************<br>";
					//$edited_img_data = $this->Master_model->getRecords('member_registration',array('DATE(images_editedon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
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
								case "U"	: 	$qualification = 1;
												break;
								case "G"	: 	$qualification = 3;
												break;
								case "P"	: 	$qualification = 5;
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
									$photo_data = $data.$img_edited_by.'|'.$optnletter.'|'.$photo.'|Y|N|N|'.date('d-M-y H:i:s',strtotime($imgdata['images_editedon'])).'|'.$imgdata['aadhar_card']."|\n";
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
									$sign_data = $data.$img_edited_by.'|'.$optnletter.'|'.$signature.'|N|Y|N|'.date('d-M-y H:i:s',strtotime($imgdata['images_editedon'])).'|'.$imgdata['aadhar_card']."|\n";
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
									$id_data = $data.$img_edited_by.'|'.$optnletter.'|'.$idproofimg.'|N|N|Y|'.date('d-M-y H:i:s',strtotime($imgdata['images_editedon'])).'|'.$imgdata['aadhar_card']."|\n";
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
									//copy("./uploads/photograph/".$imgdata['scannedphoto'],$directory."/".$imgdata['scannedphoto']);
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
									//copy("./uploads/idproof/".$imgdata['idproofphoto'],$directory."/".$imgdata['idproofphoto']);
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
					$this->log_model->cronlog("Edited Candidate Details Cron Execution End", $desc);
					
					fwrite($fp1, "\n"."************************* Edited Candidate Details Cron Execution End ".$end_time." **************************"."\n");
					fclose($fp1);
					
					$this->session->set_flashdata('success','Data generated successfully !!');
					redirect(base_url().'admin/crondata/edit_data');
				}
			}
		}
		
		$datares['breadcrumb'] = '';
		$datares['title'] = 'Generate Member Profile Edit Data';
		$this->load->view('admin/cron_data',$datares);
	}
	
	public function dra_exam()
	{	
		ini_set("memory_limit", "-1");
		
		if(isset($_POST['generate_data']))
		{
			if(isset($_POST['whereCondition']) && $_POST['whereCondition']!='')
			{
				$whereCondition = $_POST['whereCondition'];
				$dir_flg = 0;
				$parent_dir_flg = 0;
				$exam_file_flg = 0;
				$success = array();
				$error = array();
				
				$date = date("Y-m-d");
				
				$start_time = date("Y-m-d H:i:s");
				$current_date = date("Ymd",strtotime($date));
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
					fwrite($fp1, "DRA Candidate Exam Details Cron Execution Started - ".$start_time."\n");
					
					$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ( $date) ));
					//$yesterday = $date;
					
					$SQL = "SELECT `d`.`regnumber`, `d`.`registrationtype`, `a`.`id`, `c`.`exam_code`, `c`.`exam_period`, `a`.`gateway`, `a`.`UTR_no`, `a`.`transaction_no`, `c`.`exam_mode`, `c`.`training_from`, `c`.`training_to`, `c`.`exam_medium`, `c`.`exam_center_code`, `a`.`amount`, `a`.`inst_code`, `a`.`created_date`, `a`.`updated_date` FROM `dra_payment_transaction` `a` LEFT JOIN `dra_member_payment_transaction` `b` ON `a`.`id` = `b`.`ptid` LEFT JOIN `dra_member_exam` `c` ON `b`.`memexamid` = `c`.`id` LEFT JOIN `dra_members` `d` ON `d`.`regid` = `c`.`regid` WHERE `a`.`status` = 1 ";
					
					$SQL .= $whereCondition; 
					
					$dra_qry = $this->db->query($SQL);
					$dra_payment = $dra_qry->result_array();
					//echo $this->db->last_query(); exit;
					$last_query = $this->db->last_query();
					$this->session->set_flashdata('last_query','"'.$last_query.'"');
					
					if(count($dra_payment))
					{
						/*echo "<pre>";
						print_r($can_exam_data);*/
						
						$data = '';
						$i = 1;
						$exam_cnt = 0;
						foreach($dra_payment as $payment)
						{
							fwrite($fp1, $payment['id']."\n");
							
							$reg_num = $payment['regnumber'];
							$reg_type = $payment['registrationtype'];
							
							$syllabus_code = '';
							$part_no = '';
							$subject_data = $this->Master_model->getRecords('dra_subject_master',array('exam_code'=>$payment['exam_code'],'exam_period'=>$payment['exam_period']),'',array('id'=>'DESC'),0,1);
							if(count($subject_data)>0)
							{
								$syllabus_code = $subject_data[0]['syllabus_code'];
								$part_no = $subject_data[0]['part_no'];
							}
							
							if($payment['exam_mode'] == 'ON'){ $exam_mode = 'O';}
							else if($payment['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
							
							/*$trans_date = '';
							if($payment['created_date'] != '0000-00-00 00:00:00')
							{
								$trans_date = date('Y-m-d H:i:s',strtotime($payment['created_date']));
							}*/
							
							$transaction_no = '';
							$trans_date = '';
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
							if($payment['training_from'] != '0000-00-00')
							{
								$training_from = date('d-M-y',strtotime($payment['training_from']));
							}
							
							$training_to = '';
							if($payment['training_to'] != '0000-00-00')
							{
								$training_to = date('d-M-y',strtotime($payment['training_to']));
							}
							
							//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,	TRNG_INS_CD,trng_from,TRNG_TO
							
							$data .= ''.$payment['exam_code'].'|'.$payment['exam_period'].'|'.$reg_num.'|'.$reg_type.'|'.$payment['exam_medium'].'|'.$payment['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$transaction_no.'|'.$trans_date.'|'.$payment['amount'].'|'.$transaction_no.'|'.$payment['inst_code'].'|'.$training_from.'|'.$training_to."|\n";
							
							$exam_cnt++;
							$i++;
							
							//echo $reg_num."<br>";
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
					$this->log_model->cronlog("DRA Candidate Exam Details Cron Execution End", $desc);
					
					fwrite($fp1, "\n"."************************* DRA Candidate Exam Details Cron Execution End ".$end_time." **************************"."\n");
					fclose($fp1);
					
					$this->session->set_flashdata('success','Data generated successfully !!');
					redirect(base_url().'admin/crondata/dra_exam');
				}
			}
		}
		$datares['breadcrumb'] = '';
		$datares['title'] = 'Generate DRA Member Exam Data';
		$this->load->view('admin/cron_data',$datares);
	}
	
	// By VSU : Fuction to fetch new DRA member registration data and export it in TXT format
	public function dra_member()
	{ 
		ini_set("memory_limit", "-1");
		
		if(isset($_POST['generate_data']))
		{
			if(isset($_POST['whereCondition']) && $_POST['whereCondition']!='')
			{
				$whereCondition = $_POST['whereCondition'];
				
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
				
				$date = date("Y-m-d");
				
				$start_time = date("Y-m-d H:i:s");
				$current_date = date("Ymd",strtotime($date));		
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
					fwrite($fp1, "New DRA Candidate Details Cron Execution Started - ".$start_time."\n");
					
					$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ( $date) ));
					//$yesterday = $date;
					
					$SQL = "SELECT `namesub`, `firstname`, `middlename`, `lastname`, `regnumber`, `gender`, `scannedphoto`, `scannedsignaturephoto`, `idproofphoto`, `training_certificate`, `quali_certificate`, `qualification`, `registrationtype`, `address1`, `address2`, `city`, `district`, `pincode`, `state`, `dateofbirth`, `email`, `stdcode`, `phone`, `mobile`, `aadhar_no`, `idproof`, `a`.`transaction_no`, DATE(a.date) date, DATE(a.updated_date) updated_date, `a`.`UTR_no`, `a`.`amount`, `d`.`image_path`, `d`.`registration_no`, `a`.`gateway` FROM `dra_payment_transaction` `a` LEFT JOIN `dra_member_payment_transaction` `b` ON `a`.`id` = `b`.`ptid` LEFT JOIN `dra_member_exam` `c` ON `b`.`memexamid` = `c`.`id` LEFT JOIN `dra_members` `d` ON `d`.`regid` = `c`.`regid` WHERE `re_attempt` = 1 AND `a`.`status` = 1 AND 'isdeleted' =0 " ;
					
					$SQL .= $whereCondition;
					
					$SQL .= ' order by `d`.`regid` asc ';
					
					$dra_qry = $this->db->query($SQL);
					//echo $this->db->last_query();
					//exit;
					$new_dra_reg = $dra_qry->result_array();
					//echo $this->db->last_query();
					$last_query = $this->db->last_query();
					$this->session->set_flashdata('last_query','"'.$last_query.'"');
					
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
									fwrite($fp1, "**ERROR** - Training Certificate does not exist  - ".$dra['training_certificate']." (".$dra['regnumber'].")\n");	
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
							
							$photo 		= DRA_FILE_PATH.$photo_file;
							$signature 	= DRA_FILE_PATH.$sign_file;
							$idproofimg = DRA_FILE_PATH.$idproof_file;
							$tcimg 		= DRA_FILE_PATH.$tc_file;
							$dcimg 		= DRA_FILE_PATH.$dc_file;
							
							$qualification = '';
							$specialisation = '';
							switch($dra['qualification'])
							{
								case "tenth"	: 	$qualification = 1;
													$specialisation = 7;
													break;
								case "twelth"	: 	$qualification = 1;
													$specialisation = 8;
													break;
								case "graduate"	: 	$qualification = 3;
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
							
							if(strlen($dra['address1']) > 30)
							{	$address1 = substr($dra['address1'],0,29);	}
							else
							{	$address1 = $dra['address1'];	}
							
							if(strlen($dra['address2']) > 30)
							{	$address2 = substr($dra['address2'],0,29);	}
							else
							{	$address2 = $dra['address2'];	}
							
							if(strlen($dra['city']) > 30)
							{	$city = substr($dra['city'],0,29);	}
							else
							{	$city = $dra['city'];	}
							
							if(strlen($dra['district']) > 30)
							{	$district = substr($dra['district'],0,29);	}
							else
							{	$district = $dra['district'];	}
							
							
							/*$trans_date = '';
							if($dra['date'] != '0000-00-00' && $dra['date'] == $yesterday)
							{
								$trans_date = date('d-M-y',strtotime($dra['date']));
							}
							else if($dra['updated_date'] != '0000-00-00' && $dra['updated_date'] == $yesterday)
							{
								$trans_date = date('d-M-y',strtotime($dra['updated_date']));
							}
							*/
							$transaction_no = '';
							$trans_date = '';
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
							
							
							//$data .= ''.$dra['regnumber'].'|'.$dra['registrationtype'].'|'.$dra['namesub'].'|'.$dra['firstname'].'|'.$dra['middlename'].'|'.$dra['lastname'].'|'.$displayname.'|'.$address1.'|'.$address2.'|'.$city.'|'.$district.'|||'.$dra['pincode'].'|'.$dra['state'].'|'.$dob.'|'.$gender.'|'.$qualification.'|'.$specialisation.'||||||'.$dra['email'].'|'.$std_code.'|'.$dra['phone'].'|'.$dra['mobile'].'|'.$dra['idproof'].'||'.$transaction_no.'|'.$trans_date.'|'.$dra['amount'].'|'.$transaction_no.'|Y|||'.$photo.'|'.$signature.'|'.$idproofimg.'|'.$tcimg.'|'.$dcimg.'|||||'."\n";
							
							$data .= ''.$dra['regnumber'].'|'.$dra['registrationtype'].'|'.$dra['namesub'].'|'.$dra['firstname'].'|'.$dra['middlename'].'|'.$dra['lastname'].'|'.$displayname.'|'.$address1.'|'.$address2.'|'.$city.'|'.$district.'|||'.$dra['pincode'].'|'.$dra['state'].'|'.$dob.'|'.$gender.'|'.$qualification.'|'.$specialisation.'||||||'.$dra['email'].'|'.$std_code.'|'.$dra['phone'].'|'.$dra['mobile'].'|'.$dra['idproof'].'||'.$transaction_no.'|'.$trans_date.'|'.$dra['amount'].'|'.$transaction_no.'|Y|||'.$photo.'|'.$signature.'|'.$idproofimg.'|'.$tcimg.'|'.$dcimg.'|||||'.$dra['aadhar_no']."|\n";
							
							$i++;
							$mem_cnt++;
							
							//fwrite($fp1, "-------------------------------------------------------------------------------------------------------------\n");
							
							//exit;
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
					$this->log_model->cronlog("New DRA Candidate Details Cron Execution End", $desc);
					
					fwrite($fp1, "\n"."************************* New DRA Candidate Details Cron Execution End ".$end_time." **************************"."\n");
					fclose($fp1);
					
					$this->session->set_flashdata('success','Data generated successfully !!');
					redirect(base_url().'admin/crondata/dra_member');
				}
			}
		}
		$datares['breadcrumb'] = '';
		$datares['title'] = 'Generate New DRA Member Data';
		$this->load->view('admin/cron_data',$datares);
	}
	
	
	
	// By VSU : Fuction to fetch duplicate i-card requests and export in TXT format
	public function dup_icard()
	{
		ini_set("memory_limit", "-1");
		
		if(isset($_POST['generate_data']))
		{
			if(isset($_POST['whereCondition']) && $_POST['whereCondition']!='')
			{
				$whereCondition = $_POST['whereCondition'];
				
				$dir_flg = 0;
				$parent_dir_flg = 0;
				$dup_icard_flg = 0;
				$success = array();
				$error = array();
				
				$date = date("Y-m-d");
				
				$start_time = date("Y-m-d H:i:s");
				$current_date = date("Ymd",strtotime($date));		
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
					fwrite($fp1, "Duplicate I-card Details Cron Execution Started - ".$start_time."\n");
						
					$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ( $date) ));
					//$yesterday = $date;
					
					$SQL = 'SELECT `c`.`regnumber`, `c`.`reason_type`, `c`.`icard_cnt`, `a`.`registrationtype`, `b`.`transaction_no`, DATE_FORMAT(b.date, "%Y-%m-%d") date, `b`.`amount`, `b`.`transaction_no` FROM `duplicate_icard` `c` LEFT JOIN `member_registration` `a` ON `a`.`regnumber`=`c`.`regnumber` LEFT JOIN `payment_transaction` `b` ON `b`.`ref_id`=`c`.`did` WHERE `pay_type` = 3 AND `isactive` = "1" AND `status` = 1 AND `isdeleted` =0 ';
					
					$SQL .= $whereCondition;
					
					$icard_qry = $this->db->query($SQL);
					$dup_icard_data = $icard_qry->result_array();
					//echo $this->db->last_query();
					$last_query = $this->db->last_query();
					$this->session->set_flashdata('last_query','"'.$last_query.'"');
					
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
														
							$data .= ''.$icard['regnumber'].'|'.$icard['registrationtype'].'|'.$reason_type.'|'.$icard['transaction_no'].'|'.$icard['icard_cnt'].'|'.$icard_date.'|'.$icard['amount'].'|'.$icard['transaction_no']."|\n";
				
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
					$this->log_model->cronlog("Duplicate I-card Details Cron Execution End", $desc);
					
					fwrite($fp1, "\n"."************************* Duplicate I-card Details Cron Execution End ".$end_time." **************************"."\n");
					fclose($fp1);
					
					$this->session->set_flashdata('success','Data generated successfully !!');
					redirect(base_url().'admin/crondata/dup_icard');
				}
			}
		}
		$datares['breadcrumb'] = '';
		$datares['title'] = 'Generate Duplicate I-card Data';
		$this->load->view('admin/cron_data',$datares);
	}
	
	public function demo()
	{
		/*$str = ",email,asd,dgf,sfdsf,";
		echo $new_str = trim($str,',');
		exit;*/
		
		$str = 'a:2:{s:12:"updated_data";a:4:{s:5:"email";s:23:"naresh.b.babu@gmail.com";s:8:"editedon";s:19:"2017-03-10 13:46:02";s:8:"editedby";s:5:"veena";s:13:"editedbyadmin";s:2:"14";}s:8:"old_data";a:67:{s:5:"regid";s:7:"1349840";s:6:"reg_no";s:9:"811712634";s:9:"regnumber";s:9:"510258400";s:11:"usrpassword";s:24:"k8eL7jQX4M4Dfw4VNZkiHw==";s:7:"namesub";s:3:"MR.";s:9:"firstname";s:13:"B NARESH BABU";s:10:"middlename";s:0:"";s:8:"lastname";s:0:"";s:11:"displayname";s:13:"B NARESH BABU";s:14:"contactdetails";s:0:"";s:8:"address1";s:27:"#349/21 B, BALARAMAN STREET";s:8:"address2";s:18:"NEAR 1ST MAIN ROAD";s:8:"address3";s:16:"OTTERI EXTENSION";s:8:"address4";s:8:"VANDALUR";s:7:"country";s:0:"";s:8:"district";s:7:"CHENNAI";s:4:"city";s:7:"CHENNAI";s:5:"state";s:3:"TAM";s:7:"pincode";s:6:"600048";s:8:"centerid";s:1:"0";s:11:"dateofbirth";s:10:"1988-10-15";s:6:"gender";s:4:"male";s:13:"qualification";s:1:"P";s:21:"specify_qualification";s:2:"72";s:19:"associatedinstitute";s:3:"211";s:6:"branch";s:11:"HEAD OFFICE";s:6:"office";s:11:"HEAD OFFICE";s:11:"designation";s:2:"CL";s:10:"dateofjoin";s:10:"2016-02-19";s:11:"staffnumber";s:0:"";s:5:"email";s:22:"naresh.b.babu@gmail.co";s:16:"registrationtype";s:1:"O";s:7:"stdcode";s:0:"";s:12:"office_phone";s:0:"";s:6:"mobile";s:10:"8122885348";s:3:"fax";s:0:"";s:11:"nationality";s:0:"";s:12:"scannedphoto";s:0:"";s:21:"scannedsignaturephoto";s:0:"";s:7:"idproof";s:1:"5";s:4:"idNo";s:10:"AUTPN6172D";s:12:"idproofphoto";s:0:"";s:10:"optnletter";s:1:"Y";s:11:"declaration";s:0:"";s:6:"excode";s:1:"0";s:3:"fee";s:0:"";s:11:"exam_medium";s:0:"";s:11:"exam_period";s:1:"0";s:10:"centercode";s:1:"0";s:6:"exmode";s:2:"ON";s:7:"paymode";s:0:"";s:19:"registration_status";s:0:"";s:8:"isactive";s:1:"1";s:9:"isdeleted";s:1:"0";s:10:"zonal_code";s:1:"0";s:9:"createdon";s:19:"2016-03-10 22:46:14";s:8:"editedon";s:19:"2017-03-10 13:44:46";s:15:"images_editedon";s:19:"0000-00-00 00:00:00";s:8:"editedby";s:5:"veena";s:15:"images_editedby";s:0:"";s:13:"editedbyadmin";s:2:"14";s:20:"images_editedbyadmin";s:1:"0";s:9:"photo_flg";s:1:"Y";s:13:"signature_flg";s:1:"Y";s:6:"id_flg";s:1:"Y";s:10:"image_path";s:36:"/iibf_mem_reg/uploads/2016-03-10/22/";s:17:"old_member_number";s:0:"";}}';
		
		$arr = unserialize($str);
		echo "<pre>";
		print_r($arr);
		exit;
		
		$dates = array("28-Sep-16","17-Jan-17","17-Jan-17","17-Jan-17","17-Jan-17","17-Jan-17","20-Jan-17","20-Jan-17","20-Jan-17","21-Jan-17","21-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","27-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","28-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","29-Jan-17","30-Jan-17","01-Feb-17");
		
		$regnum = array(801135599,801148025,801148028,801148030,801148031,801148034,801148048,801148054,801148055,801148067,801148068,801148240,801148241,801148242,801148243,801148244,801148245,801148246,801148247,801148248,801148249,801148250,801148251,801148252,801148253,801148254,801148255,801148256,801148257,801148258,801148259,801148260,801148261,801148262,801148263,801148264,801148265,801148266,801148267,801148271,801148272,801148277,801148278,801148279,801148281,801148282,801148300,801148301,801148302,801148303,801148304,801148305,801148306,801148307,801148308,801148309,801148310,801148330,801148331,801148332,801148333,801148334,801148335,801148336,801148337,801148338,801148341,801148342,801148343,801148344,801148345,801148346,801148347,801148348,801148349,801148350,801148351,801148352,801148353,801148354,801148355,801148356,801148357,801148358,801148359,801148360,801148361,801148362,801148363,801148364,801148365,801148366,801148367,801148368,801148369,801148370,801148371,801148372,801148373,801148374,801148375,801148388,801148389,801148390,801148489,801135599);
		
		$trans_no = array("7664102605721","6480751825716","2499297339716","2499297339716","2499297339716","2499297339716","1113576616004","4789433599704","4789433599704","0336942393023","0336942393023","4088408880213","4088408880213","4088408880213","4088408880213","4088408880213","4088408880213","4088408880213","4088408880213","4088408880213","4088408880213","4088408880213","4088408880213","4088408880213","0857274040302","0857274040302","0857274040302","0857274040302","0857274040302","0857274040302","0857274040302","0857274040302","0857274040302","0857274040302","0857274040302","0857274040302","0857274040302","0857274040302","0857274040302","3380496848010","3380496848010","7433136948121","7433136948121","7433136948121","1239995181626","1239995181626","6262738873905","6262738873905","6262738873905","6262738873905","6262738873905","6262738873905","6262738873905","6262738873905","6262738873905","6262738873905","6262738873905","0051320996921","0051320996921","0051320996921","0051320996921","0051320996921","0051320996921","0051320996921","0051320996921","0051320996921","1638896495008","1638896495008","1638896495008","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","2676623129426","8155665607026","8155665607026","8155665607026","7391776898018","1020775788923");
		if(count($regnum))
		{
			for($i=0;$i<count($regnum);$i++)
			{
				//echo $regnum[$i]." - ".$trans_no[$i]." - ".$dates[$i]."<br>";
				
				echo 'UPDATE MT_OL_MEM_UPLOAD SET TRN_DATE="'.$dates[$i].'" WHERE MEM_MEM_NO = "'.$regnum[$i].'" AND BDRNO = "'.$trans_no[$i].'"; <br><br>';
			}
			echo "<br> Cnt - ".$i;
		}
	}
	
	// By : VSU - Function to send custom registration mail
	public function custom_reg_mail($regid,$regnum)
	{
		//echo "New";
		//email to user
		$last = $this->uri->total_segments();
		/*$regid = $this->uri->segment($last-2);
		$regnum = $this->uri->segment($last-1);*/
		
		if(is_numeric($regid) && $regnum!='')
		{
			$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_register_email'));
			if(count($emailerstr) > 0)
			{
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				
				//$decpass = $aes->decrypt($user_info[0]['usrpassword']);
				
				//Query to get user details
				/*$this->db->join('state_master','state_master.state_code=member_registration.state','LEFT');
				$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute','LEFT');
				$this->db->join('qualification','qualification.qid=member_registration.specify_qualification','LEFT');
				$this->db->join('idtype_master','idtype_master.id=member_registration.idproof','LEFT');
				$this->db->join('designation_master','designation_master.dcode=member_registration.designation','LEFT');
				$this->db->join('payment_transaction','payment_transaction.ref_id=member_registration.regid','LEFT');			*/	
				
				$result = $this->master_model->getRecords('member_registration',array('regnumber'=>"'".$regnum."'",'regid'=>$regid),'regid,regnumber,firstname,middlename,lastname,email,usrpassword,dateofbirth,dateofjoin,gender');
				//echo $this->db->last_query()."<br>";
				if(count($result)>0)
				{
					//echo "IN";
					$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
					$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
					
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
					
					$newstring1 = str_replace("#application_num#", "".$regnum."",$emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#password#", "".$decpass."",$newstring1);
					
					//$final_str = str_replace("#PINCODE#", "".strtoupper($result[0]['pincode'])."",$newstring24);
					//$result[0]['email'] //shruti.samdani@esds.co.in
					$info_arr=array('to'=>'shruti.samdani@esds.co.in',
									'from'=>$emailerstr[0]['from'],
									'subject'=>$emailerstr[0]['subject'],
									'message'=>$newstring2
								);				
					//echo "<pre>";
					//print_r($info_arr);			
					
					$mail_flg = $this->Emailsending->mailsend($info_arr);
					//var_dump($mail_flg);
					if($mail_flg)
					{
						echo 'Email sent successfully !!';
					}
					else
					{
						echo 'Error while sending email';
					}
				}
				else
				{
					echo 'Something went wrong...';
				}
			}
			else
			{
				echo 'Something went wrong...';
			}
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
}