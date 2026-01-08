<!DOCTYPE html>
<html>
	<head>
		<script>var site_url="<?php echo base_url();?>";</script>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>IIBF - Member Login</title>
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
			padding: 10px;
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
				max-width: 500px;
			}
			
			label.error 
			{
				margin: 2px 0 0 0;
				display: block !important;
				line-height: 14px;
				font-size: 13px;
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
				
				<form action="" method="post" name="loginFrm" id="loginFrm">
					<div class="form-group has-feedback clearfix">
						<label for="Username" class="col-md-5">Membership No. <span class="red">*</span> :</label>
						<div class="col-md-7">
							<input type="text" class="form-control" placeholder="User name" name="Username" id="Username" value='<?php echo set_value('Username'); ?>' autocomplete="off" required>
							<?php if(form_error('Username')!=""){ ?><label class="error"><?php echo form_error('Username'); ?></label> <?php } ?>
						</div>
					</div>					
					
					<div class="form-group has-feedback clearfix">						
						<label for="code" class="col-md-5">Type the exact characters you see in the picture <span class="red">*</span>.</label>			
						<div class="col-md-7">
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
				
				<?php 
					$obj = new OS_BR();
					if($obj->showInfo('browser')=='Internet Explorer')
					{ ?>
					<ul>
						<div  style="color:#F00">
						Portal best viewed in recommended browsers (Mozilla Firefox or Google chrome latest versions) for error free performance. It is suggested Internet explorer not to be used to avoid issues.</div>
					</ul>
				<?php } ?>	
			</div>
		</div>
		
		<script src="<?php echo  base_url()?>assets/admin/bootstrap/js/bootstrap.min.js"></script>
		<script src="<?php echo  base_url()?>assets/admin/plugins/iCheck/icheck.min.js"></script>
		<script type="text/javascript">
			$(function () 
			{
				$('input').iCheck(
				{
					checkboxClass: 'icheckbox_square-blue',
					radioClass: 'iradio_square-blue',
					increaseArea: '20%' // optional
				});
			});
			
			function refresh_captcha_img()
			{
				$.ajax(
				{
					type: 'POST',
					url: "<?php echo site_url('jaiibReschedule_121/generatecaptchaajax'); ?>",
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
		</script>
		
		<script src="<?php echo base_url()?>js/jquery.validate.min.js"></script>
		<?php $this->load->view('apply_elearning/common_validation_all'); ?>
		
		<script type="text/javascript">
			$(document ).ready( function() 
			{
				$.validator.addMethod("validate_member_no", function(value, element)
        {
          if($.trim(value).length == 0) { return true; }
          else
          {
            var isSuccess = false;
            var parameter = { "Username":$.trim(value) }
            $.ajax(
            {
              type: "POST",
              url: "<?php echo site_url('JaiibReschedule_121/validate_member_no/0/0') ?>",
              data: parameter,
              async: false,
              dataType: 'JSON',
              success: function(data)
              {
								if($.trim(data.flag) == 'success')
                {
                  isSuccess = true;
                }
                else
                {
									refresh_captcha_img();
                }
                
                $.validator.messages.validate_member_no = data.response;
              }
            });
						
						return isSuccess;
          }
        }, '');
				
				$("#loginFrm").validate( 
				{
					rules:
					{
						Username: { required : true, validate_member_no:true },			
						code: { required : true, remote: { url: "<?php echo site_url('JaiibReschedule_121/validate_captcha_code/0/0') ?>", type: "post" } },	
					},
					messages:
					{
						Username: { required : "Please enter Membership No" },
						code: { required : "Please enter code", remote:"Please enter valid captcha" }
					},
					/* errorPlacement: function(error, element) // For replace error 
					{
						if (element.attr("name") == "el_subject[]") 
						{
							error.insertBefore("#el_subject_err");
						}
						else 
						{
							error.insertAfter(element);
						}
					},
          submitHandler: function(form) 
          {
            var availeble_subjects_cnt = $("#availeble_subjects_cnt").val();
            var total_fee = $("#total_fee").val();
            
            if(total_fee > 0)
            {
              if(availeble_subjects_cnt > 0) { return true; }
              else { alert('You can not proceed as no elearning subject available for selected exam'); return false; }
            }
            else
            {
              alert('You can not proceed as the fee amount is zero for selected subjects'); return false;
            }
            
          } */
				});
			});
			
		</script>
	</body>
</html>
