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
					<div class="col-lg-10">
						<h2>Inspection Report</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/inspector/dashboard_inspector'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item active"> <strong>Inspection Report</strong></li>
            </ol>
          </div>
					<div class="col-lg-2"> </div>
        </div>			
				
				<div class="wrapper wrapper-content animated fadeInRight">
          <div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-content">
                  <form method="POST" id="search_form" class="search_form_common_all" action="<?php echo site_url('iibfbcbf/inspector/inspection_report_inspector/apply_search_report'); ?>" autocomplete="off">
                  	<div class="form-group text-left" style="min-width:400px;">
                      <select class="form-control chosen-select" name="s_batch_id" id="s_batch_id" onchange="validate_input('s_batch_id')" <?php /* onchange="apply_search()" */ ?> required>
                        <?php if(!isset($batch_data)) { ?><option value="">Select Batch For Inspection Report</option><?php } ?>
                        <?php if(count($batch_dropdown_data) > 0)
                        {
                          foreach($batch_dropdown_data as $res)
                          { ?>
                            <option value="<?php echo $res['batch_id']; ?>" <?php if($batch_id == $res['batch_id']) { echo 'selected'; } ?>><?php echo $res['batch_code']." (".$res['batch_hours']." Hours - ".date("d M Y", strtotime($res['batch_start_date']))." to ".date("d M Y", strtotime($res['batch_end_date'])).")"; ?></option>
                          <?php }
                        } ?>
                      </select>
                      <div id="s_batch_id_err"></div>
                    </div>

                    <div class="form-group" style="width:auto;">
                      <button type="submit" class="btn btn-primary" <?php /* onclick="apply_search()" */ ?>>Search</button>
                      <a href="<?php echo site_url('iibfbcbf/inspector/inspection_report_inspector'); ?>" class="btn btn-danger" <?php /* onclick="clear_search()" */ ?>>Clear</a>
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
				<?php $this->load->view('iibfbcbf/inspector/inc_footerbar_inspector'); ?>		
      </div>
    </div>
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>		
    <?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>

    <script language="javascript">      
      /* function clear_search() 
      { 
        $("#s_batch_id").val(''); 
        $('#search_form').submit(); 
      }

      function apply_search() 
      { 
        $('#search_form').submit();
      } */

      //START : JQUERY VALIDATION SCRIPT 
      function validate_input(input_id) { $("#"+input_id).valid(); }
      $(document ).ready( function() 
      {
        var form = $("#search_form").validate( 
        {
          onkeyup: function(element) { $(element).valid(); },          
          rules:
          {
            s_batch_id:{ required: true },           
          },
          messages:
          {
            s_batch_id: { required: "Please select the batch for inspection report" },
          }, 
          errorPlacement: function(error, element) // For replace error 
          {
            if (element.attr("name") == "s_batch_id") { error.insertAfter("#s_batch_id_err"); }
            else { error.insertAfter(element); }
          },          
          submitHandler: function(form) 
          {
            form.submit();
          }
        });
      });
      //END : JQUERY VALIDATION SCRIPT

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

      function show_loader()
      {
        $("#page_loader").show();
        setTimeout(function() { $("#page_loader").hide(); }, 2000); // 5000 milliseconds = 5 seconds
      }
    </script>

    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>