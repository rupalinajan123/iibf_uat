<?php $this->load->view('iibfdra/admin/includes/header');?>
<?php $this->load->view('iibfdra/admin/includes/sidebar');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Agency Details 
      </h1>
      <?php echo $breadcrumb; ?>
      
    </section>
	<div class="col-md-12">
    <br />        
    </div>
    <!-- Main content -->
    <section class="content">
      <div class="row">      
      <div class="col-md-12">
          <div class="box box-info box-solid disabled">
            <div class="box-header with-border">
              <h3 class="box-title">Agency Basic Details</h3>
              <div class="box-tools pull-right">
                <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
              </div>
              <!-- /.box-tools --> 
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="display: block;">
               <?php 
					   if($result['status'] == 1 ){ 
					   		$status_text =  'Active'; 
							$str_btn = '<textarea name="reject_reason" class="reject_reason" maxlength="300" rows="5" cols="40" placeholder="Describe deactive reason here"></textarea></br><a class="reject_aj btn btn-danger " dataid ="'.$result['id'].'" href="javascript:void(0);">Click to Deactive</a>';
							
							$div_class = '#d4edda';
							$div_class2 = '#d4edda';
					   }else { 
					   		$status_text =  'Deactive'; 
							$str_btn = '<textarea name="reject_reason" class="reject_reason" maxlength="300" rows="5" cols="40" placeholder="Describe activate reason here"></textarea></br><a class="approve_aj btn btn-success " dataid ="'.$result['id'].'" href="javascript:void(0);">Click to Active</a>';
							//$str_btn = '';
							$div_class = '#f8d7da';
							$div_class2 = '#f8d7da';
					   }
					   
					   $div_class = '';	
					   $div_class2 = '';					  
				?>
              <form method="post" name="appfrom" id="approve_from" >
              <input type="hidden" name="status" value="<?php echo $result['status'] ?>" />
              
              <div class="table-responsive ">
                  <table class="table table-bordered" style="background:<?php echo $div_class; ?>;">
                    <tbody>
                    <tr>                    
                      <td width="50%"><strong>Agency Name :</strong></td>
                      <td width="50%"><?php echo $result['inst_name']; ?></td>
                    </tr> 
                     <tr>                    
                      <td width="50%"><strong>Year Of Establishment :</strong></td>
                      <td width="50%"><?php echo $result['estb_year']; ?></td>
                    </tr>   
                                  
                    <tr>
                      <td width="50%"><strong> Agency Telephone Number / Fax number :</strong></td>
                      <td width="50%"> <?php if($result['inst_stdcode']!=''){ echo $result['inst_stdcode'].' -'; }else{ echo '-';}; ?>&nbsp; <?php if($result['inst_phone']!=''){ echo $result['inst_phone']; }else{ echo '---';}; ?> / 
                       <?php if($result['inst_fax_no']!=''){ echo $result['inst_fax_no']; }else{ echo '---';}; ?></td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Agency Website :</strong></td>
                      <td width="50%"><?php if($result['inst_website']!=''){ echo $result['inst_website']; }else{ echo '---';}; ?></td>
                    </tr>
                     <tr>
                      <td width="50%"><strong>Agency Main Address :</strong></td>
                      <td width="50%"><?php echo $result['main_office_address']; ?> <?php echo $result['main_address1']; ?> <?php echo $result['main_address2']; ?> <?php echo $result['main_address3']; ?> <?php echo $result['main_address4']; ?> <?php echo $result['main_district']; ?> <?php if( $result['city_name'] != ''){ echo $result['city_name']; }else{ echo $result['main_city']; } ; ?> <?php echo $result['state_name']; ?> <?php echo $result['main_pincode']; ?></td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Name Of Director/ Head Of Agency :</strong></td>
                      <td width="50%"><?php echo $result['inst_head_name']; ?></td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Director Contact Number / Email Id :</strong></td>
                      <td width="50%">
                       <?php if($result['inst_head_contact_no']!=''){ echo $result['inst_head_contact_no']; }else{ echo '---';}; ?> /
                        <?php if($result['inst_head_email']!=''){ echo $result['inst_head_email']; }else{ echo '---';}; ?>
					</td>
                    </tr>
                                        
                     <tr>
                      <td width="50%"><strong>Agency Type:</strong></td>
                      <td width="50%"><?php 
						  if($result['inst_type'] = 'R'){
							echo 'Regular'; 
						  }else{
							echo 'Mobile';  
						  }
					  ?></td>
                    </tr>
                     <tr>
                      <td width="50%"><strong>Agency Status:</strong></td>
                      <td width="50%" style="background:<?php echo $div_class2; ?>;"><strong><?php echo $status_text; ?></strong>
					 </td>
                    </tr>

                    <tr>
                      <td width="50%"><strong>GSTIN No:</strong></td>
                      <td width="50%" style="background:<?php echo $div_class2; ?>;"><strong><?php echo $result['gstin_no']; ?></strong>
					 </td>
                    </tr>

                      <tr>
                      <td width="50%"><strong>Action</strong></td>
                      <td width="50%">
					  <?php echo $str_btn; ?></td>
                    </tr>
                    
                  </tbody></table>
              </div>
                </form>
            </div>
          
            <!-- /.box-body --> 
          </div>
          <!-- /.box --> 
        </div>
        
        <div class="col-xs-12">
        <div class="box-header">
 <h3 class="box-title">Training Centers list For Agency : [ <?php echo $result['inst_name']; ?>  ]</h3> </div>
          <div class="box">
          	
            <div class="box-body">            
            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
        	<input type="hidden" name="base_url_val" id="base_url_val" value="" />
            <div class="table-responsive">
			<table id="listitems22" class="table table-bordered table-striped dataTables-example">
                <thead>
                <tr>
                  <th id="srNo" style="width:5%;">S.No.</th>
                  <th id="location_name">Center Location</th>                
                  <th id="date_of_approved">Date Of Approval</th>
                  <th id="center_validity_from">Valid From</th>
                  <th id="center_validity_to">Valid To</th>
                  <th id="gstin">GSTIN No</th>
                  <th id="center_type">Center Type</th>
                  <th id="center_status">Status</th>
                  <th id="action">Operations</th>
                </tr>
                </thead>
                <tbody class="no-bd-y" id="list_centers">  
                    
                 <?php 				 
				$k = 1;
			//	print_r($center_result); die;
				if(count($center_result) > 0){
					foreach($center_result as $res){						
					echo '<tr><td>'.$k.' </td>';
					
					if($res['city_name'] != ''){
						$city = $res['city_name'];
						}else{
						$city = $res['location_name'];	
						}
					echo '<td>'.$city.'</td>'; 
                    
                    if($res['date_of_approved'] != ''){
						$app_date =  date_format(date_create($res['date_of_approved']),"d-M-Y"); 
					} else { 
						$app_date =  '--'; 	
					}
					
					if($res['center_validity_from'] == '' || $res['center_validity_from'] == '0000-00-00' ){
						$validity_from_date = '--';
						}else{
						$validity_from_date = date_format(date_create($res['center_validity_from']),"d-M-Y");	
					}
					
					if($res['center_validity_to'] == '' || $res['center_validity_to'] == '0000-00-00' ){
						$validity_to_date = '--';
						}else{
						$validity_to_date = date_format(date_create($res['center_validity_to']),"d-M-Y");	
					}
					
					
					$today_day = date('Y-m-d');					
					$to_date =  strtotime(date('Y-m-d',strtotime($res['center_validity_to'])));				
					$today_date = strtotime($today_day);
					
					$update_date = strtotime(date('Y-m-d',strtotime($res['modified_on'])));	
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
					
					if($res['center_validity_to'] == ''){
						$expire_str = '';
						$exp_class = '';	
					}
					
					
					if($res['center_status'] == 'A'){
						//$center_status = 'Approved(A)';
						if($update_done == 1 ){							
							$center_status = 'Approved(A)';							
						 }else{							 					
							if($expire_str != ''){
								  $center_status = $expire_str;
							}else{
								$center_status = 'Approved(A)';
							}
						}
						
						}elseif($res['center_status'] == 'IR'){
						$center_status = 'In Review';
					}elseif($res['center_status'] == 'R'){
						$center_status = 'Rejected';
					}elseif($res['center_status'] == 'AR'){
						$center_status = 'Approved(R)';
					}else{
						$center_status = '--';
					}
										
					echo '<td>'.$app_date.' </td>';
					echo '<td class="'.$exp_class.'">'.$validity_from_date.' </td>';
					echo '<td class="'.$exp_class.'">'.$validity_to_date.' </td>';
					echo '<td>'.$res['gstin_no'].' </td>';
					echo '<td>'.$res['center_type'].' </td>';
					echo '<td>'.$center_status.' </td>';
					echo '<td><a class="btn btn-info btn-xs vbtn" href="'.base_url().'iibfdra/agency/training_center_detail/'.$res['center_id'].'" >View</a> </td></tr>';
					$k++;
					}
				}?>                              
                </tbody>
              </table>
              <div id="links" class="dataTables_paginate paging_simple_numbers">             
              </div>
            </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        
        <?php
        
				// Log table information code Start//
				$k = 1;
				$str = '';	
				$reasion ='';
				$agency_log_length = count($agency_log);
				
				//print_r($agency_log);
				
				if($agency_log_length > 0){
					foreach($agency_log as $res_log){
						
						
						
						$log_data = unserialize($res_log['description']);
						//print_r($log_data);
						$pre_text = '';
						
						if(isset($res_log['userid'])){	
							$admin_name = $res_log['name'];
						}else{
							$admin_name = '';
						}
						
						if(isset($log_data['reason'])){							
								$reasion = '<span> Reason: '.$log_data['reason'].'</span>';
						}else{
							$reasion = '';
						}
							
						if(isset($log_data['updated_by'])){
							
						if($log_data['updated_by'] == 1  || $log_data['updated_by'] == 'A'){							
								$update_by = ' by '.$admin_name.' (A) ';
							}else{
								$update_by = ' by '.$admin_name.' 	(R) ';	
							}
						}else{
							$update_by = '';	
						}
						
						
					$str .='<tr><td>'.$k.' </td>';				
					$str .='<td>'.$res_log['title'].' - '.$update_by.' </td>';
					$str .='<td>'.date_format(date_create($res_log['date']),"d-M-Y h:i:s").' </td>';
					$str .='<td> '.$reasion. '</td></tr>';
					$k++;	
				}
			}
		
		?>
        
        <div class="col-xs-12">
        <div class="box-header">
 <h3 class="box-title">Agency Admin logs: [ <?php echo $result['inst_name']; ?>  ]</h3> </div>
          <div class="box">
          	
            <div class="box-body">            
            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
        	<input type="hidden" name="base_url_val" id="base_url_val" value="" />
            <div class="table-responsive">
				<table id="listitems_logs" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>S.No.</th>
                    <th>Action</th>
                    <th>Action Date </th>
                    <th>Activate / Deactivate Reason</th>
                  </tr>
                </thead>
                <tbody>
                 <?php
					echo $str;
				?>
                </tbody>
              </table>
              <div id="links" class="dataTables_paginate paging_simple_numbers">             
              </div>
            </div>
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
<script type="text/javascript">
  //$('#searchDate').parsley('validate');
