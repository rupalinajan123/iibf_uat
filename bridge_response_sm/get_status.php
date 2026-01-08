<?php
	date_default_timezone_set("Asia/Kolkata");	
	require_once 'PHP_BridgePG/BridgePGUtil.php';	
	$bconn = new BridgePGUtil();
	$BridgePG_obj = new BridgePG();
	
	$tid = '';
	$csc_txn = 'N';
  if(isset($_GET['tid'])) { $tid = $_GET['tid']; }
  if(isset($_GET['csc_txn'])) { $csc_txn = $_GET['csc_txn']; }
	
	$data = array();	
	// Prepare JSON Post Data
	$data['merchant_id'] = $merchant_id = MERCHANT_ID;
	$data['merchant_txn'] = $merchant_txn = $tid;
	$data['csc_txn'] = $csc_txn;
	
	$message_cipher = $BridgePG_obj->encrypt_message_for_wallet("merchant_id=$merchant_id|merchant_txn=$merchant_txn|csc_txn=$csc_txn|");
	
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
	//CURLOPT_URL => "http://bridgeapi.csccloud.in/v2/transaction/reverse",
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
	
	if($server_output)
	{
		$d = "";
		$xml_response = simplexml_load_string($server_output); 
		
		$p = @$BridgePG_obj->decrypt_wallet_message($xml_response->response_data, $d, FALSE);		
		$p = explode('|',  $p); 
		
		$fine_params = array();
		foreach($p as $param) 
		{			
			$param = explode('=', $param);
			if(isset($param[0]))
			{
				if(isset($param[1]))
				{
					$fine_params[$param[0]] = $param[1];
				}
				else
				{
					$fine_params[$param[0]] = '';
				}
			}	
		}
		
		$p = $fine_params;		
		$xml_response = (array) $xml_response;
		
		echo '<pre>'; print_r($xml_response); echo '</pre>'; 
		
		print_r($p);		
	}
?>