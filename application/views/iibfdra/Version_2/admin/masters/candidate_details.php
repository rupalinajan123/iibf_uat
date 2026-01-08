<?php $this->load->view('iibfdra/Version_2/admin/includes/header');?>
<?php $this->load->view('iibfdra/Version_2/admin/includes/sidebar');?>

<?php 
  $drainspdata = $this->session->userdata('dra_inspector'); 
  $inspector_name = $drainspdata['inspector_name'];
  $inspector_id = $drainspdata['id'];
?>

<style type="text/css">
  .modal-dialog {
  width: 468px;
  margin: 30px auto;
}
</style>

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

      <div class="col-xs-12">
        <div class="box-header">
          <h3 class="box-title">Details of <?php echo $candidate_name. ' ('.$training_id.')'; ?></h3>
          <div class="pull-right"> 
            <?php /* <a href="<?php echo base_url();?>iibfdra/Version_2/admin/InspectionSummary" class="btn btn-warning">Back</a> */ ?></div>
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
                    <th width="3">Inspection No</th>
                    <th width="10">Inspector Name</th>
                    <th width="5">Attendance</th>
                    <th width="15">Attendance Remark</th>
                    <th width="5">Qualification Certification Verify</th>
                    <th width="10">Qualification Certification Remark</th>
                    <th width="5">Candidate Photo Verify</th>
                    <th width="10">Candidate Photo Remark</th>
                    <th width="10">Date/ Time</th>
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
                      <td><?php echo $value['remark']; ?></td>
                      <td><?php echo $value['qualification_verify']; ?></td>
                      <td><?php echo $value['qualification_remark']; ?></td>
                      <td><?php echo $value['photo_verify']; ?></td>
                      <td><?php echo $value['photo_remark']; ?></td>
                      <td><?php echo $value['created_on']; ?></td>
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
          <button type="button" class="btn btn-info" onclick="change_status('Manual Hold')">Hold</button>
        <?php } ?>

        <?php if($hold_release == 'Auto Hold' || $hold_release == 'Manual Hold') {?>
          <button type="button" class="btn btn-info" onclick="change_status('Release')">Release</button>
        <?php } ?>
        
        <button type="button" class="btn btn-danger" onclick="self.close()">Close</button>
      </div> 
      
    </div>
 
  </section>


  <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="modal_hide()"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Candidate Status</h4>
      </div>
      <form method="POST" action="<?php echo base_url("iibfdra/Version_2/admin/InspectionSummary/change_status"); ?>">  
        <div class="modal-body">
          <input type="hidden" name="status" id="status" value="">
          <input type="hidden" name="regid" id="regid" value="<?php echo $regid; ?>">

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

function change_status(status){
  
  $('#status').val(status);  
  $('#myModal').css('display','block');
  $('.modal').removeClass('fade');

  // $.ajax({
  //   url: site_url+"iibfdra/Version_2/admin/InspectionSummary/change_status",
  //   type: 'POST',
  //   data: {'status':status,'regid':regid},
  //   success: function(response){
  //    console.log(response);
  //    if(response == 1){
  //     var msg = "Status Changed Successfully.";
  //     swal(msg, "", "success");
  //     location.reload();
  //    }
  //   }
  // })      
}

function modal_hide() {
    $('#status').val('');  
    $('#myModal').css('display','none');
    $('.modal').addClass('fade');
  }
</script>
    <script>
    if ( window.history.replaceState ) {
      window.history.replaceState( null, null, window.location.href );
    }
</script>
<?php $this->load->view('iibfdra/Version_2/admin/includes/footer');?>