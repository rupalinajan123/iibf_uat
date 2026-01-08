<!DOCTYPE html>
<html lang="en">
<head>
	<?php $this->load->view('google_analytics_script_common'); ?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Current Opening">
<meta name="author" content="IFCI">
<title>Current Opening</title>
<?php include 'header-script.php';?>
<link href="<?php echo base_url()?>assets/css/styles.css" rel="stylesheet">
<link href="<?php echo  base_url()?>assets/css/css_header.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-select.css">
<script src="<?php echo base_url()?>assets/js/jquery.js"></script>
<style>
.ui-datepicker-year, .ui-datepicker-month { color:#000; }
.mar5{ margin-top:5px;}
.eligible{ color:#F00; font-size: larger;}
.drop_custom
{
	float:left;
	width:100%;
	min-height:30px;
	border:1px solid #ccc;
	padding:5px;
	margin-bottom:10px;
}
.topmsg {
    font-size: 27px;margin-bottom: 25px;
}
.success-icon img {
    width: 60px;
    height: auto;
    background: #74C343;
    padding: 13px;
}
.success-box {
    position: relative;
    background-color: whitesmoke;
	margin-top: 30px;
}
.midmsg {
    margin: 3px 0px;
}
.success-icon {
    position: absolute;
    left: 46%;
    top: -23px;
}
.success-msg {
    padding: 20px 0px;
    margin: 30px 0px;
}
.scsbtn {
    background-color: #74C343;
    padding: 10px 25px;
    font-size: 17px;
    margin-top: 30px;
    margin-bottom: 30px;
    color: white;
}
footer {
    bottom: 0;
    position: absolute;
    width: 100%;
}
html
{
	background-color:white;
}
</style>
</head>
<body>
<!-- Start of Header Area -->
<?php include 'header.php';?>
<!-- End of Header Area --> 

<!-- Start of Form Wizard -->
<div class="container atm_m40-top atm_m40-bottom">
 
  <div class="col-lg-6 col-lg-offset-3 text-center  success-box">
	<div class="success-icon"><img src="https://www.ifciltd.com/careers/assets/images/check-mark-in-white-hi.png"></div>
	<div class="success-msg">
		<div class="topmsg">Success</div>
		<div class="midmsg">Your application has been submitted with ref no <span><strong><?php echo $applicationNo?></strong></span></div>
		<div class="lastmsg">Please note the same for future reference  </div>
		<div ><a class="col-lg-4 col-lg-offset-4 scsbtn" href="<?php echo base_url();?>/careers/preview/<?php echo $applicationNo;?>">Download Application</a></div>
        <div ><a class="col-lg-4 col-lg-offset-4 scsbtn" href="https://www.ifciltd.com/">Home</a></div>
	</div>
  </div>
  
  </div>
  <!-- /well-sm--> 
</div>
<!-- /container--> 
<!-- End of Form Wizard --> 
<!-- Start of Footer --> 

<?php include 'validation.php';?>
<?php include 'footer.php';?>
<!-- End of Footer --> 
<!-- Start of Footer-script -->
<?php include 'footer-script.php';?>
<script src="<?php echo base_url()?>assets/js/myjs.js"></script> 
<script src="<?php echo base_url()?>assets/js/bootstrap-select.js"></script> 

<!-- End of Footer-script -->
</body>
</html>