<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>Pages</h1>
		<?php echo $breadcrumb; ?>
    </section>
	<form class="form-horizontal" name="roleAddForm" id="roleAddForm" action="<?php echo base_url();?>admin/MainController/addRole" method="post">
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Page List</h3>
              <div class="pull-right">
                <a href="<?php echo base_url();?>admin/Pages/add" class="btn btn-warning">Add</a>
                <input type="button" class="btn btn-info" onclick="refreshDiv('');" value="Refresh">
                <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
                <input type="hidden" name="base_url_val" id="base_url_val" value="" /> 
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
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
                <table id="listitems" class="table table-bordered table-striped dataTables-example">
                    <thead>
                        <tr>
                            <th id="srNo">S.No.</th>
                            <th id="title">Title</th>
                            <th id="url_word">URL Title</th>
                            <th id="status">Status</th>
                            <th id="action">Operations</th>
                        </tr>
                    </thead>
                    <tbody class="no-bd-y" id="list">
                                    
                    </tbody>
                </table>
              
              <!-- <div id="links" class="" style="float:right;"><?php //echo $links; ?></div>-->
               <div id="links" class="dataTables_paginate paging_simple_numbers">
               
               </div>
               
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
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
<script type="text/javascript">
  $('#roleAddForm').parsley('validate');
</script>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script>
$(function () {
	$("#listitems").DataTable();
	var base_url = '<?php echo base_url(); ?>';
	paginate(base_url+'admin/Pages/getList','','','');
	$("#base_url_val").val(base_url+'admin/Pages/getList');
	//alert( $("#base_url_val").val() );
});
		
</script>
<script>
  
</script>
<?php $this->load->view('admin/includes/footer');?>