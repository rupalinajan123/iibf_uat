  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Ordinary Membership Registration Change Password Page
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
	<form class="form-horizontal" name="userschangepass" id="userschangepass"  method="post" action="">
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
           <!-- Basic Details box closed-->
 		<div class="box box-info">
       	 <div class="box-header with-border">
            <div style="float:right;">
            Note : Minimum is 6 and Maximum is 16 Characters
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
              <?php } ?> 
       
       
                     
                   
                
                
                   <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Current Password</label>
                	<div class="col-sm-5">
                        <input  type="password" class="form-control" name="current_pass" id="current_pass"  autocomplete="off" data-parsley-minlength="6" data-parsley-maxlength="16" required>
                    	<div id="error_password"></div>
                     <br>
                     <div id="error_photo_size"></div>
                     <span class="photo_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('scannedphoto');?></span>
                    </div>
                </div>
                
                
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"> New Password </label>
                	<div class="col-sm-5">
                        <input  type="password" class="form-control" name="txtnpwd" id="txtnpwd"  autocomplete="off" required  data-parsley-minlength="6" data-parsley-maxlength="16" data-equalto="#txtrpwd">
                       
                    <div id="error_txtnpwd"></div>
                     <br>
                     <div id="error_signature_size"></div>
                     
                     <span class="signature_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('scannedsignaturephoto');?></span>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Re-type New Password </label>
                	<div class="col-sm-5">
                        <input  type="password" class="form-control" name="txtrpwd" id="txtrpwd" required data-equalto="#txtnpwd" data-parsley-minlength="6" data-parsley-maxlength="16" >
                         
                     <div id="error_txtrpwd"></div>
                     <br>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>
                
                
                
            </div>
             
             <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
                    <input type="submit" class="btn btn-info" name="btn_password" id="btn_password" value="Submit">
                    </div>
              </div>
             </div>
                
             
             
        </div>
      </div>
     
      
      
    </section>
    </form>
  </div>
  
  
  <script type="text/javascript">

  $('#userschangepass').parsley('validate');

</script>
  

