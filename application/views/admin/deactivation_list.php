<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        De-activate New Member
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
              <h3 class="box-title">Search By</h3>
              <div class="pull-right">
              	<!--<a href="<?php echo base_url();?>admin/Member/de_active" class="btn btn-info">Refresh</a>-->
              </div>
            </div>
           <div class="box-body">
            
                <div class="col-sm-12">
                <form class="form-horizontal" name="search" id="search" action="<?php echo base_url();?>admin/Member/deactivate/1" method="post">
                    <div class="form-group">
                    	<label for="from_date" class="col-sm-2">Search By</label>
                        <input type="radio" class="minimal cls_search" id="regnumber"   name="searchBy"  required value="regnumber" <?php if($this->session->userdata('searchBy') == "regnumber"){echo 'checked="checked"';} ?> onclick="clearsearch();">Membership No.
                       <input type="radio" class="minimal cls_search" id="name"   name="searchBy"  required value="name" <?php if($this->session->userdata('searchBy') == "name"){echo 'checked="checked"';} ?> onclick="clearsearch();">Candidate Name
                       <input type="radio" class="minimal cls_search" id="mobile"   name="searchBy"  required value="mobile" <?php if($this->session->userdata('searchBy') == "mobile"){echo 'checked="checked"';} ?> onclick="clearsearch();">Mobile No.
                       <input type="radio" class="minimal cls_search" id="email"   name="searchBy"  required value="email" <?php if($this->session->userdata('searchBy') == "email"){echo 'checked="checked"';} ?> onclick="clearsearch();">Email
                       <input type="radio" class="minimal cls_search" id="transaction_no"   name="searchBy"  required value="transaction_no" <?php if($this->session->userdata('searchBy') == "transaction_no"){echo 'checked="checked"';} ?> onclick="clearsearch();">Transaction No.
                       <input type="radio" class="minimal cls_search" id="receipt_no"   name="searchBy"  required value="receipt_no" <?php if($this->session->userdata('searchBy') == "receipt_no"){echo 'checked="checked"';} ?> onclick="clearsearch();">Order No.
                   </div> 
                   
                   <div class="form-group">
                       <label for="from_date" class="col-sm-2">&nbsp;</label>
                       <textarea id="searchText" name="searchText" required  cols="50" rows="4"><?php echo $this->session->userdata('searchText');?></textarea>
                      <br>
                      <span class="col-sm-2"> </span>
                      <span class=""><strong>Note:</strong> Maximum 50 numbers/names can be search with comma (,) separate. </span>
                        
                   </div>
                   
                   <div class="form-group">
                        <label for="from_date" class="col-sm-2">&nbsp;</label>
                        <input type="button" class="btn btn-info" name="btnSearch" id="btnSearch" value="Search" onclick="validateSearch();">  
                   </div>
                 </form>
                 </div>
              
              </div>
          </div>
        </div>
      </div>
      
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Result</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            	
			<table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
                <tr>
                  <th id="">Membership No</th>
                  <th id="">Transaction No.</th>
                  <th id="">Candidate Name</th>
                  <th id="">D.O.B</th>
                  <th id="">Password</th>
                  <th id="">Mobile No</th>
                  <th id="">Reg Date</th>
                  <th id="">Payment Status</th>
                  <th id="">Deactivate</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                  <?php if(count($result)){
						foreach($result as $row){  
				  ?>
                    <tr>
                    	<td><?php echo $row['regnumber'];?></td>
                        <td><?php echo $row['transaction_no'];?></td>
                        <td><?php echo $row['firstname']." ".$row['middlename']." ".$row['lastname'];?></td>
                        <td><?php echo date('d-m-Y',strtotime($row['dateofbirth']));?></td>
                        <td><?php echo $row['usrpassword'];?></td>
                        <td><?php echo $row['mobile'];?></td>
                        <td><?php echo date('d-m-Y',strtotime($row['createdon']));?></td>
                        <td>
						<?php 
							/*if($row['status']==1){
								echo "Completed";
							}else if($row['status']==2){
								echo "Pending";
							}else{
								echo "Incomplete";
							}*/
							
							echo $row['status'];
						?>
                        </td>
                        <td>
                        	<a href="<?php echo base_url(); ?>admin/Member/deactivate_member/<?php echo base64_encode($row['regnumber']); ?>" onclick="return confirm('Do you want to deactivate the member?')">Deactivate</a>
                        </td>
                    </tr>
                  <?php }}else{ ?>
                  		<tr>
                        	<td colspan="9" align="center">No Data Available</td>
                  		</tr>
                  <?php } ?>                  
                </tbody>
              </table>
               <div style="width:30%; float:left;">
               <?php echo $info; ?>
               </div>
               <div id="links" class="" style="float:right;"><?php echo $links; ?></div>
               <!--<div id="links" class="dataTables_paginate paging_simple_numbers">
               
               </div>-->
               
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
	//$("#listitems").DataTable();
	/*var base_url = '<?php //echo base_url(); ?>';
	var listing_url = base_url+'admin/Report/getList';
	
	// Pagination function call
	paginate(listing_url,'','','');
	$("#base_url_val").val(listing_url);*/
});
		

		
</script>
 
<?php $this->load->view('admin/includes/footer');?>