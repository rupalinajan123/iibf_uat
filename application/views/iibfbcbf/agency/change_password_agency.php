<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('iibfbcbf/inc_header'); ?>    
	</head>
	<body class="fixed-sidebar">
		<?php $this->load->view('iibfbcbf/common/inc_loader'); ?>
		
		<div id="wrapper">
			<?php $this->load->view('iibfbcbf/agency/inc_sidebar_agency'); ?>		
			<div id="page-wrapper" class="gray-bg">				
				<?php $this->load->view('iibfbcbf/agency/inc_topbar_agency'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>Change Password</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency'); ?>">Dashboard</a></li>
              <li class="breadcrumb-item">Profile Settings</li>
							<li class="breadcrumb-item active"> <strong>Change Password</strong></li>
						</ol>
					</div>
					<div class="col-lg-2"> </div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
				  <div class="bcbf_wrap">						
						<div class="half-circle"></div>
						<div class="row justify-content-centre">							
							<div class="ibox admin_login_form">
								<div class="text-center"><i class="fa fa-key bcbf-lock" aria-hidden="true"></i><p>Reset Your Password!</P></div>
								<div class="ibox-content border-0">									
									<form method="post" action="<?php echo site_url('iibfbcbf/agency/dashboard_agency/change_password'); ?>" id="change_pass_form" class="admin_form_all" enctype="multipart/form-data" autocomplete="off">
										<div class="row justify-content-centre">											
                      <div class="col-xl-12 col-lg-12">
												<div class="form-group login_password_common">
													<label for="current_pass_agency" class="form_label">Current Password <sup class="text-danger">*</sup></label>
													<input type="password" class="form-control custom_input" name="current_pass_agency" id="current_pass_agency" value="<?php echo set_value('current_pass_agency'); ?>" placeholder="Current Password" autofocus required minlength="8" maxlength="20" onchange="validate_input('current_pass_agency');" onblur="validate_input('current_pass_agency');" onfocusin="validate_input('current_pass_agency');">
													<?php if(form_error('current_pass_agency')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('current_pass_agency'); ?></label> <?php } ?>
                          
                          <span class="show-password" data-id="current_pass_agency" style="top:28px;"><i class="fa fa-eye" aria-hidden="true"></i></span>
                          <span class="hide-password" data-id="current_pass_agency" style="display:none;top:28px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
												</div>
											</div>
											<div class="col-xl-12 col-lg-12">
												<div class="form-group login_password_common">
													<label for="new_pass_agency" class="form_label">New Password <sup class="text-danger">*</sup></label>
													<input type="password" class="form-control custom_input" name="new_pass_agency" id="new_pass_agency" value="<?php echo set_value('new_pass_agency'); ?>" placeholder="New Password" required minlength="8" maxlength="20" onchange="validate_input('new_pass_agency');" onblur="validate_input('new_pass_agency');" onfocusin="validate_input('new_pass_agency');">
													<?php if(form_error('new_pass_agency')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('new_pass_agency'); ?></label> <?php } ?>
                          
                          <span class="show-password" data-id="new_pass_agency" style="top:28px;"><i class="fa fa-eye" aria-hidden="true"></i></span>
                          <span class="hide-password" data-id="new_pass_agency" style="display:none;top:28px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
												</div>
											</div>
											<div class="col-xl-12 col-lg-12">
												<div class="form-group login_password_common">
													<label for="confirm_pass_agency" class="form_label">Confirm Password <sup class="text-danger">*</sup></label>
													<input type="password" class="form-control custom_input" name="confirm_pass_agency" id="confirm_pass_agency" value="<?php echo set_value('confirm_pass_agency'); ?>" placeholder="Confirm Password" required minlength="8" maxlength="20" onchange="validate_input('confirm_pass_agency');" onblur="validate_input('confirm_pass_agency');" onfocusin="validate_input('confirm_pass_agency');">
													<?php if(form_error('confirm_pass_agency')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('confirm_pass_agency'); ?></label> <?php } ?>
                          
                          <span class="show-password" data-id="confirm_pass_agency" style="top:28px;"><i class="fa fa-eye" aria-hidden="true"></i></span>
                          <span class="hide-password" data-id="confirm_pass_agency" style="display:none;top:28px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
												</div>
											</div>
											<div class="hr-line-dashed mt-1"></div>										
											<div class="col-xl-12 col-lg-12">
												<div class="d-flex justify-content-between" id="submit_btn_outer">	
													<button class="btn btn-submit" type="submit" value="submit">Update Password </button>
													<button class="btn btn-submit" type="button" value="reset" onclick="reset_form()">Reset </button>												
												</div>
											</div>
										</div>										
									</div>
								</form>
							</div>               
						</div>						
						
						<div id="common_log_outer"></div>
					</div>
				</div>
			</div>				
			
			<?php $this->load->view('iibfbcbf/agency/inc_footerbar_agency'); ?>	
			
		</div>
		
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>		
		<?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>
		
    <?php 
      $this->load->view('iibfbcbf/common/get_logs_common_ajax_call', array('enc_pk_id'=>url_encode($this->session->userdata('IIBF_BCBF_LOGIN_ID')), 'module_slug'=>$log_slug, 'log_title'=>'Change Password Log'));
		?>
		
		<script type="text/javascript">
      function reset_form()
      {
        $("#change_pass_form")[0].reset();
        $("#change_pass_form").validate().resetForm();
			}
			
      function validate_input(input_id) { $("#"+input_id).valid(); }
			$(document ).ready( function() 
			{
        $("#change_pass_form").validate( 
				{
          onkeyup: function(element) { $(element).valid(); },
					rules:
					{
            current_pass_agency: { required: true, remote: { url: "<?php echo site_url('iibfbcbf/agency/dashboard_agency/validation_check_old_password/0/1') ?>", type: "post" } },
						new_pass_agency: { required: true, minlength: 8, maxlength:20, pwcheck: true, notEqual: "current_pass_agency" },
						confirm_pass_agency: { required: true, equalTo: "#new_pass_agency" }
					},
					messages:
					{
						current_pass_agency: { required: "Please enter the Current Password", remote : "Please enter correct Current Password" },
						new_pass_agency: { required: "Please enter the New Password", minlength: "Please enter minimum 8 character", pwcheck:"Password must contain at least one upper-case character, one lower-case character, one digit and one special character", notEqual:"New password must be different from Current password" },					
						confirm_pass_agency: { required: "Please enter the Confirm Password", equalTo:"Please enter the Confirm Password to match the New Password" }					
					},
					submitHandler: function(form) 
					{
            swal({ title: "Please confirm", text: "Please confirm to change your profile password", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
            { 
              $("#page_loader").show();
              $("#submit_btn_outer").html('<button class="btn btn-primary" type="button" style="cursor:wait" value="submit">Update Password <i class="fa fa-spinner" aria-hidden="true"></i></button>');
              form.submit();
						});
					}
				});
				
        $(".show-password, .hide-password").on('click', function() 
        {
          var passwordId = $(this).data("id");
          if ($(this).hasClass('show-password')) 
          {
            $("#" + passwordId).attr("type", "text");
            $(this).parent().find(".show-password").hide();
            $(this).parent().find(".hide-password").show();
					}
          else 
          {
            $("#" + passwordId).attr("type", "password");
            $(this).parent().find(".hide-password").hide();
            $(this).parent().find(".show-password").show();
					}
				});
			});
		</script>		
		<?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>	
	</body>
</html>