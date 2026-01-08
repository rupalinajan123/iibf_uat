<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Refund extends CI_Controller {
	public $UserID;
			
	public function __construct(){
		parent::__construct();
		if($this->session->id==""){
			redirect('admin/Login');
		}
		
		if($this->session->userdata('roleid')!=1){
			redirect(base_url().'admin/MainController');
		}		
		
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->UserID=$this->session->id;
		
		$this->load->helper('pagination_helper');
		$this->load->helper('refund_helper');
		$this->load->helper('general_helper');
		$this->load->library('pagination');
		$this->load->helper('upload_helper');
		$this->load->helper('getregnumber_helper');
		$this->load->library('email');
		$this->load->model('Log_model');
	}
	
	
	public function index()
	{
		$data['result'] = array();
		$SearchVal = '';
		$searchBy = '';
		$searchOn = '';
		
		if(isset($_POST['btnSearch']))
		{
			if(isset($_POST['searchBy']) && $_POST['searchBy']!='')
			{
				$searchBy = trim($_POST['searchBy']);
			}
			
			if(isset($_POST['searchOn']) && $_POST['searchOn']!='')
			{ 				
				$searchOn = trim($_POST['searchOn']);
			}
			
			if(isset($_POST['SearchVal']) && $_POST['SearchVal']!='')
			{		
				$SearchVal = trim($_POST['SearchVal']);
			}
			
			if($searchBy !='' && $searchOn != '' && $SearchVal != '')
			{
				if($searchOn == "01")	//Exam Details 
				{
					$this->session->set_userdata('refund_for','Exam');
					if($searchBy == 'transaction_no')
					{
						$this->db->join('member_exam','member_exam.id=payment_transaction.ref_id AND member_exam.regnumber=payment_transaction.member_regnumber','LEFT');
						$payment = $this->master_model->getRecords('payment_transaction',array('transaction_no'=>$SearchVal,'pay_type'=>2,'status'=>1,'pay_status'=>1));
					}
				}
				else if($searchOn == "02")	//Duplicate I-card Details
				{
					$this->session->set_userdata('refund_for','Icard');
					if($searchBy == 'transaction_no')
					{
						$this->db->join('duplicate_icard','duplicate_icard.did = payment_transaction.ref_id AND duplicate_icard.regnumber = payment_transaction.member_regnumber','LEFT');
						$payment = $this->master_model->getRecords('payment_transaction',array('transaction_no'=>$SearchVal,'pay_type'=>3,'status'=>1,'pay_status'=>'1'));
					}
				}
				else if($searchOn == "03")	//Registration Details
				{
					$this->session->set_userdata('refund_for','Registration');
					if($searchBy == 'transaction_no')
					{
						$where = "isactive = '1' AND status = 1 AND transaction_no = '".$SearchVal."' AND CASE WHEN (a.registrationtype != 'NM' AND a.registrationtype != 'DB') THEN b.pay_type=1 ELSE b.pay_type=2 END";
						$this->db->join('member_registration a','b.member_regnumber=a.regnumber','LEFT');
						$this->db->where($where,'',false);
						$payment = $this->master_model->getRecords('payment_transaction b');
					}
				}
			}
			//echo $this->db->last_query(); 
			$data['result'] = $payment;
		}
		
		if(isset($_POST['BtnDeact']))
		{
			$this->session->set_flashdata('error','This Feature is under process...');
			redirect(base_url().'admin/Refund');
			//print_r($_POST);exit;
			//if($this->session->userdata('refund_for') == "Icard" || $this->session->userdata('refund_for') == "Registration")
			//{
				$refundInput = $this->input->post('makeRefund'); 
				if(!empty($refundInput))
				{
					$inputArr = explode('|~|',$refundInput);
					$transaction_no = '';	
					$receipt_no = '';
					$MerchantOrderNo = '';	
					if(count($inputArr)==2)
					{
						$transaction_no = $inputArr[0];	
						$MerchantOrderNo = $receipt_no = $inputArr[1];	
					}
					
					if(!empty($transaction_no) && !empty($receipt_no))
					{
						
						$query_result = sbiqueryapi($receipt_no);	//DP987787930
						//var_dump($query_result);
						//$query_result = $this->sbiqueryapi("DP987787930");

						if(count($query_result))
						{
							//print_r($query_result); exit;
							//echo $query_result[2]." - ".$query_result[8];
							
							//if($query_result[8] == "Transaction Paid Out")
							if($query_result[8] == "Transaction Paid Out") // Transaction Paid Out / Transaction Settled
							{ //echo "111";exit;
								// Initiate Refund
								$payment = $this->master_model->getRecords('payment_transaction',array('transaction_no'=>$transaction_no));
								
								if(count($payment))
								{
									$refund_request_id = '';
									if($payment[0]['gateway'] == "sbiepay")
									{
										//$refund_request_id = generate_refund_request_id($field_name = "sbi_refund_request_id");
										$refund_request_id = sbi_refund_request_id($transaction_no, $receipt_no);
									}
									else if($payment[0]['gateway'] == "billdesk")
									{
										$this->session->set_flashdata('error','This Feature is under process...');
										redirect(base_url().'admin/Refund');
										
										//$refund_request_id = generate_refund_request_id($field_name = "bd_refund_request_id");
										//$refund_request_id = bd_refund_request_id($transaction_no, $receipt_no);
									}

									if($refund_request_id)
									{
										//`ARRN`, `description`, `refund_details`, `status`,
										$refund_array = array(	'member_regnumber'=>$payment[0]['member_regnumber'],
																'gateway'=>$payment[0]['gateway'],
																'amount'=>$payment[0]['amount'],
																'date'=>date('Y-m-d H:i:s'),
																'refund_request_id'=>$refund_request_id,
																'transaction_no'=>$transaction_no,
																'receipt_no'=>$receipt_no,
																'ARRN'=>'',
																'description'=>$this->input->post('refund_reason'),
																'refund_details'=>'',
																'status'=>2,
																'admin_user_id'=>$this->session->userdata('id'),
															);
										if($this->master_model->insertRecord('payment_refund',$refund_array))
										{
											//redirect(base_url().'admin/Refund/sbirefund/'.$receipt_no.'/'.$refund_request_id.'/'.$payment[0]['amount'].'/'.$transaction_no);
											//$this->sbirefund($MerchantOrderNo,$RefundRequestID,$RefundAmount,$transaction_no);
											$pr_result = sbirefundapi($MerchantOrderNo, $refund_request_id, $transaction_no, $payment[0]['amount']);
											
											//echo "<pre>";print_r($pr_result); echo "<BR>"; exit;
											if (!$pr_result)
											{
												die("Error in Refund request API response");
											}
											
											$pr_result_str = implode("|",$pr_result);
											
											$pt_status = 1;
											
											if ($pr_result[3] == "SUCCESS")
											{
												$pr_status = "1";
												$pt_status = "3";
											}
											else
											{
												$pr_status = "0";
											}
									
											$pr_refund_details = $pr_result[4]; // found  in payment gateway response
											$pr_ARRN = $pr_result[2];  // found in payment gateway response
											
											$update_refund = array(	'ARRN'=>$pr_ARRN,
																	'refund_details'=>$pr_refund_details,
																	'status'=>$pr_status
															);
											if($this->master_model->updateRecord('payment_refund',$update_refund,array('refund_request_id'=>$refund_request_id)))
											{
												$refund_log_data = array(	'date'		=>date('Y-m-d H:i:s'),
																			'gateway'	=>$payment[0]['gateway'],
																			'data'		=>$pr_result_str,
																			'result'	=>$pr_status,	
																	);
												$this->master_model->insertRecord('refund_paymentlogs',$refund_log_data);
												
												$refund_res = $this->master_model->getRecords('payment_refund',array('refund_request_id'=>$refund_request_id));
												if(count($refund_res))
												{
													// De-activate payment (status = "3")
													if($pr_status == 1)
													{
														$this->master_model->updateRecord('payment_transaction',array('status'=>3),array('transaction_no'=>$transaction_no,'receipt_no'=>$refund_res[0]['receipt_no'],'member_regnumber'=>$refund_res[0]['member_regnumber']));
													
													
														//Fetch payment data for refunded record
														$payment_res = $this->master_model->getRecords('payment_transaction',array('transaction_no'=>$refund_res[0]['transaction_no'],'receipt_no'=>$refund_res[0]['receipt_no'],'member_regnumber'=>$refund_res[0]['member_regnumber']));
														if(count($payment_res))
														{
															if($payment_res[0]['pay_type'] == 1) // Registration
															{
																if($this->master_model->updateRecord('member_registration',array('isactive'=>'0'),array('regnumber'=>"'".$refund_res[0]['member_regnumber']."'",'regid'=>$payment_res[0]['ref_id'])))
																{
																	$this->session->set_flashdata('success','Transaction Refund Booked successfully.');
																	redirect(base_url().'admin/Refund');	
																}
																else
																{
																	$this->session->set_flashdata('error','Transaction Refund Booked successfully. But error while updating status.');
																	redirect(base_url().'admin/Refund');	
																}
															}
															else if($payment_res[0]['pay_type'] == 2)	//Exam Application
															{
																// Update pay_status in 'member_exam' to '0'
																if($this->master_model->updateRecord('member_exam',array('pay_status'=>0),array('regnumber'=>"'".$refund_res[0]['member_regnumber']."'")))
																{
																	// If refunded payment is 'NM' registration with exam application, de-activate member reg.
																	if($payment_res[0]['pg_flag'] == "IIBF_EXAM_REG")
																	{
																		$this->master_model->updateRecord('member_registration',array('isactive'=>'0'),array('regnumber'=>"'".$refund_res[0]['member_regnumber']."'"));
																	}
																	$this->session->set_flashdata('success','Transaction Refund Booked successfully.');
																	redirect(base_url().'admin/Refund');	
																}
																else
																{
																	$this->session->set_flashdata('error','Transaction Refund Booked successfully. But error while updating status.');
																	redirect(base_url().'admin/Refund');	
																}
																
																$this->session->set_flashdata('success','Transaction Refund Booked successfully.');
																redirect(base_url().'admin/Refund');
															}
															else if($payment_res[0]['pay_type'] == 3)	//Duplicate I-card
															{
																// TO DO : below logic need to check
																// Decrease i-card application count by "1"
																//if($this->master_model->updateRecord('duplicate_icard',array('icard_cnt'=>'icard_cnt-1'),array('regnumber'=>"'".$refund_res[0]['member_regnumber']."'")))
																
																if($this->master_model->updateRecord('duplicate_icard',array('pay_status'=>'0'),array('regnumber'=>"'".$refund_res[0]['member_regnumber']."'",'did'=>$payment_res[0]['ref_id'])))
																{
																	$this->session->set_flashdata('success','Transaction Refund Booked successfully.');
																	redirect(base_url().'admin/Refund');	
																}
																else
																{
																	$this->session->set_flashdata('error','Transaction Refund Booked successfully. But error while updating status.');
																	redirect(base_url().'admin/Refund');	
																}
																
																$this->session->set_flashdata('success','Transaction Refund Booked successfully.');
																redirect(base_url().'admin/Refund');
															}
														}
														else
														{
															$this->session->set_flashdata('error','Error while updating status.');
															redirect(base_url().'admin/Refund');
														}
													}
													else
													{
														$this->session->set_flashdata('error','Transaction refund failed !!');
														redirect(base_url().'admin/Refund');
													}
												}
											}
											else
											{
												$this->session->set_flashdata('error','Error while updating status.');
												redirect(base_url().'admin/Refund');
											}
											
											// update payment transaction status table for
											//echo "<BR>UPDATE PT : ".$update_pt_sql = "UPDATE `dra_payment_transaction` SET `status` = '".$pt_status."' WHERE receipt_no = '".$MerchantOrderNo."' AND gateway = 'sbiepay' AND amount = '1725.00' AND pay_type = 1 AND status = 2";
										
											//mysql_query($update_pt_sql);
										}
										else
										{
											$this->session->set_flashdata('error','Error while inserting transaction refund details');
											redirect(base_url().'admin/Refund');
										}
									}
									else
									{
										$this->session->set_flashdata('error','Error while generating refund request ID');
										redirect(base_url().'admin/Refund');
									}
								}
							}
							else
							{//echo "222";exit;
								$this->session->set_flashdata('error','Transaction is not Paid Out, cannot initiate refund.');
								redirect(base_url().'admin/Refund');
							}
						}
						else
						{
							$this->session->set_flashdata('error','Transaction refund cannot be initiated.');
							redirect(base_url().'admin/Refund');
						}
					}
					else
					{
						$this->session->set_flashdata('error','Invalid Details');
						redirect(base_url().'admin/Refund');
					}
				}
			/*}
			else
			{
				$this->session->set_flashdata('error','This Feature is under process...');
				redirect(base_url().'admin/Refund');
			}*/
		}
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Billdesk Transaction Refund</li>
							   </ol>';
		
		$this->load->view('admin/refund_index',$data);
	}
	
	// SBI ePay API for query transaction
	public function sbiqueryapi($MerchantOrderNo = "DP123369121")
	{
		//echo $MerchantOrderNo;
		//$MerchantOrderNo = "DP987787930";
		$merchIdVal = $this->config->item('sbi_merchIdVal');
		$AggregatorId = $this->config->item('sbi_AggregatorId');
		$atrn  = "";

		$queryRequest  = $atrn."|".$merchIdVal."|".$MerchantOrderNo;
		
		//echo "<br><br> Webservice URL : ".$service_url = "https://test.sbiepay.com/payagg/orderStatusQuery/getOrderStatusQuery";
		$service_url = "https://test.sbiepay.com/payagg/orderStatusQuery/getOrderStatusQuery";
		//$service_url = $this->config->item('sbi_status_query_api');
		//echo "<br><br> Webservice params : ".$post_param = "queryRequest=".$queryRequest."&aggregatorId=".$AggregatorId."&merchantId=".$merchIdVal;

		$post_param = "queryRequest=".$queryRequest."&aggregatorId=".$AggregatorId."&merchantId=".$merchIdVal;
		
		$ch = curl_init();       
		curl_setopt($ch, CURLOPT_URL,$service_url);                                                 
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post_param);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      
		//echo "<br><br>Web service response : ".$result = curl_exec($ch);
		$result = curl_exec($ch);
		$response_array = explode("|", $result);

		//print_r($response_array);
		//var_dump($result); 
		  
		curl_close($ch);
		//exit;
		return $response_array;
	}
	
	public function sbirefund($MerchantOrderNo='',$RefundRequestID='',$RefundAmount='',$transaction_no='')
	{
		
		include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		$key = $this->config->item('sbi_m_key');
		
		$pg_response_url = base_url()."admin/Refund/sbirefundresponse";

		$data["pg_form_url"] = "https://test.sbiepay.com/secure/AggregatorRefundRequest"; // SBI ePay form URL
		$data["merchIdVal"] = $this->config->item('sbi_merchIdVal');
		$data["aggId"] = $this->config->item('sbi_AggregatorId');
		$AggregatorId = $this->config->item('sbi_AggregatorId');
		
		
		
		/*$MerchantOrderNo = "DP9878295";
		$RefundRequestID = "1234541861";
		$RefundAmount = "2";
		$transaction_no = "3131325968741";	// ATRN no received from SBI
		*/
		
		//refundRequestParams= AggregatorId|MerchantId|RefundRequestID|ATRN|RefundAmount|Currency|MerchantOrderNo|ResponseURL 
		$refundRequestParams = $AggregatorId."|".$data["merchIdVal"]."|".$RefundRequestID."|".$transaction_no."|".$RefundAmount."|INR|".$MerchantOrderNo."|".$pg_response_url;

		$aes = new CryptAES();
		$aes->set_key(base64_decode($key));
		$aes->require_pkcs5();
		
		$refundRequestParams = $aes->encrypt($refundRequestParams);
		
		$data["refundRequestParams"] = $refundRequestParams; // SBI encrypted form field value

		$this->load->view('pg_sbirefund_form',$data);
	}
	
	public function sbirefundresponse()
	{
		$_REQUEST['pushRespData'] = $_REQUEST['encRefundData'];
		// Only for testing purpose
		if (isset($_REQUEST['pushRespData']))
		{
			$this->load->model('log_model');

			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			
			//$key = 'fBc5628ybRQf88f/aqDUOQ==';
			$key = $this->config->item('sbi_m_key');
			
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();

			if (isset($_REQUEST['merchIdVal']))
			{
				$merchIdVal = $_REQUEST['merchIdVal'];
			}
			if (isset($_REQUEST['pushRespData']))
			{
				//echo "<Br> encD = ".$encData = $_REQUEST['pushRespData'];
				$encData = $_REQUEST['pushRespData'];
			}
			
			//echo "<BR>".$merchIdVal;
			//echo "<BR>".$encData = $aes->decrypt($_REQUEST['pushRespData']);
			
			$encData = $aes->decrypt($_REQUEST['pushRespData']);
			$responsedata = explode("|",$encData);
			
			//print_r($responsedata);
			//exit;
			
			/*[0] => 1234541861
			[1] => SUCCESS
			[2] => Refund Booked
			[3] => 6825875498741*/
			
			//$responsedata = array("1234541861","SUCCESS","Refund Booked","6825875498741");
			
			//$responsedata = array("8888898","SUCCESS","Refund Booked","2307738981841");
			
			// Refund Payment Logs
			$pg_response = "msg=".$encData;
			$this->log_model->logrefundpayment("sbiepay", $pg_response, $responsedata[1]);
			
			//if($responsedata[1] == "SUCCESS")
			if($responsedata[1])
			{
				$payment_status = '';
				switch ($responsedata[1])
				{
					case "SUCCESS":
						$payment_status = 1;
						break;
					case "FAIL":
						$payment_status = 0;
						break;
					case "PENDING":
						$payment_status = 2;
						break;
				}
				
				$update_refund = array(	'ARRN'=>$responsedata[3],
									   	'refund_details'=>$responsedata[2],
										'status'=>$payment_status
								);
				if($this->master_model->updateRecord('payment_refund',$update_refund,array('refund_request_id'=>$responsedata[0])))
				{
					$refund_res = $this->master_model->getRecords('payment_refund',array('refund_request_id'=>$responsedata[0]));
					if(count($refund_res))
					{
						// De-activate payment (status = "3")
						if($payment_status == 1)
						{
							$this->master_model->updateRecord('payment_transaction',array('status'=>3),array('transaction_no'=>$refund_res[0]['transaction_no'],'receipt_no'=>$refund_res[0]['receipt_no'],'member_regnumber'=>$refund_res[0]['member_regnumber']));
						}
						
						$payment_res = $this->master_model->getRecords('payment_transaction',array('transaction_no'=>$refund_res[0]['transaction_no'],'receipt_no'=>$refund_res[0]['receipt_no'],'member_regnumber'=>$refund_res[0]['member_regnumber']));
						if(count($payment_res))
						{
							if($payment_res[0]['pay_type'] == 1) // Registration
							{
								if($this->master_model->updateRecord('member_registration',array('isactive'=>'0'),array('regnumber'=>"'".$refund_res[0]['member_regnumber']."'")))
								{
									$this->session->set_flashdata('success','Transaction Refund Booked successfully.');
									redirect(base_url().'admin/Refund');	
								}
								else
								{
									$this->session->set_flashdata('error','Transaction Refund Booked successfully. But error while updating status.');
									redirect(base_url().'admin/Refund');	
								}
							}
							else if($payment_res[0]['pay_type'] == 3)	//Duplicate I-card
							{
								// TO DO : below logic need to test properly 
								// Decrease i-card application count by "1"
								/*if($this->master_model->updateRecord('duplicate_icard',array('icard_cnt'=>'icard_cnt-1'),array('regnumber'=>"'".$refund_res[0]['member_regnumber']."'")))
								{
									$this->session->set_flashdata('success','Transaction Refund Booked successfully.');
									redirect(base_url().'admin/Refund');	
								}
								else
								{
									$this->session->set_flashdata('error','Transaction Refund Booked successfully. But error while updating status.');
									redirect(base_url().'admin/Refund');	
								}*/
								
								$this->session->set_flashdata('success','Transaction Refund Booked successfully.');
								redirect(base_url().'admin/Refund');
							}
						}
					}
				}
				else
				{
					$this->session->set_flashdata('error','Error while updating status.');
					redirect(base_url().'admin/Refund');
				}
			}
			
			// add payment responce in log
			//$pg_response = "encRefundData=".$encData."&merchIdVal=".$merchIdVal;
			//$this->log_model->logtransaction("SBI_ePay", $pg_response, $responsedata[2]);
		}
		else
		{
			//die("Please try again...");
			$this->session->set_flashdata('error','Please try again...');
			redirect(base_url().'admin/Refund');	
		}
	}
}