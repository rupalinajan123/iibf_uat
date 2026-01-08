<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('ncvet/kyc/inc_header'); ?>    
  </head>
	<body class="fixed-sidebar">
  <?php $this->load->view('ncvet/common/inc_loader'); ?>
		
		<div id="wrapper">
    <?php $this->load->view('ncvet/kyc/inc_sidebar_admin'); ?>		
			<div id="page-wrapper" class="gray-bg">				
      <?php $this->load->view('ncvet/kyc/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2><?php echo $page_title; ?></h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('ncvet/kyc/kyc_dashboard'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item active"> <strong><?php echo $page_title; ?></strong></li>
						</ol>
					</div>
					<div class="col-lg-2"> </div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
                <div class="ibox-content">
                  <form style="display: none;" method="POST" id="search_form" class="search_form_common_all side-bg-color" action="javascript:void(0)" autocomplete="off">
                    <div class="form-group text-left">
                      <select class="form-control search_opt" name="s_module_type" id="s_module_type" >
                        <option value="">Select Module Name</option>                      
                        <option value="ncvet" selected>NCVET</option>                       
                        <!-- <option value="bcbf" selected>BCBF</option>                       
                        <option value="dra">DRA</option> -->                       
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
                          <th class="no-sort text-center">Sr. No.</th>
													<th class="text-center nowrap">Enrollment No.</th>
													<th class="text-center nowrap">Candidate Name</th>
													<!-- <th class="text-center nowrap">Exam Code</th> -->
													<th class="text-center nowrap">Enrollment Date</th>
													<th class="text-center nowrap">Mobile No.</th>
													<th class="text-center nowrap">Email ID</th>
													<th class="text-center nowrap">KYC Status</th>
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
				
				<?php $this->load->view('ncvet/kyc/inc_footerbar_admin'); ?>	
			</div>
		</div>
		<?php $this->load->view('ncvet/kyc/inc_footer'); ?>

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
						"url": '<?php echo site_url("ncvet/kyc/kyc_all/".$action_name."/".$kyc_status_list."/".$page_url); ?>',
						"type": "POST",	
            "data": function ( d ) 
						{
							d.s_module_type = $("#s_module_type").val();
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
						{"targets": [0,1,2,3,4,5,6], "className": "text-center"},
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
        $(".search_opt").val('ncvet'); 
        $('.dataTables-example').DataTable().draw(); 
      }
      
      function apply_search() { $('.dataTables-example').DataTable().draw(); }
    </script>

		<?php $this->load->view('ncvet/kyc/common/inc_bottom_script'); ?>	
	</body>
</html>