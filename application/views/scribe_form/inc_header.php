<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>IIBF - Scribe</title>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<link rel="stylesheet" href="<?php echo base_url('assets/admin/bootstrap/css/bootstrap.min.css'); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<link rel="stylesheet" href="<?php echo base_url('assets/admin/dist/css/AdminLTE.min.css'); ?>">
<!--link rel="stylesheet" href="<?php echo base_url()?>assets/admin/dist/css/skins/_all-skins.min.css"-->
<link href="<?php echo base_url('assets/css/custom_cms.css'); ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/admin/dist/css/styles.css');?>" rel="stylesheet">

<script src="<?php echo base_url('assets/admin/plugins/jQuery/jQuery-2.2.0.min.js'); ?>"></script>

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<style>
	.modal-dialog { position: relative; display: table; overflow-y: auto; overflow-x: auto; width: 920px; min-width: 300px; }
	.skin-blue .main-header .navbar { background-color: #fff; }
	body.layout-top-nav .main-header h1 { color: #0699dd; margin-bottom: 0; margin-top: 30px; }
	.ifci_logo_black { position: absolute; top: 25px; z-index: 1031; left: 135px; }
	.container { position: relative; }
	.box-header.with-border { background-color: #f1f1f1; border-top-left-radius: 10px; border-top-right-radius: 10px; }
	.header_blue { background-color: #2ea0e2 !important; color: #fff !important; }
	.box { border: 1px solid #00c0ef; box-shadow: none; border-radius: 10px; }
	.nobg { background: none !important; border: none !important; }
	.box-title-hd { color: #3c8dbc; font-size: 16px; }
	.blue_bg { background-color: #e7f3ff;	}
	.m_t_15 { margin-top: 15px; }
	.main-footer { padding-left: 160px; padding-right: 160px; }
	.content-header > h1 { font-size: 22px; font-weight: 600; }
	h4 { margin-top: 0; margin-bottom: 20px !important; font-size: 16px; line-height: 24px; padding: 0 5px; }
	.form-horizontal .control-label { padding-top: 4px; }
	.pad_top_2 { padding-top: 2px !important; }
	.pad_top_0 { padding-top: 0px !important; }
	body.layout-top-nav .main-header h1 { margin: 20px 0 0 20px; /*display:inline-block;*/ font-size: 30px; }
	.main-header { border-top: 1px solid #1287c0; border-left: 1px solid #1287c0; border-right: 1px solid #1287c0; width: 60%; margin: 1% auto 0; padding: 10px; }
	.short_logo { display: inline-block; float: left; margin: 0 0 0 20px; }
	.login-logo a { color: #619fda; font-weight: 600; text-align: center; font-size: 28px; line-height: 24px; display: inline-block; }
	.login-logo a small { font-size: 14px; color: #1d1d1d; }
	.content-wrapper { background-color: #fff; }
	.container { width: 60%; border-left: 1px solid #1287c0; border-right: 1px solid #1287c0; border-bottom: 1px solid #1287c0; margin-bottom: 10px; }
	.box { border: none; } 
	.box-body { padding: 0; }
	.box-body ul li { background-color: #dcf1fc; padding: 3px 10px 3px 30px; margin: 3px 0; list-style: none; position: relative; }
	.box-body ul li:before { display: block; position: absolute; font-family: FontAwesome; content: "\f04e"; top: 5px; left: 10px; color: #9d0000; font-size: 12px; opacity: 0.8; }
	.box-body ul li a { color: #9d0000; }
	.box-body ul li a:hover { color: #9d0000; text-decoration: underline; }
	.content-header { padding: 0; } 
	.content-header h1 { background-color: #7fd1ea; color: #fff; margin: 0 auto; padding: 5px 0; }
	.content { padding: 0; }
	
	label.error { color: #F00; font-size: 13px; text-align: left; display: block; margin: 3px 0 0 0; line-height: 14px; font-weight: 500; }
	
	.main-footer { padding-left: 0; padding-right: 0; }
	#captcha_img ul li.parsley-required { text-align:center; }
	.captcha_input, .captcha_input:focus, .captcha_input:hover, .captcha_input:active { display: inline-block; width: 30px; height: 24px; background-color: #fff; text-align: center; border: 1px solid #ccc !important; vertical-align: middle; line-height: 22px; margin: 0 2px 2px 2px; box-shadow: none; }
	
	.loading { background-color: rgba(0, 0, 0, 0.1); height: 100%; min-height: 100%; position: fixed; text-align: center; top: 0; width: 100%; z-index: 9999; left:0; }
	.loading > img { top: 35%; width: 130px; position: relative; }
</style>