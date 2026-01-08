<?php $this->load->view('iibfdra/Version_2/admin/includes/header');?>
<?php $this->load->view('iibfdra/Version_2/admin/includes/sidebar');?>
<style>
.custom_disp_label { cursor: auto;  padding: 6px 2px; display: block; margin: 0 auto; min-width: 75px; font-size: 12px; }
</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Faculty List
      </h1>
      <?php //echo $breadcrumb; ?>
      <?php /*<div class="pull-right">
        <a href="<?php echo base_url('iibfdra/Version_2/admin/faculty_master/faculty_add'); ?>">
        <button class="btn btn-primary">Add Faculty</button></a>
      </div> */ ?>
    </section>
  <div class="col-md-12">
    <br />    
    <?php 
   if($this->session->flashdata('success')!=''){ ?>
              <div class="alert alert-success alert-dismissible" id="success_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('success'); ?> </div>
     <?php }?>    
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
                  <th>Sr. No.</th>
                  <th>Faculty Id</th>
                  <th>Faculty Code</th>
                  <th>Name</th>
                  <th>DOB</th>
                  <th>Location</th>
                  <th>PAN No.</th>
                  <th>Current Batches</th>
                  <th>Agency</th>
                  <th>Status</th>
                  <th>Action</th> 
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
  </div>
  
<!-- Data Tables -->
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<style>
.active_batch{
color:#00a65a;  
font-weight:600;
}

.deactive_batch{
color:#930; 
font-weight:600;
}
.input_search_data{
 width:100%;  
}
tfoot {
    display: table-header-group;
}
.vbtn{
padding: 3px 4px;
font-weight: 600;
}
</style>

<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>



<script type="text/javascript">
  var table;  
  var site_url = '<?php echo base_url(); ?>';
  $(function () 
  {   
    var table = $('#listitems').DataTable(
    {
      order: [[1, 'desc']],
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',

      'ajax': {
         "url": site_url+"iibfdra/Version_2/admin/faculty_master/faculty_list",
         'data': function(data){
           
         }
      },
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
      ],
      'columns': [
         { data: 'sr' }, 
         { data: 'faculty_id' },
         { data: 'faculty_code'},
         { data: 'faculty_name' }, 
         { data: 'dob' },
         { data: 'base_location' }, 
         { data: 'pan_no' },
         { data: 'current_batches' },
         { data: 'agency' },
         { data: 'status' }, 
         { data: 'action' },
      ]
    }
    );
    
   
  });
  
</script>
 
<?php $this->load->view('iibfdra/Version_2/admin/includes/footer');?>