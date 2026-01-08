<!DOCTYPE html>
<html>
  <head>
    <?php $this->load->view('ncvet/inc_header'); ?>
    <style>
      .admin_login_form { min-width:450px; max-width:600px; }
    </style>
  </head>
  
  <body >
    <?php $this->load->view('ncvet/common/inc_loader'); ?>
    <div class="d-flex logo"><img src="<?php echo base_url('assets/ncvet/images/iibf_logo.png'); ?>" class="img-fluid" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">   <h3 class="mb-0">INDIAN INSTITUTE OF BANKING & FINANCE - NCVET Candidate Login</h3></div>
    <div class="bcbf_wrap"> 
      <div class="half-circle"></div>
      <div class="container">        
       
        <div class="admin_login_form animated fadeInDown">
          <?php
            $show_msg = '';
            $alert_cls = 'warning';
            if ($error) { $show_msg = $error; $alert_cls = 'danger'; }
            if ($this->session->flashdata('error')) { $show_msg = $this->session->flashdata('error'); $alert_cls = 'danger'; }
            
            if ($show_msg != "")
            { ?>
            <div class="mt-3 alert alert-<?php echo $alert_cls; ?> alert-dismissible" role="alert" id="alert_fadeout">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <?php echo $show_msg; ?>
            </div>
          <?php } ?>
          
          <form class="m-t" action="<?php echo site_url('ncvet/candidate/login_candidate'); ?>" method="post" enctype="multipart/form-data" id="ncvet_candidate_login_form" autocomplete="off">
            <input type="hidden" name="current_form_action" id="current_form_action" value="login_form">
            <div class="form-group">
              <label for="ncvet_candidate_registration_number" class="form_label">Enrollment Number <sup class="text-danger">*</sup></label>
              <input type="text" name="ncvet_candidate_registration_number" id="ncvet_candidate_registration_number" value="<?php if (set_value('ncvet_candidate_registration_number') != "") { echo set_value('ncvet_candidate_registration_number'); } ?>" class="form-control allow_only_numbers" placeholder="Enrollment Number *" required maxlength="10" onchange="validate_input('ncvet_candidate_registration_number')"/>
              
              <?php if (form_error('ncvet_candidate_registration_number') != "") { ?><div class="clearfix"></div> <div class="ci_error_msg"><?php echo form_error('ncvet_candidate_registration_number'); ?></div><?php } ?>
            </div><div class="clearfix"></div>

            <!-- <div class="form-group">
              <label for="ncvet_candidate_email_mobile" class="form_label">Registered Email / Mobile <sup class="text-danger">*</sup></label>
              <input type="text" name="ncvet_candidate_email_mobile" id="ncvet_candidate_email_mobile" value="<?php if (set_value('ncvet_candidate_email_mobile') != "") { echo set_value('ncvet_candidate_email_mobile'); } ?>" class="form-control" placeholder="Registered Email / Mobile *" required maxlength="100" onchange="validate_input('ncvet_candidate_email_mobile')"/>
              
              <a href="<?php echo site_url('ncvet/candidate/login_candidate'); ?>" id="reset_username">Reset</a>
              
              <?php if (form_error('ncvet_candidate_email_mobile') != "") { ?><div class="clearfix"></div> <div class="ci_error_msg"><?php echo form_error('ncvet_candidate_email_mobile'); ?></div><?php } ?>
            </div><div class="clearfix"></div> -->

            <div class="form-group">
              <label for="password" class="form_label">Password <sup class="text-danger">*</sup></label>
              <input type="password" name="password" id="password" value="<?php if (set_value('password') != "") { echo set_value('password'); } ?>" class="form-control" placeholder="Password *" required maxlength="50" onchange="validate_input('password')"/>
              
              <a href="<?php echo site_url('ncvet/candidate/login_candidate'); ?>" id="reset_username">Reset</a>
              
              <?php if (form_error('password') != "") { ?><div class="clearfix"></div> <div class="ci_error_msg"><?php echo form_error('password'); ?></div><?php } ?>
            </div><div class="clearfix"></div>
            
            <div id="login_captcha_outer">
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label for="ncvet_captcha" class="form_label">Code <sup class="text-danger">*</sup></label>
                    <input type="text" name="ncvet_captcha" id="ncvet_captcha" class="form-control" placeholder="Code *" required autocomplete="off" value="" />
                    <?php if (form_error('ncvet_captcha') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('ncvet_captcha'); ?></label> <?php } ?>
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
              <button type="submit" name="login" class="btn btn-primary block full-width btn-login">Login</button>
            </div>
            
            <!-- <div id="otp_screen_outer">
              <div class="form-group mb-0">
                <label for="ncvet_enter_otp" class="form_label">Enter OTP <sup class="text-danger">*</sup></label>

                <input type="text" class="form-control" placeholder="Enter OTP *" name="ncvet_enter_otp" id="ncvet_enter_otp" required maxlength="6" minlength="6" value="<?php if (set_value('ncvet_enter_otp') != "") { echo set_value('ncvet_enter_otp'); } ?>" />

                <?php if(form_error('ncvet_enter_otp')!=""){ ?><div class="clearfix"></div><label class="error"><?php echo form_error('ncvet_enter_otp'); ?></label><?php } ?>

                <div style="min-height:20px;">
                  <span id="mobile_timer" class="mb-2 mt-1"></span>
                
                  <button type="button" class="btn btn-primary mb-2 mt-1" name="resend_otp" id="resend_otp" onclick="send_resend_otp()" >Resend OTP</button>
                </div>
                  
              </div>
              <button type="submit" name="verify_otp" class="btn btn-primary block full-width btn-login">Verify OTP</button>
            </div> -->

          </form>
        </div>
      </div>
    </div>
    
    <?php $this->load->view('ncvet/inc_footer'); ?>
    <?php $this->load->view('ncvet/common/inc_common_validation_all'); ?>
    
    <script type="text/javascript">
      function refresh_captcha() 
      {
        $("#page_loader").css("display", "block");
        $("#ncvet_captcha").val("");
        $.ajax(
        {
          type: "POST",
          url: "<?php echo site_url('ncvet/candidate/login_candidate/refresh_captcha'); ?>",
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

      function validate_input(input_id) { $("#"+input_id).valid(); }
      $(document).ready(function() 
      {
        // $.validator.addMethod("validate_otp_custom", function(value, element)
        // {
        //   if($.trim(value).length == 0)
        //   {
        //     return true;
        //   }
        //   else
        //   {
        //     var isSuccess = false;
        //     var ncvet_enter_otp = $.trim(value);
        //     var ncvet_candidate_registration_number = $("#ncvet_candidate_registration_number").val();
        //     var ncvet_candidate_email_mobile = $("#ncvet_candidate_email_mobile").val();

        //     parameters= { 'ncvet_enter_otp':ncvet_enter_otp, 'ncvet_candidate_registration_number':ncvet_candidate_registration_number, 'ncvet_candidate_email_mobile':ncvet_candidate_email_mobile, }

        //     $.ajax(
        //     {
        //       type: "POST",
        //       url: "<?php echo site_url('ncvet/candidate/login_candidate/validation_validate_otp/0/1'); ?>",
        //       data: parameters,
        //       async: false,
        //       cache : false,
        //       dataType: 'JSON',
        //       success: function(data)
        //       {
        //         if($.trim(data.flag) == 'success')
        //         {
        //           isSuccess = true;
        //         }
                
        //         $.validator.messages.validate_otp_custom = data.response;
        //       }
        //     });
            
        //     return isSuccess;
        //   }
        // }, '');

        $("#ncvet_candidate_login_form").validate(
        {
          onkeyup: function(element) { $(element).valid(); },
          rules: 
          { 
            ncvet_candidate_registration_number: { required: true, allow_only_numbers:true, remote: { url: "<?php echo site_url('ncvet/candidate/login_candidate/validation_check_registration_number/0/1'); ?>", type: "post" } },
            // ncvet_candidate_email_mobile: { required: true, remote: { url: "<?php echo site_url('ncvet/candidate/login_candidate/validation_check_email_mobile/0/1'); ?>", type: "post" } },
            // ncvet_captcha: { required: true, remote: { url: "<?php echo site_url('ncvet/candidate/login_candidate/validation_check_captcha/0/1'); ?>", type: "post" } },
            // ncvet_enter_otp: { required: true, allow_only_numbers:true, maxlength:6, minlength:6, validate_otp_custom:true }
            password: { required: true, maxlength:50, minlength:1}
          },
          messages: 
          {
            ncvet_candidate_registration_number: { required: "Please enter the Enrollment Number", remote: "Please enter the valid Enrollment Number" },
            // ncvet_candidate_email_mobile: { required: "Please enter the Registered Email / Mobile", remote: "Please enter the valid Registered Email / Mobile" },
            // ncvet_captcha: { required: "Please enter the code", remote: "Please enter the valid code" },
            // ncvet_enter_otp: { required: "Please enter the OTP", minlength: "Please enter 6 numbers in OTP", maxlength: "Please enter 6 numbers in OTP" },
            password: { required: "Please enter the Password" },
          },
          submitHandler: function(form) 
          {
            let current_form_action = $("#current_form_action").val();
            if(current_form_action == 'login_form')
            {
              $("#page_loader").show();
              form.submit();
              // send_resend_otp();
            }
            // else if(current_form_action == 'otp_form')
            // {
            //   $("#page_loader").show();
            //   form.submit();
            // }
          }
        });
      });

      // function send_resend_otp()
      // {
      //   $("#ncvet_enter_otp").val('');
      //   $("#ncvet_candidate_login_form").validate().resetForm();
      //   $("#ncvet_candidate_login_form").find('.error').removeClass('error');

      //   let current_form_action = $("#current_form_action").val();
      //   let ncvet_candidate_registration_number = $("#ncvet_candidate_registration_number").val();
      //   let ncvet_candidate_email_mobile = $("#ncvet_candidate_email_mobile").val();

      //   $("#page_loader").show();
      //   parameters= { 'current_form_action': current_form_action, 'ncvet_candidate_registration_number': ncvet_candidate_registration_number, 'ncvet_candidate_email_mobile': ncvet_candidate_email_mobile }
      //   $.ajax(
      //   {
      //     type: "POST",
      //     url: "<?php echo site_url('ncvet/candidate/login_candidate/send_resend_otp'); ?>",
      //     data: parameters,
      //     dataType: 'JSON',
      //     success: function(data) 
      //     {
      //       if(data.flag == "success")
      //       {
      //         resend_countdown(data.resend_time_sec);

      //         $("#login_captcha_outer").hide();
      //         $("#otp_screen_outer").show();
      //         $("#current_form_action").val('otp_form');
      //         $("#mask_email").html(data.mask_email);
      //         $("#mask_mobile").html(data.mask_mobile);
      //         $("#ncvet_candidate_registration_number").attr('readonly',true);
      //         $("#ncvet_candidate_email_mobile").attr('readonly',true);
      //         $("#reset_username").show();
      //         sweet_alert_success(data.response_msg);
      //         $("#page_loader").hide();
      //       }
      //       else
      //       {alert(22);
      //         sweet_alert_error(data.response_msg);
      //         $("#page_loader").hide();
      //       }
      //     },
      //     error: function(jqXHR, textStatus, errorThrown) 
      //     {
      //       console.log('AJAX request failed: ' + errorThrown);
      //       sweet_alert_error("Error occurred. Please try again.");
      //       $("#page_loader").hide();
      //     }
      //   });
      // }

      // function resend_countdown(timer2) 
      // {
      //   var timer2 = convert(timer2);
        
      //   var interval2 = setInterval(function() 
      //   {
      //     var timer = timer2.split(':');
          
      //     var minutes = parseInt(timer, 10);
      //     var seconds = parseInt(timer[1], 10);
      //     --seconds;
      //     minutes = (seconds < 0) ? --minutes : minutes;
      //     if (minutes < 0) 
      //     {
      //       clearInterval(interval2);
      //       $('#mobile_timer').html("");
      //       $('#resend_otp').show();
      //     } 
      //     else 
      //     {
      //       $('#resend_otp').hide();
      //       seconds = (seconds < 0) ? 59 : seconds;
      //       seconds = (seconds < 10) ? '0' + seconds : seconds;
      //       minutes = (minutes < 10) ? minutes : minutes;
      //       $('#mobile_timer').html("Resend OTP in " + minutes + ':' + seconds);
      //       timer2 = minutes + ':' + seconds;
      //     }
      //   }, 1000);
      // }

      // function convert(value) 
      // {
      //   return Math.floor(value / 60) + ":" + (value % 60 ? value % 60 : '00')
      // }
    </script>
    <?php $this->load->view('ncvet/common/inc_bottom_script'); ?>
  </body>
</html>