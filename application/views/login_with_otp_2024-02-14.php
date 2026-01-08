<!DOCTYPE html>
<html>

<head>
  <?php $this->load->view('google_analytics_script_common'); ?>
  <script>
    var site_url = "<?php echo base_url(); ?>";
  </script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>IIBF - User Login with OTP</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo  base_url() ?>assets/admin/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo  base_url() ?>assets/admin/dist/css/AdminLTE.min.css">

  <script src="<?php echo base_url() ?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
  <style>
    .login-box,
    .register-box {
      border: 1px solid #1287c0;
      width: 90%;
      max-width: 700px;
    }

    .login-logo,
    .register-logo {
      padding: 15px 15px 15px 15px;
      border-bottom: 1px solid #1287c0;
      margin: 0;
    }

    .login-logo .login_text {
      display: inline-block;
      vertical-align: middle;
      margin: 0px 0 0 0;
    }

    .short_logo {
      float: none;
      margin: 0 5px 5px 0;
      display: inline-block;
    }

    .short_logo img {
      border-bottom: 1px solid #03a8fa;
    }

    .login-box-body,
    .register-box-body {
      background: rgba(255, 255, 255, 1);
      padding: 25px 0 25px;
      border-top: 0;
      color: #000;
      width: 90%;
      position: unset;
      left: auto;
      margin: 0 auto;
      max-width: 500px;
    }

    .login-box-body a {
      line-height: 20px;
    }

    .login-logo a {
      color: #619fda;
      font-weight: 600;
      text-align: center;
      font-size: 28px;
      line-height: 24px;
      display: inline-block;
    }

    .login-logo a small {
      font-size: 14px;
      color: #1d1d1d;
    }

    label {
      line-height: 18px;
      font-weight: normal;
    }

    label.error, label.error p {
      font-size: 13px;
      margin: 1px 0 0 0;
      line-height: 16px;
      display: block;
    }

    form {
      padding: 0 0 0 0;
      border: 1px solid #1287c0;
      background-color: #dcf1fc;
    }

    a.links {
      color: #fff;
      background: #015171;
      font-size: 12px;
      padding: 2px 8px 3px 8px;
      border-radius: 0px;
      opacity: 0.8;
      transition: all 0.5s ease-out;
      /* color: #015171; */
    }    

    .btn.btn-flat {
      min-height: 34px;
      /* background-color:#015171; */
    }

    .red {
      color: #f00;
    }

    .form-horizontal .control-label 
    {
      padding: 0 15px;
      margin: 0 0 3px 0;
      line-height: 18px;
      text-align: left;
    }

    .captcha_img_outer {
      margin: 0 0 0 0;
    }

    .captcha_img_outer #captcha_img {
      display: inline-block;
      margin: 0;
      vertical-align: top;
    }

    /* .captcha_img_outer #new_captcha {
      min-width: 85px;
      display: inline-block;
      vertical-align: top;
      line-height: 15px;
    } */

    .captcha_img_outer #new_captcha {
      height: 22px;
      display: inline-block;
      vertical-align: top;
      width: 22px;
      padding: 0;
      text-align: center;
      font-size: 13px;
      border-radius: 50%;
      line-height: 19px;
      margin: 3px 0 0 0;
    }

    .captcha_outer .form-control {
      display: inline-block;
      width: calc(100% - 155px);
    }

    .captcha_outer .captcha_img_outer {
      display: inline-block;
      vertical-align: top;
      width: 150px;
      text-align: right;
    }

    .captcha_outer .captcha_img_outer #captcha_img .CaptchaBgText {
      height: 29px;
      width: 120px;
    }

    .captcha_outer .captcha_img_outer #captcha_img .CaptchaBgText::after
    {
      width:132px;
    }

    .btn_outer {
      text-align: center;
    }

    .btn_outer .btn {
      /* min-width: 115px;
      margin: 2px 2px 5px 2px; */
    }

    .links_outer {
      margin: 0 0 8px 0;
    }

    .links_outer a 
    {
      /* margin: 0 2px 2px 2px; */
      /* min-width: 190px; */
      /* display: inline-block; */
      font-weight: 600;
      line-height: 14px;
      font-size: 14px;
    }

    .links_outer a { float:right; }
    .links_outer a:first-child { float:left; }

    .links_outer a:focus, .links_outer a:hover 
    {
      color: #23527c !important;
      text-decoration: underline !important;
    }

    .loading {
      background-color: rgba(0, 0, 0, 0.1);
      height: 100%;
      min-height: 100%;
      position: fixed;
      text-align: center;
      top: 0;
      width: 100%;
      z-index: 9999;
      left: 0;
    }

    .loading>img {
      top: 35%;
      width: 130px;
      position: relative;
    }

    note {
      font-size: 12px;
      line-height: 18px;
      display: inline-block;
      margin: 2px 0 0 0;
      color: #015171;
      font-weight: 500;
    }

    #mobile_timer 
    {
      color: red;
      font-size: 13px;
      line-height: 15px;
      height: auto;
      min-height: auto;
      float: right;
      font-weight: 500;
      margin: 2px 0 0 0;
    }

    .form-horizontal .form-group
    {
      margin-bottom:10px;
    }

    .or_div 
    {
      font-size: 13px;
      font-weight: bold;
      margin: 8px 0 8px 0;
      line-height: 15px;
    }

    @media only screen and (max-width:399px)
    {
      .links_outer a:first-child { float: none; display: block; margin: 0 auto 5px auto; max-width: 175px; }
      .links_outer a { float:none; }

      .captcha_outer .form-control { width: 100%; }
      .captcha_outer .captcha_img_outer { width: 100%; text-align: left; margin: 6px 0 0 0; }
    }
  </style>
