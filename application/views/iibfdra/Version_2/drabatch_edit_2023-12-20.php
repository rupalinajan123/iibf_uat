<!--custom style for datepicker dropdowns -->
<style>
	.example {
	width: 33%;
	min-width: 370px;
	/* padding: 15px;*/
	display: inline-block;
	box-sizing: border-box;/*text-align: center;*/
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
	.mandatory-field, .required-spn {
	color: #F00;
	}

.note { color: blue; font-size: 12px; line-height: 15px; display: inline-block; margin: 5px 0 0 0; }
.note-error { color: rgb(185, 74, 72); font-size: 12px; line-height: 15px; display: inline-block; margin: 5px 0 0 0; vertical-align:top; }
.parsley-errors-list > li { display: inline-block !important; font-size: 12px; line-height: 14px; margin: 2px 0 0 0 !important; padding: 0 !important; }
.datepicker table tr td.disabled, .datepicker table tr td.disabled:hover, .datepicker table tr td span.disabled, .datepicker table tr td span.disabled:hover { cursor: not-allowed; background: #eee; border: 1px solid #fff; }
#loading { display: none;	position: fixed; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; }
#loading > p { margin: 0 auto; width: 100%; height: 100%; position: absolute; top: 20%; }
#loading > p > img { max-height: 250px; margin:0 auto; display: block; }
.form-group ul.parsley-errors-list li::before { content: ""; }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url('assets/js/differenceHours.js'); ?>"></script>

<div id="loading" class="divLoading" style="display: none;">
  <p><img src="<?php echo base_url(); ?>assets/images/loading-4.gif"/></p>
</div>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<?php //if (isset($batchDetails)) {
		# code...
		//print_r($batchDetails); die;
		$batchs = $batchDetails[0];
		$bstatus = $batchs['batch_status'];
		$bid = $batchs['id'];
		/*foreach ($batchDetails as $batchs) { 
			$bstatus = $batchs['batch_status'];
			$bid = $batchs['id'];*/
	?>
	<section class="content-header">
		<h1> Training Batch Form</h1>
		<?php //echo $breadcrumb;?>
	</section>
		
	<form class="form-horizontal" autocomplete="off" name="draExamAddFrm" id="draExamAddFrm"  method="post" data-parsley-validate="parsley"  enctype="multipart/form-data" onsubmit ="return form_submit()">
		<section class="content">
			<div class="row">
          		<div class="col-md-12">
					<!-- Horizontal Form -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Edit Training Batch Details</h3>
							<div class="pull-right">
								<a href="<?php echo base_url().'iibfdra/Version_2/TrainingBatches/'?>" class="btn btn-warning"> Back </a>
							</div>
						</div>
						<!-- /.box-header --> 
						<!-- form start -->
						<div class="box-body">
							<?php if($this->session->flashdata('error')!=''){?>
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
							if(validation_errors()!=''){?>
								<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<?php echo validation_errors(); ?> 
								</div>
							<?php }

							if(count($error_msg)>0){
                                foreach ($error_msg as $key => $value) {?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <?php echo $value; ?>
                                    </div>
                               
                            <?php } }?> 
            
							
							<?php /*?>
							<div class="form-group">
								<label for="batch_type" class="col-sm-3 control-label">Batch Type <span class="mandatory-field">*</span></label>
								<div class="col-sm-3">
									<input <?php if($batchs['batch_type'] == 'Combined'){ echo 'checked'; } ?> type="radio" class="minimal" id="batch_type" name="batch_type"  required value="Combined" <?php echo set_radio('batch_type', 'Combined'); ?>>
									Combine Batch
									<input <?php if($batchs['batch_type'] == 'Separate'){ echo 'checked'; } ?> type="radio" class="minimal" id="batch_type" name="batch_type" required value="Separate" <?php echo set_radio('batch_type', 'Separate'); ?>>
									Separate Batch <span class="error">
										<?php //echo form_error('gender');?>
									</span> 
								</div>
							</div>
							<?php */?>
							<?php /*if($batchs['batch_type'] == 'Separate'){ ?> style="display: block;" <?php } else { ?> style="display: none;" <?php }*/ ?>
							<div class="form-group" id="type_div"  >
								<label for="batch_type" class="col-sm-3 control-label">Batch Type <span class="mandatory-field">*</span></label>
								<div class="col-sm-3">
									<input  type="radio" class="minimal" id="batch_type_50" name="hours" value="50" <?php if($batchs['hours'] == 50) { echo "checked"; } ?>>50 Hour
									<input  type="radio" class="minimal" name="hours" id="batch_type_100" value="100" <?php if($batchs['hours'] == 100) { echo "checked"; } ?>>100 Hour
									<span class="error"><?php //echo form_error('gender');?></span> 
								</div>
							</div>

							<div class="form-group">
								<input type="hidden" name="prev_status" id="prev_status" value="<?php echo $batchs['batch_status']; ?>">
							</div>

							<div class="form-group">
								<label for="center" class="col-sm-3 control-label">Center Name <span class="mandatory-field">*</span></label>
								<div class="col-sm-5">
									<?php if($batchs['city_name']!="")
										{
											$location = $batchs['city_name'];
										} 
										else{ 
											$location = $batchs['location_name'];
										}
									?>
									<select class="form-control netTime" id="center_id" name="center_id" required data-parsley-trigger="focusout">
									<option value="<?php echo $batchs['center_id']?>"><?php echo $location; ?></option>
									<?php $validity_to=$validity_from='';
									if(!empty($agency_center))
									{
										foreach($agency_center as $val)
										{
			                            	if($val['city_name']!="" || $val['city_name']!=NULL)
											{
												$val['location_name'] = $val['city_name'];
											} 
											else
											{ 
												$val['location_name'] = $val['location_name'];
											}
											if($val['renew_pay_status'] != "")  {//not empty
											if(($val['renew_type'] == 'free'  && $val['center_type'] == 'R') || ($val['renew_pay_status'] == 1 && $val['center_type'] == 'R')){ ?>
											<?php if($val['center_validity_from'] == '' && $val['center_validity_to'] == '' && $val['pay_status']==1){
													$_SESSION['validity_to']=$val['center_validity_to']; /*?>
													<option value="<?php echo $val['center_id']; ?>" disabled="disabled">  <?php   echo $val['location_name'].'( The accreditation period is not defined for this centre, please contact admin. )';?></option>
													<?php */}elseif($val['center_status'] == 'A' && $val['pay_status'] == 0){ /*?>
													<option value="<?php echo $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name']. '(The selected centre is approved by the admin, kindly make the payment for this centre in order to add the batch. )'; ?></option>
													<?php*/ }elseif($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d') && $val['center_validity_from'] <= date('Y-m-d'))
													{
														$validity_to=$val['center_validity_to'];
														$validity_from=(date('Y-m-d',strtotime("+6 day", strtotime($val['center_validity_from']))));	?>
														<option  <?php echo (set_value('center_id')==$val['center_id'])?" selected=' selected'":""?>   value="<?php echo $val['center_id']; ?>"><?php echo $val['location_name']; ?> </option>
														<?php }elseif( $val['center_validity_to']< date('Y-m-d')  && $val['pay_status'] == 1)
															{ /*?>
															<option value="<?php echo $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'].' ( The accreditation period for the selected centre is expired, please contact admin. )'; ?></option>
															<?php */ }elseif($val['center_status'] == 'R'){ /*?>
															<option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( The selected centre is rejected by the admin, please check the centre status in centre list or else contact admin. )'; ?></option>
															<?php*/ }elseif($val['center_status'] == 'IR'){ /*?>
															<option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( IN Review Process. )'; ?></option>
															<?php*/ }elseif($val['center_status'] == 'AR'){ /*?>
															<option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( Approve by Recommender. )'; ?></option>
															<?php*/ }elseif($val['center_status'] == 'A' && $val['pay_status'] == 2)
															{ /*?>
															<option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( Approve by Approver. )'; ?></option>
															<?php */ }elseif($val['center_validity_from'] > date('Y-m-d'))
															{ /*?>
															<option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( Your Accreditation period is not started. )'; ?></option>
															<?php */}
														} elseif(($val['renew_pay_status'] == 0 || $val['renew_pay_status'] == 2) && $val['center_type'] == 'R' && $val['renew_type'] != 'free'){ /* ?>
															<option value="<?php echo $val['center_id']; ?>" disabled="disabled">  <?php   echo $val['location_name'].'( Your renewal process payment is pending. )';?><?php echo set_value('location_name'); ?></option>
															<?php*/ }elseif(($val['renew_pay_status'] == 0 || $val['renew_pay_status'] == 2) && $val['center_type'] == 'T'){ /* ?>
															<option value="<?php echo $val['center_id']; ?>" disabled="disabled">  <?php   echo $val['location_name'].'( Your renewal process payment is pending. )';?></option>
															<?php */ }elseif($val['renew_pay_status'] == 1 && $val['center_type'] == 'T'){ 
															if($val['center_validity_from'] == '' && $val['center_validity_to'] == '' && $val['pay_status']==1){
															$_SESSION['validity_to']=$val['center_validity_to']; /*?>
															<option value="<?php echo $val['center_id']; ?>" disabled="disabled">  <?php   echo $val['location_name'].'( The accreditation period is not defined for this centre, please contact admin. )';?></option>
															<?php */}elseif($val['center_status'] == 'A' && $val['pay_status'] == 0){ /*?>
															<option value="<?php echo $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name']. '(The selected centre is approved by the admin, kindly make the payment for this centre in order to add the batch. )'; ?></option>
															<?php */}elseif($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d') && $val['center_validity_from'] <= date('Y-m-d'))
															{
															$validity_to=$val['center_validity_to'];
															$validity_from=(date('Y-m-d',strtotime("+6 day", strtotime($val['center_validity_from']))));?>
															<option  <?php echo (set_value('center_id')==$val['center_id'])?" selected=' selected'":""?>   value="<?php echo $val['center_id']; ?>"><?php echo $val['location_name']; ?> </option>
														<?php }elseif( $val['center_validity_to']< date('Y-m-d')  && $val['pay_status'] == 1)
														{ /*?>
														<option value="<?php echo $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'].' ( The accreditation period for the selected centre is expired, please contact admin. )'; ?></option>
														<?php */ }elseif($val['center_status'] == 'R')
														{ /*?>
														<option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( The selected centre is rejected by the admin, please check the centre status in centre list or else contact admin. )'; ?></option>
														<?php*/ }elseif($val['center_status'] == 'IR')
														{ /*?>
														<option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( IN Review Process. )'; ?></option>
														<?php */}elseif($val['center_status'] == 'AR')
														{/*?>
														<option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( Approve by Recommender. )'; ?></option>
														<?php */ }elseif($val['center_status'] == 'A' && $val['pay_status'] == 2)
														{ /*?>
														<option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( Approve by Approver. )'; ?></option>
														<?php*/ }elseif($val['center_validity_from'] > date('Y-m-d'))
														{ /*?>
														<option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( Your Accreditation period is not started. )'; ?></option>
													<?php*/ } ?>
											<?php } 
											} //not empty renew payemnt status
										else{//empty renew payemnt status
																		
			                            if($val['center_validity_from'] == '' && $val['center_validity_to'] == '' && $val['pay_status']==1){
											$_SESSION['validity_to']=$val['center_validity_to']; /*?>
											<option value="<?php echo $val['center_id']; ?>" disabled="disabled">  <?php   echo $val['location_name'].'( The accreditation period is not defined for this centre, please contact admin. )';?></option>
												<?php */ }elseif($val['center_status'] == 'A' && $val['pay_status'] == 0){ /*?>
			                              <option value="<?php echo $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name']. '(The selected centre is approved by the admin, kindly make the payment for this centre in order to add the batch. )'; ?></option>
			                              <?php*/ }elseif($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d') && $val['center_validity_from'] <= date('Y-m-d'))
			                              {
											$validity_to=$val['center_validity_to'];
											$validity_from=(date('Y-m-d',strtotime("+6 day", strtotime($val['center_validity_from'])))); ?>
			                              	<option  <?php echo (set_value('center_id')==$val['center_id'])?" selected=' selected'":""?>   value="<?php echo $val['center_id']; ?>"><?php echo $val['location_name']; ?> </option>
			                              
			                              <?php }elseif( $val['center_validity_to']< date('Y-m-d')  && $val['pay_status'] == 1)
			                              { /*?>
			                              	<option value="<?php echo $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'].' ( The accreditation period for the selected centre is expired, please contact admin. )'; ?></option>
											<?php*/ }elseif($val['center_status'] == 'R')
											{ /*?>
												<option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( The selected centre is rejected by the admin, please check the centre status in centre list or else contact admin. )'; ?></option>
												<?php */ }elseif($val['center_status'] == 'IR'){ /*?>
												<option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( IN Review Process. )'; ?></option>
												<?php */ }elseif($val['center_status'] == 'AR')
													{ /*?>
												<option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( Approve by Recommender. )'; ?></option>
											<?php */}elseif($val['center_status'] == 'A' && $val['pay_status'] == 2){ /*?>
											<option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( Approve by Approver. )'; ?></option>
											<?php*/ }elseif($val['center_validity_from'] > date('Y-m-d')){ /*?>
											<option value="<?php echo $val['center_id']?>" disabled="disabled"><?php echo $val['location_name'].' ( Your Accreditation period is not started. )'; ?></option>
										<?php*/ }?>
										<?php } }
									}?>
									</select>
									<div  id="validdate" style="display:none;" ><?php echo $validity_to?>
									</div>
									<input type="hidden" autocomplete="false" value="<?php echo $validity_to; ?>" id='validity_to' name='validity_to'/>
									<input type="hidden" autocomplete="false" value="<?php echo $validity_from; ?>" id='validity_from' name='validity_from'/>
								</div>
							</div>
							<?php /*?><div class="form-group">
								<label for="batch_name" class="col-sm-3 control-label">
									Batch Name <span class="mandatory-field">*</span>
								</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="batch_name" name="batch_name" placeholder="Batch Name" required  value="<?php echo $batchs['batch_name']?>" data-parsley-maxlength="30" autocomplete="off" maxlength="30" >
									<span class="error">
										<?php //echo form_error('middlename');?>
									</span>
								</div>
								<!-- (Max 30 Characters)  -->
							</div><?php */?>
							<div class="form-group">
								<label for="training_period" class="col-sm-3 control-label">
									Batch Training Period <span class="mandatory-field">*</span>
								</label>
								<div class="col-sm-3">
									
									From
									
									<input type="text" class="form-control period netTime" id="batch_from"  name="batch_from_date" placeholder="Training From Date" required value="<?php echo $batchs['batch_from_date']?>" autocomplete="off" />
									<span class="note">Note: Please Select From Date greater than <?php echo date('Y-m-d', strtotime("-1day", strtotime($date_check))); ?></span></br>
									<span class="note-error" id="batch_from_error"></span>
								</div>
								<div class="col-sm-3">
									To
									<input type="text" class="form-control period netTime" id="batch_to"  name="batch_to_date" placeholder="Training To Date" required value="<?php echo $batchs['batch_to_date']?>" autocomplete="off" onChange="chk_days()" />
									<span class="error">
										<?php //echo form_error('training_to');?>
									</span> 
								</div>
								<div class="col-sm-3">
			                        Gross Training Days
			                        <input type="text" id="gross_days" name="gross_days" class="form-control" value="<?php echo $batchs['gross_days']; ?>" readonly="readonly" autocomplete="off">
			                        <span class="note" id="gross_days_note">Note: Gross Training Days should be less than or equal to 30.</span>
                                    </br>
			                        <span class="note-error" id="gross_days_error"></span>
			                    </div>
			                </div>

							<div class="form-group">
		                        <label for="holidays" class="col-sm-3 control-label">Holiday(s)</label>
		                        <div class="col-sm-6">
		                        	Select Holiday(s)
		                            <input type="text" class="form-control netTime" id="holidays" name="holidays" placeholder="Select for Holidays" value="<?php echo $batchs['holidays']?>" onChange="chk_net_days()" autocomplete="off" data-parsley-trigger="focusin focusout"/>
		                            <span class="note-error" class="holidays_error"></span>
		                        </div> 

		                        <div class="col-sm-3">
			                        Net Training Days
			                        <input type="text" id="net_days" name="net_days" class="form-control" value="<?php echo $batchs['net_days']; ?>" readonly="readonly" autocomplete="off">
			                    </div>
		                    </div>

							<div class="form-group datepairtimes">
								<label for="timing_of_training" class="col-sm-3 control-label">
									Daily Training Timing <span class="mandatory-field">*</span>
								</label>
								<div class="col-sm-2">
									From                                         
									<input type="text" class="form-control time start timing_from netTime time_8" id="time1"  name="timing_from" required value="<?php echo $batchs['timing_from']; ?>" autocomplete="off" maxlength="8" onChange="calc_net_time();" />
								</div>
								<div class="col-sm-2">  To
									<input type="text" class="form-control time end timing_to netTime time_8 time_7"  id="time2"  name="timing_to"  required value="<?php echo $batchs['timing_to']; ?>" autocomplete="off" maxlength="8" onChange="calc_net_time();" />
								</div>
								<div class="col-sm-3">
                  Gross Training Time Per Day
                  <input type="text" id="gross_time" class="form-control netTime" name="gross_time" value="<?php echo $batchs['gross_time']?>" readonly="readonly" autocomplete="off" onchange="net_time_validate()">
                  <span class="note">Note : Gross Time should be less than or equal to 8 Hours.</span>
                      </br>
                  <span class="note-error" id="gross_time_error"></span>
                </div>
							</div>

							<div class="form-group">
		                        <label for="holidays" class="col-sm-3 control-label">Daily Break Times <span class="mandatory-field">*</span></label>
		                        <?php //echo $batchs['brk_time1']; ?>
		                        <div class="col-sm-2">
		                            Break Time 1
		                            <select class="form-control netTime breakTime time_7" id="brk_time1" name="brk_time1" required data-parsley-trigger="focusout">
		                            <option value="">Select Break Time</option>
		                            <?php foreach($timeArr as $key => $value){ ?>
		                                <option value="<?php echo $value; ?>" <?php if(!empty($batchs['brk_time1']) && $batchs['brk_time1'] == $value){ echo 'selected="selected"'; } ?>><?php echo $value; ?></option>
		                            <?php }?>
		                            </select>
		                        </div>
		                        <div class="col-sm-2">
		                            Break Time 2
		                            <select class="form-control netTime breakTime time_7" id="brk_time2"  name="brk_time2" required data-parsley-trigger="focusout">
		                            <option value="">Select Break Time</option>
		                            <?php foreach($timeArr as $key => $value){ ?>
		                                <option value="<?php echo $value; ?>" <?php if(!empty($batchs['brk_time2']) && $batchs['brk_time2'] == $value){ echo 'selected="selected"'; } ?>><?php echo $value; ?></option>
		                            <?php }?>
		                            </select>
		                        </div>
		                        <div class="col-sm-2">
		                            Break Time 3
		                            <select class="form-control netTime breakTime time_7" id="brk_time3"  name="brk_time3" required data-parsley-trigger="focusout">
		                            <option value="">Select Break Time</option>
		                            <?php foreach($timeArr as $key => $value){ ?>
		                                <option value="<?php echo $value; ?>" <?php if(!empty($batchs['brk_time3']) && $batchs['brk_time3'] == $value){ echo 'selected="selected"'; } ?>><?php echo $value; ?></option>
		                            <?php }?>
		                            </select>
		                        <span class="break_time_error"></span>
		                        </div> 
		                        <div class="col-sm-3">
		                            Total Break Time 
		                            <input type="text" class="form-control" name="total_brk_time" id="total_brk_time" placeholder="Total Break Time" value="<?php echo $batchs['total_break_time']; ?>" readonly="readonly" autocomplete="off">
		                            <span class="note">Note : Total Break Time should be less than or equal to 90 minutes.</span>
                                    </br>
		                            <span class="note-error" id="total_break_time_error"></span>
		                        </div>                       
		                    </div>

		                    <div class="form-group">
		                        <label for="net timings" class="col-sm-3 control-label"></label>
		                        <div class="col-sm-3">
		                            Net Training Time Per Day
		                            <input type="text" class="form-control net_time_cls" name="net_time" id="net_time" placeholder="Net Training Time Per Day" value="<?php echo $batchs['net_time']; ?>" readonly="readonly" autocomplete="off">
		                            <span class="note">Note : Net Time should be less than or equal to 7 Hours.</span>
                                    </br>
		                            <span class="note-error" id="net_time_error"></span>
		                        </div> 

		                        <div class="col-sm-3">
		                            Total Net Training Time of Duration
		                            <input type="text" class="form-control" name="total_net_time" id="total_net_time" placeholder="Total Net Training Time of Duration" value="<?php echo $batchs['total_net_time']; ?>" readonly="readonly">
		                            <span class="note" id="total_net_time_note">Note: Total Net Training Time of Duration should be greater than equal to 100</span>
                                    </br>
		                            <span class="note-error" id="total_net_time_error"></span>
		                        </div> 
		                        <span class="note-error" id="time_error"></span>
	                    	</div>

							<div class="form-group">
								<label for="training_medium" class="col-sm-3 control-label">
									Training Language <span class="mandatory-field">*</span>
								</label>
								<div class="col-sm-5">
									<select class="form-control" id="training_medium" name="training_medium" data-parsley-trigger="focusout">
										<option value="<?php echo $batchs['training_medium']?>">
											<?php echo $batchs['training_medium']?>
										</option>
										<?php if(count($medium_master) > 0){
											foreach($medium_master as $medium){  ?>
											<option value="<?php echo $medium['medium_description'];?>" <?php echo  set_select('exam_medium', $medium['medium_description']); ?>><?php echo $medium['medium_description'];?></option>
										<?php } } ?>
									</select>
									
								</div>
							</div>

							<div class="form-group">
		                        <label for="total_candidates" class="col-sm-3 control-label">No. of Candidates <span class="mandatory-field">*</span></label>
		                        <div class="col-sm-2" id="tenth_pass_div">
		                            10th pass
		                            <select class="form-control" id="tenth_pass_candidates" name="tenth_pass_candidates" onchange="calc_candidates()" data-parsley-trigger="focusout">
		                            <option value="">Select Candidate</option>
		                            <?php for($i=1;$i<=35;$i++){ ?>
		                                <option value="<?php echo $i; ?>" <?php if(!empty($batchs['tenth_pass_candidates']) && $batchs['tenth_pass_candidates'] == $i){ echo 'selected="selected"'; } ?>><?php echo $i; ?></option>
		                            <?php }?>
		                            </select>
		                            <span class="tenth_pass_candidates_error"></span>
		                        </div> 
		                        <div class="col-sm-2" id="twelth_pass_div">
		                            12th pass
		                            <select class="form-control" id="twelth_pass_candidates" name="twelth_pass_candidates" onchange="calc_candidates()" data-parsley-trigger="focusout">
		                            <option value="">Select Candidate</option>
		                            <?php for($i=1;$i<=35;$i++){ ?>
		                                <option value="<?php echo $i; ?>" <?php if(!empty($batchs['twelth_pass_candidates']) && $batchs['twelth_pass_candidates'] == $i){ echo 'selected="selected"'; } ?>><?php echo $i; ?></option>
		                            <?php }?>
		                            </select>
		                            <span class="twelth_pass_candidates_error"></span>
		                        </div> 
		                        <div class="col-sm-2">
		                            Graduates
		                            <select class="form-control" id="graduate_candidates"  name="graduate_candidates" onchange="calc_candidates()" data-parsley-trigger="focusout">
		                            <option value="">Select Candidate</option>
		                            <?php for($i=1;$i<=35;$i++){ ?>
		                                <option value="<?php echo $i; ?>" <?php if(!empty($batchs['graduate_candidates']) && $batchs['graduate_candidates'] == $i){ echo 'selected="selected"'; } ?>><?php echo $i; ?></option>
		                            <?php }?>
		                            </select>
		                        <span class="graduate_error"></span>
		                        </div> 
		                        <div class="col-sm-2">
		                            Total Candidates <span class="mandatory-field">*</span>
		                          <input type="text" class="form-control" required id="total_candidates" name="total_candidates" placeholder="Total Candidates" value="<?php echo $batchs['total_candidates'];?>" readonly="readonly" autocomplete="off">
		                          <span class="note-error" id="total_candidates_error"></span>
		                        </div>
		                    </div>

		                    <?php /*?>
		                    <div class="form-group">
		                        <label for="participate_yes_no" class="col-sm-7 control-label">Whether Graduate Candidates will participate in the entire training duration of 100 Hours</label>
		                        <div class="col-sm-3">
		                            <input type="radio" class="radiocls" id="participate_yes_no" name="participate_yes_no" value="Yes" <?php if(!empty($batchs['participate_yes_no']) && $batchs['participate_yes_no'] == 'Yes'){ echo 'checked="checked"'; } ?>>Yes
		                            <input type="radio" class="radiocls" id="participate_yes_no" name="participate_yes_no" required value="No" <?php if(!empty($batchs['participate_yes_no']) && $batchs['participate_yes_no'] == 'No'){ echo 'checked="checked"'; } ?>>No
		                            <span class="error"><?php //echo form_error('gender');?></span>
		                        </div>
		                    </div>
		                    <?php */?>
		                    <?php /*?>
		                    <div class="form-group" id="training_prd_full_capacity_div">
		                        <label for="training_prd_full_capacity" class="col-sm-3 control-label">Training Period with Full Capacity (Including Graduate Candidates)</label>
		                        <div class="col-sm-2">
		                            1st Half of the Period                     
		                            <input type="text" class="form-control" id="first_half" name="first_half" placeholder="1st 50 training hours" value="<?php echo $batchs['first_half'];?>">
		                        <span class="first_half_error"></span>
		                        </div> 
		                        <div class="col-sm-2">
		                            2nd Half of the Period
		                           <input type="text" class="form-control" id="sec_half" name="sec_half" placeholder="2nd 50 training hours" value="<?php echo $batchs['sec_half'];?>">
		                        <span class="sec_half_error"></span>
		                        </div> 
	                    	</div>
	                    	<?php */?>

		                    <div class="form-group">
		                        <label for="faculty_name" class="col-sm-3 control-label">Faculty Details <span class="mandatory-field">*</span></label>
		                        <div class="col-sm-4">
                              1st Faculty (For Basic Banking) <span class="mandatory-field">*</span>
		                            <select class="form-control" id="first_faculty" name="first_faculty" onchange="get_faculty()"  required="" data-parsley-trigger="focusout">
		                                <option value="0">Select Faculty</option>
		                                <?php foreach ($faculty_data as $key => $value) { ?>
		                                <option value="<?php echo $value['faculty_id']; ?>" <?php if(!empty($batchs['first_faculty']) && $batchs['first_faculty'] == $value['faculty_id']){ echo 'selected="selected"'; } ?>><?php echo $value['faculty_name']; ?></option>
		                                <?php }?>
		                            </select>
		                            <span class="first_faculty_error"><?php echo form_error('first_faculty');?></span>
		                        </div>

		                        <div class="col-sm-4">
		                            2nd Faculty (For Soft Skill in Banking) <span class="mandatory-field">*</span>
		                            <select class="form-control" id="sec_faculty" name="sec_faculty" last_val="" onchange="get_faculty()"  required="" data-parsley-trigger="focusout">
		                            <option value="">Select Faculty</option>
		                            <?php foreach ($faculty_data as $key => $value) { ?>
		                            <option value="<?php echo $value['faculty_id']; ?>" <?php if(!empty($batchs['sec_faculty']) && $batchs['sec_faculty'] == $value['faculty_id']){ echo 'selected="selected"'; } ?>><?php echo $value['faculty_name']; ?></option>
		                            <?php }?>
		                            </select>
		                          <span class="sec_faculty_error"><?php echo form_error('sec_faculty');?></span>
		                        </div>  
		                    </div>

		                    <div class="form-group">
		                        <label for="training_prd_full_capacity" class="col-sm-3 control-label"></label>
		                        <div class="col-sm-4">
		                            Additional Faculty I (For Basic Banking)
		                            <select class="form-control" id="additional_first_faculty" last_val="" name="additional_first_faculty" onchange="get_faculty()" data-parsley-trigger="focusout">
		                                <option value="">Select Faculty</option>
		                                <?php foreach ($faculty_data as $key => $value) { ?>
		                                <option value="<?php echo $value['faculty_id']; ?>" <?php if(!empty($batchs['additional_first_faculty']) && $batchs['additional_first_faculty'] == $value['faculty_id']){ echo 'selected="selected"'; } ?>><?php echo $value['faculty_name']; ?></option>
		                                <?php }?>
		                            </select>
		                            <span class="first_faculty_error"><?php //echo form_error('faculty_qualification');?></span>
		                        </div>

		                        <div class="col-sm-4">
		                            Additional Faculty II (For Soft Skill in Banking)
		                            <select class="form-control" id="additional_sec_faculty" last_val="" name="additional_sec_faculty" onchange="get_faculty()" data-parsley-trigger="focusout">
		                                <option value="">Select Faculty</option>
		                                <?php foreach ($faculty_data as $key => $value) { ?>
		                                <option value="<?php echo $value['faculty_id']; ?>" <?php if(!empty($batchs['additional_sec_faculty']) && $batchs['additional_sec_faculty'] == $value['faculty_id']){ echo 'selected="selected"'; } ?>><?php echo $value['faculty_name']; ?></option>
		                                <?php }?>
		                            </select>
		                          <span class="sec_faculty_error"><?php //echo form_error('faculty_qualification');?></span>
		                        </div>
		                    </div>

		                    <div class="form-group" id="training_schedule_doc">
			                    <label for="roleid" class="col-sm-3 control-label">Upload Training Schedule<span style="color:#F00">*</span></label>
			                    <div class="col-sm-5">
			                      <input type="file" class="form-control" name="training_schedule" id="training_schedule" onchange="validateDoc(event, 'training_schedule_error')" data-parsley-trigger="focusin focusout">
			                      <span class="note" id="training_schedule_note">Note: Please Upload only .txt, .doc, .docx, .pdf Files with size upto 5 MB</span></br>
			                      <span class="note-error" id="training_schedule_error"> <?php echo form_error('training_schedule'); ?></span>
			                    </div>
			                </div>

			                <?php if(!empty($batchs['training_schedule'])){?> 
			                    <div class="form-group" id="training_schedule_show">
			                      <label for="exampleInputName1" class="col-sm-3 control-label"><b>Training Schedule</b></label>
			                      <div class="col-sm-5">
			                        <a href="<?php echo base_url('uploads/training_schedule/'.$batchs['training_schedule']); ?>" target="_blank">View Document</a>
			                        <button type="button" value="Remove" id="btn_schedule_remove" class="btn-danger" onclick="removeImg('<?php echo $batchs['id']; ?>')">Remove Document</button>
			                        <input type="hidden" name="old_training_schedule" value="<?php echo $batchs['training_schedule']; ?>">
			                      </div>
			                    </div>
			              	<?php } ?>
								
							<div class="box-header with-border">
								<div class="col-sm-12"> 
									<h3 class="box-title" style="color:#333; width:100%; ">
										<center> &nbsp;<b>Venue of Training Batch</b> </center>
									</h3>
								</div>
								<br>
							</div>
							
							<div class="form-group" style="margin-top:8px;">
								<label for="state" class="col-sm-3 control-label">State <span class="mandatory-field">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="cstate" readonly placeholder="State"   name="cstate" value="<?php echo  $batchDetails[0]['state_name']; ?>" >
									<input type="hidden" autocomplete="off" class="form-control" id="ccstate" readonly placeholder="State"   name="state" value="<?php echo  $batchDetails[0]['state']; ?>" >
								</div>
							</div>

							<div class="form-group">
								<label for="district" class="col-sm-3 control-label">District <span class="mandatory-field">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" readonly id="cdistrict"  placeholder="District" value="<?php echo  $batchDetails[0]['district']; ?>" autocomplete="off">
									<span class="error">
										<?php //echo form_error('district');?>
									</span> 
								</div>
							</div>
						
							<div class="form-group">
								<label for="city" class="col-sm-3 control-label">City <span class="mandatory-field">*</span></label>
								<div class="col-sm-2">
									<input type="text" class="form-control" readonly placeholder="City" id="ccity" value="<?php echo $batchDetails[0]['city_name']; ?>">
									<input type="hidden" autocomplete="off" class="form-control" readonly placeholder="City" id="cccity" value="<?php echo $batchDetails[0]['city']; ?>">
									<span class="error">
										<?php //echo form_error('city');?>
									</span> 
								</div>
								<label for="pincode" class="col-sm-2 control-label">Pincode <span class="mandatory-field">*</span></label>
								<div class="col-sm-2">
									<input type="text" class="form-control" name="cpincode" id="cpincode" value="<?php echo $batchs['pincode']?>" data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-dracheckpin data-parsley-type="number" autocomplete="off"  onkeypress="return isNumber(event)" required readonly>
									<span class="error">
										<?php //echo form_error('pincode');?>
									</span> 
								</div>
							</div>

							
									
							<div class="form-group">
		                        <label for="address_line1" class="col-sm-3 control-label">Address</span> <span class="mandatory-field">*</span></label>
		                        <div class="col-sm-5">
		                            Line 1 <span class="mandatory-field">*</span>
		                          <input type="text" class="form-control address_fields" id="addressline1" name="addressline1" required placeholder="Address line 1" value="<?php echo $batchs['addressline1'];?>"  data-parsley-maxlength="30" autocomplete="off" maxlength="30" readonly autocomplete="off">
		                          <span class="error"><?php //echo form_error('addressline1');?></span>
		                        </div> 
		                        <div class="col-sm-4">
		                            Line 2
		                          <input type="text" class="form-control address_fields" id="addressline2" name="addressline2" placeholder="Address line 2" data-parsley-maxlength="30" value="<?php echo $batchs['addressline2'];?>"  data-parsley-maxlength="30" autocomplete="off" autocomplete="off" maxlength="30" readonly>
		                          <span class="error"><?php //echo form_error('addressline2');?></span>
		                        </div>
		                    </div>

		                    <div class="form-group">
		                        <label for="address_line3" class="col-sm-3 control-label address_fields"></label>
		                        <div class="col-sm-5">
		                          Line 3
		                          <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line 3" data-parsley-maxlength="30" value="<?php echo $batchs['addressline3'];?>"  autocomplete="off" maxlength="30" readonly>
		                          <span class="error"><?php //echo form_error('addressline2');?></span>
		                        </div>
		                        <div class="col-sm-4">
		                          Line 4
		                          <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line 4" data-parsley-maxlength="30" value="<?php echo $batchs['addressline4'];?>"   autocomplete="off" maxlength="30" readonly>
		                          <span class="error"><?php //echo form_error('addressline2');?></span>
		                        </div>
		                    </div>
									
							<div class="form-group">
		                        <label for="last_name" class="col-sm-3 control-label">Batch Coordinator Details <span class="mandatory-field">*</span></label>
		                        <div class="col-sm-5">
		                            Name <span class="mandatory-field">*</span>
		                            <input type="text" class="form-control" id="contact_person_name" name="contact_person_name" required placeholder="Contact Person Name" value="<?php echo $batchs['contact_person_name']; ?>" data-parsley-maxlength="30" data-parsley-trigger="focusin focusout" autocomplete="off" maxlength="30" onkeypress="return onlyAlphabets(event)">
		                            <span class="error"><?php //echo form_error('lastname');?></span>
		                        </div>
		                        <div class="col-sm-4">
		                            Mobile No. <span class="mandatory-field">*</span>
		                            <input type="tel" class="form-control" id="contact_person_phone" name="contact_person_phone" placeholder="Contact Person Mobile No." data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10" data-parsley-trigger="focusin focusout"  value="<?php echo $batchs['contact_person_phone'];?>"   required size="10" maxlength="10" onkeypress="return isNumber(event)"> 
		                            <span class="error"><?php //echo form_error('mobile');?></span>
		                        </div>
		                    </div> 

		                    <div class="form-group">
		                        <label for="last_name" class="col-sm-3 control-label">Alternative Contact Person Name and Contact Number </label>
		                        <div class="col-sm-5">
		                            Name
		                          <input type="text" class="form-control" id="alt_contact_person_name" name="alt_contact_person_name" placeholder="Contact Person Name" value="<?php echo $batchs['alt_contact_person_name'];?>" data-parsley-maxlength="30" data-parsley-trigger="focusin focusout" autocomplete="off" maxlength="30" onkeypress="return onlyAlphabets(event)">
		                          <span class="error"><?php //echo form_error('lastname');?></span>
		                        </div>
		                        <div class="col-sm-4">
		                            Mobile No.
		                            <input type="tel" class="form-control" id="alt_contact_person_phone" name="alt_contact_person_phone" placeholder="Alternative Contact Person Mobile No." data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10" data-parsley-trigger="focusin focusout"  value="<?php echo $batchs['alt_contact_person_phone'];?>" size="10" maxlength="10"  onkeypress="return isNumber(event)"> 
		                            <span class="error"><?php //echo form_error('mobile');?></span>
		                        </div>
		                    </div>

							<div class="form-group">
								<label for="name_of_bank " class="col-sm-3 control-label">Name of Bank / Agency / Mixed (Source of Candidates) <span class="mandatory-field">*</span></label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="name_of_bank" name="name_of_bank" placeholder="Name Of Bank/ NBFC/ Agencies" required value="<?php echo $batchs['name_of_bank']?>"  autocomplete="off" data-parsley-maxlength="30" data-parsley-trigger="focusin focusout" maxlength="30" onkeypress="return onlyAlphabets(event)">
									<span class="note" id="addressline1">Note: You can Enter maximum 30 Characters</span></br>
									<span class="error">
										<?php //echo form_error('name_of_bank');?>
									</span> </div>
							</div>

							<?php //if($batchs['remarks'] != '')
               { ?>
								<div class="form-group">
									<label for="remarks " class="col-sm-3 control-label">Remark </label>
									<div class="col-sm-5">
										<textarea style="width:100%; text-align:left;" name="remarks" id="remarks" data-parsley-maxlength="500" maxlength="1000" placeholder="Remarks"><?php echo $batchs['remarks'];?></textarea>
										<span class="note">Note: You can Enter maximum 1000 Characters</span>
									</div>
								</div>
							<?php } ?>

								
							<!--########## START : CODE ADDED BY SAGAR ON 18-08-2020 ###################--->
							<div class="box-header with-border">
								<div class="col-sm-12"> 
									<h3 class="box-title" style="color:#333; width:100%; ">
									<center> &nbsp;<b>Offline / Online Batch</b> </center></h3>
								</div><br>
							</div>
							
							<div class="form-group">								
								<label for="" class="col-sm-3 control-label">Batch Infrastructure <span class="mandatory-field">*</span></label>
								<div class="col-sm-5">
									<label class="radio-inline" style="padding-top: 0;margin-top: -8px; margin-right:20px;">
										<input type="radio" name="batch_online_offline_flag" id="batch_online_offline_flag0" <?php if($batchs['batch_online_offline_flag'] == 0) { echo "checked"; } ?> value="0" required onchange="batch_online_users_show(this.value)"> Offline
									</label>
									<label class="radio-inline" style="padding-top: 0;margin-top: -8px;"> 
										<input type="radio" name="batch_online_offline_flag" id="batch_online_offline_flag1" <?php if($batchs['batch_online_offline_flag'] == 1) { echo "checked"; } ?> value="1" required onchange="batch_online_users_show(this.value)"> Online
									</label>
									<span class="error"></span>
								</div>
							</div>

							<div id="batch_online_users_outer" style="display:none;">
								<div class="form-group">
	                                <label class="col-sm-3 control-label">Name of the online training platform used <span class="mandatory-field">*</span></label>
	                                <div class="col-sm-6">
	                                    <input type="text" onkeypress="return onlyAlphabets(event)" class="form-control" id="online_training_platform" name="online_training_platform"  value="<?php echo $batchs['online_training_platform']; ?>" autocomplete="off" maxlength="50" placeholder="e.g. Zoom/Teams" data-parsley-trigger="focusin focusout">
	                                    <span class="note" id="online_training_platform" >Note: You can Enter maximum 50 Characters</span></br>
	                                    <span class="note-error" id="err_online_training_platform"></span>
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <label class="col-sm-3 control-label">Link <span class="mandatory-field">*</span></label>
	                                <div class="col-sm-6">
	                                    <input type="text" class="form-control" id="platform_link" name="platform_link" value="<?php echo $batchs['platform_link']; ?>" autocomplete="off" maxlength="200" placeholder="" data-parsley-trigger="focusin focusout">
	                                    <span class="note" id="platform_link">Note: Please Enter link with mentioned format https://iibf.org.in/</span></br>
	                                    <span class="note-error" id="err_platform_link"></span>
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
	                            <?php 
	                            if(count($online_batch_user_details) > 0){
		                            foreach ($online_batch_user_details as $key => $value) { ?>
		                            	<div class="" id="textbox-label">
		                                <div class="form-group">
		                                	<label class="col-sm-3 control-label"></label>
			                                <div class="col-sm-2">
			                                    <input type="text" class="form-control" id="batch_online_login_ids<?php echo $key; ?>" name="batch_online_login_ids[]" required="" value="<?php echo $value['login_id']; ?>" autocomplete="off" placeholder="Login ID" data-parsley-trigger="focusin focusout">
			                                </div>
			                                <div class="col-sm-2">
			                                    <input type="password" class="form-control" id="batch_online_login_pass<?php echo $key; ?>" name="batch_online_login_pass[]" value="<?php echo base64_decode($value['password']); ?>" required="" autocomplete="off" placeholder="Password" placeholder="Enter Password"  value="" data-parsley-trigger="focusin focusout">

			                                    <span class="note-error" id="password_error_<?php echo $i; ?>"> <?php echo form_error('password'); ?></span>
			                                </div>
			                                <?php /* <div class="col-sm-4 note">
		                                        Note: 1 upper case letter, 1 lower case letter, 1 numeric value, 1 special character is Compulsory.Minimum:6, Maximum:10
		                                    </div>
		                                    <div class="col-sm-4 note">
		                                        Note: Minimum:6.
		                                    </div>  */ ?>
			                            </div>
			                            </div>
			                            <?php }
			                        } else{ ?>
			                        <div class="" id="textbox-label">
		                                <div class="form-group">
		                                	<label for="roleid" class="col-sm-3 control-label"></label>
			                                <div class="col-sm-2">
		                                        <input type="text" class="form-control" id="batch_online_login_ids0" name="batch_online_login_ids[]" value="" autocomplete="off" maxlength="10" placeholder="Login ID" >
		                                    </div>
		                                    <div class="col-sm-2">
		                                        <input type="password" class="form-control pwd" id="batch_online_login_pass0" name="batch_online_login_pass[]"  autocomplete="off" placeholder="Password" placeholder="Enter Password"  value=""
		                                        data-parsley-errors-container="#password_error<?php echo $i; ?>"
		                                        >
		                                        <span class="note-error" id="password_error_0"> <?php echo form_error('password'); ?></span>
		                                    </div>
		                                   <!--  <div class="col-sm-4 note">
		                                        Note: 1 upper case letter, 1 lower case letter, 1 numeric value, 1 special character is Compulsory. Minimum:6, Maximum:10
		                                    </div>  
		                                     <div class="col-sm-4 note">
		                                        Note: Minimum:6
		                                    </div> -->
		                                </div>
		                            </div>
	                        
								<!--########## END : CODE ADDED BY SAGAR ON 18-08-2020 #################--->
								<?php }?>
						
	                        	<div id="repeatable_userdtls"></div>
	                        </div>

	                        <div class="col-sm-12 button_div">
                                <div class="form-group">
                                  	<label for="roleid" class="col-sm-3 control-label"></label>
                                  	<div class="col-sm-6">
                                   		<a href="javascript:void(0);" id="btn_add_userdtls"><i class="btn btn-primary fa fa-plus-square" title="Add User Details"></i></a>&nbsp;
                                    	<a href="javascript:void(0);" id="btn_remove_userdtls" class="remove-added-box"><i class="btn btn-primary fa fa-minus-square" title="Remove User Details"></i></a>
                                  	</div>
                                </div>
                            </div>
                            
							
							<!--.box-body-->
						</div>
		          		<!--.box-info-->

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
				              	<!-- <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Save">--> 
				             </div>
						</div>
					</div>
				</div>
			</div>

			<?php 
              $k = 1;
              if(count($agency_batch_logs) > 0){?>
               
                  <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Training Batch Status Logs</h3>
                        <div class="box-tools pull-right"> 
                            <!-- Collapse Button -->
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"> <i class="fa fa-minus"></i> </button>
                        </div>
                        <!-- /.box-tools --> 
                    </div>
                      <!-- /.box-header -->
                      <div class="box-body ">
                        <div class="table-responsive">
                          <table id="listitems_logs" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th>Sr.No.</th>
                                <th>Action</th>
                                <th>Action Date </th>
                                <th>Reason</th>
                              </tr>
                            </thead>
                            <tbody class="no-bd-y" id="list222">
                              <?php foreach($agency_batch_logs as $res_log){ 
                                $pre_text = ''; 
                                $log_data = unserialize($res_log['description']);
                                
                                
                                $log_data = unserialize($res_log['description']);
                                $pre_text = '';
                                
                                if(isset($res_log['userid'])){  
                                  $admin_name = $res_log['institute_name'];
                                }else{
                                  $admin_name = '';
                                }
                              
                              
                                if(isset($log_data['rejection'])){  
                                  //$pre_text = 'Rejected by';            
                                  $rejection_reasion = '<span class="red"> '.$log_data['rejection'].'</span>';
                                  /*if(!$agency_center_logs_length ){
                                    $reject_action_date = $res_log['date'];
                                  }*/
                                  if($k == 1){
                                    $reject_action_date = $res_log['date'];
                                  }
                                }
                                else{
                                  $rejection_reasion = '';  
                                }
                                
                                /*if(isset($log_data['updated_by'])){             
                                
                                  if($log_data['updated_by'] == 1  || $log_data['updated_by'] == 'A'){
                                  
                                    $update_by = ' by '.$admin_name.' (A) ';
                                  }else{
                                    $update_by = ' by '.$admin_name.'   (R) ';  
                                  }
                                }
                                else{
                                  $update_by = '';  
                                }*/
                                
                                if(isset($log_data['center_validity_to'])){
                                  
                                  $pre_text = 'Updated Accreditation ';
                                  $Accridation_text = ' : '.date_format(date_create($log_data['center_validity_from']),"d-M-Y").' - '.date_format(date_create($log_data['center_validity_to']),"d-M-Y");
                                }
                                else{
                                  
                                  $Accridation_text = ''; 
                                }
                              ?>
                  
                              <tr>
                                <td><?php echo $k; ?></td>
                                <td><?php echo str_replace("DRA Admin","",$res_log['title']).' '.$Accridation_text; ?></td>
                                <td><?php echo date("d-M-Y h:i:s A", strtotime($res_log['date'])); ?></td>
                                <td><?php echo $rejection_reasion; ?></td>
                              </tr>
                              
                              <?php $k++; } ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <!-- box-footer --> 
                    </div>
                    <!-- /.box --> 
                  
            <?php }?>

            <?php 
				/*$k = 1;
				if(count($batch_checklist_logs) > 0){?>

			        <div class="box">
				        <div class="box-header with-border">
				            <h3 class="box-title">Batch Checklist Logs</h3>
				            <div class="box-tools pull-right"> 
				              	<!-- Collapse Button -->
				              	<button type="button" class="btn btn-box-tool" data-widget="collapse"> <i class="fa fa-minus"></i> </button>
				            </div>
				            <!-- /.box-tools --> 
				        </div>
			          	<!-- /.box-header -->
			          	<div class="box-body ">
				            <div class="table-responsive">
				              <table id="listitems_logs" class="table table-bordered table-striped">
				                <thead>
				                  <tr>
				                  	<th>Sr.No.</th>
				                    <th>Action</th>
				                    <th>Action Date/Time </th>
				                    <th>Description</th>
				                  </tr>
				                </thead>
				                <tbody class="no-bd-y" id="list222">
				                	<?php foreach($batch_checklist_logs as $logs){ ?>
					                
					                  <tr>
					                  	<td><?php echo $k; ?></td>
					                    <td><?php echo $logs['status']; ?></td>
					                    <td><?php echo date_format(date_create($logs['created_on']),"d-M-Y h:i:s"); ?></td>
					                    <td><?php echo $logs['reason']; ?></td>
					                  </tr>
					                
					            	<?php $k++; }?>
					            </tbody>
				              </table>
				            </div>
				        </div>
			          <!-- box-footer --> 
			        </div>
			        <!-- /.box --> 

			<?php }*/?>
				
			<?php 
				$k = 1;
				if(count($activity_logs) > 0){?>

			        <div class="box">
				        <div class="box-header with-border">
				            <h3 class="box-title">Activity Logs</h3>
				            <div class="box-tools pull-right"> 
				              	<!-- Collapse Button -->
				              	<button type="button" class="btn btn-box-tool" data-widget="collapse"> <i class="fa fa-minus"></i> </button>
				            </div>
				            <!-- /.box-tools --> 
				        </div>
			          	<!-- /.box-header -->
			          	<div class="box-body ">
				            <div class="table-responsive">
				              <table id="listitems_logs" class="table table-bordered table-striped">
				                <thead>
				                  <tr>
				                  	<th>Sr.No.</th>
				                    <th>Action</th>
				                    <th>Action Date/Time </th>
				                    <th>Description</th>
				                  </tr>
				                </thead>
				                <tbody class="no-bd-y" id="list222">
				                	<?php foreach($activity_logs as $logs){ ?>
					                
					                  <tr>
					                  	<td><?php echo $k; ?></td>
					                    <td><?php echo $logs['title']; ?></td>
					                    <td><?php echo date_format(date_create($logs['date']),"d-M-Y h:i:s"); ?></td>
					                    <td><?php echo $rejection_reasion; ?></td>
					                  </tr>
					                
					            	<?php $k++; }?>
					            </tbody>
				              </table>
				            </div>
				        </div>
			          <!-- box-footer --> 
			        </div>
			        <!-- /.box --> 

			<?php }?>

		</section>
	</form>
</div>


<style>
	option:disabled {
	color: #999;
	}
</style>
<!-- <link rel="stylesheet" href="<?php echo base_url()?>assets/css/timepicker.css">
<script src="https://www.jonthornton.github.io/jquery-timepicker/jquery.timepicker.js"></script> -->
<!--<script src="https://www.jonthornton.github.io/Datepair.js/dist/jquery.datepair.js"></script>-->
<!--<script src="https://www.jonthornton.github.io/Datepair.js/dist/datepair.js"></script>-->
<script type="text/javascript">	

	var base_url = '<?php echo base_url(); ?>';
	function batch_online_users_show(flag)
	{
		//console.log('flag--'+flag);
		if(flag == 1) 
		{ 
			$("#batch_online_users_outer").css("display","block"); 
			$("[name='batch_online_login_ids[]']").attr('required',true);
			$("[name='batch_online_login_pass[]']").attr('required',true);
			$(".pwd").attr('data-parsley-required',true);
			/*$(".pwd").attr('data-parsley-uppercase','1');
			$(".pwd").attr('data-parsley-lowercase','1');
			$(".pwd").attr('data-parsley-number','1');
			$(".pwd").attr('data-parsley-special','1');*/
			$("#online_training_platform").attr('required',true);
			$("#platform_link").attr('required',true);
			$('.button_div').css('display','block');
			
			/*$("[name='batch_online_login_pass[]']").attr('data-parsley-uppercase',"1");
            $("[name='batch_online_login_pass[]']").attr('data-parsley-lowercase',"1");
            $("[name='batch_online_login_pass[]']").attr('data-parsley-number',"1");
            $("[name='batch_online_login_pass[]']").attr('data-parsley-special',"1");
            $("[name='batch_online_login_pass[]']").attr('data-parsley-minlength',"6");
            $("[name='batch_online_login_pass[]']").attr('minlength',"6");*/
            //$("[name='batch_online_login_pass[]']").attr('maxlength',"10");
		}
		else 
		{ 
			$("#batch_online_users_outer").css("display","none"); 
			$("[name='batch_online_login_ids[]']").removeAttr('required',false);
			$(".pwd").removeAttr('data-parsley-required');
			/*$(".pwd").removeAttr('data-parsley-uppercase');
			$(".pwd").removeAttr('data-parsley-lowercase');
			$(".pwd").removeAttr('data-parsley-number');
			$(".pwd").removeAttr('data-parsley-special');*/
			$("#online_training_platform").removeAttr('required',false);
			$("#platform_link").removeAttr('required',false);
			$('.button_div').css('display','none');

			
			$("[name='batch_online_login_ids[]']").removeAttr('required');
            $("[name='batch_online_login_ids[]']").removeAttr('data-parsley-required');            
            $("[name='batch_online_login_pass[]']").removeAttr('required');
            /*$("[name='batch_online_login_pass[]']").removeAttr('data-parsley-uppercase');
            $("[name='batch_online_login_pass[]']").removeAttr('data-parsley-minlength');
            $("[name='batch_online_login_pass[]']").removeAttr('data-parsley-lowercase');
            $("[name='batch_online_login_pass[]']").removeAttr('data-parsley-number');
            $("[name='batch_online_login_pass[]']").removeAttr('data-parsley-special');
            $("[name='batch_online_login_pass[]']").removeAttr('minlength');*/
            //$("[name='batch_online_login_pass[]']").removeAttr('maxlength');
            $("[name='batch_online_login_pass[]']").removeAttr('data-parsley-required');
		}			
	}
	
	$(document).ready(function()
	{
    	var selected_flag_val = $("input[type=radio][name='batch_online_offline_flag']:checked").val();
		if(typeof  selected_flag_val === 'undefined') { var flag = 0; } else { var flag = selected_flag_val; }
		//console.log('selected_flag_val-------'+selected_flag_val);
		batch_online_users_show(flag);

		
		get_faculty();

		var training_schedule_doc = '<?php echo $batchs['training_schedule']; ?>';

		if(training_schedule_doc == ''){
			$('#training_schedule_doc').css('display','block');
			$('#training_schedule').attr("data-parsley-required",true);
			$('#training_schedule').attr("data-parsley-errors-container", "#training_schedule_error");
		}
		else{
			$('#training_schedule_doc').css('display','none');
			$('#training_schedule').removeAttr("data-parsley-required");
			$('#training_schedule').removeAttr("data-parsley-errors-container");
		}

	});
		
	// Count number of days Added by Manoj     
	function GetDays(){
		var batch_from = new Date(document.getElementById("batch_from").value);
		var batch_to = new Date(document.getElementById("batch_to").value);
		return parseInt((batch_to - batch_from) / (24 * 3600 * 1000));
	}
	
	function chk_days()
    {
    	$('#holidays,#net_days,#time1,#time2,#gross_time,#brk_time1,#brk_time2,#brk_time3,#total_brk_time,#net_time,#total_net_time').val('');

        if(document.getElementById("batch_to").value != ''){
            //document.getElementById("gross_days").value=GetDays();
            var days = GetDays();
            if($.isNumeric(days)){
                days = days + 1;
                $("#gross_days").val(days+' Days');
            }else{
                $("#gross_days").val('0 Days');
            }
        } 

        var gross_days = $('#gross_days').val();
        //var gross_time = $('#gross_time').val();

        $('#gross_days').val(gross_days);
        //$('#net_days').val(gross_days);
    }

    $('.time').on('change',function () {
        //$('#gross_time,#brk_time1,#brk_time2,#brk_time3,#total_brk_time,#net_time,#total_net_time').val('');
        var val = $(this).val();
        //console.log('.time--'+val);
        if(val != ''){
            differenceHours.diff_hours('time1', 'time2', 'gross_time')
        }
    });

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

    function chk_net_days()
    {
		//Start : Code to display the date in ascending order
        let selected_holidays_str = $("#holidays").val();
        if(selected_holidays_str.trim() != '')
        {
            let selected_holidays_arr = selected_holidays_str.split(",");
                
            // Create a new array with dates in the sortable format
            var sortableDateArray = selected_holidays_arr.map(convertToSortableFormat);
            sortableDateArray.sort();

            var convertedArray = sortableDateArray.map(convertToDdMmYyyy);
            var commaSeparatedString = convertedArray.join(",");
            $("#holidays").val(commaSeparatedString);
        }
        else
        {
            $("#holidays").val('');
        }
        //End : Code to display the date in ascending order

		var grossDays = document.getElementById("gross_days").value;
        if(grossDays != ''){
            //document.getElementById("gross_days").value=GetDays();
            var grossDays = grossDays.split(' '); 
            var days = NetDays();
            if($.isNumeric(days)){
                days = grossDays[0] - days;
                $("#net_days").val(days+' Days');
            }else{
                $("#net_days").val('0 Days');
            }
        } 

        //var gross_days = $('#gross_days').val();
        //var gross_time = $('#gross_time').val();

        //$('#net_days').val(gross_days);

        var net_days = $('#net_days').val().split(' ');
        if(net_time_val != ''){
            var net_time = net_time_val.split(':');
            var total_net_time_min = Number(net_time[0]) * 60 + Number(net_time[1]);
            var total_net_time = (net_days[0] * total_net_time_min);
            var hours = Math.floor(total_net_time / 60);  
            var minutes = total_net_time % 60;
        }
        else{
        	var hours = 0;
            var minutes = 0;
        }

        ////console.log('total_net_time=>'+total_net_time);
		if(isNaN(hours)) { hours = 0; }
        if(isNaN(minutes)) { minutes = 0; }
        $('#total_net_time').val(hours + ":" + minutes);
    }

    function NetDays()
    {
        var holidays = document.getElementById("holidays").value;
        var count = 0;
        
        if(holidays != ''){
            if(holidays.includes(',') == true){
                holidaysArr = holidays.split(',');
                count = holidaysArr.length;
            }
            else{
                count = 1;
            }
        }
        //console.log('---'+count);
        //var days = parseInt((gross_days - batch_from) / (24 * 3600 * 1000));
        return count;
    }

    $('.period').on('change',function () {

        var batch_hours = $('input[name="hours"]:checked').val();
        var gross_days = $('#gross_days').val().split(' D');
        gross_days_check(batch_hours,gross_days[0]);
    });

    $('.time_8').on('change',function () {
        var gross_time = $('#gross_time').val().split(':');
        var total_gross_min = Number(gross_time[0]) * 60 + Number(gross_time[1]);
        var hours_8 = 8*60;

        if(total_gross_min > hours_8){
            $('#gross_time_error').text('Gross Time should be less than or equal to 8');
            //$('.btn_submit').attr('disabled',true);
            $('#grossTime_err').val('1');
        }
        else{
            $('#gross_time_error').text('');
            $('.btn_submit').removeAttr('disabled');
            $('#grossTime_err').val('0');
        }
    });

    $('.breakTime').on('change',function () {
         var brk_time1 = $('#brk_time1').val();
        var brk_time2 = $('#brk_time2').val();
        var brk_time3 = $('#brk_time3').val();
        var total_brk_time = parseInt(brk_time1)+parseInt(brk_time2)+parseInt(brk_time3);
        var hours_90 = 90;

        if(total_brk_time > hours_90){
            $('#total_break_time_error').text('Total Break Time should be less than or equal to 90 minutes');
            //$('.btn_submit').attr('disabled',true);
            $('#totalBrk_err').val('1');
        }
        else{
            $('#total_break_time_error').text('');
            $('.btn_submit').removeAttr('disabled');
            $('#totalBrk_err').val('0');
        }
    });

	$('#holidays').on('click', function(e){
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

    var hours_value = $('input[name="hours"]:checked').val();
    //console.log('hours_value'+hours_value);
    var total_net_time = $('#total_net_time').val();
    var total_net_time_hours = total_net_time.split(':');
    var gross_days = $('#gross_days').val().split(' D');

    if(hours_value == '50'){
        $('#tenth_pass_div').css('display','none');
        $('#twelth_pass_div').css('display','none');
        $('#total_net_time_note').text('Note: Total Net Training Time of Duration should be greater than or equal to 50.');
    }
    else{
        $('#tenth_pass_div').css('display','block');
        $('#twelth_pass_div').css('display','block');
        $('#total_net_time_note').text('Note: Total Net Training Time of Duration should be greater than or equal to 100.');
    }

    if(parseInt(total_net_time_hours[0]) >= parseInt(hours_value)){
        //console.log('if');
        $('#total_net_time_error').text('');
        $('.btn_submit').removeAttr('disabled');
		$('#totalnetTime_err').val('0'); 
    }
    else{
        //console.log('else');
        $('#total_net_time_error').text('Total Net Training Time of Duration should be greater than '+hours_value);
        //$('.btn_submit').attr('disabled',true);
		$('#totalnetTime_err').val('1');		
    }

    gross_days_check(hours_value, gross_days[0]);

    $("input[name='hours']").click(function() 
    {   
        var batch_type = $(this).val();
        var total_net_time = $('#total_net_time').val();
        var hours = total_net_time.split(':');
        var gross_days = $('#gross_days').val().split(' D');

        //console.log(hours[0] +'< '+batch_type);
        
        if(batch_type == '50'){
            $('#tenth_pass_div').css('display','none');
            $('#twelth_pass_div').css('display','none');
            $('#tenth_pass_candidates').val('');
            $('#twelth_pass_candidates').val('');
            $('#total_net_time_note').text('Note: Total Net Training Time of Duration should be greater than or equal to 50.');
        }
        else{
            $('#tenth_pass_div').css('display','block');
            $('#twelth_pass_div').css('display','block');
            $('#total_net_time_note').text('Note: Total Net Training Time of Duration should be greater than or equal to 100.');
        }

        if(parseInt(hours[0]) >= parseInt(batch_type)){
            //console.log('iff');
            $('#total_net_time_error').text('');
            $('.btn_submit').removeAttr('disabled');
			$('#totalnetTime_err').val('0');
        }
        else{
            //console.log('elsee');
            $('#total_net_time_error').text('Total Net Training Time of Duration should be greater than '+batch_type);
			$('#totalnetTime_err').val('1');
            //$('.btn_submit').attr('disabled',true);
        }

        calc_candidates();
        gross_days_check(batch_type, gross_days[0]);
    });

	function calc_net_time(){
    //$('.time').on('change',function () {
    	//console.log('time***');
    	//$('#gross_time,#brk_time1,#brk_time2,#brk_time3,#net_time,#total_net_time').val('');
        differenceHours.diff_hours('time1', 'time2', 'gross_time')

        //var gross_days = $('#gross_days').val();
        var gross_time = $('#gross_time').val();

        $('#gross_time').val(gross_time);
    //});
	}

	$('.time').on('blur',function () {
		$('#gross_time,#brk_time1,#brk_time2,#brk_time3,#total_brk_time,#total_net_time').val('');
	});

    $('.netTime').on('change',function () {
        differenceHours.net_diff_hours('time1', 'time2', 'brk_time1', 'brk_time2', 'brk_time3', 'net_time', 'total_brk_time')

        //var gross_days = $('#gross_days').val();
        var net_days = $('#net_days').val().split(' ');
        var net_time = $('#net_time').val().split(':');
        var total_net_time_min = Number(net_time[0]) * 60 + Number(net_time[1]);
        var total_net_time = (net_days[0] * total_net_time_min);

        var hours = Math.floor(total_net_time / 60);  
        var minutes = total_net_time % 60;
        $('#total_net_time').val(hours + ":" + minutes);

        var total_net_time = $('#total_net_time').val();
        var batch_type = $('input[name="hours"]:checked').val();

        //console.log('batch_type++'+batch_type);

        if(hours != 0 && hours < batch_type){
			//console.log('3');
            $('#total_net_time_error').text('Total Net Training Time of Duration should be greater than '+batch_type);
            //$('.btn_submit').attr('disabled',true);
            $('#totalnetTime_err').val('1');
        }
        else{
			//console.log('33');
            $('#total_net_time_error').text('');
            $('.btn_submit').removeAttr('disabled');
            $('#totalnetTime_err').val('0');
        }

        net_time_validate();
    });

    $('.net_time_cls').on('change',function () {

        var netTime = $(this).val();

        if(netTime <= 0){
            $('#time_error').text('Please Select Valid inputs for time and break times');
            //$('.btn_submit').attr('disabled',true);
        }
        else{
            $('#time_error').text('');
            $('.btn_submit').removeAttr('disabled');
        }
    });

    function net_time_validate() 
    {
      var net_time_val = $('#net_time').val().split(':');
      var net_time_min = parseFloat(net_time_val[0]) * 60 + parseFloat(net_time_val[1]);
      var hours_7 = 7 * 60;

      //console.log($('#net_time').val());
      //console.log(net_time_min+' > :'+hours_7);

      if (net_time_min > hours_7) {
        $('#net_time_error').text('Net Time should be less than or equal to 7 Hours.');
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


    $('.radiocls').on('click', function(e){
        var value = $(this).val();
        
        if(value == 'No'){
            $('#training_prd_full_capacity_div').css('display','block');
        }
        else{
            $('#training_prd_full_capacity_div').css('display','none');
        }
    });

    var id_count = '<?php echo count($online_batch_user_details); ?>';
    if(id_count != ''){
      var newid = id_count;
    }
    else{
      var newid = 1;
    }


    $('#btn_add_userdtls').click(function(){

	    var repeatable_userdtls = '<div class="" id="textbox-label"><input type="hidden" name="batch_id[]" id="batch_id'+newid+'" value=""><div class="form-group"><label class="col-sm-3 control-label"></label><div class="col-sm-2"><input type="text" class="form-control" id="batch_online_login_ids'+newid+'" name="batch_online_login_ids[]" placeholder="Login ID" value="" onkeypress="return alphanumeric(event)" data-parsley-trigger="focusin focusout"><span class="note-error" id="login_error'+newid+'"></span></div><div class="col-sm-2"><input type="password" class="form-control" id="batch_online_login_pass'+newid+'" name="batch_online_login_pass[]" placeholder="Password" value="" data-parsley-trigger="focusin focusout" value=""  ><span class="note-error" id="password_error'+newid+'"></span></div><div class="col-sm-4 note"></div> </div></div>';
	    //Note: Minimum:6
	    //maxlength="10"
	    //data-parsley-uppercase="1" data-parsley-lowercase="1" data-parsley-number="1" data-parsley-special="1"
	    //Note: 1 upper case letter, 1 lower case letter, 1 numeric value, 1 special character is Compulsory. Minimum:6, Maximum:10
	      
	    $('#repeatable_userdtls').append(repeatable_userdtls);
	    $('#batch_online_login_ids'+newid).attr('data-parsley-required','true');
	  
	    $('#batch_online_login_pass'+newid).attr('data-parsley-required','true');
	    $('#batch_online_login_pass'+newid).attr('data-parsley-errors-container','#password_error'+newid);

      	newid++;
    });// add radio add more (parent)

    $(document).on('click', '#btn_remove_userdtls', function() {
        
      	$('#batch_online_login_ids'+newid).removeAttr('data-parsley-required');
     
      	$('#batch_online_login_pass'+newid).removeAttr('data-parsley-required','true');
      	$('#batch_online_login_pass'+newid).removeAttr('data-parsley-errors-container');

      	$('div#textbox-label').last().remove(); 
    });
	
	$('.datepairtimes .time').timepicker({
        'timeFormat': 'H:i',
        'showDuration': true,
        'timeFormat': 'g:ia',
        'option': true,
		'explicitMode':true,
		minuteStep: 1,
		disableFocus: true
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

    window.Parsley.addValidator('dracheckpin', function (value, requirement) {
        var response = false;
        var datastring='statecode='+$('#ccstate').val()+'&pincode='+value;
        $.ajax({
            url:site_url+'iibfdra/Version_2/DraExam/checkpin/',
            data: datastring,
            type:'POST',
            async: false,
            success: function(data) {
                if(data=='true')
                {
                    response = true;
                }
                else
                {
                    response = false;
                }
            }

        });
        return response;
    }, 31)
    .addMessage('en', 'dracheckpin', 'Please enter Valid Pincode.');

    function calc_candidates(){
        var tenth_pass_candidates = document.getElementById("tenth_pass_candidates").value;
        var twelth_pass_candidates = document.getElementById("twelth_pass_candidates").value;
        var graduate = document.getElementById("graduate_candidates").value;
        if(tenth_pass_candidates == ''){
        	tenth_pass_candidates = 0;
        }
        if(twelth_pass_candidates == ''){
        	twelth_pass_candidates = 0;
        }
        if(graduate == ''){
        	graduate = 0;
        }
        var total_candidates = parseInt(tenth_pass_candidates)+parseInt(twelth_pass_candidates)+parseInt(graduate);
        //console.log('total_candidates'+total_candidates);
        if(total_candidates == 0){
        	$('#total_candidates').val('');
        }
        else{
        	$('#total_candidates').val(total_candidates);
        }

        if(total_candidates > 35){
            $('#total_candidates_error').text('Total Candidates should be less than or equal to 35');
            //$('.btn_submit').attr('disabled',true);
        }
        else{
            $('#total_candidates_error').text('');
            $('.btn_submit').removeAttr('disabled');
        }
    }

     function form_submit(){
        
       // alert(e);
        //$( "#draExamAddFrm" )[0].submit();    
        var grossTime_err =  $('#grossTime_err').val();
        var validateDoc_err =  $('#validateDoc_err').val();
        var totalBrk_err =  $('#totalBrk_err').val();
        var netTime_err =  $('#netTime_err').val();
        var totalnetTime_err =  $('#totalnetTime_err').val();
        var grossDays_err =  $('#grossDays_err').val();
        var first_faculty_err = $('#first_faculty_err').val();
	    var sec_faculty_err = $('#sec_faculty_err').val();
	    var additional_first_faculty_err = $('#additional_first_faculty_err').val();
	    var additional_sec_faculty_err = $('#additional_sec_faculty_err').val();

        if($("#draExamAddFrm").parsley().isValid() && grossTime_err == 0 && validateDoc_err == 0 && totalBrk_err == 0 && netTime_err == 0 && totalnetTime_err == 0 && grossDays_err == 0 && first_faculty_err == 0 && sec_faculty_err == 0 && additional_first_faculty_err == 0 && additional_sec_faculty_err == 0){
            //console.log('if');
            //$( "#draExamAddFrm" )[0].submit();
             return true;
        }
        else{
            //console.log('else');
            //e.preventDefault();
			if($("#draExamAddFrm").parsley().isValid()) 
            { 
                if($('#grossDays_err').val() == 1) { $("#gross_days").focus(); }
                else if($('#totalnetTime_err').val() == 1) { $("#total_net_time").focus(); }
            }
            return false;
        }
    }

    var srcContent;
    function validateDoc(e, error_id){
        var srcid = e.srcElement.id;
        if( document.getElementById(srcid).files.length != 0 ){
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
		          $('#'+error_id).text('This file is corrupted');
		          //$('.btn_submit').attr('disabled',true);
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
			                reader.onload = function (e) {
			                    srcContent=  e.target.result;
			                }
			                reader.readAsDataURL(file);
			                $('#'+error_id).text('');
			                $('.btn_submit').removeAttr('disabled');
			                $('#validateDoc_err').val('0');
			            }
		            }
		        }
		    }
        }
        else{
            $('#'+error_id).text('Please select file');
            //$('.btn_submit').attr('disabled',true);
            $('#validateDoc_err').val('1');
        }
    }

    function get_faculty()
    {
        var first_faculty = $('#first_faculty').val(); 
        var sec_faculty = $('#sec_faculty').val();
        var additional_first_faculty = $('#additional_first_faculty').val();
        var additional_sec_faculty = $('#additional_sec_faculty').val();
        

        var id = event.srcElement.id;  
	    var value = $('#'+id).children(":selected").val();
	    var from_date = $('#batch_from').val();
	    var to_date = $('#batch_to').val();

	    $.ajax({
	      type: 'POST',
	      data: {faculty_id:value, from_date:from_date, to_date:to_date},
	      url: site_url + 'iibfdra/Version_2/TrainingBatches/check_faculty_mapping/',
	      success: function(res) {
	        if (res != '') {
	          console.log(res);
	          var res1 = res.split('---');
	          if(res1[0]>=2){
	            console.log(id+'_error');
	            $('#'+id+'_error').text(' This faculty is mapped with '+res1[1]);
	            $('#'+id+'_err').val(1);
	          }
	          else{
	            $('#'+id+'_error').text('');
	            $('#'+id+'_err').val(0);
	          }
	        }
	      }
	    });

	    $('#first_faculty option').removeAttr("disabled");
        $('#sec_faculty option').removeAttr("disabled");
        $('#additional_first_faculty option').removeAttr("disabled");
        $('#additional_sec_faculty option').removeAttr("disabled");

        if(first_faculty != "")
        {
            $('#sec_faculty option[value='+first_faculty+']').attr("disabled", "disabled");
            $('#additional_first_faculty option[value='+first_faculty+']').attr("disabled", "disabled");
            $('#additional_sec_faculty option[value='+first_faculty+']').attr("disabled", "disabled");
        }
        
        if(sec_faculty != "")
        {
            $('#first_faculty option[value='+sec_faculty+']').attr("disabled", "disabled");
            $('#additional_first_faculty option[value='+sec_faculty+']').attr("disabled", "disabled");
            $('#additional_sec_faculty option[value='+sec_faculty+']').attr("disabled", "disabled");
        }
        
        if(additional_first_faculty != "")
        {
            $('#first_faculty option[value='+additional_first_faculty+']').attr("disabled", "disabled");
            $('#sec_faculty option[value='+additional_first_faculty+']').attr("disabled", "disabled");
            $('#additional_sec_faculty option[value='+additional_first_faculty+']').attr("disabled", "disabled");
        }
        
        if(additional_sec_faculty != "")
        {
            $('#first_faculty option[value='+additional_sec_faculty+']').attr("disabled", "disabled");
            $('#sec_faculty option[value='+additional_sec_faculty+']').attr("disabled", "disabled");
            $('#additional_first_faculty option[value='+additional_sec_faculty+']').attr("disabled", "disabled");
        }

        /*
        var last_val = $('#sec_faculty').attr('last_val');
        var last_val1 = $('#additional_first_faculty').attr('last_val');
        var last_val2 = $('#additional_sec_faculty').attr('last_val');
        var last_val3 = $('#first_faculty').attr('last_val');
        //console.log('last_val--'+last_val);
        
        if(last_val != '')
        {
            $('#sec_faculty option[value='+last_val+']').removeAttr("disabled");
            $('#additional_first_faculty option[value='+last_val+']').removeAttr("disabled");
            $('#additional_sec_faculty option[value='+last_val+']').removeAttr("disabled");
        }

        if(last_val1 != '')
        {
            $('#additional_first_faculty option[value='+last_val+']').removeAttr("disabled");
            $('#additional_sec_faculty option[value='+last_val+']').removeAttr("disabled");
        }

        if(last_val2 != '')
        {
           $('#additional_sec_faculty option[value='+last_val+']').removeAttr("disabled");
        }
     
        if(first_faculty != '')
        {
            $('#sec_faculty option[value='+first_faculty+']').attr("disabled", "disabled");
            $('#additional_first_faculty option[value='+first_faculty+']').attr("disabled", "disabled");
            $('#additional_sec_faculty option[value='+first_faculty+']').attr("disabled", "disabled");
            $('#sec_faculty').attr("last_val",first_faculty);  
        }

        if(sec_faculty != '')
        {
            $('#first_faculty option[value='+sec_faculty+']').attr("disabled", "disabled");
            $('#additional_first_faculty option[value='+sec_faculty+']').attr("disabled", "disabled");
            $('#additional_sec_faculty option[value='+sec_faculty+']').attr("disabled", "disabled");
            $('#additional_first_faculty').attr("last_val",sec_faculty);
        }

        if(additional_first_faculty != '')
        {
            $('#first_faculty option[value='+additional_first_faculty+']').attr("disabled", "disabled");
            $('#sec_faculty option[value='+additional_first_faculty+']').attr("disabled", "disabled");
            $('#additional_sec_faculty option[value='+additional_first_faculty+']').attr("disabled", "disabled");
            $('#additional_sec_faculty').attr("last_val",additional_first_faculty);
        }

        if(additional_sec_faculty != '')
        {
            $('#first_faculty option[value='+additional_sec_faculty+']').attr("disabled", "disabled");
            $('#sec_faculty option[value='+additional_sec_faculty+']').attr("disabled", "disabled");
            $('#additional_first_faculty option[value='+additional_sec_faculty+']').attr("disabled", "disabled");
            $('#first_faculty').attr("last_val",additional_sec_faculty);
        } */ 
    }

    function removeImg(batch_id){
        //$("#loading").show();
        ////console.log('removeImg1'+faculty_id);
        $("#training_schedule_show").hide();
		//$('#btn_remove').hide();
		$('#training_schedule_doc').css('display','block');
		$('#training_schedule').attr("data-parsley-required",true);
		$('#training_schedule').attr("data-parsley-errors-container", "#training_schedule_error");

      /*$.ajax({
        type: 'POST',
        url: base_url+"iibfdra/Version_2/TrainingBatches/removeFile",
        data: {batch_id: batch_id, doc:'training_schedule'},
        dataType: "text",
        success: function(data) { 
          	//console.log(data);
          	if(data.trim() == 1){
            	$("#training_schedule_show").hide();
            	//$('#btn_remove').hide();
           	 	$('#training_schedule_doc').css('display','block');
				$('#training_schedule').attr("data-parsley-required",true);
				$('#training_schedule').attr("data-parsley-errors-container", "#training_schedule_error");
          	}
	         else if(data.trim() == 0){
	            //$("#loading").hide();
	            alert('error in deletion');
	            $('#training_schedule_doc').css('display','none');
				$('#training_schedule').removeAttr("data-parsley-required");
				$('#training_schedule').removeAttr("data-parsley-errors-container");
	        }
      
        }
      });*/
  }
	
	$(document).ready(function() {
		//chk_days();
		get_faculty();
		

		// on change of center get inspector master details and center details
		$('#center_id').change(function(){
			$("#loading").show();
			var center_id = $(this).val();
     		// AJAX request
			$.ajax({
				url:'<?php echo base_url()?>iibfdra/Version_2/TrainingBatches/getcenterDetails',
				method: 'post',
				data: {center_id: center_id}, 
				dataType: 'json',
				success: function(response){
      				// Add options
					$.each(response,function(index,data){
						$('#addressline1').val(data['address1']);
						$('#addressline2').val(data['address2']);
						$('#addressline3').val(data['address3']);
						$('#addressline4').val(data['address4']);
						if(data['city']!='')
						{
							$('#ccity').val(data['city_name']);
						}else{
							$('#ccity').val(data['location_name']);
						}
						$('#cccity').val(data['location_name']);
						$('#cdistrict').val(data['district']);
						$('#cstate').val(data['state_name']);
						$('#ccstate').val(data['state_code']);
						$('#cpincode').val(data['pincode']);
						
						//$('#contact_person_name').val(data['contact_person_name']);
						//$('#contact_person_phone').val(data['contact_person_mobile']);
						
						// custom validation code added by Manoj MMM	
      					var validity_from_ck = '+3d';//data['validity_chk_from'];
      					var validity_to_ck = '+5d';//data['center_validity_to'];
      			
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
			
		});
		
		//alert($('#validdate').text());
		$('#batch_from').datepicker({
			format: "yyyy-mm-dd",
      startDate: '<?php echo $date_check; ?>',
			//startDate: '+3d',//$('#validity_from').val(),
			//endDate:'+5d',
			autoclose: true,
      //todayBtn: "linked", 
      keyboardNavigation: true, 
      forceParse: false, 
      //calendarWeeks: true, 
      //todayHighlight:true, 
      //clearBtn: true    
		}).attr('readonly', 'readonly');
		
		
    var batch_to_date_start = $('#batch_from').datepicker('getDate', '+5d'); 
    batch_to_date_start.setDate(batch_to_date_start.getDate()+5);

		$('#batch_to').datepicker({
			format: "yyyy-mm-dd",
      //startDate: '<?php echo $date_check; ?>',
      startDate: batch_to_date_start,
      //startDate:'+5d',// $('#validity_from').val(), //'+6d',
			//endDate:$('#validity_to').val(),
			autoclose: true,
      //todayBtn: "linked", 
      keyboardNavigation: true, 
      forceParse: false, 
      //calendarWeeks: true, 
      //todayHighlight:true, 
      //clearBtn: true    
		}).attr('readonly', 'readonly');
		// validation for no of candidates     

		$('#holidays').datepicker({
            multidate: true,
            format: 'dd-mm-yyyy'
        });
		
		
		$('#batch_from').change(function() 
    {
			BatchToDateStart();
			chk_days();
		});

    function BatchToDateStart()
    {
      var date_check = '<?php echo $date_check; ?>';
			var date2 = $('#batch_from').datepicker('getDate', '+5d'); 
			var vval = $('#batch_from').val();
			//alert(date2);	
			if(vval != '')
      {		
				if(vval < date_check)
        {
          $('#batch_from_error').text('Please Enter Batch From Date greater than '+date_check);
          //$('#btnSubmit').attr('disabled',true);
          errCnt = 1;
        }
        else
        {
          $('#batch_from_error').text('');
          $('#btnSubmit').attr('disabled',false);
          errCnt = 0;   
        }

				date2.setDate(date2.getDate()+5); 								
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
		
		//check wich type is selected       
		/*$("input[name='batch_type']").click(function() {
			
			var test = $(this).val();
			// alert(test);
			if(test == 'Separate'){
				$("div#type_div").show();
			}
			else{
				$("div#type_div").hide();
			}
			
		});*/
		
		
		
		function isUrlValid(url) 
		{
			return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
		}
		
		$('input[name="platform_link"]').keyup(function(e)
		{
			if(isUrlValid($(this).val()) == false)
			{
				$("#err_platform_link").text("Please enter valid url");
				return false;
			}
			else
			{
				$("#err_platform_link").text("");
				return true;
			};
			
		});
		//change captcha
		$('#new_captcha').click(function(event){
			event.preventDefault();
			var sdata = {'captchaname':'draexamcaptcha'};
			$.ajax({
				type: 'POST',
				data: sdata,
				url: site_url+'iibfdra/Version_2/DraExam/generatecaptchaajax/',
				success: function(res)
				{   
					if(res!='')
					{$('#captcha_img').html(res);
					}
				}
			});
		});
		//if invalid captcha entered keep unchecked exam mode disabled
		if( $("input[name='exam_mode']:checked").length > 0) {
			$("input[name='exam_mode']:not(:checked)").attr('disabled', true);
		}
		
		$("body").on("contextmenu",function(e){
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

	function gross_days_check(batch_hours,gross_days) {
        //console.log('batch_hours++'+batch_hours+'**gross_days**'+gross_days);
        if(parseInt(batch_hours) == 100){
        	$('#gross_days_note').text('Note: Gross Days should be less than or equal to 30.');
            if(parseInt(gross_days) > 30){
                $('#gross_days_error').text('Gross Days should be less than or equal to 30.');
                //$('.btn_submit').attr('disabled',true);
                $('#grossDays_err').val('1');
            }
            else{
                $('#gross_days_error').text('');
                $('.btn_submit').removeAttr('disabled');
                $('#grossDays_err').val('0');
            }
        }

        if(parseInt(batch_hours) == 50){
        	$('#gross_days_note').text('Note: Gross Days should be less than or equal to 20.');
            if(parseInt(gross_days) > 20){
                $('#gross_days_error').text('Gross Days should be less than or equal to 20.');
                //$('.btn_submit').attr('disabled',true);
                $('#grossDays_err').val('1');
            }
            else{
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
	    if ((keycode > 64 && keycode < 91) || (keycode > 96 && keycode < 123) || keycode == 32)  
	    {     
	      return true;    
	    }
	    else
	    {
	      return false;
	    }
	         
	}

	function alphanumeric(key){
	    var keycode = (key.which) ? key.which : key.keyCode;

	    if ((keycode > 64 && keycode < 91) || (keycode > 96 && keycode < 123) || keycode == 8 || keycode == 32 || (keycode >= 48 && keycode <= 57))
	    {     
	      return true;    
	    }
	    else
	    {
	      return false;
	    }
	}
</script> 
<!-- Data Tables --> 
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script> 
<!-- <script src="<?php echo base_url()?>js/validation_dra_batch.js"></script>  