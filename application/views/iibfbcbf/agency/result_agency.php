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
						
						<h2><?php if($center_code_enc != "" && $exam_code_enc != "") { echo 'Download Centrewise Result'; }else{ echo 'Download Result'; } ?></h2> 
						
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency'); ?>">Dashboard</a></li>
							<?php if($center_code_enc != "" && $exam_code_enc != "") { ?><li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/Result_agency'); ?>">Download Result</a></li><?php } ?>
							<li class="breadcrumb-item active"> <strong><?php if($center_code_enc != "" && $exam_code_enc != "") { echo 'Download Centrewise Result'; }else{ echo 'Download Result'; } ?></strong></li>
						</ol>
					</div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">

								<?php if($center_code_enc != "" && $exam_code_enc != "")  { ?>
                  <div class="ibox-title">
                    <div class="ibox-tools"> 
                      <a href="<?php echo site_url('iibfbcbf/agency/Result_agency'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a> 
                    </div>
                  </div>   
                <?php } ?>

                <div class="ibox-content">
                <form method="POST" id="search_form" class="search_form_common_all" action="javascript:void(0)" autocomplete="off">
                    
                    <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency' && $center_code_enc == "" && $exam_code_enc == "") { ?>
                      <div class="form-group text-left">
                        <select class="form-control search_opt" name="s_centre" id="s_centre" >
                          <option value="">Select Centre</option>
                            <?php if(count($agency_centre_data) > 0)
                            {
                              foreach($agency_centre_data as $res)
                              { ?>
                                <option value="<?php echo $res['center_code']; ?>"><?php echo $res['center_name']; ?></option>
                              <?php }
                            } ?>
                        </select>
                      </div>                    
                    <?php } ?>

                    <div class="form-group text-left">
                      <input type="text" class="form-control search_opt" name="s_term" id="s_term" placeholder="Search">
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
                          <th class="no-sort text-center" style="width:60px;">Sr. No.</th>
													<th class="text-center">Centre Name</th>
													<th class="text-center">Application</th>
													<th class="text-center"><?php if($center_code_enc == "" && $exam_code_enc == ""){ echo "No of Candidates"; }else{ echo "Registration Number"; } ?></th>
													<th class="text-center">Exam Period</th>
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
		
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>

    <link href="<?php echo auto_version(base_url('assets/iibfbcbf/css/plugins/dataTables/responsive.dataTables.min.css')); ?>" rel="stylesheet">
    <script src="<?php echo auto_version(base_url('assets/iibfbcbf/js/plugins/dataTables/dataTables.responsive.min.js')); ?>"></script>
		    
    <script language="javascript">
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
						"url": '<?php echo site_url("iibfbcbf/agency/result_agency/get_result_data_ajax"); ?>',
						"type": "POST",	
            "data": function ( d ) 
						{
              <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency') { ?> d.s_centre = $("#s_centre").val(); <?php } ?>
              <?php if($center_code_enc != '' && $exam_code_enc != '') { ?> 
              	d.exam_code_enc = '<?php echo $exam_code_enc; ?>'; 
              	d.center_code_enc = '<?php echo $center_code_enc; ?>';   
              <?php } ?>
              d.s_term = $("#s_term").val(); 
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
						{"targets": [3], "className": "text-center"}, 
						{"targets": [4], "className": "text-center"},
						{"targets": [5], "className": "text-center"},
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
        $(".search_opt").val(''); $('.dataTables-example').DataTable().draw(); 
      }

      function apply_search() { $('.dataTables-example').DataTable().draw(); }
    </script>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>	
	</body>
</html>