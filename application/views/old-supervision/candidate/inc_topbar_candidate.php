  <?php $dispName = $this->supervision_model->getLoggedInUserDetails($this->session->userdata('SUPERVISION_LOGIN_ID'), $this->session->userdata('SUPERVISION_USER_TYPE')); ?>

    <?php 
    $logged_in_id = 0;
    if($this->session->userdata('SUPERVISION_USER_TYPE') == 'candidate') 
    { 
      $logged_in_id = $this->session->userdata('SUPERVISION_LOGIN_ID');
    }
    

    
    $logout_link = site_url('supervision/login/logout'); 
    ?>
  
    <div class="row border-bottom top-fix">
      <div class="col-xl-12 col-lg-12 px-0">
      <nav class="navbar navbar-static-top common_topbar user-dropdown top-bg-color" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header d-flex align-items-center">
          <a class="navbar-minimalize minimalize-styl-2 btn btn-primary menu-btn" href="javascript:voic(0)"><i class="fa fa-bars text-white"></i> </a>
          <h2 class="head-title">Indian Institute of Banking and Finance</h4>
        </div>
        <ul class="nav navbar-top-links navbar-right">
          <li><a href="<?php echo site_url('supervision/candidate/dashboard_candidate'); ?>">Welcome <?php echo $dispName['disp_name']; ?></a></li>	
          <li>
            <div class="dropdown menu-drp">
              <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-user"></i>
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?php echo site_url('supervision/candidate/dashboard_candidate/view_profile'); ?>"><i class="fa fa-eye mr-2"></i> View</a>
                <a class="dropdown-item" href="<?php echo site_url('supervision/candidate/dashboard_candidate/change_password'); ?>"><i class="fa fa-lock mr-2"></i>Change Password</a>
              </div>
            </div>
          </li>		
          <li><a href="<?php echo $logout_link; ?>"><i class="fa fa-sign-out"></i> </a></li>
        </ul>
        
        <div class="dropdown">
          <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Welcome <?php echo $dispName['disp_name']; ?>
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="<?php echo site_url('supervision/candidate/dashboard_candidate'); ?>">Dashboard</a>
            <a class="dropdown-item" href="<?php echo $logout_link; ?>">Log out</a>
          </div>
        </div>
      </nav>
    </div> 
    </div>