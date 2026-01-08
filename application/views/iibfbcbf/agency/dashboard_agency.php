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
						<h2>
              <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'centre') { echo 'Dashboard'; } 
              else if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency') 
              { 
                $dispName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->session->userdata('IIBF_BCBF_LOGIN_ID'), $this->session->userdata('IIBF_BCBF_USER_TYPE'));
                echo 'Training Institute Profile - '.$dispName['disp_name']; 
              } ?>
            </h2>
					</div>
					<div class="col-lg-2"> </div>
				</div>

        <div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox float-e-margins text-centerx">
                <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency') 
                { ?>
                  <div class="ibox-title bg_light_blue">
                    <h5>Agency Dashboard</h5>
                    <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                  </div>
                  <div class="ibox-content">
                    <div class="table-responsive">
                      <table class="table table-bordered custom_inner_tbl" style="width:100%">
                        <tbody>
                          <?php 
                            $sub_data['agency_data'] = $form_data; 
                            $this->load->view('iibfbcbf/common/inc_agency_details_common',$sub_data);  
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                <?php }
                else if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'centre') 
                { ?>
                  <div class="ibox-title"><h2>Welcome To <?php echo ucfirst($this->session->userdata('IIBF_BCBF_USER_TYPE')); ?> Dashboard</h2></div>
                  <div class="ibox-content">
                    <h4>
                      <?php echo date("d F, Y. h:i A"); /* echo '<br>'.get_ip_address();  */ ?>	
                    </h4>
                  </div> 
                <?php } ?>
							</div>
						</div>
					</div>

          <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency') 
          { ?>
            <div class="row justify-content-md-centerx">
              <div class="col-lg-4">
                <div class="ibox ">
                  <div class="ibox-title">                    
                    <h5>Total Centres : <?php echo $total_centre_cnt; ?></h5>
                    <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                  </div>
                  <div class="ibox-content">
                    <table class="table table-bordered mb-0">
                      <tbody>
                        <tr>
                          <td><b>Total Centres</b></td>
                          <td><a href="<?php echo site_url('iibfbcbf/agency/centre_master_agency'); ?>" target="_blank" class="btn btn-outline btn-info btn-sm" style="min-width:60px;"><b><?php echo $total_centre_cnt; ?></b></a></td>
                        </tr>
                        <tr>
                          <td><b>Total Active Centres</b></td>
                          <td><a href="<?php echo site_url('iibfbcbf/agency/centre_master_agency/index/1'); ?>" target="_blank" class="btn btn-outline btn-info btn-sm" style="min-width:60px;"><b><?php echo $total_active_centre_cnt; ?></b></a></td>
                        </tr>
                        <tr>
                          <td><b>Total In-Active Centres</b></td>
                          <td><a href="<?php echo site_url('iibfbcbf/agency/centre_master_agency/index/0'); ?>" target="_blank" class="btn btn-outline btn-info btn-sm" style="min-width:60px;"><b><?php echo $total_in_active_centre_cnt; ?></b></a></td>
                        </tr>
                        <tr>
                          <td><b>Total In-Review Centres</b></td>
                          <td><a href="<?php echo site_url('iibfbcbf/agency/centre_master_agency/index/2'); ?>" target="_blank" class="btn btn-outline btn-info btn-sm" style="min-width:60px;"><b><?php echo $total_in_review_centre_cnt; ?></b></a></td>
                        </tr>
                        <tr>
                          <td><b>Total Re-Submitted Centres</b></td>
                          <td><a href="<?php echo site_url('iibfbcbf/agency/centre_master_agency/index/3'); ?>" target="_blank" class="btn btn-outline btn-info btn-sm" style="min-width:60px;"><b><?php echo $total_re_submitted_centre_cnt; ?></b></a></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>   
              
              <?php if(count($form_data) > 0 && ($form_data[0]['allow_exam_types'] == 'Bulk/Individual' || $form_data[0]['allow_exam_types'] == 'Hybrid'))
              { ?>
                <div class="col-lg-4">
                  <div class="ibox ">
                    <div class="ibox-title">                    
                      <h5>Total Batches : <?php echo $total_batch_cnt; ?></h5>
                      <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                    </div>
                    <div class="ibox-content">
                      <table class="table table-bordered mb-0">
                        <tbody>
                          <tr>
                            <td><b>Total Batches</b></td>
                            <td><a href="<?php echo site_url('iibfbcbf/agency/training_batches_agency'); ?>" target="_blank" class="btn btn-outline btn-info btn-sm" style="min-width:60px;"><b><?php echo $total_batch_cnt; ?></b></a></td>
                          </tr>
                          <tr>
                            <td><b>Total Completed Batches</b></td>
                            <td><a href="<?php echo site_url('iibfbcbf/agency/training_batches_agency/index/1'); ?>" target="_blank" class="btn btn-outline btn-info btn-sm" style="min-width:60px;"><b><?php echo $total_completed_batch_cnt; ?></b></a></td>
                          </tr>
                          <tr>
                            <td><b>Total Ongoing Batches</b></td>
                            <td><a href="<?php echo site_url('iibfbcbf/agency/training_batches_agency/index/2'); ?>" target="_blank" class="btn btn-outline btn-info btn-sm" style="min-width:60px;"><b><?php echo $total_ongoing_batch_cnt; ?></b></a></td>
                          </tr>
                          <tr>
                            <td><b>Total Upcoming Batches</b></td>
                            <td><a href="<?php echo site_url('iibfbcbf/agency/training_batches_agency/index/3'); ?>" target="_blank" class="btn btn-outline btn-info btn-sm" style="min-width:60px;"><b><?php echo $total_upcoming_batch_cnt; ?></b></a></td>
                          </tr>
                          <tr>
                            <td><b>Total Rejected / Hold / Cancelled Batches </b></td>
                            <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $total_rejected_hold_cancelled_batch_cnt; ?></b></a></td>
                          </tr>  
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="col-lg-4">
                  <div class="ibox ">
                    <div class="ibox-title">                    
                      <h5>Total Faculty : <?php echo $total_faculty_cnt; ?></h5>
                      <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                    </div>
                    <div class="ibox-content">
                      <table class="table table-bordered mb-0">
                        <tbody>
                          <tr>
                            <td><b>Total Faculty</b></td>
                            <td><a href="<?php echo site_url('iibfbcbf/agency/faculty_master_agency'); ?>" target="_blank" class="btn btn-outline btn-info btn-sm" style="min-width:60px;"><b><?php echo $total_faculty_cnt; ?></b></a></td>
                          </tr>
                          <tr>
                            <td><b>Total Active Faculty</b></td>
                            <td><a href="<?php echo site_url('iibfbcbf/agency/faculty_master_agency/index/1'); ?>" target="_blank" class="btn btn-outline btn-info btn-sm" style="min-width:60px;"><b><?php echo $total_active_faculty_cnt; ?></b></a></td>
                          </tr>
                          <tr>
                            <td><b>Total In-Active Faculty</b></td>
                            <td><a href="<?php echo site_url('iibfbcbf/agency/faculty_master_agency/index/0'); ?>" target="_blank" class="btn btn-outline btn-info btn-sm" style="min-width:60px;"><b><?php echo $total_in_active_faculty_cnt; ?></b></a></td>
                          </tr>
                          <tr>
                            <td><b>Total In-Review Faculty</b></td>
                            <td><a href="<?php echo site_url('iibfbcbf/agency/faculty_master_agency/index/2'); ?>" target="_blank" class="btn btn-outline btn-info btn-sm" style="min-width:60px;"><b><?php echo $total_in_review_faculty_cnt; ?></b></a></td>
                          </tr>
                          <tr>
                            <td><b>Total Re-Submitted Faculty</b></td>
                            <td><a href="<?php echo site_url('iibfbcbf/agency/faculty_master_agency/index/3'); ?>" target="_blank" class="btn btn-outline btn-info btn-sm" style="min-width:60px;"><b><?php echo $total_re_submitted_faculty_cnt; ?></b></a></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="col-lg-4">
                  <div class="ibox ">
                    <div class="ibox-title">                    
                      <h5>Total Registered Candidates : <?php echo $total_candidate_cnt; ?></h5>
                      <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                    </div>
                    <div class="ibox-content">
                      <table class="table table-bordered mb-0">
                        <tbody>
                          <tr>
                            <td><b>Total Registered Candidates</b></td>
                            <td><a href="<?php echo site_url('iibfbcbf/agency/batch_candidates_agency'); ?>" target="_blank" class="btn btn-outline btn-info btn-sm" style="min-width:60px;"><b><?php echo $total_candidate_cnt; ?></b></a></td>
                          </tr>
                          <tr>
                            <td><b>Total Training Completed Candidates</b></td>
                            <td><a href="<?php echo site_url('iibfbcbf/agency/batch_candidates_agency/index/0/1'); ?>" target="_blank" class="btn btn-outline btn-info btn-sm" style="min-width:60px;"><b><?php echo $total_training_completed_candidate_cnt; ?></b></a></td>
                          </tr>
                          <tr>
                            <td><b>Total Hold Candidates</b></td>
                            <td><a href="<?php echo site_url('iibfbcbf/agency/batch_candidates_agency/index/0/2'); ?>" target="_blank" class="btn btn-outline btn-info btn-sm" style="min-width:60px;"><b><?php echo $total_hold_candidate_cnt; ?></b></a></td>
                          </tr>
                          <tr>
                            <td><b>Total Exam Applied Candidates</b></td>
                            <td><a href="<?php echo site_url('iibfbcbf/agency/batch_candidates_agency/index/0/3'); ?>" target="_blank" class="btn btn-outline btn-info btn-sm" style="min-width:60px;"><b><?php echo $total_exam_applied_candidate_cnt; ?></b></a></td>
                          </tr>                        
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          <?php } ?>
        </div>				
				
				<?php $this->load->view('iibfbcbf/agency/inc_footerbar_agency'); ?>		
			</div>
		</div>
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>		
		<?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>		
	</body>
</html>