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
					
					<div class="hide d-none"><?php /* echo @$paid_count_qry; */ ?></div>
					
					<div class="">
						<div class="row">
							<div class="col-lg-12">
								<h4 class="title_common">Member Exam Count</h4>
								<form id="myForm" name="myForm" method="post" action="" enctype="multipart/form-data" role="form">
									<input type="hidden" id="security_token" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
									
									<div class="row">
										<?php if(set_value('exam_code')) { $exam_code = set_value('exam_code'); }  ?>
										<div class="col-lg-6">
											<div class="form-group">
												<label for="exam_code">Exam Name <em class="red">*</em></label>
												<select name="exam_code[]" id="exam_code" class="form-control chosen-select" onchange="get_exam_period()" multiple data-placeholder="Select Exam Name">
													<?php 
														if(count($exam_data) > 0)
														{
															foreach($exam_data as $row)
															{ ?>
															<option <?php if(in_array($row['exam_code'], $exam_code)) { echo 'selected';} ?> value="<?php echo $row['exam_code']; ?>"><?php echo $row['description'];?></option>	
															<?php }
														}
													?>
												</select>
												<?php if(form_error('exam_code')!=""){ ?><label class="error"><?php echo form_error('exam_code'); ?></label><?php } ?>
											</div>
										</div>
										
										<?php if(set_value('exam_period')) { $exam_period = set_value('exam_period'); }  ?>
										<div class="col-lg-6">								
											<div class="form-group">
												<label for="exam_period">Exam Period <em class="red">*</em></label>
												<div id="exam_period_outer">
													<select name="exam_period[]" id="exam_period" class="form-control chosen-select" multiple data-placeholder="Select Exam Period">
														<?php /*
															if(count($exam_period_data) > 0)
															{
																foreach($exam_period_data as $row)
																{ ?>
																<option <?php if($row['exam_period']== $exam_period){ echo 'selected'; }?> value="<?php echo $row['exam_period']; ?>"><?php echo $row['exam_period'];?></option>	
																<?php }
															}*/
														?>
													</select>
												</div>
												<p style="margin: 3px 0 0 1px;line-height: 15px;" id="exam_period_msg"><a href="javascript:void(0)" onclick="open_ExamCodeModal()"><strong>Click Here</strong></a> to add new Exam Period</p>
												<?php if(form_error('exam_period')!=""){ ?><label class="error"><?php echo form_error('exam_period'); ?></label><?php } ?>
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
										<div class="col-lg-12">
											<div class="form-check" style="margin:0px 0 8px 0px">
												<input class="form-check-input" type="checkbox" value="1" id="elearning_check" name="elearning_check" <?php if(set_value('elearning_check') == 1) { echo "checked"; } else if($elearning_flag == 1) { echo "checked"; } ?>>
												<label class="form-check-label" for="elearning_check" style="vertical-align:top">E-Learning</label>
											</div>
										</div>
										
										<?php $this->load->view('webmanager/includes/common_count'); ?>
										
										<div class="col-lg-12">
											<div class="form-group" style="margin: 0 auto;">
												<input type="submit" id="download_csv" name="download_csv" class="btn btn-primary" value="Download CSV">
												<input type="submit" id="submit" name="submit" class="btn btn-info" value="Submit"> 
												<a href="<?php echo site_url('webmanager/examdashboard'); ?>" class="btn btn-info">Clear</a>
											</div>								
										</div>								
									</div>								
								</form>
							</div>
						</div>
					</div>
				</section>
			</div>
			
			<div class="modal fade" id="ExamCodeModal" tabindex="-1" role="dialog" aria-labelledby="ExamCodeModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="ExamCodeModalLabel">Add Exam Period</h4>
						</div>
						
						<div class="modal-body">
							<div class="form-group">
								<label for="new_exam_period">Exam Period</label>
								<input type="text" class="form-control allowd_only_numbers" id="new_exam_period" name="new_exam_period" placeholder="Exam Period" required onkeyup="check_exam_period_msg()" autocomplete="off">
								<label id="exam_period_error" class="error"></label>
							</div>
						</div>
						
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" onclick="add_new_exam_period()">Submit</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
			
			<?php $this->load->view('webmanager/includes/footer');?>
			
			<script>
				$('#ExamCodeModal').on('shown.bs.modal', function () { $('#new_exam_period').focus(); })

					function get_exam_period() {
					var exam_code = $("#exam_code").val();
					
					if(exam_code != null && exam_code.length > 0)
					{
						var exam_code_str = exam_code.join(",");
						
						var selected_period = '';
						<?php if(set_value("exam_period") != "") { ?>	
							selected_period = '<?php echo implode(",",set_value("exam_period")); ?>';
						<?php } else { ?>
							var sel_period = $("#exam_period").val();
							if(sel_period != null && sel_period.length > 0) { selected_period = sel_period.join(","); }
						<?php } ?>
						
						if(exam_code_str != "")
						{
							$("#exam_period_outer").html();
							var security_token = $("#security_token").val();
							parameters = { "exam_code":exam_code_str, "selected_period":selected_period, "security_token":security_token }
							$.ajax(
							{
								type: "POST",
								url: "<?php echo site_url('webmanager/examdashboard/get_exam_period_dropdown') ?>",
								data: parameters,
								cache: false,
								dataType: 'JSON',
								success:function(data)
								{
									if(data.flag == "success")
									{	
										$("#exam_period_outer").html(data.drop_down);
										$('.chosen-select').chosen({width: "100%"});
									}
									else if(data.flag == "error")
									{ 
										if(data.message != "")
										{
											$("#exam_period_error").html(data.message);
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
					else
					{
						$("#exam_period_outer").html('<select name="exam_period[]" id="exam_period" class="form-control chosen-select" multiple data-placeholder="Select Exam Period"></select>');	
						$('.chosen-select').chosen({width: "100%"});
					}
					
					/* var exam_code = $("#exam_code").val();
					var selected_period = '<?php echo set_value("exam_period"); ?>';
					if (exam_code > 0 && exam_code !='') 
					{						
						$("#exam_period_outer").html();
						var security_token = $("#security_token").val();
						parameters = { "exam_code":exam_code,"selected_period":selected_period, "security_token":security_token }
						$.ajax(
						{
							type: "POST",
							url: "<?php echo site_url('webmanager/examdashboard/get_exam_period_dropdown') ?>",
							data: parameters,
							cache: false,
							dataType: 'JSON',
							success:function(data)
							{
								if(data.flag == "success")
								{	
									$("#exam_period_outer").html(data.drop_down);
								}
								else if(data.flag == "error")
								{ 
									if(data.message != "")
									{
										$("#exam_period_error").html(data.message);
									}
									else
									{
										location.reload(); 
									}
								} 
							}
						});
					} */
				}

				<?php if(isset($_POST) && count($_POST) > 0) { ?> get_exam_period(); <?php } ?>				
				
				function open_ExamCodeModal()
				{
					$("#exam_code-error").remove();
					$("#exam_period-error").remove();
					$("#new_exam_period").val("");
					$("#exam_period_error").html("");
					$("#ExamCodeModal").modal("show");
					$("#custom_msg_outer").html("");					
				}
				
				function check_exam_period_msg()
				{
					var new_exam_period = $("#new_exam_period").val();
					if(new_exam_period != "") { $("#exam_period_error").html(""); }
					$("#new_exam_period").focus();
				}
				
				function add_new_exam_period()
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
								else if(data.flag == "error")
								{ 
									if(data.message != "")
									{
										$("#exam_period_error").html(data.message);
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
							'exam_code[]': { required : true },
							'exam_period[]': { required : true }
						},
						messages:
						{
							'exam_code[]': { required : "Please select the Exam Code" },
							'exam_period[]': { required : "Please select the Exam Period" }
						},
						errorPlacement: function(error, element) // For replace error 
						{
							if (element.attr("name") == "exam_code[]") 
							{
								error.insertAfter("#exam_code_chosen");
							}
							else if (element.attr("name") == "exam_period[]") 
							{
								error.insertAfter("#exam_period_msg");
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