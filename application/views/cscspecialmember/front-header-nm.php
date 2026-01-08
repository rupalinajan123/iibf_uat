<!DOCTYPE html>
<html>
<head>
<?php $this->load->view('google_analytics_script_common'); ?>
<script>var site_url="<?php echo base_url();?>";</script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>IIBF</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/admin/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/admin/dist/css/AdminLTE.min.css">

  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/admin/dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/subject-pre.css">
<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<!--<script src="<?php echo base_url()?>js/jquery.js"></script>-->
<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
 <link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/iCheck/all.css">
  <script src="<?php echo base_url()?>js/cscvalidation.js"></script>
  <link href="<?php echo base_url()?>assets/css/parsley.css" rel="stylesheet">
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style>
  ul.navlogo {
    width: 100%;
}
.navbar-custom-menu {
  padding: 5px 0;
  width: 95%;
}
ul.navlogo li:last-child {
  display: inline-block;
  float: right;
}
.skin-blue .main-header .navbar .nav > li > a {
  color: #2ea0e2;
}
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <!-- Logo -->
    <a href="<?php echo 'javascript:void(0);';//base_url()."CSCSpecialMember/"; ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>IIBF</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>IIBF</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only"><?php echo $this->session->userdata('cscnmfirstname');?></span> 
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <?php 
	  if($this->session->userdata('cscnmregid')!=''){?>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav navlogo">
        <li><img src="<?php echo base_url();?>assets/images/iibf_logo_black.png"></li>
          <li class="dropdown user user-menu">
            <a href="<?php echo  base_url()?>CSCSpecialMember/logout" class="dropdown-toggle" >
              <span class="hidden-xs">Logout</span>
              <i class="fa fa-sign-out"></i>
            </a>
          </li>
        </ul>
      </div>
      <?php }?>
    </nav>
  </header>
    <div class="loading" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/loading.gif"></div>