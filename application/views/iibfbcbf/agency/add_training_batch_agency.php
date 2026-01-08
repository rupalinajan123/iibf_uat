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
      <?php $this->load->view('iibfbcbf/agency/inc_sidebar_agency'); ?>		
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('iibfbcbf/agency/inc_topbar_agency'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2><?php echo $mode; ?> Training Batch </h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/training_batches_agency'); ?>">Training Batches</a></li>
							<li class="breadcrumb-item active"> <strong><?php echo $mode; ?> Training Batch</strong></li>
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
										<a href="<?php echo site_url('iibfbcbf/agency/training_batches_agency'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a>
                  </div>
                </div>
								<div class="ibox-content bcbf-form-head">
                  <form method="post" action="<?php echo site_url('iibfbcbf/agency/training_batches_agency/add_training_batch_agency/'.$enc_batch_id); ?>" id="add_training_batch_form" enctype="multipart/form-data" autocomplete="off">
										<input type="hidden" name="mode" id="mode" value="<?php echo $mode; ?>">
										<input type="hidden" name="form_enc_batch_id" id="form_enc_batch_id" value="<?php echo $enc_batch_id; ?>">
                    
                    <h4 class="custom_form_title" style="margin: -15px -20px 15px -20px !important;">Basic Details</h4>
                    
										<div class="row">
                      <?php if($mode == 'Update') { ?>
                        <div class="col-xl-4 col-lg-4"><?php /* Batch Code */ ?>
                          <div class="form-group">
                            <label class="form_label">Batch Code <sup class="text-danger">*</sup></label>
                            <input type="text" value="<?php echo $form_data[0]['batch_code']; ?>" class="form-control custom_input" readonly />
                          </div>					
                        </div>
                       
                        <div class="col-xl-4 col-lg-4"><?php /* Batch ID */ ?>
                          <div class="form-group">
                            <label class="form_label"> Batch ID  <sup class="text-danger">*</sup></label>
                            <input type="text" value="<?php echo $form_data[0]['centre_batch_id']; ?>" class="form-control custom_input" readonly />
                          </div>					
                        </div>
                        
                        <div class="col-xl-4 col-lg-4"><?php /* Status */ ?>
                          <div class="form-group">
                            <label class="form_label">Status <sup class="text-danger">*</sup></label>
                            <div><span class="disp_status_details badge <?php echo show_batch_status($form_data[0]['batch_status']); ?>" style="min-width:90px;"><?php echo $form_data[0]['DispBatchStatus']; ?></span></div>
                          </div>					
                        </div>
                      <?php } ?>
                      
                      <div class="col-xl-12 col-lg-12">
                        <div class="row">
                          <div class="col-xl-4 col-lg-4"><?php /* Batch Type */ ?>
                            <div class="form-group">
                              <label for="batch_type" class="form_label">Batch Type <sup class="text-danger">*</sup></label>
                              <div id="batch_type_err">
                                <?php if($mode == 'Add' || $form_data[0]['batch_status'] == '8') { ?>  
                                  <?php 
                                  //HIDE BASIC BATCH OPTION FOR AGENCY CODE 1019 - NAR AGENCY
                                  if(isset($_SESSION['IIBF_BCBF_AGENCY_CODE']) && !in_array($_SESSION['IIBF_BCBF_AGENCY_CODE'], array('1019'))) 
                                  { ?>
                                    <label class="css_checkbox_radio radio_only"> Basic
                                      <input type="radio" value="1" name="batch_type" required <?php if($mode == "Add") { if(set_value('batch_type') == '1') { echo "checked"; } } else { if($form_data[0]['batch_type'] == '1') { echo "checked"; } } ?> onchange="batch_type_change(); get_training_language_ajax(this.value);">
                                      <span class="radiobtn"></span>
                                    </label>
                                  <?php } ?>
                                  <label class="css_checkbox_radio radio_only"> Advanced
                                    <input type="radio" value="2" name="batch_type" required <?php if($mode == "Add") { if(set_value('batch_type') == '2') { echo "checked"; } } else { if($form_data[0]['batch_type'] == '2') { echo "checked"; } } ?> onchange="batch_type_change(); get_training_language_ajax(this.value);">
                                    <span class="radiobtn"></span>
                                  </label>
                                <?php }
                                else 
                                {
                                  $input_batch_type_val = '1';
                                  $input_batch_type_name = 'Basic';
                                  if($form_data[0]['batch_type'] == '2') 
                                  { 
                                    $input_batch_type_val = '2';
                                    $input_batch_type_name = 'Advanced';
                                  } ?>
                                  
                                  <label class="css_checkbox_radio radio_only"> <?php echo $input_batch_type_name; ?>
                                    <input type="radio" value="<?php echo $input_batch_type_val; ?>" name="batch_type" required checked>
                                    <span class="radiobtn"></span>
                                  </label>                                  
                                <?php } ?>
                              </div>
                              <?php if(form_error('batch_type')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('batch_type'); ?></label> <?php } ?>
                            </div>					
                          </div>
                          
                          <div class="col-xl-4 col-lg-4"><?php /* No. of Hours */ ?>
                            <div class="form-group">
                              <label for="batch_hours" class="form_label">No. of Hours <sup class="text-danger">*</sup></label>
                              
                              <input type="text" name="batch_hours" id="batch_hours" value="<?php if($mode == "Add") { echo set_value('batch_hours'); } else { echo $form_data[0]['batch_hours']; } ?>" placeholder="Batch Hours *" class="form-control custom_input" required readonly />
                              
                              <?php if(form_error('batch_hours')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('batch_hours'); ?></label> <?php } ?>
                            </div>					
                          </div>
                        </div>
                      </div>
                      
                      <?php /*if($mode == "Add") { $chk_centre = set_value('centre_id'); } else { $chk_centre = $form_data[0]['centre_id']; } ?>
                        <div class="col-xl-6 col-lg-6"><?php // Centre Name  ?>
												<div class="form-group">
                        <label for="centre_id" class="form_label">Centre Name <sup class="text-danger">*</sup></label>
                        <select name="centre_id" id="centre_id" class="form-control chosen-selectt" required onchange="validate_input('centre_id'); ">
                        <?php if(count($centre_master_data) > 0)
                        { ?>
                        <option value="">Select Centre</option>
                        <?php foreach($centre_master_data as $centre_res)
                        { ?>
                        <option value="<?php echo $centre_res['centre_id']; ?>" <?php if($chk_centre == $centre_res['centre_id']) { echo 'selected'; } ?>><?php echo $centre_res['city_name']; ?></option>
                        <?php }
                        }
                        else 
                        { ?>
                        <option value="">No Centre Available</option>
                        <?php } ?>
                        </select>
                        <span id="centre_id_err"></span>
                        <?php if(form_error('centre_id')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('centre_id'); ?></label> <?php } ?>
                        </div>					
                      </div> */ ?>                    
                      
                      <div class="col-xl-12 col-lg-12">
                        <div class="row">
                          <div class="col-xl-12 col-lg-12">
                            <strong class="d-block mb-2">Batch Training Period</strong>
                          </div>
                          
                          <div class="col-xl-4 col-lg-4"><?php /* Batch Training From Date */ ?>
                            <div class="form-group">
                              <label for="batch_start_date" class="form_label">From <sup class="text-danger">*</sup></label>
                              <input type="text" name="batch_start_date" id="batch_start_date" value="<?php if($mode == "Add") { echo set_value('batch_start_date'); } else { if($form_data[0]['batch_start_date'] != '0000-00-00') { echo $form_data[0]['batch_start_date']; } } ?>" placeholder="Batch Training From Date *" class="form-control custom_input" onchange="calculate_training_days(); validate_input('batch_start_date'); validate_input('batch_gross_days');" onclick="calculate_training_days();" required readonly />
                              
                              <?php /*<note class="form_note" id="batch_start_date_err">Note: Please Select From Date greater than <?php echo date('Y-m-d', strtotime("-1day", strtotime($chk_batch_start_date))); ?></note> */?>
                              <note class="form_note" id="batch_start_date_err">Note: Please Select the Date between <?php echo date('Y-m-d', strtotime($chk_batch_start_date)); ?> and <?php echo date('Y-m-d', strtotime("+90day", strtotime($chk_batch_start_date))); ?></note>
                              
                              <?php if(form_error('batch_start_date')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('batch_start_date'); ?></label> <?php } ?>
                            </div>				
                          </div>
                          
                          <div class="col-xl-4 col-lg-4"><?php /* Batch Training To Date */ ?>
                            <div class="form-group">
                              <label for="batch_end_date" class="form_label">To <sup class="text-danger">*</sup></label>
                              <input type="text" name="batch_end_date" id="batch_end_date" value="<?php if($mode == "Add") { echo set_value('batch_end_date'); } else { if($form_data[0]['batch_end_date'] != '0000-00-00') { echo $form_data[0]['batch_end_date']; } } ?>" placeholder="Batch Training To Date *" class="form-control custom_input" onchange="calculate_training_days(); validate_input('batch_end_date'); validate_input('batch_gross_days');" onclick="calculate_training_days();" required readonly />
                              
                              <?php if(form_error('batch_end_date')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('batch_end_date'); ?></label> <?php } ?>
                            </div>				
                          </div>
                          
                          <div class="col-xl-4 col-lg-4"><?php /* Gross Training Days */ ?>
                            <div class="form-group">
                              <label class="form_label">Gross Training Days <sup class="text-danger">*</sup></label>
                              <input type="text" id="batch_gross_days" name="batch_gross_days" value="<?php if($mode == "Add") { echo set_value('batch_gross_days'); } else { echo $form_data[0]['batch_gross_days']; } ?>" placeholder="Gross Training Days *" class="form-control custom_input" readonly />
                              
                              <note class="form_note" id="batch_gross_days_err">Note: Gross Days should be less than or equal to <?php echo $chk_gross_training_days_basic; ?>.</note>
                              
                              <?php if(form_error('batch_gross_days')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('batch_gross_days'); ?></label> <?php } ?>
                            </div>				
                          </div>                          
                        </div>
                      </div>                      
                      
                      <div class="col-xl-12 col-lg-12">
                        <div class="row">
                          <div class="col-xl-12 col-lg-12">
                            <strong class="d-block mb-2">Holidays</strong>
                          </div>
                          
                          <div class="col-xl-6 col-lg-6"><?php /* Holidays */ ?>
                            <div class="form-group">
                              <label for="batch_holidays" class="form_label">Select Holidays <sup class="text-danger"></sup></label>
                              <input type="text" name="batch_holidays" id="batch_holidays" value="<?php if($mode == "Add") { echo set_value('batch_holidays'); } else { echo $form_data[0]['batch_holidays']; } ?>" placeholder="Holidays" class="form-control custom_input" readonly onchange="sort_holidays_dates()" onblur="sort_holidays_dates()" />
                              
                              <?php if(form_error('batch_holidays')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('batch_holidays'); ?></label> <?php } ?>
                            </div>				
                          </div>
                          
                          <div class="col-xl-6 col-lg-6"><?php /* Net Training Days */ ?>
                            <div class="form-group">
                              <label class="form_label">Net Training Days <sup class="text-danger">*</sup></label>
                              <input type="text" id="batch_net_days" name="batch_net_days" value="<?php if($mode == "Add") { echo set_value('batch_net_days'); } else { echo $form_data[0]['batch_net_days']; } ?>" placeholder="Net Training Days *" class="form-control custom_input" readonly />
                              
                              <?php if(form_error('batch_net_days')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('batch_net_days'); ?></label> <?php } ?>
                            </div>				
                          </div>                          
                        </div>
                      </div>  
                      
                      <div class="col-xl-12 col-lg-12">
                        <div class="row">    
                          <div class="col-xl-12 col-lg-12">
                            <strong class="d-block mb-2">Daily Training Time</strong>
                          </div>
                          
                          <div class="col-xl-4 col-lg-4"><?php /* Daily Training Start Time */ ?>
                            <div class="form-group">
                              <label for="batch_daily_start_time" class="form_label">From <sup class="text-danger">*</sup></label>
                              <input type="text" name="batch_daily_start_time" id="batch_daily_start_time" value="<?php if($mode == "Add") { echo set_value('batch_daily_start_time'); } else { if($form_data[0]['batch_daily_start_time'] != "") { echo date("h:iA", strtotime($form_data[0]['batch_daily_start_time'])); } } ?>" placeholder="Daily Training Start Time *" class="form-control custom_input clockpicker_start_end_time" required readonly />
                              
                              <?php if(form_error('batch_daily_start_time')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('batch_daily_start_time'); ?></label> <?php } ?>
                            </div>				
                          </div>
                          
                          <div class="col-xl-4 col-lg-4"><?php /* Daily Training End Time */ ?>
                            <div class="form-group">
                              <label for="batch_daily_end_time" class="form_label">To <sup class="text-danger">*</sup></label>
                              <input type="text" name="batch_daily_end_time" id="batch_daily_end_time" value="<?php if($mode == "Add") { echo set_value('batch_daily_end_time'); } else { if($form_data[0]['batch_daily_end_time'] != "") { echo date("h:iA", strtotime($form_data[0]['batch_daily_end_time'])); } } ?>" placeholder="Daily Training End Time *" class="form-control custom_input clockpicker_start_end_time" required readonly />
                              
                              <?php if(form_error('batch_daily_end_time')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('batch_daily_end_time'); ?></label> <?php } ?>
                            </div>				
                          </div>
                          
                          <div class="col-xl-4 col-lg-4"><?php /* Gross Training Time Per Day */ ?>
                            <div class="form-group">
                              <label class="form_label">Gross Training Time Per Day <sup class="text-danger">*</sup></label>
                              <input type="text" id="batch_daily_gross_time" name="batch_daily_gross_time" value="<?php if($mode == "Add") { echo set_value('batch_daily_gross_time'); } else { echo $form_data[0]['batch_daily_gross_time']; } ?>" placeholder="Gross Training Time Per Day *" class="form-control custom_input" readonly />
                              
                              <note class="form_note" id="batch_daily_gross_time_err">Note : Gross Time should be less than or equal to <?php echo $chk_gross_training_time_per_day; ?> Hours.</note>
                              
                              <?php if(form_error('batch_daily_gross_time')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('batch_daily_gross_time'); ?></label> <?php } ?>
                            </div>				
                          </div>                          
                        </div> 
                      </div>                      
                      
                      <div class="col-xl-12 col-lg-12">
                        <div class="row"> 
                          <div class="col-xl-12 col-lg-12">
                            <strong class="d-block mb-2">Daily Break Time</strong>
                          </div>
                          
                          <div class="col-xl-3 col-lg-3">
                            <span class="d-block mb-2 break-head">Break Time1</span>
                            <div class="row">
                              <div class="col-xl-6 col-lg-6"><?php /* Break Start Time1 */ ?>
                                <div class="form-group">
                                  <label for="break_start_time1" class="form_label">From <sup class="text-danger">*</sup></label>
                                  <input type="text" name="break_start_time1" id="break_start_time1" value="<?php if($mode == "Add") { echo set_value('break_start_time1'); } else { if($form_data[0]['break_start_time1'] != "") { echo date("h:iA", strtotime($form_data[0]['break_start_time1'])); } } ?>" placeholder="Break Start Time1 *" class="form-control custom_input clockpicker_break_time1" required readonly />
                                  
                                  <?php if(form_error('break_start_time1')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('break_start_time1'); ?></label> <?php } ?>
                                </div>				
                              </div>
                              
                              <div class="col-xl-6 col-lg-6"><?php /* Break End Time1 */ ?>
                                <div class="form-group">
                                  <label for="break_end_time1" class="form_label">To <sup class="text-danger">*</sup></label>
                                  <input type="text" name="break_end_time1" id="break_end_time1" value="<?php if($mode == "Add") { echo set_value('break_end_time1'); } else { if($form_data[0]['break_end_time1'] != "") { echo date("h:iA", strtotime($form_data[0]['break_end_time1'])); } } ?>" placeholder="Break End Time1 *" class="form-control custom_input clockpicker_break_time1" required readonly />
                                  
                                  <?php if(form_error('break_end_time1')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('break_end_time1'); ?></label> <?php } ?>
                                </div>				
                              </div>
                            </div>
                          </div>                          
                          
                          <div class="col-xl-3 col-lg-3">  
                            <span class="d-block mb-2 break-head">Break Time2</span>
                            <div class="row">
                              <div class="col-xl-6 col-lg-6"><?php /* Break Start Time2 */ ?>
                                <div class="form-group">
                                  <label for="break_start_time2" class="form_label">From <sup class="text-danger">*</sup></label>
                                  <input type="text" name="break_start_time2" id="break_start_time2" value="<?php if($mode == "Add") { echo set_value('break_start_time2'); } else { if($form_data[0]['break_start_time2'] != "") { echo date("h:iA", strtotime($form_data[0]['break_start_time2'])); } } ?>" placeholder="Break Start Time2 *" class="form-control custom_input clockpicker_break_time2" required readonly />
                                  
                                  <?php if(form_error('break_start_time2')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('break_start_time2'); ?></label> <?php } ?>
                                </div>				
                              </div>
                              
                              <div class="col-xl-6 col-lg-6"><?php /* Break End Time2 */ ?>
                                <div class="form-group">
                                  <label for="break_end_time2" class="form_label">To <sup class="text-danger">*</sup></label>
                                  <input type="text" name="break_end_time2" id="break_end_time2" value="<?php if($mode == "Add") { echo set_value('break_end_time2'); } else { if($form_data[0]['break_end_time2'] != "") { echo date("h:iA", strtotime($form_data[0]['break_end_time2'])); } } ?>" placeholder="Break End Time2 *" class="form-control custom_input clockpicker_break_time2" required readonly />
                                  
                                  <?php if(form_error('break_end_time2')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('break_end_time2'); ?></label> <?php } ?>
                                </div>				
                              </div>
                            </div>
                          </div>                          
                          
                          <div class="col-xl-3 col-lg-3">  
                            <span class="d-block mb-2 break-head">Break Time3</span>
                            <div class="row">
                              <div class="col-xl-6 col-lg-6"><?php /* Break Start Time3 */ ?>
                                <div class="form-group">
                                  <label for="break_start_time3" class="form_label">From<sup class="text-danger">*</sup></label>
                                  <input type="text" name="break_start_time3" id="break_start_time3" value="<?php if($mode == "Add") { echo set_value('break_start_time3'); } else { if($form_data[0]['break_start_time3'] != "") { echo date("h:iA", strtotime($form_data[0]['break_start_time3'])); } } ?>" placeholder="Break Start Time3 *" class="form-control custom_input clockpicker_break_time3" required readonly />
                                  
                                  <?php if(form_error('break_start_time3')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('break_start_time3'); ?></label> <?php } ?>
                                </div>				
                              </div>
                              
                              <div class="col-xl-6 col-lg-6"><?php /* Break End Time3 */ ?>
                                <div class="form-group">
                                  <label for="break_end_time3" class="form_label">To <sup class="text-danger">*</sup></label>
                                  <input type="text" name="break_end_time3" id="break_end_time3" value="<?php if($mode == "Add") { echo set_value('break_end_time3'); } else { if($form_data[0]['break_end_time3'] != "") { echo date("h:iA", strtotime($form_data[0]['break_end_time3'])); } } ?>" placeholder="Break End Time3 *" class="form-control custom_input clockpicker_break_time3" required readonly />
                                  
                                  <?php if(form_error('break_end_time3')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('break_end_time3'); ?></label> <?php } ?>
                                </div>				
                              </div>  
                            </div>
                          </div>
                          
                          <div class="col-xl-3 col-lg-3"><?php /* Total Break Time */ ?>
                            <span class="d-block mb-4"></span>
                            <div class="form-group">
                              <label class="form_label">Total Break Time <sup class="text-danger">*</sup></label>
                              <input type="text" id="total_daily_break_time" name="total_daily_break_time" value="<?php if($mode == "Add") { echo set_value('total_daily_break_time'); } else { echo $form_data[0]['total_daily_break_time']; } ?>" placeholder="Total Break Time *" class="form-control custom_input" readonly />
                              
                              <note class="form_note" id="total_daily_break_time_err">Note : Total Break Time should be less than or equal to <?php echo $chk_total_break_time; ?> minutes.</note>
                              
                              <?php if(form_error('total_daily_break_time')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('total_daily_break_time'); ?></label> <?php } ?>
                            </div>				
                          </div>
                        </div>
                      </div>
                      
                      <div class="col-xl-4 col-lg-4"><?php /* Net Training Time Per Day  */ ?>
                        <div class="form-group">
                          <label class="form_label">Net Training Time Per Day <sup class="text-danger">*</sup></label>
                          <input type="text" id="batch_daily_net_time" name="batch_daily_net_time" value="<?php if($mode == "Add") { echo set_value('batch_daily_net_time'); } else { echo $form_data[0]['batch_daily_net_time']; } ?>" placeholder="Net Training Time Per Day *" class="form-control custom_input" readonly />
                          
                          <note class="form_note" id="batch_daily_net_time_err">Note : Net Time should be greater than or equal to <?php echo $chk_min_net_training_time_per_day; ?> Hours & less than or equal to <?php echo $chk_net_training_time_per_day; ?> Hours.</note>
                          
                          <?php if(form_error('batch_daily_net_time')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('batch_daily_net_time'); ?></label> <?php } ?>
                        </div>				
                      </div>
                      
                      <div class="col-xl-4 col-lg-4"><?php /* Total Net Training Time of Duration  */ ?>
                        <div class="form-group">
                          <label class="form_label">Total Net Training Time of Duration <sup class="text-danger">*</sup></label>
                          <input type="text" id="batch_total_net_time" name="batch_total_net_time" value="<?php if($mode == "Add") { echo set_value('batch_total_net_time'); } else { echo $form_data[0]['batch_total_net_time']; } ?>" placeholder="Total Net Training Time of Duration *" class="form-control custom_input" readonly />
                          
                          <note class="form_note" id="batch_total_net_time_err">Note: Total Net Training Time of Duration should be greater than or equal to <?php echo $chk_total_net_training_time_of_duration_basic; ?> Hours.</note>
                          
                          <?php if(form_error('batch_total_net_time')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('batch_total_net_time'); ?></label> <?php } ?>
                        </div>				
                      </div>

                      <?php $chk_language_known = array();
                      $language_known_arr = array('ASSAMESE', 'BENGALI', 'ENGLISH', 'GUJARATI', 'HINDI', 'KANNADA', 'MALAYALAM', 'MARATHI', 'ORIYA', 'TAMIL', 'TELUGU');
                      if($mode == "Add") { $chk_language_known = set_value('training_language'); } else { $chk_language_known = $form_data[0]['training_language']; } ?>
                      <div class="col-xl-12 col-lg-12">
                        <div class="row"> 
                          <div class="col-xl-4 col-lg-4"><?php // Training Language  ?>
                            <div class="form-group">
                              <label for="training_language" class="form_label">Training Language <sup class="text-danger">*</sup></label>
                              <div id="training_language_outer">
                                <select class="form-control" name="training_language" id="training_language" required onchange="validate_input('training_language'); ">
                                  <?php                                     
                                  if(count($language_known_arr) > 0)
                                  { ?>
                                    <option value="">Select Training Language</option>
                                    <?php foreach($language_known_arr as $res)
                                    { ?>
                                      <option value="<?php echo $res; ?>" <?php if($chk_language_known == $res) { echo "selected"; } ?>><?php echo $res; ?></option>
                                    <?php }
                                  }
                                  else
                                  { ?>
                                    <option value="">No Training Language Available</option>
                                  <?php } ?>
                                </select>
                              </div>
                              <span id="training_language_err"></span>
                              <?php if(form_error('training_language')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('training_language'); ?></label> <?php } ?>
                            </div>					
                          </div>
                        </div>                        
                      </div>
                      

                      <?php /* if($mode == "Add") { $chk_lang = set_value('training_language'); } else { $chk_lang = $form_data[0]['training_language']; } ?>
                      <div class="col-xl-12 col-lg-12">
                        <div class="row"> 
                          <div class="col-xl-4 col-lg-4"><?php // Training Language  ?>
                            <div class="form-group">
                              <label for="training_language" class="form_label">Training Language <sup class="text-danger">*</sup></label>
                              <div id="training_language_outer">
                                <select class="form-control chosen-selectx" name="training_language" id="training_language" required onchange="validate_input('training_language'); ">
                                  <?php $selected_batch_type_val = '';
                                  if($mode == "Add")
                                  {
                                    if(set_value('batch_type') != "") { $selected_batch_type_val = set_value('batch_type'); }
                                  }
                                  else { $selected_batch_type_val = $form_data[0]['batch_type']; }
                                  
                                  if($selected_batch_type_val != "")
                                  {
                                    $chk_exam_code  = $this->Iibf_bcbf_model->get_exam_code($selected_batch_type_val);
                                    
                                    $medium_master = $this->master_model->getRecords('iibfbcbf_exam_medium_master', array('exam_code' => $chk_exam_code), 'id, exam_code, medium_code, medium_description', array('medium_description'=>'ASC'));
                                    
                                    if(count($medium_master) > 0)
                                    { ?>
                                      <option value="">Select Training Language</option>
                                      <?php foreach($medium_master as $res)
                                      { ?>
                                        <option value="<?php echo $res['medium_description']; ?>" <?php if($mode == "Add") { if(set_value('training_language') == $res['medium_description']) { echo "selected"; } } else { if($form_data[0]['training_language'] == $res['medium_description']) { echo "selected"; } } ?>><?php echo $res['medium_description']; ?></option>
                                    <?php }
                                      }
                                      else
                                      { ?>
                                        <option value="">No Training Language Available</option>
                                    <?php }
                                  }
                                  else 
                                  {
                                    echo '<option value="">Select Training Language</option>';
                                  } ?>
                                </select>
                              </div>
                              <span id="training_language_err"></span>
                              <?php if(form_error('training_language')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('training_language'); ?></label> <?php } ?>
                            </div>					
                          </div>
                        </div>                        
                      </div> */ ?>
                      
                      <?php if($mode == "Add") { $chk_under_graduate = set_value('under_graduate_candidates'); } else { $chk_under_graduate = $form_data[0]['under_graduate_candidates']; } ?>
                      <div class="col-xl-12 col-lg-12">
                        <div class="row">
                          <div class="col-xl-12 col-lg-12">
                            <strong class="d-block mb-2">No. of Candidate</strong>
                          </div>
                          
                          <div class="col-xl-3 col-lg-3"><?php // Under Graduate Candidates  ?>
                            <div class="form-group">
                              <label for="under_graduate_candidates" class="form_label">Under Graduate <sup class="text-danger">*</sup></label>
                              <select name="under_graduate_candidates" id="under_graduate_candidates" class="form-control chosen-selectt" required onchange="validate_input('under_graduate_candidates'); calculate_total_candidate();">
                                <option value="">Select Under Graduate Candidates</option>
                                <?php for ($i = 0; $i <= 35; $i++) { ?>
                                  <option value="<?php echo $i; ?>" <?php if($chk_under_graduate != "" && $chk_under_graduate == $i) { echo 'selected'; } ?>><?php echo $i; ?></option>
                                <?php } ?>
                              </select>
                              <span id="under_graduate_candidates_err"></span>
                              <?php if(form_error('under_graduate_candidates')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('under_graduate_candidates'); ?></label> <?php } ?>
                            </div>					
                          </div>
                          
                          <?php if($mode == "Add") { $chk_graduate = set_value('graduate_candidates'); } else { $chk_graduate = $form_data[0]['graduate_candidates']; } ?>
                          <div class="col-xl-3 col-lg-3"><?php // Graduate Candidates  ?>
                            <div class="form-group">
                              <label for="graduate_candidates" class="form_label">Graduate<sup class="text-danger">*</sup></label>
                              <select name="graduate_candidates" id="graduate_candidates" class="form-control chosen-selectt" required onchange="validate_input('graduate_candidates'); calculate_total_candidate();">
                                <option value="">Select Graduate Candidates</option>
                                <?php for ($i = 0; $i <= 35; $i++) { ?>
                                  <option value="<?php echo $i; ?>" <?php if($chk_graduate != "" && $chk_graduate == $i) { echo 'selected'; } ?>><?php echo $i; ?></option>
                                <?php } ?>
                              </select>
                              <span id="graduate_candidatess_err"></span>
                              <?php if(form_error('graduate_candidates')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('graduate_candidates'); ?></label> <?php } ?>
                            </div>					
                          </div>
                          
                          <?php if($mode == "Add") { $chk_post_graduate = set_value('post_graduate_candidates'); } else { $chk_post_graduate = $form_data[0]['post_graduate_candidates']; } ?>
                          <div class="col-xl-3 col-lg-3"><?php // Post Graduate Candidates  ?>
                            <div class="form-group">
                              <label for="post_graduate_candidates" class="form_label">Post Graduate<sup class="text-danger">*</sup></label>
                              <select name="post_graduate_candidates" id="post_graduate_candidates" class="form-control chosen-selectt" required onchange="validate_input('post_graduate_candidates'); calculate_total_candidate();">
                                <option value="">Select Post Graduate Candidates</option>
                                <?php for ($i = 0; $i <= 35; $i++) { ?>
                                  <option value="<?php echo $i; ?>" <?php if($chk_post_graduate != "" && $chk_post_graduate == $i) { echo 'selected'; } ?>><?php echo $i; ?></option>
                                <?php } ?>
                              </select>
                              <span id="post_graduate_candidates_err"></span>
                              <?php if(form_error('post_graduate_candidates')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('post_graduate_candidates'); ?></label> <?php } ?>
                            </div>					
                          </div>
                          
                          <div class="col-xl-3 col-lg-3"><?php /* Total Candidates */ ?>
                            <div class="form-group">
                              <label class="form_label">Total Candidates <sup class="text-danger">*</sup></label>
                              <input type="text" id="total_candidates" name="total_candidates" value="<?php if($mode == "Add") { echo set_value('total_candidates'); } else { echo $form_data[0]['total_candidates']; } ?>" placeholder="Total Candidates *" class="form-control custom_input" readonly />
                              
                              <?php if(form_error('total_candidates')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('total_candidates'); ?></label> <?php } ?>
                            </div>
                          </div>
                        </div>  
                      </div>
                      
                      <?php if($mode == "Add") { $chk_faculty1 = set_value('first_faculty'); } else { $chk_faculty1 = $form_data[0]['first_faculty']; } ?>
                      <div class="col-xl-12 col-lg-12">
                        <div class="row">
                          <div class="col-xl-12 col-lg-12">
                            <strong class="d-block mb-2">Faculty Details</strong>
                          </div>
                          
                          <div class="col-xl-3 col-lg-3"><?php // Faculty1  ?>
                            <div class="form-group">
                              <label for="first_faculty" class="form_label">Select Faculty1 <sup class="text-danger">*</sup></label>
                              <select name="first_faculty" id="first_faculty" class="form-control chosen-selectt" required onchange="validate_input('first_faculty'); faculty_enable_disable()">
                                <?php if(count($faculty_master) > 0)
                                  { ?>
                                  <option value="">Select Faculty1</option>
                                  <?php foreach($faculty_master as $res)
                                    { ?>
                                    <option value="<?php echo $res['faculty_id']; ?>" <?php if($chk_faculty1 == $res['faculty_id']) { echo 'selected'; } ?>><?php echo $res['salutation'].' '.$res['faculty_name']; ?></option>
                                    <?php }
                                  }
                                  else 
                                  { ?>
                                  <option value="">Faculty Not Available</option>
                                <?php } ?>
                              </select>
                              <span id="first_faculty_err"></span>
                              <?php if(form_error('first_faculty')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('first_faculty'); ?></label> <?php } ?>
                            </div>					
                          </div>
                          
                          <?php if($mode == "Add") { $chk_faculty2 = set_value('second_faculty'); } else { $chk_faculty2 = $form_data[0]['second_faculty']; } ?>
                          <div class="col-xl-3 col-lg-3"><?php // Faculty2  ?>
                            <div class="form-group">
                              <label for="second_faculty" class="form_label">Select Faculty2 <sup class="text-danger">*</sup></label>
                              <select name="second_faculty" id="second_faculty" class="form-control chosen-selectt" required onchange="validate_input('second_faculty'); faculty_enable_disable()">
                                <?php if(count($faculty_master) > 0)
                                  { ?>
                                  <option value="">Select Faculty2</option>
                                  <?php foreach($faculty_master as $res)
                                    { ?>
                                    <option value="<?php echo $res['faculty_id']; ?>" <?php if($chk_faculty2 == $res['faculty_id']) { echo 'selected'; } ?>><?php echo $res['salutation'].' '.$res['faculty_name']; ?></option>
                                    <?php }
                                  }
                                  else 
                                  { ?>
                                  <option value="">Faculty Not Available</option>
                                <?php } ?>
                              </select>
                              <span id="second_faculty_err"></span>
                              <?php if(form_error('second_faculty')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('second_faculty'); ?></label> <?php } ?>
                            </div>					
                          </div>
                          
                          <?php if($mode == "Add") { $chk_faculty3 = set_value('third_faculty'); } else { $chk_faculty3 = $form_data[0]['third_faculty']; } ?>
                          <div class="col-xl-3 col-lg-3"><?php // Faculty3  ?>
                            <div class="form-group">
                              <label for="third_faculty" class="form_label">Select Faculty3 <sup class="text-danger"></sup></label>
                              <select name="third_faculty" id="third_faculty" class="form-control chosen-selectt" onchange="validate_input('third_faculty'); faculty_enable_disable()">
                                <?php if(count($faculty_master) > 0)
                                  { ?>
                                  <option value="">Select Faculty3</option>
                                  <?php foreach($faculty_master as $res)
                                    { ?>
                                    <option value="<?php echo $res['faculty_id']; ?>" <?php if($chk_faculty3 == $res['faculty_id']) { echo 'selected'; } ?>><?php echo $res['salutation'].' '.$res['faculty_name']; ?></option>
                                    <?php }
                                  }
                                  else 
                                  { ?>
                                  <option value="">Faculty Not Available</option>
                                <?php } ?>
                              </select>
                              <span id="third_faculty_err"></span>
                              <?php if(form_error('third_faculty')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('third_faculty'); ?></label> <?php } ?>
                            </div>					
                          </div>
                          
                          <?php if($mode == "Add") { $chk_faculty4 = set_value('fourth_faculty'); } else { $chk_faculty4 = $form_data[0]['fourth_faculty']; } ?>
                          <div class="col-xl-3 col-lg-3"><?php // Faculty4  ?>
                            <div class="form-group">
                              <label for="fourth_faculty" class="form_label">Select Faculty4 <sup class="text-danger"></sup></label>
                              <select name="fourth_faculty" id="fourth_faculty" class="form-control chosen-selectt" onchange="validate_input('fourth_faculty'); faculty_enable_disable()">
                                <?php if(count($faculty_master) > 0)
                                  { ?>
                                  <option value="">Select Faculty4</option>
                                  <?php foreach($faculty_master as $res)
                                    { ?>
                                    <option value="<?php echo $res['faculty_id']; ?>" <?php if($chk_faculty4 == $res['faculty_id']) { echo 'selected'; } ?>><?php echo $res['salutation'].' '.$res['faculty_name']; ?></option>
                                    <?php }
                                  }
                                  else 
                                  { ?>
                                  <option value="">Faculty Not Available</option>
                                <?php } ?>
                              </select>
                              <span id="fourth_faculty_err"></span>
                              <?php if(form_error('fourth_faculty')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('fourth_faculty'); ?></label> <?php } ?>
                            </div>					
                          </div>                          
                        </div>  
                      </div>                      
                      
                      <div class="col-xl-6 col-lg-6"><?php // Upload Training Schedule ?>
                        <div class="form-group">
                          <div class="img_preview_input_outer pull-left">
                            <label for="training_schedule_file" class="form_label">Upload Training Schedule <sup class="text-danger">*</sup></label>
                            <input type="file" name="training_schedule_file" id="training_schedule_file" class="form-control" accept=".txt,.doc,.docx,.pdf" data-accept=".txt,.doc,.docx,.pdf" onchange="show_preview(event, 'training_schedule_file_preview'); validate_input('training_schedule_file');" <?php if($mode == 'Add' || ($mode == 'Update' && $form_data[0]['training_schedule_file'] == "")) { echo 'required'; } ?> />
                            
                            <note class="form_note" id="training_schedule_file_err">Note: Please Upload only .txt, .doc, .docx, .pdf Files with size upto 5 MB</note>
                            
                            <?php if(form_error('training_schedule_file')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('training_schedule_file'); ?></label> <?php } ?>
                            <?php if($training_schedule_file_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $training_schedule_file_error; ?></label> <?php } ?>
                            
                            <?php if($mode == 'Update' && $form_data[0]['training_schedule_file'] != "")
                              { ?>
                              <br><a href="<?php echo site_url('iibfbcbf/download_file_common/index/'.$form_data[0]['batch_id'].'/training_schedule_file'); ?>" class="example-image-link btn btn-success">Download Uploaded Training Schedule File</a>
                            <?php } ?>
                          </div>
                          
                          <div id="training_schedule_file_preview" class="upload_img_preview pull-right">
                            <?php echo '<i class="fa fa-picture-o" aria-hidden="true"></i>'; ?>
                          </div>
                        </div>
                      </div>
                    </div> 
                    
                    <h4 class="custom_form_title" style="margin: 20px -20px 15px -20px !important;">Venue of Training Batch</h4>
                    
                    <div class="row">
                      <div class="col-xl-3 col-lg-3"><?php /* State */ ?>
                        <div class="form-group">
                          <label class="form_label">State</label>
                          <input type="text" value="<?php if(isset($centre_data) && count($centre_data) > 0) { echo $centre_data[0]['state_name']; } ?>" placeholder="State" class="form-control custom_input" readonly />
                        </div>				
                      </div>
                      
                      <div class="col-xl-3 col-lg-3"><?php /* District */ ?>
                        <div class="form-group">
                          <label class="form_label">District</label>
                          <input type="text" value="<?php if(isset($centre_data) && count($centre_data) > 0) { echo $centre_data[0]['centre_district']; } ?>" placeholder="District" class="form-control custom_input" readonly />
                        </div>				
                      </div>
                      
                      <div class="col-xl-3 col-lg-3"><?php /* City */ ?>
                        <div class="form-group">
                          <label class="form_label">City</label>
                          <input type="text" value="<?php if(isset($centre_data) && count($centre_data) > 0) { echo $centre_data[0]['city_name']; } ?>" placeholder="City" class="form-control custom_input" readonly />
                        </div>				
                      </div>
                      
                      <div class="col-xl-3 col-lg-3"><?php /* Pincode */ ?>
                        <div class="form-group">
                          <label class="form_label">Pincode</label>
                          <input type="text" value="<?php if(isset($centre_data) && count($centre_data) > 0) { echo $centre_data[0]['centre_pincode']; } ?>" placeholder="Pincode" class="form-control custom_input" readonly />
                        </div>				
                      </div>                      
                      
                      <div class="col-xl-12 col-lg-12">
                        <div class="row align-items-centre">
                          <div class="col-xl-12 col-lg-12">
                            <strong class="d-block mb-0">Batch Coordinator Details</strong>
                          </div>
                          
                          <div class="col-xl-4 col-lg-4 mb-2"><?php /* Batch Coordinator Name */ ?>
                            <div class="form-group">
                              <label for="contact_person_name" class="form_label"> Name <sup class="text-danger">*</sup></label>
                              <input type="text" name="contact_person_name" id="contact_person_name" value="<?php if($mode == "Add") { echo set_value('contact_person_name'); } else { echo $form_data[0]['contact_person_name']; } ?>" placeholder="Batch Coordinator Name *" class="form-control custom_input allow_only_alphabets_and_space" maxlength="90" required/>
                              
                              <note class="form_note" id="contact_person_name_err">Note: Please enter only 90 characters</note>
                              
                              <?php if(form_error('contact_person_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('contact_person_name'); ?></label> <?php } ?>
                            </div>					
                          </div>
                          
                          <div class="col-xl-4 col-lg-4 mb-2"><?php /* Batch Coordinator Mobile Number */ ?>
                            <div class="form-group">
                              <label for="contact_person_mobile" class="form_label">Mobile Number <sup class="text-danger">*</sup></label>
                              <input type="text" name="contact_person_mobile" id="contact_person_mobile" value="<?php if($mode == "Add") { echo set_value('contact_person_mobile'); } else { echo $form_data[0]['contact_person_mobile']; } ?>" placeholder="Batch Coordinator Mobile Number *" class="form-control custom_input allow_only_numbers" required maxlength="10" minlength="10" />
                              
                              <?php if(form_error('contact_person_mobile')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('contact_person_mobile'); ?></label> <?php } ?>
                            </div>					
                          </div>

                          <div class="col-xl-4 col-lg-4 mb-2"><?php /* Batch Coordinator Email */ ?>
                            <div class="form-group">
                              <label for="contact_person_email" class="form_label">Email <sup class="text-danger">*</sup></label>
                              <input type="text" name="contact_person_email" id="contact_person_email" value="<?php if($mode == "Add") { echo set_value('contact_person_email'); } else { echo $form_data[0]['contact_person_email']; } ?>" placeholder="Batch Coordinator Email *" class="form-control custom_input" required maxlength="80" />
                              <note class="form_note" id="contact_person_email_err">Note: Please enter only 80 characters</note>
                              
                              <?php if(form_error('contact_person_email')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('contact_person_email'); ?></label> <?php } ?>
                            </div>					
                          </div>
                        </div>
                      </div>                      
                      
                      <div class="col-xl-12 col-lg-12">
                        <div class="row align-items-centre">
                          <div class="col-xl-12 col-lg-12">
                            <strong class="d-block mb-0">Alternative Contact Person</strong>
                          </div>
                          
                          <div class="col-xl-4 col-lg-4 mb-2"><?php /* Alternative Contact Person Name */ ?>
                            <div class="form-group">
                              <label for="alt_contact_person_name" class="form_label"> Name <sup class="text-danger"></sup></label>
                              <input type="text" name="alt_contact_person_name" id="alt_contact_person_name" value="<?php if($mode == "Add") { echo set_value('alt_contact_person_name'); } else { echo $form_data[0]['alt_contact_person_name']; } ?>" placeholder="Alternative Contact Person Name" class="form-control custom_input allow_only_alphabets_and_space" maxlength="90"/>
                              
                              <note class="form_note" id="alt_contact_person_name_err">Note: Please enter only 90 characters</note>
                              
                              <?php if(form_error('alt_contact_person_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('alt_contact_person_name'); ?></label> <?php } ?>
                            </div>					
                          </div>
                          
                          <div class="col-xl-4 col-lg-4 mb-2"><?php /* Alternative Contact Person Mobile Number */ ?>
                            <div class="form-group">
                              <label for="alt_contact_person_mobile" class="form_label">Mobile Number <sup class="text-danger"></sup></label>
                              <input type="text" name="alt_contact_person_mobile" id="alt_contact_person_mobile" value="<?php if($mode == "Add") { echo set_value('alt_contact_person_mobile'); } else { echo $form_data[0]['alt_contact_person_mobile']; } ?>" placeholder="Alternative Contact Person Mobile Number" class="form-control custom_input allow_only_numbers" maxlength="10" minlength="10" />
                              
                              <?php if(form_error('alt_contact_person_mobile')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('alt_contact_person_mobile'); ?></label> <?php } ?>
                            </div>					
                          </div>  

                          <div class="col-xl-4 col-lg-4 mb-2"><?php /* Alternative Contact Person Email */ ?>
                            <div class="form-group">
                              <label for="alt_contact_person_email" class="form_label">Email <sup class="text-danger"></sup></label>
                              <input type="text" name="alt_contact_person_email" id="alt_contact_person_email" value="<?php if($mode == "Add") { echo set_value('alt_contact_person_email'); } else { echo $form_data[0]['alt_contact_person_email']; } ?>" placeholder="Alternative Contact Person Email" class="form-control custom_input" maxlength="80" />
                              <note class="form_note" id="alt_contact_person_email_err">Note: Please enter only 80 characters</note>
                              
                              <?php if(form_error('alt_contact_person_email')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('alt_contact_person_email'); ?></label> <?php } ?>
                            </div>					
                          </div>                          
                        </div> 
                      </div>

                      <?php 
                        $start_val_bank_name = 0;
                        if($mode == 'Add') {  if(set_value('row_cnt_bank_name') && set_value('row_cnt_bank_name') != '') { $start_val_bank_name = set_value('row_cnt_bank_name'); } }
                        else { $start_val_bank_name = count($form_bank_field_id_arr); }                        
                      ?>
                      
                      <input type="hidden" class="form-control" name="row_cnt_bank_name" id="row_cnt_bank_name" value="<?php echo $start_val_bank_name; ?>" />
                      <?php 
                        $bank_field_id_arr = $bank_name_arr = $cand_src_arr = array();
                        
                        if($mode == 'Add') 
                        {
                          if(set_value('bank_field_id_arr') != "") { $bank_field_id_arr = set_value('bank_field_id_arr'); }                        
                            
                          $bank_name_arr = set_value('bank_name_arr'); 
                          $cand_src_arr = set_value('cand_src_arr'); 
                        } 
                        else 
                        { 
                          $bank_field_id_arr = $form_bank_field_id_arr; 
                          $bank_name_arr = $form_bank_name_arr;
                          $cand_src_arr = $form_cand_src_arr;
                        }
                      ?>
                      
                      <div class="col-xl-12 col-lg-12">
                        <table class="table table-bordered custom_inner_tbl">
                          <thead>
                            <tr>
                              <th class="text-center hide"></th>
                              <th class="text-center">
                                Source of Candidates (Bank/Agency) <sup class="text-danger">*</sup>
                                <note class="form_note">Note: Please enter only 30 characters</note>
                              </th>
                              <th class="text-center">
                                Number of Candidates <sup class="text-danger">*</sup>
                              </th>
                              <th class="text-center">Action</th>
                            </tr>
                          </thead> 
                          
                          <tbody id="append_div_bank">
                            <?php  $i=1;                            
                              if(count($bank_field_id_arr) > 0) 
                              { 
                                foreach($bank_field_id_arr as $key => $res)
                                { ?>
                                <tr id="appended_row_bank<?php echo $i; ?>" data-id="<?php echo $i; ?>">
                                  <td class="hide">
                                    <input type="hidden" class="form-control custom_input" name="bank_field_id_arr[]" value="<?php echo $res; ?>">
                                  </td>
                                  
                                  <td>
                                    <input type="text" class="form-control custom_input allow_only_alphabets_and_space" name="bank_name_arr[]" id="bank_name_arr<?php echo $i; ?>" value="<?php echo $bank_name_arr[$i-1]; ?>" placeholder="Source of Candidates (Bank/Agency) *" required maxlength="30" />                                    

                                    <?php if(form_error('bank_name_arr[]')!="" && $bank_name_arr[$i-1] == ''){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('bank_name_arr[]'); ?></label> <?php } ?>
                                  </td>
                                  
                                  <td>
                                    <select class="form-control custom_input allow_only_numbers" name="cand_src_arr[]" id="cand_src_arr<?php echo $i; ?>" required>
                                      <option value="">Select</option>
                                      <?php for ($k = 1; $k <= 35; $k++)
                                      { ?>
                                        <option value="<?php echo $k; ?>" <?php if ($cand_src_arr[$i - 1] == $k) { echo 'selected'; } ?>><?php echo $k; ?></option>
                                      <?php } ?>
                                    </select>
                                    
                                    <?php if(form_error('cand_src_arr[]')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('cand_src_arr[]'); ?></label> <?php } ?>
                                  </td>
                                  
                                  <td class="btn_outer no_wrap"></td>
                                </tr>                    
                                <?php $i++;
                                }                                  
                              } ?> 
                          </tbody>
                        </table>
                      </div>
                      
                      <div class="col-xl-12 col-lg-12"><?php /* Remark */ ?>
												<div class="form-group">
													<label for="remarks" class="form_label">Remark <sup class="text-danger"></sup></label>
													<textarea name="remarks" id="remarks" placeholder="Remark" class="form-control custom_input" maxlength="1000"><?php if($mode == "Add") { echo set_value('remarks'); } else { echo $form_data[0]['remarks']; } ?></textarea>
                          
                          <note class="form_note" id="remarks_err">Note: Please enter only 1000 characters</note>
                          
													<?php if(form_error('remarks')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('remarks'); ?></label> <?php } ?>
                        </div>					
                      </div>
                    </div>
                    
                    <h4 class="custom_form_title" style="margin: 20px -20px 15px -20px !important;">Offline / Online Batch</h4>
                    
                    <div class="row">
                      <div class="col-xl-12 col-lg-12"><?php /* Batch Infrastructure */ ?>
												<div class="form-group">
                          <label for="batch_online_offline_flag" class="form_label">Batch Infrastructure <sup class="text-danger">*</sup></label>
                          <div id="batch_online_offline_flag_err">                              
                            <label class="css_checkbox_radio radio_only"> Offline
                              <input type="radio" value="1" name="batch_online_offline_flag" required <?php if($mode == "Add") { if(set_value('batch_online_offline_flag') == "" || set_value('batch_online_offline_flag') == '1') { echo "checked"; } } else { if($form_data[0]['batch_online_offline_flag'] == '1') { echo "checked"; } } ?> onchange="batch_infrastructure_change()">
                              <span class="radiobtn"></span>
                            </label>
                            <label class="css_checkbox_radio radio_only"> Online
                              <input type="radio" value="2" name="batch_online_offline_flag" required <?php if($mode == "Add") { if(set_value('batch_online_offline_flag') == '2') { echo "checked"; } } else { if($form_data[0]['batch_online_offline_flag'] == '2') { echo "checked"; } } ?> onchange="batch_infrastructure_change()">
                              <span class="radiobtn"></span>
                            </label>
                          </div>
                          <?php if(form_error('batch_online_offline_flag')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('batch_online_offline_flag'); ?></label> <?php } ?>
                        </div>					
                      </div>
                    </div>
                    
                    <div class="row" id="online_batch_outer" <?php if($mode == 'Update' && $form_data[0]['batch_online_offline_flag'] == '2') { } else { ?>style="display:none"<?php } ?>>
                      <div class="col-xl-5 col-lg-5"><?php /* Name of the online training platform used */ ?>
												<div class="form-group">
													<label for="online_training_platform" class="form_label">Name of the online training platform used <sup class="text-danger">*</sup></label>
													<input type="text" name="online_training_platform" id="online_training_platform" value="<?php if($mode == "Add") { echo set_value('online_training_platform'); } else { echo $form_data[0]['online_training_platform']; } ?>" placeholder="Name of the online training platform used *" class="form-control custom_input allow_only_alphabets_and_space" maxlength="50" required/>
                          
                          <note class="form_note" id="online_training_platform_err">Note: Please enter only 50 characters</note>
                          
													<?php if(form_error('online_training_platform')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('online_training_platform'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      
                      <div class="col-xl-7 col-lg-7"><?php /* Link */ ?>
												<div class="form-group">
													<label for="platform_link" class="form_label">Link <sup class="text-danger">*</sup></label>
													<input type="text" name="platform_link" id="platform_link" value="<?php if($mode == "Add") { echo set_value('platform_link'); } else { echo $form_data[0]['platform_link']; } ?>" placeholder="Link *" class="form-control custom_input" required/>
                          
                          <note class="form_note" id="platform_link_err">Note: Please Enter link with mentioned format https://iibf.org.in/</note>
                          
													<?php if(form_error('platform_link')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('platform_link'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      
                      <?php 
                        $start_val = 0;
                        if($mode == 'Add') {  if(set_value('row_cnt') && set_value('row_cnt') != '') { $start_val = set_value('row_cnt'); } }
                        else { $start_val = count($form_field_id_arr); }                        
                      ?>
                      
                      <input type="hidden" class="form-control" name="row_cnt" id="row_cnt" value="<?php echo $start_val; ?>" />
                      <?php 
                        $field_id_arr = $login_id_arr = $password_arr = array();
                        
                        if($mode == 'Add') 
                        { 
                          if(set_value('batch_online_offline_flag') == '2')
                          {
                            if(set_value('field_id_arr') != "") { $field_id_arr = set_value('field_id_arr'); }                        
                            
                            $login_id_arr = set_value('login_id_arr'); 
                            $password_arr = set_value('password_arr'); 
                          }
                        } 
                        else 
                        { 
                          $field_id_arr = $form_field_id_arr; 
                          $login_id_arr = $form_login_id_arr;
                          $password_arr = $form_password_arr;
                        } 
                      ?>
                      
                      <div class="col-xl-12 col-lg-12">
                        <label class="form_label">User Details <sup class="text-danger">*</sup></label><?php /* User Details */ ?>
                        <table class="table table-bordered custom_inner_tbl">
                          <thead>
                            <tr>
                              <th class="text-center hide"></th>
                              <th class="text-center">Login Id</th>
                              <th class="text-center">Password</th>
                              <th class="text-center">Action</th>
                            </tr>
                          </thead> 
                          
                          <tbody id="append_div">
                            <?php  $i=1;                            
                              if(count($field_id_arr) > 0) 
                              { 
                                foreach($field_id_arr as $key => $res)
                                { ?>
                                <tr id="appended_row<?php echo $i; ?>" data-id="<?php echo $i; ?>">
                                  <td class="hide">
                                    <input type="hidden" class="form-control custom_input" name="field_id_arr[]" value="<?php echo $res; ?>">
                                  </td>
                                  
                                  <td>
                                    <input type="text" class="form-control custom_input" name="login_id_arr[]" id="login_id_arr<?php echo $i; ?>" value="<?php echo $login_id_arr[$i-1]; ?>" placeholder="Login Id" required maxlength="50" />
                                    
                                    <?php if(form_error('login_id_arr[]')!="" && $login_id_arr[$i-1] == ''){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('login_id_arr[]'); ?></label> <?php } ?>
                                  </td>
                                  
                                  <td>
                                    <div class="login_password_common">
                                      <input type="password" class="form-control custom_input" name="password_arr[]" id="password_arr<?php echo $i; ?>" value="<?php echo $password_arr[$i-1]; ?>" placeholder="e.g. Manager - ABC123" required maxlength="50" />
                                      
                                      <span class="show-password" onclick="show_hide_password(this,'show', 'password_arr<?php echo $i; ?>')"><i class="fa fa-eye" aria-hidden="true"></i></span>
                                      <span class="hide-password" onclick="show_hide_password(this,'hide', 'password_arr<?php echo $i; ?>')" style="display:none;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
                                    </div>
                                    
                                    <?php if(form_error('password_arr[]')!="" && $password_arr[$i-1] == ''){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('password_arr[]'); ?></label> <?php } ?>
                                  </td>
                                  
                                  <td class="btn_outer no_wrap"></td>
                                </tr>                    
                                <?php $i++;
                                }                                  
                              } ?> 
                          </tbody>
                        </table>
                      </div>
                    </div>
                    
										<div class="hr-line-dashed"></div>										
										<div class="row">
											<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="submit_btn_outer">
                        <?php $show_draft_btn = 0;
                        if($mode == 'Add') { $show_draft_btn = 1; }
                        else { if($form_data[0]['batch_status'] == '8') { $show_draft_btn = 1;  } }

                        if($show_draft_btn == '1')
                        { ?>
												  <button class="btn btn btn-submit mr-3" type="button" onclick="save_as_draft()">Save as Draft</button>
                        <?php } ?>

												<button class="btn btn btn-submit mr-3" type="submit">Submit</button>
												<a class="btn btn btn-submit" href="<?php echo site_url('iibfbcbf/agency/training_batches_agency/'); ?>">Back</a>	
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              
              <div id="common_log_outer"></div>
            </div>					
          </div>
        </div>
				<?php $this->load->view('iibfbcbf/agency/inc_footerbar_agency'); ?>		
      </div>
    </div>
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>		
		<?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>
    
    <?php  if($mode == 'Update') {
      $this->load->view('iibfbcbf/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_batch_id, 'module_slug'=>'batch_action', 'log_title'=>'Training Batch Log'));
    } ?>
    
    <link rel="stylesheet" type="text/css" href="<?php echo auto_version(base_url('assets/iibfbcbf/css/plugins/clockpicker/clockpicker.css')); ?>">
    <script src="<?php echo auto_version(base_url('assets/iibfbcbf/js/plugins/clockpicker/clockpicker.js')); ?>"></script>
    <script type="text/javascript">
      //START : CLOCKPICKER FOR 'DAILY TRAINING START TIME', 'DAILY TRAINING END TIME'
      var input = $('.clockpicker_start_end_time').clockpicker(
      {
        placement: 'bottom',
        align: 'left',
        autoclose: false,
        'default': 'now',
        twelvehour : true,
        donetext:'Done',
        afterDone: function() 
        {
          calculate_training_time();
          calculate_total_break_time();
          
          validate_input('batch_daily_start_time');
          validate_input('batch_daily_end_time');
          validate_input('batch_daily_gross_time');
          
          if($.trim($("#batch_daily_start_time").val()) != "" && $.trim($("#batch_daily_end_time").val()) != "")
          {
            if($.trim($("#break_start_time1").val()) != "") { validate_input('break_start_time1'); }
            if($.trim($("#break_end_time1").val()) != "") { validate_input('break_end_time1'); }
            if($.trim($("#break_start_time2").val()) != "") { validate_input('break_start_time2'); }
            if($.trim($("#break_end_time2").val()) != "") { validate_input('break_end_time2'); }
            if($.trim($("#break_start_time3").val()) != "") { validate_input('break_start_time3'); }
            if($.trim($("#break_end_time3").val()) != "") { validate_input('break_end_time3'); }
          }
        }
      });//END : CLOCKPICKER FOR 'DAILY TRAINING START TIME', 'DAILY TRAINING END TIME' 
      
      //START : CLOCKPICKER FOR 'Break Start Time1', 'Break End Time1'
      var input = $('.clockpicker_break_time1').clockpicker(
      {
        placement: 'bottom',
        align: 'left',
        autoclose: false,
        'default': 'now',
        twelvehour : true,
        donetext:'Done',
        afterDone: function() 
        {
          calculate_training_time();
          calculate_total_break_time();
          
          validate_input('break_start_time1');
          validate_input('break_end_time1');
          
          if($.trim($("#break_start_time2").val()) != "") { validate_input('break_start_time2'); }
          if($.trim($("#break_end_time2").val()) != "") { validate_input('break_end_time2'); }
          
          if($.trim($("#break_start_time3").val()) != "") { validate_input('break_start_time3'); }
          if($.trim($("#break_end_time3").val()) != "") { validate_input('break_end_time3'); }
        }
      });//END : CLOCKPICKER FOR 'Break Start Time1', 'Break End Time1'
      
      //START : CLOCKPICKER FOR 'Break Start Time2', 'Break End Time2'
      var input = $('.clockpicker_break_time2').clockpicker(
      {
        placement: 'bottom',
        align: 'left',
        autoclose: false,
        'default': 'now',
        twelvehour : true,
        donetext:'Done',
        afterDone: function() 
        {
          calculate_training_time();
          calculate_total_break_time();
          
          validate_input('break_start_time2');
          validate_input('break_end_time2');
          
          if($.trim($("#break_start_time3").val()) != "") { validate_input('break_start_time3'); }
          if($.trim($("#break_end_time3").val()) != "") { validate_input('break_end_time3'); }
        }
      });//END : CLOCKPICKER FOR 'Break Start Time2', 'Break End Time2'
      
      //START : CLOCKPICKER FOR 'Break Start Time3', 'Break End Time3'
      var input = $('.clockpicker_break_time3').clockpicker(
      {
        placement: 'bottom',
        align: 'right',
        autoclose: false,
        'default': 'now',
        twelvehour : true,
        donetext:'Done',
        afterDone: function() 
        {
          calculate_training_time();
          calculate_total_break_time();
          
          validate_input('break_start_time3');
          validate_input('break_end_time3');
        }
      });//END : CLOCKPICKER FOR 'Break Start Time3', 'Break End Time3'
      
      //START : DATEPICKER FOR 'BATCH TRAINING FROM DATE'
      $('#batch_start_date').datepicker({ keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy-mm-dd", clearBtn: false, startDate:"<?php echo $chk_batch_start_date; ?>" , endDate:"<?php echo date('Y-m-d', strtotime("+90day", strtotime($chk_batch_start_date))); ?>" }).on('changeDate', function (selected) 
      { 
        if(typeof selected.date != "undefined")        
        {
          var selected_batch_type = $('input[name="batch_type"]:checked').val();
          var gap_days = 5;
          if(typeof selected_batch_type !== "undefined" && selected_batch_type == 1) { gap_days = 3; }

          var minDate = new Date(selected.date.valueOf());         
          minDate.setDate(minDate.getDate() + gap_days);          
          $('#batch_end_date').datepicker('setStartDate', minDate);

          var new_set_date = minDate.getFullYear() + '-' + ('0' + (minDate.getMonth() + 1)).slice(-2) + '-' + ('0' + minDate.getDate()).slice(-2)
          
          $('#batch_end_date').val(new_set_date).datepicker("update");
        }
        else
        {
          $('#batch_end_date').datepicker('setStartDate',"<?php echo $chk_batch_start_date; ?>");
        }
        
        set_holidays_range();
        calculate_training_days();
        validate_input('batch_end_date');
        
        if($.trim($.trim($("#first_faculty").val())).length > 0) {  validate_input('first_faculty'); }
        if($.trim($.trim($("#second_faculty").val())).length > 0) {  validate_input('second_faculty'); }
        if($.trim($.trim($("#third_faculty").val())).length > 0) {  validate_input('third_faculty'); }
        if($.trim($.trim($("#fourth_faculty").val())).length > 0) {  validate_input('fourth_faculty'); }
      }); //END : DATEPICKER FOR 'BATCH TRAINING FROM DATE' 
      
      //START : DATEPICKER FOR 'BATCH TRAINING To DATE'
      var selected_batch_type = $('input[name="batch_type"]:checked').val();
      var gap_days = 5;
      if(typeof selected_batch_type !== "undefined" && selected_batch_type == 1) { gap_days = 3; }
      
      var minDate = new Date("<?php echo $chk_batch_start_date;?>"); 
      <?php if($mode == 'Update')  
      { ?> 
        if($("#batch_start_date").val() != "" && typeof $("#batch_start_date").val() !== 'undefined')
        {
          var minDate = new Date($("#batch_start_date").val()); 
        }
      <?php } ?>
      minDate.setDate(minDate.getDate() + gap_days);
      
      $('#batch_end_date').datepicker({ keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy-mm-dd", clearBtn: false, startDate:minDate }).on('changeDate', function (selected) 
      {
        set_holidays_range();
        calculate_training_days();
        
        if($.trim($.trim($("#first_faculty").val())).length > 0) {  validate_input('first_faculty'); }
        if($.trim($.trim($("#second_faculty").val())).length > 0) {  validate_input('second_faculty'); }
        if($.trim($.trim($("#third_faculty").val())).length > 0) {  validate_input('third_faculty'); }
        if($.trim($.trim($("#fourth_faculty").val())).length > 0) {  validate_input('fourth_faculty'); }
      });//END : DATEPICKER FOR 'BATCH TRAINING To DATE'
      
      //START : DATEPICKER FOR 'HOLIDAYS'
      $('#batch_holidays').datepicker({ multidate: true, keyboardNavigation: true, forceParse: true, autoclose: false, format: "yyyy-mm-dd", clearBtn: true }).on('changeDate', function (selected) 
      { 
        sort_holidays_dates();
        calculate_training_days();
      }); //END : DATEPICKER FOR 'HOLIDAYS'
      
      <?php //START : THIS CODE IS USE FOR SERVER SIDE VALIDATION + EDIT FORM
        if($mode == 'Add')
        {
          $training_from_date = set_value('batch_start_date');
          $training_to_date = set_value('batch_end_date');
        }
        else if($mode == 'Update')
        {
          $training_from_date = $form_data[0]['batch_start_date'];
          $training_to_date = $form_data[0]['batch_end_date'];
        }
        
        if(isset($training_from_date) && $training_from_date != "")
        { ?>
        $('#batch_holidays').datepicker('setStartDate', '<?php echo date('Y-m-d', strtotime("+1day", strtotime($training_from_date))); ?>');
        <?php }
        
        if(isset($training_to_date) && $training_to_date != "")
        { ?>
        $('#batch_holidays').datepicker('setEndDate', '<?php echo date('Y-m-d', strtotime("-1day", strtotime($training_to_date))); ?>');
        <?php } //END : THIS CODE IS USE FOR SERVER SIDE VALIDATION + EDIT FORM
      ?>
      
      //START : AS PER SELECTED BATCH FROM & TO DATE, SET HOLIDAY SELECTION RANGE      
      function set_holidays_range()
      {
        //CLEAR HOLIDAY SELECTED DATES
        //SET (START DATE + 1 DAY) FOR HOLIDAY START DATE
        //SET (END DATE - 1 DAY) FOR HOLIDAY END DATE
        
        var batch_start_date = new Date($('#batch_start_date').val());
        batch_start_date.setDate(batch_start_date.getDate() + 1);
        
        var batch_end_date = new Date($('#batch_end_date').val());
        batch_end_date.setDate(batch_end_date.getDate() - 1);
        
        $("#batch_holidays").val("");
        if(batch_start_date != "") { $('#batch_holidays').datepicker('setStartDate', batch_start_date); }
        if(batch_end_date != "") { $('#batch_holidays').datepicker('setEndDate', batch_end_date); }
      }//END : AS PER SELECTED BATCH FROM & TO DATE, SET HOLIDAY SELECTION RANGE
      
      function convertToSortableFormat(date) 
      {
        var parts = date.split("-");
        return parts[2] + "-" + parts[1] + "-" + parts[0];
      }
      
      function convertToDdMmYyyy(date) 
      {
        var parts = date.split("-");
        return parts[2] + "-" + parts[1] + "-" + parts[0];
      }
      
      function sort_holidays_dates()
      {
        //START : CODE TO DISPLAY THE SELECTED HOLIDAYS IN ASCENDING ORDER
        let selected_holidays_str = $("#batch_holidays").val();
        if (selected_holidays_str.trim() != '') 
        {

           var dateArray = selected_holidays_str.split(',');

          // Parse the date strings into JavaScript Date objects
          var dateObjects = dateArray.map(function(dateString) {
              return new Date(dateString);
          });

          // Sort the array of Date objects
          dateObjects.sort(function(a, b) {
              return a - b;
          });

          // Convert the sorted Date objects back to a string format
          var sortedDateString = dateObjects.map(function(dateObject) {
              return dateObject.toISOString().split('T')[0];
          }).join(',');

          // Output the result
          $("#batch_holidays").val(sortedDateString);
         
        } 
        else 
        {
          $("#batch_holidays").val('');
        }//END : CODE TO DISPLAY THE SELECTED HOLIDAYS IN ASCENDING ORDER
      }
      
      //START : CALCULATE THE 'GROSS TRAINING DAYS' & 'NET TRAINING DAYS'  
      function calculate_training_days()
      {
        let batch_start_date = new Date($("#batch_start_date").val());
        let batch_end_date = new Date($("#batch_end_date").val());
        let batch_holidays = $("#batch_holidays").val();
        
        let holiday_count = 0;
        if (batch_holidays != '') 
        {
          if (batch_holidays.includes(',') == true) 
          {
            batch_holidaysArr = batch_holidays.split(',');
            holiday_count = batch_holidaysArr.length;
          } 
          else 
          {
            holiday_count = 1;
          }
        }
        
        let diffTime = Math.abs(batch_end_date - batch_start_date);
        let days = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        days = parseInt(days) + parseInt(1);
        let batch_gross_days = days;
        let batch_net_days = parseInt(days) - parseInt(holiday_count);
        
        if ($.isNumeric(batch_gross_days)) { $("#batch_gross_days").val(batch_gross_days) } else { $("#batch_gross_days").val("") }
        if ($.isNumeric(batch_net_days)) { $("#batch_net_days").val(batch_net_days) } else { $("#batch_net_days").val("") }
        
        calculate_training_time();
      }//END : CALCULATE THE 'GROSS TRAINING DAYS' & 'NET TRAINING DAYS'
      
      //START : CALCULATE THE 'GROSS TRAINING TIME PER DAY', 'NET TRAINING TIME PER DAY' & 'TOTAL NET TRAINING TIME OF DURATION'  
      function calculate_training_time() 
      {
        let batch_daily_start_time = $("#batch_daily_start_time").val();
        let batch_daily_end_time = $("#batch_daily_end_time").val();        
        if(batch_daily_start_time != "" && batch_daily_end_time != "")
        {
          //START : CODE FOR CALCULATING THE 'GROSS TRAINING TIME PER DAY'
          let calculated_start_end_datetime = calculate_actual_start_end_date_time(batch_daily_start_time, batch_daily_end_time);
          
          let startDate = new Date(calculated_start_end_datetime.split('#####')[0]);
          let endDate = new Date(calculated_start_end_datetime.split('#####')[1]);          
          
          var timeDiff = Math.abs(endDate - startDate);
          var minutesDiff = Math.floor((timeDiff / 1000) / 60);
          var hoursDiff = Math.floor(minutesDiff / 60);
          
          var daily_gross_time_hour = show_double_digit(hoursDiff);
          var daily_gross_time_min = show_double_digit((minutesDiff % 60));          
          
          $('#batch_daily_gross_time').val(daily_gross_time_hour+":"+daily_gross_time_min);
          //END : CODE FOR CALCULATING THE 'GROSS TRAINING TIME PER DAY'
          
          //START : CODE FOR CALCULATING THE 'NET TRAINING TIME PER DAY'
          minutesDiff = parseInt(minutesDiff) - parseInt(calculate_total_break_time(1));
          
          var hoursDiff2 = Math.floor(minutesDiff / 60);
          
          var daily_net_time_hour = show_double_digit(hoursDiff2);
          var daily_net_time_min = show_double_digit((minutesDiff % 60));
          
          $('#batch_daily_net_time').val(daily_net_time_hour+":"+daily_net_time_min);
          //END : CODE FOR CALCULATING THE 'NET TRAINING TIME PER DAY'
          
          //START : CODE FOR CALCULATING THE 'TOTAL NET TRAINING TIME OF DURATION'
          batch_net_days = $("#batch_net_days").val();
          if(batch_net_days != "" && batch_net_days > 0 && $.isNumeric(batch_net_days))
          {
            batch_total_net_time_in_min = parseInt(batch_net_days) * minutesDiff;
            
            var hoursDiff3 = Math.floor(batch_total_net_time_in_min / 60);
            
            var total_net_time_hour = show_double_digit(hoursDiff3);
            var total_net_time_min = show_double_digit((batch_total_net_time_in_min % 60)); 
            $('#batch_total_net_time').val(total_net_time_hour+":"+total_net_time_min);
          }
          else
          {
            $("#batch_total_net_time").val("");
          }
          //END : CODE FOR CALCULATING THE 'TOTAL NET TRAINING TIME OF DURATION'
        }
        else 
        {
          $('#batch_daily_gross_time').val(""); 
          $('#batch_daily_net_time').val(""); 
        }
        //END : CODE FOR CALCULATING THE 'GROSS TRAINING TIME PER DAY' 
        
        validate_input('total_daily_break_time');
        validate_input('batch_daily_net_time');
        validate_input('batch_total_net_time');
      }//END : CALCULATE THE 'GROSS TRAINING TIME PER DAY', 'NET TRAINING TIME PER DAY' & 'TOTAL NET TRAINING TIME OF DURATION' 
      
      //START : CONVERT HOUR INTO MINUTES (for 12 hours)
      function convert_hour_into_min(inputTime)
      {
        if(inputTime != "")
        {
          inputTime = inputTime.replace('AM', ' AM');
          inputTime = inputTime.replace('PM', ' PM');
          
          var timeArray = inputTime.split(':');
          var hours = parseInt(timeArray[0], 10);
          var minutes = parseInt(timeArray[1].split(' ')[0], 10);
          var ampm = timeArray[1].split(' ')[1];
          
          if (ampm === 'PM' && hours !== 12) {
            hours += 12;
            } else if (ampm === 'AM' && hours === 12) {
            hours = 0;
          }
          
          var totalMinutes = hours * 60 + minutes;
          return totalMinutes;
        }
      }//END : CONVERT HOUR INTO MINUTES (for 12 hours)
      
      //START : CONVERT HOUR INTO MINUTES (for 24 hours)
      function convert_hour_into_min_24_hr_format(inputTime)
      {
        if(inputTime != "")
        {
          var timeArray = inputTime.split(':');
          var hours = parseInt(timeArray[0]);
          var minutes = parseInt(timeArray[1]);         
          
          var totalMinutes = hours * 60 + minutes;
          return totalMinutes;
        }
      }//END : CONVERT HOUR INTO MINUTES (for 24 hours)
      
      <?php /*
        //START : GEBNERATE BREAK TIME DROPDOWN
        function generate_break_time_dropdown(interval)
        {
        $('#batch_daily_break_time1').html('');
        
        let start_time = $("#batch_daily_start_time").val();
        let end_time = $("#batch_daily_end_time").val();  
        
        if(start_time == "") { start_time = "07:30AM"; }
        if(end_time == "") { end_time = "08:30PM"; }        
        
        start_time = convert_hour_into_min(start_time);
        end_time = convert_hour_into_min(end_time);
        
        console.log(start_time)
        console.log(end_time)
        
        var startTime = start_time; //10 * 60; // Starting time in minutes (10:00 AM)
        var endTime = end_time; //18 * 60;   // Ending time in minutes (6:00 PM)      
        
        // Populate the dropdown with time values
        for (var i = startTime; i <= endTime; i += interval) 
        {
        var hours = Math.floor(i / 60);
        var minutes = i % 60;
        var ampm = hours < 12 ? 'AM' : 'PM';
        
        // Convert to 12-hour format
        hours = hours % 12 || 12;
        
        // Format minutes to always display two digits
        var formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
        
        let select_option_value = hours + ':' + formattedMinutes + ' ' + ampm;
        if(i == startTime)
        {
        $('#batch_daily_break_time1').append($('<option>', 
        {            
        value: '',
        text: 'Select Break Time1'
        }));
        }
        
        $('#batch_daily_break_time1').append($('<option>', 
        {            
        value: select_option_value,
        text: select_option_value
        }));
        }
        
        //$("#batch_daily_break_time11")
        }//END : GEBNERATE BREAK TIME DROPDOWN
      generate_break_time_dropdown(5); */ ?>
      
      //START : DISPLAY THE 0 TO 9 HOURS AS 09 & 0 TO 9 MINS AS 09 
      function show_double_digit(val)
      {
        if(val < 10) { val = '0'+val; }
        return val;
      }//END : DISPLAY THE 0 TO 9 HOURS AS 09 & 0 TO 9 MINS AS 09 
      
      function calculate_actual_start_end_date_time(start_time, end_time)
      {
        start_time = start_time.replace('AM', ' AM');
        start_time = start_time.replace('PM', ' PM');
        
        end_time = end_time.replace('AM', ' AM');
        end_time = end_time.replace('PM', ' PM'); 
        
        var start = convertTo24HourFormat(start_time);
        var end = convertTo24HourFormat(end_time);       
        
        var start_compare = start.replace(':', '');
        var end_compare = end.replace(':', '');
        
        var startDate = new Date('2023-01-01 ' + start);
        if(end_compare >= start_compare)
        {
          var endDate = new Date('2023-01-01 ' + end);
        }
        else
        {
          var endDate = new Date('2023-01-02 ' + end);
        }
        
        return startDate+"#####"+endDate;
      }
      
      //START : CONVERT 12 HOUR TIME INTO 24 HOURS TIME FORMAT
      function convertTo24HourFormat(time) 
      {
        var hours = parseInt(time.split(':')[0]);
        var minutes = time.split(':')[1].split(' ')[0];
        var period = time.split(' ')[1];
        
        if (period === 'PM' && hours < 12) { hours += 12; }
        if (period === 'AM' && hours === 12) { hours = 0; }
        
        return ('0' + hours).slice(-2) + ':' + minutes;
      }//END : CONVERT 12 HOUR TIME INTO 24 HOURS TIME FORMAT
      
      //START : CALCULATE TOTAL BREAK TIME
      function calculate_total_break_time(is_return)
      {
        let total_daily_break_time = 0;
        
        for(let i=1; i<=3; i++)
        {
          let break_start_time = $("#break_start_time"+i).val();
          let break_end_time = $("#break_end_time"+i).val();
          
          if(break_start_time != "" && break_end_time != "") 
          {
            break_start_time = convert_hour_into_min(break_start_time);
            break_end_time = convert_hour_into_min(break_end_time);
            
            if(break_end_time > break_start_time)
            {
              let break_time_in_min = parseInt(break_end_time) - parseInt(break_start_time);
              total_daily_break_time = parseInt(total_daily_break_time) + parseInt(break_time_in_min);           
            }
          }
        } 
        
        if($.isNumeric(total_daily_break_time))
        {
          if(is_return == 1) { return total_daily_break_time; }
          else { $("#total_daily_break_time").val(total_daily_break_time); }
        }
        else
        {
          if(is_return == 1) { return 0; }
          else { $("#total_daily_break_time").val(""); }
        }
        
        validate_input('total_daily_break_time');        
        
        /* calculate_training_time(); */
      }//END : CALCULATE TOTAL BREAK TIME
      
      //START : SET VALUES & MESSAGES AS PER BATCH TYPE
      function batch_type_change(is_validate='')
      {
        var gap_days = 5;

        var selectedValue = $('input[name="batch_type"]:checked').val();
        if(selectedValue == '1') 
        {
          var no_of_hrs = '<?php echo $no_of_hours_basic; ?>';
          var disp_days = '<?php echo $chk_gross_training_days_basic; ?>';
          var net_time = '<?php echo $chk_total_net_training_time_of_duration_basic; ?>'; 
          var gap_days = 3;
        }
        else if(selectedValue == '2') 
        { 
          var no_of_hrs = '<?php echo $no_of_hours_advance; ?>';
          var disp_days = '<?php echo $chk_gross_training_days_advance; ?>';
          var net_time = '<?php echo $chk_total_net_training_time_of_duration_advance; ?>';
        }
        
        if(selectedValue == '1' || selectedValue == '2')
        { 
          $("#batch_hours").val(no_of_hrs);        
          $("#batch_gross_days_err").html('Note: Gross Days should be less than or equal to '+disp_days+'.'); 
          $("#batch_total_net_time_err").html('Note: Total Net Training Time of Duration should be greater than or equal to '+net_time+' Hours.');          
        }
        else
        {
          $("#batch_hours").val("");        
          $("#batch_gross_days_err").html(''); 
          $("#batch_total_net_time_err").html('');
        }
        
        if(is_validate == '')
        {
          var selected_from_date = $("#batch_start_date").val();
          if(typeof selected_from_date === "undefined" || selected_from_date == "")
          {
            var selected_from_date = "<?php echo $chk_batch_start_date; ?>";
          }
          
          var minDate = new Date(selected_from_date);         
          minDate.setDate(minDate.getDate() + gap_days);          
          $('#batch_end_date').datepicker('setStartDate', minDate);

          var new_set_date = minDate.getFullYear() + '-' + ('0' + (minDate.getMonth() + 1)).slice(-2) + '-' + ('0' + minDate.getDate()).slice(-2)
          if($('#batch_start_date').val() != "") { $('#batch_end_date').val(new_set_date).datepicker("update"); }


          if(selectedValue == '1' || selectedValue == '2') { validate_input('batch_hours'); }
          //xxxvalidate_input('batch_gross_days');
          validate_input('batch_total_net_time');
        }
      }//END : SET VALUES & MESSAGES AS PER BATCH TYPE
      batch_type_change('0');
      
      //START : CALCULATE TOTAL CANDIDATE VALUE
      function calculate_total_candidate(is_validate='')
      {
        let under_graduate_candidates = $("#under_graduate_candidates").val();
        let graduate_candidates = $("#graduate_candidates").val();
        let post_graduate_candidates = $("#post_graduate_candidates").val();
        let total_candidate = 0;
        
        if(under_graduate_candidates != "" && $.isNumeric(under_graduate_candidates)) { total_candidate = parseInt(total_candidate) + parseInt(under_graduate_candidates); }
        if(graduate_candidates != "" && $.isNumeric(graduate_candidates)) { total_candidate = parseInt(total_candidate) + parseInt(graduate_candidates); }
        if(post_graduate_candidates != "" && $.isNumeric(post_graduate_candidates)) { total_candidate = parseInt(total_candidate) + parseInt(post_graduate_candidates); }
        
        if($.isNumeric(total_candidate)) 
        { 
          $("#total_candidates").val(total_candidate);
          
          if(is_validate == '') { validate_input('total_candidates'); }
        }
        else { $("#total_candidates").val(""); }
      }//END : CALCULATE TOTAL CANDIDATE VALUE
      calculate_total_candidate('0');
      
      //START : ENABLE/DISABLE THE FACULTY DROPDOWN ON SELECTION
      function faculty_enable_disable() 
      {
        var first_faculty = $('#first_faculty').val();
        var second_faculty = $('#second_faculty').val();
        var third_faculty = $('#third_faculty').val();
        var fourth_faculty = $('#fourth_faculty').val();
        
        $('#first_faculty option').removeAttr("disabled");
        $('#second_faculty option').removeAttr("disabled");
        $('#third_faculty option').removeAttr("disabled");
        $('#fourth_faculty option').removeAttr("disabled");
        
        if (first_faculty != "") 
        {
          $('#second_faculty option[value=' + first_faculty + ']').attr("disabled", "disabled");
          $('#third_faculty option[value=' + first_faculty + ']').attr("disabled", "disabled");
          $('#fourth_faculty option[value=' + first_faculty + ']').attr("disabled", "disabled");
        }
        
        if (second_faculty != "") 
        {
          $('#first_faculty option[value=' + second_faculty + ']').attr("disabled", "disabled");
          $('#third_faculty option[value=' + second_faculty + ']').attr("disabled", "disabled");
          $('#fourth_faculty option[value=' + second_faculty + ']').attr("disabled", "disabled");
        }
        
        if (third_faculty != "") 
        {
          $('#first_faculty option[value=' + third_faculty + ']').attr("disabled", "disabled");
          $('#second_faculty option[value=' + third_faculty + ']').attr("disabled", "disabled");
          $('#fourth_faculty option[value=' + third_faculty + ']').attr("disabled", "disabled");
        }
        
        if (fourth_faculty != "") 
        {
          $('#first_faculty option[value=' + fourth_faculty + ']').attr("disabled", "disabled");
          $('#second_faculty option[value=' + fourth_faculty + ']').attr("disabled", "disabled");
          $('#third_faculty option[value=' + fourth_faculty + ']').attr("disabled", "disabled");
        }
      }//END : ENABLE/DISABLE THE FACULTY DROPDOWN ON SELECTION
      faculty_enable_disable();
      
      function batch_infrastructure_change()
      {
        var batch_online_offline_flag = $('input[name="batch_online_offline_flag"]:checked').val(); 
        if(batch_online_offline_flag == 1) { $("#online_batch_outer").hide(); }
        else if(batch_online_offline_flag == 2) { $("#online_batch_outer").show(); }
      }
      batch_infrastructure_change();

      //START : ADD / REMOVE DYNAMIC ROWS FOR 'Source of Candidates (Bank/Agency) & NUMBER OF CANDIDATES'
      let max_bank_row_limit = "";
      function append_bank_new_row(first_row)
      {
        var current_row_cnt_bank_name = $("#row_cnt_bank_name").val();        
        var new_row_cnt_bank_name = parseInt(current_row_cnt_bank_name) + 1; 
        
        var content = '';
        content += '<tr id="appended_row_bank'+new_row_cnt_bank_name+'" data-id="'+new_row_cnt_bank_name+'">';
        content += '  <td class="hide">';
        content += '    <input type="hidden" class="form-control custom_input" name="bank_field_id_arr[]" value="0">';        
        content += '  </td>';
        
        content += '  <td>';
        content += '    <input type="text" class="form-control custom_input allow_only_alphabets_and_space" name="bank_name_arr[]" id="bank_name_arr'+new_row_cnt_bank_name+'" value="" placeholder="Source of Candidates (Bank/Agency) *" required maxlength="30" />';
        content += '  </td>';

        content += '  <td>';
        content += '    <select class="form-control custom_input allow_only_numbers" name="cand_src_arr[]" id="cand_src_arr'+new_row_cnt_bank_name+'" required >';
        content += '      <option value="">Select</option>';
        
        for(var i=1; i<=35; i++)
        {
          content += '      <option value="'+i+'">'+i+'</option>';
        }

        content += '    </select>';
        content += '  </td>';        
        
        content += '  <td class="btn_outer no_wrap"></td>';
        content += '</tr>';        
        
        $("#append_div_bank").append(content);
        
        
        $('.custom_input').on('input', function () { inc_custom_input($(this)) });// Check for and remove the first space
        $('.custom_input').on('blur', function () { inc_custom_input_blur($(this)) }); // Trim leading and trailing spaces
        $('.allow_only_alphabets_and_space').on('keydown', function (e) { restrict_input(e,'allow_only_alphabets_and_space'); }); //Allow only alphabet + space
        $('.allow_only_numbers').on('keydown', function (e) { restrict_input(e,'allow_only_numbers'); });//Allow only numbers
        
        $("#row_cnt_bank_name").val(new_row_cnt_bank_name);        
        show_hide_bank_btns();
      }
      
      let current_total_row_cnt_bank_name = 0;
      let current_bank_mode = '<?php echo $mode; ?>';
      $("table > tbody#append_div_bank > tr").each(function () 
      {
        current_total_row_cnt_bank_name++;  
      });
      
      if(current_total_row_cnt_bank_name == 0 && (current_bank_mode == 'Add' || current_bank_mode == 'Update')) { append_bank_new_row(1); }
      else if(current_total_row_cnt_bank_name > 0 && (current_bank_mode == 'Add' || current_bank_mode == 'Update')) { show_hide_bank_btns(); }      
      
      function remove_bank_row(row_id)
      {
        swal({ 
          title: "Please confirm", 
          text: "Please confirm to delete selected row", 
          type: "warning", 
          showCancelButton: true, 
          confirmButtonColor: "#DD6B55", 
          confirmButtonText: "Yes, delete it!", 
          closeOnConfirm: true 
        }, 
        function () 
        { 
          $("#appended_row_bank"+row_id).remove();
          show_hide_bank_btns();
        });
      }
      
      function show_hide_bank_btns()
      {
        let total_row_cnt_bank_name = 0;
        let last_bank_row = 0;
        $("table > tbody#append_div_bank > tr").each(function () 
        {
          last_bank_row = $(this).closest('tr').attr("data-id");
          total_row_cnt_bank_name++;          
        });
        
        if(total_row_cnt_bank_name > 1) //remove all add button and show add button only for last row
        {
          $("table > tbody#append_div_bank > tr").each(function () 
          {
            let row_data_id = $(this).closest('tr').attr("data-id");
            $("#appended_row_bank"+row_data_id+" .add_row_btn").remove();      
          });
          
          append_bank_delete_btn(); //append delete button to each row if row count is more than 1
          
          if(max_bank_row_limit != "")//remove all add buttons if row reach to max limit
          {
            if(max_bank_row_limit != total_row_cnt_bank_name) { append_bank_add_btn_last(last_bank_row); }
          }    
          else
          {
            append_bank_add_btn_last(last_bank_row);
          }      
        }
        else if(total_row_cnt_bank_name == 1)
        {
          $("table > tbody#append_div_bank > tr").each(function () 
          {
            let row_data_id = $(this).closest('tr').attr("data-id");
            $("#appended_row_bank"+row_data_id+" .btn_outer .del_row_btn").remove(); 
            $("#appended_row_bank"+row_data_id+" .btn_outer .add_row_btn").remove();               
          });
          
          append_bank_add_btn_last(last_bank_row);
        }        
      }
      
      function append_bank_add_btn_last(last_bank_row) //Append add button to last row
      {
        $("#appended_row_bank"+last_bank_row+" .btn_outer").append('<button class="btn btn-primary add_row_btn" type="button" title="Add Row" onclick="append_bank_new_row()"><i class="fa fa-plus" aria-hidden="true"></i></button>');
      }
      
      function append_bank_delete_btn() //Append delete button to all row
      {
        $("table > tbody#append_div_bank > tr").each(function () 
        {
          let row_data_id = $(this).closest('tr').attr("data-id");
          $("#appended_row_bank"+row_data_id+" .btn_outer .del_row_btn").remove();
          $("#appended_row_bank"+row_data_id+" .btn_outer").append('<button class="btn btn-danger del_row_btn" type="button" title="Remove Row" onclick="remove_bank_row('+row_data_id+', 0)"><i class="fa fa-trash" aria-hidden="true"></i></button> ');      
        });        
      }
      //END : ADD / REMOVE DYNAMIC ROWS FOR 'Source of Candidates (Bank/Agency) & NUMBER OF CANDIDATES'
      
      //START : ADD / REMOVE DYNAMIC ROWS FOR ONLINE BATCH USER DETAILS (LOGIN ID & PASSWORD)
      let max_row_limit = "";
      function append_new_row(first_row)
      {
        var current_row_cnt = $("#row_cnt").val();        
        var new_row_cnt = parseInt(current_row_cnt) + 1; 
        
        var content = '';
        content += '<tr id="appended_row'+new_row_cnt+'" data-id="'+new_row_cnt+'">';
        content += '  <td class="hide">';
        content += '    <input type="hidden" class="form-control custom_input" name="field_id_arr[]" value="0">';        
        content += '  </td>';
        
        content += '  <td>';
        content += '    <input type="text" class="form-control custom_input" name="login_id_arr[]" id="login_id_arr'+new_row_cnt+'" value="" placeholder="Login Id" required maxlength="50" />';
        content += '  </td>';
        
        content += '  <td>';
        content += '    <div class="login_password_common">';
        content += '      <input type="password" class="form-control custom_input" name="password_arr[]" id="password_arr'+new_row_cnt+'" value="" placeholder="Password" required maxlength="50" />';
        
        var fun_show_pass = "show_hide_password(this,'show', 'password_arr"+new_row_cnt+"')";
        var fun_hide_pass = "show_hide_password(this,'hide', 'password_arr"+new_row_cnt+"')";
        
        content += '      <span class="show-password" onclick="'+fun_show_pass+'"><i class="fa fa-eye" aria-hidden="true"></i></span>';
        content += '      <span class="hide-password" onclick="'+fun_hide_pass+'" style="display:none;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>';
        content += '    </div>';
        content += '  </td>';
        
        content += '  <td class="btn_outer no_wrap"></td>';
        content += '</tr>';        
        
        $("#append_div").append(content);
        
        
        $('.custom_input').on('input', function () { inc_custom_input($(this)) });// Check for and remove the first space
        $('.custom_input').on('blur', function () { inc_custom_input_blur($(this)) }); // Trim leading and trailing spaces
        $('.allow_only_numbers').on('keydown', function (e) { restrict_input(e,'allow_only_numbers'); }); //Allow only numbers
        
        $("#row_cnt").val(new_row_cnt);        
        show_hide_btns();
      }
      
      let current_total_row_cnt = 0;
      let current_mode = '<?php echo $mode; ?>';
      $("table > tbody#append_div > tr").each(function () 
      {
        current_total_row_cnt++;  
      });
      
      if(current_total_row_cnt == 0 && (current_mode == 'Add' || current_mode == 'Update')) { append_new_row(1); }
      else if(current_total_row_cnt > 0 && (current_mode == 'Add' || current_mode == 'Update')) { show_hide_btns(); }      
      
      function remove_row(row_id)
      {
        swal({ 
          title: "Please confirm", 
          text: "Please confirm to delete selected row", 
          type: "warning", 
          showCancelButton: true, 
          confirmButtonColor: "#DD6B55", 
          confirmButtonText: "Yes, delete it!", 
          closeOnConfirm: true 
        }, 
        function () 
        { 
          $("#appended_row"+row_id).remove();
          show_hide_btns();
        });
      }
      
      function show_hide_btns()
      {
        let total_row_cnt = 0;
        let last_row = 0;
        $("table > tbody#append_div > tr").each(function () 
        {
          last_row = $(this).closest('tr').attr("data-id");
          total_row_cnt++;          
        });
        
        if(total_row_cnt > 1) //remove all add button and show add button only for last row
        {
          $("table > tbody#append_div > tr").each(function () 
          {
            let row_data_id = $(this).closest('tr').attr("data-id");
            $("#appended_row"+row_data_id+" .add_row_btn").remove();      
          });
          
          append_delete_btn(); //append delete button to each row if row count is more than 1
          
          if(max_row_limit != "")//remove all add buttons if row reach to max limit
          {
            if(max_row_limit != total_row_cnt) { append_add_btn_last(last_row); }
          }    
          else
          {
            append_add_btn_last(last_row);
          }      
        }
        else if(total_row_cnt == 1)
        {
          $("table > tbody#append_div > tr").each(function () 
          {
            let row_data_id = $(this).closest('tr').attr("data-id");
            $("#appended_row"+row_data_id+" .btn_outer .del_row_btn").remove(); 
            $("#appended_row"+row_data_id+" .btn_outer .add_row_btn").remove();               
          });
          
          append_add_btn_last(last_row);
        }        
      }
      
      function append_add_btn_last(last_row) //Append add button to last row
      {
        $("#appended_row"+last_row+" .btn_outer").append('<button class="btn btn-primary add_row_btn" type="button" title="Add Row" onclick="append_new_row()"><i class="fa fa-plus" aria-hidden="true"></i></button>');
      }
      
      function append_delete_btn() //Append delete button to all row
      {
        $("table > tbody#append_div > tr").each(function () 
        {
          let row_data_id = $(this).closest('tr').attr("data-id");
          $("#appended_row"+row_data_id+" .btn_outer .del_row_btn").remove();
          $("#appended_row"+row_data_id+" .btn_outer").append('<button class="btn btn-danger del_row_btn" type="button" title="Remove Row" onclick="remove_row('+row_data_id+', 0)"><i class="fa fa-trash" aria-hidden="true"></i></button> ');      
        });        
      }
      //END : ADD / REMOVE DYNAMIC ROWS FOR ONLINE BATCH USER DETAILS (LOGIN ID & PASSWORD)
      
      //START : SHOW / HIDE PASSWORD
      function show_hide_password(this_val,type,password_id)
      {
        var passwordId = password_id;
        if (type=="show") 
        {
          $("#" + passwordId).attr("type", "text");
          $(this_val).parent().find(".show-password").hide();
          $(this_val).parent().find(".hide-password").show();
        }
        else if (type=="hide") 
        {
          $("#" + passwordId).attr("type", "password");
          $(this_val).parent().find(".hide-password").hide();
          $(this_val).parent().find(".show-password").show();
        }
      }//HIDE : SHOW / HIDE PASSWORD

      function get_training_language_ajax(batch_type)
			{
				/* $("#page_loader").show();
				parameters="batch_type="+batch_type;
				
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('iibfbcbf/agency/training_batches_agency/get_training_language_ajax'); ?>",
					data: parameters,
					cache: false,
          dataType: 'JSON',
					success:function(data)
					{
            if(data.flag == "success")
						{
							$("#training_language_outer").html(data.response);
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
				}); */
			}
    </script>
    
    <script type="text/javascript">
      //START : JQUERY VALIDATION SCRIPT 
      function validation_for_break_time_between(break_time)
      {
        if($.trim(break_time).length == 0) { return true; }
        else
        {
          var break_time = $.trim(break_time);          
          var batch_daily_start_time = $.trim($("#batch_daily_start_time").val()); 
          var batch_daily_end_time = $.trim($("#batch_daily_end_time").val()); 
          
          var return_val = '1';
          if($.trim(break_time).length > 0 && $.trim(batch_daily_start_time).length > 0 && $.trim(batch_daily_end_time).length > 0)
          {
            var break_time_in_min = convert_hour_into_min(break_time);
            var batch_daily_start_time_in_min = convert_hour_into_min(batch_daily_start_time);
            var batch_daily_end_time_in_min = convert_hour_into_min(batch_daily_end_time);
            
            if(parseInt(break_time_in_min) < parseInt(batch_daily_start_time_in_min) || parseInt(break_time_in_min) > parseInt(batch_daily_end_time_in_min)) { return_val = '0'; }
          }
          
          if($.trim(break_time).length > 0 && $.trim(batch_daily_start_time).length > 0)
          {
            var break_time_in_min = convert_hour_into_min(break_time);
            var batch_daily_start_time_in_min = convert_hour_into_min(batch_daily_start_time);            
            
            if(parseInt(break_time_in_min) < parseInt(batch_daily_start_time_in_min)) { return_val = '0'; }
          }
          
          if($.trim(break_time).length > 0 && $.trim(batch_daily_end_time).length > 0)
          {
            var break_time_in_min = convert_hour_into_min(break_time);
            var batch_daily_end_time_in_min = convert_hour_into_min(batch_daily_end_time);
            
            if(parseInt(break_time_in_min) > parseInt(batch_daily_end_time_in_min)) { return_val = '0'; }
          }
          
          return return_val;
        }
      }
      
      function validation_for_break_end_time(start_time, end_time)
      {
        if($.trim(end_time).length == 0) { return true; }
        else
        {
          var break_start_time = $.trim(start_time); 
          var break_end_time = $.trim(end_time); 
          
          if($.trim(break_start_time).length > 0 && $.trim(break_end_time).length > 0)
          {
            var break_start_time_in_min = convert_hour_into_min(break_start_time);
            var break_end_time_in_min = convert_hour_into_min(break_end_time);
            
            if(parseInt(break_start_time_in_min) >= parseInt(break_end_time_in_min))
            {              
              return '0';
            }
            else { return '1'; }
          }
          else
          {
            return '1';
          }
        }
      }      
      
      function validate_input(input_id) { $("#"+input_id).valid(); }
      $(document ).ready( function() 
			{
        $.validator.addMethod("check_calculated_readonly_values", function(value, element)
        {
          if($.trim(value).length == 0) { return true; }
          else
          {
            var current_id = element.id;
            var current_val = $.trim(value);
            
            if(current_id == 'batch_start_date')
            {
              var batch_start_date = current_val;
              var batch_chk_date = "<?php echo $chk_batch_start_date; ?>";
              var batch_chk_date_end = "<?php echo date('Y-m-d', strtotime("+90day", strtotime($chk_batch_start_date))); ?>";
              
              if(batch_start_date < batch_chk_date || batch_start_date > batch_chk_date_end)
              {
                $.validator.messages.check_calculated_readonly_values = "Please Select the Date between "+batch_chk_date+" and "+batch_chk_date_end;
                return false;
              }
              else { return true; }
            }
            else if(current_id == 'batch_end_date')
            {
              var batch_end_date = current_val;

              var selected_batch_type = $('input[name="batch_type"]:checked').val();
              var batch_chk_date = "<?php echo date('Y-m-d', strtotime("+5day", strtotime($chk_batch_start_date))); ?>";
              if(selected_batch_type == 1) 
              { 
                batch_chk_date = "<?php echo date('Y-m-d', strtotime("+3day", strtotime($chk_batch_start_date))); ?>";                
              }
              
              if(batch_end_date < batch_chk_date)
              {
                $.validator.messages.check_calculated_readonly_values = "Please Select From Date greater than <?php echo date('Y-m-d', strtotime("+90day", strtotime($chk_batch_start_date))); ?>";
                return false;
              }
              else { return true; }
            }    
            else if(current_id == 'batch_gross_days')
            {
              var batch_gross_days = current_val;            
              var batch_type_value = $('input[name="batch_type"]:checked').val(); 
              var check_gross_days = '<?php echo $chk_gross_training_days_basic; ?>';
              
              if(batch_type_value == '1') { check_gross_days = '<?php echo $chk_gross_training_days_basic; ?>'; }
              else if(batch_type_value == '2') { check_gross_days = '<?php echo $chk_gross_training_days_advance; ?>'; }
              
              if(parseInt(batch_gross_days) > parseInt(check_gross_days))
              {
                $.validator.messages.check_calculated_readonly_values = "Gross Days should be less than or equal to "+check_gross_days;
                return false;
              }
              else { return true; }
            }    
            else if(current_id == 'batch_daily_gross_time')
            {
              var batch_daily_gross_time = current_val;            
              var batch_daily_gross_time_in_min = convert_hour_into_min(batch_daily_gross_time);
              
              if(parseInt(batch_daily_gross_time_in_min) > parseInt(<?php echo ($chk_gross_training_time_per_day*60); ?>))
              {
                $.validator.messages.check_calculated_readonly_values = "Gross Time should be less than or equal to <?php echo $chk_gross_training_time_per_day; ?> Hours";
                return false;
              }
              else { return true; }
            }    
            else if(current_id == 'total_daily_break_time')
            {         
              var chk_total_break_time = "<?php echo $chk_total_break_time; ?>";     
              if(parseInt(current_val) > parseInt(chk_total_break_time))
              {
                $.validator.messages.check_calculated_readonly_values = "Total Break Time should be less than or equal to "+chk_total_break_time+" minutes.";
                return false;
              }
              else { return true; }
            }
            else if(current_id == 'batch_daily_net_time')
            {         
              var chk_min_net_training_time_per_day = "<?php echo $chk_min_net_training_time_per_day; ?>";  
              var chk_net_training_time_per_day = "<?php echo $chk_net_training_time_per_day; ?>";  
              
              var current_val_in_min = convert_hour_into_min_24_hr_format(current_val);
              var chk_min_net_training_time_per_day_in_min = "<?php echo ($chk_min_net_training_time_per_day*60); ?>";
              var chk_net_training_time_per_day_in_min = "<?php echo ($chk_net_training_time_per_day*60); ?>";
              
              if(parseInt(current_val_in_min) > parseInt(chk_net_training_time_per_day_in_min))
              {
                $.validator.messages.check_calculated_readonly_values = "Net Time should be less than or equal to "+chk_net_training_time_per_day+" Hours.";
                return false;
              }
              else if(parseInt(current_val_in_min) < parseInt(chk_min_net_training_time_per_day_in_min))
              {
                $.validator.messages.check_calculated_readonly_values = "Net Time should be greater than or equal to "+chk_min_net_training_time_per_day+" Hours.";
                return false;
              }
              else { return true; }
            }
            else if(current_id == 'batch_total_net_time')
            { 
              var batch_type_value = $('input[name="batch_type"]:checked').val(); 
              var chk_total_net_training_time_of_duration = '0';
              
              if(batch_type_value == '1') { chk_total_net_training_time_of_duration = '<?php echo $chk_total_net_training_time_of_duration_basic; ?>'; }
              else if(batch_type_value == '2') { chk_total_net_training_time_of_duration = '<?php echo $chk_total_net_training_time_of_duration_advance; ?>'; }
              
              var current_val_in_min = convert_hour_into_min_24_hr_format(current_val);
              var chk_total_net_training_time_of_duration_in_min = parseInt(chk_total_net_training_time_of_duration)*parseInt(60);
              
              if(parseInt(chk_total_net_training_time_of_duration) > 0 && parseInt(current_val_in_min) < parseInt(chk_total_net_training_time_of_duration_in_min))
              {
                $.validator.messages.check_calculated_readonly_values = "Total Net Training Time of Duration should be greater than or equal to "+chk_total_net_training_time_of_duration+" Hours.";
                return false;
              }
              else { return true; }
            }
            else if(current_id == 'total_candidates')
            { 
              var chk_total_batch_candidates = "<?php echo $chk_total_batch_candidates; ?>";     
              if(parseInt(current_val) == 0)
              {
                $.validator.messages.check_calculated_readonly_values = "Total Candidates should be more than or equal to 1";
                return false;
              }
              else if(parseInt(current_val) > parseInt(chk_total_batch_candidates))
              {
                $.validator.messages.check_calculated_readonly_values = "Total Candidates should be less than or equal to "+chk_total_batch_candidates;
                return false;
              }
              else { return true; }
            }
          }
        });          
        
        //START : BREAK START & END TIME MUST BE IN BETWEEN 'DAILY TRAINING START TIME' & 'DAILY TRAINING END TIME'
        $.validator.addMethod("check_valid_break_time_between", function(value, element)
        {
          var current_id = element.id;
          
          var check_time_type = 'start';          
          if(current_id.indexOf('end') != -1) { check_time_type = 'end'; }
          
          var check_time_msg = '1';
          if(current_id.indexOf('2') != -1) { check_time_msg = '2'; }
          else if(current_id.indexOf('3') != -1) { check_time_msg = '3'; }
          
          var current_val = $.trim(value);
          var return_flag = validation_for_break_time_between(current_val);
          if(return_flag == '1') 
          { 
            //START : BREAK START & END TIME2 MUST BE GREATER THAN BREAK START & END TIME1. ALSO BREAK START & END TIME3 MUST BE GREATER THAN BREAK START & END TIME2
            var current_val_in_min = convert_hour_into_min(current_val);
            if(current_id.indexOf('2') != -1) 
            { 
              var break_end_time1 = $.trim($("#break_end_time1").val());
              
              if($.trim(break_end_time1).length > 0)
              {
                var break_end_time1_in_min = convert_hour_into_min(break_end_time1);
                if(current_val_in_min < break_end_time1_in_min)
                {
                  //$.validator.messages.check_valid_break_time_between = "Break "+check_time_type+" time2 must be greater than break end time1";
                  $.validator.messages.check_valid_break_time_between = "Break "+check_time_type+" time2 must be greater than <b>"+break_end_time1+"</b>";
                  return false;
                }                
              }
            }
            
            if(current_id.indexOf('3') != -1) 
            { 
              var break_end_time2 = $.trim($("#break_end_time2").val());
              var break_end_time1 = $.trim($("#break_end_time1").val());
              
              if($.trim(break_end_time2).length > 0)
              {
                var break_end_time2_in_min = convert_hour_into_min(break_end_time2);
                if(current_val_in_min < break_end_time2_in_min)
                {
                  //$.validator.messages.check_valid_break_time_between = "Break "+check_time_type+" time3 must be greater than break end time2";
                  $.validator.messages.check_valid_break_time_between = "Break "+check_time_type+" time3 must be greater than <b>"+break_end_time2+"</b>";
                  return false;
                }                
              }
              
              if($.trim(break_end_time1).length > 0)
              {
                var break_end_time1_in_min = convert_hour_into_min(break_end_time1);
                if(current_val_in_min < break_end_time1_in_min)
                {
                  //$.validator.messages.check_valid_break_time_between = "Break "+check_time_type+" time3 must be greater than break end time1";
                  $.validator.messages.check_valid_break_time_between = "Break "+check_time_type+" time3 must be greater than <b>"+break_end_time1+"</b>";
                  return false;
                }                
              }
            }
            
            return true;
            //END : BREAK START & END TIME2 MUST BE GREATER THAN BREAK START & END TIME1. ALSO BREAK START & END TIME3 MUST BE GREATER THAN BREAK START & END TIME2
          }
          else if(return_flag == '0')
          {
            //$.validator.messages.check_valid_break_time_between = "Break "+check_time_type+" time"+check_time_msg+" must be in between daily training start & end time";
            $.validator.messages.check_valid_break_time_between = "Break "+check_time_type+" time"+check_time_msg+" must be in between <b>"+$.trim($("#batch_daily_start_time").val())+"</b> & <b>"+$.trim($("#batch_daily_end_time").val())+"</b>";
            return false;
          }
        });//END : BREAK START & END TIME MUST BE IN BETWEEN 'DAILY TRAINING START TIME' & 'DAILY TRAINING END TIME'
        
        //START : BREAK END TIME MUST BE GREATER THAN BREAK START TIME
        $.validator.addMethod("check_valid_break_end_time", function(value, element)
        {
          var return_flag = validation_for_break_end_time($.trim($("#"+element.id.replace("end", "start")).val()), $.trim(value));
          if(return_flag == '1') { return true; }
          else if(return_flag == '0')
          {
            var err_msg = '1';
            if(element.id.indexOf('2') != -1) { err_msg = '2'; }
            else if(element.id.indexOf('3') != -1) { err_msg = '3'; }
            
            var err_msg_text = $.trim($("#break_start_time"+err_msg).val());
            
            //$.validator.messages.check_valid_break_end_time = "Break end time"+err_msg+" must be greater than break start time"+err_msg;
            $.validator.messages.check_valid_break_end_time = "Break end time"+err_msg+" must be greater than <b>"+err_msg_text+"</b>";
            return false;
          }
        });//END : BREAK END TIME MUST BE GREATER THAN BREAK START TIME
        
        $.validator.addMethod("validation_faculty_availability", function(value, element)
        {
          var faculty_id = $.trim(value);
          var batch_start_date = $.trim($("#batch_start_date").val());
          var batch_end_date = $.trim($("#batch_end_date").val());
          
          if($.trim(faculty_id).length == 0 || $.trim(batch_start_date).length == 0 || $.trim(batch_end_date).length == 0) { return true; }
          else
          {
            var isSuccess = false;
            var parameter = { "faculty_id":faculty_id, "batch_start_date":batch_start_date, "batch_end_date":batch_end_date }
            $.ajax(
            {
              type: "POST",
              url: "<?php echo site_url('iibfbcbf/agency/training_batches_agency/validation_faculty_availability/'.$enc_batch_id.'/1'); ?>",
              data: parameter,
              async: false,
              cache : false,
              dataType: 'JSON',
              success: function(data)
              {
                if($.trim(data.flag) == 'success')
                {
                  isSuccess = true;                  
                }
                
                $.validator.messages.validation_faculty_availability = data.response;
              }
            });
            
            return isSuccess;
          }
        });
        
				$("#add_training_batch_form").validate( 
				{
          onkeyup: function(element) { $(element).valid(); },          
          rules:
					{
            batch_type:{ required: true },  
            batch_hours:{ required: true, allow_only_numbers:true },
            batch_start_date:{ required: true, check_calculated_readonly_values:true },  
            batch_end_date:{ required: true, check_calculated_readonly_values:true },  
            batch_gross_days:{ allow_only_numbers:true, check_calculated_readonly_values:true }, 
            batch_holidays:{ },
            batch_net_days:{ },
            batch_daily_start_time:{ required: true },  
            batch_daily_end_time:{ required: true },  
            batch_daily_gross_time:{ check_calculated_readonly_values:true },  
            break_start_time1:{ required: true, check_valid_break_time_between:true },  
            break_end_time1:{ required: true, check_valid_break_end_time:true, check_valid_break_time_between:true },
            break_start_time2:{ required: true, check_valid_break_time_between:true },  
            break_end_time2:{ required: true, check_valid_break_end_time:true, check_valid_break_time_between:true },  
            break_start_time3:{ required: true, check_valid_break_time_between:true },  
            break_end_time3:{ required: true, check_valid_break_end_time:true, check_valid_break_time_between:true },   
            total_daily_break_time:{ allow_only_numbers:true, check_calculated_readonly_values:true },  
            batch_daily_net_time:{ check_calculated_readonly_values:true },  
            batch_total_net_time:{ check_calculated_readonly_values:true },  
            training_language:{ required:true },  
            under_graduate_candidates:{ required:true },  
            graduate_candidates:{ required:true },  
            post_graduate_candidates:{ required:true },  
            total_candidates:{ allow_only_numbers:true, check_calculated_readonly_values:true },  
            first_faculty:{ required:true, validation_faculty_availability:true },  
            second_faculty:{ required:true, validation_faculty_availability:true },  
            third_faculty:{ validation_faculty_availability:true },  
            fourth_faculty:{ validation_faculty_availability:true },
            training_schedule_file:{ <?php if($mode == 'Add' || ($mode == 'Update' && $form_data[0]['training_schedule_file'] == "")) { ?>required: true,<?php } ?> check_valid_file:true, valid_file_format:'.txt,.doc,.docx,.pdf', filesize_max:'5000000' }, //use size in bytes //filesize_max: 1MB : 1000000   
            contact_person_name:{ required: true, allow_only_alphabets_and_space:true,maxlength:90 },  
            contact_person_mobile:{ required: true, allow_only_numbers:true, first_zero_not_allowed:true, maxlength:10, minlength:10 },
            contact_person_email:{ required: true, maxlength:80, valid_email:true },
            alt_contact_person_name:{ allow_only_alphabets_and_space:true, maxlength:90 },  
            alt_contact_person_mobile:{ allow_only_numbers:true, first_zero_not_allowed:true, maxlength:10, minlength:10 },
            alt_contact_person_email:{ maxlength:80, valid_email:true },
            "bank_name_arr[]":{ required: true, maxlength:30, allow_only_alphabets_and_space:true },  
            "cand_src_arr[]":{ required: true, allow_only_numbers:true, first_zero_not_allowed:true },
            remarks:{ maxlength:1000 },  
            batch_online_offline_flag:{ required: true },  
            online_training_platform:{ required: true, allow_only_alphabets_and_space:true,maxlength:50 },  
            platform_link:{ required: true },
            "login_id_arr[]":{ required: true, maxlength:50 },  
            "password_arr[]":{ required: true, maxlength:50 },            
          },
					messages:
					{
            batch_type: { required: "Please select the batch type" },
            batch_hours: { required: "Please enter the no. of hours" },
            batch_start_date: { required: "Please select the batch training from date" },
            batch_end_date: { required: "Please select the batch training to date" },
            batch_gross_days: { },
            batch_holidays:{ },
            batch_net_days:{ },
            batch_daily_start_time: { required: "Please select the daily training start time" },
            batch_daily_end_time: { required: "Please select the daily training end time" },
            batch_daily_gross_time: { },
            break_start_time1: { required: "Please select the break start time1" },
            break_end_time1: { required: "Please select the break end time1" },
            break_start_time2: { required: "Please select the break start time2" },
            break_end_time2: { required: "Please select the break end time2" },
            break_start_time3: { required: "Please select the break start time3" },
            break_end_time3: { required: "Please select the break end time3" },
            total_daily_break_time: { },
            batch_daily_net_time: { },
            batch_total_net_time: { },
            training_language: { required: "Please select the training language" },
            under_graduate_candidates: { required: "Please select the under graduate candidates" },
            graduate_candidates: { required: "Please select the graduate candidates" },
            post_graduate_candidates: { required: "Please select the post graduate candidates" },
            total_candidates: {  },
            first_faculty:{ required: "Please select the faculty1" },  
            second_faculty:{ required: "Please select the faculty2" },  
            third_faculty:{ },  
            fourth_faculty:{ },
            training_schedule_file: { required: "Please select the training schedule", valid_file_format:"Please upload only .txt, .doc, .docx, .pdf files", filesize_max:"Please upload file less than 5MB" },
            contact_person_name: { required: "Please enter the batch coordinator name" },
            contact_person_mobile: { required: "Please enter the batch coordinator mobile number", minlength: "Please enter 10 numbers in batch coordinator mobile number", maxlength: "Please enter 10 numbers in batch coordinator mobile number" },
            contact_person_email: { required: "Please enter the batch coordinator email", valid_email: "Please enter the valid email id" },
            alt_contact_person_name: { },
            alt_contact_person_mobile: { minlength: "Please enter 10 numbers in alternative contact person mobile number", maxlength: "Please enter 10 numbers in alternative contact person mobile number" },
            alt_contact_person_email: { valid_email: "Please enter the valid email id" },
            "bank_name_arr[]": { required: "Please enter the source of candidates (Bank/Agency)" },
            "cand_src_arr[]": { required: "Please select the number of candidates" },
            remarks: { },
            batch_online_offline_flag: { required: "Please select the batch infrastructure" },
            online_training_platform: { required: "Please enter the name of the online training platform used" },
            platform_link: { required: "Please enter the link" },
            "login_id_arr[]": { required: "Please enter the login id" },
            "password_arr[]": { required: "Please enter the password" },
          }, 
          errorPlacement: function(error, element) // For replace error 
          {
            if (element.attr("name") == "batch_type") { error.insertAfter("#batch_type_err"); }
            else if (element.attr("name") == "batch_start_date") { error.insertAfter("#batch_start_date_err"); }
            else if (element.attr("name") == "batch_gross_days") { error.insertAfter("#batch_gross_days_err"); }
            else if (element.attr("name") == "batch_daily_gross_time") { error.insertAfter("#batch_daily_gross_time_err"); }
            else if (element.attr("name") == "total_daily_break_time") { error.insertAfter("#total_daily_break_time_err"); }
            else if (element.attr("name") == "batch_daily_net_time") { error.insertAfter("#batch_daily_net_time_err"); }
            else if (element.attr("name") == "batch_total_net_time") { error.insertAfter("#batch_total_net_time_err"); }
            else if (element.attr("name") == "training_schedule_file") { error.insertAfter("#training_schedule_file_err"); }
            else if (element.attr("name") == "contact_person_name") { error.insertAfter("#contact_person_name_err"); }
            else if (element.attr("name") == "contact_person_email") { error.insertAfter("#contact_person_email_err"); }
            else if (element.attr("name") == "alt_contact_person_name") { error.insertAfter("#alt_contact_person_name_err"); }
            else if (element.attr("name") == "alt_contact_person_email") { error.insertAfter("#alt_contact_person_email_err"); }
            else if (element.attr("name") == "remarks") { error.insertAfter("#remarks_err"); }
            else if (element.attr("name") == "batch_online_offline_flag") { error.insertAfter("#batch_online_offline_flag_err"); }
            else if (element.attr("name") == "online_training_platform") { error.insertAfter("#online_training_platform_err"); }
            else if (element.attr("name") == "platform_link") { error.insertAfter("#platform_link_err"); }      
            else { error.insertAfter(element); }
          },          
					submitHandler: function(form) 
					{
            swal({ title: "Please confirm", text: "Please confirm to submit the details", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
            { 
              $("#page_loader").show();
              $("#submit_btn_outer").html('<button class="btn btn-primary" type="button" style="cursor:wait" value="submit">Submit <i class="fa fa-spinner" aria-hidden="true"></i></button> <a class="btn btn-danger" href="<?php echo site_url("iibfbcbf/agency/training_batches_agency"); ?>">Back</a>');
              form.submit();
            });            
          }
        });
      });
      //END : JQUERY VALIDATION SCRIPT 

      function save_as_draft()
      {
        swal({ title: "Please confirm", text: "Please confirm to save the details as Draft", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
        { 
          $("#page_loader").show();
          var formData = new FormData($('#add_training_batch_form')[0]);
                    
          $.ajax(
          {
            type: "POST",
            url: '<?php echo site_url('iibfbcbf/agency/training_batches_agency/save_as_draft'); ?>',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'JSON',
            success:function(data)
            {
              if(data.flag == "success")
              {
                window.location.replace("<?php echo site_url('iibfbcbf/agency/training_batches_agency/add_training_batch_agency/"+data.response+"'); ?>");
              }
              else
              {
                alert(data.response);  
                //sweet_alert_error(data.response);  
                $('#page_loader').hide();          
              }
            },
            error: function(jqXHR, textStatus, errorThrown) 
            {
              console.log('AJAX request failed: ' + errorThrown);
              sweet_alert_error("Error occurred. Please try again.")
              $('#page_loader').hide();
            }
          });
        }); 
      }
    </script>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>