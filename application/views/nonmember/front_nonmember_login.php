<!DOCTYPE html>

<html>

<head>
		<?php $this->load->view('google_analytics_script_common'); ?>
<script>var site_url="<?php echo base_url();?>";</script>
  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>IIBF - Non member Login</title>

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
	ul {
	  margin-top: -1px;
	  padding: 5px 10px 5px 30px;
	  border: 1px solid #1287c0;
	  background-color: #dcf1fc;
	}
	ul li {
		padding: 2px 0;
	}
	.login-box-body a {
		line-height: 20px;
	}
	.short_logo {
		display: inline-block;
		float: left;
		margin: 0 0 0 20px;
	}
	.login-logo a {
		color: #619fda;
		font-weight: 600;
		text-align: center;
		font-size: 28px;
		line-height: 24px;
		display: inline-block;
	}
	.login-logo a small {
		font-size: 14px;
		color: #1d1d1d;
	}
	.form-control {
		width: 50%;
	}
	label {
		line-height: 18px;
		font-weight: normal;
	}
	form {
		padding: 10px;
		border: 1px solid #1287c0;
		background-color: #dcf1fc;
	}
	.form-group {
		margin-bottom: 10px;
	}
	a.forget {
		color: #9d0000;
		line-height: 24px;
	}
	a.forget:hover {
		color: #9d0000;
		text-decoration: underline;
	}
	.btn.btn-flat {
		min-height: 34px;
		background-color: #015171;
	}
	.red {
		color: #f00;
	}

	.links_outer {
      margin: 0 0 8px 0;
    }

    .links_outer a 
    {
      /* margin: 0 2px 2px 2px; */
      /* min-width: 190px; */
      /* display: inline-block; */
      font-weight: 600;
      line-height: 14px;
      font-size: 14px;
    }

    .links_outer a { float:right; }
    .links_outer a:first-child { float:left; }

    .links_outer a:focus, .links_outer a:hover 
    {
      color: #23527c !important;
      text-decoration: underline !important;
    }


  </style>

</head>

<body class="hold-transition login-page">

<div class="login-box">

  <div class="login-logo">
    <div class="short_logo"><img src="<?php echo base_url();?>assets/images/iibf_logo_short.png"></div>
    <div><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br><small>(An ISO 21001:2018 Certified)</small></a></div>

  </div>

  

  <?php //echo $this->session->userdata('adminlogincaptcha');?>

  

  <!-- /.login-logo -->

  <div class="login-box-body">
    <?php if(validation_errors()){?>

    <div class="callout callout-danger"><?php echo validation_errors();?></div>

    <?php }?>    
    
     <?php if($error){?>

    <div class="callout callout-danger" style="color:#FFF !important"><?php echo $error;?></div>

    <?php }?>    
    
     <?php if($this->session->flashdata('error_message')){?>

    <div class="callout callout-danger"><?php echo $this->session->flashdata('error_message')?></div>

    <?php }?>   

   <?php //echo form_open()?>

   <form action="" method="post" name="loginFrm" id="loginFrm">
