
        <nav class="navbar-default navbar-static-side" role="navigation">
          <div class="sidebar-collapse" style="background-color: #effdff">
            <ul class="nav metismenu" id="side-menu">
              <li class="before_nav_header hide"><a id="custom_sidebar_close_btn"><i class="fa fa-times"></i></a></li>
              <li class="nav-header">
                <a href="<?php echo site_url('macroresearch/admin/dashboard_admin'); ?>">
                  <h3>Welcome Admin</h3>
                </a>
              </li>
              
              <li <?php if($act_id == "Dashboard") { ?>class="active" <?php } ?>><a href="<?php echo site_url('macroresearch/admin/dashboard_admin'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a></li>              
              
              
              <li <?php if($act_id == "Applications") { ?>class="active" <?php } ?>><a href="<?php echo site_url('macroresearch/admin/applications/application_list/'.base64_encode('Individual')); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Individual</span></a></li> 

              <li <?php if($act_id == "Applications") { ?>class="active" <?php } ?>><a href="<?php echo site_url('macroresearch/admin/applications/application_list/'.base64_encode('Joint')); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Joint</span></a></li> 
              
              <li <?php if($act_id == "Applications") { ?>class="active" <?php } ?>><a href="<?php echo site_url('macroresearch/admin/applications/application_list/'.base64_encode('Institute')); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Institute</span></a></li> 
              
              <li <?php if($act_id == "Applications") { ?>class="active" <?php } ?>><a href="<?php echo site_url('macroresearch/admin/applications/application_list/'.base64_encode('All')); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">All Applications</span></a></li> 
              
              <!--<li <?php if($act_id == "Change Password") { ?>class="active" <?php } ?>><a href="<?php echo site_url('macroresearch/admin/dashboard_admin/change_password'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Change Password</span></a></li> 
                  -->
              <li><a href="<?php echo site_url('macroresearch/login/logout'); ?>"><i class="fa fa-sign-out"></i> <span class="nav-label">Log out</span></a></li>
            </ul>		
          </div>
        </nav>
