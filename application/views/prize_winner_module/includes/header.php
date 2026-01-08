<?php $this->load->view('google_analytics_script_common'); ?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"><!-- Tell the browser to be responsive to screen width -->

<title>Welcome to Indian Institute of Banking &amp; Finance -Candidate List</title>

<link rel="stylesheet" href="<?php echo base_url('assets/admin/bootstrap/css/bootstrap.min.css'); ?>"><!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="<?php echo base_url('assets/chosen/bootstrap-chosen.css');?>"><!----- FOR SELECT DROPDOWN ----->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css"><!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css"><!-- Ionicons -->
<link rel="stylesheet" href="<?php echo base_url('assets/admin/dist/css/AdminLTE.min.css'); ?>"><!-- Theme style -->

<!-- AdminLTE Skins. Choose a skin from the css/skins
folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="<?php echo base_url('assets/admin/dist/css/skins/_all-skins.min.css'); ?>">

<script src="<?php echo base_url('assets/admin/plugins/jQuery/jQuery-2.2.0.min.js'); ?>"></script><!-- jQuery 2.2.3 -->

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<style>
	.navbar-custom-menu { width:95%; padding:5px 0; }
	ul.navlogo { width:100%; }
	ul.navlogo li:first-child { float:left; display:inline-block; color:#2ea0e2; text-transform:uppercase; font-size:24px; line-height:42px; }
	ul.navlogo li:last-child { float:right; display:inline-block; }
	.skin-blue .main-header .navbar .nav > li > a { color:#2ea0e2; }
	
	.form-group label { margin-bottom: 5px; line-height: 16px; display: block; }
	label.error { color: red; font-weight: 500; margin: 0px 0 0 0; line-height: 16px; font-size: 12px; }
	form#myForm { background: #eaeaea; padding: 20px; }
	.form-group ul.chosen-results li::before, .chosen-container-multi .chosen-choices .search-field::before, ul.chosen-choices li::before { display:none; }			
	.form-control, .chosen-container { border: 1px solid #ccc !important; border-radius: 000px !important; min-height:36px; }			
	.form-control:focus { border: 1px solid #ccc !important; box-shadow: none !important; }			
	.chosen-container-active .chosen-single, .chosen-container-single .chosen-default, .chosen-container-multi .chosen-choices { border:none !important; }			
	.chosen-container-single .chosen-single { line-height: 34px !important; border:none !important; }
	.chosen-container-multi .chosen-choices .search-field .default { padding-left: 8px !important; }
	
	a.logo { background: #fff !important; border-right: 1px solid #222d32; padding: 13px 8px 0 !important; cursor: auto; }
	a.logo img { max-width: 100%; display: block; }
	
	a.logo img.iibf_logo_small { display:none; }
	.sidebar-mini.sidebar-collapse a.logo img.iibf_logo_main { display:none; }
	.sidebar-mini.sidebar-collapse a.logo img.iibf_logo_small { display:block; }
	
	.sidebar-toggle:hover, a.dropdown-toggle:hover { background: transparent !important; color: #222d32 !important; }
	.red { color:red; }
	h4.title_common { margin: 5px 0 15px 0; font-weight: 600; text-align: center; }
	
	/****** Loader *******/
	#page_loader { background: rgba(0, 0, 0, 0.35) none repeat scroll 0 0; height: 100%; left: 0; position: fixed; top: 0; width: 100%; z-index: 99999; }
	#page_loader .loading { margin: 0 auto; position: relative;border: 16px solid #f3f3f3;border-radius: 50%;border-top: 16px solid #357ca5;border-bottom: 16px solid #357ca5;width: 80px;height: 80px;-webkit-animation: spin 2s linear infinite;animation: spin 2s linear infinite;top: calc( 50% - 40px);}
	@-webkit-keyframes spin {
		0% { -webkit-transform: rotate(0deg); }
		100% { -webkit-transform: rotate(360deg); }
	}
	@keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
	}
</style>