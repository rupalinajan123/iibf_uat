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
                    $exam_array = "20,34,58,74,78,79,135,148,149,153,158,160,161,162,163,164,166,175,177,8,11,18,19,24,25,26,59,81,151,154,156,165,200,590,5800,3400,177,1600,810,8,11,18,119,200,24,25,26,3400,5800,590,74,78,79,810,135,148,149,151,153,156,157,158,16000,161,162,163,164,165,166,1750,17700,".$this->config->item('examCodeSOB');
                ?>
    			<input type="hidden" name="examcode" value="<?php echo $exam_array;?>">
        		<div style="background-color:#7fd1ea; color:#fff; padding:3px 10px; margin-bottom:5px; font-size:16px;">Login</div>
        		<div class="form-group has-feedback clearfix">
        			<label for="text" class="col-md-6">Membership No. <span class="red">*</span> :</label>
        			<input type="text" class="form-control" placeholder="User name" name="Username" value='<?php echo set_value('Username'); ?>' autocomplete="off" required>
      			</div>
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

	<!-- <div style="font-weight:bold;color:black;padding:10px;margin:0 50px 0 50px">
    <div style="font-size:15px;float:left,margin:0 50px 0 50px" align="center">
			NOTICE</br>
DIPLOMA/CERTIFICATE/BLENDED EXAMINATIONS – JULY-2025<br/><br/><u>CHANGE OF VENUES:</u><br><br>
THIS IS TO BRING TO THE NOTICE OF CANDIDATES THAT DUE TO CERTAIN UNAVOIDABLE CIRCUMSTANCES, 
SOME OF THE CANDIDATES CENTRE / VENUES AND TIME OF EXAMINATION HAS BEEN CHANGED. <br/><br/>
<a href="https://iibf.esdsconnect.com/uploads/Notice - change of centre.pdf">CLICK HERE</a> TO SEE THE DETAILS OF VENUES WHICH HAS BEEN CHANGED.
			</div>
	</div> -->		
	<!-- 
  	<div style="font-weight:bold;color:black;padding:10px;margin:0 50px 0 50px">
        	<div style="font-size:15px;float:left,margin:0 50px 0 50px"><h3 align='center'>CHANGE OF VENUES:</h3><br><br>
 
