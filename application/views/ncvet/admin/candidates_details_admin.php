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

	<body class="fixed-sidebar">
    <?php $this->load->view('ncvet/common/inc_loader'); ?>
		
		<div id="wrapper">
      <?php $this->load->view('ncvet/admin/inc_sidebar_admin'); ?>		
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('ncvet/admin/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>Candidate Details</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('ncvet/admin/dashboard_admin'); ?>">Dashboard</a></li>
							
              <li class="breadcrumb-item"><a href="<?php echo site_url('ncvet/admin/candidate'); ?>">All Candidates</a></li>
                
							<li class="breadcrumb-item active"> <strong>Candidate Details</strong></li>
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
                    <a href="<?php echo site_url('ncvet/admin/candidate'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a>
                  </div>
                </div>
								<div class="ibox-content">
                  <div class="table-responsive">
										<table class="table table-bordered custom_inner_tbl" style="width:100%">
											<tbody>
                      <?php 
                        //print_r($form_data);
                          $sub_data['candidate_data'] = $form_data;
                          $sub_data['id_proof_file_path'] = $id_proof_file_path;
                          $sub_data['qualification_certificate_file_path'] = $qualification_certificate_file_path;
                          $sub_data['candidate_photo_path'] = $candidate_photo_path;
                          $sub_data['candidate_sign_path'] = $candidate_sign_path;
                          $this->load->view('ncvet/common/inc_candidate_details_common', $sub_data); 
                        ?>
                      </tbody>
                    </table>
                    
                    <div class="hr-line-dashed"></div>										
										<div class="text-center">
                      <?php 
                        $field_sub_data['candidate_data'] = $form_data;
                        $field_sub_data['enc_pk_id']      = $enc_candidate_id;
                        $this->load->view('ncvet/admin/candidate_fields_edit', $field_sub_data); 
                      ?> 
                    </div>
                  </div>                  
                </div>
              </div>
              
              <div id="common_log_outer"></div>
            </div>					
          </div>
        </div>
				<?php $this->load->view('ncvet/admin/inc_footerbar_admin'); ?>		
      </div>
    </div>
		<?php $this->load->view('ncvet/inc_footer'); ?>		
		
    <?php 
      $this->load->view('ncvet/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_candidate_id, 'module_slug'=>'candidate_action', 'log_title'=>'Candidate Log'));
    ?>
    <?php $this->load->view('ncvet/common/inc_bottom_script'); ?>
    
    <script>
      Fancybox.bind("[data-fancybox]", {
        mainClass: "custom-fancybox",
        autoFocus: false
      });
    </script>

  </body>
</html>