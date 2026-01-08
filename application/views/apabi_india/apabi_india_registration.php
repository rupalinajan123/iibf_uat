<!DOCTYPE html>
<html>
  <head>
    <?php $this->load->view('apabi_india/inc_header'); ?>
    <link href="<?php echo auto_version(base_url('assets/apabi_india/css/apabi_india_registration.css')); ?>" rel="stylesheet">
  </head>
  
  <body class="gray-bg">
    <?php $this->load->view('apabi_india/inc_loader'); ?>
    <?php $this->load->view('apabi_india/inc_apabi_header'); ?>
    
    <div class="container">
      <div class="admin_login_form animated fadeInDown" style="width: 100%; max-width: none; margin-top:40px"> 
        <form method="post" action="<?php echo site_url('apabi_india_registration'); ?>" id="add_form" enctype="multipart/form-data" autocomplete="off">
          <h3 class="text-center mb-3 custom_form_heading"><b>REGISTRATION FORM</b></h3>
                    
          <div class="row">
            <?php 
            $salutation_master_data = array('Mr.','Ms.','Mrs.','Dr.');
            $chk_salutation = set_value('salutation'); ?>
            <div class="col-xl-3 col-lg-3"><?php /* Select salutation */ ?>
              <div class="form-group">
                <label for="salutation" class="form_label">Select Salutation <sup class="text-danger">*</sup></label>
                <select name="salutation" id="salutation" class="form-control chosen-select" required onchange="validate_input('salutation'); ">
                  <?php if(count($salutation_master_data) > 0)
                    { ?>
                    <option value="">Select Salutation *</option>
                    <?php foreach($salutation_master_data as $res)
                      { ?>
                      <option value="<?php echo $res; ?>" <?php if($chk_salutation == $res) { echo 'selected'; } ?>><?php echo $res; ?></option>
                      <?php }
                    }
                    else 
                    { ?>
                    <option value="">No Salutation Available</option>
                  <?php } ?>
                </select>
                
                <span id="salutation_err"></span>
                <?php if(form_error('salutation')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('salutation'); ?></label> <?php } ?>
              </div>					
            </div>

            <div class="col-xl-9 col-lg-9"><?php /* Name */ ?>
              <div class="form-group">
                <label for="name" class="form_label">Name <sup class="text-danger">*</sup></label>
                <input type="text" name="name" id="name" value="<?php echo set_value('name'); ?>" placeholder="Name *" class="form-control custom_input" maxlength="100" required/>
                <note class="form_note" id="name_err">Note: Please enter up to 100 characters</note>
                
                <?php if(form_error('name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('name'); ?></label> <?php } ?>
              </div>					
            </div>
            
            <div class="col-xl-6 col-lg-6"><?php /*  Designation */ ?>
              <div class="form-group">
                <label for="designation" class="form_label">Designation <sup class="text-danger">*</sup></label>
                <input type="text" name="designation" id="designation" value="<?php echo set_value('designation'); ?>" placeholder="Designation *" class="form-control custom_input" maxlength="100" required/>
                <note class="form_note" id="designation_err">Note: Please enter up to 100 characters</note>
                
                <?php if(form_error('designation')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('designation'); ?></label> <?php } ?>
              </div>					
            </div>

            <?php $chk_organization = set_value('org_id'); ?>
            <div class="col-xl-6 col-lg-6"><?php /* Select Organization */ ?>
              <div class="form-group">
                <label for="org_id" class="form_label">Select Organization <sup class="text-danger">*</sup></label>
                <select name="org_id" id="org_id" class="form-control chosen-select" required onchange="validate_input('org_id'); ">
                  <?php if(count($organization_master_data) > 0)
                    { ?>
                    <option value="">Select Organization *</option>
                    <?php foreach($organization_master_data as $res)
                      { ?>
                      <option value="<?php echo $res['org_id']; ?>" <?php if($chk_organization == $res['org_id']) { echo 'selected'; } ?>><?php echo $res['org_name']; ?></option>
                      <?php }
                    }
                    else 
                    { ?>
                    <option value="">No Organization Available</option>
                  <?php } ?>
                </select>
                
                <span id="org_id_err"></span>
                <?php if(form_error('org_id')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('org_id'); ?></label> <?php } ?>
              </div>					
            </div>
            
            <div class="col-xl-6 col-lg-6"><?php /*Email id */ ?>
              <div class="form-group">
                <label for="email" class="form_label">Email id <sup class="text-danger">*</sup></label>
                <input type="text" name="email" id="email" value="<?php echo set_value('email'); ?>" placeholder="Email id *" class="form-control custom_input" required maxlength="90" />
                <note class="form_note" id="email_err">Note: Please enter up to 90 characters</note>
                
                <?php if(form_error('email')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('email'); ?></label> <?php } ?>
              </div>					
            </div>

            <div class="col-xl-6 col-lg-6"><?php /*Mobile Number */ ?>
              <div class="form-group">
                <label for="mobile" class="form_label">Mobile Number <sup class="text-danger">*</sup></label>
                <div class="input-group m-b mb-0">
                  <div class="input-group-prepend">
                    <span class="input-group-addon country_code_outer" style="min-width:60px;">+91</span>
                  </div>
                  <input type="text" name="mobile" id="mobile" value="<?php echo set_value('mobile'); ?>" placeholder="Mobile Number *" class="form-control custom_input allow_only_numbers" required maxlength="10" minlength="10" />
                </div>
                <note class="form_note" id="mobile_err">Note: Mobile number must be 10 digits long</note>
                
                <?php if(form_error('mobile')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('mobile'); ?></label> <?php } ?>
              </div>					
            </div>
            
            <div class="col-xl-6 col-lg-6"><?php /* Code */ ?>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label for="apabi_india_registration_captcha" class="form_label">Code <sup class="text-danger">*</sup></label>
                    <input type="text" name="apabi_india_registration_captcha" id="apabi_india_registration_captcha" class="form-control" placeholder="Code *" required autocomplete="off" value="" />
                    <?php if (form_error('apabi_india_registration_captcha') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('apabi_india_registration_captcha'); ?></label> <?php } ?>
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
            <div class="col-xl-12 col-lg-12 mb-3">              
              <label class="css_checkbox_radio radio_only "> I, hereby, declare that the details provided above are correct. In case my organization does not nominate my name for the conference, my registration is liable to be cancelled by IIBF.
                <input type="checkbox" value="Yes" name="accept_terms_conditions" id="accept_terms_conditions" required class="">
                <span class="checkmark" style="margin:5px 0 0 0;"></span>
              </label>
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
    
    <?php $this->load->view('apabi_india/inc_footer'); ?>        
    <?php $this->load->view('apabi_india/inc_common_validation_all'); ?>
    <?php $this->load->view('apabi_india/inc_common_show_hide_password'); ?>
    
    <script type="text/javascript">
      function refresh_captcha() 
      {
        $("#page_loader").css("display", "block");
        $("#apabi_india_registration_captcha").val("");
        $.ajax({
          type: "POST",
          url: "<?php echo site_url('apabi_india_registration/refresh_captcha'); ?>",
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
            //$('#current_faculty_status').val(status);
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
        $("#add_form").submit(function() 
        {
          if($("#org_id").valid() == false) { $('#org_id').trigger('chosen:activate'); }
        });

        var form = $("#add_form").validate( 
        {
          onkeyup: function(element) { $(element).valid(); },             
          rules:
          {
            salutation:{ required: true },
            name:{ required: true, maxlength:100 },          
            designation:{ required: true, maxlength:100 },
            org_id:{ required: true },
            email:{ required: true, maxlength:90, valid_email:true, remote: { url: "<?php echo site_url('apabi_india_registration/validation_check_email_exist/0/1'); ?>", type: "post" } },
            mobile:{ required: true, allow_only_numbers:true, first_zero_not_allowed:true, maxlength:10, minlength:10, remote: { url: "<?php echo site_url('apabi_india_registration/validation_check_mobile_exist/0/1'); ?>", type: "post" } },
            apabi_india_registration_captcha: { required: true, remote: { url: "<?php echo site_url('apabi_india_registration/validation_check_captcha/0/1'); ?>", type: "post" } },
            accept_terms_conditions: { required: true }  
          },
          messages:
          {
            salutation: { required: "Please select the salutation" },
            name: { required: "Please enter the name" },     
            designation: { required: "Please enter the designation" },
            org_id: { required: "Please select the organization" },
            email: { required: "Please enter the email id", valid_email: "Please enter the valid email id", remote: "The email id is already registered with us"  },
            mobile: { required: "Please enter the mobile number", minlength: "Mobile number must be 10 digits long", maxlength: "Mobile number must be 10 digits long", remote: "The mobile number is already registered with us" },
            apabi_india_registration_captcha: { required: "Please enter the code", remote: "Please enter the valid code" },
            accept_terms_conditions: { required: "Please accept the declaration" }
          }, 
          errorPlacement: function(error, element) // For replace error 
          {
            if (element.attr("name") == "salutation") { error.insertAfter("#salutation_err"); }
            else if (element.attr("name") == "name") { error.insertAfter("#name_err"); }
            else if (element.attr("name") == "designation") { error.insertAfter("#designation_err"); }
            else if (element.attr("name") == "org_id") { error.insertAfter("#org_id_err"); }
            else if (element.attr("name") == "email") { error.insertAfter("#email_err"); }
            else if (element.attr("name") == "mobile") { error.insertAfter("#mobile_err"); }            
            else { error.insertAfter(element); }
          },          
          submitHandler: function(form) 
          {          
            $("#page_loader").hide();
            swal({ title: "Please confirm", text: "Please confirm to submit the details?", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
            { 
              $("#page_loader").show();            
              $("#submit_btn_outer").html('<button class="btn btn-primary" type="button" style="cursor:wait">Submit</button>');
            
              form.submit();
            }); 
          }
        });
      });
    </script>
    <?php $this->load->view('apabi_india/inc_bottom_script'); ?>
  </body>
</html>