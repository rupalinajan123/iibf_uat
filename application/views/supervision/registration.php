<!DOCTYPE html>
<html>
  <head>
    <?php $this->load->view('supervision/inc_header'); ?>
  </head>
  
  <body class="gray-bg">
    <?php $this->load->view('supervision/common/inc_loader'); ?>
    <div class="d-flex logo" style="z-index:1;">
        <img src="<?php echo base_url('assets/supervision/images/iibf_logo.png'); ?>" class="img-fluid" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">   <h3 class="mb-0">INDIAN INSTITUTE OF BANKING & FINANCE - Supervision Observer Registration</h3>
    </div>
    <div class="container">        
  
      
      <div class="admin_login_form animated fadeInDown" style="width: 100%; max-width: none; margin-top:110px"> 
        <form method="post" action="<?php echo site_url('supervision/registration'); ?>" id="add_form" enctype="multipart/form-data" autocomplete="off">
          <h3 class="text-center mb-3"><b>Exam Supervision Registration</b></h3>
          <div class="hr-line-dashed mb-4"></div>
          
          <div class="row">
            <div class="col-xl-6 col-lg-6"><?php /*  Name */ ?>
              <div class="form-group">
                <label for="candidate_name" class="form_label">Name <sup class="text-danger">*</sup></label>
                <input type="text" name="candidate_name" id="candidate_name" value="<?php echo set_value('candidate_name'); ?>" placeholder="Name *" class="form-control custom_input allow_only_alphabets_and_space" maxlength="90" required/>
                <note class="form_note" id="candidate_name_err">Note: Please enter only 90 characters</note>
                
                <?php if(form_error('candidate_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('candidate_name'); ?></label> <?php } ?>
              </div>					
            </div>
            
           
            <div class="col-xl-6 col-lg-6"><?php /* Email */ ?>
              <div class="form-group">
                <label for="email" class="form_label">Email <sup class="text-danger">*</sup></label>
                <input type="email" name="email" id="email" placeholder="Email*" class="form-control custom_input" maxlength="75" required value="<?php echo set_value('email'); ?>" />
                
                <note class="form_note" id="email_err">Note: Please enter only 75 characters</note>
                
                <?php if(form_error('email')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('email'); ?></label> <?php } ?>
              </div>					
            </div>
            
            <div class="col-xl-6 col-lg-6"><?php /* Mobile */ ?>
              <div class="form-group">
                <label for="mobile" class="form_label">Mobile <sup class="text-danger">*</sup></label>
                <input type="text" name="mobile" id="mobile" placeholder="Mobile*" required class="form-control custom_input allow_only_numbers" maxlength="10" minlength="10"  value="<?php echo set_value('mobile'); ?>" />
                
                <note class="form_note" id="mobile_err">Note: Please enter only 10 characters</note>
                
                <?php if(form_error('mobile')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('mobile'); ?></label> <?php } ?>
              </div>					
            </div>
            
            <div class="col-xl-6 col-lg-6"><?php /* Bank Name */ ?>
              <div class="form-group">
                <label for="bank" class="form_label">Bank Name <sup class="text-danger">*</sup></label>
                <input type="text" name="bank" id="bank" placeholder="Bank Name*" required class="form-control custom_input" maxlength="100" value="<?php echo set_value('bank'); ?>" />
                
                <note class="form_note" id="bank_err">Note: Please enter only 100 characters</note>
                
                <?php if(form_error('bank')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('bank'); ?></label> <?php } ?>
              </div>					
            </div>
            
            <div class="col-xl-6 col-lg-6"><?php /* Branch */ ?>
              <div class="form-group">
                <label for="branch" class="form_label">Branch <sup class="text-danger">*</sup></label>
                <input type="text" name="branch" id="branch" required placeholder="Branch*" class="form-control custom_input" maxlength="100" value="<?php echo set_value('branch'); ?>" />
                
                <note class="form_note" id="branch_err">Note: Please enter only 100 characters</note>
                
                <?php if(form_error('branch')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('branch'); ?></label> <?php } ?>
              </div>					
            </div>
            <div class="col-xl-6 col-lg-6"><?php /* Designation */ ?>
              <div class="form-group">
                <label for="designation" class="form_label">Designation <sup class="text-danger">*</sup></label>
                <input type="text" name="designation" id="designation" value="<?php echo set_value('designation'); ?>" placeholder="Designation *" class="form-control custom_input allow_only_alphabets_and_space" maxlength="30" required/>
                <note class="form_note" id="designation_err">Note: Please enter only 30 characters</note>
                
                <?php if(form_error('designation')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('designation'); ?></label> <?php } ?>
              </div>					
            </div>
            

            <!--<div class="col-xl-6 col-lg-6"><?php /* Center */ ?>
              <div class="form-group">
                <label for="center" class="form_label">Center <sup class="text-danger">*</sup></label>
                <?php $chk_center = set_value('center'); ?>
                <select name="center" id="center" class="form-control chosen-select select_center" required 
                onchange=" validate_input('pdc_zone'); ">
                  <?php if(count($center_master_data) > 0)
                    { ?>
                    <option value="">Select  *</option>
                    <?php foreach($center_master_data as $center_res)
                      { ?>
                      <option pdc_zone_name="<?php echo $center_res['pdc_zone_name']; ?>" pdc_zone="<?php echo $center_res['pdc_zone']; ?>" value="<?php echo $center_res['center_code']; ?>" <?php if($chk_center == $center_res['center_code']) { echo 'selected'; } ?>><?php echo $center_res['center_name']; ?></option>
                      <?php }
                    }
                    else 
                    { ?>
                    <option value="">No list Available</option>
                  <?php } ?>
                </select>
                
                <?php if(form_error('center')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('center'); ?></label> <?php } ?>
              </div>					
            </div>
                    -->
            <?php $chk_pdc_zone = set_value('pdc_zone'); ?>
            <div class="col-xl-6 col-lg-6"><?php /* Select PDC Zone */ ?>
              <div class="form-group ">
                <label for="pdc_zone" class="form_label">PDC Zone <sup class="text-danger">*</sup></label>
                <div class="pdc_zone_outer">
                      <select name="pdc_zone" id="pdc_zone" readonly class="form-control chosen-select select_pdc_zone"  >
                      <?php if(count($pdc_zone_master_data) > 0)
                    { ?>
                    <option value="">Select  *</option>
                    <?php foreach($pdc_zone_master_data as $pdc_zone_res)
                      { ?>
                      <option value="<?php echo $pdc_zone_res['pdc_zone_code']; ?>" <?php if($chk_pdc_zone == $pdc_zone_res['pdc_zone_code']) { echo 'selected'; } ?>><?php echo $pdc_zone_res['pdc_zone_name']; ?></option>
                      <?php }
                    }
                    else 
                    { ?>
                    <option value="">No list Available</option>
                  <?php } ?>
                        <option value="">No list Available</option>
                      </select>
                </div>
                <span id="pdc_zone_err"></span>
                <?php if(form_error('pdc_zone')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('pdc_zone'); ?></label> <?php } ?>
              </div>					
            </div>
            <div class="col-xl-6 col-lg-6"><?php /* Bank ID Card */ ?>
              <div class="form-group">
                <label for="bank_id_card" class="form_label">Bank ID Card <sup class="text-danger">*</sup></label>
                <input type="file" name="bank_id_card" id="bank_id_card" value="<?php echo set_value('bank_id_card'); ?>" placeholder="Bank ID Card *" class="form-control  " onchange="validateFile(event, 'error_bank_id_card_size', 'image_upload_bank_id_card_preview', '300kb')" required maxlength="255" minlength="5" />
                <note class="form_note" id="designation_err">Note: Please upload JPEG image with 300kb in size</note>
                <?php if(form_error('bank_id_card')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('bank_id_card'); ?></label> <?php } ?>
              </div>					
            </div>
          
            <div class="col-xl-6 col-lg-6"><?php /* Password */ ?>
              <div class="form-group login_password_common">
                <label for="password" class="form_label">Password <sup class="text-danger">*</sup></label>
                <input type="password" class="form-control custom_input" name="password" id="password" value="<?php echo set_value('password'); ?>" placeholder="Password *" required minlength="8" maxlength="20">
                <note class="form_note" id="password_err">Note: Please enter minimum 8 and maximum 20 characters</note>
                
                <?php if(form_error('password')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('password'); ?></label> <?php } ?>
                
                <span class="show-password" onclick="show_hide_password(this,'show', 'password')" style="top:28px;"><i class="fa fa-eye" aria-hidden="true"></i></span>
                <span class="hide-password" onclick="show_hide_password(this,'hide', 'password')" style="display:none;top:28px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
              </div>
            </div>
            
            <div class="col-xl-6 col-lg-6"><?php /* Confirm Password */ ?>
              <div class="form-group login_password_common">
                <label for="confirm_password" class="form_label">Confirm Password <sup class="text-danger">*</sup></label>
                <input type="password" class="form-control custom_input" name="confirm_password" id="confirm_password" value="<?php echo set_value('confirm_password'); ?>" placeholder="Confirm Password *" required minlength="8" maxlength="20">
                <?php if(form_error('confirm_password')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('confirm_password'); ?></label> <?php } ?>
                
                <span class="show-password" onclick="show_hide_password(this,'show', 'confirm_password')" style="top:28px;"><i class="fa fa-eye" aria-hidden="true"></i></span>
                <span class="hide-password" onclick="show_hide_password(this,'hide', 'confirm_password')" style="display:none;top:28px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
              </div>
            </div>
            
            
              <div class="col-xl-6 col-lg-6"><?php /* Code */ ?>
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-6">
                      <label for="iibf_supervision_registration_captcha" class="form_label">Code <sup class="text-danger">*</sup></label>
                      <input type="text" name="iibf_supervision_registration_captcha" id="iibf_supervision_registration_captcha" class="form-control" placeholder="Code *" required autocomplete="off" value="" />
                      <?php if (form_error('iibf_supervision_registration_captcha') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('iibf_supervision_registration_captcha'); ?></label> <?php } ?>
                    </div>
                    <div class="col-md-6">
                      <label class="form_label">&nbsp;</label>
                      <div class="captcha_common">
                        <div class="image">
                          <?php echo $captcha_img; ?>
                        </div>
                        <a href="javascript:void(0)" onclick="refresh_captcha()" class="refresh_captcha_link"><i class="fa fa-refresh"></i></a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
           
          </div>
          
          <div class="hr-line-dashed"></div>										
          <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="submit_btn_outer">
              <button class="btn btn-primary" type="submit">Submit</button>	
            </div>
          </div>
        </form>
      </div>
    </div>
    
    
    <?php $this->load->view('supervision/inc_footer'); ?>
        
    <?php $this->load->view('supervision/common/inc_common_validation_all'); ?>
    <?php $this->load->view('supervision/common/inc_common_show_hide_password'); ?>
    
    <script type="text/javascript">

    $('select.select_center').change(function() {
      pdc_zone_func();
    });
    function pdc_zone_func() {
      var pdc_zone = $('select.select_center option:selected').attr('pdc_zone');
      var pdc_zone_name = $('select.select_center option:selected').attr('pdc_zone_name');
      $('.pdc_zone_outer').html('<select name="pdc_zone" id="pdc_zone" class="form-control chosen-select select_pdc_zone" readonly  ><option selected value="'+pdc_zone+'">'+pdc_zone_name+'</option></select>');
     
      $('.select_pdc_zone option[value="'+pdc_zone+'"]').prop('selected', true);

    }

      function refresh_captcha() 
      {
        $("#page_loader").css("display", "block");
        $("#iibf_supervision_registration_captcha").val("");
        $.ajax({
          type: "POST",
          url: "<?php echo site_url('supervision/registration/refresh_captcha'); ?>",
          success: function(res) 
          {
            if (res) 
            {
              jQuery("div.image").html(res);
              $("#page_loader").css("display", "none");
            }
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
         
            console.log('AJAX request failed: ' + errorThrown);
            sweet_alert_error("Error occurred. Please try again.");
            $("#page_loader").hide();
            
          }
        });
      } 
      
      //START : JQUERY VALIDATION SCRIPT 
      function validate_input(input_id) { $("#"+input_id).valid(); }
      $(document ).ready( function() 
      {
        var form = $("#add_form").validate( 
        {
          onkeyup: function(element) { $(element).valid(); },          
          rules:
          {
            candidate_name:{ required: true, allow_only_alphabets_and_space:true, maxlength:90 },
           
            email:{ required: true, maxlength:75 },
            bank_id_card:{ maxlength:75 },
            bank:{ maxlength:100 },
            branch:{ maxlength:100 },
            pdc_zone:{ required: true },  
            designation:{ required: true, allow_only_alphabets_and_space:true, maxlength:30 }, 
           
            mobile:{ required: true, allow_only_numbers:true, first_zero_not_allowed:true, maxlength:10, minlength:10, remote: { url: "<?php echo site_url('supervision/registration/validation_check_mobile_exist/0/1'); ?>", type: "post" } },            
              
            password: { required: true, minlength: 8, maxlength:20, pwcheck: true },
            confirm_password: { required: true, equalTo: "#password" },
            iibf_supervision_registration_captcha: { required: true, remote: { url: "<?php echo site_url('supervision/registration/validation_check_captcha/0/1'); ?>", type: "post" } }            
          },
          messages:
          {
            candidate_name: { required: "Please enter the Full name" },
            
            email: { required: "Please enter the Email ID", valid_email: "Please enter the valid email id", remote: "The email id is already exist" },
            bank: { },
            branch: { },
            pdc_zone: { required: "Please select the PDC Zone" },
            designation: { required: "Please enter the designation" },
            mobile: { required: "Please enter the mobile number", minlength: "Please enter 10 numbers in mobile number", maxlength: "Please enter 10 numbers in mobile number", remote: "The mobile number is already exist" },
            
            bank_id_card: { required: "Please upload Bank ID card" },
            
            password: { required: "Please enter the password", minlength: "Please enter minimum 8 character", pwcheck:"Password must contain at least one upper-case character, one lower-case character, one digit and one special character" },					
            confirm_password: { required: "Please enter the Confirm Password", equalTo:"Please enter confirm password same as password" },
            iibf_supervision_registration_captcha: { required: "Please enter the code", remote: "Please enter the valid code" }
          }, 
          errorPlacement: function(error, element) // For replace error 
          {
            if (element.attr("name") == "candidate_name") { error.insertAfter("#candidate_name_err"); }
            else if (element.attr("name") == "email") { error.insertAfter("#email_err"); }
            else if (element.attr("name") == "mobile") { error.insertAfter("#mobile_err"); }
            else if (element.attr("name") == "bank") { error.insertAfter("#bank_err"); }
            else if (element.attr("name") == "branch") { error.insertAfter("#branch_err"); }
            else if (element.attr("name") == "pdc_zone") { error.insertAfter("#pdc_zone_err"); }
            else if (element.attr("name") == "designation") { error.insertAfter("#designation_err"); }
            else if (element.attr("name") == "password") { error.insertAfter("#password_err"); }
            else if (element.attr("name") == "is_active") { error.insertAfter("#is_active_err"); }
            else { error.insertAfter(element); }
          },          
          submitHandler: function(form) 
          {          
            $("#page_loader").hide();
            swal({ title: "Please confirm", text: "Please confirm to submit the details?", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
            { 
              $("#page_loader").show();            
              $("#submit_btn_outer").html('<button class="btn btn-primary" type="button" style="cursor:wait">Submit</button> <a class="btn btn-danger" href="<?php echo site_url('supervision/admin/agency'); ?>">Back</a>');
            
              form.submit();
            }); 
          }
        });
      });
      function validateFile(event, error_id, show_img_id, size, img_width, img_height) {
  var srcid = event.srcElement.id;
  if (document.getElementById(srcid).files.length != 0) {
    var file = document.getElementById(srcid).files[0];

    if (file.size == 0) {
      $('#' + error_id).text('Please select valid file');
      $('#' + document.getElementById(srcid).id).val('')
      $('#' + show_img_id).attr('src', "/assets/images/default1.png");
    }
    else {
      var file_size = document.getElementById(srcid).files[0].size / 1024;
      var mimeType = document.getElementById(srcid).files[0].type;

      var allowedFiles = [".jpg", ".jpeg"];
      if ($('#' + document.getElementById(srcid).id + '_allowedFilesTypes').text() != "") {
        var allowedFiles = $('#' + document.getElementById(srcid).id + '_allowedFilesTypes').text().split(",");
      }
      var regex = new RegExp("([a-zA-Z0-9\s_\\((\d+)\)\.\-:])+(" + allowedFiles.join('|') + ")$");

      var reader = new FileReader();

      var check_size = '';

      if (size.indexOf('kb') !== -1) {
        var check_size = size.split('k');
      }
      if (size.indexOf('mb') !== -1) {
        var check_size = size.split('m');
      }

      reader.onload = function (e) {
        var img = new Image();
        img.src = e.target.result;

        if (reader.result == 'data:') {
          $('#' + error_id).text('This file is corrupted');
          $('#' + document.getElementById(srcid).id).val('')
          $('#' + show_img_id).attr('src', "/assets/images/default1.png");
        }
        else {
        
          if (!regex.test(file.name.toLowerCase())) {
            $('#' + error_id).text("Please upload " + allowedFiles.join(', ') + " only.");
            $('#' + document.getElementById(srcid).id).val('')
            $('#' + show_img_id).attr('src', "/assets/images/default1.png");
          }
          else {
            if (file_size > check_size[0]) {
            
              $('#' + error_id).text("Please upload file less than " + size);
              $('#' + document.getElementById(srcid).id).val('')
              $('#' + show_img_id).attr('src', "/assets/images/default1.png");
            }
            else if (file_size < 8) //IF FILE SIZE IS LESS THAN 8KB
            {
              $('#' + error_id).text("Please upload file having size more than 8KB");
              $('#' + document.getElementById(srcid).id).val('')
              $('#' + show_img_id).attr('src', "/assets/images/default1.png");
            }
            else {
              img.onload = function () {
                var width = this.width;
                var height = this.height;

                if (width > img_width && height > img_height) {
                  $('#' + error_id).text(' Uploaded File dimensions are ' + width + '*' + height + ' pixel. Please Upload file dimensions between ' + img_width + '*' + img_height + ' pixel');
                  $('#' + document.getElementById(srcid).id).val('')
                  $('#' + show_img_id).attr('src', "/assets/images/default1.png");
                }
                else {
                  //console.log('else');
                  $('#' + error_id).text("");
                  $('.btn_submit').attr('disabled', false);
                  $('#' + show_img_id).attr('src', '');
                  $('#' + show_img_id).removeAttr('src');
                  $('#' + show_img_id).attr('src', reader.result);

                  var img = new Image();
                  img.src = reader.result;

                }
              }

            }
          }
        }
      }

      reader.readAsDataURL(event.target.files[0]);
    }
  }
  else {
    $('#' + error_id).text('Please select file');
    
    $('#' + document.getElementById(srcid).id).val('')
    $('#' + show_img_id).attr('src', "/assets/images/default1.png");
  }
}

    </script>
    <?php $this->load->view('supervision/common/inc_bottom_script'); ?>
  </body>
</html>