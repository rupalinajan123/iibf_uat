  <?php $dispName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->session->userdata('IIBF_BCBF_LOGIN_ID'), $this->session->userdata('IIBF_BCBF_USER_TYPE')); ?>

    <?php 
    $logged_in_agency_id = 0;
    if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency') 
    { 
      $logged_in_agency_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
    }
    else if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'centre')
    {
      $logged_in_centre_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');

      $agency_id_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('centre_id' => $logged_in_centre_id), "agency_id");
      if(count($agency_id_data) > 0)
      {
        $logged_in_agency_id = $agency_id_data[0]['agency_id'];
      }
    }

    $logged_in_agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id' => $logged_in_agency_id), "agency_id, allow_exam_codes, allow_exam_types");
    
    $logout_link = site_url('iibfbcbf/login/logout'); 
    if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'centre')
    {
      if(isset($logged_in_agency_data[0]['allow_exam_types']) && ($logged_in_agency_data[0]['allow_exam_types'] == 'Bulk/Individual' || $logged_in_agency_data[0]['allow_exam_types'] == 'Hybrid'))
      { 
        $logout_link = site_url('iibfbcbf/login/logout'); 
      }
      else if(isset($logged_in_agency_data[0]['allow_exam_types']) && ($logged_in_agency_data[0]['allow_exam_types'] == 'CSC'))
      {
        $logout_link = site_url('iibfbcbf/login_csc/logout'); 
      } 
    } ?>
  
    <div class="row border-bottom top-fix">
      <div class="col-xl-12 col-lg-12 px-0">
      <nav class="navbar navbar-static-top common_topbar user-dropdown top-bg-color" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header d-flex align-items-center">
          <a class="navbar-minimalize minimalize-styl-2 btn btn-primary menu-btn" href="javascript:voic(0)"><i class="fa fa-bars text-white"></i> </a>
          <h2 class="head-title">Indian Institute of Banking and Finance</h4>
        </div>
        <ul class="nav navbar-top-links navbar-right">
          <li><a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency'); ?>">Welcome <?php echo $dispName['disp_name']; ?></a></li>	
          <li>
            <div class="dropdown menu-drp">
              <a class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-user"></i>
              </a>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?php echo site_url('iibfbcbf/agency/dashboard_agency/view_profile'); ?>"><i class="fa fa-eye mr-2"></i>View Profile</a>
                <a class="dropdown-item" href="<?php echo site_url('iibfbcbf/agency/dashboard_agency/change_password'); ?>"><i class="fa fa-lock mr-2"></i> Change Password</a>
              </div>
            </div>
          </li>		
          <li><a href="<?php echo $logout_link; ?>" title="Logout" alt="Logout"><i class="fa fa-sign-out"></i></a></li>
        </ul>
        
        <div class="dropdown">
          <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Welcome <?php echo $dispName['disp_name']; ?>
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="<?php echo site_url('iibfbcbf/agency/dashboard_agency'); ?>">Dashboard</a>
            <a class="dropdown-item" href="<?php echo site_url('iibfbcbf/agency/dashboard_agency/view_profile'); ?>">View Profile</a>
            <a class="dropdown-item" href="<?php echo site_url('iibfbcbf/agency/dashboard_agency/change_password'); ?>">Change Password</a>
            <a class="dropdown-item" href="<?php echo $logout_link; ?>">Log out</a>
          </div>
        </div>
      </nav>
    </div> 
    </div>