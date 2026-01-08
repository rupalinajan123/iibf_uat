<?php 
	header('Cache-Control: must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
<style>
	.date {
	width: 43% !important;
	}
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
	label{
	font-weight:bold !important;
	}
	.box-body ul li {float: left;}
	
	
	
	td.subject_checkbox input {
    position: absolute;
    right: 0;
    top: 50%;
    height: 50px;
    margin-top: -25px;
}

td.subject_checkbox {
    position: relative;
    padding-right: 25px !important;
    line-height: 20px;
}
</style>
<div id="confirmBox">
  <div class="message" style="color:#F00"> <strong>VERY IMPORTANT</strong> I confirm that the  Photo, Signature images  uploaded belongs to me and they are clear and readable.</div>
<span class="button yes">Confirm</span> <span class="button no">Cancel</span> </div>
<form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data">
	<input type="hidden" id="position_id" name="position_id" value="13">
  <div class="container">
		<section class="content-header" style="padding-left:0;padding-right:0;">
			<h1 class="register">Subject Matter Expert (SME) Empanelment Form</h1>
		</section>
		<section class="content">
			<div class="row">
				<div class="col-md-12">
					<h4> </h4>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<!-- Horizontal Form -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">BASIC DETAILS</h3>
						</div>
						<!-- form start -->
						<?php //echo validation_errors(); ?>
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
							<?php 
							} 
						?>
						<div class="box-body">
							<div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none"> <span>display ajax response errors here</span> </div>
							
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Name<span style="color:#F00">*</span></label>
								<div class="col-sm-5">
								<input type="text" class="form-control" id="name" name="name" placeholder="Name" required 
									value="<?php echo set_value('name');?>" data-parsley-pattern="/^[a-zA-Z/ ]+$/" data-parsley-maxlength="50" maxlength="50">
								<span class="error"></span> </div>
							(Max 50 Characters) </div>
														
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Date of Birth <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="head_pdc_nz_dobs" name="head_pdc_nz_dobs" placeholder="Date of Birth"  value="<?php echo set_value('head_pdc_nz_dobs');?>" required data-parsley-trigger-after-failure="focusout">
									<?php 
										$min_year = date('Y', strtotime("- 55 year"));
										$max_year = date('Y', strtotime("- 62 year"));
									?>

										<!-- <input type="hidden" id="head_pdc_nz_dob" name="dob" required value="<?php echo $dob;?>"> -->
										<span id="head_pdc_nz_dobs_error" class="error"></span> </div>
										<span class="error"></span> </div>
							
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Educational Qualification<span style="color:#F00">*</span></label>
								<div class="col-sm-5">
								<!-- <input type="text" class="form-control" id="educational_qualification" name="educational_qualification" placeholder="Educational Qualification" required 
									value="<?php echo set_value('educational_qualification');?>" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" data-parsley-maxlength="50" maxlength="50"> -->
									<input type="text" class="form-control" id="educational_qualification" name="educational_qualification" placeholder="Educational Qualification" required 
									value="<?php echo set_value('educational_qualification');?>" data-parsley-maxlength="50" maxlength="50">
								<span class="error"></span> </div>
							(Max 50 Characters) </div>
							
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">CAIIB Qualification <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="radio" class="minimal cls_physical_disbaility cls_physical_disbaility_no" checked="checked" id="no"   name="CAIIB_qualification"  required value="no" <?php echo set_radio('CAIIB_qualification', 'no'); ?>>
									No
									<input type="radio" class="minimal cls_physical_disbaility cls_physical_disbaility_yes" id="yes"  name="CAIIB_qualification"  required value="yes" <?php echo set_radio('CAIIB_qualification', 'yes'); ?>>
								Yes <span class="error"></span> </div>
							</div>

							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Address line1 <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" value="<?php echo set_value('addressline1');?>"  data-parsley-maxlength="50" maxlength="50" required>
								<span class="error"> </span> </div>
							(Max 50 Characters) </div>
							
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Address line2 <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2"  value="<?php echo set_value('addressline2');?>"  data-parsley-maxlength="50" maxlength="50" required>
								<span class="error"> </span> </div>
							(Max 50 Characters) </div>

							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">City <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo set_value('city');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="25" maxlength="25">
								<span class="error"> </span> </div>
							(Max 25 Characters) </div>

							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">State <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<select class="form-control" id="state" name="state" required onchange="javascript:checksate(this.value)">
										<option value="">Select</option>
										<?php if(count($states) > 0){
											foreach($states as $row1){ 	?>
											<option value="<?php echo $row1['state_code'];?>" <?php echo  set_select('state', $row1['state_code']); ?>><?php echo $row1['state_name'];?></option>
										<?php } } ?>
									</select>
									<input hidden="statepincode" id="statepincode" value="">
								</div>
							</div>

							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Pincode <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode/Zipcode" required value="<?php echo set_value('pincode');?>"  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-type="number" data-parsley-trigger-after-failure="focusout" >
									<span style="display: inline-block;width: 100%;">(Max 6 digits)</span> <span class="error"> </span> 
								</div>
							</div>	

							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Mobile No.<span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number" maxlength="10" data-parsley-minlength="10" data-parsley-minlength-message="This value is too short. It should have 10 digits."  value="<?php echo set_value('mobile');?>" required onfocusout="return is_mobile_no(event)" >
								<span class="error" id="mobile_no_error"></span> </div>
							</div>

							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Email Id<span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="email" name="email" placeholder="Email Id"  data-parsley-type="email" value="<?php echo set_value('email');?>"  data-parsley-maxlength="40" required onfocusout="return is_email(event)" >
								<span class="error" id="email_error"> </span> </div>
							</div>

							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Bank/Educational Institute <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="radio" class="minimal cls_physical_disbaility cls_physical_disbaility_no" checked="checked" id="bank"   name="bank_education"  required value="bank" <?php echo set_radio('bank_education', 'bank'); ?>>
									Bank
									<input type="radio" class="minimal cls_physical_disbaility cls_physical_disbaility_yes" id="education"  name="bank_education"  required value="education" <?php echo set_radio('bank_education', 'education'); ?>>
								Education Institute 
								
								<input type="radio" class="minimal cls_physical_disbaility cls_physical_disbaility_yes" id="others"  name="bank_education"  required value="others" <?php echo set_radio('bank_education', 'others'); ?>>
								Others
								<span class="error"></span> </div>
							</div>

							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Organization Name <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="organisation_name" name="organisation_name" placeholder="Organization Name" value="<?php echo set_value('organisation_name');?>"  data-parsley-maxlength="25" maxlength="25" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" required>
									<span class="error"> </span> </div>
								(Max 25 Characters) </div>

							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Retired/Working <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="radio" class="minimal cls_physical_disbaility cls_physical_disbaility_no" checked="checked" id="retired"   name="retired_working"  required value="retired" <?php echo set_radio('retired_working', 'retired'); ?>>
									Retired
									<input type="radio" class="minimal cls_physical_disbaility cls_physical_disbaility_yes" id="working"  name="retired_working"  required value="working" <?php echo set_radio('retired_working', 'working'); ?>>
								Working <span class="error"></span> </div>
							</div>

							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Total year of Work experience<span style="color:#F00">*</span></label>
								<div class="col-sm-7">
									<div class="col-sm-3" style="padding:0">
										<input type="text" class="form-control" id="year" name="year" placeholder="Year" data-parsley-maxlength="2" maxlength="2" size="2" data-parsley-type="number" data-parsley-trigger-after-failure="focusout" value="<?php echo set_value('year');?>">
									</div>
									<div class="col-sm-4" >
										<input type="text" class="form-control" id="month" name="month" placeholder="Month" data-parsley-maxlength="2" maxlength="2" size="2" data-parsley-type="number" data-parsley-trigger-after-failure="focusout" value="<?php echo set_value('month');?>">
									</div>
								</div>
							</div>

							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Designation <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="designation" name="designation" placeholder="Designation"  value="<?php echo set_value('designation');?>" data-parsley-maxlength="25" maxlength="25" data-parsley-pattern="/^[a-zA-Z/ ]+$/" required>
								<span class="error"> </span> </div>
							(Max 25 Characters) </div>
							

							<div class="form-group">
								<label for="roleid" class="col-sm-5 control-label">Subject/(s) of Expertise/Interest <span style="color:#F00">*</span></label>
								<div class="col-sm-12">&nbsp;</div>
								<div class="col-sm-2">&nbsp;</div>
								
								<div class="col-sm-9">
									<input type="file" name="uploadcv" id="uploadcvs" required>
									<a download href="<?php echo base_url(); ?>/uploads/Brief-resume-upload.docx">Sample Format</a>
									<input type="hidden" id="hiddenuploadcv" name="hiddenuploadcv">
									<div id="error_uploadcv"></div>
									
									<div id="error_uploadcv_size" class="parsley-required parsley-errors-list filled"></div>
									<span class="uploadcv_text" style="display:none;"></span> <span class="error">
									</span> ( Kindly upload details in PDF (max size 300KB) as per format attached) </div>
								<div class="col-sm-12" style="padding:0;">
									<table>
										<thead style="background-color:#1287c0;color:#fff;">
											<td style="text-align:left;line-height: 18px;padding: 5px;"> <b>General Banking<br/>Subjects <span style="color:#F00;">*</span></b></td>
											<td style="text-align:left;line-height: 18px;padding: 5px;"><b>Specialised Banking<br/>Subjects <span style="color:#F00">*</span></b></td>
											<td style="text-align:left;line-height: 18px;padding: 5px;"><b>Information Technology<br/>Subjects <span style="color:#F00">*</span></b></td>
											<td style="text-align:left;line-height: 18px;padding: 5px;"><b>Other Banking<br/>Subjects <span style="color:#F00">*</span></b></td>
										</thead>
										<tbody>
											<tr>
												<td style="text-align:left;" class="subject_checkbox">
													1. AML/KYC <input type="checkbox" name="general[]" value="AML/KYC"> 
												</td>
												<td style="text-align:left;" class="subject_checkbox">
													1.	Auditing & Accounting <input type="checkbox" name="specialised[]" value="Auditing & Accounting">
												</td>
												<td style="text-align:left;" class="subject_checkbox">
													1. Digital Banking	<input type="checkbox" name="it[]" value="Digital Banking">
												</td>
												<td style="text-align:left;" class="subject_checkbox">
													1.	Non-banking Finance Company <input type="checkbox" name="other[]" value="Non-banking Finance Company">
												</td>
											</tr>
											<tr>
												<td style="text-align:left;" class="subject_checkbox">
													2.	Banking Regulations & Business Laws <input type="checkbox" name="general[]" value="Banking Regulations & Business Laws"> 	
												</td>
												<td style="text-align:left;" class="subject_checkbox">
													2. Climate Risk & Sustainable Finance	<input type="checkbox" name="specialised[]" value="Climate Risk & Sustainable Finance">	
												</td>
												<td style="text-align:left;" class="subject_checkbox">
													2.	Emerging Technologies	<input type="checkbox" name="it[]" value="Emerging Technologies">	
												</td>
												<td style="text-align:left;" class="subject_checkbox">
													2.	Urban Co-operative Banking <input type="checkbox" name="other[]" value="Urban Co-operative Banking">
												</td>
											</tr>
											<tr>
												<td style="text-align:left;" class="subject_checkbox">
													3.	Central Banking <input type="checkbox" name="general[]" value="Central Banking">	
												</td>
												<td style="text-align:left;" class="subject_checkbox">
													3. Compliance in Banking	<input type="checkbox" name="specialised[]" value="Compliance in Banking"> 	
												</td>
												<td style="text-align:left;" class="subject_checkbox">
													3.	Information Technology <input type="checkbox" name="it[]" value="Information Technology">	
												</td>
												<td style="text-align:left;" class="subject_checkbox">
														
												</td>
											</tr>
											<tr>
												<td style="text-align:left;" class="subject_checkbox">
													4.	Ethics <input type="checkbox" name="general[]" value="Ethics">	
												</td>
												<td style="text-align:left;" class="subject_checkbox">
													4. Credit Management (including MSME) <input type="checkbox" name="specialised[]" value="Credit Management (including MSME)">	
												</td>
												<td style="text-align:left;" class="subject_checkbox">
													4. IT Security	<input type="checkbox" name="it[]" value="IT Security">	
												</td>
												<td style="text-align:left;">
															
												</td>
											</tr>
											<tr>
												<td style="text-align:left;" class="subject_checkbox">
													5.	Financial Management <input type="checkbox" name="general[]" value="Financial Management">	
												</td>
												<td style="text-align:left;" class="subject_checkbox">
													5. Human Resources Management	<input type="checkbox" name="specialised[]" value="Human Resources Management">	
												</td>
												<td style="text-align:left;" class="subject_checkbox">
													5. Prevention of Cyber Crimes & Fraud Management	<input type="checkbox" name="it[]" value="Prevention of Cyber Crimes & Fraud Management">
												</td>
												<td style="text-align:left;" class="subject_checkbox">		
												</td>
											</tr>
											<tr>
												<td style="text-align:left;" class="subject_checkbox">
													6.	Financial System <input type="checkbox" name="general[]" value="Financial System">
												</td>
												<td style="text-align:left;" class="subject_checkbox">
													6. Insolvency & Bankruptcy Code	<input type="checkbox" name="specialised[]" value="Insolvency & Bankruptcy Code">
												</td>
												<td style="text-align:left;" class="subject_checkbox">
													6. Systems Audit	<input type="checkbox" name="it[]" value="Systems Audit">	
												</td>
												<td style="text-align:left;" class="subject_checkbox">
															
												</td>
											</tr>
											<tr>
												<td style="text-align: left;" class="subject_checkbox">
													7.	Indian Economy <input type="checkbox" name="general[]" value="Indian Economy">	
												</td>
												<td style="text-align:left;" class="subject_checkbox">
													7. International Banking & Forex Operations	<input type="checkbox" name="specialised[]" value="International Banking & Forex Operations">	
												</td>
												<td style="text-align:left;" class="subject_checkbox">	
												</td>
												<td style="text-align:left;" class="subject_checkbox">
															
												</td>
											</tr>
											<tr>
												<td style="text-align:left;" class="subject_checkbox">
													8.	Microfinance <input type="checkbox" name="general[]" value="Microfinance">	
												</td>
												<td style="text-align:left;" class="subject_checkbox">
													8. Risk Management	<input type="checkbox" name="specialised[]" value="Risk Management">	
												</td>
												<td style="text-align:left;" class="subject_checkbox">
															
												</td>
												<td style="text-align:left;" class="subject_checkbox">
															
												</td>
											</tr>
											<tr>
												<td style="text-align:left;" class="subject_checkbox">
													9.	Principles of Banking	<input type="checkbox" name="general[]" value="Principles of Banking">
												</td>
												<td style="text-align:left;" class="subject_checkbox">
													9. Strategic Management	<input type="checkbox" name="specialised[]" value="Strategic Management">	
												</td>
												<td style="text-align:left;" class="subject_checkbox">
															
												</td>
												<td style="text-align:left;" class="subject_checkbox">
															
												</td>
											</tr>
											<tr>
												<td style="text-align:left;" class="subject_checkbox">
													10. Retail Banking	<input type="checkbox" name="general[]" value="Retail Banking">
												</td>
												<td style="text-align:left;" class="subject_checkbox">
													10. Treasury Management	<input type="checkbox" name="specialised[]" value="Treasury Management">	
												</td>
												<td style="text-align:left;" class="subject_checkbox">
															
												</td>
												<td style="text-align:left;" class="subject_checkbox">
															
												</td>
											</tr>
											<tr>
												<td style="text-align:left;" class="subject_checkbox">
													11. Rural Banking	<input type="checkbox" name="general[]" value="Rural Banking">
												</td>
												<td style="text-align:left;">
															
												</td>
												<td style="text-align:left;">
															
												</td>
												<td style="text-align:left;">
															
												</td>
											</tr>
											<tr>
												<td style="text-align:left;" class="subject_checkbox">
													12.	Statistics <input type="checkbox" name="general[]" value="Statistics">	
												</td>
												<td style="text-align:left;">
															
												</td>
												<td style="text-align:left;">
															
												</td>
												<td style="text-align:left;">
															
												</td>
											</tr>
											<tr>
												<td style="text-align:left;" class="subject_checkbox">
													13. Trade Finance <input type="checkbox" name="general[]" value="Trade Finance">	
												</td>
												<td style="text-align:left;">
															
												</td>
												<td style="text-align:left;">
															
												</td>
												<td style="text-align:left;">
															
												</td>
											</tr>
											<tr>
												<td style="text-align:left;" class="subject_checkbox">
													14.	Wealth Management <input type="checkbox" name="general[]" value="Wealth Management">	
												</td>
												<td style="text-align:left;">
															
												</td>
												<td style="text-align:left;">
															
												</td>
												<td style="text-align:left;">
															
												</td>
											</tr>
										</tbody>
									</table>		
									<span class="error"> </span> 
								</div>
							 </div>

							 	


							<div class="box-footer text-center">
								<div class="col-sm-12"> <a href="javascript:void(0);" class="btn btn-info" onclick="javascript:return checkform();" id="preview">Preview and Proceed</a>
									<button type="reset" class="btn btn-default"  name="btnReset" id="btnReset" onClick="window.location.reload()">Reset</button>
								</div>
							</div>

						</div>
					</div>
				</div>
			</section>
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
							I confirm that the  Photo, Signature images  uploaded belongs to me and they are clear and readable.<br />
							<br />
						</p>
					</div>
					<div class="modal-footer">
						<input type="submit" name="btnSubmit" class="btn btn-primary" id="btnSubmit" value="Confirm">
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
	</form>
	<script type="text/javascript">var smevalidation = 'yes';</script>
	<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
	<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
	<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
	<script src="<?php echo base_url();?>js/careers_validation.js?<?php echo time(); ?>"></script>
	<script>

		function is_mobile_no(evt) 
	  {
	    var mobile   = evt.target.value;
	    var position_id = $('#position_id').val();
	    var datastring='mobile='+mobile+'&position_id='+position_id;
	    $.ajax({
	      url:site_url+'sme/mobileduplication/',
				data: datastring,
				type:'POST',
				dataType:'json',
	      	async: false,
	      success: function(response) {
	        if (response.status) {
	          $('#mobile_no_error').html('The mobile number already exists.');
	          return true;
	        } else {
	          $('#mobile_no_error').html('');
	          return false;
	        }
	      }
	    });
	  }

	  function is_email(evt) 
	  {
	    var email   = evt.target.value;
	    var position_id = $('#position_id').val();
	    var datastring='email='+email+'&position_id='+position_id;
	    $.ajax({
	      url:site_url+'sme/emailduplication/',
				data: datastring,
				type:'POST',
				dataType:'json',
				async: false,
	      success: function(response) {
	        if (response.status) {
	          $('#email_error').html('The email is already exists.');
	          return true;
	        } 
	        else {
	          $('#email_error').html('');
	          return false;
	        }
	      }
	    });
	  }


		 $('#usersAddForm').submit(function(e) {
        // Check if at least one checkbox is selected in each column
        if ($('input[name^="general[]"]:checked').length === 0 && 
            $('input[name^="specialised[]"]:checked').length === 0 && 
            $('input[name^="it[]"]:checked').length === 0 && 
            $('input[name^="other[]"]:checked').length === 0) {
            e.preventDefault(); // Prevent the form submission
            alert("Please select at least one checkbox in subject column.");
        }
    });


		$( "#uploadcvs" ).change(function() 
		{					 
			//var filesize1=this.files[0].size/1024<8;
			var filesize2=this.files[0].size/1024>300;
			
			var flag = 1;
	 		var file, img;
			$('#p_photograph').hide();
			var photograph_image=document.getElementById('uploadcvs');
			//fileUpload[appKey]['photo'] = photograph_image;
			var photograph_im=photograph_image.value;
			var ext1=photograph_im.substring(photograph_im.lastIndexOf('.')+1);
			if(photograph_image.value!=""&&  ext1!='pdf' && ext1!='PDF' )
			{
				$('#error_uploadcv').show();
				$('#error_uploadcv').fadeIn(3000);	
				document.getElementById('error_uploadcv').innerHTML="Upload PDF file only.";
				setTimeout(function(){
					$('#error_uploadcv').css('color','#B94A48');
					document.getElementById("uploadcvs").value = "";
					$('#hiddenuploadcv').val('');
				},30);
			
				flag = 0;
				$(".uploadcv_text").hide();
			}
			else if(filesize2)
			{
				$('#error_uploadcv_size').show();
				$('#error_uploadcv_size').fadeIn(3000);	
				document.getElementById('error_uploadcv_size').innerHTML="The file uploaded in CV is too big (max size is 300).";
				setTimeout(function(){
					$('#error_uploadcv_size').css('color','#B94A48');
					//$('#error_bussiness_image').fadeOut('slow');
					document.getElementById("uploadcvs").value = "";
					$('#hiddenuploadcv').val('');
				},30);
				flag = 0;
				$(".uploadcv_text").hide();
			}
			if(flag==1)
			{
				$('#error_uploadcv').html('');
				$('#error_uploadcv_size').html('');
				var files = !!this.files ? this.files : [];
				if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
				if (/^image/.test( files[0].type)){ // only image file
					var reader = new FileReader(); // instance of the FileReader
					reader.readAsDataURL(files[0]); // read the local file
					reader.onloadend = function(){ // set image data as background of div
						$('#hiddenuploadcv').val(this.result);
					}
				}
				 readURL(this,'image_upload_uploadcv_preview');
				return true;
			}
			else
			{
				return  false;
			}
		});

		const today = new Date();
		const eighteenYearsAgo = new Date(today);
		eighteenYearsAgo.setFullYear(today.getFullYear() - 18);
		// alert(eighteenYearsAgo);

		$('#head_pdc_nz_dobs').datepicker({
			keyboardNavigation: true,
		  forceParse: true,
		  autoclose: true,
		  format: "yyyy-mm-dd",
		  endDate: eighteenYearsAgo
		}).on('changeDate', function (e) {
		    $('#head_pdc_nz_dobs').parsley().validate();
		    if ($('#head_pdc_nz_dobs').parsley().isValid()) {
		        // Date is valid, clear any error messages or classes.
		    } else {
		        // Date is invalid, show an error message or add error styling.
		    }
		});

		// $('#head_pdc_nz_dobs').datepicker({
		//   keyboardNavigation: true,
		//   forceParse: true,
		//   autoclose: true,
		//   format: "yyyy-mm-dd",
		//   endDate: eighteenYearsAgo
		// });

		function isNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
      }
      return true;
    }

		$(document).ready(function() 
		{

		var dtable = $('.dataTables-example').DataTable();

var dt = new Date();
dt.setFullYear(new Date().getFullYear()-18);

if($('#hiddenuploadcv').val()!='')
{
	$('#image_upload_uploadcv_preview').attr('src', $('#hiddenuploadcv').val());
}

});

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
		
		$("body").on("contextmenu",function(e){
			return false;
		});
		
    $(this).scrollTop(0);
	});
</script>
