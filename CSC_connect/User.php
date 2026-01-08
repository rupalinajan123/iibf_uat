<?php 
	session_start();
	include("includes/connect_config.php");
	
  function login(){
		//create a state ..
		$state = rand(10000, 99999);
		$_SESSION['connect_state'] = $state;
		$_SESSION['connect_state'] = $state;
		$auth_parameters = 
			"response_type=code&client_id=" . CLIENT_ID .
			"&redirect_uri=" . REDIRECT_URI .
			"&state=" . $state;
			
		$url = AUTHORIZATION_ENDPOINT . "?" . $auth_parameters;
		header('Location: ' . $url);
		die('Redirect');	
	}

login();
