<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>State Master</h1>
        <?php echo $breadcrumb; ?>
    </section>
	<!-- Main content -->
    <section class="content">
		<div class="row">
        	<div class="col-xs-12">
        		<div class="box">
            		<div class="box-header">
              			<h3 class="box-title">State List</h3>
              			<div class="pull-right">
                            <a href="<?php echo base_url();?>admin/StateMaster/add" class="btn btn-warning">Add</a>
                            <a href="<?php echo base_url();?>admin/StateMaster/import" class="btn  btn-primary">Import</a>
                            <a href="<?php echo base_url();?>admin/StateMaster/download" class="btn btn-primary">Download</a>
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
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php } if($this->session->flashdata('success')!=''){ ?>
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php } ?> 
                        <table id="listitems" class="table table-bordered table-striped dataTables-example">
                            <thead>
                                <tr>
                                    <th id="srNo">S.No.</th>
                                    <th id="state_code">State Code</th>
                                    <th id="state_no">State No.</th>
                                    <th id="state_name">State Name</th>
                                    <th id="start_pin">Start Pin</th>
                                    <th id="end_pin">End Pin</th>
                                    <th id="zone_code">Zone Code</th>
                                    <th id="action">Operations</th> 
                                </tr>
                            </thead>
                            <tbody class="no-bd-y" id="list">
                                        
                            </tbody>
                        </table>
                        <div id="links" class="dataTables_paginate paging_simple_numbers">
                        
                        </div>
					</div><!-- /.box-body -->
          		</div><!-- /.box -->
        	</div><!-- /.col -->
		</div>
	</section>
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
<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script>
	$(function () {
		$("#listitems").DataTable();
		var base_url = '<?php echo base_url(); ?>';
		paginate(base_url+'admin/StateMaster/getList','','','');
		$("#base_url_val").val(base_url+'admin/StateMaster/getList');
	});
</script>
<?php $this->load->view('admin/includes/footer');?>