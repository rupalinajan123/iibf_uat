<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Center Master 
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
                <input type="hidden" class="form-control" id="id" name="id"  value="<?php echo set_value('id');?>" >
               
                <label for="exam_code" class="col-sm-2 control-label">Exam Name *</label>
                	<div class="col-sm-3">
                      <select class="form-control" id="exam_name" name="exam_name" required >
                        <option value="">Select</option>
                        <?php if(count($exam_list)){
                                foreach($exam_list as $row){ 	?>
                        <option value="<?php echo $row['exam_code'];?>" <?php if($centerRes['exam_name'] == $row['exam_code']){echo "selected='selected'";} ?>><?php echo $row['description'];?></option>
                        <?php } } ?>
                      </select>
                      <span class="error"><?php echo form_error('exam_name');?></span>
                    </div>
                    
                    <label for="exam_period" class="col-sm-2 control-label">Exam Period *</label>
                     <div class="col-sm-3">
                        <select class="form-control" id="exam_period" name="exam_period" required >
                        <option value="">Select</option>
                        <?php if(count($exam_period)){
                                foreach($exam_period as $row1){ 	?>
                        <option value="<?php echo $row1['exam_period'];?>" <?php if($centerRes['exam_period'] == $row1['exam_period']){echo "selected='selected'";} ?>><?php echo $row1['exam_period'];?></option>
                        <?php } } ?>
                      </select>
                        <span class="error"><?php echo form_error('exam_period');?></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                	<label for="center_code" class="col-sm-2 control-label">Center Code *</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="center_code" name="center_code" placeholder="Center Code" required value="<?php echo $centerRes['center_code'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('center_code');?></span>
                        </div>
                    
                    <label for="center_name" class="col-sm-2 control-label">Center Name *</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="center_name" name="center_name" required placeholder="Center Name" value="<?php echo $centerRes['center_name'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('center_name');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="state_code" class="col-sm-2 control-label">State Code *</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="state_code" name="state_code" placeholder="State Code" required value="<?php echo $centerRes['state_code'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('state_code');?></span>
                        </div>
                    
                    <label for="state_description" class="col-sm-2 control-label">State Description  *</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="state_description" name="state_description" required placeholder="State Description" value="<?php echo $centerRes['state_description'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('state_description');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="exammode" class="col-sm-2 control-label">Exam Mode *</label>
                         <div class="col-sm-3">
                            <select class="form-control" id="exammode" name="exammode" required >
                                <option value="">Select</option>
                               	<option value="ON" <?php if($centerRes['exammode'] == "ON"){echo "selected='selected'";} ?>>ON</option>
                                <option value="OF" <?php if($centerRes['exammode'] == "OF"){echo "selected='selected'";} ?>>OFF</option>
                            </select>
                            
                             <span class="error"><?php echo form_error('exammode');?></span>
                        </div>
                </div>
             
             </div>
             
              <div class="box-footer">
                  <div class="col-sm-2 col-xs-offset-5">
                  <?php $last = $this->uri->total_segments();
						$id = $this->uri->segment($last);
				?>
		
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="<?php if(is_numeric($id)){ echo 'Update';}else{ echo 'Add';} ?>">
                   <a href="<?php echo base_url();?>admin/CenterMaster" class="btn btn-default pull-right">Back</a>
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
	$(document).ready(function() 
	{
	});

	$('#addForm').parsley('validate');	
</script>


</script>
 
<?php $this->load->view('admin/includes/footer');?>