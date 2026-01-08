<!DOCTYPE html>
<html>

<head>
  <?php $this->load->view('ncvet/inc_header'); ?>
</head>

<body class="gray-bg">
  <?php $this->load->view('ncvet/common/inc_loader'); ?>
  <div class="d-flex logo" style="z-index:1;"><img src="<?php echo base_url('assets/ncvet/images/iibf_logo.png'); ?>" class="img-fluid" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">
    <h3 class="mb-0" style="    font-size: 20px;">INDIAN INSTITUTE OF BANKING & FINANCE <br>
      ISO 21001:2018 Certified</h3>
  </div>
  <div class="container">
    <?php $mode = 'Add';  ?>

    <div class="admin_login_form animated fadeInDown" style="width: 100%; max-width: none; margin-top:110px">
      <form method="post" action="<?php echo site_url('ncvet/candidate_registration/addmember'); ?>" id="add_candidate_form" enctype="multipart/form-data" autocomplete="off">
        <h3 style="text-align: center;margin-bottom: 2%;" class="col-xl-12 col-lg-12">Preview Form</h3>
        <h4 class="custom_form_title" style="margin: -15px -20px 15px -20px !important;">Basic Details</h4>

        <?php

        $salutation_master_arr = array('Mr.', 'Mrs.', 'Ms.');
        $guardian_salutation_master_arr = array('Mr.', 'Mrs.');

        $qualification_arr = $this->config->item('ncvet_qualification_arr');
        $graduation_sem_arr = $this->config->item('ncvet_graduation_sem_arr');

        $post_graduation_sem_arr = $this->config->item('ncvet_post_graduation_sem_arr');

        ?>
        <div class="row">


          <div class="col-xl-3 col-lg-3">
            <div class="form-group">
              <?php $chk_salutation = $candidate_data['salutation']; ?>
              <label class="form_label">Candidate Name (Salutation) <!-- <sup class="text-danger">*</sup> --></label>

              <?php $disp_salution = '';
              if (count($salutation_master_arr) > 0) {
                foreach ($salutation_master_arr as $sal_val) {
                  if ($chk_salutation == $sal_val) {
                    $disp_salution = $sal_val;
                  }
                }
              } ?>
              <input type="text" value="<?php echo $disp_salution; ?>" class="form-control" readonly disabled />
            </div>
          </div>

          <div class="col-xl-3 col-lg-3">
            <div class="form-group">
              <label class="form_label">First Name <!-- <sup class="text-danger">*</sup> --></label>
              <input type="text" value="<?php echo $candidate_data['first_name']; ?>" class="form-control" readonly disabled />
            </div>
          </div>

          <div class="col-xl-3 col-lg-3">
            <div class="form-group">
              <label class="form_label">Middle Name <!-- <sup class="text-danger">*</sup> --></label>
              <input type="text" value="<?php echo $candidate_data['middle_name']; ?>" class="form-control" readonly disabled />
            </div>
          </div>

          <div class="col-xl-3 col-lg-3">
            <div class="form-group">
              <label class="form_label">Last Name <!-- <sup class="text-danger">*</sup> --></label>
              <input type="text" value="<?php echo $candidate_data['last_name']; ?>" class="form-control" readonly disabled />
            </div>
          </div>
          <div class="col-xl-3 col-lg-3">
            <div class="form-group">
              <label class="form_label">Gender <!-- <sup class="text-danger">*</sup> --></label>
              <input type="text" value="<?php if ($candidate_data['gender'] == '1') {
                                          echo 'Male';
                                        } else if ($candidate_data['gender'] == '2') {
                                          echo 'Female';
                                        } ?>" class="form-control" readonly disabled />
            </div>
          </div>

          <div class="col-xl-9 col-lg-9">
            <div class="form-group">
              <label class="form_label">Full Name <!-- <sup class="text-danger">*</sup> --></label>
              <input type="text" value="<?php echo strtoupper($candidate_data['first_name']); ?> <?php echo strtoupper($candidate_data['middle_name']); ?> <?php echo strtoupper($candidate_data['last_name']); ?>" class="form-control" readonly disabled />
            </div>
          </div>

          <div class="col-xl-3 col-lg-3">
            <div class="form-group">
              <?php $chk_guardian_salutation = $candidate_data['guardian_salutation']; ?>
              <label class="form_label">Guardian Name (Salutation) <!-- <sup class="text-danger">*</sup> --></label>

              <?php $disp_salution = '';
              if (count($salutation_master_arr) > 0) {
                foreach ($salutation_master_arr as $sal_val) {
                  if ($chk_guardian_salutation == $sal_val) {
                    $disp_salution = $sal_val;
                  }
                }
              } ?>
              <input type="text" value="<?php echo $disp_salution; ?>" class="form-control" readonly disabled />
            </div>
          </div>

          <div class="col-xl-9 col-lg-9">
            <div class="form-group">
              <label class="form_label">Guardian Full Name <!-- <sup class="text-danger">*</sup> --></label>
              <input type="text" value="<?php echo $candidate_data['guardian_name']; ?>" class="form-control" readonly disabled />
            </div>
          </div>

          <div class="col-xl-3 col-lg-3">
            <div class="form-group">
              <label class="form_label">Date of Birth <!-- <sup class="text-danger">*</sup> --></label>
              <input type="text" value="<?php if ($candidate_data['dob'] != '0000-00-00') {
                                          echo $candidate_data['dob'];
                                        } ?>" class="form-control" readonly disabled />
            </div>
          </div>

          <?php
          if ($candidate_data['dob'] != '0000-00-00') {
            $dob = new DateTime($candidate_data['dob']);
            $today = new DateTime();
            $age = $today->diff($dob)->y;
          }
          ?>

          <div class="col-xl-2 col-lg-2">
            <div class="form-group">
              <label class="form_label">Age <!-- <sup class="text-danger">*</sup> --></label>
              <input type="text" value="<?php echo $age; ?>" class="form-control" readonly disabled />
            </div>
          </div>



          <div class="col-xl-3 col-lg-3">
            <div class="form-group">
              <label for="mobile_no" class="form_label">Mobile Number <!-- <sup class="text-danger">*</sup> --> </label>
              <input type="text" name="mobile_no" id="mobile_no" value="<?php echo $candidate_data['mobile_no']; ?>" placeholder="Mobile Number *" class="form-control custom_input allow_only_numbers basic_form" readonly disabled maxlength="10" minlength="10" />

              <?php if (form_error('mobile_no') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('mobile_no'); ?></label> <?php } ?>
            </div>
          </div>



          <div class="col-xl-4 col-lg-4">
            <div class="form-group">
              <label for="email_id" class="form_label">Email id <!-- <sup class="text-danger">*</sup> --></label>
              <input type="text" name="email_id" id="email_id" value="<?php echo $candidate_data['email_id']; ?>" placeholder="Email id *" class="form-control custom_input basic_form" readonly disabled maxlength="80" />
              <note class="form_note" id="email_id_err"></note>

              <?php if (form_error('email_id') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('email_id'); ?></label> <?php } ?>
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
              <label for="address1" class="form_label">Address Line-1<!-- <sup class="text-danger">*</sup> --></label>
              <input type="text" name="address1" id="address1" placeholder="Address Line-1 *" class="form-control custom_input ignore_required" maxlength="75" readonly disabled value="<?php echo $candidate_data['address1']; ?>" />

              <note class="form_note" id="address1_err">Note: Please enter only 75 characters</note>

              <?php if (form_error('address1') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address1'); ?></label> <?php } ?>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label for="address1_pr" class="form_label">Address Line-1<!-- <sup class="text-danger">*</sup> --></label>
              <input type="text" name="address1_pr" id="address1_pr" placeholder="Address Line-1 *" class="form-control custom_input ignore_required" maxlength="75" readonly disabled value="<?php echo $candidate_data['address1_pr']; ?>" />

              <note class="form_note" id="address1_pr_err">Note: Please enter only 75 characters</note>

              <?php if (form_error('address1_pr') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address1_pr'); ?></label> <?php } ?>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label for="address2" class="form_label">Address Line-2 <!-- <sup class="text-danger">*</sup> --></label>
              <input type="text" name="address2" id="address2" placeholder="Address Line-2" class="form-control custom_input" maxlength="75" value="<?php echo $candidate_data['address2']; ?>" readonly disabled />

              <note class="form_note" id="address2_err">Note: Please enter only 75 characters</note>

              <?php if (form_error('address2') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address2'); ?></label> <?php } ?>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label for="address2_pr" class="form_label">Address Line-2 <!-- <sup class="text-danger">*</sup> --></label>
              <input type="text" name="address2_pr" id="address2_pr" placeholder="Address Line-2" class="form-control custom_input" maxlength="75" value="<?php echo $candidate_data['address2_pr']; ?>" readonly disabled />

              <note class="form_note" id="address2_pr_err">Note: Please enter only 75 characters</note>

              <?php if (form_error('address2_pr') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address2_pr'); ?></label> <?php } ?>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label for="address3" class="form_label">Address Line-3 <!-- <sup class="text-danger">*</sup> --></label>
              <input type="text" name="address3" id="address3" placeholder="Address Line-3" class="form-control custom_input" readonly disabled maxlength="75" value="<?php echo $candidate_data['address3']; ?>" />

              <note class="form_note" id="address3_err">Note: Please enter only 75 characters</note>

              <?php if (form_error('address3') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address3'); ?></label> <?php } ?>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label for="address3_pr" class="form_label">Address Line-3 <!-- <sup class="text-danger">*</sup> --></label>
              <input type="text" name="address3_pr" id="address3_pr" placeholder="Address Line-3" class="form-control custom_input" readonly disabled maxlength="75" value="<?php echo $candidate_data['address3_pr']; ?>" />

              <note class="form_note" id="address3_pr_err">Note: Please enter only 75 characters</note>

              <?php if (form_error('address3_pr') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address3_pr'); ?></label> <?php } ?>
            </div>
          </div>

          <?php $chk_state = $candidate_data['state']; ?>
          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label for="state" class="form_label"> State <!-- <sup class="text-danger">*</sup> --></label>

              <?php if (count($state_master_data) > 0) { ?>

                <?php foreach ($state_master_data as $state_res) {
                  if ($chk_state == $state_res['state_code']) { ?>

                    <input type="text" name="state" id="state" placeholder="S" class="form-control custom_input" readonly disabled maxlength="75" value="<?php if ($chk_state == $state_res['state_code']) echo $state_res['state_name']; ?>" />
              <?php }
                }
              }
              ?>

              <span id="state_err"></span>
              <?php if (form_error('state') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('state'); ?></label> <?php } ?>
            </div>
          </div>

          <?php $chk_state = $candidate_data['state_pr']; ?>
          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label for="state_pr" class="form_label"> State <!-- <sup class="text-danger">*</sup> --></label>

              <?php if (count($state_master_data) > 0) { ?>

                <?php foreach ($state_master_data as $state_res) {
                  if ($chk_state == $state_res['state_code']) { ?>

                    <input type="text" name="state" id="state" placeholder="S" class="form-control custom_input" readonly disabled maxlength="75" value="<?php echo $state_res['state_name']; ?>" />
              <?php }
                }
              }
              ?>


              <span id="state_pr_err"></span>
              <?php if (form_error('state_pr') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('state_pr'); ?></label> <?php } ?>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label for="city" class="form_label">City <!-- <sup class="text-danger">*</sup> --></label>
              <div id="city_outer">

                <?php $selected_state_val = '';
                $selected_state_val = $candidate_data['state'];

                if ($selected_state_val != "") {
                  $city_data = $this->master_model->getRecords('city_master', array('state_code' => $selected_state_val, 'city_delete' => '0'), 'id, city_name', array('city_name' => 'ASC'));

                  if (count($city_data) > 0) { ?>

                    <?php foreach ($city_data as $city) {
                      if ($candidate_data['city'] == $city['id']) { ?>
                        <input type="text" name="city" id="city" placeholder="S" class="form-control custom_input" readonly disabled maxlength="75" value="<?php echo $city['city_name']; ?>" />

                <?php }
                    }
                  }
                }
                ?>
              </div>

              <span id="city_err"></span>

              <?php if (form_error('city') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('city'); ?></label> <?php } ?>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label for="city_pr" class="form_label">City <!-- <sup class="text-danger">*</sup> --></label>
              <div id="city_pr_outer">

                <?php $selected_state_val = '';
                $selected_state_val = $candidate_data['state'];

                if ($selected_state_val != "") {
                  $city_data = $this->master_model->getRecords('city_master', array('state_code' => $selected_state_val, 'city_delete' => '0'), 'id, city_name', array('city_name' => 'ASC'));

                  if (count($city_data) > 0) { ?>

                    <?php foreach ($city_data as $city) {
                      if ($candidate_data['city_pr'] == $city['id']) {  ?>

                        <input type="text" name="city_pr" id="city_pr" placeholder="" class="form-control custom_input" readonly disabled maxlength="75" value="<?php echo $city['city_name']; ?>" />
                <?php }
                    }
                  }
                }
                ?>
              </div>

              <span id="city_pr_err"></span>

              <?php if (form_error('city_pr') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('city_pr'); ?></label> <?php } ?>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label for="district" class="form_label">District <!-- <sup class="text-danger">*</sup> --></label>
              <input type="text" name="district" id="district" value="<?php echo $candidate_data['district']; ?>" placeholder="District *" class="form-control custom_input allow_only_alphabets_and_numbers_and_space ignore_required" maxlength="30" readonly disabled />
              <note class="form_note" id="district_err">Note: Please enter only 30 characters</note>

              <?php if (form_error('district') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('district'); ?></label> <?php } ?>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label for="district_pr" class="form_label">District <!-- <sup class="text-danger">*</sup> --></label>
              <input type="text" name="district_pr" id="district_pr" value="<?php echo $candidate_data['district_pr']; ?>" placeholder="District *" class="form-control custom_input allow_only_alphabets_and_numbers_and_space ignore_required" maxlength="30" readonly disabled />
              <note class="form_note" id="district_pr_err">Note: Please enter only 30 characters</note>

              <?php if (form_error('district_pr') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('district_pr'); ?></label> <?php } ?>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label for="pincode" class="form_label">Pincode <!-- <sup class="text-danger">*</sup> --></label>
              <input type="text" name="pincode" id="pincode" value="<?php echo $candidate_data['pincode']; ?>" placeholder="Pincode *" class="form-control custom_input allow_only_numbers ignore_required" readonly disabled maxlength="6" minlength="6" />

              <?php if (form_error('pincode') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('pincode'); ?></label> <?php } ?>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label for="pincode_pr" class="form_label">Pincode <!-- <sup class="text-danger">*</sup> --></label>
              <input type="text" name="pincode_pr" id="pincode_pr" value="<?php echo $candidate_data['pincode_pr']; ?>" placeholder="Pincode *" class="form-control custom_input allow_only_numbers ignore_required" readonly disabled maxlength="6" minlength="6" />

              <?php if (form_error('pincode_pr') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('pincode_pr'); ?></label> <?php } ?>
            </div>
          </div>
        </div>

        <h4 class="custom_form_title" style="margin: 20px -20px 15px -20px !important;">Other Details</h4>
        <div class="row">
          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label class="form_label">Eligibility <!-- <sup class="text-danger">*</sup> --></label>
              <div id="qualification_err">
                <textarea type="text" class="form-control" readonly disabled><?php echo $qualification_arr[$candidate_data['qualification']]; ?></textarea>
              </div>
            </div>
          </div>
          

          <?php if ($candidate_data['qualification'] == 1) { ?>
            <div class="col-xl-6 col-lg-6">
              <div class="form-group">
                <label class="form_label">Experience More than 1.5 Year in BFSI <!-- <sup class="text-danger">*</sup> --></label>
                <div id="experience_err">
                  <input type="text" value="<?php echo $candidate_data['experience'] == 'Y' ? 'Yes' : 'No'; ?>" class="form-control" readonly disabled />
                </div>
              </div>
            </div>
          <?php } ?>

          <?php if ($candidate_data['qualification'] == 3 || $candidate_data['qualification'] == 4) { ?>
            <div class="col-xl-6 col-lg-6">
              <div class="form-group">
                <label class="form_label">Semester <!-- <sup class="text-danger">*</sup> --></label>
                <div id="semester_err">
                  <input type="text" value="<?php echo $candidate_data['semester']; ?>" class="form-control" readonly disabled />
                </div>
              </div>
            </div>

            <div class="col-xl-6 col-lg-6">
              <div class="form-group">
                <label class="form_label">Name of the College/Academic Institution <!-- <sup class="text-danger">*</sup> --></label>
                <div id="collage_err">
                  <input type="text" value="<?php echo $candidate_data['collage']; ?>" class="form-control" readonly disabled />
                </div>
              </div>
            </div>

            <div class="col-xl-6 col-lg-6">
              <div class="form-group">
                <label class="form_label">Name of the University <!-- <sup class="text-danger">*</sup> --></label>
                <div id="university_err">
                  <input type="text" value="<?php echo $candidate_data['university']; ?>" class="form-control" readonly disabled />
                </div>
              </div>
            </div>
          <?php } ?>
          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label class="form_label">State <!-- <sup class="text-danger">*</sup> --></label>
              <div id="qualification_err">
                <?php $chk_state = $candidate_data['qualification_state']; 
                 
                ?>
                <?php if (count($state_master_data) > 0) { ?>

                <?php foreach ($state_master_data as $state_res) {
                  if ($chk_state == $state_res['state_code']) { ?>

                    <input type="text" name="qualification_state" id="qualification_state" placeholder="S" class="form-control custom_input" readonly disabled maxlength="75" value="<?php echo $state_res['state_name']; ?>" />
                <?php } 
                  }
                }
                ?>
              </div>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label class="form_label">Aadhar Card Number <!-- <sup class="text-danger">*</sup> --></label>
              <div id="qualification_err">
                <input type="text" value="<?php echo $candidate_data['aadhar_no']; ?>" class="form-control" readonly disabled />
              </div>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label class="form_label">APAAR ID/ABC ID <!-- <sup class="text-danger">*</sup> --></label>
              <div id="qualification_err">
                <input type="text" value="<?php echo $candidate_data['id_proof_number']; ?>" class="form-control" readonly disabled />
              </div>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label class="form_label">Person with Benchmark Disability <!-- <sup class="text-danger">*</sup> --></label>
              <div id="qualification_err">
                <input type="text" value="<?php echo $candidate_data['benchmark_disability'] == 'Y' ? 'Yes' : 'No'; ?>" class="form-control" readonly disabled />
              </div>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label class="form_label">Visually impaired <!-- <sup class="text-danger">*</sup> --></label>
              <div id="qualification_err">
                <input type="text" value="<?php echo $candidate_data['visually_impaired'] == 'Y' ? 'Yes' : 'No'; ?>" class="form-control" readonly disabled />
              </div>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label class="form_label">Orthopedically Handicapped <!-- <sup class="text-danger">*</sup> --></label>
              <div id="qualification_err">
                <input type="text" value="<?php echo $candidate_data['orthopedically_handicapped'] == 'Y' ? 'Yes' : 'No'; ?>" class="form-control" readonly disabled />
              </div>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <label class="form_label">Cerebral Palsy <!-- <sup class="text-danger">*</sup> --></label>
              <div id="qualification_err">
                <input type="text" value="<?php echo $candidate_data['cerebral_palsy'] == 'Y' ? 'Yes' : 'No'; ?>" class="form-control" readonly disabled />
              </div>
            </div>
          </div>

        </div>


        <h4 class="custom_form_title" style="margin: 20px -20px 15px -20px !important;">Upload Documents</h4>
        <div class="row">
          <?php
          $preview_candidate_id = $candidate_data['candidate_id'];
          $preview_first_name   = $candidate_data['first_name'];
          $preview_training_id  = $candidate_data['training_id'];
          ?>

          <?php if ($candidate_data['qualification'] == '1' || $candidate_data['qualification'] == '2') { ?>
            <div class="col-xl-6 col-lg-6">
              <div class="form-group">
                <div class="img_preview_input_outer">
                  <label class="form_label">Uploaded Qualification Certificate <!-- <sup class="text-danger">*</sup> --></label>
                </div>
                <?php
                $preview_qualification_certificate_file = '';
                if ($candidate_data['qualification_certificate_file'] != "") {
                  $preview_qualification_certificate_file = $candidate_data['qualification_certificate_file'];
                  $preview_qualification_certificate_file = base_url($qualification_certificate_file_path . '/' . $preview_qualification_certificate_file);

                  $pathInfo = pathinfo($preview_qualification_certificate_file);
                  $extension = isset($pathInfo['extension']) ? $pathInfo['extension'] : '';
                }

                ?>
                <?php

                if ($preview_qualification_certificate_file != "" && $extension != 'pdf') { ?>
                  <div id="qualification_certificate_file_preview" class="upload_img_preview">

                    <a href="<?php echo $preview_qualification_certificate_file . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Qualification Certificate - ' . $preview_first_name;
                                                                                                                                                                        echo $preview_training_id != "" ? " (" . $preview_training_id . ")" : ""; ?>">
                      <img src="<?php echo $preview_qualification_certificate_file . "?" . time(); ?>">

                    </a>

                  </div>
                <?php } else if ($preview_qualification_certificate_file != "" && $extension == 'pdf') {
                ?>
                  <div id="qualification_certificate_file_preview">

                    <a target="_blank" href="<?php echo $preview_qualification_certificate_file . "?" . time(); ?>" data-title="<?php echo 'Qualification Certificate - ' . $preview_first_name;
                                                                                                                            echo $preview_training_id != "" ? " (" . $preview_training_id . ")" : ""; ?>">
                      <i>View File</i>

                    </a>

                  </div>
                <?php
                } else {
                  echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                } ?>
                <div class="clearfix"></div>
              </div>
            </div>
          <?php } ?>

          <?php if ($candidate_data['qualification'] == '1') { ?>
            <div class="col-xl-6 col-lg-6">
              <div class="form-group">
                <div class="img_preview_input_outer">
                  <label class="form_label">Uploaded Experience Certificate <!-- <sup class="text-danger">*</sup> --></label>
                </div>

                <?php
                $preview_exp_certificate = '';
                if ($candidate_data['exp_certificate'] != "") {
                  $preview_exp_certificate = $candidate_data['exp_certificate'];
                  $preview_exp_certificate = base_url($exp_certificate_path . '/' . $preview_exp_certificate);
                  $pathInfo = pathinfo($preview_exp_certificate);
                  $extension = isset($pathInfo['extension']) ? $pathInfo['extension'] : '';
                }
                ?>
                <?php

                if ($preview_exp_certificate != "" && $extension != 'pdf') { ?>
                  <div id="exp_certificate_preview" class="upload_img_preview">

                    <a href="<?php echo $preview_exp_certificate . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Experience Certificate - ' . $preview_first_name;
                                                                                                                                                          echo $preview_training_id != "" ? " (" . $preview_training_id . ")" : ""; ?>">

                      <img src="<?php echo $preview_exp_certificate . "?" . time(); ?>">

                    </a>

                  </div>
                <?php } else if ($preview_exp_certificate != "" && $extension == 'pdf') {
                ?>
                  <div id="exp_certificate_preview">
                    <a target="_blank" href="<?php echo $preview_exp_certificate . "?" . time(); ?>" data-title="<?php echo 'Experience Certificate - ' . $preview_first_name;
                                                                                                              echo $preview_training_id != "" ? " (" . $preview_training_id . ")" : ""; ?>">

                      <i>View File</i>

                    </a>
                  </div>
                <?php
                } else {
                  echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                } ?>
                <div class="clearfix"></div>
              </div>
            </div>
          <?php } ?>

          <?php if ($candidate_data['qualification'] == '3' || $candidate_data['qualification'] == '4') { ?>
            <div class="col-xl-6 col-lg-6">
              <div class="form-group">
                <div class="img_preview_input_outer">
                  <label class="form_label">Uploaded Institutional ID <!-- <sup class="text-danger">*</sup> --></label>
                </div>

                <?php
                $preview_institute_idproof = '';
                if ($candidate_data['institute_idproof'] != "") {
                  $preview_institute_idproof = $candidate_data['institute_idproof'];
                  $preview_institute_idproof = base_url($institute_idproof_path . '/' . $preview_institute_idproof);

                  $pathInfo = pathinfo($preview_institute_idproof);
                  $extension = isset($pathInfo['extension']) ? $pathInfo['extension'] : '';
                }

                ?>
                <?php
                if ($preview_institute_idproof != "" && $extension != 'pdf') { ?>
                  <div id="institute_idproof_preview" class="upload_img_preview">

                    <a href="<?php echo $preview_institute_idproof . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Institutional ID - ' . $preview_first_name;
                                                                                                                                                            echo $preview_training_id != "" ? " (" . $preview_training_id . ")" : ""; ?>">
                      <img src="<?php echo $preview_institute_idproof . "?" . time(); ?>">
                    </a>

                  </div>

                <?php } else if ($preview_institute_idproof != "" && $extension == 'pdf') {
                ?>
                  <div id="institute_idproof_preview">

                    <a target="_blank" href="<?php echo $preview_institute_idproof . "?" . time(); ?>" data-title="<?php echo 'Institutional ID - ' . $preview_first_name;
                                                                                                                echo $preview_training_id != "" ? " (" . $preview_training_id . ")" : ""; ?>">
                      <i>View File</i>
                    </a>

                  </div>

                <?php
                } else {
                  echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                } ?>
                <div class="clearfix"></div>
              </div>
            </div>
          <?php } ?>

          <?php if ($candidate_data['benchmark_disability'] == 'Y') { ?>

            <?php if ($candidate_data['visually_impaired'] == 'Y') { ?>
              <div class="col-xl-6 col-lg-6">
                <div class="form-group">
                  <div class="img_preview_input_outer">
                    <label class="form_label">Uploaded Visually Impaired Certificate <!-- <sup class="text-danger">*</sup> --></label>
                  </div>

                  <?php

                  $preview_vis_imp_cert_img = '';
                  if ($candidate_data['vis_imp_cert_img'] != "") {
                    $preview_vis_imp_cert_img               = $candidate_data['vis_imp_cert_img'];

                    $preview_vis_imp_cert_img = base_url($disability_path . '/' . $preview_vis_imp_cert_img);


                    $pathInfo = pathinfo($preview_vis_imp_cert_img);
                    $extension = isset($pathInfo['extension']) ? $pathInfo['extension'] : '';
                  }

                  if ($preview_vis_imp_cert_img != "" && $extension != 'pdf') { ?>
                    <div id="vis_imp_cert_img_preview" class="upload_img_preview">

                      <a href="<?php echo $preview_vis_imp_cert_img . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Visually Impaired Certificate - ' . $preview_first_name;
                                                                                                                                                            echo $preview_training_id != "" ? " (" . $preview_training_id . ")" : ""; ?>">
                        <img src="<?php echo $preview_vis_imp_cert_img . "?" . time(); ?>">
                      </a>

                    </div>
                  <?php } else if ($preview_vis_imp_cert_img != "" && $extension == 'pdf') {
                  ?>
                    <div id="vis_imp_cert_img_preview">

                      <a target="_blank" href="<?php echo $preview_vis_imp_cert_img . "?" . time(); ?>">
                        View File
                      </a>

                    </div>
                  <?php
                  } else {
                    echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                  } ?>
                  <div class="clearfix"></div>
                </div>
              </div>
            <?php } ?>

            <?php if ($candidate_data['orthopedically_handicapped'] == 'Y') { ?>
              <div class="col-xl-6 col-lg-6">
                <div class="form-group">
                  <div class="img_preview_input_outer">
                    <label class="form_label">Uploaded Orthopedically Handicapped Certificate <!-- <sup class="text-danger">*</sup> --></label>
                  </div>

                  <?php

                  $preview_orth_han_cert_img = '';
                  if ($candidate_data['orth_han_cert_img'] != "") {
                    $preview_orth_han_cert_img = $candidate_data['orth_han_cert_img'];
                    $preview_orth_han_cert_img = base_url($disability_path . '/' . $preview_orth_han_cert_img);
                    $pathInfo = pathinfo($preview_orth_han_cert_img);
                    $extension = isset($pathInfo['extension']) ? $pathInfo['extension'] : '';
                  }

                  if ($preview_orth_han_cert_img != "" && $extension != 'pdf') { ?>

                    <div id="orth_han_cert_img_preview" class="upload_img_preview">

                      <a href="<?php echo $preview_orth_han_cert_img . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Orthopedically Handicapped Certificate - ' . $preview_first_name;
                                                                                                                                                              echo $preview_training_id != "" ? " (" . $preview_training_id . ")" : ""; ?>">
                        <img src="<?php echo $preview_orth_han_cert_img . "?" . time(); ?>">
                      </a>

                    </div>
                  <?php } else if ($preview_orth_han_cert_img != "" && $extension == 'pdf') {
                  ?>

                    <div id="orth_han_cert_img_preview">

                      <a target="_blank" href="<?php echo $preview_orth_han_cert_img . "?" . time(); ?>">
                        View File
                      </a>

                    </div>
                  <?php
                  } else {
                    echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                  } ?>
                  <div class="clearfix"></div>
                </div>
              </div>
            <?php } ?>

            <?php if ($candidate_data['cerebral_palsy'] == 'Y') { ?>
              <div class="col-xl-6 col-lg-6">
                <div class="form-group">
                  <div class="img_preview_input_outer">
                    <label class="form_label">Uploaded Cerebral Palsy Certificate <!-- <sup class="text-danger">*</sup> --></label>
                  </div>

                  <?php

                  $preview_cer_palsy_cert_img = '';
                  if ($candidate_data['cer_palsy_cert_img'] != "") {
                    $preview_cer_palsy_cert_img = $candidate_data['cer_palsy_cert_img'];
                    $preview_cer_palsy_cert_img = base_url($disability_path . '/' . $preview_cer_palsy_cert_img);
                    $pathInfo = pathinfo($preview_cer_palsy_cert_img);
                    $extension = isset($pathInfo['extension']) ? $pathInfo['extension'] : '';
                  }

                  if ($preview_cer_palsy_cert_img != "" && $extension != 'pdf') { ?>

                    <div id="cer_palsy_cert_img_preview" class="upload_img_preview">

                      <a href="<?php echo $preview_cer_palsy_cert_img . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Cerebral Palsy Certificate - ' . $preview_first_name;
                                                                                                                                                              echo $preview_training_id != "" ? " (" . $preview_training_id . ")" : ""; ?>">
                        <img src="<?php echo $preview_cer_palsy_cert_img . "?" . time(); ?>">
                      </a>

                    </div>
                  <?php
                  } else if ($preview_cer_palsy_cert_img != "" && $extension == 'pdf') { ?>

                    <div id="cer_palsy_cert_img_preview" class="">

                      <a target="_blank" href="<?php echo $preview_cer_palsy_cert_img . "?" . time(); ?>">
                        View File
                      </a>

                    </div>
                  <?php
                  } else {
                    echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                  } ?>
                  <div class="clearfix"></div>
                </div>
              </div>
            <?php } ?>

          <?php } ?>

          <div class="col-xl-6 col-lg-6 mb-4">
            <div class="form-group">
              <?php if ($candidate_data['id_proof_file'] == "") { ?>
                <div class="img_preview_input_outer pull-left">
                  <label for="id_proof_file" class="form_label">Uploaded APAAR ID/ABC ID Card <!-- <sup class="text-danger">*</sup> --></label>
                  <input type="file" name="id_proof_file" id="id_proof_file" class="form-controlx hide_input_file_cropper" <?php if ($candidate_data['id_proof_file'] == "") {
                                                                                                                              echo 'required';
                                                                                                                            } ?> />

                  <div class="image-input image-input-outline image-input-circle image-input-empty">
                    <div class="profile-progress"></div>
                    <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('id_proof_file', 'ncvet_candidates', 'Edit Aadhar Card')">Uploaded APAAR ID/ABC ID Card</button>
                  </div>
                  <note class="form_note" id="id_proof_file_err">Note: Please select only .jpg, .jpeg, .png file upto 20MB.</note>

                  <input type="hidden" name="id_proof_file_cropper" id="id_proof_file_cropper" value="<?php echo set_value('id_proof_file_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>

                  <?php if (form_error('id_proof_file') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('id_proof_file'); ?></label> <?php } ?>
                  <?php if ($id_proof_file_error != "") { ?> <div class="clearfix"></div><label class="error"><?php echo $id_proof_file_error; ?></label> <?php } ?>
                </div>
              <?php } else { ?>
                <div class="img_preview_input_outer">
                  <label class="form_label">Uploaded APAAR ID/ABC ID Card <!-- <sup class="text-danger">*</sup> --></label>
                </div>
              <?php } 
              $preview_id_proof_file = '';
                if ($candidate_data['id_proof_file'] != "") {
                  $preview_id_proof_file = $candidate_data['id_proof_file'];
                  $preview_id_proof_file = base_url($id_proof_file_path . '/' . $preview_id_proof_file);

                  $pathInfo = pathinfo($preview_id_proof_file);
                  $extension = isset($pathInfo['extension']) ? $pathInfo['extension'] : '';
                }

              ?>

              <?php
                
                if ($preview_id_proof_file != "" && $extension!='pdf') { ?>
              <div id="id_proof_file_preview" class="upload_img_preview <?php if ($candidate_data['id_proof_file'] == "") {
                                                                          echo 'pull-right';
                                                                        } ?>">
               
                  <a href="<?php echo $preview_id_proof_file . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Aadhar Card - ' . $preview_first_name;
                                                                                                                                                      echo $preview_training_id != "" ? " (" . $preview_training_id . ")" : ""; ?>">
                    <img src="<?php echo $preview_id_proof_file . "?" . time(); ?>">
                  </a>
                </div>
                <?php } 
                else if ($preview_id_proof_file != "" && $extension == 'pdf') {
                ?>
                  <div id="id_proof_file_preview">

                    <a target="_blank" href="<?php echo $preview_id_proof_file . "?" . time(); ?>" data-title="<?php echo 'APAAR File - ' . $preview_first_name;
                                                                                                                            echo $preview_training_id != "" ? " (" . $preview_training_id . ")" : ""; ?>">
                      <i>View File</i>

                    </a>

                  </div>
                <?php
                }
                else {
                  echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                } ?>
              
              <div class="clearfix"></div>
            </div>
          </div>
          <div class="col-xl-6 col-lg-6 mb-4">
            <div class="form-group">
              <?php if ($candidate_data['aadhar_file'] == "") { ?>
                <div class="img_preview_input_outer pull-left">
                  <label for="aadhar_file" class="form_label">Uploaded Aadhar Card <!-- <sup class="text-danger">*</sup> --></label>
                  <input type="file" name="aadhar_file" id="aadhar_file" class="form-controlx hide_input_file_cropper" <?php if ($candidate_data['aadhar_file'] == "") {
                                                                                                                          echo 'required';
                                                                                                                        } ?> />

                  <div class="image-input image-input-outline image-input-circle image-input-empty">
                    <div class="profile-progress"></div>
                    <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('aadhar_file', 'ncvet_candidates', 'Edit Aadhar Card')">Upload Aadhar Card</button>
                  </div>
                  <note class="form_note" id="aadhar_file_err">Note: Please select only .jpg, .jpeg, .png file upto 20MB.</note>

                  <input type="hidden" name="aadhar_file_cropper" id="aadhar_file_cropper" value="<?php echo set_value('aadhar_file_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>

                  <?php if (form_error('aadhar_file') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('aadhar_file'); ?></label> <?php } ?>
                  <?php if ($aadhar_file_error != "") { ?> <div class="clearfix"></div><label class="error"><?php echo $aadhar_file_error; ?></label> <?php } ?>
                </div>
              <?php } else { ?>
                <div class="img_preview_input_outer">
                  <label class="form_label">Uploaded Aadhar Card <!-- <sup class="text-danger">*</sup> --></label>
                </div>
              <?php } ?>

               <?php
                $preview_aadhar_file = '';
                if ($candidate_data['aadhar_file'] != "") {
                  $preview_aadhar_file = $candidate_data['aadhar_file'];
                  $preview_aadhar_file = base_url($aadhar_file_path . '/' . $preview_aadhar_file);

                  $pathInfo = pathinfo($preview_aadhar_file);
                  $extension = isset($pathInfo['extension']) ? $pathInfo['extension'] : '';
                }

                if ($preview_aadhar_file != "" && $extension != 'pdf') { ?>
              <div id="aadhar_file_preview" class="upload_img_preview <?php if ($candidate_data['aadhar_file'] == "") {
                                                                        echo 'pull-right';
                                                                      } ?>">
               
                  <a href="<?php echo $preview_aadhar_file . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Aadhar Card - ' . $preview_first_name;
                                                                                                                                                    echo $preview_training_id != "" ? " (" . $preview_training_id . ")" : ""; ?>">
                    <img src="<?php echo $preview_aadhar_file . "?" . time(); ?>">
                  </a>
                  </div>
                <?php } 
                else if ($preview_aadhar_file != "" && $extension == 'pdf') {
                ?>
                  <div id="aadhar_file_preview">

                    <a target="_blank" href="<?php echo $preview_aadhar_file . "?" . time(); ?>" data-title="<?php echo 'AADHAR File - ' . $preview_first_name;
                                                                                                                            echo $preview_training_id != "" ? " (" . $preview_training_id . ")" : ""; ?>">
                      <i>View File</i>

                    </a>

                  </div>
                <?php
                }else {

                  echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                } ?>
              
              <div class="clearfix"></div>
            </div>
          </div>

          <input type="hidden" id="data_lightbox_hidden" value="candidate_images">
          <input type="hidden" id="data_lightbox_title_hidden" value="<?php echo $preview_first_name;
                                                                      echo $preview_training_id != "" ? " (" . $preview_training_id . ")" : ""; ?>">

          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <?php if ($candidate_data['candidate_photo'] == "") { ?>
                <div class="img_preview_input_outer pull-left">
                  <label for="candidate_photo" class="form_label">Upload Passport-size Photo <!-- <sup class="text-danger">*</sup> --></label>
                  <input type="file" name="candidate_photo" id="candidate_photo" class="form-controlx hide_input_file_cropper" <?php if ($candidate_data['candidate_photo'] == "") {
                                                                                                                                  echo 'required';
                                                                                                                                } ?> />

                  <div class="image-input image-input-outline image-input-circle image-input-empty">
                    <div class="profile-progress"></div>
                    <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('candidate_photo', 'ncvet_candidates', 'Edit Photo')">Upload Photo</button>
                  </div>
                  <note class="form_note" id="candidate_photo_err">Note: Please select only .jpg, .jpeg, .png file upto 20MB.</note>

                  <input type="hidden" name="candidate_photo_cropper" id="candidate_photo_cropper" value="<?php echo set_value('candidate_photo_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>

                  <?php if (form_error('candidate_photo') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('candidate_photo'); ?></label> <?php } ?>
                  <?php if ($candidate_photo_error != "") { ?> <div class="clearfix"></div><label class="error"><?php echo $candidate_photo_error; ?></label> <?php } ?>
                </div>
              <?php } else { ?>
                <div class="img_preview_input_outer">
                  <label class="form_label">Uploaded Passport-size Photo <!-- <sup class="text-danger">*</sup> --></label>
                </div>
              <?php } ?>

              <div id="candidate_photo_preview" class="upload_img_preview <?php if ($candidate_data['candidate_photo'] == "") {
                                                                            echo 'pull-right';
                                                                          } ?>">
                <?php
                $preview_candidate_photo = '';
                if ($candidate_data['candidate_photo'] != "") {
                  $preview_candidate_photo = $candidate_data['candidate_photo'];
                  $preview_candidate_photo = base_url($candidate_photo_path . '/' . $preview_candidate_photo);
                }

                if ($preview_candidate_photo != "") { ?>
                  <a href="<?php echo $preview_candidate_photo . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Passport-size Photo - ' . $preview_first_name;
                                                                                                                                                        echo $preview_training_id != "" ? " (" . $preview_training_id . ")" : ""; ?>">
                    <img src="<?php echo $preview_candidate_photo . "?" . time(); ?>">
                  </a>
                <?php } else {
                  echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                } ?>
              </div>
              <div class="clearfix"></div>
            </div>
          </div>

          <div class="col-xl-6 col-lg-6">
            <div class="form-group">
              <?php if ($candidate_data['candidate_sign'] == "") { ?>
                <div class="img_preview_input_outer pull-left">
                  <label for="candidate_sign" class="form_label">Upload Candidate Signature <!-- <sup class="text-danger">*</sup> --></label>
                  <input type="file" name="candidate_sign" id="candidate_sign" class="form-controlx hide_input_file_cropper" <?php if ($candidate_data['candidate_sign'] == "") {
                                                                                                                                echo 'required';
                                                                                                                              } ?> />

                  <div class="image-input image-input-outline image-input-circle image-input-empty">
                    <div class="profile-progress"></div>
                    <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('candidate_sign', 'ncvet_candidates', 'Edit Signature')">Upload Candidate Signature</button>
                  </div>
                  <note class="form_note" id="candidate_sign_err">Note: Please select only .jpg, .jpeg, .png file upto 20MB.</note>

                  <input type="hidden" name="candidate_sign_cropper" id="candidate_sign_cropper" value="<?php echo set_value('candidate_sign_cropper'); ?>" />

                  <?php if (form_error('candidate_sign') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('candidate_sign'); ?></label> <?php } ?>
                  <?php if ($candidate_sign_error != "") { ?> <div class="clearfix"></div><label class="error"><?php echo $candidate_sign_error; ?></label> <?php } ?>
                </div>
              <?php } else { ?>
                <div class="img_preview_input_outer">
                  <label class="form_label">Upload Candidate Signature <!-- <sup class="text-danger">*</sup> --></label>
                </div>
              <?php } ?>

              <div id="candidate_sign_preview" class="upload_img_preview <?php if ($candidate_data['candidate_sign'] == "") {
                                                                            echo 'pull-right';
                                                                          } ?>">
                <?php
                $preview_candidate_sign = '';
                if ($candidate_data['candidate_sign'] != "") {
                  $preview_candidate_sign = $candidate_data['candidate_sign'];
                  $preview_candidate_sign = base_url($candidate_sign_path . '/' . $preview_candidate_sign);
                }

                if ($preview_candidate_sign != "") { ?>
                  <a href="<?php echo $preview_candidate_sign . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Signature - ' . $preview_first_name;
                                                                                                                                                      echo $preview_training_id != "" ? " (" . $preview_training_id . ")" : ""; ?>">
                    <img src="<?php echo $preview_candidate_sign . "?" . time(); ?>">
                  </a>
                <?php } else {
                  echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                } ?>
              </div>
              <div class="clearfix"></div>
            </div>
          </div>

          <?php if ($candidate_data['qualification'] == '3' || $candidate_data['qualification'] == '4') { ?>
            <div class="col-xl-6 col-lg-6">
              <div class="form-group">
                <?php if ($candidate_data['declarationform'] == "") { ?>
                  <div class="img_preview_input_outer pull-left">
                    <label for="declarationform" class="form_label">Upload Candidate Declaration <!-- <sup class="text-danger">*</sup> --></label>
                    <input type="file" name="declarationform" id="declarationform" class="form-controlx hide_input_file_cropper" <?php if ($candidate_data['declarationform'] == "") {
                                                                                                                                    echo 'required';
                                                                                                                                  } ?> />

                    <div class="image-input image-input-outline image-input-circle image-input-empty">
                      <div class="profile-progress"></div>
                      <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('declarationform', 'ncvet_candidates', 'Edit Candidate Declaration')">Upload Candidate Declaration</button>
                    </div>
                    <note class="form_note" id="declarationform_err">Note: Please select only .jpg, .jpeg, .png file upto 20MB.</note>

                    <input type="hidden" name="declarationform_cropper" id="declarationform_cropper" value="<?php echo set_value('declarationform_cropper'); ?>" />

                    <?php if (form_error('declarationform') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('declarationform'); ?></label> <?php } ?>
                    <?php if ($declarationform_error != "") { ?> <div class="clearfix"></div><label class="error"><?php echo $declarationform_error; ?></label> <?php } ?>
                  </div>
                <?php } else { ?>
                  <div class="img_preview_input_outer">
                    <label class="form_label">Uploaded Candidate Declaration <!-- <sup class="text-danger">*</sup> --></label>
                  </div>
                <?php } ?>

                <?php
                $preview_declarationform = '';
                if ($candidate_data['declarationform'] != "") {
                  $preview_declarationform = $candidate_data['declarationform'];
                  $preview_declarationform = base_url($declarationform_path . '/' . $preview_declarationform);

                  $pathInfo = pathinfo($preview_declarationform);
                  $extension = isset($pathInfo['extension']) ? $pathInfo['extension'] : '';
                }
                ?>
                <?php

                if ($preview_declarationform != "" && $extension != 'pdf') { ?>
                  <div id="declarationform_preview" class="upload_img_preview <?php if ($candidate_data['declarationform'] == "") {
                                                                                echo 'pull-right';
                                                                              } ?>">

                    <a href="<?php echo $preview_declarationform . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Declaration - ' . $preview_first_name;
                                                                                                                                                          echo $preview_training_id != "" ? " (" . $preview_training_id . ")" : ""; ?>">
                      <img src="<?php echo $preview_declarationform . "?" . time(); ?>">
                    </a>

                  </div>
                <?php } else if ($preview_declarationform != "" && $extension == 'pdf') {
                ?>
                  <div id="declarationform_preview">

                    <a target="_blank" href="<?php echo $preview_declarationform . "?" . time(); ?>" data-title="<?php echo 'Declaration - ' . $preview_first_name;
                                                                                                              echo $preview_training_id != "" ? " (" . $preview_training_id . ")" : ""; ?>">
                      <i>View File</i>
                    </a>

                  </div>
                <?php
                } else {
                  echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                } ?>
                <div class="clearfix"></div>
              </div>
            </div>
          <?php } ?>
        </div>

        <div class="hr-line-dashed"></div>
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="submit_btn_outer2">
            <input type="submit" class="btn btn-primary" id="submitAll" name="submitAll" value="<?php echo "Proceed To Payment"; ?>">

            <button type="button" class="btn btn-warning" onclick="history.back()">Back to Form</button>
          </div>
        </div>
      </form>
    </div>
  </div>


  <?php $this->load->view('ncvet/inc_footer'); ?>

  <?php $this->load->view('ncvet/common/inc_common_validation_all'); ?>
  <?php $this->load->view('ncvet/common/inc_common_show_hide_password'); ?>

  <?php $this->load->view('ncvet/common/inc_cropper_script', array('page_name' => 'candidate_enrollment')); ?>


  <?php $this->load->view('ncvet/common/inc_bottom_script'); ?>
</body>

</html>