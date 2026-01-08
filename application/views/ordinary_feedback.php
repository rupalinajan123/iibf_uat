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
h6{
	color : #006ba9;
	font-size : 14px;
	font-weight : bold;
}
.col-sm-3 {
	padding-top: 7px;
}
</style>
<!--
div.form-group:nth-child(odd) {
	background-color: #dcf1fc;
	padding: 5px 0;
}
-->
<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
<form class="form-horizontal" name="frmFeedback" id="frmFeedback"  method="post" action="<?php echo base_url();?>OrdinaryFeedbackForm" enctype="multipart/form-data" data-parsley-validate="parsley">
  <div class="container">
    <!--<section class="content-header">
           <h1 class="register"> 
          Feedback From Ordinary Members of the Institute 
            </h1><br/>
    </section>-->
    <section class="content">
      <div class="row">
        <div class="col-md-12"> 
		
		  <!-- Validation error flash messages start--->
		  		     <?php //echo validation_errors(); ?>
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
              <?php }
			  if(@$var_errors!='')
			  {?>
                <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                <?php echo $var_errors; ?>
        	    </div>
				 
				 <?php 
			  } 
			 ?>
		  <!-- End of validation msg-->
          
          <!-- Basic Details box Start-->
          <div class="box box-info">
            <!--<div class="box-header with-border">
              <h3 class="box-title">Details</h3>
            </div>-->
          
            <div class="box-body">
              <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none"> <span>display ajax response errors here</span> </div>
              <div class="form-group">
                
				
				
				<div class="form-group">
					<h6><p>1. MEMBERS ARE AWARE OF VARIOUS DIPLOMA/CERTIFICATE COURSES OF THE INSTITUTE</p></h6>
					
					<div class="col-sm-3">
				
					  <input type="radio" class="minimal"   name="q1_answer" required value="Strongly Agree"><!--checked-->
					  Strongly Agree </br>
					  <input type="radio" class="minimal"  name="q1_answer" required  value="Agree">
					  Agree </br>
					  <input type="radio" class="minimal "  name="q1_answer" required value="Disagree">
					  Disagree </br>
					  <input type="radio" class="minimal"  name="q1_answer" required value="Strongly Disagree">
					  Strongly Disagree </br>
					  <input type="radio" class="minimal"  name="q1_answer" required value="Neutral">
					  Neutral 
					  <?php //echo form_error('gender');?>
					  </span> 
					</div>
                </div>
				<div class="form-group">
					<h6><p>2. OBJECTIVE OF DIPLOMA/CERTIFICATES COURSES ARE CLEAR IN THE COURSE BOOKLETS</p></h6>
					<div class="col-sm-3">
					  <input type="radio" class="minimal" name="q2_answer" required value="Strongly Agree" ><!--checked-->
					  Strongly Agree </br>
					  <input type="radio" class="minimal"  name="q2_answer" required value="Agree" >
					  Agree </br>
					  <input type="radio" class="minimal"  name="q2_answer" required value="Disagree" >
					  Disagree </br>
					  <input type="radio" class="minimal"  name="q2_answer" required value="Strongly Disagree" >
					  Strongly Disagree </br>
					  <input type="radio" class="minimal" name="q2_answer" required value="Neutral" >
					  Neutral 
					  <?php //echo form_error('gender');?>
					  </span> 
					</div>
                </div>
				<div class="form-group">
					<h6><p>3. DIPLOMA/CERTIFICATE COURSES OFFERED BY IIBF ARE RELEVANT TO CURRENT ENVIRONMENT</p></h6>
					<div class="col-sm-3">
					  <input type="radio" class="minimal"  name="q3_answer" required value="Strongly Agree"><!--checked-->
					  Strongly Agree </br>
					  <input type="radio" class="minimal"  name="q3_answer" required value="Agree">
					  Agree </br>
					  <input type="radio" class="minimal" name="q3_answer" required value="Disagree">
					  Disagree </br>
					  <input type="radio" class="minimal"  name="q3_answer" required value="Strongly Disagree">
					  Strongly Disagree </br>
					  <input type="radio" class="minimal"  name="q3_answer" required value="Neutral">
					  Neutral 
					  <?php //echo form_error('gender');?>
					  </span> 
					</div>
                </div>
				<div class="form-group">
					<h6><p>4. STUDY SUPPORT PROVIDED FOR THE DIPLOMA/CERTIFICATE COURSES ARE ADEQUATE</p></h6>
					<div class="col-sm-3">
					  <input type="radio" class="minimal" name="q4_answer" required value="Strongly Agree" ><!--checked-->
					  Strongly Agree </br>
					  <input type="radio" class="minimal" name="q4_answer" required value="Agree" >
					  Agree </br>
					  <input type="radio" class="minimal" name="q4_answer" required value="Disagree" >
					  Disagree </br>
					  <input type="radio" class="minimal" name="q4_answer" required value="Strongly Disagree" >
					  Strongly Disagree </br>
					  <input type="radio" class="minimal" name="q4_answer" required value="Neutral" >
					  Neutral 
					  <?php //echo form_error('gender');?>
					  </span> 
					</div>
                </div>
				<div class="form-group">
					<h6><p>5. SUPPORT SERVICES GIVEN TO THE MEMBERS FOR TAKING UP THE COURSES ARE ADEQUATE</p></h6>
					<div class="col-sm-3">
					  <input type="radio" class="minimal" name="q5_answer" required value="Strongly Agree" ><!--checked-->
					  Strongly Agree </br>
					  <input type="radio" class="minimal" name="q5_answer" required value="Agree" >
					  Agree </br>
					  <input type="radio" class="minimal" name="q5_answer" required value="Disagree" >
					  Disagree </br>
					  <input type="radio" class="minimal" name="q5_answer" required value="Strongly Disagree" >
					  Strongly Disagree </br>
					  <input type="radio" class="minimal" name="q5_answer" required value="Neutral" >
					  Neutral 
					  <?php //echo form_error('gender');?>
					  </span> 
					</div>
                </div>
				
				<div class="form-group">
                  <label for="roleid" class="control-label"><h6><p>6. PLEASE IDENTIFY AREA(S) WHERE YOU THINK THE COURSE (OR SECTION) COULD BE IMPROVED</p></h6></label>
                  <div class="col-sm-12">
				    <label for="roleid" class="col-sm-3 control-label">COURSE NAME:</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="q6_answer_i" name="q6_answer_i" placeholder=""  value="<?php echo set_value('q6_answer_i');?>" data-parsley-pattern="/^[a-zA-Z/ ]+$/" data-parsley-maxlength="250">
                    <span class="error">
                    <?php //echo form_error('nameOfBank');?>
                    </span> </div></div>
					<div class="col-sm-12">
				    <label for="roleid" class="col-sm-3 control-label">IMPROVEMENT AREA : </label>
					<div class="col-sm-6">
				   <textarea id="q6_answer_ii" name="q6_answer_ii" class="form-control"  data-parsley-maxlength="500"   data-parsley-id="5"></textarea><ul class="parsley-errors-list" id="parsley-id-5"></ul>
                                      <!-- data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"-->   
				     </div>
				     </div>
                </div>
				<div class="form-group">
                  <label for="roleid" class="control-label"><h6><p>7. SPECIFIC RESON/S FOR NOT TAKING UP THE DIPLOMA/CERTIFICATE COURSE OF IIBF</p></h6></label>
                  <div class="col-sm-9">
				   <textarea id="q7_answer" name="q7_answer" class="form-control " data-parsley-maxlength="500" data-parsley-id="5"></textarea><ul class="parsley-errors-list" id="parsley-id-5"></ul>
                     <!-- data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"--> 
				 </div>
                </div>
            </div>    
          <!-- Basic Details box closed--> 
          
          
          <!-- Communication Address Details box Closed-->
          
          <div class="box box-info">
           <!-- <div class="box-header with-border">
              <h3 class="box-title">
                <input name="declaration1" value="1" type="checkbox" required="required" 
			  <?php /*if(set_value('declaration1'))
			  {
				  echo set_radio('declaration1', '1');
				 }*/ ?>>
                &nbsp; I Accept</h3>
            </div>-->
            <!--<div class="form-group m_t_15">
              <label for="roleid" class="col-sm-3 control-label">Security Code <span style="color:#F00">*</span></label>
              <div class="col-sm-2">
                <input type="text" name="code" id="code" required class="form-control " >
                <span class="error" id="captchaid" style="color:#B94A48;"></span> </div>
              <div class="col-sm-3">
                <div id="captcha_img">
                  <?php echo $image;?>
                </div>
                <span class="error">
                <?php //echo form_error('code');?>
                </span> </div>
              <div class="col-sm-2"> <a href="javascript:void(0);" id="new_captcha" class="forget" >Change Image</a> <span class="error">
                <?php //echo form_error('code');?>
                </span> </div>
            </div>-->
            <div class="box-footer">
             <div class="col-sm-6 col-sm-offset-3"> 
              
				<center>
			  <!--<a href="javascript:void(0);" class="btn btn-info" onclick="javascript:return dracheckform();" id="preview" name="preview">Submit</a> -->
			   <button type="submit" class="btn btn-info"  name="btn_submit" id="btn_submit">Submit</button>
                <!--<input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Preview and Proceed for Payment">-->
                <button type="reset" class="btn btn-default"  name="btnReset" id="btnReset">Reset</button>
                </center>
			  <!--<input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Preview and Proceed for Payment"> 
             
                <button type="reset" class="btn btn-default"  name="btnReset" id="btnReset">Reset</button>-->
              </div>
            </div>
          </div>
         </div>
        </div>
    </section>
	<div class="modal fade" id="confirm"  role="dialog" >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"></h4>
          </div>
          <div class="modal-body">
            <p style="color:#F00"> <strong>VERY IMPORTANT</strong><br>
              I confirm that all the detail entered are correct as per my knowledge.</p>
          </div>
          <div class="modal-footer"> 
            <!--  <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="preview();">Confirm</button>-->
            <input type="submit" name="btnSubmit" class="btn btn-primary" id="btnSubmit" value="Confirm" >
          </div>
        </div>
        <!-- /.modal-content --> 
      </div>
      <!-- /.modal-dialog --> 
    </div>
  </div>
</form>
 <!--Nature of present assignment 
contact person in case of emargency telephone/mobile

Are you a JAIIB/CAIIB?
If yes, in waht way it has contributed to your career (Work Area)-->
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script> 
<!--<script src="<?php echo base_url();?>js/validation_dra_register.js?<?php echo time(); ?>"></script> -->
