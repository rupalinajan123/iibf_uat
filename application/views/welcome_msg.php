<!DOCTYPE html>
<html> 
  <head>
		<?php $this->load->view('google_analytics_script_common'); ?>
    <script>var site_url="<?php echo base_url();?>";</script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Annual General Meeting of Indian Institute of Banking & Finance</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link href="<?php echo  base_url()?>assets/css/exam-result.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
  </head> 
  <body>   
    
    <table border="0" cellpadding="0" cellspacing="0" width="900px" align="center" style=" margin-bottom:10px;">
      <tr>
        <td style="padding:10px 0;">
          <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
            <tr>
              <td><img src="<?php echo  base_url()?>assets/images/iibf_logo_short.png"></td>
              <td class="login-logo" align="center"><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br><small>An ISO 21001:2018 Certified Organisation</small></a></td>
            </tr>
          </table><!--Logo Table-->
        </td>
      </tr>
      <tr>
        <td>
          <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
            <tr>
              <td style="border:3px solid #619fda;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
                  <tr>
                    <td><img src="<?php echo  base_url()?>assets/images/iibfofferings_head.jpg" /></td>
                  </tr>
                  <tr>
                    <td>
                      <table border="0" cellpadding="0" cellspacing="0" width="80%" align="center">
                        <tr>
                          <td style="text-align:left; padding:60px 0 25px; font-size:22px; font-family:Tahoma, Geneva, sans-serif; font-weight:bold; line-height:32px;">
                            Welcome to the 98<sup>th</sup> Annual General Meeting of Indian Institute of Banking & Finance<br>
                            Date : 18<sup>th</sup> September 2025<br> 
                            Time : 11.30 AM<br>
                            <a href="<?php echo  base_url()?>amc/annual_registration"> Click here </a> to login.
                          </td>
                        </tr>
                        
                        <tr>
                          <tdpstyle="padding:20px 0 0;">
                            <!--<u>CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS / FACILITATORS</u>-->
                          </td>
                        </tr>
                        
                      </table><!--Content Table-->
                    </td>
                  </tr>
                  <?php /*
                    <tr>
                    <td class="footer" colspan="2" width="43%" valign="middle" height="24"> Copyright &copy; 2012.  INDIAN INSTITUTE OF BANKING AND FINANCE. All rights reserved.
                  </tr>*/ ?>
                </table><!--Table with Border-->
              </td>
            </tr>
          </table><!--Logo Table-->
        </td>
      </tr>
    </table>
    
    
    
    <link href="<?php echo base_url()?>assets/css/parsley.css" rel="stylesheet">
    <script src="<?php echo base_url()?>assets/js/parsley.min.js"></script> 
    <!-- jQuery 2.2.0 --> 
    <!-- Bootstrap 3.3.6 --> 
    <script src="<?php echo  base_url()?>assets/admin/bootstrap/js/bootstrap.min.js"></script> 
    <!-- iCheck --> 
    <script src="<?php echo  base_url()?>assets/admin/plugins/iCheck/icheck.min.js"></script> 
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
        $('#new_captcha').click(function(event){
          event.preventDefault();
          $.ajax({
            type: 'POST',
            url: site_url+'Login/generatecaptchaajax/',
            success: function(res)
            {	
              if(res!='')
              {$('#captcha_img').html(res);
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
  $(document).ready(function () {
    
    $("body").on("contextmenu",function(e){
      return false;
    });
  });
</script>