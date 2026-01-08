<?php $this->load->view('iibfdra/admin/includes/header');?>
<?php $this->load->view('iibfdra/admin/includes/sidebar');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Change Password
      </h1>
      <?php echo $breadcrumb; ?>
    </section>
	<form class="form-horizontal" name="draadminchangepass" id="draadminchangepass"  method="post" action="">
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
           <!-- Basic Details box closed-->
 		<div class="box box-info">
       	 <div class="box-header with-border">
            <div style="float:right;">
            <b>Note : Minimum is 6 and Maximum is 16 Characters</b>
            </div>
          </div>
          <div class="box-body">
            
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
                <label for="roleid" class="col-sm-3 control-label">Current Password <span class="red"> *</span></label>
                	<div class="col-sm-5">
                        <input  type="password" class="form-control" name="current_pass" id="current_pass"  autocomplete="off" data-parsley-minlength="6" data-parsley-maxlength="16" required>
                    	<div id="error_password"></div>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"> New Password <span class="red"> *</span></label>
                	<div class="col-sm-5">
                        <input  type="password" class="form-control" name="txtnpwd" id="txtnpwd"  autocomplete="off" required  data-parsley-minlength="6" data-parsley-maxlength="16">
                       
                    	<div id="error_txtnpwd"></div>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Re-type New Password <span class="red"> *</span></label>
                	<div class="col-sm-5">
                        <input  type="password" class="form-control" name="txtrpwd" id="txtrpwd" required data-equalto="#txtnpwd" data-parsley-minlength="6" data-parsley-maxlength="16">
                         
                     	<div id="error_txtrpwd"></div>
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
  
  <script src="<?php echo base_url(); ?>assets/js/parsley.min.js"></script>
  <script type="text/javascript">
  	$('#draadminchangepass').parsley('validate');
  </script>
  
<?php $this->load->view('iibfdra/admin/includes/footer');?>