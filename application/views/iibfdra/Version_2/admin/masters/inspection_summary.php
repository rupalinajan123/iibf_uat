<?php $this->load->view('iibfdra/Version_2/admin/includes/header');?>
<?php $this->load->view('iibfdra/Version_2/admin/includes/sidebar');?>

<?php 
  $drainspdata = $this->session->userdata('dra_inspector'); 
  $inspector_name = $drainspdata['inspector_name'];
  $inspector_id = $drainspdata['id'];
?>
<style type="text/css">
  #loading { display: none;	position: fixed; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; }
  #loading > p { margin: 0 auto; width: 100%; height: 100%; position: absolute; top: 20%; }
  #loading > p > img { max-height: 250px; margin:0 auto; display: block; }

  .select2-selection.select2-selection--single { border-radius: 0 !important; padding: 5px 0 2px 0px; height: auto !important; max-width: none; }
  .hold_release_btns { text-align: center; }
  .hold_release_btns .btn { padding: 1px 6px; margin: 2px 0; font-size: 14px; }
  .select2-container {
      width: 100% !important;
  }
</style>
<div id="loading" class="divLoading" style="display: none;">
  <p><img src="<?php echo base_url(); ?>assets/images/loading-4.gif"/></p>
</div>

<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header" style="text-align: center;">
    <h1> DRA Inspection Summary </h1>
  </section>

  <div class="col-md-12"> <br />
  </div>


  <!-- Main content -->
  <section class="content">
    <form method="POST" action="<?php echo base_url('iibfdra/Version_2/admin/InspectionSummary/export_to_pdf'); ?>">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info box-solid disabled">
          <div class="box-header with-border">
            <h3 class="box-title">Select Batch for Inspection</h3>
            <div class="box-body">
              <div class="row">
                <div class="col-md-10">
                  <select class="form-control" name="batch_id" id="batch_id">
                    <option value="">Select Batch</option>
                    <?php 
                      foreach ($batch as $key => $value) {
                        $first_faculty_photo     = $value['first_faculty_photo'];
                        $sec_faculty_photo       = $value['sec_faculty_photo'];
                        $add_first_faculty_photo = $value['add_first_faculty_photo'];
                        $add_sec_faculty_photo   = $value['add_sec_faculty_photo'];

                        $faculty_photo_url = base_url('uploads/faculty_photo').'/';
                        
                        $first_faculty_photo_url     = $faculty_photo_url.$first_faculty_photo;
                        $sec_faculty_photo_url       = $faculty_photo_url.$sec_faculty_photo; 
                        $add_first_faculty_photo_url = $faculty_photo_url.$add_first_faculty_photo; 
                        $add_sec_faculty_photo_url   = $faculty_photo_url.$add_sec_faculty_photo; 

                        $overall_compliance_list = $value['overall_compliance_list'];
                        $overall_compliance_str  = '';

                        if (!empty($overall_compliance_list)) {
                          // Split the string into an array using ',' as a delimiter
                          $arr_overall_compliance = explode(',', $overall_compliance_list);

                          // Check if the array has elements
                          if (count($arr_overall_compliance) > 0) {
                            foreach ($arr_overall_compliance as $akey => $overall_compliance_value) {
                              // Trim spaces and add numbering with a dot and a space
                              $overall_compliance_str .= ($akey + 1) . '. ' . trim($overall_compliance_value) . ' ';
                            }
                          }
                        }                       

                    ?>
                        <option value="<?php echo $value['id']; ?>" agency-id-attr="<?php echo $value['agency_id']; ?>" agency-name-attr="<?php echo $value['institute_name']; ?>" batch-code-attr="<?php echo $value['batch_code']; ?>"  batch-duration-attr="<?php echo $value['batch_from_date'].' To '.$value['batch_to_date']; ?>" batch_online_offline_flag-attr="<?php echo $value['batch_online_offline_flag']; ?>"  batch-platform-attr="<?php echo $value['online_training_platform']; ?>" batch-time-attr="<?php echo $value['hours'].' Hours'; ?>" daily-batch-timing="<?php echo $value['timing_from'].' To '.$value['timing_to']; ?>" candidate-count-attr="<?php echo $value['total_candidates']; ?>" batch-medium-attr="<?php echo $value['training_medium']; ?>" assigned-facultyid-attr1="<?php echo $value['first_faculty_id']; ?>" assigned-faculty-attr1="<?php echo $value['first_faculty_code'].'_'.$value['first_faculty_name']; ?>" assigned-facultyphoto-attr1="<?php echo $first_faculty_photo_url; ?>" assigned-facultyid-attr2="<?php echo $value['sec_faculty_id']; ?>" assigned-faculty-attr2="<?php echo $value['sec_faculty_code'].'_'.$value['sec_faculty_name']; ?>" assigned-facultyphoto-attr2="<?php echo $sec_faculty_photo_url; ?>" additional-facultyid-attr1="<?php echo $value['add_first_faculty_id']; ?>" additional-faculty-attr1="<?php echo $value['add_first_faculty_code'].'_'.$value['add_first_faculty_name']; ?>" additional-facultyphoto-attr1="<?php echo $add_first_faculty_photo_url; ?>" additional-facultyid-attr2="<?php echo $value['add_sec_faculty_id']; ?>" additional-faculty-attr2="<?php echo $value['add_sec_faculty_code'].'_'.$value['add_sec_faculty_name']; ?>" additional-facultyphoto-attr2="<?php echo $add_sec_faculty_photo_url; ?>" contact-person-attr="<?php echo $value['contact_person_name'].' ('. $value['contact_person_phone'].')';?>" alt-contact-person-attr="<?php echo $value['alt_contact_person_name'].' ('. $value['alt_contact_person_phone'].')';?>" platform-attr="<?php echo $value['platform_link'] ?>" ><?php echo $value['batch_code'].", (".$value['hours']." Hours, ".date("d/m/Y", strtotime($value['batch_from_date']))." To ".date("d/m/Y", strtotime($value['batch_to_date'])).", ".$value['timing_from']." To ".$value['timing_to'].", ".$value['training_medium'].")".' ('.$value['short_inst_name'].')'.' ('.$value['inspector_name'].')'. ' Reported Count ('.$value['reported'].') '.$overall_compliance_str; ?></option>
                      <?php }
                    ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <h4 id="inspector_no"></h4>
    <div style="display: flex;">
      <button type="submit" class="btn btn-sm btn-info" id="export_pdf" name="export_table" style="margin:0 0 10px 0; display: none">Export to PDF</button>
      <a class="btn btn-sm btn-info" id="batch_detail_btn" name="batch_detail_btn" target="_blank" style="margin:0 0 10px 10px; display: none" href="#">Go to Batch Details</a>  
    </div>
    
    </form>

    <div class="table-responsive box box-info box-solid disabled" id="batch_autofilled_div" style="display: none">
      <table class="table table-bordered table-striped" >
        <tbody>
          <tr>
            <td width="29%"><strong>Name of the DRA Accredited Institution/Bank/FI:</strong></td>
            <td width="20%" id="agency_name"></td>
            <td width="2%"></td>
            <td width="29%"><strong>Batch Type:</strong></td>
            <td width="20%" id="training_time_duration"></td>
          </tr>
          <tr>
            <td width="29%"><strong> Batch Code :</strong></td>
            <td width="20%" id="batch_code"></td>
            <td width="2%"></td>
            <td width="29%"><strong>Batch Duration:</strong></td>
            <td width="20%" id="duration"></td>
          </tr>
          <tr>
            <td width="29%"><strong>Daily Training Timing:</strong></td>
            <td width="20%" id="daily_training_timing"></td>
            <td width="2%"></td>
            <td width="29%"><strong>No. of candidates enrolled in the Batch:</strong></td>
            <td width="20%" id="candidate_count"></td>
          </tr>
          <tr>
            <td width="29%"><strong>Assigned Faculty (main 1):</strong></td>
            <td width="20%"><span id="assigned_faculty_photo1"></span><br><a href="" id="assigned_faculty1" target="_blank"></a></td>
            <td width="2%"></td>
            <td width="29%"><strong>Assigned Faculty(main 2):</strong></td>
            <td width="20%"><span id="assigned_faculty_photo2"></span><br><a href="" id="assigned_faculty2" target="_blank"></a></td>
          </tr>
          <tr>
            <td width="29%"><strong>Assigned Faculty (additional 1):</strong></td>
            <td width="20%"><span id="additional_faculty_photo1"></span><br><a href="" id="additional_faculty1" target="_blank"></a></td>
            <td width="2%"></td>
            <td width="29%"><strong>Assigned Faculty(additional 2):</strong></td>
            <td width="20%"><span id="additional_faculty_photo2"></span><br><a href="" id="additional_faculty2" target="_blank"></a></td>
          </tr>
          <tr>
            <td width="29%"><strong>Co-ordinator name and Mobile no. :</strong></td>
            <td width="20%" id="coordinator_name"></td>
            <td width="2%"></td>
            <td width="20%"><strong>Co-ordinator name and Mobile no. (additional):</strong></td>
            <td width="29%" id="additional_coordinator_name"></td>
          </tr>

          <tr>
            <td width="20%"><strong>Training Language :</strong></td>
            <td width="29%" id="training_language"></td>
          </tr>

          <tr class="online_offline_flag">
            <td width="29%"><strong>Name of the on-line platform:</strong></td>
            <td width="20%" id="batch_training_platform"></td>
            <td width="2%"></td>
            <td width="29%"><strong>Platform Link:</strong></td>
            <td width="20%"><a href="" id="platform_link_href" target="_blank"></a></td>
          </tr>

          <tr class="online_offline_flag">
            <td width="29%"><strong>Login ID/Password:</strong></td>
            <td id="login_pwd_tbl">
              
            </td>
          </tr>
          
          <?php /*?><tr>
            <td width="29%"><strong>Date/Start Time of Inspection:</strong></td>
            <td width="20%"><?php echo date('Y-m-d H:i:s'); ?></td>
            <td width="2%"></td>
            <td width="29%"><strong>Inspector Name/ID:</strong></td>
            <td width="20%" ><?php echo $inspector_name.'/'.$inspector_id; ?></td>
          </tr><?php */?>
          
        </tbody>
      </table>
    </div>


    <div class="row" id="batch_inspecton_div" style="display: none">
      <div class="col-md-12">
        <div class="box box-info box-solid disabled">
          <div class="box-header with-border">
            <h3 class="box-title">Batch Inspection</h3>
            <div class="box-tools pull-right">
              <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
            </div>
            <!-- /.box-tools --> 
          </div>
          <!-- /.box-header -->
          <div class="box-body" style="display: block;">
           
            
            <input type="hidden" name="agency_id" id="agency_id" value="">

            <div class="table-responsive">
              <table class="table table-bordered table-striped" >
                <tbody id="batch_insp">
               
                </tbody>
              </table>
            </div>
           
          </div>
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
      </div>

      <div class="col-xs-12">
        <div class="box-header">
          <h3 class="box-title">Batch Candidate's Details</h3>
          <div class="box-tools pull-right">
            <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
          </div>
        </div>
        <div class="box">
          <div class="box-body">
            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
            <input type="hidden" name="base_url_val" id="base_url_val" value="" />
            <!-- <input type="hidden" name="question_checked_array" id="question_checked_array" value="" /> -->

            <div class="table-responsive">
              <table id="listitems" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="3">S.No.</th> 
                    <th width="7">Training Id</th>
                    <th width="20">Candidate Name</th>
                    <th width="5">DOB</th>
                    <th width="10">ID Proof</th>
                    <th width="10">Candidate Photo</th>
                    <th width="10">Candidate Photo Status</th>
                    <th width="10">Candidate Photo Remark</th>
                    <th width="20">Present Count</th>
                    <th width="20">Absent Count</th>
                    <th width="20">Remark</th>
                    <th width="20">Qualification Certification</th>
                    <th width="20">Qualification Certification Verify</th>
                    <th width="20">Qualification Certification Remark</th>
                    <th width="20">Status</th>
                    <th width="20">Action</th>
                    <th width="20" align="center">Manual Hold/Release <input type="checkbox" align="center" id="selectall" value="selectall"/></th>
                  </tr>
                 </thead>
                <tbody class="no-bd-y" id="list">
                 
                </tbody>
                <tfoot>
                  <tr>
                    <th width="3"></th> 
                    <th width="7"></th>
                    <th width="20"></th>
                    <th width="5"></th>
                    <th width="10"></th>
                    <th width="10"></th>
                    <th width="20"></th>
                    <th width="20"></th>
                    <th width="20"></th>
                    <th width="20"></th>
                    <th width="20"></th>
                    <th width="20"></th>
                    <th width="20"></th>
                    <th width="20"></th>
                    <th width="20"></th>
                    <th width="20"></th>
                    <th width="20">
                      <div class="hold_release_btns">
                        <button type="button" class="btn btn-danger btn_hold_release" value="Manual Hold">Hold</button>
                        <button type="button" class="btn btn-success btn_hold_release" value="Release">Release</button>
                      </div>
                    </th>
                  </tr>
                </tfoot>
              </table>
              

              <div style="display: flex; justify-content: center;">
                <table class="table table-bordered" style="width: 50%;">
                  <thead>
                    <tr>
                      <th>Sr. No.</th>
                      <th>Date Time</th>
                      <th>Present Count</th>
                      <th>Absent Count</th>
                      <th>Total</th>
                    </tr>
                    <tr>
                      <tbody class="no-bd-y inspection_count_data">
                        <!-- <tr>
                          <td>Total</td>
                          <td id="present_count"></td>
                          <td id="absent_count"></td>
                        </tr> -->
                      </tbody>
                    </tr>  
                  </thead>
                </table>
              </div>

            </div>
          </div>
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
      </div>
      <!-- /.col collapsed-box-->
      
     <!--  <div class="col-sm-4 col-xs-offset-3">
        <button type="button" class="btn btn-info" id="btn_hold_release">Hold</button>
         <input type="reset" class="btn btn-danger" name="btnReset" id="btnReset" value="Reset" onclick="btn_reset()"> 
      </div> -->
     
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

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>$(document).ready(function() { $('#batch_id').select2(); }); </script>


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

