<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('iibfbcbf/inc_header'); ?> 
    <style>.css_checkbox_radio { margin:0; }</style>   
  </head>
	
	<body class="fixed-sidebar">
    <?php $this->load->view('iibfbcbf/common/inc_loader'); ?>
		
		<div id="wrapper">
      <?php $this->load->view('iibfbcbf/agency/inc_sidebar_agency'); ?>
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('iibfbcbf/agency/inc_topbar_agency'); ?>
				
        <div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-12">
						<h2><?php echo display_exam_name($active_exam_data[0]['description'], $active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_type']); /* helpers/iibfbcbf/iibf_bcbf_helper.php */ ?> </h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item active"> <strong><?php echo display_exam_name($active_exam_data[0]['description'], $active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_type']); /* helpers/iibfbcbf/iibf_bcbf_helper.php */ ?> </strong></li>
						</ol>
					</div>
				</div>
        
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
                <div class="ibox-title">
                  <div class="ibox-tools">
                    <a href="javascript:void(0)" class="btn btn-primary custom_right_add_new_btn" onclick="make_proforma_invoice()">Make Proforma Invoice</a>                    
                    <a href="javascript:void(0)" class="btn btn-danger custom_right_add_new_btn" onclick="reset_checkbox_selection()" title="Reset the checkbox selection">Reset</a>                    
                  </div>
                </div>
                              
                <div class="ibox-content">
                  <form method="POST" id="search_form" class="search_form_common_all" action="javascript:void(0)" autocomplete="off" style="text-align:left;">
                    <div class="form-group text-left" style="width:320px;">
                      <input type="text" class="form-control search_opt" name="s_batch_code" id="s_batch_code" placeholder="Batch Code (use , for multiple code)">
                    </div>

                    <div class="form-group text-left" style="width:320px;">
                      <input type="text" class="form-control search_opt" name="s_regnumber" id="s_regnumber" placeholder="Registration No. (use , for multiple numbers)">
                    </div>

                    <div class="form-group text-left" style="width:320px;">
                      <input type="text" class="form-control search_opt" name="s_name" id="s_name" placeholder="Candidate Name">
                    </div>

                    <div class="form-group text-left" style="width:320px;">
                      <select class="form-control search_opt" name="s_status" id="s_status" >
                        <option value="">Select Payment Status</option>                       
                        <option value="NULL">Blank</option>                       
                        <option value="0">Fail</option>                       
                        <option value="2">Pending</option>                       
                        <option value="3">Proforma Invoice Generated</option>
                        <option value="4">Payment Pending for Approval by IIBF</option>                       
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
                          <th class="no-sort text-center nowrap" style="width:30px;">
                            <label class="css_checkbox_radio"><input type="checkbox" id="checkboxlist_all_new" name="checkboxlist_all_new"><span class="checkmark"></span></label>
                          </th>
                          <th class="text-center no-sort nowrap">Sr. No.</th>
													<th class="text-center">Batch Code</th>
													<th class="text-center">Registration No.</th>
													<th class="text-center nowrap">Candidate Name</th>
													<th class="text-center">Training Id</th>
													<th class="text-center">DOB</th>
													<th class="text-center">Mobile</th>
													<th class="text-center nowrap" style="min-width:100px;">Email</th>
													<th class="text-center">Fee</th>
													<th class="text-center">Payment Mode</th>
													<th class="text-center">Payment Status</th>
													<th class="text-center">NEFT / RTGS (UTR) or Transaction Number</th>
													<th class="text-center nowrap">Exam Centre Name</th>
													<th class="text-center">Exam Medium</th>
													<th class="text-center no-sort nowrap" style="width:90px;">Action</th>
												</tr>
											</thead>
											
											<tbody></tbody>
										</table>
                    <form method="post" action="<?php echo site_url('iibfbcbf/agency/apply_exam_agency/make_payment/'.$enc_exam_code); ?>" id="make_selected_candidate_payment_form" enctype="multipart/form-data" autocomplete="off">
                      <input type="hidden" name="selcted_member_exam_ids_str" id="selcted_member_exam_ids_str">
                      <input type="hidden" name="chk_form_type" id="chk_form_type" value="candidate_selection">
                    </form>
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
						"url": '<?php echo site_url("iibfbcbf/agency/apply_exam_agency/get_exam_candidates_agency_data_ajax"); ?>',
						"type": "POST",	
            "data": function ( d ) 
						{
              d.enc_exam_code = "<?php echo $enc_exam_code; ?>";
              d.selected_ids_str = $("#selcted_member_exam_ids_str").val();
              d.s_batch_code = $("#s_batch_code").val();
              d.s_regnumber = $("#s_regnumber").val();
              d.s_name = $("#s_name").val();
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
						{"targets": [1], "className": "text-center"},
						{"targets": [4], "className": "wrap"},
						{"targets": [8], "className": "wrap"},
						{"targets": [10], "className": "text-center"},
						{"targets": [11], "className": "text-center"},
						{"targets": [12], "className": "text-center"},
						{"targets": [13], "className": "text-center"},
						{"targets": [14], "className": "text-center"},
						/* {"targets": [4], "className": "text-center"},
						{"targets": [5], "className": "text-center"},
						{"targets": [6], "className": "text-center"},
						{"targets": [8], "className": "text-center"}, */
					],
					"aaSorting": [],
					"stateSave": false,	
          'drawCallback': function(settings)
					{
						$( ".checkboxlist_new" ).click(function() { checkboxlist_new_function(); });
						$('#checkboxlist_all_new').prop('checked', false);
						checkboxlist_new_function();
						$('input[type=checkbox][data-toggle^=toggle]').bootstrapToggle();
					}	          			
				});
      });   
      
      function clear_search() { $(".search_opt").val(''); $('.dataTables-example').DataTable().draw(); }
      function apply_search() { $('.dataTables-example').DataTable().draw(); }
      
      <?php /* START : CHECKBOX SELECT / UNSELECT CHECKBOX WITH SELECT ALL OPTION */  ?>	
      $( "#checkboxlist_all_new" ).click(function()
      {
        if($(this).prop("checked") == true) { $('.checkboxlist_new').each(function() { $('.checkboxlist_new').prop('checked', true); }); }
        else if($(this).prop("checked") == false) { $('.checkboxlist_new').each(function() { $('.checkboxlist_new').prop('checked', false); }); }
        
        $('.checkboxlist_new').each(function() { update_delete_str(this.value) });
      });
      
      $( ".checkboxlist_new" ).click(function() { checkboxlist_new_function(); });
      
      function checkboxlist_new_function()
      {
        var total_length = document.querySelectorAll('.checkboxlist_new').length;
        var selected_length = document.querySelectorAll('.checkboxlist_new:checked').length;
        if(total_length > 0 && total_length == selected_length) { $('#checkboxlist_all_new').prop('checked', true); }
        else { $('#checkboxlist_all_new').prop('checked', false); }
      }
      
      function update_delete_str(id)
      {
        var selected_ids = String($("#selcted_member_exam_ids_str").val());	
        explode_arr = selected_ids.split(',');
        
        if($("#checkboxlist_new_"+id).prop("checked") == true) 
        { 
          if(selected_ids == "") { selected_ids = id; } 
          else 
          { 
            if ($.inArray(id, explode_arr) !== -1) 
            {
              //console.log(id + ' is in the array.');              
            } 
            else 
            {
              //console.log(id + ' is not in the array.');
              selected_ids = selected_ids + "," + id; 
            }
          } 
        }
        else 
        { 
          explode_arr = jQuery.grep(explode_arr, function(value) { return value != id; }); 
          selected_ids = explode_arr.join(',')
        }
        
        $("#selcted_member_exam_ids_str").val(selected_ids);
      }
      <?php /* END : CHECKBOX SELECT / UNSELECT CHECKBOX WITH SELECT ALL OPTION */  ?>	

      //START : FUNCTION TO CLEAR THE SELECTED EXAM CENTER NAME AND EXAM MEDIUM
      function sweet_alert_clear(clear_url) 
      { 
        swal({ title: "Please confirm", text: "Please confirm to clear the candidates exam application entry", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes, Clear it!", closeOnConfirm: true }, function () 
        { window.location.href = clear_url; }); 
      }//END : FUNCTION TO CLEAR THE SELECTED EXAM CENTER NAME AND EXAM MEDIUM

      //START : FUNCTION TO CLEAR THE CHECKBOX SELECTION
      function reset_checkbox_selection(clear_url) 
      { 
        var myArray = [];
        var checkValues = $('#selcted_member_exam_ids_str').val();
        
        if(checkValues=="") { sweet_alert_only_alert("There is no selected record to clear the checkbox selection"); }
        else
        {
          explode_cnt = checkValues.split(',').length;
          swal({ title: "Please confirm", text: "Please confirm to clear the "+explode_cnt+" checkbox selected records", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes, Reset it!", closeOnConfirm: true }, function () 
          { $("#selcted_member_exam_ids_str").val(""); $('.dataTables-example').DataTable().draw(); });
        }
      }//END : FUNCTION TO CLEAR THE CHECKBOX SELECTION

      //START : FUNCTION TO MAKE THE PROFORMA INVOICE FOR SELECTED CHECKBOX RECORDS
      function make_proforma_invoice() 
      { 
        var myArray = [];
        var checkValues = $('#selcted_member_exam_ids_str').val();
        
        if(checkValues=="") { sweet_alert_only_alert("Please select at least one record to make proforma Invoice"); }
        else
        {
          explode_cnt = checkValues.split(',').length;
          swal({ title: "Please confirm", text: "Please confirm to make the proforma invoice for selected "+explode_cnt+" records", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
          {
            $("#make_selected_candidate_payment_form").submit();
          });
        }
      }//END : FUNCTION TO MAKE THE PROFORMA INVOICE FOR SELECTED CHECKBOX RECORDS
    </script>
    
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>	
	</body>
</html>