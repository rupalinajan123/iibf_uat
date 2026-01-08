<!DOCTYPE html>
<html>
  <head>
    <?php $this->load->view('apabi_india/inc_header'); ?>
  </head>
  
  <body >
    <?php $this->load->view('apabi_india/inc_loader'); ?>
    <div class="d-flex logo">
      <img src="<?php echo base_url('assets/iibfbcbf/images/iibf_logo.png'); ?>" class="img-fluid" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">   
      <h3 class="mb-0">INDIAN INSTITUTE OF BANKING & FINANCE - APABI INDIA Admin</h3>
    </div>
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
          
          <form class="m-t" action="<?php echo site_url('apabi_india_admin/login'); ?>" method="post" enctype="multipart/form-data" id="apabi_admin_login_form" autocomplete="off">
            <div class="form-group">
              <label for="apabi_india_admin_username" class="form_label">Username <sup class="text-danger">*</sup></label>
              <input type="text" name="apabi_india_admin_username" id="apabi_india_admin_username" value="<?php if (set_value('apabi_india_admin_username') != "") { echo set_value('apabi_india_admin_username'); } else if (isset($COOKIE_APABI_INDIA_ADMIN_USERNAME) && $COOKIE_APABI_INDIA_ADMIN_USERNAME != "") { echo $COOKIE_APABI_INDIA_ADMIN_USERNAME; } ?>" class="form-control" placeholder="Username *" required maxlength="50" />
              <?php if (form_error('apabi_india_admin_username') != "") { ?><div class="clearfix"></div> <div class="ci_error_msg"><?php echo form_error('apabi_india_admin_username'); ?></div><?php } ?>
            </div>
            
            <div class="form-group">
              <label for="apabi_india_admin_password" class="form_label">Password <sup class="text-danger">*</sup></label>
              <div class="login_password_common">
                <input type="password" name="apabi_india_admin_password" id="apabi_india_admin_password" value="<?php if (set_value('apabi_india_admin_password') != "") { echo set_value('apabi_india_admin_password'); } else if (isset($COOKIE_APABI_INDIA_ADMIN_PASSWORD) && $COOKIE_APABI_INDIA_ADMIN_PASSWORD != "") { echo $COOKIE_APABI_INDIA_ADMIN_PASSWORD; } ?>" class="form-control" placeholder="Password *" autocomplete="off" required maxlength="20" />
                <?php if (form_error('apabi_india_admin_password') != "") { ?><div class="clearfix"></div> <div class="ci_error_msg"><?php echo form_error('apabi_india_admin_password'); ?></div><?php } ?>
                
                <span class="show-password" onclick="show_hide_password(this,'show', 'apabi_india_admin_password')"><i class="fa fa-eye" aria-hidden="true"></i></span>
                <span class="hide-password" onclick="show_hide_password(this,'hide', 'apabi_india_admin_password')" style="display:none;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
              </div>
            </div>
            
            <div class="form-group">
              <div class="row">
                <div class="col-md-6">
                  <label for="apabi_india_admin_captcha" class="form_label">Code <sup class="text-danger">*</sup></label>
                  <input type="text" name="apabi_india_admin_captcha" id="apabi_india_admin_captcha" class="form-control" placeholder="Code *" required autocomplete="off" value="" />
                  <?php if (form_error('apabi_india_admin_captcha') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('apabi_india_admin_captcha'); ?></label> <?php } ?>
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
            
            <div class="form-group hide">
              <div class="text-right">
                <label class="css_checkbox_radio d-block text-left check-bx my-3"> &nbsp;Remember Me
                  <input type="checkbox" id="apabi_admin_remember_me" name="apabi_admin_remember_me" <?php echo set_checkbox('apabi_admin_remember_me', '1'); ?> <?php if ( isset($COOKIE_APABI_INDIA_ADMIN_USERNAME) && $COOKIE_APABI_INDIA_ADMIN_USERNAME != "" && isset($COOKIE_APABI_INDIA_ADMIN_PASSWORD) && $COOKIE_APABI_INDIA_ADMIN_PASSWORD != "") { echo "checked"; } ?> value="1">
                  <span class="checkmark"></span>
                </label>
              </div>
            </div>
            
            <button type="submit" name="login" class="btn btn-primary block full-width btn-login">Login</button>
          </form>
        </div>
      </div>
    </div>    
    
    <?php $this->load->view('apabi_india/inc_footer'); ?>
        
    <script type="text/javascript" src="<?php echo auto_version(base_url('assets/apabi_india/jquery_validation/jquery.validate.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo auto_version(base_url('assets/apabi_india/jquery_validation/jquery.validate_additional.js')); ?>"></script>
    
    <script type="text/javascript">
      function refresh_captcha() 
      {
        $("#page_loader").css("display", "block");
        $("#apabi_india_admin_captcha").val("");
        $.ajax({
          type: "POST",
          url: "<?php echo site_url('apabi_india_admin/login/refresh_captcha'); ?>",
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
      
      function show_hide_password(this_val,type,password_id)
      {
        var passwordId = password_id;
        if (type=="show") 
        {
          $("#" + passwordId).attr("type", "text");
          $(this_val).parent().find(".show-password").hide();
          $(this_val).parent().find(".hide-password").show();
        }
        else if (type=="hide") 
        {
          $("#" + passwordId).attr("type", "password");
          $(this_val).parent().find(".hide-password").hide();
          $(this_val).parent().find(".show-password").show();
        }
      }
      
      $(document).ready(function() 
      {
        $.validator.addMethod("required", function(value, element) { if ($.trim(value).length == 0) { return false; } else { return true; } });
        
        $("#apabi_admin_login_form").validate(
        {
          onblur: function(element) { $(element).valid(); },
          rules: 
          { 
            apabi_india_admin_username: { required: true, remote: { url: "<?php echo site_url('apabi_india_admin/login/validation_check_username/0/1'); ?>", type: "post" } },
            apabi_india_admin_password: { required: true, remote: { url: "<?php echo site_url('apabi_india_admin/login/validation_check_login_details/0/1'); ?>", type: "post", data: { "apabi_india_admin_username": function() { return $("#apabi_india_admin_username").val() } } } },
            apabi_india_admin_captcha: { required: true, remote: { url: "<?php echo site_url('apabi_india_admin/login/validation_check_captcha/0/1'); ?>", type: "post" } }
          },
          messages: 
          {
            apabi_india_admin_username: { required: "Please enter the username", remote: "Please enter the valid username" },
            apabi_india_admin_password: { required: "Please enter the password", remote: "Please enter valid password" },
            apabi_india_admin_captcha: { required: "Please enter the code", remote: "Please enter the valid code" }
          },
          submitHandler: function(form) 
          {
            $("#page_loader").show();
            form.submit();
          }
        });
      });
    </script>
    <?php $this->load->view('apabi_india/inc_bottom_script'); ?>
  </body>
</html>