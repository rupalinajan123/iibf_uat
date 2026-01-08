<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Exam Activation Master 
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
                      <select class="form-control" id="exam_code" name="exam_code" required >
                        <option value="">Select</option>
                        <?php if(count($exam_list)){
                                foreach($exam_list as $row){ 	?>
                        <option value="<?php echo $row['exam_code'];?>" <?php if($examActiveRes['exam_code'] == $row['exam_code']){echo "selected='selected'";} ?>><?php echo $row['description'];?></option>
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
                        <option value="<?php echo $row1['exam_period'];?>" <?php if($examActiveRes['exam_period'] == $row1['exam_period']){echo "selected='selected'";} ?>><?php echo $row1['exam_period'];?></option>
                        <?php } } ?> 
                      </select>
                        <span class="error"><?php echo form_error('exam_period');?></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                	<label for="exam_from_date" class="col-sm-2 control-label">	Exam From Date *</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="exam_from_date" name="exam_from_date" placeholder="Exam From Date" required value="<?php echo $examActiveRes['exam_from_date'];?>" readonly>
                             <span class="error"><?php echo form_error('exam_from_date');?></span>
                        </div>
                    
                   <!-- <label for="exam_from_time" class="col-sm-2 control-label">Exam From Time *</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control timepicker" id="exam_from_time" name="exam_from_time" required placeholder="Exam From Time" value="<?php //echo $examActiveRes['exam_from_time'];?>" >
                             <span class="error"><?php //echo form_error('exam_from_time');?></span>
                        </div>-->
                        
                    <div class="col-sm-6"> 
                       <label for="group_code" class="col-sm-4 control-label">Exam From Time *</label>
                            <div class="input-group col-sm-6 bootstrap-timepicker">
                            <input type="text" class="form-control timepicker" id="exam_from_time" name="exam_from_time" required placeholder="Exam From Time" value="<?php echo $examActiveRes['exam_from_time'];?>" readonly>
                            <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                        <span class="error"><?php echo form_error('exam_from_time');?></span>
                    </div>  
                </div>
                
                <div class="form-group">
                	<label for="exam_to_date" class="col-sm-2 control-label">Exam To Date *</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="exam_to_date" name="exam_to_date" placeholder="Exam To Date" required value="<?php echo $examActiveRes['exam_to_date'];?>" readonly>
                             <span class="error"><?php echo form_error('exam_to_date');?></span>
                        </div>
                    
                   <!-- <label for="exam_to_time" class="col-sm-2 control-label">Exam To Time  *</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control timepicker" id="exam_to_time" name="exam_to_time" required placeholder="Exam To Time" value="<?php //echo $examActiveRes['exam_to_time'];?>" >
                             <span class="error"><?php //echo form_error('exam_to_time');?></span>
                        </div>-->
                        
                        <div class="col-sm-6"> 
                           <label for="group_code" class="col-sm-4 control-label">Exam To Time *</label>
                                <div class="input-group col-sm-6 bootstrap-timepicker">
                                <input type="text" class="form-control timepicker" id="exam_to_time" name="exam_to_time" required placeholder="Exam To Time" value="<?php echo $examActiveRes['exam_to_time'];?>" readonly>
                                    <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            <span class="error"><?php echo form_error('exam_to_time');?></span>
                        </div>    
                        
                </div>
                
             </div>
             
              <div class="box-footer">
                  <div class="col-sm-2 col-xs-offset-5">
                  <?php $last = $this->uri->total_segments(); 
						$id = $this->uri->segment($last);
				?>
		
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="<?php if(is_numeric($id)){ echo 'Update';}else{ echo 'Add';} ?>">
                   <a href="<?php echo base_url();?>admin/ExamActiveMaster" class="btn btn-default pull-right">Back</a>
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
<script type="text/javascript">
$(document).ready(function() 
{
	var startDate = '<?php echo $examActiveRes['exam_from_date'];?>';
	var startY = '<?php echo date('Y',strtotime($examActiveRes['exam_from_date']));?>';
	var startM = '<?php echo date('m',strtotime($examActiveRes['exam_from_date']));?>';
	var startD = '<?php echo date('d',strtotime($examActiveRes['exam_from_date']));?>';
	
	var endDate = '<?php echo $examActiveRes['exam_to_date'];?>';
	var endY = '<?php echo date('Y',strtotime($examActiveRes['exam_to_date']));?>';
	var endM = '<?php echo date('m',strtotime($examActiveRes['exam_to_date']));?>';
	var endD = '<?php echo date('d',strtotime($examActiveRes['exam_to_date']));?>';
	
	var newStart = new Date(Date.UTC(startY,startM,startD));
	var newEnd = new Date(Date.UTC(endY,endM,endD));
	
	if(newStart!='' && newEnd!='')
	{
		//$('#exam_to_date').datepicker({format: 'yyyy-mm-dd'},'setStartDate', new Date(newStart));
		//$('#exam_from_date').datepicker({format: 'yyyy-mm-dd'},'setEndDate', new Date(newEnd));
		/*$('#exam_to_date').datepicker({format: 'yyyy-mm-dd',autoclose: true}, function(){
			$('#exam_from_date').datepicker('setEndDate', new Date(newStart));
		});
		
		$('#exam_from_date').datepicker({format: 'yyyy-mm-dd',autoclose: true}, function(){
			$('#exam_to_date').datepicker('setStartDate', new Date(newEnd));
		});	*/
	}
   
   $('#exam_from_date').datepicker({format: 'yyyy-mm-dd',autoclose: true}).on('changeDate', function(){
		$('#exam_to_date').datepicker('setStartDate', new Date($(this).val()));
	}); 
	
	$('#exam_to_date').datepicker({format: 'yyyy-mm-dd',autoclose: true}).on('changeDate', function(){
		$('#exam_from_date').datepicker('setEndDate', new Date($(this).val()));
	});	
   
   $(".timepicker").timepicker({
		showInputs: false,
		format: 'HH:mm:ss',
		maxHours:24,
		minuteStep: 1,
		showSeconds:true,
		secondStep:1,
		snapToStep: false,
		showMeridian: false
	});
});

	$('#addForm').parsley('validate');	
</script>


</script>
 
<?php $this->load->view('admin/includes/footer');?>