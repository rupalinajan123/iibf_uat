  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Ordinary Membership Registration Edit Page 
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
	<form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data" 
    action="<?php echo base_url()?>NonMember/editimages/">
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
           <!-- Basic Details box closed-->
 		<div class="box box-info">
       	 <div class="box-header with-border">
            <div style="float:right;">
            <a href="<?php echo base_url();?>NonMember/profile/" class="btn btn-info">Back</a>
            </div>
            </div>
          <div class="box-body">
            
            <?php //echo validation_errors(); ?>
              <?php if($this->session->flashdata('error')!=''){?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
              <?php } if($this->session->flashdata('success')!=''){ ?>
                <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                <?php echo $this->session->flashdata('success'); ?>
              </div>
             <?php } 
			 if(validation_errors()!=''){?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo validation_errors(); ?>
                </div>
              <?php } 
			 ?> 
       
                   <div class="form-group">
                	
                <label for="roleid" class="col-sm-3 control-label">
                <?php 
			   	if(is_file(get_img_name($this->session->userdata('cscnmregnumber'),'p')))
				{?>
                <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('cscnmregnumber'),'p');?><?php echo '?'.time(); ?>" height="100" width="100" >
                 <?php 
				}
				else
				{?>
               		  <img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
                  <?php 
				}?>
                </label>
                <input type="hidden" name="scannedphoto1_hidd" id="scannedphoto1_hidd" value="<?php echo get_actual_img_name($this->session->userdata('cscnmregnumber'),'p');?>">
                   
               <label for="roleid" class="col-sm-3 control-label">
               <?php 
			   	if(is_file(get_img_name($this->session->userdata('cscnmregnumber'),'s')))
				{?>
          		     <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('cscnmregnumber'),'s');?><?php echo '?'.time(); ?>" height="100" width="100">
                <?php 
				}
				else
				{?>
                	 <img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
                <?php 
				}?>
               </label>
               
                 <input type="hidden" name="scannedsignaturephoto1_hidd" id="scannedsignaturephoto1_hidd" value="<?php echo get_actual_img_name($this->session->userdata('cscnmregnumber'),'s');?>">
                    
                    <label for="roleid" class="col-sm-3 control-label">
              	<?php
                if(is_file(get_img_name($this->session->userdata('cscnmregnumber'),'pr')))
				{?>
                    <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('cscnmregnumber'),'pr');?><?php echo '?'.time(); ?>"  height="100" width="100">
                    
					<?php 
                    }
                    else
                    {?>
                          <img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
                      <?php 
                    }?>
                    </label>
                    
                      <input type="hidden" name="idproofphoto1_hidd" id="idproofphoto1_hidd" value="<?php echo get_actual_img_name($this->session->userdata('cscnmregnumber'),'pr');?>">
                </div>  
                   <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Uploaded Photo</label>
            <label for="roleid" class="col-sm-3 control-label">uploaded Signature</label>
            <label for="roleid" class="col-sm-3 control-label">Uploaded ID Proof</label>
         	</div>
           <?php 
			   	if(!is_file(get_img_name($this->session->userdata('cscnmregnumber'),'p')))
				{ // priyanka d- added comment -29-dec-22 to skip alrealy uploaded images
          ?>
                
                   <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Upload your scanned Photograph <span style="color:#F00">**</span></label>
                	<div class="col-sm-5">
                        <input  type="file" class="form-control" name="scannedphoto" id="scannedphoto"  autocomplete="off">
                    	 <input type="hidden" id="hiddenphoto" name="hiddenphoto">
                    	<div id="error_photo"></div>
                     <br>
                     <div id="error_photo_size"></div>
                     <span class="photo_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('scannedphoto');?></span>
                    </div>
                </div>
                
                <?php } ?>
                <?php 
			   	if(!is_file(get_img_name($this->session->userdata('cscnmregnumber'),'s')))
				{ // priyanka d- added comment -29-dec-22 to skip alrealy uploaded images
          ?>
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"> Upload your scanned Signature Specimen <span style="color:#F00">**</span></label>
                	<div class="col-sm-5">
                        <input  type="file" class="form-control" name="scannedsignaturephoto" id="scannedsignaturephoto"  autocomplete="off">
                         <input type="hidden" id="hiddenscansignature" name="hiddenscansignature">
                    <div id="error_signature"></div>
                     <br>
                     <div id="error_signature_size"></div>
                     
                     <span class="signature_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('scannedsignaturephoto');?></span>
                    </div>
                </div>
                <?php } ?>
                <?php
                if(!is_file(get_img_name($this->session->userdata('cscnmregnumber'),'pr')))
				{ // priyanka d- added comment -29-dec-22 to skip alrealy uploaded images
          ?>
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Upload your id proof <span style="color:#F00">**</span></label>
                	<div class="col-sm-5">
                        <input  type="file" class="form-control" name="idproofphoto" id="idproofphoto">
                         <input type="hidden" id="hiddenidproofphoto" name="hiddenidproofphoto">
                     <div id="error_dob"></div>
                     <br>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>
                <?php } ?>
                <div class="form-group">
                <label for="roleid" class="col-sm-2 control-label"> Note <span style="color:#F00">**</span></label>
                	<div class="col-sm-9">
                    1. Images format should be in JPG 8bit and size should be minimum 8KB and maximum 20KB.</br>
                    2. Image Dimension of Photograph should be 100(Width) * 120(Height) Pixel only</br>
                    3. Image Dimension of Signature should be 140(Width) * 60(Height) Pixel only</br>
                    4. Image Dimension of ID Proof should be 400(Width) * 420(Height) Pixel only. Size should be minimum 8KB and maximum 25KB.</br>
                    </div>
                </div>
                
            </div>
             
             <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit" onclick="javascript:return checkEditImage()">
                    </div>
              </div>
             </div>
                
             
             
        </div>
      </div>
     
      
      
    </section>
    </form>
  </div>
