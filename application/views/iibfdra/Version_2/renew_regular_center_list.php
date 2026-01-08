<div class="content-wrapper">
  <section class="content-header">
    <h1>Renew Regular Center List View</h1>
    <p class="admin_note"> Admin allow you to renew all your regular center with payment </p>
  </section> 
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Renew Regular Center List</h3>              
              <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="listitems" class="table table-bordered table-striped Tables-example table-hover">
                <thead>
                  <tr>
                    <th id="srNo">S.No.</th>
                    <th id="centername">Center Name</th>
                    <!--<th id="city">City</th>-->
                    <th id="ststus">Status</th>
                    <th id="name">Contact Person Name</th>
                    <th id=centertype>Center Type</th>
                    <th id=accradation>Accreditation Period</th>
                  </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                
                  <?php 
				  $center_arry = array();
				  if( count( $center_listing )  > 0 ) { 
                          $i = 1;
                          foreach( $center_listing as $center ) { 
						  	$center_arry[] = $center['center_id']; 						  
						  ?>
                  <tr>
                    <td><?php echo $i;?></td>
                   <td><?php
                    if( $center['city_name']== ""){
                          echo $center['location_name'];
                      } 
                      else{
                      echo $center['city_name'];
                    }?></td>
                     
                    <?php /*?> <td>
					 <?php
                    if( $center['city_name']== ""){
                          echo $center['location_name'];
                      } 
                      else{
                      echo $center['city_name'];
                    }?>
                    </td><?php */?>
                    
                    <td>
					<?php 
					if($center['center_status'] == 'IR' || $center['center_status'] == 'AR'){ echo '<span style="color:#00C;">InReview</span>' ;} elseif($center['center_status'] == 'R'){ echo '<span style="color:#F00;">Rejected</span>' ;}else { echo '<span style="color:#093;">Approved</span>' ; }
					 ?>
                    <!-- <strong class="b_txt">Expired</strong>-->
                    </td>
                    
                    <td><?php echo $center['contact_person_name'];?></td>
                    
                    <td><?php if($center['center_type'] == 'T'){ echo 'Temporary';} else { echo 'Regular'; } ?></td>
                    
                    <td><?php if( $center['center_validity_to'] != '')  {?> FROM <strong><?php echo date_format(date_create($center['center_validity_from']),"d-M-Y"); ?> </strong> TO <strong><?php echo date_format(date_create($center['center_validity_to']),"d-M-Y"); ?></strong>
                     <?php  }else{ ?>
                     <strong> Accreditation Period Not Added</strong>
                     <?php   }?></td>
                  </tr>
                  <?php $i++; } }?>  
                </tbody>
              </table>
                        
            </div>
            <!-- /.box-body --> 
          </div>
          <!-- /.box --> 
        </div>
        <!-- /.col --> 
     
      
      <div class="col-xs-12 acc_div" >
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Renew Regular Center details </h3>
            <div class="box-tools pull-right"> 
              <!-- Collapse Button -->
              <button type="button" class="btn btn-box-tool" data-widget="collapse"> <i class="fa fa-minus"></i> </button>
            </div>
            <!-- /.box-tools --> 
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="table-responsive">
              <form name="renew_form" id="renew_form" method="POST" action="<?php echo base_url().'iibfdra/Version_2/CenterRenew/make_payment'; ?>">               
                <input type="hidden" id="center_add_status" name="center_add_status" value="" /> 
                <input type="hidden" value="1" name="is_renew" id="is_renew" />  
                 <input type="hidden" value="R" name="center_type" id="center_type" /> 
                           
                <input type="hidden" value="<?php if(count($center_arry)>0){ echo implode(',',$center_arry); } ?>" name="center_ids" id="center_ids" />
                		
                
                <?php					
              if(count($renew_result)){  ?>
              
              <table class="table table-bordered">
                  <tbody>
				<?php					
                  if(count($renew_result)){  
						$current_year = date('Y');
						$next_year = $current_year+1;
						$next_next_year = $next_year+1;
						?>
                          <td width="50%"><strong> From Date - To Date Of Accreditation Period :</strong></td>
                            <td width="50%">
                          From : <strong><?php echo date_format(date_create($renew_result['center_validity_from']),"d-M-Y"); ?> </strong>
                           - To : <strong><?php echo date_format(date_create($renew_result['center_validity_to']),"d-M-Y"); ?> </strong>    
                          </td>                      
                    <?php } ?>
                 
                 <?php if(trim($renew_result['renew_type']) == 'pay'){?>
                     <tr>
                    <td width="50%"><strong>Renewal Amount : </strong> 
                    </td>
                    <td width="50%">
                  <strong> ₹ 5000 /-</strong> <br><span class="amount_msg"> Excluding GST </span>
                    </td>
                  </tr>
                  <?php }?>
                </tbody>
                  
                </table>
              
             <?php if($renew_result['renew_type'] == 'pay' && $renew_result['pay_status'] != '1')
        	  {
        	  ?>
            <div class="box-footer">
              <div class="col-sm-6 col-sm-offset-3">
                <div class="col-sm-12">
                  <center>
                  <a class="renew_btn btn btn-success make_payment" id="make_payment" href="javascript:void(0);">Make Payment</a>
                  </center>
                </div>
              </div>
            </div>
            <?php } else if($renew_result['pay_status'] == '1' && $renew_result['renew_type'] == 'pay') {?>
            <div class="box-footer">
              <div class="col-sm-6 col-sm-offset-3">
                <div class="col-sm-12">
                  <center>
                   <strong style="color:#090">PAID</strong>
                  </center>
                </div>
              </div>
            </div>
             <?php } else if($renew_result['pay_status'] == '1'  && $renew_result['renew_type'] == 'free' ) {?>
            <div class="box-footer">
              <div class="col-sm-6 col-sm-offset-3">
                <div class="col-sm-12">
                  <center>
                   <strong style="color:#090">FREE RENEW</strong>
                  </center>
                </div>
              </div>
            </div>
            <?php }
				  }else{					  
					  echo '<center><h3 class"info">Contact Admin to Renew Regular Centers </h3></center>';
				  }
				  ?>
                <input type="hidden" name="action" value="renew_regular" />
              </form>
            </div>
          </div>
          <!-- box-footer --> 
        </div>
        <!-- /.box --> 
      </div>
      
       </div>
      
    </section>
  
</div>
<style>
.b_txt{
color:#930
}

.amount_msg{
    font-size: 12px;
    font-style: italic;
    color: #900;
    widows: 100%;
}
</style>
<script type="text/javascript">

$(function () {

	
	
	$("#make_payment").click(function () {
		if (confirm('Are you sure you want pay to Renew Agency regular center ?')) {
			$('#renew_form').submit();	
		} else {
			return false;
		}	
	});
	
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