<?php 
  $drainspdata = $this->session->userdata('dra_inspector'); 
  $inspector_name = $drainspdata['inspector_name'];
  $inspector_id = $drainspdata['id'];
  $successMassege = '';
?>
<style type="text/css">
  textarea {
    width: 100%;
    min-height: 50px;
    resize: vertical;
  }
  .note {
  color: blue;
  font-size: small;
  }
  
  .note-error {
  color: red;
  font-size: small;
  }
  
  #loading { display: none; position: fixed; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; }
  #loading > p { margin: 0 auto; width: 100%; height: 100%; position: absolute; top: 20%; }
  #loading > p > img { max-height: 250px; margin:0 auto; display: block; }
  
  .select2-selection.select2-selection--single { border-radius: 0 !important; padding: 5px 0 2px 0px; height: auto !important; max-width: none; }

  .select2-container {
      width: 100% !important;
  }

  .select2-container {
      width: 100% !important;
  }

   .blink { animation: blink 3s infinite; font-weight:bold; }
          @keyframes blink
          {
            0% { background-color: #f0434396; }
            10% { background-color: #fb0c0c; }
            20% { background-color: #f0434396; }
            30% { background-color: #fb0c0c; }
            40% { background-color: #f0434396; }
            50% { background-color: #fb0c0c; }
            60% { background-color: #f0434396; }
            70% { background-color: #fb0c0c; }
            80% { background-color: #f0434396; }
            90% { background-color: #fb0c0c; }
            100% { background-color: #f0434396; }
          }
    table.table-bordered tbody th, table.table-bordered tbody td {
        border-left-width: 0;
        border-bottom-width: 0;
        border: 0;
    }     
</style>

<div id="loading" class="divLoading" style="display: none;">
  <p><img src="<?php echo base_url(); ?>assets/images/loading-4.gif"/></p>
</div>

<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header" style="text-align: center;">
    <h1> DRA Training – Online Inspection Form </h1>
    <h3 style="color: red"> (This form will be filled in by the inspector while inspecting the batch)</h3>
    <?php $drauserdata = $this->session->userdata('dra_admin');?> 
  </section>
  <section class="content-header">
    <h4> The DRA Training Programs are to be conducted as per the latest terms and conditions as laid down by IIBF and abided by all the DRA accredited Institutions / Agencies.  </h4>
    <h4> Below mentioned format is to be filled with the fact of the training activities as delivered by the agencies and experienced by the assigned Inspector.  </h4>

    <h4><b>Points to Note :</b></h4>

    <h4>  1. Report once submitted cannot be edited so please fill the form carefully.  </h4>
    <h4>  2. Before inspection, kindly refer ‘Training Batch Status Logs’ under ‘View’ in ‘Batch List’ tab wherein information regarding changes to training details / schedule will be available. </h4>
    <h4>  3. This form will be available only when sessions are ‘Live’ i.e., training is going on. </h4>
    <h4>  4. ‘Reset File’ option will clear all filled-in information.  </h4>
    <h4>  5. Photographs of Candidates / Qualification Certificates of Candidates in the Attendance list may not be visible until end of day 3 of training as Institutes are required to upload the same until End of Day 3 of training.  </h4>
    <h4>  6. If selecting ‘Platform Link’ does not lead to the training sessions, try joining the session using Login Id and Password provided in the table for login credentials. In case that does not work, kindly call the Contact Persons whose contact nos. are provided in the Inspection form.  </h4>
    <h4>  7. Once submitted, your Inspection report will be visible under the 3rd Tab of ‘Inspection Report’. </h4>
    <h4>  8. Kindly ignore Reminder e-mails received in case Report of the concerned batch has been submitted. </h4>
    <h4>  9. Kindly verify the Qualification of Candidates by viewing the Qualification Certificates which have been uploaded by Agencies for all enrolled Candidates. For information,Candidates having qualification of only Graduation and above are eligible to enroll in a 50 Hours Batch. Others can enroll in 100 Hours Batches only. Certificates are available in the Attendance List which can be enlarged by clicking on the given images.<b> However, you are requested to be careful when marking a Candidate with 'Incorrect' certificate as such Candidate will be put on Hold and will not be able to apply for the examination.</b> </h4>
    

  </section>
  <div class="col-md-12"> <br />
  </div>
  <!-- Main content -->
  <section class="content">
    <?php if($this->session->flashdata('error')!=''){ ?>
      <!-- <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $this->session->flashdata('error'); ?>
      </div> -->
      <?php } if($this->session->flashdata('success')!='') { 
        $successMassege = $this->session->flashdata('success');
      ?>
      <!-- <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $this->session->flashdata('success'); ?>
      </div> -->
      <?php }
      
      if($error_msg!=''){?>
      <!-- <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $error_msg; ?>
      </div> -->
    <?php } 
    
    if($file_error_msg!=''){?>
      <!-- <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $file_error_msg; ?>
      </div> -->
    <?php } ?> 
    
    <form method="post" name="appfrom" action="" id="inspector_from"  enctype="multipart/form-data" >
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info box-solid disabled">
            <div class="box-header with-border">
              <h3 class="box-title">Select Batch for Inspection</h3>
              <div class="box-body">
                <div class="row">
                  <div class="col-md-10">
                    <select class="form-control" name="batch_id" id="batch_id">
                      <option value="">Select Batch</option>
                      <?php 
                        foreach ($all_batch as $key => $value) {
                          $overall_compliance_list = $value['overall_compliance_list'];
                          $overall_compliance_str  = '';

                            if (!empty($overall_compliance_list)) {
                              // Split the string into an array using ',' as a delimiter
                              $arr_overall_compliance = explode(',', $overall_compliance_list);

                              // Check if the array has elements
                              if (count($arr_overall_compliance) > 0) {
                                foreach ($arr_overall_compliance as $akey => $overall_compliance_value) {
                                  // Trim spaces and add numbering with a dot and a space
                                  $overall_compliance_str .= ($akey + 1) . '. ' . trim($overall_compliance_value) . ' ';
                                }
                              }
                            }
                        ?>

                        <option value="<?php echo $value['id']; ?>" <?php if($batch_id == $value['id']) { echo "selected"; }  ?> > <?php echo $value['batch_code'].", (".$value['hours']." Hours, ".date("d/m/Y", strtotime($value['batch_from_date']))." To ".date("d/m/Y", strtotime($value['batch_to_date'])).", ".$value['timing_from']." To ".$value['timing_to'].", ".$value['training_medium'].")".' ('.$value['short_inst_name'].')'.' ('.$value['inspector_name'].')'. ' Reported Count ('.$value['reported'].') '.$overall_compliance_str; ?></option>
                        <?php }
                      ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <input type="hidden" name="insp_no" id="insp_no" value="<?php echo $inspection_no; ?>">
      
      <h4 id="inspection_no">Inspection No.: <?php echo $inspection_no; ?></h4>
      
      <div class="table-responsive box box-info box-solid disabled" width="100%" id="batch_autofilled_div">
        <table class="table table-bordered table-striped" style="word-wrap:anywhere; background:<?php echo $div_class; ?>;">
          <tbody>
            <tr>
              <td width="29%"><strong>Name of the DRA Accredited Institution/Bank/FI:</strong></td>
              <td width="20%" id="agency_name"><?php echo $batch[0]['institute_name']; ?></td>
              <td width="2%"></td>
              <td width="29%"><strong>Batch Type:</strong></td>
              <td width="20%" id="training_time_duration"> <?php echo $batch[0]['hours'].' Hours'; ?> </td>
            </tr>
            <tr>
              <td width="29%"><strong> Batch Code :</strong></td>
              <td width="20%" id="batch_code"><?php echo $batch[0]['batch_code']; ?></td>
              <td width="2%"></td>
              <td width="29%"><strong>Batch Duration:</strong></td>
              <td width="20%" id="duration"><?php echo $batch[0]['batch_from_date'].' To '.$batch[0]['batch_to_date']; ?></td>
            </tr>
            <tr>
              <td width="29%"><strong>Daily Training Timing:</strong></td>
              <td width="20%" id="daily_training_timing"><?php echo $batch[0]['timing_from'].' To '.$batch[0]['timing_to']; ?></td>
              <td width="2%"></td>
              <td width="29%"><strong>Total No. of Candidates (Estimated)/ No. of Candidates enrolled in the Batch:</strong></td>
              <td width="20%" id="candidate_count"><?php echo $batch[0]['total_candidates'].'/'.$batch[0]['registered_candidate']; ?></td>
            </tr>
            <?php 
              $first_faculty_photo     = $batch[0]['first_faculty_photo'];
              $sec_faculty_photo       = $batch[0]['sec_faculty_photo'];
              $add_first_faculty_photo = $batch[0]['add_first_faculty_photo'];
              $add_sec_faculty_photo   = $batch[0]['add_sec_faculty_photo'];

              $faculty_photo_url = base_url('uploads/faculty_photo').'/';
                        
              // Check if the first faculty photo exists
              $first_faculty_photo_url = '';
              if (!empty($first_faculty_photo) && file_exists('uploads/faculty_photo/'.$first_faculty_photo)) {
                  $first_faculty_photo_url = $faculty_photo_url.$first_faculty_photo;
              }

              // Check if the second faculty photo exists
              $sec_faculty_photo_url = '';
              if (!empty($sec_faculty_photo) && file_exists('uploads/faculty_photo/'.$sec_faculty_photo)) {
                  $sec_faculty_photo_url = $faculty_photo_url.$sec_faculty_photo;
              }

              // Check if the additional first faculty photo exists
              $add_first_faculty_photo_url = '';
              if (!empty($add_first_faculty_photo) && file_exists('uploads/faculty_photo/'.$add_first_faculty_photo)) {
                  $add_first_faculty_photo_url = $faculty_photo_url.$add_first_faculty_photo;
              }

              // Check if the additional second faculty photo exists
              $add_sec_faculty_photo_url = '';
              if (!empty($add_sec_faculty_photo) && file_exists('uploads/faculty_photo/'.$add_sec_faculty_photo)) {
                  $add_sec_faculty_photo_url = $faculty_photo_url.$add_sec_faculty_photo;
              }

            ?>

            <tr>
              <td width="29%"><strong>Assigned Faculty (main 1):</strong></td>
              <td width="20%">
              <?php if ($first_faculty_photo_url != '') { ?>
                <img height="90" width="70" src="<?php echo $first_faculty_photo_url; ?>" alt=""/><br>
                <a href="<?php echo $first_faculty_photo_url; ?>" target="_blank"> <?php echo $batch[0]['first_faculty_code'].'_'.$batch[0]['first_faculty_name']; ?> </a>
              <?php } else { ?>
                <?php echo $batch[0]['first_faculty_code'].'_'.$batch[0]['first_faculty_name']; ?>
              <?php } ?>
              </td>
              <td width="2%"></td>
              <td width="29%"><strong>Assigned Faculty(main 2):</strong></td>
              <td width="20%">
              <?php if ($sec_faculty_photo_url != '') { ?>
                <img height="90" width="70" src="<?php echo $sec_faculty_photo_url; ?>" alt=""/><br>
                <a href="<?php echo $sec_faculty_photo_url; ?>" target="_blank"> <?php echo $batch[0]['sec_faculty_code'].'_'.$batch[0]['sec_faculty_name']; ?> </a>
              <?php } else { ?>
                <?php echo $batch[0]['sec_faculty_code'].'_'.$batch[0]['sec_faculty_name']; ?>
              <?php } ?>
              </td>
            </tr>
            <tr>
              <td width="29%"><strong>Assigned Faculty (additional 1):</strong></td>
              <td width="20%">
              <?php if ($add_first_faculty_photo_url != '') { ?>
                <img height="90" width="70" src="<?php echo $add_first_faculty_photo_url; ?>" alt=""/><br>
                <a href="<?php echo $add_first_faculty_photo_url; ?>" target="_blank"> <?php echo $batch[0]['add_first_faculty_code'].'_'.$batch[0]['add_first_faculty_name']; ?> </a>
              <?php } else { ?>
              <?php echo $batch[0]['add_first_faculty_code'].'_'.$batch[0]['add_first_faculty_name']; ?> 
              <?php } ?>   
              </td>
              <td width="2%"></td>
              <td width="29%"><strong>Assigned Faculty(additional 2):</strong></td>
              <td width="20%">
              <?php if ($add_sec_faculty_photo_url != '') { ?>
                <img height="90" width="70" src="<?php echo $add_sec_faculty_photo_url; ?>" alt=""/><br>
                <a href="<?php echo $add_sec_faculty_photo_url; ?>" target="_blank"> <?php echo $batch[0]['add_sec_faculty_code'].'_'.$batch[0]['add_sec_faculty_name']; ?> </a>
              <?php } else { ?>
                <?php echo $batch[0]['add_sec_faculty_code'].'_'.$batch[0]['add_sec_faculty_name']; ?>                  
              <?php } ?>     
              </td>
            </tr>
            <tr>
              <td width="29%"><strong>Co-ordinator name and Mobile no. :</strong></td>
              <td width="20%" id="coordinator_name"><?php echo $batch[0]['contact_person_name'].' ('. $batch[0]['contact_person_phone'].')';?></td>
              <td width="2%"></td>
              <td width="20%"><strong>Co-ordinator name and Mobile no. (additional):</strong></td>
              <td width="29%" id="additional_coordinator_name">
                <?php 
                  if($batch[0]['alt_contact_person_phone'] != '' && $batch[0]['alt_contact_person_phone'] != '')
                  {
                    echo $batch[0]['alt_contact_person_name'].' ('. $batch[0]['alt_contact_person_phone'].')';  
                  }
                ?></td>
            </tr>
            
            <tr>
              <td width="20%"><strong>Training Language :</strong></td>
              <td width="29%" id="training_language"><?php echo $batch[0]['training_medium']; ?></td>
              <td width="2%"></td>
              <td width="29%"><strong>Document:</strong></td>
              <td width="20%" id="document"><a href="<?php echo site_url('uploads/training_schedule/'.$batch[0]['training_schedule']); ?>" id="document_href" target="_blank">View Document</a></td>
            </tr>
            
            <?php if( $batch[0]['batch_online_offline_flag'] == 1) { ?>
              <tr class="online_offline_flag">
                <td width="29%"><strong>Name of the on-line platform:</strong></td>
                <td width="20%" id="batch_training_platform"><?php echo $batch[0]['online_training_platform']; ?></td>
                <td width="2%"></td>
                <td width="29%"><strong>Platform Link:</strong></td>
                <td width="20%"><a href="<?php echo $batch[0]['platform_link']; ?>" id="platform_link_href" target="_blank"><?php echo $batch[0]['platform_link']; ?></a></td>
              </tr>
              
              <tr class="online_offline_flag">
                <td width="29%"><strong>Login ID/Password:</strong></td>
                <td id="login_pwd_tbl">
                  <table border="solid 1%">
                    <thead>
                      <tr>
                        <th width="5%">Login Id</th>
                        <th width="5%">Password</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($batch_login_details as $key => $value) { ?> 
                        <tr>
                          <td width="5%"><?php echo $value['login_id']; ?> </td>
                          <td width="5%"><?php echo base64_decode($value['password']) ?></td>
                        </tr>
                      <?php } ?>    
                    </tbody>  
                  </table>
                </td>
              </tr>
            <?php } ?>
            <tr>
              <td width="29%"><strong>Date/Start Time of Inspection:</strong></td>
              <td width="20%"><?php echo set_value('inspection_start_time') != null && set_value('inspection_start_time') != '' ? set_value('inspection_start_time') : date('Y-m-d H:i:s'); ?></td>
              <td width="2%"></td>
              <td width="29%"><strong>Inspector Name/ID:</strong></td>
              <td width="20%" ><?php echo $inspector_name.'/'.$inspector_id; ?></td>
            </tr>
            
          </tbody>
        </table>
      </div>
      
      <input type="hidden" name="inspection_start_time" value="<?php echo set_value('inspection_start_time') != null && set_value('inspection_start_time') != '' ? set_value('inspection_start_time') : date('Y-m-d H:i:s'); ?>">
      
      <div class="row" id="batch_inspecton_div">
        <div class="col-md-12">
          <div class="box box-info box-solid disabled">
            <div class="box-header with-border">
              <h3 class="box-title">Batch Inspection</h3>
              <div class="box-tools pull-right">
                <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
              </div>
              <!-- /.box-tools --> 
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="display: block;">
              
              
              <input type="hidden" name="agency_id" id="agency_id" value="<?php echo $batch[0]['agency_id']; ?>">
              
              <div class="table-responsive ">
                <table class="table table-bordered table-striped" style="word-wrap:anywhere; background:<?php echo $div_class; ?>;">
                  <tbody>
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>1</strong></td>
                      <td width="48%"><strong>Number of candidates logged-in at start of visit to the platform (excluding self / faculty/ coordinator or any other administrator)</strong><span style="color:#F00">*</span></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="candidates_loggedin" id="candidates_loggedin" maxlength="100" placeholder="" required="required"><?php echo set_value('candidates_loggedin'); ?></textarea>
                      <br> 
                      <span class="error" id="candidates_loggedin_error"></span>  
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>2</strong></td>
                      <td width="48%"><strong>Whether the declared Link / Platform for the training got changed (Yes / No). If Yes, mention the Link / Name of the Platform for the training purpose.</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="platform_name" maxlength="100" placeholder=""><?php echo set_value('platform_name'); ?></textarea>
                    </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>3</strong></td>
                      <td width="48%"><strong>Whether there are multiple logins with same name (Yes / No)? If Yes, how many such multiple logins are there?</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="multiple_login_same_name" maxlength="100" placeholder="" ><?php echo set_value('multiple_login_same_name'); ?></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>4</strong></td>
                      <td width="48%"><strong>Whether log-ins with instrument name (Samsung/oppo etc) is there (Yes / No). If Yes, how many such log-ins?</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="instrument_name" maxlength="100" placeholder="" > <?php echo set_value('instrument_name'); ?> </textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>5</strong></td>
                      <td width="48%"><strong>Whether any issues were faced while logging-in onto the Online Platform (e.g. wrong log-in credentials / waited for more than 2 minutes in waiting room / taking you into a platform of a different link / only buffering for minutes etc.)</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="issues" maxlength="1000" placeholder=""><?php echo set_value('issues'); ?></textarea>
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>6</strong></td>
                      <td width="48%"><strong>Whether virtual recording is ‘On’ or “not On” or started after your joining / insisting for the same. In case the session recording is not on, mention the reason of such situation.</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="training_session" maxlength="100" placeholder=""><?php echo set_value('training_session'); ?></textarea>
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>7</strong></td>
                      <td width="48%"><strong>Training Details:</strong></td>
                      <td width="48%"></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>(i) No. of candidates available during training sessions</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="session_candidates" maxlength="100" placeholder=""><?php echo set_value('session_candidates'); ?></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>(ii) Is the training going on as per session plan shared by the Agency (can be confirmed from the Faculty) </strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="training_session_plan" maxlength="100" placeholder=""><?php echo set_value('training_session_plan'); ?></textarea></td>
                    </tr>
                                        
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>8</strong></td>
                      <td width="48%"><strong>Attendance:</strong></td>
                      <td width="48%"></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>i. Whether Attendance Sheet is updated by the Agency till the time of inspection (Yes / No).</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="attendance_sheet_updated" maxlength="100" placeholder=""><?php echo set_value('attendance_sheet_updated'); ?></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>ii. Mode of taking attendance (Online / Screen Shot / Manual calling etc.)</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="attendance_mode" maxlength="100" placeholder=""><?php echo set_value('attendance_mode'); ?></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>iii. Whether the Attendance Sheet is shown promptly to the Inspector on demand (Yes / No).</strong></td>
                      <td width="48%" > &nbsp; <textarea style="width:100%; text-align:left;" name="attendance_shown" maxlength="100" placeholder=""><?php echo set_value('attendance_shown'); ?></textarea>
                      </td>
                    </tr>  
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>9</strong></td>
                      <td width="48%"><strong>Is there any group of candidates attending the sessions from one place through a single device (Yes / No). If Yes, mention the candidates’ count and reason / situation in brief.
                      </strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="candidate_count_device" maxlength="1000" placeholder=""><?php echo set_value('candidate_count_device'); ?></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>10</strong></td>
                      <td width="48%"><strong>Faculty Details:</strong></td>
                      <td width="48%"></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>a) Whether Name / Code of Faculty is displayed on the platform (Yes / No).</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="actual_faculty" maxlength="100" placeholder=""><?php echo set_value('actual_faculty'); ?></textarea></strong>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>b) Name / Code of Faculty taking session</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="faculty_taking_session" maxlength="100" placeholder=""><?php echo set_value('faculty_taking_session'); ?></textarea></strong>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>c) If the Faculty who is taking session is different from the declared one, please mention:
                        <br>i. Name and Qualification (highest) of the Faculty
                      </strong>
                      </td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="name_qualification" maxlength="1000" placeholder=""><?php echo set_value('name_qualification'); ?></textarea>
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>
                        ii. No. of days / sessions she/he has taken / will take
                      </strong>
                      </td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="no_of_days" maxlength="1000" placeholder=""><?php echo set_value('no_of_days'); ?></textarea>
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>
                        iii. Reason of such change in faculty
                      </strong>
                      </td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="reason_of_change_in_faculty" maxlength="1000" placeholder=""><?php echo set_value('reason_of_change_in_faculty'); ?></textarea>
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>
                        iv. Whether the Faculty is having earlier experience in teaching / training in BFSI sector (mention in brief).
                      </strong>
                      </td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="experience_teaching_training_BFSI_sector" maxlength="1000" placeholder=""><?php echo set_value('experience_teaching_training_BFSI_sector'); ?></textarea>
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>d) Language in which the Faculty is taking the session</strong></td>
                      <td width="48%">
                        <textarea style="width:100%; text-align:left;" name="faculty_language" maxlength="100" placeholder=""><?php echo set_value('faculty_language'); ?></textarea>
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>e) The Faculty is taking sessions for how many hrs/min per day</strong></td>
                      <td width="48%">
                        <textarea style="width:100%; text-align:left;" name="faculty_session_time" maxlength="100" placeholder=""><?php echo set_value('faculty_session_time'); ?></textarea>
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>f) Whether minimum 2 faculties are taking sessions to complete the 50 / 100 hours training in the Batch.</strong></td>
                      <td width="48%">
                        <textarea style="width:100%; text-align:left;" name="two_faculty_taking_session" maxlength="100" placeholder=""><?php echo set_value('two_faculty_taking_session'); ?></textarea>
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>g) Whether the language(s) used by the Faculty is understandable by the candidates (can be confirmed from the participants).</strong></td>
                      <td width="48%">
                        <textarea style="width:100%; text-align:left;" name="faculty_language_understandable" maxlength="100" placeholder=""><?php echo set_value('faculty_language_understandable'); ?></textarea>
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>h) Whether the online training tools like whiteboard / PPT / PDF / Documents are used while delivering lectures.</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="whiteboard_ppt_pdf_used" maxlength="100" placeholder=""><?php echo set_value('whiteboard_ppt_pdf_used'); ?></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>11</strong></td>
                      <td width="48%"><strong>Whether the faculty (in case of new faculty only) and all the candidates have attended preparatory / briefing session on the etiquettes of the upcoming DRA training (Yes / No).</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="session_on_etiquettes" maxlength="100" placeholder=""><?php echo set_value('session_on_etiquettes'); ?></textarea> </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>12</strong></td>
                      <td width="48%"><strong>Whether the faculty and trainees were conversant with the process of on-line training.</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="faculty_trainees_conversant" maxlength="100" placeholder=""><?php echo set_value('faculty_trainees_conversant'); ?></textarea> </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>13</strong></td>
                      <td width="48%"><strong>Whether the candidates could recognise the name of the training providing agency / institution (Yes / No).</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="candidates_recognise" maxlength="100" placeholder=""><?php echo set_value('candidates_recognise'); ?></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>14</strong></td>
                      <td width="48%"><strong>Whether candidates were given "Handbook on debt recovery" by the concerned agency.</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="handbook_on_debt_recovery" maxlength="100" placeholder=""><?php echo set_value('handbook_on_debt_recovery'); ?></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>15</strong></td>
                      <td width="48%"><strong>Whether candidates are provided with other study materials in word/pdf format by the agency).</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="other_study_materials" maxlength="100" placeholder=""><?php echo set_value('other_study_materials'); ?></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>16</strong></td>
                      <td width="48%"><strong>Whether the training was conducted without any interruption/ disturbances/ noises?</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="training_conduction" maxlength="100" placeholder=""><?php echo set_value('training_conduction'); ?></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>17</strong></td>
                      <td width="48%"><strong>Batch Coordinator:</strong></td>
                      <td width="48%"></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>a) Whether Name of Batch Coordinator is displayed on the virtual platform with Batch Code (Yes / No).</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="batch_coordinator_available" maxlength="100" placeholder=""><?php echo set_value('batch_coordinator_available'); ?></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>b) Name / Code of the Coordinator who is available in the Session</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="coordinator_available_name" maxlength="100" placeholder=""><?php echo set_value('coordinator_available_name'); ?></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>c) The Coordinator is whether originally allotted or not (Yes/ No). In case No, mention the name and contact no. of the available Coordinator.</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="current_coordinator_available_name" maxlength="100" placeholder=""><?php echo set_value('current_coordinator_available_name'); ?></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>18</strong></td>
                      <td width="48%"><strong>Any irregularity(ies) consistently / frequently persist despite repetitive reminders for rectification.</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="any_irregularity" maxlength="1000" placeholder=""><?php echo set_value('any_irregularity'); ?></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>19</strong></td>
                      <td width="48%"><strong>Assessment / rating (viz. 1-Poor / 2-Average / 3-Good / 4-Excellent) consequent to overall impression during visit to the virtual training session</strong></td>
                      <td width="48%">
                        
                      <!-- <td width="48%"><textarea style="width:100%; text-align:left;" name="assessment" maxlength="1000" placeholder=""></textarea> --></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>a) Quality of Teaching:
                        <br>i. Level of interaction with candidates
                      </strong></td>
                      <td width="48%">
                        <strong>
                          <br><input  type="radio" class="minimal radio1" name="teaching_quality_interaction_with_candidates" value="Poor" <?php if(set_value('teaching_quality_interaction_with_candidates') == 'Poor') {?> checked <?php } ?>>Poor
                          <input  type="radio" class="minimal radio1" name="teaching_quality_interaction_with_candidates" value="Average" <?php if(set_value('teaching_quality_interaction_with_candidates') == 'Average') {?> checked <?php } ?>>Average
                          <input  type="radio" class="minimal radio1" name="teaching_quality_interaction_with_candidates" value="Good" <?php if(set_value('teaching_quality_interaction_with_candidates') == 'Good') {?> checked <?php } ?>>Good
                          <input  type="radio" class="minimal radio1" name="teaching_quality_interaction_with_candidates" value="Excellent" <?php if(set_value('teaching_quality_interaction_with_candidates') == 'Excellent') {?> checked <?php } ?>>Excellent
                          &nbsp;&nbsp;&nbsp;
                          <a href="javascript:void(0);" onclick="Uncheck('radio1')">Uncheck</a>
                        </strong>
                        <!-- <textarea style="width:100%; text-align:left;" name="teaching_quality" maxlength="100" placeholder=""></textarea> -->
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>
                        ii. Understanding with curiosity while teaching (especially  during soft-skill session)
                      </strong></td>
                      <td width="48%"><strong>
                        <input  type="radio" class="minimal radio2" name="teaching_quality_softskill_session" value="Poor" <?php if(set_value('teaching_quality_softskill_session') == 'Poor') {?> checked <?php } ?>>Poor
                        <input  type="radio" class="minimal radio2" name="teaching_quality_softskill_session" value="Average" <?php if(set_value('teaching_quality_softskill_session') == 'Average') {?> checked <?php } ?>>Average
                        <input  type="radio" class="minimal radio2" name="teaching_quality_softskill_session" value="Good" <?php if(set_value('teaching_quality_softskill_session') == 'Good') {?> checked <?php } ?>>Good
                        <input  type="radio" class="minimal radio2" name="teaching_quality_softskill_session" value="Excellent" <?php if(set_value('teaching_quality_softskill_session') == 'Excellent') {?> checked <?php } ?>>Excellent
                        &nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0);" onclick="Uncheck('radio2')">Uncheck</a>
                      </strong>
                      <!-- <textarea style="width:100%; text-align:left;" name="teaching_quality" maxlength="100" placeholder=""></textarea> -->
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>b) Candidates' attentiveness and participation
                      </strong></td>
                      <td width="48%"><strong>
                        <input  type="radio" class="minimal radio3" name="candidates_attentiveness" value="Poor" <?php if(set_value('candidates_attentiveness') == 'Poor') {?> checked <?php } ?>>Poor
                        <input  type="radio" class="minimal radio3" name="candidates_attentiveness" value="Average" <?php if(set_value('candidates_attentiveness') == 'Average') {?> checked <?php } ?>>Average
                        <input  type="radio" class="minimal radio3" name="candidates_attentiveness" value="Good" <?php if(set_value('candidates_attentiveness') == 'Good') {?> checked <?php } ?>>Good
                        <input  type="radio" class="minimal radio3" name="candidates_attentiveness" value="Excellent" <?php if(set_value('candidates_attentiveness') == 'Excellent') {?> checked <?php } ?>>Excellent
                        &nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0);" onclick="Uncheck('radio3')">Uncheck</a>
                      </strong>
                      <!-- <textarea style="width:100%; text-align:left;" name="candidates_attentiveness" maxlength="100" placeholder=""></textarea> -->
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>c) Candidates' Attitude and their Behaviour</strong></td>
                      <td width="48%"><strong>
                        <input  type="radio" class="minimal radio4" name="DRA_attitude_behaviour" value="Poor" <?php if(set_value('DRA_attitude_behaviour') == 'Poor') {?> checked <?php } ?>>Poor
                        <input  type="radio" class="minimal radio4" name="DRA_attitude_behaviour" value="Average" <?php if(set_value('DRA_attitude_behaviour') == 'Average') {?> checked <?php } ?>>Average
                        <input  type="radio" class="minimal radio4" name="DRA_attitude_behaviour" value="Good" <?php if(set_value('DRA_attitude_behaviour') == 'Good') {?> checked <?php } ?>>Good
                        <input  type="radio" class="minimal radio4" name="DRA_attitude_behaviour" value="Excellent" <?php if(set_value('DRA_attitude_behaviour') == 'Excellent') {?> checked <?php } ?>>Excellent
                        &nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0);" onclick="Uncheck('radio4')">Uncheck</a>
                      </strong>
                      <!-- <textarea style="width:100%; text-align:left;" name="DRA_attitude_behaviour" maxlength="100" placeholder=""></textarea> -->
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>d) Quality of learning by DRAs:
                        <br>i.  Interaction with Faculty
                      </strong></td>
                      <td width="48%"><strong>
                        <br><input  type="radio" class="minimal radio5" name="learning_quality_interaction_with_faculty" value="Poor" <?php if(set_value('learning_quality_interaction_with_faculty') == 'Poor') {?> checked <?php } ?>>Poor
                        <input  type="radio" class="minimal radio5" name="learning_quality_interaction_with_faculty" value="Average" <?php if(set_value('learning_quality_interaction_with_faculty') == 'Average') {?> checked <?php } ?>>Average
                        <input  type="radio" class="minimal radio5" name="learning_quality_interaction_with_faculty" value="Good" <?php if(set_value('learning_quality_interaction_with_faculty') == 'Good') {?> checked <?php } ?>>Good
                        <input  type="radio" class="minimal radio5" name="learning_quality_interaction_with_faculty" value="Excellent" <?php if(set_value('learning_quality_interaction_with_faculty') == 'Excellent') {?> checked <?php } ?>>Excellent
                        &nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0);" onclick="Uncheck('radio5')">Uncheck</a>
                      </strong>
                      <!-- <textarea style="width:100%; text-align:left;" name="learning_quality" maxlength="100" placeholder=""></textarea> -->
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>
                        ii. Response to queries made by faculty / inspector
                      </strong></td>
                      <td width="48%"><strong>
                        <input  type="radio" class="minimal radio6" name="learning_quality_response_to_queries" value="Poor" <?php if(set_value('learning_quality_response_to_queries') == 'Poor') {?> checked <?php } ?>>Poor
                        <input  type="radio" class="minimal radio6" name="learning_quality_response_to_queries" value="Average" <?php if(set_value('learning_quality_response_to_queries') == 'Average') {?> checked <?php } ?>>Average
                        <input  type="radio" class="minimal radio6" name="learning_quality_response_to_queries" value="Good" <?php if(set_value('learning_quality_response_to_queries') == 'Good') {?> checked <?php } ?>>Good
                        <input  type="radio" class="minimal radio6" name="learning_quality_response_to_queries" value="Excellent" <?php if(set_value('learning_quality_response_to_queries') == 'Excellent') {?> checked <?php } ?>>Excellent
                        &nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0);" onclick="Uncheck('radio6')">Uncheck</a>
                      </strong>
                      <!-- <textarea style="width:100%; text-align:left;" name="learning_quality" maxlength="100" placeholder=""></textarea> -->
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>e) Effectiveness of training</strong></td>
                      <td width="48%"><strong>
                        <input  type="radio" class="minimal radio7" name="teaching_effectiveness" value="Poor" <?php if(set_value('teaching_effectiveness') == 'Poor') {?> checked <?php } ?>>Poor
                        <input type="radio" class="minimal radio7" name="teaching_effectiveness" value="Average" <?php if(set_value('teaching_effectiveness') == 'Average') {?> checked <?php } ?>>Average
                        <input  type="radio" class="minimal radio7" name="teaching_effectiveness" value="Good" <?php if(set_value('teaching_effectiveness') == 'Good') {?> checked <?php } ?>>Good
                        <input  type="radio" class="minimal radio7" name="teaching_effectiveness" value="Excellent" <?php if(set_value('teaching_effectiveness') == 'Excellent') {?> checked <?php } ?>>Excellent
                        &nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0);" onclick="Uncheck('radio7')">Uncheck</a>
                      </strong>
                      <!-- <textarea style="width:100%; text-align:left;" name="teaching_effectiveness" maxlength="100" placeholder=""></textarea>  -->
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>f) Curriculum covered with reference to the Syllabus </strong></td>
                      <td width="48%"><strong>
                        <input  type="radio" class="minimal radio8" name="curriculum_covered" value="Poor" <?php if(set_value('curriculum_covered') == 'Poor') {?> checked <?php } ?>>Poor
                        <input  type="radio" class="minimal radio8" name="curriculum_covered" value="Average" <?php if(set_value('curriculum_covered') == 'Average') {?> checked <?php } ?>>Average
                        <input  type="radio" class="minimal radio8" name="curriculum_covered" value="Good" <?php if(set_value('curriculum_covered') == 'Good') {?> checked <?php } ?>>Good
                        <input  type="radio" class="minimal radio8" name="curriculum_covered" value="Excellent" <?php if(set_value('curriculum_covered') == 'Excellent') {?> checked <?php } ?>>Excellent
                        &nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0);" onclick="Uncheck('radio8')">Uncheck</a>
                      </strong>
                      <!-- <textarea style="width:100%; text-align:left;" name="curriculum_covered" maxlength="100" placeholder=""></textarea> -->
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>g) Overall compliance on:
                        <br>i.  Training delivery
                      </strong></td>
                      <td width="48%"><strong>
                        <br><input  type="radio" class="minimal radio9" name="overall_compliance_training_delivery" value="Poor" <?php if(set_value('overall_compliance_training_delivery') == 'Poor') {?> checked <?php } ?>>Poor
                        <input  type="radio" class="minimal radio9" name="overall_compliance_training_delivery" value="Average" <?php if(set_value('overall_compliance_training_delivery') == 'Average') {?> checked <?php } ?>>Average
                        <input  type="radio" class="minimal radio9" name="overall_compliance_training_delivery" value="Good" <?php if(set_value('overall_compliance_training_delivery') == 'Good') {?> checked <?php } ?>>Good
                        <input  type="radio" class="minimal radio9" name="overall_compliance_training_delivery" value="Excellent" <?php if(set_value('overall_compliance_training_delivery') == 'Excellent') {?> checked <?php } ?>>Excellent
                        &nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0);" onclick="Uncheck('radio9')">Uncheck</a>
                      </strong>
                      <!-- <textarea style="width:100%; text-align:left;" name="overall_compliance_training_delivery_coordination" maxlength="100" placeholder=""></textarea> -->
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>ii. Training coordination</strong></td>
                      <td width="48%"><strong>
                        <input  type="radio" class="minimal radio10" name="overall_compliance_training_coordination" value="Poor" <?php if(set_value('overall_compliance_training_coordination') == 'Poor') {?> checked <?php } ?>>Poor
                        <input  type="radio" class="minimal radio10" name="overall_compliance_training_coordination" value="Average" <?php if(set_value('overall_compliance_training_coordination') == 'Average') {?> checked <?php } ?>>Average
                        <input  type="radio" class="minimal radio10" name="overall_compliance_training_coordination" value="Good" <?php if(set_value('overall_compliance_training_coordination') == 'Good') {?> checked <?php } ?>>Good
                        <input  type="radio" class="minimal radio10" name="overall_compliance_training_coordination" value="Excellent" <?php if(set_value('overall_compliance_training_coordination') == 'Excellent') {?> checked <?php } ?>>Excellent
                        &nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0);" onclick="Uncheck('radio10')">Uncheck</a>
                      </strong>
                      <!-- <textarea style="width:100%; text-align:left;" name="overall_compliance_training_delivery_coordination" maxlength="100" placeholder=""></textarea> -->
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>20</strong></td>
                      <td width="48%"><strong>Any other observations with respect to non-adherence to the conditions stipulated by IIBF for conducting on-line DRA Training.</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="other_observations" maxlength="1000" placeholder=""><?php echo set_value('other_observations'); ?></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>21</strong></td>
                      <td width="48%"><strong>Overall Observation of the Inspector on the training of the DRA Batch.</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="overall_observation" maxlength="1000" placeholder=""><?php echo set_value('overall_observation'); ?></textarea></td>
                    </tr>         
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>22</strong></td>
                      <td width="48%"><strong>Over all compliance on imparting of DRA Training</strong></td>
                      <td width="48%"><strong>
                        <input  type="radio" class="minimal radio11" name="overall_compliance" value="Poor" <?php if(set_value('overall_compliance') == 'Poor') {?> checked <?php } ?> required>Poor
                        <input  type="radio" class="minimal radio11" name="overall_compliance" value="Average" <?php if(set_value('overall_compliance') == 'Average') {?> checked <?php } ?> required>Average
                        <input  type="radio" class="minimal radio11" name="overall_compliance" value="Good" <?php if(set_value('overall_compliance') == 'Good') {?> checked <?php } ?> required>Good
                        <input  type="radio" class="minimal radio11" name="overall_compliance" value="Excellent" <?php if(set_value('overall_compliance') == 'Excellent') {?> checked <?php } ?> required>Excellent
                        &nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0);" onclick="Uncheck('radio11')">Uncheck</a>
                        <br> 
                      <span class="error" id="overall_compliance_error"></span> 
                      </strong></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>23</strong></td>
                      <td width="48%"><strong>Attachment, if any</strong></td>
                      <td width="48%"><input type="file" class="form-control" name="attachment" id="attachment" onchange="validateDoc(event, 'attachment_error')" ></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"></td>
                      <td width="48%"></td>
                      <td width="48%"><span class="note" id="attachment_note">Note: Please Upload only .txt, .doc, .docx, .pdf, .jpg, .png, .jpeg Files with size upto 10 MB</span></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"></td>
                      <td width="48%"></td>
                      <td width="48%"><button id="btn-file-reset" class="btn-info" type="button">Reset file</button></td>
                    </tr>
                    
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"></td>
                      <td width="48%"></td>
                      <td><span class="note-error" id="attachment_error"> </span></td>
                    </tr> 
                  </tbody>
                </table>
              </div>
              
            </div>
            <!-- /.box-body --> 
          </div>
          <!-- /.box --> 
        </div>
        
        <div class="col-xs-12">
          <div class="box-header">
            <h3 class="box-title">Batch Candidate's Details</h3>
            <div class="box-tools pull-right">
              <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
            </div>
          </div>
          <div class="box">
            <div class="box-body">
              <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
              <input type="hidden" name="base_url_val" id="base_url_val" value="" />
              <!-- <input type="hidden" name="question_checked_array" id="question_checked_array" value="" /> -->
              
              <div class="table-responsive">
                <table id="listitems" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>S.No.</th> 
                      <th>Training Id</th>
                      <th>Candidate Name</th>
                      <th>Candidate Status</th>
                      <th>DOB</th>
                      <th>Present Count</th>
                      <th>Absent Count</th>
                      <th>ID Proof</th>
                      <!-- <th>Mobile</th> -->
                      <th>Candidate Photo</th>
                      <th>Candidate Photo Status</th>
                      <th>Candidate Photo Remark</th>
                      
                      <!-- <th>ID Proof Verify</th>
                      <th>ID Proof Remark</th> -->
                      <th>Attendance</th>
                      <th>Attendance Remark</th>
                      <th>Qualification Certificate</th>
                      <th>Qualification Certificate Verify</th>
                      <th>Qualification Certificate Remark</th>
                    </tr>
                  </thead>
                  <tbody class="no-bd-y" id="list">
                    <?php 
                      
                      $CI =& get_instance();
                      $CI->load->database();

                      $presentCount = 0;
                      $absentCount  = 0;
                      
                      foreach ($arr_batch_candidates as $key => $batch_candidate) 
                      { 
                        $regId        = $batch_candidate['regid'];
                        $scannedphoto = $batch_candidate['scannedphoto'];
                        $idproofphoto = $batch_candidate['idproofphoto'];
                        $quali_certificate = $batch_candidate['quali_certificate'];

                        $this->db->select('*');
                        $this->db->from('dra_candidate_logs');
                        $this->db->where('form_type','EditOtherDetails Function');
                        $this->db->where('candidate_id',$batch_candidate['regid']);
                        $this->db->where('is_read','0');

                        $incorrecLogCount = $this->db->count_all_results();
                        // if ($batch_candidate['attendance'] = 'Present') {
                          $presentCount = $presentCount+$batch_candidate['present_count'];
                        // } elseif ($batch_candidate['attendance'] = 'Absent') {
                          $absentCount = $absentCount+$batch_candidate['absent_count'];
                        // }

                    ?>
                    <tr <?php if ($incorrecLogCount > 0) { ?> class="blink" <?php } ?>>
                      <td> <?php echo $key+1; ?> </td>
                      <td> <?php echo $batch_candidate['training_id']; ?> </td>
                      <td> <?php echo $batch_candidate['name']; ?> </td>
                      <td> <?php echo $batch_candidate['hold_release']; ?> </td>
                      <td> <?php echo $batch_candidate['dateofbirth']; ?> </td>
                      <td> <?php echo $batch_candidate['present_count']; ?> </td>
                      <td> <?php echo $batch_candidate['absent_count']; ?> </td>
                      <!-- <td> <?php echo $batch_candidate['mobile_no']; ?> </td> -->

                      <td>
                        <?php 
                        $imageUrl = base_url().'uploads/iibfdra/'.$idproofphoto;
                        $headers = get_headers($imageUrl);
                        // Check if the URL returns a 404 status code
                        if (strpos($headers[0], "200") !== false) {  

                        ?> 
                        <a href="<?php echo $imageUrl; ?>" target="_blank">
                        <img height="90" width="70" id="idproof_<?php echo $regId; ?>" src="<?php echo base_url(); ?>uploads/iibfdra/<?php echo $idproofphoto; ?>" alt=""> <?php // echo $scannedphoto; ?>
                        <?php } ?> 
                        </a>
                      </td>
                      
                      <td>
                        <?php 
                        $imageUrl = base_url().'uploads/iibfdra/'.$scannedphoto;
                        $headers = get_headers($imageUrl);
                        // Check if the URL returns a 404 status code
                        if (strpos($headers[0], "200") !== false) {  

                        ?> 
                        <a href="<?php echo $imageUrl; ?>" target="_blank">
                        <img height="90" width="70" id="photo_<?php echo $regId; ?>" src="<?php echo base_url(); ?>uploads/iibfdra/<?php echo $scannedphoto; ?>" alt=""> <?php // echo $scannedphoto; ?>
                        <?php } ?> 
                        </a>
                      </td>

                      <td> 
                        <input type="radio" class="btn_photo_check" id="id_photo_correct_<?php echo $regId; ?>" name="photo_verify[<?php echo $regId; ?>][]" value="Correct" <?php echo set_radio('photo_verify['.$regId.'][]', 'Correct', (set_value('photo_verify['.$regId.'][]') == 'Correct')); ?>>Correct<br>
                        <input type="radio" class="minimal" id="id_photo_incorrect_<?php echo $regId; ?>" name="photo_verify[<?php echo $regId; ?>][]" value="Incorrect" <?php echo set_radio('photo_verify['.$regId.'][]', 'Incorrect', (set_value('photo_verify['.$regId.'][]') == 'Incorrect')); ?> onclick="return confirmIncorrectQualificationPhoto();">Incorrect
                      </td>
                      <td> 
                          <textarea row="2" col="5" name="photo_remark[<?php echo $regId; ?>][]" id="photo_remark_<?php echo $regId; ?>"><?php echo set_value('photo_remark['.$regId.'][0]'); ?></textarea> 
                      </td>

                      <!-- <td> 
                        <input type="radio" class="btn_idproof_check" id="id_idproof_correct_<?php echo $regId; ?>" name="idproof_verify[<?php echo $regId; ?>][]" value="Correct" <?php echo set_radio('idproof_verify['.$regId.'][]', 'Correct', (set_value('idproof_verify['.$regId.'][]') == 'Correct')); ?>>Correct<br>
                        <input type="radio" class="minimal" id="id_idproof_incorrect_<?php echo $regId; ?>" name="idproof_verify[<?php echo $regId; ?>][]" value="Incorrect" <?php echo set_radio('idproof_verify['.$regId.'][]', 'Incorrect', (set_value('idproof_verify['.$regId.'][]') == 'Incorrect')); ?> onclick="return confirmIncorrectidProof();">Incorrect
                      </td>
                      <td> 
                          <textarea row="2" col="5" name="idproof_remark[<?php echo $regId; ?>][]" id="idproof_remark_<?php echo $regId; ?>"><?php echo set_value('idproof_remark['.$regId.'][0]'); ?></textarea> 
                      </td> -->


                      <td class="candidate-container" data-regid="<?php echo $regId; ?>" data-training-id="<?php echo $batch_candidate['training_id']; ?>"> 
                        <input type="radio" class="btn_check" id="id_present_<?php echo $regId; ?>" name="attendance[<?php echo $regId; ?>][]" value="Present" <?php echo set_radio('attendance['.$regId.'][]', 'Present', (set_value('attendance['.$regId.'][]') == 'Present')); ?>>Present<br><input type="radio" class="minimal" id="id_absent_<?php echo $regId; ?>" name="attendance[<?php echo $regId; ?>][]" value="Absent" <?php echo set_radio('attendance['.$regId.'][]', 'Absent', (set_value('attendance['.$regId.'][]') == 'Absent')); ?>>Absent
                      </td>
                      <td> 
                        <textarea row="2" col="5" name="remark[<?php echo $regId; ?>][]" id="remark_<?php echo $regId; ?>"><?php echo set_value('remark['.$regId.'][0]'); ?></textarea> 
                      </td>  
                      <td>
                        <?php 
                        $quali_certificate_url = base_url().'uploads/iibfdra/'.$quali_certificate;
                        $headers = get_headers($quali_certificate_url);
                        // Check if the URL returns a 404 status code
                        if (strpos($headers[0], "200") !== false) {  

                        ?> 
                        <a href="<?php echo $quali_certificate_url; ?>" target="_blank">
                          <img height="90" width="70" id="quali_<?php echo $regId; ?>" src="<?php echo base_url(); ?>uploads/iibfdra/<?php echo $quali_certificate; ?>" alt=""> <?php // echo $quali_certificate; ?>
                        <?php } ?>
                        </a> 
                    </td>
                    <td> 
                        <input type="radio" class="btn_quali_check" id="id_correct_<?php echo $regId; ?>" name="quali_verify[<?php echo $regId; ?>][]" value="Correct" <?php echo set_radio('quali_verify['.$regId.'][]', 'Correct', (set_value('quali_verify['.$regId.'][]') == 'Correct')); ?>>Correct<br>
                        <input type="radio" class="minimal" id="id_incorrect_<?php echo $regId; ?>" name="quali_verify[<?php echo $regId; ?>][]" value="Incorrect" <?php echo set_radio('quali_verify['.$regId.'][]', 'Incorrect', (set_value('quali_verify['.$regId.'][]') == 'Incorrect')); ?> onclick="return confirmIncorrectQualificationPhoto();">Incorrect
                    </td>
                    <td> 
                        <textarea row="2" col="5" name="quali_remark[<?php echo $regId; ?>][]" id="quali_remark_<?php echo $regId; ?>"><?php echo set_value('quali_remark['.$regId.'][0]'); ?></textarea> 
                    </td>
                    </tr>
                  <?php } ?>  
                  </tbody>
                </table>


                <div style="display: flex; justify-content: center;">
                  <table class="table table-bordered" style="width: 50%;">
                    <thead>
                      <tr>
                        <th>Sr. No.</th>
                        <th>Date Time</th>
                        <th>Present Count</th>
                        <th>Absent Count</th>
                        <th>Total</th>
                      </tr>
                      <tr>
                        <tbody class="no-bd-y">
                        <?php if(isset($arr_inspe_batch) && count($arr_inspe_batch) > 0) { ?>
                          <?php foreach ($arr_inspe_batch as $key => $inspe_batch) { ?>
                            <tr>
                              <td><?php echo $key+1; ?></td>
                              <td><?php echo date_format(date_create($inspe_batch['date_time']),"d-M-Y H:i:s"); ?></td>
                              <td><?php echo $inspe_batch['present_cnt']; ?></td>
                              <td><?php echo $inspe_batch['absent_cnt']; ?></td>
                              <td><?php echo $inspe_batch['present_cnt']+$inspe_batch['absent_cnt']; ?></td>
                            </tr>
                          <?php } ?>  
                        <?php } ?>
                        </tbody>
                      </tr>  
                    </thead>
                  </table>
                </div>

              </div>
            </div>
            <!-- /.box-body --> 
          </div>
          <!-- /.box --> 
        </div>
        <!-- /.col collapsed-box-->
        
        <?php if (isset($batch_attendance) && count($batch_attendance) > 0) { ?>  
        <div class="col-xs-12">
          <div class="box-header">
            <h3 class="box-title">Batch Attendance</h3>
            <div class="box-tools pull-right">
              <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
            </div>
          </div>
          <div class="box">
            <div class="box-body">
              <div class="table-responsive">
                <table id="listitems_logs" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th style="text-align:center; border-color:#ccc">Sr. No</th>
                      <th style="text-align:center; border-color:#ccc">Date</th>
                      <th style="text-align:center; border-color:#ccc">Attendance Sheet</th>
                      <th style="text-align:center; border-color:#ccc">Screenshort</th>
                    </tr>
                  </thead>
                  <tbody class="no-bd-y">
                    <?php
                      $sr_no = 1;
                      foreach ($batch_attendance as $batch_attendance_value) 
                      {  
                        $attendanceSheetUrl = base_url('/uploads/attendance_doc/'.$batch_attendance_value['attendance_doc']);
                        $screenshotsUrl = base_url('/uploads/screenshots/'.$batch_attendance_value['screenshot']);
                      ?>
                        <tr>
                          <td style="text-align:center; border-color:#ccc"><?php echo $sr_no; ?></td>
                          <td style="border-color:#ccc"> <?php echo $batch_attendance_value['created_on']; ?></td>
                          <td style="border-color:#ccc"> <a href="<?php echo $attendanceSheetUrl; ?>" target="_blank"><?php echo "Attendance ".$sr_no; ?></a> </td>
                          <td style="border-color:#ccc"><a href="<?php echo $screenshotsUrl; ?>" target="_blank"><?php echo "Screen ".$sr_no; ?></a></td>
                        </tr>
                    <?php $sr_no++;
                      }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.box-body --> 
          </div>
          <!-- /.box --> 
        </div>
        <!-- /.col collapsed-box-->
      <?php } ?>


      <?php 
          $k = 1;
          $str = '';
          $reject_action_date = '';
          $is_center_update = 0;
          //$agency_batch_logs = count($agency_batch_logs);
          if(count($agency_batch_logs) > 0){
            
            foreach($agency_batch_logs as $res_log){
              $pre_text = ''; 
              $log_data = unserialize($res_log['description']);
              
              
              $log_data = unserialize($res_log['description']);
              $pre_text = '';
              
              if(isset($res_log['userid'])){  
                $admin_name = $res_log['name'];
              }else{
                $admin_name = '';
              }
              
              
              if(isset($log_data['rejection'])){  
                  //$pre_text = 'Rejected by';            
                  $rejection_reasion = '<span class="">'.$log_data['rejection'].'</span>';
                /*if(!$agency_center_logs_length ){
                  $reject_action_date = $res_log['date'];
                }*/
                if($k == 1){
                  $reject_action_date = $res_log['date'];
                }
                
                }else{
                  $rejection_reasion = '';  
                }
              
              if(isset($log_data['updated_by'])){             
              
              if($log_data['updated_by'] == 1  || $log_data['updated_by'] == 'A'){
                
                  $update_by = ' by '.$admin_name.' (A) ';
                }else{
                  $update_by = ' by '.$admin_name.'   (R) ';  
                }
              }else{
                $update_by = '';  
              }
              
              if(isset($log_data['center_validity_to'])){
                
                $pre_text = 'Updated Accreditation ';
                $Accridation_text = ' : '.date_format(date_create($log_data['center_validity_from']),"d-M-Y").' - '.date_format(date_create($log_data['center_validity_to']),"d-M-Y");
              }else{
                
                $Accridation_text = ''; 
              }
              
            $str .='<tr><td>'.$k.' </td>';
            //echo '<td>'.$res_log['title'].' </td>';
            $str .='<td>'.str_replace("DRA Admin","",$res_log['title']).' '.$Accridation_text.' -  '.$update_by.' </td>';
            $str .='<td>'.date_format(date_create($res_log['date']),"d-M-Y H:i:s").' </td>';
            $str .='<td> '.$rejection_reasion. '</td></tr>';
            $k++; 
          }
        }
        ?>

        <div class="col-xs-12">
          <div class="box-header">
            <h3 class="box-title">Training Batch Logs for (<?php echo $batch[0]['batch_code']; ?>)</h3>
            <div class="box-tools pull-right">
              <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
            </div>
          </div>
          <div class="box">
            <div class="box-body">
              <div class="table-responsive">
                <table id="listitems_logs" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Sr.No.</th>
                      <th>Action</th>
                      <th>Action Date/Time </th>
                      <th>Reason</th>
                    </tr>
                  </thead>
                  <tbody class="no-bd-y" id="list222">
                    <?php echo $str; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.box-body --> 
          </div>
          <!-- /.box --> 
        </div>
        <!-- /.col collapsed-box-->
        
        <input type="hidden" id="validateDoc_err" value="">
        <input type="hidden" id="isFileUpload" value="0">

        <div class="col-sm-4 col-xs-offset-3">
          <button type="button" class="btn btn-info btn_submit" id="btnSubmit" name="submit_inspection">Submit</button>
          <input type="reset" class="btn btn-danger" name="btnReset" id="btnReset" value="Reset">  
        </div>
        
      </div>
    </form>
  </section>
