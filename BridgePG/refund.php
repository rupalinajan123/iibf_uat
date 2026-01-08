<?php
	date_default_timezone_set("Asia/Kolkata");
	
	require_once 'PHP_BridgePG/BridgePGUtil.php';

	$bconn = new BridgePGUtil ();
	
	
	$data = array();

        // Prepare JSON Post Data
        $data['merchant_id'] = MERCHANT_ID;
        $data['csc_txn'] = '0157020622911114';//
        $data['merchant_txn'] = '6991065101039';//
        $data['merchant_txn_param'] = 'N';
        $data['merchant_txn_status'] = 'S';
        $data['merchant_reference'] = rand(0, 999999);
        $data['refund_deduction'] = '944.00';//
        $data['refund_mode'] = 'F';
        $data['refund_type'] = 'R';
        $data['refund_trigger'] = 'M';
        $data['refund_reason'] = 'Mail CSC Transactions for which amount is received in bank but invoice not generated';//
		
		
		
		 $str = "merchant_id=$data[merchant_id]|csc_txn=$data[csc_txn]|merchant_txn=$data[merchant_txn]|merchant_txn_param=$data[merchant_txn_param]|merchant_txn_status=$data[merchant_txn_status]|merchant_reference=$data[merchant_reference]|refund_deduction=$data[refund_deduction]|refund_mode=$data[refund_mode]|refund_type=$data[refund_type]|refund_trigger=$data[refund_trigger]|refund_reason=$data[refund_reason]|";


        $message_cipher = $bconn->encrypt($str);

        $json_data_array = array(
            'merchant_id' => MERCHANT_ID,
            'request_data' => $message_cipher
        );

        $post = json_encode($json_data_array);
		
		
		// cURL Request starts here
        $ch = curl_init();
        $headers = array('Content-Type: application/json');
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => 1,
//            CURLOPT_URL => "http://bridgeapi.csccloud.in/v2/refund/log",
            CURLOPT_URL => "https://bridge.csccloud.in/v2/refund/log",
            CURLOPT_VERBOSE => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => $headers,
            CURLINFO_HEADER_OUT => false,
            CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $post
        ));
        $server_output = curl_exec($ch);

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
        if ($server_output != '') {



            $xml_response = simplexml_load_string($server_output);
            $p = $bconn->decrypt($xml_response->response_data);

            $p = explode('|', $p);

            $fine_params = array();
            foreach ($p as $param) {

                $param = explode('=', $param);
                if (isset($param[0])) {
                    $fine_params[$param[0]] = $param[1];
                }
            }
            $p = $fine_params;



            $xml_response = (array) $xml_response;

			
           print_r($p);
        }
	
	
	?>


	
	