<!DOCTYPE html>
<html>
	<head>
		<?php $this->load->view('apply_elearning/inc_header'); ?>
	</head>
	
	<body class="hold-transition skin-blue layout-top-nav">
		<div class="wrapper">
			<?php $this->load->view('apply_elearning/inc_navbar'); ?>
			
			<div class="container">				
				<section class="content">
					<section class="content-header">
						<h1 class="register">Please verify your humanity by entering the code</h1><br/>
					</section>
					
					<div class="col-md-12">  						
						<div  class ="row">
							<div class="box box-info">
								<form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  action="" autocomplete="off">
									<div class="form-group">
										<label for="roleid" class="col-sm-4 control-label" style="line-height:20px;">Security Code <span style="color:#F00; ">*</span></label>										
										
										<div class="col-sm-3">
											<input type="text" name="captcha_code" id="captcha_code" required class="form-control" placeholder="Security Code" maxlength="5" value="">
										</div>										
										
										<div class="col-sm-5">
											<div id="captcha_img"><?php echo $captcha_img; ?></div>
											<a href="javascript:void(0);" onclick="refresh_captcha_img();" class="text-danger">Change Image</a>
										</div> 
										
										<?php /* <div class="col-sm-5">
											<p class="text-center blue">Please verify your humanity by solving the puzzle.</p>
											<div class="form-group has-feedback clearfix">
												<label style="width: 100%;text-align: center;">
													<div id="captcha_img">
														<input type="text" name="val1" value="<?php echo (rand(1,10))?>" style="width: 30px" readonly> + 
														<input type="text" name="val2" value="<?php echo (rand(1,10))?>"style="width: 30px" readonly> =
														<input type="text" name="val3" autocomplete="off" required style="width: 30px">
													</div>
												</label>
											</div>
										</div> */ ?>
										
									</div>
									
									<div class="col-sm-12 text-center">
										<input type="submit" class="btn btn-info" name="btn_Submit" id="btn_Submit" value="Submit Code"><br><br>
									</div>
								</form>
							</div>
						</div>
						
						<?php $this->load->view('apply_elearning/inc_footerbar'); ?>
					</div>
				</section>
			</div>
		</div>		
		
		<?php $this->load->view('apply_elearning/inc_footer'); ?>
		
		<script>
			$(document ).ready( function() 
			{
				$("#usersAddForm").validate( 
				{
          onkeyup: false,
          onclick: false,
          onblur: false,
          onfocusout: false,
          rules:
					{
						captcha_code: { required : true, remote: { url: "<?php echo site_url('validate_user_captcha/check_captcha_code_ajax') ?>", type: "post", data: { "session_name": "VALIDATE_USER" } } },  		
					},
					messages:
					{
						captcha_code: { required : "Please enter code", remote:"Please enter valid captcha" }
					}
				});
			});
			
			function refresh_captcha_img()
			{
				$(".loading").show();
				$.ajax(
				{
					type: 'POST',
					url: '<?php echo site_url("validate_user_captcha/generate_captcha_ajax/"); ?>',
					data: { "session_name":"VALIDATE_USER" },
					async: false,
					success: function(res)
					{	
						if(res!='')
						{
							$('#captcha_img').html(res);
							$("#captcha_code").val("");
							$("#captcha_code-error").html("");
						}
						$(".loading").hide();
					}
				});
			}
		</script>
		
		<script>	
			$( document ).ready( function () { $('.loading').delay(0).fadeOut('slow'); });
			/* $(document).ready(function() { setTimeout(function() { $('#alert_fadeout').fadeOut(3000); }, 8000 ); }); */
		</script>			
	</body>
</html>				