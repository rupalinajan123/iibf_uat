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
						<h2>Batch Summary</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/dashboard_admin'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item active"> <strong>Batch Summary</strong></li>
						</ol>
					</div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox"> 
                <div class="ibox-content">
                  <form method="POST" action="<?php echo base_url("iibfbcbf/admin/Reports/get_batch_summary_data_ajax"); ?>" id="search_form" class="search_form_common_all" action="javascript:void(0)" autocomplete="off">
                  	<input type="hidden" name="tbl_search_value" id="tbl_search_value">
                  	<input type="hidden" name="form_action" id="form_action" value="">
                   	 
                  	<div class="form-group text-left"> 
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
                      <div class="form-group text-left">
                        <select class="form-control search_opt" name="s_centre" id="s_centre" >
                          <option value="">Select Centre</option>
                            <?php if(count($agency_centre_data) > 0)
                            {
                              foreach($agency_centre_data as $res)
                              { ?>
                                <option value="<?php echo $res['centre_id']; ?>"><?php echo $res['centre_name']." (".$res['city_name'].")"; ?></option>
                              <?php }
                            } ?>
                        </select>
                      </div>                    
                    <?php //} ?>

                    <div class="form-group text-left">
                      <select class="form-control search_opt" name="s_batch_type" id="s_batch_type" >
                        <option value="">Select Batch Type</option>
                        <option value="1">Basic</option> 
                        <option value="2">Advanced</option> 
                      </select>
                    </div>

                     <div class="form-group text-left">
                        <select class="form-control search_opt" name="s_inspector" id="s_inspector" >
                          <option value="">Select Inspector</option>
                            <?php if(count($inspector_data) > 0)
                            {
                              foreach($inspector_data as $res)
                              { ?>
                                <option value="<?php echo $res['inspector_id']; ?>"><?php echo $res['inspector_name'].")"; ?></option>
                              <?php }
                            } ?>
                        </select>
                      </div>   
                     
                    <div class="form-group">
                      <input type="text" class="form-control s_datepicker search_opt" name="s_from_date" id="s_from_date" placeholder="From Date" value="<?php echo $s_from_date; ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                      <input type="text" class="form-control s_datepicker search_opt" name="s_to_date" id="s_to_date" placeholder="To Date" value="<?php echo $s_to_date; ?>" readonly>
                    </div>
                    
                    <!-- <div class="form-group text-left">
                      <input type="text" class="form-control search_opt" name="s_batch_code" id="s_batch_code" placeholder="Batch Code">
                    </div> --> 

                    <div class="form-group" style="width:auto;">
                      <button type="button" class="btn btn-primary" onclick="apply_search()">Search</button>
                      <button type="button" class="btn btn-success" onclick="apply_filter_with_export_to_excel()" >Export To Excel</button>
                      <button type="button" class="btn btn-danger" onclick="clear_search()">Clear</button>
                    </div>
                  </form>

                  <div class="table-responsive">
										<table class="table table-bordered table-hover dataTables-example" style="width:100%">
											<thead>
												<tr> 
                          <th class="no-sort text-center nowrap" style="width:60px;">Sr. No.</th>
			                    <th class="text-center">Agency Code</th>
			                    <th class="text-center">Agency Name</th>
			                    <th class="text-center">Centre Name</th>
			                    <th class="text-center">Training Batch Code</th>
			                    <th class="text-center">Mode of Training</th>
			                    <th class="text-center">Course Details</th>
			                    <th class="text-center">Training Medium</th>
			                    <th class="text-center">Batch From Date</th>
			                    <th class="text-center">Batch To Date</th>
			                    <th class="text-center">Total Candidates</th>
			                    <th class="text-center">Batch Status</th>
			                    <th class="text-center">Batch Communication Remarks</th>
			                    <th class="text-center">Inspector Name</th>
			                    <th class="text-center">No. of times Inspected</th>
			                    <th class="text-center">Final Inspection Report Submission Date</th>
			                    <th class="text-center">Inspector Remarks</th>
			                    <th class="text-center">No. of Candidates on hold</th>

			                    <!-- <th class="text-center">Batch Submit Date and Time</th>
			                    <th class="text-center">Batch Approve Date and Time</th>			                    
			                    <th class="text-center">Batch Co-ordinator</th>
			                    <th class="text-center">Faculties</th>      
			                    <th class="text-center">Total Inspection Time(Minutes)</th>
			                    <th class="text-center">Average Inspection Time(Minutes)</th>		
			                    <th class="text-center">Total Registered Candidates</th>			                    
			                    <th class="text-center">Total Eligible Candidates </th>			                    
			                    <th class="text-center">Assessment/Rating(Poor)</th>
			                    <th class="text-center">Assessment/Rating(Average)</th>
			                    <th class="text-center">Assessment/Rating(Good)</th>
			                    <th class="text-center">Assessment/Rating(Excellent)</th> --> 

													<!-- <th class="text-center nowrap">Date</th> --> 
													<!-- <th class="text-center no-sort nowrap" style="width:90px;">Action</th> -->
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
						"url": '<?php echo site_url("iibfbcbf/admin/Reports/get_batch_summary_data_ajax"); ?>',
						"type": "POST",	
            "data": function ( d ) 
						{ 
							d.form_action = $("#form_action").val();
							d.s_agency = $("#s_agency").val();
              d.s_centre = $("#s_centre").val();
              d.s_batch_type = $("#s_batch_type").val();
              d.s_inspector = $("#s_inspector").val();
              d.s_from_date = $("#s_from_date").val();
							d.s_to_date = $("#s_to_date").val();
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
						/*{"targets": [0], "className": "text-center"},
						{"targets": [1], "className": "text-center"},*/ 
						{"targets": [0, 1, 4, 5, 6, 7, 8, 9, 10, 11, 14, 15, 17], "className": "text-center"}, 
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
	            url: "<?php echo site_url('iibfbcbf/admin/Reports/load_centre_data/'); ?>",
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
	            }/*,
	            error: function(jqXHR, textStatus, errorThrown) 
	            {
	              //$('#current_centre_status').val(status);
	              console.log('AJAX request failed: ' + errorThrown);
	              sweet_alert_error("Error occurred. Please try again.");
	              $("#page_loader").hide();

	            }*/
	          });
					}					
				});

      });  
 
      function clear_search() 
      { 
        $('.s_datepicker').val("").datepicker("update");
        $(".search_opt").val(''); 
        $('.dataTables-example').DataTable().draw(); 
        $("#form_action").val("");
      }
      
      function apply_search() { $("#form_action").val(""); $('.dataTables-example').DataTable().draw(); } 
      function apply_filter_with_export_to_excel(export_type = 'export') { 
      	$("#tbl_search_value").val($('input[type="search"]').val());
      	$("#form_action").val(export_type);
      	$("#page_loader").show();
      	$("#search_form").submit();
      	setTimeout(function(){
      		apply_search();
      		//$("#page_loader").hide();
      	},1000); 
      } 
    </script>
    <?php //$this->load->view('iibfbcbf/common/inc_bottom_script'); ?>	
	</body>
</html>