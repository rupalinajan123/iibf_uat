  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Proforma Invoice Payment
      </h1>
      <?php echo $breadcrumb; ?>
    </section>
	<div class="col-md-12">
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
    </div>
    <!-- Main content -->
    <section class="content">
    
      <form class="form-horizontal" name="searchtransform" id="searchtransform" action="" method="post">
        <div class="row">
          <div class="col-md-12">
            <!-- Horizontal Form -->
            <!-- <div class="box box-info"> -->
              <!-- <div class="box-header with-border">
                <h3 class="box-title">Search</h3>
              </div> -->
              <!-- /.box-header -->
              <!-- form start -->
              <!-- <div class="box-body">               
					      <?php if($this->session->flashdata('error')!=''){?>
                  <div class="alert alert-danger alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <?php echo $this->session->flashdata('error'); ?>
                  </div>
                  <?php } if($this->session->flashdata('success') != '') { ?>
                    <div class="alert alert-success alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php } ?> 
              </div> -->
             <!-- </div> -->
          </div>
        </div>
      </form>
    
      <div class="row">
        <div class="col-xs-12">
        
        <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
        <input type="hidden" name="base_url_val" id="base_url_val" value="" />

          <div class="box">
          	<div class="box-header with-border">
              <h3 class="box-title">Proforma Invoice Payment</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
            	
			<table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
                <tr>
                  <th id="srNo">Sr. No.</th>
                  <!-- <th id="inst_code">Inst ID</th> -->
                  <!-- <th id="inst_name">Institute Name</th> -->
                  <!-- <th id="paid_reg_nos">Paid Reg. Nos.</th> -->
                  <th id="exam_period">Exam Period</th>
                  <th id="receipt_no">Receipt No.</th>
                  <th id="proformo_invoice_no">Proformo Invoice No.</th>
                  <th id="status">Payment Status</th>
                  <th id="transaction_no">Transaction No.</th>
                  <th id="pay_date">Invoice Generated Date</th>
                  <!-- <th id="bankcode">Branch</th> -->
                  <th id="member_count">Payment Count</th>
                  <th id="amount">Amount(in Rs.)</th>
                  <th id="action">Proforma Invoice</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                                    
                </tbody>
              </table>
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
  //$('#searchtransform').parsley('validate');
</script>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script type="text/javascript">

$(function () {
	$("#listitems").DataTable();
	
	var base_url = '<?php echo base_url(); ?>';
	var listing_url = base_url+'iibfdra/Version_2/TrainingBatches/getTransactions';
		
	paginate(listing_url,'','');
	$("#base_url_val").val(listing_url);
});
// function to reset -
function resetForm()
{
	var base_url = '<?php echo base_url(); ?>';
	var listing_url = base_url+'iibfdra/Version_2/TrainingBatches/getTransactions';
		
	paginate(listing_url,'','');
	$("#base_url_val").val(listing_url);	
}

// function to get search result -
function getSearchResult()
{
	// var perPage = $('#perPage').val();
	
	// var reg_no = $("#reg_no").val();
	// var txn_no = $("#txn_no").val();
	// var from_date = $("#from_date").val();
	// var to_date = $("#to_date").val();
	// var payment_mode = $("#payment_mode").val();
	// var payment_status = $("#payment_status").val();
	
	// if(reg_no == "" && txn_no == "" && from_date == "" && to_date == "" && payment_mode == "" && payment_status == "")
	// {
	// 	alert("Please enter atleast one data.");
	// 	return false;
	// }
	
	// var searcharr = [];
	
	// searcharr['field'] = '';
	// searcharr['value'] = reg_no+'~'+txn_no+'~'+from_date+'~'+to_date+'~'+payment_mode+'~'+payment_status;
	
	// var base_url = '<?php echo base_url(); ?>';
	// var listing_url = base_url+'iibfdra/Version_2/TrainingBatches/getTransactions';

	// paginate(listing_url,searcharr,'');
	// $("#base_url_val").val(listing_url);
}
</script>