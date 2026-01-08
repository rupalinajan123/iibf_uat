<!DOCTYPE html>
<html>
<head>
<?php $this->load->view('google_analytics_script_common'); ?>
<script>var site_url="<?php echo base_url();?>";</script>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>IIBF-CSC BCBF Venue list</title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/bootstrap/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/dist/css/AdminLTE.min.css">

<!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/dist/css/skins/_all-skins.min.css">

<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<!--<script src="<?php echo base_url()?>js/jquery.js"></script>-->
<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/iCheck/all.css">
<script src="<?php echo base_url()?>js/validation.js?<?php echo time(); ?>"></script>
<link href="<?php echo base_url()?>assets/css/parsley.css" rel="stylesheet">
<link href="<?php echo base_url()?>assets/css/custom_cms.css" rel="stylesheet">
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<style>
.modal-dialog {
	position: relative;
	display: table;
	overflow-y: auto;
	overflow-x: auto;
	width: 920px;
	min-width: 300px;
}
.skin-blue .main-header .navbar {
	background-color: #fff;
}
body.layout-top-nav .main-header h1 {
	color: #0699dd;
	margin-bottom: 0;
	margin-top: 30px;
}
.ifci_logo_black {
	position: absolute;
	top: 25px;
	z-index: 1031;
	left: 135px;
}
.container {
	position: relative;
}
.box-header.with-border {
	background-color: #f1f1f1;
	border-top-left-radius: 10px;
	border-top-right-radius: 10px;
}
.header_blue {
	background-color: #2ea0e2 !important;
	color: #fff !important;
}
.box {
	border: 1px solid #00c0ef;
	box-shadow: none;
	border-radius: 10px;
}
.nobg {
	background: none !important;
	border: none !important;
}
.box-title-hd {
	color: #3c8dbc;
	font-size: 16px;
}
.blue_bg {
	background-color: #e7f3ff;
}
.m_t_15 {
	margin-top: 15px;
}
.main-footer {
	padding-left: 160px;
	padding-right: 160px;
}
.content-header > h1 {
	font-size: 22px;
	font-weight: 600;
}
h4 {
	margin-top: 0;
	margin-bottom: 20px !important;
	font-size: 16px;
	line-height: 24px;
	padding: 0 5px;
}
.form-horizontal .control-label {
	padding-top: 4px;
}
.pad_top_2 {
	padding-top: 2px !important;
}
.pad_top_0 {
	padding-top: 0px !important;
}
body.layout-top-nav .main-header h1 {
	margin: 20px 0 0 20px;
	/*display:inline-block;*/
	font-size: 30px;
}
.main-header {
	border-top: 1px solid #1287c0;
	border-left: 1px solid #1287c0;
	border-right: 1px solid #1287c0;
	width: 60%;
	margin: 1% auto 0;
	padding: 10px;
}
.short_logo {
	display: inline-block;
	float: left;
	margin: 0 0 0 20px;
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
.content-wrapper {
	background-color: #fff;
}
.container {
	width: 60%;
	border-left: 1px solid #1287c0;
	border-right: 1px solid #1287c0;
	border-bottom: 1px solid #1287c0;
	margin-bottom: 10px;
}
.box {
	border: none;
}
.box-body {
	padding: 0;
}
.box-body ul li {
	background-color: #dcf1fc;
	padding: 3px 10px 3px 30px;
	margin: 3px 0;
	list-style: none;
	position: relative;
}
.box-body ul li:before {
	display: block;
	position: absolute;
	font-family: FontAwesome;
	content: "\f04e";
	top: 5px;
	left: 10px;
	color: #9d0000;
	font-size: 12px;
	opacity: 0.8;
}
.box-body ul li a {
	color: #9d0000;
}
.box-body ul li a:hover {
	color: #9d0000;
	text-decoration: underline;
}
.content-header {
	padding: 0 0 0 10px;
}
.content-header h1 {
	background-color: #7fd1ea;
	color: #fff;
	margin: 0 auto;
	padding: 5px 0;
}
.content {
	padding: 0;
}
	/*.box-body p {
		margin-bottom:5px;
		margin-left:10px;
		padding:3px 10px;
		background-color:#9bd5f3;
	}*/
</style>
</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
<header class="main-header"> 
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <div class="short_logo"> <img src="<?php echo base_url();?>assets/images/iibf_logo_short.png"> </div>
    <div class="login-logo"><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>
      <small>(An ISO 21001:2018 Certified)</small></a></div>
  </nav>
</header>

<style>
.modal-dialog {
  position: relative;
  display: table;
  overflow-y: auto;
  overflow-x: auto;
  width: 920px;
  min-width: 300px;
}
#confirm .modal-dialog {
  position: relative;
  display: table;
  overflow-y: auto;
  overflow-x: auto;
  width: 420px;
  min-width: 400px;
}
.skin-blue .main-header .navbar {
  background-color: #fff;
}
body.layout-top-nav .main-header h1 {
  color: #0699dd;
  margin-bottom: 0;
  margin-top: 30px;
}
.container {
  position: relative;
}
.box-header.with-border {
  background-color: #7fd1ea;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
  margin-bottom: 10px;
}
.header_blue {
  background-color: #2ea0e2 !important;
  color: #fff !important;
  margin-bottom: 0 !important;
}
.box {
  border: none;
  box-shadow: none;
  border-radius: 0;
  margin-bottom: 0;
}
.nobg {
  background: none !important;
  border: none !important;
}
.box-title-hd {
  color: #3c8dbc;
  font-size: 16px;
  margin: 0;
}
.blue_bg {
  background-color: #e7f3ff;
}
.m_t_15 {
  margin-top: 15px;
}
.main-footer {
  padding-left: 160px;
  padding-right: 160px;
}
.content-header > h1 {
  font-size: 22px;
  font-weight: 600;
}
h4 {
  margin-top: 5px;
  margin-bottom: 10px !important;
  font-size: 14px;
  line-height: 18px;
  padding: 0 5px;
  font-weight: 600;
  text-align: justify;
}
.form-horizontal .control-label {
  padding-top: 4px;
}
.pad_top_2 {
  padding-top: 2px !important;
}
.pad_top_0 {
  padding-top: 0px !important;
}
div.form-group:nth-child(odd) {
  background-color: #dcf1fc;
  padding: 5px 0;
}
#confirmBox {
  display: none;
  background-color: #eee;
  border-radius: 5px;
  border: 1px solid #aaa;
  position: fixed;
  width: 300px;
  left: 50%;
  margin-left: -150px;
  padding: 6px 8px 8px;
  box-sizing: border-box;
  text-align: center;
  z-index: 1;
  box-shadow: 0 1px 3px #000;
}
#confirmBox .button {
  background-color: #ccc;
  display: inline-block;
  border-radius: 3px;
  border: 1px solid #aaa;
  padding: 2px;
  text-align: center;
  width: 80px;
  cursor: pointer;
}
#confirmBox .button:hover {
  background-color: #ddd;
}
#confirmBox .message {
  text-align: left;
  margin-bottom: 8px;
}
.form-group {
  margin-bottom: 10px;
}
.form-horizontal .form-group {
  margin-left: 0;
  margin-right: 0;
}
.form-control {
  border-color: #888;
}
.form-horizontal .control-label {
  font-weight: normal;
}
a.forget {
  color: #9d0000;
}
a.forget:hover {
  color: #9d0000;
  text-decoration: underline;
}
ol li {
  line-height: 18px;
}
.example {
  text-align: left !important;
  padding: 0 10px;
}
</style>
<?php
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>

