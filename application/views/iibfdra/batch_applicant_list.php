
<?php 
 /* $drainspdata = $this->session->userdata('dra_institute'); 
  $inspector_name = $drainspdata['inspector_name'];
  $inspector_id = $drainspdata['id'];*/
?>
<style type="text/css">
  td.details-control {
    background: url('https://cdn.rawgit.com/DataTables/DataTables/6c7ada53ebc228ea9bc28b1b216e793b1825d188/examples/resources/details_open.png') no-repeat center center;
    cursor: pointer;
  }
  tr.shown td.details-control {
    background: url('https://cdn.rawgit.com/DataTables/DataTables/6c7ada53ebc228ea9bc28b1b216e793b1825d188/examples/resources/details_close.png') no-repeat center center;
  }

  #loading { display: none;	position: fixed; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; }
  #loading > p { margin: 0 auto; width: 100%; height: 100%; position: absolute; top: 20%; }
  #loading > p > img { max-height: 250px; margin:0 auto; display: block; }

  .select2-selection.select2-selection--single { border-radius: 0 !important; padding: 5px 0 2px 0px; height: auto !important; max-width: none; }
</style>

<div id="loading" class="divLoading" style="display: none;">
  <p><img src="<?php echo base_url(); ?>assets/images/loading-4.gif"/></p>
</div>

