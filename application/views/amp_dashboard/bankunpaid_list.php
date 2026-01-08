<?php $this->load->view('amp_dashboard/includes/header');?>
<?php $this->load->view('amp_dashboard/includes/sidebar');?>

<div class="content-wrapper"><!-- Content Wrapper. Contains page content -->	
	<section class="content-header"><!-- Content Header (Page header) -->
		<h1>Bank Sponsored Listing</h1>
		<?php echo $breadcrumb; ?>
	</section>	
	
	<section class="content"><!-- Main content -->
		<div class="row">
			<div class="col-xs-12">
				<div class="box">          	
					<div class="box-body"> 
					<div class="row" >
							<form action="" method="post">
								<div class="form-group">
									<label for="roleid" class="col-sm-1 control-label">Year </label>
									<div class="col-sm-3">
										<?php
											$startYear = (date('Y')-7);
											$endYear = date('Y'); ?>
											<select name="download_data_year" class="form-control">
												<option value="">Select</option>
												<?php 
												for($i=$startYear;$i<=$endYear;$i++) {
													?>
													<option value="<?php echo date(''.$i.'-04-01'); ?>To<?php echo date(''.($i+1).'-03-31'); ?>"> <?php echo date('01-04-'.$i); ?> To <?php echo date('31-03-'.($i+1)); ?></option>
													<?php
												}	
											?>
										</select>
									</div>

									<div class="col-sm-3">
										
											<select name="download_data" class="form-control">
												<option value="ExamForm">Exam Form</option>
												
										</select>
									</div>
									<div class="col-sm-3">
										
									<button type="submit" class="btn btn-primary">Download </button>
						
									</div>
								</div>
							</form>
						</div> 
						<?php /*<a href="<?php echo base_url('Amp_dashboard/download_bulk_invoice/bank_unpaid'); ?>" class="btn btn-primary">Download Invoice <?php echo date('Y'); ?></a> */?>  
						<!--<a href="<?php echo base_url('Amp_dashboard/download_bulk_examForm/bank_unpaid'); ?>" class="btn btn-primary mb-2">Download Exam Form <?php echo date('Y'); ?></a>   -->                
            			<input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
						<input type="hidden" name="base_url_val" id="base_url_val" value="" />
            			<div class="table-responsive">
							<table id="listitems2" class="table table-bordered table-striped dataTables-example">
                				<thead>
									<tr>
										<th>sr No</th>
										<th>Enrolment No</th>
										<th>Candidate Name</th>
										<th>Date Of Birth</th>
										<th>IIBF Membership No</th>                       
										<th>Email</th>
										<th>Mobile No</th>
										<th>Bank Name</th>
										<th>Date</th>     
										<th>Operations</th>
									</tr>
								</thead>
								<tfoot>
									<tr>         
										<th>sr No</th>
										<th>Registration No</th>
										<th>Candidate Name</th>
										<th>Date Of Birth</th>
										<th>IIBF Membership No</th>                       
										<th>Email</th>
										<th>Mobile No</th>
										<th>Bank Name</th>
										<th>Date</th>       
										<th>Operations</th>
									</tr>
								</tfoot>
                <tbody class="no-bd-y" id="list2">
									<?php 
										//echo "<pre>"; print_r($bank_list); exit;
										$k = 1;
										if(count($bank_list) > 0)
										{
											foreach($bank_list as $res)
											{
												echo '<tr><td>'.$k.' </td>';
												echo '<td>'.$res['regnumber'].' </td>';
												echo '<td>'.$res['name'].' </td>';						
												echo '<td>'.$res['dob'].' </td>';	
												echo '<td>'.$res['iibf_membership_no'].' </td>';
												echo '<td>'.$res['email_id'].' </td>';
												echo '<td>'.$res['mobile_no'].' </td>';
												echo '<td>'.$res['sponsor_bank_name'].' </td>';
												echo '<td>'.$res['createdon'].' </td>';
											    echo '<td><a class="btn btn-xs vbtn" href="'.base_url().'Amp_dashboard/bankunpaid_pdf/'.base64_encode($res['regnumber']).'">Exam Form</a>';
											    
												$k++;	
											}
										} ?>
								</tbody>
							</table>
              <div id="links" class="dataTables_paginate paging_simple_numbers"> </div>
						</div>
					</div><!-- /.box-body -->
				</div><!-- /.box -->
			</div><!-- /.col -->
		</div>		
	</section>   
</div>

<!-- Data Tables -->
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<style>
	.input_search_data { width:100%; }
	tfoot { display: table-header-group; }
	.pp0 , .pp2,.pp3,.pp4,.pp5, .pp6 , .pp7, .pp8 , .pp9 { display:none;	}
	.vbtn { padding: 3px 21px; font-weight: 900; }
	.#listitems2 { width:100%; max-width:100%; }
	.moption { width:100%; }
	.mb-2{margin-bottom: 0.5rem;}
</style>

<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">

<script src="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.css">

<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script type="text/javascript">
  //$('#searchDate').parsley('validate');
</script>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script>
	$(function () 
	{
		//$('#listitems2').DataTable();
		$("#listitems2_filter").show();
		
		// DataTable
		
		
		/*setTimeout(function(){  
			
			var table = $('#listitems2').DataTable(
			"columnDefs": [
			{ "width": "7%", "targets": 0 },
			{ "width": "25%", "targets": 1 },
			{ "width": "13%", "targets": 2 },
			{ "width": "15%", "targets": 3 },
			{ "width": "10%", "targets": 4 },
			{ "width": "15%", "targets": 5 } 
			{ "width": "10%", "targets": 6 }
			{ "width": "10%", "targets": 7 }     
			]);
			
		}, 3000);*/
		
		var table = $('#listitems2').DataTable();
		$("#listitems2 tfoot th").each( function ( i ) 
		{
			var select = $('<select  class="moption pp'+i+'" ><option value="">All</option></select>')
			.appendTo( $(this).empty() )
			.on( 'change', function () {
				table.column( i )
				.search( $(this).val() )
				.draw();
			} );
			
			table.column( i ).data().unique().sort().each( function ( d, j ) {
				select.append( '<option value="'+d+'">'+d+'</option>' )
			});
		});	
	});
</script> 
<?php $this->load->view('amp_dashboard/includes/footer');?>