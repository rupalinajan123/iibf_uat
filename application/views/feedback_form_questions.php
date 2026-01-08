<!DOCTYPE html>
<html>
<head>
<?php $this->load->view('google_analytics_script_common'); ?>
<script>var site_url="http://iibf.teamgrowth.net/";</script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>IIBF</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
   		 <link rel="stylesheet" href="<?php echo base_url();?>/assets/admin/bootstrap/css/bootstrap.min.css">	 
   <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url();?>/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>/assets/admin/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url();?>/assets/admin/dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="<?php echo base_url();?>/assets/css/subject-pre.css">
<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url();?>/assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<!--<script src="http://iibf.teamgrowth.net/js/jquery.js"></script>-->
<script src="<?php echo base_url();?>/assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
 <link rel="stylesheet" href="<?php echo base_url();?>/assets/admin/plugins/datepicker/datepicker3.css">
  <link rel="stylesheet" href="<?php echo base_url();?>/assets/admin/plugins/iCheck/all.css">
  		<link href="<?php echo base_url();?>/assets/css/parsley.css" rel="stylesheet"> 
        <script src="<?php echo base_url();?>/assets/js/parsley.min.js"></script>
    <link href="<?php echo base_url();?>/assets/css/custom_cms.css" rel="stylesheet">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style>
