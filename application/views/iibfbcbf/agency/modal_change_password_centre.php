<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <h4 class="modal-title">Change Password : <?php echo $form_data[0]['centre_name']; ?></h4>
</div>

<form class="modal_form_all" method="post" action="<?php echo site_url('iibfbcbf/agency/centre_master_agency/change_password/'.$enc_centre_id); ?>" id="change_pass_form" enctype="multipart/form-data" autocomplete="off">
	<div class="modal-body">
		<div class="form-group row">
      <label class="col-xl-3 col-lg-3" for="centre_password" class="form_label">New Password <sup class="text-danger">*</sup></label>
      <div class="col-xl-9 col-lg-9">
        <div class="form-group login_password_common">
          <input type="password" class="form-control custom_input" name="centre_password" id="centre_password" value="<?php echo set_value('centre_password'); ?>" placeholder="New Password *" required minlength="8" maxlength="20">
          <note class="form_note" id="centre_password_err">Note: Please enter minimum 8 and maximum 20 characters</note>

          <?php if(form_error('centre_password')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('centre_password'); ?></label> <?php } ?>
        
          <span class="show-password" onclick="show_hide_password(this,'show', 'centre_password')" style="top:8px;"><i class="fa fa-eye" aria-hidden="true"></i></span>
          <span class="hide-password" onclick="show_hide_password(this,'hide', 'centre_password')" style="display:none;top:8px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
        </div>
      </div>
    </div>

    <div class="form-group row">      
      <label class="col-xl-3 col-lg-3" for="confirm_password" class="form_label">Confirm Password <sup class="text-danger">*</sup></label>
      <div class="col-xl-9 col-lg-9">
        <div class="form-group login_password_common">
          <input type="password" class="form-control custom_input" name="confirm_password" id="confirm_password" value="<?php echo set_value('confirm_password'); ?>" placeholder="Confirm Password *" required minlength="8" maxlength="20">
          <?php if(form_error('confirm_password')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('confirm_password'); ?></label> <?php } ?>
          
          <span class="show-password" onclick="show_hide_password(this,'show', 'confirm_password')" style="top:8px;"><i class="fa fa-eye" aria-hidden="true"></i></span>
          <span class="hide-password" onclick="show_hide_password(this,'hide', 'confirm_password')" style="display:none;top:8px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
        </div>
      </div>
    </div>
    
	</div>
	
	<div class="modal-footer" id="submit_btn_outer">			
		<button type="submit" value="submit" class="btn btn-primary">Submit</button>
		<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
	</div>
</form>

<?php $this->load->view('iibfbcbf/common/inc_common_show_hide_password'); ?>
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
				centre_password: { required: true, minlength: 8, maxlength:20, pwcheck: true, remote: { url: "<?php echo site_url('iibfbcbf/agency/centre_master_agency/check_old_password'); ?>", type: "post", data: { "enc_centre_id": function() { return "<?php echo $enc_centre_id; ?>"; } } } },
        confirm_password: { required: true, equalTo: "#centre_password" },
			},
			messages:
			{
				centre_password: { required: "Please enter the new password", minlength: "Please enter minimum 8 character", pwcheck:"Password must contain at least one upper-case character, one lower-case character, one digit and one special character", remote:"New password can not be same as current password" },					
        confirm_password: { required: "Please enter the Confirm Password", equalTo:"Please enter confirm password same as new password" },
			}, 
      errorPlacement: function(error, element) // For replace error 
      {
        if (element.attr("name") == "centre_password") { error.insertAfter("#centre_password_err"); }
        else { error.insertAfter(element); }
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
	});
</script>