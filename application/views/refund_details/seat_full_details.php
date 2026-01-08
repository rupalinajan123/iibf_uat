<?php $this->load->view('refund_details/includes/header');?>
<?php $this->load->view('refund_details/includes/sidebar');?>
  <?php //if(@$member){echo '<pre>$member',print_r($member),'</pre>';} ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
         Seat Full Details
        
      </h1>
      <?php //echo $breadcrumb; ?>
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
              <h3 class="box-title"></h4>
              
                <div class="col-sm-12">
                  <form class="form-horizontal" name="searchExamDetails" id="searchExamDetails" action="" method="post">      
                        <label for="to_date" class="col-sm-2">Search By</label>
                         
                          <div class="col-sm-2">
                            <select class="form-control" name="searchBy" id="searchBy" required>
                                <!--<option value="">Select</option>-->
                                <option value="regnumber" <?php if(isset($_POST['searchBy']) && $_POST['searchBy'] == 'regnumber'){echo "selected='selected'";}?>>Registration No</option>
								<option value="receipt_no" <?php if(isset($_POST['searchBy']) && $_POST['searchBy'] == 'receipt_no'){echo "selected='selected'";}?>>Receipt No</option>
                                
                                <option value="transaction_no" <?php if(isset($_POST['searchBy']) && $_POST['searchBy'] == 'transaction_no'){echo "selected='selected'";}?>>Transaction Number</option>
                               
								
                            </select>
                        </div>
                        <div class="col-sm-3">
                             <input type="text" class="form-control" id="SearchVal" name="SearchVal" placeholder="" required value="<?php if(isset($_POST['SearchVal'])){echo $_POST['SearchVal'];}?>" >
                        </div>
                        <div class="col-sm-2">
                            <input type="submit" class="btn btn-info" name="btnSearch" id="btnSearch" value="Search">
                            
                           
                        </div> 
                    </form> 
                </div>
             
            </div>
            <!-- /.box-header -->
           <div class="box-body">
      <center><h4>Seat full Details</h4></center>
           <table id="regDetails" class="table table-bordered table-striped ">
                <thead>
                <tr style="background-color:#00c0ef;color:#fff;">
                  <th>Membership No.<br />(Login ID)</th>
				  <th>Name</th>
				  <th>Receipt No</th>
				  <th>Transaction No</th>
				  <th>Refund Type</th>
				  <th>Status</th>
                  <th>Email</th>
				  <th>Mobile</th>
				  <th>Payment Date</th>
                  
                </tr>
                </thead>
                <tbody class="no-bd-y" id="">
                <?php if(count($reg_num_res)){
					//echo '<pre>'; print_r(count($reg_num_res));
            foreach($reg_num_res as $row1){   ?>
                 <tr>
                  <td><?php echo $row1[0]['member_regnumber'];?></td>
                  <td><?php echo $row1[0]['namesub']. $row1[0]['firstname']. $row1[0]['lastname'];?></td>
                    <td><?php echo $row1[0]['receipt_no'];?></td>
                    <td><?php echo $row1[0]['transaction_no'];?></td>
                    <td><?php echo 'Seat Full Cases';?></td>
                    
					
                    <td><?php
					if($row1[0]['status'] == '0')
					{
						$status = "Failure"; $color = "Red";
					}else if($row1[0]['status'] == '2')
					{ $status = "Pending"; $color = "Orange";  
					} else if($row1[0]['status'] == '1')
					{ $status = "Success"; $color = "Green";  
					} else if($row1[0]['status'] == '3')
					{ $status = "Refund"; $color = "Red";
					}else if($row1[0]['status'] == '6')
					{ $status = "ChargeBack"; $color = "Blue";
					}else
					{ $status = "Pending"; $color = "Orange";    }
					
					?>
					<strong><font color="<?php echo $color; ?>"><?php echo $status; ?></font> <br><hr size="1/">
					</td>
					
                    
                    <td><?php echo $row1[0]['email'];?></td>
                    <td><?php echo $row1[0]['mobile'];?></td>
                    <td><?php echo $row1[0]['date'];?></td>
                   
				   
                 </tr>
                 <?php }}else{ ?>
                 <tr><td colspan="4" align="center">No records found...</td></tr>
                 <?php } ?>                   
                </tbody>
              </table>
            <br />
           
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
  $('#searchExamDetails').parsley('validate');
</script>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script type="text/javascript">
$(document).ready(function() 
{
  
});

function confirmMailSend()
{
  if(confirm("Do you want to re-send registration mail?"))
  {
    return true;  
  }
  else
  {
    return false;
  }
    
}



$(function () {
  $("#regDetails").DataTable();
  $("#listitems_filter").show();
});
    
</script>

<script>
function printDiv(divName) {
  
     var printContents = document.getElementById('print_div').innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
 
<?php $this->load->view('refund_details/includes/footer');?>