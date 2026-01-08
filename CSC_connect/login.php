<?php
		$start7 = microtime(true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Login :: Merchant .php</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Bootstrap template for Sample Merchant Application" />
<meta name="author" content="Abhishek Ranjan" />
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
	<?php include("includes/header.php"); ?>
	<!-- end header -->
	
	
	<section id="inner-headline">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<ul class="breadcrumb">
					<li><a href="index.php">Home</a><i class="icon-angle-right"></i></li>
					<?php
					if(empty($_SESSION)){ ?>
					?>
					<li class="active">Login</li>
					<?php } ?>

				</ul>
			</div>
		</div>
	</div>
	</section>
	<section id="content">
<div class="container">

<div class="row">

	<div class="col-lg-6">
	<!-- call response -->
	
	<h4>Digital Seva Connect Response</h4>
				<pre class="prettyprint linenums">
					<?php
						print_r($_SESSION);
					?>
				</pre>
				
				
	
	
	
	</div>

	<div class="col-lg-6">
		<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
		<?php if(empty($_SESSION)){ ?>
			<a href="User.php" class="btn btn-info">Login with Digital Seva Connect</a>
		<?php }else{
				echo "<h4>Welcome " . $_SESSION['username'] ."</h4>";
		} ?>	
			<br><br>
			<!-- <form role="form" class="register-form">
				<h3>Sign in </h3>
				<hr class="colorgraph">

				<div class="form-group">
					<input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email Address" tabindex="4">
				</div>
				<div class="form-group">
					<input type="password" class="form-control input-lg" id="exampleInputPassword1" placeholder="Password">
				</div>

				
				<div class="row">
					<div class="col-xs-12 col-md-6"><input type="submit" value="Sign in" class="btn btn-success btn-block btn-lg" tabindex="7"></div>
					
				</div>
			
			</form> -->
		</div>
	</div>
</div>

</div>
	</section>

	<?php include("includes/footer.php"); ?>
</div>


<script src="js/jquery.min.js"></script>
<script src="js/modernizr.custom.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/bootstrap.min.js"></script>

	
</body>
</html>
<?php
		$start8 = microtime(true);
		$log_srt = 
			"7# " . $start7 . "\n" . 
			"8# " . $start8 . "\n";
//		file_put_contents(__DIR__ . '/log/abc.log', $log_srt, FILE_APPEND);
