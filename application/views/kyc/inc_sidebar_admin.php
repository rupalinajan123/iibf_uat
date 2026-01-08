
        <nav class="navbar-default navbar-static-side" role="navigation">
          <div class="sidebar-collapse">
            <ul class="nav metismenu side-bg-color" id="side-menu">
              <li class="before_nav_header hide"><a id="custom_sidebar_close_btn"><i class="fa fa-times"></i></a></li>
              <li class="nav-header d-flex align-items-center justify-content-center">
              <img src="<?php echo base_url('assets/iibfbcbf/images/iibf_logo.png'); ?>" class="img-fluid logo-top" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">
                <a href="<?php echo site_url('kyc/kyc_dashboard'); ?>">
                  <?php $dispName = $this->Kyc_model->getLoggedInUserDetails($this->session->userdata('KYC_LOGIN_ID'), $this->session->userdata('KYC_ADMIN_TYPE')); ?>
                  <h3>Welcome <?php echo $dispName['disp_sidebar_name']; ?></h3>
                </a>
              </li>
              
              <li <?php if($act_id == "Dashboard") { ?>class="active" <?php } ?>><a href="<?php echo site_url('kyc/kyc_dashboard'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a></li>              
              
              <li <?php if($act_id == "bcbf") { ?>class="active" <?php } ?>><a href="<?php echo site_url('kyc/kyc_all/index/bcbf'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">BCBF KYC</span></a></li>    
              
              <li <?php if($act_id == "dra") { ?>class="active" <?php } ?>><a href="<?php echo site_url('kyc/kyc_all/index/dra'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">DRA KYC</span></a></li>    
              
              <li <?php if($act_id == "kyc_log") { ?>class="active" <?php } ?>><a href="<?php echo site_url('kyc/kyc_log'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">KYC <?php echo $dispName['disp_sidebar_name']; ?> Log</span></a></li>    
              
              <li <?php if($act_id == "Change Password") { ?>class="active" <?php } ?>><a href="<?php echo site_url('kyc/kyc_dashboard/change_password'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Change Password</span></a></li> 

              <li><a href="<?php echo site_url('kyc/login/logout'); ?>"><i class="fa fa-sign-out"></i> <span class="nav-label">Log out</span></a></li>
            </ul>		
          </div>
        </nav>
