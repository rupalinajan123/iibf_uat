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
						<h2>Batch Details</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/inspector/dashboard_inspector'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/inspector/training_batches_inspector'); ?>">Batch List</a></li>
							<li class="breadcrumb-item active"> <strong>Batch Details</strong></li>
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
										<a href="<?php echo site_url('iibfbcbf/inspector/training_batches_inspector'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a>
                  </div>
                </div>
								<div class="ibox-content">
                  <div class="table-responsive">
										<table class="table table-bordered custom_inner_tbl" style="width:100%">
											<tbody>
                        <?php 
                          $sub_data['batch_data'] = $form_data;
                          $sub_data['training_schedule_file_path'] = $training_schedule_file_path;
                          $this->load->view('iibfbcbf/common/inc_training_batch_details_common',$sub_data);
                        ?> 
                        
                        <tr>
                          <td <?php if($form_data[0]['inspection_report_by_admin'] == "") { echo 'colspan="2"'; } ?>><b style="vertical-align:top">Assigned Inspector : </b><?php echo $form_data[0]['inspector_name']; ?></td>
                          <?php if($form_data[0]['inspection_report_by_admin'] != "")
                          { ?>
                            <td><b style="vertical-align:top">Inspection Report By Admin : </b><a href="<?php echo site_url('iibfbcbf/download_file_common/index/'.$enc_batch_id.'/inspection_report_by_admin'); ?>" class="example-image-link btn btn-success btn-sm">Download Inspection Report</a></td>
                          <?php } ?>
                        </tr>
                      </tbody>
                    </table>
                    
                    <div class="hr-line-dashed"></div>										
										<div class="text-center" id="submit_btn_outer">
                      <a href="<?php echo site_url('iibfbcbf/inspector/training_batches_inspector'); ?>" class="btn btn-danger">Back</a>	
                    </div>
                  </div>                  
                </div>
              </div>

              <div id="common_log_outer"></div>              
              
            </div>					
          </div>
        </div>
				<?php $this->load->view('iibfbcbf/inspector/inc_footerbar_inspector'); ?>		
      </div>
    </div>
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>	
		
    <?php  
      $this->load->view('iibfbcbf/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_batch_id, 'module_slug'=>'batch_action', 'log_title'=>'Training Batch Log'));
    ?>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>