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
.content-wrapper {
	border-bottom: 1px solid #1287c0;
	border-left: 1px solid #1287c0;
	border-right: 1px solid #1287c0;
	width: 60%;
	margin:0 auto 10px !important;
	padding:0 10px;
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
.content-header {
	padding:0;
	margin-bottom:10px;
}
.nobg {
	background: rgba(0, 0, 0, 0) none repeat scroll 0 0 !important;
	border: medium none !important;
}
.email {
	line-height:18px !important;
}
.box-body {
	padding: 0;
}
.example {
	text-align:left !important;
}
.example select {
	padding:5px 10px !important;
	border:1px solid #888 !important;
	border-radius:0 !important;
}
</style>
<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1 class="register"> Examination Application(Registration) for Non-Member category candidates <br/>
      (Please read "Instructions to Applicants" before filling up the form) </h1>
    <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>--> 
  </section>
  
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog"> 
      
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Instructions to Applicants</h4>
        </div>
        <div class="modal-body">
          <table>
            <tbody>
              <tr>
                <td><b>1. </b></td>
                <td><b><u> Eligibility:</u></b></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td> a) &nbsp;Membership is open to the employees of recognized banking establishments both in the nationalized as well as private sector including the Reserve Bank of India, State Bank of India, other Financial Institutions,  both Central and State  Co-operative Banks and any other institutions in India  who are Institutional Members of the Institute as may be approved by the Council from time to time. </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td> b) &nbsp;<b>If the name of your organization does not appear in the drop box, please mail to <a href="mailto:mem-services@iibf.org.in">mem-services@iibf.org.in</a> with details of your organization.</b></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><b>2. </b></td>
                <td><b><u> Enrolment Fee:</u> </b></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>One time life Membership fee (for candidates in India) is <b>Rs. 2/-</b> (Fees Rs.1500/- + Service Tax@15% Rs.225/- = Rs.1725/-)</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><b>3. </b></td>
                <td><b><u> Pre-requisites for online enrolment for Membership:</u> </b></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td> I - Applicant should have scanned copy of his/her  i) photograph ii) signature and iii) id proof (ensuring that all are within the required specifications as under) <br>
                  <ol type="a">
                    <li>Images format should be in JPG 8bit and size should be minimum 8KB and maximum 20KB.</li>
                    <li>Image Dimension of Photograph should be 100(Width) * 120(Height) Pixel only;</li>
                    <li>Image Dimension of Signature should be 140(Width) * 60(Height) Pixel only.</li>
                    <li>Image Dimension of ID Proof should be 400(Width) * 420(Height) Pixel only. ID Proof should contain Name, Photo, Date of Birth and Signature. Size should be minimum 8KB and maximum 25KB.</li>
                    <li>ID proof can be any one of the following: </li>
                    <ul>
                      <li>Aadhaar ID</li>
                      <li>Driving License</li>
                      <li>Election Voters card</li>
                      <li>Employers card</li>
                      <li>PAN card</li>
                      <li>Passport</li>
                    </ul>
                  </ol>
                  II - To make online payment, an applicant should keep ready the necessary details about his/her Credit/Debit Card/Net Banking Details) <br>
                  III - Applicant should have a valid personal e-mail id. <br>
                  Note:  Do not upload your Credit Card/Debit Card scanned image with the application. </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><b>4. </b></td>
                <td><b><u> Procedure for Enrolment:</u></b></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><ol type="i">
                    <li>Visit Institute's web site www.iibf.org.in</li>
                    <li>Click on 'online membership registration'.</li>
                    <li>Read 'Instruction to applicants' carefully.</li>
                    <li>Fill up all the online application form,(all the fields mark '*' are mandatory), upload photo, signature, ID proff and follow the on-screen instructions to complete the registration process.</li>
                    <li>On successful completion of enrolment a confirmation SMS/email will be sent to the candidate intimating the membership number(membership number is your login id), password.</li>
                    <li>In case, even after 2/3 working days after enrolment  no confirmation is received intimating the membership details,  applicant should  take  up the matter with the Institute. (write to <a href="mailto:onlineservices@iibf.org.in">onlineservices@iibf.org.in</a> providing following details: 1) Membership number 2) Full Name 3) Date of Birth 4) Payment transaction details 5) Mobile no. and details problem/exact text of error message etc..)</li>
                    <li>For all failed transactions the amount is debited to applicant's Account, Institute will  refund such amount within seven  working days from the date of the transaction..</li>
                  </ol></td>
              </tr>
              <tr>
                <td><b>5. </b></td>
                <td><b><u>General:</u> </b></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><ol type="a">
                    <li>A permanent I-card will be mailed to the applicant at the address given in the application.</li>
                    <li>The permanent I-card should be produced at the time of examination and whenever demanded for availing other services.</li>
                    <li>Enrolled members can view his/her profile/payment details using the login/password and also update his/her profile except for name, date of birth, photo, signature and ID proof.</li>
                    <li>Enrolled member can also apply for all other services of the Institute with the same login and password as and when available.</li>
                    <li>Any communication in regard to membership enrolment should sent to <a href="mailto:onlineservices@iibf.org.in">onlineservices@iibf.org.in</a> </li>
                  </ol></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <form class="form-horizontal" name="nonmemAddForm" id="nonmemAddForm"  method="post"  enctype="multipart/form-data">
    <input type="hidden" name="extype" id="extype" value="<?php echo $examinfo[0]['exam_type'];?>">
    <input type="hidden" id="exname" name="exname"  value=" <?php echo $examinfo[0]['description'];?>">
    <input type="hidden" id="excd" name="excd"  value="<?php echo $this->input->get('ExId');?>">
    <input id="examcode" name="examcode" type="hidden" value="<?php echo base64_decode($this->input->get('ExId'));?>">
    <input id="eprid" name="eprid" type="hidden" value="<?php echo $examinfo[0]['exam_period'];?>">
    <input id="exmonth" name="exmonth" type="hidden" value="<?php echo $examinfo[0]['exam_month'];?>">
    <section class="content">
      <div class="row">
        <div class="col-md-12"> Pl note that if you have  already registered for any examination under Non-member Category in the past,  the same Registration Number allotted to you can be used for registering for other examinations(other than DB&F Exam) applicable for Non-members as per the eligibility criteria given.  Already Registered candidates has to apply for examinations by login using their USER ID and PASSWORD already provided - <a href=<?php echo base_url();?>nonmem><span style="color:#090">Click here for Login</span></a><br/>
          <span style="color:#F00">Enter your details carefully, correction may not be possible later.</span> 
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Basic Details</h3>
            </div>
            <!-- /.box-header --> 
            <!-- form start -->
            
            <?php //echo validation_errors(); ?>
            <?php if($this->session->flashdata('error')!=''){?>
            <div class="alert alert-danger alert-dismissible" id="error_id">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 
              <?php echo $this->session->flashdata('error'); ?> </div>
            <?php } if($this->session->flashdata('success')!=''){ ?>
            <div class="alert alert-success alert-dismissible" id="success_id">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>--> 
              <?php echo $this->session->flashdata('success'); ?> </div>
            <?php } 
			 if(validation_errors()!=''){?>
            <div class="alert alert-danger alert-dismissible" id="error_id">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 
              <?php echo validation_errors(); ?> </div>
            <?php } 
			 ?>
            <div class="box-body">
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">First Name <span style="color:#f00">*</span></label>
                <div class="col-sm-2">
                  <select name="sel_namesub" id="sel_namesub" class="form-control" required>
                    <option value="" >Select</option>
                    <option value="Mr." <?php echo  set_select('sel_namesub', 'Mr.'); ?>>Mr.</option>
                    <option value="Mrs." <?php echo  set_select('sel_namesub', 'Mrs.'); ?>>Mrs.</option>
                    <option value="Ms." <?php echo  set_select('sel_namesub', 'Ms.'); ?>>Ms.</option>
                    <option value="Dr." <?php echo  set_select('sel_namesub', 'Dr.'); ?>>Dr.</option>
                    <option value="Prof." <?php echo  set_select('sel_namesub', 'Prof.'); ?>>Prof.</option>
                  </select>
                  <span class="error" id="tiitle_error">
                  <?php //echo form_error('firstname');?>
                  </span> </div>
                (Max 30 Characters)
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required value="<?php echo set_value('firstname');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z- ]+$/" data-parsley-maxlength="30" >
                  <span class="error">
                  <?php //echo form_error('firstname');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name"  value="<?php echo set_value('middlename');?>" data-parsley-pattern="/^[a-zA-Z- ]+$/" data-parsley-maxlength="30"    >
                  <span class="error">
                  <?php //echo form_error('middlename');?>
                  </span> </div>
                (Max 30 Characters) </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="middlename" name="lastname" placeholder="Last Name"  value="<?php echo set_value('lastname');?>" data-parsley-pattern="/^[a-zA-Z- ]+$/" data-parsley-maxlength="30" >
                  <span class="error">
                  <?php //echo form_error('lastname');?>
                  </span> </div>
                (Max 30 Characters) </div>
            </div>
          </div>
          <!-- Basic Details box closed-->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Contact Details</h3>
            </div>
            <div class="box-header with-border nobg">
              <h6 class="box-title-hd">Office/Residential Address for communication (Pl do not repeat the name of the Applicant, Only Address to be typed)</h6>
            </div>
            <div class="box-body">
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line1 <span style="color:#f00">*</span></label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" required value="<?php echo set_value('addressline1');?>"  data-parsley-maxlength="30" data-parsley-pattern="/^[a-zA-Z0-9/ ]+$/">
                  <span class="error">
                  <?php //echo form_error('addressline1');?>
                  </span> </div>
                (Max 30 Characters) </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line2</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2"  value="<?php echo set_value('addressline2');?>"  data-parsley-maxlength="30" data-parsley-pattern="/^[a-zA-Z0-9/ ]+$/">
                  <span class="error">
                  <?php //echo form_error('addressline2');?>
                  </span> </div>
                (Max 30 Characters) </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line3</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line3"  value="<?php echo set_value('addressline3');?>"  data-parsley-maxlength="30" data-parsley-pattern="/^[a-zA-Z0-9/ ]+$/">
                  <span class="error">
                  <?php //echo form_error('addressline3');?>
                  </span> </div>
                (Max 30 Characters) </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line4</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4"  value="<?php echo set_value('addressline4');?>" data-parsley-maxlength="30" data-parsley-pattern="/^[a-zA-Z0-9/ ]+$/">
                  <span class="error">
                  <?php //echo form_error('addressline4');?>
                  </span> </div>
                (Max 30 Characters) </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">District <span style="color:#f00">*</span></label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="district" name="district" placeholder="District" required value="<?php echo set_value('district');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >
                  <span class="error">
                  <?php //echo form_error('district');?>
                  </span> </div>
                (Max 30 Characters) </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">City <span style="color:#f00">*</span></label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo set_value('city');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >
                  <span class="error">
                  <?php //echo form_error('city');?>
                  </span> </div>
                (Max 30 Characters) </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">State <span style="color:#f00">*</span></label>
                <div class="col-sm-3">
                  <select class="form-control" id="state" name="state" required >
                    <option value="">Select</option>
                    <?php if(count($states) > 0){
                                foreach($states as $row1){ 	?>
                    <option value="<?php echo $row1['state_code'];?>" <?php echo  set_select('state', $row1['state_code']); ?>><?php echo $row1['state_name'];?></option>
                    <?php } } ?>
                  </select>
                  <input hidden="statepincode" id="statepincode" value="">
                </div>
                <label for="roleid" class="col-sm-3 control-label">Pincode/Zipcode <span style="color:#f00">*</span></label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode/Zipcode" required value="<?php echo set_value('pincode');?>"  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-nonmemcheckpin data-parsley-type="number"  data-parsley-trigger-after-failure="focusout">
                  (Max 6 digits) <span class="error">
                  <?php //echo form_error('pincode');?>
                  </span> </div>
              </div>
              
              <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of Birth *</label>
                	<div class="col-sm-2">
                      <input type="text" class="form-control pull-right" id="datepicker"  name="dob" placeholder="Date of Birth" required value="<?php echo set_value('dob');?>" >
                      <span class="error"><?php //echo form_error('dob');?></span>
                    </div>
                </div>-->
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of Birth <span style="color:#f00">*</span></label>
                <div class="col-sm-4 example">
                  <input type="hidden" id="dob1" name="dob" required>
                  <input type="hidden" id="doj1" name="doj" value="">
                  <?php 
							$min_year = date('Y', strtotime("- 18 year"));
							$max_year = date('Y', strtotime("- 68 year"));
						?>
                  <input type='hidden' name='minyear' id='minyear' value="<?php echo $min_year; ?>">
                  <input type='hidden' name='maxyear' id='maxyear' value="<?php echo $max_year; ?>">
                  <span id="dob_error" class="error"></span> </div>
                
                <!--<input type="text" class="form-control pull-right" id="datepicker"  name="dob" placeholder="Date of Birth" required value="<?php echo set_value('dob');?>" >--> 
                <span class="error">
                <?php //echo form_error('dob');?>
                </span> </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Gender <span style="color:#f00">*</span></label>
                <div class="col-sm-3">
                  <input type="radio" class="minimal cls_gender" id="female"  checked="checked" name="gender"  required value="female" <?php echo set_radio('gender', 'female'); ?>>
                  Female
                  <input type="radio" class="minimal cls_gender" id="male"   name="gender"  required value="male" <?php echo set_radio('gender', 'male'); ?>>
                  Male <span class="error">
                  <?php //echo form_error('gender');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Qualification <span style="color:#f00">*</span></label>
                <div class="col-sm-6">
                  <input type="radio" class="minimal" id="U"   name="optedu"   value="U" onclick="changedu(this.value)" <?php echo set_radio('optedu', 'U'); ?>>
                  Under Graduate
                  <input type="radio" class="minimal" id="G"   name="optedu"  value="G" onclick="changedu(this.value)" <?php echo set_radio('optedu', 'G'); ?>>
                  Graduate
                  <input type="radio" class="minimal" id="P"   name="optedu"  value="P"   onclick="changedu(this.value)" <?php echo set_radio('optedu', 'P'); ?>>
                  Post Graduate <span class="error">
                  <?php //echo form_error('optedu');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Please specify <span style="color:#f00">*</span></label>
                <div class="col-sm-5" <?php if(set_value('eduqual1') || set_value('eduqual2') || set_value('eduqual3')){echo 'style="display:none"';}else
			  {echo 'style="display:block"';}?>  id="edu">
                  <select id="eduqual" name="eduqual" class="form-control" <?php if(!set_value('eduqual1') && !set_value('eduqual2') && !set_value('eduqual3')){echo 'required';}?>>
                    <option value="" selected="selected">--Select--</option>
                  </select>
                </div>
                <div class="col-sm-5"  <?php if(set_value('optedu')=='U'){echo 'style="display:block;"';}else if(!set_value('optedu')){echo 'style="display:none;"';}else{echo 'style="display:none;"';}?> id="UG">
                  <select class="form-control" id="eduqual1" name="eduqual1" <?php if(set_value('optedu')=='U'){echo 'required';}?> >
                    <option value="">--Select--</option>
                    <?php if(count($undergraduate)){
                                foreach($undergraduate as $row1){ 	?>
                    <option value="<?php echo $row1['qid'];?>" <?php echo  set_select('eduqual1', $row1['qid']); ?>><?php echo $row1['name'];?></option>
                    <?php } } ?>
                  </select>
                  <span class="error">
                  <?php //echo form_error('eduqual1');?>
                  </span> </div>
                <div class="col-sm-5"  <?php if(set_value('optedu')=='G'){echo 'style="display:block"';}else{echo 'style="display:none"';}?> id="GR">
                  <select class="form-control" id="eduqual2" name="eduqual2" <?php if(set_value('optedu')=='G'){echo 'required';}?> >
                    <option value="">--Select--</option>
                    <?php if(count($graduate)){
                                foreach($graduate as $row2){ 	?>
                    <option value="<?php echo $row2['qid'];?>" <?php echo  set_select('eduqual2', $row2['qid']); ?>><?php echo $row2['name'];?></option>
                    <?php } } ?>
                  </select>
                  <span class="error">
                  <?php //echo form_error('eduqual2');?>
                  </span> </div>
                <div class="col-sm-5"  <?php if(set_value('optedu')=='P'){echo 'style="display:block"';}else{echo 'style="display:none"';}?>id="PG">
                  <select class="form-control" id="eduqual3" name="eduqual3" <?php if(set_value('optedu')=='P'){echo 'required';}?>>
                    <option value="">--Select--</option>
                    <?php if(count($postgraduate)){
                                foreach($postgraduate as $row3){ 	?>
                    <option value="<?php echo $row3['qid'];?>" <?php echo  set_select('eduqual3', $row3['qid']); ?>><?php echo $row3['name'];?></option>
                    <?php } } ?>
                  </select>
                  <span class="error">
                  <?php //echo form_error('eduqual3');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email <span style="color:#f00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="email" name="email" placeholder="Email"  data-parsley-type="email" value="<?php echo set_value('email');?>"  required  data-parsley-nonmememailcheck  data-parsley-trigger-after-failure="null"/>
                  (Correct/Active E-mail address is mandatory for receipt of Admit Letter and other communication/s through e-mail) <span class="error">
                  <?php //echo form_error('email');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Phone </label>
                <div class="col-sm-4">
                  <label for="roleid" class="col-sm-4 control-label" style="padding-left:0; text-align:left; padding-right:10px;">STD Code</label>
                  <input type="text" class="form-control" id="stdcode"  name="stdcode" placeholder="STD Code"  
                      data-parsley-type="number" data-parsley-maxlength="4" value="<?php echo set_value('stdcode');?>" style="width:55%;" data-parsley-trigger-after-failure="focusout">
                  <span class="error">
                  <?php //echo form_error('stdcode');?>
                  </span> </div>
                <div class="col-sm-4">
                  <label for="roleid" class="col-sm-4 control-label" style="padding-left:0; text-align:left; padding-right:10px;">Phone No</label>
                  <input type="text" class="form-control" id="phone"  name="phone" placeholder="Phone No"  data-parsley-minlength="7"
                      data-parsley-type="number" data-parsley-maxlength="12"    value="<?php echo set_value('phone');?>" style="width:65%;" data-parsley-trigger-after-failure="focusout">
                  <span class="error">
                  <?php //echo form_error('phone');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Mobile <span style="color:#f00">*</span></label>
                <div class="col-sm-5">
                  <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10"  value="<?php echo set_value('mobile');?>"  data-parsley-nonmobilecheck required data-parsley-trigger-after-failure="focusout">
                  <span class="error">
                  <?php //echo form_error('mobile');?>
                  </span> </div>
              </div>
            
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Aadhar Card Number </label>
                <div class="col-sm-5">
                  <input type="text" class="form-control " id="aadhar_card"  name="aadhar_card" placeholder="Aadhar Card Number" 
                           data-parsley-minlength="12" data-parsley-maxlength="12" data-parsley-type="number" size="12"
                            value="<?php echo set_value('aadhar_card');?>" data-parsley-trigger-after-failure="focusout">
                  <!--(Max 25 Characters)--> 
                  <span class="error">
                  <?php //echo form_error('idNo');?>
                  </span> </div>
              </div>
            
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Upload your scanned Photograph <span style="color:#f00">**</span></label>
                <div class="col-sm-5">
                  <input  type="file" class="" name="scannedphoto" id="scannedphoto" required>
                  <input type="hidden" id="hiddenphoto" name="hiddenphoto">
                  <div id="error_photo"></div>
                  <br>
                  <div id="error_photo_size"></div>
                  <span class="photo_text" style="display:none;"></span> <span class="error">
                  <?php //echo form_error('scannedphoto');?>
                  </span> </div>
                <img id="image_upload_scanphoto_preview" height="100" width="100"/> </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"> Upload your scanned Signature Specimen<span style="color:#f00">**</span></label>
                <div class="col-sm-5">
                  <input  type="file" class="" name="scannedsignaturephoto" id="scannedsignaturephoto" required>
                  <input type="hidden" id="hiddenscansignature" name="hiddenscansignature">
                  <div id="error_signature"></div>
                  <br>
                  <div id="error_signature_size"></div>
                  <span class="signature_text" style="display:none;"></span> <span class="error">
                  <?php //echo form_error('scannedsignaturephoto');?>
                  </span> </div>
                <img id="image_upload_sign_preview" height="100" width="100"/> </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Select Id Proof <span style="color:#f00">*</span></label>
                <div class="col-sm-9">
                  <?php if(count($idtype_master) > 0)
						{
							$i=1;
							foreach($idtype_master as $idrow)
							{
								if((base64_decode($this->input->get('ExId')) == 991 || base64_decode($this->input->get('ExId')) == 101) &&  $idrow['id'] == 4)
								{
								?>
								
								<?php	
								}else{
								?>
                  <input name="idproof" value="<?php echo $idrow['id'];?>" type="radio" class="minimal" 
						   <?php if(set_value('idproof')){echo set_radio('idproof', $idrow['id'], TRUE);}else{if($i==1){echo 'checked="checked"';}}?>>
                  <?php echo $idrow['name'];?><br>
                  <?php 
							$i++;} }//else
					   }?>
                  <span class="error">
                  <?php //echo form_error('idproof');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">ID No. <span style="color:#f00">*</span></label>
                <div class="col-sm-5">
                  <input type="text" class="form-control " id="idNo"  name="idNo" placeholder="ID No." required value="<?php echo set_value('idNo');?>" data-parsley-pattern="/^[a-zA-Z0-9][a-zA-Z0-9 ]+$/" data-parsley-maxlength="25">
                  <!--(Max 25 Characters)--> 
                  <span class="error">
                  <?php //echo form_error('idNo');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Upload your id proof <span style="color:#f00">**</span></label>
                <div class="col-sm-5">
                  <input  type="file" class="" name="idproofphoto" id="idproofphoto" required>
                  <input type="hidden" id="hiddenidproofphoto" name="hiddenidproofphoto">
                  <div id="error_dob"></div>
                  <br>
                  <div id="error_dob_size"></div>
                  <span class="dob_proof_text" style="display:none;"></span> <span class="error">
                  <?php //echo form_error('idproofphoto');?>
                  </span> </div>
                <img id="image_upload_idproof_preview" height="100" width="100"/> </div>
              <input type="hidden" name="optnletter" value="N">
              <!--<div class="form-group">
                <label for="roleid" class="col-sm-9 control-label">I agree to receive the Annual report from the Institute in a softcopy, at my registered email ID, in place of physical copy</label>
                	<div class="col-sm-2">
                     
                       <input value="Y" name="optnletter" id="optnletter" checked="" type="radio"  <?php echo set_radio('optnletter', 'Y'); ?>>Yes
						<input value="N" name="optnletter" id="optnletter" type="radio"  <?php echo set_radio('optnletter', 'N'); ?>>No
                      <span class="error"><?php //echo form_error('optnletter');?></span>
                    </div>
                </div>-->
              
              <div class="form-group">
                <label for="roleid" class="col-sm-1 control-label"> Note</label>
                <div class="col-sm-9"> 1. Pl ensure all images are clear, visible and readable after uploading, if not do  not submit and upload fresh set of images.</br>
                  2. Images format should be in JPG 8bit and size should be minimum 8KB and maximum 20KB.</br>
                  3. Image Dimension of Photograph should be 100(Width) * 120(Height) Pixel only</br>
                  4. Image Dimension of Signature should be 140(Width) * 60(Height) Pixel only</br>
                  5. Image Dimension of ID Proof should be 400(Width) * 420(Height) Pixel only. Size should be minimum 8KB and maximum 25KB.</br>
                </div>
              </div>
            </div>
          </div>
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Exam Details:</h3>
            </div>
            <input type='hidden' id="hdnExamCode" maxlength="20" size="20" name="hdnExamCode" value="<?php echo base64_decode($this->input->get('ExId'));?>" />
            <input type='hidden' name='exid' id='exid' value="<?php echo $this->input->get('ExId');?>">
            <!--  <input type='hidden' name='mtype' id='mtype' value="<?php //echo $this->input->get('Mtype');?>">-->
            <input type='hidden' name='mtype' id='mtype' value="NM">
            <input type='hidden' name='memtype' id='memtype' value="NM">
            <input id="eprid" name="eprid" type="hidden" value="<?php echo $examinfo[0]['exam_period'];?>">
            <input type="hidden" value="" name="rrsub" id="rrsub" />
            <input id="excd" name="excd" type="hidden" value="<?php echo base64_decode($this->input->get('ExId'));?>">
            <input id="exname" name="exname" type="hidden" value=" <?php echo $examinfo[0]['description'];?>">
            <input id="fee" name="fee" type="hidden" value="">
            <input id="education_type" name="education_type" type="hidden" value="">
            <?php $grp_code='B1_1';?>
            <input id="grp_code" name="grp_code" type="hidden" value="<?php echo trim($grp_code);?>">
            <div class="box-body">
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Name</label>
                <div class="col-sm-5 "> <?php echo $examinfo[0]['description'];?>
                  <div id="error_dob"></div>
                  <br>
                  <div id="error_dob_size"></div>
                  <span class="dob_proof_text" style="display:none;"></span> <span class="error">
                  <?php //echo form_error('idproofphoto');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>
                <div class="col-sm-5 "  id="html_fee_id">
                  <div style="color:#F00">select center first</div>
                  <?php //echo $examinfo[0]['fee_amount'];?>
                  <div id="error_dob"></div>
                  <br>
                  <div id="error_dob_size"></div>
                  <span class="dob_proof_text" style="display:none;"></span> <span class="error">
                  <?php //echo form_error('idproofphoto');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Period</label>
                <div class="col-sm-5 ">
                  <?php 
						/*echo $examinfo[0]['exam_month'];
						//$month = date('Y')."-".substr($examinfo[0]['exam_month'],4)."-".date('d');*/
						//$month = date('Y')."-".substr($examinfo[0]['exam_month'],4);
						//echo date('F',strtotime($month))."-".substr($examinfo[0]['exam_month'],0,-2);
						$today = date('Y-m-d');
						echo date('Y',strtotime($today));
					?>
                  <div id="error_dob"></div>
                  <br>
                  <div id="error_dob_size"></div>
                  <span class="dob_proof_text" style="display:none;"></span> <span class="error">
                  <?php //echo form_error('idproofphoto');?>
                  </span> </div>
              </div>
              
              <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">GSTIN No.&nbsp;</label>
                	<div class="col-sm-5 ">
                         <input type="text" class="form-control" id="gstin_no" name="gstin_no" placeholder="GSTIN No." value="<?php echo set_value('gstin_no');?>"  data-parsley-minlength="15" data-parsley-maxlength="15" data-parsley-trigger-after-failure="focusout">
                     <div id="error_dob"></div>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>-->
              
              
                 <? if(count($compulsory_subjects) > 0 && base64_decode($this->input->get('ExId'))==101) {?>
                       	 <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Examination Date</label>
                            <div class="col-sm-5 ">
                            <?php echo  date('d-M-Y',strtotime($compulsory_subjects[0]['exam_date'])); ?>
                           </div>
                        </div>
              <?php }?>
                 
                 
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Medium *</label>
                <div class="col-sm-3">
                  <select name="medium" id="medium" class="form-control" required>
                    <option value="">Select</option>
                    <?php if(count($medium) > 0)
					{
						foreach($medium as $mrow)
						{?>
                    <option value="<?php echo $mrow['medium_code']?>" <?php echo  set_select('medium', $mrow['medium_code']); ?>><?php echo $mrow['medium_description']?></option>
                    <?php }
					}?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Centre Name *</label>
                <div class="col-sm-3">
                  <select name="selCenterName" id="selCenterName" class="form-control" required onchange="valCentre(this.value);">
                    <option value="">Select</option>
                    <?php if(count($center) > 0)
					{
						foreach($center as $crow)
						{?>
                    <!--<option value="<?php //echo $crow['center_code']?>" class=<?php //echo $crow['exammode'];?> <?php //echo  set_select('selCenterName', $crow['center_code']); ?>><?php //echo $crow['center_name']?></option>-->
                    
                    <option value="<?php echo $crow['center_code']?>" class=<?php echo $crow['exammode'];?>><?php echo $crow['center_name']?></option>
                    <?php }
					}?>
                  </select>
                </div>
              </div>
              <?php 
			   if(count($compulsory_subjects) > 0 )
			   {
					   $i=1;
					   foreach($compulsory_subjects as $subject)
					   {?>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"><?php echo $subject['subject_description']?><span style="color:#F00">*</span></label>
                <div class="col-sm-2">
                  <label for="roleid" class="col-sm-3 control-label">Venue<span style="color:#F00">*</span></label>
                  <select name="venue[<?php echo $subject['subject_code']?>]" id="venue_<?php echo $i;?>" class="form-control venue_cls" required  onchange="venue(this.value,'date_<?php echo $i;?>','time_<?php echo $i;?>','<?php echo $subject['subject_code']?>','seat_capacity_<?php echo $i;?>');" attr-data='<?php echo $subject['subject_code']?>'>
                    <option value="">Select</option>
                  </select>
                </div>
                <div class="col-sm-2">
                  <label for="roleid" class="col-sm-3 control-label">Date<span style="color:#F00">*</span></label>
                  <select name="date[<?php echo $subject['subject_code']?>]" id="date_<?php echo $i;?>" class="form-control date_cls" required  onchange="date(this.value,'venue_<?php echo $i;?>','time_<?php echo $i;?>');">
                    <option value="">Select</option>
                  </select>
                </div>
                <div class="col-sm-2">
                  <label for="roleid" class="col-sm-3 control-label">Time<span style="color:#F00">*</span></label>
                  <select name="time[<?php echo $subject['subject_code']?>]" id="time_<?php echo $i;?>" class="form-control time_cls" required onchange="time(this.value,'venue_<?php echo $i;?>','date_<?php echo $i;?>','seat_capacity_<?php echo $i;?>');">
                    <option value="">Select</option>
                  </select>
                </div>
                <label for="roleid" class="col-sm-0 control-label">Seat(s) Available<span style="color:#F00">*</span></label>
                <div id="seat_capacity_<?php echo $i;?>"> - </div>
              </div>
              <?php 
				$i++;}
				 }?>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Centre Code *</label>
                <div class="col-sm-2">
                  <input type="text" name="txtCenterCode" id="txtCenterCode"  class="form-control pull-right" readonly="readonly">
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Mode *</label>
                <!--<div class="col-sm-2">
                      <input type="radio" class="minimal " id="optsex1"   name="optmode" value="ON" <?php //echo set_radio('optmode', 'ON'); ?>>
                     Online
                   <input type="radio" class="minimal" id="optsex2"   name="optmode"  checked="checked"  value="OF" <?php //echo set_radio('optmode', 'OF'); ?>>
                     Offline
                    </div>-->
                
                <div name="optmode1" id="optmode1" style="display: none;">Exam will be in ONLINE mode only, Read Important Instructions on the website.</div>
                <div name="optmode2" id="optmode2" style="display: none;">Exam will be in OFFLINE mode only, Read Important Instructions on the website.</div>
                <input id="optmode" name="optmode" value="" type="hidden">
              </div>
            </div>
          </div>
      	   
 		   <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Scribe required?</label>
            <div class="col-sm-3">
              <input type="checkbox" name="scribe_flag" id="scribe_flag" value="Y">
            </div>
             <div class="col-sm-12">
            <img src="<?php echo base_url()?>assets/images/bullet2.gif"> The candidate should send a separate application along with the DECLARATION as given in the  Scribe Application Form (available in our website) completed to the MSS Department about such requirement for obtaining permission much before the commencement of the examination 
                         (This application is required to make suitable arrangements at the examination venue).Candidate is required to follow this procedure for each attempt 
                         of examination in case the help of scribe is required. For more details please refer to the guidelines for use of scribe, given in the website.<br />
                  
                         <span class="error"><?php //echo form_error('gender');?></span>
          </div>
               </div>

          <div class="box box-info">
            <div class="box-header with-border header_blue">
              <h3 class="box-title">Declaration:</h3>
            </div>
            <div class="box-body">
              <div class="form-group">
                <label for="roleid" class="col-sm-2 control-label"> </label>
                <div class="col-sm-12">
                  <ol>
                    <li> I declare that I have submitted my Aadhar Card Number and Proof of my  Identity : Driving License/ID Card issued by Employer / Pan Card / Passport  as specified above.. </li>
                    <!--<li>I hereby declare that all the information given in this application is true, complete and correct. I understand that in the event of any information being found false or incorrect subsequent to allotment of membership, my membership is liable to be cancelled / terminated.
		</li>-->
                    
                    <li>I hereby declare that all the information given in this application is true, complete and correct. I understand that in the event of any information being found false or incorrect subsequent to allotment of registration No, my registration No is liable to be cancelled / terminated. </li>
                    <li> I further declare that I have not at any time been a member of the Institute/applied earlier for membership of the Institute. </li>
                    <!--<li> I hereby agree, if admitted, to be bound by the Memorandum and Articles of Association of the Institute. I am aware that, if admitted as an Ordinary Member, as per the provisions of the Articles of Association of the Institute. I shall be liable, in the event of the Institute begin wound up, to contribute towards its liabilities a sum not exceeding Rs. 1725/-
        </li>-->
                    <li> I confirm having read and understood the rules and regulations of the Institute and I hereby agree to abide by the same. In case I am desirous of Instituting any legal proceedings against the Institute I hereby agree that such legal proceedings shall be instituted only in courts at Mumbai, New Delhi, Kolkata and Chennai in whose Jurisdiction Zonal office/s of the Institute is situated and my application thereto pertains and not in any other court.</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">
                <input name="declaration1" value="1" type="checkbox" required="required" 
			  <?php if(set_value('declaration1'))
              {
                  echo set_radio('declaration1', '1');
                 }?>>
                &nbsp; I Accept</h3>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-3 control-label">Security Code *</label>
              <div class="col-sm-2">
                <input type="text" name="code" id="code" required class="form-control " >
                <span class="error" id="non_mem_captchaid" style="color:#B94A48;"></span> </div>
              <div class="col-sm-3">
                <div id="captcha_img"><?php echo $image;?></div>
                <span class="error">
                <?php //echo form_error('code');?>
                </span> </div>
              <div class="col-sm-2"> <a href="javascript:void(0);" id="new_captcha" class="forget" >Change Image</a> <span class="error">
                <?php //echo form_error('code');?>
                </span> </div>
            </div>
            <div class="box-footer">
              <div class="col-sm-6 col-sm-offset-2"> <a href="javascript:void(0);" class="btn btn-info"onclick="javascript:return non_mem_checkform();" id="preview">Preview and Proceed for Payment</a> 
                <!-- <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Save">-->
                <button type="reset" class="btn btn-default pull-right"  name="btnReset" id="btnReset">Reset</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="confirm"  role="dialog" >
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
              <p style="color:#F00"> <strong>VERY IMPORTANT</strong><br>
                I confirm that the  Photo, Signature & Id proof images  uploaded belongs to me and they are clear and readable. <br />
                <br />
                <!--We find that Aadhaar Number is not mentioned in your membership account. You are requested to enter Aadhaar number in your membership account immediately.
                
                Aadhaar number can be updated to your existing membership account through edit profile option by entering your membership number and profile password. <br />
                In case, if you do not have Aadhaar number, request you to obtain it on or before December 31, 2017 and update the Aadhaar number in your membership profile.--> </p>
            </div>
            <div class="modal-footer"> 
              <!-- <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="non_mem_preview();">Confirm</button>-->
              <input type="submit" name="btnSubmit" class="btn btn-primary" id="btnSubmit" value="Confirm">
            </div>
          </div>
          <!-- /.modal-content --> 
        </div>
        <!-- /.modal-dialog --> 
      </div>
    </section>
  </form>
