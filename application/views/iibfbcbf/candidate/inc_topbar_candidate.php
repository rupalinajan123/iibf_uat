
  <?php $dispName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->session->userdata('IIBF_BCBF_CANDIDATE_LOGIN_ID'), 'candidate'); ?>
  <div class="row border-bottom">
    <nav class="navbar navbar-static-top common_topbar" role="navigation" style="margin-bottom: 0">
      <div class="navbar-header">
        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="javascript:voic(0)"><i class="fa fa-bars"></i> </a>
      </div>
      <ul class="nav navbar-top-links navbar-right">
        <li><a href="<?php echo site_url('iibfbcbf/candidate/dashboard_candidate'); ?>">Welcome <?php echo $dispName['disp_name']; ?></a></li>			
        <li><a href="<?php echo site_url('iibfbcbf/candidate/login_candidate/logout'); ?>"><i class="fa fa-sign-out"></i> Log out</a></li>
      </ul>
      
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Welcome <?php echo $dispName['disp_name']; ?>
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <a class="dropdown-item" href="<?php echo site_url('iibfbcbf/candidate/dashboard_candidate'); ?>">Dashboard</a>
          <a class="dropdown-item" href="<?php echo site_url('iibfbcbf/candidate/login_candidate/logout'); ?>">Log out</a>
        </div>
      </div>
    </nav>
  </div>