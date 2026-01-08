
        <nav class="navbar-default navbar-static-side" role="navigation">
          <div class="sidebar-collapse" style="background-color: #effdff">
            <ul class="nav metismenu" id="side-menu">
              <li class="before_nav_header hide"><a id="custom_sidebar_close_btn"><i class="fa fa-times"></i></a></li>
              <li class="nav-header">
                <a href="<?php echo site_url('supervision/admin/dashboard_admin'); ?>">
                  <h3>Welcome Admin</h3>
                </a>
              </li>
              
              <li <?php if($act_id == "Dashboard") { ?>class="active" <?php } ?>><a href="<?php echo site_url('supervision/admin/dashboard_admin'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a></li>              
              
              
              <li <?php if($act_id == "Candidate Master") { ?>class="active" <?php } ?>><a href="<?php echo site_url('supervision/admin/candidate'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Observer Master</span></a></li> 

              <li <?php if($act_id == "Supervision Report") { ?>class="active" <?php } ?>><a href="<?php echo site_url('supervision/admin/candidate/session_forms'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Supervision Report</span></a></li>
              
              <li <?php if($act_id == "Honorarium form") { ?>class="active" <?php } ?>><a href="<?php echo site_url('supervision/admin/candidate/claims'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Honorarium form</span></a></li>
              
              

              
              <li <?php if($act_id == "Change Password") { ?>class="active" <?php } ?>><a href="<?php echo site_url('supervision/admin/dashboard_admin/change_password'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Change Password</span></a></li> 

              <li><a href="<?php echo site_url('supervision/login/logout'); ?>"><i class="fa fa-sign-out"></i> <span class="nav-label">Log out</span></a></li>
            </ul>		
          </div>
        </nav>
