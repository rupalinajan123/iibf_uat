<?php $this->load->view('admin/trainingbatch/includes/header');?>
<?php $this->load->view('admin/trainingbatch/includes/sidebar');?>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper"> 
  <!-- Content Header (Page header) --> 
  <!--<section class="content-header">
    <h1> Blended Course Registrations List </h1>
  </section>--> 
  <br />
  <div class="col-md-12 msg">
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
            <h3 class="box-title">Blended Course Registrations List</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="listitems" class="table table-bordered table-striped dataTables-example">
			
              <thead>
                <tr>
                  <th id="" nowrap="nowrap">Sr</th>
                  <th id="10%" nowrap="nowrap">Date</th>
                  <th id="10%" nowrap="nowrap">Member No.</th>
                  <th id="10%" nowrap="nowrap">Attempt</th>
                  <th id="5%" nowrap="nowrap">Program Code</th>
                  <th id="5%" nowrap="nowrap">Batch Code</th>
                  <th id="5%" nowrap="nowrap">Zone Code</th>
                  <th id="5%" nowrap="nowrap">Traning Type</th>
                  <th id="5%" nowrap="nowrap">Center Code</th>
                  <th id="20%" nowrap="nowrap">Course Date</th>
                  <th id="5%" nowrap="nowrap">Invoice</th>
				  <th id="5%" nowrap="nowrap">Send Mail</th>
                </tr>
              </thead>
              <?php 
			   $row_count=1;
			if(count($mem_info)){

						foreach($mem_info as $row)
						{?> 
              <tr>
                <td align="center"><?php echo $row_count;?></td>
                <td align="center"><?php echo $row['createdon'];?></td>
                <td align="center"><?php echo $row['member_no'];?></td>
                <td align="center"><?php echo $row['attempt'];?></td>
                <td align="center"><?php echo $row['program_code'];?></td>
                <td align="center"><?php echo $row['batch_code'];?></td>
                <td align="center"><?php echo $row['zone_code'];?></td>
                <td align="center"><?php echo $row['training_type'];?></td>
                <td align="center"><?php echo $row['center_code'];?></td>
                <td align="center"><?php echo $row['start_date'].' - '.$row['end_date'];?></td>
                <td align="center">
                <a href="<?php echo base_url();?>uploads/blended_invoice/user/<?php echo $row['zone_code'];?>/<?php echo $row['invoice_image'];?>" target="_blank">View</a></td>
				<td align="center"> 
				<a class = "btn btn-info" id = "sendmail" onclick = "send_mail('<?php echo $row['batch_code'];?>','<?php echo $row['member_no'];?>');">Send Mail</a>
				  </td>
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
<script>		
$(document).ready(function(){ 
 

  });
  
   function send_mail(batch_code,member_no)
  {
  	//alert(batch_code);

    $.ajax({
                url : '<?php echo base_url(); ?>admin/trainingbatch/TrainingDashboard/send_mail',
                type : "POST",
                dataType: 'JSON',
                data : {batch_code:batch_code,member_no:member_no},
              //alert(batch_code);
                  success:function(data)
                  { 
                    $('#sendmail').show();   
                    if(data.status == 'success')
                    {   

                          
                        $('.msg').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+data.userMsg+'</div>');                 
                    }
                    else
                    {
                       $('.msg').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+data.userMsg+'</div>');                                                    
                    }
                  },
                 error:function(data){
                     
                      $("#sendmail").html('Load More');
                  }
      });


  }
		
</script>

<?php $this->load->view('admin/trainingbatch/includes/footer');?>