</script>

<style>
.err{
 border:1px solid #F00;	
}
.exp_font{
 font-size:13px;
 color:#600;	
}
.redclass{
color:#C30;	
}
.reject_reason{
 display:none;	
}

.input_search_data{
 width:100%;	
}
 tfoot {
    display: table-header-group;
}
.vbtn{
padding: 3px 21px;
font-weight: 900;
}
table.dataTable th{
	text-align:center;
	text-transform:capitalize;	
}
</style>

<script src="<?php echo base_url()?>js/js-paginate.js"></script>
<script>
$(function () {
	
	$('.approve_aj').click(function(){  
	
		$('.reject_reason').show();		
		var reject_reason = $.trim($('.reject_reason').val());
		
		if(reject_reason == ''){
		  $('.reject_reason').addClass('err');
		  return false;	
		}
		
  		if (confirm('Are you sure you want to activate Agency?')) {
			$('#approve_from').submit();	
		} else {
			return false;
		}				
	});
	
	$('.reject_aj').click(function(){
		$('.reject_reason').show();		
		var reject_reason = $.trim($('.reject_reason').val());
		
		if(reject_reason == ''){
		  $('.reject_reason').addClass('err');
		  return false;	
		}
		
  		if (confirm('Are you sure you want to deactivate Agency?')) {
			$('#approve_from').submit();	
		} else {
			return false;
		}		
	});
		
	$("#listitems22").DataTable();		
	$("#listitems_filter").show();	
	var base_url = '<?php echo base_url(); ?>';
	var agency_id = '<?php echo $result["id"]; ?>';

});

</script>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>
<?php $this->load->view('iibfdra/admin/includes/footer');?>