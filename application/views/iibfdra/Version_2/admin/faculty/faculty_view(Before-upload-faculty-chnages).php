<?php $this->load->view('iibfdra/Version_2/admin/includes/header'); ?>
<?php $this->load->view('iibfdra/Version_2/admin/includes/sidebar'); ?>

<style>
  .control-label {
    font-weight: bold !important;
  }

  .note {
    color: blue;
    font-size: small;
  }

  .note-error {
    color: red;
    font-size: medium;
  }

  .swal2-content {
    font-size: 14px !important;
    font-weight: 400 !important;
    word-wrap: break-word !important;
    line-height: 21px !important;
  }

  .custom_label { line-height: 16px !important; vertical-align: top; }
</style>
<div class="content-wrapper">
  <section class="content-header">
    <h1> View Faculty Form </h1>
  </section>
  <?php
  header('Cache-Control: must-revalidate');
  header('Cache-Control: post-check=0, pre-check=0', FALSE);
  ?>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">View Faculty</h3>
            <?php if($request_from != 'Inspector') { ?>
              <div class="pull-right"> <a href="<?php echo base_url(); ?>iibfdra/Version_2/admin/faculty_master" class="btn btn-warning">Back</a> </div>
            <?php }?>
          </div>

          <div class="box-body">
            <form class="form-horizontal" autocomplete="off" name="frmDrACenter" id="faculty_form" method="post" enctype="multipart/form-data" data-parsley-validate>
              <?php 
                $status = isset($faculty_data[0]['status']) ? $faculty_data[0]['status'] : ''; 
                if ($status == 'In Review') { $labelCls = 'text-info'; } 
                else if ($status == 'Active') { $labelCls = 'text-success'; } 
                else if ($status == 'Re-Submitted') { $labelCls = 'text-warning'; } 
                else if ($status == 'Inactive') { $labelCls = 'text-danger'; }
              ?>

              <div class="col-sm-12">
                <label class="col-sm-3 control-label">Status</label>
                <label class="col-sm-2 <?php echo $labelCls; ?>" style="margin-top:3px;"><?php echo $status; ?></label>
              </div>

              <?php if($request_from != 'Inspector') {?>
                <div class="col-sm-12">
                  <label class="col-sm-3 control-label">Change Status</label>
                  <div class="col-sm-4">
                    <div class="form-check">
                      <?php if ($status == 'In Review' || $status == 'Inactive' || $status == 'Re-Submitted') { ?>
                        <label><input type="radio" class="form-check-input radiobtn" id="active" name="radiobt" value="Active">Active</label>
                      <?php } ?>

                      <?php if ($status == 'In Review' || $status == 'Active' || $status == 'Re-Submitted') { ?>
                        <label><input type="radio" class="form-check-input radiobtn" id="inactive" name="radiobt" value="Inactive" onclick="return confirm_action(event)">Inactive</label>
                      <?php } ?>
                    </div>
                    <span class="error" id="status_error"></span>
                  </div>
                  <div class="col-sm-5">
                    <input type="button" name="btn_status" id="btn_status" value="Change Status" onclick="change_status()">
                  </div>
                </div>
              <?php }?>
                
              <div class="col-sm-12" id="reason_div" style="display: none;">
                <label class="col-sm-3 control-label">Reason</label>
                <div class="col-sm-3" style="margin-top:10px">
                  <textarea rows="2" id="reason"></textarea>
                  <br><span class="error" id="reason_error"></span>
                </div>
              </div>

              <?php
              $institute_name = '';
              if ($faculty_data[0]['institute_id'] != '') 
              {
                $where = array('dra_inst_registration_id' => $faculty_data[0]['institute_id']);
                $institute_data = $this->master_model->getRecords('dra_accerdited_master', $where);
                $institute_name = $institute_data[0]['institute_name'];
              }
              ?>
              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-3 control-label">Institute</label>
                  <div class="col-sm-6">
                    <label class=""><b><?php echo $institute_name; ?></b></label>
                  </div>
                </div>
              </div>

              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-3 control-label">Faculty No.<span style="color:#F00">*</span></label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="faculty_name" name="faculty_number" placeholder="Faculty Number" value="<?php echo isset($faculty_data[0]['faculty_number']) ? 'F' . $faculty_data[0]['faculty_number'] : 'F' . $faculty_number; ?>" readonly="readonly">
                  </div>
                </div>
              </div>
              
              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-3 control-label">Name of Faculty<span style="color:#F00">*</span></label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="faculty_name" name="faculty_name" placeholder="Faculty Name" value="<?php echo isset($faculty_data[0]['salutation']) ? $faculty_data[0]['salutation'].' ' : ''; echo isset($faculty_data[0]['faculty_name']) ? $faculty_data[0]['faculty_name'] : ''; ?>" data-parsley-maxlength="75" maxlength="75" data-parsley-required data-parsley-errors-container="#faculty_name_error" onkeypress="return onlyAlphabets(event)">
                    <span class="note-error" id="faculty_name_error"> <?php echo form_error('faculty_name'); ?></span>
                  </div>
                </div>
              </div>              

              <?php
              if (!empty($faculty_data[0]['faculty_photo'])) { ?>
                <div class="col-sm-12" id="faculty_photo_show">
                  <div class="form-group">
                    <label for="exampleInputName1" class="col-sm-3 control-label"><b>Faculty Photo</b></label>
                    <div class="col-sm-5">
                      <img height="100" width="100" src="<?php echo base_url(); ?>uploads/faculty_photo/<?php echo $faculty_data[0]['faculty_photo']; ?>" />
                    </div>
                  </div>
                </div>
              <?php } ?>

              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-3 control-label">Date of Birth</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="dob" name="dob" placeholder="Date of Birth" value="<?php echo isset($faculty_data[0]['dob']) ? $faculty_data[0]['dob'] : ''; ?>" data-parsley-minimumage="18" data-parsley-minimumage-message="Applicant must be at least 17 years of age to apply" data-parsley-validate_dob="" data-parsley-validate_dob-message="Please enter a valid date" data-parsley-pattern="/[0-9]\d*/" data-parsley-pattern-message="Only numbers allowed" data-parsley-trigger="keyup" data-parsley-validation-threshold="0" required>
                  </div>
                </div>
              </div>

              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-3 control-label">PAN No.<span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="pan_no" name="pan_no" placeholder="PAN No" value="<?php echo isset($faculty_data[0]['pan_no']) ? $faculty_data[0]['pan_no'] : ''; ?>" minlength="10" maxlength="10" data-parsley-required data-parsley-errors-container="#pan_no_error" data-parsley-pan_no_exist data-parsley-trigger=focusout data-parsley-pan_no_exist-message="This PAN no is already Exists." data-parsley-pattern="/[A-Z]{5}[0-9]{4}[A-Z]{1}$/" data-parsley-pattern-message="Please enter 10 alphanumeric PAN no with Valid Format mentioned below.">                    
                  </div>
                </div>
              </div>

              <?php if (!empty($faculty_data[0]['pan_photo'])) { ?>
                <div class="col-sm-12" id="pan_photo_show">
                  <div class="form-group">
                    <label for="exampleInputName1" class="col-sm-3 control-label"><b>PAN Photo</b></label>
                    <div class="col-sm-5">
                      <img height="100" width="100" src="<?php echo base_url(); ?>uploads/pan_photo/<?php echo $faculty_data[0]['pan_photo']; ?>" />
                    </div>
                  </div>
                </div>
              <?php } ?>

              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-3 control-label">Base Location</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="base_location" name="base_location" placeholder="Base Location" value="<?php echo isset($faculty_data[0]['base_location']) ? $faculty_data[0]['base_location'] : ''; ?>" maxlength="75">
                  </div>
                </div>
              </div>

              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-3 control-label">Academic Qualification(s) with year of passing<span style="color:#F00">*</span></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="academic_qualification" name="academic_qualification" placeholder="Academic Qualification" value="<?php echo isset($faculty_data[0]['academic_qualification']) ? $faculty_data[0]['academic_qualification'] : ''; ?>" minlength="3" maxlength="100" data-parsley-errors-container="#academic_qualification_error" data-parsley-required>
                    <span class="note-error" id="academic_qualification_error"> <?php echo form_error('academic_qualification'); ?></span>
                  </div>
                </div>
              </div>

              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-3 control-label">Professional Qualification(s) if any, (including from IIBF) with year of passing</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="personal_qualification" name="personal_qualification" placeholder="Professional Qualification" value="<?php echo isset($faculty_data[0]['personal_qualification']) ? $faculty_data[0]['personal_qualification'] : ''; ?>">
                  </div>
                </div>
              </div>

              <div class="col-sm-12">
                <div class="form-group" style="margin-bottom:0;">
                  <label class="col-sm-3 control-label custom_label">Work Experience<span style="color:#F00">*</span></label>
                  <div class="col-sm-2 text-center"><label class="control-label custom_label">Bank/ FI Name</label></div>
                  <div class="col-sm-3 text-center"><label class="control-label custom_label">Last Position Employee Id</label></div>
                  <div class="col-sm-2 text-center"><label class="control-label custom_label">Gross Duration Year</label></div>
                  <div class="col-sm-2 text-center"><label class="control-label custom_label">Gross Duration Month</label></div>
                </div>
              </div>

              <?php if (!empty($faculty_data[0]['work_exp1']) || !empty($faculty_data[0]['emp_id1']) || !empty($faculty_data[0]['gross_duration_year1']) || !empty($faculty_data[0]['gross_duration_month1']))
              { ?>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-2">
                      <input type="text" class="form-control" id="work_exp0" name="work_exp[]" placeholder="Bank/ FI Name" value="<?php echo $faculty_data[0]['work_exp1']; ?>" data-parsley-errors-container="#work_exp_error0" data-parsley-required>
                      <span class="note-error" id="work_exp_error0"></span>
                    </div>
                    
                    <div class="col-sm-3">
                      <input type="text" class="form-control" id="emp_id0" name="emp_id[]" placeholder="Last Position Employee Id" value="<?php echo $faculty_data[0]['emp_id1']; ?>" data-parsley-errors-container="#emp_id_error0" data-parsley-required>
                      <span class="note-error" id="emp_id_error0"></span>
                    </div>
                    
                    <div class="col-sm-2">
                      <input type="text" class="form-control" id="gross_duration0" name="gross_duration[]" placeholder="Gross Duration" value="<?php echo $faculty_data[0]['gross_duration_year1']; ?>" data-parsley-errors-container="#gross_duration_error0" data-parsley-required>
                      <span class="note-error" id="gross_duration_error0"></span>
                    </div> 
                    
                    <div class="col-sm-2">
                      <input type="text" class="form-control" id="gross_duration0" name="gross_duration[]" placeholder="Gross Duration" value="<?php echo $faculty_data[0]['gross_duration_month1']; ?>" data-parsley-errors-container="#gross_duration_error0" data-parsley-required>
                      <span class="note-error" id="gross_duration_error0"></span>
                    </div>
                  </div>
                </div>
              <?php }
              
              if (!empty($faculty_data[0]['work_exp2']) || !empty($faculty_data[0]['emp_id2']) || !empty($faculty_data[0]['gross_duration_year2']) || !empty($faculty_data[0]['gross_duration_month2']))
              { ?>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-2">
                      <input type="text" class="form-control" id="work_exp0" name="work_exp[]" placeholder="Bank/ FI Name" value="<?php echo $faculty_data[0]['work_exp2']; ?>" data-parsley-errors-container="#work_exp_error0" data-parsley-required>
                      <span class="note-error" id="work_exp_error0"></span>
                    </div>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" id="emp_id0" name="emp_id[]" placeholder="Last Position Employee Id" value="<?php echo $faculty_data[0]['emp_id2']; ?>" data-parsley-errors-container="#emp_id_error0" data-parsley-required>
                      <span class="note-error" id="emp_id_error0"></span>
                    </div>
                    <div class="col-sm-2">
                      <input type="text" class="form-control" id="gross_duration0" name="gross_duration[]" placeholder="Gross Duration" value="<?php echo $faculty_data[0]['gross_duration_year2']; ?>" data-parsley-errors-container="#gross_duration_error0" data-parsley-required>
                      <span class="note-error" id="gross_duration_error0"></span>
                    </div>
                    <div class="col-sm-2">
                      <input type="text" class="form-control" id="gross_duration0" name="gross_duration[]" placeholder="Gross Duration" value="<?php echo $faculty_data[0]['gross_duration_month2']; ?>" data-parsley-errors-container="#gross_duration_error0" data-parsley-required>
                      <span class="note-error" id="gross_duration_error0"></span>
                    </div>
                  </div>
                </div>
              <?php }              
              
              if (!empty($faculty_data[0]['work_exp3']) || !empty($faculty_data[0]['emp_id3']) || !empty($faculty_data[0]['gross_duration_year3']) || !empty($faculty_data[0]['gross_duration_month3']))
              { ?>
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-2">
                      <input type="text" class="form-control" id="work_exp0" name="work_exp[]" placeholder="Bank/ FI Name" value="<?php echo $faculty_data[0]['work_exp3']; ?>" data-parsley-errors-container="#work_exp_error0" data-parsley-required>
                      <span class="note-error" id="work_exp_error0"></span>
                    </div>
                    <div class="col-sm-3">
                      <input type="text" class="form-control" id="emp_id0" name="emp_id[]" placeholder="Last Position Employee Id" value="<?php echo $faculty_data[0]['emp_id3']; ?>" data-parsley-errors-container="#emp_id_error0" data-parsley-required>
                      <span class="note-error" id="emp_id_error0"></span>
                    </div>
                    <div class="col-sm-2">
                      <input type="text" class="form-control" id="gross_duration0" name="gross_duration[]" placeholder="Gross Duration" value="<?php echo $faculty_data[0]['gross_duration_year3']; ?>" data-parsley-errors-container="#gross_duration_error0" data-parsley-required>
                      <span class="note-error" id="gross_duration_error0"></span>
                    </div>
                    <div class="col-sm-2">
                      <input type="text" class="form-control" id="gross_duration0" name="gross_duration[]" placeholder="Gross Duration" value="<?php echo $faculty_data[0]['gross_duration_month3']; ?>" data-parsley-errors-container="#gross_duration_error0" data-parsley-required>
                      <span class="note-error" id="gross_duration_error0"></span>
                    </div> 
                  </div>
                </div>
              <?php } ?>
              

              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-3 control-label">Work Experience in IIBF if Any</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="work_exp_iibf" name="work_exp_iibf" placeholder="Work Experience in IIBF" value="<?php echo isset($faculty_data[0]['work_exp_iibf']) ? $faculty_data[0]['work_exp_iibf'] : ''; ?>" maxlength="100">
                  </div>
                </div>
              </div>

              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-3 control-label">Experience as Faculty in DRA Training, if Any</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="DRA_training_faculty_exp" name="DRA_training_faculty_exp" placeholder="Experience as Faculty in DRA Training, if Any" value="<?php echo isset($faculty_data[0]['DRA_training_faculty_exp']) ? $faculty_data[0]['DRA_training_faculty_exp'] : ''; ?>" maxlength="100">
                  </div>
                </div>
              </div>

              <div class="col-sm-12" id="agency_association_period" style="display: none;">
                <div class="form-group">
                  <label class="col-sm-3 control-label">Period of Association with the Agency (for DRA Training purpose):</label>
                  <div class="col-sm-4">
                    <label class="control-label">Start Date</label>
                  </div>
                  <div class="col-sm-4">
                    <label class="control-label">End Date</label>
                  </div>
                </div>
              </div>

              <div class="col-sm-12" id="agency_association_period1" style="display: none;">
                <div class="form-group">
                  <label class="col-sm-3 control-label"></label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="start_date" name="start_date" placeholder="Start Date" value="<?php echo isset($faculty_data[0]['start_date']) ? $faculty_data[0]['start_date'] : ''; ?>">
                  </div>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="end_date" name="end_date" placeholder="End Date" value="<?php echo isset($faculty_data[0]['end_date']) ? $faculty_data[0]['end_date'] : ''; ?>">
                  </div>
                </div>
                <span class="note-error" id="date_error"></span>
              </div>

              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-3 control-label">Interested to take sessions on<span style="color:#F00">*</span></label>
                  <div class="col-sm-9">
                    <div class="form-check">
                      <input type="radio" class="form-check-input radiocls" id="radio1" name="session_interested_in" value="1" data-parsley-errors-container="#session_interested_error" data-parsley-required <?php if (isset($faculty_data[0]['session_interested_in']) && $faculty_data[0]['session_interested_in'] == '1') { echo 'checked="checked"'; }; ?>>Banking Subjects
                      <label class="form-check-label" for="radio1"></label>

                      <input type="radio" class="form-check-input radiocls" id="radio2" name="session_interested_in" value="2" data-parsley-errors-container="#session_interested_error" data-parsley-required <?php if (isset($faculty_data[0]['session_interested_in']) && $faculty_data[0]['session_interested_in'] == '2') { echo 'checked="checked"'; }; ?>>Soft Skill in Banking
                      <label class="form-check-label" for="radio1"></label>

                      <input type="radio" class="form-check-input radiocls" id="radio3" name="session_interested_in" value="3" data-parsley-errors-container="#session_interested_error" data-parsley-required <?php if (isset($faculty_data[0]['session_interested_in']) && $faculty_data[0]['session_interested_in'] == '3') { echo 'checked="checked"'; }; ?>>Banking Subjects and Soft Skill in Banking
                      <label class="form-check-label" for="radio1"></label>
                      <span class="note-error" id="session_interested_error"> <?php echo form_error('session_interested'); ?></span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-3 control-label">Qualification / Experience in Soft Skill in BFSI Sector, if any</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="softskills_banking_exp" name="softskills_banking_exp" placeholder="Qualification / Experience in Soft Skill in Banking" value="<?php echo isset($faculty_data[0]['softskills_banking_exp']) ? $faculty_data[0]['softskills_banking_exp'] : ''; ?>" maxlength="100">
                  </div>
                </div>
              </div>

              <div class="col-sm-12">
                <div class="form-group">
                  <label class="col-sm-3 control-label">Experience/Comments on training specific activities, if any</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="training_activities_exp" name="training_activities_exp" placeholder="Experience/Comments on training specific activities" value="<?php echo isset($faculty_data[0]['training_activities_exp']) ? $faculty_data[0]['training_activities_exp'] : ''; ?>" maxlength="100">
                  </div>
                </div>
              </div>

              <?php if (count($log_data) > 0) { ?>
                <div class="col-xs-12">
                  <div class="box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Faculty Status Logs</h3>
                      <div class="box-tools pull-right">
                        <!-- Collapse Button -->
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"> <i class="fa fa-minus"></i> </button>
                      </div>
                      <!-- /.box-tools -->
                    </div>
                    <div class="box-body">
                      <table id="example1" class="table table-bordered dt-responsive table-hover" width="100%">
                        <thead>
                          <tr>
                            <th width="5%">Sr. No.</th>
                            <th width="15%">Status</th>
                            <th width="20%">Reason</th>
                            <th width="20%">Date/Time</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($log_data as $key => $value) { ?>
                            <tr>
                              <td><?php echo $key + 1; ?></td>
                              <td><?php echo $value['action_taken']; ?></td>
                              <td><?php echo $value['reason']; ?></td>
                              <td><?php echo $value['created_on']; ?></td>
                            </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              <?php } ?>
              
              <div class="col-sm-6 col-sm-offset-3">
                <div class="col-sm-12">
                  <center>
                    <?php if ($action == 'add' || $action == 'edit') { ?>
                      <button type="submit" class="btn btn-success" name="btn_submit" id="btn_submit" onclick="submit_form()">Submit</button>
                    <?php } ?>
                    <?php if ($request_from != 'Inspector' && ($action == 'add' || $action == 'edit' || $action == 'view')) { ?>
                      <a href="<?php echo base_url('iibfdra/Version_2/admin/faculty_master'); ?>" class="btn btn-warning mr-2">Back</a>
                    <?php }?>
                    <?php if($request_from == 'Inspector') {?>
                      <button type="button" class="btn btn-danger" name="btn_reset" id="btn_reset" onclick="self.close()">Close</button>
                    <?php }?>
                  </center>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</div>
</section>

<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="<?php echo base_url('assets/js/sweetalert2.all.min.js'); ?>" type="text/javascript" defer></script>

<script type="text/javascript">
  var base_url = '<?php echo base_url(); ?>';
  var action = '<?php echo $action; ?>';
  var institute_id = '<?php echo $institute_id; ?>';
  var faculty_id = '<?php echo isset($faculty_data[0]['faculty_id']) ? $faculty_data[0]['faculty_id'] : ''; ?>';

  if (action == 'view') {
    $('#faculty_form input[type="text"]').prop("disabled", true);
    $('.radiocls').prop("disabled", true);
  }

  var DRA_training_faculty_exp = '<?php echo $faculty_data[0]['DRA_training_faculty_exp']; ?>';
  if (DRA_training_faculty_exp != '') {
    $('#agency_association_period').css('display', 'block');
    $('#agency_association_period1').css('display', 'block');
  } else {
    $('#agency_association_period').css('display', 'none');
    $('#agency_association_period1').css('display', 'none');
  }

  $('.radiobtn').click(function() {
    var status = $(this).val();
    console.log(status);
    if (status == 'Active') {
      //$('#reason').text('');
      $('#reason_div').css('display', 'none');
    }
    /*else{
      $('#reason_div').css('display','none');
    }*/
  });

  function confirm_action(evt) {
    //alert('**'+msg+'**'+check_status+'**'+controller+'**'+prim_id+'**'+func);return false;
    var msg = msg || false;
    //evt.preventDefault();
    swal({
      title: 'Are you sure?',
      text: msg,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3f51b5',
      cancelButtonColor: '#ff4081',
      confirmButtonText: 'OK ',
      buttons: {
        confirm: {
          text: "OK",
          value: true,
          visible: true,
          className: "btn btn-primary",
          closeModal: true
        },
        cancel: {
          text: "Cancel",
          value: null,
          visible: true,
          className: "btn btn-danger",
          closeModal: true,
        }
      }
    }).then(OK => {
      if (OK.value) {
        $.ajax({
          url: base_url + "iibfdra/Version_2/admin/faculty_master/check_faculty_status",
          type: 'POST',
          data: {
            'ci_csrf_token': '',
            prim_id: faculty_id
          },
          success: function(response) {
            //alert(response); return false;
            if (response != '') {
              swal({
                title: 'Faculty Already Reffered.',
                text: response,
                icon: 'warning',
                type: 'warning',
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
              })
            } else {
              $('#reason_div').css('display', 'block');
              $('#reason_error').text('');
            }
          }
        })
      } else {
        $("#inactive").prop('checked', false);
      }
    });

  }

  $(".radiobtn").on("change", function() {
    $("#status_error").text("")
  });

  function change_status() {
    var err_cnt = 0;
    var status = $("input[name='radiobt']:checked").val();
    var reason = $('#reason').val();
    var pan_no_hidden = $('#pan_no_hidden').val();

    if (status == 'Inactive' && reason == '') {
      $('#reason_error').text('Please enter Reason');
      err_cnt = 1;
    } else if (status == 'Active') {
      $('#reason_error').text('');
      err_cnt = 0;
    }

    //alert('status--'+status);

    if (err_cnt == 0) {
      $("#status_error").text("")
      if (status == 'Inactive' || status == 'Active') {
        $("#status_error").text("")
        $.ajax({
          type: 'POST',
          url: base_url + "iibfdra/Version_2/admin/faculty_master/change_status",
          data: {
            faculty_id: faculty_id,
            status: status,
            reason: reason,
            pan_no_hidden: pan_no_hidden
          },
          dataType: "text",
          success: function(data) {
            console.log(data);
            var data1 = data.split('---');
            if (data1[0] == 1) {
              swal({
                title: 'Status Changed!',
                text: 'Faculty Status Changed Successfully...',
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
              }).then(OK => {
                location.reload();
              });
            } else if (data1[0] == 0) {
              swal({
                title: 'Error',
                text: 'Something went wrong...',
                icon: 'danger',
                type: 'danger',
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
              }).then(OK => {
                location.reload();
              });
            } else if (data1[0] == 2) {
              swal({
                title: 'Faculty Active',
                text: 'This Faculty is Active in ' + data1[1] + ' This Agency',
                icon: 'warning',
                type: 'warning',
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
              }).then(OK => {
                location.reload();
              });
            }
          }
        });
      } else {
        $("#status_error").text("Please select the status")
      }
    }

  }
</script>
<?php $this->load->view('iibfdra/Version_2/admin/includes/footer'); ?>