<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('iibfbcbf/inc_header'); ?>    
  </head>
	
	<body class="fixed-sidebar">
    <?php $this->load->view('iibfbcbf/common/inc_loader'); ?>
		
		<div id="wrapper">
      <?php $this->load->view('iibfbcbf/agency/inc_sidebar_agency'); ?>
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('iibfbcbf/agency/inc_topbar_agency'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-12">
						<h2>CSC Exam Date Master</h2>
						<ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item">Exam Masters</li>
							<li class="breadcrumb-item active"><strong>CSC Exam Date Master</strong></li>
						</ol>
					</div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
                <div class="ibox-title">
									<div class="ibox-tools">
                    <form method="POST" action="<?php echo base_url("iibfbcbf/agency/masters_agency/get_csc_exam_date_master_data_ajax"); ?>" id="search_form" class="search_form" autocomplete="off">
                      <input type="hidden" name="tbl_search_value" id="tbl_search_value">
                      <input type="hidden" name="form_action" id="form_action" value="">   
                      <button type="button" class="btn btn-success custom_right_add_new_btn" onclick="apply_filter_with_export_to_excel()" >Export To Excel</button>
                    </form>
                  </div>
                </div>

                <div class="ibox-content">
                  <div class="table-responsive">
										<table class="table table-bordered table-hover dataTables-example" style="width:100%">
											<thead>
												<tr> 
                          <th class="no-sort text-center" style="width:60px;">Sr. No.</th>
													<th class="text-center nowrap">Exam Date</th>
													<th class="text-center nowrap">Time</th>
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
				<?php $this->load->view('iibfbcbf/agency/inc_footerbar_agency'); ?>				
			</div>
		</div>
		
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>

    <link href="<?php echo auto_version(base_url('assets/iibfbcbf/css/plugins/dataTables/responsive.dataTables.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo auto_version(base_url('assets/iibfbcbf/js/plugins/dataTables/dataTables.responsive.min.js')); ?>"></script>
		    
    <script language="javascript">
      $(document).ready(function()
			{
				var table = $('.dataTables-example').DataTable(
				{
          searching: true,
					"processing": false,
					"serverSide": true,
					"ajax": 
          {
						"url": '<?php echo site_url("iibfbcbf/agency/masters_agency/get_csc_exam_date_master_data_ajax"); ?>',
						"type": "POST",	
            "data": function ( d ) 
						{ 

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
						{"targets": [2], "className": "text-center"},
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
    </script>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>	
	</body>
</html>