<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Failure Registrations
        
      </h1>
      <?php echo $breadcrumb; ?>
    </section>
    <br />
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
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              
              <div class="">
              <form class="form-horizontal" name="searchDate" id="searchDate" action="" method="post">
              	<h3 class="box-title">Select Date:</h3>
                <div class="form-group">
                  
                    <label for="from_date" class="col-sm-1">From:</label>
                         <div class="col-sm-2">
                            <input type="text" class="form-control" id="from_date" name="from_date" placeholder="From Date" required value="<?php echo set_value('from_date');?>" readonly>
                             <span class="error"><?php echo form_error('from_date');?></span>
                        </div>
                    
                  	<label for="to_date" class="col-sm-1">To:</label>
                         <div class="col-sm-2">
                            <input type="text" class="form-control" id="to_date" name="to_date" placeholder="To Date" required value="<?php echo set_value('to_date');?>" readonly>
                             <span class="error"><?php echo form_error('to_date');?></span>
                             
                         </div>
                    
                    
                    <div class="col-sm-1">
                     	<input type="button" class="btn btn-info" name="btnSearch" id="btnSearch" value="Search" onclick="return searchOnDate();"> 
                    </div>
                    <div class="col-sm-4">
                     	<input type="button" class="btn btn-info" onclick="refreshDiv('');" value="Refresh">
                        <input type="submit" class="btn btn-warning" name="download" value="Download">
                        <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
                        <input type="hidden" name="base_url_val" id="base_url_val" value="" />
                	</div>
                </div>
               </form>
               
               <form class="form-horizontal" name="searchReg" id="searchReg" action="" method="post">
               <div class="form-group">
                        
                    <label for="to_date" class="col-sm-1">Reg. No.</label>
                     <div class="col-sm-2">
                        <input type="text" class="form-control" id="regnumber" name="regnumber" placeholder="Registration No." required value="<?php echo set_value('regnumber');?>" >
                         <span class="error"><?php echo form_error('regnumber');?></span>
                    </div>
                    
                   <input type="button" class="btn btn-info" name="btnSearch" id="btnSearch" value="Search" onclick="return searchOnRegNo();"> 
               </div>
               </form> 
                
                
              </div>
             
             <!-- <div class="pull-right">
              <form class="form-horizontal" name="searchFrm" id="searchFrm" action="" method="post">
              </form>--> 
              
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            	
			<table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
                <tr>
                  <!--<th id="select">
                  	<input type="checkbox" name="check_list_all[]" id="select_all" value="1" class="chk" >
                  </th>
                 -->
                  <th id="srNo">S.No.</th>
                  <th id="firstname">Candidate Name</th>
                  <th id="regnumber">Membership No</th>
                  <th id="transaction_no">Transaction No</th>
                  <th id="amount">Amount</th>
                  <th id="createdon">Date</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                                    
                </tbody>
              </table>
              <!-- <div id="links" class="" style="float:right;"><?php //echo $links; ?></div>-->
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
  //$('#searchDate').parsley('validate');
  //$('#searchReg').parsley('validate');
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
	
	/*$(".chk").on('click', function(e){
		alert('in');
		
			var status = this.checked; // "select all" checked status
			alert(status);
			$('.chk').each(function(){ //iterate all listed checkbox items
				this.checked = status; //change ".checkbox" checked status
			});
		
	})*/
});

function searchOnRegNo()
{
	var flag=$('#searchReg').parsley().validate();
	if(flag)
	{
		var perPage = $('#perPage').val();
		var searchkey = $("#regnumber").val();
		var searcharr = [];
		searcharr['field'] = 'regnumber';
		searcharr['value'] = searchkey;
		paginate('',searcharr,perPage);
	}
}

function searchOnDate()
{
	var flag=$('#searchDate').parsley().validate();
	if(flag)
	{
		var perPage = $('#perPage').val();
		var fromDate = $("#from_date").val();
		var toDate = $("#to_date").val();
		var searcharr = [];
		searcharr['field'] = 'createdon-BETWEEN';
		//'exam_code,description,qualifying_exam1,qualifying_part1,qualifying_exam2,qualifying_part2,qualifying_exam3,qualifying_part2,exam_type';
		searcharr['value'] = fromDate+'~'+toDate;
		paginate('',searcharr,perPage);
	}
}

function confirmMailSend()
{
	if(confirm("Do you want to re-send registration mail?"))
	{
		return true;	
	}
	else
	{
		return false;
	}
		
}


$(function () {
	$("#listitems").DataTable();
	var base_url = '<?php echo base_url(); ?>';
	var listing_url = base_url+'admin/Report/getRegFailure';
	
	$(".DTTT_button_print, .DTTT_button_copy, .DTTT_button_csv, .DTTT_button_xls, .DTTT_button_pdf ").hide();
	$("#listitems_filter").hide();
	
	// Pagination function call
	paginate(listing_url,'','','');
	$("#base_url_val").val(listing_url);
});
		
</script>
 
<?php $this->load->view('admin/includes/footer');?>