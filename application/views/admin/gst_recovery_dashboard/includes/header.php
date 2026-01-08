<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>IIBF | GST Recovery Dashboard</title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/bootstrap/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="<?php echo  base_url()?>assets/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="<?php echo  base_url()?>assets/css/ionicons.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/dist/css/AdminLTE.min.css">
<link href="<?php echo base_url()?>assets/css/parsley.css" rel="stylesheet">
<!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/dist/css/skins/_all-skins.min.css">

<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<script>
$(document).ready(function(){
	$(".DTTT_button_print, .DTTT_button_copy, .DTTT_button_csv, .DTTT_button_xls, .DTTT_button_pdf").hide();
});

</script>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<style>
.navbar-custom-menu {
	width: 95%;
	padding: 5px 0;
}
ul.navlogo {
	width: 100%;
}
ul.navlogo li:first-child {
	float: left;
	display: inline-block;
	color: #2ea0e2;
	text-transform: uppercase;
	font-size: 24px;
	line-height: 42px;
}
ul.navlogo li:last-child {
	float: right;
	display: inline-block;
}
.skin-blue .main-header .navbar .nav > li > a {
	color: #2ea0e2;
}
</style>
<style>
.loading {
	position: fixed;
	left: 50%;
	top: 35%;
	display: none;
	/* background: transparent url("../images/loading-big.gif");*/
	z-index: 1000;
	height: 31px;
	width: 31px;
}
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<header class="main-header"> 
  <!-- Logo --> 
  <a href="<?php echo base_url();?>admin/MainController" class="logo"> 
  <!-- mini logo for sidebar mini 50x50 pixels --> 
  <span class="logo-mini"><b>IIBF</b></span> 
  <!-- logo for regular state and mobile devices --> 
  <span class="logo-lg"><b>IIBF</b></span> </a> 
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top"> 
    <!-- Sidebar toggle button--> 
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <span class="sr-only"><?php echo $this->session->userdata('name');?></span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav navlogo">
        <!-- User Account: style can be found in dropdown.less -->
        <li><img src="<?php echo base_url();?>assets/images/iibf_logo_black.png"></li>
        <li class="dropdown user user-menu"> <a href="<?php echo  base_url()?>admin/login/Logout" class="dropdown-toggle" ><!--data-toggle="dropdown"--> 
          <span class="hidden-xs">
          <?php if($this->session->userdata('username')!=''){ echo $this->session->userdata('username'); } ?>
          </span> <i class="fa fa-sign-out"></i> </a> 
          <!--<ul class="dropdown-menu">
               <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo  base_url()?>admin/login/Logout" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>--> 
        </li>
      </ul>
    </div>
  </nav>
  <div class="loading" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/loading.gif" width="120"></div>
</header>
