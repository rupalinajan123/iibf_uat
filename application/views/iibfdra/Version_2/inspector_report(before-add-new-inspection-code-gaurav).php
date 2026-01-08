<?php 
  $drainspdata = $this->session->userdata('dra_inspector'); 
  $inspector_name = $drainspdata['inspector_name'];
  $inspector_id = $drainspdata['id'];
?>
<style type="text/css">
  .note {
  color: blue;
  font-size: small;
  }
  
  .note-error {
  color: red;
  font-size: small;
  }
  
  #loading { display: none;	position: fixed; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; }
  #loading > p { margin: 0 auto; width: 100%; height: 100%; position: absolute; top: 20%; }
  #loading > p > img { max-height: 250px; margin:0 auto; display: block; }
  
  .select2-selection.select2-selection--single { border-radius: 0 !important; padding: 5px 0 2px 0px; height: auto !important; max-width: none; }
</style>

<div id="loading" class="divLoading" style="display: none;">
  <p><img src="<?php echo base_url(); ?>assets/images/loading-4.gif"/></p>
</div>

<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header" style="text-align: center;">
    <h1> DRA Inspection - Online Training Form </h1>
    <h3 style="color: red"> (This form will be filled in by the inspector while inspecting the batch)</h3>
    <?php $drauserdata = $this->session->userdata('dra_admin');?> 
  </section>
  <section class="content-header">
    <h4> The DRA Training Programs are to be conducted as per the latest terms and conditions as laid down by IIBF and abided by all the DRA accredited Institutions / Agencies.  </h4>
    <h4> Below mentioned format is to be filled with the fact of the training activities as delivered by the agencies and experienced by the assigned Inspector.  </h4>
  </section>
  <div class="col-md-12"> <br />
  </div>
  <!-- Main content -->
  <section class="content">
    <?php if($this->session->flashdata('error')!=''){ ?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $this->session->flashdata('error'); ?>
      </div>
      <?php } if($this->session->flashdata('success')!=''){ ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $this->session->flashdata('success'); ?>
      </div>
      <?php }
      
      if($error_msg!=''){?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $error_msg; ?>
      </div>
    <?php }?> 
    
    <form method="post" name="appfrom" id="inspector_from"  enctype="multipart/form-data" >
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info box-solid disabled">
            <div class="box-header with-border">
              <h3 class="box-title">Select Batch for Inspection</h3>
              <div class="box-body">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="batch_id" id="batch_id">
                      <option value="">Select Batch</option>
                      <?php 
                        foreach ($batch as $key => $value) {?>
                        <option value="<?php echo $value['id']; ?>" agency-id-attr="<?php echo $value['agency_id']; ?>" agency-name-attr="<?php echo $value['institute_name']; ?>" batch-code-attr="<?php echo $value['batch_code']; ?>"  batch-duration-attr="<?php echo $value['batch_from_date'].' To '.$value['batch_to_date']; ?>" batch_online_offline_flag-attr="<?php echo $value['batch_online_offline_flag']; ?>" batch-platform-attr="<?php echo $value['online_training_platform']; ?>" batch-time-attr="<?php echo $value['hours'].' Hours'; ?>" daily-batch-timing="<?php echo $value['timing_from'].' To '.$value['timing_to']; ?>" candidate-count-attr="<?php echo $value['total_candidates']; ?>" batch-medium-attr="<?php echo $value['training_medium']; ?>" assigned-facultyid-attr1="<?php echo $value['first_faculty_id']; ?>" assigned-faculty-attr1="<?php echo $value['first_faculty_name']; ?>" assigned-facultyid-attr2="<?php echo $value['sec_faculty_id']; ?>" assigned-faculty-attr2="<?php echo $value['sec_faculty_name']; ?>" additional-facultyid-attr1="<?php echo $value['add_first_faculty_id']; ?>" additional-faculty-attr1="<?php echo $value['add_first_faculty_name']; ?>" additional-facultyid-attr2="<?php echo $value['add_sec_faculty_id']; ?>" additional-faculty-attr2="<?php echo $value['add_sec_faculty_name']; ?>" contact-person-attr="<?php echo $value['contact_person_name'].' ('. $value['contact_person_phone'].')';?>" alt-contact-person-attr="<?php echo $value['alt_contact_person_name'].' ('. $value['alt_contact_person_phone'].')';?>" platform-attr="<?php echo $value['platform_link'] ?>" document-attr="<?php echo $value['training_schedule'] ?>"><?php echo $value['batch_code']." (".$value['hours']." Hours - ".date("d M Y, h:i A", strtotime($value['created_on'])).")"; ?></option>
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
      
      <input type="hidden" name="insp_no" id="insp_no" value="">
      
      <h4 id="inspection_no"></h4>
      
      <div class="table-responsive box box-info box-solid disabled" width="100%" id="batch_autofilled_div" style="display: none">
        <table class="table table-bordered table-striped" style="word-wrap:anywhere; background:<?php echo $div_class; ?>;">
          <tbody>
            <tr>
              <td width="29%"><strong>Name of the DRA Accredited Institution/Bank/FI:</strong></td>
              <td width="20%" id="agency_name"></td>
              <td width="2%"></td>
              <td width="29%"><strong>Batch Type:</strong></td>
              <td width="20%" id="training_time_duration"></td>
            </tr>
            <tr>
              <td width="29%"><strong> Batch Code :</strong></td>
              <td width="20%" id="batch_code"></td>
              <td width="2%"></td>
              <td width="29%"><strong>Batch Duration:</strong></td>
              <td width="20%" id="duration"></td>
            </tr>
            <tr>
              <td width="29%"><strong>Daily Training Timing:</strong></td>
              <td width="20%" id="daily_training_timing"></td>
              <td width="2%"></td>
              <td width="29%"><strong>No. of candidates enrolled in the Batch:</strong></td>
              <td width="20%" id="candidate_count"></td>
            </tr>
            <tr>
              <td width="29%"><strong>Assigned Faculty (main 1):</strong></td>
              <td width="20%"><a href="" id="assigned_faculty1" target="_blank"></a></td>
              <td width="2%"></td>
              <td width="29%"><strong>Assigned Faculty(main 2):</strong></td>
              <td width="20%"><a href="" id="assigned_faculty2" target="_blank"></a></td>
            </tr>
            <tr>
              <td width="29%"><strong>Assigned Faculty (additional 1):</strong></td>
              <td width="20%"><a href="" id="additional_faculty1" target="_blank"></a></td>
              <td width="2%"></td>
              <td width="29%"><strong>Assigned Faculty(additional 2):</strong></td>
              <td width="20%"><a href="" id="additional_faculty2" target="_blank"></a></td>
            </tr>
            <tr>
              <td width="29%"><strong>Co-ordinator name and Mobile no. :</strong></td>
              <td width="20%" id="coordinator_name"></td>
              <td width="2%"></td>
              <td width="20%"><strong>Co-ordinator name and Mobile no. (additional):</strong></td>
              <td width="29%" id="additional_coordinator_name"></td>
            </tr>
            
            <tr>
              <td width="20%"><strong>Training Language :</strong></td>
              <td width="29%" id="training_language"></td>
              <td width="2%"></td>
              <td width="29%"><strong>Document:</strong></td>
              <td width="20%" id="document"><a href="" id="document_href" target="_blank"></a></td>
            </tr>
            
            
            <tr class="online_offline_flag">
              <td width="29%"><strong>Name of the on-line platform:</strong></td>
              <td width="20%" id="batch_training_platform"></td>
              <td width="2%"></td>
              <td width="29%"><strong>Platform Link:</strong></td>
              <td width="20%"><a href="" id="platform_link_href" target="_blank"></a></td>
            </tr>
            
            <tr class="online_offline_flag">
              <td width="29%"><strong>Login ID/Password:</strong></td>
              <td id="login_pwd_tbl">
                
              </td>
            </tr>
            
            <tr>
              <td width="29%"><strong>Date/Start Time of Inspection:</strong></td>
              <td width="20%"><?php echo date('Y-m-d H:i:s'); ?></td>
              <td width="2%"></td>
              <td width="29%"><strong>Inspector Name/ID:</strong></td>
              <td width="20%" ><?php echo $inspector_name.'/'.$inspector_id; ?></td>
            </tr>
            
          </tbody>
        </table>
      </div>
      
      <input type="hidden" name="inspection_start_time" value="<?php echo date('Y-m-d H:i:s'); ?>">
      
      <div class="row" id="batch_inspecton_div" style="display: none">
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
              
              
              <input type="hidden" name="agency_id" id="agency_id" value="">
              
              <div class="table-responsive ">
                <table class="table table-bordered table-striped" style="word-wrap:anywhere; background:<?php echo $div_class; ?>;">
                  <tbody>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>1</strong></td>
                      <td width="48%"><strong>Number of candidates logged-in at start of visit to the platform (excluding self / faculty/ coordinator or any other administrator)</strong><span style="color:#F00">*</span></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="candidates_loggedin" maxlength="100" placeholder="" required="required"></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>2</strong></td>
                      <td width="48%"><strong>Whether the declared Link / Platform for the training got changed (Yes / No). If Yes, mention the Link / Name of the Platform for the training purpose.</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="platform_name" maxlength="100" placeholder=""></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>3</strong></td>
                      <td width="48%"><strong>Whether there are multiple logins with same name (Yes / No)? If Yes, how many such multiple logins are there?</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="multiple_login_same_name" maxlength="100" placeholder="" ></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>4</strong></td>
                      <td width="48%"><strong>Whether log-ins with instrument name (Samsung/oppo etc) is there (Yes / No). If Yes, how many such log-ins?</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="instrument_name" maxlength="100" placeholder="" ></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>5</strong></td>
                    	<td width="48%"><strong>Whether any issues were faced while logging-in onto the Online Platform (e.g. wrong log-in credentials / waited for more than 2 minutes in waiting room / taking you into a platform of a different link / only buffering for minutes etc.)</strong></td>
                    	<td width="48%"><textarea style="width:100%; text-align:left;" name="issues" maxlength="1000" placeholder=""></textarea>
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>6</strong></td>
                    	<td width="48%"><strong>Whether virtual recording is ‘On’ or “not On” or started after your joining / insisting for the same. In case the session recording is not on, mention the reason of such situation.</strong></td>
                    	<td width="48%"><textarea style="width:100%; text-align:left;" name="training_session" maxlength="100" placeholder=""></textarea>
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
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="session_candidates" maxlength="100" placeholder=""></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>(ii) Is the training going on as per session plan shared by the Agency (can be confirmed from the Faculty) </strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="training_session_plan" maxlength="100" placeholder=""></textarea></td>
                    </tr>
                    
                    <!-- <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>8</strong></td>
                      <td width="48%"><strong>Whether Name of Batch Coordinator is displayed on the platform (Yes - enter the relevant information / No)</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="actual_batch_coordinator" maxlength="1000" placeholder=""></textarea></td>
                      </tr>
                      
                      <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>9</strong></td>
                      <td width="48%"><strong>Coordinator is same as allotted or not (Yes/ No) if not mention the name of the co-ordinator:</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="diff_batch_coordinator" maxlength="1000" placeholder=""></textarea></td>
                    </tr> -->
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>8</strong></td>
                      <td width="48%"><strong>Attendance:</strong></td>
                      <td width="48%"></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>i. Whether Attendance Sheet is updated by the Agency till the time of inspection (Yes / No).</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="attendance_sheet_updated" maxlength="100" placeholder=""></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>ii. Mode of taking attendance (Online / Screen Shot / Manual calling etc.)</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="attendance_mode" maxlength="100" placeholder=""></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>iii. Whether the Attendance Sheet is shown promptly to the Inspector on demand (Yes / No).</strong></td>
                      <td width="48%" > &nbsp; <textarea style="width:100%; text-align:left;" name="attendance_shown" maxlength="100" placeholder=""></textarea>
                      </td>
                    </tr>  
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>9</strong></td>
                      <td width="48%"><strong>Is there any group of candidates attending the sessions from one place through a single device (Yes / No). If Yes, mention the candidates’ count and reason / situation in brief.
                      </strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="candidate_count_device" maxlength="1000" placeholder=""></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>10</strong></td>
                      <td width="48%"><strong>Faculty Details:</strong></td>
                      <td width="48%"></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>a) Whether Name / Code of Faculty is displayed on the platform (Yes / No).</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="actual_faculty" maxlength="100" placeholder=""></textarea></strong>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>b) Name / Code of Faculty taking session</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="faculty_taking_session" maxlength="100" placeholder=""></textarea></strong>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>c) If the Faculty who is taking session is different from the declared one, please mention:
                        <br>i. Name and Qualification (highest) of the Faculty
                      </strong>
                      </td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="name_qualification" maxlength="1000" placeholder=""></textarea>
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>
                        ii. No. of days / sessions she/he has taken / will take
                      </strong>
                      </td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="no_of_days" maxlength="1000" placeholder=""></textarea>
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>
                        iii. Reason of such change in faculty
                      </strong>
                      </td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="reason_of_change_in_faculty" maxlength="1000" placeholder=""></textarea>
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>
                        iv. Whether the Faculty is having earlier experience in teaching / training in BFSI sector (mention in brief).
                      </strong>
                      </td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="experience_teaching_training_BFSI_sector" maxlength="1000" placeholder=""></textarea>
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>d) Language in which the Faculty is taking the session</strong></td>
                      <td width="48%">
                        <textarea style="width:100%; text-align:left;" name="faculty_language" maxlength="100" placeholder=""></textarea>
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>e) The Faculty is taking sessions for how many hrs/min per day</strong></td>
                      <td width="48%">
                        <textarea style="width:100%; text-align:left;" name="faculty_session_time" maxlength="100" placeholder=""></textarea>
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>f) Whether minimum 2 faculties are taking sessions to complete the 50 / 100 hours training in the Batch.</strong></td>
                      <td width="48%">
                        <textarea style="width:100%; text-align:left;" name="two_faculty_taking_session" maxlength="100" placeholder=""></textarea>
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>g) Whether the language(s) used by the Faculty is understandable by the candidates (can be confirmed from the participants).</strong></td>
                      <td width="48%">
                        <textarea style="width:100%; text-align:left;" name="faculty_language_understandable" maxlength="100" placeholder=""></textarea>
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>h) Whether the online training tools like whiteboard / PPT / PDF / Documents are used while delivering lectures.</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="whiteboard_ppt_pdf_used" maxlength="100" placeholder=""></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>11</strong></td>
                      <td width="48%"><strong>Whether the faculty (in case of new faculty only) and all the candidates have attended preparatory / briefing session on the etiquettes of the upcoming DRA training (Yes / No).</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="session_on_etiquettes" maxlength="100" placeholder=""></textarea> </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>12</strong></td>
                      <td width="48%"><strong>Whether the faculty and trainees were conversant with the process of on-line training.</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="faculty_trainees_conversant" maxlength="100" placeholder=""></textarea> </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>13</strong></td>
                      <td width="48%"><strong>Whether the candidates could recognise the name of the training providing agency / institution (Yes / No).</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="candidates_recognise" maxlength="100" placeholder=""></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>14</strong></td>
                      <td width="48%"><strong>Whether candidates were given "Handbook on debt recovery" by the concerned agency.</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="handbook_on_debt_recovery" maxlength="100" placeholder=""></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>15</strong></td>
                      <td width="48%"><strong>Whether candidates are provided with other study materials in word/pdf format by the agency).</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="other_study_materials" maxlength="100" placeholder=""></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>16</strong></td>
                      <td width="48%"><strong>Whether the training was conducted without any interruption/ disturbances/ noises?</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="training_conduction" maxlength="100" placeholder=""></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>17</strong></td>
                      <td width="48%"><strong>Batch Coordinator:</strong></td>
                      <td width="48%"></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>a) Whether Name of Batch Coordinator is displayed on the virtual platform with Batch Code (Yes / No).</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="batch_coordinator_available" maxlength="100" placeholder=""></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>b) Name / Code of the Coordinator who is available in the Session</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="coordinator_available_name" maxlength="100" placeholder=""></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>c) The Coordinator is whether originally allotted or not (Yes/ No). In case No, mention the name and contact no. of the available Coordinator.</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="current_coordinator_available_name" maxlength="100" placeholder=""></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>18</strong></td>
                      <td width="48%"><strong>Any irregularity(ies) consistently / frequently persist despite repetitive reminders for rectification.</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="any_irregularity" maxlength="1000" placeholder=""></textarea></td>
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
                          <br><input  type="radio" class="minimal radio1" name="teaching_quality_interaction_with_candidates" value="Poor">Poor
                          <input  type="radio" class="minimal radio1" name="teaching_quality_interaction_with_candidates" value="Average">Average
                          <input  type="radio" class="minimal radio1" name="teaching_quality_interaction_with_candidates" value="Good">Good
                          <input  type="radio" class="minimal radio1" name="teaching_quality_interaction_with_candidates" value="Excellent">Excellent
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
                        <input  type="radio" class="minimal radio2" name="teaching_quality_softskill_session" value="Poor">Poor
                        <input  type="radio" class="minimal radio2" name="teaching_quality_softskill_session" value="Average">Average
                        <input  type="radio" class="minimal radio2" name="teaching_quality_softskill_session" value="Good">Good
                        <input  type="radio" class="minimal radio2" name="teaching_quality_softskill_session" value="Excellent">Excellent
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
                        <input  type="radio" class="minimal radio3" name="candidates_attentiveness" value="Poor">Poor
                        <input  type="radio" class="minimal radio3" name="candidates_attentiveness" value="Average">Average
                        <input  type="radio" class="minimal radio3" name="candidates_attentiveness" value="Good">Good
                        <input  type="radio" class="minimal radio3" name="candidates_attentiveness" value="Excellent">Excellent
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
                        <input  type="radio" class="minimal radio4" name="DRA_attitude_behaviour" value="Poor">Poor
                        <input  type="radio" class="minimal radio4" name="DRA_attitude_behaviour" value="Average">Average
                        <input  type="radio" class="minimal radio4" name="DRA_attitude_behaviour" value="Good">Good
                        <input  type="radio" class="minimal radio4" name="DRA_attitude_behaviour" value="Excellent">Excellent
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
                        <br><input  type="radio" class="minimal radio5" name="learning_quality_interaction_with_faculty" value="Poor">Poor
                        <input  type="radio" class="minimal radio5" name="learning_quality_interaction_with_faculty" value="Average">Average
                        <input  type="radio" class="minimal radio5" name="learning_quality_interaction_with_faculty" value="Good">Good
                        <input  type="radio" class="minimal radio5" name="learning_quality_interaction_with_faculty" value="Excellent">Excellent
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
                        <input  type="radio" class="minimal radio6" name="learning_quality_response_to_queries" value="Poor">Poor
                        <input  type="radio" class="minimal radio6" name="learning_quality_response_to_queries" value="Average">Average
                        <input  type="radio" class="minimal radio6" name="learning_quality_response_to_queries" value="Good">Good
                        <input  type="radio" class="minimal radio6" name="learning_quality_response_to_queries" value="Excellent">Excellent
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
                        <input  type="radio" class="minimal radio7" name="teaching_effectiveness" value="Poor">Poor
                        <input  type="radio" class="minimal radio7" name="teaching_effectiveness" value="Average">Average
                        <input  type="radio" class="minimal radio7" name="teaching_effectiveness" value="Good">Good
                        <input  type="radio" class="minimal radio7" name="teaching_effectiveness" value="Excellent">Excellent
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
                        <input  type="radio" class="minimal radio8" name="curriculum_covered" value="Poor">Poor
                        <input  type="radio" class="minimal radio8" name="curriculum_covered" value="Average">Average
                        <input  type="radio" class="minimal radio8" name="curriculum_covered" value="Good">Good
                        <input  type="radio" class="minimal radio8" name="curriculum_covered" value="Excellent">Excellent
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
                        <br><input  type="radio" class="minimal radio9" name="overall_compliance_training_delivery" value="Poor">Poor
                        <input  type="radio" class="minimal radio9" name="overall_compliance_training_delivery" value="Average">Average
                        <input  type="radio" class="minimal radio9" name="overall_compliance_training_deliveryn" value="Good">Good
                        <input  type="radio" class="minimal radio9" name="overall_compliance_training_delivery" value="Excellent">Excellent
                        &nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0);" onclick="Uncheck('radio9')">Uncheck</a>
                      </strong>
                      <!-- <textarea style="width:100%; text-align:left;" name="overall_compliance_training_delivery_coordination" maxlength="1000" placeholder=""></textarea> -->
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong></strong></td>
                      <td width="48%"><strong>ii. Training coordination</strong></td>
                      <td width="48%"><strong>
                        <input  type="radio" class="minimal radio10" name="overall_compliance_training_coordination" value="Poor">Poor
                        <input  type="radio" class="minimal radio10" name="overall_compliance_training_coordination" value="Average">Average
                        <input  type="radio" class="minimal radio10" name="overall_compliance_training_coordination" value="Good">Good
                        <input  type="radio" class="minimal radio10" name="overall_compliance_training_coordination" value="Excellent">Excellent
                        &nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0);" onclick="Uncheck('radio10')">Uncheck</a>
                      </strong>
                      <!-- <textarea style="width:100%; text-align:left;" name="overall_compliance_training_delivery_coordination" maxlength="1000" placeholder=""></textarea> -->
                      </td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>20</strong></td>
                      <td width="48%"><strong>Any other observations with respect to non-adherence to the conditions stipulated by IIBF for conducting on-line DRA Training.</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="other_observations" maxlength="1000" placeholder=""></textarea></td>
                    </tr>
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>21</strong></td>
                      <td width="48%"><strong>Overall Observation of the Inspector on the training of the DRA Batch.</strong></td>
                      <td width="48%"><textarea style="width:100%; text-align:left;" name="overall_observation" maxlength="1000" placeholder=""></textarea></td>
                    </tr>         
                    
                    <tr>
                      <td width="4%" style="white-space: nowrap !important;"><strong>22</strong></td>
                      <td width="48%"><strong>Over all compliance on imparting of DRA Training</strong></td>
                      <td width="48%"><strong>
                        <input  type="radio" class="minimal radio11" name="overall_compliance" value="Poor">Poor
                        <input  type="radio" class="minimal radio11" name="overall_compliance" value="Average">Average
                        <input  type="radio" class="minimal radio11" name="overall_compliance" value="Good">Good
                        <input  type="radio" class="minimal radio11" name="overall_compliance" value="Excellent">Excellent
                        &nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0);" onclick="Uncheck('radio11')">Uncheck</a>
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
                      <td width="48%"><span class="note" id="attachment_note">Note: Please Upload only .txt, .doc, .docx, .pdf, .jpg, .png, .jpeg Files with size upto 5 MB</span></td>
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
                      <th width="3">S.No.</th> 
                      <th width="7">Training Id</th>
                      <th width="20">Candidate Name</th>
                      <th width="5">DOB</th>
                      <th width="10">Mobile</th>
                      <th width="10">Photo</th>
                      <th width="20">Attendance</th>
                      <th width="20">Remark</th>
                    </tr>
                  </thead>
                  <tbody class="no-bd-y" id="list">
                    
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
        
        <div class="col-sm-4 col-xs-offset-3">
          <button type="submit" class="btn btn-info btn_submit" id="btnSubmit" name="submit">Submit</button>
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
  /* overflow-x:hidden !important; */
  }
  
  /* .table-responsive > .dataTables_wrapper, .table-responsive > .table
  {
  max-width: 96%;
  margin: 0 auto;
  } */
  
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
  function close_modal()
  {
    $("#SessionErrorModal").modal('hide');
  }
  
  
  var site_path = '<?php echo base_url(); ?>';
  $(function () {
    
    var selectedData = [];
    
    var dateToday = new Date();	
    //var validity_to_ck =  $('#batch_to_date_val').val(); //2019-02-26
    var validity_to_ck = $('#batch_to_date_val').val();
    
    var table  = $('#listitems').DataTable({
      
      "dom": 'Blftirp',
      "stateSave": true,
      
      "paging":   false,
      
      "select": {
        "style":    'multi',
        "selector": 'td:nth-child(2)'
        
      },
      
      "responsive":{
        "details" : {
          "type" : 'column'
        }
      },
    });
    
    $('#btn-file-reset').on('click', function(e) {
      var $el = $('#attachment');
      $el.wrap('<form>').closest('form').get(0).reset();
      $el.unwrap();
    });
    
    $('#batch_id').on('change',function () {
      $('#loading').show();
      var batch_id = $(this).val();
      var agency_id = $(this).children(":selected").attr('agency-id-attr');
      var agency_name = $(this).children(":selected").attr('agency-name-attr');
      var batch_code = $(this).children(":selected").attr('batch-code-attr');
      var batch_duration = $(this).children(":selected").attr('batch-duration-attr');
      var batch_platform = $(this).children(":selected").attr('batch-platform-attr');
      var batch_time_duration = $(this).children(":selected").attr('batch-time-attr');
      var daily_training_timing = $(this).children(":selected").attr('daily-batch-timing');
      var candidate_count = $(this).children(":selected").attr('candidate-count-attr');
      var batch_medium = $(this).children(":selected").attr('batch-medium-attr');
      var assigned_facultyid1 = $(this).children(":selected").attr('assigned-facultyid-attr1');
      var additional_facultyid1 = $(this).children(":selected").attr('additional-facultyid-attr1');
      var assigned_facultyid2 = $(this).children(":selected").attr('assigned-facultyid-attr2');
      var additional_facultyid2 = $(this).children(":selected").attr('additional-facultyid-attr2');
      
      var assigned_faculty1 = $(this).children(":selected").attr('assigned-faculty-attr1');
      var additional_faculty1 = $(this).children(":selected").attr('additional-faculty-attr1');
      var assigned_faculty2 = $(this).children(":selected").attr('assigned-faculty-attr2');
      var additional_faculty2 = $(this).children(":selected").attr('additional-faculty-attr2');
      var coordinator_name = $(this).children(":selected").attr('contact-person-attr');
      var additional_coordinator_name = $(this).children(":selected").attr('alt-contact-person-attr');
      var platform_link = $(this).children(":selected").attr('platform-attr');
      var document_link = $(this).children(":selected").attr('document-attr');
      
      var batch_online_offline_flag = $(this).children(":selected").attr('batch_online_offline_flag-attr');
      
      
      //console.log('batch_online_offline_flag-'+batch_online_offline_flag);
      
      if(batch_id != ''){
        $('#batch_autofilled_div').css('display', 'block');
        $('#batch_inspecton_div').css('display', 'block');
        $('#agency_name').text(agency_name);
        $('#batch_code').text(batch_code);
        $('#duration').text(batch_duration);
        $('#daily_training_timing').text(daily_training_timing);
        $('#batch_training_platform').text(batch_platform);
        $('#training_time_duration').text(batch_time_duration);
        $('#training_language').text(batch_medium);
        $('#assigned_faculty1').text(assigned_faculty1);
        $('#assigned_faculty1').attr('href',site_url+'iibfdra/Version_2/InspectorHome/faculty_view/'+btoa(assigned_facultyid1));
        $('#additional_faculty1').text(additional_faculty1);
        $('#additional_faculty1').attr('href',site_url+'iibfdra/Version_2/InspectorHome/faculty_view/'+btoa(additional_facultyid1));
        $('#assigned_faculty2').text(assigned_faculty2);
        $('#assigned_faculty2').attr('href',site_url+'iibfdra/Version_2/InspectorHome/faculty_view/'+btoa(assigned_facultyid2));
        $('#additional_faculty2').text(additional_faculty2);
        $('#additional_faculty2').attr('href',site_url+'iibfdra/Version_2/InspectorHome/faculty_view/'+btoa(additional_facultyid2));
        $('#candidate_count').text(candidate_count);
        $('#coordinator_name').text(coordinator_name);
        $('#additional_coordinator_name').text(additional_coordinator_name);
        $('#platform_link').text(platform_link);
        $('#platform_link_href').text(platform_link);
        $('#platform_link_href').attr('href', platform_link);
        $('#document_href').text('View Doument');
        $('#document_href').attr('href', site_url+'uploads/training_schedule/'+document_link);
        $('#agency_id').val(agency_id);
        
        if(batch_online_offline_flag == 1){
          $('.online_offline_flag').show();
        }
        else{
          $('.online_offline_flag').hide();
        }
        
        $.ajax({
          url:site_url+'iibfdra/Version_2/InspectorHome/get_candidate_data',
          data: {batch_id: batch_id,type:'report'},
          type:'POST',
          //async: false,
          success: function(res) 
          {
            var res1 = res.split(':::::');
            var data = res1[1];
            
            try 
            {
              jsonResult = JSON.parse(data);
            }
            catch (e) 
            {
              location.reload(); 
            };
            
            $('#inspection_no').text('Inspection No.:'+res1[0]);
            $('#insp_no').val(res1[0]);
            
            if(data != '')
            {
              table.clear().draw();
              
              $.each(JSON.parse(data), function(idx, obj) {
                var j = parseInt(idx)+1;
                
                var ph = site_url+"uploads/iibfdra/"+obj.scannedphoto;
                
                var http = new XMLHttpRequest();
                http.open('HEAD', ph, false);
                http.send();
                //console.log(http.status);
                var photo = '';
                if(http.status == 404){
                  //var no_img = 'no_image1.png';
                  //var photo = '<img height="90" width="120" id="photo_'+obj.regid+'" src="'+site_url+"assets/images/"+no_img+'" alt="">';
                  photo = '';
                  } else {
                  photo = '<img height="90" width="70" id="photo_'+obj.regid+'" src="'+site_url+"uploads/iibfdra/"+obj.scannedphoto+'" alt="">';
                }
                
                var attendance = '<input type="radio" class="btn_check" id="id_present_'+obj.regid+'" name="attendance['+obj.regid+'][]" value="Present">Present<br><input type="radio" class="minimal" id="id_absent_'+obj.regid+'" name="attendance['+obj.regid+'][]" value="Absent">Absent';
                
                //var attendance = '<input type="checkbox" style="text-align:right" class="btn_check" name="attendance['+obj.regid+'][]" id="id_'+obj.regid+'" value="P" >';
                
                var remark = '<textarea row="2" col="5" name="remark['+obj.regid+'][]" id="remark_'+obj.regid+'"></textarea>';
                
                values = [[j, obj.training_id, obj.name, obj.dateofbirth, obj.mobile_no, photo, attendance, remark]];
                
                table.rows.add(values).draw();
                
              });
              //tableData = JSON.parse(JSON.stringify(data)); 
            }
            
            $('#loading').hide();
            $('#login_pwd_tbl').append(res1[2]);
          }
          
        });
        
      }
      else{
        $('#batch_autofilled_div').css('display', 'none');
        $('#batch_inspecton_div').css('display', 'none');
        $('#agency_name').text('');
        $('#batch_code_duration').text('');
        $('#daily_training_timing').text('');
        $('#batch_training_platform').text('');
        $('#training_time_duration').text();
        $('#training_language').text('');
        $('#assigned_faculty1').text('');
        $('#additional_faculty1').text('');
        $('#assigned_faculty2').text('');
        $('#additional_faculty2').text('');
        $('#candidate_count').text('');
        $('#coordinator_name').text('');
        $('#additional_coordinator_name').text('');
        $('#platform_link').text('');
        $('#agency_id').val('');
        $('#inspection_no').text('');
        $('#loading').hide();
      }
    });
    
    $('#inspector_from').on('submit', function (e) 
    {
      let formData = new FormData(this)
      var validateDoc = $('#validateDoc_err').val();
      //alert(validateDoc);
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
          dataType: 'JSON',
          async: true,      
          success:function(data)
          {
            if(data.flag == "success")
            {
            	$.ajax(
            {
                url: site_path+"iibfdra/Version_2/InspectorHome/save_inspection_report",
                type: 'POST',
                data: formData,
                contentType: false,
                cache: false,
                processData:false,
                dataType: 'JSON',
                success: function(response)
                {
                  if(response.flag == "success")
                  {                  
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
                  }
                  else
                  {
                    if(response.message != "")
                    {
                      swal({
                        title: 'Error Occurred',
                        text: response.message,
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
                    else
                    {
                      location.reload();
                    }
                  }
                },
                error: function(xhr, status, error) 
                {
                  // Handle AJAX error
                  var errorMessage = "Error occurred : " + status + " - " + error;
                  //console.log(errorMessage);
                  
                  // Display the error message to the user (you can customize this part)
                  alert(errorMessage);
                }
              })
              $('#loading').hide();
              return false;
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
      }
      return false;
    });
    
    
    $('.submit_report').click(function(e){
      $('#action_status').val('REPORT');
      //$('.rejection').show();		
      var inspector_report = $('.inspector_report').val();
      //alert('inspector_report'+inspector_report)
      if(inspector_report == ''){
        $('.inspector_report').addClass('err');
        return false;	
      }
      
      var ext = $('.inspector_report').val().split('.').pop().toLowerCase();
      if($.inArray(ext,['pdf','doc','docx','jpg','png','jpeg']) == -1) {
        //pdf|PDF|doc|DOC|docx|DOCX|txt|TXT|jpg|png|jpeg|JPG|PNG|JPEG
        alert('invalid extension!');
        return false;	
      }
      
  		if (confirm('Are you sure you want to submit Inspection report?')) {
        e.preventDefault();
        $('#approve_from').submit();	
        
        } else {
        return false;
      }		
    });
    
    $('.add_report').click(function(){
      $('.report_tag').slideDown('slow');
    });
    
    //$("#listitems").DataTable();
    //$("#listitems_logs").DataTable();
		
    //$("#listitems_logs_filter").show();		
    //$("#listitems_filter").show();
    
  });
  
  function Uncheck(className){
    $('.'+className).prop('checked',false);
  }
  
  function validateDoc(e, error_id){
    var srcid = e.srcElement.id;
    //if( document.getElementById(srcid).files.length != 0 ){
    var file = document.getElementById(srcid).files[0];
    var allowedFiles = [".txt", ".doc", ".docx", ".pdf", ".png", ".jpg", ".jpeg"];
    var regex = new RegExp("([a-zA-Z0-9\s_\\((\d+)\)\.\-:])+(" + allowedFiles.join('|') + ")$");
    const fileSize = document.getElementById(srcid).files[0].size / 1024 / 1024; // in MiB
    var reader = new FileReader();
    
    if (reader.result == 'data:') {
      $('#'+error_id).text('This file is corrupted');
      ///$('.btn_submit').attr('disabled',true);
      $('#validateDoc_err').val('1');
    } 
    else{
      if (!regex.test(file.name)) {
        $('#'+error_id).text("Please upload " + allowedFiles.join(', ') + " only.");
        //$('.btn_submit').attr('disabled',true);
        $('#validateDoc_err').val('1');
      }
      else{
        if (fileSize > 5) {
          $('#'+error_id).text("Please upload file less than 5 Mb");
          //$('.btn_submit').attr('disabled',true);
          $('#validateDoc_err').val('1');
        }
        else{
          //('---');
          reader.onload = function (e) {
            srcContent=  e.target.result;
          }
          reader.readAsDataURL(file);
          $('#'+error_id).text('');
          //$('.btn_submit').removeAttr('disabled');
          $('#validateDoc_err').val('0');
        }
      }
    }
    //}
    /* else{
      $('#'+error_id).text('Please select file');
      // $('.btn_submit').attr('disabled',true);
      $('#validateDoc_err').val('1');
    }*/
    
  }
</script>
<script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
</script>