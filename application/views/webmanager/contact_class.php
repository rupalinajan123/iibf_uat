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
								<h4 class="title_common">Contact Classes Training Count</h4>
								<form id="myForm" name="myForm" method="post" action="" enctype="multipart/form-data" role="form">
									<input type="hidden" id="security_token" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
									
									<div class="row">
										<?php if(set_value('course_name')) { $course_name = set_value('course_name'); }  ?>
										<div class="col-lg-6">
											<div class="form-group">
												<label for="course_name">Course Name <em class="red">*</em></label>
												<select name="course_name" id="course_name" class="form-control chosen-select">
													<option value="">Select Course Name</option>
													<?php 
														if(count($course_data) > 0)
														{
															foreach($course_data as $row)
															{ ?>
															<option <?php if($course_name == $row['course_code']){ echo 'selected';}?> value="<?php echo $row['course_code']; ?>"><?php echo $row['course_name'];?></option>	
															<?php }
														}
													?>
												</select>
												<?php if(form_error('course_name')!=""){ ?><label class="error"><?php echo form_error('course_name'); ?></label><?php } ?>
											</div>
										</div>
										
										<?php if(set_value('course_period')) { $course_period = set_value('course_period'); }  ?>
										<div class="col-lg-6">								
											<div class="form-group">
												<label for="course_period">Course Period <em class="red">*</em></label>
												<div id="course_period_outer">
													<select name="course_period" id="course_period" class="form-control chosen-select">
														<option value="">Select Course Period</option>
														<?php 
															if(count($course_period_data) > 0)
															{
																foreach($course_period_data as $row)
																{ ?>
																<option <?php if($row['course_period']== $course_period){ echo 'selected'; }?> value="<?php echo $row['course_period']; ?>"><?php echo $row['course_period'];?></option>	
																<?php }
															}
														?>
													</select>
												</div>
												<p style="margin: 3px 0 0 1px;line-height: 15px;" id="course_period_msg"><a href="javascript:void(0)" onclick="open_CoursePeriodModal()"><strong>Click Here</strong></a> to add new Course Period</p>
												<?php if(form_error('course_period')!=""){ ?><label class="error"><?php echo form_error('course_period'); ?></label><?php } ?>
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
										<?php if($count >= '0') { ?>
											<div class="col-lg-12">
												<div class="form-group">
													<div id="count" style="background: #fff; padding: 5px; text-align: center; font-weight: 600; border: 1px solid #ccc; font-size: 15px; height:40px;width: 150px; white-space: nowrap;"><?php echo "Count : ".$count;?></div>
												</div>
											</div>
										<?php } ?>
										
										<div class="col-lg-12">
											<div class="form-group" style="margin: 0 auto;">
												<input type="submit" id="submit" name="submit" class="btn btn-info" value="Submit"> 
												<a href="<?php echo site_url('webmanager/contact_class'); ?>" class="btn btn-info">Clear</a>
											</div>								
										</div>								
									</div>							
								</form>
							</div>
						</div>
					</div>
				</section>
			</div>			
			
			<div class="modal fade" id="CoursePeriodModal" tabindex="-1" role="dialog" aria-labelledby="CoursePeriodModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="CoursePeriodModalLabel">Add Course Period</h4>
						</div>
						
						<div class="modal-body">
							<div class="form-group">
								<label for="new_course_period">Course Period</label>
								<input type="text" class="form-control allowd_only_numbers" id="new_course_period" name="new_course_period" placeholder="Course Period" required onkeyup="check_course_period_msg()" autocomplete="off">
								<label id="course_period_error" class="error"></label>
							</div>
						</div>
						
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" onclick="add_new_course_period()">Submit</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
			
			<?php $this->load->view('webmanager/includes/footer');?>
						
			<script>
				$('#CoursePeriodModal').on('shown.bs.modal', function () { $('#new_course_period').focus(); })
				
				function open_CoursePeriodModal()
				{
					$("#course_name-error").remove();
					$("#course_period-error").remove();
					$("#new_course_period").val("");
					$("#course_period_error").html("");
					$("#CoursePeriodModal").modal("show");
					$("#custom_msg_outer").html("");					
				}
				
				function check_course_period_msg()
				{
					var new_course_period = $("#new_course_period").val();
					if(new_course_period != "") { $("#course_period_error").html(""); }
					$("#new_course_period").focus();
				}
				
				function add_new_course_period()
				{
					var new_course_period = $("#new_course_period").val();
					$("#new_course_period").focus();
					if(new_course_period == "")
					{
						$("#course_period_error").html("Please enter the course period");
					}
					else
					{
						$("#course_period_error").html("");
						
						var security_token = $("#security_token").val();
						parameters = { "new_course_period":new_course_period, "sel_course_prd" : $( "#course_period" ).val(), "security_token":security_token }
						$.ajax(
						{
							type: "POST",
							url: "<?php echo site_url('webmanager/contact_class/add_new_course_period_ajax') ?>",
							data: parameters,
							cache: false,
							dataType: 'JSON',
							success:function(data)
							{
								if(data.flag == "success")
								{	
									$("#course_period_error").html("");
									$("#course_period_outer").html(data.course_period_sel);
									$('.chosen-select').chosen({width: "100%"});
									
									$("#custom_msg_outer").html(data.message);
									$("#CoursePeriodModal").modal("hide");
								}
								else if(data.flag == "error")
								{ 
									if(data.message != "")
									{
										$("#course_period_error").html(data.message);
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
							course_name: { required : true },
							course_period: { required : true }
						},
						messages:
						{
							course_name: { required : "Please select the Course Name" },
							course_period: { required : "Please select the Course Period" }
						},
						errorPlacement: function(error, element) // For replace error 
						{
							if (element.attr("name") == "course_name") 
							{
								error.insertAfter("#course_name_chosen");
							}
							else if (element.attr("name") == "course_period") 
							{
								error.insertAfter("#course_period_msg");
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