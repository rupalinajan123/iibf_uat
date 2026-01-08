<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Cron4_billdesk extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();			
			
			$this->load->library('upload');	
			$this->load->helper('upload_helper');
			$this->load->helper('master_helper');
			$this->load->model('master_model');		
			$this->load->library('email');
			$this->load->model('Emailsending');
			$this->load->model('log_model');
			$this->load->model('Ampmodel');
			$this->load->helper('blended_invoice_helper');
			$this->load->helper('date');
			$this->load->helper('gstrecovery_invoice_helper');			
			
			$this->load->helper('renewal_invoice_helper'); // added by chaitali on 2021-08-13
		}
		
		public function member_regn_log($log_title, $log_message = "", $rId = NULL, $regNo = NULL)
		{
			$obj = new OS_BR();
			$browser_details=implode('|',$obj->showInfo('all'));
			$data['title'] = $log_title;
			$data['description'] = $log_message;
			$data['regid'] = $rId;
			$data['regnumber'] = $regNo;
			$data['ip'] = $this->input->ip_address();
			$data['browser'] = $browser_details;
			$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
			//$this->db->insert('userlogs_member_cs2s', $data);
		}
		
		public function billdesk_callback()
		{
			$this->load->model('billdesk_pg_model');
			$this->load->model('billdesk_pg_module_cron');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper');
						
			$log_title ="billdesk_callback Function call Cron4";
			$log_message = 'billdesk_callback Function call Cron4';
			$rId = $regNo = '';
			$this->member_regn_log($log_title, $log_message, $rId, $regNo);
			
			$filehandle = fopen("cs2s_log/billdesk_cron4/lock.txt", "c+");
			if (flock($filehandle, LOCK_EX | LOCK_NB)) 
			{
				// code here to start the cron job
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$this->load->library('excel');
				$key = $this->config->item('sbi_m_key');
				
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				
				$receipt_no_arr = array();

        //START : PAYMENT TRANSACTION QUERY
				$query='SELECT receipt_no, pg_flag FROM payment_transaction WHERE date <= NOW() - INTERVAL 20 MINUTE AND date > NOW() - INTERVAL 100 MINUTE AND gateway = "billdesk" AND status = 2';
				$crnt_day_txn_qry = $this->db->query($query);
        //echo '<br>'.$this->db->last_query();
      
        if ($crnt_day_txn_qry->num_rows())
        {
          $receipt_no_arr = array_merge($receipt_no_arr, $crnt_day_txn_qry->result_array());
        }//END : PAYMENT TRANSACTION QUERY

        //START : BCBF PAYMENT TRANSACTION QUERY
        $query_bcbf = 'SELECT receipt_no, pg_flag FROM iibfbcbf_payment_transaction WHERE date <= NOW() - INTERVAL 20 MINUTE AND date > NOW() - INTERVAL 100 MINUTE AND gateway = "2" AND status = "2" AND payment_mode = "Individual"';
        $crnt_day_txn_bcbf_qry = $this->db->query($query_bcbf);
        //echo '<br>'.$this->db->last_query();
        
        if ($crnt_day_txn_bcbf_qry->num_rows())
        {
          $receipt_no_arr = array_merge($receipt_no_arr, $crnt_day_txn_bcbf_qry->result_array());
        }//END : BCBF PAYMENT TRANSACTION QUERY

        //START : AMP PAYMENT TRANSACTION QUERY
        $query_amp = 'SELECT receipt_no, pg_flag FROM amp_payment_transaction WHERE date <= NOW() - INTERVAL 20 MINUTE AND date > NOW() - INTERVAL 100 MINUTE AND gateway = "billdesk" AND status = "2"';
        $crnt_day_txn_amp_qry = $this->db->query($query_amp);
        //echo '<br>'.$this->db->last_query();

        if ($crnt_day_txn_amp_qry->num_rows())
        {
          $receipt_no_arr = array_merge($receipt_no_arr, $crnt_day_txn_amp_qry->result_array());
        }//END : AMP PAYMENT TRANSACTION QUERY

        //echo '<pre>'; print_r($receipt_no_arr); echo '</pre>';

				echo "*********************************** New Cron Request Started***************************\n";
				echo  "<br>Total Count =>". count($receipt_no_arr);
				//echo $this->db->last_query();exit;
				if (count($receipt_no_arr) > 0)
				{
					$start_time = date("Y-m-d H:i:s");
					$succ_cnt = $fail_cnt = $no_resp_cnt = $pending_cnt = 0;
					$succ_recp_arr = $fail_recp_arr = $no_resp_recp_arr = $pending_recp_arr = array();
					$todays_date = date("d-m-Y");
					$dir = 'cs2s_log/billdesk_cron4/'.$todays_date;
					if(!is_dir($dir)){ mkdir($dir, 0755); }
					$cell = 1;
					
					$objPHPExcel = new PHPExcel();
					$objPHPExcel->setActiveSheetIndex(0);
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$cell, "Receipt No")
					->setCellValue('B'.$cell, "Transaction Status")
					->setCellValue('C'.$cell, "Transaction Data")
					->setCellValue('D'.$cell, "Response Data")
					->setCellValue('E'.$cell, "Response Date");
					
					foreach ($receipt_no_arr as $c_row)
					{
						$cell++;
						//sleep(1);
						$responsedata = $this->billdesk_pg_model->billdeskqueryapi($c_row['receipt_no']);
						//echo '<pre>'; print_r($responsedata); echo '</pre>'; exit;
						$receipt_no = $c_row['receipt_no'];
						$pg_flag = $c_row['pg_flag'];

						$encData = implode('|',$responsedata);
						$resp_data = json_encode($responsedata);
						if(empty($responsedata) || $responsedata == 0 || $responsedata == "")
						{
							$responsedata = $this->billdesk_pg_model->billdeskqueryapi($c_row['receipt_no']);	
						}
						else if(empty($responsedata) || $responsedata == 0 || $responsedata == "")
						{
							$responsedata = $this->billdesk_pg_model->billdeskqueryapi($c_row['receipt_no']);	
						}
						// priyanka d- check if transaction is refunded already then make its status 3 in db and skip further process - 06-mar-23
						//$refundStatusData = $this->billdesk_pg_model->billdeskRefundStatusApi($c_row['receipt_no']);
						//echo '<pre>'.'refund status=='; print_r($refundStatusData); echo '</pre>'; 
						
						$refundInitiated=0;
						if(isset($responsedata['refundInfo']) && !empty($responsedata['refundInfo']))
						{
							$refundInitiated=1;
							 
							$refundStatusDataJson=json_encode($responsedata);
              if($pg_flag == 'BC')
              {
                $update_data = array( 'status' => '5', 'transaction_details' =>  " Refunded from billdesk ", 'callback' => 'c_S2S');
							  $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $c_row['receipt_no']));

                $iibfbcbf_payment_data = $this->master_model->getRecordCount('iibfbcbf_payment_transaction',array('receipt_no'=>$receipt_no));
                if(count($iibfbcbf_payment_data) > 0)
                {
                  $exam_id_str = $iibfbcbf_payment_data[0]['exam_ids'];
                  $exam_id_arr = explode(",", $exam_id_str);
                  if(count($exam_id_arr) > 0)
                  {
                    foreach($exam_id_arr as $exam_id_res)
                    {
                      $update_data_exam = array( 'pay_status' => '5', 'description' =>  " Refunded from billdesk ");
							        $this->master_model->updateRecord('iibfbcbf_member_exam', $update_data_exam, array('member_exam_id' => $exam_id_res));
                    }
                  }
                }
              }
              else if($pg_flag == 'AMP')
              {
                $update_data = array( 'status' => '5', 'transaction_details' =>  " Refunded from billdesk ", 'callback' => 'c_S2S');
							  $this->master_model->updateRecord('amp_payment_transaction', $update_data, array('receipt_no' => $c_row['receipt_no']));
							}
              else
              {
							  $update_data = array( 'status' => 3, 'transaction_details' =>  " Refunded from billdesk ", 'callback' => 'c_S2S');
							$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $c_row['receipt_no']));
              }

							$fp = @fopen($dir."/logs_".date("dmY").".txt", "a") or die("Unable to open file!");
								echo $str = "\n refund found= $receipt_no=$refundStatusDataJson\n";
								fwrite($fp, $str);
								fclose($fp);
						}

						if($refundInitiated==0 && isset($responsedata) && count($responsedata) > 0) 
            {
							## Check payment_c_s2s_log entry  
              if($pg_flag == 'BC')
              {
                $data_count = $this->master_model->getRecordCount('iibfbcbf_payment_transaction',array('receipt_no'=>$receipt_no,'status'=>'1'));
              }
              else if($pg_flag == 'AMP')
              {
                $data_count = $this->master_model->getRecordCount('amp_payment_transaction',array('receipt_no'=>$receipt_no,'status'=>'1'));
              }
              else
              {
							$data_count = $this->master_model->getRecordCount('payment_transaction',array('receipt_no'=>$receipt_no,'status'=>1));
              }
							
							if($data_count == 0)
							{
								## Add log file
								$fp = @fopen($dir."/logs_".date("dmY").".txt", "a") or die("Unable to open file!");
								echo $str = "\n $receipt_no=$encData\n";
								fwrite($fp, $str);
								fclose($fp);
								
								## Excel file
								$objPHPExcel->getActiveSheet()->setCellValue('A'.$cell,$c_row['receipt_no'])
								->setCellValue('B'.$cell, $responsedata['transaction_error_type'])
								->setCellValue('C'.$cell, $encData.'&CALLBACK=C_S2S')
								->setCellValue('D'.$cell, $resp_data)
								->setCellValue('E'.$cell, date('Y-m-d H:i:s'));
								// Save Excel xls File
								$filename="log_excel.xls";
								$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
								$objWriter->save(str_replace(__FILE__,$dir.'/'.$filename,__FILE__));
								## Update counts
								if($responsedata['auth_status'] == '0300')
								{
									$succ_cnt++;
									array_push($succ_recp_arr,$receipt_no);
								}
								else if($responsedata['auth_status'] == '0399')
								{
									$fail_cnt++;
									array_push($fail_recp_arr,$receipt_no);

                  if($pg_flag == 'BC')
                  {
                    $iibfbcbf_update_data = array( 'status' => '0', 'transaction_details' =>  $responsedata['transaction_error_type'], 'callback' => 'c_S2S');
                    $this->master_model->updateRecord('iibfbcbf_payment_transaction', $iibfbcbf_update_data, array('receipt_no' => $receipt_no,'status'=>'2'));

                    $iibfbcbf_payment_data = $this->master_model->getRecordCount('iibfbcbf_payment_transaction',array('receipt_no'=>$receipt_no));
                    if(count($iibfbcbf_payment_data) > 0)
                    {
                      $exam_id_str = $iibfbcbf_payment_data[0]['exam_ids'];
                      $exam_id_arr = explode(",", $exam_id_str);
                      if(count($exam_id_arr) > 0)
                      {
                        foreach($exam_id_arr as $exam_id_res)
                        {
                          $iibfbcbf_update_data_exam = array( 'pay_status' => '0', 'description' =>  $responsedata['transaction_error_type']);
                          $this->master_model->updateRecord('iibfbcbf_member_exam', $iibfbcbf_update_data_exam, array('member_exam_id' => $exam_id_res));
                        }
                      }
                    }
                  }
                  else if($pg_flag == 'AMP')
                  {
                    $update_data = array('status' => 0,'callback'=>'c_S2S','transaction_details'=>$responsedata['transaction_error_type']);
									  $update_query=$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$receipt_no,'status'=>2));
                  }
                  else
                  {
									$update_data = array('status' => 0,'callback'=>'c_S2S','transaction_details'=>$responsedata['transaction_error_type']);
									$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$receipt_no,'status'=>2));
								}
								}
								else if($responsedata['auth_status'] == '0002')
								{
									$pending_cnt++;
									array_push($pending_recp_arr,$receipt_no);
								}
								else
								{
									$no_resp_cnt++;
									array_push($no_resp_recp_arr,$receipt_no);
								}							
							}
							
							$resp_array = array('receipt_no'	=> $c_row['receipt_no'],
							'txn_status' 	=> $responsedata['transaction_error_type'],
							'txn_data' 		=> $encData.'&CALLBACK=C_S2S4',
							'response_data' => $resp_data,
							'remark' 		=> '',
							'resp_date' 	=> date('Y-m-d H:i:s'),
							);
							$this->master_model->insertRecord('payment_c_s2s_log', $resp_array);
							
							if (isset($responsedata) && count($responsedata) > 0)
							{
								$this->billdesk_pg_module_cron->billdesk_cron_settlement_common($responsedata, $encData, $c_row['receipt_no']);
							}
							else
							{
								echo "Please try again...";
							}
						}
					}//foreach
					
					$succ_recp = implode(",",$succ_recp_arr);
					$fail_recp = implode(",",$fail_recp_arr);
					$no_resp_recp = implode(",",$no_resp_recp_arr);
					$pending_recp = implode(",",$pending_recp_arr);
					$end_time = date("Y-m-d H:i:s");
					## Counts files
					$fp = @fopen($dir."/detail_logs_new_data_".date("dmY").".txt", "a") or die("Unable to open file!");
					echo $str = "\n***********************************************************\n\n Cron execution started at :$start_time \n\n Total Count =>". count($receipt_no_arr)."\n\nTotal records SUCCESS: $succ_cnt\n($succ_recp) \nTotal records FAIL: $fail_cnt\n($fail_recp) \n Total records PENDING: $pending_cnt\n($pending_recp)\n Total records No Response: $no_resp_cnt\n($no_resp_recp)\n Cron execution ended at: $end_time\n";
					fwrite($fp, $str);
					fclose($fp);
					
					## Total Counts files
					$fp = @fopen($dir."/log_counts_".date("dmY").".txt", "a") or die("Unable to open file!");
					echo $str = "\n***********************************************************\n\n Cron execution started at :$start_time \n\n Total Count =>". count($receipt_no_arr)."\n\nTotal records SUCCESS: $succ_cnt \nTotal records FAIL: $fail_cnt \n Total records PENDING: $pending_cnt\n Total records No Response: $no_resp_cnt\n Cron execution ended at: $end_time\n";
					fwrite($fp, $str);
					fclose($fp);
				}
				
				flock($filehandle, LOCK_UN);  // don't forget to release the lock
			} 
			else 
			{
				// throw an exception here to stop the next cron job
				echo "Cron4 is already running";
			}
			fclose($filehandle);			
		}		
	}	