THIS IS TO BRING TO THE NOTICE OF CANDIDATES THAT DUE TO CERTAIN UNAVOIDABLE CIRCUMSTANCES; VENUES/TIME OF EXAMINATION HAS BEEN CHANGED FOR SOME CANDIDATES FOR ALL THREE EXAM DATES i.e. 10th, 11th and 18th JULY-2021 THE DETAILS OF VENUES WHICH HAS BEEN CHANGED ARE AS FOLLOWS:<br>
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
<table >
    
    <tr>
	 <td>Sr.No</td>
	 <td>City Name</td>
    <td>Old Venue Name</td>
    <td>New Venue Name</td>
    
    </tr>
        <tr>
		  <td>1</td>
		  <td>MUMBAI</td>
        <td>MAHARASHTRA COLLEGE OF ARTS,SCIENCE AND COMMERCE, 246-A, JAHANGIR BOMAN BEHRAM MARG, BELLASIS ROAD,NAGPADA,</td>
        <td>Jai Bharat Junior College Of Science & Commerce, OLD BARROT, OPPOSITE ARIHANT ELECTRIC & HARDWARE STORE, MULUND COLONY, MULUND WEST,MUMBAI,MAHARASHTRA</td>
    </tr> 
        <tr>
		  <td>2</td>
		  <td>SHIMOGA</td>
        <td>JAWAHARLAL NEHRU NATIONAL COLLEGE OF ENGINEERING, SAVALANGA ROAD, NAVULE, JNNCE COLLEGE, SHIMOGA, KARNATAKA</td>
        <td>Skylite Technologies, TOP TOWER, 2ND FLOOR, SHIVALANGA ROAD,OPP. METRO HOSPITAL,SHIMOGA,KARNATAKA</td>
        </tr>
        <tr>
		  <td>3</td>
		  <td>TIRUCHIRAPPALLI</td>
        <td>INDRA GANESAN COLLEGE OF ENGINEERING,MADURAI MAIN ROAD, MANIKANDAM, TIRUCHIRAPPALLI, TAMILNADU</td>
        <td>MAHALAKSHMI ENGINEERING COLLEGE,           NEAR NO.1 TOLLGATE, THUDAIYUR POST,MANNACHANALLUR TK,TIRUCHIRAPPALLI,TAMILNADU</td>
        </tr>
        <tr><td>4</td>
		  <td>KARUR</td>
        <td>KONGU COLLEGE OF ARTS AND SCIENCE,DHEERAN CHINNAMALAI NAGAR, VENNAIMALAI, KARUR, TAMILNADU</td>
        <td>Sri Rudra Technical Institute, LAKSHMI VILAS BANK UPSTAIRS, NO.4, NAMAKKAL MAIN RD,MOHANUR,KARUR,TAMILNADU</td>
        </tr>
        <tr>
		  <td>5</td>
		  <td>TIRUNELVELI</td>
        <td>PSN COLLEGE OF ENGINEERING AND TECHNOLOGY,MELATHEDIYOOR, PIRANCHERI, PALAYAMKOTTAI, TIRUNELVELI, TAMILNADU</td>
        <td>INFANT JESUS COLLEGE OF ENGINEERING,KAMARAJAR NAGAR, TIRUNELVELI-THOOTHUKUDI HIGHWAY, KEELAVALLANADU,NEAR FAYWALK,TIRUNELVELI,TAMILNADU</td>
        </tr>
		  
		  

        <tr><td>6</td>
		  <td>TIRUPATHI</td>
        <td>SHREE INSTITUTE OF TECHNICAL EDUCATION,KRISHNAPURAM(V), RENIGUNTA(M), TIRUPATI - SRIKALAHASTI HIGH WAY, KRISHNAPURAM(V), RENIGUNTA(M), TIRUPATHI, CHITTOOR, ANDHRAPRADESH</td>
        <td>SS DIGITAL ZONE,5-178, CHANDRAGIRI ROAD, BESIDE HERO SHOWROOM, OPP CITRUS RESEARCH CENTER, PERUR,TIRUPATHI, ANDHRAPRADESH</td>
        </tr>
        <tr><td>7</td>
		  <td>GUNTUR</td>
        
        <td>SRI LAKSHMI SRINIVASA COMPUTERS,NEW GUNTUR MUNCIPAL, PLOT NO. 56 INDRA PHASE 1 & 2 AUTO NAGAR, BESIDE KUSALAVA HYUNDAI SHOWROOM, GUNTUR, ANDHRAPRADESH</td>
        <td>Malineni Perumallu Educational societys Group of Institutions,ADIGUNTA VILLAGE, KORNEPADU POST, VATTICHERUKURU MANDAL,PRATHIPADU ROAD,GUNTUR,ANDHRAPRADESH</td>
        </tr>
		  
        <tr><td>8</td>
		  <td>PONDICHERRY</td>
        <td>ACHARIYA COLLEGE OF ENGINEERING TECHNOLOGY,URUVAIYAR RD, ACHARIYAPURAM, VILLIANUR, PONDICHERRY, PONDICHERRY</td>
        <td>Icreatives Internet café,147, LAPORTE ST. SUBBARAYAPILLAI,CHATHIRAM,PONDICHERRY,PONDICHERRY</td>
        </tr>
		  
        <tr><td>9</td>
		  <td>UDAIPUR</td>
        <td>IAST COMPUTER INSTITUTE, 28, OLD JODHPUR DAIRY, ASHOK NAGAR ROAD, BHUPALPURA, NEAR MAHARASHTRA BHAWAN, UDAIPUR, RAJASTHAN</td>
        <td>IAST COMPUTER INSTITUTE, 354, J-1 Road, In front of Vardman Complex, Near New Jodhpur Dairy, Maharastra Bhawan, Bhupalpura</td></tr>
    
</table>






 <br><br>FOR CANDIDATES WHOSE VENUE/TIME HAS BEEN CHANGED, INSTITUTE HAS INFORMED THEM THROUGH EMAIL AND SMS.<br>
<b>CANDIDATES PERTAINING TO THE ABOVE CENTRE/VENUES, ARE ADVISED TO CONFIRM THEIR VENUE OF EXAMINATION BY DOWNLOADING THE ADMIT LETTER FROM THE FOLLOWING LINK :</b><br>
 <a href="https://iibf.esdsconnect.com/Admitcard/dipcert" target='blank'>Admit letters for Diploma / Certificate examination July-2021 (physical classroom environment) </a><br>
 INCONVENIENCE CAUSED IS REGRETTED.<br><br>
 
Mumbai, dated 06/07/2021  &nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;                                    Joint Director – Examinations
 
  </div>
        	
        </div>
	-->	
</div>
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
