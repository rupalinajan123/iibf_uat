<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Crontest extends CI_Controller {
		public $UserID;
		
		public function __construct(){
			parent::__construct();
			/*if($this-><title>Untitled Document</title>session->id==""){
				redirect('admin/Login');
			}*/	
			$this->load->model('UserModel');
			$this->load->model('Master_model'); 
			//$this->UserID=$this->session->id;
			
			$this->load->helper('pagination_helper'); 
			$this->load->library('pagination');
			$this->load->model('log_model');
			
			define('MEM_FILE_PATH','/fromweb/testscript/images/newmem/');
			define('CSC_MEM_FILE_PATH','/fromweb/testscript/images/newmem/');
			define('DIGITAL_EL_MEM_FILE_PATH','/fromweb/testscript/images/newmem/');
			define('DRA_FILE_PATH','/fromweb/testscript/images/dra/');
			define('MEM_FILE_EDIT_PATH','/fromweb/testscript/images/edit/');
			define('MEM_FILE_RENEWAL_PATH','/fromweb/testscript/images/renewal/');
			
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
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
				$yesterday = '2020-07-15';
				$members = array('510258893','510415515');
				$this->db->where_in('c.regnumber',$members);				
				
				$exam_code_arry = array('1002','1010','1011','1012','1013','1014');
				$select='c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,c.scannedphoto,c.scannedsignaturephoto,c.idproofphoto,c.image_path,c.reg_no, a.exam_code, ac.exam_date';
				$this->db->where_in('a.exam_code',$exam_code_arry);
				$this->db->join('member_registration c', 'a.regnumber = c.regnumber', 'LEFT');
				$this->db->join('admit_card_details ac', 'ac.mem_exam_id = a.id', 'LEFT');
				$new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
				'c.isactive' => '1',
				'c.isdeleted' => 0,
				'a.pay_status' => 1,
				'ac.remark' => 1,
				/* 'DATE(a.created_on)' => $yesterday */
				), $select);
				echo "<br> Qry : ".$this->db->last_query(); //exit;
				
				if(count($new_mem_reg))
				{
					$dirname = "NSEIT_regd_image_".$current_date;
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
						echo "<br><br>************** ".$reg_data['regnumber']." ************************";
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
							echo "<br>Data added to txt file";
						} 
						else { echo "<br>Data not added to txt file"; }
						
						
						//echo "<br> dir_flg : ".$dir_flg;
						echo "<br> photo : ".$photo; 
						//echo "<br> scannedphoto : ".$reg_data['scannedphoto'];
						//echo "<br> image_path : ".$image_path; 
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
								
								echo "<br> photo_to_add : ".$photo_to_add; 
								echo "<br> new_photo : ".$new_photo; //exit;
								/***** ADDED BY SAGAR ON 15-07-2020 *******/
								if($include_zip_flag == 1)
								{
									"<br>flag : ".$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);                
									if($photo_zip_flg == 1)
									{
										$photo_cnt++; 
										echo "<br>Photograph added to zip";
									} 
									else 
									{ 
										fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
										echo "<br>Photograph not added to zip"; 
									}
								}  						
							}
							
							// For signature images
							if($signature)
							{								
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
									if($sign_zip_flg == 1)
									{
										$sign_cnt++; 
										echo "<br>Signature added to zip";
									}
									else 
									{ 
										fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
										echo "<br>Signature not added to zip"; 
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
									if($idproof_zip_flg == 1)
									{
										$idproof_cnt++; 
										echo "<br>ID Proof added to zip";
									}
									else 
									{ 
										echo "<br>ID Proof not added to zip"; 
										fwrite($fp1, "**ERROR** - ID Proof not added to zip  - ".$reg_data['idproofphoto']." (".$reg_data['regnumber'].")\n");	
									}
								}
							}
							
							echo "<br>************************************************<br>";
							
							if($photo_zip_flg || $sign_zip_flg || $idproof_zip_flg)
							{
								$success['zip'] = "cscvendor Member Images Zip Generated Successfully";
							}
							else
							{
								$error['zip'] = "Error While Generating cscvendor Member Images Zip";
							}
						}
						$i++;
						if($include_zip_flag == 1) { $mem_cnt++; }
						$file_w_flg = fwrite($fp, $data);
						if($file_w_flg)
						{
							$success['file'] = "NSEIT Member Details File Generated Successfully. ";
						}
						else
						{
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
				else
				{
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
		
		public function cscvendor_member_sagar1()
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
				
				$yesterday = '2020-07-15';
				//$members = array('510101898','510415515');
				//$this->db->where_in('c.regnumber',$members);
				//$this->db->where('DATE(a.created_on) >=', '2020-05-29');
				//$this->db->where('DATE(a.created_on) <=', '2020-06-03');
				
				$exam_code_arry = array('1002','1003','1004','1005','1006','1007','1008','1009');
				$select='c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,c.scannedphoto,c.scannedsignaturephoto,c.idproofphoto,c.image_path,c.reg_no, a.exam_code, ac.exam_date';
				$this->db->where_in('a.exam_code',$exam_code_arry);
				//$this->db->where('c.regnumber','801463878'); // *** 
				//$this->db->limit(100);			
				$this->db->join('member_registration c', 'a.regnumber = c.regnumber', 'LEFT');
				$this->db->join('admit_card_details ac', 'ac.mem_exam_id = a.id', 'LEFT');
				$new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
				'c.isactive' => '1',
				'c.isdeleted' => 0,
				'a.pay_status' => 1,
				'ac.remark' => 1,
				'DATE(a.created_on)' => $yesterday
				), $select);
				echo "<br> Qry : ".$this->db->last_query();
				
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
						echo "<br><br>************** ".$reg_data['regnumber']." ************************";
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
							echo "<br>Data added to txt file";
						} 
						else { echo "<br>Data not added to txt file"; }
						
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
										echo "<br>Photograph not added to zip";
									} else { $photo_cnt++; echo "<br>Photograph added to zip"; }
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
										echo "<br>Signature not added to zip";
									}
									else { $sign_cnt++; echo "<br>Signature added to zip"; }
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
										echo "<br>ID Proof not added to zip";
									}
									else { $idproof_cnt++; echo "<br>ID Proof added to zip"; }
								}
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
				fwrite($fp1, "\n"."***** Vendor cscvendor Member Details Cron End ".$end_time." *****"."\n");
				fclose($fp1);
			}
		}
		
		/* Membership Edit data Cron */
		public function edit_data_live_all()
		{
			ini_set("memory_limit", "-1");
			
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$Zip_flg = 0;
			$photo_file_flg = 0;
			$sign_file_flg = 0;
			$idproof_file_flg = 0;
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
				
				$file = "edited_cand_details_".$current_date.".txt";
				$photo_file = "edited_cand_details_photo_".$current_date.".txt";
				$sign_file = "edited_cand_details_sign_".$current_date.".txt";
				$id_file = "edited_cand_details_id_".$current_date.".txt";
				$benchmark_file = "edited_cand_details_benchmark_".$current_date.".txt";
				
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$photo_fp = fopen($cron_file_path.'/'.$photo_file, 'w');
				$sign_fp = fopen($cron_file_path.'/'.$sign_file, 'w');
				$id_fp = fopen($cron_file_path.'/'.$id_file, 'w');
				$benchmark_fp = fopen($cron_file_path.'/'.$benchmark_file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n********** Edited Candidate Details Cron Start - ".$start_time."**********\n");
				
				//$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$today = date('Y-m-d');
				
				$yesterday = '2020-06-10';
				$today = '2020-06-10';
				
				
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
				
				// Blenchmark data
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
				$yesterday = '2020-05-27';
				$exam_codes_arr = array('1002','1003','1004');
				
				$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,e.exam_date,a.elearning_flag';
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber = c.regnumber','LEFT'); 
				$this->db->join('admit_card_details e','a.id = e.mem_exam_id','LEFT'); 
				$this->db->where_in('a.exam_code',$exam_codes_arr);
				$this->db->where('DATE(a.created_on) >=', '2020-06-09');
				$this->db->where('DATE(a.created_on) <=', '2020-06-14');
				$can_exam_data = $this->Master_model->getRecords('member_exam a',array(/*' DATE(a.created_on)'=>$yesterday,*/'pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1),$select);
				
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
						
						//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG,EXAM_DATE,ELEARNING_FLAG
						
						$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$exam['scribe_flag'].'|'.$exam_date.'|'.$exam['elearning_flag']."\n"; 
						
						
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
		
		/* Membership Edit data Cron */
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
				
				//$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$today = date('Y-m-d');
				
				$yesterday = '2020-06-22';
				//$today = '2020-06-11';
				
				
				
				// Blenchmark data
				//$this->db->limit(1);
				//$this->db->where('DATE(benchmark_edit_date) >=', '2020-06-22');
				//$this->db->where('DATE(benchmark_edit_date) <=', '2020-06-22');
				$edited_benchmark_data = $this->Master_model->getRecords('member_registration',array('DATE(benchmark_edit_date)'=>$yesterday,'isactive'=>'1','isdeleted'=>0,'benchmark_edit_flg' => 'Y'));
				
				// 
				
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
				
				$yesterday = '2020-07-09'; 
				//$members = array('510398631','500180924','510114674');
				
				//$this->db->where('DATE(a.created_on) >=', '2020-05-29');
				//$this->db->where('DATE(a.created_on) <=', '2020-06-03');
				
				
				$exam_code_arry = array('1003','1004');
				$select='c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,c.scannedphoto,c.scannedsignaturephoto,c.idproofphoto,c.image_path,c.reg_no';
				$this->db->where_in('a.exam_code',$exam_code_arry);
				//$this->db->where_in('c.regnumber',$members);
				$this->db->join('member_registration c', 'a.regnumber = c.regnumber', 'LEFT');
				$new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
				'c.isactive' => '1',
				'c.isdeleted' => 0,
				'a.pay_status' => 1,
				'DATE(a.created_on)' => $yesterday
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
								
								if($image_path != '')
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
								
								if($image_path != '')
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
								
								if($image_path != '')
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
		
		
		public function cscvendor_member_sagar()
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
				fwrite($fp1, "\n"."***** Vendor cscvendor Member Details Cron End ".$start_time." *****"."\n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ($current_date) ));
				
				//$yesterday = '2020-05-29';
				//$members = array('510101898','510415515');
				//$this->db->where_in('c.regnumber',$members);
				//$this->db->where('DATE(a.created_on) >=', '2020-05-29');
				//$this->db->where('DATE(a.created_on) <=', '2020-06-03');
				
				$exam_code_arry = array('1003','1004','1005','1006','1007','1008','1009');
				$select='c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,c.scannedphoto,c.scannedsignaturephoto,c.idproofphoto,c.image_path,c.reg_no';
				$this->db->where_in('a.exam_code',$exam_code_arry);
				//$this->db->where('c.regnumber','801463878'); // *** 
				//$this->db->limit(100);			
				$this->db->join('member_registration c', 'a.regnumber = c.regnumber', 'LEFT');
				$new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
				'c.isactive' => '1',
				'c.isdeleted' => 0,
				'a.pay_status' => 1,
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
						
						$data .= ''.$reg_data['regnumber'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname']."|\n";
						
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
								$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
								if(!$photo_zip_flg){
									fwrite($fp1, "**ERROR** - Photograph not added to zip  - ".$reg_data['scannedphoto']." (".$reg_data['regnumber'].")\n");	
								}
								else
								$photo_cnt++;
								
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
								$sign_zip_flg = $zip->addFile($sign_to_add,$new_sign);
								if(!$sign_zip_flg){
									fwrite($fp1, "**ERROR** - Signature not added to zip  - ".$reg_data['scannedsignaturephoto']." (".$reg_data['regnumber'].")\n");	
								}else
								$sign_cnt++;
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
				fwrite($fp1, "\n"."***** Vendor cscvendor Member Details Cron End ".$end_time." *****"."\n");
				fclose($fp1);
			}
		}
		
		
		// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron cscvendor_member
		/* Only for CSCVendor : 29-05-2019 */
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
				
				$yesterday = '2020-05-29';
				
				//$members = array('510101898','510415515');
				//$this->db->where_in('c.regnumber',$members);
				//$this->db->where('DATE(a.created_on) >=', '2020-05-29');
				//$this->db->where('DATE(a.created_on) <=', '2020-06-03');
				
				$exam_code_arry = array('1002');
				$select='c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,c.scannedphoto,c.scannedsignaturephoto,c.idproofphoto,c.image_path,c.reg_no';
				$this->db->where_in('a.exam_code',$exam_code_arry);
				//$this->db->where('DATE(a.created_on) >=', '2020-05-29');
				//$this->db->where('DATE(a.created_on) <=', '2020-06-03');
				$this->db->join('member_registration c', 'a.regnumber = c.regnumber', 'LEFT');
				$new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
				'c.isactive' => '1',
				'c.isdeleted' => 0,
				'a.pay_status' => 1,'DATE(a.created_on)' => $yesterday
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
								
								if($image_path != '')
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
								
								if($image_path != '')
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
								
								if($image_path != '')
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
				
				$yesterday = '2020-06-30';
				//$members = array('500174961','500177383','500177617','500181983','510025172','510045557','510061753','510106548','510108711','510115410','510151661','510155054','510157702','510172108','510174220','510187115','510187967','510220194','510225552','510255357','510271705','510275653','510293056','510304316','510306661','510310823','510347122','510359169','510386861','510390447','510399529','510405356','510407634','510417405','510424067','510424252','510427437','510429007','510429625','510436579','510441974','510449455','510453582','510453788','7220070','801162935','801357120','801413578','801470348','801471532','801471981','801471985','801472953','801473221','801474613','801477830','801477839','801480641');
				//$this->db->where_in('c.regnumber',$members);
				//$this->db->where('DATE(a.created_on) >=', '2020-05-29');
				//$this->db->where('DATE(a.created_on) <=', '2020-06-03');
				
				$exam_code_arry = array('1002');
				$select='c.regnumber,c.namesub,c.firstname,c.middlename,c.lastname,c.scannedphoto,c.scannedsignaturephoto,c.idproofphoto,c.image_path,c.reg_no';
				$this->db->where_in('a.exam_code',$exam_code_arry);
				$this->db->join('member_registration c', 'a.regnumber = c.regnumber', 'LEFT');
				$new_mem_reg = $this->Master_model->getRecords('member_exam a', array(
				'c.isactive' => '1',
				'c.isdeleted' => 0,
				'a.pay_status' => 1,
				'DATE(a.created_on)' => $yesterday
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
								
								if($image_path != '')
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
								
								if($image_path != '')
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
								
								if($image_path != '')
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
		
		
		/* Temp Exam Application Cron*/
		public function exam_temp()
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
				
				$exam_codes_arr = array('1002','1003','1004');
				
				$select = 'a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,a.id,a.created_on,e.exam_date';
				$this->db->where('DATE(a.created_on) >=', '2020-05-30');
				$this->db->where('DATE(a.created_on) <=', '2020-06-07');
				$this->db->where_in('a.exam_code',$exam_codes_arr);
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT');
				$this->db->join('admit_card_details e','a.id = e.mem_exam_id','LEFT'); 
				$can_exam_data = $this->Master_model->getRecords('member_exam a',array('isactive'=>'1','isdeleted'=>0,'pay_status'=>1),$select);
				//' DATE(a.created_on)'=>$yesterday,
				//echo $this->db->last_query();exit;
				
				/*$select    = 'a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,a.id,a.created_on,d.exam_date';
					$this->db->where('DATE(a.created_on) >=', '2020-05-29');
					$this->db->where('DATE(a.created_on) <=', '2020-05-29');
					$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
					$this->db->join('admit_card_details d', 'c.regnumber=d.mem_mem_no', 'left');
					$this->db->where('DATE(d.exam_date) >=', '2020-05-21');
					$this->db->where('DATE(d.exam_date) <=', '2020-06-28');
					$this->db->where_in('a.exam_code', $exam_codes_arr);
					$this->db->where('d.exm_prd','777');
					$can_exam_data = $this->Master_model->getRecords('member_exam a', array(
					'remark' => 1,
					'isactive' => '1',
					'isdeleted' => 0,
					'pay_status' => 1
				), $select);*/
				
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
							
							$exam_date = '';
							if($exam['exam_date'] != '0000-00-00')
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
							//$exam['elected_sub_code']!=0
							//if($exam_code == 60 || $exam_code == 21) // For CAIIB & JAIIB
							if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == 68 || $exam_code == 69 || $exam_code == 70 || $exam_code == 71 || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) // For CAIIB, CAIIB Elective & JAIIB, above line commented and this line added by Bhagwan Sahane, on 06-10-2017
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
								$elearning_flag = 'N'; 
								$transaction_no = '0000000000000';
								$exam_fee = '0';
								
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$transaction_no.'|'.$trans_date.'|'.$exam_fee.'|'.$transaction_no.'|'.$scribe_flag.'|'.$exam_date.'|'.$elearning_flag."\n"; 
							}
							else
							{    
								$transaction_no = '0000000000000';
								$exam_fee = '0';
								$elearning_flag = 'N'; 
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$transaction_no.'|'.$trans_date.'|'.$exam_fee.'|'.$transaction_no.'|'.$scribe_flag.'|'.$exam_date.'|'.$elearning_flag."\n"; 
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
			ini_set("memory_limit", "-1");
			
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$file_w_flg = 0;
			$success = array();
			$error = array();
			
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	
			$cron_file_dir = "./uploads/cronFilesCustom/";
			
			//$cron_file_dir = "./uploads/cronfiles_pg/";
			
			
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
				$members = array('510276652','100017682','500050705','500134768','500161321','500169210,510086332','510144765','510202376','510279672','510227819','510375250','510422317');
				
				$exam_code_a = array('1003','1004','1002'); 
				$select = 'a.regnumber, a.examination_date';
				//$this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
				$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
				//$this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
				//$this->db->join('admit_card_details d', 'c.regnumber=d.mem_mem_no', 'left');
				//$this->db->where('DATE(d.exam_date) >=', '2020-05-21');
				//$this->db->where('DATE(d.exam_date) <=', '2020-06-28');
				$this->db->where_in('d.exm_prd',array('777'));
				$this->db->where_in('a.exam_code', $exam_code_a);
				$this->db->where_in('a.regnumber',$members);
				$cand_exam_data = $this->Master_model->getRecords('member_exam a', array(
				//'remark' => 1,
				/*'pay_type' => 2,
				'status' => 1,*/
				'isactive' => '1',
				'isdeleted' => 0,
				'pay_status' => 1
				), $select);
				echo $this->db->last_query();
				//$exam_codes_arr = array('1002','1003','1004');
				//			$member = array('500174961','500177383','500177617','500181983','510025172','510045557','510061753','510106548','510108711','510115410','510151661','510155054','510157702','510172108','510174220','510187115','510187967','510220194','510225552','510255357','510271705','510275653','510293056','510304316','510306661','510310823','510347122','510359169','510386861','510390447','510399529','510405356','510407634','510417405','510424067','510424252','510427437','510429007','510429625','510436579','510441974','510449455','510453582','510453788','7220070','801162935','801357120','801413578','801470348','801471532','801471981','801471985','801472953','801473221','801474613','801477830','801477839','801480641');
				//			$select = 'a.id as mem_exam_id, a.examination_date';
				//			//$this->db->where('DATE(a.created_on) >=', '2020-05-29');
				//			//$this->db->where('DATE(a.created_on) <=', '2020-05-31');
				//			$this->db->where_in('a.regnumber', $member);
				//			$this->db->where_in('a.exam_code',$exam_codes_arr);
				//			$cand_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_status'=>1),$select);
				//			//'pay_type'=>2,'status'=>1,//' DATE(a.created_on)'=>$yesterday,
				//			echo $this->db->last_query();
				if(count($cand_exam_data))
				{
					$admit_card_count = 0;
					$admit_card_sub_count = 0;
					
					foreach($cand_exam_data as $exam)
					{
						$regnumber = $exam['regnumber'];
						//$select = 'ref_id';
						//$check_payment_data = $this->Master_model->getRecords('payment_transaction',array('pay_type'=>2,'status'=>1,'ref_id'=>$mem_exam_id),$select);
						
						//if(empty($check_payment_data))
						//{
						// get admit card details for this member by mem_exam_id
						$this->db->where('remark', 1);
						$this->db->where_in('exm_prd',array('777'));
						$admit_card_details_arr = $this->Master_model->getRecords('admit_card_details',array('mem_mem_no' => $regnumber));
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
					//}
					
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
		
		
		/* Member Registration Cron */
		public function member_test()
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
			$this->log_model->cronlog("New Member Details Cron Execution Start", $desc);
			
			if(!file_exists($cron_file_dir.$current_date)){
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "test_iibf_new_mem_details_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "test_logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n********** New Member Details Cron Start - ".$start_time." ********** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2019-11-21';
				$excode = array('526','527','991');
				$this->db->where_not_in('a.excode', $excode);
				$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0));	
				
				if(count($new_mem_reg))
				{
					$dirname = "test_regd_image_".$current_date;
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
						
						if($reg_data['excode'] == '191' || $reg_data['excode'] == '1910' || $reg_data['excode'] == '19100')
						{
							$regnumber = '191'.$reg_data['regnumber'];
						}
						else
						{
							$regnumber = $reg_data['regnumber'];
						}
						
						
						$data .= ''.$regnumber.'|'.$reg_data['registrationtype'].'|'.$reg_data['namesub'].'|'.$reg_data['firstname'].'|'.$reg_data['middlename'].'|'.$reg_data['lastname'].'|'.$reg_data['displayname'].'|'.$address1.'|'.$address2.'|'.$address3.'|'.$address4.'|'.$district.'|'.$city.'|'.$reg_data['pincode'].'|'.$reg_data['state'].'|'.$address1_pr.'|'.$address2_pr.'|'.$address3_pr.'|'.$address4_pr.'|'.$district_pr.'|'.$city_pr.'|'.$reg_data['pincode_pr'].'|'.$reg_data['state_pr'].'|'.$mem_dob.'|'.$gender.'|'.$qualification.'|'.$reg_data['specify_qualification'].'|'.$reg_data['associatedinstitute'].'|'.$branch.'|'.$reg_data['designation'].'|'.$mem_doj.'|'.$reg_data['email'].'|'.$std_code.'|'.$reg_data['office_phone'].'|'.$reg_data['mobile'].'|'.$reg_data['idproof'].'|'.$reg_data['idNo'].'|'.$transaction_no.'|'.$transaction_date.'|'.$transaction_amt.'|'.$transaction_no.'|'.$optnletter.'|||'.$photo.'|'.$signature.'|'.$idproofimg.'|||||'.$reg_data['aadhar_card'].'|'.$reg_data['bank_emp_id']."\n";
						
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
				$this->log_model->cronlog("New Member Details Cron End", $desc);
				
				fwrite($fp1, "\n"."********** New Member Details Cron End ".$end_time." ***********"."\n");
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
			$cron_file_dir = "./uploads/rahultest/";
			
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF Digital ELearning Member Details Cron Execution Start", $desc);
			
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
				fwrite($fp1, "\n***** IIBF Digital ELearning Member Details Cron Execution Started - ".$start_time." ***** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				
				$yesterday = '2019-09-14';
				
				$excode = array('526','527');
				$this->db->where_in('a.excode', $excode);
				$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0));	
				
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
				$this->log_model->cronlog("IIBF Digital ELearning Member Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."******* IIBF Digital ELearning Member Details Cron Execution End ".$end_time." ********"."\n");
				fclose($fp1);
			}
		}
		
		/* Member Registration Cron */
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
				
				$yesterday = '2020-03-09';
				//$this->db->where('DATE(a.createdon) >=', '2019-11-27');
				//$this->db->where('DATE(a.createdon) <=', '2019-11-27');
				$mem = array(510472149);
				
				$excode = array('526','527','991');
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
								$this->db->where("a.status = 1 AND  d.regnumber = '".$reg_data['regnumber']."'");
								//( DATE(a.updated_date) = '".$yesterday."') AND 
								$trans_details = $this->Master_model->getRecords('bulk_payment_transaction a');	
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
		
		/* Bulk Member Registration Cron */ 
		public function bulk_member()
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
				
				$yesterday = '2020-03-09';
				//$this->db->where('DATE(a.createdon) >=', '2019-11-27');
				//$this->db->where('DATE(a.createdon) <=', '2019-11-27');
				$mem = array('801351016','801457282');
				
				$excode = array('526','527','991');
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
						if($reg_data['registrationtype']=='NM')
						{
							//print_r($reg_data['registrationtype']);
							if($reg_data['registrationtype'] == 'DB')
							{
								$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_DB','pay_type'=>2,'member_regnumber'=>$reg_data['regnumber'],'status'=>1),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
								//echo $this->db->last_query();
								;
							}
							else
							{
								$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'iibfregn','status'=>1,'pay_type'=>1,'ref_id'=>$reg_data['regid']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');							
							}
						}
						else
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_REG','status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
							
							//print_r($trans_details);
							/* For Bulk Members if Non-Member Application */
							if(empty($trans_details))
							{
								$this->db->select("DATE(a.updated_date) AS date, a.UTR_no AS transaction_no, a.amount");
								$this->db->join('bulk_member_payment_transaction b','a.id = b.ptid','LEFT');
								$this->db->join('member_exam c','b.memexamid = c.id','LEFT');
								$this->db->join('member_registration d','d.regnumber = c.regnumber','LEFT');
								$this->db->where("a.status = 1  AND  d.regnumber = '".$reg_data['regnumber']."'");
								// ( DATE(a.updated_date) = '".$yesterday."') AND
								//( DATE(a.updated_date) = '".$yesterday."') AND 
								$trans_details = $this->Master_model->getRecords('bulk_payment_transaction a');	
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
    /* Dra Agency Center Reg. */
		public function agency_center_reg()
		{
			ini_set("memory_limit", "-1");
			
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$dra_inst_flg = 0;
			$success = array();
			$error = array();
			
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");
			$cron_file_dir = "./uploads/rahultest/";
			
			// Cron start Logs
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("Agency Center Reg. Cron Execution Start", $desc);
			
			if(!file_exists($cron_file_dir.$current_date)){
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			
			if(file_exists($cron_file_dir.$current_date)){
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "agency_center_reg_details_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n********** Agency Center Reg. Cron Execution Started - ".$start_time." **********\n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2019-05-16';
				
				// Get DRA Agency Center Reg. Details For Given Date
				
				$select = 'c.*,g.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
				$this->db->where('DATE(b.date) >=', '2019-03-01');
				$this->db->where('DATE(b.date) <=', '2019-08-31');
				$this->db->join('dra_inst_registration c','c.id = g.agency_id','LEFT');
				$this->db->join('payment_transaction b','b.ref_id = g.center_id','LEFT');
				$agn_ctr_reg_data = $this->Master_model->getRecords('agency_center g',array('pay_type' => 16,'g.pay_status' => '1','b.status' => '1'),$select);
				
				// ' DATE(b.date)' => $yesterday, //
				
				if(count($agn_ctr_reg_data))
				{
					$data = '';
					$i = 1;
					$mem_cnt = 0;
					foreach($agn_ctr_reg_data as $agn_ctr_reg)
					{	
						$trn_date = $date_of_approved = $center_validity_from = $center_validity_to = '';
						
						if($agn_ctr_reg['date'] != '0000-00-00'){
							$trn_date = date('d-M-Y',strtotime($agn_ctr_reg['date']));
						}
						
						if($agn_ctr_reg['date_of_approved'] != '0000-00-00'){
							$date_of_approved = date('d-M-Y',strtotime($agn_ctr_reg['date_of_approved']));
						}
						
						if($agn_ctr_reg['center_validity_from'] != '0000-00-00'){
							$center_validity_from = date('d-M-Y',strtotime($agn_ctr_reg['center_validity_from']));
						}
						
						if($agn_ctr_reg['center_validity_to'] != '0000-00-00'){
							$center_validity_to = date('d-M-Y',strtotime($agn_ctr_reg['center_validity_to']));
						}
						
						/* Get City Name as per location name(id)*/
						$location_name_arr = $this->Master_model->getRecords('city_master',array('id'=>$agn_ctr_reg['location_name']));
						$location_name = $location_name_arr[0]['city_name'];
						
						/* INST_CODE | INST_NAME | ESTB_YEAR | MAIN_ADR_1 | MAIN_ADR_2 | MAIN_ADR_3 | MAIN_ADR_4 | MAIN_ADR_5(DISTRICT) | MAIN_ADR_6(CITY_CODE) | MAIN_STE_CD | MAIN_PIN_CD | INST_PHONE_NO | INST_FAX_NO | INST_WEBSITE | INST_HEAD_NAME | INST_HEAD_CONTACT_NO | INST_HEAD_EMAIL | LOC_NAME(CENTER_NAME) | LOC_ADR_1 | LOC_ADR_2 | LOC_ADR_3 | LOC_ADR_4 | LOC_ADR_5(DISTRICT) | LOC_ADR_6(CITY_CODE) | LOC_STE_CD | LOC_PIN_CD | CONTACT_PERSON_NAME | CONTACT_PERSON_MOBILE | CONTACT_PERSON_EMAIL | DUE_DLGNC | GSTN_NO | CENTER_TYPE(T/R) | DATE_OF_APPROVED(e.g.18-Aug-2019) | CENTER_VALIDITY_FROM(e.g.18-Aug-2019) | CENTER_VALIDITY_TO(e.g.18-Aug-2019) | TRN_DATE(e.g.18-Aug-2019) | FEE_AMT | TRN_NO */ 
						$data .= ''.$agn_ctr_reg['institute_code'].'|'.$agn_ctr_reg['inst_name'].'|'.$agn_ctr_reg['estb_year'].'|'.$agn_ctr_reg['main_address1'].'|'.$agn_ctr_reg['main_address2'].'|'.$agn_ctr_reg['main_address3'].'|'.$agn_ctr_reg['main_address4'].'|'.$agn_ctr_reg['main_district'].'|'.$agn_ctr_reg['main_city'].'|'.$agn_ctr_reg['main_state'].'|'.$agn_ctr_reg['main_pincode'].'|'.$agn_ctr_reg['inst_phone'].'|'.$agn_ctr_reg['inst_fax_no'].'|'.$agn_ctr_reg['inst_website'].'|'.$agn_ctr_reg['inst_head_name'].'|'.$agn_ctr_reg['inst_head_contact_no'].'|'.$agn_ctr_reg['inst_head_email'].'|'.$location_name.'|'.$agn_ctr_reg['address1'].'|'.$agn_ctr_reg['address2'].'|'.$agn_ctr_reg['address3'].'|'.$agn_ctr_reg['address4'].'|'.$agn_ctr_reg['district'].'|'.$agn_ctr_reg['city'].'|'.$agn_ctr_reg['state'].'|'.$agn_ctr_reg['pincode'].'|'.$agn_ctr_reg['contact_person_name'].'|'.$agn_ctr_reg['contact_person_mobile'].'|'.$agn_ctr_reg['email_id'].'|'.$agn_ctr_reg['due_diligence'].'|'.$agn_ctr_reg['gstin_no'].'|'.$agn_ctr_reg['center_type'].'|'.$date_of_approved.'|'.$center_validity_from.'|'.$center_validity_to.'|'.$trn_date.'|'.$agn_ctr_reg['amount'].'|'.$agn_ctr_reg['transaction_no']."\n";		
						
						/* 257|ILFS|2011|ILFS|MSDC|KAPURTHALA ROAD|JALANDHAR|JALANDHAR|JALANDHAR|PUN|144021|9779073467||www.ilfsindia.com|sofi Arshid|9906547771|sofi.arshid@ilfsindia.com|Amritsar|IIS AMRITSAR,|GURU HAR RAI PUBLIC SCHOOL|NR. GURU RAMDAS COLLEGE|GT RD. AMRITSAR - 143001|AMRITSAR|774|PUN|143001|Sofi Arshid|9906547771|sofi.arshid@ilfsindia.com|Yes|03AACCI5146G1ZX|T|04-May-2019|20-May-2019|17-Aug-2019|16-May-2019|3540.00|8873432674604
						*/
						$i++;
						$mem_cnt++;
					}
					$agn_ctr_reg_flg = fwrite($fp, $data);
					if($agn_ctr_reg_flg)
					$success['agn_ctr_reg'] = "Agency Center Reg. Details File Generated Successfully. ";
					else
					$error['agn_ctr_reg'] = "Error While Generating Agency Center Reg. Details File.";
					
					fwrite($fp1, "\n"."Total Agency Center Reg.  Applications = ".$mem_cnt."\n");
				}
				else{
					$success[] = "No data found for the date";
				}
				fclose($fp);
				// Cron End Logs
				$end_time = date("Y-m-d H:i:s");
				$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
				$desc = json_encode($result);
				$this->log_model->cronlog("Agency Center Reg. Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."********** Agency Center Reg. Details Cron Execution End ".$end_time." **********"."\n");
				fclose($fp1);
			}
		}
		
		/* Dra Agency Center Renewal. */
		public function agency_center_renewal()
		{
			ini_set("memory_limit", "-1");
			
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$dra_inst_flg = 0;
			$success = array();
			$error = array();
			
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");
			$cron_file_dir = "./uploads/rahultest/";
			
			// Cron start Logs
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("Agency Center Renewal. Cron Execution Start", $desc);
			
			if(!file_exists($cron_file_dir.$current_date)){
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			
			if(file_exists($cron_file_dir.$current_date)){
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "agency_center_renewal_details_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n********** Agency Center Renewal. Cron Execution Started - ".$start_time." **********\n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2019-05-16';
				
				// Get DRA Agency Center Reg. Details For Given Date
				$select = 'c.*,g.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
				$this->db->where('DATE(b.date) >=', '2019-03-01');
				$this->db->where('DATE(b.date) <=', '2019-08-31');
				$this->db->join('dra_inst_registration c','c.id = g.agency_id','LEFT');
				$this->db->join('payment_transaction b','b.ref_id = g.center_id','LEFT');
				$agn_ctr_renewal_data = $this->Master_model->getRecords('agency_center g',array('pay_type' => 17,'g.pay_status' => '1','b.status' => '1'),$select);
				// ' DATE(b.date)' => $yesterday,
				if(count($agn_ctr_renewal_data))
				{
					$data = '';
					$i = 1;
					$mem_cnt = 0;
					foreach($agn_ctr_renewal_data as $agn_ctr_renewal)
					{	
						$trn_date = $date_of_approved = $center_validity_from = $center_validity_to = '';
						
						if($agn_ctr_renewal['date'] != '0000-00-00'){
							$trn_date = date('d-M-Y',strtotime($agn_ctr_renewal['date']));
						}
						
						if($agn_ctr_renewal['date_of_approved'] != '0000-00-00'){
							$date_of_approved = date('d-M-Y',strtotime($agn_ctr_renewal['date_of_approved']));
						}
						
						if($agn_ctr_renewal['center_validity_from'] != '0000-00-00'){
							$center_validity_from = date('d-M-Y',strtotime($agn_ctr_renewal['center_validity_from']));
						}
						
						if($agn_ctr_renewal['center_validity_to'] != '0000-00-00'){
							$center_validity_to = date('d-M-Y',strtotime($agn_ctr_renewal['center_validity_to']));
						}
						
						/* Get City Name as per location name(id)*/
						$location_name_arr = $this->Master_model->getRecords('city_master',array('id'=>$agn_ctr_renewal['location_name']));
						$location_name = $location_name_arr[0]['city_name'];
						
						/* INST_CODE | INST_NAME | ESTB_YEAR | MAIN_ADR_1 | MAIN_ADR_2 | MAIN_ADR_3 | MAIN_ADR_4 | MAIN_ADR_5(DISTRICT) | MAIN_ADR_6(CITY_CODE) | MAIN_STE_CD | MAIN_PIN_CD | INST_PHONE_NO | INST_FAX_NO | INST_WEBSITE | INST_HEAD_NAME | INST_HEAD_CONTACT_NO | INST_HEAD_EMAIL | LOC_NAME(CENTER_NAME) | LOC_ADR_1 | LOC_ADR_2 | LOC_ADR_3 | LOC_ADR_4 | LOC_ADR_5(DISTRICT) | LOC_ADR_6(CITY_CODE) | LOC_STE_CD | LOC_PIN_CD | CONTACT_PERSON_NAME | CONTACT_PERSON_MOBILE | CONTACT_PERSON_EMAIL | DUE_DLGNC | GSTN_NO | CENTER_TYPE(T/R) | DATE_OF_APPROVED(e.g.18-Aug-2019) | CENTER_VALIDITY_FROM(e.g.18-Aug-2019) | CENTER_VALIDITY_TO(e.g.18-Aug-2019) | TRN_DATE(e.g.18-Aug-2019) | FEE_AMT | TRN_NO */ 
						$data .= ''.$agn_ctr_renewal['institute_code'].'|'.$agn_ctr_renewal['inst_name'].'|'.$agn_ctr_renewal['estb_year'].'|'.$agn_ctr_renewal['main_address1'].'|'.$agn_ctr_renewal['main_address2'].'|'.$agn_ctr_renewal['main_address3'].'|'.$agn_ctr_renewal['main_address4'].'|'.$agn_ctr_renewal['main_district'].'|'.$agn_ctr_renewal['main_city'].'|'.$agn_ctr_renewal['main_state'].'|'.$agn_ctr_renewal['main_pincode'].'|'.$agn_ctr_renewal['inst_phone'].'|'.$agn_ctr_renewal['inst_fax_no'].'|'.$agn_ctr_renewal['inst_website'].'|'.$agn_ctr_renewal['inst_head_name'].'|'.$agn_ctr_renewal['inst_head_contact_no'].'|'.$agn_ctr_renewal['inst_head_email'].'|'.$location_name.'|'.$agn_ctr_renewal['address1'].'|'.$agn_ctr_renewal['address2'].'|'.$agn_ctr_renewal['address3'].'|'.$agn_ctr_renewal['address4'].'|'.$agn_ctr_renewal['district'].'|'.$agn_ctr_renewal['city'].'|'.$agn_ctr_renewal['state'].'|'.$agn_ctr_renewal['pincode'].'|'.$agn_ctr_renewal['contact_person_name'].'|'.$agn_ctr_renewal['contact_person_mobile'].'|'.$agn_ctr_renewal['email_id'].'|'.$agn_ctr_renewal['due_diligence'].'|'.$agn_ctr_renewal['gstin_no'].'|'.$agn_ctr_renewal['center_type'].'|'.$date_of_approved.'|'.$center_validity_from.'|'.$center_validity_to.'|'.$trn_date.'|'.$agn_ctr_renewal['amount'].'|'.$agn_ctr_renewal['transaction_no']."\n";		
						
						/* 257|ILFS|2011|ILFS|MSDC|KAPURTHALA ROAD|JALANDHAR|JALANDHAR|JALANDHAR|PUN|144021|9779073467||www.ilfsindia.com|sofi Arshid|9906547771|sofi.arshid@ilfsindia.com|Amritsar|IIS AMRITSAR,|GURU HAR RAI PUBLIC SCHOOL|NR. GURU RAMDAS COLLEGE|GT RD. AMRITSAR - 143001|AMRITSAR|774|PUN|143001|Sofi Arshid|9906547771|sofi.arshid@ilfsindia.com|Yes|03AACCI5146G1ZX|T|04-May-2019|20-May-2019|17-Aug-2019|16-May-2019|3540.00|8873432674604
						*/
						$i++;
						$mem_cnt++;
					}
					$agn_ctr_renewal_flg = fwrite($fp, $data);
					if($agn_ctr_renewal_flg)
					$success['agn_ctr_renewal'] = "Agency Center Renewal. Details File Generated Successfully. ";
					else
					$error['agn_ctr_renewal'] = "Error While Generating Agency Center Renewal. Details File.";
					
					fwrite($fp1, "\n"."Total Agency Center Renewal.  Applications = ".$mem_cnt."\n");
				}
				else{
					$success[] = "No data found for the date";
				}
				fclose($fp);
				// Cron End Logs
				$end_time = date("Y-m-d H:i:s");
				$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
				$desc = json_encode($result);
				$this->log_model->cronlog("Agency Center Renewal. Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."********** Agency Center Renewal. Details Cron Execution End ".$end_time." **********"."\n");
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
			$success = array();
			$error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	
			
			$current_date = '20200611';
			
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
				
				$file1 = "logs_mem_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* IIBF New Member Details Cron Execution Started - ".$start_time." ********************* \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$this->db->where('DATE(a.createdon) >=', '2019-11-19');
				//$this->db->where('DATE(a.createdon) <=', '2019-11-19');
				$mem = array('801497342');
				$this->db->where_in('a.regnumber', $mem);
				//$exam_code = array('526');
				//$this->db->where_in('e.exam_code', $exam_code);
				//$this->db->where('e.pay_status', '1');
				//$this->db->where('e.exam_period', '999');
				
				//$this->db->join('member_exam e','e.regnumber = a.regnumber ','LEFT');
				$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0));
				
				// ,'DATE(createdon)'=>$yesterday
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
								
								//$this->db->where("( DATE(a.updated_date) = '".$yesterday."') AND a.status = 1 AND d.regnumber = '".$reg_data['regnumber']."'");
								
								$this->db->where("a.status = 1 AND d.regnumber = '".$reg_data['regnumber']."'");
								$trans_details = $this->Master_model->getRecords('bulk_payment_transaction a');	
								
								//$trans_details = $this->db->get('bulk_payment_transaction a');
							}
							
							if(empty($trans_details))
							{
								$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_ELR','status'=>1,'pay_type'=>18,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
							}
							
							if(empty($trans_details))
							{
								$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EX_ELR','status'=>1,'pay_type'=>18,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date');
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
			$cron_file_dir = "./uploads/cronFilesCustom/";
			
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF CSC Member Details Cron Execution Start", $desc);
			
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
				fwrite($fp1, "\n************************* IIBF CSC Member Details Cron Execution Started - ".$start_time." ********************* \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$yesterday = '2020-03-05';
				//$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0));	
				//$exam_period = array('998');
				//$this->db->where_in('a.exam_period', $exam_period);
				//$this->db->where('DATE(a.createdon) >=', '2020-01-16');
				//$this->db->where('DATE(a.createdon) <=', '2020-01-20');
				
				$mem = array(801506534,801506535,801506536,801506537,801506538,801506539,801506540,801506541,801506542,801506543,801506546,801506547,801506548,801506549,801506550,801506551,801506552,801506553,801506554,801506555,801506556,801506557,801506558,801506559,801506560,801506561,801506562,801506563,801506564,801506565,801506566,801506567,801506568,801506569,801506570,801506571,801506572,801506574,801506575,801506576,801506577,801506578,801506579,801506580,801506581,801506582,801506583,801506584,801506585,801506586,801506587,801506588,801506589,801506590,801506591,801506593,801506594,801506595,801506596,801506597,801506598,801506599,801506600,801506601,801506602,801506603,801506604,801506605,801506606,801506607,801506608,801506609,801506610,801506611,801506612,801506613,801506614,801506615,801506616,801506617,801506618,801506619,801506620,801506621,801506622,801506623,801506624,801506625,801506626,801506627,801506628,801506629,801506630,801506631,801506632,801506633,801506634,801506635,801506636,801506637,801506638,801506639,801506640,801506641,801506642,801506643,801506644,801506645,801506646,801506647,801506648,801506649,801506650,801506651,801506652,801506653,801506654,801506655,801506656,801506657,801506658,801506659,801506660,801506661,801506662,801506663,801506664,801506665,801506666,801506667,801506668,801506669,801506670,801506671,801506672,801506673,801506674,801506675,801506676,801506677,801506678,801506679,801506680,801506681,801506682,801506683,801506684,801506685,801506686,801506687,801506688,801506689,801506691,801506692,801506693,801506694,801506695,801506696,801506697,801506698);
				$this->db->where_in('a.regnumber', $mem);
				$this->db->join('payment_transaction b','b.member_regnumber = a.regnumber','LEFT');
				$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0, 'exam_code' => '991'));
				
				echo $this->db->last_query(); 
				//,'DATE(createdon)'=>$yesterday ,'bankcode' => 'csc'
				
				
				if(count($new_mem_reg))
				{
					$dirname = "regd_image_csc_".$current_date;
					$directory = $cron_file_path.'/'.$dirname;
					if(file_exists($directory))
					{
						array_map('unlink', glob($directory."/*.*"));
						rmdir($directory);
						$dir_flg = mkdir($directory, 0755);
					}
					else
					{
						$dir_flg = mkdir($directory, 0755);
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
				$this->log_model->cronlog("IIBF CSC Member Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* IIBF CSC Member Details Cron Execution End ".$end_time." **************************"."\n");
				fclose($fp1);
			}
		}
		
		
		// By VSU : Fuction to fetch user's edited data and export it in TXT format
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
			
			$cron_file_dir = "./uploads/rahultest/";
			
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("Edited Candidate Details Cron Execution Start", $desc);
			
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0755); 
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
				fwrite($fp1, "\n************************* Edited Candidate Details Cron Execution Started - ".$start_time."*********************\n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$today = date('Y-m-d');
				
				$this->db->where('DATE(editedon) >=', '2019-04-22');
				$this->db->where('DATE(editedon) <=', '2019-04-30');
				$edited_mem_data = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','isdeleted'=>0));
				// ' DATE(editedon)'=>$yesterday,
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
						
						$data .= $edited_by.'|'.$optnletter.'|N|N|N|'.date('d-M-y H:i:s',strtotime($editeddata['editedon'])).'|'.$editeddata['aadhar_card'].'|'.$editeddata['bank_emp_id']."\n";
						
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
				
				$this->db->where('DATE(images_editedon) >=', '2019-04-22');
				$this->db->where('DATE(images_editedon) <=', '2019-04-30');
				$edited_img_data = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','isdeleted'=>0));
				// 'DATE(images_editedon)'=>$yesterday,
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
						$dir_flg = mkdir($directory, 0755);
					}
					else
					{
						$dir_flg = mkdir($directory, 0755);
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
				fwrite($fp1, "\n************************* Edited Candidate Details Cron Execution Started - ".$start_time."*********************\n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$today = date('Y-m-d');
				//$this->db->where('DATE(editedon) >=', '2019-07-15');
				//$this->db->where('DATE(editedon) <=', '2019-07-29');
				$mem = array('510350936','500112922','510226129','510032176','500023548','801319542','510039144','801322255','510316121','510301343','510165104','510384178','510126221','801341019','510045605','510030496','510421897','510336457','500115035');
				$this->db->where_in('regnumber',$mem);
				$edited_mem_data = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','isdeleted'=>0));
				//,' DATE(editedon)'=>$yesterday,
				
				//echo "SQL 1 =>".$this->db->last_query();
				//exit;
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
						
						//echo "<br>DATA=>".$data;
						
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
				//$this->db->where('DATE(images_editedon) >=', '2019-07-01');
				//$this->db->where('DATE(images_editedon) <=', '2019-07-14');
				$mem = array('510350936','500112922','510226129','510032176','500023548','801319542','510039144','801322255','510316121','510301343','510165104','510384178','510126221','801341019','510045605','510030496','510421897','510336457','500115035');
				$this->db->where_in('regnumber',$mem);
				$edited_img_data = $this->Master_model->getRecords('member_registration',array('isactive'=>'1','isdeleted'=>0));
				//echo "<br>SQL 2 =>".$this->db->last_query();
				//exit;
				//,'DATE(images_editedon)'=>$yesterday
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
			}
		}
		
		// By VSU : Fuction to fetch duplicate i-card requests and export in TXT format
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
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0755); 
			}
			
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "iibf_dup_icard_details_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* Duplicate I-card Details Cron Execution Started - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$select = 'c.regnumber,c.reason_type,c.icard_cnt,a.registrationtype,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount,b.transaction_no';
				$this->db->where('DATE(c.added_date) >=', '2019-07-17');
				$this->db->where('DATE(c.added_date) <=', '2019-07-29');
				$this->db->join('member_registration a','a.regnumber=c.regnumber','LEFT');
				$this->db->join('payment_transaction b','b.ref_id=c.did','LEFT');
				$dup_icard_data = $this->Master_model->getRecords('duplicate_icard c',array('pay_type'=>3,'isactive'=>'1','status'=>'1','isdeleted'=>0),$select);
				// ' DATE(added_date)'=>$yesterday,
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
				$this->log_model->cronlog("Duplicate I-card Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* Duplicate I-card Details Cron Execution End ".$end_time." **************************"."\n");
				fclose($fp1);
			}
		}
		
		// By VSU : Fuction to fetch candidate exam application data and export in TXT format
		public function exam_old()
		{
			//$current_date = "";
			ini_set("memory_limit", "-1");
			
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$exam_file_flg = 0;
			$success = array();
			$error = array();
			
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	
			
			/*if($current_date == "")
				{
				$current_date = date("Ymd");	
			}*/
			
			$cron_file_dir = "./uploads/rahultest/"; // cronfiles
			
			//Cron start Logs
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("Candidate Exam Details Cron Execution Start", $desc);
			
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0755); 
			}
			
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "exam_cand_report_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* Candidate Exam Details Cron Execution Started - ".$start_time." ********************** \n");
				
				//$yesterday = date('Y-m-d', strtotime("- 1 day")); 
				$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ( $current_date) ));
				
				//$mem = array('801344811','801340725','801346688','801352553','801311668');
				$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag';
				/*$this->db->join('member_exam a','a.regnumber=c.regnumber','LEFT'); 
					$this->db->join('payment_transaction b','b.ref_id=a.id','LEFT');
					$can_exam_data = $this->Master_model->getRecords('member_registration c',array(' DATE(a.created_on)'=>$yesterday,'pay_type'=>2),$select);
				*/
				//$this->db->where_in('a.regnumber', $mem);
				$this->db->where('DATE(a.created_on) >=', '2019-06-14');
				$this->db->where('DATE(a.created_on) <=', '2019-06-30');
				$this->db->where_in('a.exam_period', '998');
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
				$can_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1),$select);
				// ' DATE(a.created_on)'=>$yesterday,
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
						//$exam['elected_sub_code']!=0
						//if($exam_code == 60 || $exam_code == 21) // For CAIIB & JAIIB
						if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == 68 || $exam_code == 69 || $exam_code == 70 || $exam_code == 71 || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) // For CAIIB, CAIIB Elective & JAIIB, above line commented and this line added by Bhagwan Sahane, on 06-10-2017
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
							
							$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$exam['scribe_flag']."\n";
						}
						else
						{
							$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$exam['scribe_flag']."\n";
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
				
				fwrite($fp1, "\n"."************************* Candidate Exam Details Cron Execution End ".$end_time." **************************"."\n");
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
				//$yesterday = "2020-02-07"; 
				$mem = array('801465397','801465399','801465401','801465410','801465413','801465422','801465425','801465427','801465433','801465436','801465439','801465447','801465362');
				$select = 'a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,c.regnumber,DATE_FORMAT(a.created_on,"%Y-%m-%d") created_on,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag';
				$this->db->where_in('a.regnumber',$mem );
				$this->db->where_in('a.exam_code', array('191','1910','19100')); // ,'1910','19100'
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
				$can_exam_data = $this->Master_model->getRecords('member_exam a',array('isactive'=>'1','isdeleted'=>0,'a.pay_status'=>1),$select);
				//'DATE(a.created_on)'=>$yesterday,
				
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
		public function exam_test()
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
				$yesterday = "2020-02-07";
				$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag';
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
				$can_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'a.exam_code !='=>'991','pay_type'=>2),$select);
				
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
						if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == $this->config->item('examCodeCaiibElective63') || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == 68 || $exam_code == 69 || $exam_code == 70 || $exam_code == 71 || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) // For CAIIB, CAIIB Elective & JAIIB, above line commented and this line added by Bhagwan Sahane, on 06-10-2017
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
							
							$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$scribe_flag."\n";
						}
						else
						{
							$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$scribe_flag."\n";
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
				$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag,e.exam_date';
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber = c.regnumber','LEFT'); 
				$this->db->join('admit_card_details e','a.id = e.mem_exam_id','LEFT'); 
				$can_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1,'a.exam_code'=>'991'),$select);
				
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
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0755); 
			}
			
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "exam_cand_report_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_907_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* Candidate Exam Details Cron Execution Started - ".$start_time." ********************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day")); 
				$refid = array('4916383');
				//$mem = array('801345005');
				$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,a.examination_date,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work,c.editedon,c.branch,c.office,c.city,c.state,c.pincode,a.scribe_flag';
				
				//$this->db->where_in('a.regnumber', $mem);
				$this->db->where_in('a.id', $refid);
				//$this->db->where_in('a.exam_period', array('998','562','910'));
				//$this->db->where_in('a.exam_code', array('991','148')); 
				$this->db->where_in('pay_type', array('2','18')); 
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
				$can_exam_data = $this->Master_model->getRecords('member_exam a',array('status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1),$select);
				// 'DATE(a.created_on)'=>$yesterday,
				// 'pay_type'=>2,
				echo $this->db->last_query();
				// exit;
				if(count($can_exam_data))
				{
					
					$i = 1;
					$exam_cnt = 0;
					foreach($can_exam_data as $exam)
					{
						$data = '';
						//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,SCRIBE_FLG
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
						if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == 63 || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == 68 || $exam_code == 69 || $exam_code == 70 || $exam_code == 71 || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) // For CAIIB, CAIIB Elective & JAIIB, above line commented and this line added by Bhagwan Sahane, on 06-10-2017
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
							
							$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$exam['scribe_flag']."\n";
						}
						else
						{
							$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no'].'|'.$exam['scribe_flag']."\n";
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
				
				fwrite($fp1, "\n"."************************* Candidate Exam Details Cron Execution End ".$end_time." **************************"."\n");
				fclose($fp1);
			}
		}
		
		
		/* DRA Members Cron */
		public function dra_member_old()
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
				
				$file1 = "logs_dra_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* New DRA Candidate Details Cron Execution Started - ".$start_time." ***************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				
				//$yesterday = '2019-09-01';
				
				//$yesterday = date('Y-m-d', strtotime("- 1 day"));
				
				/*$this->db->select("namesub, firstname, middlename, lastname, regnumber, gender, scannedphoto, scannedsignaturephoto, idproofphoto, training_certificate, quali_certificate, qualification,registrationtype, address1, address2, city, district, pincode, state, dateofbirth, email, stdcode, phone, mobile, aadhar_no, idproof, a.transaction_no, DATE(a.date) date, DATE(a.updated_date) updated_date, a.UTR_no, a.amount, a.gateway, d.image_path, d.registration_no");
					$this->db->join('dra_member_payment_transaction b','a.id = b.ptid','LEFT');
					$this->db->join('dra_member_exam c','b.memexamid = c.id','LEFT');
					$this->db->join('dra_members d','d.regid = c.regid','LEFT');
					$this->db->where("DATE(a.updated_date) = '".$yesterday."' AND 'isdeleted'= 0 AND a.status = 1 AND new_reg = 1 ");
					$new_dra_reg = $this->Master_model->getRecords('dra_payment_transaction a');
				*/
				$mem = array('801405640','801405641','801405642','801405643','801405644','801405645','801405646','801405647','801405648','801405650','801406348','801406349','801406350','801406351','801406352','801406353','801406354','801406355','801406356','801406357','801406358','801406359','801406360','801406361','801406362','801406363','801406364','801406365','801406366','801406367','801406368','801406369','801406370','801406371','801406372','801406373','801406374','801406375','801406376','801406377','801406378','801406379','801406380','801406381','801406382','801406383','801406384','801406385','801406386','801406387','801406388','801406389','801406390','801406391','801406392','801406393','801406394','801406395','801406396','801406397','801406398','801406399','801406400','801406401','801406402','801406403','801406404','801406405','801406406','801406407','801406408','801406409','801406410','801406411','801406412');
				$this->db->where_in('d.regnumber', $mem);
				$this->db->select("namesub, firstname, middlename, lastname, regnumber, gender, scannedphoto, scannedsignaturephoto, idproofphoto, training_certificate, quali_certificate, qualification,registrationtype, address1, address2, city, district, pincode, state, dateofbirth, email, stdcode, phone, mobile, aadhar_no, idproof, a.transaction_no, DATE(a.date) date, DATE(a.updated_date) updated_date, a.UTR_no, a.amount, a.gateway, d.image_path, d.registration_no");
				$this->db->join('dra_member_payment_transaction b','a.id = b.ptid','LEFT');
				$this->db->join('dra_member_exam c','b.memexamid = c.id','LEFT');
				$this->db->join('dra_members d','d.regid = c.regid','LEFT');
				//$this->db->where("DATE(a.updated_date) = '".$yesterday."') AND isdeleted = 0 AND a.status = 1");
				$new_dra_reg = $this->Master_model->getRecords('dra_payment_transaction a', array('isdeleted' => '0' ,'a.status' => '1'));
				
				//echo $this->db->last_query();//exit;
				
				//AND d.new_reg = 'NEW' 
				//$this->db->where("'isdeleted'= 0 AND a.status = 1");
				//$this->db->where('a.exam_period', '200');
				//$this->db->where('a.exam_code', '45');
				//$this->db->where('DATE(a.updated_date) >=', '2019-05-30');
				//$this->db->where('DATE(a.updated_date) <=', '2019-06-15');
				//$this->db->limit(1);
				
				
				
				//AND re_attempt = 0
				//$this->db->where("( DATE(a.date) = '".$yesterday."'  OR DATE(a.updated_date) = '".$yesterday."') AND 'isdeleted'= 0 AND a.status = 1");
				
				
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
				$this->log_model->cronlog("New DRA Candidate Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* New DRA Candidate Details Cron Execution End ".$end_time." **************************"."\n");
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
				fwrite($fp1, "\n************************* New DRA Candidate Details Cron Execution Started - ".$start_time." ***************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$mem = array(801478772);
				$this->db->where_in('d.regnumber', $mem);
				$this->db->select("namesub, firstname, middlename, lastname, regnumber, gender, scannedphoto, scannedsignaturephoto, idproofphoto, training_certificate, quali_certificate, qualification,registrationtype, address1, address2, city, district, pincode, state, dateofbirth, email, stdcode, phone, mobile, aadhar_no, idproof, a.transaction_no, DATE(a.date) date, DATE(a.updated_date) updated_date, a.UTR_no, a.amount, a.gateway, d.image_path, d.registration_no");
				$this->db->join('dra_member_payment_transaction b','a.id = b.ptid','LEFT');
				$this->db->join('dra_member_exam c','b.memexamid = c.id','LEFT');
				$this->db->join('dra_members d','d.regid = c.regid','LEFT');
				//$this->db->where("DATE(a.updated_date) = '".$yesterday."' AND 'isdeleted'= 0 AND a.status = 1 AND d.new_reg = 1 ");
				$this->db->where("'isdeleted'= 0 AND a.status = 1 ");
				$this->db->group_by('regnumber'); 
				$new_dra_reg = $this->Master_model->getRecords('dra_payment_transaction a');
				
				echo $this->db->last_query();
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
				$this->log_model->cronlog("New DRA Candidate Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* New DRA Candidate Details Cron Execution End ".$end_time." **************************"."\n");
				fclose($fp1);
			}
		}
		
		/*  DRA Members Update Data */
		public function dra_member_update()
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
			$this->log_model->cronlog("DRA Candidate update Details Cron Execution Start", $desc);
			
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "dra_member_update_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* DRA Member Update Details Cron Execution Started - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day")); 
				
				$this->db->select("regnumber,new_email,new_address1,new_address2,new_address3,new_address4,created_on");
				$this->db->where("DATE(created_on) = '".$yesterday."'");
				$member_data = $this->Master_model->getRecords('dra_mem_logs');
				
				if(count($member_data))
				{
					$data = '';
					$i = 1;
					$cnt = 0;
					
					foreach($member_data as $row)
					{	
						$new_address1 = $new_address2 = $new_address3= $new_address4 ='';
						
						if(strlen($row['new_address1']) > 30)
						{	$new_address1 = substr($row['new_address1'],0,29);	}
						else
						{	$new_address1 = $row['new_address1'];	}
						
						if(strlen($row['new_address2']) > 30)
						{	$new_address2 = substr($row['new_address2'],0,29);	}
						else
						{	$new_address2 = $row['new_address2'];	}
						
						if(strlen($row['new_address3']) > 30)
						{	$new_address3 = substr($row['new_address3'],0,29);	}
						else
						{	$new_address3 = $row['new_address3'];	}
						
						if(strlen($row['new_address4']) > 30)
						{	$new_address4 = substr($row['new_address4'],0,29);	}
						else
						{	$new_address4 = $row['new_address4'];	}
						
						
						$data .= ''.$row['regnumber'].'|'.$row['new_email'].'|'.$new_address1.'|'.$new_address2.'|'.$new_address3.'|'.$new_address4."\n";
						
						$cnt++;
					}
					$i++;
					fwrite($fp1, "\nTotal DRA Member Update Count - ".$cnt."\n");
					
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
					$success['DRA_exam'] = "DRA Member Update Details File Generated Successfully."; 
					else
					$error['DRA_exam'] = "Error While Generating DRA Member Update Details File.";
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
				$this->log_model->cronlog("DRA Member Update Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* DRA Member Update Details Cron Execution End ".$end_time." **************************"."\n");
				fclose($fp1);
			}
		}
		
		// By VSU : Fuction to fetch candidate exam application data and export in TXT format
		public function dra_exam_old()
		{
			//$current_date = ""
			ini_set("memory_limit", "-1");
			
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$exam_file_flg = 0;
			$success = array();
			$error = array();
			
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	
			
			/*if($current_date == "")
				{
				$current_date = date("Ymd");	
			}*/
			
			$cron_file_dir = "./uploads/rahultest/"; // cronfiles
			
			//Cron start Logs
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("DRA Candidate Exam Details Cron Execution Start", $desc);
			
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0755); 
			}
			
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "dra_exam_cand_report_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* DRA Candidate Exam Details Cron Execution Started - ".$start_time." ******************** \n");
				
				//$yesterday = date('Y-m-d', strtotime("- 1 day")); 
				$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ( $current_date) ));
				
				$this->db->where("DATE(updated_date) >=", "2019-06-01");
				$this->db->where("DATE(updated_date) <=", "2019-06-13");
				$this->db->where("status = 1");
				//$this->db->where("( DATE(date) = '".$yesterday."'  OR DATE(updated_date) = '".$yesterday."') AND status = 1");
				$dra_payment = $this->Master_model->getRecords('dra_payment_transaction a');
				//echo $this->db->last_query();
				if(count($dra_payment))
				{
					/*echo "<pre>";
					print_r($can_exam_data);*/
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
								
								//if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
								//else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
								
								$exam_mode = "";
								//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,	TRNG_INS_CD,trng_from,TRNG_TO
								
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
								
								
								
								$data .= ''.$exam['exam_code'].'|'.$exam['exam_period'].'|'.$reg_num.'|'.$reg_type.'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$transaction_no.'|'.$trans_date.'|'.$payment['amount'].'|'.$transaction_no.'|'.$payment['inst_code'].'|'.$training_from.'|'.$training_to."\n";
								
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
				$this->log_model->cronlog("DRA Candidate Exam Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* DRA Candidate Exam Details Cron Execution End ".$end_time." **************************"."\n");
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
				fwrite($fp1, "\n************************* DRA Candidate Exam Details Cron Execution Started - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day")); 
				
				//$this->db->where("( DATE(date) = '".$yesterday."'  OR DATE(updated_date) = '".$yesterday."') AND status = 1");
				
				/*$this->db->where("DATE(updated_date) >=", "2019-05-24");
				$this->db->where("DATE(updated_date) <=", "2019-05-29");*/
				//$this->db->where("DATE(updated_date) = '".$yesterday."' AND status = 1");
				$this->db->where('exam_period', '200');
				$this->db->where("status = 1");
				$dra_payment = $this->Master_model->getRecords('dra_payment_transaction');
				//echo $this->db->last_query();
				if(count($dra_payment))
				{
					/*echo "<pre>";
					print_r($can_exam_data);*/
					
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
								
								$exam_mode = "";
								if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
								else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
								
								//EXM_CD,EXM_PRD,MEM_NO,MEM_TYP,MED_CD,CTR_CD,ONLINE_OFFLINE_YN,SYLLABUS_CODE,CURRENCY_CODE,EXM_PART,SUB_CD,PLACE_OF_WORK,POW_PINCD,POW_STE_CD,BDRNO,TRN_DATE,FEE_AMT,INSTRUMENT_NO,	TRNG_INS_CD,trng_from,TRNG_TO
								
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
								
								$data .= ''.$exam['exam_code'].'|'.$exam['exam_period'].'|'.$reg_num.'|'.$reg_type.'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$transaction_no.'|'.$trans_date.'|'.$payment['amount'].'|'.$transaction_no.'|'.$payment['inst_code'].'|'.$training_from.'|'.$training_to."\n";
								
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
				$this->log_model->cronlog("DRA Candidate Exam Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* DRA Candidate Exam Details Cron Execution End ".$end_time." **************************"."\n");
				fclose($fp1);
			}
		}
		
		// Fuction to fetch duplicate certificate registrations and export in TXT format
		public function dup_cert_old()
		{
			ini_set("memory_limit", "-1");
			
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$dup_cert_flg = 0;
			$success = array();
			$error = array();
			
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	
			$cron_file_dir = "./uploads/rahultest/";
			
			// Cron start Logs
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("Duplicate Certificate Details Cron Execution Start", $desc);
			
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0755); 
			}
			
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "dup_cert_details_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* Duplicate Certificate Details Cron Execution Started - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2017-08-21';
				
				// get duplicate certificate registration details for given date
				$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
				$this->db->where('DATE(c.created_on) >=', '2019-04-01');
				$this->db->where('DATE(c.created_on) <=', '2019-04-30');
				$this->db->join('payment_transaction b','b.ref_id=c.id','LEFT');
				$dup_cert_data = $this->Master_model->getRecords('duplicate_certificate c',array('pay_type' => 4,'pay_status' => 1,'status' => '1'),$select);
				// ' DATE(created_on)' => $yesterday,
				//echo $this->db->last_query(); exit;
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
				$this->log_model->cronlog("Duplicate Certificate Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* Duplicate Certificate Details Cron Execution End ".$end_time." **************************"."\n");
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
				fwrite($fp1, "\n************************* Duplicate Certificate Details Cron Execution Started - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2017-08-21';
				$ref = array('22610');
				// get duplicate certificate registration details for given date
				$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
				$this->db->join('payment_transaction b','b.ref_id=c.id','LEFT');
				//$this->db->where('DATE(c.created_on) >=', '2019-10-01');
				//$this->db->where('DATE(c.created_on) <=', '2019-10-01');
				$this->db->where_in('b.ref_id', $ref);
				$dup_cert_data = $this->Master_model->getRecords('duplicate_certificate c',array('pay_type' => 4,'pay_status' => 1,'status' => '1'),$select);
				
				// ' DATE(created_on)' => $yesterday,
				//echo $this->db->last_query(); exit;
				
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
				$this->log_model->cronlog("Duplicate Certificate Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* Duplicate Certificate Details Cron Execution End ".$end_time." **************************"."\n");
				fclose($fp1);
			}
		}
		
		// Fuction to fetch Renewal Member data and export it in TXT format, added By Bhagwan Sahane, on 07-09-2017
		public function renewal_old()
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
			$this->log_model->cronlog("IIBF Renewal Member Details Cron Execution Start", $desc);
			
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0755); 
			}
			
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "renewal_mem_details_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* IIBF Renewal Member Details Cron Execution Started - ".$start_time." ********************* \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2017-09-07';
				
				//$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
				$this->db->where('DATE(a.createdon) >=', '2019-04-01');
				$this->db->where('DATE(a.createdon) <=', '2019-06-13');
				$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0, 'is_renewal' => 1));
				// ' DATE(createdon)'=>$yesterday,
				//echo $this->db->last_query();
				if(count($new_mem_reg))
				{
					/*echo "<pre>";
					print_r($new_mem_reg);*/
					
					//$data = '';
					
					//echo $cron_file_path.'/'.$file;
					$dirname = "renewal_image_".$current_date;
					$directory = $cron_file_path.'/'.$dirname;
					if(file_exists($directory))
					{
						array_map('unlink', glob($directory."/*.*"));
						rmdir($directory);
						$dir_flg = mkdir($directory, 0755);
					}
					else
					{
						$dir_flg = mkdir($directory, 0755);
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
				$this->log_model->cronlog("IIBF Renewal Member Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* IIBF Renewal Member Details Cron Execution End ".$end_time." **************************"."\n");
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
			$this->log_model->cronlog("IIBF Renewal Member Details Cron Execution Start", $desc);
			
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0755); 
			}
			
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "renewal_mem_details_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* IIBF Renewal Member Details Cron Execution Started - ".$start_time." ********************* \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2017-09-07';
				
				//$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
				$mem = array('7506980','7259351','6951801','6670168','5935492','5665539','5503441','5457447','5291297','5183431','4164496','3526438','3476659');
				//$this->db->where('DATE(a.createdon) >=', '2019-09-19');
				//$this->db->where('DATE(a.createdon) <=', '2019-09-19');
				$this->db->where_in('a.regnumber', $mem);
				$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0, 'is_renewal' => 1));
				// ' DATE(createdon)'=>$yesterday,
				//echo $this->db->last_query();
				if(count($new_mem_reg))
				{
					/*echo "<pre>";
					print_r($new_mem_reg);*/
					
					//$data = '';
					
					//echo $cron_file_path.'/'.$file;
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
				$this->log_model->cronlog("IIBF Renewal Member Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* IIBF Renewal Member Details Cron Execution End ".$end_time." **************************"."\n");
				fclose($fp1);
			}
		}
		
		// Admit Card
		public function admit_card_old_old()
		{
			ini_set("memory_limit", "-1");
			
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$file_w_flg = 0;
			$success = array();
			$error = array();
			
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	
			$cron_file_dir = "./uploads/rahultest/";
			
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF Exam Admit Card Details Cron Execution Start", $desc);
			
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0755); 
			}
			
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "admit_card_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* IIBF Exam Admit Card Details Cron Execution Started - ".$start_time." ************************* \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2017-08-04';
				
				// get member exam application details for given date
				$select = 'a.id as mem_exam_id, a.examination_date';
				$this->db->where('DATE(a.created_on) >=', '2019-06-01');
				$this->db->where('DATE(a.created_on) <=', '2019-06-30');
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$cand_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'pay_status'=>1),$select);
				// ' DATE(a.created_on)'=>$yesterday,
				//echo "<br>SQL => ".$this->db->last_query(); die();
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
						//echo "<br>SQL => ".$this->db->last_query(); die();
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
								}
								
								$venueadd1 = '';
								if($admit_card_data['venueadd1'] != '')
								{
									$venueadd1 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd1']));
								}
								
								$venueadd2 = '';
								if($admit_card_data['venueadd2'] != '')
								{
									$venueadd2 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd2']));
								}
								
								$venueadd3 = '';
								if($admit_card_data['venueadd3'] != '')
								{
									$venueadd3 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd3']));
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
									//$exam_period = $exam['exam_period'];
									$exam_period = $admit_card_data['exm_prd'];
								}
								// eof code to get actual exam period for exam application, added by Bhagwan Sahane, on 28-09-2017
								
								// code rewrite exam code, added by Bhagwan Sahane, on 13-10-2017
								$exam_code = '';
								if($admit_card_data['exm_cd'] == 340 || $admit_card_data['exm_cd'] == 3400)
								{
									$exam_code = 34;
								}
								elseif($admit_card_data['exm_cd'] == 580 || $admit_card_data['exm_cd'] == 5800)
								{
									$exam_code = 58;
								}
								elseif($admit_card_data['exm_cd'] == 1600 || $admit_card_data['exm_cd'] == 16000)
								{
									$exam_code = 160;
								}
								elseif($admit_card_data['exm_cd'] == 200)
								{
									$exam_code = 20;
								}elseif($admit_card_data['exm_cd'] == 1770 || $admit_card_data['exm_cd'] == 17700)
								{
									$exam_code =177;
								}
								elseif ($admit_card_data['exm_cd'] == 590)
								{
									$exam_code = 59;
								}
								elseif ($admit_card_data['exm_cd'] == 810)
								{
									$exam_code = 81;
								}
								elseif ($admit_card_data['exm_cd'] == 1750)
								{
									$exam_code = 175;
								}
								else
								{
									$exam_code = $admit_card_data['exm_cd'];
								}
								// eof code rewrite exam code, added by Bhagwan Sahane, on 13-10-2017
								
								#EXM_CD|EXM_PRD|MEMBER_NO|CTR_CD|CTR_NAM|SUB_CD|SUB_DSC|VENUE_CD|VENUE_ADDR1|VENUE_ADDR2|VENUE_ADDR3|VENUE_ADDR4|VENUE_ADDR5|VENUE_PINCODE|EXAM_SEAT_NO|EXAM_PASSWORD|EXAM_DATE|EXAM_TIME|EXAM_MODE(Online/Offline)| EXAM_MEDIUM|SCRIBE_FLG(Y/N)|VENDOR_CODE(1/3)|TRN_DATE|VENUE_NAME
								
								# 60|119|510326276|110|JAMNAGAR|160|ADVANCED BANK MANAGEMENT|361008|SHRI A.K. DOSHI MAHILA COLLEGE CAMPUS, BEHIND AARAM HOTEL, NEAR D.K.V. CIRCLE|JAMNAGAR|GUJARAT|||361008|059|LQM2UH|02-Jun-19|11.15 AM|Online|E|N|3|01-May-19|BHAVANS SHRI H.J.DOSHI INFORMATION TECHNOLOGY INSTITUTE							
								
								/*$data .= ''.$admit_card_data['exm_cd'].'|'.$admit_card_data['exm_prd'].'|'.$admit_card_data['mem_mem_no'].'|'.$admit_card_data['center_code'].'|'.$admit_card_data['center_name'].'|'.$admit_card_data['sub_cd'].'|'.$admit_card_data['sub_dsc'].'|'.$admit_card_data['venueid'].'|'.$admit_card_data['venueadd1'].'|'.$admit_card_data['venueadd2'].'|'.$admit_card_data['venueadd3'].'|'.$admit_card_data['venueadd4'].'|'.$admit_card_data['venueadd5'].'|'.$admit_card_data['venpin'].'|'.$admit_card_data['seat_identification'].'|'.$admit_card_data['pwd'].'|'.$exam_date.'|'.$admit_card_data['time'].'|'.$admit_card_data['mode'].'|'.$admit_card_data['m_1'].'|'.$admit_card_data['scribe_flag'].'|'.$admit_card_data['vendor_code'].'|'.$trn_date."|\n";*/
								
								//$data .= ''.$admit_card_data['exm_cd'].'|'.$admit_card_data['exm_prd'].'|'.$admit_card_data['mem_mem_no'].'|'.$admit_card_data['center_code'].'|'.$admit_card_data['center_name'].'|'.$admit_card_data['sub_cd'].'|'.$admit_card_data['sub_dsc'].'|'.$admit_card_data['venueid'].'|'.$venueadd1.'|'.$venueadd2.'|'.$venueadd3.'|'.$admit_card_data['venueadd4'].'|'.$admit_card_data['venueadd5'].'|'.$admit_card_data['venpin'].'|'.$admit_card_data['seat_identification'].'|'.$admit_card_data['pwd'].'|'.$exam_date.'|'.$admit_card_data['time'].'|'.$admit_card_data['mode'].'|'.$admit_card_data['m_1'].'|'.$admit_card_data['scribe_flag'].'|'.$admit_card_data['vendor_code'].'|'.$trn_date."|\n";
								
								//$data .= ''.$admit_card_data['exm_cd'].'|'.$exam_period.'|'.$admit_card_data['mem_mem_no'].'|'.$admit_card_data['center_code'].'|'.$admit_card_data['center_name'].'|'.$admit_card_data['sub_cd'].'|'.$admit_card_data['sub_dsc'].'|'.$admit_card_data['venueid'].'|'.$venueadd1.'|'.$venueadd2.'|'.$venueadd3.'|'.$admit_card_data['venueadd4'].'|'.$admit_card_data['venueadd5'].'|'.$admit_card_data['venpin'].'|'.$admit_card_data['seat_identification'].'|'.$admit_card_data['pwd'].'|'.$exam_date.'|'.$admit_card_data['time'].'|'.$admit_card_data['mode'].'|'.$admit_card_data['m_1'].'|'.$admit_card_data['scribe_flag'].'|'.$admit_card_data['vendor_code'].'|'.$trn_date."|\n";
								
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$admit_card_data['mem_mem_no'].'|'.$admit_card_data['center_code'].'|'.$admit_card_data['center_name'].'|'.$admit_card_data['sub_cd'].'|'.$admit_card_data['sub_dsc'].'|'.$admit_card_data['venueid'].'|'.$venueadd1.'|'.$venueadd2.'|'.$venueadd3.'|'.$admit_card_data['venueadd4'].'|'.$admit_card_data['venueadd5'].'|'.$admit_card_data['venpin'].'|'.$admit_card_data['seat_identification'].'|'.$admit_card_data['pwd'].'|'.$exam_date.'|'.$admit_card_data['time'].'|'.$admit_card_data['mode'].'|'.$admit_card_data['m_1'].'|'.$admit_card_data['scribe_flag'].'|'.$admit_card_data['vendor_code'].'|'.$trn_date.'|'.$venue_name."\n";
								
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
				$this->log_model->cronlog("IIBF Exam Admit Card Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* IIBF Exam Admit Card Details Cron Execution End ".$end_time." *************************"."\n");
				fclose($fp1);
			}
		}
		
		/* Admit Card Cron */
		public function admit_card_old_fun()
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
			$this->log_model->cronlog("IIBF Exam Admit Card Details Cron Execution Start", $desc);
			
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
				fwrite($fp1, "\n************************* IIBF Exam Admit Card Details Cron Execution Started - ".$start_time." ************************* \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				
				//$yesterday = '2019-10-10';
				
				// get member exam application details for given date
				//$this->db->where('DATE(a.created_on) >=', '2019-07-01');
				//$this->db->where('DATE(a.created_on) <=', '2019-07-01');
				//$this->db->where('a.exam_code','21');
				//$this->db->where('a.exam_period','219');
				
				//$this->db->limit(1);
		    
				$mem = array('801425304','801425305','801425306','801425307','801425308','801425309','801425310','801425311','801425312','801425313','801425314','801425315','801425316','801425317','801425318','801425319','801425320','801425321','801425322','801425323','801425324','801425325','801425326','801425327','801425328','801425329','801425330','801425331','801425332','801425333','801425334','801425335','801425336','801425337','801425338','801425339','801425340','801425341','801425342','801425343','801425344','801425345','801425346','801425347','801425348','801425349','801425350','801425351','801425352','801425353','801425354','801425355','801425356','801425357','801425358','801425359','801425360','801425361');
				$select = 'a.id as mem_exam_id, a.examination_date,b.transaction_no';
				$this->db->where('DATE(a.created_on) >=', '2019-11-24');
				$this->db->where('DATE(a.created_on) <=', '2019-11-24');
				//$this->db->where('a.exam_code','580');
				//$this->db->where('a.exam_period','501');
				$this->db->where_not_in('a.regnumber',$mem);
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$cand_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'pay_status'=>1),$select);
				// ' DATE(a.created_on)'=>$yesterday,
				
				// 
				//echo "<br>SQL => ".$this->db->last_query(); exit;
				
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
						//echo "<br>SQL => ".$this->db->last_query(); die();
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
				$this->log_model->cronlog("IIBF Exam Admit Card Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* IIBF Exam Admit Card Details Cron Execution End ".$end_time." *************************"."\n");
				fclose($fp1);
			}
		}
		
		/* Admit Card Cron */
		public function admit_card_old()
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
				//$yesterday = '2017-08-04';
				
				// get member exam application details for given date
				$mem = array('500134642','100016139','500034674','510426381','510033860','510332617','500194381','500166037','510296783','510210036','510032241','510152843','510081416','510121536','510449201','510214155','510274717','801109621','510333766','510151297','510430693','510015536','500015121','510363024','500100120','510135222','510056245','510262003','510015677','6572748','510333215','510427164','500180643','510198998','510100973','500166799','510241642','510339278','510178982','510369208','510126871','510142540','500197691','500074360','510291270','510138771','510133795','510427805','510243277','500193220','510214911','510033526','510451599','510449143','100042200','510246070','510171766','510316432','510057382','510055270','510173966','510280633','100016273','500155826','510251757','100017136','510043030','510057500','510260668','510408500','500159647','510383540','500129403','510370146','500214279','500198255','7213057','510240517','510059888','510011735','500055100','510112835','510039273','500179669','500158829','510316493','510191386','510185503','510105716','500090399','510131266','510262289','510359028','500179764','510288288','510408771','510216719','510197669','500092835','510415865','510291707','500173803','510199977','510301864','510314697','510418918','510072316','5140935','510057570','510404618','510059082','510286513','500076019','801456025','510381530','510158727','510112364','510166179','510033292','510275016','510079096','500169399','510103491','510359464','510435487','6922096','500023117','510271165','510102549','200068174','510389438','200076603','510451654','510053064','100026458','510226010','510145207','510437504','500149501','510144111','510126242','500206597','510126243','510203400','510339366','510146009','510176083','510048911','510131201','500121813','500059934','510234883','500096277','7588125','510200875','500054332','500147732','510318164','510322506','510341058','200026236','510304280','510117009','510255726','510112719','500212514','510325789','500133617','510389782');
				$select = 'a.id as mem_exam_id, a.examination_date,b.transaction_no';
				//$this->db->where('DATE(a.created_on) >=', '2019-11-29');
				//$this->db->where('DATE(a.created_on) <=', '2019-12-04');
				$this->db->where_in('a.regnumber', $mem);
				//$this->db->where_in('b.receipt_no', array('902034533'));
				$this->db->where_in('a.exam_period', array('912'));
				$this->db->where_in('a.exam_code', array('1770')); 
				$this->db->where_in('pay_type', array('2')); // imp
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$cand_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'pay_status'=>1),$select);
				// ' DATE(a.created_on)'=>$yesterday,
				echo $this->db->last_query();
				
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
		public function admit_card()
		{
			//echo '**123';
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
				//$yesterday = '2017-08-04';
				
				// get member exam application details for given date
				$mem = array(801274694,801489089,801489872,801491868);
				$select = 'a.id as mem_exam_id, a.examination_date,b.transaction_no';
				$this->db->where_in('a.regnumber', $mem);
				//$this->db->where_in('a.exam_period', array('998'));
				//$this->db->where_in('a.exam_code', array('1003')); 
				//$this->db->where_in('pay_type', array('2')); // imp
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$cand_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'pay_status'=>1),$select);
				echo $this->db->last_query();
				
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
		
		// Fuction to fetch BankQuest registrations and export in TXT format
		public function bankquest_old()
		{
			ini_set("memory_limit", "-1");
			
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$cpd_flg = 0;
			$success = array();
			$error = array();
			
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	
			$cron_file_dir = "./uploads/rahultest/";
			
			// Cron start Logs
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("BankQuest Details Cron Execution Start", $desc);
			
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0755); 
			}
			
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "bankquest_details_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* BankQuest Details Cron Execution Started - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				
				// get BankQuest registration details for given date
				$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
				$this->db->where('DATE(c.created_on) >=', '2019-05-01');
				$this->db->where('DATE(c.created_on) <=', '2019-05-31');
				$this->db->join('payment_transaction b','b.ref_id=c.bv_id','LEFT');
				$bankquest_data = $this->Master_model->getRecords('bank_vision c',array('pay_type' => 6,'pay_status' => 1,'status' => '1'),$select);
				// ' DATE(created_on)' => $yesterday,
				//echo $this->db->last_query(); die();
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
						
						
						if(strlen($bq_reg['address_1']) > 30)
						{	$address_1 = substr($bq_reg['address_1'],0,29);	}
						else
						{	$address_1 = $bq_reg['address_1'];	}
						
						if(strlen($bq_reg['address_2']) > 30)
						{	$address_2 = substr($bq_reg['address_2'],0,29);	}
						else
						{	$address_2 = $bq_reg['address_2'];	}
						
						if(strlen($bq_reg['address_3']) > 30)
						{	$address_3 = substr($bq_reg['address_3'],0,29);	}
						else
						{	$address_3 = $bq_reg['address_3'];	}
						
						if(strlen($bq_reg['address_4']) > 30)
						{	$address_4 = substr($bq_reg['address_4'],0,29);	}
						else
						{	$address_4 = $bq_reg['address_4'];	}
						
						
						
						$PRD_TYPE = 'BQ';	// BQ for BankQuest
						
						$data .= ''.$bq_reg['namesub'].'|'.$bq_reg['fname'].'|'.$bq_reg['mname'].'|'.$bq_reg['lname'].'|'.$bq_reg['gender'].'|'.$bq_reg['email_id'].'|'.$bq_reg['contact_no'].'|'.$address_1.'|'.$address_2.'|'.$address_3.'|'.$address_4.'|'.$bq_reg['city'].'|'.$bq_reg['state'].'|'.$bq_reg['pincode'].'|'.$bq_reg['subscription_no'].'|'.$bq_reg['amount'].'|'.$subscription_from_date.'|'.$subscription_to_date.'|'.$bq_reg['transaction_no'].'|'.$trn_date.'|'.$PRD_TYPE."\n";
						
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
				$this->log_model->cronlog("BankQuest Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* BankQuest Details Cron Execution End ".$end_time." **************************"."\n");
				fclose($fp1);
			}
		}
		
		
		// BankQuest Registrations Cron */
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
				fwrite($fp1, "\n************************* BankQuest Details Cron Execution Started - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				
				// get BankQuest registration details for given date
				//$ref_id = array('2600');
				$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
				//$this->db->where_in('c.bv_id', $ref_id);
				$this->db->join('payment_transaction b','b.ref_id=c.bv_id','LEFT');
				//$this->db->where('DATE(c.created_on) >=', '2019-05-01');
				//$this->db->where('DATE(c.created_on) <=', '2019-05-30');
				$bankquest_data = $this->Master_model->getRecords('bank_vision c',array(' DATE(created_on)' => $yesterday,'pay_type' => 6,'pay_status' => 1,'status' => '1'),$select);
				// 
				//echo $this->db->last_query(); die();
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
				$this->log_model->cronlog("BankQuest Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* BankQuest Details Cron Execution End ".$end_time." **************************"."\n");
				fclose($fp1);
			}
		}
		
		// Fuction to fetch Vision registrations and export in TXT format
		public function vision_old()
		{
			ini_set("memory_limit", "-1");
			
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$cpd_flg = 0;
			$success = array();
			$error = array();
			
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	
			$cron_file_dir = "./uploads/rahultest/";
			
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
				fwrite($fp1, "\n************************* Vision Details Cron Execution Started - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2017-08-31';
				
				// get Vision registration details for given date
				$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
				$this->db->where('DATE(c.created_on) >=', '2019-05-01');
				$this->db->where('DATE(c.created_on) <=', '2019-05-31');
				$this->db->join('payment_transaction b','b.ref_id=c.vision_id','LEFT');
				$vision_data = $this->Master_model->getRecords('iibf_vision c',array('pay_type' => 7,'pay_status' => 1,'status' => '1'),$select);
				//' DATE(created_on)' => $yesterday,
				//echo $this->db->last_query(); die();
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
						
						$PRD_TYPE = 'VI';	// VI for Vision
						
						if(strlen($vi_reg['address_1']) > 30)
						{	$address_1 = substr($vi_reg['address_1'],0,29);	}
						else
						{	$address_1 = $vi_reg['address_1'];	}
						
						if(strlen($vi_reg['address_2']) > 30)
						{	$address_2 = substr($vi_reg['address_2'],0,29);	}
						else
						{	$address_2 = $vi_reg['address_2'];	}
						
						if(strlen($vi_reg['address_3']) > 30)
						{	$address_3 = substr($vi_reg['address_3'],0,29);	}
						else
						{	$address_3 = $vi_reg['address_3'];	}
						
						if(strlen($vi_reg['address_4']) > 30)
						{	$address_4 = substr($vi_reg['address_4'],0,29);	}
						else
						{	$address_4 = $vi_reg['address_4'];	}
						
						
						$data .= ''.$vi_reg['namesub'].'|'.$vi_reg['fname'].'|'.$vi_reg['mname'].'|'.$vi_reg['lname'].'|'.$vi_reg['gender'].'|'.$vi_reg['email_id'].'|'.$vi_reg['contact_no'].'|'.$address_1.'|'.$address_2.'|'.$address_3.'|'.$address_4.'|'.$vi_reg['city'].'|'.$vi_reg['state'].'|'.$vi_reg['pincode'].'|'.$vi_reg['subscription_no'].'|'.$vi_reg['amount'].'|'.$subscription_from_date.'|'.$subscription_to_date.'|'.$vi_reg['transaction_no'].'|'.$trn_date.'|'.$PRD_TYPE."\n";
						
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
				$this->log_model->cronlog("Vision Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* Vision Details Cron Execution End ".$end_time." **************************"."\n");
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
				fwrite($fp1, "\n************************* Vision Details Cron Execution Started - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2017-08-31';
				
				// get Vision registration details for given date
				
				$ref = array('3521');
				
				$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
				$this->db->where_in('c.vision_id', $ref);
				$this->db->join('payment_transaction b','b.ref_id=c.vision_id','LEFT');
				//$this->db->where('DATE(c.created_on) >=', '2019-05-01');
				//$this->db->where('DATE(c.created_on) <=', '2019-05-30');
				//$this->db->where_in('vision_id',$ref);
				$vision_data = $this->Master_model->getRecords('iibf_vision c',array('pay_type' => 7,'pay_status' => 1,'status' => '1'),$select);
				// ' DATE(created_on)' => $yesterday,
				//echo $this->db->last_query(); die();
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
						
						if(strlen($vi_reg['address_1']) > 40)
						{	$address_1 = substr($vi_reg['address_1'],0,39);	}
						else
						{	$address_1 = $vi_reg['address_1'];	}
						
						if(strlen($vi_reg['address_2']) > 40)
						{	$address_2 = substr($vi_reg['address_2'],0,39);	}
						else
						{	$address_2 = $vi_reg['address_2'];	}
						
						if(strlen($vi_reg['address_3']) > 40)
						{	$address_3 = substr($vi_reg['address_3'],0,39);	}
						else
						{	$address_3 = $vi_reg['address_3'];	}
						
						if(strlen($vi_reg['address_4']) > 40)
						{	$address_4 = substr($vi_reg['address_4'],0,39);	}
						else
						{	$address_4 = $vi_reg['address_4'];	}
						
						$PRD_TYPE = 'VIS';	// VI for Vision
						
						$data .= ''.$vi_reg['namesub'].'|'.$vi_reg['fname'].'|'.$vi_reg['mname'].'|'.$vi_reg['lname'].'|'.$vi_reg['gender'].'|'.$vi_reg['email_id'].'|'.$vi_reg['contact_no'].'|'.$address_1.'|'.$address_2.'|'.$address_3.'|'.$address_4.'|'.$vi_reg['city'].'|'.$vi_reg['state'].'|'.$vi_reg['pincode'].'|'.$vi_reg['subscription_no'].'|'.$vi_reg['amount'].'|'.$subscription_from_date.'|'.$subscription_to_date.'|'.$vi_reg['transaction_no'].'|'.$trn_date.'|'.$PRD_TYPE.'|'.$vi_reg['state']."\n";
						
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
				$this->log_model->cronlog("Vision Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* Vision Details Cron Execution End ".$end_time." **************************"."\n");
				fclose($fp1);
				
			}
		}
		
		// Fuction to fetch FinQuest registrations and export in TXT format
		public function finquest_old()
		{
			ini_set("memory_limit", "-1");
			
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$cpd_flg = 0;
			$success = array();
			$error = array();
			
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	
			$cron_file_dir = "./uploads/rahultest/";
			
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
				fwrite($fp1, "\n************************* FinQuest Details Cron Execution Started - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2017-08-31';
				
				// get FinQuest registration details for given date
				$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
				$this->db->where('DATE(c.created_on) >=', '2019-05-01');
				$this->db->where('DATE(c.created_on) <=', '2019-05-31');
				$this->db->join('payment_transaction b','b.ref_id=c.id','LEFT');
				$finquest_data = $this->Master_model->getRecords('fin_quest c',array('pay_type' => 8,'pay_status' => 1,'status' => '1'),$select);
				// ' DATE(created_on)' => $yesterday,
				//echo $this->db->last_query(); die();
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
				$this->log_model->cronlog("FinQuest Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* FinQuest Details Cron Execution End ".$end_time." **************************"."\n");
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
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0755); 
			}
			
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "finquest_details_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* FinQuest Details Cron Execution Started - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2017-08-31';
				
				// get FinQuest registration details for given date
				$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
				$ref_id =array('333');
				$this->db->where_in('c.id',$ref_id);
				$this->db->join('payment_transaction b','b.ref_id=c.id','LEFT');
				//$this->db->where('DATE(c.created_on) >=', '2019-07-01');
				//$this->db->where('DATE(c.created_on) <=', '2019-07-31');
				$finquest_data = $this->Master_model->getRecords('fin_quest c',array('pay_type' => 8,'pay_status' => 1,'status' => '1'),$select);
				// ' DATE(created_on)' => $yesterday,
				// echo $this->db->last_query(); die();
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
				$this->log_model->cronlog("FinQuest Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* FinQuest Details Cron Execution End ".$end_time." **************************"."\n");
				fclose($fp1);
			}
		}
		
		// Fuction to fetch CPD registrations and export in TXT format
		public function cpd_old()
		{
			ini_set("memory_limit", "-1");
			
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$cpd_flg = 0;
			$success = array();
			$error = array();
			
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	
			$cron_file_dir = "./uploads/rahultest/";
			
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
				fwrite($fp1, "\n************************* CPD Details Cron Execution Started - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2017-08-17';
				
				// get CPD registration details for given date
				$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
				$this->db->where('DATE(c.created_on) >=', '2019-05-01');
				$this->db->where('DATE(c.created_on) <=', '2019-05-31');
				$this->db->join('payment_transaction b','b.ref_id=c.id','LEFT');
				$cpd_data = $this->Master_model->getRecords('cpd_registration c',array('pay_type' => 9,'pay_status' => 1,'status' => '1'),$select);
				// ' DATE(created_on)' => $yesterday,
				//echo $this->db->last_query(); die();
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
				$this->log_model->cronlog("CPD Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* CPD Details Cron Execution End ".$end_time." **************************"."\n");
				fclose($fp1);
			}
		}
		
		/* CPD Registrations Cron */
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
				fwrite($fp1, "\n************************* CPD Details Cron Execution Started - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2017-08-17';
				
				// get CPD registration details for given date
				$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
				$this->db->join('payment_transaction b','b.ref_id=c.id','LEFT');
				$this->db->where('DATE(c.created_on) >=', '2019-07-06');
				$this->db->where('DATE(c.created_on) <=', '2019-07-31');
				$cpd_data = $this->Master_model->getRecords('cpd_registration c',array('pay_type' => 9,'pay_status' => 1,'status' => '1'),$select);
				// ' DATE(created_on)' => $yesterday,
				//echo $this->db->last_query(); exit;
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
				$this->log_model->cronlog("CPD Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* CPD Details Cron Execution End ".$end_time." **************************"."\n");
				fclose($fp1);
			}
		}
		
		// Fuction to fetch Blended registrations and export in TXT format
		public function blended_old()
		{
			ini_set("memory_limit", "-1");
			
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$blended_flg = 0;
			$success = array();
			$error = array();
			
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	
			$cron_file_dir = "./uploads/rahultest/";
			
			// Cron start Logs
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("Blended Details Cron Execution Start", $desc);
			
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0755); 
			}
			
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "blended_details_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* Blended Details Cron Execution Started - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2017-11-21';
				
				// get Blended registration details for given date
				$br_select = 'c.*';
				$this->db->where('DATE(c.createdon) >=', '2019-06-01');
				$this->db->where('DATE(c.createdon) <=', '2019-06-13');
				$br_blended_data = $this->Master_model->getRecords('blended_registration c',array('pay_status' => 1),$br_select);
				// ' DATE(createdon)' => $yesterday,
				
				/*$SQL = "SELECT `c`.*, `b`.`transaction_no`, DATE_FORMAT(b.date, '%Y-%m-%d') date, `b`.`amount`, `b`.`receipt_no`, `b`.`id` AS `pay_txn_id`, `b`.`pay_type` FROM `blended_registration` `c` LEFT JOIN `payment_transaction` `b` ON `b`.`ref_id`=`c`.`blended_id` WHERE DATE(`createdon`) >= '2017-11-17' AND DATE(`createdon`) <= '2017-11-21' AND `pay_type` = 10 AND `pay_status` = 1 AND `status` = '1'";
					$blended_reg_qry = $this->db->query($SQL);
				$blended_data = $blended_reg_qry->result_array();*/
				
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
						if($br_blended_reg['training_type'] == 'VC' && $br_blended_reg['attempt'] == 1 && $br_blended_reg['fee'] == 0)
						{
							$record_type_flag = 1;
							
							$select = 'c.*';
							$this->db->where('DATE(c.createdon) >=', '2019-06-01');
							$this->db->where('DATE(c.createdon) <=', '2019-06-13');
							$blended_data = $this->Master_model->getRecords('blended_registration c',array('pay_status' => 1, 'blended_id' => $blended_id),$select); // ' DATE(createdon)' => $yesterday,
						}
						else
						{
							$record_type_flag = 2;
							
							// get Blended registration details for given date
							$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount,b.receipt_no,b.id AS pay_txn_id,b.pay_type';
							$this->db->where('DATE(c.createdon) >=', '2019-06-01');
							$this->db->where('DATE(c.createdon) <=', '2019-06-13');
							$this->db->join('payment_transaction b','b.ref_id=c.blended_id','LEFT');
							$blended_data = $this->Master_model->getRecords('blended_registration c',array('pay_type' => 10,'pay_status' => 1,'status' => '1', 'blended_id' => $blended_id),$select);
							// ' DATE(createdon)' => $yesterday,
						}
						
						/*$SQL = "SELECT `c`.*, `b`.`transaction_no`, DATE_FORMAT(b.date, '%Y-%m-%d') date, `b`.`amount`, `b`.`receipt_no`, `b`.`id` AS `pay_txn_id`, `b`.`pay_type` FROM `blended_registration` `c` LEFT JOIN `payment_transaction` `b` ON `b`.`ref_id`=`c`.`blended_id` WHERE DATE(`createdon`) >= '2017-11-17' AND DATE(`createdon`) <= '2017-11-21' AND `pay_type` = 10 AND `pay_status` = 1 AND `status` = '1'";
							$blended_reg_qry = $this->db->query($SQL);
						$blended_data = $blended_reg_qry->result_array();*/
						
						//echo $this->db->last_query();
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
								//echo "<br>SQL => ".$this->db->last_query();
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
								//echo "<br>SQL => ".$this->db->last_query();
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
								//echo "<br>SQL => ".$this->db->last_query();
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
									//echo "<br>SQL => ".$this->db->last_query();
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
								
								/*$data .= ''.$blended_reg['member_no'].'|'.$blended_reg['program_code'].'|'.$blended_reg['zone_code'].'|'.$blended_reg['center_code'].'|'.$blended_reg['center_name'].'|'.$blended_reg['batch_code'].'|'.$blended_reg['venue_name'].'|'.$start_date.'|'.$end_date.'|'.$blended_reg['namesub'].'|'.$blended_reg['firstname'].'|'.$blended_reg['middlename'].'|'.$blended_reg['lastname'].'|'.$blended_reg['address1'].'|'.$blended_reg['address2'].'|'.$blended_reg['address3'].'|'.$blended_reg['address4'].'|'.$blended_reg['district'].'|'.$blended_reg['city'].'|'.$blended_reg['state'].'|'.$blended_reg['pincode'].'|'.$dateofbirth.'|'.$blended_reg['email'].'|'.$blended_reg['mobile'].'|'.$blended_reg['res_stdcode'].' '.$blended_reg['residential_phone'].'|'.$blended_reg['stdcode'].' '.$blended_reg['office_phone'].'|'.$blended_reg['specify_qualification'].'|'.$qualification_name.'|'.$blended_reg['designation'].'|'.$designation_name.'|'.$blended_reg['associatedinstitute'].'|'.$associatedinstitute_name.'|'.$specified_qualification_name.'|'.$blended_reg['emergency_contact_no'].'|'.$blended_reg['blood_group'].'|'.$blended_reg['fee'].'|'.$pay_status.'|'.$trn_date.'|'.$invoice_no.'|'.$tax_type.'|'.$app_type.'|'.$exempt.'|'.$blended_reg['transaction_no'].'|'.$blended_reg['pay_type'].'|'.$blended_reg['emergency_name'].'|'.$blended_reg['attempt'].'|'.$blended_reg['training_type']."|\n";*/
								
								
								
								
								
								$data .= ''.$blended_reg['member_no'].'|'.$blended_reg['program_code'].'|'.$blended_reg['zone_code'].'|'.$blended_reg['center_code'].'|'.$blended_reg['center_name'].'|'.$blended_reg['batch_code'].'|'.$blended_reg['venue_name'].'|'.$start_date.'|'.$end_date.'|'.$blended_reg['namesub'].'|'.$blended_reg['firstname'].'|'.$blended_reg['middlename'].'|'.$blended_reg['lastname'].'|'.$blended_reg['address1'].'|'.$blended_reg['address2'].'|'.$blended_reg['address3'].'|'.$blended_reg['address4'].'|'.$blended_reg['district'].'|'.$blended_reg['city'].'|'.$blended_reg['state'].'|'.$blended_reg['pincode'].'|'.$dateofbirth.'|'.$blended_reg['email'].'|'.$blended_reg['mobile'].'|'.$blended_reg['res_stdcode'].''.$blended_reg['residential_phone'].'|'.$blended_reg['stdcode'].''.$blended_reg['office_phone'].'|'.$blended_reg['specify_qualification'].'|'.$qualification_name.'|'.$blended_reg['designation'].'|'.$designation_name.'|'.$blended_reg['associatedinstitute'].'|'.$associatedinstitute_name.'|'.$specified_qualification_name.'|'.$blended_reg['emergency_contact_no'].'|'.$blended_reg['blood_group'].'|'.$fees.'|'.$pay_status.'|'.$trn_date.'|'.$invoice_no.'|'.$tax_type.'|'.$app_type.'|'.$exempt.'|'.$transaction_no.'|'.$pay_type.'|'.$blended_reg['emergency_name'].'|'.$blended_reg['attempt'].'|'.$blended_reg['training_type'].'|'.$blended_reg['gstin_no']."\n";
								
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
				$this->log_model->cronlog("Blended Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* Blended Details Cron Execution End ".$end_time." **************************"."\n");
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
				fwrite($fp1, "\n************************* Blended Details Cron Execution Started - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2017-11-21';
				
				// get Blended registration details for given date
				$br_select = 'c.*';
				
				$this->db->where('DATE(c.createdon) >=', '2020-03-01');
				$this->db->where('DATE(c.createdon) <=', '2020-04-30');
				$mem = array('510236430');
				$this->db->where_in('c.member_no', $mem);
				$br_blended_data = $this->Master_model->getRecords('blended_registration c',array('pay_status' => 1),$br_select);
				
				// ' DATE(createdon)' => $yesterday,
				
				/*$SQL = "SELECT `c`.*, `b`.`transaction_no`, DATE_FORMAT(b.date, '%Y-%m-%d') date, `b`.`amount`, `b`.`receipt_no`, `b`.`id` AS `pay_txn_id`, `b`.`pay_type` FROM `blended_registration` `c` LEFT JOIN `payment_transaction` `b` ON `b`.`ref_id`=`c`.`blended_id` WHERE DATE(`createdon`) >= '2017-11-17' AND DATE(`createdon`) <= '2017-11-21' AND `pay_type` = 10 AND `pay_status` = 1 AND `status` = '1'";
					$blended_reg_qry = $this->db->query($SQL);
				$blended_data = $blended_reg_qry->result_array();*/
				
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
							$this->db->where('DATE(c.createdon) >=', '2020-03-01');
							$this->db->where('DATE(c.createdon) <=', '2020-04-30');
							//$mem = array('510098989','510345551','510093837');
							//$this->db->where_in('c.member_no', $mem);
							$blended_data = $this->Master_model->getRecords('blended_registration c',array('pay_status' => 1, 'blended_id' => $blended_id),$select);
							//echo $this->db->last_query();exit;
							// ' DATE(createdon)' => $yesterday,
						}
						
						else
						{
							$record_type_flag = 2;
							
							// get Blended registration details for given date
							$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount,b.receipt_no,b.id AS pay_txn_id,b.pay_type';
							
							$this->db->join('payment_transaction b','b.ref_id=c.blended_id','LEFT');
							$this->db->where('DATE(c.createdon) >=', '2020-03-01');
							$this->db->where('DATE(c.createdon) <=', '2020-04-30');
							
							$blended_data = $this->Master_model->getRecords('blended_registration c',array('pay_type' => 10,'pay_status' => 1,'status' => '1', 'blended_id' => $blended_id),$select);
							// ' DATE(createdon)' => $yesterday,
						}
						
						/*$SQL = "SELECT `c`.*, `b`.`transaction_no`, DATE_FORMAT(b.date, '%Y-%m-%d') date, `b`.`amount`, `b`.`receipt_no`, `b`.`id` AS `pay_txn_id`, `b`.`pay_type` FROM `blended_registration` `c` LEFT JOIN `payment_transaction` `b` ON `b`.`ref_id`=`c`.`blended_id` WHERE DATE(`createdon`) >= '2017-11-17' AND DATE(`createdon`) <= '2017-11-21' AND `pay_type` = 10 AND `pay_status` = 1 AND `status` = '1'";
							$blended_reg_qry = $this->db->query($SQL);
						$blended_data = $blended_reg_qry->result_array();*/
						
						//echo $this->db->last_query();
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
								//echo "<br>SQL => ".$this->db->last_query();
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
								//echo "<br>SQL => ".$this->db->last_query();
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
								//echo "<br>SQL => ".$this->db->last_query();
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
									//echo "<br>SQL => ".$this->db->last_query();
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
								
								$data .= ''.$blended_reg['member_no'].'|'.$blended_reg['program_code'].'|'.$blended_reg['zone_code'].'|'.$blended_reg['center_code'].'|'.$blended_reg['center_name'].'|'.$blended_reg['batch_code'].'|'.$blended_reg['venue_name'].'|'.$start_date.'|'.$end_date.'|'.$blended_reg['namesub'].'|'.$blended_reg['firstname'].'|'.$blended_reg['middlename'].'|'.$blended_reg['lastname'].'|'.$blended_reg['address1'].'|'.$blended_reg['address2'].'|'.$blended_reg['address3'].'|'.$blended_reg['address4'].'|'.$district.'|'.$city.'|'.$blended_reg['state'].'|'.$blended_reg['pincode'].'|'.$dateofbirth.'|'.$blended_reg['email'].'|'.$blended_reg['mobile'].'|'.$blended_reg['res_stdcode'].$blended_reg['residential_phone'].'|'.$blended_reg['stdcode'].$blended_reg['office_phone'].'|'.$blended_reg['specify_qualification'].'|'.$qualification_name.'|'.$blended_reg['designation'].'|'.$designation_name.'|'.$blended_reg['associatedinstitute'].'|'.$associatedinstitute_name.'|'.$specified_qualification_name.'|'.$blended_reg['emergency_contact_no'].'|'.$blended_reg['blood_group'].'|'.$fees.'|'.$pay_status.'|'.$trn_date.'|'.$invoice_no.'|'.$tax_type.'|'.$app_type.'|'.$exempt.'|'.$transaction_no.'|'.$pay_type.'|'.$blended_reg['emergency_name'].'|'.$blended_reg['attempt'].'|'.$blended_reg['training_type'].'|'.$blended_reg['gstin_no']."\n";
								
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
				$this->log_model->cronlog("Blended Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* Blended Details Cron Execution End ".$end_time." **************************"."\n");
				fclose($fp1);
			}
		}
		
		// Blended Registrations Cron */
		public function blended_bh()
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
				$yesterday = '2020-07-29';
				
				// get Blended registration details for given date
				$br_select = 'c.*';
				//$br_blended_data = $this->Master_model->getRecords('blended_registration c',array(' DATE(createdon)' => $yesterday,'pay_status' => 1),$br_select);
				//$mem = array('801457282');
				//$this->db->where_in('c.member_no', $mem);
				$this->db->where_in('c.program_code', array('ITC','ITC2','ITC3'));
				$br_blended_data = $this->Master_model->getRecords('blended_registration c',array(' DATE(createdon)' => $yesterday,'pay_status' => 1),$br_select);
				
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
							$blended_data = $this->Master_model->getRecords('blended_registration c',array(' DATE(createdon)' => $yesterday,'pay_status' => 1, 'blended_id' => $blended_id),$select);
							echo $this->db->last_query();
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
		
		public function exam_custom()
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
			
			if(!file_exists($cron_file_dir.$current_date."_exam"))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date."_exam", 0700); 
			}
			
			if(file_exists($cron_file_dir.$current_date."_exam"))
			{
				$cron_file_path = $cron_file_dir.$current_date."_exam";	// Path with CURRENT DATE DIRECTORY
				
				$file = "exam_cand_report_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$yesterday = date('Y-m-d', strtotime("- 1 day")); 
				$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.elected_sub_code,a.place_of_work,a.state_place_of_work,a.pin_code_place_of_work';
				/*$this->db->join('member_exam a','a.regnumber=c.regnumber','LEFT'); 
					$this->db->join('payment_transaction b','b.ref_id=a.id','LEFT');
					$can_exam_data = $this->Master_model->getRecords('member_registration c',array(' DATE(a.created_on)'=>$yesterday,'pay_type'=>2),$select);
				*/
				
				$this->db->join('payment_transaction b','b.ref_id=a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
				$can_exam_data = $this->Master_model->getRecords('member_exam a',array('DATE(a.created_on) >='=>"2017-01-09", 'DATE(a.created_on) <'=>"2017-01-24",'pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1),$select);
				
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
						
						$trans_date = '';
						if($exam['date'] != '0000-00-00')
						{
							$trans_date = date('d-M-Y',strtotime($exam['date']));
						}
						
						if($exam['elected_sub_code']!=0)
						{
							$data .= ''.$exam['exam_code'].'|'.$exam['exam_period'].'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|'.$exam['elected_sub_code'].'|'.$exam['place_of_work'].'|'.$exam['pin_code_place_of_work'].'|'.$exam['state_place_of_work'].'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no']."|\n";
						}
						else
						{
							$data .= ''.$exam['exam_code'].'|'.$exam['exam_period'].'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['exam_medium'].'|'.$exam['exam_center_code'].'|'.$exam_mode.'|'.$syllabus_code.'|IRE|'.$part_no.'|||||'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['exam_fee'].'|'.$exam['transaction_no']."|\n";
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
			$cron_file_dir = "./uploads/cronfiles/";
			
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
				fwrite($fp1, "\n************************* DRA Institute Cron Execution Started - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2018-01-30';
				
				// get DRA Institute registration details for given date
				$select = 'c.*,b.transaction_no,DATE_FORMAT(b.date,"%Y-%m-%d") date,b.amount';
				$this->db->join('payment_transaction b','b.ref_id=c.id','LEFT');
				$dra_inst_data = $this->Master_model->getRecords('dra_inst_registration c',array(' DATE(created_on)' => $yesterday,'pay_type' => 12,'c.status' => 1,'b.status' => '1'),$select);
				
				//echo $this->db->last_query(); die();
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
						
						
						//echo "<br/>".$data;exit;
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
				$this->log_model->cronlog("DRA Institute Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* DRA Institute Details Cron Execution End ".$end_time." **************************"."\n");
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
			$cron_file_dir = "./uploads/rahultest/";
			
			//Cron start Logs
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("Bulk Candidate Exam Details Cron Execution Start", $desc);
			
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0755); 
			}
			
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "bulk_exam_cand_report_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* Bulk Candidate Exam Details Cron Execution Started - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day")); 
				//$yesterday = '2018-02-12';
				
				// get payment transaction for given date // DATE(date) = '".$yesterday."' OR 
				$this->db->where('DATE(updated_date) >=', '2019-06-01');
				$this->db->where('DATE(updated_date) <=', '2019-06-13');
				$this->db->where("status = 1");
				//$this->db->where("(DATE(updated_date) = '".$yesterday."') AND status = 1");
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
								
								if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
								else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
								
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
								if($exam['exam_code'] != '' && $exam['exam_code'] != 0)
								{
									//$ex_code = $this->master_model->getRecords('exam_activation_master',array('exam_code'=>$exam['exam_code']));
									// Added on 25 March 2019 - Bhushan (As per pooja changes for bulk)
									$ex_code = $this->master_model->getRecords('bulk_exam_activation_master',array('exam_code'=>$exam['exam_code'])); 
									if(count($ex_code))
									{
										if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0)
										{	$exam_code = $ex_code[0]['original_val'];	}
										else
										{	$exam_code = $exam['exam_code'];	}
									}
								}
								else
								{
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
								if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == 63 || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == 68 || $exam_code == 69 || $exam_code == 70 || $exam_code == 71 || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) 
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
				$this->log_model->cronlog("Bulk Candidate Exam Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* Bulk Candidate Exam Details Cron Execution End ".$end_time." **************************"."\n");
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
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0755); 
			}
			
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "bulk_exam_cand_report_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* Bulk Candidate Exam Details Cron Execution Started - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day")); 
				//$yesterday = '2018-02-12';
				
				// get payment transaction for given date // DATE(date) = '".$yesterday."' OR 
				//$this->db->where("(DATE(updated_date) = '".$yesterday."') AND status = 1");
				
				//$this->db->where('DATE(updated_date) >=', '2019-07-04');
				//$this->db->where('DATE(updated_date) <=', '2019-07-15');
				$utr = array('CTI8947928');
				$this->db->where_in('UTR_no', $utr);
				
				//$exam_period = array('623','624');
				//$this->db->where_in('exam_period', $exam_period);
				$this->db->where('status', '1');
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
								
								$exam_mode = "";
								if($exam['exam_mode'] == 'ON'){ $exam_mode = 'O';}
								else if($exam['exam_mode'] == 'OFF'){ $exam_mode = 'F';}
								
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
								
								if ($exam['exam_code'] == 1010 || $exam['exam_code'] == 10100 || $exam['exam_code'] == 101000){
									$exam_code = 101;
									}else{
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
								if($exam_code == $this->config->item('examCodeCaiib') || $exam_code == 62 || $exam_code == 63 || $exam_code == 64 || $exam_code == 65 || $exam_code == 66 || $exam_code == 67 || $exam_code == 68 || $exam_code == 69 || $exam_code == 70 || $exam_code == 71 || $exam_code == 72 || $exam_code == $this->config->item('examCodeJaiib')) 
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
				$this->log_model->cronlog("Bulk Candidate Exam Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* Bulk Candidate Exam Details Cron Execution End ".$end_time." **************************"."\n");
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
			//$current_date = date("Ymd");	
			$current_date = "20191119";	
			$cron_file_dir = "./uploads/cronFilesCustom/";
			
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF Bulk Exam Admit Card Details Cron Execution Start", $desc);
			
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0755); 
			}
			
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "bulk_admit_card_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* IIBF Bulk Exam Admit Card Details Cron Execution Started - ".$start_time." ************************* \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$yesterday = '2019-11-18';
				
				// get member exam application details for given date
				/*$select = 'a.id as mem_exam_id, a.examination_date';
					$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$cand_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'pay_type'=>2,'status'=>1,'pay_status'=>1),$select);*/
				//echo "<br>SQL => ".$this->db->last_query(); die();
				// get payment transaction for given date 
				//DATE(date) = '".$yesterday."' OR
				//$this->db->where('DATE(updated_date) >=', '2019-04-01');
				//$this->db->where('DATE(updated_date) <=', '2019-07-15');
				$this->db->where("( DATE(updated_date) = '".$yesterday."') AND status = 1");
				$this->db->where("status = 1");
				$bulk_payment = $this->Master_model->getRecords('bulk_payment_transaction');
				
				//echo "<br>SQL => ".$this->db->last_query();
				
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
						//echo "<br>SQL => ".$this->db->last_query();
						
						
						if(count($cand_exam_data))
						{
							foreach($cand_exam_data as $exam)
							{
								$mem_exam_id = $exam['memexamid'];
								
								// get admit card details for this member by mem_exam_id
								$this->db->where('remark', 1);
								$admit_card_details_arr = $this->Master_model->getRecords('admit_card_details_996',array('mem_exam_id' => $mem_exam_id));
								
								
								//echo "<br>SQL => ".$this->db->last_query(); die();
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
										}
										
										$venueadd2 = '';
										if($admit_card_data['venueadd2'] != '')
										{
											$venueadd2 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd2']));
										}
										
										$venueadd3 = '';
										if($admit_card_data['venueadd3'] != '')
										{
											$venueadd3 = trim(str_replace(PHP_EOL, '', $admit_card_data['venueadd3']));
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
											//$exam_period = $exam['exam_period'];
											$exam_period = $admit_card_data['exm_prd'];
										}
										// eof code to get actual exam period for exam application, added by Bhagwan Sahane, on 28-09-2017
										
										// code rewrite exam code, added by Bhagwan Sahane, on 13-10-2017
										/*$exam_code = '';
											if($admit_card_data['exm_cd'] == 340 || $admit_card_data['exm_cd'] == 3400)
											{
											$exam_code = 34;
											}
											elseif($admit_card_data['exm_cd'] == 580 || $admit_card_data['exm_cd'] == 5800)
											{
											$exam_code = 58;
											}
											elseif($admit_card_data['exm_cd'] == 1600)
											{
											$exam_code = 160;
											}
											elseif($admit_card_data['exm_cd'] == 200)
											{
											$exam_code = 20;
											}elseif($admit_card_data['exm_cd'] == 1770)
											{
											$exam_code =177;
											}
											elseif($admit_card_data['exm_cd'] == 590)
											{
											$exam_code =59;
											}
											elseif($admit_card_data['exm_cd'] == 810)
											{
											$exam_code =81;
											}
											else
											{
											$exam_code = $admit_card_data['exm_cd'];
										}*/
										// eof code rewrite exam code, added by Bhagwan Sahane, on 13-10-2017
										
										/* Start Dyanamic Exam code get from database table : Bhushan 25 March 2019 */
										$exam_code = '';
										if($admit_card_data['exm_cd'] != '' && $admit_card_data['exm_cd'] != 0)
										{
											//$ex_code = $this->master_model->getRecords('exam_activation_master',array('exam_code'=>$exam['exam_code']));
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
										
										
										// EXM_CD|EXM_PRD|MEMBER_NO|CTR_CD|CTR_NAM|SUB_CD|SUB_DSC|VENUE_CD|VENUE_ADDR1|VENUE_ADDR2|VENUE_ADDR3|VENUE_ADDR4|VENUE_ADDR5|VENUE_PINCODE|EXAM_SEAT_NO|EXAM_PASSWORD|EXAM_DATE|EXAM_TIME|EXAM_MODE(Online/Offline)| EXAM_MEDIUM|SCRIBE_FLG(Y/N)|VENDOR_CODE(1/3)|TRN_DATE|
										
										/*$data .= ''.$admit_card_data['exm_cd'].'|'.$admit_card_data['exm_prd'].'|'.$admit_card_data['mem_mem_no'].'|'.$admit_card_data['center_code'].'|'.$admit_card_data['center_name'].'|'.$admit_card_data['sub_cd'].'|'.$admit_card_data['sub_dsc'].'|'.$admit_card_data['venueid'].'|'.$admit_card_data['venueadd1'].'|'.$admit_card_data['venueadd2'].'|'.$admit_card_data['venueadd3'].'|'.$admit_card_data['venueadd4'].'|'.$admit_card_data['venueadd5'].'|'.$admit_card_data['venpin'].'|'.$admit_card_data['seat_identification'].'|'.$admit_card_data['pwd'].'|'.$exam_date.'|'.$admit_card_data['time'].'|'.$admit_card_data['mode'].'|'.$admit_card_data['m_1'].'|'.$admit_card_data['scribe_flag'].'|'.$admit_card_data['vendor_code'].'|'.$trn_date."|\n";*/
										
										//$data .= ''.$admit_card_data['exm_cd'].'|'.$admit_card_data['exm_prd'].'|'.$admit_card_data['mem_mem_no'].'|'.$admit_card_data['center_code'].'|'.$admit_card_data['center_name'].'|'.$admit_card_data['sub_cd'].'|'.$admit_card_data['sub_dsc'].'|'.$admit_card_data['venueid'].'|'.$venueadd1.'|'.$venueadd2.'|'.$venueadd3.'|'.$admit_card_data['venueadd4'].'|'.$admit_card_data['venueadd5'].'|'.$admit_card_data['venpin'].'|'.$admit_card_data['seat_identification'].'|'.$admit_card_data['pwd'].'|'.$exam_date.'|'.$admit_card_data['time'].'|'.$admit_card_data['mode'].'|'.$admit_card_data['m_1'].'|'.$admit_card_data['scribe_flag'].'|'.$admit_card_data['vendor_code'].'|'.$trn_date."|\n";
										
										//$data .= ''.$admit_card_data['exm_cd'].'|'.$exam_period.'|'.$admit_card_data['mem_mem_no'].'|'.$admit_card_data['center_code'].'|'.$admit_card_data['center_name'].'|'.$admit_card_data['sub_cd'].'|'.$admit_card_data['sub_dsc'].'|'.$admit_card_data['venueid'].'|'.$venueadd1.'|'.$venueadd2.'|'.$venueadd3.'|'.$admit_card_data['venueadd4'].'|'.$admit_card_data['venueadd5'].'|'.$admit_card_data['venpin'].'|'.$admit_card_data['seat_identification'].'|'.$admit_card_data['pwd'].'|'.$exam_date.'|'.$admit_card_data['time'].'|'.$admit_card_data['mode'].'|'.$admit_card_data['m_1'].'|'.$admit_card_data['scribe_flag'].'|'.$admit_card_data['vendor_code'].'|'.$trn_date."|\n";
										
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
				$this->log_model->cronlog("IIBF Bulk Exam Admit Card Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* IIBF Bulk Exam Admit Card Details Cron Execution End ".$end_time." *************************"."\n");
				fclose($fp1);
			}
			
		}
		
		//Fuction To Fetch Blended Member Feedback & Export In TXT Format
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
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0755); 
			}
			
			if(file_exists($cron_file_dir.$current_date)){
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "blended_member_feedback_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* Blended Member Feedback Details Cron Execution Started - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$yesterday = '2019-11-28';
				
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
							// PROGRAM_CODE|BATCH_CODE|MEMBER_NAME_NUMBER|0|QUESTION||REVIEW_NO
							// PROGRAM_CODE|BATCH_CODE|MEMBER_NAME_NUMBER|0|QUESTION||REVIEW_NO|5
							
							//$qes = trim(preg_replace('/ss+/', ' ', $rows['qes']));
							
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
				$this->log_model->cronlog("Blended Member Feedback Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* Blended Member Feedback Details Cron Execution End ".$end_time." **************************"."\n");
				fclose($fp1);
			}
		}
		
		//Fuction To Fetch Blended Faculty Feedback & Export In TXT Format
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
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0755); 
			}
			
			if(file_exists($cron_file_dir.$current_date)){
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				
				$file = "blended_faculty_feedback_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* Blended Faculty Feedback Details Cron Execution Started - ".$start_time." ******************** \n");
				
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$yesterday = '2019-06-13';
				
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
							
							//program_code|batch_code|session_code|batch_date|facilitator_code|topic_code|review_no
							//CTP|VCTP006|23|11-JUN-19|F-866|T-2032|E
							
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
				$this->log_model->cronlog("Blended Faculty Feedback Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************************* Blended Faculty Feedback Details Cron Execution End ".$end_time." **************************"."\n");
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
				
				$mem = array('510216538');
				
				//$txn = array('0392608604514');
				
				$select = 'DISTINCT(b.transaction_no),a.exam_code,a.exam_mode,a.exam_medium,a.exam_period,a.exam_center_code,a.exam_fee,b.member_regnumber,b.member_exam_id,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,a.scribe_flag';
				$this->db->where_in('a.regnumber', $mem);
				//$this->db->where_in('b.transaction_no', $txn);
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
				$can_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>18,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1),$select);
				
				/*DATE(a.created_on)'=>$yesterday,*/
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
		
		public function csc_member() 
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
			
			$current_date = '20200808';
			
			$cron_file_dir = "./uploads/rahultest/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF CSC Member Details Cron Execution Start", $desc);
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
				
				fwrite($fp1, "IIBF CSC New Member Details Cron Execution Started - ".$start_time."\n");
				
				
				$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ($current_date) ));
				//$reg = array('801499800','801499591','801499605','801499608');
				//$this->db->where_in('a.regnumber', $reg);
				
				$this->db->join('payment_transaction b','b.member_regnumber = a.regnumber','LEFT');	
				$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(date)'=>$yesterday,'pay_type'=>2,'isactive'=>'1','isdeleted'=>0,'bankcode' => 'csc', 'status'=>'1'));
				//' DATE(date)'=>$yesterday,
				if(count($new_mem_reg)){
					
					//$data = '';
					//echo $cron_file_path.'/'.$file;
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
				$this->log_model->cronlog("IIBF CSC Member Details Cron Execution End", $desc);
				
				fwrite($fp1, "\n"."************ IIBF CSC Member Details Cron Execution End ".$end_time." *************"."\n");
				fclose($fp1);
			}
		}
		
		
		
		
	}	