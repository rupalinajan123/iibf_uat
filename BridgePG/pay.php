<?php

	date_default_timezone_set("Asia/Kolkata");
	
	//require '../includes/initialize.php';
	
	//check_login();
	//die('d');
	require_once 'PHP_BridgePG/BridgePGUtil.php';
	
	
	$amt = '1000';

	$bconn = new BridgePGUtil();
	$p = array(
	//  'csc_id' => $_SESSION['user']['username'],
		'csc_id' => 114987600012,
		'merchant_receipt_no' => 'Recpt#' . rand(100,999),
		'return_url' => 'http://52.77.247.10/pet_app/public/vle/BridgePG/pay_success.php',
		'cancel_url' => 'http://52.77.247.10/pet_app/public/vle/BridgePG/pay_success.php',
		'txn_amount' => '50',
		'product_id' => '5648623762',
		'merchant_txn' => 'MW' . rand(1000, 9999)
	);

	$bconn->set_params($p);
	$enc_text = $bconn->get_parameter_string();
	$frac = $bconn->get_fraction();
	?>


	<center>
		<img src="img/loading.gif" />
	</center>
	<form method="post" id="pay" action="https://wallet.csccloud.in/v1/payment/<?php echo $frac;?>">
	   <input type="hidden" name="message" value="<?=$enc_text;?>" />
	
	</form>
	<script src="js/jquery.min.js"></script>
	<script>
		$(function(){
			
			setTimeout(function(){
				
				$('#pay').submit();
			}, 1500);
			
			
			
		});
	</script>