        <?php $dispName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->session->userdata('IIBF_BCBF_LOGIN_ID'), $this->session->userdata('IIBF_BCBF_USER_TYPE')); ?>
        <nav class="navbar-default navbar-static-side" role="navigation">
          <div class="sidebar-collapse">
            <ul class="nav metismenu side-bg-color" id="side-menu">
              <li class="before_nav_header hide"><a id="custom_sidebar_close_btn"><i class="fa fa-times"></i></a></li>
              <li class="nav-header d-flex align-items-center justify-content-center">
              <img src="<?php echo base_url('assets/iibfbcbf/images/iibf_logo.png'); ?>" class="img-fluid logo-top" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">
                <a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency'); ?>">
                  <h3>Welcome <?php echo ucfirst($this->session->userdata('IIBF_BCBF_USER_TYPE')); ?></h3>
                </a>
              </li>

              <li class="sidebar_title_only"><?php echo $dispName['disp_sidebar_name']; ?></li>
              
              <li <?php if($act_id == "Dashboard") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a></li>

              <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency') { ?>
                <li <?php if($act_id == "Centre Master") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/agency/centre_master_agency'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Centre Master</span></a></li>
              <?php } ?>  
              
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

              /***** START : SHOW EXAM MASTER DATA TO CSC AGENCY ONLY *********/
              if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency' && isset($logged_in_agency_data[0]['allow_exam_types']) && $logged_in_agency_data[0]['allow_exam_types'] == 'CSC')
              { ?>
                <li <?php if($act_id == "Exam Masters") { ?>class="active" <?php } ?>>
                  <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Exam Masters </span><span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level collapse">
                    <li <?php if($sub_act_id == "Exam Centre Master") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/agency/masters_agency/exam_centre_master_agency'); ?>"><span class="nav-label">Exam Centre Master</span></a></li> 
                    
                    <li <?php if($sub_act_id == "Exam Venue Master") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/agency/masters_agency/exam_venue_master_agency'); ?>"><span class="nav-label">Exam Venue Master</span></a></li>

                    <li <?php if($sub_act_id == "CSC Exam Date Master") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/agency/masters_agency/csc_exam_date_master_agency'); ?>"><span class="nav-label">CSC Exam Date Master</span></a></li>                  
                  </ul>
                </li>
              <?php }/***** END : SHOW EXAM MASTER DATA TO CSC AGENCY ONLY *********/
              
              if(isset($logged_in_agency_data[0]['allow_exam_types']) && ($logged_in_agency_data[0]['allow_exam_types'] == 'Bulk/Individual' || $logged_in_agency_data[0]['allow_exam_types'] == 'Hybrid'))
              { ?>
                <li <?php if($act_id == "Faculty Master") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/agency/faculty_master_agency'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Faculty Master</span></a></li>
              
                <li <?php if($act_id == "Training Batches") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/agency/training_batches_agency'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Training Batches</span></a></li>
              
                <li <?php if($act_id == "All Candidates") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/agency/batch_candidates_agency'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">All Candidates</span></a></li>

                <li <?php if($act_id == "Batch Applicant Checklist") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/agency/batch_applicant_checklist'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Batch Applicant Checklist</span></a></li>
              <?php } ?>
              
              <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'centre') 
              {
                $allow_exam_codes = $allow_exam_period = '0';
                $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'INNER');
                $agency_centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm',array('cm.centre_id'=>$this->session->userdata('IIBF_BCBF_LOGIN_ID')), 'cm.centre_id, cm.agency_id, am.allow_exam_codes, am.exam_period');
                if(count($agency_centre_data) > 0)
                {                  
                  if($agency_centre_data[0]['allow_exam_codes'] != '') 
                  {  
                    //EXAM CODE 1057 IS ACCESSIBLE ONLY TO AGENCY CODE 1019 - NAR AGENCY - ADDED THIS CONDITION BY SAGAR M ON 2024-10-09
                    if (strpos($agency_centre_data[0]['allow_exam_codes'], '1057') !== false) 
                    {
                      if(isset($_SESSION['IIBF_BCBF_AGENCY_CODE']) && in_array($_SESSION['IIBF_BCBF_AGENCY_CODE'], array('1019'))) 
                      { 
                        $allow_exam_codes .= ','.$agency_centre_data[0]['allow_exam_codes'];
                      }
                    }
                    else { $allow_exam_codes .= ','.$agency_centre_data[0]['allow_exam_codes']; }
                  }
                }
                $this->db->where_in('em.exam_code',$allow_exam_codes,FALSE);

                if(isset($logged_in_agency_data[0]['allow_exam_types']) && $logged_in_agency_data[0]['allow_exam_types'] == 'Hybrid')
                {
                  if(count($agency_centre_data) > 0 && $agency_centre_data[0]['exam_period'] != '')
                  {
                    $allow_exam_period = $agency_centre_data[0]['exam_period'];
                  }
                  $this->db->where('eam.exam_period',$allow_exam_period,FALSE);
                  $this->db->where('sm.exam_period',$allow_exam_period,FALSE);
                }

                $this->db->order_by("em.exam_code","ASC");
                $this->db->having(' (CURRENT_TIMESTAMP BETWEEN ChkExamStart AND ChkExamEnd) ');
                $this->db->join('iibfbcbf_exam_activation_master eam', 'eam.exam_code = em.exam_code', 'INNER');
                $this->db->join('iibfbcbf_exam_subject_master sm','sm.exam_code = em.exam_code', 'INNER');
                $this->db->where(" (CASE WHEN em.exam_code IN (1037,1038,1041,1042,1057) THEN sm.exam_date > '".date("Y-m-d")."' ELSE 1 END) ","",FALSE);
                $get_active_exam_data = $this->master_model->getRecords('iibfbcbf_exam_master em', array('em.exam_delete'=>'0', 'eam.exam_activation_delete' => '0', 'sm.subject_delete'=>'0'), "em.exam_code, em.description, em.exam_type, eam.exam_period, CONCAT(eam.exam_from_date,' ', eam.exam_from_time) AS ChkExamStart, CONCAT(eam.exam_to_date,' ', eam.exam_to_time) AS ChkExamEnd, eam.exam_from_date, eam.exam_from_time, eam.exam_to_date, eam.exam_to_time, sm.exam_date");
                
                if(count($get_active_exam_data) > 0)
                {
                  foreach($get_active_exam_data as $exam_res)
                  { 
                    $apply_link = '';

                    if(isset($logged_in_agency_data[0]['allow_exam_types']) && ($logged_in_agency_data[0]['allow_exam_types'] == 'Bulk/Individual' || $logged_in_agency_data[0]['allow_exam_types'] == 'Hybrid'))
                    { 
                      $apply_link = site_url('iibfbcbf/agency/apply_exam_agency/candidate_listing/'.url_encode($exam_res['exam_code'])); 
                    }
                    else if(isset($logged_in_agency_data[0]['allow_exam_types']) && ($logged_in_agency_data[0]['allow_exam_types'] == 'CSC'))
                    {
                      $apply_link = site_url('iibfbcbf/agency/apply_exam_csc_agency/index/'.url_encode($exam_res['exam_code'])); 
                    } ?>

                    <li <?php if($act_id == "Exam ".$exam_res['exam_code']) { ?>class="active" <?php } ?>><a href="<?php echo $apply_link; ?>"><i class="fa fa-book"></i> <span class="nav-label">Apply For <?php echo display_exam_name($exam_res['description'], $exam_res['exam_code'], $exam_res['exam_type']); /* helpers/iibfbcbf/iibf_bcbf_helper.php */ ?></span></a></li>
                  <?php }
                }
                ?>
              <?php } ?>

              <li <?php if($act_id == "Transaction Details") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/agency/transaction_details_agency'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Transaction Details</span></a></li>

              <?php if(isset($logged_in_agency_data[0]['allow_exam_types']) && ($logged_in_agency_data[0]['allow_exam_types'] == 'Bulk/Individual' || $logged_in_agency_data[0]['allow_exam_types'] == 'Hybrid'))
              /* { ?>
                <li <?php if($act_id == "Download Admitcard") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/agency/Admitcard_agency'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Download Admitcard</span></a></li>
              <?php } */ ?>
              
              <?php /* <li <?php if($act_id == "Download Result") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/agency/Result_agency'); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Download Result</span></a></li> */ ?>
              
              <li <?php if($act_id == "Profile Settings") { ?>class="active" <?php } ?>>
                <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Profile Settings </span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                  <li <?php if($sub_act_id == "View Profile") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency/view_profile'); ?>"><span class="nav-label">View Profile</span></a></li>
                  
                  <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'centre' && isset($logged_in_agency_data[0]['allow_exam_types']) && $logged_in_agency_data[0]['allow_exam_types'] == 'CSC') {  }
                  else
                  { ?>
                    <li <?php if($sub_act_id == "Change Password") { ?>class="active" <?php } ?>><a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency/change_password'); ?>"><span class="nav-label">Change Password</span></a></li>
                  <?php } ?>
                </ul>
              </li>
              
              <?php 
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
              <li><a href="<?php echo $logout_link; ?>"><i class="fa fa-sign-out"></i> <span class="nav-label">Log out</span></a></li>
            </ul>		
          </div>
        </nav>