<div style="background-color:#7fd1ea; color:#fff; padding:3px 10px; margin-bottom:5px; font-size:16px;">Login</div>
      <div class="form-group has-feedback clearfix">
  <label for="text" class="col-md-6">Registration No. <span class="red">*</span> :</label>
        <input type="text" class="form-control" placeholder="Register No" name="Username" value='<?php echo set_value('Username'); ?>' autocomplete="off" required>

        <!--<span class="glyphicon glyphicon-envelope form-control-feedback"></span>-->

      </div>

      <div class="form-group has-feedback clearfix">
        <label for="password" class="col-md-6">Password <span class="red">*</span> :</label>

        <input type="password" class="form-control" name="Password" value='<?php echo set_value('Password'); ?>'  placeholder="Password" autocomplete="off" required>
	   </div>
      

      	<?php /* echo get_ip_address(); */
            /* if(get_ip_address() == '115.124.115.69' || get_ip_address() == '115.124.115.75') 
            { ?>
              <input type="hidden" class="form-control" name="code" autocomplete="off" required value="<?php echo $_SESSION['nonmemlogincaptcha']; ?>" />
            <?php } 
            else */
            { ?>
      	<div class="form-group has-feedback clearfix">
					<label for="text" class="col-md-6">Type the exact characters you see in the picture <span class="red">*</span>.</label>
	        <input type="text" class="form-control" name="code" autocomplete="off"  style="padding-right:10px !important;" required>
					<label> <div id="captcha_img"><?php echo $image;?></div></label>
	 				<label for="text" class="col-md-7"></label>
	        <div class="form-group has-feedback clearfix">
	            <a href="javascript:void(0);" id="new_captcha" class="forget" >Change Image</a>
	        </div>
        </div>
      <?php } ?>
      
       	<div class="row">
       		<div class="col-xs-12">
	       		<div class="links_outer">
	            <a href="<?php echo site_url('nonmem/forgot_membership_no'); ?>" class="linksx">Forgot Membership Number?</a>
	            <div class="clearfix"></div>
	          </div>
	        </div>  
	        <div class="col-xs-4">
			  		<input id="Submit" class="btn btn-primary btn-block btn-flat" name="btnLogin" value="Submit" type="submit">
	      	</div>
	      
	      	<div class="col-xs-4">
		      	<input class="btn btn-primary btn-block btn-flat" onclick="document.forgetFrm.reset();" name="Reset" value="Reset" type="reset">
	      	</div>
	      
	      	<div class="col-xs-4">
						<a href="<?php echo base_url();?>" class="btn btn-primary btn-block btn-flat">Back</a>
	      	</div>
	      	
	      	<div class="col-xs-12">	
	      		<center><div class="or_div">OR</div></center>
	        	<button type="button" class="btn btn-primary btn-flat btn-block" name="LoginOtp" id="LoginOtp" onclick="redirectToOTP()">Log In with OTP</button>
	      	</div>          

	        <span style="color:#F00;"></span> 
	        <span style="color:#F00;"><?php //echo @$error." ".validation_errors(); ?></span> 
	      </div>
        <!-- /.col -->
  		</form>
  
      <ul>
		<!--<li><a class="disability" href="ValidateDetails.php?ex=MQ==&amp;me=Tk0=&amp;ei=NTE=">Forgot Password/Get password Click Here</a></li>-->
        <li><a class="disability forget" href="<?php echo base_url()?>Nonmem/forgotpassword/">Forgot Password/Get password Click Here</a></li>
        
        <?php 
		$obj = new OS_BR();
		if($obj->showInfo('browser')=='Internet Explorer')
		{?>
			
            <div class="message" style="color:#F00">
              Portal best viewed in recommended browsers (Mozilla Firefox or Google chrome latest versions) for error free performance. It is suggested Internet explorer not to be used to avoid issues.</div>
      <?php
        }?>		
		</ul>		
        
        
        
        
        		
	   </div>

  

    <!-- /.social-auth-links -->

    <!--<a href="#">I forgot my password</a><br>-->

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



<script>
	// function redirectToOTP()
  // {
  //   window.location.href = "<?php echo site_url('login/login_with_otp'); ?>";
  // }

  function redirectToOTP()
   {
    window.location.href = "<?php echo site_url('nonmem/login_with_otp_nonmem'); ?>";
     }
  $(function () {

    $('input').iCheck({

      checkboxClass: 'icheckbox_square-blue',

      radioClass: 'iradio_square-blue',

      increaseArea: '20%' // optional

    });
	
	
	$('#new_captcha').click(function(event){
        event.preventDefault();
    $.ajax({
 		type: 'POST',
 		url: site_url+'Nonmem/generatecaptchaajax/',
 		success: function(res)
 		{	
 			if(res!='')
 			{$('#captcha_img').html(res);
 			}
 		}
    });
	});

	

  });

  

</script>

<script type="text/javascript">

  $('#loginFrm').parsley('validate');

</script>

</body>

</html>
