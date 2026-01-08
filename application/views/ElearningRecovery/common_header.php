<!DOCTYPE html>
<html>
<head>
<?php $this->load->view('google_analytics_script_common'); ?>
<script>var site_url="<?php echo base_url();?>";</script>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>IIBF - Exam Recovery</title>
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

<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<!--<script src="<?php echo base_url()?>js/jquery.js"></script>-->
<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/iCheck/all.css">
<script src="<?php echo base_url()?>js/validation.js?<?php echo time(); ?>"></script>
<link href="<?php echo base_url()?>assets/css/parsley.css" rel="stylesheet">
<link href="<?php echo base_url()?>assets/css/custom_cms.css" rel="stylesheet">
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<style>
.modal-dialog {
	position: relative;
	display: table;
	overflow-y: auto;
	overflow-x: auto;
	width: 920px;
	min-width: 300px;
}
.skin-blue .main-header .navbar {
	background-color: #fff;
}
body.layout-top-nav .main-header h1 {
	color: #0699dd;
	margin-bottom: 0;
	margin-top: 30px;
}
.ifci_logo_black {
	position: absolute;
	top: 25px;
	z-index: 1031;
	left: 135px;
}
.container {
	position: relative;
}
.box-header.with-border {
	background-color: #f1f1f1;
	border-top-left-radius: 10px;
	border-top-right-radius: 10px;
}
.header_blue {
	background-color: #2ea0e2 !important;
	color: #fff !important;
}
.box {
	border: 1px solid #00c0ef;
	box-shadow: none;
	border-radius: 10px;
}
.nobg {
	background: none !important;
	border: none !important;
}
.box-title-hd {
	color: #3c8dbc;
	font-size: 16px;
}
.blue_bg {
	background-color: #e7f3ff;
}
.m_t_15 {
	margin-top: 15px;
}
.main-footer {
	padding-left: 160px;
	padding-right: 160px;
}
.content-header > h1 {
	font-size: 22px;
	font-weight: 600;
}
h4 {
	margin-top: 0;
	margin-bottom: 20px !important;
	font-size: 16px;
	line-height: 24px;
	padding: 0 5px;
}
.form-horizontal .control-label {
	padding-top: 4px;
}
.pad_top_2 {
	padding-top: 2px !important;
}
.pad_top_0 {
	padding-top: 0px !important;
}
body.layout-top-nav .main-header h1 {
	margin: 20px 0 0 20px;
	/*display:inline-block;*/
	font-size: 30px;
}
.main-header {
	border-top: 1px solid #1287c0;
	border-left: 1px solid #1287c0;
	border-right: 1px solid #1287c0;
	width: 60%;
	margin: 1% auto 0;
	padding: 10px;
}
.short_logo {
	display: inline-block;
	float: left;
	margin: 0 0 0 20px;
}
.login-logo a {
	color: #619fda;
	font-weight: 400;
	text-align: center;
	font-size: 24px;
	line-height: 24px;
	display: inline-block;
}
.login-logo a small {
	font-size: 14px;
	color: #1d1d1d;
}
.content-wrapper {
	background-color: #fff;
}
.container {
	width: 60%;
	border-left: 1px solid #1287c0;
	border-right: 1px solid #1287c0;
	border-bottom: 1px solid #1287c0;
	margin-bottom: 10px;
}
.box {
	border: none;
}
.box-body {
	padding: 0;
}
.box-body ul li {
	background-color: #dcf1fc;
	padding: 3px 10px 3px 30px;
	margin: 3px 0;
	list-style: none;
	position: relative;
}
.box-body ul li:before {
	display: block;
	position: absolute;
	font-family: FontAwesome;
	content: "\f04e";
	top: 5px;
	left: 10px;
	color: #9d0000;
	font-size: 12px;
	opacity: 0.8;
}
.box-body ul li a {
	color: #9d0000;
}
.box-body ul li a:hover {
	color: #9d0000;
	text-decoration: underline;
}
.content-header {
	padding: 0 0 0 10px;
}
.content-header h1 {
	background-color: #7fd1ea;
	color: #fff;
	margin: 0 auto;
	padding: 5px 0;
}
.content {
	padding: 0;
}
	/*.box-body p {
		margin-bottom:5px;
		margin-left:10px;
		padding:3px 10px;
		background-color:#9bd5f3;
	}*/
</style>
</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
<header class="main-header"> 
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <div class="short_logo"> <img src="<?php echo base_url();?>assets/images/iibf_logo_short.png"> </div>
    <div class="login-logo"><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>
      <small>(An ISO 21001:2018 Certified)</small></a></div>
  </nav>
</header>
