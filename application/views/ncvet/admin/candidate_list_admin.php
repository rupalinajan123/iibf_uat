<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'NCVET'; } ?></title>
    <?php $this->load->view('ncvet/inc_header'); ?>    
  </head>
	<style>
		.table.toggle_btn_tbl_outer .toggle.btn {
    	width: 89px !important;
    }
    /* === KEY ADDITION: Force content onto one line === */
    .dataTables-example th,
    .dataTables-example td {
        white-space: nowrap; /* Prevents text from wrapping */
    }

   /* 1. STICKY ROW & ALIGNMENT */
.dataTables_wrapper .sticky-header {
    /* STICKY POSITIONING */
    position: sticky;
    left: 0; /* Stays fixed at the left edge when scrolling horizontally */
    z-index: 10; 
    
    /* BACKGROUND AND SPACING */
    background-color: white; /* **Fix: White Background** */
    padding: 8px 15px;
    
    /* FLEXBOX ALIGNMENT */
    display: flex;
    /* This pushes the first element (length) to the left and the 
       last element (search) to the right */
    justify-content: space-between; 
    align-items: center; /* Vertically center the elements */
}

/* 2. REMOVE THE HORIZONTAL LINE */
/* The line you see is often the border-bottom of the main DataTables wrapper 
   or the border-bottom of the table header itself. */

/* Target the header/table separator line */
div.dataTables_wrapper div.dataTables_scrollHead table.dataTable {
    /* This removes the line *above* the main table header labels (THs) */
    border-top: none !important;
}

/* If a line is appearing between the sticky row and the table header, 
   it may be the border on the sticky row itself (which we set in the previous answer, now removed here).
   If a line appears *below* the table data (at the very bottom), use this: */
table.dataTable.no-footer {
    border-bottom: none !important;
}

/* Ensure the sticky wrapper doesn't have an external border that looks like a line */
.dataTables_wrapper .sticky-header {
    border-bottom: none !important; /* Remove any border that might act as the line */
}

/* 3. OPTIONAL: Clean up the individual control containers */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    /* Ensures no extra margin or styling interferes with the Flexbox layout */
    margin: 0; 
}

	/* 1. Alignment for the bottom controls */
div.dataTables_wrapper div.bottom-controls {
    /* Use Flexbox for side-by-side alignment */
    display: flex;
    
    /* Pushes the first item (info) to the left and the last item (pagination) to the right */
    justify-content: space-between; 
    
    /* Optional: Add vertical padding for breathing room */
    padding: 15px 0; 
}

