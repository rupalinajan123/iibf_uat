
        <nav class="navbar-default navbar-static-side" role="navigation">
          <div class="sidebar-collapse">
            <ul class="nav metismenu side-bg-color" id="side-menu">
              <li class="before_nav_header hide"><a id="custom_sidebar_close_btn"><i class="fa fa-times"></i></a></li>
              <li class="nav-header d-flex align-items-center justify-content-center">
              <img src="<?php echo base_url('assets/ncvet/images/iibf_logo.png'); ?>" class="img-fluid logo-top" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">
                <a href="<?php echo site_url('ncvet/kyc/kyc_dashboard'); ?>">
                  <?php $dispName = $this->Kyc_model->getLoggedInUserDetails($this->session->userdata('NCVET_KYC_LOGIN_ID'), $this->session->userdata('NCVET_KYC_ADMIN_TYPE')); ?>
                  <h3>Welcome <?php echo $dispName['disp_sidebar_name']; ?></h3>
                </a>
              </li>
              
              <li <?php if($act_id == "Dashboard") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/kyc_dashboard'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a></li>              
              
              <!-- <li <?php if($act_id == "bcbf") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/kyc_all/index/bcbf'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">BCBF KYC</span></a></li> -->  

              <!-- <li <?php if($act_id == "ncvet") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/kyc_all/index/ncvet'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">NCVET KYC</span></a></li> -->  

              <li <?php if($act_id == "New Candidate KYC") { ?>class="active" <?php } ?>>
                <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">New Candidate KYC </span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                  <li <?php if($sub_act_id == "Enrolled Candidates") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/kyc_all/new_candidate'); ?>"><span class="nav-label">Enrolled Candidates</span></a></li> 
                  <li <?php if($sub_act_id == "Pending for KYC") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/kyc_all/pending_candidate'); ?>"><span class="nav-label">Pending for KYC</span></a></li> 
                  <li <?php if($sub_act_id == "Attend Candidates") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/kyc_all/recommend_candidate'); ?>"><span class="nav-label">Attend Candidates</span></a></li> 
                  <li <?php if($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '1' && $sub_act_id == "Recommended Candidates") { ?>class="active" <?php }else if($this->session->userdata('NCVET_KYC_ADMIN_TYPE') != '1' && $sub_act_id == "Approved Candidates") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/kyc_all/approved_candidate'); ?>"><span class="nav-label"><?php echo ($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '1' ? 'Recommended Candidates' : 'Approved Candidates' ); ?></span></a></li>
                  <li <?php if($sub_act_id == "Rejected Candidates") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/kyc_all/rejected_candidate'); ?>"><span class="nav-label">Rejected Candidates</span></a></li>
                </ul>
              </li>

              <li <?php if($act_id == "Edit Candidate KYC") { ?>class="active" <?php } ?>>
                <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Edit Candidate KYC </span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                  <li <?php if($sub_act_id == "Re-KYC Candidates") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/kyc_all/edit_candidate'); ?>"><span class="nav-label">Re-KYC Candidates</span></a></li>
                  <li <?php if($sub_act_id == "Pending for Re-KYC") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/kyc_all/edit_pending_candidate'); ?>"><span class="nav-label">Pending for Re-KYC</span></a></li>
                  <li <?php if($sub_act_id == "Edit Attend Candidates") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/kyc_all/edit_recommend_candidate'); ?>"><span class="nav-label">Edit Attend Candidates</span></a></li>
                  <li <?php if($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '1' && $sub_act_id == "Edit Recommended Candidates") { ?>class="active" <?php }else if($this->session->userdata('NCVET_KYC_ADMIN_TYPE') != '1' && $sub_act_id == "Edit Approved Candidates") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/kyc_all/approved_edit_candidate'); ?>"><span class="nav-label">Edit <?php echo ($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '1' ? 'Recommended Candidates' : 'Approved Candidates' ); ?></span></a></li>
                  <li <?php if($sub_act_id == "Edit Rejected Candidates") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/kyc_all/rejected_edit_candidate'); ?>"><span class="nav-label">Edit Rejected Candidates</span></a></li>   
                </ul>
              </li>

              <!-- Benchmark KYC Start -->

              <li <?php if(isset($main_act_id) && $main_act_id == "Benchmark KYC") { ?>class="active" <?php } ?>>
                <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Benchmark KYC </span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">

                  <li <?php if($act_id == "Benchmark KYC Dashboard") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_dashboard'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Benchmark KYC Dashboard</span></a></li>    

                  <li <?php if($act_id == "New Candidate") { ?>class="active" <?php } ?>> <!-- Benchmark KYC New -->
                    <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">New Candidate </span><span class="fa arrow"></span></a>
                    <ul class="nav nav-third-level collapse">
                      <li <?php if($sub_act_id == "Enrolled Candidates") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/new_candidate'); ?>"><span class="nav-label">Enrolled Candidates</span></a></li> 
                      <li <?php if($sub_act_id == "Pending for KYC") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/pending_candidate'); ?>"><span class="nav-label">Pending for KYC</span></a></li> 
                      <li <?php if($sub_act_id == "Attend Candidates") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/recommend_candidate'); ?>"><span class="nav-label">Attend Candidates</span></a></li> 
                      <li <?php if($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '1' && $sub_act_id == "Recommended Candidates") { ?>class="active" <?php }else if($this->session->userdata('NCVET_KYC_ADMIN_TYPE') != '1' && $sub_act_id == "Approved Candidates") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/approved_candidate'); ?>"><span class="nav-label"><?php echo ($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '1' ? 'Recommended Candidates' : 'Approved Candidates' ); ?></span></a></li>
                      <li <?php if($sub_act_id == "Rejected Candidates") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/rejected_candidate'); ?>"><span class="nav-label">Rejected Candidates</span></a></li>
                    </ul> 
                  </li>

                  <li <?php if($act_id == "Edit Candidate") { ?>class="active" <?php } ?>> <!-- Benchmark KYC Edit -->
                    <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Edit Candidate </span><span class="fa arrow"></span></a>
                    <ul class="nav nav-third-level collapse">
                      <li <?php if($sub_act_id == "Re-KYC Candidates") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/edit_candidate'); ?>"><span class="nav-label">Re-KYC Candidates</span></a></li>
                      <li <?php if($sub_act_id == "Pending for Re-KYC") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/edit_pending_candidate'); ?>"><span class="nav-label">Pending for Re-KYC</span></a></li>
                      <li <?php if($sub_act_id == "Edit Attend Candidates") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/edit_recommend_candidate'); ?>"><span class="nav-label">Edit Attend Candidates</span></a></li>
                      <li <?php if($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '1' && $sub_act_id == "Edit Recommended Candidates") { ?>class="active" <?php }else if($this->session->userdata('NCVET_KYC_ADMIN_TYPE') != '1' && $sub_act_id == "Edit Approved Candidates") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/approved_edit_candidate'); ?>"><span class="nav-label">Edit <?php echo ($this->session->userdata('NCVET_KYC_ADMIN_TYPE') == '1' ? 'Recommended Candidates' : 'Approved Candidates' ); ?></span></a></li>
                      <li <?php if($sub_act_id == "Edit Rejected Candidates") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/benchmark_kyc_all/rejected_edit_candidate'); ?>"><span class="nav-label">Edit Rejected Candidates</span></a></li>   
                    </ul>
                  </li>

                </ul>
              </li>

              <!-- Benchmark KYC End -->
              
              <!-- <li <?php if($act_id == "dra") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/kyc_all/index/dra'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">DRA KYC</span></a></li> -->    
              
              <li <?php if($act_id == "KYC Report") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/kyc_all/kyc_status_report'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">KYC Status Report</span></a></li>  

              <li <?php if($act_id == "kyc_log") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/kyc_log'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">KYC <?php echo $dispName['disp_sidebar_name']; ?> Log</span></a></li>    
              
              <li <?php if($act_id == "Change Password") { ?>class="active" <?php } ?>><a href="<?php echo site_url('ncvet/kyc/kyc_dashboard/change_password'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Change Password</span></a></li> 

              <li><a href="<?php echo site_url('ncvet/kyc/login/logout'); ?>"><i class="fa fa-sign-out"></i> <span class="nav-label">Log out</span></a></li>
            </ul>		
          </div>
        </nav>
