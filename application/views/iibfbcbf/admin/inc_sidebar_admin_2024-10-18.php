
        <nav class="navbar-default navbar-static-side" role="navigation">
          <div class="sidebar-collapse">
            <ul class="nav metismenu side-bg-color" id="side-menu">
              <li class="before_nav_header hide"><a id="custom_sidebar_close_btn"><i class="fa fa-times"></i></a></li>
              <li class="nav-header d-flex align-items-center justify-content-center">
              <img src="<?php echo base_url('assets/iibfbcbf/images/iibf_logo.png'); ?>" class="img-fluid logo-top" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">
                <a href="<?php echo site_url('iibfbcbf/admin/dashboard_admin'); ?>">
                  <h3>Welcome Admin</h3>
                </a>
              </li>
              
              <li <?php if($act_id == "Dashboard") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/dashboard_admin'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a></li>              
              
              <li <?php if($act_id == "Masters") { ?>class="active" <?php } ?>>
                <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Masters </span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                  <li <?php if($sub_act_id == "Agency Master") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/agency'); ?>"><span class="nav-label">Agency Master</span></a></li> 

                  <li <?php if($sub_act_id == "Centre Master") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/centre'); ?>"><span class="nav-label">Centre Master</span></a></li>

                  <li <?php if($sub_act_id == "Faculty Master") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/faculty'); ?>"><span class="nav-label">Faculty Master</span></a></li> 

                  <li <?php if($sub_act_id == "Inspector Master") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/inspector_master'); ?>"><span class="nav-label">Inspector Master</span></a></li>
                  
                  <li <?php if($sub_act_id == "Exam Master") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/masters_admin/exam_master_admin'); ?>"><span class="nav-label">Exam Master</span></a></li> 

                  <li <?php if($sub_act_id == "Exam Activation Master") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/masters_admin/exam_activation_master_admin'); ?>"><span class="nav-label">Exam Activation Master</span></a></li> 
                  
                  <li <?php if($sub_act_id == "Exam Fee Master") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/masters_admin/exam_fee_master_admin'); ?>"><span class="nav-label">Exam Fee Master</span></a></li> 
                  
                  <li <?php if($sub_act_id == "Exam Misc Master") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/masters_admin/exam_misc_master_admin'); ?>"><span class="nav-label">Exam Misc Master</span></a></li> 
                  
                  <li <?php if($sub_act_id == "Exam Centre Master") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/masters_admin/exam_centre_master_admin'); ?>"><span class="nav-label">Exam Centre Master</span></a></li> 
                  
                  <li <?php if($sub_act_id == "Exam Medium Master") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/masters_admin/exam_medium_master_admin'); ?>"><span class="nav-label">Exam Medium Master</span></a></li> 
                  
                  <li <?php if($sub_act_id == "Exam Subject Master") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/masters_admin/exam_subject_master_admin'); ?>"><span class="nav-label">Exam Subject Master</span></a></li> 
                  
                  <li <?php if($sub_act_id == "Exam Venue Master") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/masters_admin/exam_venue_master_admin'); ?>"><span class="nav-label">Exam Venue Master</span></a></li> 

                  <li <?php if($sub_act_id == "CSC Exam Date Master") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/masters_admin/csc_exam_date_master_admin'); ?>"><span class="nav-label">CSC Exam Date Master</span></a></li>                  
                </ul>
              </li>
              
              <li <?php if($act_id == "Training Batches") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/training_batches'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Training Batches</span></a></li>
              
              <li <?php if($act_id == "All Candidates") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/batch_candidates'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">All Candidates</span></a></li>

              <li <?php if($act_id == "Approve NEFT Transactions") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/transaction/neft_transactions'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Approve NEFT Transactions</span></a></li>

              <li <?php if($act_id == "Reports") { ?>class="active" <?php } ?>>
                <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Reports </span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse"> 

                  <li <?php if($sub_act_id == "Inspection Summary") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/inspection_summary_admin'); ?>"><span class="nav-label">Inspection Summary</span></a></li>

                  <li <?php if($sub_act_id == "Billdesk Transaction Report") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/reports/index/billdesk'); ?>"><span class="nav-label">Billdesk Transaction Report</span></a></li>
                  
                  <li <?php if($sub_act_id == "CSC Transaction Report") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/reports/index/csc'); ?>"><span class="nav-label">CSC Transaction Report</span></a></li>

                  <li <?php if($sub_act_id == "NEFT Transaction Report") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/reports/index/neft'); ?>"><span class="nav-label">NEFT Transaction Report</span></a></li>

                  <li <?php if($sub_act_id == "Batch MIS") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/reports/batch_mis'); ?>"><span class="nav-label">Batch MIS</span></a></li>
 
                  <li <?php if($sub_act_id == "Exam Details Report") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/reports/exam_details'); ?>"><span class="nav-label">Exam Details Report</span></a></li>

                  <li <?php if($sub_act_id == "Individual Registration Report") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/reports/individual_registration'); ?>"><span class="nav-label">Individual Registration Report</span></a></li>

                  <li <?php if($sub_act_id == "Institution Wise Batch Report") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/reports/institution_wise_batch'); ?>"><span class="nav-label">Institution Wise Batch Report</span></a></li>

                  <li <?php if($sub_act_id == "Candidate Eligible For Examination") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/reports/eligible_candidate_for_examination'); ?>"><span class="nav-label">Candidate Eligible For Examination</span></a></li>

                  <li <?php if($sub_act_id == "Training Batch Details Report") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/reports/training_details_batch'); ?>"><span class="nav-label">Training Batch Details Report</span></a></li>

                  <li <?php if($sub_act_id == "Batch Summary") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/reports/batch_summary'); ?>"><span class="nav-label">Batch Summary</span></a></li>

                  <li <?php if($sub_act_id == "Batch Action Report") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/reports/batch_action'); ?>"><span class="nav-label">Batch Action Report</span></a></li>

                  <li <?php if($sub_act_id == "Batch Communication") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/reports/batch_communication'); ?>"><span class="nav-label">Batch Communication</span></a></li>

                  <li <?php if($sub_act_id == "Bulk Application Report") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/reports/bulk_application_report'); ?>"><span class="nav-label">Bulk Application Report</span></a></li>

                  <li <?php if($sub_act_id == "Candidates Required to re-enroll for Training") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/reports/candidates_required_re_enroll'); ?>"><span class="nav-label">Candidates Required to re-enroll for Training</span></a></li>
                  
                  <li <?php if($sub_act_id == "Inspector Report") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/reports/inspector_report'); ?>"><span class="nav-label">Inspector Report</span></a></li>

                  <li <?php if($sub_act_id == "Institution Report") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/reports/institution_report'); ?>"><span class="nav-label">Institution Report</span></a></li>                  

                </ul>
              </li>
              
              <li <?php if($act_id == "Change Password") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/admin/dashboard_admin/change_password'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Change Password</span></a></li> 

              <li><a href="<?php echo site_url('iibfbcbf/login/logout'); ?>"><i class="fa fa-sign-out"></i> <span class="nav-label">Log out</span></a></li>
            </ul>		
          </div>
        </nav>
