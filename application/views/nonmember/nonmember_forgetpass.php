<!DOCTYPE html>

<html>

<head>
	<?php $this->load->view('google_analytics_script_common'); ?>
  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>IIBF - Forgot Password/Get password Click Here</title>

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
  <link rel="stylesheet" href="<?php echo  base_url()?>assets/css/responsive.css">
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
	.login-box-body {
		background-color:#dcf1fc;
		padding:10px;
		margin-bottom:10px;
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

    <div style="background-color:#7fd1ea; color:#fff; padding:3px 10px; margin-bottom:5px; font-size:18px; text-align:center;">Non-Membership Details</div>

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

   <form action="" method="post" name="forgetFrm" id="forgetFrm">

      <div class="form-group has-feedback">

        <input type="text" class="form-control" placeholder="Register No." name="non_memno" value='<?php echo set_value('non_memno'); ?>' autocomplete="off" required>

        <!--<span class="glyphicon glyphicon-envelope form-control-feedback"></span>-->

      </div>

      
       <div class="row">

        <!-- /.col -->

            <div class="col-xs-4">
		  <input id="Submit" class="btn btn-primary btn-block btn-flat"  name="btn_forget_pass" value="Submit"  type="submit"> 
      </div>
      
      <div class="col-xs-4">
	      <input class="btn btn-primary btn-block btn-flat" onclick="document.forgetFrm.reset();" name="Reset" value="Reset" type="reset">
      </div>
      
      <div class="col-xs-4">
		<a href="<?php echo base_url();?>Nonmem/" class="btn btn-primary btn-block btn-flat">Back</a>
      </div>
      
        <span style="color:#F00;"><?php //echo @$error." ".validation_errors(); ?></span> </div>

        <!-- /.col -->

      

    </form>

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
