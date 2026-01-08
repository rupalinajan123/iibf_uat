<?php $this->load->view('iibfdra/Version_2/admin/includes/header');?>
<?php $this->load->view('iibfdra/Version_2/admin/includes/sidebar');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Exam Master
      </h1>
      <?php echo $breadcrumb; ?>
    </section>
	<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="examForm" id="examForm">
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Exam Master - Edit</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
            <?php echo validation_errors(); ?>
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
               
                <label for="exam_code" class="col-sm-2 control-label">Exam Code <span class="red"> *</span></label>
                	<div class="col-sm-3">
                      <input type="text" class="form-control" id="exam_code" name="exam_code" placeholder="Exam Code" required value="<?php echo $examRes['exam_code']; ?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                      <span class="error"><?php echo form_error('exam_code');?></span>
                    </div>
                    
                    <label for="description" class="col-sm-2 control-label">Exam Description <span class="red"> *</span></label>
                     <div class="col-sm-3">
                        <input type="text" class="form-control" id="description" name="description" placeholder="Exam Description" required value="<?php echo $examRes['description']; ?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                         <span class="error"><?php echo form_error('description');?></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                	<label for="qualifying_exam1" class="col-sm-2 control-label">Qualifying Exam1</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="qualifying_exam1" name="qualifying_exam1" placeholder="Qualifying Exam1"  value="<?php echo $examRes['qualifying_exam1']; ?>" onkeypress="return number(event)" data-parsley-type="number">
                             <span class="error"><?php echo form_error('qualifying_exam1');?></span>
                        </div>
                    
                    <label for="qualifying_part1" class="col-sm-2 control-label">Qualifying Part1</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="qualifying_part1" name="qualifying_part1" placeholder="Qualifying Part1" value="<?php echo $examRes['qualifying_part1']; ?>" onkeypress="return number(event)" data-parsley-type="number">
                             <span class="error"><?php echo form_error('qualifying_part1');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="qualifying_exam2" class="col-sm-2 control-label">Qualifying Exam2</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="qualifying_exam2" name="qualifying_exam2" placeholder="Qualifying Exam2"  value="<?php echo $examRes['qualifying_exam2']; ?>" onkeypress="return number(event)" data-parsley-type="number">
                             <span class="error"><?php echo form_error('qualifying_exam2');?></span>
                        </div>
                    
                    <label for="qualifying_part2" class="col-sm-2 control-label">Qualifying Part2</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="qualifying_part2" name="qualifying_part2" placeholder="Qualifying Part2" value="<?php echo $examRes['qualifying_part2']; ?>" onkeypress="return number(event)" data-parsley-type="number">
                             <span class="error"><?php echo form_error('qualifying_part2');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="qualifying_exam3" class="col-sm-2 control-label">Qualifying Exam3</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="qualifying_exam3" name="qualifying_exam3" placeholder="Qualifying Exam3"  value="<?php echo $examRes['qualifying_exam3']; ?>" onkeypress="return number(event)" data-parsley-type="number">
                             <span class="error"><?php echo form_error('qualifying_exam3');?></span>
                        </div>
                    
                    <label for="qualifying_part3" class="col-sm-2 control-label">Qualifying Part3</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="qualifying_part3" name="qualifying_part3" placeholder="Qualifying Part3" value="<?php echo $examRes['qualifying_part3']; ?>" onkeypress="return number(event)" data-parsley-type="number">
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
                                <option value="<?php echo $row['id'];?>" <?php if($row['id'] == $examRes['exam_type']){ echo "selected='selected'"; } ?>><?php echo $row['type'];?></option>
                                <?php } } ?>
                             </select>
                             <span class="error"><?php echo form_error('exam_type');?></span>
                        </div>
                    
                    <label for="examprty" class="col-sm-2 control-label">Exam Priority</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="examprty" name="examprty" placeholder="Exam Priority" value="<?php echo $examRes['examprty']; ?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('examprty');?></span>
                        </div>
                </div>

                <div class="form-group">
                	<label for="examdescshr" class="col-sm-2 control-label">Exam Description SHR</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="examdescshr" name="examdescshr" placeholder="Exam Description SHR" value="<?php echo $examRes['examdescshr']; ?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('examdescshr');?></span>
                        </div>
                    
                    <label for="elg_mem_o" class="col-sm-2 control-label">ELG_MEM_O</label>
                        <div class="col-sm-3">
                            <label class="radio-inline">
                            	<input type="radio" name="elg_mem_o" value="Y" <?php if($examRes['elg_mem_o']=="Y"){ echo "checked ='checked'";} ?>> Yes
                            </label>
                            <label class="radio-inline">
                            	<input type="radio" name="elg_mem_o" value="N" <?php if($examRes['elg_mem_o']=="N"){ echo "checked='checked'";} ?>> No
                            </label>
                             <span class="error"><?php echo form_error('elg_mem_o');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="elg_mem_a" class="col-sm-2 control-label">ELG_MEM_A</label>
                         <div class="col-sm-3">
                            <label class="radio-inline">
                            	<input type="radio" name="elg_mem_a" value="Y" <?php if($examRes['elg_mem_a']=="Y"){ echo "checked ='checked'";} ?>> Yes
                            </label>
                            <label class="radio-inline">
                            	<input type="radio" name="elg_mem_a" value="N" <?php if($examRes['elg_mem_a']=="N"){ echo "checked ='checked'";} ?>> No
                            </label>
                             <span class="error"><?php echo form_error('elg_mem_a');?></span>
                        </div>
                    
                    <label for="elg_mem_f" class="col-sm-2 control-label">ELG_MEM_F</label>
                        <div class="col-sm-3">
                            <label class="radio-inline">
                            	<input type="radio" name="elg_mem_f" value="Y" <?php if($examRes['elg_mem_f']=="Y"){ echo "checked ='checked'";} ?>> Yes
                            </label>
                            <label class="radio-inline">
                            	<input type="radio" name="elg_mem_f" value="N" <?php if($examRes['elg_mem_f']=="N"){ echo "checked ='checked'";} ?>> No
                            </label>
                             <span class="error"><?php echo form_error('elg_mem_f');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="elg_mem_nm" class="col-sm-2 control-label">ELG_MEM_NM</label>
                         <div class="col-sm-3">
                            <label class="radio-inline">
                            	<input type="radio" name="elg_mem_nm" value="Y" <?php if($examRes['elg_mem_nm']=="Y"){ echo "checked ='checked'";} ?>> Yes
                            </label>
                            <label class="radio-inline">
                            	<input type="radio" name="elg_mem_nm" value="N" <?php if($examRes['elg_mem_nm']=="N"){ echo "checked ='checked'";} ?>> No
                            </label>
                             <span class="error"><?php echo form_error('elg_mem_nm');?></span>
                        </div>
                    
                    <label for="elg_mem_db" class="col-sm-2 control-label">ELG_MEM_DB</label>
                        <div class="col-sm-3">
                            <label class="radio-inline">
                            	<input type="radio" name="elg_mem_db" value="Y" <?php if($examRes['elg_mem_db']=="Y"){ echo "checked ='checked'";} ?>> Yes
                            </label>
                            <label class="radio-inline">
                            	<input type="radio" name="elg_mem_db" value="N" <?php if($examRes['elg_mem_db']=="N"){ echo "checked ='checked'";} ?>> No
                            </label>
                             <span class="error"><?php echo form_error('elg_mem_db');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="exam_instruction_file" class="col-sm-2 control-label">Exam Instruction File</label>
                         <div class="col-sm-6">
                            <input type="file" id="exam_instruction_file" name="exam_instruction_file">
                  			 <p class="help-block">(Please Upload PDF Files of size less then 2MB)</p>
                             <span class="error"><?php echo form_error('exam_instruction_file');?></span>
                        </div>
                </div>
                <?php if(!empty($examRes['exam_instruction_file'])){?>
                <div class="form-group">
                	<label for="exam_instruction_file" class="col-sm-2 control-label">Existing Instruction File</label>
                         <div class="col-sm-6">
                            <input type="hidden" id="exam_instruction_file_hidden" name="exam_instruction_file_hidden" value="<?php echo $examRes['exam_instruction_file']; ?>">
                            	<a href="<?php echo base_url(); ?>uploads/iibfdra/exam_instruction/<?php echo $examRes['exam_instruction_file']; ?>" target="_blank"><?php echo $examRes['exam_instruction_file']; ?></a>
                        </div>
                </div>
                <?php } ?>
                <div class="form-group">
                	<label for="member_instruction" class="col-sm-2 control-label">Member Instruction</label>
                         <div class="col-sm-10">
                             <textarea id="member_instruction" name="member_instruction" rows="10" cols="80">
                             <?php echo $examRes['member_instruction']; ?>
                    		 </textarea>
                             <span class="error"><?php echo form_error('member_instruction');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                	<label for="nonmember_instruction" class="col-sm-2 control-label">NonMember Instruction</label>
                         <div class="col-sm-10">
                             <textarea id="nonmember_instruction" name="nonmember_instruction" rows="10" cols="80">
							 	<?php echo $examRes['nonmember_instruction']; ?>
                    		 </textarea>
                             <span class="error"><?php echo form_error('nonmember_instruction');?></span>
                        </div>
                </div>
               
             </div>
             
              <div class="box-footer">
                  <div class="col-sm-2 col-xs-offset-5">
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit">
                    <a href="<?php echo base_url();?>iibfdra/Version_2/admin/ExamMaster" class="btn btn-default pull-right">Back</a>
                    </div>
              </div>
           </div>
        </div>
      </div>

    </section>
    </form>
  </div>
  
<!-- Data Tables -->

<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
  
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script src="<?php echo base_url()?>js/validation.js"></script>
<script type="text/javascript">
  $('#examForm').parsley('validate');
</script>
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<script>
	$(function () {
		// Replace the <textarea id="editor1"> with a CKEditor
		// instance, using default configuration.
		CKEDITOR.replace('member_instruction');
		CKEDITOR.replace('nonmember_instruction');
	});
	
</script> 
 
<?php $this->load->view('admin/includes/footer');?>