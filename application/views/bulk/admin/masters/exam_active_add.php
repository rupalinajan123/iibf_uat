<?php $this->load->view('bulk/admin/includes/header');?>
<?php $this->load->view('bulk/admin/includes/sidebar');?>
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
              <h3 class="box-title">Add Exam</h3>
              <div class="pull-right">
              	<a href="<?php echo base_url();?>bulk/admin/ExamActiveMaster/" class="btn btn-warning">Back to Active Exam List</a>
              </div>
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
             <label for="exam_code" class="col-sm-2 control-label">Bank Name&nbsp;<span style="color:#F00">*</span></label>
                	<div class="col-sm-3">
                      <select class="form-control" id="institute_code" name="institute_code" required >
                        <option value="">- Select Bank Name - </option>
                        <?php if(count($institute_list)){
                                foreach($institute_list as $row){ 	?>
                        <option value="<?php echo $row['institute_code'];?>" <?php if($examActiveRes['institute_code'] == $row['institute_code']){echo "selected='selected'";} ?>><?php echo $row['institute_name'];?></option>
                        <?php } } ?>
                      </select>
                      <span class="error"><?php echo form_error('institute_code');?></span>
                    </div>
             
             </div>
                <div class="form-group">
                <input type="hidden" class="form-control" id="id" name="id"  value="<?php echo set_value('id');?>" >
               
                <label for="exam_code" class="col-sm-2 control-label">Exam Name&nbsp;<span style="color:#F00">*</span></label>
                	<div class="col-sm-3">
                      <select class="form-control" id="exam_code" name="exam_code" required onchange="getExamPeriod(this.value)">
                        <option value="">- Select Exam Name - </option>
                        <?php if(count($exam_list)){
                                foreach($exam_list as $row){ 	?>
                        <option value="<?php echo $row['exam_code'];?>" <?php if($examActiveRes['exam_code'] == $row['exam_code']){echo "selected='selected'";} ?>><?php echo $row['description'];?></option>
                        <?php } } ?>
                      </select>
                      <span class="error"><?php echo form_error('exam_code');?></span>
                    </div>
                    
                    <label for="exam_period" class="col-sm-2 control-label">Exam Period&nbsp;<span style="color:#F00">*</span></label>
                     <div class="col-sm-3" id="examPeriodDiv">
                        <!--<select class="form-control" id="exam_period" name="exam_period" required >
                            <option value="">Select</option>
                            <?php //if(count($exam_period)){
                                    //foreach($exam_period as $row1){ 	?>
                            <option value="<?php //echo $row1['exam_period'];?>" <?php //if($examActiveRes['exam_period'] == $row1['exam_period']){echo "selected='selected'";} ?>><?php //echo $row1['exam_period'];?></option>
                            <?php //} } ?> 
                      </select>-->
                      
                     <select class="form-control" id="exam_period" name="exam_period" required >
                            <option value="">- Select Exam Period -</option>
                            
                            <?php if($examActiveRes['exam_period'] != ''){
								?>
                            <option value="<?php if($examActiveRes['exam_period'] != ''){echo $examActiveRes['exam_period'];}?>" <?php if($examActiveRes['exam_period'] != ''){echo "selected='selected'";} ?>><?php if($examActiveRes['exam_period'] != ''){echo $examActiveRes['exam_period'];}?></option>
                            <?php } ?>
                      </select>
                        <span class="error"><?php echo form_error('exam_period');?></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                	<label for="exam_from_date" class="col-sm-2 control-label">	Activation From Date&nbsp;<span style="color:#F00">*</span></label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="exam_from_date" name="exam_from_date" placeholder="Exam From Date" required value="<?php echo $examActiveRes['exam_from_date'];?>" >
                             <span class="error"><?php echo form_error('exam_from_date');?></span>
                        </div>
                         
                         <label for="exam_to_date" class="col-sm-2 control-label">Activation To Date&nbsp;<span style="color:#F00">*</span></label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="exam_to_date" name="exam_to_date" placeholder="Exam To Date" required value="<?php echo $examActiveRes['exam_to_date'];?>" >
                             <span class="error"><?php echo form_error('exam_to_date');?></span>
                        </div>
                         
                    <!--<div class="col-sm-6"> 
                       <label for="group_code" class="col-sm-4 control-label">Exam From Time *</label>
                            <div class="input-group col-sm-6 bootstrap-timepicker">
                            <input type="text" class="form-control timepicker" id="exam_from_time" name="exam_from_time" required placeholder="Exam From Time" value="<?php //echo $examActiveRes['exam_from_time'];?>" >
                            <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                        <span class="error"><?php //echo form_error('exam_from_time');?></span>
                    </div>-->  
                </div>
                
                <!--<div class="form-group">
                	<label for="exam_to_date" class="col-sm-2 control-label">Exam To Date *</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="exam_to_date" name="exam_to_date" placeholder="Exam To Date" required value="<?php //echo $examActiveRes['exam_to_date'];?>" >
                             <span class="error"><?php //echo form_error('exam_to_date');?></span>
                        </div>
                    
                        <div class="col-sm-6"> 
                           <label for="group_code" class="col-sm-4 control-label">Exam To Time *</label>
                                <div class="input-group col-sm-6 bootstrap-timepicker">
                                <input type="text" class="form-control timepicker" id="exam_to_time" name="exam_to_time" required placeholder="Exam To Time" value="<?php //echo $examActiveRes['exam_to_time'];?>" >
                                    <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            <span class="error"><?php //echo form_error('exam_to_time');?></span>
                        </div>    
                        
                </div>-->
                
                
                <div class="form-group">
                	<!--<label for="center_code" class="col-sm-2 control-label">TDS(%)&nbsp;<span style="color:#F00">*</span></label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="tds" name="tds" placeholder="TDS" required value="<?php echo $examActiveRes['tds'];?>">
                             <span class="error"><?php echo form_error('tds');?></span>
                        </div>-->
                    
                    <label for="center_name" class="col-sm-2 control-label">Discount(%)&nbsp;<span style="color:#F00">*</span></label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="discount" name="discount" required placeholder="Discount" value="<?php echo $examActiveRes['discount'];?>">
                             <span class="error"><?php echo form_error('discount');?></span>
                        </div>
                </div>
                
             </div>
             
              <div class="box-footer">
                  <div class="col-sm-2 col-xs-offset-5">
                  <?php $last = $this->uri->total_segments();
						$id = $this->uri->segment($last);
				?>
		
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="<?php if(is_numeric($id)){ echo 'Update';}else{ echo 'Add';} ?>">
                   <a href="<?php echo base_url();?>bulk/admin/ExamActiveMaster" class="btn btn-default pull-right">Back</a>
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
	$('#exam_from_date').datepicker({
		autoclose: true,
		format: 'yyyy-mm-dd'
	});
   
   $('#exam_to_date').datepicker({
		autoclose: true,
		format: 'yyyy-mm-dd'
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

function getExamPeriod(exam_code){
	var base_url = '<?php echo base_url(); ?>';
	$.ajax({
		type:"POST",
		url: base_url+'bulk/admin/ExamActiveMaster/getExamPeriod',
		data:{ exam_code : exam_code },
		success:function(data){
			$("#examPeriodDiv").show();
			$("#examPeriodDiv").text('');
			$("#examPeriodDiv").append(data);
		}
	},"json");
}

$('#addForm').parsley('validate');	

</script>
<?php $this->load->view('bulk/admin/includes/footer');?>