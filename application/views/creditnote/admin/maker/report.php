<?php $this->load->view('creditnote/admin/includes/header');?>
<?php $this->load->view('creditnote/admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Refund Request List
      </h1>
      <?php echo $breadcrumb; ?>
    </section>
	<div class="col-md-12">
    <br />    
    <?php 
	 if($this->session->flashdata('success')!=''){ ?>
              <div class="alert alert-success alert-dismissible" id="success_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('success'); ?> </div>
     <?php }?>    
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
                  
                    <label for="from_date" class="col-sm-1">From:</label>
                     <div class="col-sm-3">
                        <input type="text" class="form-control" id="from_date" name="from_date" placeholder="From Date" required value="<?php echo set_value('from_date');?>">
                         <span class="error"><?php echo form_error('from_date');?></span>
                     </div>
                    
                    <label for="to_date" class="col-sm-1">To:</label>
                      <div class="col-sm-3">
                          <input type="text" class="form-control" id="to_date" name="to_date" placeholder="To Date" value="<?php echo set_value('to_date');?>">
                           <span class="error"><?php echo form_error('to_date');?></span>
                      </div>

                      <div class="col-sm-3">
                    <input type="submit" class="btn btn-info" name="submit" id="btnSearch" value="Search">
                   

                    <a href="<?php echo base_url();?>creditnote/admin/maker/report" class="btn btn-default pull-right"  name="btnReset" id="btnReset" >Reset</a>

                    </div>
                </div>
              </div>
             
              <div class="pull-right">
                <?php 
                  if ($from_date!='' && $to_date!='') { 
                ?>
                <input type="submit" class="btn btn-warning btn_download" name="export" value="Export" style="display:">
                <?php }?>
              </div>
              
              </form>
              
            </div>         	
            <div class="box-body">
            
            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
        	<input type="hidden" name="base_url_val" id="base_url_val" value="" />
            <div class="table-responsive">
			<table id="listitems2" class="table table-bordered table-striped dataTables-example">
                <thead>
                <tr>
                  <th id="srNo">Sr.No.</th>
                  <th id="id">Request Id</th>     
                  <th id="title">Request Title</th>                     
                  <th id="member_no">Member No.</th> 
                  <th id="module_mame">Module Name</th>
                  <th id="transaction_no">Transaction No.</th>
                  <!-- <th id="inst_head_name">Maker Name</th> -->
                  <th>Status</th> 
                  <th id="action">Exceptional Case (Yes/No)</th>
                </tr>
                </thead>
                  
                <tbody class="no-bd-y" id="list2">
                        <?php 
        $k = 1;
        if(count($reuest_list) > 0){
          foreach($reuest_list as $res){
          echo '<tr><td>'.$k.' </td>';
          echo '<td>'.$res['req_id'].' </td>';
          echo '<td>'.$res['req_title'].' </td>';
          echo '<td>'.$res['req_member_no'].' </td>';
          echo '<td>'.$res['module_name'].' </td>';         
          echo '<td>'.$res['transaction_no'].' </td>';
          
          if($res['req_status'] == 1){
          $reuest_status = '<span class="reuest_status" style="color: green">APPROVED</span>' ;
          }elseif($res['req_status'] == 2){
          $reuest_status = '<span class="reuest_status" style="color: red">REJECTED</span>';  
          }elseif($res['req_status'] == 3){
          $reuest_status = '<span class="reuest_status" >DROP</span>';  
          }elseif($res['req_status'] == 0){
          $reuest_status = '<span class="reuest_status" style="color: blue">PENDING</span>';  
          }elseif($res['req_status'] == 6){
          $reuest_status = '<span class="reuest_status" style="color: #da8be8">RESUBMITED</span>'; 
          }elseif($res['req_status'] == 4){
          $reuest_status = '<span class="reuest_status" style="color: #ff8d00">CANCELLED</span>'; 
          }elseif($res['req_status'] == 5){
          $reuest_status = '<span class="reuest_status" style="color: #089ac5">REFUND</span>'; 
          }
          echo '<td>'.$reuest_status.' </td>';
          echo '<td>'.$res['req_exceptional_case'].' </td>';
          
          // echo '|<a class="btn btn-primary btn-xs vbtn" href="'.base_url().'creditnote/admin/maker/request_approve'.$res['id'].'">Approve</a>';
          
          echo '</tr>';
          $k++; 
          }
        }
        ?>       
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

    </section>   
  </div>
  
<!-- Data Tables -->
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<style>
.active_batch{
color:#00a65a;	
font-weight:600;
}

.deactive_batch{
color:#930;	
font-weight:600;
}
.input_search_data{
 width:100%;	
}
tfoot {
    display: table-header-group;
}
.vbtn{
padding: 3px 4px;
font-weight: 600;
}
</style>

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

<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script>
$(function () {
	$('#listitems2').DataTable();
	$("#listitems_filter").show();

  $('#from_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true,changeMonth: true,changeYear: true}).on('changeDate', function(){
    $('#to_date').datepicker('setStartDate', new Date($(this).val()));
  }); 
  
  $('#to_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
    $('#from_date').datepicker('setEndDate', new Date($(this).val()));
  });

  $("#login").on("submit", function(e) {
    //e.preventDefault(); // Stop submitting form
    $(".btn_download").show();
});
});




</script>
 
<?php $this->load->view('creditnote/admin/includes/footer');?>