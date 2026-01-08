<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
		
	date_default_timezone_set("Asia/Kolkata");	
	require_once 'PHP_BridgePG/BridgePGUtil.php';	
	$bconn = new BridgePGUtil ();
	$data = array(); 
	
	// Prepare JSON Post Data
	$data['merchant_id'] =    '69910';
	$data['merchant_txn'] =    '96386062';
	$data['csc_txn'] =    'N';
	
	$message_cipher = $bconn->encrypt("merchant_id=$data[merchant_id]|merchant_txn=$data[merchant_txn]|csc_txn=$data[csc_txn]|");
	echo $message_cipher; exit;
	$json_data_array = array(
	'merchant_id' => '69910',
	'request_data' => $message_cipher
	);
	
	$post = json_encode($json_data_array);
	
	// cURL Request starts here
	$ch = curl_init();
	$headers = array('Content-Type: application/json');
	curl_setopt_array($ch, array(
	CURLOPT_RETURNTRANSFER => 1,
	//            CURLOPT_URL => "http://bridgeapi.csccloud.in/v2/transaction/reverse",
	CURLOPT_URL => "https://bridge.csccloud.in/v2/transaction/status",
	CURLOPT_VERBOSE => true,
	CURLOPT_HEADER => false,
	CURLOPT_HTTPHEADER => $headers,
	CURLINFO_HEADER_OUT => false,
	CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)',
	CURLOPT_POST => 1,
	CURLOPT_POSTFIELDS => $post
	));
	$server_output = curl_exec ($ch);
	
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	if($server_output){
		
		
		
		$xml_response = simplexml_load_string($server_output); 
		$p = $bconn->decrypt($xml_response->response_data);
		
		$p = explode('|',  $p);
		
		$fine_params = array();
		foreach($p as $param){
			
			$param = explode('=', $param);
			if(isset($param[0])){
				$fine_params[$param[0]] = $param[1];
			}	
		}
		$p = $fine_params;
		
		$xml_response = (array) $xml_response;
		
		echo '<pre>';
		print_r($xml_response);
		
		print_r($p) ; 
		
	}
	
	
?>