<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class DRA_billdesk_payment_cron extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();			
			
			$this->load->library('upload');	
			$this->load->helper('upload_helper');
			$this->load->helper('master_helper');
			$this->load->model('master_model');		
			$this->load->model('log_model');
			$this->load->helper('date'); 
			$this->load->helper('general_helper');
    }
		
		public function billdesk_callback()
		{
			$this->load->model('billdesk_pg_model');

			$filehandle = fopen("cs2s_log/billdesk_cron1/lock.txt", "c+");
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
				$current_date   = date('Y-m-d');

				$this->db->where('exam_code',1036);
				$this->db->order_by('exam_period','DESC');
				$active_exam_info = $this->master_model->getRecords("dra_exam_activation_master");

				$current_exam_period    = isset($active_exam_info[0]['exam_period']) ? $active_exam_info[0]['exam_period'] : 0; 
				$current_exam_to_date   = isset($active_exam_info[0]['exam_to_date']) ? $active_exam_info[0]['exam_to_date'] : 0;

				//START : DRA PAYMENT TRANSACTION QUERY
        $query_dra = 'SELECT id,receipt_no, date, pg_flag FROM dra_payment_transaction WHERE gateway = "2" AND (status = "5") AND exam_period = "12"';
        
        $crnt_day_txn_dra_qry = $this->db->query($query_dra);
        
        if ($crnt_day_txn_dra_qry->num_rows())
        {
          $receipt_no_arr = array_merge($receipt_no_arr, $crnt_day_txn_dra_qry->result_array());
        }//END : DRA PAYMENT TRANSACTION QUERY        
        
        // echo "<pre>"; print_r($receipt_no_arr); exit;
				echo "*********************************** New Cron Request Started ***************************\n";
				echo  "<br>Total Count =>". count($receipt_no_arr); 
        //echo $this->db->last_query();exit;
				if (count($receipt_no_arr) > 0)
				{
					$start_time = date("Y-m-d H:i:s");
					$succ_cnt = $fail_cnt = $no_resp_cnt = $pending_cnt = 0;
					$succ_recp_arr = $fail_recp_arr = $no_resp_recp_arr = $pending_recp_arr = array();
					$todays_date = date("d-m-Y");
					$dir = 'cs2s_log/billdesk_cron1/'.$todays_date;
					if(!is_dir($dir)){ mkdir($dir, 0755); }
					$cell = 1;
					
					$objPHPExcel = new PHPExcel();
					$objPHPExcel->setActiveSheetIndex(0);
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$cell, "Receipt No")
					->setCellValue('B'.$cell, "Transaction Status")
					->setCellValue('C'.$cell, "Transaction Data")
					->setCellValue('D'.$cell, "Response Data")
					->setCellValue('E'.$cell, "Response Date");
					// echo "<pre>"; print_r($receipt_no_arr); exit;
					foreach ($receipt_no_arr as $c_row)
					{
						$date = new DateTime($c_row['date']); // Create a DateTime object from the given date
						$date->modify('+2 days'); // Add 2 days to the date
						$twoDaysLater = $date->format('Y-m-d');
						
						if ($twoDaysLater >= $current_date) 
						{
							$cell++;
							//sleep(1);
							$responsedata = $this->billdesk_pg_model->billdeskqueryapi($c_row['receipt_no']);
							// echo '<pre>'; print_r($responsedata); echo '</pre>'; exit;
							$receipt_no = $c_row['receipt_no'];
							$pg_flag = $c_row['pg_flag'];

							$encData   = implode('|',$responsedata);
							$resp_data = json_encode($responsedata);
							if(empty($responsedata) || $responsedata == 0 || $responsedata == "")
							{
								$responsedata = $this->billdesk_pg_model->billdeskqueryapi($c_row['receipt_no']);	
	            }
							else if(empty($responsedata) || $responsedata == 0 || $responsedata == "")
							{
								$responsedata = $this->billdesk_pg_model->billdeskqueryapi($c_row['receipt_no']);	
	            }
							
							$refundInitiated=0;
							if(isset($responsedata['refundInfo']) && !empty($responsedata['refundInfo']))
							{
								$refundInitiated=1;

								$refundStatusDataJson=json_encode($responsedata);
	              
	              if($pg_flag == 'iibfbulkdra')
	              {
	                $update_data = array( 'status' => '8', 'updated_date'=> date('Y-m-d H:i:s'),'date'=> date('Y-m-d h:i:s'),'transaction_details' =>  " Refunded from billdesk ", 'callback' => 'c_S2S');

								  $this->master_model->updateRecord('dra_payment_transaction', $update_data, array('receipt_no' => $c_row['receipt_no']));
									
								  if ($this->db->affected_rows())
								  {
								  	log_dra_admin($log_title = "Transaction status is updated when Online Payment is Refunded :  C_S2S.", $log_message = serialize($update_data));

								  	$memexamidlst = $this->master_model->getRecords('dra_member_payment_transaction',array('ptid' => $c_row['id']));

									  if(count($memexamidlst) > 0) 
										{
											foreach( $memexamidlst as $memexamids ) 
											{
												$memexamid = $memexamids['memexamid'];
												$memregid  = $this->master_model->getValue('dra_member_exam',array('id' => $memexamid), 'id');

												if( isset($memregid) && $memregid != '' && $memregid != 0 ) 
												{
													$update_data_exam = array( 'pay_status' => '8');
											    $this->master_model->updateRecord('dra_member_exam', $update_data_exam, array('id' => $memregid));
											  }  
											}
											log_dra_admin($log_title = "DRA Reg No. payment status updated successfully after Online Payment Refunded : C_S2S", $log_message = serialize($log_update_data));
										}	  	
								  }
	              }
								
								$fp = @fopen($dir."/logs_".date("dmY").".txt", "a") or die("Unable to open file!");
	              echo $str = "\n refund found= $receipt_no=$refundStatusDataJson\n";
	              fwrite($fp, $str);
	              fclose($fp);
	            }
							
							if($refundInitiated==0 && isset($responsedata) && count($responsedata) > 0) 
	            { 
	              if($pg_flag == 'iibfbulkdra')
	              {
	                $data_count = $this->master_model->getRecordCount('dra_payment_transaction',array('receipt_no'=>$receipt_no,'status'=>'1'));
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
									'txn_data' 		=> $encData.'&CALLBACK=C_S2S1',
									'response_data' => $resp_data,
									'remark' 		=> '',
									'resp_date' 	=> date('Y-m-d H:i:s'),
								);

								$this->master_model->insertRecord('payment_c_s2s_log', $resp_array);
								
								if (isset($responsedata) && count($responsedata) > 0)
								{
					        sleep(8);
					        $MerchantOrderNo = $responsedata['orderid']; 
					        $transaction_no  = $responsedata['transactionid'];
					        $auth_status     = $responsedata['auth_status'];
					        $payment_status  = 2;
					        
					        switch ($auth_status)
					        {
					          case "0300": $payment_status = 1; break; //success
					          case "0399": $payment_status = 0; break; // failed
					          case "0002": $payment_status = 2; break; // pending
					        }
					        
					        if($payment_status==1)
					        {
					        	$this->load->helper('dra_admitcard_helper');

					          //get payment transaction id
					          $transdetail_det = $this->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'status,id,date');
					          if(count($transdetail_det) > 0 )
					          {				            
					            $updated_date = date('Y-m-d H:i:s');
					            $update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata['transaction_error_type'] . " - " . $responsedata['transaction_error_desc'], 'bankcode' => $responsedata['bankid'], 'paymode' =>  $responsedata['txn_process_type'], 'description' => $responsedata['transaction_error_desc'], 'updated_date' => $updated_date, 'callback'=>'c_S2S');
					            $this->master_model->updateRecord('dra_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
					            if($this->db->affected_rows())
					            {
					              $transid = 0;
					              if( count($transdetail_det) > 0 ) 
					              {
					                $transdetail = $transdetail_det[0];
					                $transid = $transdetail['id'];
					                //echo "<BR>transid = ".$transid; 
					                //get dra_member_exam_unique ids from dra_member_payment_transaction table
					                $transmemdetails = $this->master_model->getRecords('dra_member_payment_transaction',array('ptid'=>$transid));
					                //echo $this->db->last_query();
					                //print_r($transmemdetails);
					                if( count( $transmemdetails ) > 0 ) {
					                  foreach( $transmemdetails as $transmemdetail ) { //print_r($transmemdetail);
					                    $uniqueid = $transmemdetail['memexamid']; //unique id of dra_member_exam table
					                    $regidformemref = $this->master_model->getValue('dra_member_exam',array('id'=>$uniqueid),'regid');
					                    //echo "<BR>regidformemref = ".$regidformemref."  --  ".$uniqueid;
					                    $regnum = $this->master_model->getValue('dra_members',array('regid'=>$regidformemref),'regnumber');
					                    //echo "<BR>regnum = ".$regnum;
					                    if( empty( $regnum ) ) {
					                      //$regnumber = generate_dra_reg_num();
					                      //$regnumber = generate_nm_reg_num();
					                      $regnumber = generate_NM_memreg($regidformemref);
					                      $update_data = array('regnumber' => $regnumber);
					                      $this->master_model->updateRecord('dra_members',$update_data,array('regid'=>$regidformemref));
					                      //update uploaded file names which will include generated registration number
					                      //get cuurent saved file names from DB
					                      $currentpics = $this->master_model->getRecords('dra_members', array('regid'=>$regidformemref), 'scannedphoto, scannedsignaturephoto, idproofphoto, training_certificate, quali_certificate');                   $scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $trainingphoto_file = $qualiphoto_file = '';
					                      
					                      if( count($currentpics) > 0 ) {
					                        $currentphotos = $currentpics[0];
					                        $scannedphoto_file = $currentphotos['scannedphoto'];
					                        $scannedsignaturephoto_file = $currentphotos['scannedsignaturephoto'];
					                        $idproofphoto_file = $currentphotos['idproofphoto'];
					                        $trainingphoto_file = $currentphotos['training_certificate'];
					                        $qualiphoto_file = $currentphotos['quali_certificate'];
					                      }
					                      $upd_files = array();
					                      $photo_file = 'p_'.$regnumber.'.jpg';
					                      $sign_file = 's_'.$regnumber.'.jpg';
					                      $proof_file = 'pr_'.$regnumber.'.jpg';
					                      $quali_file = 'degre_'.$regnumber.'.jpg';
					                      $training_file = 'traing_'.$regnumber.'.jpg';
					                      if( !empty( $scannedphoto_file ) ) {
					                        if(@ rename("./uploads/iibfdra/".$scannedphoto_file,"./uploads/iibfdra/".$photo_file))
					                        { 
					                          $upd_files['scannedphoto'] = $photo_file; 
					                        }
					                      }
					                      if( !empty( $scannedsignaturephoto_file ) ) {
					                        if(@ rename("./uploads/iibfdra/".$scannedsignaturephoto_file,"./uploads/iibfdra/".$sign_file))
					                        { 
					                          $upd_files['scannedsignaturephoto'] = $sign_file; 
					                        }
					                      }
					                      if( !empty( $idproofphoto_file ) ) {
					                        if(@ rename("./uploads/iibfdra/".$idproofphoto_file,"./uploads/iibfdra/".$proof_file))
					                        { 
					                          $upd_files['idproofphoto'] = $proof_file; 
					                        }
					                      }
					                      if( !empty( $qualiphoto_file ) ) {
					                        if(@ rename("./uploads/iibfdra/".$qualiphoto_file,"./uploads/iibfdra/".$quali_file))
					                        { 
					                          $upd_files['quali_certificate'] = $quali_file;  
					                        }
					                      }
					                      if( !empty( $trainingphoto_file ) ) {
					                        if(@ rename("./uploads/iibfdra/".$trainingphoto_file,"./uploads/iibfdra/".$training_file))
					                        { 
					                          $upd_files['training_certificate'] = $training_file;  
					                        }
					                      }
					                      if(count($upd_files)>0)
					                      {
					                        $this->master_model->updateRecord('dra_members',$upd_files,array('regid'=>$regidformemref));
					                      }             
					                    }
					                    
					                    $update_data = array('pay_status' => 1);
					                    $this->master_model->updateRecord('dra_member_exam',$update_data,array('id'=>$uniqueid));
					                  }
					                }
					              }
					              
					              // get invoice
					              $exam_invoice = $this->master_model->getRecords('exam_invoice',array('receipt_no' => $MerchantOrderNo,'pay_txn_id' => $transdetail_det[0]['id']),'invoice_id');
					              
					              if(count($exam_invoice) > 0)
					              {
					                // generate exam invoice no
					                $invoice_no = generate_exam_invoice_number($exam_invoice[0]['invoice_id']);
					                if($invoice_no)
					                {
					                  $invoice_no = $this->config->item('exam_invoice_no_prefix').$invoice_no; // e.g. EXM/2017-18/000001
					                }
					                
					                // update invoice details
					                $invoice_update_data = array('invoice_no' => $invoice_no,'transaction_no' => $transaction_no,'date_of_invoice' =>$transdetail_det[0]['date'],'modified_on' => $updated_date);
					                $this->db->where('pay_txn_id',$transdetail_det[0]['id']);
					                $this->master_model->updateRecord('exam_invoice',$invoice_update_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));
					                
					                log_dra_user($log_title = "Update DRA Exam Invoice Successful", $log_message = serialize($invoice_update_data));
					                
					                // generate invoice image
					                $invoice_img_path = genarate_draexam_invoice($exam_invoice[0]['invoice_id']);
					              }
					            }
					            /******************* eof code added for GST Changes, by Bhagwan Sahane, on 05-07-2017 ***************/
					          }
					        }
					        else if($payment_status==0)
					        {
					          $update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata['transaction_error_type'] . " - " . $responsedata['transaction_error_desc'], 'bankcode' => $responsedata['bankid'], 'paymode' =>  $responsedata['txn_process_type'], 'description' => $responsedata['transaction_error_desc'],'callback'=>'c_S2S');
					          $this->master_model->updateRecord('dra_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
					          // Handle transaction fail case 
					          
					          $transdetail_det = $this->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$MerchantOrderNo));
					          $transid = 0;
					          if( count($transdetail_det) > 0 ) {
					            $transdetail = $transdetail_det[0];
					            $transid = $transdetail['id'];
					            //echo "<BR>transid = ".$transid; 
					            //get dra_member_exam_unique ids from dra_member_payment_transaction table
					            $transmemdetails = $this->master_model->getRecords('dra_member_payment_transaction',array('ptid'=>$transid));
					            //echo $this->db->last_query();
					            //print_r($transmemdetails);
					            if( count( $transmemdetails ) > 0 ) {
					              foreach( $transmemdetails as $transmemdetail ) { //print_r($transmemdetail);
					                $uniqueid = $transmemdetail['memexamid']; //unique id of dra_member_exam table
					                $update_data = array('pay_status' => 0); //0 for fail
					                $this->master_model->updateRecord('dra_member_exam',$update_data,array('id'=>$uniqueid));
					                //echo "<BR>dra_member_exam id = ".$uniqueid;
					              }
					            }
					          }
					        }

					        if(isset($responsedata['status']) && $responsedata['status'] == '404' && $receipt_no != "")
				          {   
				            $get_user_regnum = $this->master_model->getRecords('dra_payment_transaction', array('receipt_no' => $receipt_no) , 'id, date, status');
				            // echo date("Y-m-d H:i:s",strtotime($get_user_regnum[0]['date'])).'----'.date('Y-m-d H:i:s', strtotime("-30 minutes")); exit;
				            if(count($get_user_regnum) > 0 && ($get_user_regnum[0]['status'] == '3' || $get_user_regnum[0]['status'] == '5'))
				            { 
				              $update_data = array();
				              $update_data['transaction_no'] = '';
				              $update_data['status'] = '0';
				              $update_data['transaction_details'] = $responsedata['message']." >> ".$responsedata['error_type']." >> ".$responsedata['error_code'];
				              $update_data['auth_code'] = '';
				              $update_data['bankcode'] = '';
				              $update_data['paymode'] = '';
				              $update_data['callback'] = 'C_S2S';           
				              $update_data['description'] = 'The transaction was not completed by the candidate';
				              $update_data['date'] = date('Y-m-d H:i:s');
				              $update_data['updated_date'] = date('Y-m-d H:i:s');
				              
				              $this->db->group_start();
				              $this->db->where('receipt_no',$receipt_no);
				              $this->db->group_end();
				              $this->db->group_start(); // Start grouping for OR conditions
				              $this->db->where('status', '3');
				              $this->db->or_where('status', '5');
				              $this->db->group_end(); // End of OR conditions
				              $this->db->update('dra_payment_transaction', $update_data);
				              // END : UPDATE PAYMENT FAIL STATUS IN DRA PAYMENT TRANSACTION TABLE AND INSERT LOG
				          
				              // QUERY TO GET UPDATED PAYMENT DETAILS AFTER PAYMENT SUCCESS
				              $iibfdra_payment_data = $this->master_model->getRecords('dra_payment_transaction', array('receipt_no' => $receipt_no, 'gateway'=>'2'), 'transaction_no, date, amount, id, description, status, exam_code, exam_period, receipt_no');
				      
				              if(count($iibfdra_payment_data) > 0 && $iibfdra_payment_data[0]['status'] == 0)
				              {
				                $memexamidlst = $this->master_model->getRecords('dra_member_payment_transaction',array('ptid' => $iibfdra_payment_data[0]['id']));

				                if(count($memexamidlst) > 0) 
				                {
				                  foreach( $memexamidlst as $memexamids ) 
				                  {
				                    $memexamid = $memexamids['memexamid'];
				                    $memregid  = $this->master_model->getValue('dra_member_exam',array('id' => $memexamid), 'id');

				                    if( isset($memregid) && $memregid != '' && $memregid != 0 ) 
				                    {
				                      $update_data_exam = array( 'pay_status' => 0);
				                      $this->master_model->updateRecord('dra_member_exam', $update_data_exam, array('id' => $memregid));
				                    }  
				                  }
				                  log_dra_admin($log_title = "DRA Reg No. payment status updated successfully after Online Payment Failed : C_S2S", $log_message = serialize($log_update_data));
				                }
				              }
				            }
				          }

	              }
								else
								{
									echo "Please try again...";
	              }
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
				echo "Cron1 is already running";
      }
			fclose($filehandle);			
    }		
  }	    