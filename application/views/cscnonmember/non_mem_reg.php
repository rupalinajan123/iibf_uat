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

/*Cropper Image Editor*/
    #optionsModal > .modal-dialog, #cropModal > .modal-dialog { max-width: 600px; }
    #optionsModal > .modal-dialog h4.modal-title, #GuidelinesModal > .modal-dialog h4.modal-title, #cropModal > .modal-dialog h4.modal-title { text-align: center; }

    #GuidelinesModal > .modal-dialog { max-width: 800px; }
  /*Cropper Image Editor*/
.txtuppercase{
  text-transform: uppercase;
}

/*Corporate BC Changes */
#parsley-id-multiple-corporate_bc_option{
  margin-top: 30px;
  margin-left: 285px;
}  
/*Corporate BC Changes */  
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
                  <input type="text" onkeyup="convertToUppercaseText(this); validateTotalNameLength();" class="form-control txtuppercase" id="firstname" name="firstname" placeholder="First Name" required value="<?php echo set_value('firstname');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z- ]+$/" data-parsley-maxlength="30" >
                  <span class="error">
                  <?php //echo form_error('firstname');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                <div class="col-sm-5">
                  <input type="text" onkeyup="convertToUppercaseText(this); validateTotalNameLength();" class="form-control txtuppercase" id="middlename" name="middlename" placeholder="Middle Name"  value="<?php echo set_value('middlename');?>" data-parsley-pattern="/^[a-zA-Z- ]+$/" data-parsley-maxlength="30"    >
                  <span class="error">
                  <?php //echo form_error('middlename');?>
                  </span> </div>
                (Max 30 Characters) </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                <div class="col-sm-5">
                  <input type="text" onkeyup="convertToUppercaseText(this); validateTotalNameLength();" class="form-control txtuppercase" id="lastname" name="lastname" placeholder="Last Name"  value="<?php echo set_value('lastname');?>" data-parsley-pattern="/^[a-zA-Z- ]+$/" data-parsley-maxlength="30" >
                  <span class="error" id="lastname_error">
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
                  <!-- <input type="hidden" id="doj1" name="doj" value=""> -->
                  <?php 
							$min_year = date('Y', strtotime("- 18 year"));
							$max_year = date('Y', strtotime("- 70 year"));
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

              <!-- START: FOR IMAGE EDITOR -->
              <?php $data_lightbox_title_common = "Non Member Registration"; ?>
              <input type="hidden" name="form_value" id="form_value" value="form_value">
              <input type="hidden" id="data_lightbox_title_hidden" value="<?php echo $data_lightbox_title_common; ?>">
              <?php $inc_fileChooser_accepted_files = '.jpg, .jpeg'; ?>
              <input type="hidden" name="inc_fileChooser_accepted_files" id="inc_fileChooser_accepted_files" value="<?php echo $inc_fileChooser_accepted_files; ?>">
              <!-- END: FOR IMAGE EDITOR -->

              <?php 
                $examcode = base64_decode($this->input->get('ExId')); 
                if (isset($examcode) && ($examcode == 1052 || $examcode == 1054)){  ?> 
                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Name of Bank where working as BC <span style="color:#F00">*</span></label>
                  <div class="col-sm-5">
                    <select onchange="check_bank_bc_id_no();" id="name_of_bank_bc" name="name_of_bank_bc" class="form-control" required>
                      <option value="">-- Select --</option>
                      <?php if (count($old_bcbf_institute_data)) {
                        foreach ($old_bcbf_institute_data as $res) {   ?>
                          <option value="<?php echo $res['institute_id']; ?>" <?php echo set_select('name_of_bank_bc', $res['institute_id']); ?>> <?php echo $res['institute_name']; ?>  
                          </option>
                    <?php }
                      } 
                    ?>
                    </select>
                    <span class="error"></span>
                  </div>
                  <!-- (Max 20 Characters) -->
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Are you associated with a Corporate BC? <span style="color:#f00">*</span></label>
                  <div class="col-sm-3">
                    <input type="radio" class="minimal" id="are_you_corporate_bc_yes" name="are_you_corporate_bc"  required value="Yes" onclick="check_are_you_corporate_bc(this.value);" <?php echo set_radio('are_you_corporate_bc', 'Yes'); ?>>
                    Yes
                    <input type="radio" class="minimal" checked="checked" id="are_you_corporate_bc_no" name="are_you_corporate_bc"  required value="No" onclick="check_are_you_corporate_bc(this.value);"  <?php echo set_radio('are_you_corporate_bc', 'No'); ?>>
                    No <span class="error">
                    <?php //echo form_error('are_you_corporate_bc');?>
                    </span> 
                  </div>
                </div>

                <div id="corporate_bc_option_div" style="display:none;" class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Select Corporate BC <span style="color:#f00">*</span></label>
                  <div class="col-sm-3">
                    <input type="radio" class="minimal" id="corporate_bc_option_CSC" name="corporate_bc_option" value="CSC" onclick="check_corporate_bc_option(this.value);" <?php echo set_radio('corporate_bc_option', 'CSC'); ?>>
                    CSC
                    <input type="radio" class="minimal" id="corporate_bc_option_Other" name="corporate_bc_option" value="Other" onclick="check_corporate_bc_option(this.value);" <?php echo set_radio('corporate_bc_option', 'Other'); ?>>
                    Other 
                  </div>
                  <span class="error">
                    <?php //echo form_error('corporate_bc_option');?>
                    </span> 
                </div>

                <div id="corporate_bc_associated_div" style="display:none;" class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Corporate BC associated with <span style="color:#f00">*</span></label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="corporate_bc_associated" name="corporate_bc_associated" placeholder="Corporate BC associated with" value="<?php echo set_value('corporate_bc_associated'); ?>" data-parsley-maxlength="90" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                    <span class="error" id="corporate_bc_associated_error"></span>
                  </div>
                </div>

                <div id="corporate_bc_validation_message_div" style="display:none;" class="form-group">
                  <label for="roleid" class="col-sm-3 control-label"><span style="color:#f00"></span></label>
                  <div class="col-sm-5">
                     <span style="color:#f00">You are not eligible to appear at CSC center Exam. Kindly register through the IIBF website. (<a target="_blank" href="<?php echo base_url('iibfbcbf/apply_exam_individual?ctype=Tk0=');?>">Click Here</a>)</span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Date of commencement of operations/joining as BC <span style="color:#F00">*</span></label>
                  <div class="col-sm-4 doj">
                    <div class="col-sm-2 example">
                      <input type="hidden" id="doj1" name="doj" value="<?php echo set_value('doj1'); ?>">
                      <input type="hidden" id="exam_code_id" value="<?php echo $examcode; ?>"> 
                    </div>
                    <span id="doj_error" class="error"></span>
                  </div>
                </div> 

                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Bank BC ID No <span style="color:#F00">*</span></label>
                  <div class="col-sm-5">
                    <input required type="text" class="form-control" id="ippb_emp_id" name="ippb_emp_id" placeholder="Bank BC ID No" onchange="check_bank_bc_id_no();" value="<?php echo set_value('ippb_emp_id'); ?>" data-parsley-maxlength="20" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                    <span class="error" id="ippb_emp_id_error"></span>
                  </div>
                </div> 
              
                <?php /* <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Upload Bank BC ID Card <span style="color:#f00">**</span></label>
                  <div class="col-sm-5">
                    <input type="file" class="" name="empidproofphoto" id="empidproofphoto" required onchange="validateFile(event, 'error_empidproofphoto_size', 'image_upload_empidproof_preview', '300kb')">
                    <input type="hidden" id="hiddenempidproofphoto" name="hiddenempidproofphoto">
                    <span class="note">Please Upload only .jpg, .jpeg Files upto 300KB</span>
                    <div id="error_dob" class="error"></div>
                    <br>
                    <div id="error_empidproofphoto_size" class="error"></div>
                    <span class="dob_proof_text" style="display:none;"></span> <span class="error">
                      <?php //echo form_error('idproofphoto');
                      ?>
                    </span>
                  </div>
                  <img class="mem_reg_img" id="image_upload_empidproof_preview" height="100" width="100" src="/assets/images/default1.png" />
                </div> */ ?>

                <div class="form-group"><?php // Upload Your Upload Bank BC ID Card ?>
                <?php 
                  $image_nm_emp_bank = 'Upload Bank BC ID Card'; 
                  $field_nm_emp_bank = 'bank_bc_id_card';
                ?>
                <label for="empidproofphoto" class="col-sm-3 control-label"><?php echo 'Upload Bank BC ID Card'; ?> <span style="color:#F00">*</span></label>
                <div class="col-sm-5">
                  <div class="img_preview_input_outer pull-left">
                    <input type="file" name="empidproofphoto" id="empidproofphoto" class="form-control hide_input_file_cropper" required data-parsley-errors-container="#empidproofphotoError" />

                    <div class="image-input image-input-outline image-input-circle image-input-empty">
                      <div class="profile-progress"></div>
                      <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('<?php echo $field_nm_emp_bank; ?>', 'member_registration', 'Edit Bank BC ID Card');" onblur="validate_form_images('empidproofphoto')"><?php echo $image_nm_emp_bank; ?></button>
                    </div>
                    <note class="form_note" id="empidproofphoto_err">Note: Please select only .jpg, .jpeg file upto 20MB.</note>
                    <span id="empidproofphotoError"></span>

                    <input type="hidden" name="<?php echo $field_nm_emp_bank; ?>_cropper" id="<?php echo $field_nm_emp_bank; ?>_cropper" value="<?php echo set_value($field_nm_emp_bank.'_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>

                    <?php if (form_error('empidproofphoto') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('empidproofphoto'); ?></label> <?php } ?>
                  </div>

                  <div id="<?php echo $field_nm_emp_bank; ?>_preview" class="upload_img_preview pull-right">
                    <?php
                    $preview_empidproofphoto = '';
                    if (set_value($field_nm_emp_bank.'_cropper') != "")
                    {
                      $preview_empidproofphoto = set_value($field_nm_emp_bank.'_cropper');
                    }

                    if ($preview_empidproofphoto != "")
                    { ?>
                      <a href="<?php echo $preview_empidproofphoto . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo $image_nm_emp_bank; echo $data_lightbox_title_common;?>">
                        <img src="<?php echo $preview_empidproofphoto . "?" . time(); ?>">
                      </a>

                      <button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="<?php echo $field_nm_emp_bank; ?>" data-db_tbl_name="member_registration" data-title="Edit <?php echo $image_nm_emp_bank; ?>" title="Edit <?php echo $image_nm_emp_bank; ?>" alt="Edit <?php echo $image_nm_emp_bank; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                    <?php }
                    else
                    {
                      echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                    } ?>
                  </div>
                  <div class="clearfix"></div>
                </div>
              </div> 

              <?php } ?> 

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

              <?php 
              $examcode = base64_decode($this->input->get('ExId'));
              $file_upload_size_msg = 'Please Upload only .jpg, .jpeg Files upto 50KB';
              if(isset($examcode) && ($examcode == 1052 || $examcode == 1054)){
                $file_upload_size_msg = 'Please Upload only .jpg or .jpeg files between 20 KB and 50 KB.';
              }
              ?>
              
              <?php /* <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Upload your scanned Photograph <span style="color:#f00">**</span></label>
                <div class="col-sm-5">
                  <input  type="file" class="" name="scannedphoto" id="scannedphoto" required>
                  <input type="hidden" id="hiddenphoto" name="hiddenphoto">
                  <span class="note"><?php echo $file_upload_size_msg; ?><!-- Please Upload only .jpg, .jpeg Files upto 50KB --></span></br>
                  <div id="error_photo"></div>
                  <br>
                  <div id="error_photo_size"></div>
                  <span class="photo_text" style="display:none;"></span> <span class="error">
                  <?php //echo form_error('scannedphoto');?>
                  </span> </div>
                <img id="image_upload_scanphoto_preview" height="100" width="100"/> </div> */ ?>

                <!-- START: FOR IMAGE EDITOR -->
              <?php $data_lightbox_title_common = "CSC Non Member Registration"; ?>
              <input type="hidden" name="form_value" id="form_value" value="form_value">
              <input type="hidden" id="data_lightbox_title_hidden" value="<?php echo $data_lightbox_title_common; ?>">
              <?php $inc_fileChooser_accepted_files = '.jpg, .jpeg'; ?>
              <input type="hidden" name="inc_fileChooser_accepted_files" id="inc_fileChooser_accepted_files" value="<?php echo $inc_fileChooser_accepted_files; ?>">
              <!-- END: FOR IMAGE EDITOR -->

              <div class="form-group"><?php // Upload your scanned Photograph  ?>
                <label for="scannedphoto" class="col-sm-3 control-label">Upload your scanned Photograph <span style="color:#F00">*</span></label>
                <div class="col-sm-5">
                  <div class="img_preview_input_outer pull-left">
                    <input type="file" name="scannedphoto" id="scannedphoto" class="form-control hide_input_file_cropper" required data-parsley-errors-container="#scannedphotoError" />

                    <div class="image-input image-input-outline image-input-circle image-input-empty">
                      <div class="profile-progress"></div>
                      <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('scannedphoto', 'member_registration', 'Edit Photo');" onblur="validate_form_images('scannedphoto')">Upload Scanned Photograph</button>
                    </div>
                    <note class="form_note" id="scannedphoto_err">Note: Please select only .jpg, .jpeg file upto 20MB.</note>
                    <span id="scannedphotoError"></span>

                    <input type="hidden" name="scannedphoto_cropper" id="scannedphoto_cropper" value="<?php echo set_value('scannedphoto_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>

                    <?php if (form_error('scannedphoto') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('scannedphoto'); ?></label> <?php } ?>
                  </div>

                  <div id="scannedphoto_preview" class="upload_img_preview pull-right">
                    <?php
                    $preview_scannedphoto = '';
                    if (set_value('scannedphoto_cropper') != "")
                    {
                      $preview_scannedphoto = set_value('scannedphoto_cropper');
                    }

                    if ($preview_scannedphoto != "")
                    { ?>
                      <a href="<?php echo $preview_scannedphoto . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Scanned Photograph - '; echo $data_lightbox_title_common;?>">
                        <img src="<?php echo $preview_scannedphoto . "?" . time(); ?>">
                      </a>

                      <button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="scannedphoto" data-db_tbl_name="member_registration" data-title="Edit Photo" title="Edit Photo" alt="Edit Photo"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                    <?php }
                    else
                    {
                      echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                    } ?>
                  </div>
                  <div class="clearfix"></div>
                </div>
              </div>


              <?php /* <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"> Upload your scanned Signature Specimen<span style="color:#f00">**</span></label>
                <div class="col-sm-5">
                  <input  type="file" class="" name="scannedsignaturephoto" id="scannedsignaturephoto" required>
                  <input type="hidden" id="hiddenscansignature" name="hiddenscansignature">
                  <span class="note"><?php echo $file_upload_size_msg; ?><!-- Please Upload only .jpg, .jpeg Files upto 50KB --></span></br>
                  <div id="error_signature"></div>
                  <br>
                  <div id="error_signature_size"></div>
                  <span class="signature_text" style="display:none;"></span> <span class="error">
                  <?php //echo form_error('scannedsignaturephoto');?>
                  </span> </div>
                <img id="image_upload_sign_preview" height="100" width="100"/> </div> */ ?>

                <div class="form-group"><?php // Upload Your Scanned Signature Specimen  ?>
                <label for="scannedsignaturephoto" class="col-sm-3 control-label">Upload Your Scanned Signature Specimen <span style="color:#F00">*</span></label>
                <div class="col-sm-5">
                  <div class="img_preview_input_outer pull-left">
                    <input type="file" name="scannedsignaturephoto" id="scannedsignaturephoto" class="form-control hide_input_file_cropper" required data-parsley-errors-container="#scannedsignaturephotoError" />

                    <div class="image-input image-input-outline image-input-circle image-input-empty">
                      <div class="profile-progress"></div>
                      <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('scannedsignaturephoto', 'member_registration', 'Edit Signature');" onblur="validate_form_images('scannedsignaturephoto')">Upload Scanned Signature</button>
                    </div>
                    <note class="form_note" id="scannedsignaturephoto_err">Note: Please select only .jpg, .jpeg file upto 20MB.</note>
                    <span id="scannedsignaturephotoError"></span>

                    <input type="hidden" name="scannedsignaturephoto_cropper" id="scannedsignaturephoto_cropper" value="<?php echo set_value('scannedsignaturephoto_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>

                    <?php if (form_error('scannedsignaturephoto') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('scannedsignaturephoto'); ?></label> <?php } ?>
                  </div>

                  <div id="scannedsignaturephoto_preview" class="upload_img_preview pull-right">
                    <?php
                    $preview_scannedsignaturephoto = '';
                    if (set_value('scannedsignaturephoto_cropper') != "")
                    {
                      $preview_scannedsignaturephoto = set_value('scannedsignaturephoto_cropper');
                    }

                    if ($preview_scannedsignaturephoto != "")
                    { ?>
                      <a href="<?php echo $preview_scannedsignaturephoto . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Scanned Signature - '; echo $data_lightbox_title_common;?>">
                        <img src="<?php echo $preview_scannedsignaturephoto . "?" . time(); ?>">
                      </a>

                      <button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="scannedsignaturephoto" data-db_tbl_name="member_registration" data-title="Edit Signature" title="Edit Signature" alt="Edit Signature"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                    <?php }
                    else
                    {
                      echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                    } ?>
                  </div>
                  <div class="clearfix"></div>
                </div>
              </div>

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
              <?php /* <div class="form-group">
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
                <img id="image_upload_idproof_preview" height="100" width="100"/> </div> */ ?>

                <div class="form-group"><?php // Upload Your Id Proof  ?>
                <label for="idproofphoto" class="col-sm-3 control-label">Upload Your Id Proof <span style="color:#F00">*</span></label>
                <div class="col-sm-5">
                  <div class="img_preview_input_outer pull-left">
                    <input type="file" name="idproofphoto" id="idproofphoto" class="form-control hide_input_file_cropper" required data-parsley-errors-container="#idproofphotoError" />

                    <div class="image-input image-input-outline image-input-circle image-input-empty">
                      <div class="profile-progress"></div>
                      <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('idproofphoto', 'member_registration', 'Edit Id Proof');" onblur="validate_form_images('idproofphoto')">Upload Id Proof</button>
                    </div>
                    <note class="form_note" id="idproofphoto_err">Note: Please select only .jpg, .jpeg file upto 20MB.</note>
                    <span id="idproofphotoError"></span>

                    <input type="hidden" name="idproofphoto_cropper" id="idproofphoto_cropper" value="<?php echo set_value('idproofphoto_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>

                    <?php if (form_error('idproofphoto') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('idproofphoto'); ?></label> <?php } ?>
                  </div>

                  <div id="idproofphoto_preview" class="upload_img_preview pull-right">
                    <?php
                    $preview_idproofphoto = '';
                    if (set_value('idproofphoto_cropper') != "")
                    {
                      $preview_idproofphoto = set_value('idproofphoto_cropper');
                    }

                    if ($preview_idproofphoto != "")
                    { ?>
                      <a href="<?php echo $preview_idproofphoto . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Scanned Id Proof - '; echo $data_lightbox_title_common;?>">
                        <img src="<?php echo $preview_idproofphoto . "?" . time(); ?>">
                      </a>

                      <button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="idproofphoto" data-db_tbl_name="member_registration" data-title="Edit Id Proof" title="Edit Id Proof" alt="Edit Id Proof"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                    <?php }
                    else
                    {
                      echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                    } ?>
                  </div>
                  <div class="clearfix"></div>
                </div>
              </div>


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
                <div class="col-sm-9">
                  <ol>
                    <li>Pl ensure all images are clear, visible and readable after uploading, if not do  not submit and upload fresh set of images.</li>
                    <?php 
                    $examcode = base64_decode($this->input->get('ExId')); 
                      $file_size = '8KB';
                      if($examcode == 1052 || $examcode == 1054){
                        $file_size = '100KB';
                      }
                      if($examcode != 1052 && $examcode != 1054){ ?>
                      <li>Images format should be in JPG 8bit and size should be minimum 8KB and maximum 50KB.</li>
                    <?php } ?>
                    <li>Image Dimension of Photograph should be 100(Width) * 120(Height) Pixel only</li>
                    <li>Image Dimension of Signature should be 140(Width) * 60(Height) Pixel only</li>
                    <li>Image Dimension of ID Proof should be 400(Width) * 420(Height) Pixel only. Size should be minimum <?php echo $file_size; ?> and maximum 300KB.</li>
                  </ol>
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
              <div class="col-sm-6 col-sm-offset-2"> <a href="javascript:void(0);" class="btn btn-info" onclick="javascript:return non_mem_checkform();" id="preview">Preview and Proceed for Payment</a> 
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
				maxAge:70
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
	
    $('#new_captcha').click(function(event)
    {
        event.preventDefault();
    $.ajax({
 		type: 'POST',
 		url: site_url+'CSCNonreg/generatecaptchaajax/',
 		success: function(res)
 		{	
 			if(res!='')
 			{
        $('#captcha_img').html(res);
        $("#code").val("");
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

    $(function() {
      $("#doj1").dateDropdowns({
        submitFieldName: 'doj1',
        minAge: 0,
        maxAge: 59
      });
    });

    $(document).ready(function() {
      $("#doj1").change(function() {
        var sel_doj = $("#doj1").val();
        var exam_code_id = $("#exam_code_id").val();
        if (sel_doj != '') {
          var doj_arr = sel_doj.split('-');
          if (doj_arr.length == 3) {
            if(exam_code_id == '1052' || exam_code_id == '1054'){
              CompareMaxDate(doj_arr[2], doj_arr[1], doj_arr[0]);
            }else{
              CompareToday(doj_arr[2], doj_arr[1], doj_arr[0]);
            }
            
          } else {
            alert('Select valid date');
          }
        }
      });
    });


function CompareMaxDate(day,month,year)
  {
    var exam_date_exist = $("#exam_date_exist").val();
    //var check_start_date = "2023-07-01"; 
    var check_start_date = "1964-01-01";
    var check_start_date = new Date(check_start_date);
    var check_end_date = "2024-03-31"; 
    var check_end_date = new Date(check_end_date);
    check_end_date.setDate(check_end_date.getDate() + 1);
    //alert(exam_date_exist);
    var flag = 0;
    if(day!='' && month!='' && year!='')
    {
      /*var today = new Date();
      var dd = today.getDate(); 
      var mm = today.getMonth(); 
      var yyyy = today.getFullYear();*/

      var dd = "31"; 
      var mm = "02"; 
      var yyyy = "2024";
       
      if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} 
        var today = new Date(yyyy, mm, dd);
    
      var jday  = day;
      var jmnth = month;
      var jyear = year;
      var jdate = new Date(jyear, jmnth-1, jday);
      
      var sel_dob = $("#dob1").val();
      var dobYear = 0;
      if(sel_dob!='')
      {
        var dob_arr = sel_dob.split('-');
        if(dob_arr.length == 3)
        {
          dobYear = dob_arr[0];
        }
      }
      var minjoinyear = parseInt(dobYear) + parseInt(18);
      //console.log(jdate +'>'+ today);

      var examDate = new Date(exam_date_exist);
      var formattedExamDate = formatDateJs(examDate);
      // Add 9 months
      var ninemonthDate = new Date(jdate);
      ninemonthDate.setMonth(ninemonthDate.getMonth() + 9);
      //alert(ninemonthDate);
      var beforeninemonthDate = new Date(exam_date_exist);
      beforeninemonthDate.setMonth(beforeninemonthDate.getMonth() - 9);
      jdate.setDate(jdate.getDate() + 1); 
      
      /*if( jdate > today )
      {
        $("#doj_error").html('Date of joining should not be greater than 31-March-2024');
        flag = 0;
        return false;
      }
      else if( jdate < beforeninemonthDate ) // && jdate > examDate 
      {
        //console.log(jdate +'<'+ beforeninemonthDate);
        var formattedbeforeNineMonthDate = formatDateJs(beforeninemonthDate);
        $("#doj_error").html('Commencement of operations / joining as BC to be within 9 months from the date of examination.');
        //$("#doj_error").html('Please select your Date of Joining within 9 months (270 days) from the date of examination.<br> Your Examination Date is '+formattedExamDate+', your Date of Joining should be on or after '+formattedbeforeNineMonthDate+'.');
        flag = 0;
        return false;
      }*/
      if( jdate < check_start_date ) // && jdate > examDate 
      {
        $("#doj_error").html('Only Agents who joined the Bank as BC on or before March 31, 2024, are eligible.'); 
        flag = 0;
        return false;
      }
      else if( jdate > check_end_date ) // && jdate > examDate 
      {
        $("#doj_error").html('Only Agents who joined the Bank as BC on or before March 31, 2024, are eligible.'); 
        flag = 0;
        return false;
      }
      else if( jdate > examDate) // && jdate > examDate 
      { 
        //console.log(jdate +'>'+ examDate);
        var formattedbeforeNineMonthDate = formatDateJs(beforeninemonthDate);
        $("#doj_error").html('Only Agents who joined the Bank as BC on or before March 31, 2024, are eligible.');
        //$("#doj_error").html('Commencement of operations / joining as BC to be within 9 months from the date of examination.');
        flag = 0;
        return false;
      }
      else
      {
        $("#doj_error").html('');
        flag = 1;
      }
      
      if(jyear!='' && jyear < minjoinyear )
      {
        //alert("Please select Proper Year of Joining");
        $("#doj_error").html("Please select Proper Year of Joining");
        $("#doj_error").focus();
        flag = 0;
        return false;
      }
      else
      {
        $("#doj_error").html('');
        flag = 1;
      }
    }
    else
    {
      $("#doj_error").html('Please select valid date');
      $("#doj_error").focus();
      flag = 0;
    }
    if(flag==1)
      return true;
    else
      return false;
  }

  function formatDateJs(date) {
      var day = date.getDate();
    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", 
                      "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var month = monthNames[date.getMonth()];
    var year = date.getFullYear();

    // Add leading zero to the day if it's less than 10
    day = day < 10 ? '0' + day : day;

    return day + '-' + month + '-' + year;
  }

  function check_bank_bc_id_no(){
    var name_of_bank_bc = $("#name_of_bank_bc").val();
    var ippb_emp_id = $("#ippb_emp_id").val();
    var datastring='name_of_bank_bc='+name_of_bank_bc+'&ippb_emp_id='+ippb_emp_id+'&mem_type=NM';
    $.ajax({
        url:site_url+'Bcbfexam/check_bank_bc_id_no/',
        data: datastring,
        type:'POST',
        async: false,
        success: function(data) {
        if(data != ""){
           $("#ippb_emp_id_error").html(data);
           $("#ippb_emp_id_error").focus();
           return false;
        }else{
          $("#ippb_emp_id_error").html(data);
        }
      }
    });  
  }
</script>


<!-- START: JS CODE FOR IMAGE EDITOR -->
<?php $this->load->view('iibfbcbf/common/inc_lightbox_files'); ?>
<?php $this->load->view('iibfbcbf/common/inc_sweet_alert_files'); ?>
<?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>
<?php $this->load->view('iibfbcbf/common/inc_cropper_script', array('inc_fileChooser_accepted_files' => $inc_fileChooser_accepted_files, 'page_name'=>'csc_non_mem_reg')); ?>

<script>
  function validate_form_images(input_id) 
  {
    $("#page_loader").show();
     
    if(input_id == 'scannedphoto') { $('#scannedphoto').parsley().reset(); }
    else if(input_id == 'scannedsignaturephoto') { $('#scannedsignaturephoto').parsley().reset(); }
    else if(input_id == 'idproofphoto') { $('#idproofphoto').parsley().reset(); }
    else if(input_id == 'empidproofphoto') { $('#empidproofphoto').parsley().reset(); }
    else if(input_id == 'declarationform') { $('#declarationform').parsley().reset(); }

    $("#page_loader").hide();
  }
  function convertToUppercaseText(input) {
      input.value = input.value.toUpperCase();
  }

  function validateTotalNameLength() {
      let firstName = document.getElementById("firstname").value.trim();
      let middleName = document.getElementById("middlename").value.trim();
      let lastname = document.getElementById("lastname").value.trim();

      let totalLength = firstName.length + middleName.length + lastname.length;
      $("#lastname_error").html('');
      if (totalLength > 48) {
          //document.getElementById("errorMessage").innerText = 
              "Total length of First Name, Middle Name, and Last Name should not exceed 50 characters.";
          $("#lastname_error").html('The total length of First Name, Middle Name, and Last Name must not exceed 50 characters.');
          $("#lastname_error").focus();    
          return false; // Prevent form submission
      } 
      return true; // Allow form submission
  }

  /*Start: Corporate BC Changes*/
  function check_are_you_corporate_bc(val) {
     if(val == 'Yes'){
      $("#corporate_bc_option_div").show();
      
      $("input[name='corporate_bc_option']").prop('required', true);

     }else{
      $("#corporate_bc_option_div").hide();
      $("#corporate_bc_associated_div").hide();
      $("input[name='corporate_bc_option']").prop('checked', false);
      $("#corporate_bc_associated").val('');
      $("#corporate_bc_validation_message_div").hide();
        
      $("input[name='corporate_bc_option']").prop('required', false); 
     } 
  }

  function check_corporate_bc_option(val) {
     if(val == 'CSC'){
      $("#corporate_bc_option_div").show();
      $("#corporate_bc_associated_div").hide();
      $("#corporate_bc_associated").val('');
      $("#corporate_bc_validation_message_div").show();
      $("input[name='corporate_bc_associated']").prop('required', false);
     }else if(val == 'Other'){
      $("#corporate_bc_associated_div").show();
      $("#corporate_bc_validation_message_div").hide();
      $("input[name='corporate_bc_associated']").prop('required', true);
     }else{
      $("#corporate_bc_associated_div").hide();
      $("#corporate_bc_validation_message_div").hide();
      $("input[name='corporate_bc_associated']").prop('required', false);
     }
  } 
  //$("input[name='are_you_corporate_bc']").prop('checked', false);
  /*End: Corporate BC Changes*/
  
</script>
<!-- END: JS CODE FOR IMAGE EDITOR -->