<?php $this->load->view('iibfdra/admin/includes/header');?>
<?php $this->load->view('iibfdra/admin/includes/sidebar');?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Agency (<?php echo ucfirst($result['inst_name']); ?>) Center Details 
      <!-- <a class="btn btn-primary right" href="<?php //echo base_url().'iibfdra/agency/' ; ?>">Agency listing</a>--> 
    </h1>
    <?php echo $breadcrumb; ?> </section>
  <div class="col-md-12"> <br />
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
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info box-solid disabled">
          <div class="box-header with-border">
            <h3 class="box-title">Traning Center Detail's</h3>
            <div class="box-tools pull-right">
              <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
            </div>
            <!-- /.box-tools --> 
          </div>
          <!-- /.box-header -->
          <div class="box-body" style="display: block;">
            <?php 
					   if($result['center_status'] == 'A' ){ 
					   		$status_text =  'Approved'; 
							$str_btn = '<textarea name="rejection" class="rejection" rows="5" cols="40" placeholder="Describe rejection reason here"></textarea></br><a class="reject_center btn btn-danger " dataid ="'.$result['center_id'].'" href="javascript:voind(0);">Reject</a>';							
							$div_class = '#d4edda';
							$div_class2 = '#d4edda';
					   }else if($result['center_status'] == 'IR' ){ 
					   		$status_text =  'In Review'; 
							$str_btn = '<a class="approve_center btn btn-success " dataid ="'.$result['center_id'].'" href="javascript:voind(0);">Approve</a>';
							$div_class = '#f8d7da';
							$div_class2 = '#f8d7da';
					   }else { 
					   		$status_text =  'Reject'; 
							$str_btn = '<a class="approve_center btn btn-success " dataid ="'.$result['center_id'].'" href="javascript:voind(0);">Approve</a>';
							$div_class = '#f8d7da';
							$div_class2 = '#f8d7da';
					   }					   
					   $div_class = '';	
					   $div_class2 = '';			  
				?>
            <form method="post" name="approve_center_from" id="approve_center_from" >
              <input type="hidden" id="center_status" name="center_status" value="<?php echo $result['center_status'] ?>" />
              <input type="hidden" name="agency_id" value="<?php echo $result['agency_id'] ?>" />
              <input type="hidden" name="action" value="update_status" />
              <div class="table-responsive ">
                <table class="table table-bordered" style="background:<?php echo $div_class; ?>;">
                  <tbody>
                    <!--<tr>                      
                        <td width="50%"><strong>Agency Name :</strong></td>
                        <td width="50%"><?php //echo $result['inst_name']; ?></td>
                      </tr> -->
                    
                    <tr>
                      <td width="50%"><strong>Center Location  :</strong></td>
                      <td width="50%"><?php echo $result['location_name']; ?></td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Date of Approved :</strong></td>
                      <td width="50%"><?php echo date_format(date_create($result['date_of_approved']),"d-M-Y"); ?></td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Valid From To Valid To  :</strong></td>
                      <td width="50%"> FROM <strong><?php echo date_format(date_create($result['center_validity_from']),"d-M-Y"); ?> </strong> TO <strong><?php echo date_format(date_create($result['center_validity_to']),"d-M-Y"); ?></strong></td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Center Type:</strong></td>
                      <td width="50%"><?php					  
					  if( $result['center_type'] == 'R'){
						  echo 'Regular';
						  }else{
						  echo 'Temporary';  
						 } ?></td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Center Status:</strong></td>
                      <td width="50%" style="background:<?php echo $div_class2; ?>;" ><strong><?php echo ' '.$status_text; ?> </strong></td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Action:</strong></td>
                      <td width="50%"><?php echo $str_btn; ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </form>
          </div>
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
      </div>
      <div class="col-xs-12">
        <div class="box-header">
          <h3 class="box-title">Add Accridation period </h3>
        </div>
        <div class="box">
          <div class="box-body">
            <div class="table-responsive">
              <form name="accridation_date" id="add_date" method="POST">
                <table class="table table-bordered" style="background:<?php echo $div_class; ?>;">
                  <tbody>
                    <?php					  
					  if( $result['center_type'] == 'R'){  
					  $current_year = date('Y');
					  $next_year = $current_year+1;
					  $next_next_year = $current_year+2;
					  ?>
                  <td width="50%"><strong> Add End date Of Accredatio period </strong></td>
                    <td width="50%"><select class="center_validity form-control" name="center_validity_to_date" id="center_validity">
                        <option  value="31-03-<?php echo $next_year;?>" >31 March <?php echo $next_year;?> </option>
                        <option value="31-03-<?php echo $next_next_year+1;?>" >31 March <?php echo $next_next_year;?></option>
                      </select></td>
                    <?php  }else{ ?>
                    <td width="50%"><strong> Add Start date Of Accredatio period (to end date will be + 90 days )</strong></td>
                    <td width="50%"><input type="text" class="center_validity form-control" name="center_validity_from_date" id="center_validity"  maxlength="10" />
                      <input type="text"  class="center_validity_to center_validity_to_date form-control" name="center_validity_to_date" id="center_validity_to_date"  maxlength="10" /></td>
                    <script type="text/javascript">						 
						 //$('#center_validity').datepicker({format: 'dd-mm-yyyy',autoclose: true});
						 $("#center_validity").datepicker({
							autoclose: true, 
							format: 'dd-mm-yyyy', 							        
							dateFormat: 'dd-mm-yyyy',
							 onSelect: function(selected) {
							   $("#center_validity_to_date").datepicker("startDate", selected)
							}							
						}).attr('readonly', 'readonly');
						
						$('#center_validity_to_date').datepicker({
								autoclose: true, 
								format: 'dd-mm-yyyy', 												        
								dateFormat: 'dd-mm-yyyy',								
								minDate: '+90d',
								maxDate: '+30Y'
						}).attr('readonly', 'readonly');
						
						
						$(document).ready(function () {
							$('#center_validity').datepicker();
						/*	
						  $("#center_validity_to_date").datepicker({
								autoclose: true, 
								format: 'dd-mm-yyyy', 												        
								dateFormat: 'dd-mm-yyyy',
								minDate:'0d'	
							}).attr('readonly', 'readonly');*/
							
							
							/* $('#center_validity_to_date').datepicker({
								autoclose: true, 
								format: 'dd-mm-yyyy', 												        
								dateFormat: 'dd-mm-yyyy',							
								changeMonth: true,
								changeYear: true,
								yearRange: '2018:2025',	
								minDate: '+90d',
								maxDate: '+30Y'
							});*/
							
							$('#center_validity_to_date').datepicker({
								autoclose: true, 
								format: 'dd-mm-yyyy', 												        
								dateFormat: 'dd-mm-yyyy',							
								changeMonth: true,
								changeYear: true,																
								minDate: '+90d',
								maxDate: '+30Y'
							});
							
							
						});
					
					$('#center_validity').change(function() {
					  var date2 = $('#center_validity').datepicker('getDate', '+90d'); 
					  //console.log(date2);
					  date2.setDate(date2.getDate()+90); 
					  //console.log(date2);
					  $("#center_validity_to_date").datepicker("option", "minDate",date2);
					  $("#center_validity_to_date").datepicker("option", "startDate",date2);
					  $('#center_validity_to_date').datepicker('setDate', date2);
					    $("#center_validity_to_date").datepicker("option", "startDate",date2);
					 // $("#center_validity_to_date" ).datepicker( "option", "mixDate", 0 );
					 $('#center_validity_to_date').datepicker({
						autoclose: true, 
						format: 'dd-mm-yyyy', 												        
						dateFormat: 'dd-mm-yyyy',
						minDate: '+90d',
						startDate: date2,
						maxDate: '+30Y'
					}).attr('readonly', 'readonly');
					  
					});
					
					</script>
                    <?php	 } ?>
                  </tr>
                  <tr>
                    <td width="50%"></td>
                    <td width="50%"><a class="add_accredation_btn btn btn-primary " dataid ="<?php echo $result['center_id']; ?> " href="javascript:voind(0);">Save Accridation period</a></td>
                  </tr>
                    </tbody>
                  
                </table>
                <input type="hidden" name="action" value="add_date" />
                <input type="hidden" name="center_id" value="<?php echo $result['center_id']; ?>" />
                <input type="hidden" name="center_type" id="center_type" value="<?php echo $result['center_type'];?>" />
                <input type="hidden" name="center_status" value="<?php echo $result['center_status'] ?>" />
                <input type="hidden" name="agency_id" value="<?php echo $result['agency_id'] ?>" />
              </form>
            </div>
          </div>
        </div>
      </div>
      
      <!-- <div class="col-xs-12">
          <div class="box">   
                 <a onClick="open_models()" href="javascript:void(0);" >Open popup</a>
          </div>
          </div>-->
      
     <div class="col-xs-12" >
        <div class="box-header">
          <h3 class="box-title">Agency center logs</h3>
        </div>
         <div class="box-tools pull-right">
              <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
            </div>
        <div class="box">
          <div class="box-body">         
            <div class="table-responsive">
              <table id="listitems22" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th id="srNo">S.No.</th>
                    <th id="location_name">title</th>
                    <th id="date_of_approved">Date </th>
                    <th id="center_validity_from">action / comment</th>                  
                  </tr>
                </thead>
                <tbody class="no-bd-y" id="list222">
                </tbody>
              </table>           
            </div>
          </div>
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
      </div>
      <!-- /.col --> 
     
      
      <div class="col-xs-12" style="display:none">
        <div class="box-header">
          <h3 class="box-title">Batch Details for location : [ <?php echo $result['location_name']; ?> ]</h3>
        </div>
        <div class="box">
          <div class="box-body">
            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
            <input type="hidden" name="base_url_val" id="base_url_val" value="" />
            <div class="table-responsive">
              <table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
                  <tr>
                    <th id="srNo">S.No.</th>
                    <th id="location_name">Name</th>
                    <th id="date_of_approved">Date of approved</th>
                    <th id="center_validity_from">Valid From</th>
                    <th id="center_validity_to">Valid To</th>
                    <th id="center_type">Center type</th>
                    <th id="center_status">status</th>
                    <th id="action">Operations</th>
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
      
    <!-- Modal confirm -->
    <div class="modal" id="confirmModal" style="display: none; z-index: 1050;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
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
                  <input type="text" class="form-control" name="form_utr_no" id="form_utr_no" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">Application </label>
                <div class="col-xs-8"> <span id="form_DRA"></span> </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">No. of Candidates * </label>
                <div class="col-xs-6">
                  <input type="text" class="form-control" name="form_mem_count" id="form_mem_count" readonly/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">Amount *</label>
                <div class="col-xs-6">
                  <input type="text" class="form-control" name="form_payment_amt" id="form_payment_amt" readonly/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">Paid Date *</label>
                <div class="col-xs-6">
                  <input type="text" class="form-control" name="form_payment_date" id="form_payment_date" readonly />
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">Exam Period </label>
                <div class="col-xs-8"> <span id="form_exam_period"></span> </div>
              </div>
              <div class="form-group">
                <label class="col-xs-4 control-label">Institute Name </label>
                <div class="col-xs-8"> <span id="form_inst_name"></span> </div>
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
    
    </div>      
  </section>
