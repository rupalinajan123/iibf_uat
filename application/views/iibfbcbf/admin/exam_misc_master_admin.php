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
      <?php $this->load->view('iibfbcbf/admin/inc_sidebar_admin'); ?>
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('iibfbcbf/admin/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-12">
						<h2>Exam MISC Master</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/dashboard_admin'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item">Masters</li>
							<li class="breadcrumb-item active"><strong>Exam MISC Master</strong></li>
						</ol>
					</div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
                <div class="ibox-content">
                  <form method="POST" action="<?php echo base_url("iibfbcbf/admin/masters_admin/get_exam_misc_master_data_ajax"); ?>" id="search_form" class="search_form_common_all" autocomplete="off">
                    <input type="hidden" name="tbl_search_value" id="tbl_search_value">
                    <input type="hidden" name="form_action" id="form_action" value="">

                    <div class="form-group text-left">
                      <select class="form-control search_opt" name="s_exam_type" id="s_exam_type" >
                        <option value="">Select Exam Type</option>
                        <option value="1">Basic</option>
                        <option value="2">Advanced</option>
                      </select>
                    </div>
                    
                    <div class="form-group text-left">
                      <select class="form-control search_opt" name="s_exam_code" id="s_exam_code" >
                        <option value="">Select Exam Code</option>
                        <option value="1037">1037</option>
                        <option value="1038">1038</option>
                        <option value="1039">1039</option>
                        <option value="1040">1040</option>
                        <option value="1041">1041</option>
                        <option value="1042">1042</option>
                        <option value="1057">1057</option>
                      </select>
                    </div>
                    
                    <div class="form-group">
                      <input type="text" class="form-control search_opt" name="s_exam_period" id="s_exam_period" placeholder="Exam Period" value="">
                    </div>

                    <div class="form-group" style="width:auto;">
                      <button type="button" class="btn btn-primary" onclick="apply_search()">Search</button>
                      <button type="button" class="btn btn-success custom_right_add_new_btn" onclick="apply_filter_with_export_to_excel()" >Export To Excel</button>
                      <button type="button" class="btn btn-danger" onclick="clear_search()">Clear</button>
                    </div>
                  </form>

                  <div class="table-responsive">
										<table class="table table-bordered table-hover dataTables-example" style="width:100%">
											<thead>
												<tr> 
                          <th class="no-sort text-center" style="width:60px;">Sr. No.</th>
													<th class="text-center nowrap">Exam Name</th>
													<th class="text-center wrap_space">Exam Type</th>
													<th class="text-center wrap_space">Exam Code</th>
													<th class="text-center wrap_space">Exam Period</th>
													<th class="text-center wrap_space">Exam Month</th>
													<th class="text-center wrap_space">TRG Value</th>
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
				<?php $this->load->view('iibfbcbf/admin/inc_footerbar_admin'); ?>			
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
						"url": '<?php echo site_url("iibfbcbf/admin/masters_admin/get_exam_misc_master_data_ajax"); ?>',
						"type": "POST",	
            "data": function ( d ) 
						{ 
              d.s_exam_type = $("#s_exam_type").val();
              d.s_exam_code = $("#s_exam_code").val();
              d.s_exam_period = $("#s_exam_period").val();
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
						{"targets": [3], "className": "text-center"},
						{"targets": [4], "className": "text-center"},
						{"targets": [5], "className": "text-center"},
						{"targets": [6], "className": "text-center"},
					],
					"aaSorting": [],
					"stateSave": false,		          			
				});
      });

      function clear_search() 
      { 
        $(".search_opt").val(''); 
        $('.dataTables-example').DataTable().draw(); 
      }
      
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