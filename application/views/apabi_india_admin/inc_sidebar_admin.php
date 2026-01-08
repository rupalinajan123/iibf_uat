
        <nav class="navbar-default navbar-static-side" role="navigation">
          <div class="sidebar-collapse">
            <ul class="nav metismenu side-bg-color" id="side-menu">
              <li class="before_nav_header hide"><a id="custom_sidebar_close_btn"><i class="fa fa-times"></i></a></li>
              <li class="nav-header d-flex align-items-center justify-content-center">
              <img src="<?php echo base_url('assets/iibfbcbf/images/iibf_logo.png'); ?>" class="img-fluid logo-top" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">
                <a href="<?php echo site_url('apabi_india_admin/dashboard_admin'); ?>">
                  <h3>Welcome APABI Admin</h3>
                </a>
              </li>
              
              <li <?php if($act_id == "Dashboard") { ?>class="active" <?php } ?>><a href="<?php echo site_url('apabi_india_admin/dashboard_admin'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a></li>              
              
              <li <?php if($act_id == "Registrations") { ?>class="active" <?php } ?>><a href="<?php echo site_url('apabi_india_admin/apabi_admin_registrations'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Registrations</span></a></li>
              
              <li <?php if($act_id == "Change Password") { ?>class="active" <?php } ?>><a href="<?php echo site_url('apabi_india_admin/dashboard_admin/change_password'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Change Password</span></a></li> 

              <li><a href="<?php echo site_url('apabi_india_admin/login/logout'); ?>"><i class="fa fa-sign-out"></i> <span class="nav-label">Log out</span></a></li>
            </ul>		
          </div>
        </nav>
