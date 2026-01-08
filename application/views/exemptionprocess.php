<meta http-equiv="refresh">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
		</h1>

	</section>


	<form class="form-horizontal" name="member_conApplication" id="member_conApplication" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>exemption/process/">

		<input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('regid'); ?>">
		<section class="content">
			<div class="row">

				<div class="col-md-12">

					<!-- Horizontal Form -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Basic Details</h3>
						</div>
						<!-- /.box-header -->
						<!-- form start -->
						<div class="box-body">
							<?php //echo validation_errors(); 
							?>
							<?php if ($this->session->flashdata('error') != '') { ?>
								<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
									<?php echo $this->session->flashdata('error'); ?>
								</div>
							<?php }
							if ($this->session->flashdata('success') != '') { ?>
								<div class="alert alert-success alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
									<?php echo $this->session->flashdata('success'); ?>
								</div>
							<?php }
							if (validation_errors() != '') { ?>
								<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
									<?php echo validation_errors(); ?>
								</div>
							<?php }
							?>

							<div class="form-group">
								<label for="roleid" class="col-sm-3 control-label">Membership No</label>
								<div class="col-sm-1">
									<?php echo $user_info[0]['regnumber'];
									$fee_amount = $grp_code = ''; ?>

									<input type="hidden" name="reg_no" id="reg_no" value="<?php echo $user_info[0]['regnumber']; ?>">
									<input type="hidden" name="extype" id="extype" value="<?php echo $examinfo[0]['exam_type']; ?>">
									<input type="hidden" id="exname" name="exname" value=" <?php echo $examinfo[0]['description']; ?>">
									<input type="hidden" id="excd" name="excd" value="<?php echo base64_encode($this->session->userdata('examcode')); ?>">
									<input id="examcode" name="examcode" type="hidden" value="<?php echo $this->session->userdata('examcode'); ?>">
									<input id="eprid" name="eprid" type="hidden" value="<?php echo $examinfo[0]['exam_period']; ?>">
									<input id="fee" name="fee" type="hidden" value="">
									<input type='hidden' name='mtype' id='mtype' value="<?php echo $this->session->userdata('memtype') ?>">
									<?php
									$grp_code = 'B1_1';
									if(!empty($eligiblity_details) && isset($eligiblity_details[0]['app_category'])) {
										$grp_code = $eligiblity_details[0]['app_category'];
									}
									?>
									<input id="grp_code" name="grp_code" type="hidden" value="<?php echo trim($grp_code); ?>">
								</div>
							</div>


							<div class="form-group">
								<label for="roleid" class="col-sm-3 control-label">First Name </label>
								<div class="col-sm-3">
									<?php echo $user_info[0]['firstname']; ?>
									
								</div>

							</div>

							<div class="form-group">
								<label for="roleid" class="col-sm-3 control-label">Middle Name</label>
								<div class="col-sm-5">
									<?php echo $user_info[0]['middlename']; ?>
									
								</div><!--(Max 30 Characters) -->
							</div>


							<div class="form-group">
								<label for="roleid" class="col-sm-3 control-label">Last Name</label>
								<div class="col-sm-5">
									<?php echo $user_info[0]['lastname']; ?>
									
								</div><!--(Max 30 Characters) -->
							</div>


							<div class="form-group">
								<label for="roleid" class="col-sm-3 control-label">Phone : STD Code </label>
								<div class="col-sm-2">
									<?php echo $user_info[0]['stdcode']; ?>
									<?php echo $user_info[0]['office_phone']; ?>
									
								</div>

							</div>

							<div class="form-group">
								<label for="roleid" class="col-sm-3 control-label">Mobile <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
								<?php echo $user_info[0]['mobile']; ?>
									<input type="hidden" name="" id="mobile_hidd" value="<?php echo $user_info[0]['mobile']; ?>">
									
								</div>
							</div>


							<div class="form-group">
								<label for="roleid" class="col-sm-3 control-label">Email <span style="color:#F00">*</span></label>
								<div class="col-sm-5">
									<?php echo $user_info[0]['email']; ?>
									<input type="hidden" name="" id="email_hidd" value="<?php echo $user_info[0]['email']; ?>">
									
								</div>
							</div>
						</div>

					</div> <!-- Basic Details box closed-->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Exam Details:</h3>
						</div>


						<div class="box-body">
							<div class="form-group">
								<label for="roleid" class="col-sm-3 control-label">Exam Name</label>
								<div class="col-sm-5 ">
									<?php echo $examinfo['0']['description']; ?>
									<div id="error_dob"></div>
									<br>
									<div id="error_dob_size"></div>
									<span class="dob_proof_text" style="display:none;"></span>
									<span class="error"><?php //echo form_error('idproofphoto');
																			?></span>
								</div>
							</div>



							<div class="form-group">
								<label for="roleid" class="col-sm-3 control-label">Fee Amount</label>
								<div class="col-sm-5 " id="html_fee_id">
									<div style="color:#F00">select center first</div>
									
									<div id="error_dob"></div>
									<br>
									<div id="error_dob_size"></div>
									<span class="dob_proof_text" style="display:none;"></span>
									<span class="error"><?php //echo form_error('idproofphoto');
																			?></span>
								</div>
							</div>

							<div class="form-group">
								<label for="roleid" class="col-sm-3 control-label">Exam Period</label>
								<div class="col-sm-5 ">
									<?php
									
									$month = date('Y') . "-" . substr($examinfo['0']['exam_month'], 4);
									echo date('F', strtotime($month)) . "-" . substr($examinfo['0']['exam_month'], 0, -2);
									
									?>
									
									<div id="error_dob"></div>
									<br>
									<div id="error_dob_size"></div>
									<span class="dob_proof_text" style="display:none;"></span>
									<span class="error"></span>
								</div>
							</div>


							<div class="form-group">
								<label for="roleid" class="col-sm-3 control-label">Centre Name <span style="color:#F00">*</span></label>
								<div class="col-sm-4">
									<select name="selCenterName" id="selCenterName" class="form-control" required onchange="valCentre(this.value);">
										<option value="">Select</option>
										<?php if (count($center) > 0) {

											foreach ($center as $crow) { ?>
												<option value="<?php echo $crow['center_code'] ?>" class=<?php echo $crow['exammode']; ?>><?php echo $crow['center_name'] ?></option>
										<?php }
										} ?>
									</select>
									<input type="hidden" name="txtCenterCode" id="txtCenterCode"  class="form-control pull-right" readonly="readonly"
                       value="">
								</div>
							</div>

							<div class="form-group" style="display:none;"> 
								<label for="roleid" class="col-sm-3 control-label">Do you want to apply for elearning ? </label> 
								<div class="col-sm-3">
									<input type="radio" name="elearning_flag" id="elearning_flag_Y" value="Y" <?php echo $checked; ?>>YES
									<input type="radio" name="elearning_flag" id="elearning_flag_N" value="N" <?php echo $checked1; ?>>NO
								</div>
							</div>
							<div class="box-footer">
								<div class="col-sm-12 text-center">
								
									<input type="submit" class="btn btn-info" name="btnPaySubmit" id="btnPaySubmit" value="Pay Now" onclick="javascript:return loginusercheckform();">

								
								</div>
							</div>
						</div>
					</div>
				</div>


			</div>
		</section>


	</form>
