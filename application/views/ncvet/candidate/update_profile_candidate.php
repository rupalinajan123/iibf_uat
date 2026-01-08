<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('ncvet/inc_header'); ?>  
    <link href="<?php echo auto_version(base_url('assets/ncvet/css/fancybox.css')); ?>" rel="stylesheet">
    <script src="<?php echo auto_version(base_url('assets/ncvet/js/fancybox.umd.js')); ?>"></script>
  </head>
  
  <body class="fixed-sidebar">
    <?php 
      $this->load->view('ncvet/common/inc_loader'); 
      $isUpdateProfile = true;
    ?>
    
    <div id="wrapper">
      <?php $this->load->view('ncvet/candidate/inc_sidebar_candidate'); ?>    
      <div id="page-wrapper" class="gray-bg">       
        <?php $this->load->view('ncvet/candidate/inc_topbar_candidate'); ?>
        
        <div class="row wrapper border-bottom white-bg page-heading">
          <div class="col-lg-10">
            <h2>Profile</h2>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo site_url('ncvet/candidate/dashboard_candidate'); ?>">Dashboard</a></li>
              <li class="breadcrumb-item active"> <strong>Profile</strong></li>
            </ol>
          </div>
          <div class="col-lg-2"> </div>
        </div>      
        
        <div class="wrapper wrapper-content animated fadeInRight">
          <div class="row">
            <div class="col-lg-12">
              <div class="ibox">
                <div class="ibox-content">
                  <?php echo validation_errors(); ?>
                  <form method="post" action="<?php echo site_url('ncvet/candidate/dashboard_candidate/update_profile'); ?>" id="add_candidate_form" enctype="multipart/form-data" autocomplete="off">
                    <h4 class="custom_form_title" style="margin: -15px -20px 15px -20px !important;">Basic Details</h4>
                    
                    <?php 
                    $salutation_master_arr          = array('Mr.', 'Mrs.', 'Ms.');
                    $guardian_salutation_master_arr = array('Mr.','Ms.', 'Mrs.');
                    $qualification_arr              = $this->config->item('ncvet_qualification_arr');
                    $graduation_sem_arr             = $this->config->item('ncvet_graduation_sem_arr');
                    $post_graduation_sem_arr        = $this->config->item('ncvet_post_graduation_sem_arr'); 
                    ?>
                    
                    <div class="row">
                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <label class="form_label">Enrollment Number <sup class="text-danger">*</sup></label>
                          <input type="text" value="<?php echo $form_data[0]['regnumber']; ?>" class="form-control" readonly disabled />
                        </div>          
                      </div>

                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <label class="form_label">Training ID <sup class="text-danger">*</sup></label>
                          <input type="text" value="<?php echo $form_data[0]['training_id']; ?>" class="form-control" readonly disabled />
                        </div>          
                      </div>
                      
                      <div class="col-xl-3 col-lg-3">
                        <div class="form-group">
                          <?php $chk_salutation = $form_data[0]['salutation']; ?>
                          <label for="salutation" class="form_label">Candidate Name (Salutation) <sup class="text-danger">*</sup></label>

                          <select name="salutation" id="salutation" class="form-control basic_form" <?php echo !checkEditableField('Candidate Name', $form_data[0]['candidate_id']) && $form_data[0]['kyc_fullname_flag'] != 'N' ? 'readonly disabled' : ''; ?> onchange="show_hide_gender(); validate_input('gender');">
                            <?php if(count($salutation_master_arr) > 0)
                              { ?>
                              <option value="">Select Salutation *</option>
                              <?php foreach($salutation_master_arr as $sal_val)
                              { ?>
                              <option value="<?php echo $sal_val; ?>" <?php if($chk_salutation == $sal_val) { echo 'selected'; } ?>><?php echo $sal_val; ?></option>
                              <?php }
                              } ?>
                          </select>
                          <?php echo form_error('salutation', '<div class="text-danger">', '</div>'); ?>
                          <note class="form_note" id="salutation_err"></note>
                        </div>          
                      </div>
                      
                      <div class="col-xl-3 col-lg-3">
                        <div class="form-group">
                          <label class="form_label">First Name <sup class="text-danger">*</sup></label>
                          <input type="text" name="first_name" id="first_name" maxlength="20" value="<?php echo set_value('first_name', $form_data[0]['first_name']); ?>" class="form-control" <?php echo !checkEditableField('Candidate Name', $form_data[0]['candidate_id']) && $form_data[0]['kyc_fullname_flag'] != 'N' ? 'readonly disabled' : ''; ?> />
                          <note class="form_note" id="first_name_err"></note>
                          <?php echo form_error('first_name', '<div class="text-danger">', '</div>'); ?>
                        </div>
                      </div>

                      <div class="col-xl-3 col-lg-3">
                        <div class="form-group">
                          <label class="form_label">Middle Name </label>
                          <input type="text" name="middle_name" id="middle_name" maxlength="20" value="<?php echo set_value('middle_name', $form_data[0]['middle_name']); ?>" class="form-control" <?php echo !checkEditableField('Candidate Name', $form_data[0]['candidate_id']) && $form_data[0]['kyc_fullname_flag'] != 'N' ? 'readonly disabled' : ''; ?> />
                          <?php echo form_error('middle_name', '<div class="text-danger">', '</div>'); ?>
                          <note class="form_note" id="middle_name_err"></note>
                        </div>
                      </div>

                      <div class="col-xl-3 col-lg-3">
                        <div class="form-group">
                          <label class="form_label">Last Name </label>
                          <input type="text" name="last_name" id="last_name" maxlength="20" value="<?php echo set_value('last_name', $form_data[0]['last_name']); ?>" class="form-control" <?php echo !checkEditableField('Candidate Name', $form_data[0]['candidate_id']) && $form_data[0]['kyc_fullname_flag'] != 'N' ? 'readonly disabled' : ''; ?> />
                          <?php echo form_error('last_name', '<div class="text-danger">', '</div>'); ?>
                          <note class="form_note" id="last_name_err"></note>
                        </div>
                      </div>
                      
                      <div class="col-xl-4 col-lg-4">
                        <div class="form-group">
                          <label class="form_label">Gender <sup class="text-danger">*</sup></label>
                          <?php $chk_gender = $form_data[0]['gender']; ?>
                          <select name="gender" id="gender" class="form-control basic_form" <?php echo !checkEditableField('Candidate Name', $form_data[0]['candidate_id']) ? 'readonly disabled' : ''; ?> >
                            <option value="">Select Gender *</option>
                            <option value="1" <?php if($chk_gender == 1) { echo 'selected'; } ?>>Male</option>
                            <option value="2" <?php if($chk_gender == 2) { echo 'selected'; } ?>>Female</option>
                            <option value="3" <?php if($chk_gender == 3) { echo 'selected'; } ?>>Other</option>
                          </select>
                          <note class="form_note" id="gender_err"></note>
                          <label id="gender-error" class="error"></label>
                          <!-- <input type="text" value="<?php if($form_data[0]['gender'] == '1') { echo 'Male'; } else if($form_data[0]['gender'] == '2') { echo 'Female'; } ?>" class="form-control" readonly disabled /> -->
                        </div>          
                      </div>

                      <?php 
                        if($form_data[0]['dob'] != '0000-00-00') { 
                           $dob = new DateTime($form_data[0]['dob']); 
                           $today = new DateTime(); 
                           $age = $today->diff($dob)->y; 
                        } 
                      ?>

                      <div class="col-xl-4 col-lg-4">
                        <div class="form-group">
                          <label class="form_label">Date of Birth <sup class="text-danger">*</sup></label>
                          <input type="text" value="<?php if($form_data[0]['dob'] != '0000-00-00') { echo $form_data[0]['dob']; } ?>" name="dob" id="dob" class="form-control basic_form" readonly <?php echo !checkEditableField('Date of Birth', $form_data[0]['candidate_id']) && $form_data[0]['kyc_dob_flag'] != 'N' ? ' disabled' : ''; ?> onchange="validate_input('dob');calculate_age(this);" onclick="validate_input('dob');calculate_age(this);"/>
                          <note class="form_note" id="dob_err"></note>
                          <?php echo form_error('dob', '<div class="text-danger">', '</div>'); ?>
                        </div>          
                      </div>

                      <div class="col-xl-4 col-lg-4">
                        <div class="form-group">
                          <label class="form_label">Age <sup class="text-danger">*</sup></label>
                          <input type="text" name="age" id="age" value="<?php echo $age; ?>" placeholder="Age" class="form-control custom_input basic_form" onchange="validate_input('age');" onclick="validate_input('age');" required readonly disabled/>
                          <note class="form_note" id="age_err"></note>
                        </div>          
                      </div>

                      <?php $chk_guardian_salutation = $form_data[0]['guardian_salutation']; ?>
                      <div class="col-xl-3 col-lg-3">
                          <div class="form-group">
                            <label for="salutation" class="form_label">Guardian Name (Salutation) <sup class="text-danger">*</sup></label>  
                              <select name="guardian_salutation" id="guardian_salutation" class="form-control basic_form" <?php echo !checkEditableField('Guardian Name', $form_data[0]['candidate_id']) ? 'readonly disabled' : ''; ?> >
                                <?php if(count($salutation_master_arr) > 0)
                                  { ?>
                                  <option value="">Select Salutation *</option>
                                  <?php foreach($guardian_salutation_master_arr as $sal_val)
                                  { ?>
                                  <option value="<?php echo $sal_val; ?>" <?php if($chk_guardian_salutation == $sal_val) { echo 'selected'; } ?>><?php echo $sal_val; ?></option>
                                  <?php }
                                  } ?>
                              </select>
                              <note class="form_note" id="guardian_salutation_err"></note>
                              <?php echo form_error('guardian_salutation', '<div class="text-danger">', '</div>'); ?>
                          </div>
                      </div>

                      <div class="col-xl-9 col-lg-9">
                          <div class="form-group">
                              <label class="form_label">Father/Mother/Guardian Name <sup class="text-danger">*</sup></label>
                              <input type="text" name="guardian_name" id="guardian_name" value="<?php echo set_value('guardian_name', $form_data[0]['guardian_name']); ?>" class="form-control" <?php echo !checkEditableField('Guardian Name', $form_data[0]['candidate_id']) ? 'readonly disabled' : ''; ?> />
                              <note class="form_note" id="guardian_name_err"></note>
                              <?php echo form_error('guardian_name', '<div class="text-danger">', '</div>'); ?>
                          </div>
                      </div>
                      
                      <?php
                        $class = 'col-xl-6 col-lg-6'; 
                        $emailClass = 'col-xl-6 col-lg-6';
                        $div = '';
                        $emailPermission  = 'no';
                        $mobilePermission = 'no';

                        if(checkEditableField('Mobile Number', $form_data[0]['candidate_id']) && checkEditableField('Email Id', $form_data[0]['candidate_id']) ) {
                          $class = 'col-xl-4 col-lg-4';
                          $emailClass = 'col-xl-4 col-lg-4';

                          $emailPermission  = 'yes';
                          $mobilePermission = 'yes';
                        }

                        if(!checkEditableField('Mobile Number', $form_data[0]['candidate_id']) && checkEditableField('Email Id', $form_data[0]['candidate_id']) ) {
                          $class = 'col-xl-6 col-lg-6';
                          $emailClass = 'col-xl-4 col-lg-4';
                          $div = '<div class="col-xl-6 col-lg-6 my-email-div"><div class="form-group"></div></div>';

                          $emailPermission  = 'yes';
                        }

                        if(checkEditableField('Mobile Number', $form_data[0]['candidate_id']) && !checkEditableField('Email Id', $form_data[0]['candidate_id']) ) {
                          $class = 'col-xl-4 col-lg-4';
                          
                          $mobilePermission = 'yes';
                        } 
                      ?>
                      <div class="<?php echo $class; ?>">
                        <div class="form-group">
                          <label for="mobile_no" class="form_label">Mobile Number <sup class="text-danger">*</sup> </label>
                          <input type="text" name="mobile_no" id="mobile_no" value="<?php echo $form_data[0]['mobile_no']; ?>" placeholder="Mobile Number *" class="form-control custom_input allow_only_numbers basic_form" maxlength="10" minlength="10 " readonly disabled/>
                          <note class="form_note" id="mobile_no_err"></note>
                          <?php if(form_error('mobile_no')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('mobile_no'); ?></label> <?php } ?>
                        </div>          
                      </div>

                      <?php if(checkEditableField('Mobile Number', $form_data[0]['candidate_id'])) { ?>
                        <div class="col-xl-2 col-lg-2">
                          <div class="form-group">
                            <br>
                            <button type="button" class="btn btn-info send-otp-mobile" id="send_otp_btn_mobile" data-type='send_otp' <?php if(checkEditableField('Mobile Number', $form_data[0]['candidate_id'])) { ?> style="display:none;" <?php } ?>>Get OTP</button>
                            <a class="btn btn-info" id="reset_btn_mobile" href="javascript:void(0)" <?php if(checkEditableField('Mobile Number', $form_data[0]['candidate_id'])) { ?> style="display:inline-block;" <?php } else { ?> style="display:none;" <?php } ?>>Change Mobile No</a>
                          </div>  
                        </div>
                      <?php } ?>

                      <div class="<?php echo $emailClass; ?>">
                        <div class="form-group">
                          <label for="email_id" class="form_label">Email id <sup class="text-danger">*</sup></label>
                          <input type="text" name="email_id" id="email_id" value="<?php echo $form_data[0]['email_id']; ?>" placeholder="Email id *" class="form-control custom_input basic_form" maxlength="80" readonly disabled/>
                          <note class="form_note" id="email_id_err"></note>
                          
                          <?php if(form_error('email_id')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('email_id'); ?></label> <?php } ?>
                        </div>          
                      </div>
                      
                      <?php if(checkEditableField('Email Id', $form_data[0]['candidate_id'])) { ?>
                        <div class="col-xl-2 col-lg-2">
                          <div class="form-group"><br>
                            <button type="button" class="btn btn-info send-otp" id="send_otp_btn" data-type='send_otp' <?php if(checkEditableField('Email Id', $form_data[0]['candidate_id'])) { ?> style="display:none;" <?php } ?>>Get OTP</button>
                            <a class="btn btn-info" id="reset_btn_email" href="javascript:void(0)" <?php if(checkEditableField('Email Id', $form_data[0]['candidate_id'])) { ?> style="display:inline-block;" <?php } else { ?> style="display:none;" <?php } ?>>Change Email</a>
                          </div>
                        </div>  
                      <?php } ?>
                      <br>
                      <div class="col-xl-12 col-lg-12"><?php /* Mobile otp */ ?>
                        <div class="form-group row">

                          <?php if(checkEditableField('Mobile Number', $form_data[0]['candidate_id'])) { ?>

                          <label for="otp_mobile" class="form_label col-xl-12 col-lg-12 verify-otp-section-mobile" style="display:none;">Mobile OTP<sup class="text-danger">*</sup></label>
                          <!-- Mobile OTP Sesction Start -->
                          <div class="col-xl-3 col-lg-3 verify-otp-section-mobile" style="display:none;">
                            <input type="text" name="otp_mobile" id="otp_mobile" value="<?php if($mode == "Add") { echo set_value('otp_mobile'); } else { echo $form_data[0]['otp_mobile']; } ?>" placeholder="OTP *" class="form-control custom_input basic_form" required maxlength="6" />
                            <note class="form_note" id="otp_mobile_err"></note>
                            
                            <?php if(form_error('otp_mobile')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('otp_mobile'); ?></label> <?php } ?>
                          </div>
                          <div class="col-xl-3 col-lg-3 verify-otp-section-mobile" style="display:none;">
                            <button type="button" class="btn btn-info verify-otp-mobile" data-verify-type='mobile'>Verify OTP </button>
                            <button type="button" class="btn btn-info send-otp-mobile" data-type='resend_otp'>Resend OTP</button>
                          </div>
                          <!-- Mobile OTP Sesction End -->
                          <?php } ?>
                          <!-- Email OTP Sesction Start -->
                          <?php if(checkEditableField('Email Id', $form_data[0]['candidate_id'])) 
                          { 
                            echo $div;
                          ?>
                            <div class="col-xl-3 col-lg-3 verify-otp-section" style="display:none;">
                              <input type="text" name="otp" id="otp" value="<?php if($mode == "Add") { echo set_value('otp'); } else { echo $form_data[0]['otp']; } ?>" placeholder="OTP *" class="form-control custom_input basic_form" required maxlength="6" />
                              <note class="form_note" id="otp_email_err"></note>
                              
                              <?php if(form_error('otp')!="") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('otp'); ?></label> <?php } ?>
                            </div>
                            <div class="col-xl-3 col-lg-3 verify-otp-section" style="display:none;">
                              <button type="button" class="btn btn-info verify-otp" data-verify-type='email'>Verify OTP </button>
                              <button type="button" class="btn btn-info send-otp" data-type='resend_otp'>Resend OTP</button>
                            </div>
                          <?php } ?>  
                          <!-- Email OTP Sesction End -->                        
                        </div>          
                      </div>

                    </div>  
                    <h4 class="custom_form_title" style="margin: 20px -20px 15px -20px !important;">Contact Details</h4>
                    <div class="row">
                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <h4 class="custom_form_title" style="margin: 10px -2px 15px -2px !important;background: #c6b61c;">Communication Address</h4>
                        </div>
                      </div>  
                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">    
                          <h4 class="custom_form_title" style="margin: 10px -2px 15px -2px !important;background: #c6b61c;">Permanant Address</h4>
                        </div>
                      </div>  
                    </div>
                    <div class="row">
                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <label for="address1" class="form_label">Address Line-1<sup class="text-danger">*</sup></label>
                          <input type="text" name="address1" id="address1" placeholder="Address Line-1 *" class="form-control custom_input ignore_required" maxlength="75" value="<?php echo $form_data[0]['address1']; ?>" <?php echo !checkEditableField('Communication Address', $form_data[0]['candidate_id']) ? 'readonly disabled' : ''; ?> />
                          <note class="form_note" id="address1_err"></note>
                          <?php if(form_error('address1')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address1'); ?></label> <?php } ?>
                        </div>          
                      </div>

                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <label for="address1_pr" class="form_label">Address Line-1<sup class="text-danger">*</sup></label>
                          <input type="text" name="address1_pr" id="address1_pr" placeholder="Address Line-1 *" class="form-control custom_input ignore_required" maxlength="75" value="<?php echo $form_data[0]['address1_pr']; ?>" <?php echo !checkEditableField('Permanant Address', $form_data[0]['candidate_id']) ? 'readonly disabled' : ''; ?> />
                          <note class="form_note" id="address1_pr_err"></note>
                          
                          <?php if(form_error('address1_pr')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address1_pr'); ?></label> <?php } ?>
                        </div>          
                      </div>
                    
                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <label for="address2" class="form_label">Address Line-2 <!-- <sup class="text-danger">*</sup> --></label>
                          <input type="text" name="address2" id="address2" placeholder="Address Line-2" class="form-control custom_input" maxlength="75" value="<?php echo $form_data[0]['address2']; ?>" <?php echo !checkEditableField('Communication Address', $form_data[0]['candidate_id']) ? 'readonly disabled' : ''; ?> />
                          <note class="form_note" id="address2_err"></note>                          
                          <?php if(form_error('address2')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address2'); ?></label> <?php } ?>
                        </div>          
                      </div>

                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <label for="address2_pr" class="form_label">Address Line-2 <!-- <sup class="text-danger">*</sup> --></label>
                          <input type="text" name="address2_pr" id="address2_pr" placeholder="Address Line-2" class="form-control custom_input" maxlength="75" value="<?php echo $form_data[0]['address2_pr']; ?>" <?php echo !checkEditableField('Permanant Address', $form_data[0]['candidate_id']) ? 'readonly disabled' : ''; ?> />                          
                          <note class="form_note" id="address2_pr_err"></note>                       
                          <?php if(form_error('address2_pr')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address2_pr'); ?></label> <?php } ?>
                        </div>          
                      </div>
                    
                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <label for="address3" class="form_label">Address Line-3 <!-- <sup class="text-danger">*</sup> --></label>
                          <input type="text" name="address3" id="address3" placeholder="Address Line-3" class="form-control custom_input" maxlength="75" value="<?php echo $form_data[0]['address3']; ?>" <?php echo !checkEditableField('Communication Address', $form_data[0]['candidate_id']) ? 'readonly disabled' : ''; ?>/>
                          <note class="form_note" id="address3_err"></note>
                          
                          <?php if(form_error('address3')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address3'); ?></label> <?php } ?>
                        </div>          
                      </div>

                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <label for="address3_pr" class="form_label">Address Line-3 <!-- <sup class="text-danger">*</sup> --></label>
                          <input type="text" name="address3_pr" id="address3_pr" placeholder="Address Line-3" class="form-control custom_input" maxlength="75" value="<?php echo $form_data[0]['address3_pr']; ?>" <?php echo !checkEditableField('Permanant Address', $form_data[0]['candidate_id']) ? 'readonly disabled' : ''; ?> />
                          <note class="form_note" id="address3_pr_err"></note>

                          <?php if(form_error('address3_pr')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address3_pr'); ?></label> <?php } ?>
                        </div>          
                      </div>
                    
                      <?php $chk_state = $form_data[0]['state']; ?>
                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <label for="state" class="form_label"> State <sup class="text-danger">*</sup></label>
                          <select name="state" id="state" class="form-control chosen-select" onchange="get_city_ajax(this.value,'communication'); validate_input('city');" <?php echo !checkEditableField('Communication Address', $form_data[0]['candidate_id']) ? 'readonly disabled' : ''; ?>>
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
                          <note class="form_note" id="state_err"></note>
                          <?php if(form_error('state')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('state'); ?></label> <?php } ?>
                        </div>          
                      </div>

                      <?php $chk_state_pr = $form_data[0]['state_pr']; ?>
                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <label for="state_pr" class="form_label"> State <sup class="text-danger">*</sup></label>
                          <select name="state_pr" id="state_pr" class="form-control chosen-select" onchange="get_city_ajax(this.value,'permenant'); validate_input('city_pr');" <?php echo !checkEditableField('Permanant Address', $form_data[0]['candidate_id']) ? 'readonly disabled' : ''; ?>>
                            <?php if(count($pr_state_master_data) > 0) { ?>
                                <option value="">Select State *</option>
                                <?php foreach($pr_state_master_data as $pr_state_res) { ?>
                                <option value="<?php echo $pr_state_res['state_code']; ?>" <?php if($chk_state_pr == $pr_state_res['state_code']) { echo 'selected'; } ?>><?php echo $pr_state_res['state_name']; ?></option>
                                <?php }
                              }
                              else 
                              { ?>
                              <option value="">No State Available</option>
                            <?php } ?>
                          </select>
                          <note class="form_note" id="state_pr_err"></note>
                          <?php if(form_error('state_pr')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('state_pr'); ?></label> <?php } ?>
                        </div>          
                      </div>
                    
                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <label for="city" class="form_label">City <sup class="text-danger">*</sup></label>
                          <div id="city_outer">
                            <select class="form-control chosen-select" name="city" id="city" onchange="validate_input('city');" <?php echo !checkEditableField('Communication Address', $form_data[0]['candidate_id']) ? 'readonly disabled' : ''; ?>>
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
                                    <option value="<?php echo $city['id']; ?>" <?php if($form_data[0]['city'] == $city['id']) { echo "selected"; } ?>><?php echo $city['city_name']; ?></option>
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
                            <note class="form_note" id="city_err"></note>
                          </div>
                          <!-- <input type="text" class="form-control custom_input" name="city" id="city" value="<?php echo $city_data[0]['city_name']; ?>" readonly> -->
                          
                          <?php if(form_error('city')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('city'); ?></label> <?php } ?>                            
                        </div>          
                      </div>
                      
                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <label for="city_pr" class="form_label">City <sup class="text-danger">*</sup></label>
                          <div id="city_pr_outer">
                            <select class="form-control chosen-select" name="city_pr" id="city_pr" onchange="validate_input('city');" <?php echo !checkEditableField('Permanant Address', $form_data[0]['candidate_id']) ? 'readonly disabled' : ''; ?>>
                              <?php $selected_state_pr_val = '';
                                $selected_state_pr_val = $form_data[0]['state_pr'];
                                
                                if($selected_state_pr_val != "")
                                {
                                  $city_pr_data = $this->master_model->getRecords('city_master', array('state_code' => $selected_state_pr_val, 'city_delete' => '0'), 'id, city_name', array('city_name'=>'ASC'));
                                  
                                  if(count($city_pr_data) > 0)
                                  { ?>
                                  <option value="">Select City</option>
                                  <?php foreach($city_pr_data as $city_pr)
                                    { ?>
                                    <option value="<?php echo $city_pr['id']; ?>" <?php if($form_data[0]['city_pr'] == $city_pr['id']) { echo "selected"; } ?>><?php echo $city_pr['city_name']; ?></option>
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
                            <note class="form_note" id="city_pr_err"></note>
                          </div>
                           <!-- <input type="text" class="form-control custom_input" name="city_pr" id="city_pr" value="<?php echo $city_pr_data[0]['city_name']; ?>" readonly> -->                        
                          <?php if(form_error('city_pr')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('city_pr'); ?></label> <?php } ?>                            
                        </div>          
                      </div>

                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <label for="district" class="form_label">District <sup class="text-danger">*</sup></label>
                          <input type="text" name="district" id="district" value="<?php echo $form_data[0]['district']; ?>" placeholder="District *" class="form-control custom_input allow_only_alphabets_and_numbers_and_space ignore_required" maxlength="30" <?php echo !checkEditableField('Communication Address', $form_data[0]['candidate_id']) ? 'readonly disabled' : ''; ?>/>
                          <note class="form_note" id="district_err"></note>
                          
                          <?php if(form_error('district')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('district'); ?></label> <?php } ?>
                        </div>          
                      </div>

                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <label for="district_pr" class="form_label">District <sup class="text-danger">*</sup></label>
                          <input type="text" name="district_pr" id="district_pr" value="<?php echo $form_data[0]['district_pr']; ?>" placeholder="District *" class="form-control custom_input allow_only_alphabets_and_numbers_and_space ignore_required" maxlength="30" <?php echo !checkEditableField('Permanant Address', $form_data[0]['candidate_id']) ? 'readonly disabled' : ''; ?>/>
                          <note class="form_note" id="district_pr_err"></note>
                          
                          <?php if(form_error('district_pr')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('district_pr'); ?></label> <?php } ?>
                        </div>          
                      </div>
                    
                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <label for="pincode" class="form_label">Pincode <sup class="text-danger">*</sup></label>
                          <input type="text" name="pincode" id="pincode" value="<?php echo $form_data[0]['pincode']; ?>" placeholder="Pincode *" class="form-control custom_input allow_only_numbers ignore_required" maxlength="6" minlength="6" <?php echo !checkEditableField('Communication Address', $form_data[0]['candidate_id']) ? 'readonly disabled' : ''; ?>/>
                          <note class="form_note" id="pincode_err"></note>
                          <?php if(form_error('pincode')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('pincode'); ?></label> <?php } ?>
                        </div>          
                      </div>

                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <label for="pincode_pr" class="form_label">Pincode <sup class="text-danger">*</sup></label>
                          <input type="text" name="pincode_pr" id="pincode_pr" value="<?php echo $form_data[0]['pincode_pr']; ?>" placeholder="Pincode *" class="form-control custom_input allow_only_numbers ignore_required" maxlength="6" minlength="6" <?php echo !checkEditableField('Permanant Address', $form_data[0]['candidate_id']) ? 'readonly disabled' : ''; ?>/>
                          <note class="form_note" id="pincode_pr_err"></note>
                          <?php if(form_error('pincode_pr')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('pincode_pr'); ?></label> <?php } ?>
                        </div>          
                      </div>  
                    </div>
                    
                    <h4 class="custom_form_title" style="margin: 20px -20px 15px -20px !important;">Other Details</h4>
                    <div class="row">
                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <label class="form_label">Eligibility <sup class="text-danger">*</sup></label>
                          <div id="qualification_err">
                            <?php $chk_qualification = $form_data[0]['qualification']; ?>
                            <select name="qualification" id="qualification" class="form-control basic_form qualification_field" onchange="show_hide_dependent_fields(this);" <?php echo !checkEditableField('Eligibility', $form_data[0]['candidate_id']) && $form_data[0]['kyc_eligibility_flag'] != 'N' ? 'readonly disabled' : ''; ?>>
                              <?php if(count($qualification_arr) > 0)
                                { ?>
                                <option value="">Select  *</option>
                                <?php foreach($qualification_arr as $key=>$sal_val)
                                { ?>
                                <option value="<?php echo $key; ?>" <?php if($chk_qualification == $key) { echo 'selected'; } ?>><?php echo $sal_val; ?></option>
                                <?php }
                                              } ?>
                            </select>
                            <note class="form_note" id="qualification_err"></note>
                            <?php if(form_error('qualification')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('qualification'); ?></label> <?php } ?>
                          </div>
                        </div>          
                      </div>

                      
                      <?php 
                      // Check if editing is allowed for the whole 'Eligibility' section
                      $is_editable_eligibility   = checkEditableField('Eligibility', $form_data[0]['candidate_id']);
                      $disabled_attr_eligibility = $is_editable_eligibility || $form_data[0]['kyc_eligibility_flag'] == 'N' ? '' : 'readonly disabled';
                      ?>

                      <div class="col-xl-6 col-lg-6 experience-section" <?php if ($form_data[0]['qualification'] != 1) { ?> style="display: none;" <?php } ?> >
                          <div class="form-group">
                              <label class="form_label">Experience More than 1.5 Year in BFSI <sup class="text-danger">*</sup></label>
                              <div id="experience_err">
                                  <?php 
                                  $experience_value = $form_data[0]['experience'];
                                  ?>
                                  <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="radio" name="experience" id="experience_yes" value="Y" <?php echo ($experience_value == 'Y') ? 'checked' : ''; ?> <?php echo $disabled_attr_eligibility; ?>>
                                      <label class="form-check-label" for="experience_yes">Yes</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="radio" name="experience" id="experience_no" value="N" <?php echo ($experience_value == 'N' || $experience_value == '') ? 'checked' : ''; ?> <?php echo $disabled_attr_eligibility; ?>>
                                      <label class="form-check-label" for="experience_no">No</label>
                                  </div>
                                  <note class="form_note" id="experience_note"></note>
                                  <?php echo form_error('experience', '<div class="text-danger">', '</div>'); ?>
                              </div>
                          </div>       
                      </div>
                    
                      <?php $chk_semester = $form_data[0]['semester']; ?>
                      <div class="col-xl-6 col-lg-6 graduation-semester-section" <?php if ($form_data[0]['qualification'] != 3) { ?> style="display: none;" <?php } ?>>
                        <div class="form-group">
                          <label class="form_label">Semester <sup class="text-danger">*</sup></label>
                          <div id="semester_err">   
                            <select name="graduation_sem" id="graduation_sem" class="form-control basic_form" <?php echo !checkEditableField('Eligibility', $form_data[0]['candidate_id']) && $form_data[0]['kyc_eligibility_flag'] != 'N' ? 'readonly disabled' : ''; ?>>
                              <?php if(count($graduation_sem_arr) > 0) { ?>
                              <option value="">Select  *</option>
                              <?php foreach($graduation_sem_arr as $sal_val) { ?>
                              <option value="<?php echo $sal_val; ?>" <?php if($chk_semester == $sal_val) { echo 'selected'; } ?>><?php echo $sal_val; ?></option>
                                <?php } } ?>
                            </select>
                            <note class="form_note" id="graduation_sem_err"></note>
                            <?php if(form_error('graduation_sem')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('graduation_sem'); ?></label> <?php } ?>
                          </div>
                        </div>          
                      </div>
                      
                      <?php $chk_semester = $form_data[0]['semester']; ?>  
                      <div class="col-xl-6 col-lg-6 post-graduation-semester-section" <?php if ($form_data[0]['qualification'] != 4) { ?> style="display: none;" <?php } ?>>
                        <div class="form-group">
                          <label class="form_label">Semester <sup class="text-danger">*</sup></label>
                          <div id="semester_err">   
                            <select name="post_graduation_sem" id="post_graduation_sem" class="form-control basic_form" <?php echo !checkEditableField('Eligibility', $form_data[0]['candidate_id']) && $form_data[0]['kyc_eligibility_flag'] != 'N' ? 'readonly disabled' : ''; ?> >
                              <?php if(count($post_graduation_sem_arr) > 0) { ?>
                              <option value="">Select  *</option>
                              <?php foreach($post_graduation_sem_arr as $sal_val) { ?>
                              <option value="<?php echo $sal_val; ?>" <?php if($chk_semester == $sal_val) { echo 'selected'; } ?>><?php echo $sal_val; ?></option>
                                <?php } } ?>
                            </select>
                            <note class="form_note" id="post_graduation_sem_err"></note>
                            <?php if(form_error('post_graduation_sem')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('post_graduation_sem'); ?></label> <?php } ?>
                          </div>
                        </div>          
                      </div>  
                      
                      <div class="col-xl-6 col-lg-6 college-section" <?php if ($form_data[0]['qualification'] != 3 && $form_data[0]['qualification'] != 4) { ?> style="display: none;" <?php } ?>>
                        <div class="form-group">
                          <label class="form_label">Name of the College/Academic Institution <sup class="text-danger">*</sup></label>
                          <div id="collage_err">   
                            <input type="text" name="collage" id="collage" value="<?php echo $form_data[0]['collage']; ?>" class="form-control" <?php echo !checkEditableField('Eligibility', $form_data[0]['candidate_id']) && $form_data[0]['kyc_eligibility_flag'] != 'N' ? 'readonly disabled' : ''; ?> />
                            <note class="form_note" id="collage_err"></note>
                            <?php if(form_error('collage')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('collage'); ?></label> <?php } ?>
                          </div>
                        </div>          
                      </div>

                      <div class="col-xl-6 col-lg-6 university-section" <?php if ($form_data[0]['qualification'] != 3 && $form_data[0]['qualification'] != 4) { ?> style="display: none;" <?php } ?>>
                        <div class="form-group">
                          <label class="form_label">Name of the University <sup class="text-danger">*</sup></label>
                          <div id="university_err">   
                            <input type="text" name="university" id="university" value="<?php echo $form_data[0]['university']; ?>" class="form-control" <?php echo !checkEditableField('Eligibility', $form_data[0]['candidate_id']) && $form_data[0]['kyc_eligibility_flag'] != 'N' ? 'readonly disabled' : ''; ?> />
                            <note class="form_note" id="university_err"></note>
                            <?php if(form_error('university')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('university'); ?></label> <?php } ?>
                          </div>
                        </div>          
                      </div>
                      
                      <div class="col-xl-6 col-lg-6 qualification_depend_div qualification_state_div" <?php if ($form_data[0]['qualification'] == '' || $form_data[0]['qualification_state'] == '') { ?> style="display: none;" <?php } ?>>
                        <?php 
                          $chk_qualification_state = $form_data[0]['qualification_state'];
                          $qualification_el = $form_data[0]['qualification']; 
                          
                          $qualification_state_lable = 'State';
                          switch ($qualification_el) {
                            case '1':
                              $qualification_state_lable = 'State of Working';
                              break;
                            case '2':
                              $qualification_state_lable = 'State of Degree College';
                              break;
                            case '3':
                              $qualification_state_lable = 'State of College / Academic Institution';
                              break;
                            case '4':
                              $qualification_state_lable = 'State of College / Academic Institution';
                              break;    
                            default:
                              // code...
                              break;
                          }

                        ?>
                        <div class="form-group ">
                          <div class="qualification_state_div">
                            <label for="qualification_state" class="form_label label_qualification_state"> <?php echo $qualification_state_lable; ?> <sup class="text-danger">*</sup></label>
                            <select name="qualification_state" id="qualification_state" class="form-control chosen-select ignore_required qualification_state" <?php echo !checkEditableField('Eligibility', $form_data[0]['candidate_id']) && $form_data[0]['kyc_eligibility_flag'] != 'N' ? 'readonly disabled' : ''; ?>>
                              <?php if(count($state_master_data) > 0) { ?>
                                <option value="">Select State *</option>
                                <?php foreach($state_master_data as $state_res) { ?>
                                  <option value="<?php echo $state_res['state_code']; ?>" <?php if($chk_qualification_state == $state_res['state_code']) { echo 'selected'; } ?>><?php echo $state_res['state_name']; ?></option>
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

                      <div class="col-xl-6 col-lg-6">
                          <div class="form-group">
                              <label class="form_label">Aadhar Card Number <sup class="text-danger">*</sup> </label>
                              <div id="aadhar_no_container"> 
                                  <input type="text" name="aadhar_no" id="aadhar_no" value="<?php echo $form_data[0]['aadhar_no']; ?>" class="form-control allow_only_numbers" maxlength="12" <?php echo !checkEditableField('Aadhar Card', $form_data[0]['candidate_id']) && $form_data[0]['kyc_aadhar_flag'] != 'N' ? 'readonly disabled' : ''; ?> />
                                  <note class="form_note" id="aadhar_no_err"></note>
                                  <?php echo form_error('aadhar_no', '<div class="text-danger">', '</div>'); ?>
                              </div>       
                          </div>       
                      </div>

                      <div class="col-xl-6 col-lg-6">
                          <div class="form-group">
                              <label class="form_label">APAAR ID/ABC ID <sup class="text-danger">*</sup></label>
                              <div id="id_proof_number_container"> 
                                  <input type="text" name="id_proof_number" id="id_proof_number" value="<?php echo $form_data[0]['id_proof_number']; ?>" class="form-control allow_only_numbers" maxlength="12" <?php echo !checkEditableField('APAAR ID/ABC ID', $form_data[0]['candidate_id']) && $form_data[0]['kyc_apaar_flag'] != 'N' ? 'readonly disabled' : ''; ?> />
                                  <note class="form_note" id="id_proof_number_err"></note>
                                  <?php echo form_error('id_proof_number', '<div class="text-danger">', '</div>'); ?>
                              </div>       
                          </div>       
                      </div>

                      <?php 
                      // Determine if editing is allowed for the whole 'Benchmark Disability' section
                      $is_editable_disability = checkEditableField('Benchmark Disability', $form_data[0]['candidate_id']);
                      $disabled_attr = $is_editable_disability ? '' : 'readonly disabled';
                      ?>

                      <div class="col-xl-6 col-lg-6">
                          <div class="form-group">
                              <label class="form_label">Person with Benchmark Disability <sup class="text-danger">*</sup></label>
                              <div>
                                  <?php 
                                  $benchmark_disability_value = $form_data[0]['benchmark_disability'];
                                  ?>
                                  <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="radio" name="benchmark_disability" id="benchmark_disability_yes" value="Y" <?php echo ($benchmark_disability_value == 'Y') ? 'checked' : ''; ?> <?php echo $disabled_attr; ?>>
                                      <label class="form-check-label" for="benchmark_disability_yes">Yes</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="radio" name="benchmark_disability" id="benchmark_disability_no" value="N" <?php echo ($benchmark_disability_value == 'N' || $benchmark_disability_value == '') ? 'checked' : ''; ?> <?php echo $disabled_attr; ?>>
                                      <label class="form-check-label" for="benchmark_disability_no">No</label>
                                  </div>
                                  <note class="form_note" id="benchmark_disability_err"></note>
                                  <?php echo form_error('benchmark_disability', '<div class="text-danger">', '</div>'); ?>
                              </div>
                          </div>
                      </div>

                      <div class="col-xl-6 col-lg-6 disability-sub-field" <?php if ($form_data[0]['benchmark_disability'] != 'Y' && $form_data[0]['visually_impaired'] != 'Y') { ?> style="display: none;" <?php } ?>>
                          <div class="form-group">
                              <label class="form_label">Visually impaired <sup class="text-danger">*</sup></label>
                              <div>
                                  <?php $visually_impaired_value = $form_data[0]['visually_impaired']; ?>
                                  <div class="form-check form-check-inline">
                                      <input class="form-check-input benchmark-disability" type="radio" name="visually_impaired" id="visually_impaired_yes" value="Y" <?php echo ($visually_impaired_value == 'Y') ? 'checked' : ''; ?> <?php echo $disabled_attr; ?>>
                                      <label class="form-check-label" for="visually_impaired_yes">Yes</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                      <input class="form-check-input benchmark-disability" type="radio" name="visually_impaired" id="visually_impaired_no" value="N" <?php echo ($visually_impaired_value == 'N' || $visually_impaired_value == '') ? 'checked' : ''; ?> <?php echo $disabled_attr; ?>>
                                      <label class="form-check-label" for="visually_impaired_no">No</label>
                                  </div>
                                  <note class="form_note" id="visually_impaired_err"></note>
                                  <?php echo form_error('visually_impaired', '<div class="text-danger">', '</div>'); ?>
                              </div>
                          </div>
                      </div>

                      <div class="col-xl-6 col-lg-6 disability-sub-field" <?php if ($form_data[0]['benchmark_disability'] != 'Y' && $form_data[0]['orthopedically_handicapped'] != 'Y') { ?> style="display: none;" <?php } ?>>
                          <div class="form-group">
                              <label class="form_label">Orthopedically Handicapped <sup class="text-danger">*</sup></label>
                              <div>
                                  <?php $orthopedically_handicapped_value = set_value('orthopedically_handicapped', $form_data[0]['orthopedically_handicapped']); ?>
                                  <div class="form-check form-check-inline">
                                      <input class="form-check-input benchmark-disability" type="radio" name="orthopedically_handicapped" id="orthopedically_handicapped_yes" value="Y" <?php echo ($orthopedically_handicapped_value == 'Y') ? 'checked' : ''; ?> <?php echo $disabled_attr; ?>>
                                      <label class="form-check-label" for="orthopedically_handicapped_yes">Yes</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                      <input class="form-check-input benchmark-disability" type="radio" name="orthopedically_handicapped" id="orthopedically_handicapped_no" value="N" <?php echo ($orthopedically_handicapped_value == 'N' || $orthopedically_handicapped_value == '') ? 'checked' : ''; ?> <?php echo $disabled_attr; ?>>
                                      <label class="form-check-label" for="orthopedically_handicapped_no">No</label>
                                  </div>
                                  <note class="form_note" id="orthopedically_handicapped_err"></note>
                                  <?php echo form_error('orthopedically_handicapped', '<div class="text-danger">', '</div>'); ?>
                              </div>
                          </div>
                      </div>

                      <div class="col-xl-6 col-lg-6 disability-sub-field" <?php if ($form_data[0]['benchmark_disability'] != 'Y' && $form_data[0]['cerebral_palsy'] != 'Y') { ?> style="display: none;" <?php } ?>>
                          <div class="form-group">
                              <label class="form_label">Cerebral Palsy <sup class="text-danger">*</sup></label>
                              <div>
                                  <?php $cerebral_palsy_value = $form_data[0]['cerebral_palsy']; ?>
                                  <div class="form-check form-check-inline">
                                      <input class="form-check-input benchmark-disability" type="radio" name="cerebral_palsy" id="cerebral_palsy_yes" value="Y" <?php echo ($cerebral_palsy_value == 'Y') ? 'checked' : ''; ?> <?php echo $disabled_attr; ?>>
                                      <label class="form-check-label" for="cerebral_palsy_yes">Yes</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                      <input class="form-check-input benchmark-disability" type="radio" name="cerebral_palsy" id="cerebral_palsy_no" value="N" <?php echo ($cerebral_palsy_value == 'N' || $cerebral_palsy_value == '') ? 'checked' : ''; ?> <?php echo $disabled_attr; ?>>
                                      <label class="form-check-label" for="cerebral_palsy_no">No</label>
                                  </div>
                                  <note class="form_note" id="cerebral_palsy_err"></note>
                                  <?php echo form_error('cerebral_palsy', '<div class="text-danger">', '</div>'); ?>
                              </div>
                          </div>
                      </div>
                    </div>


                    <h4 class="custom_form_title" style="margin: 20px -20px 15px -20px !important;">Upload Documents</h4>
                    <div class="row">
                    <?php 
                      $preview_candidate_id = $form_data[0]['candidate_id']; 
                      $preview_first_name   = $form_data[0]['first_name']; 
                      $preview_training_id  = $form_data[0]['training_id'];
                      $qualification_cert_lable = '';
                      if($form_data[0]['qualification'] == '1')
                      {
                        $qualification_cert_lable = '12th Pass Certificate';
                      }

                      if($form_data[0]['qualification'] == '2')
                      {
                        $qualification_cert_lable = 'Degree Certificate/ Provisional degree certificate';
                      }
                      
                      $qualificarion_certificate = 'style="display:none;"';
                      $institute_id = 'style="display:none;"';  
                      $experience_certificate = 'style="display:none;"';
                      
                      if($form_data[0]['qualification'] == '1' || $form_data[0]['qualification'] == '2')
                      {
                        $qualificarion_certificate = 'style="display:block;"';
                      }

                      if($form_data[0]['qualification'] == '1' && $form_data[0]['experience'] == 'Y')
                      {
                        $experience_certificate = 'style="display:block;"';
                      }  

                      if($form_data[0]['qualification'] == '3' || $form_data[0]['qualification'] == '4') 
                      {
                        $institute_id = 'style="display:block;"';
                      }  

                      if($form_data[0]['qualification_certificate_file'] == "" && ($form_data[0]['qualification'] == 1 || $form_data[0]['qualification'] == 2) )
                      {
                        $isUpdateProfile = false;
                      }

                      if($form_data[0]['exp_certificate'] == "" && ($form_data[0]['qualification'] == 1 && $form_data[0]['experience'] == 'Y') )
                      {
                        $isUpdateProfile = false;
                      }  

                      if($form_data[0]['institute_idproof'] == "" && ($form_data[0]['qualification'] == 3 || $form_data[0]['qualification'] == 4) )
                      {
                        $isUpdateProfile = false;
                      }

                      if($form_data[0]['declarationform'] == "" && ($form_data[0]['qualification'] == 3 || $form_data[0]['qualification'] == 4) )
                      {
                        $isUpdateProfile = false;
                      }

                      if($form_data[0]['benchmark_disability'] == "Y" && $form_data[0]['visually_impaired'] == 'Y' && $form_data[0]['vis_imp_cert_img'] == '' ) 
                      {
                        $isUpdateProfile = false;
                      }

                      if($form_data[0]['benchmark_disability'] == "Y" && $form_data[0]['orthopedically_handicapped'] == 'Y' && $form_data[0]['orth_han_cert_img'] == '' ) 
                      {
                        $isUpdateProfile = false;
                      }

                      if($form_data[0]['benchmark_disability'] == "Y" && $form_data[0]['cerebral_palsy'] == 'Y' && $form_data[0]['cer_palsy_cert_img'] == '' ) 
                      {
                        $isUpdateProfile = false;
                      }

                      if($form_data[0]['kyc_fullname_flag'] == "N" || $form_data[0]['kyc_dob_flag'] == "N" || $form_data[0]['kyc_aadhar_flag'] == "N" || $form_data[0]['kyc_apaar_flag'] == "N" || $form_data[0]['kyc_eligibility_flag'] == "N") 
                      {
                        $isUpdateProfile = false;
                      }
                    ?>  

                      
                    <div class="col-xl-6 col-lg-6 qualification-file-section" <?php echo $qualificarion_certificate; ?>>
                      <div class="form-group">
                        <?php if($form_data[0]['qualification_certificate_file'] == "" || checkEditableField('Eligibility', $form_data[0]['candidate_id']) || $form_data[0]['kyc_eligibility_flag'] == 'N')
                        { 
                        ?>
                        <div class="img_preview_input_outer pull-left">
                          <!-- for="qualification_certificate_file"  -->
                          <label class="form_label qualification-lable">Upload <?php echo $qualification_cert_lable; ?> <sup class="text-danger">*</sup></label>
                            <input type="file" name="qualification_certificate_file" id="qualification_certificate_file" class="form-controlx hide_input_file_cropper" <?php if($form_data[0]['qualification_certificate_file'] == "") { echo 'required'; } ?> />
                          
                            <div class="image-input image-input-outline image-input-circle image-input-empty">
                              <div class="profile-progress"></div>                              

                              <button type="button" class="btn btn-sm btn-primary w-100 mb-1 qualification-lable-button" onclick="open_img_upload_modal('qualification_certificate_file', 'ncvet_candidates', 'Edit Qualification Certificate')">Upload <?php echo $qualification_cert_lable; ?></button>
                            </div>
                            <note class="form_note" id="qualification_certificate_file_err">Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB.</note>
                            
                            <input type="hidden" name="qualification_certificate_file_cropper" id="qualification_certificate_file_cropper" value="<?php echo set_value('qualification_certificate_file_cropper'); ?>" />
                          
                          <?php if(form_error('qualification_certificate_file')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('qualification_certificate_file'); ?></label> <?php } ?>
                          <?php if($qualification_certificate_file_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $qualification_certificate_file_error; ?></label> <?php } ?>
                        </div>
                          <?php }
                        else
                        { ?>
                          <div class="img_preview_input_outer">
                            <label class="form_label">Uploaded <?php echo $qualification_cert_lable; ?> <!-- <sup class="text-danger">*</sup> --></label>
                          </div>
                        <?php } ?>
                        
                        <div id="qualification_certificate_file_preview" class="upload_img_preview <?php if($form_data[0]['qualification_certificate_file'] == "" || checkEditableField('Eligibility', $form_data[0]['candidate_id']) || $form_data[0]['kyc_eligibility_flag'] == 'N') { echo 'pull-right'; } ?>">
                          <?php 
                            $preview_qualification_certificate_file = '';
                            if($form_data[0]['qualification_certificate_file'] != "") 
                            { 
                              $preview_qualification_certificate_file = $form_data[0]['qualification_certificate_file'];
                              $preview_qualification_certificate_file = base_url($qualification_certificate_file_path.'/'.$preview_qualification_certificate_file); 
                            }
                            if($preview_qualification_certificate_file != "" && strtolower(pathinfo($form_data[0]['qualification_certificate_file'], PATHINFO_EXTENSION)) !== "pdf" && strtolower(pathinfo($form_data[0]['qualification_certificate_file'], PATHINFO_EXTENSION)) !== "")
                            { 
                              echo '<i class="fa fa-picture-o default-file" aria-hidden="true" style="display:none"></i>';
                              ?>
                              <a class="qualification-file" href="<?php echo $preview_qualification_certificate_file."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo $qualification_cert_lable.' - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                <img src="<?php echo $preview_qualification_certificate_file."?".time(); ?>">
                              </a>
                            <?php 
                            }
                            else if($preview_qualification_certificate_file != "" && strtolower(pathinfo($form_data[0]['qualification_certificate_file'], PATHINFO_EXTENSION)) === "pdf" && strtolower(pathinfo($form_data[0]['qualification_certificate_file'], PATHINFO_EXTENSION)) !== "")
                            { 
                                echo '<i class="fa fa-picture-o default-file" aria-hidden="true" style="display:none"></i>';
                              ?>

                              <a class="qualification-file" data-caption="<?php echo $qualification_cert_lable.' - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>" data-fancybox data-type="iframe" data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo $preview_qualification_certificate_file."?".time(); ?>" href="javascript:;" title="<?php echo $qualification_cert_lable.' - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="60" height="60" alt="PDF"> 
                              </a>
                      <?php }  
                            else
                            {
                              echo '<i class="fa fa-picture-o qualification-file" aria-hidden="true"></i>';
                            } ?>
                        </div><div class="clearfix"></div>
                      </div>
                    </div>
                     
                    <div class="col-xl-6 col-lg-6 experience-file-section" <?php echo $experience_certificate; ?>>
                      <div class="form-group">
                        <?php if($form_data[0]['exp_certificate'] == "" || checkEditableField('Eligibility', $form_data[0]['candidate_id']) || $form_data[0]['kyc_eligibility_flag'] == 'N' )
                        { 
                        ?>
                        <div class="img_preview_input_outer pull-left">
                          <!-- for="exp_certificate" -->
                          <label class="form_label">Upload Experience Certificate <sup class="text-danger">*</sup></label>
                            <input type="file" name="exp_certificate" id="exp_certificate" class="form-controlx hide_input_file_cropper" <?php if($form_data[0]['exp_certificate'] == "") { echo 'required'; } ?> />
                          
                            <div class="image-input image-input-outline image-input-circle image-input-empty">
                              <div class="profile-progress"></div>                              
                              <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('exp_certificate', 'ncvet_candidates', 'Edit Experience Certificate')">Upload Experience Certificate</button>
                            </div>
                            <note class="form_note" id="exp_certificate_err">Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB.</note>
                            
                            <input type="hidden" name="exp_certificate_cropper" id="exp_certificate_cropper" value="<?php echo set_value('exp_certificate_cropper'); ?>" />
                          
                          <?php if(form_error('exp_certificate')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exp_certificate'); ?></label> <?php } ?>
                          <?php if($exp_certificate_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $exp_certificate_error; ?></label> <?php } ?>
                        </div>
                  <?php }
                        else
                        { ?>
                          <div class="img_preview_input_outer">
                            <label class="form_label">Uploaded Experience Certificate <!-- <sup class="text-danger">*</sup> --></label>
                          </div>
                        <?php } ?>
                        
                        <div id="exp_certificate_preview" class="upload_img_preview <?php if($form_data[0]['exp_certificate'] == "" || checkEditableField('Eligibility', $form_data[0]['candidate_id']) || $form_data[0]['kyc_eligibility_flag'] == 'N') { echo 'pull-right'; } ?>">
                          <?php 
                            $preview_exp_certificate = '';
                            if($form_data[0]['exp_certificate'] != "") 
                            { 
                              $preview_exp_certificate = $form_data[0]['exp_certificate'];
                              $preview_exp_certificate = base_url($exp_certificate_path.'/'.$preview_exp_certificate); 
                            }
                            
                            if($preview_exp_certificate != "" && strtolower(pathinfo($form_data[0]['exp_certificate'], PATHINFO_EXTENSION)) === "pdf" && strtolower(pathinfo($form_data[0]['exp_certificate'], PATHINFO_EXTENSION)) !== "")
                            { ?>

                              <a data-caption="<?php echo 'Experience Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>" data-fancybox data-type="iframe" data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo $preview_exp_certificate."?".time(); ?>" href="javascript:;" title="<?php echo 'Experience Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="60" height="60" alt="PDF"> 
                              </a>

                      <?php }
                            if($preview_exp_certificate != "" && strtolower(pathinfo($form_data[0]['exp_certificate'], PATHINFO_EXTENSION)) !== "pdf" && strtolower(pathinfo($form_data[0]['exp_certificate'], PATHINFO_EXTENSION)) !== "")
                            { ?>

                              <a href="<?php echo $preview_exp_certificate."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Experience Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                <img src="<?php echo $preview_exp_certificate."?".time(); ?>">
                              </a>
                      <?php }  
                            else
                            {
                              echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                            } ?>
                        </div><div class="clearfix"></div>
                      </div>
                    </div>
                    
                    <?php 
                      $declaration = 'style="display:none;"';  
                                            
                      if($form_data[0]['qualification'] == '3' || $form_data[0]['qualification'] == '4') 
                      {
                        $declaration = 'style="display:block;"';
                      }  

                    ?>  

   
                    <div class="col-xl-6 col-lg-6 institute-id-section" <?php echo $institute_id; ?>>
                      <div class="form-group">
                        <?php if($form_data[0]['institute_idproof'] == "" || checkEditableField('Eligibility', $form_data[0]['candidate_id']) || $form_data[0]['kyc_eligibility_flag'] == 'N')
                        { 
                        ?>
                        <div class="img_preview_input_outer pull-left">
                          <!-- for="institute_idproof"  -->
                          <label class="form_label">Upload Institutional ID <?php if ($form_data[0]['kyc_institute_idproof_flag'] == 'N') { echo '<sup class="text-danger">*</sup>'; } ?></label>
                            <input type="file" name="institute_idproof" id="institute_idproof" class="form-controlx hide_input_file_cropper" <?php if($form_data[0]['institute_idproof'] == "") { echo 'required'; } ?> />
                          
                            <div class="image-input image-input-outline image-input-circle image-input-empty">
                              <div class="profile-progress"></div>                              
                              <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('institute_idproof', 'ncvet_candidates', 'Edit Institutional ID')">Upload Institutional ID</button>
                            </div>
                            <note class="form_note" id="institute_idproof_err">Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB.</note>
                            
                            <input type="hidden" name="institute_idproof_cropper" id="institute_idproof_cropper" value="<?php echo set_value('institute_idproof_cropper'); ?>" />
                          
                          <?php if(form_error('institute_idproof')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('institute_idproof'); ?></label> <?php } ?>
                          <?php if($institute_idproof_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $institute_idproof_error; ?></label> <?php } ?>
                        </div>
                        <?php 
                        }
                        else
                        { ?>
                          <div class="img_preview_input_outer">
                            <label class="form_label">Uploaded Institutional ID <!-- <sup class="text-danger">*</sup> --></label>
                          </div>
                        <?php 
                        } ?>
                          
                        <div id="institute_idproof_preview" class="upload_img_preview <?php if($form_data[0]['institute_idproof'] == "" || checkEditableField('Eligibility', $form_data[0]['candidate_id']) || $form_data[0]['kyc_eligibility_flag'] == 'N') { echo 'pull-right'; } ?>">
                          <?php 
                            $preview_institute_idproof = '';
                            if($form_data[0]['institute_idproof'] != "") 
                            { 
                              $preview_institute_idproof = $form_data[0]['institute_idproof'];
                              $preview_institute_idproof = base_url($institute_idproof_path.'/'.$preview_institute_idproof); 
                            }
                            
                            if($preview_institute_idproof != "" && strtolower(pathinfo($form_data[0]['institute_idproof'], PATHINFO_EXTENSION)) !== "pdf" && strtolower(pathinfo($form_data[0]['institute_idproof'], PATHINFO_EXTENSION)) !== "")
                            { 
                              echo '<i class="fa fa-picture-o default-institute-id-file" aria-hidden="true" style="display:none;"></i>';
                              ?>
                              <a class="institute-id-file" href="<?php echo $preview_institute_idproof."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Institutional ID - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                <img src="<?php echo $preview_institute_idproof."?".time(); ?>">
                              </a>
                            <?php }
                            else if($preview_institute_idproof != "" && strtolower(pathinfo($form_data[0]['institute_idproof'], PATHINFO_EXTENSION)) === "pdf" && strtolower(pathinfo($form_data[0]['institute_idproof'], PATHINFO_EXTENSION)) !== "")
                            { 
                              echo '<i class="fa fa-picture-o default-institute-id-file" aria-hidden="true" style="display:none;"></i>';
                              ?>

                              <a class="institute-id-file" data-caption="<?php echo 'Institutional ID - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>" data-fancybox data-type="iframe" data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo $preview_institute_idproof."?".time(); ?>" href="javascript:;" title="<?php echo 'Institutional ID - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="60" height="60" alt="PDF"> 
                              </a>
                            <?php }
                            else
                            {
                              echo '<i class="fa fa-picture-o institute-id-file" aria-hidden="true"></i>';
                            } ?>
                        </div><div class="clearfix"></div>
                      </div>
                    </div>  

                    <?php
                      $visually_impaired          = 'style="display:none";';
                      $orthopedically_handicapped = 'style="display:none";';
                      $cerebral_palsy             = 'style="display:none";'; 

                      if($form_data[0]['benchmark_disability'] == 'Y' && $form_data[0]['visually_impaired'] == 'Y') 
                      { 
                        $visually_impaired = 'style="display:block";';
                      }

                      if($form_data[0]['benchmark_disability'] == 'Y' && $form_data[0]['orthopedically_handicapped'] == 'Y') 
                      { 
                        $orthopedically_handicapped = 'style="display:block";';
                      }

                      if($form_data[0]['benchmark_disability'] == 'Y' && $form_data[0]['cerebral_palsy'] == 'Y') 
                      { 
                        $cerebral_palsy = 'style="display:block";';
                      }  
                    ?>
                      
                    <div class="col-xl-6 col-lg-6 visually-impaired-section " <?php echo $visually_impaired; ?>>
                      <div class="form-group">
                        <?php if( ($form_data[0]['vis_imp_cert_img'] == "" && $form_data[0]['visually_impaired'] == "Y" && $form_data[0]['benchmark_disability'] == "Y") || checkEditableField('Benchmark Disability', $form_data[0]['candidate_id']))
                        { 
                        ?>
                        <div class="img_preview_input_outer pull-left">
                          <!-- for="vis_imp_cert_img"  -->
                          <label class="form_label">Upload Visually Impaired Certificate <sup class="text-danger">*</sup></label>
                            <input type="file" name="vis_imp_cert_img" id="vis_imp_cert_img" class="form-controlx hide_input_file_cropper" <?php if($form_data[0]['vis_imp_cert_img'] == "") { echo 'required'; } ?> />
                          
                            <div class="image-input image-input-outline image-input-circle image-input-empty">
                              <div class="profile-progress"></div>                              
                              <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('vis_imp_cert_img', 'ncvet_candidates', 'Edit Visually Impaired Certificate')">Upload Visually Impaired Certificate</button>
                            </div>
                            <note class="form_note" id="vis_imp_cert_img_err">Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB.</note>
                            
                            <input type="hidden" name="vis_imp_cert_img_cropper" id="vis_imp_cert_img_cropper" value="<?php echo set_value('vis_imp_cert_img_cropper'); ?>" />
                          
                          <?php if(form_error('vis_imp_cert_img')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('vis_imp_cert_img'); ?></label> <?php } ?>
                          <?php if($vis_imp_cert_img_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $vis_imp_cert_img_error; ?></label> <?php } ?>
                        </div>
                  <?php }
                        else
                        { ?>
                          <div class="img_preview_input_outer">
                            <label class="form_label">Uploaded Visually Impaired Certificate <!-- <sup class="text-danger">*</sup> --></label>
                          </div>
                  <?php } ?>
                          
                        <div id="vis_imp_cert_img_preview" class="upload_img_preview <?php if($form_data[0]['vis_imp_cert_img'] == "" || checkEditableField('Benchmark Disability', $form_data[0]['candidate_id'])) { echo 'pull-right'; } ?>">
                          <?php 
                            $preview_vis_imp_cert_img = '';
                            if($form_data[0]['vis_imp_cert_img'] != "") 
                            { 
                              $preview_vis_imp_cert_img = $form_data[0]['vis_imp_cert_img'];
                              $preview_vis_imp_cert_img = base_url($disability_cert_img_path.'/'.$preview_vis_imp_cert_img); 
                            }
                            
                            if($preview_vis_imp_cert_img != "" && strtolower(pathinfo($form_data[0]['vis_imp_cert_img'], PATHINFO_EXTENSION)) !== "pdf" && strtolower(pathinfo($form_data[0]['vis_imp_cert_img'], PATHINFO_EXTENSION)) !== "")
                            { ?>
                              <a href="<?php echo $preview_vis_imp_cert_img."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Visually Impaired Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                <img src="<?php echo $preview_vis_imp_cert_img."?".time(); ?>">
                              </a>
                      <?php }
                            else if($preview_vis_imp_cert_img != "" && strtolower(pathinfo($form_data[0]['vis_imp_cert_img'], PATHINFO_EXTENSION)) === "pdf" && strtolower(pathinfo($form_data[0]['vis_imp_cert_img'], PATHINFO_EXTENSION)) !== "")
                            { ?>

                              <a data-caption="<?php echo 'Visually Impaired Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>" data-fancybox data-type="iframe" data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo $preview_vis_imp_cert_img."?".time(); ?>" href="javascript:;" title="<?php echo 'Visually Impaired Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="60" height="60" alt="PDF"> 
                              </a>
                      <?php }
                            else
                            {
                              echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                            } ?>
                        </div><div class="clearfix"></div>
                      </div>
                    </div>
                    
                  
                    <div class="col-xl-6 col-lg-6 orthopedically-handicapped-section" <?php echo $orthopedically_handicapped; ?>>
                      <div class="form-group">
                        <?php if( ($form_data[0]['orth_han_cert_img'] == "" && $form_data[0]['orthopedically_handicapped'] == "Y" && $form_data[0]['benchmark_disability'] == "Y") || checkEditableField('Benchmark Disability', $form_data[0]['candidate_id']))
                        { 
                        ?>
                        <div class="img_preview_input_outer pull-left">
                          <!-- for="vis_imp_cert_img"  -->
                          <label class="form_label">Upload Orthopedically Handicapped Certificate <sup class="text-danger">*</sup></label>
                            <input type="file" name="orth_han_cert_img" id="orth_han_cert_img" class="form-controlx hide_input_file_cropper" <?php if($form_data[0]['orth_han_cert_img'] == "") { echo 'required'; } ?> />
                          
                            <div class="image-input image-input-outline image-input-circle image-input-empty">
                              <div class="profile-progress"></div>                              
                              <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('orth_han_cert_img', 'ncvet_candidates', 'Edit Orthopedically Handicapped Certificate')">Upload Orthopedically Handicapped Certificate</button>
                            </div>
                            <note class="form_note" id="orth_han_cert_img_err">Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB.</note>
                            
                            <input type="hidden" name="orth_han_cert_img_cropper" id="orth_han_cert_img_cropper" value="<?php echo set_value('orth_han_cert_img_cropper'); ?>" />
                          
                          <?php if(form_error('orth_han_cert_img')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('orth_han_cert_img'); ?></label> <?php } ?>
                          <?php if($orth_han_cert_img_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $orth_han_cert_img_error; ?></label> <?php } ?>
                        </div>
                  <?php }
                        else
                        { ?>
                          <div class="img_preview_input_outer">
                            <label class="form_label">Uploaded Orthopedically Handicapped Certificate <!-- <sup class="text-danger">*</sup> --></label>
                          </div>
                  <?php } ?>
                          
                        <div id="orth_han_cert_img_preview" class="upload_img_preview <?php if($form_data[0]['orth_han_cert_img'] == "" || checkEditableField('Benchmark Disability', $form_data[0]['candidate_id'])) { echo 'pull-right'; } ?>">
                          <?php 
                            $preview_orth_han_cert_img = '';
                            if($form_data[0]['orth_han_cert_img'] != "") 
                            { 
                              $preview_orth_han_cert_img = $form_data[0]['orth_han_cert_img'];
                              $preview_orth_han_cert_img = base_url($disability_cert_img_path.'/'.$preview_orth_han_cert_img); 
                            }
                            
                            if($preview_orth_han_cert_img != "" && strtolower(pathinfo($form_data[0]['orth_han_cert_img'], PATHINFO_EXTENSION)) !== "pdf" && strtolower(pathinfo($form_data[0]['orth_han_cert_img'], PATHINFO_EXTENSION)) !== "")
                            { ?>
                              <a href="<?php echo $preview_orth_han_cert_img."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Orthopedically Handicapped Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                <img src="<?php echo $preview_orth_han_cert_img."?".time(); ?>">
                              </a>
                      <?php }
                            else if($preview_orth_han_cert_img != "" && strtolower(pathinfo($form_data[0]['orth_han_cert_img'], PATHINFO_EXTENSION)) === "pdf" && strtolower(pathinfo($form_data[0]['orth_han_cert_img'], PATHINFO_EXTENSION)) !== "")
                            { ?>

                              <a data-caption="<?php echo 'Orthopedically Handicapped Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>" data-fancybox data-type="iframe" data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo $preview_orth_han_cert_img."?".time(); ?>" href="javascript:;" title="<?php echo 'Orthopedically Handicapped Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="60" height="60" alt="PDF"> 
                              </a>
                      <?php }
                            else
                            {
                              echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                            } ?>
                        </div><div class="clearfix"></div>
                      </div>
                    </div>
                    
                      
                    <div class="col-xl-6 col-lg-6 cerebral-palsy-section" <?php echo $cerebral_palsy; ?>>
                      <div class="form-group">
                        <?php if( ($form_data[0]['cer_palsy_cert_img'] == "" && $form_data[0]['cerebral_palsy'] == "Y" && $form_data[0]['benchmark_disability'] == "Y") || checkEditableField('Benchmark Disability', $form_data[0]['candidate_id']))
                        { 
                        ?>
                        <div class="img_preview_input_outer pull-left">
                          <!-- for="vis_imp_cert_img"  -->
                          <label class="form_label">Upload Cerebral Palsy Certificate <sup class="text-danger">*</sup></label>
                            <input type="file" name="cer_palsy_cert_img" id="cer_palsy_cert_img" class="form-controlx hide_input_file_cropper" <?php if($form_data[0]['cer_palsy_cert_img'] == "") { echo 'required'; } ?> />
                          
                            <div class="image-input image-input-outline image-input-circle image-input-empty">
                              <div class="profile-progress"></div>                              
                              <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('cer_palsy_cert_img', 'ncvet_candidates', 'Edit Cerebral Palsy Certificate')">Upload Cerebral Palsy Certificate</button>
                            </div>
                            <note class="form_note" id="cer_palsy_cert_img_err">Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB.</note>
                            
                            <input type="hidden" name="cer_palsy_cert_img_cropper" id="cer_palsy_cert_img_cropper" value="<?php echo set_value('cer_palsy_cert_img_cropper'); ?>" />
                          
                          <?php if(form_error('cer_palsy_cert_img')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('cer_palsy_cert_img'); ?></label> <?php } ?>
                          <?php if($cer_palsy_cert_img_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $cer_palsy_cert_img_error; ?></label> <?php } ?>
                        </div>
                  <?php }
                        else
                        { ?>
                          <div class="img_preview_input_outer">
                            <label class="form_label">Uploaded Cerebral Palsy Certificate <!-- <sup class="text-danger">*</sup> --></label>
                          </div>
                  <?php } ?>
                          
                        <div id="cer_palsy_cert_img_preview" class="upload_img_preview <?php if($form_data[0]['cer_palsy_cert_img'] == "" || checkEditableField('Benchmark Disability', $form_data[0]['candidate_id'])) { echo 'pull-right'; } ?>">
                          <?php 
                            $preview_cer_palsy_cert_img = '';
                            if($form_data[0]['cer_palsy_cert_img'] != "") 
                            { 
                              $preview_cer_palsy_cert_img = $form_data[0]['cer_palsy_cert_img'];
                              $preview_cer_palsy_cert_img = base_url($disability_cert_img_path.'/'.$preview_cer_palsy_cert_img); 
                            }
                            
                            if($preview_cer_palsy_cert_img != "" && strtolower(pathinfo($form_data[0]['cer_palsy_cert_img'], PATHINFO_EXTENSION)) !== "pdf" && strtolower(pathinfo($form_data[0]['cer_palsy_cert_img'], PATHINFO_EXTENSION)) !== "")
                            { ?>
                              <a href="<?php echo $preview_cer_palsy_cert_img."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Cerebral Palsy Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                <img src="<?php echo $preview_cer_palsy_cert_img."?".time(); ?>">
                              </a>
                      <?php }
                            else if($preview_cer_palsy_cert_img != "" && strtolower(pathinfo($form_data[0]['cer_palsy_cert_img'], PATHINFO_EXTENSION)) === "pdf" && strtolower(pathinfo($form_data[0]['cer_palsy_cert_img'], PATHINFO_EXTENSION)) !== "")
                            { ?>

                              <a data-caption="<?php echo 'Cerebral Palsy Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>" data-fancybox data-type="iframe" data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo $preview_cer_palsy_cert_img."?".time(); ?>" href="javascript:;" title="<?php echo 'Cerebral Palsy Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="60" height="60" alt="PDF"> 
                              </a>
                      <?php }
                            else
                            {
                              echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                            } ?>
                        </div><div class="clearfix"></div>
                      </div>
                    </div>
                      
                      <div class="col-xl-6 col-lg-6 mb-4">
                        <div class="form-group">
                          <?php if($form_data[0]['id_proof_file'] == "" || checkEditableField('Candidate Name', $form_data[0]['candidate_id']) || checkEditableField('APAAR ID/ABC ID', $form_data[0]['candidate_id']))
                          { 
                            $isUpdateProfile = false;
                          ?>
                          <div class="img_preview_input_outer pull-left">
                              <!--  for="id_proof_file" -->
                              <label class="form_label">Upload APAAR ID/ABC ID <sup class="text-danger">*</sup></label>
                              <input type="file" name="id_proof_file" id="id_proof_file" class="form-controlx hide_input_file_cropper" <?php if($form_data[0]['id_proof_file'] == "") { echo 'required'; } ?> />
                            
                              <div class="image-input image-input-outline image-input-circle image-input-empty">
                                <div class="profile-progress"></div>                              
                                <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('id_proof_file', 'ncvet_candidates', 'Edit APAAR ID/ABC ID')">Upload APAAR ID/ABC ID</button>
                              </div>
                              <note class="form_note" id="id_proof_file_err">Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB.</note>
                              
                              <input type="hidden" name="id_proof_file_cropper" id="id_proof_file_cropper" value="<?php echo set_value('id_proof_file_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
                            
                              <?php if(form_error('id_proof_file')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('id_proof_file'); ?></label> <?php } ?>
                              <?php if($id_proof_file_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $id_proof_file_error; ?></label> <?php } ?>
                          </div>
                          <?php }
                          else
                          { ?>
                            <div class="img_preview_input_outer">
                              <label class="form_label">Uploaded APAAR ID/ABC ID <!-- <sup class="text-danger">*</sup> --></label>
                            </div>
                          <?php } ?>
                          
                          <div id="id_proof_file_preview" class="upload_img_preview <?php if($form_data[0]['id_proof_file'] == "" || checkEditableField('Candidate Name', $form_data[0]['candidate_id']) || checkEditableField('APAAR ID/ABC ID', $form_data[0]['candidate_id'])) { echo 'pull-right'; } ?>">
                            <?php 

                            $preview_id_proof_file = '';      
                            if($form_data[0]['id_proof_file'] != "") 
                            { 
                              $preview_id_proof_file = $form_data[0]['id_proof_file'];
                              $preview_id_proof_file = base_url($id_proof_file_path.'/'.$preview_id_proof_file);
                            }
                          
                            if($preview_id_proof_file != "" && strtolower(pathinfo($form_data[0]['id_proof_file'], PATHINFO_EXTENSION)) !== "pdf" && strtolower(pathinfo($form_data[0]['id_proof_file'], PATHINFO_EXTENSION)) !== "")
                            { ?>
                              <a href="<?php echo $preview_id_proof_file."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'APAAR ID/ABC ID - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                <img src="<?php echo $preview_id_proof_file."?".time(); ?>">
                              </a>
                          <?php
                            }
                            else if($preview_id_proof_file != "" && strtolower(pathinfo($form_data[0]['id_proof_file'], PATHINFO_EXTENSION)) === "pdf" && strtolower(pathinfo($form_data[0]['id_proof_file'], PATHINFO_EXTENSION)) !== "")
                            { 
                          ?>

                            <a data-caption="<?php echo 'APAAR ID/ABC ID - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>" data-fancybox data-type="iframe" data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo $preview_id_proof_file."?".time(); ?>" href="javascript:;" title="<?php echo 'APAAR ID/ABC ID - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                              <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="60" height="60" alt="PDF"> 
                            </a>
                          <?php
                            }
                            else
                            {
                              echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                            } 
                          ?>
                          </div><div class="clearfix"></div>
                        </div>
                      </div>
                      
                      <div class="col-xl-6 col-lg-6 mb-4">
                        <div class="form-group">
                          <?php if($form_data[0]['aadhar_file'] == "" || checkEditableField('Candidate Name', $form_data[0]['candidate_id']) || checkEditableField('Aadhar Card', $form_data[0]['candidate_id']))
                          { 
                            $isUpdateProfile = false;
                          ?>
                          <div class="img_preview_input_outer pull-left">
                              <!--  for="aadhar_file" -->
                              <label class="form_label">Upload Aadhar Card <sup class="text-danger">*</sup></label>
                              <input type="file" name="aadhar_file" id="aadhar_file" class="form-controlx hide_input_file_cropper" <?php if($form_data[0]['aadhar_file'] == "") { echo 'required'; } ?> />
                            
                              <div class="image-input image-input-outline image-input-circle image-input-empty">
                                <div class="profile-progress"></div>                              
                                <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('aadhar_file', 'ncvet_candidates', 'Edit Aadhar Card')">Upload Aadhar Card</button>
                              </div>
                              <note class="form_note" id="aadhar_file_err">Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB.</note>
                              
                              <input type="hidden" name="aadhar_file_cropper" id="aadhar_file_cropper" value="<?php echo set_value('aadhar_file_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
                            
                              <?php if(form_error('aadhar_file')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('aadhar_file'); ?></label> <?php } ?>
                              <?php if($aadhar_file_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $aadhar_file_error; ?></label> <?php } ?>
                          </div>
                          <?php }
                          else
                          { ?>
                            <div class="img_preview_input_outer">
                              <label class="form_label">Uploaded Aadhar Card <!-- <sup class="text-danger">*</sup> --></label>
                            </div>
                          <?php } ?>
                          
                          <div id="aadhar_file_preview" class="upload_img_preview <?php if($form_data[0]['aadhar_file'] == "" || checkEditableField('Candidate Name', $form_data[0]['candidate_id']) || checkEditableField('Aadhar Card', $form_data[0]['candidate_id'])) { echo 'pull-right'; } ?>">
                            <?php 
                            $preview_aadhar_file = '';      
                            if($form_data[0]['aadhar_file'] != "") 
                            { 
                              $preview_aadhar_file = $form_data[0]['aadhar_file'];
                              $preview_aadhar_file = base_url($aadhar_file_path.'/'.$preview_aadhar_file);
                            }
                          
                            if($preview_aadhar_file != "" && strtolower(pathinfo($form_data[0]['aadhar_file'], PATHINFO_EXTENSION)) !== "pdf" && strtolower(pathinfo($form_data[0]['aadhar_file'], PATHINFO_EXTENSION)) !== "")
                              { ?>
                              <a href="<?php echo $preview_aadhar_file."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Aadhar Card - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                <img src="<?php echo $preview_aadhar_file."?".time(); ?>">
                              </a>
                              <?php 
                              }
                              else if( $preview_aadhar_file != "" && strtolower(pathinfo($form_data[0]['aadhar_file'], PATHINFO_EXTENSION)) === "pdf" && strtolower(pathinfo($form_data[0]['aadhar_file'], PATHINFO_EXTENSION)) !== "" )
                              { ?> 

                                <a data-caption="<?php echo 'Aadhar Card - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>" data-fancybox data-type="iframe" data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo $preview_aadhar_file."?".time(); ?>" href="javascript:;" title="<?php echo 'Aadhar Card - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                  <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="60" height="60" alt="PDF"> 
                                </a>
                              <?php  
                              }
                              else
                              {
                                echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                              } ?>
                          </div><div class="clearfix"></div>
                        </div>
                      </div>

                      <input type="hidden" id="data_lightbox_hidden" value="candidate_images">
                      <input type="hidden" id="data_lightbox_title_hidden" value="<?php echo $preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                      
                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <?php if($form_data[0]['candidate_photo'] == "" || checkEditableField('Candidate Photo', $form_data[0]['candidate_id']))
                          { 
                            $isUpdateProfile = false;
                          ?>
                          <div class="img_preview_input_outer pull-left">
                            <!-- for="candidate_photo"  -->
                            <label class="form_label">Upload Passport-size Photo <sup class="text-danger">*</sup></label>
                              <input type="file" name="candidate_photo" id="candidate_photo" class="form-controlx hide_input_file_cropper" <?php if($form_data[0]['candidate_photo'] == "") { echo 'required'; } ?> />
                            
                              <div class="image-input image-input-outline image-input-circle image-input-empty">
                                <div class="profile-progress"></div>                              
                                <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('candidate_photo', 'ncvet_candidates', 'Edit Photo')">Upload Photo</button>
                              </div>
                              <note class="form_note" id="candidate_photo_err">Note: Please select only .jpg, .jpeg, .png file upto 5MB.</note>
                              
                              <input type="hidden" name="candidate_photo_cropper" id="candidate_photo_cropper" value="<?php echo set_value('candidate_photo_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
                            
                            <?php if(form_error('candidate_photo')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('candidate_photo'); ?></label> <?php } ?>
                            <?php if($candidate_photo_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $candidate_photo_error; ?></label> <?php } ?>
                          </div>
                          <?php }
                          else
                          { ?>
                            <div class="img_preview_input_outer">
                              <label class="form_label">Uploaded Passport-size Photo <!-- <sup class="text-danger">*</sup> --></label>
                            </div>
                          <?php } ?>
                          
                          <div id="candidate_photo_preview" class="upload_img_preview <?php if($form_data[0]['candidate_photo'] == "" || checkEditableField('Candidate Photo', $form_data[0]['candidate_id'])) { echo 'pull-right'; } ?>">
                            <?php 
                              $preview_candidate_photo = '';
                              if($form_data[0]['candidate_photo'] != "") 
                              { 
                                $preview_candidate_photo = $form_data[0]['candidate_photo'];
                                $preview_candidate_photo = base_url($candidate_photo_path.'/'.$preview_candidate_photo);
                              }
                          
                              if($preview_candidate_photo != "")
                              { ?>
                                <a href="<?php echo $preview_candidate_photo."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Passport-size Photo - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
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
                          <?php if($form_data[0]['candidate_sign'] == "" || checkEditableField('Candidate Signature', $form_data[0]['candidate_id']))
                          { 
                            $isUpdateProfile = false;
                          ?>
                          <div class="img_preview_input_outer pull-left">
                            <!-- for="candidate_sign"  -->
                            <label class="form_label">Upload Candidate Signature <sup class="text-danger">*</sup></label>
                              <input type="file" name="candidate_sign" id="candidate_sign" class="form-controlx hide_input_file_cropper" <?php if($form_data[0]['candidate_sign'] == "") { echo 'required'; } ?> />
                            
                              <div class="image-input image-input-outline image-input-circle image-input-empty">
                                <div class="profile-progress"></div>                              
                                <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('candidate_sign', 'ncvet_candidates', 'Edit Signature')">Upload Candidate Signature</button>
                              </div>
                              <note class="form_note" id="candidate_sign_err">Note: Please select only .jpg, .jpeg, .png file upto 5MB.</note>
                              
                              <input type="hidden" name="candidate_sign_cropper" id="candidate_sign_cropper" value="<?php echo set_value('candidate_sign_cropper'); ?>" />
                            
                            <?php if(form_error('candidate_sign')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('candidate_sign'); ?></label> <?php } ?>
                            <?php if($candidate_sign_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $candidate_sign_error; ?></label> <?php } ?>
                          </div>
                            <?php }
                          else
                          { ?>
                            <div class="img_preview_input_outer">
                              <label class="form_label">Uploaded Candidate Signature <!-- <sup class="text-danger">*</sup> --></label>
                            </div>
                          <?php } ?>
                          
                          <div id="candidate_sign_preview" class="upload_img_preview <?php if($form_data[0]['candidate_sign'] == "" || checkEditableField('Candidate Signature', $form_data[0]['candidate_id'])) { echo 'pull-right'; } ?>">
                            <?php 
                              $preview_candidate_sign = '';
                              if($form_data[0]['candidate_sign'] != "") 
                              { 
                                $preview_candidate_sign = $form_data[0]['candidate_sign'];
                                $preview_candidate_sign = base_url($candidate_sign_path.'/'.$preview_candidate_sign); 
                              }
                              
                              if($preview_candidate_sign != "")
                              { ?>
                                <a href="<?php echo $preview_candidate_sign."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Signature - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
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

                      
                      <div class="col-xl-6 col-lg-6 declaratrion-section" <?php echo $declaration; ?>>
                        <div class="form-group">
                          <?php if($form_data[0]['declarationform'] == "" || checkEditableField('Eligibility', $form_data[0]['candidate_id']) || $form_data[0]['kyc_eligibility_flag'] == 'N')
                          { 
                            
                          ?>
                          <div class="img_preview_input_outer pull-left">
                            <!--  for="declarationform" -->
                            <label class="form_label">Upload Candidate Declaration <sup class="text-danger">*</sup></label>
                              <input type="file" name="declarationform" id="declarationform" class="form-controlx hide_input_file_cropper" <?php if($form_data[0]['declarationform'] == "") { echo 'required'; } ?> />
                            
                              <div class="image-input image-input-outline image-input-circle image-input-empty">
                                <div class="profile-progress"></div>                              
                                <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('declarationform', 'ncvet_candidates', 'Edit Candidate Declaration')">Upload Candidate Declaration</button>
                              </div>
                              <note class="form_note" id="declarationform_err">Note: Please select only .jpg, .jpeg, .png, .pdf file upto 5MB.</note>
                              
                              <input type="hidden" name="declarationform_cropper" id="declarationform_cropper" value="<?php echo set_value('declarationform_cropper'); ?>" />
                            
                            <?php if(form_error('declarationform')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('declarationform'); ?></label> <?php } ?>
                            <?php if($declarationform_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $declarationform_error; ?></label> <?php } ?>
                          </div>
                            <?php }
                          else
                          { ?>
                            <div class="img_preview_input_outer">
                              <label class="form_label">Uploaded Candidate Declaration <!-- <sup class="text-danger">*</sup> --></label>
                            </div>
                          <?php } ?>
                          
                          <div id="declarationform_preview" class="upload_img_preview <?php if($form_data[0]['declarationform'] == "" || checkEditableField('Eligibility', $form_data[0]['candidate_id']) || $form_data[0]['kyc_eligibility_flag'] == 'N') { echo 'pull-right'; } ?>">
                            <?php 
                              $preview_declarationform = '';
                              if($form_data[0]['declarationform'] != "") 
                              { 
                                $preview_declarationform = $form_data[0]['declarationform'];
                                $preview_declarationform = base_url($declarationform_path.'/'.$preview_declarationform); 
                              }
                              if($preview_declarationform != "" && strtolower(pathinfo($form_data[0]['declarationform'], PATHINFO_EXTENSION)) !== "pdf" && strtolower(pathinfo($form_data[0]['declarationform'], PATHINFO_EXTENSION)) !== "")
                              { ?>
                                <a href="<?php echo $preview_declarationform."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Declaration - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                  <img src="<?php echo $preview_declarationform."?".time(); ?>">
                              </a>
                        <?php } else if($preview_declarationform != "" && strtolower(pathinfo($form_data[0]['declarationform'], PATHINFO_EXTENSION)) === "pdf" && strtolower(pathinfo($form_data[0]['declarationform'], PATHINFO_EXTENSION)) !== "")
                              { ?>
                                <a data-caption="<?php echo 'Declaration - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>" data-fancybox data-type="iframe" data-src="https://mozilla.github.io/pdf.js/web/viewer.html?file=<?php echo $preview_declarationform."?".time(); ?>" href="javascript:;" title="<?php echo 'Declaration - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                  <img src="<?php echo base_url('assets/images/pdf.png'); ?>" width="60" height="60" alt="PDF"> 
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
                    <?php if (!$isUpdateProfile || $form_data[0]['updated_fields'] != '') { ?>                 
                      <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="submit_btn_outer2">
                          <input type="submit" class="btn btn-primary" id="submitAll" name="submitAll" value="<?php echo "Update Profile"; ?>" onclick="update_file_validations()"> 
                        </div>
                      </div>
                    <?php } ?>
                  </form>
                </div>
              </div>

              <div id="common_log_outer"></div>
            </div>          
          </div>
        </div>
        <?php $this->load->view('ncvet/candidate/inc_footerbar_candidate'); ?>    
      </div>
    </div>
    <?php
      $checkFlag = 'No'; 
      if(checkEditableField('Candidate Name', $form_data[0]['candidate_id']) || $form_data[0]['kyc_fullname_flag'] == 'N') 
      {
        $checkFlag = 'Yes';
      } 
    ?>
    <style type="text/css">
      /* Custom position for FancyBox */
      .custom-fancybox .fancybox__container {
        align-items: flex-start !important;  /* push to top */
        justify-content: center;             /* keep horizontally centered */
      }

      .custom-fancybox .fancybox__content {
        width: 70% !important;
        height: 100% !important;
        margin-left: 250px; 
      }

      .fancybox__caption {
        position: absolute;
        top: 92%;
        left: 29.5%;
        bottom: auto;
        right: auto;
        transform: translateY(-50%);
        text-align: left;
        width: auto;
        /*background: rgba(0,0,0,0.6);*/
        /*color: #fff;*/
        padding: 6px 10px;
        border-radius: 4px;
      }

    </style>

    <?php $this->load->view('ncvet/inc_footer'); ?>   
    <?php $this->load->view('ncvet/common/inc_common_validation_all'); ?>
    <?php $this->load->view('ncvet/common/inc_cropper_script_edit', array('page_name'=>'ncvet_candidate_update_profile')); ?>
  
    <?php // $this->load->view('ncvet/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_candidate_id, 'module_slug'=>'candidate_action', 'log_title'=>'Candidate Log')); ?>    
    
    <script>
      Fancybox.bind("[data-fancybox]", {
        mainClass: "custom-fancybox",
        autoFocus: false
      });
    
    var salutation = "<?php echo $form_data[0]['salutation']; ?>";  

    var dob = $('#dob').datepicker({ keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy-mm-dd", clearBtn: true,  endDate:"<?php echo $dob_end_date; ?>" });  

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

    function isValidGender(salutation) 
    {
      var selectedSalutation = salutation;
      var value = "<?php echo $form_data[0]['gender']; ?>";

      if (value != $('#gender').val()) {
        value = $('#gender').val();
      }
      
      if($.trim(value).length == 0) { return true; }
      {
        if(typeof selectedSalutation != "undefined")
        {
          let current_gender = $.trim(value);
          if(selectedSalutation == 'Mr.')//Mr.
          {
            if(current_gender == 1) { $('#gender-error').text(""); return true; }
            else 
            { 
              // alert(333);
              $('#gender-error').text("Invalid gender selected");
              return false; 
            }
          } 
          else if(selectedSalutation == 'Mrs.' || selectedSalutation == 'Ms.') //Mrs. or Ms.
          {
            if(current_gender == 2) { return true; }
            else 
            { 
              $('#gender-error').text("Invalid gender selected");
              return false; 
            }
          }              
          else
          {
            $('#gender-error').text("");
            return true;
          }
        }
        else
        {
          $('#gender-error').text("");
          return true;
        }
      }
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

    var existing_qualification_file  = "<?php echo $form_data[0]['qualification_certificate_file']; ?>";
    var existing_qualification_certificate_file = '<?php echo $form_data[0]['qualification_certificate_file']; ?>';

    var existing_institute_idproof  = "<?php echo $form_data[0]['institute_idproof']; ?>";
    var existing_institute_idproof_file = '<?php echo $form_data[0]['institute_idproof']; ?>';

    var existing_visual_impaired  = "<?php echo $form_data[0]['vis_imp_cert_img']; ?>";
    var existing_visual_impaired_file = '<?php echo $form_data[0]['vis_imp_cert_img']; ?>';

    var existing_orthopedically_handicapped  = "<?php echo $form_data[0]['orth_han_cert_img']; ?>";
    var existing_orthopedically_handicapped_file = '<?php echo $form_data[0]['orth_han_cert_img']; ?>';

    var existing_cerebral_palsy  = "<?php echo $form_data[0]['cer_palsy_cert_img']; ?>";
    var existing_cerebral_palsy_file = '<?php echo $form_data[0]['cer_palsy_cert_img']; ?>';

    var visually_impaired          = "<?php echo $form_data[0]['visually_impaired']; ?>";
    var orthopedically_handicapped = "<?php echo $form_data[0]['orthopedically_handicapped']; ?>";
    var cerebral_palsy             = "<?php echo $form_data[0]['cerebral_palsy']; ?>";

    // Attach a single change listener to all sub-disability radio buttons
    $('.benchmark-disability').on('change', function() {
        const $changedRadio = $(this);
        const radioName = $changedRadio.attr('name'); // e.g., 'visually_impaired'
        const radioValue = $changedRadio.val();       // 'Y' or 'N'
        
        // Construct the selector for the corresponding file section
        // Example: 'visually_impaired' -> '.visually-impaired-section'
        const targetSectionClass = '.' + radioName.replace(/_/g, '-') + '-section';
        const $targetSection = $(targetSectionClass);

        // Check if the current radio button is enabled (editable)
        if ($changedRadio.prop('disabled')) {
            return; // Exit if the field is not editable
        }

        if (radioValue === 'Y') {
            // Show the corresponding file upload section
            $targetSection.slideDown();

            if (radioName == 'visually_impaired') {
              visually_impaired = radioValue;
            }

            if (radioName == 'orthopedically_handicapped') {
              orthopedically_handicapped  = radioValue;
            }

            if (radioName == 'cerebral_palsy') {
              cerebral_palsy = radioValue;
            }

        } else if (radioValue === 'N') {
            
            if (radioName == 'visually_impaired') {
              visually_impaired = radioValue;
            }

            if (radioName == 'orthopedically_handicapped') {
              orthopedically_handicapped  = radioValue;
            }

            if (radioName == 'cerebral_palsy') {
              cerebral_palsy = radioValue;
            }  

            // Hide the corresponding file upload section and clear any selected file
            $targetSection.slideUp(function() {
                // Find the file input within the hidden section and reset it
                $targetSection.find('input[type="file"]').val(''); 
            });
        }
    });  

    // --- New Function for Experience File Section ---
    function toggleExperienceFileSection() {
        // Get the value of the checked radio button for 'experience'
        const experienceValue = $('input[name="experience"]:checked').val();
        
        if (experienceValue === 'Y') {
            // Show the file section if 'Yes' is selected
            $('.experience-file-section').slideDown();
        } else {
            // Hide the file section if 'No' (N) is selected
            $('.experience-file-section').slideUp();
        }
    }

    // --- Attach the change event to the Experience radio group ---
    $('input[name="experience"]').on('change', function() {
        // Only run the function if the radio button is enabled (editable)
        if (!$(this).prop('disabled')) {
            toggleExperienceFileSection();
        }
    });

      var existing_sel_elibility = $('#qualification').val();
      var sel_elibility = $('#qualification').val();
      var qualification_eligibility = sel_elibility;
      var qualification_state = $('#qualification_state').val();

      function show_hide_dependent_fields(event)
      {
        sel_elibility = $(event).val();

        qualification_eligibility = sel_elibility;
        switch (sel_elibility) 
        {
          case '1': 
              $('.experience-section').show();
              $('.graduation-semester-section').hide();
              $('.post-graduation-semester-section').hide();
              $('.university-section').hide();
              $('.college-section').hide();
              $('#experience').removeAttr('readonly').removeAttr('disabled');
              $('.qualification-lable').html('Upload 12th Pass Certificate <sup class="text-danger">*</sup>');
              $('.qualification-lable-button').text('Upload 12th Pass Certificate');
              $('.qualification-file-section').show();
              $('.declaratrion-section').hide();

              $('.label_qualification_state').html('State of Working <sup class="text-danger">*</sup>');
              $('.qualification_state_div').show();
              $('#qualification_state').removeAttr('readonly').removeAttr('disabled');
              if ( existing_qualification_file != '' && existing_qualification_file != null ) 
              {
                if ( sel_elibility == existing_sel_elibility ) {
                  $('.qualification-file').show();
                  $('.default-file').hide();
                  existing_qualification_certificate_file = existing_qualification_file;
                  $('#qualification_state').val(qualification_state).trigger('chosen:updated');
                } else {
                  $('.qualification-file').hide();
                  $('.default-file').show();
                  existing_qualification_certificate_file = '';
                  $('#qualification_state').val('').trigger('chosen:updated');
                }
              }


              if ($('#experience').val() == 'Y') {
                $('.experience-file-section').show();
              } else {
                $('.experience-file-section').hide();
              }
              $('.institute-id-section').hide();

              $('#collage').attr('readonly', 'readonly').attr('disabled', 'disabled');
              $('#post_graduation_sem').attr('readonly', 'readonly').attr('disabled', 'disabled');
              $('#university').attr('readonly', 'readonly').attr('disabled', 'disabled');
              $('#graduation_sem').attr('readonly', 'readonly').attr('disabled', 'disabled');              
            break;
          case '2':
              $('.experience-section').hide();
              $('.graduation-semester-section').hide();
              $('.post-graduation-semester-section').hide();
              $('.university-section').hide();
              $('.college-section').hide();
              $('.qualification-lable').html('Upload Degree / Provisional degree certificate <sup class="text-danger">*</sup>');
              $('.qualification-lable-button').text('Upload Degree / Provisional degree certificate');
              $('.label_qualification_state').html('State of Degree College <sup class="text-danger">*</sup>');  
              $('.qualification_state_div').show();
              $('.declaratrion-section').hide();

              $('#qualification_state').removeAttr('readonly').removeAttr('disabled');
              if ( existing_qualification_file != '' && existing_qualification_file != null ) 
              {
                if ( sel_elibility == existing_sel_elibility ) {
                  $('.qualification-file').show();
                  $('.default-file').hide();
                  existing_qualification_certificate_file = existing_qualification_file;
                  $('#qualification_state').val(qualification_state).trigger('chosen:updated');
                } else {
                  $('.qualification-file').hide();
                  $('.default-file').show();
                  existing_qualification_certificate_file = '';
                  $('#qualification_state').val('').trigger('chosen:updated');
                }
              }

              $('.qualification-file-section').show();
              $('.experience-file-section').hide();
              $('.institute-id-section').hide();

              $('#experience').attr('readonly', 'readonly').attr('disabled', 'disabled');
              $('#collage').attr('readonly', 'readonly').attr('disabled', 'disabled');
              $('#post_graduation_sem').attr('readonly', 'readonly').attr('disabled', 'disabled');
              $('#university').attr('readonly', 'readonly').attr('disabled', 'disabled');
              $('#graduation_sem').attr('readonly', 'readonly').attr('disabled', 'disabled');

            break;
          case '3':
              $('.experience-section').hide();
              $('.post-graduation-semester-section').hide();
              $('.graduation-semester-section').show();
              $('.university-section').show();
              $('.college-section').show();
              $('.qualification-file-section').hide();
              $('.experience-file-section').hide();
              $('.institute-id-section').show();
              $('.label_qualification_state').html('State of College / Academic Institution <sup class="text-danger">*</sup>');
              $('.qualification_state_div').show();
              $('#qualification_state').removeAttr('readonly').removeAttr('disabled');
              $('.declaratrion-section').show();

              if ( existing_institute_idproof != '' && existing_institute_idproof != null ) 
              {
                if ( sel_elibility == existing_sel_elibility ) {
                  $('.institute-id-file').show();
                  $('.default-institute-id-file').hide();
                  existing_institute_idproof_file = existing_institute_idproof;
                  $('#qualification_state').val(qualification_state).trigger('chosen:updated');
                } else {
                  $('.institute-id-file').hide();
                  $('.default-institute-id-file').show();
                  existing_institute_idproof_file = '';
                  $('#qualification_state').val('').trigger('chosen:updated');
                }
              }

              $('#experience').attr('readonly', 'readonly').attr('disabled', 'disabled');
              $('#collage').removeAttr('readonly').removeAttr('disabled');
              $('#post_graduation_sem').attr('readonly', 'readonly').attr('disabled', 'disabled');
              
              $('#university').removeAttr('readonly').removeAttr('disabled');
              $('#graduation_sem').removeAttr('readonly').removeAttr('disabled');

            break;
          case '4':
              $('.experience-section').hide();
              $('.graduation-semester-section').hide();
              $('.post-graduation-semester-section').show();
              $('.university-section').show();
              $('.college-section').show();
              $('.qualification-file-section').hide();
              $('.experience-file-section').hide();
              $('.institute-id-section').show();
              $('.declaratrion-section').show();
              
              $('.label_qualification_state').html('State of College / Academic Institution <sup class="text-danger">*</sup>');
              $('.qualification_state_div').show();
              $('#qualification_state').removeAttr('readonly').removeAttr('disabled');
              if ( existing_institute_idproof != '' && existing_institute_idproof != null ) 
              {
                if ( sel_elibility == existing_sel_elibility ) {
                  $('.institute-id-file').show();
                  $('.default-institute-id-file').hide();
                  existing_institute_idproof_file = existing_institute_idproof;
                  $('#qualification_state').val(qualification_state).trigger('chosen:updated');
                } else {
                  $('.institute-id-file').hide();
                  $('.default-institute-id-file').show();
                  existing_institute_idproof_file = '';
                  $('#qualification_state').val('').trigger('chosen:updated');
                }
              }

              $('#experience').attr('readonly', 'readonly').attr('disabled', 'disabled');
              $('#collage').removeAttr('readonly').removeAttr('disabled');
              $('#post_graduation_sem').removeAttr('readonly').removeAttr('disabled');
              
              $('#university').removeAttr('readonly').removeAttr('disabled');
              $('#graduation_sem').attr('readonly', 'readonly').attr('disabled', 'disabled');

            break;    
          default:
              $('.experience-section').hide();
              $('.graduation-semester-section').hide();
              $('.post-graduation-semester-section').hide();
              $('.university-section').hide();
              $('.college-section').hide();
              $('.qualification_state_div').hide();
              $('#qualification_state').attr('readonly', 'readonly').attr('disabled', 'disabled');
              $('#experience').attr('readonly', 'readonly').attr('disabled', 'disabled');
              $('#collage').attr('readonly', 'readonly').attr('disabled', 'disabled');
              $('#post_graduation_sem').attr('readonly', 'readonly').attr('disabled', 'disabled');
              $('#university').attr('readonly', 'readonly').attr('disabled', 'disabled');
              $('#graduation_sem').attr('readonly', 'readonly').attr('disabled', 'disabled');
            break;
        }
      }

      var emailVerify  = true;
      var mobileVerify = true;
      $('#reset_btn_email').click(function() {
        $('#email_id').attr('readonly',false);
        $('#email_id').attr('disabled',false);
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
        $('#mobile_no').attr('disabled',false);
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
        var email_id    = $('#email_id').val();
          var type      = 'verify_otp';
          
        var data = {};
        data.email_id      = email_id;
        data.otp       = otp;
        data.verify_type = verify_type;

        if (otp != '' && otp != undefined) {
          send_verify_otp(type,data,this)       
        } else {
          sweet_alert_error('Please enter the OTP.');
        } 
      })

      $('.verify-otp-mobile').click(function() 
      {
        var otp         = $('#otp_mobile').val();
        var verify_type = $(this).attr('data-verify-type');
        var mobile_no   = $('#mobile_no').val();
        var type        = 'verify_otp';
          
        var data = {};
            data.mobile_no   = mobile_no;
            data.otp         = otp;
            data.verify_type = verify_type;
        
        if (otp != '' && otp != undefined) 
        {
          send_verify_otp_mobile(type,data,this)        
        } 
        else 
        {
          sweet_alert_error('Please enter the OTP.');
        } 
      })

      $('.send-otp-mobile').click(function() 
      {
        var mobile_no = $('#mobile_no').val();
        var type  = $(this).attr('data-type');
      

        if (type == 'resend_otp') {
          $('#otp_mobile').val('');
        }
        var data = {};
        data.mobile_no        = mobile_no;
        data.otp              = '';
        data.enc_candidate_id = "<?php echo $enc_candidate_id; ?>";
        data.verify_type      = '';
          
        if (mobile_no.trim() != '') {
            if (mobile_no.length == 10 && $.isNumeric(mobile_no) && !mobile_no.includes('.')) {
                send_verify_otp_mobile(type,data,this)
            } else {
              if ( !$.isNumeric(mobile_no) || mobile_no.includes('.')) {
                $('.mobile').addClass('parsley-error');
                  $('#mobile').focus();
                  sweet_alert_error('Characters and special characters not allowed.');
              } else {
                $('.mobile').addClass('parsley-error');
                  $('#mobile').focus();
                  sweet_alert_error('Please enter a atleast 10 digit mobile no.');
              }
            }
        } else {
          $('.mobile').addClass('parsley-error');
            $('#mobile').focus();
            sweet_alert_error('Please enter mobile no. first.');
        }
      })

      function send_verify_otp_mobile(type,data,selector) 
      {
        $.ajax({
          type : 'POST',
          url  : "<?php echo site_url('ncvet/candidate/dashboard_candidate/send_otp_mobile'); ?>",
          data : {'mobile_no':data.mobile_no,'type':type,'otp':data.otp,'verify_type':data.verify_type,'enc_candidate_id':data.enc_candidate_id},
          beforeSend: function(xhr) {
              $(selector).attr('disabled',true).text('Processing..')  
            },
          async: true,
          success: function(otp_response) {
            console.log(otp_response);
            var json_otp_response = JSON.parse(otp_response);
            if (json_otp_response.status) {
              if (type == 'send_otp') {
                $('#send_otp_btn_mobile').hide();
                $('#send_otp_btn_mobile').attr('disabled',false).text('Get OTP')
                $('.verify-otp-section-mobile').show();
                $('#reset_btn_mobile').show(); 

                if ($('.my-email-div').length) {
                  $('.my-email-div').remove();
                }

              } else if (type == 'resend_otp') {
                $(selector).attr('disabled',false).text('Resend OTP')
                $('.verify-otp-section-mobile').show();

                if ($('.my-email-div').length) {
                  $('.my-email-div').remove();
                }

              } else if (type == 'verify_otp') {
                $(selector).attr('disabled',false).text('Verify OTP')
                $('.verify-otp-section-mobile').hide();

                var customDiv = '<div class="col-xl-6 col-lg-6 my-email-div"><div class="form-group"></div></div>';
                $('.verify-otp-section:first').before(customDiv);

                mobileVerify = true;
                $('#mobile_verify_status').val('yes');
                $('#mobile_no').valid();
              }

              $('.mobile').removeClass('parsley-error');
              $('.mobile').addClass('parsley-success');
              $('#mobile_no').attr('readonly',true);
              
              if(json_otp_response.status)
              {
                swal({
                  title: "Success!",           // Default title is "Success!"
                  text: json_otp_response.msg, // Default message
                  type: "success",             // Optional: Auto-close after 2 seconds (2000 ms)
                  showConfirmButton: true      // Optional: Hide the default "OK" button
                });
              } 
              else 
              {
                sweet_alert_error(json_otp_response.msg);
              }
            } else {
              if (type == 'send_otp') {
                $(selector).attr('disabled',false).text('Get OTP')
              } else if (type == 'resend_otp') { 
                $(selector).attr('disabled',false).text('Resend OTP')
              } else if (type == 'verify_otp') {
                $(selector).attr('disabled',false).text('Verify OTP')
              } 

              if(json_otp_response.status)
              {
                swal({
                  title: "Success!",           // Default title is "Success!"
                  text: json_otp_response.msg, // Default message
                  type: "success",             // Optional: Auto-close after 2 seconds (2000 ms)
                  showConfirmButton: true      // Optional: Hide the default "OK" button
                });
              } 
              else 
              {
                sweet_alert_error(json_otp_response.msg);
              } 
            }
            $('#otp_mobile').val('');
          }
        });
      }

      $('.send-otp').click(function() 
      {
        var email_id = $('#email_id').val();
        var type  = $(this).attr('data-type');
        var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/; // Regular expression for email format
        
        if (type == 'resend_otp') {
          $('#otp').val('');
        }
        var data = {};
        data.email_id       = email_id;
        data.otp       = '';
        data.verify_type = '';
          
        if (email_id.trim() != '') {
            if (emailRegex.test(email_id)) {
                send_verify_otp(type,data,this)
            } else {
              $('.email-id').addClass('parsley-error');
                $('#email').focus();
                sweet_alert_error('Please enter a valid email address.');
            }
        } else {
          $('.email-id').addClass('parsley-error');
            $('#email').focus();
            sweet_alert_error('Please enter email id first.');
        }
      })

      function send_verify_otp(type,data,selector) {
        $.ajax({
          type: 'POST',
          url: "<?php echo site_url('ncvet/candidate/dashboard_candidate/send_otp'); ?>",
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

                var $mobileOtpSection = $('.verify-otp-section-mobile');
                
                // Check if the element is hidden (returns true if hidden, false if visible)
                if ($mobileOtpSection.is(':hidden')) {
                  if ($('.my-email-div').length) {
                    $('.my-email-div').remove();
                  }
                  var customDiv = '<div class="col-xl-6 col-lg-6 my-email-div"><div class="form-group"></div></div>';
                  $('.verify-otp-section:first').before(customDiv);
                } 

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
              
              if(json_otp_response.status)
              {
                swal({
                  title: "Success!",           // Default title is "Success!"
                  text: json_otp_response.msg, // Default message
                  type: "success",             // Optional: Auto-close after 2 seconds (2000 ms)
                  showConfirmButton: true      // Optional: Hide the default "OK" button
                });
              } 
              else 
              {
                sweet_alert_error(json_otp_response.msg);
              }

            } else {
              if (type == 'send_otp') {
                $(selector).attr('disabled',false).text('Get OTP')
              } else if (type == 'resend_otp') { 
                $(selector).attr('disabled',false).text('Resend OTP')
              } else if (type == 'verify_otp') {
                $(selector).attr('disabled',false).text('Verify OTP')
              } 

              if(json_otp_response.status)
              {
                swal({
                  title: "Success!",           // Default title is "Success!"
                  text: json_otp_response.msg, // Default message
                  type: "success",             // Optional: Auto-close after 2 seconds (2000 ms)
                  showConfirmButton: true      // Optional: Hide the default "OK" button
                });
              } 
              else 
              {
                sweet_alert_error(json_otp_response.msg);
              }
            }
            $('#otp').val('');
          }
        });
      }

      // Helper function to trigger the file section toggle for a specific radio group
    function triggerDisabilityFileToggle(radioName) {
        // Find the currently checked radio in the group and trigger its change event
        $(`input[name="${radioName}"]:checked.benchmark-disability`).trigger('change');
    }
    
      function toggleDisabilitySubFields() {
        // Get the value of the checked radio button for 'benchmark_disability'
        const benchmarkValue = $('input[name="benchmark_disability"]:checked').val();
        
        // Define the names of the sub-disability radio groups
        const subDisabilityNames = ['visually_impaired', 'orthopedically_handicapped', 'cerebral_palsy'];
        
        if (benchmarkValue === 'Y') {
            // Show the sub-fields if 'Yes' is selected
            $('.disability-sub-field').slideDown();

            // When showing, we initialize them to 'No'
            // We need to trigger the change event on these to ensure the *file sections* hide immediately.
            subDisabilityNames.forEach(function(name) {
                // Set to 'No'
                $(`input[name="${name}"][value="N"]`).prop('checked', true);
                // Trigger change to update the corresponding file section (e.g., visually-impaired-section)
                triggerDisabilityFileToggle(name); 
            });

        } else {
            // Hide the sub-fields if 'No' (N) or nothing is selected
            $('.disability-sub-field').slideUp();
            
            // Set all sub-disability radios to 'No' (N) when hiding.
            subDisabilityNames.forEach(function(name) {
                // Set to 'No'
                $(`input[name="${name}"][value="N"]`).prop('checked', true);
                // Trigger change to update the corresponding file section
                triggerDisabilityFileToggle(name);
            });
        }
      }

      // Attach the change event to the main radio group
      $('input[name="benchmark_disability"]').on('change', function() {
          // Check if the fields are editable before responding to the change
          if (!$(this).prop('disabled')) {
              toggleDisabilitySubFields();
          }
      });

    </script>

    <script type="text/javascript">
      /********** START : Function to get the city dropdown values as per state selection ***********/
      var declarationform_required_flag = true;
      var is_valid_file = true;
      var qualification_certificate_file_required_flag = true;
      var qualification_is_valid_file     = true;
      var exp_certificate_required_flag   = true;
      var exp_certificate_is_valid_file   = true;
      var institute_idproof_required_flag = true;
      var institute_idproof_is_valid_file = true;
      
      var benchmark_disability       = "<?php echo $form_data[0]['benchmark_disability']; ?>";

      var vis_imp_cert_img_is_valid_file = true;
      var vis_imp_cert_img_required_flag = true;
      
      var orth_han_cert_img_is_valid_file = true;
      var orth_han_cert_img_required_flag = true;
      
      var cer_palsy_cert_img_is_valid_file = true;
      var cer_palsy_cert_img_required_flag = true;

      function get_city_ajax(state_id,address_type)
      {
        $("#page_loader").show();
        parameters="state_id="+state_id;
        
        $.ajax(
        {
          type: "POST",
          url: "<?php echo site_url('ncvet/candidate/dashboard_candidate/get_city_ajax'); ?>",
          data: {'state_id':state_id,'address_type':address_type},
          cache: false,
          dataType: 'JSON',
          async:false,
          success:function(data)
          {
            if(data.flag == "success")
            {
              if (address_type == 'communication') {
                $("#city_outer").html(data.response);
              } else {
                $("#city_pr_outer").html(data.response);
              }

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
        qualification_eligibility = sel_elibility;
        
        var sel_visually_impaired          = $('input[name="visually_impaired"]:checked').val();
        var sel_orthopedically_handicapped = $('input[name="orthopedically_handicapped"]:checked').val();
        var sel_cerebral_palsy             = $('input[name="cerebral_palsy"]:checked').val();
        
        var id_proof_file_required_flag = true;
        var form_id_proof_file = '<?php echo $form_data[0]['id_proof_file'] ?>';
        if($("#id_proof_file_cropper").val() != "" || form_id_proof_file != "") { id_proof_file_required_flag = false; }
        $("#id_proof_file").rules("add", 
        {
          required: id_proof_file_required_flag,
          check_valid_file:true,
        });

        var aadhar_file_required_flag = true;
        var form_aadhar_file = '<?php echo $form_data[0]['aadhar_file'] ?>';
        if($("#aadhar_file_cropper").val() != "" || form_aadhar_file != "") { aadhar_file_required_flag = false; }
        $("#aadhar_file").rules("add", 
        {
          required: aadhar_file_required_flag,
          check_valid_file:true,
        });
        
        var candidate_photo_required_flag = true;         
        var form_candidate_photo = '<?php echo $form_data[0]['candidate_photo']; ?>';
        if($("#candidate_photo_cropper").val() != "" || form_candidate_photo != "") { candidate_photo_required_flag = false; }
        $("#candidate_photo").rules("add", 
        {
          required: candidate_photo_required_flag,
          check_valid_file:true,
        });

        var candidate_sign_required_flag = true;
        var form_candidate_sign = '<?php echo $form_data[0]['candidate_sign'] ?>';
        if($("#candidate_sign_cropper").val() != "" || form_candidate_sign != "") { candidate_sign_required_flag = false; }
        $("#candidate_sign").rules("add", 
        {
          required: candidate_sign_required_flag,
          check_valid_file:true,
        });

        var form_qualification_certificate_file = existing_qualification_certificate_file;
        if($("#qualification_certificate_file_cropper").val() != "" || form_qualification_certificate_file != "") {qualification_certificate_file_required_flag = false; } else { qualification_certificate_file_required_flag = true; }
        
        if(qualification_eligibility != '1' && qualification_eligibility != '2') 
        { 
          qualification_certificate_file_required_flag = false; 
          qualification_is_valid_file = false; 
        }
        
        $("#qualification_certificate_file").rules("add", 
        {
          required: qualification_certificate_file_required_flag,
          check_valid_file:true,
        });

        
        var form_institute_idproof = existing_institute_idproof_file;
        var kyc_institute_idproof_flag = '<?php echo $form_data[0]['kyc_institute_idproof_flag']; ?>';

        if($("#institute_idproof_cropper").val() != "" || form_institute_idproof != "") { institute_idproof_required_flag = false;} else { if(kyc_institute_idproof_flag == 'N') { institute_idproof_required_flag = true; } else { institute_idproof_required_flag = false; } }

        if(qualification_eligibility != '3' && qualification_eligibility != '4') 
        { 
          institute_idproof_required_flag = false; 
          institute_idproof_is_valid_file = false; 
        }

        $("#institute_idproof").rules("add", 
        {
          required: institute_idproof_required_flag,
          check_valid_file:true,
        });

        var form_exp_certificate = '<?php echo $form_data[0]['exp_certificate'] ?>';
        if($("#exp_certificate_cropper").val() != "" || form_exp_certificate != "") { exp_certificate_required_flag = false; }
        $("#exp_certificate").rules("add", 
        {
          required: exp_certificate_required_flag,
          check_valid_file:true,
        });

        if(qualification_eligibility != '1') 
        { 
          exp_certificate_required_flag = false; 
          exp_certificate_is_valid_file = false; 
        }

        var form_declarationform = '<?php echo $form_data[0]['declarationform'] ?>';
        if($("#declarationform_cropper").val() != "" || form_declarationform != "") { declarationform_required_flag = false; }
        
        if(qualification_eligibility != '3' && qualification_eligibility != '4') 
        { 
          declarationform_required_flag = false; 
          is_valid_file = false; 
        }

        $("#declarationform").rules("add", 
        {
          required: declarationform_required_flag,
          check_valid_file:is_valid_file,
        });

        // Disability validation
        var form_vis_imp_cert_img = existing_visual_impaired_file;
        if($("#vis_imp_cert_img_cropper").val() != "" || form_vis_imp_cert_img != "") { vis_imp_cert_img_required_flag = false; } else { vis_imp_cert_img_required_flag = true; }
        
        if(visually_impaired != 'Y') 
        { 
          vis_imp_cert_img_required_flag = false; 
          is_valid_file = false; 
        }

        $("#vis_imp_cert_img").rules("add", 
        {
          required: vis_imp_cert_img_required_flag,
          check_valid_file:is_valid_file,
        });
        
        var form_orth_han_cert_img = existing_orthopedically_handicapped_file;
        if($("#orth_han_cert_img_cropper").val() != "" || form_orth_han_cert_img != "") { orth_han_cert_img_required_flag = false; } else { orth_han_cert_img_required_flag = true; }
        
        if(orthopedically_handicapped != 'Y') 
        { 
          orth_han_cert_img_required_flag = false; 
          is_valid_file = false; 
        }

        $("#orth_han_cert_img").rules("add", 
        {
          required: orth_han_cert_img_required_flag,
          check_valid_file:is_valid_file,
        });

        var form_cer_palsy_cert_img = existing_cerebral_palsy_file;
        if($("#cer_palsy_cert_img_cropper").val() != "" || form_cer_palsy_cert_img != "") { cer_palsy_cert_img_required_flag = false; } else { cer_palsy_cert_img_required_flag = true; }
        
        if(cerebral_palsy != 'Y') 
        { 
          cer_palsy_cert_img_required_flag = false; 
          is_valid_file = false; 
        }

        $("#cer_palsy_cert_img").rules("add", 
        {
          required: cer_palsy_cert_img_required_flag,
          check_valid_file:is_valid_file,
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
          // 👇 Important: validate on typing, selecting, or leaving field
          onkeyup: function(element) { $(element).valid(); },
          onchange: function(element) { $(element).valid(); },
          onblur: function(element) { $(element).valid(); },         
          rules:
          {
            salutation:  { required: function(element) { return !$(element).prop('readonly'); } },
            first_name:  { required: function(element) { return !$(element).prop('readonly');},pattern: /^[a-zA-Z\s]+$/},
            middle_name: { pattern: /^[a-zA-Z\s]+$/ },
            last_name:   { pattern: /^[a-zA-Z\s]+$/ },
            gender: { required: function(element) { return !$(element).prop('readonly'); },validate_gender: function(element) { return !$(element).prop('readonly'); } },
            dob: { required: function(element) { return !$(element).prop('readonly'); },dateFormat:'Y-m-d', validate_dob:true },
            guardian_salutation: { required: function(element) { return !$(element).prop('readonly'); } },
            guardian_name: { required: function(element) { return !$(element).prop('readonly'); }, pattern: /^[a-zA-Z\s]+$/, maxlength: 60 },
            address1:{ required: function(element) { return !$(element).prop('readonly'); } },
            state:{ required: function(element) { return !$(element).prop('readonly'); } },  
            city:{ required: function(element) { return !$(element).prop('readonly'); } }, 
            district:{ required: function(element) { return !$(element).prop('readonly'); },pattern: /^[a-zA-Z\s]+$/ }, 
            pincode:{ required: function(element) { return !$(element).prop('readonly'); }, remote: { url: "<?php echo site_url('ncvet/candidate/dashboard_candidate/validation_check_valid_pincode/0/1'); ?>", type: "post", data: { "selected_state_code": function() { return $("#state").val(); } } } },  //check validation for pincode as per selected state           
            address1_pr:{ required: function(element) { return !$(element).prop('readonly'); } },
            state_pr:{ required: function(element) { return !$(element).prop('readonly'); } },  
            city_pr:{ required: function(element) { return !$(element).prop('readonly'); } }, 
            district_pr:{ required: function(element) { return !$(element).prop('readonly'); },pattern: /^[a-zA-Z\s]+$/ }, 
            pincode_pr:{ required: function(element) { return !$(element).prop('readonly'); }, remote: { url: "<?php echo site_url('ncvet/candidate/dashboard_candidate/validation_check_valid_pincode_permenant/0/1'); ?>", type: "post", data: { "selected_state_code": function() { return $("#state_pr").val(); } } } },  //check validation for pincode as per selected state           
            mobile_no:{ required: function(element) { return !$(element).prop('readonly'); }, allow_only_numbers:true, first_zero_not_allowed:true, maxlength:10, minlength:10 }, 
            otp_mobile: { required: function(element) { return !$(element).prop('readonly'); }, maxlength:6, minlength:6 },
            email_id:{ required: function(element) { return !$(element).prop('readonly'); }, maxlength:80, valid_email:true }, 
            otp:{ required: function(element) { return !$(element).prop('readonly'); }, maxlength:6, minlength:6 },
            qualification:{ required: function(element) { return !$(element).prop('readonly'); } },
            qualification_state:{ required: function(element) { return !$(element).prop('readonly'); } },
            experience:{ required: function(element) { return !$(element).prop('readonly'); } },
            collage:{ required: function(element) { return !$(element).prop('readonly'); }, maxlength:160},
            post_graduation_sem:{ required: function(element) { return !$(element).prop('readonly'); } },
            university:{ required: function(element) { return !$(element).prop('readonly'); }, maxlength:75 },
            graduation_sem:{ required: function(element) { return !$(element).prop('readonly'); } },
            graduation_sem:{ required: function(element) { return !$(element).prop('readonly'); } },
            aadhar_no:{ required: function(element) { return !$(element).prop('readonly'); }, allow_only_numbers:true, maxlength:12,remote: { url: "<?php echo site_url('ncvet/candidate/dashboard_candidate/validation_check_aadhar_no_exist/0/1'); ?>", type: "post", data: { "enc_candidate_id": function() { return "<?php echo $enc_candidate_id; ?>"; } } } },
            id_proof_number:{ required: function(element) { return !$(element).prop('readonly'); }, allow_only_numbers:true, maxlength:12,remote: { url: "<?php echo site_url('ncvet/candidate/dashboard_candidate/validation_check_aapar_id_exist/0/1'); ?>", type: "post", data: { "enc_candidate_id": function() { return "<?php echo $enc_candidate_id; ?>"; } } } },
            benchmark_disability:{ required: function(element) { return !$(element).prop('readonly'); } },
            visually_impaired:{ required: function(element) { return !$(element).prop('readonly'); } },
            orthopedically_handicapped:{ required: function(element) { return !$(element).prop('readonly'); } },
            cerebral_palsy:{ required: function(element) { return !$(element).prop('readonly'); } },

            // alt_mobile_no:{ allow_only_numbers:true, first_zero_not_allowed:true, maxlength:10, minlength:10 },
            // email_id:{ required: true, maxlength:80, valid_email:true, remote: { url: "<?php echo site_url('ncvet/candidate/dashboard_candidate/validation_check_email_exist/0/1'); ?>", type: "post", data: { "enc_candidate_id": function() { return "<?php echo $enc_candidate_id; ?>"; } } } },
            // alt_email_id:{ maxlength:80, valid_email:true },
            id_proof_file:{ <?php if($form_data[0]['id_proof_file'] == "") { ?>required: true,<?php } ?> check_valid_file:true }, //use size in bytes //filesize_max: 1MB : 1000000
            aadhar_file:{ <?php if($form_data[0]['aadhar_file'] == "") { ?>required: true,<?php } ?> check_valid_file:true }, //use size in bytes //filesize_max: 1MB : 1000000 
            candidate_photo:{ <?php if($form_data[0]['candidate_photo'] == "") { ?>required: true,<?php } ?> check_valid_file:true }, //use size in bytes //filesize_max: 1MB : 1000000 
            candidate_sign:{ <?php if($form_data[0]['candidate_sign'] == "") { ?>required: true,<?php } ?> check_valid_file:true }, //use size in bytes //filesize_max: 1MB : 1000000
            declarationform:{ <?php if($form_data[0]['declarationform'] == "") { ?>required: declarationform_required_flag,<?php } ?> check_valid_file:is_valid_file }, //use size in bytes //filesize_max: 2MB : 2000000           
            qualification_certificate_file:{ <?php if($form_data[0]['qualification_certificate_file'] == "") { ?>required: qualification_certificate_file_required_flag,<?php } ?> check_valid_file:qualification_is_valid_file }, //use size in bytes //filesize_max: 2MB : 2000000
            exp_certificate:{ <?php if($form_data[0]['exp_certificate'] == "") { ?>required: exp_certificate_required_flag,<?php } ?> check_valid_file:exp_certificate_is_valid_file }, //use size in bytes //filesize_max: 2MB : 2000000
            institute_idproof:{ <?php if($form_data[0]['institute_idproof'] == "") { ?>required: institute_idproof_required_flag,<?php } ?> check_valid_file:institute_idproof_is_valid_file }, //use size in bytes //filesize_max: 2MB : 2000000
            vis_imp_cert_img:{ <?php if($form_data[0]['vis_imp_cert_img'] == "") { ?>required: vis_imp_cert_img_required_flag,<?php } ?> check_valid_file:vis_imp_cert_img_is_valid_file }, //use size in bytes //filesize_max: 2MB : 2000000
            orth_han_cert_img:{ <?php if($form_data[0]['orth_han_cert_img'] == "") { ?>required: orth_han_cert_img_required_flag,<?php } ?> check_valid_file:orth_han_cert_img_is_valid_file }, //use size in bytes //filesize_max: 2MB : 2000000
            cer_palsy_cert_img:{ <?php if($form_data[0]['cer_palsy_cert_img'] == "") { ?>required: cer_palsy_cert_img_required_flag,<?php } ?> check_valid_file:cer_palsy_cert_img_is_valid_file }, //use size in bytes //filesize_max: 2MB : 2000000           
          },
          messages:
          {
            salutation: { required: "Please select the Candidate Name (Salutation)." },
            first_name: { required: "Please enter the First Name.", pattern: "Please enter a valid First Name (only alphabets and spaces)." },
            middle_name: { pattern: "Please enter a valid Middle Name (only alphabets and spaces)." },
            last_name: { pattern: "Please enter a valid Last Name (only alphabets and spaces)." },
            gender: { required: "Please select the gender." },
            dob: { required: "Please select the date of birth", dateFormat:"Please enter the date of birth like yyyy-mm-dd" },
            guardian_salutation: { required: "Please select the Guardian Salutation." },
            guardian_name: { required: "Please enter the Guardian Name.", pattern: "Please enter a valid Guardian Name (only alphabets and spaces).", maxlength: "The Guardian Name field can not exceed 60 characters in length." },
            address1: { required: "Please enter the address line-1" },
            state: { required: "Please select the state." },
            city: { required: "Please select the city." },
            district: { required: "Please enter the district.", pattern: "Please enter a valid district." },
            pincode: { required: "Please enter the pincode.", remote: "Please enter valid pincode as per selected city." },
            address1_pr: { required: "Please enter the address line-1" },
            state_pr: { required: "Please select the state." },
            city_pr: { required: "Please select the city." },
            district_pr: { required: "Please enter the district.", pattern: "Please enter a valid district." },
            pincode_pr: { required: "Please enter the pincode.", remote: "Please enter valid pincode as per selected city." },
            mobile_no: { required: "Please enter the mobile number", minlength: "Please enter 10 numbers in mobile number", maxlength: "Please enter 10 numbers in mobile number" },
            otp_mobile: { required: "Please enter the mobile OTP.", minlength: "Please enter 6 numbers in otp", maxlength: "Please enter 6 numbers in otp" },
            email_id: { required: "Please enter the email id", valid_email: "Please enter the valid email id" },
            otp: { required: "Please enter the email OTP.", minlength: "Please enter 6 numbers in otp", maxlength: "Please enter 6 numbers in otp" },
            qualification: { required: "Please select the eligibility." },
            qualification_state:{ required: "Please select the working/institution state." },
            experience: { required: "Please select the experience." },
            collage: { required: "Please enter the college name.", maxlength: "The college name field can not exceed 160 characters in length." },
            post_graduation_sem: { required: "Please select the semester." },
            university: { required: "Please enter the university name.", maxlength: "The university name field can not exceed 75 characters in length." },
            graduation_sem: { required: "Please select the semester." },
            aadhar_no: { required: "Please enter the aadhar no.", remote: "The aadhar no is already exist." },
            id_proof_number: { required: "Please enter the aapar id.", remote: "The aapar id is already exist." },
            benchmark_disability:{ required: "Please select the benchmark disability." },
            visually_impaired:{ required: "Please select the visuallly imapaired." },
            orthopedically_handicapped:{ required: "Please select the orthopedically handicapped." },
            cerebral_palsy: { required: "Please select the cerebral palsy." },


            // alt_mobile_no: { minlength: "Please enter 10 numbers in alternate mobile number", maxlength: "Please enter 10 numbers in alternate mobile number" },
            // alt_email_id: { valid_email: "Please enter the valid alternate email id" },
            id_proof_file: { required: "Please upload the APAAR ID/ABC ID", valid_file_format:"Please upload only .jpg, .jpeg, .png files", filesize_min:"Please upload file greater than 75KB", filesize_max:"Please upload file less than 100KB" },
            aadhar_file: { required: "Please upload the Aadhar card", valid_file_format:"Please upload only .jpg, .jpeg, .png files", filesize_min:"Please upload file greater than 75KB", filesize_max:"Please upload file less than 100KB" },
            candidate_photo: { required: "Please upload the passport-size photo of the candidate", valid_file_format:"Please upload only .jpg, .jpeg, .png files", filesize_min:"Please upload file greater than 14KB", filesize_max:"Please upload file less than 20KB" },
            candidate_sign: { required: "Please upload the signature of the candidate", valid_file_format:"Please upload only .jpg, .jpeg, .png files", filesize_min:"Please upload file greater than 14KB", filesize_max:"Please upload file less than 20KB" },
            declarationform: { required: "Please upload the declaration of the candidate", valid_file_format:"Please upload only .jpg, .jpeg, .png, .pdf files", filesize_min:"Please upload file greater than 14KB", filesize_max:"Please upload file less than 20KB" },
            qualification_certificate_file: { required: "Please upload the qualification certificate of the candidate", valid_file_format:"Please upload only .jpg, .jpeg, .png, .pdf files", filesize_min:"Please upload file greater than 14KB", filesize_max:"Please upload file less than 20KB" },
            exp_certificate: { required: "Please upload the experience certificate of the candidate", valid_file_format:"Please upload only .jpg, .jpeg, .png, .pdf files", filesize_min:"Please upload file greater than 14KB", filesize_max:"Please upload file less than 20KB" },
            institute_idproof: { required: "Please upload the institutional id proof of the candidate", valid_file_format:"Please upload only .jpg, .jpeg, .png, .pdf files", filesize_min:"Please upload file greater than 14KB", filesize_max:"Please upload file less than 20KB" },
            vis_imp_cert_img: { required: "Please upload the visually impaired certificate of the candidate", valid_file_format:"Please upload only .jpg, .jpeg, .png, .pdf files", filesize_min:"Please upload file greater than 14KB", filesize_max:"Please upload file less than 20KB" },
            orth_han_cert_img: { required: "Please upload the orthopedically handicapped certificate of the candidate", valid_file_format:"Please upload only .jpg, .jpeg, .png, .pdf files", filesize_min:"Please upload file greater than 14KB", filesize_max:"Please upload file less than 20KB" },
            cer_palsy_cert_img: { required: "Please upload the cerebral palsy certificate of the candidate", valid_file_format:"Please upload only .jpg, .jpeg, .png, .pdf files", filesize_min:"Please upload file greater than 20KB", filesize_max:"Please upload file less than 20KB" },           
          }, 
          errorPlacement: function(error, element) // For replace error 
          {
            if (element.attr("name") == "salutation") { $("#salutation_err").next("label.error").remove(); error.insertAfter("#salutation_err"); }
            else if (element.attr("name") == "first_name") { $("#first_name_err").next("label.error").remove(); error.insertAfter("#first_name_err"); }
            else if (element.attr("name") == "middle_name") { $("#middle_name_err").next("label.error").remove(); error.insertAfter("#middle_name_err"); }
            else if (element.attr("name") == "last_name") { $("#last_name_err").next("label.error").remove(); error.insertAfter("#last_name_err"); }
            else if (element.attr("name") == "gender") { $("#gender_err").next("label.error").remove(); error.insertAfter("#gender_err"); }
            else if (element.attr("name") == "dob") { $("#dob_err").next("label.error").remove(); error.insertAfter("#dob_err"); }
            else if (element.attr("name") == "guardian_salutation") { $("#guardian_salutation_err").next("label.error").remove(); error.insertAfter("#guardian_salutation_err"); }
            else if (element.attr("name") == "guardian_name") { $("#guardian_name_err").next("label.error").remove(); error.insertAfter("#guardian_name_err"); }
            else if (element.attr("name") == "mobile_no") { $("#mobile_no_err").next("label.error").remove(); error.insertAfter("#mobile_no_err"); }
            else if (element.attr("name") == "otp_mobile") { $("#otp_mobile_err").next("label.error").remove(); error.insertAfter("#otp_mobile_err"); }
            else if (element.attr("name") == "otp") { $("#otp_email_err").next("label.error").remove(); error.insertAfter("#otp_email_err"); }
            else if (element.attr("name") == "email_id") { $("#email_id_err").next("label.error").remove(); error.insertAfter("#email_id_err"); }
            else if (element.attr("name") == "address1") { $("#address1_err").next("label.error").remove(); error.insertAfter("#address1_err"); }
            else if (element.attr("name") == "state") { $("#state_err").next("label.error").remove(); error.insertAfter("#state_err"); }
            else if (element.attr("name") == "city") { $("#city_err").next("label.error").remove(); error.insertAfter("#city_err"); }
            else if (element.attr("name") == "district") { $("#district_err").next("label.error").remove(); error.insertAfter("#district_err"); }
            else if (element.attr("name") == "pincode") { $("#pincode_err").next("label.error").remove(); error.insertAfter("#pincode_err"); }

            else if (element.attr("name") == "address1_pr") { $("#address1_pr_err").next("label.error").remove(); error.insertAfter("#address1_pr_err"); }
            else if (element.attr("name") == "state_pr") { $("#state_pr_err").next("label.error").remove(); error.insertAfter("#state_pr_err"); }
            else if (element.attr("name") == "city_pr") { $("#city_pr_err").next("label.error").remove(); error.insertAfter("#city_pr_err"); }
            else if (element.attr("name") == "district_pr") { $("#district_pr_err").next("label.error").remove(); error.insertAfter("#district_pr_err"); }
            else if (element.attr("name") == "pincode_pr") { $("#pincode_pr_err").next("label.error").remove(); error.insertAfter("#pincode_pr_err"); }
            else if (element.attr("name") == "qualification") { $("#qualification_err").next("label.error").remove(); error.insertAfter("#qualification_err"); }
            else if (element.attr("name") == "qualification_state") { $("#qualification_state_err").next("label.error").remove(); error.insertAfter("#qualification_state_err"); }
            else if (element.attr("name") == "experience") { $("#experience_err").next("label.error").remove(); error.insertAfter("#experience_err"); }
            else if (element.attr("name") == "collage") { $("#collage_err").next("label.error").remove(); error.insertAfter("#collage_err"); }
            else if (element.attr("name") == "post_graduation_sem") { $("#post_graduation_sem_err").next("label.error").remove(); error.insertAfter("#post_graduation_sem_err"); }
            else if (element.attr("name") == "university") { $("#university_err").next("label.error").remove(); error.insertAfter("#university_err"); }
            else if (element.attr("name") == "graduation_sem") { $("#graduation_sem_err").next("label.error").remove(); error.insertAfter("#graduation_sem_err"); }
            else if (element.attr("name") == "aadhar_no") { $("#aadhar_no_err").next("label.error").remove(); error.insertAfter("#aadhar_no_err"); }
            else if (element.attr("name") == "id_proof_number") { $("#id_proof_number_err").next("label.error").remove(); error.insertAfter("#id_proof_number_err"); }
            else if (element.attr("name") == "benchmark_disability") { $("#benchmark_disability_err").next("label.error").remove(); error.insertAfter("#benchmark_disability_err"); }
            else if (element.attr("name") == "visually_impaired") { $("#visually_impaired_err").next("label.error").remove(); error.insertAfter("#visually_impaired_err"); }
            else if (element.attr("name") == "orthopedically_handicapped") { $("#orthopedically_handicapped_err").next("label.error").remove(); error.insertAfter("#orthopedically_handicapped_err"); }
            else if (element.attr("name") == "cerebral_palsy") { $("#cerebral_palsy_err").next("label.error").remove(); error.insertAfter("#cerebral_palsy_err"); }

            // else if (element.attr("name") == "alt_email_id") { error.insertAfter("#alt_email_id_err"); }
            else if (element.attr("name") == "id_proof_file") { $("#id_proof_file_err").next("label.error").remove(); error.insertAfter("#id_proof_file_err"); }
            else if (element.attr("name") == "aadhar_file") { $("#aadhar_file_err").next("label.error").remove();  error.insertAfter("#aadhar_file_err");  }
            else if (element.attr("name") == "candidate_photo") { $("#candidate_photo_err").next("label.error").remove(); error.insertAfter("#candidate_photo_err"); }
            else if (element.attr("name") == "candidate_sign") { $("#candidate_sign_err").next("label.error").remove(); error.insertAfter("#candidate_sign_err"); }
            else if (element.attr("name") == "declarationform") { $("#declarationform_err").next("label.error").remove(); error.insertAfter("#declarationform_err"); }
            else if (element.attr("name") == "qualification_certificate_file") { $("#qualification_certificate_file_err").next("label.error").remove(); error.insertAfter("#qualification_certificate_file_err"); }
            else if (element.attr("name") == "exp_certificate") { $("#exp_certificate_err").next("label.error").remove(); error.insertAfter("#exp_certificate_err"); }
            else if (element.attr("name") == "institute_idproof") { $("#institute_idproof_err").next("label.error").remove();  error.insertAfter("#institute_idproof_err"); }
            else if (element.attr("name") == "vis_imp_cert_img") { $("#vis_imp_cert_img_err").next("label.error").remove();  error.insertAfter("#vis_imp_cert_img_err"); }
            else if (element.attr("name") == "orth_han_cert_img") { $("#orth_han_cert_img_err").next("label.error").remove();  error.insertAfter("#orth_han_cert_img_err"); }
            else if (element.attr("name") == "cer_palsy_cert_img") { $("#cer_palsy_cert_img_err").next("label.error").remove();  error.insertAfter("#cer_palsy_cert_img_err"); }
            else { error.insertAfter(element); }

            setTimeout(function() {error.removeAttr("for"); }, 100);

          },
          success: function(label, element) {
              // Hide error when valid
              $(element).closest(".form-group").find("note.form_note").empty();
          },          
          submitHandler: function(form) 
          {
            let field_change_flag = 0;

            // let org_mobile_no = "<?php echo $form_data[0]['mobile_no'] ?>";        
            // if(org_mobile_no != $("#mobile_no").val().trim()) { field_change_flag = 1;}

            // let org_alt_mobile_no = "<?php echo $form_data[0]['alt_mobile_no'] ?>";        
            // if(org_alt_mobile_no != $("#alt_mobile_no").val().trim()) { field_change_flag = 1;}

            // let org_email_id = "<?php echo $form_data[0]['email_id'] ?>";        
            // if(org_email_id != $("#email_id").val().trim()) { field_change_flag = 1;}

            // let org_alt_email_id = "<?php echo $form_data[0]['alt_email_id'] ?>";        
            // if(org_alt_email_id != $("#alt_email_id").val().trim()) { field_change_flag = 1;}

            let org_address1 = "<?php echo $form_data[0]['address1'] ?>";       
            if(org_address1 != $("#address1").val().trim()) { field_change_flag = 1;}

            let org_address2 = "<?php echo $form_data[0]['address2'] ?>";       
            if(org_address2 != $("#address2").val().trim()) { field_change_flag = 1;}

            let org_address3 = "<?php echo $form_data[0]['address3'] ?>";       
            if(org_address3 != $("#address3").val().trim()) { field_change_flag = 1;}

            let org_state = "<?php echo $form_data[0]['state'] ?>";       
            if(org_state != $("#state").val().trim()) { field_change_flag = 1;}

            let org_city = "<?php echo $form_data[0]['city'] ?>";       
            if(org_city != $("#city").val().trim()) { field_change_flag = 1;}

            let org_district = "<?php echo $form_data[0]['district'] ?>";       
            if(org_district != $("#district").val().trim()) { field_change_flag = 1;}

            let org_pincode = "<?php echo $form_data[0]['pincode'] ?>";       
            if(org_pincode != $("#pincode").val().trim()) { field_change_flag = 1;}
            
            let disp_id_proof_file_upload_option = "<?php echo $form_data[0]['id_proof_file']; ?>";
            if(disp_id_proof_file_upload_option == "")
            {
              let org_id_proof_file_cropper = "<?php echo $form_data[0]['id_proof_file'] ?>";       
              if(org_id_proof_file_cropper != $("#id_proof_file_cropper").val().trim()) { field_change_flag = 1;}
            }

            let disp_aadhar_file_upload_option = "<?php echo $form_data[0]['aadhar_file']; ?>";
            if(disp_aadhar_file_upload_option == "")
            {
              let org_aadhar_file_cropper = "<?php echo $form_data[0]['aadhar_file'] ?>";       
              if(org_aadhar_file_cropper != $("#aadhar_file_cropper").val().trim()) { field_change_flag = 1;}
            }
            
            let disp_candidate_photo_upload_option = "<?php echo $form_data[0]['candidate_photo']; ?>";
            if(disp_candidate_photo_upload_option == "")
            {
              let org_candidate_photo_cropper = "<?php echo $form_data[0]['candidate_photo'] ?>";       
              if(org_candidate_photo_cropper != $("#candidate_photo_cropper").val().trim()) { field_change_flag = 1;}
            }

            let disp_candidate_sign_upload_option = "<?php echo $form_data[0]['candidate_sign']; ?>";
            if(disp_candidate_sign_upload_option == "")
            {
              let org_candidate_sign_cropper = "<?php echo $form_data[0]['candidate_sign'] ?>";       
              if(org_candidate_sign_cropper != $("#candidate_sign_cropper").val().trim()) { field_change_flag = 1;}
            }

            if(qualification_eligibility == '1' || qualification_eligibility == '2') 
            {
              let disp_qualification_certificate_file_upload_option = "<?php echo $form_data[0]['qualification_certificate_file']; ?>";
              if(disp_qualification_certificate_file_upload_option == "")
              {
                let org_qualification_certificate_file_cropper = "<?php echo $form_data[0]['qualification_certificate_file'] ?>";       
                if(org_qualification_certificate_file_cropper != $("#qualification_certificate_file_cropper").val().trim()) { field_change_flag = 1;}
              }
            }
              
            if(qualification_eligibility == '3' || qualification_eligibility == '4') 
            {
              let disp_declarationform_upload_option = "<?php echo $form_data[0]['declarationform']; ?>";
              if(disp_declarationform_upload_option == "")
              {
                let org_declarationform_cropper = "<?php echo $form_data[0]['declarationform'] ?>";       
                if(org_declarationform_cropper != $("#declarationform_cropper").val().trim()) { field_change_flag = 1;}
              }
            }

            if(qualification_eligibility == '3' || qualification_eligibility == '4') 
            {
              let disp_institute_idproof_upload_option = "<?php echo $form_data[0]['institute_idproof']; ?>";
              if(disp_institute_idproof_upload_option == "")
              {
                let org_institute_idproof_cropper = "<?php echo $form_data[0]['institute_idproof'] ?>";       
                if(org_institute_idproof_cropper != $("#institute_idproof_cropper").val().trim()) { field_change_flag = 1;}
              }
            }
            
            if(qualification_eligibility == '1') 
            {
              let disp_exp_certificate_upload_option = "<?php echo $form_data[0]['exp_certificate']; ?>";
              if(disp_exp_certificate_upload_option == "")
              {
                let org_exp_certificate_cropper = "<?php echo $form_data[0]['exp_certificate'] ?>";       
                if(org_exp_certificate_cropper != $("#exp_certificate_cropper").val().trim()) { field_change_flag = 1;}
              }
            }

            if(benchmark_disability == 'Y' && visually_impaired == 'Y') 
            {
              let disp_vis_imp_cert_img_upload_option = "<?php echo $form_data[0]['vis_imp_cert_img']; ?>";
              if(disp_vis_imp_cert_img_upload_option == "")
              {
                let org_vis_imp_cert_img_cropper = "<?php echo $form_data[0]['vis_imp_cert_img'] ?>";       
                if(org_vis_imp_cert_img_cropper != $("#vis_imp_cert_img_cropper").val().trim()) { field_change_flag = 1;}
              }
            }

            if(benchmark_disability == 'Y' && orthopedically_handicapped == 'Y') 
            {
              let disp_orth_han_cert_img_upload_option = "<?php echo $form_data[0]['orth_han_cert_img']; ?>";
              if(disp_orth_han_cert_img_upload_option == "")
              {
                let org_orth_han_cert_img_cropper = "<?php echo $form_data[0]['orth_han_cert_img'] ?>";       
                if(org_orth_han_cert_img_cropper != $("#orth_han_cert_img_cropper").val().trim()) { field_change_flag = 1;}
              }
            }

            if(benchmark_disability == 'Y' && cerebral_palsy == 'Y') 
            {
              let disp_cer_palsy_cert_img_upload_option = "<?php echo $form_data[0]['cer_palsy_cert_img']; ?>";
              if(disp_cer_palsy_cert_img_upload_option == "")
              {
                let org_cer_palsy_cert_img_cropper = "<?php echo $form_data[0]['cer_palsy_cert_img'] ?>";       
                if(org_cer_palsy_cert_img_cropper != $("#cer_palsy_cert_img_cropper").val().trim()) { field_change_flag = 1;}
              }
            }

            var emailPermission   = "<?php echo $emailPermission; ?>";
            var mobilePermission  = "<?php echo $mobilePermission; ?>";
            var checkFlag         = "<?php echo $checkFlag; ?>";
            var dbSalutation      = "<?php echo $form_data[0]['salutation']; ?>";
            var isValidGenderFlag = true;
            
            if(checkFlag == 'Yes')
            {
              if ($('#salutation').val() == dbSalutation) {
                var isValidGenderFlag = isValidGender(dbSalutation);
              } else {
                var isValidGenderFlag = isValidGender($('#salutation').val());
              }  
            }
            
            
            if(emailVerify && mobileVerify && isValidGenderFlag)
            { 
              $("#page_loader").hide();
              swal({ title: "Please confirm", text: "Please confirm to submit the details", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
                {
                  if (emailPermission == 'yes') {
                    $('#email_id').removeAttr('readonly').removeAttr('disabled');
                  }
                  
                  if (mobilePermission == 'yes') {
                    $('#mobile_no').removeAttr('readonly').removeAttr('disabled');
                  }
                  
                  $("#page_loader").show();
                  form.submit();
                });
            } 
            else 
            {
              if ( !emailVerify)
              {
                sweet_alert_only_alert("Please verify the email id.");
                $('#email_id').focus();
              }
              else if ( !mobileVerify ) 
              {
                sweet_alert_only_alert("Please verify the mobile no.");
                $('#mobile_no').focus();
              }
              else if ( !isValidGenderFlag ) 
              {
                sweet_alert_only_alert("Invalid gender selected");
                $('#salutation').focus();
              }
            }  

            // if(field_change_flag === 1)
            // {
            //   $("#page_loader").hide();
            //   swal({ title: "Please confirm", text: "Please confirm to submit the details", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
            //   { 
            //     $("#page_loader").show();
            //     form.submit();
            //   });   
            // }
            // else
            // {
            //   sweet_alert_only_alert("Please update at least one field");
            // }            
          }
        });
      });
      //END : JQUERY VALIDATION SCRIPT
    </script>
    <?php $this->load->view('ncvet/common/inc_bottom_script'); ?>
  </body>
</html>