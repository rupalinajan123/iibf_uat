<!DOCTYPE html>
<html>
	<head>
		<?php $this->load->view('professional_bankers/inc_header'); ?>
		<style>
			.break_word { word-break: break-word; white-space: normal; word-wrap: anywhere; }
			.nowrap { white-space:nowrap; }
			.dataTables_wrapper { max-width: 97%; margin: 20px auto 0; }
		</style>
	</head>
	<body class="hold-transition skin-blue sidebar-mini">
		<div class="wrapper">
			<?php $this->load->view('professional_bankers/inc_navbar'); ?>			
			
			<div class="loading" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/loading.gif"></div>				
			<div class="content-wrapper">
				<section class="content-header">
					<h1>Payment History - <?php echo $exam_data[0]['description']; ?></h1>
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
									
									<div class="table-responsive">
										<table class="table table-bordered dataTables-example">
											<thead>
												<th class="text-center">Sr. No</th>
												<th class="text-center">Member Number</th>
												<th class="text-center">Amount</th>
												<th class="text-center">Payment Date</th>
												<th class="text-center">Payment Status</th>
												<th class="text-center">Transaction No</th>
												<th class="text-center">Experience Certificate</th>
												<th class="text-center">KYC Status</th>
												<th class="text-center">Remark</th>
											</thead>
											
											<tbody>
												<?php if(count($payment_history_data) > 0)
													{
														$sr_no = 1;
														foreach($payment_history_data as $res)
														{	?>
														<tr>
															<td class="text-center"><?php echo $sr_no; ?></td>
															<td><?php echo $res['regnumber']; ?></td>
															<td><?php echo $res['amount']; ?></td>
															<td><?php echo $res['PaymentDate']; ?></td>
															<td>
																<?php 
																	if($res['PaymentStatus'] == 0) { echo 'Fail'; }
																	else if($res['PaymentStatus'] == 1) { echo 'Success'; }
																	else if($res['PaymentStatus'] == 2) { echo 'Pending'; }
																else if($res['PaymentStatus'] == 3) { echo 'Refund'; }	?>
															</td>
															<td><?php echo $res['transaction_no']; ?></td>
															<td class="text-center"><a href="<?php echo site_url('professional_bankers/download_exp_cert/'.base64_encode($res['pb_reg_id'])); ?>" class="btn btn-sm btn-primary" style="padding:1px 5px 2px" target="_blank">Download</a></td>
															<td>
																<?php 
																	if($res['PaymentStatus'] == 1 || $res['PaymentStatus'] == 3)
																	{
																		if($res['kyc_status'] == 0) { echo 'Pending'; }
																		else if($res['kyc_status'] == 1) { echo 'Approved'; }
																		else if($res['kyc_status'] == 2) { echo 'Rejected'; }
																	}
																else { echo '--'; } ?>
															</td>
															<td class="break_word"><?php if($res['remark'] != "") { echo $res['remark']; } else { echo '--'; } ?></td>
														</tr>
														<?php $sr_no++;
														}
													}	?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>			
			<?php $this->load->view('professional_bankers/inc_footer_text'); ?>
		</div>
		<?php $this->load->view('professional_bankers/inc_footer'); ?>
		
		<link href="<?php echo base_url('assets/admin/plugins/datatables/dataTables.bootstrap.css'); ?>" rel="stylesheet">
		<link href="<?php echo base_url('assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.cssx'); ?>" rel="stylesheet">
		<link href="<?php echo base_url('assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css'); ?>" rel="stylesheet">
		
		<!-- Data Tables -->
		<script src="<?php echo base_url('assets/admin/plugins/datatables/jquery.dataTables.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/plugins/datatables/dataTables.bootstrap.js'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.jsx'); ?>"></script>
		<script src="<?php echo base_url('assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js'); ?>"></script>
		
		<script>	
			$(document).ready(function(){
				$('.dataTables-example').DataTable({
					pageLength: 10,
					responsive: true,
					"columnDefs": [ {
						"targets": 'no-sort',
						"orderable": false,
					} ],
					"aaSorting": [],
					//"stateSave": true,
				});				
			});
		</script>
	</body>
</html>