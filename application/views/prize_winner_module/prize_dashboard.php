<!DOCTYPE html>
<html>
	<head>
    <?php $this->load->view('prize_winner_module/includes/header');?>
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
								<h4 class="title_common">Prize Winner Dashboard</h4>
								<form id="myForm" name="myForm" method="post" action="<?php echo base_url();?>admin/PrizeDashboard/search_record" enctype="multipart/form-data" role="form">
									
									<div class="form-group">
									  
										<label for="from_date" class="col-sm-2">From Date</label>
											 <div class="col-sm-3">
												<input type="text" class="form-control" id="from_date" name="from_date" placeholder="From Date" required value="<?php echo set_value('from_date');?>" readonly>
												 <span class="error"><?php echo form_error('from_date');?></span>
											</div>
										
										<label for="to_date" class="col-sm-2">To Date</label>
											 <div class="col-sm-3">
												<input type="text" class="form-control" id="to_date" name="to_date" placeholder="To Date" required value="<?php echo set_value('to_date');?>" readonly >
												 <span class="error"><?php echo form_error('to_date');?></span>
											</div>
										<input type="submit" class="btn btn-info" name="btnSearch" id="btnSearch" value="Search" onclick="return searchOnDate();">  
										                        
										
										
										
									</div>
													
															
								</form>
							</div>
 <!-------------------CSV download------------------>
<form id="myForm" name="myForm" method="post" action="<?php echo base_url();?>admin/PrizeDashboard/download_csv" enctype="multipart/form-data" role="form">
<input type="hidden" class="form-control" id="from_date_hidden" name="from_date" placeholder="From Date" required value="<?php echo set_value('from_date');?>" readonly>
<input type="hidden" class="form-control" id="to_date_hidden" name="to_date" placeholder="To Date" required value="<?php echo set_value('to_date');?>" readonly >
			<div class="col-sm-12">
				<div class="panel panel-default">
					<div class="panel-body">
<input type="submit" class="btn btn-info" name="search_on_fields" id="search_on_fields" value="Download CSV" onclick = "update();">  
                        
                    </div>
					<!--<div class="panel-footer"><?php echo $total_count; ?></div>-->
				</div>
			</div>
</form>
	  <!-------------------End CSV----------------------->
							 <!-------------------Count div start--------------->
							<div class="col-sm-12">
								<div class="panel panel-default">
									<div class="panel-body">
										  <b> Total Members Registered For Prize Winner :- <?php echo $total_count; ?></b>
									</div>
									
								</div>
							</div>
							<!-------------------Count div End----------------->	
							
							   
							<div class="col-xs-12">

							  <div class="box">
								<div class="box-header">
								  <h3 class="box-title">Members list</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
								<div id="links" class="dataTables_paginate paging_simple_numbers"> </div>
								<table id="listcount" class="table table-bordered table-striped dataTables-example">
									<thead>
									<tr>
									  <th id="">Sr No</th> 
									  <th id="">Membership No.</th> 
									  <th id="">Name</th>
									  <th id="">Moblie</th>
									  <th id="">Email</th>
									  <th id="">Bank Name</th>
									  <th id="">Branch Name</th>
									  <th id="">IFS Code</th>
									  <th id="">Account Type</th>
									  <th id="">Account No</th>
									  <th id="">Date</th>
									  
									</tr>
									</thead>
								   <?php 
								   $row_count=1;
								if(count($success_data)){

											foreach($success_data as $row)
											{?>
											<tr>
												<td><?php echo $row_count;?></td>
												<td><?php echo $row['regnumber'];?></td>
												<td><?php echo $row['namesub'].$row['firstname'].$row['lastname'];?></td> 
												<td><?php echo $row['moblie'];?></td>
												<td><?php echo $row['email'];?></td>
												<td><?php echo $row['bankname'];?></td>
												<td><?php echo $row['branchname'];?></td>
												<td><?php echo $row['ifs_code'];?></td>
												<td><?php echo $row['account_type'];?></td>
												<td><?php echo $row['account_no'];?></td>
												<td><?php echo $row['created_on'];?></td>
												
											</tr>
									  <?php $row_count++; }} ?>   
												
									 </tbody>
								</table>
								</div>
								</div>
						</div>
					</div>
				</section>
			</div>
			
			
			
			<?php $this->load->view('prize_winner_module/includes/footer');?>
			

<link href="https://iibf.esdsconnect.com/assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="https://iibf.esdsconnect.com/assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<!-- <link href="https://iibf.esdsconnect.com/assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet"> -->

<!-- Data Tables -->
<script src="https://iibf.esdsconnect.com/assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="https://iibf.esdsconnect.com/assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="https://iibf.esdsconnect.com/assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<!-- <script src="https://iibf.esdsconnect.com/assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>  -->

<script src="https://iibf.esdsconnect.com/assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="https://iibf.esdsconnect.com/assets/admin/plugins/datepicker/datepicker3.css">

<script src="https://iibf.esdsconnect.com/assets/admin/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<link rel="stylesheet" href="https://iibf.esdsconnect.com/assets/admin/plugins/timepicker/bootstrap-timepicker.min.css">

<script src="https://iibf.esdsconnect.com/assets/js/parsley.min.js"></script>
<script src="https://iibf.esdsconnect.com/js/validation.js?1631093909"></script>	
<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script>
function getSearchBook($searchBook) {
    if(empty($searchBook))
       return array();

    $result = $this->db->like('title', $searchBook)
             ->or_like('author', $searchBook)
             ->get('books');

    return $result->result();
} 
function update()
{
	var fromDate = $("#from_date").val();
	var toDate = $("#to_date").val();
	 $("#from_date_hidden").val(fromDate);
$("#to_date_hidden").val(toDate);
}
function searchOnDate()
{
	var fromDate = $("#from_date").val();
	var toDate = $("#to_date").val();
	if(fromDate=='' && toDate=='')
	{
		//alert('Please select atleast one date');
		alert('Please select dates');
		return false;
	}
	else if(fromDate=='' && toDate!='')
	{
		alert('Please select From Date');
		return false;
	}
	/* else
	{
		var perPage = $('#perPage').val();
		var searcharr = [];
		searcharr['field'] = 'date-BETWEEN';
		//'exam_code,description,qualifying_exam1,qualifying_part1,qualifying_exam2,qualifying_part2,qualifying_exam3,qualifying_part2,exam_type';
		searcharr['value'] = fromDate+'~'+toDate;
		paginate('',searcharr,perPage);
	} */
}




				$('#ExamCodeModal').on('shown.bs.modal', function () { $('#new_exam_period').focus(); })
				
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
							else 
							{
								error.insertAfter(element);
							}
						}
					});
				});
				//END : FORM VALIDATION CODE 
			</script>
			
			<script>
$( document ).ready( function () { $('#page_loader').delay(0).fadeOut('slow'); });

$(function () {
  $('#listcount').DataTable();
  $("#listitems_filter").show();
});
</script>
		</div>
	</body>
</html>