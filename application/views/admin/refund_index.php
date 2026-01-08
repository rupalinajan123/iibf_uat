<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Transaction Refund
        
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
            <form class="form-horizontal" name="searchRefund" id="searchRefund" action="" method="post">
              <h3 class="box-title"></h3>
              <div class="pull-left">
                <div class="form-group">
                  
                    <label for="from_date" class="col-sm-1">Search</label>
                         <div class="col-sm-3">
                            <select class="form-control" name="searchOn" id="searchOn" required>
                                <option value="">Select</option>
                                <option value="01" <?php if(isset($_POST['searchOn']) && $_POST['searchOn'] == '01'){echo "selected='selected'";}?>>Exam Details</option>
                                <option value="02" <?php if(isset($_POST['searchOn']) && $_POST['searchOn'] == '02'){echo "selected='selected'";}?>>Duplicate i-card Details</option>
                                <option value="03" <?php if(isset($_POST['searchOn']) && $_POST['searchOn'] == '03'){echo "selected='selected'";}?>>Registration Details</option>
                            </select>
                             <span class="error"><?php echo form_error('from_date');?></span>
                        </div>
                    
                  		<div class="col-sm-3">
                        	<select class="form-control" name="searchBy" id="searchBy" required>
                                <option value="">Select</option>
                                <option value="transaction_no" <?php if(isset($_POST['searchBy']) && $_POST['searchBy'] == 'transaction_no'){echo "selected='selected'";}?>>Transaction Number</option>
                                </select>
                        </div>
                        <div class="col-sm-3">
                        	<input type="text" class="form-control" id="SearchVal" name="SearchVal" placeholder="" required value="<?php if(isset($_POST['SearchVal'])){echo $_POST['SearchVal'];}?>"  />
                        </div>
                    <input type="submit" class="btn btn-info" name="btnSearch" id="btnSearch" value="Search">  
                   
                </div>
              </div>
             </form>
            </div>
            
            <!-- /.box-header -->
            <div class="box-body">
            <form class="form-horizontal" name="searchRefund1" id="searchRefund1" action="" method="post">	
			<table id="listitems" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Registration No</th>
                  <th>Transaction No</th>
                  <th>Exam Fees</th>
                  <th>Payment Status</th>
                  <th>Authentication Code</th>
                  <th>Transaction Time</th>
                  <th>Make Refund</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                <?php  	if(count($result)){
							foreach($result as $row){	
				?>
                	<tr>
                        <td><?php echo $row['member_regnumber'];?></td>
                        <td><?php echo $row['transaction_no'];?></td>
                        <td><?php echo $row['amount'];?></td>
                        <td><?php echo $row['transaction_details'];?></td>
                        <td><?php echo $row['auth_code'];?></td>
                        <td><?php echo $row['date'];?></td>
                        <td>
                            <input type="checkbox" name="makeRefund" id="makeRefund" value="<?php echo $row['transaction_no'];?>|~|<?php echo $row['receipt_no'];?>"/>
                        </td>
                    </tr>
                <?php } ?>
                	<tr>
                    	<td colspan="7" align="center">
                    		<span id="chk_refund_error" style="color:#C00;"></span>
                            <br />
                           <div class="form-group">
                               <label for="from_date" class="col-sm-1">Reason</label>
                               <div class="col-md-4">
                                    <input type="text" name="refund_reason" id="refund_reason" class="form-control" required>
                                </div>
                                <div class="col-md-1">
                                    <input id="BtnDeact" value="De-Activate" name="BtnDeact" onclick="return fnDeact();" type="submit" class="btn btn-danger">
                                </div>
                            </div>
                    	</td>
                    </tr>
                <?php }else{ ?>
                	<td colspan="7" align="center">No Records Found...</td>
                <?php } ?>                  
                </tbody>
              </table>
              </form>
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
  $('#searchRefund').parsley('validate');
  $('#searchRefund1').parsley('validate');
</script>
<script>
$(document).ready(function() 
{
	
});

function fnDeact()
{
	if($("#makeRefund").is(':checked'))
	{
		if(confirm("Are you sure to deactivate the selected transaction?"))
		{
			$("#chk_refund_error").html('');
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		$("#chk_refund_error").html('Please select atleast 1 transaction to deactivate.');
		return false;
	}
}
		
</script>
 
<?php $this->load->view('admin/includes/footer');?>