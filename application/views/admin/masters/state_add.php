<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	<section class="content-header">
    	<h1>State Master</h1>
     	<?php echo $breadcrumb; ?>
    </section>
	<form class="form-horizontal" name="stateAddForm" id="stateAddForm" action="" method="post">
    <!-- Main content -->
    	<section class="content">
      		<div class="row">
       	    	<div class="col-md-12">
          			<!-- Horizontal Form -->
          			<div class="box box-info">
            			<div class="box-header with-border">
              				<h3 class="box-title">Add</h3>
            			</div>
            			<!-- /.box-header -->
            			<!-- form start -->
                        <div class="box-body">
							<?php if($this->session->flashdata('error')!=''){?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <?php echo $this->session->flashdata('error'); ?>
                            	</div>
                            <?php } if($this->session->flashdata('success')!=''){ ?>
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <?php echo $this->session->flashdata('success'); ?>
                                </div>
                            <?php } ?> 
                            <div class="form-group">
                            	<label for="state_code" class="col-sm-2 control-label">State Code *</label>
                            	<div class="col-sm-3">
                                    <input type="text" class="form-control" id="state_code" name="state_code" placeholder="State Code" required value="<?php echo set_value('state_code');?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/" data-parsley-maxlength="3">
                             		<span class="error"><?php echo form_error('state_code');?></span>
                            	</div>
                            
                            	<label for="state_name" class="col-sm-2 control-label">State Name *</label>
                            	<div class="col-sm-3">
                            		<input type="text" class="form-control" id="state_name" name="state_name" placeholder="State Name" required value="<?php echo set_value('state_name');?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/" >
                             		<span class="error"><?php echo form_error('state_name');?></span>
                                </div>
							</div>
                             <div class="form-group">
                            	<label for="start_pin" class="col-sm-2 control-label">Start Pin</label>
                            	<div class="col-sm-3">
                                    <input type="text" class="form-control" id="start_pin" name="start_pin" placeholder="Start Pin" value="<?php echo set_value('start_pin');?>" onkeypress="return number(event);" data-parsley-type="number" data-parsley-maxlength="6">
                             		<span class="error"><?php echo form_error('start_pin');?></span>
                            	</div>
                            
                            	<label for="end_pin" class="col-sm-2 control-label">End Pin</label>
                            	<div class="col-sm-3">
                            		<input type="text" class="form-control" id="end_pin" name="end_pin" placeholder="End Pin" value="<?php echo set_value('end_pin');?>" onkeypress="return number(event);" data-parsley-type="number" data-parsley-maxlength="6">
                             		<span class="error"><?php echo form_error('end_pin');?></span>
                                </div>
							</div>
                            <div class="form-group">
                            	<label for="zone_code" class="col-sm-2 control-label">Zone Code</label>
                            	<div class="col-sm-3">
                                    <input type="text" class="form-control" id="zone_code" name="zone_code" placeholder="Zone Code" value="<?php echo set_value('zone_code');?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/" data-parsley-maxlength="2">
                             		<span class="error"><?php echo form_error('zone_code');?></span>
                            	</div>
                                
                                <label for="state_no" class="col-sm-2 control-label">State No</label>
                            	<div class="col-sm-3">
                            		<input type="text" class="form-control" id="state_no" name="state_no" placeholder="State No" value="<?php echo set_value('state_no');?>" onkeypress="return number(event);" data-parsley-type="number" data-parsley-maxlength="2">
                             		<span class="error"><?php echo form_error('state_no');?></span>
                                </div>
                                
                                
                            </div>
						</div>
                        <div class="box-footer">
                              <div class="col-sm-2 col-xs-offset-5">
                              		<?php 
										$last = $this->uri->total_segments();
										$id = $this->uri->segment($last);
									?>
                              		<input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="<?php if(is_numeric($id)){ echo 'Update';}else{ echo 'Add';} ?>">
                                   <a href="<?php echo base_url();?>admin/stateMaster" class="btn btn-default pull-right">Back</a>
                              </div>
                        </div>
					</div>
				</div>
			</div>
		</section>
	</form>
</div>
<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">
<script src="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.css">
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script src="<?php echo base_url()?>js/validation.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
	   $('#stateAddForm').parsley('validate');	
	});
</script>
</script>
<?php $this->load->view('admin/includes/footer');?>