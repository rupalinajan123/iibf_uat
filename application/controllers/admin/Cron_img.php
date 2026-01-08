<?php
/*
 * Controller Name	:	Cron Member Images Generation
 * Created By		:	Bhagwan Sahane
 * Created Date		:	30-11-2017
 * Updated By		:	Bhagwan Sahane
 * Updated Date		:	30-11-2017
 *
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Cron_img extends CI_Controller {
	public $UserID;
			
	public function __construct(){
		parent::__construct();
		
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		
		$this->load->helper('pagination_helper'); 
		$this->load->library('pagination');
		$this->load->model('log_model');
		
		define('MEM_FILE_PATH','/webonline/fromweb/images/newmem/');
		define('DRA_FILE_PATH','/webonline/fromweb/images/dra/');
		define('MEM_FILE_EDIT_PATH','/webonline/fromweb/images/edit/');
		define('MEM_FILE_RENEWAL_PATH','/webonline/fromweb/images/renewal/');	// Membership Renewal
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}
	
	// By Bhagwan : Fuction to fetch user's edited data and export it in TXT format
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
		$current_date = "20171206";
			
		$cron_file_dir = "./uploads/cronFilesCustom/";
		
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
			
			// Image data
			//$edited_img_data = $this->Master_model->getRecords('member_registration',array('DATE(images_editedon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
			
			$edited_img_qry = $this->db->query("SELECT *  FROM `member_registration` WHERE `regnumber` IN ('6123625','6783519','6993310','7231135','7460961','7618592','7642918','100071373','200032529','400004753','400044374','400066086','500000392','500003193','500021843','500047097','500048838','510356356','510356358','510356414','510356578','510356613','801175943','801175948') AND `isactive` = '1' AND `isdeleted` = 0");
			
			$edited_img_data = $edited_img_qry->result_array();
			
			echo $this->db->last_query()."<br>***************************<br>";
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
					
					// Zip Image Folder
					if($dir_flg)
					{
						$photo_zip_flg = 0;
						$sign_zip_flg = 0;
						$idproof_zip_flg = 0;
						//if($imgdata['photo_flg'] == 'Y' && $photo != '')
						//{
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
						//}
						
						//if($imgdata['signature_flg'] == 'Y' && $signature != '')
						//{
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
						//}
						
						//if($imgdata['id_flg'] == 'Y' && $idproofimg != '')
						//{
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
						//}
						
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
			
			fwrite($fp1, "\n"."************************* Edited Candidate Details Cron Execution End ".$end_time." **************************"."\n");
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
}