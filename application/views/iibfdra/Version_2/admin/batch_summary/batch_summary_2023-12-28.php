<?php $this->load->view('iibfdra/Version_2/admin/includes/header'); ?>
<?php $this->load->view('iibfdra/Version_2/admin/includes/sidebar'); ?>

<script src="<?php echo base_url() ?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/plugins/datepicker/datepicker3.css">

<style>
  .search_form_common_all {
    background: #ededed;
    padding: 20px 20px 10px 20px;
    margin-bottom: 20px;
    text-align: left;
  }

  .search_form_common_all .form-group {
    display: inline-block;
    margin: 0 10px 10px;
    width: 300px;
    vertical-align: top;
  }

  .search_form_common_all .form-group .form-label {
    display: block;
    font-size: 14px;
    margin: 0 0 3px 0;
    line-height: 22px;
    text-align: left;
  }

  .search_form_common_all .form-group .form-control {
    padding: 5px 20px 5px 10px;
    height: 35px !important;
  }

  .search_form_common_all .btn {
    display: inline-block;
    vertical-align: top;
    padding: 7px 20px 6px;
    margin: 0 0px 0 0;
    min-width: 97px;
  }

  #listitems23_processing {
    display: none !important;
  }

  #page_loader {
    background: rgba(0, 0, 0, 0.35) none repeat scroll 0 0;
    height: 100%;
    left: 0;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 99999;
    display: none;
  }

  /* #page_loader .loading { margin: 0 auto; position: relative;border: 16px solid #f3f3f3;border-radius: 50%;border-top: 16px solid #064b86;border-bottom: 16px solid #064b86;width: 80px;height: 80px;-webkit-animation: spin 2s linear infinite;animation: spin 2s linear infinite;top: calc( 50% - 40px);} */
  #page_loader .loading {
    margin: 0 auto;
    position: relative;
    width: 80px;
    height: 80px;
    top: calc(50% - 40px);
    color: #fff;
    font-size: 30px;
  }

  @-webkit-keyframes spin {
    0% {
      -webkit-transform: rotate(0deg);
    }

    100% {
      -webkit-transform: rotate(360deg);
    }
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }

  .datepicker table tr td.disabled,
  .datepicker table tr td.disabled:hover,
  .datepicker table tr td span.disabled,
  .datepicker table tr td span.disabled:hover {
    cursor: not-allowed;
    background: #eee;
    border: 1px solid #fff;
  }

  .types {
    color: #223fcc;
    font-weight: 800;
  }

  .typec {
    color: #73c5ce;
    font-weight: 800;
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
    color: #7b3ede;
    font-weight: 800;
  }
</style>

<div id="page_loader">
  <div class="loading">Processing...</div>
