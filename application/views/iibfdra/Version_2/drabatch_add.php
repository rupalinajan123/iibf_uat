<!--custom style for datepicker dropdowns -->
<style>
  .example {
    width: 33%;
    min-width: 370px;
    /* padding: 15px;*/
    display: inline-block;
    box-sizing: border-box;
    /*text-align: center;*/
  }

  .example select {
    padding: 10px;
    background: #ffffff;
    border: 1px solid #CCCCCC;
    border-radius: 3px;
    margin: 0 3px;
  }

  .example select.invalid {
    color: #E9403C;
  }

  .mandatory-field,
  .required-spn {
    color: #F00;
  }


  option:disabled {
    color: #999;
  }

  .days_count {
    font-size: 14px;
  }

  .days_count span {
    font-size: 16px;
    font-weight: 900;
  }

  .time_count {
    font-size: 14px;
  }

  .time_count span {
    font-size: 16px;
    font-weight: 900;
  }

  .mytimecount {
    display: none;
  }

  .note {
    color: blue;
    font-size: 12px;
    line-height: 15px;
    display: inline-block;
    margin: 5px 0 0 0;
  }

  .note-error {
    color: rgb(185, 74, 72);
    font-size: 12px;
    line-height: 15px;
    display: inline-block;
    margin: 5px 0 0 0;
    vertical-align: top;
  }

  .parsley-errors-list>li {
    display: inline-block !important;
    font-size: 12px;
    line-height: 14px;
    margin: 2px 0 0 0 !important;
    padding: 0 !important;
  }

  .datepicker table tr td.disabled,
  .datepicker table tr td.disabled:hover {
    cursor: not-allowed;
  }

  #loading {
    display: none;
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 9999;
  }

  #loading>p {
    margin: 0 auto;
    width: 100%;
    height: 100%;
    position: absolute;
    top: 20%;
  }

  #loading>p>img {
    max-height: 250px;
    margin: 0 auto;
    display: block;
  }

  .form-group ul.parsley-errors-list li::before {
    content: "";
  }

  .datepicker table tr td.disabled, .datepicker table tr td.disabled:hover, .datepicker table tr td span.disabled, .datepicker table tr td span.disabled:hover { cursor: not-allowed; background: #eee; border: 1px solid #fff; }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css"> -->
<!-- <link rel="stylesheet" href="<?php //echo base_url('assets/css/timepicker.min.css'); 
                                  ?>">
<script src="<?php //echo base_url('assets/js/timepicker.min.js'); 
              ?>"></script>  -->
<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" /> -->

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script> -->

<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url('assets/js/differenceHours.js'); ?>"></script>

<div id="loading" class="divLoading" style="display: none;">
  <p><img src="<?php echo base_url(); ?>assets/images/loading-4.gif" /></p>
</div>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Training Batch Form</h1>
    <?php //echo $breadcrumb;
    ?>
  </section>

  <form class="form-horizontal" autocomplete="off" name="draExamAddFrm" id="draExamAddFrm" data-parsley-validate="parsley" method="post" enctype="multipart/form-data" onsubmit="return form_submit()">
    
    <?php //START : CHECK IF FORM IS SUBMITTED TWICE //ADDED BY SAGAR AND ANIL ON 2023-11-02 ?>
    <input type="hidden" name="custom_csrf_add_batches" value="<?php echo $custom_csrf_add_batches; ?>">
    <?php //END : CHECK IF FORM IS SUBMITTED TWICE //ADDED BY SAGAR AND ANIL ON 2023-11-02 ?>

    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <div class="box-header with-border">
                <h3 class="box-title">Add Training Batch Details </h3>
                <div class="pull-right"> <a href="<?php echo base_url(); ?>iibfdra/Version_2/TrainingBatches/" class="btn btn-warning">Back</a> </div>
              </div>
            </div>

            <div class="box-body">
              <?php if ($this->session->flashdata('error') != '') { ?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <?php echo $this->session->flashdata('error'); ?>
                </div>
              <?php }
              if ($this->session->flashdata('success') != '') { ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php }

              if (is_array($error_msg) && count($error_msg) > 0) {
                foreach ($error_msg as $key => $value) { ?>
                  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $value; ?>
                  </div>

              <?php }
            } 
            elseif (isset($error_msg) && $error_msg != '') { ?>
              <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $error_msg; ?>
                  </div>
            <?php } ?>  

              <div class="form-group" id="type_div">
                <label for="batch_type" class="col-sm-3 control-label">Batch Type <span class="mandatory-field">*</span></label>
                <div class="col-sm-3">
                  <label style="margin-right: 10px;"><input type="radio" class="minimal batch-limit" id="batch_type" name="hours" value="50" <?php if (set_value('hours') == '50') { echo 'checked="checked"'; } ?> style="margin-top: 5px;vertical-align: top;">
                    50 Hour</label>
                  <label><input type="radio" class="minimal batch-limit" id="batch_type" name="hours" value="100" <?php if (set_value('hours') == '100' || set_value('hours') == '') { echo 'checked="checked"'; } ?> style="margin-top: 5px;vertical-align: top;">
                    100 Hour</label>
                    <br>
                  <span class="note-error error"></span>
                </div>
              </div>

              <div class="form-group">
                <label for="center" class="col-sm-3 control-label">Center Name<span class="mandatory-field">*</span></label>
                <div class="col-sm-5">
                  <select class="form-control netTime" id="center_id" name="center_id" required data-parsley-trigger="focusout" onchange="return validateDate();">
                    <option value="">Select </option>
                    <?php $validity_to = $validity_from = '';
                    if (!empty($agency_center)) {
                      foreach ($agency_center as $val) {

                        if ($val['city_name'] != "" || $val['city_name'] != NULL) {
                          $val['location_name'] = $val['city_name'];
                        } else {
                          $val['location_name'] = $val['location_name'];
                        }

                        if ($val['renew_pay_status'] != "") { 
                          if (($val['renew_type'] == 'free' && $val['center_type'] == 'R') || ($val['renew_pay_status'] == 1 && $val['center_type'] == 'R')) { ?>
                            <?php
                            if ($val['center_validity_from'] == '' && $val['center_validity_to'] == '' && $val['pay_status'] == 1) {
                              $_SESSION['validity_to'] = $val['center_validity_to']; } 
                            elseif ($val['center_status'] == 'A' && $val['pay_status'] == 0) { } 
                            elseif ( $val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d') && $val['center_validity_from'] <= date('Y-m-d') && $val['center_validity_to'] > date('Y-m-d', strtotime($date_check)) && $val['center_validity_from'] < date('Y-m-d', strtotime($date_check)) ) 
                            {
                              $validity_to = $val['center_validity_to'];
                              $validity_from = (date('Y-m-d', strtotime("+6 day", strtotime($val['center_validity_from']))));
                            ?>
                              <option <?php echo (set_value('center_id') == $val['center_id']) ? " selected=' selected'" : "" ?> value="<?php echo $val['center_id']; ?>" data-todate="<?php echo $val['center_validity_to']; ?>"><?php echo $val['location_name']; if($val['center_id'] == '3039') { echo ' - Wagholi'; } ?> </option>

                            <?php } elseif ($val['center_validity_to'] < date('Y-m-d')  && $val['pay_status'] == 1) { } 
                            elseif ($val['center_status'] == 'R') { } 
                            elseif ($val['center_status'] == 'IR') { } 
                            elseif ($val['center_status'] == 'AR') { } 
                            elseif ($val['center_validity_from'] > date('Y-m-d')) { }
                          } 
                          elseif (($val['renew_pay_status'] == 0 || $val['renew_pay_status'] == 2) && $val['center_type'] == 'R' && $val['renew_type'] != 'free') { } 
                          elseif (($val['renew_pay_status'] == 0 || $val['renew_pay_status'] == 2) && $val['center_type'] == 'T') { } 
                          elseif ($val['renew_pay_status'] == 1 && $val['center_type'] == 'T') {
                            if ($val['center_validity_from'] == '' && $val['center_validity_to'] == '' && $val['pay_status'] == 1) 
                            {
                              $_SESSION['validity_to'] = $val['center_validity_to']; 
                            } 
                            elseif ($val['center_status'] == 'A' && $val['pay_status'] == 0) { } 
                            elseif ($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d', strtotime($date_check)) && $val['center_validity_from'] < date('Y-m-d', strtotime($date_check)) ) {
                              $validity_to = $val['center_validity_to'];
                              $validity_from = (date('Y-m-d', strtotime("+6 day", strtotime($val['center_validity_from']))));
                            ?>
                              <option <?php echo (set_value('center_id') == $val['center_id']) ? " selected=' selected'" : "" ?> value="<?php echo $val['center_id']; ?>" data-todate="<?php echo $val['center_validity_to']; ?>"><?php echo $val['location_name']; if($val['center_id'] == '3039') { echo ' - Wagholi'; } ?> </option>

                            <?php } 
                            elseif ($val['center_validity_to'] < date('Y-m-d')  && $val['pay_status'] == 1) { } 
                            elseif ($val['center_status'] == 'R') { } 
                            elseif ($val['center_status'] == 'IR') { } 
                            elseif ($val['center_status'] == 'AR') { ?>
                              <option value="<?php echo $val['center_id'] ?>" data-todate="<?php echo $val['center_validity_to']; ?>">
                            <?php echo $val['location_name'] . ' ( Approve by Recommender. )'; ?></option>
                            <?php } elseif ($val['center_status'] == 'A' && $val['pay_status'] == 2) { } 
                            elseif ($val['center_validity_from'] > date('Y-m-d')) { }
                          }
                        } //not empty renew payemnt status
                        else { //empty renew payemnt status                                                                               

                          if ($val['center_validity_from'] == '' && $val['center_validity_to'] == '' && $val['pay_status'] == 1) {
                            $_SESSION['validity_to'] = $val['center_validity_to']; } 
                          elseif ($val['center_status'] == 'A' && $val['pay_status'] == 0) { } 
                          elseif ($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d', strtotime($date_check)) && $val['center_validity_from'] < date('Y-m-d', strtotime($date_check)) ) {
                            $validity_to = $val['center_validity_to'];
                            $validity_from = (date('Y-m-d', strtotime("+6 day", strtotime($val['center_validity_from']))));
                          ?>
                            <option <?php echo (set_value('center_id') == $val['center_id']) ? " selected=' selected'" : "" ?> value="<?php echo $val['center_id']; ?>" data-todate="<?php echo $val['center_validity_to']; ?>"><?php echo $val['location_name']; if($val['center_id'] == '3039') { echo ' - Wagholi'; } ?> </option>

                          <?php } 
                          elseif ($val['center_validity_to'] < date('Y-m-d')  && $val['pay_status'] == 1) { } 
                          elseif ($val['center_status'] == 'R') { } 
                          elseif ($val['center_status'] == 'IR') { } 
                          elseif ($val['center_status'] == 'AR') { } 
                          elseif ($val['center_status'] == 'A' && $val['pay_status'] == 2) { } 
                            elseif ($val['center_validity_from'] > date('Y-m-d')) { }
                        }
                      }
                    }
                    ?>

                  </select>

                  <div style="display: none;" id="validdate"><?php echo $validity_to; ?></div>
                  <input type="hidden" autocomplete="false" value="<?php echo $validity_to; ?>" id='validity_to' name='validity_to' />
                  <input type="hidden" autocomplete="false" value="<?php echo $validity_from; ?>" id='validity_from' name='validity_from' />
                </div>
              </div>

              <!--<div class="form-group">
                                    <label for="incpector" class="col-sm-3 control-label">Inspector Name <span class="mandatory-field">*</span></label>
                                    <div class="col-sm-5">
                                        <select class="form-control" id="inspector_id" name="inspector_id" required >
                                            <option value="">Select</option>
                                           
                                        </select>
                                      
                                    </div>
                            </div> -->

              <?php /*?><div class="form-group">
                                <label for="batch_name" class="col-sm-3 control-label">Batch Name <span class="mandatory-field">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="batch_name" name="batch_name" placeholder="Batch Name" required  value="<?php echo set_value('batch_name');?>" data-parsley-maxlength="30" autocomplete="off" maxlength="30">
                                    <span class="error"><?php //echo form_error('middlename');?></span>
                                </div><!-- (Max 30 Characters)  -->
                            </div><?php */ ?>              

              <div class="form-group">
                <label for="training_period" class="col-sm-3 control-label">Batch Training Period <span class="mandatory-field">*</span> </label>
                <div class="col-sm-3">
                  From
                  <input type="text" class="form-control period netTime" id="batch_from" name="batch_from_date" placeholder="Training From Date" required value="<?php echo set_value('batch_from_date'); ?>" autocomplete="off" data-parsley-errors-container="#batch_from_error"/>
                  <?php /*<span class="note">Note: Please Select From Date greater than <?php echo date('Y-m-d', strtotime("-1day", strtotime($date_check))); ?></span> */ ?>
                  <span class="note">Note: Please Select the Date between <?php echo date('Y-m-d', strtotime("0day", strtotime($date_check))); ?> and <?php echo date('Y-m-d', strtotime("+4day", strtotime($date_check))); ?></span>
                  <span class="note-error" id="batch_from_error"></span>
                </div>
                <div class="col-sm-3">
                  To
                  <input type="text" class="form-control period netTime" id="batch_to" name="batch_to_date" placeholder="Training To Date" required value="<?php echo set_value('batch_to_date'); ?>" autocomplete="off" onchange="chk_days()" />
                  <span class="note-error error batch-limit-error" id="batch_to_error"></span>
                </div>
                <div class="col-sm-3">
                  Gross Training Days
                  <input type="text" id="gross_days" name="gross_days" class="form-control" value="0 Days" readonly="readonly" autocomplete="off">
                  <span class="note" id="gross_days_note">Note: Gross Training Days should be between 8 to 20 days.</span>
                  </br>
                  <span class="note-error" id="gross_days_error"></span>
                </div>
              </div>

              <div class="form-group">
                <label for="holidays" class="col-sm-3 control-label">Holiday(s)</label>
                <div class="col-sm-6">
                  Select Holiday(s)
                  <!-- <select class="form-control" id="holidays"  name="holidays">
                                    </select> Select Holiday(s)-->
                  <input type="text" class="form-control netTime" id="holidays" name="holidays" placeholder="Select for Holidays" onChange="chk_net_days()" autocomplete="off" value="<?php echo set_value('holidays'); ?>" data-parsley-trigger="focusin focusout"/>
                  <span class="note-error" id="holidays_error"></span>
                </div>

                <div class="col-sm-3">
                  Net Training Days
                  <input type="text" id="net_days" name="net_days" class="form-control" value="" placeholder="Net Training Days" readonly="readonly" autocomplete="off">
                </div>
              </div>

              <div class="form-group datepairtimes">
                <label for="timing_of_training" class="col-sm-3 control-label">Daily Training Time <span class="mandatory-field">*</span></label>
                <div class="col-sm-2">
                  From
                  <input type="text" class="form-control time start netTime time_8" id="time1" name="timing_from" required value="00:00" autocomplete="off" maxlength="10"/>
                </div>
                <div class="col-sm-2">
                  To
                  <input type="text" class="form-control time end timing_to netTime time_8 time_7" id="time2" name="timing_to" required value="00:00" autocomplete="off" maxlength="10"/>
                </div>
                <div class="col-sm-3">
                  Gross Training Time Per Day
                  <input type="text" id="gross_time" class="form-control netTime" name="gross_time" value="00:00" readonly="readonly" autocomplete="off" onchange="net_time_validate()">
                  <span class="note">Note : Gross Time should be less than or equal to 8 Hours.</span>
                  </br>
                  <span class="note-error" id="gross_time_error"></span>
                </div>
              </div>

              <div class="form-group datepairbreaktimes">
                  <label for="timing_of_training" class="col-sm-3 control-label">Break Time 1 <span class="mandatory-field">*</span></label>
                  <div class="col-sm-2">
                      From
                      <input type="text" class="form-control break-time" id="time1_from" name="time1_from" required value="" autocomplete="off" maxlength="10"/>
                  </div>
                  <div class="col-sm-2">
                      To
                      <input type="text" class="form-control break-time" id="time1_to" name="time1_to" required value="" autocomplete="off" maxlength="10"/>
                  </div>
                  <div class="col-sm-3">
                      Duration
                      <input type="text"  id="brk_time1" class="form-control netTime breakTime time_7" name="brk_time1" value="0" readonly>
                       <!-- readonly="readonly" autocomplete="off" -->
                      <span class="note-error" id="gross_time_error"></span>
                  </div>
              </div>

              <div class="form-group datepairbreaktimes">
                  <label for="timing_of_training" class="col-sm-3 control-label">Break Time 2</label>
                  <div class="col-sm-2">
                      From
                      <input type="text" class="form-control break-time" id="time2_from" name="time2_from" value="" autocomplete="off" maxlength="10"/>
                  </div>
                  <div class="col-sm-2">
                      To
                      <input type="text" class="form-control break-time" id="time2_to" name="time2_to" value="" autocomplete="off" maxlength="10"/>
                  </div>
                  <div class="col-sm-3">
                      Duration
                      <input type="text"  id="brk_time2" class="form-control netTime breakTime time_7" name="brk_time2" value="0" readonly>
                       <!-- readonly="readonly" autocomplete="off" -->
                      <span class="note-error" id="gross_time_error"></span>
                  </div>
              </div>

              <div class="form-group datepairbreaktimes">
                  <label for="timing_of_training" class="col-sm-3 control-label">Break Time 3</label>
                  <div class="col-sm-2">
                      From
                      <input type="text" class="form-control break-time" id="time3_from" name="time3_from" value="" autocomplete="off" maxlength="10"/>
                  </div>
                  <div class="col-sm-2">
                      To
                      <input type="text" class="form-control break-time" id="time3_to" name="time3_to" value="" autocomplete="off" maxlength="10"/>
                  </div>
                  <div class="col-sm-3">
                      Duration
                      <input type="text"  id="brk_time3" class="form-control netTime breakTime time_7" name="brk_time3" value="0" readonly>
                       <!-- readonly="readonly" autocomplete="off" -->
                      <span class="note-error" id="gross_time_error"></span>
                  </div>
              </div>

              <!-- <div class="form-group">
                <label for="holidays" class="col-sm-3 control-label">Daily Break Times <span class="mandatory-field">*</span></label>
                <div class="col-sm-2">
                  Break Time 1
                  <select class="form-control netTime breakTime time_7" id="brk_time1" name="brk_time1" required data-parsley-trigger="focusout">
                    <option value="">Select Break Time1</option>
                    <?php foreach ($timeArr as $key => $value) { ?>
                      <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-sm-2">
                  Break Time 2
                  <select class="form-control netTime breakTime time_7" id="brk_time2" name="brk_time2" required data-parsley-trigger="focusout">
                    <option value="">Select Break Time2</option>
                    <?php foreach ($timeArr as $key => $value) { ?>
                      <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-sm-2">
                  Break Time 3
                  <select class="form-control netTime breakTime time_7" id="brk_time3" name="brk_time3" required data-parsley-trigger="focusout">
                    <option value="">Select Break Time3</option>
                    <?php foreach ($timeArr as $key => $value) { ?>
                      <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                  </select>
                  <span class="break_time_error"></span>
                </div>
                <div class="col-sm-3">
                  Total Break Time
                  <input type="text" class="form-control time_7" name="total_brk_time" id="total_brk_time" placeholder="Total Break Time" value="00:00" readonly="readonly" autocomplete="off">
                  <span class="note">Note : Total Break Time should be less than or equal to 120 minutes.</span>
                  </br>
                  <span class="note-error" id="total_break_time_error"></span>
                </div>
              </div> -->

              <div class="form-group">
                <label for="net timings" class="col-sm-3 control-label"></label>

                <div class="col-sm-3">
                  Total Break Time
                  <input type="text" class="form-control time_7" name="total_brk_time" id="total_brk_time" placeholder="Total Break Time" value="00:00" readonly="readonly" autocomplete="off">
                  <span class="note">Note : Total Break Time should be less than or equal to 120 minutes.</span>
                  </br>
                  <span class="note-error" id="total_break_time_error"></span>
                </div>

                <div class="col-sm-3">
                  Net Training Time Per Day
                  <input type="text" id="net_time" class="form-control net_time_cls" name="net_time" value="00:00" readonly="readonly" autocomplete="off" placeholder="Net Training Time Per Day">
                  <span class="note">Note : Net Time should be less than or equal to 7 Hours.</span>
                  </br>
                  <span class="note-error" id="net_time_error"></span>
                </div>

                <div class="col-sm-3">
                  Net Training Time of the Batch
                  <input type="text" class="form-control" name="total_net_time" id="total_net_time" placeholder="Net Training Time of the Batch" value="00:00" readonly="readonly" autocomplete="off">
                  <span class="note" id="total_net_time_note">Note: Total Net Training Time should be greater than equal to 100</span>
                  </br>
                  <span class="note-error" id="total_net_time_error"></span>
                </div>
                <span class="note-error" id="time_error"></span>
              </div>

              <div class="form-group">
                <label for="training_medium" class="col-sm-3 control-label">Training Language <span class="mandatory-field">*</span></label>
                <div class="col-sm-5">
                  <select class="form-control" id="training_medium" name="training_medium" required data-parsley-trigger="focusout">
                    <option value="">Select</option>
                    <?php if (count($medium_master) > 0) {
                      foreach ($medium_master as $medium) {     ?>
                        <option value="<?php echo $medium['medium_description']; ?>" <?php if (set_value('training_medium') == trim($medium['medium_description'])) { echo 'selected="selected"'; } ?>><?php echo $medium['medium_description']; ?></option>
                    <?php }
                    } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-3 control-label">Tentative No. of Candidates <span class="mandatory-field">*</span></label>
                <div class="col-sm-2" id="tenth_pass_div">
                  10th pass
                  <select class="form-control" id="tenth_pass_candidates" name="tenth_pass_candidates" onchange="calc_candidates()" data-parsley-trigger="focusout">
                    <option value="">Select Candidate</option>
                    <?php for ($i = 0; $i <= 35; $i++) { ?>
                      <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                  </select>
                  <span class="tenth_pass_candidates_error"></span>
                </div>
                <div class="col-sm-2" id="twelth_pass_div">
                  12th pass
                  <select class="form-control" id="twelth_pass_candidates" name="twelth_pass_candidates" onchange="calc_candidates()" data-parsley-trigger="focusout">
                    <option value="">Select Candidate</option>
                    <?php for ($i = 0; $i <= 35; $i++) { ?>
                      <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                  </select>
                  <span class="twelth_pass_candidates_error"></span>
                </div>
                <div class="col-sm-2">
                  Graduates
                  <select class="form-control" id="graduate_candidates" name="graduate_candidates" onchange="calc_candidates()" data-parsley-trigger="focusout">
                    <option value="">Select Candidate</option>
                    <?php for ($i = 0; $i <= 35; $i++) { ?>
                      <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                  </select>
                  <span class="graduate_error"></span>
                </div>
                <div class="col-sm-2">
                  Total Candidates <span class="mandatory-field">*</span>
                  <input type="text" class="form-control" required id="total_candidates" name="total_candidates" placeholder="Total Candidates" value="" readonly="readonly" autocomplete="off">
                  <span class="note-error" id="total_candidates_error"></span>
                </div>
              </div>

              <?php /*?>
                            <div class="form-group">
                                <label for="participate_yes_no" class="col-sm-7 control-label">Whether Graduate Candidates will participate in the entire training duration of 100 Hours</label>
                                <div class="col-sm-3">
                                    <input type="radio" class="radiocls" id="participate_yes_no" name="participate_yes_no" value="Yes">Yes
                                    <input checked type="radio" class="radiocls" id="participate_yes_no" name="participate_yes_no" required value="No">No
                                    <span class="error"><?php //echo form_error('gender');?></span>
                                </div>
                            </div>
                            <?php */ ?>
                          <?php /*?>
                            <div class="form-group" id="training_prd_full_capacity_div">
                                <label for="training_prd_full_capacity" class="col-sm-3 control-label">Training Period with Full Capacity (Including Graduate Candidates)</label>
                                <div class="col-sm-4">
                                    1st Half of the Training Period                     
                                    <input type="text" class="form-control" id="first_half" name="first_half" placeholder="1st 50 training hours" value="">
                                <span class="first_half_error"></span>
                                </div> 
                                <div class="col-sm-4">
                                    2nd Half of the Training Period
                                   <input type="text" class="form-control" id="sec_half" name="sec_half" placeholder="2nd 50 training hours" value="">
                                <span class="sec_half_error"></span>
                                </div> 
                            </div>
                             <?php */ ?>

              <div class="form-group">
                <label for="faculty_name" class="col-sm-3 control-label">Faculty Details <span class="mandatory-field">*</span></label>
                <div class="col-sm-4">
                  1st Faculty (For Basic Banking) <span class="mandatory-field">*</span>
                  <select class="form-control faculty-section" id="first_faculty" data-error="first_faculty_error" name="first_faculty" onchange="get_faculty(event)" required="" data-parsley-trigger="focusout">
                    <option value="">Select Faculty</option>
                    <?php foreach ($basic_faculty_data as $key => $value) { 
                      $facLanguages = $value['languages']!='' ? '_'.str_replace(',', '_', $value['languages']):'';
                      ?>
                      <option value="<?php echo $value['faculty_id']; ?>" data-code="<?php echo $value['faculty_code']; ?>"><?php echo $value['faculty_code'].'_'.$value['faculty_name'].$facLanguages; ?></option>
                    <?php } ?>
                  </select>
                  <span class="note-error fac-errr" id="first_faculty_error"></span>
                </div>

                <div class="col-sm-4">
                  2nd Faculty (For Soft Skill in Banking) <span class="mandatory-field">*</span>
                  <select class="form-control faculty-section" id="sec_faculty" name="sec_faculty" data-error="sec_faculty_error" last_val="" onchange="get_faculty(event)" required="" data-parsley-trigger="focusout">
                    <option value="">Select Faculty</option>
                    <?php foreach ($soft_faculty_data as $key => $value) { 
                      $facLanguages = $value['languages']!='' ? '_'.str_replace(',', '_', $value['languages']):'';
                    ?>
                      <option value="<?php echo $value['faculty_id']; ?>" data-code="<?php echo $value['faculty_code']; ?>"><?php echo $value['faculty_code'].'_'.$value['faculty_name'].$facLanguages; ?></option>
                    <?php } ?>
                  </select>
                  <span class="note-error fac-errr" id="sec_faculty_error"></span>
                </div>
              </div>

              <div class="form-group">
                <label for="training_prd_full_capacity" class="col-sm-3 control-label"></label>
                <div class="col-sm-4">
                  Additional Faculty I (For Basic Banking)
                  <select class="form-control faculty-section" id="additional_first_faculty" data-error="additional_first_faculty_error" last_val="" name="additional_first_faculty" onchange="get_faculty(event)" data-parsley-trigger="focusout">
                    <option value="">Select Faculty</option>
                    <?php foreach ($basic_faculty_data as $key => $value) { 
                      $facLanguages = $value['languages']!='' ? '_'.str_replace(',', '_', $value['languages']):'';
                    ?>
                      <option value="<?php echo $value['faculty_id']; ?>" data-code="<?php echo $value['faculty_code']; ?>"><?php echo $value['faculty_code'].'_'.$value['faculty_name'].$facLanguages; ?></option>
                    <?php } ?>
                  </select>
                  <span class="note-error fac-errr" id="additional_first_faculty_error"></span>
                </div>

                <div class="col-sm-4">
                  Additional Faculty II (For Soft Skill in Banking)
                  <select class="form-control faculty-section" id="additional_sec_faculty" data-error="additional_sec_faculty_error" last_val="" name="additional_sec_faculty" onchange="get_faculty(event)" data-parsley-trigger="focusout">
                    <option value="">Select Faculty</option>
                    <?php foreach ($soft_faculty_data as $key => $value) { 
                      $facLanguages = $value['languages']!='' ? '_'.str_replace(',', '_', $value['languages']):'';
                    ?>
                      <option value="<?php echo $value['faculty_id']; ?>" data-code="<?php echo $value['faculty_code']; ?>"><?php echo $value['faculty_code'].'_'.$value['faculty_name'].$facLanguages; ?></option>
                    <?php } ?>
                  </select>
                  <span class="note-error fac-errr" id="additional_sec_faculty_error"></span>
                </div>
              </div>

              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Upload Training Schedule<span style="color:#F00">*</span></label>
                <div class="col-sm-5">
                  <input type="file" class="form-control" name="training_schedule" id="training_schedule" data-parsley-required data-parsley-errors-container="#training_schedule_error" data-parsley-trigger="focusin focusout" onchange="validateDoc(event, 'training_schedule_error')">
                  <span class="note" id="training_schedule_note">Note: Please Upload only .txt, .doc, .docx, .pdf Files with size upto 5 MB</span></br>
                  <span class="note-error" id="training_schedule_error"> <?php echo form_error('training_schedule'); ?></span>
                </div>
              </div>

              <div class="box-header with-border">
                <div class="col-sm-12">
                  <h3 class="box-title" style="color:#333; width:100%; ">
                    <center> &nbsp;<b>Venue of Training Batch</b> </center>
                  </h3>
                </div>
                <br>
              </div>

              <?php if ($inst_registration_info[0]['main_city'] != "") {
                $city = $inst_registration_info[0]['city'];
              } else {
                $city = $inst_registration_info[0]['main_city'];
              }
              ?>

              <div class="form-group" style="margin-top:8px;">
                <label for="state" class="col-sm-3 control-label">State <span class="mandatory-field">*</span></label>
                <div class="col-sm-5">
                  <input class="form-control" id="cstate" value="<?php echo set_value('csstate'); ?>" placeholder="State" readonly name="cstate" disabled>
                  <input type="hidden" autocomplete="false" class="form-control" name="state_code" id="ccstate" value="">
                  <input type="hidden" autocomplete="false" class="form-control" id="state" value="" name="state">
                  <span class="error"></span>
                </div>
              </div>

              <div class="form-group">
                <label for="district" class="col-sm-3 control-label">District <span class="mandatory-field">*</span></label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" readonly id="cdistrict" name="cdistrict" readonly placeholder="District" autocomplete="off" value="<?php echo set_value('cdistrict'); ?>">
                  <input type="hidden" class="form-control" readonly id="district" name="district" value="">
                  <span class="error"></span>
                </div>
              </div>

              <div class="form-group">

                <label for="city" class="col-sm-3 control-label">City <span class="mandatory-field">*</span></label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" readonly placeholder="City" id="ccity" name="cscity" value="<?php echo set_value('cscity'); ?>" autocomplete="off">

                  <input type="hidden" autocomplete="off" class="form-control" readonly placeholder="City" id="city" name="city_code" value="">
                  <span class="error"></span>
                </div>

                <label for="pincode" class="col-sm-2 control-label">Pincode <span class="mandatory-field">*</span></label>
                <div class="col-sm-2">
                  <input type="text" placeholder="Pincode" class="form-control" name="cpincode" id="cpincode" data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-dracheckpin autocomplete="off" data-parsley-trigger-after-failure="focusout" onkeypress="return isNumber(event)" value="" required autocomplete="off" readonly>
                  <span class="error"></span>
                </div>
              </div>

              <div class="form-group">
                <label for="address_line1" class="col-sm-3 control-label">Address</span> <span class="mandatory-field">*</span></label>
                <div class="col-sm-5">
                  Line 1</span> <span class="mandatory-field">*</span>
                  <input type="text" class="form-control address_fields" id="addressline1" name="addressline1" required placeholder="Address line 1" value="<?php echo set_value('addressline1'); ?>" data-parsley-maxlength="30" autocomplete="off" maxlength="30" readonly>
                  <span class="error"></span>
                </div>
                <div class="col-sm-4">
                  Line 2
                  <input type="text" class="form-control address_fields" id="addressline2" name="addressline2" placeholder="Address line 2" data-parsley-maxlength="30" value="<?php echo set_value('addressline2'); ?>" autocomplete="off" maxlength="30" readonly>
                  <span class="error"></span>
                </div>
              </div>

              <div class="form-group">
                <label for="address_line3" class="col-sm-3 control-label address_fields"></label>
                <div class="col-sm-5">
                  Line 3
                  <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line 3" data-parsley-maxlength="30" value="<?php echo set_value('addressline3'); ?>" autocomplete="off" maxlength="30" readonly>
                  <span class="error"></span>
                </div>
                <div class="col-sm-4">
                  Line 4
                  <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line 4" data-parsley-maxlength="30" value="<?php echo set_value('addressline4'); ?>" autocomplete="off" maxlength="30" readonly>
                  <span class="error"></span>
                </div>
              </div>

              <div class="form-group">
                <label for="last_name" class="col-sm-3 control-label">Batch Coordinator Details <span class="mandatory-field">*</span></label>
                <div class="col-sm-5">
                  Name <span class="mandatory-field">*</span>
                  <input type="text" class="form-control" id="contact_person_name" name="contact_person_name" required placeholder="Contact Person Name" value="<?php echo set_value('contact_person_name'); ?>" data-parsley-maxlength="30" data-parsley-trigger="focusin focusout" autocomplete="off" maxlength="30" onkeypress="return onlyAlphabets(event)">
                  <span class="error"></span>
                </div>
                <div class="col-sm-4">
                  Mobile No. <span class="mandatory-field">*</span>
                  <input type="tel" class="form-control" id="contact_person_phone" name="contact_person_phone" placeholder="Contact Person Mobile No." data-parsley-type="number" data-parsley-minlength="10" data-parsley-maxlength="10" value="<?php echo set_value('contact_person_phone'); ?>" required size="10" maxlength="10" data-parsley-trigger="focusin focusout" onkeypress="return isNumber(event)" autocomplete="off">
                  <span class="error"></span>
                </div>
              </div>

              <div class="form-group">
                <label for="last_name" class="col-sm-3 control-label">Alternative Contact Person Name and Contact Number </label>
                <div class="col-sm-5">
                  Name
                  <input type="text" class="form-control" id="alt_contact_person_name" name="alt_contact_person_name" placeholder="Contact Person Name" value="<?php echo set_value('alt_contact_person_name'); ?>" data-parsley-maxlength="30" data-parsley-trigger="focusin focusout" autocomplete="off" maxlength="30" onkeypress="return onlyAlphabets(event)">
                  <span class="error"></span>
                </div>
                <div class="col-sm-4">
                  Mobile No.
                  <input type="text" class="form-control" id="alt_contact_person_phone" name="alt_contact_person_phone" placeholder="Alternative Contact Person Mobile No." data-parsley-type="number" data-parsley-minlength="10" data-parsley-maxlength="10" value="<?php echo set_value('alt_contact_person_phone'); ?>" size="10" maxlength="10" data-parsley-trigger="focusin focusout" onkeypress="return isNumber(event)" autocomplete="false">
                  <span class="error"></span>
                </div>
              </div>

              <div class="form-group">
                <label for="name_of_bank " class="col-sm-3 control-label">Name of Bank / Agency / Mixed (Source of Candidates) <span class="mandatory-field">*</span></label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="name_of_bank" name="name_of_bank" placeholder="Name Of Bank/ NBFC/ Agencies" required value="<?php echo set_value('name_of_bank'); ?>" autocomplete="off" data-parsley-maxlength="30" data-parsley-trigger="focusin focusout" maxlength="30" onkeypress="return onlyAlphabets(event)">
                  <span class="note" id="addressline1">Note: You can Enter maximum 30 Characters</span></br>
                  <span class="error"></span>
                </div>
              </div>

              <div class="form-group">
                <label for="name_of_bank " class="col-sm-3 control-label">Remark <span class="mandatory-field"></span></label>
                <div class="col-sm-8">
                  <textarea style="width:100%; text-align:left;  resize: none;" name="remarks" id="remarks"  maxlength="1000" placeholder=" Remark" ><?php echo set_value('remarks');?></textarea>
                  <span class="note" id="addressline1">Note: You can Enter maximum 1000 Characters</span></br>
                  <span class="error"></span>
                </div>
              </div>

              <?php /*?>
                            <div class="form-group">
                                <label for="remarks " class="col-sm-3 control-label">Explaination / Remarks on the batch, if any </label>
                                <div class="col-sm-6">
                                    <textarea style="width:100%; text-align:left;" name="remarks" id="remarks"  maxlength="1000" placeholder="Additional Information, if any"><?php echo set_value('remarks');?></textarea>
                                    <span class="note" id="addressline1">Note: You can Enter maximum 1000 Characters</span></br>
                                </div>
                            </div>
                            <?php */ ?>


              <!--########## START : CODE ADDED BY SAGAR ON 18-08-2020 ###################-->
              <div class="box-header with-border">
                <div class="col-sm-12">
                  <h3 class="box-title" style="color:#333; width:100%; ">
                    <center> &nbsp;<b>Offline / Online Batch</b> </center>
                  </h3>
                </div><br>
              </div>


              <div class="form-group">
                <label for="" class="col-sm-3 control-label">Batch Infrastructure <span class="mandatory-field">*</span></label>
                <div class="col-sm-5">
                  <label class="radio-inline" style="padding-top: 0;margin-top: -8px; margin-right:20px;">
                    <input type="radio" name="batch_online_offline_flag" id="batch_online_offline_flag0" checked <?php echo set_radio('batch_online_offline_flag', '0'); ?> value="0" required onchange="batch_online_users_show(this.value)"> Offline
                  </label>
                  <label class="radio-inline" style="padding-top: 0;margin-top: -8px;">
                    <input type="radio" name="batch_online_offline_flag" id="batch_online_offline_flag1" <?php echo set_radio('batch_online_offline_flag', '1'); ?> value="1" required onchange="batch_online_users_show(this.value)"> Online
                  </label>
                  <span class="error"></span>
                </div>
              </div>

              <?php
              $login_id_arr = $login_pass_arr = array();
              if (set_value('batch_online_login_ids') != "") {
                $login_id_arr = set_value('batch_online_login_ids');
              }
              if (set_value('batch_online_login_pass') != "") {
                $login_pass_arr = set_value('batch_online_login_pass');
              }
              ?>

              <div id="batch_ofline_users_outer" style="display:block;">
                <div class="form-group">
                  <label class="col-sm-3 control-label">CCTV Link <span class="mandatory-field">*</span></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="cctv_link" name="cctv_link" value="<?php echo set_value('cctv_link'); ?>" autocomplete="off" maxlength="500" placeholder="" data-parsley-trigger="focusin focusout" required>
                    <span class="note" id="cctv_link">Note: Please Enter link with mentioned format https://iibf.org.in/</span></br>
                    <span class="note-error" id="err_cctv_link"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label for="center" class="col-sm-3 control-label">Center Name<span class="mandatory-field">*</span></label>
                  <div class="col-sm-5">
                    <select class="form-control netTime" id="batch_center_id" name="batch_center_id" required data-parsley-trigger="focusout">
                      <option value="">Select </option>
                      <?php $validity_to = $validity_from = '';
                      if (!empty($agency_center)) {
                        foreach ($agency_center as $val) {

                          if ($val['city_name'] != "" || $val['city_name'] != NULL) {
                            $val['location_name'] = $val['city_name'];
                          } else {
                            $val['location_name'] = $val['location_name'];
                          }

                          if ($val['renew_pay_status'] != "") { 
                            if (($val['renew_type'] == 'free' && $val['center_type'] == 'R') || ($val['renew_pay_status'] == 1 && $val['center_type'] == 'R')) { ?>
                              <?php
                              if ($val['center_validity_from'] == '' && $val['center_validity_to'] == '' && $val['pay_status'] == 1) {
                                $_SESSION['validity_to'] = $val['center_validity_to']; } 
                              elseif ($val['center_status'] == 'A' && $val['pay_status'] == 0) { } 
                              elseif ( $val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d') && $val['center_validity_from'] <= date('Y-m-d') && $val['center_validity_to'] > date('Y-m-d', strtotime($date_check)) && $val['center_validity_from'] < date('Y-m-d', strtotime($date_check)) ) 
                              {
                                $validity_to = $val['center_validity_to'];
                                $validity_from = (date('Y-m-d', strtotime("+6 day", strtotime($val['center_validity_from']))));
                              ?>
                                <option <?php echo (set_value('center_id') == $val['center_id']) ? " selected=' selected'" : "" ?> value="<?php echo $val['center_id']; ?>" data-todate="<?php echo $val['center_validity_to']; ?>"><?php echo $val['location_name']; if($val['center_id'] == '3039') { echo ' - Wagholi'; } ?> </option>

                              <?php } elseif ($val['center_validity_to'] < date('Y-m-d')  && $val['pay_status'] == 1) { } 
                              elseif ($val['center_status'] == 'R') { } 
                              elseif ($val['center_status'] == 'IR') { } 
                              elseif ($val['center_status'] == 'AR') { } 
                              elseif ($val['center_validity_from'] > date('Y-m-d')) { }
                            } 
                            elseif (($val['renew_pay_status'] == 0 || $val['renew_pay_status'] == 2) && $val['center_type'] == 'R' && $val['renew_type'] != 'free') { } 
                            elseif (($val['renew_pay_status'] == 0 || $val['renew_pay_status'] == 2) && $val['center_type'] == 'T') { } 
                            elseif ($val['renew_pay_status'] == 1 && $val['center_type'] == 'T') {
                              if ($val['center_validity_from'] == '' && $val['center_validity_to'] == '' && $val['pay_status'] == 1) 
                              {
                                $_SESSION['validity_to'] = $val['center_validity_to']; 
                              } 
                              elseif ($val['center_status'] == 'A' && $val['pay_status'] == 0) { } 
                              elseif ($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d', strtotime($date_check)) && $val['center_validity_from'] < date('Y-m-d', strtotime($date_check)) ) {
                                $validity_to = $val['center_validity_to'];
                                $validity_from = (date('Y-m-d', strtotime("+6 day", strtotime($val['center_validity_from']))));
                              ?>
                                <option <?php echo (set_value('center_id') == $val['center_id']) ? " selected=' selected'" : "" ?> value="<?php echo $val['center_id']; ?>" data-todate="<?php echo $val['center_validity_to']; ?>"><?php echo $val['location_name']; if($val['center_id'] == '3039') { echo ' - Wagholi'; } ?> </option>

                              <?php } 
                              elseif ($val['center_validity_to'] < date('Y-m-d')  && $val['pay_status'] == 1) { } 
                              elseif ($val['center_status'] == 'R') { } 
                              elseif ($val['center_status'] == 'IR') { } 
                              elseif ($val['center_status'] == 'AR') { ?>
                                <option value="<?php echo $val['center_id'] ?>" data-todate="<?php echo $val['center_validity_to']; ?>">
                              <?php echo $val['location_name'] . ' ( Approve by Recommender. )'; ?></option>
                              <?php } elseif ($val['center_status'] == 'A' && $val['pay_status'] == 2) { } 
                              elseif ($val['center_validity_from'] > date('Y-m-d')) { }
                            }
                          } //not empty renew payemnt status
                          else { //empty renew payemnt status                                                                               

                            if ($val['center_validity_from'] == '' && $val['center_validity_to'] == '' && $val['pay_status'] == 1) {
                              $_SESSION['validity_to'] = $val['center_validity_to']; } 
                            elseif ($val['center_status'] == 'A' && $val['pay_status'] == 0) { } 
                            elseif ($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d', strtotime($date_check)) && $val['center_validity_from'] < date('Y-m-d', strtotime($date_check)) ) {
                              $validity_to = $val['center_validity_to'];
                              $validity_from = (date('Y-m-d', strtotime("+6 day", strtotime($val['center_validity_from']))));
                            ?>
                              <option <?php echo (set_value('center_id') == $val['center_id']) ? " selected=' selected'" : "" ?> value="<?php echo $val['center_id']; ?>" data-todate="<?php echo $val['center_validity_to']; ?>"><?php echo $val['location_name']; if($val['center_id'] == '3039') { echo ' - Wagholi'; } ?> </option>

                            <?php } 
                            elseif ($val['center_validity_to'] < date('Y-m-d')  && $val['pay_status'] == 1) { } 
                            elseif ($val['center_status'] == 'R') { } 
                            elseif ($val['center_status'] == 'IR') { } 
                            elseif ($val['center_status'] == 'AR') { } 
                            elseif ($val['center_status'] == 'A' && $val['pay_status'] == 2) { } 
                              elseif ($val['center_validity_from'] > date('Y-m-d')) { }
                          }
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Address Of Concern Centre <span class="mandatory-field">*</span></label>
                  <div class="col-sm-6">
                    <textarea style="width:100%; text-align:left; resize: none;" class="form-control" id="center_address" name="center_address" value="<?php echo set_value('center_address'); ?>" autocomplete="off" maxlength="500" placeholder="" data-parsley-trigger="focusin focusout" required> </textarea>
                    <span class="note-error" id="err_center_address"></span>
                  </div>
                </div>
              
              </div>


              <div id="batch_online_users_outer" style="display:none;">
                <div class="form-group">
                  <label class="col-sm-3 control-label">Name of the online training platform <span class="mandatory-field">*</span></label>
                  <div class="col-sm-6">
                    <input type="text" onkeypress="return onlyAlphabets(event)" class="form-control" id="online_training_platform" name="online_training_platform" value="<?php echo set_value('online_training_platform'); ?>" autocomplete="off" maxlength="50" placeholder="e.g. Zoom/Teams" data-parsley-trigger="focusin focusout">
                    <span class="note" id="online_training_platform">Note: You can Enter maximum 50 Characters</span></br>
                    <span class="note-error" id="err_online_training_platform"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Link <span class="mandatory-field">*</span></label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="platform_link" name="platform_link" value="<?php echo set_value('platform_link'); ?>" autocomplete="off" maxlength="500" placeholder="" data-parsley-trigger="focusin focusout">
                    <span class="note" id="platform_link">Note: Please Enter link with mentioned format https://iibf.org.in/</span></br>
                    <span class="note-error" id="err_platform_link"></span>
                  </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Link For Preparatory Session</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="preparatory_session_link" name="preparatory_session_link" value="<?php echo set_value('preparatory_session_link'); ?>" autocomplete="off" maxlength="500" placeholder="" data-parsley-trigger="focusin focusout">
                        <span class="note" id="preparatory_session_link">Note: Please Enter link with mentioned format https://iibf.org.in/</span></br>
                        <span class="note-error" id="err_preparatory_session_link"></span>
                    </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">User Details<span class='mandatory-field'>*</span></label>
                  <div class="col-sm-2">
                    Login Id
                  </div>
                  <div class="col-sm-2">
                    Password
                  </div>
                  <!-- <div class="col-sm-4">
                    Note for Password
                  </div> -->
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label"></label>
                  <div class="col-sm-2">
                    <input type="text" class="form-control" id="batch_online_login_ids0" name="batch_online_login_ids[]" value="<?php if (isset($login_id_arr[$i]) && $login_id_arr[$i] != "") { echo $login_id_arr[$i]; } ?>" autocomplete="off" placeholder="Login ID" data-parsley-trigger="focusin focusout">
                    <!--maxlength="10"-->
                  </div>
                  <div class="col-sm-2">
                    <input type="password" class="form-control" id="batch_online_login_pass0" name="batch_online_login_pass[]" value="<?php if (isset($login_pass_arr[$i]) && $login_pass_arr[$i] != "") { echo $login_pass_arr[$i]; } ?>" autocomplete="off" placeholder="Password" placeholder="Enter Password" value="" data-parsley-errors-container="#password_error<?php echo $i; ?>" data-parsley-trigger="focusin focusout" autcomplete="off">

                    <span class="note-error" id="password_error<?php echo $i; ?>"> <?php echo form_error('password'); ?>
                  </div>
                  <?php /* <div class="col-sm-4 note">
                    Note: 1 upper case letter, 1 lower case letter, 1 numeric value, 1 special character is Compulsory. Minimum:6, Maximum:10.
                  </div>
                  <div class="col-sm-4 note">
                    Note: Minimum:6.
                  </div> */?>
                </div>

                <div class="box-element" id="repeatable_userdtls"></div>

                <div class="col-sm-12 button_div">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label"></label>
                    <div class="col-sm-6">
                      <a href="javascript:void(0);" id="btn_add_userdtls"><i class="btn btn-primary fa fa-plus-square" title="Add User Details"></i></a>&nbsp;
                      <a href="javascript:void(0);" id="btn_remove_userdtls" class="remove-added-box"><i class="btn btn-primary fa fa-minus-square" title="Remove User Details"></i></a>
                    </div>
                  </div>
                </div>
              </div>
              <!--########## END : CODE ADDED BY SAGAR ON 18-08-2020 ###################--->
            </div>
            <input type="hidden" id="batchLimit_err" value="0">
            <input type="hidden" id="grossTime_err" value="">
            <input type="hidden" id="validateDoc_err" value="">
            <input type="hidden" id="totalBrk_err" value="">
            <input type="hidden" id="netTime_err" value="">
            <input type="hidden" id="totalnetTime_err" value="">
            <input type="hidden" id="grossDays_err" value="">
            <input type="hidden" id="first_faculty_err" value="">
            <input type="hidden" id="sec_faculty_err" value="">
            <input type="hidden" id="additional_first_faculty_err" value="">
            <input type="hidden" id="additional_sec_faculty_err" value="">

            <div class="box-footer">
              <div class="col-sm-4 col-xs-offset-3">
                <input type="submit" class="btn btn-info btn_submit" name="btnSubmit" id="btnSubmit" value="Submit">
                <input type="reset" class="btn btn-danger" name="btnReset" id="btnReset" value="Reset">
                <!-- <a href="<?php //echo base_url('iibfdra/Version_2/TrainingBatches')
                              ?>" class="btn btn-info" name="btnCancel" id="btnCancel">Cancel</a> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </form>
</div>


<script type="text/javascript">
  
  $('.batch-limit').on('change',function()
  {
    var batch_type = $(this).val();
    var fromDate   = $('#batch_from').val();
    var toDate     = $('#batch_to').val();

    if (fromDate != '' && toDate != '' && fromDate != undefined && toDate != undefined) {
      checkBatchLimit(batch_type,fromDate,toDate);
    }
  })

  function checkBatchLimit(batch_type,from_date,to_date)
  {
    if (batch_type != '' && batch_type != null && batch_type != undefined) 
    {
      $.ajax({
        url: site_url + 'iibfdra/Version_2/TrainingBatches/checkBatchLimit/',
        data: {'batch_type':batch_type,'from_date':from_date,'to_date':to_date},
        type: 'POST',
        async: false,
        success: function(responseData) {
          let jsonResponseData = JSON.parse(responseData);
          console.log(jsonResponseData);
          if (jsonResponseData.status == 'error') {
            $('.batch-limit-error').text(jsonResponseData.massege);
            $('#batchLimit_err').val('1');
          } else {
            $('.batch-limit-error').text('');
            $('#batchLimit_err').val('0');
          }
        }
      });
    }
  }

  // checkBatchLimit($('input[name="hours"]:checked').val());

  var errCnt = 0;

  $('#batch_center_id').on('change',function() 
  {
    var batch_center_id = $('#batch_center_id').val();
    getCenrerAddress(batch_center_id);    
  })

  function getCenrerAddress(batch_center_id)
  {
    $.ajax({
      url: site_url + 'iibfdra/Version_2/TrainingBatches/getCenterAddress/',
      data: {'batch_center_id':batch_center_id},
      type: 'POST',
      async: false,
      success: function(data) {
        $('#center_address').val(data);
      }
    });
  }

  $(document).ready(function() {
    newid = 1;

    var selected_flag_val = $("input[type=radio][name='batch_online_offline_flag']:checked").val();
    if (typeof selected_flag_val === 'undefined') {
      var flag = 0;
    } else {
      var flag = selected_flag_val;
    }
    batch_online_users_show(flag);
  });



  // Count number of days Added by Manoj 
  function GetDays() {
    var batch_from = new Date(document.getElementById("batch_from").value);
    var batch_to = new Date(document.getElementById("batch_to").value);
    var holidays = document.getElementById("holidays").value;
    var count = 0;
    ////console.log('batch_from--'+batch_from+'batch_to--'+batch_to);
    if (holidays != '') 
    {
      if (holidays.includes(',') == true) 
      {
        holidaysArr = holidays.split(',');
        count = holidaysArr.length;
      } 
      else 
      {
        count = 1;
      }
    }

    //console.log('count=='+count);

    const diffTime = Math.abs(batch_to - batch_from);
    const days = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    //var days = parseInt((batch_to - batch_from) / (24 * 3600 * 1000));
    return days - count;
  }

  function NetDays() 
  {
    var holidays = document.getElementById("holidays").value;
    var count = 0;

    if (holidays != '') {
      if (holidays.includes(',') == true) {
        holidaysArr = holidays.split(',');
        count = holidaysArr.length;

        //console.log('---'+count);
      } else {
        count = 1;
      }
    }

    //var days = parseInt((gross_days - batch_from) / (24 * 3600 * 1000));
    return count;
  }

  function chk_days() {
    $('#holidays,#net_days,#time1,#time2,#gross_time,#net_time,#total_net_time').val('');

    $('#brk_time1,#brk_time2,#brk_time3,#total_brk_time').val(0);
    $('#time1_from,#time1_to,#time2_from,#time2_to,#time3_from,#time3_to').val('');

    if (document.getElementById("batch_to").value != '') {
      //document.getElementById("gross_days").value=GetDays();
      var days = GetDays();
      //console.log('days--'+days);
      if ($.isNumeric(days)) {
        days = days + 1;
        $("#gross_days").val(days + ' Days');
      } else {
        $("#gross_days").val('0 Days');
      }
    }

    var gross_days = $('#gross_days').val();
    //var gross_time = $('#gross_time').val();

    $('#gross_days').val(gross_days);
    $('#net_days').val(gross_days);
  }

  function convertToSortableFormat(date) {
    var parts = date.split("-");
    return parts[2] + "-" + parts[1] + "-" + parts[0];
  }

  function convertToDdMmYyyy(date) {
    var parts = date.split("-");
    return parts[2] + "-" + parts[1] + "-" + parts[0];
  }

  function chk_net_days() {
    //Start : Code to display the date in ascending order
    let selected_holidays_str = $("#holidays").val();
    if (selected_holidays_str.trim() != '') {
      let selected_holidays_arr = selected_holidays_str.split(",");

      // Create a new array with dates in the sortable format
      var sortableDateArray = selected_holidays_arr.map(convertToSortableFormat);
      sortableDateArray.sort();

      var convertedArray = sortableDateArray.map(convertToDdMmYyyy);
      var commaSeparatedString = convertedArray.join(",");
      $("#holidays").val(commaSeparatedString);
    } else {
      $("#holidays").val('');
    }
    //End : Code to display the date in ascending order

    var grossDays = document.getElementById("gross_days").value;
    if (grossDays != '') {
      //document.getElementById("gross_days").value=GetDays();
      var grossDays = grossDays.split(' ');
      var days = NetDays();
      if ($.isNumeric(days)) {
        days = grossDays[0] - days;
        $("#net_days").val(days + ' Days');
      } else {
        $("#net_days").val('0 Days');
      }
    }

    var gross_days = $('#grossDays').val();
    //var gross_time = $('#gross_time').val();

    var net_days = $('#net_days').val().split(' ');
    var net_time_val = $('#net_time').val();
    if (net_time_val != '') {
      var net_time = net_time_val.split(':');
      var total_net_time_min = Number(net_time[0]) * 60 + Number(net_time[1]);
      var total_net_time = (net_days[0] * total_net_time_min);
      var hours = Math.floor(total_net_time / 60);
      var minutes = total_net_time % 60;
      //return hours + ":" + minutes;    
    } else {
      var hours = 0;
      var minutes = 0;
    }

    //console.log('hours : '+hours);
    //console.log('minutes : '+minutes);
    if (isNaN(hours)) {
      hours = 0;
    }
    if (isNaN(minutes)) {
      minutes = 0;
    }
    ////console.log('total_net_time=>'+total_net_time);
    $('#total_net_time').val(hours + ":" + minutes);
    //$('#net_days').val(gross_days);
  }

  function form_submit() {
    // alert(e);
    //$( "#draExamAddFrm" )[0].submit();    
    var batchLimit_err = $('#batchLimit_err').val();
    var grossTime_err = $('#grossTime_err').val();
    var validateDoc_err = $('#validateDoc_err').val();
    var totalBrk_err = $('#totalBrk_err').val();
    var netTime_err = $('#netTime_err').val();
    var totalnetTime_err = $('#totalnetTime_err').val();
    var grossDays_err = $('#grossDays_err').val();
    var first_faculty_err = $('#first_faculty_err').val();
    var sec_faculty_err = $('#sec_faculty_err').val();
    var additional_first_faculty_err = $('#additional_first_faculty_err').val();
    var additional_sec_faculty_err = $('#additional_sec_faculty_err').val();

    if ($("#draExamAddFrm").parsley().isValid() && batchLimit_err == 0 && grossTime_err == 0 && validateDoc_err == 0 && totalBrk_err == 0 && netTime_err == 0 && totalnetTime_err == 0 && grossDays_err == 0 && first_faculty_err == 0 && sec_faculty_err == 0 && additional_first_faculty_err == 0 && additional_sec_faculty_err == 0) {
      //console.log('if');
      //$( "#draExamAddFrm" )[0].submit();
      return true;
    } else {
      //console.log('else');
      //e.preventDefault();
      if ($("#draExamAddFrm").parsley().isValid()) {
        if ($('#grossDays_err').val() == 1) {
          $("#gross_days").focus();
        } else if ($('#totalnetTime_err').val() == 1) {
          $("#total_net_time").focus();
        }
      }
      return false;
    }
  }

  var srcContent;

  function validateDoc(e, error_id) {
    var srcid = e.srcElement.id;
    if (document.getElementById(srcid).files.length != 0) {
      var file = document.getElementById(srcid).files[0];

      if(file.size == 0)
      {
        $('#validateDoc_err').text('Please select valid file');
        $('#'+document.getElementById(srcid).id).val('');
        $('#validateDoc_err').val('1');
      }  
      else
      {
        var allowedFiles = [".txt", ".doc", ".docx", ".pdf"];
        var regex = new RegExp("([a-zA-Z0-9\s_\\((\d+)\)\.\-:])+(" + allowedFiles.join('|') + ")$");
        const fileSize = document.getElementById(srcid).files[0].size / 1024 / 1024; // in MiB
        var reader = new FileReader();

        if (reader.result == 'data:') {
          $('#' + error_id).text('This file is corrupted');
          //$('.btn_submit').attr('disabled',true);
          $('#validateDoc_err').val('1');
        } 
        else {
          if (!regex.test(file.name)) {
            $('#' + error_id).text("Please upload " + allowedFiles.join(', ') + " only.");
            //$('.btn_submit').attr('disabled',true);
            $('#validateDoc_err').val('1');
          } else {
            if (fileSize > 5) {
              $('#' + error_id).text("Please upload file less than 5 Mb");
              //$('.btn_submit').attr('disabled',true);
              $('#validateDoc_err').val('1');
            } else {
              //console.log('---');
              reader.onload = function(e) {
                srcContent = e.target.result;
              }
              reader.readAsDataURL(file);
              $('#' + error_id).text('');
              //$('.btn_submit').removeAttr('disabled');
              $('#validateDoc_err').val('0');
            }
          }
        }
      } 
    }else {
      $('#' + error_id).text('Please select file');
      //$('.btn_submit').attr('disabled',true);
      $('#validateDoc_err').val('1');
    }
  }

  $('#holidays').on('click', function(e) 
  {
    var batch_from = new Date($('#batch_from').val());
    batch_from.setDate(batch_from.getDate() + 1);

    var batch_to = new Date($('#batch_to').val());
    batch_to.setDate(batch_to.getDate() - 1);

    var holidays = $('#holidays').val();
    //console.log(holidays);
    $('#holidays').datepicker('setStartDate', batch_from);
    $('#holidays').datepicker('setEndDate', batch_to);
  });

  $('#btnReset').on('click', function(e) {
    location.reload();
  });

  $('.time').on('change', function() {
    $('#gross_time,#net_time,#total_net_time').val('');
    $('#time1_from,#time1_to,#time2_from,#time2_to,#time3_from,#time3_to').val('');
    $('#brk_time1,#brk_time2,#brk_time3,#total_brk_time').val(0);
    // $('#gross_time,#total_net_time').val('');

    var val = $(this).val();
    //console.log('.time--'+val);
    if (val != '') {
      var response = differenceHours.diff_hours('time1', 'time2', 'gross_time')
      //console.log(response);
    }
  });

  // Debounce function to prevent multiple calls
function debounce(func, delay) {
    let timer;
    return function() {
        let context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function() {
            func.apply(context, args);
        }, delay);
    };
}
  
  // Event listener for fields with the 'period' class
$('.period').on('change', debounce(function() {
    // Reset faculty section
    $('.faculty-section').val('');
    $('.fac-errr').html('');
    $('.faculty-section option').removeAttr("disabled");

    // Get batch hours and gross days, and check gross days
    var batch_hours = $('input[name="hours"]:checked').val();
    var gross_days = $('#gross_days').val();
    gross_days_check(batch_hours, gross_days);

    // Only proceed if the changed element is either #batch_from or #batch_to
    if ($(this).is('#batch_from, #batch_to')) {
        var fromDate = $('#batch_from').val();
        var toDate = $('#batch_to').val();
        var batchType = $('input[name="hours"]:checked').val();

        // Check if all required values are present
        if (batchType != '' && fromDate != '' && toDate != '' && fromDate != undefined && toDate != undefined) {
            checkBatchLimit(batchType, fromDate, toDate);
        }
    }
}, 300));

  $('.time_8').on('change', function() {
    var gross_time = $('#gross_time').val().split(':');
    var total_gross_min = Number(gross_time[0]) * 60 + Number(gross_time[1]);
    var hours_8 = 8 * 60;

    //console.log('gross_time:'+gross_time);

    if (total_gross_min > hours_8) {
      $('#gross_time_error').text('Gross Time should be less than or equal to 8 Hours');
      //$('.btn_submit').attr('disabled',true);
      $('#grossTime_err').val('1');
    } else {
      $('#gross_time_error').text('');
      $('.btn_submit').removeAttr('disabled');
      $('#grossTime_err').val('0');
    }


    /*var net_time = $('#net_time').val().split(':');
    var total_net_min = Number(net_time[0]) * 60 + Number(net_time[1]);
    var hours_7 = 7*60;

    //console.log('net_time:'+net_time);
    //console.log('net_time_min:'+total_net_min+'-hours_7-:'+hours_7);


    if(total_net_min > hours_7){
        $('#net_time_error').text('Net Time should be less than or equal to 7 Hours.');
        $('.btn_submit').attr('disabled',true);
    }
    else{
        $('#net_time_error').text('');
        $('.btn_submit').removeAttr('disabled');
    }*/
  });

  $('.breakTime').on('change', function() {
    //var total_brk_time = $('#total_brk_time').val().split(':');
    
    var brk_time1 = $('#brk_time1').val();
    var brk_time2 = $('#brk_time2').val();
    var brk_time3 = $('#brk_time3').val();
    var total_brk_time = parseInt(brk_time1) + parseInt(brk_time2) + parseInt(brk_time3);

    //var total_brk_min = Number(total_brk_time[0]) * 60 + Number(total_brk_time[1]);
    var hours_90 = 90;

    //console.log('total_brk_time:'+total_brk_time);
    ////console.log('total_brk_min:'+total_brk_min);

    if (total_brk_time > hours_90) {
      $('#total_break_time_error').text('Total Break Time should be less than or equal to 90 minutes');
      //$('.btn_submit').attr('disabled',true);
      $('#totalBrk_err').val('1');
    } else {
      $('#total_break_time_error').text('');
      $('.btn_submit').removeAttr('disabled');
      $('#totalBrk_err').val('0');
    }
  });

  function net_time_validate() {
    var net_time_val = $('#net_time').val().split(':');
    var net_time_min = parseFloat(net_time_val[0]) * 60 + parseFloat(net_time_val[1]);
    var hours_7 = 7 * 60;

    //console.log($('#net_time').val());
    //console.log(net_time_min+' > :'+hours_7);

    if (net_time_min > hours_7) {
      $('#net_time_error').text('Net Time should be less than or equal to 7 Hours.');
      //$("#net_time").focus();
      //$('.btn_submit').attr('disabled',true);
      //$("#net_time").focus();
      $('#netTime_err').val('1');
    } else {
      $('#net_time_error').text('');
      $('.btn_submit').removeAttr('disabled');
      $('#netTime_err').val('0');
    }
  }

  $('.time_7').on('blur ', function() { net_time_validate(); });
  $('.time_7').on('change',function () { net_time_validate(); });

  $('.netTime').on('change', function() {

    var val = $(this).val();  
    //console.log('.netTime--'+val);
    if (val != '') {
      differenceHours.net_diff_hours('time1', 'time2', 'brk_time1', 'brk_time2', 'brk_time3', 'net_time', 'total_brk_time')
    }
    //$('#net_time').val(net_time);

    var net_days = $('#net_days').val().split(' ');
    var net_time = $('#net_time').val().split(':');
    var total_net_time_min = Number(net_time[0]) * 60 + Number(net_time[1]);
    var total_net_time = (net_days[0] * total_net_time_min);

    var hours = Math.floor(total_net_time / 60);
    var minutes = total_net_time % 60;
    //return hours + ":" + minutes; 

    if (isNaN(hours)) {
      hours = 0;
    }
    if (isNaN(minutes)) {
      minutes = 0;
    }
    $('#total_net_time').val(hours + ":" + minutes);

    var total_net_time = $('#total_net_time').val();
    var batch_type = $('input[name="hours"]:checked').val();

    //console.log('batch_type++'+batch_type);

    if (hours != 0 && hours < batch_type) {
      $('#total_net_time_error').text('Total Net Training Time of Duration should be greater than ' + batch_type);
      //$('.btn_submit').attr('disabled',true);
      $('#totalnetTime_err').val('1');
    } else {
      $('#total_net_time_error').text('');
      $('.btn_submit').removeAttr('disabled');
      $('#totalnetTime_err').val('0');
    }

    net_time_validate();

    //console.log('total_net_time_mins=>'+total_net_time+'total_net_time_hrs=>'+total_net_time/60);
    //$('#total_net_time').val(total_net_time.toFixed(2));
  });

  $('.radiocls').on('click', function(e) {
    var value = $(this).val();

    if (value == 'No') {
      $('#training_prd_full_capacity_div').css('display', 'block');
    } else {
      $('#training_prd_full_capacity_div').css('display', 'none');
    }
  });

  $('#btn_add_userdtls').click(function() {

    var repeatable_userdtls = '<div class="" id="textbox-label"><input type="hidden" name="batch_id[]" id="batch_id' + newid + '" value="" ><div class="form-group"><label class="col-sm-3 control-label"></label><div class="col-sm-2"><input type="text" class="form-control" id="batch_online_login_ids' + newid + '" name="batch_online_login_ids[]" placeholder="Login ID"  value="" onkeypress="return alphanumeric(event)"  data-parsley-trigger="focusin focusout"><span class="note-error" id="login_error' + newid + '"></span></div><div class="col-sm-2"><input type="password" class="form-control" id="batch_online_login_pass' + newid + '" name="batch_online_login_pass[]" placeholder="Password" value="" value="" data-parsley-trigger="focusin focusout"><span class="note-error" id="password_error' + newid + '"></span></div><div class="col-sm-4 note"></div> </div></div>';
    //maxlength="10"
    //Note: Minimum:6 1 upper case letter, 1 lower case letter, 1 numeric value, 1 special character is Compulsory. Minimum:6, Maximum:10
    //data-parsley-uppercase="1" data-parsley-lowercase="1" data-parsley-number="1" data-parsley-special="1"
    //maxlength="10" 

    $('#repeatable_userdtls').append(repeatable_userdtls);
    $('#batch_online_login_ids' + newid).attr('data-parsley-required', 'true');

    $('#batch_online_login_pass' + newid).attr('data-parsley-required', 'true');
    $('#batch_online_login_pass' + newid).attr('data-parsley-errors-container', '#password_error' + newid);
    
    /*$('#batch_online_login_pass'+newid).attr('data-parsley-uppercase','1');
    $('#batch_online_login_pass'+newid).attr('data-parsley-lowercase','1');
    $('#batch_online_login_pass'+newid).attr('data-parsley-number','1');
    $('#batch_online_login_pass'+newid).attr('data-parsley-required','true');
      */

    newid++;
  }); // add radio add more (parent)

  //has at least one character
  window.Parsley.addValidator('one_character', {
    requirementType: 'number',
    validateString: function(value, requirement) {
      var one_character = value.match(/[A-Za-z]/g) || [];
      return one_character.length >= requirement;
    },
    messages: {
      en: 'Your password must contain at least (%s) character.'
    }
  });

  $(document).on('click', '#btn_remove_userdtls', function() {

    $('#batch_online_login_ids' + newid).removeAttr('data-parsley-required');

    $('#batch_online_login_pass' + newid).removeAttr('data-parsley-required', 'true');
    $('#batch_online_login_pass' + newid).removeAttr('data-parsley-errors-container');

    $('div#textbox-label').last().remove();
  });

  //has uppercase
  window.Parsley.addValidator('uppercase', {
    requirementType: 'number',
    validateString: function(value, requirement) {
      var uppercases = value.match(/[A-Z]/g) || [];
      return uppercases.length >= requirement;
    },
    messages: {
      en: 'Your password must contain at least (%s) uppercase letter.'
    }
  });

  //has lowercase
  window.Parsley.addValidator('lowercase', {
    requirementType: 'number',
    validateString: function(value, requirement) {
      var lowecases = value.match(/[a-z]/g) || [];
      return lowecases.length >= requirement;
    },
    messages: {
      en: 'Your password must contain at least (%s) lowercase letter.'
    }
  });

  //has number
  window.Parsley.addValidator('number', {
    requirementType: 'number',
    validateString: function(value, requirement) {
      var numbers = value.match(/[0-9]/g) || [];
      return numbers.length >= requirement;
    },
    messages: {
      en: 'Your password must contain at least (%s) number.'
    }
  });

  //has special char
  window.Parsley.addValidator('special', {
    requirementType: 'number',
    validateString: function(value, requirement) {
      var specials = value.match(/[^a-zA-Z0-9]/g) || [];
      return specials.length >= requirement;
    },
    messages: {
      en: 'Your password must contain at least (%s) special characters.'
    }
  });

  window.Parsley.addValidator('dracheckpin', function(value) {
      var response = false;
      var datastring = 'statecode=' + $('#ccstate').val() + '&pincode=' + value;
      $.ajax({
        url: site_url + 'iibfdra/Version_2/DraExam/checkpin/',
        data: datastring,
        type: 'POST',
        async: false,
        success: function(data) {
          if (data == 'true') {
            response = true;
          } else {
            response = false;
          }
        }
      });
      return response;
    }, 31)
    .addMessage('en', 'dracheckpin', 'Please enter Valid Pincode.');

  function batch_online_users_show(flag) {
    //console.log(flag);
    if (flag == 1) {
      $("#batch_online_users_outer").css("display", "block");
      $("[name='batch_online_login_ids[]']").attr('required', true);
      $("[name='batch_online_login_pass[]']").attr('required', true);
      $("#online_training_platform").attr('required', true);
      $("#platform_link").attr('required', true);
      // $("#preparatory_session_link").attr('required', true);
      $('.button_div').css('display', 'block');

      $("#batch_ofline_users_outer").css("display", "none");
      $("#cctv_link").removeAttr('required');
      $("#batch_center_id").removeAttr('required');
      $("#center_address").removeAttr('required');
    } 
    else 
    {
      $("#batch_online_users_outer").css("display", "none");
      $("[name='batch_online_login_ids[]']").removeAttr('required');
      $("[name='batch_online_login_ids[]']").removeAttr('data-parsley-required');
      $("[name='batch_online_login_pass[]']").removeAttr('required');
      $("[name='batch_online_login_pass[]']").removeAttr('data-parsley-required');
      $("#online_training_platform").removeAttr('required');
      $("#platform_link").prop('required', false);
      // $("#preparatory_session_link").attr('required', false);
      $('.button_div').css('display', 'none');


      $("#batch_ofline_users_outer").css("display","block");
      $("#cctv_link").attr('required', true);
      $("#batch_center_id").attr('required', true);
      $("#center_address").attr('required', true);
    }
  }


  function calc_candidates() 
  {
    var tenth_pass_candidates = document.getElementById("tenth_pass_candidates").value;
    var twelth_pass_candidates = document.getElementById("twelth_pass_candidates").value;
    var graduate = document.getElementById("graduate_candidates").value;
    if (tenth_pass_candidates == '') {
      tenth_pass_candidates = 0;
    }
    if (twelth_pass_candidates == '') {
      twelth_pass_candidates = 0;
    }
    if (graduate == '') {
      graduate = 0;
    }
    var total_candidates = parseInt(tenth_pass_candidates) + parseInt(twelth_pass_candidates) + parseInt(graduate);
    if (total_candidates == 0) {
      $('#total_candidates').val('');
    } else {
      $('#total_candidates').val(total_candidates);
    }

    if (total_candidates > 35) {
      $('#total_candidates_error').text('Total Candidates should be less than or equal to 35');
      //$('.btn_submit').attr('disabled',true);
    } else {
      $('#total_candidates_error').text('');
      $('.btn_submit').removeAttr('disabled');
    }
  }

  function get_faculty(event) 
  {
    var from_date = $('#batch_from').val();
    var to_date   = $('#batch_to').val();
    var id = event.srcElement.id;  
    if( from_date == '' && to_date == '' ) 
    {
      alert('Please select training period first.');
      $('#batch_from').focus(); 
      $(event.target).val('');     
    }
    else
    {  
    var value = $('#'+id).children(":selected").val();
      var code = $('#'+id).children(":selected").attr('data-code');

    $.ajax({
      type: 'POST',
        data: {'faculty_id':value, 'faculty_code':code, 'from_date':from_date, 'to_date':to_date},
      url: site_url + 'iibfdra/Version_2/TrainingBatches/check_faculty_mapping/',
        dataType: 'JSON',
        success: function(response) {
          var fac_error = $(event.target).attr('data-error');
          if(response.status == 'error') 
          {
            $(event.target).focus();
            $('#'+fac_error).html(response.msg);  
            // $(event.target).next().html(response.msg);
            // var errorId = $(event.target).next().attr('id');
            
            switch(fac_error) {
                case 'first_faculty_error':
                    $('#first_faculty_err').val('1');
                    break;
                case 'sec_faculty_error':
                    $('#sec_faculty_err').val('1');
                    break;
                case 'additional_first_faculty_error':
                    $('#additional_first_faculty_err').val('1');
                    break;
                case 'additional_sec_faculty_error':
                    $('#additional_sec_faculty_err').val('1');
                    break;    
                default:
                    break;
            }
          }
          else
          {
            $(event.target).focus();
            // $(event.target).next().html('');
            $('#'+fac_error).html('');
            // var errorId = $(event.target).next().attr('id');
            
            switch(fac_error) {
                case 'first_faculty_error':
                    $('#first_faculty_err').val('0');
                    break;
                case 'sec_faculty_error':
                    $('#sec_faculty_err').val('0');
                    break;
                case 'additional_first_faculty_error':
                    $('#additional_first_faculty_err').val('0');
                    break;
                case 'additional_sec_faculty_error':
                    $('#additional_sec_faculty_err').val('0');
                    break;    
                default:
                    break;
          }
        }
          getfacultyDisable();
      }
    });
    } 
  }

  function getfacultyDisable()
  {
    var first_faculty = $('#first_faculty').val();
    var sec_faculty   = $('#sec_faculty').val();
    var additional_first_faculty = $('#additional_first_faculty').val();
    var additional_sec_faculty   = $('#additional_sec_faculty').val();

    $('#first_faculty option').removeAttr("disabled");
    $('#sec_faculty option').removeAttr("disabled");
    $('#additional_first_faculty option').removeAttr("disabled");
    $('#additional_sec_faculty option').removeAttr("disabled");

    if (first_faculty != "") {
      $('#sec_faculty option[value=' + first_faculty + ']').attr("disabled", "disabled");
      $('#additional_first_faculty option[value=' + first_faculty + ']').attr("disabled", "disabled");
      $('#additional_sec_faculty option[value=' + first_faculty + ']').attr("disabled", "disabled");
    }

    if (sec_faculty != "") {
      $('#first_faculty option[value=' + sec_faculty + ']').attr("disabled", "disabled");
      $('#additional_first_faculty option[value=' + sec_faculty + ']').attr("disabled", "disabled");
      $('#additional_sec_faculty option[value=' + sec_faculty + ']').attr("disabled", "disabled");
    }

    if (additional_first_faculty != "") {
      $('#first_faculty option[value=' + additional_first_faculty + ']').attr("disabled", "disabled");
      $('#sec_faculty option[value=' + additional_first_faculty + ']').attr("disabled", "disabled");
      $('#additional_sec_faculty option[value=' + additional_first_faculty + ']').attr("disabled", "disabled");
    }

    if (additional_sec_faculty != "") {
      $('#first_faculty option[value=' + additional_sec_faculty + ']').attr("disabled", "disabled");
      $('#sec_faculty option[value=' + additional_sec_faculty + ']').attr("disabled", "disabled");
      $('#additional_first_faculty option[value=' + additional_sec_faculty + ']').attr("disabled", "disabled");
    }
  }

  function validateDate()
  {
    var from_date      = $('#batch_from').val();
    var to_date        = $('#batch_to').val();
    var center_to_date = $('#center_id option:selected').attr('data-todate');
    
    if (from_date != '' || to_date != '') 
    {
      var selectedDate = new Date(to_date);
      var minDate = new Date(center_to_date);
      if (selectedDate >= minDate) 
      {
        $('#batch_to_error').text('Please select a date greater than or equal to '+center_to_date);
        $('#batch_to').val(''); 
        return false;
      }
      else
      {
        $('#batch_to_error').text('');
        return true;
      }  
    }  
  }

  $(document).ready(function() {
    $('#batch_from').datepicker({
      format: "yyyy-mm-dd",
      startDate: '<?php echo $date_check; ?>',
      endDate:'<?php echo date('Y-m-d', strtotime("+4day", strtotime($date_check))); ?>',
      //startDate: '+2d',//$('#validity_from').val(),
      //endDate:'+5d',//$('#validity_to').val(),
      autoclose: true,
      //todayBtn: "linked", 
      keyboardNavigation: true, 
      forceParse: false, 
      //calendarWeeks: true, 
      //todayHighlight:true, 
      //clearBtn: true
    }).attr('readonly', 'readonly');

    
    $('#batch_to').datepicker({
      format: "yyyy-mm-dd",
      startDate: '<?php echo $date_check; ?>',
      autoclose: true,
      keyboardNavigation: true, 
      forceParse: false
    }).attr('readonly','readonly').on('changeDate', function(e) 
    {
      var CenterToDate = $('#center_id option:selected').attr('data-todate');
      var selectedDate = new Date(e.date);
      var minDate = new Date(CenterToDate);
      if (selectedDate >= minDate) 
      {
        $('#batch_to_error').text('Please select a date greater than or equal to '+CenterToDate);
        $('#batch_to').val(''); 
      }
      else
      {
        $('#batch_to_error').text('');
      }  
    });


    // $('#batch_to').datepicker({
    //   format: "yyyy-mm-dd",
    //   startDate: '<?php echo $date_check; ?>',
    //   //startDate: '+5d',//$('#validity_from').val(), //'+6d',
    //   //endDate:$('#validity_to').val(),
    //   autoclose: true,
    //   //todayBtn: "linked", 
    //   keyboardNavigation: true, 
    //   forceParse: false, 
    //   //calendarWeeks: true, 
    //   //todayHighlight:true, 
    //   //clearBtn: true    
    // }).attr('readonly', 'readonly');
    

    // validation for no of candidates     

    $('#holidays').datepicker({
      multidate: true,
      format: 'dd-mm-yyyy'
    });

    //$('#timing_from').timepicker();
    //$('#timing_to').timepicker();
    // modify by Manoj MMM
    $('.datepairtimes .time').timepicker({
      'timeFormat': 'H:i',
      'showDuration': true,
      'timeFormat': 'g:ia',
      'option': true,
      'explicitMode': true,
      minuteStep: 1,
      disableFocus: true
    });


    $('.datepairbreaktimes .break-time').timepicker({
      'timeFormat': 'H:i',
      'showDuration': true,
      'timeFormat': 'g:ia',
      'option': true,
      'explicitMode': true,
      minuteStep: 1,
      disableFocus: true
    });

    $('.time,.break-time,#total_brk_time,#net_time,#total_net_time').val('');


    // on change of center get inspector master details and center details
    $('#center_id').change(function() {
      $("#loading").show();
      var center_id = $(this).val();
      // AJAX request
      $.ajax({
        url: '<?= base_url() ?>iibfdra/Version_2/TrainingBatches/getcenterDetails',
        method: 'post',
        data: {
          center_id: center_id
        },
        dataType: 'json',
        success: function(response) {
          // Add options
          $.each(response, function(index, data) {
            // alert(data['state_code']);
            //  alert(data['state_name']);
            // $('#inspector_id').append('<option value="'+data['id']+'">'+data['inspector_name']+'</option>');
            $('#addressline1').val(data['address1']);
            $('#addressline2').val(data['address2']);
            $('#addressline3').val(data['address3']);
            $('#addressline4').val(data['address4']);
            //$('#ccity').val(data['city_name']);
            if (data['city'] != '') {
              $('#ccity').val(data['city_name']);
            } else {
              $('#ccity').val(data['location_name']);
            }

            $('#cccity,#city').val(data['location_name']);
            $('#cdistrict,#district').val(data['district']);
            $('#cstate,#state').val(data['state_name']);
            $('#ccstate').val(data['state_code']);
            $('#cpincode').val(data['pincode']).attr('value', data['pincode']);

            //$('#contact_person_name').val(data['contact_person_name']);
            //$('#contact_person_phone').val(data['contact_person_mobile']);


            // custom validation code added by Manoj MMM    
            var validity_from_ck = '+1d'; //data['validity_chk_from'];
            var validity_to_ck = '+5d'; //data['center_validity_to'];
            //alert(validity_to_ck);
            // //console.log(date_string);
            //var date_string = userDate;       
            $('#validity_from').val(validity_from_ck);

            //$('#batch_from').val(''); 
            //$('#batch_to').val('');

            /*$('#batch_from').datepicker('setStartDate', validity_from_ck);
            $('#batch_from').datepicker('setEndDate', validity_to_ck);
            $('#batch_to').datepicker('setStartDate', validity_from_ck);
            $('#batch_to').datepicker('setEndDate', data['center_validity_to']);*/
          });
          $("#loading").hide();
        }
      });

      //$("#loading").hide();
    });

    $('#batch_from').change(function() 
    {
      BatchToDateStart();  
      chk_days();
    });

    function BatchToDateStart()
    {
      var date_check = '<?php echo $date_check; ?>';
      var date_check_end = '<?php echo date('Y-m-d', strtotime("+4day", strtotime($date_check))); ?>';
      var date2 = $('#batch_from').datepicker('getDate', '+5d');
      var vval = $('#batch_from').val();
         
      if (vval != '') 
      {
        if (vval < date_check || vval > date_check_end) 
        {
          // $('#batch_from_error').text('Please select date between '+date_check+' and '+date_check_end);
          $('#btnSubmit').attr('disabled', true);
          errCnt = 1;
        } 
        else 
        {
          $('#batch_from_error').text('');
          $('#btnSubmit').attr('disabled', false);
          errCnt = 0;
        }

        var Center_To_Date = $('#center_id option:selected').attr('data-todate');
        
        if (Center_To_Date != '' && Center_To_Date != undefined ) 
        { 
          assumeTodate = date2;
          assumeTodate = new Date(assumeTodate.setDate(assumeTodate.getDate() + 5))
          
          Center_To_Date = new Date(Center_To_Date);
          console.log(Center_To_Date);
          console.log(assumeTodate);
          if ( Center_To_Date < assumeTodate ) 
          {
            $('#batch_to').val('');
          }
          else
          {
            date2.setDate(assumeTodate);
            $('#batch_to').datepicker('setDate', assumeTodate);
            $('#batch_to').datepicker('setStartDate', assumeTodate);
            $('#batch_to').datepicker(
            {
              autoclose: true,
              format: 'yyyy-mm-dd',
              dateFormat: 'yyyy-mm-dd',
              startDate: assumeTodate,
              minDate: assumeTodate
            }).attr('readonly', 'readonly');   
          }
        }
        else
        {
          date2.setDate(date2.getDate() + 5);
          $('#batch_to').datepicker('setDate', date2);
          $('#batch_to').datepicker('setStartDate', date2);
          $('#batch_to').datepicker(
          {
            autoclose: true,
            format: 'yyyy-mm-dd',
            dateFormat: 'yyyy-mm-dd',
            startDate: date2,
            minDate: date2
          }).attr('readonly', 'readonly');
        }
      }
    }


    $("input[name='hours']").click(function() {
      var batch_type = $(this).val();
      var total_net_time = $('#total_net_time').val();
      var hours = total_net_time.split(':');

      var gross_days = $('#gross_days').val();

      //console.log(hours[0] +'< '+batch_type);

      if (batch_type == '50') {
        $('#tenth_pass_div').css('display', 'none');
        $('#twelth_pass_div').css('display', 'none');
        $('#total_net_time_note').text('Note: Total Net Training Time of Duration should be greater than or equal to 50.');
      } else {
        $('#tenth_pass_div').css('display', 'block');
        $('#twelth_pass_div').css('display', 'block');
        $('#total_net_time_note').text('Note: Total Net Training Time of Duration should be greater than or equal to 100.');
      }

      if (parseInt(hours[0]) >= parseInt(batch_type)) {
        //console.log('if');
        $('#total_net_time_error').text('');
        $('.btn_submit').removeAttr('disabled');
      } else {
        //console.log('else');
        //$('#total_net_time_error').text('Total Net Training Time of Duration should be greater than '+batch_type);
        //$('.btn_submit').attr('disabled',true);
      }

      gross_days_check(batch_type, gross_days);
    });

    $("#dateofbirth").change(function() {
      var sel_dob = $("#dateofbirth").val();
      if (sel_dob != '') {
        var dob_arr = sel_dob.split('-');
        if (dob_arr.length == 3) {
          chkage(dob_arr[2], dob_arr[1], dob_arr[0]);
        } else {
          alert('Select valid date');
        }
      }
    });

    function isUrlValid(url) {
      return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
    }

    $('input[name="platform_link"]').keyup(function(e) {
      if (isUrlValid($(this).val()) == false) {
        $("#err_platform_link").text("Please enter valid url");
        return false;
      } else {
        $("#err_platform_link").text("");
        return true;
      };

    });

    $('input[name="preparatory_session_link"]').keyup(function(e) {
      if (isUrlValid($(this).val()) == false) {
        $("#err_preparatory_session_link").text("Please enter valid url");
        return false;
      } else {
        $("#err_preparatory_session_link").text("");
        return true;
      };
    });

    $('input[name="cctv_link"]').keyup(function(e) {
      if (isUrlValid($(this).val()) == false) {
        $("#err_cctv_link").text("Please enter valid url");
        return false;
      } else {
        $("#err_cctv_link").text("");
        return true;
      };
    });

    //change captcha
    $('#new_captcha').click(function(event) {
      event.preventDefault();
      var sdata = {
        'captchaname': 'draexamcaptcha'
      };
      $.ajax({
        type: 'POST',
        data: sdata,
        url: site_url + 'iibfdra/Version_2/DraExam/generatecaptchaajax/',
        success: function(res) {
          if (res != '') {
            $('#captcha_img').html(res);
          }
        }
      });
    });

    //if invalid captcha entered keep unchecked exam mode disabled
    if ($("input[name='exam_mode']:checked").length > 0) {
      $("input[name='exam_mode']:not(:checked)").attr('disabled', true);
    }
    // change gender on chnage of name subtitle 
    // addressline4

    $("body").on("contextmenu", function(e) {
      return false;
    });
  });

  $("#contact_person_phone").keypress(function(event) {
    remove_first_zero('contact_person_phone')
    
    if (event.charCode >= 46 && event.charCode <= 57 || event.keyCode == 8 || event.keyCode == 46) {
      return true;
    } else {
      return false;
    }
  });
  $("#contact_person_phone").keyup(function(event) { remove_first_zero('contact_person_phone') });

  $("#alt_contact_person_phone").keypress(function(event) {
    remove_first_zero('alt_contact_person_phone')

    if (event.charCode >= 46 && event.charCode <= 57 || event.keyCode == 8 || event.keyCode == 46) {
      return true;
    } else {
      return false;
    }
  });

  $("#alt_contact_person_phone").keyup(function(event) { remove_first_zero('alt_contact_person_phone') });

  function remove_first_zero(input_id)
  {
    if(input_id != "")
    {
      var x = $("#"+input_id).val();
      if (x.indexOf('0') == 0) 
      {
        $('#'+input_id).val(x.substring(1, x.length));
        return false;
      }
    }
  }

  function gross_days_check(batch_hours, gross_days) {
    //console.log('batch_hours++'+batch_hours+'**gross_days**'+gross_days);
    if (parseInt(batch_hours) == 100) {
      $('#gross_days_note').text('Note: Gross Training Days should be between 16 to 30 days.');
      if (parseInt(gross_days) > 30 || parseInt(gross_days) < 16) {
        $('#gross_days_error').text('Gross Training Days should be between 16 to 30 days.');
        //$('.btn_submit').attr('disabled',true);
        $('#grossDays_err').val('1');
      } else {
        $('#gross_days_error').text('');
        $('.btn_submit').removeAttr('disabled');
        $('#grossDays_err').val('0');
      }
    }

    if (parseInt(batch_hours) == 50) {
      $('#gross_days_note').text('Note: Gross Training Days should be between 8 to 20 days.');
      if (parseInt(gross_days) > 20 || parseInt(gross_days) < 8) {
        $('#gross_days_error').text('Gross Training Days should be between 8 to 20 days.');
        //$('.btn_submit').attr('disabled',true);
        $('#grossDays_err').val('1');
      } else {
        $('#gross_days_error').text('');
        $('.btn_submit').removeAttr('disabled');
        $('#grossDays_err').val('0');
      }
    }
  }

  function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
      return false;
    }
    return true;
  }


  function onlyAlphabets(key) {
    var keycode = (key.which) ? key.which : key.keyCode;
    //alert(keycode);
    if ((keycode > 64 && keycode < 91) || (keycode > 96 && keycode < 123) || keycode == 32) {
      return true;
    } else {
      return false;
    }

  }

  function alphanumeric(key) {
    var keycode = (key.which) ? key.which : key.keyCode;

    if ((keycode > 64 && keycode < 91) || (keycode > 96 && keycode < 123) || keycode == 8 || keycode == 32 || (keycode >= 48 && keycode <= 57)) {
      return true;
    } else {
      return false;
    }
  }

  <?php if (set_value('hours') != "") { ?>
    chk_net_days()
    chk_days()
    $(".netTime").trigger("change");
  <?php } ?>

  
    // Function to convert time in "HH:MM AM/PM" format to minutes
    function timeToMinutes(time) {
        const [timePart, modifier] = time.split(' ');
        let [hours, minutes] = timePart.split(':').map(Number);

        if (modifier === 'PM' && hours !== 12) {
            hours += 12;
        } else if (modifier === 'AM' && hours === 12) {
            hours = 0;
        }

        return hours * 60 + minutes;
    }

    // Function to calculate the time difference in minutes and update the gross time field
    function calculateTime(fromSelector, toSelector, outputSelector) {

        const fromTime = $(fromSelector).val();
        const toTime = $(toSelector).val();

        // Check if from or to time is empty
        if (!fromTime || !toTime) {
            $(outputSelector).val(0);
            return;
        }

        const fromMinutes = timeToMinutes(fromTime);
        const toMinutes = timeToMinutes(toTime);

        // Calculate the difference in minutes
        let duration = toMinutes - fromMinutes;

        // Handle negative or overnight durations
        if (duration < 0) {
            duration += 1440; // Add 24 hours (1440 minutes) to handle overnight times
        }

        // If the duration is negative or incorrect, set to 0
        if (duration <= 0) {
            duration = 0;
        }

        // Update the gross time field with the calculated duration
        $(outputSelector).val(duration).trigger('change');
    }

    // Event listeners for time inputs
    $('#time1_from, #time1_to').on('change', function() {
        calculateTime('#time1_from', '#time1_to', '#brk_time1');
    });

    $('#time2_from, #time2_to').on('change', function() {
        calculateTime('#time2_from', '#time2_to', '#brk_time2');
    });

    $('#time3_from, #time3_to').on('change', function() {
        calculateTime('#time3_from', '#time3_to', '#brk_time3');
    });
    
</script>
