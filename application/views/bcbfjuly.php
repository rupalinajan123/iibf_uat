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
  <body class="hold-transition login-page">
	<div class="login-box" style='height=100%' >
    	<div class="login-logo">
    		<div class="short_logo">
            	<img src="<?php echo base_url();?>assets/images/iibf_logo_short.png">
            </div>
    	<div>
        	<a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>
      		<small>(An ISO 21001:2018 Certified)</small></a>
        </div>
  	</div>
    <div class="login-box-body">
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
        <input type="hidden" name="examcode" value="101">
        	<div style="background-color:#7fd1ea; color:#fff; padding:3px 10px; margin-bottom:5px; font-size:16px;">Login</div>
        	<div class="form-group has-feedback clearfix">
        		<label for="text" class="col-md-6">
                	Membership No. <span class="red">*</span> :
                </label>
        		<input type="text" class="form-control" placeholder="User name" name="Username" value='<?php echo set_value('Username'); ?>' autocomplete="off" required>
      		</div>
            
            <?php /*?><div class="form-group has-feedback clearfix">
        		<label for="text" class="col-md-6">
                	Password <span class="red">*</span> :
                </label>
        		<input type="password" class="form-control" placeholder="Password" name="Password" value='<?php echo set_value('Password'); ?>' autocomplete="off" required>
      		</div><?php */?>
        
        	<div class="form-group has-feedback clearfix">
        		<label for="text" class="col-md-6">
                	Type the exact characters you see in the picture <span class="red">*</span>.
                </label>
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
                <div style="padding: 10px;display:none">
                <strong>
                Due to unviability of venue at BHAGALPUR, GORAKHPUR, MUZAFFARPUR and SITAPUR the BC/BF examinations scheduled on 17th December 2021 are postponed.
                </strong>
                <br>
                <strong>
                The revised date of examination will be announced later and will be notified on the website.
                 </strong>
                 </div>
        		
        	   <div style="font-weight:bold;color:black;padding:10px;display:none" >
        	   <div style="font-size:15px;float:left;display:none">Dear Sir/Madam,<br><br>
 
                Due to unavailability of appropriate venue at PURNEA centre BCBF exam of 13th August,2021 has been rescheduled on 3rd September,2021.<br>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 70%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}
</style>
<table style='display:none'>
    
    <tr><td>City Name</td>
    <td>venueid</td>
    <td>venuead</td>
    
    </tr>
        <tr><td>ERNAKULAM</td>
        <td>683556</td>
        <td>SWEAR ONLINE ASSESSMENT CENTRE, D BLOCK, JAI BHARATH COLLEGE OF MANAGEMENT AND ENGINEERING TECHNOLOGY, JB CAMPUS, PERUMBAVOOR-PUTHENKURISH ROAD, ARAKKAPPADY, VENGOLA, ERNAKULAM, KERALA-683556, India.</td>
    </tr> 
        <tr><td>KOLLAM</td>
        <td>691572</td>
        <td>MES INSTITUTE OF TECHNOLOGY AND MANAGEMENT, NEAR THIRUMUKKU, CHATHANNOOR PO, KOLLAM, KERALA-691572, India.</td>
        </tr>
        <tr><td>KOTTAYAM</td>
        <td>686001</td>
        <td>NSEIT LIMITED - KOTTAYAM, 1ST FLOOR, CHANDRATHIL BUILDING, KIZHAKKETHIL LANE, BEHIND HEAD POST OFFICE, LAND MARK: DD DRIVE COMMUNICATION TROPHY SHOP, OPP DR. P. SUKUMARAN'S CLINIC, KOTTAYAM, KERALA-686001, India.</td>
        </tr>
        <tr><td>KOZHIKODE</td>
        <td>673008</td>
        <td>ST JOSEPHS COLLEGE - DEVAGIRI, DEVAGIRI, MEDICAL COLLEGE PO, KOZHIKODE, KERALA-673008, India.</td>
        </tr>
        <tr><td>BALLARI</td>
        <td>583101</td>
        <td>NSEIT LIMITED - BALLARI, 78/2, SECOND FLOOR, OPP: NAVARANG ELECTRICAL'S, ABOVE: TCS COLLECTIONS REDIMADE GARMENTS, KALAMMA STREET, BALLARI, KARNATAKA-583101, India.</td>
        </tr>
        <tr><td>BELAGAVI</td>
        <td>590001</td>
        <td>NSEIT LIMITED - BELAGAVI, LG 13, MEERA ARCADE, CTS NO. 3935/26D, CLUB ROAD, OFF COLLEGE ROAD, BEHIND MADIWALE COMPLEX, KALI AMRAI, BELAGAVI, KARNATAKA-590001, India.</td>
        </tr>
        <tr><td>BENGALURU</td>
        
        <td>560030</td>
        <td>ICRIS PAREEKSHA, NO. 11/3, 2ND FLOOR, OPPOSITE CHARTERED CENTRE, ABOVE BESCOM, HOSUR RD, ADUGODI, BENGALURU, KARNATAKA-560030, India.</td>
        </tr>
        <tr><td>DEVANAGERE</td>
        <td>577001</td>
        <td>NSEIT LIMITED - DEVANAGERE, 2ND FLOOR, AM TOWERS, 121/1-5, BINNY COMPANY ROAD (HM ROAD), ADJACENT TO BAPUJI CO-OPERATIVE BANK, DEVANAGERE, KARNATAKA-577001, India.</td>
        </tr>
        <tr><td>GULBARGA</td>
        <td>585105</td>
        <td>KAVITA COMPUTERS AND IT SERVICES - KFC OF KEONICS, NO. 2-907/23 C, SHIVA SHREE COMPLEX, NEAR BASAVESHWAR HOSPITAL, SEDAM ROAD, GULBARGA, KARNATAKA-585105, India.</td></tr>
        <tr><td>HUBBALLI</td>
        <td>580031</td>
        <td>EDUTECH SOLUTIONS CO - CHETAN BUSINESS SCHOOL, 100 FEET ROAD, SRINAGAR, UNKAL, LAND MARK - UNKAL LAKE BUS STOP, NEAR PRESIDENT HOTEL, HUBBALLI, KARNATAKA-580031, India.</td></tr>
        <tr><td>MYSURE</td>
        <td>570019</td>
        <td>CHANDANA RESOURCE CENTER, SATHAGALLI KSRTC BUS STAND BUILDING, 2ND AND 3RD FLOOR, MAHADEVAPURA MAIN ROAD, SATHAGALLI BUS DEPO, MYSURE, KARNATAKA-570019, India.</td></tr>
        <tr><td>PURNEA</td>
        <td>PURNEA</td>
        <td>PURNEA</td>
        </tr>
    
</table>






  </div>
        	
        </div>
   
                </div>
                 
   
      	</form>
    	<?php /*?><div style="font-weight:bold;color:#F00;">
        	<div style="font-size:15px;float:left">List of Centres merged for the examination </div>
        	<a href="<?php echo base_url()?>uploads/admitcardinfo/STATUS_10 Nov_2018_WEB.pdf" target="_blank" ><strong>(Click here)</strong></a>
        </div><?php */?>
  </div>
  </div>
</div>
</div>
<link href="<?php echo base_url()?>assets/css/parsley.css" rel="stylesheet">
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script> 
<script src="<?php echo  base_url()?>assets/admin/bootstrap/js/bootstrap.min.js"></script> 
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
