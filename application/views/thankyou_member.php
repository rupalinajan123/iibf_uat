<!DOCTYPE html>
<html>

<head>
  <?php $this->load->view('google_analytics_script_common'); ?>
  <script>
    var site_url = "<?php echo base_url(); ?>";
  </script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>IIBF - Forget membership Number</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo  base_url() ?>assets/admin/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo  base_url() ?>assets/admin/dist/css/AdminLTE.min.css">

  <script src="<?php echo base_url() ?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
  <style>
    .login-box,
    .register-box {
      border: 1px solid #1287c0;
      width: 90%;
      max-width: 700px;
    }

    .login-logo,
    .register-logo {
      padding: 15px 15px 15px 15px;
      border-bottom: 1px solid #1287c0;
      margin: 0;
    }

    .login-logo .login_text {
      display: inline-block;
      vertical-align: middle;
      margin: 0px 0 0 0;
    }

    .short_logo {
      float: none;
      margin: 0 5px 5px 0;
      display: inline-block;
    }

    .short_logo img {
      border-bottom: 1px solid #03a8fa;
    }

    .login-box-body,
    .register-box-body {
      background: rgba(255, 255, 255, 1);
      padding: 25px 0 25px;
      border-top: 0;
      color: #000;
      width: 90%;
      position: unset;
      left: auto;
      margin: 0 auto;
      max-width: 500px;
    }
     .btn.btn-flat {
      min-height: 34px;
      /* background-color:#015171; */
    }

    .login-box-body a {
      line-height: 20px;
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

    label {
      line-height: 18px;
      font-weight: normal;
    }

    label.error, label.error p {
      font-size: 13px;
      margin: 1px 0 0 0;
      line-height: 16px;
      display: block;
    }

    form {
      padding: 0 0 0 0;
      border: 1px solid #1287c0;
      background-color: #dcf1fc;
    }

    a.links {
      color: #fff;
      background: #015171;
      font-size: 12px;
      padding: 2px 8px 3px 8px;
      border-radius: 0px;
      /* opacity: 0.8; */
      transition: all 0.5s ease-out;
      /* color: #015171; */
    }

    

    .btn.btn-flat {
      min-height: 34px;
      /* background-color:#015171; */
    }

    .red {
      color: #f00;
    }

    .form-horizontal .control-label 
    {
      padding: 0 15px;
      margin: 0 0 3px 0;
      line-height: 18px;
      text-align: left;
    }

 

    /* .captcha_img_outer #new_captcha {
      min-width: 85px;
      display: inline-block;
      vertical-align: top;
      line-height: 15px;
    } */

   

    .btn_outer {
      text-align: center;
    }

    .btn_outer .btn {
      /* min-width: 120px;
      margin: 2px 5px 5px 5px; */
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

    .loading {
      background-color: rgba(0, 0, 0, 0.1);
      height: 100%;
      min-height: 100%;
      position: fixed;
      text-align: center;
      top: 0;
      width: 100%;
      z-index: 9999;
      left: 0;
    }

    .loading>img {
      top: 35%;
      width: 130px;
      position: relative;
    }

    .form-horizontal .form-group
    {
      margin-bottom:10px;
    }

    .or_div 
    {
      font-size: 13px;
      font-weight: bold;
      margin: 8px 0 8px 0;
      line-height: 15px;
    }

    @media only screen and (max-width:399px)
    {
      .links_outer a:first-child { float: none; display: block; margin: 0 auto 5px auto; max-width: 175px; }
      .links_outer a { float:none; }

      .captcha_outer .form-control { width: 100%; }
      .captcha_outer .captcha_img_outer { width: 100%; text-align: left; margin: 6px 0 0 0; }
    }
  </style>
</head>

<body class="hold-transition login-page">
  <div class="loading"><img src="<?php echo base_url(); ?>assets/images/loading.gif"></div>
  <div class="login-box">
    <div class="login-logo">
      <div class="short_logo"><img src="<?php echo base_url(); ?>assets/images/iibf_logo_short.png"></div>
      <div class="login_text"><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br><small>(An ISO 21001:2018 Certified)</small></a></div>
    </div>    
    
    <div class="login-box-body">
      

      <form class="form-horizontal" action="<?php echo base_url();?>login/" method="post" name="" id="" >
        <div style="background-color: #7fd1ea;color: #fff;padding: 6px 10px;margin-bottom: 10px;font-size: 20px;text-align: center;font-weight: 600;text-transform: uppercase;border-bottom:1px solid #1287c0;">FORGOT MEMBERSHIP NUMBER DETAILS</div>

        <div style="padding: 10px 15px 20px 15px;max-width: 500px;margin: 0 auto;">
        <div class="form-group">
        <div class="col-sm-12">
        <h4 class="text-center">Thank you</h4>
        <h5 class="text-center">Membership details have been successfully sent to your email ID.</h5>
        <button type="submit" class="btn btn-primary btn-flat btn-block" name="login" id="login">Click here to Log In</button>
        </div>
         
          </div>
      </form>
     
    </div>
  </div>

  <script src="<?php echo base_url() ?>assets/admin/bootstrap/js/bootstrap.min.js"></script>


  <script type="text/javascript">
   
    $(document).ready(function() { $('.loading').delay(0).fadeOut('slow'); });
  </script>
</body>
</html>