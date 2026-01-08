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
	<form class="form-horizontal" name="usersAddForm" id="usersAddForm" action="" method="post">
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Active Exam List</h3>
              <div class="pull-right">
              	<a href="<?php echo base_url();?>bulk/admin/ExamActiveMaster/add" class="btn btn-warning">Add Exam</a>
                <!--<a href="<?php //echo base_url();?>bulk/admin/ExamActiveMaster/import" class="btn  btn-primary">Import</a>
                <a href="<?php //echo base_url();?>bulk/admin/ExamActiveMaster/download" class="btn  btn-primary">Download</a>-->
                <!--<input type="button" class="btn btn-info" onclick="refreshDiv('');" value="Refresh">-->
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
            <div class="table-responsive">
              <table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
                <tr>
                  <!--<th id="select">
                  	<input type="checkbox" name="check_list_all[]" id="select_all" value="1" class="chk" >
                  </th>-->
                  <th id="srNo">Sr.</th>
                  <th id="institute_code">Bank Code</th>
                  <th id="institute_name">Bank Name</th>
                  <th id="description">Exam Name</th>
                  <th id="exam_code">Exam Code</th>
                  <th id="exam_period">Exam Period</th>
                  <th id="exam_from_date">From Date</th>
                  <!--<th id="exam_from_time">Exam From Time</th>-->
                  <th id="exam_to_date" >To Date</th>
                  <!--<th id="exam_to_time">Exam To Time</th>-->
                  <!--<th id="tds">TDS(%)</th>-->
                  <th id="discount">Disc(%)</th>
                  <th id="action">Action</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                                    
                </tbody>
              </table>
              <!-- <div id="links" class="" style="float:right;"><?php //echo $links; ?></div>-->
               <div id="links" class="dataTables_paginate paging_simple_numbers">
               
               </div>
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
<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script type="text/javascript">
$(function () {
	$('#usersAddForm').parsley('validate');
	$("#listitems").DataTable();
	var base_url = '<?php echo base_url(); ?>';
	var listing_url = base_url+'bulk/admin/ExamActiveMaster/getList';
	
	// Pagination function call
	paginate(listing_url,'','','');
	$("#base_url_val").val(listing_url);
});
</script>
<?php $this->load->view('bulk/admin/includes/footer');?>