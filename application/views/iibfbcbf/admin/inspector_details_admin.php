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
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>Inspector Details</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/dashboard_admin'); ?>">Dashboard</a></li>
              <li class="breadcrumb-item">Masters</li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/inspector_master'); ?>">Inspector Master</a></li>
							<li class="breadcrumb-item active"> <strong>Inspector Details</strong></li>
            </ol>
          </div>
					<div class="col-lg-2"> </div>
        </div>			
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-title">
									<div class="ibox-tools">
										<a href="<?php echo site_url('iibfbcbf/admin/inspector_master'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a>
                  </div>
                </div>
								<div class="ibox-content">
                  <div class="table-responsive">
										<table class="table table-bordered custom_inner_tbl" style="width:100%">
											<tbody>
                        <?php 
                          $sub_data['inspector_data'] = $form_data;
                          $this->load->view('iibfbcbf/common/inc_inspector_details_common',$sub_data);
                        ?>
                      </tbody>
                    </table>
                    
                    <div class="hr-line-dashed"></div>										
										<div class="text-center" id="submit_btn_outer">
                      <a href="<?php echo site_url('iibfbcbf/admin/inspector_master'); ?>" class="btn btn-danger">Back</a>	
                    </div>
                  </div>                  
                </div>
              </div>
              <div id="common_log_outer"></div>
            </div>					
          </div>
        </div>
				<?php $this->load->view('iibfbcbf/admin/inc_footerbar_admin'); ?>		
      </div>
    </div>
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>		

    <?php  
      $this->load->view('iibfbcbf/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_inspector_id, 'module_slug'=>'inspector_action,inspector_password_action', 'log_title'=>'Inspector Log'));
    ?>
		<?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>