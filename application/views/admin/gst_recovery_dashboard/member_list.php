<?php $this->load->view('admin/gst_recovery_dashboard/includes/header');?>
<?php $this->load->view('admin/gst_recovery_dashboard/includes/sidebar');?>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper"> 
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
            <h3 class="box-title">GST Recovery Registrations List</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="listitems" class="table table-bordered table-striped dataTables-example">
              <thead>
                <tr>
                  <th id="">Sr.No.</th>
                  <th id="10%">Member No</th>
                  <th id="25%">Name</th>
                  <th id="20%">Service</th>
                  <th id="10%">Amount</th>
                  <th id="10%">Doc No</th>
                  <th id="10%">Date of Doc </th>
                  <th id="10%">Doc Image</th>
                </tr>
              </thead>
              <?php 
			   $row_count=1;
			if(count($mem_info)){

						foreach($mem_info as $row)
						{
							$payStatusArr = array('1'=>'Membership Registration','2'=>'Exam','3'=>'Duplicate ID Card','4'=>'Duplicate Certificate','5'=>'Membership Renewal');
							
							?>
              <tr>
                <td><?php echo $row_count;?></td>
                <td><?php echo $row['member_no'];?></td>
                <td><?php echo $row['firstname'].' '.$row['middlename'].' '.$row['lastname']; ?></td>
                <td><?php 
					if($row['pay_type'] == 2){
						echo $payStatusArr[$row['pay_type']].' ('.$row['exam_code'].'-'.$row['description'].')';
					}else{
						echo $payStatusArr[$row['pay_type']]; 
					}?></td>
                <td><span>&#8377;</span>&nbsp;<?php echo $row['igst_amt'];?></td>
                <td><?php echo $row['doc_no'];?></td>
                <td><?php echo $row['date_of_doc'];?></td>
                <td><a href="<?php echo base_url();?>uploads/gst_recovery_invoice/user/<?php echo $row['doc_image'];?>" target="_blank">View Doc</a></td>
              </tr>
              <?php $row_count++; }} ?>
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
<?php $this->load->view('admin/gst_recovery_dashboard/includes/footer');?>
