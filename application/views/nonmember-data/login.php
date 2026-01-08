<!DOCTYPE html>
<html>
	<head>
	<?php $this->load->view('google_analytics_script_common'); ?>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>IIBF - FEDAI Non Member Data Collection</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="stylesheet" href="<?php echo  base_url()?>assets/admin/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
		<link rel="stylesheet" href="<?php echo  base_url()?>assets/admin/dist/css/AdminLTE.min.css">
		<link rel="stylesheet" href="<?php echo  base_url()?>assets/admin/plugins/iCheck/square/blue.css">
		<script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		
		<style>
			ul {
			margin-top: -1px;
			padding: 5px 10px 5px 30px;
			border: 1px solid #1287c0;
			background-color: #dcf1fc;
			}
			ul li {
			padding: 2px 0;
			}
			.login-box-body a {
			line-height: 20px;
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
			
			label {
			line-height: 18px;
			font-weight: normal;
			}
			form {
			padding: 20px 10px 30px;
			border: 1px solid #1287c0;
			background-color: #dcf1fc;
			}
			.form-group {
			margin-bottom: 10px;
			}
			a.forget {
			color: #9d0000;
			line-height: 24px;
			}
			a.forget:hover {
			color: #9d0000;
			text-decoration: underline;
			}
			.btn.btn-flat {
			min-height: 34px;
			background-color: #015171;
			}
			.red {
			color: #f00;
			}
			
			.login-box-body, .register-box-body 
			{
				background: rgba(255,255,255,1);
				padding: 0 0 20px;
				border-top: 0;
				color: #000;
				width: 90%;
				position: unset;
				left: 0;
				margin: 20px auto 10px;
				max-width: 600px;
			}
			
			label.error 
			{
				margin: 2px 0 0 0;
				display: block !important;
				line-height: 18px;
				font-size: 13px;
			}
			
			h4.login_heading 
			{
				text-align: center;
				margin: 0 0 20px 0;
				font-weight: 600;
				border-bottom: 1px solid #1287c0;
				padding-bottom: 10px;
				color: #1287c0;
				font-size: 18px;
			}
			
			.login-box 
			{
				width: 100%;
				max-width: 700px;
			}
			
			.login-logo, .register-logo
			{
				border-bottom: 1px solid #1287c0;
				padding-bottom: 7px;
			}
		</style>
	</head>
	
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<div class="short_logo"><img src="<?php echo base_url();?>assets/images/iibf_logo_short.png"></div>
				<div><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br><small>(An ISO 21001:2018 Certified)</small></a></div>
			</div>
			
			<div class="login-box-body">
				<?php 
					if($this->session->flashdata('error')!=''){?>								
					<div class="alert alert-danger alert-dismissible" id="error_id">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<?php echo $this->session->flashdata('error'); ?>
					</div>								
					<?php } 
					
					if($this->session->flashdata('success')!=''){ ?>
					<div class="alert alert-success alert-dismissible" id="success_id">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<?php echo $this->session->flashdata('success'); ?>
					</div>
				<?php } ?>   
				
				<form action="<?php echo site_url('NonmemberData/login'); ?>" method="post" name="loginFrm" id="loginFrm">
					<h4 class="login_heading">FEDAI Non Member Data Collection Login </h4>

					<div class="form-group has-feedback clearfix">
						<label for="member_no" class="col-md-4">Membership No. <span class="red">*</span> :</label>
						<div class="col-md-8">
							<input type="text" class="form-control" placeholder="Membership No." name="member_no" id="member_no" value="<?php echo set_value('member_no'); ?>" autocomplete="off" required>
							<?php if(form_error('member_no')!="") { ?><label class="error"><?php echo form_error('member_no'); ?></label> <?php } ?>
						</div>
					</div>					
					
					<div class="form-group has-feedback clearfix">						
						<label for="code" class="col-md-4">Type the exact characters you see in the picture <span class="red">*</span>.</label>			
						<div class="col-md-8">
							<input type="text" class="form-control" placeholder="Code" name="code" id="code" autocomplete="off"  style="padding-right:10px !important;" required>
							<?php if(form_error('code')!=""){ ?><label class="error"><?php echo form_error('code'); ?></label> <?php } ?>
							<label>
								<div id="captcha_img" style="margin-top:4px;"><?php echo $image;?></div>
								<a href="javascript:void(0);" id="new_captcha" class="forget" onclick="refresh_captcha_img()">Change Image</a>	
							</label>						
						</div>
					</div>
					
					<div class="row">
						<div class="col-xs-12 text-center">
							<input id="Submit" class="btn btn-primary btn-flat" name="btnLogin" value="Submit" type="submit">
							<input class="btn btn-primary btn-flat" onClick="reloadpage()" name="Reset" value="Reset" type="reset">
						</div>
						<span style="color:#F00;"></span> 
					</div>
				</form>	
			</div>
		</div>
		
		<script src="<?php echo  base_url()?>assets/admin/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/iibfbcbf/jquery_validation/jquery.validate.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/iibfbcbf/jquery_validation/jquery.validate_additional.js'); ?>"></script>
		
		<script type="text/javascript">
      function refresh_captcha_img()
			{
				$.ajax(
				{
					type: 'POST',
					url: "<?php echo site_url('NonmemberData/generatecaptchaajax'); ?>",
					success: function(res)
					{	
						if(res!='')
						{
							$('#captcha_img').html(res);
							$("#code-error").html('');
							$("#code").val('');
						}
					}
				});
			}

			function reloadpage() 
			{
				var url = window.location.href;
				window.location.href = url;
			}

			$(document).ready(function() 
      {
        $.validator.addMethod("required", function(value, element) { if ($.trim(value).length == 0) { return false; } else { return true; } });
        
        $("#loginFrm").validate(
        {
          onblur: function(element) { $(element).valid(); },
          rules: 
          { 
            member_no: { required: true, remote: { url: "<?php echo site_url('NonmemberData/validation_check_member_no/0/1'); ?>", type: "post" } },
            code: { required: true, remote: { url: "<?php echo site_url('NonmemberData/validation_check_captcha/0/1'); ?>", type: "post" } }
          },
          messages: 
          {
            member_no: { required: "Please enter the Membership No", remote: "Please enter the valid Membership No" },
            code: { required: "Please enter the code", remote: "Please enter the valid code" }
          },
          submitHandler: function(form) 
          {
            $("#page_loader").show();
            form.submit();
          }
        });
      });
		</script>
	</body>
</html>