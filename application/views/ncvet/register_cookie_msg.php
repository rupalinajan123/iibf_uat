<!DOCTYPE html>
<html>
  <head>
    <?php $this->load->view('ncvet/inc_header'); ?>
  </head>
  
  <body class="gray-bg">
    <?php $this->load->view('ncvet/common/inc_loader'); ?>
    <div class="d-flex logo" style="z-index:1;"><img src="<?php echo base_url('assets/ncvet/images/iibf_logo.png'); ?>" class="img-fluid" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">   <h3 class="mb-0" style="    font-size: 20px;">INDIAN INSTITUTE OF BANKING & FINANCE <br>
								ISO 21001:2018 Certified</h3></div>
    <div class="container">        
    <?php  $mode ='Add';  ?>
       
      <div class="admin_login_form animated fadeInDown" style="width: 100%; max-width: none; margin-top:110px"> 
		<form method="post" action="<?php echo site_url('ncvet/candidate_registration/addmember'); ?>" id="add_candidate_form" enctype="multipart/form-data" autocomplete="off">
     
                    <h4 class="custom_form_title" style="margin: -15px -20px 15px -20px !important;">Please Wait, Your Transaction is under process</h4>
                    
                   
                  </form>
     </div>
    </div>
    
    
    <?php $this->load->view('ncvet/inc_footer'); ?>
        
    <?php $this->load->view('ncvet/common/inc_common_validation_all'); ?>
    <?php $this->load->view('ncvet/common/inc_common_show_hide_password'); ?>
	
		<?php $this->load->view('ncvet/common/inc_cropper_script', array('page_name'=>'candidate_enrollment')); ?>
    
    
    <?php $this->load->view('ncvet/common/inc_bottom_script'); ?>
  </body>
</html>