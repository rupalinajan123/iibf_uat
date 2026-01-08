<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>DRA - Inspector Login</title>
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
.form-control {width:50%;}
a.forget { color:#9d0000; line-height:24px;}
a.forget:hover { color:#9d0000; text-decoration:underline;}
.btn.btn-flat {
	min-height:34px;
	background-color:#015171;
}
.red {color:#f00;}
#captcha_img ul li.parsley-required { text-align:center; }
  </style>
    </head>
    
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
               <div class="short_logo"><img src="<?php echo base_url();?>assets/images/iibf_logo_short.png"></div>
                <div><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br><small>(An ISO 21001:2018 Certified)</small></a></div>
                <div><a>DRA - Inspector Login</a></div>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
				<?php $error_msg = "";
				if(validation_errors()) { $error_msg = validation_errors(); }
				if($this->session->flashdata('error_message')) { $error_msg = $this->session->flashdata('error_message'); }
				if($error!='') { $error_msg = $error; } 
				
				if($error_msg != "") 
				{	?>
					<div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $error_msg; ?>
          </div>
        <?php }?>    
				
                <form action="" method="post" autocomplete="off" name="drainsploginFrm" id="drainsploginFrm">
                    <div class="form-group has-feedback">
						<label for="Username" class="col-sm-4 control-label labelleft">Username</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" placeholder="Username" name="username" value='<?php echo set_value('username'); ?>' autocomplete="off" required style="width:100%;">
						</div>
                        <div class="clearfix"></div>
                    </div>
									
                    <div class="form-group has-feedback">
						<label for="Password" class="col-sm-4 control-label labelleft">Password</label>
						<div class="col-sm-8">
							<input type="password" class="form-control" name="password" value='<?php echo set_value('password'); ?>'  placeholder="Password" autocomplete="off" required style="width:100%;">
						</div>
                        <div class="clearfix"></div>
					</div>
									
					<p class="text-center blue">Please verify your humanity by solving the puzzle.</p>
					<div class="form-group has-feedback clearfix">
						<label style="width: 100%;text-align: center;">
						<div id="captcha_img">
						  <input type="text" name="val1" value="<?php echo (rand(1,10))?>" style="width: 30px"> + 
							<input type="text" name="val2" value="<?php echo (rand(1,10))?>"style="width: 30px"> =
							<input type="text" name="val3" autocomplete="off" required style="width: 30px">
                        </div>
					   </label>
					</div>
										
					<?php /* 
                    <p class="text-center blue">Please enter the text you see in the image below into the text box provided.</p>
                    <div class="row">
                    	<label class="col-md-6" id="captcha_img"><?php echo $image; ?></label>  
                    	<div class="form-group has-feedback col-md-6">
                    		<input type="text" class="form-control" name="code" id="draexamcaptcha"  placeholder="Enter Captcha code" autocomplete="off"  style="padding-right:10px !important; width:100%;" required>
                    	</div>
                          <!-- //by swati -->
                         <div class="col-sm-4">
                                <a href="javascript:void(0);"  id="new_captcha" >Change Image</a>
                                <span class="error"><?php //echo form_error('code');?></span>
                        </div>
                    </div>*/ ?>
										
                    <div class="row">
                    	<!-- /.col -->
                    	<div class="col-xs-4">
                    		<button type="submit" class="btn btn-primary btn-block btn-flat" name="submit" value="Sign In">Sign In</button>
                    	</div>
                        
                        <?php 
							$obj = new OS_BR();
							if($obj->showInfo('browser')=='Internet Explorer')
							{?>
								<br>
								<div class="col-xs-12 message" style="color:#F00">
									Portal best viewed in recommended browsers (Mozilla Firefox or Google chrome latest versions) for error free performance. It is suggested Internet explorer not to be used to avoid issues.</div>
						   <?php }?>
                        
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
            $(document).ready(function() {
                //change captcha
                $('#new_captcha').click(function(event){
                    event.preventDefault();
                  
                    $.ajax({
                        type: 'POST',
                        url: 'https://iibf.esdsconnect.com/iibfdra/Version_2/InspectorLogin/generatecaptchaajax',
                        success: function(res)
                        {  
                       // alert(res); 
                            if(res!='')
                            {$('#captcha_img').html(res);
                            }
                        }
                    });
                });
            });
        
        	$('#drainsploginFrm').parsley('validate');
        </script>
    </body>
</html>