<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $menu_title; ?>
        
      </h1>
    	<?php echo $breadcrumb; ?>
    </section>
	<?php //echo form_open_multipart('admin/ExamMaster/import');?>
    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data" name="importFile" id="importFile">
    <!-- Main content -->
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $title; ?></h3>
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
                	<label for="password" class="col-sm-6 control-label">Upload Text File</label>
                     <div class="col-sm-6">
                        <input type="file" name="masterfile" required id="masterfile"> 
                         <span class="error"><?php echo form_error('masterfile');?></span>
                         <small> (Please upload text file with 2MB Size)</small> 
                    </div>
                </div>
                <div class="form-group" align="center">
                	<a href="<?php echo base_url();?>uploads/admin/masters_sample_files/<?php echo $sample_file; ?>" target="_blank">Click here to check the sample file format</a>
                </div>
               
             </div>
             
              <div class="box-footer">
                  <div class="col-sm-2 col-xs-offset-5">
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit">
                    <a href="<?php echo base_url();?>admin/<?php echo $this->router->fetch_class();?>" class="btn btn-default pull-right">Back</a>
                    </div>
              </div>
           </div>
        </div>
      </div>

    </section>
    </form>
  </div>
  
<!-- Data Tables -->

<!--<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>-->
  
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script type="text/javascript">
  $('#importFile').parsley('validate');
</script>
<script>
	$(document).ready(function() 
	{
		//var dtable = $('.dataTables-example').DataTable();
	   
	   //$(".DTTT_button_print")).hide();
	});
	
</script> 
 
<?php $this->load->view('admin/includes/footer');?>