<style>
.control-label {
	font-weight: bold !important;
}
label {      
      border-color: #80808059;
}
.types {
    color: green;
    font-weight: 800;
}
.status_div{
 font-weight: 800 !important;
}

.status {
  color: #223fcc;
  font-weight: 800;
}
.myview .form-group{
	clear:both;
}
.total_candidates{
 text-align:right;	
}
.statusi {
  color: #cca300;
  font-weight: 800;
}
.statusf {
  color: #33cc33;
  font-weight: 800;
}
.statusa {
  color: #004d00;
  font-weight: 800;
}
.statusc {
  color: #d15656;
  font-weight: 800;
}
.statusr {
  color: #800000;
  font-weight: 800;
}
.statush {
  color: #ed823b;
  font-weight: 800;
}
.statuuh {
  color: #c25e48;
  font-weight: 800;
}
.statusbe {
  color: #FF8C00;
  font-weight: 800;
}
.statusrs {
  color: #7b3ede
  font-weight: 800;
}

</style>
<div class="content-wrapper">
  <section class="content-header">
    <h1> Batch Preview </h1>
        <!-- <?php if (isset($batchDetails)) {
              # code...
             // print_r($batchDetails); die;
              foreach ($batchDetails as $batchs) { 
                $bstatus = $batchs['batch_status'];
                 $bid = $batchs['id'];
              }
            }?>
    <?php if ($bstatus == 'IR' || $bstatus == 'R') { ?>
     
   <p>Please go through the given detail, correction may be made if necessary. <a href="<?php //echo base_url().'iibfdra/Version_2/TrainingBatches/edit/'.base64_encode($bid)?>">Modify</a></p>
   <?php  } else {
    //echo "<p>Your batch is approved by admin,so you can not update your batch details.</p>" ;
   } ?> -->
  </section>
 
    <section class="content">
      <div class="row myview">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Batch Preview</h3>
              <div class="pull-right">
                <a href="<?php echo base_url();?>iibfdra/Version_2/InspectorHome/batches" class="btn btn-warning">Back</a> 
             </div>
            </div>
            <div class="box-body" style="padding-left: 45px">
              <?php
                  $institute_name = '';
                  $drainstdata = $this->session->userdata('dra_institute');
                  if( $drainstdata ) {
                    $institute_name = $drainstdata['institute_name']; 
                    $institute_code = $drainstdata['institute_code'];
                  }
                  ?> 
            <?php if (isset($batchDetails)) {
              # code...
              //print_r($batchDetails); die;
             //foreach ($batchDetails as $batch) { 
              $batch = $batchDetails[0];

            ?>


            <?php 
                $status = isset($batch['batch_status'])?$batch['batch_status']:'';
                
              ?>
              
            

            <?php 
                if($batch['batch_status']=="In Review"){ 
                  $status='<div class="col-sm-2 statusi">In Review</div>';
                }
                if($batch['batch_status']=="Final Review"){ 
                  $status='<div class="col-sm-2 statusf">Final Review</div>';
                }
                if($batch['batch_status']=="Re-Submitted"){ 
                  $status='<div class="col-sm-2 statusrs">Re-Submitted</div>';
                }
                if($batch['batch_status']=="Batch Error"){ 
                  $status='<div class="col-sm-2 statusbe">Batch Error</div>';
                }
                if($batch['batch_status']=="Approved"){ 
                  $status='<div class="col-sm-2 statusa">Go Ahead</div>';
                }
                if($batch['batch_status']=="Rejected"){ 
                  $status='<div class="col-sm-2 statusr">Rejected</div>';
                }
                if($batch['batch_status']=="Hold"){ 
                  $status='<div class="col-sm-2 statush">Hold</div>';
                }
                if($batch['batch_status']=="UnHold"){ 
                  $status='<div class="col-sm-2 statuuh">UnHold</div>';
                }
                if($batch['batch_status']=="Cancelled"){ 
                  $status='<div class="col-sm-2 statusc">Cancelled</div>';
                }
            ?>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">BATCH STATUS :</label>
                <?php echo $status; ?>
              </div>
               
             <div class="form-group">
              <label for="total_candidates" class="col-sm-4 control-label">NAME OF TRAINING AGENCY :</label>
              <div class="col-sm-5"> <?=$batch['inst_name'];?></div>
            </div>
             <?php if($batch['city_name']!="")
              {
                  $location = $batch['city_name'];
              } 
              else
              { 
                $location = $batch['location_name'];
              }?>
             <div class="form-group">
              <label for="total_candidates" class="col-sm-4 control-label">CENTER NAME :</label>
              <div class="col-sm-5"><?=$location;?></div>
            </div>
    
            <!--  <div class="form-group">
              <label for="total_candidates" class="col-sm-4 control-label">INSPECTOR NAME :</label>
              <div class="col-sm-5"></div>
            </div> -->
            
             <div class="form-group">
              <label for="total_candidates" class="col-sm-4 control-label">BATCH NAME( BATCH CODE ) (Batch Mode):</label>
              <div class="col-sm-5">
        				<?php echo  $batch['batch_name'] .' ('. $batch['batch_code'].')';?>
        				<?php if($batch['batch_online_offline_flag'] == 1) {  echo " - Online"; } else echo ' - Offline'; ?>
        			</div>
            </div>
            <?php /*?><div class="form-group">
              <label for="total_candidates" class="col-sm-4 control-label">BATCH TYPE :</label>
              <div class="col-sm-5"><span class="types"><?php echo $batch['batch_type']; ?></span></div>
            </div><?php */?>
            <div class="form-group">
              <label for="total_candidates" class="col-sm-4 control-label">HOURS :</label>
              <div class="col-sm-5"><span class="types"><?php echo $batch['hours']; ?></span></div>
            </div>

    
       <?php if($reason != ''){ 
         //$reason = $this->master_model->getValue('agency_batch_rejection',array('batch_id'=>$batch['id']), 'rejection');
       	?>
     <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">REASON :</label>
      <div class="col-sm-5"><?=$reason;?></div>
    </div>
      <?php }
       ?>
      <div class="form-group">
        <label for="total_candidates" class="col-sm-4 control-label">BATCH TRAINING PERIOD :</label>
          <div class="col-sm-5">
      	   <?php 
      			 if( $batch['batch_from_date'] != '' && $batch['batch_to_date'] != ''  && $batch['batch_to_date'] != '0000-00-00')	{ ?>
                FROM <strong><?php echo date_format(date_create($batch['batch_from_date']),"d-M-Y"); ?> </strong> TO <strong> <?php echo date_format(date_create($batch['batch_to_date']),"d-M-Y"); ?></strong>
              <strong>
                <?php 
              	  // show date diffrce by Manoj 
              	  $date1=date_create(date_format(date_create($batch['batch_from_date']),"Y-M-d")); 
              	  $date2=date_create(date_format(date_create($batch['batch_to_date']),"Y-M-d")); 
              	  $diff = date_diff($date1,$date2);	
              	  if($diff){
              	  echo '('.($diff->days+1) . ' days )';
              	  }
    	         ?>
              </strong>
	         <?php  }else{ ?>
          <strong>-Training Period Not Added-</strong>
        <?php   } ?>
        </div>
      </div>

      <div class="form-group">
        <label for="total_candidates" class="col-sm-4 control-label">HOLIDAY(s) :</label>
        <div class="col-sm-5">
          <strong><?php echo $batch['holidays']; ?></strong>
        </div>
      </div>

      <div class="form-group">
        <label for="total_candidates" class="col-sm-4 control-label">NET PERIOD :</label>
        <div class="col-sm-5">
          <strong><?php echo $batch['net_days']; ?></strong>
        </div>
      </div>
       
      <div class="form-group">
        <label for="total_candidates" class="col-sm-4 control-label">TIMING OF TRAINING :</label>
        <div class="col-sm-5">
  				<?php if($batch['timing_from'] != '' && $batch['timing_to'] != ''){	?>
  						FROM <strong><?php echo $batch['timing_from']; ?> </strong> 
  						TO <strong><?php echo $batch['timing_to']; ?></strong>
  					<?php } 
  					else
  					{ ?>
  						<strong>-Training time Not Added-</strong>
  	     <?php } ?>      
        </div>
      </div>

    <div class="form-group">
      <?php
        $gross_time = explode(':', $batch['gross_time']);
      ?>
      <label for="total_candidates" class="col-sm-4 control-label">GROSS TIME :</label>
      <div class="col-sm-5">
        <strong><?php echo $gross_time[0].' hr :'.$gross_time[1].'min'; ?></strong>
      </div>
    </div>

    <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">BREAK TIME 1 :</label>
      <div class="col-sm-5">
        <?php
          $breakTime1 = '';
          if ($batch['brk_from_time1'] != '' && $batch['brk_to_time1'] != '') {
            $breakTime1 = $batch['brk_from_time1'].' to '.$batch['brk_to_time1'];
          }
        ?>
        <strong><?php echo $breakTime1; ?> </strong> 
      </div>
    </div>

    <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">BREAK TIME 2 :</label>
      <div class="col-sm-5">
        <?php
          $breakTime2 = '';
          if ($batch['brk_from_time2'] != '' && $batch['brk_to_time2'] != '') {
            $breakTime2 = $batch['brk_from_time2'].' to '.$batch['brk_to_time2'];
          }
        ?>
        <strong><?php echo $breakTime2; ?> </strong> 
      </div>
    </div>

    <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">BREAK TIME 3 :</label>
      <div class="col-sm-5">
        <?php
          $breakTime3 = '';
          if ($batch['brk_from_time3'] != '' && $batch['brk_to_time3'] != '') {
            $breakTime3 = $batch['brk_from_time3'].' to '.$batch['brk_to_time3'];
          }
        ?>
        <strong><?php echo $breakTime3; ?> </strong> 
      </div>
    </div>

    <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">BREAK TIMES :</label>
      <div class="col-sm-5">
        <?php
          $time = intval($batch['brk_time1'])+intval($batch['brk_time2'])+intval($batch['brk_time3']);
          $hours = floor($time / 60);
          $minutes = ($time % 60);
        ?>
        <strong><?php echo $hours.'hr : '.$minutes.'min'; ?> </strong> 
      </div>
    </div>

    <div class="form-group">
      <?php
        $net_time = explode(':', $batch['net_time']);
      ?>
      <label for="total_candidates" class="col-sm-4 control-label">NET TRAINING PERIOD TIME :</label>
      <div class="col-sm-5">
        <strong><?php echo $net_time[0].' hr :'.$net_time[1].'min'; ?></strong> 
      </div>
    </div>

    <div class="form-group">
      <?php
        $total_net_time = explode(':', $batch['total_net_time']);
      ?>
      <label for="total_candidates" class="col-sm-4 control-label">TOTAL NET TRAINING TIME OF DURATION :</label>
      <div class="col-sm-5">
        <strong><?php echo $total_net_time[0].' hr :'.$total_net_time[1].'min'; ?></strong> 
      </div>
    </div>
    
     <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">TRAINING LANGUAGE :</label>
      <div class="col-sm-5"><?=$batch['training_medium'];?></div>
    </div>

    <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">Total No. of Candidates (Estimated) :</label>
      <div class="col-sm-8">
      <table class="table table-bordered" style="border-color:#ccc; margin:0;">
      <tbody>
        <tr>
          <?php if($batch['hours'] == '100') { ?>
          <th style="text-align:center; border-color:#ccc">10th Pass</th>
          <th style="text-align:center; border-color:#ccc">12th Pass</th>
          <?php } ?>
          <th style="text-align:center; border-color:#ccc">Graduate</th>
          <th style="text-align:center; border-color:#ccc">Total</th>
        </tr>
        <tr>
          <?php if($batch['hours'] == '100') { ?>
          <td style="text-align:center; border-color:#ccc"><?php echo intval($batch['tenth_pass_candidates']); ?></td>
          <td style="text-align:center; border-color:#ccc"><?php echo intval($batch['twelth_pass_candidates']); ?></td>
          <?php } ?>
          <td style="text-align:center; border-color:#ccc"><?php echo intval($batch['graduate_candidates']); ?></td>
          <td style="text-align:center; border-color:#ccc"><?php echo intval($batch['total_candidates']); ?></td>
        </tr>
      </tbody>
    </table>
    </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    
    <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">No.of Registered Candidates (Actual) :</label>
      <div class="col-sm-8">
        <table class="table table-bordered" style="border-color:#ccc; margin:0;">
          <tbody>
            <tr>
              <?php if($batch['hours'] == '100') { ?>
              <th style="text-align:center; border-color:#ccc">10th Pass</th>
              <th style="text-align:center; border-color:#ccc">12th Pass</th>
              <?php } ?>
              <th style="text-align:center; border-color:#ccc">Graduate</th>
              <th style="text-align:center; border-color:#ccc">Total</th>
            </tr>
            <tr>
              <?php if($batch['hours'] == '100') { ?>
                <td style="text-align:center; border-color:#ccc"><?php echo $tenth_members_count; ?></td>
                <td style="text-align:center; border-color:#ccc"><?php echo $twelth_members_count; ?></td>
                <?php } ?>
                <td style="text-align:center; border-color:#ccc"><?php echo $graduate_members_count; ?></td>
                <td style="text-align:center; border-color:#ccc"><?php echo $tenth_members_count+$twelth_members_count+$graduate_members_count; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    
    <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label"> FACULTY I:</label>
      <div class="col-sm-5"><?php echo $batch['first_faculty_code']."_".$batch['first_faculty_salutation']." ".$batch['first_faculty_name'];?></div>
    </div>
    
    <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">FACULTY II:</label>
      <div class="col-sm-5"><?php echo $batch['sec_faculty_code']."_".$batch['sec_faculty_salutation']." ".$batch['sec_faculty_name'];?></div>
    </div>

    <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">ADDITIONAL FACULTY I :</label>
      <div class="col-sm-5">
        <?php echo $batch['add_first_faculty_code']."_".$batch['add_first_faculty_salutation']." ".$batch['add_first_faculty_name'];?>
      </div>
    </div>

    <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">ADDITIONAL FACULTY II :</label>
      <div class="col-sm-5">
        <?php echo $batch['add_sec_faculty_code']."_".$batch['add_sec_faculty_salutation']." ".$batch['add_sec_faculty_name'];?>
      </div>
    </div>

    <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">DETAILS OF ADDITIONAL FACULTY I:</label>
      <div class="col-sm-5">
        <?php
          $add_first_faculty_name = '';
          $add_first_faculty_qual = ''; 
            if($batch['add_first_faculty_name'] != '' && $batch['add_first_faculty_name'] != 0 ) {
              $add_first_faculty_name = $batch['add_first_faculty_name'];
            }
            if($batch['add_first_faculty_qualification'] != '' ) {
              $add_first_faculty_qual = " (".$batch['add_first_faculty_qualification'].")";
            }
            echo $add_first_faculty_name.$add_first_faculty_qual;  
          ?>
      </div>
    </div>
    
    <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">DETAILS OF ADDITIONAL FACULTY II:</label>
      <div class="col-sm-5">
        <?php
          $add_sec_faculty_name = '';
          $add_sec_faculty_qual = ''; 
            if($batch['add_sec_faculty_name'] != '' && $batch['add_sec_faculty_name'] != 0) {
              $add_sec_faculty_name = $batch['add_sec_faculty_name'];
            }
            if($batch['add_sec_faculty_qualification'] != '' ) {
              $add_sec_faculty_qual = " (".$batch['add_sec_faculty_qualification'].")";
            }
            echo $add_sec_faculty_name.$add_sec_faculty_qual;  
          ?>
       </div>
    </div>
    
    <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">BATCH COORDINATOR NAME:</label>
      <div class="col-sm-5"><?=$batch['contact_person_name'];?></div>
    </div>
    
    
    <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">CONTACT NUMBER OF THE COORDINATOR: </label>
      <div class="col-sm-5"><?=$batch['contact_person_phone'];?></div>
    </div>

    <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">ALTERNATIVE CONTACT PERSON NAME:</label>
      <div class="col-sm-5"><?=$batch['alt_contact_person_name'];?></div>
    </div>
    
    
    <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">CONTACT NUMBER OF THE CONTACT PERSON:</label>
      <div class="col-sm-5"><?=$batch['alt_contact_person_phone'];?></div>
    </div>
    
     <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">ADDRESS LINE-1 :</label>
      <div class="col-sm-5"><?=$batch['addressline1'];?></div>
    </div>
     <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">ADDRESS LINE-2 :</label>
      <div class="col-sm-5"><?=$batch['addressline2'];?></div>
    </div>
     <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">ADDRESS LINE-3 :</label>
      <div class="col-sm-5"><?=$batch['addressline3'];?></div>
    </div>
     <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">ADDRESS LINE-4 :</label>
      <div class="col-sm-5"><?=$batch['addressline4'];?></div>
    </div>
    
    <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">STATE :</label>
      <div class="col-sm-5"><?=$batch['state_name'];?></div>
    </div>
     <?php if($batch['city_name']!="")
      {
          $city_name = $batch['city_name'];
      } 
      else
      { 
        $city_name = $batch['location_name'];
      }?>
    <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">CITY :</label>
      <div class="col-sm-5"><?=$city_name;?></div>
    </div>
     <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">DISTRICT :</label>
      <div class="col-sm-5"><?=$batch['district'];?></div>
    </div>
     <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">PINCODE :</label>
      <div class="col-sm-5"><?=$batch['pincode'];?></div>
    </div>
    <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">NAME OF BANK/OTHERS :</label>
      <div class="col-sm-5"><?=$batch['name_of_bank'];?></div>
    </div>
    <div class="form-group">
      <label for="total_candidates" class="col-sm-4 control-label">REMARK :</label>
      <div class="col-sm-5"><?=$batch['remarks'];?></div>
    </div>
		
		<?php if($batch['batch_online_offline_flag'] == 1) { ?>				
			<div class="form-group">
				<label for="total_candidates" class="col-sm-4 control-label">ONLINE TRAINING PLATFORM :</label>
				<div class="col-sm-5"><?php echo $batch['online_training_platform']; ?></div>
			</div>

      <div class="form-group">
        <label for="total_candidates" class="col-sm-4 control-label">ONLINE TRAINING PLATFORM URL :</label>
        <div class="col-sm-5"><?php echo $batch['platform_link']; ?></div>
      </div>
			
			<div class="form-group">
				<label for="total_candidates" class="col-sm-4 control-label">ONLINE BATCH LOGIN DETAILS :</label>
				<div class="col-sm-5">
					<table class="table table-bordered" style="border-color:#ccc; margin:0;">
						<tbody>
							<tr>
								<th style="text-align:center; border-color:#ccc">Sr. No</th>
								<th style="text-align:center; border-color:#ccc">Login ID</th>
								<th style="text-align:center; border-color:#ccc">Password</th>
							</tr>
							<?php 
              //print_r($online_batch_user_details);
              if(isset($online_batch_user_details) && count($online_batch_user_details) > 0)
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
              }
							?>
						</tbody>
					</table>
				</div>
			</div>
		<?php } ?>
    
    <?php  
        }
      
    ?>


    

    

              

              
            </div>
          </div>
        </div>


        <?php
      $k = 1;
      if (count($agency_batch_logs) > 0) { ?>
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Training Batch Status Logs</h3>
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
                      <th>Action Date </th>
                      <th>Reason</th>
                    </tr>
                  </thead>
                  <tbody class="no-bd-y" id="list222">
                    <?php foreach ($agency_batch_logs as $res_log) {
                      $pre_text = '';
                      $log_data = unserialize($res_log['description']);


                      $log_data = unserialize($res_log['description']);
                      $pre_text = '';

                      //echo '---'.$res_log['userid'].'---'.$res_log['institute_name'];

                      if (isset($res_log['userid'])) {
                        $admin_name = $res_log['institute_name'];
                      } else {
                        $admin_name = '';
                      }


                      if (isset($log_data['rejection'])) {
                        //$pre_text = 'Rejected by';            
                        $rejection_reasion = '<span class="red"> ' . $log_data['rejection'] . '</span>';
                        /*if(!$agency_center_logs_length ){
                                    $reject_action_date = $res_log['date'];
                                  }*/
                        if ($k == 1) {
                          $reject_action_date = $res_log['date'];
                        }
                      } else {
                        $rejection_reasion = '';
                      }

                      /*if (isset($log_data['updated_by'])) {

                        if ($log_data['updated_by'] == 1  || $log_data['updated_by'] == 'A') {

                          $update_by = ' by ' . $admin_name . ' (A) ';
                        } else {
                          $update_by = ' by ' . $admin_name . '   (R) ';
                        }
                      } else {
                        $update_by = '';
                      }*/

                      if (isset($log_data['center_validity_to'])) {

                        $pre_text = 'Updated Accreditation ';
                        $Accridation_text = ' : ' . date_format(date_create($log_data['center_validity_from']), "d-M-Y") . ' - ' . date_format(date_create($log_data['center_validity_to']), "d-M-Y");
                      } else {

                        $Accridation_text = '';
                      }
                    ?>

                      <tr>
                        <td><?php echo $k; ?></td>
                        <td><?php echo str_replace("DRA Admin", "", $res_log['title']) . ' ' . $Accridation_text; ?></td>
                        <td><?php echo date_format(date_create($res_log['date']), "d-M-Y H:i:s"); ?></td>
                        <td><?php echo $rejection_reasion; ?></td>
                      </tr>

                    <?php $k++;
                    } ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- box-footer -->
          </div>
          <!-- /.box -->
        </div>
      <?php } ?>
    



      </div>
    </section>
</div>

