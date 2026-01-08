  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      <?php echo $title; ?>
      </h1>
      <?php echo $breadcrumb; ?>
    </section>
	<div class="col-md-12">
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
    <?php } ?>
    </div>
    <!-- Main content -->
    <section class="content">
    
     
      <div class="row">
        <div class="col-xs-12">
       
        <input type="hidden" name="search_on_fields" id="search_on_fields" value="" />
        <input type="hidden" name="base_url_val" id="base_url_val" value="" />

          <div class="box">
          	<div class="box-header with-border">
              <h3 class="box-title">Center wise <?php echo $title;?></h3>
              <div class="pull-right">
               <!--<a  data-toggle="tooltip" class="btn btn-warning" href="<?php echo base_url('iibfdra/Admitcard/download_pdfs'); ?>"> Download All </a>-->
             </div> 
            </div>
            <!-- /.box-header -->
            <div class="box-body">
      <?php if(!empty($result)){
        $cnt = 1;
        ?>

              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sr No.</th>
                  <th>Center Name</th>
                  <th>Application</th> 
                  <th>No of Candidates</th> 
                  <th>Exam Period</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
        <?php foreach($result as $row){ 
         // print_r($row);?>
                <tr>
                  <td><?php echo $cnt  ?></td>
                  <td><a href="<?php echo base_url()?>iibfdra/Result/result_listing/<?php echo base64_encode($row['exam_code']);?>/<?php echo base64_encode($row['center_code']);?>"><?php echo $row['center_name'];?></a></td>
                  <td><?php echo $application_name; ?></td>
                  <td><?php echo $row['mem_count'] ;?></td>
                  <td><?php echo $row['exam_period'] ; ?></td> 
                  <td><a href="<?php echo base_url()?>iibfdra/Result/download_pdf/<?php echo base64_encode($row['exam_code']);?>/<?php echo base64_encode($row['center_code']);?>">Download Result</a></td>
                </tr>

          <?php  $cnt++;
        } ?>

                 </tbody>
               
              </table>
      <?php } else { echo 'Records not found'; } ?>
       </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      
    </section>
   
  </div>
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
 
 });
</script>