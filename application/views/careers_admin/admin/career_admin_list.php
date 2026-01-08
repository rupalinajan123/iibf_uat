<?php $this->load->view('careers_admin/admin/includes/header'); ?>
<?php $this->load->view('careers_admin/admin/includes/sidebar'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Candidate List
    </h1>
    <?php echo $breadcrumb; ?>
  </section>
  <div class="col-md-12">
    <br />
    <?php
    if ($this->session->flashdata('success') != '') { ?>
      <div class="alert alert-success alert-dismissible" id="success_id">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $this->session->flashdata('success'); ?>
      </div>
    <?php } ?>
  </div>

  <?php
  $position = '';
  $from_date = '';
  $to_date = '';
  if (isset($_GET['position'])) {
    $position = $_GET['position'];
  }
  if (isset($_GET['from_date'])) {
    $from_date = $_GET['from_date'];
  }
  if (isset($_GET['to_date'])) {
    $to_date = $_GET['to_date'];
  }
  ?>


  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">

            <form method="get" action="<?php echo base_url(); ?>careers_admin/admin/Career_admin/career_admin_list">
              <div class="dropdown">
                <select name="position" id="position" class="">
                  <option value="">Select Position</option>
                  <?php

                  if (count($career_position) > 0) {
                    foreach ($career_position as $row) { ?>
                      <option <?php echo ($position == $row['id'] ? 'selected' : ''); ?> value="<?php echo $row['id']; ?>"><?php echo $row['position']; ?></option>
                  <?php }
                  }
                  ?>
                </select>
                <th>From: <input type="date" id="from" name="from_date" value="<?php echo $from_date; ?>"></th>
                <th>To:<input type="date" id="to" name="to_date" value="<?php echo $to_date; ?>"></th>
                <button type="submit" id="submit" name="submit" class="">Submit</button>
              </div>
            </form>
            <br />
            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
            <input type="hidden" name="base_url_val" id="base_url_val" value="" />
            <div class="table-responsive">
              <table id="listitems2" class="table table-bordered table-striped dataTables-example" width="400">
                <thead>
                  <tr>
                    <th id="srNo" style="text-align: center">Sr.No.</th>
                    <th id="id" style="text-align: center">Candidate Name</th>
                    <th id="member_no" style="text-align: center">Email</th>
                    <th id="module_mame" style="text-align: center">Contact number</th>
                    <th id="position" style="text-align: center">Career Position</th>
                    <th id="date" style="text-align: center">Application Date</th>
                    <th id="action" style="text-align: center">View</th>
                    <th id="transaction_no" style="text-align: center">Pdf</th>
                  </tr>
                </thead>

                <tbody class="no-bd-y" id="list2">
                  <?php
                  $k = 1;
                  if (count($reuest_list) > 0) {
                    foreach ($reuest_list as $res) {
                      if ($res['alternate_mobile'] != '') {
                        $mobile = $res['mobile'] . ' , ' . $res['alternate_mobile'];
                      } else {
                        $mobile = $res['mobile'];
                      }

                      echo '<tr><td>' . $k . ' </td>';
                      echo '<td>' . $res['firstname'] . " " . $res['middlename'] . " " . $res['lastname'] . ' </td>';
                      echo '<td>' . $res['email'] . ' </td>';
                      echo '<td>' . $mobile . ' </td>';
                      echo '<td>' . $res['position'] . ' </td>';
                      echo '<td>' . $res['submit_date'] . ' </td>';
                      //echo '<td>'.$res['education'].' </td>'; 

                      echo '<td><a class="btn btn-info btn-xs vbtn" href="' . base_url() . 'careers_admin/admin/Career_admin/request_detail/' . base64_encode($res['careers_id']) . '">View</a></td>';

                      /*echo '<td><a href="'.base_url().'careers_admin/admin/Career_admin/pdf/'.$res['careers_id']."/".$res['position_id'].$res['position']'" class="btn btn" id="pdf">
                      <span  class="glyphicon glyphicon-file"></span>Pdf</a><br><br></td>';
              				echo '</td></tr>';*/

                      echo '<td><a href="' . base_url() . 'careers_admin/admin/Careers_position/pdf_record/' . $res['careers_id'] . "/" . $res['position_id'] . '"class="btn btn" id= "pdf">
                      <span  class="glyphicon glyphicon-file"></span>Pdf</a><br><br></td>';
                      //                       echo '<td><a href="' . base_url() . 'careers_admin/admin/Careers_position/pdf_record/' . $res['careers_id'] . '/' . $res['position_id'] . '" 
                      //        class="btn btn-primary" 
                      //        id="pdf_' . $res['careers_id'] . '" 
                      //        target="_blank">
                      //         <span class="glyphicon glyphicon-file"></span> PDF
                      //     </a>
                      // </td>';

                      $k++;
                    }
                  }
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
<link href="<?php echo base_url(); ?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<style>
  .active_batch {
    color: #00a65a;
    font-weight: 600;
  }

  .deactive_batch {
    color: #930;
    font-weight: 600;
  }

  .input_search_data {
    width: 100%;
  }

  tfoot {
    display: table-header-group;
  }

  .vbtn {
    padding: 3px 4px;
    font-weight: 600;
  }
</style>

<!-- Data Tables -->
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

<script src="<?php echo base_url() ?>js/js-paginate.js"></script>
<script>
  $(function() {
    $('#listitems2').DataTable();
    $("#listitems_filter").show();
  });
</script>

<?php $this->load->view('careers_admin/admin/includes/footer'); ?>