<!DOCTYPE html>
<html>
	<head>
    <?php $this->load->view('MonthlyCount_CSV/includes/header');?>
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
								<h4 class="title_common">Monthly Count</h4>
								<form id="myForm" name="myForm" method="post" action="" enctype="multipart/form-data" role="form">
									<input type="hidden" id="security_token" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
									
									<div class="row">									
										<div class="col-lg-6">								
											<div class="form-group">
												<label for="from_date">From Date<em class="red">*</em></label>
												<input type="text" name="from_date" id="from_date" class="form-control" value="<?php if(set_value('from_date')) { echo set_value('from_date'); } else { echo $from_date; } ?>" placeholder="From Date">
											</div>
										</div>									
										
										<div class="col-lg-6">								
											<div class="form-group">
												<label for="to_date">To Date<em class="red">*</em></label>
												<input type="text" name="to_date" id="to_date" class="form-control" value="<?php if(set_value('to_date')) { echo set_value('to_date'); } else { echo $to_date; } ?>" placeholder="To Date">
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
													<option value="01" <?php echo set_select('exam_code', '01'); ?> >Chartered Banker Exam</option>
													<option value="02" <?php echo set_select('exam_code', '02'); ?> >DRA Exam</option>
													<option value="03" <?php echo set_select('exam_code', '03'); ?> >GARP-FRR Exam</option>
													<option value="04" <?php echo set_select('exam_code', '04'); ?> >AMP-Self Sponsord</option>
													<option value="05" <?php echo set_select('exam_code', '05'); ?> >AMP-Bank Sponsord</option>
													<option value="06" <?php echo set_select('exam_code', '06'); ?> >XLRI-Self Sponsord</option>
													<option value="07" <?php echo set_select('exam_code', '07'); ?> >XLRI-Bank Sponsord</option>
													
												</select>
												<?php if(form_error('exam_code')!=""){ ?><label class="error"><?php echo form_error('exam_code'); ?></label><?php } ?>
											</div>
										</div>
										
										
									</div>								
								
										<div class="row">
										
										<div class="col-lg-6">								
											<div class="form-group">
												<label for="approved">Approved Invoices Count</label>
												<?php if($exam_code == '01'){
												    if($approved_chartered_count > 0){
												echo $approved_chartered_count;
												}else{
												    echo "No Data";
												}
												}else if($exam_code == '02'){
												if($approved_dra_count > 0){
												echo $approved_dra_count;
												}else{
												    echo "No Data";
												}}
												else if($exam_code == '03'){
												if($approved_garp_count > 0){
												echo $approved_garp_count;
												}else{
												    echo "No Data";
												}}
												else if($exam_code == '04'){
												if($approved_ampself_count > 0){
												echo $approved_ampself_count;
												}else{
												    echo "No Data";
												}}
												else if($exam_code == '05'){
												if($approved_ampbank_count > 0){
												echo $approved_ampbank_count;
												}else{
												    echo "No Data";
												}}
												else if($exam_code == '06'){
												if($approved_xlriself_count > 0){
												echo $approved_xlriself_count;
												}else{
												    echo "No Data";
												}}
												else if($exam_code == '07'){
												if($approved_xlribank_count > 0){
												echo $approved_xlribank_count;
												}else{
												    echo "No Data";
												}}
												?>
												
											</div>
										</div>
									</div>
									
									<div class="row">
											<div class="col-lg-12">
											<div class="form-group" style="margin: 0 auto;">
												<input type="submit" id="submit" name="submit" class="btn btn-info" value="Submit"> 
												<a href="<?php echo site_url('admin/MonthlyCount_CSV'); ?>" class="btn btn-info">Clear</a>
											</div>								
										</div>								
									</div>								
								</form>
							</div>
						</div>
					</div>
				</section>
			</div>
			
			
			
			<?php $this->load->view('MonthlyCount_CSV/includes/footer');?>
			
			<script>
				$('#ExamCodeModal').on('shown.bs.modal', function () { $('#new_exam_period').focus(); })
				
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
							exam_code: { required : true },
							from_date: { required : true },
							to_date: { required : true }
						},
						messages:
						{
							exam_code: { required : "Please select the Exam Code" },
							from_date: { required : "Please select the From Date" },
							to_date: { required : "Please select the To Date" }
						},
						errorPlacement: function(error, element) // For replace error 
						{
							if (element.attr("name") == "exam_code") 
							{
								error.insertAfter("#exam_code_chosen");
							}
							else if (element.attr("name") == "exam_period") 
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