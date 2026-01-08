<!DOCTYPE html>
<html>
  <head>
	<?php $this->load->view('google_analytics_script_common'); ?>
  <script>var site_url="<?php echo base_url();?>";</script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>IIBF - User Login</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo  base_url()?>assets/admin/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ionicons.min.css">
  <link rel="stylesheet" href="<?php echo  base_url()?>assets/admin/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo  base_url()?>assets/admin/plugins/iCheck/square/blue.css">
  <script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

  <!--[if lt IE 9]>

  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>

  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

  <![endif]-->
<style>
.login-box-body a {
	line-height:20px;
}
.short_logo {
	display:inline-block;
	float:left;
	margin:0 0 0 20px;
}
.login-logo a {
	color:#619fda;
	font-weight:600;
	text-align:center;
	font-size:28px;
	line-height:24px;
	display:inline-block;
}
.login-logo a small {
	font-size:14px;
	color:#1d1d1d;
}
.form-control {
	width:50%;
}
label {
	line-height:18px;
	font-weight:normal;
}
form {
	padding:10px;
	border:1px solid #1287c0;
	background-color:#dcf1fc;
}
.form-group {
	margin-bottom:10px;
}
a.forget {
	color:#9d0000;
	line-height:24px;
}
a.forget:hover {
	color:#9d0000;
	text-decoration:underline;
}
.btn.btn-flat {
	min-height:34px;
	background-color:#015171;
}
.red {
	color:#f00;
}
.center_box td, th{
	padding:3px;
}
</style>
  </head>

<body class="hold-transition login-page;" style="margin:0 50px 0 50px">
	<div class="login-box">
    	<div class="login-logo">
    		<div class="short_logo">
            	<img src="<?php echo base_url();?>assets/images/iibf_logo_short.png">
            </div>
    		<div>
            	<a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>
      			<small>(An ISO 9001:2008 Certified)</small></a>
            </div>
  		</div>
    	<div style="font-size:30px;text-align:center;color:#7FD1EA;font-weight:bold;"> Admit letter </div>
    	<div class="login-box-body" style="left:20% !important">
    		<?php if(validation_errors()){?>
    			<div class="callout callout-danger"><?php echo validation_errors();?></div>
    		<?php }?>
    		<?php if($error){?>
    			<div class="callout callout-danger" style="color:#FFF !important"><?php echo $error;?></div>
    		<?php }?>
    		<?php if($this->session->flashdata('error_message')){?>
    			<div class="callout callout-danger"><?php echo $this->session->flashdata('error_message')?></div>
    		<?php }?>
            <?php if(isset($msg)){?>
    			<div class="callout callout-danger"><?php echo $msg;?></div>
    		<?php }?>
    		<form action="" method="post" name="loginFrm" id="loginFrm">
				<?php 
                    $exam_array = "148,135,20,79,74,153,161,175,177,58,34,160,163,18,81,78,162,151,59,11,158,19,156,8,26,149,24,33,25,164,32";
                ?>
    			<input type="hidden" name="examcode" value="<?php echo $exam_array;?>">
        		<div style="background-color:#7fd1ea; color:#fff; padding:3px 10px; margin-bottom:5px; font-size:16px;">Login</div>
        		<div class="form-group has-feedback clearfix">
        			<label for="text" class="col-md-6">Membership No. <span class="red">*</span> :</label>
        			<input type="text" class="form-control" placeholder="User name" name="Username" value='<?php echo set_value('Username'); ?>' autocomplete="off" required>
      			</div>
                
                <!--<div class="form-group has-feedback clearfix">
                    <label for="text" class="col-md-6">
                        Password <span class="red">*</span> :
                    </label>
                    <input type="password" class="form-control" placeholder="Password" name="Password" value='<?php echo set_value('Password'); ?>' autocomplete="off" required>
                </div>-->
                
                
        		<div class="form-group has-feedback clearfix">
        			<label for="text" class="col-md-6">Type the exact characters you see in the picture <span class="red">*</span>.</label>
        			<input type="text" class="form-control" name="code" autocomplete="off"  style="padding-right:10px !important;" required>
        			<label>
        				<div id="captcha_img"><?php echo $image;?></div>
        			</label>
        			<label for="text" class="col-md-7"></label>
        			<div class="form-group has-feedback clearfix">
                    	<a href="javascript:void(0);" id="new_captcha" class="forget" >Change Image</a>
                    </div>
      			</div>
        		<div class="row"> 
        			<div class="col-xs-4">
            			<button type="submit" class="btn btn-info btn-block btn-flat" name="submit">Submit</button>
          			</div>
        			<div class="col-xs-4">
            			<button type="reset" class="btn btn-info btn-block btn-flat"  name="btnReset" id="btnReset">Reset</button>
          			</div>
        			<div class="col-xs-4"> 
            			<a href="<?php echo base_url();?>" class="btn btn-info btn-block btn-flat">Back</a>
                    </div>
        			<div class="col-xs-12">
                    	<a href="<?php echo base_url();?>login/forgotpassword/" class="forget">Forgot Password/Get password Click Here</a>
                    </div>
        			<?php 
						$obj = new OS_BR();
						if($obj->showInfo('browser')=='Internet Explorer'){
					?>
        			<br>
        			<div class="col-xs-12 message" style="color:#F00">
                    	Portal best viewed in recommended browsers (Mozilla Firefox or Google chrome latest versions) for error free performance. It is suggested Internet explorer not to be used to avoid issues.
                    </div>
        			<?php }?>
       				 <span style="color:#F00;">
          					<?php //echo @$error." ".validation_errors(); ?>
          			</span>
                 </div>
      		</form>
  		</div>
  	</div>
