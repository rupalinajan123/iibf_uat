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
      
    ?>
    
    <div id="wrapper">
      <?php $this->load->view('ncvet/candidate/inc_sidebar_candidate'); ?>    
      <div id="page-wrapper" class="gray-bg">       
        <?php $this->load->view('ncvet/candidate/inc_topbar_candidate'); ?>
        
        <div class="row wrapper border-bottom white-bg page-heading">
          <div class="col-lg-10">
            <h2>Training Re-Registration</h2>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo site_url('ncvet/candidate/dashboard_candidate'); ?>">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?php echo site_url('ncvet/candidate/applytraining/traininglist'); ?>">Trainings</a></li>
              <li class="breadcrumb-item active"> <strong>Training Re-Registration</strong></li>
            </ol>
          </div>
          <div class="col-lg-2"> </div>
        </div>      
        
        <div class="wrapper wrapper-content animated fadeInRight">
          <div class="row">
            <div class="col-lg-12">
              <div class="ibox"> 
                <div class="ibox-content">
                  <?php if(isset($var_errors)) echo $var_errors; ?>
                  <form method="post" action="<?php echo site_url('ncvet/candidate/applytraining/trainingform'); ?>" id="apply_trainingform" enctype="multipart/form-data" autocomplete="off">
                    <input id="program_code" name="program_code" value="<?php echo $training_details['program_code']; ?>" type="hidden">
                    <input id="mtype" name="mtype" value="<?php echo $form_data[0]['registration_type']; ?>" type="hidden">
                    <h4 class="custom_form_title" style="margin: -15px -20px 15px -20px !important;">Basic Details</h4>
                    
                    <?php 
                    $salutation_master_arr          = array('Mr.', 'Mrs.', 'Ms.');
                    $guardian_salutation_master_arr = array('Mr.','Ms.', 'Mrs.');
                    $qualification_arr              = $this->config->item('ncvet_qualification_arr');
                    $graduation_sem_arr             = $this->config->item('ncvet_graduation_sem_arr');
                    $post_graduation_sem_arr        = $this->config->item('ncvet_post_graduation_sem_arr'); 
                    ?>
                    
                    <div class="row">
                      <div class="col-xl-4 col-lg-4">
                        <div class="form-group">
                          <label class="form_label">Enrollment Number <!-- <sup class="text-danger">*</sup> --></label>
                          <input type="text" value="<?php echo $form_data[0]['regnumber']; ?>" class="form-control" readonly disabled />
                        </div>          
                      </div>

                      <div class="col-xl-8 col-lg-8">
                        <?php $chk_salutation = $form_data[0]['salutation']; ?>
                        <label for="salutation" class="form_label">Candidate Name </label>
                                  <input type="text" name="" id="" maxlength="" value="<?php echo $form_data[0]['salutation'].' '.str_replace('  ',' ',$form_data[0]['first_name'].' '.$form_data[0]['middle_name'].' '.$form_data[0]['last_name']); ?>" class="form-control" readonly disabled />
                      </div>
                      
                      <div class="col-xl-4 col-lg-4">
                        <div class="form-group">
                          <label class="form_label">Gender <!-- <sup class="text-danger">*</sup> --></label>
                          <input type="text" value="<?php if($form_data[0]['gender'] == '1') { echo 'Male'; } else if($form_data[0]['gender'] == '2') { echo 'Female'; } ?>" class="form-control" readonly disabled />
                        </div>          
                      </div>
                      
                      <div class="col-xl-8 col-lg-8">
                          <div class="form-group">
                              <label class="form_label">Father/Mother/Guardian Name </label>
                              <input type="text" name="guardian_name" id="guardian_name" value="<?php echo $form_data[0]['guardian_salutation']; echo set_value('guardian_name', $form_data[0]['guardian_name']); ?>" class="form-control" readonly disabled />
                              <note class="form_note" id="guardian_name_err"></note>
                              <?php echo form_error('guardian_name', '<div class="text-danger">', '</div>'); ?>
                          </div>
                      </div>

                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <label class="form_label">Email ID <!-- <sup class="text-danger">*</sup> --></label>
                          <div id="email_id_err">   
                            <input type="text" name="email_id" id="email_id" value="<?php echo $form_data[0]['email_id'];?>" class="form-control" readonly disabled />
                            <note class="form_note" id="email_id_err"></note>
                          </div>
                        </div>          
                      </div>

                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <label class="form_label">Mobile Number <!-- <sup class="text-danger">*</sup> --></label>
                          <div id="mobile_no_err">   
                            <input type="text" name="mobile_no" id="mobile_no" value="<?php echo $form_data[0]['mobile_no']; ?>" class="form-control" readonly disabled />
                            <note class="form_note" id="mobile_no_err"></note>
                          </div>
                        </div>          
                      </div>

                      <?php 
                        if($form_data[0]['dob'] != '0000-00-00') { 
                           $dob = new DateTime($form_data[0]['dob']); 
                           $today = new DateTime(); 
                           $age = $today->diff($dob)->y; 
                        } 
                      ?>

                      <div class="col-xl-3 col-lg-3">
                        <div class="form-group">
                          <label class="form_label">Date of Birth <!-- <sup class="text-danger">*</sup> --></label>
                          <input type="text" value="<?php if($form_data[0]['dob'] != '0000-00-00') { echo $form_data[0]['dob']; } ?>" class="form-control" readonly disabled />
                        </div>          
                      </div>

                      <div class="col-xl-1 col-lg-1">
                        <div class="form-group">
                          <label class="form_label">Age (Yr)</label>
                          <input type="text" value="<?php echo $age; ?>" class="form-control" readonly disabled />
                        </div>          
                      </div>
                       <div class="col-xl-8 col-lg-8">
                          <div class="form-group">
                              <label class="form_label">Eligibility </label>
                              <input type="text" name="qualification" id="qualification" value="<?php echo $qualification_arr[$form_data[0]['qualification']] ?>" class="form-control" readonly disabled />
                              <note class="form_note" id="qualification_err"></note>
                              <?php echo form_error('qualification', '<div class="text-danger">', '</div>'); ?>
                          </div>
                      </div>

                      
                  
                        
                        </div>          
                      </div>

                    </div>  
                   
                    <h4 class="custom_form_title" style="margin: 20px -20px 15px -20px !important;">Training Details</h4>
                    <div class="row">
                    
                      
                      <div class="col-xl-6 col-lg-6"  >
                        <div class="form-group">
                          <label class="form_label">Training <!-- <sup class="text-danger">*</sup> --></label>
                          <div id="exam_name_err">   
                            <input type="text" name="program_name" id="program_name" value="<?php echo $training_details['program_name'] ?>" class="form-control" readonly  />
                            <note class="form_note" id="exam_name_err"></note>
                            <?php if(form_error('program_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('program_name'); ?></label> <?php } ?>
                          </div>
                        </div>          
                      </div>

                      <div class="col-xl-3 col-lg-3 "  >
                        <div class="form-group">
                          <label class="form_label">Fee <!-- <sup class="text-danger">*</sup> --></label>
                          <div id="exam_name_err">   
                            <input type="text" name="amount" id="amount" value="<?php echo $amount ?>" class="form-control" readonly  />
                            <note class="form_note" id="exam_name_err"></note>
                            <?php if(form_error('amount')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('amount'); ?></label> <?php } ?>
                          </div>
                        </div>          
                      </div>
 
                    </div>
                    
                    <div class="hr-line-dashed"></div>  
                    <?php // if (!$isUpdateProfile) {  ?>                 
                      <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="submit_btn_outer2">
                          <input type="submit" class="btn btn-primary" id="submitAll" name="submitAll" value="Proceed to payment"> 
                        </div>
                      </div>
                    <?php // } ?>
                  </form>
                </div>
              </div>

            </div>          
          </div>
        </div>
        <?php $this->load->view('ncvet/candidate/inc_footerbar_candidate'); ?>    
      </div>
    </div>

    
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
    
    <?php $this->load->view('ncvet/common/inc_bottom_script'); ?>
  </body>
</html>