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
				
        <?php $disp_title = 'All Candidate List'; 
        if($enc_batch_id != '0') { $disp_title = 'Candidate List'; } ?>

				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-12">
						<h2><?php echo $disp_title; ?></h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/dashboard_admin'); ?>">Dashboard</a></li>
							<?php if($enc_batch_id != '0' || (isset($batch_data) && count($batch_data) > 0))  { ?><li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/training_batches'); ?>">Training Batches</a></li><?php } ?>
							<li class="breadcrumb-item active"> <strong><?php echo $disp_title; ?></strong></li>
						</ol>
					</div>
				</div>
        
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
                <?php if($enc_batch_id != '0' || (isset($batch_data) && count($batch_data) > 0))  { ?>
                  <div class="ibox-title">
                    <div class="ibox-tools"> 
                      <?php                       
                        if($batch_data[0]['batch_status'] == 3 && date('Y-m-d') <= $batch_data[0]['batch_end_date'] && $batch_candidate_count < $batch_data[0]['total_candidates']) 
                        { ?>
                          <a href="<?php echo site_url('iibfbcbf/admin/batch_candidates/add_candidates/'.$enc_batch_id); ?>" class="btn btn-primary custom_right_add_new_btn">Add Candidate</a>                    
                      <?php } ?>
                      <a href="<?php echo site_url('iibfbcbf/admin/training_batches'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a> 
                    </div>
                  </div>   
                <?php } ?>
                
                <div class="ibox-content">
                	<form method="POST" id="search_form" class="search_form_common_all" action="javascript:void(0)" autocomplete="off">
                  	
                		<?php if($enc_batch_id == "0"){ ?>
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

                    <?php //if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency') { ?>
                      <div class="form-group text-left" style="width:auto; min-width:200px;">
                        <select class="form-control search_opt" name="s_centre" id="s_centre" >
                          <option value="">Select Centre</option>
                            <?php if(count($agency_centre_data) > 0)
                            {
                              foreach($agency_centre_data as $res)
                              { ?>
                                <option value="<?php echo $res['centre_id']; ?>"><?php echo $res['centre_name']." (".$res['centre_username'].' - '.$res['city_name'].")"; ?></option>
                              <?php }
                            } ?>
                        </select>
                      </div>                    
                    <?php //} ?> 

                    <!-- <div class="form-group">
                      <input type="text" class="form-control s_datepicker search_opt" name="s_from_date" id="s_from_date" placeholder="From Date" readonly>
                    </div>
                    
                    <div class="form-group">
                      <input type="text" class="form-control s_datepicker search_opt" name="s_to_date" id="s_to_date" placeholder="To Date" readonly>
                    </div> -->
                     
                    <div class="form-group text-left">
                      <input type="text" class="form-control search_opt" name="s_batch_code" id="s_batch_code" placeholder="Batch Code">
                    </div>

                    <?php } ?>

                    <div class="form-group text-left">
                      <input type="text" class="form-control search_opt" name="s_regnumber" id="s_regnumber" placeholder="Registration No.">
                    </div>

                    <div class="form-group text-left">
                      <input type="text" class="form-control search_opt" name="s_full_name" id="s_full_name" placeholder="Candidate Name">
                    </div>
 
                    <div class="form-group text-left">
                      <select class="form-control search_opt" name="s_status" id="s_status" >
                        <option value="">Select Status</option>          
                        <option value="1">Auto Hold</option>                       
                        <option value="2">Manual Hold</option>                       
                        <option value="3">Release</option>                       
                      </select>
                    </div>

                    <div class="form-group" style="width:auto;">
                      <button type="button" class="btn btn-primary" onclick="apply_search()">Search</button>
                      <button type="button" class="btn btn-danger" onclick="clear_search()">Clear</button>
                    </div>
                  </form>

                  <div class="table-responsive">
										<table class="table table-bordered table-hover dataTables-example toggle_btn_tbl_outer" style="width:100%">
											<thead>
												<tr> 
                          <th class="no-sort text-center nowrap" style="width:60px;">Sr. No.</th>
													<th class="text-center nowrap">Agency Name</th>
													<th class="text-center nowrap">Centre Name</th>
													<th class="text-center nowrap">Batch Code</th>
													<th class="text-center nowrap">Training Id</th>
													<th class="text-center nowrap">Registration No.</th>
													<th class="text-center nowrap">Candidate Full Name</th>
													<th class="text-center nowrap">DOB</th>
													<th class="text-center nowrap">Mobile</th>
													<th class="text-center nowrap">Email</th>
													<th class="text-center nowrap all" style="width:80px;">Status</th>
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
						"url": '<?php echo site_url("iibfbcbf/admin/batch_candidates/get_batch_candidates_data_ajax"); ?>',
						"type": "POST",	
            "data": function ( d ) 
						{
              d.enc_batch_id = "<?php echo $enc_batch_id; ?>";
              <?php if($enc_batch_id == "0"){ ?>
              d.s_agency = $("#s_agency").val(); 
              d.s_centre = $("#s_centre").val();
							d.s_batch_code = $("#s_batch_code").val();
						<?php } ?>
              /*d.s_from_date = $("#s_from_date").val();
							d.s_to_date = $("#s_to_date").val();*/
							d.s_regnumber = $("#s_regnumber").val();
							d.s_full_name = $("#s_full_name").val();
              d.s_status = $("#s_status").val();
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
						{"targets": [7], "className": "nowrap"},
						{"targets": [8], "className": "nowrap"},
						{"targets": [9], "className": "nowrap"},
						{"targets": [10], "className": "nowrap text-center"},
						{"targets": [11], "className": "nowrap text-center"},
						/* {"targets": [4], "className": "text-center"},
						{"targets": [5], "className": "text-center"},
						{"targets": [6], "className": "text-center"},
						{"targets": [8], "className": "text-center"}, */
					],
					"aaSorting": [],
					"stateSave": false,		
          'drawCallback': function(settings)
					{
						$('input[type=checkbox][data-toggle^=toggle]').bootstrapToggle();
					}          			
				});
      });

      function get_centre_data(agency_id)
      {
        var s_agency = agency_id;  			
        $("#page_loader").show(); 
        $.ajax(
        {
          type: "POST",
          url: "<?php echo site_url('iibfbcbf/admin/training_batches/load_centre_data/'); ?>",
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
            //$('#current_centre_status').val(status);
            console.log('AJAX request failed: ' + errorThrown);
            sweet_alert_error("Error occurred. Please try again.");
            $("#page_loader").hide();
          }
        });			
      }

      function change_hold_release_status(cand_id, current_status)
      {
        $("#page_loader").show(); 
        var data = { 'cand_id': encodeURIComponent($.trim(cand_id)), 'status' : encodeURIComponent($.trim($("#toogle_id_"+cand_id).prop('checked'))) };	
        $.ajax(
        {
          type: "POST",
          url: "<?php echo site_url('iibfbcbf/admin/batch_candidates/change_hold_release_status'); ?>",
          data: data,
          success: function(response)
          {
            if(response.trim() != 'success') { $('.dataTables-example').DataTable().draw(); }

            if(current_status == 1)
            {
              $("#toggle_outer_"+cand_id+" .toggle-group label.btn-danger").html("Manual Hold");
            }

            if($.trim($("#toogle_id_"+cand_id).prop('checked')) == 'true')
            {
              $("#toggle_outer_"+cand_id).prop("title", "Click to make it Manual Hold");
            }
            else
            {
              $("#toggle_outer_"+cand_id).prop("title", "Click to make it Release");
            }

            $("#page_loader").hide();               
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
            $('.dataTables-example').DataTable().draw();
          }
        });
      }

      function clear_search() { $(".search_opt").val(''); get_centre_data(''); $('.dataTables-example').DataTable().draw(); }
      function apply_search() { $('.dataTables-example').DataTable().draw(); }
    </script>
    <?php /* $this->load->view('iibfbcbf/common/inc_bottom_script'); */ ?>	
	</body>
</html>