
  <?php $dispName = $this->Kyc_model->getLoggedInUserDetails($this->session->userdata('NCVET_KYC_LOGIN_ID'), $this->session->userdata('NCVET_KYC_ADMIN_TYPE')); ?>
  <div class="row border-bottom top-fix">
    <div class="col-xl-12 col-lg-12 px-0">
      <nav class="navbar navbar-static-top common_topbar user-dropdown top-bg-color" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header d-flex align-items-center">
          <a class="navbar-minimalize minimalize-styl-2 btn btn-primary menu-btn" href="javascript:voic(0)"><i class="fa fa-bars text-white"></i> </a>
          <h2 class="head-title">Indian Institute of Banking and Finance</h4>
      </div>
      <ul class="nav navbar-top-links navbar-right">
        <li><a href="<?php echo site_url('ncvet/kyc/kyc_dashboard'); ?>" title="<?php echo $dispName['disp_name']; ?>" alt="<?php echo $dispName['disp_name']; ?>">Welcome <?php echo substr($dispName['disp_name'],0,20); ?></a></li>			
        <li><a href="<?php echo site_url('ncvet/kyc/login/logout'); ?>" title="Logout" alt="Logout"><i class="fa fa-sign-out"></i></a></li>
      </ul>
      
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Welcome <?php echo $dispName['disp_name']; ?>
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <a class="dropdown-item" href="<?php echo site_url('ncvet/kyc/kyc_dashboard'); ?>">Dashboard</a>
          <a class="dropdown-item" href="<?php echo site_url('ncvet/kyc/login/logout'); ?>">Log out</a>
        </div>
      </div>
    </nav>
    </div>
  </div>