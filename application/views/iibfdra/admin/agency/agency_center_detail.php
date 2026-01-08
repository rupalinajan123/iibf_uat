<?php $this->load->view('iibfdra/admin/includes/header');?>

<?php $this->load->view('iibfdra/admin/includes/sidebar');?>

<!-- Content Wrapper. Contains page content -->



<div class="content-wrapper"> 

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h1> Agency (<?php echo ucfirst($result['inst_name']); ?>) Center Details 

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

 

 

  <?php 

				// Log table information code Start//

				$k = 1;

				$str = '';

				$reject_action_date = '';

				$is_center_update = 0;

				$agency_center_logs_length = count($agency_center_logs);

				if($agency_center_logs_length > 0){

					foreach($agency_center_logs as $res_log){

						

						$log_data = unserialize($res_log['description']);

						$pre_text = '';

						

						if(isset($res_log['userid'])){	

							$admin_name = $res_log['name'];

						}else{

							$admin_name = '';

						}

						

						if(isset($log_data['rejection'])){	

								//$pre_text = 'Rejected by';						

								$rejection_reasion = '<span class="red">  Rejection Reason: '.$log_data['rejection'].'</span>';

							/*if(!$agency_center_logs_length ){

								$reject_action_date = $res_log['date'];

							}*/

							if($k == 1){

								$reject_action_date = $res_log['date'];

							}

							

							}else{

								$rejection_reasion = '';	

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

						

						if(isset($log_data['center_validity_to'])){

							

							$pre_text = 'Updated Accreditation ';

							$Accridation_text = ' : '.date_format(date_create($log_data['center_validity_from']),"d-M-Y").' - '.date_format(date_create($log_data['center_validity_to']),"d-M-Y");

						}else{

							

							$Accridation_text = '';	

						}

						

						if(isset($log_data['update_accreditation_reason'])){							

							$rejection_reasion =  ' Accreditation Updated: '.$log_data['update_accreditation_reason'];							

							

						}

					$str .='<tr><td>'.$k.' </td>';				

					$str .='<td>'.$res_log['title'].' '.$Accridation_text.' -  '.$update_by.' </td>';

					$str .='<td>'.date_format(date_create($res_log['date']),"d-M-Y h:i:s").' </td>';

					$str .='<td> '.$rejection_reasion. '</td></tr>';

					$k++;	

				}

			}

			

			if($reject_action_date != '' && $result['center_status'] == 'R'){

				//modified_on

				// Flag set Hide Appprove button till Agency update center Details. 

				// value 0 : Hide approve button & 1 show Approve button

				$reject_action_date = date('Y-m-d H:i',strtotime( $reject_action_date));

				$modified_on = date('Y-m-d H:i',strtotime($result['modified_on']));

				

				

				

				if($modified_on == $reject_action_date ){

					$is_center_update = 0;				 

				}else{

					$is_center_update = 1;	

				}

				

			 }

			 // Log table information code end//

			// Testing code to check approve butto

			/*echo '<br>center_status >>'.$result['center_status'];

			echo '<br> reject_action_date >> '.$reject_action_date;

			echo '<br> modified_on >> '.$modified_on;

			echo '<br>is_center_update >>'.$is_center_update ;*/			

			?>

 

  <section class="content">

    <div class="row">

      <div class="col-md-12">

        <div class="box box-info box-solid disabled">

          <div class="box-header with-border">

            <h3 class="box-title">Traning Center Details</h3>

            <div class="box-tools pull-right">

              <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>

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

								 if( $result['is_renew'] == 1 && $result['center_type'] == 'T' ){

									$status_text =  'Accreditation Assigned  &nbsp; &nbsp; <a class="assign_acc btn btn-warning"  href="javascript:void(0);">Renew Accreditation</a>';

								 }else if( $result['center_validity_to'] >= date('Y-m-d')){

									$status_text =  'Accreditation Assigned  &nbsp; &nbsp; <a class="assign_acc btn btn-primary "  href="javascript:void(0);">Update Accreditation</a>';

								 }else{

									 $status_text =  'Accreditation Period Expired';

								}

								 

								}else{

									$status_text =  'Assign Accreditation Period &nbsp; &nbsp;  <a class="assign_acc btn btn-primary "  href="javascript:void(0);">Assign Accreditation</a>';	

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

							$str_btn = '<textarea name="rejection" class="rejection" maxlength="300" rows="5" cols="40" placeholder="Describe rejection reason here"></textarea><a class="approve_center btn btn-success " dataid ="'.$result['center_id'].'" href="javascript:void(0);">Approve</a>&nbsp;&nbsp;<a class="reject_center btn btn-danger " dataid ="'.$result['center_id'].'" href="javascript:void(0);">Reject</a>&nbsp;';

							$div_class = '#f8d7da';

							$div_class2 = '#9F6000';

					   }elseif($result['center_status'] == 'AR' ){ 

					   		$status_text =  'Approved (R)'; 

							$str_btn = '<textarea name="rejection" class="rejection" maxlength="300" rows="5" cols="40" placeholder="Describe rejection reason here"></textarea><a class="approve_center btn btn-success " dataid ="'.$result['center_id'].'" href="javascript:void(0);">Approve</a>&nbsp; &nbsp;<a class="reject_center btn btn-danger " dataid ="'.$result['center_id'].'" href="javascript:void(0);">Reject</a>&nbsp;';

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

									

									$today_day = date('Y-m-d');					

									$to_date =  strtotime(date('Y-m-d',strtotime($result['center_validity_to'])));				

									$today_date = strtotime($today_day);

						

									 if($to_date < $today_date){

											 $status_text =  'Accreditation Period Expired';	

									  }else{

										  

										  	$status_text =  'Accreditation Assigned &nbsp; &nbsp; <a class="assign_acc btn btn-primary "  href="javascript:void(0);">Update Accreditation</a>';										  

									 }

									

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

            <form method="post" name="approve_center_from" id="approve_center_from" >

              <input type="hidden" id="center_status" name="center_status" value="<?php echo $result['center_status'] ?>" />

              <input type="hidden" id="center_add_status" name="center_add_status" value="<?php echo $result['center_add_status'] ?>" />

              <input type="hidden" name="agency_id" value="<?php echo $result['agency_id'] ?>" />

              <input type="hidden" id="action_status" name="action_status" value="" />

              <input type="hidden" name="action" value="update_status" />

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

                      <td width="50%"><strong>Office No :</strong></td>

                      <td width="50%"> <?php if($result['stdcode']!=''){ echo $result['stdcode']; }else{ echo '-';}; ?>&nbsp; <?php if($result['office_no'] != ''){ echo $result['office_no']; } else {  echo '--'; } ?></td>

                    </tr>                    

                    <tr>

                      <td width="50%"><strong>Contact Person Name :</strong></td>

                      <td width="50%"> <?php if($result['contact_person_name'] != ''){ echo $result['contact_person_name']; } else {  echo '--'; } ?> </td>

                    </tr>

                     <tr>

                      <td width="50%"><strong>Mobile No :</strong></td>

                      <td width="50%"><?php if($result['contact_person_mobile'] != '')

					  { echo $result['contact_person_mobile']; } else {  echo '--'; } ?></td>

                    </tr>

                    

                     <tr>

                      <td width="50%"><strong>Email Id :</strong></td>

                      <td width="50%"><?php if($result['email_id'] != '')

					  { echo $result['email_id']; } else {  echo '--'; } ?></td>

                    </tr> 

                    

                      <tr>

                      <td width="50%"><strong>Due Diligence :</strong></td>

                      <td width="50%"><?php if($result['due_diligence'] != '')

					  { echo $result['due_diligence']; } else {  echo '--'; } ?></td>

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

                      <td width="50%"><strong>GSTIN No :</strong></td>

                      <td width="50%"><?php					  

					
						  echo $result['gstin_no'];

						   ?></td>

                    </tr>
                    <tr>

                      <td width="50%"><strong>Date Of Approved :</strong></td>

                      <td width="50%"><?php if($result['date_of_approved'] != ''){ 

					 	 echo date_format(date_create($result['date_of_approved']),"d-M-Y"); 

					 	} else {  

					  	echo '--'; 

						} ?></td>

                    </tr>

                    

                     

                    <?php if($result['center_type'] == 'T') {?>

                     <tr>

                      <td width="50%"><strong>Faculty name ( Qualification ) & CV :</strong></td>

                      <td width="50%"><?php //echo $result['faculty_name1']; //cv1 ?> <ol class="custom-counte">

					  <?php if($result['faculty_name1'] != '')

					  		{ 

								echo '<li>'.$result['faculty_name1'] .' ('.$result['faculty_qualification1'].')';

							 	if($result['cv1'] != ''){

									echo '&nbsp; <a class="btn1 btn-info btn-xs vbtn" target="_blank" href="'.base_url().'uploads/iibfdra/agency_center/faculty_cv/'.$result['cv1'].'">&nbsp;View CV &nbsp;<i class="fa fa fa-file"></i> &nbsp;</a>'; 

								 }

								echo '</li>';

							

							}

					  		if($result['faculty_name2'] != '')

							{ 

								echo '<li>'.$result['faculty_name2'].' ('.$result['faculty_qualification2'].')';

							

								if($result['cv2'] != ''){

									echo '&nbsp; <a class="btn1 btn-info btn-xs vbtn" target="_blank" href="'.base_url().'uploads/iibfdra/agency_center/faculty_cv/'.$result['cv2'].'">&nbsp;View CV &nbsp;<i class="fa fa fa-file"></i> &nbsp;</a>'; 

							 	}

								echo '</li>';

							}

							if($result['faculty_name3'] != '')

							{ 

								echo '<li>'.$result['faculty_name3'].' ('.$result['faculty_qualification3'].')';

							

								if($result['cv3'] != ''){

									echo '&nbsp; <a class="btn1 btn-info btn-xs vbtn" target="_blank" href="'.base_url().'uploads/iibfdra/agency_center/faculty_cv/'.$result['cv3'].'">&nbsp;View CV &nbsp;<i class="fa fa fa-file"></i> &nbsp;</a>'; 

							 	}

								echo '</li>';

							}

							if($result['faculty_name4'] != '')

							{ 

								echo '<li>'.$result['faculty_name4'].' ('.$result['faculty_qualification4'].')';

							

								if($result['cv4'] != ''){

									echo '&nbsp; <a class="btn1 btn-info btn-xs vbtn" target="_blank" href="'.base_url().'uploads/iibfdra/agency_center/faculty_cv/'.$result['cv4'].'">&nbsp;View CV &nbsp;<i class="fa fa fa-file"></i> &nbsp;</a>'; 

							 	}

								echo '</li>';

							}

							if($result['faculty_name5'] != '')

							{ 

								echo '<li>'.$result['faculty_name5'].' ('.$result['faculty_qualification5'].')';

							

								if($result['cv5'] != ''){

									echo '&nbsp; <a class="btn1 btn-info btn-xs vbtn" target="_blank" href="'.base_url().'uploads/iibfdra/agency_center/faculty_cv/'.$result['cv5'].'">&nbsp;View CV &nbsp;<i class="fa fa fa-file"></i> &nbsp;</a>'; 

							 	}

								echo '</li>';

							}

							

							/*if($result['faculty_name4'] != '')

							{ echo '<li>'.$result['faculty_name4'].' ('.$result['faculty_qualification4'].') </li>'; }

							if($result['faculty_name5'] != '')

							{ echo '<li>'.$result['faculty_name5'].' ('.$result['faculty_qualification5'].') </li>'; }*/

					  ?>

                      </ol>

                      </td>

                    </tr>

                  

                    <tr>

                      <td width="50%"><strong>Request Letter From Accredited Institute :</strong></td>

                      <td width="50%"><?php if($result['upload_file1'] != ''){ 

					  echo '  &nbsp; &nbsp; <a class="btn btn-info btn-xs vbtn" target="_blank" href="'.base_url().'uploads/iibfdra/agency_center/'.$result['upload_file1'].'"> &nbsp; &nbsp;View document &nbsp;<i class="fa fa fa-book"></i> &nbsp; &nbsp;</a>'; } else {  echo '--'; } ?></td>

                    </tr>  

                    

                    <tr>

                      <td width="50%"><strong>Letter From The Sponsoring Agency :</strong></td>

                      <td width="50%"><?php if($result['upload_file2'] != ''){ 

					   echo '  &nbsp; &nbsp; <a class="btn btn-info btn-xs vbtn" target="_blank" href="'.base_url().'uploads/iibfdra/agency_center/'.$result['upload_file2'].'"> &nbsp; &nbsp;View document &nbsp;<i class="fa fa fa-book"></i> &nbsp; &nbsp;</a>';

					 // echo $result['agency_letter'];

					   } else {  echo '--'; } ?></td>

                    </tr> 

                    

                    <?php } ?>

                    

                     <tr>

                      <td width="50%"><strong> Remarks by Agency :</strong></td>

                      <td width="50%"><?php if($result['remarks'] != ''){ 

					  echo $result['remarks']; } else {  echo '--'; } ?></td>

                    </tr>  

                    

                    

                    <tr>

                      <td width="50%"><strong>Valid From To Valid To :</strong></td>

                      <td width="50%"><?php 

					 	//echo $result['center_validity_to'];

					  if( $result['center_validity_to'] != '')	{?>

                        FROM <strong><?php if( $result['center_validity_from'] != ''){ echo date_format(date_create($result['center_validity_from']),"d-M-Y");}else{ echo '--';} ?> </strong> TO <strong><?php echo date_format(date_create($result['center_validity_to']),"d-M-Y"); ?></strong>

                        <?php  }else{ ?>

                        <strong>-Accreditation Period Not Added-</strong>

                        <?php   }

					  ?></td>

                    </tr>

                   

                    <tr>

                      <td width="50%"><strong>Payment Status :</strong></td>

                      <td width="50%"><span class="pay_span"><strong>

                        <?php		

						//echo 'status :'.$result['pay_status'];

									  

					  if( $result['pay_status'] == '0'){

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

							 } ?>

                        <?php 	

						if( 1 ){	

							if($result['invoice_image'] != ''){							

								//echo '  &nbsp; &nbsp; <a class="btn btn-info btn-xs vbtn" target="_blank" href="'.base_url().'uploads/drainvoice/supplier/'.$result['invoice_image'].'"> &nbsp; &nbsp;View Receipt <i class="fa fa-credit-card"></i> &nbsp; &nbsp;</a>';

								echo '  &nbsp; &nbsp; <a class="btn btn-info btn-xs vbtn" target="_blank" href="'.base_url().'iibfdra/agency/training_center_receipt/'.$result['center_id'].'"> &nbsp; &nbsp;View Receipts <i class="fa fa-credit-card"></i> &nbsp; &nbsp;</a>';

								

							}else{

								

								if( $result['center_validity_to'] != '')	{								

								echo '  &nbsp; &nbsp; <a class="btn btn-info btn-xs vbtn" target="_blank" href="'.base_url().'iibfdra/agency/training_center_receipt/'.$result['center_id'].'"> &nbsp; &nbsp;View Receipts <i class="fa fa-credit-card"></i> &nbsp; &nbsp;</a>';					

								}

								

							}

								//echo  $result['invoice_image'];

								//echo  $result['transaction_id'];

							}							

							

							?>

                        </strong></span></td>

                    </tr>

                    <tr>

                      <td width="50%"><strong>Center Status :</strong></td>

                      <td width="50%" style="font-size:16px; color:<?php echo $div_class2; ?>;" ><strong><?php echo ' '.$status_text; ?> </strong></td>

                    </tr>

                    <?php if ($result['center_status'] != 'R') { ?>
	                    <tr>

	                      <td width="50%"><strong>Payment Required :</strong></td>

	                      <td width="50%" style="font-size:16px; ?>;" >
	                      	<?php if (trim($result['payment_required']) == '' || $result['payment_required'] == null) { ?>
	                      	<input type="radio" id="approve_payment" name="payment_required" value="with_payment" /> With Payment
	                      	<input type="radio" id="approve_payment" name="payment_required" value="without_payment" /> Without Payment
	                      	<?php } elseif ($result['payment_required'] == 'with_payment') {
	                      			echo "With Payment";
	                      	} else {
	                      		echo "Without Payment";
	                      	}	?>
	                      </td>

	                    </tr>
	                  <?php } ?>

	                  <?php if ($result['center_status'] == 'IR') { ?>  
	                    <tr>

	                      <td width="50%"><strong>Action :</strong></td>

	                      <td width="50%"><?php echo $str_btn; ?>

	                     

	                      </td>

	                    </tr>
	                  <?php } ?>  

                  </tbody>

                </table>

              </div>

            </form>

          </div>

          <!-- /.box-body --> 

        </div>

        <!-- /.box --> 

      </div>

      <div class="col-xs-12 acc_div" >

        <div class="box">

          <div class="box-header with-border">

            <h3 class="box-title">Accreditation period</h3>

            <div class="box-tools pull-right"> 

              <!-- Collapse Button -->

              <button type="button" class="btn btn-box-tool" data-widget="collapse"> <i class="fa fa-minus"></i> </button>

            </div>

            <!-- /.box-tools --> 

          </div>

          <!-- /.box-header -->

          <div class="box-body">

            <div class="table-responsive">

              <form name="accridation_date" id="add_date" method="POST">

                <?php if( $result['center_validity_to'] != '')	{

						  $todate =  date_format(date_create($result['center_validity_to']),"d-m-Y"); 

					  }else{

						   $todate ='';

						  }

					if( $result['center_validity_from'] != '')	{

						  $fromdate =  date_format(date_create($result['center_validity_from']),"d-m-Y"); 

					  }else{

						   $fromdate ='';

						  }

						  ?>

                  <input type="hidden" id="center_add_status" name="center_add_status" value="<?php echo $result['center_add_status'] ?>" />

                  

                  		<input type="hidden" value="<?php echo $fromdate; ?>" id="old_accridation_from_date" /> 

                		<input type="hidden" value="<?php echo $todate; ?>" id="old_accridation_date" />

                        <input type="hidden" value="<?php echo $result['is_renew']; ?>" name="is_renew" id="is_renew" />

                		

               

                

                <table class="table table-bordered" style="background:<?php echo $div_class; ?>;">

                  <tbody>

                    <?php					  

                              if( $result['center_type'] == 'R'){  

                              $current_year = date('Y');

                              $next_year = $current_year+1;

                              $next_next_year = $next_year+1;

                              ?>

                  <td width="50%"><strong>Enter From Date - To date Of Accreditation period </strong></td>

                    <td width="50%">

                      From date : <input type="text" class="center_validity form-control" name="center_validity_from_date" id="center_validity"  maxlength="10" /> To date : 

                      <select class="center_validity form-control center_validity_to" name="center_validity_to_date" id="center_validity_to_date">

                        <option  value="31-03-<?php echo $next_year;?>" >31 March <?php echo $next_year;?> </option>

                        <option value="31-03-<?php echo $next_next_year;?>" >31 March <?php echo $next_next_year;?></option>

                      </select></td>

                        <script type="text/javascript">						 

                                 //$('#center_validity').datepicker({format: 'dd-mm-yyyy',autoclose: true});

                                 $("#center_validity").datepicker({

                                    autoclose: true, 

                                    format: 'dd-mm-yyyy', 							        

                                    dateFormat: 'dd-mm-yyyy',

                                     onSelect: function(selected) {

                                       //$("#center_validity_to_date").datepicker("startDate", selected)

                                    }							

                                }).attr('readonly', 'readonly');

                                

						</script>

                    <?php  }else{ ?>

                    <td width="50%"><strong> Add Start date Of Accreditation period (to end date will be + 90 days )</strong></td>

                    <td width="50%"> From Date :

                      <input type="text" class="center_validity form-control" name="center_validity_from_date" id="center_validity"  maxlength="10" />

                      <br>

                      To date :

                      <input type="text"  class="center_validity_to center_validity_to_date form-control" name="center_validity_to_date" id="center_validity_to_date"  maxlength="10" /></td>

                    <script type="text/javascript">						 

                                 //$('#center_validity').datepicker({format: 'dd-mm-yyyy',autoclose: true});

                                 $("#center_validity").datepicker({

                                    autoclose: true, 

                                    format: 'dd-mm-yyyy', 							        

                                    dateFormat: 'dd-mm-yyyy',

                                     onSelect: function(selected) {

                                       //$("#center_validity_to_date").datepicker("startDate", selected)

                                    }							

                                }).attr('readonly', 'readonly');

                                

                                $('#center_validity_to_date').datepicker({

                                        autoclose: true, 

                                        format: 'dd-mm-yyyy', 												        

                                        dateFormat: 'dd-mm-yyyy',								

                                        minDate: '+89d',

                                        maxDate: '+30Y'

                                }).attr('readonly', 'readonly');

                                

                                

                                $(document).ready(function () {

                                    $('#center_validity').datepicker();

                                /*	

                                  $("#center_validity_to_date").datepicker({

                                        autoclose: true, 

                                        format: 'dd-mm-yyyy', 												        

                                        dateFormat: 'dd-mm-yyyy',

                                        minDate:'0d'	

                                    }).attr('readonly', 'readonly');*/

                                    

                                    /* $('#center_validity_to_date').datepicker({

                                        autoclose: true, 

                                        format: 'dd-mm-yyyy', 												        

                                        dateFormat: 'dd-mm-yyyy',							

                                        changeMonth: true,

                                        changeYear: true,

                                        yearRange: '2018:2025',	

                                        minDate: '+90d',

                                        maxDate: '+30Y'

                                    });*/

                                    

                                    $('#center_validity_to_date').datepicker({

                                        autoclose: true, 

                                        format: 'dd-mm-yyyy', 												        

                                        dateFormat: 'dd-mm-yyyy',							

                                        changeMonth: true,

                                        changeYear: true,																

                                        minDate: '+89d',

                                        maxDate: '+30Y'

                                    });

                                    

                                    

                                });

                            

                            $('#center_validity').change(function() {

                              var date2 = $('#center_validity').datepicker('getDate', '+89d'); 

							  var vval = $('#center_validity').val();

                              //alert(date2);	

							  if(vval != ''){						  

                              	 date2.setDate(date2.getDate()+89); 								

								 $('#center_validity_to_date').datepicker('setDate', date2);								

								 $('#center_validity_to_date').datepicker('setStartDate', date2);

									 $('#center_validity_to_date').datepicker({

										autoclose: true, 

										format: 'dd-mm-yyyy', 												        

										dateFormat: 'dd-mm-yyyy',										

										startDate: date2,

										minDate: date2

									}).attr('readonly', 'readonly');

							  }

                            });

							

                            

                            </script>

                    <?php	 } ?>

                    

                     <tr>

                    <td width="50%"> Update Accreditation Reason :</td>

                    <td width="50%">

                     <?php 

				if($todate !=''){

					echo '<textarea name="update_accreditation_reason" class="update_accreditation_reason" rows="3" cols="40" placeholder="Describe Update Accreditation Reason"></textarea>';

					}

				?></td>

                  </tr>

                  <tr>

                    <td width="50%"></td>

                    <td width="50%"><a class="add_Accreditation_btn btn btn-primary " dataid ="<?php echo $result['center_id']; ?> " href="javascript:void(0);">Save Accreditation period</a></td>

                  </tr>

                    </tbody>

                  

                </table>

                <input type="hidden" name="action" value="add_date" />

                <input type="hidden" name="center_id" value="<?php echo $result['center_id']; ?>" />

                <input type="hidden" name="center_type" id="center_type" value="<?php echo $result['center_type'];?>" />

                <input type="hidden" name="center_status" value="<?php echo $result['center_status'] ?>" />

                <input type="hidden" name="agency_id" value="<?php echo $result['agency_id'] ?>" />

              </form>

            </div>

          </div>

          <!-- box-footer --> 

        </div>

        <!-- /.box --> 

      </div>

     

      

      <div class="col-xs-12" >

        <div class="box">

          <div class="box-header with-border">

            <h3 class="box-title">Agency center logs</h3>

            <div class="box-tools pull-right"> 

              <!-- Collapse Button -->

              <button type="button" class="btn btn-box-tool" data-widget="collapse"> <i class="fa fa-minus"></i> </button>

            </div>

            <!-- /.box-tools --> 

          </div>

          <!-- /.box-header -->

          <div class="box-body ">

            <div class="table-responsive">

              <table id="listitems_logs" class="table table-bordered table-striped">

                <thead>

                  <tr>

                    <th>S.No.</th>

                    <th>Action</th>

                    <th>Action Date </th>

                    <th>Rejection / Accreditation Update Reason</th>

                  </tr>

                </thead>

                <tbody>

                 <?php

					echo $str;

				?>

                </tbody>

              </table>

            </div>

          </div>

          <!-- box-footer --> 

        </div>

        <!-- /.box --> 

      </div>

      <div class="col-xs-12" style="display:none">

        <div class="box-header">

          <h3 class="box-title">Batch Details for location : [ <?php echo $result['location_name']; ?> ]</h3>

        </div>

        <div class="box">

          <div class="box-body">

            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />

            <input type="hidden" name="base_url_val" id="base_url_val" value="" />

            <div class="table-responsive">

              <table id="listitems" class="table table-bordered table-striped dataTables-example">

                <thead>

                  <tr>

                    <th id="srNo" style="width:8%;">S.No.</th>

                    <th id="location_name">Name</th>

                    <th id="date_of_approved">Date of approved</th>

                    <th id="center_validity_from">Valid From</th>

                    <th id="center_validity_to">Valid To</th>

                    <th id="center_type">Center type</th>

                    <th id="center_status">status</th>

                    <th id="action">Operations</th>

                  </tr>

                </thead>

                <tbody class="no-bd-y" id="list">

                </tbody>

              </table>

              <div id="links" class="dataTables_paginate paging_simple_numbers"> </div>

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

<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script> 

<script type="text/javascript">

  //$('#searchDate').parsley('validate');

</script>

<style>

.red{

 color:red;	

}

.err{

 border:1px solid #F00;	

}

.rejection{

 display:none;	

}

#center_validity{

 width:230px;	

}

