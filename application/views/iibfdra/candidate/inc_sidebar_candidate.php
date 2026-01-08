
        <nav class="navbar-default navbar-static-side" role="navigation">
          <div class="sidebar-collapse" style="background-color: #effdff">
            <ul class="nav metismenu side-bg-color" id="side-menu">
              <li class="before_nav_header hide"><a id="custom_sidebar_close_btn"><i class="fa fa-times"></i></a></li>
              <li class="nav-header d-flex align-items-center justify-content-center">
                <img src="<?php echo base_url('assets/iibfbcbf/images/iibf_logo.png'); ?>" class="img-fluid logo-top" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">
                <a href="<?php echo site_url('iibfdra/candidate/dashboard_candidate'); ?>">
                  <h3>Welcome Candidate</h3>
                </a>
              </li>
              
              <li <?php if($act_id == "Dashboard") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfdra/candidate/dashboard_candidate'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a></li> 
              
              <?php 
              $candidate_id = $this->session->userdata('IIBF_DRA_CANDIDATE_LOGIN_ID');
    
              $this->db->join('agency_batch acb', 'acb.id = bc.batch_id', 'INNER');
              $data['form_data'] = $form_data = $this->master_model->getRecords('dra_members bc', array('bc.regid' => $candidate_id, 'bc.isdeleted' => '0'), "bc.regid, acb.batch_from_date, acb.batch_to_date");        
              if(count($form_data) > 0 && $form_data[0]['batch_to_date'] < date('Y-m-d')) 
              {   ?>
                <li <?php if($act_id == "Update Profile") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfdra/candidate/dashboard_candidate/update_profile'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Update Profile</span></a></li> 
              <?php }  ?>

              <li><a href="<?php echo site_url('iibfdra/candidate/login_candidate/logout'); ?>"><i class="fa fa-sign-out"></i> <span class="nav-label">Log out</span></a></li>
            </ul>		
          </div>
        </nav>
