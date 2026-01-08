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
      <?php $this->load->view('iibfbcbf/inspector/inc_sidebar_inspector'); ?>
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('iibfbcbf/inspector/inc_topbar_inspector'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-12">
						<h2>Batch List</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/inspector/dashboard_inspector'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item active"> <strong>Batch List</strong></li>
						</ol>
					</div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
                <div class="ibox-content">
                  <form method="POST" id="search_form" class="search_form_common_all" action="javascript:void(0)" autocomplete="off">
                  	<div class="form-group text-left" style="width:auto; min-width:200px;">
                      <select class="form-control search_opt" name="s_agency" id="s_agency" >
                        	<option value="">Select Agency</option>
                          <?php if(count($agency_data) > 0)
                          {
                            foreach($agency_data as $res)
                            { ?>
                              <option value="<?php echo $res['agency_id']; ?>"><?php echo $res['agency_name']; ?></option>
                            <?php }
                          } ?>
                      </select>
                    </div>        

                    <?php //if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency') { ?>
                      <div class="form-group text-left" style="width:auto; min-width:200px;">
                        <select class="form-control search_opt" name="s_centre" id="s_centre" >
                          <option value="">Select Centre</option>
                            <?php if(count($agency_centre_data) > 0)
                            {
                              foreach($agency_centre_data as $res)
                              { ?>
                                <option value="<?php echo $res['centre_id']; ?>"><?php echo $res['centre_name']." (".$res['centre_username']." - ".$res['city_name'].")"; ?></option>
                              <?php }
                            } ?>
                        </select>
                      </div>                    
                    <?php //} ?>

                    <div class="form-group">
                      <input type="text" class="form-control s_datepicker search_opt" name="s_from_date" id="s_from_date" placeholder="From Date" readonly value="<?php if($search_start_date != "") { echo $search_start_date; } ?>">
                    </div>
                    
                    <div class="form-group">
                      <input type="text" class="form-control s_datepicker search_opt" name="s_to_date" id="s_to_date" placeholder="To Date" readonly>
                    </div>
                    
                    <div class="form-group text-left">
                      <input type="text" class="form-control search_opt" name="s_batch_code" id="s_batch_code" placeholder="Batch Code">
                    </div>

                    <div class="form-group text-left">
                      <select class="form-control search_opt" name="s_batch_status" id="s_batch_status" >
                        <option value="">Select Batch Status</option>
                        <option value="0">In Review</option>                       
                        <option value="1">Final Review</option>                       
                        <option value="2">Batch Error</option>                       
                        <option value="3">Go Ahead</option>                       
                        <option value="4">Hold</option>                       
                        <option value="5">Rejected</option>                       
                        <option value="6">Re-Submitted</option>                       
                        <option value="7">Cancelled</option>                       
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
                          <th class="no-sort text-center nowrap" style="width:60px;">Sr. No.</th>
													<th class="text-center nowrap">Agency Name</th>
													<th class="text-center nowrap">Centre Name</th>
													<th class="text-center nowrap">Batch Code</th>
													<th class="text-center nowrap">Batch Type</th>
													<th class="text-center nowrap">Batch Date</th>
													<th class="text-center nowrap">Batch Time</th>
													<th class="text-center">Candidate Capacity</th>
													<th class="text-center nowrap">Batch Created</th>
													<th class="text-center nowrap" style="width:80px;">Status</th>
													<th class="text-center no-sort nowrap" style="width:90px;">Action</th>
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
				<?php $this->load->view('iibfbcbf/inspector/inc_footerbar_inspector'); ?>			
			</div>
		</div>
		
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>
    
    <link href="<?php echo auto_version(base_url('assets/iibfbcbf/css/plugins/dataTables/responsive.dataTables.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo auto_version(base_url('assets/iibfbcbf/js/plugins/dataTables/dataTables.responsive.min.js')); ?>"></script>
    
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
						"url": '<?php echo site_url("iibfbcbf/inspector/training_batches_inspector/get_training_batches_data_ajax"); ?>',
						"type": "POST",	
            "data": function ( d ) 
						{
							d.s_agency = $("#s_agency").val();
              <?php //if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency') { ?> d.s_centre = $("#s_centre").val(); <?php //} ?>
              d.s_from_date = $("#s_from_date").val();
							d.s_to_date = $("#s_to_date").val();
							d.s_batch_code = $("#s_batch_code").val();
              d.s_batch_status = $("#s_batch_status").val();
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
						{"targets": [4], "className": "text-center"},
						{"targets": [5], "className": "text-center"},
						{"targets": [6], "className": "text-center"},
						{"targets": [8], "className": "text-center"},
					],
					"aaSorting": [],
					"stateSave": false,		          			
				});


				$("#s_agency").change(function(e)
				{
					var s_agency = $("#s_agency").val();
					if(s_agency != "" && s_agency > 0){ 
						$("#page_loader").show(); 
	          $.ajax(
	          {
	            type: "POST",
	            url: "<?php echo site_url('iibfbcbf/inspector/training_batches_inspector/load_centre_data/'); ?>",
	            data: {s_agency:s_agency},
	            /*async: false,
	            cache : false,*/
	            dataType: 'JSON',
	            success: function(data)
	            {
	            	//alert(data); 
	              if($.trim(data.flag) == 'success')
	              { 
	                $("#s_centre").html(data.response); 
	              } 
	              // data.response;
	              $("#page_loader").hide(); 
	            },
	            error: function(jqXHR, textStatus, errorThrown) 
	            {
	              //$('#current_centre_status').val(status);
	              console.log('AJAX request failed: ' + errorThrown);
	              sweet_alert_error("Error occurred. Please try again.");
	              $("#page_loader").hide();
	            }
	          });
					}					
				});

      });   
      
      function clear_search() 
      { 
        <?php if($search_start_date == "") 
        { ?>
          $('.s_datepicker').val("").datepicker("update");
          $(".search_opt").val(''); $('.dataTables-example').DataTable().draw();
        <?php }
        else
        { ?>
          window.location.href = "<?php echo site_url('iibfbcbf/inspector/training_batches_inspector'); ?>";
        <?php } ?>
      }
      function apply_search() { $('.dataTables-example').DataTable().draw(); }
    </script>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>	
	</body>
</html>