<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Exam Registration Details
        
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
              <h3 class="box-title"></h3>
              
              <div class="pull-left" style="700px;">
                <div class="col-sm-12">
                  <form class="form-horizontal" name="searchdupCertDetails" id="searchdupCertDetails" action="<?php echo base_url();?>admin/Report/dupCert" method="post">      
                        <label for="to_date" class="col-sm-2">Search By</label>
                         <div class="col-sm-3">
                           
                            <select class="form-control" name="searchBy" id="searchBy" required>
                            	<option value="">Select</option>
                              <option value="01">Registration No</option>
                                <!--<option value="02">Transaction No</option>-->
                                </select>
                            
                             <span class="error"><?php echo form_error('regnumber');?></span>
                        </div>
                        <div class="col-sm-3">
                        	 <input type="text" class="form-control" id="SearchVal" name="SearchVal" placeholder="" required value="" >
                        </div>
                        <div class="col-sm-3">
                       		<input type="submit" class="btn btn-info" value='Submit'/>
                        </div> 
                    </form> 
                </div>
              </div>
             
              
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            	
			<table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
                <tr>
                  <th id="srNo">S.No.</th>
                  <th id="regnumber">Registration No</th>
                  <th id="firstname">First Name</th>
                  <th id="description">Exam Name</th>
                  <th id="fee">Exam Fee</th>
                  <th id="transaction_no">Transaction<br />No</th>
				  <th id="recp_no">Receipt<br />No</th>
                  <th id="transaction_details">Payment Status</th>
				  <th id="date">Transaction Time</th>
				  <th id="center_name">Invoice View</th>
                  
                </tr>
                </thead>
                <tbody class="no-bd-y">
				<?php //print_r($data); ?>
			 <?php foreach($mem_details as $val)
			 { $i=1; 
			 	
			 ?>
			 <tr>
				<td id="srNo"><?php echo $i;?></td>
				<td id="regnumber"><?php echo $val['regnumber'];?></td>
				<td id="firstname"><?php echo $val['firstname'];?> </td>
				<td id="exam_name"><?php echo $val['exam_name'];?></td>
				<td id="fee"><?php echo $val['fee'];?></td>
				<td id="transaction_no"><?php if($val['transaction_no']!=''){echo $val['transaction_no'];}else{echo '-';}?></td>
				<td id="transaction_no"><?php if($val['receipt_no']!=''){ echo $val['receipt_no'];}else{echo '-';}?></td>
				<td id="transaction_details"><?php echo $val['status'];?></td>
				
				<td id="date"><?php echo $val['created_on'];?></td>
				<td id="center_name"><?php if($val['invoice_image']!=''){?>
				<a href="<?php echo base_url();?>uploads/dupcertinvoice/user/<?php echo $val['invoice_image'];?>" target="_blank">Invoice</a>
				<?php }else
				{
				echo '-';
				}?>
				</td>
				
			 </tr>
			 <?php }?>
                                    
                </tbody>
              </table>
              <!-- <div id="links" class="" style="float:right;"><?php //echo $links; ?></div>-->
               <div id="links" class="dataTables_paginate paging_simple_numbers">
               
               </div>
               
            </div>
          </div>
        </div>
      </div>
      
    </section>
   
  </div>
  
<!-- Print Content -->

<div class="content-wrapper" id="print_div" style="display: none;">
<!-- Content Header (Page header) -->
    <div  style=" background: #fff;border: 1px solid #000; padding:10px; width:100%;">
        <table width="90%" cellspacing="0" cellpadding="10" border="0" align="center" >         
        	<tr> <td colspan="4" align="left">&nbsp;</td> </tr>
            <tr>
            
                <td colspan="4" align="center" height="25">
                    <span id="1001a1" class="alert">
                    </span>
                </td>
            </tr>
        
            <tr> 
                <td colspan="4"  height="1"><img src="<?php echo base_url()?>assets/images/logo1.png" class="img"></td>
            </tr>
            <tr> 
                <td colspan="4"  height="1" align="center">Master Report ­ Exam Registration Details</td>
            </tr>
            
            <tr colspan="4">
                <table class="table" style="width:90%;">
                    <thead>
                        <tr>
                            <th id="srNo">S.No.</th>
                            <th id="regnumber">Registration No</th>
                            <th id="firstname">First Name</th>
                            <th id="gender">Gender</th>
                            <th id="description">Exam Name</th>
                            <th id="exam_fee">Exam Fee</th>
                            <th id="exam_medium">Exam Medium </th>
                            <th id="center_name">Center Name</th>
                            <th id="transaction_no">Bill Desk <br />Tran.No</th>
                            <th id="transaction_details">Payment Status</th>
                            <th id="date">Transaction Time</th>
                        </tr>
                    </thead>
                    <tbody class="no-bd-y" id="print_list">
                    
                    </tbody>
                </table>
            </tr>
        </table>
    </div>
</div>
<!-- Print Content End -->
  
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
  //$('#searchExamDetails').parsley('validate');
  //$('#searchReg').parsley('validate');
</script>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>



 
<?php $this->load->view('admin/includes/footer');?>