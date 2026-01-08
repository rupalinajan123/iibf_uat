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
					<div class="col-lg-10">
						<h2>Inspection Summary Report</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/dashboard_admin'); ?>">Dashboard</a></li>
              <li class="breadcrumb-item">Reports</li>
							<li class="breadcrumb-item active"> <strong>Inspection Summary Report</strong></li>
            </ol>
          </div>
					<div class="col-lg-2"> </div>
        </div>			
				
				<div class="wrapper wrapper-content animated fadeInRight">
          <div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-content">
                  <form method="POST" id="search_form" class="search_form_common_all" action="<?php echo site_url('iibfbcbf/admin/inspection_summary_admin/apply_search'); ?>" autocomplete="off">
                  	<div class="form-group text-left" style="min-width:400px;">
                      <select class="form-control chosen-select" name="s_batch_id" id="s_batch_id" onchange="apply_search()">
                        <option value="">Select Batch For Inspection Summary Report</option>
                          <?php if(count($batch_dropdown_data) > 0)
                          {
                            foreach($batch_dropdown_data as $res)
                            { ?>
                              <option value="<?php echo $res['batch_id']; ?>" <?php if($batch_id == $res['batch_id']) { echo 'selected'; } ?>><?php echo $res['batch_code']." (".$res['batch_hours']." Hours - ".date("d M Y", strtotime($res['batch_start_date']))." to ".date("d M Y", strtotime($res['batch_end_date'])).")"; ?></option>
                            <?php }
                          } ?>
                      </select>
                    </div>

                    <div class="form-group" style="width:auto;">
                      <button type="button" class="btn btn-primary" onclick="apply_search()">Search</button>
                      <button type="button" class="btn btn-danger" onclick="clear_search()">Clear</button>
                      <?php if(isset($batch_data) && count($batch_data) > 0) { ?>
                        <input type="submit" class="btn btn-success" name="export_to_pdf" value="Export to PDF" onclick="show_loader()">
                      <?php } ?>
                    </div>
                  </form>
                </div>
              </div>
              
              <?php if(isset($batch_data) && count($batch_data) > 0)
              { 
                $this->load->view('iibfbcbf/common/inc_inspection_report_content_common');
              } ?>
            </div>					
          </div>
        </div>
				<?php $this->load->view('iibfbcbf/admin/inc_footerbar_admin'); ?>			
      </div>
    </div>
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>		
    <?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>

    <link href="<?php echo auto_version(base_url('assets/iibfbcbf/css/plugins/dataTables/responsive.dataTables.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo auto_version(base_url('assets/iibfbcbf/js/plugins/dataTables/dataTables.responsive.min.js')); ?>"></script>

    <script language="javascript">
      function clear_search() 
      { 
        $("#s_batch_id").val(''); 
        $('#search_form').submit(); 
      }

      function apply_search() 
      { 
        $('#search_form').submit();
      }

      function change_hold_release_status(cand_id, current_status)
      {
        $("#page_loader").show(); 
        var data = { 'cand_id': encodeURIComponent($.trim(cand_id)), 'status' : encodeURIComponent($.trim($("#toogle_id_"+cand_id).prop('checked'))) };	
        $.ajax(
        {
          type: "POST",
          url: "<?php echo site_url('iibfbcbf/admin/inspection_summary_admin/change_hold_release_status'); ?>",
          data: data,
          success: function(response)
          {
            if(response.trim() != 'success') 
            { 
              location.reload();
            }

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
            location.reload();
          }
        });
      }

      function show_loader()
      {
        $("#page_loader").show();
        setTimeout(function() { $("#page_loader").hide(); }, 5000); // 5000 milliseconds = 5 seconds
      }

      <?php if(isset($batch_candidate_data) && count($batch_candidate_data) > 0) { ?>
        $(document).ready(function()
        {
          var table = $('.dataTables-example').DataTable(
          {
            searching: true,
            "processing": false,
            "serverSide": false,            
            "lengthMenu": [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]],
            "language": 
            {
              "lengthMenu": "_MENU_",
            },
            pageLength: 10,
            responsive: true,
            rowReorder: false,   
            "columnDefs": 
            [
              {"targets": 'no-sort', "orderable": false, },
              /* {"targets": [0], "className": "text-center"},
              {"targets": [7], "className": "nowrap"},
              {"targets": [8], "className": "nowrap"},
              {"targets": [9], "className": "nowrap"},
              {"targets": [10], "className": "nowrap text-center"},
              {"targets": [11], "className": "nowrap text-center"},
              {"targets": [4], "className": "text-center"},
              {"targets": [5], "className": "text-center"},
              {"targets": [6], "className": "text-center"},
              {"targets": [8], "className": "text-center"}, */
            ], 
            "aaSorting": [],
            "stateSave": false,         			
          });
        });
      <?php } ?>
    </script>

    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>