</div>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Batch Summary
      <?php if ($batch_id != "") { ?>
        <a href="<?php echo site_url('iibfdra/Version_2/admin/BatchSummary'); ?>" class="btn btn-warning pull-right">Back</a>
      <?php } ?>
      <?php //echo $breadcrumb; 
      ?>
    </h1>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            <form class="form-control border-0" action="<?php echo base_url('iibfdra/Version_2/admin/BatchSummary/get_batch_mis'); ?>" method="post">
              <div class="row">
                <div class="col-md-2 col-sm-4">
                  <div class="form-group">
                    <label class="form-label">From Date</label>
                    <input type="text" class="form-control custom_filter datepicker search_opt" name="from_date" id="from_date" value="" autocomplete="off" readonly>
                  </div>
                </div>

                <div class="col-md-2 col-sm-4">
                  <div class="form-group">
                    <label class="form-label">To Date</label>
                    <input type="text" class="form-control custom_filter datepicker search_opt" name="to_date" id="to_date" value="" autocomplete="off" readonly>
                  </div>
                </div>

                <div class="col-md-3 col-sm-6">
                  <div class="form-group">
                    <label class="form-label" >Institution</label>
                    <select class="form-control search_opt" name="agencyvalue" id="agencyvalue">
                      <option value="">Select Institute</option>
                      <?php foreach ($agency as $key => $agencyvalue) { ?>
                        <?php
            // echo '<pre>';
            // print_r($agency); // or var_dump($agency);
            // echo '</pre>'; ?>
                      <option value="<?php echo $agencyvalue['id']; ?>"><?php echo $agencyvalue['institute_name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="col-md-3 col-sm-6">
                  <div class="form-group">
                    <label class="form-label">Inspector</label>
                    <select class="form-control search_opt" name="inspectorvalue" id="inspectorvalue">
                      <option value="">Select Inspector</option>
                      <?php foreach ($inspectors as $key => $inspectorvalue) { ?>
                      <option value="<?php echo $inspectorvalue['id']; ?>"><?php echo $inspectorvalue['inspector_name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="col-md-2 col-sm-4">
                  <div class="form-group">
                    <label class="form-label">Batch Status</label>
                    <select class="form-control search_opt" id="status">
                      <option value="">Select Status</option>
                      <option value="In">In</option>
                      <option value="Review">Review</option>
                      <option value="Final">Final</option> 
                      <option value="Review">Review</option>
                      <option value="Batch">Batch</option> 
                      <option value="Error">Error</option>
                      <option value="Approved">Approved</option>
                      <option value="Hold">Hold</option>
                      <option value="UnHold">UnHold</option>
                      <option value="Rejected">Rejected</option>
                      <option value="Re-Submitted">Re-Submitted</option>
                      <option value="Cancelled">Cancelled</option>
                    </select>
                  </div>
                </div>

              </div>
              
              <div class="row" style="margin-bottom:8px;">
                <div class="col-md-12">
                  <a href="javascript:void(0)" class="btn btn-sm btn-success" style="min-width:100px;" onclick="apply_filter()">Apply Filter</a>&nbsp;&nbsp;
                  <button type="submit" class="btn btn-sm btn-info" id="export_data" name="export_table">Export To Excel</button>&nbsp;&nbsp;
                  <a href="javascript:void(0)" class="btn btn-sm btn-danger" style="min-width:100px;" onclick="clear_date_filter()">Clear</a>
                </div>
              </div>
            </form>

            <div class="table-responsive">
              <table id="listitems23" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="text-center no-sort" id="sr" style="padding-right:8px;">S.No.</th>
                    <th class="text-center">Batch Code</th>
                    <th class="text-center">Agency Code</th>
                    <th class="text-center">Agency Name</th>
                    <th class="text-center">Batch Submit Date and Time</th>
                    <th class="text-center">Batch Approve Date and Time</th>
                    <th class="text-center">Batch From Date</th>
                    <th class="text-center">Batch To Date</th>
                    <th class="text-center">Batch Co-ordinator</th>
                    <th class="text-center">Faculties</th>
                    <th class="text-center">Total Number of Inspections</th>
                    <th class="text-center">Dates of inspections</th>
                    <th class="text-center">Total Inspection Time(Minutes)</th>
                    <th class="text-center">Average Inspection Time(Minutes)</th>
                    <th class="text-center">Inspected By</th>
                    <th class="text-center">Total Candidates</th>
                    <th class="text-center">Total Registered Candidates</th>
                    <th class="text-center">Total Hold Candidates</th>
                    <th class="text-center">total Eligible Candidates </th>
                    <th class="text-center">Batch Status</th>
                    <th class="text-center">Assessment/Rating(Poor)</th>
                    <th class="text-center">Assessment/Rating(Average)</th>
                    <th class="text-center">Assessment/Rating(Good)</th>
                    <th class="text-center">Assessment/Rating(Excellent)</th>
                  </tr>
                </thead>
                <tbody></tbody>

              </table>

              <!-- <div id="links" class="dataTables_paginate paging_simple_numbers"> </div> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Data Tables -->
<link href="<?php echo base_url(); ?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<style>
  .input_search_data {
    width: 100%;
  }

  tfoot {
    display: table-header-group;
  }

  .pp0,
  .pp5,
  .pp6,
  .pp7,
  .pp8,
  .pp9 {
    display: none;
  }

  .vbtn {
    padding: 3px 21px;
    font-weight: 900;
  }

  .#listitems2 {
    width: 100%;
    max-width: 100%;
  }

  .moption {
    width: 100%;
  }

  .dataTables_wrapper {
    max-width: 96%;
    margin: 20px auto;
  }
</style>
</style>

<!-- Data Tables -->
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

<!-- <script src="<?php //echo base_url()
                  ?>js/js-paginate.js"></script> -->

<script type="text/javascript">
  $(document).ready(function() {
    var dataTable = $('#listitems23').DataTable({
      'processing': true,
      'serverSide': true,

      order: [
        [1, 'desc']
      ],

      "ajax": {
        "url": '<?php echo site_url("iibfdra/Version_2/admin/BatchSummary/get_batch_mis"); ?>',
        "type": "POST",
        /*"data": function(data) {
          var from_date = $('#from_date').val();
          var to_date = $('#to_date').val();

          data.from_date = from_date;
          data.to_date = to_date; 
        }*/
        "data": function ( d ) 
            {
              d.from_date = $("#from_date").val(); 
              d.to_date = $("#to_date").val();
              d.agencyvalue = $("#agencyvalue").val();
              d.inspectorvalue = $("#inspectorvalue").val();
              d.status = $("#status").val();
            },
      },
      responsive: false,
      'columns': [{
          data: 'sr' , name: 'sr'
        },
        {
          data: 'batch_code', name: 'batch_code'
        },
        {
          data: 'institute_code' , name: 'institute_code'
        },
        {
          data: 'institute_name' , name: 'institute_name'
        },
        {
          data: 'submit_date' , name: 'submit_date'
        },
        {
          data: 'approve_date' , name: 'approve_date'
        },
        {
          data: 'batch_from_date' , name: 'batch_from_date'
        },
        {
          data: 'batch_to_date' , name: 'batch_to_date'
        },

        {
          data: 'contact_person_name' , name: 'contact_person_name'
        },
        {
          data: 'faculty_names' , name: 'faculty_names'
        },
        {
          data: 'inspection_count' , name: 'inspection_count'
        },
        {
          data: 'inspection_dates' , name: 'inspection_dates'
        },
        {
          data: 'total_inspection_time_minutes' , name: 'total_inspection_time_minutes'
        },
        {
          data: 'average_inspection_time' , name: 'average_inspection_time'
        },
        {
          data: 'inspected_by' , name: 'inspected_by'
        },
        {
          data: 'total_candidates' , name: 'total_candidates'
        },
        {
          data: 'total_registered_candidate' , name: 'total_registered_candidate'
        },
        {
          data: 'total_hold_candidate' , name: 'total_hold_candidate'
        },
        {
          data: 'total_eligible_candidate' , name: 'total_eligible_candidate'
        },
        {
          data: 'batch_status' , name: 'batch_status'
        },
        {
          data: 'total_poor_counts' , name: 'total_poor_counts'
        },
        {
          data: 'total_average_counts' , name: 'total_average_counts'
        },
        {
          data: 'total_good_counts' , name: 'total_good_counts'
        },
        {
          data: 'total_excellent_counts' , name: 'total_excellent_counts'
        }
      ],

      "columnDefs": [{
        "targets": 1,
        "visible": false,
      }]
    });

    //$('.custom_filter').change(function(){  $('#listitems22').DataTable().ajax.reload(); });

    $('#from_date').datepicker({
      format: "yyyy-mm-dd",
      autoclose: true,
      keyboardNavigation: true,
      forceParse: false,
      //clearBtn: true       
    }).on('changeDate', function(selected) {
      var minDate = new Date(selected.date.valueOf());
      $('#to_date').datepicker('setStartDate', minDate);
    });

    $('#to_date').datepicker({
      format: "yyyy-mm-dd",
      autoclose: true,
      keyboardNavigation: true,
      forceParse: false,
      //clearBtn: true       
    }).on('changeDate', function(selected) {
      var maxDate = new Date(selected.date.valueOf());
      $('#from_date').datepicker('setEndDate', maxDate);
    });

  });

  /*function clear_date_filter() {
    if ($('#from_date').val() != "" || $('#to_date').val() != "") {
      $('.datepicker').datepicker('update', '');
      $('#from_date').datepicker('setEndDate', '');
      $('#to_date').datepicker('setStartDate', '');
      $('#listitems23').DataTable().ajax.reload();
    }
  }*/

  /*function apply_filter() {

  //console.log('agencyvalue:', $('#agencyvalue').val());
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var agencyvalue = $('#agencyvalue').val();
    var inspectorvalue = $('#inspectorvalue').val();
    var status = $('#status').val();
    var filters = {
        from_date: from_date,
        to_date: to_date,
        agencyvalue: agencyvalue,
        inspectorvalue: inspectorvalue,
        status: status
    };

   // if (($('#from_date').val() != "" || $('#to_date').val() != "" )) {
    if (from_date || to_date || agencyvalue || inspectorvalue || status) {
      // debugger;
      //$('#listitems23').DataTable().ajax.reload();
      $('#listitems23').DataTable().ajax.reload({
            data: filters
        });
    }
  }*/

  function apply_filter() { $('#listitems23').DataTable().draw(); }
  function clear_date_filter() { $(".search_opt").val(''); $('#listitems23').DataTable().draw(); }

  $(document).ajaxStart(function() {
    $("#page_loader").css("display", "block");
  });
  $(document).ajaxComplete(function() {
    $("#page_loader").css("display", "none");
  });
</script>
<?php $this->load->view('iibfdra/Version_2/admin/includes/footer'); ?>