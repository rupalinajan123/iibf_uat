<?php $this->load->view('JBIMS_dashboard/includes/header');?>
<?php $this->load->view('JBIMS_dashboard/includes/sidebar');?>

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
            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
						<input type="hidden" name="base_url_val" id="base_url_val" value="" />
            <div class="table-responsive">
							<table id="listitems2" class="table table-bordered table-striped dataTables-example">
                <thead>
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
										<th>Documents</th>
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
										<th>Documents</th>
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
											    echo '<td><a class="btn btn-xs vbtn" href="'.base_url().'JBIMS_dashboard/bankunpaid_pdf/'.base64_encode($res['regnumber']).'">Exam Form</a>';
											     echo '<td><a class="btn btn-xs vbtn" href="https://iibf.esdsconnect.com/uploads/JBIMS/photograph/'.$res['photograph'].'" download>Photograph</a>
											    <a class="btn btn-xs vbtn" href="https://iibf.esdsconnect.com/uploads/JBIMS/signature/'.$res['signature'].'" download>Signature</a>
											    <a class="btn btn-xs vbtn" href="https://iibf.esdsconnect.com/uploads/JBIMS/idproof/'.$res['idproof'].'" download>Idproof</a></td></tr>';
											    
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
	.pp0 , .pp2,.pp3,.pp4,.pp5, .pp6 , .pp7, .pp8 , .pp9, .pp10 { display:none;	}
	.vbtn { padding: 3px 21px; font-weight: 900; }
	.#listitems2 { width:100%; max-width:100%; }
	.moption { width:100%; }
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
<?php $this->load->view('JBIMS_dashboard/includes/footer');?>