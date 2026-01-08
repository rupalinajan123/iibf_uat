<?php $this->load->view('iibfdra/admin/includes/header');?>
<?php $this->load->view('iibfdra/admin/includes/sidebar');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Accredited Institution Master
      </h1>
     <?php echo $breadcrumb; ?>
    </section>
	<form class="form-horizontal" name="addForm" id="addForm" action="" method="post"> 
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
             <?php } ?> 
                
                <div class="form-group">
                	<label for="institute_code" class="col-sm-2 control-label">Institute Code *</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="institute_code" name="institute_code" placeholder="Institute Code" required value="<?php echo $institutionRes['institute_code'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-type="number">
                             <span class="error"><?php echo form_error('institute_code');?></span>
                        </div>
                    
                    <label for="institute_name" class="col-sm-2 control-label">Institute Name *</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="institute_name" name="institute_name" required placeholder="Institute Name" value="<?php echo $institutionRes['institute_name'];?>"  onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('institute_name');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="category_code" class="col-sm-2 control-label">Category Code *</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="category_code" name="category_code" placeholder="Category Code" required value="<?php echo $institutionRes['category_code'];?>"  onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('category_code');?></span>
                        </div>
                </div>
             </div>
             
              <div class="box-footer">
                  <div class="col-sm-2 col-xs-offset-5">
                  <?php $last = $this->uri->total_segments();
						$id = $this->uri->segment($last);
				?>
		
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="<?php if(is_numeric($id)){ echo 'Update';}else{ echo 'Add';} ?>">
                    <a href="<?php echo base_url();?>iibfdra/admin/InstitutionMaster" class="btn btn-default pull-right">Back</a>
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
	$('#addForm').parsley('validate');	
</script>
<?php $this->load->view('iibfdra/admin/includes/footer');?>