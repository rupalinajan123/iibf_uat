<style>
	.main-header { 	max-height: unset; }
	.wrapper { width: 100% !important; } 
</style>
<div class="loading" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/loading.gif"></div>
<div class="container">
	<section class="content">
		<section class="content-header">
			<h1 class="register">
				Download E-Certificate
			</h1><br />
		</section>

		<div class="col-md-12">
			<!-- Horizontal Form -->
			<div class="row">
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

				if (@$var_errors != '') { ?>
					<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
						<?php echo $var_errors; ?>
					</div>
				<?php
				}
				?>

				<div id="disp_msg_block"></div>

				<div class="box box-info">
					<form class="form-horizontal" name="usersAddForm" id="usersAddForm" method="post" action="<?php echo base_url(); ?>Download_ecertificate/getMemberDetails">
						<div class="form-group">
							<label for="roleid" class="col-sm-4 control-label">Membership/Registration no <span style="color:#F00">*</span></label>
							<div class="col-sm-4">
								<input type="text" class="form-control " id="member_no" name="member_no" data-parsley-type="number" placeholder="Membership/Registration no" value="<?php echo @$result[0]['regnumber']; ?>" <?php if (!empty($result[0]['regnumber'])) { echo "disabled='disabled'"; } ?> required data-parsley-trigger-after-failure="focusout">
								<span class="error"><?php //echo form_error('member_no'); ?></span>
							</div>
							<?php if (!empty($result[0]['regnumber'])) { ?><input type="hidden" class="form-control" name="member_no" value="<?php echo @$result[0]['regnumber']; ?>"> <?php } ?>
							<input type="hidden" class="form-control" name="verify_otp" value="<?php echo $otp_no; ?>">
							<div class="col-sm-4">
								<input type="submit" class="btn btn-info btn-sm" name="btn_Submit" id="btn_Submit" value="Get Details">&nbsp;
								<a href="<?php echo base_url(); ?>Download_ecertificate/reset" class="btn btn-default btn-sm"> Reset</a>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-12 text-center"><span style="color:#F00;">Please insert your membership/registration no and click on get details. All required details will get filled in automatically.</span></div>
						</div>
					</form>
				</div>

				<!-- form start -->
				<form class="form-horizontal" name="usersAddForm" id="usersAddForm" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>Download_ecertificate/getMemberDetails" onsubmit="return validateForm(this)" data-parsley-validate="parsley">
					<div class="box box-info">
						<div class="box-header with-border" style="background-color: #1287c0;">
							<h3 class="box-title">Basic Details</h3>
						</div>
						<!-- /.box-header -->
						<?php if (!empty($result)) { ?>
							<div class="box-body" style="margin-top: 10px;">
								<div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none">
									<span>display ajax response errors here</span>
								</div>
								<input type="hidden" class="form-control" name="member_no" value="<?php echo @$result[0]['regnumber']; ?>">
								<input type="hidden" class="form-control" name="is_dra_mem" value="<?php echo @$result[0]['is_dra_mem']; ?>">
								<input type="hidden" class="form-control" name="registrationtype" value="<?php echo @$result[0]['registrationtype']; ?>">
								<input type="hidden" class="form-control" name="verify_otp" value="<?php echo $otp_no; ?>">

								<div class="form-group">
									<label for="roleid" class="col-sm-3 control-label">Candidate Name<span style="color:#F00">*</span> </label>

									<input type="hidden" class="form-control" id="namesub" name="namesub" value="<?php echo $result[0]['namesub']; ?>" readonly="readonly">

									<div class="col-sm-3">
										<input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $result[0]['firstname']; ?>" readonly="readonly" required>
									</div>
									<div class="col-sm-3">
										<input type="text" class="form-control" id="middlename" name="middlename" value="<?php echo $result[0]['middlename']; ?>" readonly="readonly">
									</div>
									<div class="col-sm-3">
										<input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $result[0]['lastname']; ?>" readonly="readonly">
									</div>
								</div>


								<div class="form-group">
									<label for="" class="col-sm-3 control-label">Examination (select the correct name)<span style="color:#F00">*</span></label>
									<div class="col-sm-9" style="display:block">
										<?php if (!empty($result[0]['regnumber'])) {
											//print_r($exams);
										?>
											<select id="sel_exam" name="sel_exam" class="form-control" required>
												<?php if (count($exams) > 0) {
													foreach ($exams as $key => $val) { 	?>
														<option value="<?php echo $val . '##' . $key; ?>" <?php echo  set_select('sel_exam', $val); ?>><?php echo $val; ?></option>

												<?php
													}
												} ?>

											</select>



										<?php } else {
										?>
											<select class="form-control" id="state" name="state" required disabled>
												<option value="">--Select--</option>
											</select>
										<?php
										}
										?>
										<span class="error"><?php //echo form_error('designation');
															?></span>
									</div>
								</div>

								<?php /* <div class="form-group">
									<label for="roleid" class="col-sm-3 control-label">Email <span style="color:#F00">*</span></label>
									<div class="col-sm-6">
									<input type="text" class="form-control setAlg" id="email" name="email" placeholder="Email"  data-parsley-type="email" value="<?php echo $result[0]['email'] ;?>"  data-parsley-maxlength="45" required   data-parsley-trigger-after-failure="focusout">
									<span class="error"><?php //echo form_error('email');?></span>
									</div>
									</div> 
									
									<div class="form-group"> 
									<label for="roleid" class="col-sm-3 control-label">Mobile<span style="color:#F00">*</span></label>
									<div class="col-sm-6">
									<input type="tel" class="form-control setAlg" id="mobile" name="mobile" placeholder="mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10" value="<?php echo $result[0]['mobile'] ;?>"  required    data-parsley-trigger-after-failure="focusout" readonly="readonly">
									<span class="error"><?php //data-parsley-cpdmobilecheck  echo form_error('mobile');?></span>
									</div>
									</div> 	
								*/ ?>
								<input hidden="statepincode" id="statepincode" value="">
								<input type="hidden" class="form-control" name="state_ds" id="state_ds" value="<?php echo @$result[0]['state']; ?>">
							</div>
						<?php } ?>
					</div>
					<div id="send_otp_btn">
						<div class="box-footer">
							<div class="col-sm-9 col-sm-offset-3">
								<input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Download Certificate">
								<a href="<?php echo base_url(); ?>Download_ecertificate/reset" class="btn btn-default"> Reset</a>
							</div>
						</div>
					</div>
				</form>

				<div class="box box-info">
					<div class="box-header with-border " style="background-color: #1287c0;">
						<h3 class="box-title">Note:</h3>
					</div>

					<div class="box-body blue_bg">
						<div class="form-group">
							<div class="col-sm-12">
								<ol style="list-style-type: none;">
									<li> • Candidate can download copy of e-certificate only after the e-certificate is processed, digitally signed and emailed by the Institute. </li>
									<li> • E-certificate wont be available for download in case of any discrepancy found in processing (e.g. photo/signature etc.).</li>
									<li> • For any query click on the link <a href='https://iibf.esdsconnect.com/CmsComplaint' target="_blank">https://iibf.esdsconnect.com/CmsComplaint</a> and submit your Query.<br />(Query Category: 'Examination' , Query Sub-Category: 'Non receipt of Final Certificate').</li>
									<li> • After download candidate are advised to preserve the e-certificate file.</li>
									<li> • Candidate can not download e-certificate more than 3 times.</li>
									<li> • Kindly refer to the below link on steps to verify this digitally signed certificate. <br /><a href="http://iibf.org.in/documents/steps_to_open_and_validate_digitally_signed_certificate.pdf" target="_blank">http://iibf.org.in/documents/steps_to_open_and_validate_digitally_signed_certificate.pdf</a>
									</li>
								</ol>
							</div>
						</div>
					</div>
				</div><br>
			</div>
		</div>
	</section>
