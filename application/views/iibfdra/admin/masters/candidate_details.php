<?php $this->load->view('iibfdra/admin/includes/header');?>
<?php $this->load->view('iibfdra/admin/includes/sidebar');?>

<?php 
  $drainspdata = $this->session->userdata('dra_inspector'); 
  $inspector_name = $drainspdata['inspector_name'];
  $inspector_id = $drainspdata['id'];
?>
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header" style="text-align: center;">
    <h1> Candidate Details </h1>
  </section>

  <div class="col-md-12"> <br />
  </div>
  <!-- Main content -->
  <section class="content">
  
    <div class="row" id="candidate_details_div" >
      <div class="col-xs-12">
        <div class="box-header">
          <h3 class="box-title">Details of <?php echo $candidate_name. ' ('.$training_id.')'; ?></h3>
          <div class="pull-right"> 
            <?php /* <a href="<?php echo base_url();?>iibfdra/admin/InspectionSummary" class="btn btn-warning">Back</a> */ ?></div>
        </div>
        <div class="box">
          <div class="box-body">
           
            <input type="hidden" name="base_url_val" id="base_url_val" value="" />
            <!-- <input type="hidden" name="question_checked_array" id="question_checked_array" value="" /> -->

            <div class="table-responsive">
              <table id="listitems" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="3">S.No.</th> 
                    <th width="7">Inspection No</th>
                    <th width="10">Inspector Name</th>
                    <th width="5">Attendance</th>
                    <th width="10">Date/ Time</th>
                    <th width="15">Remark</th>
                  </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                  <?php foreach ($candidate_data as $key => $value) { 
                      $j = $key+1;
                    ?>
                    <tr>
                      <td><?php echo $j; ?></td>
                      <td><?php echo $value['inspection_no']; ?></td>
                      <td><?php echo $value['inspector_name']; ?></td>
                      <td><?php echo $value['attendance']; ?></td>
                      <td><?php echo $value['created_on']; ?></td>
                      <td><?php echo $value['remark']; ?></td>
                    </tr>  
                  <?php } ?>
                 
                </tbody>
              </table>
              
            </div>
          </div>
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
      </div>
      <!-- /.col collapsed-box-->

      <div class="col-sm-4 col-xs-offset-3">
        <?php if($hold_release == 'Release') {?>
          <button type="button" class="btn btn-info" onclick="change_status('Hold','<?php echo $regid; ?>')">Hold</button>
        <?php } ?>
        <?php if($hold_release == 'Hold') {?>
          <button type="button" class="btn btn-info" onclick="change_status('Release','<?php echo $regid; ?>')">Release</button>
        <?php } ?>
      </div>

      <div class="col-sm-6 col-sm-offset-3">
        <div class="col-sm-12">
          <center>
            <button type="button" class="btn btn-danger" onclick="self.close()">Close</button>
          </center>
        </div>
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
.rejection{
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
.rejection{
 width:85%;
 margin:4px;
 clear:both;	
}
</style>
<script src="<?php echo base_url()?>js/js-paginate.js"></script> 
<script>
var site_url = '<?php echo base_url(); ?>';
$(function () {

  var selectedData = [];

	var dateToday = new Date();	
	//var validity_to_ck =  $('#batch_to_date_val').val(); //2019-02-26
	var validity_to_ck = $('#batch_to_date_val').val();

  var table  = $('#listitems').DataTable({

      "dom": 'Blftirp',
      "stateSave": true,

      "paging":   false,
      
      "select": {
        "style":    'multi',
        "selector": 'td:nth-child(2)'
        
          },
  
      "responsive":{
          "details" : {
                  "type" : 'column'
                }
         },
    });

	
});

function change_status(status,regid){
  console.log('status:'+status+'regid:'+regid);
  $.ajax({
    url: site_url+"iibfdra/admin/InspectionSummary/change_status",
    type: 'POST',
    data: {'status':status,'regid':regid},
    success: function(response){
     console.log(response);
     if(response == 1){
      var msg = "Status Changed Successfully...";
      swal(msg, "", "success");
      location.reload();
     }
    }
  })
      
}
</script>
    <script>
    if ( window.history.replaceState ) {
      window.history.replaceState( null, null, window.location.href );
    }
</script>
<?php $this->load->view('iibfdra/admin/includes/footer');?>