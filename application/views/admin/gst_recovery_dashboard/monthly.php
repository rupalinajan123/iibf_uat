<?php $this->load->view('admin/gst_recovery_dashboard/includes/header');?>
<?php $this->load->view('admin/gst_recovery_dashboard/includes/sidebar');?>
<div class="content-wrapper">
  <section class="content minheight">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <form class="form-horizontal" name="searchDate" id="searchDate" action="<?php echo base_url();?>admin/GstRecovery/GstRecoveryDashboard/monthly" method="post">
            <div class="box-header">
              <div class="pull-left">
                <div class="form-group"> <br>
                  <div class="col-sm-2">
                    <input type="text" class="form-control" id="from_date" name="from_date" placeholder="From Date" required value="<?php echo set_value('from_date');?>"readonly >
                  </div>
                  <div class="col-sm-2">
                    <input type="text" class="form-control" id="to_date" name="to_date" placeholder="To Date" required value="<?php echo set_value('to_date');?>" readonly>
                  </div>
                  <div class="col-sm-2">
                    <select class="form-control" id="app_type" name="app_type">
                    	<option value="">Select App Type</option>
                        <option value="M" <?php if(set_value('app_type') == "M") { echo "selected"; } ?>>AMP (M)</option>
                        <option value="B" <?php if(set_value('app_type') == "B") { echo "selected"; } ?>>Bankquest (B)</option>
                        <option value="T" <?php if(set_value('app_type') == "T") { echo "selected"; } ?>>Blended (T)</option>
                        <option value="E" <?php if(set_value('app_type') == "E") { echo "selected"; } ?>>Contact Classes (E)</option>
                        <option value="P" <?php if(set_value('app_type') == "P") { echo "selected"; } ?>>CPD (P)</option>
                        <!--<option value="A">DRA Accredited Module</option>-->
                        <option value="C" <?php if(set_value('app_type') == "C") { echo "selected"; } ?>>Duplicate Certificate (C)</option>
                        <option value="D" <?php if(set_value('app_type') == "D") { echo "selected"; } ?>>Duplicate Id Card (D)</option>
                        <option value="K" <?php if(set_value('app_type') == "K") { echo "selected"; } ?>>Exam Recovery (K)</option>
                        <option value="F" <?php if(set_value('app_type') == "F") { echo "selected"; } ?>>Finquest (F)</option>
                        <option value="R" <?php if(set_value('app_type') == "R") { echo "selected"; } ?>>New Member (R)</option>
                        <option value="N" <?php if(set_value('app_type') == "N") { echo "selected"; } ?>>Renewal (N)</option>
                        <option value="V" <?php if(set_value('app_type') == "V") { echo "selected"; } ?>>Vision (V)</option>
                        <option value="H" <?php if(set_value('app_type') == "H") { echo "selected"; } ?>>DRA Centers (H)</option>
                        <option value="W" <?php if(set_value('app_type') == "W") { echo "selected"; } ?>>DRA Renewal (W)</option>
                        <option value="O" <?php if(set_value('app_type') == "O") { echo "selected"; } ?>>Exam (O)</option>
                        <option value="EL" <?php if(set_value('app_type') == "EL") { echo "selected"; } ?>>Separate Elearning (EL)</option>
                        <option value="CN" >Credit Note</option>
					</select>
                    
                  </div>
                  <div class="col-sm-2">
                    <select class="form-control" id="pay_type" name="pay_type">
                    	<option value="">Select Pay Type</option>
                        <option value="0" <?php if(set_value('pay_type') == "0") { echo "selected"; } ?>>AMP </option>
                        <option value="6" <?php if(set_value('pay_type') == "6") { echo "selected"; } ?>>Bankquest (6)</option>
                        <option value="10" <?php if(set_value('pay_type') == "10") { echo "selected"; } ?>>Blended (10)</option>
                        <option value="11" <?php if(set_value('pay_type') == "11") { echo "selected"; } ?>>Contact Classes (11)</option>
                        <option value="9" <?php if(set_value('pay_type') == "9") { echo "selected"; } ?>>CPD (9)</option>
                        <!--<option value="12">DRA Accredited Module</option>-->
                        <option value="4" <?php if(set_value('pay_type') == "4") { echo "selected"; } ?>>Duplicate Certificate (4)</option>
                        <option value="3" <?php if(set_value('pay_type') == "3") { echo "selected"; } ?>>Duplicate Id Card (3)</option>
                        <option value="14" <?php if(set_value('pay_type') == "14") { echo "selected"; } ?>>Exam Recovery (14)</option>
                        <option value="8" <?php if(set_value('pay_type') == "8") { echo "selected"; } ?>>Finquest (8)</option>
                        <option value="1" <?php if(set_value('pay_type') == "1") { echo "selected"; } ?>>New Member (1)</option>
                        <option value="5" <?php if(set_value('pay_type') == "5") { echo "selected"; } ?>>Renewal (5)</option>
                        <option value="7" <?php if(set_value('pay_type') == "7") { echo "selected"; } ?>>Vision (7)</option>
                        <option value="16" <?php if(set_value('pay_type') == "16") { echo "selected"; } ?>>DRA Centers (16)</option>
                        <option value="17" <?php if(set_value('pay_type') == "17") { echo "selected"; } ?>>DRA Renewal (17)</option>
                        <option value="2" <?php if(set_value('pay_type') == "2") { echo "selected"; } ?>>Exam (2)</option>
                        <option value="20" <?php if(set_value('pay_type') == "20") { echo "selected"; } ?>>Separate Elearning (20)</option>
                        <option value="21" >Credit Note</option>
                    </select>
                  </div>
                  <div class="col-sm-2">
                    <input type="submit" class="btn btn-info" name="btnSearch" id="btnSearch" value="Search">
                  </div>
                </div>
              </div>
              <div class="pull-right">
                <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
                <input type="hidden" name="base_url_val" id="base_url_val" value="" />
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Monthly Counts Details</h3>
          </div>
          <div class="box-body">
            <?php if($invoice_count > 0 ) { ?>
            <div>&nbsp;</div>
            <div><strong> Invoice Count:&nbsp;</strong><?php echo $invoice_count; ?>&nbsp;|&nbsp;<strong> Payment Count:&nbsp;</strong><?php echo $payment_count; ?></div>
            <div>&nbsp;</div>
            
            <div><?php if($invoice_count == $payment_count){ echo "<strong><span style='color:green;'>Count Matched</span></strong>";} else {echo "<strong><span style='color:red;'>Count Not Matched</span></strong>";}?></div>
            
            <div>&nbsp;</div>
            <div><a href="<?php echo base_url();?>admin/GstRecovery/GstRecoveryDashboard/monthly_download_CSV/<?php echo $app_type; ?>/<?php echo $from_date; ?>/<?php echo $to_date;?> ">
              <button type="button" class="btn btn-warning" >Get Invoice No. CSV</button>
              </a></div>
            <div>&nbsp;</div>
            <?php 
              }else if($credit_count > 0)
			  { ?>
				<div>&nbsp;</div>
				<div><strong> Credit Note Count:&nbsp;</strong><?php echo $credit_count; ?></div>
				<div>&nbsp;</div>
				
				<div>&nbsp;</div>
				<div><a href="<?php echo base_url();?>admin/GstRecovery/GstRecoveryDashboard/monthly_download_CSV/<?php echo $app_type; ?>/<?php echo $from_date; ?>/<?php echo $to_date;?> ">
				  <button type="button" class="btn btn-warning" >Get Invoice No. CSV</button>
				  </a></div>
				<div>&nbsp;</div>
				<?php 
				  
			  }
              ?>
              
              
              

              
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
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
<script src="<?php echo base_url()?>js/validation.js?<?php echo time(); ?>"></script> 
<script type="text/javascript">
  $('#search').parsley('validate');
