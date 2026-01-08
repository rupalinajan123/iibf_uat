<?php $this->load->view('admin/kyc/includes/header');?>
<?php $this->load->view('admin/kyc/includes/sidebar');?>
<!--fancybox-->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Edited member list 
     </h1>
     
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
        	      <h3 class="box-title">  Allocated  records  </h3>
            </div>
            <!-- /.box-header -->
         	  <div class="box-body">
    	    <?php
		//echo '<pre>';
		//print_r($result);
					   if(count($result)>0)
				   {?>
			<table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
           		     <tr>
                        <th id="">No</th> 
                        <th id="">Membership/Registration No</th>
                        <th id="">Candidate Name</th>
                        <th id="">D.O.B</th>
                        <th id="">Email</th>
                        <th id="">Registration type</th>
						<th id="">Registered date</th>
                        <th id="">Action</th>
                     </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                  <?php 
			  	/* Here array key to start from 1 instead of 0 for showing counts functionality */
				$result = array_combine(range(1, count($result)), array_values($result));
				//$totalRecCount = count($result);
				
				$original_allotted_Arr = explode(',', $original_allotted_member_id);
				$arr = array_slice($original_allotted_Arr, -$totalRecCount);
				$Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
				$reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
				//echo "<pre>"; print_r($reversedArr_list); echo "</pre>";
				  
				  if(count($result))
				  {
					  		  $row_count = 1;
					 	  	foreach($result as $rKey => $row)
							{  
								?>
								<tr>
                                    <td><?php echo $row_count;?></td>
                                    <td><?php echo $row['regnumber'];?></td>
                                      <td>
											  <?php  
                                         $username=$row['namesub'].' '.$row['firstname'].' '.$row['middlename'].' '.$row['lastname'];
                                        echo  $userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
                                         ?>
                                      </td>
                                  <td><?php 
								if($row['dateofbirth']==00-00-0000)
									{
											echo '00-00-0000';
									}
									else
									{
											echo date('d-m-Y',strtotime($row['dateofbirth']));
									}?></td>
                                    <td><?php echo $row['email'];?></td>
                                    <td><?php echo $row['registrationtype'];?></td>
									<td><?php echo $row['createdon'];?></td>
                                    
									<?php
									$memberNo = $row['regnumber'];
									$updated_list_index = array_search($memberNo, $reversedArr_list);
									$srno = $updated_list_index;
									?>
                                    
                                    <td><a href="<?php echo base_url(); ?>admin/kyc/Kyc/edited_member/<?php echo $row['regnumber']; ?>/<?php echo $srno; ?>/<?php echo $totalRecCount; ?>">Recommend</a></td>
								</tr>
               	   <?php 
						$row_count++;		
				  			} 
				  }?>                  
                </tbody>
         </table>   <?php }else
		   {
			  ?><center> 
			  <?php 
			  if(isset($emptylistmsg))
			  {
					echo  $emptylistmsg;
				}
			  ?>
              </center>
		   <?php }?>
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
<script src="<?php echo base_url()?>js/validation.js"></script>
<script type="text/javascript">
  $('#search').parsley('validate');
</script>

<!--<script src="<?php echo base_url()?>js/js-paginate.js"></script>-->
<script type="application/javascript">
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

$(function () {
	$("#listitems").DataTable();
	var base_url = '<?php echo base_url(); ?>';
	var listing_url = base_url+'admin/kyc/Kyc/allocated_list/';
	
	// Pagination function call
	//paginate(listing_url,'','','');
	//$("#base_url_val").val(listing_url);
});
	
</script>
 <?php $this->load->view('admin/kyc/includes/footer');?>