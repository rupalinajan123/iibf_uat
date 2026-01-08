<!DOCTYPE html>
<html>
  <head>
    <?php $this->load->view('ncvet/inc_header'); ?>
  </head>
  
  <style>
/*body {
    font-family: Arial, sans-serif;
    padding: 40px;
}*/

/*.form-group {
    position: relative;
    margin-bottom: 80px;
    width: 300px;
}*/

.input-field {
    width: 100%;
    padding: 8px;
    font-size: 16px;
}

/* Tooltip Box */
.tooltip {
    position: absolute;
    left: 0;
    bottom: 102%; /* place above input */
    background: #fff;
    color: #cc5965;
    border: 1px solid #cc5965;
    padding: 8px 12px;
    font-size: 13px;
    border-radius: 4px;
    display: none;

    /* Enable wrapping */
    white-space: normal;
    word-wrap: break-word;
    overflow-wrap: break-word;

    width: 93%;
    max-width: 300px; /* optional */
    min-height: 30px; /* optional */
}


/* ▼ Tooltip Arrow */
.tooltip::after {
    content: "";
    position: absolute;
    top: 100%; /* arrow at the bottom of tooltip */
    left: 20px; /* adjust arrow position */
    border-width: 6px;
    border-style: solid;
    border-color: #cc5965 transparent transparent transparent;
}

