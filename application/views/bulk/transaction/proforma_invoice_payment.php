
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Proforma Invoice Payment </h1>
    <?php echo $breadcrumb; ?> </section>
  <div class="col-md-12"> <br />
    <?php if($this->session->flashdata('error')!=''){?>
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?php echo $this->session->flashdata('error'); ?> </div>
    <?php } if($this->session->flashdata('success')!=''){ ?>
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?php echo $this->session->flashdata('success'); ?> </div>
    <?php } ?>
    <div class="alert alert-success alert-dismissible" id="neft_msg_success" style="display:none">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <span></span> </div>
    <div class="alert alert-danger alert-dismissible" id="neft_msg_error" style="display:none">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      NEFT Transaction Update Error. </div>
      
      <div class="alert alert-danger alert-dismissible" id="neft_msg_error1" style="display:none">

      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

      Entered UTR No alrady present. </div>
  </div>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
            <input type="hidden" name="base_url_val" id="base_url_val" value="" />
            <div class="table-responsive">
              <table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
                  <tr>
                    <th id="srNo" width="0%">#</th>
                    <th id="exam_code" width="5%">Exam Code</th>
                    <th id="exam_period" width="5%">Exam Period</th>
                    <th id="receipt_no">Receipt No</th>
                    <th id="proformo_invoice_no" width="15%">Proforma Invoice No.</th>
                    
                    <th id="transaction_no" width="10%">Transaction No.</th>
                    <th id="exam_inv_date" width="20%">Invoice Generated On</th>
                    <th id="member_count" width="10%">Payment Count</th>
                    <th id="amount" width="10%">Amount</th>
                    <th id="action" width="11%">Action</th>
                  </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                </tbody>
              </table>
              <div id="links" class="dataTables_paginate paging_simple_numbers"> </div>
            </div>
          </div>
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
      </div>
      <!-- /.col --> 
    </div>
    
    <!-- Modal confirm -->
  
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
<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">
<script src="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.css">
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script> 
<script type="text/javascript">
  //$('#searchDate').parsley('validate');
</script> 
<script src="<?php echo base_url()?>js/js-paginate.js"></script> 
<script>
$(function () {
	$("#listitems").DataTable();
	
	$("#listitems_filter").hide();
	
	var base_url = '<?php echo base_url(); ?>';
	var listing_url = base_url+'bulk/BulkTransaction/getProformaTransactions';
		
	paginate(listing_url,'','');
	$("#base_url_val").val(listing_url);
});



</script>