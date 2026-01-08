<div class="ibox mb-2">
  <div class="ibox-title bg_light_blue">
    <h5>Batch Details</h5>
    <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
  </div>

  <div class="ibox-content">
    <div class="table-responsive">
      <table class="table table-bordered custom_inner_tbl" style="width:100%">
        <tbody>
          <?php $this->load->view('iibfbcbf/common/inc_training_batch_details_common'); ?> 

          <tr>
            <td <?php if($batch_data[0]['inspection_report_by_admin'] == "") { echo 'colspan="2"'; } ?>><b style="vertical-align:top">Assigned Inspector : </b><?php echo $batch_data[0]['inspector_name']; ?></td>
            <?php if($batch_data[0]['inspection_report_by_admin'] != "")
            { ?>
              <td>
                <b style="vertical-align:top">Inspection Report By Admin : </b>
                <a href="<?php echo site_url('iibfbcbf/download_file_common/index/'.url_encode($batch_data[0]['batch_id']).'/inspection_report_by_admin'); ?>" class="example-image-link btn btn-success btn-sm">Download Inspection Report</a>
              </td>
            <?php } ?>
          </tr>

          <tr>
            <td colspan="2">
              <b style="vertical-align:top">Date/Start Time of Inspection: : </b><?php echo date('Y-m-d H:i:s'); ?>
              <input type="hidden" name="inspection_started_on" id="inspection_started_on" value="<?php if(set_value('inspection_started_on') != '') { echo set_value('inspection_started_on'); } else { echo date('Y-m-d H:i:s'); } ?>">
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="ibox mb-2">
  <div class="ibox-title bg_light_blue">
    <h5>Batch Inspection</h5>
    <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
  </div>

  <div class="ibox-content">
    <div class="table-responsive">
      <table class="table table-bordered table-striped batch_inspection_form_tbl" style="width:100%">
        <thead>
          <tr> 
            <th class="text-center"><b>Sr. No.</b></th>
            <th class="text-center"><b>Title</b></th>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <th class="text-center"><b>Inspection No:<?php echo $res['inspection_no']; ?></b></th>
              <?php }
            } ?>
          </tr>
        </thead>
        
        <tbody>
          <tr>
            <td></td>
            <td><b>Inspection Start Date/Time</b></td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class=""><b><?php echo $res['inspection_start_time']; ?></b></td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td></td>
            <td><b>Inspection End Date/Time</b></td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class=""><b><?php echo $res['created_on']; ?></b></td>
              <?php }
            } ?>
          </tr> 

          <tr>
            <td></td>
            <td><b>Inspection Name<b></td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="">
                  <b>
                    <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                    { 
                      echo breakLongWords($res['inspector_name'],30); 
                    } 
                    else { echo $res['inspector_name']; } ?>
                  </b>
                </td>
              <?php }
            } ?>
          </tr> 

          <tr>
            <td class="text-center">1</td>
            <td>Number of candidates logged-in at start of visit to the platform (excluding self / faculty/ coordinator or any other administrator)</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['candidates_loggedin'],30); 
                  } 
                  else { echo $res['candidates_loggedin']; } ?>
                </td>
              <?php }
            } ?>
          </tr> 

          <tr>
            <td class="text-center">2</td>
            <td>Whether the declared Link / Platform for the training got changed (Yes / No). If Yes, mention the Link / Name of the Platform for the training purpose.</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['platform_name'],30); 
                  } 
                  else { echo $res['platform_name']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">3</td>
            <td>Whether there are multiple logins with same name (Yes / No)? If Yes, how many such multiple logins are there?</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['multiple_login_same_name'],30); 
                  } 
                  else { echo $res['multiple_login_same_name']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">4</td>
            <td>Whether log-ins with instrument name (Samsung/oppo etc) is there (Yes / No). If Yes, how many such log-ins?</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['instrument_name'],30); 
                  } 
                  else { echo $res['instrument_name']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">5</td>
            <td>Whether any issues were faced while logging-in onto the Online Platform (e.g. wrong log-in credentials / waited for more than 2 minutes in waiting room / taking you into a platform of a different link / only buffering for minutes etc.)</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['issues'],30); 
                  } 
                  else { echo $res['issues']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">6</td>
            <td>Whether virtual recording is "On" or "not On" or started after your joining / insisting for the same. In case the session recording is not on, mention the reason of such situation.</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['training_session'],30); 
                  } 
                  else { echo $res['training_session']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">7</td>
            <td>Training Details:</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td></td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>(i) No. of candidates available during training sessions</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['session_candidates'],30); 
                  } 
                  else { echo $res['session_candidates']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>(ii) Is the training going on as per session plan shared by the Agency (can be confirmed from the Faculty)</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['training_session_plan'],30); 
                  } 
                  else { echo $res['training_session_plan']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">8</td>
            <td>Attendance:</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td></td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>i. Whether Attendance Sheet is updated by the Agency till the time of inspection (Yes / No).</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['attendance_sheet_updated'],30); 
                  } 
                  else { echo $res['attendance_sheet_updated']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>ii. Mode of taking attendance (Online / Screen Shot / Manual calling etc.)</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['attendance_mode'],30); 
                  } 
                  else { echo $res['attendance_mode']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>iii. Whether the Attendance Sheet is shown promptly to the Inspector on demand (Yes / No).</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['attendance_shown'],30); 
                  } 
                  else { echo $res['attendance_shown']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">9</td>
            <td>Is there any group of candidates attending the sessions from one place through a single device (Yes / No). If Yes, mention the candidates count and reason / situation in brief.</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['candidate_count_device'],30); 
                  } 
                  else { echo $res['candidate_count_device']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">10</td>
            <td>Faculty Details:</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td></td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>a) Whether Name / Code of Faculty is displayed on the platform (Yes / No).</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['actual_faculty'],30); 
                  } 
                  else { echo $res['actual_faculty']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>b) Name / Code of Faculty taking session</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['faculty_taking_session'],30); 
                  } 
                  else { echo $res['faculty_taking_session']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>c) If the Faculty who is taking session is different from the declared one, please mention: <br>i. Name and Qualification (highest) of the Faculty</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['name_qualification'],30); 
                  } 
                  else { echo $res['name_qualification']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>ii. No. of days / sessions she/he has taken / will take</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['no_of_days'],30); 
                  } 
                  else { echo $res['no_of_days']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>iii. Reason of such change in faculty</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['reason_of_change_in_faculty'],30); 
                  } 
                  else { echo $res['reason_of_change_in_faculty']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>iv. Whether the Faculty is having earlier experience in teaching / training in BFSI sector (mention in brief).</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['experience_teaching_training_BFSI_sector'],30); 
                  } 
                  else { echo $res['experience_teaching_training_BFSI_sector']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>d) Language in which the Faculty is taking the session</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['faculty_language'],30); 
                  } 
                  else { echo $res['faculty_language']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>e) The Faculty is taking sessions for how many hrs/min per day</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['faculty_session_time'],30); 
                  } 
                  else { echo $res['faculty_session_time']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>f) Whether minimum 2 faculties are taking sessions to complete the 50 / 100 hours training in the Batch.</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['two_faculty_taking_session'],30); 
                  } 
                  else { echo $res['two_faculty_taking_session']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>g) Whether the language(s) used by the Faculty is understandable by the candidates (can be confirmed from the participants).</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['faculty_language_understandable'],30); 
                  } 
                  else { echo $res['faculty_language_understandable']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>h) Whether the online training tools like whiteboard / PPT / PDF / Documents are used while delivering lectures.</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['whiteboard_ppt_pdf_used'],30); 
                  } 
                  else { echo $res['whiteboard_ppt_pdf_used']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">11</td>
            <td>Whether the faculty (in case of new faculty only) and all the candidates have attended preparatory / briefing session on the etiquettes of the upcoming BCBF training (Yes / No).</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['session_on_etiquettes'],30); 
                  } 
                  else { echo $res['session_on_etiquettes']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">12</td>
            <td>Whether the faculty and trainees were conversant with the process of on-line training.</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['faculty_trainees_conversant'],30); 
                  } 
                  else { echo $res['faculty_trainees_conversant']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">13</td>
            <td>Whether the candidates could recognise the name of the training providing agency / institution (Yes / No).</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['candidates_recognise'],30); 
                  } 
                  else { echo $res['candidates_recognise']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">14</td>
            <td>Whether candidates were given "Handbook on debt recovery" by the concerned agency.</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['handbook_on_debt_recovery'],30); 
                  } 
                  else { echo $res['handbook_on_debt_recovery']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">15</td>
            <td>Whether candidates are provided with other study materials in word/pdf format by the agency.</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['other_study_materials'],30); 
                  } 
                  else { echo $res['other_study_materials']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">16</td>
            <td>Whether the training was conducted without any interruption/ disturbances/ noises?</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['training_conduction'],30); 
                  } 
                  else { echo $res['training_conduction']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">17</td>
            <td>Batch Coordinator:</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td></td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>a) Whether Name of Batch Coordinator is displayed on the virtual platform with Batch Code (Yes / No).</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['batch_coordinator_available'],30); 
                  } 
                  else { echo $res['batch_coordinator_available']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>b) Name / Code of the Coordinator who is available in the Session</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['coordinator_available_name'],30); 
                  } 
                  else { echo $res['coordinator_available_name']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>c) The Coordinator is whether originally allotted or not (Yes/ No). In case No, mention the name and contact no. of the available Coordinator.</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['current_coordinator_available_name'],30); 
                  } 
                  else { echo $res['current_coordinator_available_name']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">18</td>
            <td>Any irregularity(ies) consistently / frequently persist despite repetitive reminders for rectification.</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['any_irregularity'],30); 
                  } 
                  else { echo $res['any_irregularity']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">19</td>
            <td>Assessment / rating (viz. 1-Poor / 2-Average / 3-Good / 4-Excellent) consequent to overall impression during visit to the virtual training session</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td></td>
              <?php }
            } ?>
          </tr>
          
          <tr>
            <td class="text-center"></td>
            <td>a) Quality of Teaching:<br>i. Level of interaction with candidates</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['teaching_quality_interaction_with_candidates'],30); 
                  } 
                  else { echo $res['teaching_quality_interaction_with_candidates']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>ii. Understanding with curiosity while teaching (especially  during soft-skill session)</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['teaching_quality_softskill_session'],30); 
                  } 
                  else { echo $res['teaching_quality_softskill_session']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>b) Candidates' attentiveness and participation</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['candidates_attentiveness'],30); 
                  } 
                  else { echo $res['candidates_attentiveness']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>c) Candidates' Attitude and their Behaviour</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['attitude_behaviour'],30); 
                  } 
                  else { echo $res['attitude_behaviour']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>d) Quality of learning by BCBF:<br>i.  Interaction with Faculty</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['learning_quality_interaction_with_faculty'],30); 
                  } 
                  else { echo $res['learning_quality_interaction_with_faculty']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>ii. Response to queries made by faculty / inspector</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['learning_quality_response_to_queries'],30); 
                  } 
                  else { echo $res['learning_quality_response_to_queries']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>e) Effectiveness of training</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['teaching_effectiveness'],30); 
                  } 
                  else { echo $res['teaching_effectiveness']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>f) Curriculum covered with reference to the Syllabus</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['curriculum_covered'],30); 
                  } 
                  else { echo $res['curriculum_covered']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>g) Overall compliance on:<br>i.  Training delivery</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['overall_compliance_training_delivery'],30); 
                  } 
                  else { echo $res['overall_compliance_training_delivery']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center"></td>
            <td>ii. Training coordination</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['overall_compliance_training_coordination'],30); 
                  } 
                  else { echo $res['overall_compliance_training_coordination']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">20</td>
            <td>Any other observations with respect to non-adherence to the conditions stipulated by IIBF for conducting on-line BCBF Training.</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['other_observations'],30); 
                  } 
                  else { echo $res['other_observations']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">21</td>
            <td>Overall Observation of the Inspector on the training of the BCBF Batch.</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['overall_observation'],30); 
                  } 
                  else { echo $res['overall_observation']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">22</td>
            <td>Over all compliance on imparting of BCBF Training</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td class="wrap">
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                  { 
                    echo breakLongWords($res['overall_compliance'],30); 
                  } 
                  else { echo $res['overall_compliance']; } ?>
                </td>
              <?php }
            } ?>
          </tr>

          <tr>
            <td class="text-center">23</td>
            <td>Attachment</td>
            <?php if(count($inspection_data) > 0)
            {
              foreach($inspection_data as $res)
              { ?>
                <td>
                  <?php if($res['attachment'] != "") 
                  { ?>
                    <a href="<?php echo site_url('iibfbcbf/download_file_common/index/'.url_encode($res['inspection_id']).'/attachment'); ?>" class="example-image-link btn btn-success btn-sm" target="_blank">Download</a>
                  <?php } ?>
                </td>
              <?php }
            } ?>
          </tr>  
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php if(isset($batch_candidate_data) && count($batch_candidate_data) > 0) { ?>
  <div class="ibox mb-2">
    <div class="ibox-title bg_light_blue">
      <h5>Batch Candidate's Details</h5>
      <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
    </div>

    <div class="ibox-content">
      <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped batch_inspection_form_tbl dataTables-example toggle_btn_tbl_outer" style="width:100%">
          <thead>
            <tr> 
              <th class="no-sort text-center nowrap" style="width:60px;">Sr. No.</th>
              <th class="text-center">Training Id</th>
              <th class="text-center nowrap" style="min-width:100px">Name</th>
              <th class="text-center nowrap">DOB</th>
              <th class="text-center nowrap">Mobile</th>
              <th class="text-center nowrap">Email</th>                                
              <th class="text-center nowrap no-sort">Photo</th>                                
              <th class="text-center">Present Count</th>                                
              <th class="text-center">Absent Count</th>                                
              <th class="text-center nowrap" style="min-width:100px;">Remark</th>   
              <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'admin') { ?>
                <th class="text-center all" style="width:120px;">Status</th>                                
                <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') { }else { ?>
                  <th class="text-center nowrap no-sort">Action</th>
                <?php } ?>
              <?php } ?>                             
            </tr>
          </thead>
          
          <tbody>
            <?php $sr_no = 1;
            $radio_attendance_option_arr = array('Present', 'Absent');
            foreach($batch_candidate_data as $batch_candidate_res)
            { ?>
              <tr>
                <td class="text-center"><?php echo $sr_no; ?></td>
                <td class="text-center"><?php echo $batch_candidate_res['training_id']; ?></td>
                <td>
                  <?php 
                    echo $batch_candidate_res['salutation']." ".$batch_candidate_res['first_name']; 
                    echo $batch_candidate_res['middle_name'] != "" ? " ".$batch_candidate_res['middle_name']:"";
                    echo $batch_candidate_res['last_name'] != "" ? " ".$batch_candidate_res['last_name']:"";
                  ?>
                </td>
                <td><?php echo $batch_candidate_res['dob']; ?></td>
                <td><?php echo $batch_candidate_res['mobile_no']; ?></td>
                <td><?php echo $batch_candidate_res['email_id']; ?></td>
                <td class="text-center">
                  <?php 
                  $imageUrl = base_url($candidate_photo_path . '/' . $batch_candidate_res['candidate_photo']) . "?" . time();
                  $headers = get_headers($imageUrl); 
                  
                  if (strpos($headers[0], "200") !== false) 
                  { 
                    if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                    { ?>
                      <img src="<?php echo $imageUrl; ?>" style="max-height:70px; max-width:70px;">
                    <?php } 
                    else 
                    { ?>
                      <div id="candidate_photo_preview_<?php echo $batch_candidate_res['candidate_id']; ?>" class="upload_img_preview">
                        <a href="<?php echo $imageUrl; ?>" class="example-image-link" data-lightbox="candidate_photo" data-title="<?php echo $batch_candidate_res['first_name']; ?>">
                          <img src="<?php echo $imageUrl; ?>">
                        </a>
                      </div>
                    <?php }
                  } ?>
                </td>

                <td class="text-center"><?php if(array_key_exists($batch_candidate_res['candidate_id'], $candidate_inspection_data_arr)) { echo $candidate_inspection_data_arr[$batch_candidate_res['candidate_id']]['present_cnt']; } else { echo '0'; } ?></td>
                
                <td class="text-center"><?php if(array_key_exists($batch_candidate_res['candidate_id'], $candidate_inspection_data_arr)) { echo $candidate_inspection_data_arr[$batch_candidate_res['candidate_id']]['absent_cnt']; } else { echo '0'; } ?></td>

                <td class="wrap"><?php if(array_key_exists($batch_candidate_res['candidate_id'], $candidate_inspection_data_arr)) { echo $candidate_inspection_data_arr[$batch_candidate_res['candidate_id']]['remark']; } ?></td>     
                
                <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'admin') { ?>
                  <td class="text-center">
                    <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') 
                    { 
                      if($batch_candidate_res['hold_release_status'] == '1') { echo 'Auto Hold'; }
                      else if($batch_candidate_res['hold_release_status'] == '2') { echo 'Manual Hold'; }
                      else if($batch_candidate_res['hold_release_status'] == '3') { echo 'Release'; }
                    }
                    else
                    { ?>
                      <span class="hide"><?php echo $batch_candidate_res['hold_release_status']; ?></span>
                      <?php 
                        $disabled_txt = $is_check = '';
                        $hover_txt = 'Click to make it Release';
                        if($batch_candidate_res['hold_release_status']=='3') 
                        { 
                          $hover_txt = 'Click to make it Manual Hold'; 
                          $is_check = "checked";
                        }

                        if(date('Y-m-d') < $batch_candidate_res['batch_start_date'])
                        {
                          $disabled_txt = 'disabled'; 
                          $hover_txt = 'The Batch is not started yet';
                        }
                        else if(date('Y-m-d') > $batch_candidate_res['batch_end_date']) 
                        { 
                          $disabled_txt = 'disabled'; 
                          $hover_txt = 'The Batch End Date is Over'; 
                        }
                        
                        $hold_text = 'Manual Hold'; 
                        if($batch_candidate_res['hold_release_status']=='1') { $hold_text = 'Auto Hold'; }

                        $onchange_fun = "change_hold_release_status('".$batch_candidate_res['candidate_id']."', '".$batch_candidate_res['hold_release_status']."')";
                      ?>
                      <div id="toggle_outer_<?php echo $batch_candidate_res['candidate_id']; ?>" class="<?php echo $disabled_txt; ?>" title="<?php echo $hover_txt; ?>">
                        <input <?php echo $is_check; ?> value="<?php echo $batch_candidate_res['candidate_id']; ?>" data-toggle="toggle" data-on="Release" data-off="<?php echo $hold_text; ?>" data-onstyle="success" data-offstyle="danger" id="toogle_id_<?php echo $batch_candidate_res['candidate_id']; ?>" onchange="<?php echo $onchange_fun; ?>" type="checkbox" <?php echo $disabled_txt; ?>>
                      </div>
                    <?php } ?>
                  </td>      
                  
                  <?php if(isset($inspection_report_pdf_flag) && $inspection_report_pdf_flag == '1') { }else { ?>
                    <td class="text-center">
                      <a href="<?php echo site_url('iibfbcbf/admin/inspection_summary_admin/candidates_inspection_details/'.url_encode($batch_data[0]['batch_id']).'/'.url_encode($batch_candidate_res['candidate_id'])); ?>" class="btn btn-success btn-xs" title="View Candidate Inspection Details" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>
                    </td>
                  <?php }
                } ?>                             
              </tr>
            <?php $sr_no++;
            } ?>
          </tbody>
        </table>
    </div>
  </div>
<?php } ?>