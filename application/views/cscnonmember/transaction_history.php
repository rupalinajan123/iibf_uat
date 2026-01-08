  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Online Transaction Details 
        
      </h1>
      <?php //echo $breadcrumb; ?>
    </section>
	<form class="form-horizontal" name="usersAddForm" id="usersAddForm" action="" method="post">
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title"></h3>
              <!--<div class="pull-right">
              	<a href="<?php //echo base_url();?>admin/CenterMaster/add"><input type="button" class="btn btn-warning"  value="Add"></a>
                <a href="<?php //echo base_url();?>admin/CenterMaster/import" class="btn  btn-primary">Import</a>
                <a href="<?php //echo base_url();?>admin/CenterMaster/download"><input type="button" class="btn  btn-primary"  value="Download"></a>
                <input type="button" class="btn btn-info" onclick="refreshDiv('');" value="Refresh">
                <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
                <input type="hidden" name="base_url_val" id="base_url_val" value="" /> 
              </div>-->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            	
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
            
              <table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
                <tr>
                  <th id="srNo">S.No.</th>
                  <th id="description">Exam Name</th>
                  <th id="receipt_no">Receipt Number</th>
                  <th id="transaction_no">Transaction Number</th>
                  <th id="transaction_no">Status</th>
                  <th id="transaction_details">Transaction Details</th>
                  <th id="amount">Amount</th>
                  <th id="date">Date</th>
				  <th id="invoice">Download Invoice</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                <?php if(count($transData)){
						$i = 1;
						foreach($transData as $row){
					?>
                    <tr>
                    	<td><?php echo $i; ?></td>
                        <?php if($row['transaction_no']) {?>
						 <td> <?php echo str_replace("\\'","",html_entity_decode($row['description']));?> </td>
						<?php } else{ ?>
                       <td> <?php $exam_name = $this->master_model->getRecords('exam_master',array('exam_code'=>$row['exam_code']),'description');
					  if(count($exam_name)){ echo $exam_name[0]['description']; } ?> </td>
						<?php } ?>
                        <td><?php echo $row['receipt_no']; ?></td>
						<?php if($row['transaction_no']){?>
							<td><?php echo $row['transaction_no']; ?></td>
						<?php } elseif(@$row['UTR_no']) { ?>
							<td><?php echo $row['UTR_no'];?></td>
						<?php } ?>
                        <td><?php if($row['status']==1){ echo "Success"; }else{ echo  "Failed"; } ?></td>
						<?php if($row['transaction_details']){?>
							<td><?php echo $row['transaction_details']; ?></td>
						<?php } elseif($row['description']){?>
							<td><?php echo $row['description']; ?></td>
						<?php }?>
						<?php if($row['transaction_no']){?>
							<td><?php echo $row['amount'];?></td>
						<?php } elseif(@$row['UTR_no']) { ?>
							<td><?php echo $row['exam_fee'];?></td>
						<?php } ?>
                        <td><?php echo date('d-M-Y',strtotime($row['date'])); ?></td>
                   	 <td><?php
						 if(isset($row['transaction_no']))
						 {
						  $invoice_path='';
						 $this->db->where('invoice_image!=','');
						$invoice_info= $this->master_model->getRecords('exam_invoice',array('transaction_no'=>$row['transaction_no']));
						if(!empty($invoice_info))
						{		
						
								$invoice_path=Get_invoice_path($invoice_info[0]['app_type'],$invoice_info[0]['invoice_image']);
								
								if($invoice_path!='')
							{
							?>
							
							
							<a href="<?php echo base_url();?><?php echo $invoice_path;?>" download><input type="button" class="btn btn-warning"  value="Invoice"></a>
							<?php 
							}else
							{
							  echo '-';
							}
					    }
						else
						{
						  echo '-';
						}
						}else
						{
						  echo '-';
						}	  
					
					 ?></td>
				   
				    </tr>
                <?php $i++;
						}
					} ?>              
                </tbody>
              </table>
              <!-- <div id="links" class="" style="float:right;"><?php //echo $links; ?></div>-->
               <div id="links" class="dataTables_paginate paging_simple_numbers">
               
               </div>
               
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      
    </section>
    </form>
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
  
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script type="text/javascript">
  $('#usersAddForm').parsley('validate');
</script>
<!--<script src="<?php //echo base_url()?>js/js-paginate.js"></script>-->

<script>
$(document).ready(function() 
{
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
	//var listing_url = base_url+'admin/Report/getList';
	
	// Pagination function call
	//paginate(listing_url,'','','');
	//$("#base_url_val").val(listing_url);
});
		
</script>
