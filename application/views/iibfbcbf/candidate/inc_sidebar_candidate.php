
        <nav class="navbar-default navbar-static-side" role="navigation">
          <div class="sidebar-collapse" style="background-color: #effdff">
            <ul class="nav metismenu side-bg-color" id="side-menu">
              <li class="before_nav_header hide"><a id="custom_sidebar_close_btn"><i class="fa fa-times"></i></a></li>
              <li class="nav-header d-flex align-items-center justify-content-center">
                <img src="<?php echo base_url('assets/iibfbcbf/images/iibf_logo.png'); ?>" class="img-fluid logo-top" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">
                <a href="<?php echo site_url('iibfbcbf/candidate/dashboard_candidate'); ?>">
                  <h3>Welcome Candidate</h3>
                </a>
              </li>
              
              <li <?php if($act_id == "Dashboard") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/candidate/dashboard_candidate'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a></li> 
              
              <?php 
              $candidate_id = $this->session->userdata('IIBF_BCBF_CANDIDATE_LOGIN_ID');
    
              $this->db->join('iibfbcbf_agency_centre_batch acb', 'acb.batch_id = bc.batch_id', 'INNER');
              $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_batch_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0', 'bc.regnumber !='=>''), "bc.candidate_id, acb.batch_start_date, acb.batch_end_date");        
              if(count($form_data) > 0 && $form_data[0]['batch_end_date'] < date('Y-m-d')) 
              {   ?>
                <li <?php if($act_id == "Update Profile") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/candidate/dashboard_candidate/update_profile'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Update Profile</span></a></li> 
              <?php }
              else
              {  ?>
                <li <?php if($act_id == "View Profile") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/candidate/dashboard_candidate/view_profile'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">View Profile</span></a></li>
              <?php } ?>

              <li><a href="<?php echo site_url('iibfbcbf/candidate/login_candidate/logout'); ?>"><i class="fa fa-sign-out"></i> <span class="nav-label">Log out</span></a></li>
            </ul>		
          </div>
        </nav>
