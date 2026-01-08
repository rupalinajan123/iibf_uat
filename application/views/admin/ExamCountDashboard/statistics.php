<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
     <br />
       <h1><i class="fa fa-calendar"></i> Member Registration Statistics </h1>
      <br />
    </section>
	<div class="col-md-12">
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
    </div>
    <!-- Main content -->
    <section class="content minheight">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
          <form class="form-horizontal" name="searchDate" id="searchDate" action="<?php echo base_url();?>admin/CountController" method="post">
            <div class="box-header">
              <h3 class="box-title"></h3>
              <div class="pull-left">
                <div class="form-group">
                  <br />
                    <!--
                    <label for="from_date" class="col-sm-2">From Date</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="from_date" name="from_date" placeholder="From Date" required value="<?php //echo set_value('from_date');?>"readonly >
                             <span class="error"><?php //echo form_error('from_date');?></span>
                        </div>
                        -->
                  	<label for="to_date" class="col-sm-3">Date</label>
                         <div class="col-sm-6">
                            <input type="text" class="form-control" id="to_date" name="to_date" placeholder="Select Date" required value="<?php echo set_value('to_date');?>" readonly >
                             <span class="error"><?php echo form_error('to_date');?></span>
                        </div>
                    <input type="submit" class="btn btn-info" name="btnSearch" id="btnSearch" value="Search" onclick="">  
                </div>
              </div>
             
              <div class="pull-right">
                <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
                <input type="hidden" name="base_url_val" id="base_url_val" value="" /> 
              </div>
            </div>
            </form>
            <!-- /.box-header -->
            <div class="box-body" style="display: block;">
              <div class="table-responsive">
                  <table class="table table-bordered">
                   <?php
					if($flag == "true"){
						$TotalCount = $total_count_NM + $total_count_O + $total_count_DB;
						//  + $total_count_A + $total_count_F
						if($TotalCount != ""){	
							?>
							<tr>
							  <td width="50%"><strong>Member Type</strong></td>
							  <td width="50%" class="text-center"><strong>No. Of Registrations</strong></td> 
							</tr>
							<tr>
							  <td>NM</td>
							  <td class="text-center"><?php echo $total_count_NM; ?></td>
							 </tr>
							 <tr>
							  <td>O</td>
							  <td class="text-center"><?php echo $total_count_O; ?></td>
							 </tr>
							 <tr>
							 <td>DB</td>
							  <td class="text-center"><?php echo $total_count_DB; ?></td>
							 </tr>
							 <!--<tr>
							  <td>A</td>
							  <td class="text-center"><?php //echo $total_count_A; ?></td>
							 </tr>
							 <tr>
							  <td>F</td>
							  <td class="text-center"><?php //echo $total_count_F; ?></td>
							</tr> -->
						   <tr>
							  <td><strong>Total Registrations</strong></td>
							  <td class="text-center"><strong><?php echo $TotalCount; ?></strong></td>
							</tr>
					   <?php 
						}
						else{
							echo "<div  style='color:red;font-weight:bold;text-align:center;'>No Record Found..! </div>";
						}
				   } 
				   ?>
                  </table>
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
 
<?php $this->load->view('admin/includes/footer');?>