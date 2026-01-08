<?php $this->load->view('admin/includes/header'); ?>
<?php $this->load->view('admin/includes/sidebar'); ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <!-- <?php //echo  $new_registration_count_M; die;
            ?> -->
      As on date report
    </h1>
  </section>
  <section class="content">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Select Date:</h3>
        <form class="form-horizontal" action="<?php echo base_url(); ?>admin/Kycmember/asondate" method="post">
          <div class="pull-left">
            <div class="form-group">

              <label for="from_date" class="col-sm-2">From:</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="from_date" name="from_date" placeholder="From Date" required value="<?php echo set_value('from_date'); ?>">
                <span class="error"><?php echo form_error('from_date'); ?></span>
              </div>

              <label for="to_date" class="col-sm-2">To:</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="to_date" name="to_date" placeholder="To Date" value="<?php echo set_value('to_date'); ?>" required>
                <span class="error"><?php echo form_error('to_date'); ?></span>
              </div>
              <input type="submit" class="btn btn-info" name="submit" value="Search">
            </div>
          </div>



        </form>
      </div>
      <div class="form-group">
        <div class="box-body">



          <center>
            <?php //if(set_value('from_date') != ''){ 
            ?>
            <!-- <b> Counts  From <?php echo set_value('from_date'); ?> To <?php echo   set_value('to_date'); ?></b> -->
            <?php //} else{ 
            ?>
            <!-- <b> Counts  From <?php echo $this->config->item('kyc_start_date'); ?> To <?php echo   date('Y-m-d'); ?></b> -->
            <?php //} 
            ?>

            <b> Counts From <?php echo $from_date; ?> To <?php echo $end_date; ?></b>

            <div class="col-sm-12">
              <div class="col-sm-6">
                <div class="panel panel-default">
                  <div class="panel-body">
                    <b> Total Members Applied For Duplicate Icard</b>
                  </div>
                  <div class="panel-footer"><?php echo $dup_card_count; ?></div>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="panel panel-default">
                  <div class="panel-body">
                    <b> Total Membership Icard Download Count</b>
                  </div>
                  <div class="panel-footer"><?php echo $dwn_mem_icard_count; ?></div>
                </div>
              </div>
            </div>

          </center>




        </div>

      </div>



      <div class="box-body" style="display: block;">
        <div class="table-responsive">
          <table class="table table-bordered">
            <tr style="background-color:#7FD1EA;">

              <td width="55%"><strong>Title</strong></td>
              <td width="15%" class="text-center" nowrap="nowrap"><strong>Odinary Member (O)</strong></td>
              <td width="15%" class="text-center" nowrap="nowrap"><strong>Non member (NM & DB) </strong></td>

            </tr>
            <tr>
              <td> Total New Registration</td>
              <td class="text-center"><?php echo  $new_registration_count_M; ?></td>
              <td class="text-center"><?php echo $new_registration_count_NM; ?></td>

            </tr>
            <tr><?PHP // ADDED BY POOJA MANE 16-11-2023?>
              <td> Total Profile Not Edited</td>
              <td class="text-center"><?php echo  $profile_not_edited_count_M; ?></td>
              <td class="text-center"><?php echo $new_registration_count_NM; ?></td>
              <?PHP // ADDED BY POOJA MANE END 16-11-2023?>
            </tr>
            <tr>
              <td>Total Edited Profile</td>
              <td class="text-center"><?php echo  $edit_registration_count_M; ?></td>
              <td class="text-center"><?php echo   $edit_registration_count_NM; ?></td>

            </tr>

          </table>
          <table class="table table-bordered">
            <tr style="background-color:#7FD1EA;">

              <td width="55%"><strong>Title</strong></td>
              <td width="15%" class="text-center" nowrap="nowrap"><strong>Odinary Member (O)</strong></td>
              <td width="15%" class="text-center" nowrap="nowrap"><strong>Non member (NM & DB) </strong></td>

            </tr>
            <tr>
              <td> KYC approved for New member </td>
              <td class="text-center"><?php echo $approve_new_member ?></td>
              <td class="text-center"><?php echo $approve_new_nonmember ?></td>

            </tr>
            <tr>
              <td>KYC approved for Edit member</td>
              <td class="text-center"><?php echo $approve_edit_member; ?></td>
              <td class="text-center"><?php echo $approve_edit_nonmember; ?></td>

            </tr>

          </table>
          <table class="table table-bordered">
            <tr style="background-color:#7FD1EA;">

              <td width="55%"><strong>Pending for recommender</strong></td>
              <td width="15%" class="text-center" nowrap="nowrap"><strong>Odinary Member (O)</strong></td>
              <td width="15%" class="text-center" nowrap="nowrap"><strong>Non member (NM & DB) </strong></td>
              <td width="15%" class="text-center" nowrap="nowrap"><strong>Download</strong></td>
            </tr>
            <tr>
              <td> New member </td>
              <td class="text-center"><?php echo $pending_new_list_member; ?></td>
              <td class="text-center"><?php echo $pending_new_list_nonmembers ?></td>
              <td class="text-center"><a data-toggle="tooltip" class="btn btn-warning" href="<?php echo base_url('admin/Kycmember/recommender_new_download_CSV/' . $from_date . '/' . $end_date); ?>"> Download CSV </a></td>

            </tr>
            <tr>
              <td>Edit member </td>
              <td class="text-center"><?php echo $pending_edit_member; ?></td>
              <td class="text-center"><?php echo $pending_edit_nonmember; ?></td>
              <td class="text-center"><a data-toggle="tooltip" class="btn btn-warning" href="<?php echo base_url('admin/Kycmember/recommender_edit_download_CSV/' . $from_date . '/' . $end_date); ?>"> Download CSV </a></td>
            </tr>

          </table>
          <table class="table table-bordered">
            <tr style="background-color:#7FD1EA;">
              <td width="55%"><strong>Pending for approver</strong></td>
              <td width="15%" class="text-center" nowrap="nowrap"><strong>Odinary Member (O)</strong></td>
              <td width="15%" class="text-center" nowrap="nowrap"><strong>Non member (NM & DB) </strong></td>
              <td width="15%" class="text-center" nowrap="nowrap"><strong>Download</strong></td>
            </tr>
            <tr>
              <td> New member </td>
              <td class="text-center"><?php echo $approver_new_pending; ?></td>
              <td class="text-center"><?php echo $approver_new_pending_non ?></td>
              <td class="text-center"><a data-toggle="tooltip" class="btn btn-warning" href="<?php echo base_url('admin/Kycmember/approver_new_download_CSV/' . $from_date . '/' . $end_date); ?>"> Download CSV </a></td>

            </tr>
            <tr>
              <td>Edit member </td>
              <td class="text-center"><?php echo $approver_edit_pending; ?></td>
              <td class="text-center"><?php echo $approver_edit_pending_non; ?></td>
              <td class="text-center"><a data-toggle="tooltip" class="btn btn-warning" href="<?php echo base_url('admin/Kycmember/approver_edit_download_CSV/' . $from_date . '/' . $end_date); ?>"> Download CSV </a></td>
            </tr>

          </table>
          <table class="table table-bordered">
            <tr style="background-color:#7FD1EA;">
              <td width="55%"><strong>Recommender Rejected Members</strong></td>
              <td width="15%" class="text-center" nowrap="nowrap"><strong>Odinary Member (O)</strong></td>
              <td width="15%" class="text-center" nowrap="nowrap"><strong>Non member (NM & DB) </strong></td>
              <td width="15%" class="text-center" nowrap="nowrap"><strong>Download</strong></td>
            </tr>
            <tr>
              <td> Rejected New member </td>
              <td class="text-center"><?php echo $rec_rejected_count_o_mem; ?></td>
              <td class="text-center"><?php echo $rec_rejected_count_non_mem ?></td>
              <td class="text-center"><a data-toggle="tooltip" class="btn btn-warning" href="<?php echo base_url('admin/Kycmember/recommender_rejected_download_CSV'); ?>"> Download CSV </a></td>
            </tr>
            <tr>
              <td> Rejected edit member </td>
              <td class="text-center"><?php echo $rec_rejected_count_edit_o_mem; ?></td>
              <td class="text-center"><?php echo $rec_rejected_count_edit_non_mem ?></td>
              <td class="text-center"><a data-toggle="tooltip" class="btn btn-warning" href="<?php echo base_url('admin/Kycmember/recommender_rejected_download_edited_CSV'); ?>"> Download CSV </a></td>
            </tr>
          </table>

          <table class="table table-bordered">
            <tr style="background-color:#7FD1EA;">
              <td width="55%"><strong>Approver Rejected Members</strong></td>
              <td width="15%" class="text-center" nowrap="nowrap"><strong>Odinary Member (O)</strong></td>
              <td width="15%" class="text-center" nowrap="nowrap"><strong>Non member (NM & DB) </strong></td>
              <td width="15%" class="text-center" nowrap="nowrap"><strong>Download</strong></td>
            </tr>
            <tr>
              <td> Rejected New member </td>
              <td class="text-center"><?php echo $ap_rejected_count_o_mem; ?></td>
              <td class="text-center"><?php echo $ap_rejected_count_non_mem ?></td>
              <td class="text-center"><a data-toggle="tooltip" class="btn btn-warning" href="<?php echo base_url('admin/Kycmember/approver_rejected_download_CSV'); ?>"> Download CSV </a></td>
            </tr>
            <tr>
              <td> Rejected edit member </td>
              <td class="text-center"><?php echo $ap_rejected_count_edit_o_mem; ?></td>
              <td class="text-center"><?php echo $ap_rejected_count_edit_non_mem ?></td>
              <td class="text-center"><a data-toggle="tooltip" class="btn btn-warning" href="<?php echo base_url('admin/Kycmember/approver_rejected_download_edited_CSV'); ?>"> Download CSV </a></td>
            </tr>
          </table>
        </div>
      </div>

      <!-- 
      - SAGAR WALZADE : Code start here
      - New code : for developer reference only to get count status by addition of new/edit - ordinary/non members count.
      -->
      <div class="box-body" style="display: none;">
        <h3 class="text-center">For developer reference only</h3>
        <div class="table-responsive">
          <table class="table table-bordered">
            <tr style="background-color:#F1DAC6;">
              <td>Total Labels</td>
              <td>Total registrations</td>
              <td>Match Status</td>
            </tr>
            <tr>
              <td>New ordinary total : </td>
              <td>
                <?php
                echo $new_registration_count_M;
                ?>
              </td>
              <td>
                <?php
                echo $new_o_total = ($approve_new_member + $pending_new_list_member + $approver_new_pending + $ap_rejected_count_o_mem + $rec_rejected_count_o_mem);
                echo ($new_o_total == $new_registration_count_M) ? '<span style="color:#02bf02;"> (Matched)</span>' : '<span style="color:red;"> (Not matched)</span>';
                ?>
              </td>
            </tr>
            <tr>
              <td>New non member total : </td>
              <td>
                <?php
                echo $new_registration_count_NM;
                ?>
              </td>
              <td>
                <?php
                echo $new_nm_total = ($approve_new_nonmember + $pending_new_list_nonmembers + $approver_new_pending_non + $ap_rejected_count_non_mem + $rec_rejected_count_non_mem);
                echo ($new_nm_total == $new_registration_count_NM) ? '<span style="color:#02bf02;"> (Matched)</span>' : '<span style="color:red;"> (Not matched)</span>';
                ?>
              </td>
            </tr>
            <tr>
              <td>Edit ordinary total : </td>
              <td>
                <?php
                echo $edit_registration_count_M;
                ?>
              </td>
              <td>
                <?php
                echo $edit_o_total = ($approve_edit_member + $pending_edit_member + $approver_edit_pending + $ap_rejected_count_edit_o_mem + $rec_rejected_count_edit_o_mem);
                echo ($edit_o_total == $edit_registration_count_M) ? '<span style="color:#02bf02;"> (Matched)</span>' : '<span style="color:red;"> (Not matched)</span>';
                ?>
              </td>
            </tr>
            <tr>
              <td>Edit non member total : </td>
              <td>
                <?php
                echo $edit_registration_count_NM;
                ?>
              </td>
              <td>
                <?php
                echo $edit_nm_total = ($approve_edit_nonmember + $pending_edit_nonmember + $approver_edit_pending_non + $ap_rejected_count_edit_non_mem + $rec_rejected_count_edit_non_mem);
                echo ($edit_nm_total == $edit_registration_count_NM) ? '<span style="color:#02bf02;"> (Matched)</span>' : '<span style="color:red;"> (Not matched)</span>';
                ?>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

<script src="<?php echo base_url() ?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/plugins/datepicker/datepicker3.css">
<script>
  $(document).ready(function() {
    $("#listitems_filter").hide();

    $('#from_date').datepicker({
      format: 'yyyy-mm-dd',
      endDate: '+0d',
      autoclose: true,
      changeMonth: true,
      changeYear: true
    }).on('changeDate', function() {
      $('#to_date').datepicker('setStartDate', new Date($(this).val()));
    });

    $('#to_date').datepicker({
      format: 'yyyy-mm-dd',
      endDate: '+0d',
      autoclose: true
    }).on('changeDate', function() {
      $('#from_date').datepicker('setEndDate', new Date($(this).val()));
    });
  });
</script>
<?php $this->load->view('admin/includes/footer'); ?>