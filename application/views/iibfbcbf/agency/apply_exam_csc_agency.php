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
                <div class="ibox-content">
                  <form class="m-t" action="<?php echo site_url('iibfbcbf/agency/apply_exam_csc_agency/index/'.$enc_exam_code); ?>" method="post" enctype="multipart/form-data" id="iibf_bcbf_apply_individual_exam_form" autocomplete="off" style="max-width: 400px;	border: 2px solid #00bdd5;	padding: 30px 30px;	margin: 20px auto;">
                    <div class="form-group">
                      <label for="training_id" class="form_label">Training ID or Registration Number <sup class="text-danger">*</sup></label>
                      <input type="text" name="training_id" id="training_id" value="<?php if(set_value('training_id') != "") { echo set_value('training_id'); } ?>" class="form-control" placeholder="Training ID or Registration Number *" required />
                      <?php if (form_error('training_id') != "") { ?><div class="clearfix"></div> <div class="ci_error_msg"><?php echo form_error('training_id'); ?></div><?php } ?>
                    </div>
                              
                    <button type="submit" class="btn btn-primary block full-width btn-login">Get Details</button>
                  </form>
								</div>                
              </div>
						</div>
					</div>
				</div>
				<?php $this->load->view('iibfbcbf/agency/inc_footerbar_agency'); ?>			
			</div>
		</div>
		
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>

    <?php if ($error) { ?><script>sweet_alert_error("<?php echo $error; ?>"); </script><?php } ?>
    
    <script type="text/javascript" src="<?php echo auto_version(base_url('assets/iibfbcbf/jquery_validation/jquery.validate.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo auto_version(base_url('assets/iibfbcbf/jquery_validation/jquery.validate_additional.js')); ?>"></script>
    
    <script type="text/javascript">
      $(document).ready(function() 
      {
        $.validator.addMethod("required", function(value, element) { if ($.trim(value).length == 0) { return false; } else { return true; } });
        
        $("#iibf_bcbf_apply_individual_exam_form").validate(
        {
          onblur: function(element) { $(element).valid(); },
          rules: 
          { 
            training_id: { required: true }
          },
          messages: 
          {
            training_id: { required: "Please enter the Training ID or Registration Number" }
          },
          submitHandler: function(form) 
          {
            $("#page_loader").show();
            form.submit();
          }
        });
      });
    </script>
    
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>	
	</body>
</html>