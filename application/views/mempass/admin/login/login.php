<!DOCTYPE html>
<html>
    <head>
				<?php $this->load->view('google_analytics_script_common'); ?>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>MemPass - Admin Login</title>
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
  .login-box-body a {
	line-height:20px;
}
.short_logo {
	display:inline-block;
	float:left;
	margin:0 0 0 20px;
}

.login-logo a{color:#619fda; font-weight:600; text-align:center; font-size:28px; line-height:24px; display:inline-block;}
.login-logo a small {font-size:14px; color:#1d1d1d;}
label {line-height:18px; font-weight:normal;}
form {
	padding:10px;
	border:1px solid #1287c0;
	background-color:#dcf1fc;
}
.form-group {
	margin-bottom:10px;
}
.form-control{width:65%}
a.forget { color:#9d0000; line-height:24px;}
a.forget:hover { color:#9d0000; text-decoration:underline;}
.btn.btn-flat {
	min-height:34px;
	background-color:#015171;
}
.red {color:#f00;}
  </style>
    </head>
    
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <div class="short_logo"><img src="<?php echo base_url();?>assets/images/iibf_logo_short.png"></div>
  <div><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br><small>(An ISO 9001:2008 Certified)</small></a></div>
            </div>
            <?php //echo $this->session->userdata('adminlogincaptcha');?>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <?php if(validation_errors() || $this->session->flashdata('error_message')){?>
                	<div class="callout callout-danger">
						<?php echo validation_errors(); echo $this->session->flashdata('error_message')?>
                    </div>
                <?php }?>    
                <form action="" method="post" name="loginFrm" id="loginFrm">
                    <div class="form-group has-feedback">
                    	<label for="roleid" class="col-sm-4 control-label labelleft">Username</label>
                        <input type="text" class="form-control" placeholder="User name" name="Username" value='<?php echo set_value('Username'); ?>' autocomplete="off" required>
                    </div>
                    <div class="form-group has-feedback">
                    <label for="roleid" class="col-sm-4 control-label labelleft">Password</label>
                    	<input type="password" class="form-control" name="Password" value='<?php echo set_value('Password'); ?>'  placeholder="Password" autocomplete="off" required>
                    </div>
                    <p class="text-center blue">Please enter the text you see in the image below into the text box provided.</p>
                    <div class="row">
                    	<label class="col-md-6"><?php echo $image; ?></label>
                    	<div class="form-group has-feedback col-md-6">
                    		<input type="text" class="form-control" name="code"  placeholder="Enter Captcha code" autocomplete="off"  style="padding-right:10px !important" required>
                    	</div>
                    </div>
                    <div class="row">
                    	<!-- /.col -->
                    	<div class="col-xs-4">
                    		<button type="submit" class="btn btn-primary btn-block btn-flat" name="submit">Sign In</button>
                    	</div>
                        
                        <?php 
							$obj = new OS_BR();
							if($obj->showInfo('browser')=='Internet Explorer')
							{?>
								<br>
								<div class="col-xs-12 message" style="color:#F00">
									Portal best viewed in recommended browsers (Mozilla Firefox or Google chrome latest versions) for error free performance. It is suggested Internet explorer not to be used to avoid issues.</div>
						<?php
						}?>
                        
                    </div>
                    <!-- /.col -->
                    
                </form>
            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->
        <link href="<?php echo base_url()?>assets/css/parsley.css" rel="stylesheet">
        <script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
        <!-- jQuery 2.2.0 -->
        <!-- Bootstrap 3.3.6 -->
        <script src="<?php echo  base_url()?>assets/admin/bootstrap/js/bootstrap.min.js"></script>
        <!-- iCheck -->
        <script src="<?php echo  base_url()?>assets/admin/plugins/iCheck/icheck.min.js"></script>
        <script type="text/javascript">
			$(function () {
				$('input').iCheck({
					checkboxClass: 'icheckbox_square-blue',
					radioClass: 'iradio_square-blue',
					increaseArea: '20%' // optional
				});
			});
        </script>
        <script type="text/javascript">
        	$('#loginFrm').parsley('validate');
        </script>
    </body>
</html>