<nav class="navbar-default navbar-static-side" role="navigation">
  <div class="sidebar-collapse">
    <ul class="nav metismenu side-bg-color" id="side-menu">
      <li class="before_nav_header hide"><a id="custom_sidebar_close_btn"><i class="fa fa-times"></i></a></li>
      <li class="nav-header d-flex align-items-center justify-content-center">
        <img src="<?php echo base_url('assets/ncvet/images/iibf_logo.png'); ?>" class="img-fluid logo-top" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">
        <a href="<?php echo site_url('ncvet/admin/dashboard_admin'); ?>">
          <h3>Welcome Admin</h3>
        </a>
      </li>

      <li <?php if ($act_id == "Dashboard") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/admin/dashboard_admin'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a></li>

      <li <?php if ($act_id == "Candidate Enrollment Management") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/admin/candidate'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Candidate Enrollment Management</span></a></li>

      <li <?php if ($act_id == "Transaction Details") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/admin/Transaction'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Transaction Details</span></a></li>

      <li <?php if ($act_id == "Change Password") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/admin/dashboard_admin/change_password'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Change Password</span></a></li>

      <li><a href="<?php echo site_url('ncvet/login/logout'); ?>"><i class="fa fa-sign-out"></i> <span class="nav-label">Log out</span></a></li>
    </ul>
  </div>
</nav>