</div>

<link href="<?php echo base_url(); ?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
<script src="<?php echo base_url(); ?>js/validation.js?<?php echo time(); ?>"></script>

<script>
	$("#btnSubmit").on('click', function() {
		setTimeout(function() {
			window.location = "<?php echo base_url(); ?>Download_ecertificate";
		}, 1000);
	})

	function validateForm(form) {
		//var 
		var member_no = document.getElementById('member_no').value;
		//alert(member_no);
		if (member_no == "") {
			alert('First get details of member and then submit');
			document.getElementById("member_no").focus();
			return false;
		} else {

			var is_dra_mem = '<?php echo @$result[0]['is_dra_mem']; ?>';

			if (is_dra_mem != '2') {

				var firstname = form.firstname.value;
				var addressline1 = form.addressline1.value;
				var district = form.district.value;
				var city = form.city.value;
				var state = form.state.value;
				var pincode = form.pincode.value;
				//alert();
				if (firstname == "" || addressline1 == "" || district == "" || city == "" || state == "" || pincode == "") {
					$('#confirm').modal('show');
					/*var answer = confirm ("Required fields are blank !fill these fields , Please click on OK to continue.")
						if (answer)
						//window.location='<?php echo base_url(); ?>';
					window.location='<?php if (!empty($result[0]['registrationtype'])) {
											if ($result[0]['registrationtype'] == 'NM') {
												echo base_url('nonmem');
											} else {
												echo base_url();
											}
										} else {
											echo base_url();
										} ?>';*/
				}
			}
		}
	}
</script>