<div class="container">
  <section class="content-header box-header with-border" style="height: 45px; background-color: #1287C0; ">
    <h1 class="register">CSC BCBF Exam Venue List</h1>
    <br />
  </section>
  <div> 
    <!-- Start Get Details -->
    <?php
if (!empty($row)) {
    if (isset($row['msg']) && $row['msg'] != '') {
        echo '<div class="alert alert-danger alert-dismissible">' . $row['msg'] . '</div>';
    }
}

?>

  </div>
  <section class="">
    <div class="row">
      <div class="col-md-12" style="">
        <?php if ($this->session->flashdata('flsh_msg') != '') {?>
        <div class="alert alert-danger"> <?php echo $this->session->flashdata('flsh_msg'); ?> </div>
        <?php }?>
        <?php
if ($this->session->flashdata('error') != '') {?>
        <div class="alert alert-danger alert-dismissible" id="error_id">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo $this->session->flashdata('error');?> </div>
        <?php } if ($this->session->flashdata('success') != '') {?>
        <div class="alert alert-success alert-dismissible" id="success_id">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo $this->session->flashdata('success');?> </div>
        <?php } if (validation_errors() != '') { ?>
        <div class="alert alert-danger alert-dismissible" id="error_id">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo validation_errors(); ?> </div>
        <?php } if ($var_errors != '') { ?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo $var_errors; ?> </div>
        <?php } ?>
        
      </div>
    </div>
  </section>
  
  <form class="form-horizontal" data-parsley-validate="parsley" name="blendedAddForm" id="blendedAddForm" action="<?php echo site_url('CSC_BCBF_venue_list'); ?>" autocomplete="off"  method="post"  enctype="multipart/form-data">
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            
         <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">State <span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <select class="form-control" id="main_state" name="main_state" required >
                      <option value="">Select</option>
                      <?php if(count($states) > 0){
                                foreach($states as $row1){ 	?>
                      <option value="<?php echo $row1['state_code'];?>" <?php echo  set_select('main_state', $row1['state_code']); ?>><?php echo $row1['state_name'];?></option>
                      <?php } } ?>
                    </select>
                    
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Center <span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <!--<input type="text" class="form-control" id="main_city" name="main_city" placeholder="City" required value="<?php //echo set_value('main_city');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" >-->
                    
                    <select class="form-control" id="main_center" name="main_center" required >
                      <option value="">Select</option>
                      <?php if(count($centers) > 0){
                                foreach($centers as $row1){ 	?>
                      <option value="<?php echo $row1['center_code'];?>" <?php echo  set_select('main_center', $row1['center_code']); ?>><?php echo $row1['center_name'];?></option>
                      <?php } } ?>
                    </select>
                    
                  </div>
                  
                </div>
                <div class="form-group" style='text-align:center'>
                
                    <input type="submit" name="btnSubmit" class="btn btn-info" id="btnSubmit" value="Submit" style='align-items:center'>
                    <a href="<?php echo site_url('CSC_BCBF_venue_list'); ?>" class="btn btn-info" style="">Clear</a>
                </div>
                
                <?php if(count($center_listing) > 0)
				{	?>
                <div class="form-group">
                
                 	<table id="listitems2" class="table table-bordered table-striped dataTables-example">
                <thead>
                  <tr>
                    <th id="srNo">S.No.</th>
                    <th id="centername">Venue Code</th>
                    <th id="ststus">Venue Name</th>
                    <th id="name">Venue Address</th>
                    <th id=centertype>Venue Pincode</th>
                    <!--<th id=accradation>Vendor Code</th>-->
                    <!--<th id="batchaction">Venue Flag</th>-->
                  </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                  <?php if( count( $center_listing )  > 0 ) { 
                          $i = 1;
                          foreach($center_listing as $center ) { ?>
                  <tr>
                    <td><?php echo $i;?></td>
                    <td><?php echo $center['venue_code'];?>
					</td>
                     
                    <td>
				    <?php echo $center['venue_name'];?>
                    </td>
                    <td><?php echo $center['venue_addr1'].' '.$center['venue_addr2'].' '.$center['venue_addr3'].' '.$center['venue_addr4'].' '.$center['venue_addr5'];?></td>
                    <td><?php echo $center['venue_pincode'];?></td>
                    <!--<td><?php //echo $center['vendor_code'];?></td>-->
                    <!--<td><?php //echo $center['venue_flag'];?></td>-->
                    
                  </tr>
                  <?php $i++; } }?>  
                </tbody>
              </table>
          </div>
              <?php } ?>  
           </div>
            
          
        </div>
      </div>
    </section>
    
  </form>
