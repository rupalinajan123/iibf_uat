<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('iibfbcbf/inc_header'); ?>    
  </head>
  
	<body class="fixed-sidebar">
    <?php $this->load->view('iibfbcbf/common/inc_loader'); ?>
		
		<div id="wrapper">
      <?php $this->load->view('iibfdra/candidate/inc_sidebar_candidate'); ?>		
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('iibfdra/candidate/inc_topbar_candidate'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>Update Profile</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfdra/candidate/dashboard_candidate'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item active"> <strong>Update Profile</strong></li>
            </ol>
          </div>
					<div class="col-lg-2"> </div>
        </div>			
				
				<div class="wrapper wrapper-content animated fadeInRight">
          <div class="row">
						<div class="col-lg-12">
							<div class="ibox">
              <div class="ibox-content">
									<form method="post" action="<?php echo site_url('iibfdra/candidate/dashboard_candidate/update_profile'); ?>" id="add_candidate_form" enctype="multipart/form-data" autocomplete="off">
										<h4 class="custom_form_title" style="margin: -15px -20px 15px -20px !important;">Basic Details</h4>
										
										<?php 
											$salutation_master_arr = array('Mr.', 'Mrs.', 'Ms.');
											$qualification_arr = array('1'=>'Under_Graduate', '2'=>'Graduate');
											$id_proof_type_arr = array('1'=>'Aadhaar Card', '2'=>'Driving Licence', '3'=>'Employee ID', '4'=>'Pan Card', '5'=>'Passport');
										  $qualification_certificate_type_arr = array('1'=>'tenth', '2'=>'twelth', '3'=>'graduate', '4'=>'post_graduate'); 
                    ?>
										
										<div class="row">
                      <div class="col-xl-6 col-lg-6"><?php /* Registration Number */ ?>
                        <div class="form-group">
                          <label class="form_label">Registration Number <sup class="text-danger"></sup></label>
                          <input type="text" value="<?php echo $form_data[0]['regnumber']; ?>" class="form-control" readonly disabled />
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* Training ID */ ?>
                        <div class="form-group">
                          <label class="form_label">Training ID <sup class="text-danger"></sup></label>
                          <input type="text" value="<?php echo $form_data[0]['training_id']; ?>" class="form-control" readonly disabled />
                        </div>					
                      </div>
												
                      <div class="col-xl-3 col-lg-3"><?php /* Candidate Name (Salutation) */ ?>
                        <div class="form-group">
                          <?php $chk_salutation = $form_data[0]['namesub']; ?>
                          <label class="form_label">Candidate Name (Salutation) <sup class="text-danger"></sup></label>

                          <?php $disp_salution = '';
                          if(count($salutation_master_arr) > 0)
                          {
                            foreach($salutation_master_arr as $sal_val)
                            {
                              if($chk_salutation == $sal_val) { $disp_salution = $sal_val; }
                            }
                          } ?>
                          <input type="text" value="<?php echo $disp_salution; ?>" class="form-control" readonly disabled />
                        </div>					
                      </div>
												
                      <div class="col-xl-3 col-lg-3"><?php /* First Name */ ?>
                        <div class="form-group">
                          <label class="form_label">First Name <sup class="text-danger"></sup></label>
                          <input type="text" value="<?php echo $form_data[0]['firstname']; ?>" class="form-control" readonly disabled />
                        </div>					
                      </div>
												
                      <div class="col-xl-3 col-lg-3"><?php /* Middle Name */ ?>
                        <div class="form-group">
                          <label class="form_label">Middle Name <sup class="text-danger"></sup></label>
                          <input type="text" value="<?php echo $form_data[0]['middlename']; ?>" class="form-control" readonly disabled />
                        </div>					
                      </div>
												
                      <div class="col-xl-3 col-lg-3"><?php /* Last Name */ ?>
                        <div class="form-group">
                          <label class="form_label">Last Name <sup class="text-danger"></sup></label>
                          <input type="text" value="<?php echo $form_data[0]['lastname']; ?>" class="form-control" readonly disabled />
                        </div>					
                      </div>
												
                      <div class="col-xl-6 col-lg-6"><?php /* Date of Birth */ ?>
                        <div class="form-group">
                          <label class="form_label">Date of Birth <sup class="text-danger"></sup></label>
                          <input type="text" value="<?php if($form_data[0]['dateofbirth'] != '0000-00-00') { echo $form_data[0]['dateofbirth']; } ?>" class="form-control" readonly disabled />
                        </div>					
                      </div>
												
                      <div class="col-xl-6 col-lg-6"><?php /* Gender */ ?>
                        <div class="form-group">
                          <label class="form_label">Gender <sup class="text-danger"></sup></label>
                          <input type="text" value="<?php if($form_data[0]['gender'] == 'male') { echo 'Male'; } else if($form_data[0]['gender'] == 'female') { echo 'Female'; } ?>" class="form-control" readonly disabled />
                        </div>					
                      </div>
												
                      <div class="col-xl-6 col-lg-6"><?php /* Mobile Number */ ?>
                        <div class="form-group">
                          <label for="mobile_no" class="form_label">Mobile Number <sup class="text-danger">*</sup></label>
                          <input type="text" name="mobile_no" id="mobile_no" value="<?php echo $form_data[0]['mobile_no']; ?>" placeholder="Mobile Number *" class="form-control custom_input allow_only_numbers basic_form" required maxlength="10" minlength="10" />
                          
                          <?php if(form_error('mobile_no')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('mobile_no'); ?></label> <?php } ?>
                        </div>					
                      </div>
												
                      <div class="col-xl-6 col-lg-6"><?php /* Alternate Mobile Number */ ?>
                        <div class="form-group">
                          <label for="alt_mobile_no" class="form_label">Alternate Mobile Number <sup class="text-danger"></sup></label>
                          <input type="text" name="alt_mobile_no" id="alt_mobile_no" value="<?php echo $form_data[0]['alt_mobile_no']; ?>" placeholder="Alternate Mobile Number" class="form-control custom_input allow_only_numbers basic_form" maxlength="10" minlength="10" />
                          
                          <?php if(form_error('alt_mobile_no')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('alt_mobile_no'); ?></label> <?php } ?>
                        </div>					
                      </div>
												
                      <div class="col-xl-6 col-lg-6"><?php /* Email id */ ?>
                        <div class="form-group">
                          <label for="email_id" class="form_label">Email id <sup class="text-danger">*</sup></label>
                          <input type="text" name="email_id" id="email_id" value="<?php echo $form_data[0]['email_id']; ?>" placeholder="Email id *" class="form-control custom_input basic_form" required maxlength="80" />
                          <note class="form_note" id="email_id_err">Note: Please enter only 80 characters</note>
                          
                          <?php if(form_error('email_id')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('email_id'); ?></label> <?php } ?>
                        </div>					
                      </div>
												
                      <div class="col-xl-6 col-lg-6"><?php /* Alternate Email id */ ?>
                        <div class="form-group">
                          <label for="alt_email_id" class="form_label">Alternate Email id <sup class="text-danger"></sup></label>
                          <input type="text" name="alt_email_id" id="alt_email_id" value="<?php echo $form_data[0]['alt_email_id']; ?>" placeholder="Alternate Email id" class="form-control custom_input basic_form" maxlength="80" />
                          <note class="form_note" id="alt_email_id_err">Note: Please enter only 80 characters</note>
                          
                          <?php if(form_error('alt_email_id')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('alt_email_id'); ?></label> <?php } ?>
                        </div>					
                      </div>
												
                      <div class="col-xl-6 col-lg-6"><?php /* Qualification */ ?>
                        <div class="form-group">
                          <label class="form_label">Qualification <sup class="text-danger"></sup></label>
                          <div id="qualification_err">   
                            <?php $disp_qualification = '';
                            if(count($qualification_arr) > 0)
                            {
                              foreach($qualification_arr as $key => $val)
                              {
                                if($form_data[0]['qualification_type'] == $val) { $disp_qualification = $val; }
                              }
                            } ?>
                            <input type="text" value="<?php echo $form_data[0]['qualification_type']; ?>" class="form-control" readonly disabled />
                          </div>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* Qualification */ ?>
                        <div class="form-group">
                          <label class="form_label">Source of Candidate <sup class="text-danger"></sup></label>
                          <div id="source_of_candidate">   
                            <input type="text" value="<?php echo $form_data[0]['source_of_candidate']; ?>" class="form-control" readonly disabled />
                          </div>
                        </div>					
                      </div>
                    </div>										
										
										<h4 class="custom_form_title" style="margin: 20px -20px 15px -20px !important;">Other Details</h4>
										<div class="row">											
											<div class="col-xl-12 col-lg-12"><?php /* Address Line-1 */ ?>
												<div class="form-group">
													<label for="address1" class="form_label">Address Line-1 <sup class="text-danger">*</sup></label>
													<input type="text" name="address1" id="address1" placeholder="Address Line-1 *" class="form-control custom_input ignore_required" maxlength="75" required value="<?php echo $form_data[0]['address1']; ?>" />
													
													<note class="form_note" id="address1_err">Note: Please enter only 75 characters</note>
													
													<?php if(form_error('address1')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address1'); ?></label> <?php } ?>
												</div>					
											</div>
											
											<div class="col-xl-12 col-lg-12"><?php /* Address Line-2 */ ?>
												<div class="form-group">
													<label for="address2" class="form_label">Address Line-2 <sup class="text-danger"></sup></label>
													<input type="text" name="address2" id="address2" placeholder="Address Line-2" class="form-control custom_input" maxlength="75" value="<?php echo $form_data[0]['address2']; ?>" />
													
													<note class="form_note" id="address2_err">Note: Please enter only 75 characters</note>
													
													<?php if(form_error('address2')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address2'); ?></label> <?php } ?>
												</div>					
											</div>
											
											<div class="col-xl-12 col-lg-12"><?php /* Address Line-3 */ ?>
												<div class="form-group">
													<label for="address3" class="form_label">Address Line-3 <sup class="text-danger"></sup></label>
													<input type="text" name="address3" id="address3" placeholder="Address Line-3" class="form-control custom_input" maxlength="75" value="<?php  echo $form_data[0]['address3']; ?>" />
													
													<note class="form_note" id="address3_err">Note: Please enter only 75 characters</note>
													
													<?php if(form_error('address3')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address3'); ?></label> <?php } ?>
												</div>					
											</div>
											
											<div class="col-xl-12 col-lg-12"><?php /* Address Line-4 */ ?>
												<div class="form-group">
													<label for="address4" class="form_label">Address Line-4 <sup class="text-danger"></sup></label>
													<input type="text" name="address4" id="address4" placeholder="Address Line-4" class="form-control custom_input" maxlength="75" value="<?php echo $form_data[0]['address4']; ?>" />
													
													<note class="form_note" id="address4_err">Note: Please enter only 75 characters</note>
													
													<?php if(form_error('address4')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address4'); ?></label> <?php } ?>
												</div>					
											</div>
											
											<?php $chk_state = $form_data[0]['state']; ?>
											<div class="col-xl-6 col-lg-6"><?php /* Select State */ ?>
												<div class="form-group">
													<label for="state" class="form_label">Select State <sup class="text-danger">*</sup></label>
													<select name="state" id="state" class="form-control chosen-select ignore_required" required onchange="get_city_ajax(this.value); validate_input('state'); ">
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
											
											<div class="col-xl-6 col-lg-6"><?php /* Select City */ ?>
												<div class="form-group">
													<label for="city" class="form_label">City <sup class="text-danger">*</sup></label>
													<div id="city_outer">
														<select class="form-control chosen-select ignore_required" name="city" id="city" required onchange="validate_input('city'); ">
															<?php $selected_state_val = '';
																$selected_state_val = $form_data[0]['state'];
																
																if($selected_state_val != "")
																{
																	$city_data = $this->master_model->getRecords('city_master', array('state_code' => $selected_state_val, 'city_delete' => '0'), 'id, city_name', array('city_name'=>'ASC'));
																	
																	if(count($city_data) > 0)
																	{ ?>
																	<option value="">Select City</option>
																	<?php foreach($city_data as $city)
																		{ ?>
																		<option value="<?php echo $city['city_name']; ?>" <?php if($form_data[0]['city'] == $city['city_name']) { echo "selected"; } ?>><?php echo $city['city_name']; ?></option>
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
													<input type="text" name="district" id="district" value="<?php echo $form_data[0]['district']; ?>" placeholder="District *" class="form-control custom_input allow_only_alphabets_and_numbers_and_space ignore_required" maxlength="30" required/>
													<note class="form_note" id="district_err">Note: Please enter only 30 characters</note>
													
													<?php if(form_error('district')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('district'); ?></label> <?php } ?>
												</div>					
											</div>
											
											<div class="col-xl-6 col-lg-6"><?php /* Pincode */ ?>
												<div class="form-group">
													<label for="pincode" class="form_label">Pincode <sup class="text-danger">*</sup></label>
													<input type="text" name="pincode" id="pincode" value="<?php echo $form_data[0]['pincode']; ?>" placeholder="Pincode *" class="form-control custom_input allow_only_numbers ignore_required" required maxlength="6" minlength="6" />
													
													<?php if(form_error('pincode')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('pincode'); ?></label> <?php } ?>
												</div>					
											</div>

                      <?php $chk_bank_associated = $form_data[0]['bank_associated'];  ?>                      
                      <!-- <div class="col-xl-6 col-lg-6 bank_associated_outer">
												<div class="form-group">
													<label class="form_label">Bank associated with <sup class="text-danger show_hide_star"></sup></label>

                          <?php $disp_bank_associated = '';
                          if(count($bank_associated_master_data) > 0)
                          {
                            foreach($bank_associated_master_data as $res)
                            {
                              if($chk_bank_associated == $res['bank_code']) { $disp_bank_associated = $res['bank_name']; }
                            }
                          } ?>
                          <input type="text" value="<?php echo $disp_bank_associated; ?>" class="form-control" readonly disabled />
												</div>					
											</div> -->
                      
                      <?php $bank_associated_other_cls = 'hide';                      
                      if($form_data[0]['bank_associated'] == 'Other') { $bank_associated_other_cls = "";  } ?>

                      <!-- <div class="col-xl-6 col-lg-6 bank_associated_other_outer_cls <?php echo $bank_associated_other_cls; ?>">
												<div class="form-group">
													<label class="form_label">Other Bank associated with <sup class="text-danger"></sup></label>
                          <input type="text" value="<?php echo $form_data[0]['bank_associated_other']; ?>" class="form-control" readonly disabled />
												</div>					
											</div>
											
											<div class="col-xl-6 col-lg-6"><?php /* Corporate BC associated with */ ?>
												<div class="form-group">
													<label class="form_label">Corporate BC associated with <sup class="text-danger"></sup></label>
                          <input type="text" value="<?php echo $form_data[0]['corporate_bc_associated']; ?>" class="form-control" readonly disabled />
												</div>					
											</div> -->
										</div> 
										
										<h4 class="custom_form_title" style="margin: 20px -20px 15px -20px !important;">Upload Documents</h4>
										<div class="row">
                      <div class="col-xl-6 col-lg-6">
												<div class="form-group">
													<label class="form_label">Qualification Certificate Type <sup class="text-danger"></sup></label>
													<div id="qualification_certificate_type_err">  
                            <?php $disp_qualification_certificate_type = '';
                            if(count($qualification_certificate_type_arr) > 0)
                            {
                              foreach($qualification_certificate_type_arr as $key => $val)
                              {
                                if($form_data[0]['qualification'] == $val) { $disp_qualification_certificate_type = $val; }
                              }
                            } ?>
                            <input type="text" value="<?php echo $disp_qualification_certificate_type; ?>" class="form-control" readonly disabled />
													</div>
												</div>					
											</div>
											
											<div class="col-xl-6 col-lg-6">
												<div class="form-group">
													<div class="img_preview_input_outer">
														<label class="form_label">Uploaded Qualification Certificate <sup class="text-danger"></sup></label>
                          </div>
													
													<div id="qualification_certificate_file_preview" class="upload_img_preview">
														<?php 
                              $preview_candidate_id = $form_data[0]['regid']; 
                              $preview_first_name = $form_data[0]['firstname']; 
                              $preview_training_id = $form_data[0]['training_id']; 
                              
                              $preview_qualification_certificate_file = '';
															if($form_data[0]['quali_certificate'] != "") 
															{ 
																$preview_qualification_certificate_file = $form_data[0]['quali_certificate'];
																$preview_qualification_certificate_file = base_url($qualification_certificate_file_path.'/'.$preview_qualification_certificate_file);
															}
															
															if($preview_qualification_certificate_file != "")
															{ ?>
                                <a href="<?php echo $preview_qualification_certificate_file."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Qualification Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                  <img src="<?php echo $preview_qualification_certificate_file."?".time(); ?>">
                                </a>
															<?php }
															else
															{
																echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
															} ?>
													</div><div class="clearfix"></div>
												</div>
											</div>

											<div class="col-xl-12 col-lg-12"><?php /* ID Proof Type */ ?>
												<div class="form-group">
													<label class="form_label">ID Proof Type <sup class="text-danger"></sup></label>
													<div id="id_proof_type_err">   
                            <?php $disp_id_proof_type = '';
                            if(count($id_proof_type_arr) > 0)
                            {
                              foreach($id_proof_type_arr as $id_proof_type_key => $id_proof_type_val)
                              {
                                if($form_data[0]['idproof'] == $id_proof_type_key) { $disp_id_proof_type = $id_proof_type_val; }
                              }
                            } ?>
                            <input type="text" value="<?php echo $disp_id_proof_type; ?>" class="form-control" readonly disabled />
													</div>
												</div>					
											</div>
											
											<div class="col-xl-6 col-lg-6"><?php /* Id Proof Number */ ?>
												<div class="form-group">
													<label class="form_label">Id Proof Number <sup class="text-danger">*</sup></label>
                          <input type="text" value="<?php echo $form_data[0]['idproof_no']; ?>" class="form-control" readonly disabled />
												</div>					
											</div>

                      <div class="col-xl-6 col-lg-6"><?php /* Aadhaar Number */ ?>
												<div class="form-group">
													<label class="form_label">Aadhaar Number <sup class="text-danger"></sup></label>
                          <input type="text" value="<?php echo $form_data[0]['aadhar_no']; ?>" class="form-control" readonly disabled />
												</div>					
											</div>
											
											<div class="col-xl-6 col-lg-6 mb-4"><?php // Upload Proof of Identity ?>
												<div class="form-group">
                          <?php if($form_data[0]['idproofphoto'] == "")
                          { ?>
                            <div class="img_preview_input_outer pull-left">
                              <label for="id_proof_file" class="form_label">Upload Proof of Identity <sup class="text-danger">*</sup></label>
                              <input type="file" name="id_proof_file" id="id_proof_file" class="form-controlx hide_input_file_cropper" <?php if($form_data[0]['idproofphoto'] == "") { echo 'required'; } ?> />
                              
                              <div class="image-input image-input-outline image-input-circle image-input-empty">
                                <div class="profile-progress"></div>                              
                                <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('id_proof_file', 'dra_members', 'Edit Proof of Identity')">Upload Proof of Identity</button>
                              </div>
                              <note class="form_note" id="id_proof_file_err">Note: Please select only .jpg, .jpeg, .png file upto 20MB.</note>
                              
                              <input type="hidden" name="id_proof_file_cropper" id="id_proof_file_cropper" value="<?php echo set_value('id_proof_file_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
                              
                              <?php if(form_error('id_proof_file')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('id_proof_file'); ?></label> <?php } ?>
                              <?php if($id_proof_file_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $id_proof_file_error; ?></label> <?php } ?>
                            </div>
                          <?php }
                          else
                          { ?>
                            <div class="img_preview_input_outer">
                              <label class="form_label">Uploaded Proof of Identity <sup class="text-danger"></sup></label>
                            </div>
                          <?php } ?>
													
													<div id="id_proof_file_preview" class="upload_img_preview <?php if($form_data[0]['idproofphoto'] == "") { echo 'pull-right'; } ?>">
														<?php 
                            $preview_id_proof_file = '';			
                            if($form_data[0]['idproofphoto'] != "") 
                            { 
                              $preview_id_proof_file = $form_data[0]['idproofphoto'];
                              $preview_id_proof_file = base_url($id_proof_file_path.'/'.$preview_id_proof_file);
                            }
                            
                            if($preview_id_proof_file != "")
                            { ?>
                              <a href="<?php echo $preview_id_proof_file."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Proof of Identity - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                <img src="<?php echo $preview_id_proof_file."?".time(); ?>">
                              </a>
                            <?php }
                            else
                            {
                              echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                            } ?>
                          </div><div class="clearfix"></div>
												</div>
											</div>

                      <input type="hidden" id="data_lightbox_hidden" value="candidate_images">
                      <input type="hidden" id="data_lightbox_title_hidden" value="<?php echo $preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
											
											<div class="col-xl-6 col-lg-6"><?php // Upload Passport-size Photo ?>
												<div class="form-group">
                          <?php if($form_data[0]['scannedphoto'] == "")
                          { ?>
                            <div class="img_preview_input_outer pull-left">
                              <label for="candidate_photo" class="form_label">Upload Passport-size Photo <sup class="text-danger">*</sup></label>
                              <input type="file" name="candidate_photo" id="candidate_photo" class="form-controlx hide_input_file_cropper" <?php if($form_data[0]['scannedphoto'] == "") { echo 'required'; } ?> />
                              
                              <div class="image-input image-input-outline image-input-circle image-input-empty">
                                <div class="profile-progress"></div>                              
                                <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('candidate_photo', 'dra_members', 'Edit Photo')">Upload Photo</button>
                              </div>
                              <note class="form_note" id="candidate_photo_err">Note: Please select only .jpg, .jpeg, .png file upto 20MB.</note>
                              
                              <input type="hidden" name="candidate_photo_cropper" id="candidate_photo_cropper" value="<?php echo set_value('candidate_photo_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
                              
                              <?php if(form_error('candidate_photo')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('candidate_photo'); ?></label> <?php } ?>
                              <?php if($candidate_photo_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $candidate_photo_error; ?></label> <?php } ?>
                            </div>
                          <?php }
                          else
                          { ?>
                            <div class="img_preview_input_outer">
                              <label class="form_label">Uploaded Passport-size Photo <sup class="text-danger"></sup></label>
                            </div>
                          <?php } ?>
													
													<div id="candidate_photo_preview" class="upload_img_preview <?php if($form_data[0]['scannedphoto'] == "") { echo 'pull-right'; } ?>">
														<?php 
															$preview_candidate_photo = '';
															if($form_data[0]['scannedphoto'] != "") 
															{ 
																$preview_candidate_photo = $form_data[0]['scannedphoto'];
                                $preview_candidate_photo = base_url($candidate_photo_path.'/'.$preview_candidate_photo);
															}
															
															if($preview_candidate_photo != "")
															{ ?>
                                <a href="<?php echo $preview_candidate_photo."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Passport-size Photo of the Candidate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                  <img src="<?php echo $preview_candidate_photo."?".time(); ?>">
                                </a>
															<?php }
                              else
															{
																echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
															} ?>
													</div><div class="clearfix"></div>
												</div>
											</div>
											
											<div class="col-xl-6 col-lg-6">
												<div class="form-group">
                          <?php if($form_data[0]['scannedsignaturephoto'] == "")
                          { ?>
                            <div class="img_preview_input_outer pull-left">
                              <label for="candidate_sign" class="form_label">Upload Signature of the Candidate <sup class="text-danger">*</sup></label>
                              <input type="file" name="candidate_sign" id="candidate_sign" class="form-controlx hide_input_file_cropper" <?php if($form_data[0]['scannedsignaturephoto'] == "") { echo 'required'; } ?> />
                              
                              <div class="image-input image-input-outline image-input-circle image-input-empty">
                                <div class="profile-progress"></div>                              
                                <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('candidate_sign', 'dra_members', 'Edit Signature')">Upload Signature</button>
                              </div>
                              <note class="form_note" id="candidate_sign_err">Note: Please select only .jpg, .jpeg, .png file upto 20MB.</note>
                              
                              <input type="hidden" name="candidate_sign_cropper" id="candidate_sign_cropper" value="<?php echo set_value('candidate_sign_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
                              
                              <?php if(form_error('candidate_sign')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('candidate_sign'); ?></label> <?php } ?>
                              <?php if($candidate_sign_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $candidate_sign_error; ?></label> <?php } ?>
                            </div>
                            <?php }
                          else
                          { ?>
                            <div class="img_preview_input_outer">
                              <label class="form_label">Uploaded Signature of the Candidate <sup class="text-danger"></sup></label>
                            </div>
                          <?php } ?>
													
													<div id="candidate_sign_preview" class="upload_img_preview <?php if($form_data[0]['scannedsignaturephoto'] == "") { echo 'pull-right'; } ?>">
														<?php 
															$preview_candidate_sign = '';
															if($form_data[0]['scannedsignaturephoto'] != "") 
															{ 
																$preview_candidate_sign = $form_data[0]['scannedsignaturephoto'];
                                $preview_candidate_sign = base_url($candidate_sign_path.'/'.$preview_candidate_sign); 
															}
															
															if($preview_candidate_sign != "")
															{ ?>
                                <a href="<?php echo $preview_candidate_sign."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Signature of the Candidate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                  <img src="<?php echo $preview_candidate_sign."?".time(); ?>">
                              </a>
															<?php }
															else
															{
																echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
															} ?>
													</div><div class="clearfix"></div>
												</div>
											</div>
                    </div>
										
										<div class="hr-line-dashed"></div>										
										<div class="row">
											<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="submit_btn_outer2">
												<input type="submit" class="btn btn-primary" id="submitAll" name="submitAll" value="<?php echo "Update Profile"; ?>" onclick="update_file_validations()"> 
											</div>
										</div>
									</form>
								</div>
              </div>
            
              <div id="common_log_outer"></div>
            </div>					
          </div>
        </div>
        <?php $this->load->view('iibfdra/candidate/inc_footerbar_candidate'); ?>		
      </div>
    </div>
  
    <?php $this->load->view('iibfbcbf/inc_footer'); ?>		
    <?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>
    <?php $this->load->view('iibfbcbf/common/inc_cropper_script', array('page_name'=>'dra_candidate_update_profile')); ?>
  	
    <?php $this->load->view('iibfdra/candidate/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_candidate_id, 'module_slug'=>'candidate_action', 'log_title'=>'Candidate Log')); ?>    
  
    <script type="text/javascript">
			/********** START : Function to get the city dropdown values as per state selection ***********/
			function get_city_ajax(state_id)
			{
				$("#page_loader").show();
				parameters="state_id="+state_id;
				
				$.ajax(
				{
					type: "POST",
					url: "<?php echo site_url('iibfdra/candidate/dashboard_candidate/get_city_ajax'); ?>",
					data: parameters,
					cache: false,
					dataType: 'JSON',
					async:false,
					success:function(data)
					{
						if(data.flag == "success")
						{
							$("#city_outer").html(data.response);
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
			}/********** END : Function to get the city dropdown values as per state selection ***********/
      
      function update_file_validations()
      {
        var id_proof_file_required_flag = true;
        var form_id_proof_file = '<?php echo $form_data[0]['idproofphoto'] ?>';
        if($("#id_proof_file_cropper").val() != "" || form_id_proof_file != "") { id_proof_file_required_flag = false; }
				$("#id_proof_file").rules("add", 
				{ 
					required: id_proof_file_required_flag,
					check_valid_file:true,
				});

        var candidate_photo_required_flag = true;         
        var form_candidate_photo = '<?php echo $form_data[0]['scannedphoto']; ?>';
        if($("#candidate_photo_cropper").val() != "" || form_candidate_photo != "") { candidate_photo_required_flag = false; }
        $("#candidate_photo").rules("add", 
				{ 
					required: candidate_photo_required_flag,
					check_valid_file:true,
				});

        var candidate_sign_required_flag = true;
        var form_candidate_sign = '<?php echo $form_data[0]['scannedsignaturephoto'] ?>';
        if($("#candidate_sign_cropper").val() != "" || form_candidate_sign != "") { candidate_sign_required_flag = false; }
        $("#candidate_sign").rules("add", 
				{ 
					required: candidate_sign_required_flag,
					check_valid_file:true,
				});
      }

      //START : JQUERY VALIDATION SCRIPT 
			function validate_input(input_id) { $("#"+input_id).valid(); }
			$(document ).ready( function() 
			{
				$("#add_candidate_form").submit(function() 
				{
					if($("#address1").valid() == false) { }
					else if($("#state").valid() == false) { $('#state').trigger('chosen:activate'); }
					else if($("#city").valid() == false) { $('#city').trigger('chosen:activate'); }					
				});

				var form = $("#add_candidate_form").validate( 
				{
					onkeyup: function(element) { $(element).valid(); },          
					rules:
					{
						mobile_no:{ required: true, allow_only_numbers:true, first_zero_not_allowed:true, maxlength:10, minlength:10, remote: { url: "<?php echo site_url('iibfdra/candidate/dashboard_candidate/validation_check_mobile_exist/0/1'); ?>", type: "post", data: { "enc_candidate_id": function() { return "<?php echo $enc_candidate_id; ?>"; } } } },            
						alt_mobile_no:{ allow_only_numbers:true, first_zero_not_allowed:true, maxlength:10, minlength:10 },
						email_id:{ required: true, maxlength:80, valid_email:true, remote: { url: "<?php echo site_url('iibfdra/candidate/dashboard_candidate/validation_check_email_exist/0/1'); ?>", type: "post", data: { "enc_candidate_id": function() { return "<?php echo $enc_candidate_id; ?>"; } } } },
						alt_email_id:{ maxlength:80, valid_email:true },
						address1:{ required: true, maxlength:75 },
						address2:{ maxlength:75 },
						address3:{ maxlength:75 },
						address4:{ maxlength:75 },
						state:{ required: true },  
						city:{ required: true }, 
						district:{ required: true, allow_only_alphabets_and_numbers_and_space:true, maxlength:30 }, 
						pincode:{ required: true, allow_only_numbers:true, minlength:6, maxlength: 6, remote: { url: "<?php echo site_url('iibfdra/candidate/dashboard_candidate/validation_check_valid_pincode/0/1'); ?>", type: "post", data: { "selected_state_code": function() { return $("#state").val(); } } } },  //check validation for pincode as per selected state
						
            id_proof_file:{ <?php if($form_data[0]['idproofphoto'] == "") { ?>required: true,<?php } ?> check_valid_file:true }, //use size in bytes //filesize_max: 1MB : 1000000 
						candidate_photo:{ <?php if($form_data[0]['scannedphoto'] == "") { ?>required: true,<?php } ?> check_valid_file:true }, //use size in bytes //filesize_max: 1MB : 1000000 
						candidate_sign:{ <?php if($form_data[0]['scannedsignaturephoto'] == "") { ?>required: true,<?php } ?> check_valid_file:true }, //use size in bytes //filesize_max: 1MB : 1000000						
					},
					messages:
					{
						mobile_no: { required: "Please enter the mobile number", minlength: "Please enter 10 numbers in mobile number", maxlength: "Please enter 10 numbers in mobile number", remote: "The mobile number is already exist" },
						alt_mobile_no: { minlength: "Please enter 10 numbers in alternate mobile number", maxlength: "Please enter 10 numbers in alternate mobile number" },
						email_id: { required: "Please enter the email id", valid_email: "Please enter the valid email id", remote: "The email id is already exist"  },
						alt_email_id: { valid_email: "Please enter the valid alternate email id" },
						address1: { required: "Please enter the address line-1" },
						address2: { },
						address3: { },
						address4: { },
						state: { required: "Please select the state" },
						city: { required: "Please select the city" },
						district: { required: "Please enter the district" },
						pincode: { required: "Please enter the pincode", minlength: "Please enter 6 numbers in pincode", maxlength: "Please enter 6 numbers in pincode", remote: "Please enter valid pincode as per selected city" },
						id_proof_file: { required: "Please upload the proof of identity", valid_file_format:"Please upload only .jpg, .jpeg, .png files", filesize_min:"Please upload file greater than 75KB", filesize_max:"Please upload file less than 100KB" },
						candidate_photo: { required: "Please upload the passport-size photo of the candidate", valid_file_format:"Please upload only .jpg, .jpeg, .png files", filesize_min:"Please upload file greater than 14KB", filesize_max:"Please upload file less than 20KB" },
						candidate_sign: { required: "Please upload the signature of the candidate", valid_file_format:"Please upload only .jpg, .jpeg, .png files", filesize_min:"Please upload file greater than 14KB", filesize_max:"Please upload file less than 20KB" },						
					}, 
					errorPlacement: function(error, element) // For replace error 
					{
						if (element.attr("name") == "email_id") { error.insertAfter("#email_id_err"); }
						else if (element.attr("name") == "alt_email_id") { error.insertAfter("#alt_email_id_err"); }
						else if (element.attr("name") == "address1") { error.insertAfter("#address1_err"); }
						else if (element.attr("name") == "address2") { error.insertAfter("#address2_err"); }
						else if (element.attr("name") == "address3") { error.insertAfter("#address3_err"); }
						else if (element.attr("name") == "address4") { error.insertAfter("#address4_err"); }
						else if (element.attr("name") == "state") { error.insertAfter("#state_err"); }
						else if (element.attr("name") == "city") { error.insertAfter("#city_err"); }
						else if (element.attr("name") == "district") { error.insertAfter("#district_err"); }
						else if (element.attr("name") == "id_proof_file") { error.insertAfter("#id_proof_file_err"); }
						else if (element.attr("name") == "candidate_photo") { error.insertAfter("#candidate_photo_err"); }
						else if (element.attr("name") == "candidate_sign") { error.insertAfter("#candidate_sign_err"); }
						else { error.insertAfter(element); }
					},          
					submitHandler: function(form) 
					{		
            let field_change_flag = 0;

            let org_mobile_no = "<?php echo $form_data[0]['mobile_no'] ?>";				
            if(org_mobile_no != $("#mobile_no").val().trim()) { field_change_flag = 1;}

            let org_alt_mobile_no = "<?php echo $form_data[0]['alt_mobile_no'] ?>";				
            if(org_alt_mobile_no != $("#alt_mobile_no").val().trim()) { field_change_flag = 1;}

            let org_email_id = "<?php echo $form_data[0]['email_id'] ?>";				
            if(org_email_id != $("#email_id").val().trim()) { field_change_flag = 1;}

            let org_alt_email_id = "<?php echo $form_data[0]['alt_email_id'] ?>";				
            if(org_alt_email_id != $("#alt_email_id").val().trim()) { field_change_flag = 1;}

            let org_address1 = "<?php echo $form_data[0]['address1'] ?>";				
            if(org_address1 != $("#address1").val().trim()) { field_change_flag = 1;}

            let org_address2 = "<?php echo $form_data[0]['address2'] ?>";				
            if(org_address2 != $("#address2").val().trim()) { field_change_flag = 1;}

            let org_address3 = "<?php echo $form_data[0]['address3'] ?>";				
            if(org_address3 != $("#address3").val().trim()) { field_change_flag = 1;}

            let org_address4 = "<?php echo $form_data[0]['address4'] ?>";				
            if(org_address4 != $("#address4").val().trim()) { field_change_flag = 1;}

            let org_state = "<?php echo $form_data[0]['state'] ?>";				
            if(org_state != $("#state").val().trim()) { field_change_flag = 1;}

            let org_city = "<?php echo $form_data[0]['city'] ?>";				
            if(org_city != $("#city").val().trim()) { field_change_flag = 1;}

            let org_district = "<?php echo $form_data[0]['district'] ?>";				
            if(org_district != $("#district").val().trim()) { field_change_flag = 1;}

            let org_pincode = "<?php echo $form_data[0]['pincode'] ?>";				
            if(org_pincode != $("#pincode").val().trim()) { field_change_flag = 1;}
            
            let disp_id_proof_file_upload_option = "<?php echo $form_data[0]['idproofphoto']; ?>";
            if(disp_id_proof_file_upload_option == "")
            {
              let org_id_proof_file_cropper = "<?php echo $form_data[0]['idproofphoto'] ?>";				
              if(org_id_proof_file_cropper != $("#id_proof_file_cropper").val().trim()) { field_change_flag = 1;}
            }

            let disp_candidate_photo_upload_option = "<?php echo $form_data[0]['scannedphoto']; ?>";
            if(disp_candidate_photo_upload_option == "")
            {
              let org_candidate_photo_cropper = "<?php echo $form_data[0]['scannedphoto'] ?>";				
              if(org_candidate_photo_cropper != $("#candidate_photo_cropper").val().trim()) { field_change_flag = 1;}
            }

            let disp_candidate_sign_upload_option = "<?php echo $form_data[0]['scannedsignaturephoto']; ?>";
            if(disp_candidate_sign_upload_option == "")
            {
              let org_candidate_sign_cropper = "<?php echo $form_data[0]['scannedsignaturephoto'] ?>";				
              if(org_candidate_sign_cropper != $("#candidate_sign_cropper").val().trim()) { field_change_flag = 1;}
            }
            
            if(field_change_flag === 1)
            {
              $("#page_loader").hide();
              swal({ title: "Please confirm", text: "Please confirm to submit the details", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
              { 
                $("#page_loader").show();
                form.submit();
              });   
            }
            else
            {
              sweet_alert_only_alert("Please update at least one field");
            }						
					}
				});
			});
			//END : JQUERY VALIDATION SCRIPT
		</script>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>