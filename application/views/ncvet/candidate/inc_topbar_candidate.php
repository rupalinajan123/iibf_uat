<style>
  .avatar-bg {
    width:45px; height:45px; border-radius:50%;
    background-size:cover; background-position:center;
  }
</style>
  <?php $dispName = $this->Ncvet_model->getLoggedInUserDetails($this->session->userdata('NCVET_CANDIDATE_LOGIN_ID'), 'candidate'); ?>
  <div class="row border-bottom">
    <nav class="navbar navbar-static-top align-items-center" role="navigation" style="margin-bottom: 0">
      <div class="navbar-header">
        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="javascript:voic(0)"><i class="fa fa-bars"></i> </a>
      </div>
      <!-- <ul class="nav navbar-top-links navbar-right">
        <li><a href="<?php echo site_url('ncvet/candidate/dashboard_candidate'); ?>">Welcome <?php echo $dispName['disp_name']; ?></a></li>			
        <li><a href="<?php echo site_url('ncvet/candidate/login_candidate/logout'); ?>"><i class="fa fa-sign-out"></i> Log out</a></li>
      </ul> -->
      
      <div class="dropdown">
        <button class="dropdown-item btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
          <?php $profileImage = $dispName['candidate_photo']; ?>

          <div class="avatar-bg" style="background-image:url(<?php echo $profileImage; ?>);"></div>
        <!-- <img src="https://iibf.esdsconnect.com/staging/uploads/ncvet/photo/p_9000052.jpg?1758012922" /> -->
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <a class="dropdown-item" href="<?php echo site_url('ncvet/candidate/dashboard_candidate'); ?>"><b>Welcome <?php echo $dispName['disp_name']; ?></b></a>
          <a class="dropdown-item" href="<?php echo site_url('ncvet/candidate/login_candidate/logout'); ?>"><b><i class="fa fa-sign-out"></i> Log out</b></a>
        </div>
      </div>
    </nav>
  </div>