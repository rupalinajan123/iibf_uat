<div class="content-wrapper">
  <section class="content-header">
    <h1>Center List View</h1>
  </section>
  <form class="form-horizontal" name="usersAddForm" id="usersAddForm" action="" method="post">
    <section class="content">
      <div class="row"> 
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Added Center List</h3>
              <div class="pull-right"><a href="<?php echo base_url();?>iibfdra/Center" class="btn btn-success">Add New Center</a>&nbsp;   &nbsp; <a href="<?php echo base_url();?>iibfdra/CenterRenew/regular" class="btn btn-warning">Renew Regular Center</a> </div>
              <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <?php if($this->session->flashdata('error')!=''){?>
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('error'); ?> </div>
              <?php } if($this->session->flashdata('success')!=''){ ?>
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('success'); ?> </div>
              <?php } ?>
              <table id="listitems" class="table table-bordered table-striped Tables-example table-hover">
                <thead>
                  <tr>
                    <th id="srNo">S.No.</th>
                    <th id="centername">Name Of Location(City)</th>
                    <th id="ststus">Status</th>
                    <th id="name">Contact Person Name</th>
                    <th id=centertype>Center Type</th>
                    <th id=accradation>Accreditation Period</th>
                    <th id=accradation>Status</th>
                    <th id="batchaction">Action</th>
                  </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                  <?php if( count( $center_listing )  > 0 ) { 
                          $i = 1;
                          foreach($center_listing as $center ) { ?>
                  <tr>
                    <td><?php echo $i;?></td>
                    <td>
					<?php
          $is_approve_status = '';
					$today_day = date('Y-m-d');					
					$to_date =  strtotime(date('Y-m-d',strtotime($center['center_validity_to'])));				
					$today_date = strtotime($today_day);
					
					$update_date = strtotime(date('Y-m-d',strtotime($center['modified_on'])));	
					$exp_class = '';					
					if($to_date < $today_date){
						$expire_str = ' <span class="exp_font">(Expired)</span> ';
						$exp_class = 'redclass';
					}else{
						$expire_str = '';
						$exp_class = '';	
					}
					
					if($update_date > $to_date){
						$update_done = 1;
					}else{
						$update_done = 0;
					}
					
					if($center['center_validity_to'] == ''){
						$expire_str = '';
						$exp_class = '';	
					}
                  
                    if($center['city_name'] == ""){
                          echo $center['location_name'];
                      } 
                      else
                      {
                      echo $center['city_name'];
                    }?></td>
                     
                    <td>
					<?php if($center['center_status'] == 'IR' || $center['center_status'] == 'AR'){ 
          echo '<span style="color:#00C;">InReview</span>' ;
          }elseif($center['center_status'] == 'R'){ 
          echo '<span style="color:#F00;">Rejected</span>' ;
          }else{             
           if($update_done == 1 ){
            $is_approve_status = 1;
            echo  '<span style="color:#093;">Approved</span>' ; 
           }else{
            
             if($expire_str != ''){
                echo $expire_str;
               }else{
                $is_approve_status = 1;
              echo  '<span style="color:#093;">Approved</span>' ;    
              }
            }
          }   
          ?>
                    </td>
                    <td><?php echo $center['contact_person_name'];?></td>
                    <td><?php if($center['center_type'] == 'T'){ echo 'Temporary';} else { echo 'Regular'; } ?></td>
                    <td class="<?php echo $exp_class; ?>"><?php if( $center['center_validity_to'] != '' &&  $center['center_validity_to'] != '0000-00-00' )  {?>
                     FROM <strong> <?php  if( $center['center_validity_from'] != '' &&  $center['center_validity_from'] != '0000-00-00' )  {  echo date_format(date_create($center['center_validity_from']),"d-M-Y"); } else{ echo '--'; } ?>
                     </strong> TO <strong>
					 <?php  if( $center['center_validity_to'] != '' &&  $center['center_validity_to'] != '0000-00-00' )  { echo date_format(date_create($center['center_validity_to']),"d-M-Y");}else{ echo '--'; } ?>
                     </strong>
                     <?php  }else{ ?>
                    Accreditation  Period Not Added
                     <?php   }?></td>
                    
                    <td>
                      <?php 
                      if(count($dra_accerdited_data) > 0)
                      {
                        if($_SESSION['dra_institute']['institute_code'] == '257') 
                        { 
                          $dra_accerdited_data[0]['address3'] = $dra_accerdited_data[0]['address4'] = $dra_accerdited_data[0]['address6'] = 'new delhi'; 
                        }

                        if($is_approve_status == '1' &&
                          (strtolower($dra_accerdited_data[0]['address3']) == strtolower($center['location_name']) ||
                          strtolower($dra_accerdited_data[0]['address3']) == strtolower($center['city_name']) ||
                          strtolower($dra_accerdited_data[0]['address3']) == strtolower($center['state_name']) ||
                          
                          strtolower($dra_accerdited_data[0]['address4']) == strtolower($center['location_name']) ||
                          strtolower($dra_accerdited_data[0]['address4']) == strtolower($center['city_name']) ||
                          strtolower($dra_accerdited_data[0]['address4']) == strtolower($center['state_name']) ||
                          
                          strtolower($dra_accerdited_data[0]['address6']) == strtolower($center['location_name']) || 
                          strtolower($dra_accerdited_data[0]['address6']) == strtolower($center['city_name']) ||
                          strtolower($dra_accerdited_data[0]['address6']) == strtolower($center['state_name']) ||
                          
                          (strtolower($dra_accerdited_data[0]['state_name']) != "" && strtolower($dra_accerdited_data[0]['state_name']) == strtolower($center['check_city_state_for_active'])))
                        )
                        { echo 'Active'; } 
                        else { echo 'Inactive'; }
                      } 
                      ?>
                    </td>

                    <td>
                    <?php 
					// add cundition to show renw table for Temporary center by Manoj 
					
					if($center['center_validity_to'] != ''&& $center['center_validity_to'] != '0000-00-00' && $to_date < $today_date && $center['center_type'] == 'T'){ ?>
					  <a href="<?php echo base_url().'iibfdra/CenterRenew/view/'.$center['center_id'];?>">View</a>	
					 
                     <?php if($center['is_renew'] != '1'){ ?>
                   | &nbsp; <a href="<?php echo base_url().'iibfdra/CenterRenew/edit/'.$center['center_id'];?>">Renew</a>
                   <?php }elseif($center['is_renew'] == '1' && $center['center_status'] == 'R'){ ?>
					| &nbsp; <a href="<?php echo base_url().'iibfdra/CenterRenew/renew_edit/'.$center['center_id'];?>">edit</a>   
					<?php }elseif($center['pay_status'] == '1') { ?> 
                  	| <strong style="color:#090">Paid</strong>
             		<?php } ?>
                     
                    <?php /*?>  <?php if(($center['center_status'] == 'IR' ||  $center['center_status'] == 'R' || $center['center_status'] == 'A') && ( $center['center_add_status']!='F')) { ?>
                       <a href="<?php echo base_url();?>iibfdra/CenterRenew/edit/<?php echo $center['center_id'];?>"> |Renew</a>
                      <?php } ?><?php */?>
					<?php }  else{ ?>
                    
                    <a href="<?php echo base_url().'iibfdra/Center/view/'.$center['center_id'];?>">View</a>
                   	<?php if(($center['center_status'] == 'IR' ||  $center['center_status'] == 'R') && ( $center['center_add_status']!='F')) {?>
                     <a href="<?php echo base_url();?>iibfdra/Center/edit/<?php echo $center['center_id'];?>"> | Edit</a>
                     <?php } else {
                      
                     }
					 
					 if($center['pay_status'] == '1') { ?> 
                  	| <strong style="color:#090">Paid</strong>
             		<?php } ?>
					<?php  
					}
                    ?>
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
.exp_font{
 font-size:13px;
 color:#600;	
}
.redclass{
color:#C30;	
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