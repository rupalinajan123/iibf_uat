<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_blended_invoice extends CI_Controller {
public function __construct(){
		parent::__construct();
		
		$this->load->model('UserModel');
		$this->load->model('Master_model');
		
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->model('log_model');
		
		define('BLENDED_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/');
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}
	
	
	// Blended Courses Invoices
	public function blended_course_invoice()
	{ 
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$file_w_flg = 0;
		$photo_zip_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronFilesCustom/"; // invoice_cronfiles
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog("IIBF Blended Courses Invoice Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700);
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "blended_course_invoice_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n************************* IIBF Blended Courses Invoice Details Cron Execution Started - ".$start_time." ************************* \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			
			$blended_id = array('18260','18261','18262','18263','18265','18267','18268','18273');
			$select = 'c.zone_code,b.id as pay_txn_id,b.receipt_no';
			//$this->db->where('DATE(c.createdon) >=', '2020-06-17');
			//$this->db->where('DATE(c.createdon) <=', '2020-06-18');
			$this->db->where_in('c.blended_id', $blended_id);
			//$this->db->where('c.batch_code', 'VCBC001');
			$this->db->join('payment_transaction b','b.ref_id = c.blended_id','LEFT');
			$blended_course_data = $this->Master_model->getRecords('blended_registration c',array('pay_type'=>10,'pay_status' => 1,'status' => '1'),$select);
			
			//DATE(createdon)' => $yesterday,
			//$this->db->where('DATE(c.createdon) >=', '2019-07-01');
			//$this->db->where('DATE(c.createdon) <=', '2019-07-01');
			//$blended_course_data = $this->Master_model->getRecords('blended_registration c',array('pay_type' => 10,'pay_status' => 1,'status' => '1'),$select);
			echo $this->db->last_query(); //die();
			
			if(count($blended_course_data))
			{
				$i = 1;
				$blended_course_invoice_count = 0;
				$blended_course_invoice_image_cnt = 0;
				
				$dirname = "blended_course_invoice_image_".$current_date;
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
				
				foreach($blended_course_data as $blended_course)
				{					
					$pay_txn_id = $blended_course['pay_txn_id'];
					$receipt_no = $blended_course['receipt_no'];
					$zone_code  = $blended_course['zone_code'];
					// /*/*/
/* */
get invoice details for this blended course payment transaction by id and receipt_no
					/*$receipt_no = array('901689717','901689983','901692142','901693424','901694350','901695641','901697279','901697722','901699471','901699697','901700414','901700610','901701270','901702160','901702589','901702748','901703565','901703877','901704735','901705587','901706005','901706118','901706405','901706533','901706537','901707900','901708017','901708320','901708389','901709969','901711460','901711769','901712684','901714110','901714397','901714724','901715354','901716018','901716185','901717813','901718730','901719094','901719108','901719114','901719122','901719269','901719355','901719841','901719910','901720243','901720800','901721114','901721332','901724584','901725227','901725699','901725752','901725817','901725929','901731101','901733591','901737491','901738053','901742906','901745004');
					$this->db->where_in('receipt_no', $receipt_no);*/
					
					$this->db->where('transaction_no !=','');
					$this->db->where('app_type','T');
					$this->db->where('receipt_no',$receipt_no);
					$blended_course_invoice_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
					echo "<br>SQL => ".$this->db->last_query();
					if(count($blended_course_invoice_data))
					{
						foreach($blended_course_invoice_data as $blended_course_invoice)
						{
							$data = '';
							$blended_course_invoice_image = '';
							
							$date_of_invoice = $blended_course_invoice['date_of_invoice'];
							$update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
							
							if(is_file("./uploads/blended_invoice/supplier/".$zone_code."/".$blended_course_invoice['invoice_image']))
							{
								$blended_course_invoice_image = BLENDED_INVOICE_FILE_PATH.$blended_course_invoice['invoice_image'];
							}
							else
							{
								fwrite($fp1, "**ERROR** - Blended Courses Invoice does not exist  - ".$blended_course_invoice['invoice_image']." (".$blended_course_invoice['member_no'].")\n");	
							}
							
							//EXAM_CODE(NULL)|EXAM_PERIOD(NULL)|CENTER_CODE|CENTER_NAME|STATE_OF_CENTER|INVOICE_NO|INVOICE_IMAGE|MEMBER_NO|DATE_OF_INVOICE|TRANSACTION_NO|FEE_AMT|CGST_RATE|CGST_AMT|SGST_RATE|SGST_AMT|CS_TOT|IGST_RATE|IGST_AMT|IGST_TOT|QUANTITY(1)|CESS(0.00)|STATE_CODE|STATE_NAME|SERVICE_CODE(XXXXXX)|GSTIN_NO(NULL)|TAX_TYPE(INTRA/INTER)|APP_TYPE(T)|
		
							$data .= ''.$blended_course_invoice['exam_code'].'|'.$blended_course_invoice['exam_period'].'|'.$blended_course_invoice['center_code'].'|'.$blended_course_invoice['center_name'].'|'.$blended_course_invoice['state_of_center'].'|'.$blended_course_invoice['invoice_no'].'|'.$blended_course_invoice_image.'|'.$blended_course_invoice['member_no'].'|'.$update_date_of_invoice.'|'.$blended_course_invoice['transaction_no'].'|'.$blended_course_invoice['fee_amt'].'|'.$blended_course_invoice['cgst_rate'].'|'.$blended_course_invoice['cgst_amt'].'|'.$blended_course_invoice['sgst_rate'].'|'.$blended_course_invoice['sgst_amt'].'|'.$blended_course_invoice['cs_total'].'|'.$blended_course_invoice['igst_rate'].'|'.$blended_course_invoice['igst_amt'].'|'.$blended_course_invoice['igst_total'].'|'.$blended_course_invoice['qty'].'|'.$blended_course_invoice['cess'].'|'.$blended_course_invoice['state_code'].'|'.$blended_course_invoice['state_name'].'|'.$blended_course_invoice['service_code'].'|'.$blended_course_invoice['gstin_no'].'|'.$blended_course_invoice['tax_type'].'|'.$blended_course_invoice['app_type'].'|'.$zone_code."\n";
							 
							if($dir_flg)
							{
								// For photo images
								if($blended_course_invoice_image)
								{
									/*$image = "./uploads/blended_invoice/supplier/".$zone_code."/".$blended_course_invoice['invoice_image'];
									$max_width = "1000";
									$max_height = "1000";
									
									$imgdata = $this->resize_image_max($image,$max_width,$max_height);
									imagejpeg($imgdata, $directory."/".$blended_course_invoice['invoice_image']);
									
									$photo_to_add = $directory."/".$blended_course_invoice['invoice_image'];
									$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
									$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);*/
									
									copy("./uploads/blended_invoice/supplier/".$zone_code."/".$blended_course_invoice['invoice_image'],$directory."/".$blended_course_invoice['invoice_image']);
									$photo_to_add = $directory."/".$blended_course_invoice['invoice_image'];
									$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
									$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
									
									if(!$photo_zip_flg)
									{
										fwrite($fp1, "**ERROR** - Blended Courses Invoice Image not added to zip  - ".$blended_course_invoice['invoice_image']." (".$blended_course_invoice['member_no'].")\n");	
									}
									else
										$blended_course_invoice_image_cnt++;
								}
								
								if($photo_zip_flg)
								{
									$success[] = "Blended Courses Invoice Images Zip Generated Successfully";
								}
								else
								{
									$error[] = "Error While Generating Blended Courses Invoice Images Zip";
								}
							}
							
							$i++;
							$blended_course_invoice_count++;
							
							//fwrite($fp1, "\n");
							
							$file_w_flg = fwrite($fp, $data);
						}
						
						if($file_w_flg)
						{
							$success[] = "Blended Courses Invoice Details File Generated Successfully. ";
						}
						else
						{
							$error[] = "Error While Generating Blended Courses Invoice Details File.";
						}
					}
				}
				
				$zip->close();
				
				fwrite($fp1, "\n"."Total Blended Courses Invoice Details Added = ".$blended_course_invoice_count."\n");
				fwrite($fp1, "\n"."Total Blended Courses Invoice Images Added = ".$blended_course_invoice_image_cnt."\n");
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
			$this->log_model->cronlog("IIBF Blended Courses Invoice Details Cron Execution End", $desc);
			
			fwrite($fp1, "\n"."************************* IIBF Blended Courses Invoice Details Cron Execution End ".$end_time." *************************"."\n");
			fclose($fp1);
		}
	}
	
	
}