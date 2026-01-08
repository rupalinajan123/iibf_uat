
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Transactions Details</h1>
    <?php echo $breadcrumb; ?>
  </section>
  <div class="col-md-12">
    <?php if($this->session->flashdata('error')!=''){?>
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?php echo $this->session->flashdata('error'); ?> </div>
    <?php } if($this->session->flashdata('success')!=''){ ?>
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <?php echo $this->session->flashdata('success'); ?> </div>
    <?php } ?>
  </div>
  <!-- Main content -->
  <section class="content">
    <form class="form-horizontal" name="searchtransform" id="searchtransform" action="" method="post">
      <div class="row">
        <div class="col-md-12"> 
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Search</h3>
            </div>
            <!-- /.box-header --> 
            <!-- form start -->
            <div class="box-body">
              <?php if($this->session->flashdata('error')!=''){?>
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('error'); ?> </div>
              <?php } if($this->session->flashdata('success')!=''){ ?>
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('success'); ?> </div>
              <?php } ?>
              <div class="form-group">
                <label for="roleid" class="col-sm-2">Registration No. :</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="reg_no" name="reg_no" placeholder="Registration No." required value="<?php echo set_value('reg_no');?>">
                  <span class="error"><?php echo form_error('reg_no');?></span> </div>
                <label for="exam_period" class="col-sm-2">NEFT/UTR No. :</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="txn_no" name="txn_no" placeholder="NEFT/UTR No." required value="<?php echo set_value('txn_no');?>">
                  <span class="error"><?php echo form_error('txn_no');?></span> </div>
              </div>
              <div class="form-group">
                <label for="from_date" class="col-sm-2">From Date :</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="from_date" name="from_date" placeholder="From Date" required value="<?php echo set_value('from_date');?>" readonly="readonly">
                  <span class="error"><?php echo form_error('from_date');?></span> </div>
                <label for="to_date" class="col-sm-2">To Date :</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="to_date" name="to_date" placeholder="To Date" value="<?php echo set_value('to_date');?>" readonly="readonly">
                  <span class="error"><?php echo form_error('to_date');?></span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-2">Payment Mode :</label>
                <div class="col-sm-3">
                  <select class="form-control" id="payment_mode" name="payment_mode" required>
                    <option value="" selected="">ALL</option>
                    <option value="1">Offline</option>
                    <!--<option value="2">Online</option>-->
                  </select>
                  <span class="error"><?php echo form_error('payment_mode');?></span> </div>
                <label for="exam_period" class="col-sm-2">Payment Status :</label>
                <div class="col-sm-3">
                  <select class="form-control" id="payment_status" name="payment_status" required>
                    <option value="" selected="">ALL</option>
                    <option value="1">Success</option>
                    <option value="0">Fail</option>
                  </select>
                  <span class="error"><?php echo form_error('payment_status');?></span> </div>
              </div>
            </div>
            <div class="box-footer">
              <div class="col-sm-2 col-xs-offset-5">
                <input type="button" class="btn btn-info" name="btnSearch" id="btnSearch" value="Submit" onclick="return getSearchResult();">
                <button type="reset" class="btn btn-default pull-right"  name="btnReset" id="btnReset" onclick="resetForm();">Reset</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
    <div class="row">
      <div class="col-xs-12">
        <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
        <input type="hidden" name="base_url_val" id="base_url_val" value="" />
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Payment Transaction Details</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive">
            <table id="listitems" width="100%" class="table table-bordered table-striped dataTables-example">
              <thead>
                <tr>
                  <th id="srNo" width="0%">#</th>
                  <th id="exam_code" width="5%">Exam Code</th>
                  <th id="exam_period" width="5%">Exam Period</th>
                  <th id="inst_code" width="5%">Bank ID</th>
                  <th id="inst_name" width="15%">Bank Name</th>
                  <th id="paid_reg_nos" width="20%">Paid Reg. Nos.</th>
                  <th id="receipt_no" width="5%">Receipt No.</th>
                  <th id="transaction_no" width="5%">NEFT/UTR No.</th>
                  <th id="pay_date" width="10%">Payment Date</th>
                  <!--<th id="bankcode" width="5%">Branch</th>-->
                  <th id="member_count" width="5%">Pay Count</th>
                  <th id="amount" width="5%">Amount</th>
                  <th id="disc_amt" width="10%">Disc Amt</th>
                  <th id="tds_amt" width="10%">TDS Amt</th>
                  <th id="status" width="10%">Payment Status</th>
                  <th id="action" width="5%">Actions</th>
                </tr>
              </thead>
              <tbody class="no-bd-y" id="list">
              </tbody>
            </table>
            <div id="links" class="dataTables_paginate paging_simple_numbers"> </div>
          </div>
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
      </div>
      <!-- /.col --> 
    </div>
  </section>
</div>
<style>
th { text-align:center;}
</style>
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
$(document).ready(function() 
{
	$("#listitems_filter").hide();
	
	$('#from_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#to_date').datepicker('setStartDate', new Date($(this).val()));
	}); 
	
	$('#to_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#from_date').datepicker('setEndDate', new Date($(this).val()));
	});
});

$(function () {
	$("#listitems").DataTable();
	
	var base_url = '<?php echo base_url(); ?>';
	var listing_url = base_url+'bulk/BulkTransaction/getTransactions';
		
	paginate(listing_url,'','');
	$("#base_url_val").val(listing_url);
});
// function to reset -
function resetForm()
{
	var base_url = '<?php echo base_url(); ?>';
	var listing_url = base_url+'bulk/BulkTransaction/getTransactions';
		
	paginate(listing_url,'','');
	$("#base_url_val").val(listing_url);	
}
// function to get search result -
function getSearchResult()
{
	var perPage = $('#perPage').val();
	
	var reg_no = $("#reg_no").val();
	var txn_no = $("#txn_no").val();

	var from_date = $("#from_date").val();
	var to_date = $("#to_date").val();
	var payment_mode = $("#payment_mode").val();
	var payment_status = $("#payment_status").val();
	
	if(reg_no == "" && txn_no == "" && from_date == "" && to_date == "" && payment_mode == "" && payment_status == "")
	{
		alert("Please enter atleast one data.");
		return false;
	}
	
	var searcharr = [];
	
	searcharr['field'] = '';
	searcharr['value'] = reg_no+'~'+txn_no+'~'+from_date+'~'+to_date+'~'+payment_mode+'~'+payment_status;
	
	var base_url = '<?php echo base_url(); ?>';
	var listing_url = base_url+'bulk/BulkTransaction/getTransactions';
	
	// Pagination function call
	paginate(listing_url,searcharr,'');
	$("#base_url_val").val(listing_url);
}
</script>

