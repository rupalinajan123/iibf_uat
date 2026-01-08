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
            <h2>Exam Registration</h2>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo site_url('ncvet/candidate/dashboard_candidate'); ?>">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?php echo site_url('ncvet/candidate/applyexam/examlist'); ?>">Exams</a></li>
              <li class="breadcrumb-item active"> <strong>Exam Registration</strong></li>
            </ol>
          </div>
          <div class="col-lg-2"> </div>
        </div>      
        
        <div class="wrapper wrapper-content animated fadeInRight">
          <div class="row">
            <div class="col-lg-12">
              <div class="ibox"> 
                <div class="ibox-content">
                  <form method="post" action="<?php echo site_url('ncvet/candidate/applyexam/process_application'); ?>" id="apply_examform" enctype="multipart/form-data" autocomplete="off">
                    <input id="optmode" name="optmode" value="ON" type="hidden">
                    <input id="examcode" name="examcode" value="<?php echo $examinfo['exam_code']; ?>" type="hidden">
                    <input id="eprid" name="examperiod" value="<?php echo $examinfo['exam_period']; ?>" type="hidden">
                    <input id="grp_code" name="grp_code" value="<?php echo $examinfo['grp_code']; ?>" type="hidden">
                    <input id="mtype" name="mtype" value="<?php echo $examinfo['registration_type']; ?>" type="hidden">
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
                   
                    <h4 class="custom_form_title" style="margin: 20px -20px 15px -20px !important;">Exam Details</h4>
                    <div class="row">
                      
                      <div class="col-xl-1 col-lg-1 "  >&nbsp;</div>
                      <div class="col-xl-6 col-lg-6 exam_name-section"  >
                        <div class="form-group">
                          <label class="form_label">Exam <!-- <sup class="text-danger">*</sup> --></label>
                          <div id="exam_name_err">   
                            <input type="text" name="exam_name" id="exam_name" value="<?php echo $examinfo['exam_name'] ?>" class="form-control" readonly disabled />
                            <note class="form_note" id="exam_name_err"></note>
                            <?php if(form_error('exam_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_name'); ?></label> <?php } ?>
                          </div>
                        </div>          
                      </div>

                      <div class="col-xl-3 col-lg-3 exam_period-section"  >
                        <div class="form-group">
                          <label class="form_label">Exam Period<!-- <sup class="text-danger">*</sup> --></label>
                          <div id="exam_period_err">   
                            <input type="text" name="exam_period" id="exam_period" value="<?php  echo $examinfo['exam_month']; ?>" class="form-control" readonly disabled />
                            <note class="form_note" id="exam_period_err"></note>
                            <?php if(form_error('exam_period')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_period'); ?></label> <?php } ?>
                          </div>
                        </div>          
                      </div>

                      <div class="col-xl-12 col-lg-12 "  >&nbsp;</div>

                     <?php 
                                 $subject_details = $examinfo['subject_details'];
                                 if(count($subject_details) > 0)
                                {
                                  foreach($subject_details as $s)
                                  {
                                    
                                    ?>

                      <div class="col-xl-1 col-lg-1 "  >&nbsp;</div>
                      <div class="col-xl-6 col-lg-6 exam_subject-section"  >
                        <div class="form-group">
                          <label class="form_label">Subject <sup class="text-danger">*</sup></label>
                          
                                  
                                    <input type="text" name="exam_subject" id="exam_subject" value="<?php echo $s['subject_description'] ?>" class="form-control" readonly disabled />
                                   
                                 
                                    <note class="form_note" id="exam_subject_err"></note>
                                    <?php if(form_error('exam_subject')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_subject'); ?></label> <?php } ?>
                          
                        </div>          
                      </div>

                      <div class="col-xl-3 col-lg-3 exam_subject-section"  >
                        <div class="form-group">
                          <label class="form_label">Date / Time <sup class="text-danger">*</sup></label>
                          
                                  
                                  
                                    <input type="text" name="exam_subject" id="exam_subject" value="<?php echo $s['exam_date'].' '.$s['exam_time'] ?>" class="form-control " readonly disabled />
                                  
                                 
                                    <note class="form_note" id="exam_subject_err"></note>
                                    <?php if(form_error('exam_subject')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_subject'); ?></label> <?php } ?>
                          
                        </div>          
                      </div>
                       <?php } 
                                  }
                                  ?>
                       <div class="col-xl-12 col-lg-12 "  >&nbsp;</div>
                       <div class="col-xl-1 col-lg-1 "  >&nbsp;</div>
                       <div class="col-xl-3 col-lg-3 exam_medium-section"  >
                        <div class="form-group">
                          <label class="form_label">Medium <sup class="text-danger">*</sup></label>
                          <div id="exam_medium_err">   
                             <?php if(count($medium_details) > 0)
                                {
                                  foreach($medium_details as $m)
                                  {
                                    if($m['medium_code']==$examinfo['exam_medium']) {
                                    ?>
                                      <input type="text" name="exam_medium" id="exam_medium" value="<?php  echo $m['medium_description']; ?>" class="form-control" readonly disabled />
                                  <?php } }
                                }?>
                              
                            <note class="form_note" id="exam_medium_err"></note>
                            <?php if(form_error('exam_medium')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_medium'); ?></label> <?php } ?>
                          </div>
                        </div>          
                      </div>

                      <div class="col-xl-3 col-lg-3 exam_center-section"  >
                        <div class="form-group">
                          <label class="form_label">Center <sup class="text-danger">*</sup></label>
                          <div id="exam_center_err">   
                              <input type="text" name="exam_center_name" id="exam_center_name" value="<?php  echo $examinfo['exam_center_name']; ?>" class="form-control" readonly disabled />
                            <note class="form_note" id="exam_center_err"></note>
                            <?php if(form_error('exam_center')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_center'); ?></label> <?php } ?>
                          </div>
                        </div>          
                      </div>
                      <div class="col-xl-3 col-lg-3 exam_fee-section"  >
                        <div class="form-group">
                          <label class="form_label">Fee <sup class="text-danger">*</sup></label>
                          <div id="exam_fee_err">   
                              <input type="text" name="exam_fee" id="exam_fee" value="<?php  echo $examinfo['exam_fee']; ?>" class="form-control" readonly  />
                            <note class="form_note" id="exam_fee_err"></note>
                            <?php if(form_error('exam_fee')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_fee'); ?></label> <?php } ?>
                          </div>
                        </div>          
                      </div>
                      <div class="col-xl-12 col-lg-12 "  >&nbsp;</div>
                      <div class="col-xl-1 col-lg-1 "  >&nbsp;</div>
                      <div class="col-xl-6 col-lg-6 scribe-section"  >
                        <div class="form-group">
                          <label class="form_label">Do you intend to use the services of a scribe ? <sup class="text-danger">*</sup></label>
                          <div id="scribe_err">   
                             
                          <?php if($examinfo['scribe_flag']=='Y'){ ?>
                            <div style="margin-top: 10px;">
                                Yes
                          </div>
                            <?php } else {
                              ?>
                              <div style="margin-top: 10px;">
                                NO
                              </div>
                              <?php 
                            } ?>
                            <note class="form_note" id="scribe_err"></note>
                            <?php if(form_error('scribe')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('scribe_flag'); ?></label> <?php } ?>
                          </div>
                        </div>          
                      </div>

                    </div>
                    
                    <div class="hr-line-dashed"></div>  
                    <?php // if (!$isUpdateProfile) {  ?>                 
                      <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="submit_btn_outer2">
                          <input type="submit" class="btn btn-primary" id="submitAll" name="process_application" value="Submit"> 
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

    <div class="modal fade" id="scribemodal" tabindex="-1" role="dialog" aria-labelledby="scribeModalLabel" >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <center><strong> <h4 class="modal-title" id="scribeModalLabel" style="color:#F00"> Scribe Information</h4></strong></center>
          </div>
          <div class="modal-body">
              In case any candidate wants to avail scribe facility he/she needs to apply online on the IIBF website by clicking on Apply Now Apply for scribe. Once the application is approved by IIBF, the candidate will get an email confirmation of the permission granted by the Institute. Candidates are advised to apply online for scribe well in advance, not later than 3 days before the examination. (This is required to make suitable arrangements at the examination venue). Candidate is required to follow this procedure for each attempt/subject of the examination in case the help of scribe is required.<br />
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
          
          </div>
        </div>
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
    <script>
      var site_url = "<?php echo base_url(); ?>/";
      function showSelect_scribe_flagY() {
        $('#scribeModal').modal('show');
        }
        function showSelect_scribe_flagN() {
        $('#scribeModal').modal('hide');
        }

    </script>
    <script src="<?php echo base_url(); ?>/assets/ncvet/js/examapplication.js?ver=1757324082"></script>

    <?php $this->load->view('ncvet/inc_footer'); ?>   
    <?php $this->load->view('ncvet/common/inc_common_validation_all'); ?>
    <?php $this->load->view('ncvet/common/inc_cropper_script_edit', array('page_name'=>'ncvet_candidate_update_profile')); ?>
  
    <?php $this->load->view('ncvet/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_candidate_id, 'module_slug'=>'candidate_action', 'log_title'=>'Candidate Log')); ?>    

    <?php $this->load->view('ncvet/common/inc_bottom_script'); ?>
  </body>
</html>