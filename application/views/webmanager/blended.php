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
								<h4 class="title_common">Blended Count</h4>
								<form id="myForm" name="myForm" method="post" action="" enctype="multipart/form-data" role="form">
									<input type="hidden" id="security_token" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
									
									<div class="row">										
										<?php if(set_value('batch_code')) { $batch_code = set_value('batch_code'); }  ?>
										<div class="col-lg-12">								
											<div class="form-group">
												<label for="batch_code">Batch Code <em class="red">*</em></label>
												<div id="batch_code_outer">
													<select name="batch_code" id="batch_code" class="form-control chosen-select">
														<option value="">Select Batch Code</option>
														<?php 
															if(count($batch_code_data) > 0)
															{
																foreach($batch_code_data as $row)
																{ ?>
																<option <?php if($row['batch_code']== $batch_code){ echo 'selected'; }?> value="<?php echo $row['batch_code']; ?>"><?php echo $row['batch_code'];?></option>	
																<?php }
															}
														?>
													</select>
												</div>
												<p style="margin: 3px 0 0 1px;line-height: 15px;" id="batch_code_msg"><a href="javascript:void(0)" onclick="open_BatchCodeModal()"><strong>Click Here</strong></a> to add new Batch Code</p>
												<?php if(form_error('batch')!=""){ ?><label class="error"><?php echo form_error('batch_code'); ?></label><?php } ?>
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
												<a href="<?php echo site_url('webmanager/blended'); ?>" class="btn btn-info">Clear</a>
											</div>								
										</div>								
									</div>								
								</form>
							</div>
						</div>
					</div>
				</section>
			</div>
			
			<div class="modal fade" id="BatchCodeModal" tabindex="-1" role="dialog" aria-labelledby="BatchCodeModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="BatchCodeModalLabel">Add Batch Code</h4>
						</div>
						
						<div class="modal-body">
							<div class="form-group">
								<label for="new_batch_code">Batch Code</label>
								<input type="text" class="form-control" id="new_batch_code" name="new_batch_code" placeholder="Batch Code" required onkeyup="check_batch_code_msg()" autocomplete="off">
								<label id="batch_code_error" class="error"></label>
							</div>
						</div>
						
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" onclick="add_new_batch_code()">Submit</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
			
			<?php $this->load->view('webmanager/includes/footer');?>
			
			<script>
				$('#BatchCodeModal').on('shown.bs.modal', function () { $('#new_batch_code').focus(); })
				
				function open_BatchCodeModal()
				{
					$("#batch_code-error").remove();					
					$("#new_batch_code").val("");
					$("#batch_code_error").html("");
					$("#BatchCodeModal").modal("show");
					$("#custom_msg_outer").html("");					
				}
				
				function check_batch_code_msg()
				{
					var new_batch_code = $("#new_batch_code").val();
					if(new_batch_code != "") { $("#batch_code_error").html(""); }
					$("#new_batch_code").focus();
				}
				
				function add_new_batch_code()
				{
					var new_batch_code = $("#new_batch_code").val();
					$("#new_batch_code").focus();
					if(new_batch_code == "")
					{
						$("#batch_code_error").html("Please enter the batch code");
					}
					else
					{
						$("#batch_code_error").html("");
						
						var security_token = $("#security_token").val();
						parameters = { "new_batch_code":new_batch_code, "sel_batch_code" : $( "#batch_code" ).val(), "security_token":security_token }
						$.ajax(
						{
							type: "POST",
							url: "<?php echo site_url('webmanager/blended/add_new_batch_code_ajax') ?>",
							data: parameters,
							cache: false,
							dataType: 'JSON',
							success:function(data)
							{
								if(data.flag == "success")
								{	
									$("#batch_code_error").html("");
									$("#batch_code_outer").html(data.batch_code_sel);
									$('.chosen-select').chosen({width: "100%"});
									
									$("#custom_msg_outer").html(data.message);
									$("#BatchCodeModal").modal("hide");
								}
								else if(data.flag == "error")
								{ 
									if(data.message != "")
									{
										$("#batch_code_error").html(data.message);
									}
									else
									{
										location.reload(); 
									}
								} 
							}
						});
					}
				}				
				
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
							batch_code: { required : true },
						},
						messages:
						{
							batch_code: { required : "Please select the Batch Code" }
						},
						errorPlacement: function(error, element) // For replace error 
						{
							if (element.attr("name") == "batch_code") 
							{
								error.insertAfter("#batch_code_msg");
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