</div>

<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script> 
<script src="<?php echo base_url();?>js/validation_blended.js?<?php echo time();?>"></script> 
<script>


$('#main_state').on('change',function(){
var state_code = $(this).val();
if(state_code){
	$.ajax({
		type:'POST',
		url: site_url+'CSC_BCBF_venue_list/getCenter',
		data:'state_code='+state_code,
		success:function(html){
			$('#main_center').show();
			$('#main_center').html(html);
		}
	});
}else{
	$('#main_center').html('<option value="">Select State First</option>');
}
});

function createCookie(name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}

$(function(){
  function readCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) === ' ') c = c.substring(1, c.length);
      if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
  }

  if(readCookie('member_register_form'))
  {
    $('#error_id').html(''); 
    $('#error_id').removeClass("alert alert-danger alert-dismissible");
    createCookie('member_register_form', "", -1); 
  }
  

   /*$(document).keydown(function(event) {
        if (event.ctrlKey==true && (event.which == '67' || event.which == '86')) {
            if(event.which == '67')
      {
        alert('Key combination CTRL + C has been disabled.');
      }
      if(event.which == '86')
      {
        alert('Key combination CTRL + V has been disabled.');
      }
      event.preventDefault();
         }
    });*/
  
  $("body").on("contextmenu",function(e){
        return false;
    });
    $(this).scrollTop(0);

});

</script> 
<footer class="main-footer">
  <div class="pull-right hidden-xs"> Powered By <b>ESDS</b> </div>
  <strong>Copyright &copy;&nbsp;<?php echo date("Y"); ?> <a href="javascript:void(0);">ESDS</a>.</strong> All rights reserved. </footer>
</div>


<!-- ./wrapper --> 
<!-- Bootstrap 3.3.6 --> 
<script src="<?php echo base_url()?>assets/admin/bootstrap/js/bootstrap.min.js"></script> 
<!-- FastClick --> 
<script src="<?php echo base_url()?>assets/admin/plugins/fastclick/fastclick.js"></script> 
<!-- AdminLTE App --> 
<script src="<?php echo base_url()?>assets/admin/dist/js/app.min.js"></script> 
<!-- AdminLTE for demo purposes --> 
<script src="<?php echo base_url()?>assets/admin/dist/js/demo.js"></script>
</body>
</html>