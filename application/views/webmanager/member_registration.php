<!DOCTYPE html>
<html>
	<head>
    <?php $this->load->view('webmanager/includes/header');?>
	</head>
	
	<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
			<?php $this->load->view('webmanager/includes/topbar'); ?>
			<?php $this->load->view('webmanager/includes/sidebar'); ?>
			
			<div class="content-wrapper" style="min-kash: 946px;">
				<section class="content">
					<div id="custom_msg_outer"></div>
					
					<div class="">
						<div class="row">
							<div class="col-lg-12">
								<h4 class="title_common">Member Registration Count</h4>
								<form id="myForm" name="myForm" method="post" action="" enctype="multipart/form-data" role="form">
									<input type="hidden" id="security_token" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
									
									<div class="row">
										<?php if(set_value('registration_type')) { $registration_type = set_value('registration_type'); } ?>
										<div class="col-lg-12">								
											<div class="form-group">
												<label for="registration_type">Registration Type <em class="red">*</em></label>
												<div id="registration_type_outer">
													<select name="registration_type[]" id="registration_type" class="form-control chosen-select" multiple data-placeholder="Select Registration Type">
														<?php 
															if(count($registration_type_data) > 0)
															{
																foreach($registration_type_data as $row)
																{ ?>
																<option <?php if(in_array($row['registration_type'],$registration_type)) { echo 'selected'; }?> value="<?php echo $row['registration_type']; ?>"><?php echo $row['registration_type'];?></option>	
																<?php }
															}
														?>
													</select>
												</div>
												<p style="margin: 3px 0 0 1px;line-height: 15px;" id="registration_type_msg"><a href="javascript:void(0)" onclick="open_RegistrationTypeModal()"><strong>Click Here</strong></a> to add new Registration Type</p>
												<?php if(form_error('registration_type[]')!=""){ ?><label class="error"><?php echo form_error('registration_type[]'); ?></label><?php } ?>
											</div>
										</div>
									</div>								
									
									<div class="row">									
										<div class="col-lg-6">								
											<div class="form-group">
												<label for="from_date">From Date</label>
												<input type="text" name="from_date" id="from_date" class="form-control" value="<?php if(set_value('from_date')) { echo set_value('from_date'); } else { echo $from_date; } ?>" placeholder="From Date">
											</div>
										</div>									
										
										<div class="col-lg-6">								
											<div class="form-group">
												<label for="to_date">To Date</label>
												<input type="text" name="to_date" id="to_date" class="form-control" value="<?php if(set_value('to_date')) { echo set_value('to_date'); } else { echo $to_date; } ?>" placeholder="To Date">
											</div>
										</div>
									</div>
									
									<div class="row">
										<?php $this->load->view('webmanager/includes/common_count'); ?>
										
										<div class="col-lg-12">
											<div class="form-group" style="margin: 0 auto;">
												<input type="submit" id="submit" name="submit" class="btn btn-info" value="Submit"> 
												<a href="<?php echo site_url('webmanager/member_registration'); ?>" class="btn btn-info">Clear</a>
											</div>								
										</div>	
										
										<div class="hide d-none">
											<?php echo $result_qry; ?>
										</div>
									</div>								
								</form>
							</div>
						</div>
					</div>
				</section>
			</div>
			
			<?php $this->load->view('webmanager/inc_member_registration_common');?>
			<script>$( document ).ready( function () { $('#page_loader').delay(0).fadeOut('slow'); });</script>
		</div>
	</body>
</html>