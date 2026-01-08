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
    		<form action="" method="post" name="loginFrm" id="loginFrm">
				<?php 
                    $exam_array = $this->config->item('examCodeCaiib').",62,".$this->config->item('examCodeCaiibElective63').",64,65,66,67,".$this->config->item('examCodeCaiibElective68').",".$this->config->item('examCodeCaiibElective69').",".$this->config->item('examCodeCaiibElective70').",".$this->config->item('examCodeCaiibElective71').",72";
                ?>
    			<input type="hidden" name="examcode" value="<?php echo $exam_array;?>">
        		<div style="background-color:#7fd1ea; color:#fff; padding:3px 10px; margin-bottom:5px; font-size:16px;">Login</div>
        		<div class="form-group has-feedback clearfix">
        			<label for="text" class="col-md-6">Membership No. <span class="red">*</span> :</label>
        			<input type="text" class="form-control" placeholder="User name" name="Username" value='<?php echo set_value('Username'); ?>' autocomplete="off" required>
      			</div>
                
                <?php /*?><div class="form-group has-feedback clearfix">
        			<label for="text" class="col-md-6">Password. <span class="red">*</span> :</label>
        			<input type="password" class="form-control" placeholder="Password" name="Password" value='<?php echo set_value('Password'); ?>' autocomplete="off" required>
      			</div><?php */?>
                
                
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
<?php /* ?>
<table cellpadding="5" cellspacing="5" width="800" border="0" align="center" style="background-color:#fff;" class="login-box">
	<tr>
           <td style="text-align:center; font-weight:bold; font-size:13px; padding:7px 0; text-transform:uppercase;"><u>Note: For Candidate who has applied for change of centre(on or before 8-Aug-2021):</u></td>
    </tr>
	<tr><td><p style="margin:10px;">
	The change of Centre is subject to the availability of seats and is available on a first-come-first-serve basis.

If the change of centre request is accepted, a revised admit letter is available for download on the website.

A separate communication is also sent to the candidate for downloading the revised Admit letter.<br/><br/>
For any other support kindly click the below link and register your query:<br/><br/>
<a href="http://iibf.org.in/membersupportservice.asp">http://iibf.org.in/membersupportservice.asp</a>
</p></td></tr>
</table>
<?php */ ?>
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
