<?php if (!defined('BASEPATH')) { exit('No direct script access alloed'); }
	require FCPATH . 'php-jws-master/vendor/autoload.php';
	
	//CREATED BY VISHAL & SAGAR
	//DESCRIPTION : THIS MODEL IS USED TO VERIFY BILLDESK RESPONSE AND CHECK TRANSACTION STATUS THROUGH QUERY API
	//MODIFIED DATE : 2022-06-09
	class Billdesk_pg_model extends CI_Model
	{
    function __construct() 
		{
			parent::__construct();
			$this->key = $this->config->item('BD_KEY'); //'dEjcgb0OQpLeC4gApFpL5msdPxHt0w79';
			$this->jws = new \Gamegos\JWS\JWS();
			$this->mercid       = $this->config->item('BD_MERCID'); //"UATIIBFV2";
			$this->currency     = $this->config->item('BD_CURRENCY'); //"356";
			$this->gstin        = $this->config->item('BD_GSTIN'); //"12344567";
			$this->billdesk_url = $this->config->item('BD_BILLDESK_URL'); //'https://pguat.billdesk.io/payments/ve1_2/orders/create';
		   // print_r($this->billdesk_url);exit;
		}

	  public function get_client_ip_billdesk() {
			$ipaddress = '';
			if (getenv('HTTP_CLIENT_IP'))
				$ipaddress = getenv('HTTP_CLIENT_IP');
			else if(getenv('HTTP_X_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
			else if(getenv('HTTP_X_FORWARDED'))
				$ipaddress = getenv('HTTP_X_FORWARDED');
			else if(getenv('HTTP_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_FORWARDED_FOR');
			else if(getenv('HTTP_FORWARDED'))
			   $ipaddress = getenv('HTTP_FORWARDED');
			else if(getenv('REMOTE_ADDR'))
				$ipaddress = getenv('REMOTE_ADDR');
			else
				$ipaddress = 'UNKNOWN';
			return $ipaddress;
		}

    public function init_payment_request($orderid, $amount, $invoice_number, $invoice_display_number, $customer_name,$return_url, $cgst = '', $sgst = '', $igst = '',$additional_info='NA')
    {
      $gst = '';
      //echo 'ip add---'.$this->get_client_ip_billdesk();
      $headers = array(
        'alg'      => $this->config->item('BD_ALG'), //'HS256', //alg is required
        'clientid' => $this->config->item('BD_CLIENTID'), //'uatiibfv2',
      );

      if ($this->config->item('bd_payment_mode_sm') == 'production')
      {
        $amount = 1;
      }

      if ($this->config->item('bd_payment_mode_sm') == 'production' && ($this->get_client_ip_billdesk() == '115.124.115.75' || $this->get_client_ip_billdesk() == '115.124.115.69' || $this->get_client_ip_billdesk() == '182.73.101.70' || $this->get_client_ip_billdesk() == '182.69.178.100' || $this->get_client_ip_billdesk() == '106.195.13.76'))
      {
        $amount = 1;
      } 

      //priyanka d - timebeing for jaiib testing
      
      $additional_info1 = "";
      $additional_info2 = "";
      $additional_info3 = "";
      $additional_info4 = "";
      $additional_info5 = "";

      if ($additional_info != "")
      {
        $additional_info_arr  = explode("-", $additional_info);
        if (count($additional_info_arr) > 0)
        {
          $additional_info1 = $additional_info_arr[0];
          $additional_info2 = $additional_info_arr[1];
          $additional_info3 = $additional_info_arr[2];
          $additional_info4 = $additional_info_arr[3];
        }
      }

      if ($additional_info2 == 'IIBF_INST_SUB')
      {
        $this->mercid = 'IIBFBOB';
      }
      else if ($additional_info2 == 'BC' && $additional_info3 == 'IIBF_BULK_BCBF')
      {
        $this->mercid = $this->config->item('BD_MERCID_BULK');////
      }
      else if ($additional_info2 == 'DRA' && $additional_info3 == 'IIBF_BULK_DRA')
      {
        $this->mercid = $this->config->item('BD_MERCID_BULK');////
      }
	  else if($additional_info2=='ncvetPay') {
		//$this->mercid = $this->config->item('BD_MERCID_NCVET');////
	  }
	  
	  
      $random_trace = substr(md5(mt_rand()), 0, 7);
      $bd_traceid   = time() . $random_trace;
      $bd_timestamp = time();

      $invoice_date = date("c");

      $payload = [
        "mercid"     => $this->mercid,
        "orderid"    => $orderid,
        "amount"     => $amount,
        "order_date" => date("c"),
        "currency"   => $this->currency,
        "ru"         => base_url() . $return_url,
        "itemcode"   => "DIRECT",
        "additional_info" => [
          "additional_info1" => $additional_info1,
          "additional_info2" => $additional_info2,
          "additional_info3" => $additional_info3,
          "additional_info4" => $additional_info4
        ],
        /* "invoice"    => [
        "invoice_number"         => $invoice_number,
        "invoice_display_number" => $invoice_display_number,
        "customer_name"          => $customer_name,
        "invoice_date"           => $invoice_date,
        "gst_details"            => [
          "cgst" => $cgst,
          "sgst" => $sgst,
          "igst" => $igst,
          "gst"  => $gst,
        ],
        ], */
        "device"     => [
          "init_channel"  => "internet",
          "ip"            => $_SERVER['REMOTE_ADDR'],
          "user_agent"    => $_SERVER['HTTP_USER_AGENT'],
          "accept_header" => "text/html",
        ],
      ];

      // echo "<pre>"; print_r($payload); exit;

      // ENCODE
      $jwsString = $this->jws->encode($headers, $payload, $this->key);

      ## curl call
      // Prepare new cURL resource
      $crl = curl_init($this->billdesk_url);
      curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($crl, CURLINFO_HEADER_OUT, true);
      curl_setopt($crl, CURLOPT_POST, true);
      curl_setopt($crl, CURLOPT_POSTFIELDS, $jwsString);
      curl_setopt($crl, CURLOPT_TIMEOUT, 65);

      // Set HTTP Header for POST request
      curl_setopt(
        $crl,
        CURLOPT_HTTPHEADER,
        array(
          'Content-Type: application/jose',
          'accept: application/jose',
          'bd-traceid: ' . $bd_traceid, //'ABD1K'
          'bd-timestamp: ' . $bd_timestamp
        )
      );

      $result = curl_exec($crl);
      $err = curl_error($crl);
      curl_close($crl);
      // echo "Success : ".$result;
      // echo "Error : ".$err; exit;
      if ($err)
      { 
        return $err;
      }
        
      // VERIFY
      $pg_data = array();
      $billdesk_pg_res = array();
      $billdesk_pg_res = $this->verify_res($result);      
      // echo "<pre>"; print_r($billdesk_pg_res); exit;
      $insert_info['headers'] = json_encode($headers);
      $insert_info['request'] = json_encode($payload);
      $insert_info['jwsString'] = $jwsString;
      $insert_info['bd_traceid'] = $bd_traceid;
      $insert_info['bd_timestamp'] = $bd_timestamp;
      $insert_info['result'] = json_encode($result);
      $insert_info['billdesk_pg_res'] = json_encode($billdesk_pg_res);
      $insert_info['function_name'] = 'Billdesk_pg_model >> init_payment_request ' . $_SERVER['SERVER_ADDR'];
      $insert_info['created_on'] = date('Y-m-d H:i:s');
      $this->master_model->insertRecord('billdesk_logs', $insert_info);

      $pg_data['billdesk_pg_complete_res'] = $billdesk_pg_res;
      
      if (count($billdesk_pg_res) > 0)
      {
        $pg_data['status'] = $status = $billdesk_pg_res['payload']['status'];

        if ($status == 'ACTIVE')
        {
          $pg_data['bdorderid'] = $billdesk_pg_res['payload']['links'][1]['parameters']['bdorderid'];
          $pg_data['token'] = $billdesk_pg_res['payload']['links'][1]['headers']['authorization'];
          $pg_data['responseXHRUrl'] = $billdesk_pg_res['payload']['links'][1]['href'];
          $pg_data['returnUrl'] = $billdesk_pg_res['payload']['ru'];
        }
        else
        {
          echo '<pre>';
          echo '<br>Headers : '; print_r($headers);
          echo '<br>payload : '; print_r($payload);
          echo '<br>jwsString : '.$jwsString;
          echo '<br>billdesk_url : '.$this->billdesk_url;
          echo '<br>result : '; print_r($result);       
          echo '</pre>';

          echo '<br>verify_res : <pre>'; print_r($billdesk_pg_res); echo '</pre>';die;
        }
      }
      return $pg_data;
    }


		
    function verify_res($res_string)
		{
			return $this->jws->verify($res_string, $this->key);
		}
		
		//START : BILLDESK QRY API (CHECK TRANSACTION STATUS) CREATED BY SAGAR ON 09-06-2022.
		function billdeskqueryapi($MerchantOrderNo)
		{
			//require APPPATH.'../php-jws-master/vendor/autoload.php';
			$headers = array(
			'alg' => $this->config->item('BD_ALG'), //'HS256', 
			'clientid' => $this->config->item('BD_CLIENTID'), //'uatiibfv2'
			);
			$key = $this->config->item('BD_KEY'); //'dEjcgb0OQpLeC4gApFpL5msdPxHt0w79';
			
			$service_url = $this->config->item('BD_SERVICE_URL'); //'https://pguat.billdesk.io/payments/ve1_2/transactions/get';
			
      $mercid   = $this->config->item('BD_MERCID'); //'UATIIBFV2';
      $payment_data = $this->master_model->getRecords('payment_transaction', array('receipt_no'=>$MerchantOrderNo), 'pg_flag');
      if(count($payment_data) > 0 && $payment_data[0]['pg_flag'] == 'IIBF_INST_SUB' && $MerchantOrderNo != '833') 
      { 
        $mercid   = 'IIBFBOB'; 
      }
      else if(count($payment_data) == 0)
      {
        $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no'=>$MerchantOrderNo), 'pg_flag');
        if(count($payment_data) > 0 && $payment_data[0]['pg_flag'] == 'BC') 
        { 
          $mercid   = $this->config->item('BD_MERCID_BULK'); ////
        }
        else
	      {
	        $payment_data = $this->master_model->getRecords('dra_payment_transaction', array('receipt_no'=>$MerchantOrderNo), 'pg_flag');
	        if(count($payment_data) > 0 && $payment_data[0]['pg_flag'] == 'iibfbulkdra') 
	        { 
	          $mercid   = $this->config->item('BD_MERCID_BULK'); ////
	        }
	      }
      }
      // echo $MerchantOrderNo;
      
			$orderId  = $MerchantOrderNo;//'900485161';  Test number
			$payload = [
			"mercid"     => $mercid,
			"orderid"    => $orderId,
			"refund_details"=>true
			];
			$random_trace = substr(md5(mt_rand()), 0, 7);
			$bd_traceid = time() .$random_trace;
			$bd_timestamp =time();
			
			$jws = new \Gamegos\JWS\JWS();	
			$jwsString = $jws->encode($headers, $payload, $key);		
			
			$ch = curl_init();       
			curl_setopt($ch, CURLOPT_URL,$service_url);
			// Set HTTP Header for POST request
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/jose',
			'accept: application/jose',
			'bd-traceid: ' . $bd_traceid,
			'bd-timestamp: ' . $bd_timestamp)
			);
			curl_setopt($ch, CURLOPT_POST, true); 
			curl_setopt($ch, CURLOPT_POSTFIELDS,$jwsString);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$info = curl_getinfo($ch);
			$result = curl_exec($ch);
			curl_close($ch);
			
			$txn_query_result = $jws->verify($result, $key);

			$insert_info['headers'] = json_encode($headers);
			$insert_info['request'] = json_encode($payload);
			$insert_info['jwsString'] = $jwsString;
			$insert_info['bd_traceid'] = $bd_traceid;
			$insert_info['bd_timestamp'] = $bd_timestamp;
			$insert_info['result'] = json_encode($result);
			$insert_info['billdesk_pg_res'] = json_encode($txn_query_result);
			$insert_info['function_name'] = 'Billdesk_pg_model >> billdeskqueryapi';
			$insert_info['created_on'] = date('Y-m-d H:i:s');
			$this->master_model->insertRecord('billdesk_logs', $insert_info);

			return $txn_query_result['payload'];
			//echo $txn_query_result['payload'];exit;
		}
		//END : BILLDESK QRY API (CHECK TRANSACTION STATUS) CREATED BY SAGAR ON 09-06-2022.

				//START : BILLDESK QRY API (CHECK TRANSACTION STATUS) CREATED BY SAGAR ON 09-06-2022.
		function billdeskqueryapi_old_transactionsbilldeskqueryapi_old_transactions($billdesk_txn_number)
		{
			//require APPPATH.'../php-jws-master/vendor/autoload.php';
			$headers = array(
			'alg' => $this->config->item('BD_ALG'), //'HS256', 
			'clientid' => $this->config->item('BD_CLIENTID'), //'uatiibfv2'
			);
			$key = $this->config->item('BD_KEY'); //'dEjcgb0OQpLeC4gApFpL5msdPxHt0w79';
			
			$service_url = $this->config->item('BD_SERVICE_URL'); //'https://pguat.billdesk.io/payments/ve1_2/transactions/get';
			$mercid   = $this->config->item('BD_MERCID'); //'UATIIBFV2';
			$orderId  = $billdesk_txn_number;//'900485161';  Test number
			$payload = [
			"mercid"     => $mercid,
			"transactionid"    => $orderId
			];
			$random_trace = substr(md5(mt_rand()), 0, 7);
			$bd_traceid = time() .$random_trace;
			$bd_timestamp =time();
			
			$jws = new \Gamegos\JWS\JWS();	
			$jwsString = $jws->encode($headers, $payload, $key);		
			$ch = curl_init();       
			curl_setopt($ch, CURLOPT_URL,$service_url);
			// Set HTTP Header for POST request
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/jose',
			'accept: application/jose',
			'bd-traceid: ' . $bd_traceid,
			'bd-timestamp: ' . $bd_timestamp)
			);
			curl_setopt($ch, CURLOPT_POST, true); 
			curl_setopt($ch, CURLOPT_POSTFIELDS,$jwsString);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$info = curl_getinfo($ch);
			$result = curl_exec($ch);
			curl_close($ch);
			
			$txn_query_result = $jws->verify($result, $key);

			$insert_info['headers'] = json_encode($headers);
			$insert_info['request'] = json_encode($payload);
			$insert_info['jwsString'] = $jwsString;
			$insert_info['bd_traceid'] = $bd_traceid;
			$insert_info['bd_timestamp'] = $bd_timestamp;
			$insert_info['result'] = json_encode($result);
			$insert_info['billdesk_pg_res'] = json_encode($txn_query_result);
			$insert_info['function_name'] = 'Billdesk_pg_model >> billdeskqueryapi';
			$insert_info['created_on'] = date('Y-m-d H:i:s');
			$this->master_model->insertRecord('billdesk_logs', $insert_info);
			$data = [];

			$data['bd-traceid']=$bd_traceid;
			$data['bd-timestamp']=$bd_timestamp;
			$data['Decrypted_request']=$payload;
			$data['Decrypted_response']=$txn_query_result;
			return $txn_query_result['payload'];
		}
		//END : BILLDESK QRY API (CHECK TRANSACTION STATUS) CREATED BY SAGAR ON 09-06-2022.
				
		//START : BILLDESK INITIATE REFUND REQUEST API CREATED BY SAGAR ON 18-06-2022.
		function billdeskRefundApi($MerchantOrderNo)
		{
			//require APPPATH.'../php-jws-master/vendor/autoload.php';
			$headers = array(
			'alg' => $this->config->item('BD_ALG'), //'HS256', 
			'clientid' => $this->config->item('BD_CLIENTID'), //'uatiibfv2'
			);
			$key = $this->config->item('BD_KEY'); //'dEjcgb0OQpLeC4gApFpL5msdPxHt0w79';
			
			## Validate status of given orderId. If it is success then proceed refund request
			$qry_api_response = $this->billdeskqueryapi($MerchantOrderNo);
			
			/* echo 'Transaction Details : <pre>';	 print_r($qry_api_response); echo '</pre>';
			die; */
			if(count($qry_api_response) > 0 && isset($qry_api_response['auth_status']) && $qry_api_response['auth_status'] == '0300')
			{		
				$random_trace = substr(md5(mt_rand()), 0, 7);		
				$bd_traceid = time() .$random_trace;
				$bd_timestamp =time();
				$refund_url = $this->config->item('BD_REFUND_URL'); //'https://pguat.billdesk.io/payments/ve1_2/refunds/create';
				
        $mercid   = $this->config->item('BD_MERCID'); //'UATIIBFV2';
        $payment_data = $this->master_model->getRecords('payment_transaction', array('receipt_no'=>$MerchantOrderNo), 'pg_flag');
        
        if(count($payment_data) > 0 && $payment_data[0]['pg_flag'] == 'IIBF_INST_SUB' && $MerchantOrderNo != '833') 
        { echo "if";
          $mercid   = 'IIBFBOB'; 
        }
        else if(count($payment_data) == 0)
        { 
          $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no'=>$MerchantOrderNo), 'pg_flag');

          if(count($payment_data) > 0 && $payment_data[0]['pg_flag'] == 'BC') 
          { 
            $mercid   = $this->config->item('BD_MERCID_BULK'); ////
          }
          else if(isset($qry_api_response['mercid']) && $qry_api_response['mercid'] == 'IIBFBLK')
	        { 
	          $payment_data = $this->master_model->getRecords('dra_payment_transaction', array('receipt_no'=>$MerchantOrderNo), 'pg_flag');

	          if(count($payment_data) > 0 && $payment_data[0]['pg_flag'] == 'iibfbulkdra') 
	          { 
	            $mercid   = $qry_api_response['mercid']; ////
	          }
	        }
        }
       
				$orderId  = $MerchantOrderNo;//'900485161';  Test number
				$txn_id   = $qry_api_response['transactionid'];
				$txn_date = $qry_api_response['transaction_date'];
				$txn_amount = $qry_api_response['amount'];
				$currency = $qry_api_response['currency'];
				$merc_refund_ref_no = 'REF_'.$orderId;
				$payload = [
						"transactionid" => $txn_id,
						"orderid"    => $orderId,
						"mercid"     => $mercid,
						"transaction_date" =>$txn_date,
						"txn_amount"=>$txn_amount,
						"refund_amount"=>$txn_amount,
						"currency"=>$currency,
						"merc_refund_ref_no"=>$merc_refund_ref_no
						];
						
				//echo '<br/> Payload : <pre>';	print_r($payload); echo '</pre>';	
				
				$jws = new \Gamegos\JWS\JWS();	
				$jwsString = $jws->encode($headers, $payload, $key);		
				//echo "<br/>".$jwsString."<br/>";
				
				$ch = curl_init();       
				curl_setopt($ch, CURLOPT_URL,$refund_url);
				// Set HTTP Header for POST request
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/jose',
					'accept: application/jose',
					'bd-traceid: ' . $bd_traceid, //REF1K
					'bd-timestamp: ' . $bd_timestamp)
				);
				curl_setopt($ch, CURLOPT_POST, true); 
				curl_setopt($ch, CURLOPT_POSTFIELDS,$jwsString);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$info = curl_getinfo($ch);
				//print_r($info);
				$result = curl_exec($ch);
				curl_close($ch);
				$refund_result = $jws->verify($result, $key);

				$insert_info['headers'] = json_encode($headers);
				$insert_info['request'] = json_encode($payload);
				$insert_info['jwsString'] = $jwsString;
				$insert_info['bd_traceid'] = $bd_traceid;
				$insert_info['bd_timestamp'] = $bd_timestamp;
				$insert_info['result'] = json_encode($result);
				$insert_info['billdesk_pg_res'] = json_encode($refund_result);
				$insert_info['function_name'] = 'Billdesk_pg_model >> billdeskRefundApi';
				$insert_info['created_on'] = date('Y-m-d H:i:s');
				$this->master_model->insertRecord('billdesk_logs', $insert_info);
				
				return $refund_result['payload'];
			}
			else
			{
				return array("message"=>"Invalid transaction.");
			}
		}	
		//END : BILLDESK INITIATE REFUND REQUEST API CREATED BY SAGAR ON 18-06-2022.


			//START : BILLDESK INITIATE REFUND REQUEST API CREATED BY SAGAR ON 18-06-2022.
		function billdeskRefundApi_old_txn($billdesk_txn_no,$MerchantOrderNo)
		{
			//require APPPATH.'../php-jws-master/vendor/autoload.php';
			$headers = array(
			'alg' => $this->config->item('BD_ALG'), //'HS256', 
			'clientid' => $this->config->item('BD_CLIENTID'), //'uatiibfv2'
			);
			$key = $this->config->item('BD_KEY'); //'dEjcgb0OQpLeC4gApFpL5msdPxHt0w79';
			
			## Validate status of given orderId. If it is success then proceed refund request
			$qry_api_response = $this->billdeskqueryapi_old_transactions($billdesk_txn_no);
			// echo "<pre>";
			// print_r($qry_api_response);
			// die;
			/* echo 'Transaction Details : <pre>';	 print_r($qry_api_response); echo '</pre>';
			die; */
			if(count($qry_api_response) > 0 && isset($qry_api_response['auth_status']) && $qry_api_response['auth_status'] == '0300')
			{		
				$random_trace = substr(md5(mt_rand()), 0, 7);		
				$bd_traceid = time() .$random_trace;
				$bd_timestamp =time();
				$refund_url = $this->config->item('BD_REFUND_URL'); //'https://pguat.billdesk.io/payments/ve1_2/refunds/create';
				$mercid   = $this->config->item('BD_MERCID'); //'UATIIBFV2';
				$orderId  = $MerchantOrderNo;//'900485161';  Test number
				$txn_id   = $qry_api_response['transactionid'];
				$txn_date = $qry_api_response['transaction_date'];
				$txn_amount = $qry_api_response['amount'];
				$currency = $qry_api_response['currency'];
				$merc_refund_ref_no = 'REF_'.$orderId;
				$payload = [
						"transactionid" => $txn_id,
						"orderid"    => $orderId,
						"mercid"     => $mercid,
						"transaction_date" =>$txn_date,
						"txn_amount"=>$txn_amount,
						"refund_amount"=>$txn_amount,
						"currency"=>$currency,
						"merc_refund_ref_no"=>$merc_refund_ref_no
						];
						
				//echo '<br/> Payload : <pre>';	print_r($payload); echo '</pre>';	
				
				$jws = new \Gamegos\JWS\JWS();	
				$jwsString = $jws->encode($headers, $payload, $key);		
				//echo "<br/>".$jwsString."<br/>";
				
				$ch = curl_init();       
				curl_setopt($ch, CURLOPT_URL,$refund_url);
				// Set HTTP Header for POST request
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/jose',
					'accept: application/jose',
					'bd-traceid: ' . $bd_traceid, //REF1K
					'bd-timestamp: ' . $bd_timestamp)
				);
				curl_setopt($ch, CURLOPT_POST, true); 
				curl_setopt($ch, CURLOPT_POSTFIELDS,$jwsString);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$info = curl_getinfo($ch);
				//print_r($info);
				$result = curl_exec($ch);
				curl_close($ch);
				$refund_result = $jws->verify($result, $key);

				$insert_info['headers'] = json_encode($headers);
				$insert_info['request'] = json_encode($payload);
				$insert_info['jwsString'] = $jwsString;
				$insert_info['bd_traceid'] = $bd_traceid;
				$insert_info['bd_timestamp'] = $bd_timestamp;
				$insert_info['result'] = json_encode($result);
				$insert_info['billdesk_pg_res'] = json_encode($refund_result);
				$insert_info['function_name'] = 'Billdesk_pg_model >> billdeskRefundApi';
				$insert_info['created_on'] = date('Y-m-d H:i:s');
				$this->master_model->insertRecord('billdesk_logs', $insert_info);
				
				return $refund_result['payload'];
			}
			else
			{
				return array("message"=>"Invalid transaction.");
			}
		}	
		//END : BILLDESK INITIATE REFUND REQUEST API CREATED BY SAGAR ON 18-06-2022.



		
		//START : BILLDESK CHECK REFUND REQUEST STATUS API CREATED BY SAGAR ON 18-06-2022.
		function billdeskRefundStatusApi($MerchantOrderNo)
		{
			//require APPPATH.'../php-jws-master/vendor/autoload.php';
			$headers = array(
			'alg' => $this->config->item('BD_ALG'), //'HS256', 
			'clientid' => $this->config->item('BD_CLIENTID'), //'uatiibfv2'
			);
			$key = $this->config->item('BD_KEY'); //'dEjcgb0OQpLeC4gApFpL5msdPxHt0w79';
			
			$service_url = $this->config->item('BD_REFUND_STATUS_URL');
			
      $mercid   = $this->config->item('BD_MERCID'); //'UATIIBFV2';
      $payment_data = $this->master_model->getRecords('payment_transaction', array('receipt_no'=>$MerchantOrderNo), 'pg_flag');
      
      if(count($payment_data) > 0 && $payment_data[0]['pg_flag'] == 'IIBF_INST_SUB' && $MerchantOrderNo != '833') 
      { 
        $mercid   = 'IIBFBOB'; 
      }
      else if(count($payment_data) == 0)
      {
        $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('receipt_no'=>$MerchantOrderNo), 'pg_flag');
        if(count($payment_data) > 0 && $payment_data[0]['pg_flag'] == 'BC') 
        { 
          $mercid   = $this->config->item('BD_MERCID_BULK'); ////
        }
      }
      
			$orderId  = $MerchantOrderNo;//'900485161';  Test number
			$payload = [
			"mercid"     => $mercid,
			"orderid"    => $orderId,
			"merc_refund_ref_no"=>'REF_'.$orderId,
			];
			
			$jws = new \Gamegos\JWS\JWS();	
			$jwsString = $jws->encode($headers, $payload, $key);		
			$ch = curl_init();       
			curl_setopt($ch, CURLOPT_URL,$service_url);
			// Set HTTP Header for POST request
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/jose',
			'accept: application/jose',
			'bd-traceid: ' . time() . 'ABD1K',
			'bd-timestamp: ' . time())
			);
			curl_setopt($ch, CURLOPT_POST, true); 
			curl_setopt($ch, CURLOPT_POSTFIELDS,$jwsString);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$info = curl_getinfo($ch);
			$result = curl_exec($ch);
			curl_close($ch);
			
			$txn_query_result = $jws->verify($result, $key);
			//echo '<pre>'; print_r($txn_query_result['payload']);exit;
			return $txn_query_result['payload'];
		}	
		//END : BILLDESK CHECK REFUND REQUEST STATUS API CREATED BY SAGAR ON 18-06-2022.


		//START : BILLDESK CHECK REFUND REQUEST STATUS API CREATED BY SAGAR ON 18-06-2022.
		function billdeskRefundStatusApi_new($MerchantOrderNo)
		{
			//require APPPATH.'../php-jws-master/vendor/autoload.php';
			$headers = array(
			'alg' => $this->config->item('BD_ALG'), //'HS256', 
			'clientid' => $this->config->item('BD_CLIENTID'), //'uatiibfv2'
			);
			$key = $this->config->item('BD_KEY'); //'dEjcgb0OQpLeC4gApFpL5msdPxHt0w79';
			
			$service_url = $this->config->item('BD_REFUND_STATUS_URL');
			$mercid   = $this->config->item('BD_MERCID'); //'UATIIBFV2';
			$orderId  = $MerchantOrderNo;//'900485161';  Test number
			$payload = [
			"mercid"     => $mercid,
			"transactionid"    => $orderId,
			"merc_refund_ref_no"=>'REF_'.$orderId,
			];
			
			$jws = new \Gamegos\JWS\JWS();	
			$jwsString = $jws->encode($headers, $payload, $key);		
			$ch = curl_init();       
			curl_setopt($ch, CURLOPT_URL,$service_url);
			// Set HTTP Header for POST request
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/jose',
			'accept: application/jose',
			'bd-traceid: ' . time() . 'ABD1K',
			'bd-timestamp: ' . time())
			);
			curl_setopt($ch, CURLOPT_POST, true); 
			curl_setopt($ch, CURLOPT_POSTFIELDS,$jwsString);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$info = curl_getinfo($ch);
			$result = curl_exec($ch);
			curl_close($ch);
			
			$txn_query_result = $jws->verify($result, $key);
			//echo '<pre>'; print_r($txn_query_result['payload']);exit;
			return $txn_query_result['payload'];
		}	
		//END : BILLDESK CHECK REFUND REQUEST STATUS API CREATED BY SAGAR ON 18-06-2022.

				public function init_payment_request_demo($orderid, $amount, $invoice_number, $invoice_display_number, $customer_name,$return_url, $cgst = '', $sgst = '', $igst = '',$additional_info='NA')
    {
			$headers = array(
			'alg'      => $this->config->item('BD_ALG'), //'HS256', //alg is required
			'clientid' => $this->config->item('BD_CLIENTID'), //'uatiibfv2',
			);

			$additional_info1 = "" ;
			$additional_info2 = "" ;
			$additional_info3 = "" ;
			$additional_info4 = "" ;
			if ($additional_info!="") {
				$additional_info_arr  = explode("-", $additional_info);
				if (count($additional_info_arr) > 0) {
					$additional_info1 = $additional_info_arr[0];
					$additional_info2 = $additional_info_arr[1];
					$additional_info3 = $additional_info_arr[2];
					$additional_info4 = $additional_info_arr[3];
				}
			}
			$random_trace = substr(md5(mt_rand()), 0, 7);
	
			$invoice_date = date("c");
			
			$payload = [
			"mercid"     => $this->mercid,
			"orderid"    => $orderid,
			"amount"     => $amount,
			"order_date" => date("c"),
			"currency"   => $this->currency,
			"ru"         => base_url().$return_url,
			"itemcode"   => "DIRECT",
			"additional_info" => [
					"additional_info1" => $additional_info1,
					"additional_info2" => $additional_info2,
					"additional_info3" => $additional_info3,
					"additional_info4" => $additional_info4
			],
			"invoice"    => [
			"invoice_number"         => $invoice_number,
			"invoice_display_number" => $invoice_display_number,
			"customer_name"          => $customer_name,
			"invoice_date"           => $invoice_date,
			"gst_details"            => [
			"cgst" => $cgst,
			"sgst" => $sgst,
			"igst" => $igst,
			"gst"  => $gst,
			],
			],
			"device"     => [
			"init_channel"  => "internet",
			"ip"            => $_SERVER['REMOTE_ADDR'],
			"user_agent"    => $_SERVER['HTTP_USER_AGENT'],
			"accept_header" => "text/html",
			],
			];
			
			//echo '<pre> headers : '; print_r($headers); echo '</pre>';//xxx
			//echo '<pre> Request : '; print_r($payload); echo '</pre>';//xxx
			// ENCODE
			$jwsString = $this->jws->encode($headers, $payload, $this->key);
			//echo '<br>jwsString : '.$jwsString;//xxx
			## curl call
			// Prepare new cURL resource
			$crl = curl_init($this->billdesk_url);
			curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($crl, CURLINFO_HEADER_OUT, true);
			curl_setopt($crl, CURLOPT_POST, true);
			curl_setopt($crl, CURLOPT_POSTFIELDS, $jwsString);
      curl_setopt($crl, CURLOPT_TIMEOUT, 65);
			
			// Set HTTP Header for POST request
			curl_setopt($crl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/jose',
			'accept: application/jose',
			'bd-traceid: ' . time() .$random_trace, //'ABD1K'
			'bd-timestamp: ' . time())
			);
			
			//echo '<br>bd-traceid: ' . time() . 'ABD1K';//xxx
			$bd_traceid = time() . 'ABD1K';//xxx
			//echo '<br>bd-timestamp: ' . time();//xxx
			$bd_timestamp =time();//xxx
			$result = curl_exec($crl);
			$response = curl_exec($ch);

			// $info = curl_getinfo($ch);
			// var_dump($info);

			curl_close($crl);
			// VERIFY
			$pg_data = array();
			$billdesk_pg_res = array();
			//echo '<pre> result : '; print_r($result); echo '</pre>';//xxx
			
			$billdesk_pg_res = $this->verify_res($result);
			//echo '<pre> verify_res : '; print_r($billdesk_pg_res); echo '</pre>';//xxx
			
			$insert_info['headers'] = json_encode($headers);
			$insert_info['request'] = json_encode($payload);
			$insert_info['jwsString'] = $jwsString;
			$insert_info['bd_traceid'] = $bd_traceid;
			$insert_info['bd_timestamp'] = $bd_timestamp;
			$insert_info['result'] = json_encode($result);
			$insert_info['billdesk_pg_res'] = json_encode($billdesk_pg_res);
			$insert_info['created_on'] = date('Y-m-d H:i:s');
			//$this->master_model->insertRecord('billdesk_details_tmp', $insert_info);
			
			$pg_data['billdesk_pg_complete_res'] = $billdesk_pg_res;
			if (count($billdesk_pg_res) > 0) {
				
				$pg_data['status'] = $status = $billdesk_pg_res['payload']['status'];
				
				if ($status == 'ACTIVE') 
				{
					$pg_data['bdorderid'] = $billdesk_pg_res['payload']['links'][1]['parameters']['bdorderid'];
					$pg_data['token'] = $billdesk_pg_res['payload']['links'][1]['headers']['authorization'];
					$pg_data['responseXHRUrl'] = $billdesk_pg_res['payload']['links'][1]['href'];
					$pg_data['returnUrl'] = $billdesk_pg_res['payload']['ru']; 
				}
			}
			return $pg_data;
		}
	}
