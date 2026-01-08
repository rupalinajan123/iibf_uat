<!DOCTYPE html>
<html>
  <head>
    <?php $this->load->view('supervision/inc_header'); ?>
  </head>
  
  <body >
    <?php $this->load->view('supervision/common/inc_loader'); ?>
    <div class="d-flex logo"><img src="<?php echo base_url('assets/supervision/images/iibf_logo.png'); ?>" class="img-fluid" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">   <h3 class="mb-0">INDIAN INSTITUTE OF BANKING & FINANCE - Exam Supervision</h3></div>
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
          
          <form class="m-t" action="<?php echo site_url('supervision/login'); ?>" method="post" enctype="multipart/form-data" id="supervision_login_form" autocomplete="off">
            <div class="form-group">
              <label for="supervision_username" class="form_label">Username <sup class="text-danger">*</sup></label>
              <input type="text" name="supervision_username" id="supervision_username" value="<?php if (set_value('supervision_username') != "") { echo set_value('supervision_username'); } else if (isset($COOKIE_SUPERVISION_USERNAME) && $COOKIE_SUPERVISION_USERNAME != "") { echo $COOKIE_SUPERVISION_USERNAME; } ?>" class="form-control" placeholder="Username *" required maxlength="50" />
              <?php if (form_error('supervision_username') != "") { ?><div class="clearfix"></div> <div class="ci_error_msg"><?php echo form_error('supervision_username'); ?></div><?php } ?>
            </div>
            
            <div class="form-group">
              <label for="supervision_password" class="form_label">Password <sup class="text-danger">*</sup></label>
              <div class="login_password_common">
                <input type="password" name="supervision_password" id="supervision_password" value="<?php if (set_value('supervision_password') != "") { echo set_value('supervision_password'); } else if (isset($COOKIE_SUPERVISION_PASSWORD) && $COOKIE_SUPERVISION_PASSWORD != "") { echo $COOKIE_SUPERVISION_PASSWORD; } ?>" class="form-control" placeholder="Password *" autocomplete="off" required maxlength="20" />
                <?php if (form_error('supervision_password') != "") { ?><div class="clearfix"></div> <div class="ci_error_msg"><?php echo form_error('supervision_password'); ?></div><?php } ?>
                
                <span class="show-password" onclick="show_hide_password(this,'show', 'supervision_password')"><i class="fa fa-eye" aria-hidden="true"></i></span>
                <span class="hide-password" onclick="show_hide_password(this,'hide', 'supervision_password')" style="display:none;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
              </div>
            </div>
            
            <?php /* echo get_ip_address(); */
            /* if(get_ip_address() == '115.124.115.69' || get_ip_address() == '115.124.115.75') 
            { ?>
              <input type="hidden" name="supervision_captcha" id="supervision_captcha" class="form-control" placeholder="Code *" required autocomplete="off" value="<?php echo $_SESSION['SUPERVISION_LOGIN_CAPTCHA']; ?>" />
            <?php } 
            else */
            { ?>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label for="supervision_captcha" class="form_label">Code <sup class="text-danger">*</sup></label>
                    <input type="text" name="supervision_captcha" id="supervision_captcha" class="form-control" placeholder="Code *" required autocomplete="off" value="" />
                    <?php if (form_error('supervision_captcha') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('supervision_captcha'); ?></label> <?php } ?>
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
            <?php } ?>
            
            <div class="form-group">
              <div class="text-right">
                <label class="css_checkbox_radio d-block text-left check-bx my-3"> &nbsp;Remember Me
                  <input type="checkbox" id="supervision_remember_me" name="supervision_remember_me" <?php echo set_checkbox('supervision_remember_me', '1'); ?> <?php if ( isset($COOKIE_SUPERVISION_USERNAME) && $COOKIE_SUPERVISION_USERNAME != "" && isset($COOKIE_SUPERVISION_PASSWORD) && $COOKIE_SUPERVISION_PASSWORD != "") { echo "checked"; } ?> value="1">
                  <span class="checkmark"></span>
                </label>
              </div>
            </div>
            
            <button type="submit" name="login" class="btn btn-primary block full-width btn-login">Login</button>
          </form>
        </div>
      </div>
    </div>

    <?php /* $server_ip = $_SERVER['SERVER_ADDR'];
      echo "<br>SERVER_ADDR IP Address: $server_ip";
     
      $app_server = explode('.',gethostname());
      if(isset($app_server[0])){ echo "<br>".$app_server[0];}
      echo "<br><br>"; */ ?>
    
    
    <?php $this->load->view('supervision/inc_footer'); ?>
    <?php /* $this->load->view('supervision/common/inc_common_validation_all'); */ ?>
    
    <script type="text/javascript" src="<?php echo auto_version(base_url('assets/supervision/jquery_validation/jquery.validate.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo auto_version(base_url('assets/supervision/jquery_validation/jquery.validate_additional.js')); ?>"></script>
    
    <script type="text/javascript">
      function refresh_captcha() 
      {
        $("#page_loader").css("display", "block");
        $("#supervision_captcha").val("");
        $.ajax({
          type: "POST",
          url: "<?php echo site_url('supervision/login/refresh_captcha'); ?>",
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
        
        $("#supervision_login_form").validate(
        {
          onblur: function(element) { $(element).valid(); },
          rules: 
          { 
            supervision_username: { required: true, remote: { url: "<?php echo site_url('supervision/login/validation_check_username/0/1'); ?>", type: "post" } },
            supervision_password: { required: true, remote: { url: "<?php echo site_url('supervision/login/validation_check_login_details/0/1'); ?>", type: "post", data: { "supervision_username": function() { return $("#supervision_username").val() } } } },
            supervision_captcha: { required: true, remote: { url: "<?php echo site_url('supervision/login/validation_check_captcha/0/1'); ?>", type: "post" } }
          },
          messages: 
          {
            supervision_username: { required: "Please enter the username", remote: "Please enter the valid username" },
            supervision_password: { required: "Please enter the password", remote: "Please enter valid password" },
            supervision_captcha: { required: "Please enter the code", remote: "Please enter the valid code" }
          },
          submitHandler: function(form) 
          {
            $("#page_loader").show();
            form.submit();
          }
        });
      });
    </script>
    <?php $this->load->view('supervision/common/inc_bottom_script'); ?>
  </body>
</html>