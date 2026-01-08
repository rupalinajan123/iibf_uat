<?php $this->load->view('iibfdra/admin/includes/header');?>
<?php $this->load->view('iibfdra/admin/includes/sidebar');?>

<style>
	#page_loader { background: rgba(0, 0, 0, 0.35) none repeat scroll 0 0; height: 100%; left: 0; position: fixed; top: 0; width: 100%; z-index: 99999; display:none; }
	
	/* #page_loader .loading2 { margin: 0 auto; position: relative;border: 16px solid #f3f3f3;border-radius: 50%;border-top: 16px solid #064b86;border-bottom: 16px solid #064b86;width: 80px;height: 80px;-webkit-animation: spin 2s linear infinite;animation: spin 2s linear infinite;top: calc( 50% - 40px);} */
	
	#page_loader .loading2 { margin: 0 auto; position: relative;	width: 80px;height: 80px;top: calc( 50% - 40px);color: #fff;font-size: 30px; }
	@-webkit-keyframes spin { 0% { -webkit-transform: rotate(0deg); } 100% { -webkit-transform: rotate(360deg); } }
	@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
		<div id="page_loader"><div class="loading2">Loading...</div></div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Approve NEFT Transactions
      </h1>
      <?php echo $breadcrumb; ?>
    </section>
	<div class="col-md-12">
    <br />
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
    
    	<div class="alert alert-success alert-dismissible" id="neft_msg_success" style="display:none">
        	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        	<span></span>
        </div>
    	<div class="alert alert-danger alert-dismissible" id="neft_msg_error" style="display:none">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            NEFT Transaction Approve/Rejection Error.
        </div>
        
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
                  <th id="srNo">S.No.</th>
                  <th id="transaction_no">NEFT No.</th>
                  <th id="DRA">Application</th>
                  <th id="member_count">No. of Candidates</th>
                  <th id="amount">Amount</th>
                  <th id="pay_date">Paid Date</th>
                  <th id="exam_period">Exam Period</th>
                  <th id="inst_name">Institute Name</th>
                  <th id="action">Operations</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                                    
                </tbody>
              </table>
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
      
      <!-- Modal confirm -->
      <div class="modal" id="confirmModal" style="display: none; z-index: 1050;">
          <div class="modal-dialog">
              <div class="modal-content">
              	  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Verify to Approve/Reject NEFT Transactions</h4>
                  </div>
                  <!-- The form is placed inside the body of modal -->
                  <form id="loginForm" method="post" class="form-horizontal">
                  <div class="modal-body" id="confirmMessage">
                  	<!--<span id="txn_details"></span>-->
                  
                  	<p id="modal_error_msg" style="color:#F00;" align="center"></p>
                    <p id="modal_success_msg" style="color:#0F0;" align="center"></p>
                    
                    <input type="hidden" name="txn_id" id="txn_id" />
                    
                    <div class="form-group">
                        <label class="col-xs-4 control-label">NEFT No. * </label>
                        <div class="col-xs-6">
                            <input type="text" class="form-control" id="form_utr_no_disp" readonly />
                            <input type="hidden" class="form-control" name="form_utr_no" id="form_utr_no" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">Application </label>
                        <div class="col-xs-8">
                            <span id="form_DRA"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">No. of Candidates * </label>
                        <div class="col-xs-6">
                            <input type="text" class="form-control" id="form_mem_count_disp" readonly />
                            <input type="hidden" class="form-control" name="form_mem_count" id="form_mem_count" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">Amount *</label>
                        <div class="col-xs-6">
                            <input type="text" class="form-control" id="form_payment_amt_disp" readonly />
                            <input type="hidden" class="form-control" name="form_payment_amt" id="form_payment_amt" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">Paid Date *</label>
                        <div class="col-xs-6">
                            <input type="text" class="form-control" id="form_payment_date_disp" readonly />
                            <input type="hidden" class="form-control" name="form_payment_date" id="form_payment_date" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">Exam Period </label>
                        <div class="col-xs-8">
                            <span id="form_exam_period"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-4 control-label">Institute Name </label>
                        <div class="col-xs-8">
                            <span id="form_inst_name"></span>
                        </div>
                    </div>
                    
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-success" id="btnApprove" onclick="confirmApprove('Approved');">Approve</button>
                      <button type="button" class="btn btn-warning" id="btnReject" onclick="confirmApprove('Rejected')">Reject</button>
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
$(document).ajaxStart(function() { $("#page_loader").css("display", "block"); });
$(document).ajaxComplete(function() { $("#page_loader").css("display", "none"); });
			