.modal-dialog{
    position: relative;
    display: table; 
    overflow-y: auto;    
    overflow-x: auto;
    width: 920px;
    min-width: 300px;   
}
.skin-blue .main-header .navbar {
	background-color:#fff;
}
body.layout-top-nav .main-header h1 {
	color:#0699dd;
	margin-bottom:0;
	margin-top:30px;
}
.ifci_logo_black {
  position: absolute;
  top: 25px;
  z-index: 1031;
  left: 135px;
}
.container {
	position:relative;
}
.box-header.with-border {
	background-color:#f1f1f1;
	border-top-left-radius:10px;
	border-top-right-radius:10px;
}
.header_blue {
	background-color:#2ea0e2 !important;
	color:#fff !important;
}
.box {
	border:1px solid #00c0ef;
	box-shadow:none;
	border-radius:10px;
}
.nobg {
	background:none !important;
	border:none !important;
}
.box-title-hd {
	color:#3c8dbc;
	font-size:16px;
}
.blue_bg {
	background-color:#e7f3ff;
}
.m_t_15 {
	margin-top:15px;
}
.main-footer {
	padding-left:160px;
	padding-right:160px;
}
.content-header > h1 {
	font-size:22px;
	font-weight:600;
}
h4 {
	margin-top:0;
	margin-bottom:20px !important;
	font-size:16px;
	line-height:24px;
	padding:0 5px;
}
.form-horizontal .control-label {
	padding-top:4px;
}
.pad_top_2 {
	padding-top:2px !important;
}
.pad_top_0 {
	padding-top:0px !important;
}
body.layout-top-nav .main-header h1 {
	margin:20px 0 0 20px;
	/*display:inline-block;*/
	font-size:30px;
}
.main-header {
	border-top:1px solid #1287c0;
	border-left:1px solid #1287c0;
	border-right:1px solid #1287c0;
	width:60%;
	margin:1% auto 0;
	padding:10px;
}
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
	.content-wrapper {
		background-color:#fff;
	}
	.container {
		width:60%;
		border-left:1px solid #1287c0;
		border-right:1px solid #1287c0;
		border-bottom:1px solid #1287c0;
		margin-bottom:10px;
	}
	.box {
		border:none;
	}
	.box-body {
		padding:0;
	}
	.box-body ul li {
		background-color:#dcf1fc;
		padding:3px 10px 3px 30px;
		margin:3px 0;
		list-style:none;
		position:relative;
	}
	.box-body ul li:before {
		display: block;
		position:absolute;
		font-family: FontAwesome;
		content: "\f04e";
		top:5px;
		left:10px;
		color:#9d0000;
		font-size:12px;
		opacity:0.8;
	}
	.box-body ul li a {
		color:#9d0000;
	}
	.box-body ul li a:hover {
		color:#9d0000;
		text-decoration:underline;
	}
	.content-header {
		padding:0 0 0 10px;
	}
	.content-header h1 {
		background-color:#7fd1ea;
		color:#fff;
		margin:0 auto;
		padding:5px 0;
	}
	.content {
		padding:0;
	}
	element {
    text-align: left;
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
     <div class="short_logo">
     <img src="http://iibf.teamgrowth.net/assets/images/iibf_logo_short.png">
     </div>
    <div class="login-logo"><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br><small>(An ISO 21001:2018 Certified)</small></a></div>
    </nav>
  </header>
       <div class="loading" style="display:none;"><img src="http://iibf.teamgrowth.net/assets/images/loading.gif"></div><style>
	.modal-dialog {
	position: relative;
	display: table;
	overflow-y: auto;
	overflow-x: auto;
	width: 920px;
	min-width: 300px;
	}
	#confirm .modal-dialog {
	position: relative;
	display: table;
	overflow-y: auto;
	overflow-x: auto;
	width: 420px;
	min-width: 400px;
	}
	.skin-blue .main-header .navbar {
	background-color: #fff;
	}
	body.layout-top-nav .main-header h1 {
	color: #0699dd;
	margin-bottom: 0;
	margin-top: 30px;
	}
	.container {
	position: relative;
	}
	.box-header.with-border {
	background-color: #7fd1ea;
	border-top-left-radius: 0;
	border-top-right-radius: 0;
	margin-bottom: 10px;
	}
	.header_blue {
	background-color: #2ea0e2 !important;
	color: #fff !important;
	margin-bottom: 0 !important;
	}
	.box {
	border: none;
	box-shadow: none;
	border-radius: 0;
	margin-bottom: 0;
	}
	.nobg {
	background: none !important;
	border: none !important;
	}
	.box-title-hd {
	color: #3c8dbc;
	font-size: 16px;
	margin: 0;
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
	margin-top: 5px;
	margin-bottom: 10px !important;
	font-size: 14px;
	line-height: 18px;
	padding: 0 5px;
	font-weight: 600;
	text-align: justify;
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
	div.form-group:nth-child(odd) {
	background-color: #dcf1fc;
	padding: 5px 0;
	}
	#confirmBox {
	display: none;
	background-color: #eee;
	border-radius: 5px;
	border: 1px solid #aaa;
	position: fixed;
	width: 300px;
	left: 50%;
	margin-left: -150px;
	padding: 6px 8px 8px;
	box-sizing: border-box;
	text-align: center;
	z-index: 1;
	box-shadow: 0 1px 3px #000;
	}
	#confirmBox .button {
	background-color: #ccc;
	display: inline-block;
	border-radius: 3px;
	border: 1px solid #aaa;
	padding: 2px;
	text-align: center;
	width: 80px;
	cursor: pointer;
	}
	#confirmBox .button:hover {
	background-color: #ddd;
	}
	#confirmBox .message {
	text-align: left;
	margin-bottom: 8px;
	}
	.form-group {
	margin-bottom: 10px;
	}
	.form-horizontal .form-group {
	margin-left: 0;
	margin-right: 0;
	}
	.form-control {
	border-color: #888;
	}
	.form-horizontal .control-label {
	font-weight: normal;
	}
	a.forget {
	color: #9d0000;
	}
	a.forget:hover {
	color: #9d0000;
	text-decoration: underline;
	}
	ol li {
	line-height: 18px;
	}
	.example {
	text-align: left !important;
	padding: 0 10px;
	}
