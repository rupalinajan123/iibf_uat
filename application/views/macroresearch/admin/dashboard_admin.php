<!DOCTYPE html>
<html>
	<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('macroresearch/inc_header'); ?>    
   
  </head>
	<body class="fixed-sidebar">
		<?php $this->load->view('macroresearch/common/inc_loader'); ?>
		
		<div id="wrapper">
			<?php $this->load->view('macroresearch/admin/inc_sidebar_admin'); ?>			
			<div id="page-wrapper" class="gray-bg">				
				<?php $this->load->view('macroresearch/admin/inc_topbar_admin'); ?>
				
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
								
							</div>
						</div>
					</div>
        </div>				
				
				<?php $this->load->view('macroresearch/admin/inc_footerbar_admin'); ?>		
			</div>
		</div>
		<?php $this->load->view('macroresearch/inc_footer'); ?>		
		<?php $this->load->view('macroresearch/common/inc_bottom_script'); ?>		
	</body>
</html>