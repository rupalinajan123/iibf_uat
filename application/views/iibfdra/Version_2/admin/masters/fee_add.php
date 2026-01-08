<?php $this->load->view('iibfdra/Version_2/admin/includes/header');?>
<?php $this->load->view('iibfdra/Version_2/admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Exam Fee Master
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
                        <option value="<?php echo $row['exam_code'];?>" <?php if($feeRes['exam_code'] == $row['exam_code']){echo "selected='selected'";} ?>><?php echo $row['description'];?></option>
                        <?php } } ?>
                      </select>
                      <span class="error"><?php echo form_error('exam_code');?></span> 
                    </div>
                    
                    <label for="exam_period" class="col-sm-2 control-label">Exam Period *</label>
                     <div class="col-sm-3">
                        <select class="form-control" id="exam_period" name="exam_period" required >
                            <option value="">Select</option>
                            <?php if(count($exam_period)){
                                    foreach($exam_period as $row1){ 	?>
                            <option value="<?php echo $row1['exam_period'];?>" <?php if($feeRes['exam_period'] == $row1['exam_period']){echo "selected='selected'";} ?>><?php echo $row1['exam_period'];?></option>
                            <?php } } ?>
                      	</select>
                             <span class="error"><?php echo form_error('exam_period');?></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                	<label for="part_no" class="col-sm-2 control-label">Part No. *</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control input-selector" id="part_no" name="part_no" placeholder="Part Number" required value="<?php echo $feeRes['part_no'];?>" onkeypress="return number(event)" data-parsley-type="number">
                             <span class="error"><?php echo form_error('part_no');?></span>
                        </div>
                    
                    <label for="syllabus_code" class="col-sm-2 control-label">Syllabus Code *</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="syllabus_code" name="syllabus_code" required placeholder="Syllabus Code" value="<?php echo $feeRes['syllabus_code'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('syllabus_code');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="member_category" class="col-sm-2 control-label">Member Category *</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="member_category" name="member_category" placeholder="Member Category" required value="<?php echo $feeRes['member_category'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('member_category');?></span>
                        </div>
                    
                   <label for="group_code" class="col-sm-2 control-label">Group Code *</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="group_code" name="group_code" placeholder="Group Code" required value="<?php echo $feeRes['group_code'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('group_code');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	 <label for="fee_amount" class="col-sm-2 control-label">Fee Amount  *</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-selector" id="fee_amount" name="fee_amount" placeholder="Fee Amount" required 
                            value="<?php echo $feeRes['fee_amount']; ?>" onkeypress="return number(event)" data-parsley-type="number">
                             <span class="error"><?php echo form_error('fee_amount');?></span>
                        </div>
                </div>
             </div>
             
              <div class="box-footer">
                  <div class="col-sm-2 col-xs-offset-5">
                  <?php $last = $this->uri->total_segments();
						$id = $this->uri->segment($last);
				?>
		
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="<?php if(is_numeric($id)){ echo 'Update';}else{ echo 'Add';} ?>">
                    <a href="<?php echo base_url();?>iibfdra/Version_2/admin/FeeMaster" class="btn btn-default pull-right">Back</a>
                     
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