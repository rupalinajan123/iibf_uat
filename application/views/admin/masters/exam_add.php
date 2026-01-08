<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Exam Master
      </h1>
      <?php echo $breadcrumb; ?>
    </section>
	<?php //echo form_open_multipart('admin/ExamMaster/import');?>
    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="examForm" id="examForm">
    <!-- Main content -->
    <section class="content">
      <div class="row">
      
      
      
      
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Exam Master - Add</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
            <?php echo validation_errors(); ?>
              <?php if(isset($error) && $error){?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo $error; ?>
                </div>
              <?php } if(isset($success) && $success!=''){ ?>
                <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                <?php echo $success; ?>
              </div>
             <?php } ?> 

                <div class="form-group">
                <input type="hidden" class="form-control" id="id" name="id"  value="<?php echo set_value('id');?>" >
               
                <label for="roleid" class="col-sm-2 control-label">Exam Code <span class="red"> *</span></label>
                	<div class="col-sm-3">
                      <input type="text" class="form-control" id="exam_code" name="exam_code" placeholder="Exam Code" required value="" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                      <span class="error"><?php echo form_error('exam_code');?></span>
                    </div>
                    
                    <label for="exam_period" class="col-sm-2 control-label">Exam Description <span class="red"> *</span></label>
                     <div class="col-sm-3">
                        <input type="text" class="form-control" id="description" name="description" placeholder="Exam Description" required value="" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9/.()- ]+$/">
                         <span class="error"><?php echo form_error('description');?></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                	<label for="exam_month" class="col-sm-2 control-label">Qualifying Exam1</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="qualifying_exam1" name="qualifying_exam1" placeholder="Qualifying Exam1"  value="" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('qualifying_exam1');?></span>
                        </div>
                    
                    <label for="trg_value" class="col-sm-2 control-label">Qualifying Part1</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="qualifying_part1" name="qualifying_part1" placeholder="Qualifying Part1" value="" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('qualifying_part1');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="exam_month" class="col-sm-2 control-label">Qualifying Exam2</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="qualifying_exam2" name="qualifying_exam2" placeholder="Qualifying Exam2"  value="" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('qualifying_exam2');?></span>
                        </div>
                    
                    <label for="trg_value" class="col-sm-2 control-label">Qualifying Part2</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="qualifying_part2" name="qualifying_part2" placeholder="Qualifying Part2" value="" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('qualifying_part2');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="exam_month" class="col-sm-2 control-label">Qualifying Exam3</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="qualifying_exam3" name="qualifying_exam3" placeholder="Qualifying Exam3"  value="" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('qualifying_exam3');?></span>
                        </div>
                    
                    <label for="trg_value" class="col-sm-2 control-label">Qualifying Part3</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="qualifying_part3" name="qualifying_part3" placeholder="Qualifying Part3" value="" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('qualifying_part3');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="exam_type" class="col-sm-2 control-label">Exam Type <span class="red"> *</span></label>
                         <div class="col-sm-3">
                             <select class="form-control" id="exam_type" name="exam_type" required >
                                <option value="">Select</option>
                                <?php if(count($exam_type_list)){
                                        foreach($exam_type_list as $row){ 	?>
                                <option value="<?php echo $row['id'];?>"><?php echo $row['type'];?></option>
                                <?php } } ?>
                             </select>
                             <span class="error"><?php echo form_error('exam_type');?></span>
                        </div>
                    
                    <label for="trg_value" class="col-sm-2 control-label">Exam Priority</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="examprty" name="examprty" placeholder="Exam Priority" value="">
                             <span class="error"><?php echo form_error('examprty');?></span>
                        </div>
                </div>

                <div class="form-group">
                	<label for="exam_month" class="col-sm-2 control-label">Exam Description SHR</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="examdescshr" name="examdescshr" placeholder="Exam Description SHR" value="" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('examdescshr');?></span>
                        </div>
                    
                    <label for="trg_value" class="col-sm-2 control-label">ELG_MEM_O</label>
                        <div class="col-sm-3">
                            <label class="radio-inline">
                            	<input type="radio" name="elg_mem_o" value="Y"> Yes
                            </label>
                            <label class="radio-inline">
                            	<input type="radio" name="elg_mem_o" value="N"> No
                            </label>
                             <span class="error"><?php echo form_error('elg_mem_o');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="exam_month" class="col-sm-2 control-label">ELG_MEM_A</label>
                         <div class="col-sm-3">
                            <label class="radio-inline">
                            	<input type="radio" name="elg_mem_a" value="Y"> Yes
                            </label>
                            <label class="radio-inline">
                            	<input type="radio" name="elg_mem_a" value="N"> No
                            </label>
                             <span class="error"><?php echo form_error('elg_mem_a');?></span>
                        </div>
                    
                    <label for="trg_value" class="col-sm-2 control-label">ELG_MEM_F</label>
                        <div class="col-sm-3">
                            <label class="radio-inline">
                            	<input type="radio" name="elg_mem_f" value="Y"> Yes
                            </label>
                            <label class="radio-inline">
                            	<input type="radio" name="elg_mem_f" value="N"> No
                            </label>
                             <span class="error"><?php echo form_error('elg_mem_f');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="exam_month" class="col-sm-2 control-label">ELG_MEM_NM</label>
                         <div class="col-sm-3">
                            <label class="radio-inline">
                            	<input type="radio" name="elg_mem_nm" value="Y"> Yes
                            </label>
                            <label class="radio-inline">
                            	<input type="radio" name="elg_mem_nm" value="N"> No
                            </label>
                             <span class="error"><?php echo form_error('elg_mem_nm');?></span>
                        </div>
                    
                    <label for="trg_value" class="col-sm-2 control-label">ELG_MEM_DB</label>
                        <div class="col-sm-3">
                            <label class="radio-inline">
                            	<input type="radio" name="elg_mem_db" value="Y"> Yes
                            </label>
                            <label class="radio-inline">
                            	<input type="radio" name="elg_mem_db" value="N"> No
                            </label>
                             <span class="error"><?php echo form_error('elg_mem_db');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="exam_month" class="col-sm-2 control-label">Exam Instruction File</label>
                         <div class="col-sm-6">
                            <input type="file" id="exam_instruction_file" name="exam_instruction_file">
                  			 <p class="help-block">(Please Upload PDF Files of size less then 2MB)</p>
                             <span class="error"><?php echo form_error('exam_instruction_file');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="exam_month" class="col-sm-2 control-label">Member Instruction</label>
                         <div class="col-sm-10">
                             <textarea id="member_instruction" name="member_instruction" rows="10" cols="80">
                    		 </textarea>
                             <span class="error"><?php echo form_error('member_instruction');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="exam_month" class="col-sm-2 control-label">NonMember Instruction</label>
                         <div class="col-sm-10">
                             <textarea id="nonmember_instruction" name="nonmember_instruction" rows="10" cols="80">
                    		 </textarea>
                             <span class="error"><?php echo form_error('nonmember_instruction');?></span>
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
                    <a href="<?php echo base_url();?>admin/ExamMaster" class="btn btn-default pull-right">Back</a>
                    </div>
              </div>
           </div>
        </div>
      </div>

    </section>
    </form>
  </div>
  
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script src="<?php echo base_url()?>js/validation.js"></script>
<script type="text/javascript">
$('#examForm').parsley('validate');
  

	
</script> 
 
<?php $this->load->view('admin/includes/footer');?>