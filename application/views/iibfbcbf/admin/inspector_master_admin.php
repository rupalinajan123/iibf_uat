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
						<h2>Inspector Master</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/dashboard_admin'); ?>">Dashboard</a></li>
              <li class="breadcrumb-item">Masters</li>
							<li class="breadcrumb-item active"> <strong>Inspector Master</strong></li>
						</ol>
					</div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
                <div class="ibox-title">
									<div class="ibox-tools">
                    <form method="POST" action="<?php echo base_url("iibfbcbf/admin/inspector_master/get_inspector_data_ajax"); ?>" id="search_form" class="search_form" autocomplete="off">
                      <input type="hidden" name="tbl_search_value" id="tbl_search_value">
                      <input type="hidden" name="form_action" id="form_action" value="">   
                      <a href="<?php echo site_url('iibfbcbf/admin/inspector_master/add_inspector'); ?>" class="btn btn-primary custom_right_add_new_btn">Add Inspector</a>
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
													<th class="text-center">Inspector Name</th>
													<th class="text-center">Mobile Number</th>
													<th class="text-center nowrap">Email Id</th>
													<th class="text-center">Inspector Designation</th>
													<th class="text-center nowrap">Username</th>
													<th class="no-sort text-center nowrap">Password</th>
													<th class="text-center">Assigned Centres(City)</th>
													<th class="text-center" style="width:80px;">Status</th>
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
				<?php $this->load->view('iibfbcbf/admin/inc_footerbar_admin'); ?>			
			</div>
		</div>

    <div class="modal inmodal fade" id="change_pass_modal" tabindex="-1" role="dialog"  aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" id="change_pass_modal_content"></div>
      </div>
		</div>
		
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>
    <?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>
		    
    <script language="javascript">
      function apply_filter_with_export_to_excel(export_type = 'export') 
      { 
      	$("#tbl_search_value").val($('input[type="search"]').val());
        $("#form_action").val(export_type);
      	$("#page_loader").show();
      	$("#search_form").submit();
      	setTimeout(function()
        {
          $('#page_loader').hide();
        },2000); 
      }

      function get_modal_change_password_data(enc_inspector_id)
			{
				parameters="enc_inspector_id="+enc_inspector_id;
				
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('iibfbcbf/admin/inspector_master/get_modal_change_password_data'); ?>",
					data: parameters,
					cache: false,          
					success:function(data)
					{
						if(data == "error")
						{
							sweet_alert_error("Error occurred. Please try again.")
						}
						else
						{
							$("#change_pass_modal_content").html(data);
							$("#change_pass_modal").modal('show');
						}
					},
          error: function(jqXHR, textStatus, errorThrown) 
          {
            console.log('AJAX request failed: ' + errorThrown);
            sweet_alert_error("Error occurred. Please try again.")
            $('#page_loader').hide();
          }
				});
			}

      $(document).ready(function()
			{
				var table = $('.dataTables-example').DataTable(
				{
          searching: true,
					"processing": false,
					"serverSide": true,
					"ajax": 
          {
						"url": '<?php echo site_url("iibfbcbf/admin/inspector_master/get_inspector_data_ajax"); ?>',
						"type": "POST",	
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
						{"targets": [8], "className": "text-center"},
						{"targets": [9], "className": "text-center"},
						/* {"targets": [3], "className": "wrap"},
						{"targets": [5], "className": "wrap"},
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
    </script>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>	
	</body>
</html>