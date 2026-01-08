<style type="text/css">
  .isDisabled {
  pointer-events: none;
  color: #0975b36b;
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
  color: #FF8C00;
  font-weight: 800;
}
.statusrs {
  color: #7b3ede;
  font-weight: 800;
}

span.notifn_cnt {
    position: absolute;
    top: -9px;
    right: -9px;
    background: red;
    height: 15px;
    width: 15px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;color: #fff;
}

.bell_shake {
    position: absolute;padding-left: 3px;
}

</style>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
<section class="content-header">
  <?php if($menu == 'training_batches') { ?>
    <h1>Training Batch List</h1>
  <?php }else{?>
    <h1>Batch Checklist</h1>
  <?php }?>

</section>


  <!-- Main content -->
   
    <section class="content">
      <?php /*if($dashboard != 'inspector') {?>
      <div class="alert alert-warning alert-dismissible">
        Batch Limit is <?php echo $batchcnt; ?>. Now You can add <?php echo $batchcnt - $agency_count; ?> Batches.
      </div>
      <?php }*/ ?>
        
      <div class="row">
         
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
                <h3 class="box-title">Batches   </h3>
                <?php if($dashboard != 'inspector'){ ?>
                <div class="pull-right">
                  <?php if($menu == 'training_batches') { ?>
                  <a href="<?php echo base_url();?>iibfdra/Version_2/TrainingBatches/allcandidates" class="btn btn-primary">All Candidates</a>
                  <?php }?>
                  <?php
                     $comp_currdate = '';
                     $comp_frmdate = '';
                     $comp_todate = '';
                     $comp_currdate = date('Y-m-d');
                     if( count( $active_exams )  > 0 ) { 
                          //$comp_currdate = date('Y-m-d H:i:s');
                          $comp_currdate = date('Y-m-d');
                          foreach( $active_exams as $exam ) { 
                          //$comp_frmdate = $exam['exam_from_date'].' '.$exam['exam_from_time'];
                          //$comp_todate = $exam['exam_to_date'].' '.$exam['exam_to_time'];
                          $comp_frmdate = $exam['exam_from_date'];
                          $comp_todate = $exam['exam_to_date'];
                        }
                      }
                   ?>
                  <?php if($menu == 'training_batches') { ?>
                  <a style="display: " href="<?php echo base_url();?>iibfdra/Version_2/TrainingBatches/add_batches" class="btn btn-warning">Create New Batch</a>
                  <?php }?>
                  </div>
                <?php }?>
            </div>
                <!-- /.box-header -->
                <div class="box-body">
            <?php if($this->session->flashdata('error')!=''){?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php } if($this->session->flashdata('success')!=''){ ?>
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php } 

                        ?> 
                        <div class="table-responsive">
                          <table id="example1" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th>Sr.No.</th>
                                <th>Batch Code</th>
                                <?php if($dashboard != 'institute') { ?>
                                  <th>Reported</th>
                                <?php } ?>
                                <th>Hours</th>
                                <th>Center Name</th>
                                <th>Training Medium</th>
                                <th>Batch From & To Date</th>
                                <th width="20px">Holidays</th>
                                <th>Training Timings</th>
                                <!-- <th>Maximum Capacity</th> -->
                                <!-- <th>No.of Registered Candidates</th> -->
                                <th>Batch Creation Date</th>
                                <th>Batch Status</th>
                                <th>Action</th> 
                                <th>Batch Id</th>
                              </tr>
                            </thead>
                            <tbody class="no-bd-y" id="list"></tbody>
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
<!-- DataTables -->
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<!-- Data Tables -->
<script type="text/javascript">
  $(document).ready(function() {
    var dashboard = '<?php echo $dashboard; ?>';
    

    if(dashboard == 'institute'){
      var menu = '<?php echo $menu; ?>';

      var url =  site_url+"iibfdra/Version_2/TrainingBatches/dra_batch_list/"+menu;    

      var orderArray = [[11, 'desc']];  

      var columnsArray = [
         { data: 'sr' }, 
         { data: 'batch_code' },
         { data: 'hours' }, 
         { data: 'city_name' },
         { data: 'training_medium' },
         { data: 'from_to_date' }, 
         { data: 'holiday' }, 
         { data: 'training_timings' },
         // { data: 'maximum_capacity' },
         // { data: 'no_of_registered_candidates' },
         { data: 'created_on' }, 
         { data: 'status' }, 
         { data: 'action' },
         { data: 'id' },
      ]; 

      var columnDefArray = [ {
                              "targets": 0,
                              "orderable": false
                            },
                            {
                              "targets":7,
                              "orderable":false
                            },
                            {
                              "targets":8,
                              "orderable":false
                            },
                            {
                              "targets":11,
                              "visible":false
                            }
                          ];   
    }
    else{
      var url =  site_url+"iibfdra/Version_2/inspectorHome/batch_list";

      var orderArray = [[12, 'desc']];

      var columnsArray = [
         { data: 'sr' }, 
         { data: 'batch_code' },
         { data: 'reported' }, 
         { data: 'hours' }, 
         { data: 'city_name' },
         { data: 'training_medium' },
         { data: 'from_to_date' }, 
         { data: 'holiday' }, 
         { data: 'training_timings' },
         // { data: 'maximum_capacity' },
         // { data: 'no_of_registered_candidates' },
         { data: 'created_on' }, 
         { data: 'status' }, 
         { data: 'action' },
         { data: 'id' },
      ]; 

      var columnDefArray = [ {
                              "targets": 0,
                              "orderable": false
                            },
                            {
                              "targets":8,
                              "orderable":false
                            },
                            {
                              "targets":9,
                              "orderable":false
                            },
                            {
                              "targets":12,
                              "visible":false
                            }
                          ]; 
    }

    var table = $('#example1').DataTable(
    {
      order: orderArray,
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',

      'ajax': {
         "url": url,
         'data': function(data) {
         }
      },
      
      'columns': columnsArray,
      "columnDefs": columnDefArray
    }
    );

    $("body").on("contextmenu",function(e){
      return false;
    });
  });
</script>