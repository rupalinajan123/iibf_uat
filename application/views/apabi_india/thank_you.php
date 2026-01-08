<!DOCTYPE html>
<html>
  <head>
    <?php $this->load->view('apabi_india/inc_header'); ?>
    <link href="<?php echo auto_version(base_url('assets/apabi_india/css/apabi_india_registration.css')); ?>" rel="stylesheet">
  </head>
  
  <body class="gray-bg">
    <?php $this->load->view('apabi_india/inc_loader'); ?>
    <?php $this->load->view('apabi_india/inc_apabi_header'); ?>
    
    <div class="container">
      <div class="admin_login_form animated fadeInDown" style="width: 100%; max-width: none; margin-top:40px"> 
        <div style="text-align: center; <?php if($type == 'success') { echo 'color: green;'; } else { echo 'color: red;'; } ?>  font-weight: 600;  margin: 50px auto;font-size:26px;"><?php echo $message; ?></div> 
      </div>
    </div>    
    
    <?php $this->load->view('apabi_india/inc_footer'); ?>
    <?php $this->load->view('apabi_india/inc_bottom_script'); ?>
  </body>
</html>