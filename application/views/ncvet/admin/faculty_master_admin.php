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
						<h2>Faculty Master</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/dashboard_admin'); ?>">Dashboard</a></li>
              <li class="breadcrumb-item">Masters</li>
							<li class="breadcrumb-item active"> <strong>Faculty Master</strong></li>
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
                      <select class="form-control search_opt" name="s_agency" id="s_agency" onchange="get_centre_data(this.value)">
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
                    
                    <div class="form-group text-left" style="width:auto; min-width:200px;">
                      <select class="form-control search_opt" name="s_centre" id="s_centre" >
                        <option value="">Select Centre</option>
                        <option value="0">Blank Centre</option>
                        <?php if(count($agency_centre_data) > 0)
                        {
                          foreach($agency_centre_data as $res)
                          { ?>
                            <option value="<?php echo $res['centre_id']; ?>"><?php echo $res['centre_name']." (".$res['centre_username'].' - '.$res['city_name'].")"; ?></option>
                          <?php }
                        } ?>
                      </select>
                    </div>

                    <div class="form-group">
                      <input type="text" class="form-control s_datepicker search_opt" name="s_from_date" id="s_from_date" placeholder="From Date" readonly>
                    </div>
                    
                    <div class="form-group">
                      <input type="text" class="form-control s_datepicker search_opt" name="s_to_date" id="s_to_date" placeholder="To Date" readonly>
                    </div>

                    <div class="form-group text-left">
                      <select class="form-control search_opt" name="s_added_by" id="s_added_by" >
                        <option value="">Select Added By</option>                      
                        <option value="1">Centre</option>                       
                        <option value="2">Agency</option>                       
                      </select>
                    </div>

                    <div class="form-group text-left">
                      <select class="form-control search_opt" name="s_faculty_status" id="s_faculty_status" >
                        <option value="">Select Faculty Status</option>
                        <option value="0">Inactive</option>                       
                        <option value="1">Active</option>                       
                        <option value="2">In Review</option>                       
                        <option value="3">Re-Submitted </option>                       
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
                          <th class="no-sort text-center" style="width:60px;">Sr. No.</th>
													<th class="text-center nowrap">Agency Name</th>
													<th class="text-center nowrap">Centre Name</th>
													<th class="text-center nowrap">Faculty<br>Number</th>
													<th class="text-center nowrap">Faculty Name</th>
													<th class="text-center nowrap">DOB</th>
													<th class="text-center nowrap">Location</th>
													<th class="text-center nowrap">PAN No.</th>
													<th class="text-center">Language Known</th>
													<th class="text-center">Current Batches</th>
                          <th class="text-center">Added By</th>
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
						"url": '<?php echo site_url("iibfbcbf/admin/faculty/get_faculty_data_ajax"); ?>',
						"type": "POST",	
            "data": function ( d ) 
						{
							d.s_agency = $("#s_agency").val();
              d.s_centre = $("#s_centre").val();
              d.s_from_date = $("#s_from_date").val();
							d.s_to_date = $("#s_to_date").val();
              d.s_added_by = $("#s_added_by").val();
              d.s_faculty_status = $("#s_faculty_status").val();
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
						{"targets": [3], "className": "text-center wrap"},
						{"targets": [4], "className": "wrap"},
						{"targets": [5], "className": "nowrap"},
						{"targets": [6], "className": "wrap"},
						{"targets": [10], "className": "text-center"},
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

      function get_centre_data(agency_id)
      {
        var s_agency = agency_id;        
        $("#page_loader").show(); 
        $.ajax(
        {
          type: "POST",
          url: "<?php echo site_url('iibfbcbf/admin/faculty/load_centre_data/'); ?>",
          data: {s_agency:s_agency},
          dataType: 'JSON',
          success: function(data)
          {
            if($.trim(data.flag) == 'success')
            { 
              $("#s_centre").html(data.response); 
            } 
            
            $("#page_loader").hide(); 
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
            console.log('AJAX request failed: ' + errorThrown);
            sweet_alert_error("Error occurred. Please try again.");
            $("#page_loader").hide();
          }
        });
      }
      
      function clear_search() 
      { 
        $(".search_opt").val(''); 
        get_centre_data('');
        $('.s_datepicker').val("").datepicker("update");
        $('.dataTables-example').DataTable().draw(); 
      }
      
      function apply_search() { $('.dataTables-example').DataTable().draw(); }
    </script>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>	
	</body>
</html>