</style>
<div class="container">
  <section class="content-header box-header with-border" style="background-color: #1287C0; padding:16px 10px;">
    <h1 class="register" style="padding:0;">Members Feedback regarding JAIIB examination<span style='display: block;font-size: 16px;line-height: 18px; margin: 6px 0 0 0; '></span></h1>    
	</section>
  <div> 
    <!-- Start Get Details -->
	</div>
  <section class="">
    <div class="row">
      <div class="col-md-12" style="">
	   <?php if ($this->session->flashdata('flsh_msg') != '') {?>
					<div class="alert alert-danger"> <?php echo $this->session->flashdata('flsh_msg'); ?> </div>
				<?php }?>
        <?php
					if ($this->session->flashdata('error') != '') {?>
					<div class="alert alert-danger alert-dismissible" id="error_id">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<?php echo $this->session->flashdata('error');?> </div>
					<?php } if ($this->session->flashdata('success') != '') {?>
					<div class="alert alert-success alert-dismissible" id="success_id">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<?php echo $this->session->flashdata('success');?> </div>
					<?php } if (validation_errors() != '') { ?>
					<div class="alert alert-danger alert-dismissible" id="error_id">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<?php echo validation_errors(); ?> </div>
					<?php } if ($var_errors != '') { ?>
					<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<?php echo $var_errors; ?> </div>
				<?php } ?>
                        <form class="form-horizontal" name="feedbackFormQuestion" id="feedbackFormQuestion"  method="post"  enctype="multipart/form-data"  autocomplete="off">
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-body">
                <div class="form-group">
              <label  class="col-md-12 control-label text-left" style="text-align: left;padding-left: 20px;"><strong>Q1.Which one of the following streams have you studied in graduate/post graduate level? <span style="color:#F00">*</span></strong></label>
                <div class="col-sm-12">
				   <label style="width: 50%;padding-left: 10px;"><input type="radio" class="minimal cls_gender edu" id="science" name="streams" required="" value="science" style="top: 2px;" onclick = "return other_streams();" >Science</label>
					 <label style="width: 50%;padding-left: 10px;"><input type="radio" class="minimal cls_gender edu" id="commerce" name="streams"  value="commerce" style="top: 2px;" onclick = "return other_streams();" >Commerce</label>
					 <label style="width: 50%;padding-left: 10px;"><input type="radio" class="minimal cls_gender edu" id="arts" name="streams"  value="arts" style="top: 2px;" onclick = "return other_streams();" >Arts</label>
					 <label style="width: 50%;padding-left: 10px;"><input type="radio" class="minimal cls_gender others" id="others" name="streams"  value="others" style="top: 2px;" onclick = "return other_streams();" >Others   
					 <input type="text" class="otherstreams" id="otherstreams" name="otherstreams" value="" style="display:none;" >
					 </label>
					<span id="streams_error" class="error"></span>
				</div>
            </div>
              <div class="form-group">
                <label  class="col-sm-5 control-label text-left" style="text-align: left;padding-left: 20px;"><strong>Q2.Please indicate your work experience. <span style="color:#F00">*</span></strong></label>
                <div class="col-sm-12">
                  <label style="width: 100%;padding-left: 10px;"><input type="radio" class="minimal cls_gender" id="0-2 years"   name="years"  required value="0-2 years" <?php echo set_radio('years', '0-2 years'); ?> style="top: 2px;">
                  0-2 years</label>
                  <label style="width: 100%;padding-left: 10px;"><input type="radio" class="minimal cls_gender" id="2-5 years"  name="years"  required value="2-5 years" <?php echo set_radio('years', '2-5 years'); ?> style="top: 2px;">
                  2-5 years</label>
				  <label style="width: 100%;padding-left: 10px;"><input type="radio" class="minimal cls_gender" id="5 -10 years"   name="years"  required value="5 -10 years" <?php echo set_radio('years', '5 -10 years'); ?> style="top: 2px;">
                  5 -10 years</label>
				  <label style="width: 100%;padding-left: 10px;"><input type="radio" class="minimal cls_gender" id="10 years and above "   name="years"  required value="10 years and above " <?php echo set_radio('years', '10 years and above '); ?> style="top: 2px;">
                  10 years and above </label>
				  <span id="years_error" class="error"></span>
              </div>
              </div>
			   <div class="form-group">
                <label  class="col-md-9 control-label text-left" style="text-align: left;padding-left: 20px;"><strong>Q3.Had you studied the IIBF books published by Macmillan for the examination? <span style="color:#F00">*</span></strong></label>
                <div class="col-sm-12">
                   <label style="width: 100%;padding-left: 10px;"><input type="radio" class="minimal cls_gender" id="yes"   name="macmillan"  required value="yes" <?php echo set_radio('macmillan', 'yes'); ?> style="top: 2px;">
                  Yes</label>
                   <label style="width: 100%;padding-left: 10px;"><input type="radio" class="minimal cls_gender" id="no"  name="macmillan"  required value="no" <?php echo set_radio('macmillan', 'no'); ?> style="top: 2px;">
                  No</label> <span id="macmillan_error" class="error"></span> </div>
              </div>
			  <div class="form-group">
                <label  class="col-md-12 control-label text-left" style="text-align: left;padding-left: 20px;"><strong>Q4.Did you register and go through the e-learning provided by IIBF? <span style="color:#F00">*</span></strong></label>
                <div class="col-sm-12">
                  <label style="width: 100%;padding-left: 10px;"><input type="radio" class="minimal cls_gender" id="yes"   name="e-learning"  required value="yes" <?php echo set_radio('e-learning', 'yes'); ?> style="top: 2px;">
                  Yes</label>
                  <label style="width: 100%;padding-left: 10px;"><input type="radio" class="minimal cls_gender" id="no"  name="e-learning"  required value="no" <?php echo set_radio('e-learning', 'no'); ?> style="top: 2px;">
                  No</label> <span id="elearning_error" class="error"></span>
              </div>
              </div>
			  <div class="form-group">
                <label  class="col-md-12 control-label text-left" style="text-align: left;padding-left: 20px;"><strong>Q5.Did you register and take the IIBF mock test? <span style="color:#F00">*</span></strong></label>
                <div class="col-sm-12">
                 <label style="width: 100%;padding-left: 10px;"><input type="radio" class="minimal cls_gender" id="yes"   name="mock"  required value="yes" <?php echo set_radio('mock', 'yes'); ?> style="top: 2px;">
                  Yes</label>
                 <label style="width: 100%;padding-left: 10px;"><input type="radio" class="minimal cls_gender" id="no"  name="mock"  required value="no" <?php echo set_radio('mock', 'no'); ?> style="top: 2px;">
                  No </label><span id="mock_error" class="error"></span> 
              </div>
              </div>
			  <div class="form-group">
                <label  class="col-md-12 control-label text-left" style="text-align: left;padding-left: 20px;"><strong>Q6.Did you go through the subject updates provided on IIBF website? <span style="color:#F00">*</span></strong></label>
                <div class="col-sm-12">
                 <label style="width: 100%;padding-left: 10px;"> <input type="radio" class="minimal cls_gender" id="yes"   name="subject"  required value="yes" <?php echo set_radio('subject', 'yes'); ?> style="top: 2px;">
                  Yes</label>
                  <label style="width: 100%;padding-left: 10px;"><input type="radio" class="minimal cls_gender" id="no"  name="subject"  required value="no" <?php echo set_radio('subject', 'no'); ?> style="top: 2px;">
                  No</label> <span id="subject_error" class="error"></span> 
              </div>
              </div>
			   <div class="form-group">
                <label  class="col-md-12 control-label text-left" style="text-align: left;padding-left: 20px;"><strong>Q7.Please indicate the time spent by you in preparing for the exam. <span style="color:#F00">*</span></strong></label>
                <div class="col-sm-12">
                  <label style="width: 100%;padding-left: 10px;"><input type="radio" class="minimal cls_gender" id="Less than 10 days"   name="time"  required value="Less than 10 days" <?php echo set_radio('time', 'Less than 10 days'); ?> style="top: 2px;">
                 Less than 10 days </label>
                 <label style="width: 100%;padding-left: 10px;"><input type="radio" class="minimal cls_gender" id="10 days to 1 month"  name="time"  required value="10 days to 1 month" <?php echo set_radio('time', '10 days to 1 month'); ?> style="top: 2px;">
                  10 days to 1 month</label>
				  <label style="width: 100%;padding-left: 10px;"><input type="radio" class="minimal cls_gender" id="1 month to 3 months"   name="time"  required value="1 month to 3 months" <?php echo set_radio('time', '1 month to 3 months'); ?> style="top: 2px;">
                  1 month to 3 months</label>
				  <label style="width: 100%;padding-left: 10px;"><input type="radio" class="minimal cls_gender" id="More than 3 months"   name="time"  required value="More than 3 months" <?php echo set_radio('time', 'More than 3 months'); ?> style="top: 2px;">
                  More than 3 months </label>
				  <span id="time_error" class="error"></span> 
              </div>
              </div>
			  <div class="form-group">
                <label  class="col-md-12 control-label text-left" style="text-align: left;padding-left: 20px;"><strong>Q8.How did you find the examination?<span style="color:#F00">*</span></strong></label>
                <div class="col-sm-12">
                  <label style="width: 100%;padding-left: 10px;"><input type="radio" class="minimal cls_gender" id="easy"   name="exam"  required value="easy" <?php echo set_radio('exam', 'easy'); ?> style="top: 2px;">
                  Easy</label>
                  <label style="width: 100%;padding-left: 10px;"><input type="radio" class="minimal cls_gender" id="moderate"  name="exam"  required value="moderate" <?php echo set_radio('exam', 'moderate'); ?> style="top: 2px;">
                  Moderate </label>
				  <label style="width: 100%;padding-left: 10px;"><input type="radio" class="minimal cls_gender" id="difficult"   name="exam"  required value="difficult" <?php echo set_radio('exam', 'difficult '); ?> style="top: 2px;">
                  Difficult </label><span id="exam_error" class="error"></span>
              </div>
              </div>
			  <div class="form-group">
				<label  class="col-md-12 control-label text-left" style="text-align: left;padding-left: 20px;"><strong>Q9.Which subject did you find difficult? <span style="color:#F00">*</span></strong></label>
					 <div class="col-sm-12" id ="subcheck">
							<label style="width: 100%;padding-left: 10px;"><input type="checkbox" class="minimal subj" id="subject_difficult" name="subject_difficult[]" required value="Principles & Practices of Banking" <?php echo set_checkbox('subject_difficult', 'Principles & Practices of Banking'); ?>  style="top: 2px;"/> Principles & Practices of Banking</label>
							<label style="width: 100%;padding-left: 10px;"><input type="checkbox" class="minimal subj" id="subject_difficult" name="subject_difficult[]" required value="Accounting & Finance for Bankers" <?php echo set_checkbox('subject_difficult', 'Accounting & Finance for Bankers'); ?> style="top: 2px;"/>Accounting & Finance for Bankers</label>
							<label style="width: 100%;padding-left: 10px;"><input type="checkbox" class="minimal subj" id="subject_difficult" name="subject_difficult[]" required value="Legal & Regulatory Aspects of Banking" <?php echo set_checkbox('subject_difficult', 'Legal & Regulatory Aspects of Banking'); ?> style="top: 2px;"/>Legal & Regulatory Aspects of Banking</label>
							<label style="width: 100%;padding-left: 10px;"><input type="checkbox" class="minimal nosubj" id="subject_difficult" name="subject_difficult[]" required value="None of the above" <?php echo set_checkbox('subject_difficult', 'None of the above'); ?> onclick = "checksub(subcheck, $(this));" style="top: 2px;"/>None of the above</label> 	<span id="subject_difficult_error" class="error"></span>	
					</div>
			</div> 
			<div class="form-group">
				<label  class="col-md-12 control-label text-left" style="text-align: left;padding-left: 20px;"><strong>Q10.In which type of questions did you face difficulty?   <span style="color:#F00">*</span></strong></label>
					<div class="col-sm-12">
							<label style="width: 100%;padding-left: 10px;"><input type="checkbox" class="minimal" id="questions1" name="questions[]" required value="Concept based" <?php echo set_checkbox('questions', 'Concept based'); ?> style="top: 2px;"/>Concept based</label>
							<label style="width: 100%;padding-left: 10px;"><input type="checkbox" class="minimal" id="questions2" name="questions[]" required value="Numerical" <?php echo set_checkbox('questions', 'Numerical'); ?> style="top: 2px;"/>Numerical</label>
							<label style="width: 100%;padding-left: 10px;"><input type="checkbox" class="minimal" id="questions3" name="questions[]" required value="Theoretical" <?php echo set_checkbox('questions', 'Theoretical'); ?> style="top: 2px;"/>Theoretical</label>
									<span id="questions_error" class="error"></span>
					</div>
			</div>
			<div class="form-group">
				<label  class="col-md-12 control-label text-left" style="text-align: left;padding-left: 20px;"><strong>Q11.What type of additional pedagogical support you require from IIBF?    <span style="color:#F00">*</span></strong></label>
					<div class="col-sm-12">
							<label style="width: 100%;padding-left: 10px;"><input type="checkbox" class="minimal " id="support" name="support[]" required value="Greater coverage of topics in the courseware" <?php echo set_checkbox('support', 'Greater coverage of topics in the courseware'); ?> style="top: 2px;"/>Greater coverage of topics in the courseware</label>
							<label style="width: 100%;padding-left: 10px;"><input type="checkbox" class="minimal" id="support" name="support[]" required value="e-learning" <?php echo set_checkbox('support', 'e-learning'); ?> style="top: 2px;"/>Greater coverage of topics in e-learning</label>
							<label style="width: 100%;padding-left: 10px;"><input type="checkbox" class="minimal" id="support" name="support[]" required value="video lectures" <?php echo set_checkbox('support', 'video lectures'); ?> style="top: 2px;"/>Greater coverage of topics in video lectures </label><span id="support_error" class="error"></span>
					</div>
			</div>
			 <div class="form-group">
                <label  class="col-md-12 control-label text-left" style="text-align: left;padding-left: 20px;"><strong>Q12.Do you feel that the time allotted for the exam is sufficient? <span style="color:#F00">*</span></strong></label>
				<div class="col-sm-12">
                  <label style="width: 100%;padding-left: 10px;"><input type="radio" class="minimal cls_gender" id="yes"   name="examtime"  required value="yes" <?php echo set_radio('examtime', 'yes'); ?> style="top: 2px;">
                  Yes</label>
                  <label style="width: 100%;padding-left: 10px;"><input type="radio" class="minimal cls_gender" id="no"  name="examtime"  required value="no" <?php echo set_radio('examtime', 'no'); ?> style="top: 2px;">
                  No</label> <span id="examtime_error" class="error"></span>
              </div>
              </div>
			  <div class="form-group">
                <label  class="col-sm-4 control-label text-left" style="text-align: left;padding-left: 20px;"><strong>Q13.Your suggestions:<span style="color:#F00">*</span></strong><br><span id="suggestions_error" class="error"></span></label> 
                <div class="col-sm-3">
					<textarea id="suggestions" name="suggestions" rows="4" cols="50" required>
					</textarea>
				  </div>
              </div>
            </div>
            <!-- Basic Details box closed-->
             <div class="box-footer">
              <div class="col-sm-6 col-sm-offset-3">
                <input type="submit" name="btnSubmit" class="btn btn-info" id="btnSubmit" value="Submit" onclick = "return feedbackForm_Question();">  
                <a href="<?php echo base_url();?>FeedbackForm/feedback_questions" class="btn btn-default" >Reset</a> 
                <a href="<?php echo base_url();?>FeedbackForm" class="btn btn-default" >Back</a> </div>
            </div> 
          </div>
        </div>
      </div>
    </section>
            </div>
          </div>
        </div>
      </div>
    </section>
  </form>
			</div>
		</div>
	</section>
  <br />
  <!-- Close Get Details-->
  </div>