/* ▼ Tooltip Arrow Border (for outline) */
.tooltip::before {
    content: "";
    position: absolute;
    top: 100%;
    left: 20px;
    border-width: 7px;
    border-style: solid;
    border-color: #cc5965 transparent transparent transparent;
}
</style>

  <body class="gray-bg">
    <?php $this->load->view('ncvet/common/inc_loader'); ?>
    <div class="d-flex logo" style="z-index:1;"><img src="<?php echo base_url('assets/ncvet/images/iibf_logo.png'); ?>" class="img-fluid" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">   <h3 class="mb-0" style="    font-size: 20px;">INDIAN INSTITUTE OF BANKING & FINANCE
								<br>
								ISO 21001:2018 Certified
	</h3></div>
    <div class="container">        
    <?php $mode ='Add'; ?>
      <div class="admin_login_form animated fadeInDown" style="width: 100%; max-width: none; margin-top:110px"> 
                  <form method="post" action="<?php echo site_url('ncvet/candidate_registration'); ?>" id="add_candidate_form" enctype="multipart/form-data" 		autocomplete="off">
						<h3 style="text-align: center;margin-bottom: 2%;" class="col-xl-12 col-lg-12" >NCVET – Admission cum Enrollment Form (Fundamentals of Retail Banking)</h3>
						<i style="text-align: center;" class="col-xl-12 col-lg-12">Candidates are requested to go through the detailed Rules and Syllabus available at <a href="https://www.iibf.org.in/NCVET.asp">https://www.iibf.org.in/NCVET.asp</a> before filling up/submitting the enrollment form.</i>
						<input type="hidden" name="mode" id="mode" value="<?php echo $mode; ?>">
						<input type="hidden" name="form_action" id="form_action" value="">
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
						
						<h4 class="custom_form_title" style="margin: 3px -20px 15px -20px !important;">Basic Details</h4>
					
						<?php 
		
						$salutation_master_arr = array('Mr.', 'Mrs.', 'Ms.');
						$guardian_salutation_master_arr = array('Mr.','Ms.', 'Mrs.');
						
						$qualification_arr = $this->config->item('ncvet_qualification_arr');
						$graduation_sem_arr = $this->config->item('ncvet_graduation_sem_arr');

						$post_graduation_sem_arr = $this->config->item('ncvet_post_graduation_sem_arr');
						
							?>
										
                      	<div class="row">
                  
                        
							<div class="col-xl-3 col-lg-3"><?php /* Candidate Name (Salutation) */ ?>
							<div class="form-group">
								<?php 
								$chk_salutation = set_value('salutation');
															?>
								<label for="salutation" class="form_label">Candidate Name (Salutation) <sup class="text-danger">*</sup></label>
									<select name="salutation" id="salutation" class="form-control basic_form" required onchange="show_hide_gender(); ">
										<?php if(count($salutation_master_arr) > 0)
											{ ?>
											<option value="">Select Salutation *</option>
											<?php foreach($salutation_master_arr as $sal_val)
											{ ?>
											<option value="<?php echo $sal_val; ?>" <?php if($chk_salutation == $sal_val) { echo 'selected'; } ?>><?php echo $sal_val; ?></option>
											<?php }
											} ?>
									</select>
									<note class="form_note" id="salutation_err"></note>
								<?php if(form_error('salutation')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('salutation'); ?></label> <?php } ?>
														</div>					
													</div>
							
							<div class="col-xl-3 col-lg-3"><?php /* First Name */ ?>
								<div class="form-group">
									<label for="first_name" class="form_label">First Name <sup class="text-danger">*</sup></label>
									<input onchange="createfullname()" onkeyup="createfullname(); " onblur="createfullname()" type="text" name="first_name" id="first_name" value="<?php if($mode == "Add") { echo set_value('first_name'); } else { echo $form_data[0]['first_name']; } ?>" placeholder="First Name *" class="form-control custom_input allow_only_alphabets_and_space basic_form input-field" maxlength="20" required/>
									<div class="tooltip">Kindly enter your name exactly as it appears on your Aadhaar card.</div>
									<!-- <note class="form_note" id="first_name_err">Note: Please enter only 20 characters</note> -->
									<note class="form_note" id="first_name_err"></note>
									
									

									<?php if(form_error('first_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('first_name'); ?></label> <?php } ?>
								</div>					
							</div>
							
							<div class="col-xl-3 col-lg-3"><?php /* Middle Name */ ?>
							<div class="form-group">
								<label for="middle_name" class="form_label">Middle Name <sup class="text-danger"></sup></label>
								<input onchange="createfullname()" onkeyup="createfullname(); " type="text" name="middle_name" id="middle_name" value="<?php if($mode == "Add") { echo set_value('middle_name'); } else { echo $form_data[0]['middle_name']; } ?>" placeholder="Middle Name" class="form-control custom_input allow_only_alphabets_and_space basic_form input-field" maxlength="20"/>
								<div class="tooltip">Kindly enter your name exactly as it appears on your Aadhaar card.</div>
								<!-- <note class="form_note" id="middle_name_err">Note: Please enter only 20 characters</note> -->
								
								<note class="form_note" id="first_name_err"></note>

								<?php if(form_error('middle_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('middle_name'); ?></label> <?php } ?>
							</div>					
							</div>
							
							<div class="col-xl-3 col-lg-3"><?php /* Last Name */ ?>
								<div class="form-group">
									<label for="last_name" class="form_label">Last Name <sup class="text-danger"></sup></label>
									<input onchange="createfullname()" onkeyup="createfullname(); " type="text" name="last_name" id="last_name" value="<?php if($mode == "Add") { echo set_value('last_name'); } else { echo $form_data[0]['last_name']; } ?>" placeholder="Last Name" class="form-control custom_input allow_only_alphabets_and_space basic_form input-field" maxlength="20"/>
									<div class="tooltip">Kindly enter your name exactly as it appears on your Aadhaar card.</div>
									<!-- <note class="form_note" id="last_name_err">Note: Please enter only 20 characters</note> -->
									<note class="form_note" id="first_name_err"></note>
									<?php if(form_error('last_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('last_name'); ?></label> <?php } ?>
								</div>					
							</div>
							<div class="col-xl-3 col-lg-3"> </div>
							<div class="col-xl-9 col-lg-9">
								<div class="form-group">
								<note class="form_note" id="first_name_err">Note:<br> 1. Kindly enter your name exactly as it appears on your Aadhaar card. <br> 2. Please ensure the First, Middle, and Last Name fields each have no more than 20 characters.</note>
							</div>
							</div>	

							<div class="col-xl-3 col-lg-3"><?php /* Gender */ ?>
								<div class="form-group">
									<?php 
									$chk_gender = set_value('gender');
															?>
									<label for="gender" class="form_label">Gender <sup class="text-danger">*</sup></label>
									<!--<div id="gender_err">     
															
										<label class="css_checkbox_radio radio_only"> Male
											<input type="radio" value="1" name="gender" id="gender_male" required <?php if($mode == "Add") { if(set_value('gender') == '1') { echo "checked"; } } else { if($form_data[0]['gender'] == '1') { echo "checked"; } } ?> class=" basic_form">
											<span class="radiobtn"></span>
																		</label>
																		
										<label class="css_checkbox_radio radio_only"> Female
											<input type="radio" value="2" name="gender" id="gender_female" required <?php if($mode == "Add") { if(set_value('gender') == '2') { echo "checked"; } } else { if($form_data[0]['gender'] == '2') { echo "checked"; } } ?> class=" basic_form">
											<span class="radiobtn"></span>
										</label>

										<label class="css_checkbox_radio radio_only"> Other
											<input type="radio" value="3" name="gender" id="gender_female" required <?php if($mode == "Add") { if(set_value('gender') == '2') { echo "checked"; } } else { if($form_data[0]['gender'] == '3') { echo "checked"; } } ?> class=" basic_form">
											<span class="radiobtn"></span>
										</label>
									</div> --> 
									<div id="gender_err">
										<select name="gender" id="gender" class="form-control basic_form" required >
										
											<option value="">Select Gender *</option>
											
											<option value="1" <?php if($chk_gender == 1) { echo 'selected'; } ?>>Male</option>
											<option value="2" <?php if($chk_gender == 2) { echo 'selected'; } ?>>Female</option>
											<option value="3" <?php if($chk_gender == 3) { echo 'selected'; } ?>>Other</option>
											
										</select>
										<?php if(form_error('gender')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('gender'); ?></label> <?php } ?>
									</div> 
								</div>					
							</div>
							
							<div class="col-xl-9 col-lg-9"><?php /* Full Name */ ?>
									<div class="form-group">
										<label for="full_name" class="form_label">Full Name <sup class="text-danger"></sup></label>
										<input type="text" readonly name="full_name" id="full_name" value="<?php if($mode == "Add") { echo set_value('full_name'); } else { echo $form_data[0]['full_name']; } ?>" placeholder="Full Name" class="form-control custom_input allow_only_alphabets_and_space basic_form" maxlength="255"/>
										<note class="form_note" id="full_name_err"></note>
										
										<?php if(form_error('full_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('full_name'); ?></label> <?php } ?>
									</div>					
							</div>
							

							

							<div class="col-xl-3 col-lg-3"><?php /* Guardian Name (Salutation) */ ?>
								<div class="form-group">
									<?php 
									$chk_salutation = set_value('guardian_salutation');
																?>
									<label for="guardian_salutation" class="form_label">Guardian Name (Salutation) <sup class="text-danger">*</sup></label>
										<select name="guardian_salutation" id="guardian_salutation" class="form-control basic_form" required >
											<?php if(count($salutation_master_arr) > 0)
												{ ?>
												<option value="">Select Salutation *</option>
												<?php foreach($guardian_salutation_master_arr as $sal_val)
												{ ?>
												<option value="<?php echo $sal_val; ?>" <?php if($chk_salutation == $sal_val) { echo 'selected'; } ?>><?php echo $sal_val; ?></option>
												<?php }
												} ?>
										</select>
										<note class="form_note" id="guardian_salutation_err"></note>
									<?php if(form_error('guardian_salutation')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('guardian_salutation'); ?></label> <?php } ?>
								</div>					
							</div>
							
							<div class="col-xl-9 col-lg-9"><?php /* Guardian Name */ ?>
								<div class="form-group">
									<label for="guardian_name" class="form_label">Father/Mother/Guardian’s Name <sup class="text-danger">*</sup></label>
									<input type="text" name="guardian_name" id="guardian_name" value="<?php if($mode == "Add") { echo set_value('guardian_name'); } else { echo $form_data[0]['guardian_name']; } ?>" placeholder="Guardian Name *" class="form-control custom_input allow_only_alphabets_and_space basic_form" maxlength="80" required/>
									<note class="form_note" id="guardian_name_err">Note: Please enter only 80 characters</note>
									
									<?php if(form_error('guardian_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('guardian_name'); ?></label> <?php } ?>
								</div>					
							</div>


						</div>
                      
                  
						
						<h4 class="custom_form_title" style="margin: 20px -20px 15px -20px !important;">Address for Communication</h4>
						<div class="row">
							<div class="col-xl-12 col-lg-12">
								<div class="alert alert-primary"><b>Address for Communication : Please do not repeat the name of Applicant, only Address to be typed</b></div>
							</div>
						
							<div class="col-xl-12 col-lg-12"><?php /* Address Line-1 */ ?>
									<div class="form-group">
										<label for="address1" class="form_label">Address Line-1 <sup class="text-danger">*</sup></label>
										<input type="text" name="address1" id="address1" placeholder="Address Line-1 *" class="form-control custom_input ignore_required" maxlength="75" required value="<?php if($mode == "Add") { echo set_value('address1'); } else { echo $form_data[0]['address1']; } ?>" />
										
										<note class="form_note" id="address1_err">Note: Please enter only 75 characters</note>
										
										<?php if(form_error('address1')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address1'); ?></label> <?php } ?>
									</div>					
							</div>
						
							<div class="col-xl-12 col-lg-12"><?php /* Address Line-2 */ ?>
									<div class="form-group">
									<label for="address2" class="form_label">Address Line-2 <sup class="text-danger"></sup></label>
									<input type="text" name="address2" id="address2" placeholder="Address Line-2" class="form-control custom_input" maxlength="75" value="<?php if($mode == "Add") { echo set_value('address2'); } else { echo $form_data[0]['address2']; } ?>" />
									
									<note class="form_note" id="address2_err">Note: Please enter only 75 characters</note>
									
									<?php if(form_error('address2')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address2'); ?></label> <?php } ?>
									</div>					
								</div>
							
							<div class="col-xl-12 col-lg-12"><?php /* Address Line-3 */ ?>
								<div class="form-group">
									<label for="address3" class="form_label">Address Line-3 <sup class="text-danger"></sup></label>
									<input type="text" name="address3" id="address3" placeholder="Address Line-3" class="form-control custom_input" maxlength="75" value="<?php if($mode == "Add") { echo set_value('address3'); } else { echo $form_data[0]['address3']; } ?>" />
									
									<note class="form_note" id="address3_err">Note: Please enter only 75 characters</note>
									
									<?php if(form_error('address3')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address3'); ?></label> <?php } ?>
								</div>					
							</div>
												
											
							<?php if($mode == "Add") { $chk_state = set_value('state'); } else { $chk_state = $form_data[0]['state']; } ?>
							<div class="col-xl-6 col-lg-6"><?php /* Select State */ ?>
								<div class="form-group ">
									<div class="state_div">
										<label for="state" class="form_label">Select State <sup class="text-danger">*</sup></label>
										<select name="state" id="state" class="form-control chosen-select ignore_required" required onchange="get_city_ajax(this.value,'city'); validate_input('state'); ">
											<?php if(count($state_master_data) > 0) { ?>
												<option value="">Select State *</option>
												<?php foreach($state_master_data as $state_res) { ?>
													<option value="<?php echo $state_res['state_code']; ?>" <?php if($chk_state == $state_res['state_code']) { echo 'selected'; } ?>><?php echo $state_res['state_name']; ?></option>
													<?php }
												}
												else 
												{ ?>
												<option value="">No State Available</option>
											<?php } ?>
										</select>
										
										<span id="state_err"></span>
										<?php if(form_error('state')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('state'); ?></label> <?php } ?>
									</div>
								</div>					
							</div>
												
							<div class="col-xl-6 col-lg-6"><?php /* Select City */ ?>
								<div class="form-group">
									<label for="city" class="form_label">City <sup class="text-danger">*</sup></label>
									<div id="city_outer">
										<select class="form-control chosen-select ignore_required" name="city" id="city" required onchange="validate_input('city'); ">
											<?php $selected_state_val = '';
												if($mode == "Add")
												{
													if(set_value('state') != "") { $selected_state_val = set_value('state'); }
												}
												else { $selected_state_val = $form_data[0]['state']; }
												
												if($selected_state_val != "")
												{
													$city_data = $this->master_model->getRecords('city_master', array('state_code' => $selected_state_val, 'city_delete' => '0'), 'id, city_name', array('city_name'=>'ASC'));
													
													if(count($city_data) > 0)
													{ ?>
													<option value="">Select City</option>
													<?php foreach($city_data as $city)
														{ ?>
														<option value="<?php echo $city['id']; ?>" <?php if($mode == "Add") { if(set_value('city') == $city['id']) { echo "selected"; } } else { if($form_data[0]['city'] == $city['id']) { echo "selected"; } } ?>><?php echo $city['city_name']; ?></option>
														<?php }
													}
													else
													{ ?>
													<option value="">No City Available</option>
													<?php }
												}
												else 
												{
													echo '<option value="">Select City</option>';
												} ?>
										</select>
									</div>
									
									<span id="city_err"></span>
									
									<?php if(form_error('city')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('city'); ?></label> <?php } ?>                            
								</div>					
							</div>
												
							<div class="col-xl-6 col-lg-6"><?php /* District */ ?>
								<div class="form-group">
									<label for="district" class="form_label">District <sup class="text-danger">*</sup></label>
									<input type="text" name="district" id="district" value="<?php if($mode == "Add") { echo set_value('district'); } else { echo $form_data[0]['district']; } ?>" placeholder="District *" class="form-control custom_input allow_only_alphabets_and_numbers_and_space ignore_required" maxlength="30" required/>
									<note class="form_note" id="district_err">Note: Please enter only 30 characters</note>
									
									<?php if(form_error('district')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('district'); ?></label> <?php } ?>
								</div>					
							</div>
												
							<div class="col-xl-6 col-lg-6"><?php /* Pincode */ ?>
								<div class="form-group">
									<label for="pincode" class="form_label">Pincode <sup class="text-danger">*</sup></label>
									<input type="text" name="pincode" id="pincode" value="<?php if($mode == "Add") { echo set_value('pincode'); } else { echo $form_data[0]['pincode']; } ?>" placeholder="Pincode *" class="form-control custom_input allow_only_numbers ignore_required" required maxlength="6" minlength="6" />
									<note class="form_note" id="pincode_err"></note>
									<?php if(form_error('pincode')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('pincode'); ?></label> <?php } ?>
								</div>					
							</div>
												
												
						</div>

                    
						<h4 class="custom_form_title" style="margin: 20px -20px 15px -20px !important;">Permanent Address Details</h4>
						<div class="row">
							<div class="col-xl-12 col-lg-12">
								<div class="alert alert-primary"><b>Permanent Address same as Address for Communication 
									<input style="margin-left: 10px;" type="checkbox" name="same_as_above" id="same_as_above" class="custom_input same_as_above" onclick="sameAsAbove(this.form)"  value="1" /></b></div>
							</div>
						
							<div class="col-xl-12 col-lg-12"><?php /* Address Line-1 */ ?>
									<div class="form-group">
									<label for="address1_pr" class="form_label">Address Line-1 <sup class="text-danger">*</sup></label>
									<input type="text" name="address1_pr" id="address1_pr" placeholder="Address Line-1 *" class="form-control custom_input ignore_required" maxlength="75" required value="<?php if($mode == "Add") { echo set_value('address1_pr'); } else { echo $form_data[0]['address1_pr']; } ?>" />
									
									<note class="form_note" id="address1_pr_err">Note: Please enter only 75 characters</note>
									
									<?php if(form_error('address1_pr')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address1_pr'); ?></label> <?php } ?>
									</div>					
								</div>
							
							<div class="col-xl-12 col-lg-12"><?php /* Address Line-2 */ ?>
									<div class="form-group">
									<label for="address2_pr" class="form_label">Address Line-2 <sup class="text-danger"></sup></label>
									<input type="text" name="address2_pr" id="address2_pr" placeholder="Address Line-2" class="form-control custom_input" maxlength="75" value="<?php if($mode == "Add") { echo set_value('address2_pr'); } else { echo $form_data[0]['address2_pr']; } ?>" />
									
									<note class="form_note" id="address2_pr_err">Note: Please enter only 75 characters</note>
									
									<?php if(form_error('address2_pr')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address2_pr'); ?></label> <?php } ?>
									</div>					
								</div>
							
							<div class="col-xl-12 col-lg-12"><?php /* Address Line-3 */ ?>
									<div class="form-group">
										<label for="address3_pr" class="form_label">Address Line-3 <sup class="text-danger"></sup></label>
										<input type="text" name="address3_pr" id="address3_pr" placeholder="Address Line-3" class="form-control custom_input" maxlength="75" value="<?php if($mode == "Add") { echo set_value('address3_pr'); } else { echo $form_data[0]['address3_pr']; } ?>" />
										
										<note class="form_note" id="address3_pr_err">Note: Please enter only 75 characters</note>
										
										<?php if(form_error('address3_pr')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address3_pr'); ?></label> <?php } ?>
									</div>					
							</div>
												
											
							<?php if($mode == "Add") { $chk_state = set_value('state_pr'); } else { $chk_state = $form_data[0]['state_pr']; } ?>
							<div class="col-xl-6 col-lg-6"><?php /* Select State */ ?>
								<div class="form-group ">
									<div class="state_pr_div">
										<label for="state_pr" class="form_label">Select State <sup class="text-danger">*</sup></label>
										<select name="state_pr" id="state_pr" class="form-control chosen-select ignore_required" required onchange="get_city_ajax(this.value,'city_pr'); validate_input('state_pr'); ">
											<?php if(count($state_master_data) > 0) { ?>
												<option value="">Select State *</option>
												<?php foreach($state_master_data as $state_res) { ?>
													<option value="<?php echo $state_res['state_code']; ?>" <?php if($chk_state == $state_res['state_code']) { echo 'selected'; } ?>><?php echo $state_res['state_name']; ?></option>
													<?php }
												}
												else 
												{ ?>
												<option value="">No State Available</option>
											<?php } ?>
										</select>
									</div>
									<span id="state_pr_err"></span>
									<?php if(form_error('state_pr')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('state_pr'); ?></label> <?php } ?>
								</div>					
							</div>
												
							<div class="col-xl-6 col-lg-6"><?php /* Select City */ ?>
								<div class="form-group">
									<label for="city_pr" class="form_label">City <sup class="text-danger">*</sup></label>
									<div id="city_pr_outer">
										<select class="form-control chosen-select ignore_required" name="city_pr" id="city_pr" required onchange="validate_input('city_pr'); ">
											<?php $selected_state_val = '';
												if($mode == "Add")
												{
													if(set_value('state') != "") { $selected_state_val = set_value('state'); }
												}
												else { $selected_state_val = $form_data[0]['state']; }
												
												if($selected_state_val != "")
												{
													$city_data = $this->master_model->getRecords('city_master', array('state_code' => $selected_state_val, 'city_delete' => '0'), 'id, city_name', array('city_name'=>'ASC'));
													
													if(count($city_data) > 0)
													{ ?>
													<option value="">Select City</option>
													<?php foreach($city_data as $city)
														{ ?>
														<option value="<?php echo $city['id']; ?>" <?php if($mode == "Add") { if(set_value('city_pr') == $city['id']) { echo "selected"; } } else { if($form_data[0]['city_pr'] == $city['id']) { echo "selected"; } } ?>><?php echo $city['city_name']; ?></option>
														<?php }
													}
													else
													{ ?>
													<option value="">No City Available</option>
													<?php }
												}
												else 
												{
													echo '<option value="">Select City</option>';
												} ?>
										</select>
									</div>
									
									<span id="city_pr_err"></span>
									
									<?php if(form_error('city_pr')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('city_pr'); ?></label> <?php } ?>                            
								</div>					
							</div>
												
							<div class="col-xl-6 col-lg-6"><?php /* District */ ?>
								<div class="form-group">
									<label for="district_pr" class="form_label">District <sup class="text-danger">*</sup></label>
									<input type="text" name="district_pr" id="district_pr" value="<?php if($mode == "Add") { echo set_value('district_pr'); } else { echo $form_data[0]['district_pr']; } ?>" placeholder="District *" class="form-control custom_input allow_only_alphabets_and_numbers_and_space ignore_required" maxlength="30" required/>
									<note class="form_note" id="district_pr_err">Note: Please enter only 30 characters</note>
									
									<?php if(form_error('district_pr')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('district_pr'); ?></label> <?php } ?>
								</div>					
							</div>
												
							<div class="col-xl-6 col-lg-6"><?php /* Pincode */ ?>
								<div class="form-group">
									<label for="pincode_pr" class="form_label">Pincode <sup class="text-danger">*</sup></label>
									<input type="text" name="pincode_pr" id="pincode_pr" value="<?php if($mode == "Add") { echo set_value('pincode_pr'); } else { echo $form_data[0]['pincode_pr']; } ?>" placeholder="Pincode *" class="form-control custom_input allow_only_numbers ignore_required" required maxlength="6" minlength="6" />
									<note class="form_note" id="pincode_pr_err"></note>
									<?php if(form_error('pincode_pr')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('pincode_pr'); ?></label> <?php } ?>
								</div>					
							</div>
												
												
						</div>
						<h4 class="custom_form_title" style="margin: 20px -20px 15px -20px !important;">Other Details</h4>
						<div class="row">

                    
							
							<div class="col-xl-6 col-lg-6"><?php /* Qualification */ ?>
								<div class="form-group">
									<?php 
									$chk_qualification = set_value('qualification');
																?>
									<label for="qualification" class="form_label">Eligibility <sup class="text-danger">*</sup></label>
									<select name="qualification" id="qualification" class="form-control basic_form qualification_field" required onchange="show_hide_dependent_fields(this);">
										<?php if(count($qualification_arr) > 0)
											{ ?>
											<option value="">Select  *</option>
											<?php foreach($qualification_arr as $key=>$sal_val)
											{ ?>
											<option value="<?php echo $key; ?>" <?php if($chk_qualification == $key) { echo 'selected'; } ?>><?php echo $sal_val; ?></option>
											<?php }
																		} ?>
									</select>
									<span id="qualification_err"></span>
									<?php if(form_error('qualification')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('qualification'); ?></label> <?php } ?>
								</div>					
							</div>
							
							<div class="col-xl-6 col-lg-6 qualification_depend_div twelth_qualification_div" style="display:none;">
								<div class="form-group">
									<label for="roleid" class="control-label form_label">Experience More than 1.5 Years in BFSI</label>
									<div class="col-sm-12" >
										<span>
											<input  onchange="show_hide_exp_file(this);validate_input('experience');" value="Y" name="experience" id="experience" type="radio" <?php echo set_radio('experience', 'Y'); ?> class="experience_y custom_input">
											Yes
										</span>
										<span style="margin-left: 20%;">
											<input onchange="show_hide_exp_file(this);validate_input('experience');" value="N" name="experience" id="experience" type="radio" <?php echo set_radio('experience', 'N'); ?> class="experience_n custom_input" checked="checked">
											No 
										</span>
										<span class="error" id="experience_error"></span>
									</div>
								</div>
								
							</div>

							<div class="col-xl-6 col-lg-6 qualification_depend_div twelth_qualification_div graduate_qualification_div" style="display:none;"><?php // 	Upload Qualification Certificate ?>
								<div class="form-group">
									<div class="img_preview_input_outer pull-left">
										<label class="form_label label_qualification_certificate_file">Upload Qualification Certificate <sup class="text-danger">*</sup></label>
									
										<input type="file" name="qualification_certificate_file" id="qualification_certificate_file" class="form-control hide_input_file_cropper"   />
										
										<div class="image-input image-input-outline image-input-circle image-input-empty">
											<div class="profile-progress"></div>                              
											<button type="button" class="btn btn-sm btn-primary w-100 mb-1 qualification_certificate_file_btn" onclick="open_img_upload_modal('qualification_certificate_file', 'ncvet_candidates', 'Edit Qualification Certificate')">Upload Qualification Certificate</button>
										</div>
										<note class="form_note" id="qualification_certificate_file_err">Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB.</note>
										
										
										<input class="uploaded_hidden_file" datafile="qualification_certificate_file" type="hidden" name="qualification_certificate_file_cropper" id="qualification_certificate_file_cropper" value="<?php echo set_value('qualification_certificate_file_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
										
										<input type="hidden" name="qualification_certificate_file_old" id="qualification_certificate_file_old" value="<?php echo set_value('qualification_certificate_file_old'); ?>" /><?php /* FOR GET OLD CANDIDATE DETAILS IMAGE */ ?>
										
										<?php if(form_error('qualification_certificate_file')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('qualification_certificate_file'); ?></label> <?php } ?>
										<?php if($qualification_certificate_file_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $qualification_certificate_file_error; ?></label> <?php } ?>
									</div>
									
									<div id="qualification_certificate_file_preview" class="upload_img_preview pull-right">
										<?php 
											$preview_qualification_certificate_file = '';
											if($mode == 'Add' && set_value('qualification_certificate_file_cropper') != "") 
											{ 
												$preview_qualification_certificate_file = set_value('qualification_certificate_file_cropper');
											}
											else if($mode == 'Add' && set_value('qualification_certificate_file_old') != "") 
											{ 
												$preview_qualification_certificate_file = set_value('qualification_certificate_file_old');
												$preview_qualification_certificate_file = base_url($qualification_certificate_file_path.'/'.$preview_qualification_certificate_file);
											}
											else if($mode == 'Update' && $form_data[0]['qualification_certificate_file'] != "") 
											{ 
												$preview_qualification_certificate_file = $form_data[0]['qualification_certificate_file'];
												$preview_qualification_certificate_file = base_url($qualification_certificate_file_path.'/'.$preview_qualification_certificate_file);
											}
											
											if($preview_qualification_certificate_file != "")
											{ ?>
											<a href="<?php echo $preview_qualification_certificate_file."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Qualification Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
												<img src="<?php echo $preview_qualification_certificate_file."?".time(); ?>">
											</a>
											
											<button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="qualification_certificate_file" data-db_tbl_name="ncvet_candidates" data-title="Edit Qualification Certificate" title="Edit Qualification Certificate" alt="Edit Qualification Certificate"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
											<?php }
											else
											{
												echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
											} ?>
									</div><div class="clearfix"></div>
								</div>
							</div>
							
							

							<div class="col-xl-6 col-lg-6  qualification_depend_div twelth_qualification_div exp_certificate_div" style="display:none;"><?php // Upload Experience Certificate ?>
								<div class="form-group">
									<div class="img_preview_input_outer pull-left">
										<label class="form_label">Upload Experience Certificate <sup class="text-danger">*</sup></label>
									
										<input type="file" name="exp_certificate" id="exp_certificate" class="form-control hide_input_file_cropper"  />
										
										<div class="image-input image-input-outline image-input-circle image-input-empty">
											<div class="profile-progress"></div>                              
											<button type="button" class="btn btn-sm btn-primary w-100 mb-1 exp_certificate_btn" onclick="open_img_upload_modal('exp_certificate', 'ncvet_candidates', 'Edit Experience Certificate')">Upload Experience Certificate</button>
										</div>
										<note class="form_note" id="exp_certificate_err">Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB.</note>
										
										<input type="hidden" class="uploaded_hidden_file" datafile="exp_certificate" name="exp_certificate_cropper" id="exp_certificate_cropper" value="<?php echo set_value('exp_certificate_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
										
										<input type="hidden" name="exp_certificate_old" id="exp_certificate_old" value="<?php echo set_value('exp_certificate_old'); ?>" /><?php /* FOR GET OLD CANDIDATE DETAILS IMAGE */ ?>
										
										<?php if(form_error('exp_certificate')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exp_certificate'); ?></label> <?php } ?>
										<?php if($exp_certificate_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $exp_certificate_error; ?></label> <?php } ?>
									</div>
									
									<div id="exp_certificate_preview" class="upload_img_preview pull-right">
										<?php 
											$preview_exp_certificate = '';
											if($mode == 'Add' && set_value('exp_certificate_cropper') != "") 
											{ 
												$preview_exp_certificate = set_value('exp_certificate_cropper');
											}
											else if($mode == 'Add' && set_value('exp_certificate_old') != "") 
											{ 
												$preview_exp_certificate = set_value('exp_certificate_old');
												$preview_exp_certificate = base_url($exp_certificate_path.'/'.$preview_exp_certificate);
											}
											else if($mode == 'Update' && $form_data[0]['exp_certificate'] != "") 
											{ 
												$preview_exp_certificate = $form_data[0]['exp_certificate'];
												$preview_exp_certificate = base_url($exp_certificate_path.'/'.$preview_exp_certificate);
											}
											
											if($preview_exp_certificate != "")
											{ ?>
											<a href="<?php echo $preview_exp_certificate."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Exp Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
												<img src="<?php echo $preview_exp_certificate."?".time(); ?>">
											</a>
											
											<button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="exp_certificate" data-db_tbl_name="ncvet_candidates" data-title="Edit Exp Certificate" title="Edit Exp Certificate" alt="Edit Exp Certificate"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
											<?php }
											else
											{
												echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
											} ?>
									</div><div class="clearfix"></div>
								</div>
							</div>

							<div class="col-xl-6 col-lg-6 qualification_depend_div pursuing_graduation_sem_div" style="display:none;"><?php /* graduation_sem_div */ ?>
							<div class="form-group">
									<?php 
									$chk_graduation_sem = set_value('graduation_sem');
																?>
									<label for="graduation_sem" class="form_label">Semester <sup class="text-danger">*</sup></label>
																<select name="graduation_sem" id="graduation_sem" class="form-control basic_form"  >
									<?php if(count($graduation_sem_arr) > 0)
										{ ?>
										<option value="">Select  *</option>
										<?php foreach($graduation_sem_arr as $sal_val)
										{ ?>
										<option value="<?php echo $sal_val; ?>" <?php if($chk_graduation_sem == $sal_val) { echo 'selected'; } ?>><?php echo $sal_val; ?></option>
										<?php }
																		} ?>
																</select>
									<?php if(form_error('graduation_sem')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('graduation_sem'); ?></label> <?php } ?>
								</div>					
							</div>

							<div class="col-xl-6 col-lg-6 qualification_depend_div pursuing_post_graduation_sem_div" style="display:none;"><?php /* graduation_sem_div */ ?>
							<div class="form-group">
									<?php 
									$chk_post_graduation_sem = set_value('post_graduation_sem');
																?>
									<label for="post_graduation_sem" class="form_label">Semester <sup class="text-danger">*</sup></label>
																<select name="post_graduation_sem" id="post_graduation_sem" class="form-control basic_form"  >
									<?php if(count($post_graduation_sem_arr) > 0)
										{ ?>
										<option value="">Select  *</option>
										<?php foreach($post_graduation_sem_arr as $sal_val)
										{ ?>
										<option value="<?php echo $sal_val; ?>" <?php if($chk_post_graduation_sem == $sal_val) { echo 'selected'; } ?>><?php echo $sal_val; ?></option>
										<?php }
																		} ?>
																</select>
									<?php if(form_error('post_graduation_sem')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('post_graduation_sem'); ?></label> <?php } ?>
								</div>					
							</div>


							<div class="col-xl-6 col-lg-6 qualification_depend_div collage_div pursuing_graduation_div" style="display:none;"><?php /* College */ ?>
							<div class="form-group">
								<label for="collage" class="form_label">Name of the College / Academic Institution <sup class="text-danger">*</sup></label>
								<input type="text"   name="collage" id="collage" placeholder="Name of the College / Academic Institution *" class="form-control custom_input ignore_required" maxlength="160"  value="<?php if($mode == "Add") { echo set_value('collage'); } else { echo $form_data[0]['collage']; } ?>" />
								
								<note class="form_note" id="collage_err">Note: Please enter only 160 characters</note>
								
								<?php if(form_error('collage')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('collage'); ?></label> <?php } ?>
							</div>					
							</div>
							<div class="col-xl-6 col-lg-6 qualification_depend_div university_div pursuing_graduation_div" style="display:none;"><?php /* Name of the University */ ?>
							<div class="form-group">
								<label for="university" class="form_label"> Name of the University <sup class="text-danger">*</sup></label>
								<input type="text" name="university" id="university" placeholder="Name of the University *" class="form-control custom_input ignore_required" maxlength="75"  value="<?php if($mode == "Add") { echo set_value('university'); } else { echo $form_data[0]['university']; } ?>" />
								
								<note class="form_note" id="university_err">Note: Please enter only 75 characters</note>
								
								<?php if(form_error('university')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('coluniversitylage'); ?></label> <?php } ?>
							</div>					
							</div>



							<div class="col-xl-6 col-lg-6 qualification_depend_div institute_idproof_div pursuing_graduation_div" style="display:none;"><?php // Upload institute_idproof ?>
							<div class="form-group">
								<div class="img_preview_input_outer pull-left">
								<label class="form_label">Upload Institute ID <sup class="text-danger"></sup></label>
								
								<input type="file" name="institute_idproof" id="institute_idproof" class="form-control hide_input_file_cropper"  />
								
								<div class="image-input image-input-outline image-input-circle image-input-empty">
									<div class="profile-progress"></div>                              
									<button type="button" class="btn btn-sm btn-primary w-100 mb-1 institute_idproof_btn" onclick="open_img_upload_modal('institute_idproof', 'ncvet_candidates', 'Institue ID')">Upload Institue ID</button>
								</div>
								<note class="form_note" id="institute_idproof_err">Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB.</note>
								
								<input type="hidden" class="uploaded_hidden_file" datafile="institute_idproof" name="institute_idproof_cropper" id="institute_idproof_cropper" value="<?php echo set_value('institute_idproof_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
								
								<input type="hidden" name="institute_idproof_old" id="institute_idproof_old" value="<?php echo set_value('institute_idproof_old'); ?>" /><?php /* FOR GET OLD CANDIDATE DETAILS IMAGE */ ?>                            
								
								
								<?php if(form_error('institute_idproof')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('institute_idproof'); ?></label> <?php } ?>
								<?php if($institute_idproof_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $institute_idproof_error; ?></label> <?php } ?>
								</div>
								
								<div id="institute_idproof_preview" class="upload_img_preview pull-right">
								<?php 
									$preview_candidate_id = $preview_first_name = $preview_training_id = '';
									if($mode == 'Add') 
									{ 
									$preview_candidate_id = set_value('old_candidate_id');
									$preview_first_name = set_value('first_name');
									}
								
									$preview_institute_idproof = '';			
									if($mode == 'Add' && set_value('institute_idproof_cropper') != "") 
									{ 
									$preview_institute_idproof = set_value('institute_idproof_cropper');                              
									}
									else if($mode == 'Add' && set_value('institute_idproof_old') != "") 
									{ 
									$preview_institute_idproof = set_value('institute_idproof_old');                              
									$preview_institute_idproof = base_url($institute_idproof_path.'/'.$preview_institute_idproof);                              
									}
								
									
									if($preview_institute_idproof != "")
									{ ?>
									<a href="<?php echo $preview_institute_idproof."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Proof of Identity - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
									<img src="<?php echo $preview_institute_idproof."?".time(); ?>">
									</a>
									
									<button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="institute_idproof" data-db_tbl_name="ncvet_candidates" data-title="Edit " title="Edit " alt="Edit "><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
									<?php }
									else
									{
									echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
									} ?>
								</div><div class="clearfix"></div>
							</div>
							</div>

							<?php if($mode == "Add") { $chk_state = set_value('qualification_state '); } else { $chk_state = $form_data[0]['qualification_state ']; } ?>
							<div class="col-xl-6 col-lg-6 qualification_depend_div qualification_state_div" style="display:none;"><?php /* Select State */ ?>
								<div class="form-group ">
									<div class="qualification_state_div">
										<label for="qualification_state" class="form_label label_qualification_state"> State <sup class="text-danger">*</sup></label>
										<select required name="qualification_state" id="qualification_state" class="form-control chosen-select ignore_required qualification_state" >
											<?php if(count($state_master_data) > 0) { ?>
												<option value="">Select State *</option>
												<?php foreach($state_master_data as $state_res) { ?>
													<option value="<?php echo $state_res['state_code']; ?>" <?php if($chk_state == $state_res['state_code']) { echo 'selected'; } ?>><?php echo $state_res['state_name']; ?></option>
													<?php }
												}
												else 
												{ ?>
												<option value="">No State Available</option>
											<?php } ?>
										</select>
										
										<span id="qualification_state_err"></span>
										<?php if(form_error('qualification_state')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('qualification_state'); ?></label> <?php } ?>
									</div>
								</div>					
							</div>
							<div class="col-xl-12 col-lg-12">
								
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
							<input type="hidden" id="email_verify_status" name="email_verify_status" value="<?php echo $email_verify_status; ?>">
							<input type="hidden" id="mobile_verify_status" name="mobile_verify_status" value="<?php echo $mobile_verify_status; ?>">
							</div>
							<div class="col-xl-6 col-lg-6">
								<div class="col-xl-12 col-lg-12"><?php /* Email id */ ?>
									<div class="form-group row">
										<label for="email_id" class="form_label col-xl-12 col-lg-12">Email id <sup class="text-danger">*</sup></label>
										<div class="col-xl-8 col-lg-8">
											<input type="text" name="email_id" id="email_id" value="<?php if($mode == "Add") { echo set_value('email_id'); } else { echo $form_data[0]['email_id']; } ?>" placeholder="Email id *" class="form-control custom_input basic_form" required maxlength="80" />
											<note class="form_note" id="email_id_err">Note: Please enter only 80 characters</note>
											
											<?php if(form_error('email_id')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('email_id'); ?></label> <?php } ?>
										</div>
										<div class="col-xl-4 col-lg-4">
											<button type="button" class="btn btn-info send-otp" id="send_otp_btn" data-type='send_otp' <?php if($emailStatus == 'yes') { ?> style="display:none;" <?php } ?>>Get OTP</button>
											<a class="btn btn-info" id="reset_btn_email" href="javascript:void(0)" <?php if($emailStatus == 'yes') { ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>Change Email</a>
										</div>
									</div>					
								</div>

								
							
								<div class="col-xl-12 col-lg-12 verify-otp-section" style="display:none;"><?php /* Email otp */ ?>
									<div class="form-group row">
										<label for="otp" class="form_label col-xl-12 col-lg-12">Email OTP<sup class="text-danger">*</sup></label>
										<div class="col-xl-6 col-lg-6">
											<input type="text" name="otp" id="otp" value="<?php if($mode == "Add") { echo set_value('otp'); } else { echo $form_data[0]['otp']; } ?>" placeholder="OTP *" class="form-control custom_input basic_form" required maxlength="8" />
											<note class="form_note" id="otp_err"></note>
											
											<?php if(form_error('otp')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('otp'); ?></label> <?php } ?>
										</div>
										<div class="col-xl-6 col-lg-6">
											<button type="button" class="btn btn-info verify-otp" data-verify-type='email'>Verify OTP </button>
											<button type="button" class="btn btn-info send-otp" data-type='resend_otp'>Resend OTP</button>
										</div>
									</div>					
								</div>
							</div>
							<div class="col-xl-6 col-lg-6">
								<div class="col-xl-12 col-lg-12"><?php /* Mobile Number */ ?>
									<div class="form-group row">
										<label for="mobile_no" class="form_label col-xl-12 col-lg-12">Mobile Number <sup class="text-danger">*</sup></label>
										<div class="col-xl-8 col-lg-8">
											<input type="text" name="mobile_no" id="mobile_no" value="<?php if($mode == "Add") { echo set_value('mobile_no'); } else { echo $form_data[0]['mobile_no']; } ?>" placeholder="Mobile Number *" class="form-control custom_input allow_only_numbers basic_form" required maxlength="10" minlength="10" />
											<note class="form_note" id="mobile_no_err"></note>
											<?php if(form_error('mobile_no')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('mobile_no'); ?></label> <?php } ?>
										</div>
										<div class="col-xl-4 col-lg-4">
											<button type="button" class="btn btn-info send-otp-mobile" id="send_otp_btn_mobile" data-type='send_otp' <?php if($mobileStatus == 'yes') { ?> style="display:none;" <?php } ?>>Get OTP</button>
											<a class="btn btn-info" id="reset_btn_mobile" href="javascript:void(0)" <?php if($mobileStatus == 'yes') { ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>Change Mobile No</a>
										</div>
									</div>					
								</div>

								<div class="col-xl-12 col-lg-12 verify-otp-section-mobile" style="display:none;"><?php /* Mobile otp */ ?>
									<div class="form-group row">
										<label for="otp_mobile" class="form_label col-xl-12 col-lg-12">Mobile OTP<sup class="text-danger">*</sup></label>
										<div class="col-xl-6 col-lg-6">
											<input type="text" name="otp_mobile" id="otp_mobile" value="<?php if($mode == "Add") { echo set_value('otp_mobile'); } else { echo $form_data[0]['otp_mobile']; } ?>" placeholder="OTP *" class="form-control custom_input basic_form" required maxlength="8" />
											<note class="form_note" id="otp_mobile_err"></note>
											
											<?php if(form_error('otp_mobile')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('otp_mobile'); ?></label> <?php } ?>
										</div>
										<div class="col-xl-6 col-lg-6">
											<button type="button" class="btn btn-info verify-otp-mobile" data-verify-type='mobile'>Verify OTP </button>
											<button type="button" class="btn btn-info send-otp-mobile" data-type='resend_otp'>Resend OTP</button>
										</div>
									</div>					
								</div>
							</div>
							<div class="col-xl-12 col-lg-12"></div>
							<div class="col-xl-6 col-lg-6"><?php /* Date of Birth */ ?>
								<div class="row">
									<div class="form-group col-xl-8 col-lg-8">
										<label for="dob" class="form_label">Date of Birth <sup class="text-danger">*</sup></label>
										<input type="text" name="dob" id="dob" value="<?php if($mode == "Add") { echo set_value('dob'); } else { if($form_data[0]['dob'] != '0000-00-00') { echo $form_data[0]['dob']; } } ?>" placeholder="Date of Birth" class="form-control custom_input basic_form input-field" onchange="validate_input('dob');calculate_age(this);" onclick="validate_input('dob');calculate_age(this);" required readonly/>
										<div class="tooltip">Kindly enter your Date of Birth exactly as it appears on your Aadhaar card.</div>
             				<note class="form_note" id="dob_err"></note>
										
										<?php if(form_error('dob') != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('dob'); ?></label> <?php } ?>
									</div>	
									<div class="form-group col-xl-4 col-lg-4">
										<label for="age" class="form_label">Age <sup class="text-danger">*</sup></label>
										<input type="text" name="age" id="age" value="<?php if($mode == "Add") { echo set_value('age'); } else { if($form_data[0]['age'] != '0000-00-00') { echo $form_data[0]['age']; } } ?>" placeholder="Age" class="form-control custom_input basic_form" onchange="validate_input('age');" onclick="validate_input('age');"  readonly/>
										<note class="form_note" id="age_err"></note>
										<?php if(form_error('age')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('age'); ?></label> <?php } ?>
									</div>	
								</div>						
							</div>
							<div class="col-xl-12 col-lg-12"></div>
							<div class="col-xl-12 col-lg-12"><div class="form-group"><note class="form_note" id="dob_err">Note: <br> 1. Kindly enter your Date of Birth exactly as it appears on your Aadhaar card. <br>2. Please Select date of birth before <?php echo date('Y-m-d', strtotime("+1days",strtotime($dob_end_date))); ?> date.</div></note></div>

							<div class="col-xl-6 col-lg-6"><?php /* Id Proof Number */ ?>
								<div class="form-group">
									<label for="id_proof_number" class="form_label">APAAR ID/ABC ID <sup class="text-danger">*</sup></label>
									<input type="text" name="id_proof_number" id="id_proof_number" value="<?php if($mode == "Add") { echo set_value('id_proof_number'); } else { echo $form_data[0]['id_proof_number']; } ?>" maxlength="12" placeholder="APAAR ID/ABC ID *" class="form-control custom_input input-field" />
									<div class="tooltip">Kindly enter your APAAR ID number exactly as it appears on your APAAR ID card.</div>
									<note class="form_note" id="id_proof_number_err">Note: Kindly enter your APAAR ID number exactly as it appears on your APAAR ID card.</note>
									
									<?php if(form_error('id_proof_number')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('id_proof_number'); ?></label> <?php } ?>
								</div>					
							</div>

							<div class="col-xl-6 col-lg-6"><?php // Upload Proof of Identity ?>
								<div class="form-group">
									<div class="img_preview_input_outer pull-left">
										<label class="form_label">Upload APAAR ID/ABC ID <sup class="text-danger">*</sup></label>
									
										<input type="file" name="id_proof_file" id="id_proof_file" class="form-control hide_input_file_cropper" required/>
										
										<div class="image-input image-input-outline image-input-circle image-input-empty">
											<div class="profile-progress"></div>                              
											<button type="button" class="btn btn-sm btn-primary w-100 mb-1 id_proof_file_btn" onclick="open_img_upload_modal('id_proof_file', 'ncvet_candidates', 'APAAR ID/ABC ID')">Upload APAAR ID/ABC ID</button>
										</div>
										<note class="form_note" id="id_proof_file_err">Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB.</note>
										
										<input type="hidden" class="uploaded_hidden_file" datafile="id_proof_file" name="id_proof_file_cropper" id="id_proof_file_cropper" value="<?php echo set_value('id_proof_file_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
										
										<input type="hidden" name="id_proof_file_old" id="id_proof_file_old" value="<?php echo set_value('id_proof_file_old'); ?>" /><?php /* FOR GET OLD CANDIDATE DETAILS IMAGE */ ?>                            
										
										
										<?php if(form_error('id_proof_file')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('id_proof_file'); ?></label> <?php } ?>
										<?php if($id_proof_file_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $id_proof_file_error; ?></label> <?php } ?>
									</div>
									
									<div id="id_proof_file_preview" class="upload_img_preview pull-right">
										<?php 
											$preview_candidate_id = $preview_first_name = $preview_training_id = '';
											if($mode == 'Add') 
											{ 
												$preview_candidate_id = set_value('old_candidate_id');
												$preview_first_name = set_value('first_name');
											}
										
											$preview_id_proof_file = '';			
											if($mode == 'Add' && set_value('id_proof_file_cropper') != "") 
											{ 
												$preview_id_proof_file = set_value('id_proof_file_cropper');                              
											}
											else if($mode == 'Add' && set_value('id_proof_file_old') != "") 
											{ 
												$preview_id_proof_file = set_value('id_proof_file_old');                              
												$preview_id_proof_file = base_url($id_proof_file_path.'/'.$preview_id_proof_file);                              
											}
										
											
											if($preview_id_proof_file != "")
											{ ?>
											<a href="<?php echo $preview_id_proof_file."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Proof of Identity - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
												<img src="<?php echo $preview_id_proof_file."?".time(); ?>">
											</a>
											
											<button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="id_proof_file" data-db_tbl_name="ncvet_candidates" data-title="Edit Proof of Identity" title="Edit Proof of Identity" alt="Edit Proof of Identity"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
											<?php }
											else
											{
												echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
											} ?>
									</div><div class="clearfix"></div>
								</div>
							</div>
                        
						
							<div class="col-xl-6 col-lg-6"><?php /* Aadhar Number */ ?>
								<div class="form-group">
									<label for="aadhar_no" class="form_label">Aadhar Number <sup class="text-danger">*</sup></label>
									<input type="text" name="aadhar_no" required id="aadhar_no" value="<?php if($mode == "Add") { echo set_value('aadhar_no'); } else { echo $form_data[0]['aadhar_no']; } ?>" placeholder="Aadhar Number *" class="form-control custom_input allow_only_numbers input-field" maxlength="12" minlength="12" />
									<div class="tooltip">Kindly enter your AADHAR ID number exactly as it appears on your AADHAR ID card.</div>
									<note class="form_note" id="aadhar_no_err">Note: Kindly enter your AADHAR ID number exactly as it appears on your AADHAR ID card.</note>
									
									<?php if(form_error('aadhar_no')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('aadhar_no'); ?></label> <?php } ?>
								</div>					
								
							</div>	
							<div class="col-xl-6 col-lg-6"><?php // Upload Aadhar card ?>
								<div class="form-group">
									<div class="img_preview_input_outer pull-left">
										<label class="form_label">Upload Aadhar Card <sup class="text-danger">*</sup></label>
									
										<input type="file" name="aadhar_file" id="aadhar_file" class="form-control hide_input_file_cropper" required/>
										
										<div class="image-input image-input-outline image-input-circle image-input-empty">
											<div class="profile-progress"></div>                              
											<button type="button" class="btn btn-sm btn-primary w-100 mb-1 aadhar_file_btn" onclick="open_img_upload_modal('aadhar_file', 'ncvet_candidates', 'Aadhar Card')">Upload Aadhar Card</button>
										</div>
										<note class="form_note" id="aadhar_file_err">Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB.</note>
										
										<input type="hidden" class="uploaded_hidden_file" datafile="aadhar_file" name="aadhar_file_cropper" id="aadhar_file_cropper" value="<?php echo set_value('aadhar_file_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
										
										<input type="hidden" name="aadhar_file_old" id="aadhar_file_old" value="<?php echo set_value('aadhar_file_old'); ?>" /><?php /* FOR GET OLD CANDIDATE DETAILS IMAGE */ ?>                            
										
										
										<?php if(form_error('aadhar_file')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('aadhar_file'); ?></label> <?php } ?>
										<?php if($aadhar_file_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $aadhar_file_error; ?></label> <?php } ?>
									</div>
									
									<div id="aadhar_file_preview" class="upload_img_preview pull-right">
										<?php 
											$preview_candidate_id = $preview_first_name = $preview_training_id = '';
											if($mode == 'Add') 
											{ 
												$preview_candidate_id = set_value('old_candidate_id');
												$preview_first_name = set_value('first_name');
											}
										
											$preview_aadhar_file = '';			
											if($mode == 'Add' && set_value('aadhar_file_cropper') != "") 
											{ 
												$preview_aadhar_file = set_value('aadhar_file_cropper');                              
											}
											else if($mode == 'Add' && set_value('aadhar_file_old') != "") 
											{ 
												$preview_aadhar_file = set_value('aadhar_file_old');                              
												$preview_aadhar_file = base_url($aadhar_file_path.'/'.$preview_aadhar_file);                              
											}
										
											
											if($preview_aadhar_file != "")
											{ ?>
											<a href="<?php echo $preview_aadhar_file."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Aadhar card - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
												<img src="<?php echo $preview_aadhar_file."?".time(); ?>">
											</a>
											
											<button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="aadhar_file" data-db_tbl_name="ncvet_candidates" data-title="Edit Aadhar card" title="Edit Aadhar card" alt="Edit Aadhar card"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
											<?php }
											else
											{
												echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
											} ?>
									</div><div class="clearfix"></div>
								</div>
							</div>
										
							<div class="col-xl-6 col-lg-6"><?php // Upload Passport-size Photo ?>
								<div class="form-group">
									<div class="img_preview_input_outer pull-left">
										<label class="form_label">Upload Passport-size Photo <sup class="text-danger">*</sup></label>
									
										<input type="file" name="candidate_photo" id="candidate_photo" class="form-control hide_input_file_cropper" <?php if($mode == 'Add' || ($mode == 'Update' && $form_data[0]['candidate_photo'] == "")) { echo 'required'; } ?> />
										
										<div class="image-input image-input-outline image-input-circle image-input-empty">
											<div class="profile-progress"></div>                              
											<button type="button" class="btn btn-sm btn-primary w-100 mb-1 candidate_photo_btn" onclick="open_img_upload_modal('candidate_photo', 'ncvet_candidates', 'Edit Photo')">Upload Photo</button>
										</div>
										<note class="form_note" id="candidate_photo_err">Note: Please select only .jpg, .jpeg, .png file upto 5MB.</note>
										
										<input type="hidden" class="uploaded_hidden_file" datafile="candidate_photo" name="candidate_photo_cropper" id="candidate_photo_cropper" value="<?php echo set_value('candidate_photo_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
										
										<input type="hidden" name="candidate_photo_old" id="candidate_photo_old" value="<?php echo set_value('candidate_photo_old'); ?>" /><?php /* FOR GET OLD CANDIDATE DETAILS IMAGE */ ?>
										
										<?php if(form_error('candidate_photo')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('candidate_photo'); ?></label> <?php } ?>
										<?php if($candidate_photo_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $candidate_photo_error; ?></label> <?php } ?>
									</div>
									
									<div id="candidate_photo_preview" class="upload_img_preview pull-right">
										<?php 
											$preview_candidate_photo = '';
											if($mode == 'Add' && set_value('candidate_photo_cropper') != "") 
											{ 
												$preview_candidate_photo = set_value('candidate_photo_cropper');
											}
											else if($mode == 'Add' && set_value('candidate_photo_old') != "") 
											{ 
												$preview_candidate_photo = set_value('candidate_photo_old');
												$preview_candidate_photo = base_url($candidate_photo_path.'/'.$preview_candidate_photo);
											}
											
											
											if($preview_candidate_photo != "")
											{ ?>
												<a href="<?php echo $preview_candidate_photo."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Passport-size Photo of the Candidate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
													<img src="<?php echo $preview_candidate_photo."?".time(); ?>">
												</a>
												
												<button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="candidate_photo" data-db_tbl_name="ncvet_candidates" data-title="Edit Photo" title="Edit Photo" alt="Edit Photo"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
												<?php }
											else
											{
												echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
											} ?>
									</div><div class="clearfix"></div>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6"><?php // Upload Signature of the Candidate ?>
								<div class="form-group">
									<div class="img_preview_input_outer pull-left">
										<label class="form_label">Upload Signature of the Candidate <sup class="text-danger">*</sup></label>
									
										
										<input type="file" name="candidate_sign" id="candidate_sign" class="form-control hide_input_file_cropper" <?php if($mode == 'Add' || ($mode == 'Update' && $form_data[0]['candidate_sign'] == "")) { echo 'required'; } ?> />
										
										<div class="image-input image-input-outline image-input-circle image-input-empty">
											<div class="profile-progress"></div>                              
											<button type="button" class="btn btn-sm btn-primary w-100 mb-1 candidate_sign_btn" onclick="open_img_upload_modal('candidate_sign', 'ncvet_candidates', 'Edit Signature')">Upload Signature</button>
										</div>
										<note class="form_note" id="candidate_sign_err">Note: Please select only .jpg, .jpeg, .png file upto 5MB.</note>
										
										<input type="hidden" class="uploaded_hidden_file" datafile="candidate_sign" name="candidate_sign_cropper" id="candidate_sign_cropper" value="<?php echo set_value('candidate_sign_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
										
										<input type="hidden" name="candidate_sign_old" id="candidate_sign_old" value="<?php echo set_value('candidate_sign_old'); ?>" /><?php /* FOR GET OLD CANDIDATE DETAILS IMAGE */ ?>                          
										
										<?php if(form_error('candidate_sign')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('candidate_sign'); ?></label> <?php } ?>
										<?php if($candidate_sign_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $candidate_sign_error; ?></label> <?php } ?>
									</div>
									
									<div id="candidate_sign_preview" class="upload_img_preview pull-right">
										<?php 
											$preview_candidate_sign = '';
											if($mode == 'Add' && set_value('candidate_sign_cropper') != "") 
											{ 
												$preview_candidate_sign = set_value('candidate_sign_cropper'); 
											}
											else if($mode == 'Add' && set_value('candidate_sign_old') != "") 
											{ 
												$preview_candidate_sign = set_value('candidate_sign_old'); 
												$preview_candidate_sign = base_url($candidate_sign_path.'/'.$preview_candidate_sign); 
											}
										
											if($preview_candidate_sign != "")
											{ ?>
													<a href="<?php echo $preview_candidate_sign."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Signature of the Candidate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
													<img src="<?php echo $preview_candidate_sign."?".time(); ?>">
																				</a>
																				
													<button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="candidate_sign" data-db_tbl_name="ncvet_candidates" data-title="Edit Signature" title="Edit Signature" alt="Edit Signature"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
											<?php }
											else
											{
												echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
											} ?>
									</div><div class="clearfix"></div>
								</div>
							</div>
								
												
									
							<input type="hidden" id="data_lightbox_hidden" value="candidate_images">
							<input type="hidden" id="data_lightbox_title_hidden" value="">
									
						</div>
						
						<h4 class="custom_form_title" style="margin: 20px -20px 15px -20px !important;">Disability</h4>
						<div class="col-xl-12 col-lg-12 ">
							<div class="form-group row">
								<label for="roleid" class="col-xl-4 col-lg-4 control-label form_label">Person with Benchmark Disability</label>
								<div class="col-xl-6 col-lg-6">
									<span>
											<input value="Y" name="benchmark_disability" id="benchmark_disability" type="radio" <?php echo set_radio('benchmark_disability', 'Y'); ?> class="benchmark_disability_y custom_input ">
											Yes
									</span>
									<span style="margin-left:20%;">
										<input value="N" name="benchmark_disability" id="benchmark_disability" type="radio" <?php echo set_radio('benchmark_disability', 'N'); ?> class="benchmark_disability_n custom_input " checked="checked">
										No 
									</span>
									<span class="error"></span>
								</div>
							</div>
							<div id="benchmark_disability_div" class="row" style="display:none;">

								<div class="col-xl-4 col-lg-4">
									<div class="form-group">
										<label for="roleid" class="col-sm-12 control-label form_label">Visually impaired</label>
										<div class="col-sm-12">
											<input value="Y" name="visually_impaired" id="visually_impaired" type="radio" <?php echo set_radio('visually_impaired', 'Y'); ?> class="visually_impaired_y">
											Yes
											<input style="margin-left: 20%;" value="N" name="visually_impaired" id="visually_impaired" type="radio" <?php echo set_radio('visually_impaired', 'N'); ?> class="visually_impaired_n" checked="checked">
											No <span class="error"></span>
										</div>
									</div>
									<!--<div class="form-group" id="vis_imp_cert_div" style="display:none;">
										<label for="roleid" class="col-sm-4 control-label">Attach scan copy of PWD certificate <span style="color:#F00">*</span></label>
										<div class="col-sm-5">
											<input type="file" name="scanned_vis_imp_cert" id="scanned_vis_imp_cert" required style="word-wrap: break-word;width: 100%;">
											<input type="hidden" id="hidden_vis_imp_cert" name="hidden_vis_imp_cert">
											<div id="error_vis_imp_cert"></div>
											<br>
											<div id="error_vis_imp_cert_size"></div>
											<span class="vis_imp_cert_text" style="display:none;"></span> <span class="error"> </span>
										</div>
									</div>--> 
									<div class="form-group" id="vis_imp_cert_div" style="display:none;">
										<div class="img_preview_input_outer pull-left">
											<label class="form_label">Attach scan copy of PWD certificate <sup class="text-danger">*</sup></label>
											<input type="hidden" id="hidden_vis_imp_cert" name="hidden_vis_imp_cert">
											<input type="file" name="scanned_vis_imp_cert" id="scanned_vis_imp_cert" class="form-control hide_input_file_cropper" required/>
											
											<div class="image-input image-input-outline image-input-circle image-input-empty">
												<div class="profile-progress"></div>                              
												<button type="button" class="btn btn-sm btn-primary w-100 mb-1 scanned_vis_imp_cert_btn" onclick="open_img_upload_modal('scanned_vis_imp_cert', 'ncvet_candidates', 'Aadhar Card')">Upload PWD certificate</button>
											</div>
											<note class="form_note" id="scanned_vis_imp_cert_err">Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB.</note>
											
											<input type="hidden" class="uploaded_hidden_file" datafile="scanned_vis_imp_cert" name="scanned_vis_imp_cert_cropper" id="scanned_vis_imp_cert_cropper" value="<?php echo set_value('scanned_vis_imp_cert_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
											
											<input type="hidden" name="scanned_vis_imp_cert_old" id="scanned_vis_imp_cert_old" value="<?php echo set_value('scanned_vis_imp_cert_old'); ?>" /><?php /* FOR GET OLD CANDIDATE DETAILS IMAGE */ ?>                            
											
											
											<?php if(form_error('scanned_vis_imp_cert')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('scanned_vis_imp_cert'); ?></label> <?php } ?>
											<?php if($scanned_vis_imp_cert_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $scanned_vis_imp_cert_error; ?></label> <?php } ?>
										</div>
										
										<div id="scanned_vis_imp_cert_preview" class="upload_img_preview pull-right">
											<?php 
												$preview_candidate_id = $preview_first_name = $preview_training_id = '';
												if($mode == 'Add') 
												{ 
													$preview_candidate_id = set_value('old_candidate_id');
													$preview_first_name = set_value('first_name');
												}
											
												$preview_scanned_vis_imp_cert = '';			
												if($mode == 'Add' && set_value('scanned_vis_imp_cert_cropper') != "") 
												{ 
													$preview_scanned_vis_imp_cert = set_value('scanned_vis_imp_cert_cropper');                              
												}
												else if($mode == 'Add' && set_value('scanned_vis_imp_cert_old') != "") 
												{ 
													$preview_scanned_vis_imp_cert = set_value('scanned_vis_imp_cert_old');                              
													$preview_scanned_vis_imp_cert = base_url($scanned_vis_imp_cert_path.'/'.$preview_scanned_vis_imp_cert);                              
												}
											
												
												if($preview_scanned_vis_imp_cert != "")
												{ ?>
												<a href="<?php echo $preview_scanned_vis_imp_cert."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Proof of Identity - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
													<img src="<?php echo $preview_scanned_vis_imp_cert."?".time(); ?>">
												</a>
												
												<button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="scanned_vis_imp_cert" data-db_tbl_name="ncvet_candidates" data-title="Edit Proof of Identity" title="Edit Proof of Identity" alt="Edit Proof of Identity"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
												<?php }
												else
												{
													echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
												} ?>
										</div><div class="clearfix"></div>
									</div>
								</div>
								<div class="col-xl-4 col-lg-4">
									<div class="form-group">
										<label for="roleid" class="col-sm-12 control-label">Orthopedically handicapped</label>
										<div class="col-sm-12">
											<input value="Y" name="orthopedically_handicapped" id="orthopedically_handicapped" type="radio" <?php echo set_radio('orthopedically_handicapped', 'Y'); ?> class="orthopedically_handicapped_y">
											Yes
											<input style="margin-left: 20%;" value="N" name="orthopedically_handicapped" id="orthopedically_handicapped" type="radio" <?php echo set_radio('orthopedically_handicapped', 'N'); ?> class="orthopedically_handicapped_n" checked="checked">
											No <span class="error"></span>
										</div>
									</div>
									<!--<div class="form-group" id="orth_han_cert_div" style="display:none;">
										<label for="roleid" class="col-sm-4 control-label">Attach scan copy of PWD certificate <span style="color:#F00">*</span></label>
										<div class="col-sm-5">
											<input type="file" name="scanned_orth_han_cert" id="scanned_orth_han_cert" required style="word-wrap: break-word;width: 100%;">
											<input type="hidden" id="hidden_orth_han_cert" name="hidden_orth_han_cert">
											<div id="error_orth_han_cert"></div>
											<br>
											<div id="error_orth_han_cert_size"></div>
											<span class="orth_han_cert_text" style="display:none;"></span> <span class="error"> </span>
										</div>
									</div>--> 
									<div class="form-group" id="orth_han_cert_div" style="display:none;">
										<div class="img_preview_input_outer pull-left">
											<label class="form_label">Attach scan copy of PWD certificate <sup class="text-danger">*</sup></label>
											<input type="hidden" id="hidden_orth_han_cert" name="hidden_orth_han_cert">
											<input type="file" name="scanned_orth_han_cert" id="scanned_orth_han_cert" class="form-control hide_input_file_cropper" required/>
											
											<div class="image-input image-input-outline image-input-circle image-input-empty">
												<div class="profile-progress"></div>                              
												<button type="button" class="btn btn-sm btn-primary w-100 mb-1 scanned_orth_han_cert_btn" onclick="open_img_upload_modal('scanned_orth_han_cert', 'ncvet_candidates', 'Aadhar Card')">Upload PWD certificate</button>
											</div>
											<note class="form_note" id="scanned_orth_han_cert_err">Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB.</note>
											
											<input type="hidden" class="uploaded_hidden_file" datafile="scanned_orth_han_cert" name="scanned_orth_han_cert_cropper" id="scanned_orth_han_cert_cropper" value="<?php echo set_value('scanned_orth_han_cert_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
											
											<input type="hidden" name="scanned_orth_han_cert_old" id="scanned_orth_han_cert_old" value="<?php echo set_value('scanned_orth_han_cert_old'); ?>" /><?php /* FOR GET OLD CANDIDATE DETAILS IMAGE */ ?>                            
											
											
											<?php if(form_error('scanned_orth_han_cert')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('scanned_orth_han_cert'); ?></label> <?php } ?>
											<?php if($scanned_orth_han_cert_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $scanned_orth_han_cert_error; ?></label> <?php } ?>
										</div>
										
										<div id="scanned_orth_han_cert_preview" class="upload_img_preview pull-right">
											<?php 
												$preview_candidate_id = $preview_first_name = $preview_training_id = '';
												if($mode == 'Add') 
												{ 
													$preview_candidate_id = set_value('old_candidate_id');
													$preview_first_name = set_value('first_name');
												}
											
												$preview_scanned_orth_han_cert = '';			
												if($mode == 'Add' && set_value('scanned_orth_han_cert_cropper') != "") 
												{ 
													$preview_scanned_orth_han_cert = set_value('scanned_orth_han_cert_cropper');                              
												}
												else if($mode == 'Add' && set_value('scanned_orth_han_cert_old') != "") 
												{ 
													$preview_scanned_orth_han_cert = set_value('scanned_orth_han_cert_old');                              
													$preview_scanned_orth_han_cert = base_url($scanned_orth_han_cert_path.'/'.$preview_scanned_orth_han_cert);                              
												}
											
												
												if($preview_scanned_orth_han_cert != "")
												{ ?>
												<a href="<?php echo $preview_scanned_orth_han_cert."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Proof of Identity - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
													<img src="<?php echo $preview_scanned_orth_han_cert."?".time(); ?>">
												</a>
												
												<button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="scanned_orth_han_cert" data-db_tbl_name="ncvet_candidates" data-title="Edit Proof of Identity" title="Edit Proof of Identity" alt="Edit Proof of Identity"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
												<?php }
												else
												{
													echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
												} ?>
										</div><div class="clearfix"></div>
									</div>
								</div>
								<div class="col-xl-4 col-lg-4">
									<div class="form-group">
										<label for="roleid" class="col-sm-12 control-label">Cerebral palsy</label>
										<div class="col-sm-12">
											<input value="Y" name="cerebral_palsy" id="cerebral_palsy" type="radio" <?php echo set_radio('cerebral_palsy', 'Y'); ?> class="cerebral_palsy_y">
											Yes
											<input style="margin-left: 20%;" value="N" name="cerebral_palsy" id="cerebral_palsy" type="radio" <?php echo set_radio('cerebral_palsy', 'N'); ?> class="cerebral_palsy_n" checked="checked">
											No <span class="error"></span>
										</div>
									</div>
									<!--<div class="form-group" id="cer_palsy_cert_div" style="display:none;">
										<label for="roleid" class="col-sm-4 control-label">Attach scan copy of PWD certificate <span style="color:#F00">*</span></label>
										<div class="col-sm-5">
											<input type="file" name="scanned_cer_palsy_cert" id="scanned_cer_palsy_cert" required style="word-wrap: break-word;width: 100%;">
											<input type="hidden" id="hidden_cer_palsy_cert" name="hidden_cer_palsy_cert">
											<div id="error_cer_palsy_cert"></div>
											<br>
											<div id="error_cer_palsy_cert_size"></div>
											<span class="cer_palsy_cert_text" style="display:none;"></span> <span class="error"> </span>
										</div>
									</div> --> 
									<div class="form-group" id="cer_palsy_cert_div" style="display:none;">
										<div class="img_preview_input_outer pull-left">
											<label class="form_label">Attach scan copy of PWD certificate <sup class="text-danger">*</sup></label>
											<input type="hidden" id="hidden_vis_imp_cert" name="hidden_vis_imp_cert">
											<input type="file" name="scanned_cer_palsy_cert" id="scanned_cer_palsy_cert" class="form-control hide_input_file_cropper" required/>
											
											<div class="image-input image-input-outline image-input-circle image-input-empty">
												<div class="profile-progress"></div>                              
												<button type="button" class="btn btn-sm btn-primary w-100 mb-1 scanned_cer_palsy_cert_btn" onclick="open_img_upload_modal('scanned_cer_palsy_cert', 'ncvet_candidates', 'PWD Certificate')">Upload PWD Certificate</button>
											</div>
											<note class="form_note" id="scanned_cer_palsy_cert_err">Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB.</note>
											
											<input type="hidden" class="uploaded_hidden_file" datafile="scanned_cer_palsy_cert" name="scanned_cer_palsy_cert_cropper" id="scanned_cer_palsy_cert_cropper" value="<?php echo set_value('scanned_cer_palsy_cert_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
											
											<input type="hidden" name="scanned_cer_palsy_cert_old" id="scanned_cer_palsy_cert_old" value="<?php echo set_value('scanned_cer_palsy_cert_old'); ?>" /><?php /* FOR GET OLD CANDIDATE DETAILS IMAGE */ ?>                            
											
											
											<?php if(form_error('scanned_cer_palsy_cert')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('scanned_cer_palsy_cert'); ?></label> <?php } ?>
											<?php if($scanned_cer_palsy_cert_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $scanned_cer_palsy_cert_error; ?></label> <?php } ?>
										</div>
										
										<div id="scanned_cer_palsy_cert_preview" class="upload_img_preview pull-right">
											<?php 
												$preview_candidate_id = $preview_first_name = $preview_training_id = '';
												if($mode == 'Add') 
												{ 
													$preview_candidate_id = set_value('old_candidate_id');
													$preview_first_name = set_value('first_name');
												}
											
												$preview_scanned_cer_palsy_cert = '';			
												if($mode == 'Add' && set_value('scanned_cer_palsy_cert_cropper') != "") 
												{ 
													$preview_scanned_cer_palsy_cert = set_value('scanned_cer_palsy_cert_cropper');                              
												}
												else if($mode == 'Add' && set_value('scanned_cer_palsy_cert_old') != "") 
												{ 
													$preview_scanned_cer_palsy_cert = set_value('scanned_cer_palsy_cert_old');                              
													$preview_scanned_cer_palsy_cert = base_url($scanned_cer_palsy_cert_path.'/'.$preview_scanned_cer_palsy_cert);                              
												}
											
												
												if($preview_scanned_cer_palsy_cert != "")
												{ ?>
												<a href="<?php echo $preview_scanned_cer_palsy_cert."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Proof of Identity - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
													<img src="<?php echo $preview_scanned_cer_palsy_cert."?".time(); ?>">
												</a>
												
												<button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="scanned_cer_palsy_cert" data-db_tbl_name="ncvet_candidates" data-title="Edit Proof of Identity" title="Edit Proof of Identity" alt="Edit Proof of Identity"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
												<?php }
												else
												{
													echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
												} ?>
										</div><div class="clearfix"></div>
									</div>
								</div>
							</div>
							
						
						</div>
						
						<h4 class="custom_form_title pursuing_graduation_div qualification_depend_div" style="margin: 20px -20px 15px -20px !important;display:none;">Declaration Form</h4>
						<div class="row pursuing_graduation_div qualification_depend_div" style="display:none;">
							<div class="col-xl-12 col-lg-12">
								<div class="alert alert-primary"><b>Declaration by the concerned course coordinator/ HOD/Principal/any other authorised person from your education institute in case of students pursuing UG/PG</b>
							</div>
							<div class="col-xl-12 col-lg-12" >
								<div >
										
										<span style="margin-bottom:5%;"><a target="_blank" style="color:red;" href="<?php echo base_url(); ?>/uploads/ncvet/ncvet_declaration_format.docx">Click here to download the Declaration Form</a></span><br>
								</div>
							</div>


								<div class="col-xl-6 col-lg-6 declarationform_div"><?php // Upload Declaration ?>
									<div class="form-group">
										<div class="img_preview_input_outer pull-left">
										<label class="form_label">Upload Declaration <sup class="text-danger">*</sup></label>
										
										<input type="file" name="declarationform" id="declarationform" class="form-control hide_input_file_cropper"  />
										
										<div class="image-input image-input-outline image-input-circle image-input-empty">
											<div class="profile-progress"></div>                              
											<button type="button" class="btn btn-sm btn-primary w-100 mb-1 declarationform_btn" onclick="open_img_upload_modal('declarationform', 'ncvet_candidates', 'Declaration')">Upload Declaration</button>
										</div>
										<note class="form_note" id="declarationform_err">Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB.</note>
										
										<input type="hidden" class="uploaded_hidden_file" datafile="declarationform" name="declarationform_cropper" id="declarationform_cropper" value="<?php echo set_value('declarationform_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
										
										<input type="hidden" name="declarationform_old" id="declarationform_old" value="<?php echo set_value('declarationform_old'); ?>" /><?php /* FOR GET OLD CANDIDATE DETAILS IMAGE */ ?>                            
										
										
										<?php if(form_error('declarationform')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('declarationform'); ?></label> <?php } ?>
										<?php if($declarationform_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $declarationform_error; ?></label> <?php } ?>
										</div>
										
										<div id="declarationform_preview" class="upload_img_preview pull-right">
										<?php 
											$preview_candidate_id = $preview_first_name = $preview_training_id = '';
											if($mode == 'Add') 
											{ 
											$preview_candidate_id = set_value('old_candidate_id');
											$preview_first_name = set_value('first_name');
											}
										
											$preview_declarationform = '';			
											if($mode == 'Add' && set_value('declarationform_cropper') != "") 
											{ 
											$preview_declarationform = set_value('declarationform_cropper');                              
											}
											else if($mode == 'Add' && set_value('declarationform_old') != "") 
											{ 
											$preview_declarationform = set_value('declarationform_old');                              
											$preview_declarationform = base_url($declarationform_path.'/'.$preview_declarationform);                              
											}
										
											
											if($preview_declarationform != "")
											{ ?>
											<a href="<?php echo $preview_declarationform."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Declaration - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
											<img src="<?php echo $preview_declarationform."?".time(); ?>">
											</a>
											
											<button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="declarationform" data-db_tbl_name="ncvet_candidates" data-title="Edit " title="Edit " alt="Edit "><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
											<?php }
											else
											{
											echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
											} ?>
										</div><div class="clearfix"></div>
									</div>
									</div>
								</div>

						</div>
						<h4 class="custom_form_title declarationform_row_div" style="margin: 20px -20px 15px -20px !important;">Declaration </h4>
						<div class="row declarationform_row_div">
							<div class="col-xl-12 col-lg-12">
								<div class="alert alert-primary"><b>
									<input style="margin-right:2%;" type="checkbox" name="declare" id="declare" required> I herby declare all the information provided above is true, correct and complete. I understand in the event of any information being found false or incorrect subsequent to allotment of enrolment number, my candidature is liable to be cancelled
								</b>
								<note class="form_note" id="declare_err"></note>
								<div class="declare_err" id="declare_err"></div>
							</div>
											
							<div class="col-xl-6 col-lg-6"><?php /* Code */ ?>
								<div class="form-group">
								<div class="row">
									<div class="col-md-6">
									<label for="ncvet_candidate_enrollment_captcha" class="form_label">Code <sup class="text-danger">*</sup></label>
									<input type="text" name="ncvet_candidate_enrollment_captcha" id="ncvet_candidate_enrollment_captcha" class="form-control" placeholder="Code *" required autocomplete="off" value="" />
									<note class="form_note" id="ncvet_candidate_enrollment_captcha_err"></note>

									<?php if (form_error('ncvet_candidate_enrollment_captcha') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('ncvet_candidate_enrollment_captcha'); ?></label> <?php } ?>
									</div>
									<div class="col-md-6">
									<label class="form_label">&nbsp;</label>
									<div class="captcha_common">
										<div class="image">
										<?php echo $captcha_img; ?>
										</div>
										<a href="javascript:void(0)" onclick="refresh_captcha()" class="refresh_captcha_link"><i class="fa fa-refresh"></i></a>
									</div>
									</div>
								</div>
								</div>
							</div>
										
							
								

						</div>

						<div class="hr-line-dashed"></div>										
						<div class="row">
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="submit_btn_outer2">
								<input type="submit" class="btn btn-primary" id="submitAll" name="submitAll" onclick="validate_all_details(2)" value="<?php  echo "Preview Details";  ?>">  
								
								<a class="btn btn-danger" href="<?php echo site_url('ncvet/candidate_registration'); ?>">Reset</a>
							</div>
						</div>
					</form>

					
      </div>
    </div>
    
    
    <?php $this->load->view('ncvet/inc_footer'); ?>
        
    <?php $this->load->view('ncvet/common/inc_common_validation_all'); ?>
    <?php $this->load->view('ncvet/common/inc_common_show_hide_password'); ?>
	
		<?php $this->load->view('ncvet/common/inc_cropper_script', array('page_name'=>'candidate_enrollment')); ?>
   	
    <script type="text/javascript">

    	const inputs = document.querySelectorAll('.input-field');

			inputs.forEach(input => {
		    const tooltip = input.nextElementSibling;

		    input.addEventListener('focus', function () {
		        hideAllTooltips();
		        const labelText = $(this).next().text();

		        tooltip.innerHTML = `<b>${labelText}</b> `;
		        tooltip.style.display = 'block';
		        tooltip.style.opacity = '1';
		        if ($(this).attr('id') == 'dob') { 
		        	tooltip.style.left = '230px';
		        	tooltip.style.bottom = '82%';
		        	tooltip.style.height = '70%';
		        }
		    });

		    input.addEventListener('blur', function () {
		        tooltip.style.display = 'none';
		    });
			});

			function hideAllTooltips() {
		    document.querySelectorAll('.tooltip').forEach(t => {
		        t.style.display = 'none';
		    });
			}


		var site_url = "<?php echo base_url(); ?>";

		const formSelector = '#add_candidate_form';

		

		// 2. Restore form data if it exists
		
		const savedFormData = sessionStorage.getItem('ncvet_candidate_enroll_data');
		
		
		if (savedFormData) {
			console.log(savedFormData);
			sessionStorage.removeItem('ncvet_candidate_enroll_data');
			const formData = JSON.parse(savedFormData);
			setTimeout(function() {
				

				$('#reset_btn_email').show();	
				$('#send_otp_btn').hide();	

				$('#reset_btn_mobile').show();	
				$('#send_otp_btn_mobile').hide();	
				
				for (const name in formData) {
					const field = $(`${formSelector} [name="${name}"]`);
					const type = field.attr('type');

					if (type === 'checkbox' ) {
						field.prop('checked', formData[name]);
					} 
					else if (type === 'radio' ) {
						$(`${formSelector} [name="${name}"][value="${formData[name]}"]`).prop('checked', true).click();
					} 
					else {
						field.val(formData[name]);
						
						

						if (field.hasClass('uploaded_hidden_file')) {
							var current_image_id = field.attr('datafile');
							var lightbox_data_title = '';
							if (current_image_id == 'id_proof_file') {
								lightbox_data_title = 'Proof of Identity - ';
							} else if (current_image_id == 'aadhar_file') {
								lightbox_data_title = 'Aadhar File - ';
							} else if (current_image_id == 'qualification_certificate_file') {
								lightbox_data_title = 'Qualification Certificate - ';
							} else if (current_image_id == 'candidate_photo') {
								lightbox_data_title = 'Passport-size Photo of the Candidate - ';
							} else if (current_image_id == 'candidate_sign') {
								lightbox_data_title = 'Signature of the Candidate - ';
							} else if (current_image_id == 'exp_certificate') {
								lightbox_data_title = 'Experience Certificate - ';
							} else if (current_image_id == 'institute_idproof') {
								lightbox_data_title = 'Institutional IDproof - ';
							} else if (current_image_id == 'scanned_vis_imp_cert') {
								lightbox_data_title = 'PWD certificate - ';
							} else if (current_image_id == 'scanned_orth_han_cert') {
								lightbox_data_title = 'PWD certificate - ';
							} else if (current_image_id == 'scanned_cer_palsy_cert') {
								lightbox_data_title = 'PWD certificate - ';
							} else if (current_image_id == 'declaration' || current_image_id == 'declarationform') {
								lightbox_data_title = 'Declaration - ';
							}

							let cleanUrl = formData[name].split('?')[0].split('#')[0];
		
							// Get file extension
							let extension = cleanUrl.split('.').pop();
							var fileext = extension.toLowerCase();
							if(fileext!='') {
								if(fileext=='pdf') {
									$("#" + current_image_id+"_preview").html('<a target="_blank" href="' + formData[name] + '?'  + '"  data-title="' + lightbox_data_title  + '">View File</a>');
								}
								else {
									$("#" + current_image_id+"_preview").html('<a href="' + formData[name] + '?' + '" class="example-image-link" data-lightbox="candidate_images'  + '" data-title="' + lightbox_data_title  + '"><img src="' + formData[name] + '?'  + '"></a>');
								}
							}
						}
						
					}
					if(type!='select' && type!='radio') {
						//field.trigger('change'); // for dependent dropdown logic
					}
					if(name=='state') {
						var state_html = $("#state").html();
						
						$(".state_div").html(
							'<label for="state" class="form_label">Select State <sup class="text-danger">*</sup></label>' +
							'<select name="state" id="state" class="form-control chosen-select ignore_required" required ' +
							'onchange="get_city_ajax(this.value,\'city\','+formData['city']+'); validate_input(\'state\');">' +
							state_html +
							'</select>'
						);
						$('select#state').val(formData[name]).trigger('change');
					}
					if(name=='state_pr') {
						var state_pr_html = $("#state_pr").html();
						
						$(".state_pr_div").html(
							'<label for="state_pr" class="form_label">Select State <sup class="text-danger">*</sup></label>' +
							'<select name="state_pr" id="state_pr" class="form-control chosen-select ignore_required" required ' +
							'onchange="get_city_ajax(this.value,\'city_pr\','+formData['city_pr']+'); validate_input(\'state_pr\');">' +
							state_pr_html +
							'</select>'
						);
						$('select#state_pr').val(formData[name]).trigger('change');
					}
					if(name=='qualification') {
						$('select#qualification').val(formData[name]).trigger('change');
					}
				}
				$('#ncvet_candidate_enrollment_captcha').val('');
			}, 1500);
		}
		function createfullname() {
			firstname = $.trim($("#first_name").val()).toUpperCase();
			middlename = ' ' + $.trim($("#middle_name").val()).toUpperCase();
			lastname = ' ' + $.trim($("#last_name").val()).toUpperCase();
			if ($.trim(firstname) != "" || $.trim(middlename) != "" || $.trim(lastname) != "") {
				$("#full_name").val(firstname + middlename + lastname);
			} else {
				$("#full_name").val("")
			}

			//nameoncard.value = firstname.value+' '+middlename.value+' '+lastname.value;
		}
		//disability code start
		if ($(".benchmark_disability_y").prop("checked")) {
			$("#benchmark_disability_div").show();
			$(".scribe_div").show();
		}

		/* Benchmark Disability */
		$(document).ready(function() {

			$(document).on("click", ".benchmark_disability_y", function() {
				$("#benchmark_disability_div").show();
				$(".scribe_div").show();
			});

			$(document).on("click", ".benchmark_disability_n", function() {

				$("#scanned_vis_imp_cert").removeAttr("required");
				$("#scanned_vis_imp_cert").val("");
				$('.visually_impaired_n').prop('checked', 'checked');
				$("#vis_imp_cert_div").hide();

				$("#scanned_orth_han_cert").removeAttr("required");
				$("#scanned_orth_han_cert").val("");
				$('.orthopedically_handicapped_n').prop('checked', 'checked');
				$("#orth_han_cert_div").hide();

				$("#scanned_cer_palsy_cert").removeAttr("required");
				$("#scanned_cer_palsy_cert").val("");
				$('.cerebral_palsy_n').prop('checked', 'checked');
				$("#cer_palsy_cert_div").hide();

				$("#benchmark_disability_div").hide();
				$(".scribe_div").hide();
			});

			if ($(".benchmark_disability_y").prop("checked")) {
				$("#benchmark_disability_div").show();
			}

			/* Visually impaired certificate */
			if ($("#scanned_vis_imp_cert").val() != "") {
				$("#vis_imp_cert_div").show();
			} else {
				$("#scanned_vis_imp_cert").removeAttr("required");
			}

			if ($(".visually_impaired_y").prop("checked")) {
				$("#scanned_vis_imp_cert").prop('required', true);
				$("#vis_imp_cert_div").show();
			} else {
				$("#scanned_vis_imp_cert").removeAttr("required");
				$("#scanned_vis_imp_cert").val("");
				$("#vis_imp_cert_div").hide();
			}

			$(document).on("click", ".visually_impaired_y", function() {
				$("#scanned_vis_imp_cert").prop('required', true);
				$("#vis_imp_cert_div").show();
			});
			$(document).on("click", ".visually_impaired_n", function() {
				$("#scanned_vis_imp_cert").removeAttr("required");
				$("#scanned_vis_imp_cert").val("");
				$("#vis_imp_cert_div").hide();
			});

			/* Orthopedically handicapped certificate */
			if ($("#scanned_orth_han_cert").val() != "") {
				$("#orth_han_cert_div").show();
			} else {
				$("#scanned_orth_han_cert").removeAttr("required");
			}

			if ($(".orthopedically_handicapped_y").prop("checked")) {
				$("#scanned_orth_han_cert").prop('required', true);
				$("#orth_han_cert_div").show();
			} else {
				$("#scanned_orth_han_cert").removeAttr("required");
				$("#scanned_orth_han_cert").val("");
				$("#orth_han_cert_div").hide();
			}

			$(document).on("click", ".orthopedically_handicapped_y", function() {
				$("#scanned_orth_han_cert").prop('required', true);
				$("#orth_han_cert_div").show();
			});
			$(document).on("click", ".orthopedically_handicapped_n", function() {
				$("#scanned_orth_han_cert").removeAttr("required");
				$("#scanned_orth_han_cert").val("");
				$("#orth_han_cert_div").hide();
			});

			/* Cerebral palsy certificate */
			if ($("#scanned_cer_palsy_cert").val() != "") {
				$("#cer_palsy_cert_div").show();
			} else {
				$("#scanned_cer_palsy_cert").removeAttr("required");
			}

			if ($(".cerebral_palsy_y").prop("checked")) {
				$("#scanned_cer_palsy_cert").prop('required', true);
				$("#cer_palsy_cert_div").show();
			} else {
				$("#scanned_cer_palsy_cert").removeAttr("required");
				$("#scanned_cer_palsy_cert").val("");
				$("#cer_palsy_cert_div").hide();
			}

			$(document).on("click", ".cerebral_palsy_y", function() {
				$("#scanned_cer_palsy_cert").prop('required', true);
				$("#cer_palsy_cert_div").show();
			});
			$(document).on("click", ".cerebral_palsy_n", function() {
				$("#scanned_cer_palsy_cert").removeAttr("required");
				$("#scanned_cer_palsy_cert").val("");
				$("#cer_palsy_cert_div").hide();
			});

		});
		//disability code end
										
		function calculate_age(elem) {
			if($(elem).val()!='') {
				var dob =  new Date($(elem).val());
				const today = new Date();

				let age = today.getFullYear() - dob.getFullYear();
				const m = today.getMonth() - dob.getMonth();

				// Adjust if birthday hasn't occurred yet this year
				if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
					age--;
				}
				
				$('input#age').val(age);
			}
		}
		$('#reset_btn_email').click(function() {
			$('#email_id').attr('readonly',false);
			$('#email_id').val('');
			$('#send_otp_btn').show();
			$('#reset_btn_email').hide();
			$('.verify-otp-section').hide();
			emailVerify = false;
			$('#email_verify_status').val('no');
			$('#otp').val('');
		})

	$('#reset_btn_mobile').click(function() {
		$('#mobile_no').attr('readonly',false);
		$('#mobile_no').val('');
		$('#send_otp_btn_mobile').show();
		$('#reset_btn_mobile').hide();
		$('.verify-otp-section-mobile').hide();
		mobileVerify = false;
		$('#mobile_verify_status').val('no');
		$('#otp_mobile').val('');
	})

	$('.verify-otp').click(function() 
	{
		var otp         = $('#otp').val();
		var verify_type = $(this).attr('data-verify-type');
		var email_id 		= $('#email_id').val();
	    var type  		= 'verify_otp';
	    
	    var data = {};
	    data.email_id 		 = email_id;
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
		var mobile_no 		= $('#mobile_no').val();
	    var type  		= 'verify_otp';
	    
	    var data = {};
	    data.mobile_no 	 = mobile_no;
	    data.otp   		 = otp;
	    data.verify_type = verify_type;
		if (otp != '' && otp != undefined) 
		{
			send_verify_otp_mobile(type,data,this)				
		} else {
			alert('Please enter the OTP.');
		}	
	})
	function send_verify_otp(type,data,selector) {
		$.ajax({
			type: 'POST',
			url: site_url + 'ncvet/Candidate_registration/send_otp/',
			data : {'email_id':data.email_id,'type':type,'otp':data.otp,'verify_type':data.verify_type},
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
						$('#reset_btn_email').show();	
					} else if (type == 'resend_otp') {
						$(selector).attr('disabled',false).text('Resend OTP')
						$('.verify-otp-section').show();
					} else if (type == 'verify_otp') {
						$(selector).attr('disabled',false).text('Verify OTP')
						$('.verify-otp-section').hide();
						emailVerify = true;
						$('#email_verify_status').val('yes');
						$('#email_id').valid();
					}

					$('.email-id').removeClass('parsley-error');
					$('.email-id').addClass('parsley-success');
					$('#email_id').attr('readonly',true);
					
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
			url  : site_url + 'ncvet/Candidate_registration/send_otp_mobile/',
			data : {'mobile_no':data.mobile_no,'type':type,'otp':data.otp,'verify_type':data.verify_type},
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
						$('#mobile_no').valid();
					}

					$('.mobile').removeClass('parsley-error');
					$('.mobile').addClass('parsley-success');
					$('#mobile_no').attr('readonly',true);
					
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
	    var email_id = $('#email_id').val();
	    var type  = $(this).attr('data-type');
	    var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/; // Regular expression for email format
	    
	    if (type == 'resend_otp') {
	    	$('#otp').val('');
	    }
	    var data = {};
	    data.email_id       = email_id;
	    data.otp   		 = '';
	    data.verify_type = '';
	     	
	    if (email_id.trim() != '') {
	        if (emailRegex.test(email_id)) {
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
	    var mobile_no = $('#mobile_no').val();
	    var type  = $(this).attr('data-type');
	  

	    if (type == 'resend_otp') {
	    	$('#otp_mobile').val('');
	    }
	    var data = {};
	    data.mobile_no      = mobile_no;
	    data.otp   		 = '';
	    data.verify_type = '';
	     	
	    if (mobile_no.trim() != '') {
	        if (mobile_no.length == 10 && $.isNumeric(mobile_no) && !mobile_no.includes('.')) {
	            send_verify_otp_mobile(type,data,this)
	        } else {
	        	if ( !$.isNumeric(mobile_no) || mobile_no.includes('.')) {
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
	show_hide_dependent_fields($('#qualification'));
	 function show_hide_dependent_fields(elem) {
		console.log(elem);

		$('.qualification_certificate_file_btn').removeAttr('disabled');
		$('.exp_certificate_btn').removeAttr('disabled');
		$('input#email_id').removeAttr('disabled');
		$('input#mobile_no').removeAttr('disabled');
		$('input#id_proof_number').removeAttr('disabled');
		$('input#aadhar_no').removeAttr('disabled');
		$('.id_proof_file_btn').removeAttr('disabled');
		$('.aadhar_file_btn').removeAttr('disabled');
		$('.candidate_photo_btn').removeAttr('disabled');
		$('.candidate_sign_btn').removeAttr('disabled');
		$('#submitAll').removeAttr('disabled');

		$('.qualification_depend_div').each(function(i, obj) {
			$(this).hide();
			$(this).find('input').not('#experience').removeAttr('required').val('').trigger('change');
			$(this).find('input').removeAttr('required')
			$(this).find('select').removeAttr('required').val('').trigger('change');
			$(this).find('.upload_img_preview').html('<i class="fa fa-picture-o" aria-hidden="true"></i>');
		});
		
		if($(elem).val()!='') {
		$('.qualification_state_div').show().attr('required','required');;
		}
		if($(elem).val()==1) {
			
			$('.twelth_qualification_div').each(function(i, obj) {
				$(this).show();
				$(this).find('input').not('[type="file"]').attr('required','required');
			});
			$('.label_qualification_certificate_file').text('Upload 12th Pass Certificate *');
			$('.label_qualification_state').text('State of Working *');
			$('.experience_y').prop('checked', true).trigger('change');
		}
		if($(elem).val()==2) {
			
			$('.graduate_qualification_div').each(function(i, obj) {
				$(this).show();
				$(this).find('input').not('[type="file"]').attr('required','required');
			});
			$('.label_qualification_certificate_file').text('Upload Degree Certificate/ Provisional degree certificate *');
			$('.label_qualification_state').text('State of Degree College *');
			
		}
		if($(elem).val()==3) {
			
			$('.pursuing_graduation_div').each(function(i, obj) {
				$(this).show();
				$(this).find('input').not('[type="file"]').not('#institute_idproof').attr('required','required');
			});
			$('.pursuing_graduation_sem_div').show();
			$('.pursuing_graduation_sem_div').find('select').attr('required','required');
			$('.label_qualification_state').text('State of College / Academic Institution *');
			
		}if($(elem).val()==4) {
			
			$('.pursuing_graduation_div').each(function(i, obj) {
				$(this).show();
				$(this).find('input').not('[type="file"]').not('#institute_idproof').attr('required','required');
			});
			$('.pursuing_post_graduation_sem_div').show();
			$('.pursuing_post_graduation_sem_div').find('select').attr('required','required');
			$('.label_qualification_state').text('State of College / Academic Institution *');
			
		}
		//pursuing_graduation_div
	 }
	 function show_hide_exp_file(elem) {
		
		$('.exp_certificate_div').show();

		$('.qualification_certificate_file_btn').removeAttr('disabled');
		$('.exp_certificate_btn').removeAttr('disabled');
		$('input#email_id').removeAttr('disabled');
		$('input#mobile_no').removeAttr('disabled');
		$('input#id_proof_number').removeAttr('disabled');
		$('input#aadhar_no').removeAttr('disabled');
		$('.id_proof_file_btn').removeAttr('disabled');
		$('.aadhar_file_btn').removeAttr('disabled');
		$('.candidate_photo_btn').removeAttr('disabled');
		$('.candidate_sign_btn').removeAttr('disabled');
		$('.qualification_state').removeAttr('disabled');
		$('#submitAll').removeAttr('disabled');
		
		if($(elem).val()=='Y') {
			
			//$('.exp_certificate_div').show();
			
		}
		else {
			$('.qualification_certificate_file_btn').attr('disabled','disabled');
			$('.exp_certificate_btn').attr('disabled','disabled');
			$('input#email_id').attr('disabled','disabled');
			$('input#mobile_no').attr('disabled','disabled');
			$('input#id_proof_number').attr('disabled','disabled');
			$('input#aadhar_no').attr('disabled','disabled');
			$('.id_proof_file_btn').attr('disabled','disabled');
			$('.aadhar_file_btn').attr('disabled','disabled');
			$('.candidate_photo_btn').attr('disabled','disabled');
			$('.candidate_sign_btn').attr('disabled','disabled');
			$('.qualification_state').attr('disabled','disabled');
			$('#submitAll').attr('disabled','disabled');
			alert('You are not eligible for enrollment');
		}
		/*else
			$('.exp_certificate_div').hide();*/
		
	 }
    function sameAsAbove(fill) {

		if (fill.same_as_above.checked == true) {
			fill.address1_pr.value = fill.address1.value;
			fill.address2_pr.value = fill.address2.value;
			fill.address3_pr.value = fill.address3.value;
			fill.district_pr.value = fill.district.value;
			fill.pincode_pr.value = fill.pincode.value;
			//fill.state_pr.value = fill.state.value;
			//$('select#state_pr').val(fill.state.value).trigger('change');
			var state_pr_html = $("#state_pr").html();
			$(".state_pr_div").html(
				'<label for="state_pr" class="form_label">Select State <sup class="text-danger">*</sup></label>' +
				'<select name="state_pr" id="state_pr" class="form-control chosen-select ignore_required" required ' +
				'onchange="get_city_ajax(this.value,\'city_pr\','+fill.city.value+'); validate_input(\'state_pr\');">' +
				state_pr_html +
				'</select>'
			);
			$('select#state_pr').val(fill.state.value).trigger('change');
							
			//fill.city_pr.value = fill.city.value;
			
			
		} else {
			fill.address1_pr.value = '';
			fill.address2_pr.value = '';
			fill.address3_pr.value = '';
			fill.district_pr.value = '';
			fill.pincode_pr.value = '';
			//fill.state_pr.value = '';
			$('#state_pr').val('').trigger('change');
			fill.city_pr.value = '';
			
			
		}

	}
      function refresh_captcha() 
      {
        $("#page_loader").css("display", "block");
        $("#ncvet_candidate_enrollment_captcha").val("");
        $.ajax({
          type: "POST",
          url: "<?php echo site_url('ncvet/candidate_registration/refresh_captcha'); ?>",
          success: function(res) 
          {
            if (res) 
            {
              jQuery("div.image").html(res);
              $("#page_loader").css("display", "none");
            }
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
            //$('#current_faculty_status').val(status);
            console.log('AJAX request failed: ' + errorThrown);
            sweet_alert_error("Error occurred. Please try again.");
            $("#page_loader").hide();
            
          }
        });
      }

      var dob = $('#dob').datepicker({ keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy-mm-dd", clearBtn: true,  endDate:"<?php echo $dob_end_date; ?>" });
    
      function get_city_ajax(state_id,city_field_id,selectedCity='')
      {
        $("#page_loader").show();
        var parameters = {
		state_id: state_id,
		city_field_id: city_field_id,
		selectedCity : selectedCity
		};
        
        $.ajax({
          type: "POST",
          url: "<?php echo site_url('ncvet/candidate_registration/get_city_ajax'); ?>",
          data: parameters,
          cache: false,
          dataType: 'JSON',
          success:function(data)
          {
            if(data.flag == "success")
            {
              $("#"+city_field_id+"_outer").html(data.response);
			  //$("#"+city_field_id+"_outer").val(selectedCity).trigger('change');
              $("#page_loader").hide();
            }
            else
            {
              alert("Error occurred. Please try again.")
              $('#page_loader').hide();
            }
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
            console.log('AJAX request failed: ' + errorThrown);
            alert("Error occurred. Please try again.")
            $('#page_loader').hide();
          }
        });
      }     
      
	  /********** START : On Salutation selection, enable/disable the Gender radio buttons ***********/
			function show_hide_gender()
			{
				var selectedGender = $('#salutation').val();              
				
				if(selectedGender == 'Mr.')//'Mr.'
				{
					$("#gender_male").prop( "checked", true );
					$("#gender_female").prop( "checked", false );
					
					$("#gender_male").prop( "disabled", false );
					$("#gender_female").prop( "disabled", true );
					
					$("#gender_male").parent('label').removeClass('disabled');
					$("#gender_female").parent('label').addClass('disabled');
				}
				else if(selectedGender == 'Mrs.' || selectedGender == 'Ms.')//'Mrs, Miss'
				{
					$("#gender_male").prop( "checked", false );
					$("#gender_female").prop( "checked", true );
					
					$("#gender_male").prop( "disabled", true );
					$("#gender_female").prop( "disabled", false );
					
					$("#gender_male").parent('label').addClass('disabled');
					$("#gender_female").parent('label').removeClass('disabled');           
				}
			}/********** END : On Salutation selection, enable/disable the Gender radio buttons ***********/
			show_hide_gender();

      		/********** START : Function for applying validation to 'Id Proof Number' input field  ***********/
			function id_proof_number_validation(is_validate='')
			{

			}
			id_proof_number_validation('0');  
			//START : JQUERY VALIDATION SCRIPT 
    function validate_input(input_id) { $("#"+input_id).valid(); }
    function validate_all_details(form_action)
		{
			
			$("#page_loader").show();
			$("#form_action").val(form_action);
			$(".ignore_required").prop('required',true);
			
			
			
			//$("#email_verify_status").rules("add", { validate_email_id_verified: true });
			//$("#mobile_verify_status").rules("add", { validate_mobile_no_verified: true });
			
			$("#qualification").rules("add", { validate_qualification_field: true });
			$("#email_id").rules("add", {  required: true, maxlength:80, valid_email:true,validate_email_id_verified: true,remote: { url: "<?php echo site_url('ncvet/candidate_registration/validation_check_email_exist/0/1'); ?>", type: "post", data: {  } } });
			           
			$("#mobile_no").rules("add", {  required: true, allow_only_numbers:true, first_zero_not_allowed:true, maxlength:10,validate_mobile_no_verified: true , minlength:10,remote: { url: "<?php echo site_url('ncvet/candidate_registration/validation_check_mobile_exist/0/1'); ?>", type: "post", data: { } } });
				

			$("#address1").rules("add", { required: true, maxlength:75  });
			$("#address2").rules("add", { maxlength:75 });
			$("#address3").rules("add", { maxlength:75 });
			$("#state").rules("add", { required: true });
			$("#city").rules("add", { required: true });
			$("#district").rules("add", { required: true, allow_only_alphabets_and_numbers_and_space:true, maxlength:30 });
			$("#pincode").rules("add", { required: true, allow_only_numbers:true, minlength:6, maxlength: 6, remote: { url: "<?php echo site_url('ncvet/candidate_registration/validation_check_valid_pincode/0/1'); ?>", type: "post", data: { "selected_state_code": function() { return $("#state").val(); } } } });

			
			$("#address1_pr").rules("add", { required: true, maxlength:75  });
			$("#address2_pr").rules("add", { maxlength:75 });
			$("#address3_pr").rules("add", { maxlength:75 });
			$("#state_pr").rules("add", { required: true });
			$("#city_pr").rules("add", { required: true });
			$("#district_pr").rules("add", { required: true, allow_only_alphabets_and_numbers_and_space:true, maxlength:30 });
			$("#pincode_pr").rules("add", { required: true, allow_only_numbers:true, minlength:6, maxlength: 6, remote: { url: "<?php echo site_url('ncvet/candidate_registration/validation_check_valid_pincode_pr/0/1'); ?>", type: "post", data: { "selected_state_code": function() { return $("#state_pr").val(); } } } });
			$("#id_proof_number").rules("add", {  remote: { url: "<?php echo site_url('ncvet/candidate_registration/validation_check_id_proof_number_exist/0/1'); ?>", type: "post", data: { } } });
			
			var id_proof_file_required_flag = true;
			<?php if($mode == 'Add') { ?> if($("#id_proof_file_cropper").val() != "" || $("#id_proof_file_old").val() != "") { id_proof_file_required_flag = false; } <?php }
				?> 
			
			$("#id_proof_file").rules("add", 
			{ 
				required: id_proof_file_required_flag,
				check_valid_file:true, 
				valid_file_format:'.jpg,.jpeg,.png', 
				filesize_min:'75000',
				filesize_max:'500000' 
			});

			var aadhar_file_required_flag = true;
			<?php if($mode == 'Add') { ?> if($("#aadhar_file_cropper").val() != "" || $("#aadhar_file_old").val() != "") { aadhar_file_required_flag = false; } <?php }
				?> 
			
			$("#aadhar_file").rules("add", 
			{ 
				required: aadhar_file_required_flag,
				check_valid_file:true, 
				valid_file_format:'.jpg,.jpeg,.png', 
				filesize_min:'75000',
				filesize_max:'500000' 
			});
			
			var candidate_photo_required_flag = true;
			<?php if($mode == 'Add') { ?> if($("#candidate_photo_cropper").val() != "" || $("#candidate_photo_old").val() != "") { candidate_photo_required_flag = false; } <?php }
				?>

			$("#candidate_photo").rules("add", 
			{ 
				required: candidate_photo_required_flag,
				check_valid_file:true, 
				valid_file_format:'.jpg,.jpeg,.png', 
				filesize_min:'14000',
				filesize_max:'50000' 
			});
			
			var candidate_sign_required_flag = true;
			<?php if($mode == 'Add') { ?> if($("#candidate_sign_cropper").val() != "" || $("#candidate_sign_old").val() != "") { candidate_sign_required_flag = false; } <?php }
				?>

			$("#candidate_sign").rules("add", 
			{ 
				required: candidate_sign_required_flag,
				check_valid_file:true, 
				valid_file_format:'.jpg,.jpeg,.png', 
				filesize_min:'14000',
				filesize_max:'50000' 
			});

			var qualification_certificate_file_required_flag = true;
			<?php if($mode == 'Add') { ?> if($("#qualification_certificate_file_cropper").val() != "" || $("#qualification_certificate_file_old").val() != "") { qualification_certificate_file_required_flag = false; } <?php }
				?> 


			$("#qualification_certificate_file").rules("add", 
			{ 
				required: qualification_certificate_file_required_flag,
				check_valid_file:true, 
				valid_file_format:'.jpg,.jpeg,.png', 
				filesize_min:'75000', 
				filesize_max:'500000' 
			});
			


			var exp_certificate_required_flag = true;
			<?php if($mode == 'Add') { ?> if($("#exp_certificate_cropper").val() != "" || $("#exp_certificate_old").val() != "") { exp_certificate_required_flag = false; } <?php }
				?> 


			$("#exp_certificate").rules("add", 
			{ 
				required: exp_certificate_required_flag,
				check_valid_file:true, 
				valid_file_format:'.jpg,.jpeg,.png', 
				filesize_min:'75000', 
				filesize_max:'500000' 
			});
			
			var institute_idproof_required_flag = false;
			<?php if($mode == 'Add') { ?> if($("#institute_idproof_cropper").val() != "" || $("#institute_idproof_old").val() != "") { institute_idproof_required_flag = false; } <?php }
				?> 


			$("#institute_idproof").rules("add", 
			{ 
				required: institute_idproof_required_flag,
				check_valid_file:true, 
				valid_file_format:'.jpg,.jpeg,.png', 
				filesize_min:'75000', 
				filesize_max:'500000' 
			});
			var scanned_vis_imp_cert_required_flag = true;
			<?php if($mode == 'Add') { ?> if($("#scanned_vis_imp_cert_cropper").val() != "" || $("#scanned_vis_imp_cert_old").val() != "") { scanned_vis_imp_cert_required_flag = false; } <?php }
				?> 


			$("#scanned_vis_imp_cert").rules("add", 
			{ 
				required: scanned_vis_imp_cert_required_flag,
				check_valid_file:true, 
				valid_file_format:'.jpg,.jpeg,.png,.pdf', 
				filesize_min:'75000', 
				filesize_max:'500000' 
			});
			
			var scanned_orth_han_cert_required_flag = true;
			<?php if($mode == 'Add') { ?> if($("#scanned_orth_han_cert_cropper").val() != "" || $("#scanned_orth_han_cert_old").val() != "") { scanned_orth_han_cert_required_flag = false; } <?php }
				?> 


			$("#scanned_orth_han_cert").rules("add", 
			{ 
				required: scanned_orth_han_cert_required_flag,
				check_valid_file:true, 
				valid_file_format:'.jpg,.jpeg,.png,.pdf', 
				filesize_min:'75000', 
				filesize_max:'500000' 
			});
			var scanned_cer_palsy_cert_required_flag = true;
			<?php if($mode == 'Add') { ?> if($("#scanned_cer_palsy_cert_cropper").val() != "" || $("#scanned_cer_palsy_cert_old").val() != "") { scanned_cer_palsy_cert_required_flag = false; } <?php }
				?> 


			$("#scanned_cer_palsy_cert").rules("add", 
			{ 
				required: scanned_cer_palsy_cert_required_flag,
				check_valid_file:true, 
				valid_file_format:'.jpg,.jpeg,.png,.pdf', 
				filesize_min:'75000', 
				filesize_max:'500000' 
			});
			
			var declarationform_required_flag = true;
			<?php if($mode == 'Add') { ?> if($("#declarationform_cropper").val() != "" || $("#declarationform_old").val() != "") { declarationform_required_flag = false; } <?php }
				?> 


			$("#declarationform").rules("add", 
			{ 
				required: declarationform_required_flag,
				check_valid_file:true, 
				valid_file_format:'.jpg,.jpeg,.png,.pdf', 
				filesize_min:'75000', 
				filesize_max:'500000' 
			});
			
			
			$("#aadhar_no").rules("add", { allow_only_numbers:true, maxlength:12, minlength:12,  remote: { url: "<?php echo site_url('ncvet/candidate_registration/validation_check_aadhar_no_exist/0/1'); ?>", type: "post", data: {  } } });
			

			if($("#add_candidate_form").valid() == false)// Validate the form
			{
				$("#page_loader").hide();
			}
		}  
	$(document ).ready( function() 
    {
		
		//START : VALIDATE Id Proof Number
		$.validator.addMethod("validate_id_proof_number", function(value, element)
		{
			//1=>Aadhar Card, 2=>Driving Licence, 3=>Employee's Id, 4=>Pan Card, 5=>Passport
			var selectedIdProofType = $('input[name="id_proof_type"]:checked').val();
			
			if($.trim(value).length == 0) 
			{ 
				let err_msg1 = 'Please enter the APAAR ID/ABC ID';
				
				
				$.validator.messages.validate_id_proof_number = err_msg1;
				return false; 
			}
			else
			{
				var regex = /([0-9]){12}$/;
				if (regex.test(value)) { return true; } 
				else 
				{ 
					$.validator.messages.validate_id_proof_number = "Please enter valid APAAR ID/ABC ID";
					return false; 
				}
			}
		});//END : VALIDATE Id Proof Number

		//START : validate_email_id_verified
		$.validator.addMethod("validate_email_id_verified", function(value, element)
		{
			//1=>Aadhar Card, 2=>Driving Licence, 3=>Employee's Id, 4=>Pan Card, 5=>Passport
			var email_verify_status = $('#email_verify_status').val();
			//alert($('#email_verify_status').val());
			if(email_verify_status=='no') 
			{ 
				let err_msg1 = 'Please Verify Email id Before Proceeding';
				
				
				$.validator.messages.validate_email_id_verified = err_msg1;
				return false; 
			}
			else return true;
			
		});//END : validate_email_id_verified
		
		//START : validate_mobile_no_verified
		$.validator.addMethod("validate_mobile_no_verified", function(value, element)
		{
			//1=>Aadhar Card, 2=>Driving Licence, 3=>Employee's Id, 4=>Pan Card, 5=>Passport
			var mobile_verify_status = $('#mobile_verify_status').val();
			
			if(mobile_verify_status=='no') 
			{ 
				let err_msg1 = 'Please Verify Mobile No. Before Proceeding';
				
				
				$.validator.messages.validate_mobile_no_verified = err_msg1;
				return false; 
			}
			else return true;
			
		});//END : validate_mobile_no_verified

		//START : validate_qualification_field
		$.validator.addMethod("validate_qualification_field", function(value, element)
		{
			//1=>Aadhar Card, 2=>Driving Licence, 3=>Employee's Id, 4=>Pan Card, 5=>Passport
			var qualification = $('#qualification').val();
			
			if(qualification==1 && $('#experience:checked').val()=='N') 
			{ 
				let err_msg1 = 'You are not eligible for the selected criteria. Please select another';
				
				
				$.validator.messages.validate_qualification_field = err_msg1;
				return false; 
			}
			else return true;
			
		});//END : validate_qualification_field
		
		
		
		//START : VALIDATE GENDER
		$.validator.addMethod("validate_gender", function(value, element)
		{
			var selectedSalutation = $('#salutation').val();
			
			if($.trim(value).length == 0) { return true; }
			{
				if(typeof selectedSalutation != "undefined")
				{
					let current_gender = $.trim(value);
					if(selectedSalutation == 'Mr.')//Mr.
					{
						if(current_gender == 1) { return true; }
						else 
						{ 
							$.validator.messages.validate_gender = "Invalid gender selected";
							return false; 
						}
					} 
					else if(selectedSalutation == 'Mrs.' || selectedSalutation == 'Ms.') //Mrs. or Ms.
					{
						if(current_gender == 2) { return true; }
						else 
						{ 
							$.validator.messages.validate_gender = "Invalid gender selected";
							return false; 
						}
					}              
					else
					{
						return true;
					}
				}
				else
				{
					return true;
				}
			}
		});//END : VALIDATE GENDER
		
		$.validator.addMethod("validate_dob", function(value, element)
		{
			if($.trim(value).length == 0) { return true; }
			else
			{
				var current_val = $.trim(value);
				//var chk_dob_start_date = "<?php /* echo $dob_start_date; */ ?>";
				var chk_dob_end_date = "<?php echo $dob_end_date; ?>";
				
				//if(current_val >= chk_dob_start_date && current_val <= chk_dob_end_date)
				if(current_val <= chk_dob_end_date && current_val >= "<?php echo date('Y-m-d', strtotime("- 80 year", strtotime(date('Y-m-d')))) ?>")
				{ 
					return true;              
				}
				
				else 
				{ 
					$.validator.messages.validate_dob = "Select date of birth between <?php echo date('Y-m-d', strtotime("- 80 year", strtotime(date('Y-m-d')))) ?> to <?php  echo $dob_end_date;  ?> date";
					//$.validator.messages.validate_dob = "Select the date of birth before <?php echo date('Y-m-d', strtotime("+1days",strtotime($dob_end_date))); ?> date";
					return false;
				}
			}
		}); 
		
		
		

			var id_proof_file_required_flag = true;
			<?php if($mode == 'Add') { ?> if($("#id_proof_file_cropper").val() != "" || $("#id_proof_file_old").val() != "") { id_proof_file_required_flag = false; } <?php }
				?> 

			var aadhar_file_required_flag = true;
			<?php if($mode == 'Add') { ?> if($("#aadhar_file_cropper").val() != "" || $("#aadhar_file_old").val() != "") { aadhar_file_required_flag = false; } <?php }
				?> 
				var qualification_certificate_file_required_flag = true;
			<?php if($mode == 'Add') { ?> if($("#qualification_certificate_file_cropper").val() != "" || $("#qualification_certificate_file_old").val() != "") { qualification_certificate_file_required_flag = false; } <?php }
				?> 
				
				var candidate_photo_required_flag = true;
			<?php if($mode == 'Add') { ?> if($("#candidate_photo_cropper").val() != "" || $("#candidate_photo_old").val() != "") { candidate_photo_required_flag = false; } <?php }
				?>

			var candidate_sign_required_flag = true;
			<?php if($mode == 'Add') { ?> if($("#candidate_sign_cropper").val() != "" || $("#candidate_sign_old").val() != "") { candidate_sign_required_flag = false; } <?php }
				?>
				
			var exp_certificate_required_flag = true;
			<?php if($mode == 'Add') { ?> if($("#exp_certificate_cropper").val() != "" || $("#exp_certificate_old").val() != "") { exp_certificate_required_flag = false; } <?php }
				?>
			var institute_idproof_required_flag = false;
			<?php if($mode == 'Add') { ?> if($("#institute_idproof_cropper").val() != "" || $("#institute_idproof_old").val() != "") { institute_idproof_required_flag = false; } <?php }
				?>
			var declarationform_required_flag = true;
			<?php if($mode == 'Add') { ?> if($("#declarationform_cropper").val() != "" || $("#declarationform_old").val() != "") { declarationform_required_flag = false; } <?php }
				?>
			var scanned_vis_imp_cert_required_flag = true;
			<?php if($mode == 'Add') { ?> if($("#scanned_vis_imp_cert_cropper").val() != "" || $("#scanned_vis_imp_cert_old").val() != "") { scanned_vis_imp_cert_required_flag = false; } <?php }
				?>
			var scanned_orth_han_cert_required_flag = true;
			<?php if($mode == 'Add') { ?> if($("#scanned_orth_han_cert_cropper").val() != "" || $("#scanned_orth_han_cert_old").val() != "") { scanned_orth_han_cert_required_flag = false; } <?php }
				?>
			var scanned_cer_palsy_cert_required_flag = true;
			<?php if($mode == 'Add') { ?> if($("#scanned_cer_palsy_cert_cropper").val() != "" || $("#scanned_cer_palsy_cert_old").val() != "") { scanned_cer_palsy_cert_required_flag = false; } <?php }
				?>
				
			var form = $("#add_candidate_form").validate( 
		{
			onkeyup: function(element) { $(element).valid(); },  
			onfocusout: function(element) { $(element).valid(); },
			       
			rules:
			{
				salutation:{ required: true }, 
				first_name:{ required: true, allow_only_alphabets_and_space:true,maxlength:20 },
				middle_name:{ allow_only_alphabets_and_space:true,maxlength:20 },
				last_name:{ allow_only_alphabets_and_space:true,maxlength:20 },
				guardian_salutation:{ required: true }, 
				guardian_name:{ required: true, allow_only_alphabets_and_space:true,maxlength:80 },
				dob:{ required: true, dateFormat:'Y-m-d', validate_dob:true },
				gender:{ required: true, validate_gender: true },
				mobile_no:{ required: true, allow_only_numbers:true, first_zero_not_allowed:true, maxlength:10, minlength:10,remote: { url: "<?php echo site_url('ncvet/candidate_registration/validation_check_mobile_exist/0/1'); ?>", type: "post", data: { } } },            
				
				email_id:{ required: true, maxlength:80, valid_email:true,remote: { url: "<?php echo site_url('ncvet/candidate_registration/validation_check_email_exist/0/1'); ?>", type: "post", data: {  } } },
				//email_verify_status:{ validate_email_id_verified: true },
				//mobile_verify_status:{ validate_mobile_no_verified: true },
				qualification:{ required: true },
				address1:{ required: true, maxlength:75 },
				address2:{ maxlength:75 },
				address3:{ maxlength:75 },
				state:{ required: true },  
				city:{ required: true }, 
				district:{ required: true, allow_only_alphabets_and_numbers_and_space:true, maxlength:30 }, 
				pincode:{ required: true, allow_only_numbers:true, minlength:6, maxlength: 6, remote: { url: "<?php echo site_url('ncvet/candidate_registration/validation_check_valid_pincode/0/1'); ?>", type: "post", data: { "selected_state_code": function() { return $("#state").val(); } } } },  //check validation for pincode as per selected state

				address1_pr:{ required: true, maxlength:75 },
				address2_pr:{ maxlength:75 },
				address3_pr:{ maxlength:75 },
				state_pr:{ required: true },  
				city_pr:{ required: true }, 
				
				experience : {validate_qualification_field:true},
				district_pr:{ required: true, allow_only_alphabets_and_numbers_and_space:true, maxlength:30 }, 
				pincode_pr:{ required: true, allow_only_numbers:true, minlength:6, maxlength: 6, remote: { url: "<?php echo site_url('ncvet/candidate_registration/validation_check_valid_pincode_pr/0/1'); ?>", type: "post", data: { "selected_state_code": function() { return $("#state_pr").val(); } } } },  //check validation for pincode as per selected state
				
				id_proof_number:{ validate_id_proof_number: true, remote: { url: "<?php echo site_url('ncvet/candidate_registration/validation_check_id_proof_number_exist/0/1'); ?>", type: "post", data: {  } } },
				id_proof_file:{ required: id_proof_file_required_flag, check_valid_file:true, valid_file_format:'.jpg,.jpeg,.png', filesize_min:'75000', filesize_max:'500000' }, //use size in bytes //filesize_max: 1MB : 1000000 
				aadhar_file:{ required: aadhar_file_required_flag, check_valid_file:true, valid_file_format:'.jpg,.jpeg,.png', filesize_min:'75000', filesize_max:'500000' }, //use size in bytes //filesize_max: 1MB : 1000000 
				qualification_certificate_file:{

					 required: function(element) {
							return (
							$('.qualification_certificate_file_btn').is(':visible') &&
							($("#qualification_certificate_file_cropper").val() === "" || $("#qualification_certificate_file_old").val() === "")
							);
						},
					 check_valid_file:true, valid_file_format:'.jpg,.jpeg,.png,.pdf', filesize_min:'75000', filesize_max:'500000' }, //use size in bytes //filesize_max: 1MB : 1000000 
				exp_certificate:{  required: function(element) {
							return (
							$('.exp_certificate_btn').is(':visible') &&
							($("#exp_certificate_cropper").val() === "" || $("#exp_certificate_old").val() === "")
							);
						},check_valid_file:true, valid_file_format:'.jpg,.jpeg,.png,.pdf', filesize_min:'75000', filesize_max:'500000' }, //use size in bytes //filesize_max: 1MB : 1000000 
				institute_idproof:{check_valid_file:true, valid_file_format:'.jpg,.jpeg,.png,.pdf', filesize_min:'75000', filesize_max:'500000' }, //use size in bytes //filesize_max: 1MB : 1000000 
				candidate_photo:{ required: candidate_photo_required_flag, 
				check_valid_file:true, valid_file_format:'.jpg,.jpeg,.png', filesize_min:'14000', filesize_max:'50000' }, //use size in bytes //filesize_max: 1MB : 1000000 
				
				candidate_sign:{ required: candidate_sign_required_flag,  check_valid_file:true, valid_file_format:'.jpg,.jpeg,.png', filesize_min:'14000', filesize_max:'50000' }, //use size in bytes //filesize_max: 1MB : 1000000 
				aadhar_no:{ required:true,allow_only_numbers:true, maxlength:12, minlength:12,  remote: { url: "<?php echo site_url('ncvet/candidate_registration/validation_check_aadhar_no_exist/0/1'); ?>", type: "post", data: { } } },
				scanned_vis_imp_cert:{ required: function(element) {
							return (
							$('.scanned_vis_imp_cert_btn').is(':visible') &&
							($("#scanned_vis_imp_cert_cropper").val() === "" || $("#scanned_vis_imp_cert_old").val() === "")
							);
						},check_valid_file:true, valid_file_format:'.jpg,.jpeg,.png', filesize_min:'75000', filesize_max:'500000' }, //use size in bytes //filesize_max: 1MB : 1000000 
				scanned_orth_han_cert:{  required: function(element) {
							return (
							$('.scanned_orth_han_cert_btn').is(':visible') &&
							($("#scanned_orth_han_cert_cropper").val() === "" || $("#scanned_orth_han_cert_old").val() === "")
							);
						},check_valid_file:true, valid_file_format:'.jpg,.jpeg,.png', filesize_min:'75000', filesize_max:'500000' }, //use size in bytes //filesize_max: 1MB : 1000000 
				scanned_cer_palsy_cert:{ required: function(element) {
							return (
							$('.scanned_cer_palsy_cert_btn').is(':visible') &&
							($("#scanned_cer_palsy_cert_cropper").val() === "" || $("#scanned_cer_palsy_cert_old").val() === "")
							);
						},check_valid_file:true, valid_file_format:'.jpg,.jpeg,.png', filesize_min:'75000', filesize_max:'500000' }, //use size in bytes //filesize_max: 1MB : 1000000 
				declarationform:{ required: function(element) {
							return (
							$('.declarationform_btn').is(':visible') &&
							($("#declarationform_cropper").val() === "" || $("#declarationform_old").val() === "")
							);
						},check_valid_file:true, valid_file_format:'.jpg,.jpeg,.png', filesize_min:'75000', filesize_max:'500000' }, //use size in bytes //filesize_max: 1MB : 1000000 
				declare:{ required: true }, 
				ncvet_candidate_enrollment_captcha: { required: true, remote: { url: "<?php echo site_url('ncvet/candidate_registration/validation_check_captcha/0/1'); ?>", type: "post" } }       

			},
			messages:
			{
				salutation: { required: "Please select the candidate name (salutation)" },
				first_name: { required: "Please enter the first name" },
				middle_name: { },
				last_name: { },
				dob: { required: "Please select the date of birth", dateFormat:"Please enter the date of birth like yyyy-mm-dd" },
				gender: { required: "Please select the gender" },
				mobile_no: { required: "Please enter the mobile number", minlength: "Please enter 10 numbers in mobile number", maxlength: "Please enter 10 numbers in mobile number", remote: "The mobile number is already exist" },
				guardian_salutation: { required: "Please select the Guardian name (salutation)" },
				guardian_name: { required: "Please enter the guardian name" },
				
				email_id: { required: "Please enter the email id", valid_email: "Please enter the valid email id", remote: "The email id is already exist"  },
				email_verify_status : {validate_email_id_verified : 'Please verify Email Id before proceeding'},
				mobile_verify_status : {validate_mobile_no_verified : 'Please Verify Mobile No. before proceeding'},
				
				qualification: { required: "Please select the Eligibility" },
				address1: { required: "Please enter the address line-1" },
				address2: { },
				address3: { },
				state: { required: "Please select the state" },
				city: { required: "Please select the city" },
				district: { required: "Please enter the district" },
				pincode: { required: "Please enter the pincode", minlength: "Please enter 6 numbers in pincode", maxlength: "Please enter 6 numbers in pincode", remote: "Please enter valid pincode as per selected city" },
				address1_pr: { required: "Please enter the address line-1" },
				address2_pr: { },
				address3_pr: { },
				state_pr: { required: "Please select the state" },
				city_pr: { required: "Please select the city" },
				district_pr: { required: "Please enter the district" },
				pincode_pr: { required: "Please enter the pincode", minlength: "Please enter 6 numbers in pincode", maxlength: "Please enter 6 numbers in pincode", remote: "Please enter valid pincode as per selected city" },
				
				id_proof_number: { remote : "The APAAR ID/ABC ID is already exist or not in proper format" },
				id_proof_file: { required: "Please upload the proof of identity", valid_file_format:"Please upload only .jpg, .jpeg, .png files", filesize_min:"Please upload file greater than 75KB", filesize_max:"Please upload file less than 100KB" },
				aadhar_file: { required: "Please upload the Aadhar File", valid_file_format:"Please upload only .jpg, .jpeg, .png files", filesize_min:"Please upload file greater than 75KB", filesize_max:"Please upload file less than 100KB" },
				qualification_certificate_file: { required: "Please upload the qualification certificate", valid_file_format:"Please upload only .jpg, .jpeg, .png, .pdf files", filesize_min:"Please upload file greater than 75KB", filesize_max:"Please upload file less than 500KB" },
				exp_certificate: { required: "Please upload the experience certificate", valid_file_format:"Please upload only .jpg, .jpeg, .png, .pdf files", filesize_min:"Please upload file greater than 75KB", filesize_max:"Please upload file less than 500KB" },
				institute_idproof: { required: "Please upload the institute idproof", valid_file_format:"Please upload only .jpg, .jpeg, .png, .pdf files", filesize_min:"Please upload file greater than 75KB", filesize_max:"Please upload file less than 500KB" },
				candidate_photo: { required: "Please upload the passport-size photo of the candidate", valid_file_format:"Please upload only .jpg, .jpeg, .png files", filesize_min:"Please upload file greater than 14KB", filesize_max:"Please upload file less than 50KB" },
				candidate_sign: { required: "Please upload the signature of the candidate", valid_file_format:"Please upload only .jpg, .jpeg, .png files", filesize_min:"Please upload file greater than 14KB", filesize_max:"Please upload file less than 50KB" },
				aadhar_no: { minlength: "Please enter 12 numbers in aadhar number", maxlength: "Please enter 12 numbers in aadhar number", remote: "The aadhar number is already exist" },
				scanned_vis_imp_cert: { required: "Please upload the PWD Certificate", valid_file_format:"Please upload only .jpg, .jpeg, .png, .pdf files", filesize_min:"Please upload file greater than 75KB", filesize_max:"Please upload file less than 500KB" },
				scanned_orth_han_cert: { required: "Please upload the PWD Certificate", valid_file_format:"Please upload only .jpg, .jpeg, .png, .pdf, .pdf files", filesize_min:"Please upload file greater than 75KB", filesize_max:"Please upload file less than 500KB" },
				scanned_cer_palsy_cert: { required: "Please upload the PWD Certificate", valid_file_format:"Please upload only .jpg, .jpeg, .png files", filesize_min:"Please upload file greater than 75KB", filesize_max:"Please upload file less than 500KB" },
				declarationform: { required: "Please upload the Declaration", valid_file_format:"Please upload only .jpg, .jpeg, .png, .pdf files", filesize_min:"Please upload file greater than 75KB", filesize_max:"Please upload file less than 500KB" },
				declare: { required: "Please Confirm the Declaration" },
				ncvet_candidate_enrollment_captcha: { required: "Please enter the code", remote: "Please enter the valid code" }
				
			}, 
			errorPlacement: function(error, element) // For replace error 
			{
				
				if (element.attr("name") == "salutation") { error.insertAfter("#salutation_err"); }
				
				else if (element.attr("name") == "first_name") { error.insertAfter("#first_name_err"); }
				else if (element.attr("name") == "middle_name") { error.insertAfter("#middle_name_err"); }
				else if (element.attr("name") == "last_name") { error.insertAfter("#last_name_err"); }
				else if (element.attr("name") == "guardian_salutation") { error.insertAfter("#guardian_salutation_err"); }
				else if (element.attr("name") == "guardian_name") { error.insertAfter("#guardian_name_err"); }
				else if (element.attr("name") == "dob") { error.insertAfter("#dob_err"); }
				else if (element.attr("name") == "gender") { error.insertAfter("#gender_err"); }
				else if (element.attr("name") == "email_id") { error.insertAfter("#email_id_err"); }
				// else if (element.attr("name") == "mobile_no") { error.insertAfter("#mobile_no_err"); }
				// else if (element.attr("name") == "age") { error.insertAfter("#age_err"); }
				// else if (element.attr("name") == "declare") { error.insertAfter("#declare_err"); }
				// else if (element.attr("name") == "ncvet_candidate_enrollment_captcha") { error.insertAfter("#ncvet_candidate_enrollment_captchas_err"); }
				else if (element.attr("name") == "email_verify_status") {  error.insertAfter("#email_id_err"); }
				else if (element.attr("name") == "mobile_verify_status") {  error.insertAfter("#mobile_no_err"); }
				else if (element.attr("name") == "qualification") { error.insertAfter("#qualification_err"); }
				else if (element.attr("name") == "address1") { error.insertAfter("#address1_err"); }
				else if (element.attr("name") == "address2") {  error.insertAfter("#address2_err"); }
				else if (element.attr("name") == "address3") {  error.insertAfter("#address3_err"); }
				// else if (element.attr("name") == "pincode") {  error.insertAfter("#pincode_err"); }
				// else if (element.attr("name") == "pincode_pr") {  error.insertAfter("#pincode_pr_err"); }
				else if (element.attr("name") == "state") { error.insertAfter("#state_err"); }
				else if (element.attr("name") == "city") { error.insertAfter("#city_err"); }
				else if (element.attr("name") == "district") { error.insertAfter("#district_err"); }
				else if (element.attr("name") == "address1_pr") { error.insertAfter("#address1_pr_err"); }
				else if (element.attr("name") == "address2_pr") { error.insertAfter("#address2_pr_err"); }
				else if (element.attr("name") == "address3_pr") { error.insertAfter("#address3_pr_err"); }
				else if (element.attr("name") == "state_pr") { error.insertAfter("#state_pr_err"); }
				else if (element.attr("name") == "city_pr") { error.insertAfter("#city_pr_err"); }
				else if (element.attr("name") == "district_pr") { error.insertAfter("#district_pr_err"); }
				else if (element.attr("name") == "experience") { error.insertAfter("#experience_error"); }

				else if (element.attr("name") == "id_proof_number") { error.insertAfter("#id_proof_number_err"); }
				else if (element.attr("name") == "id_proof_file") { $("#id_proof_file_err").next("label.error").remove(); error.insertAfter("#id_proof_file_err"); }
				else if (element.attr("name") == "aadhar_file") { $("#aadhar_file_err").next("label.error").remove(); error.insertAfter("#aadhar_file_err"); }
				else if (element.attr("name") == "qualification_certificate_file") { $("#qualification_certificate_file_err").next("label.error").remove(); error.insertAfter("#qualification_certificate_file_err"); }
				else if (element.attr("name") == "exp_certificate") { $("#exp_certificate_err").next("label.error").remove(); error.insertAfter("#exp_certificate_err"); }
				else if (element.attr("name") == "institute_idproof") { $("#institute_idproof_err").next("label.error").remove(); error.insertAfter("#institute_idproof_err"); }
				else if (element.attr("name") == "candidate_photo") { $("#candidate_photo_err").next("label.error").remove(); error.insertAfter("#candidate_photo_err"); }
				else if (element.attr("name") == "candidate_sign") { $("#candidate_sign_err").next("label.error").remove(); error.insertAfter("#candidate_sign_err"); }
				else if (element.attr("name") == "aadhar_no") { error.insertAfter("#aadhar_no_err"); }
				else if (element.attr("name") == "scanned_vis_imp_cert") { $("#scanned_vis_imp_cert_err").next("label.error").remove(); error.insertAfter("#scanned_vis_imp_cert_err"); }
				
				else if (element.attr("name") == "scanned_orth_han_cert") { $("#scanned_orth_han_cert_err").next("label.error").remove(); error.insertAfter("#scanned_orth_han_cert_err"); }
				
				else if (element.attr("name") == "scanned_cer_palsy_cert") { $("#scanned_cer_palsy_cert_err").next("label.error").remove(); error.insertAfter("#scanned_cer_palsy_cert_err"); }
				else if (element.attr("name") == "declarationform") { $("#declarationform_err").next("label.error").remove(); error.insertAfter("#declarationform_err"); }
				else if (element.attr("name") == "declare") { error.insertAfter("#declare_err"); }
				else { error.insertAfter(element); }

				setTimeout(function() {
					if (element.attr("name") == "scanned_cer_palsy_cert" || element.attr("name") == "scanned_orth_han_cert" || element.attr("name") == "scanned_vis_imp_cert" || element.attr("name") == "declarationform" || element.attr("name") == "candidate_sign" || element.attr("name") == "candidate_photo" || element.attr("name") == "institute_idproof" || element.attr("name") == "exp_certificate" || element.attr("name") == "qualification_certificate_file" || element.attr("name") == "aadhar_file" || element.attr("name") == "id_proof_file" || element.attr("name") == "institute_idproof"  ) {
						$("#scanned_cer_palsy_cert-error").removeAttr("for");
						$("#scanned_orth_han_cert-error").removeAttr("for"); 
						$("#scanned_vis_imp_cert-error").removeAttr("for"); 
						$("#declarationform-error").removeAttr("for"); 
						$("#candidate_sign-error").removeAttr("for"); 
						$("#candidate_photo-error").removeAttr("for");  
						$("#institute_idproof-error").removeAttr("for"); 
						$("#exp_certificate-error").removeAttr("for"); 
						$("#qualification_certificate_file-error").removeAttr("for"); 
						$("#aadhar_file-error").removeAttr("for"); 
						$("#id_proof_file-error").removeAttr("for"); 
						$("#institute_idproof-error").removeAttr("for"); 
					}
				}, 100);
			},          
			submitHandler: function(form) 
			{
				let form_action = $("#form_action").val();
				if(form_action == 3)
				{
					show_preview_modal();
				}
				else
				{
					$("#page_loader").hide();
					swal({ title: "Please confirm", text: "Proceed to Preview before Submit", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
					{ 
						$("#page_loader").show();
						
						if(form_action == '1')
						{
							$("#submit_btn_outer1").html('<input type="button" class="btn btn-primary" id="submitFirst" name="submitFirst" value="Submit I " style="cursor:wait">');
						}
						else if(form_action == '2')
						{
							$("#submit_btn_outer2").html('<input type="submit" class="btn btn-primary" id="submitAll" name="submitAll" onclick="validate_all_details(2)" value="Preview Details">   ');
						}

						const formSelector = '#add_candidate_form';
							
						const formData = {};

						$(`${formSelector} :input[name]`).each(function () {
							const name = $(this).attr('name');
							const type = $(this).attr('type');

							if (type === 'checkbox') {
								formData[name] = $(this).prop('checked');
							} 
							 else if (type === 'radio') {
								// Get the checked radio with this name
								const checkedRadio = $(`${formSelector} input[name="${name}"]:checked`);
								formData[name] = checkedRadio.length ? checkedRadio.val() : null;
							} 
							else {
								formData[name] = $(this).val();
							}
						});

						$(formSelector).find('select').each(function () {
							const name = $(this).attr('name');
							formData[name] = $(this).val();
						});

						sessionStorage.setItem('ncvet_candidate_enroll_data', JSON.stringify(formData));

								
							
						form.submit();
					});   
				}
			}
		});

			 

      });
    </script>
    <?php $this->load->view('ncvet/common/inc_bottom_script'); ?>
  </body>
</html>