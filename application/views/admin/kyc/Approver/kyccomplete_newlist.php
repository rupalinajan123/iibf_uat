<?php $this->load->view('admin/kyc/includes/header'); ?>
<?php $this->load->view('admin/kyc/includes/approver_sidebar'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> KYC complete member list </h1>
  </section>
  <br />
  <div class="col-md-12">
    <?php if ($this->session->flashdata('error') != '') { ?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
        <?php echo $this->session->flashdata('error'); ?>
      </div>
    <?php }
    if ($this->session->flashdata('success') != '') { ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
        <?php echo $this->session->flashdata('success'); ?>
      </div>
    <?php } ?>
  </div>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Member list </h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="listitems" class="table table-bordered table-striped dataTables-example">
              <thead>
                <tr>
                  <th id="">No</th>
                  <th id="">Membership/<br>Registration No</th>
                  <th id="">Candidate Name</th>
                  <th id="">Registration type</th>
                  <th id="">Recommended by</th>
                  <th id="">Recommended date</th>
                  <th id="">Approved date</th>
                  <th id="">Action</th>
                </tr>
              </thead>
              <tbody class="no-bd-y" id="list">

                <?php //if (count($result)) {
                // $row_count = 1;
                // foreach ($result as $row) {
                // $employer = $this->master_model->getRecords("administrators", array('id' => $row['recommended_by']), 'name');
                // print_r($employer[0]['name']);exit;
                ?>
                <!-- <tr>
                      <td><?php //echo $row_count; 
                          ?></td>
                      <td><?php //echo $row['regnumber']; 
                          ?></td>
                      <td><?php //echo $row['namesub'] . " " . $row['firstname'] . " " . $row['middlename'] . " " . $row['lastname']; 
                          ?></td>
                      <td><?php //echo $row['registrationtype']; 
                          ?></td>

                      <td><?php //echo $employer[0]['name']; 
                          ?></td>
                      <td><?php //echo date('d-m-Y ', strtotime($row['recommended_date'])); 
                          ?></td>
                      <td><?php //echo  date('d-m-Y ', strtotime($row['approved_date'])); 
                          ?></td>
                      <td><a href="<?php //echo base_url(); 
                                    ?>admin/kyc/Approver/completed_details/<?php //echo $row['regnumber']; 
                                                                                                    ?>/<?php //echo  $row_count; 
                                                                                                                                      ?>">View Details</a></td>
                    </tr> -->
                <?php //$row_count++;
                //}
                //} 
                ?>
              </tbody>
            </table>
            <center>
            </center>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
  </section>
</div>
<!-- Data Tables -->
<link href="<?php echo base_url(); ?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

<script type="application/javascript">
  $(document).ready(function() {
    $('#listitems').DataTable({
      'processing': true,
      'serverSide': true,
      'searching': false,
      'serverMethod': 'post',
      'ajax': {
        url: "<?php echo base_url(); ?>admin/kyc/Approver/get_kyc_complete_list",
      },
      'columns': [{
          data: 'no'
        },
        {
          data: 'regnumber'
        },
        {
          data: 'name'
        },
        {
          data: 'registration_type'
        },
        {
          data: 'recommended_by'
        },
        {
          data: 'recommended_date'
        },
        {
          data: 'approved_date'
        },
        {
          data: 'action'
        },
      ]
    });
  });
</script>
<script type="application/javascript">
  // $(function() {
  // $("#listitems").DataTable();
  /*	
  	var base_url = '<?php echo base_url(); ?>';
  	var listing_url = base_url+'admin/kyc/Approver/kyccomplete_newlist';
  	// Pagination function call
  	paginate(listing_url,'','','');
  	$("#base_url_val").val(listing_url);
  */
  // });
</script>

<?php $this->load->view('admin/kyc/includes/footer'); ?>