<link href="<?php echo base_url();?>/assets/admin/dist/css/styles.css" rel="stylesheet">
</div>
<!-- ./wrapper -->
 				 <script src="<?php echo base_url();?>/assets/admin/bootstrap/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url();?>/assets/admin/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>/assets/admin/dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url();?>/assets/admin/dist/js/demo.js"></script>
<script>
function other_streams()
{
	if($(".others").prop('checked'))
	{
		//alert('in if');
		//$(".otherstreams").show();
		document.getElementById("otherstreams").style.display='inline';
		$('#otherstreams').attr('required',true);
		//$("#otherstreams").val("").attr("required","true");
	}
	else
	{
		//alert('in else');
		//$('.edu').prop('checked', false);
		document.getElementById("otherstreams").style.display='none';
	}
}
function checksub(e,t)
{
	//var sub_val = $(".nosubj").val(); alert(sub_val);
	 if($(".nosubj").prop('checked'))
	 {
		  $(".subj").attr("disabled", true);
		 $('.subj').prop('checked', false);
	 }
	 else
	 {
		$(".subj").attr("disabled", false);
		// $(".nosubj").attr("disabled", true);
		$('.subj').prop('checked', false);
	 }
}
function feedbackForm_Question()
{
	var form_flag=$('#feedbackFormQuestion').parsley().validate();
	if(form_flag)
			{
				//$('#regnumber').parsley().isValid();
				return true;
			}
			else
			{
				return false;
			}
	/* $('#error_id').html(''); 
	$('#success_id').html('');
	$('#error_id').removeClass("alert alert-danger alert-dismissible");
	$('#success_id').removeClass("alert alert-danger alert-dismissible");
	var streams = $('#streams').val();
	var years = $('#years').val();
	var macmillan = $('#macmillan').val();
	var elearning = $('#e-learning').val();
	var mock = $('#mock').val();
	var subject = $('#subject').val();
	var time = $('#time').val();
	var exam = $('#exam').val();
	var subject_difficult = $('#subject_difficult').val();
	var questions = $('#questions').val();
	var support = $('#support').val();
	var examtime = $('#examtime').val();
	var suggestions = $('#suggestions').val();
	if(streams == '' || years == '' ||macmillan == '' ||e-learning == '' ||mock == '' ||subject == '' ||time == '' ||exam == '' ||subject_difficult == '' ||questions == '' || support == '' ||examtime == '' || suggestions == '')
	{
		$("#streams_error").html('Please enter study.');
		$("#years_error").html('Please enter work experienc.');
		$("#macmillan_error").html('Please select YES or NO.');
		$("#elearning_error").html('Please select YES or NO.');
		$("#mock_error").html('Please select YES or NO.');
		$("#subject_error").html('Please select YES or NO.');
		$("#time_error").html('Please enter time spent by you.');
		$("#exam_error").html('Please enter examination.');
		$("#subject_difficult_error").html('Please select difficult subject.');
		$("#questions_error").html('Please select YES or NO.');
		$("#support_error").html('Please select YES or NO.');
		$("#examtime_error").html('Please select YES or NO.');
		return false;
	}
	else{
		return true;
	} */
}
/* $(document).ready(function() {
	$("#btnSubmit").on("click", function(e) {
		var flag = $('#feedbackForm').parsley().validate();
	$('#feedbackForm').parsley('validate');
});
}); */
</script>
 <footer class="main-footer">
    <div class="pull-right hidden-xs">
     Powered By <b>ESDS</b> 
    </div>
    <strong>Copyright &copy;&nbsp;2021 <a href="javascript:void(0);">ESDS</a>.</strong> All rights
    reserved. 
  </footer>
</body>
</html>