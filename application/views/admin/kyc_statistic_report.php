<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       User wise kyc report
        
      </h1>
      <?php //echo $breadcrumb; ?>
    </section>
    <br />
	<div class="col-md-12">
     <?php if(isset($error) && $error != ''){ ?>
    <div class="callout callout-danger" style="color:#FFF !important"><?php echo $error;?></div>
    <?php }?>
    </div>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              
              <div class="pull-left">
                <div class="form-group">
                  <form class="form-horizontal" action="" method="post">
                    <label for="from_date" class="col-sm-2">Select:</label>
                         <div class="col-sm-5">
                            <input type="text" class="form-control" id="from_date" name="from_date" placeholder="Date" value="<?php echo set_value('from_date');?>" required="required" readonly>
                             <span class="error"><?php echo form_error('from_date');?></span>
                        </div>
                    <input type="submit" class="btn btn-info" name="btnSearch" id="btnSearch" value="Search" >  
                   </form>
                </div>
                <h3 class="box-title">Recomender</h3>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            	<table class="table">
                	<tr>
                    	<th>Sr.</th>
                    	<th>Name</th>
                        <th>Recomended</th>
                        <th>Remaining</th> 
                    </tr>
                    <?php 
						$j = 1;
						$i = 0; 
						$mem_array=$arraid=$insert=$arraid1=$arraid2=array();
						foreach($rocomeder_details as $record){
					?>
                    <tr>
                    	<td><?php echo $j;?></td>
                    	<td><?php echo $record['name'];?></td>
                        <td><?php echo $recomended_cnt[$i];?></td>
                        
                        <td>
							<?php  
								if(isset($recomender_rem_cnt[$i])){ 
								    if($recomender_rem_cnt[$i] < 0){
										echo  "0";
									}else{
										echo $recomender_rem_cnt[$i] ;
									}
									
								}else{
									echo "-";
								}
						?>
                         </td>
                    </tr>
                    <?php $i++; $j++; }?>
                </table>
            </div>
            <!-- /.box-body -->
          </div>
          
          <div class="box">
            <div class="box-header">
              
              <div class="pull-left">
              	<h3 class="box-title">Approver</h3>
                <div class="form-group">
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            	<table class="table">
                	<tr>
                    	<th>Sr.</th>
                    	<th>Name</th>
                        <th>Approved</th>
                        <th>Remaining</th>
                    </tr>
                    <?php 
						$j = 1;
						$i = 0; 
						foreach($approver_details as $result){
					?>
                    <tr>
                    	<td><?php echo $j;?></td>
                    	<td><?php echo $result['name'];?></td>
                        <td><?php echo $approved_cnt[$i];//$app_rec_cnt[$i];?></td>
                        <td>
							<?php  
								if(isset($approver_rem_cnt[$i])){ 
									if($approver_rem_cnt[$i] < 0){
										echo "0";
									}else{
										echo $approver_rem_cnt[$i];
									}
									
								}else{
									echo "-";
								}
							?>
                        </td>
                    </tr>
                    <?php $i++; $j++; }?>
                </table>
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
	
	
});
		
</script>
 
<?php $this->load->view('admin/includes/footer');?>