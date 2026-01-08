<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('apabi_india/inc_header'); ?>    
  </head>
	
	<body class="fixed-sidebar">
    <?php $this->load->view('apabi_india/inc_loader'); ?>
		
		<div id="wrapper">
      <?php $this->load->view('apabi_india_admin/inc_sidebar_admin'); ?>
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('apabi_india_admin/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-12">
						<h2>Registration Data</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('apabi_india_admin/dashboard_admin'); ?>">Dashboard</a></li>
              <li class="breadcrumb-item active"> <strong>Registration Data</strong></li>
						</ol>
					</div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
                <div class="ibox-title text-right">
                  <form method="POST" action="<?php echo base_url("apabi_india_admin/apabi_admin_registrations/get_registration_data_ajax"); ?>" id="search_form" class="" autocomplete="off">
                    <input type="hidden" name="form_action" id="form_action" value=""> 
                    <input type="hidden" name="tbl_search_value" id="tbl_search_value">
                    <button type="button" class="btn btn-success" onclick="apply_filter_with_export_to_excel()" >Export To Excel</button>
                  </form>
                </div>

                <div class="ibox-content">
                  <?php /* <form method="POST" id="search_form" class="search_form_common_all" action="javascript:void(0)" autocomplete="off">
                    <div class="form-group">
                      <input type="text" class="form-control s_datepicker search_opt" name="s_from_date" id="s_from_date" placeholder="From Date" readonly>
                    </div>
                    
                    <div class="form-group">
                      <input type="text" class="form-control s_datepicker search_opt" name="s_to_date" id="s_to_date" placeholder="To Date" readonly>
                    </div>

                    <div class="form-group text-left">
                      <select class="form-control search_opt" name="s_centre_status" id="s_centre_status" >
                        <option value="">Select Centre Status</option>
                        <option value="0">Inactive</option>                       
                        <option value="1">Active</option>                       
                        <option value="2">In Review</option>                       
                        <option value="3">Re-Submitted</option>  
                      </select>
                    </div>

                    <div class="form-group" style="width:auto;">
                      <button type="button" class="btn btn-primary" onclick="apply_search()">Search</button>
                      <button type="button" class="btn btn-danger" onclick="clear_search()">Clear</button>
                    </div>
                  </form> */ ?>
                  
                  <div class="table-responsive">
										<table class="table table-bordered table-hover dataTables-example" style="width:100%">
											<thead>
												<tr> 
                          <th class="no-sort text-center" style="width:40px;">Sr No.</th>
                          <th class="text-center nowrap">Organization Name</th>
													<th class="text-center nowrap">APABI Code</th>
													<th class="text-center nowrap">Name</th>
													<th class="text-center nowrap">Designation</th>
													<th class="text-center nowrap">Email</th>
													<th class="text-center nowrap">Mobile</th>
													<th class="text-center nowrap">Registration Date</th>
													<th class="text-center no-sort" style="width:90px;">Action</th>
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
				<?php $this->load->view('apabi_india_admin/inc_footerbar_admin'); ?>			
			</div>
		</div>
		
		<?php $this->load->view('apabi_india/inc_footer'); ?>

    <link href="<?php echo auto_version(base_url('assets/apabi_india/css/plugins/dataTables/responsive.dataTables.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo auto_version(base_url('assets/apabi_india/js/plugins/dataTables/dataTables.responsive.min.js')); ?>"></script>
		    
    <script language="javascript">
      $('.s_datepicker').datepicker({ todayBtn: "linked", keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy-mm-dd", todayHighlight:true, clearBtn: true });

      $(document).ready(function()
			{
				var table = $('.dataTables-example').DataTable(
				{
          searching: true,
					"processing": false,
					"serverSide": true,
					"ajax": 
          {
						"url": '<?php echo site_url("apabi_india_admin/apabi_admin_registrations/get_registration_data_ajax"); ?>',
						"type": "POST",	
            "data": function ( d ) 
						{
              /* d.form_action = $("#form_action").val();
							d.s_agency = $("#s_agency").val();
              d.s_from_date = $("#s_from_date").val();
							d.s_to_date = $("#s_to_date").val();
              d.s_centre_status = $("#s_centre_status").val(); */
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
					responsive: true,
          rowReorder: false,
					"columnDefs": 
					[
						{"targets": 'no-sort', "orderable": false, },
						{"targets": [0], "className": "text-center"},
						{"targets": [2], "className": "wrap"},
						{"targets": [7], "className": "text-right"},
						{"targets": [8], "className": "text-center"},
						/* {"targets": [9], "className": "nowrap"},
						{"targets": [10], "className": "text-center"},
						{"targets": [11], "className": "text-center"},
						{"targets": [4], "className": "text-center"},
            {"targets": [5], "className": "text-right nowrap"},
            {"targets": [7], "className": "text-center"}, */
						/* {"targets": [1], "className": "hide"},
						{"targets": [3], "className": "hide"}, */
					],
					"aaSorting": [],
					"stateSave": false,		          			
				});
      });
      
      function apply_search() 
      {
        $("#form_action").val(""); 
        $('.dataTables-example').DataTable().draw(); 
      } 

      function apply_filter_with_export_to_excel(export_type = 'export') 
      { 
      	$("#tbl_search_value").val($('input[type="search"]').val());
        $("#form_action").val(export_type);
      	$("#page_loader").show();
      	$("#search_form").submit();
      	setTimeout(function()
        {
      		apply_search();
      	},1000);
      } 
      
      /* function clear_search() 
      { 
        $(".search_opt").val(''); 
        $('.s_datepicker').val("").datepicker("update");
        $('.dataTables-example').DataTable().draw(); 
      } 
      
      function apply_search() { $('.dataTables-example').DataTable().draw(); }*/
    </script>
    <?php $this->load->view('apabi_india/inc_bottom_script'); ?>	
	</body>
</html>