</div>
<!-- Modal -->
<div class="modal fade" id="myModalscrib" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <center><strong> <h4 class="modal-title" id="myModalLabel" style="color:#F00"> Important Notice</h4></strong></center>
      </div>
      <div class="modal-body" style="color:#F00">
      The facility of scribe ,on request, is provided to the person with Disability only.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
       
      </div>
    </div>
  </div>
</div>
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script> 
<script src="<?php echo base_url();?>js/cscvalidation.js"></script> 
<script type="text/javascript">
  <!--var flag=$('#usersAddForm').parsley('validate');-->

</script> 
<script>
	$(document).ready(function() 
	{
		var cCode=$('#selCenterName').val();
		if(cCode!='')
		{
			document.getElementById('txtCenterCode').value = cCode ;
			var examType = document.getElementById('extype').value;
			var examCode = document.getElementById('examcode').value;
			var temp = document.getElementById("selCenterName").selectedIndex;
			selected_month = document.getElementById("selCenterName").options[temp].className;
			if(selected_month == 'ON')
			{
				if(document.getElementById("optmode1")){
					document.getElementById("optmode1").style.display = "block";
					document.getElementById('optmode').value= 'ON';
				}
					
				if(document.getElementById("optmode2"))
				{
					document.getElementById("optmode2").style.display = "none";	
				}
				
			}	
			else if(selected_month == 'OF')
			{
				if(document.getElementById("optmode2")){
					document.getElementById("optmode2").style.display = "block";
					document.getElementById('optmode').value= 'OF';
				}
				if(document.getElementById("optmode1")){
					document.getElementById("optmode1").style.display = "none";
				}	
			}
			else{
					if(document.getElementById("optmode1")){
						document.getElementById("optmode1").style.display = "none";
					}
					if(document.getElementById("optmode2")){

						document.getElementById("optmode2").style.display = "none";
					}
			}
		
		}
		//var dtable = $('.dataTables-example').DataTable();
	
		//$(".DTTT_button_print")).hide();
		/*$('#datepicker,#doj').datepicker({
			autoclose: true
		});*/
		
		$(function() {
			$("#dob1").dateDropdowns({
				submitFieldName: 'dob1',
				minAge: 0,
				maxAge:67
			});
			// Set all hidden fields to type text for the demo
			//$('input[type="hidden"]').attr('type', 'text').attr('readonly', 'readonly');
		});
		
		$("#dob1").change(function(){
			var sel_dob = $("#dob1").val();
			if(sel_dob!='')
			{
				var dob_arr = sel_dob.split('-');
				if(dob_arr.length == 3)
				{
					chkage(dob_arr[2],dob_arr[1],dob_arr[0]);	
				}
				else
				{	alert('Select valid date');	}
			}
		});
	
		$("body").on("contextmenu",function(e){
			return false;
		});
		
		$('#male').prop("checked", true);
		
		/*$('#eduqual1').show();
		$('#UG').show();
		$('#eduqual').hide();
		$('#edu').hide();*/
		
		var selEducation = $("#education_type").val();
		if(selEducation!='')
		{
			changedu(selEducation);
		}
		
		
		$(document).keydown(function(event) {
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
    });
	
	});
		
	function editUser(id,roleid,Name,Username,Email){
		$('#id').val(id);
		$('#roleid').val(roleid);
		$('#name').val(Name);
		$('#username').val(Username);
		$('#emailid').val(Email);
		$('#btnSubmit').val('Update');
		$('#roleid').focus();
		$('#password').removeAttr('required');
		$('#confirmPassword').removeAttr('required');
		
	}
	
