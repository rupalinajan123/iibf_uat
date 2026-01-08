<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/** Last Updated : Pooja Mane On : 2023-04-19(garp table update)**/
	class Payment extends CI_Controller 
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
			$this->load->model('billdesk_pg_model');
			$this->load->helper('gstrecovery_invoice_helper');
			$this->sms_template_id = '';
			$this->load->model('refund_after_capacity_full');
			$this->load->helper('update_image_name_helper');
			//accedd denied due to GST
			//$this->master_model->warning();
		}
		
		public function exam()
		{
			$pg_reponse_url = "https://pg.sifyitest.com/iibfdemo/iibfexam_pg/response_iibf.php";
			$data["pg_form_url"] = "https://www.billdesk.com/pgidsk/pgmerc/IIBFPaymentoption.jsp"; // BillDesk form URL
			$data["msg"] = "IIBF|2138118|NA|1|NA|NA|NA|INR|NA|R|iibf|NA|NA|F|iibfexam|7609717|20201701|NA|NA|NA|NA|".$pg_reponse_url."|3592212800"; // BillDesk form field value
			$this->load->view('pg_bd_form',$data);
		}
		
		public function examresponse()
		{
			error_reporting(E_ALL);
			//$pg_reply = @file_get_contents('php://input');
			$myfile = fopen("BD_callback.txt", "a") or die("Unable to open file!");
			$txt = "user id date";
			//fwrite($myfile, "\n". $pg_reply);
			//$raw_post_data = file_get_contents('php://input');
			//fwrite($myfile, "\ntesttset". implode(" ",$_REQUEST)
			fwrite($myfile, "\ntesttset ".$txt);
			//		fwrite($myfile, "\noooooooooooooooooooo ");
			fclose($myfile);
			// Check payment status
			//echo "Hi";
			$this->load->library('email');
			$this->load->model('Emailsending');
			$final_str= "BD callback executed ";
			$info_arr=array(
			'to'=> "sagar.deshmukh@esds.co.in",
			'from'=> "starsagar123@gmail.com",
			'subject'=>"BD callback",
			'message'=>$final_str." ***** ".$_REQUEST['msg']
			);
			$this->Emailsending->mailsend($info_arr);
			// Check payment status
			echo "Hi";
			// As per status update DB and redirect to success/failure page
		}
		
		public function billdesk_response_validation($result = "")
		{
			//$result = "IIBF|2138196|HYBK4897974090|39740|00000002.00|YBK|NA|01|INR|DIRECT|NA|NA|NA|15-11-2016 13:23:02|0300|NA|iibfexam|510296983|32201701|NA|NA|NA|NA|NA|Merchant transaction successfull|2915503922";
			//$result = "IIBF|2138198|HHMP4898440631|NA|2.00|HMP|NA|NA|INR|DIRECT|NA|NA|NA|15-11-2016 16:20:20|0399|NA|iibfexam|510296983|32201701|NA|NA|NA|NA|NA|Canceled By User|382207639";
			//$result = "IIBF|IIBFEXAM31|HYBK4901239365|11551|00000001.00|YBK|NA|01|INR|DIRECT|NA|NA|NA|16-11-2016 17:35:30|0300|NA|iibfexam|201678030000000|2|NA|NA|NA|NA|NA|Merchant transaction successfull|3449079101";
			//$result = "IIBF|";
			error_reporting(0);  // do not show errors for S2S response
			if ($result != "")
			{
				$result_array = explode("|",$result);
				//print_r($result_array); exit;
				$errormsg = array();
				$return_flag = 1;
				// Config params
				$MerchantID = $this->config->item('bd_MerchantID');
				$SecurityID = $this->config->item('bd_SecurityID');
				$checksum_key = $this->config->item('bd_ChecksumKey');
				// TO DO :
				$MerchantOrderNo = filter_var($result_array[1], FILTER_SANITIZE_NUMBER_INT);//$result_array[1]; // To DO: temp testing changes please remove it and use valid recipt id
				// No. of tokens/params validation
				if (count($result_array) != 26)
				{
					$errormsg[] = "Invalide response param count";
					$return_flag = 0;
				}
				// validate MerchantID
				if ($MerchantID != $result_array[0])
				{
					$errormsg[] = "Invalide MerchantID in response";
					$return_flag = 0;
				}
				/*// validate AuthStatus
					if ($result_array[14] != "0300")
					{
					$errormsg[] = "Transaction unsuccessfull";
					$return_flag = 0;
				}*/
				// Validate checksum
				preg_match_all("/(.*)\|([0-9]*)$/", $result, $preg_result);
				$res_checksum = $result_array[25];
				$msg_without_Checksum = $preg_result[1][0];
				$checksum_key = $this->config->item('bd_ChecksumKey');
				$string_new = $msg_without_Checksum."|".$checksum_key;
				$checksum = crc32($string_new);
				if ($res_checksum != $checksum)
				{
					$errormsg[] = "Invalide checksum value";
					$return_flag = 0;
				}
				$pt_result = $this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'amount');
				// Validate order number
				if (count($pt_result) < 1)
				{
					$errormsg[] = "Invalide order number in response param";
					$return_flag = 0;
				}
				// Validate order number
				/*if (count($pt_result) > 1)
					{
					$errormsg[] = "Duplicate order number found in payment_transaction table";
					//$return_flag = 0;
				}*/
				// Validate amount
				if (count($pt_result))
				{
					$pt_amount = $pt_result[0]['amount'];
					if ($pt_amount != $result_array[4])
					{
						$errormsg[] = "Invalide amount in response param";
						$return_flag = 0;
					}
				}
				//echo " return_flag = ".$return_flag;
				//print_r($errormsg); exit;
				return $return_flag;
			}
			else
			{
				return 0;
			}
		}
		
		public function bdcallback()
		{
			//error_reporting(E_ALL);
			//$_REQUEST['msg'] = "IIBF|2138196|HYBK4897974090|39740|00000002.00|YBK|NA|01|INR|DIRECT|NA|NA|NA|15-11-2016 13:23:02|0300|NA|iibfexam|510296983|32201701|NA|NA|NA|NA|NA|Merchant transaction successfull|2915503922";
			$msg = isset($_REQUEST['msg']) ? rawurlencode($_REQUEST['msg']):"";
			$myfile = fopen("bd_epay_callback.txt", "a") or die("Unable to open file!");
			//$txt = "user id date--";
			//fwrite($myfile, "\n". $pg_reply);
			//$raw_post_data = file_get_contents('php://input');
			//fwrite($myfile, "\ntesttset". implode(" ",$_REQUEST)
			//		fwrite($myfile, "\ntesttset ".count($_REQUEST)." ".$txt);
			fwrite($myfile, "\nmsg=".$msg." \n ".$_REQUEST['msg']);
			fclose($myfile);
			// Check payment status
			//echo "Hi";
			$this->load->library('email');
			$this->load->model('Emailsending');
			$final_str= "BD callback executed ";
			$info_arr=array(
			'to'=> "sagar.deshmukh@esds.co.in",
			'from'=> "starsagar123@gmail.com",
			'subject'=>"BD callback",
			'message'=>$final_str." ***** ".$msg." \n ".$_REQUEST['msg']
			);
			$this->Emailsending->mailsend($info_arr);
			// first log response in DB
			$pg_res_param = $_REQUEST['msg'];
			$pg_res_array = explode("|", $pg_res_param);   //print_r($pg_res); exit;
			// add payment responce in log
			$this->load->model('log_model');
			$pg_response = "msg=".$pg_res_param;
			$this->log_model->logtransaction("billdesk", $pg_response, isset($pg_res_array[14]) ? $pg_res_array[14] : '');
			// call response validation function here
			if ($this->billdesk_response_validation($pg_res_param))
			{
				if($pg_res_array[16] == "iibfexam")
				{
					$MerchantOrderNo = filter_var($pg_res_array[1], FILTER_SANITIZE_NUMBER_INT);//$responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
					// Check order status in DB, if already paid then only send ack to billdesk
					$pt_result = $this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo));
					if (count($pt_result))
					{
						if ($pt_result[0]['status'] == 1)
						{
							die("SUCCESS:".$pg_res_array[2]);
						}
					}
					$transaction_no = $pg_res_array[2];
					$payment_status = 2;
					switch ($pg_res_array[14])
					{
						case "0300":  // Successful Transaction
						$payment_status = 1;
						break;
						case "0399":  // Failed Transaction
						$payment_status = 0;
						break;
						/*case "0002":  // Pending Transaction
							$payment_status = 2;
							break;
							case "0001":  // Error at BillDesk (Cancel Transaction)
							$payment_status = 0;
						break;*/
						default:
						$payment_status = 0;
					}
					if ($payment_status == 1)
					{
						$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $pg_res_array[24],'auth_code' => $pg_res_array[14],'bankcode' => $pg_res_array[5],'paymode' => $pg_res_array[7],'callback'=>'S2S');
						$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
						// TO DO: Other changes as per success transaction
					}
					else if($payment_status == 0)
					{
						$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[24],'auth_code' => $pg_res_array[14],'bankcode' => $pg_res_array[5],'paymode' => $pg_res_array[7],'callback'=>'S2S');
						$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
						// TO DO: Other changes as per fail transaction
					}
					echo "SUCCESS:".$pg_res_array[2];
					exit;
				}
			}
			else
			{
				die("INVALID:".$pg_res_array[2]);
			}
			//$_REQUEST['msg'] = "IIBF|2138195|HHMP4897894246|NA|2.00|HMP|NA|NA|INR|DIRECT|NA|NA|NA|15-11-2016 12:55:48|0399|NA|iibfexam|510296983|32201701|NA|NA|NA|NA|NA|Canceled By User|1435616898";
			/*if (isset($_REQUEST['msg']))
				{
				//echo "<pre>";
				//print_r($_REQUEST);
				//echo "<BR> Response : ".$_REQUEST['msg'];
				// validate checksum
				preg_match_all("/(.*)\|([0-9]*)$/", $_REQUEST['msg'],$result);
				//print_r($result);
				$res_checksum = $result[2][0];
				//echo "<BR> res = ".$res_checksum;
				$msg_without_Checksum = $result[1][0];
				//echo "<BR> msg_without_Checksum = ".$msg_without_Checksum;
				$common_string = "sRKUUgdDrMGL";
				$string_new = $msg_without_Checksum."|".$common_string;
				$checksum = crc32($string_new);
				//echo "<BR> new = ".$checksum;
				$pg_res = explode("|",$msg_without_Checksum);
				if ($res_checksum == $checksum)
				{
				//echo "<BR>Checksum validated successfully<br>";
				echo "SUCCESS:".$pg_res[2];
				}
				else
				{
				//echo "<BR>Checksum validation unsuccessful<br>";
				echo "INVALID:".$pg_res[2];
				}
				}
				else
				{
				die("Please try again...");	
			}*/
			//$pg_reply = @file_get_contents('php://input');
			exit;
		}
		
		// BillDesk API for query transaction
		public function bdqueryapi($MerchantOrderNo = "HYBK4897974090")
		{
			$MerchantID = $this->config->item('bd_MerchantID');
			$SecurityID = $this->config->item('bd_SecurityID');
			$checksum_key = $this->config->item('bd_ChecksumKey');
			$RequestType = "0122";
			$currenttimestamp = date("YmdHis");
			$pg_form_url = $this->config->item('bd_pg_form_url'); // SBI ePay form URL
			$data["pg_form_url"] = $pg_form_url;
			/*
				Format:
				requestparameter=RequestType|Merchant ID|Customer ID|Current Date/Time stamp|Checksum
				(Current Date/Time stamp = yyyymmdd24hhmmss)
				Ex.	
				requestparameter=0122|IIBF|ARP10234|20130325182510|455057528
			*/
			$requestparameter = $RequestType."|".$MerchantID."|".$MerchantOrderNo."|".$currenttimestamp;
			// Generate checksum for request parameter
			$req_param = $requestparameter."|".$checksum_key;
			$checksum = crc32($req_param);
			$requestparameter = $requestparameter . "|".$checksum;
			//$data["msg"] = $requestparameter;
			//print_r($data); exit;
			//$this->load->view('pg_bd_form',$data);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $pg_form_url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,
			"msg=".$requestparameter);
			// in real life you should use something like:
			// curl_setopt($ch, CURLOPT_POSTFIELDS, 
			//          http_build_query(array('postvar1' => 'value1')));
			// receive server response ...
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec ($ch);
			curl_close ($ch);
			print_r($server_output);
			echo "done";
			exit;
			// further processing ....
			//if ($server_output == "OK") { ... } else { ... }
		}
		
		public function icardresponse()
		{
			// Check payment status
			echo "Hi";
			// As per status update DB and redirect to success/failure page
		}
		
		/*public function draexam()
			{
			include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$merchIdVal = $this->config->item('sbi_merchIdVal');
			$AggregatorId = $this->config->item('sbi_AggregatorId');
			$pg_success_url = base_url()."Payment/sbitranssuccess";
			$pg_fail_url    = base_url()."Payment/sbitransfail";
			$amount = 2;
			$data["pg_form_url"] = $this->config->item('sbi_pg_form_url'); // SBI ePay form URL
			$data["merchIdVal"] = $merchIdVal;
			$EncryptTrans = "1000003|DOM|IN|INR|2|Other|".$pg_success_url."|".$pg_fail_url."|SBIEPAY|DP9878315|2|NB|ONLINE|ONLINE";
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$EncryptTrans = $aes->encrypt($EncryptTrans);
			$data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
			$this->load->view('pg_sbi_form',$data);
			}
			public function sbitranssuccess()
			{
			echo "Transaction sucessfull";
			include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = 'fBc5628ybRQf88f/aqDUOQ==';
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			//echo $pg_reply = @file_get_contents('php://input');
			if (isset($_REQUEST['encData']))
			{
			// add responce in log
			if (isset($_REQUEST['merchIdVal']))
			{
			$merchIdVal = $_REQUEST['merchIdVal'];
			}
			if (isset($_REQUEST['Bank_Code']))
			{
			$Bank_Code = $_REQUEST['Bank_Code'];
			}
			if (isset($_REQUEST['encData']))
			{
			echo "<Br> encD = ".$encData = $_REQUEST['encData'];
			}
			echo "<BR>".$merchIdVal;
			echo "<BR>".$Bank_Code;
			echo "<BR>".$encData = $aes->decrypt($_REQUEST['encData']);
			$responsedata = explode("|",$encData);
			print_r($responsedata);
			//$merchIdVal = merchIdVal
			}
			else
			{
			die("Please try again...");
			}
			exit;
			}
			public function sbitransfail()
			{
			echo "Transaction failed";
			include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = "fBc5628ybRQf88f/aqDUOQ==";
			$aes = new CryptAES();
			$aes->set_key(base64_encode($key));
			$aes->require_pkcs5();
			echo $pg_reply = @file_get_contents('php://input');
			if ($pg_reply)
			{
			// add responce in log
			$pg_params = explode("&", $pg_reply);
			foreach($pg_params as $pg_param)
			{
			$field = explode("=", $pg_param);
			if ($field[0] == "merchIdVal")
			{
			$merchIdVal = $field[1];
			continue;
			}
			if ($field[0] == "Bank_Code")
			{
			$Bank_Code = $field[1];
			continue;
			}
			if ($field[0] == "encData")
			{
			echo "<Br> encD = ".$encData = $field[1];
			continue;
			}
			}
			echo "<BR>".$merchIdVal;
			echo "<BR>".$Bank_Code;
			echo "<BR>".$encData = $aes->decrypt($encData);
			//$merchIdVal = merchIdVal
			}
			else
			{
			die("Please try again...");
			}
			exit;
		}*/
		
		/*public function draexamresponse()
			{
			// Check payment status
			echo "Hi";
			// As per status update DB and redirect to success/failure page
		}*/
		
		// SBI ePay API for query transaction
		public function sbiqueryapi($MerchantOrderNo = "DP123369121")
		{
			$merchIdVal = $this->config->item('sbi_merchIdVal');
			$AggregatorId = $this->config->item('sbi_AggregatorId');
			$atrn  = "";
			$queryRequest  = $atrn."|".$merchIdVal."|".$MerchantOrderNo;
			//echo "<br><br> Webservice URL : ".$service_url = "https://test.sbiepay.com/payagg/orderStatusQuery/getOrderStatusQuery";
			$service_url = $this->config->item('sbi_status_query_api');
			echo "<br><br> Webservice params : ".$post_param = "queryRequest=".$queryRequest."&aggregatorId=".$AggregatorId."&merchantId=".$merchIdVal;
			$ch = curl_init();       
			curl_setopt($ch,CURLOPT_URL,$service_url);                                                 
			curl_setopt($ch, CURLOPT_POST, true); 
			curl_setopt($ch, CURLOPT_POSTFIELDS,$post_param);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      
			echo "<br><br>Web service response : ".$result = curl_exec($ch);
			$response_array = explode("|", $result);
			print_r($response_array);
			//var_dump($result);   
			curl_close($ch);
		}
		
		// SBI ePay API for Refund and Cancellation API
		public function sbirefundapi($MerchantOrderNo = "DP987787930")
		{
			$merchIdVal = $this->config->item('sbi_merchIdVal');
			$AggregatorId = $this->config->item('sbi_AggregatorId');
			$refundRequestId = "RID12345";
			$atrn   = "7008771670841";
			$refundAmount  = "2";
			$refundAmountCurrency = "INR";
			$refundRequest = $AggregatorId."|".$merchIdVal."|".$refundRequestId."|".$atrn."|".$refundAmount."|".$refundAmountCurrency."|".$MerchantOrderNo;
			echo "<br><br> Webservice URL : ".$service_url = "https://test.sbiepay.com/payagg/orderRefundCancellation/bookRefundCancellation";
			//$service_url = $this->config->item('sbi_refund_canc_api');
			echo "<br><br> Webservice params : ".$post_param = "refundRequest=".$refundRequest."&aggregatorId=".$AggregatorId."&merchantId=".$merchIdVal;
			$ch = curl_init();       
			curl_setopt($ch,CURLOPT_URL,$service_url);                                                 
			curl_setopt($ch, CURLOPT_POST, true); 
			curl_setopt($ch, CURLOPT_POSTFIELDS,$post_param); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      
			echo "<br><br>Web service response : ".$result = curl_exec($ch);
			$response_array = explode("|", $result);
			print_r($response_array);
			//var_dump($result);   
			curl_close($ch);
		}
		
		public function sbicancellationapi($MerchantOrderNo = "DP123369121")
		{
			echo "<BR>";
			echo "Order Number = ".generate_order_id($field_name = "reg_sbi_order_id");
			echo "<BR>";
			echo "<BR>";
			echo "Order Number = ".generate_order_id($field_name = "bd_exam_order_id");
			echo "<BR>";
			echo "<BR>";
			echo "Order Number = ".generate_order_id($field_name = "idcard_sbi_order_id");
			echo "<BR>";
			echo "<BR>";
			echo "DRA Order Number = ".generate_dra_order_id();
			echo "<BR>";
			echo "<BR>";
			echo "Member reg id sequense = ".generate_mem_reg_num();
			echo "<BR>";
			echo "<BR>";
			echo "Non Member reg id sequense = ".generate_nm_reg_num();
			echo "<BR>";
			echo "<BR>";
			echo "DBF Member reg id sequense = ".generate_dbf_reg_num();
			echo "<BR>";
			echo "<BR>";
			echo "DRA Member reg id sequense = ".generate_dra_reg_num();
			echo "<BR>";
			exit;
		}
		
		public function sbiquerytransaction($MerchantOrderNo = "DP123369121")
		{
			error_reporting(E_ALL);
			//include('CryptAES.php');
			//$this->load->library('CryptAES');
			include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$merchIdVal = $this->config->item('sbi_merchIdVal');
			$AggregatorId = $this->config->item('sbi_AggregatorId');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$pg_reponse_url = base_url()."Payment/sbiquerysuccess";
			$data["pg_form_url"] = $this->config->item('sbi_query_form_url'); // SBI ePay form URL
			$data["merchIdVal"] = $merchIdVal;
			$data["aggIdVal"]   = $AggregatorId;
			$encryptQuery = "|".$merchIdVal."|".$MerchantOrderNo."|".$pg_reponse_url; // SBI encrypted form field value
			$data["encryptQuery"] = $aes->encrypt($encryptQuery);
			print_r($data);
			$this->load->view('pg_sbi_trans_verify_form',$data);
		}
		
		public function sbiquerysuccess()
		{
			$_REQUEST['pushRespData'] = $_REQUEST['encStatusData'];
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
				if (isset($_REQUEST['Bank_Code']))
				{
					$Bank_Code = $_REQUEST['Bank_Code'];
				}
				if (isset($_REQUEST['pushRespData']))
				{
					echo "<Br> encD = ".$encData = $_REQUEST['pushRespData'];
				}
				echo "<BR>".$merchIdVal;
				echo "<BR>".$Bank_Code;
				echo "<BR>".$encData = $aes->decrypt($_REQUEST['pushRespData']);
				$responsedata = explode("|",$encData);
				print_r($responsedata);
				if ($responsedata[6] == "MEM_REG")
				{
					$MerchantOrderNo = filter_var($responsedata[0], FILTER_SANITIZE_NUMBER_INT);//$responsedata[0];
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					switch ($responsedata[2])
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
					// Update member_regnumber, description, transaction_details, ref_id	
					/*$update_data = array(
						'transaction_no' => $transaction_no,
						'status' => $payment_status
					);*/
					echo "MerchantOrderNo = ".$MerchantOrderNo; print_r($update_data);
					//echo $this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
				}
				else if($responsedata[6] == "DRA_EXAM")
				{
				}
				else
				{
				}
				// add payment responce in log
				$pg_response = "encStatusData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
				$this->log_model->logtransaction("SBI_ePay", $pg_response, $responsedata[2]);
			}
			else
			{
				die("Please try again....");
			}
		}
		
		public function sbicallback()		
		{
			exit;
			//error_reporting(E_ALL);
			//ini_set('display_errors', 1);
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$encData = $aes->decrypt($_REQUEST['pushRespData']);
			$responsedata = explode("|",$encData);
			//print_r($responsedata);
			//$cust = explode('^',$responsedata[6]);
			//$responsedata[6] = $cust['1'];
			$resp_data = json_encode($_REQUEST);
			$resp_array = array('receipt_no'	=> $responsedata[0],
			'txn_status' 	=> $responsedata[2],
			'txn_data' 		=> $encData,
			'response_data' => $resp_data,
			//'remark' 		=> '',
			'resp_date' 	=> date('Y-m-d H:i:s'),
			);
			$this->master_model->insertRecord('payment_s2s_log', $resp_array);
			
			if (isset($_REQUEST['pushRespData']))
			{
				$this->load->model('log_model');
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('sbi_m_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				if (isset($_REQUEST['merchIdVal']))
				{
					$merchIdVal = $_REQUEST['merchIdVal'];
				}
				
				if (isset($_REQUEST['Bank_Code']))
				{
					$Bank_Code = $_REQUEST['Bank_Code'];
				}
				
				if (isset($_REQUEST['pushRespData']))
				{
					$encData = $_REQUEST['pushRespData'];
				}
				
				$encData = $aes->decrypt($_REQUEST['pushRespData']);
				$responsedata = explode("|",$encData);
				//print_r($responsedata);
				$cust=explode('^',$responsedata[6]);
				$responsedata[6]=$cust['1'];
				//############################# check for late (90 min)response s2s and refund
				$this->db->order_by('id','DESC');
				$this->db->limit(1); 
				$get_receipt=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$responsedata[0],'gateway'=>'sbiepay'),'pg_flag,receipt_no,member_regnumber,date,status');
				
				$val = 0;
				$current_date = date('Y-m-d H:i:s'); // 
				$today_date = date('Y-m-d'); // FOR - SQL
				$txn_date = $get_receipt[0]['date']; 
				$pay_status = $get_receipt[0]['status']; 
				$order_id = $get_receipt[0]['receipt_no']; 
				$start_date = new DateTime($txn_date, new DateTimeZone('Asia/Kolkata'));
				$end_date = new DateTime($current_date, new DateTimeZone('Asia/Kolkata'));
				$interval = $start_date->diff($end_date);
				$hours   = $interval->format('%h'); 
				$minutes = $interval->format('%i');
				$minutes_diff = ($hours * 60 + $minutes);
				if($minutes_diff>120)
				{
					if($responsedata[2]=='SUCCESS')
					{
						$refund_insert_array=array('receipt_no'=>$responsedata[0],'response'=>$encData);
						$inser_id=$this->master_model->insertRecord('S2S_direcrt_refund',$refund_insert_array);
					}
					exit;	
				}
				//#############################END of check for late (90 min)response s2s and refund 
				// Examination
				if($responsedata[6]=='iibfexam')
				{
					if($cust['2']!='iibfdra')	// Not DRA Exam Application
					{
						$MerchantOrderNo = $responsedata[0]; 
						$get_pg_flag=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'pg_flag');
						$responsedata[6]=$get_pg_flag[0]['pg_flag'];
					}
					else
					{
						$responsedata[6]=$cust['2'];
					}
				}
				// Registration
				if($responsedata[6]=='iibfregn')
				{
					if($cust['2']!='iibfregn')	// Not New Member registration
					{
						$MerchantOrderNo = $responsedata[0]; 
						$get_pg_flag=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'pg_flag');
						$responsedata[6]=$get_pg_flag[0]['pg_flag'];
						//$responsedata[6]=$cust['2'];
					}
				}
				if ($responsedata[6] == "iibfregn")
				{
					sleep(8);
					$MerchantOrderNo = $responsedata[0]; 
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					switch ($responsedata[2])
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
					if($payment_status==1)
					{
						$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status,id');
						//check user payment status is updated by S2S or not
						if($get_user_regnum_info[0]['status']==2)
						{
							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
							$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
							if($this->db->affected_rows())
							{		
								$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status,id');
								if($get_payment_status[0]['status']==1)
								{
									$reg_id=$get_user_regnum_info[0]['ref_id'];
									//$applicationNo = generate_mem_reg_num();
									$applicationNo =generate_O_memreg($reg_id);
									####### update member number #########
									$update_data = array('member_regnumber' => $applicationNo);
									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
									/*$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber');*/
									if(count($get_user_regnum_info) > 0)
									{
										$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
										$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
										$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,mobile');
										########get Old image Name############
										$log_title ="Ordinory member OLD Image S2S :".$reg_id;
										$log_message = serialize($user_info);
										$rId = $reg_id;
										$regNo = $reg_id;
										storedUserActivity($log_title, $log_message, $rId, $regNo);
										$upd_files = array();
										$photo_file = 'p_'.$applicationNo.'.jpg';
										$sign_file = 's_'.$applicationNo.'.jpg';
										$proof_file = 'pr_'.$applicationNo.'.jpg';
										if(@ rename("./uploads/photograph/".$user_info[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
										{	$upd_files['scannedphoto'] = $photo_file;	}
										if(@ rename("./uploads/scansignature/".$user_info[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
										{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
										if(@ rename("./uploads/idproof/".$user_info[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
										{	$upd_files['idproofphoto'] = $proof_file;	}
										if(count($upd_files)>0)
										{
											$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
											$log_title ="Ordinory member PIC update S2S :".$reg_id;
											$log_message = serialize($upd_files);
											$rId = $reg_id;
											$regNo = $reg_id;
											storedUserActivity($log_title, $log_message, $rId, $regNo);
										}
										else
										{
											$upd_files['scannedphoto'] = $photo_file;
											$upd_files['scannedsignaturephoto'] = $sign_file;	
											$upd_files['idproofphoto'] = $proof_file;
											$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
											$log_title ="Member MANUAL PICS Update S2S :".$reg_id;
											$log_message = serialize($upd_files);
											$rId = $reg_id;
											$regNo = $reg_id;
											storedUserActivity($log_title, $log_message, $rId, $regNo);	
										}
									}
									//email to user
									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_register_email'));
									$this->sms_template_id = 'DPDoOIwMR';
									if(count($emailerstr) > 0)
									{
										include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
										$key = $this->config->item('pass_key');
										$aes = new CryptAES();
										$aes->set_key(base64_decode($key));
										$aes->require_pkcs5();
										//$encPass = $aes->encrypt(trim($user_info[0]['usrpassword']));
										$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
										//$decpass = $aes->decrypt($user_info[0]['usrpassword']);
										$newstring = str_replace("#application_num#", "".$applicationNo."",  $emailerstr[0]['emailer_text']);
										$final_str= str_replace("#password#", "".$decpass."",  $newstring);
										$info_arr=array('to'=>$user_info[0]['email'],
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'].' '.$applicationNo,
										'message'=>$final_str
										);
										//set invoice
										$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum_info[0]['id']));
										//echo $this->db->last_query();exit;
										if(count($getinvoice_number) > 0)
										{
											/*if($getinvoice_number[0]['state_of_center']=='JAM')
												{
												$invoiceNumber = generate_registration_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
												if($invoiceNumber)
												{
												$invoiceNumber=$this->config->item('mem_invoice_no_prefix_jammu').$invoiceNumber;
												}
												}
												else
											{*/
											$invoiceNumber = generate_registration_invoice_number($getinvoice_number[0]['invoice_id']);
											if($invoiceNumber)
											{
												$invoiceNumber=$this->config->item('mem_invoice_no_prefix').$invoiceNumber;
												//}
											}
											$update_data = array('invoice_no' => $invoiceNumber,'member_no'=>$applicationNo,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
											$this->db->where('pay_txn_id',$get_user_regnum_info[0]['id']);
											$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
											$attachpath=genarate_reg_invoice($getinvoice_number[0]['invoice_id']);
										}	
										if($attachpath!='')
										{	
											$sms_newstring = str_replace("#application_num#", "".$applicationNo."",  $emailerstr[0]['sms_text']);
											$sms_final_str= str_replace("#password#", "".$decpass."",  $sms_newstring);
											//$this->master_model->send_sms($user_info[0]['mobile'],$sms_final_str);
											//$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']),$sms_final_str,$this->sms_template_id);
											$this->master_model->send_sms_common_all($user_info[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

											//if($this->Emailsending->mailsend($info_arr))
											if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
											{
												//$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
												//redirect(base_url('register/acknowledge/'));
											}
											else
											{
												//echo 'Error while sending email';
												//$this->session->set_flashdata('error','Error while sending email !!');
												//redirect(base_url('register/preview/'));
											}
										}
										else
										{
											//$this->session->set_flashdata('error','Error while sending email !!');
											//redirect(base_url('register/preview/'));
										}
									}
								}
							}
						}
					}	
					else if($payment_status==0)
					{
						$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status');
						if($get_user_regnum_info[0]['status']==2)
						{
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
						}
					}
				}
				if ($responsedata[6] == "iibfren")
				{
					die();
					sleep(8);
					$MerchantOrderNo = $responsedata[0]; 
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					switch ($responsedata[2])
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
					if($payment_status==1)
					{
						$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status,id');
						//check user payment status is updated by s2s or not
						if ($get_user_regnum_info[0]['status'] == 2) {
							$reg_id = $get_user_regnum_info[0]['ref_id'];
							$applicationNo = $get_user_regnum_info[0]['member_regnumber']; // User Entered Number
							$update_data = array('member_regnumber' => $applicationNo, 'transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'S2S');
							$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
						  if (count($get_user_regnum_info) > 0) {
								$update_mem_data = array('isactive' => '1', 'regnumber' => $applicationNo, 'is_renewal' => 1);
								$this->master_model->updateRecord('member_registration', $update_mem_data, array('regid' => $reg_id));
								$user_info = $this->master_model->getRecords('member_registration', array('regid' => $reg_id), 'usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,mobile');
								$upd_files = array();
								$photo_file = 'p_' . $applicationNo . '.jpg';
								$sign_file = 's_' . $applicationNo . '.jpg';
								$proof_file = 'pr_' . $applicationNo . '.jpg';
								if (@rename("./uploads/photograph/" . $user_info[0]['scannedphoto'], "./uploads/photograph/" . $photo_file)) {
									$upd_files['scannedphoto'] = $photo_file;
								}
								if (@rename("./uploads/scansignature/" . $user_info[0]['scannedsignaturephoto'], "./uploads/scansignature/" . $sign_file)) {
									$upd_files['scannedsignaturephoto'] = $sign_file;
								}
								if (@rename("./uploads/idproof/" . $user_info[0]['idproofphoto'], "./uploads/idproof/" . $proof_file)) {
									$upd_files['idproofphoto'] = $proof_file;
								}
								if (count($upd_files) > 0) {
									$this->master_model->updateRecord('member_registration', $upd_files, array('regid' => $reg_id));
								}
							}
							//Manage Log
							$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
							$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
							//email to user
 						  $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' =>'user_renewal_email'));
 						  $this->sms_template_id = 'MQvtFIwMg';
							if (count($emailerstr) > 0) {
								include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
								$key = $this->config->item('pass_key');
								$aes = new CryptAES();
								$aes->set_key(base64_decode($key));
								$aes->require_pkcs5();
								//$encPass = $aes->encrypt(trim($user_info[0]['usrpassword']));
								$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
								//$decpass = $aes->decrypt($user_info[0]['usrpassword']);
								$newstring = str_replace("#application_num#", "" . $applicationNo . "", $emailerstr[0]['emailer_text']);
								$final_str = str_replace("#password#", "" . $decpass . "", $newstring);
								$info_arr = array('to' => $user_info[0]['email'],
								//'to'=>'kumartupe@gmail.com',
								'from' => $emailerstr[0]['from'], 'subject' => $emailerstr[0]['subject'].' '.$applicationNo, 'message' => $final_str);
								// INVOICE CODE
								$getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $get_user_regnum_info[0]['id']));
								if (count($getinvoice_number) > 0) {
									/*if ($getinvoice_number[0]['state_of_center'] == 'JAM') {
										$invoiceNumber = generate_renewal_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
										if ($invoiceNumber) {
										$invoiceNumber = $this->config->item('renewal_mem_invoice_no_prefix_jammu') . $invoiceNumber;
										}
									} else {*/
									$invoiceNumber = generate_renewal_invoice_number($getinvoice_number[0]['invoice_id']);
									if ($invoiceNumber) {
										$invoiceNumber = $this->config->item('renewal_mem_invoice_no_prefix') . $invoiceNumber;
									}
									/*}*/
									$update_data = array('invoice_no' => $invoiceNumber, 'member_no' => $applicationNo, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
									$this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
									$this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
									$attachpath = genarate_renewal_invoice($getinvoice_number[0]['invoice_id']);
								}
								if ($attachpath != '') {
									$this->Emailsending->mailsend_attch($info_arr, $attachpath);
								} 
							}
						}
					}	
					else if($payment_status==0)
					{
						$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status');
						if($get_user_regnum_info[0]['status']==2)
						{
							$update_data = array('transaction_no' => $transaction_no, 'status' => 0, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'S2S');
							$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
						}
					}
				}
				else if($responsedata[6] == "iibfdup")
				{
					sleep(8);
					$MerchantOrderNo = $responsedata[0]; 
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					switch ($responsedata[2])
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
					if($payment_status==1)
					{
						// Handle transaction sucess case 
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,id');
						if($get_user_regnum[0]['status']==2)
						{
							if(count($get_user_regnum) > 0)
							{
								$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
							}
							$update_data = array('pay_status' => '1');
							$this->master_model->updateRecord('duplicate_icard',$update_data,array('did'=>$get_user_regnum[0]['ref_id']));
							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,id');
							if($get_payment_status[0]['status']==1)
							{
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'duplicate_id'));
								$this->sms_template_id = 'NA';
								if(count($emailerstr) > 0 && (count($get_user_regnum) > 0))
								{
									//Query to get user details
									$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'namesub,firstname,middlename,lastname,email');
									$username=$user_info[0]['namesub'].' '.$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									$newstring1 = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);
									$newstring2 = str_replace("#MEM_NO#", "".$get_user_regnum[0]['member_regnumber']."", $newstring1 );
									$final_str= str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring2);
									$info_arr = array('to'=>$user_info[0]['email'],'from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],'message'=>$final_str);
									//genertate invoice and email send with invoice attach 8-7-2017					
									//get invoice	
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum[0]['id']));
									if(count($getinvoice_number) > 0)
									{ 
										/*if($getinvoice_number[0]['state_of_center']=='JAM')
											{
											$invoiceNumber = generate_duplicate_id_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
											if($invoiceNumber)
											{
											$invoiceNumber=$this->config->item('Dup_Id_invoice_no_prefix_jammu').$invoiceNumber;
											}
											}
											else
										{*/
										$invoiceNumber = generate_duplicate_id_invoice_number($getinvoice_number[0]['invoice_id']);
										if($invoiceNumber)
										{
											$invoiceNumber=$this->config->item('Dup_Id_invoice_no_prefix').$invoiceNumber;
										}
										//	}
										$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
										$this->db->where('pay_txn_id',$get_user_regnum[0]['id']);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										$attachpath=genarate_duplicateicard_invoice($getinvoice_number[0]['invoice_id']);
									}
									if($attachpath!='')
									{
										$pay_status=array();
										$regnumber = $get_user_regnum[0]['member_regnumber'];
										$where1 = array('member_number'=> $regnumber);
										$pay_status= $this->master_model->updateRecord('member_idcard_cnt',array('card_cnt'=>'0'),$where1);
										/* User Log Activities : Pooja */
										$uerlog = $this->master_model->getRecords('member_registration',array('regnumber'=>$regnumber,'isactive'=>'1'),'regid');
										$user_info = $this->master_model->getRecords('member_idcard_cnt',array('member_number'=>$regnumber));
										$log_title ="Apply for Duplicate Id card : ".$uerlog[0]['regid'];
										$log_message = serialize($user_info);
										$rId =$uerlog[0]['regid'];
										$regNo = $regnumber;
										storedUserActivity($log_title, $log_message, $rId, $regNo);
										$this->Emailsending->mailsend($info_arr);
									}
								}
							}
						}
					}
					else if($payment_status==0)
					{
						// Handle transaction fail case 
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
						if($get_user_regnum[0]['status']==2)
						{
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
						}
						// Handle transaction fail case 
					}
				}
				else if($responsedata[6] == "iibfdupcer")
				{
					sleep(8);
					$MerchantOrderNo = $responsedata[0]; 
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					switch ($responsedata[2])
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
					if($payment_status==1)
					{// Handle transaction sucess case 
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,id');
						if($get_user_regnum[0]['status']==2)
						{
							if(count($get_user_regnum) > 0)
							{
								$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'isactive'=>'1'),'regnumber,usrpassword,email');
								//if member is DRA member
								if(empty($user_info))
								{
									$user_info = $this->master_model->getRecords('dra_members',array('regnumber'=>$get_user_regnum[0]['member_regnumber']));
								}
							}
							$update_data = array('pay_status' => '1');
							$this->master_model->updateRecord('duplicate_certificate',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,id');
							if($get_payment_status[0]['status']==1)
							{
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'duplicate_cert'));
								$this->sms_template_id = 'MVPWKSwGg';
								if(count($emailerstr) > 0 && (count($get_user_regnum) > 0))
								{
									$final_str = $emailerstr[0]['emailer_text'];
									$info_arr = array('to'=>$user_info[0]['email'],'from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'].' '.$get_payment_status[0]['member_regnumber'],'message'=>$final_str);
									//genertate invoice and email send with invoice attach 8-7-2017					
									//get invoice	
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum[0]['id']));
									//echo $this->db->last_query();exit;
									if(count($getinvoice_number) > 0)
									{ 
										/*if($getinvoice_number[0]['state_of_center']=='JAM')
											{
											$invoiceNumber = generate_duplicate_cert_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
											if($invoiceNumber)
											{
											$invoiceNumber=$this->config->item('Dup_cert_invoice_no_prefix_jammu').$invoiceNumber;
											}
											}
											else
										{*/
										$invoiceNumber = generate_duplicate_cert_invoice_number($getinvoice_number[0]['invoice_id']);
										if($invoiceNumber)
										{
											$invoiceNumber=$this->config->item('Dup_cert_invoice_no_prefix').$invoiceNumber;
										}
										//}
										$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
										$this->db->where('pay_txn_id',$get_user_regnum[0]['id']);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										$attachpath=genarate_duplicatecert_invoice($getinvoice_number[0]['invoice_id']);
									}
									if($attachpath!='')
									{	
										$this->Emailsending->mailsend_attch($info_arr,$attachpath);
									}
								}
							}
						}
					}
					else if($payment_status==0)
					{
						// Handle transaction fail case 
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
						if($get_user_regnum[0]['status']==2)
						{
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
						}
						// Handle transaction fail case 
					}
				}
				else if($responsedata[6] == "IIBF_EXAM_O")
				{
					sleep(2);
					$MerchantOrderNo = $responsedata[0]; 
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					$attachpath=$invoiceNumber=$admitcard_pdf='';
					switch ($responsedata[2])
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
					if($payment_status==1)
					{	
						$exam_period_date='';
						//Handle transaction success case
						$elective_subject_name='';
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
						///check for dual exam applied or not 
						////
						if($get_user_regnum[0]['status']==2)
						{
							///charter bank S2S call
							if($get_user_regnum[0]['exam_code']=='1016')
							{
								$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(
								'receipt_no' => $MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
								$reg_id        = $get_user_regnum_info[0]['ref_id'];
								$applicationNo = $get_user_regnum_info[0]['member_regnumber'];
								$update_data   = array(
								//'member_regnumber' => $applicationNo,
								'transaction_no' => $transaction_no,
								'status' => 1,
								'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
								'auth_code' => '0300',
								'bankcode' => $responsedata[8],
								'paymode' => $responsedata[5],
								'callback' => 'S2S'
								);
								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								/* Transaction Log */
								$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
								$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
								//echo $reg_id; die;
								$reg_data = $this->master_model->getRecords('caiib_jaiib_newexam_registration', array('member_no' => $applicationNo, 'mem_exam_id' => $reg_id),'exam_code');
								$selected_exam_code =$reg_data[0]['exam_code'];
								/* Get User Attempt */
								$attemptQry=$this->db->query("SELECT attempt,fee_flag FROM caiib_jaiib_newexam_eligible WHERE member_no='".$applicationNo."' AND exam_code = '" . $selected_exam_code . "' LIMIT 1"); 
								$attemptArr = $attemptQry->row_array();
								$attempt = $attemptArr['attempt'];
								$fee_flag=$attemptArr['fee_flag'];
								$attempt = $attempt+1;
								/* Update Pay Status and User Attemp Status */
								$blended_data = array('pay_status'=>1, 'attempt'=>$attempt, 'modify_date'=>date('Y-m-d H:i:s'));
								$memberexam_data = array('pay_status'=>1,  'modified_on'=>date('Y-m-d H:i:s'));
								//$regno=$this->session->userdata['memberdata']['regno'];
								$this->master_model->updateRecord('caiib_jaiib_newexam_registration',$blended_data,array('mem_exam_id'=>$reg_id,'member_no'=>$applicationNo));
								$this->master_model->updateRecord('member_exam',$memberexam_data,array('id'=>$reg_id));
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'charterd_email'));
								$this->sms_template_id = 'rP53cIwMR';
								if (!empty($applicationNo)) {
									$user_info = $this->master_model->getRecords('member_registration',array('regnumber'=> $applicationNo,'isactive'=>'1'),'email,mobile');
								}
								if (count($emailerstr) > 0) 
								{
									/* Set Email Content For user */
									$Qry=$this->db->query("SELECT exam_code, qualification FROM caiib_jaiib_newexam_registration WHERE mem_exam_id = '".$reg_id."' LIMIT 1");
									$detailsArr        = $Qry->row_array();
									$exam_code = $detailsArr['exam_code'];
									$exam_name ='Chartered Banker Intitute'; //$detailsArr['qualification'];
									$newstring    = str_replace("#exam_name#","".$exam_name."",$emailerstr[0]['emailer_text']);
									/* Set Email sending options */
									$info_arr          = array(
									'to' => $user_info[0]['email'],
									'from' => $emailerstr[0]['from'],
									'subject' => $emailerstr[0]['subject'].' '.$applicationNo,
									'message' => $newstring
									);
									$getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo,'pay_txn_id' => $get_user_regnum_info[0]['id']));
									$zone_code = ""; 
									$zoneArr = array();
									//$regno = $this->session->userdata['memberdata']['regno'];
									$zoneArr = $this->master_model->getRecords('caiib_jaiib_newexam_registration',array('mem_exam_id'=>$reg_id,'pay_status'=>1),'gstin_no');
									$gstin_no          = $zoneArr[0]['gstin_no'];
									/* Invoice Number Genarate Functinality */
									if (count($getinvoice_number) > 0){
										$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
										if($invoiceNumber)
										{
											$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
										}
										$update_data = array(
										'invoice_no' => $invoiceNumber,
										'transaction_no' => $transaction_no,
										'date_of_invoice' => date('Y-m-d H:i:s'),
										'modified_on' => date('Y-m-d H:i:s')
										);
										$this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
										$this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
										/* Invoice Genarate Function */
										$attachpath = genarate_chartered_exam_invoice($getinvoice_number[0]['invoice_id']);
										$this->Emailsending->mailsend_attch($info_arr,$attachpath);
										/* User Log Activities  */
										$log_title ="Charterd accountant Registration-Invoice Genarate";
										$log_message = serialize($update_data);
										$rId = $reg_id;
										$regNo = $applicationNo;
										storedUserActivity($log_title, $log_message, $rId, $regNo);
									}
									if ($attachpath != '') 
									{ 
										/* Email Send To Clints */
										if (!empty($applicationNo)) {
											$reg_info = $this->master_model->getRecords('caiib_jaiib_newexam_registration',array('member_no'=> $applicationNo,'mem_exam_id' => $reg_id));
										}
										$payment_infoArr=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$reg_info[0]['member_no']),'transaction_no,date,amount');
										if($reg_info[0]['member_no'] == $applicationNo)
										{
											$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'charterd_emailer_client'));
											$this->sms_template_id = 'NA';
											if(count($emailerSelfStr) > 0)
											{
												$designation_name=$undergraduate_name=$graduate_name=$postgraduate_name=$specify_qualification=$institution_name=$pay="";
												$qualification = $reg_info[0]['qualification'];
												$exam_name ='Chartered Banker Intitute'; //$detailsArr['qualification'];
												if($reg_info[0]['pay_status'] == 1){ $pay = "Success";}
												$selfstr1 = str_replace("#regnumber#", "".$reg_info[0]['member_no']."",  $emailerSelfStr[0]['emailer_text']);
												$selfstr2 = str_replace("#exam_name#", "".$exam_name."",  $selfstr1);
												$selfstr8 = str_replace("#name#", "".$reg_info[0]['namesub']." ".$reg_info[0]['firstname']." ".$reg_info[0]['middlename']." ".$reg_info[0]['lastname'],  $selfstr2);
												$selfstr9 = str_replace("#address1#", "".$reg_info[0]['address1']."",  $selfstr8);
												$selfstr10 = str_replace("#address2#", "".$reg_info[0]['address2']."",  $selfstr9);
												$selfstr11 = str_replace("#address3#", "".$reg_info[0]['address3']."",  $selfstr10);
												$selfstr12 = str_replace("#address4#", "".$reg_info[0]['address4']."",  $selfstr11);
												$selfstr13 = str_replace("#district#", "".$reg_info[0]['district']."",  $selfstr12);
												$selfstr14 = str_replace("#city#", "".$reg_info[0]['city']."",  $selfstr13);
												$selfstr15 = str_replace("#state#", "".$reg_info[0]['state']."",  $selfstr14);
												$selfstr16 = str_replace("#pincode#", "".$reg_info[0]['pincode']."",  $selfstr15);
												$selfstr19 = str_replace("#dateofbirth#", "".$reg_info[0]['dateofbirth']."",  $selfstr16);
												$selfstr20 = str_replace("#email#", "".$reg_info[0]['email']."",  $selfstr19);
												$selfstr21 = str_replace("#mobile#", "".$reg_info[0]['mobile']."",  $selfstr20);
												$selfstr26 = str_replace("#qualification#", "".$reg_info[0]['qualification']."",  $selfstr21);
												$selfstr31 = str_replace("#TRANSACTION_NO#", "".$payment_infoArr[0]['transaction_no']."",  $selfstr26);
												$selfstr32 = str_replace("#AMOUNT#", "".$payment_infoArr[0]['amount']."",  $selfstr31);
												$selfstr33 = str_replace("#STATUS#", "Transaction Successful",  $selfstr32);
												$final_selfstr = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_infoArr[0]['date']))."",  $emailerSelfStr[0]['subject']);
												// $s1 = str_replace("#exam_code#", "".$reg_info[0]['exam_code'],$emailerSelfStr[0]['subject']);
												$final_sub = str_replace("#exam_code#", "".$reg_info[0]['exam_code']."",  $final_selfstr);
												/* Get Client Emails Details */
												// $emailsQry = $this->db->query("SELECT emails FROM offline_email_master WHERE  exam_code = '" . $reg_info[0]['exam_code'] . "'AND isdelete = 0 LIMIT 1 ");
												// $emailsArr    = $emailsQry->row_array();
												// $emails  = $emailsArr['emails'];  
												$self_mail_arr = array(
												'to'=>$emails,
												'from'=>$emailerSelfStr[0]['from'],
												'subject'=>$final_sub.' '.$reg_info[0]['member_no'],
												'message'=>$final_selfstr); 
												$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);
											}
										}
										} else { 
										redirect(base_url() . 'caiib_jaiib_reg/caiib_jaiib_reg_acknowledge/');
									}
								}
							}
							else
							{
								######### payment Transaction ############
								$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
								$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
								if($this->db->affected_rows())
								{
									$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
									if($get_payment_status[0]['status']==1)
									{
										if(count($get_user_regnum) > 0)
										{
											$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
										}
										//Query to get exam details	
										$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
										$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
										$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
										$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
										$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code');
										if($exam_info[0]['exam_code']!=101 || $exam_info[0]['exam_code']!=990 || $exam_info[0]['exam_code']!=993 || $exam_info[0]['exam_code']!=1021 || $exam_info[0]['exam_code']!=1022 || $exam_info[0]['exam_code']!=1023 || $exam_info[0]['exam_code']!=1024 || $exam_info[0]['exam_code']!=1025)
										{
											########## Generate Admit card and allocate Seat #############
											$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
											############check capacity is full or not ##########
											//$subject_arr=$this->session->userdata['examinfo']['subject_arr'];
											if(count($exam_admicard_details) > 0)
											{			
												$msg='';
												$sub_flag=1;
												$sub_capacity=1;
												foreach($exam_admicard_details as $row)
												{
													$capacity=check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
													if($capacity==0)
													{
														#########get message if capacity is full##########
														$log_title ="Capacity full id:".$get_user_regnum[0]['member_regnumber'];
														$log_message = serialize($exam_admicard_details);
														$rId = $get_user_regnum[0]['ref_id'];
														$regNo = $get_user_regnum[0]['member_regnumber'];
														storedUserActivity($log_title, $log_message, $rId, $regNo);
														$refund_insert_array=array('receipt_no'=>$MerchantOrderNo,'response'=>$encData);
														$inser_id = $this->master_model->insertRecord('S2S_direcrt_refund',$refund_insert_array);
													  $this->refund_after_capacity_full->make_refund($MerchantOrderNo);
														exit;
														//redirect(base_url().'Home/refund/'.base64_encode($MerchantOrderNo));
													}
												}
											}
											if(count($exam_admicard_details) > 0 && $capacity > 0)
											{	
												$password=random_password();
												foreach($exam_admicard_details as $row)
												{
													$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'exam_date'=>$row['exam_date'],'session_time'=>$row['time']));
													$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
													//echo $this->db->last_query().'<br>';
													$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
													if($seat_number!='')
													{
														$final_seat_number =$seat_number;
														$update_data = array('pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
														$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
													}
													else
													{
														$admit_card_details=$this->master_model->getRecords('admit_card_details',array('admitcard_id'=>$admit_card_details[0]['admitcard_id'],'remark'=>1));
														if(count($admit_card_details) > 0)
														{
															$log_title ="Seat number already allocated id:".$get_user_regnum[0]['member_regnumber'];
															$log_message = serialize($exam_admicard_details);
															$rId = $admit_card_details[0]['admitcard_id'];
															$regNo = $get_user_regnum[0]['member_regnumber'];
															storedUserActivity($log_title, $log_message, $rId, $regNo);
														}
														else
														{
															$log_title ="Fail user seat allocation id:".$get_user_regnum[0]['member_regnumber'];
															$log_message = serialize($exam_admicard_details);
															$rId = $get_user_regnum[0]['member_regnumber'];
															$regNo = $get_user_regnum[0]['member_regnumber'];
															storedUserActivity($log_title, $log_message, $rId, $regNo);
															//redirect(base_url().'Home/refund/'.base64_encode($MerchantOrderNo));
														}
													}
												}
											}
											else
											{
												//redirect(base_url().'Home/refund/'.base64_encode($MerchantOrderNo));
											}
										}
										######update member_exam######
										if($get_payment_status[0]['status']==1){

											$update_data = array('pay_status' => '1');
											$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
											$log_title ="S2S member exam Update:".$get_user_regnum[0]['ref_id'];
											$log_message = '';
											$rId = $get_user_regnum[0]['ref_id'];
											$regNo = $get_user_regnum[0]['ref_id'];
											storedUserActivity($log_title, $log_message, $rId, $regNo);

											##Code added by Pratiba Borse to remove 777 static exm code on 21 march 22
											if($capacity == 0){
												$log_title ="Fail user seat allocation id:".$get_user_regnum[0]['member_regnumber'];
												$log_message = serialize($exam_admicard_details);
												$rId = $get_user_regnum[0]['member_regnumber'];
												$regNo = $get_user_regnum[0]['member_regnumber'];
												storedUserActivity($log_title, $log_message, $rId, $regNo);
											}
										}else{
											$log_title ="S2S member exam Update fail:".$get_user_regnum[0]['ref_id'];
											$log_message = $get_user_regnum[0]['ref_id'];
											$rId = $get_user_regnum[0]['ref_id'];
											$regNo = $get_user_regnum[0]['ref_id'];
											storedUserActivity($log_title, $log_message, $rId, $regNo);
										}
										//Query to get user details
										$this->db->join('state_master','state_master.state_code=member_registration.state');
										$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
										$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');
										if(count($exam_info) <= 0)
										{
											$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']));
										}
										if($exam_info[0]['exam_mode']=='ON')
										{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
										{$mode='Offline';}
										else{$mode='';}
										if($exam_info[0]['examination_date']!='0000-00-00')
										{
											$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
										}
										else if($exam_info[0]['exam_code']!=990)
										{
											//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
											$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
											$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
										}
										//Query to get Medium	
										$this->db->where('exam_code',$exam_info[0]['exam_code']);
										$this->db->where('exam_period',$exam_info[0]['exam_period']);
										$this->db->where('medium_code',$exam_info[0]['exam_medium']);
										$this->db->where('medium_delete','0');
										$medium=$this->master_model->getRecords('medium_master','','medium_description');
										$this->db->where('state_delete','0');
										$states=$this->master_model->getRecords('state_master',array('state_code'=>$exam_info[0]['state_place_of_work']),'state_name');
										//Query to get Payment details	
										$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,id');
										$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
										$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
										//if($this->session->userdata['examinfo']['elected_exam_mode']=='E')
										if($exam_info[0]['place_of_work']!='' && $exam_info[0]['state_place_of_work']!='' && $exam_info[0]['pin_code_place_of_work']!='')
										{
											//get Elective Subeject name for CAIIB Exam	
											if($exam_info[0]['elected_sub_code']!=0 && $exam_info[0]['elected_sub_code']!='')
											{
												$elective_sub_name_arr=$this->master_model->getRecords('subject_master',array('subject_code'=>$exam_info[0]['elected_sub_code'],'subject_delete'=>0),'subject_description');
												if(count($elective_sub_name_arr) > 0)
												{
													$elective_subject_name=$elective_sub_name_arr[0]['subject_description'];
												}	
											}
											$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'member_exam_enrollment_nofee_elective'));
											$this->sms_template_id = 'P6tIFIwGR';
											$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
											$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
											$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
											$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
											$newstring5 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring4);
											$newstring6 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring5);
											$newstring7 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring6);
											$newstring8 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring7);
											$newstring9 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring8);
											$newstring10 = str_replace("#CITY#", "".$result[0]['city']."",$newstring9);
											$newstring11 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring10);
											$newstring12 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring11);
											$newstring13 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring12);
											$newstring14 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring13);
											$newstring15 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring14);
											$newstring16 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring15);
											$newstring17 = str_replace("#ELECTIVE_SUB#", "".$elective_subject_name."",$newstring16);
											$newstring18 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring17);
											$newstring19 = str_replace("#PLACE_OF_WORK#", "".strtoupper($exam_info[0]['place_of_work'])."",$newstring18);
											$newstring20 = str_replace("#STATE_PLACE_OF_WORK#", "".$states[0]['state_name']."",$newstring19);
											$newstring21 = str_replace("#PINCODE_PLACE_OF_WORK#", "".$exam_info[0]['pin_code_place_of_work']."",$newstring20);
											$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
											if(count($elern_msg_string) > 0)
											{
												foreach($elern_msg_string as $row)
												{
													$arr_elern_msg_string[]=$row['exam_code'];
												}
												if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
												{
													$newstring22 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring21);		
												}
												else
												{
													$newstring22 = str_replace("#E-MSG#", '',$newstring21);		
												}
											}
											else
											{
												$newstring22 = str_replace("#E-MSG#", '',$newstring21);
											}
											$final_str = str_replace("#MODE#", "".$mode."",$newstring22);
										}
										else
										{
											if($exam_info[0]['exam_code']==990)
											{
												$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'DISA_emailer'));
												$this->sms_template_id = 'jYZ1dSwGR';
												$final_str = $emailerstr[0]['emailer_text'];
											}else if($exam_info[0]['exam_code']==993)
											{
												$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'Cisi_emailer'));
												$this->sms_template_id = 'gewX5IwGR';
												$final_str = $emailerstr[0]['emailer_text'];
											}
											else
											{
												$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'apply_exam_transaction_success'));
												$this->sms_template_id = 'LSy_cIwGg';
												$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
												$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
												$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
												$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
												$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
												$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
												$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
												$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
												$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
												$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
												$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
												$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
												$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
												$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
												$newstring15 = str_replace("#INSTITUDE#", "".$result[0]['name']."",$newstring14);
												$newstring16 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring15);
												$newstring17 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring16);
												$newstring18 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring17);
												$newstring19 = str_replace("#MODE#", "".$mode."",$newstring18);
												$newstring20 = str_replace("#PLACE_OF_WORK#", "".$result[0]['office']."",$newstring19);
												$newstring21 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring20);
												$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
												if(count($elern_msg_string) > 0 )
												{
													foreach($elern_msg_string as $row)
													{
														$arr_elern_msg_string[]=$row['exam_code'];
													}
													if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
													{
														$newstring22 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring21);		
													}
													else
													{
														$newstring22 = str_replace("#E-MSG#", '',$newstring21);		
													}
												}
												else
												{
													$newstring22 = str_replace("#E-MSG#", '',$newstring21);
												}
												$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring22);
											}
										}
										$info_arr=array('to'=>$result[0]['email'],
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],
										'message'=>$final_str
										);
										//echo $final_str; exit;
										//get invoice	
										$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
										//echo $this->db->last_query();exit;
										########### generate invoice ###########
										if(count($getinvoice_number) > 0)
										{
											/*if($getinvoice_number[0]['state_of_center']=='JAM')
												{
												$invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
												if($invoiceNumber)
												{
												$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
												}
												}
												else
											{*/
											if($exam_info[0]['exam_code']==990)
											{
												$invoiceNumber =generate_DISA_invoice_number($getinvoice_number[0]['invoice_id']);
												if($invoiceNumber)
												{
													$invoiceNumber=$this->config->item('DISA_invoice_no_prefix').$invoiceNumber;
												}
											}
											else if($exam_info[0]['exam_code']==993)
											{
												$invoiceNumber =generate_CISI_invoice_number($getinvoice_number[0]['invoice_id']);
												if($invoiceNumber)
												{
													$invoiceNumber=$this->config->item('Cisi_invoice_no_prefix').$invoiceNumber;
												}
											}
											else if($exam_info[0]['exam_code']==1018)
											{
												## Code added for GARP Module on 8 Jul 2021	
												$invoiceNumber = generate_GARP_invoice_number($getinvoice_number[0]['invoice_id']);
												if($invoiceNumber)
												{
													$invoiceNumber=$this->config->item('garp_invoice_no_prefix').$invoiceNumber;
												}
											}
											else if($exam_info[0]['exam_code']==$this->config->item('examCodeCFP'))
											{
												## Code added for GARP Module on 8 Jul 2021	
												$invoiceNumber = generate_CFP_invoice_number($getinvoice_number[0]['invoice_id']);
												if($invoiceNumber)
												{
													$invoiceNumber=$this->config->item('cfp_invoice_no_prefix').$invoiceNumber;
												}
											}
											else
											{
												$invoiceNumber = '';
												if($get_payment_status[0]['status']==1){
													$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
													$log_title ="S2S exam invoice number generate:".$getinvoice_number[0]['invoice_id'];
													$log_message = '';
													$rId = $MerchantOrderNo;
													$regNo = $MerchantOrderNo;
													storedUserActivity($log_title, $log_message, $rId, $regNo);
													}else{
													$log_title ="S2S exam invoice number generate fail:".$MerchantOrderNo;
													$log_message = $MerchantOrderNo;
													$rId = $MerchantOrderNo;
													$regNo = $MerchantOrderNo;
													storedUserActivity($log_title, $log_message, $rId, $regNo);
												}
												if($invoiceNumber)
												{
													$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
												}
											}
											//}
											## invoice no condition added by chaitali on 2021-10-15
											if($get_payment_status[0]['status']==1 && $invoiceNumber != ''){
												$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
												$this->db->where('pay_txn_id',$payment_info[0]['id']);
												$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
												$log_title ="S2S exam invoice Update:".$MerchantOrderNo;
												$log_message = '';
												$rId = $MerchantOrderNo;
												$regNo = $MerchantOrderNo;
												storedUserActivity($log_title, $log_message, $rId, $regNo);
												}else{
												$log_title ="S2S exam invoice Update fail:".$MerchantOrderNo;
												$log_message = $MerchantOrderNo;
												$rId = $MerchantOrderNo;
												$regNo = $MerchantOrderNo;
												storedUserActivity($log_title, $log_message, $rId, $regNo);
											}
											if($exam_info[0]['exam_code']==990)
											{
												$attachpath=genarate_DISA_invoice($getinvoice_number[0]['invoice_id']);	
											}else if($exam_info[0]['exam_code']==993)
											{
												$attachpath=genarate_CISI_invoice($getinvoice_number[0]['invoice_id']);
											}
											else if($exam_info[0]['exam_code']==1018)
											{
												$attachpath=genarate_garp_exam_invoice($getinvoice_number[0]['invoice_id']);
												
												//garp pay_status update code added by Pooja mane on 2023-04-19
												$update_data = array('pay_status' => '1','modified_on'=>date('Y-m-d H:i:s'));
												$this->db->where('mem_exam_id',$exam_info[0]['id']);
												$this->master_model->updateRecord('garp_exam_registration',$update_data);
												$log_title ="S2S garp pay_status Update:".$MerchantOrderNo;
												$log_message = '';
												$rId = $MerchantOrderNo;
												$regNo = $MerchantOrderNo;
												storedUserActivity($log_title, $log_message, $rId, $regNo);
												//garp pay_status update code end by Pooja mane on 2023-04-19
											}
											else if($exam_info[0]['exam_code']==$this->config->item('examCodeCFP'))
											{
												$attachpath=genarate_cfp_exam_invoice($getinvoice_number[0]['invoice_id']);
												
												//garp pay_status update code added by Pooja mane on 2023-04-19
												$update_data = array('pay_status' => '1','modified_on'=>date('Y-m-d H:i:s'));
												$this->db->where('mem_exam_id',$exam_info[0]['id']);
												$this->master_model->updateRecord('cfp_exam_registration',$update_data);
												$log_title ="S2S cfp pay_status Update:".$MerchantOrderNo;
												$log_message = '';
												$rId = $MerchantOrderNo;
												$regNo = $MerchantOrderNo;
												storedUserActivity($log_title, $log_message, $rId, $regNo);
												//garp pay_status update code end by Pooja mane on 2023-04-19
											}
											else if($exam_info[0]['exam_code']==1021 || $exam_info[0]['exam_code']==1022 || $exam_info[0]['exam_code']==1023 || $exam_info[0]['exam_code']==1024 || $exam_info[0]['exam_code']==1025)
											{
												$attachpath=genarate_PB_invoice($getinvoice_number[0]['invoice_id']);
											}
											else
											{
												$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
											}
											if($exam_info[0]['exam_code']!=101 || $exam_info[0]['exam_code']!=990 || $exam_info[0]['exam_code']!=993)
											{
												##############Get Admit card#############
												$admitcard_pdf=genarate_admitcard($get_user_regnum[0]['member_regnumber'],$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
											}
										}	
										if($attachpath!='')
										{		
											$files=array($attachpath,$admitcard_pdf);
											if($exam_info[0]['exam_code']==990 || $exam_info[0]['exam_code']==993)
											{
												$sms_final_str = $emailerstr[0]['sms_text'];
											}
											else
											{
												
												$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
												$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
												$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
												$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
												$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
											}
											if($this->Emailsending->mailsend_attch($info_arr,$files)){
												$insert_data1['description']  = 'S2S Exam registration mail send.';
												$insert_data1['to_email']  = @$result[0]['email'];
												$insert_data1['email_data']  = json_encode($final_str);
												$insert_data1['attachment']  = json_encode($attachpath.'--'.$admitcard_pdf);
												$this->master_model->insertRecord('log_payment_callback_s2s', $insert_data1);
												}else{
												$insert_data1['description']  = 'S2S Exam registration mail not send check if.';
												$insert_data1['to_email']  = @$result[0]['email'];
												$insert_data1['email_data']  = json_encode($final_str);
												$insert_data1['attachment']  = json_encode($attachpath.'--'.$admitcard_pdf);
												$this->master_model->insertRecord('log_payment_callback_s2s', $insert_data1);
											}
											// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
											//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
											$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

											//$this->Emailsending->mailsend($info_arr);
											}else{
											$insert_data1['description']  = 'S2S Exam registration mail not send.';
											$insert_data1['to_email']  = @$result[0]['email'];
											$insert_data1['email_data']  = json_encode($final_str);
											$this->master_model->insertRecord('log_payment_callback_s2s', $insert_data1);
										}
									}
								}
								else
								{
									$log_title ="S2S Update fail:".$get_user_regnum[0]['member_regnumber'];
									$log_message = serialize($update_data);
									$rId = $MerchantOrderNo;
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);	
								}
							}
						}
					}
					else if($payment_status==0)
					{
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code');
						if($get_user_regnum[0]['status']==2)
						{
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399','bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'S2S');
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
							//Query to get Payment details	
							$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
							if($get_user_regnum[0]['exam_code']!='990')
							{
								//Query to get user details
								$this->db->join('state_master','state_master.state_code=member_registration.state');
								$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
								$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');
								//Query to get exam details	
								$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
								$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
								$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
								$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
								$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
								//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
								$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
								$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
								$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
								$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
								$this->sms_template_id = 'Jw6bOIQGg';
								$newstring1 = str_replace("#application_num#", "".$get_user_regnum[0]['member_regnumber']."",  $emailerstr[0]['emailer_text']);
								$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
								$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
								$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
								$info_arr=array(	'to'=>$result[0]['email'],
								'from'=>$emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],
								'message'=>$final_str
								);
								//send sms to Ordinary Member
								$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
								$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
								$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
								//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
								//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
								$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

								$this->Emailsending->mailsend($info_arr);
							}
						}
					}				
				}
				else if($responsedata[6] == "IPPB_EXAM_REG")
				{
					sleep(2);
					$MerchantOrderNo = $responsedata[0]; 
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					$attachpath=$invoiceNumber=$admitcard_pdf='';
					switch ($responsedata[2])
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
					if($payment_status==1)
					{	
						$exam_period_date='';
						//Handle transaction success case
						$elective_subject_name='';
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
						///check for dual exam applied or not 
						////
						if($get_user_regnum[0]['status']==2) 
						{
							######### payment Transaction ############

							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
							
							$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));

							if($this->db->affected_rows())
							{
								$exam_code=$get_user_regnum[0]['exam_code'];
								$reg_id=$get_user_regnum[0]['member_regnumber'];

								############check capacity is full or not ##########
								$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
								//$subject_arr=$this->session->userdata['enduserinfo']['subject_arr'];
								if(count($exam_admicard_details) > 0)
								{		
									$msg='';
									$sub_flag=1;
									$sub_capacity=1;
									foreach($exam_admicard_details as $row)
									{
										$capacity=csc_check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
										if($capacity==0)
										{
											#########get message if capacity is full##########
											/*Add code trans_start & trans_complete :   */
											/* $update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
											$this->db->order_by('id','DESC');
											$this->db->limit(1);
											$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo)); */
											
											$log_title ="IPPB nonreg Capacity full id:".$get_user_regnum[0]['member_regnumber'];
											$log_message = serialize($exam_admicard_details);
											$rId = $get_user_regnum[0]['ref_id'];
											$regNo = $get_user_regnum[0]['member_regnumber'];
											storedUserActivity($log_title, $log_message, $rId, $regNo);
											$refund_insert_array=array('receipt_no'=>$MerchantOrderNo,'response'=>$encData);
											$inser_id = $this->master_model->insertRecord('S2S_direcrt_refund',$refund_insert_array);
											exit;
											//redirect(base_url().'Ippbapplyexam/refund/'.base64_encode($MerchantOrderNo));
										}
									}
								}
							
								$this->db->or_where('regnumber',$reg_id);
								$this->db->or_where('regid',$reg_id);
								$user_info=$this->master_model->getRecords('member_registration',array());
								if($user_info[0]['regnumber'] !== ''){
									$applicationNo = $user_info[0]['regnumber'];
								}else{
									$applicationNo = generate_NM_memreg($reg_id);
								}
								######### payment Transaction ############
								$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
								$this->db->order_by('id','DESC');
								$this->db->limit(1);	
								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								
								$payment_query_update=$this->db->last_query();
								
								$log_title ="IPPBNonreg Paymnet update :".$payment_query_update;
								$log_message = serialize($update_data);
								$rId = $get_user_regnum[0]['ref_id'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								######### Exam Invoice Transaction ############
								$update_data = array('transaction_no'=>$transaction_no);
								$this->db->where('receipt_no',$MerchantOrderNo);
								$this->db->order_by('invoice_id','DESC');
								$this->db->limit(1);	
								$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
								########## Update Member Registration#############

								
								// echo 'before update mem_reg'.$reg_id; 
								$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
								$this->db->order_by('regid','DESC');
								$this->db->limit(1);
								$this->db->or_where('regnumber',$reg_id);
								$this->db->or_where('regid',$reg_id);
								$this->master_model->updateRecord('member_registration',$update_mem_data,array());

								
								##########Update Member Exam#############
								$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
								$this->db->order_by('id','DESC');
								$this->db->limit(1);
								$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
								
								
								########## Generate Invoice #############
								$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNo),'transaction_no,date,amount,id');
								//get invoice	
								$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
								$invoice_get_query= $this->db->last_query();
								$log_title ="IPPB NONreg invoice get query :".$invoice_get_query;
								$log_message = serialize($getinvoice_number);
								$rId = $get_user_regnum[0]['ref_id'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);	
								if(count($getinvoice_number) > 0)
								{
										$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
										if($invoiceNumber)
										{
											$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
										}
									$update_data = array('invoice_no' => $invoiceNumber,'member_no'=>$applicationNo,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
									/*Add code trans_start & trans_complete :   */
									$this->db->where('pay_txn_id',$payment_info[0]['id']);
									$this->db->order_by('invoice_id','DESC');
									$this->db->limit(1);
									$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
									
									
									$invoice_update_query= $this->db->last_query();
									$log_title ="IPPB NONreg invoice update query :".$invoice_update_query;
									$log_message = serialize($update_data);
									$rId = $get_user_regnum[0]['ref_id'];
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);	
									
									$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
									$log_title ="IPPB NONreg invoice Img path";
									$log_message = serialize($attachpath);
									$rId = $get_user_regnum[0]['ref_id'];
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);	
								}
								//Query to get exam details	
								$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period','left');
								$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
								$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
								$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
								$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
								$exam_info_query = $this->db->last_query();
								
								$log_title ="IPPBNonreg admit_card_details :".$exam_info_query;
								$log_message = serialize($exam_info);
								$rId = $get_user_regnum[0]['ref_id'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								
								########## Generate Admit card and allocate Seat #############
								if(count($exam_admicard_details) > 0)
								{
									$password=random_password();
									foreach($exam_admicard_details as $row)
									{
										$query='(exam_date = "0000-00-00" OR exam_date = "")';
										$this->db->where($query);
										$this->db->where('session_time=','');
										$this->db->where('venue_flag','P');
										$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'center_code'=>$row['center_code']));
										
										$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
										
										//echo $this->db->last_query().'<br>';
										$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$admit_card_details[0]['exam_date'],$admit_card_details[0]['time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
							
										if($seat_number!='')
										{
											$final_seat_number = $seat_number;
											$update_data = array('mem_mem_no'=>$applicationNo,'pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
											$this->db->order_by('mem_exam_id','DESC');
											$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
										}
										else
										{
											$admit_card_details=$this->master_model->getRecords('admit_card_details',array('admitcard_id'=>$admit_card_details[0]['admitcard_id'],'remark'=>1));
											if(count($admit_card_details) > 0)
											{
												$log_title ="IPPB nonreg Seat number already allocated id:".$get_user_regnum[0]['member_regnumber'];
												$log_message = serialize($exam_admicard_details);
												$rId = $get_user_regnum[0]['ref_id'];
												$regNo = $get_user_regnum[0]['member_regnumber'];
												storedUserActivity($log_title, $log_message, $rId, $regNo);
											}
											else
											{
												$log_title ="IPPB nonreg Fail user seat allocation id:".$applicationNo;
												$log_message = '';
												$rId = $get_user_regnum[0]['ref_id'];
												$regNo = $get_user_regnum[0]['member_regnumber'];
												storedUserActivity($log_title, $log_message, $rId, $regNo);
												//redirect(base_url().'Nonreg/refund/'.base64_encode($MerchantOrderNo));
											}
										}
									}
								}	
								else
								{
									//redirect(base_url().'CSCNonreg/refund/'.base64_encode($MerchantOrderNo));
								}
								
								##############Get Admit card#############
								$admitcard_pdf=genarate_admitcard($applicationNo,$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
								$log_title ="IPPB nonreg admit cart image path:";
								$log_message = serialize($admitcard_pdf);
								$rId = $get_user_regnum[0]['ref_id'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								
							
							
								if($exam_info[0]['exam_mode']=='ON')
								{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
								{$mode='Offline';}
								else{$mode='';}
								//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
								$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
								$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
								//Query to get Medium	
								$this->db->where('exam_code',$exam_code);
								$this->db->where('exam_period',$exam_info[0]['exam_period']);
								$this->db->where('medium_code',$exam_info[0]['exam_medium']);
								$this->db->where('medium_delete','0');
								$medium=$this->master_model->getRecords('medium_master','','medium_description');
								//Query to get Payment details	
								
								//Query to get user details
								$this->db->join('state_master','state_master.state_code=member_registration.state');
								$this->db->or_where('regnumber',$reg_id);
								$this->db->or_where('regid',$reg_id);
								$result=$this->master_model->getRecords('member_registration',array(),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,usrpassword');	
								
								########get Old image Name############
								$log_title ="IPPB nonreg OLD Image :".$reg_id;
								$log_message = serialize($result);
								$rId = $get_user_regnum[0]['ref_id'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);	
								
								$upd_files = array();
								$photo_file = 'p_'.$applicationNo.'.jpg';
								$sign_file = 's_'.$applicationNo.'.jpg';
								$proof_file = 'pr_'.$applicationNo.'.jpg';
								
								/* if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
								{	$upd_files['scannedphoto'] = $photo_file;	} */
								$chk_photo = update_image_name("./uploads/photograph/", $result[0]['scannedphoto'], $photo_file); //update_image_name_helper.php
								if($chk_photo != "") { $upd_files['scannedphoto'] = $chk_photo; }
								
								/* if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
								{	$upd_files['scannedsignaturephoto'] = $sign_file;	} */
								$chk_sign = update_image_name("./uploads/scansignature/", $result[0]['scannedsignaturephoto'], $sign_file); //update_image_name_helper.php
								if($chk_sign != "") { $upd_files['scannedsignaturephoto'] = $chk_sign; }
								
								/* if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
								{	$upd_files['idproofphoto'] = $proof_file;	} */
								$chk_proof = update_image_name("./uploads/idproof/", $result[0]['idproofphoto'], $proof_file); //update_image_name_helper.php
								if($chk_proof != "") { $upd_files['idproofphoto'] = $chk_proof; }
								
								if(count($upd_files)>0)
								{
									$this->db->or_where('regnumber',$reg_id);
									$this->db->or_where('regid',$reg_id);
									$this->master_model->updateRecord('member_registration',$upd_files,array());
									$log_title ="IPPB nonreg PICS Update :".$reg_id;
									$log_message = serialize($this->db->last_query());
									$rId = $get_user_regnum[0]['ref_id'];
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);	
								}
								else
								{
										$upd_files['scannedphoto'] = $photo_file;
										$upd_files['scannedsignaturephoto'] = $sign_file;	
										$upd_files['idproofphoto'] = $proof_file;
										$this->db->or_where('regnumber',$reg_id);
										$this->db->or_where('regid',$reg_id);
										$this->master_model->updateRecord('member_registration',$upd_files,array());
										$log_title ="IPPB nonreg MANUAL PICS Update :".$reg_id;
										$log_message = serialize($upd_files);
										$rId = $get_user_regnum[0]['ref_id'];
										$regNo = $get_user_regnum[0]['member_regnumber'];
										storedUserActivity($log_title, $log_message, $rId, $regNo);	
								}
						
								$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
								$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);

								include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
								$key = $this->config->item('pass_key');
								$aes = new CryptAES();
								$aes->set_key(base64_decode($key));
								$aes->require_pkcs5();
								$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
								
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
								$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
								$newstring2 = str_replace("#REG_NUM#", "".$applicationNo."",$newstring1);
								$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
								$newstring4 = str_replace("#EXAM_DATE#", "-",$newstring3);
								$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
								$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
								$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
								$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
								$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
								$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
								$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
								$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
								$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
								$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
								$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
								$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
								$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
								$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
								$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
								$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring19);
								$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
								if(count($elern_msg_string) > 0)
								{
									foreach($elern_msg_string as $row)
									{
										$arr_elern_msg_string[]=$row['exam_code'];
									}
									if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
									{
										$newstring21 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring20);		
									}
									else
									{
										$newstring21 = str_replace("#E-MSG#", '',$newstring20);		
									}
								}
								else
								{
									$newstring21 = str_replace("#E-MSG#", '',$newstring20);
								}
								$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
								$info_arr=array('to'=>$result[0]['email'],
														'from'=>$emailerstr[0]['from'],
														'subject'=>$emailerstr[0]['subject'],
														'message'=>$final_str
													);
								if($attachpath!='')
								{		
									//send sms	
									$files=array($attachpath,$admitcard_pdf);				
									$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
									$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
									$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
									$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
									$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);						
									$mail_flag=$this->Emailsending->mailsend_attch($info_arr,$files);
									if($mail_flag)
									{
										$log_title ="Mail IPPB nonreg Flag success :".$mail_flag;
										$log_message = serialize($info_arr);
										$rId = $get_user_regnum[0]['ref_id'];
										$regNo = $get_user_regnum[0]['member_regnumber'];
										storedUserActivity($log_title, $log_message, $rId, $regNo);	
									}
									else
									{
										$log_title ="Mail IPPB nonreg Flag fail :".$mail_flag;
										$log_message = serialize($info_arr);
										$rId = $get_user_regnum[0]['ref_id'];
										$regNo = $get_user_regnum[0]['member_regnumber'];
										storedUserActivity($log_title, $log_message, $rId, $regNo);	
									}
								}else{
									
										$log_title ="Attachement IPPB nonreg not found email mail";
										$log_message = serialize($attachpath);
										$rId = $get_user_regnum[0]['ref_id'];
										$regNo = $get_user_regnum[0]['member_regnumber'];
										storedUserActivity($log_title, $log_message, $rId, $regNo);	
								}//Manage Log
							
								
						
							}
							else
							{
								$log_title ="IPPB nonreg S2S Update fail:".$get_user_regnum[0]['member_regnumber'];
								$log_message = serialize($update_data);
								$rId = $MerchantOrderNo;
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);	
							}
							$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
							$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
							//redirect(base_url().'Ippbapplyexam/acknowledge/'.base64_encode($MerchantOrderNo));
						
								}
					}
					else if($payment_status==0)
					{
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code');
						if($get_user_regnum[0]['status']==2)
						{
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' =>'0399','bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'S2S');

								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

							

								//Query to get Payment details	

								$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id,member_regnumber');

								$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');

								

								$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');

								$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');

								$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');

								$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');

								$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.regnumber'=>$payment_info[0]['member_regnumber']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');

							

								//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');

								$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);

								$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);

								

								$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];

								$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);

								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));

								$newstring1 = str_replace("#application_num#", "",  $emailerstr[0]['emailer_text']);

								$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);

								$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);

								$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);

								

								$info_arr=array(	'to'=>$result[0]['email'],

															'from'=>$emailerstr[0]['from'],

															'subject'=>$emailerstr[0]['subject'],

															'message'=>$final_str

														);

								

								// send SMS

								$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
								$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);

								$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);

								// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	

								//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,'Jw6bOIQGg');	
								$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

								$this->Emailsending->mailsend($info_arr);

							//Manage Log

							$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;

							$this->log_model->logtransaction("sbiepay", $pg_response,$responsedata[2]);		

							
						}
					}				
				}
				else if($responsedata[6] == "IIBF_EXAM_NM")
				{
					sleep(8);
					$MerchantOrderNo = $responsedata[0]; 
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					switch ($responsedata[2])
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
					if($payment_status==1)
					{
						// Handle transaction success case 
						$exam_period_date=$attachpath=$invoiceNumber='';
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
						///check for duplicate entry
						///check duplcate entry end
						if($get_user_regnum[0]['status']==2)
						{
							######### payment Transaction ############
							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
							$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
							if($this->db->affected_rows())
							{
								$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
								if($get_payment_status[0]['status']==1)
								{
									if(count($get_user_regnum) > 0)
									{
										$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
									}
									//Query to get user details
									$this->db->join('state_master','state_master.state_code=member_registration.state');
									//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
									$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword');
									//Query to get exam details	
									$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
									$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
									$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.examination_date');
									if(count($exam_info) <= 0)
									{
										$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']));
									}
									########## Generate Admit card and allocate Seat #############
									if($exam_info[0]['exam_code']!=101 || $exam_info[0]['exam_code']!=990 || $exam_info[0]['exam_code']!=993)
									{
										$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
										if(count($exam_admicard_details) > 0)
										{
											############check capacity is full or not ##########
											//$subject_arr=$this->session->userdata['examinfo']['subject_arr'];
											if(count($exam_admicard_details) > 0)
											{		
												$msg='';
												$sub_flag=1;
												$sub_capacity=1;
												foreach($exam_admicard_details as $row)
												{
													$capacity=check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
													if($capacity==0)
													{
														#########get message if capacity is full##########
														$log_title ="Capacity full id:".$get_user_regnum[0]['member_regnumber'];
														$log_message = serialize($exam_admicard_details);
														$rId = $get_user_regnum[0]['ref_id'];
														$regNo = $get_user_regnum[0]['member_regnumber'];
														storedUserActivity($log_title, $log_message, $rId, $regNo);
														$refund_insert_array=array('receipt_no'=>$MerchantOrderNo,'response'=>$encData);
														$inser_id = $this->master_model->insertRecord('S2S_direcrt_refund',$refund_insert_array);
														exit;
														//redirect(base_url().'NonMember/refund/'.base64_encode($MerchantOrderNo));
													}
												}
											}
											$password=random_password();
											foreach($exam_admicard_details as $row)
											{
												$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'exam_date'=>$row['exam_date'],'session_time'=>$row['time']));
												$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
												//echo $this->db->last_query().'<br>';
												$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
												if($seat_number!='')
												{
													$final_seat_number = $seat_number;
													$update_data = array('pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
													$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
												}
											}
										}		
										else
										{
											//redirect(base_url().'NonMember/refund/'.base64_encode($MerchantOrderNo));
										}
									}
									######update member_exam######	
									$update_data = array('pay_status' => '1');
									$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
									if($exam_info[0]['exam_mode']=='ON')
									{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
									{$mode='Offline';}
									else{$mode='';}
									if($exam_info[0]['examination_date']!='0000-00-00')
									{
										$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
									}
									else if($exam_info[0]['exam_code']!=990)
									{
										//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
										$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
										$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
									}
									//Query to get Medium	
									$this->db->where('exam_code',$exam_info[0]['exam_code']);
									$this->db->where('exam_period',$exam_info[0]['exam_period']);
									$this->db->where('medium_code',$exam_info[0]['exam_medium']);
									$this->db->where('medium_delete','0');
									$medium=$this->master_model->getRecords('medium_master','','medium_description');
									//Query to get Payment details	
									$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,id');
									$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
									$key = $this->config->item('pass_key');
									$aes = new CryptAES();
									$aes->set_key(base64_decode($key));
									$aes->require_pkcs5();
									$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
									if($exam_info[0]['exam_code']==990)
									{
										$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'DISA_emailer'));
										$this->sms_template_id = 'S8OmhSQGg';
										$final_str = $emailerstr[0]['emailer_text'];
									}
									else if($exam_info[0]['exam_code']==993)
									{
										$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'Cisi_emailer'));
										$this->sms_template_id = 'gewX5IwGR';
										$final_str = $emailerstr[0]['emailer_text'];
									}
									else
									{
										$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
										$this->sms_template_id = 'P6tIFIwGR';
										$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
										$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
										$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
										$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
										$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
										$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
										$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
										$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
										$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
										$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
										$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
										$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
										$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
										$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
										$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
										$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
										$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
										$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
										$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
										$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring19);
										$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
										if(count($elern_msg_string) > 0)
										{
											foreach($elern_msg_string as $row)
											{
												$arr_elern_msg_string[]=$row['exam_code'];
											}
											if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
											{
												$newstring21 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring20);		
											}
											else
											{
												$newstring21 = str_replace("#E-MSG#", '',$newstring20);		
											}
										}
										else
										{
											$newstring21 = str_replace("#E-MSG#", '',$newstring20);
										}
										$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
									}
									$info_arr=array('to'=>$result[0]['email'],
									'from'=>$emailerstr[0]['from'],
									'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],
									'message'=>$final_str
									);
									//get invoice	
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
									//echo $this->db->last_query();exit;
									if(count($getinvoice_number) > 0)
									{
										/*if($getinvoice_number[0]['state_of_center']=='JAM')
											{
											$invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
											if($invoiceNumber)
											{
											$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
											}
											}
											else
										{*/
										if($exam_info[0]['exam_code']==990)
										{
											$invoiceNumber =generate_DISA_invoice_number($getinvoice_number[0]['invoice_id']);
											if($invoiceNumber)
											{
												$invoiceNumber=$this->config->item('DISA_invoice_no_prefix').$invoiceNumber;
											}
										}
										else if($exam_info[0]['exam_code']==993)
										{
											$invoiceNumber =generate_CISI_invoice_number($getinvoice_number[0]['invoice_id']);
											if($invoiceNumber)
											{
												$invoiceNumber=$this->config->item('Cisi_invoice_no_prefix').$invoiceNumber;
											}
										}
										else
										{
											$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
											if($invoiceNumber)
											{
												$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
											}
										}
										//}
										$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'));
										$this->db->where('pay_txn_id',$payment_info[0]['id']);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										if($exam_info[0]['exam_code']==990)
										{
											$attachpath=genarate_DISA_invoice($getinvoice_number[0]['invoice_id']);
										}
										else if($exam_info[0]['exam_code']==993)
										{
											$attachpath=genarate_CISI_invoice($getinvoice_number[0]['invoice_id']);
										}
										else
										{
											$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
										}
										if($exam_info[0]['exam_code']!=101 || $exam_info[0]['exam_code']!=990 || $exam_info[0]['exam_code']!=993)
										{
											##############Get Admit card#############
											$admitcard_pdf=genarate_admitcard($get_user_regnum[0]['member_regnumber'],$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
										}
									}		
									if($attachpath!='')
									{	
										$files=array($attachpath,$admitcard_pdf);	
										if($exam_info[0]['exam_code']==990 || $exam_info[0]['exam_code']==993)
										{
											$sms_final_str = $emailerstr[0]['sms_text'];
										}
										else
										{
											$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
											$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
											$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
											$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
											$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
										}
										// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
										//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
										$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

										$this->Emailsending->mailsend_attch($info_arr,$files);
										//$this->Emailsending->mailsend($info_arr);
									}
								}
							}
							else
							{
								$log_title ="S2S Update fail:".$get_user_regnum[0]['member_regnumber'];
								$log_message = serialize($update_data);
								$rId = $MerchantOrderNo;
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);	
							}
						}
					}
					else if($payment_status==0)
					{
						// Handle transaction  fail case
						// Handle transaction success case 
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code');
						if($get_user_regnum[0]['status']==2)
						{
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399','bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'S2S');
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							if($get_user_regnum[0]['exam_code']!='990')
							{
								$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
								//Query to get Payment details	
								$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
								//Query to get exam details	
								$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
								$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
								$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
								$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
								$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
								//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
								$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
								$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
								$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
								$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
								$this->sms_template_id = 'Jw6bOIQGg';
								$newstring1 = str_replace("#application_num#", "".$get_user_regnum[0]['member_regnumber']."",  $emailerstr[0]['emailer_text']);
								$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
								$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
								$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
								$info_arr=array(	'to'=>$result[0]['email'],
								'from'=>$emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],
								'message'=>$final_str
								);
								$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
								$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
								$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
								//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
								//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
								$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

								$this->Emailsending->mailsend($info_arr);
							}
						}
					}
				}
				else if($responsedata[6] == "IIBF_EXAM_REG")
				{
					sleep(8);
					$MerchantOrderNo = $responsedata[0]; 
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					$attachpath=$invoiceNumber='';
					switch ($responsedata[2])
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
					if($payment_status==1)
					{
						// Handle transaction success case 
						$exam_period_date='';
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
						if($get_user_regnum[0]['status']==2)
						{
							######### payment Transaction ############
							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
							$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
							if($this->db->affected_rows())
							{
								$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
								if($get_payment_status[0]['status']==1)
								{
									$exam_code=$get_user_regnum[0]['exam_code'];
									$reg_id=$get_user_regnum[0]['member_regnumber'];
									########## Generate Admit card and allocate Seat #############
									if($exam_code!=101)
									{
										$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
										//$subject_arr=$this->session->userdata['enduserinfo']['subject_arr'];
										if(count($exam_admicard_details) > 0)
										{		
											$msg='';
											$sub_flag=1;
											$sub_capacity=1;
											foreach($exam_admicard_details as $row)
											{
												$capacity=check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
												if($capacity==0)
												{
													#########get message if capacity is full##########
													#Add code trans_start & trans_complete : pooja  #
													$this->db->trans_start(); 
													$update_data = array('transaction_no' => $transaction_no,'status' => 1,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
													$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
													$this->db->trans_complete();
													//redirect(base_url().'Nonreg/refund/'.base64_encode($MerchantOrderNo));
												}
											}
										}
									}
									//$applicationNo = generate_nm_reg_num();
									$applicationNo = generate_NM_memreg($reg_id);
									######### payment Transaction ############
									$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
									######### update application number to Registration table#########
									$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
									$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
									######### update application number to member exam#########
									$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
									$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
									//Query to get exam details	
									$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
									$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
									$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.examination_date');
									########## Generate Admit card and allocate Seat #############
									if($exam_code!='101')
									{
										if(count($exam_admicard_details) > 0)
										{
											$password=random_password();
											foreach($exam_admicard_details as $row)
											{
												$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'exam_date'=>$row['exam_date'],'session_time'=>$row['time']));
												$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
												//echo $this->db->last_query().'<br>';
												$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
												if($seat_number!='')
												{
													$final_seat_number = $seat_number;
													$update_data = array('mem_mem_no'=>$applicationNo,'pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
													$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
												}
												else
												{
													$log_title ="Fail user seat allocation id:".$applicationNo;
													$log_message = serialize($exam_admicard_details);
													$rId = $applicationNo;
													$regNo = $applicationNo;
													storedUserActivity($log_title, $log_message, $rId, $regNo);
												}
											}
											##############Get Admit card#############
											$admitcard_pdf=genarate_admitcard($applicationNo,$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
										}	
										else
										{
											//redirect(base_url().'Nonreg/refund/'.base64_encode($MerchantOrderNo));
										}
									}
									if($exam_info[0]['exam_mode']=='ON')
									{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
									{$mode='Offline';}
									else{$mode='';}
									if($exam_info[0]['examination_date']!='0000-00-00')
									{
										$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
									}
									else
									{
										//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
										$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
										$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
									}
									//Query to get Medium	
									$this->db->where('exam_code',$exam_code);
									$this->db->where('exam_period',$exam_info[0]['exam_period']);
									$this->db->where('medium_code',$exam_info[0]['exam_medium']);
									$this->db->where('medium_delete','0');
									$medium=$this->master_model->getRecords('medium_master','','medium_description');
									//Query to get Payment details	
									$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNo),'transaction_no,date,amount,id');
									//Query to get user details
									$this->db->join('state_master','state_master.state_code=member_registration.state');
									//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
									$result=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,usrpassword');	
									########get Old image Name############
									$log_title ="Non member OLD Image :".$reg_id;
									$log_message = serialize($result);
									$rId = $reg_id;
									$regNo = $reg_id;
									storedUserActivity($log_title, $log_message, $rId, $regNo);
									$upd_files = array();
									$photo_file = 'p_'.$applicationNo.'.jpg';
									$sign_file = 's_'.$applicationNo.'.jpg';
									$proof_file = 'pr_'.$applicationNo.'.jpg';
									if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
									{	$upd_files['scannedphoto'] = $photo_file;	}
									if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
									{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
									if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
									{	$upd_files['idproofphoto'] = $proof_file;	}
									if(count($upd_files)>0)
									{
										$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
										$log_title ="Non member PICS Update S2S :".$reg_id;
										$log_message = serialize($upd_files);
										$rId = $reg_id;
										$regNo = $reg_id;
										storedUserActivity($log_title, $log_message, $rId, $regNo);	
									}
									else
									{
										$upd_files['scannedphoto'] = $photo_file;
										$upd_files['scannedsignaturephoto'] = $sign_file;	
										$upd_files['idproofphoto'] = $proof_file;
										$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
										$log_title ="Non member PICS MANUAL PICS Update S2S :".$reg_id;
										$log_message = serialize($upd_files);
										$rId = $reg_id;
										$regNo = $reg_id;
										storedUserActivity($log_title, $log_message, $rId, $regNo);	
									}
									include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
									$key = $this->config->item('pass_key');
									$aes = new CryptAES();
									$aes->set_key(base64_decode($key));
									$aes->require_pkcs5();
									$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
									$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
									$this->sms_template_id = 'P6tIFIwGR';
									$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
									$newstring2 = str_replace("#REG_NUM#", "".$applicationNo."",$newstring1);
									$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
									$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
									$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
									$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
									$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
									$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
									$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
									$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
									$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
									$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
									$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
									$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
									$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
									$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
									$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
									$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
									$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
									$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring19);
									$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
									if(count($elern_msg_string) > 0)
									{
										foreach($elern_msg_string as $row)
										{
											$arr_elern_msg_string[]=$row['exam_code'];
										}
										if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
										{
											$newstring21 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring20);		
										}
										else
										{
											$newstring21 = str_replace("#E-MSG#", '',$newstring20);		
										}
									}
									else
									{
										$newstring21 = str_replace("#E-MSG#", '',$newstring20);
									}
									$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
									$info_arr=array('to'=>$result[0]['email'],
									'from'=>$emailerstr[0]['from'],
									'subject'=>$emailerstr[0]['subject'].' '.$applicationNo,
									'message'=>$final_str
									);
									//get invoice	
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
									//echo $this->db->last_query();exit;
									if(count($getinvoice_number) > 0)
									{
										/*if($getinvoice_number[0]['state_of_center']=='JAM')
											{
											$invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
											if($invoiceNumber)
											{
											$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
											}
											}
											else
										{*/
										$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
										if($invoiceNumber)
										{
											$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
										}
										//}
										$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'member_no'=>$applicationNo,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
										$this->db->where('pay_txn_id',$payment_info[0]['id']);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
									}	
									if($attachpath!='')
									{
										//send sms		
										$files=array($attachpath,$admitcard_pdf);			
										$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);		
										$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
										$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
										$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
										$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
										//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);						
										//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
										$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

										$this->Emailsending->mailsend_attch($info_arr,$files);
										//$this->Emailsending->mailsend($info_arr);
									}
								}
							}
							else
							{
								$log_title ="S2S Update fail:".$get_user_regnum[0]['member_regnumber'];
								$log_message = serialize($update_data);
								$rId = $MerchantOrderNo;
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);	
							}
						}
					}
					else if($payment_status==0)
					{
						// Handle transaction fail case 
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
						if($get_user_regnum[0]['status']==2)
						{
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' =>'0399','bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'S2S');
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							//Query to get Payment details	
							$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id');
							$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
							$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
							$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
							$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
							$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
							$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.regnumber'=>$payment_info[0]['member_regnumber']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
							//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
							$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
							$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
							$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
							$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
							$this->sms_template_id = 'Jw6bOIQGg';
							$newstring1 = str_replace("#application_num#", "",  $emailerstr[0]['emailer_text']);
							$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
							$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
							$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
							$info_arr=array(	'to'=>$result[0]['email'],
							'from'=>$emailerstr[0]['from'],
							'subject'=>$emailerstr[0]['subject'],
							'message'=>$final_str
							);
							// send SMS
							$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
							$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
							$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
							// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
							//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
							$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

							$this->Emailsending->mailsend($info_arr);
						}
					}
				}	
				else if($responsedata[6] == "IIBF_EXAM_DB")
				{
					sleep(8);
					$MerchantOrderNo = $responsedata[0]; 
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					$attachpath=$invoiceNumber='';
					switch ($responsedata[2])
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
					if($payment_status==1)
					{
						// Handle transaction success case 
						/*	$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');*/
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
						if($get_user_regnum[0]['status']==2)
						{
							######### payment Transaction ############
							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
							$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
							if($this->db->affected_rows())
							{
								$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
								if($get_payment_status[0]['status']==1)
								{
									// Handle transaction success case 
									$exam_code=$get_user_regnum[0]['exam_code'];
									$reg_id=$get_user_regnum[0]['member_regnumber'];
									############check capacity is full or not ##########
									$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
									//$subject_arr=$this->session->userdata['enduserinfo']['subject_arr'];
									if(count($exam_admicard_details) > 0)
									{		
										$msg='';
										$sub_flag=1;
										$sub_capacity=1;
										foreach($exam_admicard_details as $row)
										{
											$capacity=check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
											if($capacity==0)
											{
												#########get message if capacity is full##########
												$this->db->trans_start(); 
												$update_data = array('transaction_no' => $transaction_no,'status' => 1,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
												$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
												$this->db->trans_complete();
												//redirect(base_url().'Dbfuser/refund/'.base64_encode($MerchantOrderNo));
											}
										}
									}
									//$applicationNo = generate_dbf_reg_num(); 
									$applicationNo = generate_DBF_memreg($reg_id);
									######### payment Transaction ############
									$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
									##########Update Member Exam#############
									$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
									$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
									######update member_exam######
									$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
									$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
									//Query to get exam details	
									$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
									$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
									$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
									########## Generate Admit card and allocate Seat #############
									if(count($exam_admicard_details) > 0)
									{
										$password=random_password();
										foreach($exam_admicard_details as $row)
										{
											$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'exam_date'=>$row['exam_date'],'session_time'=>$row['time']));
											$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
											//echo $this->db->last_query().'<br>';
											$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
											if($seat_number!='')
											{
												$final_seat_number = $seat_number;
												$update_data = array('mem_mem_no'=>$applicationNo,'pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
												$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
											}
											else
											{
												$log_title ="Fail user seat allocation id:".$applicationNo;
												$log_message = serialize($exam_admicard_details);
												$rId = $applicationNo;
												$regNo = $applicationNo;
												storedUserActivity($log_title, $log_message, $rId, $regNo);
												//redirect(base_url().'Dbfuser/refund/'.base64_encode($MerchantOrderNo));
											}
										}
										##############Get Admit card#############
										$admitcard_pdf=genarate_admitcard($applicationNo,$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
									}	
									else
									{
										//redirect(base_url().'Dbfuser/refund/'.base64_encode($MerchantOrderNo));
									}
									if($exam_info[0]['exam_mode']=='ON')
									{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
									{$mode='Offline';}
									else{$mode='';}
									//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
									$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
									$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
									//Query to get Medium	
									$this->db->where('exam_code',$exam_code);
									$this->db->where('exam_period',$exam_info[0]['exam_period']);
									$this->db->where('medium_code',$exam_info[0]['exam_medium']);
									$this->db->where('medium_delete','0');
									$medium=$this->master_model->getRecords('medium_master','','medium_description');
									//Query to get Payment details	
									$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNo),'transaction_no,date,amount,id');
									//Query to get user details
									$this->db->join('state_master','state_master.state_code=member_registration.state');
									//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
									$result=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto');	
									include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
									$key = $this->config->item('pass_key');
									$aes = new CryptAES();
									$aes->set_key(base64_decode($key));
									$aes->require_pkcs5();
									$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
									$upd_files = array();
									$photo_file = 'p_'.$applicationNo.'.jpg';
									$sign_file = 's_'.$applicationNo.'.jpg';
									$proof_file = 'pr_'.$applicationNo.'.jpg';
									if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
									{	$upd_files['scannedphoto'] = $photo_file;	}
									if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
									{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
									if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
									{	$upd_files['idproofphoto'] = $proof_file;	}
									if(count($upd_files)>0)
									{
										$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
									}
									$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
									$this->sms_template_id = 'P6tIFIwGR';
									$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
									$newstring2 = str_replace("#REG_NUM#", "".$applicationNo."",$newstring1);
									$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
									$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
									$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
									$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
									$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
									$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
									$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
									$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
									$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
									$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
									$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
									$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
									$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
									$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
									$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
									$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
									$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
									$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring19);
									$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
									if(count($elern_msg_string) > 0)
									{
										foreach($elern_msg_string as $row)
										{
											$arr_elern_msg_string[]=$row['exam_code'];
										}
										if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
										{
											$newstring21 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring20);		
										}
										else
										{
											$newstring21 = str_replace("#E-MSG#", '',$newstring20);		
										}
									}
									else
									{
										$newstring21 = str_replace("#E-MSG#", '',$newstring20);
									}
									$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
									$info_arr=array('to'=>$result[0]['email'],
									'from'=>$emailerstr[0]['from'],
									'subject'=>$emailerstr[0]['subject'].' '.$applicationNo,
									'message'=>$final_str
									);
									//get invoice 	
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
									//echo $this->db->last_query();exit;
									if(count($getinvoice_number) > 0)
									{
										/*if($getinvoice_number[0]['state_of_center']=='JAM')
											{
											$invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
											if($invoiceNumber)
											{
											$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
											}
											}
											else
										{*/
										$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
										if($invoiceNumber)
										{
											$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
										}
										//}
										$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'member_no'=>$applicationNo,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
										$this->db->where('pay_txn_id',$payment_info[0]['id']);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
									}	
									if($attachpath!='')
									{		
										//send sms	
										$files=array($attachpath,$admitcard_pdf);					
										$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);			
										$sms_newstring = str_replace("#exam_name#", "".trim($exm_name_sms)."",$emailerstr[0]['sms_text']);
										$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",$sms_newstring);
										$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",$sms_newstring1);
										$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",$sms_newstring2);
										//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);				
										//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);	
										$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

										$this->Emailsending->mailsend_attch($info_arr,$files);
									}
								}
							}
							else
							{
								$log_title ="S2S Update fail:".$get_user_regnum[0]['member_regnumber'];
								$log_message = serialize($update_data);
								$rId = $MerchantOrderNo;
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);	
							}
						}
					}
					else if($payment_status==0)
					{
						// Handle transaction fail case 
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
						if($get_user_regnum[0]['status']==2)
						{
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399','bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'S2S');
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							//Query to get Payment details	
							$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id,member_regnumber');
							$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
							$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
							$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
							$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
							$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
							$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.regnumber'=>$payment_info[0]['member_regnumber']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
							//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
							$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
							$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
							$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
							$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
							$this->sms_template_id = 'Jw6bOIQGg';
							$newstring1 = str_replace("#application_num#", "",  $emailerstr[0]['emailer_text']);
							$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
							$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
							$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
							$info_arr=array(	'to'=>$result[0]['email'],
							'from'=>$emailerstr[0]['from'],
							'subject'=>$emailerstr[0]['subject'],
							'message'=>$final_str
							);
							// send SMS
							$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
							$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
							$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
							// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
							//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);	
							$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

							$this->Emailsending->mailsend($info_arr);
						}
					}
				}		
				else if($responsedata[6] == "IIBF_EXAM_DB_EXAM")
				{
					sleep(8);
					$MerchantOrderNo = $responsedata[0]; 
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					$attachpath=$invoiceNumber='';
					switch ($responsedata[2])
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
					if($payment_status==1)
					{	
						// Handle transaction success case 
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
						if($get_user_regnum[0]['status']==2)
						{
							######### payment Transaction ############
							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
							$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
							if($this->db->affected_rows())
							{
								$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
								if($get_payment_status[0]['status']==1)
								{
									if(count($get_user_regnum) > 0)
									{
										$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
									}
									######### payment Transaction ############
									/*	$this->db->trans_start();
										$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
										$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
									$this->db->trans_complete();*/
									//Query to get user details
									$this->db->join('state_master','state_master.state_code=member_registration.state');
									//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
									$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword');
									//Query to get exam details	
									$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
									$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
									$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
									include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
									$key = $this->config->item('pass_key');
									$aes = new CryptAES();
									$aes->set_key(base64_decode($key));
									$aes->require_pkcs5();
									$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
									########## Generate Admit card and allocate Seat #############
									$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
									if(count($exam_admicard_details) > 0)
									{
										############check capacity is full or not ##########
										//$subject_arr=$this->session->userdata['examinfo']['subject_arr'];
										if(count($exam_admicard_details) > 0)
										{	
											$msg='';
											$sub_flag=1;
											$sub_capacity=1;
											foreach($exam_admicard_details as $row)
											{
												$capacity=check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
												if($capacity==0)
												{
													#########get message if capacity is full##########
													//redirect(base_url().'Dbf/refund/'.base64_encode($MerchantOrderNo));
												}
											}
										}
										$password=random_password();
										foreach($exam_admicard_details as $row)
										{
											$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'exam_date'=>$row['exam_date'],'session_time'=>$row['time']));
											$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
											//echo $this->db->last_query().'<br>';
											$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
											if($seat_number!='')
											{
												$final_seat_number = $seat_number;
												$update_data = array('pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
												$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
											}
											else
											{
												$log_title ="Fail user seat allocation id:".$get_user_regnum[0]['member_regnumber'];
												$log_message = serialize($this->session->userdata['examinfo']['subject_arr']);
												$rId = $get_user_regnum[0]['member_regnumber'];
												$regNo = $get_user_regnum[0]['member_regnumber'];
												storedUserActivity($log_title, $log_message, $rId, $regNo);
												//redirect(base_url().'Dbf/refund/'.base64_encode($MerchantOrderNo));
											}
										}
										##############Get Admit card#############
										$admitcard_pdf=genarate_admitcard($get_user_regnum[0]['member_regnumber'],$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
										######update member_exam transaction######
										$update_data = array('pay_status' => '1');
										$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
									}
									else
									{
										//redirect(base_url().'Dbf/refund/'.base64_encode($MerchantOrderNo));
									}
									######update member_exam######	
									$update_data = array('pay_status' => '1');
									$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
									if($exam_info[0]['exam_mode']=='ON')
									{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
									{$mode='Offline';}
									else{$mode='';}
									//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
									$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
									$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
									//Query to get Medium	
									$this->db->where('exam_code',$exam_info[0]['exam_code']);
									$this->db->where('exam_period',$exam_info[0]['exam_period']);
									$this->db->where('medium_code',$exam_info[0]['exam_medium']);
									$this->db->where('medium_delete','0');
									$medium=$this->master_model->getRecords('medium_master','','medium_description');
									//Query to get Payment details	
									$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,id');
									$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
									$this->sms_template_id = 'P6tIFIwGR';
									$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
									$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
									$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
									$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
									$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
									$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
									$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
									$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
									$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
									$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
									$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
									$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
									$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
									$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
									$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
									$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
									$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
									$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
									$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
									$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring19);	
									$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
									if(count($elern_msg_string) > 0)
									{
										foreach($elern_msg_string as $row)
										{
											$arr_elern_msg_string[]=$row['exam_code'];
										}
										if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
										{
											$newstring21 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring20);		
										}
										else
										{
											$newstring21 = str_replace("#E-MSG#", '',$newstring20);		
										}
									}
									else
									{
										$newstring21 = str_replace("#E-MSG#", '',$newstring20);
									}
									$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
									$info_arr=array('to'=>$result[0]['email'],
									'from'=>$emailerstr[0]['from'],
									'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],
									'message'=>$final_str
									);
									//get invoice	
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
									//echo $this->db->last_query();exit;
									if(count($getinvoice_number) > 0)
									{
										/*if($getinvoice_number[0]['state_of_center']=='JAM')
											{
											$invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
											if($invoiceNumber)
											{
											$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
											}
											}
											else
										{*/
										$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
										if($invoiceNumber)
										{
											$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
										}
										//}
										$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
										$this->db->where('pay_txn_id',$payment_info[0]['id']);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
									}	
									if($attachpath!='')
									{						
										$files=array($attachpath,$admitcard_pdf);
										$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
										$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
										$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
										$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
										$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
										//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
										//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);	
										$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

										$this->Emailsending->mailsend_attch($info_arr,$files);
										//$this->Emailsending->mailsend($info_arr);
									}
								}
							}
							else
							{
								$log_title ="S2S Update fail:".$get_user_regnum[0]['member_regnumber'];
								$log_message = serialize($update_data);
								$rId = $MerchantOrderNo;
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);	
							}
						}
					}
					else if($payment_status==0)
					{
						// Handle transaction fail case 
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
						if($get_user_regnum[0]['status']==2)
						{
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399','bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'S2S');
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
							//Query to get Payment details	
							$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
							// Handle transaction 
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
							//Query to get exam details	
							$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
							$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
							$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
							$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
							$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
							//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
							$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
							$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
							$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
							$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
							$this->sms_template_id = 'Jw6bOIQGg';
							$newstring1 = str_replace("#application_num#", "".$get_user_regnum[0]['member_regnumber']."",  $emailerstr[0]['emailer_text']);
							$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
							$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
							$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
							$info_arr=array(	'to'=>$result[0]['email'],
							'from'=>$emailerstr[0]['from'],
							'subject'=>$emailerstr[0]['subject'],
							'message'=>$final_str
							);
							$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
							$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
							$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
							// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
							//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
							$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

							$this->Emailsending->mailsend($info_arr);
						}
					}
				}		
				else if($responsedata[6] == "iibfdra")
				{
					sleep(8);
					$MerchantOrderNo = $responsedata[0];
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					switch ($responsedata[2])
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
					if($payment_status==1)
					{
						// Handle transaction sucess case 
						/*$get_user_regnum=$this->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
							if(count($get_user_regnum) > 0)
							{
							$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
						}*/
						//get payment transaction id
						$transdetail_det = $this->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'status,id,date');
						if($transdetail_det[0]['status']==2)
						{
							$updated_date = date('Y-m-d H:i:s');
							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2], 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'description' => $responsedata[7], 'updated_date' => $updated_date, 'callback'=>'S2S');
							$this->master_model->updateRecord('dra_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							if($this->db->affected_rows())
							{
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
												$currentpics = $this->master_model->getRecords('dra_members', array('regid'=>$regidformemref), 'scannedphoto, scannedsignaturephoto, idproofphoto, training_certificate, quali_certificate'); 									$scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $trainingphoto_file = $qualiphoto_file = '';
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
											//echo "<BR>dra_member_exam id = ".$uniqueid;
										}
									}
								}
								/*$updated_date = date('Y-m-d H:i:s');
									$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2], 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'description' => $responsedata[7], 'updated_date' => $updated_date, 'callback'=>'S2S');
								$this->master_model->updateRecord('dra_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));*/
								/******************* code added for GST Changes, by Bhagwan Sahane, on 05-07-2017 ***************/
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
						$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2], 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'description' => $responsedata[7],'callback'=>'S2S');
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
				}
				else if ($responsedata[6] == "iibfamp")
				{//die();
					sleep(8);
					$MerchantOrderNo = $responsedata[0]; 
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					switch ($responsedata[2])
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
					if($payment_status==1)
					{
						$get_user_regnum_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'id,ref_id,status,payment_option ');
						//check user payment status is updated by s2s or not
						if($get_user_regnum_info[0]['status']==2)
						{
							if($get_user_regnum_info[0]['payment_option']==1 || $get_user_regnum_info[0]['payment_option']==4)
							{
								$reg_id=$get_user_regnum_info[0]['ref_id'];
								//$applicationNo = generate_mem_reg_num();
								//Get membership number from 'amp_membershipno' and update in 'amp_candidates'
								$applicationNo =generate_amp_memreg($reg_id);
								//update amp registration table
								$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
								$this->master_model->updateRecord('amp_candidates',$update_mem_data,array('id'=>$reg_id));
								//get user information...
								//$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id),'name,regnumber,email_id,photograph,signature,mobile_no,sponsor');
								$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id));
								$update_data = array('member_regnumber' => $applicationNo,'sponsor'=>$user_info[0]['sponsor'],'transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
								$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								//get payment details
								//Query to get Payment details	
								$payment_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$user_info[0]['regnumber']),'transaction_no,date,amount');
								$upd_files = array();
								$photo_file = 'p_'.$applicationNo.'.jpg';
								$sign_file = 's_'.$applicationNo.'.jpg';
								$proof_file = 'pr_'.$applicationNo.'.jpg';
								if(@ rename("./uploads/amp/photograph/".$user_info[0]['scannedphoto'],"./uploads/amp/photograph/".$photo_file))
								{	$upd_files['scannedphoto'] = $photo_file;	}
								if(@ rename("./uploads/amp/signature/".$user_info[0]['scannedsignaturephoto'],"./uploads/amp/signature/".$sign_file))
								{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
								if(count($upd_files)>0)
								{
									$this->master_model->updateRecord('amp_candidates',$upd_files,array('id'=>$reg_id));
								}
								//email to user
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer'));
								$this->sms_template_id = 'N8YRKIwMg';
								if(count($emailerstr) > 0)
								{
									$username=$user_info[0]['name'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									$newstring1 = str_replace("#REG_NUM#", "".$user_info[0]['regnumber']."",  $emailerstr[0]['emailer_text']);
									$newstring2 = str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring1);
									$newstring3 = str_replace("#EMAIL#", "".$user_info[0]['email_id']."",  $newstring2);
									$newstring4 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $newstring3);
									$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $newstring4);
									$newstring6 = str_replace("#STATUS#", "Transaction Successful",  $newstring5);
									$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring6);

									$info_arr=array('to'=>$user_info[0]['email_id'],
									'cc'=>'dd.trg1@iibf.org.in,dir.trg@iibf.org.in,iibfdevp@esds.co.in',
									'from'=>'logs@iibf.esdsconnect.com',
									'subject'=>$emailerstr[0]['subject'].' '.$user_info[0]['regnumber'],
									'message'=>$final_str,
									//'bcc'=>'priyanka.wadnere@esds.co.in'
									);

									$this->Emailsending->sendmail($info_arr);
									//$this->send_mail($applicationNo);
									//$this->send_sms($applicationNo);
									//Manage Log
									//Invoice generation
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum_info[0]['id']));
									if(count($getinvoice_number) > 0)
									{
										$invoiceNumber = generate_amp_invoice_number($getinvoice_number[0]['invoice_id']);
										if($invoiceNumber)
										{
											$invoiceNumber=$this->config->item('amp_invoice_no_prefix').$invoiceNumber;
										}
										$update_data = array('invoice_no' => $invoiceNumber,'member_no'=>$applicationNo,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
										$this->db->where('pay_txn_id',$get_user_regnum_info[0]['id']);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										$attachpath=genarate_amp_invoice($getinvoice_number[0]['invoice_id']);	
									}
									if($attachpath!='')
									{	 
										$sms_newstring = str_replace("#fee#", "".$payment_info[0]['amount']."",  $emailerstr[0]['sms_text']);
										$sms_newstring1 = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring);
										$sms_final_str = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $sms_newstring1);
										//$this->master_model->send_sms($user_info[0]['mobile_no'],$sms_final_str);	
										//$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']),$sms_final_str,$this->sms_template_id);
										$this->master_model->send_sms_common_all($user_info[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

										if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
										{
											//email send to sk datta and kavan for self sponsor
											if($user_info[0]['sponsor']=='self')
											{
												$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_self'));
												$this->sms_template_id = 'N8YRKIwMg';
												if(count($emailerSelfStr) > 0){
													$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
													if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
													if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
													if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
													if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = ''; }
													if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
													if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
													$selfstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerSelfStr[0]['emailer_text']);
													$selfstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr1);
													$selfstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr2);
													$selfstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $selfstr3);
													$selfstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $selfstr4);
													$selfstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $selfstr5);
													$selfstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $selfstr6);
													$selfstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $selfstr7);
													$selfstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $selfstr8);
													$selfstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $selfstr9);
													$selfstr11 = str_replace("#phone_no#", "".$phone_no."",  $selfstr10);
													$selfstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $selfstr11);
													$selfstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $selfstr12);
													$selfstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $selfstr13);
													$selfstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $selfstr14);
													$selfstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $selfstr15);
													$selfstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $selfstr16);
													$selfstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $selfstr17);
													$selfstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $selfstr18);
													$selfstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $selfstr19);
													$selfstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $selfstr20);
													$selfstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $selfstr21);
													$selfstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $selfstr22);
													$selfstr24 = str_replace("#till_present#", "".$till_present."",  $selfstr23);
													$selfstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $selfstr24);
													$selfstr26 = str_replace("#payment#", "".$payment."",  $selfstr25);
													$selfstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $selfstr26);
													$selfstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $selfstr27);
													$selfstr29 = str_replace("#STATUS#", "Transaction Successful",  $selfstr28);
													$selfstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $selfstr29);
													$final_selfstr = str_replace("#sponsor#", "".$sponsor."",  $selfstr30);
													$self_mail_arr = array(
													'to'=>'ravita@iibf.org.in,training@iibf.org.in,dd.trg1@iibf.org.in,dir.trg@iibf.org.in,iibfdevp@esds.co.in',
													'from'=>$emailerSelfStr[0]['from'],
													'subject'=>$emailerSelfStr[0]['subject'],
													'message'=>$final_selfstr,
													);
													//$this->Emailsending->mailsend($self_mail_arr);
													$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);
												}
											}
											//email send to sk datta and kavan for bank sponsor
											if($user_info[0]['sponsor']=='bank'){
												//get bank contact email id
												$contact_mail_id = $user_info[0]['sponsor_contact_email'];
												$emailerBankStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_bank'));
												$this->sms_template_id = 'N8YRKIwMg';
												if(count($emailerBankStr) > 0){
													$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
													if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
													if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
													if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
													if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = 'NO'; }
													if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
													if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
													if($user_info[0]['sponsor_contact_phone']!=0){ $sponsor_contact_phone = $user_info[0]['sponsor_contact_phone']; }else{ $sponsor_contact_phone = ''; }
													$bankstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerBankStr[0]['emailer_text']);
													$bankstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr1);
													$bankstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr2);
													$bankstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $bankstr3);
													$bankstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $bankstr4);
													$bankstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $bankstr5);
													$bankstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $bankstr6);
													$bankstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $bankstr7);
													$bankstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $bankstr8);
													$bankstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $bankstr9);
													$bankstr11 = str_replace("#phone_no#", "".$phone_no."",  $bankstr10);
													$bankstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $bankstr11);
													$bankstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $bankstr12);
													$bankstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $bankstr13);
													$bankstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $bankstr14);
													$bankstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $bankstr15);
													$bankstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $bankstr16);
													$bankstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $bankstr17);
													$bankstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $bankstr18);
													$bankstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $bankstr19);
													$bankstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $bankstr20);
													$bankstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $bankstr21);
													$bankstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $bankstr22);
													$bankstr24 = str_replace("#till_present#", "".$till_present."",  $bankstr23);
													$bankstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $bankstr24);
													$bankstr26 = str_replace("#payment#", "".$payment."",  $bankstr25);
													$bankstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $bankstr26);
													$bankstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $bankstr27);
													$bankstr29 = str_replace("#STATUS#", "Transaction Successful",  $bankstr28);
													$bankstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $bankstr29);
													$bankstr31 = str_replace("#sponsor#", "".$sponsor."",  $bankstr30);
													$bankstr32 = str_replace("#sponsor_bank_name#", "".$user_info[0]['sponsor_bank_name']."",  $bankstr31);
													$bankstr33 = str_replace("#sponsor_email#", "".$user_info[0]['sponsor_email']."",  $bankstr32);
													$bankstr34 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr33);
													$bankstr35 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr34);
													$bankstr36 = str_replace("#sponsor_contact_designation#", "".$user_info[0]['sponsor_contact_designation']."",  $bankstr35);
													$bankstr37 = str_replace("#sponsor_contact_std#", "".$user_info[0]['sponsor_contact_std']."",  $bankstr36);
													$bankstr38 = str_replace("#sponsor_contact_phone#", "".$sponsor_contact_phone."",  $bankstr37);
													$bankstr39 = str_replace("#sponsor_contact_mobile#", "".$user_info[0]['sponsor_contact_mobile']."",  $bankstr38);
													$final_bankstr = str_replace("#sponsor_contact_email#", "".$user_info[0]['sponsor_contact_email']."",  $bankstr39);
													$bank_mail_arr = array(
													//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in,prabhakara@iibf.org.in',
													//'to'=>'21bhavsartejasvi@gmail.com,'.$contact_mail_id.'',
													'to'=>'ravita@iibf.org.in,training@iibf.org.in,dd.trg1@iibf.org.in,dir.trg@iibf.org.in,iibfdevp@esds.co.in,'.$contact_mail_id.'',
													'from'=>$emailerBankStr[0]['from'],
													'subject'=>$emailerBankStr[0]['subject'],
													'message'=>$final_bankstr,
													);
													//print_r($bank_mail_arr);exit;
													//$this->Emailsending->mailsend($bank_mail_arr);
													$this->Emailsending->mailsend_attch($bank_mail_arr,$attachpath);
												}
											}
											$this->session->set_flashdata('success','Amp registration has been done successfully !!');
											//redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
										}
										else
										{
											//redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
										}
									}
								}
							}
							else if($get_user_regnum_info[0]['payment_option']==2 || $get_user_regnum_info[0]['payment_option']==3)
							{	
								$payment_option='';
								if($get_user_regnum_info[0]['payment_option']== 2)
								{
									$payment_option='second';
								}
								else if($get_user_regnum_info[0]['payment_option']== 3)
								{
									$payment_option='Full';
								}
								$reg_id=$get_user_regnum_info[0]['ref_id'];
								//update amp registration table with installment status
								$update_mem_data = array('payment' =>$payment_option);
								$this->master_model->updateRecord('amp_candidates',$update_mem_data,array('id'=>$reg_id));
								//$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id),'name,regnumber,email_id,photograph,signature,mobile_no,sponsor');
								$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id));
								//update payment transaction
								$update_data = array('member_regnumber' => $user_info[0]['regnumber'],'sponsor'=>$user_info[0]['sponsor'],'transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
								$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								//maintain log in for updated transaction
								$log_title ="Installment payment";
								$update_info['membershipno'] = $user_info[0]['regnumber'];
								$log_message = serialize($update_mem_data);
								$this->Ampmodel->create_log($log_title, $log_message);
								//Query to get Payment details	
								$payment_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$user_info[0]['regnumber']),'transaction_no,date,amount');
								//email to user
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer'));
								$this->sms_template_id = 'N8YRKIwMg';
								if(count($emailerstr) > 0)
								{
									$username=$user_info[0]['name'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									$newstring1 = str_replace("#REG_NUM#", "".$user_info[0]['regnumber']."",  $emailerstr[0]['emailer_text']);
									$newstring2 = str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring1);
									$newstring3 = str_replace("#EMAIL#", "".$user_info[0]['email_id']."",  $newstring2);
									$newstring4 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $newstring3);
									$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $newstring4);
									$newstring6 = str_replace("#STATUS#", "Transaction Successful",  $newstring5);
									$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring6);
									$info_arr=array('to'=>$user_info[0]['email_id'],
									'from'=>$emailerstr[0]['from'],
									'subject'=>$emailerstr[0]['subject'].' '.$user_info[0]['regnumber'],
									'message'=>$final_str,
									//'bcc'=>'kumartupe@gmail.com,raajpardeshi@gmail.com'
									);
									//$this->send_mail($applicationNo);
									//$this->send_sms($applicationNo);
									//Invoice generation
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum_info[0]['id']));
									if(count($getinvoice_number) > 0)
									{
										$invoiceNumber = generate_amp_invoice_number($getinvoice_number[0]['invoice_id']);
										//	echo '<pre>',print_r($invoiceNumber),'</pre>';
										if($invoiceNumber)
										{
											$invoiceNumber=$this->config->item('amp_invoice_no_prefix').$invoiceNumber;
										}
										$update_data = array('invoice_no' => $invoiceNumber,'member_no'=>$user_info[0]['regnumber'],'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
										$this->db->where('pay_txn_id',$get_user_regnum_info[0]['id']);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										$attachpath=genarate_amp_invoice($getinvoice_number[0]['invoice_id']);
										//echo $this->db->last_query();
										//echo '<pre>update_data',print_r($update_data),'</pre>';
										//echo '<pre>',print_r($attachpath),'</pre>';
									}
									if($attachpath!='')
									{
										$sms_newstring = str_replace("#fee#", "".$payment_info[0]['amount']."",  $emailerstr[0]['sms_text']);
										$sms_newstring1 = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring);
										$sms_final_str = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $sms_newstring1);
										//$this->master_model->send_sms($user_info[0]['mobile_no'],$sms_final_str);	
										//$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']),$sms_final_str,$this->sms_template_id);
										$this->master_model->send_sms_common_all($user_info[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

										//$this->Emailsending->mailsend($info_arr);
										if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
										{
											//email send to sk datta and kavan for self sponsor
											if($user_info[0]['sponsor']=='self')
											{
												$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_self'));
												$this->sms_template_id = 'N8YRKIwMg';
												if(count($emailerSelfStr) > 0){
													$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
													if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
													if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
													if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
													if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = 'No'; }
													if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
													//echo $payment;exit;
													if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
													$selfstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerSelfStr[0]['emailer_text']);
													$selfstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr1);
													$selfstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr2);
													$selfstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $selfstr3);
													$selfstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $selfstr4);
													$selfstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $selfstr5);
													$selfstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $selfstr6);
													$selfstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $selfstr7);
													$selfstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $selfstr8);
													$selfstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $selfstr9);
													$selfstr11 = str_replace("#phone_no#", "".$phone_no."",  $selfstr10);
													$selfstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $selfstr11);
													$selfstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $selfstr12);
													$selfstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $selfstr13);
													$selfstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $selfstr14);
													$selfstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $selfstr15);
													$selfstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $selfstr16);
													$selfstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $selfstr17);
													$selfstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $selfstr18);
													$selfstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $selfstr19);
													$selfstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $selfstr20);
													$selfstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $selfstr21);
													$selfstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $selfstr22);
													$selfstr24 = str_replace("#till_present#", "".$till_present."",  $selfstr23);
													$selfstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $selfstr24);
													$selfstr26 = str_replace("#payment#", "".$payment."",  $selfstr25);
													$selfstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $selfstr26);
													$selfstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $selfstr27);
													$selfstr29 = str_replace("#STATUS#", "Transaction Successful",  $selfstr28);
													$selfstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $selfstr29);
													$final_selfstr = str_replace("#sponsor#", "".$sponsor."",  $selfstr30);
													$self_mail_arr = array(
													//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in,prabhakara@iibf.org.in',
													'to'=>'ravita@iibf.org.in,training@iibf.org.in,dd.trg1@iibf.org.in,dir.trg@iibf.org.in,iibfdevp@esds.co.in',
													'from'=>$emailerSelfStr[0]['from'],
													'subject'=>$emailerSelfStr[0]['subject'],
													'message'=>$final_selfstr,
													);
													//echo '<pre>',print_r($self_mail_arr),'</pre>';
													$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);
												}
											}
											//email send to sk datta and kavan for bank sponsor
											if($user_info[0]['sponsor']=='bank'){
												$contact_mail_id = $user_info[0]['sponsor_contact_email'];
												$emailerBankStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_bank'));
												$this->sms_template_id = 'N8YRKIwMg';
												if(count($emailerBankStr) > 0){
													$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
													if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
													if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
													if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
													if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = ''; }
													if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
													if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
													if($user_info[0]['sponsor_contact_phone']!=0){ $sponsor_contact_phone = $user_info[0]['sponsor_contact_phone']; }else{ $sponsor_contact_phone = ''; }
													$bankstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerBankStr[0]['emailer_text']);
													$bankstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr1);
													$bankstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr2);
													$bankstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $bankstr3);
													$bankstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $bankstr4);
													$bankstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $bankstr5);
													$bankstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $bankstr6);
													$bankstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $bankstr7);
													$bankstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $bankstr8);
													$bankstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $bankstr9);
													$bankstr11 = str_replace("#phone_no#", "".$phone_no."",  $bankstr10);
													$bankstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $bankstr11);
													$bankstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $bankstr12);
													$bankstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $bankstr13);
													$bankstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $bankstr14);
													$bankstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $bankstr15);
													$bankstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $bankstr16);
													$bankstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $bankstr17);
													$bankstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $bankstr18);
													$bankstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $bankstr19);
													$bankstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $bankstr20);
													$bankstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $bankstr21);
													$bankstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $bankstr22);
													$bankstr24 = str_replace("#till_present#", "".$till_present."",  $bankstr23);
													$bankstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $bankstr24);
													$bankstr26 = str_replace("#payment#", "".$payment."",  $bankstr25);
													$bankstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $bankstr26);
													$bankstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $bankstr27);
													$bankstr29 = str_replace("#STATUS#", "Transaction Successful",  $bankstr28);
													$bankstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $bankstr29);
													$bankstr31 = str_replace("#sponsor#", "".$sponsor."",  $bankstr30);
													$bankstr32 = str_replace("#sponsor_bank_name#", "".$user_info[0]['sponsor_bank_name']."",  $bankstr31);
													$bankstr33 = str_replace("#sponsor_email#", "".$user_info[0]['sponsor_email']."",  $bankstr32);
													$bankstr34 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr33);
													$bankstr35 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr34);
													$bankstr36 = str_replace("#sponsor_contact_designation#", "".$user_info[0]['sponsor_contact_designation']."",  $bankstr35);
													$bankstr37 = str_replace("#sponsor_contact_std#", "".$user_info[0]['sponsor_contact_std']."",  $bankstr36);
													$bankstr38 = str_replace("#sponsor_contact_phone#", "".$sponsor_contact_phone."",  $bankstr37);
													$bankstr39 = str_replace("#sponsor_contact_mobile#", "".$user_info[0]['sponsor_contact_mobile']."",  $bankstr38);
													$final_bankstr = str_replace("#sponsor_contact_email#", "".$user_info[0]['sponsor_contact_email']."",  $bankstr39);
													$bank_mail_arr = array(
													//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in',
													'to'=>'ravita@iibf.org.in,training@iibf.org.in,dd.trg1@iibf.org.in,dir.trg@iibf.org.in,iibfdevp@esds.co.in,'.$contact_mail_id.'',
													'from'=>$emailerBankStr[0]['from'],
													'subject'=>$emailerBankStr[0]['subject'],
													'message'=>$final_bankstr,
													);
													$this->Emailsending->mailsend_attch($bank_mail_arr,$attachpath);
												}
											}
										} 
									}
									//Manage Log
									$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
									$this->log_model->logamptransaction("sbiepay", $pg_response, $responsedata[2]);
									//redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
								}
								else
								{
									//redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
								}
							}
						}
					}
					else if($payment_status==0)
					{
						$get_user_regnum_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status');
						if($get_user_regnum_info[0]['status']==2)
						{
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
							$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							//Manage Log
							$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
							$this->log_model->logamptransaction("sbiepay", $pg_response,$responsedata[2]);	
						}
					}
				}
				else if($responsedata[6] == "iibfmisc")
				{
					sleep(2);
					$MerchantOrderNo = $responsedata[0]; 
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					$attachpath=$invoiceNumber=$admitcard_pdf='';
					switch ($responsedata[2])
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
					if($payment_status==1)
					{
						$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id','','','1');
						if ($get_user_regnum_info[0]['status'] == 2) 
						{
							$gst_recovery_details_pk = $get_user_regnum_info[0]['ref_id'];
							$member_regnumber        = $get_user_regnum_info[0]['member_regnumber'];
							$update_data             = array(
							'transaction_no' => $transaction_no,
							'status' => 1,
							'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
							'auth_code' => '0300',
							'bankcode' => $responsedata[8],
							'paymode' => $responsedata[5],
							'callback' => 'S2S'
							);
							$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
					    /* Transaction Log */
							$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
							$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
							/* Update Pay Status */
							$gst_recovery_details_data = array('pay_status' => 1,'modified_on' => date('Y-m-d H:i:s'));
							$this->master_model->updateRecord('gst_recovery_details', $gst_recovery_details_data, array( 'gst_recovery_details_pk' => $gst_recovery_details_pk,'member_no' => $member_regnumber));
							/* Update Pay Status */
							$gst_recovery_master_data = array('pay_status' => 1,'modified_on' => date('Y-m-d H:i:s'));
							$this->master_model->updateRecord('gst_recovery_master', $gst_recovery_master_data, array('member_no' => $member_regnumber));
							/* Email */
							$get_exam_period = $this->master_model->getRecords('gst_recovery_details', array('gst_recovery_details_pk' => $get_user_regnum_info[0]['ref_id']), 'exam_period','','','');
							if($get_exam_period[0]['exam_period'] == '552')
							{
								$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_bcbf_gst_recovery_email'),'','','1');
								$this->sms_template_id = 'NA';
							}
							else
							{
								$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_gst_recovery_email'),'','','1');
								$this->sms_template_id = 'NA';
							}
							if (!empty($member_regnumber)) 
							{
								$user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $member_regnumber, 'isactive' => '1'), 'email,mobile','','','1');
							}
							if (count($emailerstr) > 0) 
							{ 
								/* Set Email sending options */
								$info_arr   = array(
								'to' => $user_info[0]['email'],
								'from' => $emailerstr[0]['from'],
								'subject' => $emailerstr[0]['subject'],
								'message' => $emailerstr[0]['emailer_text']
								);
								/* Invoice Number Genarate Functinality */
								if ($gst_recovery_details_pk != '') 
								{
									if($get_exam_period[0]['exam_period'] == '552')
									{
										$invoiceNumber = "";
										$update_data_invoice = array();
									}
									else
									{
										$invoiceNumber = generate_gst_recovery_invoice_number($gst_recovery_details_pk);
									}
									if ($invoiceNumber != '') 
									{
										$invoiceNumber = $this->config->item('gst_recovery_prefix') . $invoiceNumber;
										$update_data_invoice = array('doc_no' => $invoiceNumber,'date_of_doc' => date('Y-m-d H:i:s'));
										$this->db->where('gst_recovery_details_pk', $gst_recovery_details_pk);
										$this->master_model->updateRecord('gst_recovery_details', $update_data_invoice, array('modified_on' => date('Y-m-d H:i:s')));
									}
									/* Invoice Create Function */
									$get_exam_period = $this->master_model->getRecords('gst_recovery_details', array('gst_recovery_details_pk' => $gst_recovery_details_pk), 'exam_period','','','');
									if($get_exam_period[0]['exam_period'] == '552')
									{
										$attachpath  = '';
										$log_title   = "GST Recovery - BCBF - sbitranssuccess function";
									}
									else
									{
										$attachpath  = genarate_gst_recovery_invoice($gst_recovery_details_pk);
										$log_title   = "GST Recovery-Invoice Genarate";
									}
									/* User Log Activities  */
									$log_message = serialize($update_data_invoice);
									$rId         = $gst_recovery_details_pk;
									$regNo       = $member_regnumber;
									storedUserActivity($log_title, $log_message, $rId, $regNo);
								}
								if ($attachpath != '') 
								{
									if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) 
									{
										//redirect(base_url() . 'GstRecovery/acknowledge/');
									} 
									else 
									{ 
										//redirect(base_url() . 'GstRecovery/acknowledge/');
									}
								} 
								else 
								{
									if($get_exam_period[0]['exam_period'] == '552')
									{
										if ($this->Emailsending->mailsend($info_arr)) 
										{
											$anil_info_arr   = array(  
											'to' => 'anil@iibf.org.in',
											'from' => $emailerstr[0]['from'],
											'subject' => "BCBF 8 Rs Fee Paid",
											'message' => "Member No.".$member_regnumber);
											$this->Emailsending->mailsend($anil_info_arr);
											//redirect(base_url() . 'GstRecovery/bcbf_acknowledge/');
										} 
									}
									//redirect(base_url() . 'GstRecovery/acknowledge/');
								}
							}
						}
					}
				}
				if ($responsedata[6] == "iibftrg")
				{
					sleep(8);
					$MerchantOrderNo = $responsedata[0]; 
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					switch ($responsedata[2])
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
					if($payment_status==1)
					{
						$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id');
						//check user payment status is updated by S2S or not
						if ($get_user_regnum_info[0]['status'] == 2) 
						{
							$reg_id        = $get_user_regnum_info[0]['ref_id'];
							$applicationNo = $get_user_regnum_info[0]['member_regnumber'];
							$update_data   = array(
							//'member_regnumber' => $applicationNo,
							'transaction_no' => $transaction_no,
							'status' => 1,
							'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
							'auth_code' => '0300',
							'bankcode' => $responsedata[8],
							'paymode' => $responsedata[5],
							'callback' => 'S2S'
							);
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							$reg_data = $this->master_model->getRecords('blended_registration', array('member_no' => $applicationNo,'blended_id' => $reg_id),'program_code,center_code,batch_code,training_type,venue_code,start_date');
							$selected_program_code = $reg_data[0]['program_code'];
							$selected_center_code = $reg_data[0]['center_code'];
							$venue_batch_code = $reg_data[0]['batch_code'];
							$selected_training_type = $reg_data[0]['training_type'];
							$selected_venue_code	= $reg_data[0]['venue_code'];		
							$sDate = $reg_data[0]['start_date'];
							/* Check Registration Capacity */
							$RegCount = "";
							$RegCount = blendedRegistrationCapacity($selected_program_code,$selected_center_code,$venue_batch_code,$selected_training_type,$selected_venue_code,$sDate);
							/* Get Venue Capacity */
							$capacity = "";
							$capacity = getVenueCapacity($selected_program_code,$selected_center_code,$venue_batch_code,$selected_training_type,$selected_venue_code,$sDate);
							/* Get User Attempt */
							$attemptQry=$this->db->query("SELECT attempt,fee_flag FROM blended_eligible_master WHERE member_number='".$applicationNo."' AND program_code = '" . $selected_program_code . "' LIMIT 1"); 
							$attemptArr = $attemptQry->row_array();
							$attempt = $attemptArr['attempt'];
							$fee_flag=$attemptArr['fee_flag'];
							/* Check Count of Vitual Attempts */
							$VitualAttemptsCount = "";
							$VitualAttemptsCount = getVitualAttemptsCounts($applicationNo,$selected_program_code,$venue_batch_code);
							if($VitualAttemptsCount != 0)
							{
								$attempt = 1;
							}
							$attempt = $attempt+1;
							if($RegCount >= $capacity)
							{
								// Refundable
								$blended_data = array('pay_status' => 3, 'attempt'=>$attempt, 'modify_date' => date('Y-m-d H:i:s'));
								$this->master_model->updateRecord('blended_registration',$blended_data,array('blended_id'=>$reg_id));
								/* User Log Activities  */
								$log_title ="Blended Course Registraion - Capacity is full after payment success.";
								$log_message = $log_message = 'Program Code : '.$selected_program_code;
								$rId = $reg_id;
								$regNo = $applicationNo;
								storedUserActivity($log_title, $log_message, $rId, $regNo);
							}
							/* Update Pay Status and User Attemp Status */
							$blended_data = array('pay_status'=>1, 'attempt'=>$attempt, 'modify_date'=>date('Y-m-d H:i:s'));
							$this->master_model->updateRecord('blended_registration',$blended_data,array('blended_id'=>$reg_id));
							$log_title ="Blended Course Registraion update query.".$this->db->last_query();
							$log_message = serialize($blended_data);
							$rId = $reg_id;
							$regNo = $reg_id;
							storedUserActivity($log_title, $log_message, $rId, $regNo);
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_blended_email'));
							$this->sms_template_id = 'Xb5EFSwGg';
							if (!empty($applicationNo)) {
								$user_info = $this->master_model->getRecords('member_registration',array('regnumber'=> $applicationNo,'isactive'=>'1'),'email,mobile');
							}
							if (count($emailerstr) > 0) 
							{
								/* Set Email Content For user */
						    $Qry=$this->db->query("SELECT program_code, program_name, training_type, center_name, venue_name, start_date, end_date,member_no FROM blended_registration WHERE blended_id = '".$reg_id."' LIMIT 1");
								$detailsArr        = $Qry->row_array();
								$program_code = $detailsArr['program_code'];
								$program_name = $detailsArr['program_name'];
								$training_type = $detailsArr['training_type'];
								if($training_type=="PC"){
									$training_type='Physical Classroom';
									$venue_name   = $detailsArr['venue_name'];
								}
								else{
									$training_type='Virtual Classes';
									$venue_name   = '-';
								}
								$center_name  = $detailsArr['center_name'];
								$start_date1  = $detailsArr['start_date'];
								$end_date1    = $detailsArr['end_date'];
								$start_date   = date("d-M-Y", strtotime($start_date1));
								$end_date     = date("d-M-Y", strtotime($end_date1));
								$newstring    = str_replace("#program_name#","".$program_name."",$emailerstr[0]['emailer_text']);
								$newstring1   = str_replace("#training_type#","".$training_type."",$newstring);
								$newstring2   = str_replace("#center_name#","".$center_name."",$newstring1);
								$newstring3   = str_replace("#venue_name#","".$venue_name."",$newstring2);
								$newstring4   = str_replace("#start_date#","".$start_date."",$newstring3);
								$newstring5   = str_replace("#end_date#", "".$end_date."",$newstring4);
								/* Set Email sending options */
								$info_arr          = array(
								'to' => $user_info[0]['email'],
								'from' => $emailerstr[0]['from'],
								'subject' => $emailerstr[0]['subject'].' '.$detailsArr['member_no'],
								'message' => $newstring5
								);
								$getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo,'pay_txn_id' => $get_user_regnum_info[0]['id']));
								$zone_code = ""; 
								$zoneArr = array();
								//$regno = $this->session->userdata['memberdata']['regno'];
								$zoneArr = $this->master_model->getRecords('blended_registration',array('blended_id'=>$reg_id,'pay_status'=>1),'zone_code,gstin_no');
								$zone_code = $zoneArr[0]['zone_code'];
								$gstin_no          = $zoneArr[0]['gstin_no'];
								/* Invoice Number Genarate Functinality */
								if (count($getinvoice_number) > 0){
									$invoiceNumber = generate_blended_invoice_number($getinvoice_number[0]['invoice_id'],$zone_code);
									if($invoiceNumber){$invoiceNumber = $this->config->item('blended_invoice_T'.$zone_code.'_prefix').$invoiceNumber;}
									$update_data = array(
									'invoice_no' => $invoiceNumber,
									//'member_no' => $applicationNo,
									'transaction_no' => $transaction_no,
									'date_of_invoice' => date('Y-m-d H:i:s'),
									'modified_on' => date('Y-m-d H:i:s')
									);
									$this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
									$this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
									/* Invoice Genarate Function */
									$attachpath = genarate_blended_invoice($getinvoice_number[0]['invoice_id'],$zone_code,$program_name,$gstin_no);
									/* User Log Activities  */
									$log_title ="Blended Course Registration-Invoice Genarate";
									$log_message = serialize($update_data);
									$rId = $reg_id;
									$regNo = $applicationNo;
									storedUserActivity($log_title, $log_message, $rId, $regNo);
								}
								if ($attachpath != '') 
								{	
									/* Email Send To Clints */
									if (!empty($applicationNo)) {
										$reg_info = $this->master_model->getRecords('blended_registration',array('member_no'=> $applicationNo,'blended_id' => $reg_id));
									}
									$payment_infoArr=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$reg_info[0]['member_no']),'transaction_no,date,amount');
									if($reg_info[0]['member_no'] == $applicationNo)
									{
										$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'blended_emailer_client'));
										$this->sms_template_id = 'NA';
										if(count($emailerSelfStr) > 0)
										{
											$designation_name=$undergraduate_name=$graduate_name=$postgraduate_name=$specify_qualification=$institution_name=$pay="";
											$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
											$graduate=$this->master_model->getRecords('qualification', array('type' => 'GR'));
											$postgraduate=$this->master_model->getRecords('qualification', array('type'=> 'PG'));
											$institution_master = $this->master_model->getRecords('institution_master');
											$states             = $this->master_model->getRecords('state_master');
											$designation        = $this->master_model->getRecords('designation_master');
											if(count($designation)){
												foreach($designation as $designation_row){
													if($reg_info[0]['designation']==$designation_row['dcode']){
													$designation_name = $designation_row['dname'];}
												} 
											}
											if(count($institution_master)){
												foreach($institution_master as $institution_row){ 	
													if($reg_info[0]['associatedinstitute']==$institution_row['institude_id']){
													$institution_name = $institution_row['name'];}
												}
											}
											if($reg_info[0]['qualification']=='U'){$undergraduate_name = 'Under Graduate';}
											if($reg_info[0]['qualification']=='G'){$graduate_name = 'Graduate';}
											if($reg_info[0]['qualification']=='P'){$postgraduate_name = 'Post Graduate';}	
											$qualificationArr=$this->master_model->getRecords('qualification',array('qid'=>$reg_info[0]['specify_qualification']),'name','','1');
											if(count($qualificationArr)) 
											{
												$specify_qualification = $qualificationArr[0]['name'];
											}
											$training_type = $reg_info[0]['training_type'];
											if($training_type=="PC")
											{
												$training_type='Physical Classroom';
												$venue_name   = $reg_info[0]['venue_name'];
											}
											else
											{
												$training_type='Virtual Classes';
												$venue_name   = "-";
											}
											$center_name  = $reg_info[0]['center_name'];
											$start_date   = date("d-M-Y", strtotime($reg_info[0]['start_date']));
											$end_date     = date("d-M-Y", strtotime($reg_info[0]['end_date']));
											if($reg_info[0]['pay_status'] == 1){ $pay = "Success";}
											$selfstr1 = str_replace("#regnumber#", "".$reg_info[0]['member_no']."",  $emailerSelfStr[0]['emailer_text']);
											$selfstr2 = str_replace("#program_name#", "".$reg_info[0]['program_name']."",  $selfstr1);
											$selfstr3 = str_replace("#center_name#", "".$center_name."",  $selfstr2);
											$selfstr4 = str_replace("#venue_name#", "".$venue_name."", $selfstr3);
											$selfstr5 = str_replace("#start_date#", "".$start_date."", $selfstr4);
											$selfstr6 = str_replace("#end_date#", "".$end_date."",  $selfstr5);
											$selfstr7 = str_replace("#training_type#", "".$training_type."",  $selfstr6);	
											$selfstr8 = str_replace("#name#", "".$reg_info[0]['namesub']." ".$reg_info[0]['firstname']." ".$reg_info[0]['middlename']." ".$reg_info[0]['lastname'],  $selfstr7);
											$selfstr9 = str_replace("#address1#", "".$reg_info[0]['address1']."",  $selfstr8);
											$selfstr10 = str_replace("#address2#", "".$reg_info[0]['address2']."",  $selfstr9);
											$selfstr11 = str_replace("#address3#", "".$reg_info[0]['address3']."",  $selfstr10);
											$selfstr12 = str_replace("#address4#", "".$reg_info[0]['address4']."",  $selfstr11);
											$selfstr13 = str_replace("#district#", "".$reg_info[0]['district']."",  $selfstr12);
											$selfstr14 = str_replace("#city#", "".$reg_info[0]['city']."",  $selfstr13);
											$selfstr15 = str_replace("#state#", "".$reg_info[0]['state']."",  $selfstr14);
											$selfstr16 = str_replace("#pincode#", "".$reg_info[0]['pincode']."",  $selfstr15);
											$selfstr17 = str_replace("#designation#", "".$designation_name."",  $selfstr16);
											$selfstr18 = str_replace("#associatedinstitute#", "".$institution_name."",  $selfstr17);
											$selfstr19 = str_replace("#dateofbirth#", "".$reg_info[0]['dateofbirth']."",  $selfstr18);
											$selfstr20 = str_replace("#email#", "".$reg_info[0]['email']."",  $selfstr19);
											$selfstr21 = str_replace("#mobile#", "".$reg_info[0]['mobile']."",  $selfstr20);
											$selfstr22 = str_replace("#res_stdcode#", "".$reg_info[0]['res_stdcode']."",  $selfstr21);
											$selfstr23 = str_replace("#residential_phone#", "".$reg_info[0]['residential_phone']."",  $selfstr22);
											$selfstr24 = str_replace("#stdcode#", "".$reg_info[0]['stdcode']."",  $selfstr23);
											$selfstr25 = str_replace("#office_phone#", "".$reg_info[0]['office_phone']."",  $selfstr24);
											$selfstr26 = str_replace("#qualification#", "".$undergraduate_name.$graduate_name.$postgraduate_name."",  $selfstr25);
											$selfstr27 = str_replace("#specify_qualification#", "".$specify_qualification."",  $selfstr26);
											$selfstr28 = str_replace("#emergency_name#", "".$reg_info[0]['emergency_name']."",  $selfstr27);
											$selfstr29 = str_replace("#emergency_contact_no#", "".$reg_info[0]['emergency_contact_no']."",  $selfstr28);
											$selfstr30 = str_replace("#blood_group#", "".$reg_info[0]['blood_group']."",  $selfstr29);
											$selfstr31 = str_replace("#TRANSACTION_NO#", "".$payment_infoArr[0]['transaction_no']."",  $selfstr30);
											$selfstr32 = str_replace("#AMOUNT#", "".$payment_infoArr[0]['amount']."",  $selfstr31);
											$selfstr33 = str_replace("#STATUS#", "Transaction Successful",  $selfstr32);
											$final_selfstr = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_infoArr[0]['date']))."",  $selfstr33);
											$s1 = str_replace("#program_code#", "".$reg_info[0]['program_code'],$emailerSelfStr[0]['subject']);
									    $final_sub = str_replace("#Batch_code#", "".$reg_info[0]['batch_code']."",  $s1);
											/* Get Client Emails Details */
											$emailsQry = $this->db->query("SELECT emails FROM offline_email_master WHERE zone_code = '" . $reg_info[0]['zone_code'] . "' AND program_code = '" . $reg_info[0]['program_code'] . "' AND batch_code = '" . $reg_info[0]['batch_code'] . "'AND isdelete = 0 LIMIT 1 ");
											$emailsArr    = $emailsQry->row_array();
											$emails  = $emailsArr['emails'];	
											$self_mail_arr = array(
											'to'=>$emails,
											'from'=>$emailerSelfStr[0]['from'],
											'subject'=> $final_sub,
											'message'=>$final_selfstr);	
											$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);
										}
									}
									/* SMS Sending Code */
									$sms_newstring = str_replace("#program_name#", "" . $program_name . "", $emailerstr[0]['sms_text']);
									// $this->master_model->send_sms($user_info[0]['mobile'], $sms_newstring);
									//$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']),$sms_final_str,$this->sms_template_id);
									$this->master_model->send_sms_common_all($user_info[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

									if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
									} 
								} 
							}
						}
					}	
					else if($payment_status==0)
					{
						$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status');
						if($get_user_regnum_info[0]['status']==2)
						{
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
						}
					}
				}
				else if($responsedata[6] == "IIBFDRAREG")
				{
					sleep(8);
					$MerchantOrderNo = $responsedata[0];
					$transaction_no  = $responsedata[1];               
					$payment_status = 2;
					switch ($responsedata[2])
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
					//Payment Success
					if($payment_status==1)
					{
						// Handle transaction success case                    
						$get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status,id');
						if ($get_user_regnum[0]['status'] == 2)
						{
							if (count($get_user_regnum) > 0)
							{
								// Get agency_id from agency_center table
								$agency_center_info = $this->master_model->getRecords('agency_center', array(
								'center_id' => $get_user_regnum[0]['ref_id']
								), 'agency_id,center_id,institute_code');
							}
							// Get Details from dra_inst_registration table for email and center type
							if(count($agency_center_info) > 0){   
								$user_info = $this->master_model->getRecords('dra_inst_registration', array(
								'id' => $agency_center_info[0]['agency_id']
								), 'id,email_id,inst_head_email,center_type');            
								$email_id = $user_info[0]['inst_head_email'];
							}                       
							$update_data = array(
							'transaction_no' => $transaction_no,
							'status' => 1,
							'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
							'auth_code' => '0300',
							'bankcode' => $responsedata[8],
							'paymode' => $responsedata[5],
							'callback' => 'S2S'
							);
							$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
							$agency_id = $agency_center_info[0]['agency_id'];
							$center_id = $agency_center_info[0]['center_id'];
							$institute_code = $agency_center_info[0]['institute_code'];
							if($this->db->affected_rows())
							{   
								if($institute_code != 0) // Conditions for only center add (After Login)
								{
									$validate_upto = '';
									if ($user_info[0]['center_type'] == 'T')
									{
										$created_on    = date('Y-m-d H:i:s');
										$validate_upto = date('Y-m-d H:i:s', strtotime('+3 months', strtotime($created_on)));
									}
									$update_data = array('pay_status' => '1');
									$this->master_model->updateRecord('agency_center', $update_data, array('center_id' => $agency_id));
								}
								else // Conditions for only Agency + center add(Outer Registration)
								{
									/* Payment Status Updates */
									$update_data1 = array('status' => '1');
									$this->master_model->updateRecord('dra_inst_registration',$update_data1,array('id'=>$agency_id));
									$update_data2 = array('pay_status' => '1','center_status'=>'A');
									$this->master_model->updateRecord('agency_center',$update_data2, array('center_id'=>$center_id));
									$update_data3 = array('pay_status' => '1');
									$this->master_model->updateRecord('dra_accerdited_master', $update_data3, array('dra_inst_registration_id' => $agency_id));
									$check_status = $this->master_model->getRecords('dra_accerdited_master',array('dra_inst_registration_id' => $agency_id,'pay_status' => '1'),'pay_status');
									if($check_status[0]['pay_status'] == '1')
									{
										$update_data4 = array('center_id' => $center_id);
										$last_id = $this->master_model->insertRecord('config_institute_code', $update_data4, true);      
										/* Get last Inst. Code */
										$institute_code = $last_id;
										if($institute_code != "" && $institute_code > 0)
										{
											/* Add Inst. Code  in agency_center and dra_accerdited_master */
											$update_data = array('institute_code' => $institute_code);
											$this->master_model->updateRecord('agency_center', $update_data, array('center_id' => $center_id));
											$update_data = array('institute_code' => $institute_code);
											$this->master_model->updateRecord('dra_accerdited_master', $update_data, array('dra_inst_registration_id' => $agency_id));
										}
									}
								}
								//$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
								$emailerstr = $this->master_model->getRecords('emailer', array(
								'emailer_name' => 'dra_institute'
								));
								$this->sms_template_id = 'NA';
								if (count($emailerstr) > 0 && (count($get_user_regnum) > 0)) {
									$final_str = $emailerstr[0]['emailer_text'];                           
									$info_arr  = array(
									'to' => $email_id,
									'from' => $emailerstr[0]['from'],
									'subject' => $emailerstr[0]['subject'],
									'message' => $final_str
									);
									//genertate invoice and email send with invoice attach 8-7-2017                   
									//get invoice   
									$getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
									'receipt_no' => $MerchantOrderNo,
									'pay_txn_id' => $get_user_regnum[0]['id']
									));
									if (count($getinvoice_number) > 0) {
										$invoiceNumber = generate_dra_invoice_number($getinvoice_number[0]['invoice_id']);
										if ($invoiceNumber) {
											$invoiceNumber = $this->config->item('DRA_invoice_no_prefix') . $invoiceNumber;
										}
										$update_data = array(
										'invoice_no' => $invoiceNumber,
										'transaction_no' => $transaction_no,
										'date_of_invoice' => date('Y-m-d H:i:s'),
										'modified_on' => date('Y-m-d H:i:s')
										);
										$this->db->where('pay_txn_id', $get_user_regnum[0]['id']);
										$this->master_model->updateRecord('exam_invoice', $update_data, array(
										'receipt_no' => $MerchantOrderNo
										));
										$attachpath = genarate_dra_invoice($getinvoice_number[0]['invoice_id']);
									}
									if ($attachpath != '') {
										if ($this->Emailsending->mailsend_attch_DRA($info_arr, $attachpath)) {
										}
									}
								}
							}                   
						}                   
					}else if($payment_status==0)
					{//Payment Fail                   
						$update_data = array(
						'transaction_no' => $transaction_no,
						'status' => 0,
						'transaction_details' => $responsedata[2],
						'bankcode' => $responsedata[8],
						'paymode' => $responsedata[5],
						'description' => $responsedata[7],
						'callback'=>'S2S'
						);                   
						// Handle transaction fail case
						$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(
						'receipt_no' => $MerchantOrderNo
						), 'ref_id,status');
					}
				}
				else if($responsedata[6] == "IIBFDRAREN")
				{
					sleep(8);
					//Added by manoj MMM for agency center Renewal 
					$MerchantOrderNo = $responsedata[0];
					$transaction_no  = $responsedata[1];				
					$payment_status = 2;
					switch ($responsedata[2])
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
					//Payment Success
					if($payment_status==1)
					{
						// Handle transaction success case 					
						$get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status,id');
						if ($get_user_regnum[0]['status'] == 2) 
						{
						  $update_data = array(
							'transaction_no' => $transaction_no,
							'status' => 1,
							'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
							'auth_code' => '0300',
							'bankcode' => $responsedata[8],
							'paymode' => $responsedata[5],
							'callback' => 'S2S'
							);
							$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
							if($this->db->affected_rows())
							{	
								if (count($get_user_regnum) > 0) 
								{
									$renew_info = $this->master_model->getRecords('agency_center_renew', array(
									'agency_renew_id' => $get_user_regnum[0]['ref_id']
									), 'agency_id');
								}
								if(count($renew_info) > 0){
									$user_info = $this->master_model->getRecords('dra_inst_registration', array(
									'id' => $renew_info[0]['agency_id']
									), 'inst_head_email');	
									$email_id = $user_info[0]['inst_head_email'];
								}						
								// Get email of institute by its id                        
								$update_agency_pay_statues = array('pay_status' => '1');
								$this->master_model->updateRecord('agency_center_renew', $update_agency_pay_statues, array(
								'agency_renew_id' => $get_user_regnum[0]['ref_id']
								));
								//================== NEW CODE ADDED TO update PAY status  BY Manoj============
								$agency_info = $this->master_model->getRecords('agency_center_renew', array(
								'agency_renew_id' => $get_user_regnum[0]['ref_id']
								));	
								$agency_id 	= $agency_info[0]['agency_id']; 					
								$center_ids = $agency_info[0]['centers_id']; 
								$center_arr = explode(',',$center_ids);
								// ADD CODE TO SET PAY STATUS 1: SUCCESS  
								$update_data = array('center_status' => 'A','pay_status'  => '1');					
								foreach($center_arr as $center_id){
									$this->master_model->updateRecord('agency_center', $update_data, array(
									'center_id' => $center_id
									));
								}
								//===============================================================================						
								//$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
								$emailerstr = $this->master_model->getRecords('emailer', array(
								'emailer_name' => 'dra_agency_renew'
								));
								$this->sms_template_id = 'NA';
								if (count($emailerstr) > 0 && (count($get_user_regnum) > 0)) {
									$final_str = $emailerstr[0]['emailer_text'];							
									$info_arr  = array(
									'to' => $email_id,
									'from' => $emailerstr[0]['from'],
									'subject' => $emailerstr[0]['subject'],
									'message' => $final_str
									);
									//genertate invoice and email send with invoice attach 8-7-2017                    
									//get invoice    
									$getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
									'receipt_no' => $MerchantOrderNo,
									'pay_txn_id' => $get_user_regnum[0]['id']
									));
									if (count($getinvoice_number) > 0) {
										$invoiceNumber = generate_agnecy_renewal_invoice_number($getinvoice_number[0]['invoice_id']);
										if ($invoiceNumber) {
											$invoiceNumber = $this->config->item('DRA_agency_renew_invoice_no_prefix') . $invoiceNumber;
										}
										$update_data = array(
										'invoice_no' => $invoiceNumber,
										'transaction_no' => $transaction_no,
										'date_of_invoice' => date('Y-m-d H:i:s'),
										'modified_on' => date('Y-m-d H:i:s')
										);
										$this->db->where('pay_txn_id', $get_user_regnum[0]['id']);
										$this->master_model->updateRecord('exam_invoice', $update_data, array(
										'receipt_no' => $MerchantOrderNo
										));
										$attachpath = genarate_agnecy_renewal_invoice($getinvoice_number[0]['invoice_id']);
									}
									if ($attachpath != '') {
										if ($this->Emailsending->mailsend_attch_DRA($info_arr, $attachpath)) {
										} 
									} 
								}
							}					
						}					
					}else if($payment_status==0)
					{//Payment Fail					
						$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status');
						if ($get_user_regnum_info[0]['status'] != 0 && $get_user_regnum_info[0]['status'] == 2) 
						{
							$update_data = array(
							'transaction_no' => $transaction_no,
							'status' => 0,
							'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
							'auth_code' => '0399',
							'bankcode' => $responsedata[8],
							'paymode' => $responsedata[5],
							'callback' => 'S2S'
							);
							$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
						}
					}
				}
				else if($responsedata[6] == "IIBF_EL")
				{
					sleep(8);
					$MerchantOrderNo = $responsedata[0]; 
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					$attachpath=$invoiceNumber=$admitcard_pdf='';
					switch ($responsedata[2])
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
					if($payment_status==1)
					{	
						$exam_period_date='';
						//Handle transaction success case
						$elective_subject_name='';
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
						if($get_user_regnum[0]['status']==2)
						{
							######### payment Transaction ############
							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
							$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
							if($this->db->affected_rows())
							{
								if(count($get_user_regnum) > 0)
								{
									$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
								}
								//Query to get exam details	
								$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
								$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
								$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
								$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
								$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code');
								######update member_exam######
								$update_data = array('pay_status' => '1');
								$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
								//Query to get user details
								$this->db->join('state_master','state_master.state_code=member_registration.state');
								$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name');
								if(count($exam_info) <= 0)
								{
									$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']));
								}
								if($exam_info[0]['exam_mode']=='ON')
								{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
								{$mode='Offline';}
								else{$mode='';}
								if($exam_info[0]['examination_date']!='0000-00-00')
								{
									$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
								}
								else if($exam_info[0]['exam_code']!=990)
								{
									//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
									$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
									$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
								}
								//Query to get Medium	
								$this->db->where('exam_code',$exam_info[0]['exam_code']);
								$this->db->where('exam_period',$exam_info[0]['exam_period']);
								$this->db->where('medium_code',$exam_info[0]['exam_medium']);
								$this->db->where('medium_delete','0');
								$medium=$this->master_model->getRecords('medium_master','','medium_description');
								$this->db->where('state_delete','0');
								$states=$this->master_model->getRecords('state_master',array('state_code'=>$exam_info[0]['state_place_of_work']),'state_name');
								//Query to get Payment details	
								$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,id');
								$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
								$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
								//if($this->session->userdata['examinfo']['elected_exam_mode']=='E')
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'Elearning_apply_exam_transaction_success'));
								$this->sms_template_id = 'dvPQcIQGR';
								$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
								$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
								$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
								$newstring4 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring3);
								$newstring5 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring4);
								$newstring6 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring5);
								$newstring7 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring6);
								$newstring8 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring7);
								$newstring9 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring8);
								$newstring10 = str_replace("#CITY#", "".$result[0]['city']."",$newstring9);
								$newstring11 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring10);
								$newstring12 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring11);
								$newstring13 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring12);
								$newstring14 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring13);
								$newstring15 = str_replace("#MODE#", "".$mode."",$newstring14);
								$newstring16 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring15);
								$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
								if(count($elern_msg_string) > 0)
								{
									foreach($elern_msg_string as $row)
									{
										$arr_elern_msg_string[]=$row['exam_code'];
									}
									if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
									{
										$newstring17 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring16);		
									}
									else
									{
										$newstring17 = str_replace("#E-MSG#", '',$newstring16);		
									}
								}
								else
								{
									$newstring17 = str_replace("#E-MSG#", '',$newstring16);
								}
								$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring17);
								$info_arr=array('to'=>$result[0]['email'],
								'from'=>$emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],
								'message'=>$final_str
								);
								//echo $final_str; exit;
								//get invoice	
								$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
								//echo $this->db->last_query();exit;
								########### generate invoice ###########
								if(count($getinvoice_number) > 0)
								{
									$invoiceNumber =generate_elearning_exam_invoice_number($getinvoice_number[0]['invoice_id']);
									if($invoiceNumber)
									{
										$invoiceNumber=$this->config->item('El_exam_invoice_no_prefix').$invoiceNumber;
									}
									$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
									$this->db->where('pay_txn_id',$payment_info[0]['id']);
									$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
									$attachpath=genarate_elearning_exam_invoice($getinvoice_number[0]['invoice_id']);
								}	
								if($attachpath!='')
								{		
									$files=array($attachpath);
									$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
									$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
									$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
									$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
									$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
									//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
									//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
									$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

									$this->Emailsending->mailsend_attch($info_arr,$files);
									//$this->Emailsending->mailsend($info_arr);
								}
							}
							else
							{
								$log_title ="S2S Update fail:".$get_user_regnum[0]['member_regnumber'];
								$log_message = serialize($update_data);
								$rId = $MerchantOrderNo;
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);	
							}
						}
					}
					else if($payment_status==0)
					{
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code');
						if($get_user_regnum[0]['status']==2)
						{
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399','bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'S2S');
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
							//Query to get Payment details	
							$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
							if($get_user_regnum[0]['exam_code']!='990')
							{
								//Query to get user details
								$this->db->join('state_master','state_master.state_code=member_registration.state');
								$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name');
								//Query to get exam details	
								$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
								$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
								$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
								$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
								$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
								//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
								$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
								$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
								$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
								$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
								$this->sms_template_id = 'Jw6bOIQGg';
								$newstring1 = str_replace("#application_num#", "".$get_user_regnum[0]['member_regnumber']."",  $emailerstr[0]['emailer_text']);
								$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
								$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
								$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
								$info_arr=array(	'to'=>$result[0]['email'],
								'from'=>$emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],
								'message'=>$final_str
								);
								//send sms to Ordinary Member
								$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
								$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
								$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
								// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
								//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
								$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

								$this->Emailsending->mailsend($info_arr);
							}
						}
					}				
				}
				else if($responsedata[6] == "IIBF_ELR")
				{
					sleep(8);
					$MerchantOrderNo = $responsedata[0]; 
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					$attachpath=$invoiceNumber='';
					switch ($responsedata[2])
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
					if($payment_status==1)
					{
						// Handle transaction success case 
						$exam_period_date='';
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
						if($get_user_regnum[0]['status']==2)
						{
							######### payment Transaction ############
							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
							$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
							if($this->db->affected_rows())
							{
								$exam_code=$get_user_regnum[0]['exam_code'];
								$reg_id=$get_user_regnum[0]['member_regnumber'];
								//$applicationNo = generate_nm_reg_num();
								$applicationNo = generate_NM_memreg($reg_id);
								######### payment Transaction ############
								$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								######### update application number to Registration table#########
								$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
								$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
								######### update application number to member exam#########
								$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
								$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
								//Query to get exam details	
								$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
								$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
								$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
								$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
								$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.examination_date');
								if($exam_info[0]['exam_mode']=='ON')
								{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
								{$mode='Offline';}
								else{$mode='';}
								if($exam_info[0]['examination_date']!='0000-00-00')
								{
									$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
								}
								else
								{
									//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
									$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
									$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
								}
								//Query to get Medium	
								$this->db->where('exam_code',$exam_code);
								$this->db->where('exam_period',$exam_info[0]['exam_period']);
								$this->db->where('medium_code',$exam_info[0]['exam_medium']);
								$this->db->where('medium_delete','0');
								$medium=$this->master_model->getRecords('medium_master','','medium_description');
								//Query to get Payment details	
								$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNo),'transaction_no,date,amount,id');
								//Query to get user details
								$this->db->join('state_master','state_master.state_code=member_registration.state');
								$result=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,usrpassword');	
								$upd_files = array();
								$photo_file = 'p_'.$applicationNo.'.jpg';
								$sign_file = 's_'.$applicationNo.'.jpg';
								$proof_file = 'pr_'.$applicationNo.'.jpg';
								if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
								{	$upd_files['scannedphoto'] = $photo_file;	}
								if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
								{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
								if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
								{	$upd_files['idproofphoto'] = $proof_file;	}
								if(count($upd_files)>0)
								{
									$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
								}
								include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
								$key = $this->config->item('pass_key');
								$aes = new CryptAES();
								$aes->set_key(base64_decode($key));
								$aes->require_pkcs5();
								$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
								$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
								$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'ELnon_member_apply_exam_transaction_success'));
								$this->sms_template_id = 'LSy_cIwGg';
								$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
								$newstring2 = str_replace("#REG_NUM#", "".$applicationNo."",$newstring1);
								$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
								$newstring4 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring3);
								$newstring5 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring4);
								$newstring6 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring5);
								$newstring7 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring6);
								$newstring8 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring7);
								$newstring9 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring8);
								$newstring10 = str_replace("#CITY#", "".$result[0]['city']."",$newstring9);
								$newstring11 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring10);
								$newstring12 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring11);
								$newstring13 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring12);
								$newstring14 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring13);
								$newstring15 = str_replace("#MODE#", "".$mode."",$newstring14);
								$newstring16 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring15);
								$newstring17 = str_replace("#PASS#", "".$decpass."",$newstring16);
								$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
								if(count($elern_msg_string) > 0)
								{
									foreach($elern_msg_string as $row)
									{
										$arr_elern_msg_string[]=$row['exam_code'];
									}
									if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
									{
										$newstring18 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring17);		
									}
									else
									{
										$newstring18 = str_replace("#E-MSG#", '',$newstring17);		
									}
								}
								else
								{
									$newstring18 = str_replace("#E-MSG#", '',$newstring17);
								}
								$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring18);
								$info_arr=array('to'=>$result[0]['email'],
								'from'=>$emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'].' '.$applicationNo,
								'message'=>$final_str
								);
								//get invoice	
								$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
								//echo $this->db->last_query();exit;
								if(count($getinvoice_number) > 0)
								{
									$invoiceNumber =generate_elearning_exam_invoice_number($getinvoice_number[0]['invoice_id']);
									if($invoiceNumber)
									{
										$invoiceNumber=$this->config->item('El_exam_invoice_no_prefix').$invoiceNumber;
									}
									$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'member_no'=>$applicationNo,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
									$this->db->where('pay_txn_id',$payment_info[0]['id']);
									$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
									$attachpath=genarate_elearning_exam_invoice($getinvoice_number[0]['invoice_id']);
								}	
								if($attachpath!='')
								{
									//send sms		
									$files=array($attachpath);			
									$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);	
									$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
									$sms_newstring1 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring);
									$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring1);
									//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);						
									//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
									$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

									$this->Emailsending->mailsend_attch($info_arr,$files);
									//$this->Emailsending->mailsend($info_arr);
								}
							}
							else
							{
								$log_title ="S2S Update fail:".$get_user_regnum[0]['member_regnumber'];
								$log_message = serialize($update_data);
								$rId = $MerchantOrderNo;
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);	
							}
						}
					}
					else if($payment_status==0)
					{
						// Handle transaction fail case 
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
						if($get_user_regnum[0]['status']==2)
						{
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' =>'0399','bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'S2S');
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							//Query to get Payment details	
							$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id');
							$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
							$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
							$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
							$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
							$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
							$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.regnumber'=>$payment_info[0]['member_regnumber']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
							//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
							$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
							$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
							$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
							$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
							$this->sms_template_id = 'Jw6bOIQGg';
							$newstring1 = str_replace("#application_num#", "",  $emailerstr[0]['emailer_text']);
							$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
							$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
							$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
							$info_arr=array(	'to'=>$result[0]['email'],
							'from'=>$emailerstr[0]['from'],
							'subject'=>$emailerstr[0]['subject'],
							'message'=>$final_str
							);
							// send SMS
							$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
							$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
							$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
							//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
							//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
							$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

							$this->Emailsending->mailsend($info_arr);
						}
					}
				}		
				else if($responsedata[6] == "iibfcpd")
				{
					sleep(8);
					$MerchantOrderNo = $responsedata[0]; 
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					$attachpath=$invoiceNumber=$admitcard_pdf='';
					switch ($responsedata[2])
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
					if($payment_status==1)
					{	
						$exam_period_date='';
						//Handle transaction success case
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
						if($get_user_regnum[0]['status']==2)
						{
							######### payment Transaction ############
							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
							$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
							if($this->db->affected_rows())
							{
								if(count($get_user_regnum) > 0)
								{
									$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'isactive'=>'1'),'regnumber,usrpassword,email');
								}
								///chiatli's code
								$created_on = date('Y-m-d H:i:s');
								$validate_upto  = date('Y-m-d H:i:s', strtotime('+2 years', strtotime($created_on)));
								$update_data = array('pay_status'=>'1','validate_upto'=>$validate_upto);
								$this->master_model->updateRecord('cpd_registration',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'cpd'));
								$this->sms_template_id = 'NA';
								if(count($emailerstr) > 0 && (count($get_user_regnum) > 0))
								{
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum[0]['id']));
									if(count($getinvoice_number) > 0)
									{ 
										$invoiceNumber = generate_cpd_invoice_number($getinvoice_number[0]['invoice_id']);
										if($invoiceNumber)
										{
											$invoiceNumber=$this->config->item('CPD_invoice_no_prefix').$invoiceNumber;
										}
										$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
										$this->db->where('pay_txn_id',$get_user_regnum[0]['id']);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										$attachpath=genarate_cpd_invoice($getinvoice_number[0]['invoice_id']);
									}
									if($attachpath!='')
									{	 
										$final_str = $emailerstr[0]['emailer_text'];
										$info_arr = array('to'=>$user_info[0]['email'],'from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'].' '.$user_info[0]['regnumber'],'message'=>$final_str);
										if($this->Emailsending->mailsend_attch_cpd($info_arr,$attachpath))
										{
											redirect(base_url().'Cpd/acknowledge/'.base64_encode($MerchantOrderNo));
										}
										else
										{
											redirect(base_url('Cpd/acknowledge/'));
										}
									}
									else
									{
										redirect(base_url('Cpd/acknowledge/'));
									}	
									//Manage Log
									$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=S2S"; 
									$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
									//$this->db->last_query();exit;
									//$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
								}
							}
							else if($payment_status==0)
							{
								$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code');
								if($get_user_regnum[0]['status']==2)
								{
									$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399','bankcode' => $responsedata[8],'paymode' =>  $responsedata[5],'callback'=>'S2S');
									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								}
							}				
						}
						// add payment responce in log
						if($responsedata[6] == "iibfdra")
						{
							$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=S2S";
							$this->log_model->logdratransaction("sbiepay", $pg_response, $responsedata[2]);
						}
						else if($responsedata[6] == "iibfamp")
						{
							$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=S2S";
							$this->log_model->logamptransaction("sbiepay", $pg_response, $responsedata[2]);
						}
						else
						{
							$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=S2S";
							$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
						}
					}
					else
					{
						die("Please try again...");
					}
					exit;
				}
				else if($responsedata[6] == "IIBFELS" && $responsedata[2] == 'SUCCESS')
				{
					sleep(8);
					$MerchantOrderNo = $responsedata[0]; 
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					switch ($responsedata[2])
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
					if($payment_status==1)
					{
						//START : CODE ADDED BY SAGAR ON 03-09-2021 : GENARATE REGNUMBER FOR NEWLY ADDED MEMBER ONLY WHEN PAYMENT IS SUCCESS
						$get_regid = $this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'id, ref_id');
						if(count($get_regid) > 0)
						{
							$regid = $get_regid[0]['ref_id'];
							$chk_member_no = $this->master_model->getRecords('spm_elearning_registration',array('regid'=>$regid, 'regnumber'=>''),'regid, regnumber');
							if(count($chk_member_no) > 0)
							{
								## Generate regnumber for newly added member. Note: This is for only E-learning module
								$regnumber = generate_eLearning_memreg($regid);
								//UPDATE regnumber IN spm_elearning_registration
								$up_data1['registrationtype'] = 1; 
								$up_data1['isactive'] = '1'; 
								$up_data1['regnumber'] = $regnumber; 
								$this->master_model->updateRecord('spm_elearning_registration',$up_data1,array('regid'=>$regid, 'regnumber'=>''));
								$log_title ="E-learning member UPDATE array spm_elearning_registration :".$regid;
								$log_message = serialize($up_data1);
								storedUserActivity($log_title, $log_message, $regid, $regnumber);
								//UPDATE regnumber IN spm_elearning_member_subjects
								$up_data2['regnumber'] = $regnumber; 
								$this->master_model->updateRecord('spm_elearning_member_subjects',$up_data2,array('regid'=>$regid, 'regnumber'=>''));
								$log_title ="E-learning member UPDATE array spm_elearning_member_subjects :".$regid;
								$log_message = serialize($up_data2);
								storedUserActivity($log_title, $log_message, $regid, $regnumber);
								//UPDATE regnumber IN payment_transaction
								$up_data3['member_regnumber'] = $regnumber; 
								$this->master_model->updateRecord('payment_transaction',$up_data3,array('ref_id'=>$regid, 'member_regnumber'=>'', 'pay_type'=>'20'));
								$log_title ="E-learning member UPDATE array payment_transaction :".$regid;
								$log_message = serialize($up_data3);
								storedUserActivity($log_title, $log_message, $regid, $regnumber);
								//UPDATE regnumber IN exam_invoice
								$up_data4['member_no'] = $regnumber; 
								$this->master_model->updateRecord('exam_invoice',$up_data4,array('receipt_no'=>$MerchantOrderNo, 'member_no'=>''));
								$log_title ="E-learning member UPDATE array exam_invoice :".$MerchantOrderNo;
								$log_message = serialize($up_data4);
								storedUserActivity($log_title, $log_message, $MerchantOrderNo, $regnumber);
							}
							else
							{
								//UPDATE status IN spm_elearning_registration
								$up_data1['isactive'] = '1'; 
								$this->master_model->updateRecord('spm_elearning_registration',$up_data1,array('regid'=>$regid));
							}
						}
						//END : CODE ADDED BY SAGAR ON 03-09-2021 : GENARATE REGNUMBER FOR NEWLY ADDED MEMBER ONLY WHEN PAYMENT IS SUCCESS        
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'id, member_regnumber, ref_id, status');
						//check user payment status is updated by b2b or not
						if($get_user_regnum[0]['status']==2)
						{
							######### payment Transaction ############
							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[16],'callback'=>'S2S');
							$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
							$last_qry_payment_transaction = $this->db->last_query();
							$log_title = "E-learning payment_transaction s2s update : receipt_no = '".$MerchantOrderNo."'";
							$log_message =  $last_qry_payment_transaction;
							$rId = $get_user_regnum[0]['member_regnumber'];
							$regNo = $get_user_regnum[0]['member_regnumber'];
							storedUserActivity($log_title, $log_message, $rId, $regNo);
							if($this->db->affected_rows())	
							{
							  $el_sub_id_arr = array();
							  $get_el_sub_ids = $this->master_model->getRecords('spm_elearning_member_subjects',array('pt_id'=>$get_user_regnum[0]['id']),'el_sub_id');
							  if(count($get_el_sub_ids) > 0)
							  {
									foreach($get_el_sub_ids as $get_el_sub_res)
									{
										$el_sub_id_arr[] = $get_el_sub_res['el_sub_id'];
									}
								}
							  if(count($el_sub_id_arr) > 0)
							  {
									foreach($el_sub_id_arr as $el_sub_id_res)
									{
										$this->master_model->updateRecord('spm_elearning_member_subjects',array('pt_id'=>$get_user_regnum[0]['id'], 'transaction_no'=>$transaction_no, 'receipt_no'=>$MerchantOrderNo, 'status'=>1, 'updated_on'=>date('Y-m-d H:i:s')),array('el_sub_id'=>$el_sub_id_res));
									}
								}
							  //Query to get Payment details	
							  $payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'exam_code, transaction_no, date, amount, id, status');
							  //get invoice	
							  $getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
							  if(count($getinvoice_number) > 0)
							  {
									$invoiceNumber = generate_el_invoice_number($getinvoice_number[0]['invoice_id']);
									if($invoiceNumber)
									{
										/* $cyear = date("y");
										$nyear = $cyear+1;
										$invoiceNumber='EL/'.$cyear.'-'.$nyear.'/'.$invoiceNumber; */

                    //START : THIS CODE WAS UPDATED BY SAGAR M ON 2024-04-16. EARLIER THE INVOICE YEAR FOLLOW THE CALENDER YEAR INSTEAD OF FINANCIAL YEAR
                    if(date("Y-m-d") >= date("Y").'-04-01') { $cyear = date("y"); } else { $cyear = date('y') - 1; }
                    $nyear = $cyear + 1;
                    if($cyear.'-'.$nyear == '24-25' && $invoiceNumber >= 6860) { $invoiceNumber = $invoiceNumber + 3056; }
                    $invoiceNumber = 'EL/' . $cyear . '-' . $nyear . '/' . str_pad($invoiceNumber,6,0,STR_PAD_LEFT);
                    //END : THIS CODE WAS UPDATED BY SAGAR M ON 2024-04-16. EARLIER THE INVOICE YEAR FOLLOW THE CALENDER YEAR INSTEAD OF FINANCIAL YEAR
									}
									$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
									$this->db->where('pay_txn_id',$payment_info[0]['id']);
									$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
									$attachpath=genarate_el_invoice($getinvoice_number[0]['invoice_id']);
									$last_qry_exam_invoice = $this->db->last_query();
									$log_title = "E-learning exam_invoice s2s update : receipt_no = '".$MerchantOrderNo."'";
									$log_message = $last_qry_exam_invoice;
									$rId = $get_user_regnum[0]['member_regnumber'];
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);
								}	
							  $selected_sub_data = $this->master_model->getRecords('spm_elearning_member_subjects ms',array('ms.status'=>'1', 'ms.pt_id'=>$payment_info[0]['id']), 'ms.subject_code, ms.subject_description');
							  $selected_sub_disp_str = '';
							  if(count($selected_sub_data) > 0)
							  {
									$sr_no = 1;
									foreach($selected_sub_data as $selected_sub_res)
									{
										$selected_sub_disp_str .= '<span style="display: block;margin: 2px 0 5px 0px;">'.$sr_no.'. '.$selected_sub_res['subject_description'].'</span>';
										$sr_no++;
									}								
								}
							  $email_payment_status = '';
							  if($payment_info[0]['status'] == 0) { $email_payment_status = 'Fail'; }
							  else if($payment_info[0]['status'] == 1) { $email_payment_status = 'Success'; }
							  else if($payment_info[0]['status'] == 2) { $email_payment_status = 'Pending'; }
							  else if($payment_info[0]['status'] == 3) { $email_payment_status = 'Refund'; }
							  //Query to get Exam Name	
							  $email_exam_name = '';
							  $this->db->limit(1);
							  $this->db->order_by('exam_id', 'DESC');
							  $exam_data = $this->master_model->getRecords('spm_elearning_exam_master',array('exam_code'=>$payment_info[0]['exam_code']),'exam_code, exam_name');
							  if(count($exam_data) > 0)
							  {
									$email_exam_name = $exam_data[0]['exam_name'];
								}
								$Candidate = $this->master_model->getRecords('spm_elearning_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']));
							  $mail_content = '
								<table cellspacing="5" cellpadding="0" align="center" style="border: 1px solid #ddd; font-family: Arial,Helvetica,sans-serif; font-size: 14px; margin: 0; max-width: 800px; width: 100%; background:#FFFFCC" border="1">
								<thead>
								<tr>
								<td colspan="2" align="center"><h2 style="line-height:24px; margin:10px 0;">Transaction Details</h2></td>
								</tr>
								</thead>
								<tbody>
								<tr>
								<td colspan="2" style="padding:10px;" width="100%">
								<p>Dear '.ucfirst($Candidate[0]['firstname']).'</p>
								<p>We acknowledge with thanks the receipt of the payment for Enrolment in E-Learning as per the details given below.</p>
								</td>
								</tr>
								<tr>
								<td style="padding:5px 10px;" width="35%"><p><strong>Registration / Membership No : </strong></p></td>
								<td style="padding:5px 10px;" width="64%">'.$get_user_regnum[0]['member_regnumber'].'</td>
								</tr>
								<tr>
								<td style="padding:5px 10px;;" width="35%"><p><strong>Member Name : </strong></p></td>
								<td style="padding:5px 10px;" width="64%">'.ucfirst($Candidate[0]['firstname']).' '.ucfirst($Candidate[0]['lastname']).'</td>
								</tr>
								<tr>
								<td style="padding:5px 10px;" width="35%"><p><strong>Email Id : </strong></p></td>
								<td style="padding:5px 10px;" width="64%"><a href="mailto:'.$Candidate[0]['email'].'">'.$Candidate[0]['email'].'</a></td>
								</tr>
								<tr>
								<td style="padding:5px 10px;" width="35%"><p><strong>Transaction ID:</strong></p></td>
								<td style="padding:5px 10px;" width="64%">'.$payment_info[0]['transaction_no'].'</td>
								</tr>
								<tr>
								<td style="padding:5px 10px;" width="35%"><p><strong>Exam Name: </strong></p></td>
								<td style="padding:5px 10px;" width="64%"><p>'.$email_exam_name.'</p></td>
								</tr>
								<tr>
								<td style="padding:5px 10px;" width="35%"><p><strong>E-Learning Subject/s: </strong></p></td>
								<td style="padding:5px 10px;" width="64%"><p>'.$selected_sub_disp_str.'</p></td>
								</tr>
								<tr>
								<td style="padding:5px 10px;" width="35%"><p><strong>Transaction Status: </strong></p></td>
								<td style="padding:5px 10px;" width="64%"><p>'.$email_payment_status.'</p></td>
								</tr>
								<tr>
								<td style="padding:5px 10px;" width="35%"><p><strong>Amount : </strong></p></td>
								<td style="padding:5px 10px;" width="64%">'.$payment_info[0]['amount'].'</td>
								</tr>
								<tr>
								<td style="padding:5px 10px;" width="35%"><p><strong>Transaction Date :</strong> </p></td>
								<td style="padding:5px 10px;" width="64%">'.date('Y-m-d H:i:s A',strtotime($payment_info[0]['date'])).'</td>
								</tr>
								</tbody>
								</table>';
							  $sender_email = $this->master_model->getRecords('spm_elearning_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'isactive'=>"1"),"email", "", "", "1");
							  $info_arr['to'] = $sender_email[0]['email']; 
							  $info_arr['from'] = "logs@iibf.esdsconnect.com";
							  $info_arr['cc'] = "iibfdevp@esds.co.in";
							  $info_arr['subject'] = "E-Learning Payment Acknowledgment";
							  $info_arr['message'] = $mail_content;
							  if($attachpath!='')
							  {		
									$files=array($attachpath);
									$this->Emailsending->mailsend_attch($info_arr,$files);
								}
							  //Manage Log
							  $pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
							  $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
							}
						}	
					}
				}/* coded added for Elearning separate module end*/
				else if($responsedata[6] == "IIBF_INST_SUB")
				{
					$MerchantOrderNo = $responsedata[0]; 
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					
					switch ($responsedata[2])
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
					
					if($payment_status==1)
					{
						$payment_data = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status,date');					
						
						if($payment_data[0]['status'] == 2)//IF payment status is pending
						{
							// ######## payment Transaction ############
							$update_data['transaction_no'] = $transaction_no;
							$update_data['status'] = 1;
							$update_data['transaction_details'] = $responsedata[2]." - ".$responsedata[7];
							$update_data['auth_code'] = '0300';
							$update_data['bankcode'] = $responsedata[8];
							$update_data['paymode'] = $responsedata[16];
							$update_data['callback'] = 'S2S';						
							$update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));
							
							// ######## Insert Log ############
							$query_update_payment_tra = $this->db->last_query();
							$log_title = "S2S query_update_payment_tra :" . $query_update_payment_tra;
							$log_message = serialize($update_data);
							$rId = $payment_data[0]['member_regnumber'];
							$regNo = $payment_data[0]['member_regnumber'];
							storedUserActivity($log_title, $log_message, $rId, $regNo);				
						}
						
						// Query to get Payment details
						//$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $payment_data[0]['member_regnumber']), 'member_regnumber, transaction_no, date, amount, id');
						
						//$this->send_mail_common('success', $payment_info[0]['member_regnumber'], $payment_info[0]['description'], $payment_info[0]['amount'], $payment_info[0]['transaction_no'], $payment_info[0]['date']);
						
						// Manage Log
						$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
						$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
					}
					else if($payment_status==0)
					{
						$update_data = array(
						'transaction_no' => $transaction_no,
						'status' => 0,
						'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
						'auth_code' => '0399',
						'bankcode' => $responsedata[8],
						'paymode' => $responsedata[16],
						'callback' => 'S2S'
						);					
						$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
						
						// Query to get Payment details
						//$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $get_user_regnum[0]['member_regnumber']) , 'member_regnumber, transaction_no,date,amount, ref_id');
						
						//$this->send_mail_common('fail', $payment_info[0]['member_regnumber'], $payment_info[0]['description'], $payment_info[0]['amount'], $payment_info[0]['transaction_no'], $payment_info[0]['date']);
						
						// Manage Log
						$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
						$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
					}
				} 
				
				// add payment responce in log
				if($responsedata[6] == "iibfdra")
				{
					$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=S2S";
					$this->log_model->logdratransaction("sbiepay", $pg_response, $responsedata[2]);
				}
				else if($responsedata[6] == "iibfamp")
				{
					$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=S2S";
					$this->log_model->logamptransaction("sbiepay", $pg_response, $responsedata[2]);
				}
				else
				{
					$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=S2S";
					$this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
				}
			}
			else
			{
				die("Please try again...");
			}
			exit;
		}
		
		## Billdesk Integration new functions added by Pratibha & Sagar on 25 Mar 2022
		## Billdesk S2S call function
		//add this function in payment.php
		public function billdesk_callback() //billdesk_callback : This function is provided to billdesk team for s2s callback
		{
			##Fetch Billdesk response encrypted string
			$insert_info['headers'] = 'NA';
			$insert_info['request'] = 'NA';
			$insert_info['jwsString'] = 'NA';
			$insert_info['bd_traceid'] = 'NA';
			$insert_info['bd_timestamp'] = 'NA';
			$insert_info['result'] = 'NA';
			$insert_info['billdesk_pg_res'] = json_encode(file_get_contents( 'php://input'));
			$insert_info['function_name'] = 'billdesk_callback';
			$insert_info['created_on'] = date('Y-m-d H:i:s');
			$this->master_model->insertRecord('billdesk_logs', $insert_info);
			$encData = file_get_contents( 'php://input');
			if (isset($encData)) 
			{	
				$encData = file_get_contents( 'php://input');
				$bd_response = $this->billdesk_pg_model->verify_res($encData );
				$date_for_log = date('Y-m-d H:i:s');
				$responsedata = $bd_response['payload']; /* echo '<pre>'; print_r($responsedata); echo '</pre>'; exit; */
				$MerchantOrderNo = $responsedata['orderid'];
				$transaction_no = $responsedata['transactionid'];
				$transaction_error_type = $responsedata['transaction_error_type'];
				$transaction_error_desc = $responsedata['transaction_error_desc'];
				$bankid = $responsedata['bankid'];
				$txn_process_type = $responsedata['txn_process_type'];
				$merchIdVal = $responsedata['mercid'];
				$Bank_Code = $responsedata['bankid'];
				$additional_info = $responsedata['additional_info'];
				$auth_status = $responsedata['auth_status'];
				$resp_array['receipt_no']	= $MerchantOrderNo;
				$resp_array['txn_status'] = $transaction_error_type;
				$resp_array['txn_data'] = $encData;
				$resp_array['response_data'] = json_encode($responsedata);
				$resp_array['resp_date'] = date('Y-m-d H:i:s');
				$this->master_model->insertRecord('payment_s2s_log', $resp_array);
				## It is ^ in SBI but billdesk dont allow this character so we used -
				//$cust = explode('-',$additional_info['additional_info1']);
				$pay_type = $responsedata['additional_info']['additional_info2'];
				
				//############################# check for late (90 min)response s2s and refund
				$this->db->order_by('id','DESC');
				$this->db->limit(1); 
				$get_receipt=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'gateway'=>'billdesk'),'pg_flag,receipt_no,member_regnumber,date,status');

        if(count($get_receipt) == 0)
        {
          $get_receipt=$this->master_model->getRecords('iibfbcbf_payment_transaction',array('receipt_no'=>$MerchantOrderNo,'gateway'=>'2'),'pg_flag,receipt_no,date,status');
        }
				
        if(count($get_receipt) == 0)
        {
          $get_receipt=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo,'gateway'=>'billdesk'),'pg_flag,receipt_no,date,status');
        }
				
				$val = 0;
				$current_date = date('Y-m-d H:i:s'); // 
				$today_date = date('Y-m-d'); // FOR - SQL
				$txn_date = $get_receipt[0]['date']; 
				$pay_status = $get_receipt[0]['status']; 
				$order_id = $get_receipt[0]['receipt_no']; 
				$start_date = new DateTime($txn_date, new DateTimeZone('Asia/Kolkata'));
				$end_date = new DateTime($current_date, new DateTimeZone('Asia/Kolkata'));
				$interval = $start_date->diff($end_date);
				$hours = $interval->format('%h'); 
				$minutes = $interval->format('%i');
				$minutes_diff = ($hours * 60 + $minutes);
				if($minutes_diff>120)
				{
					if($auth_status == '0300')
					{
						//$refund_insert_array=array('receipt_no'=>$MerchantOrderNo,'response'=>$encData);
						//$inser_id = $this->master_model->insertRecord('S2S_direcrt_refund',$refund_insert_array);
					}
					//exit;	
				}//############################# END of check for late (90 min)response s2s and refund				
				//$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=S2S";
				//$this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);
				// Examination
				$get_pg_flag = $this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'pg_flag');
        if(count($get_pg_flag) == 0)
        {
          $get_pg_flag = $this->master_model->getRecords('iibfbcbf_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'pg_flag');
        }
				
        if(count($get_pg_flag) == 0)
        {
          $get_pg_flag = $this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'pg_flag');
        }
				
        if(count($get_pg_flag ) > 0)
				{
					$pay_type = $get_pg_flag[0]['pg_flag'];
				}				
				
						
				if($pay_type=='iibfexam')
				{
					if($pay_type != 'iibfdra')	// Not DRA Exam Application
					{
						$get_pg_flag = $this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'pg_flag');
						$pay_type = $get_pg_flag[0]['pg_flag'];
					}
					else
					{
						$pay_type = $pay_type;
					}
				}
				
				// Registration
				if($pay_type=='iibfregn')
				{
					if($pay_type !='iibfregn')	// Not New Member registration
					{
						$get_pg_flag=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'pg_flag');
						$pay_type = $get_pg_flag[0]['pg_flag'];
					}
				}
				
				$payment_status = 2;
				$attachpath=$invoiceNumber='';
				switch ($auth_status)
				{
					case "0300": $payment_status = 1; break; //success
					case "0399": $payment_status = 0; break; // failed
					case "0002": $payment_status = 2; break; // pending
				}
				
				if($payment_status==1)
				{
					if($pay_type == "iibfregn")
					{
						sleep(12);
						$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status,id');
						
						//check user payment status is updated by S2S or not
						if($get_user_regnum_info[0]['status']==2)
						{
							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
							$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
							if($this->db->affected_rows())
							{		
								$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status,id');
								if($get_payment_status[0]['status']==1)
								{
									$reg_id=$get_user_regnum_info[0]['ref_id'];
									//$applicationNo = generate_mem_reg_num();
									$applicationNo =generate_O_memreg($reg_id);
									####### update member number #########
									$update_data = array('member_regnumber' => $applicationNo);
									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
									/*$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber');*/
									if(count($get_user_regnum_info) > 0)
									{
										$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
										$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
										$user_info=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,mobile');
										########get Old image Name############
										$log_title ="Ordinory member OLD Image S2S :".$reg_id;
										$log_message = serialize($user_info);
										$rId = $reg_id;
										$regNo = $reg_id;
										storedUserActivity($log_title, $log_message, $rId, $regNo);
										$upd_files = array();
										$photo_file = 'p_'.$applicationNo.'.jpg';
										$sign_file = 's_'.$applicationNo.'.jpg';
										$proof_file = 'pr_'.$applicationNo.'.jpg';
										if(@ rename("./uploads/photograph/".$user_info[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
										{	$upd_files['scannedphoto'] = $photo_file;	}
										if(@ rename("./uploads/scansignature/".$user_info[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
										{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
										if(@ rename("./uploads/idproof/".$user_info[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
										{	$upd_files['idproofphoto'] = $proof_file;	}
										if(count($upd_files)>0)
										{
											$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
											$log_title ="Ordinory member PIC update S2S :".$reg_id;
											$log_message = serialize($upd_files);
											$rId = $reg_id;
											$regNo = $reg_id;
											storedUserActivity($log_title, $log_message, $rId, $regNo);
										}
										else
										{
											$upd_files['scannedphoto'] = $photo_file;
											$upd_files['scannedsignaturephoto'] = $sign_file;	
											$upd_files['idproofphoto'] = $proof_file;
											$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
											$log_title ="Member MANUAL PICS Update S2S :".$reg_id;
											$log_message = serialize($upd_files);
											$rId = $reg_id;
											$regNo = $reg_id;
											storedUserActivity($log_title, $log_message, $rId, $regNo);	
										}
									}
									//email to user
									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_register_email'));
									$this->sms_template_id = 'DPDoOIwMR';
									if(count($emailerstr) > 0)
									{
										include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
										$key = $this->config->item('pass_key');
										$aes = new CryptAES();
										$aes->set_key(base64_decode($key));
										$aes->require_pkcs5();
										//$encPass = $aes->encrypt(trim($user_info[0]['usrpassword']));
										$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
										//$decpass = $aes->decrypt($user_info[0]['usrpassword']);
										$newstring = str_replace("#application_num#", "".$applicationNo."",  $emailerstr[0]['emailer_text']);
										$final_str= str_replace("#password#", "".$decpass."",  $newstring);
										$info_arr=array('to'=>$user_info[0]['email'],
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'].' '.$applicationNo,
										'message'=>$final_str
										);
										//set invoice
										$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum_info[0]['id']));
										//echo $this->db->last_query();exit;
										if(count($getinvoice_number) > 0)
										{
											$invoiceNumber = generate_registration_invoice_number($getinvoice_number[0]['invoice_id']);
											if($invoiceNumber)
											{
												$invoiceNumber=$this->config->item('mem_invoice_no_prefix').$invoiceNumber;
												//}
											}
											$update_data = array('invoice_no' => $invoiceNumber,'member_no'=>$applicationNo,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
											$this->db->where('pay_txn_id',$get_user_regnum_info[0]['id']);
											$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
											$attachpath=genarate_reg_invoice($getinvoice_number[0]['invoice_id']);
										}	
										if($attachpath!='')
										{	
											$sms_newstring = str_replace("#application_num#", "".$applicationNo."",  $emailerstr[0]['sms_text']);
											$sms_final_str= str_replace("#password#", "".$decpass."",  $sms_newstring);
											//$this->master_model->send_sms($user_info[0]['mobile'],$sms_final_str);
											//$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']),$sms_final_str,$this->sms_template_id);
											$this->master_model->send_sms_common_all($user_info[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

											//if($this->Emailsending->mailsend($info_arr))
											if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
											{
												//$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
												//redirect(base_url('register/acknowledge/'));
											}
											else
											{
												//echo 'Error while sending email';
												//$this->session->set_flashdata('error','Error while sending email !!');
												//redirect(base_url('register/preview/'));
											}
										}
										else
										{
											//$this->session->set_flashdata('error','Error while sending email !!');
											//redirect(base_url('register/preview/'));
										}
									}
								}
							}
						}
					}
					else if($pay_type == "iibfdup")
					{
						sleep(12);
						// Handle transaction sucess case 
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,id');
						if($get_user_regnum[0]['status']==2)
						{
							if(count($get_user_regnum) > 0)
							{
								$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
							}
							$update_data = array('pay_status' => '1');
							$this->master_model->updateRecord('duplicate_icard',$update_data,array('did'=>$get_user_regnum[0]['ref_id']));
							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,id');
							if($get_payment_status[0]['status']==1)
							{
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'duplicate_id'));
								$this->sms_template_id = 'NA';
								if(count($emailerstr) > 0 && (count($get_user_regnum) > 0))
								{
									//Query to get user details
									$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'namesub,firstname,middlename,lastname,email');
									$username=$user_info[0]['namesub'].' '.$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									$newstring1 = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);
									$newstring2 = str_replace("#MEM_NO#", "".$get_user_regnum[0]['member_regnumber']."", $newstring1 );
									$final_str= str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring2);
									$info_arr = array('to'=>$user_info[0]['email'],'from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],'message'=>$final_str);
									//genertate invoice and email send with invoice attach 8-7-2017					
									//get invoice	
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum[0]['id']));
									if(count($getinvoice_number) > 0)
									{ 
										$invoiceNumber = generate_duplicate_id_invoice_number($getinvoice_number[0]['invoice_id']);
										if($invoiceNumber)
										{
											$invoiceNumber=$this->config->item('Dup_Id_invoice_no_prefix').$invoiceNumber;
										}
										//	}
										$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
										$this->db->where('pay_txn_id',$get_user_regnum[0]['id']);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										$attachpath=genarate_duplicateicard_invoice($getinvoice_number[0]['invoice_id']);
									}
									if($attachpath!='')
									{
										$pay_status=array();
										$regnumber = $get_user_regnum[0]['member_regnumber'];
										$where1 = array('member_number'=> $regnumber);
										$pay_status= $this->master_model->updateRecord('member_idcard_cnt',array('card_cnt'=>'0'),$where1);
										/* User Log Activities : Pooja */
										$uerlog = $this->master_model->getRecords('member_registration',array('regnumber'=>$regnumber,'isactive'=>'1'),'regid');
										$user_info = $this->master_model->getRecords('member_idcard_cnt',array('member_number'=>$regnumber));
										$log_title ="Apply for Duplicate Id card : ".$uerlog[0]['regid'];
										$log_message = serialize($user_info);
										$rId =$uerlog[0]['regid'];
										$regNo = $regnumber;
										storedUserActivity($log_title, $log_message, $rId, $regNo);
										$this->Emailsending->mailsend($info_arr);
									}
								}
							}
						}
					}
					else if($pay_type == "iibfdupcer")
					{
						sleep(12);
						// Handle transaction sucess case 
						$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,id');
						if($get_user_regnum[0]['status']==2)
						{
							if(count($get_user_regnum) > 0)
							{
								$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'isactive'=>'1'),'regnumber,usrpassword,email');
								//if member is DRA member
								if(empty($user_info))
								{
									$user_info = $this->master_model->getRecords('dra_members',array('regnumber'=>$get_user_regnum[0]['member_regnumber']));
								}
							}
							$update_data = array('pay_status' => '1');
							$this->master_model->updateRecord('duplicate_certificate',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,id');
							if($get_payment_status[0]['status']==1)
							{
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'duplicate_cert'));
								$this->sms_template_id = 'MVPWKSwGg';
								if(count($emailerstr) > 0 && (count($get_user_regnum) > 0))
								{
									$final_str = $emailerstr[0]['emailer_text'];
									$info_arr = array('to'=>$user_info[0]['email'],'from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'].' '.$get_payment_status[0]['member_regnumber'],'message'=>$final_str);
									//genertate invoice and email send with invoice attach 8-7-2017					
									//get invoice	
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum[0]['id']));
									//echo $this->db->last_query();exit;
									if(count($getinvoice_number) > 0)
									{ 
										$invoiceNumber = generate_duplicate_cert_invoice_number($getinvoice_number[0]['invoice_id']);
										if($invoiceNumber)
										{
											$invoiceNumber=$this->config->item('Dup_cert_invoice_no_prefix').$invoiceNumber;
										}
										$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
										$this->db->where('pay_txn_id',$get_user_regnum[0]['id']);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										$attachpath=genarate_duplicatecert_invoice($getinvoice_number[0]['invoice_id']);
									}
									if($attachpath!='')
									{	
										$this->Emailsending->mailsend_attch($info_arr,$attachpath);
									}
								}
							}
						}
					}
					else if($pay_type == "IIBF_EXAM_O")
					{
						sleep(10);
						
						if($payment_status==1)
						{	
							 $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
               				$this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type . " - " . $transaction_error_desc.'-S2S-success-log-'.$date_for_log.'-'.$MerchantOrderNo);

							$exam_period_date='';
							//Handle transaction success case
							$elective_subject_name='';
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
							///check for dual exam applied or not 
							if($get_user_regnum[0]['status']==2)
							{
								///charter bank S2S call
								if($get_user_regnum[0]['exam_code']=='1016')
								{
									$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(
									'receipt_no' => $MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
									$reg_id        = $get_user_regnum_info[0]['ref_id'];
									$applicationNo = $get_user_regnum_info[0]['member_regnumber'];
									$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
									/* Transaction Log */
									$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
									$this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);
									//echo $reg_id; die;
									$reg_data = $this->master_model->getRecords('caiib_jaiib_newexam_registration', array('member_no' => $applicationNo, 'mem_exam_id' => $reg_id),'exam_code');
									$selected_exam_code =$reg_data[0]['exam_code'];
									/* Get User Attempt */
									$attemptQry=$this->db->query("SELECT attempt,fee_flag FROM caiib_jaiib_newexam_eligible WHERE member_no='".$applicationNo."' AND exam_code = '" . $selected_exam_code . "' LIMIT 1"); 
									$attemptArr = $attemptQry->row_array();
									$attempt = $attemptArr['attempt'];
									$fee_flag=$attemptArr['fee_flag'];
									$attempt = $attempt+1;
									/* Update Pay Status and User Attemp Status */
									$blended_data = array('pay_status'=>1, 'attempt'=>$attempt, 'modify_date'=>date('Y-m-d H:i:s'));
									$memberexam_data = array('pay_status'=>1,  'modified_on'=>date('Y-m-d H:i:s'));
									//$regno=$this->session->userdata['memberdata']['regno'];
									$this->master_model->updateRecord('caiib_jaiib_newexam_registration',$blended_data,array('mem_exam_id'=>$reg_id,'member_no'=>$applicationNo));
									$this->master_model->updateRecord('member_exam',$memberexam_data,array('id'=>$reg_id));
									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'charterd_email'));
									$this->sms_template_id = 'rP53cIwMR';
									if (!empty($applicationNo)) {
										$user_info = $this->master_model->getRecords('member_registration',array('regnumber'=> $applicationNo,'isactive'=>'1'),'email,mobile');
									}
									if (count($emailerstr) > 0) 
									{
										/* Set Email Content For user */
										$Qry=$this->db->query("SELECT exam_code, qualification FROM caiib_jaiib_newexam_registration WHERE mem_exam_id = '".$reg_id."' LIMIT 1");
										$detailsArr        = $Qry->row_array();
										$exam_code = $detailsArr['exam_code'];
										$exam_name ='Chartered Banker Intitute'; //$detailsArr['qualification'];
										$newstring    = str_replace("#exam_name#","".$exam_name."",$emailerstr[0]['emailer_text']);
										/* Set Email sending options */
										$info_arr          = array(
										'to' => $user_info[0]['email'],
										'from' => $emailerstr[0]['from'],
										'subject' => $emailerstr[0]['subject'].' '.$applicationNo,
										'message' => $newstring
										);
										$getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo,'pay_txn_id' => $get_user_regnum_info[0]['id']));
										$zone_code = ""; 
										$zoneArr = array();
										//$regno = $this->session->userdata['memberdata']['regno'];
										$zoneArr = $this->master_model->getRecords('caiib_jaiib_newexam_registration',array('mem_exam_id'=>$reg_id,'pay_status'=>1),'gstin_no');
										$gstin_no          = $zoneArr[0]['gstin_no'];
										/* Invoice Number Genarate Functinality */
										if (count($getinvoice_number) > 0){
											$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
											if($invoiceNumber)
											{
												$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
											}
											$update_data = array(
											'invoice_no' => $invoiceNumber,
											'transaction_no' => $transaction_no,
											'date_of_invoice' => date('Y-m-d H:i:s'),
											'modified_on' => date('Y-m-d H:i:s')
											);
											$this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
											$this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
											/* Invoice Genarate Function */
											$attachpath = genarate_chartered_exam_invoice($getinvoice_number[0]['invoice_id']);
											$this->Emailsending->mailsend_attch($info_arr,$attachpath);
											/* User Log Activities  */
											$log_title ="Charterd accountant Registration-Invoice Genarate";
											$log_message = serialize($update_data);
											$rId = $reg_id;
											$regNo = $applicationNo;
											storedUserActivity($log_title, $log_message, $rId, $regNo);
										}
										if ($attachpath != '') 
										{ 
											/* Email Send To Clints */
											if (!empty($applicationNo)) {
												$reg_info = $this->master_model->getRecords('caiib_jaiib_newexam_registration',array('member_no'=> $applicationNo,'mem_exam_id' => $reg_id));
											}
											$payment_infoArr=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$reg_info[0]['member_no']),'transaction_no,date,amount');
											if($reg_info[0]['member_no'] == $applicationNo)
											{
												$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'charterd_emailer_client'));
												$this->sms_template_id = 'NA';
												if(count($emailerSelfStr) > 0)
												{
													$designation_name=$undergraduate_name=$graduate_name=$postgraduate_name=$specify_qualification=$institution_name=$pay="";
													$qualification = $reg_info[0]['qualification'];
													$exam_name ='Chartered Banker Intitute'; //$detailsArr['qualification'];
													if($reg_info[0]['pay_status'] == 1){ $pay = "Success";}
													$selfstr1 = str_replace("#regnumber#", "".$reg_info[0]['member_no']."",  $emailerSelfStr[0]['emailer_text']);
													$selfstr2 = str_replace("#exam_name#", "".$exam_name."",  $selfstr1);
													$selfstr8 = str_replace("#name#", "".$reg_info[0]['namesub']." ".$reg_info[0]['firstname']." ".$reg_info[0]['middlename']." ".$reg_info[0]['lastname'],  $selfstr2);
													$selfstr9 = str_replace("#address1#", "".$reg_info[0]['address1']."",  $selfstr8);
													$selfstr10 = str_replace("#address2#", "".$reg_info[0]['address2']."",  $selfstr9);
													$selfstr11 = str_replace("#address3#", "".$reg_info[0]['address3']."",  $selfstr10);
													$selfstr12 = str_replace("#address4#", "".$reg_info[0]['address4']."",  $selfstr11);
													$selfstr13 = str_replace("#district#", "".$reg_info[0]['district']."",  $selfstr12);
													$selfstr14 = str_replace("#city#", "".$reg_info[0]['city']."",  $selfstr13);
													$selfstr15 = str_replace("#state#", "".$reg_info[0]['state']."",  $selfstr14);
													$selfstr16 = str_replace("#pincode#", "".$reg_info[0]['pincode']."",  $selfstr15);
													$selfstr19 = str_replace("#dateofbirth#", "".$reg_info[0]['dateofbirth']."",  $selfstr16);
													$selfstr20 = str_replace("#email#", "".$reg_info[0]['email']."",  $selfstr19);
													$selfstr21 = str_replace("#mobile#", "".$reg_info[0]['mobile']."",  $selfstr20);
													$selfstr26 = str_replace("#qualification#", "".$reg_info[0]['qualification']."",  $selfstr21);
													$selfstr31 = str_replace("#TRANSACTION_NO#", "".$payment_infoArr[0]['transaction_no']."",  $selfstr26);
													$selfstr32 = str_replace("#AMOUNT#", "".$payment_infoArr[0]['amount']."",  $selfstr31);
													$selfstr33 = str_replace("#STATUS#", "Transaction Successful",  $selfstr32);
													$final_selfstr = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_infoArr[0]['date']))."",  $emailerSelfStr[0]['subject']);
													// $s1 = str_replace("#exam_code#", "".$reg_info[0]['exam_code'],$emailerSelfStr[0]['subject']);
													$final_sub = str_replace("#exam_code#", "".$reg_info[0]['exam_code']."",  $final_selfstr);
													/* Get Client Emails Details */
													// $emailsQry = $this->db->query("SELECT emails FROM offline_email_master WHERE  exam_code = '" . $reg_info[0]['exam_code'] . "'AND isdelete = 0 LIMIT 1 ");
													// $emailsArr    = $emailsQry->row_array();
													// $emails  = $emailsArr['emails'];  
													$self_mail_arr = array(
													'to'=>$emails,
													'from'=>$emailerSelfStr[0]['from'],
													'subject'=>$final_sub.' '.$reg_info[0]['member_no'],
													'message'=>$final_selfstr); 
													$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);
												}
											}
											} else { 
											redirect(base_url() . 'caiib_jaiib_reg/caiib_jaiib_reg_acknowledge/');
										}
									}
								}
								else
								{
									//START :  Code by vishal/sagar to prevent duplicate exam registration (2023-07-25)
									$member_exam_data=$this->master_model->getRecords('member_exam',array('id'=>$get_user_regnum[0]['ref_id']));
									if (count($member_exam_data)>0) {
										$current_exam_code = $member_exam_data[0]['exam_code'];
										$current_exam_period = $member_exam_data[0]['exam_period'];
										$current_mem_no = $member_exam_data[0]['regnumber'];
										if ($current_exam_code!='' && $current_exam_period!='' && $current_mem_no!='') {
											$check_if_already_apply=$this->master_model->getRecords('member_exam',array('exam_code'=>$current_exam_code,'exam_period'=>$current_exam_period,'pay_status'=>'1','regnumber'=>$current_mem_no));
											if (count($check_if_already_apply)>0) {
												$refund_insert_array=array('receipt_no'=>$MerchantOrderNo,'response'=>$encData);
												$this->master_model->insertRecord('S2S_direcrt_refund',$refund_insert_array);
												$this->refund_after_capacity_full->make_refund($MerchantOrderNo);
													
												$log_title = "S2S duplicate entry prevented1 :" . $MerchantOrderNo;
												$log_message = $log_title;
												$rId = $current_mem_no;
												$regNo = $current_mem_no;
												storedUserActivity($log_title, $log_message, $rId, $regNo);		
												exit();
											}
										}
									}

									//END :  Code by vishal/sagar to prevent duplicate exam registration

									######### payment Transaction ############
									$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
									$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
									if($this->db->affected_rows())
									{
										$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
										if($get_payment_status[0]['status']==1)
										{
											if(count($get_user_regnum) > 0)
											{
												$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
											}
											//Query to get exam details	
											$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
											$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
											$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
											$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
											$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code');
											if($exam_info[0]['exam_code']!=101 || $exam_info[0]['exam_code']!=990 || $exam_info[0]['exam_code']!=993 || $exam_info[0]['exam_code']!=1021 || $exam_info[0]['exam_code']!=1022 || $exam_info[0]['exam_code']!=1023 || $exam_info[0]['exam_code']!=1024 || $exam_info[0]['exam_code']!=1025)
											{
												########## Generate Admit card and allocate Seat #############
												$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
												############check capacity is full or not ##########
												//$subject_arr=$this->session->userdata['examinfo']['subject_arr'];
												if(count($exam_admicard_details) > 0)
												{			
													$msg='';
													$sub_flag=1;
													$sub_capacity=1;
													foreach($exam_admicard_details as $row)
													{
														$capacity=check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
														if($capacity==0)
														{
															#########get message if capacity is full##########
															$log_title ="Capacity full id:".$get_user_regnum[0]['member_regnumber'];
															$log_message = serialize($exam_admicard_details);
															$rId = $get_user_regnum[0]['ref_id'];
															$regNo = $get_user_regnum[0]['member_regnumber'];
															storedUserActivity($log_title, $log_message, $rId, $regNo);
															$refund_insert_array=array('receipt_no'=>$MerchantOrderNo,'response'=>$encData);
															$inser_id = $this->master_model->insertRecord('S2S_direcrt_refund',$refund_insert_array);
															$this->refund_after_capacity_full->make_refund($MerchantOrderNo);
															exit;
															//redirect(base_url().'Home/refund/'.base64_encode($MerchantOrderNo));
														}
													}
												}
												if(count($exam_admicard_details) > 0 && $capacity > 0)
												{	
													$password=random_password();
													foreach($exam_admicard_details as $row)
													{
														$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'exam_date'=>$row['exam_date'],'session_time'=>$row['time'],'center_code'  => $row['center_code']));
														$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
														//echo $this->db->last_query().'<br>';
														$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
														if($seat_number!='')
														{
															$final_seat_number =$seat_number;
															$update_data = array('pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
															$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
														}
														else
														{
															$admit_card_details=$this->master_model->getRecords('admit_card_details',array('admitcard_id'=>$admit_card_details[0]['admitcard_id'],'remark'=>1));
															if(count($admit_card_details) > 0)
															{
																$log_title ="Seat number already allocated id:".$get_user_regnum[0]['member_regnumber'];
																$log_message = serialize($exam_admicard_details);
																$rId = $admit_card_details[0]['admitcard_id'];
																$regNo = $get_user_regnum[0]['member_regnumber'];
																storedUserActivity($log_title, $log_message, $rId, $regNo);
															}
															else
															{
																$log_title ="1)Fail user seat allocation id:".$get_user_regnum[0]['member_regnumber'];
																$log_message = serialize($exam_admicard_details);
																$rId = $get_user_regnum[0]['member_regnumber'];
																$regNo = $get_user_regnum[0]['member_regnumber'];
																storedUserActivity($log_title, $log_message, $rId, $regNo);
																//redirect(base_url().'Home/refund/'.base64_encode($MerchantOrderNo));
															}
														}
													}
												}
												else
												{
													//redirect(base_url().'Home/refund/'.base64_encode($MerchantOrderNo));
												}
											}
											######update member_exam######
											if($get_payment_status[0]['status']==1){
												
												$update_data = array('pay_status' => '1');
												$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
												$log_title ="S2S member exam Update:".$get_user_regnum[0]['ref_id'];
												$log_message = '';
												$rId = $get_user_regnum[0]['ref_id'];
												$regNo = $get_user_regnum[0]['ref_id'];
												storedUserActivity($log_title, $log_message, $rId, $regNo);
												
												##Code added by Pratiba Borse to remove 777 static exm code on 21 march 22
												if($capacity == 0){
													$log_title ="2)Fail user seat allocation id:".$get_user_regnum[0]['member_regnumber'];
													$log_message = serialize($exam_admicard_details);
													$rId = $get_user_regnum[0]['member_regnumber'];
													$regNo = $get_user_regnum[0]['member_regnumber'];
													storedUserActivity($log_title, $log_message, $rId, $regNo);
												}
												}else{
												$log_title ="S2S member exam Update fail:".$get_user_regnum[0]['ref_id'];
												$log_message = $get_user_regnum[0]['ref_id'];
												$rId = $get_user_regnum[0]['ref_id'];
												$regNo = $get_user_regnum[0]['ref_id'];
												storedUserActivity($log_title, $log_message, $rId, $regNo);
											}
											//Query to get user details
											$this->db->join('state_master','state_master.state_code=member_registration.state');
											$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
											$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');
											if(count($exam_info) <= 0)
											{
												$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']));
											}
											if($exam_info[0]['exam_mode']=='ON')
											{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
											{$mode='Offline';}
											else{$mode='';}
											if($exam_info[0]['examination_date']!='0000-00-00')
											{
												$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
											}
											else if($exam_info[0]['exam_code']!=990)
											{
												//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
												$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
												$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
											}
											//Query to get Medium	
											$this->db->where('exam_code',$exam_info[0]['exam_code']);
											$this->db->where('exam_period',$exam_info[0]['exam_period']);
											$this->db->where('medium_code',$exam_info[0]['exam_medium']);
											$this->db->where('medium_delete','0');
											$medium=$this->master_model->getRecords('medium_master','','medium_description');
											$this->db->where('state_delete','0');
											$states=$this->master_model->getRecords('state_master',array('state_code'=>$exam_info[0]['state_place_of_work']),'state_name');
											//Query to get Payment details	
											$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,id');
											$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
											$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
											//if($this->session->userdata['examinfo']['elected_exam_mode']=='E')
											if($exam_info[0]['place_of_work']!='' && $exam_info[0]['state_place_of_work']!='' && $exam_info[0]['pin_code_place_of_work']!='')
											{
												//get Elective Subeject name for CAIIB Exam	
												if($exam_info[0]['elected_sub_code']!=0 && $exam_info[0]['elected_sub_code']!='')
												{
													$elective_sub_name_arr=$this->master_model->getRecords('subject_master',array('subject_code'=>$exam_info[0]['elected_sub_code'],'subject_delete'=>0),'subject_description');
													if(count($elective_sub_name_arr) > 0)
													{
														$elective_subject_name=$elective_sub_name_arr[0]['subject_description'];
													}	
												}
												$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'member_exam_enrollment_nofee_elective'));
												$this->sms_template_id = 'P6tIFIwGR';
												$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
												$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
												$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
												$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
												$newstring5 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring4);
												$newstring6 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring5);
												$newstring7 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring6);
												$newstring8 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring7);
												$newstring9 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring8);
												$newstring10 = str_replace("#CITY#", "".$result[0]['city']."",$newstring9);
												$newstring11 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring10);
												$newstring12 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring11);
												$newstring13 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring12);
												$newstring14 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring13);
												$newstring15 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring14);
												$newstring16 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring15);
												$newstring17 = str_replace("#ELECTIVE_SUB#", "".$elective_subject_name."",$newstring16);
												$newstring18 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring17);
												$newstring19 = str_replace("#PLACE_OF_WORK#", "".strtoupper($exam_info[0]['place_of_work'])."",$newstring18);
												$newstring20 = str_replace("#STATE_PLACE_OF_WORK#", "".$states[0]['state_name']."",$newstring19);
												$newstring21 = str_replace("#PINCODE_PLACE_OF_WORK#", "".$exam_info[0]['pin_code_place_of_work']."",$newstring20);
												$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
												if(count($elern_msg_string) > 0)
												{
													foreach($elern_msg_string as $row)
													{
														$arr_elern_msg_string[]=$row['exam_code'];
													}
													if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
													{
														$newstring22 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring21);		
													}
													else
													{
														$newstring22 = str_replace("#E-MSG#", '',$newstring21);		
													}
												}
												else
												{
													$newstring22 = str_replace("#E-MSG#", '',$newstring21);
												}
												$final_str = str_replace("#MODE#", "".$mode."",$newstring22);
											}
											else
											{
												if($exam_info[0]['exam_code']==990)
												{
													$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'DISA_emailer'));
													$this->sms_template_id = 'jYZ1dSwGR';
													$final_str = $emailerstr[0]['emailer_text'];
												}else if($exam_info[0]['exam_code']==993)
												{
													$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'Cisi_emailer'));
													$this->sms_template_id = 'gewX5IwGR';
													$final_str = $emailerstr[0]['emailer_text'];
												}
												else
												{
													$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'apply_exam_transaction_success'));
													$this->sms_template_id = 'LSy_cIwGg';
													$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
													$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
													$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
													$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
													$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
													$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
													$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
													$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
													$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
													$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
													$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
													$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
													$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
													$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
													$newstring15 = str_replace("#INSTITUDE#", "".$result[0]['name']."",$newstring14);
													$newstring16 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring15);
													$newstring17 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring16);
													$newstring18 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring17);
													$newstring19 = str_replace("#MODE#", "".$mode."",$newstring18);
													$newstring20 = str_replace("#PLACE_OF_WORK#", "".$result[0]['office']."",$newstring19);
													$newstring21 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring20);
													$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
													if(count($elern_msg_string) > 0 )
													{
														foreach($elern_msg_string as $row)
														{
															$arr_elern_msg_string[]=$row['exam_code'];
														}
														if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
														{
															$newstring22 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring21);		
														}
														else
														{
															$newstring22 = str_replace("#E-MSG#", '',$newstring21);		
														}
													}
													else
													{
														$newstring22 = str_replace("#E-MSG#", '',$newstring21);
													}
													$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring22);
												}
											}
											$info_arr=array('to'=>$result[0]['email'],
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],
											'message'=>$final_str
											);
											//echo $final_str; exit;
											//get invoice	
											$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
											//echo $this->db->last_query();exit;
											########### generate invoice ###########
											if(count($getinvoice_number) > 0)
											{
												
												if($exam_info[0]['exam_code']==990)
												{
													$invoiceNumber =generate_DISA_invoice_number($getinvoice_number[0]['invoice_id']);
													if($invoiceNumber)
													{
														$invoiceNumber=$this->config->item('DISA_invoice_no_prefix').$invoiceNumber;
													}
												}
												else if($exam_info[0]['exam_code']==993)
												{
													$invoiceNumber =generate_CISI_invoice_number($getinvoice_number[0]['invoice_id']);
													if($invoiceNumber)
													{
														$invoiceNumber=$this->config->item('Cisi_invoice_no_prefix').$invoiceNumber;
													}
												}
												else if($exam_info[0]['exam_code']==1018)
												{
													## Code added for GARP Module on 8 Jul 2021	
													$invoiceNumber = generate_GARP_invoice_number($getinvoice_number[0]['invoice_id']);
													if($invoiceNumber)
													{
														$invoiceNumber=$this->config->item('garp_invoice_no_prefix').$invoiceNumber;
													}
												}
												else if($exam_info[0]['exam_code']==$this->config->item('examCodeCFP'))
												{
													## Code added for GARP Module on 8 Jul 2021	
													$invoiceNumber = generate_CFP_invoice_number($getinvoice_number[0]['invoice_id']);
													if($invoiceNumber)
													{
														$invoiceNumber=$this->config->item('cfp_invoice_no_prefix').$invoiceNumber;
													}
												}
												else
												{
													$invoiceNumber = '';
													if($get_payment_status[0]['status']==1){
														$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
														$log_title ="S2S exam invoice number generate:".$getinvoice_number[0]['invoice_id'];
														$log_message = '';
														$rId = $MerchantOrderNo;
														$regNo = $MerchantOrderNo;
														storedUserActivity($log_title, $log_message, $rId, $regNo);
														}else{
														$log_title ="S2S exam invoice number generate fail:".$MerchantOrderNo;
														$log_message = $MerchantOrderNo;
														$rId = $MerchantOrderNo;
														$regNo = $MerchantOrderNo;
														storedUserActivity($log_title, $log_message, $rId, $regNo);
													}
													if($invoiceNumber)
													{
														$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
													}
												}
												//}
												## invoice no condition added by chaitali on 2021-10-15
												if($get_payment_status[0]['status']==1 && $invoiceNumber != ''){
													$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
													$this->db->where('pay_txn_id',$payment_info[0]['id']);
													$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
													$log_title ="S2S exam invoice Update:".$MerchantOrderNo;
													$log_message = '';
													$rId = $MerchantOrderNo;
													$regNo = $MerchantOrderNo;
													storedUserActivity($log_title, $log_message, $rId, $regNo);
													}else{
													$log_title ="S2S exam invoice Update fail:".$MerchantOrderNo;
													$log_message = $MerchantOrderNo;
													$rId = $MerchantOrderNo;
													$regNo = $MerchantOrderNo;
													storedUserActivity($log_title, $log_message, $rId, $regNo);
												}
												if($exam_info[0]['exam_code']==990)
												{
													$attachpath=genarate_DISA_invoice($getinvoice_number[0]['invoice_id']);	
												}else if($exam_info[0]['exam_code']==993)
												{
													$attachpath=genarate_CISI_invoice($getinvoice_number[0]['invoice_id']);
												}
												else if($exam_info[0]['exam_code']==1018)
												{
													$attachpath=genarate_garp_exam_invoice($getinvoice_number[0]['invoice_id']);

													//garp pay_status update code added by Pooja mane on 2023-04-19
													$update_data = array('pay_status' => '1','modified_on'=>date('Y-m-d H:i:s'));
													$this->db->where('mem_exam_id',$exam_info[0]['id']);
													$this->master_model->updateRecord('garp_exam_registration',$update_data);
													$log_title ="S2S garp pay_status Update:".$MerchantOrderNo;
													$log_message = '';
													$rId = $MerchantOrderNo;
													$regNo = $MerchantOrderNo;
													storedUserActivity($log_title, $log_message, $rId, $regNo);
													//garp pay_status update code end by Pooja mane on 2023-04-19
												}
												else if($exam_info[0]['exam_code']==$this->config->item('examCodeCFP'))
												{
													$attachpath=genarate_cfp_exam_invoice($getinvoice_number[0]['invoice_id']);

													
													$update_data = array('pay_status' => '1','modified_on'=>date('Y-m-d H:i:s'));
													$this->db->where('mem_exam_id',$exam_info[0]['id']);
													$this->master_model->updateRecord('cfp_exam_registration',$update_data);
													$log_title ="S2S cfp pay_status Update:".$MerchantOrderNo;
													$log_message = '';
													$rId = $MerchantOrderNo;
													$regNo = $MerchantOrderNo;
													storedUserActivity($log_title, $log_message, $rId, $regNo);
													//garp pay_status update code end by Pooja mane on 2023-04-19
												}
												else if($exam_info[0]['exam_code']==1021 || $exam_info[0]['exam_code']==1022 || $exam_info[0]['exam_code']==1023 || $exam_info[0]['exam_code']==1024 || $exam_info[0]['exam_code']==1025)
												{
													$attachpath=genarate_PB_invoice($getinvoice_number[0]['invoice_id']);
												}
												else
												{
													$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
												}
												if($exam_info[0]['exam_code']!=101 || $exam_info[0]['exam_code']!=990 || $exam_info[0]['exam_code']!=993)
												{
													##############Get Admit card#############
													$admitcard_pdf=genarate_admitcard($get_user_regnum[0]['member_regnumber'],$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
												}
											}	
											if($attachpath!='')
											{		
												$files=array($attachpath,$admitcard_pdf);
												if($exam_info[0]['exam_code']==990 || $exam_info[0]['exam_code']==993)
												{
													$sms_final_str = $emailerstr[0]['sms_text'];
												}
												else
												{
													$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
													$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
													$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
													$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
													$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
												}
												if($this->Emailsending->mailsend_attch($info_arr,$files)){
													$insert_data1['description']  = 'S2S Exam registration mail send.';
													$insert_data1['to_email']  = @$result[0]['email'];
													$insert_data1['email_data']  = json_encode($final_str);
													$insert_data1['attachment']  = json_encode($attachpath.'--'.$admitcard_pdf);
													$this->master_model->insertRecord('log_payment_callback_s2s', $insert_data1);
													}else{
													$insert_data1['description']  = 'S2S Exam registration mail not send check if.';
													$insert_data1['to_email']  = @$result[0]['email'];
													$insert_data1['email_data']  = json_encode($final_str);
													$insert_data1['attachment']  = json_encode($attachpath.'--'.$admitcard_pdf);
													$this->master_model->insertRecord('log_payment_callback_s2s', $insert_data1);
												}
												// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
												//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
												$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

												//$this->Emailsending->mailsend($info_arr);
												}else{
												$insert_data1['description']  = 'S2S Exam registration mail not send.';
												$insert_data1['to_email']  = @$result[0]['email'];
												$insert_data1['email_data']  = json_encode($final_str);
												$this->master_model->insertRecord('log_payment_callback_s2s', $insert_data1);
											}
										}
									}
									else
									{
										$log_title ="S2S Update fail:".$get_user_regnum[0]['member_regnumber'];
										$log_message = serialize($update_data);
										$rId = $MerchantOrderNo;
										$regNo = $get_user_regnum[0]['member_regnumber'];
										storedUserActivity($log_title, $log_message, $rId, $regNo);	
									}
								}
							}
						}
						else if($payment_status==0)
						{
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code');
							if($get_user_regnum[0]['status']==2)
							{
								$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
								//Query to get Payment details	
								$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
								if($get_user_regnum[0]['exam_code']!='990')
								{
									//Query to get user details
									$this->db->join('state_master','state_master.state_code=member_registration.state');
									$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
									$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');
									//Query to get exam details	
									$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
									$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
									$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
									//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
									$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
									$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
									$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
									$this->sms_template_id = 'Jw6bOIQGg';
									$newstring1 = str_replace("#application_num#", "".$get_user_regnum[0]['member_regnumber']."",  $emailerstr[0]['emailer_text']);
									$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
									$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
									$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
									$info_arr=array(	'to'=>$result[0]['email'],
									'from'=>$emailerstr[0]['from'],
									'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],
									'message'=>$final_str
									);
									//send sms to Ordinary Member
									$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
									$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
									$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
									//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
									//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
									$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

									$this->Emailsending->mailsend($info_arr);
								}
							}
						}				
					}
					else if($pay_type == "IIBF_EXAM_NM")
					{
						sleep(8);
						$this->sms_template_id = 'P6tIFIwGR';
						if($payment_status==1)
						{
							// Handle transaction success case 
							$exam_period_date=$attachpath=$invoiceNumber='';
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');

							//START :  Code by vishal/sagar to prevent duplicate exam registration (2023-07-25)
							$member_exam_data=$this->master_model->getRecords('member_exam',array('id'=>$get_user_regnum[0]['ref_id']));
							if (count($member_exam_data)>0) {
										$current_exam_code = $member_exam_data[0]['exam_code'];
										$current_exam_period = $member_exam_data[0]['exam_period'];
										$current_mem_no = $member_exam_data[0]['regnumber'];
										if ($current_exam_code!='' && $current_exam_period!='' && $current_mem_no!='') {
											$check_if_already_apply=$this->master_model->getRecords('member_exam',array('exam_code'=>$current_exam_code,'exam_period'=>$current_exam_period,'pay_status'=>'1','regnumber'=>$current_mem_no));
											if (count($check_if_already_apply)>0) {
												$refund_insert_array=array('receipt_no'=>$MerchantOrderNo,'response'=>$encData);
												$this->master_model->insertRecord('S2S_direcrt_refund',$refund_insert_array);
												$this->refund_after_capacity_full->make_refund($MerchantOrderNo);
												
												$log_title = "S2S duplicate entry prevented2 :" . $MerchantOrderNo;
												$log_message = $log_title;
												$rId = $current_mem_no;
												$regNo = $current_mem_no;
												storedUserActivity($log_title, $log_message, $rId, $regNo);		
												exit();
											}
										}
									}

							//END :  Code by vishal/sagar to prevent duplicate exam registration


							///check for duplicate entry
							///check duplcate entry end
							if($get_user_regnum[0]['status']==2)
							{
								######### payment Transaction ############
								$update_data = array('transaction_no' => $transaction_no,'status' => 1,'transaction_details' =>  $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
								
								$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
								if($this->db->affected_rows())
								{
									$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
									if($get_payment_status[0]['status']==1)
									{
										if(count($get_user_regnum) > 0)
										{
											$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
										}
										//Query to get user details
										$this->db->join('state_master','state_master.state_code=member_registration.state');
										//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
										$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword');
										//Query to get exam details	
										$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
										$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
										$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
										$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
										$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.examination_date');
										if(count($exam_info) <= 0)
										{
											$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']));
										}
										########## Generate Admit card and allocate Seat #############
										if($exam_info[0]['exam_code']!=101 || $exam_info[0]['exam_code']!=990 || $exam_info[0]['exam_code']!=993)
										{
											$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
											if(count($exam_admicard_details) > 0)
											{
												############check capacity is full or not ##########
												//$subject_arr=$this->session->userdata['examinfo']['subject_arr'];
												if(count($exam_admicard_details) > 0)
												{		
													$msg='';
													$sub_flag=1;
													$sub_capacity=1;
													foreach($exam_admicard_details as $row)
													{
														$capacity=check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
														if($capacity==0)
														{
															#########get message if capacity is full##########
															$log_title ="Capacity full id:".$get_user_regnum[0]['member_regnumber'];
															$log_message = serialize($exam_admicard_details);
															$rId = $get_user_regnum[0]['ref_id'];
															$regNo = $get_user_regnum[0]['member_regnumber'];
															storedUserActivity($log_title, $log_message, $rId, $regNo);
															$refund_insert_array=array('receipt_no'=>$MerchantOrderNo,'response'=>$encData);
															$inser_id = $this->master_model->insertRecord('S2S_direcrt_refund',$refund_insert_array);
															$this->refund_after_capacity_full->make_refund($MerchantOrderNo);
															exit;
															//redirect(base_url().'NonMember/refund/'.base64_encode($MerchantOrderNo));
														}
													}
												}
												$password=random_password();
												foreach($exam_admicard_details as $row)
												{
													$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'exam_date'=>$row['exam_date'],'session_time'=>$row['time'],'center_code'  => $row['center_code']));
													$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
													//echo $this->db->last_query().'<br>';
													$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
													if($seat_number!='')
													{
														$final_seat_number = $seat_number;
														$update_data = array('pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
														$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
													}
												}
											}		
											else
											{
												//redirect(base_url().'NonMember/refund/'.base64_encode($MerchantOrderNo));
											}
										}
										######update member_exam######	
										$update_data = array('pay_status' => '1');
										$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
										if($exam_info[0]['exam_mode']=='ON')
										{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
										{$mode='Offline';}
										else{$mode='';}
										if($exam_info[0]['examination_date']!='0000-00-00')
										{
											$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
										}
										else if($exam_info[0]['exam_code']!=990)
										{
											//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
											$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
											$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
										}
										//Query to get Medium	
										$this->db->where('exam_code',$exam_info[0]['exam_code']);
										$this->db->where('exam_period',$exam_info[0]['exam_period']);
										$this->db->where('medium_code',$exam_info[0]['exam_medium']);
										$this->db->where('medium_delete','0');
										$medium=$this->master_model->getRecords('medium_master','','medium_description');
										//Query to get Payment details	
										$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,id');
										$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
										$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
										include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
										$key = $this->config->item('pass_key');
										$aes = new CryptAES();
										$aes->set_key(base64_decode($key));
										$aes->require_pkcs5();
										$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
										if($exam_info[0]['exam_code']==990)
										{
											$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'DISA_emailer'));
											$this->sms_template_id = 'S8OmhSQGg';
											$final_str = $emailerstr[0]['emailer_text'];
										}
										else if($exam_info[0]['exam_code']==993)
										{
											$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'Cisi_emailer'));
											$this->sms_template_id = 'gewX5IwGR';
											$final_str = $emailerstr[0]['emailer_text'];
										}
										else
										{
											$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
											$this->sms_template_id = 'P6tIFIwGR';
											$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
											$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
											$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
											$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
											$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
											$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
											$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
											$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
											$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
											$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
											$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
											$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
											$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
											$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
											$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
											$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
											$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
											$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
											$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
											$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring19);
											$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
											if(count($elern_msg_string) > 0)
											{
												foreach($elern_msg_string as $row)
												{
													$arr_elern_msg_string[]=$row['exam_code'];
												}
												if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
												{
													$newstring21 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring20);		
												}
												else
												{
													$newstring21 = str_replace("#E-MSG#", '',$newstring20);		
												}
											}
											else
											{
												$newstring21 = str_replace("#E-MSG#", '',$newstring20);
											}
											$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
										}
										$info_arr=array('to'=>$result[0]['email'],
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],
										'message'=>$final_str
										);
										//get invoice	
										$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
										//echo $this->db->last_query();exit;
										if(count($getinvoice_number) > 0)
										{
											if($exam_info[0]['exam_code']==990)
											{
												$invoiceNumber =generate_DISA_invoice_number($getinvoice_number[0]['invoice_id']);
												if($invoiceNumber)
												{
													$invoiceNumber=$this->config->item('DISA_invoice_no_prefix').$invoiceNumber;
												}
											}
											else if($exam_info[0]['exam_code']==993)
											{
												$invoiceNumber =generate_CISI_invoice_number($getinvoice_number[0]['invoice_id']);
												if($invoiceNumber)
												{
													$invoiceNumber=$this->config->item('Cisi_invoice_no_prefix').$invoiceNumber;
												}
											}
											else
											{
												$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
												if($invoiceNumber)
												{
													$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
												}
											}
											//}
											$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'));
											$this->db->where('pay_txn_id',$payment_info[0]['id']);
											$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
											if($exam_info[0]['exam_code']==990)
											{
												$attachpath=genarate_DISA_invoice($getinvoice_number[0]['invoice_id']);
											}
											else if($exam_info[0]['exam_code']==993)
											{
												$attachpath=genarate_CISI_invoice($getinvoice_number[0]['invoice_id']);
											}
											else
											{
												$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
											}
											if($exam_info[0]['exam_code']!=101 || $exam_info[0]['exam_code']!=990 || $exam_info[0]['exam_code']!=993)
											{
												##############Get Admit card#############
												$admitcard_pdf=genarate_admitcard($get_user_regnum[0]['member_regnumber'],$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
											}
										}		
										if($attachpath!='')
										{	
											$files=array($attachpath,$admitcard_pdf);	
											if($exam_info[0]['exam_code']==990 || $exam_info[0]['exam_code']==993)
											{
												$sms_final_str = $emailerstr[0]['sms_text'];
											}
											else
											{
												$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
												$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
												$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
												$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
												$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
											}
											// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
											//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
											$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

											$this->Emailsending->mailsend_attch($info_arr,$files);
											//$this->Emailsending->mailsend($info_arr);
										}
									}
								}
								else
								{
									$log_title ="S2S Update fail:".$get_user_regnum[0]['member_regnumber'];
									$log_message = serialize($update_data);
									$rId = $MerchantOrderNo;
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);	
								}
							}
						}
						else if($payment_status==0)
						{
							// Handle transaction  fail case
							// Handle transaction success case 
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code');
							if($get_user_regnum[0]['status']==2)
							{
								$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
								$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' =>  $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								if($get_user_regnum[0]['exam_code']!='990')
								{
									$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
									//Query to get Payment details	
									$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
									//Query to get exam details	
									$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
									$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
									$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
									//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
									$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
									$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
									$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
									$this->sms_template_id = 'Jw6bOIQGg';
									$newstring1 = str_replace("#application_num#", "".$get_user_regnum[0]['member_regnumber']."",  $emailerstr[0]['emailer_text']);
									$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
									$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
									$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
									$info_arr=array(	'to'=>$result[0]['email'],
									'from'=>$emailerstr[0]['from'],
									'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],
									'message'=>$final_str
									);
									$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
									$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
									$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
									//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
									//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
									$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

									$this->Emailsending->mailsend($info_arr);
								}
							}
						}
					}
					else if($pay_type == "IIBF_EXAM_REG")
					{
						sleep(8);
						
						if($payment_status==1)
						{
							// Handle transaction success case 
							$exam_period_date='';
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
							if($get_user_regnum[0]['status']==2)
							{
								######### payment Transaction ############
								$update_data = array('transaction_no' => $transaction_no,'status' => 1,'transaction_details' =>  $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
								$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
								if($this->db->affected_rows())
								{
									$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
									if($get_payment_status[0]['status']==1)
									{
										$exam_code=$get_user_regnum[0]['exam_code'];
										$reg_id=$get_user_regnum[0]['member_regnumber'];
										########## Generate Admit card and allocate Seat #############
										if($exam_code!=101)
										{
											$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
											//$subject_arr=$this->session->userdata['enduserinfo']['subject_arr'];
											if(count($exam_admicard_details) > 0)
											{		
												$msg='';
												$sub_flag=1;
												$sub_capacity=1;
												foreach($exam_admicard_details as $row)
												{
													$capacity=check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
													if($capacity==0)
													{
														#########get message if capacity is full##########
														#Add code trans_start & trans_complete : pooja  #
														$this->db->trans_start(); 
														$update_data = array('transaction_no' => $transaction_no,'status' => 1,'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
														$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
														$this->db->trans_complete();
														//redirect(base_url().'Nonreg/refund/'.base64_encode($MerchantOrderNo));
													}
												}
											}
										}
										//$applicationNo = generate_nm_reg_num();
										$applicationNo = generate_NM_memreg($reg_id);
										######### payment Transaction ############
										$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
										$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
										######### update application number to Registration table#########
										$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
										$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
										######### update application number to member exam#########
										$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
										$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
										//Query to get exam details	
										$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
										$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
										$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
										$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
										$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.examination_date');
										########## Generate Admit card and allocate Seat #############
										if($exam_code!='101')
										{
											if(count($exam_admicard_details) > 0)
											{
												$password=random_password();
												foreach($exam_admicard_details as $row)
												{
													$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'exam_date'=>$row['exam_date'],'session_time'=>$row['time'],'center_code'  => $row['center_code']));
													$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
													//echo $this->db->last_query().'<br>';
													$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
													if($seat_number!='')
													{
														$final_seat_number = $seat_number;
														$update_data = array('mem_mem_no'=>$applicationNo,'pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
														$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
													}
													else
													{
														$log_title ="Fail user seat allocation id:".$applicationNo;
														$log_message = serialize($exam_admicard_details);
														$rId = $applicationNo;
														$regNo = $applicationNo;
														storedUserActivity($log_title, $log_message, $rId, $regNo);
													}
												}
												##############Get Admit card#############
												$admitcard_pdf=genarate_admitcard($applicationNo,$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
											}	
											else
											{
												//redirect(base_url().'Nonreg/refund/'.base64_encode($MerchantOrderNo));
											}
										}
										if($exam_info[0]['exam_mode']=='ON')
										{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
										{$mode='Offline';}
										else{$mode='';}
										if($exam_info[0]['examination_date']!='0000-00-00')
										{
											$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
										}
										else
										{
											//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
											$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
											$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
										}
										//Query to get Medium	
										$this->db->where('exam_code',$exam_code);
										$this->db->where('exam_period',$exam_info[0]['exam_period']);
										$this->db->where('medium_code',$exam_info[0]['exam_medium']);
										$this->db->where('medium_delete','0');
										$medium=$this->master_model->getRecords('medium_master','','medium_description');
										//Query to get Payment details	
										$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNo),'transaction_no,date,amount,id');
										//Query to get user details
										$this->db->join('state_master','state_master.state_code=member_registration.state');
										//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
										$result=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,usrpassword');	
										########get Old image Name############
										$log_title ="Non member OLD Image :".$reg_id;
										$log_message = serialize($result);
										$rId = $reg_id;
										$regNo = $reg_id;
										storedUserActivity($log_title, $log_message, $rId, $regNo);
										$upd_files = array();
										$photo_file = 'p_'.$applicationNo.'.jpg';
										$sign_file = 's_'.$applicationNo.'.jpg';
										$proof_file = 'pr_'.$applicationNo.'.jpg';
										if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
										{	$upd_files['scannedphoto'] = $photo_file;	}
										if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
										{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
										if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
										{	$upd_files['idproofphoto'] = $proof_file;	}
										if(count($upd_files)>0)
										{
											$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
											$log_title ="Non member PICS Update S2S :".$reg_id;
											$log_message = serialize($upd_files);
											$rId = $reg_id;
											$regNo = $reg_id;
											storedUserActivity($log_title, $log_message, $rId, $regNo);	
										}
										else
										{
											$upd_files['scannedphoto'] = $photo_file;
											$upd_files['scannedsignaturephoto'] = $sign_file;	
											$upd_files['idproofphoto'] = $proof_file;
											$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
											$log_title ="Non member PICS MANUAL PICS Update S2S :".$reg_id;
											$log_message = serialize($upd_files);
											$rId = $reg_id;
											$regNo = $reg_id;
											storedUserActivity($log_title, $log_message, $rId, $regNo);	
										}
										include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
										$key = $this->config->item('pass_key');
										$aes = new CryptAES();
										$aes->set_key(base64_decode($key));
										$aes->require_pkcs5();
										$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
										$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
										$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
										$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
										$this->sms_template_id = 'P6tIFIwGR';
										$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
										$newstring2 = str_replace("#REG_NUM#", "".$applicationNo."",$newstring1);
										$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
										$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
										$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
										$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
										$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
										$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
										$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
										$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
										$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
										$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
										$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
										$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
										$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
										$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
										$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
										$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
										$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
										$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring19);
										$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
										if(count($elern_msg_string) > 0)
										{
											foreach($elern_msg_string as $row)
											{
												$arr_elern_msg_string[]=$row['exam_code'];
											}
											if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
											{
												$newstring21 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring20);		
											}
											else
											{
												$newstring21 = str_replace("#E-MSG#", '',$newstring20);		
											}
										}
										else
										{
											$newstring21 = str_replace("#E-MSG#", '',$newstring20);
										}
										$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
										$info_arr=array('to'=>$result[0]['email'],
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'].' '.$applicationNo,
										'message'=>$final_str
										);
										//get invoice	
										$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
										//echo $this->db->last_query();exit;
										if(count($getinvoice_number) > 0)
										{
											/*if($getinvoice_number[0]['state_of_center']=='JAM')
												{
												$invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
												if($invoiceNumber)
												{
												$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
												}
												}
												else
											{*/
											$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
											if($invoiceNumber)
											{
												$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
											}
											//}
											$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'member_no'=>$applicationNo,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
											$this->db->where('pay_txn_id',$payment_info[0]['id']);
											$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
											$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
										}	
										if($attachpath!='')
										{
											//send sms		
											$files=array($attachpath,$admitcard_pdf);			
											$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);		
											$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
											$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
											$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
											$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
											//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);						
											//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
											$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

											$this->Emailsending->mailsend_attch($info_arr,$files);
											//$this->Emailsending->mailsend($info_arr);
										}
									}
								}
								else
								{
									$log_title ="S2S Update fail:".$get_user_regnum[0]['member_regnumber'];
									$log_message = serialize($update_data);
									$rId = $MerchantOrderNo;
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);	
								}
							}
						}
						else if($payment_status==0)
						{
							// Handle transaction fail case 
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
							if($get_user_regnum[0]['status']==2)
							{
								$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' =>  $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								//Query to get Payment details	
								$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id');
								$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
								$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
								$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
								$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
								$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
								$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.regnumber'=>$payment_info[0]['member_regnumber']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
								//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
								$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
								$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
								$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
								$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
								$this->sms_template_id = 'Jw6bOIQGg';
								$newstring1 = str_replace("#application_num#", "",  $emailerstr[0]['emailer_text']);
								$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
								$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
								$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
								$info_arr=array(	'to'=>$result[0]['email'],
								'from'=>$emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'],
								'message'=>$final_str
								);
								// send SMS
								$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);	
								$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
								$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
								// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
								//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
								$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

								$this->Emailsending->mailsend($info_arr);
							}
						}
					}	
					else if($pay_type == "IIBF_EXAM_DB")
					{
						sleep(8);
						if($payment_status==1)
						{
							// Handle transaction success case 
							/*	$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');*/
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
							if($get_user_regnum[0]['status']==2)
							{
								######### payment Transaction ############
								$update_data = array('transaction_no' => $transaction_no,'status' => 1,'transaction_details' =>  $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
								$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
								if($this->db->affected_rows())
								{
									$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
									if($get_payment_status[0]['status']==1)
									{
										// Handle transaction success case 
										$exam_code=$get_user_regnum[0]['exam_code'];
										$reg_id=$get_user_regnum[0]['member_regnumber'];
										############check capacity is full or not ##########
										$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
										//$subject_arr=$this->session->userdata['enduserinfo']['subject_arr'];
										if(count($exam_admicard_details) > 0)
										{		
											$msg='';
											$sub_flag=1;
											$sub_capacity=1;
											foreach($exam_admicard_details as $row)
											{
												$capacity=check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
												if($capacity==0)
												{
													#########get message if capacity is full##########
													$this->db->trans_start(); 
													$update_data = array('transaction_no' => $transaction_no,'status' => 1,'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
													$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
													$this->db->trans_complete();
													//redirect(base_url().'Dbfuser/refund/'.base64_encode($MerchantOrderNo));
												}
											}
										}
										//$applicationNo = generate_dbf_reg_num(); 
										$applicationNo = generate_DBF_memreg($reg_id);
										######### payment Transaction ############
										$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
										$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
										##########Update Member Exam#############
										$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
										$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
										######update member_exam######
										$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
										$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
										//Query to get exam details	
										$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
										$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
										$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
										$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
										$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
										########## Generate Admit card and allocate Seat #############
										if(count($exam_admicard_details) > 0)
										{
											$password=random_password();
											foreach($exam_admicard_details as $row)
											{
												$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'exam_date'=>$row['exam_date'],'session_time'=>$row['time'],'center_code'  => $row['center_code']));
												$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
												//echo $this->db->last_query().'<br>';
												$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
												if($seat_number!='')
												{
													$final_seat_number = $seat_number;
													$update_data = array('mem_mem_no'=>$applicationNo,'pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
													$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
												}
												else
												{
													$log_title ="Fail user seat allocation id:".$applicationNo;
													$log_message = serialize($exam_admicard_details);
													$rId = $applicationNo;
													$regNo = $applicationNo;
													storedUserActivity($log_title, $log_message, $rId, $regNo);
													//redirect(base_url().'Dbfuser/refund/'.base64_encode($MerchantOrderNo));
												}
											}
											##############Get Admit card#############
											$admitcard_pdf=genarate_admitcard($applicationNo,$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
										}	
										else
										{
											//redirect(base_url().'Dbfuser/refund/'.base64_encode($MerchantOrderNo));
										}
										if($exam_info[0]['exam_mode']=='ON')
										{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
										{$mode='Offline';}
										else{$mode='';}
										//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
										$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
										$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
										//Query to get Medium	
										$this->db->where('exam_code',$exam_code);
										$this->db->where('exam_period',$exam_info[0]['exam_period']);
										$this->db->where('medium_code',$exam_info[0]['exam_medium']);
										$this->db->where('medium_delete','0');
										$medium=$this->master_model->getRecords('medium_master','','medium_description');
										//Query to get Payment details	
										$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNo),'transaction_no,date,amount,id');
										//Query to get user details
										$this->db->join('state_master','state_master.state_code=member_registration.state');
										//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
										$result=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto');	
										include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
										$key = $this->config->item('pass_key');
										$aes = new CryptAES();
										$aes->set_key(base64_decode($key));
										$aes->require_pkcs5();
										$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
										$upd_files = array();
										$photo_file = 'p_'.$applicationNo.'.jpg';
										$sign_file = 's_'.$applicationNo.'.jpg';
										$proof_file = 'pr_'.$applicationNo.'.jpg';
										if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
										{	$upd_files['scannedphoto'] = $photo_file;	}
										if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
										{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
										if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
										{	$upd_files['idproofphoto'] = $proof_file;	}
										if(count($upd_files)>0)
										{
											$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
										}
										$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
										$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
										$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
										$this->sms_template_id = 'P6tIFIwGR';
										$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
										$newstring2 = str_replace("#REG_NUM#", "".$applicationNo."",$newstring1);
										$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
										$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
										$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
										$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
										$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
										$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
										$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
										$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
										$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
										$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
										$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
										$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
										$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
										$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
										$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
										$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
										$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
										$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring19);
										$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
										if(count($elern_msg_string) > 0)
										{
											foreach($elern_msg_string as $row)
											{
												$arr_elern_msg_string[]=$row['exam_code'];
											}
											if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
											{
												$newstring21 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring20);		
											}
											else
											{
												$newstring21 = str_replace("#E-MSG#", '',$newstring20);		
											}
										}
										else
										{
											$newstring21 = str_replace("#E-MSG#", '',$newstring20);
										}
										$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
										$info_arr=array('to'=>$result[0]['email'],
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'].' '.$applicationNo,
										'message'=>$final_str
										);
										//get invoice 	
										$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
										//echo $this->db->last_query();exit;
										if(count($getinvoice_number) > 0)
										{
											/*if($getinvoice_number[0]['state_of_center']=='JAM')
												{
												$invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
												if($invoiceNumber)
												{
												$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
												}
												}
												else
											{*/
											$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
											if($invoiceNumber)
											{
												$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
											}
											//}
											$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'member_no'=>$applicationNo,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
											$this->db->where('pay_txn_id',$payment_info[0]['id']);
											$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
											$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
										}	
										if($attachpath!='')
										{		
											//send sms	
											$files=array($attachpath,$admitcard_pdf);					
											$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);					
											$sms_newstring = str_replace("#exam_name#", "".trim($exm_name_sms)."",$emailerstr[0]['sms_text']);
											$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",$sms_newstring);
											$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",$sms_newstring1);
											$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",$sms_newstring2);
											//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);				
											//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
											$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

											$this->Emailsending->mailsend_attch($info_arr,$files);
										}
									}
								}
								else
								{
									$log_title ="S2S Update fail:".$get_user_regnum[0]['member_regnumber'];
									$log_message = serialize($update_data);
									$rId = $MerchantOrderNo;
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);	
								}
							}
						}
						else if($payment_status==0)
						{
							// Handle transaction fail case 
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
							if($get_user_regnum[0]['status']==2)
							{
								$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' =>  $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								//Query to get Payment details	
								$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id,member_regnumber');
								$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
								$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
								$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
								$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
								$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
								$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.regnumber'=>$payment_info[0]['member_regnumber']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
								//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
								$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
								$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
								$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
								$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
								$this->sms_template_id = 'Jw6bOIQGg';
								$newstring1 = str_replace("#application_num#", "",  $emailerstr[0]['emailer_text']);
								$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
								$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
								$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
								$info_arr=array(	'to'=>$result[0]['email'],
								'from'=>$emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'],
								'message'=>$final_str
								);
								// send SMS
								$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);	
								$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
								$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
								// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
								//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);	
								$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

								$this->Emailsending->mailsend($info_arr);
							}
						}
					}
					else if($pay_type == "IIBF_EXAM_DB_EXAM")
					{
						sleep(8);
						
						if($payment_status==1)
						{	
							// Handle transaction success case 
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
							if($get_user_regnum[0]['status']==2)
							{
								######### payment Transaction ############
								$update_data = array('transaction_no' => $transaction_no,'status' => 1,'transaction_details' =>  $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
								$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
								if($this->db->affected_rows())
								{
									$get_payment_status=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
									if($get_payment_status[0]['status']==1)
									{
										if(count($get_user_regnum) > 0)
										{
											$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
										}
										######### payment Transaction ############
										/*	$this->db->trans_start();
											$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'S2S');
											$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
										$this->db->trans_complete();*/
										//Query to get user details
										$this->db->join('state_master','state_master.state_code=member_registration.state');
										//$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
										$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword');
										//Query to get exam details	
										$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
										$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
										$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
										$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
										$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
										include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
										$key = $this->config->item('pass_key');
										$aes = new CryptAES();
										$aes->set_key(base64_decode($key));
										$aes->require_pkcs5();
										$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
										########## Generate Admit card and allocate Seat #############
										$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
										if(count($exam_admicard_details) > 0)
										{
											############check capacity is full or not ##########
											//$subject_arr=$this->session->userdata['examinfo']['subject_arr'];
											if(count($exam_admicard_details) > 0)
											{	
												$msg='';
												$sub_flag=1;
												$sub_capacity=1;
												foreach($exam_admicard_details as $row)
												{
													$capacity=check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
													if($capacity==0)
													{
														#########get message if capacity is full##########
														//redirect(base_url().'Dbf/refund/'.base64_encode($MerchantOrderNo));
													}
												}
											}
											$password=random_password();
											foreach($exam_admicard_details as $row)
											{
												$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'exam_date'=>$row['exam_date'],'session_time'=>$row['time'],'center_code'  => $row['center_code']));
												$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
												//echo $this->db->last_query().'<br>';
												$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$get_subject_details[0]['exam_date'],$get_subject_details[0]['session_time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
												if($seat_number!='')
												{
													$final_seat_number = $seat_number;
													$update_data = array('pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
													$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
												}
												else
												{
													$log_title ="Fail user seat allocation id:".$get_user_regnum[0]['member_regnumber'];
													$log_message = serialize($this->session->userdata['examinfo']['subject_arr']);
													$rId = $get_user_regnum[0]['member_regnumber'];
													$regNo = $get_user_regnum[0]['member_regnumber'];
													storedUserActivity($log_title, $log_message, $rId, $regNo);
													//redirect(base_url().'Dbf/refund/'.base64_encode($MerchantOrderNo));
												}
											}
											##############Get Admit card#############
											$admitcard_pdf=genarate_admitcard($get_user_regnum[0]['member_regnumber'],$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
											######update member_exam transaction######
											$update_data = array('pay_status' => '1');
											$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
										}
										else
										{
											//redirect(base_url().'Dbf/refund/'.base64_encode($MerchantOrderNo));
										}
										######update member_exam######	
										$update_data = array('pay_status' => '1');
										$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
										if($exam_info[0]['exam_mode']=='ON')
										{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
										{$mode='Offline';}
										else{$mode='';}
										//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
										$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
										$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
										//Query to get Medium	
										$this->db->where('exam_code',$exam_info[0]['exam_code']);
										$this->db->where('exam_period',$exam_info[0]['exam_period']);
										$this->db->where('medium_code',$exam_info[0]['exam_medium']);
										$this->db->where('medium_delete','0');
										$medium=$this->master_model->getRecords('medium_master','','medium_description');
										//Query to get Payment details	
										$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,id');
										$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
										$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
										$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
										$this->sms_template_id = 'P6tIFIwGR';
										$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
										$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
										$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
										$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
										$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
										$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
										$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
										$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
										$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
										$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
										$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
										$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
										$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
										$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
										$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
										$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
										$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
										$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
										$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
										$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring19);	
										$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
										if(count($elern_msg_string) > 0)
										{
											foreach($elern_msg_string as $row)
											{
												$arr_elern_msg_string[]=$row['exam_code'];
											}
											if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
											{
												$newstring21 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring20);		
											}
											else
											{
												$newstring21 = str_replace("#E-MSG#", '',$newstring20);		
											}
										}
										else
										{
											$newstring21 = str_replace("#E-MSG#", '',$newstring20);
										}
										$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
										$info_arr=array('to'=>$result[0]['email'],
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],
										'message'=>$final_str
										);
										//get invoice	
										$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
										//echo $this->db->last_query();exit;
										if(count($getinvoice_number) > 0)
										{
											/*if($getinvoice_number[0]['state_of_center']=='JAM')
												{
												$invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
												if($invoiceNumber)
												{
												$invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
												}
												}
												else
											{*/
											$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
											if($invoiceNumber)
											{
												$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
											}
											//}
											$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
											$this->db->where('pay_txn_id',$payment_info[0]['id']);
											$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
											$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
										}	
										if($attachpath!='')
										{						
											$files=array($attachpath,$admitcard_pdf);
											$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
											$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
											$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
											$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
											$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
											//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
											//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);	
											$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

											$this->Emailsending->mailsend_attch($info_arr,$files);
											//$this->Emailsending->mailsend($info_arr);
										}
									}
								}
								else
								{
									$log_title ="S2S Update fail:".$get_user_regnum[0]['member_regnumber'];
									$log_message = serialize($update_data);
									$rId = $MerchantOrderNo;
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);	
								}
							}
						}
						else if($payment_status==0)
						{
							// Handle transaction fail case 
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
							if($get_user_regnum[0]['status']==2)
							{
								$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' =>  $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
								//Query to get Payment details	
								$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
								// Handle transaction 
								$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
								//Query to get exam details	
								$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
								$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
								$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
								$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
								$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
								//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
								$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
								$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
								$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
								$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
								$this->sms_template_id = 'Jw6bOIQGg';
								$newstring1 = str_replace("#application_num#", "".$get_user_regnum[0]['member_regnumber']."",  $emailerstr[0]['emailer_text']);
								$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
								$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
								$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
								$info_arr=array(	'to'=>$result[0]['email'],
								'from'=>$emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'],
								'message'=>$final_str
								);
								$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
								$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
								$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
								// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
								//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
								$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

								$this->Emailsending->mailsend($info_arr);
							}
						}
					}		
					else if($pay_type == "iibfdra")
					{
						sleep(8);
						
						if($payment_status==1)
						{
							//get payment transaction id
							$transdetail_det = $this->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'status,id,date');
							if($transdetail_det[0]['status']==2)
							{
								$updated_date = date('Y-m-d H:i:s');
								$update_data = array('transaction_no' => $transaction_no,'status' => 1,'transaction_details' =>  $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
								$this->master_model->updateRecord('dra_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								if($this->db->affected_rows())
								{
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
													$currentpics = $this->master_model->getRecords('dra_members', array('regid'=>$regidformemref), 'scannedphoto, scannedsignaturephoto, idproofphoto, training_certificate, quali_certificate'); 									$scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $trainingphoto_file = $qualiphoto_file = '';
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
												//echo "<BR>dra_member_exam id = ".$uniqueid;
											}
										}
									}
									/*$updated_date = date('Y-m-d H:i:s');
										$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2], 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'description' => $responsedata[7], 'updated_date' => $updated_date, 'callback'=>'S2S');
									$this->master_model->updateRecord('dra_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));*/
									/******************* code added for GST Changes, by Bhagwan Sahane, on 05-07-2017 ***************/
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
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' =>  $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
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
					}
					else if ($pay_type == "iibfamp")
					{//die();
						sleep(8);
						
						if($payment_status==1)
						{
							$get_user_regnum_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'id,ref_id,status,payment_option ');
							//check user payment status is updated by s2s or not
							if($get_user_regnum_info[0]['status']==2)
							{
								if($get_user_regnum_info[0]['payment_option']==1 || $get_user_regnum_info[0]['payment_option']==4)
								{
									$reg_id=$get_user_regnum_info[0]['ref_id'];
									//$applicationNo = generate_mem_reg_num();
									//Get membership number from 'amp_membershipno' and update in 'amp_candidates'
									$applicationNo =generate_amp_memreg($reg_id);
									//update amp registration table
									$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
									$this->master_model->updateRecord('amp_candidates',$update_mem_data,array('id'=>$reg_id));
									//get user information...
									//$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id),'name,regnumber,email_id,photograph,signature,mobile_no,sponsor');
									$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id));
									$update_data = array('transaction_no' => $transaction_no,'status' =>1,'transaction_details' =>  $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
									$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
									//get payment details
									//Query to get Payment details	
									$payment_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$user_info[0]['regnumber']),'transaction_no,date,amount');
									$upd_files = array();
									$photo_file = 'p_'.$applicationNo.'.jpg';
									$sign_file = 's_'.$applicationNo.'.jpg';
									$proof_file = 'pr_'.$applicationNo.'.jpg';
									if(@ rename("./uploads/amp/photograph/".$user_info[0]['scannedphoto'],"./uploads/amp/photograph/".$photo_file))
									{	$upd_files['scannedphoto'] = $photo_file;	}
									if(@ rename("./uploads/amp/signature/".$user_info[0]['scannedsignaturephoto'],"./uploads/amp/signature/".$sign_file))
									{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
									if(count($upd_files)>0)
									{
										$this->master_model->updateRecord('amp_candidates',$upd_files,array('id'=>$reg_id));
									}
									//email to user
									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer'));
									$this->sms_template_id = 'N8YRKIwMg';
									if(count($emailerstr) > 0)
									{
										$username=$user_info[0]['name'];
										$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
										$newstring1 = str_replace("#REG_NUM#", "".$user_info[0]['regnumber']."",  $emailerstr[0]['emailer_text']);
										$newstring2 = str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring1);
										$newstring3 = str_replace("#EMAIL#", "".$user_info[0]['email_id']."",  $newstring2);
										$newstring4 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $newstring3);
										$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $newstring4);
										$newstring6 = str_replace("#STATUS#", "Transaction Successful",  $newstring5);
										$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring6);
										$info_arr=array('to'=>$user_info[0]['email_id'],
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'].' '.$user_info[0]['regnumber'],
										'message'=>$final_str,
										//'bcc'=>'skdutta@iibf.org.in,kavan@iibf.org.in'
										);
										$this->Emailsending->mailsend_attch($info_arr);
										//$this->send_mail($applicationNo);
										//$this->send_sms($applicationNo);
										//Manage Log
										//Invoice generation
										$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum_info[0]['id']));
										if(count($getinvoice_number) > 0)
										{
											$invoiceNumber = generate_amp_invoice_number($getinvoice_number[0]['invoice_id']);
											if($invoiceNumber)
											{
												$invoiceNumber=$this->config->item('amp_invoice_no_prefix').$invoiceNumber;
											}
											$update_data = array('invoice_no' => $invoiceNumber,'member_no'=>$applicationNo,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
											$this->db->where('pay_txn_id',$get_user_regnum_info[0]['id']);
											$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
											$attachpath=genarate_amp_invoice($getinvoice_number[0]['invoice_id']);	
										}
										if($attachpath!='')
										{	 
											$sms_newstring = str_replace("#fee#", "".$payment_info[0]['amount']."",  $emailerstr[0]['sms_text']);
											$sms_newstring1 = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring);
											$sms_final_str = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $sms_newstring1);
											//$this->master_model->send_sms($user_info[0]['mobile_no'],$sms_final_str);	
											//$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']),$sms_final_str,$this->sms_template_id);
											$this->master_model->send_sms_common_all($user_info[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

											if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
											{
												//email send to sk datta and kavan for self sponsor
												if($user_info[0]['sponsor']=='self')
												{
													$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_self'));
													$this->sms_template_id = 'N8YRKIwMg';
													if(count($emailerSelfStr) > 0){
														$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
														if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
														if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
														if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
														if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = ''; }
														if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
														if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
														$selfstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerSelfStr[0]['emailer_text']);
														$selfstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr1);
														$selfstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr2);
														$selfstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $selfstr3);
														$selfstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $selfstr4);
														$selfstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $selfstr5);
														$selfstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $selfstr6);
														$selfstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $selfstr7);
														$selfstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $selfstr8);
														$selfstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $selfstr9);
														$selfstr11 = str_replace("#phone_no#", "".$phone_no."",  $selfstr10);
														$selfstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $selfstr11);
														$selfstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $selfstr12);
														$selfstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $selfstr13);
														$selfstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $selfstr14);
														$selfstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $selfstr15);
														$selfstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $selfstr16);
														$selfstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $selfstr17);
														$selfstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $selfstr18);
														$selfstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $selfstr19);
														$selfstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $selfstr20);
														$selfstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $selfstr21);
														$selfstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $selfstr22);
														$selfstr24 = str_replace("#till_present#", "".$till_present."",  $selfstr23);
														$selfstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $selfstr24);
														$selfstr26 = str_replace("#payment#", "".$payment."",  $selfstr25);
														$selfstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $selfstr26);
														$selfstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $selfstr27);
														$selfstr29 = str_replace("#STATUS#", "Transaction Successful",  $selfstr28);
														$selfstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $selfstr29);
														$final_selfstr = str_replace("#sponsor#", "".$sponsor."",  $selfstr30);
														$self_mail_arr = array(
														'to'=>'ravita@iibf.org.in,training@iibf.org.in',
														'from'=>$emailerSelfStr[0]['from'],
														'subject'=>$emailerSelfStr[0]['subject'],
														'message'=>$final_selfstr,
														);
														//$this->Emailsending->mailsend($self_mail_arr);
														$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);
													}
												}
												//email send to sk datta and kavan for bank sponsor
												if($user_info[0]['sponsor']=='bank'){
													//get bank contact email id
													$contact_mail_id = $user_info[0]['sponsor_contact_email'];
													$emailerBankStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_bank'));
													$this->sms_template_id = 'N8YRKIwMg';
													if(count($emailerBankStr) > 0){
														$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
														if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
														if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
														if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
														if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = 'NO'; }
														if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
														if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
														if($user_info[0]['sponsor_contact_phone']!=0){ $sponsor_contact_phone = $user_info[0]['sponsor_contact_phone']; }else{ $sponsor_contact_phone = ''; }
														$bankstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerBankStr[0]['emailer_text']);
														$bankstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr1);
														$bankstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr2);
														$bankstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $bankstr3);
														$bankstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $bankstr4);
														$bankstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $bankstr5);
														$bankstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $bankstr6);
														$bankstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $bankstr7);
														$bankstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $bankstr8);
														$bankstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $bankstr9);
														$bankstr11 = str_replace("#phone_no#", "".$phone_no."",  $bankstr10);
														$bankstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $bankstr11);
														$bankstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $bankstr12);
														$bankstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $bankstr13);
														$bankstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $bankstr14);
														$bankstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $bankstr15);
														$bankstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $bankstr16);
														$bankstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $bankstr17);
														$bankstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $bankstr18);
														$bankstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $bankstr19);
														$bankstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $bankstr20);
														$bankstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $bankstr21);
														$bankstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $bankstr22);
														$bankstr24 = str_replace("#till_present#", "".$till_present."",  $bankstr23);
														$bankstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $bankstr24);
														$bankstr26 = str_replace("#payment#", "".$payment."",  $bankstr25);
														$bankstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $bankstr26);
														$bankstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $bankstr27);
														$bankstr29 = str_replace("#STATUS#", "Transaction Successful",  $bankstr28);
														$bankstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $bankstr29);
														$bankstr31 = str_replace("#sponsor#", "".$sponsor."",  $bankstr30);
														$bankstr32 = str_replace("#sponsor_bank_name#", "".$user_info[0]['sponsor_bank_name']."",  $bankstr31);
														$bankstr33 = str_replace("#sponsor_email#", "".$user_info[0]['sponsor_email']."",  $bankstr32);
														$bankstr34 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr33);
														$bankstr35 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr34);
														$bankstr36 = str_replace("#sponsor_contact_designation#", "".$user_info[0]['sponsor_contact_designation']."",  $bankstr35);
														$bankstr37 = str_replace("#sponsor_contact_std#", "".$user_info[0]['sponsor_contact_std']."",  $bankstr36);
														$bankstr38 = str_replace("#sponsor_contact_phone#", "".$sponsor_contact_phone."",  $bankstr37);
														$bankstr39 = str_replace("#sponsor_contact_mobile#", "".$user_info[0]['sponsor_contact_mobile']."",  $bankstr38);
														$final_bankstr = str_replace("#sponsor_contact_email#", "".$user_info[0]['sponsor_contact_email']."",  $bankstr39);
														$bank_mail_arr = array(
														//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in,prabhakara@iibf.org.in',
														//'to'=>'21bhavsartejasvi@gmail.com,'.$contact_mail_id.'',
														'to'=>'ravita@iibf.org.in,training@iibf.org.in,'.$contact_mail_id.'',
														'from'=>$emailerBankStr[0]['from'],
														'subject'=>$emailerBankStr[0]['subject'],
														'message'=>$final_bankstr,
														);
														//print_r($bank_mail_arr);exit;
														//$this->Emailsending->mailsend($bank_mail_arr);
														$this->Emailsending->mailsend_attch($bank_mail_arr,$attachpath);
													}
												}
												$this->session->set_flashdata('success','Amp registration has been done successfully !!');
												//redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
											}
											else
											{
												//redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
											}
										}
									}
								}
								else if($get_user_regnum_info[0]['payment_option']==2 || $get_user_regnum_info[0]['payment_option']==3)
								{	
									$payment_option='';
									if($get_user_regnum_info[0]['payment_option']== 2)
									{
										$payment_option='second';
									}
									else if($get_user_regnum_info[0]['payment_option']== 3)
									{
										$payment_option='Full';
									}
									$reg_id=$get_user_regnum_info[0]['ref_id'];
									//update amp registration table with installment status
									$update_mem_data = array('payment' =>$payment_option);
									$this->master_model->updateRecord('amp_candidates',$update_mem_data,array('id'=>$reg_id));
									//$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id),'name,regnumber,email_id,photograph,signature,mobile_no,sponsor');
									$user_info=$this->master_model->getRecords('amp_candidates',array('id'=>$reg_id));
									//update payment transaction
									$update_data = array('member_regnumber' => $user_info[0]['regnumber'],'sponsor'=>$user_info[0]['sponsor'],'transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0300', 'bankcode' =>$bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
									$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
									//maintain log in for updated transaction
									$log_title ="Installment payment";
									$update_info['membershipno'] = $user_info[0]['regnumber'];
									$log_message = serialize($update_mem_data);
									$this->Ampmodel->create_log($log_title, $log_message);
									//Query to get Payment details	
									$payment_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$user_info[0]['regnumber']),'transaction_no,date,amount');
									//email to user
									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer'));
									$this->sms_template_id = 'N8YRKIwMg';
									if(count($emailerstr) > 0)
									{
										$username=$user_info[0]['name'];
										$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
										$newstring1 = str_replace("#REG_NUM#", "".$user_info[0]['regnumber']."",  $emailerstr[0]['emailer_text']);
										$newstring2 = str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring1);
										$newstring3 = str_replace("#EMAIL#", "".$user_info[0]['email_id']."",  $newstring2);
										$newstring4 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $newstring3);
										$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $newstring4);
										$newstring6 = str_replace("#STATUS#", "Transaction Successful",  $newstring5);
										$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring6);
										$info_arr=array('to'=>$user_info[0]['email_id'],
										'from'=>$emailerstr[0]['from'],
										'subject'=>$emailerstr[0]['subject'].' '.$user_info[0]['regnumber'],
										'message'=>$final_str,
										//'bcc'=>'kumartupe@gmail.com,raajpardeshi@gmail.com'
										);
										//$this->send_mail($applicationNo);
										//$this->send_sms($applicationNo);
										//Invoice generation
										$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum_info[0]['id']));
										if(count($getinvoice_number) > 0)
										{
											$invoiceNumber = generate_amp_invoice_number($getinvoice_number[0]['invoice_id']);
											//	echo '<pre>',print_r($invoiceNumber),'</pre>';
											if($invoiceNumber)
											{
												$invoiceNumber=$this->config->item('amp_invoice_no_prefix').$invoiceNumber;
											}
											$update_data = array('invoice_no' => $invoiceNumber,'member_no'=>$user_info[0]['regnumber'],'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
											$this->db->where('pay_txn_id',$get_user_regnum_info[0]['id']);
											$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
											$attachpath=genarate_amp_invoice($getinvoice_number[0]['invoice_id']);
											//echo $this->db->last_query();
											//echo '<pre>update_data',print_r($update_data),'</pre>';
											//echo '<pre>',print_r($attachpath),'</pre>';
										}
										if($attachpath!='')
										{
											$sms_newstring = str_replace("#fee#", "".$payment_info[0]['amount']."",  $emailerstr[0]['sms_text']);
											$sms_newstring1 = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring);
											$sms_final_str = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $sms_newstring1);
											//$this->master_model->send_sms($user_info[0]['mobile_no'],$sms_final_str);	
											//$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']),$sms_final_str,$this->sms_template_id);
											$this->master_model->send_sms_common_all($user_info[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

											//$this->Emailsending->mailsend($info_arr);
											if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
											{
												//email send to sk datta and kavan for self sponsor
												if($user_info[0]['sponsor']=='self')
												{
													$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_self'));
													$this->sms_template_id = 'N8YRKIwMg';
													if(count($emailerSelfStr) > 0){
														$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
														if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
														if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
														if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
														if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = 'No'; }
														if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
														//echo $payment;exit;
														if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
														$selfstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerSelfStr[0]['emailer_text']);
														$selfstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr1);
														$selfstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $selfstr2);
														$selfstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $selfstr3);
														$selfstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $selfstr4);
														$selfstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $selfstr5);
														$selfstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $selfstr6);
														$selfstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $selfstr7);
														$selfstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $selfstr8);
														$selfstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $selfstr9);
														$selfstr11 = str_replace("#phone_no#", "".$phone_no."",  $selfstr10);
														$selfstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $selfstr11);
														$selfstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $selfstr12);
														$selfstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $selfstr13);
														$selfstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $selfstr14);
														$selfstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $selfstr15);
														$selfstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $selfstr16);
														$selfstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $selfstr17);
														$selfstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $selfstr18);
														$selfstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $selfstr19);
														$selfstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $selfstr20);
														$selfstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $selfstr21);
														$selfstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $selfstr22);
														$selfstr24 = str_replace("#till_present#", "".$till_present."",  $selfstr23);
														$selfstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $selfstr24);
														$selfstr26 = str_replace("#payment#", "".$payment."",  $selfstr25);
														$selfstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $selfstr26);
														$selfstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $selfstr27);
														$selfstr29 = str_replace("#STATUS#", "Transaction Successful",  $selfstr28);
														$selfstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $selfstr29);
														$final_selfstr = str_replace("#sponsor#", "".$sponsor."",  $selfstr30);
														$self_mail_arr = array(
														//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in,prabhakara@iibf.org.in',
														'to'=>'ravita@iibf.org.in,training@iibf.org.in',
														'from'=>$emailerSelfStr[0]['from'],
														'subject'=>$emailerSelfStr[0]['subject'],
														'message'=>$final_selfstr,
														);
														//echo '<pre>',print_r($self_mail_arr),'</pre>';
														$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);
													}
												}
												//email send to sk datta and kavan for bank sponsor
												if($user_info[0]['sponsor']=='bank'){
													$contact_mail_id = $user_info[0]['sponsor_contact_email'];
													$emailerBankStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'amp_emailer_bank'));
													$this->sms_template_id = 'N8YRKIwMg';
													if(count($emailerBankStr) > 0){
														$sponsor = ucwords($user_info[0]['sponsor']).' Sponsor';
														if($user_info[0]['phone_no']!=0){ $phone_no = $user_info[0]['phone_no']; }else{ $phone_no = ''; }
														if($user_info[0]['work_from_year']!=0){ $work_from_year = $user_info[0]['work_from_year']; }else{ $work_from_year = ''; }
														if($user_info[0]['work_to_year']!=0){ $work_to_year = $user_info[0]['work_to_year']; }else{ $work_to_year = ''; }
														if($user_info[0]['till_present']==1){ $till_present = 'Yes'; }else{ $till_present = ''; }
														if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }
														if($user_info[0]['work_experiance']!=0){ $work_experiance = $user_info[0]['work_experiance']; }else{ $work_experiance = ''; }
														if($user_info[0]['sponsor_contact_phone']!=0){ $sponsor_contact_phone = $user_info[0]['sponsor_contact_phone']; }else{ $sponsor_contact_phone = ''; }
														$bankstr1 = str_replace("#name#", "".$user_info[0]['name']."",  $emailerBankStr[0]['emailer_text']);
														$bankstr2 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr1);
														$bankstr3 = str_replace("#regnumber#", "".$user_info[0]['regnumber']."",  $bankstr2);
														$bankstr4 = str_replace("#dob#", "".$user_info[0]['dob']."",  $bankstr3);
														$bankstr5 = str_replace("#address1#", "".$user_info[0]['address1']."",  $bankstr4);
														$bankstr6 = str_replace("#address2#", "".$user_info[0]['address2']."",  $bankstr5);
														$bankstr7 = str_replace("#address3#", "".$user_info[0]['address3']."",  $bankstr6);
														$bankstr8 = str_replace("#address4#", "".$user_info[0]['address4']."",  $bankstr7);
														$bankstr9 = str_replace("#pincode_address#", "".$user_info[0]['pincode_address']."",  $bankstr8);
														$bankstr10 = str_replace("#std_code#", "".$user_info[0]['std_code']."",  $bankstr9);
														$bankstr11 = str_replace("#phone_no#", "".$phone_no."",  $bankstr10);
														$bankstr12 = str_replace("#mobile_no#", "".$user_info[0]['mobile_no']."",  $bankstr11);
														$bankstr13 = str_replace("#email_id#", "".$user_info[0]['email_id']."",  $bankstr12);
														$bankstr14 = str_replace("#alt_email_id#", "".$user_info[0]['alt_email_id']."",  $bankstr13);
														$bankstr15 = str_replace("#graduation#", "".$user_info[0]['graduation']."",  $bankstr14);
														$bankstr16 = str_replace("#post_graduation#", "".$user_info[0]['post_graduation']."",  $bankstr15);
														$bankstr17 = str_replace("#special_qualification#", "".$user_info[0]['special_qualification']."",  $bankstr16);
														$bankstr18 = str_replace("#name_employer#", "".$user_info[0]['name_employer']."",  $bankstr17);
														$bankstr19 = str_replace("#position#", "".$user_info[0]['position']."",  $bankstr18);
														$bankstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $bankstr19);
														$bankstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $bankstr20);
														$bankstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $bankstr21);
														$bankstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $bankstr22);
														$bankstr24 = str_replace("#till_present#", "".$till_present."",  $bankstr23);
														$bankstr25 = str_replace("#work_experiance#", "".$work_experiance."",  $bankstr24);
														$bankstr26 = str_replace("#payment#", "".$payment."",  $bankstr25);
														$bankstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $bankstr26);
														$bankstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $bankstr27);
														$bankstr29 = str_replace("#STATUS#", "Transaction Successful",  $bankstr28);
														$bankstr30 = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $bankstr29);
														$bankstr31 = str_replace("#sponsor#", "".$sponsor."",  $bankstr30);
														$bankstr32 = str_replace("#sponsor_bank_name#", "".$user_info[0]['sponsor_bank_name']."",  $bankstr31);
														$bankstr33 = str_replace("#sponsor_email#", "".$user_info[0]['sponsor_email']."",  $bankstr32);
														$bankstr34 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr33);
														$bankstr35 = str_replace("#sponsor_contact_person#", "".$user_info[0]['sponsor_contact_person']."",  $bankstr34);
														$bankstr36 = str_replace("#sponsor_contact_designation#", "".$user_info[0]['sponsor_contact_designation']."",  $bankstr35);
														$bankstr37 = str_replace("#sponsor_contact_std#", "".$user_info[0]['sponsor_contact_std']."",  $bankstr36);
														$bankstr38 = str_replace("#sponsor_contact_phone#", "".$sponsor_contact_phone."",  $bankstr37);
														$bankstr39 = str_replace("#sponsor_contact_mobile#", "".$user_info[0]['sponsor_contact_mobile']."",  $bankstr38);
														$final_bankstr = str_replace("#sponsor_contact_email#", "".$user_info[0]['sponsor_contact_email']."",  $bankstr39);
														$bank_mail_arr = array(
														//'to'=>'skdatta@iibf.org.in,kavan@iibf.org.in',
														'to'=>'ravita@iibf.org.in,training@iibf.org.in,'.$contact_mail_id.'',
														'from'=>$emailerBankStr[0]['from'],
														'subject'=>$emailerBankStr[0]['subject'],
														'message'=>$final_bankstr,
														);
														$this->Emailsending->mailsend_attch($bank_mail_arr,$attachpath);
													}
												}
											} 
										}
										//Manage Log
										$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
										$this->log_model->logamptransaction("billdesk", $pg_response, $transaction_error_type);
										//redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
									}
									else
									{
										//redirect(base_url().'Amp/details/'.base64_encode($MerchantOrderNo));
									}
								}
							}
						}
						else if($payment_status==0)
						{
							$get_user_regnum_info=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status');
							if($get_user_regnum_info[0]['status']==2)
							{
								$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' =>  $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
								$this->master_model->updateRecord('amp_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								//Manage Log
								$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
								$this->log_model->logamptransaction("billdesk", $pg_response, $transaction_error_type);
							}
						}
					}
					else if($pay_type == "iibfmisc")
					{
						sleep(8);
						
						if($payment_status==1)
						{
							$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id','','','1');
							if ($get_user_regnum_info[0]['status'] == 2) 
							{
								$gst_recovery_details_pk = $get_user_regnum_info[0]['ref_id'];
								$member_regnumber        = $get_user_regnum_info[0]['member_regnumber'];
								$update_data             = array(
								'transaction_no' => $transaction_no,
								'status' => 1,
								'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
								'auth_code' => '0300',
								'bankcode' => $bankid,
								'paymode' => $txn_process_type,
								'callback' => 'S2S'
								);
								$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
								/* Transaction Log */
								$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
								$this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);
								/* Update Pay Status */
								$gst_recovery_details_data = array('pay_status' => 1,'modified_on' => date('Y-m-d H:i:s'));
								$this->master_model->updateRecord('gst_recovery_details', $gst_recovery_details_data, array( 'gst_recovery_details_pk' => $gst_recovery_details_pk,'member_no' => $member_regnumber));
								/* Update Pay Status */
								$gst_recovery_master_data = array('pay_status' => 1,'modified_on' => date('Y-m-d H:i:s'));
								$this->master_model->updateRecord('gst_recovery_master', $gst_recovery_master_data, array('member_no' => $member_regnumber));
								/* Email */
								$get_exam_period = $this->master_model->getRecords('gst_recovery_details', array('gst_recovery_details_pk' => $get_user_regnum_info[0]['ref_id']), 'exam_period','','','');
								if($get_exam_period[0]['exam_period'] == '552')
								{
									$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_bcbf_gst_recovery_email'),'','','1');
									$this->sms_template_id = 'NA';
								}
								else
								{
									$emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_gst_recovery_email'),'','','1');
									$this->sms_template_id = 'NA';
								}
								if (!empty($member_regnumber)) 
								{
									$user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $member_regnumber, 'isactive' => '1'), 'email,mobile','','','1');
								}
								if (count($emailerstr) > 0) 
								{ 
									/* Set Email sending options */
									$info_arr   = array(
									'to' => $user_info[0]['email'],
									'from' => $emailerstr[0]['from'],
									'subject' => $emailerstr[0]['subject'],
									'message' => $emailerstr[0]['emailer_text']
									);
									/* Invoice Number Genarate Functinality */
									if ($gst_recovery_details_pk != '') 
									{
										if($get_exam_period[0]['exam_period'] == '552')
										{
											$invoiceNumber = "";
											$update_data_invoice = array();
										}
										else
										{
											$invoiceNumber = generate_gst_recovery_invoice_number($gst_recovery_details_pk);
										}
										if ($invoiceNumber != '') 
										{
											$invoiceNumber = $this->config->item('gst_recovery_prefix') . $invoiceNumber;
											$update_data_invoice = array('doc_no' => $invoiceNumber,'date_of_doc' => date('Y-m-d H:i:s'));
											$this->db->where('gst_recovery_details_pk', $gst_recovery_details_pk);
											$this->master_model->updateRecord('gst_recovery_details', $update_data_invoice, array('modified_on' => date('Y-m-d H:i:s')));
										}
										/* Invoice Create Function */
										$get_exam_period = $this->master_model->getRecords('gst_recovery_details', array('gst_recovery_details_pk' => $gst_recovery_details_pk), 'exam_period','','','');
										if($get_exam_period[0]['exam_period'] == '552')
										{
											$attachpath  = '';
											$log_title   = "GST Recovery - BCBF - sbitranssuccess function";
										}
										else
										{
											$attachpath  = genarate_gst_recovery_invoice($gst_recovery_details_pk);
											$log_title   = "GST Recovery-Invoice Genarate";
										}
										/* User Log Activities  */
										$log_message = serialize($update_data_invoice);
										$rId         = $gst_recovery_details_pk;
										$regNo       = $member_regnumber;
										storedUserActivity($log_title, $log_message, $rId, $regNo);
									}
									if ($attachpath != '') 
									{
										if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) 
										{
											//redirect(base_url() . 'GstRecovery/acknowledge/');
										} 
										else 
										{ 
											//redirect(base_url() . 'GstRecovery/acknowledge/');
										}
									} 
									else 
									{
										if($get_exam_period[0]['exam_period'] == '552')
										{
											if ($this->Emailsending->mailsend($info_arr)) 
											{
												$anil_info_arr   = array(  
												'to' => 'anil@iibf.org.in',
												'from' => $emailerstr[0]['from'],
												'subject' => "BCBF 8 Rs Fee Paid",
												'message' => "Member No.".$member_regnumber);
												$this->Emailsending->mailsend($anil_info_arr);
												//redirect(base_url() . 'GstRecovery/bcbf_acknowledge/');
											} 
										}
										//redirect(base_url() . 'GstRecovery/acknowledge/');
									}
								}
							}
						}
					}
					else if ($pay_type == "iibftrg")
					{
						sleep(8);
						
						if($payment_status==1)
						{
							$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id');
							//check user payment status is updated by S2S or not
							if ($get_user_regnum_info[0]['status'] == 2) 
							{
								$reg_id        = $get_user_regnum_info[0]['ref_id'];
								$applicationNo = $get_user_regnum_info[0]['member_regnumber'];
								$update_data   = array(
								//'member_regnumber' => $applicationNo,
								'transaction_no' => $transaction_no,
								'status' => 1,
								'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
								'auth_code' => '0300',
								'bankcode' => $bankid,
								'paymode' => $txn_process_type,
								'callback' => 'S2S'
								);
								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								$reg_data = $this->master_model->getRecords('blended_registration', array('member_no' => $applicationNo,'blended_id' => $reg_id),'program_code,center_code,batch_code,training_type,venue_code,start_date');
								$selected_program_code = $reg_data[0]['program_code'];
								$selected_center_code = $reg_data[0]['center_code'];
								$venue_batch_code = $reg_data[0]['batch_code'];
								$selected_training_type = $reg_data[0]['training_type'];
								$selected_venue_code	= $reg_data[0]['venue_code'];		
								$sDate = $reg_data[0]['start_date'];
								/* Check Registration Capacity */
								$RegCount = "";
								$RegCount = blendedRegistrationCapacity($selected_program_code,$selected_center_code,$venue_batch_code,$selected_training_type,$selected_venue_code,$sDate);
								/* Get Venue Capacity */
								$capacity = "";
								$capacity = getVenueCapacity($selected_program_code,$selected_center_code,$venue_batch_code,$selected_training_type,$selected_venue_code,$sDate);
								/* Get User Attempt */
								$attemptQry=$this->db->query("SELECT attempt,fee_flag FROM blended_eligible_master WHERE member_number='".$applicationNo."' AND program_code = '" . $selected_program_code . "' LIMIT 1"); 
								$attemptArr = $attemptQry->row_array();
								$attempt = $attemptArr['attempt'];
								$fee_flag=$attemptArr['fee_flag'];
								/* Check Count of Vitual Attempts */
								$VitualAttemptsCount = "";
								$VitualAttemptsCount = getVitualAttemptsCounts($applicationNo,$selected_program_code,$venue_batch_code);
								if($VitualAttemptsCount != 0)
								{
									$attempt = 1;
								}
								$attempt = $attempt+1;
								if($RegCount >= $capacity)
								{
									// Refundable
									$blended_data = array('pay_status' => 3, 'attempt'=>$attempt, 'modify_date' => date('Y-m-d H:i:s'));
									$this->master_model->updateRecord('blended_registration',$blended_data,array('blended_id'=>$reg_id));
									/* User Log Activities  */
									$log_title ="Blended Course Registraion - Capacity is full after payment success.";
									$log_message = $log_message = 'Program Code : '.$selected_program_code;
									$rId = $reg_id;
									$regNo = $applicationNo;
									storedUserActivity($log_title, $log_message, $rId, $regNo);
								}
								/* Update Pay Status and User Attemp Status */
								$blended_data = array('pay_status'=>1, 'attempt'=>$attempt, 'modify_date'=>date('Y-m-d H:i:s'));
								$this->master_model->updateRecord('blended_registration',$blended_data,array('blended_id'=>$reg_id));
								$log_title ="Blended Course Registraion update query.".$this->db->last_query();
								$log_message = serialize($blended_data);
								$rId = $reg_id;
								$regNo = $reg_id;
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_blended_email'));
								$this->sms_template_id = 'Xb5EFSwGg';
								if (!empty($applicationNo)) {
									$user_info = $this->master_model->getRecords('member_registration',array('regnumber'=> $applicationNo,'isactive'=>'1'),'email,mobile');
								}
								if (count($emailerstr) > 0) 
								{
									/* Set Email Content For user */
									$Qry=$this->db->query("SELECT program_code, program_name, training_type, center_name, venue_name, start_date, end_date,member_no FROM blended_registration WHERE blended_id = '".$reg_id."' LIMIT 1");
									$detailsArr        = $Qry->row_array();
									$program_code = $detailsArr['program_code'];
									$program_name = $detailsArr['program_name'];
									$training_type = $detailsArr['training_type'];
									if($training_type=="PC"){
										$training_type='Physical Classroom';
										$venue_name   = $detailsArr['venue_name'];
									}
									else{
										$training_type='Virtual Classes';
										$venue_name   = '-';
									}
									$center_name  = $detailsArr['center_name'];
									$start_date1  = $detailsArr['start_date'];
									$end_date1    = $detailsArr['end_date'];
									$start_date   = date("d-M-Y", strtotime($start_date1));
									$end_date     = date("d-M-Y", strtotime($end_date1));
									$newstring    = str_replace("#program_name#","".$program_name."",$emailerstr[0]['emailer_text']);
									$newstring1   = str_replace("#training_type#","".$training_type."",$newstring);
									$newstring2   = str_replace("#center_name#","".$center_name."",$newstring1);
									$newstring3   = str_replace("#venue_name#","".$venue_name."",$newstring2);
									$newstring4   = str_replace("#start_date#","".$start_date."",$newstring3);
									$newstring5   = str_replace("#end_date#", "".$end_date."",$newstring4);
									/* Set Email sending options */
									$info_arr          = array(
									'to' => $user_info[0]['email'],
									'from' => $emailerstr[0]['from'],
									'subject' => $emailerstr[0]['subject'].' '.$detailsArr['member_no'],
									'message' => $newstring5
									);
									$getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo,'pay_txn_id' => $get_user_regnum_info[0]['id']));
									$zone_code = ""; 
									$zoneArr = array();
									//$regno = $this->session->userdata['memberdata']['regno'];
									$zoneArr = $this->master_model->getRecords('blended_registration',array('blended_id'=>$reg_id,'pay_status'=>1),'zone_code,gstin_no');
									$zone_code = $zoneArr[0]['zone_code'];
									$gstin_no          = $zoneArr[0]['gstin_no'];
									/* Invoice Number Genarate Functinality */
									if (count($getinvoice_number) > 0){
										$invoiceNumber = generate_blended_invoice_number($getinvoice_number[0]['invoice_id'],$zone_code);
										if($invoiceNumber){$invoiceNumber = $this->config->item('blended_invoice_T'.$zone_code.'_prefix').$invoiceNumber;}
										$update_data = array(
										'invoice_no' => $invoiceNumber,
										//'member_no' => $applicationNo,
										'transaction_no' => $transaction_no,
										'date_of_invoice' => date('Y-m-d H:i:s'),
										'modified_on' => date('Y-m-d H:i:s')
										);
										$this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
										$this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
										/* Invoice Genarate Function */
										$attachpath = genarate_blended_invoice($getinvoice_number[0]['invoice_id'],$zone_code,$program_name,$gstin_no);
										/* User Log Activities  */
										$log_title ="Blended Course Registration-Invoice Genarate";
										$log_message = serialize($update_data);
										$rId = $reg_id;
										$regNo = $applicationNo;
										storedUserActivity($log_title, $log_message, $rId, $regNo);
									}
									if ($attachpath != '') 
									{	
										/* Email Send To Clints */
										if (!empty($applicationNo)) {
											$reg_info = $this->master_model->getRecords('blended_registration',array('member_no'=> $applicationNo,'blended_id' => $reg_id));
										}
										$payment_infoArr=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$reg_info[0]['member_no']),'transaction_no,date,amount');
										if($reg_info[0]['member_no'] == $applicationNo)
										{
											$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'blended_emailer_client'));
											$this->sms_template_id = 'NA';
											if(count($emailerSelfStr) > 0)
											{
												$designation_name=$undergraduate_name=$graduate_name=$postgraduate_name=$specify_qualification=$institution_name=$pay="";
												$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
												$graduate=$this->master_model->getRecords('qualification', array('type' => 'GR'));
												$postgraduate=$this->master_model->getRecords('qualification', array('type'=> 'PG'));
												$institution_master = $this->master_model->getRecords('institution_master');
												$states             = $this->master_model->getRecords('state_master');
												$designation        = $this->master_model->getRecords('designation_master');
												if(count($designation)){
													foreach($designation as $designation_row){
														if($reg_info[0]['designation']==$designation_row['dcode']){
														$designation_name = $designation_row['dname'];}
													} 
												}
												if(count($institution_master)){
													foreach($institution_master as $institution_row){ 	
														if($reg_info[0]['associatedinstitute']==$institution_row['institude_id']){
														$institution_name = $institution_row['name'];}
													}
												}
												if($reg_info[0]['qualification']=='U'){$undergraduate_name = 'Under Graduate';}
												if($reg_info[0]['qualification']=='G'){$graduate_name = 'Graduate';}
												if($reg_info[0]['qualification']=='P'){$postgraduate_name = 'Post Graduate';}	
												$qualificationArr=$this->master_model->getRecords('qualification',array('qid'=>$reg_info[0]['specify_qualification']),'name','','1');
												if(count($qualificationArr)) 
												{
													$specify_qualification = $qualificationArr[0]['name'];
												}
												$training_type = $reg_info[0]['training_type'];
												if($training_type=="PC")
												{
													$training_type='Physical Classroom';
													$venue_name   = $reg_info[0]['venue_name'];
												}
												else
												{
													$training_type='Virtual Classes';
													$venue_name   = "-";
												}
												$center_name  = $reg_info[0]['center_name'];
												$start_date   = date("d-M-Y", strtotime($reg_info[0]['start_date']));
												$end_date     = date("d-M-Y", strtotime($reg_info[0]['end_date']));
												if($reg_info[0]['pay_status'] == 1){ $pay = "Success";}
												$selfstr1 = str_replace("#regnumber#", "".$reg_info[0]['member_no']."",  $emailerSelfStr[0]['emailer_text']);
												$selfstr2 = str_replace("#program_name#", "".$reg_info[0]['program_name']."",  $selfstr1);
												$selfstr3 = str_replace("#center_name#", "".$center_name."",  $selfstr2);
												$selfstr4 = str_replace("#venue_name#", "".$venue_name."", $selfstr3);
												$selfstr5 = str_replace("#start_date#", "".$start_date."", $selfstr4);
												$selfstr6 = str_replace("#end_date#", "".$end_date."",  $selfstr5);
												$selfstr7 = str_replace("#training_type#", "".$training_type."",  $selfstr6);	
												$selfstr8 = str_replace("#name#", "".$reg_info[0]['namesub']." ".$reg_info[0]['firstname']." ".$reg_info[0]['middlename']." ".$reg_info[0]['lastname'],  $selfstr7);
												$selfstr9 = str_replace("#address1#", "".$reg_info[0]['address1']."",  $selfstr8);
												$selfstr10 = str_replace("#address2#", "".$reg_info[0]['address2']."",  $selfstr9);
												$selfstr11 = str_replace("#address3#", "".$reg_info[0]['address3']."",  $selfstr10);
												$selfstr12 = str_replace("#address4#", "".$reg_info[0]['address4']."",  $selfstr11);
												$selfstr13 = str_replace("#district#", "".$reg_info[0]['district']."",  $selfstr12);
												$selfstr14 = str_replace("#city#", "".$reg_info[0]['city']."",  $selfstr13);
												$selfstr15 = str_replace("#state#", "".$reg_info[0]['state']."",  $selfstr14);
												$selfstr16 = str_replace("#pincode#", "".$reg_info[0]['pincode']."",  $selfstr15);
												$selfstr17 = str_replace("#designation#", "".$designation_name."",  $selfstr16);
												$selfstr18 = str_replace("#associatedinstitute#", "".$institution_name."",  $selfstr17);
												$selfstr19 = str_replace("#dateofbirth#", "".$reg_info[0]['dateofbirth']."",  $selfstr18);
												$selfstr20 = str_replace("#email#", "".$reg_info[0]['email']."",  $selfstr19);
												$selfstr21 = str_replace("#mobile#", "".$reg_info[0]['mobile']."",  $selfstr20);
												$selfstr22 = str_replace("#res_stdcode#", "".$reg_info[0]['res_stdcode']."",  $selfstr21);
												$selfstr23 = str_replace("#residential_phone#", "".$reg_info[0]['residential_phone']."",  $selfstr22);
												$selfstr24 = str_replace("#stdcode#", "".$reg_info[0]['stdcode']."",  $selfstr23);
												$selfstr25 = str_replace("#office_phone#", "".$reg_info[0]['office_phone']."",  $selfstr24);
												$selfstr26 = str_replace("#qualification#", "".$undergraduate_name.$graduate_name.$postgraduate_name."",  $selfstr25);
												$selfstr27 = str_replace("#specify_qualification#", "".$specify_qualification."",  $selfstr26);
												$selfstr28 = str_replace("#emergency_name#", "".$reg_info[0]['emergency_name']."",  $selfstr27);
												$selfstr29 = str_replace("#emergency_contact_no#", "".$reg_info[0]['emergency_contact_no']."",  $selfstr28);
												$selfstr30 = str_replace("#blood_group#", "".$reg_info[0]['blood_group']."",  $selfstr29);
												$selfstr31 = str_replace("#TRANSACTION_NO#", "".$payment_infoArr[0]['transaction_no']."",  $selfstr30);
												$selfstr32 = str_replace("#AMOUNT#", "".$payment_infoArr[0]['amount']."",  $selfstr31);
												$selfstr33 = str_replace("#STATUS#", "Transaction Successful",  $selfstr32);
												$final_selfstr = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_infoArr[0]['date']))."",  $selfstr33);
												$s1 = str_replace("#program_code#", "".$reg_info[0]['program_code'],$emailerSelfStr[0]['subject']);
												$final_sub = str_replace("#Batch_code#", "".$reg_info[0]['batch_code']."",  $s1);
												/* Get Client Emails Details */
												$emailsQry = $this->db->query("SELECT emails FROM offline_email_master WHERE zone_code = '" . $reg_info[0]['zone_code'] . "' AND program_code = '" . $reg_info[0]['program_code'] . "' AND batch_code = '" . $reg_info[0]['batch_code'] . "'AND isdelete = 0 LIMIT 1 ");
												$emailsArr    = $emailsQry->row_array();
												$emails  = $emailsArr['emails'];	
												$self_mail_arr = array(
												'to'=>$emails,
												'from'=>$emailerSelfStr[0]['from'],
												'subject'=> $final_sub,
												'message'=>$final_selfstr);	
												$this->Emailsending->mailsend_attch($self_mail_arr,$attachpath);
											}
										}
										/* SMS Sending Code */
										$sms_newstring = str_replace("#program_name#", "" . $program_name . "", $emailerstr[0]['sms_text']);
										// $this->master_model->send_sms($user_info[0]['mobile'], $sms_newstring);
										//$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']),$sms_final_str,$this->sms_template_id);
										$this->master_model->send_sms_common_all($user_info[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

										if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
										} 
									} 
								}
							}
						}	
						else if($payment_status==0)
						{
							$get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status');
							if($get_user_regnum_info[0]['status']==2)
							{
								$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' =>  $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							}
						}
					}
					else if($pay_type == "IIBFDRAREG")
					{
						sleep(8);
						
						//Payment Success
						if($payment_status==1)
						{
							// Handle transaction success case                    
							$get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status,id');
							if ($get_user_regnum[0]['status'] == 2)
							{
								if (count($get_user_regnum) > 0)
								{
									// Get agency_id from agency_center table
									$agency_center_info = $this->master_model->getRecords('agency_center', array(
									'center_id' => $get_user_regnum[0]['ref_id']
									), 'agency_id,center_id,institute_code');
								}
								// Get Details from dra_inst_registration table for email and center type
								if(count($agency_center_info) > 0){   
									$user_info = $this->master_model->getRecords('dra_inst_registration', array(
									'id' => $agency_center_info[0]['agency_id']
									), 'id,email_id,inst_head_email,center_type');            
									$email_id = $user_info[0]['inst_head_email'];
								}                       
								$update_data = array(
								'transaction_no' => $transaction_no,
								'status' => 1,
								'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
								'auth_code' => '0300',
								'bankcode' => $bankid,
								'paymode' => $txn_process_type,
								'callback' => 'S2S'
								);
								$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
								$agency_id = $agency_center_info[0]['agency_id'];
								$center_id = $agency_center_info[0]['center_id'];
								$institute_code = $agency_center_info[0]['institute_code'];
								if($this->db->affected_rows())
								{   
									if($institute_code != 0) // Conditions for only center add (After Login)
									{
										$validate_upto = '';
										if ($user_info[0]['center_type'] == 'T')
										{
											$created_on    = date('Y-m-d H:i:s');
											$validate_upto = date('Y-m-d H:i:s', strtotime('+3 months', strtotime($created_on)));
										}
										$update_data = array('pay_status' => '1');
										$this->master_model->updateRecord('agency_center', $update_data, array('center_id' => $agency_id));
									}
									else // Conditions for only Agency + center add(Outer Registration)
									{
										/* Payment Status Updates */
										$update_data1 = array('status' => '1');
										$this->master_model->updateRecord('dra_inst_registration',$update_data1,array('id'=>$agency_id));
										$update_data2 = array('pay_status' => '1','center_status'=>'A');
										$this->master_model->updateRecord('agency_center',$update_data2, array('center_id'=>$center_id));
										$update_data3 = array('pay_status' => '1');
										$this->master_model->updateRecord('dra_accerdited_master', $update_data3, array('dra_inst_registration_id' => $agency_id));
										$check_status = $this->master_model->getRecords('dra_accerdited_master',array('dra_inst_registration_id' => $agency_id,'pay_status' => '1'),'pay_status');
										if($check_status[0]['pay_status'] == '1')
										{
											$update_data4 = array('center_id' => $center_id);
											$last_id = $this->master_model->insertRecord('config_institute_code', $update_data4, true);      
											/* Get last Inst. Code */
											$institute_code = $last_id;
											if($institute_code != "" && $institute_code > 0)
											{
												/* Add Inst. Code  in agency_center and dra_accerdited_master */
												$update_data = array('institute_code' => $institute_code);
												$this->master_model->updateRecord('agency_center', $update_data, array('center_id' => $center_id));
												$update_data = array('institute_code' => $institute_code);
												$this->master_model->updateRecord('dra_accerdited_master', $update_data, array('dra_inst_registration_id' => $agency_id));
											}
										}
									}
									//$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
									$emailerstr = $this->master_model->getRecords('emailer', array(
									'emailer_name' => 'dra_institute'
									));
									$this->sms_template_id = 'NA';
									if (count($emailerstr) > 0 && (count($get_user_regnum) > 0)) {
										$final_str = $emailerstr[0]['emailer_text'];                           
										$info_arr  = array(
										'to' => $email_id,
										'from' => $emailerstr[0]['from'],
										'subject' => $emailerstr[0]['subject'],
										'message' => $final_str
										);
										//genertate invoice and email send with invoice attach 8-7-2017                   
										//get invoice   
										$getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
										'receipt_no' => $MerchantOrderNo,
										'pay_txn_id' => $get_user_regnum[0]['id']
										));
										if (count($getinvoice_number) > 0) {
											$invoiceNumber = generate_dra_invoice_number($getinvoice_number[0]['invoice_id']);
											if ($invoiceNumber) {
												$invoiceNumber = $this->config->item('DRA_invoice_no_prefix') . $invoiceNumber;
											}
											$update_data = array(
											'invoice_no' => $invoiceNumber,
											'transaction_no' => $transaction_no,
											'date_of_invoice' => date('Y-m-d H:i:s'),
											'modified_on' => date('Y-m-d H:i:s')
											);
											$this->db->where('pay_txn_id', $get_user_regnum[0]['id']);
											$this->master_model->updateRecord('exam_invoice', $update_data, array(
											'receipt_no' => $MerchantOrderNo
											));
											$attachpath = genarate_dra_invoice($getinvoice_number[0]['invoice_id']);
										}
										if ($attachpath != '') {
											if ($this->Emailsending->mailsend_attch_DRA($info_arr, $attachpath)) {
											}
										}
									}
								}                   
							}                   
						}
						else if($payment_status==0)
						{//Payment Fail                   
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' =>  $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');       
							// Handle transaction fail case
							$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(
							'receipt_no' => $MerchantOrderNo
							), 'ref_id,status');
						}
					}
					else if($pay_type == "IIBFDRAREN")
					{
						sleep(8);
						//Added by manoj MMM for agency center Renewal 
						
						//Payment Success
						if($payment_status==1)
						{
							// Handle transaction success case 					
							$get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status,id');
							if ($get_user_regnum[0]['status'] == 2) 
							{
								$update_data = array(
								'transaction_no' => $transaction_no,
								'status' => 1,
								'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
								'auth_code' => '0300',
								'bankcode' => $bankid,
								'paymode' => $txn_process_type,
								'callback' => 'S2S'
								);
								$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
								if($this->db->affected_rows())
								{	
									if (count($get_user_regnum) > 0) 
									{
										$renew_info = $this->master_model->getRecords('agency_center_renew', array(
										'agency_renew_id' => $get_user_regnum[0]['ref_id']
										), 'agency_id');
									}
									if(count($renew_info) > 0){
										$user_info = $this->master_model->getRecords('dra_inst_registration', array(
										'id' => $renew_info[0]['agency_id']
										), 'inst_head_email');	
										$email_id = $user_info[0]['inst_head_email'];
									}						
									// Get email of institute by its id                        
									$update_agency_pay_statues = array('pay_status' => '1');
									$this->master_model->updateRecord('agency_center_renew', $update_agency_pay_statues, array(
									'agency_renew_id' => $get_user_regnum[0]['ref_id']
									));
									//================== NEW CODE ADDED TO update PAY status  BY Manoj============
									$agency_info = $this->master_model->getRecords('agency_center_renew', array(
									'agency_renew_id' => $get_user_regnum[0]['ref_id']
									));	
									$agency_id 	= $agency_info[0]['agency_id']; 					
									$center_ids = $agency_info[0]['centers_id']; 
									$center_arr = explode(',',$center_ids);
									// ADD CODE TO SET PAY STATUS 1: SUCCESS  
									$update_data = array('center_status' => 'A','pay_status'  => '1');					
									foreach($center_arr as $center_id){
										$this->master_model->updateRecord('agency_center', $update_data, array(
										'center_id' => $center_id
										));
									}
									//===============================================================================						
									//$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
									$emailerstr = $this->master_model->getRecords('emailer', array(
									'emailer_name' => 'dra_agency_renew'
									));
									$this->sms_template_id = 'NA';
									if (count($emailerstr) > 0 && (count($get_user_regnum) > 0)) {
										$final_str = $emailerstr[0]['emailer_text'];							
										$info_arr  = array(
										'to' => $email_id,
										'from' => $emailerstr[0]['from'],
										'subject' => $emailerstr[0]['subject'],
										'message' => $final_str
										);
										//genertate invoice and email send with invoice attach 8-7-2017                    
										//get invoice    
										$getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
										'receipt_no' => $MerchantOrderNo,
										'pay_txn_id' => $get_user_regnum[0]['id']
										));
										if (count($getinvoice_number) > 0) {
											$invoiceNumber = generate_agnecy_renewal_invoice_number($getinvoice_number[0]['invoice_id']);
											if ($invoiceNumber) {
												$invoiceNumber = $this->config->item('DRA_agency_renew_invoice_no_prefix') . $invoiceNumber;
											}
											$update_data = array(
											'invoice_no' => $invoiceNumber,
											'transaction_no' => $transaction_no,
											'date_of_invoice' => date('Y-m-d H:i:s'),
											'modified_on' => date('Y-m-d H:i:s')
											);
											$this->db->where('pay_txn_id', $get_user_regnum[0]['id']);
											$this->master_model->updateRecord('exam_invoice', $update_data, array(
											'receipt_no' => $MerchantOrderNo
											));
											$attachpath = genarate_agnecy_renewal_invoice($getinvoice_number[0]['invoice_id']);
										}
										if ($attachpath != '') {
											if ($this->Emailsending->mailsend_attch_DRA($info_arr, $attachpath)) {
											} 
										} 
									}
								}					
							}					
						}
						else if($payment_status==0)
						{//Payment Fail					
							$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status');
							if ($get_user_regnum_info[0]['status'] != 0 && $get_user_regnum_info[0]['status'] == 2) 
							{
								$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' =>  $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
								$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
							}
						}
					}
					else if($pay_type == "IIBF_EL")
					{
						sleep(8);
						
						if($payment_status==1)
						{	
							$exam_period_date='';
							//Handle transaction success case
							$elective_subject_name='';
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
							if($get_user_regnum[0]['status']==2)
							{
								######### payment Transaction ############
								$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
								$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
								if($this->db->affected_rows())
								{
									if(count($get_user_regnum) > 0)
									{
										$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
									}
									//Query to get exam details	
									$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
									$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
									$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code');
									######update member_exam######
									$update_data = array('pay_status' => '1');
									$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
									//Query to get user details
									$this->db->join('state_master','state_master.state_code=member_registration.state');
									$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name');
									if(count($exam_info) <= 0)
									{
										$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']));
									}
									if($exam_info[0]['exam_mode']=='ON')
									{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
									{$mode='Offline';}
									else{$mode='';}
									if($exam_info[0]['examination_date']!='0000-00-00')
									{
										$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
									}
									else if($exam_info[0]['exam_code']!=990)
									{
										//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
										$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
										$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
									}
									//Query to get Medium	
									$this->db->where('exam_code',$exam_info[0]['exam_code']);
									$this->db->where('exam_period',$exam_info[0]['exam_period']);
									$this->db->where('medium_code',$exam_info[0]['exam_medium']);
									$this->db->where('medium_delete','0');
									$medium=$this->master_model->getRecords('medium_master','','medium_description');
									$this->db->where('state_delete','0');
									$states=$this->master_model->getRecords('state_master',array('state_code'=>$exam_info[0]['state_place_of_work']),'state_name');
									//Query to get Payment details	
									$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount,id');
									$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									//if($this->session->userdata['examinfo']['elected_exam_mode']=='E')
									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'Elearning_apply_exam_transaction_success'));
									$this->sms_template_id = 'dvPQcIQGR';
									$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
									$newstring2 = str_replace("#REG_NUM#", "".$get_user_regnum[0]['member_regnumber']."",$newstring1);
									$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
									$newstring4 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring3);
									$newstring5 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring4);
									$newstring6 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring5);
									$newstring7 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring6);
									$newstring8 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring7);
									$newstring9 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring8);
									$newstring10 = str_replace("#CITY#", "".$result[0]['city']."",$newstring9);
									$newstring11 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring10);
									$newstring12 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring11);
									$newstring13 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring12);
									$newstring14 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring13);
									$newstring15 = str_replace("#MODE#", "".$mode."",$newstring14);
									$newstring16 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring15);
									$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
									if(count($elern_msg_string) > 0)
									{
										foreach($elern_msg_string as $row)
										{
											$arr_elern_msg_string[]=$row['exam_code'];
										}
										if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
										{
											$newstring17 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring16);		
										}
										else
										{
											$newstring17 = str_replace("#E-MSG#", '',$newstring16);		
										}
									}
									else
									{
										$newstring17 = str_replace("#E-MSG#", '',$newstring16);
									}
									$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring17);
									$info_arr=array('to'=>$result[0]['email'],
									'from'=>$emailerstr[0]['from'],
									'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],
									'message'=>$final_str
									);
									//echo $final_str; exit;
									//get invoice	
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
									//echo $this->db->last_query();exit;
									########### generate invoice ###########
									if(count($getinvoice_number) > 0)
									{
										$invoiceNumber =generate_elearning_exam_invoice_number($getinvoice_number[0]['invoice_id']);
										if($invoiceNumber)
										{
											$invoiceNumber=$this->config->item('El_exam_invoice_no_prefix').$invoiceNumber;
										}
										$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
										$this->db->where('pay_txn_id',$payment_info[0]['id']);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										$attachpath=genarate_elearning_exam_invoice($getinvoice_number[0]['invoice_id']);
									}	
									if($attachpath!='')
									{		
										$files=array($attachpath);
										$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
										$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
										$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
										$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
										$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
										//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
										//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
										$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

										$this->Emailsending->mailsend_attch($info_arr,$files);
										//$this->Emailsending->mailsend($info_arr);
									}
								}
								else
								{
									$log_title ="S2S Update fail:".$get_user_regnum[0]['member_regnumber'];
									$log_message = serialize($update_data);
									$rId = $MerchantOrderNo;
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);	
								}
							}
						}
						else if($payment_status==0)
						{
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code');
							if($get_user_regnum[0]['status']==2)
							{
								$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' =>  $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
								//Query to get Payment details	
								$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'transaction_no,date,amount');
								if($get_user_regnum[0]['exam_code']!='990')
								{
									//Query to get user details
									$this->db->join('state_master','state_master.state_code=member_registration.state');
									$result=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name');
									//Query to get exam details	
									$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
									$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
									$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
									//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
									$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
									$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
									$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
									$this->sms_template_id = 'Jw6bOIQGg';
									$newstring1 = str_replace("#application_num#", "".$get_user_regnum[0]['member_regnumber']."",  $emailerstr[0]['emailer_text']);
									$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
									$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
									$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
									$info_arr=array(	'to'=>$result[0]['email'],
									'from'=>$emailerstr[0]['from'],
									'subject'=>$emailerstr[0]['subject'].' '.$get_user_regnum[0]['member_regnumber'],
									'message'=>$final_str
									);
									//send sms to Ordinary Member
									$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
									$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
									$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
									// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
									//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
									$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

									$this->Emailsending->mailsend($info_arr);
								}
							}
						}				
					}
					else if($pay_type == "IIBF_ELR")
					{
						sleep(8);
						
						if($payment_status==1)
						{
							// Handle transaction success case 
							$exam_period_date='';
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
							if($get_user_regnum[0]['status']==2)
							{
								######### payment Transaction ############
								$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
								$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
								if($this->db->affected_rows())
								{
									$exam_code=$get_user_regnum[0]['exam_code'];
									$reg_id=$get_user_regnum[0]['member_regnumber'];
									//$applicationNo = generate_nm_reg_num();
									$applicationNo = generate_NM_memreg($reg_id);
									######### payment Transaction ############
									$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
									######### update application number to Registration table#########
									$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
									$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$reg_id));
									######### update application number to member exam#########
									$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
									$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
									//Query to get exam details	
									$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
									$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
									$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.examination_date');
									if($exam_info[0]['exam_mode']=='ON')
									{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
									{$mode='Offline';}
									else{$mode='';}
									if($exam_info[0]['examination_date']!='0000-00-00')
									{
										$exam_period_date= date('d-M-Y',strtotime($exam_info[0]['examination_date']));
									}
									else
									{
										//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
										$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
										$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
									}
									//Query to get Medium	
									$this->db->where('exam_code',$exam_code);
									$this->db->where('exam_period',$exam_info[0]['exam_period']);
									$this->db->where('medium_code',$exam_info[0]['exam_medium']);
									$this->db->where('medium_delete','0');
									$medium=$this->master_model->getRecords('medium_master','','medium_description');
									//Query to get Payment details	
									$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNo),'transaction_no,date,amount,id');
									//Query to get user details
									$this->db->join('state_master','state_master.state_code=member_registration.state');
									$result=$this->master_model->getRecords('member_registration',array('regid'=>$reg_id),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,usrpassword');	
									$upd_files = array();
									$photo_file = 'p_'.$applicationNo.'.jpg';
									$sign_file = 's_'.$applicationNo.'.jpg';
									$proof_file = 'pr_'.$applicationNo.'.jpg';
									if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
									{	$upd_files['scannedphoto'] = $photo_file;	}
									if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
									{	$upd_files['scannedsignaturephoto'] = $sign_file;	}
									if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
									{	$upd_files['idproofphoto'] = $proof_file;	}
									if(count($upd_files)>0)
									{
										$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$reg_id));
									}
									include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
									$key = $this->config->item('pass_key');
									$aes = new CryptAES();
									$aes->set_key(base64_decode($key));
									$aes->require_pkcs5();
									$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
									$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'ELnon_member_apply_exam_transaction_success'));
									$this->sms_template_id = 'LSy_cIwGg';
									$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
									$newstring2 = str_replace("#REG_NUM#", "".$applicationNo."",$newstring1);
									$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
									$newstring4 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring3);
									$newstring5 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring4);
									$newstring6 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring5);
									$newstring7 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring6);
									$newstring8 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring7);
									$newstring9 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring8);
									$newstring10 = str_replace("#CITY#", "".$result[0]['city']."",$newstring9);
									$newstring11 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring10);
									$newstring12 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring11);
									$newstring13 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring12);
									$newstring14 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring13);
									$newstring15 = str_replace("#MODE#", "".$mode."",$newstring14);
									$newstring16 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring15);
									$newstring17 = str_replace("#PASS#", "".$decpass."",$newstring16);
									$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
									if(count($elern_msg_string) > 0)
									{
										foreach($elern_msg_string as $row)
										{
											$arr_elern_msg_string[]=$row['exam_code'];
										}
										if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
										{
											$newstring18 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring17);		
										}
										else
										{
											$newstring18 = str_replace("#E-MSG#", '',$newstring17);		
										}
									}
									else
									{
										$newstring18 = str_replace("#E-MSG#", '',$newstring17);
									}
									$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring18);
									$info_arr=array('to'=>$result[0]['email'],
									'from'=>$emailerstr[0]['from'],
									'subject'=>$emailerstr[0]['subject'].' '.$applicationNo,
									'message'=>$final_str
									);
									//get invoice	
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
									//echo $this->db->last_query();exit;
									if(count($getinvoice_number) > 0)
									{
										$invoiceNumber =generate_elearning_exam_invoice_number($getinvoice_number[0]['invoice_id']);
										if($invoiceNumber)
										{
											$invoiceNumber=$this->config->item('El_exam_invoice_no_prefix').$invoiceNumber;
										}
										$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'member_no'=>$applicationNo,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
										$this->db->where('pay_txn_id',$payment_info[0]['id']);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										$attachpath=genarate_elearning_exam_invoice($getinvoice_number[0]['invoice_id']);
									}	
									if($attachpath!='')
									{
										//send sms		
										$files=array($attachpath);			
										$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);	
										$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
										$sms_newstring1 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring);
										$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring1);
										//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);						
										//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
										$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

										$this->Emailsending->mailsend_attch($info_arr,$files);
										//$this->Emailsending->mailsend($info_arr);
									}
								}
								else
								{
									$log_title ="S2S Update fail:".$get_user_regnum[0]['member_regnumber'];
									$log_message = serialize($update_data);
									$rId = $MerchantOrderNo;
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);	
								}
							}
						}
						else if($payment_status==0)
						{
							// Handle transaction fail case 
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,exam_code,status');
							if($get_user_regnum[0]['status']==2)
							{
								$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' =>  $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
								$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
								//Query to get Payment details	
								$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'transaction_no,date,amount,ref_id');
								$result=$this->master_model->getRecords('member_registration',array('regid'=>$payment_info[0]['member_regnumber']),'firstname,middlename,lastname,email,mobile');
								$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
								$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
								$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
								$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
								$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.regnumber'=>$payment_info[0]['member_regnumber']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
								//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
								$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
								$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
								$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
								$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
								$this->sms_template_id = 'Jw6bOIQGg';
								$newstring1 = str_replace("#application_num#", "",  $emailerstr[0]['emailer_text']);
								$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
								$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
								$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
								$info_arr=array(	'to'=>$result[0]['email'],
								'from'=>$emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'],
								'message'=>$final_str
								);
								// send SMS
								$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
								$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
								$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
								//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
								//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
								$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

								$this->Emailsending->mailsend($info_arr);
							}
						}
					}		
					else if($pay_type == "iibfcpd")
					{
						sleep(8);
						
						if($payment_status==1)
						{	
							$exam_period_date='';
							//Handle transaction success case
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status');
							if($get_user_regnum[0]['status']==2)
							{
								######### payment Transaction ############
								$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
								$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
								if($this->db->affected_rows())
								{
									if(count($get_user_regnum) > 0)
									{
										$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'isactive'=>'1'),'regnumber,usrpassword,email');
									}
									///chiatli's code
									$created_on = date('Y-m-d H:i:s');
									$validate_upto  = date('Y-m-d H:i:s', strtotime('+2 years', strtotime($created_on)));
									$update_data = array('pay_status'=>'1','validate_upto'=>$validate_upto);
									$this->master_model->updateRecord('cpd_registration',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'cpd'));
									$this->sms_template_id = 'NA';
									if(count($emailerstr) > 0 && (count($get_user_regnum) > 0))
									{
										$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$get_user_regnum[0]['id']));
										if(count($getinvoice_number) > 0)
										{ 
											$invoiceNumber = generate_cpd_invoice_number($getinvoice_number[0]['invoice_id']);
											if($invoiceNumber)
											{
												$invoiceNumber=$this->config->item('CPD_invoice_no_prefix').$invoiceNumber;
											}
											$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
											$this->db->where('pay_txn_id',$get_user_regnum[0]['id']);
											$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
											$attachpath=genarate_cpd_invoice($getinvoice_number[0]['invoice_id']);
										}
										if($attachpath!='')
										{	 
											$final_str = $emailerstr[0]['emailer_text'];
											$info_arr = array('to'=>$user_info[0]['email'],'from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'].' '.$user_info[0]['regnumber'],'message'=>$final_str);
											if($this->Emailsending->mailsend_attch_cpd($info_arr,$attachpath))
											{
												redirect(base_url().'Cpd/acknowledge/'.base64_encode($MerchantOrderNo));
											}
											else
											{
												redirect(base_url('Cpd/acknowledge/'));
											}
										}
										else
										{
											redirect(base_url('Cpd/acknowledge/'));
										}	
										//Manage Log
										$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=S2S"; 
										$this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);
										//$this->db->last_query();exit;
										//$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
									}
								}
								else if($payment_status==0)
								{
									$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code');
									if($get_user_regnum[0]['status']==2)
									{
										$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0399','bankcode' => $bankid,'paymode' =>  $txn_process_type,'callback'=>'S2S');
										$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
									}
								}				
							}
							// add payment responce in log
							if($pay_type == "iibfdra")
							{
								$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=S2S";
								$this->log_model->logdratransaction("billdesk", $pg_response, $transaction_error_type);
							}
							else if($pay_type == "iibfamp")
							{
								$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=S2S";
								$this->log_model->logamptransaction("billdesk", $pg_response, $transaction_error_type);
							}
							else
							{
								$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=S2S";
								$this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);
							}
						}
						else
						{
							die("Please try again...");
						}
						exit;
					}
					else if($pay_type == "IIBFELS" && $transaction_error_type == 'success')
					{
						sleep(8);
						
						if($payment_status==1)
						{
							//START : CODE ADDED BY SAGAR ON 03-09-2021 : GENARATE REGNUMBER FOR NEWLY ADDED MEMBER ONLY WHEN PAYMENT IS SUCCESS
							$get_regid = $this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'id, ref_id');
							if(count($get_regid) > 0)
							{
								$regid = $get_regid[0]['ref_id'];
								$chk_member_no = $this->master_model->getRecords('spm_elearning_registration',array('regid'=>$regid, 'regnumber'=>''),'regid, regnumber');
								if(count($chk_member_no) > 0)
								{
									## Generate regnumber for newly added member. Note: This is for only E-learning module
									$regnumber = generate_eLearning_memreg($regid);
									//UPDATE regnumber IN spm_elearning_registration
									$up_data1['registrationtype'] = 1; 
									$up_data1['isactive'] = '1'; 
									$up_data1['regnumber'] = $regnumber; 
									$this->master_model->updateRecord('spm_elearning_registration',$up_data1,array('regid'=>$regid, 'regnumber'=>''));
									$log_title ="E-learning member UPDATE array spm_elearning_registration :".$regid;
									$log_message = serialize($up_data1);
									storedUserActivity($log_title, $log_message, $regid, $regnumber);
									//UPDATE regnumber IN spm_elearning_member_subjects
									$up_data2['regnumber'] = $regnumber; 
									$this->master_model->updateRecord('spm_elearning_member_subjects',$up_data2,array('regid'=>$regid, 'regnumber'=>''));
									$log_title ="E-learning member UPDATE array spm_elearning_member_subjects :".$regid;
									$log_message = serialize($up_data2);
									storedUserActivity($log_title, $log_message, $regid, $regnumber);
									//UPDATE regnumber IN payment_transaction
									$up_data3['member_regnumber'] = $regnumber; 
									$this->master_model->updateRecord('payment_transaction',$up_data3,array('ref_id'=>$regid, 'member_regnumber'=>'', 'pay_type'=>'20'));
									$log_title ="E-learning member UPDATE array payment_transaction :".$regid;
									$log_message = serialize($up_data3);
									storedUserActivity($log_title, $log_message, $regid, $regnumber);
									//UPDATE regnumber IN exam_invoice
									$up_data4['member_no'] = $regnumber; 
									$this->master_model->updateRecord('exam_invoice',$up_data4,array('receipt_no'=>$MerchantOrderNo, 'member_no'=>''));
									$log_title ="E-learning member UPDATE array exam_invoice :".$MerchantOrderNo;
									$log_message = serialize($up_data4);
									storedUserActivity($log_title, $log_message, $MerchantOrderNo, $regnumber);
								}
								else
								{
									//UPDATE status IN spm_elearning_registration
									$up_data1['isactive'] = '1'; 
									$this->master_model->updateRecord('spm_elearning_registration',$up_data1,array('regid'=>$regid));
								}
							}
							//END : CODE ADDED BY SAGAR ON 03-09-2021 : GENARATE REGNUMBER FOR NEWLY ADDED MEMBER ONLY WHEN PAYMENT IS SUCCESS        
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'id, member_regnumber, ref_id, status');
							//check user payment status is updated by b2b or not
							if($get_user_regnum[0]['status']==2)
							{
								######### payment Transaction ############
								$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
								$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
								$last_qry_payment_transaction = $this->db->last_query();
								$log_title = "E-learning payment_transaction cs2s update : receipt_no = '".$MerchantOrderNo."'";
								$log_message =  $last_qry_payment_transaction;
								$rId = $get_user_regnum[0]['member_regnumber'];
								$regNo = $get_user_regnum[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);
								if($this->db->affected_rows())	
								{
									$el_sub_id_arr = array();
									$get_el_sub_ids = $this->master_model->getRecords('spm_elearning_member_subjects',array('pt_id'=>$get_user_regnum[0]['id']),'el_sub_id');
									if(count($get_el_sub_ids) > 0)
									{
										foreach($get_el_sub_ids as $get_el_sub_res)
										{
											$el_sub_id_arr[] = $get_el_sub_res['el_sub_id'];
										}
									}
									if(count($el_sub_id_arr) > 0)
									{
										foreach($el_sub_id_arr as $el_sub_id_res)
										{
											$this->master_model->updateRecord('spm_elearning_member_subjects',array('pt_id'=>$get_user_regnum[0]['id'], 'transaction_no'=>$transaction_no, 'receipt_no'=>$MerchantOrderNo, 'status'=>1, 'updated_on'=>date('Y-m-d H:i:s')),array('el_sub_id'=>$el_sub_id_res));
										}
									}
									//Query to get Payment details	
									$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'exam_code, transaction_no, date, amount, id, status');
									//get invoice	
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
									if(count($getinvoice_number) > 0)
									{
										$invoiceNumber = generate_el_invoice_number($getinvoice_number[0]['invoice_id']);
										if($invoiceNumber)
										{
											/* $cyear = date("y");
											$nyear = $cyear+1;
											$invoiceNumber='EL/'.$cyear.'-'.$nyear.'/'.$invoiceNumber; */

                      //START : THIS CODE WAS UPDATED BY SAGAR M ON 2024-04-16. EARLIER THE INVOICE YEAR FOLLOW THE CALENDER YEAR INSTEAD OF FINANCIAL YEAR
                      if(date("Y-m-d") >= date("Y").'-04-01') { $cyear = date("y"); } else { $cyear = date('y') - 1; }
                      $nyear = $cyear + 1;
                      if($cyear.'-'.$nyear == '24-25' && $invoiceNumber >= 6860) { $invoiceNumber = $invoiceNumber + 3056; }
                      $invoiceNumber = 'EL/' . $cyear . '-' . $nyear . '/' . str_pad($invoiceNumber,6,0,STR_PAD_LEFT);
                      //END : THIS CODE WAS UPDATED BY SAGAR M ON 2024-04-16. EARLIER THE INVOICE YEAR FOLLOW THE CALENDER YEAR INSTEAD OF FINANCIAL YEAR
										}
										$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
										$this->db->where('pay_txn_id',$payment_info[0]['id']);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										$attachpath=genarate_el_invoice($getinvoice_number[0]['invoice_id']);
										$last_qry_exam_invoice = $this->db->last_query();
										$log_title = "E-learning exam_invoice cs2s update : receipt_no = '".$MerchantOrderNo."'";
										$log_message = $last_qry_exam_invoice;
										$rId = $get_user_regnum[0]['member_regnumber'];
										$regNo = $get_user_regnum[0]['member_regnumber'];
										storedUserActivity($log_title, $log_message, $rId, $regNo);
									}	
									$selected_sub_data = $this->master_model->getRecords('spm_elearning_member_subjects ms',array('ms.status'=>'1', 'ms.pt_id'=>$payment_info[0]['id']), 'ms.subject_code, ms.subject_description');
									$selected_sub_disp_str = '';
									if(count($selected_sub_data) > 0)
									{
										$sr_no = 1;
										foreach($selected_sub_data as $selected_sub_res)
										{
											$selected_sub_disp_str .= '<span style="display: block;margin: 2px 0 5px 0px;">'.$sr_no.'. '.$selected_sub_res['subject_description'].'</span>';
											$sr_no++;
										}								
									}
									$email_payment_status = '';
									if($payment_info[0]['status'] == 0) { $email_payment_status = 'Fail'; }
									else if($payment_info[0]['status'] == 1) { $email_payment_status = 'Success'; }
									else if($payment_info[0]['status'] == 2) { $email_payment_status = 'Pending'; }
									else if($payment_info[0]['status'] == 3) { $email_payment_status = 'Refund'; }
									//Query to get Exam Name	
									$email_exam_name = '';
									$this->db->limit(1);
									$this->db->order_by('exam_id', 'DESC');
									$exam_data = $this->master_model->getRecords('spm_elearning_exam_master',array('exam_code'=>$payment_info[0]['exam_code']),'exam_code, exam_name');
									if(count($exam_data) > 0)
									{
										$email_exam_name = $exam_data[0]['exam_name'];
									}
									$Candidate = $this->master_model->getRecords('spm_elearning_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']));
									$mail_content = '
									<table cellspacing="5" cellpadding="0" align="center" style="border: 1px solid #ddd; font-family: Arial,Helvetica,sans-serif; font-size: 14px; margin: 0; max-width: 800px; width: 100%; background:#FFFFCC" border="1">
									<thead>
									<tr>
									<td colspan="2" align="center"><h2 style="line-height:24px; margin:10px 0;">Transaction Details</h2></td>
									</tr>
									</thead>
									<tbody>
									<tr>
									<td colspan="2" style="padding:10px;" width="100%">
									<p>Dear '.ucfirst($Candidate[0]['firstname']).'</p>
									<p>We acknowledge with thanks the receipt of the payment for Enrolment in E-Learning as per the details given below.</p>
									</td>
									</tr>
									<tr>
									<td style="padding:5px 10px;" width="35%"><p><strong>Registration / Membership No : </strong></p></td>
									<td style="padding:5px 10px;" width="64%">'.$get_user_regnum[0]['member_regnumber'].'</td>
									</tr>
									<tr>
									<td style="padding:5px 10px;;" width="35%"><p><strong>Member Name : </strong></p></td>
									<td style="padding:5px 10px;" width="64%">'.ucfirst($Candidate[0]['firstname']).' '.ucfirst($Candidate[0]['lastname']).'</td>
									</tr>
									<tr>
									<td style="padding:5px 10px;" width="35%"><p><strong>Email Id : </strong></p></td>
									<td style="padding:5px 10px;" width="64%"><a href="mailto:'.$Candidate[0]['email'].'">'.$Candidate[0]['email'].'</a></td>
									</tr>
									<tr>
									<td style="padding:5px 10px;" width="35%"><p><strong>Transaction ID:</strong></p></td>
									<td style="padding:5px 10px;" width="64%">'.$payment_info[0]['transaction_no'].'</td>
									</tr>
									<tr>
									<td style="padding:5px 10px;" width="35%"><p><strong>Exam Name: </strong></p></td>
									<td style="padding:5px 10px;" width="64%"><p>'.$email_exam_name.'</p></td>
									</tr>
									<tr>
									<td style="padding:5px 10px;" width="35%"><p><strong>E-Learning Subject/s: </strong></p></td>
									<td style="padding:5px 10px;" width="64%"><p>'.$selected_sub_disp_str.'</p></td>
									</tr>
									<tr>
									<td style="padding:5px 10px;" width="35%"><p><strong>Transaction Status: </strong></p></td>
									<td style="padding:5px 10px;" width="64%"><p>'.$email_payment_status.'</p></td>
									</tr>
									<tr>
									<td style="padding:5px 10px;" width="35%"><p><strong>Amount : </strong></p></td>
									<td style="padding:5px 10px;" width="64%">'.$payment_info[0]['amount'].'</td>
									</tr>
									<tr>
									<td style="padding:5px 10px;" width="35%"><p><strong>Transaction Date :</strong> </p></td>
									<td style="padding:5px 10px;" width="64%">'.date('Y-m-d H:i:s A',strtotime($payment_info[0]['date'])).'</td>
									</tr>
									</tbody>
									</table>';
									$sender_email = $this->master_model->getRecords('spm_elearning_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber'],'isactive'=>"1"),"email", "", "", "1");
									$info_arr['to'] = $sender_email[0]['email']; 
									$info_arr['from'] = "logs@iibf.esdsconnect.com";
									$info_arr['cc'] = "iibfdevp@esds.co.in";
									$info_arr['subject'] = "E-Learning Payment Acknowledgment";
									$info_arr['message'] = $mail_content;
									if($attachpath!='')
									{		
										$files=array($attachpath);
										$this->Emailsending->mailsend_attch($info_arr,$files);
									}
									//Manage Log
									$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
									$this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);
								}
							}	
						}
					}
					else if($pay_type == "IIBF_INST_SUB") 
					{
						sleep(8);
						if($payment_status==1)
						{
							$payment_data = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status,date');					
							
							if($payment_data[0]['status'] == 2)//IF payment status is pending
							{
								// ######## payment Transaction ############
								$update_data['transaction_no'] = $transaction_no;
								$update_data['status'] = 1;
								$update_data['transaction_details'] = $transaction_error_type . " - " . $transaction_error_desc;
								$update_data['auth_code'] = '0300';
								$update_data['bankcode'] = $bankid;
								$update_data['paymode'] = $txn_process_type;
								$update_data['callback'] = 'S2S';						
								$update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));
								
								// ######## Insert Log ############
								$query_update_payment_tra = $this->db->last_query();
								$log_title = "S2S query_update_payment_tra :" . $query_update_payment_tra;
								$log_message = serialize($update_data);
								$rId = $payment_data[0]['member_regnumber'];
								$regNo = $payment_data[0]['member_regnumber'];
								storedUserActivity($log_title, $log_message, $rId, $regNo);				
							}
							
							// Query to get Payment details
							$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $payment_data[0]['member_regnumber']), 'member_regnumber, transaction_no, date, amount, id');
							
							$this->send_mail_common('success', $payment_info[0]['member_regnumber'], $payment_info[0]['description'], $payment_info[0]['amount'], $payment_info[0]['transaction_no'], $payment_info[0]['date']);
							
							// Manage Log
							$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
							$this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);
						}
					}
          else if($pay_type == "BC") //FOR IIBF BCBF MODULE. ADDED BY SAGAR & ANIL ON 15-04-2024
					{
						sleep(8);
            $this->load->model('iibfbcbf/Iibf_bcbf_model');
            $this->load->helper('iibfbcbf/iibf_bcbf_helper');
            $this->load->helper('file'); 
            $this->load->helper('getregnumber_helper'); 

						if($payment_status==1)
						{
							$payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $MerchantOrderNo, 'payment_mode'=>'Individual'), 'id, status, date');			
							
							if($payment_data[0]['status'] == '2')//IF payment status is PENDING
							{
                // START : UPDATE PAYMENT SUCCESS STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
                $update_data = array();
                $update_data['transaction_no'] = $transaction_no;
                $update_data['status'] = '1';
                $update_data['transaction_details'] = $transaction_error_type . " - " . $transaction_error_desc;
                $update_data['auth_code'] = '0300';
                $update_data['bankcode'] = $bankid;
                $update_data['paymode'] = $txn_process_type;
                $update_data['callback'] = 'S2S';						
                $update_data['description'] = 'Payment Success By Individual Candidate : S2S';
                $update_data['approve_reject_date'] = date('Y-m-d H:i:s');
                $update_data['updated_on'] = date('Y-m-d H:i:s');
                $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => '2', 'payment_mode'=>'Individual'));
                
                $this->Iibf_bcbf_model->insert_common_log('BILLDESK RESPONSE : payment status updated as success : S2S', 'iibfbcbf_payment_transaction', $this->db->last_query(), $payment_data[0]['id'],'billdesk_action','The payment status successfully updated as success : S2S', json_encode($update_data));
                // END : UPDATE PAYMENT SUCCESS STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG

                // QUERY TO GET UPDATED PAYMENT DETAILS AFTER PAYMENT SUCCESS
                $payment_info = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $MerchantOrderNo, 'payment_mode'=>'Individual'), 'transaction_no, date, amount, id, description, status, exam_ids, exam_code, exam_period, receipt_no');
          
                if(count($payment_info) > 0 && $payment_info[0]['status'] == '1')
                {
                  $member_exam_id = $payment_info[0]['exam_ids'];

                  //START : GET MEMBER REGNUMBER. IF IT IS EMPTY THEN GENERATE NEW REGNUMBER, RENAME THE IMAGES. ALSO UPDATE THE RE-ATTEMPT         
                  $this->db->join('iibfbcbf_batch_candidates bc', 'bc.candidate_id = me.candidate_id', 'INNER');
                  $member_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.member_exam_id' => $member_exam_id, 'payment_mode'=>'Individual'), 'bc.candidate_id, bc.regnumber, bc.candidate_photo, bc.candidate_sign, bc.id_proof_file, bc.qualification_certificate_file, bc.re_attempt');

                  if(count($member_data) > 0)
                  {
                    $up_cand_data = array();

                    //START : GENERATE REGNUMBER AND RENAME THE IMAGES
                    $log_msg = '';
                    if($member_data[0]['regnumber'] == '')
                    {
                      $id_proof_file_path = 'uploads/iibfbcbf/id_proof';
                      $qualification_certificate_file_path = 'uploads/iibfbcbf/qualification_certificate';
                      $candidate_photo_path = 'uploads/iibfbcbf/photo';
                      $candidate_sign_path = 'uploads/iibfbcbf/sign';
                      
                      $current_id_proof_file = $member_data[0]['id_proof_file'];
                      $current_qualification_certificate_file = $member_data[0]['qualification_certificate_file'];
                      $current_candidate_photo = $member_data[0]['candidate_photo'];
                      $current_candidate_sign = $member_data[0]['candidate_sign'];              
                      
                      $up_cand_data['regnumber'] = $new_regnumber = generate_NM_memreg($member_data[0]['candidate_id']);
                      
                      if(!empty($current_id_proof_file)) 
                      {
                        $new_id_proof_file = 'id_proof_'.$new_regnumber.'.'.strtolower(pathinfo($current_id_proof_file, PATHINFO_EXTENSION));
                        $chk_rename_id_proof = $this->Iibf_bcbf_model->check_file_rename($current_id_proof_file, "./".$id_proof_file_path."/", $new_id_proof_file);

                        if($chk_rename_id_proof == 'success') { $up_cand_data['id_proof_file'] = $new_id_proof_file; }
                      }

                      if(!empty( $current_qualification_certificate_file)) 
                      {
                        $new_qualification_certificate_file = 'quali_cert_'.$new_regnumber.'.'.strtolower(pathinfo($current_qualification_certificate_file, PATHINFO_EXTENSION));                
                        $chk_rename_quali_cert = $this->Iibf_bcbf_model->check_file_rename($current_qualification_certificate_file, "./".$qualification_certificate_file_path."/", $new_qualification_certificate_file);

                        if($chk_rename_quali_cert == 'success') { $up_cand_data['qualification_certificate_file'] = $new_qualification_certificate_file; }
                      }

                      if(!empty($current_candidate_photo)) 
                      {
                        $new_candidate_photo = 'photo_'.$new_regnumber.'.'.strtolower(pathinfo($current_candidate_photo, PATHINFO_EXTENSION));
                        $chk_rename_photo = $this->Iibf_bcbf_model->check_file_rename($current_candidate_photo, "./".$candidate_photo_path."/", $new_candidate_photo);

                        if($chk_rename_photo == 'success') { $up_cand_data['candidate_photo'] = $new_candidate_photo; }
                      }

                      if(!empty( $current_candidate_sign)) 
                      {
                        $new_candidate_sign = 'sign_'.$new_regnumber.'.'.strtolower(pathinfo($current_candidate_sign, PATHINFO_EXTENSION));
                        $chk_rename_sign = $this->Iibf_bcbf_model->check_file_rename($current_candidate_sign, "./".$candidate_sign_path."/", $new_candidate_sign);

                        if($chk_rename_sign == 'success') { $up_cand_data['candidate_sign'] = $new_candidate_sign; }
                      }   
                      
                      $log_msg .= 'The regnumber is successfully generated, successfully rename the images';
                    }//END : GENERATE REGNUMBER AND RENAME THE IMAGES
                    
                    $up_cand_data['re_attempt'] = $member_data[0]['re_attempt'] + 1;//UPDATE RE-ATTEMT
                    $up_cand_data['updated_on'] = date('Y-m-d H:i:s');
                    $this->master_model->updateRecord('iibfbcbf_batch_candidates',$up_cand_data, array('candidate_id' => $member_data[0]['candidate_id']));
                    if($log_msg == "") { $log_msg .= 'The re-attempt is updated successfully'; }
                    else { $log_msg .= ' and re-attempt is updated successfully'; }

                    $this->Iibf_bcbf_model->insert_common_log('BILLDESK RESPONSE : generate new regnumber, rename the images and update re-attempt : S2S', 'iibfbcbf_batch_candidates', $this->db->last_query(), $member_data[0]['candidate_id'],'billdesk_action',$log_msg." : S2S", json_encode($up_cand_data));

                    //START : UPDATE TRANSACTION NUMBER & PAYMENT STATUS IN iibfbcbf_member_exam
                    $up_exam_data = array();
                    $up_exam_data['ref_utr_no'] = $transaction_no;
                    $up_exam_data['pay_status'] = '1';
                    $this->master_model->updateRecord('iibfbcbf_member_exam',$up_exam_data, array('member_exam_id' => $member_exam_id));
                    
                    $this->Iibf_bcbf_model->insert_common_log('BILLDESK RESPONSE : update transaction number and payment status in member exam : S2S', 'iibfbcbf_member_exam', $this->db->last_query(), $member_exam_id,'billdesk_action','The transaction number and payment status is successfully updated in member exam : S2S', json_encode($up_exam_data));

                    $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment success', 'iibfbcbf_batch_candidates', '', $member_data[0]['candidate_id'],'candidate_action','The candidate has successfully applied for the exam ('.$payment_info[0]['exam_code'].' & '.$payment_info[0]['exam_period'].') by individual.', '');
                  }//END : GET MEMBER REGNUMBER. IF IT IS EMPTY THEN GENERATE NEW REGNUMBER, RENAME THE IMAGES. ALSO UPDATE THE RE-ATTEMPT
                  
                  // START : GENERATE INVOICE NUMBER AND INVOICE IMAGE.
                  $exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $payment_info[0]['id'], 'exam_code'=>$payment_info[0]['exam_code'], 'exam_period'=>$payment_info[0]['exam_period'], 'receipt_no'=>$payment_info[0]['receipt_no'], 'app_type'=>'BC'),'invoice_id');
                
                  if(count($exam_invoice) > 0 && $payment_info[0]['amount'] > 0)
                  {
                    $invoice_no = generate_iibfbcbf_exam_invoice_number($exam_invoice[0]['invoice_id']); // Use helpers/iibfbcbf/iibf_bcbf_helper.php
                  
                    if($invoice_no)
                    {
                      $invoice_no = $this->config->item('iibfbcbf_exam_invoice_no_prefix').$invoice_no; // e.g. EXM/2017-18/000001
                    }
                    
                    $up_invoice_data['invoice_no'] = $invoice_no;
                    $up_invoice_data['transaction_no'] = $transaction_no;
                    $up_invoice_data['date_of_invoice'] = date('Y-m-d H:i:s');
                    $up_invoice_data['modified_on'] = date('Y-m-d H:i:s');            
                    $this->master_model->updateRecord('exam_invoice',$up_invoice_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));
                    
                    $this->Iibf_bcbf_model->insert_common_log('BILLDESK RESPONSE : update exam invoice number and image : S2S', 'exam_invoice', $this->db->last_query(), $exam_invoice[0]['invoice_id'],'billdesk_action','The exam invoice number and image is successfully updated in exam invoice table : S2S', json_encode($up_invoice_data));          
                    
                    $invoice_img_path = genarate_iibf_bcbf_exam_invoice($exam_invoice[0]['invoice_id']); // Use helpers/iibfbcbf/iibf_bcbf_helper.php  
                  }// END : GENERATE INVOICE NUMBER AND INVOICE IMAGE.
                  
                  $this->Iibf_bcbf_model->generate_admit_card_common($enc_pt_id); //GENERATE ADMITCARD
                
                  $this->Iibf_bcbf_model->send_transaction_details_email_sms($payment_info[0]['id']);
                }
							}

							// Manage Log
							$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
							$this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);
						}
					}
					else if($pay_type == "iibf_career_reg")
					{
						sleep(8);
						if($payment_status==1)
						{
							// S2s success callback for career 
					 	$user_payment_txn_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status,id');

            if ($user_payment_txn_info[0]['status'] == 2) {
                $update_data = array(
                    'transaction_no'      => $transaction_no,
                    'status'              => 1,
                    'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                    'gateway'             => 'billdesk',
                    'auth_code'           => '0300',
                    'bankcode'            => $bankid,
                    'paymode'             => $txn_process_type,
                    'callback'            => 'S2S',
                );
                $update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));

                $log_title   = "Career payment payment update s2s";
                $log_message = serialize($update_data);
                $reg_id         = $user_payment_txn_info[0]['ref_id'];
              
                storedUserActivity($log_title, $log_message, $reg_id, $reg_id);

                if ($this->db->affected_rows()) {
         
                    if (count($user_payment_txn_info) > 0) {
                        $custom_reg_id='JE'.date('Y').sprintf("%04d", $reg_id);

                        $update_mem_data = array('pay_status' => '1','reg_id'=>$custom_reg_id);
                        $this->master_model->updateRecord('careers_registration', $update_mem_data, array('careers_id' => $reg_id));
                    }
                    //get invoice
                    $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $user_payment_txn_info[0]['id']));
                  
                    if (count($getinvoice_number) > 0) {

                        $invoiceNumber = generate_registration_invoice_number($getinvoice_number[0]['invoice_id']);
                        if ($invoiceNumber) {
                            $invoiceNumber = 'CAREER/2022-23/'. $invoiceNumber;
                        }

                        $update_data = array('invoice_no' => $invoiceNumber,'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                        $this->db->where('pay_txn_id', $user_payment_txn_info[0]['id']);
                        $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
                        
                        $attachpath = genarate_career_invoice($getinvoice_number[0]['invoice_id']);
                        $log_title   = "Career Invoice log update  c_S2s:" . $reg_id;
                        $log_message = serialize($this->db->last_query());
                        storedUserActivity($log_title, $log_message, $reg_id, $reg_id);
                    }

                   $emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'careers_email'));
                    if(count($emailerstr) > 0)
                    {  
                      /*'to'=>$email,*/
                      $final_str = $emailerstr[0]['emailer_text'];
                      
                      $career_data = $this->master_model->getRecords('careers_registration',array('careers_id'=>$reg_id));
                      if (count($career_data)>0) {
																$info_arr = array(
																'to'=>$career_data[0]['email'],
																'from'=>$emailerstr[0]['from'],
																'subject'=>$emailerstr[0]['subject'],
																'message'=>$final_str
																);

												  if($attachpath!='')
			                    {
			                      $this->Emailsending->mailsend_attch($info_arr,$attachpath);
			                    }
               				 }
			                  
                    }
                    
                }

                //Manage Log
                $pg_response = "encData=" . json_encode($responsedata) . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);
            }
							//End s2s sucess callback for career
						}
					}
          else if($pay_type == "IPPB_EXAM_REG"){
						//IPPB start
						sleep(8);
						if($payment_status==1)
						{	
							$exam_period_date='';
							//Handle transaction success case
							$elective_subject_name='';
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id,status,exam_code,receipt_no,pay_type,status,date');
							///check for dual exam applied or not 
							////
							if($get_user_regnum[0]['status']==2) 
							{
								######### payment Transaction ############
								
								$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata['transaction_error_type']." - ".$responsedata['transaction_error_desc'],'auth_code' => '0300', 'bankcode' => $responsedata['bankid'], 'paymode' => $responsedata['txn_process_type'],'callback'=>'S2S');
								
								$update_query=$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo,'status'=>2));
								
								if($this->db->affected_rows())
								{
									$exam_code=$get_user_regnum[0]['exam_code'];
									$reg_id=$get_user_regnum[0]['member_regnumber'];
									
									############check capacity is full or not ##########
									$exam_admicard_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$get_user_regnum[0]['ref_id']));
									if(count($exam_admicard_details) > 0)
									{		
										$msg='';
										$sub_flag=1;
										$sub_capacity=1;
										foreach($exam_admicard_details as $row)
										{
											$capacity=csc_check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
											if($capacity==0)
											{
												#########get message if capacity is full##########
												/*Add code trans_start & trans_complete :   */
												/* $update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata['transaction_error_type']." - ".$responsedata['transaction_error_desc'],'auth_code' => '0300', 'bankcode' => $responsedata['bankid'], 'paymode' => $responsedata['additional_info']['additional_info1'],'callback'=>'C_S2S');
													$this->db->order_by('id','DESC');
													$this->db->limit(1);
												$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo)); */
												
												$log_title ="IPPB nonreg Capacity full id:".$get_user_regnum[0]['member_regnumber'];
												$log_message = serialize($exam_admicard_details);
												$rId = $get_user_regnum[0]['ref_id'];
												$regNo = $get_user_regnum[0]['member_regnumber'];
												storedUserActivity($log_title, $log_message, $rId, $regNo);
												$refund_insert_array=array('receipt_no'=>$MerchantOrderNo,'response'=>$encData);
												$inser_id = $this->master_model->insertRecord('S2S_direcrt_refund',$refund_insert_array);
												$this->refund_after_capacity_full->make_refund($MerchantOrderNo);
												exit;
												//redirect(base_url().'Ippbapplyexam/refund/'.base64_encode($MerchantOrderNo));
											}
										}
									}
									
									$this->db->or_where('regnumber',$reg_id);
									$this->db->or_where('regid',$reg_id);
									$user_info=$this->master_model->getRecords('member_registration',array());
									if($user_info[0]['regnumber'] !== ''){
										$applicationNo = $user_info[0]['regnumber'];
										}else{
										$applicationNo = generate_NM_memreg($reg_id);
									}
									######### payment Transaction ############
									$update_data = array('transaction_no' => $transaction_no,'status' => 1,'member_regnumber'=>$applicationNo,'transaction_details' => $responsedata['transaction_error_type']." - ".$responsedata['transaction_error_desc'],'auth_code' => '0300', 'bankcode' => $responsedata['bankid'], 'paymode' => $responsedata['txn_process_type'],'callback'=>'S2S');
									$this->db->order_by('id','DESC');
									$this->db->limit(1);	
									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
									
									$payment_query_update=$this->db->last_query();
									
									$log_title ="IPPBNonreg Paymnet update :".$payment_query_update;
									$log_message = serialize($update_data);
									$rId = $get_user_regnum[0]['ref_id'];
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);
									######### Exam Invoice Transaction ############
									$update_data = array('transaction_no'=>$transaction_no);
									$this->db->where('receipt_no',$MerchantOrderNo);
									$this->db->order_by('invoice_id','DESC');
									$this->db->limit(1);	
									$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
									########## Update Member Registration#############
									
									
									// echo 'before update mem_reg'.$reg_id; 
									$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
									$this->db->order_by('regid','DESC');
									$this->db->limit(1);
									$this->db->or_where('regnumber',$reg_id);
									$this->db->or_where('regid',$reg_id);
									$this->master_model->updateRecord('member_registration',$update_mem_data,array());

									########### Update Employee Data IPPB- ADDED BY POOJA MANE: 05-06-2023#############
									$update_data = array('regnumber'=>$applicationNo,'updatedon'=>date('Y-m-d H:i:s'));
									$this->db->order_by('id','DESC');
									$this->db->limit(1);
									$this->master_model->updateRecord('employee_data_ippb',$update_data,array('regnumber'=>$reg_id));

									
									##########Update Member Exam#############
									$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
									$this->db->order_by('id','DESC');
									$this->db->limit(1);
									$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));
									
									
									########## Generate Invoice #############
									$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNo),'transaction_no,date,amount,id');
									//get invoice	
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
									$invoice_get_query= $this->db->last_query();
									$log_title ="IPPB NONreg invoice get query :".$invoice_get_query;
									$log_message = serialize($getinvoice_number);
									$rId = $get_user_regnum[0]['ref_id'];
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);	
									if(count($getinvoice_number) > 0)
									{
										$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
										if($invoiceNumber)
										{
											$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
										}
										$update_data = array('invoice_no' => $invoiceNumber,'member_no'=>$applicationNo,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
										/*Add code trans_start & trans_complete :   */
										$this->db->where('pay_txn_id',$payment_info[0]['id']);
										$this->db->order_by('invoice_id','DESC');
										$this->db->limit(1);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										
										
										$invoice_update_query= $this->db->last_query();
										$log_title ="IPPB NONreg invoice update query :".$invoice_update_query;
										$log_message = serialize($update_data);
										$rId = $get_user_regnum[0]['ref_id'];
										$regNo = $get_user_regnum[0]['member_regnumber'];
										storedUserActivity($log_title, $log_message, $rId, $regNo);	
										
										$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
										$log_title ="IPPB NONreg invoice Img path";
										$log_message = serialize($attachpath);
										$rId = $get_user_regnum[0]['ref_id'];
										$regNo = $get_user_regnum[0]['member_regnumber'];
										storedUserActivity($log_title, $log_message, $rId, $regNo);	
									}
									//Query to get exam details	
									$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period','left');
									$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
									$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
									$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$applicationNo),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
									$exam_info_query = $this->db->last_query();
									
									$log_title ="IPPBNonreg admit_card_details :".$exam_info_query;
									$log_message = serialize($exam_info);
									$rId = $get_user_regnum[0]['ref_id'];
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);
									
									########## Generate Admit card and allocate Seat #############
									if(count($exam_admicard_details) > 0)
									{
										$password=random_password();
										foreach($exam_admicard_details as $row)
										{
											$query='(exam_date = "0000-00-00" OR exam_date = "")';
											$this->db->where($query);
											$this->db->where('session_time=','');
											$this->db->where('venue_flag','P');
											$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'center_code'=>$row['center_code']));
											
											$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$get_user_regnum[0]['ref_id'],'sub_cd'=>$row['sub_cd']));
											
											//echo $this->db->last_query().'<br>';
											$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$admit_card_details[0]['exam_date'],$admit_card_details[0]['time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
											
											if($seat_number!='')
											{
												$final_seat_number = $seat_number;
												$update_data = array('mem_mem_no'=>$applicationNo,'pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
												$this->db->order_by('mem_exam_id','DESC');
												$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
											}
											else
											{
												$admit_card_details=$this->master_model->getRecords('admit_card_details',array('admitcard_id'=>$admit_card_details[0]['admitcard_id'],'remark'=>1));
												if(count($admit_card_details) > 0)
												{
													$log_title ="IPPB nonreg Seat number already allocated id:".$get_user_regnum[0]['member_regnumber'];
													$log_message = serialize($exam_admicard_details);
													$rId = $get_user_regnum[0]['ref_id'];
													$regNo = $get_user_regnum[0]['member_regnumber'];
													storedUserActivity($log_title, $log_message, $rId, $regNo);
												}
												else
												{
													$log_title ="IPPB nonreg Fail user seat allocation id:".$applicationNo;
													$log_message = serialize();
													$rId = $get_user_regnum[0]['ref_id'];
													$regNo = $get_user_regnum[0]['member_regnumber'];
													storedUserActivity($log_title, $log_message, $rId, $regNo);
													//redirect(base_url().'Nonreg/refund/'.base64_encode($MerchantOrderNo));
												}
											}
										}
									}	
									else
									{
										//redirect(base_url().'CSCNonreg/refund/'.base64_encode($MerchantOrderNo));
									}
									
									##############Get Admit card#############
									$admitcard_pdf=genarate_admitcard($applicationNo,$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
									$log_title ="IPPB nonreg admit cart image path:";
									$log_message = serialize($admitcard_pdf);
									$rId = $get_user_regnum[0]['ref_id'];
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);
									
									
									
									if($exam_info[0]['exam_mode']=='ON')
									{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
									{$mode='Offline';}
									else{$mode='';}
									//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
									$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
									$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
									//Query to get Medium	
									$this->db->where('exam_code',$exam_code);
									$this->db->where('exam_period',$exam_info[0]['exam_period']);
									$this->db->where('medium_code',$exam_info[0]['exam_medium']);
									$this->db->where('medium_delete','0');
									$medium=$this->master_model->getRecords('medium_master','','medium_description');
									//Query to get Payment details	
									
									//Query to get user details
									$this->db->join('state_master','state_master.state_code=member_registration.state');
									$this->db->or_where('regnumber',$reg_id);
									$this->db->or_where('regid',$reg_id);
									$result=$this->master_model->getRecords('member_registration',array(),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,usrpassword');	
									
									########get Old image Name############
									$log_title ="IPPB nonreg OLD Image :".$reg_id;
									$log_message = serialize($result);
									$rId = $get_user_regnum[0]['ref_id'];
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);	
									
									$upd_files = array();
									$photo_file = 'p_'.$applicationNo.'.jpg';
									$sign_file = 's_'.$applicationNo.'.jpg';
									$proof_file = 'pr_'.$applicationNo.'.jpg';
									
									/* if(@ rename("./uploads/photograph/".$result[0]['scannedphoto'],"./uploads/photograph/".$photo_file))
									{	$upd_files['scannedphoto'] = $photo_file;	} */
									$chk_photo = update_image_name("./uploads/photograph/", $result[0]['scannedphoto'], $photo_file); //update_image_name_helper.php
									if($chk_photo != "") { $upd_files['scannedphoto'] = $chk_photo; }
									
									/* if(@ rename("./uploads/scansignature/".$result[0]['scannedsignaturephoto'],"./uploads/scansignature/".$sign_file))
									{	$upd_files['scannedsignaturephoto'] = $sign_file;	} */
									$chk_sign = update_image_name("./uploads/scansignature/", $result[0]['scannedsignaturephoto'], $sign_file); //update_image_name_helper.php
									if($chk_sign != "") { $upd_files['scannedsignaturephoto'] = $chk_sign; }
									
									/* if(@ rename("./uploads/idproof/".$result[0]['idproofphoto'],"./uploads/idproof/".$proof_file))
									{	$upd_files['idproofphoto'] = $proof_file;	} */
									$chk_proof = update_image_name("./uploads/idproof/", $result[0]['idproofphoto'], $proof_file); //update_image_name_helper.php
									if($chk_proof != "") { $upd_files['idproofphoto'] = $chk_proof; }
									
									if(count($upd_files)>0)
									{
										$this->db->or_where('regnumber',$reg_id);
										$this->db->or_where('regid',$reg_id);
										$this->master_model->updateRecord('member_registration',$upd_files,array());
										$log_title ="IPPB nonreg PICS Update :".$reg_id;
										$log_message = serialize($this->db->last_query());
										$rId = $get_user_regnum[0]['ref_id'];
										$regNo = $get_user_regnum[0]['member_regnumber'];
										storedUserActivity($log_title, $log_message, $rId, $regNo);	
									}
									else
									{
										$upd_files['scannedphoto'] = $photo_file;
										$upd_files['scannedsignaturephoto'] = $sign_file;	
										$upd_files['idproofphoto'] = $proof_file;
										$this->db->or_where('regnumber',$reg_id);
										$this->db->or_where('regid',$reg_id);
										$this->master_model->updateRecord('member_registration',$upd_files,array());
										$log_title ="IPPB nonreg MANUAL PICS Update :".$reg_id;
										$log_message = serialize($upd_files);
										$rId = $get_user_regnum[0]['ref_id'];
										$regNo = $get_user_regnum[0]['member_regnumber'];
										storedUserActivity($log_title, $log_message, $rId, $regNo);	
									}
									
									$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
									$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
									
									include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
									$key = $this->config->item('pass_key');
									$aes = new CryptAES();
									$aes->set_key(base64_decode($key));
									$aes->require_pkcs5();
									$decpass = $aes->decrypt(trim($result[0]['usrpassword']));
									
									$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'non_member_apply_exam_transaction_success'));
									$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
									$newstring2 = str_replace("#REG_NUM#", "".$applicationNo."",$newstring1);
									$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
									$newstring4 = str_replace("#EXAM_DATE#", "-",$newstring3);
									$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
									$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
									$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
									$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
									$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
									$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
									$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
									$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
									$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
									$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
									$newstring15 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring14);
									$newstring16 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring15);
									$newstring17 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring16);
									$newstring18 = str_replace("#MODE#", "".$mode."",$newstring17);
									$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
									$newstring20 = str_replace("#PASS#", "".$decpass."",$newstring19);
									$elern_msg_string=$this->master_model->getRecords('elearning_examcode');
									if(count($elern_msg_string) > 0)
									{
										foreach($elern_msg_string as $row)
										{
											$arr_elern_msg_string[]=$row['exam_code'];
										}
										if(in_array($exam_info[0]['exam_code'],$arr_elern_msg_string))
										{
											$newstring21 = str_replace("#E-MSG#",$this->config->item('e-learn-msg'),$newstring20);		
										}
										else
										{
											$newstring21 = str_replace("#E-MSG#", '',$newstring20);		
										}
									}
									else
									{
										$newstring21 = str_replace("#E-MSG#", '',$newstring20);
									}
									$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);

									$cc_array = array('iibfdevp@esds.co.in','dd.exm1@iibf.org.in');
									
									$info_arr=array('to'=>$result[0]['email'],
									'cc'=>$cc_array,
									'from'=>$emailerstr[0]['from'],
									'subject'=>$emailerstr[0]['subject'],
									'message'=>$final_str
									);
									if($attachpath!='')
									{		
										//send sms	
										$files=array($attachpath,$admitcard_pdf);				
										$sms_newstring = str_replace("#exam_name#", "".$exam_info[0]['description']."",  $emailerstr[0]['sms_text']);
										$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
										$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
										$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
										//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);						
										$mail_flag=$this->Emailsending->mailsend_attch($info_arr,$files);
										if($mail_flag)
										{
											$log_title ="Mail IPPB nonreg Flag success :".$mail_flag;
											$log_message = serialize($info_arr);
											$rId = $get_user_regnum[0]['ref_id'];
											$regNo = $get_user_regnum[0]['member_regnumber'];
											storedUserActivity($log_title, $log_message, $rId, $regNo);	
										}
										else
										{
											$log_title ="Mail IPPB nonreg Flag fail :".$mail_flag;
											$log_message = serialize($info_arr);
											$rId = $get_user_regnum[0]['ref_id'];
											$regNo = $get_user_regnum[0]['member_regnumber'];
											storedUserActivity($log_title, $log_message, $rId, $regNo);	
										}
										}else{
										
										$log_title ="Attachement IPPB nonreg not found email mail";
										$log_message = serialize($attachpath);
										$rId = $get_user_regnum[0]['ref_id'];
										$regNo = $get_user_regnum[0]['member_regnumber'];
										storedUserActivity($log_title, $log_message, $rId, $regNo);	
									}//Manage Log
									
									
									
								}
								else
								{
									$log_title ="IPPB nonreg S2S Update fail:".$get_user_regnum[0]['member_regnumber'];
									$log_message = serialize($update_data);
									$rId = $MerchantOrderNo;
									$regNo = $get_user_regnum[0]['member_regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);	
								}
								$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
								$this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);
								//redirect(base_url().'Ippbapplyexam/acknowledge/'.base64_encode($MerchantOrderNo));
								
							}
						}
						//IPPB end
					} 
				 
					/* coded added for Elearning separate module end*/
					// add payment responce in log
					if($pay_type == "iibfdra")
					{
						$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=S2S";
						$this->log_model->logdratransaction("billdesk", $pg_response, $transaction_error_type);
					}
					else if($pay_type == "iibfamp")
					{
						$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=S2S";
						$this->log_model->logamptransaction("billdesk", $pg_response, $transaction_error_type);
					}
					else
					{
						$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code."&CALLBACK=S2S";
						$this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);
					}					
				}
				else if($payment_status==0)
				{
          if($pay_type == "BC") //FOR IIBF BCBF MODULE. ADDED BY SAGAR & ANIL ON 15-04-2024
          {
            $this->load->model('iibfbcbf/Iibf_bcbf_model');
            $this->load->helper('iibfbcbf/iibf_bcbf_helper');
            $this->load->helper('file'); 
            $this->load->helper('getregnumber_helper');
            
            $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $MerchantOrderNo, 'payment_mode'=>'Individual'), 'id, status, date');

            if($payment_data[0]['status'] == '2')//IF payment status is PENDING
            {
              // START : UPDATE PAYMENT FAIL STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
              $update_data = array();
              $update_data['transaction_no'] = $transaction_no;
              $update_data['status'] = '0';
              $update_data['transaction_details'] = $transaction_error_type . " - " . $transaction_error_desc;
              $update_data['auth_code'] = '0300';
              $update_data['bankcode'] = $bankid;
              $update_data['paymode'] = $txn_process_type;
              $update_data['callback'] = 'S2S';						
              $update_data['description'] = 'Payment Fail By Individual Candidate : S2S';
              $update_data['approve_reject_date'] = date('Y-m-d H:i:s');
              $update_data['updated_on'] = date('Y-m-d H:i:s');
              $this->master_model->updateRecord('iibfbcbf_payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => '2', 'payment_mode'=>'Individual'));

              $this->Iibf_bcbf_model->insert_common_log('BILLDESK RESPONSE : payment status updated as fail : S2S', 'iibfbcbf_payment_transaction', $this->db->last_query(), $payment_data[0]['id'],'billdesk_action','The payment is fail  : S2S', json_encode($update_data));
              // END : UPDATE PAYMENT FAIL STATUS IN PAYMENT TRANSACTION TABLE AND INSERT LOG
            
              // QUERY TO GET UPDATED PAYMENT DETAILS AFTER PAYMENT SUCCESS
              $payment_info = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no' => $MerchantOrderNo, 'payment_mode'=>'Individual'), 'transaction_no, date, amount, id, description, status, exam_ids, exam_code, exam_period, receipt_no');
        
              if(count($payment_info) > 0 && $payment_info[0]['status'] == '0')
              {
                $member_exam_id = $payment_info[0]['exam_ids'];

                $this->db->join('iibfbcbf_batch_candidates bc', 'bc.candidate_id = me.candidate_id', 'INNER');
                $member_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.member_exam_id' => $member_exam_id, 'payment_mode'=>'Individual'), 'bc.candidate_id, bc.regnumber, bc.candidate_photo, bc.candidate_sign, bc.id_proof_file, bc.qualification_certificate_file, bc.re_attempt');

                if(count($member_data) > 0)
                {
                  //START : UPDATE TRANSACTION NUMBER & PAYMENT STATUS IN iibfbcbf_member_exam
                  $up_exam_data = array();
                  $up_exam_data['ref_utr_no'] = $transaction_no;
                  $up_exam_data['pay_status'] = '0';
                  $this->master_model->updateRecord('iibfbcbf_member_exam',$up_exam_data, array('member_exam_id' => $member_exam_id));
                  
                  $this->Iibf_bcbf_model->insert_common_log('BILLDESK RESPONSE : update transaction number and payment status in member exam : S2S', 'iibfbcbf_member_exam', $this->db->last_query(), $member_exam_id,'billdesk_action','The payment fail transaction number and payment status is updated in member exam : S2S', json_encode($up_exam_data));

                  $this->Iibf_bcbf_model->insert_common_log('Candidate : Candidate payment fail', 'iibfbcbf_batch_candidates', '', $member_data[0]['candidate_id'],'candidate_action','The payment fail for the exam ('.$payment_info[0]['exam_code'].' & '.$payment_info[0]['exam_period'].') by individual.', '');
                }
                
                // START : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
                $exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $payment_info[0]['id'], 'exam_code'=>$payment_info[0]['exam_code'], 'exam_period'=>$payment_info[0]['exam_period'], 'receipt_no'=>$payment_info[0]['receipt_no'], 'app_type'=>'BC'),'invoice_id');
              
                if(count($exam_invoice) > 0 && $payment_info[0]['amount'] > 0)
                {
                  $up_invoice_data['transaction_no'] = $transaction_no;                
                  $up_invoice_data['modified_on'] = date('Y-m-d H:i:s');            
                  $this->master_model->updateRecord('exam_invoice',$up_invoice_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));
                  
                  $this->Iibf_bcbf_model->insert_common_log('BILLDESK RESPONSE : update transaction number for fail payment in invoice : S2S', 'exam_invoice', $this->db->last_query(), $exam_invoice[0]['invoice_id'],'billdesk_action','The exam transaction number is updated in exam invoice table for fail payment : S2S', json_encode($up_invoice_data));
                }// END : UPDATE TRANSACTION NUMBER IN INVOICE TABLE
              
                //xxx $this->send_mail_common('success', $payment_info[0]['member_regnumber'], $payment_info[0]['description'], $payment_info[0]['amount'], $payment_info[0]['transaction_no'], $payment_info[0]['date']);
              }
            }

            $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
            $this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);
          }
          else
          {
            $get_user_regnum_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'ref_id,status');
            if($get_user_regnum_info[0]['status']==2)
            {
              $update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' =>  $transaction_error_type . " - " . $transaction_error_desc,'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type,'callback'=>'S2S');
              $this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
            }
          }
				} 
			}
		}
	
		
		public function sbirefund($MerchantOrderNo = "DP987787930")
		{
			include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$pg_response_url = base_url()."Payment/sbirefundresponse";
			$data["pg_form_url"] = "https://test.sbiepay.com/secure/AggregatorRefundRequest"; // SBI ePay form URL
			$data["merchIdVal"] = $this->config->item('sbi_merchIdVal');
			$data["aggId"] = $this->config->item('sbi_AggregatorId');
			$AggregatorId = $this->config->item('sbi_AggregatorId');
			$RefundRequestID = "1234541870123";
			$RefundAmount = "2";
			$transaction_no = "7008771670841";// ATRN no received from SBI
			//refundRequestParams= AggregatorId|MerchantId|RefundRequestID|ATRN|RefundAmount|Currency|MerchantOrderNo|ResponseURL 
			echo $refundRequestParams = $AggregatorId."|".$data["merchIdVal"]."|".$RefundRequestID."|".$transaction_no."|".$RefundAmount."|INR|".$MerchantOrderNo."|".$pg_response_url;
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$refundRequestParams = $aes->encrypt($refundRequestParams);
			$data["refundRequestParams"] = $refundRequestParams; // SBI encrypted form field value
			$this->load->view('pg_sbirefund_form',$data);
		}
		
		public function sbirefundresponse()
		{
			print_r($_REQUEST);
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
					echo "<Br> encD = ".$encData = $_REQUEST['pushRespData'];
				}
				//echo "<BR>".$merchIdVal;
				echo "<BR>".$encData = $aes->decrypt($_REQUEST['pushRespData']);
				$responsedata = explode("|",$encData);
				print_r($responsedata);
				exit;
				if ($responsedata[6] == "MEM_REG")
				{
					$MerchantOrderNo = filter_var($responsedata[0], FILTER_SANITIZE_NUMBER_INT);//$responsedata[0];
					$transaction_no  = $responsedata[1];
					$payment_status = 2;
					switch ($responsedata[2])
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
					// Update member_regnumber, description, transaction_details, ref_id	
					$update_data = array(
					'transaction_no' => $transaction_no,
					'status' => $payment_status
					);
					echo "MerchantOrderNo = ".$MerchantOrderNo; print_r($update_data);
					//echo $this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
				}
				else if($responsedata[6] == "DRA_EXAM")
				{
				}
				else
				{
				}
				// add payment responce in log
				//$pg_response = "encRefundData=".$encData."&merchIdVal=".$merchIdVal;
				//$this->log_model->logtransaction("SBI_ePay", $pg_response, $responsedata[2]);
			}
			else
			{
				die("Please try again...");
			}
			exit;
		}
		
		// BillDesk payment gateway
		public function make_payment()
		{
			if(isset($_POST['processPayment']) && $_POST['processPayment'])
			{
				$regno = $this->session->userdata('regnumber');//$this->session->userdata('regnumber');
				$MerchantID = $this->config->item('bd_MerchantID');
				$SecurityID = $this->config->item('bd_SecurityID');
				$checksum_key = $this->config->item('bd_ChecksumKey');
				$pg_return_url = base_url()."Payment/pg_response";
				//$amount = trim($this->session->userdata['examinfo']['fee']); // Exam fee//$this->config->item('dup_id_card_fee'); 
				$amount ='1';
				//$MerchantOrderNo = generate_order_id("bd_exam_order_id");
				// Create transaction
				$insert_data = array(
				'member_regnumber' => $regno,
				'amount'           => $amount,
				'gateway'          => "billdesk",
				'date'             => date('Y-m-d H:i:s'),
				'pay_type'         => '2',
				'ref_id'           => $this->session->userdata['examinfo']['insdet_id'],
				'description'      => $this->session->userdata['examinfo']['exname'],
				'status'           => '2',
				'exam_code'    =>base64_decode($this->session->userdata['examinfo']['excd']),
				//'receipt_no'       => $MerchantOrderNo
				);
				$pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
				$MerchantOrderNo = bd_exam_order_id($pt_id);
				// update receipt no. in payment transaction -
				$update_data = array('receipt_no' => $MerchantOrderNo);
				$this->master_model->updateRecord('payment_transaction',$update_data,array('id'=>$pt_id));
				$MerchantCustomerID = $regno;
				$custom_field = "iibfexam";
				$data["pg_form_url"] = $this->config->item('bd_pg_form_url'); // SBI ePay form URL
				/*
					Format:			requestparameter=MerchantID|CustomerID|NA|TxnAmount|NA|NA|NA|CurrencyType|NA|TypeField1|SecurityID|NA|NA|TypeField2|AdditionalInfo1|AdditionalInfo2|AdditionalInfo3|AdditionalInfo4|AdditionalInfo5|NA|NA|RU|Checksum
					Ex.	
					requestparameter=IIBF|2138759|NA|500.00|NA|NA|NA|INR|NA|R|iibf|NA|NA|F|iibfexam|500081141|148201701|NA|NA|NA|NA|http://abc.somedomain.com|2387462372
				*/
				$member_exam_id=$this->session->userdata['examinfo']['insdet_id'];
				$requestparameter = $MerchantID."|".$MerchantOrderNo."|NA|".$amount."|NA|NA|NA|INR|NA|R|".$SecurityID."|NA|NA|F|".$custom_field."|".$MerchantCustomerID."|".$member_exam_id."|NA|NA|NA|NA|".$pg_return_url;
				// Generate checksum for request parameter
				$req_param = $requestparameter."|".$checksum_key;
				$checksum = crc32($req_param);
				$requestparameter = $requestparameter . "|".$checksum;
				$data["msg"] = $requestparameter;
				$this->load->view('pg_bd_form',$data);
			}
			else
			{
				$this->load->view('pg_bd/make_payment_page');
			}
		}
		
		public function pg_response()
		{	
			//$_REQUEST['msg'] = "IIBF|2138196|HYBK4897974090|39740|00000002.00|YBK|NA|01|INR|DIRECT|NA|NA|NA|15-11-2016 13:23:02|0300|NA|iibfexam|510296983|32201701|NA|NA|NA|NA|NA|Merchant transaction successfull|2915503922";
			//	$_REQUEST['msg'] = "IIBF|2138195|HHMP4897894246|NA|2.00|HMP|NA|NA|INR|DIRECT|NA|NA|NA|15-11-2016 12:55:48|0399|NA|iibfexam|510296983|32201701|NA|NA|NA|NA|NA|Canceled By User|1435616898";
			if (isset($_REQUEST['msg']))
			{
				//echo "<pre>";
				//print_r($_REQUEST);
				//echo "<BR> Response : ".$_REQUEST['msg'];
				// validate checksum
				preg_match_all("/(.*)\|([0-9]*)$/", $_REQUEST['msg'],$result);
				//print_r($result);
				$res_checksum = $result[2][0];
				$msg_without_Checksum = $result[1][0];
				//$common_string = "sRKUUgdDrMGL";
				$checksum_key = $this->config->item('bd_ChecksumKey');
				$string_new=$msg_without_Checksum."|".$checksum_key;
				$checksum = crc32($string_new);
				$pg_res = explode("|",$msg_without_Checksum);   //print_r($pg_res); exit;
				// add payment responce in log
				$pg_response = "msg=".$_REQUEST['msg'];
				$this->log_model->logtransaction("billdesk", $pg_response, $pg_res[14]);
				if ($res_checksum == $checksum)
				{
					if($pg_res[16] == "iibfexam")
					{
						$MerchantOrderNo = filter_var($pg_res[1], FILTER_SANITIZE_NUMBER_INT);//$responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
						$transaction_no  = $pg_res[2];
						$payment_status = 2;
						switch ($pg_res[14])
						{
							case "0300":
							$payment_status = 1;
							break;
							case "0399":
							$payment_status = 0;
							break;
							/*case "PENDING":
								$payment_status = 2;
							break;*/
						}
						if($payment_status==1)
						{
							// Handle transaction success case 
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
							if(count($get_user_regnum) > 0)
							{
								$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$get_user_regnum[0]['member_regnumber']),'regnumber,usrpassword,email');
							}
							$update_data = array('pay_status' => '1');
							$this->master_model->updateRecord('member_exam',$update_data,array('id'=>$get_user_regnum[0]['ref_id']));
							$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $pg_res[24],'auth_code' => $pg_res[14],'bankcode' => $pg_res[5],'paymode' => $pg_res[7],'callback'=>'S2S');
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							//Query to get user details
							$this->db->join('state_master','state_master.state_code=member_registration.state');
							$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
							$result=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber')),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');
							//Query to get exam details	
							$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
							$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
							$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
							$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
							$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$this->session->userdata('regnumber'),'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
							if($exam_info[0]['exam_mode']=='ON')
							{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
							{$mode='Offline';}
							else{$mode='';}
							//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
							$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
							$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
							//Query to get Medium	
							$this->db->where('exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
							$this->db->where('exam_period',$exam_info[0]['exam_period']);
							$this->db->where('medium_code',$exam_info[0]['exam_medium']);
							$this->db->where('medium_delete','0');
							$medium=$this->master_model->getRecords('medium_master','','medium_description');
							$this->db->where('state_delete','0');
							$states=$this->master_model->getRecords('state_master',array('state_code'=>$this->session->userdata['examinfo']['state_place_of_work']),'state_name');
							//Query to get Payment details	
							$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$this->session->userdata('regnumber')),'transaction_no,date,amount');
							$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
							$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
							if($this->session->userdata['examinfo']['elected_exam_mode']=='E')
							{
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'member_exam_enrollment_nofee_elective'));
								$this->sms_template_id = 'P6tIFIwGR';
								$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
								$newstring2 = str_replace("#REG_NUM#", "".$this->session->userdata('regnumber')."",$newstring1);
								$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
								$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
								$newstring5 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring4);
								$newstring6 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring5);
								$newstring7 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring6);
								$newstring8 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring7);
								$newstring9 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring8);
								$newstring10 = str_replace("#CITY#", "".$result[0]['city']."",$newstring9);
								$newstring11 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring10);
								$newstring12 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring11);
								$newstring13 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring12);
								$newstring14 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring13);
								$newstring15 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring14);
								$newstring16 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring15);
								$newstring17 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring16);
								$newstring18 = str_replace("#PLACE_OF_WORK#", "".strtoupper($this->session->userdata['examinfo']['placeofwork'])."",$newstring17);
								$newstring19 = str_replace("#STATE_PLACE_OF_WORK#", "".$states[0]['state_name']."",$newstring18);
								$newstring20 = str_replace("#PINCODE_PLACE_OF_WORK#", "".$this->session->userdata['examinfo']['pincode_place_of_work']."",$newstring19);
								$final_str = str_replace("#MODE#", "".$mode."",$newstring20);
							}
					    else
							{
								$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'apply_exam_transaction_success'));
								$this->sms_template_id = 'P6tIFIwGR';
								$newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
								$newstring2 = str_replace("#REG_NUM#", "".$this->session->userdata('regnumber')."",$newstring1);
								$newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
								$newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
								$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
								$newstring6 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring5);
								$newstring7 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring6);
								$newstring8 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring7);
								$newstring9 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring8);
								$newstring10 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring9);
								$newstring11 = str_replace("#CITY#", "".$result[0]['city']."",$newstring10);
								$newstring12 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring11);
								$newstring13 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring12);
								$newstring14 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring13);
								$newstring15 = str_replace("#INSTITUDE#", "".$result[0]['name']."",$newstring14);
								$newstring16 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring15);
								$newstring17 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring16);
								$newstring18 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring17);
								$newstring19 = str_replace("#MODE#", "".$mode."",$newstring18);
								$newstring20 = str_replace("#PLACE_OF_WORK#", "".$result[0]['office']."",$newstring19);
								$newstring21 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring20);
								$final_str = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring21);
							}
							$info_arr=array('to'=>$result[0]['email'],
							'from'=>$emailerstr[0]['from'],
							'subject'=>$emailerstr[0]['subject'],
							'message'=>$final_str
							);
							$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
							$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
							$sms_newstring1 = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
							$sms_newstring2 = str_replace("#fee#", "".$payment_info[0]['amount']."",  $sms_newstring1);
							$sms_final_str = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring2);
							// $this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
							//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
							$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

							//To Do---Transaction email to user	currently we using failure emailer 					
							if($this->Emailsending->mailsend($info_arr))
							{
								//redirect(base_url().'Home/details/'.base64_encode($MerchantOrderNo).'/'.$this->session->userdata['examinfo']['excd']);
							}
						}
						else if($payment_status==0)
						{
							// Handle transaction fail case 
							$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $pg_res[24],'auth_code' => $pg_res[14],'bankcode' => $pg_res[5],'paymode' => $pg_res[7],'callback'=>'S2S');
							$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
							$result=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber')),'firstname,middlename,lastname,email,mobile');
							//Query to get Payment details	
							$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$this->session->userdata('regnumber')),'transaction_no,date,amount');
							// Handle transaction 
							$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'member_regnumber,ref_id');
							//Query to get user details
							$this->db->join('state_master','state_master.state_code=member_registration.state');
							$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
							$result=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber')),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,	pincode,state_master.state_name,institution_master.name');
							//Query to get exam details	
							$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
							$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
							$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
							$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
							$exam_info=$this->master_model->getRecords('member_exam',array('regnumber'=>$this->session->userdata('regnumber'),'member_exam.id'=>$get_user_regnum[0]['ref_id']),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
							//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
							$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
							$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
							$username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
							$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
							$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'transaction_fail'));
							$this->sms_template_id = 'Jw6bOIQGg';
							$newstring1 = str_replace("#application_num#", "".$this->session->userdata('regnumber')."",  $emailerstr[0]['emailer_text']);
							$newstring2 = str_replace("#username#", "".$userfinalstrname."",  $newstring1);
							$newstring3 = str_replace("#transaction_id#", "".$payment_info[0]['transaction_no']."",  $newstring2);
							$final_str = str_replace("#transaction_date#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $newstring3);
							$info_arr=array(	'to'=>$result[0]['email'],
							'from'=>$emailerstr[0]['from'],
							'subject'=>$emailerstr[0]['subject'],
							'message'=>$final_str
							);
							$exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
							$sms_newstring = str_replace("#exam_name#", "".$exm_name_sms."",  $emailerstr[0]['sms_text']);
							$sms_final_str = str_replace("#period#", "".$exam_period_date."",  $sms_newstring);
							//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);	
							//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']),$sms_final_str,$this->sms_template_id);
							$this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 20 Sep 2023

							//To Do---Transaction email to user	currently we using failure emailer 					
							if($this->Emailsending->mailsend($info_arr))
							{
								//redirect(base_url().'Home/fail/'.base64_encode($MerchantOrderNo));
							}
							//echo 'transaction fail';exit;
						}
					}
					///echo "<BR>Checksum validated successfully<br>";
					//echo "SUCCESS:".$pg_res[2];
				}
				else
				{
					//echo "<BR>Checksum validation unsuccessful<br>";
					//echo "INVALID:".$pg_res[2];
				}
				// Redirect to success/failure
			}
			else
			{
				die("Please try again...");	
			}
		}
	}