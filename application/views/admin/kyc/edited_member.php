<?php $this->load->view('admin/kyc/includes/header');?>
<?php $this->load->view('admin/kyc/includes/sidebar');?>
<!--fancybox--Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      KYC edited Member List 
    </h1>
    
  </section>
  <br />
	<div class="col-md-12">
    <?php if($this->session->flashdata('error')!=''){?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
        <?php echo $this->session->flashdata('error'); ?>
      </div>
      <?php } if($this->session->flashdata('success')!=''){ ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
        <?php echo $this->session->flashdata('success'); ?>
      </div>
    <?php } ?>
  </div>

  <?php // search functionality by pooja mane 2024-11-06  ?>
  <div class="collapsee" id="collapseExample">
    
    <form class="form-control" name="searchExamDetails" id="searchExamDetails" action="
      <?php echo base_url('admin/kyc/Kyc/next_edited_list'); ?>" method="post">
      <div class="row">
      <div class="col-sm-3">
      <div class="form-group">
      <label>Search By</label>
      <select class="custom_filter form-control" name="searchBy" id="searchBy" required>
      <option value="">Select</option>
      <option value="01" selected="">Registration No</option>
      </select>
      </div>
      </div>
      <div class="col-md-3">
      <div class="form-group">
      <label>Search Value</label>
      <input type="text" class="form-control" id="SearchVal" name="SearchVal" placeholder="" value="<?php if(set_value('SearchVal')) { echo set_value('SearchVal'); }?>" required>
      </div>
      </div>
      
      <div class="col-md-1">
      <div class="form-group">
      <label>Search</label>
      <input id="search" onclick="hideAlert()" type="submit" class="mb-2 float-right btn btn-primary" name="btnSearch" id="btnSearch" value="Search" >
      </div>
      </div>
      
      <div class="col-md-1">
      <div class="form-group">
      <label>Reset</label>
      <input type="button" class="mb-2 float-right btn btn-primary" name="reset" id="reset" value="Reset" onclick="location.href='<?php echo base_url('admin/kyc/Kyc/edited_allocation_type'); ?>';">
      </div>
      </div>
      
      </div>     
    </form>
  </div>
  <?php // search functionality end pooja mane 2024-11-06 ?>

  <?php // <!-- Main content --> ?> 
        <section class="content" style="min-height : 500px;">
          <div class="row">
              <div class="col-xs-12">
                  <div class="box">
                      <div class="box-header">
                          <h3 class="box-title">Allocated Records</h3>
                      </div>
                      <!-- /.box-header -->
                      <div class="box-body">
                          <?php if (count($result) > 0) { ?>
                              <div class="table-responsive">
                                  <table id="listitems" class="table table-bordered table-striped dataTables-example">
                                      <thead>
                                          <tr>
                                              <th>Sr.No</th>
                                              <th>Membership No</th>
                                              <th>Candidate Name</th>
                                              <th>D.O.B</th>
                                              <th>Email</th>
                                              <th>Registration Type</th>
                                              <th>Action</th>
                                          </tr>
                                      </thead>
                                      <tbody class="no-bd-y" id="list">
                                          <?php 
                                          $row_count = 1;
                                          foreach ($result as $row) { ?>
                                              <tr>
                                                  <td><?php echo $row_count; ?>.</td>
                                                  <td><?php echo $row['regnumber']; ?></td>
                                                  <td><?php echo $row['firstname'] . " " . $row['middlename'] . " " . $row['lastname']; ?></td>
                                                  <td><?php echo date('d-m-Y', strtotime($row['dateofbirth'])); ?></td>
                                                  <td><?php echo $row['email']; ?></td>
                                                  <td><?php echo $row['registrationtype']; ?></td>
                                                  <td><a href="<?php echo base_url(); ?>admin/kyc/Kyc/edited_member/<?php echo $row['regnumber']; ?>">Recommend</a></td>
                                              </tr>
                                          <?php 
                                              $row_count++;
                                          } ?>
                                      </tbody>
                                  </table>
                              </div>
                          <?php } else { ?>
                              <center>
                                  <?php 
                                  if (isset($emptylistmsg)) {
                                      echo $emptylistmsg;
                                  } ?>
                              </center>
                          <?php } ?>
                      </div>
                      <!-- /.box-body -->
                  </div>
                  <!-- /.box -->
              </div>
              <!-- /.col -->
          </div>
          <!-- /.row -->
      </section>
</div>
      <!-- ###### Data Tables ##### -->
      
      <link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
      <link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
      <link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
      
      <!-- ###### Data Tables -->
      <script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
      <script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
      <script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
      <script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
      
      <script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
      <link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">
      
      <script src="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.js"></script>
      <link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.css">
      
      <script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
      <script src="<?php echo base_url()?>js/validation.js"></script>
      <script type="text/javascript">
        $('#search').parsley('validate');
      </script>
      
      <!--<script src="<?php echo base_url()?>js/js-paginate.js"></script>-->
      <script type="application/javascript">
        $(document).ready(function() 
        {
          $('#from_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
            $('#to_date').datepicker('setStartDate', new Date($(this).val()));
          }); 
          
          $('#to_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
            $('#from_date').datepicker('setEndDate', new Date($(this).val()));
          });
        });
        
        $(function () {
          $("#listitems").DataTable();
          var base_url = '<?php echo base_url(); ?>';
          var listing_url = base_url+'admin/kyc/Kyc/edited_list/';
          
          // Pagination function call
          paginate(listing_url,'','','');
          $("#base_url_val").val(listing_url);
        });
      </script>
      
    <?php $this->load->view('admin/kyc/includes/footer');?>    