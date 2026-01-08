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
	background-color:#fff;
}
body.layout-top-nav .main-header h1 {
	color:#0699dd;
	margin-bottom:0;
	margin-top:30px;
}
.container {
	position:relative;
}
.box-header.with-border {
	background-color:#7fd1ea;
	border-top-left-radius:0;
	border-top-right-radius:0;
	margin-bottom:10px;
}
.header_blue {
	background-color:#2ea0e2 !important;
	color:#fff !important;
	margin-bottom:0 !important;
}
.box {
	border:none;
	box-shadow:none;
	border-radius:0;
	margin-bottom:0;
}
.nobg {
	background:none !important;
	border:none !important;
}
.box-title-hd {
	color:#3c8dbc;
	font-size:16px;
	margin:0;
}
.blue_bg {
	background-color:#e7f3ff;
}
.m_t_15 {
	margin-top:15px;
}
.main-footer {
	padding-left:160px;
	padding-right:160px;
}
.content-header > h1 {
	font-size:22px;
	font-weight:600;
}
h4 {
	margin-top:5px;
	margin-bottom:10px !important;
	font-size:14px;
	line-height:18px;
	padding:0 5px;
	font-weight:600;
	text-align:justify;
}
.form-horizontal .control-label {
	padding-top:4px;
}
.pad_top_2 {
	padding-top:2px !important;
}
.pad_top_0 {
	padding-top:0px !important;
}
 div.form-group:nth-child(odd) {
 background-color:#dcf1fc;
 padding:5px 0;
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
	z-index:1;
	box-shadow:0 1px 3px #000;
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
	margin-bottom:10px;
}
.form-horizontal .form-group {
	margin-left:0;
	margin-right:0;
}
.form-control {
	border-color:#888;
}
.form-horizontal .control-label {
	font-weight:normal;
}
a.forget {
	color:#9d0000;
}
a.forget:hover {
	color:#9d0000;
	text-decoration:underline;
}
ol li {
	line-height:18px;
}
.example {
	text-align:left !important;
	padding:0 10px;
}
</style>
<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>

<form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data" action="<?php echo base_url()?>FinQuest">
  <div class="container">
    <section class="content-header box-header with-border" style="height: 48px; background-color: #1287C0; ">
      <h1 class="register">IIBF Vision Subscription</h1>
    </section>
    <div class="row"> 
      <!--<div class="col-md-1"></div>     -->
      <div class="col-md-12"> </div>
      <!--<div class="col-md-1"></div>--> 
    </div>
    <div class="row">
      <div class="col-md-12"> 
        <!-- Horizontal Form --> 
        <div class="box box-info">
      
          <!-- /.box-header --> 
          <!-- form start -->

          <div class="box-body">
            <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none"> <span>display ajax response errors here</span> </div>
            <div class="form-group">  
            <div class="col-md-12"> 
           <br>
                    <center><div style="font-size:25px" >  You have successfully subscribed for IIBF Vision.  </div><br>  
<br>  </center>
              
                    <br><?php $start_date = date('d/m/Y');
$end_date = date('d/m/Y', strtotime('+1 years'));
$subscription_range = $start_date." to ".$end_date;?>
                   <center> <strong>Your subscription number is</strong> <?php echo $subscription_number;?> <strong><br /> Validity of your subscription is for 1 year i.e. from   </strong><?php echo $valid_period  ?><br>
                    <br>
                  Please note down your subscription no and valid period of subscription for future reference.<br><br>   <br>      <br>  </center> 
                  </div>
                  
            </div>
           
          </div>
        </div>
      </div>
    </div>
    </section>
  </div>
   <br>     <br> 
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script> 
