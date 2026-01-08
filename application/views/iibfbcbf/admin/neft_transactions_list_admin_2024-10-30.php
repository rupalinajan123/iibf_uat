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
				
        <?php $disp_title = 'Approve NEFT Transactions';  ?>

				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-12">
						<h2><?php echo $disp_title; ?></h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/dashboard_admin'); ?>">Dashboard</a></li> 
							<li class="breadcrumb-item active"> <strong><?php echo $disp_title; ?></strong></li>
						</ol>
					</div>
				</div>
        
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
                 
                  <!-- <div class="ibox-title">
                    <div class="ibox-tools"> 
                      <a href="<?php echo site_url('iibfbcbf/admin/transaction'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a> 
                    </div>
                  </div> -->   
                 
                
                <div class="ibox-content">
                	 
                	<form method="POST" id="search_form" class="search_form_common_all" action="javascript:void(0)" autocomplete="off">
                    <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency') { ?>
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
                    <?php } ?>

                    <div class="form-group text-left">
                      <input type="text" class="form-control search_opt" name="s_member_no" id="s_member_no" placeholder="Registration No.">
                    </div>

                    <div class="form-group text-left">
                      <input type="text" class="form-control search_opt" name="s_utr_no" id="s_utr_no" placeholder="NEFT / RTGS (UTR) Number">
                    </div>

                    <div class="form-group text-left">
                      <select class="form-control search_opt" name="s_exam_code" id="s_exam_code">
                        <option value="">Select Exam Code</option>
                        <option value="1037">1037</option>
                        <option value="1038">1038</option>
                        <option value="1039">1039</option>
                        <option value="1040">1040</option>
                        <option value="1041">1041</option>
                        <option value="1042">1042</option>
                        <option value="1057">1057</option>
                      </select>
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

                    <div class="form-group text-left">
                      <select class="form-control search_opt" name="s_payment_mode" id="s_payment_mode" >
                        <?php /* <option value="">Select Payment Mode</option> */ ?>
                        <option value="Bulk">Bulk</option>                       
                        <option value="CSC">CSC</option>                       
                        <option value="Individual">Individual</option>                       
                      </select>
                    </div>
                    
                    <div class="form-group text-left">
                      <select class="form-control search_opt" name="s_payment_status" id="s_payment_status" >
                        <option value="">Select Payment Status</option>
                        <option value="0">Fail</option>                       
                        <option value="1">Success</option>                     
                        <option value="3">Payment Pending for Approval by IIBF</option>                       
                        <option value="4">Cancelled</option>                       
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
													<th class="text-center">Agency Name</th>
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
		

		<div class="modal inmodal fade" id="transaction_confirm_modal" tabindex="-1" role="dialog"  aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Verify to Approve/Reject NEFT Transactions</h4>
              </div>
              <form method="post" class="form-horizontal" id="loginForm" autocomplete="off" enctype="multipart/form-data">
                 
                 <input type="hidden" name="txn_id" id="txn_id" />
                 <input type="hidden" name="enc_payment_id" id="enc_payment_id" />

                <div class="modal-body">
                  <div class="modal_form_outer">

                  	<div class="form-group row">
                      <label class="col-lg-4 text-right"><b>NEFT / RTGS (UTR) Number <sup class="text-danger">*</sup> :</b></label>
                      <div class="col-lg-8"> 
                        <input type="text" name="form_utr_no_disp" id="form_utr_no_disp" class="form-control custom_input" readonly />
                        <input type="hidden" class="form-control" name="form_utr_no" id="form_utr_no" /> 
                      </div>
                    </div> 

                    <div class="form-group row">
                    <label class="col-lg-4 text-right"><b>Application :</b></label>
                      <div class="col-lg-8"><div class="modal_form_info_text"><span id="form_DRA"></span></div></div>
                    </div>

                    <div class="form-group row">
                      <label class="col-lg-4 text-right"><b>No. of Candidates <sup class="text-danger">*</sup> :</b></label>
                      <div class="col-lg-8"> 
                        <input type="text" name="form_mem_count_disp" id="form_mem_count_disp" class="form-control custom_input" readonly />
                        <input type="hidden" class="form-control" name="form_mem_count" id="form_mem_count" />
                      </div>
                    </div> 

                    <div class="form-group row">
                      <label class="col-lg-4 text-right"><b>Amount <sup class="text-danger">*</sup> :</b></label>
                      <div class="col-lg-8"> 
                        <input type="text" name="form_payment_amt_disp" id="form_payment_amt_disp" class="form-control custom_input" readonly />
                        <input type="hidden" class="form-control" name="form_payment_amt" id="form_payment_amt" />
                      </div>
                    </div> 

                    <div class="form-group row">
                      <label class="col-lg-4 text-right"><b>Paid Date <sup class="text-danger">*</sup> :</b></label>
                      <div class="col-lg-8"> 
                        <input type="text" name="form_payment_date_disp" id="form_payment_date_disp" class="form-control custom_input" readonly />
                        <input type="hidden" class="form-control" name="form_payment_date" id="form_payment_date" />
                      </div>
                    </div>

 										<div class="form-group row">
                      <label class="col-lg-4 text-right"><b>Exam Period :</b></label>
                      <div class="col-lg-8"><div class="modal_form_info_text"><span id="form_exam_period"></span></div></div>
                    </div>

 										<div class="form-group row">
                      <label class="col-lg-4 text-right"><b>Payment (UTR) slip :</b></label>
                      <div class="col-lg-8"><div class="modal_form_info_text"><span id="form_utr_slip"></span></div></div>
                    </div>

                    <!-- <div class="form-group">
                        <label class="col-xs-4 control-label">Institute Name </label>
                        <div class="col-xs-8">
                            <span id="form_inst_name"></span>
                        </div>
                    </div> --> 

                  </div>
                </div>

                <div class="modal-footer" id="submit_btn_outer"> 
                	<button type="button" class="btn btn-success" id="btnApprove" onclick="actionOnTransaction('Approved');">Approve</button>
                    <button type="button" class="btn btn-danger" id="btnReject" onclick="actionOnTransaction('Rejected')">Reject</button>  
                </div>
              </form>
          </div>
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
						"url": '<?php echo site_url("iibfbcbf/admin/transaction/get_transaction_data_ajax"); ?>',
						"type": "POST",	
            "data": function ( d ) 
						{ 
							d.s_member_no = $("#s_member_no").val();
              d.s_utr_no = $("#s_utr_no").val();
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
						{"targets": [6], "className": "text-right"},
						{"targets": [7], "className": "text-right"},
						{"targets": [8], "className": "text-right"},
						{"targets": [9], "className": "text-center"},
						{"targets": [10], "className": "text-center"},
						{"targets": [13], "className": "text-center"},
						{"targets": [14], "className": "text-center"},
						{"targets": [15], "className": "text-center"},
						/* {"targets": [4], "className": "text-center"},
						{"targets": [5], "className": "text-center"},
						{"targets": [6], "className": "text-center"},
						{"targets": [8], "className": "text-center"}, */
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
	            url: "<?php echo site_url('iibfbcbf/admin/transaction/load_centre_data/'); ?>",
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

      function verifyTransaction(enc_id){
      	//alert(enc_id); 
      		$("#page_loader").show(); 
          $.ajax(
          {
            type: "POST",
            url: "<?php echo site_url('iibfbcbf/admin/transaction/getTransactionDetails/'); ?>",
            data: {enc_id:enc_id},
            /*async: false,
            cache : false,*/
            dataType: 'JSON',
            success: function(data)
            { 
              if($.trim(data.flag) == 'success')
              {  
                $("#page_loader").hide(); 
                var result = data.response;
                //console.log(result);
                //alert(result.transaction_no);

                
                $("#txn_id").val(result.id); 
                $("#enc_payment_id").val(result.enc_payment_id); 
								$("#form_utr_no_disp").val(result.transaction_no);
								$("#form_utr_no").val(result.transaction_no);
								$("#form_DRA").text(result.description);
								$("#form_mem_count_disp").val(result.member_count);
								$("#form_mem_count").val(result.member_count);
								$("#form_payment_amt_disp").val(result.amount);
								$("#form_payment_amt").val(result.amount);
								$("#form_payment_date_disp").val(result.date);
								$("#form_payment_date").val(result.date);
								$("#form_exam_period").text(result.exam_period);

                $("#form_utr_slip").html("<a class='btn btn-success btn-sm' target='_blank' href='<?php echo base_url($utr_slip_path); ?>/"+result.UTR_slip_file+"'>View</a>")
								//$("#form_inst_name").text(result.inst_name);
								
								if(result.status == 0) { $("#btnReject").css("display","none"); } else { $("#btnReject").css("display","inline-block"); }	 
								//$('#form_payment_date').datepicker({format: 'dd-mm-yyyy',endDate: '+0d',autoclose: true});
								$('#form_payment_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true});

                var modal = $("#transaction_confirm_modal");
    						modal.modal({backdrop: 'static', keyboard: false}, 'show');
              }else{ 
                  sweet_alert_error("Error occurred. Please try again.");
                  $("#page_loader").hide(); 
              } 
              // data.response;
            },
            error: function(jqXHR, textStatus, errorThrown) 
            {
              //$('#current_faculty_status').val(status);
              console.log('AJAX request failed: ' + errorThrown);
              sweet_alert_error("Error occurred. Please try again.");
              $("#page_loader").hide();

            }
          }); 
      }

	    function actionOnTransaction(action)
			{
				var action_msg = '';
			 	if(action == 'Approved'){
			 		action_msg = 'Approve';
			 	}else if(action == 'Rejected'){
			 		action_msg = 'Reject';
			 	}

				swal({ title: "Please confirm", text: "Please confirm, do you want to "+action_msg+" the NEFT Transactions", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: false }, function (){

            var txn_id = $("#txn_id").val();
						var enc_payment_id = $("#enc_payment_id").val();
						var utr_no = $("#form_utr_no").val();
						var mem_count = $("#form_mem_count").val();
						var payment_amt = $("#form_payment_amt").val();
						var payment_date = $("#form_payment_date").val();
						//alert(txn_id + '-' + utr_no + '-' + mem_count + '-' + payment_amt + '-' + payment_date);
						
						if(utr_no == '' || mem_count == '' || payment_amt == '' || payment_date == '')
						{
							$("#modal_error_msg").text("All fields are required.");
							return false;	
						}
						
						//$("#neft_msg_success").hide();
						//$("#neft_msg_error").hide();
						
						var base_url = '<?php echo base_url(); ?>';
						var url = base_url+'iibfbcbf/admin/transaction/approveNeftTransactions';
						
						$.ajax({
							url: url,
							type: 'POST',
							dataType:"json",
							data: {id: txn_id, action: action, utr_no: utr_no, mem_count: mem_count, payment_amt: payment_amt, payment_date: payment_date},
							success: function(res) 
							{
								if(res)
								{
									if(res.success == 'success')
									{
                    generate_admit_card(enc_payment_id);
										sweet_alert_success("Admin NEFT "+action+" Successfully.");
										$('.dataTables-example').DataTable().draw();

										$("#transaction_confirm_modal").modal("hide");
                  }
                  else if(res.success == 'capacity_error')
									{
                    sweet_alert_error("You can not approve this transaction, as the capacity is full");
                    $("#transaction_confirm_modal").modal("hide");
                  }
                  else if(res.success == 'error2')
									{
                    sweet_alert_error("Error occurred. Please try again.");
                    $("#transaction_confirm_modal").modal("hide");
                  }
                  else if(res.success == 'invalid_request')
                  {
                    sweet_alert_error("Invalid Request. Please refresh the page and try again.");
                    $("#transaction_confirm_modal").modal("hide");
                  }
									else
									{
										//alert("error");
										//$("#neft_msg_error").show();
                    sweet_alert_error("Error occurred. Please contact to admin.");
										$("#transaction_confirm_modal").modal("hide");
										return false;
									}
								}
							},
							error: function(jqXHR, textStatus, errorThrown) 
							{
								console.log(textStatus, errorThrown);
								$("#transaction_confirm_modal").modal("hide");
							}
						}); 
					}); 

	        $(".cancel").click(function () {  
            //var status = $('#current_faculty_status').val(); 
	        });  
			}

      function generate_admit_card(enc_payment_id)
      {
        $.ajax(
        {
          url: "<?php echo site_url('iibfbcbf/admin/transaction/generate_admit_card/'); ?>/"+enc_payment_id,
          type: 'POST',
          data: { "enc_payment_id":enc_payment_id },          
          success: function(res)
          { }
        });
      }

			function clear_search() 
      { 
        $('.s_datepicker').val("").datepicker("update");
        $(".search_opt").val(''); 
        $("#s_payment_mode").val('Bulk'); 
        $('.dataTables-example').DataTable().draw(); 
      }

      function apply_search() { $('.dataTables-example').DataTable().draw(); }

      $('.s_datepicker').datepicker({ todayBtn: "linked", keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy-mm-dd", todayHighlight:true, clearBtn: true, endDate:"<?php echo date("Y-m-d"); ?>" });

         
      $(document).ready(function() { setTimeout(function() { $('#alert_fadeout').fadeOut(3000); }, 8000 ); });
    </script>
	</body>
</html>