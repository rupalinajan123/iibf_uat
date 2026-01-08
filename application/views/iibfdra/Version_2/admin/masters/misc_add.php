<?php $this->load->view('iibfdra/Version_2/admin/includes/header');?>
<?php $this->load->view('iibfdra/Version_2/admin/includes/sidebar');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Misc Master
      </h1>
     <?php echo $breadcrumb; ?>
    </section>
	<form class="form-horizontal" name="miscAddForm" id="miscAddForm" action="" method="post">
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
               
                <label for="roleid" class="col-sm-2 control-label">Exam Name <span class="red"> *</span></label>
                	<div class="col-sm-3">
                      <?php 
						$last = $this->uri->total_segments();
						$id = $this->uri->segment($last);
						
						 if(is_numeric($id)){
							if(count($exam_list)){
							   foreach($exam_list as $row){ 	
									if($miscRes['exam_code'] == $row['exam_code']){echo $row['description'];} 
							} } 
						}else{  ?>
                      <select class="form-control" id="exam_code" name="exam_code" required >
                        <option value="">Select</option>
                        <?php if(count($exam_list)){
                                foreach($exam_list as $row){ 	?>
                        <option value="<?php echo $row['exam_code'];?>" <?php if($miscRes['exam_code'] == $row['exam_code']){echo "selected='selected'";} ?>><?php echo $row['description'];?></option>
                        <?php } } ?>
                      </select>
                      <span class="error"><?php echo form_error('exam_code');?></span>
                      <?php } ?>
                    </div>
                    
                    <label for="exam_period" class="col-sm-2 control-label">Exam Period <span class="red"> *</span></label>
                     <div class="col-sm-3">
                        <input type="text" class="form-control" id="exam_period" name="exam_period" placeholder="Exam Period" required value="<?php echo $miscRes['exam_period'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                         <span class="error"><?php echo form_error('exam_period');?></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                	<label for="exam_month" class="col-sm-2 control-label">Exam Month <span class="red"> *</span></label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="exam_month" name="exam_month" placeholder="Exam Month" required value="<?php echo $miscRes['exam_month'];?>" data-parsley-type="number" onkeypress="return number(event);">
                             <span class="error"><?php echo form_error('exam_month');?></span>
                        </div>
                    
                    <label for="trg_value" class="col-sm-2 control-label">TRG Value</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="trg_value" name="trg_value" placeholder="TRG Value" value="<?php echo $miscRes['trg_value'];?>" data-parsley-pattern="/^[a-zA-Z0-9]+$/" onkeypress="return number(event);">
                             <span class="error"><?php echo form_error('trg_value');?></span>
                        </div>
                </div>
             
             </div>
             
              <div class="box-footer">
                  <div class="col-sm-2 col-xs-offset-5">
                  <?php $last = $this->uri->total_segments();
						$id = $this->uri->segment($last);
				?>
		
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="<?php if(is_numeric($id)){ echo 'Update';}else{ echo 'Add';} ?>">
                     <a href="<?php echo base_url();?>iibfdra/Version_2/admin/MiscMaster" class="btn btn-default pull-right">Back</a>
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
  $('#miscAddForm').parsley('validate');
</script>
<?php $this->load->view('iibfdra/Version_2/admin/includes/footer');?>