<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	header("Access-Control-Allow-Origin: *");
	
	class Sm_custom_scripts extends CI_Controller
	{    
		public function __construct()
		{ 
			parent::__construct();
			$this->load->library('upload');
			$this->load->helper('upload_helper');
			/* $this->load->helper('master_helper');*/
			$this->load->helper('general_helper');
			$this->load->helper('blended_invoice_helper');
			$this->load->model('Master_model');
			$this->load->library('email');
			$this->load->helper('date');
			$this->load->library('email');
			$this->load->model('Emailsending');
			$this->load->model('log_model'); 
		}
		
		public function index($val='')
		{
			if($val == 1953)
			{
				echo "Welcome to the Sm_custom_scripts controller made by sagar matale<br>";
				echo "01. member_resend_mail() //RESEND EMAIL TO ALL MEMBERS FROM exam_invoice_without_gst TABLE<br>";
				echo "02. backup_db() //Database Backup<br>";
				echo "03. delete_db() //Database Delete<br>";
				echo "04. rename_delete_folder() //Rename or delete folder<br>";
				echo "05. remove_old_folder_files(start_date='', end_date='') //Remove all older files & folder for selected date range or if date range is not selected then for last to last month<br>";
				echo "06. folderSize(dir) //Get folder size<br>";
				echo "07. display_size(size) //Display size in readable format<br>";
				echo "08. rmdir_recursive(dir) //Child function of remove_old_folder_files. It is recursive function to folder inside the folder<br>";
				echo "09. send_mail(from_mail='', to_email='', subject='', mail_data='', view_flag='') //For email sending<br>";
			}
		}
		
		/* RESEND EMAIL TO ALL MEMBERS FROM 'exam_invoice_without_gst' TABLE
		ALTER TABLE `exam_invoice_without_gst` ADD `resend_email_flag` TINYINT NOT NULL DEFAULT '0' COMMENT '0=>Not send, 1=>Send' AFTER `mobile`; */
		public function cron_member_resend_mail()
		{	
			exit;
			$select = "i.invoice_id, i.exam_code, i.member_no, i.pay_amount, i.pay_status, i.pay_txn_date, i.pay_txn_no, i.resend_email_flag, mr.regid, mr.reg_no, mr.regnumber, mr.firstname, mr.middlename, mr.lastname, mr.email, mr.isdeleted";
			$whr_con['i.pay_status'] = '1';
			$whr_con['i.resend_email_flag'] = '0';
			$whr_con['mr.isdeleted'] = '0';
			$this->db->join('member_registration mr','mr.regnumber = i.member_no', 'INNER');
			
			$payment_data = $this->master_model->getRecords('exam_invoice_without_gst i',$whr_con,$select,array(),'',100);
			if(!empty($payment_data) && count($payment_data) > 0)
			{
				foreach($payment_data as $payment)
				{
					$member_name = $transaction_date = $transaction_amt = $transaction_number = $to_email = $from_email = $subject = '';
					
					if($payment['firstname'] != '') { $member_name .= $payment['firstname']; }
					if($payment['middlename'] != '') { $member_name .= " ".$payment['middlename']; }
					if($payment['lastname'] != '') { $member_name .= " ".$payment['lastname']; }
					$transaction_date = $payment['pay_txn_date'];
					$transaction_amt = $payment['pay_amount'];
					$transaction_number = $payment['pay_txn_no'];
					$to_email = $payment['email']; 
					$subject = "IIBF : Transaction Successful";
					$mail_body = '<html>
					<head>
					<title>Transaction Successful</title>
					</head>
					<body style="max-width:600px;border:1px solid #ccc;margin:10px auto;font-size:16px;line-height:18px;">
					<div style="text-align: center;padding: 15px 10px;border-bottom: 1px solid #ccc;line-height:22px;">INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>(AN ISO 21001:2018 Certified )</div>
					<div style="padding:20px;">
					<p style="margin:0 0 20px 0;">Dear '.$member_name.',</p>
					<p style="margin:0 0 10px 0;line:height:22px;">Your transaction has been successfully completed. Please check below transaction details for reference,</p>
					<p style="margin: 0;line-height: 22px;font-weight: 600;">
					Transaction Status : Success</br>
					Transaction Date : '.$transaction_date.'</br>
					Transaction Amount : '.$transaction_amt.'</br>
					Transaction Number : '.$transaction_number.'
					</p>
					<p style="margin:18px 0 0 0;line-height: 20px;">Yours Truly,<br>IIBF Team.</p>
					</div>                            
					</body>
					</html>';
					
					$email_arr = array( 'to'=>$to_email, 'from'=>'noreply@iibf.org.in', 'subject'=>$subject, 'message'=>$mail_body);
					if($this->Emailsending->mailsend($email_arr))
					{
						//echo "Mail send to => ".$to_email;
						
						$up_data['resend_email_flag'] = 1;
						$where_arr['invoice_id'] = $payment['invoice_id'];
						$this->master_model->updateRecord('exam_invoice_without_gst',$up_data,$where_arr);
					}
					else 
					{ 
						//echo "fail";	 
					}
				}
			}
		}
		
		/* Backup database */
		public function backup_db()
		{	
			/* $this->load->dbutil();
			
			// Backup your entire database and assign it to a variable
			$backup = $this->dbutil->backup();
			
			// Load the file helper and write the file to your server
			$this->load->helper('file');
			write_file('/assets/mybackup.gz', $backup);
			
			// Load the download helper and send the file to your desktop
			$this->load->helper('download');
			
			force_download('mybackup'.date("y_m_d_H_i_s").'.gz', $backup); */
		}
		
		/* Delete Database */
		public function delete_db()
		{
			/* $tables=$this->db->query("SELECT t.TABLE_NAME AS myTables FROM INFORMATION_SCHEMA.TABLES AS t WHERE t.TABLE_SCHEMA = 'old_admin' AND t.TABLE_NAME LIKE '%a%' ")->result_array();   
			foreach($tables as $key => $val)
			{
				$this->db->query("DROP TABLE ".$val['myTables']);
			} */
		}
		
		/* Rename folder / Delete folder */
		public function rename_delete_folder()
		{
			/* $this->load->helper("file");
			
			$oldDir = ("./assets");
			$newDir = ("./assets");
			
			//rename($oldDir, $newDir);//Rename Directory
			//rmdir("xxx");//Remove directory */
		}
		
		/* 	DELETE ALL FOLDER & SUBFOLDERS FROM LAST TO LAST MONTH IF DATE FILTER IS NOT SET
				Eg. Suppose current month is July 2020, then delete all data from May 2020.
				Delete Folders : 	kyc_cronfiles_pg, cronCSV, cronCSV_csc, cronCSV_flipick, cronCSV_kesdee, cronCSV_NSEIT, cronCSV_mps, cronCSV_SIFFY_MSME, cronCSV_SIFFY, Cron_iibfdra_Dailyreport,
													invoice_cronfiles_pg, cronfiles, cronfiles_pg */		
		public function cron_delete_old_folders($start_date='', $end_date='')
		{
			ini_set("memory_limit", "-1");
			$this->load->helper('directory'); 
			
			//If start date is not present, consider start date as first day of last to last month from current date. If start date is present, consider start date as start date
			if($start_date == '' || $start_date == '0') { $check_start_date = date('Y-m-d', strtotime(date('Y-m-01').' -2 MONTH')); }
			else { $check_start_date = date("Y-m-d", strtotime($start_date)); }
			
			//If end date is not present, consider end date as last day of $check_start_date. If end date is present, consider end date as end date
			if($end_date == '' || $end_date == '0') { $check_end_date = date("Y-m-t", strtotime($check_start_date)); }
			else { $check_end_date = date("Y-m-d", strtotime($end_date)); }
			
			if($check_start_date == '1970-01-01' || $check_end_date == '1970-01-01') { echo "Invalid date range"; }
			else
			{
				//Created array of all dates from required date range
				$result_date_arr = array();
				$check_date_arr = new DatePeriod(new DateTime($check_start_date), new DateInterval('P1D'), new DateTime(date('Y-m-d', strtotime("+1 days", strtotime($check_end_date)))));
				foreach ($check_date_arr as $key => $value) 
				{
					$chk_date = $value->format('Y-m-d');
					$result_date_arr[date("Ymd", strtotime($chk_date))] = $chk_date;
				} 
				//echo count($result_date_arr); echo "<pre>";print_r($result_date_arr);echo "</pre>";	exit; 
				
				$main_cnt = 0;
				$dir_name_arr = array("kyc_cronfiles_pg", "cronCSV", "cronCSV_csc", "cronCSV_flipick", "cronCSV_kesdee", "cronCSV_NSEIT", "cronCSV_mps", "cronCSV_SIFFY_MSME", "cronCSV_SIFFY", "Cron_iibfdra_Dailyreport", "cronCSV_cscvendor", "CSC_FREE_APP", "dra_csv", 
				"invoice_cronfiles_pg", "cronfiles", "cronfiles_pg"); 
				
				/* $dir_name_arr = array("kyc_cronfiles_pg", "cronCSV", "cronCSV_csc", "cronCSV_flipick", "cronCSV_kesdee", "cronCSV_NSEIT", "cronCSV_mps", "cronCSV_SIFFY_MSME", "cronCSV_SIFFY", "Cron_iibfdra_Dailyreport", "cronCSV_cscvendor", 
				"invoice_cronfiles_pg", "cronfiles_pg"); */
				//print_r($dir_name_arr);
				foreach($dir_name_arr as $directory_name)
				{
					//$dir_name = 'uploads/rahultest/'.$directory_name."/";
					$dir_name = 'uploads/'.$directory_name."/";
					$directory_list = $this->get_directory_list($dir_name); //echo "<pre>";print_r($directory_list);echo "</pre>"; exit;					
					
					$deleted_folder_list = $deleted_file_list = '';
					$i = $j = 1;
					$deleted_folder_cnt = $deleted_file_cnt = 0;
					$del_cnt = 1;					
					if($directory_name == 'invoice_cronfiles_pg' || $directory_name == 'cronfiles' || $directory_name == 'cronfiles_pg') 
					{
						$pending_dir_cnt = $this->get_pending_directory_cnt($dir_name, $result_date_arr);
						if($pending_dir_cnt > 11) { $del_limit = 10; } else { $del_limit = 11; }
					} 
					else { $del_limit = 31; }
					
					//echo "<br>Del limit : $del_limit >> pending_dir_cnt : $pending_dir_cnt";
					$deleted_size = $delete_flag = 0;
					if($directory_name == 'kyc_cronfiles_pg' || $directory_name == 'cronCSV' || $directory_name == 'cronCSV_csc' || $directory_name == 'cronCSV_flipick' || $directory_name == 'cronCSV_kesdee' || $directory_name == 'cronCSV_NSEIT' || $directory_name == 'cronCSV_mps' || $directory_name == 'cronCSV_SIFFY_MSME' || $directory_name == 'cronCSV_SIFFY' || $directory_name == 'Cron_iibfdra_Dailyreport' || $directory_name == 'cronCSV_cscvendor')
					{
						$delete_flag = 1;
					}
					else if(($directory_name == 'invoice_cronfiles_pg' || $directory_name == 'cronfiles' || $directory_name == 'cronfiles_pg') && $main_cnt == 0)
					{
						$delete_flag = 1;
					}
					
					if($delete_flag == 1 && $directory_list != "" && count($directory_list) > 0)
					{
						foreach($directory_list as $directory)
						{
							if($del_cnt <= $del_limit)
							{
								$file_folder_name = str_replace('\\', '', $directory);
								$file_folder_name = str_replace('/', '', $file_folder_name);
								
								$final_file_folder_name = $file_folder_name;
								//$final_file_folder_name = str_replace('.txt', '', $final_file_folder_name);
								//$final_file_folder_name = str_replace('.zip', '', $final_file_folder_name);
								
								if(array_key_exists($final_file_folder_name, $result_date_arr))
								{
									$deleted_size = $deleted_size + $this->folderSize($dir_name.$file_folder_name); 
									
									if($final_file_folder_name == $file_folder_name)
									{
										$this->rmdir_recursive($dir_name.$file_folder_name);
										$deleted_folder_list .= $dir_name.$file_folder_name."<br>";
										$i++;
										$deleted_folder_cnt++;
									}
									else
									{
										/* unlink($dir_name.$file_folder_name);
											$deleted_file_list .= $j.". ".$dir_name.$file_folder_name."<br>";
											$j++;
										$deleted_file_cnt++; */
									}
									
									$del_cnt++;									
								}				
							}				
						}
						
						$deleted_size = $this->display_size($deleted_size);
						if($deleted_folder_cnt != "" || $deleted_file_cnt != '') //Send email for deleted folders
						{
							$main_cnt++;
							$email_str = '';
							if($deleted_folder_cnt != "") { $email_str .= $deleted_folder_cnt.' folders'; }
							//if($deleted_folder_cnt != "" && $deleted_file_cnt != '') { $email_str .=" and "; }
							//if($deleted_file_cnt != "") { $email_str .= $deleted_file_cnt.' files'; }
							
							//EMAIL CODE GOES HERE
							$to_email = 'sagar.matale@esds.co.in'; 
							$subject = "IIBF : Deleted Files and Folders list from ".$check_start_date." to ".$check_end_date;
							$mail_body = '<html>
							<head><title>IIBF : File and Folder deletion</title></head>
							<body>
							<div style="max-width:600px;border:1px solid #ccc;margin:10px auto;font-size:16px;line-height:18px;">
							<div style="text-align: center;padding: 15px 10px;border-bottom: 1px solid #ccc;line-height:22px;">INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>(AN ISO 21001:2018 Certified )</div>
							<div style="padding:20px;">
							<p style="margin:0 0 20px 0;">Hello Team,</p>
							<p style="margin:0 0 10px 0;line-height:22px;">We have deleted '.$email_str.' from the date range '.$check_start_date.' to '.$check_end_date.'. <br>Please check below details for reference,</p>
							<p style="margin: 0;line-height: 22px;font-weight: 600;">Deleted Folder : '.$deleted_folder_cnt.'</p>
							<p style="margin: 0;line-height: 22px;font-weight: 600;">Deleted Folder Size : '.$deleted_size.'</p>';
							
							if($deleted_folder_cnt != "")
							{
								$mail_body .= '<p style="margin: 10px 0 0 0;line-height: 22px;font-weight: 600;">Deleted Folder List : <br>'.$deleted_folder_list.'</p>';
							}
							
							/* if($deleted_folder_cnt != "" && $deleted_file_cnt != '') { $mail_body .="<br>"; }
								
								if($deleted_file_cnt != "")
								{
								$mail_body .= '<p style="margin: 0;line-height: 22px;font-weight: 600;">Deleted Files : <br>'.$deleted_file_list.'</p>';
							} */
							
							$mail_body .= '<p style="margin:18px 0 0 0;line-height: 20px;">Yours Truly,<br>IIBF Team.</p>
							</div>                            
							</div>                            
							</body>
							</html>';
							
							$email_arr = array( 'to'=>$to_email, 'from'=>'noreply@iibf.org.in', 'subject'=>$subject, 'message'=>$mail_body);
							@$this->Emailsending->mailsend($email_arr);
							//@$this->send_mail('noreply@iibf.org.in', $to_email, $subject, $mail_body, '');
						}
						
						if($deleted_folder_cnt > 0) { echo "<br>".$dir_name." : ".$deleted_folder_cnt." folders deleted having total size ".$deleted_size."."; }
					}
				}
			}			
		}
		
		public function cron_delete_old_sbi_mis_report($start_date='', $end_date='')
		{	exit;
			ini_set("memory_limit", "-1");
			$this->load->helper('directory'); 
			
			//If start date is not present, consider start date as first day of last to last month from current date. If start date is present, consider start date as start date
			if($start_date == '' || $start_date == '0') { $check_start_date = date('Y-m-d', strtotime(date('Y-m-01').' -2 MONTH')); }
			else { $check_start_date = date("Y-m-d", strtotime($start_date)); }
			
			//If end date is not present, consider end date as last day of $check_start_date. If end date is present, consider end date as end date
			if($end_date == '' || $end_date == '0') { $check_end_date = date("Y-m-t", strtotime($check_start_date)); }
			else { $check_end_date = date("Y-m-d", strtotime($end_date)); }
			
			if($check_start_date == '1970-01-01' || $check_end_date == '1970-01-01') { echo "Invalid date range"; }
			else
			{
				//Created array of all dates from required date range
				$result_date_arr = array();
				$check_date_arr = new DatePeriod(new DateTime($check_start_date), new DateInterval('P1D'), new DateTime(date('Y-m-d', strtotime("+1 days", strtotime($check_end_date)))));
				foreach ($check_date_arr as $key => $value) 
				{
					$chk_date = $value->format('Y-m-d');
					$result_date_arr['SBI_MIS_SUCCESS_'.date("dmY", strtotime($chk_date)).'.csv'] = $chk_date;
				} 
				//echo count($result_date_arr); echo "<pre>";print_r($result_date_arr);echo "</pre>";	exit;
				
				$main_cnt = 0;
				$dir_name_arr = array("sbi_mis_report");
				
				foreach($dir_name_arr as $directory_name)
				{
					//$dir_name = 'uploads/rahultest/'.$directory_name."/";
					$dir_name = 'uploads/'.$directory_name."/";
					$directory_list = $this->get_directory_list($dir_name); //echo "<pre>";print_r($directory_list);echo "</pre>"; exit;					
					
					$deleted_folder_list = $deleted_file_list = '';
					$i = $j = 1;
					$deleted_folder_cnt = $deleted_file_cnt = 0;
					$del_cnt = 1;	
					$del_limit = 100;
					
					
					//echo "<br>Del limit : $del_limit >> pending_dir_cnt : $pending_dir_cnt";
					$deleted_size = $delete_flag = 0;
					if(($directory_name == 'sbi_mis_report') && $main_cnt == 0)
					{
						$delete_flag = 1;
					}
					
					if($delete_flag == 1 && $directory_list != "" && count($directory_list) > 0)
					{
						foreach($directory_list as $directory)
						{
							if($del_cnt <= $del_limit)
							{
								$file_folder_name = str_replace('\\', '', $directory);
								$file_folder_name = str_replace('/', '', $file_folder_name);
								
								$final_file_folder_name = $file_folder_name;
								//$final_file_folder_name = str_replace('.txt', '', $final_file_folder_name);
								//$final_file_folder_name = str_replace('.zip', '', $final_file_folder_name);
								
								if(array_key_exists($final_file_folder_name, $result_date_arr))
								{
									$delete_file_name = $dir_name.$file_folder_name;
									$deleted_size = $deleted_size + $this->file_Size($delete_file_name);
									
									if($final_file_folder_name == $file_folder_name)
									{										
										unlink($delete_file_name);
										//$this->rmdir_recursive($dir_name.$file_folder_name);
										$deleted_folder_list .= $delete_file_name."<br>";
										$i++;
										$deleted_folder_cnt++;
									}
									else
									{
										/* unlink($dir_name.$file_folder_name);
											$deleted_file_list .= $j.". ".$dir_name.$file_folder_name."<br>";
											$j++;
										$deleted_file_cnt++; */
									}
									
									$del_cnt++;									
								}				
							}				
						}
						
						$deleted_size = $this->display_size($deleted_size);
						if($deleted_folder_cnt != "" || $deleted_file_cnt != '') //Send email for deleted folders
						{
							$main_cnt++;
							$email_str = '';
							if($deleted_folder_cnt != "") { $email_str .= $deleted_folder_cnt.' files'; }
							//if($deleted_folder_cnt != "" && $deleted_file_cnt != '') { $email_str .=" and "; }
							//if($deleted_file_cnt != "") { $email_str .= $deleted_file_cnt.' files'; }
							
							//EMAIL CODE GOES HERE
							$to_email = 'iibfdevp@esds.co.in'; 
							$subject = "IIBF : Deleted Files and Folders list from ".$check_start_date." to ".$check_end_date;
							$mail_body = '<html>
							<head><title>IIBF : File and Folder deletion</title></head>
							<body>
							<div style="max-width:600px;border:1px solid #ccc;margin:10px auto;font-size:16px;line-height:18px;">
							<div style="text-align: center;padding: 15px 10px;border-bottom: 1px solid #ccc;line-height:22px;">INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>(AN ISO 21001:2018 Certified )</div>
							<div style="padding:20px;">
							<p style="margin:0 0 20px 0;">Hello Team,</p>
							<p style="margin:0 0 10px 0;line-height:22px;">We have deleted '.$email_str.' from the date range '.$check_start_date.' to '.$check_end_date.'. <br>Please check below details for reference,</p>
							<p style="margin: 0;line-height: 22px;font-weight: 600;">Deleted Files : '.$deleted_folder_cnt.'</p>
							<p style="margin: 0;line-height: 22px;font-weight: 600;">Deleted File Size : '.$deleted_size.'</p>';
							
							if($deleted_folder_cnt != "")
							{
								$mail_body .= '<p style="margin: 10px 0 0 0;line-height: 22px;font-weight: 600;">Deleted Folder List : <br>'.$deleted_folder_list.'</p>';
							}
							
							/* if($deleted_folder_cnt != "" && $deleted_file_cnt != '') { $mail_body .="<br>"; }
								
								if($deleted_file_cnt != "")
								{
								$mail_body .= '<p style="margin: 0;line-height: 22px;font-weight: 600;">Deleted Files : <br>'.$deleted_file_list.'</p>';
							} */
							
							$mail_body .= '<p style="margin:18px 0 0 0;line-height: 20px;">Yours Truly,<br>IIBF Team.</p>
							</div>                            
							</div>                            
							</body>
							</html>';
							//echo $mail_body; exit;
							$email_arr = array( 'to'=>$to_email, 'from'=>'noreply@iibf.org.in', 'subject'=>$subject, 'message'=>$mail_body);
							@$this->Emailsending->mailsend($email_arr);
							//@$this->send_mail('noreply@iibf.org.in', $to_email, $subject, $mail_body, '');
						}
						
						if($deleted_folder_cnt > 0) { echo "<br>".$dir_name." : ".$deleted_folder_cnt." files deleted having total size ".$deleted_size."."; }
					}
				}
			}			
		}
		
		/* DELETE ALL ADMIT CARDS OF THAT EXAMS WHOSE EXAM DATE IS OVER BEFORE 5 DAYS FROM CURRENT DATE
		Eg. Suppose today's date is 10-07-2020, then find out all exams having end date less than or equal to 04-07-2020 and delete all admit cards for that exams 
		ADMIT CARD PDF FORMAT : exam_code followed by exam_period followed by member_no.pdf */
		public function cron_delete_admitcard_pdf()
		{ exit;
			ini_set("memory_limit", "-1");
			$this->load->helper('directory');
			$current_date = date("Y-m-d");	
			$deleted_file_list = "";
			$deleted_file_cnt = 0;
			
			$select = 'exm_prd AS ExamPeriod, exm_cd AS ExamCode, exam_date';
			$whr_arr['exam_date <= '] = date('Y-m-d', strtotime("-6days", strtotime($current_date)));
			$this->db->group_by('ExamCode'); 
			$this->db->order_by('ExamCode', 'ASC'); 
			$exam_data = $this->Master_model->getRecords('admit_card_details', $whr_arr, $select);
			
			$delete_pdf_arr = array();
			if(!empty($exam_data) && count($exam_data) > 0)
			{
				foreach($exam_data as $exam_rec)
				{
					$file_name = $exam_rec['ExamCode']."_".$exam_rec['ExamPeriod']."_";
					$delete_pdf_arr[$file_name] = $file_name;
				}
			}
			////echo "<pre>";print_r($delete_pdf_arr);echo "</pre>"; exit;
			
			$dir_name = "uploads/admitcardpdf/";
			$directory_list = $this->get_directory_list($dir_name); //echo "<pre>";print_r($directory_list);echo "</pre>";
			if(!empty($directory_list) && count($directory_list) > 0)
			{
				foreach($directory_list as $dir_res)
				{
					$file_extension = strtolower(pathinfo($dir_res, PATHINFO_EXTENSION));
					if($file_extension == 'pdf')
					{
						$explode_dir_name = explode("_",$dir_res);
						if(!empty($explode_dir_name) && count($explode_dir_name) >= 2)
						{
							$chk_dir_name = $explode_dir_name[0]."_".$explode_dir_name[1]."_";
							if(array_key_exists($chk_dir_name, $delete_pdf_arr))
							{
								@unlink($dir_name.$dir_res);
								$deleted_file_list .= $dir_name.$dir_res.", ";
								$deleted_file_cnt++;
							}
						}
					}
				}
			}
			
			echo "<br>Deleted Files Count : ".$deleted_file_cnt;
			echo "<br>Deleted Files : ".$deleted_file_list;		
			if($deleted_file_cnt > 0)
			{
				//EMAIL CODE GOES HERE
			}
		}
		
		/* GET ALL FOLDER LISTING FROM REQUIRED FOLDER  */
		function get_directory_list($dir_name)
		{
			return $this->array_sort_ascending(directory_map('./'.$dir_name, 1)); // This is use to get all folders and files from current directory excluding subfolders
		}
		
		/* GET ALL FOLDER COUNT FROM REQUIRED DIRECTORY IN SPECIFIC DATE RANGE
			Eg. Suppose we have a folder 'DemoFolder' having multiple folder of all months and we need the count for all folders from MAY 2020, then this function is used to get that count . */
		function get_pending_directory_cnt($dir_name, $result_date_arr)
		{
			$pending_cnt = 0;			
			$directory_list = $this->get_directory_list($dir_name);
			if($directory_list != "" && count($directory_list) > 0)
			{
				foreach($directory_list as $directory)
				{
					$file_folder_name = str_replace('\\', '', $directory);
					$file_folder_name = str_replace('/', '', $file_folder_name);
					
					$final_file_folder_name = $file_folder_name;
					if(array_key_exists($final_file_folder_name, $result_date_arr))
					{
						if($final_file_folder_name == $file_folder_name)
						{
							$pending_cnt++;
						}									
					}				
				}				
			}			
			return $pending_cnt;
		}
		
		/* SORT ARRAY IN ASCENDING ORDER USING VALUES NOT KEY */
		function array_sort_ascending($array)
		{
			if($array != "") { sort($array); /* sort() - sort arrays in ascending order. rsort() - sort arrays in descending order. */ }
			return $array;
		}
		
		/* CALCULATE SPECIFIC FOLDER SIZE */
		function folderSize($dir)
		{
			$size = 0;
			foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) 
			{
				$size += is_file($each) ? filesize($each) : $this->folderSize($each);
			}
			return $size;
		}
		
		/* CALCULATE SPECIFIC FILE SIZE */
		function file_Size($file_nm)
		{
			$size = 0;
			$size = is_file($file_nm) ? filesize($file_nm):0;
			return $size;
		}
		
		/* DISPLAY SIZE IN READABLE FORMAT */
		function display_size($size=0)
		{
			if($size<1024){$size=$size." Bytes";}
			elseif(($size<1048576)&&($size>1023)){$size=round($size/1024, 1)." KB";}
			elseif(($size<1073741824)&&($size>1048575)){$size=round($size/1048576, 1)." MB";}
			else{$size=round($size/1073741824, 1)." GB";}
			return $size;
		}
		
		/* RECURSIVE FUNCTION TO DELETE ALL SUB FILES AND FOLDER FROM REQUIRED FOLDER */
		function rmdir_recursive($dir) 
		{
			foreach(scandir($dir) as $file) 
			{
				if ('.' === $file || '..' === $file) continue;
				if (is_dir("$dir/$file")) 
				{
					$this->rmdir_recursive("$dir/$file");
				}
				else unlink("$dir/$file");
			}
			rmdir($dir);
		}
		
    /* */
    function api_post_registration_encrypted_data_static()
    {
      ini_set("memory_limit", "-1");
			
			include(FCPATH."/BridgePG/Crypt_lib.php");
			$encryObj = new Crypt_lib();
			
			$EncryptKey = 'c+cNh7y44v4wLjb30U2xwcbimGz0ci4VoSxmQvhC94o=';
      echo "<br>Encrypt key : ".$EncryptKey;
			
      echo "<br>******************* Posted URL ***************************************************************************<br>";
      echo $curl_url = 'https://iibf.cscexams.in/backend/web/user/getstudentsapi';
      
      /***** POST DATA TO API : CODE ADDED BY SAGAR ON 13-07-2020 *******/
      echo "<br><br><br>******************* Original Data ***************************************************************************";
      echo "<br>first_name : ".$first_name = 'MOHAN'; //firstname
      echo "<br>middle_name : ".$middle_name = 'CHAND'; //middlename
      echo "<br>last_name : ".$last_name = 'NALLURI'; //lastname
      echo "<br>member_number : ".$member_number = '510329777'; //regnumber
      echo "<br>password : ".$password = 'WK72GQ'; //pwd
      echo "<br>dob : ".$dob = '1981-03-19'; //dateofbirth
      echo "<br>gender : ".$gender = 'M'; //gender
			echo "<br>email_id : ".$email_id = 'demouser@gmail.com'; //email mohannalluri81@gmail.com
      echo "<br>mobile : ".$mobile = '9381845355'; //mobile
      echo "<br>address : ".$address = 'xxx'; //address
      echo "<br>state : ".$state = 'TEL'; //state
      echo "<br>pin_code : ".$pin_code = 'xxx'; //pin_code
      echo "<br>country : ".$country = 'xxx'; //country
      echo "<br>profession : ".$profession = 'xxx'; //profession
      echo "<br>organization : ".$organization = 'xxx'; //organization
      echo "<br>designation : ".$designation = 'xxx'; //designation
      echo "<br>exam_code : ".$exam_code = '991'; //exam_code
      echo "<br>course : ".$course = 'xxx'; //course
      echo "<br>elective_sub_code : ".$elective_sub_code = 'xxx'; //elective_sub_code
      echo "<br>elective_sub_desc : ".$elective_sub_desc = 'xxx'; //elective_sub_desc
			
			$select = 'regnumber';
      $this->db->where_in('exam_code', $exam_code);
      $this->db->where_in('regnumber', $member_number);
      $attempt_count = $this->Master_model->getRecords('member_exam', array('pay_status' => 1), $select);
      $attempt_count = count($attempt_count);
      $attempt_count = $attempt_count - 1;      
      echo "<br>attempt : ".$attempt = $attempt_count;
			echo "<br>registration_date : ".$registration_date = '2019-05-29'; //registration_date
			echo "<br>exam_date : ".$exam_date = '2019-06-03'; //exam_date
			echo "<br>batch_start_time : ".$batch_start_time = '2019-06-03 10:00:00'; //time
			echo "<br>exam_medium : ".$exam_medium = 'A'; //exam_medium
			echo "<br>exam_center_code : ".$exam_center_code = 'xxx'; //exam_center_code
			echo "<br>venue_code : ".$venue_code = 'xxx'; //venue_code
			echo "<br>server_url : ".$server_url = 'xxx'; //server_url
			
			$member_images = $this->get_member_images('', '',$member_number, 'p_510329777.jpg', 'pr_510329777.jpg', 's_510329777.jpg');
      $scannedphoto = $member_images['scannedphoto'];
      $scannedsignaturephoto = $member_images['scannedsignaturephoto'];
      $idproofphoto = $member_images['idproofphoto'];      
      echo "<br>p_image : ".$p_image = $scannedphoto;
      echo "<br>s_image : ".$s_image = $scannedsignaturephoto;
      echo "<br>pr_image : ".$pr_image = $idproofphoto;
			
      $post_field_arr['first_name'] = $encryObj->encrypts_dat($first_name,$EncryptKey);
      $post_field_arr['middle_name'] = $encryObj->encrypts_dat($middle_name,$EncryptKey);
      $post_field_arr['last_name'] = $encryObj->encrypts_dat($last_name,$EncryptKey);
      $post_field_arr['member_number'] = $encryObj->encrypts_dat($member_number,$EncryptKey);
      $post_field_arr['password'] = $encryObj->encrypts_dat($password,$EncryptKey);
      $post_field_arr['dob'] = $encryObj->encrypts_dat($dob,$EncryptKey);
      $post_field_arr['gender'] = $encryObj->encrypts_dat($gender,$EncryptKey);
			$post_field_arr['email_id'] = $encryObj->encrypts_dat($email_id,$EncryptKey);
      $post_field_arr['mobile'] = $encryObj->encrypts_dat($mobile,$EncryptKey);
      $post_field_arr['address'] = $encryObj->encrypts_dat($address,$EncryptKey);
      $post_field_arr['state'] = $encryObj->encrypts_dat($state,$EncryptKey);
      $post_field_arr['pin_code'] = $encryObj->encrypts_dat($pin_code,$EncryptKey);
      $post_field_arr['country'] = $encryObj->encrypts_dat($country,$EncryptKey);
      $post_field_arr['profession'] = $encryObj->encrypts_dat($profession,$EncryptKey);
      $post_field_arr['organization'] = $encryObj->encrypts_dat($organization,$EncryptKey);
      $post_field_arr['designation'] = $encryObj->encrypts_dat($designation,$EncryptKey);
			$post_field_arr['exam_code'] = $encryObj->encrypts_dat($exam_code,$EncryptKey);
			$post_field_arr['course'] = $encryObj->encrypts_dat($course,$EncryptKey);
			$post_field_arr['elective_sub_code'] = $encryObj->encrypts_dat($elective_sub_code,$EncryptKey);
			$post_field_arr['elective_sub_desc'] = $encryObj->encrypts_dat($elective_sub_desc,$EncryptKey);
      $post_field_arr['attempt'] = $encryObj->encrypts_dat($attempt,$EncryptKey);
      $post_field_arr['registration_date'] = $encryObj->encrypts_dat($registration_date,$EncryptKey);
      $post_field_arr['exam_date'] = $encryObj->encrypts_dat($exam_date,$EncryptKey);
      $post_field_arr['batch_start_time'] = $encryObj->encrypts_dat($batch_start_time,$EncryptKey);
      $post_field_arr['exam_medium'] = $encryObj->encrypts_dat($exam_medium,$EncryptKey);
      $post_field_arr['exam_center_code'] = $encryObj->encrypts_dat($exam_center_code,$EncryptKey);
      $post_field_arr['venue_code'] = $encryObj->encrypts_dat($venue_code,$EncryptKey);
      $post_field_arr['server_url'] = $encryObj->encrypts_dat($server_url,$EncryptKey);
      $post_field_arr['p_image'] = $encryObj->encrypts_dat($p_image,$EncryptKey);
      $post_field_arr['s_image'] = $encryObj->encrypts_dat($s_image,$EncryptKey);
      $post_field_arr['pr_image'] = $encryObj->encrypts_dat($pr_image,$EncryptKey);      
      echo "<br><br><br>******************* Original Encrypted Data ***************************************************************************";
      echo "<pre>"; print_r($post_field_arr); echo "</pre>"; 
      
      /* $fields_string = "";      
      foreach($post_field_arr as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
      rtrim($fields_string, '&'); */
			
			$fields_string = json_encode($post_field_arr);
      
      echo "<br><br>******************* Posted Data String ***************************************************************************<br>";
      echo $fields_string;
            
      $ch = curl_init($curl_url);                                                                      
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
      curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);                                                                  
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     
      $result = curl_exec($ch); 
      curl_close($ch);
      
      echo "<br><br><br><br>******************* Response ***************************************************************************<br>";
      echo $result;
    }
    
    function api_post_registration_encrypted_data() 
    {
			/* header("Access-Control-Allow-Origin: *");
			header("Authorization: Bearer VGVjQ1NDU1BWOlRlY0NTQ1NQXv");
			header("Content-Type: application/json; charset=UTF-8");
			header("Access-Control-Allow-Methods: POST");
			header("Access-Control-Max-Age: 3600");
			header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); */
			
			ini_set("memory_limit", "-1");
			error_reporting(E_ALL);
      ini_set('display_errors', 1);
			
			/**** ADDED CODE BY SAGAR FOR ENCRYPTION : 09-07-2020 ****/
			include(FCPATH."/BridgePG/Crypt_lib.php");
			$encryObj = new Crypt_lib();
			$EncryptKey = 'c+cNh7y44v4wLjb30U2xwcbimGz0ci4VoSxmQvhC94o=';			//c+cNh7y44v4wLjb30U2xwcbimGz0ci4VoSxmQvhC94o=		//VGVjQ1NDU1BWOlRlY0NTQ1NQXv
			/*** ADDED CODE BY SAGAR FOR ENCRYPTION : 09-07-2020 ****/
			
      $dir_flg = 0;
      $parent_dir_flg = 0;
      $exam_file_flg = 0;
      $success = array();
      $error = array();
      $start_time = date("Y-m-d H:i:s");
      $current_date = date("Ymd");
      $cron_file_dir = "./uploads/rahultest/";
      $result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
      $desc = json_encode($result);
      $this->log_model->cronlog("CSC CSV Cron Execution Start", $desc);
      
      if(!file_exists($cron_file_dir.$current_date)) { $parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700); }
      
      if(file_exists($cron_file_dir.$current_date)) 
      {
        $cron_file_path = $cron_file_dir.$current_date; // Path with CURRENT DATE DIRECTORY
        $file = "iibfportal_".$current_date.".csv";
        $fp = fopen($cron_file_path.'/'.$file,'w');
        $file1 = "logs_" . $current_date.".txt";
        $fp1 = fopen($cron_file_path.'/'.$file1,'a');
        $member_img_log = fopen($cron_file_path.'/member_img_'.$file1,'a');
        fwrite($fp1, "\n**************** CSC CSV Cron Execution Started - " . $start_time . " **************** \n");
        $yesterday = date('Y-m-d', strtotime("- 1 day"));
        //$yesterday = '2020-06-18';
        
        //$reg = array('801499800');
        $exam_code = array('991');
        $select = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname, c.image_path, c.reg_no, c.regnumber, c.scannedphoto, c.idproofphoto, c.scannedsignaturephoto, a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date,a.exam_medium,a.exam_center_code,d.venueid,d.pwd,d.exam_date,d.time';
        $this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
        $this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
        $this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
        $this->db->where_in('a.exam_code', $exam_code);
				//$this->db->where_in('a.regnumber', $reg);
        $can_exam_data = $this->Master_model->getRecords('member_exam a', array(
				'remark' => 1,
        'pay_type' => 2,
        'status' => 1,
        'isactive' => '1',
        'isdeleted' => 0,
        'pay_status' => 1,
        'bankcode' => 'csc',
        'DATE(a.created_on)' => $yesterday 
        ), $select,'','',
				3 #
				);
        ///*
        // ,'DATE(a.created_on)' => $yesterday 
        //   //'a.regnumber' => '801345005',
        //echo $this->db->last_query()."<br><br>"; //exit;
        
				$api_data_arr = array();
        if(count($can_exam_data)) 
        {
          $i = 1;
          $exam_cnt = 0;
          // Column headers for CSV            
          $data1 = "First Name,Middle name,Last Name,Mem. Number,Password,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt,Registration Date,Exam date,Time,Exam Medium,Exam Center Code,Venue Code,server_url,p_image,s_image,pr_image \n";
          $exam_file_flg = fwrite($fp, $data1);
          foreach ($can_exam_data as $exam) 
          {
            $firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $registration_date = $data = $syllabus_code = $subject_description = $subject_code = $subject_description = $institution_name = $designation_name = $exam_code = $exam_name = $medium_name = $server_url = $scannedphoto = $scannedsignaturephoto = $idproofphoto = '';
            
            //ADDED BY SAGAR ON 27-06-2020 FOR MEMBER IMAGES          
            $member_images = $this->get_member_images($exam['image_path'], $exam['reg_no'], $exam['regnumber'], $exam['scannedphoto'], $exam['idproofphoto'], $exam['scannedsignaturephoto']);
            $scannedphoto = $member_images['scannedphoto'];
            $scannedsignaturephoto = $member_images['scannedsignaturephoto'];
            $idproofphoto = $member_images['idproofphoto'];
            
            if($scannedphoto == "") { fwrite($member_img_log, "Photo missing - " . $exam['regnumber'] . " \n"); }
            if($scannedsignaturephoto == "") { fwrite($member_img_log, "Signature missing - " . $exam['regnumber'] . " \n"); }
            if($idproofphoto == "") { fwrite($member_img_log, "ID Proof missing - " . $exam['regnumber'] . " \n"); }            
            if($scannedphoto == "" || $scannedsignaturephoto == "" || $idproofphoto == "") { fwrite($member_img_log, "\n"); }
                        
            if ($exam['exam_code'] != '' && $exam['exam_code'] != 0) 
						{
              $ex_code = $this->master_model->getRecords('exam_activation_master', array('exam_code' => $exam['exam_code']));
              if(count($ex_code)) 
              {
                if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0) 
								{
                  $exam_code = $ex_code[0]['original_val'];
                } 
                else 
                {
                  $exam_code = $exam['exam_code'];
                }
              }
              else 
              {
                $exam_code = $exam['exam_code'];
              }
            } 
            else 
						{
              $exam_code = $exam['exam_code'];
            }
            
            //$dateofbirth       = date('m/d/Y', strtotime($exam['dateofbirth']));
            //$registration_date = date('m/d/Y', strtotime($exam['registration_date']));
            
            $dateofbirth = date('d-m-Y', strtotime($exam['dateofbirth']));
            $registration_date = date('d-m-Y', strtotime($exam['registration_date']));
            
            $address = $exam['address1'] . ' ' . $exam['address2'] . ' ' . $exam['address3'] . ' ' . $exam['address4'] . ' ' . $exam['district'] . ' ' . $exam['city'];
            $address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
            $gender = $exam['gender'];
            if ($gender == 'male' || $gender == 'Male') { $gender = 'M'; } elseif($gender == 'female' || $gender == 'Female') { $gender = 'F'; }
            $designation = $this->master_model->getRecords('designation_master');
            if (count($designation)) 
						{
              foreach ($designation as $designation_row) 
							{
                if ($exam['designation'] == $designation_row['dcode']) 
								{
                  $designation_name = $designation_row['dname'];
                }
              }
            }
            $designation_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
            
            $medium = $this->master_model->getRecords('medium_master');
            if (count($medium)) 
						{
              foreach ($medium as $medium_row) 
							{
                if ($exam['exam_medium'] == $medium_row['medium_code']) 
								{
                  $medium_name = $medium_row['medium_description'];
                }
              }
            }
            $medium_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $medium_name);
            
            $institution_master = $this->master_model->getRecords('institution_master');
            if (count($institution_master)) 
						{
              foreach ($institution_master as $institution_row) 
							{
                if ($exam['associatedinstitute'] == $institution_row['institude_id']) 
								{
                  $institution_name = $institution_row['name'];
                }
              }
            }
            $institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
            $firstname = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
            $middlename = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
            $lastname = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
            $mobile = preg_replace('/[^0-9]/', '', $exam['mobile']);
            $pincode = preg_replace('/[^0-9]/', '', $exam['pincode']);
            
            $exam_arr = array('991'=>'CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS/FACILITATORS');            
            foreach ($exam_arr as $k => $val) 
						{
              if ($exam_code == $k) 
							{
                $exam_name = $val;
              }
            }
                        
            $select    = 'regnumber';
            $this->db->where_in('exam_code', 991);
            $this->db->where_in('regnumber', $exam['regnumber']);
            $attempt_count = $this->Master_model->getRecords('member_exam', array('pay_status' => 1), $select);
            $attempt_count = count($attempt_count);            
            $attempt_count = $attempt_count - 1;
            
            /*if($attempt_count == 1){
              $attempt_count = 0;
              }
              elseif($attempt_count == 2){
              $attempt_count = 1
              }
              elseif($attempt_count == 3){
              $attempt_count = 2;
              }
              elseif($attempt_count == 4){ 
              $attempt_count = 3;
              }
              else{
              $attempt_count;
            }*/
            
						/* First Name|Middle name|Last Name|Mem. Number|Password|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession|Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt|Registration Date|Exam Medium|Exam Center Code|Venue Code */
            
            $data .= ''.$encryObj->encrypts_dat($firstname,$EncryptKey).','.$encryObj->encrypts_dat($middlename,$EncryptKey).','.
												$encryObj->encrypts_dat($lastname,$EncryptKey).','.$encryObj->encrypts_dat($exam['regnumber'],$EncryptKey).','.
												$encryObj->encrypts_dat($exam['pwd'],$EncryptKey).','.$encryObj->encrypts_dat($dateofbirth,$EncryptKey).','.
												$encryObj->encrypts_dat($gender,$EncryptKey).','.$encryObj->encrypts_dat($exam['email'],$EncryptKey).','.
												$encryObj->encrypts_dat($mobile,$EncryptKey).','.$encryObj->encrypts_dat($address,$EncryptKey).','.
												$encryObj->encrypts_dat($exam['state'],$EncryptKey).','.$encryObj->encrypts_dat($pincode,$EncryptKey).','.
												$encryObj->encrypts_dat('INDIA',$EncryptKey).','.''.','.$encryObj->encrypts_dat($institution_name,$EncryptKey).','.
												$encryObj->encrypts_dat($designation_name,$EncryptKey).','.$encryObj->encrypts_dat($exam_code,$EncryptKey).','.
												$encryObj->encrypts_dat($exam_name,$EncryptKey).','.$encryObj->encrypts_dat($subject_code,$EncryptKey).','.
												$encryObj->encrypts_dat($subject_description,$EncryptKey).','.$encryObj->encrypts_dat($attempt_count,$EncryptKey).','. 
												$encryObj->encrypts_dat($registration_date,$EncryptKey).','.$encryObj->encrypts_dat($exam['exam_date'],$EncryptKey).','.
												$encryObj->encrypts_dat($exam['time'],$EncryptKey).','.$encryObj->encrypts_dat($medium_name,$EncryptKey).','.
												$encryObj->encrypts_dat($exam['exam_center_code'],$EncryptKey).','.$encryObj->encrypts_dat($exam['venueid'],$EncryptKey).','.
												$encryObj->encrypts_dat($server_url,$EncryptKey).','.$encryObj->encrypts_dat($scannedphoto,$EncryptKey).','.
												$encryObj->encrypts_dat($scannedsignaturephoto,$EncryptKey).','.$encryObj->encrypts_dat($idproofphoto,$EncryptKey)."\n";
            
            /***** POST DATA TO API : CODE ADDED BY SAGAR ON 13-07-2020 *******/
            /* First Name : $firstname
            Middle name : $middlename
            Last Name : $lastname
            Email ID : $exam['email']
            Password : $exam['pwd']
            Mem. Number : $exam['regnumber']
            Gender : $gender
            Date of Birth : $dateofbirth
            Mobile : $mobile
            Registration Date : $registration_date
            Exam Code : $exam_code
            State : $exam['state']
            Attempt : $attempt_count
            Exam date : $exam['exam_date']
            Time : $exam['time']
            Exam Medium : $medium_name
            p_image : $scannedphoto
            s_image : $scannedsignaturephoto
            pr_image : $idproofphoto
            Elective Sub Code : $subject_code
            Elective Sub Desc : $subject_description
            
            Address : $address
            Pin Code : $pincode
            Country : 'INDIA'
            Profession : ''
            Organization : $institution_name
            Designation : $designation_name
            Course : $exam_name
            Exam Center Code : $exam['exam_center_code']
            Venue Code : $exam['venueid']
            server_url : $server_url
            */
            
            $post_field_arr['first_name'] = ($firstname);
            $post_field_arr['middle_name'] = ($middlename);
            $post_field_arr['last_name'] = ($lastname);
            $post_field_arr['member_number'] = ($exam['regnumber']);
            $post_field_arr['password'] = ($exam['pwd']);
            $post_field_arr['dob'] = (date("Y-m-d", strtotime($dateofbirth)));
            $post_field_arr['gender'] = ($gender);
            $post_field_arr['email_id'] = ($exam['email']);
            $post_field_arr['mobile'] = ($mobile);
            $post_field_arr['address'] = ($address);
            $post_field_arr['state'] = ($exam['state']);
            $post_field_arr['pin_code'] = ($pincode);
            $post_field_arr['country'] = ('INDIA');
            $post_field_arr['profession'] = ('');
            $post_field_arr['organization'] = ($institution_name);
            $post_field_arr['designation'] = ($designation_name);
            $post_field_arr['exam_code'] = ($exam_code);
            $post_field_arr['course'] = ($exam_name);
            $post_field_arr['elective_sub_code'] = ($subject_code);
            $post_field_arr['elective_sub_desc'] = ($subject_description);
            $post_field_arr['attempt'] = ($attempt_count);
            $post_field_arr['registration_date'] = (date("Y-m-d", strtotime($registration_date)));
            $post_field_arr['exam_date'] = (date("Y-m-d", strtotime($exam['exam_date'])));
            $post_field_arr['batch_start_time'] = ($exam['time']);
            $post_field_arr['exam_medium'] = ($medium_name);
            $post_field_arr['exam_center_code'] = ($exam['exam_center_code']);
            $post_field_arr['venue_code'] = ($exam['venueid']);
            $post_field_arr['server_url'] = ($server_url);
            $post_field_arr['p_image'] = ($scannedphoto);
            $post_field_arr['s_image'] = ($scannedsignaturephoto);
            $post_field_arr['pr_image'] = ($idproofphoto);
						
            $api_data_arr[] = $post_field_arr;
            
						$exam_file_flg = fwrite($fp, $data);
            if($exam_file_flg) { $success['cand_exam'] = "CSC CSV File Generated Successfully."; }
            else { $error['cand_exam'] = "Error While Generating CSC CSV File."; }
            $i++;
            $exam_cnt++;
          }
          fclose($fp);
          fwrite($fp1, "Total Applications - " . $exam_cnt . "\n");
          
          // File Rename Functinality
          $oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
          $newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
          rename($oldPath, $newPath);
          $OldName = "iibfportal_" . $current_date . ".csv";
          $NewName = "iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
          $insert_info = array('CurrentDate' => $current_date, 'old_file_name' => $OldName, 'new_file_name' => $NewName, 'record_count' => $exam_cnt, 'createdon' => date('Y-m-d H:i:s'));
          $this->master_model->insertRecord('cron_csv_custom', $insert_info, true);
        } 
        else 
        {
          fclose($fp);
          $yesterday = date('Y-m-d', strtotime("- 1 day"));
          fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
          // File Rename Functinality
          $oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
          $newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_0.csv";
          rename($oldPath, $newPath);
          $OldName = "iibfportal_" . $current_date . ".csv";
          $NewName = "iibfportal_" . date('dmYhi') . "_0.csv";
          $insert_info = array('CurrentDate' => $current_date, 'old_file_name' => $OldName, 'new_file_name' => $NewName, 'record_count' => 0, 'createdon' => date('Y-m-d H:i:s'));
          $this->master_model->insertRecord('cron_csv_custom', $insert_info, true);
          $success[] = "No data found for the date";
        }
        fclose($member_img_log);
        				
				/*************** START : SEND ENCRYPTED DATA TO API IN JSON FORMAT ********************************/
				//$api_data_new_arr['request_data'] = $api_data_arr; 
				if(count($api_data_arr) > 0)
				{
					//$curl_url = 'https://iibf.cscexams.in/backend/web/user/getstudentsapi';
					$api_json = json_encode($api_data_arr);
					echo "
					<script src='https://iibf.esdsconnect.com/assets/admin/plugins/jQuery/jQuery-2.2.0.min.js'></script>
					<script>
						/* alert('$api_json'); */
						$.ajax({
							dataType: 'json', 
							type: 'POST',
							headers: 
							{
								'Authorization': 'Bearer VGVjQ1NDU1BWOlRlY0NTQ1NQXv',
							},
							url: 'https://iibf.cscexams.in/backend/web/user/getstudentsapi',
							data: '$api_json',
							success: function(response) 
							{
								console.log(response);
							}
						});
					</script>";
					
					 
					/* headers: 
							{
								'Access-Control-Allow-Origin': '*',
								'Authorization': 'Bearer VGVjQ1NDU1BWOlRlY0NTQ1NQXv',
								'Content-Type': 'application/json; charset=UTF-8',
								'Access-Control-Allow-Methods': 'POST',
								'Access-Control-Max-Age': '3600',
								'Access-Control-Allow-Headers': 'Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With'
							}, */
					
					/* echo "<br>******************* ARRAY ***************************************************************************";
					echo "<pre>"; print_r($api_data_new_arr); echo "</pre>"; //exit; */
					
					//echo "<br><br>******************* ENCODED JSON ***************************************************************************<br><br>";
					//$api_json = json_encode($api_data_arr);
					//echo $api_json; //exit;					
					
					//echo "<br><br><br><br>******************* RESPONSE ***************************************************************************<br><br>";
					/* $ch = curl_init($curl_url);                                                                      
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
					curl_setopt($ch, CURLOPT_POSTFIELDS, $api_json);                                                                  
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				 
					echo $result = curl_exec($ch); 
					curl_close($ch);
				
					$response = (json_decode($result, true)); 
					echo "<pre>"; print_r($response); echo "</pre>"; 
					echo "<br><br><br>"; */
					
					/* $ch = curl_init($curl_url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLINFO_HEADER_OUT, true);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $api_json);
					 
					// Set HTTP Header for POST request 
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
							'Access-Control-Allow-Origin: *',
							'Authorization: Bearer VGVjQ1NDU1BWOlRlY0NTQ1NQXv',
							'Content-Type: application/json; charset=UTF-8',
							'Access-Control-Allow-Methods: POST',
							'Access-Control-Max-Age: 3600',
							'Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With',
							'Content-Length: ' . strlen($api_json))
					); */
					 
					// Submit the POST request
					//echo $result = curl_exec($ch);
					
					// Check for errors and display the error message
					/* if($errno = curl_errno($ch)) 
					{
							$error_message = curl_strerror($errno);
							echo "Error ({$errno}):\n {$error_message}";
					} */
					 
					// Close cURL session handle
					/* curl_close($ch); */
					
					//echo "<br><br>*********************************************************************************************************";
				}
				/*************** END : SEND ENCRYPTED DATA TO API IN JSON FORMAT ********************************/
        
        $end_time = date("Y-m-d H:i:s");
        $result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
        $desc = json_encode($result);
        $this->log_model->cronlog("CSC CSV Cron Execution End", $desc);
        fwrite($fp1, "\n" . "**************** CSC CSV Cron Execution End " . $end_time . " *****************" . "\n");
        fclose($fp1);
      }
    }
    		
		function api_post_registration_encrypted_data_new() 
    {
			ini_set("memory_limit", "-1");
			error_reporting(E_ALL);
      ini_set('display_errors', 1);
			
			/**** ADDED CODE BY SAGAR FOR ENCRYPTION : 09-07-2020 ****/
			include(FCPATH."/BridgePG/Crypt_lib.php");
			$encryObj = new Crypt_lib();
			$EncryptKey = 'c+cNh7y44v4wLjb30U2xwcbimGz0ci4VoSxmQvhC94o=';			//c+cNh7y44v4wLjb30U2xwcbimGz0ci4VoSxmQvhC94o=		//VGVjQ1NDU1BWOlRlY0NTQ1NQXv
			/*** ADDED CODE BY SAGAR FOR ENCRYPTION : 09-07-2020 ****/
			
      $dir_flg = 0;
      $parent_dir_flg = 0;
      $exam_file_flg = 0;
      $success = array();
      $error = array();
      $start_time = date("Y-m-d H:i:s");
      $current_date = date("Ymd");
      $cron_file_dir = "./uploads/rahultest/";
      $result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
      $desc = json_encode($result);
      $this->log_model->cronlog("CSC CSV Cron Execution Start", $desc);
      
      if(!file_exists($cron_file_dir.$current_date)) { $parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700); }
      
      if(file_exists($cron_file_dir.$current_date)) 
      {
        $cron_file_path = $cron_file_dir.$current_date; // Path with CURRENT DATE DIRECTORY
        $file = "iibfportal_".$current_date.".csv";
        $fp = fopen($cron_file_path.'/'.$file,'w');
        $file1 = "logs_" . $current_date.".txt";
        $fp1 = fopen($cron_file_path.'/'.$file1,'a');
        $member_img_log = fopen($cron_file_path.'/member_img_'.$file1,'a');
        fwrite($fp1, "\n**************** CSC CSV Cron Execution Started - " . $start_time . " **************** \n");
        $yesterday = date('Y-m-d', strtotime("- 1 day"));
        //$yesterday = '2020-06-18';
        
        //$reg = array('801499800');
        $exam_code = array('991');
        $select = 'DISTINCT(b.transaction_no),a.exam_code,c.firstname,c.middlename,c.lastname, c.image_path, c.reg_no, c.regnumber, c.scannedphoto, c.idproofphoto, c.scannedsignaturephoto, a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date,a.exam_medium,a.exam_center_code,d.venueid,d.pwd,d.exam_date,d.time';
        $this->db->join('payment_transaction b', 'b.ref_id = a.id', 'LEFT');
        $this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
        $this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
        $this->db->where_in('a.exam_code', $exam_code);
				//$this->db->where_in('a.regnumber', $reg);
        $can_exam_data = $this->Master_model->getRecords('member_exam a', array(
				'remark' => 1,
        'pay_type' => 2,
        'status' => 1,
        'isactive' => '1',
        'isdeleted' => 0,
        'pay_status' => 1,
        'bankcode' => 'csc',
        'DATE(a.created_on)' => $yesterday 
        ), $select,'','',
				3 #
				);
        ///*
        // ,'DATE(a.created_on)' => $yesterday 
        //   //'a.regnumber' => '801345005',
        //echo $this->db->last_query()."<br><br>"; //exit;
        
				$api_data_arr = array();
        if(count($can_exam_data)) 
        {
          $i = 1;
          $exam_cnt = 0;
          // Column headers for CSV            
          $data1 = "First Name,Middle name,Last Name,Mem. Number,Password,Date of Birth,Gender,Email ID,Mobile,Address,State,Pin Code,Country,Profession,Organization,Designation,Exam Code,Course,Elective Sub Code,Elective Sub Desc,Attempt,Registration Date,Exam date,Time,Exam Medium,Exam Center Code,Venue Code,server_url,p_image,s_image,pr_image \n";
          $exam_file_flg = fwrite($fp, $data1);
          foreach ($can_exam_data as $exam) 
          {
            $firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $registration_date = $data = $syllabus_code = $subject_description = $subject_code = $subject_description = $institution_name = $designation_name = $exam_code = $exam_name = $medium_name = $server_url = $scannedphoto = $scannedsignaturephoto = $idproofphoto = '';
            
            //ADDED BY SAGAR ON 27-06-2020 FOR MEMBER IMAGES          
            $member_images = $this->get_member_images($exam['image_path'], $exam['reg_no'], $exam['regnumber'], $exam['scannedphoto'], $exam['idproofphoto'], $exam['scannedsignaturephoto']);
            $scannedphoto = $member_images['scannedphoto'];
            $scannedsignaturephoto = $member_images['scannedsignaturephoto'];
            $idproofphoto = $member_images['idproofphoto'];
            
            if($scannedphoto == "") { fwrite($member_img_log, "Photo missing - " . $exam['regnumber'] . " \n"); }
            if($scannedsignaturephoto == "") { fwrite($member_img_log, "Signature missing - " . $exam['regnumber'] . " \n"); }
            if($idproofphoto == "") { fwrite($member_img_log, "ID Proof missing - " . $exam['regnumber'] . " \n"); }            
            if($scannedphoto == "" || $scannedsignaturephoto == "" || $idproofphoto == "") { fwrite($member_img_log, "\n"); }
                        
            if ($exam['exam_code'] != '' && $exam['exam_code'] != 0) 
						{
              $ex_code = $this->master_model->getRecords('exam_activation_master', array('exam_code' => $exam['exam_code']));
              if(count($ex_code)) 
              {
                if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0) 
								{
                  $exam_code = $ex_code[0]['original_val'];
                } 
                else 
                {
                  $exam_code = $exam['exam_code'];
                }
              }
              else 
              {
                $exam_code = $exam['exam_code'];
              }
            } 
            else 
						{
              $exam_code = $exam['exam_code'];
            }
            
            //$dateofbirth       = date('m/d/Y', strtotime($exam['dateofbirth']));
            //$registration_date = date('m/d/Y', strtotime($exam['registration_date']));
            
            $dateofbirth = date('d-m-Y', strtotime($exam['dateofbirth']));
            $registration_date = date('d-m-Y', strtotime($exam['registration_date']));
            
            $address = $exam['address1'] . ' ' . $exam['address2'] . ' ' . $exam['address3'] . ' ' . $exam['address4'] . ' ' . $exam['district'] . ' ' . $exam['city'];
            $address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
            $gender = $exam['gender'];
            if ($gender == 'male' || $gender == 'Male') { $gender = 'M'; } elseif($gender == 'female' || $gender == 'Female') { $gender = 'F'; }
            $designation = $this->master_model->getRecords('designation_master');
            if (count($designation)) 
						{
              foreach ($designation as $designation_row) 
							{
                if ($exam['designation'] == $designation_row['dcode']) 
								{
                  $designation_name = $designation_row['dname'];
                }
              }
            }
            $designation_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
            
            $medium = $this->master_model->getRecords('medium_master');
            if (count($medium)) 
						{
              foreach ($medium as $medium_row) 
							{
                if ($exam['exam_medium'] == $medium_row['medium_code']) 
								{
                  $medium_name = $medium_row['medium_description'];
                }
              }
            }
            $medium_name   = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $medium_name);
            
            $institution_master = $this->master_model->getRecords('institution_master');
            if (count($institution_master)) 
						{
              foreach ($institution_master as $institution_row) 
							{
                if ($exam['associatedinstitute'] == $institution_row['institude_id']) 
								{
                  $institution_name = $institution_row['name'];
                }
              }
            }
            $institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
            $firstname = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
            $middlename = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
            $lastname = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
            $mobile = preg_replace('/[^0-9]/', '', $exam['mobile']);
            $pincode = preg_replace('/[^0-9]/', '', $exam['pincode']);
            
            $exam_arr = array('991'=>'CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS/FACILITATORS');            
            foreach ($exam_arr as $k => $val) 
						{
              if ($exam_code == $k) 
							{
                $exam_name = $val;
              }
            }
                        
            $select    = 'regnumber';
            $this->db->where_in('exam_code', 991);
            $this->db->where_in('regnumber', $exam['regnumber']);
            $attempt_count = $this->Master_model->getRecords('member_exam', array('pay_status' => 1), $select);
            $attempt_count = count($attempt_count);            
            $attempt_count = $attempt_count - 1;
            
            /*if($attempt_count == 1){
              $attempt_count = 0;
              }
              elseif($attempt_count == 2){
              $attempt_count = 1
              }
              elseif($attempt_count == 3){
              $attempt_count = 2;
              }
              elseif($attempt_count == 4){ 
              $attempt_count = 3;
              }
              else{
              $attempt_count;
            }*/
            
						/* First Name|Middle name|Last Name|Mem. Number|Password|Date of Birth|Gender|Email ID|Mobile|Address|State|Pin Code|Country|Profession|Organization|Designation|Exam Code|Course|Elective Sub Code|Elective Sub Desc|Attempt|Registration Date|Exam Medium|Exam Center Code|Venue Code */
            
            $data .= ''.$encryObj->encrypts_dat($firstname,$EncryptKey).','.$encryObj->encrypts_dat($middlename,$EncryptKey).','.
												$encryObj->encrypts_dat($lastname,$EncryptKey).','.$encryObj->encrypts_dat($exam['regnumber'],$EncryptKey).','.
												$encryObj->encrypts_dat($exam['pwd'],$EncryptKey).','.$encryObj->encrypts_dat($dateofbirth,$EncryptKey).','.
												$encryObj->encrypts_dat($gender,$EncryptKey).','.$encryObj->encrypts_dat($exam['email'],$EncryptKey).','.
												$encryObj->encrypts_dat($mobile,$EncryptKey).','.$encryObj->encrypts_dat($address,$EncryptKey).','.
												$encryObj->encrypts_dat($exam['state'],$EncryptKey).','.$encryObj->encrypts_dat($pincode,$EncryptKey).','.
												$encryObj->encrypts_dat('INDIA',$EncryptKey).','.''.','.$encryObj->encrypts_dat($institution_name,$EncryptKey).','.
												$encryObj->encrypts_dat($designation_name,$EncryptKey).','.$encryObj->encrypts_dat($exam_code,$EncryptKey).','.
												$encryObj->encrypts_dat($exam_name,$EncryptKey).','.$encryObj->encrypts_dat($subject_code,$EncryptKey).','.
												$encryObj->encrypts_dat($subject_description,$EncryptKey).','.$encryObj->encrypts_dat($attempt_count,$EncryptKey).','. 
												$encryObj->encrypts_dat($registration_date,$EncryptKey).','.$encryObj->encrypts_dat($exam['exam_date'],$EncryptKey).','.
												$encryObj->encrypts_dat($exam['time'],$EncryptKey).','.$encryObj->encrypts_dat($medium_name,$EncryptKey).','.
												$encryObj->encrypts_dat($exam['exam_center_code'],$EncryptKey).','.$encryObj->encrypts_dat($exam['venueid'],$EncryptKey).','.
												$encryObj->encrypts_dat($server_url,$EncryptKey).','.$encryObj->encrypts_dat($scannedphoto,$EncryptKey).','.
												$encryObj->encrypts_dat($scannedsignaturephoto,$EncryptKey).','.$encryObj->encrypts_dat($idproofphoto,$EncryptKey)."\n";
            
            /***** POST DATA TO API : CODE ADDED BY SAGAR ON 13-07-2020 *******/
            /* First Name : $firstname
            Middle name : $middlename
            Last Name : $lastname
            Email ID : $exam['email']
            Password : $exam['pwd']
            Mem. Number : $exam['regnumber']
            Gender : $gender
            Date of Birth : $dateofbirth
            Mobile : $mobile
            Registration Date : $registration_date
            Exam Code : $exam_code
            State : $exam['state']
            Attempt : $attempt_count
            Exam date : $exam['exam_date']
            Time : $exam['time']
            Exam Medium : $medium_name
            p_image : $scannedphoto
            s_image : $scannedsignaturephoto
            pr_image : $idproofphoto
            Elective Sub Code : $subject_code
            Elective Sub Desc : $subject_description
            
            Address : $address
            Pin Code : $pincode
            Country : 'INDIA'
            Profession : ''
            Organization : $institution_name
            Designation : $designation_name
            Course : $exam_name
            Exam Center Code : $exam['exam_center_code']
            Venue Code : $exam['venueid']
            server_url : $server_url
            */
            
            $post_field_arr['first_name'] = $firstname;
            $post_field_arr['middle_name'] = $middlename;
            $post_field_arr['last_name'] = $lastname;
            $post_field_arr['member_number'] = $exam['regnumber'];
            $post_field_arr['password'] = $exam['pwd'];
            $post_field_arr['dob'] = date("Y-m-d", strtotime($dateofbirth));
            $post_field_arr['gender'] = $gender;
            $post_field_arr['email_id'] = $exam['email'];
            $post_field_arr['mobile'] = $mobile;
            $post_field_arr['address'] = $address;
            $post_field_arr['state'] = $exam['state'];
            $post_field_arr['pin_code'] = $pincode;
            $post_field_arr['country'] = 'INDIA';
            $post_field_arr['profession'] = '';
            $post_field_arr['organization'] = $institution_name;
            $post_field_arr['designation'] = $designation_name;
            $post_field_arr['exam_code'] = $exam_code;
            $post_field_arr['course'] = $exam_name;
            $post_field_arr['elective_sub_code'] = $subject_code;
            $post_field_arr['elective_sub_desc'] = $subject_description;
            $post_field_arr['attempt'] = $attempt_count;
            $post_field_arr['registration_date'] = date("Y-m-d", strtotime($registration_date));
            $post_field_arr['exam_date'] = date("Y-m-d", strtotime($exam['exam_date']));
            $post_field_arr['batch_start_time'] = $exam['time'];
            $post_field_arr['exam_medium'] = $medium_name;
            $post_field_arr['exam_center_code'] = $exam['exam_center_code'];
            $post_field_arr['venue_code'] = $exam['venueid'];
            $post_field_arr['server_url'] = $server_url;
            $post_field_arr['p_image'] = $scannedphoto;
            $post_field_arr['s_image'] = $scannedsignaturephoto;
            $post_field_arr['pr_image'] = $idproofphoto;
						
            $api_data_arr[] = $post_field_arr;
            
						$exam_file_flg = fwrite($fp, $data);
            if($exam_file_flg) { $success['cand_exam'] = "CSC CSV File Generated Successfully."; }
            else { $error['cand_exam'] = "Error While Generating CSC CSV File."; }
            $i++;
            $exam_cnt++;
          }
          fclose($fp);
          fwrite($fp1, "Total Applications - " . $exam_cnt . "\n");
          
          // File Rename Functinality
          $oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
          $newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
          rename($oldPath, $newPath);
          $OldName = "iibfportal_" . $current_date . ".csv";
          $NewName = "iibfportal_" . date('dmYhi') . "_" . $exam_cnt . ".csv";
          $insert_info = array('CurrentDate' => $current_date, 'old_file_name' => $OldName, 'new_file_name' => $NewName, 'record_count' => $exam_cnt, 'createdon' => date('Y-m-d H:i:s'));
          $this->master_model->insertRecord('cron_csv_custom', $insert_info, true);
        } 
        else 
        {
          fclose($fp);
          $yesterday = date('Y-m-d', strtotime("- 1 day"));
          fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
          // File Rename Functinality
          $oldPath = $cron_file_dir . $current_date . "/iibfportal_" . $current_date . ".csv";
          $newPath = $cron_file_dir . $current_date . "/iibfportal_" . date('dmYhi') . "_0.csv";
          rename($oldPath, $newPath);
          $OldName = "iibfportal_" . $current_date . ".csv";
          $NewName = "iibfportal_" . date('dmYhi') . "_0.csv";
          $insert_info = array('CurrentDate' => $current_date, 'old_file_name' => $OldName, 'new_file_name' => $NewName, 'record_count' => 0, 'createdon' => date('Y-m-d H:i:s'));
          $this->master_model->insertRecord('cron_csv_custom', $insert_info, true);
          $success[] = "No data found for the date";
        }
        fclose($member_img_log);
        				
				/*************** START : SEND ENCRYPTED DATA TO API IN JSON FORMAT ********************************/
				$api_data_new_arr['request_data'] = $api_data_arr;
				if(count($api_data_new_arr) > 0)
				{
					$curl_url = 'https://iibf.cscexams.in/backend/web/user/getstudentsapi';
					
					/* echo "<br>******************* ARRAY ***************************************************************************";
					echo "<pre>"; print_r($api_data_new_arr); echo "</pre>"; //exit; */
					
					echo "<br><br>******************* JSON ***************************************************************************<br><br>";
					$api_json = json_encode($api_data_new_arr);
					echo $api_json; //exit;
					
					
					echo "<br><br>******************* ENCODED JSON ***************************************************************************<br><br>";
					$api_json_final = $encryObj->encrypts_dat($api_json,$EncryptKey);
					echo $api_json_final; //exit;					
					
					echo "<br><br><br><br>******************* RESPONSE ***************************************************************************<br><br>";
					/* $ch = curl_init($curl_url);                                                                      
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
					curl_setopt($ch, CURLOPT_POSTFIELDS, $api_json);                                                                  
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				 
					echo $result = curl_exec($ch); 
					curl_close($ch);
				
					$response = (json_decode($result, true)); 
					echo "<pre>"; print_r($response); echo "</pre>"; 
					echo "<br><br><br>"; */
					
					$ch = curl_init($curl_url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLINFO_HEADER_OUT, true);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $api_json_final);
					 
					// Set HTTP Header for POST request 
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
							'Access-Control-Allow-Origin: *',
							'Authorization: Bearer Tectoken',
							'Content-Type: application/json; charset=UTF-8',
							'Access-Control-Allow-Methods: POST',
							'Access-Control-Max-Age: 3600',
							'Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With',
							'Content-Length: ' . strlen($api_json))
					); 
					 
					// Submit the POST request
					echo $result = curl_exec($ch);
					
					// Check for errors and display the error message
					if($errno = curl_errno($ch)) 
					{
							$error_message = curl_strerror($errno);
							echo "Error ({$errno}):\n {$error_message}";
					}
					 
					// Close cURL session handle
					curl_close($ch);
					
					echo "<br><br>*********************************************************************************************************";
				}
				/*************** END : SEND ENCRYPTED DATA TO API IN JSON FORMAT ********************************/
        
        $end_time = date("Y-m-d H:i:s");
        $result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
        $desc = json_encode($result);
        $this->log_model->cronlog("CSC CSV Cron Execution End", $desc);
        fwrite($fp1, "\n" . "**************** CSC CSV Cron Execution End " . $end_time . " *****************" . "\n");
        fclose($fp1);
      }
    }
		
		function test()
		{
			ini_set("memory_limit", "-1");
			error_reporting(E_ALL);
      ini_set('display_errors', 1);
			
			/**** ADDED CODE BY SAGAR FOR ENCRYPTION : 09-07-2020 ****/
			include(FCPATH."/BridgePG/Crypt_lib.php");
			$encryObj = new Crypt_lib();
			$EncryptKey = 'c+cNh7y44v4wLjb30U2xwcbimGz0ci4VoSxmQvhC94o=';	
			
			$json_data = 'wa2HYD0dnSn/B/nWj2zRGmLES6Ry+hZhPG/j7tBRNS2K1tE4ObLyE+ShPrkKRGvKBOaT1g+FmiLk71T/9KZ7fx0owmNKP7bgF4BbIUlkj0GkV/+zR5b7CUN08HzYaOi3kcWLcCbiwEK/w2h0yL2Be0UkLB0+6dF6NKRVg99FztLcEIT+15BkcoPwanpV6/wEIS7WNnLJutUQ5NMOh6erhtr6eSHMtcJvwLpEz3hDqWUq2g7b4qqU0ivZJqRT9OaVDThUYAAf7zCajUgGAS2L/9KiWMmbUddMsxE6mUV2qlV+ZXdQfAYWAUy90OdlQvwIhXdSkQknNo7aP9Ni0HcIzhZOlmFuS2Ou+Gv4rqWO0WF+A5tLcV3HBE7B2t0s2R3TWqdnyBmjg4L9emrAeaCJDO3uX8nD24t8b+s3XbQuS2gqpwZ5gJIkGlyGs7PWU1jf41q4uLCGyElfNTCn9eBpxEaCdnrEvuTU/mRcuncjStPBOXbW0Ae21LUbHIjfcGDMskfU6XEnWY666ct0da3+vqpFUkoq+KDQl/TlGuS4xiOZkm56uP3K6+HGg0pKc/fQKSHAQNe0LDExB1iU+cdjjgZ6LXNly4yBL/QhnK9mzLV9pLLbPgf+jkh8T+qjL+AsoARNZDKzfHcKdMmTtxjUVjQiSLzugF1eajhz+wqXVSCzIbxmyHje5ypGudIBN4cpXRM4cOBphNGRMb++MNHpZCm/LmAQmcK+x2aaPSQ9udoTpZH6pXi9EqvuTiSMeGmhckk+DID8oMPef640NrwLe4zGLpQNHtmQIvULCoPSghCa8w0ToPs76JNbHJATkvSkG3InlJpPbVzP6UkH2O68zNSD4+ymSAszPyxIrRYLNiwpEwIKsy6iSc+6BDaZaVz1uuX0pfeVWlmRnIc8BeBTIMYTuQi72Dyy9eVItTiYdfvl6H5vCWKz2vywvOIJQ3b1SKkHBfEto2RGalzJGtzTx+5rVRfyGsbhkG4HLrTZ3Xqbm5W+wkthBASB5mcEvCX42a9pzxoxlEkPG7BKFXPAvG4q1DUOnYKIH7HDZsvWkatbavaNBxia0KxxNL7oTZV1mb6bqbM1fMjy0IPckpcPgolBCCdMzSrsrT4FjL4m0diMKv7ig+ciSK+XwUzU78XGgwWzAuVOKzzAQL5JdaG/PUqYQ7gJAkv5NYwzrwh0vq+7oHxBFWHMXh+r6wLgB7gNLeWyHdKHLvSKKu2nK1Ae5pz7KtGyp7WoYDDWDIvMxNEJ4SfamZXVjwFy7mVjosSurgmiOoN4uYUoKl3ROOy9Tmfewddonf3ALArX6zAbM/VJmznr9TF3v/nb3eFNNjg7AsbCMGosbN2uB/FcqwunWVcgaVWw9AHVWGy6ODSwsc5GpWZXyTShmvhpkswZLQRLme3/OY+eFWcHLXjTIF5FMqqadLQDnCF9iS8f17MyzIYIN0snfuYlHIqrvMcuEZTZfMRWLaeBMzkYXhtn4Sc0Tpix46YNTxxR3J3iVSQp5DLuKBxrtMvleGniJVCYV1OpNz+tlKeRP/nx8EMnb2rsOLQhUoZ106cq2Aoh+5Gb9ds3qJaytt5WeBWeiAFE7qPWhXn/a9FoDklELi9/kCuUwavklrYUg65yPcrbabErgAimBJtNnPD8noQ4ORmXacP98LZaj0nZBRz4FsawOZITK5BZJxvweR0wJ9AJSH2ID/h+807J8kZGaqrGLsW8NKMpZGB7YQcP1/UYnZDWQ3cXmzLpL2ag3/s3WYg69ygQydRvgeMTUJd0bkIpIiKIOJXMeDGxw2l6Ou0rgkSebdTvbqddMkQNXPNuy05KfpUTrxPxXsXxma7a+dnOEqVnl6pJzkjOfm/oK7TeXjjBT+fc1/hWhbkCgjoe3fjeP5lkrTbQxWM8GlTJaIg58y0k7s+vhJRZjUqqhbfNwCo/G0i98dh8zDh5i+zwm27j+3uh1Dpmd7HthPFbWncATDQvt7ClV5UzuMo6G5SrMP2JqsD91PwXDanT5kzaYWbqiNz+Y3YnLJfb8gJsu5x4oIkNFeMXwSvAc9CDuK0S1VAa5xyTv/fweodYR7qTJ/ARhj4bKD+G82GM2PDzn0mKJ/zvCy0K4cjtGatWUIdlIXEd12TtsaRpLfX5s2st81Iunrt6KYz6ppMx7l9ovvg2Da6GnMe5U9Twit2LFqv+aG+osW8W+/9BYAfGOxjHPkO+YHPBmZbbdYLGPT5zTHCQ+cT0y2RYtY7jvEOtS3klrGH4OI+VwatNWFKPX2v3Nb2fe3v+DxqNfjlPc02OY41d7c9Q5zhZzrWePdmmgd/ArWog/BP8l5fMSKdkwuKORvvYft1xZKfLyLlEXtWJ1B6QxmXA5tStcewxNV6IWzN8eLJx3hT4qDacIiw3l9C08YAuXLM2OZVZT+GRgOqc013PmOTthHTBM31xXEzzj/wfVvIH2AnHmYwNNLLsszTEVM15VncG5yvwYC/4UupGKMr50TuucNklZ5C6QORlhDZ09bO3GmguZQY1qrINnYycT3n4//BaZST+uXTai11A71JSHV18IewFZ03ePsPm5cJ/Me7H4EHiZXjnn5SShmCNOYRifkO/AeJX6+Z7MEZ6bL7wbd5HinrlPUMEAN1/x5dyETgG2ThS/hrRIvUdrfxpNKl2XZwk/2RIuzFcRn07UierLyFDdCYfzSOnO9XB1vWKPkeFGRzeQl5SX7fxO2LR8c4heGTl18O1/Vwyk1sQd5RrrYgl5/oqcXwBnIIqfaHzV30Wq1qsXqwLxQVuxHmhsRGsVMN6CauPDV3rJX5T6C0A/D1Zc7WBMqHC69eIk1a/x3qrCSN0DsZRaUY8mKATDDVqHllb+o/93VzUxaoVImHyuf9UE+XWVE8vcXkHV815k2zE9+jHADUzVtIG2hjYWfOHQOB2GZhnhCez/xo6CM56sRZ3kBgmwzBjc86HJXp+hQWGinblfqkWXpNuSOyIZ2xX7K6fba/js1qxDwCka9oXx6E2rynoQjHaoYqZBabXpV/dQ4ogE+DJnk12JITZjQK9D25854I/vP4Lq5pB25NVBoH9xNVMmtow7qCdJfdQSEDSpr6WKWIxEwgmXMVz2VVFm1Q/i+ohCc1p+OLKOrDIwc6rTxJjnktO6m8u4xh16I7MSDUdMByoHPnBIuy9Frq9dPyPWpxpVC8iO8RHIle8nmnitBjshWpIygFsLMVAn45AmB1HJ037L6zCaH5wF1exelNn1Bwmuul14D4M72TrfV1WzcCe6+LAhqzU/q676loXqXDddMgfrf7cKng9+0LBilYqxgBQwLKbBiN4wIRX19H5KopaGeUWSw8K8c1RSgksvhnlxDYzs8vTADRK/2tmhKJG5QzPDiOcqRy9lER5rTaMjxBJoB5RPilrUKMLRx+sVHJkDKqpxW9r1Ot9ukdyCp+gMJGd3+zB2TM2GHperF5pnBIVv3TtP/N2eV56okpwReEQbJVyMYg8XrHUrG4mZziUAN6HH8U/2e7/MN/9rhEeO+yTxLo+pMKE5jfIQ/RslYDEQPbEMJ2PsO2VTyCzjA/3n2Eu3tJ1Frtrg9hf16nQ7zy8xB/xC753Sd5TReV2oI5+B+1uy5/3zLbTJo45LuxRuGGNHOM9zh2nqMsOfyDnsnTwNXC9Y7fda2nTinMCsUwE/kQOl3Wnq1vnMOA7XxIJN63PxgE071WncMN+Jexj7sZSe7SXttHDpmLVv1TrTpYPxw8O6NtwRD1l/Cy8qzNZ3Y2pGG1ly1WZctkfHPyvn6t+gdG4wpeErrG7nQ8qupmNojLapVAFgTSHt8xD7s7QdpA=';
			$decrypted_json = json_decode($encryObj->decrypts_dat($json_data, $array = FALSE, $EncryptKey),true);
			echo "<pre>"; print_r($decrypted_json);
		}
    
		function api_post_encrypted_data()
    {
      ini_set("memory_limit", "-1");
			
			include(FCPATH."/BridgePG/Crypt_lib.php");
			$encryObj = new Crypt_lib();
			$EncryptKey = '7Rad05IPnpmq9w0I'; //c+cNh7y44v4wLjb30U2xwcbimGz0ci4VoSxmQvhC94o=
      echo "<br>Encrypt key : 7Rad05IPnpmq9w0I";
      echo "<br>******************* Posted URL ***************************************************************************<br>";
      echo $curl_url = 'https://uat.cscexams.in/cscLogin/iibf2RegApi.obj';
      
      /***** POST DATA TO API : CODE ADDED BY SAGAR ON 13-07-2020 *******/
      echo "<br><br><br>******************* Original Data ***************************************************************************";
      echo "<br>first_name : ".$first_name = 'MOHAN'; //firstname
      echo "<br>middle_name : ".$middle_name = 'CHAND'; //middlename
      echo "<br>last_name : ".$last_name = 'NALLURI'; //lastname
      echo "<br>email_id : ".$email_id = 'demouser@gmail.com'; //email mohannalluri81@gmail.com
      echo "<br>password : ".$password = 'WK72GQ'; //pwd
      echo "<br>member_number : ".$member_number = '510329777'; //regnumber
      echo "<br>gender : ".$gender = 'M'; //gender
      echo "<br>dob : ".$dob = '1981-03-19'; //dateofbirth
      echo "<br>mobile : ".$mobile = '9381845355'; //mobile
      echo "<br>registration_date : ".$registration_date = '2019-05-29'; //registration_date
      echo "<br>exam_code : ".$exam_code = '991'; //exam_code
      echo "<br>state : ".$state = 'TEL'; //state
      
      $select = 'regnumber';
      $this->db->where_in('exam_code', $exam_code);
      $this->db->where_in('regnumber', $member_number);
      $attempt_count = $this->Master_model->getRecords('member_exam', array('pay_status' => 1), $select);
      $attempt_count = count($attempt_count);
      $attempt_count = $attempt_count - 1;      
      echo "<br>attempt : ".$attempt = $attempt_count;
      echo "<br>exam_date : ".$exam_date = '2019-06-03'; //exam_date
      echo "<br>batch_start_time : ".$batch_start_time = '2019-06-03 10:00:00'; //time
      echo "<br>exam_medium : ".$exam_medium = 'A'; //exam_medium
      
      $member_images = $this->get_member_images('', '',$member_number, 'p_510329777.jpg', 'pr_510329777.jpg', 's_510329777.jpg');
      $scannedphoto = $member_images['scannedphoto'];
      $scannedsignaturephoto = $member_images['scannedsignaturephoto'];
      $idproofphoto = $member_images['idproofphoto'];      
      echo "<br>student_photo : ".$student_photo = $scannedphoto;
      echo "<br>student_signature : ".$student_signature = $scannedsignaturephoto;
      echo "<br>student_document_photo : ".$student_document_photo = $idproofphoto;
      echo "<br>subject : ".$subject = '';
            
      $post_field_arr['first_name'] = $encryObj->encrypts_dat($first_name,$EncryptKey);
      $post_field_arr['middle_name'] = $encryObj->encrypts_dat($middle_name,$EncryptKey);
      $post_field_arr['last_name'] = $encryObj->encrypts_dat($last_name,$EncryptKey);
      $post_field_arr['email_id'] = $encryObj->encrypts_dat($email_id,$EncryptKey);
      $post_field_arr['password'] = $encryObj->encrypts_dat($password,$EncryptKey);
      $post_field_arr['member_number'] = $encryObj->encrypts_dat($member_number,$EncryptKey);
      $post_field_arr['gender'] = $encryObj->encrypts_dat($gender,$EncryptKey);
      $post_field_arr['dob'] = $encryObj->encrypts_dat($dob,$EncryptKey);
      $post_field_arr['mobile'] = $encryObj->encrypts_dat($mobile,$EncryptKey);
      $post_field_arr['registration_date'] = $encryObj->encrypts_dat($registration_date,$EncryptKey);
      $post_field_arr['exam_code'] = $encryObj->encrypts_dat($exam_code,$EncryptKey);
      $post_field_arr['state'] = $encryObj->encrypts_dat($state,$EncryptKey);
      $post_field_arr['attempt'] = $encryObj->encrypts_dat($attempt,$EncryptKey);
      $post_field_arr['exam_date'] = $encryObj->encrypts_dat($exam_date,$EncryptKey);
      $post_field_arr['batch_start_time'] = $encryObj->encrypts_dat($batch_start_time,$EncryptKey);
      $post_field_arr['exam_medium'] = $encryObj->encrypts_dat($exam_medium,$EncryptKey);
      $post_field_arr['student_photo'] = $encryObj->encrypts_dat($student_photo,$EncryptKey);
      $post_field_arr['student_signature'] = $encryObj->encrypts_dat($student_signature,$EncryptKey);
      $post_field_arr['student_document_photo'] = $encryObj->encrypts_dat($student_document_photo,$EncryptKey);
      $post_field_arr['subject'] = $encryObj->encrypts_dat($subject,$EncryptKey);
      
      echo "<br><br><br>******************* Original Encrypted Data ***************************************************************************";
      echo "<pre>"; print_r($post_field_arr); echo "</pre>"; 
      
      $fields_string = "";      
      foreach($post_field_arr as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
      rtrim($fields_string, '&');
      
      echo "<br><br>******************* Posted Data String ***************************************************************************<br>";
      echo $fields_string;
      
      /* first_name:38058e52e0ecdfff800f9e63c6f8ec18
      middle_name:
      last_name:
      email_id:f8f082ac754559e0f6a1facd54812eae33c0e3f175f603b8bd54e3bb0dcf6e80
      password:c5552bf17279d1a657d8cdc69516ca82
      member_number:b016581c826cc862862d1838e6347447
      gender:3e679f385505936531c4b2677369977e
      mobile:5774522b36cfa12bff0bfb0b500d1575
      registration_date:ca89e5dfc7b1e8c77993364e575827d6
      exam_code:f44c85a262160b83d76b0bec1728c9a9
      state:41eeafa690198780062184859ec900cf
      attempt:c3b611225094436dd0906274c3f7f3ae
      exam_date:3b84e5eb790c51e7dc16ab0891c4b48a
      batch_start_time:0a6c7477812d83f67c5fc4d37508d843fbabfe767f1ae62bbb4cf2e5d511e830
      exam_medium:f44c85a262160b83d76b0bec1728c9a9
      student_photo:1021fd72cfd8ad675a18c3f211b81d826e762844b2ed93e9d08427d33781a5211e096de1b9ab35c3245595289afae2f4a172d251d470fd371859978bff0d4cc8bd545643296e743667d7b5697cd2c65c7d8ca4bd0f99f30beaf0302ea97ac1d7
      student_signature:1021fd72cfd8ad675a18c3f211b81d826e762844b2ed93e9d08427d33781a5211e096de1b9ab35c3245595289afae2f4a172d251d470fd371859978bff0d4cc898d336939ae6242865e5b6bf3e9efc37
      student_document_photo:1021fd72cfd8ad675a18c3f211b81d826e762844b2ed93e9d08427d33781a5211e096de1b9ab35c3245595289afae2f4a172d251d470fd371859978bff0d4cc8bd545643296e743667d7b5697cd2c65c7d8ca4bd0f99f30beaf0302ea97ac1d7
      dob:07d0045167ecf74d754dd612bc01056f
      subject:b45d87a93e12b9105462362df8301e6f */
      
      $ch = curl_init($curl_url);                                                                      
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
      curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);                                                                  
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     
      $result = curl_exec($ch); 
      curl_close($ch);
      
      echo "<br><br><br><br>******************* Response ***************************************************************************<br>";
      echo "<textarea disabled style='width: 100%;height: 400px;margin-bottom: 40px;resize: vertical;'>".$result."</textarea>";
    }
    
		function api_post_encrypted_data_new()
    {
      ini_set("memory_limit", "-1");
			
			include(FCPATH."/BridgePG/AES128BitEncryption.php");
			
			echo "<br>******************* Encryption Key ***************************************************************************<br>";
			echo "7Rad05IPnpmq9w0I <br><br>";
			$secretKey = hex2bin(md5('7Rad05IPnpmq9w0I')); ////c+cNh7y44v4wLjb30U2xwcbimGz0ci4VoSxmQvhC94o=
			$blockSize = 128;
			$aes = new AES($secretKey, $blockSize);	
			
			echo "<br>******************* Posted URL ***************************************************************************<br>";
      echo $curl_url = 'https://uat.cscexams.in/cscLogin/iibf2RegApi.obj';
      
      /***** POST DATA TO API : CODE ADDED BY SAGAR ON 13-07-2020 *******/
      echo "<br><br><br>******************* Original Data ***************************************************************************";
      echo "<br>first_name : ".$first_name = 'Demo User'; //firstname
      echo "<br>middle_name : ".$middle_name = ''; //middlename
      echo "<br>last_name : ".$last_name = ''; //lastname
      echo "<br>email_id : ".$email_id = 'deemouser@gmail.com'; //email mohannalluri81@gmail.com
      echo "<br>password : ".$password = '1234'; //pwd
      echo "<br>member_number : ".$member_number = '10002'; //regnumber
      echo "<br>gender : ".$gender = 'M'; //gender
      echo "<br>dob : ".$dob = '2019-07-23'; //dateofbirth
      echo "<br>mobile : ".$mobile = '8156476865'; //mobile
      echo "<br>registration_date : ".$registration_date = '2019-05-09'; //registration_date
      echo "<br>state : ".$state = 'UP'; //state
      
			$exam_code = 'S_110';
      $select = 'regnumber';
      $this->db->where_in('exam_code', $exam_code);
      $this->db->where_in('regnumber', $member_number);
      $attempt_count = $this->Master_model->getRecords('member_exam', array('pay_status' => 1), $select);
      $attempt_count = count($attempt_count);
      $attempt_count = $attempt_count - 1;      
      echo "<br>attempt : ".$attempt = 1; //$attempt_count;
      echo "<br>exam_date : ".$exam_date = '2020-07-11'; //exam_date
      echo "<br>batch_start_time : ".$batch_start_time = '2020-07-11 10:30:00'; //time
      echo "<br>exam_medium : ".$exam_medium = 'S_110'; //exam_medium
      echo "<br>exam_code : ".$exam_code; //exam_code
      
      $member_images = $this->get_member_images('', '',$member_number, 'p_510329777.jpg', 'pr_510329777.jpg', 's_510329777.jpg');
      $scannedphoto = $member_images['scannedphoto'];
      $scannedsignaturephoto = $member_images['scannedsignaturephoto'];
      $idproofphoto = $member_images['idproofphoto'];      
      echo "<br>student_photo : ".$student_photo = 'https://img.nielitexam.in/images/aadaarImages/127068881001734993foryourimage.jpg';//$scannedphoto;
      echo "<br>student_signature : ".$student_signature = 'https://img.nielitexam.in/images/aadaarImages/127068881001734993forIDimage.jpg';//$scannedsignaturephoto;
      echo "<br>student_document_photo : ".$student_document_photo = 'https://img.nielitexam.in/images/aadaarImages/127068881001734993foryourimage.jpg';//$idproofphoto;
      echo "<br>subject : ".$subject = 'Cyber';
			
			$post_field_arr['first_name'] = ($first_name == '' ? $first_name : $aes->encrypt(strval($first_name)));
      $post_field_arr['middle_name'] = ($middle_name == '' ? $middle_name : $aes->encrypt(strval($middle_name)));
      $post_field_arr['last_name'] = ($last_name == '' ? $last_name : $aes->encrypt(strval($last_name)));
      $post_field_arr['email_id'] = ($email_id == '' ? $email_id : $aes->encrypt(strval($email_id)));
      $post_field_arr['password'] = ($password == '' ? $password : $aes->encrypt(strval($password)));
      $post_field_arr['member_number'] = ($member_number == '' ? $member_number : $aes->encrypt(strval($member_number)));
      $post_field_arr['gender'] = ($gender == '' ? $gender : $aes->encrypt(strval($gender)));
      $post_field_arr['dob'] = ($dob == '' ? $dob : $aes->encrypt(strval($dob)));
      $post_field_arr['mobile'] = ($mobile == '' ? $mobile : $aes->encrypt(strval($mobile)));
      $post_field_arr['registration_date'] = ($registration_date == '' ? $registration_date : $aes->encrypt(strval($registration_date)));
      $post_field_arr['state'] = ($state == '' ? $state : $aes->encrypt(strval($state)));
      $post_field_arr['attempt'] = ($attempt == '' ? $attempt : $aes->encrypt(strval($attempt)));
      $post_field_arr['exam_date'] = ($exam_date == '' ? $exam_date : $aes->encrypt(strval($exam_date)));
      $post_field_arr['batch_start_time'] = ($batch_start_time == '' ? $batch_start_time : $aes->encrypt(strval($batch_start_time)));
      $post_field_arr['exam_medium'] = ($exam_medium == '' ? $exam_medium : $aes->encrypt(strval($exam_medium)));
      $post_field_arr['exam_code'] = ($exam_code == '' ? $exam_code : $aes->encrypt(strval($exam_code)));
			$post_field_arr['student_photo'] = ($student_photo == '' ? $student_photo : $aes->encrypt(strval($student_photo)));
			$post_field_arr['student_signature'] = ($student_signature == '' ? $student_signature : $aes->encrypt(strval($student_signature)));
			$post_field_arr['student_document_photo'] = ($student_document_photo == '' ? $student_document_photo : $aes->encrypt(strval($student_document_photo)));
			$post_field_arr['subject'] = ($subject == '' ? $subject : $aes->encrypt(strval($subject)));
			      
      echo "<br><br><br>******************* Original Encrypted Data ***************************************************************************";
      echo "<pre>"; print_r($post_field_arr); echo "</pre>"; 
      
      $fields_string = "";      
      foreach($post_field_arr as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
      rtrim($fields_string, '&');
      
      echo "<br><br>******************* Posted Data String ***************************************************************************<br>";
      echo $fields_string;
      
			$ch = curl_init($curl_url);                                                                      
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
      curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);                                                                  
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     
      $result = curl_exec($ch); 
      curl_close($ch);
      
      echo "<br><br><br><br>******************* Response ***************************************************************************<br>";
      //echo "<textarea disabled style='width: 100%;height: 400px;margin-bottom: 40px;resize: vertical;'>".$result."</textarea>";
      echo $result;
    }
    
		
    /********** CODE ADDED BY SAGAR ON 28-06-2020 FOR MEMBER IMAGES ****************/
    public function get_member_images($image_path='', $reg_no='', $regnumber='', $scannedphoto='', $idproofphoto='', $scannedsignaturephoto='')
    {
      $db_img_path = $image_path; //Get old image path from database
      $scannedphoto_res = $idproofphoto_res = $scannedsignaturephoto_res = '';
      
      if($scannedphoto != "" && file_exists(FCPATH."uploads/photograph/".$scannedphoto)) //Check photo in regular folder
      { 
        $scannedphoto_res = base_url()."uploads/photograph/".$scannedphoto; 
      }
      else if($db_img_path != "") //Check photo in old image path
      { 
        if($reg_no != "" && file_exists(FCPATH."uploads".$db_img_path."photo/p_".$reg_no.".jpg"))
        {
          $scannedphoto_res = base_url()."uploads".$db_img_path."photo/p_".$reg_no.".jpg"; 
        }
        else if($regnumber != "" && file_exists(FCPATH."uploads".$db_img_path."photo/p_".$regnumber.".jpg"))
        {
          $scannedphoto_res = base_url()."uploads".$db_img_path."photo/p_".$regnumber.".jpg"; 
        }
      }
      else  //Check photo in kyc folder          
      {
        if($reg_no != "" && file_exists(FCPATH."uploads/photograph/k_p_".$reg_no.".jpg"))
        {
          $scannedphoto_res = base_url()."uploads/photograph/k_p_".$reg_no.".jpg"; 
        }
        else if($regnumber != "" && file_exists(FCPATH."uploads/photograph/k_p_".$regnumber.".jpg"))
        {
          $scannedphoto_res = base_url()."uploads/photograph/k_p_".$regnumber.".jpg"; 
        }
      }
      
      if ($idproofphoto != "" && file_exists(FCPATH."uploads/idproof/".$idproofphoto)) //Check id proof in regular folder
      { 
        $idproofphoto_res = base_url()."uploads/idproof/".$idproofphoto; 
      }
      else if($db_img_path != "") //Check id proof in old image path
      { 
        if($reg_no != "" && file_exists(FCPATH."uploads".$db_img_path."idproof/pr_".$reg_no.".jpg"))
        {
          $idproofphoto_res = base_url()."uploads".$db_img_path."idproof/pr_".$reg_no.".jpg"; 
        }
        else if($regnumber != "" && file_exists(FCPATH."uploads".$db_img_path."idproof/pr_".$regnumber.".jpg"))
        {
          $idproofphoto_res = base_url()."uploads".$db_img_path."idproof/pr_".$regnumber.".jpg"; 
        }
      }
      else //Check photo in kyc folder
      {
        if($reg_no != "" && file_exists(FCPATH."uploads/idproof/k_pr_".$reg_no.".jpg"))
        {
          $idproofphoto_res = base_url()."uploads/idproof/k_pr_".$reg_no.".jpg"; 
        }
        else if($regnumber != "" && file_exists(FCPATH."uploads/idproof/k_pr_".$regnumber.".jpg"))
        {
          $idproofphoto_res = base_url()."uploads/idproof/k_pr_".$regnumber.".jpg"; 
        }
      }
      
      if ($scannedsignaturephoto != "" && file_exists(FCPATH."uploads/scansignature/".$scannedsignaturephoto)) //Check signature in regular folder
      { 
        $scannedsignaturephoto_res = base_url()."uploads/scansignature/".$scannedsignaturephoto; 
      }
      else if($db_img_path != "") //Check signature in old image path
      { 
        if($reg_no != "" && file_exists(FCPATH."uploads".$db_img_path."signature/s_".$reg_no.".jpg"))
        {
          $scannedsignaturephoto_res = base_url()."uploads".$db_img_path."signature/s_".$reg_no.".jpg"; 
        }
        else if($regnumber != "" && file_exists(FCPATH."uploads".$db_img_path."signature/s_".$regnumber.".jpg"))
        {
          $scannedsignaturephoto_res = base_url()."uploads".$db_img_path."signature/s_".$regnumber.".jpg"; 
        }
      }
      else //Check signature in kyc folder
      {
        if($reg_no != "" && file_exists(FCPATH."uploads/scansignature/k_s_".$reg_no.".jpg"))
        {
          $scannedsignaturephoto_res = base_url()."uploads/scansignature/k_s_".$reg_no.".jpg"; 
        }
        else if($regnumber != "" && file_exists(FCPATH."uploads/scansignature/k_s_".$regnumber.".jpg"))
        {
          $scannedsignaturephoto_res = base_url()."uploads/scansignature/k_s_".$regnumber.".jpg"; 
        }
      }
      
      $data['scannedphoto'] = $scannedphoto_res;
      $data['idproofphoto'] = $idproofphoto_res;
      $data['scannedsignaturephoto'] = $scannedsignaturephoto_res;
      return $data;
    }
    
    
		/* TESTING EMAIL SENDING FUNCTION  */
		function send_mail($from_mail='', $to_email='', $subject='', $mail_data='', $view_flag='')
		{
			if($from_mail != '' && $to_email != '' && $subject != '' && $mail_data != '')
			{
				if($view_flag=='1')
				{
					echo "<br>From = ".$from_mail;
					echo "<br>To = ".$to_email;				
					echo "<br>subject = ".$subject;
					echo "<br>message = ".$mail_data; exit;
				}
				
				$this->load->library('email');
				//$config['protocol'] = 'sendmail';
				//$config['mailpath'] = '/usr/sbin/sendmail';
				$config['charset'] = 'iso-8859-1';
				$config['charset'] = 'UTF-8';
				$config['wordwrap'] = TRUE;
				$config['mailtype'] = 'html';
				$this->email->initialize($config);
				//$this->email->subject($subject." php mail");
				
				$this->email->from($from_mail);
				$this->email->to($to_email);
				$this->email->subject($subject);
				$this->email->message($mail_data);
				
				if(@$this->email->send())
				{
					$final_msg = 'success';
				}
				else
				{
					$final_msg = 'error. Email not send<br>';
					$final_msg .= $this->email->print_debugger();
				}
				
				return $final_msg;
				$this->email->clear();				
			}
			else
			{
				return 'error - invalid form fields';
			}
		}
		
	}
