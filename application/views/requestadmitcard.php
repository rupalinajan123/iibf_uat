
<script src="<?php echo base_url();?>js/validation_admitcardrequest.js?<?php echo time(); ?>"></script> 
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
  <section class="content-header box-header with-border" style="height: 48px; background-color: #1287C0; ">
    <h1 class="register"></h1>
  </section>
  <div> 
    <!-- Start Get Details -->
    <?php
  if(!empty($selectedRecord)) { 
	if(isset($selectedRecord['msg']) && $selectedRecord['msg'] != ''){ 
		echo '<div class="alert alert-danger alert-dismissible">'.$selectedRecord['msg'].'</div>'; 
	}
  }
?>
  </div>
  <section class="">
    <div class="row">
      <div class="col-md-12" style=""> 
        <!-- /.box-header --> 
        <!-- form start -->
        <?php if($this->session->flashdata('error')!=''){?>
        <div class="alert alert-danger alert-dismissible" id="error_id">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo $this->session->flashdata('error'); ?> </div>
        <?php } if($this->session->flashdata('success')!=''){ ?>
        <div class="alert alert-success alert-dismissible" id="success_id">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo $this->session->flashdata('success'); ?> </div>
        <?php } 
			 if(validation_errors()!=''){?>
        <div class="alert alert-danger alert-dismissible" id="error_id">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo validation_errors(); ?> </div>
        <?php }
			  if($var_errors!='')
			  {?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo $var_errors; ?> </div>
        <?php }  ?>
      </div>
    </div>
  </section>
 
  <!-- Close Get Details -->
<script src="<?php echo base_url();?>js/parsley.min.js"></script>
<form class="form-horizontal" name="requestForm" id="requestForm"  method="post"   action="<?php echo base_url()?>Requestadmitcard/send_mail">
    <section class="content">
      <div class="row">
        <div class="col-md-12"> 
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Enter Details</h3>
            </div>
            <div class="box-body">
              <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none"> <span>display ajax response errors here</span> </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Enter exam code<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="exam_code" name="exam_code" placeholder="Enter exam code" value="" required data-parsley-type="number">
                  <span class="error">
                  </span> </div>
                </div>
                
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Enter exam period<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="exam_period" name="exam_period" placeholder="Enter exam period" value="" required data-parsley-type="number">
                  <span class="error">
                  </span> </div>
                </div>
            </div>
          </div>
          <div class="box box-info">
            
            
            <div class="box-footer">
              <div class="col-sm-6 col-sm-offset-3"> 
              	<a href="javascript:void(0);" class="btn btn-info"onclick="javascript:return bnqcheckform();" >Submit</a>
                <!--<input type="submit" name="submit" value="submit" class="btn btn-info" />-->
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">

 
 