$(function () {
	$("#listitems").DataTable();
	
	$("#listitems_filter").hide();
	
	var base_url = '<?php echo base_url(); ?>';
	var listing_url = base_url+'iibfdra/admin/transaction/getNeftTransactions';
		
	paginate(listing_url,'','');
	$("#base_url_val").val(listing_url);
});

function confirmVerify(id)
{
	var txn_id = id;
	
	var base_url = '<?php echo base_url(); ?>';
	var url = base_url+'iibfdra/admin/transaction/getNeftTransactionDetails';
	
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
					
					$("#txn_id").val(txn_id);
					
					$("#form_utr_no_disp").val(res.result[0].transaction_no);
					$("#form_utr_no").val(res.result[0].transaction_no);
					$("#form_DRA").text(res.result[0].DRA);
					$("#form_mem_count_disp").val(res.result[0].member_count);
					$("#form_mem_count").val(res.result[0].member_count);
					$("#form_payment_amt_disp").val(res.result[0].amount);
					$("#form_payment_amt").val(res.result[0].amount);
					$("#form_payment_date_disp").val(res.result[0].date);
					$("#form_payment_date").val(res.result[0].date);
					$("#form_exam_period").text(res.result[0].exam_period);
					$("#form_inst_name").text(res.result[0].inst_name);
					
					if(res.result[0].status == 0) { $("#btnReject").css("display","none"); } else { $("#btnReject").css("display","inline-block"); }					
					
					//$('#form_payment_date').datepicker({format: 'dd-mm-yyyy',endDate: '+0d',autoclose: true});
					$('#form_payment_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true});
					
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

function confirmApprove(action)
{
	
	var txn_id = $("#txn_id").val();
	var utr_no = $("#form_utr_no").val();
	var mem_count = $("#form_mem_count").val();
	var payment_amt = $("#form_payment_amt").val();
	var payment_date = $("#form_payment_date").val();
	//alert(txn_id + '-' + utr_no + '-' + mem_count + '-' + payment_amt + '-' + payment_date);
	
	if(utr_no == '' || mem_count == '' || payment_amt == '' || payment_date == '')
	{
		$("#modal_error_msg").text("All fields are required.");
		return false;	
	}
	
	$("#neft_msg_success").hide();
	$("#neft_msg_error").hide();
	
	var base_url = '<?php echo base_url(); ?>';
	var url = base_url+'iibfdra/admin/transaction/approveNeftTransactions';
	
	$.ajax({
		url: url,
		type: 'POST',
		dataType:"json",
		data: {id: txn_id, action: action, utr_no: utr_no, mem_count: mem_count, payment_amt: payment_amt, payment_date: payment_date},
		success: function(res) 
		{
			if(res)
			{
				if(res.success == 'success')
				{
					/* alert('success'); */
					$("#neft_msg_success span").text("NEFT Transaction " + action + ".");
					$("#neft_msg_success").show();
					// update status -
					//$("span[data-id='"+txn_id+"']").html(action);
					$("#transaction_action_outer_"+txn_id).html(action);
					$("#confirmModal").modal("hide");
					
					if(res.chk_exam_mode == 'RPE')
					{					
					var url_admitcard = base_url+'iibfdra/admin/transaction/dra_generate_admitcard';
					$.ajax({
							url: url_admitcard,
							type: 'POST',
							dataType:"json",
							data: {id: txn_id, action: action, utr_no: utr_no, mem_count: mem_count, payment_amt: payment_amt, payment_date: payment_date},
							success: function(res) {
								if(res){}
				
							},				
							error: function(jqXHR, textStatus, errorThrown) {
								console.log(textStatus, errorThrown);
							}
					});
					}
				}
				else
				{
					//alert("error");
					$("#neft_msg_error").show();
					$("#confirmModal").modal("hide");
					return false;
				}
			}
		},
		error: function(jqXHR, textStatus, errorThrown) 
		{
			console.log(textStatus, errorThrown);
			$("#confirmModal").modal("hide");
		}
	});
}
</script>
 
<?php $this->load->view('iibfdra/admin/includes/footer');?>