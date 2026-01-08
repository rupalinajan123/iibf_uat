
<style>
.modal-dialog {
  width: 468px;
  margin: 30px auto;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Candidate List
      <div class="pull-right">
        <?php
        $current_date = date('Y-m-d');
        //echo $current_date .'>='. $created_on .'&&'. $current_date .'<='. $training_from_date;
        if ($agency[0]['batch_status'] == 'Approved' && $current_date >= $created_on && $current_date <= $training_from_date && $members_cnt < $agency[0]['total_candidates'])
        {  ?>
          <a href="<?php echo base_url('iibfdra/Version_2/TrainingBatches/addApplication/' . $batch_id . '/0/1'); ?>" class="btn btn-info">Add Candidate</a>
        <?php } ?>
        <a href="<?php echo base_url('iibfdra/Version_2/TrainingBatches'); ?>" class="btn btn-warning">Back</a>
      </div>
    </h1>
    <?php //echo $breadcrumb; 
    ?>
  </section>
  <div class="col-md-12">
    <br />
    <?php
    if ($this->session->flashdata('success') != '')
    { ?>
      <div class="alert alert-success alert-dismissible" id="success_id">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $this->session->flashdata('success'); ?>
      </div>
    <?php }
    else if ($_SESSION['custom_success'] != '')
    { ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $_SESSION['custom_success']; ?>
      </div>
    <?php }

    if ($this->session->flashdata('error') != '')
    { ?>
      <div class="alert alert-danger alert-dismissible" id="error_id">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $this->session->flashdata('error'); ?>
      </div>
    <?php } ?>
  </div>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">

            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
            <input type="hidden" name="base_url_val" id="base_url_val" value="" />
            <div class="table-responsive">
              <table id="listitems" class="table table-bordered table-striped dataTables-example">
                <thead>
                  <tr>
                    <th class="no-sort">Sr.</th>
                    <th>Reg No.</th>
                    <th>Registration No.</th>
                    <th>Training Id.</th>
                    <th>Name</th>
                    <th>DOB</th>
                    <th>Mobile No.</th>
                    <th>Email</th>
                    <th>Candidate Status</th>
                    <th class="no-sort">Action</th>
                  </tr>
                </thead>

                <tbody class="no-bd-y" id="list2">

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

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="modal_hide()"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Candidate Hold</h4>
      </div>
      <form method="POST" action="<?php echo base_url("iibfdra/Version_2/TrainingBatches/holdApplicant"); ?>">  
        <div class="modal-body">
          <input type="hidden" name="batch_id" id="batch_id" value="<?php echo $batchid; ?> ">
          <input type="hidden" name="candidate_id" id="candidate_id" value="">

          <textarea class="form-control" maxlength="500" name="reason" id="reason" rows="4" required></textarea>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" onclick="modal_hide()">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>  
    </div>
  </div>
</div>



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


<script type="text/javascript">
  var table;
  var site_url = '<?php echo base_url(); ?>';
  var batch_id = '<?php echo $batch_id; ?>';


  function modal_show(candidateId) { 

    $('#candidate_id').val(candidateId);  
    $('#myModal').css('display','block');
    $('.modal').removeClass('fade');
  }

  function modal_hide() {
    $('#candidate_id').val('');  
    $('#myModal').css('display','none');
    $('.modal').addClass('fade');
  }

  $(function() {
    console.log('batch_id' + batch_id);
    var table = $('#listitems').DataTable({
      order: [
        [1, 'desc']
      ],
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',

      'ajax': {
        "url": site_url + "iibfdra/Version_2/TrainingBatches/candidate_list_ajax/" + batch_id,
        'data': function(data) {
          console.log('data--' + data);
        }
      },
      'columns': [{
          data: 'sr'
        },
        {
          data: 'regid'
        },
        {
          data: 'regnumber'
        },
        {
          data: 'training_id'
        },
        {
          data: 'candidate_name'
        },
        {
          data: 'dob'
        },
        {
          data: 'mobile_no'
        },
        {
          data: 'email_id'
        },
        {
          data: 'hold_release'
        },
        {
          data: 'action'
        },
      ],
      "columnDefs": 
					[
						{"targets": 'no-sort', "orderable": false, }
					],
    });


  });

  

  $.ajax({
    type: "POST",
    url: "<?php echo site_url('iibfdra/Version_2/TrainingBatches/remove_custom_session'); ?>",
    cache: false,
    dataType: 'JSON'
  });
</script>