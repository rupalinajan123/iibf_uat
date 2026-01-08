<?php $this->load->view('iibfdra/Version_2/admin/includes/header');?>
<?php $this->load->view('iibfdra/Version_2/admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Eligible Master 
      </h1>
     <?php echo $breadcrumb; ?>
    </section>
	<form class="form-horizontal" name="usersAddForm" id="usersAddForm" action="" method="post">
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
                      <select class="form-control" id="exam_code" name="exam_code" required >
                        <option value="">Select</option>
                        <?php if(count($exam_list)){
                                foreach($exam_list as $row){ 	?>
                        <option value="<?php echo $row['exam_code'];?>" <?php if($eligibleRes['exam_code'] == $row['exam_code']){echo "selected='selected'";} ?>><?php echo $row['description'];?></option>
                        <?php } } ?>
                      </select>
                      <span class="error"><?php echo form_error('exam_code');?></span>
                    </div>
                    
                    <label for="eligible_period" class="col-sm-2 control-label">Eligible Period *</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="eligible_period" name="eligible_period" required placeholder="Elgible Period" value="<?php echo $eligibleRes['eligible_period'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/" data-parsley-maxlength="3">
                             <span class="error"><?php echo form_error('eligible_period');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="part_no" class="col-sm-2 control-label">Part No</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control input-selector" id="part_no" name="part_no" placeholder="Part Number" value="<?php echo $eligibleRes['part_no'];?>" onkeypress="return number(event);" data-parsley-type="number" data-parsley-maxlength="1">
                             <span class="error"><?php echo form_error('part_no');?></span>
                        </div>
                    
                    <label for="member_no" class="col-sm-2 control-label">Member No</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="member_no" name="member_no" placeholder="Member No" value="<?php echo $eligibleRes['member_no'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('member_no');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="member_type" class="col-sm-2 control-label">Member Type</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="member_type" name="member_type" placeholder="Member Type" value="<?php echo $eligibleRes['member_type'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/" data-parsley-maxlength="2">
                             <span class="error"><?php echo form_error('member_type');?></span>
                        </div>
                    
                   <label for="exam_status" class="col-sm-2 control-label">Exam Status</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="exam_status" name="exam_status" placeholder="Exam Status" value="<?php echo $eligibleRes['exam_status'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/" data-parsley-maxlength="1">
                             <span class="error"><?php echo form_error('exam_status');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="app_category" class="col-sm-2 control-label">APP CATEGORY</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="app_category" name="app_category" placeholder="APP CATEGORY" value="<?php echo $eligibleRes['app_category'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/" data-parsley-maxlength="2">
                             <span class="error"><?php echo form_error('app_category');?></span>
                        </div>
                    
                   <label for="fees" class="col-sm-2 control-label">FEES</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control input-selector" id="fees" name="fees" placeholder="FEES" value="<?php echo $eligibleRes['fees'];?>" onkeypress="number(event);" data-parsley-type="number">
                             <span class="error"><?php echo form_error('fees');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="training_institute" class="col-sm-2 control-label">Training Institute *</label>
                	<div class="col-sm-3">
                      <select class="form-control" id="institute_code" name="institute_code" required >
                        <option value="">Select</option>
                        <?php if(count($exam_institutes)){
                                foreach($exam_institutes as $row){ 	?>
                        <option value="<?php echo $row['institute_code'];?>" <?php if($eligibleRes['institute_code'] == $row['institute_code']){echo "selected='selected'";} ?>><?php echo $row['institute_name'];?></option>
                        <?php } } ?>
                      </select>
                      <span class="error"><?php echo form_error('training_institute');?></span>
                    </div>
                	<label for="training_from_date" class="col-sm-2 control-label">	Training From *</label>
                     <div class="col-sm-3">
                        <input type="text" class="form-control" id="training_from" name="training_from" placeholder="Training From" required value="<?php echo $eligibleRes['training_from'];?>" >
                         <span class="error"><?php echo form_error('training_from');?></span>
                    </div>
                </div>
                <div class="form-group">
                	<label for="training_to_date" class="col-sm-2 control-label"> Training To *</label>
                     <div class="col-sm-3">
                        <input type="text" class="form-control" id="training_to" name="training_to" placeholder="Training To" required value="<?php echo $eligibleRes['training_to'];?>" >
                         <span class="error"><?php echo form_error('training_to');?></span>
                    </div>
                	<label for="remark" class="col-sm-2 control-label">REMARK</label>
                     <div class="col-sm-3">
                        <input type="text" class="form-control" id="remark" name="remark" placeholder="REMARK"  value="<?php echo $eligibleRes['remark'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                         <span class="error"><?php echo form_error('remark');?></span>
                    </div>
                </div>

             </div>
             
              <div class="box-footer">
                  <div class="col-sm-2 col-xs-offset-5">
                  <?php $last = $this->uri->total_segments();
						$id = $this->uri->segment($last);
				?>
		
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="<?php if(is_numeric($id)){ echo 'Update';}else{ echo 'Add';} ?>">
                     <a href="<?php echo base_url();?>iibfdra/Version_2/admin/EligibleMaster" class="btn btn-default pull-right">Back</a>
                    </div>
              </div>
           </div>
        </div>
      </div>
    </section>
    </form>
  </div>
  
<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css" />
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script src="<?php echo base_url()?>js/validation.js"></script>
<script type="text/javascript">
	$(document).ready(function() 
	{
		$('#training_from').datepicker({
			autoclose: true,
			format: 'yyyy-mm-dd'
	   	});
	   	$('#training_to').datepicker({
			autoclose: true,
			format: 'yyyy-mm-dd'
	   	});
	   	$('#usersAddForm').parsley('validate');
	});
	$('.input-selector').on('keypress', function(e){
	  return e.metaKey || // cmd/ctrl
		e.which <= 0 || // arrow keys
		e.which == 8 || // delete key
		/[0-9]/.test(String.fromCharCode(e.which)); // numbers
	});
</script>
<?php $this->load->view('iibfdra/Version_2/admin/includes/footer');?>