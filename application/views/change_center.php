<meta http-equiv="refresh">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
     
    
    <form class="form-horizontal" name="" id=""  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>home/change_center/">
    
			<input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('regid');?>"> 
				<section class="content">
					<div class="row">
					
						<div class="col-md-12">

							<!-- Horizontal Form -->
							<div class="box box-info" style="display:none;">
								<div class="box-header with-border">
									<h3 class="box-title">Basic Details</h3>
								</div>
							
									<div class="box-body">
								
										<div class="form-group">
											<label for="roleid" class="col-sm-3 control-label">Membership No</label>
												<div class="col-sm-1">
												<?php echo $user_info[0]['regnumber'];
															$fee_amount=$grp_code='';?>
													
													<input type="hidden" name="reg_no" id="reg_no" value="<?php echo $user_info[0]['regnumber'];?>">
																
																	
												</div>
										</div>
										
										
										<div class="form-group">
											<label for="roleid" class="col-sm-3 control-label">First Name </label>
													<div class="col-sm-3">
															<?php echo $user_info[0]['firstname'];?>
															
													</div>
													
										</div>
										
										<div class="form-group">
											<label for="roleid" class="col-sm-3 control-label">Middle Name</label>
											<div class="col-sm-5">
												<?php echo $user_info[0]['middlename'];?>
										
											</div>
										</div>
										
										<div class="form-group">
											<label for="roleid" class="col-sm-3 control-label">Last Name</label>
											<div class="col-sm-5">
												<?php echo $user_info[0]['lastname'];?>
													
											</div>
										</div>
										
										
										<div class="form-group">
											<label for="roleid" class="col-sm-3 control-label">Mobile <span style="color:#F00">*</span></label>
											<div class="col-sm-5">
											<?php echo $user_info[0]['mobile'];?>
													
											</div>
										</div>
										
										
										<div class="form-group">
											<label for="roleid" class="col-sm-3 control-label">Email <span style="color:#F00">*</span></label>
											<div class="col-sm-5">
												<?php echo $user_info[0]['email'];?>
											</div>
										</div>
								</div>
										
									</div> <!-- Basic Details box closed-->
										<div class="box box-info">
												<div class="box-header with-border">
													<h3 class="box-title">Center Change Request</h3>
												</div>
								
						
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
													?> 
													<div class="form-group">
														<label for="roleid" class="col-sm-3 control-label">Exam Name</label>
														<div class="col-sm-4">
															<select name="exam_code"  id="exam_code" class="form-control exam_code" required onchange="examchangefunc(this.value);">
															<option value="-">Select</option>
															<?php if(count($exam_list) > 0)
																{
																	
																	foreach($exam_list as $exam)
																	{?>
																			<option curr_center="<?php echo $exam['center_name']?>" exam_period="<?php echo $exam['exam_period']?>" value="<?php echo $exam['exam_code']?>" class=<?php echo $exam['exam_code'];?>><?php echo $exam['description']?></option>
																	<?php }
																}?>
															</select>
																<input type="hidden" class="exam_period" name="exam_period" value="">
																<span class="error"><?php //echo form_error('idproofphoto');?></span>
														</div>
													</div>
													
													<div class="form-group">
														<label for="roleid" class="col-sm-3 control-label">Current Centre <span style="color:#F00">*</span></label>
														<div class="col-sm-4">
															<input type="text" readonly name="current_center" class="curr_center form-control">
															
															</div>
													</div>
													
													<div class="form-group">
														<label for="roleid" class="col-sm-3 control-label">New Centre <span style="color:#F00">*</span></label>
														<div class="col-sm-4">
															<input type="hidden" name="center_name" class="center_name">
															<select onchange="centerchanged(this.value);" name="center_code" id="" class="center_code form-control" required >
															<option value="-">Select</option>
															
															</select>
															</div>
													</div>
												
																
													<div class="form-group">
															<label for="roleid" class="col-sm-3 control-label">Transfer order letter <span style="color:#F00">**</span></label>
															<div class="col-sm-5">
																		<input  type="file" class="form-control" name="transfer_letter" id="transfer_letter"  autocomplete="off" onchange="validateFile(event, 'error_transfer_letter_size', 'image_upload_transfer_letter_preview', '50kb')">
																	<input type="hidden" id="hiddentransfer_letter" name="hiddentransfer_letter">
																	<span class="note">Please Upload only .jpg, .jpeg  Files upto 50KB</span></br>
																	<span class="note-error" id="error_transfer_letter_size" class="note-error"></span>
																<br>
																<span class="transfer_letter_text" style="display:none;"></span>
																	<span class="error"><?php //echo form_error('scannedphoto');?></span>
																</div>
																<img class="mem_reg_img" id="image_upload_transfer_letter_preview" height="100" width="100" src="/assets/images/default1.png"/>
																
														</div>
												
													<div class="box-footer">
														<div class="col-sm-12 text-center">
																
															<input type="submit" class="btn btn-info" name="btnPreviewSubmit" id="btnPreviewSubmit" value="Submit" onclick="javascript:return loginusercheckform();">
														</div>
													</div>
												</div>
										</div>

										<div class="box box-info">
												<div class="box-header with-border">
													<h3 class="box-title">Logs</h3>
												</div>
								
						
											<div class="box-body">
													
												<table id="listitems" class="table table-bordered table-striped dataTables-example">
														<thead>

															<tr>
															<th id="srNo">S.No</th>
															<th id="exam_code">Exam Name</th>														
															<th id="qualifying_exam1">New Center</th>
															<th id="qualifying_part1">Status</th>
															<th id="qualifying_part2">Updated On</th>
															
															</tr>

														</thead>
														<tbody class="no-bd-y" id="list">
																<?php
																	$i=1;
																foreach($previous_records as $previous_record) {
																	if($previous_record['status']==2)
																		$status='<span class="btn btn-sm btn-warning">Pending</span>';
																	if($previous_record['status']==1)
																		$status='<span class="btn btn-sm btn-success">Approved</span>';
																	if($previous_record['status']==0)
																		$status='<span class="btn btn-sm btn-danger">Rejected</span>';;
																	
																	?>
																	<tr>
																		<td><?php echo $i++; ?></td>
																		<td><?php echo $previous_record['description']; ?></td>
																		<td><?php echo $previous_record['center_name']; ?></td>
																		<td><?php echo $status; ?></td>
																		<td><?php echo date('Y-m-d',strtotime($previous_record['updated_on'])); ?></td>
																	</tr>
																		<?php
																} ?>
														</tbody>
													</table>
													<div class="box-footer">
														<div class="col-sm-12 text-center">
													
															
														</div>
													</div>
												</div>
										</div>

								</div>     
					
					</div>
				</section> 
  
		</form>
	</div>

<!-- Data Tables -->
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
 
<script>
	function centerchanged(center_code) {
		var center_name = $('select.center_code').find('option[value="'+center_code+'"]').attr('center_name');
		$('.center_name').val(center_name);
	}
	function examchangefunc(exam_code) {
		var exam_period = $('.exam_code').find('option[value="'+exam_code+'"]').attr('exam_period');
		$('.exam_period').val(exam_period);

		var curr_center = $('.exam_code').find('option[value="'+exam_code+'"]').attr('curr_center');
		$('.curr_center').val(curr_center);

		$.ajax({
				url:site_url+'Home/checkPreviousEntries/',
				data: {exam_code:exam_code},
				type:'POST',
				async: false,
				success: function(data) {
					if(data=='true'){
						$('#btnPreviewSubmit').hide();
					}
					else
					$('#btnPreviewSubmit').show();
			}
		});
		$.ajax({
				url:site_url+'Home/getCentersByExamCode/',
				data: {exam_code:exam_code},
				type:'POST',
				async: false,
				success: function(data) {
					if(data){
						$('select.center_code').html(data);
					}
			}
		});
	}
	
</script>