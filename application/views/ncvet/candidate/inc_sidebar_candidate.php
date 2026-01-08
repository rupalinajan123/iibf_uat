<nav class="navbar-default navbar-static-side" role="navigation">
  <div class="sidebar-collapse" style="background-color: #effdff">
    <ul class="nav metismenu side-bg-color" id="side-menu">
      <li class="before_nav_header hide"><a id="custom_sidebar_close_btn"><i class="fa fa-times"></i></a></li>
      <li class="nav-header d-flex align-items-center justify-content-center">
        <img src="<?php echo base_url('assets/ncvet/images/iibf_logo.png'); ?>" class="img-fluid logo-top" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">
        <a href="<?php echo site_url('ncvet/candidate/dashboard_candidate'); ?>">
          <h3>Welcome Candidate</h3>
        </a>
      </li>

      <li <?php if ($act_id == "Dashboard") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/candidate/dashboard_candidate'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a></li>

      <?php
      $candidate_id = $this->session->userdata('NCVET_CANDIDATE_LOGIN_ID');

      // $this->db->join('ncvet_agency_centre_batch acb', 'acb.batch_id = bc.batch_id', 'INNER');
      $data['form_data'] = $form_data = $this->master_model->getRecords('ncvet_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0'), "bc.candidate_id");
      // if(count($form_data) > 0 && $form_data[0]['batch_end_date'] < date('Y-m-d')) {   
      ?>
      <li <?php if ($act_id == "Profile") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/candidate/dashboard_candidate/update_profile'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Profile</span></a></li>
      <?php // }  
      ?>

      <li <?php if ($act_id == "E-Learning") { ?>class="active" <?php } ?>><a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">E-Learning/Virtual Training</span></a></li>

      <li <?php if ($act_id == "Training Re-Registration") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/candidate/applytraining/traininglist/'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Training Re-Registration</span></a></li>

      <li <?php if ($act_id == "Exam Registration") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/candidate/applyexam/examlist/'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Exam Registration</span></a></li>

      <li <?php if ($act_id == "Apply For Scribe") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/Scribe_form/details_page/'); ?>" target="_blank"><i class="fa fa-th-large"></i> <span class="nav-label">Apply For Scribe</span></a></li>

      <li <?php if ($act_id == "Admit Card") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/candidate/applyexam/admitcards/'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Admit Card</span></a></li>

      <li <?php if ($act_id == "Marksheet") { ?>class="active" <?php } ?>><a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Marksheet</span></a></li>

      <li <?php if ($act_id == "Transaction Details") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/candidate/Transaction'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Transaction Details</span></a></li>

      <li><a href="<?php echo site_url('ncvet/candidate/dashboard_candidate/change_password'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Change Password</span></a></li>

      <li><a href="<?php echo site_url('ncvet/candidate/login_candidate/logout'); ?>"><i class="fa fa-sign-out"></i> <span class="nav-label">Log out</span></a></li>
    </ul>
  </div>
</nav>