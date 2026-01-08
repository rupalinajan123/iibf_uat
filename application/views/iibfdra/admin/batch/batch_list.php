<?php $this->load->view('iibfdra/admin/includes/header');?>
<?php $this->load->view('iibfdra/admin/includes/sidebar');?>

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






.bell_shake i.fa.fa-bell{
  animation: shake-animation 3s ease infinite;
  transform-origin: 50% 50%;
  color: #fff;
  box-shadow:none
}

@keyframes shake-animation {
0% { transform:translate(0,0) }
  1.78571% { transform:translate(5px,0) }
  3.57143% { transform:translate(0,0) }
  5.35714% { transform:translate(5px,0) }
  7.14286% { transform:translate(0,0) }
  8.92857% { transform:translate(5px,0) }
  10.71429% { transform:translate(0,0) }
  100% { transform:translate(0,0) }
}

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
td .vbtn {position: relative;}


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
							<div class="form-group">
								<label class="form-label">Batch Name</label>
								<select class="form-control custom_search_cls" name="s_batch_name" id="s_batch_name">
									<option value="">All</option>																	
									<option value="ESDS PHP Batch">ESDS PHP Batch</option>																	
									<option value="ESDS DESIGN Batch">ESDS DESIGN Batch</option>																	
								</select>
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
										<th class="text-center">Batch Code</th>
										<th class="text-center">Batch name</th>
										<th class="text-center">From Date - To date</th>
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

	
	/*.bell_shake {
		animation: shake 1200ms infinite;
	    position: absolute;
	    left: 12px;
	    width: 18px;
	    height: 18px;
	    line-height: 20px;
	    border-radius: 100%;
	    background: #FF5E6D;
	    top: 0px;
	    font-size: 12px;
	    color: #ffffff;
	    text-align: center;
	    font-weight: bold;
	}*/
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
		$('.custom_search_cls').change(function(){ console.log();			
			userDataTable.ajax.reload();	
        });

        
 
		var userDataTable = $('#listitems22').DataTable(
		{
			order: [[0, 'desc']],
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": '<?php echo site_url("iibfdra/batch/get_table_data_ajax"); ?>', 
				"type": "POST",
				"data": function ( d ) 
				{
					d.s_agency_name = $("#s_agency_name").val();
					d.s_center_location = $("#s_center_location").val();
					d.s_batch_code = $("#s_batch_code").val();
					d.s_batch_name = $("#s_batch_name").val();
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
			],
			"aaSorting": [],
			"stateSave": false,

			'drawCallback': function(settings)
			{
				var response = settings.json;
				$("#s_agency_name").html(response.agency_name_str);
				$("#s_center_location").html(response.center_location_str);
				$("#s_batch_code").html(response.batch_code_str);
				$("#s_batch_name").html(response.batch_name_str);
			}			
        });

        //$('.bell_id').addClass("bell_shake");
    });

	function clear_search_val()
	{
		$("#s_agency_name").val("");
		$("#s_center_location").val("");
		$("#s_batch_code").val("");
		$("#s_batch_name").val("");
		$('#listitems22').DataTable().ajax.reload();	
	}
		
  $(document).ajaxStart(function() { $("#page_loader").css("display", "block"); });
  $(document).ajaxComplete(function() { $("#page_loader").css("display", "none"); });
</script> 
<?php $this->load->view('iibfdra/admin/includes/footer');?>