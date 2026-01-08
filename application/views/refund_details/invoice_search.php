<?php $this->load->view('refund_details/includes/header');?>
<?php $this->load->view('refund_details/includes/sidebar');?>
  <?php //if(@$member){echo '<pre>$member',print_r($member),'</pre>';} ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
         Invoice Details
        
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
                                
								<option value="receipt_no" <?php if(isset($_POST['searchBy']) && $_POST['searchBy'] == 'receipt_no'){echo "selected='selected'";}?>>Receipt No</option>
                               
                                <option value="transaction_no" <?php if(isset($_POST['searchBy']) && $_POST['searchBy'] == 'transaction_no'){echo "selected='selected'";}?>>Transaction Number</option>
                               
							   <option value="regnumber" <?php if(isset($_POST['searchBy']) && $_POST['searchBy'] == 'regnumber'){echo "selected='selected'";}?>>Registration No</option>
								
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
      <center><h4>Invoice Details</h4></center>
           <table id="regDetails" class="table table-bordered table-striped ">
                <thead>
                <tr style="background-color:#00c0ef;color:#fff;">
                  <th>Membership No.<br />(Login ID)</th>
                  <th>Receipt No</th>
				  <th>Transaction No</th>
                  <th>Amount</th>
                  <th>Transaction Details</th>
                  <th>Date of Invoice</th>
                  <th>Invoice</th>
                  
                </tr>
                </thead>
                <tbody class="no-bd-y" id="">
                <?php if(count($invoice_records)){
					//print_r($invoice_records);
            foreach($invoice_records as $row1){   ?>
                 <tr>
                  <td><?php echo $row1['member_regnumber'];?></td>
                    <td><?php echo $row1['receipt_no'];?></td>
                    <td><?php echo $row1['transaction_no'];?></td>
                    <td><?php echo $row1['amount'];?></td>
                    <td><?php echo $row1['transaction_details'];?></td>
                    <td><?php echo $row1['date_of_invoice'];?></td>
                    <td><?php
					if($row1['app_type']=='O' && $row1['invoice_image']!= '')
					{ 
					?>
					   <a href="<?php echo base_url();?>uploads/examinvoice/user/<?php echo $row1['invoice_image'];?>" target="_blank">Invoice</a>
					<?php					
					}else if($row1['app_type']=='R'&& $row1['invoice_image']!= '')
					{
					?>
					   <a href="<?php echo base_url();?>uploads/reginvoice/user/<?php echo $row1['invoice_image'];?>" target="_blank">Invoice</a>
					<?php	
					}else if($row1['app_type']=='B'&& $row1['invoice_image']!= '')
					{
					?>
					   <a href="<?php echo base_url();?>uploads/bnqinvoice/user/<?php echo $row1['invoice_image'];?>" target="_blank">Invoice</a>
					<?php	
					}else if($row1['app_type']=='C'&& $row1['invoice_image']!= '')
					{
					?>
					   <a href="<?php echo base_url();?>uploads/dupcertinvoice/user/<?php echo $row1['invoice_image'];?>" target="_blank">Invoice</a>
					<?php	
					}else if($row1['app_type']=='D'&& $row1['invoice_image']!= '')
					{
					?>
					   <a href="<?php echo base_url();?>uploads/dupicardinvoice/user/<?php echo $row1['invoice_image'];?>" target="_blank">Invoice</a>
					<?php	
					}else if($row1['app_type']=='E'&& $row1['invoice_image']!= '')
					{
						$zone=  substr($row1['invoice_image'], 2, 2);
					?>
					   <a href="<?php echo base_url();?>uploads/contact_classes_invoice/user/<?php echo $zone;?>/<?php echo $row1['invoice_image'];?>" target="_blank">Invoice</a>
					   
					<?php	
					}else if($row1['app_type']=='F'&& $row1['invoice_image']!= '')
					{
					?>
					   <a href="<?php echo base_url();?>uploads/finquestinvoice/user/<?php echo $row1['invoice_image'];?>" target="_blank">Invoice</a>
					<?php	
					}else if($row1['app_type']=='H'&& $row1['invoice_image']!= '')
					{
					?>
					   <a href="<?php echo base_url();?>uploads/agency_renewal_invoice/user/<?php echo $row1['invoice_image'];?>" target="_blank">Invoice</a>
					<?php	
					}else if($row1['app_type']=='L'&& $row1['invoice_image']!= '')
					{
					?>
					   <a href="<?php echo base_url();?>uploads/examinvoice/user/<?php echo $row1['invoice_image'];?>" target="_blank">Invoice</a>
					<?php	
					}else if($row1['app_type']=='N'&& $row1['invoice_image']!= '')
					{
					?>
					   <a href="<?php echo base_url();?>uploads/renewal_invoice/user/<?php echo $row1['invoice_image'];?>" target="_blank">Invoice</a>
					<?php	
					}else if($row1['app_type']=='P'&& $row1['invoice_image']!= '')
					{
					?>
					   <a href="<?php echo base_url();?>uploads/cpdinvoice/user/<?php echo $row1['invoice_image'];?>" target="_blank">Invoice</a>
					<?php	
					}else if($row1['app_type']=='T'&& $row1['invoice_image']!= '')
					{
						$zone=  substr($row1['invoice_image'], 2, 2);
					?>
					   <a href="<?php echo base_url();?>uploads/blended_invoice/user/<?php echo $zone;?>/<?php echo $row1['invoice_image'];?>" target="_blank">Invoice</a>
					<?php	
					}else if($row1['app_type']=='V'&& $row1['invoice_image']!= '')
					{
					?>
					   <a href="<?php echo base_url();?>uploads/vision_invoice/user/<?php echo $row1['invoice_image'];?>" target="_blank">Invoice</a>
					<?php	
					}else if($row1['app_type']=='W'&& $row1['invoice_image']!= '')
					{
					?>
					   <a href="<?php echo base_url();?>uploads/drainvoice/user/<?php echo $row1['invoice_image'];?>" target="_blank">Invoice</a>
					<?php	
					}else if($row1['app_type']=='EL'&& $row1['invoice_image']!= '')
					{
					?>
					   <a href="<?php echo base_url();?>uploads/Elearning_invoice/user/<?php echo $row1['invoice_image'];?>" target="_blank">Invoice</a>
					<?php	
					}else if($row1['app_type'] == ''){
						echo 'No record found..';
					}?>
					</td>
					
                    
				   
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

/*function printContent(searchBy,searchkey)
{
  var base_url = '<?php echo base_url(); ?>';
  $.ajax({
    url: base_url+'admin/Report/getExamDetailsToPrint',
    type: 'POST',
    dataType:"json",
    data: {field : searchBy, value : searchkey },
    success: function(res) {
      if(res)
      {
        if(res.success == 'Success')
        {
          var content = '';
          for(i=0;i<res.result.length;i++)
          {
            var resultrow = res.result[i].firstname;
            //alert(resultrow);
            var index = i+1;
            content += '<tr><td>'+index+'</td><td>'+res.result[i].regnumber+'</td><td>'+res.result[i].firstname+'</td><td>'+res.result[i].gender+'</td><td>'+res.result[i].description+'</td><td>'+res.result[i].exam_fee+'</td><td>'+res.result[i].medium_description+'</td><td>'+res.result[i].center_name+'</td><td>'+res.result[i].transaction_no+'</td><td>'+res.result[i].transaction_details+'</td><td>'+res.result[i].date+'</td></tr>';
          }
          $("#print_list").html(content);
          $("#printBtn").show();
        }
        else
          $("#printBtn").hide();
      }
      else
        $("#printBtn").hide();
    }
  });
}*/

$(function () {
  //$("#listitems").DataTable();
  //$("#regDetails").DataTable();
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