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
					<div class="col-lg-10">
						<h2>Batch Applicant Checklist</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item active"> <strong>Batch Applicant Checklist</strong></li>
            </ol>
          </div>
					<div class="col-lg-2"> </div>
        </div>			
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-content">
                  <form method="POST" id="search_form" class="search_form_common_all" action="<?php echo site_url('iibfbcbf/agency/batch_applicant_checklist/export_to_pdf'); ?>" autocomplete="off" style="text-align:left;"> 
                    <div class="form-group text-left" style="width:500px;">
                      <select class="form-control search_opt chosen-select" name="s_batch" id="s_batch" onchange="apply_search()">
                        <option value="">Select Batch</option>
                          <?php if(count($batch_data) > 0)
                          {
                            foreach($batch_data as $res)
                            { 
                              $disp_batch_name = $res['batch_code']; 
                              
                              if($res['batch_type'] == '1') { $disp_batch_name .= ' (Basic'; }
                              else if($res['batch_type'] == '2') { $disp_batch_name .= ' (Advance'; }

                              $disp_batch_name .= " - ".$res['batch_hours']." Hours)";
                              $disp_batch_name .= " - ".$res['batch_start_date'].' to '.$res['batch_end_date']." - ";
                                                            
                              if($res['batch_status'] == '0') { $disp_batch_name .= "In Review"; }
                              else if($res['batch_status'] == '1') { $disp_batch_name .= "Final Review"; }
                              else if($res['batch_status'] == '2') { $disp_batch_name .= "Batch Error"; }
                              else if($res['batch_status'] == '3') { $disp_batch_name .= "Go Ahead"; }
                              else if($res['batch_status'] == '4') { $disp_batch_name .= "Hold"; }
                              else if($res['batch_status'] == '5') { $disp_batch_name .= "Rejected"; }
                              else if($res['batch_status'] == '6') { $disp_batch_name .= "Re-Submitted"; }
                              else if($res['batch_status'] == '7') { $disp_batch_name .= "Cancelled"; } ?>
                              
                              <option value="<?php echo url_encode($res['batch_id']); ?>"><?php echo $disp_batch_name; ?></option>
                            <?php }
                          } ?>
                      </select>
                    </div>

                    <div class="form-group" style="width:auto;">
                      <button type="button" class="btn btn-danger" onclick="clear_search()">Clear</button>
                      <button type="submit" class="btn btn-success" id="export_to_pdf_btn" style="display:none;">Export To PDF</button>
                    </div>
                  </form> 
                  
                  <div class="table-responsive" id="candidate_listing_outer">
                    <table class="table table-bordered table-hover dataTables-example" style="width:100%">
                      <thead>
                        <tr>
                          <th class="no-sort text-center nowrap" style="width:60px;">Sr. No.</th>
                          <th class="text-center nowrap">Training  Id</th>
                          <th class="text-center nowrap">Candidate Name</th>
                          <th class="text-center nowrap">ID Proof</th>
                          <th class="text-center nowrap">ID Proof No.</th>
                          <th class="no-sort text-center nowrap">Photo</th>
                          <th class="no-sort text-center nowrap">Signature</th>
                          <th class="no-sort text-center">ID Proof Photo</th>
                          <th class="no-sort text-center">Qualification Certificate</th>
                          <th class="text-center nowrap">Gender</th>
                          <th class="text-center nowrap">DOB</th>
                          <th class="text-center nowrap">Mobile</th>
                          <th class="text-center nowrap">Email</th>
                          <th class="text-center nowrap">Address</th>
                          <th class="text-center nowrap">Qualification</th>
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
						"url": '<?php echo site_url("iibfbcbf/agency/batch_applicant_checklist/get_candidate_list_ajax"); ?>',
						"type": "POST",	
            "data": function ( d ) 
						{
              d.s_batch = $("#s_batch").val();
            },
            beforeSend: function() { $("#page_loader").show(); },
            complete: function() { $("#page_loader").hide(); },            
            error: function()
            {
              alert("Call failed...");
            }
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
            {"targets": [0], "className": "no-sort text-center"},
            {"targets": [5], "className": "no-sort text-center"},
            {"targets": [6], "className": "no-sort text-center"},
            {"targets": [7], "className": "no-sort text-center"},
            {"targets": [8], "className": "no-sort text-center"},
            {"targets": [9], "className": "text-center"},
            {"targets": [14], "className": "text-center"},
					],
					"aaSorting": [],
					"stateSave": false,		          			
				});
      });   
      
      function apply_search() 
      { 
        show_hode_export_to_pdf_btn();
        $('.dataTables-example').DataTable().draw(); 
      }
      
      function clear_search() 
      { 
        $(".search_opt").val(''); 
        $('#s_batch').val('').trigger('chosen:updated'); 
        $('.dataTables-example').DataTable().draw(); 
        show_hode_export_to_pdf_btn();
      }
      
      function show_hode_export_to_pdf_btn()
      {
        if($("#s_batch").val() == "") { $("#export_to_pdf_btn").hide(); }
        else { $("#export_to_pdf_btn").show(); }
      }
      show_hode_export_to_pdf_btn();

      function export_to_pdf_ajax()
      {
        $("#page_loader").show();
        var parameters = { "enc_batch_id":$("#s_batch").val() }
        $.ajax(
        {
          type: "POST",
          url: "<?php echo site_url('iibfbcbf/agency/batch_applicant_checklist/export_to_pdf_ajax'); ?>",
          data: parameters,
          cache: false,
          dataType: 'JSON',
          success:function(data)
          {
            
            $("#page_loader").hide();
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
            console.log('AJAX request failed: ' + errorThrown);
            sweet_alert_error("Error occurred. Please try again.")
            $('#page_loader').hide();
          }          
        });
      }
    </script>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>