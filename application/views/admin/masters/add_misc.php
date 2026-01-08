<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Misc Master
        
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
	<form class="form-horizontal" name="usersAddForm" id="usersAddForm" action="<?php echo base_url();?>admin/MainController/addUser" method="post">
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
               
                <label for="roleid" class="col-sm-2 control-label">Exam Code</label>
                	<div class="col-sm-3">
                      <select class="form-control" id="exam_code" name="exam_code" required >
                        <option value="">Select</option>
                        <?php if(count($exam_list)){
                                foreach($exam_list as $row){ 	?>
                        <option value="<?php echo $row['exam_code'];?>" <?php echo  set_select('exam_code', $row['exam_code']); ?>><?php echo $row['description'];?></option>
                        <?php } } ?>
                      </select>
                      <span class="error"><?php echo form_error('exam_code');?></span>
                    </div>
                    
                    <label for="exam_period" class="col-sm-2 control-label">Exam Period</label>
                     <div class="col-sm-3">
                        <input type="text" class="form-control" id="exam_period" name="exam_period" placeholder="Exam Period" required value="<?php echo set_value('exam_period');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="5">
                         <span class="error"><?php echo form_error('exam_period');?></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                	<label for="exam_month" class="col-sm-2 control-label">Exam Month</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="exam_month" name="exam_month" placeholder="Exam Month" required value="<?php echo set_value('exam_month');?>" data-parsley-maxlength="5">
                             <span class="error"><?php echo form_error('exam_month');?></span>
                        </div>
                    
                    <label for="trg_value" class="col-sm-2 control-label">TRG Value</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="trg_value" name="trg_value" placeholder="TRG Value" required value="<?php echo set_value('trg_value');?>" data-parsley-pattern="/^\S*$/" data-parsley-maxlength="5">
                             <span class="error"><?php echo form_error('trg_value');?></span>
                        </div>
                </div>
             
             </div>
             
              <div class="box-footer">
                  <div class="col-sm-2 col-xs-offset-5">
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="<?php if(isset($_POST['btnSubmit']) && $_POST['btnSubmit']!=''){echo $_POST['btnSubmit'];}else{echo "Add";}?>">
                     <button type="reset" class="btn btn-default pull-right"  name="btnReset" id="btnReset">Reset</button>
                    </div>
              </div>
           </div>
        </div>
      </div>
    </section>
    </form>
  </div>
  
<!-- Data Tables -->



<!-- Data Tables -->

  
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script type="text/javascript">
$('#usersAddForm').parsley('validate');
</script>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script>
$(function () {
	$("#listitems").DataTable();
	var base_url = '<?php echo base_url(); ?>';
	var listing_url = base_url+'admin/MiscMaster/getList';
	
	// Pagination function call
	paginate(listing_url,'','','');
	$("#base_url_val").val(listing_url);
});

	
</script>

<script>
	$(document).ready(function() 
	{
		//var dtable = $('.dataTables-example').DataTable();
	   
	   //$(".DTTT_button_print")).hide();
	});
	
	function editUser(id,roleid,Name,Username,Email){
		$('#id').val(id);
		$('#roleid').val(roleid);
		$('#name').val(Name);
		$('#username').val(Username);
		$('#emailid').val(Email);
		$('#btnSubmit').val('Update');
		$('#roleid').focus();
		$('#password').removeAttr('required');
		$('#confirmPassword').removeAttr('required');
		
	}
	
</script> 
</script>
 
<?php $this->load->view('admin/includes/footer');?>