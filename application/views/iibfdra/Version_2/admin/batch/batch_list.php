<?php $this->load->view('iibfdra/Version_2/admin/includes/header');?>
<?php $this->load->view('iibfdra/Version_2/admin/includes/sidebar');?>

<style>
.search_form_common_all { background: #ededed; padding: 20px 20px 10px 20px; margin-bottom: 20px; text-align: left; }
.search_form_common_all .form-group { display: inline-block; margin: 0 10px 10px; width: 300px; vertical-align: top; }
.search_form_common_all .form-group .form-label { display: block; font-size: 14px; margin: 0 0 3px 0; line-height: 22px; text-align: left; }
.search_form_common_all .form-group .form-control { padding: 5px 20px 5px 10px; height: 35px !important; }
.search_form_common_all .btn { display: inline-block; vertical-align: top; padding: 7px 20px 6px; margin: 0 0px 0 0; min-width: 97px; }

#listitems22_processing { display:none !important; }
#page_loader { background: rgba(0, 0, 0, 0.35) none repeat scroll 0 0; height: 100%; left: 0; position: fixed; top: 0; width: 100%; z-index: 99999; display:none; }
/* #page_loader .loading { margin: 0 auto; position: relative;border: 16px solid #f3f3f3;border-radius: 50%;border-top: 16px solid #064b86;border-bottom: 16px solid #064b86;width: 80px;height: 80px;-webkit-animation: spin 2s linear infinite;animation: spin 2s linear infinite;top: calc( 50% - 40px);} */
#page_loader .loading { margin: 0 auto; position: relative;	width: 80px;height: 80px;top: calc( 50% - 40px);color: #fff;font-size: 30px; }
@-webkit-keyframes spin { 0% { -webkit-transform: rotate(0deg); } 100% { -webkit-transform: rotate(360deg); } }
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

span.notifn_cnt {
    position: absolute;
    top: -9px;
    right: -9px;
    background: red;
    height: 15px;
    width: 15px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
}

.bell_shake {
    position: absolute;padding-left: 3px;
}
</style>

<div id="page_loader"><div class="loading">Processing...</div></div>

  <div class="content-wrapper">
    <section class="content-header">
		<h1>Training Batch List</h1>
      <?php echo $breadcrumb; ?>
    </section>	
	
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">          	
            <div class="box-body">            
            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
        	<input type="hidden" name="base_url_val" id="base_url_val" value="" />
						
						<form method="POST" class="search_form_common_all">
							<div class="form-group">
								<label class="form-label">Agency Name</label>
								<select class="form-control custom_search_cls" name="s_agency_name" id="s_agency_name">
																									
								</select>
							</div>
							<div class="form-group">
								<label class="form-label">Center Location</label>
								<select class="form-control custom_search_cls" name="s_center_location" id="s_center_location">
									<option value="">All</option>																	
									<option value="Nashik">Nashik</option>																	
									<option value="Pune">Pune</option>																	
								</select>
							</div>
							<div class="form-group">
								<label class="form-label">Batch Code</label>
								<select class="form-control custom_search_cls" name="s_batch_code" id="s_batch_code">
									<option value="">All</option>																		
									<option value="BTCH14">BTCH14</option>																	
									<option value="BTCH15">BTCH15</option>																
								</select>
							</div>
							<!-- <div class="form-group">
								<label class="form-label">Batch Name</label>
								<select class="form-control custom_search_cls" name="s_batch_name" id="s_batch_name">
									<option value="">All</option>																	
									<option value="ESDS PHP Batch">ESDS PHP Batch</option>																	
									<option value="ESDS DESIGN Batch">ESDS DESIGN Batch</option>																	
								</select>
							</div> -->

							<div class="form-group">
								<label class="form-label">No. of Hours</label>
								<select class="form-control custom_search_cls" name="s_batch_hours" id="s_batch_hours">
									<option value="">All</option>																	
									<option value="50">50 Hours</option>																	
									<option value="100">100 Hours</option>																	
								</select>
							</div>

							<div class="form-group">
								<label class="form-label">Mode Of Training</label>
								<select class="form-control custom_search_cls" name="s_modoe_of_training" id="s_modoe_of_training">
									<option value="">All</option>																	
									<option value="1">Online</option>																	
									<option value="0">Offline</option>																	
								</select>
							</div>

							<div class="form-group">
								<label class="form-label">Inspector</label>
								<select class="form-control custom_search_cls" name="s_inspector_name" id="s_inspector_name">
									<option value="">All</option>																																		
								</select>
							</div>
							
							<div class="form-group">
								<label class="form-label">From Date</label>
								<input class="form-control custom_search_cls" type="date" name="s_batch_from_date" id="s_batch_from_date" value="">
							</div>

							<div class="form-group">
								<label class="form-label">To Date</label>
								<input class="form-control custom_search_cls" type="date" name="s_batch_to_date" id="s_batch_to_date" value="">
							</div>	

							<div class="form-group">
								<label class="form-label">&nbsp;</label>
								<a href="javascript:void(0)" class="btn btn-primary" onclick="clear_search_val()">Clear</a>
							</div>
						</form>
									
            			<div class="table-responsive">
							<table id="listitems22" class="table table-bordered table-striped dataTables-examplexx">
                				<thead>
                					<tr>
										<th class="text-center no-sort" style="padding-right:8px;">Sr No</th>
										<th class="text-center">Agency Name</th>
										<th class="text-center">Center location</th>
										<th class="text-center">Total Registered Candidate</th>
										<th class="text-center">Batch Code</th>
										<th class="text-center">Inspector</th>
										<th class="text-center">Reported</th>
										<th class="text-center">Training Medium</th>
										<th class="text-center">Mode Of Training</th>
										<th class="text-center">No. Of Hours</th>
										<th class="text-center">From Date - To date</th>
										<th class="text-center" style="width: 120px;">Holidays</th>
										<th class="text-center">Training Timing</th>
										<th class="text-center">Batch create date</th>
										<th class="text-center">Batch Status</th>                              
										<th class="text-center no-sort">Operations</th>
					                </tr>
					            </thead>
								<tbody></tbody>
				                <!-- <tfoot>
				                    <tr>         
										<th class="text-center no-sort" style="padding-right:8px;">sr No</th>
										<th class="text-center">Agency Name</th>
										<th class="text-center">Center location</th>
										<th class="text-center">Batch Code</th>
										<th class="text-center">Batch name</th>
										<th class="text-center">From Date - To date</th>
										<th class="text-center">Training Timing</th>
										<th class="text-center">Batch create date</th>
										<th class="text-center">Batch Status</th>                              
										<th class="text-center no-sort">Operations</th>
				                    </tr>
				       			</tfoot> -->
              				</table>
              		<div id="links" class="dataTables_paginate paging_simple_numbers"> </div>
            </div>
            </div>
          </div>
        </div>
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
	.pp0 , .pp5, .pp6 , .pp7, .pp8 , .pp9 { display:none;	}
	.vbtn { padding: 3px 21px; font-weight: 900; }
	.#listitems2 { width:100%; max-width:100%; }
	.moption { width:100%; }

	.dataTables_wrapper { max-width:96%; margin:20px auto; }
</style>
</style>

<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script type="text/javascript">
	$(document).ready(function()
	{
		$('.custom_search_cls').change(function() { 
			if ($(this).attr('name') == 's_batch_from_date') {
				var s_batch_from_date = $("#s_batch_from_date").val();
				$("#s_batch_to_date").val(s_batch_from_date);
			}			
			userDataTable.ajax.reload();	
    });
 
		var userDataTable = $('#listitems22').DataTable(
		{
			 order: [[0, 'desc']], 
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": '<?php echo site_url("iibfdra/Version_2/batch/get_table_data_ajax"); ?>', 
				"type": "POST",
				"data": function ( d ) 
				{
					d.s_agency_name       = $("#s_agency_name").val();
					d.s_batch_from_date   = $("#s_batch_from_date").val();
					d.s_batch_to_date     = $("#s_batch_to_date").val();
					d.s_center_location   = $("#s_center_location").val();
					d.s_batch_code        = $("#s_batch_code").val();
					// d.s_batch_name        = $("#s_batch_name").val();
					d.s_batch_hours       = $("#s_batch_hours").val();
					d.s_modoe_of_training = $("#s_modoe_of_training").val();
					d.s_inspector_name    = $("#s_inspector_name").val();
					
				}
			},
			"lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
			 "language": 
			 {
				"lengthMenu": "_MENU_",
				/* "zeroRecords": "Nothing found - sorry",
				"info": "Showing page _PAGE_ of _PAGES_",
				"infoEmpty": "No records available",
				"infoFiltered": "(filtered from _MAX_ total records)" */
			},
			pageLength: 10,
			responsive: true,
			"columnDefs": 
			[
				{"targets": 'no-sort', "orderable": false, },
				{"targets": [0], "className": "text-center"},
				{"targets": [5], "className": "text-center"},
				{"targets": [6], "className": "text-center"},
				{"targets": [7], "className": "text-center"},
				{"targets": [8], "className": "text-center"},
				{"targets": [9], "className": "text-center"},
				{"targets": [10], "className": "text-center"},
			],
			"aaSorting": [],
			"stateSave": false,
			/* dom: '<"html5buttons"B>lTfgitp',
			buttons: 
			[
			{extend: 'copy'},
			{extend: 'csv', title: 'list_csv_<?php echo date("Ymdhis"); ?>', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 8 ] }},
			{extend: 'excel', title: 'list_csv_<?php echo date("Ymdhis"); ?>', exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 8 ] }},
			{extend: 'pdf', title: 'ExampleFile'},					
			{
				extend: 'print',
				customize: function (win)
				{
					$(win.document.body).addClass('white-bg');
					$(win.document.body).css('font-size', '10px');
					$(win.document.body).find('table')
					.addClass('compact')
					.css('font-size', 'inherit');
				}
			}
			], */
			'drawCallback': function(settings)
			{
				/* $( ".checkboxlist_new" ).click(function() { checkboxlist_new_function(); });
				$('#checkboxlist_all_new').prop('checked', false);
				checkboxlist_new_function();
				$('input[type=checkbox][data-toggle^=toggle]').bootstrapToggle(); */
				var response = settings.json;
				$("#s_agency_name").html(response.agency_name_str);
				$("#s_center_location").html(response.center_location_str);
				$("#s_batch_code").html(response.batch_code_str);
				// $("#s_batch_hours").html(response.batch_hours_str);
				// $("#s_batch_name").html(response.batch_name_str);
				$("#s_inspector_name").html(response.inspector_name_str);
			}			
        });
    });

	function clear_search_val()
	{
		$("#s_agency_name").val("");
		$("#s_batch_from_date").val('');
		$("#s_batch_to_date").val('');
		$("#s_center_location").val("");
		$("#s_batch_code").val("");
		// $("#s_batch_name").val("");
		$("#s_batch_hours").val("");
		$("#s_modoe_of_training").val("");
		$("#s_inspector_name").val("");
		$('#listitems22').DataTable().ajax.reload();	
	}
		
  $(document).ajaxStart(function() { $("#page_loader").css("display", "block"); });
  $(document).ajaxComplete(function() { $("#page_loader").css("display", "none"); });
</script> 
<?php $this->load->view('iibfdra/Version_2/admin/includes/footer');?>