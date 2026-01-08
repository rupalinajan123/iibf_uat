<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('apabi_india/inc_header'); ?>    
  </head>
	<body class="fixed-sidebar">
  <?php $this->load->view('apabi_india/inc_loader'); ?>
		
		<div id="wrapper">
    <?php $this->load->view('apabi_india_admin/inc_sidebar_admin'); ?>		
			<div id="page-wrapper" class="gray-bg">				
      <?php $this->load->view('apabi_india_admin/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>Change Password </h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('apabi_india_admin/dashboard_admin'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item active"> <strong>Change Password</strong></li>
						</ol>
					</div>
					<div class="col-lg-2"> </div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-content">
									<form method="post" action="<?php echo site_url('apabi_india_admin/dashboard_admin/change_password'); ?>" id="change_pass_form" class="admin_form_all" enctype="multipart/form-data" autocomplete="off">										
										<div class="row">
                      <div class="col-xl-6 col-lg-12">
												<div class="form-group login_password_common">
													<label for="current_pass_admin" class="form_label">Current Password <sup class="text-danger">*</sup></label>
													<input type="password" class="form-control custom_input" name="current_pass_admin" id="current_pass_admin" value="<?php echo set_value('current_pass_admin'); ?>" placeholder="Current Password" autofocus required minlength="8" maxlength="20" onchange="validate_input('current_pass_admin');" onblur="validate_input('current_pass_admin');" onfocusin="validate_input('current_pass_admin');">
													<?php if(form_error('current_pass_admin')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('current_pass_admin'); ?></label> <?php } ?>
                          
                          <span class="show-password" onclick="show_hide_password(this,'show', 'current_pass_admin')" style="top:28px;"><i class="fa fa-eye" aria-hidden="true"></i></span>
                          <span class="hide-password" onclick="show_hide_password(this,'hide', 'current_pass_admin')" style="display:none;top:28px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
												</div>
											</div>
											<div class="col-xl-6 col-lg-12">
												<div class="form-group login_password_common">
													<label for="new_pass_admin" class="form_label">New Password <sup class="text-danger">*</sup></label>
													<input type="password" class="form-control custom_input" name="new_pass_admin" id="new_pass_admin" value="<?php echo set_value('new_pass_admin'); ?>" placeholder="New Password" required minlength="8" maxlength="20" onchange="validate_input('new_pass_admin');" onblur="validate_input('new_pass_admin');" onfocusin="validate_input('new_pass_admin');">
													<note class="form_note" id="new_pass_admin_err">Note: Please enter minimum 8 and maximum 20 characters</note>
                          
                          <?php if(form_error('new_pass_admin')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('new_pass_admin'); ?></label> <?php } ?>
                          
                          <span class="show-password" onclick="show_hide_password(this,'show', 'new_pass_admin')" style="top:28px;"><i class="fa fa-eye" aria-hidden="true"></i></span>
                          <span class="hide-password" onclick="show_hide_password(this,'hide', 'new_pass_admin')" style="display:none;top:28px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
												</div>
											</div>
											<div class="col-xl-6 col-lg-12">
												<div class="form-group login_password_common">
													<label for="confirm_pass_admin" class="form_label">Confirm Password <sup class="text-danger">*</sup></label>
													<input type="password" class="form-control custom_input" name="confirm_pass_admin" id="confirm_pass_admin" value="<?php echo set_value('confirm_pass_admin'); ?>" placeholder="Confirm Password" required minlength="8" maxlength="20" onchange="validate_input('confirm_pass_admin');" onblur="validate_input('confirm_pass_admin');" onfocusin="validate_input('confirm_pass_admin');">
													<?php if(form_error('confirm_pass_admin')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('confirm_pass_admin'); ?></label> <?php } ?>
                          
                          <span class="show-password" onclick="show_hide_password(this,'show', 'confirm_pass_admin')" style="top:28px;"><i class="fa fa-eye" aria-hidden="true"></i></span>
                          <span class="hide-password" onclick="show_hide_password(this,'hide', 'confirm_pass_admin')" style="display:none;top:28px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
												</div>
											</div>
										</div>
										
										<div class="hr-line-dashed mt-1"></div>										
										<div class="row">
											<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="submit_btn_outer">	
												<button class="btn btn-primary" type="submit" value="submit">Update Password</button>												
                      </div>
										</div>
									</form>
								</div>               
							</div> 

              <div id="common_log_outer"></div>   

						</div>
					</div>
				</div>				
				
				<?php $this->load->view('apabi_india_admin/inc_footerbar_admin'); ?>	
			</div>
		</div>
		<?php $this->load->view('apabi_india/inc_footer'); ?>		

		<?php $this->load->view('apabi_india/inc_common_validation_all'); ?>
    <?php $this->load->view('apabi_india/inc_common_show_hide_password'); ?>

		<script type="text/javascript">
      function validate_input(input_id) { $("#"+input_id).valid(); }
			$(document ).ready( function() 
			{
        $("#change_pass_form").validate( 
				{
          onkeyup: function(element) { $(element).valid(); },
					rules:
					{
            current_pass_admin: { required: true, remote: { url: "<?php echo site_url('apabi_india_admin/dashboard_admin/validation_check_old_password/0/1') ?>", type: "post" } },
						new_pass_admin: { required: true, minlength: 8, maxlength:20, pwcheck: true, notEqual: "current_pass_admin" },
						confirm_pass_admin: { required: true, equalTo: "#new_pass_admin" }
					},
					messages:
					{
						current_pass_admin: { required: "Please enter the Current Password", remote : "Please enter correct Current Password" },
						new_pass_admin: { required: "Please enter the New Password", minlength: "Please enter minimum 8 character", pwcheck:"Password must contain at least one upper-case character, one lower-case character, one digit and one special character", notEqual:"New password must be different from Current password" },					
						confirm_pass_admin: { required: "Please enter the Confirm Password", equalTo:"Please enter the Confirm Password to match the New Password" }					
					},
          errorPlacement: function(error, element) // For replace error 
          {
            if (element.attr("name") == "new_pass_admin") { error.insertAfter("#new_pass_admin_err"); }
            else { error.insertAfter(element); }
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
      });
		</script>		
		<?php $this->load->view('apabi_india/inc_bottom_script'); ?>	
	</body>
</html>