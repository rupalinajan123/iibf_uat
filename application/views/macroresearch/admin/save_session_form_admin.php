<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('supervision/inc_header'); ?>    
  </head>
  
	<body class="fixed-sidebar">
    <?php $this->load->view('supervision/common/inc_loader'); ?>
		
		<div id="wrapper">
      <?php $this->load->view('supervision/admin/inc_sidebar_admin'); ?>		
			<div id="page-wrapper" class="gray-bg">				
      <?php $this->load->view('supervision/admin/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2><?php echo $mode; ?>Supervision Report</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('supervision/admin/dashboard_admin'); ?>">Dashboard</a></li>
              <li class="breadcrumb-item">Masters</li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('supervision/admin/candidate/session_forms'); ?>">Supervision Report</a></li>
							<li class="breadcrumb-item active"> <strong><?php echo $mode; ?> Supervision Report</strong></li>
            </ol>
          </div>
					<div class="col-lg-2"> </div>
        </div>			
				
				<div class="wrapper wrapper-content animated fadeInRight">
          <div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-title">
									<div class="ibox-tools">
										<a href="<?php echo site_url('supervision/admin/candidate/session_forms'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a>
                  </div>
                </div>
								<div class="ibox-content">
                  <form method="post" action="<?php echo site_url('supervision/admin/candidate/save_session_form/'.$enc_form_id); ?>" id="add_form" enctype="multipart/form-data" autocomplete="off">
										<input type="hidden" name="mode" id="mode" value="<?php echo $mode; ?>">
                    <input type="hidden" name="form_id" id="form_id" value="<?php echo $form_id; ?>">
                    
                    <h4 class="custom_form_title" style="margin: -15px -20px 15px -20px !important;">Basic Details</h4>
                    
                    <div class="row">                      
                      <div class="col-xl-6 col-lg-6"><?php /* Agency Name */ ?>
                        <div class="form-group">
                          <label for="candidate_name" class="form_label"> Name <sup class="text-danger">*</sup></label>
                          <input readonly type="text" name="" id="candidate_name" value="<?php echo $candidate_data[0]['candidate_name'];?>" placeholder=" Name *" class="form-control custom_input allow_only_alphabets_and_space" maxlength="90" required/>
                          
                        </div>					
                      </div>
                      
                      <div class="col-xl-6 col-lg-6"><?php /* Establishment Year */ ?>
                        <div class="form-group">
                          <label for="email" class="form_label">Email <sup class="text-danger">*</sup></label>
                          <input readonly type="email" name="" id="email" value="<?php echo $candidate_data[0]['email']; ?>" placeholder="Email *" class="form-control custom_input" maxlength="160" readonly onchange="validate_input('email')"/>
                          
                   
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* Contact Person Mobile Number */ ?>
                        <div class="form-group">
                          <label for="mobile" class="form_label">Mobile Number <sup class="text-danger">*</sup></label>
                          <input readonly type="text" name="" id="mobile" value="<?php echo $candidate_data[0]['mobile']; ?>" placeholder="Contact Person Mobile Number *" class="form-control custom_input allow_only_numbers" required maxlength="10" minlength="10" />
                          
                        </div>					
                      </div>
                    
                      <div class="col-xl-6 col-lg-6"><?php /* Address line2 */ ?>
                        <div class="form-group">
                          <label for="bank" class="form_label">Bank <sup class="text-danger"></sup></label>
                          <input readonly type="text" name="" id="bank" placeholder="Bank" class="form-control custom_input" maxlength="75" value="<?php echo $candidate_data[0]['bank']; ?>" />

                        </div>					
                      </div>
                    
                      <div class="col-xl-6 col-lg-6"><?php /* Address line3 */ ?>
                        <div class="form-group">
                          <label for="branch" class="form_label">Branch <sup class="text-danger"></sup></label>
                          <input readonly type="text" name="" id="branch" placeholder="Branch" class="form-control custom_input" maxlength="75" value="<?php echo $candidate_data[0]['branch']; ?>" />
                          
                      
                        </div>					
                      </div>
                    
                      <div class="col-xl-6 col-lg-6"><?php /* Address line4 */ ?>
                        <div class="form-group">
                          <label for="designation" class="form_label">Designation<sup class="text-danger"></sup></label>
                          <input readonly type="text" name="" id="designation" placeholder="Designation" class="form-control custom_input" maxlength="75" value="<?php echo $candidate_data[0]['designation']; ?>" />
                         
                        </div>					
                      </div>
                    
                      <div class="col-xl-6 col-lg-6"><?php /* Address line4 */ ?>
                        <div class="form-group">
                          <label for="pdc_zone" class="form_label">PDC Zone<sup class="text-danger"></sup></label>
                          <input readonly type="text" name="" id="pdc_zone" placeholder="Designation" class="form-control custom_input" maxlength="75" value="<?php echo $candidate_data[0]['pdc_zone_name']; ?>" />
                          
                         
                        </div>					
                      </div>
                      <div class="col-xl-6 col-lg-6"><?php /* Address line4 */ ?>
                        <div class="form-group">
                          <label for="center" class="form_label">Center<sup class="text-danger"></sup></label>
                          <input readonly type="text" name="" id="center" placeholder="Center" class="form-control custom_input" maxlength="75" value="<?php echo $candidate_data[0]['center_name']; ?>" />
                          
                         
                        </div>					
                      </div>
                    
                    </div>
                    
                    <div class="hr-line-dashed"></div>										
									
                       
                    <h4 class="custom_form_title" style="margin: -15px -20px 15px -20px !important;">Supervision Details</h4>
                    
                    <div class="row">                      
                    
                      
                      <div class="col-xl-6 col-lg-6">
                        <div class="form-group">
                          <label for="exam_code" class="form_label"> Exam <sup class="text-danger">*</sup></label>
                          <select class="form-control chosen-select exam_code"  name="exam_code" id="exam_code"  required onchange="get_exam_venue_ajax(this.value); validate_input('exam_code'); ">
                          <option value="">Select</option>
                            <?php foreach($exams as $exam) {
                              ?>
                              <option <?php if($mode == "Add") { if(set_value('exam_code') == $exam['exam_code']) echo'selected';  } elseif($form_data[0]['exam_code']==$exam['exam_code']) {echo'selected';} ?> value="<?php echo $exam['exam_code'] ?>"><?php echo $exam['exam_name'] ?></option>
                              <?php
                            } ?>
                        </select>
                          
                          <?php if(form_error('exam_code')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_code'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      
                      
                      <div class="col-xl-6 col-lg-6"><?php /* Select exam venue */ ?>
                        <div class="form-group">
                          <label for="venue_code" class="form_label">Venue <sup class="text-danger">*</sup></label>
                          <div id="venue_outer">
                            <select class="form-control chosen-select venue_code" name="venue_code" id="venue_code" required onchange="get_exam_date_ajax(this.value);validate_input('venue_code'); ">
                              <?php $exam_code = '';
                                if($mode == "Add")
                                {
                                  if(set_value('exam_code') != "") { $exam_code = set_value('exam_code'); }
                                }
                                else { $exam_code = $form_data[0]['exam_code']; }
                                
                                if($exam_code != "")
                                {
                                  $this->db->group_by('venue_code');
                                  $venue_data = $this->master_model->getRecords('supervision_venue_master', array('exam_code' => $exam_code, 'venue_delete' => '0'), 'id, venue_name,venue_code,venue_name,venue_addr1,venue_addr2,venue_addr3,venue_addr4,venue_addr5', array('venue_name'=>'ASC'));
                                  
                                  if(count($venue_data) > 0)
                                  { ?>
                                  <option value="">Select</option>
                                  <?php foreach($venue_data as $venue)
                                    { ?>
                                    <option value="<?php echo $venue['venue_code']; ?>" <?php if($mode == "Add") { if(set_value('venue_code') == $venue['venue_code']) { echo "selected"; } } else { if($form_data[0]['venue_code'] == $venue['venue_code']) { echo "selected"; } } ?>><?php echo $venue['venue_name'].' '.$venue['venue_addr1'].' '.$venue['venue_addr2'].' '.$venue['venue_addr3'].' '.$venue['venue_addr4'].' '.$venue['venue_addr5']; ?></option>
                                    <?php }
                                  }
                                  else
                                  { ?>
                                  <option value="">No List Available</option>
                                  <?php }
                                }
                                else 
                                {
                                  echo '<option value="">Select</option>';
                                } ?>
                            </select>
                          </div>
                          <span id="venue_code_err"></span>
                          <?php if(form_error('venue_code')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('venue_code'); ?></label> <?php } ?>                            
                        </div>                       
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* Select exam date */ ?>
                        <div class="form-group">
                          <label for="exam_date" class="form_label">Exam Date <sup class="text-danger">*</sup></label>
                          <div id="exam_date_outer">
                            <select class="form-control chosen-select exam_date" name="exam_date" id="exam_date" required onchange="get_exam_time_ajax(this.value);validate_input('exam_date'); ">
                              <?php $exam_code = $venue_code='';
                                if($mode == "Add")
                                {
                                  if(set_value('exam_code') != "") { $exam_code = set_value('exam_code'); }
                                  if(set_value('venue_code') != "") { $venue_code = set_value('venue_code'); }
                                }
                                else { $exam_code = $form_data[0]['exam_code']; $venue_code = $form_data[0]['venue_code']; }
                                
                                if($exam_code != "" && $venue_code != "")
                                {
                                  $this->db->group_by('exam_date');
                                  $exam_dates_data = $this->master_model->getRecords('supervision_venue_master', array('exam_code' => $exam_code,'venue_code' => $venue_code, 'venue_delete' => '0'), 'id, exam_date', array('venue_name'=>'ASC'));
                                  
                                  if(count($exam_dates_data) > 0)
                                  { ?>
                                  <option value="">Select</option>
                                  <?php foreach($exam_dates_data as $exam_date)
                                    { ?>
                                    <option value="<?php echo $exam_date['exam_date']; ?>" <?php if($mode == "Add") { if(set_value('exam_date') == $exam_date['exam_date']) { echo "selected"; } } else { if($form_data[0]['exam_date'] == $exam_date['exam_date']) { echo "selected"; } } ?>><?php echo $exam_date['exam_date']; ?></option>
                                    <?php }
                                  }
                                  else
                                  { ?>
                                  <option value="">No List Available</option>
                                  <?php }
                                }
                                else 
                                {
                                  echo '<option value="">Select</option>';
                                } ?>
                            </select>
                          </div>
                          <span id="exam_date_err"></span>
                          <?php if(form_error('exam_date')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_date'); ?></label> <?php } ?>                            
                        </div>                       
                      </div>
                      
                      <div class="col-xl-6 col-lg-6"><?php /* Select exam time */ ?>
                        <div class="form-group">
                          <label for="exam_time" class="form_label">Exam Time <sup class="text-danger">*</sup></label>
                          <div id="exam_time_outer">
                            <select multiple class="exam_time form-control chosen-select" name="exam_time[]" id="exam_time" required onchange="validate_input('exam_time'); ">
                              <?php $exam_code = $venue_code='';
                                if($mode == "Add")
                                {
                                  if(set_value('exam_code') != "") { $exam_code = set_value('exam_code'); }
                                  if(set_value('venue_code') != "") { $venue_code = set_value('venue_code'); }
                                  if(set_value('exam_date') != "") { $exam_date = set_value('exam_date'); }
                                  $exam_time_arr = array();
                                }
                                else { $exam_code = $form_data[0]['exam_code']; $venue_code = $form_data[0]['venue_code']; $exam_date = $form_data[0]['exam_date'];
                                $exam_time_arr = explode(',',$form_data[0]['exam_time']); }
                                
                                if($exam_code != "" && $venue_code != "" && $exam_date != "")
                                {
                                  $this->db->group_by('session_time');
                                  $exam_times_data = $this->master_model->getRecords('supervision_venue_master', array('exam_code' => $exam_code,'venue_code' => $venue_code,'exam_date' => $exam_date, 'venue_delete' => '0'), 'id, session_time', array('id'=>'ASC'));
                                  
                                  if(count($exam_times_data) > 0)
                                  { 
                                    $i=1; ?>
                                  <option value="">Select</option>
                                  <?php foreach($exam_times_data as $exam_time)
                                    { ?>
                                    <option class="<?php echo $i++; ?>" value="<?php echo $exam_time['session_time']; ?>" <?php if($mode == "Add") { if(isset($_POST['exam_time']) && in_array($exam_time['session_time'],$_POST['exam_time'])) { echo "selected"; } } else { if(in_array($exam_time['session_time'],$exam_time_arr) ) { echo "selected"; } } ?>><?php echo $exam_time['session_time']; ?></option>
                                    <?php }
                                  }
                                  else
                                  { ?>
                                  <option value="">No List Available</option>
                                  <?php }
                                }
                                else 
                                {
                                  echo '<option value="">Select</option>';
                                } ?>
                            </select>
                          </div>
                          <span id="exam_time_err"></span>
                          <?php if(form_error('exam_time')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_time'); ?></label> <?php } ?>                            
                        </div>                       
                      </div>

                      
                      
                     
                        <div class="col-xl-6 col-lg-6"><?php /* suitable */ ?>
                          <div class="form-group">
                            <label for="suitable_venue_loc" class="form_label">1. Location of venue whether suitable and convenient <sup class="text-danger">*</sup></label>
                            <div id="suitable_venue_loc_err">
                              <label class="css_checkbox_radio radio_only"> Yes
                                <input type="radio" value="Yes" name="suitable_venue_loc" required <?php if($mode == "Add") { if(set_value('suitable_venue_loc') == 'Yes') { echo "checked"; } } else { if($form_data[0]['suitable_venue_loc']=='Yes') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              
                              <label class="css_checkbox_radio radio_only"> No
                                <input type="radio" value="No" name="suitable_venue_loc" required <?php if($mode == "Add") { if(set_value('suitable_venue_loc') == "" || set_value('suitable_venue_loc') == 'No') { echo "checked"; } } else { if($form_data[0]['suitable_venue_loc']=='No') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>
                            </div>
                            
                            <?php if(form_error('suitable_venue_loc')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('suitable_venue_loc'); ?></label> <?php } ?>
                          </div>					
                        </div>

                        <div class="col-xl-6 col-lg-6 "><?php /* Reason */ ?>
                        <div class="form-group suitable_venue_loc_reason_div" style="<?php if($mode == "Update" && isset($form_data) && $form_data[0]['suitable_venue_loc']=='Yes') { } else echo 'display:none'; ?>">
                          <label for="suitable_venue_loc_reason" class="form_label">Reason <sup class="text-danger"></sup></label>
                          <input type="text" name="suitable_venue_loc_reason"  id="suitable_venue_loc_reason" value="<?php if($mode == "Add") { echo set_value('suitable_venue_loc_reason'); } else { echo $form_data[0]['suitable_venue_loc_reason']; } ?>" placeholder="Reason" class="form-control custom_input suitable_venue_loc_reason"  maxlength="500"  onchange="validate_input('suitable_venue_loc_reason')"/>
                          
                          <?php if(form_error('suitable_venue_loc_reason')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('suitable_venue_loc_reason'); ?></label> <?php } ?>
                        </div>					
                      </div>

                        <div class="col-xl-6 col-lg-6"><?php /* opened */ ?>
                          <div class="form-group">
                            <label for="venue_open_bef_exam" class="form_label">2. Whether venue was opened before the examination time  <sup class="text-danger">*</sup></label>
                            <div id="venue_open_bef_exam_err">
                              <label class="css_checkbox_radio radio_only"> Yes
                                <input type="radio" value="Yes" name="venue_open_bef_exam" required <?php if($mode == "Add") { if(set_value('venue_open_bef_exam') == 'Yes') { echo "checked"; } } else { if($form_data[0]['venue_open_bef_exam']=='Yes') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              
                              <label class="css_checkbox_radio radio_only"> No
                                <input type="radio" value="No" name="venue_open_bef_exam" required <?php if($mode == "Add") { if(set_value('venue_open_bef_exam') == "" || set_value('venue_open_bef_exam') == 'No') { echo "checked"; } } else { if($form_data[0]['venue_open_bef_exam']=='No') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>
                            </div>
                            
                            <?php if(form_error('venue_open_bef_exam')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('venue_open_bef_exam'); ?></label> <?php } ?>
                          </div>					
                        </div>

                        <div class="col-xl-6 col-lg-6"><?php /* Reason */ ?>
                        <div class="form-group venue_open_bef_exam_reason_div" style="<?php if($mode == "Update" && isset($form_data) && $form_data[0]['venue_open_bef_exam']=='Yes') { } else echo 'display:none'; ?>">
                          <label for="venue_open_bef_exam_reason" class="form_label">Reason <sup class="text-danger"></sup></label>
                          <input type="text" name="venue_open_bef_exam_reason"  id="venue_open_bef_exam_reason" value="<?php if($mode == "Add") { echo set_value('venue_open_bef_exam_reason'); } else { echo $form_data[0]['venue_open_bef_exam_reason']; } ?>" placeholder="Reason" class="form-control custom_input venue_open_bef_exam_reason" maxlength="500"  onchange="validate_input('venue_open_bef_exam_reason')"/>
                          
                          <?php if(form_error('venue_open_bef_exam_reason')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('venue_open_bef_exam_reason'); ?></label> <?php } ?>
                        </div>					
                      </div>


                      <div class="col-xl-12 col-lg-12"><?php /*Number Of PC */ ?>
                        <div class="form-group">
                          <label for="no_of_pc" class="form_label">3. Number Of PCs in the venue <sup class="text-danger">*</sup></label>
                          <input type="text" name="no_of_pc" required id="no_of_pc" value="<?php if($mode == "Add") { echo set_value('no_of_pc'); } else { echo $form_data[0]['no_of_pc']; } ?>" placeholder="Number Of PCs in the venue *" class="form-control custom_input" maxlength="4"  onchange="validate_input('no_of_pc')"/>
                          
                          <?php if(form_error('no_of_pc')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('no_of_pc'); ?></label> <?php } ?>
                        </div>					
                      </div>


                        <div class="col-xl-6 col-lg-6"><?php /* reserved */ ?>
                          <div class="form-group">
                            <label for="venue_reserved" class="form_label">4. Whether the venue was exclusively reserved for IIBF <sup class="text-danger">*</sup></label>
                            <div id="venue_reserved">
                              <label class="css_checkbox_radio radio_only"> Yes
                                <input type="radio" value="Yes" name="venue_reserved" required <?php if($mode == "Add") { if(set_value('venue_reserved') == 'Yes') { echo "checked"; } } else { if($form_data[0]['venue_reserved']=='Yes') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              
                              <label class="css_checkbox_radio radio_only"> No
                                <input type="radio" value="No" name="venue_reserved" required <?php if($mode == "Add") { if(set_value('venue_reserved') == "" || set_value('venue_reserved') == 'No') { echo "checked"; } } else { if($form_data[0]['venue_reserved']=='No') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>
                            </div>
                            
                            <?php if(form_error('venue_reserved')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('venue_reserved'); ?></label> <?php } ?>
                          </div>					
                        </div>

                        <div class="col-xl-6 col-lg-6"><?php /* Reason */ ?>
                          <div class="form-group venue_reserved_reason_div" style="<?php if($mode == "Update" && isset($form_data) && $form_data[0]['venue_reserved']=='Yes') { } else echo 'display:none'; ?>">
                            <label for="venue_reserved_reason" class="form_label">Reason <sup class="text-danger"></sup></label>
                            <input type="text" name="venue_reserved_reason"  id="venue_reserved_reason" value="<?php if($mode == "Add") { echo set_value('venue_reserved_reason'); } else { echo $form_data[0]['venue_reserved_reason']; } ?>" placeholder="Reason" class="form-control custom_input venue_reserved_reason" maxlength="500"  onchange="validate_input('venue_reserved_reason')"/>
                            
                            <?php if(form_error('venue_reserved_reason')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('venue_reserved_reason'); ?></label> <?php } ?>
                        </div>					
                      </div>
                    
                      <div class="col-xl-12 col-lg-12"><?php /* power problem */ ?>
                          <div class="form-group">
                            <label for="venue_power_problem" class="form_label">5. Was there a power problem in venue (if yes please explain alternate arrangement made and the duration of power problem)<sup class="text-danger">*</sup></label>
                            <div id="venue_power_problem">
                              <label class="css_checkbox_radio radio_only"> Yes
                                <input type="radio" value="Yes" name="venue_power_problem" required <?php if($mode == "Add") { if( set_value('venue_power_problem') == 'Yes') { echo "checked"; } } else { if($form_data[0]['venue_power_problem']=='Yes') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              
                              <label class="css_checkbox_radio radio_only"> No
                                <input type="radio" value="No" name="venue_power_problem" required <?php if($mode == "Add") { if(set_value('venue_power_problem') == "" || set_value('venue_power_problem') == 'No') { echo "checked"; } } else { if($form_data[0]['venue_power_problem']=='No') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>
                            </div>
                            
                            <?php if(form_error('venue_power_problem')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('venue_power_problem'); ?></label> <?php } ?>
                          </div>					
                        </div>

                        <div class="col-xl-12 col-lg-12"><?php /* sol */ ?>
                          <div class="form-group venue_power_problem_sol_div" style="<?php if($mode == "Update" && isset($form_data) && $form_data[0]['venue_power_problem']=='Yes') { } else echo 'display:none'; ?>">
                            <label for="venue_power_problem_sol" class="form_label">(if yes please explain alternate arrangement made and the duration of power problem) <sup class="text-danger"></sup></label>
                            <input type="text" name="venue_power_problem_sol"  id="venue_power_problem_sol" value="<?php if($mode == "Add") { echo set_value('venue_power_problem_sol'); } else { echo $form_data[0]['venue_power_problem_sol']; } ?>" placeholder=" alternate arrangement made  and the duration of power problem" class="venue_power_problem_sol form-control custom_input" maxlength="255"  onchange="validate_input('venue_power_problem_sol')"/>
                            
                            <?php if(form_error('venue_power_problem_sol')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('venue_power_problem_sol'); ?></label> <?php } ?>
                        </div>					
                      </div>
                    
                      <div class="col-xl-12 col-lg-12"><?php /* sol */ ?>
                          <div class="form-group">
                            <label for="no_of_supervisors" class="form_label">6. Number of test supervisors in the venue (give details room/lasb wise) <sup class="text-danger">*</sup></label>
                            <input required type="text" name="no_of_supervisors"  id="no_of_supervisors" value="<?php if($mode == "Add") { echo set_value('no_of_supervisors'); } else { echo $form_data[0]['no_of_supervisors']; } ?>" placeholder="Number of test supervisors in the venue *" class="form-control custom_input" maxlength="255"  onchange="validate_input('no_of_supervisors')"/>
                            
                            <?php if(form_error('no_of_supervisors')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('no_of_supervisors'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* registration */ ?>
                          <div class="form-group">
                            <label for="registration_process" class="form_label">7. Whether registration process was completed before the examination time <sup class="text-danger">*</sup></label>
                            <div id="registration_process">
                              <label class="css_checkbox_radio radio_only"> Yes
                                <input type="radio" value="Yes" name="registration_process" required <?php if($mode == "Add") { if(set_value('registration_process') == 'Yes') { echo "checked"; } } else { if($form_data[0]['registration_process']=='Yes') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              
                              <label class="css_checkbox_radio radio_only"> No
                                <input type="radio" value="No" name="registration_process" required <?php if($mode == "Add") { if(set_value('registration_process') == "" || set_value('registration_process') == 'No') { echo "checked"; } } else { if($form_data[0]['registration_process']=='No') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>
                            </div>
                            
                            <?php if(form_error('registration_process')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('registration_process'); ?></label> <?php } ?>
                          </div>					
                        </div>

                        <div class="col-xl-6 col-lg-6"><?php /* Reason */ ?>
                          <div class="form-group registration_process_reason_div" style="<?php if($mode == "Update" && isset($form_data) && $form_data[0]['registration_process']=='Yes') { } else echo 'display:none'; ?>">
                            <label for="registration_process_reason" class="form_label">Reason <sup class="text-danger"></sup></label>
                            <input type="text" name="registration_process_reason"  id="registration_process_reason" value="<?php if($mode == "Add") { echo set_value('registration_process_reason'); } else { echo $form_data[0]['registration_process_reason']; } ?>" placeholder="Reason" class="form-control custom_input registration_process_reason" maxlength="500"  onchange="validate_input('registration_process_reason')"/>
                            
                            <?php if(form_error('registration_process_reason')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('registration_process_reason'); ?></label> <?php } ?>
                        </div>					
                      </div>
                    
                      <div class="col-xl-6 col-lg-6"><?php /* frisking */ ?>
                          <div class="form-group">
                            <label for="frisking" class="form_label">8. Whether frisking was done before the candidate were allowed to enter in computer lab? (To ensure that candidate do not carry mobile, any electronic gadgets, papers, chits etc.) <sup class="text-danger">*</sup></label>
                            <div id="frisking">
                              <label class="css_checkbox_radio radio_only"> Yes
                                <input type="radio" value="Yes" name="frisking" required <?php if($mode == "Add") { if( set_value('frisking') == 'Yes') { echo "checked"; } } else { if($form_data[0]['frisking']=='Yes') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              
                              <label class="css_checkbox_radio radio_only"> No
                                <input type="radio" value="No" name="frisking" required <?php if($mode == "Add") { if(set_value('frisking') == "" || set_value('frisking') == 'No') { echo "checked"; } } else { if($form_data[0]['frisking']=='No') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>
                            </div>
                            
                            <?php if(form_error('frisking')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('frisking'); ?></label> <?php } ?>
                          </div>					
                        </div>

                        <div class="col-xl-6 col-lg-6"><?php /* Reason */ ?>
                          <div class="form-group frisking_reason_div" style="<?php if($mode == "Update" && isset($form_data) && $form_data[0]['frisking']=='Yes') { } else echo 'display:none'; ?>">
                            <label for="frisking_reason" class="form_label">Reason <sup class="text-danger"></sup></label>
                            <input type="text" name="frisking_reason"  id="frisking_reason" value="<?php if($mode == "Add") { echo set_value('frisking_reason'); } else { echo $form_data[0]['frisking_reason']; } ?>" placeholder="Reason" class="form-control custom_input frisking_reason" maxlength="500"  onchange="validate_input('frisking_reason')"/>
                            
                            <?php if(form_error('frisking_reason')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('frisking_reason'); ?></label> <?php } ?>
                        </div>					
                      </div>
                    
                      <div class="col-xl-6 col-lg-6"><?php /* frisking lady */ ?>
                          <div class="form-group">
                            <label for="frisking_lady" class="form_label">9. Whether lady frisking staff was available for frisking the lady candidates <sup class="text-danger">*</sup></label>
                            <div id="frisking">
                              <label class="css_checkbox_radio radio_only"> Yes
                                <input type="radio" value="Yes" name="frisking_lady" required <?php if($mode == "Add") { if( set_value('frisking_lady') == 'Yes') { echo "checked"; } } else { if($form_data[0]['frisking_lady']=='Yes') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              
                              <label class="css_checkbox_radio radio_only"> No
                                <input type="radio" value="No" name="frisking_lady" required <?php if($mode == "Add") { if(set_value('frisking_lady') == "" || set_value('frisking_lady') == 'No') { echo "checked"; } } else { if($form_data[0]['frisking_lady']=='No') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>
                            </div>
                            
                            <?php if(form_error('frisking_lady')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('frisking_lady'); ?></label> <?php } ?>
                          </div>					
                        </div>

                        <div class="col-xl-6 col-lg-6"><?php /* Reason */ ?>
                          <div class="form-group frisking_lady_reason_div"  style="<?php if($mode == "Update" && isset($form_data) && $form_data[0]['frisking_lady']=='Yes') { } else echo 'display:none'; ?>">
                            <label for="frisking_lady_reason" class="form_label">Reason <sup class="text-danger"></sup></label>
                            <input type="text" name="frisking_lady_reason"  id="frisking_lady_reason" value="<?php if($mode == "Add") { echo set_value('frisking_lady_reason'); } else { echo $form_data[0]['frisking_lady_reason']; } ?>" placeholder="Reason" class="form-control custom_input frisking_lady_reason" maxlength="500"  onchange="validate_input('frisking_lady_reason')"/>
                            
                            <?php if(form_error('frisking_lady_reason')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('frisking_lady_reason'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      <div class="col-xl-6 col-lg-6"><?php /* mobile_allowed */ ?>
                          <div class="form-group">
                            <label for="mobile_allowed" class="form_label">10. Whether mobile phone,text materials etc. were allowed in venue <sup class="text-danger">*</sup></label>
                            <div id="mobile_allowed">
                              <label class="css_checkbox_radio radio_only"> Yes
                                <input type="radio" value="Yes" name="mobile_allowed" required <?php if($mode == "Add") { if(set_value('mobile_allowed') == 'Yes') { echo "checked"; } } else { if($form_data[0]['mobile_allowed']=='Yes') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              
                              <label class="css_checkbox_radio radio_only"> No
                                <input type="radio" value="No" name="mobile_allowed" required <?php if($mode == "Add") { if(set_value('mobile_allowed') == "" ||  set_value('mobile_allowed') == 'No') { echo "checked"; } } else { if($form_data[0]['mobile_allowed']=='No') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>
                            </div>
                            
                            <?php if(form_error('mobile_allowed')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('mobile_allowed'); ?></label> <?php } ?>
                          </div>					
                        </div>

                        <div class="col-xl-6 col-lg-6"><?php /* Reason */ ?>
                          <div class="form-group mobile_allowed_reason_div"  style="<?php if($mode == "Update" && isset($form_data) && $form_data[0]['mobile_allowed']=='Yes') { } else echo 'display:none'; ?>">
                            <label for="mobile_allowed_reason" class="form_label">Reason <sup class="text-danger"></sup></label>
                            <input type="text" name="mobile_allowed_reason"  id="mobile_allowed_reason" value="<?php if($mode == "Add") { echo set_value('mobile_allowed_reason'); } else { echo $form_data[0]['mobile_allowed_reason']; } ?>" placeholder="Reason" class="form-control custom_input mobile_allowed_reason" maxlength="500"  onchange="validate_input('mobile_allowed_reason')"/>
                            
                            <?php if(form_error('mobile_allowed_reason')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('mobile_allowed_reason'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* admit_letter_checked */ ?>
                          <div class="form-group">
                            <label for="admit_letter_checked" class="form_label">11. Whether candidate admit letter was checked and verified before permitting to sit for examination be the supervisors <sup class="text-danger">*</sup></label>
                            <div id="admit_letter_checked">
                              <label class="css_checkbox_radio radio_only"> Yes
                                <input type="radio" value="Yes" name="admit_letter_checked" required <?php if($mode == "Add") { if( set_value('admit_letter_checked') == 'Yes') { echo "checked"; } } else { if($form_data[0]['admit_letter_checked']=='Yes') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              
                              <label class="css_checkbox_radio radio_only"> No
                                <input type="radio" value="No" name="admit_letter_checked" required <?php if($mode == "Add") { if(set_value('admit_letter_checked') == "" || set_value('admit_letter_checked') == 'No') { echo "checked"; } } else { if($form_data[0]['admit_letter_checked']=='No') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>
                            </div>
                            
                            <?php if(form_error('admit_letter_checked')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('admit_letter_checked'); ?></label> <?php } ?>
                          </div>					
                        </div>

                        <div class="col-xl-6 col-lg-6"><?php /* Reason */ ?>
                          <div class="form-group admit_letter_checked_reason_div"  style="<?php if($mode == "Update" && isset($form_data) && $form_data[0]['admit_letter_checked']=='Yes') { } else echo 'display:none'; ?>">
                            <label for="admit_letter_checked_reason" class="form_label">Reason <sup class="text-danger"></sup></label>
                            <input type="text" name="admit_letter_checked_reason"  id="admit_letter_checked_reason" value="<?php if($mode == "Add") { echo set_value('admit_letter_checked_reason'); } else { echo $form_data[0]['admit_letter_checked_reason']; } ?>" placeholder="Reason" class="form-control custom_input admit_letter_checked_reason" maxlength="500"  onchange="validate_input('admit_letter_checked_reason')"/>
                            
                            <?php if(form_error('admit_letter_checked_reason')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('admit_letter_checked_reason'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* exam_without_admit_letter */ ?>
                          <div class="form-group">
                            <label for="exam_without_admit_letter" class="form_label">12. Whether any candidate were permitted to appear for the examination without proper admit letter and ID card (give details) <sup class="text-danger">*</sup></label>
                            <div id="exam_without_admit_letter">
                              <label class="css_checkbox_radio radio_only"> Yes
                                <input type="radio" value="Yes" name="exam_without_admit_letter" required <?php if($mode == "Add") { if(set_value('exam_without_admit_letter') == 'Yes') { echo "checked"; } } else { if($form_data[0]['exam_without_admit_letter']=='Yes') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              
                              <label class="css_checkbox_radio radio_only"> No
                                <input type="radio" value="No" name="exam_without_admit_letter" required <?php if($mode == "Add") { if(set_value('exam_without_admit_letter') == "" || set_value('exam_without_admit_letter') == 'No') { echo "checked"; } } else { if($form_data[0]['exam_without_admit_letter']=='No') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>
                            </div>
                            
                            <?php if(form_error('exam_without_admit_letter')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_without_admit_letter'); ?></label> <?php } ?>
                          </div>					
                        </div>

                        <div class="col-xl-6 col-lg-6"><?php /* Give Details */ ?>
                          <div class="form-group exam_without_admit_letter_detils_div"  style="<?php if($mode == "Update" && isset($form_data) && $form_data[0]['exam_without_admit_letter']=='Yes') { } else echo 'display:none'; ?>">
                            <label for="exam_without_admit_letter_detils" class="form_label">Give Details <sup class="text-danger"></sup></label>
                            <input type="text" name="exam_without_admit_letter_detils"  id="exam_without_admit_letter_detils" value="<?php if($mode == "Add") { echo set_value('exam_without_admit_letter_detils'); } else { echo $form_data[0]['exam_without_admit_letter_detils']; } ?>" placeholder="Give Details" class="form-control custom_input exam_without_admit_letter_detils" maxlength="500"  onchange="validate_input('exam_without_admit_letter_detils')"/>
                            
                            <?php if(form_error('exam_without_admit_letter_detils')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_without_admit_letter_detils'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* seat_no_written */ ?>
                          <div class="form-group">
                            <label for="seat_no_written" class="form_label">13. Whether seat numbers were written againts each PC <sup class="text-danger">*</sup></label>
                            <div id="seat_no_written">
                              <label class="css_checkbox_radio radio_only"> Yes
                                <input type="radio" value="Yes" name="seat_no_written" required <?php if($mode == "Add") { if(set_value('seat_no_written') == 'Yes') { echo "checked"; } } else { if($form_data[0]['seat_no_written']=='Yes') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              
                              <label class="css_checkbox_radio radio_only"> No
                                <input type="radio" value="No" name="seat_no_written" required <?php if($mode == "Add") { if(set_value('seat_no_written') == "" || set_value('seat_no_written') == 'No') { echo "checked"; } } else { if($form_data[0]['seat_no_written']=='No') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>
                            </div>
                            
                            <?php if(form_error('seat_no_written')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('seat_no_written'); ?></label> <?php } ?>
                          </div>					
                        </div>

                        <div class="col-xl-6 col-lg-6"><?php /* Give Details */ ?>
                          <div class="form-group seat_no_written_reason_div"  style="<?php if($mode == "Update" && isset($form_data) && $form_data[0]['seat_no_written']=='Yes') { } else echo 'display:none'; ?>">
                            <label for="seat_no_written_reason" class="form_label">Reason <sup class="text-danger"></sup></label>
                            <input type="text" name="seat_no_written_reason"  id="seat_no_written_reason" value="<?php if($mode == "Add") { echo set_value('seat_no_written_reason'); } else { echo $form_data[0]['seat_no_written_reason']; } ?>" placeholder="Reason" class="form-control custom_input seat_no_written_reason" maxlength="500"  onchange="validate_input('seat_no_written_reason')"/>
                            
                            <?php if(form_error('seat_no_written_reason')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('seat_no_written_reason'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      <div class="col-xl-6 col-lg-6"><?php /* candidate_seated */ ?>
                          <div class="form-group">
                            <label for="candidate_seated" class="form_label">14. Whether candidates were seated in the seat number mentioned in the admit letter <sup class="text-danger">*</sup></label>
                            <div id="candidate_seated">
                              <label class="css_checkbox_radio radio_only"> Yes
                                <input type="radio" value="Yes" name="candidate_seated" required <?php if($mode == "Add") { if(set_value('candidate_seated') == 'Yes') { echo "checked"; } } else { if($form_data[0]['candidate_seated']=='Yes') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              
                              <label class="css_checkbox_radio radio_only"> No
                                <input type="radio" value="No" name="candidate_seated" required <?php if($mode == "Add") { if(set_value('candidate_seated') == "" || set_value('candidate_seated') == 'No') { echo "checked"; } } else { if($form_data[0]['candidate_seated']=='No') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>
                            </div>
                            
                            <?php if(form_error('candidate_seated')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('candidate_seated'); ?></label> <?php } ?>
                          </div>					
                        </div>

                        <div class="col-xl-6 col-lg-6"><?php /* reason */ ?>
                          <div class="form-group candidate_seated_reason_div" style="<?php if($mode == "Update" && isset($form_data) && $form_data[0]['candidate_seated']=='Yes') { } else echo 'display:none'; ?>">
                            <label for="candidate_seated_reason" class="form_label">Reason <sup class="text-danger"></sup></label>
                            <input type="text" name="candidate_seated_reason"  id="candidate_seated_reason" value="<?php if($mode == "Add") { echo set_value('candidate_seated_reason'); } else { echo $form_data[0]['candidate_seated_reason']; } ?>" placeholder="Reason" class="form-control custom_input candidate_seated_reason" maxlength="500"  onchange="validate_input('candidate_seated_reason')"/>
                            
                            <?php if(form_error('candidate_seated_reason')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('candidate_seated_reason'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-12 col-lg-12"><?php /* scribe_arrange */ ?>
                          <div class="form-group">
                            <label for="scribe_arrange" class="form_label">15. Whether separate arrangments was made available for PWD(Person with Disabilities) candidates using scribe <sup class="text-danger">*</sup></label>
                            <div id="scribe_arrange">
                              <label class="css_checkbox_radio radio_only"> Yes
                                <input type="radio" value="Yes" name="scribe_arrange" required <?php if($mode == "Add") { if(set_value('scribe_arrange') == 'Yes') { echo "checked"; } } else { if($form_data[0]['scribe_arrange']=='Yes') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              
                              <label class="css_checkbox_radio radio_only"> No
                                <input type="radio" value="No" name="scribe_arrange" required <?php if($mode == "Add") { if(set_value('scribe_arrange') == "" || set_value('scribe_arrange') == 'No') { echo "checked"; } } else { if($form_data[0]['scribe_arrange']=='No') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>
                            </div>
                            
                            <?php if(form_error('scribe_arrange')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('scribe_arrange'); ?></label> <?php } ?>
                          </div>					
                        </div>

                        <div class="col-xl-12 col-lg-12"><?php /* reason */ ?>
                          <div class="form-group scribe_arrange_reason_div" style="<?php if($mode == "Update" && isset($form_data) && $form_data[0]['scribe_arrange']=='Yes') { } else echo 'display:none'; ?>">
                            <label for="scribe_arrange_reason" class="form_label">Reason <sup class="text-danger"></sup></label>
                            <input type="text" name="scribe_arrange_reason"  id="scribe_arrange_reason" value="<?php if($mode == "Add") { echo set_value('scribe_arrange_reason'); } else { echo $form_data[0]['scribe_arrange_reason']; } ?>" placeholder="Reason" class="form-control custom_input scribe_arrange_reason" maxlength="500"  onchange="validate_input('scribe_arrange_reason')"/>
                            
                            <?php if(form_error('scribe_arrange_reason')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('scribe_arrange_reason'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      
                      <div class="col-xl-12 col-lg-12"><?php /* announcement */ ?>
                          <div class="form-group">
                            <label for="announcement" class="form_label">16. Whether rules of examination are announced to the candidates by the Invigilators (mention if you feel any gap in announcement)<sup class="text-danger">*</sup></label>
                            <div id="announcement">
                              <label class="css_checkbox_radio radio_only"> Yes
                                <input type="radio" value="Yes" name="announcement" required <?php if($mode == "Add") { if( set_value('announcement') == 'Yes') { echo "checked"; } } else { if($form_data[0]['announcement']=='Yes') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              
                              <label class="css_checkbox_radio radio_only"> No
                                <input type="radio" value="No" name="announcement" required <?php if($mode == "Add") { if(set_value('announcement') == "" || set_value('announcement') == 'No') { echo "checked"; } } else { if($form_data[0]['announcement']=='No') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>
                            </div>
                            
                            <?php if(form_error('announcement')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('announcement'); ?></label> <?php } ?>
                          </div>					
                        </div>

                        <div class="col-xl-12 col-lg-12"><?php /* reason */ ?>
                          <div class="form-group announcement_gap_div" ><!--style="<?php if($mode == "Update" && isset($form_data) && $form_data[0]['announcement']=='Yes') { } else echo 'display:none'; ?>" -->
                            <label for="announcement_gap" class="form_label">Mention if you feel any gap in announcement <sup class="text-danger"></sup></label>
                            <input type="text" name="announcement_gap"  id="announcement_gap" value="<?php if($mode == "Add") { echo set_value('announcement_gap'); } else { echo $form_data[0]['announcement_gap']; } ?>" placeholder="Mention if you feel any gap in announcement" class="form-control custom_input announcement_gap" maxlength="500"  onchange="validate_input('announcement_gap')"/>
                            
                            <?php if(form_error('announcement_gap')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('announcement_gap'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      <div class="col-xl-6 col-lg-6"><?php /* exam_started */ ?>
                          <div class="form-group">
                            <label for="exam_started" class="form_label">17. Whether examination started as scheduled<sup class="text-danger">*</sup></label>
                            <div id="exam_started">
                              <label class="css_checkbox_radio radio_only"> Yes
                                <input type="radio" value="Yes" name="exam_started" required <?php if($mode == "Add") { if(set_value('exam_started') == 'Yes') { echo "checked"; } } else { if($form_data[0]['exam_started']=='Yes') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              
                              <label class="css_checkbox_radio radio_only"> No
                                <input type="radio" value="No" name="exam_started" required <?php if($mode == "Add") { if(set_value('exam_started') == "" || set_value('exam_started') == 'No') { echo "checked"; } } else { if($form_data[0]['exam_started']=='No') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>
                            </div>
                            
                            <?php if(form_error('exam_started')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_started'); ?></label> <?php } ?>
                          </div>					
                        </div>

                        <div class="col-xl-6 col-lg-6"><?php /* reason */ ?>
                          <div class="form-group exam_started_reason_div" style="<?php if($mode == "Update" && isset($form_data) && $form_data[0]['exam_started']=='Yes') { } else echo 'display:none'; ?>">
                            <label for="exam_started_reason" class="form_label">Reason <sup class="text-danger"></sup></label>
                            <input type="text" name="exam_started_reason"  id="exam_started_reason" value="<?php if($mode == "Add") { echo set_value('exam_started_reason'); } else { echo $form_data[0]['exam_started_reason']; } ?>" placeholder="Reason" class="form-control custom_input exam_started_reason" maxlength="500"  onchange="validate_input('exam_started_reason')"/>
                            
                            <?php if(form_error('exam_started_reason')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_started_reason'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      
                      <div class="col-xl-12 col-lg-12"><?php /* candidate_appeared */ ?>
                       
                       <div class="form-group candidate_appeared1 candidate_appeared_div">
                         <label for="candidate_appeared1" class="form_label">18. No. of candidates appeared at session <span class="session_time_text"></span> <sup class="text-danger">*</sup></label>
                           <input type="text" name="candidate_appeared1" required id="candidate_appeared1" value="<?php if($mode == "Add") { echo set_value('candidate_appeared1'); } else { echo $form_data[0]['candidate_appeared1']; } ?>" placeholder="No. of candidates appeared session wise" class="form-control custom_input candidate_appeared1 candidate_appeared1" maxlength="100"  onchange="validate_input('candidate_appeared1')"/>
                         
                           <?php if(form_error('candidate_appeared')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('candidate_appeared'); ?></label> <?php } ?>
                       </div>	
                       <div class="form-group candidate_appeared2 candidate_appeared_div" <?php if($mode == "Add" && isset($_POST['candidate_appeared2']) && $_POST['candidate_appeared2']!='') {                          } 
                       else if($mode == "Update" && isset($_POST['candidate_appeared2']) && $_POST['candidate_appeared2']!='') { }
                       else if($mode == "Update" && $form_data[0]['candidate_appeared2']!='') { }
                       else echo 'style="display:none;" ' ?>>
                          <label for="candidate_appeared1" class="form_label">18. No. of candidates appeared at session <span class="session_time_text"></span> <sup class="text-danger">*</sup></label>
                           <input type="text" name="candidate_appeared2"  id="candidate_appeared2" value="<?php if($mode == "Add") { echo set_value('candidate_appeared2'); } else { echo $form_data[0]['candidate_appeared2']; } ?>" placeholder="No. of candidates appeared session wise" class="form-control custom_input candidate_appeared2" maxlength="100"  onchange="validate_input('candidate_appeared2')"/>
                         
                           <?php if(form_error('candidate_appeared2')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('candidate_appeared2'); ?></label> <?php } ?>
                       </div>	
                       <div class="form-group candidate_appeared3 candidate_appeared_div" <?php if($mode == "Add" && isset($_POST['candidate_appeared3']) && $_POST['candidate_appeared3']!='') {                          } 
                       else if($mode == "Update" && isset($_POST['candidate_appeared3']) && $_POST['candidate_appeared3']!='') { }
                       else if($mode == "Update" && $form_data[0]['candidate_appeared3']!='') { }
                       else echo 'style="display:none;" ' ?>>
                          <label for="candidate_appeared1" class="form_label">18. No. of candidates appeared at session <span class="session_time_text"></span> <sup class="text-danger">*</sup></label>
                           <input type="text" name="candidate_appeared3"  id="candidate_appeared3" value="<?php if($mode == "Add") { echo set_value('candidate_appeared3'); } else { echo $form_data[0]['candidate_appeared3']; } ?>" placeholder="No. of candidates appeared session wise" class="form-control custom_input candidate_appeared3" maxlength="100"  onchange="validate_input('candidate_appeared3')"/>
                         
                           <?php if(form_error('candidate_appeared3')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('candidate_appeared3'); ?></label> <?php } ?>
                       </div>				
                     </div>

                        <div class="col-xl-6 col-lg-6"><?php /* started_late */ ?>
                          <div class="form-group">
                            <label for="started_late" class="form_label">19. Whether any candidate were allowed to start the examination after 15 minutes of scheduled examination<sup class="text-danger">*</sup></label>
                            <div id="started_late">
                              <label class="css_checkbox_radio radio_only"> Yes
                                <input type="radio" value="Yes" name="started_late" required <?php if($mode == "Add") { if( set_value('started_late') == 'Yes') { echo "checked"; } } else { if($form_data[0]['started_late']=='Yes') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              
                              <label class="css_checkbox_radio radio_only"> No
                                <input type="radio" value="No" name="started_late" required <?php if($mode == "Add") { if(set_value('started_late') == "" || set_value('started_late') == 'No') { echo "checked"; } } else { if($form_data[0]['started_late']=='No') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>
                            </div>
                            
                            <?php if(form_error('started_late')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('started_late'); ?></label> <?php } ?>
                          </div>					
                        </div>

                        <div class="col-xl-6 col-lg-6"><?php /* reason */ ?>
                          <div class="form-group started_late_reason_div" style="<?php if($mode == "Update" && isset($form_data) && $form_data[0]['started_late']=='Yes') { } else echo 'display:none'; ?>">
                            <label for="started_late_reason" class="form_label">Reason <sup class="text-danger"></sup></label>
                            <input type="text" name="started_late_reason"  id="started_late_reason" value="<?php if($mode == "Add") { echo set_value('started_late_reason'); } else { echo $form_data[0]['started_late_reason']; } ?>" placeholder="Reason" class="form-control custom_input started_late_reason" maxlength="500"  onchange="validate_input('started_late_reason')"/>
                            
                            <?php if(form_error('started_late_reason')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('started_late_reason'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      <div class="col-xl-12 col-lg-12"><?php /* unfair_candidates*/ ?>
                          <div class="form-group">
                            <label for="unfair_candidates" class="form_label">20. Was any unfair means was adopted by the candidates during the examination(pls explain, give details of candidates)<sup class="text-danger">*</sup></label>
                            <div id="unfair_candidates">
                              <label class="css_checkbox_radio radio_only"> Yes
                                <input type="radio" value="Yes" name="unfair_candidates" required <?php if($mode == "Add") { if( set_value('unfair_candidates') == 'Yes') { echo "checked"; } } else { if($form_data[0]['unfair_candidates']=='Yes') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              
                              <label class="css_checkbox_radio radio_only"> No
                                <input type="radio" value="No" name="unfair_candidates" required <?php if($mode == "Add") { if(set_value('unfair_candidates') == "" || set_value('unfair_candidates') == 'No') { echo "checked"; } } else { if($form_data[0]['unfair_candidates']=='No') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>
                            </div>
                            
                            <?php if(form_error('unfair_candidates')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('unfair_candidates'); ?></label> <?php } ?>
                          </div>					
                        </div>

                        <div class="col-xl-12 col-lg-12"><?php /* pls explain, give details of candidates */ ?>
                          <div class="form-group unfair_candidates_reason_div" style="<?php if($mode == "Update" && isset($form_data) && $form_data[0]['unfair_candidates']=='Yes') { } else echo 'display:none'; ?>">
                            <label for="unfair_candidates_reason" class="form_label">pls explain, give details of candidates <sup class="text-danger"></sup></label>
                            <input type="text" name="unfair_candidates_reason"  id="unfair_candidates_reason" value="<?php if($mode == "Add") { echo set_value('unfair_candidates_reason'); } else { echo $form_data[0]['unfair_candidates_reason']; } ?>" placeholder="pls explain, give details of candidates" class="form-control custom_input unfair_candidates_reason" maxlength="500"  onchange="validate_input('unfair_candidates_reason')"/>
                            
                            <?php if(form_error('unfair_candidates_reason')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('unfair_candidates_reason'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      <div class="col-xl-6 col-lg-6"><?php /* rough_sheet */ ?>
                          <div class="form-group">
                            <label for="rough_sheet" class="form_label">21. Rough sheet given to candidates were collected back and destroyed.<sup class="text-danger">*</sup></label>
                            <div id="rough_sheet">
                              <label class="css_checkbox_radio radio_only"> Yes
                                <input type="radio" value="Yes" name="rough_sheet" required <?php if($mode == "Add") { if(set_value('rough_sheet') == 'Yes') { echo "checked"; } } else { if($form_data[0]['rough_sheet']=='Yes') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              
                              <label class="css_checkbox_radio radio_only"> No
                                <input type="radio" value="No" name="rough_sheet" required <?php if($mode == "Add") { if(set_value('rough_sheet') == "" ||  set_value('rough_sheet') == 'No') { echo "checked"; } } else { if($form_data[0]['rough_sheet']=='No') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>
                            </div>
                            
                            <?php if(form_error('rough_sheet')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('rough_sheet'); ?></label> <?php } ?>
                          </div>					
                        </div>

                        <div class="col-xl-6 col-lg-6"><?php /* reason */ ?>
                          <div class="form-group rough_sheet_reason_div"  style="<?php if($mode == "Update" && isset($form_data) && $form_data[0]['rough_sheet']=='Yes') { } else echo 'display:none'; ?>">
                            <label for="rough_sheet_reason" class="form_label">Reason <sup class="text-danger"></sup></label>
                            <input type="text" name="rough_sheet_reason"  id="rough_sheet_reason" value="<?php if($mode == "Add") { echo set_value('rough_sheet_reason'); } else { echo $form_data[0]['rough_sheet_reason']; } ?>" placeholder="Reason" class="form-control custom_input rough_sheet_reason" maxlength="500"  onchange="validate_input('rough_sheet_reason')"/>
                            
                            <?php if(form_error('rough_sheet_reason')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('rough_sheet_reason'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-12 col-lg-12"><?php /* action_for_unfair */ ?>
                          <div class="form-group">
                            <label for="action_for_unfair" class="form_label">22. What is the action taken for unfair means adopted by the candidates? Whether the Format for reporting UNFAIR Practices is duly filled. <sup class="text-danger">*</sup></label>
                            <input  type="text" name="action_for_unfair"  id="action_for_unfair" value="<?php if($mode == "Add") { echo set_value('action_for_unfair'); } else { echo $form_data[0]['action_for_unfair']; } ?>" placeholder="Give Details" class="form-control custom_input" maxlength="500"  onchange="validate_input('action_for_unfair')"/>
                            
                            <?php if(form_error('action_for_unfair')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('action_for_unfair'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      <div class="col-xl-12 col-lg-12"><?php /* name_mob_exam_contro */ ?>
                          <div class="form-group">
                            <label for="name_mob_exam_contro" class="form_label">23. Name & Mobile No. of Examination Controller - Sify/NSEIT <sup class="text-danger">*</sup></label>
                            <input required type="text" name="name_mob_exam_contro"  id="name_mob_exam_contro" value="<?php if($mode == "Add") { echo set_value('name_mob_exam_contro'); } else { echo $form_data[0]['name_mob_exam_contro']; } ?>" placeholder="Give Details" class="form-control custom_input" maxlength="500"  onchange="validate_input('name_mob_exam_contro')"/>
                            
                            <?php if(form_error('name_mob_exam_contro')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('name_mob_exam_contro'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      <div class="col-xl-12 col-lg-12"><?php /* issue_reported */ ?>
                          <div class="form-group">
                            <label for="issue_reported" class="form_label">24. Any issue reported/faced by candidates <sup class="text-danger"></sup></label>
                            <input  type="text" name="issue_reported"  id="issue_reported" value="<?php if($mode == "Add") { echo set_value('issue_reported'); } else { echo $form_data[0]['issue_reported']; } ?>" placeholder="Give Details" class="form-control custom_input" maxlength="500"  onchange="validate_input('issue_reported')"/>
                            
                            <?php if(form_error('issue_reported')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('issue_reported'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      <div class="col-xl-12 col-lg-12"><?php /* observation */ ?>
                          <div class="form-group">
                            <label for="observation" class="form_label">25. Any other observation /Suggestion if any <sup class="text-danger"></sup></label>
                            <input  type="text" name="observation"  id="observation" value="<?php if($mode == "Add") { echo set_value('observation'); } else { echo $form_data[0]['observation']; } ?>" placeholder="Give Details" class="form-control custom_input" maxlength="500"  onchange="validate_input('observation')"/>
                            
                            <?php if(form_error('observation')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('observation'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* today_date */ ?>
                          <div class="form-group">
                            <label for="filled_date" class="form_label">Date <sup class="text-danger"></sup></label>
                            <input readonly type="text" name="filled_date"  id="filled_date" value="<?php if($mode == "Add") { echo Date('Y-m-d'); } else { echo $form_data[0]['filled_date']; } ?>" placeholder="" class="form-control custom_input"   onchange="validate_input('filled_date')"/>
                            
                            <?php if(form_error('filled_date')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('filled_date'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      <?php if($form_data[0]['uploaded_file']!='') { ?>
                      <div class="col-xl-6 col-lg-6"><?php /* sign */ ?>
                          <div class="form-group">
                            <label for="sign" class="form_label"><?php if($form_data[0]['uploaded_file']!='') { ?>
                            &nbsp;<i style="float:right;">Signed PDF : <a href="<?php echo base_url(); ?>/supervision/admin/candidate/download_form_pdf/?pdf_file=<?php echo $form_data[0]['uploaded_file'] ?>">Click Here</a></i>
                            <?php } ?> <sup class="text-danger"></sup></label>
                            <?php if(form_error('sign')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('sign'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      <?php } ?>
                    </div>
                    
                    <div class="hr-line-dashed"></div>										
										<div class="row">
											<div class="col-xl-11 col-lg-11 col-md-11 col-sm-11 col-xs-11 text-center" id="">
                        <div class="form-group row">
                            
                            <input style="margin-left: 10px;" type="text" name="comment_by_pdc"  id="comment_by_pdc" value="<?php if($mode == "Add") { echo set_value('comment_by_pdc'); } else { echo $form_data[0]['comment_by_pdc']; } ?>" placeholder="Put Comment here if any" class="col-md-9 form-control custom_input" maxlength="500"  onchange="validate_input('comment_by_pdc')"/>
                            
                            <?php if(form_error('comment_by_pdc')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('comment_by_pdc'); ?></label> <?php } ?>
                            <button class="btn btn-primary " style="margin-left: 10px;" type="submit">Send Comment</button>
                        </div>		
                       
											
                      </div>
                      <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center" id="submit_btn_outer">
                     
												<a class="btn btn-danger" href="<?php echo site_url('supervision/admin/candidate/session_forms'); ?>">Back</a>	
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            
              <div id="common_log_outer"></div>              
            </div>
          </div>					
        </div>
      </div>
      <?php $this->load->view('supervision/admin/inc_footerbar_admin'); ?>		
    </div>
  </div>
  
  <?php $this->load->view('supervision/inc_footer'); ?>		
  <?php $this->load->view('supervision/common/inc_common_validation_all'); ?>
  <?php $this->load->view('supervision/common/inc_common_show_hide_password'); ?>
 
  
  <script type="text/javascript">
    var filled_date = $('#filled_date').datepicker({ keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy", viewMode: "years", minViewMode: "years", clearBtn: true, endDate:"<?php echo date("Y"); ?>" });

    function validateFile(event, error_id, show_img_id, size, img_width, img_height) {
        var srcid = event.srcElement.id;
        if (document.getElementById(srcid).files.length != 0) {
          var file = document.getElementById(srcid).files[0];

          if (file.size == 0) {
            $('#' + error_id).text('Please select valid file');
            $('#' + document.getElementById(srcid).id).val('')
            $('#' + show_img_id).attr('src', "/assets/images/default1.png");
          }
          else {
            var file_size = document.getElementById(srcid).files[0].size / 1024;
            var mimeType = document.getElementById(srcid).files[0].type;

            var allowedFiles = [".pdf"];
            if ($('#' + document.getElementById(srcid).id + '_allowedFilesTypes').text() != "") {
              var allowedFiles = $('#' + document.getElementById(srcid).id + '_allowedFilesTypes').text().split(",");
            }
            var regex = new RegExp("([a-zA-Z0-9\s_\\((\d+)\)\.\-:])+(" + allowedFiles.join('|') + ")$");

            var reader = new FileReader();

            var check_size = '';

            if (size.indexOf('kb') !== -1) {
              var check_size = size.split('k');
            }
            if (size.indexOf('mb') !== -1) {
              var check_size = size.split('m');
            }

            reader.onload = function (e) {
              var img = new Image();
              img.src = e.target.result;

              if (reader.result == 'data:') {
                $('#' + error_id).text('This file is corrupted');
                $('#' + document.getElementById(srcid).id).val('')
                $('#' + show_img_id).attr('src', "/assets/images/default1.png");
              }
              else {
        
                if (!regex.test(file.name.toLowerCase())) {
                  $('#' + error_id).text("Please upload " + allowedFiles.join(', ') + " only.");
             
                  $('#' + document.getElementById(srcid).id).val('')
                  $('#' + show_img_id).attr('src', "/assets/images/default1.png");
                }
                else {
                  if (file_size > check_size[0]) {
             
                    $('#' + error_id).text("Please upload file less than " + size);
                    $('#' + document.getElementById(srcid).id).val('')
                    $('#' + show_img_id).attr('src', "/assets/images/default1.png");
                  }
                  else if (file_size < 8) //IF FILE SIZE IS LESS THAN 8KB
                  {
                    $('#' + error_id).text("Please upload file having size more than 8KB");
                    $('#' + document.getElementById(srcid).id).val('')
                    $('#' + show_img_id).attr('src', "/assets/images/default1.png");
                  }
                  else {
                    img.onload = function () {
                      var width = this.width;
                      var height = this.height;

                      if (width > img_width && height > img_height) {
                        $('#' + error_id).text(' Uploaded File dimensions are ' + width + '*' + height + ' pixel. Please Upload file dimensions between ' + img_width + '*' + img_height + ' pixel');
                        $('#' + document.getElementById(srcid).id).val('')
                        $('#' + show_img_id).attr('src', "/assets/images/default1.png");
                      }
                      else {
                     
                        $('#' + error_id).text("");
                        $('.btn_submit').attr('disabled', false);
                        $('#' + show_img_id).attr('src', '');
                        $('#' + show_img_id).removeAttr('src');
                        $('#' + show_img_id).attr('src', reader.result);

                        var img = new Image();
                        img.src = reader.result;
                      }
                    }

                  }
                }
              }
            }

            reader.readAsDataURL(event.target.files[0]);
          }
        }
        else {
          $('#' + error_id).text('Please select file');
          $('#' + document.getElementById(srcid).id).val('')
          $('#' + show_img_id).attr('src', "/assets/images/default1.png");
        }
      }
      function candidate_appeared_func() {
      
      $("select.exam_time option:selected").each(function () {
        var $this = $(this);
        if ($this.length) {
          var selClass = $this.attr('class');
          var selText = $this.text();
          
          $("input.candidate_appeared"+selClass).parent('.candidate_appeared_div').find('.session_time_text').text(selText);
          $("input.candidate_appeared"+selClass).parent('.candidate_appeared_div').show();
          $("input.candidate_appeared"+selClass).attr('required','required');
       
        }
      });
    
  }
  $(document).on('change', 'select.exam_time', function() {
     
      $(".candidate_appeared_div").each(function () {
        $(this).hide();
        $(this).find('input').removeAttr('required').val('');
      });
      candidate_appeared_func();
    
    });
    candidate_appeared_func();
      function showhideinputs() {

    if ($('input[type=radio][name=suitable_venue_loc]:checked').val() == 'No') {
        $('.suitable_venue_loc_reason_div').show();
        $('.suitable_venue_loc_reason').attr('required','required');
    }
    else if ($('input[type=radio][name=suitable_venue_loc]:checked').val() == 'Yes') {
      $('.suitable_venue_loc_reason_div').hide();
        $('.suitable_venue_loc_reason').removeAttr('required').removeClass('error').val('');
    }

    if ($('input[type=radio][name=venue_open_bef_exam]:checked').val() == 'No') {
        $('.venue_open_bef_exam_reason_div').show();
        $('.venue_open_bef_exam_reason').attr('required','required');
    }
    else if ($('input[type=radio][name=venue_open_bef_exam]:checked').val() == 'Yes') {
      $('.venue_open_bef_exam_reason_div').hide();
        $('.venue_open_bef_exam_reason').removeAttr('required').removeClass('error').val('');
    }

    if ($('input[type=radio][name=venue_reserved]:checked').val() == 'No') {
        $('.venue_reserved_reason_div').show();
        $('.venue_reserved_reason').attr('required','required');
    }
    else if ($('input[type=radio][name=venue_reserved]:checked').val() == 'Yes') {
      $('.venue_reserved_reason_div').hide();
        $('.venue_reserved_reason').removeAttr('required').removeClass('error').val('');
    }

    if ($('input[type=radio][name=venue_power_problem]:checked').val() == 'Yes') {
        $('.venue_power_problem_sol_div').show();
        $('.venue_power_problem_sol').attr('required','required');
    }
    else if ($('input[type=radio][name=venue_power_problem]:checked').val() == 'No') {
      $('.venue_power_problem_sol_div').hide();
        $('.venue_power_problem_sol').removeAttr('required').removeClass('error').val('');
    }

    if ($('input[type=radio][name=registration_process]:checked').val() == 'No') {
        $('.registration_process_reason_div').show();
        $('.registration_process_reason').attr('required','required');
    }
    else if ($('input[type=radio][name=registration_process]:checked').val() == 'Yes') {
      $('.registration_process_reason_div').hide();
        $('.registration_process_reason').removeAttr('required').removeClass('error').val('');
    }

    if ($('input[type=radio][name=frisking]:checked').val() == 'No') {
        $('.frisking_reason_div').show();
        $('.frisking_reason').attr('required','required');
    }
    else if ($('input[type=radio][name=frisking]:checked').val() == 'Yes') {
      $('.frisking_reason_div').hide();
        $('.frisking_reason').removeAttr('required').removeClass('error').val('');
    }

    if ($('input[type=radio][name=frisking_lady]:checked').val() == 'No') {
        $('.frisking_lady_reason_div').show();
        $('.frisking_lady_reason').attr('required','required');
    }
    else if ($('input[type=radio][name=frisking_lady]:checked').val() == 'Yes') {
      $('.frisking_lady_reason_div').hide();
        $('.frisking_lady_reason').removeAttr('required').removeClass('error').val('');
    }

    if ($('input[type=radio][name=mobile_allowed]:checked').val() == 'Yes') {
        $('.mobile_allowed_reason_div').show();
        $('.mobile_allowed_reason').attr('required','required');
    }
    else if ($('input[type=radio][name=mobile_allowed]:checked').val() == 'No') {
      $('.mobile_allowed_reason_div').hide();
        $('.mobile_allowed_reason').removeAttr('required').removeClass('error').val('');
    }

    if ($('input[type=radio][name=admit_letter_checked]:checked').val() == 'No') {
        $('.admit_letter_checked_reason_div').show();
        $('.admit_letter_checked_reason').attr('required','required');
    }
    else if ($('input[type=radio][name=admit_letter_checked]:checked').val() == 'Yes') {
      $('.admit_letter_checked_reason_div').hide();
        $('.admit_letter_checked_reason').removeAttr('required').removeClass('error').val('');
    }

    if ($('input[type=radio][name=exam_without_admit_letter]:checked').val() == 'Yes') {
        $('.exam_without_admit_letter_detils_div').show();
        $('.exam_without_admit_letter_detils').attr('required','required');
    }
    else if ($('input[type=radio][name=exam_without_admit_letter]:checked').val() == 'No') {
      $('.exam_without_admit_letter_detils_div').hide();
        $('.exam_without_admit_letter_detils').removeAttr('required').removeClass('error').val('');
    }

    if ($('input[type=radio][name=seat_no_written]:checked').val() == 'No') {
        $('.seat_no_written_reason_div').show();
        $('.seat_no_written_reason').attr('required','required');
    }
    else if ($('input[type=radio][name=seat_no_written]:checked').val() == 'Yes') {
      $('.seat_no_written_reason_div').hide();
        $('.seat_no_written_reason').removeAttr('required').removeClass('error').val('');
    }

    if ($('input[type=radio][name=candidate_seated]:checked').val() == 'No') {
        $('.candidate_seated_reason_div').show();
        $('.candidate_seated_reason').attr('required','required');
    }
    else if ($('input[type=radio][name=candidate_seated]:checked').val() == 'Yes') {
      $('.candidate_seated_reason_div').hide();
        $('.candidate_seated_reason').removeAttr('required').removeClass('error').val('');
    }

    if ($('input[type=radio][name=scribe_arrange]:checked').val() == 'No') {
        $('.scribe_arrange_reason_div').show();
        $('.scribe_arrange_reason').attr('required','required');
    }
    else if ($('input[type=radio][name=scribe_arrange]:checked').val() == 'Yes') {
      $('.scribe_arrange_reason_div').hide();
        $('.scribe_arrange_reason').removeAttr('required').removeClass('error').val('');
    }
    if ($('input[type=radio][name=started_late]:checked').val() == 'Yes') {
        $('.started_late_reason_div').show();
        $('.started_late_reason').attr('required','required');
    }
    else if ($('input[type=radio][name=started_late]:checked').val() == 'No') {
      $('.started_late_reason_div').hide();
        $('.started_late_reason').removeAttr('required').removeClass('error').val('');
    }

    if ($('input[type=radio][name=unfair_candidates]:checked').val() == 'Yes') {
        $('.unfair_candidates_reason_div').show();
        $('.unfair_candidates_reason').attr('required','required');
    }
    else if ($('input[type=radio][name=unfair_candidates]:checked').val() == 'No') {
      $('.unfair_candidates_reason_div').hide();
        $('.unfair_candidates_reason').removeAttr('required').removeClass('error').val('');
    }

    if ($('input[type=radio][name=rough_sheet]:checked') == 'Yes') {
        $('.rough_sheet_reason_div').show();
        $('.rough_sheet_reason').attr('required','required');
    }
    else if ($('input[type=radio][name=rough_sheet]:checked') == 'No') {
      $('.rough_sheet_reason_div').hide();
        $('.rough_sheet_reason').removeAttr('required').removeClass('error').val('');
    }

    }
    
    $('input[type=radio][name=suitable_venue_loc]').change(function() {
      showhideinputs();
    });

    $('input[type=radio][name=venue_open_bef_exam]').change(function() {
      showhideinputs();
    });
    $('input[type=radio][name=venue_reserved]').change(function() {
      showhideinputs();
    });

    $('input[type=radio][name=venue_power_problem]').change(function() {
      showhideinputs();
    });

    $('input[type=radio][name=registration_process]').change(function() {
      showhideinputs();
    });

    $('input[type=radio][name=frisking]').change(function() {
      showhideinputs();
    });

    $('input[type=radio][name=frisking_lady]').change(function() {
      showhideinputs();
    });

    $('input[type=radio][name=mobile_allowed]').change(function() {
      showhideinputs();
    });

    $('input[type=radio][name=admit_letter_checked]').change(function() {
      showhideinputs();
    });

    $('input[type=radio][name=exam_without_admit_letter]').change(function() {
      showhideinputs();
    });
   

    $('input[type=radio][name=seat_no_written]').change(function() {
      showhideinputs();
    });

    $('input[type=radio][name=candidate_seated]').change(function() {
      showhideinputs();
    });

    $('input[type=radio][name=scribe_arrange]').change(function() {
      showhideinputs();
    });
   
    $('input[type=radio][name=exam_started]').change(function() {
      showhideinputs();
    });
    $('input[type=radio][name=started_late]').change(function() {
      showhideinputs();
    });
    $('input[type=radio][name=unfair_candidates]').change(function() {
      showhideinputs();
    });

    $('input[type=radio][name=rough_sheet]').change(function() {
      showhideinputs();
    });

    showhideinputs();
    function get_exam_venue_ajax(exam_code)
    {
      $("#page_loader").show();
      parameters="exam_code="+exam_code;
      
      $.ajax({
        type: "POST",
        url: "<?php echo site_url('supervision/admin/candidate/get_exam_venues_ajax'); ?>",
        data: parameters,
        cache: false,
        dataType: 'JSON',
        success:function(data)
        {
          if(data.flag == "success")
          {
            $("#venue_outer").html(data.response);
            $("#page_loader").hide();
          }
          else
          {
            alert("Error occurred. Please try again.")
            $('#page_loader').hide();
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
    function get_exam_date_ajax(venue_code)
    {
      $("#page_loader").show();
      parameters="venue_code="+venue_code;
      
      $.ajax({
        type: "POST",
        url: "<?php echo site_url('supervision/admin/candidate/get_exam_dates_ajax'); ?>",
        data: parameters,
        cache: false,
        dataType: 'JSON',
        success:function(data)
        {
          if(data.flag == "success")
          {
            $("#exam_date_outer").html(data.response);
            $("#page_loader").hide();
          }
          else
          {
            alert("Error occurred. Please try again.")
            $('#page_loader').hide();
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
    function get_exam_time_ajax(exam_date)
    {
      $("#page_loader").show();
      parameters="exam_date="+exam_date+'&venue_code='+$('.venue_code').val()+'&exam_code='+$('.exam_code').val();
      
      $.ajax({
        type: "POST",
        url: "<?php echo site_url('supervision/admin/candidate/get_exam_times_ajax'); ?>",
        data: parameters,
        cache: false,
        dataType: 'JSON',
        success:function(data)
        {
          if(data.flag == "success")
          {
            $("#exam_time_outer").html(data.response);
            $("#page_loader").hide();
          }
          else
          {
            alert("Error occurred. Please try again.")
            $('#page_loader').hide();
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
    
    //START : JQUERY VALIDATION SCRIPT 
    function validate_input(input_id) { $("#"+input_id).valid(); }
    $(document ).ready( function() 
    {
      var form = $("#add_form").validate( 
      {
        onkeyup: function(element) { $(element).valid(); },          
        rules:
        {
          
        },
        messages:
        {
          
        }, 
        errorPlacement: function(error, element) // For replace error 
        {
          
        },          
        submitHandler: function(form) 
        {          
          $("#page_loader").hide();
          swal({ title: "Please confirm", text: "Please confirm to send comment", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
          { 
            $("#page_loader").show();            
            $("#submit_btn_outer").html('<button class="btn btn-primary" type="button" style="cursor:wait">Submit</button> <a class="btn btn-danger" href="<?php echo site_url('supervision/admin/candidate/session_forms/'); ?>">Back</a>');
           
            form.submit();
          }); 
        }
      });
    });
    //END : JQUERY VALIDATION SCRIPT
  </script>
  <?php $this->load->view('supervision/common/inc_bottom_script'); ?>
</body>
</html>