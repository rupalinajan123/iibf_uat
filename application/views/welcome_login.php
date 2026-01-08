<!DOCTYPE html>
<html> 

<head>
		<?php $this->load->view('google_analytics_script_common'); ?>
  <script>
    var site_url = "<?php echo base_url(); ?>";
  </script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Annual General Meeting of Indian Institute of Banking & Finance</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
  <link href="<?php echo  base_url() ?>assets/css/exam-result.css" rel="stylesheet" type="text/css" />
  <script src="<?php echo base_url() ?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
</head>
<style>
  body {
    background-image: url('<?php echo base_url('assets/images/AGM2025.png'); ?>');
    display: flex;
    align-items: center;
    height: 100vh;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: top center;
  }
</style>

<body>
    
  <?php /* <table border="0" cellpadding="0" cellspacing="0" width="900px" align="center" style=" margin-bottom:10px;">
      <tr>
      <td style="padding:10px 0;">
      <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
      <tr>
            <td><img src="<?php echo  base_url() ?>assets/images/iibf_logo_short.png"></td>
      <td class="login-logo" align="center"><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br><small>An ISO 9001:2008 Certified Organisation </small></a></td>
      </tr>
      </table>
      </td>
      </tr>
      <tr>
      <td>
      <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
      <tr>
    <td style="border:3px solid #619fda;">-->
    <table border="0" cellpadding="0" cellspacing="0" width="50%" align="center" style="margin-top: 484px;margin-left: 500px;">
                <tr><td></td></tr>
                <tr>
                  <td class="footer" colspan="2" width="43%" valign="middle" height="24"><?php Copyright & copy; 2012. ?> INDIAN INSTITUTE OF BANKING AND FINANCE. All rights reserved.</td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table> */ ?>
      
  <table style= 'width: 500px; position: fixed; right: 10px; bottom: 10px;'> 
            <?php /*<tr>
              <td style="text-align:center; padding:60px 0 25px; font-size:22px; font-family:Tahoma, Geneva, sans-serif; font-weight:bold; line-height:32px;">
              Indian Institute of Banking &amp; Finance welcome you on 93rd annual general meeting .
              </td>
            </tr> */ ?>
            
            <tr>
              <td bgcolor="#6699CC">
                <form action="" method="post" name="loginFrm" id="loginFrm">
          <table id="Table4" style= 'width: 100%;'> 
                    <tbody>
              <?php if (validation_errors())
              { ?>
                        <tr>
                          <td colspan="2" class="text4" color="#d8e4f1" height="20" bgcolor="#9CAFD9" align="center">
                    <b style="color:#F00"><?php echo validation_errors(); ?></b>
                          </td>
                        </tr>
              <?php } ?>
                      
              <?php if ($error)
              { ?>
                        <tr>
                          <td colspan="2" class="text4" color="#d8e4f1" height="20" bgcolor="#9CAFD9" align="center">
                    <b style="color:#F00"><?php echo $error; ?></b>
                          </td>
                        </tr>
              <?php } ?>
                      
              <?php if ($this->session->flashdata('error_message'))
              { ?>
                        <tr>
                          <td colspan="2" class="text4" color="#d8e4f1" height="20" bgcolor="#9CAFD9" align="center">
                    <b style="color:#F00"><?php echo $this->session->flashdata('error_message') ?></b>
                          </td>
                        </tr>
              <?php } ?>
                      
                      <tr>
                <td colspan="2" class="text4" color="#d8e4f1" height="20" bgcolor="#9CAFD9" align="center"><strong>Login Form</strong></td>
                      </tr>
                      <tr>
                        <td class="content" bgcolor="#d8e4f1">
                        Registration/Membership No.&nbsp;</td>
                        <td class="text1" bgcolor="#d8e4f1">
                  <input class="text4" name="Username" id="register_no" value='<?php echo set_value('Username'); ?>' type="text" required style="margin-left:0; width:calc(100% - 15px);">
                        </td>
                      </tr>
                      <tr>
                        <td class="content" bgcolor="#d8e4f1">
                        Password.&nbsp;</td>
                        <td class="text1" bgcolor="#d8e4f1">
                  <input class="text4" name="Password" id="Password" value='<?php echo set_value('Password'); ?>' type="Password" required style="margin-left:0; width:calc(100% - 15px);">
                        </td>
                      </tr>
                      <tr>
                        <td valign="top" bgcolor="#d8e4f1">&nbsp;&nbsp;</td>
                        <td class="text" bgcolor="#d8e4f1">
                          <a href="javascript:document.forms[0].reset();" style="margin-left:10px;cursor:pointer"><input name="Reset22" class="button2" value="RESET" type="reset" style="cursor:pointer"></a>
                          <input class="button1" name="submit" value="SUBMIT" type="submit" style="margin-left:5px; cursor:pointer">
                        </td>
                      </tr>                      
                    </tbody>
                  </table>
                </form>
              </td>
            </tr>
      </table>
    
  <link href="<?php echo base_url() ?>assets/css/parsley.css" rel="stylesheet">
  <script src="<?php echo base_url() ?>assets/js/parsley.min.js"></script>
    <!-- jQuery 2.2.0 --> 
    <!-- Bootstrap 3.3.6 --> 
  <script src="<?php echo  base_url() ?>assets/admin/bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck --> 
  <script src="<?php echo  base_url() ?>assets/admin/plugins/iCheck/icheck.min.js"></script>
    <script>
    $(function() {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      $('#new_captcha').click(function(event) {
          event.preventDefault();
          $.ajax({
            type: 'POST',
          url: site_url + 'Login/generatecaptchaajax/',
          success: function(res) {
            if (res != '') {
              $('#captcha_img').html(res);
              }
            }
          });
        });
        
      });
    </script> 
    <script type="text/javascript">
      $('#loginFrm').parsley('validate');
    </script>
</body>

</html>
<script>
  $(document).ready(function() {
    
    $("body").on("contextmenu", function(e) {
      return false;
    });
  });
</script>