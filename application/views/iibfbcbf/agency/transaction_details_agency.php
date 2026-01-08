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
					<div class="col-lg-12">
						<h2>Transaction Details</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item active"> <strong>Transaction Details</strong></li>
						</ol>
					</div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
                <div class="ibox-content">                
                  <form method="POST" action="<?php echo base_url("iibfbcbf/agency/transaction_details_agency/get_transaction_data_ajax"); ?>"  id="search_form" class="search_form_common_all" autocomplete="off">
                    <input type="hidden" name="tbl_search_value" id="tbl_search_value">
                    <input type="hidden" name="form_action" id="form_action" value="">

                    <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency') { ?>
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
                    <?php } ?>

                    <?php /* <div class="form-group text-left">
                      <input type="text" class="form-control search_opt" name="s_member_no" id="s_member_no" placeholder="Registration No.">
                    </div> */ ?>

                    <div class="form-group text-left" style="min-width:300px;">
                      <input type="text" class="form-control search_opt" name="s_utr_no" id="s_utr_no" placeholder="NEFT / RTGS (UTR) or Transaction No.">
                    </div>

                    <div class="form-group text-left">
                      <input type="text" class="form-control search_opt" name="s_receipt_no" id="s_receipt_no" placeholder="Receipt No.">
                    </div>

                    <div class="form-group text-left">
                      <input type="text" class="form-control search_opt" name="s_exam_code" id="s_exam_code" placeholder="Exam Code">
                    </div>

                    <div class="form-group text-left">
                      <input type="text" class="form-control search_opt" name="s_exam_period" id="s_exam_period" placeholder="Exam Period">
                    </div>

                    <div class="form-group">
                      <input type="text" class="form-control s_datepicker search_opt" name="s_from_date" id="s_from_date" placeholder="From Date" readonly>
                    </div>
                    
                    <div class="form-group">
                      <input type="text" class="form-control s_datepicker search_opt" name="s_to_date" id="s_to_date" placeholder="To Date" readonly>
                    </div>
                    
                    <?php if($allow_exam_types == 'CSC')
                    { ?>  
                      <input type="hidden" name="s_payment_mode" id="s_payment_mode" value="CSC">
                    <?php }
                    else { ?>
                      <div class="form-group text-left">
                        <select class="form-control search_opt" name="s_payment_mode" id="s_payment_mode" >
                          <?php /* <option value="">Select Payment Mode</option> */ ?>
                          <option value="Bulk">Bulk</option>                       
                          <option value="CSC">CSC</option>                       
                          <option value="Individual">Individual</option>                       
                        </select>
                      </div>
                    <?php } ?>

                    <div class="form-group text-left">
                      <select class="form-control search_opt" name="s_payment_status" id="s_payment_status" >
                        <option value="">Select Payment Status</option>
                        <option value="0">Fail</option>                       
                        <option value="1">Success</option>                     
                        <option value="2">Pending</option>                     
                        <option value="4">Cencelled</option> 
                        <?php if($allow_exam_types != 'CSC')  { ?>                  
                          <option value="3">Proforma Invoice Generated</option>
                          <option value="5">Payment Pending for Approval by IIBF</option>                       
                        <?php } ?>
                      </select>
                    </div>

                    <div class="form-group" style="width:auto;">
                      <button type="button" class="btn btn-primary" onclick="apply_search()">Search</button>
                      <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency') { ?>
                        <button type="button" class="btn btn-success custom_right_add_new_btn" onclick="apply_filter_with_export_to_excel()" >Export To Excel</button>
                      <?php } ?>
                      <button type="button" class="btn btn-danger" onclick="clear_search()">Clear</button>
                    </div>
                  </form>

                  <div class="table-responsive">
										<table class="table table-bordered table-hover dataTables-example" style="width:100%">
											<thead>
												<tr> 
                          <th class="no-sort text-center" style="width:60px;">Sr. No.</th>
													<th class="text-center">Centre Name</th>
													<th class="text-center">NEFT / RTGS (UTR) or Transaction Number</th>
													<th class="text-center">Receipt No.</th>
													<th class="text-center">Application</th>
													<th class="text-center">Amount</th>
													<th class="text-center">No. of Candidates</th>
													<th class="text-center">Payment Date</th>
                          <th class="text-center">Exam Code</th>
                          <th class="text-center">Exam Period</th>
													<th class="no-sort text-center">Registration No.</th>                          
													<th class="no-sort text-center">Training Ids</th>                          
													<th class="text-center">Payment Mode</th>
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
				<?php $this->load->view('iibfbcbf/agency/inc_footerbar_agency'); ?>			
			</div>
		</div>

    <div class="modal inmodal fade" id="update_payment_details_modal" tabindex="-1" role="dialog"  aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" id="update_payment_details_outer">
          
        </div>
      </div>
    </div>
		
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>
    <?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>

    <link href="<?php echo auto_version(base_url('assets/iibfbcbf/css/plugins/dataTables/responsive.dataTables.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo auto_version(base_url('assets/iibfbcbf/js/plugins/dataTables/dataTables.responsive.min.js')); ?>"></script>
		    
    <script language="javascript">
      <?php if(validation_errors() != "" && count(validation_errors()) > 0 || $utr_slip_error != "") 
      { ?> 
        update_payment_details_modal('<?php echo set_value('enc_payment_id'); ?>');
      <?php } ?>

      function update_payment_details_modal(enc_payment_id='')
      {
        $("#page_loader").show();
        parameters= { "enc_payment_id":enc_payment_id, "form_utr_no":"<?php echo set_value('utr_no'); ?>", "form_payment_date":"<?php echo set_value('payment_date'); ?>", "utr_slip_error":"<?php echo str_replace(array("<p>","</p>"),"",$utr_slip_error); ?>", "form_utr_no_error":"<?php echo str_replace(array("<p>","</p>"),"",form_error('utr_no')); ?>", "form_payment_date_error":"<?php echo str_replace(array("<p>","</p>"),"",form_error('payment_date')); ?>", "form_utr_slip_error":"<?php echo str_replace(array("<p>","</p>"),"",form_error('utr_slip')); ?>" }; 
        <?php /* "form_gst_centre_id":"<?php echo set_value('gst_centre_id'); ?>",
        "form_gst_centre_id_error":"<?php echo str_replace(array("<p>","</p>"),"",form_error('gst_centre_id')); ?>" */ ?>

        $.ajax(
        {
          type: "POST",
          url: "<?php echo site_url('iibfbcbf/agency/transaction_details_agency/get_payment_details_modal_ajax'); ?>",
          data: parameters,
          cache: false,
          dataType: 'JSON',
          async:false,
          success:function(data)
          {
            if(data.flag == "success")
            {
              $("#update_payment_details_outer").html(data.response);
              $("#update_payment_details_modal").modal({backdrop: 'static', keyboard: false}, 'show');
              //$("#city_outer").html(data.response);
              $("#page_loader").hide();
            }
            else
            {
              sweet_alert_error("Error occurred. Please try again.")
              $('#page_loader').hide();
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
      
      
      $('.s_datepicker').datepicker({ todayBtn: "linked", keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy-mm-dd", todayHighlight:true, clearBtn: true, endDate:"<?php echo date("Y-m-d"); ?>" });

      $(document).ready(function()
			{
				var table = $('.dataTables-example').DataTable(
				{
          searching: true,
					"processing": false,
					"serverSide": true,
					"ajax": 
          {
						"url": '<?php echo site_url("iibfbcbf/agency/transaction_details_agency/get_transaction_data_ajax"); ?>',
						"type": "POST",	
            "data": function ( d ) 
						{
              <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency') { ?> d.s_centre = $("#s_centre").val(); <?php } ?>
              /* d.s_member_no = $("#s_member_no").val(); */
              d.s_utr_no = $("#s_utr_no").val();
              d.s_receipt_no = $("#s_receipt_no").val();
              d.s_exam_code = $("#s_exam_code").val();
              d.s_exam_period = $("#s_exam_period").val();
              d.s_from_date = $("#s_from_date").val();
							d.s_to_date = $("#s_to_date").val();
							d.s_payment_mode = $("#s_payment_mode").val();
							d.s_payment_status = $("#s_payment_status").val();
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
            <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'centre') { ?> {"targets": [1], "className": "hide"}, <?php } ?>
						{"targets": [5], "className": "text-right"},
						{"targets": [6], "className": "text-right"},
						{"targets": [7], "className": "text-right"},
						{"targets": [8], "className": "text-center"},
						{"targets": [9], "className": "text-center"},
						{"targets": [12], "className": "text-center"},
						{"targets": [13], "className": "text-center"},
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
        $('.s_datepicker').val("").datepicker("update");
        $(".search_opt").val(''); 
        var allow_exam_types = "<?php echo $allow_exam_types; ?>";
        if(allow_exam_types == 'CSC') { }
        else { $("#s_payment_mode").val('Bulk'); }
        $('.dataTables-example').DataTable().draw(); 
      }

      function apply_search() 
      { 
        $("#form_action").val(""); 
        $('.dataTables-example').DataTable().draw(); 
      }

      function apply_filter_with_export_to_excel(export_type = 'export') 
      { 
      	$("#tbl_search_value").val($('input[type="search"]').val());
        $("#form_action").val(export_type);
      	$("#page_loader").show();
      	$("#search_form").submit();
      	setTimeout(function()
        {
      		apply_search();
      	},1000); 
      }
    </script>
    <?php /* $this->load->view('iibfbcbf/common/inc_bottom_script'); */ ?>	
	</body>
</html>