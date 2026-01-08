<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Cron_invoice_test_SW extends CI_Controller {
		public function __construct(){
			parent::__construct();
			$this->load->model('UserModel');
			$this->load->model('Master_model');
			$this->load->helper('pagination_helper');
			$this->load->library('pagination');
			$this->load->model('log_model');
			define('MEMBER_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/');
			define('EXAM_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/');
			define('DRA_EXAM_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/');
			define('DUP_ICARD_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/');
			define('DUP_CERT_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/');	// Duplicate Certificate
			define('MEMBER_RENEWAL_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/');	// Membership Renewal
			define('BQ_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/');	// BankQuest
			define('VI_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/');	// Vision
			define('CPD_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/');	// CPD
			define('FQ_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/');	// FinQuest
			define('BLENDED_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/');
			define('CC_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/');	// Contact Classes
			define('GST_RECOVERY_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/');	// GST Recovery
			define('DRA_INSTITUTE_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/');	// DRA Institute
			define('BULK_EXAM_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/'); // Bulk Exam Module
			define('AC_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/'); // Agency Center Module
			define('AC_RENEW_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/');		// Agency Center Renewal.
			define('CREDIT_NOTE_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/');	// Credit Note
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
		}
		/*New Credit Note Cron : Bhushan 18-10-2019 */
		public function credit_note()
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
			$cron_file_dir = "./uploads/cronFilesCustom/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("Credit Note Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date)){
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700);
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;
				$file = "credit_note_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n******** Credit Note Details Cron Execution Started - ".$start_time." ********* \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = "2020-10-07";
				$credit_note_number = array('CDN/20-21/00842','CDN/20-21/00844','CDN/20-21/00848','CDN/20-21/00859','CDN/20-21/00850','CDN/20-21/00858','CDN/20-21/00840','CDN/20-21/00841');
				$select = 'm.req_title,m.req_desc,m.req_member_no,m.req_module,m.transaction_no,m.req_exceptional_case,m.req_reason,m.req_maker_id,m.credit_note_image,m.refund_date,m.credit_note_date,m.credit_note_number,p.transaction_no,p.amount,p.receipt_no,p.refund_request_id,p.ARRN,a.name,m.sbi_refund_date,m.sbi_refund_modified_date,m.credit_note_gen_date'; 
				$this->db->join('administrators a','a.id  = m.req_maker_id','LEFT');
				$this->db->join('payment_refund p','p.transaction_no  = m.transaction_no','LEFT');
				$this->db->where('credit_note_number !=', '');
				$this->db->where('credit_note_image !=', '');
				$this->db->where('ARRN !=', '');
				$this->db->where('ARRN !=', 'NA');
				$this->db->where_in('m.credit_note_number' ,$credit_note_number);
				$credit_note_data = $this->Master_model->getRecords('maker_checker m',array('m.req_status' => '5'),$select);
				// 'DATE(m.credit_note_gen_date)' => $yesterday,
				echo $this->db->last_query();
				//
				if(count($credit_note_data))
				{
					$i = 1;
					$credit_note_count = 0;
					$credit_note_image_cnt = 0;
					$dirname = "credit_note_image_".$current_date;
					$directory = $cron_file_path.'/'.$dirname;
					if(file_exists($directory))
					{
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
					foreach($credit_note_data as $row)
					{					
						$receipt_no = $row['receipt_no'];
						$select = 'exam_code,exam_period,qty,institute_code,invoice_no,date_of_invoice';
						$invoice_data=$this->Master_model->getRecords('exam_invoice',array('receipt_no'=>$receipt_no),$select);
						if(count($invoice_data))
						{
							foreach($invoice_data as $invoice_row)
							{
								$data = '';
								$credit_note_image = '';
								$exam_code = $invoice_row['exam_code'];
								$exam_period = $invoice_row['exam_period'];
								$qty = $invoice_row['qty'];
								$institute_code = $invoice_row['institute_code'];
								$invoice_no = $invoice_row['invoice_no'];
								$date_of_invoice = date('d-M-y',strtotime($invoice_row['date_of_invoice']));
								$req_title = $row['req_title'];
								$req_desc = $row['req_desc'];
								$req_member_no = $row['req_member_no'];
								$req_module = $row['req_module'];
								$transaction_no = $row['transaction_no'];
								$req_exceptional_case = $row['req_exceptional_case'];
								$req_reason = $row['req_reason'];
								$req_maker_id = $row['req_maker_id'];
								$credit_note_image_path = $row['credit_note_image'];
								$refund_date = date('d-M-y',strtotime($row['sbi_refund_date']));
								$credit_note_date = date('d-M-y',strtotime($row['credit_note_date']));
								$credit_note_number = $row['credit_note_number'];
								$transaction_no = $row['transaction_no'];
								$amount = $row['amount'];
								$receipt_no = $row['receipt_no'];
								$refund_request_id = $row['refund_request_id'];
								$ARRN = $row['ARRN'];
								$maker_name = $row['name'];
								$credit_note_gen_date = $row['credit_note_gen_date'];
								$req_title   	= trim($req_title, " \t\n\r");
								$req_desc   	= trim($req_desc, " \t\n\r");
								$req_reason   	= trim($req_reason, " \t\n\r");
								$maker_name   	= trim($maker_name, " \t\n\r");
								$req_title = str_replace(array("\n", "\r"), '', $req_title);
								$req_desc = str_replace(array("\n", "\r"), '', $req_desc);
								$req_reason = str_replace(array("\n", "\r"), '', $req_reason);
								$maker_name = str_replace(array("\n", "\r"), '', $maker_name);
								if($exam_code == 340 || $exam_code == 3400 || $exam_code == 34000)
								{
									$exam_code = 34;
									}elseif($exam_code == 580 || $exam_code == 5800 || $exam_code == 58000){
									$exam_code = 58;
									}elseif($exam_code == 1600 || $exam_code == 16000){
									$exam_code = 160;
									}elseif($exam_code == 200){
									$exam_code = 20;
									}elseif($exam_code == 1770 || $exam_code == 17700){
									$exam_code =177;
									}elseif ($exam_code == 590){
									$exam_code = 59;
									}elseif ($exam_code == 810){
									$exam_code = 81;
									}elseif ($exam_code == 1750){
									$exam_code = 175;
									}else{
									$exam_code;
								}
								if(is_file("./uploads/CreditNote/".$row['credit_note_image']))
								{
									$credit_note_image = CREDIT_NOTE_INVOICE_FILE_PATH.$row['credit_note_image'];
								}
								else{
									fwrite($fp1, "*ERROR* Credit Note Image does not exist-".$row['credit_note_image']."\n");	
								}
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$qty.'|'.$institute_code.'|'.$invoice_no.'|'.$date_of_invoice.'|'.$req_title.'|'.$req_desc.'|'.$req_member_no.'|'.$req_module.'|'.$req_exceptional_case.'|'.$req_reason.'|'.$maker_name.'|'.$credit_note_image.'|'.$refund_date.'|'.$credit_note_date.'|'.$credit_note_number.'|'.$transaction_no.'|'.$amount.'|'.$receipt_no.'|'.$refund_request_id.'|'.$ARRN.'|'.$credit_note_gen_date."\n";
								//1003|777|1|0|EX/20-21/017165|11-Jun-20|Remote exam phase-2 GST recovery|Candidate not paid GST amount|500190336|2|NO||ESDSMaker|/fromweb/testscript/images/invoice/CN_EX_20-21_017165.jpg|04-Aug-20|04-Aug-20|CDN/20-21/00196|0095592382701|1100.00|902370708|8894458|7778018546951|2020-08-18							
								if($dir_flg)
								{
									if($credit_note_image)
									{
										copy("./uploads/CreditNote/".$row['credit_note_image'],$directory."/".$row['credit_note_image']);
										$photo_to_add = $directory."/".$row['credit_note_image'];
										$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
										if(!$photo_zip_flg)
										{
											fwrite($fp1, "*ERROR*-Credit Note Image not added to zip-".$row['credit_note_image']."\n");		
										}
										else
										$credit_note_image_cnt++;
									}
									if($photo_zip_flg)
									{
										$success[] = "Credit Note Images Zip Generated Successfully";
									}
									else
									{
										$error[] = "Error While Generating Credit Note Images Zip";
									}
								}
								$i++;
								$credit_note_count++;
								$file_w_flg = fwrite($fp, $data);
							}
							if($file_w_flg){
								$success[] = "Credit Note Details File Generated Successfully. ";
							}
							else{
								$error[] = "Error While Generating Credit Note Details File.";
							}
						}
					}
					$zip->close();
					fwrite($fp1, "\n"."Total Credit Note Details Added = ".$credit_note_count."\n");
					fwrite($fp1, "\n"."Total Credit Note Images Added = ".$credit_note_image_cnt."\n");
				}
				else{
					$success[] = "No data found for the date";
				}
				fclose($fp);
				// Cron End Logs
				$end_time = date("Y-m-d H:i:s");
				$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
				$desc = json_encode($result);
				$this->log_model->cronlog("Credit Note Cron End", $desc);
				fwrite($fp1, "\n"."********** Credit Note Cron End ".$end_time." **********"."\n");
				fclose($fp1);
			}
		} 
		/* Member Registration Invoices */ 
		public function mem_invoice_id()
		{
			//$current_date = ""
			ini_set("memory_limit", "-1");
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$file_w_flg = 0;
			$photo_zip_flg = 0;
			$success = array();
			$error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	
			/*if($current_date == "")
				{
				$current_date = date("Ymd");	
			}*/
			$cron_file_dir = "./uploads/rahultest/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF Member Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0755); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "mem_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* IIBF Member Invoice Details Cron Execution Started - ".$start_time." ************************* \n");
				//$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$yesterday = date('Y-m-d', strtotime("- 1 day", strtotime ( $current_date) ));
				//$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
				$this->db->where('DATE(a.createdon) >=', '2019-05-01');
				$this->db->where('DATE(a.createdon) <=', '2019-05-31');
				$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0));	// Condition added to skip Renewal Member data from New Member Registration data
				//' DATE(createdon)'=>$yesterday,
				//echo $this->db->last_query();
				if(count($new_mem_reg))
				{
					$i = 1;
					$member_invoice_count = 0;
					$member_invoice_Image_cnt = 0;
					$dirname = "mem_invoice_image_".$current_date;
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
					foreach($new_mem_reg as $reg_data)
					{
						if($reg_data['registrationtype'] != 'NM')
						{
							if($reg_data['registrationtype'] == 'DB')
							{
								$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_DB','pay_type'=>2,'member_regnumber'=>$reg_data['regnumber'],'status'=>1),'id,receipt_no');
							}
							else
							{
								$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'iibfregn','status'=>1,'pay_type'=>1,'ref_id'=>$reg_data['regid']),'id,receipt_no');
							}
						}
						else
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_REG','status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'id,receipt_no');
						}
						if(count($trans_details))
						{
							$pay_txn_id = $trans_details[0]['id'];
							$receipt_no = $trans_details[0]['receipt_no'];
							// get invoice details for this member payment transaction by id and receipt_no
							$this->db->where('transaction_no !=','');
							$this->db->where('app_type','R');
							$this->db->where('receipt_no',$receipt_no);
							$member_invoice_arr = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
							//echo "<br>SQL => ".$this->db->last_query();
							if(count($member_invoice_arr))
							{
								foreach($member_invoice_arr as $memIn_data)
								{
									$data = '';
									$member_invoice_Image = '';
									$date_of_invoice = $memIn_data['date_of_invoice'];
									$Update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
									if(is_file("./uploads/reginvoice/supplier/".$memIn_data['invoice_image']))
									{
										$member_invoice_Image = MEMBER_INVOICE_FILE_PATH.$memIn_data['invoice_image'];
									}
									else
									{
										fwrite($fp1, "**ERROR** - Exam Invoice does not exist  - ".$memIn_data['invoice_image']." (".$memIn_data['member_no'].")\n");	
									}
									$data .= ''.$memIn_data['invoice_no'].'|'.$member_invoice_Image.'|'.$memIn_data['member_no'].'|'.$Update_date_of_invoice.'|'.$memIn_data['transaction_no'].'|'.$memIn_data['fee_amt'].'|'.$memIn_data['cgst_rate'].'|'.$memIn_data['cgst_amt'].'|'.$memIn_data['sgst_rate'].'|'.$memIn_data['sgst_amt'].'|'.$memIn_data['cs_total'].'|'.$memIn_data['igst_rate'].'|'.$memIn_data['igst_amt'].'|'.$memIn_data['igst_total'].'|'.$memIn_data['qty'].'|'.$memIn_data['cess'].'|'.$memIn_data['state_code'].'|'.$memIn_data['state_name'].'|'.$memIn_data['service_code'].'|'.$memIn_data['gstin_no'].'|'.$memIn_data['tax_type']."\n";
									if($dir_flg)
									{
										// For photo images
										if($member_invoice_Image)
										{
											/*$image = "./uploads/reginvoice/supplier/".$memIn_data['invoice_image'];
												$max_width = "1000";
												$max_height = "1000";
												$imgdata = $this->resize_image_max($image,$max_width,$max_height);
												imagejpeg($imgdata, $directory."/".$memIn_data['invoice_image']);
												$photo_to_add = $directory."/".$memIn_data['invoice_image'];
												$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
											$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);*/
											copy("./uploads/reginvoice/supplier/".$memIn_data['invoice_image'],$directory."/".$memIn_data['invoice_image']);
											$photo_to_add = $directory."/".$memIn_data['invoice_image'];
											$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
											$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
											if(!$photo_zip_flg)
											{
												fwrite($fp1, "**ERROR** - Member Invoice Image not added to zip  - ".$memIn_data['invoice_image']." (".$memIn_data['member_no'].")\n");	
											}
											else
											$member_invoice_Image_cnt++;
										}
										if($photo_zip_flg)
										{
											$success['zip'] = "Member Invoice Images Zip Generated Successfully";
										}
										else
										{
											$error['zip'] = "Error While Generating Member Invoice Images Zip";
										}
									}
									$i++;
									$member_invoice_count++;
									//fwrite($fp1, "\n");
									$file_w_flg = fwrite($fp, $data);
								}
								if($file_w_flg)
								{
									$success['file'] = "Member Invoice Details File Generated Successfully. ";
								}
								else
								{
									$error['file'] = "Error While Generating Member Invoice Details File.";
								}
							}
						}
					}
					$zip->close();
					fwrite($fp1, "\n"."Total Member Invoice Details Added = ".$member_invoice_count."\n");
					fwrite($fp1, "\n"."Total Member Invoice Images Added = ".$member_invoice_Image_cnt."\n");
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
				$this->log_model->cronlog("IIBF Member Invoice Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."************************* IIBF Member Invoice Details Cron Execution End ".$end_time." *************************"."\n");
				fclose($fp1);
			}
		}
		/* Member Registration Invoices */ 
		public function mem_invoice()
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
			$cron_file_dir = "./uploads/cronFilesCustom/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF Member Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "mem_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* IIBF Member Invoice Details Cron Execution Started - ".$start_time." ************************* \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$yesterday = '2020-12-15'; // temp
				$mem = array(510494323,510495545,510495549,510495555,510495561,510495570,510495784,510495777,510495798,510495810,510495882,510495892,510495893,510495904,510495907,510495909,510495945,510495955,510495956,510495938,510496429,510496665,510496687,510498058,510498221,510498251,510498260,510498253,510498242,510498252,510498241,510498243,510498259,510498256,510498264,510498265,510498258,510498268,510498270,510498276,510498272,510498273,510498275,510498267,510498269,510498280,510498277,510498274,510498282,510498278,510498288,510498284,510498287,510498285,510498281,510498290,510498291,510498294,510498298,510498296,510498303,510498658,510498674,510498675,510498694,510498720,510498785,510498788,510498792,510498861,510498871,510498890);
				$this->db->where_in('a.regnumber', $mem);
				$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0));
				//$new_mem_reg = $this->Master_model->getRecords('payment_transaction a',array('status'=>'1','pay_type'=>1));
				//' DATE(createdon)'=>$yesterday,
				//echo $this->db->last_query();
				//exit;
				//echo count($new_mem_reg); exit;
				if(count($new_mem_reg))
				{
					$i = 1;
					$member_invoice_count = 0;
					$member_invoice_Image_cnt = 0;
					$dirname = "mem_invoice_image_".$current_date;
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
					foreach($new_mem_reg as $reg_data)
					{
						//echo '<br>'.$reg_data['registrationtype'];
						if($reg_data['registrationtype'] != 'NM')
						{
							if($reg_data['registrationtype'] == 'DB')
							{
								$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_DB','pay_type'=>2,'member_regnumber'=>$reg_data['regnumber'],'status'=>1),'id,receipt_no,pay_type');
							}
							else
							{
								$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'iibfregn','status'=>1,'pay_type'=>1,'ref_id'=>$reg_data['regid']),'id,receipt_no,pay_type');
								//echo '<br>'.$this->db->last_query(); 
							}
						}
						else
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_REG','status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'id,receipt_no,pay_type');
							/*// Digital ELG Member Invoice
								if(empty($trans_details))
								{
								$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_ELR','status'=>1,'pay_type'=>18,'member_regnumber'=>$reg_data['regnumber']),'id,receipt_no,pay_type');
								}
								// CSC Member Invoice
								if(empty($trans_details))
								{
								$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'CSC_NM_REG','status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'id,receipt_no,pay_type');
							}*/
						}
						if(count($trans_details))
						{
							$pay_txn_id = $trans_details[0]['id'];
							$receipt_no = $trans_details[0]['receipt_no'];
							$pay_type = $trans_details[0]['pay_type'];
							// get invoice details for this member payment transaction by id and receipt_no
							$this->db->where('transaction_no !=','');
							$this->db->where('app_type','R');
							$this->db->where('receipt_no',$receipt_no);
							$member_invoice_arr = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
							//echo "<br>SQL => ".$this->db->last_query();
							if(count($member_invoice_arr))
							{
								foreach($member_invoice_arr as $memIn_data)
								{
									$data = '';
									$member_invoice_Image = '';
									$date_of_invoice = $memIn_data['date_of_invoice'];
									$Update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
									if(is_file("./uploads/reginvoice/supplier/".$memIn_data['invoice_image']))
									{
										$member_invoice_Image = MEMBER_INVOICE_FILE_PATH.$memIn_data['invoice_image'];
									}
									else
									{
										fwrite($fp1, "**ERROR** - Exam Invoice does not exist  - ".$memIn_data['invoice_image']." (".$memIn_data['member_no'].")\n");	
									}
									$data .= ''.$memIn_data['invoice_no'].'|'.$member_invoice_Image.'|'.$memIn_data['member_no'].'|'.$Update_date_of_invoice.'|'.$memIn_data['transaction_no'].'|'.$memIn_data['fee_amt'].'|'.$memIn_data['cgst_rate'].'|'.$memIn_data['cgst_amt'].'|'.$memIn_data['sgst_rate'].'|'.$memIn_data['sgst_amt'].'|'.$memIn_data['cs_total'].'|'.$memIn_data['igst_rate'].'|'.$memIn_data['igst_amt'].'|'.$memIn_data['igst_total'].'|'.$memIn_data['qty'].'|'.$memIn_data['cess'].'|'.$memIn_data['state_code'].'|'.$memIn_data['state_name'].'|'.$memIn_data['service_code'].'|'.$memIn_data['gstin_no'].'|'.$memIn_data['tax_type']."\n";
									if($dir_flg)
									{
										// For photo images
										if($member_invoice_Image)
										{
											/*$image = "./uploads/reginvoice/supplier/".$memIn_data['invoice_image'];
												$max_width = "1000";
												$max_height = "1000";
												$imgdata = $this->resize_image_max($image,$max_width,$max_height);
												imagejpeg($imgdata, $directory."/".$memIn_data['invoice_image']);
												$photo_to_add = $directory."/".$memIn_data['invoice_image'];
												$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
											$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);*/
											copy("./uploads/reginvoice/supplier/".$memIn_data['invoice_image'],$directory."/".$memIn_data['invoice_image']);
											$photo_to_add = $directory."/".$memIn_data['invoice_image'];
											$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
											$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
											if(!$photo_zip_flg)
											{
												fwrite($fp1, "**ERROR** - Member Invoice Image not added to zip  - ".$memIn_data['invoice_image']." (".$memIn_data['member_no'].")\n");	
											}
											else
											$member_invoice_Image_cnt++;
										}
										if($photo_zip_flg)
										{
											$success['zip'] = "Member Invoice Images Zip Generated Successfully";
										}
										else
										{
											$error['zip'] = "Error While Generating Member Invoice Images Zip";
										}
									}
									$i++;
									$member_invoice_count++;
									//fwrite($fp1, "\n");
									$file_w_flg = fwrite($fp, $data);
								}
								if($file_w_flg)
								{
									$success['file'] = "Member Invoice Details File Generated Successfully. ";
								}
								else
								{
									$error['file'] = "Error While Generating Member Invoice Details File.";
								}
							}
						}
					}
					$zip->close();
					fwrite($fp1, "\n"."Total Member Invoice Details Added = ".$member_invoice_count."\n");
					fwrite($fp1, "\n"."Total Member Invoice Images Added = ".$member_invoice_Image_cnt."\n");
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
				$this->log_model->cronlog("IIBF Member Invoice Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."************************* IIBF Member Invoice Details Cron Execution End ".$end_time." *************************"."\n");
				fclose($fp1);
			}
		}
		
		/* Member Registration Invoices */ 
		public function mem_invoice_chaitali()
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
			$cron_file_dir = "./uploads/cronFilesCustom/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF Member Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "mem_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* IIBF Member Invoice Details Cron Execution Started - ".$start_time." ************************* \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$yesterday = '2020-12-15'; // temp
				$mem_invoice = array('M/20-21/025279','M/20-21/025272','M/20-21/025307','M/20-21/025281','M/20-21/025285','M/20-21/025401','M/20-21/025414','M/20-21/025417','M/20-21/025377','M/20-21/025434','M/20-21/025394','M/20-21/025347','M/20-21/025416','M/20-21/025429','M/20-21/025369','M/20-21/025386','M/20-21/025419','M/20-21/025441','M/20-21/025439','M/20-21/025461','M/20-21/025506','M/20-21/025564','M/20-21/025541','M/20-21/025553','M/20-21/025568','M/20-21/025593','M/20-21/025557','M/20-21/025603','M/20-21/025536','M/20-21/025549','M/20-21/025601','M/20-21/025575','M/20-21/025577','M/20-21/025542','M/20-21/025589','M/20-21/025597','M/20-21/025556','M/20-21/025559','M/20-21/025563','M/20-21/025585','M/20-21/025634','M/20-21/025635','M/20-21/025636','M/20-21/025637','M/20-21/025638','M/20-21/025639','M/20-21/025640','M/20-21/025641','M/20-21/025642','M/20-21/025645','M/20-21/025685','M/20-21/025690','M/20-21/025701','M/20-21/025703','M/20-21/025705','M/20-21/025721','M/20-21/025722','M/20-21/025723','M/20-21/025724','M/20-21/025725','M/20-21/025726','M/20-21/025727','M/20-21/025729','M/20-21/025730','M/20-21/025731','M/20-21/025733','M/20-21/025734','M/20-21/025735','M/20-21/025736','M/20-21/025737','M/20-21/025738','M/20-21/025739','M/20-21/025740','M/20-21/025741','M/20-21/025742','M/20-21/025743');
				$this->db->where_in('a.invoice_no', $mem_invoice);
				$new_mem_reg = $this->Master_model->getRecords('exam_invoice a',array('invoice_no !='=> '','app_type'=>'R'));
				//$new_mem_reg = $this->Master_model->getRecords('payment_transaction a',array('status'=>'1','pay_type'=>1));
				//' DATE(createdon)'=>$yesterday,
				//echo $this->db->last_query();
				//exit;
				if(count($new_mem_reg))
				{
					$i = 1;
					$member_invoice_count = 0;
					$member_invoice_Image_cnt = 0;
					$dirname = "mem_invoice_image_".$current_date;
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
					$registrationtype = 'O';
					foreach($new_mem_reg as $reg_data)
					{
						if($registrationtype != 'NM')
						{
							if($registrationtype == 'DB')
							{
								$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_DB','pay_type'=>2,'member_regnumber'=>$reg_data['regnumber'],'status'=>1),'id,receipt_no,pay_type');
							}
							else
							{//print_r($registrationtype); die;
								$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'iibfregn','status'=>1,'pay_type'=>1,'receipt_no'=>$reg_data['receipt_no']),'id,receipt_no,pay_type');
								echo $this->db->last_query(); die;
							}
						}
						else
						{//print_r($registrationtype); die;
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_REG','status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'id,receipt_no,pay_type');
							/*// Digital ELG Member Invoice
								if(empty($trans_details))
								{
								$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_ELR','status'=>1,'pay_type'=>18,'member_regnumber'=>$reg_data['regnumber']),'id,receipt_no,pay_type');
								}
								// CSC Member Invoice
								if(empty($trans_details))
								{
								$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'CSC_NM_REG','status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'id,receipt_no,pay_type');
							}*/
						}
						if(count($trans_details))
						{
							$pay_txn_id = $trans_details[0]['id'];
							$receipt_no = $trans_details[0]['receipt_no'];
							$pay_type = $trans_details[0]['pay_type'];
							// get invoice details for this member payment transaction by id and receipt_no
							$this->db->where('transaction_no !=','');
							$this->db->where('app_type','R');
							$this->db->where('receipt_no',$receipt_no);
							$member_invoice_arr = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
							//echo "<br>SQL => ".$this->db->last_query();
							if(count($member_invoice_arr))
							{
								foreach($member_invoice_arr as $memIn_data)
								{
									$data = '';
									$member_invoice_Image = '';
									$date_of_invoice = $memIn_data['date_of_invoice'];
									$Update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
									if(is_file("./uploads/reginvoice/supplier/".$memIn_data['invoice_image']))
									{
										$member_invoice_Image = MEMBER_INVOICE_FILE_PATH.$memIn_data['invoice_image'];
									}
									else
									{
										fwrite($fp1, "**ERROR** - Exam Invoice does not exist  - ".$memIn_data['invoice_image']." (".$memIn_data['member_no'].")\n");	
									}
									$data .= ''.$memIn_data['invoice_no'].'|'.$member_invoice_Image.'|'.$memIn_data['member_no'].'|'.$Update_date_of_invoice.'|'.$memIn_data['transaction_no'].'|'.$memIn_data['fee_amt'].'|'.$memIn_data['cgst_rate'].'|'.$memIn_data['cgst_amt'].'|'.$memIn_data['sgst_rate'].'|'.$memIn_data['sgst_amt'].'|'.$memIn_data['cs_total'].'|'.$memIn_data['igst_rate'].'|'.$memIn_data['igst_amt'].'|'.$memIn_data['igst_total'].'|'.$memIn_data['qty'].'|'.$memIn_data['cess'].'|'.$memIn_data['state_code'].'|'.$memIn_data['state_name'].'|'.$memIn_data['service_code'].'|'.$memIn_data['gstin_no'].'|'.$memIn_data['tax_type']."\n";
									if($dir_flg)
									{
										// For photo images
										if($member_invoice_Image)
										{
											/*$image = "./uploads/reginvoice/supplier/".$memIn_data['invoice_image'];
												$max_width = "1000";
												$max_height = "1000";
												$imgdata = $this->resize_image_max($image,$max_width,$max_height);
												imagejpeg($imgdata, $directory."/".$memIn_data['invoice_image']);
												$photo_to_add = $directory."/".$memIn_data['invoice_image'];
												$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
											$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);*/
											copy("./uploads/reginvoice/supplier/".$memIn_data['invoice_image'],$directory."/".$memIn_data['invoice_image']);
											$photo_to_add = $directory."/".$memIn_data['invoice_image'];
											$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
											$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
											if(!$photo_zip_flg)
											{
												fwrite($fp1, "**ERROR** - Member Invoice Image not added to zip  - ".$memIn_data['invoice_image']." (".$memIn_data['member_no'].")\n");	
											}
											else
											$member_invoice_Image_cnt++;
										}
										if($photo_zip_flg)
										{
											$success['zip'] = "Member Invoice Images Zip Generated Successfully";
										}
										else
										{
											$error['zip'] = "Error While Generating Member Invoice Images Zip";
										}
									}
									$i++;
									$member_invoice_count++;
									//fwrite($fp1, "\n");
									$file_w_flg = fwrite($fp, $data);
								}
								if($file_w_flg)
								{
									$success['file'] = "Member Invoice Details File Generated Successfully. ";
								}
								else
								{
									$error['file'] = "Error While Generating Member Invoice Details File.";
								}
							}
						}
					}
					$zip->close();
					fwrite($fp1, "\n"."Total Member Invoice Details Added = ".$member_invoice_count."\n");
					fwrite($fp1, "\n"."Total Member Invoice Images Added = ".$member_invoice_Image_cnt."\n");
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
				$this->log_model->cronlog("IIBF Member Invoice Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."************************* IIBF Member Invoice Details Cron Execution End ".$end_time." *************************"."\n");
				fclose($fp1);
			}
		}
		///usr/local/bin/php /home/supp0rttest/public_html/index.php Cron_invoice_test exam_invoice
		/* Exam invoices */
		public function exam_invoice()
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
			$cron_file_dir = "./uploads/cronFilesCustom/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF Exam Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "exam_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* IIBF Exam Invoice Details Cron Execution Started - ".$start_time." ************************* \n");
				//$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2021-03-12'; // temp
				$exam_code=array('1016');
				$this->db->where_in('a.exam_code', $exam_code);
				//$this->db->where('a.exam_period', '573');
				//$this->db->where_not_in('a.regnumber', $regnumber);
				//$this->db->where('DATE(a.created_on) >=', '2021-02-01');
				//$this->db->where('DATE(a.created_on) <=', '2021-02-25');
				//$this->db->where_not_in('a.id', $ref_id);
				//$regnumber = array();
				$select = 'a.examination_date,b.id as pay_txn_id,b.receipt_no';
			//	$ref_id = array(5460716,5469501,5479437,5480178,5480237,5480239,5480248,5480305,5480307,5480367,5480441,5480618,5480666,5480667,5480699,5480713,5480801,5480816,5480819,5480854,5480857,5480868,5480875,5480942,5480948,5480975,5481015,5481019,5481024,5481100,5481136,5481246,5481251,5481610,5481873,5481891,5481892,5481896,5481905,5481915,5481922,5481966,5486777,5486782,5486791,5486876,5486889,5486904,5486921,5486959,5486985,5486997,5487042,5487046,5487131,5487165,5487216,5487217,5487218,5487249,5487319,5487343,5487349,5485887,5487386,5487417,5487428,5487440,5487467,5487493,5487498,5487478,5487520,5487536,5487566,5487586,5487589,5487594,5487623,5487645,5487676,5487699,5487792,5487803,5487815,5487877,5487993,5488017,5488054,5488055,5488084,5488097,5488128,5488130,5488132,5488133,5488145,5488148,5488161,5488217,5488239,5488244,5488338,5488354,5488365,5488368,5488378,5488385,5488386,5488389,5488412,5488441,5488442,5488459,5488471,5488482,5488517,5488540,5488544,5488541,5488549,5488602,5488632,5488647,5488681,5488724,5488751,5488762,5488838,5488909,5488917,5488922,5488935,5488942,5489028,5489055,5489057,5489060,5489136,5489174,5489237,5489277,5489282,5489340,5489355,5489374,5489377,5489397,5489403,5489411,5489447,5489475,5489481,5489489,5489527,5489525,5489545,5489607,5489613,5489640,5489645,5489665,5489688,5489687,5489748,5489784,5489802,5489806,5489822,5489833,5489840,5489844,5489879,5489877,5489873,5489893,5489900,5489923,5489927,5489920,5489960,5489970,5489992,5490003,5490008,5490030,5490031,5490043,5490055,5490060,5490101,5490115,5490150,5490161,5490430,5490441,5490467,5490483,5490499,5490512,5490528,5490559,5490560,5490589,5490652,5490668,5490747,5490750,5490771,5490791,5490809,5492724,5498789,5498817,5498821,5498823,5498829,5498845,5498846,5498852,5498854,5498855,5498857,5498861,5498862,5498864,5498880,5498882,5498884,5498889,5498891,5498892,5498904,5498914,5498916,5498925,5498926,5498927,5498928,5498931,5498934,5498937,5498945,5498957,5498971,5498973,5498979,5498978,5498984,5498987,5498990,5498989,5499001,5499004,5498999,5499011,5499016,5499021,5499029,5499032,5499033,5499030,5499031,5499041,5499046,5499052,5499049,5499060,5499062,5499058,5499131,5499152,4943416,5499582,5504103,5504112,5504118,5504123,5504114,5504122,5504125,5504128,5504124,5504140,5504131,5504148,5504142,5504147,5504149,5504151,5504155,5504153,5504163,5504167,5504141,5504170,5504177,5504182,5504186,5504190,5503989,5504204,5504200,5504207,5504159,5504209,5504213,5504215,5504187,5504221,5504226,5504227,5504236,5504229,5504239,5504246,5504248,5504257,5504260,5504261,5504262,5504266,5504270,5504268,5504269,5504280,5504284,5504285,5504294,5504295,5504273,5504286,5504304,5504308,5504310,5504305,5504312,5504316,5504318,5504321,5504326,5504338,5504340,5504345,5504328,5504346,5504348,5503346,5504352,5504354,5504353,5504355,5504351,5504359,5504364,5504370,5504368,5504376,5504375,5504377,5504383,5504379,5504371,5504386,5504391,5504393,5504388,5504396,5504402,5504404,5504337,5504413,5504418,5504408,5504419,5504426,5504431,5502969,5504432,5504435,5504438,5504436,5504440,5504444,5504448,5504446,5504450,5504452,5504456,5504464,5504465,5504466,5504480,5504481,5504482,5504486,5504488,5504490,5504493,5504494,5504489,5504492,5504502,5504506,5504508,5504505,5504515,5504513,5504516,5504517,5504522,5504518,5504521,5504532,5504530,5504534,5504537,5504540,5504548,5504550,5504558,5504557,5504564,5504565,5504562,5504554,5504573,5504577,5504578,5504581,5504694,5504702,5504705,5504709,5504712,5504716,5504731,5504736,5504737,5504733,5504744,5504745,5504748,5504750,5504754,5504767,5504763,5504770,5504764,5504774,5504778,5504782,5504787,5504788,5504795,5504796,5504800,5504802,5504806,5504812,5504815,5504814,5504821,5504827,5504825,5504831,5502854,5504838,5504841,5504853,5504854,5504858,5504864,5504871,5504870,5504872,5504880,5504881,5504885,5504888,5504889,5504886,5504897,5504906,5504908,5504907,5504920,5504919,5504927,5504924,5504948,5504958,5504960,5504918,5504964,5504963,5504966,5504967,5504971,5504974,5504977,5504984,5504992,5504994,5504995,5505001,5505003,5505017,5505021,5505026,5505027,5505037,5505036,5505015,5505047,5505048,5505051,5505054,5505059,5505062,5505068,5505061,5505069,5505075,5505082,5505083,5505090,5505091,5505094,5505097,5505107,5505110,5505112,5505116,5505118,5505122,5505123,5505132,5505133,5505142,5505148,5505152,5505162,5505168,5505172,5505175,5505176,5505178,5505183,5505195,5505193,5505197,5505200,5505204,5505213,5505214,5505221,5505225,5505231,5505232,5505230,5505235,5505234,5505241,5505238,5505199,5505243,5505249,5505247,5505254,5505253,5505256,5505257,5505261,5505260,5505186,5505264,5505274,5505276,5505272,5505279,5505281,5505283,5505289,5505292,5505286,5505293,5505294,5505299,5505300,5505302,5505305,5505307,5505308,5505309,5505312,5505315,5505317,5505316,5505323,5505322,5505326,5505327,5505328,5505334,5505336,5505333,5505337,5505343,5505355,5505354,5505357,5505361,5505362,5505363,5505365,5505366,5505368,5505367,5505373,5505369,5505375,5505383,5505392,5505386,5505395,5505397,5505396,5505401,5505403,5505409,5505417,5505419,5505421,5505424,5505425,5505426,5505431,5505429,5505440,5505443,5505447,5505448,5505454,5505455,5505453,5505457,5505451,5505459,5505460,5505458,5505463,5505464,5505452,5505467,5505471,5505473,5505472,5505474,5505475,5505478,5505479,5505482,5505484,5505485,5505488,5505497,5505499,5505500,5505506,5505510,5505511,5505515,5505512,5505519,5505520,5505521,5505523,5505536,5505540,5505542,5505532,5505547,5505558,5505561,5505552,5505562,5505557,5505571,5505570,5505573,5505574,5505576,5505580,5505583,5505587,5505585,5505595,5505600,5505601,5505603,5505606,5505611,5505614,5505617,5505613,5505621,5505636,5505634,5505637,5505642,5505650,5505649,5505657,5505661,5505654,5505668,5505672,5505675,5505677,5505680,5505682,5505685,5505688,5505698,5505705,5505704,5505707,5505706,5505710,5505721,5505717,5505723,5505726,5505740,5505725,5505753,5505750,5505756,5505760,5505762,5505764,5505767,5505769,5505772,5505783,5505782,5505787,5505796,5505797,5505805,5505807,5503770,5505811,5505820,5505826,5505828,5505829,5505832,5505833,5505834,5505831,5505838,5505842,5505845,5505846,5505849,5505852,5505858,5505861,5505862,5505863,5505865,5505859,5505872,5505876,5505871,5505883,5505888,5505884,5505882,5505892,5505896,5505899,5505903,5505900,5505905,5505898,5505906,5505912,5505913,5505918,5505921,5505916,5505922,5505926,5505932,5505930,5505936,5505951,5505939,5505956,5505964,5505965,5505967,5505970,5505969,5505975,5505976,5505980,5505982,5505984,5505988,5505989,5505993,5506000,5506002,5506005,5506006,5506007,5506008,5506011,5506020,5506023,5506027,5506035,5506038,5506037,5506036,5506039,5506041,5506044,5506048,5506050,5506051,5506053,5506054,5506057,5506064,5506052,5506073,5506072,5506077,5506078,5506088,5506085,5506094,5506091,5506095,5506086,5506099,5506105,5506114,5506127,5506125,5506135,5506137,5506141,5506139,5506147,5506154,5506155,5506159,5506164,5506168,5506177,5506181,5506182,5506183,5506186,5506198,5506200,5506201,5506203,5506204,5506207,5506213,5506211,5506210,5506215,5506219,5506220,5506225,5506218,5506222,5506230,5506235,5506241,5506243,5506206,5506261,5506264,5506265,5506270,5506273,5506274,5506275,5506276,5506278,5506279,5506283,5506297,5506289,5506298,5506303,5506302,5506307,5506308,5506309,5506311,5506315,5506280,5506326,5506327,5506319,5506336,5506341,5506349,5506356,5506357,5506361,5506368,5506364,5506371,5506374,5506381,5506376,5506384,5506390,5506395,5506382,5506402,5506392,5506394,5506406,5506410,5506411,5506415,5506414,5506421,5506422,5506419,5506428,5506429,5506433,5506444,5506447,5506453,5506455,5506464,5506468,5506470,5506467,5506478,5506482,5506486,5506484,5506491,5506497,5506500,5506505,5506511,5506510,5506519,5506524,5506522,5506529,5506530,5506532,5506540,5506546,5506551,5506553,5506552,5506556,5506559,5506563,5506568,5506566,5506575,5506581,5506591,5506593,5506603,5506606,5506614,5506621,5506619,5506625,5506627,5506628,5506631,5506629,5506639,5506640,5506642,5506643,5506645,5506637,5506649,5506650,5506655,5506652,5506670,5506663,5506683,5506685,5506688,5506686,5506697,5506695,5506698,5506699,5506707,5506681,5506710,5506717,5506720,5506716,5506721,5506727,5506726,5506729,5506732,5506731,5506735,5506733,5506739,5506740,5506746,5506747,5506748,5506749,5506755,5506759,5506761,5506766,5506769,5506778,5506784,5506785,5506792,5506791,5506800,5506797,5506804,5506812,5506813,5506814,5506808,5506816,5506809,5506823,5506826,5506821,5506834,5506836,5506840,5506845,5506847,5506846,5506859,5506861,5506870,5506872,5506876,5506877,5506881,5506880,5506879,5506883,5506888,5506882,5506890,5506899,5506908,5506906,5506915,5506918,5506921,5506914,5506926,5506929,5506924,5506934,5506939,5506947,5506951,5506957,5506962,5506960,5506963,5506964,5506968,5506971,5506973,5506974,5506975,5506978,5506980,5506991,5507000,5507001,5506999,5507011,5507013,5507017,5507009,5506994,5507030,5507035,5507032,5507027,5507040,5507042,5507049,5507050,5507056,5507057,5507060,5507065,5507066,5507074,5507078,5507077,5507087,5507092,5507089,5507093,5507094,5507096,5507102,5507108,5507106,5507111,5507109,5507118,5507121,5507124,5507125,5507130,5507131,5507129,5507134,5507140,5507142,5507144,5507147,5507148,5507149,5507151,5507158,5507160,5507163,5507168,5507166,5507162,5507171,5507174,5507183,5507190,5507192,5507198,5507204,5507202,5507203,5507205,5507210,5507216,5507217,5507222,5507235,5507238,5507240,5507236,5507242,5507249,5507256,5507251,5507260,5507263,5507271,5507270,5507274,5507278,5507279,5507284,5507277,5507289,5507288,5507283,5507286,5507296,5507301,5507302,5507291,5507306,5507308,5507305,5507310,5507303,5507307,5507317,5507316,5507318,5507321,5507328,5507330,5507331,5507335,5507336,5507340,5507338,5507347,5507346,5507353,5507351,5507355,5507356,5507358,5507357,5507368,5507363,5507369,5507378,5507382,5507380,5507384,5507390,5507371,5507397,5507406,5507412,5507411,5507417,5507423,5507424,5507426,5507429,5507425,5507422,5507433,5507437,5507439,5507440,5507444,5507450,5507447,5507451,5507462,5507463,5507465,5507468,5507474,5507473,5507478,5507492,5507490,5507496,5507499,5507506,5507508,5507505,5507510,5507511,5507515,5507522,5507526,5507529,5507531,5507535,5507532,5507540,5507543,5507548,5507546,5507554,5507559,5507563,5507566,5507571,5507565,5507578,5507579,5507577,5507582,5507574,5507588,5507592,5507597,5507598,5507601,5507604,5507605,5507602,5507607,5507603,5507613,5507608,5507616,5507618,5507620,5507621,5507617,5507622,5507624,5507623,5507627,5507629,5507635,5507636,5507634,5507642,5507628,5503281,5507655,5507662,5507669,5507667,5507673,5507671,5507677,5507676,5507678,5507675,5507682,5507686,5507687,5507689,5507695,5507697,5507699,5507705,5507690,5507719,5507720,5507711,5507713,5507722,5507724,5507730,5507734,5507736,5507741,5507742,5507743,5507744,5507750,5507756,5507760,5507757,5507776,5507779,5507781,5507784,5507780,5507790,5507785,5507795,5507796,5507806,5507807,5507808,5507812,5507813,5507820,5507824,5507827,5507828,5507829,5507833,5507834,5507837,5507838,5507839,5507844,5507853,5507854,5507857,5507859,5507862,5507866,5507864,5507873,5507867,5507876,5507879,5507885,5507890,5507897,5507899,5507901,5507898,5507902,5507904,5507908,5507914,5507916,5507918,5507911,5507923,5507921,5507928,5507925,5507927,5507932,5507938,5507945,5507946,5507948,5507949,5507950,5507962,5507960,5507959,5507965,5507964,5507967,5507968,5507969,5507972,5507974,5507976,5507985,5507987,5507989,5507992,5507997,5508001,5508004,5508008,5508006,5508015,5508019,5508022,5508023,5508028,5508027,5508030,5508036,5508039,5508040,5508043,5508045,5508055,5508057,5508058,5508049,5508052,5508044,5508060,5508062,5508061,5508064,5508066,5508067,5508070,5508071,5508073,5508076,5508074,5508077,5508081,5508079,5508082,5508084,5508063,5508086,5508089,5508091,5508092,5508098,5508097,5508099,5508100,5508083,5508102,5508108,5508113,5508116,5508121,5508122,5508123,5508126,5508132,5508133,5508136,5508134,5508129,5508143,5508148,5508150,5508152,5508153,5508155,5508156,5508160,5508161,5508164,5508171,5508173,5508174,5508176,5508178,5508181,5508182,5508188,5508186,5508189,5508192,5508199,5508202,5508206,5508207,5508209,5508213,5508201,5508218,5508224,5508225,5508226,5508232,5508233,5508237,5508242,5508240,5508244,5508249,5508251,5508250,5508255,5508246,5508262,5508270,5508273,5508274,5508275,5508279,5508280,5508286,5508282,5508288,5508293,5508298,5508297,5508304,5508306,5508310,5508312,5508308,5508320,5508324,5508321,5508316,5508326,5508328,5508331,5508334,5508332,5508337,5508340,5508342,5508343,5508345,5508347,5508350,5508355,5508354,5508359,5508366,5508372,5508375,5508378,5508381,5508382,5508379,5508380,5508386,5508387,5508391,5508393,5508395,5508402,5508404,5508406,5508409,5508405,5508410,5508416,5508417,5508423,5508424,5508425,5508427,5508426,5508433,5508429,5508432,5508434,5508435,5508436,5508438,5508440,5508441,5508442,5508446,5508447,5508445,5508452,5508453,5508450,5508458,5508463,5508466,5508465,5508468,5508464,5508469,5508475,5508476,5508477,5508633,5508796,5508799,5508800,5508805,5508807,5508812,5508816,5508821,5508823,5508828,5508829,5508831,5508835,5508839,5508833,5508834,5508842,5508843,5508847,5508848,5508850,5508864,5508868,5508871,5508873,5508874,5508879,5508882,5508884,5508886,5508902,5508903,5508904,5508906,5508909,5508907,5508912,5508915,5508923,5508924,5508925,5508926,5508929,5508934,5508941,5508942,5508944,5508947,5508948,5508954,5508957,5508964,5508973,5508978,5508980,5508982,5508984,5508988,5508990,5508992,5508994,5508997,5508999,5509005,5509009,5509010,5509012,5509015,5509020,5509037,5509039,5509040,5509042,5509043,5509047,5509049,5509050,5509054,5509058,5509063,5509069,5509071,5509074,5509076,5509077,5509081,5509091,5509089,5509096,5509097,5509099,5509101,5509107,5509109,5509120,5509122,5509123,5509126,5509136,5509138,5509139,5509142,5509161,5509165,5509168,5509178,5509180,5509182,5509185,5509189,5509193,5509197,5509199,5509205,5509209,5509207,5509213,5509212,5509220,5509222,5509227,5509228,5509234,5509243,5509245,5509246,5509248,5509267,5509269,5509273,5509277,5509279,5509280,5509284,5509285,5509293,5509297,5509296,5509299,5509302,5509304,5509311,5509316,5509318,5509323,5509324,5509325,5509329,5509335,5509337,5509347,5509352,5509354,5509357,5509359,5509365,5509371,5509379,5509376,5509380,5509387,5509391,5509390,5509400,5509401,5509405,5509407,5509408,5509411,5509409,5509421,5509422,5509431,5509443,5509447,5509448,5509450,5509451,5509453,5509459,5509460,5509462,5509465,5509467,5509471,5509470,5509480,5509481,5509484,5509500,5509499,5509502,5509505,5509510,5509515,5509517,5509518,5509519,5509520,5509521,5509529,5509534,5509535,5509539,5509548,5509546,5509552,5509558,5509562,5509568,5509571,5509576,5509577,5509584,5509586,5509585,5509593,5509606,5509618,5509617,5509626,5509628,5509633,5509637,5509638,5509640,5509639,5509642,5509646,5509657,5509660,5509663,5509664,5509666,5509668,5509672,5509675,5509669,5509687,5509688,5509689,5509694,5509692,5509691,5509697,5509698,5509701,5509702,5509700,5509703,5509708,5509709,5509719,5509720,5509722,5509728,5509731,5509729,5509732,5509734,5509738,5509737,5509740,5509747,5509749,5509751,5509753,5509755,5509758,5509762,5509768,5509769,5509775,5509781,5509780,5509785,5509783,5509790,5509795,5509798,5509797,5509802,5509805,5509801,5509806,5509808,5509809,5509811,5509815,5509818,5509825,5509832,5509835,5509834,5509837,5509840,5509842,5509845,5509847,5509855,5509859,5509860,5509863,5509866,5509873,5509875,5509874,5509872,5509876,5509884,5509885,5509883,5509889,5509890,5509858,5509895,5509896,5509900,5509907,5509906,5509914,5509915,5509916,5509920,5509924,5509925,5509929,5509928,5509930,5509935,5509937,5509936,5509941,5509945,5509946,5509948,5509956,5509959,5509958,5509960,5509963,5509969,5509971,5509972,5509974,5509968,5509981,5509984,5509987,5509990,5509996,5509993,5509999,5509998,5510002,5510005,5510007,5510009,5510008,5510010,5510013,5510019,5510026,5510027,5510029,5510033,5510036,5510038,5510041,5510044,5510045,5510046,5510048,5510049,5510040,5510051,5510057,5510066,5510070,5510072,5510068,5510073,5510079,5510082,5510085,5510086,5510091,5510099,5510097,5510103,5510108,5510109,5510114,5510118,5510129,5510130,5510132,5510136,5510141,5510145,5510143,5510147,5510151,5510153,5510156,5510160,5510162,5510163,5510164,5510166,5510167,5510171,5510175,5510177,5510180,5510185,5510188,5510190,5510195,5510196,5510199,5510194,5510201,5510204,5510208,5510212,5510217,5510219,5510222,5510224,5510225,5510228,5510230,5510235,5510232,5510236,5510243,5510244,5510247,5510246,5510252,5510256,5510263,5510266,5510272,5510280,5510281,5510284,5510285,5510287,5510288,5510289,5510295,5510301,5510303,5510304,5510306,5510309,5510308,5510314,5510313,5510316,5510319,5510317,5510320,5510322,5510312,5510329,5510325,5510328,5510332,5510339,5510338,5510345,5510347,5510355,5510354,5510360,5510358,5510363,5510367,5510378,5510377,5510386,5510388,5510392,5510393,5510395,5510394,5510396,5510397,5510407,5510408,5510410,5510415,5510425,5510436,5510438,5510442,5510439,5510447,5510446,5510451,5510452,5510440,5510456,5510453,5510457,5510459,5510463,5510469,5510476,5510473,5510478,5510479,5510483,5510481,5510487,5510482,5510488,5510491,5510500,5510495,5510508,5510511,5510514,5510517,5510520,5510531,5510535,5510528,5510556,5510560,5510559,5510562,5510563,5510568,5510570,5510576,5510584,5510585,5510592,5510593,5510591,5510594,5510590,5510596,5510599,5510603,5510607,5510610,5510608,5510609,5510615,5510620,5510616,5510619,5510622,5510624,5510626,5510628,5510637,5510639,5510634,5510641,5510643,5510646,5510656,5510666,5510670,5510673,5510674,5510672,5510669,5510676,5510679,5510682,5510683,5510687,5510688,5510690,5510702,5510701,5510705,5510710,5510711,5510708,5510716,5510719,5510724,5510725,5510726,5510728,5510727,5510733,5510736,5510742,5510745,5510748,5510750,5510757,5510756,5510758,5510755,5510765,5510774,5510769,5510775,5510792,5510798,5510801,5510806,5510808,5510809,5510812,5510817,5510820,5510823,5510818,5510831,5510837,5510848,5510851,5510855,5510856,5510865,5510864,5510868,5510873,5510875,5510880,5510882,5510884,5510885,5510886,5510887,5510889,5510895,5510894,5510902,5510907,5510908,5510913,5510912,5510911,5510914,5510916,5510917,5510920,5510924,5510926,5510927,5510929,5510931,5510934,5510935,5510936,5510944,5510942,5510941,5510948,5510949,5510951,5510953,5510955,5510958,5510961,5510967,5510968,5510971,5510972,5510978,5510980,5510976,5510983,5510984,5510988,5510993,5510994,5510996,5510999,5511001,5510997,5511006,5511008,5511010,5511013,5511018,5511023,5511029,5511034,5511033,5511039,5511040,5511044,5511053,5511051,5511056,5511057,5511060,5511063,5511067,5511066,5511069,5511072,5511074,5511084,5511089,5511093,5511096,5511097,5511099,5511102,5511103,5511114,5511119,5511122,5511125,5511118,5511131,5511130,5511136,5511137,5511139,5511143,5511145,5511146,5511144,5511147,5511155,5511162,5511164,5511165,5511172,5511175,5511186,5511182,5511187,5511207,5511211,5511216,5511218,5511236,5511238,5511241,5511245,5511248,5511257,5511267,5511278,5511284,5511291,5511294,5511296,5511305,5511306,5511310,5511311,5511314,5511315,5511317,5511318,5511319,5511324,5511327,5511328,5511323,5511339,5511332,5511349,5511355,5511345,5511357,5511361,5511366,5511367,5511370,5511371,5511376,5511377,5511380,5511384,5511388,5511392,5511405,5511402,5511411,5511421,5511436,5511438,5511441,5511446,5511447,5511450,5511410,5511455,5511454,5511460,5511458,5511463,5511464,5511468,5511473,5511478,5511481,5511484,5511485,5511493,5511496,5511499,5511500,5511504,5511514,5511515,5511517,5511521,5511522,5511530,5511537,5511538,5511541,5511544,5511547,5511548,5511553,5511560,5511564,5511565,5511563,5511566,5511569,5511573,5511571,5511577,5511579,5511580,5511582,5511584,5511589,5511591,5511590,5511595,5511600,5511602,5511596,5511617,5511618,5511627,5511599,5511638,5511639,5511647,5511652,5511656,5511653,5511658,5511663,5511670,5511672,5511674,5511677,5511680,5511684,5511686,5511688,5511691,5511692,5511695,5511697,5511700,5511699,5511708,5511710,5511713,5511715,5511712,5511716,5511719,5511720,5511726,5511736,5511738,5511742,5511740,5511744,5511745,5511746,5511752,5511756,5511757,5511761,5511771,5511772,5511776,5511777,5511779,5511787,5511789,5511790,5511796,5511801,5511802,5511799,5511797,5511807,5511816,5511823,5511833,5511835,5511841,5511842,5511850,5511849,5511857,5511858,5511859,5511860,5511864,5511862,5511867,5511871,5511876,5511879,5511880,5511883,5511886,5511894,5511898,5511903,5511908,5511911,5511907,5511914,5511917,5511918,5511919,5511921,5511925,5511927,5511931,5511933,5511937,5511939,5511938,5511951,5511959,5511962,5511958,5511649,5511976,5511980,5512002,5512003,5512017,5511995,5512014,5512023,5512030,5512031,5512038,5512041,5512080,5514479,5514477,5514486,5514496,5514498,5514478,5513209,5514519,5514525,5514526,5514527,5514534,5514115,5514543,5514554,5514558,5514568,5514572,5514575,5512886,5514581,5514589,5514590,5514597,5514599,5514602,5514607,5514610,5514601,5514614,5514625,5514578,5514630,5513609,5514636,5514637,5514644,5514576,5514650,5514652,5514651,5514656,5514661,5514664,5514676,5514678,5514690,5514691,5514694,5514699,5514695,5514703,5514705,5514709,5514710,5514717,5514718,5514727,5514730,5514732,5514735,5514738,5514740,5514741,5514742,5514744,5514747,5514750,5514749,5514751,5514752,5514755,5514757,5514766,5514768,5514772,5514771,5514782,5514777,5514785,5514815,5514813,5514816,5514820,5514821,5514825,5514829,5514832,5514831,5514835,5514841,5514847,5514849,5514850,5514854,5514856,5514865,5514866,5514870,5514891,5514902,5514907,5514906,5514908,5514918,5514915,5514921,5514000,5514924,5514171,5514928,5514912,5514929,5514933,5514937,5514943,5513005,5514962,5514974,5514976,5514987,5514988,5514993,5514994,5514999,5515000,5515002,5515004,5515010,5515014,5515032,5515036,5515041,5515045,5515047,5515048,5515049,5515054,5515057,5515060,5515070,5515073,5515081,5515080,5515089,5515090,5515093,5515105,5515132,5515133,5515134,5515135,5515139,5515137,5515155,5514294,5515167,5515168,5515171,5515172,5515176,5515180,5515184,5515188,5515195,5515197,5515199,5515216,5515220,5515229,5515233,5515248,5515250,5515268,5515282,5515284,5515286,5515290,5515293,5515299,5515300,5515305,5515303,5515318,5515322,5515325,5515326,5515331,5515336,5515345,5515347,5515351,5515359,5515365,5515367,5515389,5515392,5515401,5515405,5515408,5515411,5515416,5515421,5515423,5515429,5515432,5515433,5515436,5515435,5515437,5515440,5515446,5515450,5515453,5515457,5515456,5515472,5515473,5515463,5515478,5515481,5515484,5515477,5515500,5515501,5515507,5515508,5515526,5515531,5515529,5515532,5515536,5515521,5515554,5515557,5515559,5515562,5515563,5515568,5515572,5515573,5515574,5515577,5515578,5515587,5515594,5515597,5515608,5515613,5515614,5515617,5515619,5515618,5515632,5515627,5515634,5515640,5515641,5515644,5515646,5515649,5515648,5515653,5515652,5515654,5515659,5515661,5515664,5515668,5515665,5515676,5515678,5515680,5515681,5515686,5515693,5515699,5515700,5515703,5515704,5515707,5515710,5515714,5515716,5515720,5515718,5515725,5515730,5515735,5515740,5515744,5515738,5515753,5515764,5515772,5515777,5515775,5515784,5515795,5515797,5515803,5515825,5515831,5515840,5515841,5515846,5515843,5515865,5515871,5515876,5515883,5515902,5515904,5515905,5515913,5515920,5515924,5515930,5515931,5515932,5515921,5515941,5515951,5515954,5515959,5515964,5515965,5515967,5515971,5515982,5515998,5516006,5516011,5516018,5516026,5516031,5516032,5516035,5516126,5516949,5519623,5519134,5518625,5519667,5518229,5519698,5519762,5519765,5519801,5519834,5519958,5520068,5520068,4446802,5520312,5520567,5522667,4624031,5524921,5525101,5526662,5533236,5538792,5544415,5544579,5544585,5545080,5546224,5547267,5548547,5548876,5550194,5550260,5550501,5550575,5550671,5551313,5551326,5551419,5551439,5551651,5552081,5552217,5552323,5552667,5552949,5553443,5554684,5554686,5554690,5554691,5554696,5554697,5554698,5554699,5554701,5554702,5554703,5554711,5554712,5554713,5554719,5554721,5554723,5554724,5554728,5554729,5554731,5554732,5554737,5554738,5554742,5554743,5554744,5554746,5554748,5554750,5554752,5554757,5554758,5554756,5554760,5554761,5554767,5554773,5554774,5554780,5554778,5554781,5554786,5554787,5554789,5554793,5554796,5554800,5554807,5554808,5554813,5554814,5554822,5554826,5554828,5554832,5554834,5554835,5554837,5554838,5554839,5554843,5554845,5554846,5554849,5554852,5554854,5554861,5554862,5554865,5554866,5554871,5554874,5554875,5554876,5554878,5554879,5554880,5554881,5554882,5554883,5554905,5554920,5555011,5555020,5555089,5555129,5555168,5555169,5555212,5555292,5555301,5555532,5555573,5555621,5555763,5555771,5555882,5555929,5555995,5556035,5556127,5556184,5556200,5556258,5556291,5556315,5556367,5556400,5556404,5556406,5556411,5556415,5556417,5556421,5556420,5556425,5556432,5556437,5556440,5556455,5556460,5556462,5556464,5556472,5556466,5556476,5556478,5556483,5556485,5556487,5556489,5556493,5556492,5556498,5556499,5556501,5556504,5556505,5556513,5556521,5556524,5556527,5556530,5556538,5556539,5556541,5556543,5556546,5556548,5556554,5556563,5556559,5556566,5556572,5556574,5556576,5556582,5556590,5556594,5556597,5556598,5556599,5556601,5556605,5556608,5556611,5556619,5556623,5556622,5556600,5556629,5556644,5556652,5556653,5556674,5556675,5556677,5556688,5556689,5556691,5556696,5556700,5556701,5556704,5556703,5556706,5556710,5556713,5556724,5556736,5556737,5556733,5556746,5556750,5556752,5556757,5556758,5556759,5556763,5556765,5556771,5556772,5556776,5556778,5556779,5556782,5556785,5556787,5556788,5556791,5556796,5556798,5556804,5556807,5556809,5556810,5556793,5556814,5556811,5556815,5556813,5556734,5556817,5556820,5556821,5556823,5556828,5556825,5556790,5556836,5556839,5556841,5556843,5556829,5556847,5556846,5556850,5556844,5556855,5556860,5556870,5556873,5556875,5556877,5556878,5556879,5556880,5556884,5556885,5556887,5556888,5556890,5556897,5556896,5556889,5556900,5556898,5556901,5556912,5556913,5556915,5556918,5556920,5556919,5556925,5556937,5556940,5556938,5556947,5556961,5556962,5556969,5556968,5556990,5556993,5556960,5556991,5556994,5556992,5557003,5557008,5557013,5557007,5557020,5557019,5557030,5557031,5557037,5557038,5557048,5557045,5557049,5557051,5557054,5557056,5557059,5557057,5557072,5557070,5557080,5557085,5557088,5557089,5557087,5557094,5557096,5557100,5557101,5557103,5557106,5557109,5557116,5557118,5557120,5557122,5557123,5557125,5557124,5557126,5557111,5557129,5557133,5557135,5557130,5557137,5557140,5557149,5557154,5557155,5557158,5557162,5557170,5557174,5557178,5557183,5557185,5557194,5557193,5557186,5557195,5557199,5557201,5557206,5557214,5557216,5557215,5557217,5557220,5557219,5557224,5557245,5557262,5557265,5557267,5557248,5557271,5557281,5557292,5557293,5557296,5557299,5557301,5557307,5557311,5557314,5557318,5557321,5557323,5557340,5557342,5557347,5557346,5557349,5557352,5557351,5557358,5557359,5557364,5557372,5557376,5557377,5557380,5557379,5557386,5557388,5557389,5557390,5557394,5557395,5557396,5557398,5557407,5557416,5557418,5557420,5557421,5557423,5557427,5557428,5557429,5557432,5557438,5557439,5557447,5557451,5557458,5557455,5557463,5557464,5557466,5557468,5557471,5557475,5557477,5557480,5557478,5557481,5557490,5557494,5557503,5557504,5557506,5557512,5557523,5557527,5557528,5557529,5557531,5557537,5557530,5557543,5557548,5557549,5557551,5557554,5557552,5557555,5557556,5557559,5557562,5557563,5557566,5557565,5557573,5557577,5557580,5557583,5557584,5557585,5557589,5557588,5557586,5557594,5557595,5557600,5557601,5557590,5557602,5557603,5557604,5557592,5557606,5557607,5557609,5557608,5557611,5557610,5557613,5557614,5557617,5557618,5557630,5557634,5557633,5557635,5557643,5557639,5557642,5557638,5557645,5557648,5557657,5557659,5557656,5557663,5557670,5557671,5557677,5557678,5557680,5557682,5557684,5557689,5557692,5557694,5557698,5557691,5557701,5557703,5557704,5557708,5557710,5557718,5557717,5557721,5557722,5557724,5557726,5557729,5557714,5557736,5557739,5557742,5557735,5557744,5557747,5557749,5557755,5557756,5557758,5557761,5557763,5557766,5557768,5557773,5557777,5557779,5557780,5557782,5557785,5557789,5557793,5557794,5557796,5557799,5557810,5557816,5557821,5557828,5557832,5557831,5557841,5557844,5557845,5557846,5557848,5557847,5557853,5557849,5557855,5557858,5557860,5557862,5557863,5557869,5557872,5557878,5557880,5557882,5557884,5557890,5557894,5557896,5557895,5557905,5557908,5557909,5557912,5557914,5557922,5557925,5557930,5557935,5557934,5557944,5557947,5557950,5557952,5557955,5557961,5557962,5557965,5557971,5557974,5557975,5557978,5557982,5557983,5557988,5557989,5557992,5557997,5557998,5558006,5558008,5558010,5558013,5558017,5558018,5558030,5558032,5558037,5558036,5558039,5558044,5558047,5558049,5558053,5558051,5558056,5558057,5558058,5558060,5558059,5558065,5558067,5558071,5558073,5558075,5558080,5558082,5558087,5558094,5558093,5558078,5558096,5558097,5558103,5558102,5558105,5558109,5558113,5558117,5558119,5558125,5558128,5558129,5558132,5558134,5558138,5558140,5558147,5558146,5558149,5558152,5558153,5558155,5558158,5558159,5558160,5558162,5558165,5558164,5558163,5558169,5558170,5558168,5558174,5558176,5558179,5558183,5558182,5558184,5558195,5558199,5558201,5558202,5558204,5558206,5558209,5558210,5558213,5558215,5558219,5558220,5558225,5558227,5558229,5558232,5558231,5558240,5558235,5558243,5558242,5558249,5558251,5558252,5558254,5558260,5558263,5558262,5558269,5558270,5558274,5558268,5558278,5558283,5558286,5558292,5558293,5558296,5558299,5558300,5558308,5558312,5558306,5558310,5558314,5558307,5558316,5558318,5558319,5558320,5558325,5558329,5558335,5558336,5558340,5558342,5558345,5558346,5558348,5558350,5558361,5558364,5558366,5558370,5558372,5558375,5558373,5558374,5558376,5558378,5558379,5558380,5558382,5558384,5558385,5558386,5558389,5558396,5558398,5558403,5558410,5558411,5558419,5558423,5558426,5558427,5558434,5558441,5558442,5558444,5558446,5558447,5558323,5558449,5558451,5558457,5558460,5558458,5558456,5558462,5558461,5558464,5558470,5558467,5558471,5558473,5558472,5558477,5558479,5558486,5558490,5558491,5558497,5558498,5558499,5558504,5558502,5558508,5558509,5558515,5558521,5558524,5558525,5558513,5558537,5558541,5558548,5558549,5558551,5558558,5558562,5558557,5558564,5558568,5558576,5558574,5558578,5558579,5558582,5558586,5558588,5558590,5558591,5558597,5558594,5558600,5558593,5558607,5558605,5558624,5558623,5558628,5558629,5558626,5558631,5558642,5558643,5558646,5558652,5558656,5558655,5558658,5558660,5558661,5558663,5558653,5558668,5558670,5558673,5558675,5558676,5558679,5558685,5558686,5558687,5558683,5558688,5558691,5558694,5558697,5558698,5558701,5558707,5558709,5558708,5558715,5558728,5558736,5558737,5558744,5558741,5558745,5558749,5558751,5558754,5558757,5558767,5558769,5558770,5558771,5558774,5558780,5558781,5558782,5558784,5558792,5558787,5558800,5558797,5558807,5558808,5558810,5558818,5558819,5558820,5558826,5558823,5558831,5558833,5558838,5558836,5558845,5558856,5558862,5558865,5558870,5558872,5558873,5558877,5558882,5558884,5558890,5558893,5558902,5558906,5558912,5558916,5558914,5558917,5558930,5558932,5558944,5558945,5558950,5558954,5558967,5558969,5558972,5558989,5558980,5558991,5558992,5559002,5559006,5559014,5559020,5559023,5559030,5559033,5559054,5559059,5559067,5559068,5559071,5559072,5559089,5559091,5559094,5559117,5559121,5559118,5559119,5559123,5559131,5559134,5559137,5559138,5559149,5559147,5559152,5559154,5559156,5559159,5559161,5559167,5559170,5559175,5559177,5559179,5559180,5559181,5559184,5559190,5559192,5559194,5559198,5559197,5559200,5559202,5559203,5559210,5559214,5559216,5559219,5559220,5559218,5559221,5559228,5559230,5559232,5559235,5559238,5559243,5559244,5559245,5559247,5559249,5559251,5559252,5559253,5559256,5559258,5559263,5559262,5559265,5559275,5559274,5559276,5559278,5559280,5559284,5559289,5559287,5559290,5559291,5559294,5559295,5559296,5559298,5559299,5559302,5559304,5559307,5559312,5559310,5559320,5559322,5559323,5559327,5559328,5559326,5559331,5559329,5559336,5559337,5559342,5559344,5559343,5559347,5559348,5559353,5559355,5559360,5559362,5559363,5559366,5559369,5559368,5559372,5559374,5559376,5559377,5559378,5559379,5559380,5559381,5559385,5559387,5559386,5559388,5559389,5559390,5559391,5559392,5559394,5559408,5559411,5559414,5559420,5559423,5559427,5559428,5559430,5559434,5559435,5559437,5559442,5559440,5559443,5559446,5559450,5559447,5559455,5559458,5559439,5559461,5559466,5559454,5559468,5559470,5559474,5559476,5559480,5559481,5559484,5559483,5559488,5559489,5559491,5559492,5559494,5559498,5559496,5559497,5559502,5559504,5559505,5559508,5559507,5559512,5559511,5559519,5559520,5559522,5559525,5559526,5559527,5559531,5559530,5559536,5559539,5559537,5559538,5559543,5559544,5559545,5559546,5559547,5559550,5559549,5559548,5559553,5559556,5559555,5559560,5559558,5559564,5559569,5559570,5559566,5559577,5559580,5559579,5559583,5559585,5559589,5559574,5559595,5559594,5559596,5559598,5559604,5559603,5559599,5559606,5559607,5559611,5559615,5559617,5559619,5559620,5559624,5559623,5559628,5559631,5559632,5559637,5559643,5559640,5559644,5559645,5559646,5559647,5559650,5559649,5559652,5559655,5559663,5559662,5559664,5559666,5559671,5559675,5559678,5559684,5559686,5559691,5559698,5559688,5559699,5559709,5559710,5559712,5559713,5559714,5559719,5559722,5559721,5559723,5559725,5559724,5559727,5559730,5559732,5559733,5559741,5559742,5559744,5559748,5559750,5559753,5559752,5559756,5559757,5559758,5559759,5559762,5559746,5559771,5559774,5559776,5559778,5559779,5559780,5559782,5559784,5559789,5559791,5559876,5560554,5560559,5560563,5560564,5560566,5560567,5560569,5560572,5560573,5560580,5560581,5560583,5560587,5560591,5560593,5560597,5560595,5560600,5560599,5560602,5560604,5560606,5560609,5560610,5560614,5560615,5560616,5560619,5560620,5560618,5560624,5560625,5560626,5560629,5560635,5560634,5560636,5560642,5560644,5560647,5560650,5560412,5560651,5560652,5560656,5560657,5560655,5560659,5560663,5560665,5560662,5560666,5560664,5560667,5560670,5560673,5560675,5560674,5560676,5560678,5560683,5560679,5560684,5560687,5560693,5560694,5560695,5560705,5560702,5560707,5560706,5560711,5560715,5560718,5560719,5560722,5560721,5560728,5560726,5560730,5560734,5560739,5560745,5560744,5560748,5560750,5560752,5560753,5560759,5560758,5560761,5560763,5560762,5560769,5560771,5560768,5560774,5560777,5560780,5560783,5560784,5560789,5560791,5560792,5560793,5560794,5560795,5560797,5560801,5560802,5560806,5560807,5560809,5560812,5560811,5560816,5560818,5560819,5560821,5560815,5560822,5560823,5560828,5560830,5560829,5560836,5560833,5560840,5560845,5560843,5560847,5560848,5560850,5560853,5560852,5560854,5560855,5560856,5560857,5560865,5560869,5560872,5560873,5560871,5560880,5560882,5560878,5560886,5560889,5560891,5560894,5560898,5560900,5560899,5560901,5560903,5560905,5560908,5560909,5560912,5560915,5560918,5560919,5560920,5560921,5560925,5560926,5560929,5560928,5560924,5560932,5560931,5560940,5560941,5560943,5560946,5560950,5560952,5560953,5560954,5560955,5560959,5560960,5560958,5560962,5560961,5560963,5560964,5560965,5560969,5560970,5560968,5560975,5560974,5560979,5560980,5560982,5560984,5560988,5560991,5560992,5560994,5561000,5560998,5560999,5561001,5561002,5561009,5561006,5561007,5561012,5561010,5561015,5561018,5561020,5561022,5561026,5560981,5561031,5561028,5561032,5561033,5561036,5561038,5561039,5561041,5561045,5561042,5561051,5561049,5561054,5561053,5561048,5561058,5561055,5561060,5561063,5561065,5561064,5561069,5561071,5561072,5561073,5561077,5561075,5561082,5561085,5561089,5561090,5561091,5561083,5561095,5561102,5561098,5561103,5561106,5561105,5561108,5561092,5561110,5561114,5561115,5561120,5561122,5561124,5561126,5561125,5561130,5561134,5561137,5561139,5561133,5561140,5561142,5561144,5561145,5561146,5561148,5561147,5561141,5561152,5561153,5561156,5561157,5561154,5561163,5561165,5561166,5561167,5561170,5561174,5561172,5561177,5561178,5561180,5561182,5561183,5561187,5561189,5561188,5561186,5561190,5561192,5561196,5561197,5561202,5560241,5561203,5561205,5561201,5561211,5561207,5561213,5561209,5561215,5561212,5561216,5561218,5561217,5561222,5561219,5561224,5561223,5561227,5561228,5561214,5561230,5561229,5561232,5561233,5561240,5561235,5561242,5561243,5561239,5561241,5561244,5561245,5561246,5561252,5561256,5561254,5561260,5561263,5561261,5561264,5561266,5561267,5561268,5561270,5561271,5561273,5561276,5561272,5561278,5561281,5561280,5561285,5561286,5561282,5561287,5561292,5561294,5561295,5561298,5561300,5561305,5561307,5561309,5561308,5561315,5561321,5561322,5561326,5560314,5561331,5561329,5561330,5561333,5561334,5561335,5561337,5561339,5561342,5561343,5561344,5561345,5561348,5561349,5561350,5561356,5561355,5561358,5561359,5561360,5561361,5561364,5561362,5561366,5561368,5561371,5561375,5561376,5561380,5561377,5561384,5561385,5561386,5561390,5561398,5561399,5561397,5561400,5561404,5561405,5561389,5561407,5561410,5561408,5561409,5561417,5561419,5561420,5561421,5561425,5561423,5561426,5561432,5561434,5561437,5561428,5561441,5561442,5561443,5561446,5561445,5561447,5561449,5561450,5561451,5561452,5561455,5561453,5561456,5561461,5561458,5561462,5561465,5561466,5561464,5561467,5561468,5561473,5561474,5561478,5561477,5561479,5561480,5561485,5561489,5561491,5561494,5561483,5561496,5561493,5561498,5561500,5561499,5561501,5561507,5561505,5561509,5561513,5561518,5561519,5561517,5561524,5561528,5561523,5561530,5561532,5561531,5561535,5561537,5561545,5561548,5561549,5561553,5561551,5561560,5561563,5561565,5561564,5561567,5561562,5561569,5561566,5561571,5561572,5561573,5561574,5561575,5561561,5561580,5561579,5561586,5561582,5561590,5561594,5561598,5561593,5561595,5561597,5561596,5561604,5561603,5561609,5561610,5561611,5561613,5561605,5561614,5561620,5561624,5561623,5561619,5561625,5561626,5561621,5561627,5561630,5561632,5561622,5561637,5561636,5561638,5561641,5561642,5561646,5561648,5561655,5561654,5561657,5561651,5561659,5561664,5561662,5561663,5561660,5561665,5561666,5561667,5561671,5561672,5561674,5561678,5561682,5561684,5561686,5561687,5561688,5561685,5561689,5561690,5561694,5561691,5561693,5561702,5561704,5561706,5561707,5561708,5561714,5561716,5561717,5561713,5561711,5561718,5561721,5561722,5561730,5561736,5561737,5561739,5561738,5561742,5561744,5561745,5561743,5561748,5561750,5561751,5561753,5561752,5561756,5561758,5561760,5561761,5561762,5561763,5561764,5561767,5561771,5561769,5561765,5561773,5561776,5561777,5561778,5561781,5561791,5561793,5561798,5561799,5561807,5561810,5561812,5561816,5561822,5561826,5561825,5561820,5561828,5561833,5561834,5561835,5561819,5561838,5561842,5561843,5561847,5561858,5561854,5561860,5561855,5561863,5561865,5561870,5561871,5561866,5561880,5561885,5561884,5561889,5561888,5561890,5561894,5561892,5561897,5561901,5561900,5561902,5561898,5561904,5561899,5561912,5561913,5561905,5561914,5561916,5561867,5561919,5561924,5561918,5561921,5561925,5561926,5561927,5561928,5561931,5561932,5561934,5561936,5561935,5561939,5561940,5561946,5561938,5561942,5561948,5561945,5561951,5561952,5561949,5561955,5561956,5561960,5561961,5561953,5561963,5561968,5561967,5561971,5561972,5561974,5561977,5561976,5561975,5561983,5561984,5561980,5561982,5561990,5561991,5561994,5561996,5561999,5561987,5562002,5562005,5562004,5562011,5562014,5562018,5562020,5562022,5562023,5562025,5562032,5562041,5562039,5562046,5562038,5562040,5562049,5562051,5562045,5562052,5562055,5562053,5562058,5562057,5562059,5562064,5562067,5562068,5562071,5562073,5562074,5562077,5562075,5562081,5562083,5562086,5562085,5562089,5562091,5562093,5562097,5562103,5562100,5562098,5562078,5562101,5562102,5562104,5562108,5562105,5562110,5562112,5562113,5562114,5562116,5562120,5562128,5562129,5562126,5562130,5562123,5562131,5562135,5562138,5562136,5562143,5562140,5562145,5562148,5562150,5562147,5562151,5562154,5562160,5562167,5562168,5562156,5562169,5562171,5562172,5562163,5562178,5562179,5562164,5562181,5562186,5562184,5562185,5562189,5562188,5562193,5562191,5562194,5562199,5562201,5562195,5562204,5562208,5562209,5562207,5562200,5562211,5562215,5562220,5562221,5562225,5562226,5562227,5562228,5562233,5562231,5562239,5562238,5562241,5562223,5562244,5562240,5562246,5562245,5562249,5562250,5562254,5562253,5562256,5562257,5562262,5562260,5562264,5562265,5562263,5562268,5562273,5562269,5562275,5562272,5562274,5562282,5562267,5562286,5562288,5562287,5562292,5562301,5562296,5562299,5562300,5562305,5562304,5562309,5562312,5562308,5562313,5562315,5562314,5562320,5562322,5562316,5562334,5562321,5562329,5562337,5562310,5562325,5562335,5562336,5562339,5562317,5562346,5562343,5562351,5562349,5562270,5562359,5562362,5562360,5562370,5562373,5562371,5562379,5562376,5562381,5562382,5562383,5562387,5562392,5562394,5562393,5562397,5562385,5562400,5562398,5562399,5562395,5562402,5562406,5562404,5562405,5562407,5562409,5562414,5562419,5562420,5562418,5562421,5562413,5562423,5562424,5562426,5562428,5562430,5562432,5562434,5562433,5562435,5562436,5562438,5562439,5562440,5562442,5562445,5562443,5562447,5562446,5562451,5562452,5562450,5562453,5562457,5562459,5562456,5562462,5562463,5562465,5562466,5562468,5562470,5562467,5562477,5562479,5562478,5562480,5562481,5562482,5562483,5562486,5562489,5562487,5562488,5562490,5562492,5562491,5562496,5562497,5562495,5562499,5562503,5562502,5562505,5562506,5562508,5562510,5562509,5562512,5562516,5562513,5562521,5562524,5562523,5562527,5562531,5562533,5562536,5562537,5562538,5562540,5562544,5562549,5562550,5562551,5562553,5562556,5562558,5562560,5562566,5562564,5562568,5562572,5562571,5562569,5562573,5562575,5562579,5562578,5562583,5562585,5562587,5562592,5562588,5562590,5562559,5562593,5562597,5562586,5562594,5562598,5562601,5562602,5562604,5562606,5562608,5562611,5562612,5562613,5562614,5562616,5562615,5562617,5562618,5562621,5562622,5562624,5562623,5562625,5562628,5562629,5562633,5562634,5562630,5562641,5562632,5562643,5562644,5562620,5562645,5562646,5562642,5562648,5562647,5562650,5562651,5562649,5562656,5562658,5562653,5562659,5562664,5562663,5562666,5562662,5562661,5562665,5562670,5562671,5562672,5562675,5562677,5562667,5562681,5562682,5562683,5562684,5562685,5562690,5562691,5562694,5562697,5562698,5562695,5562699,5562700,5562696,5562701,5562687,5562702,5562703,5562705,5562706,5562713,5562717,5562719,5562722,5562723,5562727,5562726,5562725,5562728,5562731,5562733,5562738,5562734,5562739,5562740,5562742,5562749,5562746,5562744,5562716,5562751,5562753,5562756,5562755,5562745,5562758,5562759,5562764,5562763,5562765,5562768,5562761,5562772,5562776,5562780,5562785,5562787,5562788,5562791,5562793,5562792,5562795,5562797,5562799,5562801,5562802,5562810,5562806,5562811,5562812,5562809,5562814,5562816,5562794,5562818,5562820,5562817,5562826,5562827,5562831,5562828,5562832,5562834,5562836,5562837,5562841,5562844,5562846,5562825,5562850,5562855,5562857,5562860,5562865,5562864,5562859,5562868,5562869,5562871,5562873,5562872,5562870,5562878,5562879,5562880,5562883,5562884,5562886,5562890,5562889,5562887,5562893,5562901,5562900,5562902,5562903,5562906,5562907,5562908,5562909,5562911,5562915,5562916,5562919,5562922,5562932,5562934,5562927,5562935,5562936,5562933,5562940,5562931,5562941,5562943,5562945,5562946,5562948,5562947,5562951,5562952,5562950,5562953,5562954,5562959,5562958,5562962,5562963,5562968,5562967,5562969,5562971,5562976,5562974,5562980,5562978,5562981,5562989,5562991,5562992,5562995,5562996,5562997,5562998,5563000,5563002,5563007,5563005,5563009,5563013,5563014,5563018,5563017,5563020,5563027,5564394,5564394);
				$pay_tr = array(4905759,4914875,4919518,4920096);
				$this->db->where_in('b.id', $pay_tr);
				//$this->db->like('b.date', '2021-02-');
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				//$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT');
				$can_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'pay_status'=>1),$select);				//,'isactive'=>'1','isdeleted'=>0
				//'DATE(a.created_on)'=>$yesterday,
			//	echo $this->db->last_query();
				//exit;
				if(count($can_exam_data))
				{
					$i = 1;
					$exam_invoice_count = 0;
					$exam_invoice_Image_cnt = 0;
					$dirname = "exam_invoice_image_".$current_date;
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
					foreach($can_exam_data as $exam)
					{
						$pay_txn_id = $exam['pay_txn_id'];
						$receipt_no = $exam['receipt_no'];
						$examination_date = $exam['examination_date'];
						// get invoice details for this member payment transaction by id and receipt_no
						$this->db->where('transaction_no !=','');
						$this->db->where('app_type','O');
						$this->db->where('receipt_no',$receipt_no);
						$exam_invoice_arr = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
						//echo "<br>".$this->db->last_query();
						//exit;
						if(count($exam_invoice_arr))
						{
							foreach($exam_invoice_arr as $exIn_data)
							{
								$data = '';
								$exam_invoice_Image = '';
								$date_of_invoice = $exIn_data['date_of_invoice'];
								$Update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
								if(is_file("./uploads/examinvoice/supplier/".$exIn_data['invoice_image']))
								{
									$exam_invoice_Image = EXAM_INVOICE_FILE_PATH.$exIn_data['invoice_image'];
								}
								else
								{
									fwrite($fp1, "**ERROR** - Exam Invoice does not exist  - ".$exIn_data['invoice_image']." (".$exIn_data['member_no'].")\n");	
								}
								//$exam_code = '';
								/*if($exIn_data['exam_code'] != '' && $exIn_data['exam_code'] != 0)
									{
									$ex_code = $this->master_model->getRecords('exam_activation_master',array('exam_code'=>$exIn_data['exam_code']));
									if(count($ex_code))
									{
									if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0)
									{	$exam_code = $ex_code[0]['original_val'];	}
									else
									{	$exam_code = $exIn_data['exam_code'];	}
									}
									}
								else{	$exam_code = $exIn_data['exam_code'];	}*/
								$exam_code = '';
								if($exIn_data['exam_code'] == 340 || $exIn_data['exam_code'] == 3400 || $exIn_data['exam_code'] == 34000)
								{
									$exam_code = 34;
									}elseif($exIn_data['exam_code'] == 580 || $exIn_data['exam_code'] == 5800 || $exIn_data['exam_code'] == 58000){
									$exam_code = 58;
									}elseif($exIn_data['exam_code'] == 1600 || $exIn_data['exam_code'] == 16000){
									$exam_code = 160;
									}elseif($exIn_data['exam_code'] == 200){
									$exam_code = 20;
									}elseif($exIn_data['exam_code'] == 1770 || $exIn_data['exam_code'] == 17700){
									$exam_code =177;
									}elseif ($exIn_data['exam_code'] == 590){
									$exam_code = 59;
									}elseif ($exIn_data['exam_code'] == 810){
									$exam_code = 81;
									}elseif ($exIn_data['exam_code'] == 1750){
									$exam_code = 175;
									}else{
									$exam_code = $exIn_data['exam_code'];
								}
								// rewrite exam period
								$exam_period = '';
								if($examination_date != '' && $examination_date != "0000-00-00")
								{
									$special_exam_period = $this->Master_model->getRecords('special_exam_dates',array('examination_date'=>$examination_date));
									if(count($special_exam_period))
									{
										$exam_period = $special_exam_period[0]['period'];	
									}
									}else{
									$exam_period = $exIn_data['exam_period'];
								}
								// eof code added by Bhagwan Sahane, on 18-08-2017
								$gstin_no = 0;
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$exIn_data['center_code'].'|'.$exIn_data['center_name'].'|'.$exIn_data['state_of_center'].'|'.$exIn_data['invoice_no'].'|'.$exam_invoice_Image.'|'.$exIn_data['member_no'].'|'.$Update_date_of_invoice.'|'.$exIn_data['transaction_no'].'|'.$exIn_data['fee_amt'].'|'.$exIn_data['cgst_rate'].'|'.$exIn_data['cgst_amt'].'|'.$exIn_data['sgst_rate'].'|'.$exIn_data['sgst_amt'].'|'.$exIn_data['cs_total'].'|'.$exIn_data['igst_rate'].'|'.$exIn_data['igst_amt'].'|'.$exIn_data['igst_total'].'|'.$exIn_data['qty'].'|'.$exIn_data['cess'].'|'.$exIn_data['state_code'].'|'.$exIn_data['state_name'].'|'.$exIn_data['service_code'].'|'.$gstin_no.'|'.$exIn_data['tax_type'].'|'.$exIn_data['app_type']."\n";
								if($dir_flg)
								{
									// For photo images
									if($exam_invoice_Image)
									{
										copy("./uploads/examinvoice/supplier/".$exIn_data['invoice_image'],$directory."/".$exIn_data['invoice_image']);
										$photo_to_add = $directory."/".$exIn_data['invoice_image'];
										$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
										if(!$photo_zip_flg)
										{
											fwrite($fp1, "**ERROR** - Exam Invoice Image not added to zip  - ".$exIn_data['invoice_image']." (".$exIn_data['member_no'].")\n");	
										}
										else
										$exam_invoice_Image_cnt++;
									}
									if($photo_zip_flg)
									{
										$success['zip'] = "Exam Invoice Images Zip Generated Successfully";
									}
									else
									{
										$error['zip'] = "Error While Generating Exam Invoice Images Zip";
									}
								}
								$i++;
								$exam_invoice_count++;
								$file_w_flg = fwrite($fp, $data);
							}
							if($file_w_flg)
							{
								$success['file'] = "Exam Invoice Details File Generated Successfully. ";
							}
							else
							{
								$error['file'] = "Error While Generating Exam Invoice Details File.";
							}
						}
					}
					fwrite($fp1, "\n"."Total Exam Invoice Details Added = ".$exam_invoice_count."\n");
					fwrite($fp1, "\n"."Total Exam Invoice Images Added = ".$exam_invoice_Image_cnt."\n");
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
				$this->log_model->cronlog("IIBF Exam Invoice Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."************************* IIBF Exam Invoice Details Cron Execution End ".$end_time." *************************"."\n");
				fclose($fp1);
			}
		}
		/* DRA Exam Invoices */
		public function dra_exam_invoice_old_20210129()
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
			$cron_file_dir = "./uploads/cronFilesCustom/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF DRA Exam Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "dra_exam_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* IIBF DRA Exam Invoice Details Cron Execution Started - ".$start_time." ************************* \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$yesterday = '2020-10-03'; // temp
				//$this->db->where("(DATE(date) = '".$yesterday."' OR DATE(updated_date) = '".$yesterday."') AND status = 1");
				$UTR_no = array('404443333','404443333');
				$this->db->where_in('UTR_no', $UTR_no);
				$this->db->where('exam_period', '777');
				//$this->db->where("status = 1");
				$this->db->where("DATE(updated_date) = '".$yesterday."' AND status = 1");
				$dra_payment = $this->Master_model->getRecords('dra_payment_transaction');
				echo $this->db->last_query();
				if(count($dra_payment))
				{
					$i = 1;
					$dra_exam_invoice_count = 0;
					$dra_exam_invoice_Image_cnt = 0;
					$dirname = "dra_exam_invoice_image_".$current_date;
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
					foreach($dra_payment as $payment)
					{
						$pay_txn_id = $payment['id'];
						$receipt_no = $payment['receipt_no'];
						$payment_type = $payment['gateway'];
						// get invoice details for this member payment transaction by id and receipt_no
						$this->db->where('transaction_no !=','');
						$this->db->where('app_type','I');
						$dra_exam_invoice_arr = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id,'receipt_no'=>$receipt_no));
						echo "<br>SQL => ".$this->db->last_query(); //die();
						if(count($dra_exam_invoice_arr))
						{
							foreach($dra_exam_invoice_arr as $dra_exIn_data)
							{
								$data = '';
								$dra_exam_invoice_Image = '';
								$date_of_invoice = $dra_exIn_data['date_of_invoice'];
								$Update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
								if(is_file("./uploads/draexaminvoice/supplier/".$dra_exIn_data['invoice_image']))
								{
									$dra_exam_invoice_Image = DRA_EXAM_INVOICE_FILE_PATH.$dra_exIn_data['invoice_image'];
								}
								else
								{
									fwrite($fp1, "**ERROR** - Exam Invoice does not exist  - ".$dra_exIn_data['invoice_image']." (".$dra_exIn_data['member_no'].")\n");	
								}
								$data .= ''.$dra_exIn_data['exam_code'].'|'.$dra_exIn_data['exam_period'].'|'.$dra_exIn_data['invoice_no'].'|'.$dra_exam_invoice_Image.'|'.$Update_date_of_invoice.'|'.$dra_exIn_data['transaction_no'].'|'.$dra_exIn_data['fee_amt'].'|'.$dra_exIn_data['cgst_rate'].'|'.$dra_exIn_data['cgst_amt'].'|'.$dra_exIn_data['sgst_rate'].'|'.$dra_exIn_data['sgst_amt'].'|'.$dra_exIn_data['cs_total'].'|'.$dra_exIn_data['igst_rate'].'|'.$dra_exIn_data['igst_amt'].'|'.$dra_exIn_data['igst_total'].'|'.$dra_exIn_data['qty'].'|'.$dra_exIn_data['cess'].'|'.$dra_exIn_data['institute_code'].'|'.$dra_exIn_data['institute_name'].'|'.$dra_exIn_data['state_code'].'|'.$dra_exIn_data['state_name'].'|'.$dra_exIn_data['service_code'].'|'.$dra_exIn_data['gstin_no'].'|'.$dra_exIn_data['tax_type'].'|'.$dra_exIn_data['app_type'].'|'.$payment_type."\n";
								if($dir_flg)
								{
									// For photo images
									if($dra_exam_invoice_Image)
									{
										copy("./uploads/draexaminvoice/supplier/".$dra_exIn_data['invoice_image'],$directory."/".$dra_exIn_data['invoice_image']);
										$photo_to_add = $directory."/".$dra_exIn_data['invoice_image'];
										$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
										if(!$photo_zip_flg)
										{
											fwrite($fp1, "**ERROR** - DRA Exam Invoice Image not added to zip  - ".$dra_exIn_data['invoice_image']." (".$dra_exIn_data['member_no'].")\n");	
										}
										else
										$dra_exam_invoice_Image_cnt++;
									}
									if($photo_zip_flg)
									{
										$success['zip'] = "DRA Exam Invoice Images Zip Generated Successfully";
									}
									else
									{
										$error['zip'] = "Error While Generating DRA Exam Invoice Images Zip";
									}
								}
								$i++;
								$dra_exam_invoice_count++;
								$file_w_flg = fwrite($fp, $data);
							}
							if($file_w_flg)
							{
								$success['file'] = "DRA Exam Invoice Details File Generated Successfully. ";
							}
							else
							{
								$error['file'] = "Error While Generating DRA Exam Invoice Details File.";
							}
						}
					}
					fwrite($fp1, "\n"."Total DRA Exam Invoice Details Added = ".$dra_exam_invoice_count."\n");
					fwrite($fp1, "\n"."Total DRA Exam Invoice Images Added = ".$dra_exam_invoice_Image_cnt."\n");
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
				$this->log_model->cronlog("IIBF DRA Exam Invoice Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."************************* IIBF DRA Exam Invoice Details Cron Execution End ".$end_time." *************************"."\n");
				fclose($fp1);
			}
		}
		/* DRA Exam Invoices */
		public function dra_exam_invoice()
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
			$cron_file_dir = "./uploads/cronFilesCustom/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog(" DRA Exam Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "dra_exam_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n********** DRA Exam Invoice Details Cron Start - ".$start_time." ********** \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$this->db->where("(DATE(date) = '".$yesterday."' OR DATE(updated_date) = '".$yesterday."') AND status = 1");
				//$this->db->where("DATE(updated_date) = '".$yesterday."' AND status = 1");
				//$this->db->where('DATE(updated_date)>=' ,'2021-01-01');
				//$this->db->where('DATE(updated_date)<=' , '2021-01-27');
				$receipt_no = array(17364,17366,17369,17371,17372,17375,17376,17377,17380,17384,17388,17389,17390,17392,17393,17394,17395,17399,17403,17408,17411,17415,17423,17425,17426,17428,17429,17431,17433,17437,17440,17444,17447,17449,17450,17453,17454,17455,17473,17476,17485,17487,17488,17489,17490);
				$this->db->where_in('receipt_no',$receipt_no);
				$dra_payment = $this->Master_model->getRecords('dra_payment_transaction',array('status'=>'1'));
				echo  $this->db->last_query();  
				if(count($dra_payment))
				{
					$i = 1;
					$dra_exam_invoice_count = 0;
					$dra_exam_invoice_Image_cnt = 0;
					$dirname = "dra_exam_invoice_image_".$current_date;
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
					foreach($dra_payment as $payment)
					{
						$pay_txn_id = $payment['id'];
						$receipt_no = $payment['receipt_no'];
						$payment_type = $payment['gateway'];
						// get invoice details for this member payment transaction by id and receipt_no
						$this->db->where('transaction_no !=','');
						$this->db->where('app_type','I');
						$this->db->where('receipt_no',$receipt_no);
						$dra_exam_invoice_arr = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
						if(count($dra_exam_invoice_arr))
						{
							foreach($dra_exam_invoice_arr as $dra_exIn_data)
							{
								$data = '';
								$dra_exam_invoice_Image = '';
								$date_of_invoice = $dra_exIn_data['date_of_invoice'];
								$Update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
								if(is_file("./uploads/draexaminvoice/supplier/".$dra_exIn_data['invoice_image']))
								{
									$dra_exam_invoice_Image = DRA_EXAM_INVOICE_FILE_PATH.$dra_exIn_data['invoice_image'];
								}
								else
								{
									fwrite($fp1, "* ERROR * - Exam Invoice does not exist  - ".$dra_exIn_data['invoice_image']." (".$dra_exIn_data['member_no'].")\n");	
								}
								$center_code = $dra_exIn_data['center_code']; 
								if($center_code == 0)
								{		
									$inst_details = $this->master_model->getRecords('dra_accerdited_master',array('institute_code' => $dra_exIn_data['institute_code']),'address1,address2,address3,address4,address5,address6,pin_code as PINCODE');
								}
								else{
									$inst_details = $this->master_model->getRecords('agency_center',array('institute_code' => $dra_exIn_data['institute_code'],'center_id'=>$dra_exIn_data['center_code']),'address1,address2,address3,address4,pincode as PINCODE');
								}
								$address1 = $address2 = $address3 = $address4 = $address5 = $address6 = '';
								$address1 = $inst_details[0]['address1'];
								$address2 = $inst_details[0]['address2'];
								$address3 = $inst_details[0]['address3'];
								$address4 = $inst_details[0]['address4'];
								if(isset($inst_details[0]['address5']))
								{
									$address5 = $inst_details[0]['address5'];
								}
								if(isset($inst_details[0]['address6']))
								{
									$address6 = $inst_details[0]['address6'];
								}
								$inst_address = $pin_code = '';
								if(count($inst_details) > 0)
								{
									if($address1 != '') { $inst_address .= trim($address1)." "; }
									if($address2 != '') { $inst_address .= trim($address2)." "; }
									if($address3 != '') { $inst_address .= trim($address3)." "; }
									if($address4 != '') { $inst_address .= trim($address4)." "; }
									if($address5 != '') { $inst_address .= trim($address5)." "; }
									if($address6 != '') { $inst_address .= trim($address6); }
									if($inst_details[0]['PINCODE'] != '') { $pin_code = $inst_details[0]['PINCODE']; }
								}
								if(date("Y-m-d", strtotime($date_of_invoice)) >= '2021-01-01' && date("Y-m-d", strtotime($date_of_invoice)) <= '2021-01-27')
								{
									$data .= ''.$dra_exIn_data['exam_code'].'|'.$dra_exIn_data['exam_period'].'|'.$dra_exIn_data['invoice_no'].'|'.$dra_exam_invoice_Image.'|'.$Update_date_of_invoice.'|'.$dra_exIn_data['transaction_no'].'|'.$dra_exIn_data['fee_amt'].'|'.$dra_exIn_data['cgst_rate'].'|'.$dra_exIn_data['cgst_amt'].'|'.$dra_exIn_data['sgst_rate'].'|'.$dra_exIn_data['sgst_amt'].'|'.$dra_exIn_data['cs_total'].'|'.$dra_exIn_data['igst_rate'].'|'.$dra_exIn_data['igst_amt'].'|'.$dra_exIn_data['igst_total'].'|'.$dra_exIn_data['qty'].'|'.$dra_exIn_data['cess'].'|'.$dra_exIn_data['institute_code'].'|'.$dra_exIn_data['institute_name'].'|'.$dra_exIn_data['state_code'].'|'.$dra_exIn_data['state_name'].'|'.$dra_exIn_data['service_code'].'|'.$dra_exIn_data['gstin_no'].'|'.$dra_exIn_data['tax_type'].'|'.$dra_exIn_data['app_type'].'|'.$payment_type.'|'.$pin_code.'|'.$inst_address."\n";
									if($dir_flg)
									{
										// For photo images
										if($dra_exam_invoice_Image)
										{
											copy("./uploads/draexaminvoice/supplier/".$dra_exIn_data['invoice_image'],$directory."/".$dra_exIn_data['invoice_image']);
											$photo_to_add = $directory."/".$dra_exIn_data['invoice_image'];
											$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
											$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
											if(!$photo_zip_flg)
											{
												fwrite($fp1, "* ERROR * - DRA Exam Invoice Image not added to zip  - ".$dra_exIn_data['invoice_image']." (".$dra_exIn_data['member_no'].")\n");	
											}
											else
											$dra_exam_invoice_Image_cnt++;
										}
										if($photo_zip_flg)
										{
											$success['zip'] = "DRA Exam Invoice Images Zip Generated Successfully";
										}
										else
										{
											$error['zip'] = "Error While Generating DRA Exam Invoice Images Zip";
										}
									}
									$i++;
									$dra_exam_invoice_count++;
									$file_w_flg = fwrite($fp, $data);
								}							
							}
							if($file_w_flg)
							{
								$success['file'] = "DRA Exam Invoice Details File Generated Successfully. ";
							}
							else
							{
								$error['file'] = "Error While Generating DRA Exam Invoice Details File.";
							}
						}
					}
					fwrite($fp1, "\n"."Total DRA Exam Invoice Details Added = ".$dra_exam_invoice_count."\n");
					fwrite($fp1, "\n"."Total DRA Exam Invoice Images Added = ".$dra_exam_invoice_Image_cnt."\n");
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
				$this->log_model->cronlog(" DRA Exam Invoice Details Cron End", $desc);
				fwrite($fp1, "\n"."********** DRA Exam Invoice Details Cron End ".$end_time." **********"."\n");
				fclose($fp1);
			}
		}
		/* Duplicate I-Card Invoices */ 
		public function dup_icard_invoice()
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
			$cron_file_dir = "./uploads/cronFilesCustom/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF Duplicate I-Card Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700);
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "dup_icard_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* IIBF Duplicate I-Card Invoice Details Cron Execution Started - ".$start_time." ************************* \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$yesterday ='2020-12-20';
				$select = 'b.id as pay_txn_id,b.receipt_no';
				$this->db->join('member_registration a','a.regnumber=c.regnumber','LEFT');
				//$this->db->where('DATE(c.added_date) >=', '2019-07-16');
				//$this->db->where('DATE(c.added_date) <=', '2019-07-27');
				$ref_id = array(24961);
				$this->db->where_in('c.did', $ref_id);
				$this->db->join('payment_transaction b','b.ref_id=c.did','LEFT');
				$dup_icard_data = $this->Master_model->getRecords('duplicate_icard c',array('pay_type'=>3,'isactive'=>'1','status'=>'1','isdeleted'=>0),$select);
				// ' DATE(added_date)'=>$yesterday,
				echo $this->db->last_query();
				if(count($dup_icard_data))
				{
					$i = 1;
					$dup_icard_invoice_count = 0;
					$dup_icard_invoice_image_cnt = 0;
					$dirname = "dup_icard_invoice_image_".$current_date;
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
					foreach($dup_icard_data as $dup_icard)
					{					
						$pay_txn_id = $dup_icard['pay_txn_id'];
						$receipt_no = $dup_icard['receipt_no'];
						// get invoice details for this member payment transaction by id and receipt_no
						$this->db->where('transaction_no !=','');
						$this->db->where('app_type','D');
						$this->db->where('receipt_no',$receipt_no);
						$dup_icard_invoice_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
						//echo "<br>SQL => ".$this->db->last_query();
						if(count($dup_icard_invoice_data))
						{
							foreach($dup_icard_invoice_data as $dup_icard_invoice)
							{
								$data = '';
								$dup_icard_invoice_image = '';
								$date_of_invoice = $dup_icard_invoice['date_of_invoice'];
								$update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
								if(is_file("./uploads/dupicardinvoice/supplier/".$dup_icard_invoice['invoice_image']))
								{
									$dup_icard_invoice_image = DUP_ICARD_INVOICE_FILE_PATH.$dup_icard_invoice['invoice_image'];
								}
								else
								{
									fwrite($fp1, "**ERROR** - Duplicate I-Card Invoice does not exist  - ".$dup_icard_invoice['invoice_image']." (".$dup_icard_invoice['member_no'].")\n");	
								}
								$data .= ''.$dup_icard_invoice['invoice_no'].'|'.$dup_icard_invoice_image.'|'.$dup_icard_invoice['member_no'].'|'.$update_date_of_invoice.'|'.$dup_icard_invoice['transaction_no'].'|'.$dup_icard_invoice['fee_amt'].'|'.$dup_icard_invoice['cgst_rate'].'|'.$dup_icard_invoice['cgst_amt'].'|'.$dup_icard_invoice['sgst_rate'].'|'.$dup_icard_invoice['sgst_amt'].'|'.$dup_icard_invoice['cs_total'].'|'.$dup_icard_invoice['igst_rate'].'|'.$dup_icard_invoice['igst_amt'].'|'.$dup_icard_invoice['igst_total'].'|'.$dup_icard_invoice['qty'].'|'.$dup_icard_invoice['cess'].'|'.$dup_icard_invoice['state_code'].'|'.$dup_icard_invoice['state_name'].'|'.$dup_icard_invoice['service_code'].'|'.$dup_icard_invoice['gstin_no'].'|'.$dup_icard_invoice['tax_type']."\n";
								if($dir_flg)
								{
									// For photo images
									if($dup_icard_invoice_image)
									{
										/*$image = "./uploads/dupicardinvoice/supplier/".$dup_icard_invoice['invoice_image'];
											$max_width = "1000";
											$max_height = "1000";
											$imgdata = $this->resize_image_max($image,$max_width,$max_height);
											imagejpeg($imgdata, $directory."/".$dup_icard_invoice['invoice_image']);
											$photo_to_add = $directory."/".$dup_icard_invoice['invoice_image'];
											$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);*/
										copy("./uploads/dupicardinvoice/supplier/".$dup_icard_invoice['invoice_image'],$directory."/".$dup_icard_invoice['invoice_image']);
										$photo_to_add = $directory."/".$dup_icard_invoice['invoice_image'];
										$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
										if(!$photo_zip_flg)
										{
											fwrite($fp1, "**ERROR** - Duplicate I-Card Invoice Image not added to zip  - ".$memIn_data['invoice_image']." (".$memIn_data['member_no'].")\n");	
										}
										else
										$dup_icard_invoice_image_cnt++;
									}
									if($photo_zip_flg)
									{
										$success[] = "Duplicate I-Card Invoice Images Zip Generated Successfully";
									}
									else
									{
										$error[] = "Error While Generating Duplicate I-Card Invoice Images Zip";
									}
								}
								$i++;
								$dup_icard_invoice_count++;
								//fwrite($fp1, "\n");
								$file_w_flg = fwrite($fp, $data);
							}
							if($file_w_flg)
							{
								$success[] = "Duplicate I-Card Invoice Details File Generated Successfully. ";
							}
							else
							{
								$error[] = "Error While Generating Duplicate I-Card Invoice Details File.";
							}
						}
					}
					$zip->close();
					fwrite($fp1, "\n"."Total Duplicate I-Card Invoice Details Added = ".$dup_icard_invoice_count."\n");
					fwrite($fp1, "\n"."Total Duplicate I-Card Invoice Images Added = ".$dup_icard_invoice_image_cnt."\n");
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
				$this->log_model->cronlog("IIBF Duplicate I-Card Invoice Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."************************* IIBF Duplicate I-Card Invoice Details Cron Execution End ".$end_time." *************************"."\n");
				fclose($fp1);
			}
		}
		/* Duplicate Certificate Invoices */ 
		public function dup_cert_invoice()
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
			$cron_file_dir = "./uploads/cronFilesCustom/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF Duplicate Certificate Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700);
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "dup_cert_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* IIBF Duplicate Certificate Invoice Details Cron Execution Started - ".$start_time." ************************* \n");
				//$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$yesterday = '2020-10-24';
				$select = 'b.id as pay_txn_id,b.receipt_no';
				//$this->db->where('DATE(c.created_on) >=', '2019-07-02');
				//$this->db->where('DATE(c.created_on) <=', '2019-07-27');
				$ref_id = array(48285,48307,48660,48713,48722,48737,48772,48776,48778,48787,48812);
				$this->db->where_in('c.id', $ref_id);
				$this->db->join('payment_transaction b','b.ref_id = c.id','LEFT');
				$dup_cert_data = $this->Master_model->getRecords('duplicate_certificate c',array('pay_type' => 4,'pay_status' => 1,'status' => '1'),$select);
				// ' DATE(created_on)' => $yesterday,
				//echo $this->db->last_query();
				if(count($dup_cert_data))
				{
					$i = 1;
					$dup_cert_invoice_count = 0;
					$dup_cert_invoice_image_cnt = 0;
					$dirname = "dup_cert_invoice_image_".$current_date;
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
					foreach($dup_cert_data as $dup_cert)
					{					
						$pay_txn_id = $dup_cert['pay_txn_id'];
						$receipt_no = $dup_cert['receipt_no'];
						// get invoice details for this duplicate certificate payment transaction by id and receipt_no
						$this->db->where('transaction_no !=','');
						$this->db->where('app_type','C');
						$this->db->where('receipt_no',$receipt_no);
						$dup_cert_invoice_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
						//echo "<br>SQL => ".$this->db->last_query();
						if(count($dup_cert_invoice_data))
						{
							foreach($dup_cert_invoice_data as $dup_cert_invoice)
							{
								$data = '';
								$dup_cert_invoice_image = '';
								$date_of_invoice = $dup_cert_invoice['date_of_invoice'];
								$update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
								if(is_file("./uploads/dupcertinvoice/supplier/".$dup_cert_invoice['invoice_image']))
								{
									$dup_cert_invoice_image = DUP_CERT_INVOICE_FILE_PATH.$dup_cert_invoice['invoice_image'];
								}
								else
								{
									fwrite($fp1, "**ERROR** - Duplicate Certificate Invoice does not exist  - ".$dup_cert_invoice['invoice_image']." (".$dup_cert_invoice['member_no'].")\n");	
								}
								//EXAM_CODE|EXAM_PERIOD(NULL)|CENTER_CODE(NULL)|CENTER_NAME(NULL)|STATE_OF_CENTER|INVOICE_NO|INVOICE_IMAGE|MEMBER_NO|DATE_OF_INVOICE|TRANSACTION_NO|FEE_AMT|CGST_RATE|CGST_AMT|SGST_RATE|SGST_AMT|CS_TOT|IGST_RATE|IGST_TOT|QUANTITY(1)|CESS(0.00)|STATE_CODE|STATE_NAME|SERVICE_CODE(XXXXXX)|GSTIN_NO(NULL)|TAX_TYPE(INTRA/INTER)|
								$data .= ''.$dup_cert_invoice['exam_code'].'|'.$dup_cert_invoice['exam_period'].'|'.$dup_cert_invoice['center_code'].'|'.$dup_cert_invoice['center_name'].'|'.$dup_cert_invoice['state_of_center'].'|'.$dup_cert_invoice['invoice_no'].'|'.$dup_cert_invoice_image.'|'.$dup_cert_invoice['member_no'].'|'.$update_date_of_invoice.'|'.$dup_cert_invoice['transaction_no'].'|'.$dup_cert_invoice['fee_amt'].'|'.$dup_cert_invoice['cgst_rate'].'|'.$dup_cert_invoice['cgst_amt'].'|'.$dup_cert_invoice['sgst_rate'].'|'.$dup_cert_invoice['sgst_amt'].'|'.$dup_cert_invoice['cs_total'].'|'.$dup_cert_invoice['igst_rate'].'|'.$dup_cert_invoice['igst_amt'].'|'.$dup_cert_invoice['igst_total'].'|'.$dup_cert_invoice['qty'].'|'.$dup_cert_invoice['cess'].'|'.$dup_cert_invoice['state_code'].'|'.$dup_cert_invoice['state_name'].'|'.$dup_cert_invoice['service_code'].'|'.$dup_cert_invoice['gstin_no'].'|'.$dup_cert_invoice['tax_type']."\n";
								if($dir_flg)
								{
									// For photo images
									if($dup_cert_invoice_image)
									{
										/*$image = "./uploads/dupcertinvoice/supplier/".$dup_cert_invoice['invoice_image'];
											$max_width = "1000";
											$max_height = "1000";
											$imgdata = $this->resize_image_max($image,$max_width,$max_height);
											imagejpeg($imgdata, $directory."/".$dup_cert_invoice['invoice_image']);
											$photo_to_add = $directory."/".$dup_cert_invoice['invoice_image'];
											$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);*/
										copy("./uploads/dupcertinvoice/supplier/".$dup_cert_invoice['invoice_image'],$directory."/".$dup_cert_invoice['invoice_image']);
										$photo_to_add = $directory."/".$dup_cert_invoice['invoice_image'];
										$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
										if(!$photo_zip_flg)
										{
											fwrite($fp1, "**ERROR** - Duplicate Certificate Invoice Image not added to zip  - ".$dup_cert_invoice['invoice_image']." (".$dup_cert_invoice['member_no'].")\n");	
										}
										else
										$dup_cert_invoice_image_cnt++;
									}
									if($photo_zip_flg)
									{
										$success[] = "Duplicate Certificate Invoice Images Zip Generated Successfully";
									}
									else
									{
										$error[] = "Error While Generating Duplicate Certificate Invoice Images Zip";
									}
								}
								$i++;
								$dup_cert_invoice_count++;
								//fwrite($fp1, "\n");
								$file_w_flg = fwrite($fp, $data);
							}
							if($file_w_flg)
							{
								$success[] = "Duplicate Certificate Invoice Details File Generated Successfully. ";
							}
							else
							{
								$error[] = "Error While Generating Duplicate Certificate Invoice Details File.";
							}
						}
					}
					$zip->close();
					fwrite($fp1, "\n"."Total Duplicate Certificate Invoice Details Added = ".$dup_cert_invoice_count."\n");
					fwrite($fp1, "\n"."Total Duplicate Certificate Invoice Images Added = ".$dup_cert_invoice_image_cnt."\n");
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
				$this->log_model->cronlog("IIBF Duplicate Certificate Invoice Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."************************* IIBF Duplicate Certificate Invoice Details Cron Execution End ".$end_time." *************************"."\n");
				fclose($fp1);
			}
		}
		/* Member Renewal Invoices */ 
		public function renewal_invoice()
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
			$cron_file_dir = "./uploads/cronFilesCustom/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF Renewal Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "renewal_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* IIBF Renewal Invoice Details Cron Execution Started - ".$start_time." ************************* \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$yesterday = '2017-09-19';
				/*$this->db->where('DATE(a.createdon) >=', '2019-07-01');
				$this->db->where('DATE(a.createdon) <=', '2019-07-02');*/
				$member_no = array(5936373);
				$this->db->where_in('a.regnumber', $member_no);
				$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0, 'is_renewal' => 1));
				//' DATE(createdon)'=>$yesterday,
				//echo $this->db->last_query();
				if(count($new_mem_reg))
				{
					$i = 1;
					$member_invoice_count = 0;
					$member_invoice_Image_cnt = 0;
					$dirname = "renewal_invoice_image_".$current_date;
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
					foreach($new_mem_reg as $reg_data)
					{
						if($reg_data['registrationtype']!='NM')
						{
							if($reg_data['registrationtype'] == 'DB')	// DB Member
							{
								$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_DB','pay_type'=>2,'member_regnumber'=>$reg_data['regnumber'],'status'=>1),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date,id,receipt_no');
							}
							else	// Ordinary Member
							{
								$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'iibfren','status'=>1,'pay_type'=>5,'ref_id'=>$reg_data['regid']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date,id,receipt_no');
							}
						}
						else	// Non Member
						{
							$trans_details = $this->Master_model->getRecords('payment_transaction a',array('pg_flag'=>'IIBF_EXAM_REG','status'=>1,'pay_type'=>2,'member_regnumber'=>$reg_data['regnumber']),'transaction_no,amount,DATE_FORMAT(date,"%Y-%m-%d") date,id,receipt_no');
						}
						if(count($trans_details))
						{
							$pay_txn_id = $trans_details[0]['id'];
							$receipt_no = $trans_details[0]['receipt_no'];
							// get invoice details for this member renewal payment transaction by id and receipt_no
							$this->db->where('transaction_no !=','');
							$this->db->where('app_type','N');
							$this->db->where('receipt_no',$receipt_no);
							$member_invoice_arr = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
							//echo "<br>SQL => ".$this->db->last_query();
							if(count($member_invoice_arr))
							{
								foreach($member_invoice_arr as $memIn_data)
								{
									$data = '';
									$member_invoice_Image = '';
									$date_of_invoice = $memIn_data['date_of_invoice'];
									$Update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
									if(is_file("./uploads/renewal_invoice/supplier/".$memIn_data['invoice_image']))
									{
										$member_invoice_Image = MEMBER_RENEWAL_INVOICE_FILE_PATH.$memIn_data['invoice_image'];
									}
									else
									{
										fwrite($fp1, "**ERROR** - Renewal Invoice does not exist  - ".$memIn_data['invoice_image']." (".$memIn_data['member_no'].")\n");	
									}
									$data .= ''.$memIn_data['invoice_no'].'|'.$member_invoice_Image.'|'.$memIn_data['member_no'].'|'.$Update_date_of_invoice.'|'.$memIn_data['transaction_no'].'|'.$memIn_data['fee_amt'].'|'.$memIn_data['cgst_rate'].'|'.$memIn_data['cgst_amt'].'|'.$memIn_data['sgst_rate'].'|'.$memIn_data['sgst_amt'].'|'.$memIn_data['cs_total'].'|'.$memIn_data['igst_rate'].'|'.$memIn_data['igst_amt'].'|'.$memIn_data['igst_total'].'|'.$memIn_data['qty'].'|'.$memIn_data['cess'].'|'.$memIn_data['state_code'].'|'.$memIn_data['state_name'].'|'.$memIn_data['service_code'].'|'.$memIn_data['gstin_no'].'|'.$memIn_data['tax_type']."\n";
									if($dir_flg)
									{
										// For photo images
										if($member_invoice_Image)
										{
											/*$image = "./uploads/renewal_invoice/supplier/".$memIn_data['invoice_image'];
												$max_width = "1000";
												$max_height = "1000";
												$imgdata = $this->resize_image_max($image,$max_width,$max_height);
												imagejpeg($imgdata, $directory."/".$memIn_data['invoice_image']);
												$photo_to_add = $directory."/".$memIn_data['invoice_image'];
												$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
											$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);*/
											copy("./uploads/renewal_invoice/supplier/".$memIn_data['invoice_image'],$directory."/".$memIn_data['invoice_image']);
											$photo_to_add = $directory."/".$memIn_data['invoice_image'];
											$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
											$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
											if(!$photo_zip_flg)
											{
												fwrite($fp1, "**ERROR** - Renewal Invoice Image not added to zip  - ".$memIn_data['invoice_image']." (".$memIn_data['member_no'].")\n");	
											}
											else
											$member_invoice_Image_cnt++;
										}
										if($photo_zip_flg)
										{
											$success['zip'] = "Renewal Invoice Images Zip Generated Successfully";
										}
										else
										{
											$error['zip'] = "Error While Generating Renewal Invoice Images Zip";
										}
									}
									$i++;
									$member_invoice_count++;
									//fwrite($fp1, "\n");
									$file_w_flg = fwrite($fp, $data);
								}
								if($file_w_flg)
								{
									$success['file'] = "Renewal Invoice Details File Generated Successfully. ";
								}
								else
								{
									$error['file'] = "Error While Generating Renewal Invoice Details File.";
								}
							}
						}
					}
					$zip->close();
					fwrite($fp1, "\n"."Total Renewal Invoice Details Added = ".$member_invoice_count."\n");
					fwrite($fp1, "\n"."Total Renewal Invoice Images Added = ".$member_invoice_Image_cnt."\n");
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
				$this->log_model->cronlog("IIBF Renewal Invoice Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."************************* IIBF Renewal Invoice Details Cron Execution End ".$end_time." *************************"."\n");
				fclose($fp1);
			}
		}
		/* BankQuest Invoices */ 
		public function bankquest_invoice()
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
			$cron_file_dir = "./uploads/cronFilesCustom/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("BankQuest Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700);
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "bankquest_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* BankQuest Invoice Details Cron Execution Started - ".$start_time." ************************* \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$yesterday = '2020-11-01';
				//$this->db->where('DATE(c.created_on) >=', '2019-07-18');
				//$this->db->where('DATE(c.created_on) <=', '2019-07-18');
				//$ref_id = array('2158');
				//$this->db->where_in('c.bv_id', $ref_id);
				$id = array(3206,3207);
				$select = 'c.subscription_no,b.id as pay_txn_id,b.receipt_no';
				$this->db->where_in('b.ref_id', $id);
				$this->db->join('payment_transaction b','b.ref_id=c.bv_id','LEFT');
				$bankquest_data = $this->Master_model->getRecords('bank_vision c',array('pay_type' => 6,'pay_status' => 1,'status' => '1'),$select);
				//' DATE(created_on)' => $yesterday,
				echo $this->db->last_query();
				if(count($bankquest_data))
				{
					$i = 1;
					$bankquest_invoice_count = 0;
					$bankquest_invoice_image_cnt = 0;
					$dirname = "bankquest_invoice_image_".$current_date;
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
					foreach($bankquest_data as $bq_reg)
					{					
						$pay_txn_id = $bq_reg['pay_txn_id'];
						$receipt_no = $bq_reg['receipt_no'];
						$subscription_no = $bq_reg['subscription_no'];
						// get invoice details for this bankquest reg payment transaction by id and receipt_no
						$this->db->where('transaction_no !=','');
						$this->db->where('app_type','B');
						$this->db->where('receipt_no',$receipt_no);
						$bankquest_invoice_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
						//echo "<br>SQL => ".$this->db->last_query();
						if(count($bankquest_invoice_data))
						{
							foreach($bankquest_invoice_data as $bankquest_invoice)
							{
								$data = '';
								$bankquest_invoice_image = '';
								$date_of_invoice = $bankquest_invoice['date_of_invoice'];
								$update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
								if(is_file("./uploads/bnqinvoice/supplier/".$bankquest_invoice['invoice_image']))
								{
									$bankquest_invoice_image = BQ_INVOICE_FILE_PATH.$bankquest_invoice['invoice_image'];
								}
								else
								{
									fwrite($fp1, "**ERROR** - BankQuest Invoice does not exist  - ".$bankquest_invoice['invoice_image']." (".$bankquest_invoice['member_no'].")\n");	
								}
								//INVOICE_NO|INVOICE_IMAGE|MEMBER_NO(NULL)|DATE_OF_INVOICE|TRANSACTION_NO|FEE_AMT|CGST_RATE|CGST_AMT|SGST_RATE|SGST_AMT|CS_TOT|IGST_RATE|IGST_AMT|IGST_TOT|QUANTITY(1)|CESS(0.00)|STATE_CODE|STATE_NAME|SERVICE_CODE(XXXXXX)|GSTIN_NO(NULL)|TAX_TYPE(INTRA/INTER)|APP_TYPE(B)|
								//INVOICE_NO|INVOICE_IMAGE|SUBSCRIPTION_NO|DATE_OF_INVOICE|TRANSACTION_NO|FEE_AMT|CGST_RATE|CGST_AMT|SGST_RATE|SGST_AMT|CS_TOT|IGST_RATE|IGST_AMT|IGST_TOT|QUANTITY(1)|CESS(0.00)|STATE_CODE|STATE_NAME|SERVICE_CODE(XXXXXX)|GSTIN_NO(NULL)|TAX_TYPE(INTRA/INTER)|APP_TYPE(B)|
								/*$data .= ''.$bankquest_invoice['invoice_no'].'|'.$bankquest_invoice_image.'|'.$bankquest_invoice['member_no'].'|'.$update_date_of_invoice.'|'.$bankquest_invoice['transaction_no'].'|'.$bankquest_invoice['fee_amt'].'|'.$bankquest_invoice['cgst_rate'].'|'.$bankquest_invoice['cgst_amt'].'|'.$bankquest_invoice['sgst_rate'].'|'.$bankquest_invoice['sgst_amt'].'|'.$bankquest_invoice['cs_total'].'|'.$bankquest_invoice['igst_rate'].'|'.$bankquest_invoice['igst_amt'].'|'.$bankquest_invoice['igst_total'].'|'.$bankquest_invoice['qty'].'|'.$bankquest_invoice['cess'].'|'.$bankquest_invoice['state_code'].'|'.$bankquest_invoice['state_name'].'|'.$bankquest_invoice['service_code'].'|'.$bankquest_invoice['gstin_no'].'|'.$bankquest_invoice['tax_type'].'|'.$bankquest_invoice['app_type']."|\n";*/
								$data .= ''.$bankquest_invoice['invoice_no'].'|'.$bankquest_invoice_image.'|'.$subscription_no.'|'.$update_date_of_invoice.'|'.$bankquest_invoice['transaction_no'].'|'.$bankquest_invoice['fee_amt'].'|'.$bankquest_invoice['cgst_rate'].'|'.$bankquest_invoice['cgst_amt'].'|'.$bankquest_invoice['sgst_rate'].'|'.$bankquest_invoice['sgst_amt'].'|'.$bankquest_invoice['cs_total'].'|'.$bankquest_invoice['igst_rate'].'|'.$bankquest_invoice['igst_amt'].'|'.$bankquest_invoice['igst_total'].'|'.$bankquest_invoice['qty'].'|'.$bankquest_invoice['cess'].'|'.$bankquest_invoice['state_code'].'|'.$bankquest_invoice['state_name'].'|'.$bankquest_invoice['service_code'].'|'.$bankquest_invoice['gstin_no'].'|'.$bankquest_invoice['tax_type'].'|'.$bankquest_invoice['app_type']."\n";
								if($dir_flg)
								{
									// For photo images
									if($bankquest_invoice_image)
									{
										/*$image = "./uploads/bnqinvoice/supplier/".$bankquest_invoice['invoice_image'];
											$max_width = "1000";
											$max_height = "1000";
											$imgdata = $this->resize_image_max($image,$max_width,$max_height);
											imagejpeg($imgdata, $directory."/".$bankquest_invoice['invoice_image']);
											$photo_to_add = $directory."/".$bankquest_invoice['invoice_image'];
											$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);*/
										copy("./uploads/bnqinvoice/supplier/".$bankquest_invoice['invoice_image'],$directory."/".$bankquest_invoice['invoice_image']);
										$photo_to_add = $directory."/".$bankquest_invoice['invoice_image'];
										$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
										if(!$photo_zip_flg)
										{
											fwrite($fp1, "**ERROR** - BankQuest Invoice Image not added to zip  - ".$bankquest_invoice['invoice_image']." (".$bankquest_invoice['member_no'].")\n");	
										}
										else
										$bankquest_invoice_image_cnt++;
									}
									if($photo_zip_flg)
									{
										$success[] = "BankQuest Invoice Images Zip Generated Successfully";
									}
									else
									{
										$error[] = "Error While Generating BankQuest Invoice Images Zip";
									}
								}
								$i++;
								$bankquest_invoice_count++;
								//fwrite($fp1, "\n");
								$file_w_flg = fwrite($fp, $data);
							}
							if($file_w_flg)
							{
								$success[] = "BankQuest Invoice Details File Generated Successfully. ";
							}
							else
							{
								$error[] = "Error While Generating BankQuest Invoice Details File.";
							}
						}
					}
					$zip->close();
					fwrite($fp1, "\n"."Total BankQuest Invoice Details Added = ".$bankquest_invoice_count."\n");
					fwrite($fp1, "\n"."Total BankQuest Invoice Images Added = ".$bankquest_invoice_image_cnt."\n");
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
				$this->log_model->cronlog("BankQuest Invoice Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."************************* BankQuest Invoice Details Cron Execution End ".$end_time." *************************"."\n");
				fclose($fp1);
			}
		}
		/* Vision Invoices */ 
		public function vision_invoice()
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
			$cron_file_dir = "./uploads/cronFilesCustom/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("Vision Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700);
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "vision_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* Vision Invoice Details Cron Execution Started - ".$start_time." ************************* \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$yesterday ='2020-10-29';
				//$this->db->where('DATE(c.created_on) >=', '2019-07-16');
				//$this->db->where('DATE(c.created_on) <=', '2019-07-27');
				//$ref_id = array('2247','2248','2249','2251','2253','2254','2256','2258','2261','2265','2267','2269','2274','2275','2278','2280','2282','2285','2287','2290','2293','2294','2295','2297','2299','2309','2386','2387','2389','2398','2399','2400','2403');
				//$this->db->where_in('c.vision_id', $ref_id);
				$id = array(4453);
				$select = 'c.subscription_no,b.id as pay_txn_id,b.receipt_no';
				$this->db->where_in('b.ref_id', $id);
				$this->db->join('payment_transaction b','b.ref_id = c.vision_id','LEFT');
				$vision_data = $this->Master_model->getRecords('iibf_vision c',array('pay_type'=>7,'pay_status'=>1,'status'=>'1'),$select);
				//' DATE(created_on)' => $yesterday,
				//echo $this->db->last_query();
				if(count($vision_data))
				{
					$i = 1;
					$vision_invoice_count = 0;
					$vision_invoice_image_cnt = 0;
					$dirname = "vision_invoice_image_".$current_date;
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
					foreach($vision_data as $vi_reg)
					{					
						$pay_txn_id = $vi_reg['pay_txn_id'];
						$receipt_no = $vi_reg['receipt_no'];
						$subscription_no = $vi_reg['subscription_no'];
						// get invoice details for this vision reg payment transaction by id and receipt_no
						$this->db->where('transaction_no !=','');
						$this->db->where('app_type','V');
						$this->db->where('receipt_no',$receipt_no);
						$vision_invoice_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
						//echo "<br>SQL => ".$this->db->last_query();
						if(count($vision_invoice_data))
						{
							foreach($vision_invoice_data as $vision_invoice)
							{
								$data = '';
								$vision_invoice_image = '';
								$date_of_invoice = $vision_invoice['date_of_invoice'];
								$update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
								if(is_file("./uploads/vision_invoice/supplier/".$vision_invoice['invoice_image']))
								{
									$vision_invoice_image = VI_INVOICE_FILE_PATH.$vision_invoice['invoice_image'];
								}
								else
								{
									fwrite($fp1, "**ERROR** - Vision Invoice does not exist  - ".$vision_invoice['invoice_image']." (".$vision_invoice['member_no'].")\n");	
								}
								//INVOICE_NO|INVOICE_IMAGE|MEMBER_NO(NULL)|DATE_OF_INVOICE|TRANSACTION_NO|FEE_AMT|CGST_RATE|CGST_AMT|SGST_RATE|SGST_AMT|CS_TOT|IGST_RATE|IGST_AMT|IGST_TOT|QUANTITY(1)|CESS(0.00)|STATE_CODE|STATE_NAME|SERVICE_CODE(XXXXXX)|GSTIN_NO(NULL)|TAX_TYPE(INTRA/INTER)|APP_TYPE(V)|
								//INVOICE_NO|INVOICE_IMAGE|SUBSCRIPTION_NO|DATE_OF_INVOICE|TRANSACTION_NO|FEE_AMT|CGST_RATE|CGST_AMT|SGST_RATE|SGST_AMT|CS_TOT|IGST_RATE|IGST_AMT|IGST_TOT|QUANTITY(1)|CESS(0.00)|STATE_CODE|STATE_NAME|SERVICE_CODE(XXXXXX)|GSTIN_NO(NULL)|TAX_TYPE(INTRA/INTER)|APP_TYPE(V)|
								/*$data .= ''.$vision_invoice['invoice_no'].'|'.$vision_invoice_image.'|'.$vision_invoice['member_no'].'|'.$update_date_of_invoice.'|'.$vision_invoice['transaction_no'].'|'.$vision_invoice['fee_amt'].'|'.$vision_invoice['cgst_rate'].'|'.$vision_invoice['cgst_amt'].'|'.$vision_invoice['sgst_rate'].'|'.$vision_invoice['sgst_amt'].'|'.$vision_invoice['cs_total'].'|'.$vision_invoice['igst_rate'].'|'.$vision_invoice['igst_amt'].'|'.$vision_invoice['igst_total'].'|'.$vision_invoice['qty'].'|'.$vision_invoice['cess'].'|'.$vision_invoice['state_code'].'|'.$vision_invoice['state_name'].'|'.$vision_invoice['service_code'].'|'.$vision_invoice['gstin_no'].'|'.$vision_invoice['tax_type'].'|'.$vision_invoice['app_type']."|\n";*/
								$data .= ''.$vision_invoice['invoice_no'].'|'.$vision_invoice_image.'|'.$subscription_no.'|'.$update_date_of_invoice.'|'.$vision_invoice['transaction_no'].'|'.$vision_invoice['fee_amt'].'|'.$vision_invoice['cgst_rate'].'|'.$vision_invoice['cgst_amt'].'|'.$vision_invoice['sgst_rate'].'|'.$vision_invoice['sgst_amt'].'|'.$vision_invoice['cs_total'].'|'.$vision_invoice['igst_rate'].'|'.$vision_invoice['igst_amt'].'|'.$vision_invoice['igst_total'].'|'.$vision_invoice['qty'].'|'.$vision_invoice['cess'].'|'.$vision_invoice['state_code'].'|'.$vision_invoice['state_name'].'|'.$vision_invoice['service_code'].'|'.$vision_invoice['gstin_no'].'|'.$vision_invoice['tax_type'].'|'.$vision_invoice['app_type']."\n";
								if($dir_flg)
								{
									// For photo images
									if($vision_invoice_image)
									{
										/*$image = "./uploads/vision_invoice/supplier/".$vision_invoice['invoice_image'];
											$max_width = "1000";
											$max_height = "1000";
											$imgdata = $this->resize_image_max($image,$max_width,$max_height);
											imagejpeg($imgdata, $directory."/".$vision_invoice['invoice_image']);
											$photo_to_add = $directory."/".$vision_invoice['invoice_image'];
											$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);*/
										copy("./uploads/vision_invoice/supplier/".$vision_invoice['invoice_image'],$directory."/".$vision_invoice['invoice_image']);
										$photo_to_add = $directory."/".$vision_invoice['invoice_image'];
										$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
										if(!$photo_zip_flg)
										{
											fwrite($fp1, "**ERROR** - Vision Invoice Image not added to zip  - ".$vision_invoice['invoice_image']." (".$vision_invoice['member_no'].")\n");	
										}
										else
										$vision_invoice_image_cnt++;
									}
									if($photo_zip_flg)
									{
										$success[] = "Vision Invoice Images Zip Generated Successfully";
									}
									else
									{
										$error[] = "Error While Generating Vision Invoice Images Zip";
									}
								}
								$i++;
								$vision_invoice_count++;
								//fwrite($fp1, "\n");
								$file_w_flg = fwrite($fp, $data);
							}
							if($file_w_flg)
							{
								$success[] = "Vision Invoice Details File Generated Successfully. ";
							}
							else
							{
								$error[] = "Error While Generating Vision Invoice Details File.";
							}
						}
					}
					$zip->close();
					fwrite($fp1, "\n"."Total Vision Invoice Details Added = ".$vision_invoice_count."\n");
					fwrite($fp1, "\n"."Total Vision Invoice Images Added = ".$vision_invoice_image_cnt."\n");
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
				$this->log_model->cronlog("Vision Invoice Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."************************* Vision Invoice Details Cron Execution End ".$end_time." *************************"."\n");
				fclose($fp1);
			}
		}
		// CPD Invoices
		public function cpd_invoice()
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
			$cron_file_dir = "./uploads/cronFilesCustom/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF CPD Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700);
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "cpd_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* IIBF CPD Invoice Details Cron Execution Started - ".$start_time." ************************* \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$yesterday = '2021-01-31';
				$select = 'b.id as pay_txn_id,b.receipt_no';
				//$this->db->where('DATE(c.created_on) >=', '2019-07-16');
				//$this->db->where('DATE(c.created_on) <=', '2019-07-27');
				$ref_id = array(1064,1066,1069);
				$this->db->where_in('c.id', $ref_id);
				$this->db->join('payment_transaction b','b.ref_id = c.id','LEFT');
				$cpd_data = $this->Master_model->getRecords('cpd_registration c',array('pay_type' => 9,'pay_status' => 1,'status' => '1'),$select);
				// ' DATE(created_on)' => $yesterday,
				//echo $this->db->last_query();
				if(count($cpd_data))
				{
					$i = 1;
					$cpd_invoice_count = 0;
					$cpd_invoice_image_cnt = 0;
					$dirname = "cpd_invoice_image_".$current_date;
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
					foreach($cpd_data as $cpd_reg)
					{					
						$pay_txn_id = $cpd_reg['pay_txn_id'];
						$receipt_no = $cpd_reg['receipt_no'];
						// get invoice details for this member payment transaction by id and receipt_no
						$this->db->where('transaction_no !=','');
						$this->db->where('app_type','P');
						$this->db->where('receipt_no',$receipt_no);
						$cpd_invoice_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
						//echo "<br>SQL => ".$this->db->last_query();
						if(count($cpd_invoice_data))
						{
							foreach($cpd_invoice_data as $cpd_invoice)
							{
								$data = '';
								$cpd_invoice_image = '';
								$date_of_invoice = $cpd_invoice['date_of_invoice'];
								$update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
								if(is_file("./uploads/cpdinvoice/supplier/".$cpd_invoice['invoice_image']))
								{
									$cpd_invoice_image = CPD_INVOICE_FILE_PATH.$cpd_invoice['invoice_image'];
								}
								else
								{
									fwrite($fp1, "**ERROR** - CPD Invoice does not exist  - ".$cpd_invoice['invoice_image']." (".$cpd_invoice['member_no'].")\n");	
								}
								//INVOICE_NO|INVOICE_IMAGE|MEMBER_NO|DATE_OF_INVOICE|TRANSACTION_NO|FEE_AMT|CGST_RATE|CGST_AMT|SGST_RATE|SGST_AMT|CS_TOT|IGST_RATE|IGST_TOT|QUANTITY(1)|CESS(0.00)|STATE_CODE|STATE_NAME|SERVICE_CODE(XXXXXX)|GSTIN_NO(NULL)|TAX_TYPE(INTRA/INTER)|
								$data .= ''.$cpd_invoice['invoice_no'].'|'.$cpd_invoice_image.'|'.$cpd_invoice['member_no'].'|'.$update_date_of_invoice.'|'.$cpd_invoice['transaction_no'].'|'.$cpd_invoice['fee_amt'].'|'.$cpd_invoice['cgst_rate'].'|'.$cpd_invoice['cgst_amt'].'|'.$cpd_invoice['sgst_rate'].'|'.$cpd_invoice['sgst_amt'].'|'.$cpd_invoice['cs_total'].'|'.$cpd_invoice['igst_rate'].'|'.$cpd_invoice['igst_amt'].'|'.$cpd_invoice['igst_total'].'|'.$cpd_invoice['qty'].'|'.$cpd_invoice['cess'].'|'.$cpd_invoice['state_code'].'|'.$cpd_invoice['state_name'].'|'.$cpd_invoice['service_code'].'|'.$cpd_invoice['gstin_no'].'|'.$cpd_invoice['tax_type'].'|'.$cpd_invoice['app_type']."\n";
								if($dir_flg)
								{
									// For photo images
									if($cpd_invoice_image)
									{
										/*$image = "./uploads/cpdinvoice/supplier/".$cpd_invoice['invoice_image'];
											$max_width = "1000";
											$max_height = "1000";
											$imgdata = $this->resize_image_max($image,$max_width,$max_height);
											imagejpeg($imgdata, $directory."/".$cpd_invoice['invoice_image']);
											$photo_to_add = $directory."/".$cpd_invoice['invoice_image'];
											$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);*/
										copy("./uploads/cpdinvoice/supplier/".$cpd_invoice['invoice_image'],$directory."/".$cpd_invoice['invoice_image']);
										$photo_to_add = $directory."/".$cpd_invoice['invoice_image'];
										$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
										if(!$photo_zip_flg)
										{
											fwrite($fp1, "**ERROR** - CPD Invoice Image not added to zip  - ".$cpd_invoice['invoice_image']." (".$cpd_invoice['member_no'].")\n");	
										}
										else
										$cpd_invoice_image_cnt++;
									}
									if($photo_zip_flg)
									{
										$success[] = "CPD Invoice Images Zip Generated Successfully";
									}
									else
									{
										$error[] = "Error While Generating CPD Invoice Images Zip";
									}
								}
								$i++;
								$cpd_invoice_count++;
								//fwrite($fp1, "\n");
								$file_w_flg = fwrite($fp, $data);
							}
							if($file_w_flg)
							{
								$success[] = "CPD Invoice Details File Generated Successfully. ";
							}
							else
							{
								$error[] = "Error While Generating CPD Invoice Details File.";
							}
						}
					}
					$zip->close();
					fwrite($fp1, "\n"."Total CPD Invoice Details Added = ".$cpd_invoice_count."\n");
					fwrite($fp1, "\n"."Total CPD Invoice Images Added = ".$cpd_invoice_image_cnt."\n");
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
				$this->log_model->cronlog("IIBF CPD Invoice Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."************************* IIBF CPD Invoice Details Cron Execution End ".$end_time." *************************"."\n");
				fclose($fp1);
			}
		}
		/* FinQuest Invoices */ 
		public function finquest_invoice()
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
			$cron_file_dir = "./uploads/cronFilesCustom/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("FinQuest Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700);
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "finquest_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* FinQuest Invoice Details Cron Execution Started - ".$start_time." ************************* \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$yesterday = '2021-01-08';
				$select = 'b.id as pay_txn_id,b.receipt_no';
				//$this->db->where('DATE(c.created_on) >=', '2019-11-01');
				//$this->db->where('DATE(c.created_on) <=', '2019-11-30');
				$ref_id = array('404');
				$this->db->where_in('c.id', $ref_id);
				$this->db->join('payment_transaction b','b.ref_id=c.id','LEFT');
				$finquest_data = $this->Master_model->getRecords('fin_quest c',array('pay_type' => 8,'pay_status' => 1,'status' => '1'),$select);
				// ' DATE(created_on)' => $yesterday,
				//echo $this->db->last_query(); exit;
				if(count($finquest_data))
				{
					$i = 1;
					$finquest_invoice_count = 0;
					$finquest_invoice_image_cnt = 0;
					$dirname = "finquest_invoice_image_".$current_date;
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
					foreach($finquest_data as $fq_reg)
					{					
						$pay_txn_id = $fq_reg['pay_txn_id'];
						$receipt_no = $fq_reg['receipt_no'];
						// get invoice details for this finquest reg payment transaction by id and receipt_no
						$this->db->where('transaction_no !=','');
						$this->db->where('app_type','F');
						$this->db->where('receipt_no',$receipt_no);
						$finquest_invoice_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
						//echo "<br>SQL => ".$this->db->last_query();
						if(count($finquest_invoice_data))
						{
							foreach($finquest_invoice_data as $finquest_invoice)
							{
								$data = '';
								$finquest_invoice_image = '';
								$date_of_invoice = $finquest_invoice['date_of_invoice'];
								$update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
								if(is_file("./uploads/finquestinvoice/supplier/".$finquest_invoice['invoice_image']))
								{
									$finquest_invoice_image = FQ_INVOICE_FILE_PATH.$finquest_invoice['invoice_image'];
								}
								else
								{
									fwrite($fp1, "**ERROR** - FinQuest Invoice does not exist  - ".$finquest_invoice['invoice_image']." (".$finquest_invoice['member_no'].")\n");	
								}
								//INVOICE_NO|INVOICE_IMAGE|MEMBER_NO|DATE_OF_INVOICE|TRANSACTION_NO|FEE_AMT|CGST_RATE|CGST_AMT|SGST_RATE|SGST_AMT|CS_TOT|IGST_RATE|IGST_AMT|IGST_TOT|QUANTITY(1)|CESS(0.00)|STATE_CODE|STATE_NAME|SERVICE_CODE(XXXXXX)|GSTIN_NO(NULL)|TAX_TYPE(INTRA/INTER)|APP_TYPE(F)|
								$data .= ''.$finquest_invoice['invoice_no'].'|'.$finquest_invoice_image.'|'.$finquest_invoice['member_no'].'|'.$update_date_of_invoice.'|'.$finquest_invoice['transaction_no'].'|'.$finquest_invoice['fee_amt'].'|'.$finquest_invoice['cgst_rate'].'|'.$finquest_invoice['cgst_amt'].'|'.$finquest_invoice['sgst_rate'].'|'.$finquest_invoice['sgst_amt'].'|'.$finquest_invoice['cs_total'].'|'.$finquest_invoice['igst_rate'].'|'.$finquest_invoice['igst_amt'].'|'.$finquest_invoice['igst_total'].'|'.$finquest_invoice['qty'].'|'.$finquest_invoice['cess'].'|'.$finquest_invoice['state_code'].'|'.$finquest_invoice['state_name'].'|'.$finquest_invoice['service_code'].'|'.$finquest_invoice['gstin_no'].'|'.$finquest_invoice['tax_type'].'|'.$finquest_invoice['app_type']."\n";
								if($dir_flg)
								{
									// For photo images
									if($finquest_invoice_image)
									{
										/*$image = "./uploads/finquestinvoice/supplier/".$finquest_invoice['invoice_image'];
											$max_width = "1000";
											$max_height = "1000";
											$imgdata = $this->resize_image_max($image,$max_width,$max_height);
											imagejpeg($imgdata, $directory."/".$finquest_invoice['invoice_image']);
											$photo_to_add = $directory."/".$finquest_invoice['invoice_image'];
											$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);*/
										copy("./uploads/finquestinvoice/supplier/".$finquest_invoice['invoice_image'],$directory."/".$finquest_invoice['invoice_image']);
										$photo_to_add = $directory."/".$finquest_invoice['invoice_image'];
										$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
										if(!$photo_zip_flg)
										{
											fwrite($fp1, "**ERROR** - FinQuest Invoice Image not added to zip  - ".$finquest_invoice['invoice_image']." (".$finquest_invoice['member_no'].")\n");	
										}
										else
										$finquest_invoice_image_cnt++;
									}
									if($photo_zip_flg)
									{
										$success[] = "FinQuest Invoice Images Zip Generated Successfully";
									}
									else
									{
										$error[] = "Error While Generating FinQuest Invoice Images Zip";
									}
								}
								$i++;
								$finquest_invoice_count++;
								//fwrite($fp1, "\n");
								$file_w_flg = fwrite($fp, $data);
							}
							if($file_w_flg)
							{
								$success[] = "FinQuest Invoice Details File Generated Successfully. ";
							}
							else
							{
								$error[] = "Error While Generating FinQuest Invoice Details File.";
							}
						}
					}
					$zip->close();
					fwrite($fp1, "\n"."Total FinQuest Invoice Details Added = ".$finquest_invoice_count."\n");
					fwrite($fp1, "\n"."Total FinQuest Invoice Images Added = ".$finquest_invoice_image_cnt."\n");
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
				$this->log_model->cronlog("FinQuest Invoice Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."************************* FinQuest Invoice Details Cron Execution End ".$end_time." *************************"."\n");
				fclose($fp1);
			}
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
				$yesterday = '2020-10-24';
				$blended_id = array(20757,20764,20766,20773,20774,20815,20823,20825,20830,20837,20839,20843,20844,20858,20861,20863,20875,20877,20878,20882,20884,20887,20911,20935,20941);
				$select = 'c.zone_code,b.id as pay_txn_id,b.receipt_no';
				//$this->db->where('DATE(c.createdon) >=', '2020-06-17');
				//$this->db->where('DATE(c.createdon) <=', '2020-06-18');
				$this->db->where_in('c.blended_id', $blended_id);
				//$this->db->where('c.batch_code', 'VCBC001');
				$this->db->join('payment_transaction b','b.ref_id = c.blended_id','LEFT');
				$blended_course_data = $this->Master_model->getRecords('blended_registration c',array('pay_type'=>10,'pay_status' => 1,'status' => '1'),$select);
				//'DATE(createdon)' => $yesterday,
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
						/* 
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
		// Contact Classes Invoices
		public function contact_classes_invoice()
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
			//$current_date = "20171108";	// custom date
			$cron_file_dir = "./uploads/cronFilesCustom/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF Contact Classes Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700);
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "contact_classes_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* IIBF Contact Classes Invoice Details Cron Execution Started - ".$start_time." ************************* \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2019-06-05';
				$select = 'c.contact_classes_id,b.id as pay_txn_id,b.receipt_no';
				$ref_id = array(3294);
				$this->db->where_in('c.contact_classes_id', $ref_id);
				//$this->db->where('DATE(c.createdon) >=', '2019-07-02');
				//$this->db->where('DATE(c.createdon) <=', '2019-07-27');
				$this->db->join('payment_transaction b','b.ref_id=c.contact_classes_id','LEFT');
				$contact_classes_data = $this->Master_model->getRecords('contact_classes_registration c',array('pay_type' => 11,'pay_status' => 1,'status' => '1'),$select);
				//' DATE(createdon)' => $yesterday,
				//$contact_classes_data = $this->Master_model->getRecords('contact_classes_registration c',array('pay_type' => 11,'pay_status' => 1,'status' => '1'),$select);
				echo $this->db->last_query();
				//exit;
				if(count($contact_classes_data))
				{
					$i = 1;
					$contact_classes_invoice_count = 0;
					$contact_classes_invoice_image_cnt = 0;
					$dirname = "contact_classes_invoice_image_".$current_date;
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
					foreach($contact_classes_data as $contact_classes)
					{					
						$pay_txn_id = $contact_classes['pay_txn_id'];
						$receipt_no = $contact_classes['receipt_no'];
						$contact_classes_id = $contact_classes['contact_classes_id'];
						// get zone code for this Contact Classes registration by id
						//$contact_classes_sub_reg_data = $this->Master_model->getRecords('contact_classes_Subject_registration',array('contact_classes_regid'=>$contact_classes_id), 'zone_code', FALSE, FALSE, 1);
						//echo $this->db->last_query(); die;
						$zone_code = 'NZ';//$contact_classes_sub_reg_data[0]['zone_code'];
						// get invoice details for this Contact Classes payment transaction by id and receipt_no
						$this->db->where('transaction_no !=','');
						$this->db->where('app_type','E');
						$this->db->where('receipt_no',$receipt_no);
						$contact_classes_invoice_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
						//echo "<br>SQL => ".$this->db->last_query();
						if(count($contact_classes_invoice_data))
						{
							foreach($contact_classes_invoice_data as $contact_classes_invoice)
							{
								$data = '';
								$contact_classes_invoice_image = '';
								$date_of_invoice = $contact_classes_invoice['date_of_invoice'];
								$update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
								if(is_file("./uploads/contact_classes_invoice/user/".$zone_code."/".$contact_classes_invoice['invoice_image']))
								{
									$contact_classes_invoice_image = CC_INVOICE_FILE_PATH.$contact_classes_invoice['invoice_image'];
								}
								else
								{
									fwrite($fp1, "**ERROR** - Contact Classes Invoice does not exist  - ".$contact_classes_invoice['invoice_image']." (".$contact_classes_invoice['member_no'].")\n");	
								}
								$state_of_center_code = '';
								$state_of_center = $contact_classes_invoice['state_of_center'];
								$state_of_center_code = $this->Master_model->getRecords('state_master c',array('state_code' => $state_of_center,'state_delete' => 0),'state_no');
								$state_of_center_code = $state_of_center_code[0]['state_no'];
								// Check exam code original value
								$exam_code = '';
								if($contact_classes_invoice['exam_code'] != '' && $contact_classes_invoice['exam_code'] != 0)
								{
									$ex_code = $this->master_model->getRecords('contact_classes_cource_master',array('course_code'=>$contact_classes_invoice['exam_code'],'isactive' => 1));
									if(count($ex_code))
									{
										if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0)
										{	$exam_code = $ex_code[0]['original_val'];	}
										else
										{	$exam_code = $contact_classes_invoice['exam_code'];	}
									}
								}
								else
								{	
									$exam_code = $contact_classes_invoice['exam_code'];	
								}
								//EXAM_CODE|EXAM_PERIOD|CENTER_CODE|CENTER_NAME|STATE_OF_CENTER|INVOICE_NO|INVOICE_IMAGE|MEMBER_NO|DATE_OF_INVOICE|TRANSACTION_NO|FEE_AMT|CGST_RATE|CGST_AMT|SGST_RATE|SGST_AMT|CS_TOT|IGST_RATE|IGST_AMT|IGST_TOT|QUANTITY(1)|CESS(0.00)|STATE_CODE|STATE_NAME|SERVICE_CODE(XXXXXX)|GSTIN_NO(NULL)|TAX_TYPE(INTRA/INTER)|APP_TYPE(E)|ZONE_CODE|STATE_OF_CENTER_CODE
								$data .= ''.$exam_code.'|'.$contact_classes_invoice['exam_period'].'|'.$contact_classes_invoice['center_code'].'|'.$contact_classes_invoice['center_name'].'|'.$contact_classes_invoice['state_of_center'].'|'.$contact_classes_invoice['invoice_no'].'|'.$contact_classes_invoice_image.'|'.$contact_classes_invoice['member_no'].'|'.$update_date_of_invoice.'|'.$contact_classes_invoice['transaction_no'].'|'.$contact_classes_invoice['fee_amt'].'|'.$contact_classes_invoice['cgst_rate'].'|'.$contact_classes_invoice['cgst_amt'].'|'.$contact_classes_invoice['sgst_rate'].'|'.$contact_classes_invoice['sgst_amt'].'|'.$contact_classes_invoice['cs_total'].'|'.$contact_classes_invoice['igst_rate'].'|'.$contact_classes_invoice['igst_amt'].'|'.$contact_classes_invoice['igst_total'].'|'.$contact_classes_invoice['qty'].'|'.$contact_classes_invoice['cess'].'|'.$contact_classes_invoice['state_code'].'|'.$contact_classes_invoice['state_name'].'|'.$contact_classes_invoice['service_code'].'|'.$contact_classes_invoice['gstin_no'].'|'.$contact_classes_invoice['tax_type'].'|'.$contact_classes_invoice['app_type'].'|'.$zone_code.'|'.$state_of_center_code."\n";
								if($dir_flg)
								{
									// For photo images
									if($contact_classes_invoice_image)
									{
										/*$image = "./uploads/contact_classes_invoice/supplier/".$zone_code."/".$contact_classes_invoice['invoice_image'];
											$max_width = "1000";
											$max_height = "1000";
											$imgdata = $this->resize_image_max($image,$max_width,$max_height);
											imagejpeg($imgdata, $directory."/".$contact_classes_invoice['invoice_image']);
											$photo_to_add = $directory."/".$contact_classes_invoice['invoice_image'];
											$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);*/
										copy("./uploads/contact_classes_invoice/user/".$zone_code."/".$contact_classes_invoice['invoice_image'],$directory."/".$contact_classes_invoice['invoice_image']);
										$photo_to_add = $directory."/".$contact_classes_invoice['invoice_image'];
										$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
										if(!$photo_zip_flg)
										{
											fwrite($fp1, "**ERROR** - Contact Classes Invoice Image not added to zip  - ".$contact_classes_invoice['invoice_image']." (".$contact_classes_invoice['member_no'].")\n");	
										}
										else
										$contact_classes_invoice_image_cnt++;
									}
									if($photo_zip_flg)
									{
										$success[] = "Contact Classes Invoice Images Zip Generated Successfully";
									}
									else
									{
										$error[] = "Error While Generating Contact Classes Invoice Images Zip";
									}
								}
								$i++;
								$contact_classes_invoice_count++;
								//fwrite($fp1, "\n");
								$file_w_flg = fwrite($fp, $data);
							}
							if($file_w_flg)
							{
								$success[] = "Contact Classes Invoice Details File Generated Successfully. ";
							}
							else
							{
								$error[] = "Error While Generating Contact Classes Invoice Details File.";
							}
						}
					}
					$zip->close();
					fwrite($fp1, "\n"."Total Contact Classes Invoice Details Added = ".$contact_classes_invoice_count."\n");
					fwrite($fp1, "\n"."Total Contact Classes Invoice Images Added = ".$contact_classes_invoice_image_cnt."\n");
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
				$this->log_model->cronlog("IIBF Contact Classes Invoice Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."************************* IIBF Contact Classes Invoice Details Cron Execution End ".$end_time." *************************"."\n");
				fclose($fp1);
			}
		}
		// GST Recovery Invoices
		public function gst_recovery_invoice()
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
			//$current_date = "20171225";	// custom date
			$cron_file_dir = "./uploads/invoice_cronfiles/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF GST Recovery Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0755);
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "gst_recovery_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* IIBF GST Recovery Invoice Details Cron Execution Started - ".$start_time." ************************* \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = '2017-11-08';
				$select = 'gst_recovery_details.*,payment_transaction.transaction_no';
				$this->db->join('payment_transaction','payment_transaction.ref_id=gst_recovery_details.gst_recovery_details_pk','LEFT');
				$gst_recovery_data = $this->Master_model->getRecords('gst_recovery_details',array(' DATE(created_on)' => $yesterday,'payment_transaction.pay_type' => 13,'pay_status' => 1,'status' => '1'),$select);
				//echo "<br>".$this->db->last_query(); //die();
				if(count($gst_recovery_data))
				{
					$i = 1;
					$gst_recovery_invoice_count = 0;
					$gst_recovery_invoice_image_cnt = 0;
					$dirname = "gst_recovery_invoice_image_".$current_date;
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
					foreach($gst_recovery_data as $gst_recovery)
					{					
						$data = '';
						$gst_recovery_invoice_image = '';
						$date_of_invoice = $gst_recovery['date_of_invoice'];
						$update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
						$date_of_doc = $gst_recovery['date_of_doc'];
						$update_date_of_doc = date('d-M-y',strtotime($date_of_doc));
						if(is_file("./uploads/gst_recovery_invoice/supplier/".$gst_recovery['doc_image']))
						{
							$gst_recovery_invoice_image = GST_RECOVERY_INVOICE_FILE_PATH.$gst_recovery['doc_image'];
						}
						else
						{
							fwrite($fp1, "**ERROR** - GST Recovery Invoice does not exist  - ".$gst_recovery['doc_image']." (".$gst_recovery['member_no'].")\n");	
						}
						$igst_rate = '18'; // for IGST
						/*
							------------------------------------
							Service Name				App Type
							------------------------------------
							Membership Registration		R
							Exam - <<exam_name>>		O
							Duplicate ID Card			D
							Duplicate Certificate		C
							Membership Renewal			N
							------------------------------------
						*/
						$appTypeArr = array('1'=>'R','2'=>'O','3'=>'D','4'=>'C','5'=>'N');
						$app_type = $appTypeArr[$gst_recovery['pay_type']];
						//MEMBER_NO|EXAM_CODE(NULL for APP TYPE R/D/N) )|EXAM_PERIOD(NULL for APP TYPE R/D/N)|INVOICE_NO|DATE_OF_INVOICE|IGST_RATE|IGST_AMT|IGST_TOT|STATE_CODE|STATE_NAME|DOC_NO|DATE_OF_DOC|DOC_IMAGE|TRANSACTION_NO|APP_TYPE(R/O/D/C/N)|
						$data .= ''.$gst_recovery['member_no'].'|'.$gst_recovery['exam_code'].'|'.$gst_recovery['exam_period'].'|'.$gst_recovery['invoice_no'].'|'.$update_date_of_invoice.'|'.$igst_rate.'|'.$gst_recovery['igst_amt'].'|'.$gst_recovery['igst_amt'].'|'.$gst_recovery['state_code'].'|'.$gst_recovery['state_name'].'|'.$gst_recovery['doc_no'].'|'.$update_date_of_doc.'|'.$gst_recovery['doc_image'].'|'.$gst_recovery['transaction_no'].'|'.$app_type."|\n";
						if($dir_flg)
						{
							if($gst_recovery_invoice_image)
							{
								copy("./uploads/gst_recovery_invoice/supplier/".$gst_recovery['doc_image'],$directory."/".$gst_recovery['doc_image']);
								$photo_to_add = $directory."/".$gst_recovery['doc_image'];
								$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
								$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
								if(!$photo_zip_flg)
								{
									fwrite($fp1, "**ERROR** - GST Recovery Invoice Image not added to zip  - ".$gst_recovery['doc_image']." (".$gst_recovery['member_no'].")\n");	
								}
								else
								$gst_recovery_invoice_image_cnt++;
							}
							if($photo_zip_flg)
							{
								$success[] = "GST Recovery Invoice Images Zip Generated Successfully";
							}
							else
							{
								$error[] = "Error While Generating GST Recovery Invoice Images Zip";
							}
						}
						$i++;
						$gst_recovery_invoice_count++;
						//fwrite($fp1, "\n");
						$file_w_flg = fwrite($fp, $data);
					}
					if($file_w_flg)
					{
						$success[] = "GST Recovery Invoice Details File Generated Successfully. ";
					}
					else
					{
						$error[] = "Error While Generating GST Recovery Invoice Details File.";
					}
					$zip->close();
					fwrite($fp1, "\n"."Total GST Recovery Invoice Details Added = ".$gst_recovery_invoice_count."\n");
					fwrite($fp1, "\n"."Total GST Recovery Invoice Images Added = ".$gst_recovery_invoice_image_cnt."\n");
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
				$this->log_model->cronlog("IIBF GST Recovery Invoice Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."************************* IIBF GST Recovery Invoice Details Cron Execution End ".$end_time." *************************"."\n");
				fclose($fp1);
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
		public function dra_inst_invoice()
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
			//$current_date = "20171107";	// custom date
			$cron_file_dir = "./uploads/rahultest/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF DRA Institute Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700);
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "dra_inst_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* IIBF DRA Institute Invoice Details Cron Execution Started - ".$start_time." ************************* \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				//$yesterday = "2018-01-30";	// custom date
				$select = 'b.id as pay_txn_id,b.receipt_no';
				$ref_id = array('44','46','49','48','54','50','55','60','58');
				$this->db->where_in('c.id', $ref_id);
				$this->db->join('payment_transaction b','b.ref_id=c.id','LEFT');
				$dra_inst_data = $this->Master_model->getRecords('dra_inst_registration c',array('pay_type' => 16,'c.status' => 1,'b.status' => '1'),$select);
				// ' DATE(created_on)' => $yesterday,
				echo $this->db->last_query();
				if(count($dra_inst_data))
				{
					$i = 1;
					$dra_inst_invoice_count = 0;
					$dra_inst_invoice_image_cnt = 0;
					$dirname = "dra_inst_invoice_image_".$current_date;
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
					foreach($dra_inst_data as $dra_inst_reg)
					{					
						$pay_txn_id = $dra_inst_reg['pay_txn_id'];
						$receipt_no = $dra_inst_reg['receipt_no'];
						// get invoice details for this member payment transaction by id and receipt_no
						$this->db->where('transaction_no !=','');
						$this->db->where('app_type','H');
						$this->db->where('receipt_no',$receipt_no);
						$dra_inst_invoice_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
						//echo "<br>SQL => ".$this->db->last_query();
						if(count($dra_inst_invoice_data))
						{
							foreach($dra_inst_invoice_data as $dra_inst_invoice)
							{
								$data = '';
								$dra_inst_invoice_image = '';
								$date_of_invoice = $dra_inst_invoice['date_of_invoice'];
								$update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
								if(is_file("./uploads/drainvoice/supplier/".$dra_inst_invoice['invoice_image']))
								{
									$dra_inst_invoice_image = DRA_INSTITUTE_INVOICE_FILE_PATH.$dra_inst_invoice['invoice_image'];
								}
								else
								{
									fwrite($fp1, "**ERROR** - DRA Institute Invoice does not exist  - ".$dra_inst_invoice['invoice_image']."\n");	
								}
								//INVOICE_NO|INVOICE_IMAGE|DATE_OF_INVOICE|TRANSACTION_NO|FEE_AMT|CGST_RATE|CGST_AMT|SGST_RATE|SGST_AMT|CS_TOT|IGST_RATE|IGST_AMT|IGST_TOT|QUANTITY(1)|CESS(0.00)|STATE_CODE|STATE_NAME|SERVICE_CODE(XXXXXX)|GSTIN_NO(NULL)|TAX_TYPE(INTRA/INTER)|APP_TYPE(A)|
								$data .= ''.$dra_inst_invoice['invoice_no'].'|'.$dra_inst_invoice_image.'|'.$update_date_of_invoice.'|'.$dra_inst_invoice['transaction_no'].'|'.$dra_inst_invoice['fee_amt'].'|'.$dra_inst_invoice['cgst_rate'].'|'.$dra_inst_invoice['cgst_amt'].'|'.$dra_inst_invoice['sgst_rate'].'|'.$dra_inst_invoice['sgst_amt'].'|'.$dra_inst_invoice['cs_total'].'|'.$dra_inst_invoice['igst_rate'].'|'.$dra_inst_invoice['igst_amt'].'|'.$dra_inst_invoice['igst_total'].'|'.$dra_inst_invoice['qty'].'|'.$dra_inst_invoice['cess'].'|'.$dra_inst_invoice['state_code'].'|'.$dra_inst_invoice['state_name'].'|'.$dra_inst_invoice['service_code'].'|'.$dra_inst_invoice['gstin_no'].'|'.$dra_inst_invoice['tax_type'].'|'.$dra_inst_invoice['app_type']."\n";							
								if($dir_flg)
								{
									// For photo images
									if($dra_inst_invoice_image)
									{
										/*$image = "./uploads/dra_instinvoice/supplier/".$dra_inst_invoice['invoice_image'];
											$max_width = "1000";
											$max_height = "1000";
											$imgdata = $this->resize_image_max($image,$max_width,$max_height);
											imagejpeg($imgdata, $directory."/".$dra_inst_invoice['invoice_image']);
											$photo_to_add = $directory."/".$dra_inst_invoice['invoice_image'];
											$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);*/
										copy("./uploads/drainvoice/supplier/".$dra_inst_invoice['invoice_image'],$directory."/".$dra_inst_invoice['invoice_image']);
										$photo_to_add = $directory."/".$dra_inst_invoice['invoice_image'];
										$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
										if(!$photo_zip_flg)
										{
											fwrite($fp1, "**ERROR** - DRA Institute Invoice Image not added to zip  - ".$dra_inst_invoice['invoice_image']." (".$dra_inst_invoice['member_no'].")\n");	
										}
										else
										$dra_inst_invoice_image_cnt++;
									}
									if($photo_zip_flg)
									{
										$success[] = "DRA Institute Invoice Images Zip Generated Successfully";
									}
									else
									{
										$error[] = "Error While Generating DRA Institute Invoice Images Zip";
									}
								}
								$i++;
								$dra_inst_invoice_count++;
								//fwrite($fp1, "\n");
								$file_w_flg = fwrite($fp, $data);
							}
							if($file_w_flg)
							{
								$success[] = "DRA Institute Invoice Details File Generated Successfully. ";
							}
							else
							{
								$error[] = "Error While Generating DRA Institute Invoice Details File.";
							}
						}
					}
					$zip->close();
					fwrite($fp1, "\n"."Total DRA Institute Invoice Details Added = ".$dra_inst_invoice_count."\n");
					fwrite($fp1, "\n"."Total DRA Institute Invoice Images Added = ".$dra_inst_invoice_image_cnt."\n");
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
				$this->log_model->cronlog("IIBF DRA Institute Invoice Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."************************* IIBF DRA Institute Invoice Details Cron Execution End ".$end_time." *************************"."\n");
				fclose($fp1);
			}
		}
		/* Bulk Exam Invoices */
		public function bulk_exam_invoice_old()
		{
			ini_set("memory_limit", "-1");
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$file_w_flg = 0;
			$photo_zip_flg = 0;
			$success = array();
			$error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	// default current date
			//$current_date = "20180103";	// custom date
			$cron_file_dir = "./uploads/cronFilesCustom/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF Bulk Exam Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "bulk_exam_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* IIBF Bulk Exam Invoice Details Cron Execution Started - ".$start_time." ************************* \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));	// default current date
				//$this->db->where('DATE(updated_date) >=', '2019-07-03');
				//$this->db->where('DATE(updated_date) <=', '2019-07-03');
				//$this->db->where('exam_code', '101');
				//$this->db->where('exam_period', '564');
				//$this->db->where("(DATE(updated_date) = '".$yesterday."') AND status = 1");
				//$this->db->where("`UTR_no` LIKE 'KMB_UTR_05072019' AND status = 1");
				$this->db->where("status = 1");
				$recp_no = array(537,
				543,
				556,
				568);
				$this->db->where_in('receipt_no', $recp_no);
				$bulk_payment = $this->Master_model->getRecords('bulk_payment_transaction');
				//echo $this->db->last_query(); 
				//die();
				if(count($bulk_payment))
				{
					$i = 1;
					$bulk_exam_invoice_count = 0;
					$bulk_exam_invoice_Image_cnt = 0;
					$dirname = "bulk_exam_invoice_image_".$current_date;
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
					foreach($bulk_payment as $payment)
					{
						$pay_txn_id = $payment['id'];
						$receipt_no = $payment['receipt_no'];
						// get special exam dates
						$this->db->join('member_exam b','a.memexamid = b.id','LEFT');
						$mem_exam_data = $this->Master_model->getRecords('bulk_member_payment_transaction a',array('ptid'=>$pay_txn_id));      
						//echo "<br><br>1SQL => ".$this->db->last_query();
						$examination_date = $mem_exam_data[0]['examination_date'];
						// get invoice details for this member payment transaction by id and receipt_no
						$this->db->where('transaction_no !=','');
						$this->db->where('app_type','Z');
						$this->db->where('receipt_no',$receipt_no);
						$bulk_exam_invoice_arr = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
						//echo "<br>2SQL => ".$this->db->last_query();// die();
						if(count($bulk_exam_invoice_arr))
						{
							foreach($bulk_exam_invoice_arr as $bulk_exIn_data)
							{
								$data = '';
								$bulk_exam_invoice_Image = '';
								$date_of_invoice = $bulk_exIn_data['date_of_invoice'];
								$Update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
								if(is_file("./uploads/bulkexaminvoice/supplier/".$bulk_exIn_data['invoice_image']))
								{
									$bulk_exam_invoice_Image = BULK_EXAM_INVOICE_FILE_PATH.$bulk_exIn_data['invoice_image'];
								}
								else
								{
									fwrite($fp1, "**ERROR** - Exam Invoice does not exist  - ".$bulk_exIn_data['invoice_image']." (".$bulk_exIn_data['member_no'].")\n");	
								}
								$exam_code = '';
								if($bulk_exIn_data['exam_code'] == 340 || $bulk_exIn_data['exam_code'] == 3400 || $bulk_exIn_data['exam_code'] == 34000){
									$exam_code = 34;
									}elseif($bulk_exIn_data['exam_code'] == 580 || $bulk_exIn_data['exam_code'] == 5800 || $bulk_exIn_data['exam_code'] == 58000){
									$exam_code = 58;
									}elseif($bulk_exIn_data['exam_code'] == 1600 || $bulk_exIn_data['exam_code'] == 16000){
									$exam_code = 160;
									}elseif($bulk_exIn_data['exam_code'] == 200){
									$exam_code = 20;
									}elseif($bulk_exIn_data['exam_code'] == 1770 || $bulk_exIn_data['exam_code'] == 17700){
									$exam_code =177;
									}elseif ($bulk_exIn_data['exam_code'] == 590){
									$exam_code = 59;
									}elseif ($bulk_exIn_data['exam_code'] == 810){
									$exam_code = 81;
									}elseif ($bulk_exIn_data['exam_code'] == 1750){
									$exam_code = 175;
									}elseif ($bulk_exIn_data['exam_code'] == 1010 || $bulk_exIn_data['exam_code'] == 10100 || $bulk_exIn_data['exam_code'] == 101000){
									$exam_code = 101;
									}elseif ($bulk_exIn_data['exam_code'] == 2010){
									$exam_code = 1010;
									}else{
									$exam_code = $bulk_exIn_data['exam_code'];
								}
								// rewrite exam period
								$exam_period = '';
								if($examination_date != '' && $examination_date != "0000-00-00")
								{
									$special_exam_period = $this->Master_model->getRecords('special_exam_dates',array('examination_date'=>$examination_date));
									if(count($special_exam_period))
									{
										$exam_period = $special_exam_period[0]['period'];	
									}
									}else{
									$exam_period = $bulk_exIn_data['exam_period'];
								}
								//EXAM_CODE|EXAM_PERIOD|INVOICE_NO|INVOICE_IMAGE|DATE_OF_INVOICE|TRANSACTION_NO|FEE_AMT|CGST_RATE|CGST_AMT|SGST_RATE|SGST_AMT|CS_TOT|IGST_RATE|IGST_AMT|IGST_TOT|QUANTITY(1/N)|CESS(0.00)|INSTITUTE_CODE|INSTITUTE_NAME|INSITITUTE_STATE_CODE|INSITITUTE_STATE_NAME|SERVICE_CODE(999294)|GSTIN_NO(15 digits)|TAX_TYPE(INTRA/INTER)|APP_TYPE(Z)|DISC_RATE|DISC_AMT|TDS_AMT|BULK_FLG(Y/N)|
								$bulk_flg = 'Y';
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$bulk_exIn_data['invoice_no'].'|'.$bulk_exam_invoice_Image.'|'.$Update_date_of_invoice.'|'.$bulk_exIn_data['transaction_no'].'|'.$bulk_exIn_data['fee_amt'].'|'.$bulk_exIn_data['cgst_rate'].'|'.$bulk_exIn_data['cgst_amt'].'|'.$bulk_exIn_data['sgst_rate'].'|'.$bulk_exIn_data['sgst_amt'].'|'.$bulk_exIn_data['cs_total'].'|'.$bulk_exIn_data['igst_rate'].'|'.$bulk_exIn_data['igst_amt'].'|'.$bulk_exIn_data['igst_total'].'|'.$bulk_exIn_data['qty'].'|'.$bulk_exIn_data['cess'].'|'.$bulk_exIn_data['institute_code'].'|'.$bulk_exIn_data['institute_name'].'|'.$bulk_exIn_data['state_code'].'|'.$bulk_exIn_data['state_name'].'|'.$bulk_exIn_data['service_code'].'|'.$bulk_exIn_data['gstin_no'].'|'.$bulk_exIn_data['tax_type'].'|'.$bulk_exIn_data['app_type'].'|'.$bulk_exIn_data['disc_rate'].'|'.$bulk_exIn_data['disc_amt'].'|'.$bulk_exIn_data['tds_amt'].'|'.$bulk_flg."\n";
								if($dir_flg)
								{
									// For photo images
									if($bulk_exam_invoice_Image)
									{
										/*$image = "./uploads/bulkexaminvoice/supplier/".$bulk_exIn_data['invoice_image'];
											$max_width = "1000";
											$max_height = "1000";
											$imgdata = $this->resize_image_max($image,$max_width,$max_height);
											imagejpeg($imgdata, $directory."/".$bulk_exIn_data['invoice_image']);
											$photo_to_add = $directory."/".$bulk_exIn_data['invoice_image'];
											$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);*/
										copy("./uploads/bulkexaminvoice/supplier/".$bulk_exIn_data['invoice_image'],$directory."/".$bulk_exIn_data['invoice_image']);
										$photo_to_add = $directory."/".$bulk_exIn_data['invoice_image'];
										$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
										if(!$photo_zip_flg)
										{
											fwrite($fp1, "**ERROR** - Bulk Exam Invoice Image not added to zip  - ".$bulk_exIn_data['invoice_image']." (".$bulk_exIn_data['member_no'].")\n");	
										}
										else
										$bulk_exam_invoice_Image_cnt++;
									}
									if($photo_zip_flg)
									{
										$success['zip'] = "Bulk Exam Invoice Images Zip Generated Successfully";
									}
									else
									{
										$error['zip'] = "Error While Generating Bulk Exam Invoice Images Zip";
									}
								}
								$i++;
								$bulk_exam_invoice_count++;
								$file_w_flg = fwrite($fp, $data);
							}
							if($file_w_flg)
							{
								$success['file'] = "Bulk Exam Invoice Details File Generated Successfully. ";
							}
							else
							{
								$error['file'] = "Error While Generating Bulk Exam Invoice Details File.";
							}
						}
					}
					fwrite($fp1, "\n"."Total Bulk Exam Invoice Details Added = ".$bulk_exam_invoice_count."\n");
					fwrite($fp1, "\n"."Total Bulk Exam Invoice Images Added = ".$bulk_exam_invoice_Image_cnt."\n");
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
				$this->log_model->cronlog("IIBF Bulk Exam Invoice Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."************************* IIBF Bulk Exam Invoice Details Cron Execution End ".$end_time." *************************"."\n");
				fclose($fp1);
			}
		}
		/* Bulk Exam Invoices */
		public function bulk_exam_invoice()
		{
			ini_set("memory_limit", "-1");
			$dir_flg = 0;
			$parent_dir_flg = 0;
			$file_w_flg = 0;
			$photo_zip_flg = 0;
			$success = array();
			$error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	// default current date
			$cron_file_dir = "./uploads/cronFilesCustom/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog(" Bulk Exam Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "bulk_exam_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n********** Bulk Exam Invoice Details Cron Start - ".$start_time." ********** \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));	// default current date
				//DATE(date) = '".$yesterday."' OR 
				//$this->db->where("(DATE(updated_date) = '".$yesterday."') AND status = 1");
				//$this->db->where("`UTR_no` LIKE 'BKIDN17356990384' AND status = 1");
				$this->db->where('DATE(updated_date)>=' ,'2021-01-01');
				$this->db->where('DATE(updated_date)<=' , '2021-01-27');
				
				$bulk_payment = $this->Master_model->getRecords('bulk_payment_transaction',array('status'=>'1'));
				//echo  $this->db->last_query(); die;
				if(count($bulk_payment))
				{
					$i = 1;
					$bulk_exam_invoice_count = 0;
					$bulk_exam_invoice_Image_cnt = 0;
					$dirname = "bulk_exam_invoice_image_".$current_date;
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
					foreach($bulk_payment as $payment)
					{
						$pay_txn_id = $payment['id'];
						$receipt_no = $payment['receipt_no'];
						// get special exam dates
						$this->db->join('member_exam b','a.memexamid = b.id','LEFT');
						$mem_exam_data = $this->Master_model->getRecords('bulk_member_payment_transaction a',array('ptid'=>$pay_txn_id));
						$examination_date = $mem_exam_data[0]['examination_date'];
						// get invoice details for this member payment transaction by id and receipt_no
						$this->db->where('transaction_no !=','');
						$this->db->where('app_type','Z');
						$this->db->where('receipt_no',$receipt_no);
						$bulk_exam_invoice_arr = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
						if(count($bulk_exam_invoice_arr))
						{
							foreach($bulk_exam_invoice_arr as $bulk_exIn_data)
							{
								$data = '';
								$bulk_exam_invoice_Image = '';
								$date_of_invoice = $bulk_exIn_data['date_of_invoice'];
								$Update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
								if(is_file("./uploads/bulkexaminvoice/supplier/".$bulk_exIn_data['invoice_image']))
								{
									$bulk_exam_invoice_Image = BULK_EXAM_INVOICE_FILE_PATH.$bulk_exIn_data['invoice_image'];
								}
								else
								{
									fwrite($fp1, "* ERROR * - Exam Invoice does not exist  - ".$bulk_exIn_data['invoice_image']." (".$bulk_exIn_data['member_no'].")\n");	
								}
								$exam_code = '';
								if($bulk_exIn_data['exam_code'] == 340 || $bulk_exIn_data['exam_code'] == 3400 || $bulk_exIn_data['exam_code'] == 34000){
									$exam_code = 34;
									}elseif($bulk_exIn_data['exam_code'] == 580 || $bulk_exIn_data['exam_code'] == 5800 || $bulk_exIn_data['exam_code'] == 58000){
									$exam_code = 58;
									}elseif($bulk_exIn_data['exam_code'] == 1600 || $bulk_exIn_data['exam_code'] == 16000){
									$exam_code = 160;
									}elseif($bulk_exIn_data['exam_code'] == 200){
									$exam_code = 20;
									}elseif($bulk_exIn_data['exam_code'] == 1770 || $bulk_exIn_data['exam_code'] == 17700){
									$exam_code =177;
									}elseif ($bulk_exIn_data['exam_code'] == 590){
									$exam_code = 59;
									}elseif ($bulk_exIn_data['exam_code'] == 810){
									$exam_code = 81;
									}elseif ($bulk_exIn_data['exam_code'] == 1750){
									$exam_code = 175;
									}elseif ($bulk_exIn_data['exam_code'] == 1010 || $bulk_exIn_data['exam_code'] == 10100 || $bulk_exIn_data['exam_code'] == 101000){
									$exam_code = 101;
									}else{
									$exam_code = $bulk_exIn_data['exam_code'];
								}
								// rewrite exam period
								$exam_period = '';
								if($examination_date != '' && $examination_date != "0000-00-00")
								{
									$special_exam_period = $this->Master_model->getRecords('special_exam_dates',array('examination_date'=>$examination_date));
									if(count($special_exam_period))
									{
										$exam_period = $special_exam_period[0]['period'];	
									}
									}else{
									$exam_period = $bulk_exIn_data['exam_period'];
								}
								//EXAM_CODE|EXAM_PERIOD|INVOICE_NO|INVOICE_IMAGE|DATE_OF_INVOICE|TRANSACTION_NO|FEE_AMT|CGST_RATE|CGST_AMT|SGST_RATE|SGST_AMT|CS_TOT|IGST_RATE|IGST_AMT|IGST_TOT|QUANTITY(1/N)|CESS(0.00)|INSTITUTE_CODE|INSTITUTE_NAME|INSITITUTE_STATE_CODE|INSITITUTE_STATE_NAME|SERVICE_CODE(999294)|GSTIN_NO(15 digits)|TAX_TYPE(INTRA/INTER)|APP_TYPE(Z)|DISC_RATE|DISC_AMT|TDS_AMT|BULK_FLG(Y/N)|
								$bulk_flg = 'Y';
								$inst_addres = $pin_code = '';
								$institute_info = $this->master_model->getRecords('bulk_accerdited_master',array('institute_code'=>$bulk_exIn_data['institute_code']),'address1,address2,address3,address4,address5,address6, pin_code');
								if(count($institute_info) > 0)
								{
									if($institute_info[0]['address1'] != '') { $inst_addres .= trim($institute_info[0]['address1'])." "; }
									if($institute_info[0]['address2'] != '') { $inst_addres .= trim($institute_info[0]['address2'])." "; }
									if($institute_info[0]['address3'] != '') { $inst_addres .= trim($institute_info[0]['address3'])." "; }
									if($institute_info[0]['address4'] != '') { $inst_addres .= trim($institute_info[0]['address4'])." "; }
									if($institute_info[0]['address5'] != '') { $inst_addres .= trim($institute_info[0]['address5'])." "; }
									if($institute_info[0]['address6'] != '') { $inst_addres .= trim($institute_info[0]['address6']); }
									if($institute_info[0]['pin_code'] != '') { $pin_code = $institute_info[0]['pin_code']; }								
								}
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$bulk_exIn_data['invoice_no'].'|'.$bulk_exam_invoice_Image.'|'.$Update_date_of_invoice.'|'.$bulk_exIn_data['transaction_no'].'|'.$bulk_exIn_data['fee_amt'].'|'.$bulk_exIn_data['cgst_rate'].'|'.$bulk_exIn_data['cgst_amt'].'|'.$bulk_exIn_data['sgst_rate'].'|'.$bulk_exIn_data['sgst_amt'].'|'.$bulk_exIn_data['cs_total'].'|'.$bulk_exIn_data['igst_rate'].'|'.$bulk_exIn_data['igst_amt'].'|'.$bulk_exIn_data['igst_total'].'|'.$bulk_exIn_data['qty'].'|'.$bulk_exIn_data['cess'].'|'.$bulk_exIn_data['institute_code'].'|'.$bulk_exIn_data['institute_name'].'|'.$bulk_exIn_data['state_code'].'|'.$bulk_exIn_data['state_name'].'|'.$bulk_exIn_data['service_code'].'|'.$bulk_exIn_data['gstin_no'].'|'.$bulk_exIn_data['tax_type'].'|'.$bulk_exIn_data['app_type'].'|'.$bulk_exIn_data['disc_rate'].'|'.$bulk_exIn_data['disc_amt'].'|'.$bulk_exIn_data['tds_amt'].'|'.$bulk_flg.'|'.$pin_code.'|'.$inst_addres."\n";
								if($dir_flg)
								{
									// For photo images
									if($bulk_exam_invoice_Image)
									{
										copy("./uploads/bulkexaminvoice/supplier/".$bulk_exIn_data['invoice_image'],$directory."/".$bulk_exIn_data['invoice_image']);
										$photo_to_add = $directory."/".$bulk_exIn_data['invoice_image'];
										$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
										if(!$photo_zip_flg)
										{
											fwrite($fp1, "* ERROR * - Bulk Exam Invoice Image not added to zip  - ".$bulk_exIn_data['invoice_image']." (".$bulk_exIn_data['member_no'].")\n");	
										}
										else
										$bulk_exam_invoice_Image_cnt++;
									}
									if($photo_zip_flg)
									{
										$success['zip'] = "Bulk Exam Invoice Images Zip Generated Successfully";
									}
									else
									{
										$error['zip'] = "Error While Generating Bulk Exam Invoice Images Zip";
									}
								}
								$i++;
								$bulk_exam_invoice_count++;
								$file_w_flg = fwrite($fp, $data);
							}
							if($file_w_flg)
							{
								$success['file'] = "Bulk Exam Invoice Details File Generated Successfully. ";
							}
							else
							{
								$error['file'] = "Error While Generating Bulk Exam Invoice Details File.";
							}
						}
					}
					fwrite($fp1, "\n"."Total Bulk Exam Invoice Details Added = ".$bulk_exam_invoice_count."\n");
					fwrite($fp1, "\n"."Total Bulk Exam Invoice Images Added = ".$bulk_exam_invoice_Image_cnt."\n");
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
				$this->log_model->cronlog(" Bulk Exam Invoice Details Cron End", $desc);
				fwrite($fp1, "\n"."********** Bulk Exam Invoice Details Cron End ".$end_time." **********"."\n");
				fclose($fp1);
			}
		}
		/* Digital Elearning Invoice Cron : Bhushan 12/04/2019 */
		public function digital_elearning_invoice()
		{
			ini_set("memory_limit", "-1");
			$dir_flg = $parent_dir_flg = $file_w_flg = $photo_zip_flg = 0;
			$success = $error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd");	// default current date
			$cron_file_dir = "./uploads/cronFilesCustom/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("IIBF Digital Elearning Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date)){
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
			}
			if(file_exists($cron_file_dir.$current_date)){
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "digital_elearning_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n***** Digital Elearning Invoice Cron Started - ".$start_time." *****\n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));	// default current date
				$yesterday ='2020-11-07';
				$select = 'a.examination_date,b.id as pay_txn_id,b.receipt_no';
				//$this->db->where('DATE(a.created_on) >=', '2019-05-01');
				//$this->db->where('DATE(a.created_on) <=', '2019-05-15');
				$ref_id = array(5364320);
				$this->db->where_in('a.id', $ref_id);
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
				$can_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>18,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1),$select);
				//' DATE(a.created_on)'=>$yesterday,
				echo "SQL=>".$this->db->last_query();
				//exit;
				// ' DATE(a.created_on)'=>$yesterday,
				if(count($can_exam_data)){
					$i = 1;
					$exam_invoice_count = 0;
					$exam_invoice_Image_cnt = 0;
					$dirname = "digital_elearning_invoice_image_".$current_date;
					$directory = $cron_file_path.'/'.$dirname;
					if(file_exists($directory)){
						array_map('unlink', glob($directory."/*.*"));
						rmdir($directory);
						$dir_flg = mkdir($directory, 0700);
					}
					else{
						$dir_flg = mkdir($directory, 0700);
					}
					$zip = new ZipArchive;
					$zip->open($directory.'.zip', ZipArchive::CREATE);
					foreach($can_exam_data as $exam)
					{
						$pay_txn_id = $exam['pay_txn_id'];
						$receipt_no = $exam['receipt_no'];
						$examination_date = $exam['examination_date'];
						$this->db->where('transaction_no !=','');
						$this->db->where('app_type','L');
						$this->db->where('receipt_no',$receipt_no);
						$exam_invoice_arr = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
						if(count($exam_invoice_arr))
						{
							foreach($exam_invoice_arr as $exIn_data)
							{
								$data = '';
								$exam_invoice_Image = '';
								$date_of_invoice = $exIn_data['date_of_invoice'];
								$Update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
								if(is_file("./uploads/examinvoice/supplier/".$exIn_data['invoice_image'])){
									$exam_invoice_Image = EXAM_INVOICE_FILE_PATH.$exIn_data['invoice_image'];
								}
								else{
									fwrite($fp1, "**ERROR** - Digital Elearning Invoice does not exist  - ".$exIn_data['invoice_image']." (".$exIn_data['member_no'].")\n");	
								}
								$exam_code = $exIn_data['exam_code'];
								$exam_period = $exIn_data['exam_period'];
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$exIn_data['center_code'].'|'.$exIn_data['center_name'].'|'.$exIn_data['state_of_center'].'|'.$exIn_data['invoice_no'].'|'.$exam_invoice_Image.'|'.$exIn_data['member_no'].'|'.$Update_date_of_invoice.'|'.$exIn_data['transaction_no'].'|'.$exIn_data['fee_amt'].'|'.$exIn_data['cgst_rate'].'|'.$exIn_data['cgst_amt'].'|'.$exIn_data['sgst_rate'].'|'.$exIn_data['sgst_amt'].'|'.$exIn_data['cs_total'].'|'.$exIn_data['igst_rate'].'|'.$exIn_data['igst_amt'].'|'.$exIn_data['igst_total'].'|'.$exIn_data['qty'].'|'.$exIn_data['cess'].'|'.$exIn_data['state_code'].'|'.$exIn_data['state_name'].'|'.$exIn_data['service_code'].'|'.$exIn_data['gstin_no'].'|'.$exIn_data['tax_type'].'|'.$exIn_data['app_type']."\n";
								if($dir_flg)
								{
									if($exam_invoice_Image){
										copy("./uploads/examinvoice/supplier/".$exIn_data['invoice_image'],$directory."/".$exIn_data['invoice_image']);
										$photo_to_add = $directory."/".$exIn_data['invoice_image'];
										$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);						
										if(!$photo_zip_flg)
										{
											fwrite($fp1, "**ERROR** - Digital Elearning Invoice Image not added to zip  - ".$exIn_data['invoice_image']." (".$exIn_data['member_no'].")\n");	
										}
										else
										$exam_invoice_Image_cnt++;
									}
									if($photo_zip_flg){
										$success['zip'] = "Digital Elearning Invoice Images Zip Generated Successfully";
									}
									else{
										$error['zip'] = "Error While Generating Digital Elearning Invoice Images Zip";
									}
								}
								$i++;
								$exam_invoice_count++;
								$file_w_flg = fwrite($fp, $data);
							}
							if($file_w_flg){
								$success['file'] = "Digital Elearning Invoice Details File Generated Successfully. ";
							}
							else{
								$error['file'] = "Error While Generating Digital Elearning Invoice Details File.";
							}
						}
					}
					fwrite($fp1, "\n"."Total Digital Elearning Invoice Details Added = ".$exam_invoice_count."\n");
					fwrite($fp1, "\n"."Total Digital Elearning Invoice Images Added = ".$exam_invoice_Image_cnt."\n");
					$zip->close();
				}
				else{
					$success[] = "No data found for the date";
				}
				fclose($fp);
				// Cron End Logs
				$end_time = date("Y-m-d H:i:s");
				$result = array("success"=>$success,"error"=>$error,"Start Time"=>$start_time,"End Time"=>$end_time);
				$desc = json_encode($result);
				$this->log_model->cronlog("Digital Elearning Invoice Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."***** Digital Elearning Invoice Details Cron Execution End ".$end_time." *****"."\n");
				fclose($fp1);
			}
		}
		/* Agency Center Invoice Cron */
		public function agency_center_invoice()
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
			$cron_file_dir = "./uploads/cronFilesCustom/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("Agency Center Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date))
			{
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700);
			}
			if(file_exists($cron_file_dir.$current_date))
			{
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "agency_center_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n************************* Agency Center Invoice Details Cron Execution Started - ".$start_time." ************************* \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$transaction_no = array('6041302619701');
				$select = 'b.id as pay_txn_id,b.receipt_no';
				$this->db->where_in('b.transaction_no', $transaction_no);
				//$this->db->where('DATE(date) >=', '2019-09-01');
				//$this->db->where('DATE(date) <=', '2019-09-30');
				$this->db->join('payment_transaction b','b.ref_id=c.center_id','LEFT');
				//$agency_center_data = $this->Master_model->getRecords('agency_center c',array(' DATE(date)' => $yesterday,'pay_type' => 16,'pay_status' => 1,'status' => '1','center_add_status' => 'E'),$select);
				$agency_center_data = $this->Master_model->getRecords('agency_center c',array('pay_type' => 16,'pay_status' => '1','status' => '1','center_add_status' => 'E'),$select);
				//echo $this->db->last_query();
				if(count($agency_center_data))
				{
					$i = 1;
					$agency_center_invoice_count = 0;
					$agency_center_invoice_image_cnt = 0;
					$dirname = "agency_center_invoice_image_".$current_date;
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
					foreach($agency_center_data as $ac_reg)
					{					
						$pay_txn_id = $ac_reg['pay_txn_id'];
						$receipt_no = $ac_reg['receipt_no'];
						// get invoice details for this bankquest reg payment transaction by id and receipt_no
						$this->db->where('transaction_no !=','');
						$this->db->where('app_type','H');
						$this->db->where('receipt_no',$receipt_no);
						$agency_center_invoice_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
						//	echo "<br>SQL => ".$this->db->last_query();
						if(count($agency_center_invoice_data))
						{
							foreach($agency_center_invoice_data as $agency_center_invoice)
							{
								$data = '';
								$agency_center_invoice_image = '';
								$date_of_invoice = $agency_center_invoice['date_of_invoice'];
								$update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
								if(is_file("./uploads/drainvoice/supplier/".$agency_center_invoice['invoice_image']))
								{
									$agency_center_invoice_image = AC_INVOICE_FILE_PATH.$agency_center_invoice['invoice_image'];
								}
								else
								{
									fwrite($fp1, "**ERROR** - Agency Center Invoice does not exist  - ".$agency_center_invoice['invoice_image']."\n");	
								}
								//INVOICE_NO | INVOICE_IMAGE  | DATE_OF_INVOICE | TRANSACTION_NO | FEE_AMT | CGST_RATE | CGST_AMT | SGST_RATE | SGST_AMT | CS_TOT | IGST_RATE | IGST_AMT | IGST_TOT | QUANTITY(1)| CESS(0.00) | STATE_CODE | STATE_NAME | SERVICE_CODE(XXXXXX)| GSTIN_NO(NULL) | TAX_TYPE(INTRA/INTER) | APP_TYPE(H)
								$data .= ''.$agency_center_invoice['invoice_no'].'|'.$agency_center_invoice_image.'|'.$update_date_of_invoice.'|'.$agency_center_invoice['transaction_no'].'|'.$agency_center_invoice['fee_amt'].'|'.$agency_center_invoice['cgst_rate'].'|'.$agency_center_invoice['cgst_amt'].'|'.$agency_center_invoice['sgst_rate'].'|'.$agency_center_invoice['sgst_amt'].'|'.$agency_center_invoice['cs_total'].'|'.$agency_center_invoice['igst_rate'].'|'.$agency_center_invoice['igst_amt'].'|'.$agency_center_invoice['igst_total'].'|'.$agency_center_invoice['qty'].'|'.$agency_center_invoice['cess'].'|'.$agency_center_invoice['state_code'].'|'.$agency_center_invoice['state_name'].'|'.$agency_center_invoice['service_code'].'|'.$agency_center_invoice['gstin_no'].'|'.$agency_center_invoice['tax_type'].'|'.$agency_center_invoice['app_type'].'|'.$agency_center_invoice['institute_code']."\n";
								if($dir_flg)
								{
									// For photo images
									if($agency_center_invoice_image)
									{
										copy("./uploads/drainvoice/supplier/".$agency_center_invoice['invoice_image'],$directory."/".$agency_center_invoice['invoice_image']);
										$photo_to_add = $directory."/".$agency_center_invoice['invoice_image'];
										$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
										if(!$photo_zip_flg)
										{
											fwrite($fp1, "**ERROR** - Agency Center Invoice Image not added to zip  - ".$agency_center_invoice['invoice_image']."\n");	
										}
										else
										$agency_center_invoice_image_cnt++;
									}
									if($photo_zip_flg)
									{
										$success[] = "Agency Center Invoice Images Zip Generated Successfully";
									}
									else
									{
										$error[] = "Error While Generating Agency Center Invoice Images Zip";
									}
								}
								$i++;
								$agency_center_invoice_count++;
								$file_w_flg = fwrite($fp, $data);
							}
							if($file_w_flg)
							{
								$success[] = "Agency Center Invoice Details File Generated Successfully. ";
							}
							else
							{
								$error[] = "Error While Generating Agency Center Invoice Details File.";
							}
						}
					}
					$zip->close();
					fwrite($fp1, "\n"."Total Agency Center Invoice Details Added = ".$agency_center_invoice_count."\n");
					fwrite($fp1, "\n"."Total Agency Center Invoice Images Added = ".$agency_center_invoice_image_cnt."\n");
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
				$this->log_model->cronlog("Agency Center Invoice Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."******************** Agency Center Invoice Details Cron Execution End ".$end_time." **********************"."\n");
				fclose($fp1);
			}
		}
		/* Agency Center Renewal Invoice Cron */
		public function agn_ctr_renew_invoice()
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
			$cron_file_dir ="./uploads/cronFilesCustom/";
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
			$desc = json_encode($result);
			$this->log_model->cronlog("Agency Center Renewal Invoice Details Cron Execution Start", $desc);
			if(!file_exists($cron_file_dir.$current_date)){
				$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700);
			}
			if(file_exists($cron_file_dir.$current_date)){
				$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
				$file = "agn_ctr_renew_invoice_".$current_date.".txt";
				$fp = fopen($cron_file_path.'/'.$file, 'w');
				$file1 = "logs_".$current_date.".txt";
				$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
				fwrite($fp1, "\n******** Agency Center Renewal Invoice Details Cron Execution Started - ".$start_time." ********* \n");
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$yesterday = "2019-04-18";
				$transaction_no = array(2993676741312,6176430435125,8983460225509);
				$select = 'b.id as pay_txn_id,b.receipt_no';
				$this->db->where_in('b.transaction_no', $transaction_no);
				$this->db->join('payment_transaction b','b.ref_id = c.agency_renew_id','LEFT');
				$agn_ctr_renew_data = $this->Master_model->getRecords('agency_center_renew c',array('b.pay_type' => 17,'c.pay_status' => '1','b.status' => '1'),$select);
				echo $this->db->last_query();
				//exit;
				if(count($agn_ctr_renew_data)){
					$i = 1;
					$agn_ctr_renew_invoice_count = 0;
					$agn_ctr_renew_invoice_image_cnt = 0;
					$dirname = "agn_ctr_renew_invoice_image_".$current_date;
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
					foreach($agn_ctr_renew_data as $ac_reg)
					{					
						$pay_txn_id = $ac_reg['pay_txn_id'];
						$receipt_no = $ac_reg['receipt_no'];
						$this->db->where('transaction_no !=','');
						$this->db->where('app_type','W');
						$this->db->where('receipt_no',$receipt_no);
						$agn_ctr_renew_invoice_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
						if(count($agn_ctr_renew_invoice_data)){
							foreach($agn_ctr_renew_invoice_data as $agn_ctr_renew_invoice){
								$data = '';
								$agn_ctr_renew_image = '';
								$date_of_invoice = $agn_ctr_renew_invoice['date_of_invoice'];
								$update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
								if(is_file("./uploads/agency_renewal_invoice/supplier/".$agn_ctr_renew_invoice['invoice_image']))
								{
									$agn_ctr_renew_invoice_image = AC_RENEW_INVOICE_FILE_PATH.$agn_ctr_renew_invoice['invoice_image'];
								}
								else{
									fwrite($fp1, "**ERROR**-Agency Center Renewal Invoice does not exist - ".$agn_ctr_renew_invoice['invoice_image']."\n");	
								}
								/* INVOICE_NO | INVOICE_IMAGE  | DATE_OF_INVOICE | TRANSACTION_NO | FEE_AMT | CGST_RATE | CGST_AMT | SGST_RATE | SGST_AMT | CS_TOT | IGST_RATE | IGST_AMT | IGST_TOT | QUANTITY(1)| CESS(0.00) | STATE_CODE | STATE_NAME | SERVICE_CODE(XXXXXX)| GSTIN_NO(NULL) | TAX_TYPE(INTRA/INTER) | APP_TYPE(H) | INSTITUTE_CODE */
								// .$agency_center_invoice['institute_name'].'|'
								$data .= ''.$agn_ctr_renew_invoice['invoice_no'].'|'.$agn_ctr_renew_invoice_image.'|'.$update_date_of_invoice.'|'.$agn_ctr_renew_invoice['transaction_no'].'|'.$agn_ctr_renew_invoice['fee_amt'].'|'.$agn_ctr_renew_invoice['cgst_rate'].'|'.$agn_ctr_renew_invoice['cgst_amt'].'|'.$agn_ctr_renew_invoice['sgst_rate'].'|'.$agn_ctr_renew_invoice['sgst_amt'].'|'.$agn_ctr_renew_invoice['cs_total'].'|'.$agn_ctr_renew_invoice['igst_rate'].'|'.$agn_ctr_renew_invoice['igst_amt'].'|'.$agn_ctr_renew_invoice['igst_total'].'|'.$agn_ctr_renew_invoice['qty'].'|'.$agn_ctr_renew_invoice['cess'].'|'.$agn_ctr_renew_invoice['state_code'].'|'.$agn_ctr_renew_invoice['state_name'].'|'.$agn_ctr_renew_invoice['service_code'].'|'.$agn_ctr_renew_invoice['gstin_no'].'|'.$agn_ctr_renew_invoice['tax_type'].'|'.$agn_ctr_renew_invoice['app_type'].'|'.$agn_ctr_renew_invoice['institute_code']."\n";
								if($dir_flg){
									if($agn_ctr_renew_invoice_image){
										copy("./uploads/agency_renewal_invoice/supplier/".$agn_ctr_renew_invoice['invoice_image'],$directory."/".$agn_ctr_renew_invoice['invoice_image']);
										$photo_to_add = $directory."/".$agn_ctr_renew_invoice['invoice_image'];
										$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
										$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
										if(!$photo_zip_flg){
											fwrite($fp1, "**ERROR** - Agency Center Renewal Invoice Image not added to zip  - ".$agn_ctr_renew_invoice['invoice_image']."\n");	
										}
										else
										$agn_ctr_renew_invoice_image_cnt++;
									}
									if($photo_zip_flg){
										$success[] = "Agency Center Renewal Invoice Images Zip Generated Successfully";
									}
									else{
										$error[] = "Error While Generating Agency Center Renewal Invoice Images Zip";
									}
								}
								$i++;
								$agn_ctr_renew_invoice_count++;
								$file_w_flg = fwrite($fp, $data);
							}
							if($file_w_flg){
								$success[] = "Agency Center Renewal Invoice Details File Generated Successfully. ";
							}
							else{
								$error[] = "Error While Generating Agency Center Renewal Invoice Details File.";
							}
						}
					}
					$zip->close();
					fwrite($fp1, "\n"."Total Agency Center Renewal Invoice Details Added = ".$agn_ctr_renew_invoice_count."\n");
					fwrite($fp1, "\n"."Total Agency Center Renewal Invoice Images Added = ".$agn_ctr_renew_invoice_image_cnt."\n");
				}
				else{
					$success[] = "No data found for the date";
				}
				fclose($fp);
				// Cron End Logs
				$end_time = date("Y-m-d H:i:s");
				$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
				$desc = json_encode($result);
				$this->log_model->cronlog("Agency Center Renewal Invoice Details Cron Execution End", $desc);
				fwrite($fp1, "\n"."********Agency Center Renewal Invoice Details Cron Execution End ".$end_time." **********"."\n");
				fclose($fp1);
			}
		}
	}	