<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('ncvet/inc_header'); ?>    
  </head>
	
	<body class="fixed-sidebar">
    <?php $this->load->view('ncvet/common/inc_loader'); ?>
		
		<div id="wrapper">
      <?php $this->load->view('ncvet/candidate/inc_sidebar_candidate'); ?>
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('ncvet/candidate/inc_topbar_candidate'); ?>
				
        <?php $disp_title = 'Transactions';  ?>

				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-12">
						<h2><?php echo $disp_title; ?></h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('ncvet/candidate/dashboard_candidate'); ?>">Dashboard</a></li> 
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
                      <a href="<?php echo site_url('ncvet/candidate/transaction'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a> 
                    </div>
                  </div> -->   
                 
                
                <div class="ibox-content">
                	 
                	<form method="POST" id="search_form" class="search_form_common_all" action="javascript:void(0)" autocomplete="off">
                    
                		<div class="form-group">
                      <input type="text" class="form-control s_datepicker search_opt" name="s_from_date" id="s_from_date" placeholder="From Date" readonly>
                    </div>
                    
                    <div class="form-group">
                      <input type="text" class="form-control s_datepicker search_opt" name="s_to_date" id="s_to_date" placeholder="To Date" readonly>
                    </div>

                    <div class="form-group">
                      <input type="text" class="form-control search_opt" name="s_utr_no" id="s_utr_no" placeholder="Transaction No.">
                    </div>

                    <div class="form-group">
                      <input type="text" class="form-control search_opt" name="s_receipt_no" id="s_receipt_no" placeholder="Receipt No.">
                    </div>

                    <div class="form-group text-left" style="width: -webkit-fill-available;">
                      <button type="button" class="btn btn-primary" onclick="apply_search()">Search</button>
                      <button type="button" class="btn btn-danger" onclick="clear_search()">Clear</button>
                    </div>
                  </form>


                  <div class="table-responsive">
										<table class="table table-bordered table-hover dataTables-example" style="width:100%">
											<thead>
												<tr> 
                          <th class="no-sort text-center nowrap" style="width:60px;">Sr. No.</th>
                          <th class="no-sort text-center nowrap">Details</th>
                          <th class="text-center">Transaction Number</th>
                          <th class="text-center">Receipt No.</th>
													<th class="text-center">Amount</th>
													<th class="text-center">Payment Date</th>
													<th class="text-center" style="width:80px;">Status</th>
													<th class="text-center no-sort nowrap" style="width:90px;">Download Invoice</th>
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
				<?php $this->load->view('ncvet/candidate/inc_footerbar_candidate'); ?>			
			</div>
		</div>
		<?php $this->load->view('ncvet/inc_footer'); ?>

    <link href="<?php echo auto_version(base_url('assets/ncvet/css/plugins/dataTables/responsive.dataTables.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo auto_version(base_url('assets/ncvet/js/plugins/dataTables/dataTables.responsive.min.js')); ?>"></script>
		    
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
						"url": '<?php echo site_url("ncvet/candidate/transaction/get_transaction_data_ajax"); ?>',
						"type": "POST",	
            "data": function ( d ) 
						{ 
							/* d.s_member_no = $("#s_member_no").val(); */
              d.s_utr_no = $("#s_utr_no").val();
              d.s_receipt_no = $("#s_receipt_no").val();
              d.s_from_date = $("#s_from_date").val();
							d.s_to_date = $("#s_to_date").val();
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
					"columnDefs": [
            { "targets": 'no-sort', "orderable": false }, // Sr. No.
            { "targets": [0], "className": "text-center" }, // Details  
            { "targets": [1], "className": "text-center" }, // Transaction No  
            { "targets": [2], "className": "text-center" }, // Receipt No  
            { "targets": [3], "className": "text-center" }, // Amount  
            { "targets": [4], "className": "text-right" }, // Payment Date   
            { "targets": [5], "className": "text-center" },// Status   
            { "targets": [6], "className": "text-center" }, // Action  
            

          ],
					"aaSorting": [],
					"stateSave": false,		          			
				});
      }); 

			function clear_search() 
      { 
        $('.s_datepicker').val("").datepicker("update");
        $(".search_opt").val(''); 
        $('.dataTables-example').DataTable().draw(); 
      }

      function apply_search() { $('.dataTables-example').DataTable().draw(); }

      $('.s_datepicker').datepicker({ todayBtn: "linked", keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy-mm-dd", todayHighlight:true, clearBtn: true, endDate:"<?php echo date("Y-m-d"); ?>" });

         
      $(document).ready(function() { setTimeout(function() { $('#alert_fadeout').fadeOut(3000); }, 8000 ); });
    </script>
	</body>
</html>