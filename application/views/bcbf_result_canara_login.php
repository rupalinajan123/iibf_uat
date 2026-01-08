<!DOCTYPE html>
<html>
  <head>
<?php $this->load->view('google_analytics_script_common'); ?>
  <script>var site_url="<?php echo base_url();?>";</script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">  
  <title>IIBF - User Login</title>
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
                 <td style="text-align:center; padding:60px 0 25px; font-size:22px; font-family:Tahoma, Geneva, sans-serif; font-weight:bold; line-height:32px;">Result data </td>
                 <!-- for BCBF Examination -->
               </tr>
               <tr>
                <td bgcolor="#6699CC">
                <form action="" method="post" name="loginFrm" id="loginFrm">
                  <table id="Table4" width="100%" cellspacing="1" cellpadding="2" border="0">
                    <tbody>
                    
                     <?php if(validation_errors()){?>
                     <tr>
                        <td colspan="2" class="text4" color="#d8e4f1" height="20" bgcolor="#9CAFD9" align="center">
                        	<b style="color:#F00"><?php echo validation_errors();?></b>
                        </td>
                      </tr>
                      <?php }?>
                      
                     <?php if($error){?>
                     <tr>
                        <td colspan="2" class="text4" color="#d8e4f1" height="20" bgcolor="#9CAFD9" align="center">
                        	<b style="color:#F00"><?php echo $error;?></b>
                        </td>
                      </tr>
                      <?php }?>
                      
                     <?php if($this->session->flashdata('error_message')){?>
                     <tr>
                        <td colspan="2" class="text4" color="#d8e4f1" height="20" bgcolor="#9CAFD9" align="center">
                        	<b style="color:#F00"><?php echo $this->session->flashdata('error_message')?></b>
                        </td>
                      </tr>
                      <?php }?>
                      
                      <tr>
                        <td colspan="2" class="text4" color="#d8e4f1" height="20" 
                        bgcolor="#9CAFD9" align="center"><b>EXAMINATION RESULTS</b></td>
                      </tr>
                    <tr>
                      <td class="content" width="45%" bgcolor="#d8e4f1">Exam&nbsp;</td>
                      <td class="content" width="55%" bgcolor="#d8e4f1">
                        <select name="exam" id="exam" class="text1" style="min-width:218px;">
                          <option value="">Select Exam</option>
                         
                           <option value="1033">Certificate on AML/KYC & Compliance in Banks</option>
                           

                          </select>
                      </td>
                    </tr>
                    <tr> 
                      <td class="content" bgcolor="#d8e4f1">
                      Registration/Membership No.&nbsp;</td>
                      <td class="text1" bgcolor="#d8e4f1">
                        <input class="text4" name="Username" id="register_no" value='<?php echo set_value('Username'); ?>' type="text">
                      </td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#d8e4f1">&nbsp;&nbsp;</td>
                      <td class="text" bgcolor="#d8e4f1">
                        <input class="button1" name="submit" value="SUBMIT" type="submit">
                        <a href="javascript:document.forms[0].reset();">
                          <input name="Reset22" class="button2" value="RESET" type="reset">
                        </a>
                      </td>
                    </tr> 
                    <tr>
                      <td valign="top" bgcolor="#d8e4f1">&nbsp;&nbsp;</td>
                      <td class="text" bgcolor="#d8e4f1">
                        <?php 
$obj = new OS_BR();
if($obj->showInfo('browser')=='Internet Explorer')
{?>
<br>
<div class="col-xs-12 message" style="color:#F00">
Portal best viewed in recommended browsers (Mozilla Firefox or Google chrome latest versions) for error free performance. It is suggested Internet explorer not to be used to avoid issues.</div>
<?php }?>
                      </td>
                    </tr>
                   </tbody>
                 </table>
                </form>
               </td>
              </tr>
              <tr>
                <td style="padding:20px 0 0;">
                  <!--<u>CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS / FACILITATORS</u>-->
                </td> 
              </tr>
                <tr>
                  <td>
                    <ol>
                    <li>Printed Result <span class="red"><strong>Advice will NOT be SENT to the candidates.</strong></span> Candidates can <span class="red"><strong>take a print out of the advice</strong></span> available in the website using the print tab. The printed result will contain signature , however the signature will not be visible when viewed on screen.</li>
                    <li>CANDIDATES <strong>PASSING THE EXAMINATION</strong> WILL BE ISSUED A CERTIFICATE</li>
                    <li><strong>Candidates are required to update their correct correspondence address, e-mail address and mobile number with the Institute since Institute <span class="red">will be sending the CERTIFICATE</span> and other important communications <span class="red">through post</span>, e-mail and SMS. For updating the same candidate can login using their login id and password to access their EDIT Profile given under Members/Others on the home page of Institute's web site <a href="http://www.iibf.org.in/">www.iibf.org.in</a> or inform the respective Zonal Office of the Institute.</strong> </li>
                    </ol>
                  </td>
                </tr>
             </table><!--Content Table-->
           </td>
         </tr>
         <tr>
                <td class="footer" colspan="2" width="43%" valign="middle" height="24">Copyright &copy; 2012. INDIAN INSTITUTE OF BANKING AND FINANCE. All rights reserved.
                </tr>
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