</script> 
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script> 
<script type="text/javascript">
  $('#searchDate').parsley('validate');
</script> 
<script src="<?php echo base_url()?>js/js-paginate.js"></script> 
<script>
$(document).ready(function() 
{
	$('#from_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#to_date').datepicker('setStartDate', new Date($(this).val()));
	}); 
	
	$('#to_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#from_date').datepicker('setEndDate', new Date($(this).val()));
	});
});


function searchOnDate()
{
	var fromDate = $("#from_date").val();
	var toDate = $("#to_date").val();
	if(fromDate=='' && toDate=='')
	{
		alert('Please select atleast one date');	
	}
	else if(fromDate=='' && toDate!='')
	{
		alert('Please select From Date');
	}
	else
	{
		var perPage = $('#perPage').val();
		var searcharr = [];
		searcharr['field'] = 'date-BETWEEN';
		//'exam_code,description,qualifying_exam1,qualifying_part1,qualifying_exam2,qualifying_part2,qualifying_exam3,qualifying_part2,exam_type';
		searcharr['value'] = fromDate+'~'+toDate;
		paginate('',searcharr,perPage);
	}
}
$(function () {
	$("#listitems").DataTable();
	var base_url = '<?php echo base_url(); ?>';
	var listing_url = base_url+'admin/Report/getSuccessBDList';
	
	$(".DTTT_button_print, .DTTT_button_copy, .DTTT_button_csv, .DTTT_button_xls, .DTTT_button_pdf ").hide();
	$("#listitems_filter").hide();
	
	// Pagination function call
	//paginate(listing_url,'','','');
	$("#base_url_val").val(listing_url);
});
		
</script> 
</script>
<?php $this->load->view('admin/gst_recovery_dashboard/includes/footer');?>
