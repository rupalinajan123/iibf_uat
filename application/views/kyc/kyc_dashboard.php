<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if (isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('kyc/inc_header'); ?>
  </head>

  <body class="fixed-sidebar">
    <?php $this->load->view('kyc/common/inc_loader'); ?>

    <div id="wrapper">
      <?php $this->load->view('kyc/inc_sidebar_admin'); ?>
      <div id="page-wrapper" class="gray-bg">
        <?php $this->load->view('kyc/inc_topbar_admin'); ?>

        <div class="row wrapper border-bottom white-bg page-heading">
          <div class="col-lg-10">
            <h2>Dashboard </h2>
            <ol class="breadcrumb">
              <li class="breadcrumb-item active"> <strong>Dashboard</strong></li>
            </ol>
          </div>
          <div class="col-lg-2"> </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
          <div class="row">
            <div class="col-lg-12">
              <div class="ibox float-e-margins text-centerx">
                <div class="ibox-title">
                  <?php $dispName = $this->Kyc_model->getLoggedInUserDetails($this->session->userdata('KYC_LOGIN_ID'), $this->session->userdata('KYC_ADMIN_TYPE')); ?>
                  <h2>Welcome To KYC <?php echo $dispName['disp_sidebar_name']; ?> Dashboard</h2>
                </div>
                <div class="ibox-content">
                  <h4>
                    <?php echo date("d F, Y. h:i A"); ?>
                  </h4>
                </div>
              </div>

              <?php 
                /* START : BCBF TOTAL NEW REGISTRATIONS */
                $this->db->where(" kyc_eligible_date != '' AND kyc_eligible_date IS NOT NULL AND kyc_eligible_date != '0000-00-00 00:00:00' ");
                $total_new_reg = $this->master_model->getRecords('iibfbcbf_batch_candidates', array('regnumber !=' => '', 'hold_release_status'=>'3', 'is_deleted'=>'0'), 'regnumber');
                $total_new_reg_cnt = count($total_new_reg);
                /* END : BCBF TOTAL NEW REGISTRATIONS */
                
                /* START : BCBF KYC APPROVED */
                $kyc_approved_new_reg = $this->master_model->getRecords('kyc_log_data', array('module_name'=>'bcbf', 'member_type'=>'new', 'login_type'=>'2', 'module_slug' => 'kyc_approver_Approved'), 'DISTINCT(pk_id)');
                $kyc_approved_new_reg_cnt = count($kyc_approved_new_reg);
                /* END : BCBF KYC APPROVED */

                /* START : BCBF APPROVER PENDING */
                $this->db->where(" pk_id NOT IN (SELECT DISTINCT(pk_id) FROM kyc_log_data WHERE module_name = 'bcbf' AND member_type = 'new' AND login_type = '2' AND (module_slug = 'kyc_approver_Approved' OR module_slug = 'kyc_approver_Rejected'))");
                $approver_pending_new_reg = $this->master_model->getRecords('kyc_log_data', array('module_name'=>'bcbf', 'member_type'=>'new', 'login_type'=>'1', 'module_slug' => 'kyc_recommender_Approved'), 'DISTINCT(pk_id)');
                $approver_pending_new_reg_cnt = count($approver_pending_new_reg);
                /* END : BCBF APPROVER PENDING */

                /* START : BCBF RECOMMENDER REJECTED */
                $recommender_rejected_new_reg = $this->master_model->getRecords('kyc_log_data', array('module_name'=>'bcbf', 'member_type'=>'new', 'login_type'=>'1', 'module_slug' => 'kyc_recommender_Rejected'), 'DISTINCT(pk_id)');
                $recommender_rejected_new_reg_cnt = count($recommender_rejected_new_reg);
                /* END : BCBF RECOMMENDER REJECTED */

                /* START : BCBF APPROVER REJECTED */
                $approver_rejected_new_reg = $this->master_model->getRecords('kyc_log_data', array('module_name'=>'bcbf', 'member_type'=>'new', 'login_type'=>'2', 'module_slug' => 'kyc_approver_Rejected'), 'DISTINCT(pk_id)');
                $approver_rejected_new_reg_cnt = count($approver_rejected_new_reg);
                /* END : BCBF APPROVER REJECTED */

                /* START : BCBF RECOMMENDER PENDING */
                $recommender_pending_new_reg_cnt = $total_new_reg_cnt - $kyc_approved_new_reg_cnt - $approver_pending_new_reg_cnt - $recommender_rejected_new_reg_cnt - $approver_rejected_new_reg_cnt;
                /* END : BCBF RECOMMENDER PENDING */
                
                

                /* START : BCBF TOTAL EDIT PROFILE */
                $this->db->where(" kyc_eligible_date != '' AND kyc_eligible_date IS NOT NULL AND kyc_eligible_date != '0000-00-00 00:00:00' ");
                $this->db->where(" img_ediited_on != '' AND img_ediited_on IS NOT NULL AND img_ediited_on != '0000-00-00 00:00:00' ");
                $total_edited = $this->master_model->getRecords('iibfbcbf_batch_candidates', array('regnumber !=' => '', 'hold_release_status'=>'3', 'is_deleted'=>'0'));
                $total_edited_cnt = count($total_edited);
                /* END : BCBF TOTAL EDIT PROFILE */
                
                /* START : BCBF KYC APPROVED */
                $kyc_approved_edited = $this->master_model->getRecords('kyc_log_data', array('module_name'=>'bcbf', 'member_type'=>'edited', 'login_type'=>'2', 'module_slug' => 'kyc_approver_Approved'), 'DISTINCT(pk_id)');
                $kyc_approved_edited_cnt = count($kyc_approved_edited);
                /* END : BCBF KYC APPROVED */

                /* START : BCBF APPROVER PENDING */
                $this->db->where(" pk_id NOT IN (SELECT DISTINCT(pk_id) FROM kyc_log_data WHERE module_name = 'bcbf' AND member_type = 'edited' AND login_type = '2' AND (module_slug = 'kyc_approver_Approved' OR module_slug = 'kyc_approver_Rejected'))");
                $approver_pending_edited = $this->master_model->getRecords('kyc_log_data', array('module_name'=>'bcbf', 'member_type'=>'edited', 'login_type'=>'1', 'module_slug' => 'kyc_recommender_Approved'), 'DISTINCT(pk_id)');
                $approver_pending_edited_cnt = count($approver_pending_edited);
                /* END : BCBF APPROVER PENDING */

                /* START : BCBF RECOMMENDER REJECTED */
                $recommender_rejected_edited = $this->master_model->getRecords('kyc_log_data', array('module_name'=>'bcbf', 'member_type'=>'edited', 'login_type'=>'1', 'module_slug' => 'kyc_recommender_Rejected'), 'DISTINCT(pk_id)');
                $recommender_rejected_edited_cnt = count($recommender_rejected_edited);
                /* END : BCBF RECOMMENDER REJECTED */

                /* START : BCBF APPROVER REJECTED */
                $approver_rejected_edited = $this->master_model->getRecords('kyc_log_data', array('module_name'=>'bcbf', 'member_type'=>'edited', 'login_type'=>'2', 'module_slug' => 'kyc_approver_Rejected'), 'DISTINCT(pk_id)');
                $approver_rejected_edited_cnt = count($approver_rejected_edited);
                /* END : BCBF APPROVER REJECTED */

                /* START : BCBF RECOMMENDER PENDING */
                $recommender_pending_edited_cnt = $total_edited_cnt - $kyc_approved_edited_cnt - $approver_pending_edited_cnt - $recommender_rejected_edited_cnt - $approver_rejected_edited_cnt;
                /* END : BCBF RECOMMENDER PENDING */
              ?>


              <?php 
                /* START : DRA TOTAL NEW REGISTRATIONS */
                $this->db->where_in('excode', array(45, 57, 1036));
                $this->db->where(" kyc_eligible_date != '' AND kyc_eligible_date IS NOT NULL AND kyc_eligible_date != '0000-00-00 00:00:00' ");
                $dra_total_new_reg = $this->master_model->getRecords('dra_members', array('regnumber !=' => '', 'hold_release'=>'Release', 'isdeleted'=>'0'), 'regnumber');                
                $dra_total_new_reg_cnt = count($dra_total_new_reg);
                
                /* END : DRA TOTAL NEW REGISTRATIONS */
                
                /* START : DRA KYC APPROVED */
                $dra_kyc_approved_new_reg = $this->master_model->getRecords('kyc_log_data', array('module_name'=>'dra', 'member_type'=>'new', 'login_type'=>'2', 'module_slug' => 'kyc_approver_Approved'), 'DISTINCT(pk_id)');
                $dra_kyc_approved_new_reg_cnt = count($dra_kyc_approved_new_reg);
                /* END : DRA KYC APPROVED */

                /* START : DRA APPROVER PENDING */
                $this->db->where(" pk_id NOT IN (SELECT DISTINCT(pk_id) FROM kyc_log_data WHERE module_name = 'dra' AND member_type = 'new' AND login_type = '2' AND (module_slug = 'kyc_approver_Approved' OR module_slug = 'kyc_approver_Rejected'))");
                $dra_approver_pending_new_reg = $this->master_model->getRecords('kyc_log_data', array('module_name'=>'dra', 'member_type'=>'new', 'login_type'=>'1', 'module_slug' => 'kyc_recommender_Approved'), 'DISTINCT(pk_id)');
                $dra_approver_pending_new_reg_cnt = count($dra_approver_pending_new_reg);
                /* END : DRA APPROVER PENDING */

                /* START : DRA RECOMMENDER REJECTED */
                $dra_recommender_rejected_new_reg = $this->master_model->getRecords('kyc_log_data', array('module_name'=>'dra', 'member_type'=>'new', 'login_type'=>'1', 'module_slug' => 'kyc_recommender_Rejected'), 'DISTINCT(pk_id)');
                $dra_recommender_rejected_new_reg_cnt = count($dra_recommender_rejected_new_reg);
                /* END : DRA RECOMMENDER REJECTED */

                /* START : DRA APPROVER REJECTED */
                $dra_approver_rejected_new_reg = $this->master_model->getRecords('kyc_log_data', array('module_name'=>'dra', 'member_type'=>'new', 'login_type'=>'2', 'module_slug' => 'kyc_approver_Rejected'), 'DISTINCT(pk_id)');
                $dra_approver_rejected_new_reg_cnt = count($dra_approver_rejected_new_reg);
                /* END : DRA APPROVER REJECTED */

                /* START : DRA RECOMMENDER PENDING */
                $dra_recommender_pending_new_reg_cnt = $dra_total_new_reg_cnt - $dra_kyc_approved_new_reg_cnt - $dra_approver_pending_new_reg_cnt - $dra_recommender_rejected_new_reg_cnt - $dra_approver_rejected_new_reg_cnt;
                /* END : DRA RECOMMENDER PENDING */
                
                

                /* START : DRA TOTAL EDIT PROFILE */
                $this->db->where(" kyc_eligible_date != '' AND kyc_eligible_date IS NOT NULL AND kyc_eligible_date != '0000-00-00 00:00:00' ");
                $this->db->where(" img_ediited_on != '' AND img_ediited_on IS NOT NULL AND img_ediited_on != '0000-00-00 00:00:00' ");
                $dra_total_edited = $this->master_model->getRecords('dra_members', array('regnumber !=' => '', 'hold_release'=>'Release', 'isdeleted'=>'0'));
                $dra_total_edited_cnt = count($dra_total_edited);
                /* END : DRA TOTAL EDIT PROFILE */
                
                /* START : DRA KYC APPROVED */
                $dra_kyc_approved_edited = $this->master_model->getRecords('kyc_log_data', array('module_name'=>'dra', 'member_type'=>'edited', 'login_type'=>'2', 'module_slug' => 'kyc_approver_Approved'), 'DISTINCT(pk_id)');
                $dra_kyc_approved_edited_cnt = count($dra_kyc_approved_edited);
                /* END : DRA KYC APPROVED */

                /* START : DRA APPROVER PENDING */
                $this->db->where(" pk_id NOT IN (SELECT DISTINCT(pk_id) FROM kyc_log_data WHERE module_name = 'dra' AND member_type = 'edited' AND login_type = '2' AND (module_slug = 'kyc_approver_Approved' OR module_slug = 'kyc_approver_Rejected'))");
                $dra_approver_pending_edited = $this->master_model->getRecords('kyc_log_data', array('module_name'=>'dra', 'member_type'=>'edited', 'login_type'=>'1', 'module_slug' => 'kyc_recommender_Approved'), 'DISTINCT(pk_id)');
                $dra_approver_pending_edited_cnt = count($dra_approver_pending_edited);
                /* END : DRA APPROVER PENDING */

                /* START : DRA RECOMMENDER REJECTED */
                $dra_recommender_rejected_edited = $this->master_model->getRecords('kyc_log_data', array('module_name'=>'dra', 'member_type'=>'edited', 'login_type'=>'1', 'module_slug' => 'kyc_recommender_Rejected'), 'DISTINCT(pk_id)');
                $dra_recommender_rejected_edited_cnt = count($dra_recommender_rejected_edited);
                /* END : DRA RECOMMENDER REJECTED */

                /* START : DRA APPROVER REJECTED */
                $dra_approver_rejected_edited = $this->master_model->getRecords('kyc_log_data', array('module_name'=>'dra', 'member_type'=>'edited', 'login_type'=>'2', 'module_slug' => 'kyc_approver_Rejected'), 'DISTINCT(pk_id)');
                $dra_approver_rejected_edited_cnt = count($dra_approver_rejected_edited);
                /* END : DRA APPROVER REJECTED */

                /* START : DRA RECOMMENDER PENDING */
                $dra_recommender_pending_edited_cnt = $dra_total_edited_cnt - $dra_kyc_approved_edited_cnt - $dra_approver_pending_edited_cnt - $dra_recommender_rejected_edited_cnt - $dra_approver_rejected_edited_cnt;
                /* END : DRA RECOMMENDER PENDING */
              ?>
                
              <div class="ibox float-e-margins text-centerx">
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
                        <td><b>BCBF NM (New Registrations)</b></td>
                        <td class="text-center"><?php echo $total_new_reg_cnt; ?></td><!-- Total New Registrations -->
                        <td class="text-center"><?php echo $kyc_approved_new_reg_cnt; ?></td><!-- KYC Approved -->                       
                        <td class="text-center"><?php echo $recommender_pending_new_reg_cnt; ?></td><!-- Recommender Pending -->                       
                        <td class="text-center"><?php echo $approver_pending_new_reg_cnt; ?></td><!-- Approver Pending -->          
                        <td class="text-center"><?php echo $recommender_rejected_new_reg_cnt; ?></td><!-- Recommender Rejected -->
                        <td class="text-center"><?php echo $approver_rejected_new_reg_cnt; ?></td><!-- Approver Rejected -->
                      </tr>             
                      <tr>
                        <td><b>BCBF NM (Profile Edited)</b></td>
                        <td class="text-center"><?php echo $total_edited_cnt; ?></td><!-- Total Profile Edited -->
                        <td class="text-center"><?php echo $kyc_approved_edited_cnt; ?></td><!-- KYC Approved -->                       
                        <td class="text-center"><?php echo $recommender_pending_edited_cnt; ?></td><!-- Recommender Pending -->                       
                        <td class="text-center"><?php echo $approver_pending_edited_cnt; ?></td><!-- Approver Pending -->          
                        <td class="text-center"><?php echo $recommender_rejected_edited_cnt; ?></td><!-- Recommender Rejected -->
                        <td class="text-center"><?php echo $approver_rejected_edited_cnt; ?></td><!-- Approver Rejected -->
                      </tr>


                      <tr>
                        <td><b>DRA NM (New Registrations)</b></td>
                        <td class="text-center"><?php echo $dra_total_new_reg_cnt; ?></td><!-- DRA Total New Registrations -->
                        <td class="text-center"><?php echo $dra_kyc_approved_new_reg_cnt; ?></td><!-- DRA KYC Approved -->                       
                        <td class="text-center"><?php echo $dra_recommender_pending_new_reg_cnt; ?></td><!-- DRA Recommender Pending -->                       
                        <td class="text-center"><?php echo $dra_approver_pending_new_reg_cnt; ?></td><!-- DRA Approver Pending -->          
                        <td class="text-center"><?php echo $dra_recommender_rejected_new_reg_cnt; ?></td><!-- DRA Recommender Rejected -->
                        <td class="text-center"><?php echo $dra_approver_rejected_new_reg_cnt; ?></td><!-- DRA Approver Rejected -->
                      </tr>             
                      <tr>
                        <td><b>DRA NM (Profile Edited)</b></td>
                        <td class="text-center"><?php echo $dra_total_edited_cnt; ?></td><!-- DRA Total Profile Edited -->
                        <td class="text-center"><?php echo $dra_kyc_approved_edited_cnt; ?></td><!-- DRA KYC Approved -->                       
                        <td class="text-center"><?php echo $dra_recommender_pending_edited_cnt; ?></td><!-- DRA Recommender Pending -->                       
                        <td class="text-center"><?php echo $dra_approver_pending_edited_cnt; ?></td><!-- DRA Approver Pending -->          
                        <td class="text-center"><?php echo $dra_recommender_rejected_edited_cnt; ?></td><!-- DRA Recommender Rejected -->
                        <td class="text-center"><?php echo $dra_approver_rejected_edited_cnt; ?></td><!-- DRA Approver Rejected -->
                      </tr>
                    </tbody>
                  </table>
                  <p class="note mt-1">A = B + C + D + D + F</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <?php $this->load->view('kyc/inc_footerbar_admin'); ?>
      </div>
    </div>
    <?php $this->load->view('kyc/inc_footer'); ?>
    <?php $this->load->view('kyc/common/inc_bottom_script'); ?>
  </body>
</html>