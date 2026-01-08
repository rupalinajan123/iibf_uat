<!DOCTYPE html>
<html>
  <head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta name="robots" content="noindex, nofollow">
  <meta name="googlebot" content="noindex, nofollow">
   
	
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
		
    <?php $this->load->view('supervision/inc_header'); ?>   
		 
  </head>
	<body class="fixed-sidebar">
  <?php $this->load->view('supervision/common/inc_loader'); ?>
		
		<div id="wrapper">
    <?php $this->load->view('supervision/admin/inc_sidebar_admin'); ?>		
			<div id="page-wrapper" class="gray-bg">				
      <?php $this->load->view('supervision/admin/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>Send E-mail </h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('supervision/admin/dashboard_admin'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item active"> <strong>Send E-mail</strong></li>
						</ol>
					</div>
					<div class="col-lg-2"> </div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-content">
									<form method="post" action="<?php echo site_url('supervision/admin/candidate/send_mail_to_candidate/'.$enc_id); ?>" id="change_pass_form" class="admin_form_all" enctype="multipart/form-data" autocomplete="off">										
										<div class="row">
										<div class="col-xl-12 col-lg-12">
												<div class="form-group login_password_common">
													<label for="mail_subject" class="form_label">Mail Subject <sup class="text-danger">*</sup></label>
													<input type="text" class="form-control custom_input col-md-10" name="mail_subject" id="mail_subject" value="<?php echo set_value('mail_subject'); ?>" placeholder="Mail Subject"  required minlength="8" maxlength="255">
													<?php if(form_error('mail_subject')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('mail_subject'); ?></label> <?php } ?>
                          
												</div>
											</div>
                      <div class="col-xl-12 col-lg-12">
												<div class="form-group login_password_common">
													<label for="mail_subject" class="form_label">Mail content <sup class="text-danger">*</sup></label>
													<textarea  class="form-control" name="mail_content" id="mail_content"><?php echo set_value('mail_content');?></textarea>
												</div>
											</div>
											
											
										</div>
										
										<div class="hr-line-dashed mt-1"></div>										
										<div class="row">
											<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="submit_btn_outer">	
												<button class="btn btn-primary" type="submit" value="submit">Send now</button>												
                      </div>
										</div>
									</form>
								</div>               
							</div> 

              <div id="common_log_outer"></div>   

						</div>
					</div>
				</div>				
				
				<?php $this->load->view('supervision/admin/inc_footerbar_admin'); ?>	
			</div>
			
		</div>
		<?php $this->load->view('supervision/inc_footer'); ?>		


		<?php $this->load->view('supervision/common/inc_common_validation_all'); ?>
		
      <script type="text/javascript" src="<?php echo base_url(); ?>/assets/ckeditor/ckeditor.js"></script>
		
		<script type="text/javascript">
			var editor =CKEDITOR.replace('mail_content', { height: 200,
      width: 1000, 
			
      removeButtons: 'PasteFromWord'});			
				</script>
	
		<?php $this->load->view('supervision/common/inc_bottom_script'); ?>	
	</body>
</html>