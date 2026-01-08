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

  .content-wrapper {
    border-bottom: 1px solid #1287c0;
    border-left: 1px solid #1287c0;
    border-right: 1px solid #1287c0;
    width: 60%;
    margin: 0 auto 10px !important;
    padding: 0 10px;
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

  .content-header>h1 {
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

  .content-header {
    padding: 0;
    margin-bottom: 10px;
  }

  .nobg {
    background: rgba(0, 0, 0, 0) none repeat scroll 0 0 !important;
    border: medium none !important;
  }

  .email {
    line-height: 18px !important;
  }

  .box-body {
    padding: 0;
  }

  .example {
    text-align: left !important;
  }

  .example select {
    padding: 5px 10px !important;
    border: 1px solid #888 !important;
    border-radius: 0 !important;
  }

  .main-header {
    max-height: unset;
  }

  .note {
    font-size: 12px;
    line-height: 16px;
    display: inline-block;
    margin: 5px 0 0 0;
  }

  /*Cropper Image Editor*/
    #optionsModal > .modal-dialog, #cropModal > .modal-dialog { max-width: 600px; }
    #optionsModal > .modal-dialog h4.modal-title, #GuidelinesModal > .modal-dialog h4.modal-title, #cropModal > .modal-dialog h4.modal-title { text-align: center; }

    #GuidelinesModal > .modal-dialog { max-width: 800px; }
  /*Cropper Image Editor*/

  .txtuppercase{
    text-transform: uppercase;
  } 

.blink-highlight {
  background-color: #ffb300;
  animation: blink 1s step-start 0s infinite;
}

@keyframes blink {
  50% {
    background-color: transparent;
  }
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
    <h1 class="register"> Examination Application(Registration) for Non-Member category candidates <br />
      (Please read "Instructions to Applicants" before filling up the form) </h1>
    <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();
                      ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());
                                          ?></a></li>
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
                <td> a) &nbsp;Membership is open to the employees of recognized banking establishments both in the nationalized as well as private sector including the Reserve Bank of India, State Bank of India, other Financial Institutions, both Central and State Co-operative Banks and any other institutions in India who are Institutional Members of the Institute as may be approved by the Council from time to time. </td>
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
                <td> I - Applicant should have scanned copy of his/her i) photograph ii) signature and iii) id proof (ensuring that all are within the required specifications as under) <br>
                  <ol type="a">
                    <li>Images format should be in JPG 8bit and size should be minimum 8KB and maximum 50KB.</li>
                    <li>Image Dimension of Photograph should be 100(Width) * 120(Height) Pixel only;</li>
                    <li>Image Dimension of Signature should be 140(Width) * 60(Height) Pixel only.</li>
                    <li>Image Dimension of ID Proof should be 400(Width) * 420(Height) Pixel only. ID Proof should contain Name, Photo, Date of Birth and Signature. Size should be minimum 8KB and maximum 300KB.</li>
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
                  Note: Do not upload your Credit Card/Debit Card scanned image with the application.
                </td>
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
                <td>
                  <ol type="i">
                    <li>Visit Institute's web site www.iibf.org.in</li>
                    <li>Click on 'online membership registration'.</li>
                    <li>Read 'Instruction to applicants' carefully.</li>
                    <li>Fill up all the online application form,(all the fields mark '*' are mandatory), upload photo, signature, ID proff and follow the on-screen instructions to complete the registration process.</li>
                    <li>On successful completion of enrolment a confirmation SMS/email will be sent to the candidate intimating the membership number(membership number is your login id), password.</li>
                    <li>In case, even after 2/3 working days after enrolment no confirmation is received intimating the membership details, applicant should take up the matter with the Institute. (write to <a href="mailto:onlineservices@iibf.org.in">onlineservices@iibf.org.in</a> providing following details: 1) Membership number 2) Full Name 3) Date of Birth 4) Payment transaction details 5) Mobile no. and details problem/exact text of error message etc..)</li>
                    <li>For all failed transactions the amount is debited to applicant's Account, Institute will refund such amount within seven working days from the date of the transaction..</li>
                  </ol>
                </td>
              </tr>
              <tr>
                <td><b>5. </b></td>
                <td><b><u>General:</u> </b></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>
                  <ol type="a">
                    <li>A permanent I-card will be mailed to the applicant at the address given in the application.</li>
                    <li>The permanent I-card should be produced at the time of examination and whenever demanded for availing other services.</li>
                    <li>Enrolled members can view his/her profile/payment details using the login/password and also update his/her profile except for name, date of birth, photo, signature and ID proof.</li>
                    <li>Enrolled member can also apply for all other services of the Institute with the same login and password as and when available.</li>
                    <li>Any communication in regard to membership enrolment should sent to <a href="mailto:onlineservices@iibf.org.in">onlineservices@iibf.org.in</a> </li>
                  </ol>
                </td>
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
  <form class="form-horizontal" name="nonmemAddForm" id="nonmemAddForm" method="post" enctype="multipart/form-data">
    <input type="hidden" name="extype" id="extype" value="<?php echo $examinfo[0]['exam_type']; ?>">
    <input type="hidden" id="exname" name="exname" value=" <?php echo $examinfo[0]['description']; ?>">
    <input type="hidden" id="excd" name="excd" value="<?php echo $this->input->get('ExId'); ?>">
    <input id="examcode" name="examcode" type="hidden" value="<?php echo base64_decode($this->input->get('ExId')); ?>">
    <input id="eprid" name="eprid" type="hidden" value="<?php echo $examinfo[0]['exam_period']; ?>">
    <input id="exmonth" name="exmonth" type="hidden" value="<?php echo $examinfo[0]['exam_month']; ?>">
    <section class="content">
      <div class="row">
        <div class="col-md-12"> Please note that if you have already registered for any examination under Non-member Category in the past, the same Registration Number allotted to you can be used for registering for other examinations(other than DB&F Exam) applicable for Non-members as per the eligibility criteria given. Already Registered candidates have to apply for examinations by login using their USER ID and PASSWORD already provided - <a href=<?php echo base_url(); ?>nonmem><span style="color:#090">Click here for Login</span></a><br />
          <span style="color:#F00">Enter your details carefully, correction may not be possible later.</span>
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Basic Details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

            <?php //echo validation_errors(); 
            ?>
            <?php if ($this->session->flashdata('error') != '') { ?>
              <div class="alert alert-danger alert-dismissible" id="error_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                <?php echo $this->session->flashdata('error'); ?>
              </div>
            <?php }
            if ($this->session->flashdata('success') != '') { ?>
              <div class="alert alert-success alert-dismissible" id="success_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                <?php echo $this->session->flashdata('success'); ?>
              </div>
            <?php }
            if (validation_errors() != '') { ?>
              <div class="alert alert-danger alert-dismissible" id="error_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                <?php echo validation_errors(); ?>
              </div>
            <?php }
            ?>
            <div class="box-body">
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">First Name <span style="color:#f00">*</span></label>
                <div class="col-sm-2">
                  <select name="sel_namesub" id="sel_namesub" class="form-control" required>
                    <option value="">Select</option>
                    <option value="Mr." <?php echo  set_select('sel_namesub', 'Mr.'); ?>>Mr.</option>
                    <option value="Mrs." <?php echo  set_select('sel_namesub', 'Mrs.'); ?>>Mrs.</option>
                    <option value="Ms." <?php echo  set_select('sel_namesub', 'Ms.'); ?>>Ms.</option>
                    <option value="Dr." <?php echo  set_select('sel_namesub', 'Dr.'); ?>>Dr.</option>
                    <option value="Prof." <?php echo  set_select('sel_namesub', 'Prof.'); ?>>Prof.</option>
                  </select>
                  <span class="error" id="tiitle_error">
                    <?php //echo form_error('firstname');
                    ?>
                  </span>
                </div>
                (Max 30 Characters)
                <div class="col-sm-3">
                  <input type="text" class="form-control txtuppercase" id="firstname" name="firstname" placeholder="First Name" required value="<?php echo set_value('firstname'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z- ]+$/" data-parsley-maxlength="30" onchange="createfullname()" onkeyup="createfullname(); convertToUppercaseText(this); validateTotalNameLength();" onblur="createfullname()">
                  <span class="error">
                    <?php //echo form_error('firstname');
                    ?>
                  </span>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control txtuppercase" id="middlename" name="middlename" placeholder="Middle Name" value="<?php echo set_value('middlename'); ?>" data-parsley-pattern="/^[a-zA-Z- ]+$/" data-parsley-maxlength="30" onchange="createfullname()" onkeyup="createfullname(); convertToUppercaseText(this); validateTotalNameLength();" onblur="createfullname()">
                  <span class="error">
                    <?php //echo form_error('middlename');
                    ?>
                  </span>
                </div>
                (Max 30 Characters)
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control txtuppercase" id="lastname" name="lastname" placeholder="Last Name" value="<?php echo set_value('lastname'); ?>" data-parsley-pattern="/^[a-zA-Z- ]+$/" data-parsley-maxlength="30" onchange="createfullname()" onkeyup="createfullname(); convertToUppercaseText(this); validateTotalNameLength();" onblur="createfullname()">
                  <span class="error" id="lastname_error">
                    <?php //echo form_error('lastname');
                    ?>
                  </span>
                </div>
                (Max 30 Characters)
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Full Name</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="nameoncard" name="nameoncard" placeholder="Full Name" value="<?php echo set_value('nameoncard'); ?>" readonly disabled>
                  <small style="color: #F00;">(Please check that you have entered your name correctly)</small>
                </div>
              </div>
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
                  <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" required value="<?php echo set_value('addressline1'); ?>" data-parsley-maxlength="30" data-parsley-pattern="/^[a-zA-Z0-9/ ]+$/">
                  <span class="error">
                    <?php //echo form_error('addressline1');
                    ?>
                  </span>
                </div>
                (Max 30 Characters)
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line2</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2" value="<?php echo set_value('addressline2'); ?>" data-parsley-maxlength="30" data-parsley-pattern="/^[a-zA-Z0-9/ ]+$/">
                  <span class="error">
                    <?php //echo form_error('addressline2');
                    ?>
                  </span>
                </div>
                (Max 30 Characters)
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line3</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line3" value="<?php echo set_value('addressline3'); ?>" data-parsley-maxlength="30" data-parsley-pattern="/^[a-zA-Z0-9/ ]+$/">
                  <span class="error">
                    <?php //echo form_error('addressline3');
                    ?>
                  </span>
                </div>
                (Max 30 Characters)
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line4</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4" value="<?php echo set_value('addressline4'); ?>" data-parsley-maxlength="30" data-parsley-pattern="/^[a-zA-Z0-9/ ]+$/">
                  <span class="error">
                    <?php //echo form_error('addressline4');
                    ?>
                  </span>
                </div>
                (Max 30 Characters)
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">District <span style="color:#f00">*</span></label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="district" name="district" placeholder="District" required value="<?php echo set_value('district'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30">
                  <span class="error">
                    <?php //echo form_error('district');
                    ?>
                  </span>
                </div>
                (Max 30 Characters)
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">City <span style="color:#f00">*</span></label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo set_value('city'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30">
                  <span class="error">
                    <?php //echo form_error('city');
                    ?>
                  </span>
                </div>
                (Max 30 Characters)
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">State <span style="color:#f00">*</span></label>
                <div class="col-sm-3">
                  <select class="form-control" id="state" name="state" required>
                    <option value="">Select</option>
                    <?php if (count($states) > 0) {
                      foreach ($states as $row1) {   ?>
                        <option value="<?php echo $row1['state_code']; ?>" <?php echo  set_select('state', $row1['state_code']); ?>><?php echo $row1['state_name']; ?></option>
                    <?php }
                    } ?>
                  </select>
                  <input hidden="statepincode" id="statepincode" value="">
                </div>
                <label for="roleid" class="col-sm-3 control-label">Pincode/Zipcode <span style="color:#f00">*</span></label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode/Zipcode" required value="<?php echo set_value('pincode'); ?>" data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-nonmemcheckpin data-parsley-type="number" data-parsley-trigger-after-failure="focusout">
                  (Max 6 digits) <span class="error">
                    <?php //echo form_error('pincode');
                    ?>
                  </span>
                </div>
              </div>

              <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of Birth *</label>
                  <div class="col-sm-2">
                      <input type="text" class="form-control pull-right" id="datepicker"  name="dob" placeholder="Date of Birth" required value="<?php echo set_value('dob'); ?>" >
                      <span class="error"><?php //echo form_error('dob');
                                          ?></span>
                    </div>
                </div>-->

              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of Birth <span style="color:#f00">*</span></label>
                <div class="col-sm-4 example">
                  <input type="hidden" id="dob1" name="dob" required value="<?php echo set_value('dob1'); ?>">
                  <?php
                  $min_year = date('Y', strtotime("- 18 year"));
                  $max_year = date('Y', strtotime("- 72 year"));

                  if (base64_decode($this->input->get('ExId')) == '101' || base64_decode($this->input->get('ExId')) == '1046' || base64_decode($this->input->get('ExId')) == '1047') {
                    $max_year = date('Y', strtotime("- 70 year"));
                  }
                  ?>
                  <input type='hidden' name='minyear' id='minyear' value="<?php echo $min_year; ?>">
                  <input type='hidden' name='maxyear' id='maxyear' value="<?php echo $max_year; ?>">
                  <span id="dob_error" class="error"></span>
                </div>

                <!--<input type="text" class="form-control pull-right" id="datepicker"  name="dob" placeholder="Date of Birth" required value="<?php echo set_value('dob'); ?>" >-->
                <span class="error">
                  <?php //echo form_error('dob');
                  ?>
                </span>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Gender <span style="color:#f00">*</span></label>
                <div class="col-sm-3">
                  <input type="radio" class="minimal cls_gender" id="female" checked="checked" name="gender" required value="female" <?php echo set_radio('gender', 'female'); ?>>
                  Female
                  <input type="radio" class="minimal cls_gender" id="male" name="gender" required value="male" <?php echo set_radio('gender', 'male'); ?>>
                  Male <span class="error">
                    <?php //echo form_error('gender');
                    ?>
                  </span>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Qualification <span style="color:#f00">*</span></label>
                <div class="col-sm-6">
                  <input type="radio" class="minimal" id="U" name="optedu" value="U" onclick="changedu(this.value)" <?php echo set_radio('optedu', 'U'); ?>>
                  Under Graduate
                  <input type="radio" class="minimal" id="G" name="optedu" value="G" onclick="changedu(this.value)" <?php echo set_radio('optedu', 'G'); ?>>
                  Graduate
                  <input type="radio" class="minimal" id="P" name="optedu" value="P" onclick="changedu(this.value)" <?php echo set_radio('optedu', 'P'); ?>>
                  Post Graduate <span class="error">
                    <?php //echo form_error('optedu');
                    ?>
                  </span>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Please specify <span style="color:#f00">*</span></label>
                <div class="col-sm-5" <?php if (set_value('eduqual1') || set_value('eduqual2') || set_value('eduqual3')) {
                                        echo 'style="display:none"';
                                      } else {
                                        echo 'style="display:block"';
                                      } ?> id="edu">
                  <select id="eduqual" name="eduqual" class="form-control" <?php if (!set_value('eduqual1') && !set_value('eduqual2') && !set_value('eduqual3')) {
                                                                              echo 'required';
                                                                            } ?>>
                    <option value="" selected="selected">--Select--</option>
                  </select>
                </div>
                <div class="col-sm-5" <?php if (set_value('optedu') == 'U') {
                                        echo 'style="display:block;"';
                                      } else if (!set_value('optedu')) {
                                        echo 'style="display:none;"';
                                      } else {
                                        echo 'style="display:none;"';
                                      } ?> id="UG">
                  <select class="form-control" id="eduqual1" name="eduqual1" <?php if (set_value('optedu') == 'U') {
                                                                                echo 'required';
                                                                              } ?>>
                    <option value="">--Select--</option>
                    <?php if (count($undergraduate)) {
                      foreach ($undergraduate as $row1) {   ?>
                        <option value="<?php echo $row1['qid']; ?>" <?php echo  set_select('eduqual1', $row1['qid']); ?>><?php echo $row1['name']; ?></option>
                    <?php }
                    } ?>
                  </select>
                  <span class="error">
                    <?php //echo form_error('eduqual1');
                    ?>
                  </span>
                </div>
                <div class="col-sm-5" <?php if (set_value('optedu') == 'G') {
                                        echo 'style="display:block"';
                                      } else {
                                        echo 'style="display:none"';
                                      } ?> id="GR">
                  <select class="form-control" id="eduqual2" name="eduqual2" <?php if (set_value('optedu') == 'G') {
                                                                                echo 'required';
                                                                              } ?>>
                    <option value="">--Select--</option>
                    <?php if (count($graduate)) {
                      foreach ($graduate as $row2) {   ?>
                        <option value="<?php echo $row2['qid']; ?>" <?php echo  set_select('eduqual2', $row2['qid']); ?>><?php echo $row2['name']; ?></option>
                    <?php }
                    } ?>
                  </select>
                  <span class="error">
                    <?php //echo form_error('eduqual2');
                    ?>
                  </span>
                </div>
                <div class="col-sm-5" <?php if (set_value('optedu') == 'P') {
                                        echo 'style="display:block"';
                                      } else {
                                        echo 'style="display:none"';
                                      } ?>id="PG">
                  <select class="form-control" id="eduqual3" name="eduqual3" <?php if (set_value('optedu') == 'P') {
                                                                                echo 'required';
                                                                              } ?>>
                    <option value="">--Select--</option>
                    <?php if (count($postgraduate)) {
                      foreach ($postgraduate as $row3) {   ?>
                        <option value="<?php echo $row3['qid']; ?>" <?php echo  set_select('eduqual3', $row3['qid']); ?>><?php echo $row3['name']; ?></option>
                    <?php }
                    } ?>
                  </select>
                  <span class="error">
                    <?php //echo form_error('eduqual3');
                    ?>
                  </span>
                </div>
              </div>


              <!-- Start gaurav shewale (7th may 2024) code for FEDI -->
              <?php if (isset($examcode) && $examcode == 1009 && $member_type == 'NM') { ?>
                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Bank/Institution working <span style="color:#F00">*</span></label>
                  <div class="col-sm-5" style="display:block">
                    <select id="institutionworking" name="institutionworking" class="form-control" required>
                      <option value="">--Select--</option>
                      <?php if (count($institution_master)) {
                        foreach ($institution_master as $institution_row) {   ?>
                          <option value="<?php echo $institution_row['institude_id']; ?>" <?php echo  set_select('institutionworking', $institution_row['institude_id']); ?>><?php echo $institution_row['name'] . "(" . $institution_row['institude_id'] . ")"; ?></option>
                      <?php }
                      } ?>
                    </select>
                    <span class="error"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Branch/Office <span style="color:#F00">*</span></label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="office" name="office" placeholder="Branch/Office" required value="<?php echo set_value('office'); ?>" data-parsley-maxlength="20" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/">
                    <span class="error"></span>
                  </div>
                  (Max 20 Characters)
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Designation <span style="color:#F00">*</span></label>
                  <div class="col-sm-5" style="display:block">
                    <select id="designation" name="designation" class="form-control" required>
                      <option value="">--Select--</option>
                      <?php if (count($designation)) {
                        foreach ($designation as $designation_row) {   ?>
                          <option value="<?php echo $designation_row['dcode']; ?>" <?php echo  set_select('designation', $designation_row['dcode']); ?>><?php echo $designation_row['dname']; ?></option>
                      <?php }
                      } ?>
                    </select>
                    <span class="error"></span>
                  </div>
                </div>
              <?php }
                if (isset($examcode) && ($examcode == 1009 || $examcode == 101 || $examcode == 1046 || $examcode == 1047) && $member_type == 'NM'){  ?>  
                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label"><?php echo (isset($examcode) && $examcode == '1009' ? 'Date of joining Bank/Institution' : 'Date of commencement of operations/joining as BC'); ?> <span style="color:#F00">*</span></label>
                  <div class="col-sm-4 doj">
                    <div class="col-sm-2 example">
                      <input type="hidden" id="doj1" name="doj" value="<?php echo set_value('doj1'); ?>">
                      <input type="hidden" id="exam_code_id" value="<?php echo $examcode; ?>">
                      <input type="hidden" name="exam_date_exist" id="exam_date_exist" value="<?php echo date('Y-m-d', strtotime($compulsory_subjects[0]['exam_date'])); ?>">
                    </div>
                    <span id="doj_error" class="error"></span>
                  </div>
                </div>
              <?php }
                if (isset($examcode) && ($examcode == 101 || $examcode == 1046 || $examcode == 1047) && $member_type == 'NM'){  ?>  
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
              <?php } ?>   
              

              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Bank Employee Id <span style="color:#F00"><?php echo (isset($examcode) && ($examcode == 1009) ? '*' : ''); ?></span></label>
                <div class="col-sm-5">
                  <input  <?php if (isset($examcode) && ($examcode == 1009) ) echo'required'; ?> type="text" class="form-control" id="bank_emp_id" name="bank_emp_id" placeholder="Bank Employee Id" value="<?php echo set_value('bank_emp_id'); ?>" data-parsley-maxlength="20" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                  <span class="error"></span>
                </div>
              </div>

              <!-- End gaurav shewale(7th may 2024) code for FEDI -->
              <?php
              if(isset($examcode) && ($examcode == 101 || $examcode == 1046 || $examcode == 1047 || $examcode == 991 || $examcode == 997)){ 
              ?>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Bank BC ID No <span style="color:#F00">*</span></label>
                <div class="col-sm-5">
                  <input required type="text" class="form-control" id="ippb_emp_id" name="ippb_emp_id" placeholder="Bank BC ID No" onchange="check_bank_bc_id_no();" value="<?php echo set_value('ippb_emp_id'); ?>" data-parsley-maxlength="20" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                  <span class="error" id="ippb_emp_id_error"></span>
                </div>
              </div>
              <?php } ?>

              <!-- Start Pooja Mane (11-07-2024) code for email verification -->

              <?php 

                $email_verify_status  = set_value('email_verify_status') != '' ? set_value('email_verify_status') : 'no'; 
                $mobile_verify_status = set_value('mobile_verify_status') != '' ? set_value('mobile_verify_status') : 'no';
                $emailStatus  = false;  
                $mobileStatus = false;
                if ($email_verify_status == 'yes') {
                  $emailStatus = true;
                }

                if ($mobile_verify_status == 'yes') {
                  $mobileStatus = true;
                }

                if($_POST['email_verified'] == 'yes'){
                  $emailStatus = 'yes';
                  $email_verify_status = 'yes';
                }
                if($_POST['mobile_verified'] == 'yes'){
                  $mobileStatus = 'yes';
                  $mobile_verify_status = 'yes';
                }
              ?>

              <?PHP //print_r($_POST); //echo $_POST['mobile_verified']; echo $_POST['email_verified'];?>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email Id <span style="color:#F00">*</span></label>
                <div class="row">
                  <div class="col-sm-5">
                    <input type="email" class="form-control email-id" id="email" name="email" placeholder="Email" data-parsley-type="email" value="<?php echo set_value('email'); ?>" data-parsley-maxlength="45" required data-parsley-emailcheck data-parsley-trigger-after-failure="focusout" <?php if($emailStatus) { ?> readonly <?php } ?>>
                  </div>
                  <div class="col-sm-2">
                  <?php ?>
                  <button type="button" class="btn btn-info send-otp" id="send_otp_btn" data-type='send_otp' <?php if($emailStatus == 'yes') { ?> style="display:none;" <?php } ?>>Get OTP</button>
                  <?php?>
                  <a class="btn btn-info" id="reset_btn" href="javascript:void(0)" <?php if($emailStatus == 'yes') { ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>Change Email</a>
                  <?php ?>
                  <!-- (Enter valid and correct Email Id to receive communication)  -->
                  </div>
                  <span class="error">
                    <?php //echo form_error('email');
                    ?>
                  </span>
                </div>
              </div>

              <div class="form-group verify-otp-section" style="display:none;">
                <label for="roleid" class="col-sm-3 control-label">OTP <span style="color:#F00">*</span></label>
                <div class="row">
                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="otp" name="otp" placeholder="OTP" onKeyPress="if(this.value.length==6) return false;" value="<?php echo set_value('otp'); ?>">
                  </div>
                  <div class="col-sm-4">
                    <button type="button" class="btn btn-info verify-otp" data-verify-type='email'>Verify OTP </button>
                    <button type="button" class="btn btn-info send-otp" data-type='resend_otp'>Resend OTP</button>
                  </div>  
                </div>  
              </div>

              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Phone </label>
                <div class="col-sm-4">
                  <label for="roleid" class="col-sm-4 control-label" style="padding-left:0; text-align:left; padding-right:10px;">STD Code</label>
                  <input type="text" class="form-control" id="stdcode" name="stdcode" placeholder="STD Code" data-parsley-type="number" data-parsley-maxlength="4" value="<?php echo set_value('stdcode'); ?>" style="width:55%;" data-parsley-trigger-after-failure="focusout">
                  <span class="error">
                    <?php //echo form_error('stdcode');
                    ?>
                  </span>
                </div>
                <div class="col-sm-4">
                  <label for="roleid" class="col-sm-4 control-label" style="padding-left:0; text-align:left; padding-right:10px;">Phone No</label>
                  <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone No" data-parsley-minlength="7" data-parsley-type="number" data-parsley-maxlength="12" value="<?php echo set_value('phone'); ?>" style="width:65%;" data-parsley-trigger-after-failure="focusout">
                  <span class="error">
                    <?php //echo form_error('phone');
                    ?>
                  </span>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Mobile <span style="color:#F00">*</span></label>
                <div class="row">
                  <div class="col-sm-5">
                    <input type="text" class="form-control mobile" id="mobile" name="mobile" placeholder="Mobile" onKeyPress="if(this.value.length==10) return false;" data-parsley-minlength="10" data-parsley-maxlength="10" value="<?php echo set_value('mobile'); ?>" required data-parsley-mobilecheck data-parsley-trigger-after-failure="focusout" <?php if($mobileStatus) { ?> readonly <?php } ?>>
                    <span class="error">
                      <?php //echo form_error('mobile');
                      ?>
                    </span>
                  </div>  
                  <div class="col-sm-3">
                    <button type="button" class="btn btn-info send-otp-mobile" id="send_otp_btn_mobile" data-type='send_otp' <?php if($mobileStatus == 'yes') { ?> style="display:none;" <?php } ?>>Get OTP</button>
                    <a class="btn btn-info" id="reset_btn_mobile" href="javascript:void(0)" <?php if($mobileStatus == 'yes') { ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>Change Mobile No.</a>
                    <!-- (Enter valid and correct Email Id to receive communication)  -->
                  </div>
                </div>  
              </div>

              <div class="form-group verify-otp-section-mobile" style="display:none;">
                <label for="roleid" class="col-sm-3 control-label">OTP <span style="color:#F00">*</span></label>
                <div class="row">
                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="otp_mobile" name="otp_mobile" placeholder="OTP" onKeyPress="if(this.value.length==6) return false;" value="<?php echo set_value('otp'); ?>">
                  </div>
                  <div class="col-sm-4">
                    <button type="button" class="btn btn-info verify-otp-mobile" data-verify-type='mobile'>Verify OTP </button>
                    <button type="button" class="btn btn-info send-otp-mobile" data-type='resend_otp'>Resend OTP</button>
                  </div>  
                </div>  
              </div>

              <?php if (base64_decode($this->input->get('ExId')) == 101 || base64_decode($this->input->get('ExId')) == 1046 || base64_decode($this->input->get('ExId')) == 1047) { ?>
                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Aadhar Card Number </label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control " id="aadhar_card" name="aadhar_card" placeholder="Aadhar Card Number" data-parsley-minlength="12" data-parsley-maxlength="12" data-parsley-type="number" size="12" value="<?php echo set_value('aadhar_card'); ?>" data-parsley-trigger-after-failure="focusout">
                    <!--(Max 25 Characters)-->
                    <span class="error">
                      <?php //echo form_error('idNo');
                      ?>
                    </span>
                  </div>
                </div>
              <?php
              } else { ?>
                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Aadhar Card Number <!--<span style="color:#f00">*</span>--></label>
                  <div class="col-sm-5">
                    <?php /*?> <input type="text" class="form-control " id="aadhar_card"  name="aadhar_card" placeholder="Aadhar Card Number" 
                       data-parsley-minlength="12" data-parsley-maxlength="12" data-parsley-type="number" size="12"
                       required value="<?php echo set_value('aadhar_card');?>" data-parsley-trigger-after-failure="focusout"><!--(Max 25 Characters)-->
                      <span class="error"><?php //echo form_error('idNo');?></span><?php */ ?>
                    <input type="text" class="form-control " id="aadhar_card" name="aadhar_card" placeholder="Aadhar Card Number" data-parsley-minlength="12" data-parsley-maxlength="12" data-parsley-type="number" size="12" value="<?php echo set_value('aadhar_card'); ?>" data-parsley-trigger-after-failure="focusout">
                    <!--(Max 25 Characters)-->
                    <span class="error">
                      <?php //echo form_error('idNo');
                      ?>
                    </span>
                  </div>
                </div>
              <?php
              } ?>

              <!-- <div class="form-group">
                <label for="bank_emp_id" class="col-sm-3 control-label">Bank Employee ID <span style="color:#f00">*</span></label>

                <div class="col-sm-5">
                  <input type="text" class="form-control" id="bank_emp_id" name="bank_emp_id" placeholder="Bank Employee ID" value="<?php echo set_value('bank_emp_id'); ?>" required data-parsley-trigger-after-failure="focusout">
                  <span class="error"> </span> 
                </div>
              </div> -->

              <?php 
              $file_upload_size_msg = 'Please Upload only .jpg, .jpeg Files upto 50KB';
              if(isset($examcode) && ($examcode == 101 || $examcode == 1046 || $examcode == 1047)){
                $file_upload_size_msg = 'Please Upload only .jpg or .jpeg files between 20 KB and 50 KB.';
              }
              ?>

              <!-- ADDED BY POOJA MANE : ON : 08-06-2023 -->
              <div class="form-group">
                <div class="col-sm-11">
                  <div><a style="color:#FF0000;" href="https://iibf.esdsconnect.com//uploads/Guideline_for_photo_image_loading_final.pdf" target="_blank"><strong style="color:#F00; text-decoration:underline">Guidelines for photo-image loading (Annexure I and Annexure II)</strong></a></div>
                </div>
              </div>
              <!-- ADDED BY POOJA MANE : ON : 08-06-2023 -->
              <?php /* <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Upload your scanned Photograph <span style="color:#f00">**</span></label>
                <div class="col-sm-5">
                  <input type="file" class="" name="scannedphoto" id="scannedphoto" required onchange="validateFile(event, 'error_photo_size', 'image_upload_scanphoto_preview', '50kb')">
                  <input type="hidden" id="hiddenphoto" name="hiddenphoto">
                  <span class="note"><?php echo $file_upload_size_msg; ?><!-- Please Upload only .jpg, .jpeg Files upto 50KB --></span></br>
                  <div id="error_photo" class="error"></div>
                  <br>
                  <div id="error_photo_size" class="error"></div>
                  <span class="photo_text" style="display:none;"></span> <span class="error">
                    <?php //echo form_error('scannedphoto');
                    ?>
                  </span>
                </div>
                <img class="mem_reg_img" id="image_upload_scanphoto_preview" height="100" width="100" src="/assets/images/default1.png" />
              </div> */ ?>

              <!-- START: FOR IMAGE EDITOR -->
              <?php $data_lightbox_title_common = "Non Member Registration"; ?>
              <input type="hidden" name="form_value" id="form_value" value="form_value">
              <input type="hidden" id="data_lightbox_title_hidden" value="<?php echo $data_lightbox_title_common; ?>">
              <?php $inc_fileChooser_accepted_files = '.jpg, .jpeg'; ?>
              <input type="hidden" name="inc_fileChooser_accepted_files" id="inc_fileChooser_accepted_files" value="<?php echo $inc_fileChooser_accepted_files; ?>">
              <!-- END: FOR IMAGE EDITOR -->

              <div class="form-group"><?php // Upload your scanned Photograph  ?>
                <label for="scannedphoto" class="col-sm-4 control-label">Upload your scanned Photograph <span style="color:#F00">*</span></label>
                <div class="col-sm-7">
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


              <?php /*<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"> Upload your scanned Signature Specimen<span style="color:#f00">**</span></label>
                <div class="col-sm-5">
                  <input type="file" class="" name="scannedsignaturephoto" id="scannedsignaturephoto" required onchange="validateFile(event, 'error_signature_size', 'image_upload_sign_preview', '50kb')">
                  <input type="hidden" id="hiddenscansignature" name="hiddenscansignature">
                  <span class="note"><?php echo $file_upload_size_msg; ?><!-- Please Upload only .jpg, .jpeg Files upto 50KB --></span></br>
                  <div id="error_signature" class="error"></div>
                  <br>
                  <div id="error_signature_size" class="error"></div>
                  <span class="signature_text" style="display:none;"></span> <span class="error">
                    <?php //echo form_error('scannedsignaturephoto');
                    ?>
                  </span>
                </div>
                <img class="mem_reg_img" id="image_upload_sign_preview" height="100" width="100" src="/assets/images/default1.png" />
              </div> */ ?>

              <div class="form-group"><?php // Upload Your Scanned Signature Specimen  ?>
                <label for="scannedsignaturephoto" class="col-sm-4 control-label">Upload Your Scanned Signature Specimen <span style="color:#F00">*</span></label>
                <div class="col-sm-7">
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
                  <?php if (count($idtype_master) > 0) {
                    $i = 1;
                    foreach ($idtype_master as $idrow) { ?>
                      <input name="idproof" value="<?php echo $idrow['id']; ?>" type="radio" class="minimal" <?php if (set_value('idproof')) {
                                                                                                              echo set_radio('idproof', $idrow['id'], TRUE);
                                                                                                            } else {
                                                                                                              if ($i == 1) {
                                                                                                                echo 'checked="checked"';
                                                                                                              }
                                                                                                            } ?>>
                      <?php echo $idrow['name']; ?><br>
                  <?php
                      $i++;
                    }
                  } ?>
                  <span class="error">
                    <?php //echo form_error('idproof');
                    ?>
                  </span>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">ID No. <span style="color:#f00">*</span></label>
                <div class="col-sm-5">
                  <input type="text" class="form-control " id="idNo" name="idNo" placeholder="ID No." required value="<?php echo set_value('idNo'); ?>" data-parsley-pattern="/^[a-zA-Z0-9][a-zA-Z0-9 ]+$/" data-parsley-maxlength="25">
                  <!--(Max 25 Characters)-->
                  <span class="error">
                    <?php //echo form_error('idNo');
                    ?>
                  </span>
                </div>
              </div>

              <?php /* <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Upload your id proof <span style="color:#f00">**</span></label>
                <div class="col-sm-5">
                  <input type="file" class="" name="idproofphoto" id="idproofphoto" required onchange="validateFile(event, 'error_idproofphoto_size', 'image_upload_idproof_preview', '300kb')">
                  <input type="hidden" id="hiddenidproofphoto" name="hiddenidproofphoto">
                  <span class="note">Please Upload only .jpg, .jpeg Files upto 300KB</span>
                  <div id="error_dob" class="error"></div>
                  <br>
                  <div id="error_idproofphoto_size" class="error"></div>
                  <span class="dob_proof_text" style="display:none;"></span> <span class="error">
                    <?php //echo form_error('idproofphoto');
                    ?>
                  </span>
                </div>
                <img class="mem_reg_img" id="image_upload_idproof_preview" height="100" width="100" src="/assets/images/default1.png" />
              </div> */ ?>

              <div class="form-group"><?php // Upload Your Id Proof  ?>
                <label for="idproofphoto" class="col-sm-4 control-label">Upload Your Id Proof <span style="color:#F00">*</span></label>
                <div class="col-sm-7">
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



              <?php
              $file_size = '300kb';
              if(isset($examcode) && $examcode == 101 || $examcode == 1046 || $examcode == 1047){
                $file_size = '100kb';
              }
              ?>

              <!-------------- Gaurav Add the employee id card field (10-5-2024)------->
              <?php if (isset($examcode) && ($examcode == 1009 || $examcode == 101 || $examcode == 1046 || $examcode == 1047) && $member_type == 'NM') { ?>
                <?php /* <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label"><?php echo (isset($examcode) && ($examcode == 101 || $examcode == 1046 || $examcode == 1047) ? 'Upload Bank BC ID Card' : 'Upload your Employee Id proof'); ?> <span style="color:#f00">**</span></label>
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


                <div class="form-group"><?php // Upload Your Upload Bank BC ID Card / Employee Id proof  ?>
                <?php 
                  $image_nm_emp_bank = (isset($examcode) && ($examcode == 101 || $examcode == 1046 || $examcode == 1047) ? 'Upload Bank BC ID Card' : 'Upload your Employee Id proof'); 
                  $field_nm_emp_bank = (isset($examcode) && ($examcode == 101 || $examcode == 1046 || $examcode == 1047) ? 'bank_bc_id_card' : 'empidproofphoto');
                ?>
                <label for="empidproofphoto" class="col-sm-4 control-label"><?php echo (isset($examcode) && ($examcode == 101 || $examcode == 1046 || $examcode == 1047) ? 'Upload Bank BC ID Card' : 'Upload your Employee Id proof'); ?> <span style="color:#F00">*</span></label>
                <div class="col-sm-7">
                  <div class="img_preview_input_outer pull-left">
                    <input type="file" name="empidproofphoto" id="empidproofphoto" class="form-control hide_input_file_cropper" required data-parsley-errors-container="#empidproofphotoError" />

                    <div class="image-input image-input-outline image-input-circle image-input-empty">
                      <div class="profile-progress"></div>
                      <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('<?php echo $field_nm_emp_bank; ?>', 'member_registration', 'Edit <?php echo $image_nm_emp_bank; ?>');" onblur="validate_form_images('empidproofphoto')"><?php echo $image_nm_emp_bank; ?></button>
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


               <?php }
                if (isset($examcode) && $examcode == 1009 && $member_type == 'NM') { ?> 
                <div class="form-group">
                  <div class="col-sm-11">
                    <span><label class="box-title">Declaration Form :</label> Mandatorily upload the Declaration form signed(with stamped) by Branch Manager/HOD.</span>
                    <div><a style='color:#FF0000;' href=" <?php echo base_url() ?>uploads/declaration/DECLARATION_1.pdf" target="_blank"><strong style="color:#F00; text-decoration:underline">Please click here to PRINT.</strong></a></div>
                  </div>
                </div>

                <?php /* <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Upload your Declaration Form <span style="color:#F00">**</span></label>
                  <div class="col-sm-5">
                    <input type="file" name="declarationform" id="declarationform" required onchange="validateFile(event, 'error_declaration', 'image_upload_declarationform_preview', '300kb')">
                    <input type="hidden" id="hiddendeclarationform" name="hiddendeclarationform">
                    <span class="note">Please Upload only .jpg, .jpeg Files upto 300KB</span></br>
                    <span class="note-error" id="error_declaration"></span>
                    <br>
                    <div id="error_declaration_size"></div>
                    <span class="declaration_proof_text" style="display:none;"></span> <span class="error">
                      <?php //echo form_error('declarationform');
                      ?>
                    </span>
                  </div>
                  <img class="mem_reg_img" id="image_upload_declarationform_preview" height="100" width="100" src="/assets/images/default1.png" />
                  <div class="col-sm-12">
                    <!--<div id="declaration_id" style="display:none">
                    <span style='color:#FF0000;'>For ID Proof,  ID Card issued by Employer is compulsory and for Newly inducted employees if the same is  not available, Declaration Form duly filled and endorsed by employer to be uploaded as per the format given here:   Pl</span>   <a href="<?php echo base_url() ?>uploads/declaration/Declaration-format.jpg" target="_blank">CLICK / Print</a>
                  </div>-->
                  </div>
                </div> */ ?>

                <div class="form-group"><?php // Upload Your Declaration Form ?>
                <label for="declarationform" class="col-sm-4 control-label">Upload Your Declaration Form <span style="color:#F00">*</span></label>
                <div class="col-sm-7">
                  <div class="img_preview_input_outer pull-left">
                    <input type="file" name="declarationform" id="declarationform" class="form-control hide_input_file_cropper" required data-parsley-errors-container="#declarationformError" />

                    <div class="image-input image-input-outline image-input-circle image-input-empty">
                      <div class="profile-progress"></div>
                      <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('declarationform', 'member_registration', 'Edit Declaration Form');" onblur="validate_form_images('declarationform')">Upload Declaration Form</button>
                    </div>
                    <note class="form_note" id="declarationform_err">Note: Please select only .jpg, .jpeg file upto 20MB.</note>
                    <span id="declarationformError"></span>

                    <input type="hidden" name="declarationform_cropper" id="declarationform_cropper" value="<?php echo set_value('declarationform_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>

                    <?php if (form_error('declarationform') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('declarationform'); ?></label> <?php } ?>
                  </div>

                  <div id="declarationform_preview" class="upload_img_preview pull-right">
                    <?php
                    $preview_declarationform = '';
                    if (set_value('declarationform_cropper') != "")
                    {
                      $preview_declarationform = set_value('declarationform_cropper');
                    }

                    if ($preview_declarationform != "")
                    { ?>
                      <a href="<?php echo $preview_declarationform . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Upload Declaration Form - '; echo $data_lightbox_title_common;?>">
                        <img src="<?php echo $preview_declarationform . "?" . time(); ?>">
                      </a>

                      <button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="declarationform" data-db_tbl_name="member_registration" data-title="Edit Declaration Form" title="Edit Declaration Form" alt="Edit Declaration Form"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
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
              <!--------------------- Gaurav End the code ------------------------------>


              <input type="hidden" name="optnletter" value="N">
              <!--<div class="form-group">
                <label for="roleid" class="col-sm-9 control-label">I agree to receive the Annual report from the Institute in a softcopy, at my registered email ID, in place of physical copy</label>
                  <div class="col-sm-2">
                     
                       <input value="Y" name="optnletter" id="optnletter" checked="" type="radio"  <?php echo set_radio('optnletter', 'Y'); ?>>Yes
            <input value="N" name="optnletter" id="optnletter" type="radio"  <?php echo set_radio('optnletter', 'N'); ?>>No
                      <span class="error"><?php //echo form_error('optnletter');
                                          ?></span>
                    </div>
                </div>-->

              <div class="form-group">
                <label for="roleid" class="col-sm-1 control-label"> Note</label>
                <div class="col-sm-9"> 
                  <ol>
                  <li>Pl ensure all images are clear, visible and readable after uploading, if not do not submit and upload fresh set of images.</li>

                  <?php if (isset($examcode) && $examcode != 1046) { ?> 

                  <?php 
                  $file_size = '8KB';
                  if(isset($examcode) && $examcode == 101 || $examcode == 1046 || $examcode == 1047){
                    $file_size = '100KB';
                  }
                  if($examcode != 101 && $examcode != 1046 && $examcode != 1047){ ?>
                  <li>Images format should be in JPG 8bit and size should be minimum 8KB and maximum 50KB.</li>
                <?php } ?>
                  <li>Image Dimension of Photograph should be 100(Width) * 120(Height) Pixel only</li>
                  <li>Image Dimension of Signature should be 140(Width) * 60(Height) Pixel only</li>
                  <li>Image Dimension of ID Proof should be 400(Width) * 420(Height) Pixel only. Size should be minimum <?php echo $file_size; ?> and maximum 300KB.</li>

                  <?php } ?>

                  <ol>
                </div>
              </div>
            </div>
          </div>
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Exam Details:</h3>
            </div>
            <input type='hidden' id="hdnExamCode" maxlength="20" size="20" name="hdnExamCode" value="<?php echo base64_decode($this->input->get('ExId')); ?>" />
            <input type='hidden' name='exid' id='exid' value="<?php echo $this->input->get('ExId'); ?>">
            <!--  <input type='hidden' name='mtype' id='mtype' value="<?php //echo $this->input->get('Mtype');
                                                                      ?>">-->
            <input type='hidden' name='mtype' id='mtype' value="NM">
            <input type='hidden' name='memtype' id='memtype' value="<?php echo base64_decode($this->input->get('Mtype')); ?>">
            <input id="eprid" name="eprid" type="hidden" value="<?php echo $examinfo[0]['exam_period']; ?>">
            <input type="hidden" value="" name="rrsub" id="rrsub" />
            <input id="excd" name="excd" type="hidden" value="<?php echo base64_decode($this->input->get('ExId')); ?>">
            <input id="exname" name="exname" type="hidden" value=" <?php echo $examinfo[0]['description']; ?>">
            <input id="fee" name="fee" type="hidden" value="">
            <input id="education_type" name="education_type" type="hidden" value="">
            <?php $grp_code = 'B1_1'; ?>
            <input id="grp_code" name="grp_code" type="hidden" value="<?php echo trim($grp_code); ?>">
            <div class="box-body">
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Name</label>
                <div class="col-sm-5 "> <?php echo $examinfo[0]['description']; ?>
                  <div id="error_dob" class="error"></div>
                  <br>
                  <div id="error_dob_size" class="error"></div>
                  <span class="dob_proof_text" style="display:none;"></span> <span class="error">
                    <?php //echo form_error('idproofphoto');
                    ?>
                  </span>
                </div>
              </div>



              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>
                <div class="col-sm-5 " id="html_fee_id">
                  <div style="color:#F00">select center first</div>
                  <?php //echo $examinfo[0]['fee_amount'];
                  ?>
                  <div id="error_dob" class="error"></div>
                  <br>
                  <div id="error_dob_size" class="error"></div>
                  <span class="dob_proof_text" style="display:none;"></span> <span class="error">
                    <?php //echo form_error('idproofphoto');
                    ?>
                  </span>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Period</label>
                <div class="col-sm-5 ">
                  <?php

                  //$month = date('Y')."-".substr($examinfo[0]['exam_month'],4)."-".date('d');
                  $month = date('Y') . "-" . substr($examinfo[0]['exam_month'], 4);
                  echo date('F', strtotime($month)) . "-" . substr($examinfo[0]['exam_month'], 0, -2);
                  ?>
                  <div id="error_dob" class="error"></div>
                  <br>
                  <div id="error_dob_size" class="error"></div>
                  <span class="dob_proof_text" style="display:none;"></span> <span class="error">
                    <?php //echo form_error('idproofphoto');
                    ?>
                  </span>
                </div>
              </div>

              <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">GSTIN No.&nbsp;</label>
                  <div class="col-sm-5 ">
                         <input type="text" class="form-control" id="gstin_no" name="gstin_no" placeholder="GSTIN No." value="<?php echo set_value('gstin_no'); ?>"  data-parsley-minlength="15" data-parsley-maxlength="15" data-parsley-trigger-after-failure="focusout">
                     <div id="error_dob" class="error"></div>
                     <div id="error_dob_size" class="error"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');
                                          ?></span>
                    </div>
                </div>-->

              <? if (count($compulsory_subjects) > 0 && (base64_decode($this->input->get('ExId')) == 101 || base64_decode($this->input->get('ExId')) == 1046 || base64_decode($this->input->get('ExId')) == 1047)) { ?>
                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label"><span style="font-weight: bold;background-color: #ffb300;padding: 5px;" class="blink-highlight">Examination Date</span></label>
                  <div class="col-sm-5 ">
                    <span style="font-weight: bold;background-color: #ffb300;padding: 1px 9px 9px 9px;"><?php echo  date('d-M-Y', strtotime($compulsory_subjects[0]['exam_date'])); ?></span>
                  </div>
                </div>
              <?php } ?>

              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Medium *</label>
                <div class="col-sm-3">
                  <select name="medium" id="medium" class="form-control" required>
                    <option value="">Select</option>
                    <?php if (count($medium) > 0) {
                      foreach ($medium as $mrow) { ?>
                        <option value="<?php echo $mrow['medium_code'] ?>" <?php echo  set_select('medium', $mrow['medium_code']); ?>><?php echo $mrow['medium_description'] ?></option>
                    <?php }
                    } ?>
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
                  $this->db->where('exam_code',$examinfo['0']['exam_code']);
          $sql = $this->master_model->getRecords('exam_master','','elearning_flag'); 
          if($sql[0]['elearning_flag'] == 'Y'){
        ?>
                
                <div class="form-group" style="display:none;">
                    <label for="roleid" class="col-sm-3 control-label">Do you want to apply for elearning ? </label> 
                        <div class="col-sm-3">
                       
                           <input type="radio" name="" id="elearning_flag_Y" value="Y" >YES
               <input type="radio" name="" id="elearning_flag_N" value="N" checked="checked">NO
               
                        </div>
                 </div>
                 <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Do you want to select eLearning</label>
                        <div class="col-sm-3">
                        <input type="radio" name="elearning_flag" id="subject_elearning_flag_Y" value="Y" checked="checked">YES
               <input type="radio" name="elearning_flag" id="subject_elearning_flag_N" value="N" >NO
                        </div>
                 </div>
                 <?php foreach($compulsory_subjects as $el_subject){?>
                 
                   <div class="form-group show_el_subject" >
                    <label for="roleid" class="col-sm-3 control-label"><?php echo $el_subject['subject_description']?><span style="color:#F00">*</span></label>
                        <div class="col-sm-3">
                        <input type="checkbox" name="el_subject[<?php echo $el_subject['subject_code']?>]" value="Y" checked="checked" class="el_sub_prop" />
                        </div>
                 </div>
                 <?php } }else{?>
                 <input type="hidden" name="elearning_flag" id="elearning_flag_Y" value="N" >
         <input type="hidden" name="elearning_flag" id="elearning_flag_N" value="N" >
                 <?php }?>
              

                 <!-- skipadmit -->
                <?php
            if(in_array(base64_decode($this->input->get('ExId')),$this->config->item('skippedAdmitCardForExams'))) {    
            ?> 

                <!-- skipadmit -->
                <div class="col-md-12">&nbsp;</div>
                    
                <div class=" form-group  ">
                  <div class="col-md-1"></div>
                  <label for="roleid" style="text-align: center;" class="control-label col-md-4"><b>Eligible Subjects</b></span></label>
                  <label for="roleid" style="text-align: center;" class="control-label col-md-4"><b>Exam Date</b></span></label>
                </div>
                <!-- end skipadmit -->

                <?php 
                    foreach($compulsory_subjects as $subject)
                    { 
                    
                      ?>
                      <div class=" form-group  ">
                      <div class="col-md-1">&nbsp;</div>
                        <label for="roleid" style="text-align: center;" class="control-label col-md-4"><?php echo $subject['subject_description']?></span></label>
                        <label for="roleid" style="text-align: center;" class="control-label col-md-4"><?php echo date('d-m-Y',strtotime($subject['exam_date'])) ?></span></label>
                      </div>
                        <?php 
                      

                    }
                    ?>
                    <?php 
                    } ?> 
                    <!-- end skippadmit -->


              <?php 
         if(count($compulsory_subjects) > 0 && (base64_decode($this->input->get('ExId'))!=101 && base64_decode($this->input->get('ExId'))!=1046 && base64_decode($this->input->get('ExId'))!=1047))
         {
             $i=1;
             foreach($compulsory_subjects as $subject)
             {?>
              <div class="form-group" <?php if(in_array(base64_decode($this->input->get('ExId')),$this->config->item('skippedAdmitCardForExams'))) echo'style="display:none;"'?>>
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
                  <input type="text" name="txtCenterCode" id="txtCenterCode" class="form-control pull-right" readonly="readonly">
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Mode *</label>
                <!--<div class="col-sm-2">
                      <input type="radio" class="minimal " id="optsex1"   name="optmode" value="ON" <?php //echo set_radio('optmode', 'ON'); 
                                                                                                    ?>>
                     Online
                   <input type="radio" class="minimal" id="optsex2"   name="optmode"  checked="checked"  value="OF" <?php //echo set_radio('optmode', 'OF'); 
                                                                                                                    ?>>
                     Offline
                    </div>-->

                <div name="optmode1" id="optmode1" style="display: none;">Exam will be in ONLINE mode only, Read Important Instructions on the website.</div>
                <div name="optmode2" id="optmode2" style="display: none;">Exam will be in OFFLINE mode only, Read Important Instructions on the website.</div>
                <input id="optmode" name="optmode" value="" type="hidden">
              </div>
            </div>
          </div>

          <!-- Benchmark Disability Code Start -->
          <div class="box-header with-border header_blue">
            <h3 class="box-title">Disability</h3>
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Person with Benchmark Disability</label>
            <div class="col-sm-2">
              <input value="Y" name="benchmark_disability" id="benchmark_disability" type="radio" <?php echo set_radio('benchmark_disability', 'Y'); ?> class="benchmark_disability_y">
              Yes
              <input value="N" name="benchmark_disability" id="benchmark_disability" type="radio" <?php echo set_radio('benchmark_disability', 'N'); ?> class="benchmark_disability_n" checked="checked">
              No <span class="error"></span>
            </div>
          </div>

          <div id="benchmark_disability_div" style="display:none;">

            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Visually impaired</label>
              <div class="col-sm-2">
                <input value="Y" name="visually_impaired" id="visually_impaired" type="radio" <?php echo set_radio('visually_impaired', 'Y'); ?> class="visually_impaired_y">
                Yes
                <input value="N" name="visually_impaired" id="visually_impaired" type="radio" <?php echo set_radio('visually_impaired', 'N'); ?> class="visually_impaired_n" checked="checked">
                No <span class="error"></span>
              </div>
            </div>
            <div class="form-group" id="vis_imp_cert_div" style="display:none;">
              <label for="roleid" class="col-sm-4 control-label">Attach scan copy of PWD certificate <span style="color:#F00">*</span></label>
              <div class="col-sm-5">
                <input type="file" name="scanned_vis_imp_cert" id="scanned_vis_imp_cert" required style="word-wrap: break-word;width: 100%;">
                <input type="hidden" id="hidden_vis_imp_cert" name="hidden_vis_imp_cert">
                <div id="error_vis_imp_cert" class="error"></div>
                <br>
                <div id="error_vis_imp_cert_size" class="error"></div>
                <span class="vis_imp_cert_text" style="display:none;"></span> <span class="error"> </span>
              </div>
            </div>

            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Orthopedically handicapped</label>
              <div class="col-sm-2">
                <input value="Y" name="orthopedically_handicapped" id="orthopedically_handicapped" type="radio" <?php echo set_radio('orthopedically_handicapped', 'Y'); ?> class="orthopedically_handicapped_y">
                Yes
                <input value="N" name="orthopedically_handicapped" id="orthopedically_handicapped" type="radio" <?php echo set_radio('orthopedically_handicapped', 'N'); ?> class="orthopedically_handicapped_n" checked="checked">
                No <span class="error"></span>
              </div>
            </div>
            <div class="form-group" id="orth_han_cert_div" style="display:none;">
              <label for="roleid" class="col-sm-4 control-label">Attach scan copy of PWD certificate <span style="color:#F00">*</span></label>
              <div class="col-sm-5">
                <input type="file" name="scanned_orth_han_cert" id="scanned_orth_han_cert" required style="word-wrap: break-word;width: 100%;">
                <input type="hidden" id="hidden_orth_han_cert" name="hidden_orth_han_cert">
                <div id="error_orth_han_cert" class="error"></div>
                <br>
                <div id="error_orth_han_cert_size" class="error"></div>
                <span class="orth_han_cert_text" style="display:none;"></span> <span class="error"> </span>
              </div>
            </div>

            <div class="form-group">
              <label for="roleid" class="col-sm-4 control-label">Cerebral palsy</label>
              <div class="col-sm-2">
                <input value="Y" name="cerebral_palsy" id="cerebral_palsy" type="radio" <?php echo set_radio('cerebral_palsy', 'Y'); ?> class="cerebral_palsy_y">
                Yes
                <input value="N" name="cerebral_palsy" id="cerebral_palsy" type="radio" <?php echo set_radio('cerebral_palsy', 'N'); ?> class="cerebral_palsy_n" checked="checked">
                No <span class="error"></span>
              </div>
            </div>
            <div class="form-group" id="cer_palsy_cert_div" style="display:none;">
              <label for="roleid" class="col-sm-4 control-label">Attach scan copy of PWD certificate <span style="color:#F00">*</span></label>
              <div class="col-sm-5">
                <input type="file" name="scanned_cer_palsy_cert" id="scanned_cer_palsy_cert" required style="word-wrap: break-word;width: 100%;">
                <input type="hidden" id="hidden_cer_palsy_cert" name="hidden_cer_palsy_cert">
                <div id="error_cer_palsy_cert" class="error"></div>
                <br>
                <div id="error_cer_palsy_cert_size" class="error"></div>
                <span class="cer_palsy_cert_text" style="display:none;"></span> <span class="error"> </span>
              </div>
            </div>
            <br />
            
          </div>
          <div class="box-header with-border header_blue">
            <h3 class="box-title">&nbsp;</h3>
          </div>
          <!-- Benchmark Disability Code End -->

          <div class="form-group scribe_div" style="display:none;">
            <label for="roleid" class="col-sm-3 control-label">Scribe required?</label>
            <div class="col-sm-3">
              <input type="checkbox" name="scribe_flag" id="scribe_flag" value="Y">
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
                    <li>I declare that I have submitted my Aadhar Card Number and Proof of my Identity: Driving License/ID Card issued by Employer / Pan Card / Passport as specified above. </li>
                    

                    <li>I hereby declare that all the information given in this application is true, complete and correct. I understand that in the event of any information being found false or incorrect subsequent to allotment of registration no., my candidature is liable to be cancelled / terminated. </li>
                    <li>I further declare that I have not at any time been a member of the Institute/applied earlier for membership of the Institute. </li>
                    
                    <li>I hereby declare that I have not been debarred from appearing for any examination of the Institute.</li>
                    <li>I confirm having read and understood the rules and regulations of the Institute and I hereby agree to abide by the same. In case I am desirous of pursuing any legal proceedings against the Institute I hereby agree that such legal proceedings shall be initiated only in courts in Mumbai, New Delhi, Kolkata and Chennai under whose jurisdiction the Zonal Office/s of the Institute is / are situated and my application thereto pertains and not in any other court. </li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">
                <input name="declaration1" value="1" type="checkbox" required="required" <?php if (set_value('declaration1')) {
                                                                                            echo set_radio('declaration1', '1');
                                                                                          } ?>>
                &nbsp; I Accept
              </h3>
            </div>
            <div class="form-group">
              <label for="roleid" class="col-sm-3 control-label">Security Code *</label>
              <div class="col-sm-3">
                <input type="text" name="code" id="code" required class="form-control ">
                <span class="error" id="non_mem_captchaid" style="color:#B94A48;"></span>
              </div>

              <div class="col-sm-4">
                <div id="captcha_img"><?php echo $image; ?></div>
                <a href="javascript:void(0);" id="new_captcha" class="forget">Change Image</a>
              </div>
            </div>
            <div class="box-footer">
              <div class="col-sm-10 col-sm-offset-2"> <a href="javascript:void(0);" class="btn btn-info" onclick="javascript:return non_mem_checkform();" id="preview">Preview and Proceed for Payment</a>&nbsp;&nbsp;
                <!-- <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Save">-->
                <button type="reset" class="btn btn-default" name="btnReset" id="btnReset">Reset</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <input type="hidden" id="email_verify_status" name="email_verify_status" value="<?php echo $email_verify_status; ?>">
      <input type="hidden" id="mobile_verify_status" name="mobile_verify_status" value="<?php echo $mobile_verify_status; ?>">
      
      <div class="modal fade" id="confirm" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
              <p style="color:#F00"> <strong>VERY IMPORTANT</strong><br>
                I confirm that the Photo, Signature & Id proof images uploaded belongs to me and they are clear and readable. <br />
                <br />
                We find that Aadhaar Number is not mentioned in your membership account. You are requested to enter Aadhaar number in your membership account immediately.

                Aadhaar number can be updated to your existing membership account through edit profile option by entering your membership number and profile password. <br />
                In case, if you do not have Aadhaar number, request you to obtain it on or before December 31, 2017 and update the Aadhaar number in your membership profile.
              </p>
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
<div class="modal fade" id="myModalscrib" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center><strong>
            <h4 class="modal-title" id="myModalLabel" style="color:#F00"> Important Notice</h4>
          </strong></center>
      </div>
      <div class="modal-body">
        Dear Candidate,<br><br>
    <p>You have opted for the services of a scribe for the above mentioned examination under <strong>Remote Proctored mode.</strong> Please note the following - </p> 
    <ul>  
    <li>Candidates desirous of availing scribe facility need to apply online on the IIBF website by clicking on <u>Apply Now> Apply for scribe.</u></li>
    <li>Only the candidates who have applied Online & obtained prior approval for scribe from IIBF will be allowed to appear with the scribe on the day of the examination.</li> 
    <li>Candidates are advised to apply online for scribe well in advance, not later than 3 days before the examination</li>
    <li>Please ensure that the scribe fulfils the eligibility criteria as prescribed in the rules/guidelines before applying</li>
    <li>Please note that, in case, it is found later that the scribe does not fulfil the eligibility criteria, candidature of the applicant will stand cancelled</li>
    <li>Please read the rules/guidelines for availing the facility of scribe carefully before applying for Scribe</li>
    <li>Your application for scribe will be scrutinized and an email will be sent 1-2 days before the exam date, mentioning the status of acceptance of scribe</li>
    <li>You will be required to produce the print out of permission granted, required documents along with the Admit Letter to the test conducting authority (proctor)</li>
    <li>For the Scribe Guidelines Click Here -</li> 
    </ul> 
    <p style="color:#F00"><a href="https://iibf.esdsconnect.com/uploads/Scribe_Guideline_2024.pdf" target="_blank">https://iibf.esdsconnect.com/uploads/Scribe_Guideline_2024.pdf</a><br> 
     </p>
    Regards,<br>
    IIBF Team.<br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>

      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var examCode = "<?php echo $examcode; ?>";
  var memberType = "<?php echo $member_type; ?>";
  // alert(examCode+memberType);
</script>
<link href="<?php echo base_url(); ?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
<script src="<?php echo base_url(); ?>js/validation.js"></script>
<script src="<?php echo base_url(); ?>js/disability.js?<?php echo time(); ?>"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  $(document).ready(function() {
    $('#institutionworking').select2();
  });
</script>

<script type="text/javascript">
  // var flag=$('#usersAddForm').parsley('validate');
</script>
<script>
  //START : AUTO FETCH FIRST, MIDDLE & LAST NAME FOR DISPLAY UNDER FULL NAME FIELD
  ///ADDED ON 12-07-2023 BY SM
  function createfullname() {
    firstname = $.trim($("#firstname").val()).toUpperCase();
    middlename = ' ' + $.trim($("#middlename").val()).toUpperCase();
    lastname = ' ' + $.trim($("#lastname").val()).toUpperCase();
    if ($.trim(firstname) != "" || $.trim(middlename) != "" || $.trim(lastname) != "") {
      $("#nameoncard").val(firstname + middlename + lastname);
    } else {
      $("#nameoncard").val("")
    }
  } //END : AUTO FETCH FIRST, MIDDLE & LAST NAME FOR DISPLAY UNDER FULL NAME FIELD

  $(document).ready(function() {
    var cCode = $('#selCenterName').val();
    if (cCode != '') {
      document.getElementById('txtCenterCode').value = cCode;
      var examType = document.getElementById('extype').value;
      var examCode = document.getElementById('examcode').value;
      var temp = document.getElementById("selCenterName").selectedIndex;
      selected_month = document.getElementById("selCenterName").options[temp].className;
      if (selected_month == 'ON') {
        if (document.getElementById("optmode1")) {
          document.getElementById("optmode1").style.display = "block";
          document.getElementById('optmode').value = 'ON';
        }

        if (document.getElementById("optmode2")) {
          document.getElementById("optmode2").style.display = "none";
        }

      } else if (selected_month == 'OF') {
        if (document.getElementById("optmode2")) {
          document.getElementById("optmode2").style.display = "block";
          document.getElementById('optmode').value = 'OF';
        }
        if (document.getElementById("optmode1")) {
          document.getElementById("optmode1").style.display = "none";
        }
      } else {
        if (document.getElementById("optmode1")) {
          document.getElementById("optmode1").style.display = "none";
        }
        if (document.getElementById("optmode2")) {
          document.getElementById("optmode2").style.display = "none";
        }
      }

    }
    
  // Start Pooja Mane ( ) code for email verification 
  $('.verify-otp').click(function() 
  {
    var otp         = $('#otp').val();
    var verify_type = $(this).attr('data-verify-type');
    var email     = $('#email').val();
      var type      = 'verify_otp';
      
      var data = {};
      data.email     = email;
      data.otp       = otp;
      data.verify_type = verify_type;
    if (otp != '' && otp != undefined) 
    {
      send_verify_otp(type,data,this)       
    } else {
      alert('Please enter the OTP.');
    } 
  })

  $('.verify-otp-mobile').click(function() 
  {
    var otp         = $('#otp_mobile').val();
    var verify_type = $(this).attr('data-verify-type');
    var mobile    = $('#mobile').val();
      var type      = 'verify_otp';
      
      var data = {};
      data.mobile    = mobile;
      data.otp       = otp;
      data.verify_type = verify_type;
    if (otp != '' && otp != undefined) 
    {
      send_verify_otp_mobile(type,data,this)        
    } else {
      alert('Please enter the OTP.');
    } 
  })

  $('#reset_btn').click(function() {
    $('#email').attr('readonly',false);
    $('#email').val('');
    $('#send_otp_btn').show();
    $('#reset_btn').hide();
    $('.verify-otp-section').hide();
    emailVerify = false;
    $('#email_verify_status').val('no');
    $('#otp').val('');
  })

  $('#reset_btn_mobile').click(function() {
    $('#mobile').attr('readonly',false);
    $('#mobile').val('');
    $('#send_otp_btn_mobile').show();
    $('#reset_btn_mobile').hide();
    $('.verify-otp-section-mobile').hide();
    mobileVerify = false;
    $('#mobile_verify_status').val('no');
    $('#otp_mobile').val('');
  })

  function send_verify_otp(type,data,selector) {
    $.ajax({
      type: 'POST',
      url: site_url + 'Nonreg/send_otp/',
      data : {'email':data.email,'type':type,'otp':data.otp,'verify_type':data.verify_type},
      beforeSend: function(xhr) {
          $(selector).attr('disabled',true).text('Processing..')  
        },
      async: true,
      success: function(otp_response) {
        var json_otp_response = JSON.parse(otp_response);
        if (json_otp_response.status) {
          if (type == 'send_otp') {
            $('#send_otp_btn').hide();
            $('#send_otp_btn').attr('disabled',false).text('Get OTP')
            $('.verify-otp-section').show();
            $('#reset_btn').show(); 
          } else if (type == 'resend_otp') {
            $(selector).attr('disabled',false).text('Resend OTP')
            $('.verify-otp-section').show();
          } else if (type == 'verify_otp') {
            $(selector).attr('disabled',false).text('Verify OTP')
            $('.verify-otp-section').hide();
            emailVerify = true;
            $('#email_verify_status').val('yes');
          }

          $('.email-id').removeClass('parsley-error');
          $('.email-id').addClass('parsley-success');
          $('#email').attr('readonly',true);
          
          alert(json_otp_response.msg);
        } else {
          if (type == 'send_otp') {
            $(selector).attr('disabled',false).text('Get OTP')
          } else if (type == 'resend_otp') { 
            $(selector).attr('disabled',false).text('Resend OTP')
          } else if (type == 'verify_otp') {
            $(selector).attr('disabled',false).text('Verify OTP')
          } 
          alert(json_otp_response.msg); 
        }
        $('#otp').val('');
      }
    });
  }

  function send_verify_otp_mobile(type,data,selector) {
    $.ajax({
      type : 'POST',
      url  : site_url + 'Nonreg/send_otp_mobile/',
      data : {'mobile':data.mobile,'type':type,'otp':data.otp,'verify_type':data.verify_type},
      beforeSend: function(xhr) {
          $(selector).attr('disabled',true).text('Processing..')  
        },
      async: true,
      success: function(otp_response) {
        var json_otp_response = JSON.parse(otp_response);
        if (json_otp_response.status) {
          if (type == 'send_otp') {
            $('#send_otp_btn_mobile').hide();
            $('#send_otp_btn_mobile').attr('disabled',false).text('Get OTP')
            $('.verify-otp-section-mobile').show();
            $('#reset_btn_mobile').show();  
          } else if (type == 'resend_otp') {
            $(selector).attr('disabled',false).text('Resend OTP')
            $('.verify-otp-section-mobile').show();
          } else if (type == 'verify_otp') {
            $(selector).attr('disabled',false).text('Verify OTP')
            $('.verify-otp-section-mobile').hide();
            emailVerify = true;
            $('#mobile_verify_status').val('yes');
          }

          $('.mobile').removeClass('parsley-error');
          $('.mobile').addClass('parsley-success');
          $('#mobile').attr('readonly',true);
          
          alert(json_otp_response.msg);
        } else {
          if (type == 'send_otp') {
            $(selector).attr('disabled',false).text('Get OTP')
          } else if (type == 'resend_otp') { 
            $(selector).attr('disabled',false).text('Resend OTP')
          } else if (type == 'verify_otp') {
            $(selector).attr('disabled',false).text('Verify OTP')
          } 
          alert(json_otp_response.msg); 
        }
        $('#otp_mobile').val('');
      }
    });
  }
  var sessionEmail = '<?php echo $_POST['verified_email_val']; ?>';
  $('.send-otp').click(function() {
      var email = $('#email').val();
      var type  = $(this).attr('data-type');
      var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/; // Regular expression for email format
      var stopExecution = false;
      var otpButton = document.getElementById('send_otp_btn');
      var reset_btn = document.getElementById('reset_btn');

      if(sessionEmail !='' && email !='' && email === sessionEmail) 
      {
          alert("This email has already been verified by you previously. You do not need to verify it again.");
          otpButton.style.display = 'none';
          reset_btn.style.display = 'block';
          stopExecution = true;
      }

      if (stopExecution) {
        return; // Exit the function if the flag is true
      }
      
      if (type == 'resend_otp') {
        $('#otp').val('');
      }
      var data = {};
      data.email       = email;
      data.otp       = '';
      data.verify_type = '';
        
      if (email.trim() != '') {
          if (emailRegex.test(email)) {
              send_verify_otp(type,data,this)
          } else {
            $('.email-id').addClass('parsley-error');
              $('#email').focus();
              alert('Please enter a valid email address.');
          }
      } else {
        $('.email-id').addClass('parsley-error');
          $('#email').focus();
          alert('Please enter email id first.');
      }
  })

  var sessionMobile = '<?php echo $_POST['verified_mobile_val']; ?>';
  $('.send-otp-mobile').click(function() {
      var mobile = $('#mobile').val();
      var type  = $(this).attr('data-type');
      var stopExecution = false;
      var otpButton = document.getElementById('send_otp_btn_mobile');
      var reset_btn = document.getElementById('reset_btn_mobile');

      if(sessionMobile !='' && mobile !='' && mobile == sessionMobile) 
      {
          alert("This mobile has already been verified by you previously. You do not need to verify it again.");
          otpButton.style.display = 'none';
          reset_btn.style.display = 'block';
          stopExecution = true;
      }

      if (stopExecution) {
        return; // Exit the function if the flag is true
      }
      
      // if($.isNumeric(mobile) && mobile.length === 10){
        //     $("#result").text("Valid mobile number");
        // } else {
        //     $("#result").text("Invalid mobile number");
        // }

      if (type == 'resend_otp') {
        $('#otp_mobile').val('');
      }
      var data = {};
      data.mobile      = mobile;
      data.otp       = '';
      data.verify_type = '';
        
      if (mobile.trim() != '') {
          if (mobile.length == 10 && $.isNumeric(mobile) && !mobile.includes('.')) {
              send_verify_otp_mobile(type,data,this)
          } else {
            if ( !$.isNumeric(mobile) || mobile.includes('.')) {
              $('.mobile').addClass('parsley-error');
                $('#mobile').focus();
                alert('Characters and special characters not allowed.');
            } else {
              $('.mobile').addClass('parsley-error');
                $('#mobile').focus();
                alert('Please enter a atleast 10 digit mobile no.');
            }
          }
      } else {
        $('.mobile').addClass('parsley-error');
          $('#mobile').focus();
          alert('Please enter mobile no. first.');
      }
  })
  // END Pooja Mane ( ) code for email verification



    $(function() {
      var examCode_dob = document.getElementById('examcode').value;
      var maxAge_dob = 71;
      if (examCode_dob == 101 || examCode_dob == 1046 || examCode_dob == 1047) {
        maxAge_dob = 69;
      }
      $("#dob1").dateDropdowns({
        submitFieldName: 'dob1',
        minAge: 0,
        maxAge: maxAge_dob
      });
      // Set all hidden fields to type text for the demo
      //$('input[type="hidden"]').attr('type', 'text').attr('readonly', 'readonly');
    });

    $("#dob1").change(function() {
      var sel_dob = $("#dob1").val();
      if (sel_dob != '') {
        var dob_arr = sel_dob.split('-');
        if (dob_arr.length == 3) {
          chkage(dob_arr[2], dob_arr[1], dob_arr[0]);
        } else {
          alert('Select valid date');
        }
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
            if(exam_code_id == '101' || exam_code_id == '1046' || exam_code_id == '1047' || exam_code_id == '991' || exam_code_id == '997'){
              CompareMaxDate(doj_arr[2], doj_arr[1], doj_arr[0]);
            }else{
              CompareToday(doj_arr[2], doj_arr[1], doj_arr[0]);
            }
            
          } else {
            alert('Select valid date');
          }
        }
      });
    })

    $("body").on("contextmenu", function(e) {
      return false;
    });

    $('#male').prop("checked", true);

    /*$('#eduqual1').show();
    $('#UG').show();
    $('#eduqual').hide();
    $('#edu').hide();*/

    var selEducation = $("#education_type").val();
    if (selEducation != '') {
      changedu(selEducation);
    }


    $(document).keydown(function(event) {
      if (event.ctrlKey == true && (event.which == '67' || event.which == '86')) {
        if (event.which == '67') {
          alert('Key combination CTRL + C has been disabled.');
        }
        if (event.which == '86') {
          alert('Key combination CTRL + V has been disabled.');
        }
        event.preventDefault();
      }
    });
    //priyanka d - 08-feb-23 >> changeFeeFromElarningY >> this made common to load code after page load >> did this because after come back from preview page, values was not prepopulated
    if ($("#subject_elearning_flag_Y").length > 0 && $('#subject_elearning_flag_Y').is(':checked')) {
      changeFeeFromElarningY();
    }
    $("#subject_elearning_flag_Y").click(function() {
      changeFeeFromElarningY();
    });

    //priyanka d- 02-feb-23 >> start to get proper fee when select-deselect e-learning


     function changeFeeFromElarningY() {
        $(".loading").show();
        $(".show_el_subject").show();
        $(".el_sub_prop").prop('checked', true);
        
        var el_subject_cnt =  $('.show_el_subject :input[type="checkbox"]:checked').length;
        var datastring_1='subject_cnt='+el_subject_cnt;
        
        $.ajax({
            url:site_url+'nonreg/set_nonmem_elsub_cnt',
            data: datastring_1,
            type:'POST',
            async: false,
            success: function(data) {
            }
          });
        
        
        var cCode =  document.getElementById('txtCenterCode').value;
        var examType = document.getElementById('extype').value;
        var examCode = document.getElementById('examcode').value;
        var temp = document.getElementById("selCenterName").selectedIndex;
        var selected_month = document.getElementById("selCenterName").options[temp].className;
        var eprid = document.getElementById('eprid').value;
        var excd = document.getElementById('excd').value;
        var grp_code = document.getElementById('grp_code').value;
        var extype= document.getElementById('extype').value;
        var mtype= document.getElementById('mtype').value;
        var Eval = 'N'; 
        //alert($("#elearning_flag_Y").val());
        //alert(document.getElementById('elearning_flag_Y').checked);
        if(document.getElementById('subject_elearning_flag_Y').checked){
          var Eval = document.getElementById('subject_elearning_flag_Y').value;
          //alert(Eval);
        }

        if(document.getElementById('subject_elearning_flag_N').checked){
          var Eval = document.getElementById('subject_elearning_flag_N').value;
        }
        //alert(Eval);
        var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+excd+'&grp_code='+grp_code+'&mtype='+mtype+'&elearning_flag='+Eval;;
        
        

        $.ajax({
            url:site_url+'Fee/getFee/',
            data: datastring,
            type:'POST',
            async: false,
            success: function(data) {
            if(data){
              document.getElementById('fee').value = data ;
              document.getElementById('html_fee_id').innerHTML =data;
            }
          }
        });
        
        $(".loading").hide();
      }

    if ($("#subject_elearning_flag_N").length > 0 && $('#subject_elearning_flag_N').is(':checked')) {
      changeFeeFromElarningN();
    }
    $("#subject_elearning_flag_N").click(function() {
      changeFeeFromElarningN();
    });

    function changeFeeFromElarningN() {
      $(".loading").show();
      $(".show_el_subject").hide();
      $(".el_sub_prop").prop('checked', false);

      var el_subject_cnt = 0;

      var datastring_1 = 'subject_cnt=' + el_subject_cnt;

      $.ajax({
        url: site_url + 'nonreg/set_nonmem_elsub_cnt',
        data: datastring_1,
        type: 'POST',
        async: false,
        success: function(data) {}
      });


      var cCode = document.getElementById('txtCenterCode').value;
      var examType = document.getElementById('extype').value;
      var examCode = document.getElementById('examcode').value;
      var temp = document.getElementById("selCenterName").selectedIndex;
      var selected_month = document.getElementById("selCenterName").options[temp].className;
      var eprid = document.getElementById('eprid').value;
      var excd = document.getElementById('excd').value;
      var grp_code = document.getElementById('grp_code').value;
      var extype = document.getElementById('extype').value;
      var mtype = document.getElementById('mtype').value;
      var Eval = 'N';

      if (document.getElementById('subject_elearning_flag_Y').checked) {
        var Eval = document.getElementById('subject_elearning_flag_Y').value;
      }

      if (document.getElementById('subject_elearning_flag_N').checked) {
        var Eval = document.getElementById('subject_elearning_flag_N').value;
      }
        //alert(Eval);
      var datastring = 'centerCode=' + cCode + '&eprid=' + eprid + '&excd=' + excd + '&grp_code=' + grp_code + '&mtype=' + mtype + '&elearning_flag=' + Eval;;



      $.ajax({
        url: site_url + 'Fee/getFee/',
        data: datastring,
        type: 'POST',
        async: false,
        success: function(data) {
          if (data) {
            document.getElementById('fee').value = data;
            document.getElementById('html_fee_id').innerHTML = data;
          }
        }
      });
      $(".loading").hide();
    }
    //priyanka d - 08-feb-23 >> el_sub_prop >> this made common to load code after page load >> did this because after come back from preview page, values was not prepopulated


    $(".el_sub_prop").click(function() {
      el_sub_prop();
    });

    function el_sub_prop() {
      $(".loading").show();
      var el_subject_cnt = $('.show_el_subject :input[type="checkbox"]:checked').length;
      var datastring_1 = 'subject_cnt=' + el_subject_cnt;

      $.ajax({
        url: site_url + 'nonreg/set_nonmem_elsub_cnt',
        data: datastring_1,
        type: 'POST',
        async: false,
        success: function(data) {}
      });


      var cCode = document.getElementById('txtCenterCode').value;
      var examType = document.getElementById('extype').value;
      var examCode = document.getElementById('examcode').value;
      var temp = document.getElementById("selCenterName").selectedIndex;
      var selected_month = document.getElementById("selCenterName").options[temp].className;
      var eprid = document.getElementById('eprid').value;
      var excd = document.getElementById('excd').value;
      var grp_code = document.getElementById('grp_code').value;
      var extype = document.getElementById('extype').value;
      var mtype = document.getElementById('mtype').value;
      var Eval = 'N';

      if (document.getElementById('subject_elearning_flag_Y').checked) {
        var Eval = document.getElementById('subject_elearning_flag_Y').value;
      }

      if (document.getElementById('subject_elearning_flag_N').checked) {
        var Eval = document.getElementById('subject_elearning_flag_N').value;
      }

      var datastring = 'centerCode=' + cCode + '&eprid=' + eprid + '&excd=' + excd + '&grp_code=' + grp_code + '&mtype=' + mtype + '&elearning_flag=' + Eval;;



      $.ajax({
        url: site_url + 'Fee/getFee/',
        data: datastring,
        type: 'POST',
        async: false,
        success: function(data) {
          if (data) {
            document.getElementById('fee').value = data;
            document.getElementById('html_fee_id').innerHTML = data;
          }
        }
      });
      $(".loading").hide();
    }
    /*
  $("#elearning_flag_Y").click(function(){
    var cCode =  document.getElementById('txtCenterCode').value;
    var examType = document.getElementById('extype').value;
    var examCode = document.getElementById('examcode').value;
    var temp = document.getElementById("selCenterName").selectedIndex;
    var selected_month = document.getElementById("selCenterName").options[temp].className;
    var eprid = document.getElementById('eprid').value;
    var excd = document.getElementById('excd').value;
    var grp_code = document.getElementById('grp_code').value;
    var extype= document.getElementById('extype').value;
    var mtype= document.getElementById('mtype').value;
    
    if(document.getElementById('elearning_flag_Y').checked){
      var Eval = document.getElementById('elearning_flag_Y').value;
    }
    
    if(document.getElementById('elearning_flag_N').checked){
      var Eval = document.getElementById('elearning_flag_N').value;
    }
    
    if(cCode != ''){
      var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+excd+'&grp_code='+grp_code+'&mtype='+mtype+'&elearning_flag='+Eval;
        $.ajax({
            url:site_url+'Fee/getFee/',
            data: datastring,
            type:'POST',
            async: false,
            success: function(data) {
             if(data)
            {
              document.getElementById('fee').value = data ;
              document.getElementById('html_fee_id').innerHTML =data;
              //response = true;
            }
          }
        });
    }
  });
  
  $("#elearning_flag_N").click(function(){
    var cCode =  document.getElementById('txtCenterCode').value;
    var examType = document.getElementById('extype').value;
    var examCode = document.getElementById('examcode').value;
    var temp = document.getElementById("selCenterName").selectedIndex;
    var selected_month = document.getElementById("selCenterName").options[temp].className;
    var eprid = document.getElementById('eprid').value;
    var excd = document.getElementById('excd').value;
    var grp_code = document.getElementById('grp_code').value;
    var extype= document.getElementById('extype').value;
    var mtype= document.getElementById('mtype').value;
    
    if(document.getElementById('elearning_flag_Y').checked){
      var Eval = document.getElementById('elearning_flag_Y').value;
    }
    
    if(document.getElementById('elearning_flag_N').checked){
      var Eval = document.getElementById('elearning_flag_N').value;
    }
    
    if(cCode != ''){
      var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+excd+'&grp_code='+grp_code+'&mtype='+mtype+'&elearning_flag='+Eval;
        $.ajax({
            url:site_url+'Fee/getFee/',
            data: datastring,
            type:'POST',
            async: false,
            success: function(data) {
             if(data)
            {
              document.getElementById('fee').value = data ;
              document.getElementById('html_fee_id').innerHTML =data;
              //response = true;
            }
          }
        });
    }
  });
  */
  });

  function editUser(id, roleid, Name, Username, Email) {
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

  function changedu(dval) {

    $("#education_type").val(dval);
    var UGid = document.getElementById('UG');
    var GRid = document.getElementById('GR');
    var PGid = document.getElementById('PG');
    var EDUid = document.getElementById('edu');

    if (dval == 'U') {
      $('#eduqual1').attr('required', 'required');
      $('#eduqual2').removeAttr('required');
      $('#eduqual3').removeAttr('required');
      $('#eduqual').removeAttr('required');

      if (UGid != null) {
        //  alert('UG');
        document.getElementById('UG').style.display = "block";
      }
      if (GRid != null) {
        document.getElementById('GR').style.display = "none";
      }
      if (PGid != null) {
        document.getElementById('PG').style.display = "none";
      }
      if (EDUid != null) {
        document.getElementById('edu').style.display = "none";
      }
    } else if (dval == 'G') {
      $('#eduqual1').removeAttr('required');;
      $('#eduqual2').attr('required', 'required');
      $('#eduqual3').removeAttr('required');
      $('#eduqual').removeAttr('required');

      if (UGid != null) {
        document.getElementById('UG').style.display = "none";
      }
      if (GRid != null) {
        document.getElementById('GR').style.display = "block";
      }
      if (PGid != null) {
        document.getElementById('PG').style.display = "none";
      }
      if (EDUid != null) {
        document.getElementById('edu').style.display = "none";
      }

    } else if (dval == 'P') {
      $('#eduqual1').removeAttr('required');;
      $('#eduqual2').removeAttr('required');
      $('#eduqual3').attr('required', 'required');
      $('#eduqual').removeAttr('required');

      if (UGid != null) {
        document.getElementById('UG').style.display = "none";
      }
      if (GRid != null) {
        document.getElementById('GR').style.display = "none";
      }
      if (PGid != null) {
        document.getElementById('PG').style.display = "block";
      }
      if (EDUid != null) {
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

  $(function() {

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

    if (readCookie('member_register_form')) {
      //$('#error_id').html(''); 
      //$('#error_id').removeClass("alert alert-danger alert-dismissible");
      createCookie('member_register_form', "", -1);
    }

    /*  $('#new_captcha').click(function(event){
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
}); */
    $('#new_captcha').click(function(event) {
      event.preventDefault();
      $.ajax({
        type: 'POST',
        url: site_url + 'Nonreg/generatecaptchaajax/',
        success: function(res) {
          if (res != '') {
            $('#captcha_img').html(res);
          }
        }
      });
    });
    //$("#datepicker,#doj").keypress(function(event) {event.preventDefault();});

    if ($('#hiddenphoto').val() != '') {
      $('#image_upload_scanphoto_preview').attr('src', $('#hiddenphoto').val());
    }
    if ($('#hiddenscansignature').val() != '') {
      $('#image_upload_sign_preview').attr('src', $('#hiddenscansignature').val());
    }
    if ($('#hiddenidproofphoto').val() != '') {
      $('#image_upload_idproof_preview').attr('src', $('#hiddenidproofphoto').val());
    }

    // Add below if condition code by gaurav
    if (examCode == '1009' && memberType) {}
    if ($('#hiddenempidproofphoto').val() != '') {
      $('#image_upload_empidproof_preview').attr('src', $('#hiddenempidproofphoto').val());
    }

  });
</script>
<script>
  $('#scribe_flag').on('change', function(e) {
    if (e.target.checked) {
      $('#myModalscrib').modal();
    }
  });

  $('#btnReset').on('click', function(e) {
    $('.mem_reg_img').attr('src', "/assets/images/default1.png");
  });

  //email check
  // Get the session email value and verification status from PHP
        // var sessionEmail = '<?php echo $_POST['verified_email_val']; ?>';
        // var otpButton = document.getElementById('send_otp_btn');
        // var reset_btn = document.getElementById('reset_btn');


        // document.getElementById('email').addEventListener('onchange', function() {
        //     var email = document.getElementById('email').value;
        //     console.log(sessionEmail);
        //     console.log(email);
        //     if (sessionEmail !='' && email !='' && email === sessionEmail) {
        //         alert("This email has already been verified by you previously. You do not need to verify it again.");
        //         otpButton.style.display = 'none';
        //         reset_btn.style.display = 'block';
        //     } else if (sessionEmail !='' && email !='' && email !== sessionEmail ) {
        //         alert('Please verify email');
        //         otpButton.style.display = 'block';
        //     }
        // });

      // var sessionEmail = '<?php echo $_POST['verified_email_val']; ?>';
      // document.getElementById('email').addEventListener('blur', function() 
      // {
      //   var email = document.getElementById('email').value;
      //   alert(sessionEmail);
      //   alert(email);
      //   if (sessionEmail !='' && sessionEmail !='' && email === sessionEmail) 
      //   {
      //     alert("This email has already been verified by you previously. You do not need to verify it again.");
      //   }
      // }

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
<?php $this->load->view('iibfbcbf/common/inc_cropper_script', array('inc_fileChooser_accepted_files' => $inc_fileChooser_accepted_files, 'page_name'=>'non_mem_reg')); ?>

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
          /*document.getElementById("errorMessage").innerText = 
              "Total length of First Name, Middle Name, and Last Name should not exceed 50 characters.";*/
        
          $("#lastname_error").html('The total length of First Name, Middle Name, and Last Name must not exceed 50 characters.');
          $("#lastname_error").focus();    
          return false; // Prevent form submission
      } 
      return true; // Allow form submission
  }
</script>
<!-- END: JS CODE FOR IMAGE EDITOR -->