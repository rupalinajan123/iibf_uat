<!DOCTYPE html>
<html>
	<head>
		<?php $this->load->view('apply_elearning/inc_header'); ?>
		<style>
			form#myForm { background: #eaeaea; padding: 20px; margin-bottom:20px; }
			.main-header { width: 100%; max-width:1400px; }
			.container { width: 100%; max-width:1400px; }
			
			.main-header .navbar { max-width: 600px; margin: 0 auto !important; }
			
			.custom_table_listing { vertical-align:top; border-color:#ccc !important; margin-bottom:0 !important; }
			.custom_table_listing th { background: #eaeaea; border-color:#ccc !important; vertical-align: top !important; padding:8px 8px !important; }
			.custom_table_listing td { border-color:#ccc !important; vertical-align: top !important; padding:6px 6px !important; }
		</style>
	</head>
	
	<body class="hold-transition skin-blue layout-top-nav">
		<div class="wrapper">
			<?php $this->load->view('apply_elearning/inc_navbar'); ?>
			
			<div class="container">				
				<section class="content">
					<section class="content-header">
						<h1 class="register">E-learning Member Subject Data</h1><br/>
					</section>
					
					<div class="box box-info">
						<div class="row">
							<div class="col-sm-12">
								<form id="myForm" name="myForm" method="post" action="javascript:void(0)" enctype="multipart/form-data" role="form">
									<input type="hidden" id="security_token" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
									
									<div class="row">									
										<div class="col-lg-4">								
											<div class="form-group">
												<label for="from_date">From Date <em class="red">*</em></label>
												<input type="text" name="from_date" id="from_date" class="form-control" value="<?php echo $from_date; ?>" placeholder="From Date">
											</div>
										</div>									
										
										<div class="col-lg-4">								
											<div class="form-group">
												<label for="to_date">To Date <em class="red">*</em></label>
												<input type="text" name="to_date" id="to_date" class="form-control" value="<?php echo $to_date; ?>" placeholder="To Date">
											</div>
										</div>									
										
										<div class="col-lg-4">								
											<div class="form-group">
												<label for="regnumber">Membership No. <em class="red"></em></label>
												<input type="text" name="regnumber" id="regnumber" class="form-control" value="<?php echo $regnumber; ?>" placeholder="Membership No.">
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-lg-12">
											<div class="form-group" style="margin: 0 auto;">
												<input type="submit" id="submit" name="submit" class="btn btn-info" value="Submit"> 
												
												<?php if(count($result_data) > 0)
													{ ?>
														<a href="javascript:void(0)" onclick="export_csv_fun()" class="btn btn-primary">Export CSV</a>
										<?php }  ?>
												
												<a href="<?php echo site_url('elearning_spm_data'); ?>" class="btn btn-danger">Clear</a>
											</div>								
										</div>								
									</div>								
								</form>
								
								<p style="font-weight: bold; font-size: 15px; margin: 0 0 6px 0; background: #eaeaea; display: inline-block; padding: 6px 15px; ">Total Count : <?php echo count($result_data); ?></p>
								<div class="table-responsive">
									<table class="table table-bordered custom_table_listing">
										<thead>
											<th class="text-center">Sr. No</th>
											<th class="text-center">Name Sub</th>
											<th class="text-center">First Name</th>
											<th class="text-center">Middle Name</th>
											<th class="text-center">Last Name</th>
											<th class="text-center">Email</th>
											<th class="text-center">Mobile</th>
											<th class="text-center">State</th>
											<th class="text-center">Membership No.</th>
											<th class="text-center">Exam Code</th>
											<th class="text-center">Subject Code</th>
											<th class="text-center">Subject Name</th>
											<th class="text-center">Date</th>
										</thead>
										
										<tbody>
											<?php if(count($result_data) > 0)
												{
													$sr_no = 1;
													foreach($result_data as $res)
													{ ?>
													<tr>
														<td class="text-center"><?php echo $sr_no; ?></td>
														<td><?php echo $res['namesub']; ?></td>
														<td><?php echo $res['firstname']; ?></td>
														<td><?php echo $res['middlename']; ?></td>
														<td><?php echo $res['lastname']; ?></td>
														<td><?php echo $res['email']; ?></td>
														<td><?php echo $res['mobile']; ?></td>
														<td><?php echo $res['state']; ?></td>
														<td><?php echo $res['regnumber']; ?></td>
														<td><?php echo $res['exam_code']; ?></td>
														<td><?php echo $res['subject_code']; ?></td>
														<td><?php echo $res['subject_description']; ?></td>
														<td><?php echo date("Y-m-d h:ia", strtotime($res['created_on'])); ?></td>
													</tr>
													<?php		$sr_no++;
													}
												}
												else {	?>
												<tr><td class="text-center" colspan="13">No Record found</td></tr>
												<?php } ?>
										</tbody>											
									</table>
								</div>
							</div>
						</div>
					</div>
					
					<?php $this->load->view('apply_elearning/inc_footerbar'); ?>					
				</section>
			</div>
		</div>		
		
		<?php $this->load->view('apply_elearning/inc_footer'); ?>
		
		<!----- FOR DATEPICKER ----->
		<script src="<?php echo base_url('assets/admin/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>
		<link rel="stylesheet" href="<?php echo base_url('assets/admin/plugins/datepicker/datepicker3.css'); ?>">
		
		<script type="text/javascript">
			$(document).ready(function() 
			{			
				$("#from_date").attr('autocomplete', 'off');
				$("#to_date").attr('autocomplete', 'off');
				
				$('#from_date').datepicker({ format: 'yyyy-mm-dd', endDate: '+0d', autoclose: true, forceParse: true }).on('changeDate', function()
				{
					$('#to_date').datepicker('setStartDate', new Date($(this).val()));
				}); 
				
				$('#to_date').datepicker({ format: 'yyyy-mm-dd', endDate: '+0d', autoclose: true, forceParse: true }).on('changeDate', function()
				{
					$('#from_date').datepicker('setEndDate', new Date($(this).val()));
				});
			});
		</script>
		
		<?php if(isset($from_date) && $from_date != "") 
			{	?> 
			<script type="text/javascript">
				$('#to_date').datepicker({ format: 'yyyy-mm-dd', startDate:'<?php echo $from_date; ?>', endDate: '+0d', autoclose: true });
			</script>	
			<?php }
			
			if(isset($to_date) && $to_date != "") 
			{	?> 
			<script type="text/javascript">
				$('#from_date').datepicker({ format: 'yyyy-mm-dd', endDate: '<?php echo $to_date; ?>', autoclose: true });
			</script>	
		<?php } ?>
		
		<script src="<?php echo base_url('assets/js/jquery.validate.js'); ?>"></script><!----- FOR JQUERY VALIDATION ----->
		<script type="text/javascript">
			//START : FORM VALIDATION CODE 
			$(document ).ready( function() 
			{
				$.validator.addMethod("nowhitespace", function(value, element) { if($.trim(value).length == 0) { return false; } else { return true; } });
				
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
						to_date: { required : "Please select the To Date", nowhitespace : "Please select the To Date", check_valid_range : "Please select the date within one month only" }
					},
					submitHandler: function(form) 
					{
						$('.loading').show();
						var sel_from_date = $("#from_date").val();
						var sel_to_date = $("#to_date").val();
						var regnumber = $("#regnumber").val();
						window.location.replace("<?php echo site_url('elearning_spm_data/index') ?>/"+sel_from_date+"/"+sel_to_date+"/"+regnumber);
					}
				});
			});
			//END : FORM VALIDATION CODE 
			
			function export_csv_fun()
			{
				var sel_from_date = $("#from_date").val();
				var sel_to_date = $("#to_date").val();
				var regnumber = $("#regnumber").val();
				if(sel_from_date != "" && sel_to_date != "")
				{
					window.location.replace("<?php echo site_url('elearning_spm_data/elearning_spm_data_download_CSV') ?>/"+sel_from_date+"/"+sel_to_date+"/"+regnumber);
				}
				else
				{
					alert("Please select the date");
				}
			}
		</script>
    
    <script>	
			$( document ).ready( function () { $('.loading').delay(0).fadeOut('slow'); });
		</script>			
	</body>
</html>