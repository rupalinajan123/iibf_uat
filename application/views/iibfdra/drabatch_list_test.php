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
.statusa {
  color: green;
  font-weight: 800;
}
.statusc {
  color: #9aa544;
  font-weight: 800;
}
.statusr {
  color: #cc4122;
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
        
      <div class="row">
         
        <div class="col-xs-12">

        <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Batches   </h3>
                    <div class="pull-right">
                      <a href="<?php echo base_url();?>iibfdra/TrainingBatches/allcandidates" class="btn btn-primary">All Candidates</a>
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
                       <!-- <?php if(strtotime($comp_currdate) >= strtotime($comp_frmdate) && strtotime($comp_currdate) <= strtotime($comp_todate)){ ?>
                        <a href="#" class="isDisabled btn">Create New Batch</a>
                       <?php }else { ?>
                      <a style="display: " href="<?php //echo base_url();?>iibfdra/TrainingBatches/add_batches" class="btn btn-warning">Create New Batch</a>
                      <?php } ?> -->

                      <a style="display: " href="<?php echo base_url();?>iibfdra/TrainingBatches/add_batches" class="btn btn-warning">Create New Batch</a>

                      </div>
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

                               <!--   <th><input type="checkbox" id="selectall"/></th> -->
                                    <th id="srNo">S.No.</th>
                                    <th id="batchcode">Batch Code</th>
                                    <th id="batchname">Batch Name</th>
                                    <th id="atchtype">Combined/Separate Batch</th>
                                    <th id="centername">Center Name</th>
                                    <th id="from_to_date">Batch From & To Date</th>
                                    <th id="totalapplications">Total Applications</th>
                                    <th id="batchstatus">Batch Status</th>
                                    <th id="action">Operations</th> 
                                </tr>
                            </thead>
                             <tbody class="no-bd-y" id="list">
                         <?php if( count( $batchDetails )  > 0 ) { 
                          $i = 1;
                          foreach( $batchDetails as $batch ) 
													{ 
														//echo "<pre>"; print_r($batch); exit;
                          //$this->db->select('COUNT(regid) as no_of_count'); 
                          $members = $this->master_model->getRecords('dra_members',array('batch_id'=>$batch['id']));

                            ?>
                                  <tr> <!--  <td><input type="checkbox" id="selectall"/></td> -->
                                      <td><?php echo $i;?></td>
                                      <td><?php echo $batch['batch_code'];?></td>
                                      <td><?php echo $batch['batch_name']; if($batch['batch_online_offline_flag'] == 1) { echo " (Online)"; } ?></td>
                                      <td><?php if($batch['batch_type'] == 'C'){ echo '<span class="typec">Combined</span>' ;} else { echo '<span class="types">Separate('.$batch['hours'].')</span>' ; } ?></td>
                                      <td><?php
                                            if( $batch['city_name']== ""){
                                                  echo ucfirst($batch['location_name']);
                                              } 
                                              else{
                                              echo ucfirst($batch['city_name']);
                                            }?></td>
                                
                                      <td><?php echo date("d-M-Y", strtotime($batch['batch_from_date'])).' To <br>'.date("d-M-Y", strtotime($batch['batch_to_date']));?></td>
                                      <td><?php echo $batch['total_candidates'];?></td>
                                      <td><?php if($batch['batch_status'] == 'IR'){ echo 'In Review' ; } else if($batch['batch_status'] =='A'){ echo '<span class="statusa">Approved</span>'; } else if($batch['batch_status'] =='R'){ echo '<span class="statusr">Rejected</span>'; } else { echo '<span class="statusc">cancelled</span>';} ?></td>
                                      <td>
                                         <?php
                                         if ($batch['batch_status'] == 'IR' || $batch['batch_status'] == 'R') { ?>
                                          <a href="<?php echo base_url().'iibfdra/TrainingBatches/edit/'.base64_encode($batch['id'])?>">Edit | </a>
                                     <?php  } ?>
                                        <a href="<?php echo base_url().'iibfdra/TrainingBatches/view/'.base64_encode($batch['id']) ?>">View </a> 

                                       <?php  
                                        if($batch['batch_status'] == 'A' && $batch['batch_from_date'] <= date('Y-m-d') && $batch['batch_to_date'] >=  date('Y-m-d') && count($members) < $batch['total_candidates'])
																				{ ?>
																					<a href="<?php echo base_url().'iibfdra/TrainingBatches/addApplication/'.base64_encode($batch['id']) ?>">| Add New Applications </a>

																		<?php }
                                        elseif($batch['batch_status'] == 'A' && $batch['batch_active_period'] != '' && $batch['batch_active_period'] >= date('Y-m-d') && count($members) < $batch['total_candidates'])
																				{ ?>

                                        <a href="<?php echo base_url().'iibfdra/TrainingBatches/addApplication/'.base64_encode($batch['id']) ?>">| Add New Applications </a>
                                      <?php  }
																			else
																			{?>
                                         <a href="#" class="isDisabled ">| Add New Applications </a>
																	 <?php }   ?>
                                      
                                      </td>
                                  </tr>
                         <?php $i++; }
                         } ?>                
                                </tbody>
                        </table>
                        <div style="width:30%; float:left;">
                <?php /*Removed pagination on 21-01-2017*/ 
              //echo $info; ?>
                        </div>
                        <div id="links" class="" style="float:right;"><!-- <?php //echo $links; ?> --></div>
                  </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
          </div>
          <!-- /.col -->
        </div>
  </section>
  
</div>
<script type="text/javascript">
$(function () {
  /*$("#listitems").DataTable();
  var base_url = '<?php //echo base_url(); ?>';
  paginate(base_url+'iibfdra/DraExam/getApplicantList','','','');
  $("#base_url_val").val(base_url+'iibfdra/DraExam/getApplicantList');*/
  // add multiple select / deselect functionality
  $("#selectall").click(function () {
      $('.chkmakepay').prop('checked', this.checked);
  });

  // if all checkbox are selected, check the selectall checkbox
  // and viceversa
  $(".chkmakepay").click(function(){
    if($(".chkmakepay").length == $(".chkmakepay:checked").length) {
      $("#selectall").prop("checked", true);
    } else {
      $("#selectall").removeAttr("checked");
    }

  });
  $( ".draexampay" ).submit(function() {
    if( $(".chkmakepay:checked").length == 0 ) {
      alert('Please select at least one candidate to pay');
      return false; 
    } else {
      return true;  
    }
  });
});
</script>
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
    $('#example1').DataTable({
    responsive: true
  });
     $("body").on("contextmenu",function(e){
        return false;
    });
 });
</script>