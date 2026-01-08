<div class="content-wrapper">
  <section class="content-header">
    <h1>Renew Temporary Training Centers </h1>
  </section>
  <form class="form-horizontal" name="usersAddForm" id="usersAddForm" action="" method="post">
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
               <?php if($this->session->flashdata('error')!=''){?>
              <div class="alert alert-danger alert-dismissible" id="error_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('error'); ?> </div>
              <?php } if($this->session->flashdata('success')!=''){ ?>
              <div class="alert alert-success alert-dismissible" id="success_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('success'); ?> </div>
              <?php } ?>
            <div class="box-header">
              <h3 class="box-title">Renew Temporary Center List</h3>
              
              <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="listitems" class="table table-bordered table-striped Tables-example table-hover">
                <thead>
                  <tr>
                    <th id="srNo">S.No.</th>
                    <th id="centername">Center Name</th>
                   <!-- <th id="city">City</th>-->
                    <th id="ststus">Status</th>
                    <th id="name">Contact Person Name</th>
                    <th id=centertype>Center Type</th>
                    <th id=accradation>Accreditation Period</th>
                    <th id="batchaction">Operations</th>
                  </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                  <?php if( count( $center_listing )  > 0 ) { 
                          $i = 1;
                          foreach( $center_listing as $center ) { ?>
                  <tr>
                    <td><?php echo $i;?></td>
                   <td><?php
                    if( $center['city_name']== ""){
                          echo $center['location_name'];
                      } 
                      else{
                      echo $center['city_name'];
                    }?></td>
                     <!--<td><?php
                    if( $center['city_name']== ""){
                          //echo $center['location_name'];
                      }else{
                      //echo $center['city_name'];
                    }?></td>-->
                    
                    <td>
                    <?php
					$today_day = date('Y-m-d');					
					$to_date =  strtotime(date('Y-m-d',strtotime($center['center_validity_to'])));					
					$today_date = strtotime($today_day);
					
					if($to_date < $today_date){
						$expire_str = ' <span class="exp_font">(Expired)</span> ';
					}else{
						$expire_str = '';
					}
					
					if($center['center_validity_to'] == ''){
						$expire_str = '';
					}
					?>
                    
					<?php 
					if($center['center_status'] == 'IR' || $center['center_status'] == 'AR'){ echo '<span style="color:#00C;">InReview</span>'.$expire_str ;} elseif($center['center_status'] == 'R'){ echo '<span style="color:#F00;">Rejected</span>' ;}else { echo $expire_str ; }
					 ?>
                    <!-- <strong class="b_txt">Expired</strong>-->
                    </td>
                    
                    <td><?php echo $center['contact_person_name'];?></td>
                    
                    <td><?php if($center['center_type'] == 'T'){ echo 'Temporary';} else { echo 'Regular'; } ?></td>
                    
                    <td><?php if( $center['center_validity_to'] != '')  {?> FROM <strong><?php echo date_format(date_create($center['center_validity_from']),"d-M-Y"); ?> </strong> TO <strong><?php echo date_format(date_create($center['center_validity_to']),"d-M-Y"); ?></strong>
                     <?php  }else{ ?>
                     <strong> Accreditation  Period Not Added</strong>
                     <?php   }?></td>
                     
                    <td>
                    <a class="btn btn-info btn-xs vbtn" href="<?php echo base_url().'iibfdra/Version_2/CenterRenew/view/'.$center['center_id'];?>">View</a> &nbsp; &nbsp; 
                    <?php if($center['is_renew'] != '1'){ ?>
                   | &nbsp; &nbsp; <a class="btn btn-info btn-xs vbtn" href="<?php echo base_url().'iibfdra/Version_2/CenterRenew/edit/'.$center['center_id'];?>">Renew</a>
                   <?php } ?>
                   <?php /*?> <?php if($center['pay_status'] == '1') { ?> 
                  	| <strong style="color:#090">Paid</strong>
             		<?php } ?><?php */?>
                    </td>
                  </tr>
                  <?php $i++; } }?>  
                </tbody>
              </table>
              <div style="width:30%; float:left;">
                <?php /*Removed pagination on 21-01-2017*/ 
							//echo $info; ?>
              </div>
              <div id="links" class="" style="float:right;"><!-- <?php //echo $links; ?> --></div>
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
<style>
.b_txt{
color:#930
}
.exp_font{
 font-size:13px;
 color:#600;	
}
</style>
<script type="text/javascript">

$(function () {

	// add multiple select / deselect functionality
	$("#selectall").click(function () {
		  $('.chkmakepay').prop('checked', this.checked);
	});

	// if all checkbox are selected, check the selectall checkbox
	// and viceversa
	$(".chkmakepay").click(function(){
		if($(".chkmakepay").length == $(".chkmakepay:checked").length) {
			$("#selectall").prop("checked", true);
		} else {
			$("#selectall").removeAttr("checked");
		}

	});
	$( ".draexampay" ).submit(function() {
		if( $(".chkmakepay:checked").length == 0 ) {
			alert('Please select at least one candidate to pay');
			return false;	
		} else {
			return true;	
		}
	});
});

</script>
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

<!-- Data Tables --> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script> 
<!-- Data Tables --> 
<script type="text/javascript">
 $(document).ready(function() {
    $('#listitems').DataTable({
    responsive: true
  });
 });
</script>