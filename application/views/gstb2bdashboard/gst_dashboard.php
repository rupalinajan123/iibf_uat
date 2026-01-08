<!DOCTYPE html>
<html>
	<head>
    <?php $this->load->view('gstb2bdashboard/includes/header');?>
	</head>
	
	<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
			<?php //$this->load->view('gstb2bdashboard/includes/topbar'); ?>
			<?php //$this->load->view('gstb2bdashboard/includes/sidebar'); ?>
			
			<div class="content-wrapper" style="margin-left:0">
				<section class="content">
					<div id="custom_msg_outer"></div>
					
					<div class="">
						<div class="row">
							<div class="col-lg-12">
								<h4 class="title_common">B2B Invoice Count</h4>
								<form id="myForm" name="myForm" method="post" action="" enctype="multipart/form-data" role="form">
									<input type="hidden" id="security_token" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
									
									<div class="row">									
										<div class="col-lg-6">								
											<div class="form-group">
												<label for="from_date">Invoice Generated From Date<em class="red">*</em></label>
												<input type="text" name="from_date" id="from_date" class="form-control" value="<?php if(set_value('from_date')) { echo set_value('from_date'); } else { echo $from_date; } ?>" placeholder="From Date" readonly = "readonly">
											</div>
										</div>									
										
										<div class="col-lg-6">								
											<div class="form-group">
												<label for="to_date">Invoice Generated To Date<em class="red">*</em></label>
												<input type="text" name="to_date" id="to_date" class="form-control" value="<?php if(set_value('to_date')) { echo set_value('to_date'); } else { echo $to_date; } ?>" placeholder="To Date" readonly = "readonly">
												<div class="col-sm-12" align="left"> <span style="color:#F00; font-size:14px;">Note: Current and day before date is not available for selection as its data is not available in PG database to process.</span> </div>
											</div>
										</div>
									</div>
									
									<div class="row">
										<?php if(set_value('exam_code')) { $exam_code = set_value('exam_code'); }  ?>
										<div class="col-lg-6">
											<div class="form-group">
												<label for="exam_code">Exam Name <em class="red">*</em></label>
												<select name="exam_code" id="exam_code" class="form-control chosen-select">
													<option value="">Select Exam Name</option>
													<option value="01" <?php echo set_select('exam_code', '01'); ?> >Bulk Exam</option>
													<option value="02" <?php echo set_select('exam_code', '02'); ?> >DRA Exam</option>
													<option value="03" <?php echo set_select('exam_code', '03'); ?> >DRA Center Registration</option>
													<option value="04" <?php echo set_select('exam_code', '04'); ?> >DRA Agency Renewal</option>
													
												</select>
												<?php if(form_error('exam_code')!=""){ ?><label class="error"><?php echo form_error('exam_code'); ?></label><?php } ?>
											</div>
										</div>
										
										
									</div>								
									<div class="row">									
										<div class="col-lg-6">								
											<div class="form-group">
												<label for="pending_approval" id="pending_approval"> <?php if($exam_code == '02') { ?> Proforma Invoice pending for payment <?php } else { ?>  Invoices Pending for Approval <?php } ?></label>
												<input type="text" class="form-control" name="pending_approval" id="pending_approval" value="<?php if($exam_code == '01'){echo $pending_bulk_count;}else if($exam_code == '02'){echo $pending_dra_count;}else if($exam_code == '03'){echo $pending_dra_reg_count;}else if($exam_code == '04'){echo $pending_agn_renewal_count;}?>" readonly="readonly"/>
											</div>
										</div>
										</div>
										<div class="row">
										
										<div class="col-lg-6">								
											<div class="form-group">
												<label for="approved" id="payment_approval"> <?php if($exam_code == '02') { ?> Online Paid Invoice Count <?php } else { ?> Approved Invoices Count <?php } ?></label>
												<input type="text" class="form-control" name="pending_approval" id="pending_approval" value="<?php if($exam_code == '01'){echo $approved_bulk_count;}else if($exam_code == '02'){echo $approved_dra_count;}else if($exam_code == '03'){echo $approved_dra_reg_count;}else if($exam_code == '04'){echo $approved_agn_renewal_count;}?>" readonly="readonly"/>
											</div>
										</div>
									</div>
									
									<div class="row">
											<div class="col-lg-12">
											<div class="form-group" style="margin: 0 auto;">
												<input type="submit" id="submit" name="submit" class="btn btn-info" value="Submit"> 
												<a href="<?php echo site_url('admin/GstB2BDashboard'); ?>" class="btn btn-info">Clear</a>
											</div>								
										</div>								
									</div>								
								</form>
							</div>
						</div>
					</div>
				
				<div class="">
						<div class="row">
							<div class="col-lg-12">
								<h4 class="title_common">Credit Note Count</h4>
								<form id="myForm" name="myForm" method="post" action="" enctype="multipart/form-data" role="form">
									<input type="hidden" id="security_token" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
									
									<div class="row">									
										<div class="col-lg-6">								
											<div class="form-group">
												<label for="from_date_cn">Credit Note Generated From Date<em class="red">*</em></label>
												<input type="text" name="from_date_cn" id="from_date_cn" class="form-control" value="<?php if(set_value('from_date_cn')) { echo set_value('from_date_cn'); } else { echo $from_date_cn; } ?>" placeholder="From Date" readonly = "readonly">
											</div>
										</div>									
										
										<div class="col-lg-6">								
											<div class="form-group">
												<label for="to_date_cn">Credit Note Generated To Date<em class="red">*</em></label>
												<input type="text" name="to_date_cn" id="to_date_cn" class="form-control" value="<?php if(set_value('to_date_cn')) { echo set_value('to_date_cn'); } else { echo $to_date_cn; } ?>" placeholder="To Date" readonly = "readonly">
												
											</div>
										</div>
									</div>
									
										
										<div class="row">
										
										<div class="col-lg-6">								
											<div class="form-group">
												<label for="approved">Credit Note Count</label>
												<input type="text" class="form-control" name="pending_approval" id="pending_approval" value="<?php echo $credit_note_counts; ?>" readonly="readonly"/>
											</div>
										</div>
									</div>
									
									<div class="row">
											<div class="col-lg-12">
											<div class="form-group" style="margin: 0 auto;">
												<input type="submit" id="submit" name="submit" class="btn btn-info" value="Submit"> 
												<a href="<?php echo site_url('admin/GstB2BDashboard'); ?>" class="btn btn-info">Clear</a>
											</div>								
										</div>								
									</div>								
								</form>
							</div>
						</div>
					</div>
				
				</section>
			</div>
			
			
			
			<?php $this->load->view('gstb2bdashboard/includes/footer');?>
			
			<script>
				$('#ExamCodeModal').on('shown.bs.modal', function () { $('#new_exam_period').focus(); })
				
				$('#exam_code').on('change',function()
				{  
					var exCode = $('#exam_code').val();
					if (exCode == '02') {
						$('#pending_approval').text('Proforma Invoice pending for payment');
						$('#payment_approval').text('Online Paid Invoice Count');
					} else {
						$('#pending_approval').text('Invoices Pending for Approval');
						$('#payment_approval').text('Approved Invoice Count');
					}
				})

				function open_ExamCodeModal()
				{
					$("#exam_code-error").remove();
					//$("#exam_period-error").remove();
					//$("#new_exam_period").val("");
					//$("#exam_period_error").html("");
					$("#ExamCodeModal").modal("show");
					$("#custom_msg_outer").html("");					
				}
				
				/* function check_exam_period_msg()
				{
					var new_exam_period = $("#new_exam_period").val();
					if(new_exam_period != "") { $("#exam_period_error").html(""); }
					$("#new_exam_period").focus();
				}
				 */
				/* function add_new_exam_period()
				{
					var new_exam_period = $("#new_exam_period").val();
					$("#new_exam_period").focus();
					if(new_exam_period == "")
					{
						$("#exam_period_error").html("Please enter the exam period");
					}
					else
					{
						$("#exam_period_error").html("");
						
						var security_token = $("#security_token").val();
						parameters = { "new_exam_period":new_exam_period, "sel_exam_prd" : $( "#exam_period" ).val(), "security_token":security_token }
						$.ajax(
						{
							type: "POST",
							url: "<?php echo site_url('webmanager/Examdashboard/add_new_exam_period_ajax') ?>",
							data: parameters,
							cache: false,
							dataType: 'JSON',
							success:function(data)
							{
								if(data.flag == "success")
								{	
									$("#exam_period_error").html("");
									$("#exam_period_outer").html(data.exam_period_sel);
									$('.chosen-select').chosen({width: "100%"});
									
									$("#custom_msg_outer").html(data.message);
									$("#ExamCodeModal").modal("hide");
								}
								
							}
						});
					}
				}	 */			
				
				//START : FORM VALIDATION CODE 
				$(document ).ready( function() 
				{
				
					$.validator.addMethod("nowhitespace", function(value, element) { if($.trim(value).length == 0) { return false; } else { return true; } });
					
					$.validator.setDefaults({ ignore: ":hidden:not(.chosen-select)" })// For chosen validation
					
					$("#myForm").validate( 
					{
						ignore: [], // For Ckeditor
						debug: false, // For Ckeditor
						rules:
						{
							exam_code: { required : true },
							from_date: { required : true },
							to_date: { required : true },
							from_date_cn: { required : true },
							to_date_cn: { required : true }
						},
						messages:
						{
							exam_code: { required : "Please select the Exam Code" },
							from_date: { required : "Please select the From Date" },
							to_date: { required : "Please select the To Date" },
							from_date_cn: { required : "Please select the From Date" },
							to_date_cn: { required : "Please select the To Date" }
						},
						errorPlacement: function(error, element) // For replace error 
						{
							if (element.attr("name") == "exam_code") 
							{
								error.insertAfter("#exam_code_chosen");
							}
							else 
							{
								error.insertAfter(element);
							}
						}
					});
				});
				//END : FORM VALIDATION CODE 
			</script>
			
			<script>$( document ).ready( function () { $('#page_loader').delay(0).fadeOut('slow'); });</script>
		</div>
	</body>
</html>