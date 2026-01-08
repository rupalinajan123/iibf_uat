<?php $this->load->view('iibfdra/admin/includes/header');?>
<?php $this->load->view('iibfdra/admin/includes/sidebar');?>
<!-- Content Wrapper. Contains page content -->
<style type="text/css">
	.note {
	  color: blue;
	  font-size: small;
	}

	.note-error {
	  color: red;
	  font-size: small;
	}
</style>

 <?php 				
				$k = 1;
				$str = '';
				$reject_action_date = '';
				$is_center_update = 0;
				//$agency_batch_logs = count($agency_batch_logs);
				if(count($agency_batch_logs) > 0){
					
					foreach($agency_batch_logs as $res_log){
						$pre_text = '';	
						$log_data = unserialize($res_log['description']);
						
						
						$log_data = unserialize($res_log['description']);
						$pre_text = '';
						
						if(isset($res_log['userid'])){	
							$admin_name = $res_log['institute_name'].' '.$res_log['name'];
						}else{
							$admin_name = '';
						}
						
						
						if(isset($log_data['rejection'])){	
								//$pre_text = 'Rejected by';						
								$rejection_reasion = '<span class="">'.$log_data['rejection'].'</span>';
							/*if(!$agency_center_logs_length ){
								$reject_action_date = $res_log['date'];
							}*/
							if($k == 1){
								$reject_action_date = $res_log['date'];
							}
							
							}else{
								$rejection_reasion = '';	
							}
						
						/*if(isset($log_data['updated_by'])){							
						
						if($log_data['updated_by'] == 1  || $log_data['updated_by'] == 'A'){
							
								$update_by = ' by '.$admin_name.' (A) ';
							}else{
								$update_by = ' by '.$admin_name.' 	(R) ';	
							}
						}else{
							$update_by = '';	
						}*/
						
						if(isset($log_data['center_validity_to'])){
							
							$pre_text = 'Updated Accreditation ';
							$Accridation_text = ' : '.date_format(date_create($log_data['center_validity_from']),"d-M-Y").' - '.date_format(date_create($log_data['center_validity_to']),"d-M-Y");
						}else{
							
							$Accridation_text = '';	
						}
						
					$str .='<tr><td>'.$k.' </td>';
					//echo '<td>'.$res_log['title'].' </td>';
					$str .='<td>'.str_replace("DRA Admin","",$res_log['title']).' '.$Accridation_text.' </td>';
					$str .='<td>'.date_format(date_create($res_log['date']),"d-M-Y h:i:s").' </td>';
					$str .='<td> '.$rejection_reasion. '</td></tr>';
					$k++;	
				}
			}
			
			
			if($reject_action_date != '' && $result['batch_status'] == 'Rejected'){
				//modified_on
				// Flag set Hide Appprove button till Agency update center Details. 
				// value 0 : Hide approve button & 1 show Approve button
				if($result['updated_on'] == ($reject_action_date.'.000000')){
					$is_center_update = 0;				 
				}else{
					$is_center_update = 1;	
				}
				
			 }
			 
			 //echo 'updated_on >>'.$result['updated_on'];
			// echo '<br> reject_action_date >>'.$reject_action_date;
			//echo '<br> STATUS - flag >>'.$result['batch_status'].'-'.$is_center_update;
			
				?>



