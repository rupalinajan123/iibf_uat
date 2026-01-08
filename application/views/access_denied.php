<!DOCTYPE html>

<html>

<head>
<?php $this->load->view('google_analytics_script_common'); ?>

  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>IIBF - Access Denied</title>

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

  <div class="login-logo">

    <a><b>IIBF</b>- Access Denied</a>

  </div>

  

  <?php //echo $this->session->userdata('adminlogincaptcha');?>

  

  <!-- /.login-logo -->
   <div class="col-md-12">
   Pl note that if you have  already registered for any examination under Non-member Category in the past,  the same Registration Number allotted to you can be used for registering for other examinations(other than DB&F Exam) applicable for Non-members as per the eligibility criteria given.  Already Registered candidates has to apply for examinations by login using their USER ID and PASSWORD already provided -  <a href=<?php echo base_url();?>nonmem><span style="color:#090">Click here for Login</span></a><br/>
        <span style="color:#F00">Enter your details carefully, correction may not be possible later.</span>
        </div>
  <div class="login-box-body">

    <p class="login-box-msg">Access Denied</p>
  <div class="callout callout-danger">You are not eligible for this exam. / Registration for this exam is closed.</div>
   <?php //echo form_open()?>
       <div class="row">
        <div class="col-xs-3">
        <a href="<?php echo base_url();?>">Back</a>
        </div>
      </div>
  </div>
</div>
<link href="<?php echo base_url()?>assets/css/parsley.css" rel="stylesheet">
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script src="<?php echo  base_url()?>assets/admin/bootstrap/js/bootstrap.min.js"></script>
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

