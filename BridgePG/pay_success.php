<?php



	date_default_timezone_set("Asia/Kolkata");
	
	require '../includes/initialize.php';
	
	check_login();
	
	

	require_once 'PHP_BridgePG/BridgePGUtil.php';

	$bconn = new BridgePGUtil ();
	$bridge_message = $bconn->get_bridge_message();
	?>


	
	
	<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Payment Receipt</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Bootstrap template for Sample Merchant Application" />
<meta name="author" content="" />
<!-- css -->
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/style.css" rel="stylesheet" />
	
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

</head>
<body>



<div id="wrapper">
	<!-- start header -->
	
	<!-- end header -->


	<section id="inner-headline">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<ul class="breadcrumb">
					
					<li class="active">Payment Receipt</li>
				</ul>
			</div>
		</div>
	</div>
	</section>
	<section id="content">
	<div class="container">
		<div class="row">

			<div class="col-lg-6">
				<div class="pricing-box-alt">
					
					<div class="pricing-terms">
						<h6> INR 150.00 </h6>
					</div>
					<div class="pricing-content">
					
	<?php 
	
		$params = explode('|',  $bridge_message);
		
		echo '<pre>';
		print_r($params);
		
		
		
	?>
						<table class="table table-striped table-hover table-bordered">
							
							<tr><th class="icon-ok">csc_txn</th><td>=591656417022815474924924</td><tr>
							<tr><th class="icon-ok">merchant_id</th><td>=56486</td><tr>
							<tr><th class="icon-ok">csc_id</th><td>=500100100013</td><tr>
							<tr><th class="icon-ok">merchant_txn</th><td>=MW2671</td><tr>
							<tr><th class="icon-ok">txn_status</th><td>=100</td><tr>
							<tr><th class="icon-ok">merchant_txn_date_time</th><td>=2017-02-28 15:47:45</td><tr>
							<tr><th class="icon-ok">product_id</th><td>=5648623762</td><tr>
							<tr><th class="icon-ok">txn_amount</th><td>=50.00</td><tr>
							<tr><th class="icon-ok">amount_parameter</th><td>=NA</td><tr>
							<tr><th class="icon-ok">txn_mode</th><td>=D</td><tr>
							<tr><th class="icon-ok"> txn_type</th><td>=D</td><tr>
							<tr><th class="icon-ok"> merchant_receipt_no</th><td>=Recpt#843</td><tr>
							<tr><th class="icon-ok"> csc_share_amount</th><td>=0.00</td><tr>
							<tr><th class="icon-ok"> pay_to_email</th><td>=a@abc.com</td><tr>
							<tr><th class="icon-ok"> currency</th><td>=INR</td><tr>
							<tr><th class="icon-ok"> discount</th><td>=0.00</td><tr>
							<tr><th class="icon-ok"> param_1</th><td>=NA</td><tr>
							<tr><th class="icon-ok"> param_2</th><td>=NA</td><tr>
							<tr><th class="icon-ok"> param_3</th><td>=NA</td><tr>
							<tr><th class="icon-ok"> param_4</th><td>=NA</td><tr>
							<tr><th class="icon-ok"> txn_status_message</th><td>=Success</td><tr>
							<tr><th class="icon-ok"> status_message</th><td>=Successful</td><tr>
							
							
						</table>
						
						
					</div>
				 <?php 
                    if($_SESSION){ ?>
					<div class="pricing-action">
						<a href="../add_patient.php" class="btn btn-primary btn-theme"><i class="icon-bolt"></i>Go Back</a>
					</div>
				<?php } ?>
				</div>
			</div>
			
		</div>
	</div>
	</section>

	
</div>

<script src="js/jquery.min.js"></script>

<script src="js/bootstrap.min.js"></script>

	
</body>
</html>