</div>


<script>
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

		$("form#member_conApplication").submit(function() {

			if (!confirm('Please check your application details carefully before proceeding for payment ')) {

				return false;
			}

			setCookie('sotredPreivousValues', 1);
			//alert(getCookie('sotredPreivousValues'));
			var currform = $(this);
			currform.find('input').each(function() {

				var setcookiename = $(this).attr('name');
				var setcookieval = $(this).val();
				setCookie(setcookiename, setcookieval);
			});
			currform.find('input[type="radio"]:checked').each(function() {
				//if($(this).is(':checked')) {
				var setcookiename = $(this).attr('name');
				var setcookieval = $(this).val();
				setCookie(setcookiename, setcookieval);
				//}
				//	alert(setcookiename+'=='+getCookie(setcookiename));
			});
			currform.find('select').each(function() {

				var setcookiename = $(this).attr('name');
				var setcookieval = $(this).val();
				setCookie(setcookiename, setcookieval);

			});
			currform.find('input[type="checkbox"]').each(function() {
				var setcookiename = $(this).attr('name');
				setCookie(setcookiename, '');
				if ($(this).is(':checked')) {

					var setcookieval = $(this).val();
					setCookie(setcookiename, setcookieval);
					//alert(setcookiename+'=='+getCookie(setcookiename));
				}
			});
		});



	});



	var base_url = '<?php echo base_url(); ?>'

	
</script>