</div>

<div class="modal fade" id="SessionErrorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Session Error</h4>
      </div>
      <div class="modal-body">
        Your session has expired. Please <strong><a href="<?php echo site_url('iibfdra/Version_2/InspectorLogin'); ?>" onclick="close_modal()" target="_blank">Click Here</a></strong> to login again. After logged in, please revisit the same page and submit your report.
        <note>Note: If you are already logged in, please click the 'Close' button and proceed to submit your report.</note>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Data Tables -->
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<!-- Data Tables --> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script> 
<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">
<script src="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.css">

<script src="<?php echo base_url('assets/js/sweetalert2.all.min.js') ?>" type="text/javascript"></script> 
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>$(document).ready(function() { $('#batch_id').select2(); }); </script>

<script type="text/javascript">
  //$('#searchDate').parsley('validate');
</script>
<style>
  .report_tag{
  display:none;
  clear: both;
  padding: 17px 8px;
  border: 1px solid #ccc;
  margin: 5px;
  max-width: 408px;
  text-align: center;
  }
  .inspec{
  max-width:80%;  
  }
  .red{
  color:red;  
  }
  .err{
  border:1px solid #F00;  
  }
  .rejection{
  display:none; 
  }
  #center_validity{
  width:230px;  
  }
  #center_validity_to_date{
  width:230px;  
  }
  .box-header > .box-tools {
  top: 0px !important;
  }
  table.dataTable th{
  /*text-align:center;*/
  text-transform:capitalize;  
  }
  table.dataTable thead > tr > th{
  padding-right:4px !important;
  }
  .table-responsive{
  }
  
  td {
  word-wrap: anywhere;
  white-space: unset !important;
  }
  
  .DTTT_button_print{
  display:block;
  }
  #batch_active_period{
  width:220px;  
  float:left;
  }
  .batch_active_period_btn{
  float:right;  
  }
  .act_msg{
  font-size:12px;
  font-style:italic;
  color:#900;
  widows:100%;  
  }
  #inspector_id{
  width:210px;  
  }
  .rejection{
  width:85%;
  margin:4px;
  clear:both; 
  }
  
  #SessionErrorModal .modal-header {
  background: red;
  color: #fff;
  text-align: center;
  }
  
  #SessionErrorModal .modal-header button {
  color: #fff;
  opacity: 1;
  }
  
  #SessionErrorModal .modal-header h4 {
  font-size: 22px;
  font-weight: 600;
  text-transform: uppercase;
  }
  
  #SessionErrorModal .modal-body {
  font-size: 16px;
  line-height: 22px;
  padding: 30px 20px;
  text-align: center;
  font-weight: 600;
  }
  #SessionErrorModal .modal-footer {
  background: #eee;
  }
  
  #SessionErrorModal .modal-body note {
  display: block;
  font-size: 14px;
  margin: 15px 0 0 0;
  line-height: 16px;
  }