</div>
<!--
<table cellpadding="5" cellspacing="5" width="800" border="0" align="center" style="background-color:#fff;" class="login-box">
   <tr><td><p style="margin:10px;color:#ff0000"><strong>JAIIB/SOB/DBF Admit Letters for SHIVAMOGA center will be available by 11/02/2022</strong></p></td></tr>
	<tr><td><p style="margin:10px;">
	The Centre/Venue/Batch selected by the candidate at the time of registration for JAIIB/SOB/DBF Jan-2022 examinations may have changed due to COVID-19 protocol/social distancing norms/guidelines. Revised admit letter with new Centre/Venue/Batch is available above.</p></td></tr>
	<tr><td><p style="margin:10px;">Candidate should download the revised Admit letter and check all the details mentioned in it. In case any candidate is transferred due to work requirements, he/she may change the Centre/Venue/Batch, if required, using the below link. (The Change of Centre/Venue/Batch option can be exercised only once)</p></td></tr>
<tr><td><p style="margin:10px;"><strong>For centre change please <a href="https://iibf.esdsconnect.com/Applyjaiib_centerchange/login">click here</a><strong></p></td></tr>
	<tr><td><p style="margin:10px;">If the candidate changes the Centre/Venue/Batch, a revised Admit letter will be generated and available for download. The revised admit letter will also be available under the candidate’s login profile.</p></td></tr>
	<tr><td><p style="margin:10px;">
	<u><strong>Note: <br/> </strong></u>
 
1.  Change of the Centre/Venue/Batch is subject to availability of seats and will be on first-come-first-serve basis.<br/>
2.  The change of centre link will be active from <strong>10-Feb-2022 3.00 PM onwards to 14-Feb-2022.</strong> <br/>
3. Candidates are advised to visit Institute's Website regularly, as well as, a day before the Examination Date for any important update/Notice or Change in Examination Venue/Batch etc. related to the examination.
<br/>
</p></td></tr>
	</table>
	</td></tr>
	
</table>
-->
<!-- 
<table cellpadding="5" cellspacing="5" width="800" border="0" align="center" style="background-color:#fff;" class="login-box">
	<tr>
           <td style="background-color:#7fd1ea; color:#fff; text-align:center; font-weight:bold; font-size:13px; padding:7px 0; text-transform:uppercase;">Notice</td>
    </tr>
	<tr><td><p style="margin:10px;">
	Due to non-availability of appropriate venues, the admit letter for the below centres will be available by 25-Aug-2021 after 6.00 PM.</p></td></tr>
	<tr align="center"><td>
	<table border="1" cellpadding="5" cellspacing="5" width="50%" style="padding:10px;" class="center_box">
	<tr><td>Sr.</td><td>Exam Centre Name</td></tr>
	<tr><td>1</td><td>Nellore</td></tr>
	<tr><td>2</td><td>Ratnagiri</td></tr>
	<tr><td>3</td><td>Mira Road</td></tr>
	<tr><td>4</td><td>Shivamogga</td></tr>
	
	</table>
	</td></tr>
	<tr><td><p style="margin:10px;">
	<u><strong>Note: For Candidate who has applied for change of centre:<br/> </strong></u>
 
In case the candidate is transferred to another city due to work requirement and have applied for change of centre:<br/>
 <br/>
The change of centre request will be considered on a case-to-case basis. If accepted, a revised Admit letter will be sent to the candidate's registered email id as well as will be available under the candidate's login profile one week before the exam date.
<br/>
The change of Centre is subject to the availability of seats and is available on first-come-first-serve basis.

</p></td></tr>
</table>	-->
<link href="<?php echo base_url()?>assets/css/parsley.css" rel="stylesheet">
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script> 
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
			url: site_url+'Admitcard/generatecaptchaajax/',
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
