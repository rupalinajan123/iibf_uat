<!DOCTYPE html>
<html>
	<head>
		<?php $this->load->view('apply_elearning/inc_header'); ?>
  </head>
	
	<body class="hold-transition skin-blue layout-top-nav">
		<div class="wrapper">
			<?php $this->load->view('apply_elearning/inc_navbar'); ?>
			
			<div class="container">				
				<section class="content">
					<section class="content-header">
						<h1 class="register">Apply For E-learning</h1><br/>
          </section>
					
					<div class="col-md-12">  						
						<div  class ="row">
							<?php if($this->session->flashdata('error')!=''){?>								
								<div class="alert alert-danger alert-dismissible" id="error_id">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<?php echo $this->session->flashdata('error'); ?>
                </div>								
								<?php } 
								
								if($this->session->flashdata('success')!=''){ ?>
								<div class="alert alert-success alert-dismissible" id="success_id">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<?php echo $this->session->flashdata('success'); ?>
                </div>
              <?php } ?>							
							
							<div class="box box-info">
								<div class="col-sm-12 text-center" style="margin:20px 0 40px 0">
                  <a href="<?php echo site_url('ApplyElearning/index/ordinary') ?>" class="btn btn-info">Ordinary Member</a>&nbsp;&nbsp;
                  <a href="<?php echo site_url('ApplyElearning/index/non_ordinary') ?>" class="btn btn-info">Non Member</a>
                </div>
              </div>
            </div>
						
						<?php $this->load->view('apply_elearning/inc_footerbar'); ?>
          </div>
        </section>
      </div>
    </div>		
		
		<?php $this->load->view('apply_elearning/inc_footer'); ?>
    
    <script>	
			$( document ).ready( function () { $('.loading').delay(0).fadeOut('slow'); });
			/* $(document).ready(function() { setTimeout(function() { $('#alert_fadeout').fadeOut(3000); }, 8000 ); }); */
    </script>			
  </body>
</html>				