function changedu(dval)
{
	
	$("#education_type").val(dval);
	var UGid = document.getElementById('UG');
	var GRid = document.getElementById('GR');
	var PGid = document.getElementById('PG');
	var EDUid = document.getElementById('edu');

	if(dval == 'U')
	{
		$('#eduqual1').attr('required','required');
		$('#eduqual2').removeAttr('required');
		$('#eduqual3').removeAttr('required');
		$('#eduqual').removeAttr('required');
		
		if(UGid != null) {
		//	alert('UG');
			document.getElementById('UG').style.display = "block";
		}
		if(GRid != null) {
			document.getElementById('GR').style.display = "none";
		}
		if(PGid != null) {
			document.getElementById('PG').style.display = "none";	
		}
		if(EDUid != null) {
			document.getElementById('edu').style.display = "none";	
		}
	}
	else if(dval == 'G')
	{
		$('#eduqual1').removeAttr('required');;
		$('#eduqual2').attr('required','required');
		$('#eduqual3').removeAttr('required');
		$('#eduqual').removeAttr('required');
			
		if(UGid != null) {
			document.getElementById('UG').style.display = "none";
		}
		if(GRid != null) {
			document.getElementById('GR').style.display = "block";
		}
		if(PGid != null) {
			document.getElementById('PG').style.display = "none";	
		}
		if(EDUid != null) {
			document.getElementById('edu').style.display = "none";	
		}
	
	}
	else if(dval == 'P')
	{
		$('#eduqual1').removeAttr('required');;
		$('#eduqual2').removeAttr('required');
		$('#eduqual3').attr('required','required');
		$('#eduqual').removeAttr('required');
			
		if(UGid != null) {
			document.getElementById('UG').style.display = "none";
		}
		if(GRid != null) {
			document.getElementById('GR').style.display = "none";
		}
		if(PGid != null) {
			document.getElementById('PG').style.display = "block";	
		}
		if(EDUid != null) {
			document.getElementById('edu').style.display = "none";	
		}
	}
}
	