#center_validity_to_date{

 width:230px;	

}

.box-header > .box-tools {

    top: 0px !important;

}

table.dataTable th{

	/*text-align:center;*/

	text-transform:capitalize;	

}

table.dataTable thead > tr > th{

	padding-right:2px !important;

}

.table-responsive{

overflow-x:hidden !important;

}

.acc_div{

	display:none;	

}

.rejection{

 width:85%;

 margin:4px;

 clear:both;	

}

</style>

<script src="<?php echo base_url()?>js/js-paginate.js"></script> 

<script>



String.prototype.reverse = function() {

    var str = this,

        newString = new String();

    for (n = str.length; n >= 0; n--) {

        newString += str.charAt(n);

    }

    return newString;

}



$(function () {

	

	$('.approve_center').click(function(){  

		$('#action_status').val('A');		
		var payment_required = $('input[name="payment_required"]:checked').val();
		if (payment_required == '' || payment_required == undefined) {
			alert('Please select payment required field.');
			return false;	
		}
		if (confirm('Are you sure you want to Approve Center?')) {

			$('#approve_center_from').submit();	

		} else {

			return false;

		}			

	});

	

	$('.assign_acc').click(function(){  

			$('.acc_div').show();	

	});

	

	$('.add_Accreditation_btn').click(function(){

		var center_status = $('#center_status').val();	

		$('#action_status').val(''); 	

		if(center_status != 'A'){

		  alert("Please Approve Center Before Adding Accreditation Period")

		  return false;	

		}

		var center_validity = $('#center_validity').val();		

		if(center_validity == ''){

		  alert("Please add Accreditation period")

		   $('#center_validity').addClass('err');

		  return false;	

		}	

		

		var center_validity_to_date = $('#center_validity_to_date').val();	

			

		if(center_validity_to_date == ''){

		  alert("Please add Accreditation period")

		   $('#center_validity_to_date').addClass('err');

		  return false;	

		}

		

		

		

		var old_from_date = $('#old_accridation_from_date').val();

		var old_date = $('#old_accridation_date').val();

		var center_val2 = $('.center_validity_to').val();		

		var center_from_date = $('#center_validity').val();

		var is_renew = $('#is_renew').val();

		

		var update_accreditation_reason = $('.update_accreditation_reason').val();  

		var msg_str =  'Are you sure you want to Add Accreditation period for this Center?';

		console.log(old_from_date+'-to-'+center_from_date);	

		//alert(old_from_date+'----to----'+center_from_date)

		if(old_date != ''){

			if(is_renew){	

				var msg_str =  'Are you sure you want to Renew Accreditation period for this Center?';

			}else{

				var msg_str =  'Are you sure you want to Update Accreditation period for this Center?';		

			}

		console.log(old_date+'--'+center_val2);	

		

			if(old_date == center_val2 && old_from_date == center_from_date){

				 alert("Same Accreditation period is already assigned");

				 return false;	

			}

			

			if(update_accreditation_reason == '' ){

				 alert("Please Enter Accreditation Reason ");

				 return false;	

			}	

					

		}	 	

			

		if (confirm(msg_str)) {

			$('#add_date').submit();	

			//center_status

		} else {

			return false;

		}			

	});

	

	$('.reject_center').click(function(){

		$('#action_status').val('R');

		$('.rejection').show();

		var rejection = $.trim($('.rejection').val()); //$('.rejection').val();

		

		if(rejection == ''){

		  $('.rejection').addClass('err');

		  return false;	

		}

		

		

  		if (confirm('Are you sure you want to Reject Center?')) {

			$('#approve_center_from').submit();	

		} else {

			return false;

		}		

	});

	

	$("#listitems").DataTable();

	$("#listitems_filter").show();

	$("#listitems_logs_filter").show();

	

	var base_url = '<?php echo base_url(); ?>';

	var center_id = '<?php echo $result["center_id"]; ?>';

	

	var listing_url = base_url+'iibfdra/agency/get_tranning_center_list/'+center_id;

		

	paginate(listing_url,'','');

	$("#base_url_val").val(listing_url);

});





