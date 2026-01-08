<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Cron_invoice_test extends CI_Controller {
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
			define('EXAM_ELEARNING_SPM_INVOICE_FILE_PATH','/fromweb/testscript/images/invoice/'); //E-learning Separate Module 
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
		}
		
		public function exam_invoice_elearning_spm()
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
		//xxx$cron_file_dir = "./uploads/invoice_cronfiles_pg/";
		//$cron_file_dir = "./uploads/rahultest/";
		$cron_file_dir = "./uploads/cronFilesCustom/";
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		//xxx$this->log_model->cronlog(" Exam E-learning Separate Module Invoice Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "exam_invoice_spm_elearning_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "invoice_spm_elearning_logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Exam E-learning Separate Module Invoice Details Cron Start - ".$start_time." ********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2023-02-25';
			//$this->db->limit(1);
			$invoice = array(903881291); 
			$select = 'pt.id as pay_txn_id, pt.receipt_no';
			$this->db->join('payment_transaction pt','pt.receipt_no = ms.receipt_no','LEFT');
			$this->db->join('spm_elearning_registration er','er.regnumber = ms.regnumber','LEFT'); 
			$this->db->join('exam_invoice a','a.receipt_no = pt.receipt_no','LEFT'); 
			$this->db->group_by('pt.receipt_no');
			//$this->db->where('DATE(ms.created_on)', $yesterday);
			//this->db->where('DATE(ms.created_on)>=', '2021-08-01');
			//$this->db->where('DATE(ms.created_on)<=', '2021-08-03');
			$this->db->where_in('a.receipt_no', $invoice);
			$can_exam_data = $this->Master_model->getRecords('spm_elearning_member_subjects ms',array('pt.pay_type'=>'20', 'pt.status'=>'1','er.isactive'=>'1'),$select);
			//echo $this->db->last_query(); exit;
			
			if(count($can_exam_data))
			{
				$i = 1;
				$exam_invoice_count = 0;
				$exam_invoice_Image_cnt = 0;
				
				$dirname = "exam_invoice_spm_elearning_image_".$current_date;
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
					
					// get invoice details for this member payment transaction by id and receipt_no
					//$this->db->where_in('invoice_no', array("EL/21-22/000704"));
					$this->db->where('transaction_no !=','');
					$this->db->where('app_type','EL');
					$this->db->where('receipt_no', $receipt_no);
					$exam_invoice_arr = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
					//echo $this->db->last_query(); exit;
					
					if(count($exam_invoice_arr))
					{
						foreach($exam_invoice_arr as $exIn_data)
						{
							$data = '';
							$exam_invoice_Image = '';
							
							$date_of_invoice = $exIn_data['date_of_invoice'];
							$Update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
							
							if(is_file("./uploads/Elearning_invoice/supplier/".$exIn_data['invoice_image']))
							{
								$exam_invoice_Image = EXAM_ELEARNING_SPM_INVOICE_FILE_PATH.$exIn_data['invoice_image'];
							}
							else
							{
								fwrite($fp1, "* ERROR * - Exam E-learning Separate Module Invoice does not exist  - ".$exIn_data['invoice_image']." (".$exIn_data['member_no'].")\n");	
							}
							
							$exam_code = $exIn_data['exam_code'];
							$exam_period = '0';
							
							$data .= ''.$exam_code.'|'.$exam_period.'|'.$exIn_data['center_code'].'|'.$exIn_data['center_name'].'|'.$exIn_data['state_of_center'].'|'.$exIn_data['invoice_no'].'|'.$exam_invoice_Image.'|'.$exIn_data['member_no'].'|'.$Update_date_of_invoice.'|'.$exIn_data['transaction_no'].'|'.$exIn_data['fee_amt'].'|'.$exIn_data['cgst_rate'].'|'.$exIn_data['cgst_amt'].'|'.$exIn_data['sgst_rate'].'|'.$exIn_data['sgst_amt'].'|'.$exIn_data['cs_total'].'|'.$exIn_data['igst_rate'].'|'.$exIn_data['igst_amt'].'|'.$exIn_data['igst_total'].'|'.$exIn_data['qty'].'|'.$exIn_data['cess'].'|'.$exIn_data['state_code'].'|'.$exIn_data['state_name'].'|'.$exIn_data['service_code'].'|'.$exIn_data['gstin_no'].'|'.$exIn_data['tax_type'].'|'.$exIn_data['app_type']."\n";
							
							if($dir_flg)
							{
								// For photo images
								if($exam_invoice_Image)
								{
									copy("./uploads/Elearning_invoice/supplier/".$exIn_data['invoice_image'],$directory."/".$exIn_data['invoice_image']);
									$photo_to_add = $directory."/".$exIn_data['invoice_image'];
									$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
									$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
									
									if(!$photo_zip_flg)
									{
										fwrite($fp1, "* ERROR * - Exam E-learning Separate Module Invoice Image not added to zip  - ".$exIn_data['invoice_image']." (".$exIn_data['member_no'].")\n");	
									}
									else { $exam_invoice_Image_cnt++; }
								}
								
								if($photo_zip_flg)
								{
									$success['zip'] = "Exam E-learning Separate Module Invoice Images Zip Generated Successfully";
								}
								else
								{
									$error['zip'] = "Error While Generating Exam E-learning Separate Module Invoice Images Zip";
								}
							}
							
							$i++;
							$exam_invoice_count++;
							
							$file_w_flg = fwrite($fp, $data);
						}
						
						if($file_w_flg)
						{
							$success['file'] = "Exam E-learning Separate Module Invoice Details File Generated Successfully. ";
						}
						else
						{
							$error['file'] = "Error While Generating Exam E-learning Separate Module Invoice Details File.";
						}
					}
				}
				
				fwrite($fp1, "\n"."Total Exam E-learning Separate Module Invoice Details Added = ".$exam_invoice_count."\n");
				fwrite($fp1, "\n"."Total Exam E-learning Separate Module Invoice Images Added = ".$exam_invoice_Image_cnt."\n");
				
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
			//xxx$this->log_model->cronlog(" Exam E-learning Separate Module Invoice Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Exam E-learning Separate Module Invoice Details Cron End ".$end_time." **********"."\n");
			fclose($fp1);
		}
	}
	
	
		/* New Credit Note Cron : Bhushan 23-10-2019 */
	public function credit_note()
	{
		//exit;    
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
		$this->log_model->cronlog("Credit Note Details Cron Start", $desc);
		
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
			fwrite($fp1, "\n********** Credit Note Cron Start - ".$start_time." ********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2021-08-03';
			$credit_note_number = array('CDN/22-23/000346','CDN/22-23/000400','CDN/22-23/000399','CDN/22-23/000398','CDN/22-23/000397','CDN/22-23/000396','CDN/22-23/000395','CDN/22-23/000394','CDN/22-23/000393','CDN/22-23/000392','CDN/22-23/000391','CDN/22-23/000390','CDN/22-23/000389','CDN/22-23/000388','CDN/22-23/000387','CDN/22-23/000386','CDN/22-23/000385','CDN/22-23/000384','CDN/22-23/000383','CDN/22-23/000382','CDN/22-23/000381','CDN/22-23/000380','CDN/22-23/000379','CDN/22-23/000378','CDN/22-23/000377','CDN/22-23/000376','CDN/22-23/000375','CDN/22-23/000374','CDN/22-23/000373','CDN/22-23/000372','CDN/22-23/000371','CDN/22-23/000370','CDN/22-23/000369','CDN/22-23/000368','CDN/22-23/000367','CDN/22-23/000366','CDN/22-23/000365','CDN/22-23/000364','CDN/22-23/000363','CDN/22-23/000362','CDN/22-23/000361','CDN/22-23/000360','CDN/22-23/000359','CDN/22-23/000358','CDN/22-23/000357','CDN/22-23/000356','CDN/22-23/000355','CDN/22-23/000354','CDN/22-23/000353','CDN/22-23/000405','CDN/22-23/000404','CDN/22-23/000403','CDN/22-23/000420','CDN/22-23/000419','CDN/22-23/000418','CDN/22-23/000411','CDN/22-23/000422','CDN/22-23/000421','CDN/22-23/000348','CDN/22-23/000347'); 
			$select = 'm.req_title,m.req_desc,m.req_member_no,m.req_module,m.transaction_no,m.req_exceptional_case,m.req_reason,m.req_maker_id,m.credit_note_image,m.refund_date,m.credit_note_date,m.credit_note_number,p.transaction_no,p.amount,p.receipt_no,p.date,a.name,m.sbi_refund_date,m.sbi_refund_modified_date,m.credit_note_gen_date'; 
			$this->db->join('administrators a','a.id  = m.req_maker_id','LEFT');
			//$this->db->join('payment_transaction p','p.transaction_no  = m.transaction_no','LEFT');
			$this->db->join('payment_transaction p','p.transaction_no  = m.transaction_no','LEFT');
			$this->db->where('credit_note_number !=', '');
			$this->db->where('credit_note_image !=', '');
			
			//$this->db->where('ARRN !=', '');
			//$this->db->where('ARRN !=', 'NA'); 
			$this->db->group_by('credit_note_number'); 
			$this->db->where_in('m.credit_note_number' ,$credit_note_number);
			$credit_note_data = $this->Master_model->getRecords('maker_checker m',array('m.req_status' => '5'),$select);
			echo $this->db->last_query(); //die; 
			//'DATE(m.credit_note_gen_date)' => $yesterday,
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
							$date_of_invoice =date('d-M-y',strtotime($invoice_row['date_of_invoice']));
							
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
							$refund_request_id = '';//$row['refund_request_id'];
							$ARRN = '';//$row['ARRN'];
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
	
		/* New Credit Note Cron : Bhushan 23-10-2019 */
	public function credit_note_bulk()
	{
		//exit;    
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
		$this->log_model->cronlog("Credit Note Details Cron Start", $desc);
		
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
			fwrite($fp1, "\n********** Credit Note Cron Start - ".$start_time." ********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			$yesterday = '2021-07-30';
			$credit_note_number = array('CDN/22-23/000074','CDN/22-23/000075'); 
			$select = 'm.req_title,m.req_desc,m.req_member_no,m.req_module,m.transaction_no,m.req_exceptional_case,m.req_reason,m.req_maker_id,m.credit_note_image,m.refund_date,m.credit_note_date,m.credit_note_number,m.transaction_no,p.amount,p.receipt_no,p.date,a.name,m.sbi_refund_date,m.sbi_refund_modified_date,m.credit_note_gen_date'; 
			$this->db->join('administrators a','a.id  = m.req_maker_id','LEFT');
			//$this->db->join('payment_transaction p','p.transaction_no  = m.transaction_no','LEFT');
			//$this->db->join('bulk_payment_transaction p','p.UTR_no  = m.transaction_no','LEFT');
			$this->db->join('dra_payment_transaction p','p.UTR_no  = m.transaction_no','LEFT');
			$this->db->where('credit_note_number !=', '');
			$this->db->where('credit_note_image !=', ''); 
			
			//$this->db->where('ARRN !=', '');
			//$this->db->where('ARRN !=', 'NA');
			$this->db->where_in('m.credit_note_number' ,$credit_note_number);
			$credit_note_data = $this->Master_model->getRecords('maker_checker m',array('m.req_status' => '5'),$select);
			//echo $this->db->last_query(); //die; 
			//'DATE(m.credit_note_gen_date)' => $yesterday,
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
					 echo $this->db->last_query();
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
							$date_of_invoice =date('d-M-y',strtotime($invoice_row['date_of_invoice']));
							
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
							$refund_request_id = '';//$row['refund_request_id'];
							$ARRN = '';//$row['ARRN'];
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
	
		/*New Credit Note Cron : Bhushan 18-10-2019 */
		public function credit_note_old()
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
				$credit_note_number = array('CDN/20-21/02122','CDN/20-21/02115','CDN/20-21/02109','CDN/20-21/02118','CDN/20-21/02119','CDN/20-21/02112','CDN/20-21/02111','CDN/20-21/02123','CDN/20-21/02108','CDN/20-21/02114','CDN/20-21/02121','CDN/20-21/02107','CDN/20-21/02106','CDN/20-21/02110','CDN/20-21/02117','CDN/20-21/02120','CDN/20-21/02116','CDN/20-21/02113','CDN/20-21/02037','CDN/20-21/02105','CDN/20-21/02086','CDN/20-21/02057','CDN/20-21/02096','CDN/20-21/02007','CDN/20-21/02044','CDN/20-21/02082','CDN/20-21/02068','CDN/20-21/02104','CDN/20-21/02091','CDN/20-21/02069','CDN/20-21/02103','CDN/20-21/02016','CDN/20-21/02089','CDN/20-21/02094','CDN/20-21/02084','CDN/20-21/02028','CDN/20-21/02004','CDN/20-21/02001','CDN/20-21/02003','CDN/20-21/02045','CDN/20-21/01992','CDN/20-21/02029','CDN/20-21/02059','CDN/20-21/02047','CDN/20-21/02038','CDN/20-21/02101','CDN/20-21/02097','CDN/20-21/02063','CDN/20-21/01997','CDN/20-21/02009','CDN/20-21/02041','CDN/20-21/01984','CDN/20-21/02085','CDN/20-21/02051','CDN/20-21/02095','CDN/20-21/01985','CDN/20-21/01986','CDN/20-21/02099','CDN/20-21/02072','CDN/20-21/02017','CDN/20-21/02035','CDN/20-21/02052','CDN/20-21/02081','CDN/20-21/01989','CDN/20-21/02061','CDN/20-21/01983','CDN/20-21/02087','CDN/20-21/01993','CDN/20-21/02019','CDN/20-21/02071','CDN/20-21/02100','CDN/20-21/02050','CDN/20-21/02078','CDN/20-21/02034','CDN/20-21/02065','CDN/20-21/01990','CDN/20-21/02023','CDN/20-21/02083','CDN/20-21/02020','CDN/20-21/02080','CDN/20-21/02055','CDN/20-21/02049','CDN/20-21/02031','CDN/20-21/02014','CDN/20-21/02073','CDN/20-21/02025','CDN/20-21/02060','CDN/20-21/01987','CDN/20-21/02039','CDN/20-21/02064','CDN/20-21/02054','CDN/20-21/02008','CDN/20-21/02011','CDN/20-21/02090','CDN/20-21/02077','CDN/20-21/02030','CDN/20-21/02092','CDN/20-21/02046','CDN/20-21/01988','CDN/20-21/02012','CDN/20-21/02070','CDN/20-21/02022','CDN/20-21/02088','CDN/20-21/02075','CDN/20-21/02013','CDN/20-21/02076','CDN/20-21/02066','CDN/20-21/02006','CDN/20-21/02005','CDN/20-21/02000','CDN/20-21/02062','CDN/20-21/02040','CDN/20-21/01996','CDN/20-21/02032','CDN/20-21/02043','CDN/20-21/02093','CDN/20-21/01991','CDN/20-21/02036','CDN/20-21/02042','CDN/20-21/02067','CDN/20-21/02056','CDN/20-21/02033','CDN/20-21/02015','CDN/20-21/02074','CDN/20-21/02098','CDN/20-21/01994','CDN/20-21/02079','CDN/20-21/02058','CDN/20-21/02102','CDN/20-21/01995','CDN/20-21/01999','CDN/20-21/02010','CDN/20-21/02002','CDN/20-21/02027','CDN/20-21/02053','CDN/20-21/02048','CDN/20-21/02018','CDN/20-21/02024','CDN/20-21/01998');
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
				//$yesterday = '2023-02-25'; // temp
				$mem = array(7879831,7879842,7879854);
				$this->db->where_in('a.regid', $mem);
				$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0, 'is_renewal' => 0));
				//$new_mem_reg = $this->Master_model->getRecords(' DATE(createdon)'=>$yesterday,'payment_transaction a',array('status'=>'1','pay_type'=>1));
				//' DATE(createdon)'=>$yesterday,
				echo $this->db->last_query();
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
								echo '<br>'.$this->db->last_query(); 
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
				$mem_invoice = array('M/22-23/005768','M/22-23/005767','M/22-23/005769','M/22-23/005770','M/22-23/005771','M/22-23/005772','M/22-23/005773','M/22-23/005774','M/22-23/005796','M/22-23/005775','M/22-23/005776','M/22-23/005777','M/22-23/005778','M/22-23/005779','M/22-23/005780','M/22-23/005781','M/22-23/005782','M/22-23/005783','M/22-23/005784','M/22-23/005785','M/22-23/005786','M/22-23/005787','M/22-23/005788','M/22-23/005789','M/22-23/005790','M/22-23/005791','M/22-23/005792','M/22-23/005793','M/22-23/005794','M/22-23/005795','M/22-23/005797','M/22-23/005798','M/22-23/005799','M/22-23/005801','M/22-23/005800','M/22-23/005802','M/22-23/005803','M/22-23/005805','M/22-23/005804','M/22-23/005806','M/22-23/005807','M/22-23/005808','M/22-23/005809','M/22-23/005810','M/22-23/005811','M/22-23/005812','M/22-23/005813','M/22-23/005814','M/22-23/005815','M/22-23/005816','M/22-23/005817','M/22-23/005818','M/22-23/005820','M/22-23/005819','M/22-23/005821','M/22-23/005822','M/22-23/005824','M/22-23/005823','M/22-23/005825','M/22-23/005826','M/22-23/005827','M/22-23/005828','M/22-23/005829','M/22-23/005830','M/22-23/005831','M/22-23/005832','M/22-23/005833','M/22-23/005834','M/22-23/005835','M/22-23/005836','M/22-23/005837','M/22-23/005838','M/22-23/005839','M/22-23/005840','M/22-23/005841','M/22-23/005842','M/22-23/005843','M/22-23/005845','M/22-23/005844','M/22-23/005846','M/22-23/005847','M/22-23/005875','M/22-23/005848','M/22-23/005849','M/22-23/005850','M/22-23/005853','M/22-23/005851','M/22-23/005852','M/22-23/005854','M/22-23/005856','M/22-23/005855','M/22-23/005857','M/22-23/005858','M/22-23/005859','M/22-23/005860','M/22-23/005861','M/22-23/005863','M/22-23/005862','M/22-23/005864','M/22-23/005866','M/22-23/005865','M/22-23/005867','M/22-23/005868','M/22-23/005869','M/22-23/005870','M/22-23/005872','M/22-23/005871','M/22-23/005876','M/22-23/005873','M/22-23/005874','M/22-23/005878','M/22-23/005877','M/22-23/005879','M/22-23/005880','M/22-23/005881','M/22-23/005883','M/22-23/005882','M/22-23/005884','M/22-23/005887','M/22-23/005888','M/22-23/005885','M/22-23/005886','M/22-23/005889','M/22-23/005890','M/22-23/005891','M/22-23/005892','M/22-23/005895','M/22-23/005893','M/22-23/005896','M/22-23/005897','M/22-23/005894','M/22-23/005898','M/22-23/005899','M/22-23/005900','M/22-23/005901','M/22-23/005902','M/22-23/005905','M/22-23/005906','M/22-23/005903','M/22-23/005904','M/22-23/005907','M/22-23/005909','M/22-23/005908','M/22-23/005912','M/22-23/005913','M/22-23/005910','M/22-23/005911','M/22-23/005915','M/22-23/005914','M/22-23/005921','M/22-23/005916','M/22-23/005917','M/22-23/005918','M/22-23/005919','M/22-23/005920','M/22-23/005922','M/22-23/005923','M/22-23/005924','M/22-23/005926','M/22-23/005928','M/22-23/005925','M/22-23/005929','M/22-23/005927','M/22-23/005931','M/22-23/005930','M/22-23/005933','M/22-23/005932','M/22-23/005934','M/22-23/005935','M/22-23/005941','M/22-23/005936','M/22-23/005937','M/22-23/005939','M/22-23/005940','M/22-23/005938','M/22-23/005942','M/22-23/005943','M/22-23/005944','M/22-23/005945','M/22-23/005947','M/22-23/005946','M/22-23/005948','M/22-23/005950','M/22-23/005949','M/22-23/005957','M/22-23/005951','M/22-23/005953','M/22-23/005954','M/22-23/005952','M/22-23/005956','M/22-23/005955','M/22-23/005959','M/22-23/005958','M/22-23/005960','M/22-23/005961','M/22-23/005962','M/22-23/005963','M/22-23/005964','M/22-23/005966','M/22-23/005967','M/22-23/005965','M/22-23/005971','M/22-23/005968','M/22-23/005970','M/22-23/005972','M/22-23/005969','M/22-23/005973','M/22-23/005975','M/22-23/005974','M/22-23/005976','M/22-23/005979','M/22-23/005977','M/22-23/005981','M/22-23/005978','M/22-23/005980','M/22-23/005982','M/22-23/005984','M/22-23/005983','M/22-23/005986','M/22-23/005985','M/22-23/005988','M/22-23/005987','M/22-23/005989','M/22-23/005990','M/22-23/005991','M/22-23/005992','M/22-23/005994','M/22-23/005993','M/22-23/005995','M/22-23/005996','M/22-23/005997','M/22-23/005998','M/22-23/005999','M/22-23/006000','M/22-23/006001','M/22-23/006003','M/22-23/006002','M/22-23/006004','M/22-23/006006','M/22-23/006005','M/22-23/006007','M/22-23/006008','M/22-23/006009','M/22-23/006013','M/22-23/006011','M/22-23/006021','M/22-23/006010','M/22-23/006016','M/22-23/006012','M/22-23/006014','M/22-23/006015','M/22-23/006020','M/22-23/006018','M/22-23/006017','M/22-23/006019','M/22-23/006022','M/22-23/006023','M/22-23/006024','M/22-23/006025','M/22-23/006026','M/22-23/006027','M/22-23/006029','M/22-23/006028','M/22-23/006030','M/22-23/006031','M/22-23/006032','M/22-23/006033','M/22-23/006034','M/22-23/006036','M/22-23/006035','M/22-23/006038','M/22-23/006037','M/22-23/006039','M/22-23/006041','M/22-23/006040','M/22-23/006042','M/22-23/006043','M/22-23/006044','M/22-23/006045','M/22-23/006047','M/22-23/006046','M/22-23/006048','M/22-23/006049','M/22-23/006050','M/22-23/006051','M/22-23/006052','M/22-23/006053','M/22-23/006057','M/22-23/006054','M/22-23/006059','M/22-23/006055','M/22-23/006056','M/22-23/006058','M/22-23/006067','M/22-23/006060','M/22-23/006062','M/22-23/006061','M/22-23/006064','M/22-23/006063','M/22-23/006065','M/22-23/006068','M/22-23/006066','M/22-23/006071','M/22-23/006069','M/22-23/006070','M/22-23/006072','M/22-23/006073','M/22-23/006074','M/22-23/006076','M/22-23/006075','M/22-23/006078','M/22-23/006077','M/22-23/006079','M/22-23/006084','M/22-23/006082','M/22-23/006080','M/22-23/006081','M/22-23/006083','M/22-23/006088','M/22-23/006087','M/22-23/006086','M/22-23/006085','M/22-23/006089','M/22-23/006092','M/22-23/006090','M/22-23/006100','M/22-23/006091','M/22-23/006093','M/22-23/006099','M/22-23/006097','M/22-23/006094','M/22-23/006095','M/22-23/006098','M/22-23/006096','M/22-23/006101','M/22-23/006102','M/22-23/006106','M/22-23/006103','M/22-23/006104','M/22-23/006105','M/22-23/006110','M/22-23/006107','M/22-23/006108','M/22-23/006109','M/22-23/006111','M/22-23/006112','M/22-23/006113','M/22-23/006114','M/22-23/006117','M/22-23/006115','M/22-23/006116','M/22-23/006119','M/22-23/006120','M/22-23/006118','M/22-23/006121','M/22-23/006122','M/22-23/006124','M/22-23/006127','M/22-23/006123','M/22-23/006125','M/22-23/006131','M/22-23/006126','M/22-23/006128','M/22-23/006129','M/22-23/006130','M/22-23/006135','M/22-23/006133','M/22-23/006137','M/22-23/006132','M/22-23/006134','M/22-23/006140','M/22-23/006136','M/22-23/006138','M/22-23/006139','M/22-23/006143','M/22-23/006142','M/22-23/006141','M/22-23/006144','M/22-23/006147','M/22-23/006145','M/22-23/006148','M/22-23/006149','M/22-23/006146','M/22-23/006150','M/22-23/006151','M/22-23/006153','M/22-23/006154','M/22-23/006152','M/22-23/006159','M/22-23/006155','M/22-23/006157','M/22-23/006156','M/22-23/006158','M/22-23/006160','M/22-23/006161','M/22-23/006162','M/22-23/006163','M/22-23/006165','M/22-23/006164','M/22-23/006166','M/22-23/006167','M/22-23/006168','M/22-23/006169','M/22-23/006170','M/22-23/006171','M/22-23/006173','M/22-23/006172','M/22-23/006174','M/22-23/006176','M/22-23/006175','M/22-23/006177','M/22-23/006178','M/22-23/006179','M/22-23/006180','M/22-23/006181','M/22-23/006182','M/22-23/006184','M/22-23/006183','M/22-23/006185','M/22-23/006186','M/22-23/006187','M/22-23/006188','M/22-23/006190','M/22-23/006191','M/22-23/006189','M/22-23/006195','M/22-23/006193','M/22-23/006192','M/22-23/006194','M/22-23/006197','M/22-23/006196','M/22-23/006198','M/22-23/006200','M/22-23/006199','M/22-23/006201','M/22-23/006202','M/22-23/006206','M/22-23/006203','M/22-23/006205','M/22-23/006207','M/22-23/006208','M/22-23/006204','M/22-23/006209','M/22-23/006210');
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
								echo $this->db->last_query();
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
		public function exam_invoice_old()
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
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$yesterday = '2021-03-20'; // temp
				//$exam_code=array('1016');
				//$this->db->where_in('a.exam_code', $exam_code);
				//$this->db->where('a.exam_period', '573');
				//$this->db->where_not_in('a.regnumber', $regnumber);
				//$this->db->where('DATE(a.created_on) >=', '2021-02-01');
				//$this->db->where('DATE(a.created_on) <=', '2021-02-25');
				//$this->db->where_not_in('a.id', $ref_id);
				//$regnumber = array();
				$select = 'a.examination_date,b.id as pay_txn_id,b.receipt_no,a.elearning_flag';
				$ref_id = array(6072755,5970423,5970439,6028814);
				//$recipte_no = array('902138434','902138474');
				$this->db->where_in('a.id', $ref_id);
				//$this->db->like('b.date', '2021-02-');
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				//$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT');
				$can_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'pay_status'=>1),$select);				//,'isactive'=>'1','isdeleted'=>0
				//'DATE(a.created_on)'=>$yesterday,
				//echo $this->db->last_query();
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
									}elseif ($exIn_data['exam_code'] == 2027){
									$exam_code = 1017;
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
								// code added by chaitali
								if(($exam_code == 21 || $exam_code == 42 || $exam_code == 992) && ($exam['elearning_flag'] == 'Y'))
							{
							if($exIn_data['state_code'] == 27) //Maharshtra
							{
								$exam_fee = ($exIn_data['fee_amt']+$exIn_data['total_el_base_amount']);
								$cgst     = $exIn_data['cgst_amt'] +  $exIn_data['total_el_gst_amount'] / 2;
								$sgst     = $exIn_data['sgst_amt'] +  $exIn_data['total_el_gst_amount'] / 2;
								$igst     =  '0.00';
								
							}else{
								$exam_fee = ($exIn_data['fee_amt']+$exIn_data['total_el_base_amount']);
								$igst     = $exIn_data['igst_amt'] + $exIn_data['total_el_gst_amount'];
								$cgst = '0.00';
								$sgst = '0.00';
							}
							
							
							 $data .= ''.$exam_code.'|'.$exam_period.'|'.$exIn_data['center_code'].'|'.$exIn_data['center_name'].'|'.$exIn_data['state_of_center'].'|'.$exIn_data['invoice_no'].'|'.$exam_invoice_Image.'|'.$exIn_data['member_no'].'|'.$Update_date_of_invoice.'|'.$exIn_data['transaction_no'].'|'.$exam_fee.'|'.$exIn_data['cgst_rate'].'|'.$cgst .'|'.$exIn_data['sgst_rate'].'|'.$sgst .'|'.$exIn_data['cs_total'].'|'.$exIn_data['igst_rate'].'|'.$igst.'|'.$exIn_data['igst_total'].'|'.$exIn_data['qty'].'|'.$exIn_data['cess'].'|'.$exIn_data['state_code'].'|'.$exIn_data['state_name'].'|'.$exIn_data['service_code'].'|'.$gstin_no.'|'.$exIn_data['tax_type'].'|'.$exIn_data['app_type']."\n";
						}
						else{
								 $data .= ''.$exam_code.'|'.$exam_period.'|'.$exIn_data['center_code'].'|'.$exIn_data['center_name'].'|'.$exIn_data['state_of_center'].'|'.$exIn_data['invoice_no'].'|'.$exam_invoice_Image.'|'.$exIn_data['member_no'].'|'.$Update_date_of_invoice.'|'.$exIn_data['transaction_no'].'|'.$exIn_data['fee_amt'].'|'.$exIn_data['cgst_rate'].'|'.$exIn_data['cgst_amt'].'|'.$exIn_data['sgst_rate'].'|'.$exIn_data['sgst_amt'].'|'.$exIn_data['cs_total'].'|'.$exIn_data['igst_rate'].'|'.$exIn_data['igst_amt'].'|'.$exIn_data['igst_total'].'|'.$exIn_data['qty'].'|'.$exIn_data['cess'].'|'.$exIn_data['state_code'].'|'.$exIn_data['state_name'].'|'.$exIn_data['service_code'].'|'.$gstin_no.'|'.$exIn_data['tax_type'].'|'.$exIn_data['app_type']."\n";
						}
						
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
		$this->log_model->cronlog(" Exam Invoice Details Cron Execution Start", $desc);
		
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
			fwrite($fp1, "\n********** Exam Invoice Details Cron Start - ".$start_time." ********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			//$yesterday = '2023-02-25';
			
			/* $not_exam_codes = array('1018');
			$this->db->where_not_in('a.exam_code',$not_exam_codes); */
			
			// get invoice details for this member exam application payment transaction by id and receipt_no
			$ref_id = array(7553077,7562411,7562410,7571592,7571574,7571687,7571770);
			$select = 'a.examination_date,b.id as pay_txn_id,b.receipt_no, a.exam_code,a.elearning_flag';
			$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
			$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT');  
			$this->db->where_in('a.id', $ref_id);
			$can_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1),$select); 
			//' DATE(a.created_on)'=>$yesterday,
			echo $this->db->last_query();
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
					$chk_exam_code = $exam['exam_code'];
					
					$examination_date = $exam['examination_date'];
					
					// get invoice details for this member payment transaction by id and receipt_no
					$this->db->where('transaction_no !=','');
					$this->db->where('app_type','O');
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
							
							if($chk_exam_code == '1018')//FOR GARP INVOICE PATH IS DIFFERENT
							{
								$chk_img_path = "./uploads/garpinvoice/supplier/";
							}
							else
							{
								$chk_img_path = "./uploads/examinvoice/supplier/";
							}
							
							if(is_file($chk_img_path.$exIn_data['invoice_image']))
							{
								$exam_invoice_Image = EXAM_INVOICE_FILE_PATH.$exIn_data['invoice_image']; 
							}
							else
							{
								fwrite($fp1, "* ERROR * - Exam Invoice does not exist  - ".$exIn_data['invoice_image']." (".$exIn_data['member_no'].")\n");	
							}
							
							$exam_code = '';
							if($exIn_data['exam_code'] != '' && $exIn_data['exam_code'] != 0)
							{
								$ex_code = $this->master_model->getRecords('exam_activation_master',array('exam_code'=>$exIn_data['exam_code']));
								if(count($ex_code))
								{
									if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0)
									{	
										$exam_code = $ex_code[0]['original_val'];	
									}
									else
									{	
										$exam_code = $exIn_data['exam_code'];	
									}
								}
								else
								{	
									$exam_code = $exIn_data['exam_code'];	
								}
							}
							else{	$exam_code = $exIn_data['exam_code'];	}
							
							
							// rewrite exam period
							$exam_period = '';
							if($examination_date != '' && $examination_date != "0000-00-00")
							{
								$special_exam_period = $this->Master_model->getRecords('special_exam_dates',array('examination_date'=>$examination_date));
								if(count($special_exam_period))
								{
									$exam_period = $special_exam_period[0]['period'];	
								}
							}
							else{
								$exam_period = $exIn_data['exam_period'];
							}
							// eof code added by Bhagwan Sahane, on 18-08-2017
							if(($exam_code == $this->config->item('examCodeJaiib') || $exam_code == $this->config->item('examCodeDBF') || $exam_code == $this->config->item('examCodeSOB') || $exam_code == $this->config->item('examCodeCaiib') || $exam_code == 65) && ($exam['elearning_flag'] == 'Y'))
							{
								if($exIn_data['state_code'] == 27) //Maharshtra
								{
									$exam_fee = ($exIn_data['fee_amt']+$exIn_data['total_el_base_amount']);
									$cgst     = $exIn_data['cgst_amt'] +  $exIn_data['total_el_gst_amount'] / 2;
									$sgst     = $exIn_data['sgst_amt'] +  $exIn_data['total_el_gst_amount'] / 2;
									$igst     =  '0.00';
									
								}
								else{ 
									$exam_fee = ($exIn_data['fee_amt']+$exIn_data['total_el_base_amount']);
									$igst     = $exIn_data['igst_amt'] + $exIn_data['total_el_gst_amount'];
									$cgst = '0.00';
									$sgst = '0.00';
								}
								$data .= ''.$exam_code.'|'.$exam_period.'|'.$exIn_data['center_code'].'|'.$exIn_data['center_name'].'|'.$exIn_data['state_of_center'].'|'.$exIn_data['invoice_no'].'|'.$exam_invoice_Image.'|'.$exIn_data['member_no'].'|'.$Update_date_of_invoice.'|'.$exIn_data['transaction_no'].'|'.$exam_fee.'|'.$exIn_data['cgst_rate'].'|'.$cgst .'|'.$exIn_data['sgst_rate'].'|'.$sgst .'|'.$exIn_data['cs_total'].'|'.$exIn_data['igst_rate'].'|'.$igst.'|'.$exIn_data['igst_total'].'|'.$exIn_data['qty'].'|'.$exIn_data['cess'].'|'.$exIn_data['state_code'].'|'.$exIn_data['state_name'].'|'.$exIn_data['service_code'].'|'.$gstin_no.'|'.$exIn_data['tax_type'].'|'.$exIn_data['app_type']."\n";
							}
						else{
							$data .= ''.$exam_code.'|'.$exam_period.'|'.$exIn_data['center_code'].'|'.$exIn_data['center_name'].'|'.$exIn_data['state_of_center'].'|'.$exIn_data['invoice_no'].'|'.$exam_invoice_Image.'|'.$exIn_data['member_no'].'|'.$Update_date_of_invoice.'|'.$exIn_data['transaction_no'].'|'.$exIn_data['fee_amt'].'|'.$exIn_data['cgst_rate'].'|'.$exIn_data['cgst_amt'].'|'.$exIn_data['sgst_rate'].'|'.$exIn_data['sgst_amt'].'|'.$exIn_data['cs_total'].'|'.$exIn_data['igst_rate'].'|'.$exIn_data['igst_amt'].'|'.$exIn_data['igst_total'].'|'.$exIn_data['qty'].'|'.$exIn_data['cess'].'|'.$exIn_data['state_code'].'|'.$exIn_data['state_name'].'|'.$exIn_data['service_code'].'|'.$exIn_data['gstin_no'].'|'.$exIn_data['tax_type'].'|'.$exIn_data['app_type']."\n";
						}
							if($dir_flg)
							{
								// For photo images
								if($exam_invoice_Image)
								{
									
									copy($chk_img_path.$exIn_data['invoice_image'],$directory."/".$exIn_data['invoice_image']);
									$photo_to_add = $directory."/".$exIn_data['invoice_image'];
									$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
									$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
									
									if(!$photo_zip_flg)
									{
										fwrite($fp1, "* ERROR * - Exam Invoice Image not added to zip  - ".$exIn_data['invoice_image']." (".$exIn_data['member_no'].")\n");	
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
			$this->log_model->cronlog(" Exam Invoice Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Exam Invoice Details Cron End ".$end_time." **********"."\n");
			fclose($fp1);
		}
	}
	
			
			public function exam_invoice_garp()
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
				$yesterday = date('Y-m-d', strtotime("- 1 day"));
				$yesterday = '2021-03-20'; // temp
				//$exam_code=array('1016');
				//$this->db->where_in('a.exam_code', $exam_code);
				//$this->db->where('a.exam_period', '573');
				//$this->db->where_not_in('a.regnumber', $regnumber);
				//$this->db->where('DATE(a.created_on) >=', '2021-02-01');
				//$this->db->where('DATE(a.created_on) <=', '2021-02-25');
				//$this->db->where_not_in('a.id', $ref_id);
				//$regnumber = array();
				$select = 'a.examination_date,b.id as pay_txn_id,b.receipt_no';
				$ref_id = array(5947219,5946958,5946818,5946756,5946754,5945995,5945248,5947886);
				//$recipte_no = array('902138434','902138474');
				$this->db->where_in('a.id', $ref_id);
				//$this->db->like('b.date', '2021-02-');
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				//$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT');
				$can_exam_data = $this->Master_model->getRecords('member_exam a',array('pay_type'=>2,'status'=>1,'pay_status'=>1),$select);				//,'isactive'=>'1','isdeleted'=>0
				//'DATE(a.created_on)'=>$yesterday,
				echo $this->db->last_query();
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
								if(is_file("./uploads/garpinvoice/supplier/".$exIn_data['invoice_image']))
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
									}elseif ($exIn_data['exam_code'] == 2027){
									$exam_code = 1017;
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
										copy("./uploads/garpinvoice/supplier/".$exIn_data['invoice_image'],$directory."/".$exIn_data['invoice_image']);
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
		//START : CREATED BY SAGAR AND SWATI ON 18-02-2021 FOR AMP INVOICES
	public function exam_invoice_amp() 
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
		//$cron_file_dir = "./uploads/rahultest/";
		
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");  
		$desc = json_encode($result);
		$this->log_model->cronlog(" AMP Exam Invoice Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date)) { $parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); }
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$file = "exam_invoice_amp_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** AMP Exam Invoice Details Cron Start - ".$start_time." ********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));//'2021-12-11'; //
			//$yesterday = '2021-12-11'; 
			$pay_tax_id= array(743,745,746);
			

			$this->db->join('amp_payment_transaction ap', 'ap.id = ei.pay_txn_id', 'INNER', TRUE);
			$this->db->where_in('id',$pay_tax_id);
			$exam_invoice_data = $this->Master_model->getRecords('exam_invoice ei',array('ei.transaction_no != '=>'', 'ei.app_type'=>'M', 'ei.exam_code'=>'0', 'ei.exam_period'=>'0',  'ap.status'=>'1'), 'ei.invoice_id, ei.exam_code, ei.exam_period, ei.center_code, ei.center_name, ei.state_of_center, ei.member_no, ei.pay_txn_id, ei.receipt_no, ei.transaction_no, ei.date_of_invoice, ei.invoice_no, ei.invoice_image, ei.fee_amt, ei.cgst_rate, ei.cgst_amt, ei.sgst_rate, ei.sgst_amt, ei.cs_total, ei.igst_rate, ei.igst_amt, ei.igst_total, ei.qty, ei.cess, ei.state_code, ei.state_name, ei.service_code, ei.gstin_no, ei.tax_type, ei.app_type');
			echo '<br><b>AMP Exam Invoice Qry : </b><br>'.$this->db->last_query(0); //exit;
			//'DATE(ei.date_of_invoice)'=>$yesterday,
			$exam_invoice_count = 0;
			$exam_invoice_Image_cnt = 0;
			if(count($exam_invoice_data))
			{
				$i = 1;
				
				$dirname = "amp_exam_invoice_image_".$current_date;
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
				
				foreach($exam_invoice_data as $exam_invoice_res)
				{
					$pay_txn_id = $exam_invoice_res['pay_txn_id'];
					$receipt_no = $exam_invoice_res['receipt_no'];
					$chk_exam_code = $exam_invoice_res['exam_code'];
					
					$examination_date = ''; //$exam['examination_date'];
					
					$data = '';
					$exam_invoice_Image = '';
					
					$date_of_invoice = $exam_invoice_res['date_of_invoice'];
					$Update_date_of_invoice = date('d-M-y',strtotime($date_of_invoice));
							
					$chk_img_path = "./uploads/ampinvoice/supplier/";
							
					if(is_file($chk_img_path.$exam_invoice_res['invoice_image']))
					{
						$exam_invoice_Image = EXAM_INVOICE_FILE_PATH.$exam_invoice_res['invoice_image']; 
					}
					else
					{
						fwrite($fp1, "* ERROR * - AMP Exam Invoice does not exist  - ".$exam_invoice_res['invoice_image']." (".$exam_invoice_res['member_no'].")\n");	
					}
							
					$exam_code = $exam_invoice_res['exam_code'];						
					$exam_period = $exam_invoice_res['exam_period'];
					
					$data .= ''.$exam_code.'|'.$exam_period.'|'.$exam_invoice_res['center_code'].'|'.$exam_invoice_res['center_name'].'|'.$exam_invoice_res['state_of_center'].'|'.$exam_invoice_res['invoice_no'].'|'.$exam_invoice_Image.'|'.$exam_invoice_res['member_no'].'|'.$Update_date_of_invoice.'|'.$exam_invoice_res['transaction_no'].'|'.$exam_invoice_res['fee_amt'].'|'.$exam_invoice_res['cgst_rate'].'|'.$exam_invoice_res['cgst_amt'].'|'.$exam_invoice_res['sgst_rate'].'|'.$exam_invoice_res['sgst_amt'].'|'.$exam_invoice_res['cs_total'].'|'.$exam_invoice_res['igst_rate'].'|'.$exam_invoice_res['igst_amt'].'|'.$exam_invoice_res['igst_total'].'|'.$exam_invoice_res['qty'].'|'.$exam_invoice_res['cess'].'|'.$exam_invoice_res['state_code'].'|'.$exam_invoice_res['state_name'].'|'.$exam_invoice_res['service_code'].'|'.$exam_invoice_res['gstin_no'].'|'.$exam_invoice_res['tax_type'].'|'.$exam_invoice_res['app_type']."\n";
													
					if($dir_flg)
					{
						// For photo images
						if($exam_invoice_Image)
						{
							copy($chk_img_path.$exam_invoice_res['invoice_image'],$directory."/".$exam_invoice_res['invoice_image']);
							$photo_to_add = $directory."/".$exam_invoice_res['invoice_image'];
							$new_photo = substr($photo_to_add,strrpos($photo_to_add,'/') + 1);
							$photo_zip_flg = $zip->addFile($photo_to_add,$new_photo);
							
							if(!$photo_zip_flg)
							{
								fwrite($fp1, "* ERROR * - AMP Exam Invoice Image not added to zip  - ".$exam_invoice_res['invoice_image']." (".$exam_invoice_res['member_no'].")\n");	
							}
							else
							{
								$exam_invoice_Image_cnt++;
							}
						}
								
						if($photo_zip_flg)
						{
							$success['zip'] = "AMP Exam Invoice Images Zip Generated Successfully";
						}
						else
						{
							$error['zip'] = "Error While Generating AMP Exam Invoice Images Zip";
						}
					}
							
					$i++;
					$exam_invoice_count++;
							
					$file_w_flg = fwrite($fp, $data);
				}
						
				if($file_w_flg)
				{
					$success['file'] = "AMP Exam Invoice Details File Generated Successfully. ";
				}
				else
				{
					$error['file'] = "Error While Generating AMP Exam Invoice Details File.";
				}
				$zip->close();
			}
			else
			{
				$success[] = "No data found for the date";
			}
			
			fwrite($fp1, "\n"."Total AMP Exam Invoice Details Added = ".$exam_invoice_count."\n");
			fwrite($fp1, "\n"."Total AMP Exam Invoice Images Added = ".$exam_invoice_Image_cnt."\n");
		}
		fclose($fp);
			
		// Cron End Logs
		$end_time = date("Y-m-d H:i:s");
		$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
		$desc = json_encode($result);
		$this->log_model->cronlog(" AMP Exam Invoice Details Cron End", $desc);
			
		fwrite($fp1, "\n"."********** AMP Exam Invoice Details Cron End ".$end_time." **********"."\n");
		fclose($fp1);
	}//START : CREATED BY SAGAR AND SWATI ON 18-02-2021 FOR AMP INVOICES
		
	
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
				//$yesterday = '2021-10-12';
				//$this->db->where("(DATE(date) = '".$yesterday."' OR DATE(updated_date) = '".$yesterday."') AND status = 1");
				//$this->db->where("DATE(updated_date) = '".$yesterday."' AND status = 1");
				//$this->db->where('DATE(updated_date)>=' ,'2021-01-01');
				//$this->db->where('DATE(updated_date)<=' , '2021-01-27');
				$receipt_no = array(21318);
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
								if(date("Y-m-d", strtotime($date_of_invoice)) >= '2021-01-01')
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
										{ //echo 'swa';
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
				$yesterday ='2023-02-25';
				$select = 'b.id as pay_txn_id,b.receipt_no';
				$this->db->join('member_registration a','a.regnumber=c.regnumber','LEFT');
				//$this->db->where('DATE(c.added_date) >=', '2019-07-16');
				//$this->db->where('DATE(c.added_date) <=', '2019-07-27');
				//$ref_id = array(31307);
				//$this->db->where_in('c.did', $ref_id);
				$this->db->join('payment_transaction b','b.ref_id=c.did','LEFT');
				$dup_icard_data = $this->Master_model->getRecords('duplicate_icard c',array(' DATE(added_date)'=>$yesterday,'pay_type'=>3,'isactive'=>'1','status'=>'1','isdeleted'=>0),$select);
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
				$yesterday = '2023-02-25';
				$select = 'b.id as pay_txn_id,b.receipt_no';
				//$this->db->where('DATE(c.created_on) >=', '2019-07-02');
				//$this->db->where('DATE(c.created_on) <=', '2019-07-27');
				//$ref_id = array(69200);
				//$this->db->where_in('c.id', $ref_id);
				$this->db->join('payment_transaction b','b.ref_id = c.id','LEFT');
				$dup_cert_data = $this->Master_model->getRecords('duplicate_certificate c',array(' DATE(created_on)' => $yesterday,'pay_type' => 4,'pay_status' => 1,'status' => '1'),$select);
				// ' DATE(created_on)' => $yesterday,
				echo $this->db->last_query();
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
				$member_no = array(7336351);
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
				$ref_id = array(1459,1460,1461); 
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
				$yesterday = '2021-10-02';
				$select = 'b.id as pay_txn_id,b.receipt_no';
				//$this->db->where('DATE(c.created_on) >=', '2019-11-01');
				//$this->db->where('DATE(c.created_on) <=', '2019-11-30');
				$ref_id = array(485,486,487);
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
				//$yesterday = '2023-02-25';
				$blended_id = array(25962);
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
				//$yesterday = '2022-09-30';
				$select = 'c.contact_classes_id,b.id as pay_txn_id,b.receipt_no';
				$ref_id = array(4120,4121,4122,4127,4123);
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
				$yesterday = '2023-02-24';//date('Y-m-d', strtotime("- 1 day"));	// default current date
				//DATE(date) = '".$yesterday."' OR 
				//$this->db->where("(DATE(updated_date) = '".$yesterday."') AND status = 1");
				//$this->db->where("`UTR_no` IN ('AXIS12345') AND status = 1");
				$this->db->where('DATE(updated_date)=' ,$yesterday);
				//$this->db->where('DATE(updated_date)<=' , '2021-01-27');
				
				$bulk_payment = $this->Master_model->getRecords('bulk_payment_transaction',array('status'=>'1'));
				echo  $this->db->last_query();
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
				$yesterday ='2023-02-25'; 
				$select = 'a.examination_date,b.id as pay_txn_id,b.receipt_no';
				//$this->db->where('DATE(a.created_on) >=', '2019-05-01');
				//$this->db->where('DATE(a.created_on) <=', '2019-05-15');
				//$ref_id = array(7307078);
				//$this->db->where_in('a.id', $ref_id);
				$this->db->join('payment_transaction b','b.ref_id = a.id','LEFT');
				$this->db->join('member_registration c','a.regnumber=c.regnumber','LEFT'); 
				$can_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'pay_type'=>18,'status'=>1,'isactive'=>'1','isdeleted'=>0,'pay_status'=>1),$select);
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