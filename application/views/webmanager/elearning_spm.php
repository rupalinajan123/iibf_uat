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
					<div class="hide d-none"><?php /* echo @$chk_qry; */ ?></div>
					<div class="">
						<div class="row">
							<div class="col-lg-12">
								<h4 class="title_common">Separate E-learning Module Exam Count</h4>
								<form id="myForm" name="myForm" method="post" action="" enctype="multipart/form-data" role="form">
									<input type="hidden" id="security_token" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
									
									<div class="row">									
										<div class="col-lg-6">								
											<div class="form-group">
												<label for="from_date">From Date <em class="red">*</em></label>
												<input type="text" name="from_date" id="from_date" class="form-control" value="<?php if(set_value('from_date')) { echo set_value('from_date'); } else { echo $from_date; } ?>" placeholder="From Date">
												<?php if(form_error('from_date')!=""){ ?><label class="error"><?php echo form_error('from_date'); ?></label><?php } ?>
											</div>
										</div>									
										
										<div class="col-lg-6">								
											<div class="form-group">
												<label for="to_date">To Date <em class="red">*</em></label>
												<input type="text" name="to_date" id="to_date" class="form-control" value="<?php if(set_value('to_date')) { echo set_value('to_date'); } else { echo $to_date; } ?>" placeholder="To Date">
												<?php if(form_error('to_date')!=""){ ?><label class="error"><?php echo form_error('to_date'); ?></label><?php } ?>
											</div>
										</div>
										
										<div class="col-lg-8">								
											<div class="form-group">
												<label for="to_date">Select Subject <em class="red"></em></label>
												<select name="el_subject" id="el_subject" class="form-control chosen-select">
													<option value="">Select Subject</option>
													<?php 
														if(count($exam_data) > 0)
														{
															foreach($exam_data as $res)
															{	$el_sub_val = $res['exam_code'].'##'.$res['subject_code']; ?>
															<option <?php if($el_sub_val == $el_subject) { echo 'selected'; }?> value="<?php echo $el_sub_val; ?>"><?php echo $res['subject_description'];?></option>	
															<?php }
														}
													?>
												</select>
											</div>
										</div>
										
										<div class="col-lg-4">								
											<div class="form-group">
												<label for="to_date">Select Type <em class="red"></em></label>
												<select name="type" id="type" class="form-control chosen-select">
													<option value="">All</option>													
													<option value="exam" <?php if($type == "exam") { echo 'selected'; }?>>Exam</option>
													<option value="spm_el" <?php if($type == "spm_el") { echo 'selected'; }?>>Separate E-learning</option>
												</select>
											</div>
										</div>
									</div>
									
									<div class="row">										
										<?php $this->load->view('webmanager/includes/common_count'); ?>
										
										<div class="col-lg-12">
											<div class="form-group" style="margin: 0 auto;">
												<input type="submit" id="submit" name="submit" class="btn btn-info" value="Submit"> 
												<a href="<?php echo site_url('webmanager/elearning_spm'); ?>" class="btn btn-info">Clear</a>
												<input type="submit" id="download_csv" name="download_csv" class="btn btn-primary" value="Download CSV"> 
											</div>								
										</div>								
									</div>								
								</form>
							</div>
						</div>
						</div>
				</section>
			</div>
			
			<?php $this->load->view('webmanager/includes/footer');?>
			
			<script>
				//START : FORM VALIDATION CODE 
				$(document ).ready( function() 
				{
					$.validator.addMethod("nowhitespace", function(value, element) { if($.trim(value).length == 0) { return false; } else { return true; } });
					
					$.validator.setDefaults({ ignore: ":hidden:not(.chosen-select)" })// For chosen validation
					
					$.validator.addMethod("check_valid_range", function(value, element) 
					{ 
						var from_date = new Date($("#from_date").val());
						var to_date = new Date($.trim(value));
						if(from_date != "" && to_date != "") 
						{ 
							var diff = new Date(to_date - from_date);
							var days = diff/1000/60/60/24;
							if(parseInt(days) > 30)
							{
								return false; 
							}
							else { return true; }						
						} 
						else { return true; } 
					});
					
					$("#myForm").validate( 
					{
						ignore: [], // For Ckeditor
						debug: false, // For Ckeditor
						rules:
						{
							from_date: { required : true, nowhitespace : true },
							to_date: { required : true, nowhitespace : true, check_valid_range : true }
						},
						messages:
						{
							from_date: { required : "Please select the From Date", nowhitespace : "Please select the From Date" },
							to_date: { required : "Please select the To Date", nowhitespace : "Please select the To Date", check_valid_range : "Please select the date range between 30 days" }
						}
					});
				});
				//END : FORM VALIDATION CODE 
			</script>
			
			<script>$( document ).ready( function () { $('#page_loader').delay(0).fadeOut('slow'); });</script>
		</div>
	</body>
</html>