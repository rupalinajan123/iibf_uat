<?php $this->load->view('iibfdra/admin/includes/header');?>
<?php $this->load->view('iibfdra/admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Quarterly Report
      </h1>
      
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
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Select Date:</h3>
              <form class="form-horizontal" name="searchDate" id="searchDate" action="" method="post">
              <div class="pull-left">
                <div class="form-group">
                  
                    <label for="from_date" class="col-sm-2">From:</label>
                     <div class="col-sm-3">
                        <input type="text" class="form-control" id="from_date" name="from_date" placeholder="From Date" required value="<?php echo set_value('from_date');?>">
                         <span class="error"><?php echo form_error('from_date');?></span>
                     </div>
                    
                  	<label for="to_date" class="col-sm-2">To:</label>
                      <div class="col-sm-3">
                          <input type="text" class="form-control" id="to_date" name="to_date" placeholder="To Date" value="<?php echo set_value('to_date');?>" required>
                           <span class="error"><?php echo form_error('to_date');?></span>
                      </div>
                      <input type="submit" class="btn btn-info" name="submit" value="Download" >
                </div>
              </div>
             
             
              
              </form>
              
            </div>
            <!-- /.box-header -->
            
           
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
	$("#listitems_filter").hide();
	
	$('#from_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true,changeMonth: true,changeYear: true}).on('changeDate', function(){
		$('#to_date').datepicker('setStartDate', new Date($(this).val()));
	}); 
	
	$('#to_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#from_date').datepicker('setEndDate', new Date($(this).val()));
	});
});

function searchOnDate()
{
	var perPage = $('#perPage').val();
	var fromDate = $("#from_date").val();
	var toDate = $("#to_date").val();
	var searcharr = [];
	
	if(fromDate == "" || toDate == "")
	{
		alert("Please Select Date!");
		return false;	
	}
	
	var base_url = '<?php echo base_url(); ?>';
	var listing_url = base_url+'iibfdra/admin/report/getBDSuccess';
	
	searcharr['field'] = 'date-BETWEEN';
	
	searcharr['value'] = fromDate+'~'+toDate;
	paginate(listing_url,searcharr,perPage);
	$("#base_url_val").val(listing_url);
	
	$(".btn_download").show();
}

</script>
 
<?php $this->load->view('iibfdra/admin/includes/footer');?>