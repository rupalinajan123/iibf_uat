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

	.example {
		text-align: left !important;
		padding: 0 10px;
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

  #optionsModal > .modal-dialog, #cropModal > .modal-dialog { max-width: 600px; }
  #optionsModal > .modal-dialog h4.modal-title, #GuidelinesModal > .modal-dialog h4.modal-title, #cropModal > .modal-dialog h4.modal-title { text-align: center; }

  #GuidelinesModal > .modal-dialog { max-width: 800px; }
</style>
<?php
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
<div id="confirmBox">
	<div class="message" style="color:#F00"> <strong>VERY IMPORTANT</strong> I confirm that the Photo, Signature & Id proof images uploaded belongs to me and they are clear and readable.</div>
	<span class="button yes">Confirm</span> <span class="button no">Cancel</span>
</div>
<form class="form-horizontal" name="usersAddForm" id="usersAddForm" method="post" enctype="multipart/form-data" autocomplete="off">
	<div class="container">

		<!-- Trigger the modal with a button -->

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
									<td> One time life Membership fee (for candidates in India) with GST is <b> Rs.1,770/- </b>(Membership Fees Rs. 1,500/- & GST Rs.270/-) </td>
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
											<li>Image Dimension of Photograph should be 100(Width) <span style="color:#F00">*</span> 120(Height) Pixel only;</li>
											<li>Image Dimension of Signature should be 140(Width) <span style="color:#F00">*</span> 60(Height) Pixel only.</li>
											<li>Image Dimension of ID Proof should be 400(Width)<span style="color:#F00">*</span>420(Height) Pixel only. </li>
											<li>ID proof can be any one of the following: </li>
											<br>
											<ul>
												<li> ID Card issued by Employer (With Photo, Signature of Employee)
													With effect from 1st June 2017, Only Employer ID Card is accepted as ID Proof. </li>
												<li>Declaration Form (With Photo, Signature of Employee and endorsed by the Br. Manager (HOD of the Dept.)
													Only those newly recruited Employees who has not been issued Employer ID card can opt for Declaration form and the Declaration form should be in the format prescribed by IIBF and the same should be attested by the Br. Manager / HOD of the Dept. where the employee is working. Please note that only employees who are in regular service is eligible for Ordinary Membership of the Institute.
													If the declaration form uploaded is incomplete and not in the prescribed format your application will rejected. </li>
												<br>
											</ul>
											(ID proof should be clear / readable and verifiable after uploading, if not application is liable to be rejected)
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
											<li>Fill up all the online application form,(all the fields mark '<span style="color:#F00">*</span>' are mandatory), upload photo, signature, ID proff and follow the on-screen instructions to complete the registration process.</li>
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

		<!--  <img src="<?php echo base_url(); ?>assets/images/iibf_logo_black.png" class="ifci_logo_black" />-->
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1 class="register"> Ordinary Membership Registration<br />
				(Please read <a data-toggle="modal" data-target="#myModal" style="cursor:pointer;">"Instructions to Applicants"</a> before filling up the form) </h1>
			<!--<ol class="breadcrumb">
				<li><a href="<?php //echo base_url();
								?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
				<li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());
													?></a></li>
				<li class="active">Manage Users</li>
			</ol>-->
		</section>
		<section class="content">
			<div class="row">
				<!--<div class="col-md-1"></div>     -->
				<div class="col-md-12">
					<h4> I, as an employee of the bank/financial institution mentioned below, apply myself for being admitted as an Ordinary Member of Indian Institute of Banking &amp; Finance (I have never been a Member of the Institute in the past.)
						Please enter your details carefully, correction may not be possible later </h4>
				</div>
				<!--<div class="col-md-1"></div>-->
			</div>
			<div class="row">
				<div class="col-md-12">
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
						if ($var_errors != '') { ?>
							<div class="alert alert-danger alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
								<?php echo $var_errors; ?>
							</div>
						<?php
						}
						?>
						<div class="box-body">
							<div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none"> <span>display ajax response errors here</span> </div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">First Name <span style="color:#F00">*</span></label>
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
									<input type="text" class="form-control" id="firstname" onchange="createfullname()" onkeyup="createfullname()" onblur="createfullname()" name="firstname" placeholder="First Name" required value="<?php echo set_value('firstname'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
									<span class="error">
										<?php //echo form_error('firstname');
										?>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Middle Name</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="middlename" onchange="createfullname()" onkeyup="createfullname()" onblur="createfullname()" name="middlename" placeholder="Middle Name" value="<?php echo set_value('middlename'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
									<span class="error">
										<?php //echo form_error('middlename');
										?>
									</span>
								</div>
								(Max 30 Characters)
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Last Name</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="lastname" onchange="createfullname()" onkeyup="createfullname()" onblur="createfullname()" name="lastname" placeholder="Last Name" value="<?php echo set_value('lastname'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
									<span class="error">
										<?php //echo form_error('lastname');
										?>
									</span>
								</div>
								(Max 30 Characters)
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label">Full Name <span style="color:#F00"></span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="nameoncard" name="nameoncard" placeholder="Full Name" value="<?php echo set_value('nameoncard'); ?>" <?php /* data-parsley-maxlength="35" maxlength="35" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" */ ?> readonly disabled>
									<small style="color: #F00;">(Please check that you have entered your name correctly)</small>
									<span class="error">
										<?php //echo form_error('nameoncard');
										?>
									</span>
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
							<h6 class="box-title-hd">Office/Residential Address for communication (Please do not repeat the name of the Applicant, Only Address to be typed)</h6>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Address line1 <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" required value="<?php echo set_value('addressline1'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
									<span class="error">
										<?php //echo form_error('addressline1');
										?>
									</span>
								</div>
								(Max 30 Characters)
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Address line2</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2" value="<?php echo set_value('addressline2'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
									<span class="error">
										<?php //echo form_error('addressline2');
										?>
									</span>
								</div>
								(Max 30 Characters)
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Address line3</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line3" value="<?php echo set_value('addressline3'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
									<span class="error">
										<?php //echo form_error('addressline3');
										?>
									</span>
								</div>
								(Max 30 Characters)
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Address line4</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4" value="<?php echo set_value('addressline4'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
									<span class="error">
										<?php //echo form_error('addressline4');
										?>
									</span>
								</div>
								(Max 30 Characters)
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">District <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="district" name="district" placeholder="District" required value="<?php echo set_value('district'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
									<span class="error">
										<?php //echo form_error('district');
										?>
									</span>
								</div>
								(Max 30 Characters)
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">City <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo set_value('city'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
									<span class="error">
										<?php //echo form_error('city');
										?>
									</span>
								</div>
								(Max 30 Characters)
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">State <span style="color:#F00">*</span></label>
								<div class="col-sm-3">
									<select class="form-control" id="state" name="state" required onchange="javascript:checksate(this.value)">
										<option value="">Select</option>
										<?php if (count($states) > 0) {
											foreach ($states as $row1) {   ?>
												<option value="<?php echo $row1['state_code']; ?>" <?php echo  set_select('state', $row1['state_code']); ?>><?php echo $row1['state_name']; ?></option>
										<?php }
										} ?>
									</select>
									<input hidden="statepincode" id="statepincode" value="" autocomplete="false">
								</div>
								<label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode <span style="color:#F00">*</span></label>
								<div class="col-sm-3">
									<input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode/Zipcode" required value="<?php echo set_value('pincode'); ?>" data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-checkpin data-parsley-type="number" data-parsley-trigger-after-failure="focusout">
									(6 digits only) <span class="error">
										<?php //echo form_error('pincode');
										?>
									</span>
								</div>
							</div>

							<!------------------------------| Permenent Address : Added By Bhushan|--------------------------->

							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">Permanent Address Details</h3>
								</div>
								<div class="box-header with-border nobg">
									<h6 class="box-title-hd">
										<input type="checkbox" name="same_as_above" onclick="sameAsAbove(this.form)">
										<em>Permanent address same as official/Residential address</em>
									</h6>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Address line1 <span style="color:#F00">*</span></label>
									<div class="col-sm-5">
										<input type="text" class="form-control" id="addressline1_pr" name="addressline1_pr" placeholder="Address line1" required value="<?php echo set_value('addressline1_pr'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
										<span class="error">
											<?php //echo form_error('addressline1');
											?>
										</span>
									</div>
									(Max 30 Characters)
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Address line2</label>
									<div class="col-sm-5">
										<input type="text" class="form-control" id="addressline2_pr" name="addressline2_pr" placeholder="Address line2" value="<?php echo set_value('addressline2_pr'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
										<span class="error">
											<?php //echo form_error('addressline2');
											?>
										</span>
									</div>
									(Max 30 Characters)
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Address line3</label>
									<div class="col-sm-5">
										<input type="text" class="form-control" id="addressline3_pr" name="addressline3_pr" placeholder="Address line3" value="<?php echo set_value('addressline3_pr'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
										<span class="error">
											<?php //echo form_error('addressline3');
											?>
										</span>
									</div>
									(Max 30 Characters)
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Address line4</label>
									<div class="col-sm-5">
										<input type="text" class="form-control" id="addressline4_pr" name="addressline4_pr" placeholder="Address line4" value="<?php echo set_value('addressline4_pr'); ?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
										<span class="error">
											<?php //echo form_error('addressline4');
											?>
										</span>
									</div>
									(Max 30 Characters)
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">District <span style="color:#F00">*</span></label>
									<div class="col-sm-5">
										<input type="text" class="form-control" id="district_pr" name="district_pr" placeholder="District" required value="<?php echo set_value('district_pr'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
										<span class="error">
											<?php //echo form_error('district');
											?>
										</span>
									</div>
									(Max 30 Characters)
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">City <span style="color:#F00">*</span></label>
									<div class="col-sm-5">
										<input type="text" class="form-control" id="city_pr" name="city_pr" placeholder="City" required value="<?php echo set_value('city_pr'); ?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
										<span class="error">
											<?php //echo form_error('city');
											?>
										</span>
									</div>
									(Max 30 Characters)
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">State <span style="color:#F00">*</span></label>
									<div class="col-sm-3">
										<select class="form-control" id="state_pr" name="state_pr" required>
											<option value="">Select</option>
											<?php if (count($states) > 0) {
												foreach ($states as $row1) {   ?>
													<option value="<?php echo $row1['state_code']; ?>" <?php echo  set_select('state_pr', $row1['state_code']); ?>><?php echo $row1['state_name']; ?></option>
											<?php }
											} ?>
										</select>
										<input hidden="statepincode" id="statepincode" value="" autocomplete="false">
									</div>
									<label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode <span style="color:#F00">*</span></label>
									<div class="col-sm-3">
										<input type="text" class="form-control" id="pincode_pr" name="pincode_pr" placeholder="Pincode/Zipcode" required value="<?php echo set_value('pincode_pr'); ?>" data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-permanant_checkpin data-parsley-type="number" data-parsley-trigger-after-failure="focusout">
										(Max 6 digits) <span class="error">
											<?php //echo form_error('pincode');
											?>
										</span>
									</div>
								</div>
							</div>

							<!-----------------------------------------| Close Permenent Address Tab |------------------------------------->
							<!----------------------------------------------------------------------------------->
							<!--<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Date of Birth <span style="color:#F00">*</span></label>
								<div class="col-sm-2">
								<input type="text" class="form-control pull-right" id="datepicker"  name="dob" placeholder="Date of Birth" required value="<?php echo set_value('dob'); ?>" >
								<span class="error"><?php //echo form_error('dob');
													?></span>
								</div>
							</div>-->

							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Date of Birth <span style="color:#F00">*</span></label>
								<div class="col-sm-2 example dobpin">
									<input type="hidden" id="dob1" name="dob" required autocomplete="false" value="<?php echo set_value('dob1'); ?>">
									<?php
									$min_year = date('Y', strtotime("- 18 year"));
									$max_year = date('Y', strtotime("- 80 year"));
									?>
									<input type='hidden' name='minyear' id='minyear' value="<?php echo $min_year; ?>" autocomplete="false">
									<input type='hidden' name='maxyear' id='maxyear' value="<?php echo $max_year; ?>" autocomplete="false">
									<span id="dob_error" class="error"></span>
								</div>

								<!--<input type="text" class="form-control pull-right" id="datepicker"  name="dob" placeholder="Date of Birth" required value="<?php echo set_value('dob'); ?>" >-->
								<span class="error">
									<?php //echo form_error('dob');
									?>
								</span>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Gender <span style="color:#F00">*</span></label>
								<div class="col-sm-3">
									<input type="radio" class="minimal cls_gender" id="female" name="gender" required value="female" <?php echo set_radio('gender', 'female'); ?>>
									Female
									<input type="radio" class="minimal cls_gender" id="male" name="gender" required value="male" <?php echo set_radio('gender', 'male'); ?>>
									Male <span class="error">
										<?php //echo form_error('gender');
										?>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Qualification <span style="color:#F00">*</span></label>
								<div class="col-sm-6">
									<input type="radio" class="minimal" id="U" attr="optedu" name="optedu" value="U" onclick="changedu(this.value)" <?php echo set_radio('optedu', 'U'); ?>required>
									Under Graduate
									<input type="radio" class="minimal" id="G" attr="optedu" name="optedu" value="G" onclick="changedu(this.value)" <?php echo set_radio('optedu', 'G'); ?>>
									Graduate
									<input type="radio" class="minimal" id="P" attr="optedu" name="optedu" value="P" onclick="changedu(this.value)" <?php echo set_radio('optedu', 'P'); ?>>
									Post Graduate <span class="error">
										<?php //echo form_error('optedu');
										?>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Please specify <span style="color:#F00">*</span></label>
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

								<!-- <div class="col-sm-5" id="noOptEdu">
									<select class="form-control" id="noOptEdu1" name="noOptEdu1" required>
									<option value="">--Select--</option>
									</select>
									<span class="error"></span>
								</div>-->

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
												<option value="<?php echo $row1['qid']; ?>" <?php echo set_select('eduqual1', $row1['qid']); ?>><?php echo $row1['name']; ?></option>
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
							<input type="hidden" id="education_type" name="education_type" value="<?php echo set_value('education_type'); ?>">
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Bank/Institution working <span style="color:#F00">*</span></label>
								<div class="col-sm-5" style="display:block">
									<select id="institutionworking" name="institutionworking" class="form-control" required>
										<option value="">--Select--</option>
										<?php if (count($institution_master)) {
											foreach ($institution_master as $institution_row) {   ?>
												<option value="<?php echo $institution_row['institude_id']; ?>" <?php echo  set_select('institutionworking', $institution_row['institude_id']); ?>><?php echo $institution_row['name']; ?></option>
										<?php }
										} ?>
									</select>
									<span class="error">
										<?php //echo form_error('institutionworking');
										?>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Branch/Office <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="office" name="office" placeholder="Branch/Office" required value="<?php echo set_value('office'); ?>" data-parsley-maxlength="20" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/">
									<span class="error">
										<?php //echo form_error('office');
										?>
									</span>
								</div>
								(Max 20 Characters)
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Designation <span style="color:#F00">*</span></label>
								<div class="col-sm-5" style="display:block">
									<select id="designation" name="designation" class="form-control" required>
										<option value="">--Select--</option>
										<?php if (count($designation)) {
											foreach ($designation as $designation_row) {   ?>
												<option value="<?php echo $designation_row['dcode']; ?>" <?php echo  set_select('designation', $designation_row['dcode']); ?>><?php echo $designation_row['dname']; ?></option>
										<?php }
										} ?>
									</select>
									<span class="error">
										<?php //echo form_error('designation');
										?>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Bank Employee Id <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="bank_emp_id" name="bank_emp_id" placeholder="Employee Id" value="<?php echo set_value('bank_emp_id'); ?>" data-parsley-maxlength="20" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required>
									<span class="error">
										<?php //echo form_error('city');
										?>
									</span>
								</div>
							</div>
							<!--<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Date of joining Bank/Institution  <span style="color:#F00">*</span></label>
								<div class="col-sm-3">
								<input type="text" class="form-control pull-right" id="doj"  name="doj" placeholder="Date of joining Bank/Institution" required value="<?php echo set_value('doj'); ?>" >
								<span class="error"><?php //echo form_error('doj');
													?></span>
								</div>
							</div>-->

							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Date of joining Bank/Institution <span style="color:#F00">*</span></label>
								<div class="col-sm-4 doj">
									<!--<input type="text" class="form-control pull-right" id="doj"  name="doj" placeholder="Date of joining Bank/Institution" required value="<?php echo set_value('doj'); ?>" >-->

									<div class="col-sm-2 example">
										<input type="hidden" id="doj1" name="doj" value="<?php echo set_value('doj1'); ?>">
									</div>
									<span id="doj_error" class="error"></span>
								</div>
							</div>
							<!-- Start gaurav shewale(12th march 2024) code for email verification -->

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
							?>

							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Email Id <span style="color:#F00">*</span></label>
								<div class="row">
									<div class="col-sm-5">
										<input type="email" class="form-control email-id" id="email" name="email" placeholder="Email" data-parsley-type="email" value="<?php echo set_value('email'); ?>" data-parsley-maxlength="45" required data-parsley-emailcheck data-parsley-trigger-after-failure="focusout" <?php if($emailStatus) { ?> readonly <?php } ?>>
									</div>
									<div class="col-sm-2">
									<button type="button" class="btn btn-info send-otp" id="send_otp_btn" data-type='send_otp' <?php if($emailStatus == 'yes') { ?> style="display:none;" <?php } ?>>Get OTP</button>
									<a class="btn btn-info" id="reset_btn" href="javascript:void(0)" <?php if($emailStatus == 'yes') { ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>Change Email</a>
									<!-- (Enter valid and correct Email Id to receive communication)  -->
									</div>
									<span class="error">
										<?php //echo form_error('email');
										?>
									</span>
								</div>
							</div>

							<div class="form-group verify-otp-section" style="display:none;">
								<label for="roleid" class="col-sm-4 control-label">OTP <span style="color:#F00">*</span></label>
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
							<!-- End gaurav shewale(12th march 2024) code for email verification -->
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Phone </label>
								<label for="roleid" class="col-sm-1 control-label" style="text-align:left; padding:0; margin-left:15px;"> STD Code</label>
								<div class="col-sm-2">
									<input type="text" class="form-control " id="stdcode" data-parsley-type="number" data-parsley-maxlength="4" name="stdcode" placeholder="STD Code" value="<?php echo set_value('stdcode'); ?>" data-parsley-trigger-after-failure="focusout">
									<span class="error">
										<?php //echo form_error('stdcode');
										?>
									</span>
								</div>
								<label for="roleid" class="col-sm-1 control-label" style="text-align:left; padding:0;"> Phone No</label>
								<div class="col-sm-3">

									<input type="text" class="form-control" id="phone" name="phone" placeholder="Phone No" data-parsley-minlength="7" data-parsley-type="number" data-parsley-maxlength="12" value="<?php echo set_value('phone'); ?>" data-parsley-trigger-after-failure="focusout">
									<span class="error">
										<?php //echo form_error('phone');
										?>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Mobile <span style="color:#F00">*</span></label>
								<div class="row">
									<div class="col-sm-5">
										<input type="text" class="form-control mobile" id="mobile" name="mobile" placeholder="Mobile" onKeyPress="if(this.value.length==10) return false;" data-parsley-minlength="10" data-parsley-maxlength="10" value="<?php echo set_value('mobile'); ?>" required data-parsley-mobilecheck data-parsley-trigger-after-failure="focusout" <?php if($mobileStatus) { ?> readonly <?php } ?>>
										<span class="error">
											<?php //echo form_error('mobile');
											?>
										</span>
									</div>	
									<div class="col-sm-2">
										<button type="button" class="btn btn-info send-otp-mobile" id="send_otp_btn_mobile" data-type='send_otp' <?php if($mobileStatus == 'yes') { ?> style="display:none;" <?php } ?>>Get OTP</button>
										<a class="btn btn-info" id="reset_btn_mobile" href="javascript:void(0)" <?php if($mobileStatus == 'yes') { ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>Change Mobile No.</a>
										<!-- (Enter valid and correct Email Id to receive communication)  -->
									</div>
								</div>	
							</div>

							<div class="form-group verify-otp-section-mobile" style="display:none;">
								<label for="roleid" class="col-sm-4 control-label">OTP <span style="color:#F00">*</span></label>
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

							<?php
							$star = '';
							$requiredflag = 0;
							if (set_value('state')) {
								if (set_value('state') != 'ASS' && set_value('state') != 'JAM' && set_value('state') != 'MEG') {
									$star = '*';
									$requiredflag = 1;
								}
							} else {
								$star = '*';
								$requiredflag = 1;
							} ?>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Aadhar Card Number <!--<span style="color:#F00" id="mendatory_state"><?php //echo $star; ?></span>--></label>
								<div class="col-sm-5">
									<?php ?> <input type="text" class="form-control" id="aadhar_card" name="aadhar_card" placeholder="Aadhar Card Number" data-parsley-minlength="12" data-parsley-maxlength="12" data-parsley-type="number" size="12" 
									<?php if ($requiredflag) { echo 'required'; } ?> value="<?php echo set_value('aadhar_card'); ?>" data-parsley-trigger-after-failure="focusout">
									<span class="error"><?php //echo form_error('idNo');
														?></span><?php ?>
									<!--  <input type="password" class="form-control" id="aadhar_card"  name="aadhar_card" placeholder="Aadhar Card Number" 
												data-parsley-minlength="12" data-parsley-maxlength="12" data-parsley-type="number" size="12" 
												value="<?php echo set_value('aadhar_card'); ?>" data-parsley-trigger-after-failure="focusout" data-parsley-encryptaadhar>
												<input type="text" class="form-control" id="aadhar_card"  name="aadhar_card" placeholder="Aadhar Card Number" 
												value="<?php echo set_value('aadhar_card'); ?>" data-parsley-trigger-after-failure="focusout">
												<span class="error">
												<?php //echo form_error('idNo');
												?>
											</span>  -->
								</div>
							</div>
							<!-- ADDED BY POOJA MANE : ON : 08-06-2023 -->
							<div class="form-group">
								<div class="col-sm-11">
									<div><a style="color:#FF0000;" href="https://iibf.esdsconnect.com//uploads/Guideline_for_photo_image_loading_final.pdf" target="_blank"><strong style="color:#F00; text-decoration:underline">Guidelines for photo-image loading (Annexure I and Annexure II)</strong></a></div>
								</div>
							</div>
							
              <?php $data_lightbox_title_common = "Member Registration"; ?>

              <input type="hidden" name="form_value" id="form_value" value="form_value">
              <input type="hidden" id="data_lightbox_title_hidden" value="<?php echo $data_lightbox_title_common; ?>">
              <?php $inc_fileChooser_accepted_files = '.jpg, .jpeg'; ?>
              <input type="hidden" name="inc_fileChooser_accepted_files" id="inc_fileChooser_accepted_files" value="<?php echo $inc_fileChooser_accepted_files; ?>">

              <!-- ADDED BY POOJA MANE : ON : 08-06-2023 -->
							<?php /* <div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Upload Your Scanned Photograph <span style="color:#F00">**</span></label>
								<div class="col-sm-5">
									<input type="file" name="scannedphoto" id="scannedphoto" required onchange="validateFile(event, 'error_photo_size', 'image_upload_scanphoto_preview', '50kb')">
									<input type="hidden" id="hiddenphoto" name="hiddenphoto">
									<span class="note">Please Upload only .jpg, .jpeg Files upto 50KB</span></br>
									<span class="note-error" id="error_photo_size" class="note-error"></span>
									<br>
									<div id="error_photo_size"></div>
									<span class="photo_text" style="display:none;"></span> <span class="error">
										<?php //echo form_error('scannedphoto');
										?>
									</span>
								</div>
								<img class="mem_reg_img" id="image_upload_scanphoto_preview" height="100" width="100" src="/assets/images/default1.png" />
							</div> */ ?>
              
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

							<?php /* <div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Upload Your Scanned Signature Specimen <span style="color:#F00">**</span></label>
								<div class="col-sm-5">
									<input type="file" name="scannedsignaturephoto" id="scannedsignaturephoto" required onchange="validateFile(event, 'error_signature', 'image_upload_sign_preview', '50kb')">
									<div id="scannedsignaturephoto_allowedFilesTypes" style="display:none">.jpg,.jpeg,.png</div>
									<input type="hidden" id="hiddenscansignature" name="hiddenscansignature">
									<span class="note">Please Upload only .jpg, .jpeg, .png Files upto 50KB</span></br>
									<span id="error_signature" class="note-error"></span>
									<br>
									<div id="error_signature_size"></div>
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

							<!--<div class="form-group">
							<label for="roleid" class="col-sm-4 control-label">Select Id Proof <span style="color:#F00">*</span></label>
							<div class="col-sm-8">
							<?php /*if(count($idtype_master) > 0)
								{
								$i=1;
								foreach($idtype_master as $idrow)
								{?>
								<input name="idproof" value="<?php echo $idrow['id'];?>" type="radio" class="minimal idproof_cls" 
								<?php if(set_value('idproof')){echo set_radio('idproof', $idrow['id'], TRUE);}?> 
								<?php if($i==count($idtype_master)){echo 'required';}?> onclick="handleClick(this);">
								<?php echo $idrow['name'];?><br>
								<?php 
								$i++;}
							}*/ ?>
							<span class="error"><?php //echo form_error('idproof');
												?></span>
							</div>
						</div>-->
							<!--  idproof_hidd set static 4(type idproof), confirmed by prafull-->
							<input type="hidden" name="idproof" id="idproof" value="4">
							<div class="form-group">
								<div class="col-sm-11">
									<div class=""><label class="box-title"> Id Proof : </label> Mandatorily upload Identity Card issued by the bank. In case Employees who have not been issued Employer ID Card then upload any one of the following documents from the list given below
										<ol>
											<li>Aadhaar Card</li>
											<li>Passport</li>
											<li>PAN Card</li>
											<li>Voter ID Card</li>
											<li>Driving Licence</li>
										</ol>
									</div>
								</div>
							</div>

							<?php /* <div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Upload Your Id Proof <span style="color:#F00">**</span></label>
								<div class="col-sm-5">
									<input type="file" name="idproofphoto" id="idproofphoto" required onchange="validateFile(event, 'error_idproof_size', 'image_upload_idproof_preview', '300kb')">
									<div id="idproofphoto_allowedFilesTypes" style="display:none">.jpg,.jpeg,.png</div>
									<input type="hidden" id="hiddenidproofphoto" name="hiddenidproofphoto">
									<span class="note">Please Upload only .jpg, .jpeg, .png Files upto 300KB</span></br>
									<span class="note-error" id="error_idproof_size"></span>
									<br>
									<div id="error_dob_size"></div>
									<span class="dob_proof_text" style="display:none;"></span> <span class="error">
										<?php //echo form_error('idproofphoto');
										?>
									</span>
								</div>
								<img class="mem_reg_img" id="image_upload_idproof_preview" height="100" width="100" src="/assets/images/default1.png" />
								<div class="col-sm-12">
									<!--<div id="declaration_id" style="display:none">
										<span style='color:#FF0000;'>For ID Proof,  ID Card issued by Employer is compulsory and for Newly inducted employees if the same is  not available, Declaration Form duly filled and endorsed by employer to be uploaded as per the format given here:   Pl</span>   <a href="<?php echo base_url() ?>uploads/declaration/Declaration-format.jpg" target="_blank">CLICK / Print</a>
									</div>-->
								</div>
							</div> */ ?>

              <div class="form-group"><?php // Upload Your Id Proof  ?>
                <label for="idproofphoto" class="col-sm-4 control-label">Upload Your Id Proof <span style="color:#F00">*</span></label>
                <div class="col-sm-7">
                  <div class="img_preview_input_outer pull-left">
                    <input type="file" name="idproofphoto" id="idproofphoto" class="form-control hide_input_file_cropper" required data-parsley-errors-container="#idproofphotoError" />

                    <div class="image-input image-input-outline image-input-circle image-input-empty">
                      <div class="profile-progress"></div>
                      <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('idproofphoto', 'member_registration', 'Edit Signature');" onblur="validate_form_images('idproofphoto')">Upload Id Proof</button>
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

							<div class="form-group">
								<div class="col-sm-11">
									<span><label class="box-title">Declaration Form :</label> Mandatorily upload the Declaration form signed(with stamped) by Branch Manager/HOD.</span>
									<div><a style='color:#FF0000;' href=" <?php echo base_url() ?>uploads/declaration/DECLARATION.pdf" target="_blank"><strong style="color:#F00; text-decoration:underline">Please click here to PRINT.</strong></a></div>
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

              <div class="form-group"><?php // Upload your Declaration Form  ?>
                <label for="declarationform" class="col-sm-4 control-label">Upload your Declaration Form <span style="color:#F00">*</span></label>
                <div class="col-sm-7">
                  <div class="img_preview_input_outer pull-left">
                    <input type="file" name="declarationform" id="declarationform" class="form-control hide_input_file_cropper" required data-parsley-errors-container="#declarationformError" />

                    <div class="image-input image-input-outline image-input-circle image-input-empty">
                      <div class="profile-progress"></div>
                      <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('declarationform', 'member_registration', 'Edit Signature');" onblur="validate_form_images('declarationform')">Upload Declaration Form</button>
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
                      <a href="<?php echo $preview_declarationform . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Scanned Declaration - '; echo $data_lightbox_title_common;?>">
                        <img src="<?php echo $preview_declarationform . "?" . time(); ?>">
                      </a>

                      <button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="declarationform" data-db_tbl_name="member_registration" data-title="Edit Declaration" title="Edit Declaration" alt="Edit Declaration"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
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
								<label for="roleid" class="col-sm-10 control-label" style="text-align:left;">I agree to receive the Annual report from the Institute in a softcopy, at my registered Email Id, in place of physical copy</label>
								<div class="col-sm-2">
									<input value="Y" name="optnletter" id="optnletter" checked="" type="radio" <?php echo set_radio('optnletter', 'Y'); ?>>
									Yes
									<input value="N" name="optnletter" id="optnletter" type="radio" <?php echo set_radio('optnletter', 'N'); ?>>
									No <span class="error">
										<?php //echo form_error('optnletter');
										?>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-1 control-label pad_top_2"> Note <span style="color:#F00">**</span></label>
								<div class="col-sm-10"> 1. Please ensure all images are clear, visible and readable after uploading, if not do not submit and upload fresh set of images.</br>
									2. Images format should be in JPG 8bit and size should be minimum 8KB and maximum 50KB.</br>
									3. Image Dimension of Photograph should be 100(Width) <span style="color:#F00">*</span> 120(Height) Pixel only</br>
									4. Image Dimension of Signature should be 140(Width) <span style="color:#F00">*</span> 60(Height) Pixel only</br>
									5. Image Dimension of ID Proof should be 400(Width) <span style="color:#F00">*</span> 420(Height) Pixel only. Size should be minimum 8KB and maximum 300KB.</br>
								</div>
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
								<div id="error_vis_imp_cert"></div>
								<br>
								<div id="error_vis_imp_cert_size"></div>
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
								<div id="error_orth_han_cert"></div>
								<br>
								<div id="error_orth_han_cert_size"></div>
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
								<div id="error_cer_palsy_cert"></div>
								<br>
								<div id="error_cer_palsy_cert_size"></div>
								<span class="cer_palsy_cert_text" style="display:none;"></span> <span class="error"> </span>
							</div>
						</div>

					</div>
					<!-- Benchmark Disability Code End -->

					<div class="box box-info">
						<div class="box-header with-border header_blue">
							<h3 class="box-title">Declaration:</h3>
						</div>
						<div class="box-body blue_bg">
							<div class="form-group">
								<div class="col-sm-12">
									<ol>
										<li> I declare that I have submitted my Aadhar Card Number and Proof of my Identity : ID Card issued by Employer / Declaration Form as specified above. </li>
										<li> I hereby declare that all the information given in this application is true, complete and correct. I understand that in the event of any information being found false or incorrect subsequent to allotment of membership, my membership is liable to be cancelled / terminated. </li>
										<li> I further declare that I have not at any time been a member of the Institute/applied earlier for membership of the Institute. </li>
										<li> I hereby agree, if admitted, to be bound by the Memorandum and Articles of Association of the Institute. I am aware that, if admitted as an Ordinary Member, as per the provisions of the Articles of Association of the Institute. I shall be liable, in the event of the Institute begin wound up, to contribute towards its liabilities a sum not exceeding Rs. 1770/- </li>
										<li> I hereby agree that "Life Membership fees paid is towards corpus"</li>
										<li> I confirm having read and understood the rules and regulations of the Institute and I hereby agree to abide by the same. In case I am desirous of Instituting any legal proceedings against the Institute I hereby agree that such legal proceedings shall be instituted only in courts at Mumbai, New Delhi, Kolkata and Chennai in whose Jurisdiction Zonal office/s of the Institute is situated and my application thereto pertains and not in any other court </li>
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
						<div class="form-group m_t_15">
							<label for="roleid" class="col-sm-3 control-label">Security Code <span style="color:#F00">*</span></label>
							<div class="col-sm-2">
								<input type="text" name="code" id="code" required class="form-control ">
								<span class="error" id="captchaid" style="color:#B94A48;"></span>
							</div>
							<div class="col-sm-3">
								<div id="captcha_img"><?php echo $image; ?></div>
								<span class="error">
									<?php //echo form_error('code');
									?>
								</span>
							</div>
							<div class="col-sm-2"> <a href="javascript:void(0);" id="new_captcha" onclick="refresh_register_captcha();" class="forget">Change Image</a> <span class="error">
									<?php //echo form_error('code');
									?>
								</span> </div>
						</div>
						<div class="box-footer">
							<div class="col-sm-9 col-sm-offset-3"> <a href="javascript:void(0);" class="btn btn-info btn_submit" onclick="javascript:return checkform();" id="preview">Preview and Proceed for Payment</a>&nbsp;
								<!--<input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Preview and Proceed for Payment">-->
								<button type="reset" class="btn btn-default" name="btnReset" id="btnReset">Reset</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		
		<input type="hidden" id="email_verify_status" name="email_verify_status" value="<?php echo $email_verify_status; ?>">
		<input type="hidden" id="mobile_verify_status" name="mobile_verify_status" value="<?php echo $mobile_verify_status; ?>">
	</div>
	<div class="modal fade" id="confirm" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<p style="color:#F00"> <strong>VERY IMPORTANT</strong><br>
						I confirm that the Photo, Signature & Id proof images uploaded belongs to me and they are clear and readable.<br />
						<br />
						<!--   We find that Aadhaar Number is not mentioned in your membership account. You are requested to enter Aadhaar number in your membership account immediately.
						
						Aadhaar number can be updated to your existing membership account through edit profile option by entering your membership number and profile password. <br />
					In case, if you do not have Aadhaar number, request you to obtain it on or before December 31, 2017 and update the Aadhaar number in your membership profile.-->
					</p>
				</div>
				<div class="modal-footer">
					<!--  <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="preview();">Confirm</button>-->
					<input type="submit" name="btnSubmit" class="btn btn-primary" id="btnSubmit" value="Confirm">
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
</form>
<link href="<?php echo base_url(); ?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
<script src="<?php echo base_url(); ?>js/validation.js?<?php echo time(); ?>"></script>
<script src="<?php echo base_url(); ?>js/disability.js?<?php echo time(); ?>"></script>
<!-- Encrypttion code  start -->
<!--- 
	<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/pbkdf2.js"></script>
	
	<script>
	function encaadhar(){
	
    var key = CryptoJS.enc.Hex.parse('bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3');
	var iv  = CryptoJS.enc.Hex.parse('101112131415161718191a1b1c1d1e1f');
	
	var encrypted = CryptoJS.AES.encrypt($('#aadhar_card').val(), key, { iv: iv });
	
	var password_base64 = encrypted.ciphertext.toString(CryptoJS.enc.Base64); 
	//alert("herer"+password_base64);
	$("#aadhar_card").removeAttr('data-parsley-minlength');
	$("#aadhar_card").removeAttr('data-parsley-maxlength');
	$('#aadhar_card').val(password_base64); 
	
	}
	
</script> -->
<!-- Encrypttion code  end -->
<script>
	//added to auto fetch fullname Pooja Mane : 7-6-23
	createfullname();

	var emailVerify  = false;
	var mobileVerify = false;

	function createfullname() {
		firstname = $.trim($("#firstname").val()).toUpperCase();
		middlename = ' ' + $.trim($("#middlename").val()).toUpperCase();
		lastname = ' ' + $.trim($("#lastname").val()).toUpperCase();
		if ($.trim(firstname) != "" || $.trim(middlename) != "" || $.trim(lastname) != "") {
			$("#nameoncard").val(firstname + middlename + lastname);
		} else {
			$("#nameoncard").val("")
		}

		//nameoncard.value = firstname.value+' '+middlename.value+' '+lastname.value;
	} //added to auto fetch fullname code end : 7-6-23

	function refresh_register_captcha() {
		$.ajax({
			type: 'POST',
			url: site_url + 'Register/generatecaptchaajax/',
			async: false,
			success: function(res) {
				if (res != '') {
					$('#captcha_img').html(res);
					$("#code").val("");
				}
			}
		});
	}
</script>
<script>
	// Start gaurav shewale(12th march 2024) code for email verification 
	$('.verify-otp').click(function() 
	{
		var otp         = $('#otp').val();
		var verify_type = $(this).attr('data-verify-type');
		var email 		= $('#email').val();
	    var type  		= 'verify_otp';
	    
	    var data = {};
	    data.email 		 = email;
	    data.otp   		 = otp;
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
		var mobile 		= $('#mobile').val();
	    var type  		= 'verify_otp';
	    
	    var data = {};
	    data.mobile 	 = mobile;
	    data.otp   		 = otp;
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
			url: site_url + 'Register/send_otp/',
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
			url  : site_url + 'Register/send_otp_mobile/',
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

	$('.send-otp').click(function() {
	    var email = $('#email').val();
	    var type  = $(this).attr('data-type');
	    var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/; // Regular expression for email format
	    
	    if (type == 'resend_otp') {
	    	$('#otp').val('');
	    }
	    var data = {};
	    data.email       = email;
	    data.otp   		 = '';
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

	$('.send-otp-mobile').click(function() {
	    var mobile = $('#mobile').val();
	    var type  = $(this).attr('data-type');
	    
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
	    data.otp   		 = '';
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
	// END gaurav shewale(12th march 2024) code for email verification
	
	$(function() {
		$("#dob1").dateDropdowns({
			submitFieldName: 'dob1',
			minAge: 0,
			maxAge: 79
		});
		// Set all hidden fields to type text for the demo
		//$('input[type="hidden"]').attr('type', 'text').attr('readonly', 'readonly');
	});
	$(function() {
		$("#doj1").dateDropdowns({
			submitFieldName: 'doj1',
			minAge: 0,
			maxAge: 59
		});
	});

	$(document).ready(function() {
		var dtable = $('.dataTables-example').DataTable();
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

		$("#doj1").change(function() {
			var sel_doj = $("#doj1").val();
			if (sel_doj != '') {
				var doj_arr = sel_doj.split('-');
				if (doj_arr.length == 3) {
					CompareToday(doj_arr[2], doj_arr[1], doj_arr[0]);
				} else {
					alert('Select valid date');
				}
			}
		});

		var dt = new Date();
		dt.setFullYear(new Date().getFullYear() - 18);

		if ($('#hiddenphoto').val() != '') {
			$('#image_upload_scanphoto_preview').attr('src', $('#hiddenphoto').val());
		}
		if ($('#hiddenscansignature').val() != '') {
			$('#image_upload_sign_preview').attr('src', $('#hiddenscansignature').val());
		}
		if ($('#hiddenidproofphoto').val() != '') {
			$('#image_upload_idproof_preview').attr('src', $('#hiddenidproofphoto').val());
		}

		statecode = $("#state option:selected").val();

		if (statecode != '') 
    {
			if (statecode == 'ASS' || statecode == 'JAM' || statecode == 'MEG')
      {
				//document.getElementById('mendatory_state').style.display = "none";
				//document.getElementById('non_mendatory_state').style.display = "block";
				$("#aadhar_card").removeAttr("required");
			} 
      else 
      {
				//document.getElementById('mendatory_state').style.display = "block";
				//document.getElementById('mendatory_state').innerHTML = "*";
				//document.getElementById('non_mendatory_state').style.display = "none";
				$("#aadhar_card").attr("required", "true");
			}
		}

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

		$('#education_type').val(dval)
		var UGid = document.getElementById('UG');
		var GRid = document.getElementById('GR');
		var PGid = document.getElementById('PG');
		var EDUid = document.getElementById('edu');

		if (dval == 'U') {
			$('#eduqual1').attr('required', 'required');
			$('#eduqual2').removeAttr('required');
			$('#eduqual3').removeAttr('required');
			$('#eduqual').removeAttr('required');
			//	$('#noOptEdu').hide();

			if (UGid != null) {
				//	alert('UG');
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
			//$('#noOptEdu').hide();

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
			//$('#noOptEdu').hide();

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
		} else {
			//$('#noOptEdu').show();	
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
			// $('#error_id').html('');
			// $('#error_id').removeClass("alert alert-danger alert-dismissible");
			createCookie('member_register_form', "", -1);
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

		$("body").on("contextmenu", function(e) {
			return false;
		});

		$(this).scrollTop(0);

		var dval = $('#education_type').val();
		if (dval != '') {
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
					//	alert('UG');
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

	});

	function sameAsAbove(fill) {

		/*var addressline1 = fill.addressline1.value;
			var district = fill.district.value;
			var city = fill.city.value;
			var state = fill.state.value;
			var pincode = fill.pincode.value;
			
			var r = confirm("Please fill contact details first!");
			if (addressline1 == '' && district == '' && city == '' && state == '' && pincode == '' ) {
			alert('please fill contact details first..');
			} 
			else
		{ }*/
		if (fill.same_as_above.checked == true) {
			fill.addressline1_pr.value = fill.addressline1.value;
			fill.addressline2_pr.value = fill.addressline2.value;
			fill.addressline3_pr.value = fill.addressline3.value;
			fill.addressline4_pr.value = fill.addressline4.value;
			fill.district_pr.value = fill.district.value;
			fill.city_pr.value = fill.city.value;
			fill.state_pr.value = fill.state.value;
			fill.pincode_pr.value = fill.pincode.value;
		} else {
			fill.addressline1_pr.value = '';
			fill.addressline2_pr.value = '';
			fill.addressline3_pr.value = '';
			fill.addressline4_pr.value = '';
			fill.district_pr.value = '';
			fill.city_pr.value = '';
			fill.state_pr.value = '';
			fill.pincode_pr.value = '';
		}

		/*else {
			txt = "You pressed Cancel!";
		} */

	}


	///////////////////// Declaration form validation done by Pratibha Borse on 23 March 22 //////////////////////

	/*$( "#declarationform" ).change(function() {
		//var filesize1=this.files[0].size/1024<8;
		var filesize2=this.files[0].size/1024>300;
		var flag = 1;
		//$("#p_dob_proof").hide();
		
		var declartion_proof_image=document.getElementById('declarationform');
		var declaration_proof_im=declartion_proof_image.value;
		var ext3=declaration_proof_im.substring(declaration_proof_im.lastIndexOf('.')+1);
		
		if(declartion_proof_image.value!=""&&  ext3!='jpg' && ext3!='JPG' && ext3!='jpeg' && ext3!='JPEG')
		{
		$('#error_declaration').show();
		$('#error_declaration').fadeIn(300);	
		document.getElementById('error_declaration').innerHTML="Upload JPG or jpg file only.";
		setTimeout(function(){
		$('#error_declaration').css('color','#B94A48');
		document.getElementById("declarationform").value = "";
		$('#hiddendeclarationform').val('');
		//$('#error_bussiness_image').fadeOut('slow');
		},30);
		flag = 0;
		$(".declaration_proof_text").hide();
		}else if(filesize2){
		$('#error_declaration_size').show();
		$('#error_declaration_size').fadeIn(300);	
		document.getElementById('error_declaration_size').innerHTML="File size should be maximum 300KB.";
		setTimeout(function(){
		$('#error_declaration_size').css('color','#B94A48');
		document.getElementById("declarationform").value = "";
		$('#hiddendeclarationform').val('');
		//$('#error_bussiness_image').fadeOut('slow');
		},30);
		flag = 0;
		$(".declaration_proof_text").hide();
		}
		
		if(flag=='1')
		{
		$('#error_declaration_size').html('');
		$('#error_declaration').html('');
		var files = !!this.files ? this.files : [];
		if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
		
		if (/^image/.test( files[0].type)){ // only image file
		var reader = new FileReader(); // instance of the FileReader
		reader.readAsDataURL(files[0]); // read the local file
		reader.onloadend = function(){ // set image data as background of div
		$('#hiddendeclarationform').val(this.result);
		}
		}
		
		readURL(this,'image_upload_declarationform_preview');
		return true;
		}
		else
		{
		return false;
		}
	});*/

	///////////////////// Reset files code by Pratibha Borse on 24 March 22 //////////////////////

	$('#btnReset').on('click', function(e) {
		$('.mem_reg_img').attr('src', "/assets/images/default1.png");
	});
</script>

<?php $this->load->view('iibfbcbf/common/inc_lightbox_files'); ?>
<?php $this->load->view('iibfbcbf/common/inc_sweet_alert_files'); ?>
<?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>
<?php $this->load->view('iibfbcbf/common/inc_cropper_script', array('inc_fileChooser_accepted_files' => $inc_fileChooser_accepted_files, 'page_name'=>'ordinary_member_registration')); ?>

<script>
  function validate_form_images(input_id) 
  {
    $("#page_loader").show();
    
    if(input_id == 'scannedphoto') { $('#scannedphoto').parsley().reset(); }
    else if(input_id == 'scannedsignaturephoto') { $('#scannedsignaturephoto').parsley().reset(); }
    else if(input_id == 'idproofphoto') { $('#idproofphoto').parsley().reset(); }
    else if(input_id == 'declarationform') { $('#declarationform').parsley().reset(); }

    $("#page_loader").hide();
  }
</script>