/* 2. Optional: Tidy up the default DataTables elements */
div.dataTables_wrapper div.dataTables_info,
div.dataTables_wrapper div.dataTables_paginate {
    /* Remove any default margin or float that might interfere */
    margin: 0;
    /* Ensure no wrapping happens */
    white-space: nowrap; 
}

	</style>
	<body class="fixed-sidebar">
    <?php $this->load->view('ncvet/common/inc_loader'); ?>
		
		<div id="wrapper">
      <?php $this->load->view('ncvet/admin/inc_sidebar_admin'); ?>
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('ncvet/admin/inc_topbar_admin'); ?>
				
        <?php $disp_title = 'All Candidate List'; 
        if($enc_batch_id != '0') { $disp_title = 'Candidate List'; } ?>

				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-12">
						<h2><?php echo $disp_title; ?></h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('ncvet/admin/dashboard_admin'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item active"> <strong><?php echo $disp_title; ?></strong></li>
						</ol>
					</div>
				</div>
        
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
               
                <!-- <div class="ibox-title">
                  <div class="ibox-tools"> 
                    <a href="<?php echo site_url('ncvet/admin/candidate'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a> 
                  </div>
                </div> -->   
               
                <div class="ibox-content" style="padding: 15px 20px 20px 20px; overflow-x: none;">
                	<form method="POST" action="<?php echo base_url("ncvet/admin/candidate/get_eligible_candidate_for_examination_data_ajax"); ?>" id="search_form" class="search_form_common_all" action="javascript:void(0)" autocomplete="off">
                		
                    <input type="hidden" name="tbl_search_value" id="tbl_search_value">
                  	<input type="hidden" name="form_action" id="form_action" value="">

                    <div class="form-group text-left">
                    	<select class="form-control chosen-select search_opt" name="s_reference" id="s_reference">
                    		<option value="ALL" selected>Select Enrollment Channel</option>
                    		<option value="BFSI"> BFSI SSC </option>
                    		<option value="REGULAR"> IIBF website </option>
                    	</select>
                    </div>

                    <div class="form-group text-left">
                    	<select class="form-control chosen-select search_opt" name="s_gender" id="s_gender">
                    		<option value="" selected>Select Gender</option>
                    		<option value="1"> Male </option>
                    		<option value="2"> Female </option>
                    	</select>
                    </div>
                    
                    <?php $qualification_arr = $this->config->item('ncvet_qualification_arr'); ?>
                    <div class="form-group text-left">
                    	<select name="s_qualification" id="s_qualification" class="form-control chosen-select search_opt">
												<?php if(count($qualification_arr) > 0)
												{ ?>
												<option value="" selected>Select Eligibility</option>
												<?php foreach($qualification_arr as $key=>$sal_val)
												{ ?>
												<option value="<?php echo $key; ?>"><?php echo $sal_val; ?></option>
												<?php }
												} ?>
											</select>
                    </div>

                    <div class="form-group text-left">
	                    <select name="s_qualification_state" id="s_qualification_state" class="form-control chosen-select search_opt" >
												<?php if(count($state_master_data) > 0) { ?>
													<option value="" selected>Select Eligibility State </option>
													<?php foreach($state_master_data as $state_res) { ?>
														<option value="<?php echo $state_res['state_code']; ?>"> 
															<?php echo $state_res['state_name']; ?>  
													  </option>
														<?php }
													}
													else 
													{ ?>
														<option value="">No State Available</option>
												<?php } ?>
											</select>
										</div>
										
										<div class="form-group text-left">
                    	<select class="form-control chosen-select search_opt" name="s_benchmark_disability" id="s_benchmark_disability">
                    		<option value="" selected>Select Disability</option>
                    		<option value="Y"> Yes </option>
                    		<option value="N"> No </option>
                    	</select>
                    </div>

                    <div class="form-group text-left">
                    	<select class="form-control chosen-select search_opt" name="s_kyc_status" id="s_kyc_status">
                    		<option value="" selected>Select KYC Status</option>
                    		<option value="0"> Pending </option>
                    		<option value="1"> In Progress </option>
                    		<option value="2"> Approved </option>
                    		<option value="3"> Rejected </option>
                    	</select>
                    </div>

                    <div class="form-group text-left">
                    	<select class="form-control chosen-select search_opt" name="s_benchmark_kyc_status" id="s_benchmark_kyc_status">
                    		<option value="" selected>Select BenchMark KYC Status</option>
                    		<option value="0"> Pending </option>
                    		<option value="1"> In Progress </option>
                    		<option value="2"> Approved </option>
                    		<option value="3"> Rejected </option>
                    	</select>
                    </div>

                    <div class="form-group text-left">
                    	<select class="form-control chosen-select search_opt" name="s_status" id="s_status">
                    		<option value="" selected>Select Status</option>
                    		<option value="1"> Active </option>
                    		<option value="0"> Deactive </option>
                    	</select>
                    </div>

                    <!-- <div class="form-group text-left">
                      <input type="text" class="form-control search_opt" name="s_regnumber" id="s_regnumber" placeholder="Registration No.">
                    </div>


                    <div class="form-group text-left">
                      <input type="text" class="form-control search_opt" name="s_full_name" id="s_full_name" placeholder="Candidate Name">
                    </div> -->
 
                    <!-- <div class="form-group text-left">
                      <select class="form-control search_opt" name="s_status" id="s_status" >
                        <option value="">Select Status</option>          
                        <option value="1">Active</option>                       
                        <option value="2">Manual Hold</option>                       
                        <option value="3">Release</option>                       
                      </select>
                    </div> -->

                    <div class="form-group">
                      <input type="text" class="form-control s_datepicker search_opt" name="s_from_date" id="s_from_date" placeholder="From Date" value="<?php echo $s_from_date; ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                      <input type="text" class="form-control s_datepicker search_opt" name="s_to_date" id="s_to_date" placeholder="To Date" value="<?php echo $s_to_date; ?>" readonly>
                    </div>

                    <div class="form-group" style="width:auto;">
                      <button type="button" class="btn btn-primary" onclick="apply_search()">Search</button>
                      <button type="button" class="btn btn-success" onclick="apply_filter_with_export_to_excel()" >Export To Excel</button>
                      <button type="button" class="btn btn-danger" onclick="clear_search()">Clear</button>
                    </div>
                  </form>

                  <div class="table-responsive">
                  <table class="table table-bordered table-hover dataTables-example toggle_btn_tbl_outer" style="width:100%"> 
                  <thead> 
                  	<tr> 
                  		<th class="no-sort text-center nowrap">Sr. No.</th> 
                  		<th class="text-center nowrap">Registration No.</th> 
		                  <th class="text-center nowrap">Enrollment channel</th>
		                  <th class="text-center nowrap">Enrollment Date</th> 
		                  <th class="text-center nowrap">Candidate Full Name</th> 
		                  <th class="text-center nowrap">Gender</th> 
		                  <th class="text-center nowrap">DOB</th> 
		                  <th class="text-center nowrap">Age</th> 
		                  <th class="text-center nowrap">Mobile</th> 
		                  <th class="text-center nowrap">Email</th> 
		                  <th class="no-sort text-center nowrap">Eligibility</th>
		                  <th class="no-sort text-center nowrap">University Name</th>
		                  <th class="no-sort text-center nowrap">College Name</th>
		                  <th class="no-sort text-center nowrap">State of college/university (both pursuing & completed)</th>
		                  <th class="text-center nowrap">APAAR ID</th>
		                  <th class="text-center nowrap">AADHAR Number</th>
		                  <th class="text-center nowrap">Disability</th> 
		                  <th class="no-sort text-center nowrap">KYC Status</th> 
		                  <th class="no-sort text-center nowrap">Benchmark KYC Status</th> 
		                  <th class="text-center">Status</th> 
		                  <th class="text-center">Action</th> 
                		</tr>
                  </thead>	
										<tbody></tbody>
									</table>
									</div>									
								</div>                
                </div>
						</div>
					</div>
				</div>
				<?php $this->load->view('ncvet/admin/inc_footerbar_admin'); ?>			
			</div>
		</div>
		
		<?php $this->load->view('ncvet/inc_footer'); ?>

    <link href="<?php echo auto_version(base_url('assets/ncvet/css/plugins/dataTables/responsive.dataTables.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo auto_version(base_url('assets/ncvet/js/plugins/dataTables/dataTables.responsive.min.js')); ?>"></script>
		    
    <script language="javascript">

    	$('.s_datepicker').datepicker({ todayBtn: "linked", keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy-mm-dd", todayHighlight:true, clearBtn: true });

      $(document).ready(function()
			{
        var table = $('.dataTables-example').DataTable(
				{
          searching: true,
					"processing": false,
					"serverSide": true,
					// Enable horizontal scrolling
    			"scrollX": true,
    			// 'H' is for fixed header, 'f' is the search field
    			"dom": '<"top-controls sticky-header"lf>rt<"bottom-controls"ip>',
					"ajax": 
          {
						"url": '<?php echo site_url("ncvet/admin/candidate/get_candidates_data_ajax"); ?>',
						"type": "POST",	
            "data": function ( d ) 
						{ 
							d.s_qualification     = $("#s_qualification").val();
              d.s_qualification_state     = $("#s_qualification_state").val();
              d.s_benchmark_disability     = $("#s_benchmark_disability").val(); 
              d.s_kyc_status     = $("#s_kyc_status").val();
              d.s_benchmark_kyc_status     = $("#s_benchmark_kyc_status").val();
              d.s_gender     = $("#s_gender").val();
							d.s_reference  = $("#s_reference").val();
							d.s_regnumber  = $("#s_regnumber").val();
							d.s_full_name  = $("#s_full_name").val();
              d.s_status     = $("#s_status").val();
              d.s_from_date  = $("#s_from_date").val();
							d.s_to_date    = $("#s_to_date").val();
            },
            beforeSend: function() { $("#page_loader").show(); },
            complete: function() { $("#page_loader").hide(); },
					},
					"lengthMenu": [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]],
          "language": 
          {
						"lengthMenu": "_MENU_",
          },
					pageLength: 10,
					responsive: false,
          rowReorder: false,
					"columnDefs":
					[
					    {"targets": 'no-sort', "orderable": false, },
					    {"targets": [0], "className": "text-center"}
					    // {"targets": [18], "className": "nowrap"},
					    // {"targets": [19], "className": "nowrap text-center"}
					],
					"aaSorting": [],
					"stateSave": false,		
          'drawCallback': function(settings)
					{
						$('input[type=checkbox][data-toggle^=toggle]').bootstrapToggle();
					}          			
				});
      });
      
      function change_hold_release_status(cand_id, current_status)
      {
        $("#page_loader").show(); 

        var data = { 'cand_id': encodeURIComponent($.trim(cand_id)), 'status' : encodeURIComponent($.trim($("#toogle_id_"+cand_id).prop('checked'))) };	
        $.ajax(
        {
          type: "POST",
          url: "<?php echo site_url('ncvet/admin/candidate/change_hold_release_status'); ?>",
          data: data,
          success: function(response)
          {
            if(response.trim() != 'success') { $('.dataTables-example').DataTable().draw(); }

            if(current_status == 1)
            {
              $("#toggle_outer_"+cand_id+" .toggle-group label.btn-danger").html("Deactive");
            }
            else
            {
            	$("#toggle_outer_"+cand_id+" .toggle-group label.btn-danger").html("Deactive");
            }

            if($.trim($("#toogle_id_"+cand_id).prop('checked')) == 'true')
            {
              $("#toggle_outer_"+cand_id).prop("title", "Click to make it Deactivate");
            }
            else
            {
              $("#toggle_outer_"+cand_id).prop("title", "Click to make it Activate");
            }

            $("#page_loader").hide();               
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
            $('.dataTables-example').DataTable().draw();
          }
        });
      }

      function clear_search() { $(".search_opt").val(''); $('.s_datepicker').val("").datepicker("update"); $('.dataTables-example').DataTable().draw(); }
      function apply_search() { $("#form_action").val("");  $('.dataTables-example').DataTable().draw(); }

      function apply_filter_with_export_to_excel() { 
      	$("#tbl_search_value").val($('input[type="search"]').val());
      	$("#form_action").val("export");
      	$("#page_loader").show();
      	$("#search_form").submit();
      	setTimeout(function(){
      		apply_search();
      		$("#page_loader").hide();
      	},1000); 
      } 

    </script>
	</body>
</html>