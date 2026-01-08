<?php $this->load->view('admin/kyc/includes/header');?>
<?php $this->load->view('admin/kyc/includes/approver_sidebar');?>
<link href="<?php echo base_url('assets/css/popup.css')?>" rel="stylesheet">	
<link href="<?php echo base_url('assets/dist/css/lightgallery.css')?>" rel="stylesheet">
<script src="<?php echo base_url('assets/dist/js/jquery.min.js')?>"></script>
<style>.min-height{ min-height:150px;}</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Professional Banker KYC Verification </h1>
	</section><br />
	
	<div class="col-md-12">
    <?php if($this->session->flashdata('error')!=''){?>
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
				<?php echo $this->session->flashdata('error'); ?>
			</div>
			<?php } 
			
			if($this->session->flashdata('success')!=''){ ?>
			<div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
        <?php echo $this->session->flashdata('success'); ?>
			</div>
		<?php } ?>
    
		<?php if($error!=''){?>
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
				<?php echo $error; ?>
			</div>
			<?php } 
			
			if($success!=''){ ?>
			<div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
        <?php echo $success; ?>
			</div>
		<?php } ?>
	</div>
	
	<section class="content">
		<div class="row">
			<div class="col-xs-12">				
				<div class="box min-height">
					<div class="box-header">
						<h3 class="box-title" style="padding:8px 0px 12px 0">Member KYC Details</h3>
					</div>
					
					<div class="box-body">
            <form class="form-horizontal" name="checkmember" id="checkmember" action="" method="post">
							<input type="hidden" name="regnumber" id="regnumber" value="<?php echo $exp_cert_data[0]['regnumber']; ?>">
							<input type="hidden" name="pb_reg_id" id="pb_reg_id" value="<?php echo base64_encode($exp_cert_data[0]['pb_reg_id']); ?>">
							
							<table id="listitems" class="table table-bordered table-striped dataTables-example" style="max-width:700px; margin:0 auto 20px;">
                <tbody>
									<tr><td style="width:200px;"><b>Membership/Registration No</b> </td><td><?php echo $exp_cert_data[0]['regnumber']; ?></td></tr>
									<tr><td><b>Candidate Name</b> </td><td><?php echo $exp_cert_data[0]['DispName']; ?></td></tr>
									<tr><td><b>Exam Code</b> </td><td><?php echo $exp_cert_data[0]['exam_code']; ?></td></tr>
									<tr><td><b>Exam Name</b> </td><td><?php echo $exp_cert_data[0]['description']; ?></td></tr>
									<tr><td><b>Amount</b> </td><td><?php echo $exp_cert_data[0]['amount']; ?></td></tr>
									<tr><td><b>Experience Certificate</b> </td><td><a href="<?php echo site_url('admin/kyc/Approver/download_exp_cert/'.base64_encode($exp_cert_data[0]['pb_reg_id'])); ?>" class="btn btn-sm btn-primary" style="padding:1px 5px 2px" target="_blank">Download</a></td></tr>
									<tr><td><b>Created Date</b> </td><td><?php echo $exp_cert_data[0]['created_on']; ?></td></tr>
								</tbody>
							</table>
							
							<div class="text-center">
								<input type="submit" class="btn btn-primary" name="btnSubmitkyc" id="btnSubmitkyc" value="Approve" onclick="return confirm('Are you confirm to approve the kyc?')">
								<input type="button" class="btn btn-danger" name="btnSubmitRecmd" id="btnSubmitRecmd" value="Reject" onclick="reject_remark()" >
								<a href="<?php echo base_url()?>admin/kyc/Approver/professional_banker_kyc" class="btn btn-info">Back</a>
							</div>
						</form> 
					</div>
				</div>
			</div>
			
			<?php //echo '<pre>'; print_r($rejection_logs); echo '</pre>'; //exit; 
				if(count($rejection_logs) > 0)
				{	?>	
				<div class="col-xs-12">				
					<div class="box min-height">
						<div class="box-header">
							<h3 class="box-title" style="padding:8px 0px 12px 0">Rejected Document History</h3>
						</div>
						
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
													<a href="<?php echo site_url('admin/kyc/Approver/download_exp_cert_log/'.base64_encode($res['log_id'])); ?>" class="btn btn-sm btn-primary" style="padding:1px 5px 2px" target="_blank">Download</a>
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
			<?php	} ?>
		</div>
	</section>	
</div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Reject Remark</h4>
			</div>
			
			<form id="professional_bankers_reject" method="post">
				<input type="hidden" name="regnumber_modal" id="regnumber_modal" value="<?php echo $exp_cert_data[0]['regnumber']; ?>">
				<input type="hidden" name="pb_reg_id_modal" id="pb_reg_id_modal" value="<?php echo base64_encode($exp_cert_data[0]['pb_reg_id']); ?>">
				
				<div class="modal-body">
					<div class="form-group">
						<label for="remark">Reject Remark</label>
						<textarea class="form-control" id="remark" name="remark" required placeholder="Reject Remark"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" name="btnSubmitkyc" value="Reject" class="btn btn-info">Submit</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script src="<?php echo base_url()?>js/jquery.validate.min.js"></script>
<?php $this->load->view('apply_elearning/common_validation_all'); ?>

<script type="text/javascript">
	function reject_remark()
	{
		$("#remark").val('');
		$("#myModal").modal('show');
	}
	
	$(document ).ready( function() 
	{
		$("#professional_bankers_reject").validate( 
		{
			rules:
			{
				remark: { required : true }	
			},
			messages:
			{
				remark: { required : "Please enter the remark", }
			},
			submitHandler: function(form) 
			{
				return confirm('Are you confirm to reject the kyc?');
			}
		});
	});
</script>

<?php $this->load->view('admin/kyc/includes/footer');?>	