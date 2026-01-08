<?php $this->load->view('admin/blended_dashboard/includes/header');?>
<?php $this->load->view('admin/blended_dashboard/includes/sidebar');?>

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
            <h3 class="box-title">Blended Course Count</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="listitems" class="table table-bordered table-striped dataTables-example">
              <thead>
                <tr>
        
                  <th id="2%" nowrap="nowrap" align="center">Sr</th>
                  <th id="10%" nowrap="nowrap" align="center">Batch</th>
                  <th id="10%" nowrap="nowrap" align="center">Zone Code</th>
                  <th id="8%" nowrap="nowrap" align="center">Program Code</th>
                  <th id="5%" nowrap="nowrap" align="center">Type</th>
                  <th id="10%" nowrap="nowrap" align="center">Counts</th>
                  <th id="7%" nowrap="nowrap" align="center">Capacity</th>
                  <th id="10%" nowrap="nowrap" align="center">Center Name</th>
                  <th id="25%" nowrap="nowrap" align="center">Course Date</th>
                    <th id="20%" nowrap="nowrap" align="center">Status</th>
                   <th id="10%" nowrap="nowrap" align="center">Last date </th>
                  <th id="5%" nowrap="nowrap" align="center">CSV</th>
              
                </tr>
              </thead>
              <?php 
			   $row_count=1;
		
			
			if(count($counts)){
				
						foreach($counts as $row)
						{
						 $this->db->where('batch_code',$row['batch_code']);
						$batch_info = $this->master_model->getRecords('blended_program_activation_master','','program_reg_to_date');
						$end_date =  date('Y-m-d', strtotime($batch_info[0]['program_reg_to_date']));
						
							   if(!empty($traninginfo)){
								 foreach($traninginfo as $val)
					        	{
	 								if($val['batch_code']==$row['batch_code'])
									{
										if($end_date >= date('Y-m-d'))
										{
											$status ='Active'; 
										}
										else
										{
											$status ='Deactive';
											 if($val['program_activation_delete']==0)
											 {
											 	
													$batch_code=$row['batch_code'];
												
												
												//Offline mail table update 
												  $this->master_model->updateRecord('offline_email_master', array('isdelete' =>1) , array('batch_code' =>$batch_code, 'isdelete' =>0));
												  
												 //blended_dates
												   $this->master_model->updateRecord('blended_dates', array('isdelete' =>1) , array('batch_code' =>$batch_code, 'isdelete' =>0));
												   
												  //blended_fee_master
												  $this->master_model->updateRecord('blended_fee_master', array('fee_delete' =>1) , array('batch_code' =>$batch_code, 'fee_delete' =>0));
											
												//blended_program_activation_master
												 $this->master_model->updateRecord('blended_program_activation_master', array('program_activation_delete' =>1) , array('batch_code' =>$batch_code, 'program_activation_delete' =>0));
												 
												 //blended_venue_master
												 $this->master_model->updateRecord('blended_venue_master', array('isdeleted' =>1) , array('batch_code' =>$batch_code, 'isdeleted' =>0));

											}
										}
/*										   if($val['program_activation_delete']==0)
										   { 
											  $status ='Active'; 
										   }else
										   {
												$status ='Deactive';
										 }
*/							   }
								}
							   }?> 
                    
                <tr batch_code="<?php echo $row['batch_code'];?>">
                <td align="center"><?php echo $row_count;?></td>
                <td align="center"><?php echo $row['batch_code'];?></td>
                <td align="center"><?php echo $row['zone_code'];?></td>
                <td align="center"><?php echo $row['program_code'];?></td>
                <td align="center"><?php echo $row['training_type'];?></td>
                <td align="center"><?php echo $row['Counts'];?></td>
                
                <td align="center"><?php $this->db->where('batch_code',$row['batch_code']);
				$capacity = $this->master_model->getRecords('blended_venue_master','','capacity');
				echo $capacity[0]['capacity'];
				?></td>
                <td align="center"><?php echo $row['center_name'];?></td>
                <td align="center"><?php echo $row['start_date'].' - '.$row['end_date'];?></td>
                <?php  if($status=='Active')
				{?>
                <td align="center" style="color:#0C0">
                <button class="btn btn-success btn-sm remove"><?php echo $status;?></button>
                
                 <!-- <a onClick=\"javascript: return confirm('Please confirm deletion');\" href="<?php echo base_url();?>admin/blended/BlendedDashboard/Deactive_batch/<?php //echo $row['batch_code'];?>/<?php //echo $row['training_type'];?>"  style="color:#0C0"><?php //echo $status;?></a>-->
                 
				</td>
                 <?php }else
				 {
				 ?> <td align="center" style="color:#F00"><?php echo $status;?></td>
                 <?php }?>
                     <td align="center"><?php echo $end_date;?></td>
                 
                  <td align="center">    <a href="<?php echo base_url();?>admin/blended/BlendedDashboard/download_CSV/<?php echo $row['batch_code']; ?>/<?php echo $row['training_type']; ?>/<?php echo $row['center_name'];?> ">

                 <center><button type="button" class="btn btn-warning pull-right"  >CSV</button>  </center><br />
              
       </a></td>
       
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
	$("#listitems").DataTable(
	{
	"aaSorting": [[ 9, "asc" ]] });
	
	/*var base_url = '<?php // echo base_url(); ?>';
	var listing_url = base_url+'admin/kyc/Kyc/recommended_list/';
	
	// Pagination function call
	paginate(listing_url,'','','');
	$("#base_url_val").val(listing_url);*/
});
		
var site_url = 'https://'+window.location.hostname+'/';  
    $(".remove").click(function(){

        var batch_code = $(this).parents("tr").attr("batch_code");


        if(confirm('Are you sure to deactive this batch ?'))

        {

            $.ajax({

			url:site_url+'admin/blended/BlendedDashboard/Deactive_batch/',
         

               type: 'GET',

               data: {batch_code: batch_code},

               error: function() {

                  alert('Something is wrong');
				  

               },

               success: function(data) {

                    $("#"+batch_code).remove();

                    alert("Batch has been deactive successfully");  
					window.location = site_url+'admin/blended/BlendedDashboard/counts';
               }

            });

        }

    });

		
</script>
<?php $this->load->view('admin/blended_dashboard/includes/footer');?>
