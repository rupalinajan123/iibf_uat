<!DOCTYPE html>
<html>
	<head>
		<?php $this->load->view('professional_bankers/inc_header'); ?>
	</head>
	<body class="hold-transition skin-blue sidebar-mini">
		<div class="wrapper">
			<?php $this->load->view('professional_bankers/inc_navbar'); ?>			
			
			<div class="loading" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/loading.gif"></div>				
			<div class="content-wrapper">
				<section class="content-header">
					<h1>Welcome <?php if(count($member_data) > 0) { echo $member_data[0]['namesub'].' '.$member_data[0]['firstname'].' '.$member_data[0]['lastname']; } else { echo 'User'; } ?></h1>
				</section>
				<section class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="box box-info">
								<div class="box-body">
									<?php 
										if($this->session->flashdata('error')!=''){?>								
										<div class="alert alert-danger alert-dismissible" id="error_id">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
											<?php echo $this->session->flashdata('error'); ?>
										</div>								
										<?php }	
										
										if($this->session->flashdata('success')!=''){ ?>
										<div class="alert alert-success alert-dismissible" id="success_id">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
											<?php echo $this->session->flashdata('success'); ?>
										</div>
									<?php }	?>
									
									<?php if(count($member_data) > 0) {	?>
										<form class="form-horizontal" name="professional_bankers" id="professional_bankers" method="post" enctype="multipart/form-data" action="<?php echo site_url('professional_bankers/dashboard'); ?>">
											<input type="hidden" name="professional_bankers_form" id="professional_bankers_form" value="1">
											<input type="hidden" name="fee_amount" id="fee_amount" value="<?php echo $fee_amount; ?>">
											<div class="table-responsive">
												<table class="table table-bordered" style="max-width:600px; margin:20px auto;">
													<tbody>
														<tr><td><strong>Name </strong></td><td><?php echo $member_data[0]['namesub'].' '.$member_data[0]['firstname'].' '.$member_data[0]['lastname']; ?></td></tr>
														<tr><td><strong>Email </strong></td><td><?php echo $member_data[0]['email']; ?></td></tr>
														<tr><td><strong>Mobile </strong></td><td><?php echo $member_data[0]['mobile']; ?></td></tr>
														<tr><td><strong>Membership No. </strong></td><td><?php echo $member_data[0]['regnumber']; ?></td></tr>
														<tr><td><strong>Exam Name </strong></td><td><?php if(count($exam_data) > 0) { echo $exam_data[0]['description']; } ?></td></tr>
														
														<?php 
															//echo '<pre>'; print_r($member_exam_application_data); echo '</pre>';
															//echo '<div style="display:none"><pre>'; print_r($member_data); echo '</pre></div>'; 
														$show_upload_flag = 1;
														$disp_status = '';
														if(count($member_exam_application_data) > 0)
														{
															if($member_exam_application_data[0]['PaymentStatus'] == '1' && $member_exam_application_data[0]['kyc_status'] == '1') // payment completed and kyc approved
															{ 
																$show_upload_flag = 0;	
																$disp_status = 'KYC Approved';
															}
															else if($member_exam_application_data[0]['PaymentStatus'] == '1' && $member_exam_application_data[0]['kyc_status'] == '0') // payment completed, but kyc is pending
															{ 
																$show_upload_flag = 0;
																$disp_status = 'KYC Pending';
															}
																else if($member_exam_application_data[0]['PaymentStatus'] == '1' && $member_exam_application_data[0]['kyc_status'] == '2') // payment completed, and kyc is rejected
																{ 
																	$show_upload_flag = 0;
																	$disp_status = 'KYC Rejected';
																}
														}
														
														if($show_upload_flag == 1) 
														{ ?>
															<tr>
																<td><strong>Amount </strong></td>
																<td>
																	<?php echo $fee_amount; ?>
																</td>
															</tr>
														
															<tr>
																<td><strong>Upload Experience Certificate <span class="text-danger">*</span></strong></td>
																<td>
																	<input type="file" name="exp_cert" id="exp_cert" class="form-control" required>
																	<small><b>Note : Please upload only PDF file less than 5MB</b></small>
																	<span id="exp_cert_err"></span>
																	<?php if(form_error('exp_cert')!=""){ ?><label class="error"><?php echo form_error('exp_cert'); ?></label> <?php } ?>
																	<?php if($exp_cert_error != ""){ ?><label class="error"><?php echo $exp_cert_error; ?></label> <?php } ?>
																</td>
															</tr>
															
															<?php if($fee_amount > 0) 
															{ ?>
																<tr>
																	<td colspan="2" class="text-center">
																		<button type="submit" class="btn btn-info btn-flat" name="professional_bankers" value="pay_now">Pay Now</button>
																	</td>
																</tr>
												<?php } 
														}
														else
														{	?>
															<tr><td><strong>Amount </strong></td><td><?php echo $member_exam_application_data[0]['amount']; ?></td></tr>
															<tr><td><strong>Payment Date </strong></td><td><?php echo $member_exam_application_data[0]['date']; ?></td></tr>
															<tr><td><strong>Transaction No. </strong></td><td><?php echo $member_exam_application_data[0]['transaction_no']; ?></td></tr>
															<tr><td><strong>Status </strong></td><td><?php echo $disp_status; ?></td></tr>
															
															<tr><td><strong>Uploaded Experience Certificate </strong></td><td><a href="<?php echo site_url('professional_bankers/download_exp_cert/'.base64_encode($member_exam_application_data[0]['pb_reg_id'])); ?>" class="btn btn-sm btn-primary" style="padding:1px 5px 2px" target="_blank">Download</a></td></tr>
															
															<?php if($member_exam_application_data[0]['kyc_status'] == '2')
																{	?>
																<tr><td><strong>Rejected Reason </strong></td><td><?php echo $member_exam_application_data[0]['remark']; ?></td></tr>
																
																<tr>
																	<td><strong>Re-Upload Experience Certificate <span class="text-danger">*</span></strong></td>
																	<td>
																		<input type="file" name="exp_cert" id="exp_cert" class="form-control" required>
																		<small><b>Note : Please upload only PDF file less than 5MB</b></small>
																		<span id="exp_cert_err"></span>
																		<?php if(form_error('exp_cert')!=""){ ?><label class="error"><?php echo form_error('exp_cert'); ?></label> <?php } ?>
																		<?php if($exp_cert_error != ""){ ?><label class="error"><?php echo $exp_cert_error; ?></label> <?php } ?>
																	</td>
																</tr>
																
																<tr>
																	<td colspan="2" class="text-center">
																		<button type="submit" class="btn btn-info btn-flat" name="professional_bankers" value="re-upload">Submit</button>
																	</td>
																</tr>
																<?php	}
															} ?>
													</tbody>
												</table>
											</div>
										</form>
										<?php }
										else
										{	?>
										<br><br><h4 class="text-center text-danger"><b>Member data not available in System. Kindly contact administrator.</b></h4><br><br><br>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</section>
				
				
				<?php //echo '<pre>'; print_r($rejection_logs); echo '</pre>'; //exit; 
					if(count($rejection_logs) > 0)
					{	?>					
					<section class="content-header">
						<h1>Rejected Document History</h1>
					</section>
					<section class="content" style="min-height:auto;">
						<div class="row">
							<div class="col-md-12">
								<div class="box box-info">
									<div class="box-body">									
										<div class="table-responsive">
											<table class="table table-bordered dataTables-example">
												<thead>
													<th class="text-center">Sr. No</th>
													<th class="text-center">Remark</th>
													<th class="text-center">Document</th>
													<th class="text-center">Rejection Date</th>
												</thead>
												
												<tbody>
													<?php 
														$sr_no = 1;
														foreach($rejection_logs as $res)
														{	?>
														<tr>
															<td class="text-center"><?php echo $sr_no; ?></td>
															<td><?php echo $res['remark']; ?></td>
															<td class="text-center">
																<a href="<?php echo site_url('professional_bankers/download_exp_cert_log/'.base64_encode($res['log_id'])); ?>" class="btn btn-sm btn-primary" style="padding:1px 5px 2px" target="_blank">Download</a>
															</td>
															<td><?php echo $res['created_on']; ?></td>																
														</tr>
														<?php $sr_no++;
														}	?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>
				<?php	} ?>
			</div>
			
			<?php $this->load->view('professional_bankers/inc_footer_text'); ?>
		</div>
		<?php $this->load->view('professional_bankers/inc_footer'); ?>
		
		<script src="<?php echo base_url()?>js/jquery.validate.min.js"></script>
		<?php $this->load->view('apply_elearning/common_validation_all'); ?>
		
		<script type="text/javascript">
			$(document ).ready( function() 
			{
				$("#professional_bankers").validate( 
				{
					rules:
					{
						fee_amount: { required : true },	
						exp_cert: { required : true, valid_file_format:'.pdf', filesize_max:5000000 },	
					},
					messages:
					{
						exp_cert: { required : "Please upload the Experience Certificate", valid_file_format:"Please upload only PDF file", filesize_max:"Please upload file less than 5MB" },
					},
					errorPlacement: function(error, element) // For replace error 
					{
						if (element.attr("name") == "exp_cert") 
						{
							error.insertBefore("#exp_cert_err");
						}
						else 
						{
							error.insertAfter(element);
						}
					},
				});
			});			
		</script>
	</body>
</html>