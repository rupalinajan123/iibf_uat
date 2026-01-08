<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('ncvet/kyc/inc_header'); ?>    

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

  </head>
	<body class="fixed-sidebar">
  <?php $this->load->view('ncvet/common/inc_loader'); ?>
		
		<div id="wrapper">
    <?php $this->load->view('ncvet/kyc/inc_sidebar_admin'); ?>		
			<div id="page-wrapper" class="gray-bg">				
      <?php $this->load->view('ncvet/kyc/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2><?php echo $page_title; ?></h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('ncvet/kyc/kyc_dashboard'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item active"> <strong><?php echo $page_title; ?></strong></li>
						</ol>
					</div>
					<div class="col-lg-2"> </div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
                <div class="ibox-content">
                   <form method="POST" action="<?php echo base_url("ncvet/kyc/kyc_all/".$action_name."/".$kyc_status_list."/".$page_url); ?>" id="search_form" class="search_form_common_all" autocomplete="off">
                  	<input type="hidden" name="tbl_search_value" id="tbl_search_value">
                  	<input type="hidden" name="form_action" id="form_action" value=""> 

                  	<div class="form-group text-left"> 
                      <input type="text" class="form-control search_opt" name="enrollment_number" id="enrollment_number" placeholder="Enrollment Number">
                    </div>        

                    <div class="form-group">
                      <input type="text" class="form-control s_datepicker search_opt" name="s_from_date" id="s_from_date" placeholder="From Date" readonly>
                    </div>
                    
                    <div class="form-group">
                      <input type="text" class="form-control s_datepicker search_opt" name="s_to_date" id="s_to_date" placeholder="To Date" readonly>
                    </div> 

                    <div class="form-group text-left"> 
                      <select class="form-control search_opt" name="enrollment_channel" id="enrollment_channel" >
                        	<option value="">Select Enrollment Channel</option>
                          <option value="Website">IIBF Website</option> 
                        	<option value="BFSI">BFSI</option> 
                      </select>
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
													<th class="text-center nowrap">Enrollment No.</th> 
													<th class="text-center nowrap">Enrollment Channel</th>
													<th class="text-center nowrap">Name</th>
													<th class="text-center nowrap">Birth Date</th>
													<th class="text-center nowrap">Aadhar No.</th>
													<th class="text-center nowrap">APAAR ID/ABC ID</th>
													<th class="text-center nowrap">Eligibility</th>
													<th class="text-center nowrap">Photo</th>
													<th class="text-center nowrap">Signature</th>
													<th class="text-center nowrap">APAAR ID/ABC ID File</th>
													<th class="text-center nowrap">Aadhar Card File</th>
													<th class="text-center nowrap">Qualification Certificate</th>
													<th class="text-center nowrap">Institute ID</th>
													<th class="text-center nowrap">Declaration</th>
													<th class="text-center nowrap">Experience Certificate</th>
													<th class="text-center nowrap">Visually Impaired</th>
													<th class="text-center nowrap">Orthopedically Handicapped</th>
													<th class="text-center nowrap">Cerebral Palsy</th>
													<th class="text-center nowrap">KYC Status</th>
													<!-- <th class="text-center no-sort nowrap" style="width:90px;">Action</th> -->
												</tr>
											</thead>
											
											<tbody></tbody>
										</table>
									</div>									
									<!-- </div> -->									
								</div>               
							</div>
						</div>
					</div>
				</div>				
				
				<?php $this->load->view('ncvet/kyc/inc_footerbar_admin'); ?>	
			</div>
		</div>
		<?php $this->load->view('ncvet/kyc/inc_footer'); ?>

    <link href="<?php echo auto_version(base_url('assets/ncvet/css/plugins/dataTables/responsive.dataTables.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo auto_version(base_url('assets/ncvet/js/plugins/dataTables/dataTables.responsive.min.js')); ?>"></script>
		    
    <script language="javascript">

    	 $('.s_datepicker').datepicker({ todayBtn: "linked", keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy-mm-dd", todayHighlight:true, clearBtn: true });

      $(document).ready(function()
			{
				var table = $('.dataTables-example').DataTable(
				{
          searching: true,
          /*scrollX: true,
    			scrollY: '400px',
    			scrollCollapse: true,*/
    			scrollX: true,
					"processing": false,
					"serverSide": true,
					"dom": '<"top-controls sticky-header"lf>rt<"bottom-controls"ip>',
					"ajax": 
          {
						"url": '<?php echo site_url("ncvet/kyc/kyc_all/".$action_name."/".$kyc_status_list."/".$page_url); ?>',
						"type": "POST",	
            "data": function ( d ) 
						{
							//d.s_module_type = $("#s_module_type").val();
							d.form_action = $("#form_action").val();
							d.enrollment_number = $("#enrollment_number").val();
							d.s_from_date = $("#s_from_date").val();
							d.s_to_date = $("#s_to_date").val();
							d.enrollment_channel = $("#enrollment_channel").val();
            },
            beforeSend: function() { $("#page_loader").show(); },
            complete: function() { $("#page_loader").hide(); },
					},
					"lengthMenu": [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]],
          "language": 
          {
						"lengthMenu": "_MENU_",
          },
          //"dom": '<"top"lf><"clear"><i>rt<"bottom row"<"col-sm-12 col-md-5" and i><"col-sm-12 col-md-7" and p>><"clear">',
					pageLength: 10,
					responsive: false,
          rowReorder: false,
					"columnDefs": 
					[
						{"targets": 'no-sort', "orderable": false, },
						{"targets": [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19], "className": "text-center"},
            /* {"targets": [4], "className": "text-center"},
            {"targets": [5], "className": "text-right nowrap"},
            {"targets": [7], "className": "text-center"}, */
						/* {"targets": [1], "className": "hide"},
						{"targets": [3], "className": "hide"}, */
					],
					"aaSorting": [],
					"stateSave": false,		
          'drawCallback': function(settings)
					{
						$('input[type=checkbox][data-toggle^=toggle]').bootstrapToggle();
					}		          			
				});
      });   

      function clear_search() 
      {
        $('.s_datepicker').val("").datepicker("update"); 
        $(".search_opt").val(''); 
        $("#form_action").val(""); 
        $('.dataTables-example').DataTable().draw(); 
      }
      function apply_search() { 
      	$("#form_action").val(""); 
      	$('.dataTables-example').DataTable().draw(); 
      } 
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

		<?php $this->load->view('ncvet/kyc/common/inc_bottom_script'); ?>	
	</body>
</html>