<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('supervision/inc_header'); ?>    
  </head>
	
	<body class="fixed-sidebar">
    <?php $this->load->view('supervision/common/inc_loader'); ?>
		
		<div id="wrapper">
      <?php $this->load->view('supervision/admin/inc_sidebar_admin'); ?>
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('supervision/admin/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-12">
						<h2>Observer Master</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('supervision/admin/dashboard_admin'); ?>">Dashboard</a></li>
              <li class="breadcrumb-item">Masters</li>
							<li class="breadcrumb-item active"> <strong>Observer Master</strong></li>
						</ol>
					</div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-title">
									
								</div>

								<div class="ibox-content">
                  <form method="POST" id="search_form" class="search_form_common_all" action="javascript:void(0)" autocomplete="off">
                    <div class="form-group">
                      <input type="text" class="form-control s_datepicker search_opt" name="s_from_date" id="s_from_date" placeholder="From Date" readonly>
                    </div>
                    
                    <div class="form-group">
                      <input type="text" class="form-control s_datepicker search_opt" name="s_to_date" id="s_to_date" placeholder="To Date" readonly>
                    </div>

                    <div class="form-group text-left">
                      <select class="form-control search_opt" name="s_candidate_status" id="s_candidate_status" >
                        <option value="">Select Status</option>
                        <option value="1">Active</option>                       
                        <option value="0">Inactive</option>               
                        <option value="2">In Review</option>               
                      </select>
                    </div>

                    <div class="form-group" style="width:auto;">
                      <button type="button" class="btn btn-primary" onclick="apply_search()">Search</button>
                      <button type="button" class="btn btn-danger" onclick="clear_search()">Clear</button>
                    </div>
                  </form>

                  <div class="table-responsive">
										<table class="table table-bordered table-hover dataTables-example" style="width:100%">
											<thead>
												<tr> 
                          <th class="no-sort text-center" style="width:40px;">Sr No.</th>
													<th class="text-center nowrap">Name</th> 
													<th class="text-center">Role</th>
													<th class="text-center nowrap">Email</th> 
													<th class="text-center nowrap">Mobile</th> 
													<th class="text-center nowrap">PDC Zone</th>
													<th class="text-center">Registration Date</th>
													<th class="text-center">Bank</th>
													<th class="text-center nowrap">Branch</th>
													
													<th class="text-center nowrap">Designation</th>
													<th class="text-center">Center</th> 
													
													<th class="text-center" style="width:80px;"> Status</th>
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
				<?php $this->load->view('supervision/admin/inc_footerbar_admin'); ?>			
			</div>
		</div>
		
    <div class="modal inmodal fade" id="change_pass_modal" tabindex="-1" role="dialog"  aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" id="change_pass_modal_content"></div>
      </div>
		</div>

		<?php $this->load->view('supervision/inc_footer'); ?>
    <?php $this->load->view('supervision/common/inc_common_validation_all'); ?>

    <link href="<?php echo auto_version(base_url('assets/supervision/css/plugins/dataTables/responsive.dataTables.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo auto_version(base_url('assets/supervision/js/plugins/dataTables/dataTables.responsive.min.js')); ?>"></script>
		    
    <script language="javascript">
      $('.s_datepicker').datepicker({ todayBtn: "linked", keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy-mm-dd", todayHighlight:true, clearBtn: true });

      function get_modal_change_password_data(enc_id)
			{
				parameters="enc_id="+enc_id;
				
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('supervision/admin/candidate/get_modal_change_password_data'); ?>",
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
						"url": '<?php echo site_url("supervision/admin/candidate/get_candidate_data_ajax"); ?>',
						"type": "POST",	
            "data": function ( d ) 
						{
							d.s_from_date = $("#s_from_date").val();
							d.s_to_date = $("#s_to_date").val();
							d.s_candidate_status = $("#s_candidate_status").val();
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
						//{"targets": [2], "className": "wrap"},
						//{"targets": [4], "className": "wrap"},
						{"targets": [11], "className": "text-center"},
						{"targets": [12], "className": "text-center"},
						/* {"targets": [4], "className": "text-center"},
            {"targets": [5], "className": "text-right nowrap"},
            {"targets": [7], "className": "text-center"}, */
						/* {"targets": [1], "className": "hide"},
						{"targets": [3], "className": "hide"}, */
					],
					"aaSorting": [],
					"stateSave": false,		          			
				});
      }); 
      
      function clear_search() 
      { 
        $(".search_opt").val(''); 
        $('.s_datepicker').val("").datepicker("update");
        $('.dataTables-example').DataTable().draw(); 
      }

      function apply_search() { $('.dataTables-example').DataTable().draw(); }
    </script>
    <?php $this->load->view('supervision/common/inc_bottom_script'); ?>	
	</body>
</html>