<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Training Batch Details </h1>
    <?php //echo $breadcrumb;	  
	 //print_r($result);	

	$drauserdata = $this->session->userdata('dra_admin');
	 ?> </section>
  <div class="col-md-12"> <br />
  </div>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info box-solid disabled">
          <div class="box-header with-border">
            <h3 class="box-title">Training Batch Details</h3>
            <div class="pull-right">
            	<a href="<?php echo base_url();?>iibfdra/batch/" class="btn btn-warning">Back</a>
              	<button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
              
            </div>
            <!-- /.box-tools --> 
          </div>
          <!-- /.box-header -->
          <div class="box-body" style="display: block;">
            <?php // echo $is_applied;
			   
	   			if($result['batch_status'] == 'Approved' ){ 
			   		$status_text =  'Go Ahead'; 
					if(isset($is_applied)){
						if( (date('Y-m-d',strtotime("+5 day", strtotime($result['batch_to_date']))) > date('Y-m-d'))){
						    if($is_applied == 0 ){
								$str_btn = '<textarea name="rejection" maxlength="300" class="reason" rows="5" cols="40" placeholder="Describe cancel reason here"></textarea></br><a class="hold_batch btn btn-primary " dataid ="'.$result['id'].'" href="javascript:void(0);">Hold Batch</a>&nbsp;&nbsp;<a class="cancel_batch btn btn-danger " dataid ="'.$result['id'].'" href="javascript:void(0);">Cancel Batch</a>';	
							}else{
								$str_btn = '<a class="btn btn-danger" disabled="disabled" dataid ="'.$result['id'].'" href="javascript:void(0);">Cancel Batch</a> <br> <span class="act_msg"> Some Candidate already applied for exam </span>';	
							//$str_btn = 'Candidate applied for exam';	
							}
						    
						}else{
						    if($is_applied == 0 ){
								$str_btn = '<textarea name="rejection" maxlength="300" class="reason" rows="5" cols="40" placeholder="Describe cancel reason here"></textarea></br><a class="cancel_batch btn btn-danger " dataid ="'.$result['id'].'" href="javascript:void(0);">Cancel Batch</a>';	
							}else{
							$str_btn = '<a class="btn btn-danger" disabled="disabled" dataid ="'.$result['id'].'" href="javascript:void(0);">Cancel Batch</a> <br> <span class="act_msg"> Some Candidate already applied for exam </span>';	
								//$str
							}
						}
					}
					$div_class = '#d4edda';
					$div_class2 = '#004d00';
			   		}elseif($result['batch_status'] == 'Hold' ){ 
			   		$status_text =  'On Hold'; 
					if(isset($is_applied)){
						if($is_applied == 0){
						    
							$str_btn = '<textarea name="rejection" maxlength="300" class="reason" rows="5" cols="40" placeholder="Describe cancel reason here"></textarea></br><a class="unhold_batch btn btn-primary " dataid ="'.$result['id'].'" href="javascript:void(0);">UnHold Batch</a>&nbsp;&nbsp;<a class="cancel_batch btn btn-danger " dataid ="'.$result['id'].'" href="javascript:void(0);">Cancel Batch</a>';	
						}else{
							$str_btn = '<a class="btn btn-danger" disabled="disabled" dataid ="'.$result['id'].'" href="javascript:void(0);">Cancel Batch</a> <br> <span class="act_msg"> Some Candidate already applied for exam </span>';	
							//$str_btn = 'Candidate applied for exam';	
						}
					}
					$div_class = '#d4edda';
					$div_class2 = '#9900cc';
			   		}/*elseif($result['batch_status'] == 'In Review' ){ 
			   			$status_text =  'In Review'; 
						$str_btn = '<textarea name="rejection" maxlength="300" class="reason" rows="5" cols="40" placeholder="Describe rejection reason here"></textarea> <a class="approve_batch btn btn-success " dataid ="'.$result['id'].'" href="javascript:void(0);">Go Ahead</a>&nbsp; &nbsp; &nbsp;<a class="reject_batch btn btn-danger " dataid ="'.$result['id'].'" href="javascript:void(0);">Reject</a> &nbsp; &nbsp;';
						$div_class = '#f8d7da';
						$div_class2 = '#cca300';
			   		}*/
			   		elseif($result['batch_status'] == 'Cancelled' ){ 
				   		$status_text =  'Cancelled'; 
						$str_btn = '';
						$div_class = '#f8d7da';
						$div_class2 = '#ff0000';
			   		}
			   		elseif($result['batch_status'] == 'UnHold' ){ 
				   		$status_text =  'UnHold'; 

				   		$str_btn = '<textarea name="rejection" maxlength="300" class="reason" rows="5" cols="40" placeholder="Describe Cancellation reason here"></textarea> <a class="cancel_batch btn btn-danger " dataid ="'.$result['id'].'" href="javascript:void(0);">Cancel Batch</a> &nbsp; &nbsp;';
				   		//<a class="approve_batch btn btn-success " dataid ="'.$result['id'].'" href="javascript:void(0);">Go Ahead</a>&nbsp; &nbsp; &nbsp;
						$div_class = '#ff3399';
						$div_class2 = '#ff3399';
			   		}
			   		else if($result['batch_status'] == 'Rejected' ){ 
			   			$status_text =  'Rejected'; 
						//$str_btn = '<a class="approve_batch btn btn-success " dataid ="'.$result['id'].'" href="javascript:void(0);">Approve</a>';
					
						if($is_center_update){
							$str_btn = '<a class="approve_batch btn btn-success " dataid ="'.$result['id'].'" href="javascript:void(0);">Go Ahead</a>';
							
						}else{
							$str_btn = '';
						}
					
						$div_class = '#f8d7da';
						$div_class2 = '#800000';
				    }	
				    else if($result['batch_status'] == 'Final Review' ){ 
			   			$status_text =  'Final Review'; 
						$str_btn = '<textarea name="rejection" maxlength="300" class="reason" rows="5" cols="40" placeholder="Describe rejection reason here"></textarea> <a class="approve_batch btn btn-success " dataid ="'.$result['id'].'" href="javascript:void(0);">Go Ahead</a>&nbsp; &nbsp; &nbsp;<a class="batch_error btn btn-danger " dataid ="'.$result['id'].'" href="javascript:void(0);">Batch Error</a>&nbsp; &nbsp; &nbsp;<a class="reject_batch btn btn-danger " dataid ="'.$result['id'].'" href="javascript:void(0);">Reject</a> &nbsp; &nbsp;';
						$div_class = '#33cc33';
						$div_class2 = '#33cc33';
				    }		
				    else if($result['batch_status'] == 'Batch Error' ){ 
			   			$status_text =  'Batch Error'; 
						//$str_btn = '<a class="approve_batch btn btn-success " dataid ="'.$result['id'].'" href="javascript:void(0);">Approve</a>';
					
						$str_btn = '<textarea name="rejection" maxlength="300" class="reason" rows="5" cols="40" placeholder="Describe rejection reason here"></textarea> <a class="approve_batch btn btn-success " dataid ="'.$result['id'].'" href="javascript:void(0);">Go Ahead</a>&nbsp; &nbsp; &nbsp;<a class="batch_error btn btn-danger " dataid ="'.$result['id'].'" href="javascript:void(0);">Batch Error</a>&nbsp; &nbsp; &nbsp;<a class="reject_batch btn btn-danger " dataid ="'.$result['id'].'" href="javascript:void(0);">Reject</a> &nbsp; &nbsp;';
					
						$div_class = '#f8d7da';
						$div_class2 = '#cc0000';
				    }		
				    else if($result['batch_status'] == 'Re-Submitted' ){ 
			   			$status_text =  'Re-Submitted'; 
						//$str_btn = '<a class="approve_batch btn btn-success " dataid ="'.$result['id'].'" href="javascript:void(0);">Approve</a>';
					
						$str_btn = '<textarea name="rejection" maxlength="300" class="reason" rows="5" cols="40" placeholder="Describe rejection reason here"></textarea> <a class="approve_batch btn btn-success " dataid ="'.$result['id'].'" href="javascript:void(0);">Go Ahead</a>&nbsp; &nbsp; &nbsp;<a class="batch_error btn btn-danger " dataid ="'.$result['id'].'" href="javascript:void(0);">Batch Error</a>&nbsp; &nbsp; &nbsp;<a class="reject_batch btn btn-danger " dataid ="'.$result['id'].'" href="javascript:void(0);">Reject</a> &nbsp; &nbsp;';
					
						$div_class = '#f8d7da';
						$div_class2 = '#7b3ede';
				    }						   
			   $div_class = '';	
			   
			   
			 if( $result['batch_to_date'] != '')	{
				  $todate =  date_format(date_create($result['batch_to_date']),"d-m-Y");  //2019-02-26
			  }else{
				   $todate ='';
				  }
								  
			?>
             <form method="post" name="appfrom" id="approve_from"  enctype="multipart/form-data">
              <input type="hidden" id="center_id" name="center_id" value="<?php echo $result['id'] ?>" />
              <input type="hidden" id="agency_id" name="agency_id" value="<?php echo $result['agency_id'] ?>" />
              <input type="hidden" id="batch_id" name="batch_id" value="<?php echo $result['id'] ?>" />
               <input type="hidden" id="batch_to_date_val" name="batch_to_date_val" value="<?php echo $todate; ?>" />
              <input type="hidden" id="batch_status" name="status" value="<?php echo $result['batch_status'] ?>" />
              <input type="hidden" id="old_inspector_name" name="old_inspector_name" value="<?php echo $result['inspector_id'] ?>" />
              <input type="hidden" id="inspector_name" name="inspector_name" value="<?php echo $result['inspector_id'] ?>" />
               <input type="hidden" id="new_inspector_id" name="new_inspector_id" value="<?php echo $result['inspector_id'] ?>" />
              <input type="hidden" id="action_status" name="action_status" value="" />
              <div class="table-responsive ">
                <table class="table table-bordered table-striped" style="word-wrap:anywhere; background:<?php echo $div_class; ?>;">
                  <tbody>
                    <tr>
                      <td width="50%"><strong>DRA Accredited Agency Name :</strong></td>
                      <td width="50%"><?php echo $result['inst_name']; ?></td>
                    </tr>
                    <tr>
                      <td width="50%"><strong> Training Center Location:</strong></td>
                      <td width="50%"> <?php 					  
					   if($result['cityname'] !=''){  echo $result['cityname'];} else { echo $result['location_name'];   } 	
					  ?>
                      
                      
                      </td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Batch code - Batch Mode :</strong></td>
                      <td width="50%">
												<?php echo $result['batch_code']; ?>
												<?php if($result['batch_online_offline_flag'] == 1) {  echo " - Online"; } ?>
											</td>
                    </tr>
                    
                    <tr>
                      <td width="50%"><strong>1st Faculty Details :</strong></td>
                      <td width="50%">
					<?php  if($result['first_faculty_name'] != ''){
					   echo $result['first_faculty_name']; ?>
                       <?php }else{ echo '--';} ?>
                       </td>
                    </tr>
                    
                     <tr>
                      <td width="50%"><strong>2nd Faculty Details :</strong></td>
                      <td width="50%">
					<?php  if($result['sec_faculty_name'] != ''){
					   echo $result['sec_faculty_name']; ?>
                       <?php }else{ echo '--';} ?>
                       </td>
                    </tr>

                    <tr>
                      <td width="50%"><strong>Additional Faculty I Details :</strong></td>
                      <td width="50%">
					<?php  if($result['add_first_faculty_name'] != ''){
					   echo $result['add_first_faculty_name']; ?>
                       <?php }else{ echo '--';} ?>
                       </td>
                    </tr>
                    
                     <tr>
                      <td width="50%"><strong>Additional Faculty II Details :</strong></td>
                      <td width="50%">
					<?php  if($result['add_sec_faculty_name'] != ''){
					   echo $result['add_sec_faculty_name']; ?>
                       <?php }else{ echo '--';} ?>
                       </td>
                    </tr>
                    
                     <tr>
                      <td width="50%"><strong>Training Schedule :</strong></td>
                      <td width="50%">
                        <?php if($result['training_schedule'] != "") { ?>
                        <a href="<?php echo base_url('uploads/training_schedule/'.$result['training_schedule']); ?>" target="_blank">View Document</a>
                        <?php } ?>
                       </td>
                    </tr>
                    
                    
                    <tr>
                      <td width="50%"><strong>Name of Bank / Agency / Mixed (Source of Candidates):</strong></td>
                      <td width="50%"><?php echo $result['name_of_bank']; ?></td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Training Language Medium:</strong></td>
                      <td width="50%"><?php if( $result['training_medium'] != '' )	{?>
                        <?php echo $result['training_medium']; //medium_description ?>
                         <?php }else{ echo '--';} ?></td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Training Place Address :</strong></td>
                      <td width="50%"><?php echo $result['addressline1']; ?> <?php echo $result['addressline2']; ?> <?php echo $result['addressline3']; ?> <?php echo $result['addressline4']; ?> <?php echo $result['district']; ?> <?php echo  $result['city_name'] != '' ? $result['city_name'] : $result['city']; ?> <?php echo $result['state_name']; ?> <?php echo $result['pincode']; ?></td>
                    </tr>

                    <tr>
                      <td width="50%"><strong>Batch Coordinator Name :</strong></td>
                      <td width="50%"><?php if( $result['contact_person_name'] != '' )	{?>
                        <?php echo $result['contact_person_name']; ?>
                         <?php }else{ echo '--';} ?> </td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Batch Coordinator Mobile No :</strong></td>
                      <td width="50%"><?php if( $result['contact_person_phone'] != '' )	{?>
                        <?php echo $result['contact_person_phone']; //medium_description ?>
                         <?php }else{ echo '--';} ?></td>
                    </tr>

                    <tr>
                      <td width="50%"><strong>Alternative Contact Person Name :</strong></td>
                      <td width="50%"><?php if( $result['alt_contact_person_name'] != '' )	{?>
                        <?php echo $result['alt_contact_person_name']; ?>
                         <?php }else{ echo '--';} ?> </td>
                    </tr>
                    <tr>
                      <td width="50%"><strong>Alternative Contact Person Contact Number :</strong></td>
                      <td width="50%"><?php if( $result['alt_contact_person_phone'] != '' )	{?>
                        <?php echo $result['alt_contact_person_phone']; //medium_description ?>
                         <?php }else{ echo '--';} ?></td>
                    </tr>
                    
                    <tr>
                      <td width="50%"><strong>Batch Type (Hours):</strong></td>
                      <td width="50%"><?php  echo ($result['hours']) ? $result['hours'] : "0";  ?> hrs </td>
                    </tr>
                    
                    
                    <tr>
                      <td width="50%"><strong>Training Period  [ From date - To date ] :</strong></td>
                      <td width="50%"><?php 
					   // echo $result['center_validity_to'];
					  if( $result['batch_from_date'] != '' && $result['batch_to_date'] != ''  && $result['batch_to_date'] != '0000-00-00')	{?>
                        FROM <strong><?php echo date_format(date_create($result['batch_from_date']),"d-M-Y"); ?> </strong> TO <strong> <?php echo date_format(date_create($result['batch_to_date']),"d-M-Y"); ?></strong>
                        <strong>
					      <?php 
						  // show date diffrce by Manoj 
						  $date1=date_create(date_format(date_create($result['batch_from_date']),"Y-M-d")); 
						  $date2=date_create(date_format(date_create($result['batch_to_date']),"Y-M-d")); 
						  $diff = date_diff($date1,$date2);	
						  if($diff){
						  echo ' ('.($diff->days+1) . ' days)';
						  }
						  ?>
						  	
						 </strong>
                        <?php  }else{ ?>
                        <strong>-Training Period Not Added-</strong>
                        <?php   } ?></td>
                    </tr>

                    <tr>
                      <td width="50%"><strong>Holiday(s) during the Training Period:</strong></td>
                      <td width="50%">
						<?php /* if( $result['holidays'] != '' )	{ echo $result['holidays']; } */ echo $disp_holidays; ?>
                      </td>
                    </tr>

                    <tr>
                      <td width="50%"><strong>Net Training Days:</strong></td>
                      <td width="50%"><?php if( $result['net_days'] != '' )	{ echo $result['net_days']; }?>
                      </td>
                    </tr>

                    <tr>
                      <td width="50%"><strong>Training Time [ Start time - End time ] :</strong></td>
                      <td width="50%">
						<?php 
							if( $result['timing_from'] != ''  && $result['timing_to'] != '')	
							{	?>
								FROM <strong><?php echo $result['timing_from']; ?> </strong> 
								TO <strong><?php echo $result['timing_to']; ?></strong>
								<?php
								$datetime1 = new DateTime((strtoupper($result['timing_from'] )));
								$datetime2 = new DateTime((strtoupper($result['timing_to'] )));
								$interval = $datetime1->diff($datetime2);
								echo ' <strong> ( '.$interval->format('%h hr : %i min').' )</storng>';
							} 
							else
							{ ?>
								<strong>-Training Not Added-</strong>
						<?php   } ?></td>
                    </tr>

                    <tr>
                    	<?php $gross_time = explode(':', $result['gross_time']);?>
				      	<td width="50%"><strong>Gross Training Period Time :</strong></td>
				      	<td width="50%"><?php echo $gross_time[0].' hr :'.$gross_time[1].'min'; ?></td>
				    </tr>

                    <tr>
	                    <?php
				          $time = intval($result['brk_time1'])+intval($result['brk_time2'])+intval($result['brk_time2']);
				          $hours = floor($time / 60);
				          $minutes = ($time % 60);
				        ?>

                      	<td width="50%"><strong>Daily Break Times:</strong></td>
                      	<td width="50%"><?php echo $result['total_break_time']; ?></td>
                    </tr>
				    
				    <tr>
				    	<?php $net_time = explode(':', $result['net_time']);?>
				      	<td width="50%"><strong>Net Training Time Per Day :</strong></td>
				      	<td width="50%"><?php echo $net_time[0].' hr :'.$net_time[1].'min'; ?></td>
				    </tr>

                    <tr>
                      	<td width="50%"><strong>Total No Of Candidates :</strong></td>
                        <td>
	                      	<table class="table table-bordered" style="border-color:#ccc; margin:0;">
								<tbody>
									<tr>
                    <?php if($result['hours'] == '100') { ?>
										<th style="text-align:center; border-color:#ccc">10th Pass</th>
										<th style="text-align:center; border-color:#ccc">12th Pass</th>
                    <?php } ?>
										<th style="text-align:center; border-color:#ccc">Graduate</th>
										<th style="text-align:center; border-color:#ccc">Total</th>
									</tr>
									<tr>
                    <?php if($result['hours'] == '100') { ?>
										<td style="text-align:center; border-color:#ccc"><?php echo intval($result['tenth_pass_candidates']); ?></td>
										<td style="text-align:center; border-color:#ccc"><?php echo intval($result['twelth_pass_candidates']); ?></td>
                    <?php } ?>
										<td style="text-align:center; border-color:#ccc"><?php echo intval($result['graduate_candidates']); ?></td>
										<td style="text-align:center; border-color:#ccc"><?php echo intval($result['total_candidates']); ?></td>
									</tr>
								</tbody>
							</table>
						</td>
                      <?php /*?><td width="50%"><?php if( $result['total_candidates'] != '' )	{?>
                        <?php echo $result['total_candidates'].' (Under Graduates:'.(intval($result['tenth_pass_candidates'])+intval($result['twelth_pass_candidates'])).', Graduates:'.$result['graduate_candidates'].')'; } ?></td><?php */?>
                    </tr>

          <tr>
            <td width="50%"><strong>Remarks :</strong></td>
            <td width="50%"><?php echo $result['remarks']; ?></td>
          </tr>
										
					<?php if($result['batch_online_offline_flag'] == 1) { ?>	
						<tr>
							<td><strong>Online Batch Login Details</strong></td>
							<td>
								<table class="table table-bordered" style="border-color:#ccc; margin:0;">
									<tbody>
										<tr>
											<th style="text-align:center; border-color:#ccc">Sr. No</th>
											<th style="text-align:center; border-color:#ccc">Login ID</th>
											<th style="text-align:center; border-color:#ccc">Password</th>
										</tr>
										<?php if(isset($online_batch_user_details) && count($online_batch_user_details) > 0)
										{
											$sr_no=1;
											foreach($online_batch_user_details as $online_batch)
											{	?>
												<tr>
													<td style="text-align:center; border-color:#ccc"><?php echo $sr_no; ?></td>
													<td style="border-color:#ccc"><?php echo $online_batch['login_id']; ?></td>
													<td style="border-color:#ccc"><?php echo base64_decode($online_batch['password']); ?></td>
												</tr>
										<?php $sr_no++;
											}	
										}	?>
									</tbody>
								</table>
							</td>
						</tr>
						
						<tr>
							<td><strong>Online Training Platform</strong></td>
							<td><?php echo $result['online_training_platform']; ?></td>
						</tr>

						<tr>
							<td><strong>Online Training Platform URL</strong></td>
							<td><?php echo $result['platform_link']; ?></td>
						</tr>
					<?php } ?>
                    <?php /*?>
                    <tr>
                      <td width="50%"><strong>Training Batch Remarks by Agency :</strong></td>
                      <td width="50%"><?php if( $result['remarks'] != '' )	{?>
                        <?php echo $result['remarks']; ?>
                         <?php }else{ echo '--';} ?></td>
                    </tr>
                    <?php */?>
                    <tr> </tr>
                    
                    <tr>
                    	 <?php if($drauserdata['roleid'] != 3 && $drauserdata['roleid'] != 4){ ?>
                    <?php  if($result['batch_status'] == 'Approved'  ){  ?> 
                     <tr>
                      <td width="50%"><strong>Assign Batch Active Period:</strong></td>
                      <td width="50%"><strong><?php if( $result['batch_active_period'] != '' && $result['batch_active_period'] != '0000-00-00'){ echo date_format(date_create($result['batch_active_period']),"d-M-Y"); }else { echo ''; } ?></strong>   <br>   <br><input type="text" class="center_validity form-control" name="batch_active_period" id="batch_active_period"  maxlength="10" />  &nbsp; &nbsp; <a class="batch_active_period_btn btn btn-success " dataid ="<?php echo$result['id']; ?>" href="javascript:void(0);">Assign Batch Active Period</a>  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <br> <br> <span class="note"> Active period will be assign from current date to selected date. </span></td>
                    </tr>
                    <?php } ?>
                    
                    <?php } ?>
                    <tr>
                    
                      <td width="50%"><strong>Assigned Inspector :</strong></td>
                      <td width="50%">
                      <div class="col-md-12">
                      <div class="col-md-6">
							<?php 
                            $k = 1;
                            if(count($result_inspector) > 0){?>
                                <select class="form-control inspec" name="inspector_id" id="inspector_id" >
                                <option value=""> -- Select Inspector -- </option>
                                <?php 
                                $sel = '';
                                foreach($result_inspector as $D){ 
                                if($result['inspector_id'] != '' ){
                                    if($D['inspector_id'] == $result['inspector_id'] ){
                                        $sel =  'selected="selected"';
                                        }else{
                                        $sel =  '';	
                                        }
                                    }
                                ?>
                                <option value="<?php echo $D['inspector_id']; ?>" <?php echo $sel; ?> ><?php echo $D['inspector_name']; ?> </option>						
                            <?php } ?>
                                </select>
                            <?php } ?>
                            </div>
                             <div class="col-md-6">
                            <?php	
                              if($drauserdata['roleid'] != 3){ 					
							 echo '<a class="update_inspector btn btn-primary"  href="javascript:void(0);">Assign Inspector</a>';
							}
							//}
							 ?>	
							</div>
                        
                            </div>
                          
                       </td>                          
                    
                    </tr>
                    
                      <?php  if(($result['batch_status'] == 'Approved' || $result['batch_status'] == 'Cancelled') && $result['inspector_id'] != 0 ){  ?> 
                    <tr>
                      <td width="50%"><strong>Inspection Report :</strong></td>
                      <td width="50%" > &nbsp; <?php if($result['inspector_report'] != ''){ 
					   echo '<a class="btn btn-info btn-xs vbtn" target="_blank" href="'.base_url().'uploads/iibfdra/agency_center/'.$result['inspector_report'].'"> &nbsp; &nbsp;View Inspection Report &nbsp;<i class="fa fa fa-file"></i> &nbsp; &nbsp;</a>  &nbsp; &nbsp;';
					   
					   
					    echo '<a class="add_report btn btn-primary"  href="javascript:void(0);">Update Inspection Report &nbsp;<i class="fa fa fa-file"></i> &nbsp;</a>  &nbsp; &nbsp;'; ?>
                       <div class="report_tag">
                       <input type="file" style="display:inline;" class="inspector_report" id="inspector_report" name="inspector_report" />
                      	<a style="display:inline;" class="submit_report btn btn-success" href="javascript:void(0);">Update Report </a></div>
					   <?php
					 // echo $result['agency_letter'];
					   } else {  echo '<a class="add_report btn btn-primary"  href="javascript:void(0);">Add Inspection Report &nbsp;<i class="fa fa fa-file"></i> &nbsp;</a>'; ?>
                       <div class="report_tag">
                       <input type="file" style="display:inline;" class="inspector_report" id="inspector_report" name="inspector_report" />
                      	<a style="display:inline;" class="submit_report btn btn-success" href="javascript:void(0);">Save Report </a></div>
                       <?php
					   
					   
					   } ?></td>
                    </tr>                    
                    <tr>
                    <?php } ?>
                    
                      <td width="50%"><strong>Batch Status :</strong></td>
                      <td width="50%" style="font-size:16px; color:<?php echo $div_class2; ?>;" ><strong><?php echo ' '.$status_text; ?> </strong></td>
                    </tr>
                     <?php if($drauserdata['roleid'] != 3 && $drauserdata['roleid'] != 4){ ?>
                    
                    <tr>
                      <td width="50%"><strong>Action:</strong></td>
                      <td width="50%"><?php echo $str_btn; ?>
                      </td>
                    </tr>
                    <?php } ?>
                    <?php /*?><tr class="report_tag">
                      <td width="50%"><strong>Upload Inspection Report</strong></td>
                      <td width="50%"> 
                      	<input type="file" style="display:inline;" class="inspector_report" id="inspector_report" name="inspector_report" />
                      	<a style="display:inline;" class="submit_report btn btn-success" href="javascript:void(0);">Save Report</a>
                      </td>
                    </tr>
                    <?php */?>

                    <tr>
                      <td width="50%"><strong>Batch Communication:</strong></td>
                      <td width="40%">
                      	<textarea name="batch_communication" id="batch_communication" maxlength="1000"  rows="4" cols="75" placeholder="Enter Remark here"></textarea>
                      	<span class="note-error" id="batch_communication_error"></span>
                      </td>
                      <td width="10%">
                      	<a class="update_batch_communication btn btn-primary"  href="javascript:void(0);">Submit</a>
                      </td>
                    </tr>

                  </tbody>
                </table>
              </div>
            </form>
          </div>
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
      </div>
      
      
    <?php/*?>  
    <div class="col-xs-12">
        <div class="box-header">
          <h3 class="box-title">Batch Candidate's Details : [ <?php echo $result['inst_name']; ?> ]</h3>
        </div>
        <div class="box">
          <div class="box-body">
            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
            <input type="hidden" name="base_url_val" id="base_url_val" value="" />
            <div class="table-responsive">
              <table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
                  <tr>
                    <th>Sr.No.</th>
                    <th>Reg. Number</th>
                    <th>Candidate Name</th>
                    <th>Mobile</th>
                    <th>Aadhar Number</th>
                    <th>Institute Code</th>
                    <th>Associated Institute</th>
                    <th>Registration Date</th>
                     <th>Action</th>
                  </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                 <?php 
				$k = 1;
				if(count($result_student) > 0){
					foreach($result_student as $res){
					echo '<tr><td>'.$k.' </td>';					
					echo '<td>'.$res['regnumber'].' </td>';
					echo '<td>'.$res['namesub'].' '.$res['firstname'].' '.$res['middlename'].' '.$res['lastname'].' </td>';	
					echo '<td>'.$res['mobile'].' </td>';
					if($res['aadhar_no'] != ''){	
					echo '<td>'.$res['aadhar_no'].' </td>';	
					}else{
					echo '<td> -- </td>';		
						}
					echo '<td>'.$res['inst_code'].' </td>';	
					echo '<td>'.$res['associatedinstitute'].' </td>';					
				
					if( $res['createdon'] != '' )	{
                       echo '<td>'.date_format(date_create($res['createdon']),"d-M-Y").' </td>';
                    }else{ 
                        echo '<td>---</td>';
					} 	
					echo '<td><a class="btn btn-info btn-xs vbtn" href="'.base_url().'iibfdra/batch/candidate_detail/'.$res['regid'].'">View</a </td></tr>';
					$k++;	
				}
			}
				?>
                </tbody>
              </table>
              <div id="links" class="dataTables_paginate paging_simple_numbers"> </div>
            </div>
          </div>
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
      </div>
      <!-- /.col collapsed-box-->
      <?php */?>
      	<?php /*?>
	    <div class="col-xs-12" >
	        <div class="box">
	          <div class="box-header with-border">
	            <h3 class="box-title">Training Batch Communication For [ <?php echo $result['batch_name']; ?> ( <?php echo $result['batch_code']; ?> ) ]</h3>
	            <div class="box-tools pull-right"> 
	              <!-- Collapse Button -->
	              <button type="button" class="btn btn-box-tool" data-widget="collapse"> <i class="fa fa-minus"></i> </button>
	            </div>
	            <!-- /.box-tools --> 
	          </div>
	          <!-- /.box-header -->
	          <div class="box-body ">
	            <div class="table-responsive">
	              <table id="" class="table table-bordered table-striped">
	                <thead>
	                  <tr>
	                  	<th>Sr.No.</th>
	                    <th>Remark</th>
	                    <th>Date/Time </th>
	                  </tr>
	                </thead>
	                <tbody class="no-bd-y" id="">
	                <?php foreach ($batch_communication as $key => $value) {
	                	$j = $key+1;
	                ?>	
	                  <tr>
	                  	<td><?php echo $j; ?></td>
	                    <td><?php echo $value['batch_communication']; ?></td>
	                    <td><?php echo $value['created_on']; ?></td>
	                  </tr>
	              	<?php }?>
	                </tbody>
	              </table>
	            </div>
	          </div>
	          <!-- box-footer --> 
	        </div>
	        <!-- /.box --> 
	    </div>
	    <?php */?>

	    <div class="col-xs-12" >
	        <div class="box">
	          <div class="box-header with-border">
	            <h3 class="box-title">Training Batch Logs For [ <?php echo $result['batch_name']; ?> ( <?php echo $result['batch_code']; ?> ) ]</h3>
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
	                  	<th>Sr.No.</th>
	                    <th>Action</th>
	                    <th>Action Date/Time </th>
	                    <th>Reason</th>
	                  </tr>
	                </thead>
	                <tbody class="no-bd-y" id="list222">
	                  <?php echo $str; ?>
	                </tbody>
	              </table>
	            </div>
	          </div>
	          <!-- box-footer --> 
	        </div>
	        <!-- /.box --> 
	    </div>
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
<script src="<?php echo base_url('assets/js/sweetalert2.all.min.js') ?>" type="text/javascript"></script> 
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script> 
<script type="text/javascript">
  //$('#searchDate').parsley('validate');
