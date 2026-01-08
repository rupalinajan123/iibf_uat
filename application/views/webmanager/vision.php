<!DOCTYPE html>
<html>
	<head>
    <?php $this->load->view('webmanager/includes/header');?>
	</head>
	
	<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
			<?php $this->load->view('webmanager/includes/topbar'); ?>
			<?php $this->load->view('webmanager/includes/sidebar'); ?>
			
			<div class="content-wrapper" style="min-kash: 946px;">
				<section class="content">
					<div id="custom_msg_outer"></div>
					
					<div class="">
						<div class="row">
							<div class="col-lg-12">
								<h4 class="title_common">Vision Count</h4>
								<form id="myForm" name="myForm" method="post" action="" enctype="multipart/form-data" role="form">
									<input type="hidden" id="security_token" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
									<div class="row">									
										<div class="col-lg-6">								
											<div class="form-group">
												<label for="from_date">From Date</label>
												<input type="text" name="from_date" id="from_date" class="form-control" value="<?php if(set_value('from_date')) { echo set_value('from_date'); } else { echo $from_date; } ?>" placeholder="From Date">
											</div>
										</div>									
										
										<div class="col-lg-6">								
											<div class="form-group">
												<label for="to_date">To Date</label>
												<input type="text" name="to_date" id="to_date" class="form-control" value="<?php if(set_value('to_date')) { echo set_value('to_date'); } else { echo $to_date; } ?>" placeholder="To Date">
											</div>
										</div>
									</div>
									
									<div class="row">
										<?php $this->load->view('webmanager/includes/common_count'); ?>
										
										<div class="col-lg-12">
											<div class="form-group" style="margin: 0 auto;">
												<input type="submit" id="submit" name="submit" class="btn btn-info" value="Submit"> 
												<a href="<?php echo site_url('webmanager/vision'); ?>" class="btn btn-info">Clear</a>
											</div>								
										</div>								
									</div>								
								</form>
							</div>
						</div>
					</div>
				</section>
			</div>
			<?php $this->load->view('webmanager/includes/footer');?>
			<script>$( document ).ready( function () { $('#page_loader').delay(0).fadeOut('slow'); });</script>
		</div>
	</body>
</html>