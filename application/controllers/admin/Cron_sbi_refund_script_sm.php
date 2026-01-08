<?php 
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Cron_sbi_refund_script_sm extends CI_Controller
  {
		public function __construct()
    {
      parent::__construct();
      $this->load->model('Master_model');
      $this->load->model('log_model');
      $this->load->model('Emailsending');
      //error_reporting(E_ALL);
      //ini_set('display_errors', 1);
		}
    
    public function index() 
		{
			echo "Welcome to sbi refund script";
			//phpinfo();
		}
		
		//GENERATE CSV FILE FOR PENDING REFUND RECORDS AND MAIL IT TO SBI TEAM
    public function generate_sbi_refund_csv()
    {
      ini_set("memory_limit", "-1");
      $csv_file_flag = 0;
      $success_log = $error_log = array();
      $start_time = date("Y-m-d H:i:s");
      $current_date = date("Ymd");
      $cron_file_main_dir = "./uploads/rahultest/sbi_refund_cron/";
			if (!file_exists($cron_file_main_dir)) { mkdir($cron_file_main_dir, 0700); }
			
      $cron_file_dir = "./uploads/rahultest/sbi_refund_cron/sbi_refund_status_csv_by_iibf/";
			if (!file_exists($cron_file_dir)) { mkdir($cron_file_dir, 0700); }
			
			$crone_log_arr['success'] = '';
			$crone_log_arr['error'] = '';
			$crone_log_arr['Start Time'] = $start_time;
			$crone_log_arr['End Time'] = '';
			$this->log_model->cronlog("SBI Refund CSV Cron Execution Start", json_encode($crone_log_arr));
			
      if(!file_exists($cron_file_dir.$current_date)) { mkdir($cron_file_dir.$current_date, 0700); }
      
      if(file_exists($cron_file_dir.$current_date)) 
      {
        $cron_file_path = $cron_file_dir.$current_date; // Path with CURRENT DATE DIRECTORY
        
				$csv_file_name = "iibfportal_sbi_refund_".$current_date.".csv";
        $log_file_name = "sbi_refund_logs_".$current_date.".txt";
        $csv_file_opr = fopen($cron_file_path.'/'.$csv_file_name, 'w');
        $log_file_opr = fopen($cron_file_path.'/'.$log_file_name, 'a');       
        fwrite($log_file_opr, "\n**************** SBI Refund CSV Cron Execution Started - ".$start_time." **************** \n");				
				/* $yesterday = date('Y-m-d', strtotime("- 1 day")); $yesterday = '2020-06-18'; */
        
        $select = 'id, receipt_no, transaction_no, transaction_date, transaction_amount, refund_date, sbi_refund_status, sbi_refund_date';
				$whr_arr['LENGTH(transaction_no)'] = 13;
				$whr_arr['req_status'] = 5;
				$whr_arr['refund_date != '] = '0000-00-00';
				$whr_arr['credit_note_number'] = '0';
				$whr_arr['sbi_refund_status'] = '0';
				$sbi_refund_data = $this->Master_model->getRecords('maker_checker', $whr_arr, $select);
        //echo $this->db->last_query(); exit;
        
				$cnt = $success_cnt = $error_cnt = 0; 				
        if(count($sbi_refund_data)) 
        {					         
					// Column headers for CSV            
          $csv_column_headers = "Order_Id,Transaction_Date,Transaction_Amount,Refund_Initiate_Date,SBI_Refund_Status,Sbi_Refund_Date \n";
          $csv_file_flag = fwrite($csv_file_opr, $csv_column_headers);
          
					foreach ($sbi_refund_data as $res) 
          {
            $receipt_no = $transaction_date = $transaction_amount = $refund_initiate_date = $sbi_refund_status = $sbi_refund_date = $csv_file_data = '';
            $receipt_no = $res['receipt_no'];
            $transaction_date = $res['transaction_date'];
            $transaction_amount = $res['transaction_amount'];
            $refund_initiate_date = $res['refund_date'];
            $sbi_refund_status = '';//$res['sbi_refund_status'];
            $sbi_refund_date = '';//$res['sbi_refund_date'];
            
            $csv_file_data = ''.$receipt_no.','.$transaction_date.','.$transaction_amount.','.$refund_initiate_date.','.$sbi_refund_status.','.$sbi_refund_date. "\n";
            
            $csv_file_flag = fwrite($csv_file_opr, $csv_file_data);
            if($csv_file_flag) { $success_cnt++; } else { $error_cnt; }
						$cnt++;
          }					
        }         
					
				if($cnt == 0) 
				{ 
					fwrite($log_file_opr, "No data found \n"); 
					$success_log['msg'] = "No data found"; 
				}
				else 
				{ 
					fwrite($log_file_opr, "Total Records - " . $cnt . "\n"); 
					if($success_cnt > 0) { $success_log['msg'] = "SBI Refund CSV File Generated Successfully for ".$success_cnt." records"; }
					if($error_cnt > 0) { $error_log['msg'] = "Error While Generating SBI Refund CSV File for ".$error_cnt." records"; }
				}
          
				// File Rename Functinality
				$oldPath = $cron_file_dir.$current_date."/iibfportal_sbi_refund_".$current_date.".csv";
				$newPath = $cron_file_dir.$current_date."/iibfportal_sbi_refund_".date('dmYHis')."_".$cnt.".csv";
				fclose($csv_file_opr);
				rename($oldPath, $newPath);
          
				$OldName = "iibfportal_sbi_refund_".$current_date.".csv";
				$NewName = "iibfportal_sbi_refund_".date('dmYHis')."_".$cnt.".csv";
        
				$ins_info['CurrentDate'] = $current_date;
				$ins_info['old_file_name'] = $OldName;
				$ins_info['new_file_name'] = $NewName;
				$ins_info['record_count'] = $cnt;
				$this->master_model->insertRecord('sbi_refund_csv', $ins_info, true);				
				
        $end_time = date("Y-m-d H:i:s");
				$crone_log_arr2['success'] = $success_log;
				$crone_log_arr2['error'] = $error_log;
				$crone_log_arr2['Start Time'] = $start_time;
				$crone_log_arr2['End Time'] = $end_time;
				$this->log_model->cronlog("SBI Refund CSV Cron Execution End", json_encode($crone_log_arr2));
				
        fwrite($log_file_opr, "\n" . "**************** SBI Refund CSV Cron Execution End - " . $end_time . " *****************" . "\n");
        fclose($log_file_opr);
				
				if($cnt > 0)
				{
					//EMAIL CODE GOES HERE
					$to_email = 'iibfdevp@esds.co.in'; 
					$subject = "IIBF : SBI Refund List";
					$mail_body = '<html>
													<head><title>IIBF : SBI Refund List</title></head>
													<body>
														<div style="max-width:600px;border:1px solid #ccc;margin:10px auto;font-size:16px;line-height:18px;">
															<div style="text-align: center;padding: 15px 10px;border-bottom: 1px solid #ccc;line-height:22px;">INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>(AN ISO 21001:2018 Certified )</div>
															<div style="padding:20px;">
																<p style="margin:0 0 20px 0;">Hello Team,</p>
																<p style="margin:0 0 10px 0;line-height:22px;">Please find the attachment for '.$cnt.' refund records.</p>
																<p style="margin:18px 0 0 0;line-height: 20px;">Yours Truly,<br>IIBF Team.</p>
															</div>                            
														</div>                            
													</body>
												</html>';
					
					//echo $mail_body; exit; 
					$attachment_file = FCPATH."/uploads/rahultest/sbi_refund_cron/sbi_refund_status_csv_by_iibf/".$current_date."/".$NewName;
					$this->email->attach($attachment_file);
					$email_arr = array('to'=>$to_email, 'from'=>'noreply@iibf.org.in', 'subject'=>$subject, 'message'=>$mail_body);
					@$this->Emailsending->mailsend($email_arr);
				} 
      }
    }
		
		//READ SBI RESPONSE EMAIL FROM EMAIL AND UPDATE ALL RECORDS AS PER RECEIVED ATTACHMENT
		public function read_sbi_refund_email()
		{
			//$server_name = '{outlook.office.com/ssl}sbi_refund_received';
			$server_name = '{owa.esds.co.in/ssl}sbi_refund_received';
			$username = 'chaitali.jadhav@esds.co.in';
		$password = '&=#g*9}STux9'; 
			//$port = 993;
			
			$connection = imap_open($server_name, $username, $password);			
			$sorted_mbox = imap_sort($connection, SORTDATE, 1);
			$messages   = imap_sort($connection, SORTDATE, 1, SE_UID, 'UNSEEN');
			$totalrows = imap_num_msg($connection);
			$msgnos = imap_search($connection, 'ALL');
			$MC = imap_check($connection);
			//echo '<pre>'; print_r($MC); echo '</pre>';
			
			$update_rec_cnt = $record_found_cnt = 0;	
			//echo "<br> >> 1";
			if($MC->Nmsgs > 0)
			{
				//echo "<br> >> 2";
				$range = "1:".$MC->Nmsgs; // Get the number of emails in selected folder or inbox
				
				// Retrieve the email details of all emails from required folder
				$response = imap_fetch_overview($connection,$range); 
				$response = array_reverse($response); 
								
				foreach ($response as $key => $msg) 
				{
					//echo "<br> >> 3";
					if (array_key_exists("subject",$msg))
					{
						if(trim($msg->subject)=='SBI REFUND')
						{
							// Response : stdClass Object ( [subject] => mail subject [from] => from email name [to] => to email name [date] => Tue, 7 Jul 2020 12:27:35 +0530 [message_id] => <985ccf7c7e4d4f14a8823b7919996924@esds.co.in> [size] => 9160 [uid] => 4 [msgno] => 2 [recent] => 0 [flagged] => 0 [answered] => 0 [deleted] => 0 [seen] => 1 [draft] => 0 [udate] => 1594105055 ) 
							//echo "<br>MsgID :- ".$msg->msgno;						exit;
							
							$attachments = $this->extract_attachments($connection,$msg->msgno);
							//echo '<pre>'; print_r($attachments); echo '</pre>';
							if(!empty($attachments))
							{
								//READ ATTACHMENT FILE FROM SAVED FOLDER AND UPDATE THAT DATA INTO DATABASE TABLE
								if(file_exists($attachments))
								{
									$FileName = fopen($attachments, 'r');								
									$max_row_size = 1000;								
									$i = 0;
									$headers_arr = $result_arr = $up_data = array();			
									while(($row = fgetcsv($FileName, $max_row_size)) !== FALSE)
									{	
										if($i == 0) //First row as headers
										{
											$headers_arr = $row;
											//echo "<pre>Headers <br> "; print_r($headers_arr); echo "</pre>"; //exit;
										}
										else
										{ 
											/* if($i > 1) { break; } */
											if(count($headers_arr) == count($row)) //check if headers and values count is same or not										
											{
												for($j=0; $j < count($headers_arr); $j++)
												{
													$result_arr[$i][$headers_arr[$j]] = $row[$j];
												}
												
												//echo "<pre>result_arr <br> "; print_r($result_arr); echo "</pre>"; exit;
												"<br>Actual date : ".$result_arr[$i]['Refund Date'];
												echo "<br>order_id : ".$order_id = str_replace("x", "0", strtolower($this->escape_string($result_arr[$i]['SBIePAY Ref NO'])));
												"<br>SBI_Refund_Status : ".$SBI_Refund_Status = 'Transaction Refund'; //$this->escape_string($result_arr[$i]['STATUSCODE']);
												"<br>Sbi_Refund_Date : ".$Sbi_Refund_Date = $this->convert_date('d-m-Y', $this->escape_string($result_arr[$i]['Refund Date']));
												
												if($order_id != "" && $order_id != '0' && $SBI_Refund_Status != "" && $SBI_Refund_Status != '0' && $Sbi_Refund_Date != '' && $Sbi_Refund_Date != '0000-00-00')
												{
													$where_arr['transaction_no'] = $order_id;
													$where_arr['req_status'] = "5";
													$where_arr['refund_date !='] = "0000-00-00"; 
													$where_arr['sbi_refund_date'] = "0000-00-00";
													$where_arr['credit_note_number'] = "";
													$check_rec = $this->master_model->getRecords('maker_checker i',$where_arr);
													if(count($check_rec) > 0) 
													{  
														//echo "<br>Found : ".$order_id; $record_found_cnt++;
													
														$up_data['sbi_refund_status'] = $SBI_Refund_Status;
														$up_data['sbi_refund_date'] = $Sbi_Refund_Date;
														$up_data['sbi_refund_modified_date'] = date("Y-m-d H:i:s");
														$up_data['credit_note_date'] = $Sbi_Refund_Date;
																											
														echo "<pre>up_data <br> "; print_r($up_data); echo "</pre>";  
														//echo "<pre>where_arr <br> "; print_r($where_arr); echo "</pre>"; //exit;
														//XXX $up_qry = $this->master_model->updateRecord('maker_checker',$up_data,$where_arr);
														//XXX if($up_qry)   
														{ 
															$update_rec_cnt++; // NEED TO CHECK THIS COUNT. AS IT MUST BE INCREASE ONLY WHEN RECORD UPDATED. CURRENTLY IT IS INCRESES FOR ALL RECORDS
														}
														//echo $this->db->last_query();
														//echo "<pre>"; print_r($up_data); print_r($where_arr); echo "</pre>";
														//echo $this->db->last_query();
													}
												}
											}				
										}
										$i++;
									} 
									
									//echo "<pre>"; print_r($result_arr); echo "</pre>";exit;								
									fclose($FileName);// Close opened CSV file
									
									//Move email to read folder
									//imap_mail_move($connection, $msg->msgno, 'Sbi_refund_done');
									imap_expunge($connection);
									
									//@unlink($attachments);//Unlink the email downloaded file
								}
							}
						}
					}				
				}
			}
			
			imap_close($connection);
			echo "<br>".$update_rec_cnt." records updated";		
			echo "<br>".$record_found_cnt." found";
		}



			//READ SBI RESPONSE EMAIL FROM EMAIL AND UPDATE ALL RECORDS AS PER RECEIVED ATTACHMENT
		public function read_sbi_refund_ftp_vishal()
		{
			
					
							// Response : stdClass Object ( [subject] => mail subject [from] => from email name [to] => to email name [date] => Tue, 7 Jul 2020 12:27:35 +0530 [message_id] => <985ccf7c7e4d4f14a8823b7919996924@esds.co.in> [size] => 9160 [uid] => 4 [msgno] => 2 [recent] => 0 [flagged] => 0 [answered] => 0 [deleted] => 0 [seen] => 1 [draft] => 0 [udate] => 1594105055 ) 
							//echo "<br>MsgID :- ".$msg->msgno;						exit;
							
							$attachments = $cron_file_main_dir = "./uploads/sbi_refund_received/Consol SBI E-pay August 22.csv";;
		
						
								//READ ATTACHMENT FILE FROM SAVED FOLDER AND UPDATE THAT DATA INTO DATABASE TABLE
								if(file_exists($attachments))
								{
					
									$FileName = fopen($attachments, 'r');								
									$max_row_size = 1000;								
									$i = 0;
									$headers_arr = $result_arr = $up_data = array();			
									while(($row = fgetcsv($FileName, $max_row_size)) !== FALSE)
									{	
							
										if($i == 0) //First row as headers
										{
											$headers_arr = $row;
											//echo "<pre>Headers <br> "; print_r($headers_arr); echo "</pre>"; //exit;
										}
										else
										{ 
											/* if($i > 1) { break; } */
											if(count($headers_arr) == count($row)) //check if headers and values count is same or not										
											{
												for($j=0; $j < count($headers_arr); $j++)
												{
													$result_arr[$i][$headers_arr[$j]] = $row[$j];
												}
												
												//echo "<pre>result_arr <br> "; print_r($result_arr); echo "</pre>"; exit;
												"<br>Actual date : ".$result_arr[$i]['Refund Date'];
												echo "<br>order_id : ".$order_id = str_replace("x", "0", strtolower($this->escape_string($result_arr[$i]['SBIePAY Ref NO'])));
												"<br>SBI_Refund_Status : ".$SBI_Refund_Status = 'Transaction Refund'; //$this->escape_string($result_arr[$i]['STATUSCODE']);
												"<br>Sbi_Refund_Date : ".$Sbi_Refund_Date = $this->convert_date('d-m-Y', $this->escape_string($result_arr[$i]['Refund Date']));
												
												if($order_id != "" && $order_id != '0' && $SBI_Refund_Status != "" && $SBI_Refund_Status != '0' && $Sbi_Refund_Date != '' && $Sbi_Refund_Date != '0000-00-00')
												{
													$where_arr['transaction_no'] = $order_id;
													$where_arr['req_status'] = "5";
													$where_arr['refund_date !='] = "0000-00-00"; 
													$where_arr['sbi_refund_date'] = "0000-00-00";
													$where_arr['credit_note_number'] = "";
													$check_rec = $this->master_model->getRecords('maker_checker i',$where_arr);
													if(count($check_rec) > 0) 
													{  
														//echo "<br>Found : ".$order_id; $record_found_cnt++;
													
														$up_data['sbi_refund_status'] = $SBI_Refund_Status;
														$up_data['sbi_refund_date'] = $Sbi_Refund_Date;
														$up_data['sbi_refund_modified_date'] = date("Y-m-d H:i:s");
														$up_data['credit_note_date'] = $Sbi_Refund_Date;
																											
														echo "<pre>up_data <br> "; print_r($up_data); echo "</pre>";  
														//echo "<pre>where_arr <br> "; print_r($where_arr); echo "</pre>"; //exit;
														//XXX $up_qry = $this->master_model->updateRecord('maker_checker',$up_data,$where_arr);
														//XXX if($up_qry)   
														{ 
															$update_rec_cnt++; // NEED TO CHECK THIS COUNT. AS IT MUST BE INCREASE ONLY WHEN RECORD UPDATED. CURRENTLY IT IS INCRESES FOR ALL RECORDS
														}
														//echo $this->db->last_query();
														//echo "<pre>"; print_r($up_data); print_r($where_arr); echo "</pre>";
														//echo $this->db->last_query();
													}
												}
											}				
										}
										$i++;
									} 
									
									//echo "<pre>"; print_r($result_arr); echo "</pre>";exit;								
									fclose($FileName);// Close opened CSV file
									
									//Move email to read folder
									//imap_mail_move($connection, $msg->msgno, 'Sbi_refund_done');
									imap_expunge($connection);
									
									//@unlink($attachments);//Unlink the email downloaded file
								}
											
							
				
			

			echo "<br>".$update_rec_cnt." records updated";		
			echo "<br>".$record_found_cnt." found";
		}
		
		public function read_billdesk_refund_ftp_vishal()
		{
			
					
							// Response : stdClass Object ( [subject] => mail subject [from] => from email name [to] => to email name [date] => Tue, 7 Jul 2020 12:27:35 +0530 [message_id] => <985ccf7c7e4d4f14a8823b7919996924@esds.co.in> [size] => 9160 [uid] => 4 [msgno] => 2 [recent] => 0 [flagged] => 0 [answered] => 0 [deleted] => 0 [seen] => 1 [draft] => 0 [udate] => 1594105055 ) 
							//echo "<br>MsgID :- ".$msg->msgno;						exit;
							
							$attachments = $cron_file_main_dir = "./uploads/sbi_refund_received/Refunds - for Credit Note";;
		
						
								//READ ATTACHMENT FILE FROM SAVED FOLDER AND UPDATE THAT DATA INTO DATABASE TABLE
								if(file_exists($attachments))
								{
					
									$FileName = fopen($attachments, 'r');								
									$max_row_size = 1000;								
									$i = 0;
									$headers_arr = $result_arr = $up_data = array();			
									while(($row = fgetcsv($FileName, $max_row_size)) !== FALSE)
									{	
							
										if($i == 0) //First row as headers
										{
											$headers_arr = $row;
											//echo "<pre>Headers <br> "; print_r($headers_arr); echo "</pre>"; //exit;
										}
										else
										{ 
											/* if($i > 1) { break; } */
											if(count($headers_arr) == count($row)) //check if headers and values count is same or not										
											{
												for($j=0; $j < count($headers_arr); $j++)
												{
													$result_arr[$i][$headers_arr[$j]] = $row[$j];
												}
												
												//echo "<pre>result_arr <br> "; print_r($result_arr); echo "</pre>"; exit;
												"<br>Actual date : ".$result_arr[$i]['Refund Date'];
												echo "<br>order_id : ".$order_id = str_replace("x", "0", strtolower($this->escape_string($result_arr[$i]['PGI Ref. No.'])));
												"<br>BILLDESK_Refund_Status : ".$BILLDESK_Refund_Status = 'Transaction Refund'; //$this->escape_string($result_arr[$i]['STATUSCODE']);
												"<br>BILLDESK_Refund_Date : ".$BILLDESK_Refund_Date = $this->convert_date('d-m-Y', $this->escape_string($result_arr[$i]['Refund Date']));
												
												if($order_id != "" && $order_id != '0' && $BILLDESK_Refund_Status != "" && $BILLDESK_Refund_Status != '0' && $BILLDESK_Refund_Date != '' && $BILLDESK_Refund_Date != '0000-00-00')
												{
													$where_arr['transaction_no'] = $order_id;
													$where_arr['req_status'] = "5";
													$where_arr['refund_date !='] = "0000-00-00"; 
													$where_arr['BILLDESK_refund_date'] = "0000-00-00";
													$where_arr['credit_note_number'] = "";
													$check_rec = $this->master_model->getRecords('maker_checker i',$where_arr);
													if(count($check_rec) > 0) 
													{  
														//echo "<br>Found : ".$order_id; $record_found_cnt++;
													
														$up_data['sbi_refund_status'] = $BILLDESK_Refund_Status;
														$up_data['sbi_refund_date'] = $BILLDESK_Refund_Date;
														$up_data['sbi_refund_modified_date'] = date("Y-m-d H:i:s");
														$up_data['credit_note_date'] = $BILLDESK_Refund_Date;
																											
														echo "<pre>up_data <br> "; print_r($up_data); echo "</pre>";  
														//echo "<pre>where_arr <br> "; print_r($where_arr); echo "</pre>"; //exit;
														//XXX $up_qry = $this->master_model->updateRecord('maker_checker',$up_data,$where_arr);
														//XXX if($up_qry)   
														{ 
															$update_rec_cnt++; // NEED TO CHECK THIS COUNT. AS IT MUST BE INCREASE ONLY WHEN RECORD UPDATED. CURRENTLY IT IS INCRESES FOR ALL RECORDS
														}
														//echo $this->db->last_query();
														//echo "<pre>"; print_r($up_data); print_r($where_arr); echo "</pre>";
														//echo $this->db->last_query();
													}
												}
											}				
										}
										$i++;
									} 
									
									//echo "<pre>"; print_r($result_arr); echo "</pre>";exit;								
									fclose($FileName);// Close opened CSV file
									
									//Move email to read folder
									//imap_mail_move($connection, $msg->msgno, 'Sbi_refund_done');
									imap_expunge($connection);
									
									//@unlink($attachments);//Unlink the email downloaded file
								}
											
							
				
			

			echo "<br>".$update_rec_cnt." records updated";		
			echo "<br>".$record_found_cnt." found";
		}
		//READ SBI RESPONSE EMAIL FROM EMAIL AND UPDATE ALL RECORDS AS PER RECEIVED ATTACHMENT
		public function read_billdesk_refund_email()
		{
			//$server_name = '{outlook.office.com/ssl}sbi_refund_received';
			$server_name = '{owa.esds.co.in/ssl}sbi_refund_received';
			$username = 'pallavi.panchal@esds.co.in';
			$password = 'DSQQs([JGM5T';
			//$port = 993;
			
			$connection = imap_open($server_name, $username, $password);
			if($connection){
				print("Connection established....");
			 } else {
				print("Connection failed");
			 } exit;
				
			$sorted_mbox = imap_sort($connection, SORTDATE, 1);
			$messages   = imap_sort($connection, SORTDATE, 1, SE_UID, 'UNSEEN');
			$totalrows = imap_num_msg($connection);
			$msgnos = imap_search($connection, 'ALL');
			$MC = imap_check($connection);
			echo '<pre>'; print_r($MC); echo '</pre>';
			
			$update_rec_cnt = $record_found_cnt = 0;	
			echo "<br> >> 1";
			if($MC->Nmsgs > 0)
			{
				echo "<br> >> 2";
				$range = "1:".$MC->Nmsgs; // Get the number of emails in selected folder or inbox
				
				// Retrieve the email details of all emails from required folder
				$response = imap_fetch_overview($connection,$range); 
				$response = array_reverse($response); 
								
				foreach ($response as $key => $msg) 
				{
					echo "<br> >> 3";
					if (array_key_exists("subject",$msg))
					{
						if(trim($msg->subject)=='BILLDESK REFUND')
						{
							// Response : stdClass Object ( [subject] => mail subject [from] => from email name [to] => to email name [date] => Tue, 7 Jul 2020 12:27:35 +0530 [message_id] => <985ccf7c7e4d4f14a8823b7919996924@esds.co.in> [size] => 9160 [uid] => 4 [msgno] => 2 [recent] => 0 [flagged] => 0 [answered] => 0 [deleted] => 0 [seen] => 1 [draft] => 0 [udate] => 1594105055 ) 
							//echo "<br>MsgID :- ".$msg->msgno;						exit;
							
							$attachments = $this->extract_attachments($connection,$msg->msgno);
							echo '<pre>'; print_r($attachments); echo '</pre>';
							if(!empty($attachments))
							{
								//READ ATTACHMENT FILE FROM SAVED FOLDER AND UPDATE THAT DATA INTO DATABASE TABLE
								if(file_exists($attachments))
								{
									$FileName = fopen($attachments, 'r');								
									$max_row_size = 1000;								
									$i = 0;
									$headers_arr = $result_arr = $up_data = array();			
									while(($row = fgetcsv($FileName, $max_row_size)) !== FALSE)
									{	
										if($i == 0) //First row as headers
										{
											$headers_arr = $row;
											echo "<pre>Headers <br> "; print_r($headers_arr); echo "</pre>"; exit;
										}
										else
										{ 
											/* if($i > 1) { break; } */
											if(count($headers_arr) == count($row)) //check if headers and values count is same or not										
											{
												for($j=0; $j < count($headers_arr); $j++)
												{
													$result_arr[$i][$headers_arr[$j]] = $row[$j];
												}
												
												//echo "<pre>result_arr <br> "; print_r($result_arr); echo "</pre>"; exit;
												"<br>Actual date : ".$result_arr[$i]['Refund Date'];
												//echo "<br>order_id : ".$order_id = str_replace("x", "0", strtolower($this->escape_string($result_arr[$i]['PGI Ref. No.'])));
												echo "<br>order_id : ".$order_id = $this->escape_string($result_arr[$i]['PGI Ref. No.']);
												"<br>BILLDESK_Refund_Status : ".$BILLDESK_Refund_Status = 'Transaction Refund'; //$this->escape_string($result_arr[$i]['STATUSCODE']);
												"<br>BILLDESK_Refund_Date : ".$BILLDESK_Refund_Date = $this->convert_date('d-m-Y', $this->escape_string($result_arr[$i]['Refund Date']));
												
												if($order_id != "" && $order_id != '0' && $BILLDESK_Refund_Status != "" && $BILLDESK_Refund_Status != '0' && $BILLDESK_Refund_Date != '' && $BILLDESK_Refund_Date != '0000-00-00')
												{
													$where_arr['transaction_no'] = $order_id;
													$where_arr['req_status'] = "5";
													$where_arr['refund_date !='] = "0000-00-00"; 
													$where_arr['BILLDESK_Refund_Date'] = "0000-00-00";
													$where_arr['credit_note_number'] = "";
													$check_rec = $this->master_model->getRecords('maker_checker i',$where_arr);
													if(count($check_rec) > 0) 
													{  
														//echo "<br>Found : ".$order_id; $record_found_cnt++;
													
														$up_data['sbi_refund_status'] = $BILLDESK_Refund_Status;
														$up_data['sbi_refund_date'] = $BILLDESK_Refund_Date;
														$up_data['sbi_refund_modified_date'] = date("Y-m-d H:i:s");
														$up_data['credit_note_date'] = $BILLDESK_Refund_Date;
																											
														echo "<pre>up_data <br> "; print_r($up_data); echo "</pre>";  
														//echo "<pre>where_arr <br> "; print_r($where_arr); echo "</pre>"; //exit;
														//XXX $up_qry = $this->master_model->updateRecord('maker_checker',$up_data,$where_arr);
														//XXX if($up_qry)   
														{ 
															$update_rec_cnt++; // NEED TO CHECK THIS COUNT. AS IT MUST BE INCREASE ONLY WHEN RECORD UPDATED. CURRENTLY IT IS INCRESES FOR ALL RECORDS
														}
														//echo $this->db->last_query();
														//echo "<pre>"; print_r($up_data); print_r($where_arr); echo "</pre>";
														//echo $this->db->last_query();
													}
												}
											}				
										}
										$i++;
									} 
									
									//echo "<pre>"; print_r($result_arr); echo "</pre>";exit;								
									fclose($FileName);// Close opened CSV file
									
									//Move email to read folder
									//imap_mail_move($connection, $msg->msgno, 'Sbi_refund_done');
									imap_expunge($connection);
									
									//@unlink($attachments);//Unlink the email downloaded file
								}
							}
						}
					}				
				}
			}
			
			imap_close($connection);
			echo "<br>".$update_rec_cnt." records updated";		
			echo "<br>".$record_found_cnt." found";
		}
		
		
		function convert_date($current_format='', $date='')
		{
			$final_date = '';
			if($current_format != '' && $date != '')
			{
				if($current_format == 'd-m-Y')
				{
					$final_date = date("Y-m-d", strtotime($date));
				}
				else if($current_format == 'd/m/Y')
				{
					$explode_arr1 = explode(" ",$date);
					
					if(count($explode_arr1) > 0)
					{
						$explode_arr2 = explode("/",$explode_arr1[0]);
						$final_date = $explode_arr2[2]."-".$explode_arr2[1]."-".$explode_arr2[0];
					}
				}
				else if($current_format == 'Y-m-d')
				{
					$final_date = $date;
				}
				else if($current_format == 'm-d-Y')
				{
					$explode_arr1 = explode(" ",$date);
					
					if(count($explode_arr1) > 0)
					{
						$explode_arr2 = explode("-",$explode_arr1[0]);
						$final_date = $explode_arr2[2]."-".$explode_arr2[0]."-".$explode_arr2[1];
					}
				}
			}
			
			return $final_date;
		}
		
		function escape_string($data)
		{
			$result = str_replace('"', '', $data);
			$result = str_replace("'", "", $result);
			
			return $result;
    }   
		
		public function extract_attachments($connection, $message_number) 
		{			
			$attachments = array();
			$structure = imap_fetchstructure($connection, $message_number);
      
			if(isset($structure->parts) && count($structure->parts)) 
			{				
				for($i = 0; $i < count($structure->parts); $i++) 
				{					
					$attachments[$i] = array(
					'is_attachment' => false,
					'filename' => '',
					'name' => '',
					'attachment' => ''
					);
					
					if($structure->parts[$i]->ifdparameters) 
					{
						foreach($structure->parts[$i]->dparameters as $object) 
						{
							if(strtolower($object->attribute) == 'filename') 
							{
								$attachments[$i]['is_attachment'] = true;
								$attachments[$i]['filename'] = $object->value;
							}
						}
					}
					
					if($structure->parts[$i]->ifparameters) 
					{
						foreach($structure->parts[$i]->parameters as $object) 
						{
							if(strtolower($object->attribute) == 'name') 
							{
								$attachments[$i]['is_attachment'] = true;
								$attachments[$i]['name'] = $object->value;
							}
						}
					}
					
					if($attachments[$i]['is_attachment']) 
					{
						$attachments[$i]['attachment'] = imap_fetchbody($connection, $message_number, $i+1);
						if($structure->parts[$i]->encoding == 3) 
						{ // 3 = BASE64
							$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
						}
						elseif($structure->parts[$i]->encoding == 4) 
						{ // 4 = QUOTED-PRINTABLE
							$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
						}
					}
					
					$filename ="";
					/* iterate through each attachment and save it */
					foreach($attachments as $attachment)
					{
						if($attachment['is_attachment'] == 1)
						{
							$attachment_folder = "./uploads/rahultest/sbi_refund_cron/";
							if(!file_exists($attachment_folder)) { mkdir($attachment_folder, 0700); }
							
							$filename = $attachment['name'];
							if(empty($filename)) { $filename = date('YmdHis').".dat"; }
							
							$file_extension = pathinfo($filename, PATHINFO_EXTENSION);
							$filename_explode = explode('.', $filename);
							$filename = $filename_explode[0].'_'.rand().'_'.date('YmdHis').'.'.$file_extension;
							//$filename = $filename_explode[0].".".$file_extension;
							$fp = fopen("./".$attachment_folder."/".$filename, "w+");
							fwrite($fp, $attachment['attachment']);
							fclose($fp);
							
							$filename = $attachment_folder.$filename;
						}
					}					
				}				
			}			
			return $filename;			
		}		
	
	
	}  