</script> 
<script>
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
	
    $('#new_captcha').click(function(event){
        event.preventDefault();
    $.ajax({
 		type: 'POST',
 		url: site_url+'Register/generatecaptchaajax/',
 		success: function(res)
 		{	
 			if(res!='')
 			{$('#captcha_img').html(res);
 			}
 		}
    });
});
	 //$("#datepicker,#doj").keypress(function(event) {event.preventDefault();});

	if($('#hiddenphoto').val()!='')
	{
		   $('#image_upload_scanphoto_preview').attr('src', $('#hiddenphoto').val());
	}
	if($('#hiddenscansignature').val()!='')
	{
		   $('#image_upload_sign_preview').attr('src', $('#hiddenscansignature').val());
	}
	if($('#hiddenidproofphoto').val()!='')
	{
		   $('#image_upload_idproof_preview').attr('src', $('#hiddenidproofphoto').val());
	}
	
});
</script> 
<script>
$('#scribe_flag').on('change', function(e){
   if(e.target.checked){
     $('#myModalscrib').modal();
   }
});
//priyanka d - 06-april-23 >> added this for weekly off csc dates task
$(document).ready(function() 
	{
    /*
$.ajax({
        url: "<?php echo base_url(); ?>/CSCVenue/getcentrenonavailability",
        type: "get",
       // data: values ,
        aync:true,
        success: function (response) {

          
        }
    });*/
  });
</script>