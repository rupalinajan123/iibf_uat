<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Login</title>
		
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"><!-- Tell the browser to be responsive to screen width -->		
		<link rel="stylesheet" href="<?php echo  base_url('assets/admin/bootstrap/css/bootstrap.min.css'); ?>"><!-- Bootstrap 3.3.6 -->		
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css"><!-- Font Awesome --><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css"><!-- Ionicons -->		
		<link rel="stylesheet" href="<?php echo  base_url('assets/admin/dist/css/AdminLTE.min.css'); ?>"><!-- Theme style -->		
		<script src="<?php echo base_url('assets/admin/plugins/jQuery/jQuery-2.2.0.min.js'); ?>"></script>
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		
		<style>
			.login-box-body a { line-height:20px; }
			.short_logo { display:inline-block; float:left; margin:0 0 0 20px; }
			.login-logo a{color:#619fda; font-weight:600; text-align:center; font-size:28px; line-height:24px; display:inline-block;}
			.login-logo a small {font-size:14px; color:#1d1d1d;}
			form { padding:20px 30px; border:1px solid #1287c0; background-color:#dcf1fc; }
			.form-group { margin-bottom:10px; }
			a.forget { color:#9d0000; line-height:24px;}
			a.forget:hover { color:#9d0000; text-decoration:underline;}
			.btn.btn-flat { min-height:34px; background-color:#015171; }
			.red {color:#f00;}
			
			label { margin-bottom: 5px; line-height: 16px; font-weight: normal; display: block; }
			label.error { color: red; font-weight: 500; margin: 0px 0 0 0; line-height: 16px; font-size: 12px; }	
			.form-control.error { color: inherit; }
			
			/****** Loader *******/
			#page_loader { background: rgba(0, 0, 0, 0.35) none repeat scroll 0 0; height: 100%; left: 0; position: fixed; top: 0; width: 100%; z-index: 99999; }
			#page_loader .loading { margin: 0 auto; position: relative;border: 16px solid #f3f3f3;border-radius: 50%;border-top: 16px solid #357ca5;border-bottom: 16px solid #357ca5;width: 80px;height: 80px;-webkit-animation: spin 2s linear infinite;animation: spin 2s linear infinite;top: calc( 50% - 40px);}
			@-webkit-keyframes spin {
				0% { -webkit-transform: rotate(0deg); }
				100% { -webkit-transform: rotate(360deg); }
			}
			@keyframes spin {
				0% { transform: rotate(0deg); }
				100% { transform: rotate(360deg); }
			}
		</style>
	</head>
	
	<body class="hold-transition login-page">
		<div id="page_loader"><div class="loading"></div></div>	
		<div class="login-box">
			<div class="login-logo">
				<div class="short_logo"><img src="<?php echo base_url();?>assets/images/iibf_logo_short.png"></div>
				<div><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br><small>(An ISO 9001:2008 Certified)</small></a></div>
			</div>
			
			<div class="login-box-body">
				<form action="" method="post" name="loginFrm" id="loginFrm">
					
					<?php if($error != "") {	?>
						<div class="alert alert-danger alert-dismissible in show" role="alert" id="success_alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
							<?php echo $error; ?>
						</div>
				<?php	}	?>
				
					<div class="form-group">
						<label for="Username" class="">Username</label>
						<input type="text" class="form-control" placeholder="User name" name="Username" value='<?php echo set_value('Username'); ?>' autocomplete="off" required>
						<?php if(form_error('Username')!=""){ ?><label class="error"><?php echo form_error('Username'); ?></label><?php } ?>
					</div>
					
					<div class="form-group">
						<label for="Password" class="">Password</label>
						<input type="password" class="form-control" name="Password" value='<?php echo set_value('Password'); ?>'  placeholder="Password" autocomplete="off" required>
						<?php if(form_error('Password')!=""){ ?><label class="error"><?php echo form_error('Password'); ?></label><?php } ?>
					</div>
					
					<div class="row">
						<div class="col-xs-4">
							<button type="submit" class="btn btn-primary btn-block btn-flat" name="submit">Sign In</button>
						</div>						
					</div>					
				</form>
			</div>
		</div>
		
		<script src="<?php echo base_url()?>assets/admin/bootstrap/js/bootstrap.min.js"></script><!-- Bootstrap 3.3.6 -->
		<script src="<?php echo base_url('assets/js/jquery.validate.js'); ?>"></script><!----- FOR JQUERY VALIDATION ----->
		
		<script>
				$(document ).ready( function() 
				{
					$.validator.addMethod("nowhitespace", function(value, element) { if($.trim(value).length == 0) { return false; } else { return true; } });
					
					$("#loginFrm").validate( 
					{
						rules:
						{
							Username: { required : true, nowhitespace : true },
							Password: { required : true, nowhitespace : true }
						},
						messages:
						{
							Username: { required : "Please enter the Username", nowhitespace : "Please enter the Username" },
							Password: { required : "Please enter the Password", nowhitespace : "Please enter the Password" }
						}
					});
				});
			</script>
			
			<script>$( document ).ready( function () { $('#page_loader').delay(0).fadeOut('slow'); });</script>
	</body>
</html>