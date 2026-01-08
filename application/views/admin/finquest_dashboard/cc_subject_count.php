<?php $this->load->view('admin/finquest_dashboard/includes/header');?>
<?php $this->load->view('admin/finquest_dashboard/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
 Contact classes  member subscription List 
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
  <?php /*?>    <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
            	<div class="col-md-12">
                    <h3 class="box-title">Select List:</h3>
                    <div class="form-group">
                      <form class="form-horizontal" name="btnSearch" id="btnSearch" action="<?php echo base_url();?>/admin/kyc/Kyc/recommended_list"  method="post">      
                     
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
              <h3 class="box-title">Contact classes  members</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
    	
			<table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
                <tr>
                
                  <th id="">Sr No</th> 
                <th id="">Sub Name</th> 
                  <th id=""> Sub code</th>
                    <th id="">Center code</th>
                      <th id="">Total reg</th>
                       <th id="">Capacity</th>
                       <th id="">Last date of registration</th>
               </tr>
                </thead>
               <?php 
		
			   $row_count=1;
			if(count($cc_subject_list)){

						foreach($cc_subject_list as $row)
						{?>
							<tr>
               			<td><?php echo $row_count;?></td>
                        <td><?php echo $row['sub_name'];?></td>
                         <td><?php echo $row['sub_code'];?></td>
                          <td><?php echo $row['center_code'];?></td>
                           <td><?php echo $row['total_reg'];?></td>
                        
                    <td ><?php if($row['total_reg']>=$row['capacity'])
                    {?>
                  		  <p style="color:#F00"> <?php echo $row['capacity'];?></p>
                    <?php }else
					{
						echo $row['capacity'];
						
					}?></td> 
                    
                      <td><?php echo $row['last_date_reg'];?></td>


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
	$("#listitems").DataTable();
	/*var base_url = '<?php // echo base_url(); ?>';
	var listing_url = base_url+'admin/kyc/Kyc/recommended_list/';
	
	// Pagination function call
	paginate(listing_url,'','','');
	$("#base_url_val").val(listing_url);*/
});
		

		
</script>
 
<?php $this->load->view('admin/kyc/includes/footer');?>