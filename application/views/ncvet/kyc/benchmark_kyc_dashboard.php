<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if (isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('ncvet/kyc/inc_header'); ?>

    <style>
    .dashboard-card {
      border-radius: 12px;
      padding: 20px;
      color: #fff;
      text-align: center;
      font-weight: bold;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      transition: transform 0.2s;
    }
    .dashboard-card:hover {
      transform: scale(1.05);
    }
    .card-blue { background-color: #5DADE2; }
    .card-green { background-color: #58D68D; }
    .card-orange { background-color: #F5B041; }
    .card-red { background-color: #EC7063; }
    .card-purple { background-color: #AF7AC5; }
  </style>

  </head>

  <body class="fixed-sidebar">
    <?php $this->load->view('ncvet/kyc/common/inc_loader'); ?>

    <div id="wrapper">
      <?php $this->load->view('ncvet/kyc/inc_sidebar_admin'); ?>
      <div id="page-wrapper" class="gray-bg">
        <?php $this->load->view('ncvet/kyc/inc_topbar_admin'); ?>

        <div class="row wrapper border-bottom white-bg page-heading">
          <div class="col-lg-10">
            <h2>Dashboard </h2>
            <ol class="breadcrumb">
              <li class="breadcrumb-item active"> <strong>Benchmark KYC Dashboard</strong></li>
            </ol>
          </div>
          <div class="col-lg-2"> </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
          <div class="row">
            <div class="col-lg-12">
              <div class="ibox float-e-margins text-centerx">
                <div class="ibox-title">
                  <?php $dispName = $this->Kyc_model->getLoggedInUserDetails($this->session->userdata('NCVET_KYC_LOGIN_ID'), $this->session->userdata('NCVET_KYC_ADMIN_TYPE')); ?>
                  <h2>Welcome To Benchmark KYC <?php echo $dispName['disp_sidebar_name']; ?> Dashboard</h2>
                </div>
                <div class="ibox-content">
                  <h4>
                    <?php echo date("d F, Y. h:i A"); ?>
                  </h4>
                </div>

                <div class="ibox-content">
                  <!-- <div class="container my-4"> -->
                    <h3 class="mb-4">New Candidates</h3>
                    
                    <!-- Row 1 -->
                    <div class="row g-3">
                      <div class="col-md-2 col-6">
                        <a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/new_candidate'); ?>"><div class="dashboard-card card-blue">
                          <div>Enrolled <br>Candidates</div>
                          <h2><?php echo $total_enrolled_new; ?></h2>
                        </div></a>
                      </div> 
                      <div class="col-md-2 col-6">
                        <a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/pending_candidate'); ?>"><div class="dashboard-card card-purple">
                          <div>Pending for <br>KYC</div>
                          <h2><?php echo $total_pending_new; ?></h2>
                        </div></a>
                      </div>
                      <div class="col-md-2 col-6">
                        <a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/recommend_candidate'); ?>"><div class="dashboard-card card-orange">
                          <div>Attend <br>Candidates</div>
                          <h2><?php echo $total_recommend_new; ?></h2>
                        </div></a>
                      </div>
                      <div class="col-md-2 col-6">
                        <a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/approved_candidate'); ?>"><div class="dashboard-card card-green">
                          <div><?php echo ($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '1' ? 'Recommended' : 'Approved' ); ?> <br>Candidates</div>
                          <h2><?php echo $total_approved_new; ?></h2>
                        </div></a>
                      </div>
                      <div class="col-md-2 col-6">
                        <a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/rejected_candidate'); ?>"><div class="dashboard-card card-red">
                          <div>Rejected <br>Candidates</div>
                          <h2><?php echo $total_rejected_new; ?></h2>
                        </div></a>
                      </div> 
                    </div>

                    <!-- Row 2 -->
                    <!-- <h5 class="mt-5">Re-KYC – Edit Member Section</h5> -->
                    <br>
                    <h3 class="mb-4">Re-KYC – Edit Member Section</h3>
                    <div class="row g-3">
                      <div class="col-md-2 col-6">
                        <a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/edit_candidate'); ?>"><div class="dashboard-card card-blue">
                          <div>Re-KYC <br>Candidates</div>
                          <h2><?php echo $total_enrolled_edited; ?></h2>
                        </div></a>
                      </div> 
                      <div class="col-md-2 col-6">
                        <a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/edit_pending_candidate'); ?>"><div class="dashboard-card card-purple">
                          <div>Pending for <br>Re-KYC</div>
                          <h2><?php echo $total_pending_edited; ?></h2>
                        </div></a>
                      </div>
                      <div class="col-md-2 col-6">
                        <a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/edit_recommend_candidate'); ?>"><div class="dashboard-card card-orange">
                          <div>Attend <br>Candidates</div>
                          <h2><?php echo $total_recommend_edited; ?></h2>
                        </div></a>
                      </div>
                      <div class="col-md-2 col-6">
                        <a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/approved_edit_candidate'); ?>"><div class="dashboard-card card-green">
                          <div><?php echo ($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '1' ? 'Recommended' : 'Approved' ); ?> <br>Candidates</div>
                          <h2><?php echo $total_approved_edited; ?></h2>
                        </div></a>
                      </div>
                      <div class="col-md-2 col-6">
                        <a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/rejected_edit_candidate'); ?>"><div class="dashboard-card card-red">
                          <div>Rejected <br>Candidates</div>
                          <h2><?php echo $total_rejected_edited; ?></h2>
                        </div></a>
                      </div> 
                    </div>

                    <!-- Row 3 -->
                    <!--<h5 class="mt-5">Scribe Candidate KYC</h5>
                    <div class="row g-3">
                      <div class="col-md-2 col-6">
                        <div class="dashboard-card card-blue">
                          <div>Total Scribe Records</div>
                          <h2>5</h2>
                        </div>
                      </div>
                      <div class="col-md-2 col-6">
                        <div class="dashboard-card card-orange">
                          <div>Total Recommended</div>
                          <h2>16</h2>
                        </div>
                      </div>
                      <div class="col-md-2 col-6">
                        <div class="dashboard-card card-red">
                          <div>Total Rejected</div>
                          <h2>16</h2>
                        </div>
                      </div>
                      <div class="col-md-2 col-6">
                        <div class="dashboard-card card-purple">
                          <div>Pending for KYC</div>
                          <h2>16</h2>
                        </div>
                      </div>
                    </div>-->

                  <!-- </div> -->

                </div>
              </div>

              <?php 
              /*
                // START : NCVET TOTAL NEW REGISTRATIONS //
                $this->db->where(" kyc_eligible_date != '' AND kyc_eligible_date IS NOT NULL AND kyc_eligible_date != '0000-00-00 00:00:00' ");
                $total_new_reg = $this->master_model->getRecords('ncvet_candidates', array('regnumber !=' => '', 'is_deleted'=>'0'), 'regnumber');
                $total_new_reg_cnt = count($total_new_reg);
                // END : NCVET TOTAL NEW REGISTRATIONS //
                
                // START : NCVET KYC APPROVED //
                $kyc_approved_new_reg = $this->master_model->getRecords('ncvet_kyc_log_data', array('module_name'=>'ncvet', 'member_type'=>'new', 'login_type'=>'2', 'module_slug' => 'kyc_approver_Approved'), 'DISTINCT(pk_id)');
                $kyc_approved_new_reg_cnt = count($kyc_approved_new_reg);
                // END : NCVET KYC APPROVED //

                // START : NCVET APPROVER PENDING //
                $this->db->where(" pk_id NOT IN (SELECT DISTINCT(pk_id) FROM ncvet_kyc_log_data WHERE module_name = 'ncvet' AND member_type = 'new' AND login_type = '2' AND (module_slug = 'kyc_approver_Approved' OR module_slug = 'kyc_approver_Rejected'))");
                $approver_pending_new_reg = $this->master_model->getRecords('ncvet_kyc_log_data', array('module_name'=>'ncvet', 'member_type'=>'new', 'login_type'=>'1', 'module_slug' => 'kyc_recommender_Approved'), 'DISTINCT(pk_id)');
                $approver_pending_new_reg_cnt = count($approver_pending_new_reg);
                // END : NCVET APPROVER PENDING //

                // START : NCVET RECOMMENDER REJECTED //
                $recommender_rejected_new_reg = $this->master_model->getRecords('ncvet_kyc_log_data', array('module_name'=>'ncvet', 'member_type'=>'new', 'login_type'=>'1', 'module_slug' => 'kyc_recommender_Rejected'), 'DISTINCT(pk_id)');
                $recommender_rejected_new_reg_cnt = count($recommender_rejected_new_reg);
                // END : NCVET RECOMMENDER REJECTED //

                // START : NCVET APPROVER REJECTED //
                $approver_rejected_new_reg = $this->master_model->getRecords('ncvet_kyc_log_data', array('module_name'=>'ncvet', 'member_type'=>'new', 'login_type'=>'2', 'module_slug' => 'kyc_approver_Rejected'), 'DISTINCT(pk_id)');
                $approver_rejected_new_reg_cnt = count($approver_rejected_new_reg);
                // END : NCVET APPROVER REJECTED //

                // START : NCVET RECOMMENDER PENDING //
                $recommender_pending_new_reg_cnt = $total_new_reg_cnt - $kyc_approved_new_reg_cnt - $approver_pending_new_reg_cnt - $recommender_rejected_new_reg_cnt - $approver_rejected_new_reg_cnt;
                // END : NCVET RECOMMENDER PENDING // 
                

                // START : NCVET TOTAL EDIT PROFILE //
                $this->db->where(" kyc_eligible_date != '' AND kyc_eligible_date IS NOT NULL AND kyc_eligible_date != '0000-00-00 00:00:00' ");
                $this->db->where(" img_ediited_on != '' AND img_ediited_on IS NOT NULL AND img_ediited_on != '0000-00-00 00:00:00' ");
                $total_edited = $this->master_model->getRecords('ncvet_candidates', array('regnumber !=' => '', 'is_deleted'=>'0'));
                $total_edited_cnt = count($total_edited);
                // END : NCVET TOTAL EDIT PROFILE //
                
                // START : NCVET KYC APPROVED //
                $kyc_approved_edited = $this->master_model->getRecords('ncvet_kyc_log_data', array('module_name'=>'ncvet', 'member_type'=>'edited', 'login_type'=>'2', 'module_slug' => 'kyc_approver_Approved'), 'DISTINCT(pk_id)');
                $kyc_approved_edited_cnt = count($kyc_approved_edited);
                // END : NCVET KYC APPROVED //

                // START : NCVET APPROVER PENDING //
                $this->db->where(" pk_id NOT IN (SELECT DISTINCT(pk_id) FROM ncvet_kyc_log_data WHERE module_name = 'ncvet' AND member_type = 'edited' AND login_type = '2' AND (module_slug = 'kyc_approver_Approved' OR module_slug = 'kyc_approver_Rejected'))");
                $approver_pending_edited = $this->master_model->getRecords('ncvet_kyc_log_data', array('module_name'=>'ncvet', 'member_type'=>'edited', 'login_type'=>'1', 'module_slug' => 'kyc_recommender_Approved'), 'DISTINCT(pk_id)');
                $approver_pending_edited_cnt = count($approver_pending_edited);
                // END : NCVET APPROVER PENDING //

                // START : NCVET RECOMMENDER REJECTED //
                $recommender_rejected_edited = $this->master_model->getRecords('ncvet_kyc_log_data', array('module_name'=>'ncvet', 'member_type'=>'edited', 'login_type'=>'1', 'module_slug' => 'kyc_recommender_Rejected'), 'DISTINCT(pk_id)');
                $recommender_rejected_edited_cnt = count($recommender_rejected_edited);
                // END : NCVET RECOMMENDER REJECTED //

                // START : NCVET APPROVER REJECTED //
                $approver_rejected_edited = $this->master_model->getRecords('ncvet_kyc_log_data', array('module_name'=>'ncvet', 'member_type'=>'edited', 'login_type'=>'2', 'module_slug' => 'kyc_approver_Rejected'), 'DISTINCT(pk_id)');
                $approver_rejected_edited_cnt = count($approver_rejected_edited);
                // END : NCVET APPROVER REJECTED //

                // START : NCVET RECOMMENDER PENDING //
                $recommender_pending_edited_cnt = $total_edited_cnt - $kyc_approved_edited_cnt - $approver_pending_edited_cnt - $recommender_rejected_edited_cnt - $approver_rejected_edited_cnt;
                // END : NCVET RECOMMENDER PENDING //

                */
              ?> 

              <!-- <div class="ibox float-e-margins text-centerx">
                <div class="ibox-content">
                  <table class="table table-bordered mb-0">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th class="text-center"><b>Total Count (A)</b></th>
                        <th class="text-center"><b>KYC Approved (B)</b></th>
                        <th class="text-center"><b>Recommender Pending (C)</b></th>
                        <th class="text-center"><b>Approver Pending (D)</b></th>
                        <th class="text-center"><b>Recommender Rejected (E)</b></th>
                        <th class="text-center"><b>Approver Rejected (F)</b></th>
                      </tr>
                    </thead>
                    <tbody>                      
                      <tr>
                        <td><b>NCVET NM (New Registrations)</b></td>
                        <td class="text-center"><?php //echo $total_new_reg_cnt; ?></td> 
                        <td class="text-center"><?php //echo $kyc_approved_new_reg_cnt; ?></td>                    
                        <td class="text-center"><?php //echo $recommender_pending_new_reg_cnt; ?></td>                        
                        <td class="text-center"><?php //echo $approver_pending_new_reg_cnt; ?></td>     
                        <td class="text-center"><?php //echo $recommender_rejected_new_reg_cnt; ?></td> 
                        <td class="text-center"><?php //echo $approver_rejected_new_reg_cnt; ?></td> 
                      </tr>             
                      <tr>
                        <td><b>NCVET NM (Profile Edited)</b></td>
                        <td class="text-center"><?php //echo $total_edited_cnt; ?></td> 
                        <td class="text-center"><?php //echo $kyc_approved_edited_cnt; ?></td>                 
                        <td class="text-center"><?php //echo $recommender_pending_edited_cnt; ?></td>                        
                        <td class="text-center"><?php //echo $approver_pending_edited_cnt; ?></td>         
                        <td class="text-center"><?php //echo $recommender_rejected_edited_cnt; ?></td> 
                        <td class="text-center"><?php //echo $approver_rejected_edited_cnt; ?></td> 
                      </tr> 
                    </tbody>
                  </table>
                  <p class="note mt-1">A = B + C + D + E + F</p>
                </div>
              </div> -->
              

            </div>
          </div>
        </div>

        <?php $this->load->view('ncvet/kyc/inc_footerbar_admin'); ?>
      </div>
    </div>
    <?php $this->load->view('ncvet/kyc/inc_footer'); ?>
    <?php $this->load->view('ncvet/kyc/common/inc_bottom_script'); ?>
  </body>
</html>