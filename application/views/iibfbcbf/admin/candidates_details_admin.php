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
      <?php $this->load->view('iibfbcbf/admin/inc_sidebar_admin'); ?>		
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('iibfbcbf/admin/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>Candidate Details</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/dashboard_admin'); ?>">Dashboard</a></li>
							<?php if($enc_batch_id != '0')  
              { ?>
                <li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/training_batches_admin'); ?>">Training Batches</a></li>
                <li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/batch_candidates/index/'.url_encode($form_data[0]['batch_id'])); ?>">Candidate List (<?php echo $batch_data[0]['batch_code']; ?>)</a></li>
              <?php }
              else
              { ?>
                <li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/batch_candidates'); ?>">All Candidates</a></li>
              <?php } ?>  
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
                    <?php if($enc_batch_id != '0') { ?>
										  <a href="<?php echo site_url('iibfbcbf/admin/batch_candidates/index/'.url_encode($form_data[0]['batch_id'])); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a>
                    <?php }
                    else { ?>
                      <a href="<?php echo site_url('iibfbcbf/admin/batch_candidates'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a>
                    <?php } ?>
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
                          $this->load->view('iibfbcbf/common/inc_candidate_details_common', $sub_data); 
                        ?>
                      </tbody>
                    </table>
                    
                    <div class="hr-line-dashed"></div>										
										<div class="text-center" id="submit_btn_outer">
                      

                      <?php if($enc_batch_id != '0') { ?>
                        <a href="<?php echo site_url('iibfbcbf/admin/batch_candidates/index/'.url_encode($form_data[0]['batch_id'])); ?>" class="btn btn-danger">Back</a>
                      <?php }
                      else { ?>
                        <a href="<?php echo site_url('iibfbcbf/admin/batch_candidates'); ?>" class="btn btn-danger">Back</a>
                      <?php } ?>
                    </div>
                  </div>                  
                </div>
              </div>
              
              <div id="common_log_outer"></div>
            </div>					
          </div>
        </div>
				<?php $this->load->view('iibfbcbf/admin/inc_footerbar_admin'); ?>		
      </div>
    </div>
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>		
		
    <?php 
      $this->load->view('iibfbcbf/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_candidate_id, 'module_slug'=>'candidate_action', 'log_title'=>'Candidate Log'));
    ?>

    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>