var table = jQuery('#listitems_logs').DataTable( {  

	buttons: [

        'print'

    ],

    "paging": true,	

    "ordering": true,

    "autoWidth": false,   

    "columnDefs": [

      { "width": "7%", "targets": 0 },

      { "width": "45%", "targets": 1 },

      { "width": "18%", "targets": 2 },

      { "width": "32%", "targets": 3 }     

    ],

    "rowCallback": function( row, data, index ) {

      //console.log(index, data);

      for(n=6;n<55;n++){

          var color = (data[n] == 1) ? 'green' : ((data[n] == 2) ? 'yellow': ((data[n] == 3) ? 'red' : 'grey'));

          jQuery('td:eq('+n+')', row).css('background-color', color);

          jQuery('td:eq('+n+')', row).css('color', color);

      }

    },

    "headerCallback": function( thead, data, start, end, display ) {

      jQuery(thead).find('th').eq(0).css('width', '300px');

    },

    "drawCallback": function( settings ) {

        var api = new jQuery.fn.dataTable.Api( settings ); 

        // Output the data for the visible rows to the browser's console

        // You might do something more useful with it!

        console.log( api.rows( {page:'current'} ).data() );

    }

       

} );



</script>



 <script>

    if ( window.history.replaceState ) {

      window.history.replaceState( null, null, window.location.href );

    }

</script>

<?php $this->load->view('iibfdra/admin/includes/footer');?>

