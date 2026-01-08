<?php
/*
 	* Controller Name	:	Dbftojaiibcron File Generation
 	* Created By		:	Priyanka D
 	* Created Date		:	21-02-2024
*/

defined('BASEPATH') OR exit('No direct script access allowed');
class Dbftojaiibcron extends CI_Controller {
			//exit;
	public function __construct(){
		parent::__construct();
		
		$this->load->model('UserModel');
		$this->load->model('Master_model');
		
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->model('log_model');
		
		/* File Path */
		define('MEM_FILE_PATH','/fromweb/images/newmem/');
		define('CSC_MEM_FILE_PATH','/fromweb/images/newmem/');
		define('DRA_FILE_PATH','/fromweb/images/dra/');
		define('MEM_FILE_EDIT_PATH','/fromweb/images/edit/');
		define('MEM_FILE_RENEWAL_PATH','/fromweb/images/renewal/');
		define('DIGITAL_EL_MEM_FILE_PATH','/fromweb/images/newmem/');
		define('SCE_FILE_PATH','/fromweb/images/scribe/');
		
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

	
	/* Exam Application Cron*/
	public function logdata()
	{
		ini_set("memory_limit", "-1");
		
		$dir_flg = 0;
		$parent_dir_flg = 0;
		$exam_file_flg = 0;
		$success = array();
		$error = array();
		
		$start_time = date("Y-m-d H:i:s");
		$current_date = date("Ymd");	
		$cron_file_dir = "./uploads/cronfiles_pg/";
		
		//Cron start Logs
		$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
		$desc = json_encode($result);
		$this->log_model->cronlog("Candidate DBFTOJAIIB Details Cron Execution Start", $desc);
		
		if(!file_exists($cron_file_dir.$current_date))
		{
			$parent_dir_flg = mkdir($cron_file_dir.$current_date, 0700); 
		}
		
		
		if(file_exists($cron_file_dir.$current_date))
		{
			$cron_file_path = $cron_file_dir.$current_date;	// Path with CURRENT DATE DIRECTORY
			
			$dirname = "dbftojaiib_offer_letter_" . $current_date;
			$directory = $cron_file_path . '/' . $dirname;
			if (file_exists($directory))
			{
				array_map('unlink', glob($directory . "/*.*"));
				rmdir($directory);
				$dir_flg = mkdir($directory, 0700);
			}
			else
			{
				$dir_flg = mkdir($directory, 0700);
			}
	
			// Create a zip of images folder
			$zip = new ZipArchive;
			$zip->open($directory . '.zip', ZipArchive::CREATE);

			$file = "dbftojaiib_cand_report_".$current_date.".txt";
			$fp = fopen($cron_file_path.'/'.$file, 'w');
			echo $cron_file_path.'/'.$file.'<br>';
			
			$file1 = "logs_".$current_date.".txt";
			$fp1 = fopen($cron_file_path.'/'.$file1, 'a');
			fwrite($fp1, "\n********** Candidate dbftojaiib Details Cron Start - ".$start_time." *********** \n");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			$pay_ids = array(7934141,7939139,7943837,7962537,7896709);
			
			$select = 'DISTINCT(b.transaction_no),,b.amount,DATE_FORMAT(b.date,"%Y-%m-%d")date,b.receipt_no,b.ref_id,b.status,c.regnumber,c.registrationtype,dbj.certificate_number,dbj.mobile,dbj.certificate_number,dbj.certificate_date,c.firstname,c.email,dbj.dbf_regnumber,e.invoice_image,e.exam_code,e.exam_period,m.medium_code,e.center_code,dbj.offer_letter';
			$this->db->join('payment_transaction b','b.ref_id = dbj.id','LEFT');
			$this->db->join('member_registration c','c.regnumber=dbj.regnumber','LEFT'); 
			$this->db->join('exam_invoice e','b.id = e.pay_txn_id','LEFT');
			$this->db->join('medium_master m','e.exam_code = m.exam_code','LEFT');
			$this->db->join('center_master cm','e.exam_code = cm.exam_name','LEFT');
			/*$can_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'status'=>1,'c.isactive'=>'1','c.isdeleted'=>0,'a.pay_status'=>1,'dbj.pay_status'=>1,'pay_type'=>25),$select);*/
			//$this->db->where_in('b.id',$pay_ids);
			$can_exam_data = $this->Master_model->getRecords('dbftojaiib_registrations dbj',array('status'=>1,'c.isactive'=>'1','c.isdeleted'=>0,'dbj.pay_status'=>1,'pay_type'=>25,'m.exam_period'=>999),$select);
			 
			//echo $this->db->last_query();
			//echo'<pre>';print_r($can_exam_data);exit;
			if(count($can_exam_data)) 
			{
				
				$i = 1;
				$exam_cnt = 0;
				
				foreach($can_exam_data as $exam)
				{
					
					$data = '';
					{
						$part_no=1;
						$elected_sub_code=$place_of_work=$state_place_of_work=$scribe_flag=$elearning_flag=$sub_el_count=$optFlg=$pin_code_place_of_work=''; 
						$trans_date = '';
						if($exam['date'] != '0000-00-00')
						{
							$trans_date = date('d-M-Y',strtotime($exam['date']));
						}
						$offer_letter = '';
						if($exam['offer_letter']!='') {
							$offer_letter = base_url().'/uploads/offer_letter/'.$exam['offer_letter'];
						}
						$data .= ''.$exam['exam_code'].'|'.$exam['exam_period'].'|'.$exam['regnumber'].'|'.$exam['registrationtype'].'|'.$exam['medium_code'].'|'.$exam['center_code'].'|ON||IRE|'.$part_no.'|'.$elected_sub_code.'|'.$place_of_work.'|'.$pin_code_place_of_work.'|'.$state_place_of_work.'|'.$exam['transaction_no'].'|'.$trans_date.'|'.$exam['amount'].'|'.$exam['transaction_no'].'|'.$scribe_flag.'|'.$elearning_flag.'|'.$sub_el_count.'|'.$optFlg.'|'.$exam['dbf_regnumber'].'|'.$offer_letter."\n"; 
						
						//echo '===='.$data.'<br>';
						$chk_img_path = "./uploads/offer_letter/";
						copy($chk_img_path .$exam['offer_letter'], $directory . "/" . $exam['offer_letter']);
						$photo_to_add = $directory . "/" .$exam['offer_letter'];
						$new_photo = substr($photo_to_add, strrpos($photo_to_add, '/') + 1);
						$photo_zip_flg = $zip->addFile($photo_to_add, $new_photo);

						
					}
					
					$exam_file_flg = fwrite($fp, $data);
					if($exam_file_flg)
							$success['cand_exam'] = "Candidate DBFTOJAIIB Details File Generated Successfully.";
					else
						$error['cand_exam'] = "Error While Generating Candidate DBFTOJAIIB Details File.";
					
					$i++;
					$exam_cnt++;
				}
				
				fwrite($fp1, "Total DBFTOJAIIB Applications - ".$exam_cnt."\n");
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
			$this->log_model->cronlog("Candidate DBFTOJAIIB Details Cron End", $desc);
			
			fwrite($fp1, "\n"."********** Candidate DBFTOJAIIB Details Cron End ".$end_time." ***********"."\n");
			fclose($fp1);
		}
	}
	
	/* Exam invoices */
	public function invoice()
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
	  $cron_file_dir = "./uploads/invoice_cronfiles_pg/";
  
	  $result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
	  $desc = json_encode($result);
	  $this->log_model->cronlog(" Exam Invoice Details Cron Execution Start", $desc);
  
	  if (!file_exists($cron_file_dir . $current_date))
	  {
		$parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700);
	  }
  
	  if (file_exists($cron_file_dir . $current_date))
	  {
		$cron_file_path = $cron_file_dir . $current_date;  // Path with CURRENT DATE DIRECTORY
  
		$file = "dbftojaiib_invoice_" . $current_date . ".txt";
		$fp = fopen($cron_file_path . '/' . $file, 'w');
  
		$file1 = "logs_" . $current_date . ".txt";
		$fp1 = fopen($cron_file_path . '/' . $file1, 'a');
		fwrite($fp1, "\n********** Exam Invoice Details Cron Start - " . $start_time . " ********** \n");
  
		$yesterday = date('Y-m-d', strtotime("- 1 day"));
  
		/* $not_exam_codes = array('1018');
			  $this->db->where_not_in('a.exam_code',$not_exam_codes); */
  
		// get invoice details for this member exam application payment transaction by id and receipt_no
		$select = 'b.id as pay_txn_id,b.receipt_no, b.exam_code, c.regnumber';
		$this->db->join('payment_transaction b', 'b.ref_id = dbj.id', 'LEFT');
		$this->db->join('member_registration c', 'dbj.regnumber=c.regnumber', 'LEFT');

		
		/*$can_exam_data = $this->Master_model->getRecords('member_exam a',array(' DATE(a.created_on)'=>$yesterday,'status'=>1,'c.isactive'=>'1','c.isdeleted'=>0,'a.pay_status'=>1,'dbj.pay_status'=>1,'pay_type'=>25),$select);*/

			$can_exam_data = $this->Master_model->getRecords('dbftojaiib_registrations dbj',array('status'=>1,'c.isactive'=>'1','c.isdeleted'=>0,'dbj.pay_status'=>1,'pay_type'=>25),$select);
		
		//echo $this->db->last_query();exit;

		$exam_invoice_data_arr = array();
		if (count($can_exam_data) > 0)
		{
		  $exam_invoice_data_arr = array_merge($exam_invoice_data_arr, $can_exam_data);
		}
		
  
		if (count($exam_invoice_data_arr))
		{
		  $i = 1;
		  $exam_invoice_count = 0;
		  $exam_invoice_Image_cnt = 0;
  
		  $dirname = "dbftojaiib_invoice_image_" . $current_date;
		  $directory = $cron_file_path . '/' . $dirname;
		  if (file_exists($directory))
		  {
			array_map('unlink', glob($directory . "/*.*"));
			rmdir($directory);
			$dir_flg = mkdir($directory, 0700);
		  }
		  else
		  {
			$dir_flg = mkdir($directory, 0700);
		  }
  
		  // Create a zip of images folder
		  $zip = new ZipArchive;
		  $zip->open($directory . '.zip', ZipArchive::CREATE);
  
		  foreach ($exam_invoice_data_arr as $exam)
		  {
			$pay_txn_id = $exam['pay_txn_id'];
			$receipt_no = $exam['receipt_no'];
			$chk_exam_code = $exam['exam_code'];
  
			//$examination_date = $exam['examination_date'];
  
			$this->db->where('app_type', 'G');
  
			$this->db->where('transaction_no !=', '');
			$this->db->where('receipt_no', $receipt_no);
			$exam_invoice_arr = $this->Master_model->getRecords('exam_invoice', array('pay_txn_id' => $pay_txn_id));
			
			if (count($exam_invoice_arr))
			{
			  foreach ($exam_invoice_arr as $exIn_data)
			  {
				$data = '';
				$exam_invoice_Image = '';
  
				$date_of_invoice = $exIn_data['date_of_invoice'];
				$Update_date_of_invoice = date('d-M-y', strtotime($date_of_invoice));
  
				$chk_img_path = "./uploads/debftojaiib/supplier/";
  
				if (is_file($chk_img_path . $exIn_data['invoice_image']))
				{
				  $exam_invoice_Image = base_url() .'/uploads/debftojaiib/supplier/'. $exIn_data['invoice_image'];
				}
				else
				{
				  fwrite($fp1, "* ERROR * - Exam Invoice does not exist  - " . $exIn_data['invoice_image'] . " (" . $exIn_data['member_no'] . ")\n");
				}
  
				$exam_code = '';
				if ($exIn_data['exam_code'] != '' && $exIn_data['exam_code'] != 0)
				{
				  $ex_code = $this->master_model->getRecords('exam_activation_master', array('exam_code' => $exIn_data['exam_code']));
				  if (count($ex_code))
				  {
					if ($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0)
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
				else
				{
				  $exam_code = $exIn_data['exam_code'];
				}
  
  
				// rewrite exam period
				$exam_period = '';
				$exam_period = $exIn_data['exam_period'];
  
				$member_no = $exIn_data['member_no'];

				$inv_path = '/fromweb/images/invoice/'.$exIn_data['invoice_image'];
				$data .= '' . $exam_code . '|' . $exam_period . '|' . $exIn_data['center_code'] . '|' . $exIn_data['center_name'] . '|' . $exIn_data['state_of_center'] . '|' . $exIn_data['invoice_no'] . '|' . $inv_path . '|' . $member_no . '|' . $Update_date_of_invoice . '|' . $exIn_data['transaction_no'] . '|' . $exIn_data['fee_amt'] . '|' . $exIn_data['cgst_rate'] . '|' . $exIn_data['cgst_amt'] . '|' . $exIn_data['sgst_rate'] . '|' . $exIn_data['sgst_amt'] . '|' . $exIn_data['cs_total'] . '|' . $exIn_data['igst_rate'] . '|' . $exIn_data['igst_amt'] . '|' . $exIn_data['igst_total'] . '|' . $exIn_data['qty'] . '|' . $exIn_data['cess'] . '|' . $exIn_data['state_code'] . '|' . $exIn_data['state_name'] . '|' . $exIn_data['service_code'] . '|' . $exIn_data['gstin_no'] . '|' . $exIn_data['tax_type'] . '|' . $exIn_data['app_type'] . "\n";

				if ($dir_flg)
				{
				  // For photo images
				  if ($exam_invoice_Image)
				  {
  
					copy($chk_img_path . $exIn_data['invoice_image'], $directory . "/" . $exIn_data['invoice_image']);
					$photo_to_add = $directory . "/" . $exIn_data['invoice_image'];
					$new_photo = substr($photo_to_add, strrpos($photo_to_add, '/') + 1);
					$photo_zip_flg = $zip->addFile($photo_to_add, $new_photo);
  
					if (!$photo_zip_flg)
					{
					  fwrite($fp1, "* ERROR * - Exam Invoice Image not added to zip  - " . $exIn_data['invoice_image'] . " (" . $exIn_data['member_no'] . ")\n");
					}
					else
					  $exam_invoice_Image_cnt++;
				  }
  
				  if ($photo_zip_flg)
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
  
			  if ($file_w_flg)
			  {
				$success['file'] = "Exam Invoice Details File Generated Successfully. ";
			  }
			  else
			  {
				$error['file'] = "Error While Generating Exam Invoice Details File.";
			  }
			}
		  }
  
		  fwrite($fp1, "\n" . "Total Exam Invoice Details Added = " . $exam_invoice_count . "\n");
		  fwrite($fp1, "\n" . "Total Exam Invoice Images Added = " . $exam_invoice_Image_cnt . "\n");
  
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
  
		fwrite($fp1, "\n" . "********** Exam Invoice Details Cron End " . $end_time . " **********" . "\n");
		fclose($fp1);
	  }
	}

}
