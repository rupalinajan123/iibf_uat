<?php $this->load->view('admin/blended_dashboard/includes/header');?>
<?php $this->load->view('admin/blended_dashboard/includes/sidebar');?>
<style>
  .webgrid-table-hidden
  {
      display: none;
  }
</style>
<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper"> 
  <!-- Content Header (Page header) --> 
  <!--<section class="content-header">
    <h1> Blended Course Registrations List </h1>
  </section>--> 
  <br />
  <div class="col-md-12">
    <?php if($this->session->flashdata('error')!=''){?>
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 
      <?php echo $this->session->flashdata('error'); ?> </div>
    <?php } if($this->session->flashdata('success')!=''){ ?>
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>--> 
      <?php echo $this->session->flashdata('success'); ?> </div>
    <?php } ?>
  </div>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <div class="col-md-12">
              <h3 class="box-title">Search Filters</h3>
            </div>
          </div>
          <div class="box-body">
            <form class="form-horizontal" name="btnSearch" id="btnSearch" action="<?php echo base_url();?>admin/ippb/IppbDashboard"  method="post">
              
              <!-- <div class="col-sm-2">
                <input type="text" class="form-control" id="regnumber" name="regnumber" placeholder="Member registration number"/>
              </div> -->
              <div class="col-sm-2">
                <input type="text" class="form-control" id="email" name="email" placeholder="Email Id"/>
              </div>
              <div class="col-sm-2">
                <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile Number"/>
              </div>
              <div class="col-sm-2">
                <input type="text" class="form-control" id="emp_id" name="emp_id" placeholder="Employee Id"/>
              </div>
              <input type="submit" class="btn btn-info" name="btnSearch" id="btnSearch" value="Search">
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">IPPB Member Eligible List</h3>
              <div class="pull-right">
              	<a href="<?php echo base_url();?>admin/ippb/IppbDashboard/add" class="btn btn-warning">Add</a>
                <a href="<?php echo base_url();?>admin/ippb/IppbDashboard/download" class="btn  btn-primary">Download</a>
              </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="listitems" class="table table-bordered table-striped dataTables-example">
              <thead>
                <tr>
                  <th id="" nowrap="nowrap">Sr</th>
                  <!-- <th id="10%" nowrap="nowrap">Member No.</th> -->
                  <th id="10%" nowrap="nowrap">Firstname</th>
                  <th id="5%" nowrap="nowrap">Email</th>
                  <th id="5%" nowrap="nowrap">Mobile</th>
                  <th id="5%" nowrap="nowrap">Employee Id</th>
                  <th id="5%" nowrap="nowrap">Branch</th>
                  <th id="5%" nowrap="nowrap">Circle</th>
                  <th id="20%" nowrap="nowrap">created Date</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                  $row_count=1;
                  if(count($mem_info)){

                  foreach($mem_info as $row)
                  {?> 
                    <tr>
                      <td align="center"><?php echo $row_count;?></td>
                      <!-- <td align="center"><?php //echo $row['regnumber'];?></td> -->
                      <td align="center"><?php echo $row['firstname'];?></td>
                      <td align="center"><?php echo $row['email'];?></td>
                      <td align="center"><?php echo $row['mobile'];?></td>
                      <td align="center"><?php echo $row['emp_id'];?></td>
                      <td align="center"><?php echo $row['branch'];?></td>
                      <td align="center"><?php echo $row['circle'];?></td>
                      <td align="center"><?php echo $row['createdon'];?></td>
                    </tr>
                <?php $row_count++; } } ?>
              </tbody>
              
            </table>

          
          </div>
        </div>
        <!-- /.box-body --> 
      </div>
      <!-- /.box --> 

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
<script src="<?php echo base_url()?>js/validation.js?<?php echo time(); ?>"></script> 
<script type="text/javascript">
  $('#search').parsley('validate');
</script> 

<!--<script src="<?php echo base_url()?>js/js-paginate.js"></script>--> 
<script type="application/javascript">
$(document).ready(function() 
{
/*	$('#from_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#to_date').datepicker('setStartDate', new Date($(this).val()));
	}); 
	
	$('#to_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#from_date').datepicker('setEndDate', new Date($(this).val()));
	});*/
	
	/*$(".chk").on('click', function(e){
		alert('in');
		
			var status = this.checked; // "select all" checked status
			alert(status);
			$('.chk').each(function(){ //iterate all listed checkbox items
				this.checked = status; //change ".checkbox" checked status
			});
		
	})*/
});

$(function () {
	$("#listitems").DataTable();
	/*var base_url = '<?php // echo base_url(); ?>';
	var listing_url = base_url+'admin/kyc/Kyc/recommended_list/';
	
	// Pagination function call
	paginate(listing_url,'','','');
	$("#base_url_val").val(listing_url);*/
});


		
</script>
<?php $this->load->view('admin/blended_dashboard/includes/footer');?>