</head>

<body class="hold-transition login-page">
  <div class="loading"><img src="<?php echo base_url(); ?>assets/images/loading.gif"></div>
  <div class="login-box">
    <div class="login-logo">
      <div class="short_logo"><img src="<?php echo base_url(); ?>assets/images/iibf_logo_short.png"></div>
      <div class="login_text"><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br><small>(An ISO 21001:2018 Certified)</small></a></div>
    </div>    
    
    <div class="login-box-body">
      <?php 
        $show_msg = ''; $alert_cls = 'warning'; 
        if ($error) { $show_msg = $error; $alert_cls = 'danger'; }
        if ($this->session->flashdata('error_message')) { $show_msg = $this->session->flashdata('error_message'); $alert_cls = 'danger'; } 
        if ($this->session->flashdata('error')) { $show_msg = $this->session->flashdata('error'); $alert_cls = 'danger'; } 
        if ($this->session->flashdata('success')) { $show_msg = $this->session->flashdata('success'); $alert_cls = 'success'; } 

        if($show_msg != "")
        { ?>      
          <div class="alert alert-<?php echo $alert_cls; ?> alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $show_msg; ?>
          </div>
        <?php } 
      ?>

      <form class="form-horizontal" action="" method="post" name="loginFrm" id="loginFrm" autocomplete="off">
        <div style="background-color: #7fd1ea;color: #fff;padding: 6px 10px;margin-bottom: 10px;font-size: 20px;text-align: center;font-weight: 600;text-transform: uppercase;border-bottom:1px solid #1287c0;">Log In with OTP</div>
        
        <div style="padding: 10px 15px 20px 15px;max-width: 420px;margin: 0 auto;">
          <div class="form-group">
            <label for="regnumber" class="col-sm-12 control-label">Membership No. <span class="red">*</span> :</label>
            <div class="col-sm-12">
              <input type="text" class="form-control" placeholder="Membership No. *" name="regnumber" id="regnumber" value='<?php if($session_regnumber != "") { echo $session_regnumber; } else if(set_value('regnumber') != "") { echo set_value('regnumber'); } ?>' required <?php if($show_otp_input_flag == '1') { echo "readonly='readonly'"; } ?> onkeypress="remove_spaces('regnumber')" onkeyup="remove_spaces('regnumber')" onkeydown="remove_spaces('regnumber')">

              <?php if($show_otp_input_flag == '1') { ?>
                <a href="javascript:void(0)" class="" onclick="fun_reset_form()" style="display: block;float: right;">Reset</a>
              <?php } ?>

              <?php if(form_error('regnumber')!=""){ ?><div class="clearfix"></div><label class="error"><?php echo form_error('regnumber'); ?></label><?php } ?>
            </div>
          </div>  
          
          <?php if($show_otp_input_flag == '1') { ?>
            <div class="form-group">
              <label for="input_otp" class="col-sm-12 control-label">Enter OTP <span class="red">*</span> :</label>
              <div class="col-sm-12">
                <input type="text" class="form-control" placeholder="Enter OTP *" name="input_otp" id="input_otp" required maxlength="6" value="<?php echo set_value('input_otp'); ?>" onkeypress="remove_spaces('input_otp')" onkeyup="remove_spaces('input_otp')" onkeydown="remove_spaces('input_otp')">

                <div style="min-height:20px;">
                  <span id="mobile_timer"></span>
                
                  <button type="button" class="btn btn-warning btn-flat" name="resend_otp" id="resend_otp" onclick="fun_resend_otp()" style="padding: 0px 5px 1px 5px;min-height: auto;float: right;font-size: 12px;font-weight: 600;margin: 2px 0px 0px; display:none;">Resend OTP</button>
                </div>
                
                <note>Note : OTP successfully sent on <?php echo $mask_mobile; ?> & <?php echo $mask_email; ?>. OTP is valid for 5 minutes.</note>                
                <?php if(form_error('input_otp')!=""){ ?><div class="clearfix"></div><label class="error"><?php echo form_error('input_otp'); ?></label><?php } ?>
              </div>
            </div>             
          <?php } ?>
          
          <?php if($show_otp_input_flag == '0') { ?>
            <div class="form-group">
              <label for="code" class="col-sm-12 control-label">Type the exact characters you see in the picture <span class="red">*</span> :</label>
              <div class="col-sm-12 captcha_outer">
                <input type="text" class="form-control" name="code" id="code" required placeholder="Enter Code *" onkeypress="remove_spaces('code')" onkeyup="remove_spaces('code')" onkeydown="remove_spaces('code')">
                <?php if(form_error('code')!=""){ ?><div class="clearfix"></div><label class="error"><?php echo form_error('code'); ?></label><?php } ?>
                <span id="code_err2"></span>
                <div class="captcha_img_outer" id="code_err1">
                  <div id="captcha_img"><?php echo $image; ?></div>
                  <a href="javascript:void(0);" id="new_captcha" class="btn btn-primary" onclick="change_captcha_image()" title="Change Image"><i class="fa fa-refresh"></i></a>
                </div>
              </div>
            </div>
          <?php } ?>

          <div class="btn_outer">
            <div class="row">
              <div class="col-xs-12">
                <div class="links_outer">
                  <a href="<?php echo site_url('login/forgot_membership_no'); ?>" class="linksx forget">Forgot Membership Number?</a>
                  <a href="<?php echo site_url('login/forgotpassword'); ?>" class="linksx forget">Forgot Password?</a>
                  <div class="clearfix"></div>
                </div>

                <?php if($show_otp_input_flag == '1') { ?>
                  <input type="submit" class="btn btn-primary btn-flat btn-block" name="verify_otp" id="verify_otp" value="Verify OTP">
                  <button type="button" class="btn btn-warning btn-flat" name="resend_otp" id="resend_otp" style="display:none;" onclick="fun_resend_otp()">Resend OTP</button>
                <?php }
                else
                { ?>
                  <input type="submit" class="btn btn-primary btn-flat btn-block" name="send_otp" id="send_otp" value="Send OTP">
                <?php } ?>

                <div class="or_div">OR</div>
                <a href="<?php echo site_url('login'); ?>" class="btn btn-primary btn-flat btn-block">Login with Password</a>
                <?php /* <a href="<?php echo base_url();?>" class="btn btn-danger btn-flat">Back</a> */ ?>                
              </div>
            </div>
          </div>
      </form>      
    </div>
  </div>

  <script src="<?php echo base_url() ?>assets/admin/bootstrap/js/bootstrap.min.js"></script>
  <?php $this->load->view('vendors/common_validation_all'); ?>

  <script type="text/javascript">
    function remove_spaces(input_id)
    {
      if(input_id != '')
      {
        $("#"+input_id).val($.trim($("#"+input_id).val()));
      }
    }

    <?php if($resend_time_sec != '' && $resend_time_sec > 0) { ?> resend_countdown('<?php echo $resend_time_sec; ?>'); <?php }  else { ?> $('#resend_otp').show(); <?php } ?>
    
    function resend_countdown(timer2) 
    {
      var timer2 = convert(timer2);
      // console.log('timer2-1: ' + timer2);
      var interval2 = setInterval(function() 
      {
        // console.log('timer2-2: ' + timer2);
        var timer = timer2.split(':');
        //by parsing integer, I avoid all extra string processing
        var minutes = parseInt(timer, 10);
        var seconds = parseInt(timer[1], 10);
        --seconds;
        minutes = (seconds < 0) ? --minutes : minutes;
        if (minutes < 0) 
        {
          clearInterval(interval2);
          $('#mobile_timer').html("");
          $('#resend_otp').show();
        } 
        else 
        {
          $('#resend_otp').hide();
          seconds = (seconds < 0) ? 59 : seconds;
          seconds = (seconds < 10) ? '0' + seconds : seconds;
          minutes = (minutes < 10) ? minutes : minutes;
          $('#mobile_timer').html("Resend OTP in " + minutes + ':' + seconds);
          timer2 = minutes + ':' + seconds;
        }
      }, 1000);
    }

    function convert(value) 
    {
      return Math.floor(value / 60) + ":" + (value % 60 ? value % 60 : '00')
    }   
    
    function change_captcha_image() 
    {
      $('.loading').show();
      $.ajax({
        type: 'POST',
        url: site_url + 'Login/generate_captcha_ajax_login_with_otp',
        success: function(res) {
          if (res != '') {
            $('#captcha_img').html(res);
            $("#code").val("");
            $('.loading').hide();
          }
        }
      });
    }    

    function fun_resend_otp()
    {
      $('.loading').show();

      $("#input_otp").val("");
      $("#code").val("");

      $( "#loginFrm" ).validate().resetForm();

      parameters= { 'membership_no': $("#regnumber").val() }
      $.ajax({
        type: 'POST',
        url: "<?php echo site_url('login/set_session_login_with_otp_ajax') ?>",
        data: parameters,
        dataType: 'JSON',
        success:function(data)
        {
          if(data.flag == "success")
          { 
            $('#resend_otp').hide();
            resend_countdown(data.resend_time_sec);
            $('.loading').hide();
          }
          else 
          {	
            alert("Error occurred. Please try again.")
            $('.loading').hide();
          }          
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
          console.log('AJAX request failed: ' + errorThrown);
          alert("Error occurred. Please try again.")
          $('.loading').hide();
        }
      });
    }    

    function fun_reset_form()
    {
      $('.loading').show();
      parameters= { 'membership_no': $("#regnumber").val() }
      $.ajax({
        type: 'POST',
        url: "<?php echo site_url('login/reset_form_ajax') ?>",
        data: parameters,
        dataType: 'JSON',
        success:function(data)
        {
          if(data.flag == "success")
          { 
            location.reload();
          }
          else 
          {	
            location.reload();
          }          
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
          location.reload(); 
        }
      });
    }    

    $(document).ready(function() 
    {
      $.validator.addMethod("validate_otp_custom", function(value, element)
      {
        if($.trim(value).length == 0)
        {
          return true;
        }
        else
        {
          var isSuccess = false;
          var input_otp = $.trim(value);
          var regnumber = $("#regnumber").val();

          parameters= { 'input_otp':input_otp, 'regnumber':regnumber, }

          $.ajax(
          {
            type: "POST",
            url: "<?php echo site_url('login/validation_validate_otp/0/1'); ?>",
            data: parameters,
            async: false,
            cache : false,
            dataType: 'JSON',
            success: function(data)
            {
              if($.trim(data.flag) == 'success')
              {
                isSuccess = true;
              }
              
              $.validator.messages.validate_otp_custom = data.response;
            }
          });
          
          return isSuccess;
        }
      }, ''); 
		
      $("#loginFrm").validate({
        /*onkeyup: false,
        onclick: false,
        onblur: false,
        onfocusout: false,*/
        onblur: function(element) { $(element).valid(); },
        rules: 
        {
          regnumber: { required: true, digits: true, remote: { url: "<?php echo site_url('login/validation_validate_membership_no/0/1'); ?>", type: "post" } },
          input_otp: { required: true, digits: true, validate_otp_custom:true },
          code: { required: true, remote: { url: "<?php echo site_url('login/validation_check_captcha_login_with_otp/0/1'); ?>", type: "post" } },          
        },
        messages: 
        {
          regnumber: { required: "Please enter Membership No", digits: "Please enter only numbers", remote:"Please enter the valid membership number" },
          input_otp: { required: "Please enter OTP", digits: "Please enter only numbers" },
          code: { required: "Please enter the characters you see in the picture", remote: "Please enter the exact characters you see in the picture" },          
        },   
        errorPlacement: function(error, element) // For replace error 
        {
          if (element.attr("name") == "code") 
          {
            if($( document ).width() < 400)
            {
              error.insertAfter("#code_err2");
            }
            else
            {
              error.insertAfter("#code_err1");
            }
          }
          else 
          {
            error.insertAfter(element);
          }
        },       
        submitHandler: function(form) 
        {
          $(".loading").show();
          form.submit();
        }
      });
    });

    $(document).ready(function() { $('.loading').delay(0).fadeOut('slow'); });
  </script>
</body>
</html>