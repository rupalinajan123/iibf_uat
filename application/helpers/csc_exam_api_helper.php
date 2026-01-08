<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
 

// Push Notification Start 
function call_api($url, $API_access_key)
{
	
	$CI = &get_instance();
	
	$headers = array
	(
		'Authorization: key=' . $api_access_key,
		'Content-Type: application/json'
	);

	#Send Reponse To FireBase Server	
	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, $url);
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch );
	curl_close( $ch );

	#Echo Result Of FireBase Server
	return $result;
	//return true;
}