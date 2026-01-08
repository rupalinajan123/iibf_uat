<?php $this->load->view('vendors/admin/includes/header');?>
<?php $this->load->view('vendors/admin/includes/sidebar');?>

<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">

<style>
.search_form_common_all { background: #ededed; padding: 20px 20px 10px 20px; margin-bottom: 20px; text-align: left; }
.search_form_common_all .form-group { display: inline-block; margin: 0 10px 10px; width: 300px; vertical-align: top; }
.search_form_common_all .form-group .form-label { display: block; font-size: 14px; margin: 0 0 3px 0; line-height: 22px; text-align: left; }
.search_form_common_all .form-group .form-control { padding: 5px 20px 5px 10px; height: 35px !important; }
.search_form_common_all .btn { display: inline-block; vertical-align: top; padding: 7px 20px 6px; margin: 0 0px 0 0; min-width: 97px; }

#listitems22_processing { display:none !important; }
#page_loader { background: rgba(0, 0, 0, 0.35) none repeat scroll 0 0; height: 100%; left: 0; position: fixed; top: 0; width: 100%; z-index: 99999; display:none; }
/* #page_loader .loading { margin: 0 auto; position: relative;border: 16px solid #f3f3f3;border-radius: 50%;border-top: 16px solid #064b86;border-bottom: 16px solid #064b86;width: 80px;height: 80px;-webkit-animation: spin 2s linear infinite;animation: spin 2s linear infinite;top: calc( 50% - 40px);} */
#page_loader .loading { margin: 0 auto; position: relative; width: 80px;height: 80px;top: calc( 50% - 40px);color: #fff;font-size: 30px; }
@-webkit-keyframes spin { 0% { -webkit-transform: rotate(0deg); } 100% { -webkit-transform: rotate(360deg); } }
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

.datepicker table tr td.disabled, .datepicker table tr td.disabled:hover, .datepicker table tr td span.disabled, .datepicker table tr td span.disabled:hover { cursor: not-allowed; }

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

<div id="page_loader"><div class="loading">Processing...</div></div>

  <div class="content-wrapper">
    <section class="content-header">      
      <h1>
        Vendor Registration List
        <?php if($batch_id != "") 
        { ?>
          <!-- <a href="<?php echo site_url('vendors/batch');?>" class="btn btn-warning pull-right">Back</a> -->
        <?php } ?> 
        <?php //echo $breadcrumb; ?>
      </h1>
    </section>  
  
    <section class="content">

      <?php //$this->load->view('vendors/inc_navbar'); ?>

      <div class="row">
        <div class="col-xs-12">
          <div class="box">           
            <div class="box-body"> 
              <form class="form-control border-0" action="<?php echo base_url('vendors/Dashboard/get_vendor_registration'); ?>" method="post">
              <div class="row">    
                  <div class="col-md-3 col-sm-6">
              <div class="form-group">
                <label class="form-label">From Date</label>
                <input type="text" class="form-control custom_filter" name="from_date" id="from_date" value="" autocomplete="off">
                <input type="hidden" name="search_input_text" id="search_input_text">
                <input type="hidden" name="reg_id" id="reg_id">
              </div>
            </div>

            <div class="col-md-3 col-sm-6">
              <div class="form-group">
                <label class="form-label">To Date</label>
                <input type="text" class="form-control custom_filter" name="to_date" id="to_date" value="" autocomplete="off">
              </div>
            </div>
          </div>
      
          <button type="submit" class="btn btn-sm btn-info" id="export_data" name="export_table">Export To Excel</button>
          <!-- <button type="submit" class="btn btn-sm btn-info" id="export_data_csv" name="export_table_csv">Export To CSV</button> -->
          <button type="submit" class="btn btn-sm btn-info" value="Export To Zip" id="export_zip" name="export_zip">Export Files</button>
              </form>
        
              <div class="table-responsive">
          <table id="listitems22" class="table table-bordered table-striped dataTables-examplexx">
                    <thead>
                      <tr>
                <th class="text-center no-sort" id="srNo" style="padding-right:8px;">Sr.No.</th>
                <th class="text-center">Vendor Name</th>
                <th class="text-center">Pan No.</th>
                <th class="text-center">Registration Date</th> 
                <th class="text-center">Download Excel</th>                 
                <th class="text-center">Download Files</th> 
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
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<style>
  .input_search_data { width:100%; }
  tfoot { display: table-header-group; }
  .pp0 , .pp5, .pp6 , .pp7, .pp8 , .pp9 { display:none; }
  .vbtn { padding: 3px 21px; font-weight: 900; }
  .#listitems2 { width:100%; max-width:100%; }
  .moption { width:100%; }

  .dataTables_wrapper { max-width:96%; margin:20px auto; }
</style>
</style>

<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

<!-- <script src="<?php //echo base_url()?>js/js-paginate.js"></script> -->
<script type="text/javascript">
  $(document).ready(function()
  {
    
    var dataTable = $('#listitems22').DataTable(
    {
      'processing':true,
      'serverSide': true,

      order: [[1, 'desc']],
       
      "ajax": {
        "url": '<?php echo site_url("vendors/Dashboard/get_vendor_registration"); ?>', 
        "type": "POST",
        "data": function (data) 
        {
          var from_date = $('#from_date').val();
          var to_date = $('#to_date').val();

          data.from_date = from_date;
          data.to_date = to_date;
        }
      },
      responsive: true,
      'columns': [
            { data: 'sr' },
            { data: 'full_name' },
            { data: 'pan_no' },
            { data: 'registration_date' },
            { data: 'excel' },            
            { data: 'zip' }
        ],

        "columnDefs": [ 
            {
              "className": "text-center", 
              "targets": "_all",
              //"targets": 1,
              "visible": false,
            }
      ]
    });

    $('.custom_filter').change(function(){
        dataTable.draw();
      });

      $('#from_date,#to_date').datepicker(
      {
          format: "yyyy-mm-dd",
          autoclose: true,
          keyboardNavigation: true, 
          forceParse: false, 
          clearBtn: true         
      })

      $("#search-table").change(function(){
        //alert($(this).val());
        $("#search_input_text").val($(this).val());
      });
      $("#search-table").keyup(function(){
        //alert($(this).val());
        $("#search_input_text").val($(this).val());
      });
  });
  
  function apply_search_val()
  {
    $('#listitems22').DataTable().ajax.reload();
  }
  
  function clear_search_val()
  {
    $("#s_member_no").val("");
    /* $("#s_center_location").val("");
    $("#s_batch_code").val("");
    $("#s_batch_name").val(""); */
    $('#listitems22').DataTable().ajax.reload();  
  }
  function download_excel(val){
    //alert(val);
    if(val != ""){
      $("#reg_id").val(val);
      setTimeout(function(){
        $("#export_data").trigger('click');
        reset_reg_id();
      },1500);
      
    }
  }

  function download_csv(val){
    //alert(val);
    if(val != ""){
      $("#reg_id").val(val);
      setTimeout(function(){
        $("#export_data_csv").trigger('click');
        reset_reg_id();
      },1500);
      
    }
  }

  function download_zip(val){
    //alert(val);
    if(val != ""){
      $("#reg_id").val(val);
      setTimeout(function(){
        $("#export_zip").trigger('click');
        reset_reg_id();
      },1500);
      
    }
  }

   

  function reset_reg_id(){
    setTimeout(function(){ 
        $("#reg_id").val('');
      },1500);
  }

  $(document).ajaxStart(function() { $("#page_loader").css("display", "block"); });
  $(document).ajaxComplete(function() { $("#page_loader").css("display", "none"); });
</script>  
<?php $this->load->view('vendors/admin/includes/footer');?>