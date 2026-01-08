<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('supervision/inc_header'); ?>    
	</head>
	<body class="fixed-sidebar">
		<?php $this->load->view('supervision/common/inc_loader'); ?>
		
		<div id="wrapper">
			<?php $this->load->view('supervision/candidate/inc_sidebar_candidate'); ?>		
			<div id="page-wrapper" class="gray-bg">				
				<?php $this->load->view('supervision/candidate/inc_topbar_candidate'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>Exam Setting</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('supervision/candidate/dashboard_candidate'); ?>">Dashboard</a></li>
              <li class="breadcrumb-item active">Exam Setting</li>
						</ol>
					</div>
					<div class="col-lg-2"> </div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
				  <div class="bcbf_wrap">						
						<div class="half-circle"></div>
						<div class="row justify-content-centre">							
							<div class="ibox admin_login_form">
								<div class="text-center"><i class="fa fa-key bcbf-lock" aria-hidden="true"></i><p>Exam Setting</P></div>
								<div class="ibox-content border-0">									
									<form method="post" action="<?php echo site_url('supervision/candidate/dashboard_candidate/'); ?>" id="change_pass_form" class="admin_form_all" enctype="multipart/form-data" autocomplete="off">
										<div class="row justify-content-centre">											
                      <div class="col-xl-12 col-lg-12">
												<div class="form-group login_password_common">
													<label for="current_pass_candidate" class="form_label">Exam <sup class="text-danger">*</sup></label>
													<select required name="exam_code_period" class="form-control select_exam">
                            
                          <?php foreach($exams as $exam) {
                            ?>
                              <option <?php if($form_data[0]['exam_code'] == $exam['exam_code'] && $form_data[0]['exam_period'] == $exam['exam_period']) echo'selected' ?> value="<?php echo $exam['exam_code'] ?>-<?php echo $exam['exam_period'] ?>"><?php echo $exam['exam_name'] ?> - <?php echo $exam['exam_month'] ?></option>
                            <?php
                          } ?>
                          </select>
                          
												</div>
											</div>
										
											
											<div class="hr-line-dashed mt-1"></div>										
											<div class="col-xl-12 col-lg-12">
												<div class="d-flex justify-content-between" id="submit_btn_outer">	
													<button class="btn btn-submit" type="submit" value="submit">Save Details</button>
													<button class="btn btn-submit" type="button" value="reset" onclick="reset_form()">Reset </button>												
												</div>
											</div>
										</div>										
									</div>
								</form>
							</div>               
						</div>						
						
						<div id="common_log_outer"></div>
					</div>
				</div>
			</div>				
			
			<?php $this->load->view('supervision/candidate/inc_footerbar_candidate'); ?>	
			
		</div>
		
		<?php $this->load->view('supervision/inc_footer'); ?>		
		<?php $this->load->view('supervision/common/inc_common_validation_all'); ?>
		
		<?php $this->load->view('supervision/common/inc_bottom_script'); ?>	
	</body>
</html>