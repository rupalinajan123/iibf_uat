<!DOCTYPE html>
<html>

<head>
  <?php $this->load->view('google_analytics_script_common'); ?>
  <script>
    var site_url = "<?php echo base_url(); ?>";
  </script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>IIBF - Membership Details</title>
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
      opacity: 0.8;
      transition: all 0.5s ease-out;
      /* color: #015171; */
    }

    a.links:hover {
      text-decoration: underline;
      opacity: 1;
    }

    .btn.btn-flat {
      min-height: 34px;
      /* background-color:#015171; */
    }

    .red {
      color: #f00;
    }

    .form-horizontal .control-label {
      padding: 0 15px;
      margin: 0;
      line-height: 18px;
    }

    .captcha_img_outer {
      margin: 10px 0 0 0;
    }

    .captcha_img_outer #captcha_img {
      display: inline-block;
      margin: 0px 5px 3px 0;
      vertical-align: top;
    }

    .captcha_img_outer #new_captcha {
      min-width: 85px;
      display: inline-block;
      vertical-align: top;
      line-height: 15px;
    }

    .btn_outer {
      text-align: center;
    }

    .btn_outer .btn {
      min-width: 120px;
      margin: 2px 5px 5px 5px;
    }

    .links_outer {
      margin: 8px 0 0 0;
    }

    .links_outer a {
      margin: 0 2px 2px 2px;
      min-width: 190px;
      display: inline-block;
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
    .table td {
      border: 1px solid #1287c0 !important;
      font-weight: 600;
      background: rgba(255,255,255,0.5);
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
      <?php 
        $show_msg = ''; $alert_cls = 'warning'; 
        if ($error) { $show_msg = $error; $alert_cls = 'danger'; }
        if ($this->session->flashdata('error_message')) { $show_msg = $this->session->flashdata('error_message'); $alert_cls = 'danger'; } 

        if($show_msg != "")
        { ?>      
          <div class="alert alert-<?php echo $alert_cls; ?> alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $show_msg; ?>
          </div>
        <?php } 
      ?>

      <form class="form-horizontal" autocomplete="off">
        <div style="background-color: #7fd1ea;color: #fff;padding: 6px 10px;margin-bottom: 10px;font-size: 20px;text-align: center;font-weight: 600;text-transform: uppercase;border-bottom:1px solid #1287c0;">Membership Details</div>

        <div style="padding:10px 20px 15px 20px">
          <div class="table-responsive">
            <table class="table table-hover table-bordered">
              <tbody>
                <tr><td>Membership No.</td><td><?php echo $user_data[0]['regnumber']; ?></td></tr>
                <tr><td>Name</td><td><?php echo $user_data[0]['namesub'].' '.$user_data[0]['firstname']; if($user_data[0]['middlename'] != "") { echo " ".$user_data[0]['middlename'] ; } if($user_data[0]['lastname'] != "") { echo " ".$user_data[0]['lastname'] ; } ?></td></tr>
                <tr><td>Email Id</td><td><?php echo $user_data[0]['email']; ?></td></tr>
                <tr><td>Mobile Number</td><td><?php echo $user_data[0]['mobile']; ?></td></tr>
                <tr><td>Birth Date</td><td><?php echo date("d M, Y", strtotime($user_data[0]['dateofbirth'])); ?></td></tr>
                <tr><td>Gender</td><td><?php echo $user_data[0]['gender']; ?></td></tr>
              </tbody>
            </table>
          </div>
          
          <div class="btn_outer">
            <div class="row">
              <div class="col-xs-12">
                <a href="<?php echo site_url('login'); ?>" class="btn btn-success btn-flat">Back to Login</a>
              </div>
            </div>
          </div>

          <?php
          $obj = new OS_BR();
          if ($obj->showInfo('browser') == 'Internet Explorer')
          { ?>
            <div class="row" style="margin-top: 12px; padding-top: 8px;	border-top: 1px solid #F00;">
              <div class="col-xs-12 message text-center" style="color:#F00;">
                Portal best viewed in recommended browsers (Mozilla Firefox or Google chrome latest versions) for error free performance. It is suggested Internet explorer not to be used to avoid issues.</div>
            </div>
          <?php
          } ?>
      </form>
      <?php  ## Commented on 7 Jan 2022 //$app_server=explode('.',gethostname());if(isset($app_server[0])){echo $app_server[0];} ?>
    </div>
  </div>

  <script src="<?php echo  base_url() ?>assets/admin/bootstrap/js/bootstrap.min.js"></script>  

  <script type="text/javascript">
    $(document).ready(function() { $('.loading').delay(0).fadeOut('slow'); });
  </script>
</body>
</html>