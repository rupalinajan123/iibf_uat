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
      <?php $this->load->view('iibfbcbf/agency/inc_sidebar_agency'); ?>		
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('iibfbcbf/agency/inc_topbar_agency'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2><?php echo $mode; ?> Faculty </h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/faculty_master_agency'); ?>">Faculty Master</a></li>
							<li class="breadcrumb-item active"> <strong><?php echo $mode; ?> Faculty</strong></li>
            </ol>
          </div>
					<div class="col-lg-2"> </div>
        </div>			
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-title">
									<div class="ibox-tools">
										<a href="<?php echo site_url('iibfbcbf/agency/faculty_master_agency'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a>
                  </div>
                </div>
								<div class="ibox-content">
                <?php echo validation_errors(); ?>
                  <form method="post" action="<?php echo site_url('iibfbcbf/agency/faculty_master_agency/add_faculty_agency/'.$enc_faculty_id); ?>" id="add_faculty_form" enctype="multipart/form-data" autocomplete="off">
										<input type="hidden" name="mode" id="mode" value="<?php echo $mode; ?>">
										
                    <?php /* echo validation_errors(); */ ?>

										<div class="row">
                      <?php if($mode == 'Update') { ?>
                        <div class="col-xl-6 col-lg-6"><?php /* Faculty Number */ ?>
                          <div class="form-group">
                            <label for="faculty_number" class="form_label">Faculty Number <sup class="text-danger">*</sup></label>
                            <input type="text" value="<?php echo $form_data[0]['faculty_number']; ?>" class="form-control custom_input" disabled readonly/>
                          </div>					
                        </div>

                        <div class="col-xl-6 col-lg-6"><?php /* Status */ ?>
                          <div class="form-group">
                            <label class="form_label">Status <sup class="text-danger">*</sup></label>
                            <div><span class="disp_status_details badge <?php echo show_faculty_status($form_data[0]['status']); ?>" style="min-width:90px;"><?php echo $form_data[0]['DispStatus']; ?></span></div>
                          </div>					
                        </div>
                      <?php } ?>

                      <div class="col-xl-6 col-lg-6"><?php /* Faculty Name (Salutation) */ ?>
												<div class="form-group">
                          <?php 
                            $salutation_master_arr = array('Mr.', 'Mrs.', 'Ms.');
                            if($mode == "Add") { $chk_salutation = set_value('salutation'); } else { $chk_salutation = $form_data[0]['salutation']; } 
                          ?>
													<label for="salutation" class="form_label">Faculty Name (Salutation) <sup class="text-danger">*</sup></label>
													<select name="salutation" id="salutation" class="form-control" required>
                            <?php if(count($salutation_master_arr) > 0)
                            { ?>
                              <option value="">Select Salutation *</option>
                              <?php foreach($salutation_master_arr as $sal_val)
                              { ?>
                                <option value="<?php echo $sal_val; ?>" <?php if($chk_salutation == $sal_val) { echo 'selected'; } ?>><?php echo $sal_val; ?></option>
                              <?php }
                            } ?>
                          </select>
													<?php if(form_error('salutation')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('salutation'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      
                      <div class="col-xl-6 col-lg-6"><?php /* Faculty Full Name */ ?>
												<div class="form-group">
													<label for="faculty_name" class="form_label">Faculty Full Name <sup class="text-danger">*</sup></label>
													<input type="text" name="faculty_name" id="faculty_name" value="<?php if($mode == "Add") { echo set_value('faculty_name'); } else { echo $form_data[0]['faculty_name']; } ?>" placeholder="Faculty Full Name *" class="form-control custom_input allow_only_alphabets_and_space" maxlength="90" required/>
                          <note class="form_note" id="faculty_name_err">Note: Please enter only 90 characters</note>

													<?php if(form_error('faculty_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('faculty_name'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php // Upload faculty photo ?>
                        <div class="form-group">
                          <div class="img_preview_input_outer pull-left">
                            <label for="faculty_photo" class="form_label">Upload Faculty Photo <sup class="text-danger">*</sup></label>
                            <input type="file" name="faculty_photo" id="faculty_photo" class="form-control" accept=".png,.jpeg,.jpg" data-accept=".jpg,.jpeg,.pdf" onchange="show_preview(event, 'faculty_photo_preview'); validate_input('faculty_photo');" <?php if($mode == 'Add' || ($mode == 'Update' && $form_data[0]['faculty_photo'] == "")) { echo 'required'; } ?> />

                            <note class="form_note" id="faculty_photo_err">Note: Please Upload only .jpg, .jpeg, .png Files upto 20KB</note>
                            
                            <?php if(form_error('faculty_photo')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('faculty_photo'); ?></label> <?php } ?>
                            <?php if($faculty_photo_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $faculty_photo_error; ?></label> <?php } ?>
                          </div>

                          <div id="faculty_photo_preview" class="upload_img_preview pull-right">
                            <?php if($mode == 'Update' && $form_data[0]['faculty_photo'] != "")
                            { ?>
                              <a href="<?php echo base_url($faculty_photo_path.'/'.$form_data[0]['faculty_photo'])."?".time(); ?>" class="example-image-link" data-lightbox="faculty_photo_<?php echo $form_data[0]['faculty_id']; ?>" data-title="<?php echo $form_data[0]['faculty_name']." (".$form_data[0]['faculty_number'].")"; ?>">
                                <img src="<?php echo base_url($faculty_photo_path.'/'.$form_data[0]['faculty_photo'])."?".time(); ?>">
                              </a>
                            <?php }
                            else
                            {
                              echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                            } ?>
                          </div>
                        </div>
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* Date of Birth */ ?>
                        <div class="form-group">
                          <label for="dob" class="form_label">Date of Birth <sup class="text-danger">*</sup></label>
                          <input type="text" name="dob" id="dob" value="<?php if($mode == "Add") { echo set_value('dob'); } else { if($form_data[0]['dob'] != '0000-00-00') { echo $form_data[0]['dob']; } } ?>" placeholder="Date of Birth" class="form-control custom_input" onchange="validate_input('dob');" onclick="validate_input('dob');" readonly />

                          <note class="form_note" id="dob_err">Note: Please Enter DOB like yyyy-mm-dd and must be less than <?php echo $dob_end_date; ?></note>

                          <?php if(form_error('dob')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('dob'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      
                      <div class="col-xl-6 col-lg-6"><?php /* PAN No */ ?>
												<div class="form-group">
													<label for="pan_no" class="form_label">PAN No. <sup class="text-danger">*</sup></label>
													<input type="text" name="pan_no" id="pan_no" value="<?php if($mode == "Add") { echo set_value('pan_no'); } else { echo $form_data[0]['pan_no']; } ?>" placeholder="PAN No. *" class="form-control custom_input allow_only_alphabets_and_numbers" required maxlength="10" />

                          <note class="form_note" id="pan_no_err">Note: Please enter PAN no like ABCTY1234D</note>

													<?php if(form_error('pan_no')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('pan_no'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php // Upload PAN photo ?>
                        <div class="form-group">
                          <div class="img_preview_input_outer pull-left">
                            <label for="pan_photo" class="form_label">Upload PAN photo <sup class="text-danger">*</sup></label>
                            <input type="file" name="pan_photo" id="pan_photo" class="form-control" accept=".png,.jpeg,.jpg" data-accept=".jpg,.jpeg,.pdf" onchange="show_preview(event, 'pan_photo_preview'); validate_input('pan_photo');" <?php if($mode == 'Add' || ($mode == 'Update' && $form_data[0]['pan_photo'] == "")) { echo 'required'; } ?> />

                            <note class="form_note" id="pan_photo_err">Note: Please Upload only .jpg, .jpeg, .png Files upto 20KB</note>
                            
                            <?php if(form_error('pan_photo')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('pan_photo'); ?></label> <?php } ?>
                            <?php if($pan_photo_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $pan_photo_error; ?></label> <?php } ?>
                          </div>
                          
                          <div id="pan_photo_preview" class="upload_img_preview pull-right">
                            <?php if($mode == 'Update' && $form_data[0]['pan_photo'] != "")
                            { ?>
                              <a href="<?php echo base_url($pan_photo_path.'/'.$form_data[0]['pan_photo'])."?".time(); ?>" class="example-image-link" data-lightbox="pan_photo_<?php echo $form_data[0]['faculty_id']; ?>" data-title="<?php echo $form_data[0]['faculty_name']." (".$form_data[0]['faculty_number'].")"; ?>">
                                <img src="<?php echo base_url($pan_photo_path.'/'.$form_data[0]['pan_photo']).'?'.time(); ?>">
                              </a>
                            <?php  }
                            else
                            {
                              echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                            } ?>
                          </div>
                        </div>					
                      </div>
                      
                      <div class="col-xl-6 col-lg-6"><?php /* Base Location */ ?>
												<div class="form-group">
													<label for="base_location" class="form_label">Base Location <sup class="text-danger"></sup></label>
													<input type="text" name="base_location" id="base_location" value="<?php if($mode == "Add") { echo set_value('base_location'); } else { echo $form_data[0]['base_location']; } ?>" placeholder="City / District / State" class="form-control custom_input allow_only_alphabets_and_space" maxlength="50" />

                          <note class="form_note" id="base_location_err">Note: Please enter only 50 characters</note>

													<?php if(form_error('base_location')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('base_location'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* Academic Qualification(s) with year of passing */ ?>
												<div class="form-group">
													<label for="academic_qualification" class="form_label">Academic Qualification(s) with year of passing <sup class="text-danger">*</sup></label>
													<input type="text" name="academic_qualification" id="academic_qualification" value="<?php if($mode == "Add") { echo set_value('academic_qualification'); } else { echo $form_data[0]['academic_qualification']; } ?>" placeholder="e.g. BE/Mcom (2023)" class="form-control custom_input"  maxlength="50" required />

                          <note class="form_note" id="academic_qualification_err">Note: Please enter only 50 characters</note>

													<?php if(form_error('academic_qualification')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('academic_qualification'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-12 col-lg-12"><?php /* Professional Qualification(s) if any, (including from IIBF) with year of passing */ ?>
												<div class="form-group">
													<label for="professional_qualification" class="form_label">Professional Qualification(s) if any, (including from IIBF) with year of passing <sup class="text-danger"></sup></label>
													<input type="text" name="professional_qualification" id="professional_qualification" value="<?php if($mode == "Add") { echo set_value('professional_qualification'); } else { echo $form_data[0]['professional_qualification']; } ?>" placeholder="e.g. MBA (1994)" class="form-control custom_input"  maxlength="50" />

                          <note class="form_note" id="professional_qualification_err">Note: Please enter only 50 characters</note>

													<?php if(form_error('professional_qualification')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('professional_qualification'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <?php $chk_language_known = array();
                      $language_known_arr = array('ASSAMESE', 'BENGALI', 'ENGLISH', 'GUJARATI', 'HINDI', 'KANNADA', 'MALAYALAM', 'MARATHI', 'ORIYA', 'TAMIL', 'TELUGU');
                      if($mode == "Add") { $chk_language_known = set_value('language_known'); } else { $chk_language_known = explode(", ",$form_data[0]['language_known']); } ?>
                      <div class="col-xl-12 col-lg-12"><?php /* Language Known */ ?>
                        <div class="form-group">
                          <label for="language_known" class="form_label">Language Known <sup class="text-danger">*</sup></label>
                          <select name="language_known[]" id="language_known" class="form-control chosen-select" required onchange="validate_input('language_known'); " multiple data-placeholder="<?php if(count($language_known_arr) > 0)
                              { echo "Select Language *"; } else { echo "No Language Available"; } ?>">
                            <?php if(count($language_known_arr) > 0)
                            { 
                              foreach($language_known_arr as $language_res)
                              { ?>
                                <option value="<?php echo $language_res; ?>" <?php if(is_array($chk_language_known) && in_array($language_res,$chk_language_known)){ echo 'selected'; } ?>><?php echo $language_res; ?></option>
                              <?php }
                            } ?>
                          </select>
                          
                          <span id="language_known_err"></span>
                          <?php if(form_error('language_known[]')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('language_known[]'); ?></label> <?php } ?>
                        </div>					
                      </div>
                    </div>

                    <?php 
                      $start_val = 0;
                      if($mode == 'Add') {  if(set_value('row_cnt') && set_value('row_cnt') != '') { $start_val = set_value('row_cnt'); } }
                      else { $start_val = count($form_field_id_arr); }                        
                    ?>

                    <input type="hidden" class="form-control" name="row_cnt" id="row_cnt" value="<?php echo $start_val; ?>" />
                    <?php 
                      $field_id_arr = $bank_fi_name_arr = $last_position_id_arr = $gross_duration_year_arr = $gross_duration_month_arr = array();
                      
                      if($mode == 'Add') 
                      { 
                        if(set_value('field_id_arr') != "") { $field_id_arr = set_value('field_id_arr'); }                        

                        $bank_fi_name_arr = set_value('bank_fi_name_arr'); 
                        $last_position_id_arr = set_value('last_position_id_arr');                            
                        $gross_duration_year_arr = set_value('gross_duration_year_arr');                            
                        $gross_duration_month_arr = set_value('gross_duration_month_arr');                            
                      } 
                      else 
                      { 
                        $field_id_arr = $form_field_id_arr; 
                        $bank_fi_name_arr = $form_bank_fi_name_arr;
                        $last_position_id_arr = $form_last_position_id_arr;
                        $gross_duration_year_arr = $form_gross_duration_year_arr;
                        $gross_duration_month_arr = $form_gross_duration_month_arr;
                      } 
                    ?>
                    
                    <div class="row">
                      <div class="col-xl-12 col-lg-12">
                        <label class="form_label">Work Experience <sup class="text-danger">*</sup></label><?php /* Work Experience */ ?>
                        <table class="table table-bordered custom_inner_tbl">
                          <thead>
                            <tr>
                              <th class="text-center hide"></th>
                              <th class="text-center">Bank/ FI Name</th>
                              <th class="text-center">Last Position held, Employee Id</th>
                              <th class="text-center">Gross Duration Year</th>
                              <th class="text-center">Gross Duration Month</th>
                              <th class="text-center">Action</th>
                            </tr>
                          </thead> 
                          
                          <tbody id="append_div">
                            <?php  $i=1;                            
                            if(count($field_id_arr) > 0) 
                            { 
                              foreach($field_id_arr as $key => $res)
                              { ?>
                                <tr id="appended_row<?php echo $i; ?>" data-id="<?php echo $i; ?>">
                                  <td class="hide">
                                    <input type="hidden" class="form-control custom_input" name="field_id_arr[]" value="<?php echo $res; ?>">
                                  </td>

                                  <td>
                                    <input type="text" class="form-control custom_input" name="bank_fi_name_arr[]" id="bank_fi_name_arr<?php echo $i; ?>" value="<?php echo $bank_fi_name_arr[$i-1]; ?>" placeholder="e.g. ABC Bank/ABC Agency" required maxlength="100" />
                                    
                                    <?php if(form_error('bank_fi_name_arr[]')!="" && $bank_fi_name_arr[$i-1] == ''){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('bank_fi_name_arr[]'); ?></label> <?php } ?>
                                  </td>

                                  <td>
                                    <input type="text" class="form-control custom_input allow_only_alphabets_and_numbers_and_space" name="last_position_id_arr[]" id="last_position_id_arr<?php echo $i; ?>" value="<?php echo $last_position_id_arr[$i-1]; ?>" placeholder="e.g. Manager - ABC123" required maxlength="50" />
                                    
                                    <?php if(form_error('last_position_id_arr[]')!="" && $last_position_id_arr[$i-1] == ''){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('last_position_id_arr[]'); ?></label> <?php } ?>
                                  </td>
                                  
                                  <td>
                                    <input type="text" class="form-control custom_input allow_only_numbers" name="gross_duration_year_arr[]" id="gross_duration_year_arr<?php echo $i; ?>" value="<?php echo $gross_duration_year_arr[$i-1]; ?>" placeholder="e.g. 10" required maxlength="2" />

                                    <?php if(form_error('gross_duration_year_arr[]')!="" && $gross_duration_year_arr[$i-1] == ''){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('gross_duration_year_arr[]'); ?></label> <?php } ?>
                                  </td>

                                  <td>
                                    <input type="text" class="form-control custom_input allow_only_numbers" name="gross_duration_month_arr[]" id="gross_duration_month_arr<?php echo $i; ?>" value="<?php echo $gross_duration_month_arr[$i-1]; ?>" placeholder="e.g. 1" required maxlength="2" max="12" />

                                    <?php if(form_error('gross_duration_month_arr[]')!="" && $gross_duration_month_arr[$i-1] == ''){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('gross_duration_month_arr[]'); ?></label> <?php } ?>
                                  </td>

                                  <td class="btn_outer no_wrap"></td>
                                </tr>                    
                        <?php $i++;
                              }                                  
                            } ?> 
                          </tbody>
                        </table>

                        <note class="form_note" style="margin:-12px 0 12px 0">Note: Click on + to mention up to 2 previous work experience (Mention in order of latest to previous experience only)</note>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-xl-6 col-lg-6"><?php /* Work Experience in Training, if any */ ?>
												<div class="form-group">
													<label for="work_exp_iibf" class="form_label">Work Experience in Training, if any <sup class="text-danger"></sup></label>
													<input type="text" name="work_exp_iibf" id="work_exp_iibf" value="<?php if($mode == "Add") { echo set_value('work_exp_iibf'); } else { echo $form_data[0]['work_exp_iibf']; } ?>" placeholder="Work Experience in Training, if any" class="form-control custom_input allow_only_alphabets_and_floats_and_space"  maxlength="100" />

                          <note class="form_note" id="work_exp_iibf_err">Note: Please enter only 100 characters</note>

													<?php if(form_error('work_exp_iibf')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('work_exp_iibf'); ?></label> <?php } ?>
                        </div>					
                      </div>

                       <div class="col-xl-6 col-lg-6"><?php /* Experience as Faculty in BC/BF training, if any */  ?>
												<div class="form-group">
													<label for="training_faculty_exp" class="form_label">Experience as Faculty in BC/BF training, if any <sup class="text-danger"></sup></label>
                          <input type="text" name="training_faculty_exp" id="training_faculty_exp" value="<?php if($mode == "Add") { echo set_value('training_faculty_exp'); } else { echo $form_data[0]['training_faculty_exp']; } ?>" placeholder="Experience as Faculty in BC/BF training, if any" class="form-control custom_input allow_only_alphabets_and_floats_and_space" maxlength="100" onkeyup="show_period_of_association()" onchange="show_period_of_association()" />

                          <note class="form_note" id="training_faculty_exp_err">Note: Please enter only 100 characters</note>

													<?php if(form_error('training_faculty_exp')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('training_faculty_exp'); ?></label> <?php } ?>
                        </div>					
                      </div> 

                      <?php /*<div class="col-xl-12 col-lg-12" id="period_of_association_outer"><?php /* Period of Association with the agency in providing BCBF training  ?>
												<div class="form-group">
													<label class="form_label">Period of Association with the agency in providing BCBF training <sup class="text-danger">*</sup></label>
                            <div class="row">
                              <div class="col-xl-6 col-lg-6"><?php /* Year  ?>                                
                                <label for="training_faculty_exp_year" class="form_label">Year <sup class="text-danger">*</sup></label>
                                <input type="text" name="training_faculty_exp_year" id="training_faculty_exp_year" value="<?php if($mode == "Add") { echo set_value('training_faculty_exp_year'); } else { echo $form_data[0]['training_faculty_exp_year']; } ?>" placeholder="Year" class="form-control custom_input allow_only_numbers" maxlength="4" required />
                                <?php if(form_error('training_faculty_exp_year')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('training_faculty_exp_year'); ?></label> <?php } ?>
                              </div>

                              <div class="col-xl-6 col-lg-6"><?php /* Month  ?>                                
                                <label for="training_faculty_exp_month" class="form_label">Month <sup class="text-danger">*</sup></label>
                                <input type="text" name="training_faculty_exp_month" id="training_faculty_exp_month" value="<?php if($mode == "Add") { echo set_value('training_faculty_exp_month'); } else { echo $form_data[0]['training_faculty_exp_month']; } ?>" placeholder="Month" class="form-control custom_input allow_only_numbers" maxlength="2" max="12" required />
                                <?php if(form_error('training_faculty_exp_month')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('training_faculty_exp_month'); ?></label> <?php } ?>                                			
                              </div>
                            </div>
                        </div>					
                      </div> */ ?>
                      
                      
                      <div class="col-xl-12 col-lg-12"><?php /* Interested to take sessions on */ ?>
                        <div class="form-group">
                          <label for="session_interested_id" class="form_label">Interested to take sessions on <sup class="text-danger">*</sup></label>
                          <div id="session_interested_id_err">
                            <?php if(count($faculty_intrested_session_data) > 0)
                            { 
                              foreach($faculty_intrested_session_data as $faculty_intrested_session_res)
                              { ?>
                                <label class="css_checkbox_radio radio_only"> <?php echo $faculty_intrested_session_res['intrested_session_name'] ?>
                                  <input type="radio" value="<?php echo $faculty_intrested_session_res['session_interested_id'] ?>" name="session_interested_id" required <?php if($mode == "Add") { if(set_value('session_interested_id') == $faculty_intrested_session_res['session_interested_id']) { echo "checked"; } } else { if($form_data[0]['session_interested_id'] == $faculty_intrested_session_res['session_interested_id']) { echo "checked"; } } ?>>
                                  <span class="radiobtn"></span>
                                </label>
                              <?php }
                            } ?>
                          </div>
                          <?php if(form_error('session_interested_id')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('session_interested_id'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* Qualification / Experience in Soft Skill in BFSI Sector, if any */ ?>
												<div class="form-group">
													<label for="softskills_banking_exp" class="form_label">Qualification / Experience in Soft Skill in BFSI Sector, if any <sup class="text-danger"></sup></label>
                          <input type="text" name="softskills_banking_exp" id="softskills_banking_exp" value="<?php if($mode == "Add") { echo set_value('softskills_banking_exp'); } else { echo $form_data[0]['softskills_banking_exp']; } ?>" placeholder="Qualification / Experience in Soft Skill in BFSI Sector, if any" class="form-control custom_input" maxlength="100" />

                          <note class="form_note" id="softskills_banking_exp_err">Note: Please enter only 100 characters</note>

													<?php if(form_error('softskills_banking_exp')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('softskills_banking_exp'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <?php /* <div class="col-xl-6 col-lg-6"><?php /* Experience/Comments on training specific activities, if any  ?>
												<div class="form-group">
													<label for="training_activities_exp" class="form_label">Experience/Comments on training specific activities, if any <sup class="text-danger"></sup></label>
                          <input type="text" name="training_activities_exp" id="training_activities_exp" value="<?php if($mode == "Add") { echo set_value('training_activities_exp'); } else { echo $form_data[0]['training_activities_exp']; } ?>" placeholder="Experience/Comments on training specific activities, if any" class="form-control custom_input" maxlength="100" />

                          <note class="form_note" id="training_activities_exp_err">Note: Please enter only 100 characters</note>
                          
													<?php if(form_error('training_activities_exp')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('training_activities_exp'); ?></label> <?php } ?>
                        </div>					
                      </div> */ ?>
                    </div>                
                    
										<div class="hr-line-dashed"></div>										
										<div class="row">
											<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="submit_btn_outer">
												<button class="btn btn-primary" type="submit">Submit</button>
												<a class="btn btn-danger" href="<?php echo site_url('iibfbcbf/agency/faculty_master_agency/'); ?>">Back</a>	
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              
              <div id="common_log_outer"></div>
            </div>					
          </div>
        </div>
				<?php $this->load->view('iibfbcbf/agency/inc_footerbar_agency'); ?>		
      </div>
    </div>
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>		
		<?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>
    
    <?php if($mode == 'Update') { 
      $this->load->view('iibfbcbf/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_faculty_id, 'module_slug'=>'faculty_action', 'log_title'=>'Faculty Log'));
    } ?>

    <script type="text/javascript">
      var dob = $('#dob').datepicker({ keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy-mm-dd", clearBtn: true, endDate:"<?php echo $dob_end_date; ?>" });
      
      let max_row_limit = 3;
      function append_new_row(first_row)
      {
        var current_row_cnt = $("#row_cnt").val();        
        var new_row_cnt = parseInt(current_row_cnt) + 1; 
        
        var content = '';
        content += '<tr id="appended_row'+new_row_cnt+'" data-id="'+new_row_cnt+'">';
        content += '  <td class="hide">';
        content += '    <input type="hidden" class="form-control custom_input" name="field_id_arr[]" value="0">';        
        content += '  </td>';

        content += '  <td>';
        content += '    <input type="text" class="form-control custom_input" name="bank_fi_name_arr[]" id="bank_fi_name_arr'+new_row_cnt+'" value="" placeholder="e.g. ABC Bank/ABC Agency" required maxlength="100" />';
        content += '  </td>';

        content += '  <td>';
        content += '    <input type="text" class="form-control custom_input allow_only_alphabets_and_numbers_and_space" name="last_position_id_arr[]" id="last_position_id_arr'+new_row_cnt+'" value="" placeholder="e.g. Manager - ABC123" required maxlength="50" />';
        content += '  </td>';

        content += '  <td>';
        content += '    <input type="text" class="form-control custom_input allow_only_numbers" name="gross_duration_year_arr[]" id="gross_duration_year_arr'+new_row_cnt+'" value="" placeholder="e.g. 10" required maxlength="2" />';
        content += '  </td>';

        content += '  <td>';
        content += '    <input type="text" class="form-control custom_input allow_only_numbers" name="gross_duration_month_arr[]" id="gross_duration_month_arr'+new_row_cnt+'" value="" placeholder="e.g. 1" required maxlength="2" max="12" />';
        content += '  </td>';

        content += '  <td class="btn_outer no_wrap"></td>';
        content += '</tr>';        
        
        $("#append_div").append(content);

        $('.custom_input').on('input', function () { inc_custom_input($(this)) });// Check for and remove the first space
        $('.custom_input').on('blur', function () { inc_custom_input_blur($(this)) }); // Trim leading and trailing spaces
        $('.allow_only_numbers').on('keydown', function (e) { restrict_input(e,'allow_only_numbers'); }); //Allow only numbers

        $("#row_cnt").val(new_row_cnt);        
        show_hide_btns();
      }

      let current_total_row_cnt = 0;
      let current_mode = '<?php echo $mode; ?>';
      $("table > tbody#append_div > tr").each(function () 
      {
        current_total_row_cnt++;  
      });
      
      if(current_total_row_cnt == 0 && current_mode == 'Add') { append_new_row(1); }
      else if(current_total_row_cnt == 0 && current_mode == 'Update') { append_new_row(1); }
      
      if(current_total_row_cnt > 0 && (current_mode == 'Add' || current_mode == 'Update')) { show_hide_btns(); }      

      function remove_row(row_id)
      {
        swal({ 
          title: "Please confirm", 
          text: "Please confirm to delete selected row", 
          type: "warning", 
          showCancelButton: true, 
          confirmButtonColor: "#DD6B55", 
          confirmButtonText: "Yes, delete it!", 
          closeOnConfirm: true 
        }, 
        function () 
        { 
          $("#appended_row"+row_id).remove();
          show_hide_btns();
        });
      }

      function show_hide_btns()
      {
        let total_row_cnt = 0;
        let last_row = 0;
        $("table > tbody#append_div > tr").each(function () 
        {
          last_row = $(this).closest('tr').attr("data-id");
          total_row_cnt++;          
        });

        if(total_row_cnt > 1) //remove all add button and show add button only for last row
        {
          $("table > tbody#append_div > tr").each(function () 
          {
            let row_data_id = $(this).closest('tr').attr("data-id");
            $("#appended_row"+row_data_id+" .add_row_btn").remove();      
          });

          append_delete_btn(); //append delete button to each row if row count is more than 1

          if(max_row_limit != "")//remove all add buttons if row reach to max limit
          {
            if(max_row_limit != total_row_cnt) { append_add_btn_last(last_row); }
          }    
          else
          {
            append_add_btn_last(last_row);
          }      
        }
        else if(total_row_cnt == 1)
        {
          $("table > tbody#append_div > tr").each(function () 
          {
            let row_data_id = $(this).closest('tr').attr("data-id");
            $("#appended_row"+row_data_id+" .btn_outer .del_row_btn").remove(); 
            $("#appended_row"+row_data_id+" .btn_outer .add_row_btn").remove();               
          });

          append_add_btn_last(last_row);
        }        
      }

      function append_add_btn_last(last_row) //Append add button to last row
      {
        $("#appended_row"+last_row+" .btn_outer").append('<button class="btn btn-primary add_row_btn" type="button" title="Add Row" onclick="append_new_row()"><i class="fa fa-plus" aria-hidden="true"></i></button>');
      }

      function append_delete_btn() //Append delete button to all row
      {
        $("table > tbody#append_div > tr").each(function () 
        {
          let row_data_id = $(this).closest('tr').attr("data-id");
          $("#appended_row"+row_data_id+" .btn_outer .del_row_btn").remove();
          $("#appended_row"+row_data_id+" .btn_outer").append('<button class="btn btn-danger del_row_btn" type="button" title="Remove Row" onclick="remove_row('+row_data_id+', 0)"><i class="fa fa-trash" aria-hidden="true"></i></button> ');      
        });        
      }

      <?php /* function show_period_of_association()
      {
        let training_faculty_exp = $.trim($("#training_faculty_exp").val());
        if(training_faculty_exp != "")
        {
          $("#period_of_association_outer").show();
        }
        else
        {
          $("#period_of_association_outer").hide();
        }
      }
      show_period_of_association(); */ ?>
      
      
      function validate_input(input_id) { $("#"+input_id).valid(); }
      $(document ).ready( function() 
			{
        $.validator.addMethod("validation_check_pan_exist", function(value, element)
        {
          if($.trim(value).length == 0) { return true; }
          else
          {
            var isSuccess = false;
            var pan_no = $.trim(value);
            $.ajax(
            {
              type: "POST",
              url: "<?php echo site_url('iibfbcbf/agency/faculty_master_agency/validation_check_pan_exist/'.$enc_faculty_id.'/1'); ?>",
              data: "pan_no="+pan_no,
              async: false,
              cache : false,
              dataType: 'JSON',
              success: function(data)
              {
                if($.trim(data.flag) == 'success')
                {
                  isSuccess = true;
                }
                
                $.validator.messages.validation_check_pan_exist = data.response;
              }
            });
            
            return isSuccess;
          }
        });		

        $.validator.addMethod("validation_check_year", function(value, element)
        {
          if($.trim(value).length == 0) { return true; }
          else
          {
            // Regular expression to match the pattern and extract the number            
            var pattern = /\d+/g;
            var match = $.trim(value).match(pattern);
            
            var validate_flag = 1;
            if (match) 
            {
              let current_year = new Date().getFullYear();              
              let chkArr = match;
              
              if(chkArr.length > 0)
              {
                $.each(chkArr, function(arr_index, arr_value) 
                {
                  if(arr_value.length >= 4)
                  {
                    if(parseInt(arr_value) > current_year)
                    {
                      validate_flag = 0;
                    }
                  }
                });
              }

              if(validate_flag == '0')
              {
                $.validator.messages.validation_check_year = "Please enter the year less than or equal to "+current_year;
                return false;
              }
              else { return true; }
            }
            else 
            {
              return true;
            }
          }
        });		

				$("#add_faculty_form").validate( 
				{
          //onfocusout: true,
          onkeyup: function(element) 
          {
            $(element).valid();
          },          
          rules:
					{
            salutation:{ required: true },  
            faculty_name:{ required: true, allow_only_alphabets_and_space:true, maxlength:90 },  
            faculty_photo:{ <?php if($mode == 'Add' || ($mode == 'Update' && $form_data[0]['faculty_photo'] == "")) { ?>required: true,<?php } ?> check_valid_file:true, valid_file_format:'.jpg,.jpeg,.png', filesize_max:'20000' }, //use size in bytes //filesize_max: 1MB : 1000000  
            dob:{ required: true, dateFormat:'Y-m-d' },  
            pan_no:{ required: true, valid_pan_no:true, allow_only_alphabets_and_numbers:true, validation_check_pan_exist:true,maxlength:10 },  
            pan_photo:{ <?php if($mode == 'Add' || ($mode == 'Update' && $form_data[0]['pan_photo'] == "")) { ?>required: true,<?php } ?> check_valid_file:true, valid_file_format:'.jpg,.jpeg,.png', filesize_max:'20000' }, //use size in bytes //filesize_max: 1MB : 1000000  
            base_location:{ allow_only_alphabets_and_space:true, maxlength:50 },  
            academic_qualification:{ required: true, maxlength:50, validation_check_year:true },  
            professional_qualification:{ maxlength:50, validation_check_year:true },  
            'language_known[]':{ required: true }, 
            "bank_fi_name_arr[]":{ required: true, maxlength:100 },  
            "last_position_id_arr[]":{ required: true, allow_only_alphabets_and_numbers_and_space:true, maxlength:50 },  
            "gross_duration_year_arr[]":{ required: true, maxlength:2, allow_only_numbers:true },  
            "gross_duration_month_arr[]":{ required: true, maxlength:2, max: 12, allow_only_numbers:true },  
            work_exp_iibf:{ allow_only_alphabets_and_floats_and_space:true, maxlength:100 },  
            training_faculty_exp:{ allow_only_alphabets_and_floats_and_space:true, maxlength:100 },  
            <?php /* training_faculty_exp_year:{ required:true, allow_only_numbers:true, maxlength:4, validation_check_year:true  },  
            training_faculty_exp_month:{ required:true, allow_only_numbers:true, maxlength:2, max: 12 },   */ ?>
            session_interested_id:{ required:true },  
            softskills_banking_exp:{ maxlength:100 },  
            <?php /* training_activities_exp:{ maxlength:100 } */ ?>
					},
					messages:
					{
            salutation: { required: "Please select the Faculty Name (Salutation)" },
            faculty_name: { required: "Please enter the Faculty Full Name" },
            faculty_photo: { required: "Please select the faculty photo", valid_file_format:"Please upload only .jpg, .jpeg, .png files", filesize_max:"Please upload file less than 20KB" },
            dob: { required: "Please select the Date of Birth", dateFormat:"Please enter the DOB like yyyy-mm-dd" },
            pan_no: { required: "Please enter the PAN no.", valid_pan_no:"Please enter the valid PAN no. like ABCTY1234D" },
            pan_photo: { required: "Please select the PAN photo", valid_file_format:"Please upload only .jpg, .jpeg, .png files", filesize_max:"Please upload file less than 20KB" },
            base_location: { },
            academic_qualification: { required: "Please enter the Academic Qualification(s) with year of passing" },
            professional_qualification: { },
            'language_known[]': { required: "Please select the language" },
            "bank_fi_name_arr[]": { required: "Please enter the Bank/ FI Name" },
            "last_position_id_arr[]": { required: "Please enter the Last Position held, Employee Id" },
            "gross_duration_year_arr[]": { required: "Please enter the Gross Duration Year" },
            "gross_duration_month_arr[]": { required: "Please enter the Gross Duration Month" },
            work_exp_iibf: { },
            training_faculty_exp: { }, 
            <?php /* training_faculty_exp_year: { required: "Please enter the Year" },
            training_faculty_exp_month: { required: "Please enter the Month" },*/ ?>
            session_interested_id: { required: "Please select the Interested to take sessions on" },
            softskills_banking_exp: { },
            <?php /* training_activities_exp: { }, */ ?>
					}, 
          errorPlacement: function(error, element) // For replace error 
          {
            if (element.attr("name") == "faculty_name") { error.insertAfter("#faculty_name_err"); }
            else if (element.attr("name") == "faculty_photo") { error.insertAfter("#faculty_photo_err"); }
            else if (element.attr("name") == "dob") { error.insertAfter("#dob_err"); }
            else if (element.attr("name") == "pan_no") { error.insertAfter("#pan_no_err"); }
            else if (element.attr("name") == "pan_photo") { error.insertAfter("#pan_photo_err"); }
            else if (element.attr("name") == "base_location") { error.insertAfter("#base_location_err"); }
            else if (element.attr("name") == "academic_qualification") { error.insertAfter("#academic_qualification_err"); }
            else if (element.attr("name") == "professional_qualification") { error.insertAfter("#professional_qualification_err"); }
            else if (element.attr("name") == "language_known[]") { error.insertAfter("#language_known_err"); }
            else if (element.attr("name") == "work_exp_iibf") { error.insertAfter("#work_exp_iibf_err"); }
            else if (element.attr("name") == "training_faculty_exp") { error.insertAfter("#training_faculty_exp_err"); }
            else if (element.attr("name") == "session_interested_id") { error.insertAfter("#session_interested_id_err"); }
            else if (element.attr("name") == "softskills_banking_exp") { error.insertAfter("#softskills_banking_exp_err"); }
            <?php /*else if (element.attr("name") == "training_activities_exp") { error.insertAfter("#training_activities_exp_err"); } */ ?>       
            else { error.insertAfter(element); }
          },          
					submitHandler: function(form) 
					{
            swal({ title: "Please confirm", text: "Please confirm to submit the details", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
            { 
              $("#page_loader").show();
              $("#submit_btn_outer").html('<button class="btn btn-primary" type="button" style="cursor:wait" value="submit">Submit <i class="fa fa-spinner" aria-hidden="true"></i></button> <a class="btn btn-danger" href="<?php echo site_url("iibfbcbf/agency/faculty_master_agency"); ?>">Back</a>');
              form.submit();
            });            
					}
				});
			});
		</script>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>