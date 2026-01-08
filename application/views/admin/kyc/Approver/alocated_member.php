

<?php $this->load->view('admin/kyc/includes/header');?>
<?php $this->load->view('admin/kyc/includes/sidebar');?>
<!--fancybox--

	

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
     KYC Member List 
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
      <?php /*?><div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
            	<div class="col-md-12">
                    <h3 class="box-title">Select List:</h3>
                    <div class="form-group">
                      <form class="form-horizontal" name="btnSearch" id="btnSearch" action="<?php echo base_url();?>/admin/kyc/Kyc/allocated_list"  method="post">      
                     
                                  <label for="to_date" class="col-sm-2">Membership No:</label>
                            <div class="col-sm-3">
                            <input type="text" class="form-control" id="regnumber" name="regnumber" placeholder="Membership No."/>                        </div>
                            
                                    <label for="to_date" class="col-sm-2">Registration type</label>
                            <div class="col-sm-3">
                            <input type="text" class="form-control" id="registrationtype" name="registrationtype" placeholder="Registration typ"/>                        </div>
                            
                            
                        <input type="submit" class="btn btn-info" name="btnSearch" id="btnSearch" value="Search"> 
                    </form> 
                    </div>
                   
              </div>
          
            </div>
           <div class="box-body">
            
            </div>
          </div>
        </div>
      </div><?php */?>
      
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Allocated  records </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
    	
			<table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
                <tr>
                 <th id="">Sr.No</th> 
                  <th id="">Membership No</th>
                  <th id="">Candidate Name</th>
                  <th id="">D.O.B</th>
                  <th id="">Email</th>
                  <th id="">Registration type</th>
                  <th id="">Action</th>
                  
                </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                  <?php if(count($result)){
					  		  $row_count = 1;
					 	  	foreach($result as $row)
							{  
							?>
                            <tr>
                                <td><?php echo $row_count;?>.</td>
                                <td><?php echo $row['regnumber'];?></td>
                                <td><?php echo $row['firstname']." ".$row['middlename']." ".$row['lastname'];?></td>
                                <td><?php echo date('d-m-Y',strtotime($row['dateofbirth']));?></td>
                                <td><?php echo $row['email'];?></td>
                                 <td><?php echo $row['registrationtype'];?></td>
                                <td><a href="<?php echo base_url(); ?>admin/kyc/Kyc/member/<?php echo $row['regnumber']; ?>">Recommend</a></td>
                              
                            </tr>
               	   <?php 
						$row_count++;		
				  			} 
				 		 }?>                  
                </tbody>
              </table>
             <!--  <div style="width:30%; float:left;">
               <?php //echo $info; ?>
               </div>-->
               <!--<div id="links" class="" style="float:right;"><?php //echo $links; ?></div>-->
               <!--<div id="links" class="dataTables_paginate paging_simple_numbers">
               
               </div>-->
               
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col 
      
  
  
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
	paginate(listing_url,'','','');
	$("#base_url_val").val(listing_url);
});
		

		
</script>
 
<?php $this->load->view('admin/kyc/includes/footer');?>