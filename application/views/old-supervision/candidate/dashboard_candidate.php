<!DOCTYPE html>
<html>
	<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php  if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('supervision/inc_header'); ?>    
  </head>
	<body class="fixed-sidebar">
		<?php //$this->load->view('supervision/common/inc_loader');  ?>
		
		<div id="wrapper">      
			<?php  $this->load->view('supervision/candidate/inc_sidebar_candidate'); ?>			
			<div id="page-wrapper" class="gray-bg">				
				<?php $this->load->view('supervision/candidate/inc_topbar_candidate'); exit; ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>
              <?php  if($this->session->userdata('SUPERVISION_USER_TYPE') == 'candidate') 
              { 
                $dispName = $this->supervision_model->getLoggedInUserDetails($this->session->userdata('SUPERVISION_LOGIN_ID'), $this->session->userdata('SUPERVISION_USER_TYPE'));
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
                <?php if($this->session->userdata('SUPERVISION_USER_TYPE') == 'candidate') 
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
                            $sub_data['candidate_data'] = $form_data; 
                            $this->load->view('supervision/common/inc_candidate_details_common',$sub_data);  
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                <?php }
               ?>
							</div>
						</div>
					</div>

          <?php if($this->session->userdata('SUPERVISION_USER_TYPE') == 'candidate') 
          { ?>
            <div class="row justify-content-md-center" style="display:none">
              <div class="col-lg-3">
                <a href="<?php echo site_url('supervision/candidate/centre_master_candidate'); ?>" target="_blank" style="color:#000;">
                  <div class="ibox text-center">
                    <div class="ibox-title"><h5>Total Centres</h5></div>
                    <div class="ibox-content"><h1 class="no-margins"><b><?php echo $total_centre_cnt; ?></b></h1></div>
                  </div>
                </a>
              </div>

              <div class="col-lg-3">
                <a href="<?php echo site_url('supervision/candidate/centre_master_candidate/index/1'); ?>" target="_blank" style="color:#000;">
                  <div class="ibox text-center">
                    <div class="ibox-title"><h5>Total Active Centres</h5></div>
                    <div class="ibox-content"><h1 class="no-margins"><b><?php echo $total_active_centre_cnt; ?></b></h1></div>
                  </div>
                </a>
              </div>

              <div class="col-lg-3">
                <a href="<?php echo site_url('supervision/candidate/centre_master_candidate/index/0'); ?>" target="_blank" style="color:#000;">
                  <div class="ibox text-center">
                    <div class="ibox-title"><h5>Total In-Active Centres</h5></div>
                    <div class="ibox-content"><h1 class="no-margins"><b><?php echo $total_in_active_centre_cnt; ?></b></h1></div>
                  </div>
                </a>
              </div>

              <div class="col-lg-3">
                <a href="<?php echo site_url('supervision/candidate/centre_master_candidate/index/2'); ?>" target="_blank" style="color:#000;">
                  <div class="ibox text-center">
                    <div class="ibox-title"><h5>Total In-Review Centres</h5></div>
                    <div class="ibox-content"><h1 class="no-margins"><b><?php echo $total_in_review_centre_cnt; ?></b></h1></div>
                  </div>
                </a>
              </div>

              <div class="col-lg-3">
                <a href="<?php echo site_url('supervision/candidate/centre_master_candidate/index/3'); ?>" target="_blank" style="color:#000;">
                  <div class="ibox text-center">
                    <div class="ibox-title"><h5>Total Re-Submitted Centres</h5></div>
                    <div class="ibox-content"><h1 class="no-margins"><b><?php echo $total_re_submitted_centre_cnt; ?></b></h1></div>
                  </div>
                </a>
              </div>


              <div class="col-lg-3">
                <a href="<?php echo site_url('supervision/candidate/training_batches_candidate'); ?>" target="_blank" style="color:#000;">
                  <div class="ibox text-center">
                    <div class="ibox-title"><h5>Total Batches</h5></div>
                    <div class="ibox-content"><h1 class="no-margins"><b><?php echo $total_batch_cnt; ?></b></h1></div>
                  </div>
                </a>
              </div>

              <div class="col-lg-3">
                <a href="<?php echo site_url('supervision/candidate/training_batches_candidate/index/1'); ?>" target="_blank" style="color:#000;">
                  <div class="ibox text-center">
                    <div class="ibox-title"><h5>Total Completed Batches</h5></div>
                    <div class="ibox-content"><h1 class="no-margins"><b><?php echo $total_completed_batch_cnt; ?></b></h1></div>
                  </div>
                </a>
              </div>

              <div class="col-lg-3">
                <a href="<?php echo site_url('supervision/candidate/training_batches_candidate/index/2'); ?>" target="_blank" style="color:#000;">
                  <div class="ibox text-center">
                    <div class="ibox-title"><h5>Total Ongoing Batches</h5></div>
                    <div class="ibox-content"><h1 class="no-margins"><b><?php echo $total_ongoing_batch_cnt; ?></b></h1></div>
                  </div>
                </a>
              </div>

              <div class="col-lg-3">
                <a href="<?php echo site_url('supervision/candidate/training_batches_candidate/index/3'); ?>" target="_blank" style="color:#000;">
                  <div class="ibox text-center">
                    <div class="ibox-title"><h5>Total Upcoming Batches</h5></div>
                    <div class="ibox-content"><h1 class="no-margins"><b><?php echo $total_upcoming_batch_cnt; ?></b></h1></div>
                  </div>
                </a>
              </div>


              <div class="col-lg-3">
                <a href="<?php echo site_url('supervision/candidate/faculty_master_candidate'); ?>" target="_blank" style="color:#000;">
                  <div class="ibox text-center">
                    <div class="ibox-title"><h5>Total Faculty</h5></div>
                    <div class="ibox-content"><h1 class="no-margins"><b><?php echo $total_faculty_cnt; ?></b></h1></div>
                  </div>
                </a>
              </div>

              <div class="col-lg-3">
                <a href="<?php echo site_url('supervision/candidate/faculty_master_candidate/index/1'); ?>" target="_blank" style="color:#000;">
                  <div class="ibox text-center">
                    <div class="ibox-title"><h5>Total Active Faculty</h5></div>
                    <div class="ibox-content"><h1 class="no-margins"><b><?php echo $total_active_faculty_cnt; ?></b></h1></div>
                  </div>
                </a>
              </div>

              <div class="col-lg-3">
                <a href="<?php echo site_url('supervision/candidate/faculty_master_candidate/index/0'); ?>" target="_blank" style="color:#000;">
                  <div class="ibox text-center">
                    <div class="ibox-title"><h5>Total In-Active Faculty</h5></div>
                    <div class="ibox-content"><h1 class="no-margins"><b><?php echo $total_in_active_faculty_cnt; ?></b></h1></div>
                  </div>
                </a>
              </div>

              <div class="col-lg-3">
                <a href="<?php echo site_url('supervision/candidate/faculty_master_candidate/index/2'); ?>" target="_blank" style="color:#000;">
                  <div class="ibox text-center">
                    <div class="ibox-title"><h5>Total In-Review Faculty</h5></div>
                    <div class="ibox-content"><h1 class="no-margins"><b><?php echo $total_in_review_faculty_cnt; ?></b></h1></div>
                  </div>
                </a>
              </div>

              <div class="col-lg-3">
                <a href="<?php echo site_url('supervision/candidate/faculty_master_candidate/index/3'); ?>" target="_blank" style="color:#000;">
                  <div class="ibox text-center">
                    <div class="ibox-title"><h5>Total Re-Submitted Faculty</h5></div>
                    <div class="ibox-content"><h1 class="no-margins"><b><?php echo $total_re_submitted_faculty_cnt; ?></b></h1></div>
                  </div>
                </a>
              </div>
            </div>
          <?php } ?>
        </div>				
				
				<?php $this->load->view('supervision/candidate/inc_footerbar_candidate'); ?>		
			</div>
		</div>
		<?php $this->load->view('supervision/inc_footer'); ?>		
		<?php $this->load->view('supervision/common/inc_bottom_script'); ?>		
	</body>
</html>