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

  .status_div {
    font-weight: 800 !important;
  }

  .status {
    color: #223fcc;
    font-weight: 800;
  }

  .myview .form-group {
    clear: both;
  }

  .total_candidates {
    text-align: right;
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
    color: #cc0000;
    font-weight: 800;
  }

  .statusrs {
    color: #7b3ede font-weight: 800;
  }

  .form-group>label {
    line-height: 18px;
    margin-bottom: 15px;
  }

  .form-group>div {
    line-height: 18px;
    margin-bottom: 15px;
  }

  .wrap_text
  {
    word-wrap: break-word;
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
          } ?>
    <?php if ($bstatus == 'IR' || $bstatus == 'R') { ?>
     
   <p>Please go through the given detail, correction may be made if necessary. <a href="<?php //echo base_url().'iibfdra/TrainingBatches/edit/'.base64_encode($bid)
                                                                                        ?>">Modify</a></p>
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
              <?php if ($menu == 'batch_checklist') { ?>
                <a href="<?php echo base_url(); ?>iibfdra/TrainingBatches/batch_checklist" class="btn btn-warning">Back</a>
              <?php } else { ?>
                <a href="<?php echo base_url(); ?>iibfdra/TrainingBatches/" class="btn btn-warning">Back</a>
              <?php } ?>
            </div>
          </div>

          <div class="box-body" style="padding: 15px 10px 10px 10px">
            <?php
            $institute_name = '';
            $drainstdata = $this->session->userdata('dra_institute');
            if ($drainstdata) {
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
              //print_r($batch);
              $status = isset($batch['batch_status']) ? $batch['batch_status'] : '';
              ?>

              <input type="hidden" name="agency_id" id="agency_id" value="<?php echo $batch['agency_id']; ?>">
              <input type="hidden" name="batch_id" id="batch_id" value="<?php echo $batch['id']; ?>">

              <?php
              if ($batch['batch_status'] == "In Review") {
                $status = '<div class="col-sm-2 statusi">In Review</div>';
              }
              if ($batch['batch_status'] == "Final Review") {
                $status = '<div class="col-sm-2 statusf">Final Review</div>';
              }
              if ($batch['batch_status'] == "Re-Submitted") {
                $status = '<div class="col-sm-2 statusrs">Re-Submitted</div>';
              }
              if ($batch['batch_status'] == "Batch Error") {
                $status = '<div class="col-sm-2 statusbe">Batch Error</div>';
              }
              if ($batch['batch_status'] == "Approved") {
                $status = '<div class="col-sm-2 statusa">Go Ahead</div>';
              }
              if ($batch['batch_status'] == "Rejected") {
                $status = '<div class="col-sm-2 statusr">Rejected</div>';
              }
              if ($batch['batch_status'] == "Hold") {
                $status = '<div class="col-sm-2 statush">Hold</div>';
              }
              if ($batch['batch_status'] == "UnHold") {
                $status = '<div class="col-sm-2 statuuh">UnHold</div>';
              }
              if ($batch['batch_status'] == "Cancelled") {
                $status = '<div class="col-sm-2 statusc">Cancelled</div>';
              }
              ?>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Batch Status :</label>
                <?php echo $status; ?>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Name of Training Agency :</label>
                <div class="col-sm-8"> <?= $institute_name; ?></div>
              </div>
              <?php if ($batch['city_name'] != "") {
                $location = $batch['city_name'];
              } else {
                $location = $batch['location_name'];
              } ?>
              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Center Name :</label>
                <div class="col-sm-8"><?= $location; ?></div>
              </div>

              <!--  <div class="form-group">
              <label for="total_candidates" class="col-sm-4 control-label">INSPECTOR NAME :</label>
              <div class="col-sm-8"></div>
            </div> -->

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Batch code - Batch Mode:</label>
                <div class="col-sm-8">
                  <?php echo  $batch['batch_code']; ?>
                  <?php if ($batch['batch_online_offline_flag'] == 1) {
                    echo " - Online";
                  } else echo ' - Offline'; ?>
                </div>
              </div>
              <?php /*?><div class="form-group">
              <label for="total_candidates" class="col-sm-4 control-label">BATCH TYPE :</label>
              <div class="col-sm-8"><span class="types"><?php echo $batch['batch_type']; ?></span></div>
            </div><?php */ ?>
              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Batch Type :</label>
                <div class="col-sm-8"><span class="types"><?php echo $batch['hours']; ?> Hours</span></div>
              </div>


              <?php if ($reason != '') {
                //$reason = $this->master_model->getValue('agency_batch_rejection',array('batch_id'=>$batch['id']), 'rejection');
              ?>
                <div class="form-group">
                  <label for="total_candidates" class="col-sm-4 control-label">Reason :</label>
                  <div class="col-sm-8"><?= $reason; ?></div>
                </div>
              <?php }
              ?>
              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Batch Training Period :</label>
                <div class="col-sm-8">
                  <?php
                  if ($batch['batch_from_date'] != '' && $batch['batch_to_date'] != ''  && $batch['batch_to_date'] != '0000-00-00') { ?>
                    FROM <strong><?php echo date_format(date_create($batch['batch_from_date']), "d-M-Y"); ?> </strong> TO <strong> <?php echo date_format(date_create($batch['batch_to_date']), "d-M-Y"); ?></strong>
                    <strong>
                      <?php
                      // show date diffrce by Manoj 
                      $date1 = date_create(date_format(date_create($batch['batch_from_date']), "Y-M-d"));
                      $date2 = date_create(date_format(date_create($batch['batch_to_date']), "Y-M-d"));
                      $diff = date_diff($date1, $date2);
                      if ($diff) {
                        echo '(' . ($diff->days + 1) . ' days)';
                      }
                      ?>
                    </strong>
                  <?php  } else { ?>
                    <strong>-Training Period Not Added-</strong>
                  <?php   } ?>
                </div>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Holiday(s) :</label>
                <div class="col-sm-8">
                  <strong><?php /*echo $batch['holidays'];*/ echo str_replace(",", ", ", $disp_holidays); ?></strong>
                </div>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Net Training Days :</label>
                <div class="col-sm-8">
                  <strong><?php echo $batch['net_days']; ?></strong>
                </div>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Daily Training Timing :</label>
                <div class="col-sm-8">
                  <?php if ($batch['timing_from'] != '' && $batch['timing_to'] != '') {  ?>
                    FROM <strong><?php echo $batch['timing_from']; ?> </strong>
                    TO <strong><?php echo $batch['timing_to']; ?></strong>
                  <?php } else { ?>
                    <strong>-Training time Not Added-</strong>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group">
                <?php
                $gross_time = explode(':', $batch['gross_time']);
                ?>
                <label for="total_candidates" class="col-sm-4 control-label">Gross Training Time Per Day :</label>
                <div class="col-sm-8">
                  <strong>
                    <?php if ($gross_time[0] != "" && $gross_time[1] != "") {
                      echo $gross_time[0] . ' hr : ' . $gross_time[1] . 'min';
                    } ?>
                  </strong>
                </div>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Daily Break Time :</label>
                <div class="col-sm-8">
                  <?php
                  $time = intval($batch['brk_time1']) + intval($batch['brk_time2']) + intval($batch['brk_time2']);
                  $hours = floor($time / 60);
                  $minutes = ($time % 60);
                  ?>
                  <strong><?php if ($batch['total_break_time'] != "") {
                            echo $batch['total_break_time'];
                          } ?> </strong>
                </div>
              </div>

              <div class="form-group">
                <?php
                $net_time = explode(':', $batch['net_time']);
                ?>
                <label for="total_candidates" class="col-sm-4 control-label">Net Training Time Per Day :</label>
                <div class="col-sm-8">
                  <strong><?php if ($net_time[0] != "" && $net_time[1] != "") {
                            echo $net_time[0] . ' hr : ' . $net_time[1] . 'min';
                          } ?></strong>
                </div>
              </div>

              <div class="form-group">
                <?php
                $total_net_time = explode(':', $batch['total_net_time']);
                ?>
                <label for="total_candidates" class="col-sm-4 control-label">Total Net Training Time of Duration :</label>
                <div class="col-sm-8">
                  <strong><?php if ($total_net_time[0] != "" && $total_net_time[1] != "") {
                            echo $total_net_time[0] . ' hr : ' . $total_net_time[1] . 'min';
                          } ?></strong>
                </div>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Training Language :</label>
                <div class="col-sm-8"><?= $batch['training_medium']; ?></div>
              </div>

              <?php if ($batch['hours'] == 100) { ?>
                <div class="form-group">
                  <label for="total_candidates" class="col-sm-4 control-label">10th Pass Candidates :</label>
                  <div class="col-sm-8"><?php echo $batch['tenth_pass_candidates']; ?></div>
                </div>

                <div class="form-group">
                  <label for="total_candidates" class="col-sm-4 control-label">12th Pass Candidates :</label>
                  <div class="col-sm-8"><?php echo $batch['twelth_pass_candidates']; ?></div>
                </div>
              <?php } ?>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Graduate Candidates :</label>
                <div class="col-sm-8"><?php echo $batch['graduate_candidates']; ?></div>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Total No. of Candidates :</label>
                <div class="col-sm-8"><?php echo $batch['total_candidates']; ?></div>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">1st Faculty Details :</label>
                <div class="col-sm-8"><?php echo $batch['first_faculty_name']; ?></div>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">2nd Faculty Details :</label>
                <div class="col-sm-8"><?php
                                      if ($batch['sec_faculty_name'] != '') {
                                        echo $batch['sec_faculty_name'];
                                      }
                                      ?></div>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Additional Faculty I Details :</label>
                <div class="col-sm-8"><?php echo $batch['add_first_faculty_name']; ?></div>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Additional Faculty II Details :</label>
                <div class="col-sm-8"><?php
                                      if ($batch['add_sec_faculty_name'] != '') {
                                        echo $batch['add_sec_faculty_name'];
                                      }
                                      ?></div>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Training Schedule :</label>
                <div class="col-sm-8">
                  <?php if ($batch['training_schedule'] != "") { ?>
                    <a href="<?php echo base_url('uploads/training_schedule/' . $batch['training_schedule']); ?>" target="_blank">View Document</a>
                  <?php } ?>
                </div>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Training Batch State :</label>
                <div class="col-sm-8"><?= $batch['state_name']; ?></div>
              </div>

              <?php if ($batch['city_name'] != "") {
                $city_name = $batch['city_name'];
              } else {
                $city_name = $batch['location_name'];
              } ?>
              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Training Batch District :</label>
                <div class="col-sm-8"><?= $batch['district']; ?></div>
              </div>
              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Training Batch City :</label>
                <div class="col-sm-8"><?= $city_name; ?></div>
              </div>
              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Training Batch Pincode :</label>
                <div class="col-sm-8"><?= $batch['pincode']; ?></div>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Training Batch Address Line 1 :</label>
                <div class="col-sm-8"><?= $batch['addressline1']; ?></div>
              </div>
              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Training Batch Address Line 2 :</label>
                <div class="col-sm-8"><?= $batch['addressline2']; ?></div>
              </div>
              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Training Batch Address Line 3 :</label>
                <div class="col-sm-8"><?= $batch['addressline3']; ?></div>
              </div>
              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Training Batch Address Line 4 :</label>
                <div class="col-sm-8"><?= $batch['addressline4']; ?></div>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Batch Coordinator Name :</label>
                <div class="col-sm-8"><?= $batch['contact_person_name']; ?></div>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Batch Coordinator Mobile No : </label>
                <div class="col-sm-8"><?= $batch['contact_person_phone']; ?></div>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Alternative Contact Person Name :</label>
                <div class="col-sm-8"><?= $batch['alt_contact_person_name']; ?></div>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Alternative Contact Person Contact Number :</label>
                <div class="col-sm-8"><?= $batch['alt_contact_person_phone']; ?></div>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Name of Bank / Agency / Mixed (Source of Candidates) :</label>
                <div class="col-sm-8"><?= $batch['name_of_bank']; ?></div>
              </div>

              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Remarks :</label>
                <div class="col-sm-8 wrap_text"><?php echo $batch['remarks'];?></div>
              </div>

              <?php if ($batch['batch_online_offline_flag'] == 1) { ?>
                <div class="form-group">
                  <label for="total_candidates" class="col-sm-4 control-label">Online Training Platform :</label>
                  <div class="col-sm-8"><?php echo $batch['online_training_platform']; ?></div>
                </div>

                <div class="form-group">
                  <label for="total_candidates" class="col-sm-4 control-label">Online Training Platform Link :</label>
                  <div class="col-sm-8"><?php echo $batch['platform_link']; ?></div>
                </div>

                <div class="form-group">
                  <label for="total_candidates" class="col-sm-4 control-label">Online Batch Login Details :</label>
                  <div class="col-sm-8">
                    <table class="table table-bordered" style="border-color:#ccc; margin:10px 0 15px 0;">
                      <tbody>
                        <tr>
                          <th style="text-align:center; border-color:#ccc">Sr. No</th>
                          <th style="text-align:center; border-color:#ccc">Login ID</th>
                          <th style="text-align:center; border-color:#ccc">Password</th>
                        </tr>
                        <?php if (isset($online_batch_user_details) && count($online_batch_user_details) > 0) {
                          $sr_no = 1;
                          foreach ($online_batch_user_details as $online_batch) {  ?>
                            <tr>
                              <td style="text-align:center; border-color:#ccc"><?php echo $sr_no; ?></td>
                              <td style="border-color:#ccc"><?php echo $online_batch['login_id']; ?></td>
                              <td style="border-color:#ccc"><?php echo base64_decode($online_batch['password']); ?></td>
                            </tr>
                        <?php $sr_no++;
                          }
                        }  ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              <?php } ?>

            <?php } ?>

            <?php if ($menu == 'training_batches') { ?>
              <div class="form-group">
                <label for="total_candidates" class="col-sm-4 control-label">Batch Communication:</label>
                <div class="col-sm-8">
                  <textarea name="batch_communication" id="batch_communication" maxlength="1000" rows="4" cols="50" placeholder="Enter Remark here"></textarea><br>
                  <span class="note-error" id="batch_communication_error"></span>
                  <a class="update_batch_communication btn btn-primary" href="javascript:void(0);" style="margin-top:10px;display:block; max-width:100px;">Submit</a>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>


      <?php //if($menu == 'batch_checklist') { 
      ?>
      <div class="col-xs-12">
        <div class="box box-info batch_checklist" style="display: none;">
          <div class="box-body">
            <div class="form-group">
              <label for="total_candidates" class="col-sm-4 control-label">Take Action</label>
              <div class="form-check">
                <input type="radio" class="form-check-input radiobtn control-label" id="final_review" name="radiobt" value="final_review">Submit Batch to IIBF for Approval

                <!-- <input type="radio" class="form-check-input radiobtn control-label" id="add_remark" name="radiobt" value="add_remark" >Add Remark and send for correction -->
              </div>
            </div>

            <div class="form-group">
              
              <label for="total_candidates" class="col-sm-4 control-label"></label>
              <?php /*<div id="reason_div" style="display: none;">
                <div class="form-group">
                  <label for="remarks " class="col-sm-4 control-label">Explaination / Remarks on the batch, if any </label>
                  <div class="col-sm-6">
                    <textarea style="width:100%; text-align:left;" name="remarks" id="remarks" maxlength="1000" placeholder="Additional Information, if any" rows="6"><?php echo set_value('remarks'); ?></textarea>
                    <span class="note" id="addressline1">Note: You can Enter maximum 1000 Characters</span></br>
                  </div>
                </div>
              </div> */ ?>

              <span id="btn" style="display: none;">
                <input type="button" name="btn_status" id="btn_status" value="Submit" onclick="change_status()">
              </span>
            </div>
          </div>
        </div>
      </div>
      <?php //}
      ?>

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
                        <td><?php echo date_format(date_create($res_log['date']), "d-M-Y h:i:s"); ?></td>
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

      <?php
      /*$k = 1;
      if (count($batch_checklist_logs) > 0) { ?>
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Batch Checklist Logs</h3>
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
                      <!-- <th>Description</th> -->
                    </tr>
                  </thead>
                  <tbody class="no-bd-y" id="list222">
                    <?php foreach ($batch_checklist_logs as $logs) { ?>

                      <tr>
                        <td><?php echo $k; ?></td>
                        <td><?php echo $logs['status']; ?></td>
                        <td><?php echo date_format(date_create($logs['created_on']), "d-M-Y h:i:s"); ?></td>
                        <?php //<td><?php echo $logs['reason']; ?></td> ?>
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
      <?php }*/ ?>

      <?php
      $k = 1;
      if (count($activity_logs) > 0) { ?>
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Activity Logs</h3>
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
                      <th>Description</th>
                    </tr>
                  </thead>
                  <tbody class="no-bd-y" id="list222">
                    <?php foreach ($activity_logs as $logs) { ?>

                      <tr>
                        <td><?php echo $k; ?></td>
                        <td><?php echo $logs['title']; ?></td>
                        <td><?php echo date_format(date_create($logs['date']), "d-M-Y h:i:s"); ?></td>
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
      <?php  } ?>
    </div>
  </section>
</div>

<script type="text/javascript">
  var base_url = '<?php echo base_url(); ?>';
  var menu = '<?php echo $menu; ?>';
  var status = '<?php echo $batch['batch_status']; ?>';
  console.log('status' + status);
  if (menu == 'batch_checklist' && status == 'In Review') {
    console.log('yes');
    $('.batch_checklist').css('display', 'block');
  }

  $(document).ready(function() {

    $("body").on("contextmenu", function(e) {
      return false;
    });

    $('.radiobtn').click(function() {
      var status = $(this).val();
      console.log(status);
      if (status == 'add_remark') {
        $('#reason_div').css('display', 'block');
        $('#btn').css('display', 'block');
      }

      if (status == 'final_review') {
        $('#reason_div').css('display', 'none');
        $('#btn').css('display', 'block');
      }

    });

    $('.update_batch_communication').click(function() {
      console.log('update_batch_communication');
      var agency_id = $('#agency_id').val();
      var batch_id = $('#batch_id').val();
      var batch_communication = $('#batch_communication').val();

      if (batch_communication == '') {
        $('#batch_communication_error').text('Please Enter Batch Communication Remark');
      } else {
        $('#batch_communication_error').text('');
        $.ajax({
          url: site_url + 'iibfdra/TrainingBatches/agency_update_batch_communication',
          data: {
            agency_id: agency_id,
            batch_id: batch_id,
            batch_communication: batch_communication
          },
          type: 'POST',
          async: false,
          success: function(response) {
            if (response == 1) {
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
  });

  function change_status() {
    var err_cnt = 0;
    var status = $("input[name='radiobt']:checked").val();
    var reason = $('#remarks').val();
    var batch_id = '<?php echo $batch['id']; ?>';

    if (err_cnt == 0) {
      $.ajax({
        type: 'POST',
        url: base_url + "iibfdra/TrainingBatches/change_status",
        data: {
          batch_id: batch_id,
          status: status,
          reason: reason
        },
        dataType: "text",
        success: function(data) {
          console.log(data);
          if (data.trim() == 1) {

            swal({
              title: 'Status Changed!',
              text: 'Status Changed Successfully...',
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

            /*if(status == 'final_review'){
              $('.batch_checklist').css('display','none');
              location.reload();
            }
            else{
              window.location = base_url+'TrainingBatches/batch_checklist';
            }*/

          } else if (data.trim() == 0) {
            swal('Something went wrong...', '', 'danger');
          }

        }
      });
    }

  }
</script>