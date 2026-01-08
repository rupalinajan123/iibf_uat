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
						<h2>Candidate Listing</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/transaction_details_agency'); ?>">Transaction Details</a></li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/transaction_details_agency/payment_receipt_agency/'.$enc_pt_id); ?>">Payment Receipt</a></li>
							<li class="breadcrumb-item active"> <strong>Candidate Listing</strong></li>
						</ol>
					</div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
                <div class="ibox-title">
									<div class="ibox-tools">
										<a href="<?php echo site_url('iibfbcbf/agency/transaction_details_agency/payment_receipt_agency/'.$enc_pt_id); ?>" class="btn btn-primary custom_right_add_new_btn">Back</a>                    
                  </div>
                </div>

                <div class="ibox-content">
                  <div class="table-responsive">
										<table class="table table-bordered table-hover dataTables-example" style="width:100%">
											<thead>
												<tr> 
                          <th class="no-sort text-center" style="width:60px;">Sr. No.</th>
													<th class="text-center">Candidate Name</th>
													<th class="text-center">Training Id</th>
													<th class="text-center">Amount</th>
													<th class="text-center no-sort" style="width:90px;">Action</th>
												</tr>
											</thead>
											
											<tbody></tbody>
											
											<tfoot>
												<tr>
                          <th></th>
                          <th></th>
                          <th class="text-right"><b>Total</b></th>
													<th class="text-right"><b><?php echo $payment_data[0]['amount']; ?></b></th>
                          <th></th>
												</tr>
											</tfoot>
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
						"url": '<?php echo site_url("iibfbcbf/agency/transaction_details_agency/get_payment_receipt_candidate_listing_data_ajax"); ?>',
						"type": "POST",
            "data": function ( d ) 
						{
              d.enc_pt_id = "<?php echo $enc_pt_id; ?>";              
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
						{"targets": [2], "className": "text-center"},
						{"targets": [3], "className": "text-right"},
					],
					"aaSorting": [],
					"stateSave": false,		          			
				});
      });     
    </script>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>	
	</body>
</html>