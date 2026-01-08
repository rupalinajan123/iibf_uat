<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <h4 class="modal-title">Update Batch No. of Candidate : <?php echo $form_data[0]['batch_code']; ?></h4>
</div>

<form class="modal_form_all" method="post" action="<?php echo site_url('iibfbcbf/agency/training_batches_agency/update_batch_number_of_candidates/'.$enc_batch_id); ?>" id="change_number_of_candidates_form" enctype="multipart/form-data" autocomplete="off">
	<div class="modal-body">
    <?php $chk_under_graduate = $form_data[0]['under_graduate_candidates']; ?>
    <div class="form-group row"><?php // Under Graduate Candidates  ?>
      <label class="col-xl-3 col-lg-3" for="under_graduate_candidates" class="form_label">Under Graduate <sup class="text-danger">*</sup></label>
      <div class="col-xl-9 col-lg-9">
        <div class="form-group">
          <select name="under_graduate_candidates" id="under_graduate_candidates" class="form-control chosen-selectt" required onchange="validate_input('under_graduate_candidates'); calculate_total_candidate();">
            <option value="">Select Under Graduate Candidates</option>
            <?php for ($i = 0; $i <= 35; $i++) { ?>
              <option value="<?php echo $i; ?>" <?php if($chk_under_graduate != "" && $chk_under_graduate == $i) { echo 'selected'; } ?>><?php echo $i; ?></option>
            <?php } ?>
          </select>
          <span id="under_graduate_candidates_err"></span>
          <?php if(form_error('under_graduate_candidates')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('under_graduate_candidates'); ?></label> <?php } ?>
        </div>					
      </div>
    </div>
        
    <?php $chk_graduate = $form_data[0]['graduate_candidates']; ?>
    <div class="form-group row"><?php // Graduate Candidates  ?>
      <label class="col-xl-3 col-lg-3" for="graduate_candidates" class="form_label">Graduate<sup class="text-danger">*</sup></label>
      <div class="col-xl-9 col-lg-9">
        <div class="form-group">
          <select name="graduate_candidates" id="graduate_candidates" class="form-control chosen-selectt" required onchange="validate_input('graduate_candidates'); calculate_total_candidate();">
            <option value="">Select Graduate Candidates</option>
            <?php for ($i = 0; $i <= 35; $i++) { ?>
              <option value="<?php echo $i; ?>" <?php if($chk_graduate != "" && $chk_graduate == $i) { echo 'selected'; } ?>><?php echo $i; ?></option>
            <?php } ?>
          </select>
          <span id="graduate_candidatess_err"></span>
          <?php if(form_error('graduate_candidates')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('graduate_candidates'); ?></label> <?php } ?>
        </div>					
      </div>
    </div>
        
    <?php $chk_post_graduate = $form_data[0]['post_graduate_candidates']; ?>
    <div class="form-group row"><?php // Post Graduate Candidates  ?>
      <label class="col-xl-3 col-lg-3" for="post_graduate_candidates" class="form_label">Post Graduate<sup class="text-danger">*</sup></label>
      <div class="col-xl-9 col-lg-9">
        <div class="form-group login_password_common">
          <select name="post_graduate_candidates" id="post_graduate_candidates" class="form-control chosen-selectt" required onchange="validate_input('post_graduate_candidates'); calculate_total_candidate();">
            <option value="">Select Post Graduate Candidates</option>
            <?php for ($i = 0; $i <= 35; $i++) { ?>
              <option value="<?php echo $i; ?>" <?php if($chk_post_graduate != "" && $chk_post_graduate == $i) { echo 'selected'; } ?>><?php echo $i; ?></option>
            <?php } ?>
          </select>
          <span id="post_graduate_candidates_err"></span>
          <?php if(form_error('post_graduate_candidates')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('post_graduate_candidates'); ?></label> <?php } ?>
        </div>					
      </div>
    </div>
        
    <div class="form-group row"><?php /* Total Candidates */ ?>
      <label class="col-xl-3 col-lg-3" for="total_candidates" class="form_label">Total Candidates <sup class="text-danger">*</sup></label>
      <div class="col-xl-9 col-lg-9">
        <div class="form-group login_password_common">
          <input type="text" id="total_candidates" name="total_candidates" value="<?php echo $form_data[0]['total_candidates']; ?>" placeholder="Total Candidates *" class="form-control custom_input" readonly />
          
          <?php if(form_error('total_candidates')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('total_candidates'); ?></label> <?php } ?>
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
  //START : CALCULATE TOTAL CANDIDATE VALUE
  function calculate_total_candidate(is_validate='')
  {
    let under_graduate_candidates = $("#under_graduate_candidates").val();
    let graduate_candidates = $("#graduate_candidates").val();
    let post_graduate_candidates = $("#post_graduate_candidates").val();
    let total_candidate = 0;
    
    if(under_graduate_candidates != "" && $.isNumeric(under_graduate_candidates)) { total_candidate = parseInt(total_candidate) + parseInt(under_graduate_candidates); }
    if(graduate_candidates != "" && $.isNumeric(graduate_candidates)) { total_candidate = parseInt(total_candidate) + parseInt(graduate_candidates); }
    if(post_graduate_candidates != "" && $.isNumeric(post_graduate_candidates)) { total_candidate = parseInt(total_candidate) + parseInt(post_graduate_candidates); }
    
    if($.isNumeric(total_candidate)) 
    { 
      $("#total_candidates").val(total_candidate);
      
      if(is_validate == '') { validate_input('total_candidates'); }
    }
    else { $("#total_candidates").val(""); }
  }//END : CALCULATE TOTAL CANDIDATE VALUE
  calculate_total_candidate('0');
  
  function validate_input(input_id) { $("#"+input_id).valid(); }
	$(document ).ready( function() 
	{
    $.validator.addMethod("check_calculated_readonly_values", function(value, element)
    {
      if($.trim(value).length == 0) { return true; }
      else
      {
        var current_id = element.id;
        var current_val = $.trim(value);
        
        if(current_id == 'under_graduate_candidates')
        { 
          var chk_batch_total_ug_candidates = "<?php echo $batch_total_ug_candidates; ?>";     
          if(parseInt(current_val) < parseInt(chk_batch_total_ug_candidates))
          {
            $.validator.messages.check_calculated_readonly_values = "You have already added "+chk_batch_total_ug_candidates+" under graduate candidates in this batch. So please select the value equal or higer than "+chk_batch_total_ug_candidates;
            return false;
          }
          else { return true; }
        }
        else if(current_id == 'graduate_candidates')
        { 
          var chk_batch_total_g_candidates = "<?php echo $batch_total_g_candidates; ?>";     
          if(parseInt(current_val) < parseInt(chk_batch_total_g_candidates))
          {
            $.validator.messages.check_calculated_readonly_values = "You have already added "+chk_batch_total_g_candidates+" graduate candidates in this batch.";
            return false;
          }
          else { return true; }
        }
        else if(current_id == 'post_graduate_candidates')
        { 
          var chk_batch_total_pg_candidates = "<?php echo $batch_total_pg_candidates; ?>";     
          if(parseInt(current_val) < parseInt(chk_batch_total_pg_candidates))
          {
            $.validator.messages.check_calculated_readonly_values = "You have already added "+chk_batch_total_pg_candidates+" post graduate candidates in this batch.";
            return false;
          }
          else { return true; }
        }
        else if(current_id == 'total_candidates')
        { 
          var chk_total_batch_candidates = "<?php echo $chk_total_batch_candidates; ?>";     
          if(parseInt(current_val) == 0)
          {
            $.validator.messages.check_calculated_readonly_values = "Total Candidates should be more than or equal to 1";
            return false;
          }
          else if(parseInt(current_val) > parseInt(chk_total_batch_candidates))
          {
            $.validator.messages.check_calculated_readonly_values = "Total Candidates should be less than or equal to "+chk_total_batch_candidates;
            return false;
          }
          else { return true; }
        }
      }
    }); 
    
		$("#change_number_of_candidates_form").validate( 
		{
      onkeyup: function(element) { $(element).valid(); },
			rules:
			{
				under_graduate_candidates:{ required:true, check_calculated_readonly_values:true },  
        graduate_candidates:{ required:true, check_calculated_readonly_values:true },  
        post_graduate_candidates:{ required:true, check_calculated_readonly_values:true },  
        total_candidates:{ allow_only_numbers:true, check_calculated_readonly_values:true }, 
			},
			messages:
			{
				under_graduate_candidates: { required: "Please select the under graduate candidates" },
        graduate_candidates: { required: "Please select the graduate candidates" },
        post_graduate_candidates: { required: "Please select the post graduate candidates" },
        total_candidates: {  },
			},      
			submitHandler: function(form) 
			{
        var chk_under_graduate = "<?php echo $chk_under_graduate; ?>";
        var chk_graduate = "<?php echo $chk_graduate; ?>";
        var chk_post_graduate = "<?php echo $chk_post_graduate; ?>";

        var current_under_graduate = $("#under_graduate_candidates").val();
        var current_graduate = $("#graduate_candidates").val();
        var current_post_graduate = $("#post_graduate_candidates").val();

        if(chk_under_graduate == current_under_graduate && chk_graduate == current_graduate && chk_post_graduate == current_post_graduate)
        {
          sweet_alert_only_alert("Please change at least one value to update the details");
        }
        else
        {
          swal({ title: "Please confirm", text: "Please confirm to update the details", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
          { 
            $("#page_loader").show();
            $("#submit_btn_outer").html('<button class="btn btn-primary" type="button" style="cursor:wait" value="submit">Submit <i class="fa fa-spinner" aria-hidden="true"></i></button> <a class="btn btn-danger" href="<?php echo site_url("iibfbcbf/agency/training_batches_agency"); ?>">Back</a>');
            form.submit();
          });
        }
			}
		});
	});
</script>