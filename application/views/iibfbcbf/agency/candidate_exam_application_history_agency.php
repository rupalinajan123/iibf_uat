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
						<h2>Candidate exam application history</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item active"> <strong>Candidate exam application history</strong></li>
						</ol>
					</div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-content">
                  <form method="POST" id="search_form" class="search_form_common_all side-bg-color" action="" autocomplete="off">
                    <div class="form-group text-left" style="width:400px;">
                      <input type="text" class="form-control" name="training_id_or_regnumber" id="training_id_or_regnumber" placeholder="Enter Training ID or Registration Number" value="<?php if(set_value('training_id_or_regnumber')) { echo set_value('training_id_or_regnumber'); } else { echo $training_id_or_regnumber; } ?>">
                      <?php if(form_error('training_id_or_regnumber')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('training_id_or_regnumber'); ?></label> <?php } ?>
                    </div>
                    
                    <div class="form-group" style="width:auto;">
                      <button type="submit" class="btn btn-primary">Submit</button>
                      <a class="btn btn-danger" href="<?php echo site_url('iibfbcbf/agency/candidate_exam_application_history_agency'); ?>">Clear</a>
                    </div>
                  </form>		
                  
                  <?php $this->load->view('iibfbcbf/common/inc_candidate_exam_application_history_common', $data); ?>
								</div>                
              </div>
						</div>
					</div>
				</div>
				<?php $this->load->view('iibfbcbf/agency/inc_footerbar_agency'); ?>			
			</div>
		</div>
		
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>

    <?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>
    <script type="text/javascript">
      function validate_input(input_id) { $("#"+input_id).valid(); }
      $(document ).ready( function() 
			{
        $("#search_form").validate( 
				{
          onkeyup: function(element) 
          {
            $(element).valid();
          },          
          rules:
					{
            training_id_or_regnumber:{ required: true, remote: { url: "<?php echo site_url('iibfbcbf/agency/candidate_exam_application_history_agency/validation_check_training_id_or_regnumber/0/1'); ?>", type: "post" } },
          },
					messages:
					{
            training_id_or_regnumber: { required: "Please Enter Training ID or Registration Number", remote:"Please Enter correct Training ID or Registration Number" },
					}
				});

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
          //"dom": '<"top"lf><"clear"><i>rt<"bottom row"<"col-sm-12 col-md-5" and i><"col-sm-12 col-md-7" and p>><"clear">',
					pageLength: 10,
					responsive: true,
          rowReorder: false,					
					"aaSorting": [],
					"stateSave": false,		          			
				});
			});
		</script>

    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>	
	</body>
</html>