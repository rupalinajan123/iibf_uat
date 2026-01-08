<div class="content-wrapper">
	<section class="content-header">
		<h1>Welcome <?php echo $member_data[0]['namesub'].' '.$member_data[0]['firstname'].' '.$member_data[0]['lastname']; ?></h1>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
            <div style="float:right;">
						</div>
						<h3 class="box-title"></h3>
					</div>
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
						
						<form class="form-horizontal" name="JaiibReschedule_121" id="JaiibReschedule_121" method="post" enctype="multipart/form-data" action="<?php echo site_url('JaiibReschedule_121/add_record'); ?>">
							<div class="table-responsive">
								<table class="table table-bordered" style="max-width:600px; margin:20px auto;">
									<tbody>
										<tr><td><strong>Name </strong></td><td><?php echo $member_data[0]['namesub'].' '.$member_data[0]['firstname'].' '.$member_data[0]['lastname']; ?></td></tr>
										<tr><td><strong>Email </strong></td><td><?php echo $member_data[0]['email']; ?></td></tr>
										<tr><td><strong>Mobile </strong></td><td><?php echo $member_data[0]['mobile']; ?></td></tr>
										<tr><td><strong>Membership No. </strong></td><td><?php echo $member_data[0]['regnumber']; ?></td></tr>
										<tr><td><strong>Exam Name </strong></td><td><?php if(count($exam_data) > 0) { echo $exam_data[0]['description']; } ?></td></tr>
										<tr><td><strong>Exam Period </strong></td><td><?php if(count($exam_data) > 0) { echo $exam_data[0]['exam_period']; } ?></td></tr>
										
										<?php if(count($elearning_data) > 0) { ?>
											<tr>
												<td><strong>Elearning Subjects </strong></td>
												<td>
													<?php $i=1;
														foreach($elearning_data as $elearning_res)
														{
															echo $i.'. '.$elearning_res['sub_dsc'];
															if($i < count($elearning_data)) { echo '<br>'; }
															$i++;
														}	?>
												</td>
											</tr>
										<?php } ?>
										
										
										<?php if(count($member_success_exam_data) == 0) 
										{ ?>
											<tr><td><strong>Amount </strong></td><td><?php $amount = 0; if(count($exam_data) > 0) { $amount = $exam_data[0]['amount']; echo $amount; } ?></td></tr>
										
											<?php /* <tr><td><strong>Amount </strong></td><td><?php echo $disp_amount; ?></td></tr> */ ?>
										
											<?php if($amount > 0) { ?>
												<tr>
													<td colspan="2" class="text-center">
														<button type="submit" class="btn btn-info btn-flat" name="JaiibReschedule_121">Pay Now</button>
													</td>
												</tr>
										<?php } 
										}	
										else
										{	?>
											<tr><td><strong>Amount </strong></td><td><?php echo $member_success_exam_data[0]['amount']; ?></td></tr>
											<tr><td><strong>Payment Date </strong></td><td><?php echo $member_success_exam_data[0]['date']; ?></td></tr>
											<tr><td><strong>Transaction No. </strong></td><td><?php echo $member_success_exam_data[0]['transaction_no']; ?></td></tr>
							<?php } ?>
									</tbody>
								</table>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>