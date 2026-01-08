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
              <li class="breadcrumb-item active"> <strong>Exam Acknowledgement</strong></li>
            </ol>
          </div>
          <div class="col-lg-2"> </div>
        </div>      
        
        <div class="wrapper wrapper-content animated fadeInRight">
          <div class="row">
            <div class="col-lg-12">
              <div class="ibox"> 
                <div class="ibox-content">
                  <h2>Thank you for Application</h2>
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
    
    <script src="<?php echo base_url(); ?>/assets/ncvet/js/examapplication.js?ver=1757324082"></script>

    <?php $this->load->view('ncvet/inc_footer'); ?>   
    <?php $this->load->view('ncvet/common/inc_common_validation_all'); ?>
    <?php $this->load->view('ncvet/common/inc_cropper_script_edit', array('page_name'=>'ncvet_candidate_update_profile')); ?>
  
    <?php $this->load->view('ncvet/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_candidate_id, 'module_slug'=>'candidate_action', 'log_title'=>'Candidate Log')); ?>    

    <?php $this->load->view('ncvet/common/inc_bottom_script'); ?>
  </body>
</html>