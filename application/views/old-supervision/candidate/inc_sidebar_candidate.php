        <?php $dispName = $this->supervision_model->getLoggedInUserDetails($this->session->userdata('SUPERVISION_LOGIN_ID'), $this->session->userdata('SUPERVISION_USER_TYPE')); ?>
        <nav class="navbar-default navbar-static-side" role="navigation">
          <div class="sidebar-collapse">
            <ul class="nav metismenu side-bg-color" id="side-menu">
              <li class="before_nav_header hide"><a id="custom_sidebar_close_btn"><i class="fa fa-times"></i></a></li>
              <li class="nav-header d-flex align-items-center justify-content-center">
              <img src="<?php echo base_url('assets/supervision/images/iibf_logo.png'); ?>" class="img-fluid logo-top" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">
                <a href="<?php echo site_url('supervision/candidate/dashboard_candidate'); ?>">
                  <h3>Welcome </h3>
                </a>
              </li>

              <li class="sidebar_title_only">
                <?php echo $dispName['disp_sidebar_name']; ?>
              </li>
              
              <li <?php if($act_id == "Dashboard") { ?>class="active" <?php } ?>><a href="<?php echo site_url('supervision/candidate/dashboard_candidate'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a></li>

              
                <li <?php if($act_id == "Forms") { ?>class="active" <?php } ?>><a href="<?php echo site_url('supervision/candidate/dashboard_candidate/session_forms'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Supervision Report</span></a></li>
              
                <li <?php if($act_id == "Honorarium form") { ?>class="active" <?php } ?>><a href="<?php echo site_url('supervision/candidate/dashboard_candidate/claims'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Honorarium form</span></a></li>
              

                <!--<li <?php if($act_id == "View Profile") { ?>class="active" <?php } ?>><a href="<?php echo site_url('supervision/candidate/dashboard_candidate/view_profile'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">View Profile</span></a></li>
                -->
                <li <?php if($act_id == "Change Password") { ?>class="active" <?php } ?>><a href="<?php echo site_url('supervision/candidate/dashboard_candidate/change_password'); ?>"><i class="fa fa-th-large"></i><span class="nav-label">Change Password</span></a></li>
             
                
              </li>
              
              <?php 
              $logout_link = site_url('supervision/login/logout'); 
              
             ?>
              <li><a href="<?php echo $logout_link; ?>"><i class="fa fa-sign-out"></i> <span class="nav-label">Log out</span></a></li>
            </ul>		
          </div>
        </nav>