</div>

<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script> 
<script type="text/javascript">
  //$('#searchDate').parsley('validate');
</script>
<style>
.err{
 border:1px solid #F00;	
}
.rejection{
 display:none;	
}
#center_validity{
 width:230px;	
}
#center_validity_to_date{
 width:230px;	
}
</style>
<script src="<?php echo base_url()?>js/js-paginate.js"></script> 
<script>
$(function () {
	
	$('.approve_center').click(function(){  		
		if (confirm('Are you sure you want to approve Center?')) {
			$('#approve_center_from').submit();	
		} else {
			return false;
		}			
	});
	
	$('.add_accredation_btn').click(function(){ 
		
		var center_status = $('#center_status').val();
		
		if(center_status != 'A'){
		  alert("Please approve Center before adding Accredation period")
		  return false;	
		}
		
		var center_validity = $('#center_validity').val();		
		if(center_validity == ''){
		  alert("Please add Accredation period")
		   $('#center_validity').addClass('err');
		  return false;	
		}		
			
		if (confirm('Are you sure you want to Add Accredation period for this Center?')) {
			$('#add_date').submit();	
			//center_status
		} else {
			return false;
		}			
	});
	
	$('.reject_center').click(function(){
		$('.rejection').show();
		
		var rejection = $('.rejection').val();
		
		if(rejection == ''){
		  $('.rejection').addClass('err');
		  return false;	
		}
		
		
  		if (confirm('Are you sure you want to Reject Center?')) {
			$('#approve_center_from').submit();	
		} else {
			return false;
		}		
	});
	
	$("#listitems").DataTable();
	
	$("#listitems_filter").hide();
	
	var base_url = '<?php echo base_url(); ?>';
	var center_id = '<?php echo $result["center_id"]; ?>';
	
	var listing_url = base_url+'iibfdra/agency/get_tranning_center_list/'+center_id;
		
	paginate(listing_url,'','');
	$("#base_url_val").val(listing_url);
});

