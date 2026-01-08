<!DOCTYPE html>

<html>
<head>
<?php $this->load->view('google_analytics_script_common'); ?>

  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>IIBF</title>

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

</head>

<body class="hold-transition login-page">

<div class="login-box">

  <div class="login-logo" style="margin-bottom: 1px;">

    <a href="javascript:void(0);"><b>IIBF</b>
   </a>
 
  </div>
<center>(An ISO 21001:2018 Certified)</center>
  

  <?php //echo $this->session->userdata('adminlogincaptcha');?>

  

  <!-- /.login-logo -->

  <div class="login-box-body">

    <p class="login-box-msg">Password has been sent to your registered email ID and mobile number.</p>
      
       <!--<div class="row">

        <div class="col-sm-4 col-xs-offset-3">
		<a href="<?php echo base_url();?>login/" class="btn btn-primary btn-block btn-flat">Login</a>
        <a href="<?php echo base_url();?>" class="btn btn-primary btn-block btn-flat">Home</a>
      </div>
      
     
      
     
        
        </div>-->
        
        <div class="box-footer">
                  <div class="col-sm-7 col-xs-offset-3">
                  <a href="<?php echo base_url();?>Nonmem/" class="btn btn-info">Login</a>
			      <a href="<?php echo base_url();?>" class="btn btn-info">Home</a>
                    </div>
              </div>

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