function btn_reset()
{
  $("#batch_id").val('');
  $('#batch_autofilled_div').css('display', 'none');
  $('#batch_inspecton_div').css('display', 'none');
  $('#export_pdf').css('display', 'none');
  $('#batch_detail_btn').css('display', 'none');
  $('#agency_name').text('');
  $('#batch_code_duration').text('');
  $('#daily_training_timing').text('');
  $('#batch_training_platform').text('');
  $('#training_time_duration').text();
  $('#training_language').text('');
  $('#assigned_faculty1').text('');
  $('#additional_faculty1').text('');
  $('#assigned_faculty2').text('');
  $('#additional_faculty2').text('');
  $('#candidate_count').text('');
  $('#coordinator_name').text('');
  $('#additional_coordinator_name').text('');
  $('#platform_link').text('');
  $('#agency_id').val('');
  $('#loading').hide();
}

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


  // Handle click on "Select all" control
  $('#selectall').on('click', function(){
      // Get all rows with search applied
      var rows = table.rows({ 'search': 'applied' }).nodes();
      // Check/uncheck checkboxes for all rows in the table
      $('input[type="checkbox"]', rows).prop('checked', this.checked);
     
      if (this.checked) {
        var rowData = table.data().toArray();

        for(var j = 0; j < rowData.length; j++)
        {
          //var data = rowData[j][2];
          var data = JSON.stringify(rowData[j]);
          if(selectedData.indexOf(data) == -1)
          {
            selectedData.push(data);
          }
        }
      }
      else{
        var rowData1 = table.data().toArray();
        for(var i = 0; i < rowData1.length; i++)
        {
          var data1 = JSON.stringify(rowData1[i]);
          for(var j = selectedData.length-1; j >= 0; j--)
          {
            if(selectedData[j] == data1)
            {
              selectedData.splice(j, 1);
            }
          }
        }
      }
    });

  $('.btn_hold_release').on('click',function () {
    var candidates_checked_array = [];
    var candidates_unchecked_array = [];
    var batch_id  = $('#batch_id').val();

    var status = $(this).val();

    console.log('---'+status);

    $.each($("input[name='checkbox_data']:checked"), function(){
      candidates_checked_array.push($(this).val());
    });

    if(candidates_checked_array.length > 0){
      $.ajax({
        url:site_url+'iibfdra/Version_2/admin/InspectionSummary/candidate_hold',
        type: 'POST',
        data: {candidates_checked_array: candidates_checked_array,status:status,batch_id:batch_id},
        success: function(response){
         console.log(response);//return false;
         var res = response.split('---');
         var data = res[1];
         if(res[0] == 1){
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
            //location.reload();
            $('input[type="checkbox"]').prop('checked','');

            if(data != '')
            {
              table.clear().draw();
              $.each(JSON.parse(data), function(idx, obj) {
                var j = parseInt(idx)+1;
                var k = parseInt(idx)+1;

                var ph = site_url+"uploads/iibfdra/"+obj.scannedphoto;
                var quali = site_url+"uploads/iibfdra/"+obj.quali_certificate;
                var idproofphoto = site_url+"uploads/iibfdra/"+obj.idproofphoto;

                var http = new XMLHttpRequest();
                http.open('HEAD', ph, false);
                http.send();
                //console.log(http.status);

                if(http.status == 404){
                  ///var no_img = 'no_image1.png';
                  ///var photo = '<img height="90" width="120" id="photo_'+obj.regid+'" src="'+site_url+"assets/images/"+no_img+'" alt="">';
                  var photo = '';
                } 
                else 
                {
                  var photo = '<a href="'+site_url+"uploads/iibfdra/"+obj.scannedphoto+'" target="_blank"><img height="90" width="70" id="photo_'+obj.regid+'" src="'+site_url+"uploads/iibfdra/"+obj.scannedphoto+'" alt=""></a>';
                }

                var http = new XMLHttpRequest();
                http.open('HEAD', idproofphoto, false);
                http.send();
                //console.log(http.status);

                if(http.status == 404){
                  ///var no_img = 'no_image1.png';
                  ///var photo = '<img height="90" width="120" id="photo_'+obj.regid+'" src="'+site_url+"assets/images/"+no_img+'" alt="">';
                  var idproof = '';
                } 
                else 
                {
                  var idproof = '<a href="'+site_url+"uploads/iibfdra/"+obj.idproofphoto+'" target="_blank"><img height="90" width="70" id="photo_'+obj.regid+'" src="'+site_url+"uploads/iibfdra/"+obj.idproofphoto+'" alt=""> </a>';
                }

                var http = new XMLHttpRequest();
                http.open('HEAD', quali, false);
                http.send();
                //console.log(http.status);

                if(http.status == 404){
                  ///var no_img = 'no_image1.png';
                  ///var photo = '<img height="90" width="120" id="photo_'+obj.regid+'" src="'+site_url+"assets/images/"+no_img+'" alt="">';
                  var quali = '';
                } 
                else 
                {
                  var quali = '<a href="'+site_url+"uploads/iibfdra/"+obj.quali_certificate+'" target="_blank"> <img height="90" width="70" id="quali_'+obj.regid+'" src="'+site_url+"uploads/iibfdra/"+obj.quali_certificate+'" alt=""></a>';
                }

                var view = '<a href="'+site_url+"iibfdra/Version_2/admin/inspectionSummary/details/"+btoa(obj.regid)+'" target="_blank">details </a>'

                /*if(obj.remark.includes('|') == true){
                  var rmrk = obj.remark.replaceAll('|', '<br>'+k+')')
                }*/

                var checkbox = '<input type="checkbox" align="center" class="btn_check" name="checkbox_data" id="id_'+obj.regid+'" value="'+obj.regid+'" >';

                // values = [[j, obj.training_id, obj.name, obj.dateofbirth, obj.mobile_no, photo, obj.present_cnt, obj.absent_cnt, obj.remark, obj.hold_release, view, checkbox]];

                values = [[j, obj.training_id, obj.name, obj.dateofbirth, idproof, photo, obj.photo_verify, obj.photo_remark, obj.present_cnt, obj.absent_cnt, obj.remark,quali,obj.qualification_verify,obj.qualification_remark, obj.hold_release, view, checkbox]];
                  
                table.rows.add(values).draw();
              });
              //tableData = JSON.parse(JSON.stringify(data)); 
            }
          });
         }
        }
      })
      //$('#btn_submit').prop('disabled', false);
    }
    else{
      var msg = "Please Select atleast one Candidate";
      swal(msg, "", "warning");
      //$('#btn_submit').prop('disabled', true);
    }
  });

  $('#batch_id').on('change',function () {
    $('#loading').show();
    $('#batch_insp').empty();
    var batch_id = $(this).val();
    var agency_id = $(this).children(":selected").attr('agency-id-attr');
    var agency_name = $(this).children(":selected").attr('agency-name-attr');
    var batch_code = $(this).children(":selected").attr('batch-code-attr');
    var batch_duration = $(this).children(":selected").attr('batch-duration-attr');
    var batch_platform = $(this).children(":selected").attr('batch-platform-attr');
    var batch_time_duration = $(this).children(":selected").attr('batch-time-attr');
    var daily_training_timing = $(this).children(":selected").attr('daily-batch-timing');
    var candidate_count = $(this).children(":selected").attr('candidate-count-attr');
    var batch_medium = $(this).children(":selected").attr('batch-medium-attr');
    
    var assigned_facultyid1 = $(this).children(":selected").attr('assigned-facultyid-attr1');
    var additional_facultyid1 = $(this).children(":selected").attr('additional-facultyid-attr1');
    var assigned_facultyid2 = $(this).children(":selected").attr('assigned-facultyid-attr2');
    var additional_facultyid2 = $(this).children(":selected").attr('additional-facultyid-attr2');

    var assigned_facultyphoto1   = $(this).children(":selected").attr('assigned-facultyphoto-attr1');
    var assigned_facultyphoto2   = $(this).children(":selected").attr('assigned-facultyphoto-attr2');
    var additional_facultyphoto1 = $(this).children(":selected").attr('additional-facultyphoto-attr1');
    var additional_facultyphoto2 = $(this).children(":selected").attr('additional-facultyphoto-attr2');

    var assigned_faculty1 = $(this).children(":selected").attr('assigned-faculty-attr1');
    var additional_faculty1 = $(this).children(":selected").attr('additional-faculty-attr1');
    var assigned_faculty2 = $(this).children(":selected").attr('assigned-faculty-attr2');
    var additional_faculty2 = $(this).children(":selected").attr('additional-faculty-attr2');
    
    var coordinator_name = $(this).children(":selected").attr('contact-person-attr');
    var additional_coordinator_name = $(this).children(":selected").attr('alt-contact-person-attr');
    var platform_link = $(this).children(":selected").attr('platform-attr');

    var batch_online_offline_flag = $(this).children(":selected").attr('batch_online_offline_flag-attr');
   
    // console.log('batch_id-'+batch_id+'agency_id--'+agency_id);

    if(batch_id != '') {

      var http = new XMLHttpRequest();
      http.open('HEAD',assigned_facultyphoto1, false);
      http.send();
     
      if(http.status == 404){
        var assigned_facultyphoto1_img = '';
      } 
      else 
      { 
        if (assigned_faculty1 != '_') {
          var assigned_facultyphoto1_img = '<img height="90" width="70" src="'+assigned_facultyphoto1+'" alt="">';
          $('#assigned_faculty_photo1').html(assigned_facultyphoto1_img);
        }
      }

      var http = new XMLHttpRequest();
      http.open('HEAD',assigned_facultyphoto2, false);
      http.send();
      
      if(http.status == 404){
        var assigned_facultyphoto2_img = '';
      } 
      else 
      {
        if (assigned_faculty2 != '_') {
          var assigned_facultyphoto2_img = '<img height="90" width="70" src="'+assigned_facultyphoto2+'" alt="">';
          $('#assigned_faculty_photo2').html(assigned_facultyphoto2_img);
        }
      }

      var http = new XMLHttpRequest();
      http.open('HEAD',additional_facultyphoto1, false);
      http.send();
      console.log(http.status);     
      if(http.status == 404){
        
        var additional_facultyphoto1_img = '';
      } 
      else 
      {
        if (additional_faculty1 != '_') {
          var additional_facultyphoto1_img = '<img height="90" width="70" src="'+additional_facultyphoto1+'" alt="">';
          $('#additional_faculty_photo1').html(additional_facultyphoto1_img);
        }
      }

      var http = new XMLHttpRequest();
      http.open('HEAD',additional_facultyphoto2, false);
      http.send();
      console.log(http.status);
      if(http.status == 404){
        var additional_facultyphoto2_img = '';
      } 
      else 
      {
        if (additional_faculty2 != '_') {
          var additional_facultyphoto2_img = '<img height="90" width="70" src="'+additional_facultyphoto2+'" alt="">';        
          $('#additional_faculty_photo2').html(additional_facultyphoto2_img);
        }
      }
      var batch_details_url = site_url+'iibfdra/Version_2/batch/batch_detail/'+btoa(batch_id);

      $('#batch_autofilled_div').css('display', 'block');
      $('#batch_inspecton_div').css('display', 'block');
      $('#export_pdf').css('display', 'block');
      $('#batch_detail_btn').css('display', 'inline-block');
      $('#batch_detail_btn').attr('href', batch_details_url);
      $('#agency_name').text(agency_name);
      $('#batch_code').text(batch_code);
      $('#duration').text(batch_duration);
      $('#daily_training_timing').text(daily_training_timing);
      $('#batch_training_platform').text(batch_platform);
      $('#training_time_duration').text(batch_time_duration);
      $('#training_language').text(batch_medium);
      $('#assigned_faculty1').text(assigned_faculty1);
      $('#assigned_faculty1').attr('href',site_url+'iibfdra/Version_2/admin/faculty_master/faculty_view/'+btoa(assigned_facultyid1));
      $('#additional_faculty1').text(additional_faculty1);
      $('#additional_faculty1').attr('href',site_url+'iibfdra/Version_2/admin/faculty_master/faculty_view/'+btoa(additional_facultyid1));
      $('#assigned_faculty2').text(assigned_faculty2);
      $('#assigned_faculty2').attr('href',site_url+'iibfdra/Version_2/admin/faculty_master/faculty_view/'+btoa(assigned_facultyid2));
      $('#additional_faculty2').text(additional_faculty2);
      $('#additional_faculty2').attr('href',site_url+'iibfdra/Version_2/admin/faculty_master/faculty_view/'+btoa(additional_facultyid2));
      $('#candidate_count').text(candidate_count);
      $('#coordinator_name').text(coordinator_name);
      $('#additional_coordinator_name').text(additional_coordinator_name);
      $('#platform_link').text(platform_link);
      $('#platform_link_href').text(platform_link);
      $('#platform_link_href').attr('href', platform_link);
      $('#agency_id').val(agency_id);

      if(batch_online_offline_flag == 1){
        $('.online_offline_flag').show();
      }
      else{
        $('.online_offline_flag').hide();
      }

      $.ajax({
          url:site_url+'iibfdra/Version_2/admin/InspectionSummary/get_candidate_data',
          data: {batch_id: batch_id,type:'summary'},
          type:'POST',
          //async: false,
          success: function(res) {
            // console.log(res);
            var res1 = res.split('~~~~');
            
            $('#batch_insp').append(res1[0]);
            var data      = res1[1];
            var insp_data = res1[3];

            var present_count = 0;
            var absent_count  = 0;

            if(data != '')
            {
              table.clear().draw();
              $.each(JSON.parse(data), function(idx, obj) {
                var j = parseInt(idx)+1;
                var k = parseInt(idx)+1;

                var ph    = site_url+"uploads/iibfdra/"+obj.scannedphoto;
                var quali = site_url+"uploads/iibfdra/"+obj.quali_certificate;
                var idproofphoto = site_url+"uploads/iibfdra/"+obj.idproofphoto;
                
                var http = new XMLHttpRequest();
                http.open('HEAD', ph, false);
                http.send();
                //console.log(http.status);

                if(http.status == 404){
                  var photo = '';
                } 
                else 
                {
                  var photo = '<a href="'+site_url+"uploads/iibfdra/"+obj.scannedphoto+'" target="_blank"><img height="90" width="70" id="photo_'+obj.regid+'" src="'+site_url+"uploads/iibfdra/"+obj.scannedphoto+'" alt=""></a>';
                }

                var http = new XMLHttpRequest();
                http.open('HEAD', idproofphoto, false);
                http.send();
                //console.log(http.status);

                if(http.status == 404){
                  ///var no_img = 'no_image1.png';
                  ///var photo = '<img height="90" width="120" id="photo_'+obj.regid+'" src="'+site_url+"assets/images/"+no_img+'" alt="">';
                  var idproof = '';
                } 
                else 
                {
                  var idproof = '<a href="'+site_url+"uploads/iibfdra/"+obj.idproofphoto+'" target="_blank"><img height="90" width="70" id="photo_'+obj.regid+'" src="'+site_url+"uploads/iibfdra/"+obj.idproofphoto+'" alt=""> </a>';
                }


                var http = new XMLHttpRequest();
                http.open('HEAD', quali, false);
                http.send();
                //console.log(http.status);

                if(http.status == 404){
                  ///var no_img = 'no_image1.png';
                  ///var photo = '<img height="90" width="120" id="photo_'+obj.regid+'" src="'+site_url+"assets/images/"+no_img+'" alt="">';
                  var quali = '';
                } 
                else 
                {
                  var quali = '<a href="'+site_url+"uploads/iibfdra/"+obj.quali_certificate+'" target="_blank"> <img height="90" width="70" id="quali_'+obj.regid+'" src="'+site_url+"uploads/iibfdra/"+obj.quali_certificate+'" alt=""></a>';
                }

                var view = '<a href="'+site_url+"iibfdra/Version_2/admin/inspectionSummary/details/"+btoa(obj.regid)+'" target="_blank">details </a>'

                var checkbox = '<input type="checkbox" align="center" class="btn_check" name="checkbox_data" id="id_'+obj.regid+'" value="'+obj.regid+'" >';

                present_count = present_count + parseInt(obj.present_cnt);
                absent_count  = absent_count + parseInt(obj.absent_cnt);

                values = [[j, obj.training_id, obj.name, obj.dateofbirth,idproof, photo, obj.photo_verify, obj.photo_remark, obj.present_cnt, obj.absent_cnt, obj.remark,quali,obj.qualification_verify,obj.qualification_remark, obj.hold_release, view, checkbox]];

                  
                table.rows.add(values).draw();
              }); 

              var insp_cnt_html = '';

              $.each(JSON.parse(insp_data), function(insp_idx, insp_obj) {
                var rowIndex = parseInt(insp_idx)+1;
                var present_cnt = parseInt(insp_obj.present_cnt);
                var absent_cnt  = parseInt(insp_obj.absent_cnt);
                var date_time  = insp_obj.date_time;
                var total_cnt   = present_cnt+absent_cnt;
                insp_cnt_html += '<tr>';
                insp_cnt_html += '  <td>'+rowIndex+'</td>';
                insp_cnt_html += '  <td>'+date_time+'</td>';
                insp_cnt_html += '  <td>'+present_cnt+'</td>';
                insp_cnt_html += '  <td>'+absent_cnt+'</td>';
                insp_cnt_html += '  <td>'+total_cnt+'</td>';
                insp_cnt_html += '</tr>'; 
              });  

              // insp_cnt_html += '<tr>';
              // insp_cnt_html += '  <td>Total</td>';
              // insp_cnt_html += '  <td>'+present_count+'</td>';
              // insp_cnt_html += '  <td>'+absent_count+'</td>';
              // insp_cnt_html += '</tr>';
            }

            $('.inspection_count_data').append(insp_cnt_html)

            // $('#present_count').text(present_count);
            // $('#absent_count').text(absent_count);

            $('#login_pwd_tbl').html('');
            $('#login_pwd_tbl').append(res1[2]);
            $('#loading').hide();
          }
      });
    }
    else
    {
      $('#batch_autofilled_div').css('display', 'none');
      $('#batch_inspecton_div').css('display', 'none');
      $('#export_pdf').css('display', 'none');
      $('#batch_detail_btn').css('display', 'none');      
      $('#agency_name').text('');
      $('#batch_code_duration').text('');
      $('#daily_training_timing').text('');
      $('#batch_training_platform').text('');
      $('#training_time_duration').text();
      $('#training_language').text('');
      $('#assigned_faculty1').text('');
      $('#additional_faculty1').text('');
      $('#assigned_faculty2').text('');
      $('#additional_faculty2').text('');
      $('#candidate_count').text('');
      $('#coordinator_name').text('');
      $('#additional_coordinator_name').text('');
      $('#platform_link').text('');
      $('#agency_id').val('');
      $('#loading').hide();
    }
  });

	
});


</script>
    <script>
    if ( window.history.replaceState ) {
      window.history.replaceState( null, null, window.location.href );
    }
</script>
<?php $this->load->view('iibfdra/Version_2/admin/includes/footer');?>