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
      <?php $this->load->view('iibfbcbf/inspector/inc_sidebar_inspector'); ?>		
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('iibfbcbf/inspector/inc_topbar_inspector'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>View Profile</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/inspector/dashboard_inspector'); ?>">Dashboard</a></li>
              <li class="breadcrumb-item">Profile Settings</li>
							<li class="breadcrumb-item active"> <strong>View Profile</strong></li>
            </ol>
          </div>
					<div class="col-lg-2"> </div>
        </div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">								
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
                  </div>                  
                </div>
              </div>
              
              <div id="common_log_outer"></div>
            </div>					
          </div>
        </div>
      </div>				
      
      <?php $this->load->view('iibfbcbf/inspector/inc_footerbar_inspector'); ?>	
      
    </div>
    
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>		
		<?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>
    
    <?php 
      $this->load->view('iibfbcbf/common/get_logs_common_ajax_call', array('enc_pk_id'=>url_encode($this->session->userdata('IIBF_BCBF_LOGIN_ID')), 'module_slug'=>'inspector_action,inspector_password_action', 'log_title'=>'Inspector Log'));
    ?>	
		<?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>	
  </body>
</html>