function open_models(){
  $('#confirmModal').modal('show');
}

function confirmVerify(id)
{
	var txn_id = id;
	
	var base_url = '<?php echo base_url(); ?>';
	var url = base_url+'iibfdra/agency/get_center_details';
	
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
					
					$("#form_utr_no").val(res.result[0].transaction_no);
					$("#form_DRA").text(res.result[0].DRA);
					$("#form_mem_count").val(res.result[0].member_count);
					$("#form_payment_amt").val(res.result[0].amount);
					$("#form_payment_date").val(res.result[0].date);
					$("#form_exam_period").text(res.result[0].exam_period);
					$("#form_inst_name").text(res.result[0].inst_name);
					
         // $('#form_payment_date').datepicker({format: 'dd-mm-yyyy',endDate: '+0d',autoclose: true});
          $("#form_payment_date").datepicker({ dateFormat: "dd-mm-yyyy",endDate: '+0d',autoclose: true}).datepicker("setDate", "0");
					
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
		success: function(res) {
			if(res)
			{
				if(res.success == 'success')
				{
					//alert("success");
					
					$("#neft_msg_success span").text("NEFT Transaction " + action + ".");
					$("#neft_msg_success").show();
					
					// update status -
					$("span[data-id='"+txn_id+"']").html(action);
				}
				else
				{
					//alert("error");
					
					$("#neft_msg_error").show();
					
					return false;
				}
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
<?php $this->load->view('iibfdra/admin/includes/footer');?>