<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header" style="text-align: center;">
    <h1> Batch Checklist Report </h1>
  </section>

  <div class="col-md-12"> <br />
  </div>
  <!-- Main content -->
  <form method="POST" action="<?php echo base_url('iibfdra/TrainingBatches/export_to_pdf'); ?>">
  <section class="content">
    
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info box-solid disabled">
          <div class="box-header with-border">
            <h3 class="box-title">Select Batch</h3>
            <div class="box-body">
              <div class="row">
                <div class="col-md-6">
                  <select class="form-control" id="batch_id" name="batch_id">
                    <option value="">Select Batch</option>
                    <?php 
                      foreach ($batch as $key => $value) 
                      {?>
                       <option value="<?php echo $value['id']; ?>"><?php echo $value['batch_code']." (".$value['hours']." Hours - ".date("d M Y, h:i A", strtotime($value['created_on'])).")"; ?></option>
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

    <div class="row" id="batch_inspecton_div" style="display: none">
      <div class="col-xs-12">
        <div class="box-header">
          <h3 class="box-title">Batch Candidate's Details</h3>
          <div class="box-tools pull-right">
            <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
          </div>
        </div>
        <div class="box">
          <div class="box-body">

            <button type="submit" class="btn btn-sm btn-info" id="export_pdf" name="export_table" style="margin:0 0 10px 0;">Export to PDF</button>
            <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
            <input type="hidden" name="base_url_val" id="base_url_val" value="" />
            <!-- <input type="hidden" name="question_checked_array" id="question_checked_array" value="" /> -->
            
            <div class="table-responsive">
              <table id="listitems" class="table table-bordered table-striped" width="100%">
                <thead>
                  <tr>
                    <th width="1%">Expand</th> 
                    <th width="2%">Sr</th> 
                    <th width="7%">Training Id</th>
                    <th width="10%">Candidate Name</th>
                    <!--<th>Gender</th>
                    <th>DOB</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Address</th> 
                    <th>Qualification</th>
                    <th>Education Qualification</th>-->
                    <th width="7%">Id Proof</th>
                    <th width="10%">Id Proof No</th>
                    <th width="15%">Photo</th>
                    <th width="15%">Signature</th>
                    <th width="15%">Id Proof Photo</th>
                    <th width="15%">Qualification Certificate</th>
                  </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                 
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
        <!-- <button type="submit" class="btn btn-info" id="btnSubmit" name="submit">Submit</button> -->
        <input type="reset" class="btn btn-danger" name="btnReset" id="btnReset" value="Reset" onclick="btn_reset()">  
      </div>
      
    </div>
 
  </section>
  </form>
</div>
<!-- Data Tables -->

<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<!-- Data Tables --> 
<!-- <script src="<?php //echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>  -->
<script type="text/javascript" src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script> 
<script src="<?php echo base_url()?>js/js-paginate.js"></script> 

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>$(document).ready(function() { $('#batch_id').select2(); }); </script>

<script>
function btn_reset()
{
  $('#batch_inspecton_div').hide();
}

var site_url = '<?php echo base_url(); ?>';
$(function () {

  var selectedData = [];

  var table  = $('#listitems').DataTable({

    "dom": 'Blftirp',
    "stateSave": true,
    "paging": true,
    'responsive': true,


    "columnDefs": [                  
      {
        "className": 'details-control',
        "orderable": false,
        "data":      null,
        "targets":   0,
        "defaultContent": ''
      },
    ]
  });

  // Add event listener for opening and closing details
  $('#listitems tbody').on('click', '.details-control', function(){
    var tr = $(this).closest('tr');
    var row = table.row( tr );

    if(row.child.isShown()){
      // This row is already open - close it
      row.child.hide();
      tr.removeClass('shown');
    } else {
      // Open this row
      row.child(format(row.data())).show();
      tr.addClass('shown');
    }
  });


  $('#batch_id').on('change',function () {
    $('.divLoading').show();
    
    var batch_id = $(this).val();
    if(batch_id != '')
    {
      $.ajax({
        url:site_url+'iibfdra/TrainingBatches/get_candidate_data',
        data: {batch_id: batch_id},
        type:'POST',
        //async: false,
        success: function(res) {

          if(res != '')
          {
            $('#batch_inspecton_div').css('display', 'block');
            table.clear().draw();
            $.each(JSON.parse(res), function(idx, obj) {
              var j = parseInt(idx)+1;

              var ph = site_url+"uploads/iibfdra/"+obj.scannedphoto;

              var http = new XMLHttpRequest();
              http.open('HEAD', ph, false);
              http.send();
              //console.log(http.status);

              if(http.status == 404){
                //var no_img = 'no_image1.png';
                //var photo = '<img height="90" width="120" id="photo_'+obj.regid+'" src="'+site_url+"assets/images/"+no_img+'" alt="">';
                var photo = '';
              } else {
                var photo = '<img height="90" width="70" id="photo_'+obj.regid+'" src="'+site_url+"uploads/iibfdra/"+obj.scannedphoto+'" alt="">';
              }

              if(http.status == 404){
                //var no_img = 'no_image1.png';
                //var signature = '<img height="90" width="120" id="photo_'+obj.regid+'" src="'+site_url+"assets/images/"+no_img+'" alt="">';
                var signature = '';
              } else {
                var signature = '<img height="90" width="70" id="photo_'+obj.regid+'" src="'+site_url+"uploads/iibfdra/"+obj.scannedsignaturephoto+'" alt="">';
              }

              if(http.status == 404){
                //var no_img = 'no_image1.png';
                //var quali_certificate = '<img height="90" width="120" id="photo_'+obj.regid+'" src="'+site_url+"assets/images/"+no_img+'" alt="">';
                var quali_certificate = '';
              } else {
                var quali_certificate = '<img height="90" width="70" id="photo_'+obj.regid+'" src="'+site_url+"uploads/iibfdra/"+obj.quali_certificate+'" alt="">';
              }

              if(http.status == 404){
                //var no_img = 'no_image1.png';
                //var idproofphoto = '<img height="90" width="120" id="photo_'+obj.regid+'" src="'+site_url+"assets/images/"+no_img+'" alt="">';
                var idproofphoto = '';
              } else {
                var idproofphoto = '<img height="90" width="70" id="photo_'+obj.regid+'" src="'+site_url+"uploads/iibfdra/"+obj.idproofphoto+'" alt="">';
              }

              values = [[obj.ex, j, obj.training_id, obj.name, obj.idtype_name, obj.idproof_no, photo, signature, idproofphoto, quali_certificate, obj.gender, obj.dateofbirth, obj.mobile_no, obj.email_id, obj.address, obj.qualification_type, obj.qualification]];
                
              table.rows.add(values).draw();                           
            });
            //tableData = JSON.parse(JSON.stringify(data));            
          }
          $('.divLoading').hide();
        }
      });
    }
    else
    {
      $('#batch_inspecton_div').hide();
      $('.divLoading').hide();
    }
  });

  $('#export_pdf').click(function(e){
    console.log('---');
    $('.divLoading').show();
    var batch_id = $('#batch_id').val();
    location.href = site_url+"TrainingBatches/export_to_pdf/"+batch_id;
    $('.divLoading').hide();
  });
});



/* Formatting function for row details - modify as you need */
function format ( d ) {
    // `d` is the original data object for the row
    console.log('d:'+d);
    return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
        '<tr>'+
            '<td>Gender:</td>'+
            '<td>'+d[10]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>DOB:</td>'+
            '<td>'+d[11]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Mobile No:</td>'+
            '<td>'+d[12]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Email:</td>'+
            '<td>'+d[13]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Address:</td>'+
            '<td>'+d[14]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Qualification:</td>'+
            '<td>'+d[15]+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Education Qualification:</td>'+
            '<td>'+d[16]+'</td>'+
        '</tr>'+
    '</table>';
}


</script>
    <script>
    if ( window.history.replaceState ) {
      window.history.replaceState( null, null, window.location.href );
    }
</script>
