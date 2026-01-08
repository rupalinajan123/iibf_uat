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
		<h4 class="register">NCVET Enrollment Recovery Form</h4>
		<form name="getDetailsForm" id="getDetailsForm" method="post" action="<?php echo base_url(); ?>ncvet/recovery">
          <br />
          <div class="">
            <div class="col-sm-12 col-md-3">
				<label>Membership No.</label>
              <input type="text" class="form-control" id="regnumber" name="regnumber" placeholder="Membership No." required value="<?php if (isset($row['regnumber'])) { echo $row['regnumber'];} else { echo set_value('regnumber'); }
?>" <?php if (isset($row['regnumber'])) { echo "readonly='readonly'";} elseif (set_value('regnumber')) { echo "readonly='readonly'"; } ?> style="border-color:#000;" title="Membership No.">
            </div>
            <div class="col-sm-12 col-md-3">
              <?php 
			  	if (isset($row['regnumber']) || set_value('regnumber')) {
				?>
              <a href="<?php echo base_url();?>Ncvet/recovery" class="btn btn-warning" id="modify" style="">Reset</a>
              <input type="submit" class="btn btn-info" name="btnGetDetails" id="btnGet" value="Search" style="display:none;">
              <?php
				} 
				else
				{
				?><br>
              <input type="submit" class="btn btn-info" name="btnGetDetails" id="btnGetDetails" value="Search">
              <?php 
				 } 
				  ?>
            </div>
            <div> 
              <!-- <div class="col-sm-12" align="center"> <span style="color:#F00; font-size:14px;">Please insert your 'Membership No.' and click on 'Get Details' button. All below details will get filled automatically.</span> </div>--> 
            </div>
          </div>
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