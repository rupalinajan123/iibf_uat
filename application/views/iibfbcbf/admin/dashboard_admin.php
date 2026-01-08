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
						<h2>Dashboard </h2>
						<ol class="breadcrumb"><li class="breadcrumb-item active"> <strong>Dashboard</strong></li></ol>
					</div>
					<div class="col-lg-2"> </div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox float-e-margins text-centerx">
								<div class="ibox-title"><h2>Welcome To Admin Dashboard</h2></div>
                <div class="ibox-content">
									<h4>
										<?php echo date("d F, Y. h:i A"); ?>	
                  </h4>
                </div>
              </div>
            </div>
          </div>

          <div class="row justify-content-md-centerx" style="display:nonex;">
            <div class="col-lg-4">
              <div class="ibox ">
                <div class="ibox-title">                    
                  <h5>Total Exam Registration : <?php echo $total_exam_registraion_cnt; ?></h5>
                  <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                </div>
                <div class="ibox-content">
                  <table class="table table-bordered mb-0">
                    <tbody>
                      <tr>
                        <td><b>BC/BF Exam (Basic)</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $total_basic_exam_reg_cnt; ?></b></a></td>
                      </tr>                      
                      <tr>
                        <td><b>BC/BF Exam (Advanced)</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $total_advanced_exam_reg_cnt; ?></b></a></td>
                      </tr>                      
                      <tr>
                        <td><b>Re-Attempt (Basic)</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $total_basic_re_attempt_exam_reg_cnt; ?></b></a></td>
                      </tr>                      
                      <tr>
                        <td><b>Re-Attempt (Advanced)</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $total_advanced_re_attempt_exam_reg_cnt; ?></b></a></td>
                      </tr>                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="ibox ">
                <div class="ibox-title">                    
                  <h5>Today's Exam Registration : <?php echo $today_exam_registraion_cnt; ?></h5>
                  <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                </div>
                <div class="ibox-content">
                  <table class="table table-bordered mb-0">
                    <tbody>
                      <tr>
                        <td><b>BC/BF Exam (Basic)</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $today_basic_exam_reg_cnt; ?></b></a></td>
                      </tr>                      
                      <tr>
                        <td><b>BC/BF Exam (Advanced)</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $today_advanced_exam_reg_cnt; ?></b></a></td>
                      </tr>                      
                      <tr>
                        <td><b>Re-Attempt (Basic)</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $today_basic_re_attempt_exam_reg_cnt; ?></b></a></td>
                      </tr>                      
                      <tr>
                        <td><b>Re-Attempt (Advanced)</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $today_advanced_re_attempt_exam_reg_cnt; ?></b></a></td>
                      </tr>                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="ibox ">
                <div class="ibox-title">                    
                  <h5>Total Training Registration : <?php echo $total_registraion_for_training_cnt; ?></h5>
                  <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                </div>
                <div class="ibox-content">
                  <table class="table table-bordered mb-0">
                    <tbody>
                      <tr>
                        <td><b>BC/BF Exam (Basic)</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $total_basic_reg_for_training_cnt; ?></b></a></td>
                      </tr>                      
                      <tr>
                        <td><b>BC/BF Exam (Advanced)</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $total_advance_reg_for_training_cnt; ?></b></a></td>
                      </tr>                      
                      <tr>
                        <td><b>Re-Attempt (Basic)</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $total_re_attempt_basic_reg_for_training_cnt; ?></b></a></td>
                      </tr>                      
                      <tr>
                        <td><b>Re-Attempt (Advanced)</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $total_re_attempt_advanced_reg_for_training_cnt; ?></b></a></td>
                      </tr>                      
                      <tr>
                        <td><b>Eligible for Examination but not Applied</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $total_eligible_for_exam; ?></b></a></td>
                      </tr>                      
                      </tr>                      
                      <tr>
                        <td><b>Candidates required to re-enroll for Training</b></td>
                        <td><a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm" style="min-width:60px;cursor:text;"><b><?php echo $total_re_enroll_for_training; ?></b></a></td>
                      </tr>                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>				
				
				<?php $this->load->view('iibfbcbf/admin/inc_footerbar_admin'); ?>		
			</div>
		</div>
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>		
		<?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>		
	</body>
</html>