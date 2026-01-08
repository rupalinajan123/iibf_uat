<?php $this->load->view('admin/includes/header');?>
<?php 
$userRole = $this->session->userdata('roleid');

if($userRole == 1){
		?>
<?php $this->load->view('admin/includes/sidebar');?>
<?php } else {
  ?>
  <?php $this->load->view('admin/includes/garp_sidebar');?>
  <?php
} ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="min-height: 700px;">
    <!-- Content Header (Page header) -->
    <section class="content-header"> 
      <h1>
        GARP Registration Details
        
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
              <h3 class="box-title"></h3>
              
              <div class="pull-left" style="700px;">
                <form action="" method="get">
                  <div class="form-group">
                      
                      <label for="from_date" class="col-sm-2">From Date</label>
                          <div class="col-sm-3">
                              <input type="text" class="form-control" id="from_date" name="from_date" placeholder="From Date" required value="<?php echo $from_date;?>"readonly >
                              <span class="error"><?php echo form_error('from_date');?></span>
                          </div>
                      
                      <label for="to_date" class="col-sm-2">To Date</label>
                          <div class="col-sm-3">
                              <input type="text" class="form-control" id="to_date" name="end_date" placeholder="To Date" required value="<?php echo $end_date;?>" readonly >
                              <span class="error"><?php echo form_error('to_date');?></span>
                          </div>
                      <input type="submit" class="btn btn-info" name="btnSearch" id="btnSearch" value="Search" >  
                    
                  </div>
                </form>
              </div>
             
              <div class="pull-right">
              	<a href="<?php echo base_url(); ?>admin/Garp/exportToCSV?from_date=<?php echo $from_date;?>&end_date=<?php echo $end_date;?>" class="btn btn-warning" id="exportBtn">Export To CSV</a>
                
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <div class="table-responsive">
			        <table id="garplistitems" class="table table-bordered ">
                <thead>
                <tr>
                  <th id="srNo">S.No.</th>                  
                  <th id="description">Exam Name</th>
                  <th id="regnumber">Registration No</th>
                  <th id="firstname">First Name</th>
                  <th id="gender">Last Name</th>
                  <th id="exam_fee">Email<br> Address</th>
                  <th id="medium_description">Member Type</th>
                  <th id="center_name">FRR Course</th>
                  <th id="transaction_no">Ebooks</th>
                  <th id="transaction_details">Total</th>
                  <th id="date">Opt-In to Share<br> Exam Results</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                  <?php
                  $i=1;
                    foreach($result as $row) { ?>
                     <tr>
                      <td><?php echo $i++; ?></td>
                      <td>Financial Risk and Regulation<?php //echo $row['description'] ?></td>
                      <td><?php echo $row['regnumber'] ?></td>
                      <td><?php echo $row['firstname'] ?></td>
                      <td><?php echo $row['lastname'] ?></td>
                      <td><?php echo $row['email'] ?></td>
                      <td><?php echo $row['registrationtype'] ?></td>
                      <td> $300.00 </td>
                      <td> Included </td>
                      <td> $300.00 </td>
                      <td>Yes</td>
                     </tr>
                    <?php } ?>
                </tbody>
              </table>
            </div>
              <!-- <div id="links" class="" style="float:right;"><?php //echo $links; ?></div>-->
               <div id="links" class="dataTables_paginate paging_simple_numbers">
               
               </div>
               
            </div>
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
  //$('#searchExamDetails').parsley('validate');
  //$('#searchReg').parsley('validate');
</script>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script type="text/javascript">
$(document).ready(function() 
{
	$('#from_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#to_date').datepicker('setStartDate', new Date($(this).val()));
	}); 
	
	$('#to_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#from_date').datepicker('setEndDate', new Date($(this).val()));
	});
	

	
$(window).keydown(function(event){
	if(event.keyCode == 13) {
		event.preventDefault();
		return false;
	}
});
	
});

$("#garplistitems").DataTable();
</script>

 
<?php $this->load->view('admin/includes/footer');?>