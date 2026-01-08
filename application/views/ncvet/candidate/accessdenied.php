<!DOCTYPE html>
<html>
	<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('ncvet/inc_header'); ?> 
  </head>
	<body class="fixed-sidebar">
		<?php $this->load->view('ncvet/common/inc_loader'); ?>
		
		<div id="wrapper">
			<?php $this->load->view('ncvet/candidate/inc_sidebar_candidate'); ?>			
			<div id="page-wrapper" class="gray-bg">				
				<?php $this->load->view('ncvet/candidate/inc_topbar_candidate'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>Exams
						</h2>						
					</div>
					<div class="col-lg-2"> </div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox float-e-margins text-centerx">
                <div class="ibox-title"><h2>Exams</h2></div>
                <div class="ibox-content">
                  <h4>
                    <?php echo date("d F, Y. h:i A"); /* echo '<br>'.get_ip_address();  */ ?>	
                  </h4>
				   <?php if($this->session->flashdata('error_message')){?>

					<div class="callout callout-danger"><?php echo $this->session->flashdata('error_message')?></div>

					<?php }?>   
                </div>
							</div>
						</div>
					</div>
        </div>				
				
				<?php $this->load->view('ncvet/candidate/inc_footerbar_candidate'); ?>		
			</div>
		</div>
		<?php $this->load->view('ncvet/inc_footer'); ?>		
		<?php $this->load->view('ncvet/common/inc_bottom_script'); ?>		
	</body>
</html>