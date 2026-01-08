<!DOCTYPE html>
<html>
  <head>
    <?php $this->load->view('macroresearch/inc_header'); ?>
  </head>
  
  <body class="gray-bg">
    <?php $this->load->view('macroresearch/common/inc_loader'); ?>
    <div class="d-flex logo" style="z-index:1;">
        <img src="<?php echo base_url('assets/macroresearch/images/iibf_logo.png'); ?>" class="img-fluid" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING  & FINANCE">   <h3 class="mb-0">INDIAN INSTITUTE OF BANKING & FINANCE - Macro Research Application Form</h3>
    </div>
    <div class="container">        
  
      
      <div class="admin_login_form animated fadeInDown" style="width: 100%; max-width: none; margin-top:110px"> 
        <form method="post" action="<?php echo site_url('macroresearch/application'); ?>" id="add_form" enctype="multipart/form-data" autocomplete="off">
          <h3 class="text-center mb-3"><b> Macro Research Application Form</b></h3>
          <div class="hr-line-dashed mb-4"></div>
          <?php if (isset($var_errors) && $var_errors != '') { ?>
							<div class="alert alert-danger alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
								<?php echo $var_errors; ?>
							</div>
						<?php
						}
						?>
            <label id="error " class="displayerrors error col-xl-12"  style=""></label>
          <div class="row">
          <div class="col-xl-4 col-lg-4"><?php /* Select Type */ ?>
              <div class="form-group ">
                <label for="application_type" class="form_label">Type <sup class="text-danger">*</sup></label>
                <div class="application_type_outer">
                  
                      <select name="application_type" id="application_type" readonly class="application_type form-control chosen-select          select_application_type"  >
                      
                        <option <?php if(isset($_COOKIE['IIBFMACRORESEARCH']) && $_COOKIE['IIBFMACRORESEARCH']=='Individual') echo'selected' ?> value="Individual">Individual</option>
                        <option <?php if(isset($_COOKIE['IIBFMACRORESEARCH']) && $_COOKIE['IIBFMACRORESEARCH']=='Joint') echo'selected' ?> value="Joint">Joint</option>
                        <option <?php if(isset($_COOKIE['IIBFMACRORESEARCH']) && $_COOKIE['IIBFMACRORESEARCH']=='Institute') echo'selected' ?> value="Institute">Institute</option>
                      
                      </select>
                </div>
                <span id="application_type_err"></span>
                <?php if(form_error('application_type')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('application_type'); ?></label> <?php } ?>
              </div>					
            </div>
            <div class="col-xl-2 col-lg-2">&nbsp;</div>
            <div class="col-xl-6 col-lg-6 institute_name_div"><?php /* Institute Name*/ ?>
                  <div class="form-group">
                    <label for="institute_name" class="form_label"> Institute Name <sup class="text-danger">*</sup></label>
                    <input type="text" name="institute_name" id="institute_name" value="<?php echo set_value('institute_name'); ?>" placeholder=" Institute Name *" class="form-control  "  maxlength="255" 
                    />
                    <note class="form_note" id="institute_name_err">Note: Please enter only up to 255 characters</note>
                    <?php if(form_error('institute_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('institute_name'); ?></label> <?php } ?>
                  </div>					
                </div>

            
            
            <div class=" multiplesections">
              <div class="row col-md-12 addmoresection addmoresection_0">

                <h3 class="text-center mb-3 col-md-12"><b>Applicant’s Details</b> <a href="javascript:void(0);"  style="display:none;" class="pull-right btn btn-danger btn-sm removesection">Remove</a></h3>
                  <div class="col-xl-6 col-lg-6"><?php /*  Salutation */ ?>
                    <div class="form-group">
                      <label for="candidate_salutation" class="form_label">Salutation <sup class="text-danger">*</sup></label>
                      <select name="candidate_salutation[]" id="candidate_salutation" required placeholder="Salutation*" class="form-control custom_input" >
                        <option value="">Select</option>
                        <option value="Mr.">Mr.</option>
                        <option value="Ms.">Ms.</option>
                        <option value="Mrs.">Mrs.</option>
                        <option value="Dr.">Dr.</option>
                      </select>
                      <note class="form_note" id="candidate_salutation_err"></note>
                      
                      <?php if(form_error('candidate_salutation')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('candidate_salutation'); ?></label> <?php } ?>
                    </div>					
                  </div>

                  <div class="col-xl-6 col-lg-6"><?php /*  Name */ ?>
                    <div class="form-group">
                      <label for="candidate_name" class="form_label candidate_name_label">Full Name <sup class="text-danger">*</sup></label>
                      <!--<br><label class="moreinfo form_label"><span style="margin-right: 60px;">First name  </span> <span style="margin-right: 60px;"> Middle name   </span><span>     last name</span></label>-->
                      <input type="text" name="candidate_name[]" id="candidate_name" value="<?php echo set_value('candidate_name'); ?>" placeholder="First name          Middle name           Last name" class="form-control custom_input allow_only_alphabets_and_space" maxlength="160" required/>
                      <note class="form_note" id="candidate_name_err">Note: Please enter only up to 160 characters</note>
                      
                      <?php if(form_error('candidate_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('candidate_name'); ?></label> <?php } ?>
                    </div>					
                  </div>
                  
                  <div class="col-xl-6 col-lg-6"><?php /*  Dob */ ?>
                    <div class="form-group">
                      <label for="dob" class="form_label">Date of Birth <sup class="text-danger">*</sup></label>
                      <input type="text" name="dob[]" onchange="validate_input('dob')" id="dob" value="<?php echo set_value('dob'); ?>" placeholder="Date of Birth *" class="form-control custom_input date_field" required/>
                      
                      
                      <?php if(form_error('dob')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('dob'); ?></label> <?php } ?>
                    </div>					
                  </div>
                  
                
                  <div class="col-xl-6 col-lg-6"><?php /* Email */ ?>
                    <div class="form-group">
                      <label for="email" class="form_label">Email Id<sup class="text-danger">*</sup>
                      
                    </label>
                    <span class="pull-right receive_acknowledge_span" style="display:none;">
                        <label class="form_label"> Acknowledgement required</label>
                      <input type="checkbox" name="receive_acknowledge[0]" id="receive_acknowledge"  class="custom_input" value="1" />
                      </span>
                      <input type="email" name="email[]" id="email" placeholder="Email Id*" class="form-control custom_input" maxlength="75" required value="<?php echo set_value('email'); ?>" />
                      
                      <note class="form_note" id="email_err">Note: Please enter only up to 75 characters</note>
                      
                      <?php if(form_error('email')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('email'); ?></label> <?php } ?>
                    </div>					
                  </div>
                  
                  <div class="col-xl-6 col-lg-6"><?php /* Mobile */ ?>
                    <div class="form-group">
                      <label for="mobile" class="form_label">Mobile Number<sup class="text-danger">*</sup></label>
                      <input type="text" name="mobile[]" id="mobile" placeholder="Mobile Number*" required class="form-control custom_input allow_only_numbers" maxlength="10" minlength="10"  value="<?php echo set_value('mobile'); ?>" />
                      
                      <note class="form_note" id="mobile_err">Note: Please enter only up to 10 characters</note>
                      
                      <?php if(form_error('mobile')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('mobile'); ?></label> <?php } ?>
                    </div>					
                  </div>
                  
                  
                  
                  <div class="col-xl-6 col-lg-6"><?php /* nature_of_job */ ?>
                    <div class="form-group">
                      <label for="nature_of_job" class="form_label">Nature of Job <sup class="text-danger">*</sup></label>
                      <select onchange="nature_of_job_func(this);" name="nature_of_job[]" id="nature_of_job" required placeholder="Nature of Job*" class="form-control custom_input" >
                        <option value="">Select</option>
                        <option value="Regular">Regular</option>
                        <option value="Contractual">Contractual</option>
                        <option value="Part-time">Part-time</option>
                      </select>
                      
                      <?php if(form_error('nature_of_job')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('nature_of_job'); ?></label> <?php } ?>
                    </div>					
                  </div>
                  
                  <div class="col-xl-6 col-lg-6"><?php /* Designation */ ?>
                    <div class="form-group">
                      <label for="designation" class="form_label">Designation <sup class="text-danger">*</sup></label>
                      <input type="text" name="designation[]" id="designation" value="<?php echo set_value('designation'); ?>" placeholder="Designation *" class="form-control custom_input " maxlength="30" required/>
                      <note class="form_note" id="designation_err">Note: Please enter only up to 30 characters</note>
                      
                      <?php if(form_error('designation')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('designation'); ?></label> <?php } ?>
                    </div>					
                  </div>

                  <div class="col-xl-6 col-lg-6"><?php /* Employer */ ?>
                    <div class="form-group">
                      <label for="employer" class="form_label">Employer <sup class="text-danger">*</sup></label>
                      <input type="text" name="employer[]" id="employer" value="<?php echo set_value('employer'); ?>" placeholder="Employer *" class="form-control custom_input" maxlength="160" required/>
                      <note class="form_note" id="employer_err">Note: Please enter only up to 160 characters</note>
                      
                      <?php if(form_error('employer')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('employer'); ?></label> <?php } ?>
                    </div>					
                  </div>
                  
                  <div class="col-xl-12 col-lg-12"><?php /* Address */ ?>
                    <div class="form-group">
                      <label for="address" class="form_label">Address <sup class="text-danger">*</sup></label>
                      <input type="text" name="address[]" id="address" placeholder="Address*" required class="form-control custom_input" maxlength="255" value="<?php echo set_value('address'); ?>" />
                      
                      <note class="form_note" id="address_err">Note: Please enter only up to 255 characters</note>
                      
                      <?php if(form_error('address')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address'); ?></label> <?php } ?>
                    </div>					
                  </div>
                  <div class="col-xl-6 col-lg-6"><?php /* Forwarding Letter */ ?>
                    <div class="form-group">
                      <label for="forwarding_letter" class="form_label">Forwarding Letter <sup class="text-danger">*</sup></label>
                      <input type="file" name="forwarding_letter[]" id="forwarding_letter" value="<?php echo set_value('forwarding_letter'); ?>" placeholder="Forwarding Letter *" class="form-control  " onchange="validateFile(event, 'forwarding_letter-error', 'image_upload_forwarding_letter_preview', '5130kb')" required maxlength="255" minlength="5" />
                      <label id="forwarding_letter-error" class="error" for="forwarding_letter"></label>
                      <!-- <label  id="error_forwarding_letter_size" class="error"></label> -->
                      <note class="form_note" id="forwarding_letter_err">Note: Please upload JPEG/PDF file only up to 5MB in size</note>
                      
                      <?php if(form_error('forwarding_letter')!=""){ ?> <div class="clearfix"></div><label  id="error_forwarding_letter_size" class="error"><?php echo form_error('forwarding_letter'); ?></label> <?php } ?>
                    </div>					
                  </div>
                
                  <div class="col-xl-6 col-lg-6"><?php /* CV */ ?>
                    <div class="form-group">
                      <label for="resume" class="form_label">CV <sup class="text-danger">*</sup></label>
                      <input type="file" name="resume[]" id="resume" value="<?php echo set_value('resume'); ?>" placeholder="CV *" class="form-control  " onchange="validateFile(event, 'resume-error', 'image_upload_resume_preview', '5130kb')" required maxlength="255" minlength="5" />
                      <label id="resume-error" class="error" for="resume"></label>
                      <!-- <label id="error_resume_size" class="error"></label> -->
                      <note class="form_note" id="resume_err">Note: Please upload JPEG/PDF file only up to 5MB in size</note>
                      <?php if(form_error('resume')!=""){ ?> <div class="clearfix"></div><label id="error_resume_size" class="error"><?php echo form_error('resume'); ?></label> <?php } ?>
                    </div>					
                  </div>
                  <hr style="display:none; background-color: darkgray;" class="col-md-12 seprateline"></hr>
              </div>
              
            </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center addmorebtndiv" id="" style="display:none;    margin-bottom: 3%;">
                  <a href="javascript:void(0);" class="btn btn-info addmoresectionbtn" >Add More</a>	
                </div>
                
               
                <div class="col-xl-6 col-lg-6"><?php /* Title Of Research Proposal */ ?>
                  <div class="form-group">
                    <label for="title_research_proposal" class="form_label"> Title of Research Proposal <sup class="text-danger">*</sup></label>
                    <input type="text" name="title_research_proposal" id="title_research_proposal" value="<?php echo set_value('title_research_proposal'); ?>" placeholder=" Title of Research Proposal *" class="form-control  " required maxlength="255" 
                    />
                    <note class="form_note" id="title_research_proposal_err">Note: Please enter only up to 255 characters</note>
                    <?php if(form_error('title_research_proposal')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('title_research_proposal'); ?></label> <?php } ?>
                  </div>					
                </div>


                <div class="col-xl-6 col-lg-6"><?php /* Major Objectives of Research */ ?>
                  <div class="form-group">
                    <label for="objectives" class="form_label"> Major Objectives of Research <sup class="text-danger">*</sup></label>
                    <input type="text" name="objectives" id="objectives" value="<?php echo set_value('objectives'); ?>" placeholder=" Major Objectives of Research *" class="form-control  " required maxlength="255" 
                    />
                    <note class="form_note" id="objectives_err">Note: Please enter only up to 255 characters</note>
                    <?php if(form_error('objectives')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('objectives'); ?></label> <?php } ?>
                  </div>					
                </div>
                <div class="col-xl-6 col-lg-6"><?php /* theme */ ?>
                  <div class="form-group">
                    <label for="theme" class="form_label">Theme <sup class="text-danger">*</sup></label>
                    <select name="theme" id="theme" placeholder="Theme*" class="form-control custom_input" required>
                      <option value="">Select</option>
                      <option value="Effectiveness of Credit Guarantee Schemes: India in a Cross-Country Setting">Effectiveness of Credit Guarantee Schemes: India in a Cross-Country Setting</option>
                      <option value="Changing Dimension and Patterns of Financial Savings in India">Changing Dimension and Patterns of Financial Savings in India</option>
                      <option value="Effectiveness of Deposit Insurance Systems in Emerging Markets and developed countries with a Special Reference to India">Effectiveness of Deposit Insurance Systems in Emerging Markets and developed countries with a Special Reference to India</option>
                      <option value="Transformation in the Indian NBFC Sector: Prospects & Challenges">Transformation in the Indian NBFC Sector: Prospects & Challenges</option>
                      <option value="Business Correspondents Model: Gateway to Financial Inclusion and Social Outreach">Business Correspondents Model: Gateway to Financial Inclusion and Social Outreach</option>
                      
                    </select>
                    <label id="theme-error" class="error" for="theme"></label>
                    <?php if(form_error('theme')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('theme'); ?></label> <?php } ?>
                  </div>					
                </div>
                <div class="col-xl-6 col-lg-6"><?php /* Proposal */ ?>
                  <div class="form-group">
                    <label for="proposal" class="form_label">Proposal <sup class="text-danger">*</sup></label>
                    <input type="file" name="proposal" id="proposal" value="<?php echo set_value('proposal'); ?>" placeholder="Proposal *" class="form-control  " onchange="validateFile(event, 'proposal-error', 'image_upload_proposal_preview', '5130kb','','','.docx')" required maxlength="255" minlength="5" />
                    <label id="proposal-error" class="error" for="proposal"></label>
                    <!-- <label id="error_proposal_size" class="error"></label>  -->
                    <note class="form_note" id="proposal_err">Note: Please upload MS-Word file only up to 5MB in size</note>
                    <?php if(form_error('proposal')!=""){ ?> <div class="clearfix"></div><label id="error_proposal_size" class="error"><?php echo form_error('proposal'); ?></label> <?php } ?>
                  </div>					
                </div>
                  <div class="col-xl-6 col-lg-6"><?php /* Code */ ?>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-6">
                          <label for="iibf_macroresearch_application_captcha" class="form_label">Code <sup class="text-danger">*</sup></label>
                          <input type="text" name="iibf_macroresearch_application_captcha" id="iibf_macroresearch_application_captcha" class="form-control" placeholder="Code *" required autocomplete="off" value="" />
                          <?php if (form_error('iibf_macroresearch_application_captcha') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('iibf_macroresearch_application_captcha'); ?></label> <?php } ?>
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
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id=""></br></br>
              <i>Note: In case of any query, please write to us at se.ra-aca@iibf.org.in </i>
            </div>
          </div>
        </form>
      </div>
    </div>
    
    
    <?php $this->load->view('macroresearch/inc_footer'); ?>
        
    <?php $this->load->view('macroresearch/common/inc_common_validation_all'); ?>
    <?php $this->load->view('macroresearch/common/inc_common_show_hide_password'); ?>
    
    <script type="text/javascript">
      $(document).ready(function(){
        //$('#add_form').on('submit', function(e){
          
          
    });

      function refresh_captcha() 
      {
        $("#page_loader").css("display", "block");
        $("#iibf_macroresearch_application_captcha").val("");
        $.ajax({
          type: "POST",
          url: "<?php echo site_url('macroresearch/application/refresh_captcha'); ?>",
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
          onkeyup: function(element) { $(element).valid();
            $('.displayerrors').html('');
           },          
          rules:
          {
            candidate_name:{ required: true, allow_only_alphabets_and_space:true, maxlength:160 , remote: { url: "<?php echo site_url('macroresearch/application/check_eligibility/0/1'); ?>", type: "post" } },
           
            email:{ required: true, maxlength:75 },
            dob:{ required: true},
            
            address:{required: true, maxlength:255 },
            nature_of_job:{  required: true,maxlength:100 },
            theme:{ required: true },
            pdc_zone:{ required: true },  
            forwarding_letter:{ required: true },  
            proposal:{ required: true },  
            resume:{ required: true },  
            designation:{ required: true,  maxlength:30 }, 
            employer: { required: true, maxlength:160 }, 
            
            institute_name: {  maxlength:255 }, 
            //project_coordinator: {  maxlength:255 }, 
            
            title_research_proposal: { required: true,  maxlength:255 }, 
            mobile:{ required: true, allow_only_numbers:true, first_zero_not_allowed:true, maxlength:10, minlength:10, remote: { url: "<?php echo site_url('macroresearch/application/validation_check_mobile_exist/0/1'); ?>", type: "post" } },            
              
            
            iibf_macroresearch_application_captcha: { required: true, remote: { url: "<?php echo site_url('macroresearch/application/validation_check_captcha/0/1'); ?>", type: "post" } }            
          },
          messages:
          {
            candidate_name: { required: "Please enter the Full name" , remote: "We regret to inform you that you are not eligible to participate in this scheme" },
            
            email: { required: "Please enter the Email ID", valid_email: "Please enter the valid email id", remote: "The email id is already exist" },
            dob: { required: "Please Select Date Of Birth" },
            
            address: { },
            nature_of_job: {required: "Please select the Nature of Job"  },
            theme: { required: "Please select the Theme" },
            designation: { required: "Please enter the designation" },
            employer: { required: "Please enter the employer" },
            institute_name: { required: "Please enter the Institute Name" },
            project_coordinator: { required: "Please enter the Project Coordinator Name" },
            
            title_research_proposal: { required: "Please enter the Title Of Research Proposal" },
            objectives: { required: "Please enter the Objectives" },
            mobile: { required: "Please enter the mobile number", minlength: "Please enter 10 numbers in mobile number", maxlength: "Please enter 10 numbers in mobile number", remote: "The mobile number is already exist" },
            forwarding_letter: { required: "Please enter the Forwarding Letter" },
            proposal: { required: "Please upload proposal letter" },
            resume: { required: "Please enter the Resume" },
            
           
            iibf_macroresearch_application_captcha: { required: "Please enter the code", remote: "Please enter the valid code" }
          }, 
          errorPlacement: function(error, element) // For replace error 
          {
            if (element.attr("name") == "candidate_name") { error.insertAfter("#candidate_name_err"); }
            else if (element.attr("name") == "dob") { error.insertAfter("#dob_err"); }
            else if (element.attr("name") == "email") { error.insertAfter("#email_err"); }
            else if (element.attr("name") == "mobile") { error.insertAfter("#mobile_err"); }
            else if (element.attr("name") == "address") { error.insertAfter("#address_err"); }
            else if (element.attr("name") == "nature_of_job") { error.insertAfter("#nature_of_job_err"); }
            else if (element.attr("name") == "theme") { error.insertAfter("#theme_err"); }
            else if (element.attr("name") == "designation") { error.insertAfter("#designation_err"); }
            else if (element.attr("name") == "employer") { error.insertAfter("#employer_err"); }
            else if (element.attr("name") == "is_active") { error.insertAfter("#is_active_err"); }
            else { error.insertAfter(element); }
          },          
          submitHandler: function(form) 
          {          
            $("#page_loader").hide();
            swal({ title: "Please confirm", text: "Please confirm to submit the details?", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function (e) 
            { 
              $("#page_loader").show();  
              var form_type = $('#application_type').val();

              //$("#submit_btn_outer").html('<button class="btn btn-primary" type="button" >Submit</button> <a class="btn btn-danger" href="<?php echo site_url('macroresearch/application'); ?>">Back</a>');
              
              if($('input[name^="receive_acknowledge"]:checked').length <= 0 && form_type != 'Individual') {
                $("#page_loader").hide();
                $('.displayerrors').html('Please select any one acknowledgement checkbox').show();
                        //$('.displayerrors').focus();
                        $('html, body').animate({
                            scrollTop: $('.admin_login_form').offset().top
                        }, 1000);
                        return false;
              }
              
              //form.submit();
                //e.preventDefault(); // Prevent default form submission
                var formData = new FormData($('#add_form')[0]); // Create form data object
                
                $.ajax({
                    url: '<?= base_url("macroresearch/application/submit") ?>', // Your controller's submit URL
                    type: 'POST',
                    data: formData,
                    processData: false, // Required for FormData
                    contentType: false, // Required for FormData
                    success: function(funcresponse){
                      $("#page_loader").hide();
                      var data = JSON.parse(funcresponse); 
                      if(data.status=='error') {
                        $('.displayerrors').html(data.message).show();
                        //$('.displayerrors').focus();
                        $('html, body').animate({
                            scrollTop: $('.admin_login_form').offset().top
                        }, 1000);

                      }
                      else {
                        //alert(data.message); // Handle success
                        sweet_alert_success(data.message);
                        setTimeout(function() {
                              location.reload();
                          }, 3000);
                        
                      }
                        
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                      $("#page_loader").hide();
                      //alert('ok2');
                        alert("Error: " + textStatus); // Handle error
                    }
                });
            
                
            }); 
            
          }
        });
      });
  $("input#candidate_name").each(function() {
    
      $(this).focusout(function(){
        if($(this).val()!='') {
          
          check_eligibility($(this));
        }
      });
    
    
  });
function check_eligibility(elem) {
   var candidate_name = $(elem).val();
   $.ajax({
          type: "POST",
          url: "<?php echo site_url('macroresearch/application/check_eligibility/0/1'); ?>",
          data: {candidate_name:candidate_name},
          success: function(res) 
          {
            if(res=='true') {
              $("input:visible").each(function() {
     
              $(this).removeAttr('disabled','disabled');
              });
              $("button").removeAttr('disabled','disabled');
            }
            else {
              $("input:visible").each(function() {
     
                $(this).attr('disabled','disabled');
              });
              $("button").attr('disabled','disabled');
              alert('We regret to inform you that you are not eligible to participate in this scheme');
            }
            $(elem).removeAttr('disabled','disabled');
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
         
            console.log('AJAX request failed: ' + errorThrown);
            sweet_alert_error("Error occurred. Please try again.");
            $("#page_loader").hide();
            
          }
        });


  }

  function nature_of_job_func(elem) {
   var selectedVal = $(elem).val();
   if(selectedVal!='Regular' && selectedVal!='') { 
    /*$(elem).closest('.addmoresection ').find("input").each(function() {
     
      $(this).attr('disabled','disabled');
    });*/
    $("input:visible").each(function() {
     
     $(this).attr('disabled','disabled');
   });
   $("#theme").each(function() {
     
     $(this).attr('disabled','disabled');
   });
    //$("a").attr('disabled','disabled');
    $("button").attr('disabled','disabled');
    alert('We regret to inform you that you are not eligible to participate in this scheme');
   }
   else {
    var enableFields = 1;
    
    $("select#nature_of_job").each(function() {
     if($(this).val()!='Regular' && $(this).val()!='') {
      
      enableFields=0;
     }
   });
   $("input:visible").each(function() {
     
    $(this).removeAttr('disabled','disabled');
   });
   $('#theme').removeAttr('disabled','disabled');
    //  $("a").removeAttr('disabled','disabled');
    if(enableFields==1)
      $("button").removeAttr('disabled','disabled');
   }

  }
  $(".application_type").change(function(){
    setCookie('IIBFMACRORESEARCH',$(".application_type").val(),1);
    location.reload();
  });
      if(!getCookie('IIBFMACRORESEARCH')) {
        setCookie('IIBFMACRORESEARCH','Individual',1);
      }
  var IIBFMACRORESEARCH = getCookie('IIBFMACRORESEARCH');
  if(IIBFMACRORESEARCH=='Individual') {
    $('.addmorebtndiv').remove();
    $('.seprateline').remove();
    $('.removesection').remove();
    $('.receive_acknowledge_span').hide();
    $('.institute_name_div').hide();
    $('.candidate_name_label').html('Full Name <sup class="text-danger">*</sup>');
    
  }
  if(IIBFMACRORESEARCH=='Joint') {
    $('.addmorebtndiv').show();
    $('.addmoresection').attr('style','margin-bottom: 3%;');
    $('.seprateline').show();
    $('.receive_acknowledge_span').show();
    $('.institute_name_div').hide();
    $('.candidate_name_label').html('Full Name <sup class="text-danger">*</sup>');
    //$('.project_coordinator_div').hide();
    
  }
  if(IIBFMACRORESEARCH=='Institute') {
    $('.addmorebtndiv').show();
    $('.addmoresection').attr('style','margin-bottom: 3%;');
    $('.seprateline').show();
    $('.receive_acknowledge_span').show();
    $('.institute_name_div').show();
    $('.institute_name_div').find('input').attr('required','required');
    //$('.project_coordinator_div').show();
    //$('.project_coordinator_div').find('input').attr('required','required');
    $('.candidate_name_label').html('Project Co-ordinator Name <sup class="text-danger">*</sup>');
    $('#candidate_name').attr('placeholder','Project Co-ordinator Name *');
    
    
  }
  
  //$(".removesection").each(function() {
    $(document).on("click",".removesection",function() {
      //alert('clicked');
      $(this).closest('.addmoresection').remove();
    });
  //});
  <?php  $minYear = (date('Y')-58);
   $maxYear = (date('Y')-18);
   ?>
  $(".date_field").each(function() {
            var date_field = $(this).datepicker({ keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy-mm-dd",  clearBtn: true, startDate:"<?php echo date($minYear.'-12-31') ?>", endDate:"<?php echo date($maxYear.'-m-d') ?>" });
      
          });
          
  $(".addmoresectionbtn").click(function(){
    
    var divcount = $('.addmoresection').length;

    if(divcount<=4) {
        var template = $('.addmoresection').first().clone();
        var index = $('.addmoresection').length;
        
        template.find('select').removeClass('error');
        template.find('label.error').remove();
        template.find('.removesection').show();

        template.find("input").each(function(eachindex) {
          //might as well set value here since
          //we are looping anyway
          var receive_acknowledge_field = divcount; 
          if ( $(this).attr('type') == 'checkbox' ) 
          {
            $(this).prop('checked', false);
            $(this).attr('name','receive_acknowledge['+receive_acknowledge_field+']');
          } 
          else 
          {
            this.value = "";
          }

          $(this).attr('id', 'item-' +index+ (eachindex + 1)).removeClass('error');
          $(this).prev('label').attr('for', 'item-' +index+ (eachindex + 1));
          
          // Dynamically add validateFile function with new parameters
          if ($(this).attr('type') == 'file') {



            if ( $(this).attr('name') == 'forwarding_letter[]' ) {
              var parameter = 'image_upload_forwarding_letter_preview';
            } else if( $(this).attr('name') == 'resume[]' ) {
              var parameter = 'image_upload_resume_preview';
            }

            var errorId      = 'item-'+index + (eachindex + 1)+'-error';
            var errorFieldId = 'item-'+index + (eachindex + 1);

            let errorLabel =  $('<label>', {
                                id: errorId,
                                class: 'error',
                                for: errorFieldId,
                                text: ''
                              });
            
            $(this).after(errorLabel);
            $(this).attr('onchange', "validateFile(event, '" + errorId + "', '" + parameter + "', '5000kb')");
          }
        });
        
       // template.find('label').removeAttr('for');
        $('.multiplesections').append(template);
        $(".date_field").each(function() {
            var date_field = $(this).datepicker({ keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy-mm-dd",  clearBtn: true, startDate:"<?php echo date('1966-12-31') ?>", endDate:"<?php echo date('2006-m-d') ?>"  });
         
            $(this).attr('onchange',"validate_input('"+$(this).attr('id')+"')");
          });
          $(document).on('change', '.date_field', function() {
            $(this).trigger('keyup');
            var dob = $(this).val();

          });
          
        //$(".addmoresection").clone().find(':input').attr('value','').find('select').val('').insertAfter("div.addmoresection:last");
      }
      else
        swal({
            title: "Limit Exceeded!",
            text: "You have exceeded the maximum limit of candidates allowed to add.",
            icon: "warning",
            button: "OK",
        });
  });
  function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
  }
  function getCookie(name) {
      var nameEQ = name + "=";
      var ca = document.cookie.split(';');
      for(var i=0;i < ca.length;i++) {
          var c = ca[i];
          while (c.charAt(0)==' ') c = c.substring(1,c.length);
          if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
      }
      return null;
  }
  function eraseCookie(name) {   
      document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
  }
 function validateFile(event, error_id, show_img_id, size, img_width, img_height,passedallowedFiles='') {
  var srcid = event.srcElement.id;
  if (document.getElementById(srcid).files.length != 0) 
  {
    
    var file = document.getElementById(srcid).files[0];
    $('#' + error_id).text('');
    if (file.size == 0) {
      $('#' + error_id).text('Please select valid file');
      $('#' + document.getElementById(srcid).id).val('')
      $('#' + show_img_id).attr('src', "/assets/images/default1.png");
    }
    else {
      var file_size = document.getElementById(srcid).files[0].size / 1024;
      var mimeType = document.getElementById(srcid).files[0].type;

      var allowedFiles = [".jpg", ".jpeg",".pdf",'.docx'];
      if(passedallowedFiles!='')
      var allowedFiles = [passedallowedFiles];
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
          $('#' + error_id).show();
          $('#' + document.getElementById(srcid).id).val('')
          $('#' + show_img_id).attr('src', "/assets/images/default1.png");
        }
        else {
        
          if (!regex.test(file.name.toLowerCase())) {
            $('#' + error_id).text("Please upload " + allowedFiles.join(', ') + " only up to 5MB in size");
            $('#' + error_id).show();
            $('#' + document.getElementById(srcid).id).val('')
            $('#' + show_img_id).attr('src', "/assets/images/default1.png");
          }
          else {
            //alert(file_size+'=='+check_size[0]);
            if (file_size > check_size[0]) {
            
              $('#' + error_id).text("Please upload file less than 5MB" );
              $('#' + error_id).show();
              $('#' + document.getElementById(srcid).id).val('')
              $('#' + show_img_id).attr('src', "/assets/images/default1.png");
            }
            else if (file_size < 8) //IF FILE SIZE IS LESS THAN 8KB
            {
              $('#' + error_id).text("Please upload file having size more than 8KB");
              $('#' + error_id).show();
              $('#' + document.getElementById(srcid).id).val('')
              $('#' + show_img_id).attr('src', "/assets/images/default1.png");
            }
            else {
              img.onload = function () {
                var width = this.width;
                var height = this.height;

                if (width > img_width && height > img_height) {
                  $('#' + error_id).text(' Uploaded File dimensions are ' + width + '*' + height + ' pixel. Please Upload file dimensions between ' + img_width + '*' + img_height + ' pixel');
                  $('#' + error_id).show();
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
    <?php $this->load->view('macroresearch/common/inc_bottom_script'); ?>
  </body>
</html>