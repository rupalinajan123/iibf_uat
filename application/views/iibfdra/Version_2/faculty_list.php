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
  
  .custom_pagination { margin-top: 20px; text-align: center; }
  .custom_pagination a { padding: 2px 10px; margin: 0 0px 2px 0px; }
  .custom_pagination a.active { cursor:auto; }
  
  .search_form_common_all { background: #ededed; padding: 20px 20px 10px 20px; text-align: left; margin-bottom:20px; }
  .search_form_common_all .form-group { display: inline-block; margin: 0 0px 10px; width: 100%; vertical-align: top; }
  .search_form_common_all .form-group .form-label { display: block; font-size: 14px; margin: 0 0 3px 0; line-height: 22px; text-align: left; }
  .search_form_common_all .form-group .form-control { padding: 5px 20px 5px 10px; height: 35px !important; }
  .search_form_common_all .btn { display: inline-block; vertical-align: top; padding: 7px 20px 6px; margin: 0 0px 0 0; min-width: 97px; }
  
  .custom_first_row { padding: 0 !important; }
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
  #page_loader .loading {
  margin: 0 auto;
  position: relative;
  width: 80px;
  height: 80px;
  top: calc( 50% - 40px);
  color: #fff;
  font-size: 30px;
  }
  .custom_disp_label { cursor: auto;  padding: 6px 2px; display: block; margin: 0 auto; min-width: 75px; font-size: 12px; }
</style>
<div id="page_loader" style="display: none;"><div class="loading">Processing...</div></div>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
  
    <h1>Faculty List</h1>
  </section>
  <!-- Main content -->

    
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Faculty List</h3>
              <div class="pull-right">
                <a href="<?php echo base_url('iibfdra/Version_2/faculty/faculty_add'); ?>">
                <button class="btn btn-primary">Add Faculty</button></a>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <?php if($this->session->flashdata('error_message')!=''){?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <?php echo $this->session->flashdata('error_message'); ?>
                </div>
                <?php } if($this->session->flashdata('success_message')!=''){ ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <?php echo $this->session->flashdata('success_message'); ?>
                </div>
                <?php } 
                //echo count($eligible); die; 
                
              /* print_r($eligible); die; */ ?> 
           
              
              <!-- <input id="myInput" type="text" placeholder="Search.." style="float: right"><br><br>-->
              <div class="table-responsive">                        
                <table id="listitems" class="table table-bordered table-striped dataTables-example" width="100%">
                  <thead>
                    <tr>
                      <th>Sr.No.</th>
                      <th>Faculty Id</th>
                      <th>Faculty Code</th>
                      <th>Name</th>
                      <th>Language(s) proficient with</th>
                      <th>DOB</th>
                      <th>Location</th>
                      <th>PAN No.</th>
                      <th>Current Batches</th>
                      <th>Faculty Use Status</th>
                      <th>Status</th>
                      <th>Action</th> 
                    </tr>
                  </thead>
                  
                  <tbody class="no-bd-y" id="ApplicantDataOuter"></tbody>
                </table>
              </div>
              <div id="showMoreBtnOuter"></div>
              <input type="hidden" name="selcted_checkbox_all_hidden" id="selcted_checkbox_all_hidden">
             <!--  <input type="hidden" value="<?=$examcode?>" id="examcode"> -->
              <div>
              </div>              
            </div>
          </div>
        </div>
      </div>
    </section>
  </form>
</div>



<script type="text/javascript">
  $(function () 
  {   
  
    var table = $('#listitems').DataTable(
    {
      order: [[1, 'desc']],
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',

      'ajax': {
         "url": site_url+"iibfdra/Version_2/faculty/faculty_list",
         'data': function(data){
           
         }
      },
      
      'columns': [
         { data: 'sr' },
         { data: 'faculty_id' },
         { data: 'faculty_number' },
         { data: 'faculty_name' }, 
         { data: 'languages' }, 
         { data: 'dob' },
         { data: 'base_location' }, 
         { data: 'pan_no' },
         { data: 'current_batches' },
         { data: 'faculty_use_status' }, 
         { data: 'status' },
         { data: 'action' },
      ],
        "columnDefs": [ 
        {
          "targets": 1,
          "visible": false,
        },
        {
          "targets": 0,
          "orderable": false
        },
        {
          "targets": 8,
          "orderable": false
        },
        {
          "targets": 9,
          "orderable": false
        } 
      ]
    }
    );
    
   
  });
  


  

</script>


</script> 
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

<!-- Data Tables --> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script> 
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script> 
<!-- Data Tables --> 
