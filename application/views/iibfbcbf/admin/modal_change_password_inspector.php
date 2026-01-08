<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <h4 class="modal-title">Change Password : <?php echo $form_data[0]['inspector_name']; ?></h4>
</div>

<form class="modal_form_all" method="post" action="<?php echo site_url('iibfbcbf/admin/inspector_master/change_password/'.$enc_inspector_id); ?>" id="change_pass_form" enctype="multipart/form-data" autocomplete="off">
	<div class="modal-body">
		<div class="form-group row">
      <label class="col-xl-3 col-lg-3" for="inspector_password" class="form_label">New Password <sup class="text-danger">*</sup></label>
      <div class="col-xl-9 col-lg-9">
        <div class="form-group login_password_common">
          <input type="password" class="form-control custom_input" name="inspector_password" id="inspector_password" value="<?php echo set_value('inspector_password'); ?>" placeholder="New Password *" required minlength="8" maxlength="20">
          <?php if(form_error('inspector_password')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('inspector_password'); ?></label> <?php } ?>
        
          <span class="show-password" data-id="inspector_password" style="top:8px;"><i class="fa fa-eye" aria-hidden="true"></i></span>
          <span class="hide-password" data-id="inspector_password" style="display:none;top:8px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
        </div>
      </div>
    </div>

    <div class="form-group row">      
      <label class="col-xl-3 col-lg-3" for="confirm_password" class="form_label">Confirm Password <sup class="text-danger">*</sup></label>
      <div class="col-xl-9 col-lg-9">
        <div class="form-group login_password_common">
          <input type="password" class="form-control custom_input" name="confirm_password" id="confirm_password" value="<?php echo set_value('confirm_password'); ?>" placeholder="Confirm Password *" required minlength="8" maxlength="20">
          <?php if(form_error('confirm_password')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('confirm_password'); ?></label> <?php } ?>
          
          <span class="show-password" data-id="confirm_password" style="top:8px;"><i class="fa fa-eye" aria-hidden="true"></i></span>
          <span class="hide-password" data-id="confirm_password" style="display:none;top:8px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
        </div>
      </div>
    </div>
    
	</div>
	
	<div class="modal-footer" id="submit_btn_outer">			
		<button type="submit" value="submit" class="btn btn-primary">Submit</button>
		<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
	</div>
</form>

<script type="text/javascript">
  function validate_input(input_id) { $("#"+input_id).valid(); }
	$(document ).ready( function() 
	{
		$("#change_pass_form").validate( 
		{
      onkeyup: function(element) 
      {
        $(element).valid();
      }, 
			rules:
			{
				inspector_password: { required: true, minlength: 8, maxlength:20, pwcheck: true, remote: { url: "<?php echo site_url('iibfbcbf/admin/inspector_master/check_old_password'); ?>", type: "post", data: { "enc_inspector_id": function() { return "<?php echo $enc_inspector_id; ?>"; } } } },
        confirm_password: { required: true, equalTo: "#inspector_password" },
			},
			messages:
			{
				inspector_password: { required: "Please enter the new password", minlength: "Please enter minimum 8 character", pwcheck:"Password must contain at least one upper-case character, one lower-case character, one digit and one special character", remote:"New password can not be same as current password" },					
        confirm_password: { required: "Please enter the Confirm Password", equalTo:"Please enter confirm password same as new password" },
			},
			submitHandler: function(form) 
			{
        swal({ title: "Please confirm", text: "Please confirm to update the password", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
        { 
          $("#page_loader").show();
          $("#submit_btn_outer").html('<button type="button" style="cursor:wait" value="submit" class="btn btn-primary">Submit <i class="fa fa-spinner" aria-hidden="true"></i></button> <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>');
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