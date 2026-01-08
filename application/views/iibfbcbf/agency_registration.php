<!DOCTYPE html>
<html>
  <head>
    <?php $this->load->view('iibfbcbf/inc_header'); ?>
  </head>
  
  <body class="gray-bg">
    <?php $this->load->view('iibfbcbf/common/inc_loader'); ?>
    <div class="d-flex logo" style="z-index:1;"><img src="<?php echo base_url('assets/iibfbcbf/images/iibf_logo.png'); ?>" class="img-fluid" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">   <h3 class="mb-0">INDIAN INSTITUTE OF BANKING & FINANCE - BCBF Agency Registration</h3></div>
    <div class="container">        
  
      
      <div class="admin_login_form animated fadeInDown" style="width: 100%; max-width: none; margin-top:110px"> 
        <form method="post" action="<?php echo site_url('iibfbcbf/agency_registration'); ?>" id="add_form" enctype="multipart/form-data" autocomplete="off">
          <h3 class="text-center mb-3"><b>BCBF Agency Registration</b></h3>
          <div class="hr-line-dashed mb-4"></div>
          
          <div class="row">
            <div class="col-xl-6 col-lg-6"><?php /* Agency Name */ ?>
              <div class="form-group">
                <label for="agency_name" class="form_label">Agency Name <sup class="text-danger">*</sup></label>
                <input type="text" name="agency_name" id="agency_name" value="<?php echo set_value('agency_name'); ?>" placeholder="Agency Name *" class="form-control custom_input allow_only_alphabets_and_space" maxlength="90" required/>
                <note class="form_note" id="agency_name_err">Note: Please enter only 90 characters</note>
                
                <?php if(form_error('agency_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('agency_name'); ?></label> <?php } ?>
              </div>					
            </div>
            
            <div class="col-xl-6 col-lg-6"><?php /* Establishment Year */ ?>
              <div class="form-group">
                <label for="estb_year" class="form_label">Establishment Year <sup class="text-danger">*</sup></label>
                <input type="text" name="estb_year" id="estb_year" value="<?php echo set_value('estb_year'); ?>" placeholder="Establishment Year *" class="form-control custom_input" maxlength="4" readonly onchange="validate_input('estb_year')"/>
                
                <?php if(form_error('estb_year')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('estb_year'); ?></label> <?php } ?>
              </div>					
            </div>
            
            <div class="col-xl-12 col-lg-12"><?php /* Address line1 */ ?>
              <div class="form-group">
                <label for="agency_address1" class="form_label">Address Line-1 <sup class="text-danger">*</sup></label>
                <input type="text" name="agency_address1" id="agency_address1" placeholder="Address Line-1 *" class="form-control custom_input" maxlength="75" required value="<?php echo set_value('agency_address1'); ?>" />
                
                <note class="form_note" id="agency_address1_err">Note: Please enter only 75 characters</note>
                
                <?php if(form_error('agency_address1')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('agency_address1'); ?></label> <?php } ?>
              </div>					
            </div>
            
            <div class="col-xl-12 col-lg-12"><?php /* Address line2 */ ?>
              <div class="form-group">
                <label for="agency_address2" class="form_label">Address Line-2 <sup class="text-danger"></sup></label>
                <input type="text" name="agency_address2" id="agency_address2" placeholder="Address Line-2" class="form-control custom_input" maxlength="75" value="<?php echo set_value('agency_address2'); ?>" />
                
                <note class="form_note" id="agency_address2_err">Note: Please enter only 75 characters</note>
                
                <?php if(form_error('agency_address2')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('agency_address2'); ?></label> <?php } ?>
              </div>					
            </div>
            
            <div class="col-xl-12 col-lg-12"><?php /* Address line3 */ ?>
              <div class="form-group">
                <label for="agency_address3" class="form_label">Address Line-3 <sup class="text-danger"></sup></label>
                <input type="text" name="agency_address3" id="agency_address3" placeholder="Address Line-3" class="form-control custom_input" maxlength="75" value="<?php echo set_value('agency_address3'); ?>" />
                
                <note class="form_note" id="agency_address3_err">Note: Please enter only 75 characters</note>
                
                <?php if(form_error('agency_address3')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('agency_address3'); ?></label> <?php } ?>
              </div>					
            </div>
            
            <div class="col-xl-12 col-lg-12"><?php /* Address line4 */ ?>
              <div class="form-group">
                <label for="agency_address4" class="form_label">Address Line-4 <sup class="text-danger"></sup></label>
                <input type="text" name="agency_address4" id="agency_address4" placeholder="Address Line-4" class="form-control custom_input" maxlength="75" value="<?php echo set_value('agency_address4'); ?>" />
                
                <note class="form_note" id="agency_address4_err">Note: Please enter only 75 characters</note>
                
                <?php if(form_error('agency_address4')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('agency_address4'); ?></label> <?php } ?>
              </div>					
            </div>
            
            <?php $chk_state = set_value('agency_state'); ?>
            <div class="col-xl-6 col-lg-6"><?php /* Select State */ ?>
              <div class="form-group">
                <label for="agency_state" class="form_label">Select State <sup class="text-danger">*</sup></label>
                <select name="agency_state" id="agency_state" class="form-control chosen-select" required onchange="get_city_ajax(this.value); validate_input('agency_state'); ">
                  <?php if(count($state_master_data) > 0)
                    { ?>
                    <option value="">Select State *</option>
                    <?php foreach($state_master_data as $state_res)
                      { ?>
                      <option value="<?php echo $state_res['state_code']; ?>" <?php if($chk_state == $state_res['state_code']) { echo 'selected'; } ?>><?php echo $state_res['state_name']; ?></option>
                      <?php }
                    }
                    else 
                    { ?>
                    <option value="">No State Available</option>
                  <?php } ?>
                </select>
                
                <span id="agency_state_err"></span>
                <?php if(form_error('agency_state')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('agency_state'); ?></label> <?php } ?>
              </div>					
            </div>
            
            <div class="col-xl-6 col-lg-6"><?php /* Select City */ ?>
              <div class="form-group">
                <label for="agency_city" class="form_label">Select City <sup class="text-danger">*</sup></label>
                <div id="city_outer">
                  <select class="form-control chosen-select" name="agency_city" id="agency_city" required onchange="validate_input('agency_city'); ">
                    <?php $selected_state_val = '';
                      if(set_value('agency_state') != "") { $selected_state_val = set_value('agency_state'); }
                                            
                      if($selected_state_val != "")
                      {
                        $city_data = $this->master_model->getRecords('city_master', array('state_code' => $selected_state_val, 'city_delete' => '0'), 'id, city_name', array('city_name'=>'ASC'));
                        
                        if(count($city_data) > 0)
                        { ?>
                        <option value="">Select City</option>
                        <?php foreach($city_data as $city)
                          { ?>
                          <option value="<?php echo $city['id']; ?>" <?php if(set_value('agency_city') == $city['id']) { echo "selected"; } ?>><?php echo $city['city_name']; ?></option>
                          <?php }
                        }
                        else
                        { ?>
                        <option value="">No City Available</option>
                        <?php }
                      }
                      else 
                      {
                        echo '<option value="">Select City</option>';
                      } ?>
                  </select>
                </div>
                <span id="agency_city_err"></span>
                <?php if(form_error('agency_city')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('agency_city'); ?></label> <?php } ?>                            
              </div>                       
            </div>
            
            <div class="col-xl-6 col-lg-6"><?php /* District */ ?>
              <div class="form-group">
                <label for="agency_district" class="form_label">District <sup class="text-danger">*</sup></label>
                <input type="text" name="agency_district" id="agency_district" value="<?php echo set_value('agency_district'); ?>" placeholder="District *" class="form-control custom_input allow_only_alphabets_and_space" maxlength="30" required/>
                <note class="form_note" id="agency_district_err">Note: Please enter only 30 characters</note>
                
                <?php if(form_error('agency_district')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('agency_district'); ?></label> <?php } ?>
              </div>					
            </div>
            
            <div class="col-xl-6 col-lg-6"><?php /* Pincode */ ?>
              <div class="form-group">
                <label for="agency_pincode" class="form_label">Pincode <sup class="text-danger">*</sup></label>
                <input type="text" name="agency_pincode" id="agency_pincode" value="<?php echo set_value('agency_pincode'); ?>" placeholder="Pincode *" class="form-control custom_input allow_only_numbers" required maxlength="6" minlength="6" />
                
                <?php if(form_error('agency_pincode')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('agency_pincode'); ?></label> <?php } ?>
              </div>					
            </div>
            
            <div class="col-xl-6 col-lg-6"><?php /* Contact Person Name */ ?>
              <div class="form-group">
                <label for="contact_person_name" class="form_label">Contact Person Name <sup class="text-danger">*</sup></label>
                <input type="text" name="contact_person_name" id="contact_person_name" value="<?php echo set_value('contact_person_name'); ?>" placeholder="Contact Person Name *" class="form-control custom_input allow_only_alphabets_and_space" maxlength="90" required/>
                <note class="form_note" id="contact_person_name_err">Note: Please enter only 90 characters</note>
                
                <?php if(form_error('contact_person_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('contact_person_name'); ?></label> <?php } ?>
              </div>					
            </div>
            
            <div class="col-xl-6 col-lg-6"><?php /* Contact Person Designation */ ?>
              <div class="form-group">
                <label for="contact_person_designation" class="form_label">Contact Person Designation <sup class="text-danger">*</sup></label>
                <input type="text" name="contact_person_designation" id="contact_person_designation" value="<?php echo set_value('contact_person_designation'); ?>" placeholder="Contact Person Designation *" class="form-control custom_input allow_only_alphabets_and_space" maxlength="90" required/>
                <note class="form_note" id="contact_person_designation_err">Note: Please enter only 90 characters</note>
                
                <?php if(form_error('contact_person_designation')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('contact_person_designation'); ?></label> <?php } ?>
              </div>					
            </div>
            
            <div class="col-xl-6 col-lg-6"><?php /* Contact Person Mobile Number */ ?>
              <div class="form-group">
                <label for="contact_person_mobile" class="form_label">Contact Person Mobile Number <sup class="text-danger">*</sup></label>
                <input type="text" name="contact_person_mobile" id="contact_person_mobile" value="<?php echo set_value('contact_person_mobile'); ?>" placeholder="Contact Person Mobile Number *" class="form-control custom_input allow_only_numbers" required maxlength="10" minlength="10" />
                
                <?php if(form_error('contact_person_mobile')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('contact_person_mobile'); ?></label> <?php } ?>
              </div>					
            </div>
            
            <div class="col-xl-6 col-lg-6"><?php /* Contact Person Email id */ ?>
              <div class="form-group">
                <label for="contact_person_email" class="form_label">Contact Person Email id <sup class="text-danger">*</sup></label>
                <input type="text" name="contact_person_email" id="contact_person_email" value="<?php echo set_value('contact_person_email'); ?>" placeholder="Contact Person Email id *" class="form-control custom_input" required maxlength="80" />
                <note class="form_note" id="contact_person_email_err">Note: Please enter only 80 characters</note>
                
                <?php if(form_error('contact_person_email')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('contact_person_email'); ?></label> <?php } ?>
              </div>					
            </div>
            
            <div class="col-xl-12 col-lg-12"><?php /* Agency GST No */ ?>
              <div class="form-group">
                <label for="gst_no" class="form_label">Agency GST No <sup class="text-danger">*</sup></label>
                <input type="text" name="gst_no" id="gst_no" value="<?php echo set_value('gst_no'); ?>" placeholder="Agency GST No *" class="form-control custom_input allow_only_alphabets_and_numbers" required minlength="15" maxlength="15" />
                
                <note class="form_note" id="gst_no_err">Note: Please enter GST no like 29ABCDE1234F1ZW</note>

                <?php if(form_error('gst_no')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('gst_no'); ?></label> <?php } ?>
              </div>					
            </div>
            
            <div class="col-xl-6 col-lg-6"><?php /* Password */ ?>
              <div class="form-group login_password_common">
                <label for="agency_password" class="form_label">Password <sup class="text-danger">*</sup></label>
                <input type="password" class="form-control custom_input" name="agency_password" id="agency_password" value="<?php echo set_value('agency_password'); ?>" placeholder="Password *" required minlength="8" maxlength="20">
                <note class="form_note" id="agency_password_err">Note: Please enter minimum 8 and maximum 20 characters</note>
                
                <?php if(form_error('agency_password')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('agency_password'); ?></label> <?php } ?>
                
                <span class="show-password" onclick="show_hide_password(this,'show', 'agency_password')" style="top:28px;"><i class="fa fa-eye" aria-hidden="true"></i></span>
                <span class="hide-password" onclick="show_hide_password(this,'hide', 'agency_password')" style="display:none;top:28px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
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
            
            <?php /* echo get_ip_address(); */
            if(get_ip_address() == '115.124.115.69' || get_ip_address() == '115.124.115.75') 
            { ?>
              <input type="hidden" name="iibf_bcbf_agency_registration_captcha" id="iibf_bcbf_agency_registration_captcha" class="form-control" placeholder="Code *" required autocomplete="off" value="<?php echo $_SESSION['IIBF_BCBF_AGENCY_REGISTRATION_CAPTCHA']; ?>" />
            <?php } 
            else
            { ?>
              <div class="col-xl-6 col-lg-6"><?php /* Code */ ?>
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-6">
                      <label for="iibf_bcbf_agency_registration_captcha" class="form_label">Code <sup class="text-danger">*</sup></label>
                      <input type="text" name="iibf_bcbf_agency_registration_captcha" id="iibf_bcbf_agency_registration_captcha" class="form-control" placeholder="Code *" required autocomplete="off" value="" />
                      <?php if (form_error('iibf_bcbf_agency_registration_captcha') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('iibf_bcbf_agency_registration_captcha'); ?></label> <?php } ?>
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
            <?php } ?>
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
    
    
    <?php $this->load->view('iibfbcbf/inc_footer'); ?>
        
    <?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>
    <?php $this->load->view('iibfbcbf/common/inc_common_show_hide_password'); ?>
    
    <script type="text/javascript">
      function refresh_captcha() 
      {
        $("#page_loader").css("display", "block");
        $("#iibf_bcbf_agency_registration_captcha").val("");
        $.ajax({
          type: "POST",
          url: "<?php echo site_url('iibfbcbf/agency_registration/refresh_captcha'); ?>",
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

      var estb_year = $('#estb_year').datepicker({ keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy", viewMode: "years", minViewMode: "years", clearBtn: true, endDate:"<?php echo date("Y"); ?>" });
    
      function get_city_ajax(state_id)
      {
        $("#page_loader").show();
        parameters="state_id="+state_id;
        
        $.ajax({
          type: "POST",
          url: "<?php echo site_url('iibfbcbf/agency_registration/get_city_ajax'); ?>",
          data: parameters,
          cache: false,
          dataType: 'JSON',
          success:function(data)
          {
            if(data.flag == "success")
            {
              $("#city_outer").html(data.response);
              $("#page_loader").hide();
            }
            else
            {
              alert("Error occurred. Please try again.")
              $('#page_loader').hide();
            }
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
            console.log('AJAX request failed: ' + errorThrown);
            alert("Error occurred. Please try again.")
            $('#page_loader').hide();
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
            agency_name:{ required: true, allow_only_alphabets_and_space:true, maxlength:90 },
            estb_year:{ required: true },
            agency_address1:{ required: true, maxlength:75 },
            agency_address2:{ maxlength:75 },
            agency_address3:{ maxlength:75 },
            agency_address4:{ maxlength:75 },
            agency_state:{ required: true },  
            agency_city:{ required: true }, 
            agency_district:{ required: true, allow_only_alphabets_and_space:true, maxlength:30 }, 
            agency_pincode:{ required: true, allow_only_numbers:true, minlength:6, maxlength: 6, remote: { url: "<?php echo site_url('iibfbcbf/agency_registration/validation_check_valid_pincode/0/1'); ?>", type: "post", data: { "selected_state_code": function() { return $("#agency_state").val(); } } } },  //check validation for pincode as per selected agency_state
            contact_person_name:{ required: true, allow_only_alphabets_and_space:true, maxlength:90 },
            contact_person_designation:{ required: true, allow_only_alphabets_and_space:true, maxlength:90 },
            contact_person_mobile:{ required: true, allow_only_numbers:true, first_zero_not_allowed:true, maxlength:10, minlength:10, remote: { url: "<?php echo site_url('iibfbcbf/agency_registration/validation_check_mobile_exist/0/1'); ?>", type: "post" } },            
            contact_person_email:{ required: true, maxlength:80, valid_email:true, remote: { url: "<?php echo site_url('iibfbcbf/agency_registration/validation_check_email_exist/0/1'); ?>", type: "post" } }, 
            gst_no:{ required: true, minlength:15, maxlength:15, allow_only_alphabets_and_numbers:true, valid_gst_no:true, remote: { url: "<?php echo site_url('iibfbcbf/agency_registration/validation_check_gst_no_exist/0/1'); ?>", type: "post" } },           
            agency_password: { required: true, minlength: 8, maxlength:20, pwcheck: true },
            confirm_password: { required: true, equalTo: "#agency_password" },
            iibf_bcbf_agency_registration_captcha: { required: true, remote: { url: "<?php echo site_url('iibfbcbf/agency_registration/validation_check_captcha/0/1'); ?>", type: "post" } }            
          },
          messages:
          {
            agency_name: { required: "Please enter the agency name" },
            estb_year: { required: "Please select the establishment year" },
            agency_address1: { required: "Please enter the address line-1" },
            agency_address2: { },
            agency_address3: { },
            agency_address4: { },
            agency_state: { required: "Please select the state" },
            agency_city: { required: "Please select the city" },
            agency_district: { required: "Please enter the district" },
            agency_pincode: { required: "Please enter the pincode", minlength: "Please enter 6 numbers in pincode", maxlength: "Please enter 6 numbers in pincode", remote: "Please enter valid pincode as per selected city" },
            contact_person_name: { required: "Please enter the contact person name" },
            contact_person_designation: { required: "Please enter the contact person designation" },
            contact_person_mobile: { required: "Please enter the mobile number", minlength: "Please enter 10 numbers in mobile number", maxlength: "Please enter 10 numbers in mobile number", remote: "The mobile number is already exist" },
            contact_person_email: { required: "Please enter the email id", valid_email: "Please enter the valid email id", remote: "The email id is already exist"  },
            gst_no: { required: "Please enter the GST no.", minlength: "Please enter 15 character in GST no.", maxlength: "Please enter 15 character in GST no.", valid_gst_no : "Please enter the valid GST no. like 29ABCDE1234F1ZW", remote: "The gst number is already exist" },
            agency_password: { required: "Please enter the password", minlength: "Please enter minimum 8 character", pwcheck:"Password must contain at least one upper-case character, one lower-case character, one digit and one special character" },					
            confirm_password: { required: "Please enter the Confirm Password", equalTo:"Please enter confirm password same as password" },
            iibf_bcbf_agency_registration_captcha: { required: "Please enter the code", remote: "Please enter the valid code" }
          }, 
          errorPlacement: function(error, element) // For replace error 
          {
            if (element.attr("name") == "agency_name") { error.insertAfter("#agency_name_err"); }
            else if (element.attr("name") == "agency_address1") { error.insertAfter("#agency_address1_err"); }
            else if (element.attr("name") == "agency_address2") { error.insertAfter("#agency_address2_err"); }
            else if (element.attr("name") == "agency_address3") { error.insertAfter("#agency_address3_err"); }
            else if (element.attr("name") == "agency_address4") { error.insertAfter("#agency_address4_err"); }
            else if (element.attr("name") == "agency_state") { error.insertAfter("#agency_state_err"); }
            else if (element.attr("name") == "agency_city") { error.insertAfter("#agency_city_err"); }
            else if (element.attr("name") == "agency_district") { error.insertAfter("#agency_district_err"); }
            else if (element.attr("name") == "contact_person_name") { error.insertAfter("#contact_person_name_err"); }
            else if (element.attr("name") == "contact_person_designation") { error.insertAfter("#contact_person_designation_err"); }
            else if (element.attr("name") == "contact_person_email") { error.insertAfter("#contact_person_email_err"); }
            else if (element.attr("name") == "gst_no") { error.insertAfter("#gst_no_err"); }
            else if (element.attr("name") == "agency_password") { error.insertAfter("#agency_password_err"); }
            else if (element.attr("name") == "is_active") { error.insertAfter("#is_active_err"); }
            else { error.insertAfter(element); }
          },          
          submitHandler: function(form) 
          {          
            $("#page_loader").hide();
            swal({ title: "Please confirm", text: "Please confirm to submit the details?", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
            { 
              $("#page_loader").show();            
              $("#submit_btn_outer").html('<button class="btn btn-primary" type="button" style="cursor:wait">Submit</button> <a class="btn btn-danger" href="<?php echo site_url('iibfbcbf/admin/agency'); ?>">Back</a>');
            
              form.submit();
            }); 
          }
        });
      });
    </script>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>