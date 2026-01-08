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
      <?php $this->load->view('iibfbcbf/candidate/inc_sidebar_candidate'); ?>		
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('iibfbcbf/candidate/inc_topbar_candidate'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>View Profile</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/candidate/dashboard_candidate'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item active"> <strong>View Profile</strong></li>
            </ol>
          </div>
					<div class="col-lg-2"> </div>
        </div>			
				
				<div class="wrapper wrapper-content animated fadeInRight">
          <div class="row">
						<div class="col-lg-12">
							<div class="ibox">
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
                  </div>                  
                </div>
              </div>
            
              <div id="common_log_outer"></div>
            </div>					
          </div>
        </div>
        <?php $this->load->view('iibfbcbf/candidate/inc_footerbar_candidate'); ?>		
      </div>
    </div>
  
    <?php $this->load->view('iibfbcbf/inc_footer'); ?>
    <?php $this->load->view('iibfbcbf/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_candidate_id, 'module_slug'=>'candidate_action', 'log_title'=>'Candidate Log')); ?>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>