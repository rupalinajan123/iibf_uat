<?php
session_start();
include("includes/connect_config.php");

	function callback()
	{
		global	$con;
		$code  = $_GET['code'];
		$state = $_GET['state'];
		
		if(!$state || $state != $_SESSION['connect_state'] ){
			exit('STATE mismatch');
		}
		unset($_SESSION['connect_state']);

		if(!$code)
			exit('No code!!');
		//fetch token
		$post_data = array(
			'code' => $code,
			'redirect_uri' => REDIRECT_URI,
			'grant_type' => 'authorization_code',
			'client_id' => CLIENT_ID,
			'client_secret' => encrypt(CLIENT_SECRET)
		);
		
		$token_resp = fetch_data(TOKEN_ENDPOINT, $post_data, false);
		$token_resp_data = (array)json_decode($token_resp);
		
		
		$curl = curl_init();

		$curl_opts = array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'https://connect.csc.gov.in/account/resource?access_token='.$token_resp_data['access_token']
//			CURLOPT_POSTFIELDS => $post
		);

		curl_setopt_array($curl, $curl_opts);
		$result = curl_exec($curl);
		
		
		//die('https://connect.csccloud.in/account/resource?access_token='.$token_resp_data['access_token']);
		
		
		$resp_json = (array)json_decode($result);
		
		if(!isset($resp_json['User']->email) || !isset($resp_json['User']->username))
		{
			print_r($resp_json['error_message']);
			echo "<br>";
			echo "<br>";
			echo "Please Clear the browser cache and retry!!";
			die();
		}
		
		//print_r($resp_json);die;
		
		$username		=	$resp_json['User']->username;// 56486
		$email			=	$resp_json['User']->email;// neeraj@csc.gov.in
		$csc_id			=	$resp_json['User']->csc_id;// 56486
		$state_code		=	$resp_json['User']->state_code;// DL
		$active_status	=	$resp_json['User']->active_status;// 1
		$user_type		=	$resp_json['User']->user_type;// 03
		$last_active	=	$resp_json['User']->last_active;// 2017-02-21 17:10:30
		
		//$user_id		=	$resp_json['User']->user_id;
		//$aadhaar		=	$resp_json['User']->aadhar_number;

		session_start();
		$_SESSION['loggedin']=1;		
		$_SESSION['username']=$username;
		//$_SESSION['user_id']=$user_id;
		$_SESSION['email']=$email;
		//$_SESSION['aadhaar']=$aadhaar;
		$_SESSION['user_type']=$user_type;
		//$start6 = date('Y-m-d H:i:s', time());
		$start6 = microtime(true);

		//print_r($_SESSION); die();
		
	}

	function fetch_data($url, $post, $heads){

		$curl = curl_init();

		$curl_opts = array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,
			CURLOPT_HEADER => false,
			CURLINFO_HEADER_OUT => false,
			CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)',
			CURLOPT_POST => 1,
//			CURLOPT_POSTFIELDS => $post
		);

		if($post && is_array($post) && count($post) > 0 )
			$curl_opts[CURLOPT_POSTFIELDS] = $post;

		if($heads && is_array($heads) && count($heads) > 0 )
			$curl_opts[CURLOPT_HTTPHEADER] = $heads;

		curl_setopt_array($curl, $curl_opts);

		$result = curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		//echo '>>>> '; print_r($httpcode); die();
		
		if(!$result){
			$httpcode = curl_getinfo($curl);
			print_r(array('Error code' => $httpcode, 'URL' => $url, 'post' => $post, 'LOG' => ""));
			exit("Error: 378972");
		}
		curl_close($curl);

		
		return $result;
		//echo $result . "\n\n";
	}
	
	function encrypt($in_t){
		$key = CLIENT_TOKEN;
		$pre = ":";
		$post = "@";
		$plaintext = rand(10, 99) . $pre . $in_t . $post . rand(10,99);
		$iv = "0000000000000000";
		$pval = 16 - (strlen($plaintext) % 16);
		$ptext = $plaintext . str_repeat(chr($pval), $pval);

		$dec = @mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $ptext, MCRYPT_MODE_CBC, $iv );

		return bin2hex($dec);
	}
	
	callback();