</style>
<script src="<?php echo base_url()?>js/js-paginate.js"></script> 
<script>
  $(document).ready(function(){
    $('#listitems').DataTable({
        paging: false,
        searching: true,
        ordering: true,
        info: true,
        lengthChange: false,
        columnDefs: [
          { width: '2%', targets: 0 },
          { width: '2%', targets: 1 },
          { width: '2%', targets: 2 },
          { width: '4%', targets: 3 },
          { width: '20%', targets: 4 },
          { width: '2%', targets: 5 },
          { width: '3%', targets: 6 },
          { width: '20%', targets: 7 },
          { width: '5%', targets: 8 },
          { width: '20%', targets: 9 },
          { width: '5%', targets: 10 },
          { width: '5%', targets: 11 },
          { width: '10%', targets: 12 },
          { width: '5%', targets: 13 }
        ]
    });  
});

  var success_massege = "<?php echo $successMassege; ?>";
  var file_massege    = "<?php echo $file_error_msg; ?>";
	
	function confirmIncorrectQualificationPhoto() 
  {
    return confirm("Proceed to Mark Incorrect? Once marked Incorrect, Candidate will be put on Hold and will be unable to apply for the examination.");
  }

  function confirmIncorrectidProof() 
  {
    return confirm("Proceed to Mark Incorrect? Once marked Incorrect, Candidate will be put on Hold and will be unable to apply for the examination.");
  }

  if ( success_massege != '' ) {

    swal({
      title: 'Inspection Done!',
      text: 'Inspection Report Saved Successfully...',
      icon: 'success',
      type: 'success',
      confirmButtonColor: '#3f51b5',
      confirmButtonText: 'OK ',
      buttons: {
        confirm: {
          text: "OK",
          value: true,
          visible: true,
          className: "btn btn-primary",
          closeModal: true
        }            
      }
    }).then(OK => { location.reload(); });
  
  } else if( file_massege != '' ) {

    swal({
      title: 'Error Occurred',
      text: file_massege,
      icon: 'error',
      type: 'error',
      confirmButtonColor: '#3f51b5',
      confirmButtonText: 'OK ',
      buttons: {
        confirm: {
          text: "OK",
          value: true,
          visible: true,
          className: "btn btn-primary",
          closeModal: true
        }            
      }
    });
  }

  function close_modal()
  {
    $("#SessionErrorModal").modal('hide');
  }
  
  $(function () 
  {  
    $('#btn-file-reset').on('click', function(e) {
      var $el = $('#attachment');
      $el.wrap('<form>').closest('form').get(0).reset();
      $el.unwrap();
      $('#isFileUpload').val('0');
    });
    
    $('#batch_id').on('change',function () {
      if ($(this).val() != '') {
        window.location.href = "https://iibf.esdsconnect.com/staging/iibfdra/Version_2/InspectorHome/show_batch_inspection_report/"+$(this).val();
      } else {
        window.location.href = "https://iibf.esdsconnect.com/staging/iibfdra/Version_2/InspectorHome/inspection_report";
      }
    });    
  });
  
  function Uncheck(className){
    $('.'+className).prop('checked',false);
  }
  
  function validateDoc(e, error_id){
    var srcid = e.srcElement.id;
    //if( document.getElementById(srcid).files.length != 0 ){
    var file = document.getElementById(srcid).files[0];
    var allowedFiles = [".txt", ".doc", ".docx", ".pdf", ".png", ".jpg", ".jpeg", ".PNG", ".JPG", ".JPEG"];
    var regex = new RegExp("([a-zA-Z0-9\s_\\((\d+)\)\.\-:])+(" + allowedFiles.join('|') + ")$");
    const fileSize = document.getElementById(srcid).files[0].size / 1024 / 1024; // in MiB
    var reader = new FileReader();
    
    if (reader.result == 'data:') {
      $('#'+error_id).text('This file is corrupted');
      ///$('.btn_submit').attr('disabled',true);
      $('#validateDoc_err').val('1');
      $('#isFileUpload').val('0');
      $('#attachment').focus();
    } 
    else{
      if (!regex.test(file.name)) {
        $('#'+error_id).text("Please upload " + allowedFiles.join(', ') + " only.");
        //$('.btn_submit').attr('disabled',true);
        $('#validateDoc_err').val('1');
        $('#isFileUpload').val('0');
        $('#attachment').focus();
      }
      else{
        if (fileSize > 10) {
          $('#'+error_id).text("Please upload file less than 10 Mb");
          //$('.btn_submit').attr('disabled',true);
          $('#validateDoc_err').val('1');
          $('#isFileUpload').val('0');
          $('#attachment').focus();
        }
        else {
          reader.onload = function (e) {
            srcContent=  e.target.result;
          }
          reader.readAsDataURL(file);
          $('#'+error_id).text('');
          //$('.btn_submit').removeAttr('disabled');
          $('#validateDoc_err').val('0');
          $('#isFileUpload').val('1');
        }
      }
    }
  }

  $('#candidates_loggedin').keyup(function()
  {
    var candidates_loggedin_val = $(this).val();
    if ($.trim(candidates_loggedin_val) != '' && candidates_loggedin_val != undefined) {
      $('#candidates_loggedin_error').text('');
      $('#candidates_loggedin').focusout();
    } else {
      $('#candidates_loggedin').focus();
      $('#candidates_loggedin_error').text('This field is required.'); 
    }
  })

  $('#btnSubmit').click(function (e) 
  {
    var candidates_loggedin = $('#candidates_loggedin').val();
    var selectedValue = $("input[name='overall_compliance']:checked").val();
    
    if( $.trim(candidates_loggedin) == '' || candidates_loggedin == undefined )
    {
      $('#candidates_loggedin').focus();
      $('#candidates_loggedin_error').text('This field is required.');
      return false;
    }
    else if(selectedValue == '' || selectedValue == undefined)  
    {
      $('.radio11').focus();
      $('#overall_compliance_error').text('This field is required.');
      return false;
    } 

    var validateDoc  = $('#validateDoc_err').val();
    var isFileUpload = $('#isFileUpload').val();
    
    let allValid = true;

    // Loop through all candidates and check if a radio button is selected
    $('.candidate-container').each(function () {
        var regId      = $(this).attr('data-regid');
        var trainingId = $(this).attr('data-training-id');
        var radioSelected = $(`input[name="attendance[${regId}][]"]:checked`).length > 0;

        if (!radioSelected) {
            allValid = false;
            alert(`Please select attendance for candidate with RegID: ${trainingId}`);
            return false;
        }
    });

    if (!allValid) return false; // Exit if any radio button validation fails
    
    if(validateDoc==0)
    {
      $('#loading').show();
      $.ajax(
      {
        url: "<?php echo site_url('iibfdra/Version_2/Check_inspector_session/index'); ?>",
        type: 'POST',
        contentType: false,
        cache: false,
        processData:false,
        async: true,      
        success:function(response)
        {
          var data = JSON.parse(response);
          if(data.flag == "success")
          { 
            $('#loading').hide();
            if ( isFileUpload == 0 ) 
            {
              swal({
                title: "You have missed to upload Attachment at Sl. No. 23.",
                text: "Are you sure you want to submit the inspection report without uploading an attachment?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true // Swap the positions of confirm and cancel buttons
              }).then((result) => {
                if (result.value) {
                  $('#inspector_from').on('submit',function(){
                    $('#loading').show();
                  });
                  $('#inspector_from').submit(); // Executed when user clicks "Yes"
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                  setTimeout(function() {
                    $('#attachment').focus(); // Focus on attachment field after a short delay
                  }, 1000); // Executed when user clicks "No" or outside the dialog
                }
              });
            }
            else
            {
              swal({
                title: "Are you sure?",
                text: "Are you sure you want to submit the inspection report?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true // Swap the positions of confirm and cancel buttons
              }).then((result) => {
                if (result.value) {
                  $('#inspector_from').on('submit',function(){
                    $('#loading').show();
                  });
                  $('#inspector_from').submit(); // Executed when user clicks "Yes"
                } 
              }); 
            }
          }
          else 
          { 
            $("#SessionErrorModal").modal("show");
            //console.log('Session Expired');
            $('#loading').hide();
            return false;
          }
        },
        error: function(xhr, status, error) 
        {
          // Handle AJAX error
          var errorMessage = "Error occurred : " + status + " - " + error;
          //console.log(errorMessage);
          
          // Display the error message to the user (you can customize this part)
          alert(errorMessage);
          $('#loading').hide();
        }
      });
    } else {
      $('#attachment').focus();  
    }
    
    return false;
  });

</script>
<script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
</script>