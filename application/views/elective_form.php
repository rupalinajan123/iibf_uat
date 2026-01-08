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
<form class="form-horizontal" name="esubject" id="esubject"  method="post" >
  <div class="container">
    
    <section class="content">
      <div class="row"> 
      </div>
      <div class="row">
        <div class="col-md-12"> 
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Basic Details</h3>
            </div>
             <?php if($this->session->flashdata('error')!=''){?>
                
                <div class="alert alert-danger alert-dismissible" id="error_id">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
                
              <?php } if($this->session->flashdata('success')!=''){ ?>
                <div class="alert alert-success alert-dismissible" id="success_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                <?php echo $this->session->flashdata('success'); ?>
              </div>
             <?php } 
			 if(validation_errors()!=''){?>
                <div class="alert alert-danger alert-dismissible" id="error_id">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo validation_errors(); ?>
                </div>
              <?php }?>
            <?php if(!isset($thank)){?>
            <div class="box-body">
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Registration number</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="regnumber" name="regnumber" placeholder="Registration number"  value="<?php echo set_value('regnumber');?>" required>
                  <span class="error"></span>
                  </div>
                </div>
                
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Email ID</label>
                <div class="col-sm-5">
                  <input type="email" class="form-control" id="email_id" name="email_id" placeholder="Email"  value="<?php echo set_value('email_id');?>" data-parsley-type="email" data-parsley-maxlength="45" maxlength="45" data-parsley-trigger-after-failure="focusout" required>
                  <span class="error"></span>
                  </div>
                  (Enter Registered Emai ID) 
                </div>
                
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Mobile</label>
                <div class="col-sm-5">
                  <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="mobile"  value="<?php echo set_value('mobile');?>"  maxlength="10" pattern="[0-9]{10}" required="required">
                  <span class="error"></span>
                  </div>
                  (10 Digit, Enter Registered Mobile Number) 
                </div>
               <?php
               	$subjects=$this->master_model->getRecords('subject_master',array('exam_code'=>$this->config->item('examCodeCaiib'),'subject_delete'=>'0','group_code'=>'E','exam_period'=>117));
				
			   ?> 
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Select subject</label>
                <div class="col-sm-5">
                  <select name="elec_subject" id="elec_subject" class="form-control" required>
                    <option value="" >Select</option>
                    <?php 
						foreach($subjects as $rec){
					?>
                    <option value="<?php echo $rec['subject_code']?>" ><?php echo $rec['subject_description']?></option>
                    <?php }?>
                  </select>
                  <span class="error"></span>
                  </div>
                </div>
                
            </div>
          </div>
          <!-- Basic Details box closed-->
          <div class="box box-info">
            <div class="box-footer">
              <div class="col-sm-5 col-sm-offset-5"> 
              <input type="submit" class="btn btn-info" name="Submit" value="Submit">
              </div>
            </div>
          </div>
          <?php }?>
          <?php if(isset($thank)){?>
          <div class="alert alert-success">
              <strong>Thank You!</strong>
           </div>
          <?php }?>
        </div>
      </div>
    </section>
  </div>
</form>
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>js/validation.js"></script> 
