        <?php $dispName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->session->userdata('IIBF_BCBF_LOGIN_ID'), $this->session->userdata('IIBF_BCBF_USER_TYPE')); ?>
        <nav class="navbar-default navbar-static-side" role="navigation">
          <div class="sidebar-collapse">
            <ul class="nav metismenu side-bg-color" id="side-menu">
              <li class="before_nav_header hide"><a id="custom_sidebar_close_btn"><i class="fa fa-times"></i></a></li>
              <li class="nav-header d-flex align-items-center justify-content-center">
              <img src="<?php echo base_url('assets/iibfbcbf/images/iibf_logo.png'); ?>" class="img-fluid logo-top" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">
                <a href="<?php echo site_url('iibfbcbf/inspector/dashboard_inspector'); ?>">
                  <h3>Welcome <?php echo ucfirst($this->session->userdata('IIBF_BCBF_USER_TYPE')); ?></h3>
                </a>
              </li>

              <li class="sidebar_title_only"><?php echo $dispName['disp_name']; ?></li>
              
              <li <?php if($act_id == "Dashboard") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/inspector/dashboard_inspector'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a></li>
              
              <li <?php if($act_id == "Training Batches") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/inspector/training_batches_inspector'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Batch List</span></a></li>
              
              <li <?php if($act_id == "Add Inspection Report") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/inspector/inspection_report_inspector/add_inspection_report_inspector'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Add Inspection Report</span></a></li>

              <li <?php if($act_id == "Inspection Report") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/inspector/inspection_report_inspector'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Inspection Report</span></a></li>

              <li <?php if($act_id == "Profile Settings") { ?>class="active" <?php } ?>>
                <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Profile Settings </span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                  <li <?php if($sub_act_id == "View Profile") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/inspector/dashboard_inspector/view_profile'); ?>"><span class="nav-label">View Profile</span></a></li>
                  <li <?php if($sub_act_id == "Change Password") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/inspector/dashboard_inspector/change_password'); ?>"><span class="nav-label">Change Password</span></a></li>
                </ul>
              </li>

              <li><a href="<?php echo site_url('iibfbcbf/login/logout'); ?>"><i class="fa fa-sign-out"></i> <span class="nav-label">Log out</span></a></li>
            </ul>		
          </div>
        </nav>
