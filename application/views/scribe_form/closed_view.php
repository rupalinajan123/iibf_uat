<!DOCTYPE html>
<html>
	<head>
		<?php $this->load->view('scribe_form/inc_header'); ?>
		
		<style type="text/css">
		.container{
			padding-top: 5%;
		}
		#register{
			font-size: 25px;
		}
		</style>
		
	</head>
	
	<body class="hold-transition skin-blue layout-top-nav">
		<div class="wrapper">
			<header class="main-header"> 
				<nav class="navbar navbar-static-top">
					<div class="short_logo"> <img src="<?php echo base_url();?>assets/images/iibf_logo_short.png"> </div>
					<div class="login-logo"><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br>
					<small>(An ISO 21001:2018 Certified)</small></a></div>
				</nav>
			</header>
			
			<div class="container">				
				
					<section class="content-header">
						<!-- <h1>SORRY! Registration is closed</h1> -->
						<h1 id="register" class="register">SORRY! Registration is closed</h1><br/>
					</section>
					
				<?php $this->load->view('scribe_form/inc_footerbar'); ?>
			</div>
		</div>		
		
		<?php $this->load->view('scribe_form/inc_footer'); ?>

		</body>
	</html>				