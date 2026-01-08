<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('iibfbcbf/inc_header'); ?>
  </head>
  
	<body class="fixed-sidebar">
    <?php $this->load->view('iibfbcbf/common/inc_loader'); ?>
		
		<div id="wrapper">
      <?php $this->load->view('iibfbcbf/inspector/inc_sidebar_inspector'); ?>		
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('iibfbcbf/inspector/inc_topbar_inspector'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>Add Inspection Report</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/inspector/dashboard_inspector'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item active"> <strong>Add Inspection Report</strong></li>
            </ol>
          </div>
					<div class="col-lg-2"> </div>
        </div>			
				
				<div class="wrapper wrapper-content animated fadeInRight">
          <section class="content-header text-center">
            <h2><b>BCBF Inspection - Online Training Form</b></h2>
            <h3 class="text-danger mt-3"><b>(This form will be filled in by the inspector while inspecting the batch)</b></h3>     
            <h4 class="mt-4">The BCBF Training Programs are to be conducted as per the latest terms and conditions as laid down by IIBF and abided by all the BCBF Institutions / Agencies.</h4> 
            <h4 class="mt-2 mb-4">Below mentioned format is to be filled with the fact of the training activities as delivered by the agencies and experienced by the assigned Inspector.</h4>               
          </section>

					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-content">
                  <form method="POST" id="search_form" class="search_form_common_all" action="<?php echo site_url('iibfbcbf/inspector/inspection_report_inspector/apply_search'); ?>" autocomplete="off">
                  	<div class="form-group text-left" style="min-width:400px;">
                      <select class="form-control chosen-select" name="s_batch_id" id="s_batch_id" onchange="validate_input('s_batch_id')" <?php /* onchange="apply_search()" */ ?> required>
                        <?php if(!isset($batch_data)) { ?><option value="">Select Batch For Inspection</option><?php } ?>
                        <?php if(count($batch_dropdown_data) > 0)
                        {
                          foreach($batch_dropdown_data as $res)
                          { ?>
                            <option value="<?php echo $res['batch_id']; ?>" <?php if($batch_id == $res['batch_id']) { echo 'selected'; } ?>><?php echo $res['batch_code']." (".$res['batch_hours']." Hours - ".date("d M Y", strtotime($res['batch_start_date']))." to ".date("d M Y", strtotime($res['batch_end_date'])).")"; ?></option>
                          <?php }
                        } ?>
                      </select>
                      <div id="s_batch_id_err"></div>
                    </div>

                    <div class="form-group" style="width:auto;">
                      <button type="submit" class="btn btn-primary" <?php /* onclick="apply_search()" */ ?>>Search</button>
                      <a href="<?php echo site_url('iibfbcbf/inspector/inspection_report_inspector/add_inspection_report_inspector'); ?>" class="btn btn-danger" <?php /* onclick="clear_search()" */ ?>>Clear</a>
                    </div>
                  </form>
                </div>
              </div>
              
              <?php if(isset($batch_data) && count($batch_data) > 0)
              { ?>
                <form method="POST" id="inspection_report_form" class="inspection_report_form" action="<?php echo site_url('iibfbcbf/inspector/inspection_report_inspector/add_inspection_report_inspector/'.$enc_batch_id); ?>" autocomplete="off"  enctype="multipart/form-data">
                  <input type="hidden" name="auto_save_flag" id="auto_save_flag" value="0">
                  <input type="hidden" name="enc_batch_id" id="enc_batch_id" value="<?php echo $enc_batch_id; ?>">
                  <h4 id="inspection_no">Inspection No.: <?php echo $inspection_no; ?></h4>
                  
                  <div class="ibox mb-2">
                    <div class="ibox-title" style="background:#effdff;">
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
                                  <a href="<?php echo site_url('iibfbcbf/download_file_common/index/'.$enc_batch_id.'/inspection_report_by_admin'); ?>" class="example-image-link btn btn-success btn-sm">Download Inspection Report</a>
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
                    <div class="ibox-title" style="background:#effdff;">
                      <h5>Batch Inspection</h5>
                    </div>

                    <div class="ibox-content">
                      <div class="table-responsive">
                        <table class="table table-bordered table-striped batch_inspection_form_tbl" style="width:100%">
                          <tbody>
                            <tr>
                              <td class="text-center"><b>1</b></td>
                              <td><b>Number of candidates logged-in at start of visit to the platform (excluding self / faculty/ coordinator or any other administrator) <sup class="text-danger">*</sup></b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="candidates_loggedin" id="candidates_loggedin" maxlength="100" placeholder="" required="required"><?php if(set_value('candidates_loggedin')) { echo set_value('candidates_loggedin'); } else if(isset($inspection_auto_save_data[0]['candidates_loggedin'])) { echo $inspection_auto_save_data[0]['candidates_loggedin']; } ?></textarea>
                                <note class="form_note" id="candidates_loggedin_err">Note: Please enter a maximum of 100 characters</note>
                                <?php if(form_error('candidates_loggedin')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('candidates_loggedin'); ?></label> <?php } ?>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>2</b></td>
                              <td><b>Whether the declared Link / Platform for the training got changed (Yes / No). If Yes, mention the Link / Name of the Platform for the training purpose.</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="platform_name" id="platform_name" maxlength="100" placeholder=""><?php if(set_value('platform_name')) { echo set_value('platform_name'); } else if(isset($inspection_auto_save_data[0]['platform_name'])) { echo $inspection_auto_save_data[0]['platform_name']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>3</b></td>
                              <td><b>Whether there are multiple logins with same name (Yes / No)? If Yes, how many such multiple logins are there?</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="multiple_login_same_name" id="multiple_login_same_name" maxlength="100" placeholder=""><?php if(set_value('multiple_login_same_name')) { echo set_value('multiple_login_same_name'); } else if(isset($inspection_auto_save_data[0]['multiple_login_same_name'])) { echo $inspection_auto_save_data[0]['multiple_login_same_name']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>4</b></td>
                              <td><b>Whether log-ins with instrument name (Samsung/oppo etc) is there (Yes / No). If Yes, how many such log-ins?</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="instrument_name" id="instrument_name" maxlength="100" placeholder=""><?php if(set_value('instrument_name')) { echo set_value('instrument_name'); } else if(isset($inspection_auto_save_data[0]['instrument_name'])) { echo $inspection_auto_save_data[0]['instrument_name']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>5</b></td>
                              <td><b>Whether any issues were faced while logging-in onto the Online Platform (e.g. wrong log-in credentials / waited for more than 2 minutes in waiting room / taking you into a platform of a different link / only buffering for minutes etc.)</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="issues" id="issues" maxlength="1000" placeholder=""><?php if(set_value('issues')) { echo set_value('issues'); } else if(isset($inspection_auto_save_data[0]['issues'])) { echo $inspection_auto_save_data[0]['issues']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 1000 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>6</b></td>
                              <td><b>Whether virtual recording is "On" or "not On" or started after your joining / insisting for the same. In case the session recording is not on, mention the reason of such situation.</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="training_session" id="training_session" maxlength="100" placeholder=""><?php if(set_value('training_session')) { echo set_value('training_session'); } else if(isset($inspection_auto_save_data[0]['training_session'])) { echo $inspection_auto_save_data[0]['training_session']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>7</b></td>
                              <td><b>Training Details:</b></td>
                              <td class="batch_inspection_form_input"></td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>(i) No. of candidates available during training sessions</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="session_candidates" id="session_candidates" maxlength="100" placeholder=""><?php if(set_value('session_candidates')) { echo set_value('session_candidates'); } else if(isset($inspection_auto_save_data[0]['session_candidates'])) { echo $inspection_auto_save_data[0]['session_candidates']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>(ii) Is the training going on as per session plan shared by the Agency (can be confirmed from the Faculty)</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="training_session_plan" id="training_session_plan" maxlength="100" placeholder=""><?php if(set_value('training_session_plan')) { echo set_value('training_session_plan'); } else if(isset($inspection_auto_save_data[0]['training_session_plan'])) { echo $inspection_auto_save_data[0]['training_session_plan']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>8</b></td>
                              <td><b>Attendance:</b></td>
                              <td class="batch_inspection_form_input"></td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>i. Whether Attendance Sheet is updated by the Agency till the time of inspection (Yes / No).</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="attendance_sheet_updated" id="attendance_sheet_updated" maxlength="100" placeholder=""><?php if(set_value('attendance_sheet_updated')) { echo set_value('attendance_sheet_updated'); } else if(isset($inspection_auto_save_data[0]['attendance_sheet_updated'])) { echo $inspection_auto_save_data[0]['attendance_sheet_updated']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>ii. Mode of taking attendance (Online / Screen Shot / Manual calling etc.)</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="attendance_mode" id="attendance_mode" maxlength="100" placeholder=""><?php if(set_value('attendance_mode')) { echo set_value('attendance_mode'); } else if(isset($inspection_auto_save_data[0]['attendance_mode'])) { echo $inspection_auto_save_data[0]['attendance_mode']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>iii. Whether the Attendance Sheet is shown promptly to the Inspector on demand (Yes / No).</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="attendance_shown" id="attendance_shown" maxlength="100" placeholder=""><?php if(set_value('attendance_shown')) { echo set_value('attendance_shown'); } else if(isset($inspection_auto_save_data[0]['attendance_shown'])) { echo $inspection_auto_save_data[0]['attendance_shown']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>9</b></td>
                              <td><b>Is there any group of candidates attending the sessions from one place through a single device (Yes / No). If Yes, mention the candidates count and reason / situation in brief.</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="candidate_count_device" id="candidate_count_device" maxlength="1000" placeholder=""><?php if(set_value('candidate_count_device')) { echo set_value('candidate_count_device'); } else if(isset($inspection_auto_save_data[0]['candidate_count_device'])) { echo $inspection_auto_save_data[0]['candidate_count_device']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 1000 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>10</b></td>
                              <td><b>Faculty Details:</b></td>
                              <td class="batch_inspection_form_input"></td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>a) Whether Name / Code of Faculty is displayed on the platform (Yes / No).</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="actual_faculty" id="actual_faculty" maxlength="100" placeholder=""><?php if(set_value('actual_faculty')) { echo set_value('actual_faculty'); } else if(isset($inspection_auto_save_data[0]['actual_faculty'])) { echo $inspection_auto_save_data[0]['actual_faculty']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>b) Name / Code of Faculty taking session</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="faculty_taking_session" id="faculty_taking_session" maxlength="100" placeholder=""><?php if(set_value('faculty_taking_session')) { echo set_value('faculty_taking_session'); } else if(isset($inspection_auto_save_data[0]['faculty_taking_session'])) { echo $inspection_auto_save_data[0]['faculty_taking_session']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>c) If the Faculty who is taking session is different from the declared one, please mention: <br>i. Name and Qualification (highest) of the Faculty</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="name_qualification" id="name_qualification" maxlength="1000" placeholder=""><?php if(set_value('name_qualification')) { echo set_value('name_qualification'); } else if(isset($inspection_auto_save_data[0]['name_qualification'])) { echo $inspection_auto_save_data[0]['name_qualification']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 1000 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>ii. No. of days / sessions she/he has taken / will take</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="no_of_days" id="no_of_days" maxlength="1000" placeholder=""><?php if(set_value('no_of_days')) { echo set_value('no_of_days'); } else if(isset($inspection_auto_save_data[0]['no_of_days'])) { echo $inspection_auto_save_data[0]['no_of_days']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 1000 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>iii. Reason of such change in faculty</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="reason_of_change_in_faculty" id="reason_of_change_in_faculty" maxlength="1000" placeholder=""><?php if(set_value('reason_of_change_in_faculty')) { echo set_value('reason_of_change_in_faculty'); } else if(isset($inspection_auto_save_data[0]['reason_of_change_in_faculty'])) { echo $inspection_auto_save_data[0]['reason_of_change_in_faculty']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 1000 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>iv. Whether the Faculty is having earlier experience in teaching / training in BFSI sector (mention in brief).</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="experience_teaching_training_BFSI_sector" id="experience_teaching_training_BFSI_sector" maxlength="1000" placeholder=""><?php if(set_value('experience_teaching_training_BFSI_sector')) { echo set_value('experience_teaching_training_BFSI_sector'); } else if(isset($inspection_auto_save_data[0]['experience_teaching_training_BFSI_sector'])) { echo $inspection_auto_save_data[0]['experience_teaching_training_BFSI_sector']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 1000 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>d) Language in which the Faculty is taking the session</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="faculty_language" id="faculty_language" maxlength="100" placeholder=""><?php if(set_value('faculty_language')) { echo set_value('faculty_language'); } else if(isset($inspection_auto_save_data[0]['faculty_language'])) { echo $inspection_auto_save_data[0]['faculty_language']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>e) The Faculty is taking sessions for how many hrs/min per day</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="faculty_session_time" id="faculty_session_time" maxlength="100" placeholder=""><?php if(set_value('faculty_session_time')) { echo set_value('faculty_session_time'); } else if(isset($inspection_auto_save_data[0]['faculty_session_time'])) { echo $inspection_auto_save_data[0]['faculty_session_time']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>f) Whether minimum 2 faculties are taking sessions to complete the 50 / 100 hours training in the Batch.</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="two_faculty_taking_session" id="two_faculty_taking_session" maxlength="100" placeholder=""><?php if(set_value('two_faculty_taking_session')) { echo set_value('two_faculty_taking_session'); } else if(isset($inspection_auto_save_data[0]['two_faculty_taking_session'])) { echo $inspection_auto_save_data[0]['two_faculty_taking_session']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>g) Whether the language(s) used by the Faculty is understandable by the candidates (can be confirmed from the participants).</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="faculty_language_understandable" id="faculty_language_understandable" maxlength="100" placeholder=""><?php if(set_value('faculty_language_understandable')) { echo set_value('faculty_language_understandable'); } else if(isset($inspection_auto_save_data[0]['faculty_language_understandable'])) { echo $inspection_auto_save_data[0]['faculty_language_understandable']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>h) Whether the online training tools like whiteboard / PPT / PDF / Documents are used while delivering lectures.</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="whiteboard_ppt_pdf_used" id="whiteboard_ppt_pdf_used" maxlength="100" placeholder=""><?php if(set_value('whiteboard_ppt_pdf_used')) { echo set_value('whiteboard_ppt_pdf_used'); } else if(isset($inspection_auto_save_data[0]['whiteboard_ppt_pdf_used'])) { echo $inspection_auto_save_data[0]['whiteboard_ppt_pdf_used']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>11</b></td>
                              <td><b>Whether the faculty (in case of new faculty only) and all the candidates have attended preparatory / briefing session on the etiquettes of the upcoming BCBF training (Yes / No).</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="session_on_etiquettes" id="session_on_etiquettes" maxlength="100" placeholder=""><?php if(set_value('session_on_etiquettes')) { echo set_value('session_on_etiquettes'); } else if(isset($inspection_auto_save_data[0]['session_on_etiquettes'])) { echo $inspection_auto_save_data[0]['session_on_etiquettes']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>12</b></td>
                              <td><b>Whether the faculty and trainees were conversant with the process of on-line training.</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="faculty_trainees_conversant" id="faculty_trainees_conversant" maxlength="100" placeholder=""><?php if(set_value('faculty_trainees_conversant')) { echo set_value('faculty_trainees_conversant'); } else if(isset($inspection_auto_save_data[0]['faculty_trainees_conversant'])) { echo $inspection_auto_save_data[0]['faculty_trainees_conversant']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>13</b></td>
                              <td><b>Whether the candidates could recognise the name of the training providing agency / institution (Yes / No).</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="candidates_recognise" id="candidates_recognise" maxlength="100" placeholder=""><?php if(set_value('candidates_recognise')) { echo set_value('candidates_recognise'); } else if(isset($inspection_auto_save_data[0]['candidates_recognise'])) { echo $inspection_auto_save_data[0]['candidates_recognise']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>14</b></td>
                              <td><b>Whether candidates were given "Handbook on debt recovery" by the concerned agency.</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="handbook_on_debt_recovery" id="handbook_on_debt_recovery" maxlength="100" placeholder=""><?php if(set_value('handbook_on_debt_recovery')) { echo set_value('handbook_on_debt_recovery'); } else if(isset($inspection_auto_save_data[0]['handbook_on_debt_recovery'])) { echo $inspection_auto_save_data[0]['handbook_on_debt_recovery']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>15</b></td>
                              <td><b>Whether candidates are provided with other study materials in word/pdf format by the agency.</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="other_study_materials" id="yother_study_materialsyy" maxlength="100" placeholder=""><?php if(set_value('other_study_materials')) { echo set_value('other_study_materials'); } else if(isset($inspection_auto_save_data[0]['other_study_materials'])) { echo $inspection_auto_save_data[0]['other_study_materials']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>16</b></td>
                              <td><b>Whether the training was conducted without any interruption/ disturbances/ noises?</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="training_conduction" id="training_conduction" maxlength="100" placeholder=""><?php if(set_value('training_conduction')) { echo set_value('training_conduction'); } else if(isset($inspection_auto_save_data[0]['training_conduction'])) { echo $inspection_auto_save_data[0]['training_conduction']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>17</b></td>
                              <td><b>Batch Coordinator:</b></td>
                              <td class="batch_inspection_form_input"></td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>a) Whether Name of Batch Coordinator is displayed on the virtual platform with Batch Code (Yes / No).</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="batch_coordinator_available" id="batch_coordinator_available" maxlength="100" placeholder=""><?php if(set_value('batch_coordinator_available')) { echo set_value('batch_coordinator_available'); } else if(isset($inspection_auto_save_data[0]['batch_coordinator_available'])) { echo $inspection_auto_save_data[0]['batch_coordinator_available']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>b) Name / Code of the Coordinator who is available in the Session</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="coordinator_available_name" id="coordinator_available_name" maxlength="100" placeholder=""><?php if(set_value('coordinator_available_name')) { echo set_value('coordinator_available_name'); } else if(isset($inspection_auto_save_data[0]['coordinator_available_name'])) { echo $inspection_auto_save_data[0]['coordinator_available_name']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>c) The Coordinator is whether originally allotted or not (Yes/ No). In case No, mention the name and contact no. of the available Coordinator.</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="current_coordinator_available_name" id="current_coordinator_available_name" maxlength="100" placeholder=""><?php if(set_value('current_coordinator_available_name')) { echo set_value('current_coordinator_available_name'); } else if(isset($inspection_auto_save_data[0]['current_coordinator_available_name'])) { echo $inspection_auto_save_data[0]['current_coordinator_available_name']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 100 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>18</b></td>
                              <td><b>Any irregularity(ies) consistently / frequently persist despite repetitive reminders for rectification.</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="any_irregularity" id="any_irregularity" maxlength="1000" placeholder=""><?php if(set_value('any_irregularity')) { echo set_value('any_irregularity'); } else if(isset($inspection_auto_save_data[0]['any_irregularity'])) { echo $inspection_auto_save_data[0]['any_irregularity']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 1000 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>19</b></td>
                              <td><b>Assessment / rating (viz. 1-Poor / 2-Average / 3-Good / 4-Excellent) consequent to overall impression during visit to the virtual training session</b></td>
                              <td class="batch_inspection_form_input"> </td>
                            </tr>
                            
                            <?php $radio_option_arr = array('Poor', 'Average', 'Good', 'Excellent');  ?>
                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>a) Quality of Teaching:<br>i. Level of interaction with candidates</b></td>
                              <td class="batch_inspection_form_input batch_inspection_form_radio">
                                <?php foreach($radio_option_arr as $radio_option_res){?>
                                  <label class="css_checkbox_radio radio_only"> <?php echo $radio_option_res; ?>
                                    <input type="radio" value="<?php echo $radio_option_res; ?>" name="teaching_quality_interaction_with_candidates" <?php if(set_value('teaching_quality_interaction_with_candidates') == $radio_option_res) { echo "checked"; } else if(isset($inspection_auto_save_data[0]['teaching_quality_interaction_with_candidates']) && $inspection_auto_save_data[0]['teaching_quality_interaction_with_candidates'] == $radio_option_res) { echo "checked"; } ?>>
                                    <span class="radiobtn"></span>
                                  </label>&nbsp;&nbsp;
                                <?php } ?>
                                <a class="btn btn-sm btn-danger uncheck_btn" href="javascript:void(0);" onclick="Uncheck_radio('teaching_quality_interaction_with_candidates')">Uncheck</a>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>ii. Understanding with curiosity while teaching (especially  during soft-skill session)</b></td>
                              <td class="batch_inspection_form_input batch_inspection_form_radio">
                                <?php foreach($radio_option_arr as $radio_option_res){?>
                                  <label class="css_checkbox_radio radio_only"> <?php echo $radio_option_res; ?>
                                    <input type="radio" value="<?php echo $radio_option_res; ?>" name="teaching_quality_softskill_session" <?php if(set_value('teaching_quality_softskill_session') == $radio_option_res) { echo "checked"; } else if(isset($inspection_auto_save_data[0]['teaching_quality_softskill_session']) && $inspection_auto_save_data[0]['teaching_quality_softskill_session'] == $radio_option_res) { echo "checked"; } ?>>
                                    <span class="radiobtn"></span>
                                  </label>&nbsp;&nbsp;
                                <?php } ?>
                                <a class="btn btn-sm btn-danger uncheck_btn" href="javascript:void(0);" onclick="Uncheck_radio('teaching_quality_softskill_session')">Uncheck</a>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>b) Candidates' attentiveness and participation</b></td>
                              <td class="batch_inspection_form_input batch_inspection_form_radio">
                                <?php foreach($radio_option_arr as $radio_option_res){?>
                                  <label class="css_checkbox_radio radio_only"> <?php echo $radio_option_res; ?>
                                    <input type="radio" value="<?php echo $radio_option_res; ?>" name="candidates_attentiveness" <?php if(set_value('candidates_attentiveness') == $radio_option_res) { echo "checked"; } else if(isset($inspection_auto_save_data[0]['candidates_attentiveness']) && $inspection_auto_save_data[0]['candidates_attentiveness'] == $radio_option_res) { echo "checked"; } ?>>
                                    <span class="radiobtn"></span>
                                  </label>&nbsp;&nbsp;
                                <?php } ?>
                                <a class="btn btn-sm btn-danger uncheck_btn" href="javascript:void(0);" onclick="Uncheck_radio('candidates_attentiveness')">Uncheck</a>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>c) Candidates' Attitude and their Behaviour</b></td>
                              <td class="batch_inspection_form_input batch_inspection_form_radio">
                                <?php foreach($radio_option_arr as $radio_option_res){?>
                                  <label class="css_checkbox_radio radio_only"> <?php echo $radio_option_res; ?>
                                    <input type="radio" value="<?php echo $radio_option_res; ?>" name="attitude_behaviour" <?php if(set_value('attitude_behaviour') == $radio_option_res) { echo "checked"; } else if(isset($inspection_auto_save_data[0]['attitude_behaviour']) && $inspection_auto_save_data[0]['attitude_behaviour'] == $radio_option_res) { echo "checked"; } ?>>
                                    <span class="radiobtn"></span>
                                  </label>&nbsp;&nbsp;
                                <?php } ?>
                                <a class="btn btn-sm btn-danger uncheck_btn" href="javascript:void(0);" onclick="Uncheck_radio('attitude_behaviour')">Uncheck</a>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>d) Quality of learning by BCBF:<br>i.  Interaction with Faculty</b></td>
                              <td class="batch_inspection_form_input batch_inspection_form_radio">
                                <?php foreach($radio_option_arr as $radio_option_res){?>
                                  <label class="css_checkbox_radio radio_only"> <?php echo $radio_option_res; ?>
                                    <input type="radio" value="<?php echo $radio_option_res; ?>" name="learning_quality_interaction_with_faculty" <?php if(set_value('learning_quality_interaction_with_faculty') == $radio_option_res) { echo "checked"; } else if(isset($inspection_auto_save_data[0]['learning_quality_interaction_with_faculty']) && $inspection_auto_save_data[0]['learning_quality_interaction_with_faculty'] == $radio_option_res) { echo "checked"; } ?>>
                                    <span class="radiobtn"></span>
                                  </label>&nbsp;&nbsp;
                                <?php } ?>
                                <a class="btn btn-sm btn-danger uncheck_btn" href="javascript:void(0);" onclick="Uncheck_radio('learning_quality_interaction_with_faculty')">Uncheck</a>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>ii. Response to queries made by faculty / inspector</b></td>
                              <td class="batch_inspection_form_input batch_inspection_form_radio">
                                <?php foreach($radio_option_arr as $radio_option_res){?>
                                  <label class="css_checkbox_radio radio_only"> <?php echo $radio_option_res; ?>
                                    <input type="radio" value="<?php echo $radio_option_res; ?>" name="learning_quality_response_to_queries" <?php if(set_value('learning_quality_response_to_queries') == $radio_option_res) { echo "checked"; } else if(isset($inspection_auto_save_data[0]['learning_quality_response_to_queries']) && $inspection_auto_save_data[0]['learning_quality_response_to_queries'] == $radio_option_res) { echo "checked"; } ?>>
                                    <span class="radiobtn"></span>
                                  </label>&nbsp;&nbsp;
                                <?php } ?>
                                <a class="btn btn-sm btn-danger uncheck_btn" href="javascript:void(0);" onclick="Uncheck_radio('learning_quality_response_to_queries')">Uncheck</a>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>e) Effectiveness of training</b></td>
                              <td class="batch_inspection_form_input batch_inspection_form_radio">
                                <?php foreach($radio_option_arr as $radio_option_res){?>
                                  <label class="css_checkbox_radio radio_only"> <?php echo $radio_option_res; ?>
                                    <input type="radio" value="<?php echo $radio_option_res; ?>" name="teaching_effectiveness" <?php if(set_value('teaching_effectiveness') == $radio_option_res) { echo "checked"; } else if(isset($inspection_auto_save_data[0]['teaching_effectiveness']) && $inspection_auto_save_data[0]['teaching_effectiveness'] == $radio_option_res) { echo "checked"; } ?>>
                                    <span class="radiobtn"></span>
                                  </label>&nbsp;&nbsp;
                                <?php } ?>
                                <a class="btn btn-sm btn-danger uncheck_btn" href="javascript:void(0);" onclick="Uncheck_radio('teaching_effectiveness')">Uncheck</a>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>f) Curriculum covered with reference to the Syllabus</b></td>
                              <td class="batch_inspection_form_input batch_inspection_form_radio">
                                <?php foreach($radio_option_arr as $radio_option_res){?>
                                  <label class="css_checkbox_radio radio_only"> <?php echo $radio_option_res; ?>
                                    <input type="radio" value="<?php echo $radio_option_res; ?>" name="curriculum_covered" <?php if(set_value('curriculum_covered') == $radio_option_res) { echo "checked"; } else if(isset($inspection_auto_save_data[0]['curriculum_covered']) && $inspection_auto_save_data[0]['curriculum_covered'] == $radio_option_res) { echo "checked"; } ?>>
                                    <span class="radiobtn"></span>
                                  </label>&nbsp;&nbsp;
                                <?php } ?>
                                <a class="btn btn-sm btn-danger uncheck_btn" href="javascript:void(0);" onclick="Uncheck_radio('curriculum_covered')">Uncheck</a>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>g) Overall compliance on:<br>i.  Training delivery</b></td>
                              <td class="batch_inspection_form_input batch_inspection_form_radio">
                                <?php foreach($radio_option_arr as $radio_option_res){?>
                                  <label class="css_checkbox_radio radio_only"> <?php echo $radio_option_res; ?>
                                    <input type="radio" value="<?php echo $radio_option_res; ?>" name="overall_compliance_training_delivery" <?php if(set_value('overall_compliance_training_delivery') == $radio_option_res) { echo "checked"; } else if(isset($inspection_auto_save_data[0]['overall_compliance_training_delivery']) && $inspection_auto_save_data[0]['overall_compliance_training_delivery'] == $radio_option_res) { echo "checked"; } ?>>
                                    <span class="radiobtn"></span>
                                  </label>&nbsp;&nbsp;
                                <?php } ?>
                                <a class="btn btn-sm btn-danger uncheck_btn" href="javascript:void(0);" onclick="Uncheck_radio('overall_compliance_training_delivery')">Uncheck</a>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b></b></td>
                              <td><b>ii. Training coordination</b></td>
                              <td class="batch_inspection_form_input batch_inspection_form_radio">
                                <?php foreach($radio_option_arr as $radio_option_res){?>
                                  <label class="css_checkbox_radio radio_only"> <?php echo $radio_option_res; ?>
                                    <input type="radio" value="<?php echo $radio_option_res; ?>" name="overall_compliance_training_coordination" <?php if(set_value('overall_compliance_training_coordination') == $radio_option_res) { echo "checked"; } else if(isset($inspection_auto_save_data[0]['overall_compliance_training_coordination']) && $inspection_auto_save_data[0]['overall_compliance_training_coordination'] == $radio_option_res) { echo "checked"; } ?>>
                                    <span class="radiobtn"></span>
                                  </label>&nbsp;&nbsp;
                                <?php } ?>
                                <a class="btn btn-sm btn-danger uncheck_btn" href="javascript:void(0);" onclick="Uncheck_radio('overall_compliance_training_coordination')">Uncheck</a>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>20</b></td>
                              <td><b>Any other observations with respect to non-adherence to the conditions stipulated by IIBF for conducting on-line BCBF Training.</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="other_observations" id="other_observations" maxlength="1000" placeholder=""><?php if(set_value('other_observations')) { echo set_value('other_observations'); } else if(isset($inspection_auto_save_data[0]['other_observations'])) { echo $inspection_auto_save_data[0]['other_observations']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 1000 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>21</b></td>
                              <td><b>Overall Observation of the Inspector on the training of the BCBF Batch.</b></td>
                              <td class="batch_inspection_form_input">
                                <textarea class="form-control" name="overall_observation" id="overall_observation" maxlength="1000" placeholder=""><?php if(set_value('overall_observation')) { echo set_value('overall_observation'); } else if(isset($inspection_auto_save_data[0]['overall_observation'])) { echo $inspection_auto_save_data[0]['overall_observation']; } ?></textarea>
                                <note class="form_note">Note: Please enter a maximum of 1000 characters</note>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>22</b></td>
                              <td><b>Over all compliance on imparting of BCBF Training</b></td>
                              <td class="batch_inspection_form_input batch_inspection_form_radio">
                                <?php foreach($radio_option_arr as $radio_option_res){?>
                                  <label class="css_checkbox_radio radio_only"> <?php echo $radio_option_res; ?>
                                    <input type="radio" value="<?php echo $radio_option_res; ?>" name="overall_compliance" <?php if(set_value('overall_compliance') == $radio_option_res) { echo "checked"; } else if(isset($inspection_auto_save_data[0]['overall_compliance']) && $inspection_auto_save_data[0]['overall_compliance'] == $radio_option_res) { echo "checked"; } ?>>
                                    <span class="radiobtn"></span>
                                  </label>&nbsp;&nbsp;
                                <?php } ?>
                                <a class="btn btn-sm btn-danger uncheck_btn" href="javascript:void(0);" onclick="Uncheck_radio('overall_compliance')">Uncheck</a>
                              </td>
                            </tr>

                            <tr>
                              <td class="text-center"><b>23</b></td>
                              <td><b>Attachment, if any</b></td>
                              <td class="batch_inspection_form_input">
                                <div class="img_preview_input_outer pull-left">
                                  <input type="file" name="attachment" id="attachment" class="form-control" accept=".txt,.doc,.docx,.pdf,.jpg,.png,.jpeg" data-accept=".txt,.doc,.docx,.pdf,.jpg,.png,.jpeg" onchange="show_preview(event, 'attachment_preview'); validate_input('attachment');" />
                              
                                  <note class="form_note" id="attachment_err">Note: Please Upload only .txt, .doc, .docx, .pdf, .jpg, .png, .jpeg Files with size upto 5 MB.</note>
                                  
                                  <?php if(form_error('attachment')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('attachment'); ?></label> <?php } ?>
                                  <?php if($attachment_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $attachment_error; ?></label> <?php } ?>

                                  <?php /* <input type="hidden" id="isFileUpload" value="0"> */ ?>
                                  <a class="btn btn-sm btn-danger uncheck_btn mt-2" href="javascript:void(0);" onclick="reset_file()">Reset file</a>
                                </div>

                                <div id="attachment_preview" class="upload_img_preview pull-right">
                                  <i class="fa fa-picture-o" aria-hidden="true"></i>
                                </div>
                              </td>
                            </tr>                            
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  
                  <?php if(isset($batch_candidate_data) && count($batch_candidate_data) > 0) { ?>
                    <div class="ibox mb-2">
                      <div class="ibox-title" style="background:#effdff;">
                        <h5>Batch Candidate's Details</h5>
                      </div>

                      <div class="ibox-content">
                        <div class="table-responsive">
                          <table class="table table-bordered table-hover table-striped batch_inspection_form_tbl" style="width:100%">
                            <thead>
                              <tr> 
                                <th class="no-sort text-center nowrap" style="width:60px;">Sr. No.</th>
                                <th class="text-center">Training Id</th>
                                <th class="text-center nowrap" style="min-width:100px">Name</th>
                                <th class="text-center nowrap">DOB</th>
                                <th class="text-center nowrap">Mobile</th>
                                <th class="text-center nowrap">Email</th>                                
                                <th class="text-center nowrap">Photo</th>                                
                                <th class="text-center nowrap">Attendance</th>                                
                                <th class="text-center nowrap">Remark</th>                                
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
                                    { ?>
                                      <div id="candidate_photo_preview_<?php echo $batch_candidate_res['candidate_id']; ?>" class="upload_img_preview">
                                        <a href="<?php echo $imageUrl; ?>" class="example-image-link" data-lightbox="candidate_photo" data-title="<?php echo $batch_candidate_res['first_name']; ?>">
                                          <img src="<?php echo $imageUrl; ?>">
                                        </a>
                                      </div>
                                    <?php } ?>
                                  </td>
                                  <td class="">
                                    <?php foreach($radio_attendance_option_arr as $radio_option_res){?>
                                      <label class="css_checkbox_radio radio_only"> <?php echo $radio_option_res; ?>
                                        <input type="radio" value="<?php echo $radio_option_res; ?>" id="id_<?php echo $radio_option_res."_".$batch_candidate_res['candidate_id']; ?>" name="attendance[<?php echo $batch_candidate_res['candidate_id']; ?>][]" <?php if(set_value('attendance['.$batch_candidate_res['candidate_id'].'][0]') == $radio_option_res) { echo "checked"; } else if(isset($inspection_candidate_auto_save_arr[$batch_candidate_res['candidate_id']]['attendance']) && $inspection_candidate_auto_save_arr[$batch_candidate_res['candidate_id']]['attendance'] == $radio_option_res) { echo "checked"; } ?>>
                                        <span class="radiobtn"></span>
                                      </label><br>
                                    <?php } ?>

                                    <a class="btn btn-sm btn-danger uncheck_btn" href="javascript:void(0);" onclick="Uncheck_radio('attendance[<?php echo $batch_candidate_res['candidate_id']; ?>][]')">Uncheck</a>
                                  </td>
                                  <td>
                                    <textarea class="form-control" name="remark[<?php echo $batch_candidate_res['candidate_id']; ?>][]" id="remark_<?php echo $batch_candidate_res['candidate_id']; ?>" maxlength="250"><?php if(set_value('remark['.$batch_candidate_res['candidate_id'].'][0]')) { echo set_value('remark['.$batch_candidate_res['candidate_id'].'][0]'); } else if(isset($inspection_candidate_auto_save_arr[$batch_candidate_res['candidate_id']]['remark'])) { echo $inspection_candidate_auto_save_arr[$batch_candidate_res['candidate_id']]['remark']; } ?>
                                    <?php echo set_value('remark['.$batch_candidate_res['candidate_id'].'][0]'); ?></textarea>
                                    <note class="form_note" id="remark_err_<?php echo $batch_candidate_res['candidate_id']; ?>">Note: Please enter a maximum of 250 characters</note>
                                  </td>                                  
                                </tr>
                              <?php $sr_no++;
                              } ?>
                            </tbody>
                          </table>
                      </div>
                    </div>
                  <?php } ?>

                  <div class="form-group text-center mt-3 mb-0" id="submit_btn_outer">
                    <button type="submit" class="btn btn-primary" style="min-width:100px;">Submit</button>
                    <button type="button" class="btn btn-danger" onclick="clear_form_data()" style="min-width:100px;">Clear</button>
                  </div>
                </form>

                <div class="modal inmodal fade session_modal_outer" id="SessionErrorModal" tabindex="-1" role="dialog"  aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Session Error</h4>
                      </div>
                      <div class="modal-body">
                        Your session has expired. Please <strong><a href="<?php echo site_url('iibfbcbf/login'); ?>" onclick="close_modal()" target="_blank">Click Here</a></strong> to login again. After logged in, please revisit the same page and submit your report.
                        <note>Note: If you are already logged in, please click the 'Close' button and proceed to submit your report.</note>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="modal inmodal fade session_modal_outer" id="SessionErrorModalAutosave" tabindex="-1" role="dialog"  aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" onclick="close_modal_autosave()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Session Error</h4>
                      </div>
                      <div class="modal-body">
                        Your session has expired. Your data has been autosaved.<br>Please login again to resume the inspection report submission.
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="close_modal_autosave()">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>					
          </div>
        </div>
				<?php $this->load->view('iibfbcbf/inspector/inc_footerbar_inspector'); ?>		
      </div>
    </div>
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>		
    <?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>

    <script language="javascript">
      /* function clear_search() 
      { 
        $("#s_batch_id").val(''); 
        $('#search_form').submit(); 
      }

      function apply_search() 
      { 
        $('#search_form').submit();
      } */

      //START : JQUERY VALIDATION SCRIPT 
      function validate_input(input_id) { $("#"+input_id).valid(); }
      $(document ).ready( function() 
      {
        var form = $("#search_form").validate( 
        {
          onkeyup: function(element) { $(element).valid(); },          
          rules:
          {
            s_batch_id:{ required: true },           
          },
          messages:
          {
            s_batch_id: { required: "Please select the batch for inspection" },
          }, 
          errorPlacement: function(error, element) // For replace error 
          {
            if (element.attr("name") == "s_batch_id") { error.insertAfter("#s_batch_id_err"); }
            else { error.insertAfter(element); }
          },          
          submitHandler: function(form) 
          {
            form.submit();
          }
        });
      });
      //END : JQUERY VALIDATION SCRIPT

      <?php if(isset($batch_data) && count($batch_data) > 0)
      { ?>
        function clear_form_data()
        {
          location.reload();
        }

        function Uncheck_radio(input_name)
        {
          $('input:radio[name="'+input_name+'"]').attr('checked',false);
          $('input[name="'+input_name+'"]').prop('checked', false);
          $("#auto_save_flag").val("1");
        }

        function reset_file()
        {
          var $el = $('#attachment');
          $el.wrap('<form>').closest('form').get(0).reset();
          $el.unwrap();
          //$('#isFileUpload').val('0');
          $('#attachment_preview').html('<i class="fa fa-picture-o" aria-hidden="true"></i>');
          validate_input('attachment')
        }

        //START : JQUERY VALIDATION SCRIPT         
        $(document ).ready( function() 
        {
          var form = $("#inspection_report_form").validate( 
          {
            onkeyup: function(element) { $(element).valid(); },          
            rules:
            {
              candidates_loggedin:{ required: true }, 
              attachment:{ check_valid_file:true, valid_file_format:'.txt,.doc,.docx,.pdf,.jpg,.png,.jpeg', filesize_max:'5000000' }, //use size in bytes //filesize_max: 1MB : 1000000              
            },
            messages:
            {
              candidates_loggedin: { required: "Please enter the value" },
              attachment: { valid_file_format:"Please upload only .txt, .doc, .docx, .pdf, .jpg, .png, .jpeg files", filesize_max:"Please upload file less than 5 MB" },
            }, 
            errorPlacement: function(error, element) // For replace error 
            {
              if (element.attr("name") == "candidates_loggedin") { error.insertAfter("#candidates_loggedin_err"); }
              else if (element.attr("name") == "attachment") { error.insertAfter("#attachment_err"); }
              else { error.insertAfter(element); }
            },          
            submitHandler: function(form) 
            {
              $('#page_loader').show();
              $.ajax(
              {
                url: "<?php echo site_url('iibfbcbf/Check_inspector_session/index'); ?>",
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
                    var input = document.getElementById('attachment');
                    var selected_file = input.files[0];
                    
                    var confirm_title = 'Please confirm';
                    var confirm_msg = 'Please confirm to submit the details';
                    if (typeof selected_file === "undefined") 
                    {
                      confirm_title = 'You have missed to upload Attachment';
                      confirm_msg = 'Please confirm to submit the inspection report without uploading an attachment?';
                    }

                    $("#page_loader").hide();
                    swal({ title: confirm_title, text: confirm_msg, type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: false }, 
                    function (selectedOption) 
                    { 
                      if (selectedOption===true)
                      {
                        swal.close();
                        $("#page_loader").show();
                        $("#submit_btn_outer").html('<button type="button" class="btn btn-primary" style="min-width:100px;" style="cursor:wait">Submit <i class="fa fa-spinner" aria-hidden="true"></i></button> <button type="button" class="btn btn-danger" onclick="clear_form_data()" style="min-width:100px;">Clear</button>');
                        form.submit();
                      }
                      else 
                      {
                        $('html, body').animate(
                        {
                          scrollTop: $("#attachment").offset().top - 30                          
                        }, 1000);

                        setTimeout(function() 
                        {
                          $('#attachment').focus(); // Focus on attachment field after a short delay
                        }, 1000);
                      }
                    });
                  }
                  else 
                  { 
                    $("#SessionErrorModal").modal("show");
                    $('#page_loader').hide();
                    return false;
                  }
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                  console.log('AJAX request failed: ' + errorThrown);
                  alert("Error occurred. Please try again.")
                  $('#page_loader').hide();
                }
              });
            }
          });
        });
        //END : JQUERY VALIDATION SCRIPT

        function close_modal()
        {
          $("#SessionErrorModal").modal('hide');
        }

        $(document).ready(function()
        {
          // Attach change event listener to all input elements
          $('input, textarea, input[type=radio]').on('change', function() { $("#auto_save_flag").val("1"); });
          $('textarea').on('keyup', function() { $("#auto_save_flag").val("1"); });

          // Function to submit form via AJAX
          function auto_save_form_ajax() 
          {
            /* var currentDate = new Date();
            console.log(currentDate) */
            
            var auto_save_flag = $("#auto_save_flag").val();
            if(auto_save_flag == '1')
            {
              $.ajax(
              {
                url: "<?php echo site_url('iibfbcbf/Check_inspector_session/index'); ?>",
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
                    $.ajax(
                    {
                      url: '<?php echo site_url('iibfbcbf/inspector/inspection_report_inspector/auto_save_form_ajax'); ?>', // Replace with your form submission URL
                      type: 'POST',
                      data: $('#inspection_report_form').serialize(), // Serialize form data
                      success: function(response) 
                      {
                        $("#auto_save_flag").val('0');
                      },
                      error: function(xhr, status, error) 
                      {
                        console.error('Error submitting form:', error);
                      }
                    });  
                  }
                  else 
                  { 
                    $("#auto_save_flag").val('0');
                    $("#SessionErrorModalAutosave").modal({backdrop: 'static', keyboard: false}, 'show');                    
                  }
                },
                error: function(xhr, status, error) 
                {
                  console.error('Error submitting form:', error);
                }
              });
            }
          }

          // Submit the form every 30 seconds
          setInterval(auto_save_form_ajax, 30000); // 30 seconds = 30000 milliseconds
        });

        function close_modal_autosave()
        {
          window.location.href = "<?php echo site_url('iibfbcbf/login'); ?>";
        }
      <?php }
      
      if($submission_time_error_msg != '') { ?>
        sweet_alert_error("<?php echo $submission_time_error_msg; ?>"); 
      <?php }
      
      if($error != '') { ?>
        sweet_alert_error("<?php echo $error; ?>"); 
      <?php }?>
    </script>

    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>