</script>
<style>
.report_tag{
display:none;
clear: both;
padding: 17px 8px;
border: 1px solid #ccc;
margin: 5px;
max-width: 408px;
text-align: center;
}
.inspec{
 max-width:80%;	
}
.red{
 color:red;	
}
.err{
 border:1px solid #F00;	
}
/*.success{
 border:1px solid #198754;	
}*/
.reason{
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
	padding-right:4px !important;
}
.table-responsive{
/* overflow-x:hidden !important; */
}

/* .table-responsive > .dataTables_wrapper, .table-responsive > .table
{
	max-width: 96%;
	margin: 0 auto;
} */

td {
	word-wrap: anywhere;
	white-space: unset !important;
}

.DTTT_button_print{
	display:block;
}
#batch_active_period{
 width:220px;	
 float:left;
}
.batch_active_period_btn{
 float:right;	
}
.act_msg{
 font-size:12px;
 font-style:italic;
 color:#900;
 widows:100%;	
}
#inspector_id{
 width:210px;	
}
.reason{
 width:85%;
 margin:4px;
 clear:both;	
}
</style>
<script src="<?php echo base_url()?>js/js-paginate.js"></script> 
<script>
var site_url = '<?php echo base_url(); ?>';
$(function () {

	var dateToday = new Date();	
	//var validity_to_ck =  $('#batch_to_date_val').val(); //2019-02-26
	var validity_to_ck = $('#batch_to_date_val').val();
 
	$('#batch_active_period').datepicker({
			autoclose: true, 
			format: 'dd-mm-yyyy', 												        
			dateFormat: 'dd-mm-yyyy',
			todayHighlight: true			
	}).attr('readonly', 'readonly');
	
   $('#batch_active_period').datepicker('setStartDate', dateToday);
  
  // Remove this code to allow user to add bach active period more than to date // on 25 may 2019 by MM 
   //if(validity_to_ck != ''){
  	//$('#batch_active_period').datepicker('setEndDate', validity_to_ck);
  // }
   
   
	//batch_active_period_btn is button add to add active period
	$('.batch_active_period_btn').click(function(){
		//AP = Active period		
		$('#action_status').val('AP');		
		var active_period = $('#batch_active_period').val();		
		if(active_period == ''){
		  $('#batch_active_period').addClass('err');
		  return false;	
		}
		var batch_status = $('#batch_status').val();
		if(batch_status != 'Approved'){
		  alert('Please approve batch before assign batch active period');		
		  return false;	
		}
		
		//alert(batch_status);				
  		if (confirm('Are you sure you want to assign Batch Active Period?')) {
			$('#approve_from').submit();	
		} else {
			return false;
		}		
	});
	
	$('.inspec').on('change', function() {
		var curr_insp = $('.inspec').val();	
	    var insp_new =  $('#new_inspector_id').val(curr_insp);	
	});
	
	
	$('.approve_batch').click(function(){ 
		$('#action_status').val('Approved');
		
		var insp_old 	=  $('#old_inspector_name').val();
		var add_reason 	= 1;
		var approve_reason 	= 1;
			
		if ($(".inspec").length > 0) {		
			var curr_insp = $('.inspec').val();			
			if(curr_insp != ''){
				var insp_new =  $('#new_inspector_id').val(curr_insp);	
			}
	  	}
		
		var insp_new =  $('#new_inspector_id').val();		
		
		if(insp_new != '' && insp_new != 0 ){			
			add_reason = 0;
			console.log('in_1');
		}
		if(insp_old != '' && insp_old != 0 && add_reason != 0){
			add_reason = 0;
			console.log('in_2');
		}
		
		//alert(insp_new+''+insp_new+''+add_reason);
		console.log(insp_new+''+insp_new+''+add_reason);
		if(add_reason == 1 || approve_reason == 1){
			
			$('.reason').attr("placeholder", "Describe Approval Reason here");	
			$('.reason').show();	
			var reject_reason = $.trim($('.reason').val());
			if(reject_reason == ''){
			  //$('.reason').addClass('err');
			  return false;	
			}
		}else{
			
			$('.reason').hide();
			$('.reason').val('');
		}
			 		
		if (confirm('Are you sure you want to Approve Batch?')) {
			$('#approve_from').submit();	
		} else {
			return false;
		}			
	});
	
	$('.update_inspector').click(function(){ 
		$('#action_status').val('UPDATE_INSPECTOR');
		var insp =  $('#inspector_id').val();
		if(insp == ''){
			$('#inspector_id').addClass('err');
			alert('Please assign inspector for batch');			
		  	return false;	
		}else{
			$('#inspector_id').removeClass('err');
			$('#new_inspector_id').val(insp);
		}	
				
		var insp_old =  $('#old_inspector_name').val();
		var insp_new =  $('#new_inspector_id').val();
	
		if(insp_new == ''){
			alert('Please assign inspector for batch');
		  	return false;	
		}else if(insp_new == insp_old ){			
			alert('Selected inspector already assign for this batch');
		  	return false;	
		}			 		
		if (confirm('Are you sure you want to update inspector?')) {
			$('#approve_from').submit();	
		} else {
			return false;
		}			
	});

	$('.update_batch_communication').click(function(){ 
		console.log('update_batch_communication');
		var agency_id =  $('#agency_id').val();
		var batch_id =  $('#batch_id').val();
		var batch_communication =  $('#batch_communication').val();
		
		if(batch_communication == ''){
			$('#batch_communication_error').text('Please Enter Batch Communication Remark');
		}
		else{
			$('#batch_communication_error').text('');
			$.ajax({
	            url:site_url+'iibfdra/Batch/agency_update_batch_communication',
	            data: {agency_id: agency_id, batch_id:batch_id, batch_communication:batch_communication},
	            type:'POST',
	            async: false,
	            success: function(response) {
	                if(response == 1){
			            swal({
	                      title: 'Message Save!',
	                      text: 'Batch Communication Saved Successfully...',
	                      icon: 'success',
	                      type: 'success',
	                      confirmButtonColor: '#3f51b5',
	                      confirmButtonText: 'OK ',
	                      buttons: {
	                        confirm: {
	                          text: "OK",
	                          value: true,
	                          visible: true,
	                          className: "btn btn-primary",
	                          closeModal: true
	                        }            
	                      }
	                    }).then(OK => {
	                        location.reload();
	                    });
			        }
	            }
	        });
		}		 		
	});
	
	
	$('.reject_batch').click(function(e){
		$('#action_status').val('Rejected');
		$('.reason').show();		
		$('.reason').attr("placeholder", "Describe rejection reason here");	
		var reject_reason = $.trim($('.reason').val());
		
		if(reject_reason == ''){
		  $('.reason').addClass('err');
		  return false;	
		}
		
  		if (confirm('Are you sure you want to Reject Batch?')) {
			e.preventDefault();
			$('#approve_from').submit();
				
		} else {
			return false;
		}		
	});

	$('.batch_error').click(function(e){
		$('#action_status').val('Batch Error');
		$('.reason').show();		
		$('.reason').attr("placeholder", "Describe Error here");	
		var reject_reason = $.trim($('.reason').val());
		
		if(reject_reason == ''){
		  $('.reason').addClass('err');
		  return false;	
		}
		
  		//if (confirm('Are you sure you want to Reject Center?')) {
			//e.preventDefault();
			$('#approve_from').submit();
				
		/*} else {
			return false;
		}*/		
	});
	
	$('.cancel_batch').click(function(e){
		$('#action_status').val('Cancelled');
		$('.reason').attr("placeholder", "Describe cancel batch reason here");	
		$('.reason').show();	
		var reject_reason = $.trim($('.reason').val());
		
		if(reject_reason == ''){
		  $('.reason').addClass('err');
		  return false;	
		}
		
  		if (confirm('Are you sure you want to Cancel Batch?')) {
			e.preventDefault();
			$('#approve_from').submit();	
		} else {
			return false;
		}		
	});
	
	$('.hold_batch').click(function(e){
		$('#action_status').val('Hold');
		
		$('.reason').attr("placeholder", "Describe Hold batch reason here");	
		$('.reason').show();	
		var reject_reason = $.trim($('.reason').val());
		
		if(reject_reason == ''){
		  $('.reason').addClass('err');
		  return false;	
		}
		
  		if (confirm('Are you sure you want to put batch on Hold?')) {
			e.preventDefault();
			$('#approve_from').submit();	
		} else {
			return false;
		}		
	});

	$('.unhold_batch').click(function(e){
		$('#action_status').val('UnHold');

		$('.reason').attr("placeholder", "Describe UnHold batch reason here");	
		$('.reason').show();
		var unhold_reason = $.trim($('.reason').val());

		if(unhold_reason == ''){
		  $('.reason').addClass('err');
		  return false;	
		}

  		if (confirm('Are you sure you want to UnHold Batch?')) {
			e.preventDefault();
			$('#approve_from').submit();	
		} else {
			return false;
		}		
	});
	
	$('.submit_report').click(function(e){
		$('#action_status').val('REPORT');
		//$('.reason').show();		
		var inspector_report = $('.inspector_report').val();
		//alert('inspector_report'+inspector_report)
		if(inspector_report == ''){
		  $('.inspector_report').addClass('err');
		  return false;	
		}
		
		var ext = $('.inspector_report').val().split('.').pop().toLowerCase();
		if($.inArray(ext,['pdf','doc','docx','jpg','png','jpeg']) == -1) {
			//pdf|PDF|doc|DOC|docx|DOCX|txt|TXT|jpg|png|jpeg|JPG|PNG|JPEG
			alert('invalid extension!');
			return false;	
		}
		
  		if (confirm('Are you sure you want to submit Inspection report?')) {
			e.preventDefault();
			$('#approve_from').submit();	
			
		} else {
			return false;
		}		
	});
	
	$('.add_report').click(function(){
		$('.report_tag').slideDown('slow');
	});
	
	$("#listitems").DataTable();
	//$("#listitems_logs").DataTable();
		
	//$("#listitems_logs_filter").show();		
	$("#listitems_filter").show();
	
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
      { "width": "25%", "targets": 1 },
      { "width": "17%", "targets": 2 },
      { "width": "50%", "targets": 3 }     
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
        //console.log( api.rows( {page:'current'} ).data() );
    }
       
} );


</script>
    <script>
    if ( window.history.replaceState ) {
      window.history.replaceState( null, null, window.location.href );
    }
    </script>
<?php $this->load->view('iibfdra/admin/includes/footer');?>
