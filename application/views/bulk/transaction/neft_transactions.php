
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> NEFT Details </h1>
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
                    <th id="inst_code">Bank ID</th>
                    <th id="inst_name" width="30%">Bank Name</th>
                    <th id="member_count" width="5%" nowrap="nowrap">Pay Count</th>
                    <th id="amount" width="10%">Amount</th>
                    <th id="disc_amt" width="10%">Disc Amt</th>
                    <th id="tds_amt" width="10%">TDS Amt</th>
                    <th id="pay_date" width="10%">Paid Date</th>
                    <th id="transaction_no" width="10%">NEFT/UTR No.</th>
                    <th id="added_date" width="12%">Added Date</th>
                    <th id="updated_date" width="12%">Approve Date</th>
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
    <div class="modal" id="confirmModal" style="display: none; z-index: 1050;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
        <center> <h4 class="modal-title"><strong>After payment NEFT / RTGS (UTR) Number</strong></h4></center>
          </div>
          <!-- The form is placed inside the body of modal -->
          <form id="loginForm" method="post" class="form-horizontal" autocomplete="off">
            <div class="modal-body" id="confirmMessage"> 
              <!--<span id="txn_details"></span>-->
              
              <p id="modal_error_msg" style="color:#F00;" align="center"></p>
              <p id="modal_success_msg" style="color:#0F0;" align="center"></p>
              <input type="hidden" name="txn_id" id="txn_id" />
              <div class="form-group">
                <label class="col-xs-4 control-label">NEFT / RTGS (UTR) Number <span style="color:#F00">* </span>: </label>
                <div class="col-xs-6">
                  <input type="text" class="form-control" name="form_utr_no" id="form_utr_no" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">Paid Date <span style="color: #F00">* </span> : </label>
                <div class="col-xs-6">
                  <input type="text" class="form-control" name="form_payment_date" id="form_payment_date" />
                </div>
              </div>
              <div class="form-group" id="states_dropdown">
                    <label for="" class="col-xs-4 control-label">State<span style="color:#F00">*</span></label>
                        <div class="col-xs-6">
                        <select class="form-control" id="state" name="state" required >
                            <option value="">Select</option>
                            <?php if(count($states) > 0){
                                    foreach($states as $row1){ 	?>
                            <option value="<?php echo $row1['state_name'];?>" ><?php echo $row1['state_name'];?></option>
                            <?php } } ?>
                          </select>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id="btnApprove" onclick="updateNeft();">Update</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
      </div>
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
  //$('#searchDate').parsley('validate');
</script> 
<script src="<?php echo base_url()?>js/js-paginate.js"></script> 
<script>
$(function () {
	$("#listitems").DataTable();
	
	$("#listitems_filter").hide();
	
	var base_url = '<?php echo base_url(); ?>';
	var listing_url = base_url+'bulk/BulkTransaction/getNeftTransactions';
		
	paginate(listing_url,'','');
	$("#base_url_val").val(listing_url);
});

function confirmVerify(id)
{
	var txn_id = id;
	
	var base_url = '<?php echo base_url(); ?>';
	var url = base_url+'bulk/BulkTransaction/getNeftTransactionDetails';
	
	$.ajax({
		url: url,
		type: 'POST',
		dataType:"json",
		data: {id: txn_id},
		success: function(res) {
			if(res)
			{
				if(res.success == 'success')
				{
					//alert("success");
					//alert(res.result[0].id);
					$("#state").attr('selectedIndex', '-1');
					$("#state").val('');
					$("#txn_id").val(txn_id);
					
					//$("#form_utr_no").val(res.result[0].transaction_no);
					$("#form_mem_count").text(res.result[0].member_count);
					$("#form_payment_amt").text(res.result[0].amount);
					//$("#form_payment_date").val(res.result[0].date);
					$("#form_exam_period").text(res.result[0].exam_period);
					//$("#form_inst_name").text(res.result[0].inst_name);

					if (res.result[0].exam_code!='994' && res.result[0].exam_code!='1056') {
						
						$("#states_dropdown").hide();
					}else{
						$("#states_dropdown").show();
					}
					
				$('#form_payment_date').datepicker({format: 'dd-mm-yyyy',endDate: '+0d',autoclose: true});
					
					var modal = $("#confirmModal");
    				modal.modal("show");
				}
				else
				{
					//alert("error");
					return false;
				}
			}
			else
			{
				//alert("error");
				return false;
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
			console.log(textStatus, errorThrown);
		}
	});
}

function updateNeft()
{
	var txn_id = $("#txn_id").val();
	var utr_no = $("#form_utr_no").val();
	var payment_date = $("#form_payment_date").val();
	var state = $("#state").val();
	
	
	
	//alert(txn_id + '-' + utr_no + '-' + '-' + payment_date);
	
	if(utr_no == '' || payment_date == '')
	{
		$("#modal_error_msg").text("All fields are required.");
		return false;	
	}
	
	if (/\s/.test(utr_no)) {
		$("#modal_error_msg").text("Space is not allowed in NEFT / RTGS (UTR) Number.");
		return false;	
	}
	
	$(".loading").show();
	
	$("#neft_msg_success").hide();
	$("#neft_msg_error").hide();
	
	var base_url = '<?php echo base_url(); ?>';
	var url = base_url+'bulk/BulkTransaction/updateNeftTransaction';
	
	$.ajax({
		url: url,
		type: 'POST',
		dataType:"json",
		data: {id: txn_id, utr_no: utr_no, payment_date: payment_date,state:state},
		success: function(res) {
			if(res)
			{
				if(res.success == 'success')
				{
					//alert('success');
					$("#neft_msg_success span").text("NEFT Transaction Updated Successfully.");
					$("#neft_msg_success").show();
					$("#neft_msg_error1").hide();
					// update status -
					//$("span[data-id='"+txn_id+"']").html(action);
				}
				else
				{
					//alert('error');
					if(res.success == 'error1'){
						$("#neft_msg_error1").show();
					}
					$("#neft_msg_error").show();
					
					//$("#neft_msg_error").show();
					//return false;
				}
				$(".loading").hide();
			}
			var modal = $("#confirmModal");
			modal.modal("hide");
		},
		error: function(jqXHR, textStatus, errorThrown) {
			console.log(textStatus, errorThrown);
		}
	});
}

</script>