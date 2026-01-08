<!DOCTYPE html>
<html>
	<head>
		<?php $this->load->view('apply_elearning/inc_header'); ?>
	</head>
	
	<body class="hold-transition skin-blue layout-top-nav">
		<div class="wrapper">
			<?php $this->load->view('apply_elearning/inc_navbar'); ?>
			<?php 
				$loginLable = '';
				$sessionName = 'ELEARNING_SPM';
				if ($login_type == 'sbi') {
					$loginLable = ': SBI Only ';
					$sessionName = 'ELEARNING_SBI';	
				}
			?>
			<div class="container">				
				<section class="content">
					<section class="content-header">
						<h1 class="register">Apply For E-learning <?php echo $loginLable; ?>  </h1><br/><div style="display:none"><?php echo get_ip_address(); ?></div>
					</section>
					
					<div class="col-md-12">  						
						<div  class ="row">
							<?php if($this->session->flashdata('error')!=''){?>								
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
							
							<?php 
								if ( $login_type == 'global') {
									$actionURL = site_url('ApplyElearning/index/'.$member_type);
								} else {
									$actionURL = site_url('ApplyElearning/applyExam');
								}
							?>

							<div class="box box-info">
								<form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  action="<?php echo $actionURL; ?>" autocomplete="off">
									<div class="form-group">
										<label for="roleid" class="col-sm-4 control-label">Membership/Registration No.<span style="color:#F00">*</span></label>
										<div class="col-sm-6">
											<input type="text" class="form-control" id="member_no" name="member_no" placeholder="Membership/Registration No" required value="<?php echo set_value('member_no'); ?>" >
											<?php if(form_error('member_no')!=""){ ?><label class="error"><?php echo form_error('member_no'); ?></label> <?php } ?>
										</div>
									</div>	
									
									<div class="form-group">
										<?php /*
										<label for="roleid" class="col-sm-4 control-label" style="line-height:20px;">Please verify your humanity by solving the puzzle<span style="color:#F00; ">*</span></label>
										<div class="col-sm-6">
											<div id="captcha_img">
												<?php $val1_hidden = (rand(1,10)); $val2_hidden = (rand(1,10)); ?>
												<input type="hidden" name="val1" id="val1" value="<?php echo $val1_hidden; ?>" autocomplete='false'>
												<input type="hidden" name="val2" id="val2" value="<?php echo $val2_hidden; ?>" autocomplete='false'>
												
												<?php echo "<span class='captcha_input' id='captcha_input_val1'>".$val1_hidden."</span> + <span class='captcha_input' id='captcha_input_val2'>".$val2_hidden."</span> = "; ?>
												<input class='captcha_input' type="text" name="val3" id="val3" autocomplete="off" required >
												<?php if(form_error('val3')!=""){ ?><label class="error"><?php echo form_error('val3'); ?></label> <?php } ?>
											</div>
										</div>
										*/ ?>
										
										<label for="roleid" class="col-sm-4 control-label" style="line-height:20px;">Security Code <span style="color:#F00; ">*</span></label>										
										
										<div class="col-sm-3">
											<input type="text" name="captcha_code" id="captcha_code" required class="form-control" placeholder="Security Code" maxlength="5" value="">
										</div>										
										
										<div class="col-sm-5">
											<div id="captcha_img"><?php echo $captcha_img; ?></div>
											<a href="javascript:void(0);" onclick="refresh_captcha_img();" class="text-danger">Change Image</a>
										</div>
									</div>
																		
									<div class="col-sm-12 text-center">
										<input type="submit" class="btn btn-info" name="btn_Submit" id="btn_Submit" value="Get Details">&nbsp;&nbsp;
                    
                    <?php if($member_type == 'non_ordinary') { ?>
										<input type="button" class="btn btn-info" name="btn_register" id="btn_register" value="Non Member Register" onclick="window.location='<?php echo base_url('ApplyElearning/register');?>'">&nbsp;&nbsp;
                    <?php } ?>

                    <?php if($login_type == 'global') { ?>
                    <input type="button" class="btn btn-info" value="Cancel" onclick="window.location='<?php echo base_url('ApplyElearning');?>'">
                    <?php } ?>
									</div>
								</form>
							</div>
							<div class="form-group">
                <div class="col-sm-12 text-center">
                  <span style="color:#F00; font-size:13px; font-weight:bold">
                    <?php if($member_type == 'ordinary') 
                    { 
                      echo "Please enter your membership/registration no and click on get details, if you are already registered with us.";
                    }
                    else if($member_type == 'non_ordinary') 
                    {                    
                      echo "Please enter your e-learning non member no starting with 'EL' and click on get details if you are already registered with us for e-learning. Click on 'Non Member Register' button if applying here for E-Learning as a Non Member for the first time.";
                    } ?>
                  </span>
                </div>
              </div>
						</div>
						
						<?php $this->load->view('apply_elearning/inc_footerbar'); ?>
					</div>
				</section>
			</div>
		</div>		
		
		<?php $this->load->view('apply_elearning/inc_footer'); ?>
				
		<script>

			var loginType   = "<?php echo $login_type; ?>";
			var sessionName = "<?php echo $sessionName; ?>"; 
			$(document ).ready( function() 
			{ 
        $.validator.addMethod("custom_check_member_no_ajax", function(value, element)
        {
          if($.trim(value).length == 0) { return true; }
          else
          {
            var isSuccess = false;
            var parameter = { "member_no":$.trim(value), "member_type":"<?php echo $member_type; ?>","login_type":loginType }
            $.ajax(
            {
              type: "POST",
              url: "<?php echo site_url('ApplyElearning/check_member_no_ajax') ?>",
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
									//refresh_captcha_img();
                  /* $("#val1").val(data.val1_hidden);
                  $("#val2").val(data.val2_hidden);
                  $("#val3").val('');
                  $("#captcha_input_val1").html(data.val1_hidden);
                  $("#captcha_input_val2").html(data.val2_hidden); */
                }
                
                $.validator.messages.custom_check_member_no_ajax = data.response;
              }
            });
            
            return isSuccess;
          }
        }, '');
		
				$("#usersAddForm").validate( 
				{
          onkeyup: function(element) 
          {
            $(element).valid();
          },   
					rules:
					{
						member_no: { required : true, custom_check_member_no_ajax:true /* , remote: { url: "<?php echo site_url('ApplyElearning/check_member_no_ajax') ?>", type: "post", data: { "member_type": function() { return '<?php echo $member_type; ?>'; } } } */ }, 					
						/* val3: { required : true, check_captcha : true }, */  		
						captcha_code: { required : true, remote: { url: "<?php echo site_url('ApplyElearning/check_captcha_code_ajax') ?>", type: "post", data: { "session_name": sessionName } } },  		
					},
					messages:
					{
						member_no: { required : "Please enter Membership/Registration No", <?php if($login_type == "global" || $login_type == 'non_ordinary') { ?>custom_check_member_no_ajax : "Please enter valid Membership/Registration No" <?php } else { ?> custom_check_member_no_ajax : "You are not eligible to enroll for this course"<?php } ?> },
						/* val3: { required : "Please enter code" } */
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
					url: '<?php echo site_url("ApplyElearning/generate_captcha_ajax/"); ?>',
					data: { "session_name":sessionName },
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