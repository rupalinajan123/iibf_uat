<?php $this->load->view('iibfdra/Version_2/admin/includes/header');?>
<?php $this->load->view('iibfdra/Version_2/admin/includes/sidebar');?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Agency (<?php echo ucfirst($result['inst_name']); ?>) Center Receipt Details 
      <!-- <a class="btn btn-primary right" href="<?php //echo base_url().'iibfdra/agency/' ; ?>">Agency listing</a>--> 
    </h1>
    <?php echo $breadcrumb;	
	//print_r($agency_center_logs);		
	?> </section>
  <div class="col-md-12"> <br />
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
  <!-- Main content -->
 
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info box-solid disabled">
          <div class="box-header with-border">
            <h3 class="box-title">Traning Center Details</h3>
           <div class="pull-right">
                <a href="<?php echo base_url()?>iibfdra/Version_2/agency/training_center_detail/<?php echo $result['center_id']; ?>" class="btn btn-warning button">Back</a> 
              </div>
            <!-- /.box-tools --> 
          </div>
          <!-- /.box-header -->
          <div class="box-body" style="display: block;">
            <?php 
						$div_class = '';
						$div_class2 = '';
						$str_btn = '';
						$status_text =  '';
						$drauserdata = $this->session->userdata('dra_admin');
						$user_type_flag = $drauserdata['roleid'];						
						
					   if($result['center_status'] == 'A' ){ 
					        if( $result['pay_status'] == '1'){
							//$status_text =  'Approved (A)'; 
							 if($result['center_validity_to'] != ''){
								 if( $result['is_renew'] ==1 ){
									$status_text =  'Accreditation Assigned  &nbsp; &nbsp; <a class="assign_acc btn btn-warning"  href="javascript:void(0);">Renew Accreditation</a>';
								 }else{
									$status_text =  'Accreditation Assigned  &nbsp; &nbsp; <a class="assign_acc btn btn-primary "  href="javascript:void(0);">Update Accreditation</a>'; 
								}
								 
								}else{
									$status_text =  'Assign Accreditation Period &nbsp; &nbsp;  <a class="assign_acc btn btn-primary "  href="javascript:void(0);">Assign Accreditation</a>' ;	
								}
							//$str_btn = '<textarea name="rejection" class="rejection" rows="5" cols="40" placeholder="Describe rejection reason here"></textarea></br><a class="reject_center btn btn-danger " dataid ="'.$result['center_id'].'" href="javascript:void(0);">Reject</a>';	
							}else{
							
					   		$status_text =  'Approved (A)'; 
							$str_btn = '';
							//$str_btn = '<textarea name="rejection" class="rejection" rows="5" cols="40" placeholder="Describe rejection reason here"></textarea></br><a class="reject_center btn btn-danger " dataid ="'.$result['center_id'].'" href="javascript:void(0);">Reject</a>';				
							$div_class = '#d4edda';
							$div_class2 = '#270';
							}
					   }elseif($result['center_status'] == 'IR' ){ 
					   		$status_text =  'In Review'; 
							$str_btn = '<a class="approve_center btn btn-success " dataid ="'.$result['center_id'].'" href="javascript:void(0);">Approve</a>&nbsp;&nbsp;<a class="reject_center btn btn-danger " dataid ="'.$result['center_id'].'" href="javascript:void(0);">Reject</a>&nbsp;<textarea name="rejection" class="rejection" rows="5" cols="40" placeholder="Describe rejection reason here"></textarea>';
							$div_class = '#f8d7da';
							$div_class2 = '#9F6000';
					   }elseif($result['center_status'] == 'AR' ){ 
					   		$status_text =  'Approved (R)'; 
							$str_btn = '<a class="approve_center btn btn-success " dataid ="'.$result['center_id'].'" href="javascript:void(0);">Approve</a>&nbsp; &nbsp;<a class="reject_center btn btn-danger " dataid ="'.$result['center_id'].'" href="javascript:void(0);">Reject</a>&nbsp;<textarea name="rejection" class="rejection" rows="5" cols="40" placeholder="Describe rejection reason here"></textarea>';
							$div_class = '#f8d7da';
							$div_class2 = '#9F6000';
					   }elseif($result['center_status'] == 'R' ){ 
					   
					   		$status_text =  'Rejected';
							// Hide Appprove button till Agency update center Details.
							if($is_center_update){
								$str_btn = '<a class="approve_center btn btn-success " dataid ="'.$result['center_id'].'" href="javascript:void(0);">Approve</a>';
							}else{
								$str_btn = '';
							}
							
								$div_class = '#f8d7da';
							$div_class2 = '#D8000C';
					   }else { 
					   		$status_text =  'Rejected'; 
							$str_btn = '<a class="approve_center btn btn-success " dataid ="'.$result['center_id'].'" href="javascript:void(0);">Approve</a>';
							$div_class = '#f8d7da';
							$div_class2 = '#D8000C';
					   }					   
					   $div_class = '';	
					   
					   if( $result['center_status'] == 'AR' && $user_type_flag == 2 ){
						   
						   $status_text =  'Approved (R)'; 
						  $str_btn = '<textarea name="rejection" class="rejection" rows="5" cols="40" placeholder="Describe rejection reason here"></textarea></br><a class="reject_center btn btn-danger " dataid ="'.$result['center_id'].'" href="javascript:void(0);">Reject</a>';		
						    
						   }
						   
						 //  echo 'asdfasdf ss :'.$result['center_add_status'];
						   
						   if($result['center_add_status'] == 'F' && $result['center_status'] != 'R' ){
							  // echo 'here';
							    if($result['center_validity_to'] != ''){
									$status_text =  'Accreditation Assigned &nbsp; &nbsp; <a class="assign_acc btn btn-primary "  href="javascript:void(0);">Update Accreditation</a>';
									
									 $str_btn = '';
									 
								}else{
									$status_text =  'Assign Accreditation Period &nbsp; &nbsp;  <a class="assign_acc btn btn-primary "  href="javascript:void(0);">Assign Accreditation</a>';
									
									 $str_btn = '<textarea name="rejection" class="rejection" rows="5" cols="40" placeholder="Describe rejection reason here"></textarea></br><a class="reject_center btn btn-danger " dataid ="'.$result['center_id'].'" href="javascript:void(0);">Reject</a>';	
								}
						  	
							 }
							 
							  if($result['center_add_status'] == 'F' && $result['center_status'] == 'R' ){
								  
							   $str_btn = '';
							   $status_text =  ' Rejected ';
							  }
					   //$div_class2 = '';			  
				?>
           
              <div class="table-responsive ">
                <table class="table table-bordered" style="background:<?php echo $div_class; ?>;">
                  <tbody>
                    <!-- <tr>                      
                        <td width="50%"><strong>Agency Name :</strong></td>
                        <td width="50%"><?php //echo $result['inst_name']; ?></td>
                      </tr> -->
 
                    <tr>
                      <td width="50%"><strong>Training Center Location :</strong></td>
                      <td width="50%">
					  <?php if($result['city_name'] != ''){ 
					  echo $result['city_name'];  
					  }else {
						echo  $result['location_name'];
					  }?></td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Address :</strong></td>
                      <td width="50%"><?php echo $result['location_address'].' '.$result['address1'].' '.$result['address2'].' '.$result['address3'].' '.$result['address4'].' '.$result['district']; ?>
                      
                      <?php if($result['city_name'] != ''){ 
					  echo $result['city_name'];  
					  }else {
						echo    $result['city'];
						   }?>
                      <?php echo $result['state_name'].' '.$result['pincode']; ?></td>
                    </tr>
                                
                    <tr>
                      <td width="50%"><strong>Centre Type :</strong></td>
                      <td width="50%"><?php					  
					  if( $result['center_type'] == 'R'){
						  echo 'Regular';
						  }else{
						  echo 'Temporary';  
						 } ?></td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Valid From To Valid To :</strong></td>
                      <td width="50%"><?php 
					 	//echo $result['center_validity_to'];
					  if( $result['center_validity_to'] != '')	{?>
                        FROM <strong><?php echo date_format(date_create($result['center_validity_from']),"d-M-Y"); ?> </strong> TO <strong><?php echo date_format(date_create($result['center_validity_to']),"d-M-Y"); ?></strong>
                        <?php  }else{ ?>
                        <strong>-Accreditation Period Not Added-</strong>
                        <?php   }
					  
					  ?></td>
                    </tr>
                   
                    <tr>
                      <td width="50%"><strong>Payment Receipt :</strong></td>
                      <td width="50%"><span class="pay_span"><strong>
                      <ol class="r_list">
                        <?php	
						// echo 'status :'.$result['pay_status'];
						  /*if( $result['pay_status'] == '0'){
							  echo 'Fail';
							}elseif( $result['pay_status'] == '1'){
							  echo 'Payment Done ';  
							}elseif( $result['pay_status'] == '2'){
							  echo 'Payment Pending ';  
							}elseif( $result['pay_status'] == '3'){
							  echo 'Refund ';  
							}elseif( $result['pay_status'] == '4'){
							  echo ' Refund Pending ';  
							}else{
								echo 'Payment Pending '; 
							}*/ 
						
						?>
                        <?php 	
						if( $result['pay_status'] == '1' ){	
							if($result['invoice_image'] != ''){							
								echo ' <li > &nbsp; &nbsp; <a class="btn btn-info btn-xs vbtn" target="_blank" href="'.base_url().'uploads/drainvoice/supplier/'.$result['invoice_image'].'"> &nbsp; &nbsp;View Receipt <i class="fa fa-credit-card"></i> &nbsp; &nbsp;</a> ON '.date_format(date_create($result['date_of_invoice']),"d-M-Y").' </li>';
							}else{
														
								}
								//echo  $result['invoice_image'];
								//echo  $result['transaction_id'];
							}							
							
							?>
                            
                            <?php 
							if(count($renew_receipt)>0){
								
								foreach($renew_receipt as $res){
									
									if($res['invoice_image'] != ''){	
															
											echo '<li> &nbsp; &nbsp; <a class="btn btn-info btn-xs vbtn" target="_blank" href="'.base_url().'uploads/agency_renewal_invoice/user/'.$res['invoice_image'].'"> &nbsp; &nbsp;View Renew Receipt <i class="fa fa-credit-card"></i> &nbsp; &nbsp;</a> ON '.date_format(date_create($res['date_of_invoice']),"d-M-Y").' </li>';
											
									}
								}
							}
							?>
                            
                          </ol>  
                        </strong></span></td>
                    </tr>
                    
                  </tbody>
                </table>
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
<style>
.r_list li{
margin-bottom:11px;	
}
.box.box-solid > .box-header .btn:hover, .box.box-solid > .box-header a:hover{
background-color: #f39c12;
border-color: #e08e0b;
background: #f39c12 !important;
}
</style>
 <script>
    if ( window.history.replaceState ) {
      window.history.replaceState( null, null, window.location.href );
    }
</script>
<?php $this->load->view('iibfdra/Version_2/admin/includes/footer');?>
