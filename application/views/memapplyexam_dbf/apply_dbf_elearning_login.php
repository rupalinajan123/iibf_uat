<!DOCTYPE html>
<html>
  <head>
	<?php $this->load->view('google_analytics_script_common'); ?>
		<script>var site_url="<?php echo base_url();?>";</script>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>IIBF - Member Login</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="<?php echo  base_url()?>assets/admin/bootstrap/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="<?php echo  base_url()?>assets/admin/dist/css/AdminLTE.min.css">
		<!-- iCheck -->
		<link rel="stylesheet" href="<?php echo  base_url()?>assets/admin/plugins/iCheck/square/blue.css">
		<script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		
		<style>
			ul { margin-top: -1px; padding: 5px 10px 5px 30px; border: 1px solid #1287c0; background-color: #dcf1fc; margin-bottom:0; }
			ul li { padding: 2px 0; }
			.login-box-body a { line-height: 20px; }
			.short_logo { display: inline-block; float: left; margin: 0 0 0 20px; }
			.login-logo a { color: #619fda; font-weight: 600; text-align: center; font-size: 28px; line-height: 24px; display: inline-block; }
			.login-logo a small { font-size: 14px; color: #1d1d1d; }
			.form-control { width: 50%; }
			label { line-height: 18px; font-weight: normal; }
			form { padding: 10px; border: 1px solid #1287c0; background-color: #dcf1fc; }
			.form-group { margin-bottom: 10px; }
			a.forget { color: #9d0000; line-height: 24px; }
			a.forget:hover { color: #9d0000; text-decoration: underline; }
			.btn.btn-flat { min-height: 34px; background-color: #015171; }
			.red { color: #f00; }
			
			ul.parsley-errors-list { border:none; }
			#captcha_img ul li.parsley-required { text-align:center; }
			.captcha_input, .captcha_input:focus, .captcha_input:hover, .captcha_input:active { display: inline-block; width: 30px; height: 24px; background-color: #fff; text-align: center; border: 1px solid #ccc !important; vertical-align: middle; line-height: 22px; margin: 0 2px 2px 2px; box-shadow: none; }
		</style>
	</head>
  <body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<div class="short_logo"><img src="<?php echo base_url();?>assets/images/iibf_logo_short.png"></div>
				<div><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>
				<small>(An ISO 21001:2018 Certified)</small></a></div>
			</div>
			
			<div class="login-box-body">				
				<?php $error_msg = "";
					if(validation_errors()) { $error_msg = validation_errors(); }
					if($this->session->flashdata('error_message')) { $error_msg = $this->session->flashdata('error_message'); }
					if($this->session->flashdata('error')) { $error_msg = $this->session->flashdata('error'); }
					if($error!='') { $error_msg = $error; } 
					
					if($error_msg != "") 
					{	?>
					<div class="callout callout-danger"><?php echo $error_msg; ?></div>
				<?php }	?>
				
				<form action="" method="post" name="loginFrm" id="loginFrm">
					<div class="form-group has-feedback">
						<label for="Username" class="col-sm-4 control-label labelleft">Membership Number <span class="red">*</span></label>
						<div class="col-sm-8">
							<input type="text" class="form-control" placeholder="Membership Number" name="Username" value='<?php echo set_value('Username'); ?>' autocomplete="off" required style="width:100%;">
						</div><div class="clearfix"></div>
					</div>
					
					<p class="text-center blue">Please verify your humanity by solving the puzzle.</p>
					<div class="form-group has-feedback clearfix">
						<label style="width: 100%;text-align: center;">
							<div id="captcha_img">
								<?php $val1_hidden = (rand(1,10)); $val2_hidden = (rand(1,10)); ?>
								<input type="hidden" name="val1" value="<?php echo $val1_hidden; ?>">
								<input type="hidden" name="val2" value="<?php echo $val2_hidden; ?>">
								
								<?php echo "<span class='captcha_input'>".$val1_hidden."</span> + <span class='captcha_input'>".$val2_hidden."</span> = "; ?>
								<input class='captcha_input' type="text" name="val3" autocomplete="off" required >
							</div>
						</label>
					</div>
					
					<div class="row">
						<div class="col-xs-6">
							<input id="Submit" class="btn btn-primary btn-block btn-flat" name="submit" value="Submit" type="submit">
						</div>
						<div class="col-xs-6">
							<input class="btn btn-primary btn-block btn-flat" onClick="reloadpage()" name="Reset" value="Reset" type="reset">
						</div>
						<?php /* <div class="col-xs-4"> <a onClick="window.history.go(-1); return false;" class="btn btn-primary btn-block btn-flat">Back</a> </div> */ ?>
					</div>
				</form>
				
				<?php /*<ul>
					<li><a class="disability forget" href="<?php echo base_url()?>login/forgotpassword/">Forgot Password/Get password Click Here</a></li>					
				</ul> */ ?>
				
				<?php 
						$obj = new OS_BR();
						if($obj->showInfo('browser')=='Internet Explorer')
						{?>
						<div  style="color: #F00;background: #dcf1fc;padding: 10px 10px;border-left: 1px solid #1287c0;border-right: 1px solid #1287c0;border-bottom: 1px solid #1287c0;">Note : Portal best viewed in recommended browsers (Mozilla Firefox latest versions) for error free performance. It is suggested Internet explorer not to be used to avoid issues.</div>
						<?php
						}	?>
			</div>
		</div>
	</div>
	
	<link href="<?php echo base_url()?>assets/css/parsley.css" rel="stylesheet">
	<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script> 
	<script src="<?php echo  base_url()?>assets/admin/bootstrap/js/bootstrap.min.js"></script> 
	
	<script type="text/javascript">
		$('#loginFrm').parsley('validate');
		
		function reloadpage() 
		{
			var url   = window.location.href;  
			window.location.href = url;
		}
	</script>
</body>
</html>
