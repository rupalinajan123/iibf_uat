<style type="text/css">
  .isDisabled {
  pointer-events: none;
  color: #0975b36b;
 /* display: none*/
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
  color: #3474eb;
  font-weight: 800;
}

.statusa {
  color: #008000;
  font-weight: 800;
}
.statusc {
  color: #d15656;
  font-weight: 800;
}
.statusr {
  color: #ad0505;
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

</style>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Training Batch List</h1>
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
                  <a href="<?php echo base_url();?>iibfdra/TrainingBatches/allcandidates" class="btn btn-primary">All Candidates</a>
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
                  <?php if($menu == 'training_batches' ) { //&& $batchcnt >= $agency_count ?>
                  <a style="display: " href="<?php echo base_url();?>iibfdra/TrainingBatches/add_batches" class="btn btn-warning">Create New Batch</a>
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

                        <table id="example1" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>Sr.No.</th>
                              <th>Batch Id</th>
                              <th>Batch Code</th>
                              <!-- <th>Batch Name</th> -->
                              <th>Hours</th>
                              <th>Center Name</th>
                              <th>Batch From & To Date</th>
                              <th>Total Applications</th>
                              <th>Batch Creation Date</th>
                              <th>Batch Status</th>
                              <th>Action</th> 
                            </tr>
                        </thead>
                        <tbody class="no-bd-y" id="list">
                        </tbody>
                      </table>
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
      var url =  site_url+"iibfdra/TrainingBatches/dra_batch_list/"+menu;
    }
    else{
      var url =  site_url+"iibfdra/inspectorHome/batch_list";
    }

    var table = $('#example1').DataTable(
    {
      order: [[1, 'desc']],
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',

      'ajax': {
         "url": url,
         'data': function(data){
           
         }
      },
      
      'columns': [
         { data: 'sr' }, 
         { data: 'batch_id' },
         { data: 'batch_code' },
         //{ data: 'batch_name' }, 
         { data: 'hours' }, 
         { data: 'city_name' },
         { data: 'from_to_date' }, 
         { data: 'total_applications' },
         { data: 'created_on' }, 
         { data: 'status' }, 
         { data: 'action' },
      ],
      "columnDefs": [ {
          "targets": 0,
          "orderable": false
        },
        {
          "targets":1,
          "visible":false
        },
        {
          "targets":9,
          "orderable":false
        }
      ]
    }
    );

    $("body").on("contextmenu",function(e){
      return false;
    });
  });
</script>